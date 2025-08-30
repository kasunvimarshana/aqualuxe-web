/**
 * AquaLuxe Theme JavaScript
 * 
 * Main JavaScript file for the AquaLuxe WordPress theme
 */

(function($) {
    'use strict';

    // Global variables
    var $window = $(window),
        $document = $(document),
        $body = $('body'),
        $header = $('#masthead'),
        $navigation = $('#site-navigation'),
        $mobileMenu = $('#mobile-menu'),
        $backToTop = $('#back-to-top');

    // AquaLuxe object
    var AquaLuxe = {
        /**
         * Initialize the theme's JavaScript
         */
        init: function() {
            // Document ready
            $(function() {
                AquaLuxe.stickyHeader();
                AquaLuxe.mobileMenu();
                AquaLuxe.backToTop();
                AquaLuxe.headerSearch();
                AquaLuxe.miniCart();
                AquaLuxe.readingProgress();
                AquaLuxe.initSliders();
                AquaLuxe.initAccordions();
                AquaLuxe.initTabs();
                AquaLuxe.initModals();
                AquaLuxe.initTooltips();
                AquaLuxe.initAnimations();
                AquaLuxe.initMasonry();
                AquaLuxe.initLightbox();
                AquaLuxe.initAjaxFilters();
                AquaLuxe.initWooCommerce();
                AquaLuxe.initContactForm();
                AquaLuxe.initNewsletterForm();
                AquaLuxe.initLazyLoad();
            });

            // Window load
            $window.on('load', function() {
                AquaLuxe.preloader();
            });

            // Window resize
            $window.on('resize', function() {
                AquaLuxe.debounce(AquaLuxe.resizeHandler, 250);
            });

            // Window scroll
            $window.on('scroll', function() {
                AquaLuxe.scrollHandler();
            });
        },

        /**
         * Preloader
         */
        preloader: function() {
            $('.preloader').fadeOut(500);
        },

        /**
         * Sticky header
         */
        stickyHeader: function() {
            if ($header.hasClass('sticky-header')) {
                var headerHeight = $header.outerHeight(),
                    scrollTop = $window.scrollTop();

                $window.on('scroll', function() {
                    scrollTop = $window.scrollTop();

                    if (scrollTop > headerHeight) {
                        $header.addClass('is-sticky');
                        $body.css('padding-top', headerHeight);
                    } else {
                        $header.removeClass('is-sticky');
                        $body.css('padding-top', 0);
                    }
                });
            }
        },

        /**
         * Mobile menu
         */
        mobileMenu: function() {
            // Toggle mobile menu
            $('#mobile-menu-toggle').on('click', function(e) {
                e.preventDefault();
                $mobileMenu.toggleClass('translate-x-full translate-x-0');
                $body.toggleClass('mobile-menu-open');
            });

            // Close mobile menu
            $('#mobile-menu-close').on('click', function(e) {
                e.preventDefault();
                $mobileMenu.toggleClass('translate-x-full translate-x-0');
                $body.toggleClass('mobile-menu-open');
            });

            // Mobile menu dropdown
            $('.mobile-menu-nav .menu-item-has-children > a').on('click', function(e) {
                var $this = $(this);
                
                if (!$this.hasClass('active')) {
                    e.preventDefault();
                    $this.addClass('active');
                    $this.next('.sub-menu').slideDown(300);
                }
            });
        },

        /**
         * Back to top button
         */
        backToTop: function() {
            if ($backToTop.length) {
                $window.on('scroll', function() {
                    if ($window.scrollTop() > 300) {
                        $backToTop.addClass('opacity-100 visible').removeClass('opacity-0 invisible');
                    } else {
                        $backToTop.addClass('opacity-0 invisible').removeClass('opacity-100 visible');
                    }
                });

                $backToTop.on('click', function(e) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: 0
                    }, 800);
                });
            }
        },

        /**
         * Header search
         */
        headerSearch: function() {
            var $searchToggle = $('#header-search-toggle'),
                $searchForm = $('#header-search-form');

            if ($searchToggle.length && $searchForm.length) {
                $searchToggle.on('click', function(e) {
                    e.preventDefault();
                    $searchForm.toggleClass('hidden');
                    if (!$searchForm.hasClass('hidden')) {
                        $searchForm.find('input[type="search"]').focus();
                    }
                });

                $(document).on('click', function(e) {
                    if (!$(e.target).closest($searchToggle).length && !$(e.target).closest($searchForm).length) {
                        $searchForm.addClass('hidden');
                    }
                });
            }
        },

        /**
         * Mini cart
         */
        miniCart: function() {
            var $miniCart = $('.mini-cart'),
                $cartLink = $('.header-cart > a');

            if ($miniCart.length && $cartLink.length) {
                $cartLink.on('click', function(e) {
                    e.preventDefault();
                    $miniCart.toggleClass('hidden');
                });

                $(document).on('click', function(e) {
                    if (!$(e.target).closest($cartLink).length && !$(e.target).closest($miniCart).length) {
                        $miniCart.addClass('hidden');
                    }
                });

                // Update mini cart using AJAX
                $body.on('added_to_cart', function(e, fragments, cart_hash) {
                    $miniCart.removeClass('hidden');
                    
                    // Auto-hide after 4 seconds
                    setTimeout(function() {
                        $miniCart.addClass('hidden');
                    }, 4000);
                });
            }
        },

        /**
         * Reading progress
         */
        readingProgress: function() {
            var $progressBar = $('.reading-progress-bar');

            if ($progressBar.length && $body.hasClass('single')) {
                var $content = $('.entry-content'),
                    contentHeight = $content.height(),
                    contentOffset = $content.offset().top;

                $window.on('scroll', function() {
                    var scrollTop = $window.scrollTop(),
                        scrollPercent = 0;

                    if (scrollTop > contentOffset) {
                        scrollPercent = (scrollTop - contentOffset) / contentHeight * 100;
                    }

                    if (scrollPercent > 100) {
                        scrollPercent = 100;
                    }

                    $progressBar.css('width', scrollPercent + '%');
                });
            }
        },

        /**
         * Initialize sliders
         */
        initSliders: function() {
            // Check if Swiper is loaded
            if (typeof Swiper === 'undefined') {
                return;
            }

            // Hero slider
            if ($('.hero-slider').length) {
                new Swiper('.hero-slider .swiper-container', {
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.hero-slider .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.hero-slider .swiper-button-next',
                        prevEl: '.hero-slider .swiper-button-prev',
                    },
                });
            }

            // Testimonial slider
            if ($('.testimonial-slider').length) {
                new Swiper('.testimonial-slider .swiper-container', {
                    loop: true,
                    autoplay: {
                        delay: 6000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.testimonial-slider .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.testimonial-slider .swiper-button-next',
                        prevEl: '.testimonial-slider .swiper-button-prev',
                    },
                    slidesPerView: 1,
                    spaceBetween: 30,
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1024: {
                            slidesPerView: 3,
                        },
                    },
                });
            }

            // Product slider
            if ($('.product-slider').length) {
                new Swiper('.product-slider .swiper-container', {
                    loop: true,
                    pagination: {
                        el: '.product-slider .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.product-slider .swiper-button-next',
                        prevEl: '.product-slider .swiper-button-prev',
                    },
                    slidesPerView: 1,
                    spaceBetween: 30,
                    breakpoints: {
                        576: {
                            slidesPerView: 2,
                        },
                        768: {
                            slidesPerView: 3,
                        },
                        1024: {
                            slidesPerView: 4,
                        },
                    },
                });
            }

            // Featured posts slider
            if ($('.featured-posts-slider').length) {
                new Swiper('.featured-posts-slider .swiper-container', {
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.featured-posts-slider .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.featured-posts-slider .swiper-button-next',
                        prevEl: '.featured-posts-slider .swiper-button-prev',
                    },
                });
            }
        },

        /**
         * Initialize accordions
         */
        initAccordions: function() {
            $('.accordion-header').on('click', function() {
                var $this = $(this),
                    $accordion = $this.parent('.accordion-item'),
                    $content = $accordion.find('.accordion-content');

                $accordion.toggleClass('active');
                $content.slideToggle(300);

                // Close other accordions if this is part of an accordion group
                if ($accordion.parent('.accordion-group').hasClass('single-open')) {
                    $accordion.siblings('.accordion-item.active').removeClass('active').find('.accordion-content').slideUp(300);
                }
            });
        },

        /**
         * Initialize tabs
         */
        initTabs: function() {
            $('.tabs-nav a').on('click', function(e) {
                e.preventDefault();
                
                var $this = $(this),
                    tabId = $this.attr('href'),
                    $tabsNav = $this.closest('.tabs-nav'),
                    $tabsContent = $tabsNav.siblings('.tabs-content');
                
                // Remove active class from all tabs
                $tabsNav.find('a').removeClass('active');
                $tabsContent.find('.tab-pane').removeClass('active');
                
                // Add active class to current tab
                $this.addClass('active');
                $(tabId).addClass('active');
            });
        },

        /**
         * Initialize modals
         */
        initModals: function() {
            // Open modal
            $('[data-modal]').on('click', function(e) {
                e.preventDefault();
                
                var modalId = $(this).data('modal');
                $(modalId).removeClass('hidden').addClass('flex');
                $body.addClass('modal-open');
            });
            
            // Close modal
            $('.modal-close, .modal-overlay').on('click', function(e) {
                e.preventDefault();
                
                $(this).closest('.modal').removeClass('flex').addClass('hidden');
                $body.removeClass('modal-open');
            });
            
            // Close modal on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && $body.hasClass('modal-open')) {
                    $('.modal:not(.hidden)').removeClass('flex').addClass('hidden');
                    $body.removeClass('modal-open');
                }
            });
        },

        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            $('[data-tooltip]').each(function() {
                var $this = $(this),
                    tooltip = $this.data('tooltip'),
                    position = $this.data('tooltip-position') || 'top';
                
                $this.append('<span class="tooltip tooltip-' + position + '">' + tooltip + '</span>');
            });
        },

        /**
         * Initialize animations
         */
        initAnimations: function() {
            // Check if AOS is loaded
            if (typeof AOS === 'undefined') {
                return;
            }

            AOS.init({
                offset: 120,
                delay: 0,
                duration: 800,
                easing: 'ease',
                once: true,
                mirror: false,
                anchorPlacement: 'top-bottom',
            });
        },

        /**
         * Initialize masonry layout
         */
        initMasonry: function() {
            // Check if Masonry is loaded
            if (typeof Masonry === 'undefined') {
                return;
            }

            // Blog masonry
            if ($('.blog-masonry').length) {
                var $grid = $('.blog-masonry').masonry({
                    itemSelector: '.masonry-item',
                    columnWidth: '.masonry-sizer',
                    percentPosition: true,
                    gutter: 30,
                });

                // Initialize masonry after images are loaded
                $grid.imagesLoaded().progress(function() {
                    $grid.masonry('layout');
                });
            }
        },

        /**
         * Initialize lightbox
         */
        initLightbox: function() {
            // Check if Fancybox is loaded
            if (typeof $.fancybox === 'undefined') {
                return;
            }

            // Image lightbox
            $('.entry-content img').each(function() {
                var $img = $(this);
                
                if (!$img.closest('a').length) {
                    $img.wrap('<a href="' + $img.attr('src') + '" data-fancybox="gallery"></a>');
                }
            });

            // Initialize Fancybox
            $('[data-fancybox]').fancybox({
                buttons: [
                    'zoom',
                    'slideShow',
                    'fullScreen',
                    'download',
                    'thumbs',
                    'close'
                ],
                loop: true,
                protect: true,
                animationEffect: 'fade',
                transitionEffect: 'fade',
            });
        },

        /**
         * Initialize AJAX filters
         */
        initAjaxFilters: function() {
            // Blog filters
            $('.blog-filters a').on('click', function(e) {
                e.preventDefault();
                
                var $this = $(this),
                    category = $this.data('category'),
                    $posts = $('.blog-posts'),
                    $loader = $('.blog-loader');
                
                // Add active class
                $this.parent().siblings().find('a').removeClass('active');
                $this.addClass('active');
                
                // Show loader
                $loader.removeClass('hidden');
                
                // AJAX request
                $.ajax({
                    url: aqualuxeData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_filter_posts',
                        category: category,
                        nonce: aqualuxeData.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Replace posts
                            $posts.html(response.data.html);
                            
                            // Initialize masonry if needed
                            if ($posts.hasClass('blog-masonry')) {
                                AquaLuxe.initMasonry();
                            }
                            
                            // Initialize animations
                            AquaLuxe.initAnimations();
                        }
                        
                        // Hide loader
                        $loader.addClass('hidden');
                    },
                    error: function() {
                        // Hide loader
                        $loader.addClass('hidden');
                    }
                });
            });
        },

        /**
         * Initialize WooCommerce features
         */
        initWooCommerce: function() {
            // Quick view
            $('.quick-view-button').on('click', function(e) {
                e.preventDefault();
                
                var $this = $(this),
                    productId = $this.data('product-id'),
                    $modal = $('#quick-view-modal'),
                    $content = $modal.find('.modal-content'),
                    $loader = $modal.find('.modal-loader');
                
                // Show modal and loader
                $modal.removeClass('hidden').addClass('flex');
                $loader.removeClass('hidden');
                $content.addClass('hidden');
                
                // AJAX request
                $.ajax({
                    url: aqualuxeData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        nonce: aqualuxeData.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update content
                            $content.html(response.data.html);
                            
                            // Initialize product gallery
                            if (typeof wc_single_product_params !== 'undefined') {
                                $content.find('.woocommerce-product-gallery').each(function() {
                                    $(this).wc_product_gallery(wc_single_product_params);
                                });
                            }
                            
                            // Show content
                            $content.removeClass('hidden');
                        }
                        
                        // Hide loader
                        $loader.addClass('hidden');
                    },
                    error: function() {
                        // Hide loader
                        $loader.addClass('hidden');
                    }
                });
            });

            // AJAX add to cart
            if (aqualuxeData.isWooCommerceActive && aqualuxeData.ajaxAddToCart) {
                $body.on('click', '.ajax_add_to_cart', function(e) {
                    var $thisbutton = $(this);
                    
                    if ($thisbutton.is('.product_type_simple')) {
                        if (!$thisbutton.attr('data-product_id')) {
                            return true;
                        }
                        
                        e.preventDefault();
                        
                        $thisbutton.removeClass('added');
                        $thisbutton.addClass('loading');
                        
                        var data = {};
                        
                        $.each($thisbutton.data(), function(key, value) {
                            data[key] = value;
                        });
                        
                        // Trigger event
                        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
                        
                        // AJAX add to cart request
                        $.ajax({
                            type: 'POST',
                            url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                            data: data,
                            success: function(response) {
                                if (!response) {
                                    return;
                                }
                                
                                if (response.error && response.product_url) {
                                    window.location = response.product_url;
                                    return;
                                }
                                
                                // Trigger event
                                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                            },
                            complete: function() {
                                $thisbutton.removeClass('loading');
                            }
                        });
                    }
                });
            }

            // Quantity buttons
            $body.on('click', '.quantity-button', function() {
                var $button = $(this),
                    $input = $button.siblings('input.qty'),
                    oldValue = parseFloat($input.val()),
                    newVal;
                
                if ($button.hasClass('plus')) {
                    newVal = oldValue + 1;
                } else {
                    // Don't allow decrementing below 1
                    if (oldValue > 1) {
                        newVal = oldValue - 1;
                    } else {
                        newVal = 1;
                    }
                }
                
                $input.val(newVal);
                $input.trigger('change');
            });
        },

        /**
         * Initialize contact form
         */
        initContactForm: function() {
            var $form = $('#contact-form');
            
            if ($form.length) {
                $form.on('submit', function(e) {
                    e.preventDefault();
                    
                    var $this = $(this),
                        $submit = $this.find('button[type="submit"]'),
                        $response = $this.find('.form-response'),
                        formData = $this.serialize();
                    
                    // Disable submit button
                    $submit.prop('disabled', true);
                    
                    // Clear response
                    $response.empty();
                    
                    // AJAX request
                    $.ajax({
                        url: aqualuxeData.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_contact_form',
                            form_data: formData,
                            nonce: aqualuxeData.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                // Show success message
                                $response.html('<div class="alert alert-success">' + response.data.message + '</div>');
                                
                                // Reset form
                                $this[0].reset();
                            } else {
                                // Show error message
                                $response.html('<div class="alert alert-danger">' + response.data.message + '</div>');
                            }
                            
                            // Enable submit button
                            $submit.prop('disabled', false);
                        },
                        error: function() {
                            // Show error message
                            $response.html('<div class="alert alert-danger">' + aqualuxeData.i18n.errorMessage + '</div>');
                            
                            // Enable submit button
                            $submit.prop('disabled', false);
                        }
                    });
                });
            }
        },

        /**
         * Initialize newsletter form
         */
        initNewsletterForm: function() {
            var $form = $('.newsletter-form');
            
            if ($form.length) {
                $form.on('submit', function(e) {
                    e.preventDefault();
                    
                    var $this = $(this),
                        $submit = $this.find('button[type="submit"]'),
                        $response = $this.find('.form-response'),
                        $email = $this.find('input[type="email"]'),
                        email = $email.val();
                    
                    // Validate email
                    if (!email || !AquaLuxe.isValidEmail(email)) {
                        $response.html('<div class="alert alert-danger">' + aqualuxeData.i18n.invalidEmail + '</div>');
                        return;
                    }
                    
                    // Disable submit button
                    $submit.prop('disabled', true);
                    
                    // Clear response
                    $response.empty();
                    
                    // AJAX request
                    $.ajax({
                        url: aqualuxeData.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_newsletter_form',
                            email: email,
                            nonce: aqualuxeData.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                // Show success message
                                $response.html('<div class="alert alert-success">' + response.data.message + '</div>');
                                
                                // Reset form
                                $this[0].reset();
                            } else {
                                // Show error message
                                $response.html('<div class="alert alert-danger">' + response.data.message + '</div>');
                            }
                            
                            // Enable submit button
                            $submit.prop('disabled', false);
                        },
                        error: function() {
                            // Show error message
                            $response.html('<div class="alert alert-danger">' + aqualuxeData.i18n.errorMessage + '</div>');
                            
                            // Enable submit button
                            $submit.prop('disabled', false);
                        }
                    });
                });
            }
        },

        /**
         * Initialize lazy loading
         */
        initLazyLoad: function() {
            // Check if lazy loading is enabled
            if (!aqualuxeData.lazyLoading) {
                return;
            }

            // Check if Intersection Observer is supported
            if ('IntersectionObserver' in window) {
                var lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));
                
                var lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var lazyImage = entry.target;
                            lazyImage.src = lazyImage.dataset.src;
                            
                            if (lazyImage.dataset.srcset) {
                                lazyImage.srcset = lazyImage.dataset.srcset;
                            }
                            
                            lazyImage.classList.remove('lazy');
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    });
                });
                
                lazyImages.forEach(function(lazyImage) {
                    lazyImageObserver.observe(lazyImage);
                });
            } else {
                // Fallback for browsers that don't support Intersection Observer
                var lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));
                var active = false;
                
                var lazyLoad = function() {
                    if (active === false) {
                        active = true;
                        
                        setTimeout(function() {
                            lazyImages.forEach(function(lazyImage) {
                                if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== 'none') {
                                    lazyImage.src = lazyImage.dataset.src;
                                    
                                    if (lazyImage.dataset.srcset) {
                                        lazyImage.srcset = lazyImage.dataset.srcset;
                                    }
                                    
                                    lazyImage.classList.remove('lazy');
                                    
                                    lazyImages = lazyImages.filter(function(image) {
                                        return image !== lazyImage;
                                    });
                                    
                                    if (lazyImages.length === 0) {
                                        document.removeEventListener('scroll', lazyLoad);
                                        window.removeEventListener('resize', lazyLoad);
                                        window.removeEventListener('orientationchange', lazyLoad);
                                    }
                                }
                            });
                            
                            active = false;
                        }, 200);
                    }
                };
                
                document.addEventListener('scroll', lazyLoad);
                window.addEventListener('resize', lazyLoad);
                window.addEventListener('orientationchange', lazyLoad);
                lazyLoad();
            }
        },

        /**
         * Resize handler
         */
        resizeHandler: function() {
            // Handle resize events
        },

        /**
         * Scroll handler
         */
        scrollHandler: function() {
            // Handle scroll events
        },

        /**
         * Debounce function
         * 
         * @param {Function} func The function to debounce
         * @param {number} wait The wait time in milliseconds
         * @param {boolean} immediate Whether to execute immediately
         * @return {Function} The debounced function
         */
        debounce: function(func, wait, immediate) {
            var timeout;
            
            return function() {
                var context = this,
                    args = arguments;
                    
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },

        /**
         * Validate email address
         * 
         * @param {string} email The email address to validate
         * @return {boolean} Whether the email is valid
         */
        isValidEmail: function(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    };

    // Initialize AquaLuxe
    AquaLuxe.init();

})(jQuery);