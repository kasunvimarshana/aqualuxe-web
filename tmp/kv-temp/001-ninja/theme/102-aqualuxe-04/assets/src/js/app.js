/**
 * AquaLuxe Main JavaScript
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Import modules
import './modules/navigation';
import './modules/search';
import './modules/accessibility';
import './modules/performance';

(function($) {
    'use strict';

    /**
     * AquaLuxe Theme Class
     */
    class AquaLuxeTheme {
        constructor() {
            this.init();
        }

        /**
         * Initialize theme functionality
         */
        init() {
            this.bindEvents();
            this.initComponents();
            this.loadAccessibilityFeatures();
        }

        /**
         * Bind event handlers
         */
        bindEvents() {
            $(document).ready(() => {
                this.onDocumentReady();
            });

            $(window).on('load', () => {
                this.onWindowLoad();
            });

            $(window).on('scroll', this.debounce(() => {
                this.onScroll();
            }, 100));

            $(window).on('resize', this.debounce(() => {
                this.onResize();
            }, 250));
        }

        /**
         * Document ready handler
         */
        onDocumentReady() {
            this.initMobileMenu();
            this.initSearchToggle();
            this.initBackToTop();
            this.initSmoothScroll();
            this.initLazyLoading();
        }

        /**
         * Window load handler
         */
        onWindowLoad() {
            this.removePreloader();
            this.initAnimations();
        }

        /**
         * Scroll handler
         */
        onScroll() {
            this.handleHeaderScroll();
            this.handleBackToTop();
        }

        /**
         * Resize handler
         */
        onResize() {
            this.handleMobileMenuResize();
        }

        /**
         * Initialize mobile menu
         */
        initMobileMenu() {
            const $menuToggle = $('#mobile-menu-toggle');
            const $mobileMenu = $('#mobile-menu');

            $menuToggle.on('click', (e) => {
                e.preventDefault();
                $mobileMenu.toggleClass('hidden');
                $menuToggle.attr('aria-expanded', !$mobileMenu.hasClass('hidden'));
            });

            // Close menu when clicking outside
            $(document).on('click', (e) => {
                if (!$(e.target).closest('#mobile-menu, #mobile-menu-toggle').length) {
                    $mobileMenu.addClass('hidden');
                    $menuToggle.attr('aria-expanded', 'false');
                }
            });
        }

        /**
         * Initialize search toggle
         */
        initSearchToggle() {
            const $searchToggle = $('#search-toggle');
            const $searchOverlay = $('#search-overlay');

            $searchToggle.on('click', (e) => {
                e.preventDefault();
                $searchOverlay.toggleClass('hidden');
                
                if (!$searchOverlay.hasClass('hidden')) {
                    $searchOverlay.find('input[type="search"]').focus();
                }
            });

            // Close search when clicking outside
            $(document).on('click', (e) => {
                if (!$(e.target).closest('#search-overlay, #search-toggle').length) {
                    $searchOverlay.addClass('hidden');
                }
            });

            // Close search on escape key
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && !$searchOverlay.hasClass('hidden')) {
                    $searchOverlay.addClass('hidden');
                    $searchToggle.focus();
                }
            });
        }

        /**
         * Initialize back to top button
         */
        initBackToTop() {
            const $backToTop = $('#back-to-top');

            $backToTop.on('click', (e) => {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 600);
            });
        }

        /**
         * Handle back to top visibility
         */
        handleBackToTop() {
            const $backToTop = $('#back-to-top');
            const scrollTop = $(window).scrollTop();

            if (scrollTop > 300) {
                $backToTop.removeClass('opacity-0 pointer-events-none');
            } else {
                $backToTop.addClass('opacity-0 pointer-events-none');
            }
        }

        /**
         * Initialize smooth scrolling
         */
        initSmoothScroll() {
            $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').on('click', function(e) {
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                    location.hostname === this.hostname) {
                    
                    let target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    
                    if (target.length) {
                        e.preventDefault();
                        $('html, body').animate({
                            scrollTop: target.offset().top - 100
                        }, 600);
                    }
                }
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
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        }

        /**
         * Handle header scroll effects
         */
        handleHeaderScroll() {
            const $header = $('#masthead');
            const scrollTop = $(window).scrollTop();

            if (scrollTop > 100) {
                $header.addClass('scrolled');
            } else {
                $header.removeClass('scrolled');
            }
        }

        /**
         * Handle mobile menu on resize
         */
        handleMobileMenuResize() {
            const $mobileMenu = $('#mobile-menu');
            const $menuToggle = $('#mobile-menu-toggle');

            if ($(window).width() >= 1024) {
                $mobileMenu.addClass('hidden');
                $menuToggle.attr('aria-expanded', 'false');
            }
        }

        /**
         * Initialize components
         */
        initComponents() {
            // Initialize tooltips
            $('[data-tooltip]').each(function() {
                $(this).attr('title', $(this).data('tooltip'));
            });

            // Initialize accordions
            $('.accordion-toggle').on('click', function(e) {
                e.preventDefault();
                const $content = $(this).next('.accordion-content');
                $content.slideToggle();
                $(this).toggleClass('active');
            });

            // Initialize tabs
            $('.tab-nav a').on('click', function(e) {
                e.preventDefault();
                const target = $(this).attr('href');
                
                $(this).parent().siblings().find('a').removeClass('active');
                $(this).addClass('active');
                
                $(target).siblings('.tab-pane').removeClass('active');
                $(target).addClass('active');
            });
        }

        /**
         * Load accessibility features
         */
        loadAccessibilityFeatures() {
            // Skip links
            $('.skip-link').on('click', function() {
                const target = $($(this).attr('href'));
                if (target.length) {
                    target.focus();
                }
            });

            // Focus management for modals
            $(document).on('keydown', this.handleModalKeyboard.bind(this));
        }

        /**
         * Handle keyboard navigation for modals
         */
        handleModalKeyboard(e) {
            if (e.key === 'Escape') {
                $('.modal.active').removeClass('active');
            }

            if (e.key === 'Tab') {
                this.trapFocus(e);
            }
        }

        /**
         * Trap focus within modal
         */
        trapFocus(e) {
            const $modal = $('.modal.active');
            if (!$modal.length) return;

            const focusableElements = $modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const firstElement = focusableElements.first();
            const lastElement = focusableElements.last();

            if (e.shiftKey) {
                if (document.activeElement === firstElement[0]) {
                    lastElement.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastElement[0]) {
                    firstElement.focus();
                    e.preventDefault();
                }
            }
        }

        /**
         * Remove preloader
         */
        removePreloader() {
            $('.preloader').fadeOut(500, function() {
                $(this).remove();
            });
        }

        /**
         * Initialize animations
         */
        initAnimations() {
            // Fade in elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        }

        /**
         * Debounce function
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
         * Throttle function
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
    }

    // Initialize theme
    window.AquaLuxeTheme = new AquaLuxeTheme();

    // Make utilities available globally
    window.aqualuxeUtils = {
        debounce: window.AquaLuxeTheme.debounce,
        throttle: window.AquaLuxeTheme.throttle
    };

})(jQuery);