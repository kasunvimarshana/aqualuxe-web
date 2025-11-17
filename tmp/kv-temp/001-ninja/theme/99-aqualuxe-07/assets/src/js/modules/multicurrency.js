/**
 * Multicurrency Module JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

class MulticurrencySupport {
    constructor() {
        this.storageKey = 'aqualuxe-currency';
        this.ratesCache = {};
        this.cacheTTL = 3600000; // 1 hour in milliseconds
        this.init();
    }

    init() {
        this.createCurrencySwitcher();
        this.bindEvents();
        this.applyStoredCurrency();
        this.initExchangeRates();
    }

    createCurrencySwitcher() {
        const existingSwitcher = document.querySelector('.currency-switcher');
        if (existingSwitcher) return;

        // Get available currencies from global object
        const currencies = window.AQUALUXE?.currencies || this.getDefaultCurrencies();
        
        if (Object.keys(currencies).length <= 1) return;

        const switcher = document.createElement('div');
        switcher.className = 'currency-switcher relative inline-block ml-4';
        
        const currentCurrency = this.getCurrentCurrency();
        const currentCurrencyData = currencies[currentCurrency] || currencies[Object.keys(currencies)[0]];

        switcher.innerHTML = `
            <button class="currency-toggle flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                <span class="currency-symbol">${currentCurrencyData.symbol}</span>
                <span class="currency-code">${currentCurrency}</span>
                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div class="currency-dropdown absolute top-full left-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg min-w-40 z-50 hidden">
                ${Object.entries(currencies).map(([code, currency]) => `
                    <a href="#" 
                       class="currency-option flex items-center justify-between px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 ${code === currentCurrency ? 'bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : ''}"
                       data-currency="${code}">
                        <span class="flex items-center space-x-2">
                            <span class="currency-symbol">${currency.symbol}</span>
                            <span>${code}</span>
                        </span>
                        ${code === currentCurrency ? '<svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' : ''}
                    </a>
                `).join('')}
            </div>
        `;

        // Add to header navigation (after language switcher if it exists)
        const nav = document.querySelector('.primary-navigation') || document.querySelector('nav') || document.body;
        const langSwitcher = document.querySelector('.language-switcher');
        
        if (langSwitcher) {
            langSwitcher.parentNode.insertBefore(switcher, langSwitcher.nextSibling);
        } else {
            nav.appendChild(switcher);
        }
    }

    bindEvents() {
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('.currency-toggle');
            const option = e.target.closest('.currency-option');
            const switcher = e.target.closest('.currency-switcher');

            if (toggle) {
                e.preventDefault();
                this.toggleDropdown();
            } else if (option) {
                e.preventDefault();
                const currency = option.dataset.currency;
                this.switchCurrency(currency);
            } else if (!switcher) {
                this.closeDropdown();
            }
        });

        // Listen for currency change events
        document.addEventListener('currencyChanged', (e) => {
            this.handleCurrencyChange(e.detail.currency);
        });
    }

    toggleDropdown() {
        const dropdown = document.querySelector('.currency-dropdown');
        const toggle = document.querySelector('.currency-toggle svg');
        
        if (dropdown && toggle) {
            const isOpen = !dropdown.classList.contains('hidden');
            
            if (isOpen) {
                this.closeDropdown();
            } else {
                this.openDropdown();
            }
        }
    }

    openDropdown() {
        const dropdown = document.querySelector('.currency-dropdown');
        const toggle = document.querySelector('.currency-toggle svg');
        
        if (dropdown && toggle) {
            dropdown.classList.remove('hidden');
            toggle.style.transform = 'rotate(180deg)';
        }
    }

    closeDropdown() {
        const dropdown = document.querySelector('.currency-dropdown');
        const toggle = document.querySelector('.currency-toggle svg');
        
        if (dropdown && toggle) {
            dropdown.classList.add('hidden');
            toggle.style.transform = 'rotate(0deg)';
        }
    }

    switchCurrency(currency) {
        // Show loading state
        this.showLoadingState();
        
        // Store the selected currency
        localStorage.setItem(this.storageKey, currency);
        
        // Update the UI immediately
        this.updateCurrencyDisplay(currency);
        
        // Convert all prices on the page
        this.convertPagePrices(currency);
        
        // Send AJAX request to update session currency
        this.sendCurrencyChangeRequest(currency);
        
        // Trigger custom event
        document.dispatchEvent(new CustomEvent('currencyChanged', {
            detail: { currency }
        }));
        
        this.closeDropdown();
    }

    sendCurrencyChangeRequest(currency) {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_switch_currency');
        formData.append('currency', currency);
        formData.append('nonce', window.AQUALUXE?.nonce || '');

        fetch(window.AQUALUXE?.ajaxUrl || '/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update exchange rates if provided
                if (data.data.rates) {
                    this.updateExchangeRates(data.data.rates);
                }
            }
        })
        .catch(error => {
            console.error('Currency switch error:', error);
        })
        .finally(() => {
            this.hideLoadingState();
        });
    }

    updateCurrencyDisplay(currency) {
        const currencies = window.AQUALUXE?.currencies || this.getDefaultCurrencies();
        const currencyData = currencies[currency];
        
        if (!currencyData) return;

        const symbol = document.querySelector('.currency-symbol');
        const code = document.querySelector('.currency-code');
        
        if (symbol) symbol.textContent = currencyData.symbol;
        if (code) code.textContent = currency;

        // Update active state in dropdown
        const options = document.querySelectorAll('.currency-option');
        options.forEach(option => {
            const isActive = option.dataset.currency === currency;
            option.classList.toggle('bg-primary-50', isActive);
            option.classList.toggle('dark:bg-primary-900', isActive);
            option.classList.toggle('text-primary-700', isActive);
            option.classList.toggle('dark:text-primary-300', isActive);
            
            const checkmark = option.querySelector('svg');
            if (checkmark) {
                checkmark.style.display = isActive ? 'block' : 'none';
            }
        });
    }

    convertPagePrices(toCurrency) {
        const fromCurrency = this.getBaseCurrency();
        
        if (fromCurrency === toCurrency) return;

        const priceElements = document.querySelectorAll('[data-price]');
        priceElements.forEach(element => {
            const originalPrice = parseFloat(element.dataset.originalPrice || element.dataset.price);
            const originalCurrency = element.dataset.originalCurrency || fromCurrency;
            
            if (!isNaN(originalPrice)) {
                // Store original price if not already stored
                if (!element.dataset.originalPrice) {
                    element.dataset.originalPrice = originalPrice.toString();
                    element.dataset.originalCurrency = originalCurrency;
                }
                
                this.convertPrice(originalPrice, originalCurrency, toCurrency)
                    .then(convertedPrice => {
                        element.textContent = this.formatPrice(convertedPrice, toCurrency);
                        element.dataset.price = convertedPrice.toString();
                    })
                    .catch(error => {
                        console.error('Price conversion error:', error);
                    });
            }
        });

        // Update WooCommerce price elements
        const wooCommercePrices = document.querySelectorAll('.woocommerce-Price-amount');
        wooCommercePrices.forEach(element => {
            this.convertWooCommercePrice(element, toCurrency);
        });
    }

    convertWooCommercePrice(element, toCurrency) {
        const priceText = element.textContent.replace(/[^\d.,]/g, '');
        const price = parseFloat(priceText.replace(',', '.'));
        
        if (!isNaN(price)) {
            const fromCurrency = this.getBaseCurrency();
            
            this.convertPrice(price, fromCurrency, toCurrency)
                .then(convertedPrice => {
                    const currencySymbol = this.getCurrencySymbol(toCurrency);
                    const formattedPrice = this.formatPrice(convertedPrice, toCurrency);
                    element.innerHTML = element.innerHTML.replace(element.textContent, formattedPrice);
                })
                .catch(error => {
                    console.error('WooCommerce price conversion error:', error);
                });
        }
    }

    async convertPrice(amount, fromCurrency, toCurrency) {
        if (fromCurrency === toCurrency) {
            return amount;
        }

        const rate = await this.getExchangeRate(fromCurrency, toCurrency);
        return amount * rate;
    }

    async getExchangeRate(fromCurrency, toCurrency) {
        const cacheKey = `${fromCurrency}_${toCurrency}`;
        const cached = this.ratesCache[cacheKey];
        
        if (cached && (Date.now() - cached.timestamp) < this.cacheTTL) {
            return cached.rate;
        }

        try {
            const rate = await this.fetchExchangeRate(fromCurrency, toCurrency);
            this.ratesCache[cacheKey] = {
                rate: rate,
                timestamp: Date.now()
            };
            return rate;
        } catch (error) {
            console.error('Exchange rate fetch error:', error);
            return 1; // Fallback to 1:1 ratio
        }
    }

    async fetchExchangeRate(fromCurrency, toCurrency) {
        // Try WordPress AJAX first
        const formData = new FormData();
        formData.append('action', 'aqualuxe_get_exchange_rate');
        formData.append('from', fromCurrency);
        formData.append('to', toCurrency);
        formData.append('nonce', window.AQUALUXE?.nonce || '');

        try {
            const response = await fetch(window.AQUALUXE?.ajaxUrl || '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success && data.data.rate) {
                return parseFloat(data.data.rate);
            }
        } catch (error) {
            console.error('WordPress exchange rate error:', error);
        }

        // Fallback to public API if available
        return await this.fetchExchangeRateFromAPI(fromCurrency, toCurrency);
    }

    async fetchExchangeRateFromAPI(fromCurrency, toCurrency) {
        // Using a free exchange rate API as fallback
        const apiUrl = `https://api.exchangerate-api.com/v4/latest/${fromCurrency}`;
        
        try {
            const response = await fetch(apiUrl);
            const data = await response.json();
            
            if (data.rates && data.rates[toCurrency]) {
                return data.rates[toCurrency];
            }
        } catch (error) {
            console.error('API exchange rate error:', error);
        }

        return 1; // Final fallback
    }

    initExchangeRates() {
        // Pre-load commonly used exchange rates
        const currentCurrency = this.getCurrentCurrency();
        const baseCurrency = this.getBaseCurrency();
        
        if (currentCurrency !== baseCurrency) {
            this.getExchangeRate(baseCurrency, currentCurrency);
        }
    }

    updateExchangeRates(rates) {
        const timestamp = Date.now();
        
        Object.entries(rates).forEach(([pair, rate]) => {
            this.ratesCache[pair] = {
                rate: parseFloat(rate),
                timestamp: timestamp
            };
        });
    }

    applyStoredCurrency() {
        const stored = localStorage.getItem(this.storageKey);
        if (stored) {
            this.updateCurrencyDisplay(stored);
        }
    }

    getCurrentCurrency() {
        return localStorage.getItem(this.storageKey) || 
               this.getBaseCurrency();
    }

    getBaseCurrency() {
        return window.AQUALUXE?.baseCurrency || 'USD';
    }

    getCurrencySymbol(currency) {
        const currencies = window.AQUALUXE?.currencies || this.getDefaultCurrencies();
        return currencies[currency]?.symbol || currency;
    }

    formatPrice(amount, currency) {
        const symbol = this.getCurrencySymbol(currency);
        const formattedAmount = amount.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        return `${symbol}${formattedAmount}`;
    }

    showLoadingState() {
        const switcher = document.querySelector('.currency-switcher');
        if (switcher) {
            switcher.classList.add('loading');
        }
    }

    hideLoadingState() {
        const switcher = document.querySelector('.currency-switcher');
        if (switcher) {
            switcher.classList.remove('loading');
        }
    }

    handleCurrencyChange(currency) {
        // Update any currency-dependent content
        this.updateCartCurrency(currency);
        this.updateCheckoutCurrency(currency);
    }

    updateCartCurrency(currency) {
        // Update cart totals if visible
        const cartTotals = document.querySelectorAll('.cart-total, .mini-cart-total');
        cartTotals.forEach(total => {
            this.convertWooCommercePrice(total, currency);
        });
    }

    updateCheckoutCurrency(currency) {
        // Update checkout totals if on checkout page
        const checkoutTotals = document.querySelectorAll('.order-total');
        checkoutTotals.forEach(total => {
            this.convertWooCommercePrice(total, currency);
        });
    }

    getDefaultCurrencies() {
        return {
            'USD': { symbol: '$', name: 'US Dollar' },
            'EUR': { symbol: '€', name: 'Euro' },
            'GBP': { symbol: '£', name: 'British Pound' },
            'JPY': { symbol: '¥', name: 'Japanese Yen' },
            'AUD': { symbol: 'A$', name: 'Australian Dollar' },
            'CAD': { symbol: 'C$', name: 'Canadian Dollar' },
            'CHF': { symbol: 'Fr', name: 'Swiss Franc' },
            'CNY': { symbol: '¥', name: 'Chinese Yuan' },
            'INR': { symbol: '₹', name: 'Indian Rupee' },
            'BRL': { symbol: 'R$', name: 'Brazilian Real' }
        };
    }

    // Public API methods
    getAvailableCurrencies() {
        return window.AQUALUXE?.currencies || this.getDefaultCurrencies();
    }

    setCurrency(currency) {
        this.switchCurrency(currency);
    }

    getCurrency() {
        return this.getCurrentCurrency();
    }

    clearCache() {
        this.ratesCache = {};
    }
}

// Initialize multicurrency support
document.addEventListener('DOMContentLoaded', () => {
    window.multicurrencySupport = new MulticurrencySupport();
});

// Export for global access
window.MulticurrencySupport = MulticurrencySupport;