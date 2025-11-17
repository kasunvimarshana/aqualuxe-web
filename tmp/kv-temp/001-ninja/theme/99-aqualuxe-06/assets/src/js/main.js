/**
 * AquaLuxe Main JavaScript
 * 
 * Main theme JavaScript with modular architecture
 * "Bringing elegance to aquatic life – globally"
 * 
 * @package AquaLuxe
 * @version 2.0.0
 */

// Import modules
import { DarkMode } from './modules/dark-mode';
import { Navigation } from './modules/navigation';
import { LazyLoading } from './modules/lazy-loading';
import { Animations } from './modules/animations';
import { Forms } from './modules/forms';
import { Modal } from './modules/modal';
import { Utils } from './modules/utils';

/**
 * AquaLuxe Theme Class
 * Main theme controller
 */
class AquaLuxeTheme {
    constructor() {
        this.config = {
            debug: window.aqualuxeData?.debug || false,
            animations: window.aqualuxeData?.animations !== false,
            lazyLoading: window.aqualuxeData?.lazyLoading !== false,
            darkMode: window.aqualuxeData?.darkMode || 'auto',
        };

        this.modules = new Map();
        this.eventBus = this.createEventBus();
        
        this.init();
    }

    /**
     * Initialize theme
     */
    init() {
        this.log('🐠 AquaLuxe Theme initializing...');
        
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.onDOMReady());
        } else {
            this.onDOMReady();
        }
    }

    /**
     * DOM Ready handler
     */
    onDOMReady() {
        this.log('📄 DOM ready, starting module initialization');
        
        this.loadModules();
        this.bindEvents();
        this.initAccessibility();
        
        // Emit ready event
        this.eventBus.emit('theme:ready');
        
        this.log('✅ AquaLuxe Theme initialized successfully');
    }

    /**
     * Load and initialize modules
     */
    loadModules() {
        const moduleConfigs = [
            { 
                name: 'utils', 
                class: Utils, 
                enabled: true 
            },
            { 
                name: 'darkMode', 
                class: DarkMode, 
                enabled: true,
                config: { mode: this.config.darkMode }
            },
            { 
                name: 'navigation', 
                class: Navigation, 
                enabled: true 
            },
            { 
                name: 'lazyLoading', 
                class: LazyLoading, 
                enabled: this.config.lazyLoading 
            },
            { 
                name: 'animations', 
                class: Animations, 
                enabled: this.config.animations 
            },
            { 
                name: 'forms', 
                class: Forms, 
                enabled: true 
            },
            { 
                name: 'modal', 
                class: Modal, 
                enabled: true 
            },
        ];

        moduleConfigs.forEach(moduleConfig => {
            if (moduleConfig.enabled) {
                try {
                    const moduleInstance = new moduleConfig.class({
                        ...moduleConfig.config,
                        eventBus: this.eventBus,
                        debug: this.config.debug
                    });
                    
                    this.modules.set(moduleConfig.name, moduleInstance);
                    this.log(`📦 Module loaded: ${moduleConfig.name}`);
                } catch (error) {
                    this.error(`Failed to load module: ${moduleConfig.name}`, error);
                }
            }
        });
    }

    /**
     * Bind global events
     */
    bindEvents() {
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                this.eventBus.emit('window:resize', {
                    width: window.innerWidth,
                    height: window.innerHeight
                });
            }, 100);
        });

        // Handle scroll
        let scrollTimer;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                this.eventBus.emit('window:scroll', {
                    scrollY: window.scrollY,
                    scrollX: window.scrollX
                });
            }, 10);
        });

        // Handle orientation change
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                this.eventBus.emit('window:orientationchange');
            }, 100);
        });

        // Handle visibility change
        document.addEventListener('visibilitychange', () => {
            this.eventBus.emit('page:visibilitychange', {
                hidden: document.hidden
            });
        });

        // Handle before unload
        window.addEventListener('beforeunload', () => {
            this.eventBus.emit('page:beforeunload');
        });
    }

    /**
     * Initialize accessibility features
     */
    initAccessibility() {
        // Skip links
        this.initSkipLinks();
        
        // Focus management
        this.initFocusManagement();
        
        // Keyboard navigation
        this.initKeyboardNavigation();
    }

    /**
     * Initialize skip links
     */
    initSkipLinks() {
        const skipLinks = document.querySelectorAll('.skip-link');
        
        skipLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    }

    /**
     * Initialize focus management
     */
    initFocusManagement() {
        // Trap focus in modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                const modal = document.querySelector('.modal.is-active');
                if (modal) {
                    this.trapFocus(e, modal);
                }
            }
        });
    }

    /**
     * Trap focus within element
     */
    trapFocus(event, element) {
        const focusableElements = element.querySelectorAll(
            'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select'
        );
        
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];

        if (event.shiftKey) {
            if (document.activeElement === firstFocusable) {
                lastFocusable.focus();
                event.preventDefault();
            }
        } else {
            if (document.activeElement === lastFocusable) {
                firstFocusable.focus();
                event.preventDefault();
            }
        }
    }

    /**
     * Initialize keyboard navigation
     */
    initKeyboardNavigation() {
        // Escape key handling
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.eventBus.emit('keyboard:escape');
            }
        });
    }

    /**
     * Create event bus for module communication
     */
    createEventBus() {
        const events = new Map();

        return {
            on(event, callback) {
                if (!events.has(event)) {
                    events.set(event, []);
                }
                events.get(event).push(callback);
            },

            off(event, callback) {
                if (events.has(event)) {
                    const callbacks = events.get(event);
                    const index = callbacks.indexOf(callback);
                    if (index > -1) {
                        callbacks.splice(index, 1);
                    }
                }
            },

            emit(event, data = null) {
                if (events.has(event)) {
                    events.get(event).forEach(callback => {
                        try {
                            callback(data);
                        } catch (error) {
                            console.error(`Error in event callback for ${event}:`, error);
                        }
                    });
                }
            }
        };
    }

    /**
     * Get module instance
     */
    getModule(name) {
        return this.modules.get(name);
    }

    /**
     * Logging helper
     */
    log(message, ...args) {
        if (this.config.debug) {
            console.log(`🐠 AquaLuxe: ${message}`, ...args);
        }
    }

    /**
     * Error logging helper
     */
    error(message, ...args) {
        console.error(`🐠 AquaLuxe Error: ${message}`, ...args);
    }
}

/**
 * Initialize theme when script loads
 */
const aqualuxeTheme = new AquaLuxeTheme();

// Make theme instance globally available for debugging
if (window.aqualuxeData?.debug) {
    window.aqualuxeTheme = aqualuxeTheme;
}

// Export for module usage
export default aqualuxeTheme;