/**
 * AquaLuxe Theme WooCommerce JavaScript
 *
 * This file contains all the WooCommerce specific JavaScript functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // AquaLuxe WooCommerce object
    var AquaLuxeWC = {
        /**
         * Initialize the WooCommerce scripts
         */
        init: function() {
            this.initQuantityButtons();
            this.initAjaxAddToCart();
            this.initQuickView();
            this.initProductGallery();
            this.initProductTabs();
            this.initProductFAQ();
            this.initCountdownTimer();
            this.initProductInquiry();
            this.initProduct360View();
            this.initMobileFilters();
            this.initStickyProductSummary();
            this.initCheckoutSteps();
        },

        /**
         * Initialize quantity buttons
         */
        initQuantityButtons: function() {
            // Quantity buttons
            $(document).on('click', '.quantity-button', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var $input = $button.parent().find('input.qty');
                var oldValue = parseFloat($input.val());
                var newVal;
                
                if ($button.hasClass('plus')) {
                    var max = parseFloat($input.attr('max'));
                    if (max && (max <= oldValue)) {
                        newVal = max;
                    } else {
                        newVal = oldValue + 1;
                    }
                } else {
                    var min = parseFloat($input.attr('min'));
                    if (min && (min >= oldValue)) {
                        newVal = min;
                    } else if (oldValue > 1) {
                        newVal = oldValue - 1;
                    } else {
                        newVal = 1;
                    }
                }
                
                $input.val(newVal);
                $input.trigger('change');
            });
            
            // Add quantity buttons to inputs
            $('.quantity:not(.buttons-added)').addClass('buttons-added').append('<button type="button" class="quantity-button plus">+</button>').prepend('<button type="button" class="quantity-button minus">-</button>');
        },

        /**
         * Initialize AJAX add to cart
         */
        initAjaxAddToCart: function() {
            $(document).on('click', '.ajax_add_to_cart', function(e) {
                var $thisbutton = $(this);
                
                if ($thisbutton.is('.loading')) {
                    return false;
                }
                
                // AJAX add to cart request
                var data = {
                    action: 'aqualuxe_add_to_cart',
                    product_id: $thisbutton.data('product_id'),
                    quantity: $thisbutton.data('quantity') || 1
                };
                
                // Trigger event
                $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
                
                // Ajax action
                $.ajax({
                    type: 'POST',
                    url: aqualuxe_wc_params.ajax_url,
                    data: data,
                    beforeSend: function() {
                        $thisbutton.removeClass('added').addClass('loading');
                    },
                    complete: function() {
                        $thisbutton.removeClass('loading').addClass('added');
                    },
                    success: function(response) {
                        if (response) {
                            // Update cart fragments
                            if (response.fragments) {
                                $.each(response.fragments, function(key, value) {
                                    $(key).replaceWith(value);
                                });
                            }
                            
                            // Show mini cart
                            AquaLuxeWC.showMiniCart();
                            
                            // Trigger event
                            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                        }
                    }
                });
                
                return false;
            });
        },

        /**
         * Show mini cart
         */
        showMiniCart: function() {
            $('.mini-cart-wrapper').addClass('open');
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $('.mini-cart-wrapper').removeClass('open');
            }, 5000);
        },

        /**
         * Initialize quick view
         */
        initQuickView: function() {
            $(document).on('click', '.quick-view-button', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var productId = $button.data('product-id');
                
                // Show loading overlay
                $('body').append('<div class="quick-view-overlay"><div class="quick-view-loader"></div></div>');
                
                // AJAX request
                $.ajax({
                    type: 'POST',
                    url: aqualuxe_wc_params.ajax_url,
                    data: {
                        action: 'aqualuxe_quick_view',
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.success) {
                            // Create modal
                            $('body').append('<div class="quick-view-modal"><div class="quick-view-modal-content"><span class="quick-view-close">&times;</span>' + response.data + '</div></div>');
                            
                            // Initialize product gallery
                            AquaLuxeWC.initQuickViewGallery();
                            
                            // Initialize quantity buttons
                            AquaLuxeWC.initQuantityButtons();
                            
                            // Show modal
                            setTimeout(function() {
                                $('.quick-view-modal').addClass('open');
                            }, 100);
                        }
                    },
                    complete: function() {
                        // Remove loading overlay
                        $('.quick-view-overlay').remove();
                    }
                });
            });
            
            // Close quick view modal
            $(document).on('click', '.quick-view-close, .quick-view-modal', function(e) {
                if ($(e.target).is('.quick-view-modal') || $(e.target).is('.quick-view-close')) {
                    $('.quick-view-modal').removeClass('open');
                    
                    setTimeout(function() {
                        $('.quick-view-modal').remove();
                    }, 300);
                }
            });
        },

        /**
         * Initialize quick view gallery
         */
        initQuickViewGallery: function() {
            if ($('.quick-view-images').length) {
                $('.quick-view-images').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: true,
                    fade: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>'
                });
            }
        },

        /**
         * Initialize product gallery
         */
        initProductGallery: function() {
            // Product gallery slider
            if ($('.woocommerce-product-gallery__wrapper').length) {
                $('.woocommerce-product-gallery__wrapper').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: true,
                    fade: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    asNavFor: '.product-thumbnails'
                });
                
                // Product thumbnails
                $('.product-thumbnails').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    asNavFor: '.woocommerce-product-gallery__wrapper',
                    dots: false,
                    arrows: false,
                    focusOnSelect: true,
                    centerMode: false,
                    responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 3
                            }
                        }
                    ]
                });
            }
        },

        /**
         * Initialize product tabs
         */
        initProductTabs: function() {
            $('.woocommerce-tabs').each(function() {
                var $tabs = $(this);
                var $tabLinks = $tabs.find('.wc-tabs li a');
                var $tabContents = $tabs.find('.woocommerce-Tabs-panel');
                
                $tabLinks.on('click', function(e) {
                    e.preventDefault();
                    
                    var $this = $(this);
                    var tabId = $this.attr('href');
                    
                    // Remove active class from all tabs
                    $tabLinks.parent().removeClass('active');
                    $tabContents.hide();
                    
                    // Add active class to current tab
                    $this.parent().addClass('active');
                    $(tabId).show();
                });
            });
        },

        /**
         * Initialize product FAQ
         */
        initProductFAQ: function() {
            $(document).on('click', '.product-faq-question', function() {
                var $item = $(this).closest('.product-faq-item');
                
                if ($item.hasClass('active')) {
                    $item.removeClass('active');
                    $item.find('.product-faq-answer').slideUp(300);
                } else {
                    $('.product-faq-item').removeClass('active');
                    $('.product-faq-answer').slideUp(300);
                    
                    $item.addClass('active');
                    $item.find('.product-faq-answer').slideDown(300);
                }
            });
        },

        /**
         * Initialize countdown timer
         */
        initCountdownTimer: function() {
            $('.product-countdown').each(function() {
                var $countdown = $(this);
                var endDate = new Date($countdown.data('end-date')).getTime();
                
                // Update countdown every second
                var countdownInterval = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = endDate - now;
                    
                    // If countdown is finished
                    if (distance < 0) {
                        clearInterval(countdownInterval);
                        $countdown.remove();
                        return;
                    }
                    
                    // Calculate days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    // Display the result
                    $countdown.find('.days').text(days < 10 ? '0' + days : days);
                    $countdown.find('.hours').text(hours < 10 ? '0' + hours : hours);
                    $countdown.find('.minutes').text(minutes < 10 ? '0' + minutes : minutes);
                    $countdown.find('.seconds').text(seconds < 10 ? '0' + seconds : seconds);
                }, 1000);
            });
        },

        /**
         * Initialize product inquiry
         */
        initProductInquiry: function() {
            // Open modal
            $(document).on('click', '.product-inquiry-button', function(e) {
                e.preventDefault();
                
                var $modal = $('#product-inquiry-modal');
                $modal.addClass('open');
            });
            
            // Close modal
            $(document).on('click', '.aqualuxe-modal-close, .aqualuxe-modal', function(e) {
                if ($(e.target).is('.aqualuxe-modal') || $(e.target).is('.aqualuxe-modal-close')) {
                    $('.aqualuxe-modal').removeClass('open');
                }
            });
            
            // Submit inquiry form
            $(document).on('submit', '.product-inquiry-form', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                var $submitButton = $form.find('.inquiry-submit');
                
                // Validate form
                var isValid = true;
                $form.find('[required]').each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        $(this).addClass('error');
                    } else {
                        $(this).removeClass('error');
                    }
                });
                
                if (!isValid) {
                    return;
                }
                
                // Disable submit button
                $submitButton.prop('disabled', true).text(aqualuxe_wc_params.i18n_loading);
                
                // AJAX request
                $.ajax({
                    type: 'POST',
                    url: aqualuxe_wc_params.ajax_url,
                    data: {
                        action: 'aqualuxe_product_inquiry',
                        nonce: aqualuxe_wc_params.nonce,
                        product_id: $form.find('input[name="product_id"]').val(),
                        product_name: $form.find('input[name="product_name"]').val(),
                        name: $form.find('input[name="name"]').val(),
                        email: $form.find('input[name="email"]').val(),
                        phone: $form.find('input[name="phone"]').val(),
                        message: $form.find('textarea[name="message"]').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            $form.html('<div class="inquiry-success"><i class="fas fa-check-circle"></i><p>' + response.data + '</p></div>');
                            
                            // Close modal after 3 seconds
                            setTimeout(function() {
                                $('.aqualuxe-modal').removeClass('open');
                            }, 3000);
                        } else {
                            // Show error message
                            $form.prepend('<div class="inquiry-error"><i class="fas fa-exclamation-circle"></i><p>' + response.data + '</p></div>');
                            
                            // Enable submit button
                            $submitButton.prop('disabled', false).text(aqualuxe_wc_params.i18n_send_inquiry);
                        }
                    },
                    error: function() {
                        // Show error message
                        $form.prepend('<div class="inquiry-error"><i class="fas fa-exclamation-circle"></i><p>' + aqualuxe_wc_params.i18n_inquiry_error + '</p></div>');
                        
                        // Enable submit button
                        $submitButton.prop('disabled', false).text(aqualuxe_wc_params.i18n_send_inquiry);
                    }
                });
            });
        },

        /**
         * Initialize product 360 view
         */
        initProduct360View: function() {
            // Open modal
            $(document).on('click', '.product-360-view-button', function(e) {
                e.preventDefault();
                
                var $modal = $('#product-360-modal');
                $modal.addClass('open');
                
                // Initialize 360 view
                AquaLuxeWC.init360View();
            });
            
            // Close modal
            $(document).on('click', '.aqualuxe-modal-close, .aqualuxe-modal', function(e) {
                if ($(e.target).is('.aqualuxe-modal') || $(e.target).is('.aqualuxe-modal-close')) {
                    $('.aqualuxe-modal').removeClass('open');
                }
            });
        },

        /**
         * Initialize 360 view
         */
        init360View: function() {
            var $container = $('.product-360-view');
            
            if ($container.length) {
                var images = $container.data('images').toString().split(',');
                var currentImage = 0;
                var totalImages = images.length;
                var $image = $container.find('.product-360-image');
                var $spinner = $container.find('.spinner');
                var loaded = 0;
                var dragging = false;
                var startX = 0;
                var currentX = 0;
                
                // Preload images
                $.each(images, function(i, src) {
                    var img = new Image();
                    img.onload = function() {
                        loaded++;
                        
                        if (loaded === totalImages) {
                            $spinner.hide();
                        }
                    };
                    img.src = src;
                });
                
                // Previous image
                $(document).on('click', '.product-360-prev', function(e) {
                    e.preventDefault();
                    
                    currentImage = (currentImage - 1 + totalImages) % totalImages;
                    $image.attr('src', images[currentImage]);
                });
                
                // Next image
                $(document).on('click', '.product-360-next', function(e) {
                    e.preventDefault();
                    
                    currentImage = (currentImage + 1) % totalImages;
                    $image.attr('src', images[currentImage]);
                });
                
                // Mouse events for drag rotation
                $container.on('mousedown touchstart', function(e) {
                    e.preventDefault();
                    
                    dragging = true;
                    startX = e.pageX || e.originalEvent.touches[0].pageX;
                    currentX = startX;
                });
                
                $(document).on('mousemove touchmove', function(e) {
                    if (dragging) {
                        e.preventDefault();
                        
                        var pageX = e.pageX || e.originalEvent.touches[0].pageX;
                        var delta = pageX - currentX;
                        currentX = pageX;
                        
                        if (Math.abs(delta) > 5) {
                            if (delta > 0) {
                                currentImage = (currentImage - 1 + totalImages) % totalImages;
                            } else {
                                currentImage = (currentImage + 1) % totalImages;
                            }
                            
                            $image.attr('src', images[currentImage]);
                        }
                    }
                });
                
                $(document).on('mouseup touchend', function() {
                    dragging = false;
                });
            }
        },

        /**
         * Initialize mobile filters
         */
        initMobileFilters: function() {
            // Toggle filter button
            $(document).on('click', '.filter-toggle-button', function(e) {
                e.preventDefault();
                
                $('.shop-sidebar').toggleClass('open');
                $('body').toggleClass('filter-open');
            });
            
            // Close filters when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.shop-sidebar').length && !$(e.target).closest('.filter-toggle-button').length) {
                    $('.shop-sidebar').removeClass('open');
                    $('body').removeClass('filter-open');
                }
            });
        },

        /**
         * Initialize sticky product summary
         */
        initStickyProductSummary: function() {
            if ($('.single-product').length && $(window).width() > 991) {
                var $summary = $('.summary.entry-summary');
                var $gallery = $('.woocommerce-product-gallery');
                
                if ($summary.length && $gallery.length) {
                    var summaryHeight = $summary.outerHeight();
                    var galleryHeight = $gallery.outerHeight();
                    
                    if (summaryHeight < galleryHeight) {
                        var headerHeight = $('#masthead').outerHeight();
                        
                        $(window).on('scroll', function() {
                            var scrollTop = $(window).scrollTop();
                            var galleryOffset = $gallery.offset().top;
                            var galleryBottom = galleryOffset + galleryHeight;
                            var summaryBottom = galleryOffset + summaryHeight;
                            
                            if (scrollTop + headerHeight > galleryOffset && scrollTop + headerHeight + summaryHeight < galleryBottom) {
                                $summary.css({
                                    'position': 'fixed',
                                    'top': headerHeight + 20 + 'px',
                                    'width': $summary.width() + 'px'
                                });
                            } else if (scrollTop + headerHeight + summaryHeight >= galleryBottom) {
                                $summary.css({
                                    'position': 'absolute',
                                    'top': galleryHeight - summaryHeight + 'px',
                                    'width': $summary.width() + 'px'
                                });
                            } else {
                                $summary.css({
                                    'position': 'static',
                                    'top': 'auto',
                                    'width': 'auto'
                                });
                            }
                        });
                    }
                }
            }
        },

        /**
         * Initialize checkout steps
         */
        initCheckoutSteps: function() {
            if ($('.woocommerce-checkout').length) {
                // Update steps based on current section
                var updateSteps = function() {
                    var currentStep = 1;
                    
                    if ($('#ship-to-different-address-checkbox').is(':checked') || $('#shipping_method').is(':visible')) {
                        currentStep = 2;
                    }
                    
                    if ($('#payment').is(':visible')) {
                        currentStep = 3;
                    }
                    
                    $('.checkout-steps .step').removeClass('active completed');
                    
                    for (var i = 1; i <= currentStep; i++) {
                        if (i < currentStep) {
                            $('.checkout-steps .step:nth-child(' + (i * 2 - 1) + ')').addClass('completed');
                        } else {
                            $('.checkout-steps .step:nth-child(' + (i * 2 - 1) + ')').addClass('active');
                        }
                    }
                };
                
                // Update steps on page load
                updateSteps();
                
                // Update steps when shipping checkbox changes
                $('#ship-to-different-address-checkbox').on('change', function() {
                    updateSteps();
                });
                
                // Update steps when payment method changes
                $('body').on('payment_method_selected', function() {
                    updateSteps();
                });
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeWC.init();
    });

})(jQuery);