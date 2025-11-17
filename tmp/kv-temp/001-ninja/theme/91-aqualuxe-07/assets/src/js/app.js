/**
 * AquaLuxe Theme - Main Application
 *
 * Modern, modular WordPress theme with SOLID principles
 * Comprehensive production-ready architecture
 *
 * @package AquaLuxe
 * @version 1.2.0
 * @since 1.0.0
 */

import { ThemeCore } from './core/theme-core.js';
import { EventBus } from './core/event-bus.js';
import { ModuleLoader } from './core/module-loader.js';
import { PerformanceMonitor } from './core/performance-monitor.js';

/**
 * AquaLuxe Application Class
 * 
 * Main application controller that orchestrates all theme functionality
 * Implements dependency injection and modular architecture
 */
class AquaLuxeApp {
    /**
     * Constructor
     * 
     * @param {Object} config Application configuration
     */
    constructor(config = {}) {
        this.config = {
            debug: window.aqualuxeConfig?.debug || false,
            version: '1.2.0',
            basePath: window.aqualuxeConfig?.basePath || '/wp-content/themes/aqualuxe/',
            ...config
        };
        
        this.isInitialized = false;
        this.isStarted = false;
        this.startTime = performance.now();
        
        // Core services
        this.eventBus = null;
        this.themeCore = null;
        this.moduleLoader = null;
        this.performanceMonitor = null;
        
        // Module instances
        this.modules = new Map();
        
        // Initialize immediately
        this.init();
    }

    /**
     * Initialize application
     * 
     * @return {Promise<void>} Initialization promise
     */
    async init() {
        if (this.isInitialized) {
            return;
        }
        
        try {
            // Performance monitoring
            this.performanceMonitor = new PerformanceMonitor({
                debug: this.config.debug,
                trackResources: true,
                trackUserTiming: true,
                trackLCP: true,
                trackFID: true,
                trackCLS: true
            });
            
            this.performanceMonitor.mark('app:init:start');
            
            // Event system
            this.eventBus = new EventBus({
                debug: this.config.debug,
                namespace: 'aqualuxe',
                maxListeners: 100
            });
            
            // Theme core
            this.themeCore = new ThemeCore({
                debug: this.config.debug,
                version: this.config.version,
                colors: window.aqualuxeConfig?.colors || {}
            });
            
            await this.themeCore.init();
            
            // Module loader
            this.moduleLoader = new ModuleLoader({
                debug: this.config.debug,
                basePath: this.config.basePath + 'assets/dist/js/modules/',
                timeout: 30000,
                retryAttempts: 3
            }, this.eventBus);
            
            // Register core modules
            this.registerCoreModules();
            
            // Set up event listeners
            this.setupEventListeners();
            
            // Mark initialization complete
            this.performanceMonitor.mark('app:init:end');
            this.performanceMonitor.measure('app:init', 'app:init:start', 'app:init:end');
            
            this.isInitialized = true;
            
            // Emit initialization event
            this.eventBus.emit('app:initialized', {
                config: this.config,
                initTime: performance.now() - this.startTime
            });
            
            if (this.config.debug) {
                console.log('🚀 AquaLuxe App initialized');
            }
            
        } catch (error) {
            console.error('❌ Failed to initialize AquaLuxe App:', error);
            throw error;
        }
    }

    /**
     * Start application
     * 
     * @return {Promise<void>} Start promise
     */
    async start() {
        if (!this.isInitialized) {
            await this.init();
        }
        
        if (this.isStarted) {
            return;
        }
        
        try {
            this.performanceMonitor.mark('app:start:begin');
            
            // Load critical modules first
            await this.loadCriticalModules();
            
            // Set up progressive enhancement
            this.setupProgressiveEnhancement();
            
            // Set up lazy loading
            this.setupLazyLoading();
            
            // Start performance monitoring
            this.startPerformanceTracking();
            
            this.performanceMonitor.mark('app:start:end');
            this.performanceMonitor.measure('app:start', 'app:start:begin', 'app:start:end');
            
            this.isStarted = true;
            
            // Emit start event
            this.eventBus.emit('app:started', {
                startTime: performance.now() - this.startTime
            });
            
            if (this.config.debug) {
                console.log('🎯 AquaLuxe App started');
                this.logDebugInfo();
            }
            
        } catch (error) {
            console.error('❌ Failed to start AquaLuxe App:', error);
        }
    }

    /**
     * Register core modules
     */
    registerCoreModules() {
        // Navigation module
        this.moduleLoader.register('navigation', {
            path: 'navigation.js',
            lazy: false,
            priority: 10,
            dependencies: []
        });
        
        // Responsive utilities
        this.moduleLoader.register('responsive', {
            path: 'responsive.js',
            lazy: false,
            priority: 9,
            dependencies: []
        });
        
        // Accessibility features
        this.moduleLoader.register('accessibility', {
            path: 'accessibility.js',
            lazy: false,
            priority: 8,
            dependencies: []
        });
        
        // Dark mode
        this.moduleLoader.register('dark-mode', {
            path: 'dark-mode.js',
            lazy: true,
            priority: 5,
            condition: () => document.querySelector('[data-dark-mode]')
        });
        
        // Animations (GSAP)
        this.moduleLoader.register('animations', {
            path: 'animations/index.js',
            lazy: true,
            priority: 3,
            dependencies: ['intersection-observer']
        });
        
        // Three.js scenes
        this.moduleLoader.register('three-scenes', {
            path: 'three/index.js',
            lazy: true,
            priority: 2,
            condition: () => document.querySelector('[data-three-scene]')
        });
        
        // WooCommerce integration
        this.moduleLoader.register('woocommerce', {
            path: 'woocommerce/index.js',
            lazy: true,
            priority: 6,
            condition: () => document.body.classList.contains('woocommerce')
        });
        
        // Search functionality
        this.moduleLoader.register('search', {
            path: 'search.js',
            lazy: true,
            priority: 4,
            condition: () => document.querySelector('.search-form, .wp-block-search')
        });
        
        // Contact forms
        this.moduleLoader.register('forms', {
            path: 'forms.js',
            lazy: true,
            priority: 4,
            condition: () => document.querySelector('form')
        });
        
        // Intersection Observer polyfill/utility
        this.moduleLoader.register('intersection-observer', {
            path: 'utils/intersection-observer.js',
            lazy: true,
            priority: 7
        });
        
        // Smooth scroll
        this.moduleLoader.register('smooth-scroll', {
            path: 'smooth-scroll.js',
            lazy: true,
            priority: 3,
            condition: () => document.querySelector('[data-smooth-scroll]')
        });
        
        // Image optimization
        this.moduleLoader.register('image-optimization', {
            path: 'image-optimization.js',
            lazy: true,
            priority: 4,
            dependencies: ['intersection-observer']
        });
    }

    /**
     * Load critical modules
     * 
     * @return {Promise<void>} Load promise
     */
    async loadCriticalModules() {
        const criticalModules = [
            'navigation',
            'responsive',
            'accessibility'
        ];
        
        try {
            await this.moduleLoader.loadMultiple(criticalModules, { parallel: true });
            
            // Store module instances
            criticalModules.forEach(name => {
                const instance = this.moduleLoader.get(name);
                if (instance) {
                    this.modules.set(name, instance);
                }
            });
            
        } catch (error) {
            console.error('❌ Failed to load critical modules:', error);
        }
    }

    /**
     * Set up progressive enhancement
     */
    setupProgressiveEnhancement() {
        // Load modules based on viewport and interaction
        this.setupViewportLoading();
        this.setupInteractionLoading();
        
        // Set up conditional loading
        this.setupConditionalLoading();
    }

    /**
     * Set up viewport-based loading
     */
    setupViewportLoading() {
        if (!('IntersectionObserver' in window)) {
            return;
        }
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const modules = element.dataset.loadModules;
                    
                    if (modules) {
                        const moduleList = modules.split(',').map(m => m.trim());
                        this.moduleLoader.loadMultiple(moduleList);
                        observer.unobserve(element);
                    }
                }
            });
        }, {
            rootMargin: '50px'
        });
        
        // Observe elements with data-load-modules attribute
        document.querySelectorAll('[data-load-modules]').forEach(element => {
            observer.observe(element);
        });
    }

    /**
     * Set up interaction-based loading
     */
    setupInteractionLoading() {
        const interactionEvents = ['click', 'touchstart', 'mouseenter', 'focus'];
        
        interactionEvents.forEach(eventType => {
            document.addEventListener(eventType, (event) => {
                const element = event.target.closest('[data-interaction-modules]');
                
                if (element) {
                    const modules = element.dataset.interactionModules;
                    if (modules) {
                        const moduleList = modules.split(',').map(m => m.trim());
                        this.moduleLoader.loadMultiple(moduleList);
                        
                        // Remove attribute to prevent multiple loads
                        element.removeAttribute('data-interaction-modules');
                    }
                }
            }, { passive: true, once: false });
        });
    }

    /**
     * Set up conditional loading
     */
    setupConditionalLoading() {
        // Load modules based on media queries
        const mediaQueries = {
            'min-width: 768px': ['desktop-navigation', 'advanced-animations'],
            'prefers-reduced-motion: no-preference': ['animations', 'smooth-scroll'],
            'hover: hover': ['hover-effects']
        };
        
        Object.entries(mediaQueries).forEach(([query, modules]) => {
            const mediaQuery = window.matchMedia(`(${query})`);
            
            const handleChange = (e) => {
                if (e.matches) {
                    modules.forEach(module => {
                        if (this.moduleLoader.getRegistered().includes(module)) {
                            this.moduleLoader.load(module).catch(() => {
                                // Module might not exist, ignore
                            });
                        }
                    });
                }
            };
            
            handleChange(mediaQuery);
            mediaQuery.addListener(handleChange);
        });
    }

    /**
     * Set up lazy loading
     */
    setupLazyLoading() {
        // Preload high-priority modules
        setTimeout(() => {
            this.moduleLoader.preload({
                priority: 5,
                viewport: true,
                interaction: true
            });
        }, 1000);
        
        // Load remaining modules on idle
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                this.loadRemainingModules();
            }, { timeout: 5000 });
        } else {
            setTimeout(() => {
                this.loadRemainingModules();
            }, 3000);
        }
    }

    /**
     * Load remaining modules
     */
    async loadRemainingModules() {
        const registered = this.moduleLoader.getRegistered();
        const loaded = this.moduleLoader.getLoaded();
        const remaining = registered.filter(name => !loaded.includes(name));
        
        if (remaining.length > 0) {
            if (this.config.debug) {
                console.log('📦 Loading remaining modules:', remaining);
            }
            
            // Load modules with delay to avoid blocking
            for (const module of remaining) {
                try {
                    await this.moduleLoader.load(module);
                    
                    // Small delay between loads
                    await new Promise(resolve => setTimeout(resolve, 100));
                } catch (error) {
                    // Continue with other modules
                    if (this.config.debug) {
                        console.warn(`⚠️ Failed to load module: ${module}`, error);
                    }
                }
            }
        }
    }

    /**
     * Start performance tracking
     */
    startPerformanceTracking() {
        // Track long tasks
        if ('PerformanceObserver' in window) {
            try {
                const observer = new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    entries.forEach(entry => {
                        if (entry.duration > 50) {
                            console.warn(`⚠️ Long task detected: ${entry.duration}ms`);
                        }
                    });
                });
                
                observer.observe({ entryTypes: ['longtask'] });
            } catch (error) {
                // Long task API not supported
            }
        }
        
        // Track memory usage if available
        if (performance.memory) {
            setInterval(() => {
                const memory = performance.memory;
                const usage = {
                    used: Math.round(memory.usedJSHeapSize / 1048576), // MB
                    total: Math.round(memory.totalJSHeapSize / 1048576), // MB
                    limit: Math.round(memory.jsHeapSizeLimit / 1048576) // MB
                };
                
                if (usage.used > usage.limit * 0.9) {
                    console.warn('⚠️ High memory usage detected:', usage);
                }
            }, 30000); // Check every 30 seconds
        }
    }

    /**
     * Set up event listeners
     */
    setupEventListeners() {
        // Listen for module events
        this.eventBus.on('module:loaded', (event) => {
            this.modules.set(event.data.name, event.data.instance);
            
            if (this.config.debug) {
                console.log(`📦 Module loaded: ${event.data.name}`);
            }
        });
        
        this.eventBus.on('module:error', (event) => {
            console.error(`❌ Module error: ${event.data.name}`, event.data.error);
        });
        
        // Listen for performance events
        this.eventBus.on('performance:threshold-exceeded', (event) => {
            console.warn(`⚠️ Performance threshold exceeded:`, event.data);
        });
        
        // Listen for accessibility events
        this.eventBus.on('accessibility:violation', (event) => {
            console.warn(`♿ Accessibility violation:`, event.data);
        });
        
        // Handle visibility change
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.eventBus.emit('app:hidden');
            } else {
                this.eventBus.emit('app:visible');
            }
        });
        
        // Handle online/offline
        window.addEventListener('online', () => {
            this.eventBus.emit('app:online');
        });
        
        window.addEventListener('offline', () => {
            this.eventBus.emit('app:offline');
        });
        
        // Handle resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.eventBus.emit('app:resize', {
                    width: window.innerWidth,
                    height: window.innerHeight
                });
            }, 250);
        });
    }

    /**
     * Get module instance
     * 
     * @param {string} name Module name
     * @return {Object|null} Module instance
     */
    getModule(name) {
        return this.modules.get(name) || this.moduleLoader.get(name);
    }

    /**
     * Check if module is loaded
     * 
     * @param {string} name Module name
     * @return {boolean} True if loaded
     */
    hasModule(name) {
        return this.modules.has(name) || this.moduleLoader.isLoaded(name);
    }

    /**
     * Load module on demand
     * 
     * @param {string} name Module name
     * @return {Promise} Load promise
     */
    async loadModule(name) {
        return this.moduleLoader.load(name);
    }

    /**
     * Get application status
     * 
     * @return {Object} Application status
     */
    getStatus() {
        return {
            initialized: this.isInitialized,
            started: this.isStarted,
            uptime: performance.now() - this.startTime,
            modules: {
                registered: this.moduleLoader.getRegistered().length,
                loaded: this.moduleLoader.getLoaded().length,
                loading: this.moduleLoader.getDebugInfo().loading.length,
                failed: this.moduleLoader.getDebugInfo().failed.length
            },
            performance: this.performanceMonitor.generateReport(),
            eventBus: this.eventBus.getDebugInfo()
        };
    }

    /**
     * Log debug information
     */
    logDebugInfo() {
        const status = this.getStatus();
        
        console.group('🚀 AquaLuxe App Status');
        console.log('Uptime:', `${Math.round(status.uptime)}ms`);
        console.log('Modules:', status.modules);
        console.log('Performance:', status.performance.coreWebVitals);
        console.log('Event Bus:', status.eventBus);
        console.groupEnd();
    }

    /**
     * Cleanup application
     */
    cleanup() {
        // Cleanup modules
        this.modules.forEach(module => {
            if (module && typeof module.cleanup === 'function') {
                module.cleanup();
            }
        });
        
        // Cleanup core services
        if (this.performanceMonitor) {
            this.performanceMonitor.cleanup();
        }
        
        if (this.eventBus) {
            this.eventBus.removeAllListeners();
        }
        
        // Clear references
        this.modules.clear();
        
        if (this.config.debug) {
            console.log('🧹 AquaLuxe App cleaned up');
        }
    }
}

// Initialize and start the application
let app;

const initApp = async () => {
    try {
        // Get configuration from WordPress
        const config = window.aqualuxeConfig || {};
        
        // Create application instance
        app = new AquaLuxeApp(config);
        
        // Start the application
        await app.start();
        
        // Make app globally available for debugging
        if (config.debug) {
            window.aqualuxe = app;
        }
        
    } catch (error) {
        console.error('❌ Failed to initialize AquaLuxe:', error);
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    initApp();
}

// Handle page unload
window.addEventListener('beforeunload', () => {
    if (app) {
        app.cleanup();
    }
});

// Export for ES modules
export { AquaLuxeApp };
export default app;
