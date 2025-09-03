/**
 * AquaLuxe Theme - Main Application
 * 
 * Modern, modular JavaScript architecture for the AquaLuxe WordPress theme.
 * Bringing elegance to aquatic life – globally.
 */

// Import core components
import FishTank from './components/fishtank.js';
import WooCommerce from './components/woocommerce.js';
import Navigation from './components/navigation.js';
import Modal from './components/modal.js';
import Accessibility from './components/accessibility.js';

class AquaLuxeApp {
    constructor() {
        this.components = new Map();
        this.isInitialized = false;
        this.settings = window.aqualuxe_settings || {};
        
        this.init();
    }

    async init() {
        try {
            console.log('🐠 Initializing AquaLuxe Theme...');
            
            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                await new Promise(resolve => {
                    document.addEventListener('DOMContentLoaded', resolve);
                });
            }

            // Initialize core features in optimal order
            await this.initializeComponents();
            
            // Set up global event listeners
            this.bindGlobalEvents();
            
            this.isInitialized = true;
            console.log('✨ AquaLuxe Theme initialized successfully!');
            
            // Dispatch ready event
            document.dispatchEvent(new CustomEvent('aqualuxe:ready', {
                detail: { app: this }
            }));
            
        } catch (error) {
            console.error('❌ AquaLuxe Theme initialization failed:', error);
        }
    }

    async initializeComponents() {
        // Initialize in priority order for best user experience
        
        // 1. Accessibility first (foundation for everything)
        await this.initAccessibility();
        
        // 2. Navigation (critical for UX)
        await this.initNavigation();
        
        // 3. Modal system (used by other components)
        await this.initModals();
        
        // 4. WooCommerce features (if applicable)
        await this.initWooCommerce();
        
        // 5. Fish Tank Hero (flagship interactive feature)
        await this.initFishTank();
        
        // 6. Performance optimizations
        await this.initPerformance();
        
        // 7. Dark mode toggle
        await this.initDarkMode();
        
        // 8. Additional features
        await this.initLazyLoading();
        await this.initFormEnhancements();
        await this.initScrollEffects();
    }

    async initFishTank() {
        const fishTankContainers = document.querySelectorAll('.fishtank');
        
        if (fishTankContainers.length === 0) return;
        
        console.log('🐟 Initializing Fish Tank animations...');
        
        for (const container of fishTankContainers) {
            try {
                const fishCount = this.getResponsiveFishCount();
                const options = {
                    fishCount: fishCount,
                    enableSound: this.settings.enableSound !== false,
                    enableInteraction: this.settings.enableInteraction !== false,
                    reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
                    ...this.settings.fishTank
                };
                
                const fishTank = new FishTank(container, options);
                this.components.set(`fishtank-${container.id || Math.random()}`, fishTank);
                
                // Add error handling
                container.addEventListener('fishtank:error', (e) => {
                    console.warn('Fish tank error:', e.detail);
                    this.createCSSFallback(container);
                });
                
            } catch (error) {
                console.warn('Fish tank initialization failed for container:', container, error);
                
                // Fallback to CSS-only animation
                container.classList.add('fishtank-fallback');
                this.createCSSFallback(container);
            }
        }
    }

    getResponsiveFishCount() {
        const width = window.innerWidth;
        const devicePixelRatio = window.devicePixelRatio || 1;
        
        // Adjust based on device capabilities
        if (width < 768 || devicePixelRatio > 2) return 6;  // Mobile or high DPI
        if (width < 1024) return 9; // Tablet
        return 12; // Desktop
    }

    createCSSFallback(container) {
        // Create CSS-only fish animation as fallback
        const fallbackHTML = `
            <div class="css-fish"></div>
            <div class="css-fish"></div>
            <div class="css-fish"></div>
            <div class="css-bubble"></div>
            <div class="css-bubble"></div>
            <div class="css-bubble"></div>
            <div class="water-overlay"></div>
        `;
        container.innerHTML = fallbackHTML;
        
        // Announce fallback to screen readers
        const accessibility = this.getComponent('accessibility');
        if (accessibility) {
            accessibility.announce('Fish tank animation loaded in compatibility mode');
        }
    }

    async initWooCommerce() {
        if (!document.body.classList.contains('woocommerce') && 
            !document.querySelector('.woocommerce')) return;
        
        console.log('🛒 Initializing WooCommerce features...');
        
        try {
            const woocommerce = new WooCommerce();
            this.components.set('woocommerce', woocommerce);
        } catch (error) {
            console.error('WooCommerce initialization failed:', error);
        }
    }

    async initNavigation() {
        console.log('🧭 Initializing Navigation...');
        
        try {
            const navigation = new Navigation();
            this.components.set('navigation', navigation);
            
            // Highlight current page
            navigation.highlightCurrentPage();
            navigation.addActiveStates();
            
        } catch (error) {
            console.error('Navigation initialization failed:', error);
        }
    }

    async initModals() {
        console.log('🪟 Initializing Modal system...');
        
        try {
            const modal = new Modal();
            this.components.set('modal', modal);
        } catch (error) {
            console.error('Modal initialization failed:', error);
        }
    }

    async initAccessibility() {
        console.log('♿ Initializing Accessibility features...');
        
        try {
            const accessibility = new Accessibility();
            this.components.set('accessibility', accessibility);
        } catch (error) {
            console.error('Accessibility initialization failed:', error);
        }
    }

    async initPerformance() {
        console.log('⚡ Initializing Performance optimizations...');
        
        try {
            const { default: Performance } = await import('./components/performance.js');
            const performance = new Performance();
            this.components.set('performance', performance);
        } catch (error) {
            console.error('Performance initialization failed:', error);
        }
    }

    async initDarkMode() {
        console.log('🌙 Initializing Dark mode...');
        
        try {
            const { default: DarkMode } = await import('./components/dark-mode.js');
            const darkMode = new DarkMode();
            this.components.set('darkMode', darkMode);
        } catch (error) {
            console.error('Dark mode initialization failed:', error);
        }
    }

    async initLazyLoading() {
        if (!('IntersectionObserver' in window)) return;
        
        console.log('🔄 Initializing Lazy loading...');
        
        try {
            const { default: LazyLoad } = await import('./components/lazy-load.js');
            const lazyLoad = new LazyLoad();
            this.components.set('lazyLoad', lazyLoad);
        } catch (error) {
            console.error('Lazy loading initialization failed:', error);
        }
    }

    async initFormEnhancements() {
        const forms = document.querySelectorAll('form');
        if (forms.length === 0) return;
        
        console.log('📝 Initializing Form enhancements...');
        
        try {
            const { default: FormEnhancer } = await import('./components/form-enhancer.js');
            const formEnhancer = new FormEnhancer();
            this.components.set('formEnhancer', formEnhancer);
        } catch (error) {
            console.error('Form enhancement initialization failed:', error);
        }
    }

    async initScrollEffects() {
        console.log('📜 Initializing Scroll effects...');
        
        try {
            const { default: ScrollEffects } = await import('./components/scroll-effects.js');
            const scrollEffects = new ScrollEffects();
            this.components.set('scrollEffects', scrollEffects);
        } catch (error) {
            console.error('Scroll effects initialization failed:', error);
        }
    }

    bindGlobalEvents() {
        // Handle visibility changes (performance optimization)
        document.addEventListener('visibilitychange', () => {
            const isHidden = document.hidden;
            
            this.components.forEach((component) => {
                if (component && typeof component.handleVisibilityChange === 'function') {
                    component.handleVisibilityChange(isHidden);
                }
            });
        });
        
        // Handle window resize with debouncing
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                this.handleResize();
            }, 250);
        });
        
        // Handle orientation changes
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                this.handleResize();
            }, 100);
        });
        
        // Handle connection changes
        if ('connection' in navigator) {
            navigator.connection.addEventListener('change', () => {
                this.handleConnectionChange();
            });
        }
    }

    handleResize() {
        const newFishCount = this.getResponsiveFishCount();
        
        this.components.forEach((component, name) => {
            if (name.startsWith('fishtank-') && component.options) {
                component.options.fishCount = newFishCount;
            }
            
            if (component && typeof component.handleResize === 'function') {
                component.handleResize();
            }
        });
    }

    handleConnectionChange() {
        const connection = navigator.connection;
        const isSlowConnection = connection.effectiveType === 'slow-2g' || 
                                connection.effectiveType === '2g';
        
        // Reduce animations on slow connections
        if (isSlowConnection) {
            document.body.classList.add('slow-connection');
            
            this.components.forEach((component) => {
                if (component && typeof component.optimizeForSlowConnection === 'function') {
                    component.optimizeForSlowConnection();
                }
            });
        }
    }

    // Public API
    getComponent(name) {
        return this.components.get(name);
    }

    hasComponent(name) {
        return this.components.has(name);
    }

    getAllComponents() {
        return Array.from(this.components.entries());
    }

    async loadComponent(name, options = {}) {
        if (this.hasComponent(name)) {
            return this.getComponent(name);
        }
        
        try {
            const { default: Component } = await import(`./components/${name}.js`);
            const instance = new Component(options);
            this.components.set(name, instance);
            
            console.log(`📦 Dynamically loaded component: ${name}`);
            return instance;
        } catch (error) {
            console.error(`Failed to load component: ${name}`, error);
            return null;
        }
    }

    unloadComponent(name) {
        const component = this.getComponent(name);
        if (component && typeof component.destroy === 'function') {
            component.destroy();
        }
        this.components.delete(name);
    }

    getPerformanceMetrics() {
        return {
            componentsLoaded: this.components.size,
            memoryUsage: performance.memory ? {
                used: Math.round(performance.memory.usedJSHeapSize / 1048576),
                total: Math.round(performance.memory.totalJSHeapSize / 1048576),
                limit: Math.round(performance.memory.jsHeapSizeLimit / 1048576)
            } : null,
            timing: performance.timing ? {
                domContentLoaded: performance.timing.domContentLoadedEventEnd - performance.timing.navigationStart,
                loadComplete: performance.timing.loadEventEnd - performance.timing.navigationStart
            } : null
        };
    }

    destroy() {
        console.log('🔄 Destroying AquaLuxe Theme...');
        
        this.components.forEach((component, name) => {
            if (component && typeof component.destroy === 'function') {
                try {
                    component.destroy();
                } catch (error) {
                    console.error(`Failed to destroy component: ${name}`, error);
                }
            }
        });
        
        this.components.clear();
        this.isInitialized = false;
        
        // Clean up global event listeners
        window.removeEventListener('resize', this.handleResize);
        window.removeEventListener('orientationchange', this.handleResize);
        
        if ('connection' in navigator) {
            navigator.connection.removeEventListener('change', this.handleConnectionChange);
        }
    }
}

// Initialize the application
const app = new AquaLuxeApp();

// Export for global access
window.AquaLuxe = app;

// Expose development helpers in development mode
if (process.env.NODE_ENV === 'development') {
    window.AquaLuxeDev = {
        app,
        components: app.components,
        metrics: () => app.getPerformanceMetrics(),
        reload: (componentName) => {
            if (componentName) {
                app.unloadComponent(componentName);
                return app.loadComponent(componentName);
            } else {
                window.location.reload();
            }
        }
    };
}

export default app;
