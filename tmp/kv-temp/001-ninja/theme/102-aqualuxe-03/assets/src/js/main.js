/**
 * AquaLuxe Main JavaScript
 * 
 * Main theme functionality and progressive enhancement.
 * Implements accessibility, mobile navigation, and core interactions.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Theme object
    const AquaLuxe = {
        
        /**
         * Initialize theme
         */
        init() {
            this.bindEvents();
            this.initAccessibility();
            this.initMobileMenu();
            this.initScrollEffects();
            this.initLazyLoading();
            this.initForms();
            this.handlePreferredColorScheme();
            
            // Mark as initialized
            document.body.classList.add('aqualuxe-initialized');
        },
        
        /**
         * Bind events
         */
        bindEvents() {
            // Window events
            $(window).on('scroll', this.throttle(this.handleScroll.bind(this), 16));
            $(window).on('resize', this.debounce(this.handleResize.bind(this), 250));
            $(window).on('load', this.handleWindowLoad.bind(this));
            
            // Document events
            $(document).on('keydown', this.handleKeydown.bind(this));
            $(document).on('click', '[data-toggle]', this.handleToggle.bind(this));
            $(document).on('click', '.js-smooth-scroll', this.handleSmoothScroll.bind(this));
            
            // Form events
            $(document).on('submit', 'form', this.handleFormSubmit.bind(this));
            $(document).on('focus', 'input, textarea, select', this.handleFormFocus.bind(this));
            $(document).on('blur', 'input, textarea, select', this.handleFormBlur.bind(this));
        },
        
        /**
         * Initialize accessibility features
         */
        initAccessibility() {
            // Skip links
            $('.skip-link').on('click', function(e) {
                e.preventDefault();
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    target.focus();
                    target.get(0).scrollIntoView();
                }
            });
            
            // Focus management for modals
            $(document).on('shown.bs.modal', '[role="dialog"]', function() {
                $(this).find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])').first().focus();
            });
            
            // Escape key handler for modals
            $(document).on('keydown', '[role="dialog"]', function(e) {
                if (e.key === 'Escape') {
                    $(this).find('[data-dismiss="modal"]').click();
                }
            });
            
            // Focus trap for mobile menu
            this.initFocusTrap();
        },
        
        /**
         * Initialize mobile menu
         */
        initMobileMenu() {
            const $mobileToggle = $('.mobile-menu-toggle');
            const $mobileMenu = $('.mobile-menu');
            const $body = $('body');
            
            $mobileToggle.on('click', function(e) {
                e.preventDefault();
                
                const isOpen = $mobileMenu.hasClass('is-open');
                
                if (isOpen) {
                    $mobileMenu.removeClass('is-open').attr('aria-hidden', 'true');
                    $mobileToggle.attr('aria-expanded', 'false');
                    $body.removeClass('mobile-menu-open');
                } else {
                    $mobileMenu.addClass('is-open').attr('aria-hidden', 'false');
                    $mobileToggle.attr('aria-expanded', 'true');
                    $body.addClass('mobile-menu-open');
                    
                    // Focus first menu item
                    $mobileMenu.find('a').first().focus();
                }
            });
            
            // Close menu on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $mobileMenu.hasClass('is-open')) {
                    $mobileToggle.click();
                    $mobileToggle.focus();
                }
            });
            
            // Close menu when clicking outside
            $(document).on('click', function(e) {
                if ($mobileMenu.hasClass('is-open') && 
                    !$mobileMenu.is(e.target) && 
                    $mobileMenu.has(e.target).length === 0 &&
                    !$mobileToggle.is(e.target)) {
                    $mobileToggle.click();
                }
            });
        },
        
        /**
         * Initialize scroll effects
         */
        initScrollEffects() {
            const $header = $('.site-header');
            
            this.handleScroll();
            
            // Scroll to top button
            const $scrollTop = $('.scroll-to-top');
            if ($scrollTop.length) {
                $scrollTop.on('click', function(e) {
                    e.preventDefault();
                    $('html, body').animate({ scrollTop: 0 }, 500);
                });
            }
        },
        
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
                            imageObserver.unobserve(img);
                        }
                    });
                });
                
                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            } else {
                // Fallback for older browsers
                $('img[data-src]').each(function() {
                    $(this).attr('src', $(this).data('src')).removeClass('lazy');
                });
            }
        },
        
        /**
         * Initialize forms
         */
        initForms() {
            // Add floating labels
            $('.form-floating input, .form-floating textarea').on('focus blur', function() {
                $(this).closest('.form-floating').toggleClass('focused', this.value !== '' || document.activeElement === this);
            });
            
            // Form validation
            $('form[data-validate]').each(function() {
                $(this).on('submit', AquaLuxe.validateForm.bind(AquaLuxe));
            });
        },
        
        /**
         * Handle scroll events
         */
        handleScroll() {
            const scrollTop = $(window).scrollTop();
            const $header = $('.site-header');
            
            // Sticky header
            if (scrollTop > 100) {
                $header.addClass('is-scrolled');
            } else {
                $header.removeClass('is-scrolled');
            }
            
            // Show/hide scroll to top button
            const $scrollTop = $('.scroll-to-top');
            if (scrollTop > 500) {
                $scrollTop.addClass('visible');
            } else {
                $scrollTop.removeClass('visible');
            }
            
            // Progress bar
            const $progressBar = $('.reading-progress');
            if ($progressBar.length) {
                const docHeight = $(document).height();
                const winHeight = $(window).height();
                const scrollPercent = (scrollTop / (docHeight - winHeight)) * 100;
                $progressBar.css('width', scrollPercent + '%');
            }
        },
        
        /**
         * Handle resize events
         */
        handleResize() {
            // Close mobile menu on resize to desktop
            if ($(window).width() >= 1024) {
                $('.mobile-menu').removeClass('is-open').attr('aria-hidden', 'true');
                $('.mobile-menu-toggle').attr('aria-expanded', 'false');
                $('body').removeClass('mobile-menu-open');
            }
        },
        
        /**
         * Handle window load
         */
        handleWindowLoad() {
            // Remove loading classes
            $('body').removeClass('is-loading');
            
            // Initialize animations
            this.initAnimations();
        },
        
        /**
         * Handle keydown events
         */
        handleKeydown(e) {
            // Handle tab navigation in mobile menu
            if ($('.mobile-menu').hasClass('is-open')) {
                this.handleMobileMenuTab(e);
            }
        },
        
        /**
         * Handle toggle elements
         */
        handleToggle(e) {
            e.preventDefault();
            
            const $toggle = $(e.currentTarget);
            const target = $toggle.data('target') || $toggle.attr('href');
            const $target = $(target);
            
            if ($target.length) {
                const isExpanded = $toggle.attr('aria-expanded') === 'true';
                
                $toggle.attr('aria-expanded', !isExpanded);
                $target.toggleClass('is-open').attr('aria-hidden', isExpanded);
                
                if (!isExpanded) {
                    $target.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])').first().focus();
                }
            }
        },
        
        /**
         * Handle smooth scroll
         */
        handleSmoothScroll(e) {
            e.preventDefault();
            
            const target = e.currentTarget.getAttribute('href');
            const $target = $(target);
            
            if ($target.length) {
                const offset = $('.site-header').outerHeight() || 0;
                $('html, body').animate({
                    scrollTop: $target.offset().top - offset
                }, 500);
            }
        },
        
        /**
         * Handle form submission
         */
        handleFormSubmit(e) {
            const $form = $(e.target);
            
            if ($form.data('validate')) {
                if (!this.validateForm.call(this, e)) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Show loading state
            $form.addClass('is-loading');
            $form.find('[type="submit"]').prop('disabled', true);
        },
        
        /**
         * Handle form focus
         */
        handleFormFocus(e) {
            $(e.target).closest('.form-group').addClass('focused');
        },
        
        /**
         * Handle form blur
         */
        handleFormBlur(e) {
            const $input = $(e.target);
            const $group = $input.closest('.form-group');
            
            if ($input.val() === '') {
                $group.removeClass('focused');
            }
            
            // Validate field
            this.validateField($input);
        },
        
        /**
         * Validate form
         */
        validateForm(e) {
            const $form = $(e.target);
            let isValid = true;
            
            $form.find('[required]').each(function() {
                if (!AquaLuxe.validateField($(this))) {
                    isValid = false;
                }
            });
            
            return isValid;
        },
        
        /**
         * Validate field
         */
        validateField($field) {
            const value = $field.val();
            const type = $field.attr('type');
            const $group = $field.closest('.form-group');
            let isValid = true;
            let message = '';
            
            // Required validation
            if ($field.prop('required') && !value) {
                isValid = false;
                message = 'This field is required.';
            }
            
            // Email validation
            if (type === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                isValid = false;
                message = 'Please enter a valid email address.';
            }
            
            // Update UI
            $group.toggleClass('has-error', !isValid);
            $group.find('.error-message').remove();
            
            if (!isValid && message) {
                $group.append(`<span class="error-message text-red-600 text-sm mt-1">${message}</span>`);
            }
            
            return isValid;
        },
        
        /**
         * Initialize animations
         */
        initAnimations() {
            // Intersection Observer for animations
            if ('IntersectionObserver' in window) {
                const animationObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in-view');
                        }
                    });
                }, { threshold: 0.1 });
                
                document.querySelectorAll('.animate-on-scroll').forEach(el => {
                    animationObserver.observe(el);
                });
            }
        },
        
        /**
         * Handle mobile menu tab navigation
         */
        handleMobileMenuTab(e) {
            if (e.key !== 'Tab') return;
            
            const $menu = $('.mobile-menu');
            const focusableElements = $menu.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const firstElement = focusableElements.first();
            const lastElement = focusableElements.last();
            
            if (e.shiftKey) {
                if (document.activeElement === firstElement[0]) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement[0]) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        },
        
        /**
         * Initialize focus trap
         */
        initFocusTrap() {
            // This would typically use a library like focus-trap
            // For now, we'll implement basic tab trapping in handleMobileMenuTab
        },
        
        /**
         * Handle preferred color scheme
         */
        handlePreferredColorScheme() {
            // Check for saved theme preference or default to OS preference
            const savedTheme = localStorage.getItem('aqualuxe-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme) {
                document.documentElement.classList.toggle('dark', savedTheme === 'dark');
            } else if (prefersDark) {
                document.documentElement.classList.add('dark');
            }
            
            // Listen for changes in OS preference
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('aqualuxe-theme')) {
                    document.documentElement.classList.toggle('dark', e.matches);
                }
            });
        },
        
        /**
         * Utility: Throttle function
         */
        throttle(func, delay) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, delay);
                }
            };
        },
        
        /**
         * Utility: Debounce function
         */
        debounce(func, delay) {
            let debounceTimer;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            };
        }
    };
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        AquaLuxe.init();
    });
    
    // Expose to global scope
    window.AquaLuxe = AquaLuxe;
    
})(jQuery);