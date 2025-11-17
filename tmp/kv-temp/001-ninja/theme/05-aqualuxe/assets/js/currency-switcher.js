/**
 * AquaLuxe Currency Switcher
 *
 * This file contains the currency switcher functionality for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Currency Switcher Object
     */
    var AquaLuxeCurrencySwitcher = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Handle currency switcher change
            $(document).on('change', '#aqualuxe-currency', this.switchCurrency);
        },

        /**
         * Switch currency
         */
        switchCurrency: function() {
            var $this = $(this);
            var currency = $this.val();
            var nonce = $this.data('nonce');
            
            // Show loading message
            $this.prop('disabled', true);
            
            if ($this.next('.aqualuxe-currency-notice').length) {
                $this.next('.aqualuxe-currency-notice').text(aqualuxeCurrency.i18n.switching);
            } else {
                $this.after('<div class="aqualuxe-currency-notice">' + aqualuxeCurrency.i18n.switching + '</div>');
            }
            
            // Ajax request
            $.ajax({
                url: aqualuxeCurrency.ajaxUrl,
                data: {
                    action: 'aqualuxe_switch_currency',
                    currency: currency,
                    nonce: nonce
                },
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        // Reload page
                        window.location.reload();
                    } else {
                        // Show error message
                        $this.prop('disabled', false);
                        $this.next('.aqualuxe-currency-notice').text(aqualuxeCurrency.i18n.switchError);
                    }
                },
                error: function() {
                    // Show error message
                    $this.prop('disabled', false);
                    $this.next('.aqualuxe-currency-notice').text(aqualuxeCurrency.i18n.switchError);
                }
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeCurrencySwitcher.init();
    });

})(jQuery);