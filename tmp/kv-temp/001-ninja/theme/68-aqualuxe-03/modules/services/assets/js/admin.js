/**
 * AquaLuxe Services Module Admin JavaScript
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Services Admin
     */
    var AquaLuxeServicesAdmin = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initMetaBoxes();
            this.initServiceFeatures();
            this.initWooCommerceIntegration();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Price type change
            $(document).on('change', '#aqualuxe-service-price-type', this.handlePriceTypeChange);
            
            // Bookable checkbox change
            $(document).on('change', 'input[name="aqualuxe_service_bookable"]', this.handleBookableChange);
            
            // Service package services change
            $(document).on('change', 'input[name="aqualuxe_service_package_services[]"]', this.updatePackagePrice);
        },

        /**
         * Initialize meta boxes
         */
        initMetaBoxes: function() {
            // Initialize price type
            this.handlePriceTypeChange();
            
            // Initialize bookable
            this.handleBookableChange();
        },

        /**
         * Initialize service features
         */
        initServiceFeatures: function() {
            var $featuresField = $('#aqualuxe-service-features');
            
            if ($featuresField.length === 0) {
                return;
            }

            // Create features editor
            var $editor = $('<div class="aqualuxe-service-features-editor"></div>');
            var features = $featuresField.val().split('\n');
            
            // Add features
            $.each(features, function(index, feature) {
                if (feature.trim() !== '') {
                    AquaLuxeServicesAdmin.addFeatureItem($editor, feature);
                }
            });
            
            // Add button
            $editor.append('<button type="button" class="button add-feature">Add Feature</button>');
            
            // Insert editor after textarea
            $featuresField.after($editor);
            
            // Hide textarea
            $featuresField.hide();
            
            // Add feature button click
            $editor.on('click', '.add-feature', function() {
                AquaLuxeServicesAdmin.addFeatureItem($editor, '');
            });
            
            // Remove feature button click
            $editor.on('click', '.remove-feature', function() {
                $(this).closest('.feature-item').remove();
                AquaLuxeServicesAdmin.updateFeaturesField($featuresField, $editor);
            });
            
            // Feature input change
            $editor.on('input', '.feature-input', function() {
                AquaLuxeServicesAdmin.updateFeaturesField($featuresField, $editor);
            });
        },

        /**
         * Initialize WooCommerce integration
         */
        initWooCommerceIntegration: function() {
            var $wooCommerceIntegration = $('.aqualuxe-woocommerce-integration');
            
            if ($wooCommerceIntegration.length === 0) {
                return;
            }

            // Create product button click
            $wooCommerceIntegration.on('click', '.create-product', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var serviceId = $button.data('service-id');
                
                if (!serviceId) {
                    return;
                }

                // Disable button
                $button.prop('disabled', true).text('Creating...');
                
                // Create product
                $.ajax({
                    url: aqualuxeServicesAdmin.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_create_service_product',
                        nonce: aqualuxeServicesAdmin.nonce,
                        service_id: serviceId
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update button
                            $button.text('Product Created').addClass('button-disabled');
                            
                            // Show link
                            $wooCommerceIntegration.append('<p>Product created successfully. <a href="' + response.data.edit_url + '" target="_blank">Edit Product</a></p>');
                        } else {
                            // Show error
                            $wooCommerceIntegration.append('<p class="error">' + response.data.message + '</p>');
                            
                            // Enable button
                            $button.prop('disabled', false).text('Create Product');
                        }
                    },
                    error: function() {
                        // Show error
                        $wooCommerceIntegration.append('<p class="error">An error occurred. Please try again.</p>');
                        
                        // Enable button
                        $button.prop('disabled', false).text('Create Product');
                    }
                });
            });
        },

        /**
         * Handle price type change
         */
        handlePriceTypeChange: function() {
            var $priceType = $('#aqualuxe-service-price-type');
            var $priceField = $('#aqualuxe-service-price').closest('.aqualuxe-meta-box-row');
            var $salePriceField = $('#aqualuxe-service-sale-price').closest('.aqualuxe-meta-box-row');
            
            if ($priceType.length === 0) {
                return;
            }

            var priceType = $priceType.val();
            
            if (priceType === 'quote') {
                $priceField.hide();
                $salePriceField.hide();
            } else {
                $priceField.show();
                $salePriceField.show();
            }
        },

        /**
         * Handle bookable change
         */
        handleBookableChange: function() {
            var $bookable = $('input[name="aqualuxe_service_bookable"]');
            var $capacityField = $('#aqualuxe-service-capacity').closest('.aqualuxe-meta-box-row');
            
            if ($bookable.length === 0) {
                return;
            }

            if ($bookable.is(':checked')) {
                $capacityField.show();
            } else {
                $capacityField.hide();
            }
        },

        /**
         * Add feature item
         * 
         * @param {jQuery} $editor 
         * @param {string} feature 
         */
        addFeatureItem: function($editor, feature) {
            var $item = $('<div class="feature-item"></div>');
            var $input = $('<input type="text" class="feature-input" value="' + feature + '" placeholder="Enter feature">');
            var $button = $('<button type="button" class="button remove-feature">Remove</button>');
            
            $item.append($input).append($button);
            $editor.find('.add-feature').before($item);
        },

        /**
         * Update features field
         * 
         * @param {jQuery} $field 
         * @param {jQuery} $editor 
         */
        updateFeaturesField: function($field, $editor) {
            var features = [];
            
            $editor.find('.feature-input').each(function() {
                var feature = $(this).val().trim();
                
                if (feature !== '') {
                    features.push(feature);
                }
            });
            
            $field.val(features.join('\n'));
        },

        /**
         * Update package price
         */
        updatePackagePrice: function() {
            var $selectedServices = $('input[name="aqualuxe_service_package_services[]"]:checked');
            var serviceIds = [];
            
            $selectedServices.each(function() {
                serviceIds.push($(this).val());
            });
            
            if (serviceIds.length === 0) {
                return;
            }

            // Calculate package price
            $.ajax({
                url: aqualuxeServicesAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_calculate_package_price',
                    nonce: aqualuxeServicesAdmin.nonce,
                    service_ids: serviceIds
                },
                success: function(response) {
                    if (response.success) {
                        // Update price fields
                        $('#aqualuxe-service-package-price').val(response.data.regular_price);
                        $('#aqualuxe-service-package-sale-price').val(response.data.sale_price);
                        
                        // Show savings
                        if (response.data.savings > 0) {
                            var savingsHtml = '<div class="aqualuxe-service-package-savings">Package saves customers $' + response.data.savings.toFixed(2) + ' (' + response.data.savings_percent + '%)</div>';
                            
                            if ($('.aqualuxe-service-package-savings').length) {
                                $('.aqualuxe-service-package-savings').replaceWith(savingsHtml);
                            } else {
                                $('#aqualuxe-service-package-sale-price').closest('.aqualuxe-meta-box-row').after(savingsHtml);
                            }
                        } else {
                            $('.aqualuxe-service-package-savings').remove();
                        }
                    }
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeServicesAdmin.init();
    });

})(jQuery);