/**
 * AquaLuxe Module Loader
 *
 * Dynamic module loading system for the AquaLuxe theme
 * Handles lazy loading, dependencies, and module lifecycle
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

/**
 * Module Loader Class
 * 
 * Manages dynamic loading and initialization of theme modules
 * Supports dependency resolution and lazy loading
 */
export class ModuleLoader {
    /**
     * Constructor
     * 
     * @param {Object} config Module loader configuration
     * @param {EventBus} eventBus Event bus instance
     */
    constructor(config = {}, eventBus = null) {
        this.config = {
            debug: false,
            basePath: '/wp-content/themes/aqualuxe/assets/dist/js/modules/',
            timeout: 30000, // 30 seconds
            retryAttempts: 3,
            retryDelay: 1000,
            ...config
        };
        
        this.eventBus = eventBus;
        this.modules = new Map();
        this.loading = new Map();
        this.failed = new Set();
        this.dependencies = new Map();
        this.loadQueue = [];
        this.isProcessingQueue = false;
    }

    /**
     * Register a module
     * 
     * @param {string} name Module name
     * @param {Object} options Module options
     */
    register(name, options = {}) {
        const moduleConfig = {
            name,
            path: options.path || `${name}.js`,
            dependencies: options.dependencies || [],
            lazy: options.lazy !== false,
            priority: options.priority || 0,
            condition: options.condition || null,
            init: options.init || null,
            ...options
        };
        
        this.modules.set(name, moduleConfig);
        
        if (moduleConfig.dependencies.length > 0) {
            this.dependencies.set(name, moduleConfig.dependencies);
        }
        
        if (this.config.debug) {
            console.log(`📦 Module registered: ${name}`);
        }
        
        // Auto-load if not lazy
        if (!moduleConfig.lazy) {
            this.load(name);
        }
    }

    /**
     * Load a module
     * 
     * @param {string} name Module name
     * @param {Object} options Load options
     * @return {Promise} Load promise
     */
    async load(name, options = {}) {
        if (!this.modules.has(name)) {
            throw new Error(`Module not registered: ${name}`);
        }
        
        const module = this.modules.get(name);
        
        // Check if already loaded
        if (module.loaded) {
            return module.instance;
        }
        
        // Check if currently loading
        if (this.loading.has(name)) {
            return this.loading.get(name);
        }
        
        // Check if failed and max retries reached
        if (this.failed.has(name)) {
            throw new Error(`Module failed to load: ${name}`);
        }
        
        // Check condition
        if (module.condition && !module.condition()) {
            if (this.config.debug) {
                console.log(`📦 Module condition not met: ${name}`);
            }
            return null;
        }
        
        // Load dependencies first
        if (module.dependencies.length > 0) {
            await this.loadDependencies(name);
        }
        
        // Start loading
        const loadPromise = this.loadModule(name, options);
        this.loading.set(name, loadPromise);
        
        try {
            const instance = await loadPromise;
            this.loading.delete(name);
            
            // Mark as loaded
            module.loaded = true;
            module.instance = instance;
            module.loadedAt = Date.now();
            
            // Initialize module if init function provided
            if (module.init && typeof module.init === 'function') {
                await module.init(instance);
            }
            
            // Emit load event
            if (this.eventBus) {
                this.eventBus.emit('module:loaded', {
                    name,
                    instance,
                    module
                });
            }
            
            if (this.config.debug) {
                console.log(`📦 Module loaded: ${name}`);
            }
            
            return instance;
        } catch (error) {
            this.loading.delete(name);
            
            // Handle retry logic
            const retryCount = module.retryCount || 0;
            if (retryCount < this.config.retryAttempts) {
                module.retryCount = retryCount + 1;
                
                if (this.config.debug) {
                    console.log(`📦 Retrying module load (${retryCount + 1}/${this.config.retryAttempts}): ${name}`);
                }
                
                // Wait before retry
                await new Promise(resolve => setTimeout(resolve, this.config.retryDelay * retryCount));
                
                return this.load(name, options);
            } else {
                this.failed.add(name);
                
                // Emit error event
                if (this.eventBus) {
                    this.eventBus.emit('module:error', {
                        name,
                        error,
                        module
                    });
                }
                
                console.error(`❌ Module failed to load: ${name}`, error);
                throw error;
            }
        }
    }

    /**
     * Load module dependencies
     * 
     * @param {string} name Module name
     * @return {Promise} Dependencies load promise
     */
    async loadDependencies(name) {
        const module = this.modules.get(name);
        const dependencies = module.dependencies;
        
        if (this.config.debug) {
            console.log(`📦 Loading dependencies for ${name}:`, dependencies);
        }
        
        // Check for circular dependencies
        this.checkCircularDependencies(name, dependencies);
        
        // Load all dependencies
        const dependencyPromises = dependencies.map(dep => {
            if (!this.modules.has(dep)) {
                throw new Error(`Dependency not registered: ${dep} (required by ${name})`);
            }
            return this.load(dep);
        });
        
        await Promise.all(dependencyPromises);
    }

    /**
     * Check for circular dependencies
     * 
     * @param {string} name Module name
     * @param {Array} dependencies Dependency list
     * @param {Set} visited Visited modules
     */
    checkCircularDependencies(name, dependencies, visited = new Set()) {
        if (visited.has(name)) {
            throw new Error(`Circular dependency detected: ${Array.from(visited).join(' -> ')} -> ${name}`);
        }
        
        visited.add(name);
        
        for (const dep of dependencies) {
            if (this.dependencies.has(dep)) {
                this.checkCircularDependencies(dep, this.dependencies.get(dep), new Set(visited));
            }
        }
    }

    /**
     * Actually load the module
     * 
     * @param {string} name Module name
     * @param {Object} options Load options
     * @return {Promise} Module instance
     */
    async loadModule(name, options = {}) {
        const module = this.modules.get(name);
        const fullPath = module.path.startsWith('http') ? module.path : this.config.basePath + module.path;
        
        if (this.config.debug) {
            console.log(`📦 Loading module from: ${fullPath}`);
        }
        
        // Create timeout promise
        const timeoutPromise = new Promise((_, reject) => {
            setTimeout(() => {
                reject(new Error(`Module load timeout: ${name}`));
            }, this.config.timeout);
        });
        
        // Dynamic import with timeout
        const loadPromise = import(fullPath);
        
        const moduleExports = await Promise.race([loadPromise, timeoutPromise]);
        
        // Get the default export or named export
        let ModuleClass = moduleExports.default;
        
        if (!ModuleClass && module.export) {
            ModuleClass = moduleExports[module.export];
        }
        
        if (!ModuleClass) {
            throw new Error(`No valid export found in module: ${name}`);
        }
        
        // Create instance if it's a class
        let instance;
        if (typeof ModuleClass === 'function' && ModuleClass.prototype) {
            instance = new ModuleClass(module.config || {}, this.eventBus);
        } else {
            instance = ModuleClass;
        }
        
        return instance;
    }

    /**
     * Unload a module
     * 
     * @param {string} name Module name
     * @return {boolean} True if unloaded
     */
    unload(name) {
        if (!this.modules.has(name)) {
            return false;
        }
        
        const module = this.modules.get(name);
        
        if (!module.loaded) {
            return false;
        }
        
        // Call cleanup if available
        if (module.instance && typeof module.instance.cleanup === 'function') {
            try {
                module.instance.cleanup();
            } catch (error) {
                console.error(`❌ Error during module cleanup: ${name}`, error);
            }
        }
        
        // Reset module state
        module.loaded = false;
        module.instance = null;
        module.loadedAt = null;
        module.retryCount = 0;
        
        // Remove from failed set
        this.failed.delete(name);
        
        // Emit unload event
        if (this.eventBus) {
            this.eventBus.emit('module:unloaded', {
                name,
                module
            });
        }
        
        if (this.config.debug) {
            console.log(`📦 Module unloaded: ${name}`);
        }
        
        return true;
    }

    /**
     * Get module instance
     * 
     * @param {string} name Module name
     * @return {Object|null} Module instance
     */
    get(name) {
        const module = this.modules.get(name);
        return module && module.loaded ? module.instance : null;
    }

    /**
     * Check if module is loaded
     * 
     * @param {string} name Module name
     * @return {boolean} True if loaded
     */
    isLoaded(name) {
        const module = this.modules.get(name);
        return module ? module.loaded : false;
    }

    /**
     * Check if module is loading
     * 
     * @param {string} name Module name
     * @return {boolean} True if loading
     */
    isLoading(name) {
        return this.loading.has(name);
    }

    /**
     * Get all loaded modules
     * 
     * @return {Array} Array of loaded module names
     */
    getLoaded() {
        const loaded = [];
        
        for (const [name, module] of this.modules) {
            if (module.loaded) {
                loaded.push(name);
            }
        }
        
        return loaded;
    }

    /**
     * Get all registered modules
     * 
     * @return {Array} Array of all module names
     */
    getRegistered() {
        return Array.from(this.modules.keys());
    }

    /**
     * Load multiple modules
     * 
     * @param {Array} names Module names
     * @param {Object} options Load options
     * @return {Promise} Load promise
     */
    async loadMultiple(names, options = {}) {
        const loadPromises = names.map(name => this.load(name, options));
        
        if (options.parallel !== false) {
            return Promise.all(loadPromises);
        } else {
            // Sequential loading
            const results = [];
            for (const promise of loadPromises) {
                results.push(await promise);
            }
            return results;
        }
    }

    /**
     * Preload modules based on conditions
     * 
     * @param {Object} conditions Preload conditions
     */
    async preload(conditions = {}) {
        const toLoad = [];
        
        for (const [name, module] of this.modules) {
            if (module.loaded || module.lazy === false) {
                continue;
            }
            
            // Check preload conditions
            let shouldPreload = false;
            
            if (conditions.priority && module.priority >= conditions.priority) {
                shouldPreload = true;
            }
            
            if (conditions.viewport && this.isInViewport(module.selector)) {
                shouldPreload = true;
            }
            
            if (conditions.interaction && this.hasInteractionTrigger(module.triggers)) {
                shouldPreload = true;
            }
            
            if (shouldPreload) {
                toLoad.push(name);
            }
        }
        
        if (toLoad.length > 0) {
            if (this.config.debug) {
                console.log(`📦 Preloading modules:`, toLoad);
            }
            
            await this.loadMultiple(toLoad, { parallel: true });
        }
    }

    /**
     * Check if element is in viewport
     * 
     * @param {string} selector Element selector
     * @return {boolean} True if in viewport
     */
    isInViewport(selector) {
        if (!selector) return false;
        
        const element = document.querySelector(selector);
        if (!element) return false;
        
        const rect = element.getBoundingClientRect();
        return rect.top < window.innerHeight && rect.bottom > 0;
    }

    /**
     * Check if interaction triggers are present
     * 
     * @param {Array} triggers Trigger selectors
     * @return {boolean} True if triggers found
     */
    hasInteractionTrigger(triggers) {
        if (!triggers || !Array.isArray(triggers)) return false;
        
        return triggers.some(trigger => document.querySelector(trigger));
    }

    /**
     * Get debug information
     * 
     * @return {Object} Debug information
     */
    getDebugInfo() {
        return {
            config: this.config,
            registered: this.getRegistered().length,
            loaded: this.getLoaded().length,
            loading: Array.from(this.loading.keys()),
            failed: Array.from(this.failed),
            dependencies: Object.fromEntries(this.dependencies)
        };
    }
}
