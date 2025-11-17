/**
 * AquaLuxe Theme Main JavaScript
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Theme Object
     */
    const AquaLuxe = {
        
        /**
         * Initialize theme
         */
        init: function() {
            this.bindEvents();
            this.initComponents();
            this.initAccessibility();
            this.initPerformance();
            
            // Fire ready event
            $(document).trigger('aqualuxe:ready');
        },

        /**
         * Bind global events
         */
        bindEvents: function() {
            $(window).on('load', this.onWindowLoad.bind(this));
            $(window).on('resize', this.debounce(this.onWindowResize.bind(this), 250));
            $(window).on('scroll', this.throttle(this.onWindowScroll.bind(this), 16));
            
            // Mobile menu toggle
            $(document).on('click', '#mobile-menu-toggle', this.toggleMobileMenu.bind(this));
            
            // Search toggle
            $(document).on('click', '#search-toggle', this.toggleSearch.bind(this));
            $(document).on('click', '#search-close', this.closeSearch.bind(this));
            
            // Back to top button
            $(document).on('click', '#back-to-top', this.scrollToTop.bind(this));
            
            // Close search on escape key
            $(document).on('keydown', this.handleKeydown.bind(this));
            
            // AJAX form submissions
            $(document).on('submit', '.ajax-form', this.handleAjaxForm.bind(this));
        },

        /**
         * Initialize components
         */
        initComponents: function() {
            this.initLazyLoading();
            this.initSmoothScroll();
            this.initParallax();
            this.initTooltips();
            this.initModals();
            this.initCarousels();
            this.initTabs();
            this.initAccordions();
            this.initCounters();
            this.initLightbox();
        },

        /**
         * Initialize accessibility features
         */
        initAccessibility: function() {
            // Skip links
            this.initSkipLinks();
            
            // Focus management
            this.initFocusManagement();
            
            // ARIA live regions
            this.initAriaLiveRegions();
            
            // Keyboard navigation
            this.initKeyboardNavigation();
        },

        /**
         * Initialize performance optimizations
         */
        initPerformance: function() {
            // Preload critical resources
            this.preloadCriticalResources();
            
            // Optimize images
            this.optimizeImages();
            
            // Service worker for caching
            if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
                this.registerServiceWorker();
            }
        },

        /**
         * Window load handler
         */
        onWindowLoad: function() {
            this.hideLoader();
            this.initBackToTop();
            this.calculateViewportHeight();
            
            // Fire loaded event
            $(document).trigger('aqualuxe:loaded');
        },

        /**
         * Window resize handler
         */
        onWindowResize: function() {
            this.calculateViewportHeight();
            this.updateCarousels();
            
            // Fire resize event
            $(document).trigger('aqualuxe:resize');
        },

        /**
         * Window scroll handler
         */
        onWindowScroll: function() {
            this.updateBackToTop();
            this.updateHeaderOnScroll();
            this.updateParallax();
            
            // Fire scroll event
            $(document).trigger('aqualuxe:scroll');
        },

        /**
         * Toggle mobile menu
         */
        toggleMobileMenu: function(e) {
            e.preventDefault();
            
            const $toggle = $(e.currentTarget);
            const $menu = $('#mobile-navigation');
            const isExpanded = $toggle.attr('aria-expanded') === 'true';
            
            $toggle.attr('aria-expanded', !isExpanded);
            $menu.toggleClass('hidden');
            
            if (!isExpanded) {
                $menu.find('a:first').focus();
                $(document.body).addClass('mobile-menu-open');
            } else {
                $(document.body).removeClass('mobile-menu-open');
            }
        },

        /**
         * Toggle search overlay
         */
        toggleSearch: function(e) {
            e.preventDefault();
            
            const $overlay = $('#search-overlay');
            $overlay.removeClass('hidden');
            $overlay.find('input[type="search"]').focus();
            $(document.body).addClass('search-open');
        },

        /**
         * Close search overlay
         */
        closeSearch: function(e) {
            e.preventDefault();
            
            const $overlay = $('#search-overlay');
            $overlay.addClass('hidden');
            $(document.body).removeClass('search-open');
            $('#search-toggle').focus();
        },

        /**
         * Scroll to top
         */
        scrollToTop: function(e) {
            e.preventDefault();
            
            $('html, body').animate({
                scrollTop: 0
            }, 800, 'easeInOutCubic');
        },

        /**
         * Handle keydown events
         */
        handleKeydown: function(e) {
            // Close search on escape
            if (e.keyCode === 27 && $(document.body).hasClass('search-open')) {
                this.closeSearch(e);
            }
            
            // Close mobile menu on escape
            if (e.keyCode === 27 && $(document.body).hasClass('mobile-menu-open')) {
                $('#mobile-menu-toggle').click();
            }
        },

        /**
         * Initialize lazy loading
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
                            observer.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        },

        /**
         * Initialize smooth scroll
         */
        initSmoothScroll: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function(e) {
                const target = $(this.hash);
                if (target.length) {
                    e.preventDefault();
                    
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 800, 'easeInOutCubic');
                }
            });
        },

        /**
         * Initialize parallax effects
         */
        initParallax: function() {
            $('.parallax').each(function() {
                const $element = $(this);
                const speed = $element.data('parallax-speed') || 0.5;
                
                $element.data('parallax-speed', speed);
            });
        },

        /**
         * Update parallax on scroll
         */
        updateParallax: function() {
            const scrollTop = $(window).scrollTop();
            
            $('.parallax').each(function() {
                const $element = $(this);
                const speed = $element.data('parallax-speed');
                const yPos = -(scrollTop * speed);
                
                $element.css('transform', `translateY(${yPos}px)`);
            });
        },

        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            $('[data-tooltip]').each(function() {
                const $element = $(this);
                const tooltip = $element.data('tooltip');
                
                $element.on('mouseenter focus', function() {
                    const $tooltip = $('<div class="tooltip">' + tooltip + '</div>');
                    $('body').append($tooltip);
                    
                    const position = $element.offset();
                    $tooltip.css({
                        top: position.top - $tooltip.outerHeight() - 10,
                        left: position.left + ($element.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
                    });
                });
                
                $element.on('mouseleave blur', function() {
                    $('.tooltip').remove();
                });
            });
        },

        /**
         * Initialize modals
         */
        initModals: function() {
            $(document).on('click', '[data-modal]', function(e) {
                e.preventDefault();
                
                const modalId = $(this).data('modal');
                const $modal = $('#' + modalId);
                
                if ($modal.length) {
                    $modal.removeClass('hidden');
                    $modal.find('.modal-content').focus();
                    $(document.body).addClass('modal-open');
                }
            });
            
            $(document).on('click', '.modal-close, .modal-overlay', function(e) {
                e.preventDefault();
                
                const $modal = $(this).closest('.modal');
                $modal.addClass('hidden');
                $(document.body).removeClass('modal-open');
            });
        },

        /**
         * Initialize carousels
         */
        initCarousels: function() {
            $('.carousel').each(function() {
                const $carousel = $(this);
                
                // Initialize Swiper if available
                if (typeof Swiper !== 'undefined') {
                    new Swiper($carousel[0], {
                        loop: true,
                        autoplay: {
                            delay: 5000,
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                    });
                }
            });
        },

        /**
         * Update carousels on resize
         */
        updateCarousels: function() {
            // Update carousel dimensions if needed
        },

        /**
         * Initialize tabs
         */
        initTabs: function() {
            $('.tabs').each(function() {
                const $tabs = $(this);
                const $tabButtons = $tabs.find('.tab-button');
                const $tabPanels = $tabs.find('.tab-panel');
                
                $tabButtons.on('click', function(e) {
                    e.preventDefault();
                    
                    const target = $(this).data('tab');
                    
                    $tabButtons.removeClass('active').attr('aria-selected', 'false');
                    $tabPanels.removeClass('active').attr('aria-hidden', 'true');
                    
                    $(this).addClass('active').attr('aria-selected', 'true');
                    $('#' + target).addClass('active').attr('aria-hidden', 'false');
                });
            });
        },

        /**
         * Initialize accordions
         */
        initAccordions: function() {
            $('.accordion').each(function() {
                const $accordion = $(this);
                const $buttons = $accordion.find('.accordion-button');
                
                $buttons.on('click', function(e) {
                    e.preventDefault();
                    
                    const $button = $(this);
                    const $panel = $button.next('.accordion-panel');
                    const isExpanded = $button.attr('aria-expanded') === 'true';
                    
                    $button.attr('aria-expanded', !isExpanded);
                    $panel.toggleClass('expanded');
                });
            });
        },

        /**
         * Initialize counters
         */
        initCounters: function() {
            $('.counter').each(function() {
                const $counter = $(this);
                const target = parseInt($counter.data('target'));
                const duration = $counter.data('duration') || 2000;
                
                if ('IntersectionObserver' in window) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.animateCounter($counter, target, duration);
                                observer.unobserve(entry.target);
                            }
                        });
                    });
                    
                    observer.observe($counter[0]);
                }
            }.bind(this));
        },

        /**
         * Animate counter
         */
        animateCounter: function($counter, target, duration) {
            let current = 0;
            const increment = target / (duration / 16);
            
            const timer = setInterval(() => {
                current += increment;
                
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                $counter.text(Math.floor(current));
            }, 16);
        },

        /**
         * Initialize lightbox
         */
        initLightbox: function() {
            $('[data-lightbox]').on('click', function(e) {
                e.preventDefault();
                
                const src = $(this).attr('href') || $(this).data('src');
                const alt = $(this).data('alt') || '';
                
                const $lightbox = $(`
                    <div class="lightbox fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center">
                        <img src="${src}" alt="${alt}" class="max-w-full max-h-full">
                        <button class="lightbox-close absolute top-4 right-4 text-white text-4xl">&times;</button>
                    </div>
                `);
                
                $('body').append($lightbox);
                $(document.body).addClass('lightbox-open');
                
                $lightbox.on('click', '.lightbox-close, .lightbox', function(e) {
                    if (e.target === this) {
                        $lightbox.remove();
                        $(document.body).removeClass('lightbox-open');
                    }
                });
            });
        },

        /**
         * Handle AJAX forms
         */
        handleAjaxForm: function(e) {
            e.preventDefault();
            
            const $form = $(e.currentTarget);
            const $submit = $form.find('[type="submit"]');
            const originalText = $submit.text();
            
            $submit.prop('disabled', true).text(window.aqualuxe.strings.loading);
            
            $.ajax({
                url: $form.attr('action') || window.aqualuxe.ajaxurl,
                type: $form.attr('method') || 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $form.trigger('reset');
                        AquaLuxe.showNotification(response.data.message || window.aqualuxe.strings.success, 'success');
                    } else {
                        AquaLuxe.showNotification(response.data.message || window.aqualuxe.strings.error, 'error');
                    }
                },
                error: function() {
                    AquaLuxe.showNotification(window.aqualuxe.strings.error, 'error');
                },
                complete: function() {
                    $submit.prop('disabled', false).text(originalText);
                }
            });
        },

        /**
         * Show notification
         */
        showNotification: function(message, type = 'info') {
            const $notification = $(`
                <div class="notification notification-${type} fixed top-4 right-4 bg-white border-l-4 p-4 shadow-lg z-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                ${type === 'success' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>' : '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>'}
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">${message}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button class="notification-close">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append($notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                $notification.remove();
            }, 5000);
            
            // Manual close
            $notification.on('click', '.notification-close', function() {
                $notification.remove();
            });
        },

        /**
         * Initialize skip links
         */
        initSkipLinks: function() {
            $('.skip-link').on('click', function(e) {
                const target = $($(this).attr('href'));
                if (target.length) {
                    target.attr('tabindex', '-1').focus();
                }
            });
        },

        /**
         * Initialize focus management
         */
        initFocusManagement: function() {
            // Trap focus in modals
            $(document).on('keydown', '.modal', function(e) {
                if (e.keyCode === 9) { // Tab key
                    const $modal = $(this);
                    const $focusable = $modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                    const $first = $focusable.first();
                    const $last = $focusable.last();
                    
                    if (e.shiftKey) {
                        if (document.activeElement === $first[0]) {
                            e.preventDefault();
                            $last.focus();
                        }
                    } else {
                        if (document.activeElement === $last[0]) {
                            e.preventDefault();
                            $first.focus();
                        }
                    }
                }
            });
        },

        /**
         * Initialize ARIA live regions
         */
        initAriaLiveRegions: function() {
            if (!$('#aria-live-region').length) {
                $('body').append('<div id="aria-live-region" aria-live="polite" aria-atomic="true" class="sr-only"></div>');
            }
        },

        /**
         * Initialize keyboard navigation
         */
        initKeyboardNavigation: function() {
            // Add keyboard support for custom components
            $('.dropdown-toggle').on('keydown', function(e) {
                if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                    e.preventDefault();
                    $(this).click();
                }
            });
        },

        /**
         * Hide loader
         */
        hideLoader: function() {
            $('.page-loader').fadeOut(300);
        },

        /**
         * Initialize back to top
         */
        initBackToTop: function() {
            this.updateBackToTop();
        },

        /**
         * Update back to top button visibility
         */
        updateBackToTop: function() {
            const $button = $('#back-to-top');
            const scrollTop = $(window).scrollTop();
            
            if (scrollTop > 300) {
                $button.removeClass('opacity-0 invisible').addClass('opacity-100 visible');
            } else {
                $button.removeClass('opacity-100 visible').addClass('opacity-0 invisible');
            }
        },

        /**
         * Update header on scroll
         */
        updateHeaderOnScroll: function() {
            const $header = $('.site-header');
            const scrollTop = $(window).scrollTop();
            
            if (scrollTop > 100) {
                $header.addClass('scrolled');
            } else {
                $header.removeClass('scrolled');
            }
        },

        /**
         * Calculate viewport height for mobile
         */
        calculateViewportHeight: function() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        },

        /**
         * Preload critical resources
         */
        preloadCriticalResources: function() {
            // Preload critical images
            const criticalImages = [
                // Add critical image URLs here
            ];
            
            criticalImages.forEach(src => {
                const link = document.createElement('link');
                link.rel = 'preload';
                link.as = 'image';
                link.href = src;
                document.head.appendChild(link);
            });
        },

        /**
         * Optimize images
         */
        optimizeImages: function() {
            // Add WebP support detection
            const webpSupport = this.supportsWebP();
            if (webpSupport) {
                document.documentElement.classList.add('webp');
            }
        },

        /**
         * Check WebP support
         */
        supportsWebP: function() {
            const canvas = document.createElement('canvas');
            canvas.width = 1;
            canvas.height = 1;
            return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
        },

        /**
         * Register service worker
         */
        registerServiceWorker: function() {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('Service Worker registered:', registration);
                })
                .catch(error => {
                    console.log('Service Worker registration failed:', error);
                });
        },

        /**
         * Debounce function
         */
        debounce: function(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
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
        },

        /**
         * Throttle function
         */
        throttle: function(func, limit) {
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
    };

    /**
     * Initialize when DOM is ready
     */
    $(document).ready(function() {
        AquaLuxe.init();
    });

    /**
     * Make AquaLuxe globally available
     */
    window.AquaLuxe = AquaLuxe;

})(jQuery);