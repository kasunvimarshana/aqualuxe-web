/**
 * AquaLuxe Currency Switcher
 * 
 * Handles currency switching functionality for the AquaLuxe theme
 */
(function($) {
    'use strict';

    // Main currency switcher object
    const AquaLuxeCurrencySwitcher = {
        // Initialize the currency switcher
        init: function() {
            this.currencySelect = $('#aqualuxe-currency-select');
            this.currentCurrency = this.getCookie('aqualuxe_currency') || 'USD';
            
            // Bind events
            this.bindEvents();
            
            // Set initial currency
            this.setCurrency(this.currentCurrency);
        },

        // Bind all events
        bindEvents: function() {
            const self = this;
            
            // Currency select change
            this.currencySelect.on('change', function() {
                const currency = $(this).val();
                
                self.setCurrency(currency);
            });
        },

        // Set currency
        setCurrency: function(currency) {
            const self = this;
            
            // Update current currency
            this.currentCurrency = currency;
            
            // Update select
            this.currencySelect.val(currency);
            
            // Update prices
            this.updatePrices(currency);
            
            // Save currency to cookie
            this.setCookie('aqualuxe_currency', currency, 30);
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCurrency.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_set_currency',
                    nonce: aqualuxeCurrency.nonce,
                    currency: currency
                },
                success: function(response) {
                    // Success
                    if (response.success) {
                        // Trigger event
                        $(document.body).trigger('aqualuxe_currency_changed', [currency]);
                    }
                }
            });
        },

        // Update prices
        updatePrices: function(currency) {
            const self = this;
            const currencyData = aqualuxeCurrency.currencies[currency];
            
            if (!currencyData) {
                return;
            }
            
            // Update all prices
            $('.woocommerce-Price-amount').each(function() {
                const $price = $(this);
                const originalPrice = $price.data('original-price');
                
                // If original price is not set, save it
                if (!originalPrice) {
                    // Get price value
                    const priceText = $price.text();
                    const priceValue = self.extractPrice(priceText);
                    
                    if (priceValue) {
                        // Save original price
                        $price.data('original-price', priceValue);
                        $price.data('original-currency', 'USD');
                    }
                }
                
                // Get original price
                const price = $price.data('original-price');
                
                if (price) {
                    // Convert price
                    const convertedPrice = price * currencyData.rate;
                    
                    // Format price
                    const formattedPrice = self.formatPrice(convertedPrice, currencyData.symbol);
                    
                    // Update price
                    $price.html(formattedPrice);
                }
            });
        },

        // Extract price from text
        extractPrice: function(text) {
            // Remove currency symbol and formatting
            const price = text.replace(/[^\d.,]/g, '').replace(',', '.');
            
            return parseFloat(price);
        },

        // Format price
        formatPrice: function(price, symbol) {
            // Format price with 2 decimal places
            price = price.toFixed(2);
            
            // Format with currency symbol
            return symbol + price;
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
        AquaLuxeCurrencySwitcher.init();
    });

})(jQuery);