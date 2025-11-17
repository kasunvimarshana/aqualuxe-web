/**
 * AquaLuxe Shipping Zones Admin
 *
 * This file contains the shipping zones admin functionality for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Shipping Zones Object
     */
    var AquaLuxeShippingZones = {
        /**
         * Initialize
         */
        init: function() {
            this.initTabs();
            this.initSelect2();
            this.bindEvents();
        },

        /**
         * Initialize tabs
         */
        initTabs: function() {
            // Handle tab click
            $('.aqualuxe-shipping-zones-tab-nav a').on('click', function(e) {
                e.preventDefault();
                
                var $this = $(this);
                var target = $this.attr('href');
                
                // Update active tab
                $('.aqualuxe-shipping-zones-tab-nav a').removeClass('active');
                $this.addClass('active');
                
                // Show target tab
                $('.aqualuxe-shipping-zone-tab').removeClass('active');
                $(target).addClass('active');
            });
        },

        /**
         * Initialize Select2
         */
        initSelect2: function() {
            $('.aqualuxe-shipping-zone-countries').select2({
                placeholder: 'Select countries',
                allowClear: true,
                width: '100%'
            });
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Handle form submit
            $('.aqualuxe-shipping-zone-form').on('submit', this.saveZone);
        },

        /**
         * Save zone
         *
         * @param {Event} e Event object.
         */
        saveZone: function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var zone = $form.data('zone');
            var $spinner = $form.find('.spinner');
            var $notice = $('.aqualuxe-shipping-zones-notice');
            
            // Show spinner
            $spinner.addClass('is-active');
            
            // Show saving message
            $notice.html('<div class="notice notice-info"><p>' + aqualuxeShippingZones.i18n.saving + '</p></div>');
            
            // Get form data
            var formData = $form.serializeArray();
            
            // Add zone and nonce
            formData.push({
                name: 'zone',
                value: zone
            });
            
            formData.push({
                name: 'action',
                value: 'aqualuxe_save_shipping_zone'
            });
            
            formData.push({
                name: 'nonce',
                value: aqualuxeShippingZones.nonce
            });
            
            // Ajax request
            $.ajax({
                url: aqualuxeShippingZones.ajaxUrl,
                data: formData,
                type: 'POST',
                success: function(response) {
                    // Hide spinner
                    $spinner.removeClass('is-active');
                    
                    if (response.success) {
                        // Show success message
                        $notice.html('<div class="notice notice-success"><p>' + aqualuxeShippingZones.i18n.saveSuccess + '</p></div>');
                    } else {
                        // Show error message
                        $notice.html('<div class="notice notice-error"><p>' + (response.data.message || aqualuxeShippingZones.i18n.saveError) + '</p></div>');
                    }
                },
                error: function() {
                    // Hide spinner
                    $spinner.removeClass('is-active');
                    
                    // Show error message
                    $notice.html('<div class="notice notice-error"><p>' + aqualuxeShippingZones.i18n.saveError + '</p></div>');
                }
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeShippingZones.init();
    });

})(jQuery);