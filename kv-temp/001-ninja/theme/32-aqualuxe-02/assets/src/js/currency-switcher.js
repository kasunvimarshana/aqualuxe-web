/**
 * AquaLuxe Theme - Currency Switcher
 *
 * Handles the currency switching functionality.
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Check if currency switcher exists
        if (!$('.currency-switcher').length) {
            return;
        }

        // Handle currency change
        $('.currency-switcher select').on('change', function() {
            const currency = $(this).val();
            
            // Show loading overlay
            $('body').append('<div class="currency-loading-overlay"><div class="loading-spinner"></div></div>');
            
            // AJAX call to switch currency
            $.ajax({
                url: aqualuxeSettings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_switch_currency',
                    currency: currency,
                    nonce: aqualuxeSettings.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Reload page to update prices
                        window.location.reload();
                    } else {
                        // Remove loading overlay
                        $('.currency-loading-overlay').remove();
                        
                        // Show error message
                        alert('Failed to switch currency. Please try again.');
                    }
                },
                error: function() {
                    // Remove loading overlay
                    $('.currency-loading-overlay').remove();
                    
                    // Show error message
                    alert('Failed to switch currency. Please try again.');
                }
            });
        });

        // Initialize select2 for currency selector if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('.currency-switcher select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'currency-dropdown',
                templateResult: formatCurrency,
                templateSelection: formatCurrency
            });
        }

        // Format currency option with flag
        function formatCurrency(currency) {
            if (!currency.id) {
                return currency.text;
            }
            
            const $option = $(currency.element);
            const flagCode = $option.data('flag') || currency.id.toLowerCase();
            
            return $(`
                <span class="currency-option">
                    <span class="currency-flag flag-icon flag-icon-${flagCode}"></span>
                    <span class="currency-code">${currency.id}</span>
                    <span class="currency-symbol">${$option.data('symbol') || ''}</span>
                </span>
            `);
        }

        // Handle currency switcher in mobile menu
        $('.mobile-currency-switcher').on('click', function(e) {
            e.preventDefault();
            
            $('.currency-switcher').toggleClass('active');
        });

        // Close currency switcher when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.currency-switcher, .mobile-currency-switcher').length) {
                $('.currency-switcher').removeClass('active');
            }
        });
    });

})(jQuery);