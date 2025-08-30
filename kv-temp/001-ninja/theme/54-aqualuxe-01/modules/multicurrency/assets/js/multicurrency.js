/**
 * AquaLuxe Multicurrency Module Scripts
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Multicurrency Module
     */
    var AquaLuxeMulticurrencyModule = {
        /**
         * Initialize module
         */
        init: function() {
            this.initCurrencySwitcher();
            this.initMenuSwitcher();
        },

        /**
         * Initialize currency switcher
         */
        initCurrencySwitcher: function() {
            // Dropdown currency switcher
            $('.aqualuxe-currency-select').on('change', function() {
                var currency = $(this).val();
                
                if (currency) {
                    AquaLuxeMulticurrencyModule.switchCurrency(currency);
                }
            });
            
            // Flags currency switcher
            $('.aqualuxe-currency-flag-item a').on('click', function(e) {
                e.preventDefault();
                
                var currency = $(this).parent().data('currency');
                
                if (currency) {
                    AquaLuxeMulticurrencyModule.switchCurrency(currency);
                }
            });
            
            // Text currency switcher
            $('.aqualuxe-currency-text-item a').on('click', function(e) {
                e.preventDefault();
                
                var currency = $(this).parent().data('currency');
                
                if (currency) {
                    AquaLuxeMulticurrencyModule.switchCurrency(currency);
                }
            });
        },

        /**
         * Initialize menu switcher
         */
        initMenuSwitcher: function() {
            // Menu currency switcher
            $('.aqualuxe-currency-menu-item .sub-menu a').on('click', function(e) {
                e.preventDefault();
                
                var currency = $(this).data('currency');
                
                if (currency) {
                    AquaLuxeMulticurrencyModule.switchCurrency(currency);
                }
            });
        },

        /**
         * Switch currency
         *
         * @param {string} currency Currency code
         */
        switchCurrency: function(currency) {
            // Show loading
            AquaLuxeMulticurrencyModule.showLoading();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeMulticurrency.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_switch_currency',
                    nonce: aqualuxeMulticurrency.nonce,
                    currency: currency
                },
                success: function(response) {
                    if (response.success) {
                        // Set cookie
                        AquaLuxeMulticurrencyModule.setCookie('aqualuxe_currency', currency, 30);
                        
                        // Reload page
                        window.location.reload();
                    } else {
                        console.error('Error switching currency:', response.data);
                        
                        // Hide loading
                        AquaLuxeMulticurrencyModule.hideLoading();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    
                    // Hide loading
                    AquaLuxeMulticurrencyModule.hideLoading();
                }
            });
        },

        /**
         * Show loading
         */
        showLoading: function() {
            // Add loading overlay
            $('body').append('<div class="aqualuxe-loading-overlay"><div class="aqualuxe-loading-spinner"></div></div>');
            
            // Add loading styles
            var style = '<style id="aqualuxe-loading-styles">' +
                '.aqualuxe-loading-overlay {' +
                '    position: fixed;' +
                '    top: 0;' +
                '    left: 0;' +
                '    width: 100%;' +
                '    height: 100%;' +
                '    background-color: rgba(0, 0, 0, 0.5);' +
                '    z-index: 9999;' +
                '    display: flex;' +
                '    align-items: center;' +
                '    justify-content: center;' +
                '}' +
                '.aqualuxe-loading-spinner {' +
                '    width: 40px;' +
                '    height: 40px;' +
                '    border: 4px solid rgba(255, 255, 255, 0.3);' +
                '    border-radius: 50%;' +
                '    border-top-color: #ffffff;' +
                '    animation: aqualuxe-spin 1s linear infinite;' +
                '}' +
                '@keyframes aqualuxe-spin {' +
                '    to { transform: rotate(360deg); }' +
                '}' +
                '</style>';
            
            $('head').append(style);
        },

        /**
         * Hide loading
         */
        hideLoading: function() {
            $('.aqualuxe-loading-overlay').remove();
            $('#aqualuxe-loading-styles').remove();
        },

        /**
         * Set cookie
         *
         * @param {string} name Cookie name
         * @param {string} value Cookie value
         * @param {number} days Cookie expiration in days
         */
        setCookie: function(name, value, days) {
            var expires = '';
            
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + value + expires + '; path=/';
        },

        /**
         * Get cookie
         *
         * @param {string} name Cookie name
         * @return {string|null} Cookie value
         */
        getCookie: function(name) {
            var nameEQ = name + '=';
            var ca = document.cookie.split(';');
            
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                
                while (c.charAt(0) === ' ') {
                    c = c.substring(1, c.length);
                }
                
                if (c.indexOf(nameEQ) === 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            
            return null;
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeMulticurrencyModule.init();
    });

})(jQuery);