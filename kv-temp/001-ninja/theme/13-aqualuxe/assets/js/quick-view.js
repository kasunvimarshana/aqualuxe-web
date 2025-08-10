/**
 * AquaLuxe Theme Quick View JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Quick View Object
     */
    var AquaLuxeQuickView = {
        /**
         * Initialize
         */
        init: function() {
            this.modal = $('#quick-view-modal');
            this.content = this.modal.find('.quick-view-content');
            this.loading = this.modal.find('.quick-view-loading');
            
            this.setupEventListeners();
        },

        /**
         * Setup Event Listeners
         */
        setupEventListeners: function() {
            var self = this;

            // Quick view button click
            $(document).on('click', '.quick-view-button', function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                self.openQuickView(productId);
            });

            // Close button click
            $('#quick-view-close').on('click', function() {
                self.closeQuickView();
            });

            // Close on overlay click
            this.modal.on('click', function(e) {
                if ($(e.target).is(self.modal)) {
                    self.closeQuickView();
                }
            });

            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && !self.modal.hasClass('hidden')) {
                    self.closeQuickView();
                }
            });

            // Handle thumbnail clicks in quick view
            $(document).on('click', '.quick-view-content .thumbnail', function() {
                var $thumbnail = $(this);
                var $image = $thumbnail.find('img');
                var $mainImage = $('.quick-view-content .main-image img');
                var imageSrc = $image.attr('src');
                
                // Update main image
                $mainImage.attr('src', imageSrc);
                
                // Update active thumbnail
                $('.quick-view-content .thumbnail').removeClass('border-primary');
                $thumbnail.addClass('border-primary');
            });

            // Handle add to cart in quick view
            $(document).on('click', '.quick-view-content .single_add_to_cart_button', function(e) {
                e.preventDefault();
                var $button = $(this);
                var $form = $button.closest('form');
                
                // Don't proceed if already loading
                if ($button.is('.loading')) {
                    return;
                }
                
                // Add loading state
                $button.addClass('loading');
                
                // Get form data
                var formData = $form.serialize();
                
                // Add action
                formData += '&action=woocommerce_add_to_cart';
                
                // Add to cart via AJAX
                $.ajax({
                    url: wc_add_to_cart_params.ajax_url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.error && response.product_url) {
                            window.location = response.product_url;
                            return;
                        }
                        
                        // Update cart fragments
                        if (response.fragments) {
                            $.each(response.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                        
                        // Trigger event
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                        
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        // Close quick view
                        self.closeQuickView();
                    },
                    error: function() {
                        // Remove loading state
                        $button.removeClass('loading');
                        
                        // Show error
                        alert(wc_add_to_cart_params.i18n_ajax_error);
                    }
                });
            });
        },

        /**
         * Open Quick View
         *
         * @param {number} productId Product ID
         */
        openQuickView: function(productId) {
            var self = this;

            // Show modal and loading spinner
            this.modal.removeClass('hidden');
            this.content.addClass('hidden');
            this.loading.removeClass('hidden');

            // Load product data via AJAX
            $.ajax({
                url: aqualuxeQuickView.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_load_product_quick_view',
                    product_id: productId,
                    nonce: aqualuxeQuickView.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update modal content
                        self.modal.find('.product-title').html(response.data.title);
                        self.modal.find('.product-image').html(response.data.image);
                        self.modal.find('.product-price').html(response.data.price_html);
                        self.modal.find('.product-rating').html(response.data.rating_html);
                        self.modal.find('.product-excerpt').html(response.data.excerpt);
                        self.modal.find('.product-add-to-cart').html(response.data.add_to_cart_html);
                        self.modal.find('.view-full-details').attr('href', response.data.permalink);

                        // Initialize quantity buttons
                        self.initQuantityButtons();

                        // Initialize variation form if exists
                        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                            self.modal.find('.variations_form').wc_variation_form();
                        }

                        // Show content
                        self.content.removeClass('hidden');
                        self.loading.addClass('hidden');
                    } else {
                        self.closeQuickView();
                        alert(aqualuxeQuickView.i18n.error);
                    }
                },
                error: function() {
                    self.closeQuickView();
                    alert(aqualuxeQuickView.i18n.error);
                }
            });
        },

        /**
         * Close Quick View
         */
        closeQuickView: function() {
            this.modal.addClass('hidden');
        },

        /**
         * Initialize Quantity Buttons
         */
        initQuantityButtons: function() {
            // Add quantity buttons if they don't exist
            if (this.modal.find('.quantity').length > 0 && this.modal.find('.quantity-button').length === 0) {
                this.modal.find('.quantity').each(function() {
                    var $quantityInput = $(this).find('input.qty');
                    
                    // Add minus button
                    $('<button type="button" class="quantity-button minus">-</button>').insertBefore($quantityInput);
                    
                    // Add plus button
                    $('<button type="button" class="quantity-button plus">+</button>').insertAfter($quantityInput);
                });
            }

            // Handle quantity button clicks
            this.modal.find('.quantity-button').on('click', function() {
                var $button = $(this);
                var $input = $button.parent().find('input.qty');
                var oldValue = parseFloat($input.val());
                var max = parseFloat($input.attr('max'));
                var min = parseFloat($input.attr('min'));
                var step = parseFloat($input.attr('step'));

                if (isNaN(oldValue)) {
                    oldValue = 0;
                }

                if (isNaN(max)) {
                    max = 100;
                }

                if (isNaN(min)) {
                    min = 0;
                }

                if (isNaN(step)) {
                    step = 1;
                }

                if ($button.hasClass('plus')) {
                    if (oldValue < max) {
                        var newVal = oldValue + step;
                    } else {
                        var newVal = max;
                    }
                } else {
                    if (oldValue > min) {
                        var newVal = oldValue - step;
                    } else {
                        var newVal = min;
                    }
                }

                $input.val(newVal);
                $input.trigger('change');
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeQuickView.init();
    });

})(jQuery);