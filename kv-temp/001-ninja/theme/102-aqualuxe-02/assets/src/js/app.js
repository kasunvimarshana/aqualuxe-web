/**
 * AquaLuxe Theme Main JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Import required modules
import AOS from 'aos';
import 'aos/dist/aos.css';

// Theme Core Class
class AquaLuxeTheme {
    constructor() {
        this.isLoaded = false;
        this.config = {
            debug: false,
            version: '1.0.0'
        };
        
        this.init();
    }

    /**
     * Initialize theme
     */
    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.onReady());
        } else {
            this.onReady();
        }
    }

    /**
     * DOM ready handler
     */
    onReady() {
        this.setupUtilities();
        this.setupAnimations();
        this.setupNavigation();
        this.setupAccessibility();
        this.setupDarkMode();
        this.setupLazyLoading();
        
        this.isLoaded = true;
        
        // Trigger custom event
        document.dispatchEvent(new CustomEvent('aqualuxe:loaded'));
        
        if (this.config.debug) {
            console.log('🌊 AquaLuxe Theme loaded successfully');
        }
    }

    /**
     * Setup utility functions
     */
    setupUtilities() {
        // Expose theme utilities globally
        window.AquaLuxe = {
            theme: this,
            utils: {
                debounce: this.debounce,
                throttle: this.throttle,
                addClass: this.addClass,
                removeClass: this.removeClass,
                toggleClass: this.toggleClass
            }
        };
    }

    /**
     * Setup animations
     */
    setupAnimations() {
        // Initialize AOS (Animate On Scroll)
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic'
        });
    }

    /**
     * Setup navigation
     */
    setupNavigation() {
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        if (navToggle && navMenu) {
            navToggle.addEventListener('click', (e) => {
                e.preventDefault();
                
                const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
                navToggle.setAttribute('aria-expanded', !isExpanded);
                navMenu.classList.toggle('is-active');
                
                // Add body class for mobile menu
                document.body.classList.toggle('menu-open');
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (navMenu && navMenu.classList.contains('is-active')) {
                if (!navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                    navMenu.classList.remove('is-active');
                    navToggle.setAttribute('aria-expanded', 'false');
                    document.body.classList.remove('menu-open');
                }
            }
        });

        // Handle dropdown menus
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                
                const dropdown = toggle.nextElementSibling;
                if (dropdown) {
                    dropdown.classList.toggle('is-active');
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', !isExpanded);
                }
            });
        });
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // Skip link focus fix
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', (e) => {
                const target = document.querySelector(skipLink.getAttribute('href'));
                if (target) {
                    target.setAttribute('tabindex', '-1');
                    target.focus();
                }
            });
        }

        // Keyboard navigation for dropdowns
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                // Close open dropdowns
                const activeDropdowns = document.querySelectorAll('.dropdown-menu.is-active');
                activeDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('is-active');
                    const toggle = dropdown.previousElementSibling;
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                        toggle.focus();
                    }
                });

                // Close mobile menu
                const navMenu = document.querySelector('.nav-menu.is-active');
                if (navMenu) {
                    navMenu.classList.remove('is-active');
                    const navToggle = document.querySelector('.nav-toggle');
                    if (navToggle) {
                        navToggle.setAttribute('aria-expanded', 'false');
                        navToggle.focus();
                    }
                    document.body.classList.remove('menu-open');
                }
            }
        });
    }

    /**
     * Setup dark mode
     */
    setupDarkMode() {
        const darkModeToggle = document.querySelector('.dark-mode-toggle');
        
        if (darkModeToggle) {
            // Check for saved preference or default to light mode
            const savedMode = localStorage.getItem('aqualuxe-color-mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const defaultMode = savedMode || (prefersDark ? 'dark' : 'light');
            
            this.setColorMode(defaultMode);
            
            darkModeToggle.addEventListener('click', () => {
                const currentMode = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                const newMode = currentMode === 'dark' ? 'light' : 'dark';
                this.setColorMode(newMode);
                localStorage.setItem('aqualuxe-color-mode', newMode);
            });
        }
    }

    /**
     * Set color mode
     */
    setColorMode(mode) {
        if (mode === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    /**
     * Setup lazy loading
     */
    setupLazyLoading() {
        // Simple lazy loading for images
        const images = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for browsers without IntersectionObserver
            images.forEach(img => {
                img.src = img.dataset.src;
                img.classList.remove('lazy');
            });
        }
    }

    /**
     * Utility: Debounce function
     */
    debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * Utility: Throttle function
     */
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Utility: Add class
     */
    addClass(element, className) {
        if (element && className) {
            element.classList.add(className);
        }
    }

    /**
     * Utility: Remove class
     */
    removeClass(element, className) {
        if (element && className) {
            element.classList.remove(className);
        }
    }

    /**
     * Utility: Toggle class
     */
    toggleClass(element, className) {
        if (element && className) {
            element.classList.toggle(className);
        }
    }
}

// Initialize theme when script loads
new AquaLuxeTheme();