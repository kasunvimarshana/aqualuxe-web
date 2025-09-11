// Main WooCommerce functionality
(function($) {
    'use strict';
    
    const AquaLuxeWooCommerce = {
        init: function() {
            this.enhanceProductGallery();
            this.setupQuantityButtons();
            this.enhanceVariations();
            this.setupAjaxAddToCart();
        },
        
        enhanceProductGallery: function() {
            // Enhance product image gallery
            $('.woocommerce-product-gallery').each(function() {
                const $gallery = $(this);
                
                // Add zoom functionality if not already present
                if (!$gallery.hasClass('wc-product-gallery-slider')) {
                    $gallery.find('img').on('click', function() {
                        // Simple lightbox functionality
                        const src = $(this).attr('src');
                        if (src) {
                            window.open(src, '_blank');
                        }
                    });
                }
            });
        },
        
        setupQuantityButtons: function() {
            // Add plus/minus buttons to quantity inputs
            $('.quantity').each(function() {
                const $quantity = $(this);
                const $input = $quantity.find('input[type="number"]');
                
                if ($input.length && !$quantity.find('.qty-btn').length) {
                    const min = parseInt($input.attr('min') || 1);
                    const max = parseInt($input.attr('max') || 999);
                    
                    $input.wrap('<div class="qty-wrapper"></div>');
                    const $wrapper = $input.parent();
                    
                    $wrapper.prepend('<button type="button" class="qty-btn qty-minus">-</button>');
                    $wrapper.append('<button type="button" class="qty-btn qty-plus">+</button>');
                    
                    // Bind events
                    $wrapper.on('click', '.qty-minus', function(e) {
                        e.preventDefault();
                        const currentVal = parseInt($input.val()) || min;
                        if (currentVal > min) {
                            $input.val(currentVal - 1).trigger('change');
                        }
                    });
                    
                    $wrapper.on('click', '.qty-plus', function(e) {
                        e.preventDefault();
                        const currentVal = parseInt($input.val()) || 0;
                        if (currentVal < max) {
                            $input.val(currentVal + 1).trigger('change');
                        }
                    });
                }
            });
        },
        
        enhanceVariations: function() {
            // Enhance variable product functionality
            $('.variations_form').each(function() {
                const $form = $(this);
                
                $form.on('woocommerce_variation_has_changed', function() {
                    // Update variation-specific content
                    console.log('Variation changed');
                });
            });
        },
        
        setupAjaxAddToCart: function() {
            // Enhanced AJAX add to cart functionality
            $(document).on('click', '.ajax_add_to_cart', function(e) {
                const $button = $(this);
                const originalText = $button.text();
                
                $button.addClass('loading').text('Adding...');
                
                // The default WooCommerce AJAX will handle the actual add to cart
                // We just enhance the UI feedback
                $(document).one('added_to_cart', function() {
                    $button.removeClass('loading').text('Added!');
                    
                    setTimeout(function() {
                        $button.text(originalText);
                    }, 2000);
                });
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeWooCommerce.init();
    });
    
})(jQuery);