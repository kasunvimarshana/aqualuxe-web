/*!
 * AquaLuxe Theme - Main JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Import modules
import { initializeNavigation } from './modules/navigation';
import { initializeSearch } from './modules/search';
import { initializeModals } from './modules/modals';
import { initializeDarkMode } from './modules/dark-mode';
import { initializeAnimations } from './modules/animations';
import { initializeWooCommerce } from './modules/woocommerce';
import { initializeUtils } from './modules/utils';
import { initializeAccessibility } from './modules/accessibility';

/**
 * AquaLuxe Theme Main Class
 */
class AquaLuxeTheme {
    constructor() {
        this.isInitialized = false;
        this.modules = new Map();
        this.config = {
            debug: window.aqualuxe_config?.debug || false,
            ajax_url: window.aqualuxe_config?.ajax_url || '/wp-admin/admin-ajax.php',
            nonce: window.aqualuxe_config?.nonce || '',
            theme_url: window.aqualuxe_config?.theme_url || '',
            is_mobile: window.innerWidth < 768,
            is_admin: document.body.classList.contains('admin-bar'),
            animations_enabled: !window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        };
        
        this.bindEvents();
        this.init();
    }

    /**
     * Initialize the theme
     */
    init() {
        if (this.isInitialized) {
            return;
        }

        this.log('Initializing AquaLuxe Theme...');
        
        // Initialize core modules
        this.initializeModules();
        
        // Set up global event listeners
        this.setupGlobalEvents();
        
        // Initialize lazy loading
        this.initializeLazyLoading();
        
        // Initialize performance monitoring
        this.initializePerformance();
        
        this.isInitialized = true;
        this.log('AquaLuxe Theme initialized successfully');
        
        // Dispatch custom event
        this.dispatchEvent('theme:initialized');
    }

    /**
     * Initialize all modules
     */
    initializeModules() {
        const modules = [
            { name: 'utils', init: initializeUtils },
            { name: 'accessibility', init: initializeAccessibility },
            { name: 'navigation', init: initializeNavigation },
            { name: 'search', init: initializeSearch },
            { name: 'modals', init: initializeModals },
            { name: 'dark-mode', init: initializeDarkMode },
            { name: 'animations', init: initializeAnimations },
            { name: 'woocommerce', init: initializeWooCommerce },
        ];

        modules.forEach(module => {
            try {
                this.log(`Initializing ${module.name} module...`);
                const instance = module.init(this.config);
                this.modules.set(module.name, instance);
                this.log(`${module.name} module initialized`);
            } catch (error) {
                this.error(`Failed to initialize ${module.name} module:`, error);
            }
        });
    }

    /**
     * Set up global event listeners
     */
    setupGlobalEvents() {
        // Resize handler with debouncing
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.config.is_mobile = window.innerWidth < 768;
                this.dispatchEvent('theme:resize', { 
                    width: window.innerWidth, 
                    height: window.innerHeight,
                    is_mobile: this.config.is_mobile 
                });
            }, 100);
        });

        // Scroll handler with throttling
        let scrollTimeout;
        let isScrolling = false;
        window.addEventListener('scroll', () => {
            if (!isScrolling) {
                window.requestAnimationFrame(() => {
                    this.dispatchEvent('theme:scroll', { 
                        scrollY: window.scrollY,
                        scrollX: window.scrollX 
                    });
                    isScrolling = false;
                });
                isScrolling = true;
            }
        }, { passive: true });

        // Click handler for various interactive elements
        document.addEventListener('click', this.handleGlobalClick.bind(this));

        // Form submission handler
        document.addEventListener('submit', this.handleFormSubmit.bind(this));

        // Key press handler for accessibility
        document.addEventListener('keydown', this.handleKeyDown.bind(this));

        // Visibility change handler
        document.addEventListener('visibilitychange', () => {
            this.dispatchEvent('theme:visibility', { 
                hidden: document.hidden 
            });
        });

        // Online/offline status
        window.addEventListener('online', () => {
            this.dispatchEvent('theme:online');
        });

        window.addEventListener('offline', () => {
            this.dispatchEvent('theme:offline');
        });
    }

    /**
     * Global click handler
     */
    handleGlobalClick(event) {
        const target = event.target;
        const closest = target.closest.bind(target);

        // Handle smooth scroll links
        if (closest('a[href^="#"]')) {
            this.handleSmoothScroll(event);
        }

        // Handle external links
        if (closest('a[href^="http"]:not([href*="' + window.location.hostname + '"])')) {
            this.handleExternalLink(event);
        }

        // Handle toggle elements
        if (closest('[data-toggle]')) {
            this.handleToggle(event);
        }

        // Handle copy elements
        if (closest('[data-copy]')) {
            this.handleCopy(event);
        }

        // Close dropdowns when clicking outside
        if (!closest('.dropdown, [data-dropdown]')) {
            this.closeAllDropdowns();
        }
    }

    /**
     * Handle smooth scroll links
     */
    handleSmoothScroll(event) {
        const link = event.target.closest('a[href^="#"]');
        if (!link) return;

        const href = link.getAttribute('href');
        if (href === '#') return;

        const target = document.querySelector(href);
        if (!target) return;

        event.preventDefault();
        
        const headerOffset = this.config.is_admin ? 112 : 80; // Account for admin bar
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });

        // Update URL hash
        if (history.pushState) {
            history.pushState(null, null, href);
        }

        // Focus the target for accessibility
        target.focus({ preventScroll: true });
    }

    /**
     * Handle external links
     */
    handleExternalLink(event) {
        const link = event.target.closest('a');
        if (!link) return;

        // Add external link attributes if not present
        if (!link.hasAttribute('target')) {
            link.setAttribute('target', '_blank');
        }
        if (!link.hasAttribute('rel')) {
            link.setAttribute('rel', 'noopener noreferrer');
        }
    }

    /**
     * Handle toggle elements
     */
    handleToggle(event) {
        const trigger = event.target.closest('[data-toggle]');
        if (!trigger) return;

        event.preventDefault();
        
        const targetSelector = trigger.getAttribute('data-toggle');
        const target = document.querySelector(targetSelector);
        
        if (!target) return;

        const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
        
        // Toggle states
        trigger.setAttribute('aria-expanded', !isExpanded);
        target.classList.toggle('hidden');
        target.classList.toggle('show');

        // Dispatch event
        this.dispatchEvent('theme:toggle', {
            trigger,
            target,
            expanded: !isExpanded
        });
    }

    /**
     * Handle copy to clipboard
     */
    async handleCopy(event) {
        const trigger = event.target.closest('[data-copy]');
        if (!trigger) return;

        event.preventDefault();

        const textToCopy = trigger.getAttribute('data-copy') || trigger.textContent;
        
        try {
            await navigator.clipboard.writeText(textToCopy);
            this.showNotification('Copied to clipboard!', 'success');
        } catch (error) {
            this.error('Failed to copy to clipboard:', error);
            this.showNotification('Failed to copy to clipboard', 'error');
        }
    }

    /**
     * Close all open dropdowns
     */
    closeAllDropdowns() {
        const openDropdowns = document.querySelectorAll('[data-dropdown].show, .dropdown.show');
        openDropdowns.forEach(dropdown => {
            dropdown.classList.remove('show');
            dropdown.classList.add('hidden');
            
            const trigger = document.querySelector(`[data-toggle="#${dropdown.id}"], [aria-controls="${dropdown.id}"]`);
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    /**
     * Handle form submissions
     */
    handleFormSubmit(event) {
        const form = event.target;
        
        // Handle AJAX forms
        if (form.classList.contains('ajax-form')) {
            event.preventDefault();
            this.handleAjaxForm(form);
        }

        // Validate forms
        if (!this.validateForm(form)) {
            event.preventDefault();
        }
    }

    /**
     * Handle AJAX form submissions
     */
    async handleAjaxForm(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('[type="submit"]');
        const originalText = submitButton?.textContent;

        try {
            // Show loading state
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Processing...';
            }

            const response = await fetch(form.action || this.config.ajax_url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.message || 'Form submitted successfully!', 'success');
                form.reset();
            } else {
                throw new Error(data.message || 'Form submission failed');
            }

        } catch (error) {
            this.error('AJAX form submission failed:', error);
            this.showNotification(error.message || 'Form submission failed', 'error');
        } finally {
            // Reset button state
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        }
    }

    /**
     * Validate form
     */
    validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        // Email validation
        const emailFields = form.querySelectorAll('input[type="email"]');
        emailFields.forEach(field => {
            if (field.value && !this.isValidEmail(field.value)) {
                this.showFieldError(field, 'Please enter a valid email address');
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * Show field error
     */
    showFieldError(field, message) {
        field.classList.add('error');
        
        let errorElement = field.parentElement.querySelector('.field-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'field-error text-red-600 text-sm mt-1';
            field.parentElement.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
    }

    /**
     * Clear field error
     */
    clearFieldError(field) {
        field.classList.remove('error');
        const errorElement = field.parentElement.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Validate email
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Handle keyboard events
     */
    handleKeyDown(event) {
        // Escape key handler
        if (event.key === 'Escape') {
            this.handleEscapeKey(event);
        }

        // Enter key handler for buttons
        if (event.key === 'Enter' && event.target.matches('button:not([type="submit"])')) {
            event.target.click();
        }
    }

    /**
     * Handle escape key
     */
    handleEscapeKey(event) {
        // Close modals
        const openModal = document.querySelector('.modal-overlay:not(.hidden)');
        if (openModal) {
            const closeButton = openModal.querySelector('.modal-close');
            if (closeButton) {
                closeButton.click();
            }
            return;
        }

        // Close dropdowns
        this.closeAllDropdowns();

        // Close search overlay
        const searchOverlay = document.querySelector('.search-overlay:not(.hidden)');
        if (searchOverlay) {
            searchOverlay.classList.add('hidden');
        }
    }

    /**
     * Initialize lazy loading
     */
    initializeLazyLoading() {
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('img[data-src], [data-bg]');
            
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        
                        if (element.hasAttribute('data-src')) {
                            element.src = element.getAttribute('data-src');
                            element.removeAttribute('data-src');
                        }
                        
                        if (element.hasAttribute('data-bg')) {
                            element.style.backgroundImage = `url(${element.getAttribute('data-bg')})`;
                            element.removeAttribute('data-bg');
                        }
                        
                        element.classList.add('loaded');
                        observer.unobserve(element);
                    }
                });
            });

            lazyImages.forEach(image => imageObserver.observe(image));
        }
    }

    /**
     * Initialize performance monitoring
     */
    initializePerformance() {
        // Measure page load time
        window.addEventListener('load', () => {
            if ('performance' in window) {
                const perfData = performance.getEntriesByType('navigation')[0];
                if (perfData) {
                    this.log(`Page load time: ${Math.round(perfData.loadEventEnd - perfData.fetchStart)}ms`);
                }
            }
        });

        // Monitor long tasks
        if ('PerformanceObserver' in window) {
            try {
                const observer = new PerformanceObserver((list) => {
                    list.getEntries().forEach((entry) => {
                        if (entry.duration > 50) {
                            this.log(`Long task detected: ${Math.round(entry.duration)}ms`);
                        }
                    });
                });
                observer.observe({ entryTypes: ['longtask'] });
            } catch (error) {
                // Longtask observer not supported
            }
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} fixed top-4 right-4 z-50 bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm transform translate-x-full transition-transform duration-300`;
        
        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${this.getNotificationIcon(type)}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button type="button" class="notification-close text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto hide
        const hideTimeout = setTimeout(() => {
            this.hideNotification(notification);
        }, duration);

        // Manual close
        notification.querySelector('.notification-close').addEventListener('click', () => {
            clearTimeout(hideTimeout);
            this.hideNotification(notification);
        });
    }

    /**
     * Hide notification
     */
    hideNotification(notification) {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }

    /**
     * Get notification icon
     */
    getNotificationIcon(type) {
        const icons = {
            success: '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            error: '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            warning: '<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            info: '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
        };
        return icons[type] || icons.info;
    }

    /**
     * Bind events
     */
    bindEvents() {
        // DOM content loaded handler
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.dispatchEvent('theme:dom-ready');
            });
        } else {
            setTimeout(() => this.dispatchEvent('theme:dom-ready'), 0);
        }

        // Window load handler
        if (document.readyState === 'complete') {
            setTimeout(() => this.dispatchEvent('theme:loaded'), 0);
        } else {
            window.addEventListener('load', () => {
                this.dispatchEvent('theme:loaded');
            });
        }
    }

    /**
     * Dispatch custom event
     */
    dispatchEvent(eventName, detail = {}) {
        const event = new CustomEvent(eventName, {
            detail: { ...detail, theme: this },
            bubbles: true,
            cancelable: true
        });
        document.dispatchEvent(event);
    }

    /**
     * Get module instance
     */
    getModule(name) {
        return this.modules.get(name);
    }

    /**
     * Logging utility
     */
    log(...args) {
        if (this.config.debug && console.log) {
            console.log('[AquaLuxe]', ...args);
        }
    }

    /**
     * Error logging utility
     */
    error(...args) {
        if (console.error) {
            console.error('[AquaLuxe Error]', ...args);
        }
    }

    /**
     * Warning logging utility
     */
    warn(...args) {
        if (this.config.debug && console.warn) {
            console.warn('[AquaLuxe Warning]', ...args);
        }
    }
}

// Initialize theme when DOM is ready
const theme = new AquaLuxeTheme();

// Make theme instance globally available
window.AquaLuxe = theme;

// Export for module usage
export default theme;
