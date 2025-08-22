/**
 * AquaLuxe Theme Main JavaScript
 * 
 * This file contains the main JavaScript functionality for the AquaLuxe theme.
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // AquaLuxe object
    const AquaLuxe = {
        /**
         * Initialize the theme
         */
        init: function() {
            // Initialize components
            this.navigation.init();
            this.header.init();
            this.search.init();
            this.scrollToTop.init();
            this.responsiveVideos.init();
            this.forms.init();
            
            // Initialize WooCommerce if it exists
            if (typeof woocommerce !== 'undefined') {
                this.woocommerce.init();
            }
            
            // Initialize modules
            this.modules.init();
            
            // Trigger custom event when everything is initialized
            $(document).trigger('aqualuxe:initialized');
        },

        /**
         * Navigation functionality
         */
        navigation: {
            init: function() {
                this.setupMobileMenu();
                this.setupDropdowns();
                this.setupMegaMenus();
            },
            
            setupMobileMenu: function() {
                const $menuToggle = $('.menu-toggle');
                const $mobileMenu = $('.main-navigation');
                
                $menuToggle.on('click', function() {
                    $(this).toggleClass('active');
                    $mobileMenu.toggleClass('active');
                    $('body').toggleClass('mobile-menu-active');
                    
                    // Toggle aria-expanded attribute
                    const isExpanded = $(this).hasClass('active');
                    $(this).attr('aria-expanded', isExpanded);
                });
                
                // Close menu when clicking outside
                $(document).on('click', function(event) {
                    if (!$(event.target).closest('.main-navigation, .menu-toggle').length) {
                        $menuToggle.removeClass('active');
                        $mobileMenu.removeClass('active');
                        $('body').removeClass('mobile-menu-active');
                        $menuToggle.attr('aria-expanded', false);
                    }
                });
                
                // Handle sub-menu toggles on mobile
                $('.menu-item-has-children > a').append('<span class="sub-menu-toggle"></span>');
                
                $('.sub-menu-toggle').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const $parent = $(this).closest('.menu-item-has-children');
                    $parent.toggleClass('sub-menu-open');
                    $parent.find('> .sub-menu').slideToggle(200);
                });
            },
            
            setupDropdowns: function() {
                // Add dropdown functionality for desktop
                $('.menu-item-has-children').on('mouseenter', function() {
                    if (window.innerWidth >= 992) {
                        $(this).addClass('sub-menu-open');
                    }
                }).on('mouseleave', function() {
                    if (window.innerWidth >= 992) {
                        $(this).removeClass('sub-menu-open');
                    }
                });
                
                // Accessibility - keyboard navigation
                $('.menu-item-has-children > a').on('focus', function() {
                    if (window.innerWidth >= 992) {
                        $(this).parent().addClass('sub-menu-open');
                    }
                });
                
                $('.menu-item-has-children').on('focusout', function(e) {
                    if (window.innerWidth >= 992 && !$(this).has(e.relatedTarget).length) {
                        $(this).removeClass('sub-menu-open');
                    }
                });
            },
            
            setupMegaMenus: function() {
                // Add mega menu functionality if needed
                $('.mega-menu').each(function() {
                    // Custom mega menu setup
                });
            }
        },

        /**
         * Header functionality
         */
        header: {
            init: function() {
                this.setupStickyHeader();
                this.setupTransparentHeader();
            },
            
            setupStickyHeader: function() {
                const $header = $('.sticky-header');
                
                if ($header.length) {
                    const headerHeight = $header.outerHeight();
                    const headerOffset = $header.offset().top;
                    
                    $(window).on('scroll', function() {
                        if ($(window).scrollTop() > headerOffset) {
                            $header.addClass('is-sticky');
                            $('body').css('padding-top', headerHeight);
                        } else {
                            $header.removeClass('is-sticky');
                            $('body').css('padding-top', 0);
                        }
                    });
                    
                    // Trigger scroll event on page load
                    $(window).trigger('scroll');
                }
            },
            
            setupTransparentHeader: function() {
                const $header = $('.transparent-header');
                
                if ($header.length) {
                    $(window).on('scroll', function() {
                        if ($(window).scrollTop() > 100) {
                            $header.addClass('scrolled');
                        } else {
                            $header.removeClass('scrolled');
                        }
                    });
                    
                    // Trigger scroll event on page load
                    $(window).trigger('scroll');
                }
            }
        },

        /**
         * Search functionality
         */
        search: {
            init: function() {
                this.setupSearchToggle();
                this.setupAjaxSearch();
            },
            
            setupSearchToggle: function() {
                const $searchToggle = $('.search-toggle');
                const $searchForm = $('.header-search-form');
                
                $searchToggle.on('click', function(e) {
                    e.preventDefault();
                    
                    $(this).toggleClass('active');
                    $searchForm.toggleClass('active');
                    
                    // Toggle aria-expanded attribute
                    const isExpanded = $(this).hasClass('active');
                    $(this).attr('aria-expanded', isExpanded);
                    
                    // Focus on search input if opened
                    if (isExpanded) {
                        setTimeout(function() {
                            $searchForm.find('input[type="search"]').focus();
                        }, 100);
                    }
                });
                
                // Close search when clicking outside
                $(document).on('click', function(event) {
                    if (!$(event.target).closest('.header-search').length) {
                        $searchToggle.removeClass('active');
                        $searchForm.removeClass('active');
                        $searchToggle.attr('aria-expanded', false);
                    }
                });
            },
            
            setupAjaxSearch: function() {
                const $searchForm = $('.ajax-search');
                const $searchInput = $searchForm.find('input[type="search"]');
                const $searchResults = $('.ajax-search-results');
                let searchTimer;
                
                if ($searchForm.length) {
                    $searchInput.on('keyup', function() {
                        const query = $(this).val();
                        
                        // Clear previous timer
                        clearTimeout(searchTimer);
                        
                        // Set new timer to prevent too many requests
                        if (query.length > 2) {
                            searchTimer = setTimeout(function() {
                                $.ajax({
                                    url: aqualuxeData.ajaxUrl,
                                    type: 'post',
                                    data: {
                                        action: 'aqualuxe_ajax_search',
                                        query: query,
                                        nonce: aqualuxeData.nonce
                                    },
                                    beforeSend: function() {
                                        $searchForm.addClass('searching');
                                    },
                                    success: function(response) {
                                        $searchResults.html(response).addClass('active');
                                        $searchForm.removeClass('searching');
                                    }
                                });
                            }, 500);
                        } else {
                            $searchResults.removeClass('active');
                        }
                    });
                    
                    // Close search results when clicking outside
                    $(document).on('click', function(event) {
                        if (!$(event.target).closest('.ajax-search, .ajax-search-results').length) {
                            $searchResults.removeClass('active');
                        }
                    });
                }
            }
        },

        /**
         * Scroll to top functionality
         */
        scrollToTop: {
            init: function() {
                const $scrollButton = $('.scroll-to-top');
                
                if ($scrollButton.length) {
                    $(window).on('scroll', function() {
                        if ($(window).scrollTop() > 300) {
                            $scrollButton.addClass('show');
                        } else {
                            $scrollButton.removeClass('show');
                        }
                    });
                    
                    $scrollButton.on('click', function(e) {
                        e.preventDefault();
                        $('html, body').animate({ scrollTop: 0 }, 500);
                    });
                }
            }
        },

        /**
         * Responsive videos functionality
         */
        responsiveVideos: {
            init: function() {
                // Make embedded videos responsive
                $('.entry-content iframe[src*="youtube"], .entry-content iframe[src*="vimeo"]').each(function() {
                    if (!$(this).parent().hasClass('responsive-video')) {
                        $(this).wrap('<div class="responsive-video"></div>');
                    }
                });
            }
        },

        /**
         * Forms functionality
         */
        forms: {
            init: function() {
                this.setupFormValidation();
                this.setupFloatingLabels();
            },
            
            setupFormValidation: function() {
                // Add custom form validation if needed
                $('form.validate').each(function() {
                    // Custom validation setup
                });
            },
            
            setupFloatingLabels: function() {
                // Add floating label functionality
                $('.floating-label-field').each(function() {
                    const $field = $(this);
                    const $input = $field.find('input, textarea');
                    
                    // Check if input has value on load
                    if ($input.val()) {
                        $field.addClass('has-value');
                    }
                    
                    // Check on input change
                    $input.on('input', function() {
                        if ($(this).val()) {
                            $field.addClass('has-value');
                        } else {
                            $field.removeClass('has-value');
                        }
                    });
                    
                    // Check on focus
                    $input.on('focus', function() {
                        $field.addClass('is-focused');
                    }).on('blur', function() {
                        $field.removeClass('is-focused');
                    });
                });
            }
        },

        /**
         * WooCommerce functionality
         */
        woocommerce: {
            init: function() {
                if (typeof $.fn.wc_product_gallery !== 'undefined') {
                    this.setupProductGallery();
                }
                
                this.setupQuantityButtons();
                this.setupAjaxCart();
                this.setupQuickView();
            },
            
            setupProductGallery: function() {
                // Custom product gallery setup if needed
                $('.woocommerce-product-gallery').each(function() {
                    // Custom gallery enhancements
                });
            },
            
            setupQuantityButtons: function() {
                // Add quantity increment/decrement buttons
                $('.quantity').each(function() {
                    const $quantity = $(this);
                    const $input = $quantity.find('input[type="number"]');
                    
                    // Only add buttons if they don't already exist
                    if (!$quantity.find('.quantity-button').length) {
                        $quantity.prepend('<button type="button" class="quantity-button minus">-</button>');
                        $quantity.append('<button type="button" class="quantity-button plus">+</button>');
                        
                        // Handle button clicks
                        $quantity.on('click', '.quantity-button', function() {
                            const $button = $(this);
                            const oldValue = parseFloat($input.val());
                            let newVal;
                            
                            if ($button.hasClass('plus')) {
                                const max = parseFloat($input.attr('max'));
                                if (max && oldValue >= max) {
                                    newVal = max;
                                } else {
                                    newVal = oldValue + 1;
                                }
                            } else {
                                const min = parseFloat($input.attr('min'));
                                if (min && oldValue <= min) {
                                    newVal = min;
                                } else if (oldValue > 0) {
                                    newVal = oldValue - 1;
                                } else {
                                    newVal = 0;
                                }
                            }
                            
                            $input.val(newVal).trigger('change');
                        });
                    }
                });
            },
            
            setupAjaxCart: function() {
                // Add to cart ajax functionality
                $(document).on('click', '.ajax_add_to_cart', function() {
                    $(document.body).on('added_to_cart', function() {
                        // Show mini cart or notification
                        $('.header-cart-dropdown').addClass('show');
                        
                        // Hide after 5 seconds
                        setTimeout(function() {
                            $('.header-cart-dropdown').removeClass('show');
                        }, 5000);
                    });
                });
                
                // Close mini cart when clicking outside
                $(document).on('click', function(event) {
                    if (!$(event.target).closest('.header-cart, .header-cart-dropdown').length) {
                        $('.header-cart-dropdown').removeClass('show');
                    }
                });
            },
            
            setupQuickView: function() {
                // Quick view functionality
                $(document).on('click', '.quick-view', function(e) {
                    e.preventDefault();
                    
                    const productId = $(this).data('product-id');
                    
                    $.ajax({
                        url: aqualuxeData.ajaxUrl,
                        type: 'post',
                        data: {
                            action: 'aqualuxe_quick_view',
                            product_id: productId,
                            nonce: aqualuxeData.nonce
                        },
                        beforeSend: function() {
                            $('body').addClass('loading');
                        },
                        success: function(response) {
                            $('body').removeClass('loading');
                            
                            // Create modal if it doesn't exist
                            if (!$('#quick-view-modal').length) {
                                $('body').append('<div id="quick-view-modal" class="modal"><div class="modal-content"><span class="close">&times;</span><div class="modal-body"></div></div></div>');
                            }
                            
                            // Add response to modal
                            $('#quick-view-modal .modal-body').html(response);
                            
                            // Show modal
                            $('#quick-view-modal').addClass('show');
                            
                            // Initialize product gallery
                            if (typeof $.fn.wc_product_gallery !== 'undefined') {
                                $('#quick-view-modal .woocommerce-product-gallery').wc_product_gallery();
                            }
                            
                            // Setup quantity buttons
                            AquaLuxe.woocommerce.setupQuantityButtons();
                        }
                    });
                });
                
                // Close modal when clicking on close button or outside
                $(document).on('click', '#quick-view-modal .close, #quick-view-modal', function(e) {
                    if (e.target === this || $(e.target).hasClass('close')) {
                        $('#quick-view-modal').removeClass('show');
                    }
                });
            }
        },

        /**
         * Modules functionality
         */
        modules: {
            init: function() {
                // Initialize active modules
                if (typeof aqualuxeModules !== 'undefined') {
                    if (aqualuxeModules.darkMode) {
                        this.darkMode.init();
                    }
                    
                    if (aqualuxeModules.multilingual) {
                        this.multilingual.init();
                    }
                    
                    if (aqualuxeModules.multicurrency) {
                        this.multicurrency.init();
                    }
                }
            },
            
            darkMode: {
                init: function() {
                    // Dark mode functionality will be implemented in the module's JS file
                }
            },
            
            multilingual: {
                init: function() {
                    // Multilingual functionality will be implemented in the module's JS file
                }
            },
            
            multicurrency: {
                init: function() {
                    // Multicurrency functionality will be implemented in the module's JS file
                }
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxe.init();
    });

})(jQuery);