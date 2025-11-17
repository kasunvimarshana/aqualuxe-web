/**
 * AquaLuxe Theme Main JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

import Alpine from 'alpinejs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import AOS from 'aos';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Import modules
import DarkMode from './modules/dark-mode';
import Search from './modules/search';
import MobileMenu from './modules/mobile-menu';
import SmoothScroll from './modules/smooth-scroll';
import LazyLoading from './modules/lazy-loading';
import Animations from './modules/animations';
import Performance from './modules/performance';

class AquaLuxeTheme {
    constructor() {
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.onDOMReady());
        } else {
            this.onDOMReady();
        }

        // Initialize Alpine.js
        window.Alpine = Alpine;
        Alpine.start();
    }

    onDOMReady() {
        // Initialize core modules
        this.initModules();
        
        // Initialize event listeners
        this.initEventListeners();
        
        // Initialize animations
        this.initAnimations();
        
        // Initialize performance optimizations
        this.initPerformance();
        
        // Initialize accessibility features
        this.initAccessibility();
        
        // Initialize theme features
        this.initThemeFeatures();
    }

    initModules() {
        // Dark mode
        if (aqualuxeData.darkModeEnabled) {
            this.darkMode = new DarkMode();
        }

        // Search functionality
        this.search = new Search();

        // Mobile menu
        this.mobileMenu = new MobileMenu();

        // Smooth scrolling
        this.smoothScroll = new SmoothScroll();

        // Lazy loading
        if (aqualuxeData.settings.lazyLoading) {
            this.lazyLoading = new LazyLoading();
        }

        // Animations
        if (aqualuxeData.settings.animationsEnabled) {
            this.animations = new Animations();
        }

        // Performance optimizations
        this.performance = new Performance();
    }

    initEventListeners() {
        // Back to top button
        this.initBackToTop();

        // Form enhancements
        this.initForms();

        // External links
        this.initExternalLinks();

        // Image galleries
        this.initGalleries();

        // Newsletter signup
        this.initNewsletter();

        // AJAX functionality
        this.initAjax();
    }

    initAnimations() {
        // Initialize AOS (Animate On Scroll)
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100,
            disable: 'mobile'
        });

        // Page transitions
        this.initPageTransitions();

        // Scroll animations
        this.initScrollAnimations();

        // Hover animations
        this.initHoverAnimations();
    }

    initPerformance() {
        // Preload critical resources
        this.preloadCriticalResources();

        // Optimize images
        this.optimizeImages();

        // Debounce scroll and resize events
        this.debounceEvents();

        // Monitor performance
        this.monitorPerformance();
    }

    initAccessibility() {
        // Keyboard navigation
        this.initKeyboardNavigation();

        // Focus management
        this.initFocusManagement();

        // ARIA live regions
        this.initAriaLiveRegions();

        // Skip links
        this.initSkipLinks();
    }

    initThemeFeatures() {
        // WooCommerce features
        if (aqualuxeData.isWooCommerceActive) {
            this.initWooCommerce();
        }

        // Social sharing
        if (aqualuxeData.settings.socialSharing) {
            this.initSocialSharing();
        }

        // Cookie consent
        this.initCookieConsent();

        // Analytics
        this.initAnalytics();
    }

    initBackToTop() {
        const backToTopBtn = document.getElementById('back-to-top');
        if (!backToTopBtn) return;

        const showBackToTop = () => {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('opacity-100', 'visible');
                backToTopBtn.classList.remove('opacity-0', 'invisible');
            } else {
                backToTopBtn.classList.remove('opacity-100', 'visible');
                backToTopBtn.classList.add('opacity-0', 'invisible');
            }
        };

        window.addEventListener('scroll', this.throttle(showBackToTop, 100));

        backToTopBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    initForms() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Add loading states
            form.addEventListener('submit', (e) => {
                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn) {
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                }
            });

            // Enhanced validation
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => this.clearFieldErrors(input));
            });
        });
    }

    initExternalLinks() {
        const externalLinks = document.querySelectorAll('a[href^="http"]:not([href*="' + window.location.hostname + '"])');
        
        externalLinks.forEach(link => {
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', 'noopener noreferrer');
            
            // Add external link icon
            if (!link.querySelector('.external-icon')) {
                const icon = document.createElement('span');
                icon.className = 'external-icon ml-1';
                icon.innerHTML = '↗';
                link.appendChild(icon);
            }
        });
    }

    initGalleries() {
        const galleries = document.querySelectorAll('.wp-block-gallery, .gallery');
        
        galleries.forEach(gallery => {
            const images = gallery.querySelectorAll('img');
            
            images.forEach(img => {
                img.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.openLightbox(img);
                });
            });
        });
    }

    initNewsletter() {
        const newsletterForms = document.querySelectorAll('.newsletter-form');
        
        newsletterForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const email = form.querySelector('input[type="email"]').value;
                const result = await this.subscribeNewsletter(email);
                
                if (result.success) {
                    this.showNotification(aqualuxeData.strings.subscriptionSuccess, 'success');
                    form.reset();
                } else {
                    this.showNotification(result.message || aqualuxeData.strings.error, 'error');
                }
            });
        });
    }

    initAjax() {
        // AJAX form submissions
        const ajaxForms = document.querySelectorAll('[data-ajax="true"]');
        
        ajaxForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.submitFormAjax(form);
            });
        });

        // Load more functionality
        const loadMoreBtns = document.querySelectorAll('.load-more-btn');
        
        loadMoreBtns.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                await this.loadMoreContent(btn);
            });
        });
    }

    initPageTransitions() {
        // Smooth page transitions
        const links = document.querySelectorAll('a[href^="/"], a[href^="' + window.location.origin + '"]');
        
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                if (link.getAttribute('target') === '_blank') return;
                
                e.preventDefault();
                this.transitionToPage(link.href);
            });
        });
    }

    initScrollAnimations() {
        // Parallax effects
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        
        parallaxElements.forEach(element => {
            const speed = element.dataset.parallax || 0.5;
            
            ScrollTrigger.create({
                trigger: element,
                start: "top bottom",
                end: "bottom top",
                scrub: true,
                onUpdate: self => {
                    const yPos = -(self.progress * speed * 100);
                    gsap.set(element, { y: yPos });
                }
            });
        });

        // Fade in animations
        const fadeElements = document.querySelectorAll('.fade-in');
        
        fadeElements.forEach(element => {
            gsap.fromTo(element, 
                { opacity: 0, y: 30 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    scrollTrigger: {
                        trigger: element,
                        start: "top 80%",
                        end: "bottom 20%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        });
    }

    initHoverAnimations() {
        // Card hover effects
        const cards = document.querySelectorAll('.card, .product-card, .post-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                gsap.to(card, { 
                    y: -5, 
                    scale: 1.02, 
                    duration: 0.3, 
                    ease: "power2.out" 
                });
            });

            card.addEventListener('mouseleave', () => {
                gsap.to(card, { 
                    y: 0, 
                    scale: 1, 
                    duration: 0.3, 
                    ease: "power2.out" 
                });
            });
        });

        // Button hover effects
        const buttons = document.querySelectorAll('.btn');
        
        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => {
                gsap.to(button, { scale: 1.05, duration: 0.2 });
            });

            button.addEventListener('mouseleave', () => {
                gsap.to(button, { scale: 1, duration: 0.2 });
            });
        });
    }

    initKeyboardNavigation() {
        // Tab trap for modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModals();
            }

            if (e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
        });
    }

    initFocusManagement() {
        // Focus outline styles
        document.addEventListener('mousedown', () => {
            document.body.classList.add('using-mouse');
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                document.body.classList.remove('using-mouse');
            }
        });
    }

    initAriaLiveRegions() {
        // Create live region for announcements
        const liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        liveRegion.id = 'aria-live-region';
        document.body.appendChild(liveRegion);
    }

    initSkipLinks() {
        const skipLinks = document.querySelectorAll('.skip-link');
        
        skipLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    }

    initWooCommerce() {
        // Dynamic import for WooCommerce module
        import('./modules/woocommerce').then(module => {
            this.woocommerce = new module.default();
        });
    }

    initSocialSharing() {
        const shareButtons = document.querySelectorAll('.share-button');
        
        shareButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.shareContent(button.dataset.platform, button.dataset.url, button.dataset.title);
            });
        });
    }

    initCookieConsent() {
        // Simple cookie consent implementation
        if (!this.getCookie('cookie_consent')) {
            this.showCookieConsent();
        }
    }

    initAnalytics() {
        // Initialize analytics tracking
        if (typeof gtag !== 'undefined') {
            this.trackPageView();
        }
    }

    // Utility methods
    throttle(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    async fetchWithTimeout(url, options = {}, timeout = 8000) {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), timeout);
        
        try {
            const response = await fetch(url, {
                ...options,
                signal: controller.signal
            });
            clearTimeout(timeoutId);
            return response;
        } catch (error) {
            clearTimeout(timeoutId);
            throw error;
        }
    }

    showNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Trigger animation
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto-remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, duration);
    }

    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    }

    // Validation helpers
    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        let isValid = true;
        let message = '';

        if (field.required && !value) {
            isValid = false;
            message = 'This field is required';
        } else if (type === 'email' && value && !this.isValidEmail(value)) {
            isValid = false;
            message = 'Please enter a valid email address';
        } else if (type === 'tel' && value && !this.isValidPhone(value)) {
            isValid = false;
            message = 'Please enter a valid phone number';
        }

        this.toggleFieldError(field, !isValid, message);
        return isValid;
    }

    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    isValidPhone(phone) {
        const re = /^[\+]?[1-9][\d]{0,15}$/;
        return re.test(phone.replace(/\s/g, ''));
    }

    toggleFieldError(field, hasError, message) {
        const wrapper = field.closest('.form-field') || field.parentNode;
        const errorElement = wrapper.querySelector('.field-error');

        if (hasError) {
            field.classList.add('error');
            if (!errorElement) {
                const error = document.createElement('span');
                error.className = 'field-error text-red-500 text-sm mt-1';
                error.textContent = message;
                wrapper.appendChild(error);
            } else {
                errorElement.textContent = message;
            }
        } else {
            field.classList.remove('error');
            if (errorElement) {
                errorElement.remove();
            }
        }
    }

    clearFieldErrors(field) {
        const wrapper = field.closest('.form-field') || field.parentNode;
        const errorElement = wrapper.querySelector('.field-error');
        
        field.classList.remove('error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    // Performance optimization methods
    preloadCriticalResources() {
        // Preload above-the-fold images
        const criticalImages = document.querySelectorAll('img[data-priority="high"]');
        criticalImages.forEach(img => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = img.src;
            document.head.appendChild(link);
        });
    }

    optimizeImages() {
        // Add loading="lazy" to images below the fold
        const images = document.querySelectorAll('img:not([loading])');
        images.forEach((img, index) => {
            if (index > 3) { // Skip first 4 images (likely above the fold)
                img.loading = 'lazy';
            }
        });
    }

    debounceEvents() {
        // Debounce scroll events
        let scrollTimeout;
        const originalScroll = window.onscroll;
        window.onscroll = (e) => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                if (originalScroll) originalScroll(e);
            }, 16);
        };

        // Debounce resize events
        let resizeTimeout;
        const originalResize = window.onresize;
        window.onresize = (e) => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (originalResize) originalResize(e);
            }, 250);
        };
    }

    monitorPerformance() {
        // Monitor Core Web Vitals
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    console.log(`${entry.name}: ${entry.value}`);
                });
            });

            observer.observe({ entryTypes: ['largest-contentful-paint', 'first-input', 'layout-shift'] });
        }
    }
}

// Initialize theme when DOM is ready
const aqualuxeTheme = new AquaLuxeTheme();

// Export for global access
window.AquaLuxeTheme = aqualuxeTheme;
