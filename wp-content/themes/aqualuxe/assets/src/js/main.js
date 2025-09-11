/**
 * AquaLuxe Main JavaScript
 * 
 * Core functionality for the AquaLuxe theme
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Theme Object
     */
    const AquaLuxe = {
        
        /**
         * Initialize the theme
         */
        init: function() {
            this.initMobileMenu();
            this.initScrollEffects();
            this.initSmoothScrolling();
            this.initLazyLoading();
            this.initAccessibility();
            this.initAOS();
            this.initTooltips();
            this.initModals();
            this.initFormValidation();
            this.initSearchToggle();
            this.initBackToTop();
        },

        /**
         * Initialize mobile menu
         */
        initMobileMenu: function() {
            const mobileMenuToggle = $('.mobile-menu-toggle');
            const mobileMenu = $('.mobile-menu');
            const mobileMenuOverlay = $('.mobile-menu-overlay');
            const mobileMenuClose = $('.mobile-menu-close');

            mobileMenuToggle.on('click', function(e) {
                e.preventDefault();
                mobileMenu.addClass('open');
                mobileMenuOverlay.addClass('open');
                $('body').addClass('overflow-hidden');
                
                // Trap focus in mobile menu
                mobileMenu.find('a, button').first().focus();
            });

            mobileMenuClose.add(mobileMenuOverlay).on('click', function(e) {
                e.preventDefault();
                mobileMenu.removeClass('open');
                mobileMenuOverlay.removeClass('open');
                $('body').removeClass('overflow-hidden');
                mobileMenuToggle.focus();
            });

            // Close menu with escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && mobileMenu.hasClass('open')) {
                    mobileMenu.removeClass('open');
                    mobileMenuOverlay.removeClass('open');
                    $('body').removeClass('overflow-hidden');
                    mobileMenuToggle.focus();
                }
            });

            // Handle submenu toggles
            $('.mobile-menu .menu-item-has-children > a').on('click', function(e) {
                e.preventDefault();
                const $this = $(this);
                const submenu = $this.siblings('.sub-menu');
                
                $this.parent().toggleClass('open');
                submenu.slideToggle(300);
            });
        },

        /**
         * Initialize scroll effects
         */
        initScrollEffects: function() {
            const header = $('.site-header');
            let lastScrollTop = 0;

            $(window).on('scroll', function() {
                const scrollTop = $(this).scrollTop();
                
                // Add scrolled class to header
                if (scrollTop > 100) {
                    header.addClass('scrolled');
                } else {
                    header.removeClass('scrolled');
                }

                // Hide/show header on scroll
                if (scrollTop > lastScrollTop && scrollTop > 200) {
                    header.addClass('header-hidden');
                } else {
                    header.removeClass('header-hidden');
                }
                
                lastScrollTop = scrollTop;
            });
        },

        /**
         * Initialize smooth scrolling
         */
        initSmoothScrolling: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function(e) {
                const target = $(this.hash);
                
                if (target.length) {
                    e.preventDefault();
                    
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 800, 'easeInOutCubic');
                }
            });
        },

        /**
         * Initialize lazy loading for images
         */
        initLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            img.classList.add('lazy-loaded');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                $('.lazy').each(function() {
                    imageObserver.observe(this);
                });
            } else {
                // Fallback for older browsers
                $('.lazy').each(function() {
                    const $this = $(this);
                    $this.attr('src', $this.data('src'));
                    $this.removeClass('lazy').addClass('lazy-loaded');
                });
            }
        },

        /**
         * Initialize accessibility features
         */
        initAccessibility: function() {
            // Skip to content link
            $('body').prepend('<a class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 bg-primary-600 text-white px-4 py-2 z-50" href="#main-content">Skip to content</a>');

            // Add ARIA labels to menu items with children
            $('.menu-item-has-children > a').attr('aria-haspopup', 'true').attr('aria-expanded', 'false');

            // Handle keyboard navigation for dropdowns
            $('.menu-item-has-children').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const $this = $(this);
                    const isExpanded = $this.children('a').attr('aria-expanded') === 'true';
                    
                    $this.children('a').attr('aria-expanded', !isExpanded);
                    $this.children('.sub-menu').slideToggle(300);
                }
            });

            // Announce dynamic content changes
            this.announceContentChanges();
        },

        /**
         * Announce content changes to screen readers
         */
        announceContentChanges: function() {
            // Create live region for announcements
            if (!$('#aria-live-region').length) {
                $('body').append('<div id="aria-live-region" aria-live="polite" aria-atomic="true" class="sr-only"></div>');
            }
        },

        /**
         * Announce message to screen readers
         */
        announce: function(message) {
            const liveRegion = $('#aria-live-region');
            liveRegion.text(message);
            
            // Clear after a delay
            setTimeout(() => {
                liveRegion.text('');
            }, 1000);
        },

        /**
         * Initialize AOS (Animate On Scroll)
         */
        initAOS: function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true,
                    offset: 100,
                    disable: function() {
                        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                    }
                });
            }
        },

        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            $('[data-tooltip]').each(function() {
                const $this = $(this);
                const tooltip = $this.data('tooltip');
                
                $this.on('mouseenter focus', function() {
                    if (!$this.data('tooltip-created')) {
                        const tooltipEl = $('<div class="tooltip absolute z-50 px-2 py-1 text-sm text-white bg-gray-900 rounded shadow-lg pointer-events-none"></div>');
                        tooltipEl.text(tooltip);
                        $('body').append(tooltipEl);
                        $this.data('tooltip-element', tooltipEl);
                        $this.data('tooltip-created', true);
                    }
                    
                    const tooltipEl = $this.data('tooltip-element');
                    const rect = this.getBoundingClientRect();
                    
                    tooltipEl.css({
                        top: rect.top - tooltipEl.outerHeight() - 5,
                        left: rect.left + (rect.width / 2) - (tooltipEl.outerWidth() / 2)
                    }).addClass('opacity-100');
                });
                
                $this.on('mouseleave blur', function() {
                    const tooltipEl = $this.data('tooltip-element');
                    if (tooltipEl) {
                        tooltipEl.removeClass('opacity-100');
                    }
                });
            });
        },

        /**
         * Initialize modals
         */
        initModals: function() {
            // Modal triggers
            $('[data-modal]').on('click', function(e) {
                e.preventDefault();
                const modalId = $(this).data('modal');
                const modal = $('#' + modalId);
                
                if (modal.length) {
                    modal.addClass('open');
                    $('body').addClass('overflow-hidden');
                    
                    // Focus first focusable element
                    modal.find('input, button, select, textarea, a[href]').first().focus();
                }
            });

            // Modal close
            $('.modal-close, .modal-overlay').on('click', function(e) {
                e.preventDefault();
                const modal = $(this).closest('.modal');
                modal.removeClass('open');
                $('body').removeClass('overflow-hidden');
            });

            // Close modal with escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('.modal.open').removeClass('open');
                    $('body').removeClass('overflow-hidden');
                }
            });
        },

        /**
         * Initialize form validation
         */
        initFormValidation: function() {
            $('form[data-validate]').each(function() {
                const form = $(this);
                
                form.on('submit', function(e) {
                    let isValid = true;
                    
                    // Check required fields
                    form.find('[required]').each(function() {
                        const field = $(this);
                        const value = field.val().trim();
                        
                        if (!value) {
                            isValid = false;
                            field.addClass('error');
                            field.attr('aria-invalid', 'true');
                        } else {
                            field.removeClass('error');
                            field.attr('aria-invalid', 'false');
                        }
                    });
                    
                    // Email validation
                    form.find('input[type="email"]').each(function() {
                        const field = $(this);
                        const email = field.val().trim();
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        
                        if (email && !emailRegex.test(email)) {
                            isValid = false;
                            field.addClass('error');
                            field.attr('aria-invalid', 'true');
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        const firstError = form.find('.error').first();
                        firstError.focus();
                        AquaLuxe.announce('Please correct the errors in the form');
                    }
                });
                
                // Real-time validation
                form.find('input, textarea, select').on('blur', function() {
                    const field = $(this);
                    const value = field.val().trim();
                    
                    if (field.prop('required') && !value) {
                        field.addClass('error');
                        field.attr('aria-invalid', 'true');
                    } else {
                        field.removeClass('error');
                        field.attr('aria-invalid', 'false');
                    }
                });
            });
        },

        /**
         * Initialize search toggle
         */
        initSearchToggle: function() {
            const searchToggle = $('.search-toggle');
            const searchForm = $('.search-form-modal');
            
            searchToggle.on('click', function(e) {
                e.preventDefault();
                searchForm.addClass('open');
                searchForm.find('input[type="search"]').focus();
            });
            
            $('.search-close, .search-overlay').on('click', function(e) {
                e.preventDefault();
                searchForm.removeClass('open');
            });
        },

        /**
         * Initialize back to top button
         */
        initBackToTop: function() {
            const backToTop = $('.back-to-top');
            
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 500) {
                    backToTop.addClass('visible');
                } else {
                    backToTop.removeClass('visible');
                }
            });
            
            backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
            });
        },

        /**
         * Utility function to check if element is in viewport
         */
        isInViewport: function(element) {
            const rect = element[0].getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        /**
         * Debounce function
         */
        debounce: function(func, wait, immediate) {
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
    };

    /**
     * Initialize theme when document is ready
     */
    $(document).ready(function() {
        AquaLuxe.init();
    });

    /**
     * Make AquaLuxe object globally available
     */
    window.AquaLuxe = AquaLuxe;

})(jQuery);