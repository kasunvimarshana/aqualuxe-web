/**
 * Main JavaScript Application
 * 
 * Core functionality for AquaLuxe theme
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

import Alpine from 'alpinejs';
import { Glide } from 'glide-js';
import 'lazysizes';
import 'lazysizes/plugins/unveilhooks/ls.unveilhooks';

// Import modules
import './modules/navigation';
import './modules/search';
import './modules/dark-mode';
import './modules/newsletter';
import './modules/animations';
import './modules/lazy-loading';
import './modules/back-to-top';
import './modules/accessibility';

/**
 * Theme Core Class
 */
class AquaLuxeTheme {
    constructor() {
        this.init();
    }

    /**
     * Initialize theme
     */
    init() {
        this.initAlpine();
        this.initGlide();
        this.bindEvents();
        this.setupAjax();
        this.initModules();
        
        // Theme ready event
        document.dispatchEvent(new CustomEvent('aqualuxe:ready'));
    }

    /**
     * Initialize Alpine.js
     */
    initAlpine() {
        // Global Alpine data
        Alpine.data('theme', () => ({
            darkMode: localStorage.getItem('aqualuxe_dark_mode') === 'true',
            mobileMenuOpen: false,
            searchOpen: false,
            loading: false,
            
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('aqualuxe_dark_mode', this.darkMode);
                document.documentElement.classList.toggle('dark', this.darkMode);
            },
            
            toggleMobileMenu() {
                this.mobileMenuOpen = !this.mobileMenuOpen;
                document.body.classList.toggle('mobile-menu-open', this.mobileMenuOpen);
            },
            
            toggleSearch() {
                this.searchOpen = !this.searchOpen;
                if (this.searchOpen) {
                    this.$nextTick(() => {
                        const searchInput = document.querySelector('.search-field');
                        if (searchInput) searchInput.focus();
                    });
                }
            },
            
            showLoading() {
                this.loading = true;
                document.getElementById('loading-overlay')?.style.setProperty('display', 'flex');
            },
            
            hideLoading() {
                this.loading = false;
                document.getElementById('loading-overlay')?.style.setProperty('display', 'none');
            }
        }));

        // Start Alpine
        Alpine.start();
    }

    /**
     * Initialize Glide.js sliders
     */
    initGlide() {
        // Testimonials slider
        const testimonialsSlider = document.querySelector('[data-glide]');
        if (testimonialsSlider) {
            new Glide(testimonialsSlider, {
                type: 'carousel',
                perView: 1,
                autoplay: 5000,
                hoverpause: true,
                gap: 20,
                breakpoints: {
                    768: {
                        perView: 1
                    }
                }
            }).mount();
        }

        // Product gallery sliders
        document.querySelectorAll('.product-gallery-slider').forEach(slider => {
            new Glide(slider, {
                type: 'carousel',
                perView: 4,
                gap: 10,
                breakpoints: {
                    768: {
                        perView: 2
                    },
                    480: {
                        perView: 1
                    }
                }
            }).mount();
        });
    }

    /**
     * Bind global events
     */
    bindEvents() {
        // Handle form submissions
        document.addEventListener('submit', this.handleFormSubmit.bind(this));
        
        // Handle AJAX loading
        document.addEventListener('click', this.handleAjaxLinks.bind(this));
        
        // Handle scroll events
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(this.handleScroll.bind(this));
                ticking = true;
            }
        });
        
        // Handle resize events
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(this.handleResize.bind(this), 150);
        });
        
        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.handleEscapeKey();
            }
        });
        
        // Handle clicks outside elements
        document.addEventListener('click', this.handleOutsideClick.bind(this));
    }

    /**
     * Setup AJAX functionality
     */
    setupAjax() {
        // Make AJAX URL available globally
        window.aqualuxeAjax = window.aqualuxe_ajax || {};
        
        // Setup default AJAX headers
        this.ajaxDefaults = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            }
        };
    }

    /**
     * Initialize theme modules
     */
    initModules() {
        // Newsletter popup
        if (document.getElementById('newsletter-popup')) {
            setTimeout(() => {
                this.showNewsletterPopup();
            }, 30000); // Show after 30 seconds
        }
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Initialize tooltips
        this.initTooltips();
        
        // Initialize modals
        this.initModals();
    }

    /**
     * Handle form submissions
     */
    handleFormSubmit(e) {
        const form = e.target;
        
        // Newsletter form
        if (form.classList.contains('newsletter-form')) {
            e.preventDefault();
            this.handleNewsletterSubmit(form);
        }
        
        // Contact form
        if (form.classList.contains('contact-form')) {
            e.preventDefault();
            this.handleContactSubmit(form);
        }
        
        // Search form
        if (form.classList.contains('search-form')) {
            this.handleSearchSubmit(form);
        }
    }

    /**
     * Handle AJAX links
     */
    handleAjaxLinks(e) {
        const link = e.target.closest('a[data-ajax]');
        if (!link) return;
        
        e.preventDefault();
        this.loadContent(link.href, link.dataset.target || 'main');
    }

    /**
     * Handle scroll events
     */
    handleScroll() {
        const scrollY = window.scrollY;
        
        // Header scroll effect
        const header = document.querySelector('.site-header');
        if (header) {
            header.classList.toggle('scrolled', scrollY > 100);
        }
        
        // Back to top button
        const backToTop = document.getElementById('back-to-top');
        if (backToTop) {
            backToTop.classList.toggle('opacity-100', scrollY > 300);
            backToTop.classList.toggle('visible', scrollY > 300);
            backToTop.classList.toggle('opacity-0', scrollY <= 300);
            backToTop.classList.toggle('invisible', scrollY <= 300);
        }
        
        // Scroll animations
        this.triggerScrollAnimations();
        
        // Reset ticking
        setTimeout(() => {
            ticking = false;
        }, 10);
    }

    /**
     * Handle resize events
     */
    handleResize() {
        // Close mobile menu on larger screens
        if (window.innerWidth >= 1024) {
            document.body.classList.remove('mobile-menu-open');
        }
        
        // Recalculate element positions
        this.recalculatePositions();
    }

    /**
     * Handle escape key
     */
    handleEscapeKey() {
        // Close modals
        document.querySelectorAll('.modal.open').forEach(modal => {
            modal.classList.remove('open');
        });
        
        // Close search overlay
        const searchOverlay = document.querySelector('[data-search-overlay]');
        if (searchOverlay && !searchOverlay.classList.contains('hidden')) {
            searchOverlay.classList.add('hidden');
        }
        
        // Close mobile menu
        document.body.classList.remove('mobile-menu-open');
    }

    /**
     * Handle outside clicks
     */
    handleOutsideClick(e) {
        // Close dropdowns
        document.querySelectorAll('.dropdown.open').forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });
        
        // Close account dropdown
        const accountDropdown = document.querySelector('[data-account-dropdown]');
        const accountToggle = document.querySelector('[data-account-toggle]');
        if (accountDropdown && !accountDropdown.classList.contains('hidden') && 
            !accountDropdown.contains(e.target) && !accountToggle.contains(e.target)) {
            accountDropdown.classList.add('hidden');
        }
    }

    /**
     * Newsletter form submission
     */
    async handleNewsletterSubmit(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        try {
            submitButton.textContent = 'Subscribing...';
            submitButton.disabled = true;
            
            const response = await fetch(window.aqualuxe_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('Successfully subscribed to newsletter!', 'success');
                form.reset();
                
                // Hide newsletter popup if it's from there
                const popup = form.closest('#newsletter-popup');
                if (popup) {
                    popup.style.display = 'none';
                    document.cookie = 'aqualuxe_newsletter_shown=1; max-age=2592000; path=/';
                }
            } else {
                this.showNotification(result.data || 'Subscription failed. Please try again.', 'error');
            }
        } catch (error) {
            this.showNotification('Network error. Please try again.', 'error');
        } finally {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    }

    /**
     * Contact form submission
     */
    async handleContactSubmit(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        try {
            submitButton.textContent = 'Sending...';
            submitButton.disabled = true;
            
            const response = await fetch(window.aqualuxe_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('Message sent successfully!', 'success');
                form.reset();
            } else {
                this.showNotification(result.data || 'Failed to send message. Please try again.', 'error');
            }
        } catch (error) {
            this.showNotification('Network error. Please try again.', 'error');
        } finally {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    }

    /**
     * Load content via AJAX
     */
    async loadContent(url, target = 'main') {
        const targetElement = document.querySelector(target);
        if (!targetElement) return;
        
        try {
            this.showLoading();
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector(target);
            
            if (newContent) {
                targetElement.innerHTML = newContent.innerHTML;
                
                // Update URL
                history.pushState(null, '', url);
                
                // Reinitialize components
                this.reinitializeComponents(targetElement);
            }
        } catch (error) {
            this.showNotification('Failed to load content.', 'error');
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm`;
        notification.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="notification-icon">
                    ${this.getNotificationIcon(type)}
                </div>
                <div class="notification-message flex-1">${message}</div>
                <button class="notification-close text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            this.removeNotification(notification);
        }, 5000);
        
        // Remove on click
        notification.querySelector('.notification-close').addEventListener('click', () => {
            this.removeNotification(notification);
        });
        
        // Animate in
        requestAnimationFrame(() => {
            notification.classList.add('animate-slide-in-right');
        });
    }

    /**
     * Remove notification
     */
    removeNotification(notification) {
        notification.classList.add('animate-slide-out-right');
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
            success: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
            error: '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
            warning: '<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>',
            info: '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        };
        return icons[type] || icons.info;
    }

    /**
     * Show newsletter popup
     */
    showNewsletterPopup() {
        const popup = document.getElementById('newsletter-popup');
        if (!popup || document.cookie.includes('aqualuxe_newsletter_shown=1')) {
            return;
        }
        
        popup.style.display = 'flex';
        popup.classList.add('animate-fade-in');
        
        // Close on click outside
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                popup.style.display = 'none';
                document.cookie = 'aqualuxe_newsletter_shown=1; max-age=86400; path=/'; // 1 day
            }
        });
        
        // Close on close button
        popup.querySelector('.newsletter-close')?.addEventListener('click', () => {
            popup.style.display = 'none';
            document.cookie = 'aqualuxe_newsletter_shown=1; max-age=86400; path=/'; // 1 day
        });
    }

    /**
     * Initialize tooltips
     */
    initTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', this.showTooltip.bind(this));
            element.addEventListener('mouseleave', this.hideTooltip.bind(this));
        });
    }

    /**
     * Initialize modals
     */
    initModals() {
        document.querySelectorAll('[data-modal]').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.dataset.modal;
                const modal = document.getElementById(modalId);
                if (modal) {
                    this.openModal(modal);
                }
            });
        });
        
        document.querySelectorAll('.modal-close').forEach(closeBtn => {
            closeBtn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal');
                if (modal) {
                    this.closeModal(modal);
                }
            });
        });
    }

    /**
     * Trigger scroll animations
     */
    triggerScrollAnimations() {
        const elements = document.querySelectorAll('[data-animate]');
        elements.forEach(element => {
            if (this.isElementInViewport(element) && !element.classList.contains('animated')) {
                element.classList.add('animated', element.dataset.animate);
            }
        });
    }

    /**
     * Check if element is in viewport
     */
    isElementInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    /**
     * Recalculate positions
     */
    recalculatePositions() {
        // Recalculate any position-dependent elements
        document.dispatchEvent(new CustomEvent('aqualuxe:resize'));
    }

    /**
     * Reinitialize components after AJAX load
     */
    reinitializeComponents(container) {
        // Reinitialize lazy loading
        if (window.lazySizes) {
            window.lazySizes.init();
        }
        
        // Reinitialize sliders
        container.querySelectorAll('[data-glide]').forEach(slider => {
            new Glide(slider).mount();
        });
        
        // Reinitialize tooltips
        this.initTooltips();
        
        // Dispatch event
        container.dispatchEvent(new CustomEvent('aqualuxe:content-loaded'));
    }
}

// Initialize theme when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.AquaLuxe = new AquaLuxeTheme();
});

// Export for potential external use
export default AquaLuxeTheme;
