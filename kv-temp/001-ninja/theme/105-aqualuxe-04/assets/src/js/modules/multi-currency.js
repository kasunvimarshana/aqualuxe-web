/**
 * Multi-Currency Module JavaScript
 *
 * Handles currency switching and price conversion functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Multi-Currency Module
     */
    const AquaLuxeMultiCurrency = {
        
        /**
         * Current settings
         */
        currentCurrency: 'USD',
        exchangeRates: {},
        currencies: {},
        
        /**
         * Initialize the module
         */
        init() {
            // Set initial values from localized data
            this.currentCurrency = aqualuxe_currency.current_currency || 'USD';
            this.exchangeRates = aqualuxe_currency.exchange_rates || {};
            this.currencies = aqualuxe_currency.supported_currencies || {};
            
            this.bindEvents();
            this.initializeComponents();
        },

        /**
         * Bind event handlers
         */
        bindEvents() {
            // Currency switcher dropdown
            $(document).on('change', '#currency-switcher', this.handleCurrencySwitch.bind(this));
            
            // Currency buttons
            $(document).on('click', '.currency-btn', this.handleCurrencyButtonClick.bind(this));
            
            // Price converter input
            $(document).on('input', '.price-converter-input', this.handlePriceConversion.bind(this));
            
            // Refresh exchange rates
            $(document).on('click', '.refresh-rates-btn', this.refreshExchangeRates.bind(this));
            
            // Auto-detect currency
            $(document).on('click', '.auto-detect-currency', this.autoDetectCurrency.bind(this));
            
            // Price hover conversion
            $(document).on('mouseenter', '.price, .woocommerce-Price-amount', this.showPriceTooltip.bind(this));
            $(document).on('mouseleave', '.price, .woocommerce-Price-amount', this.hidePriceTooltip.bind(this));
        },

        /**
         * Initialize components
         */
        initializeComponents() {
            this.initializeCurrencySwitcher();
            this.updateAllPrices();
            this.initializePriceTooltips();
            this.loadStoredPreferences();
            this.setupPeriodicRateUpdates();
        },

        /**
         * Initialize currency switcher
         */
        initializeCurrencySwitcher() {
            // Update active states
            $('.currency-btn').removeClass('active');
            $(`.currency-btn[data-currency="${this.currentCurrency}"]`).addClass('active');
            
            // Update dropdown selection
            $('#currency-switcher').val(this.currentCurrency);
            
            // Show current currency in display elements
            $('.current-currency').text(this.currentCurrency);
            $('.current-currency-symbol').text(this.currencies[this.currentCurrency]?.symbol || '$');
        },

        /**
         * Handle currency switch from dropdown
         */
        handleCurrencySwitch(e) {
            const newCurrency = $(e.target).val();
            this.switchCurrency(newCurrency);
        },

        /**
         * Handle currency button click
         */
        handleCurrencyButtonClick(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            const newCurrency = $btn.data('currency');
            
            if (newCurrency && newCurrency !== this.currentCurrency) {
                this.switchCurrency(newCurrency);
            }
        },

        /**
         * Switch to new currency
         */
        switchCurrency(newCurrency) {
            if (!this.currencies[newCurrency]) {
                this.showMessage('Currency not supported.', 'error');
                return;
            }

            // Show loading state
            this.showLoadingState(true);

            // Send AJAX request to update server-side currency
            $.ajax({
                url: aqualuxe_currency.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_switch_currency',
                    currency: newCurrency,
                    nonce: aqualuxe_currency.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.currentCurrency = newCurrency;
                        this.updateLocalStorage();
                        this.updateUI();
                        this.updateAllPrices();
                        this.showMessage(aqualuxe_currency.messages.success, 'success');
                    } else {
                        this.showMessage(response.data.message || aqualuxe_currency.messages.error, 'error');
                    }
                },
                error: () => {
                    this.showMessage(aqualuxe_currency.messages.error, 'error');
                },
                complete: () => {
                    this.showLoadingState(false);
                }
            });
        },

        /**
         * Update local storage with preferences
         */
        updateLocalStorage() {
            localStorage.setItem('aqualuxe_preferred_currency', this.currentCurrency);
        },

        /**
         * Load stored preferences
         */
        loadStoredPreferences() {
            const storedCurrency = localStorage.getItem('aqualuxe_preferred_currency');
            
            if (storedCurrency && storedCurrency !== this.currentCurrency && this.currencies[storedCurrency]) {
                // Only switch if different from server-side currency
                this.switchCurrency(storedCurrency);
            }
        },

        /**
         * Update UI elements
         */
        updateUI() {
            // Update currency switcher
            this.initializeCurrencySwitcher();
            
            // Update currency displays
            $('.current-currency-code').text(this.currentCurrency);
            $('.current-currency-name').text(this.currencies[this.currentCurrency]?.name || this.currentCurrency);
            $('.current-currency-symbol').text(this.currencies[this.currentCurrency]?.symbol || '$');
            
            // Trigger currency change event
            $(document).trigger('aqualuxe_currency_changed', [this.currentCurrency]);
        },

        /**
         * Update all prices on page
         */
        updateAllPrices() {
            $('.price-convertible').each((index, element) => {
                this.convertPriceElement($(element));
            });

            // Update WooCommerce prices
            $('.woocommerce-Price-amount, .amount').each((index, element) => {
                this.convertWooCommercePriceElement($(element));
            });
        },

        /**
         * Convert individual price element
         */
        convertPriceElement($element) {
            const originalPrice = parseFloat($element.data('original-price'));
            const originalCurrency = $element.data('original-currency') || 'USD';
            
            if (isNaN(originalPrice)) {
                return;
            }

            const convertedPrice = this.convertPrice(originalPrice, originalCurrency, this.currentCurrency);
            const formattedPrice = this.formatPrice(convertedPrice, this.currentCurrency);
            
            $element.html(formattedPrice);
        },

        /**
         * Convert WooCommerce price element
         */
        convertWooCommercePriceElement($element) {
            // Store original price if not already stored
            if (!$element.data('original-amount')) {
                const priceText = $element.text().replace(/[^\d.,]/g, '');
                const originalAmount = parseFloat(priceText.replace(',', ''));
                
                if (!isNaN(originalAmount)) {
                    $element.data('original-amount', originalAmount);
                    $element.data('original-currency', 'USD'); // Default base currency
                }
            }

            const originalAmount = parseFloat($element.data('original-amount'));
            const originalCurrency = $element.data('original-currency') || 'USD';
            
            if (isNaN(originalAmount)) {
                return;
            }

            const convertedAmount = this.convertPrice(originalAmount, originalCurrency, this.currentCurrency);
            const formattedPrice = this.formatPrice(convertedAmount, this.currentCurrency);
            
            $element.html(formattedPrice);
        },

        /**
         * Convert price between currencies
         */
        convertPrice(amount, fromCurrency, toCurrency) {
            if (fromCurrency === toCurrency) {
                return amount;
            }

            // Convert to base currency (USD) first if needed
            let baseAmount = amount;
            if (fromCurrency !== 'USD') {
                const fromRate = this.exchangeRates[fromCurrency];
                if (fromRate) {
                    baseAmount = amount / fromRate;
                } else {
                    return amount; // No conversion available
                }
            }

            // Convert from base to target currency
            if (toCurrency !== 'USD') {
                const toRate = this.exchangeRates[toCurrency];
                if (toRate) {
                    return baseAmount * toRate;
                } else {
                    return amount; // No conversion available
                }
            }

            return baseAmount;
        },

        /**
         * Format price with currency
         */
        formatPrice(amount, currency) {
            const currencyConfig = this.currencies[currency];
            
            if (!currencyConfig) {
                return amount.toString();
            }

            const formattedAmount = this.numberFormat(
                amount,
                currencyConfig.decimals || 2,
                currencyConfig.decimal_separator || '.',
                currencyConfig.thousand_separator || ','
            );

            if (currencyConfig.position === 'right') {
                return formattedAmount + currencyConfig.symbol;
            } else {
                return currencyConfig.symbol + formattedAmount;
            }
        },

        /**
         * Number formatting function
         */
        numberFormat(number, decimals, decimalSep, thousandsSep) {
            const n = !isFinite(+number) ? 0 : +number;
            const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
            const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
            const dec = (typeof decimalSep === 'undefined') ? '.' : decimalSep;
            
            const s = (prec ? this.toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            
            return s.join(dec);
        },

        /**
         * Fix for toFixed
         */
        toFixedFix(n, prec) {
            const k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k);
        },

        /**
         * Handle price conversion input
         */
        handlePriceConversion(e) {
            const $input = $(e.target);
            const amount = parseFloat($input.val());
            const fromCurrency = $input.data('from-currency') || 'USD';
            
            if (isNaN(amount)) {
                return;
            }

            // Update all conversion outputs
            $('.conversion-output').each((index, element) => {
                const $output = $(element);
                const toCurrency = $output.data('to-currency');
                
                if (toCurrency) {
                    const convertedAmount = this.convertPrice(amount, fromCurrency, toCurrency);
                    const formattedPrice = this.formatPrice(convertedAmount, toCurrency);
                    $output.text(formattedPrice);
                }
            });
        },

        /**
         * Refresh exchange rates
         */
        refreshExchangeRates(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            this.setButtonLoading($btn, true);

            $.ajax({
                url: aqualuxe_currency.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_exchange_rates',
                    force_refresh: true,
                    nonce: aqualuxe_currency.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.exchangeRates = response.data.rates;
                        this.updateAllPrices();
                        this.showMessage('Exchange rates updated successfully!', 'success');
                        
                        // Update last updated display
                        const lastUpdated = new Date(response.data.last_updated * 1000);
                        $('.last-updated').text('Last updated: ' + lastUpdated.toLocaleString());
                    } else {
                        this.showMessage('Failed to update exchange rates.', 'error');
                    }
                },
                error: () => {
                    this.showMessage('Failed to update exchange rates.', 'error');
                },
                complete: () => {
                    this.setButtonLoading($btn, false);
                }
            });
        },

        /**
         * Auto-detect currency based on location
         */
        autoDetectCurrency(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            this.setButtonLoading($btn, true);

            // Try to get user's location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.detectCurrencyByLocation(position.coords, $btn);
                    },
                    () => {
                        this.detectCurrencyByIP($btn);
                    }
                );
            } else {
                this.detectCurrencyByIP($btn);
            }
        },

        /**
         * Detect currency by IP
         */
        detectCurrencyByIP($btn) {
            $.ajax({
                url: aqualuxe_currency.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_detect_currency',
                    method: 'ip',
                    nonce: aqualuxe_currency.nonce
                },
                success: (response) => {
                    if (response.success && response.data.currency) {
                        this.switchCurrency(response.data.currency);
                        this.showMessage(`Currency auto-detected: ${response.data.currency}`, 'success');
                    } else {
                        this.showMessage('Could not auto-detect currency.', 'warning');
                    }
                },
                error: () => {
                    this.showMessage('Failed to auto-detect currency.', 'error');
                },
                complete: () => {
                    this.setButtonLoading($btn, false);
                }
            });
        },

        /**
         * Initialize price tooltips
         */
        initializePriceTooltips() {
            // Add data attributes to price elements for tooltip conversion
            $('.price, .woocommerce-Price-amount').each(function() {
                const $element = $(this);
                
                if (!$element.data('tooltip-enabled')) {
                    $element.data('tooltip-enabled', true);
                }
            });
        },

        /**
         * Show price tooltip with conversion
         */
        showPriceTooltip(e) {
            const $element = $(e.target);
            
            if (!$element.data('tooltip-enabled') || this.currentCurrency === 'USD') {
                return;
            }

            const originalPrice = parseFloat($element.data('original-amount')) || 
                                parseFloat($element.text().replace(/[^\d.,]/g, '').replace(',', ''));
            
            if (isNaN(originalPrice)) {
                return;
            }

            // Convert to different major currencies for tooltip
            const conversions = ['USD', 'EUR', 'GBP', 'JPY'].filter(curr => curr !== this.currentCurrency);
            let tooltipContent = '<div class="currency-tooltip"><h5>Price in other currencies:</h5>';
            
            conversions.forEach(currency => {
                if (this.currencies[currency]) {
                    const convertedPrice = this.convertPrice(originalPrice, this.currentCurrency, currency);
                    const formattedPrice = this.formatPrice(convertedPrice, currency);
                    tooltipContent += `<div class="tooltip-currency">${currency}: ${formattedPrice}</div>`;
                }
            });
            
            tooltipContent += '</div>';

            // Create and position tooltip
            const $tooltip = $(tooltipContent);
            $('body').append($tooltip);
            
            const elementOffset = $element.offset();
            $tooltip.css({
                position: 'absolute',
                top: elementOffset.top - $tooltip.outerHeight() - 10,
                left: elementOffset.left,
                zIndex: 9999
            });

            $tooltip.fadeIn(200);
            $element.data('active-tooltip', $tooltip);
        },

        /**
         * Hide price tooltip
         */
        hidePriceTooltip(e) {
            const $element = $(e.target);
            const $tooltip = $element.data('active-tooltip');
            
            if ($tooltip) {
                $tooltip.fadeOut(200, () => {
                    $tooltip.remove();
                });
                $element.removeData('active-tooltip');
            }
        },

        /**
         * Setup periodic rate updates
         */
        setupPeriodicRateUpdates() {
            // Update exchange rates every 30 minutes
            setInterval(() => {
                this.refreshExchangeRatesInBackground();
            }, 30 * 60 * 1000);
        },

        /**
         * Refresh exchange rates in background
         */
        refreshExchangeRatesInBackground() {
            $.ajax({
                url: aqualuxe_currency.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_exchange_rates',
                    nonce: aqualuxe_currency.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.exchangeRates = response.data.rates;
                        this.updateAllPrices();
                    }
                }
            });
        },

        /**
         * Show loading state
         */
        showLoadingState(isLoading) {
            if (isLoading) {
                $('.currency-switcher').addClass('loading');
                $('.currency-btn').prop('disabled', true);
                $('#currency-switcher').prop('disabled', true);
            } else {
                $('.currency-switcher').removeClass('loading');
                $('.currency-btn').prop('disabled', false);
                $('#currency-switcher').prop('disabled', false);
            }
        },

        /**
         * Set button loading state
         */
        setButtonLoading($btn, isLoading) {
            if (isLoading) {
                $btn.prop('disabled', true)
                    .data('original-text', $btn.text())
                    .html('<span class="spinner"></span> ' + aqualuxe_currency.messages.loading_rates);
            } else {
                $btn.prop('disabled', false)
                    .text($btn.data('original-text') || $btn.text());
            }
        },

        /**
         * Show message
         */
        showMessage(message, type = 'info') {
            const $message = $(`
                <div class="aqualuxe-message aqualuxe-message-${type}">
                    <span class="message-text">${message}</span>
                    <button class="message-close">&times;</button>
                </div>
            `);
            
            // Remove existing messages
            $('.aqualuxe-message').remove();
            
            // Add new message
            $('body').prepend($message);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                $message.fadeOut(() => $message.remove());
            }, 5000);
            
            // Manual close
            $message.find('.message-close').on('click', () => {
                $message.fadeOut(() => $message.remove());
            });
        }
    };

    /**
     * Initialize when document is ready
     */
    $(document).ready(() => {
        AquaLuxeMultiCurrency.init();
    });

    // Make it globally available
    window.AquaLuxeMultiCurrency = AquaLuxeMultiCurrency;

})(jQuery);