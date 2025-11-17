/**
 * AquaLuxe Country Selector
 * 
 * Handles country selection functionality for the AquaLuxe theme
 */
(function($) {
    'use strict';

    // Main country selector object
    const AquaLuxeCountrySelector = {
        // Initialize the country selector
        init: function() {
            this.countrySelect = $('#aqualuxe-country-select');
            this.currentCountry = this.getCookie('aqualuxe_country') || '';
            
            // Bind events
            this.bindEvents();
            
            // Set initial country
            if (this.currentCountry) {
                this.setCountry(this.currentCountry);
            }
        },

        // Bind all events
        bindEvents: function() {
            const self = this;
            
            // Country select change
            this.countrySelect.on('change', function() {
                const country = $(this).val();
                
                self.setCountry(country);
            });
        },

        // Set country
        setCountry: function(country) {
            const self = this;
            
            // Update current country
            this.currentCountry = country;
            
            // Update select
            this.countrySelect.val(country);
            
            // Save country to cookie
            this.setCookie('aqualuxe_country', country, 30);
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCountry.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_set_country',
                    nonce: aqualuxeCountry.nonce,
                    country: country
                },
                success: function(response) {
                    // Success
                    if (response.success) {
                        // Trigger event
                        $(document.body).trigger('aqualuxe_country_changed', [country]);
                        
                        // Refresh page if on cart or checkout
                        if ($('.woocommerce-cart-form').length || $('.woocommerce-checkout').length) {
                            location.reload();
                        }
                    }
                }
            });
        },

        // Get cookie
        getCookie: function(name) {
            const value = '; ' + document.cookie;
            const parts = value.split('; ' + name + '=');
            
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            
            return '';
        },

        // Set cookie
        setCookie: function(name, value, days) {
            let expires = '';
            
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + value + expires + '; path=/';
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeCountrySelector.init();
    });

})(jQuery);