import Alpine from 'alpinejs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Register Swiper modules
Swiper.use([Navigation, Pagination, Autoplay]);

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine.js
Alpine.start();

/**
 * Main Theme JavaScript
 */
class AquaLuxeTheme {
    constructor() {
        this.init();
    }

    init() {
        this.initScrollAnimations();
        this.initMobileMenu();
        this.initSearch();
        this.initSmoothScroll();
        this.initLazyLoading();
        this.initFormValidation();
        this.initTooltips();
        this.initNewsletterForm();
        this.initScrollToTop();
        this.initThemeMode();
        this.initSliders();
        this.initParallax();
        this.initCounters();
        this.initImageLightbox();
        this.initAjaxForms();
        this.initPreloader();
    }

    /**
     * Initialize scroll animations
     */
    initScrollAnimations() {
        // Fade in elements
        gsap.fromTo('.fade-in', {
            opacity: 0,
            y: 30
        }, {
            opacity: 1,
            y: 0,
            duration: 0.8,
            stagger: 0.2,
            scrollTrigger: {
                trigger: '.fade-in',
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reverse'
            }
        });

        // Slide up animations
        gsap.fromTo('.slide-up', {
            opacity: 0,
            y: 50
        }, {
            opacity: 1,
            y: 0,
            duration: 1,
            stagger: 0.3,
            scrollTrigger: {
                trigger: '.slide-up',
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            }
        });

        // Scale animations
        gsap.fromTo('.scale-in', {
            opacity: 0,
            scale: 0.8
        }, {
            opacity: 1,
            scale: 1,
            duration: 0.6,
            scrollTrigger: {
                trigger: '.scale-in',
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            }
        });
    }

    /**
     * Initialize mobile menu
     */
    initMobileMenu() {
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', () => {
                const isOpen = mobileMenu.classList.contains('hidden');
                
                if (isOpen) {
                    mobileMenu.classList.remove('hidden');
                    mobileToggle.setAttribute('aria-expanded', 'true');
                    document.body.classList.add('mobile-menu-open');
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileToggle.setAttribute('aria-expanded', 'false');
                    document.body.classList.remove('mobile-menu-open');
                }
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!mobileMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                    mobileToggle.setAttribute('aria-expanded', 'false');
                    document.body.classList.remove('mobile-menu-open');
                }
            });
        }
    }

    /**
     * Initialize search functionality
     */
    initSearch() {
        const searchToggle = document.querySelector('.search-toggle');
        const searchOverlay = document.querySelector('.search-overlay');
        const searchClose = document.querySelector('.search-close');
        const searchInput = document.querySelector('.search-overlay input[type="search"]');

        if (searchToggle && searchOverlay) {
            searchToggle.addEventListener('click', () => {
                searchOverlay.classList.remove('hidden');
                searchInput?.focus();
                document.body.classList.add('search-open');
            });

            if (searchClose) {
                searchClose.addEventListener('click', () => {
                    searchOverlay.classList.add('hidden');
                    document.body.classList.remove('search-open');
                });
            }

            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !searchOverlay.classList.contains('hidden')) {
                    searchOverlay.classList.add('hidden');
                    document.body.classList.remove('search-open');
                }
            });

            // Close on outside click
            searchOverlay?.addEventListener('click', (e) => {
                if (e.target === searchOverlay) {
                    searchOverlay.classList.add('hidden');
                    document.body.classList.remove('search-open');
                }
            });
        }
    }

    /**
     * Initialize smooth scrolling
     */
    initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                const target = document.querySelector(link.getAttribute('href'));
                
                if (target) {
                    e.preventDefault();
                    
                    const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Initialize lazy loading
     */
    initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy-loading');
                        img.classList.add('lazy-loaded');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                img.classList.add('lazy-loading');
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Initialize form validation
     */
    initFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });

            // Real-time validation
            form.querySelectorAll('input, textarea, select').forEach(field => {
                field.addEventListener('blur', () => {
                    this.validateField(field);
                });
            });
        });
    }

    /**
     * Validate a form
     */
    validateForm(form) {
        let isValid = true;
        const fields = form.querySelectorAll('input, textarea, select');
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * Validate a field
     */
    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        const required = field.hasAttribute('required');
        let isValid = true;
        let message = '';

        // Remove previous error styling
        field.classList.remove('border-red-500', 'ring-red-500');
        this.removeErrorMessage(field);

        // Required field validation
        if (required && !value) {
            isValid = false;
            message = 'This field is required.';
        }

        // Email validation
        if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                message = 'Please enter a valid email address.';
            }
        }

        // Phone validation
        if (type === 'tel' && value) {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            if (!phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''))) {
                isValid = false;
                message = 'Please enter a valid phone number.';
            }
        }

        // URL validation
        if (type === 'url' && value) {
            try {
                new URL(value);
            } catch {
                isValid = false;
                message = 'Please enter a valid URL.';
            }
        }

        if (!isValid) {
            field.classList.add('border-red-500', 'ring-red-500');
            this.showErrorMessage(field, message);
        }

        return isValid;
    }

    /**
     * Show error message
     */
    showErrorMessage(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-sm mt-1';
        errorDiv.textContent = message;
        errorDiv.setAttribute('data-error-for', field.name);
        
        field.parentNode.appendChild(errorDiv);
    }

    /**
     * Remove error message
     */
    removeErrorMessage(field) {
        const errorDiv = field.parentNode.querySelector(`[data-error-for="${field.name}"]`);
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    /**
     * Initialize tooltips
     */
    initTooltips() {
        const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
        
        tooltipTriggers.forEach(trigger => {
            let tooltip;
            
            trigger.addEventListener('mouseenter', () => {
                const text = trigger.getAttribute('data-tooltip');
                tooltip = this.createTooltip(text);
                document.body.appendChild(tooltip);
                this.positionTooltip(trigger, tooltip);
            });

            trigger.addEventListener('mouseleave', () => {
                if (tooltip) {
                    tooltip.remove();
                    tooltip = null;
                }
            });
        });
    }

    /**
     * Create tooltip element
     */
    createTooltip(text) {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip fixed z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg pointer-events-none';
        tooltip.textContent = text;
        return tooltip;
    }

    /**
     * Position tooltip
     */
    positionTooltip(trigger, tooltip) {
        const triggerRect = trigger.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();
        
        let top = triggerRect.bottom + window.scrollY + 8;
        let left = triggerRect.left + window.scrollX + (triggerRect.width - tooltipRect.width) / 2;

        // Keep tooltip within viewport
        if (left < 8) left = 8;
        if (left + tooltipRect.width > window.innerWidth - 8) {
            left = window.innerWidth - tooltipRect.width - 8;
        }

        tooltip.style.top = `${top}px`;
        tooltip.style.left = `${left}px`;
    }

    /**
     * Initialize newsletter form
     */
    initNewsletterForm() {
        const forms = document.querySelectorAll('.newsletter-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(form);
                const button = form.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                
                // Show loading state
                button.textContent = 'Subscribing...';
                button.disabled = true;
                
                try {
                    const response = await fetch(aqualuxe.ajax_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'aqualuxe_newsletter_subscribe',
                            nonce: aqualuxe.nonce,
                            email: formData.get('email')
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        this.showNotification('Successfully subscribed!', 'success');
                        form.reset();
                    } else {
                        this.showNotification(result.data.message || 'Subscription failed.', 'error');
                    }
                } catch (error) {
                    this.showNotification('An error occurred. Please try again.', 'error');
                } finally {
                    button.textContent = originalText;
                    button.disabled = false;
                }
            });
        });
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white max-w-sm transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' :
            'bg-blue-500'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    /**
     * Initialize scroll to top
     */
    initScrollToTop() {
        const button = document.querySelector('.back-to-top');
        
        if (button) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    button.classList.remove('translate-y-16', 'opacity-0');
                    button.classList.add('translate-y-0', 'opacity-100');
                } else {
                    button.classList.add('translate-y-16', 'opacity-0');
                    button.classList.remove('translate-y-0', 'opacity-100');
                }
            });
        }
    }

    /**
     * Initialize theme mode
     */
    initThemeMode() {
        // Check for saved theme preference or default to 'light'
        const currentTheme = localStorage.getItem('darkMode');
        
        if (currentTheme === 'true' || (!currentTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    }

    /**
     * Initialize sliders
     */
    initSliders() {
        // Hero slider
        const heroSlider = document.querySelector('.hero-slider');
        if (heroSlider) {
            new Swiper(heroSlider, {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                }
            });
        }

        // Product sliders
        const productSliders = document.querySelectorAll('.product-slider');
        productSliders.forEach(slider => {
            new Swiper(slider, {
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 3,
                    },
                    1024: {
                        slidesPerView: 4,
                    },
                }
            });
        });

        // Testimonial slider
        const testimonialSlider = document.querySelector('.testimonial-slider');
        if (testimonialSlider) {
            new Swiper(testimonialSlider, {
                slidesPerView: 1,
                spaceBetween: 30,
                autoplay: {
                    delay: 6000,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                }
            });
        }
    }

    /**
     * Initialize parallax effects
     */
    initParallax() {
        const parallaxElements = document.querySelectorAll('.parallax');
        
        parallaxElements.forEach(element => {
            gsap.fromTo(element, {
                yPercent: -50
            }, {
                yPercent: 50,
                ease: 'none',
                scrollTrigger: {
                    trigger: element,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });
    }

    /**
     * Initialize counters
     */
    initCounters() {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = parseInt(counter.getAttribute('data-duration')) || 2000;
            
            ScrollTrigger.create({
                trigger: counter,
                start: 'top 80%',
                onEnter: () => {
                    gsap.fromTo(counter, {
                        textContent: 0
                    }, {
                        textContent: target,
                        duration: duration / 1000,
                        ease: 'power2.out',
                        snap: { textContent: 1 },
                        stagger: 0.2
                    });
                }
            });
        });
    }

    /**
     * Initialize image lightbox
     */
    initImageLightbox() {
        const lightboxImages = document.querySelectorAll('.lightbox-image');
        
        lightboxImages.forEach(image => {
            image.addEventListener('click', (e) => {
                e.preventDefault();
                this.openLightbox(image.src, image.alt);
            });
        });
    }

    /**
     * Open lightbox
     */
    openLightbox(src, alt) {
        const lightbox = document.createElement('div');
        lightbox.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4';
        lightbox.innerHTML = `
            <div class="relative max-w-full max-h-full">
                <img src="${src}" alt="${alt}" class="max-w-full max-h-full object-contain">
                <button class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(lightbox);
        
        // Close on click
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox || e.target.tagName === 'BUTTON' || e.target.tagName === 'SVG' || e.target.tagName === 'PATH') {
                lightbox.remove();
                document.body.classList.remove('lightbox-open');
            }
        });
        
        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                lightbox.remove();
                document.body.classList.remove('lightbox-open');
            }
        });
        
        document.body.classList.add('lightbox-open');
    }

    /**
     * Initialize AJAX forms
     */
    initAjaxForms() {
        const ajaxForms = document.querySelectorAll('form[data-ajax]');
        
        ajaxForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(form);
                const button = form.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                
                // Show loading state
                button.textContent = button.getAttribute('data-loading-text') || 'Processing...';
                button.disabled = true;
                
                try {
                    const response = await fetch(form.action || aqualuxe.ajax_url, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        this.showNotification(result.data.message || 'Form submitted successfully!', 'success');
                        if (form.getAttribute('data-reset') !== 'false') {
                            form.reset();
                        }
                    } else {
                        this.showNotification(result.data.message || 'Form submission failed.', 'error');
                    }
                } catch (error) {
                    this.showNotification('An error occurred. Please try again.', 'error');
                } finally {
                    button.textContent = originalText;
                    button.disabled = false;
                }
            });
        });
    }

    /**
     * Initialize preloader
     */
    initPreloader() {
        const preloader = document.querySelector('.preloader');
        
        if (preloader) {
            window.addEventListener('load', () => {
                gsap.to(preloader, {
                    opacity: 0,
                    duration: 0.5,
                    onComplete: () => {
                        preloader.style.display = 'none';
                    }
                });
            });
        }
    }
}

// Initialize theme when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new AquaLuxeTheme();
});

// Handle resize events
let resizeTimeout;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        ScrollTrigger.refresh();
    }, 250);
});