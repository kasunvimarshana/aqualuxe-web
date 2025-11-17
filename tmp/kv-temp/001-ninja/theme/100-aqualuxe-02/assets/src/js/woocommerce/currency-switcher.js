// Currency switcher
(function($) {
    'use strict';
    
    const CurrencySwitcher = {
        init: function() {
            this.createSwitcher();
            this.bindEvents();
        },
        
        createSwitcher: function() {
            // Create currency switcher dropdown
            const currencies = [
                { code: 'USD', symbol: '$', name: 'US Dollar' },
                { code: 'EUR', symbol: '€', name: 'Euro' },
                { code: 'GBP', symbol: '£', name: 'British Pound' },
                { code: 'JPY', symbol: '¥', name: 'Japanese Yen' }
            ];
            
            const currentCurrency = 'USD'; // This would come from settings
            
            const $switcher = $(`
                <div class="currency-switcher">
                    <button class="currency-toggle" aria-expanded="false">
                        <span class="current-currency">${currentCurrency}</span>
                        <span class="arrow">▼</span>
                    </button>
                    <ul class="currency-list" aria-hidden="true">
                        ${currencies.map(currency => 
                            `<li><a href="#" data-currency="${currency.code}">${currency.symbol} ${currency.code}</a></li>`
                        ).join('')}
                    </ul>
                </div>
            `);
            
            // Add to header if WooCommerce is active
            if ($('.header-woocommerce').length) {
                $('.header-woocommerce').append($switcher);
            }
        },
        
        bindEvents: function() {
            $(document).on('click', '.currency-toggle', function(e) {
                e.preventDefault();
                const $toggle = $(this);
                const $list = $toggle.next('.currency-list');
                const isOpen = $toggle.attr('aria-expanded') === 'true';
                
                $toggle.attr('aria-expanded', !isOpen);
                $list.attr('aria-hidden', isOpen);
                $list.toggle();
            });
            
            $(document).on('click', '.currency-list a', function(e) {
                e.preventDefault();
                const currency = $(this).data('currency');
                
                // Update current currency display
                $('.current-currency').text(currency);
                
                // Close dropdown
                $('.currency-toggle').attr('aria-expanded', 'false');
                $('.currency-list').attr('aria-hidden', 'true').hide();
                
                // Here you would implement actual currency switching
                console.log('Currency switched to:', currency);
            });
            
            // Close on outside click
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.currency-switcher').length) {
                    $('.currency-toggle').attr('aria-expanded', 'false');
                    $('.currency-list').attr('aria-hidden', 'true').hide();
                }
            });
        }
    };
    
    $(document).ready(function() {
        CurrencySwitcher.init();
    });
    
})(jQuery);