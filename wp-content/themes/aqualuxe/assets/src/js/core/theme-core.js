/**
 * AquaLuxe Theme Core
 *
 * Core functionality and utilities for the AquaLuxe theme
 * Provides foundational features for modular architecture
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

/**
 * Theme Core Class
 * 
 * Handles core theme functionality and provides
 * a foundation for other modules
 */
export class ThemeCore {
    /**
     * Constructor
     * 
     * @param {Object} config Theme configuration
     */
    constructor(config = {}) {
        this.config = {
            debug: false,
            version: '1.2.0',
            ...config
        };
        
        this.isInitialized = false;
        this.features = new Map();
    }

    /**
     * Initialize core
     * 
     * @return {Promise<void>} Initialization promise
     */
    async init() {
        if (this.isInitialized) {
            return;
        }

        // Set up core features
        this.setupUtilities();
        this.setupCompatibility();
        this.setupGlobalStyles();
        
        this.isInitialized = true;
        
        if (this.config.debug) {
            console.log('🔧 ThemeCore initialized');
        }
    }

    /**
     * Set up utility functions
     */
    setupUtilities() {
        // Add utility classes to body
        document.body.classList.add('aqualuxe-theme');
        
        // Set CSS custom properties for theme variables
        this.setCSSVariables();
    }

    /**
     * Set CSS custom properties
     */
    setCSSVariables() {
        const root = document.documentElement;
        
        // Theme colors
        if (this.config.colors) {
            Object.entries(this.config.colors).forEach(([key, value]) => {
                root.style.setProperty(`--aqualuxe-${key}`, value);
            });
        }
        
        // Theme version
        root.style.setProperty('--aqualuxe-version', `"${this.config.version}"`);
    }

    /**
     * Set up browser compatibility features
     */
    setupCompatibility() {
        // Add browser classes
        this.addBrowserClasses();
        
        // Add feature detection classes
        this.addFeatureClasses();
    }

    /**
     * Add browser detection classes
     */
    addBrowserClasses() {
        const body = document.body;
        const ua = navigator.userAgent;
        
        // Browser detection
        if (ua.includes('Chrome')) {
            body.classList.add('browser-chrome');
        } else if (ua.includes('Firefox')) {
            body.classList.add('browser-firefox');
        } else if (ua.includes('Safari')) {
            body.classList.add('browser-safari');
        } else if (ua.includes('Edge')) {
            body.classList.add('browser-edge');
        }
        
        // Mobile detection
        if (/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua)) {
            body.classList.add('is-mobile');
        }
        
        // Touch device detection
        if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
            body.classList.add('is-touch');
        } else {
            body.classList.add('no-touch');
        }
    }

    /**
     * Add feature detection classes
     */
    addFeatureClasses() {
        const body = document.body;
        
        // JavaScript enabled
        body.classList.remove('no-js');
        body.classList.add('js');
        
        // CSS Grid support
        if (CSS.supports('display', 'grid')) {
            body.classList.add('supports-grid');
        } else {
            body.classList.add('no-grid');
        }
        
        // CSS Custom Properties support
        if (CSS.supports('--custom', 'property')) {
            body.classList.add('supports-custom-properties');
        }
        
        // IntersectionObserver support
        if ('IntersectionObserver' in window) {
            body.classList.add('supports-intersection-observer');
        }
        
        // WebP support
        this.checkWebPSupport().then(supported => {
            body.classList.add(supported ? 'supports-webp' : 'no-webp');
        });
    }

    /**
     * Check WebP support
     * 
     * @return {Promise<boolean>} WebP support status
     */
    checkWebPSupport() {
        return new Promise(resolve => {
            const webP = new Image();
            webP.onload = webP.onerror = () => {
                resolve(webP.height === 2);
            };
            webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
        });
    }

    /**
     * Set up global styles
     */
    setupGlobalStyles() {
        // Add CSS for smooth scrolling if supported
        if (CSS.supports('scroll-behavior', 'smooth')) {
            document.documentElement.style.scrollBehavior = 'smooth';
        }
        
        // Add focus-visible polyfill class handling
        this.setupFocusVisible();
    }

    /**
     * Set up focus-visible handling
     */
    setupFocusVisible() {
        // Simple focus-visible polyfill
        let hadKeyboardEvent = true;
        
        const keyboardThrottledInputTypes = {
            textarea: true,
            input: true,
            select: true,
            button: true
        };

        function onPointerDown() {
            hadKeyboardEvent = false;
        }

        function onKeyDown(e) {
            if (e.metaKey || e.altKey || e.ctrlKey) {
                return;
            }
            hadKeyboardEvent = true;
        }

        function onFocus(e) {
            if (hadKeyboardEvent || e.target.matches(':focus-visible')) {
                e.target.classList.add('focus-visible');
            }
        }

        function onBlur(e) {
            e.target.classList.remove('focus-visible');
        }

        document.addEventListener('keydown', onKeyDown, true);
        document.addEventListener('mousedown', onPointerDown, true);
        document.addEventListener('pointerdown', onPointerDown, true);
        document.addEventListener('touchstart', onPointerDown, true);
        document.addEventListener('focus', onFocus, true);
        document.addEventListener('blur', onBlur, true);
    }

    /**
     * Register a feature
     * 
     * @param {string} name Feature name
     * @param {Object} feature Feature instance
     */
    registerFeature(name, feature) {
        this.features.set(name, feature);
        
        if (this.config.debug) {
            console.log(`🔧 Feature registered: ${name}`);
        }
    }

    /**
     * Get a feature
     * 
     * @param {string} name Feature name
     * @return {Object|null} Feature instance
     */
    getFeature(name) {
        return this.features.get(name) || null;
    }

    /**
     * Check if feature is registered
     * 
     * @param {string} name Feature name
     * @return {boolean} True if registered
     */
    hasFeature(name) {
        return this.features.has(name);
    }

    /**
     * Get theme configuration
     * 
     * @return {Object} Theme configuration
     */
    getConfig() {
        return { ...this.config };
    }

    /**
     * Update theme configuration
     * 
     * @param {Object} newConfig New configuration
     */
    updateConfig(newConfig) {
        this.config = { ...this.config, ...newConfig };
        
        // Update CSS variables if colors changed
        if (newConfig.colors) {
            this.setCSSVariables();
        }
    }

    /**
     * Get initialization status
     * 
     * @return {boolean} True if initialized
     */
    isReady() {
        return this.isInitialized;
    }
}
