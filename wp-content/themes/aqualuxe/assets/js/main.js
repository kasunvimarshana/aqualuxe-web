/**
 * KV Enterprise Theme - Main JavaScript
 * 
 * Main JavaScript functionality for the enterprise theme
 * Implements progressive enhancement, AJAX handling, and accessibility features
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Theme Object
     * Main theme functionality namespace
     */
    const KVEnterprise = {
        
        /**
         * Configuration
         */
        config: {
            // Localized from WordPress
            ajaxUrl: kvEnterprise.ajaxUrl || '',
            nonce: kvEnterprise.nonce || '',
            textDomain: kvEnterprise.textDomain || 'kv-enterprise',
            isRTL: kvEnterprise.isRTL || false,
            breakpoints: kvEnterprise.breakpoints || {
                sm: 576,
                md: 768,
                lg: 992,
                xl: 1200,
                '2xl': 1400
            },
            // Default settings
            lazyLoadOffset: 200,
            scrollThrottle: 100,
            resizeThrottle: 250,
            animationDuration: 300
        },

        /**
         * State management
         */
        state: {
            isLoaded: false,
            isMobile: false,
            isTablet: false,
            currentBreakpoint: '',
            menuOpen: false,
            modalsOpen: 0,
            scrollPosition: 0,
            windowWidth: 0,
            windowHeight: 0
        },

        /**
         * Cache for DOM elements
         */
        cache: {
            $window: null,
            $document: null,
            $body: null,
            $header: null,
            $main: null,
            $footer: null,
            $navigation: null,
            $mobileMenuToggle: null,
            $searchForm: null,
            $backToTop: null
        },

        /**
         * Initialize theme
         */
        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.detectDevice();
            this.initComponents();
            this.state.isLoaded = true;
            
            // Trigger custom event
            this.cache.$document.trigger('kvThemeLoaded');
            
            console.log('KV Enterprise Theme initialized');
        },

        /**
         * Cache DOM elements
         */
        cacheElements: function() {
            this.cache.$window = $(window);
            this.cache.$document = $(document);
            this.cache.$body = $('body');
            this.cache.$header = $('header');
            this.cache.$main = $('main');
            this.cache.$footer = $('footer');
            this.cache.$navigation = $('.main-navigation');
            this.cache.$mobileMenuToggle = $('.mobile-menu-toggle');
            this.cache.$searchForm = $('.search-form');
            this.cache.$backToTop = $('.back-to-top');
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            const self = this;

            // Window events
            this.cache.$window
                .on('resize.kvTheme', this.throttle(this.handleResize.bind(this), this.config.resizeThrottle))
                .on('scroll.kvTheme', this.throttle(this.handleScroll.bind(this), this.config.scrollThrottle))
                .on('orientationchange.kvTheme', this.handleOrientationChange.bind(this));

            // Document events
            this.cache.$document
                .on('click.kvTheme', '.mobile-menu-toggle', this.toggleMobileMenu.bind(this))
                .on('click.kvTheme', '.back-to-top', this.scrollToTop.bind(this))
                .on('click.kvTheme', '.modal-trigger', this.openModal.bind(this))
                .on('click.kvTheme', '.modal-close, .modal-backdrop', this.closeModal.bind(this))
                .on('click.kvTheme', '.accordion-toggle', this.toggleAccordion.bind(this))
                .on('click.kvTheme', '.tab-trigger', this.switchTab.bind(this))
                .on('submit.kvTheme', '.ajax-form', this.handleAjaxForm.bind(this))
                .on('keydown.kvTheme', this.handleKeyboardNavigation.bind(this));

            // Intersection Observer for animations
            if ('IntersectionObserver' in window) {
                this.initIntersectionObserver();
            }

            // Handle AJAX complete
            this.cache.$document.ajaxComplete(function(event, xhr, settings) {
                self.initDynamicContent();
            });
        },

        /**
         * Handle window resize
         */
        handleResize: function() {
            this.updateWindowDimensions();
            this.detectDevice();
            this.adjustLayoutForBreakpoint();
        },

        /**
         * Handle window scroll
         */
        handleScroll: function() {
            this.state.scrollPosition = this.cache.$window.scrollTop();
            this.toggleBackToTop();
            this.updateHeaderOnScroll();
            this.handleParallax();
        },

        /**
         * Handle orientation change
         */
        handleOrientationChange: function() {
            setTimeout(() => {
                this.updateWindowDimensions();
                this.detectDevice();
            }, 100);
        },

        /**
         * Update window dimensions
         */
        updateWindowDimensions: function() {
            this.state.windowWidth = this.cache.$window.width();
            this.state.windowHeight = this.cache.$window.height();
        },

        /**
         * Detect device type and breakpoint
         */
        detectDevice: function() {
            const width = this.state.windowWidth || this.cache.$window.width();
            
            this.state.isMobile = width < this.config.breakpoints.md;
            this.state.isTablet = width >= this.config.breakpoints.md && width < this.config.breakpoints.lg;
            
            // Determine current breakpoint
            if (width >= this.config.breakpoints['2xl']) {
                this.state.currentBreakpoint = '2xl';
            } else if (width >= this.config.breakpoints.xl) {
                this.state.currentBreakpoint = 'xl';
            } else if (width >= this.config.breakpoints.lg) {
                this.state.currentBreakpoint = 'lg';
            } else if (width >= this.config.breakpoints.md) {
                this.state.currentBreakpoint = 'md';
            } else if (width >= this.config.breakpoints.sm) {
                this.state.currentBreakpoint = 'sm';
            } else {
                this.state.currentBreakpoint = 'xs';
            }

            // Update body classes
            this.cache.$body
                .toggleClass('is-mobile', this.state.isMobile)
                .toggleClass('is-tablet', this.state.isTablet)
                .toggleClass('is-desktop', !this.state.isMobile && !this.state.isTablet);
        },

        /**
         * Adjust layout for current breakpoint
         */
        adjustLayoutForBreakpoint: function() {
            // Close mobile menu on desktop
            if (!this.state.isMobile && this.state.menuOpen) {
                this.closeMobileMenu();
            }
            
            // Trigger custom event
            this.cache.$document.trigger('kvBreakpointChange', [this.state.currentBreakpoint]);
        },

        /**
         * Initialize components
         */
        initComponents: function() {
            this.initLazyLoading();
            this.initSmoothScrolling();
            this.initDropdownMenus();
            this.initSearchFunctionality();
            this.initTooltips();
            this.initCarousels();
            this.initGalleries();
            this.initForms();
            this.initStickyElements();
            this.initParallax();
            this.initAnimations();
        },

        /**
         * Initialize lazy loading
         */
        initLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const lazyImages = document.querySelectorAll('[data-lazy-src]');
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.lazySrc;
                            img.classList.remove('lazy');
                            img.classList.add('lazyloaded');
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: `${this.config.lazyLoadOffset}px`
                });

                lazyImages.forEach(img => imageObserver.observe(img));
            } else {
                // Fallback for older browsers
                $('[data-lazy-src]').each(function() {
                    $(this).attr('src', $(this).data('lazy-src')).removeClass('lazy').addClass('lazyloaded');
                });
            }
        },

        /**
         * Initialize smooth scrolling
         */
        initSmoothScrolling: function() {
            this.cache.$document.on('click', 'a[href^="#"]', function(e) {
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 600);
                }
            });
        },

        /**
         * Initialize dropdown menus
         */
        initDropdownMenus: function() {
            $('.menu-item-has-children').each(function() {
                const $menuItem = $(this);
                const $submenu = $menuItem.find('.sub-menu').first();
                const $link = $menuItem.find('> a');

                // Add ARIA attributes
                $link.attr({
                    'aria-haspopup': 'true',
                    'aria-expanded': 'false'
                });

                $submenu.attr('aria-hidden', 'true');

                // Handle mouse events
                $menuItem.on('mouseenter', function() {
                    $link.attr('aria-expanded', 'true');
                    $submenu.attr('aria-hidden', 'false').stop().fadeIn(200);
                }).on('mouseleave', function() {
                    $link.attr('aria-expanded', 'false');
                    $submenu.attr('aria-hidden', 'true').stop().fadeOut(200);
                });

                // Handle keyboard events
                $link.on('keydown', function(e) {
                    if (e.key === 'ArrowDown' || e.key === ' ') {
                        e.preventDefault();
                        $link.attr('aria-expanded', 'true');
                        $submenu.attr('aria-hidden', 'false').show();
                        $submenu.find('a').first().focus();
                    }
                });
            });
        },

        /**
         * Toggle mobile menu
         */
        toggleMobileMenu: function(e) {
            e.preventDefault();
            
            if (this.state.menuOpen) {
                this.closeMobileMenu();
            } else {
                this.openMobileMenu();
            }
        },

        /**
         * Open mobile menu
         */
        openMobileMenu: function() {
            this.state.menuOpen = true;
            this.cache.$body.addClass('mobile-menu-open');
            this.cache.$mobileMenuToggle.attr('aria-expanded', 'true');
            this.cache.$navigation.attr('aria-hidden', 'false');
            
            // Focus first menu item
            this.cache.$navigation.find('a').first().focus();
        },

        /**
         * Close mobile menu
         */
        closeMobileMenu: function() {
            this.state.menuOpen = false;
            this.cache.$body.removeClass('mobile-menu-open');
            this.cache.$mobileMenuToggle.attr('aria-expanded', 'false');
            this.cache.$navigation.attr('aria-hidden', 'true');
        },

        /**
         * Initialize search functionality
         */
        initSearchFunctionality: function() {
            const $searchToggle = $('.search-toggle');
            const $searchForm = $('.search-form');
            const $searchInput = $searchForm.find('input[type="search"]');

            $searchToggle.on('click', function(e) {
                e.preventDefault();
                $searchForm.toggleClass('is-open');
                
                if ($searchForm.hasClass('is-open')) {
                    $searchInput.focus();
                }
            });

            // Close search on escape
            $searchInput.on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $searchForm.removeClass('is-open');
                    $searchToggle.focus();
                }
            });

            // AJAX search if enabled
            if ($searchForm.hasClass('ajax-search')) {
                this.initAjaxSearch($searchInput);
            }
        },

        /**
         * Initialize AJAX search
         */
        initAjaxSearch: function($input) {
            let searchTimeout;
            const $results = $('.search-results');

            $input.on('input', (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();

                if (query.length < 3) {
                    $results.hide();
                    return;
                }

                searchTimeout = setTimeout(() => {
                    this.performAjaxSearch(query, $results);
                }, 500);
            });
        },

        /**
         * Perform AJAX search
         */
        performAjaxSearch: function(query, $results) {
            $.ajax({
                url: this.config.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'kv_ajax_search',
                    query: query,
                    nonce: this.config.nonce
                },
                beforeSend: function() {
                    $results.html('<div class="search-loading">Searching...</div>').show();
                },
                success: function(response) {
                    if (response.success) {
                        $results.html(response.data).show();
                    } else {
                        $results.html('<div class="search-no-results">No results found.</div>').show();
                    }
                },
                error: function() {
                    $results.html('<div class="search-error">Search failed. Please try again.</div>').show();
                }
            });
        },

        /**
         * Toggle back to top button
         */
        toggleBackToTop: function() {
            if (this.state.scrollPosition > 300) {
                this.cache.$backToTop.fadeIn();
            } else {
                this.cache.$backToTop.fadeOut();
            }
        },

        /**
         * Scroll to top
         */
        scrollToTop: function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 600);
        },

        /**
         * Update header on scroll
         */
        updateHeaderOnScroll: function() {
            if (this.state.scrollPosition > 100) {
                this.cache.$header.addClass('scrolled');
            } else {
                this.cache.$header.removeClass('scrolled');
            }
        },

        /**
         * Handle modal functionality
         */
        openModal: function(e) {
            e.preventDefault();
            
            const target = $(this).attr('href') || $(this).data('target');
            const $modal = $(target);
            
            if ($modal.length) {
                this.state.modalsOpen++;
                
                $modal.addClass('is-open').attr('aria-hidden', 'false');
                this.cache.$body.addClass('modal-open');
                
                // Focus first focusable element
                const $focusable = $modal.find('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
                if ($focusable.length) {
                    $focusable.first().focus();
                }
                
                // Store trigger element for returning focus
                $modal.data('trigger', $(this));
            }
        },

        /**
         * Close modal
         */
        closeModal: function(e) {
            if (e.target !== e.currentTarget && !$(e.target).hasClass('modal-close')) {
                return;
            }
            
            const $modal = $(e.target).closest('.modal');
            
            if ($modal.length) {
                this.state.modalsOpen = Math.max(0, this.state.modalsOpen - 1);
                
                $modal.removeClass('is-open').attr('aria-hidden', 'true');
                
                if (this.state.modalsOpen === 0) {
                    this.cache.$body.removeClass('modal-open');
                }
                
                // Return focus to trigger element
                const $trigger = $modal.data('trigger');
                if ($trigger && $trigger.length) {
                    $trigger.focus();
                }
            }
        },

        /**
         * Toggle accordion
         */
        toggleAccordion: function(e) {
            e.preventDefault();
            
            const $toggle = $(this);
            const $content = $toggle.next('.accordion-content');
            const isOpen = $toggle.attr('aria-expanded') === 'true';
            
            $toggle.attr('aria-expanded', !isOpen);
            $content.attr('aria-hidden', isOpen);
            
            if (isOpen) {
                $content.slideUp(this.config.animationDuration);
            } else {
                $content.slideDown(this.config.animationDuration);
            }
        },

        /**
         * Switch tabs
         */
        switchTab: function(e) {
            e.preventDefault();
            
            const $trigger = $(this);
            const $tabPanel = $($trigger.attr('href'));
            const $tabGroup = $trigger.closest('.tabs');
            
            // Update triggers
            $tabGroup.find('.tab-trigger').attr('aria-selected', 'false').removeClass('active');
            $trigger.attr('aria-selected', 'true').addClass('active');
            
            // Update panels
            $tabGroup.find('.tab-panel').attr('aria-hidden', 'true').removeClass('active');
            $tabPanel.attr('aria-hidden', 'false').addClass('active');
        },

        /**
         * Handle AJAX forms
         */
        handleAjaxForm: function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const formData = new FormData(this);
            formData.append('action', 'kv_ajax_form');
            formData.append('nonce', this.config.nonce);
            
            $.ajax({
                url: this.config.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $form.addClass('loading').find('[type="submit"]').prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        $form.trigger('reset');
                        KVEnterprise.showNotification(response.data.message, 'success');
                    } else {
                        KVEnterprise.showNotification(response.data.message || 'An error occurred.', 'error');
                    }
                },
                error: function() {
                    KVEnterprise.showNotification('Form submission failed. Please try again.', 'error');
                },
                complete: function() {
                    $form.removeClass('loading').find('[type="submit"]').prop('disabled', false);
                }
            });
        },

        /**
         * Handle keyboard navigation
         */
        handleKeyboardNavigation: function(e) {
            // Close modal on escape
            if (e.key === 'Escape' && this.state.modalsOpen > 0) {
                $('.modal.is-open').each((index, modal) => {
                    $(modal).find('.modal-close').trigger('click');
                });
            }
            
            // Close mobile menu on escape
            if (e.key === 'Escape' && this.state.menuOpen) {
                this.closeMobileMenu();
            }
        },

        /**
         * Initialize intersection observer for animations
         */
        initIntersectionObserver: function() {
            const animateElements = document.querySelectorAll('[data-animate]');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const animation = element.dataset.animate;
                        
                        element.classList.add('animate', `animate-${animation}`);
                        observer.unobserve(element);
                    }
                });
            }, {
                rootMargin: '0px 0px -50px 0px'
            });
            
            animateElements.forEach(el => observer.observe(el));
        },

        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            $('[data-tooltip]').each(function() {
                const $element = $(this);
                const text = $element.data('tooltip');
                const position = $element.data('tooltip-position') || 'top';
                
                $element.on('mouseenter focus', function() {
                    KVEnterprise.showTooltip($(this), text, position);
                }).on('mouseleave blur', function() {
                    KVEnterprise.hideTooltip();
                });
            });
        },

        /**
         * Show tooltip
         */
        showTooltip: function($element, text, position) {
            const $tooltip = $('<div class="tooltip" role="tooltip">')
                .addClass(`tooltip-${position}`)
                .html(text);
            
            this.cache.$body.append($tooltip);
            
            const elementRect = $element[0].getBoundingClientRect();
            const tooltipRect = $tooltip[0].getBoundingClientRect();
            
            let top, left;
            
            switch (position) {
                case 'top':
                    top = elementRect.top - tooltipRect.height - 10;
                    left = elementRect.left + (elementRect.width - tooltipRect.width) / 2;
                    break;
                case 'bottom':
                    top = elementRect.bottom + 10;
                    left = elementRect.left + (elementRect.width - tooltipRect.width) / 2;
                    break;
                case 'left':
                    top = elementRect.top + (elementRect.height - tooltipRect.height) / 2;
                    left = elementRect.left - tooltipRect.width - 10;
                    break;
                case 'right':
                    top = elementRect.top + (elementRect.height - tooltipRect.height) / 2;
                    left = elementRect.right + 10;
                    break;
            }
            
            $tooltip.css({
                top: top + window.scrollY,
                left: Math.max(10, Math.min(left, window.innerWidth - tooltipRect.width - 10))
            }).addClass('show');
        },

        /**
         * Hide tooltip
         */
        hideTooltip: function() {
            $('.tooltip').remove();
        },

        /**
         * Initialize carousels
         */
        initCarousels: function() {
            $('.carousel').each(function() {
                // Basic carousel implementation
                // In a real implementation, you might use a library like Swiper or Glide
            });
        },

        /**
         * Initialize galleries
         */
        initGalleries: function() {
            $('.gallery').each(function() {
                // Gallery lightbox implementation
            });
        },

        /**
         * Initialize forms
         */
        initForms: function() {
            // Form validation
            $('.validate-form').each(function() {
                const $form = $(this);
                
                $form.on('submit', function(e) {
                    if (!KVEnterprise.validateForm($form)) {
                        e.preventDefault();
                    }
                });
            });
            
            // File upload enhancement
            $('.file-input').each(function() {
                KVEnterprise.enhanceFileInput($(this));
            });
        },

        /**
         * Validate form
         */
        validateForm: function($form) {
            let isValid = true;
            
            $form.find('[required]').each(function() {
                const $field = $(this);
                const value = $field.val().trim();
                
                if (!value) {
                    KVEnterprise.showFieldError($field, 'This field is required.');
                    isValid = false;
                } else {
                    KVEnterprise.clearFieldError($field);
                }
            });
            
            // Email validation
            $form.find('[type="email"]').each(function() {
                const $field = $(this);
                const value = $field.val().trim();
                
                if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    KVEnterprise.showFieldError($field, 'Please enter a valid email address.');
                    isValid = false;
                }
            });
            
            return isValid;
        },

        /**
         * Show field error
         */
        showFieldError: function($field, message) {
            $field.addClass('error');
            
            let $error = $field.next('.field-error');
            if (!$error.length) {
                $error = $('<div class="field-error" role="alert"></div>');
                $field.after($error);
            }
            
            $error.text(message).show();
        },

        /**
         * Clear field error
         */
        clearFieldError: function($field) {
            $field.removeClass('error').next('.field-error').hide();
        },

        /**
         * Enhance file input
         */
        enhanceFileInput: function($input) {
            const $wrapper = $('<div class="file-input-wrapper">');
            const $label = $('<label class="file-input-label">Choose File</label>');
            const $filename = $('<span class="file-input-filename">No file chosen</span>');
            
            $input.wrap($wrapper).after($label).after($filename);
            
            $input.on('change', function() {
                const files = this.files;
                if (files.length > 0) {
                    $filename.text(files[0].name);
                } else {
                    $filename.text('No file chosen');
                }
            });
        },

        /**
         * Initialize sticky elements
         */
        initStickyElements: function() {
            $('.sticky-element').each(function() {
                const $element = $(this);
                const offset = $element.data('offset') || 0;
                
                KVEnterprise.makeSticky($element, offset);
            });
        },

        /**
         * Make element sticky
         */
        makeSticky: function($element, offset) {
            const elementTop = $element.offset().top;
            
            this.cache.$window.on('scroll.sticky', function() {
                const scrollTop = $(this).scrollTop();
                
                if (scrollTop > elementTop - offset) {
                    $element.addClass('is-sticky');
                } else {
                    $element.removeClass('is-sticky');
                }
            });
        },

        /**
         * Initialize parallax
         */
        initParallax: function() {
            this.parallaxElements = $('.parallax');
        },

        /**
         * Handle parallax
         */
        handleParallax: function() {
            if (!this.parallaxElements || this.parallaxElements.length === 0) {
                return;
            }
            
            this.parallaxElements.each(function() {
                const $element = $(this);
                const speed = $element.data('parallax-speed') || 0.5;
                const yPos = -(KVEnterprise.state.scrollPosition * speed);
                
                $element.css('transform', `translateY(${yPos}px)`);
            });
        },

        /**
         * Initialize animations
         */
        initAnimations: function() {
            // CSS animations will be handled by intersection observer
            // This is for any JavaScript-based animations
        },

        /**
         * Initialize dynamic content (after AJAX)
         */
        initDynamicContent: function() {
            // Re-initialize components for dynamically loaded content
            this.initLazyLoading();
            this.initTooltips();
            this.initForms();
        },

        /**
         * Show notification
         */
        showNotification: function(message, type = 'info', duration = 5000) {
            const $notification = $(`
                <div class="notification notification-${type}" role="alert">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" aria-label="Close notification">&times;</button>
                </div>
            `);
            
            $('.notifications').append($notification);
            
            // Auto-hide
            setTimeout(() => {
                $notification.fadeOut(() => $notification.remove());
            }, duration);
            
            // Manual close
            $notification.find('.notification-close').on('click', function() {
                $notification.fadeOut(() => $notification.remove());
            });
        },

        /**
         * Utility: Throttle function
         */
        throttle: function(func, wait) {
            let timeout;
            return function(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func.apply(this, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        /**
         * Utility: Debounce function
         */
        debounce: function(func, wait, immediate) {
            let timeout;
            return function(...args) {
                const later = () => {
                    timeout = null;
                    if (!immediate) func.apply(this, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(this, args);
            };
        },

        /**
         * Utility: Get viewport dimensions
         */
        getViewport: function() {
            return {
                width: this.state.windowWidth,
                height: this.state.windowHeight
            };
        },

        /**
         * Utility: Check if element is in viewport
         */
        isInViewport: function(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= this.state.windowHeight &&
                rect.right <= this.state.windowWidth
            );
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        KVEnterprise.init();
    });

    // Expose to global scope
    window.KVEnterprise = KVEnterprise;

})(jQuery);
