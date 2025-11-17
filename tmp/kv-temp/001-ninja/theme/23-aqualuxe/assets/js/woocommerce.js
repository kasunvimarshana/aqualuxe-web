/**
 * WooCommerce specific JavaScript for the AquaLuxe theme
 *
 * Contains all the WooCommerce specific JavaScript functionality
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Quick view functionality
        $('.quick-view-button').on('click', function(e) {
            e.preventDefault();
            
            const productId = $(this).data('product-id');
            
            $.ajax({
                url: aqualuxeWooCommerce.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    nonce: aqualuxeWooCommerce.quick_view_nonce
                },
                beforeSend: function() {
                    // Show loading overlay
                    $('body').append('<div class="quick-view-overlay"><div class="quick-view-loader"></div></div>');
                },
                success: function(response) {
                    // Remove loading overlay
                    $('.quick-view-overlay').remove();
                    
                    // Append modal to body
                    $('body').append(response);
                    
                    // Show modal
                    $('.quick-view-modal').addClass('open');
                    
                    // Initialize product gallery in quick view
                    $('.quick-view-modal .woocommerce-product-gallery').each(function() {
                        $(this).wc_product_gallery();
                    });
                    
                    // Initialize variation form
                    $('.quick-view-modal .variations_form').each(function() {
                        $(this).wc_variation_form();
                    });
                    
                    // Quantity buttons
                    initQuantityButtons();
                    
                    // Close modal on click outside
                    $('.quick-view-modal').on('click', function(e) {
                        if ($(e.target).hasClass('quick-view-modal')) {
                            closeQuickView();
                        }
                    });
                    
                    // Close modal on close button click
                    $('.quick-view-close').on('click', function(e) {
                        e.preventDefault();
                        closeQuickView();
                    });
                    
                    // Close modal on ESC key
                    $(document).on('keyup.quickView', function(e) {
                        if (e.keyCode === 27) {
                            closeQuickView();
                        }
                    });
                },
                error: function() {
                    // Remove loading overlay
                    $('.quick-view-overlay').remove();
                    
                    // Show error message
                    $('body').append('<div class="woocommerce-error">' + aqualuxeWooCommerce.i18n_quick_view_error + '</div>');
                    
                    // Remove error message after 3 seconds
                    setTimeout(function() {
                        $('.woocommerce-error').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }, 3000);
                }
            });
        });
        
        // Close quick view function
        function closeQuickView() {
            $('.quick-view-modal').removeClass('open');
            
            // Remove modal after animation
            setTimeout(function() {
                $('.quick-view-modal').remove();
            }, 300);
            
            // Remove ESC key event
            $(document).off('keyup.quickView');
        }
        
        // Wishlist functionality
        $('.add-to-wishlist').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const productId = button.data('product-id');
            
            $.ajax({
                url: aqualuxeWooCommerce.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_wishlist',
                    product_id: productId,
                    nonce: aqualuxeWooCommerce.wishlist_nonce
                },
                beforeSend: function() {
                    button.addClass('loading');
                },
                success: function(response) {
                    button.removeClass('loading');
                    
                    if (response.success) {
                        if (response.data.action === 'added') {
                            button.addClass('added');
                            button.attr('title', aqualuxeWooCommerce.i18n_browse_wishlist);
                            button.find('.add-to-wishlist-text').text(aqualuxeWooCommerce.i18n_browse_wishlist);
                            
                            // Show success message
                            $('body').append('<div class="woocommerce-message">' + aqualuxeWooCommerce.i18n_added_to_wishlist + '</div>');
                        } else {
                            button.removeClass('added');
                            button.attr('title', aqualuxeWooCommerce.i18n_add_to_wishlist);
                            button.find('.add-to-wishlist-text').text(aqualuxeWooCommerce.i18n_add_to_wishlist);
                            
                            // Show success message
                            $('body').append('<div class="woocommerce-message">' + aqualuxeWooCommerce.i18n_removed_from_wishlist + '</div>');
                        }
                        
                        // Remove message after 3 seconds
                        setTimeout(function() {
                            $('.woocommerce-message').fadeOut(300, function() {
                                $(this).remove();
                            });
                        }, 3000);
                        
                        // Update wishlist counter
                        updateWishlistCounter();
                    } else {
                        // Show error message
                        $('body').append('<div class="woocommerce-error">' + response.data.message + '</div>');
                        
                        // Remove error message after 3 seconds
                        setTimeout(function() {
                            $('.woocommerce-error').fadeOut(300, function() {
                                $(this).remove();
                            });
                        }, 3000);
                    }
                },
                error: function() {
                    button.removeClass('loading');
                    
                    // Show error message
                    $('body').append('<div class="woocommerce-error">' + aqualuxeWooCommerce.i18n_wishlist_error + '</div>');
                    
                    // Remove error message after 3 seconds
                    setTimeout(function() {
                        $('.woocommerce-error').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }, 3000);
                }
            });
        });
        
        // Update wishlist counter function
        function updateWishlistCounter() {
            $.ajax({
                url: aqualuxeWooCommerce.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_wishlist_count',
                    nonce: aqualuxeWooCommerce.wishlist_nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.wishlist-count').text(response.data.count);
                    }
                }
            });
        }
        
        // Remove from wishlist
        $(document).on('click', '.remove-from-wishlist', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const productId = button.data('product-id');
            const productItem = button.closest('.wishlist-item');
            
            $.ajax({
                url: aqualuxeWooCommerce.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_wishlist',
                    product_id: productId,
                    nonce: aqualuxeWooCommerce.wishlist_nonce
                },
                beforeSend: function() {
                    button.addClass('loading');
                },
                success: function(response) {
                    button.removeClass('loading');
                    
                    if (response.success) {
                        productItem.fadeOut(300, function() {
                            $(this).remove();
                            
                            // If no items left, show empty message
                            if ($('.wishlist-item').length === 0) {
                                $('.wishlist-items').html('<div class="woocommerce-info">' + aqualuxeWooCommerce.i18n_empty_wishlist + '</div>');
                            }
                            
                            // Update wishlist counter
                            updateWishlistCounter();
                        });
                    } else {
                        // Show error message
                        $('body').append('<div class="woocommerce-error">' + response.data.message + '</div>');
                        
                        // Remove error message after 3 seconds
                        setTimeout(function() {
                            $('.woocommerce-error').fadeOut(300, function() {
                                $(this).remove();
                            });
                        }, 3000);
                    }
                },
                error: function() {
                    button.removeClass('loading');
                    
                    // Show error message
                    $('body').append('<div class="woocommerce-error">' + aqualuxeWooCommerce.i18n_wishlist_error + '</div>');
                    
                    // Remove error message after 3 seconds
                    setTimeout(function() {
                        $('.woocommerce-error').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }, 3000);
                }
            });
        });
        
        // Initialize quantity buttons
        function initQuantityButtons() {
            // Quantity buttons
            $('.quantity').each(function() {
                const $this = $(this);
                const $input = $this.find('input.qty');
                
                // Don't add buttons if they already exist
                if ($this.find('.quantity-button').length > 0) {
                    return;
                }
                
                // Add minus button
                $input.before('<button type="button" class="quantity-button minus">-</button>');
                
                // Add plus button
                $input.after('<button type="button" class="quantity-button plus">+</button>');
                
                // Minus button click
                $this.on('click', '.minus', function(e) {
                    e.preventDefault();
                    
                    let val = parseFloat($input.val());
                    const min = parseFloat($input.attr('min'));
                    
                    if (val > min) {
                        $input.val(val - 1).trigger('change');
                    }
                });
                
                // Plus button click
                $this.on('click', '.plus', function(e) {
                    e.preventDefault();
                    
                    let val = parseFloat($input.val());
                    const max = parseFloat($input.attr('max'));
                    
                    if (max && val >= max) {
                        $input.val(max);
                    } else {
                        $input.val(val + 1).trigger('change');
                    }
                });
            });
        }
        
        // Initialize quantity buttons on page load
        initQuantityButtons();
        
        // Initialize quantity buttons after AJAX cart update
        $(document.body).on('updated_cart_totals', function() {
            initQuantityButtons();
        });
        
        // Initialize quantity buttons after AJAX add to cart
        $(document.body).on('added_to_cart', function() {
            initQuantityButtons();
        });
        
        // Product gallery
        $('.woocommerce-product-gallery').each(function() {
            const $gallery = $(this);
            const $mainImage = $gallery.find('.woocommerce-product-gallery__image:first-child a');
            const $thumbnails = $gallery.find('.woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image:not(:first-child) a');
            
            // Lightbox for main image
            $mainImage.attr('data-fancybox', 'product-gallery');
            
            // Lightbox for thumbnails
            $thumbnails.attr('data-fancybox', 'product-gallery');
            
            // Initialize Fancybox if available
            if ($.fancybox) {
                $('[data-fancybox="product-gallery"]').fancybox({
                    buttons: [
                        'zoom',
                        'slideShow',
                        'fullScreen',
                        'thumbs',
                        'close'
                    ],
                    loop: true,
                    protect: true,
                    animationEffect: 'fade',
                    transitionEffect: 'fade',
                    preventCaptionOverlap: true,
                    idleTime: 3,
                    gutter: 50,
                    clickOutside: 'close',
                    clickSlide: 'close',
                    mobile: {
                        clickContent: 'close',
                        clickSlide: 'close'
                    }
                });
            }
            
            // Zoom functionality
            if ($.fn.zoom && $gallery.data('zoom-enabled')) {
                const zoomOptions = {
                    touch: false,
                    magnify: 1.5
                };
                
                // Zoom on hover
                $mainImage.parent().zoom(zoomOptions);
                
                // Update zoom on gallery change
                $gallery.on('woocommerce_gallery_init_zoom', function() {
                    $gallery.find('.woocommerce-product-gallery__image:first-child a').parent().trigger('zoom.destroy').zoom(zoomOptions);
                });
            }
        });
        
        // Product tabs
        $('.wc-tabs-wrapper, .woocommerce-tabs').each(function() {
            const $tabs = $(this);
            const $tabLinks = $tabs.find('.wc-tabs li a, ul.tabs li a');
            const $tabPanels = $tabs.find('.wc-tab, .panel:not(.panel .panel)');
            
            // Hide all panels except the active one
            $tabPanels.not('.active').hide();
            
            // Click event for tab links
            $tabLinks.on('click', function(e) {
                e.preventDefault();
                
                const $link = $(this);
                const $li = $link.parent();
                const $target = $($link.attr('href'));
                
                // Remove active class from all tabs
                $li.siblings().removeClass('active');
                $tabPanels.removeClass('active').hide();
                
                // Add active class to current tab
                $li.addClass('active');
                $target.addClass('active').show();
                
                // Trigger custom event
                $(document.body).trigger('aqualuxe_tab_changed', [$link.attr('href')]);
            });
            
            // Set active tab on page load
            const hash = window.location.hash;
            const url = window.location.href;
            const $hashTab = hash ? $tabLinks.filter('[href="' + hash + '"]') : null;
            
            if ($hashTab && $hashTab.length) {
                $hashTab.trigger('click');
            } else if (url.indexOf('#comment-') > 0 || url.indexOf('comment-page-') > 0 || url.indexOf('#reviews') > 0 || url.indexOf('#tab-reviews') > 0) {
                $tabLinks.filter('[href="#tab-reviews"]').trigger('click');
            } else {
                $tabLinks.first().trigger('click');
            }
        });
        
        // Star rating in reviews
        $('.comment-form-rating .stars a').on('hover', function() {
            $(this).addClass('hover');
            $(this).prevAll().addClass('hover');
            $(this).nextAll().removeClass('hover');
        }).on('mouseout', function() {
            $(this).removeClass('hover');
            $(this).prevAll().removeClass('hover');
        });
        
        // Shop filters toggle
        $('.filter-toggle').on('click', function(e) {
            e.preventDefault();
            $('.shop-filters').slideToggle(300);
            $(this).toggleClass('active');
        });
        
        // Price range slider
        if ($.fn.slider && $('.price_slider').length > 0) {
            const $priceSlider = $('.price_slider');
            const $minPrice = $('.price_slider_amount #min_price');
            const $maxPrice = $('.price_slider_amount #max_price');
            const $minAmount = $('.price_slider_amount .from');
            const $maxAmount = $('.price_slider_amount .to');
            const minPrice = parseInt($minPrice.data('min'), 10);
            const maxPrice = parseInt($maxPrice.data('max'), 10);
            const currentMinPrice = parseInt($minPrice.val(), 10);
            const currentMaxPrice = parseInt($maxPrice.val(), 10);
            
            $priceSlider.slider({
                range: true,
                min: minPrice,
                max: maxPrice,
                values: [currentMinPrice, currentMaxPrice],
                create: function() {
                    $minPrice.val(currentMinPrice);
                    $maxPrice.val(currentMaxPrice);
                    
                    $(document.body).trigger('price_slider_create', [currentMinPrice, currentMaxPrice]);
                },
                slide: function(event, ui) {
                    $minPrice.val(ui.values[0]);
                    $maxPrice.val(ui.values[1]);
                    
                    $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
                },
                change: function(event, ui) {
                    $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                }
            });
        }
        
        // AJAX add to cart on single product page
        if (aqualuxeWooCommerce.enable_ajax_add_to_cart === 'yes' && $('.single_add_to_cart_button').length > 0) {
            $('.single_add_to_cart_button').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const $form = $button.closest('form.cart');
                
                // Don't proceed if already processing
                if ($button.hasClass('loading')) {
                    return;
                }
                
                // Don't proceed if disabled
                if ($button.is('.disabled') || $button.is(':disabled')) {
                    return;
                }
                
                // Get form data
                const formData = new FormData($form[0]);
                formData.append('add-to-cart', $form.find('[name="add-to-cart"]').val());
                formData.append('action', 'aqualuxe_ajax_add_to_cart');
                formData.append('nonce', aqualuxeWooCommerce.add_to_cart_nonce);
                
                // AJAX add to cart
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $button.addClass('loading');
                    },
                    success: function(response) {
                        $button.removeClass('loading');
                        
                        if (response.error) {
                            // Show error message
                            $form.append('<div class="woocommerce-error">' + response.error + '</div>');
                            
                            // Remove error message after 3 seconds
                            setTimeout(function() {
                                $form.find('.woocommerce-error').fadeOut(300, function() {
                                    $(this).remove();
                                });
                            }, 3000);
                        } else {
                            // Show success message
                            $form.append('<div class="woocommerce-message">' + response.message + '</div>');
                            
                            // Remove success message after 3 seconds
                            setTimeout(function() {
                                $form.find('.woocommerce-message').fadeOut(300, function() {
                                    $(this).remove();
                                });
                            }, 3000);
                            
                            // Update cart fragments
                            if (response.fragments) {
                                $.each(response.fragments, function(key, value) {
                                    $(key).replaceWith(value);
                                });
                            }
                            
                            // Update cart hash
                            if (response.cart_hash) {
                                $('.woocommerce-cart-hash').val(response.cart_hash);
                            }
                            
                            // Trigger event
                            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                        }
                    },
                    error: function() {
                        $button.removeClass('loading');
                        
                        // Show error message
                        $form.append('<div class="woocommerce-error">' + aqualuxeWooCommerce.i18n_add_to_cart_error + '</div>');
                        
                        // Remove error message after 3 seconds
                        setTimeout(function() {
                            $form.find('.woocommerce-error').fadeOut(300, function() {
                                $(this).remove();
                            });
                        }, 3000);
                    }
                });
            });
        }
        
        // Product filter widgets
        $('.widget_layered_nav_filters').on('click', 'a', function() {
            const $link = $(this);
            const href = $link.attr('href');
            const filterKey = $link.data('filter-key');
            
            if (filterKey) {
                // Remove filter from URL
                const newUrl = href.replace(new RegExp('&?' + filterKey + '=[^&]*'), '');
                
                // Redirect to new URL
                window.location.href = newUrl;
                
                return false;
            }
        });
        
        // Product sorting
        $('.woocommerce-ordering select.orderby').on('change', function() {
            $(this).closest('form').submit();
        });
        
        // Product view switcher
        $('.shop-view-switcher a').on('click', function(e) {
            e.preventDefault();
            
            const $link = $(this);
            const view = $link.data('view');
            
            // Don't proceed if already active
            if ($link.hasClass('active')) {
                return;
            }
            
            // Update active class
            $('.shop-view-switcher a').removeClass('active');
            $link.addClass('active');
            
            // Update products wrapper class
            $('.products').removeClass('grid-view list-view').addClass(view + '-view');
            
            // Save preference in cookie
            document.cookie = 'aqualuxe_shop_view=' + view + '; path=/; max-age=31536000'; // 1 year
        });
        
        // Initialize shop view from cookie
        const shopViewCookie = document.cookie.replace(/(?:(?:^|.*;\s*)aqualuxe_shop_view\s*\=\s*([^;]*).*$)|^.*$/, '$1');
        
        if (shopViewCookie) {
            $('.shop-view-switcher a[data-view="' + shopViewCookie + '"]').trigger('click');
        }
        
        // Product categories widget accordion
        $('.widget_product_categories .cat-parent').each(function() {
            const $parent = $(this);
            const $children = $parent.find('> .children');
            
            // Add toggle button
            $parent.find('> a').after('<button class="category-toggle" aria-expanded="false"><span class="screen-reader-text">Toggle subcategories</span></button>');
            
            // Hide children by default
            $children.hide();
            
            // Show children if parent is current
            if ($parent.hasClass('current-cat') || $parent.hasClass('current-cat-parent')) {
                $children.show();
                $parent.find('> .category-toggle').attr('aria-expanded', 'true');
            }
            
            // Toggle children on button click
            $parent.find('> .category-toggle').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const expanded = $button.attr('aria-expanded') === 'true';
                
                $button.attr('aria-expanded', !expanded);
                $children.slideToggle(300);
            });
        });
    });

})(jQuery);