/**
 * Currency Switcher Module
 * 
 * Handles currency switching functionality:
 * - Currency selection
 * - Price conversion
 * - Currency symbol display
 * - Persistence of currency preference
 */

class CurrencySwitcher {
    constructor() {
        this.switcher = document.querySelector('.currency-switcher');
        this.currencySelect = document.querySelector('.currency-select');
        this.currencyOptions = document.querySelectorAll('.currency-option');
        this.priceElements = document.querySelectorAll('.price');
        this.storageKey = 'aqualuxe_currency';
        this.defaultCurrency = 'USD';
        this.currentCurrency = this.defaultCurrency;
        this.exchangeRates = {};
        this.symbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'JPY': '¥',
            'CAD': 'C$',
            'AUD': 'A$',
            'CHF': 'CHF',
            'CNY': '¥',
            'INR': '₹',
            'BRL': 'R$'
        };
        this.ajaxUrl = window.aqualuxe?.ajaxUrl || '/wp-admin/admin-ajax.php';
        this.nonce = window.aqualuxe?.nonce || '';
    }

    init() {
        if (!this.switcher) {
            return;
        }

        this.loadExchangeRates()
            .then(() => {
                this.loadSavedCurrency();
                this.setupEventListeners();
                this.updatePrices();
            })
            .catch(error => {
                console.error('Failed to initialize currency switcher:', error);
            });
    }

    loadExchangeRates() {
        // First check if rates are already available in window.aqualuxe
        if (window.aqualuxe?.exchangeRates) {
            this.exchangeRates = window.aqualuxe.exchangeRates;
            return Promise.resolve();
        }

        // Otherwise fetch from server
        return new Promise((resolve, reject) => {
            const data = new FormData();
            data.append('action', 'get_exchange_rates');
            data.append('nonce', this.nonce);

            fetch(this.ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                body: data
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                if (response.success && response.data) {
                    this.exchangeRates = response.data;
                    resolve();
                } else {
                    reject(new Error('Failed to load exchange rates'));
                }
            })
            .catch(error => {
                console.error('AJAX error:', error);
                reject(error);
            });
        });
    }

    loadSavedCurrency() {
        const savedCurrency = localStorage.getItem(this.storageKey);
        
        if (savedCurrency && this.isValidCurrency(savedCurrency)) {
            this.currentCurrency = savedCurrency;
        } else {
            // Try to detect user's currency based on browser locale
            this.detectUserCurrency();
        }
        
        // Update UI to reflect current currency
        this.updateCurrencyUI();
    }

    detectUserCurrency() {
        try {
            const locale = navigator.language || navigator.userLanguage;
            
            // Map common locales to currencies
            const localeCurrencyMap = {
                'en-US': 'USD',
                'en-GB': 'GBP',
                'en-CA': 'CAD',
                'en-AU': 'AUD',
                'fr-FR': 'EUR',
                'de-DE': 'EUR',
                'it-IT': 'EUR',
                'es-ES': 'EUR',
                'ja-JP': 'JPY',
                'zh-CN': 'CNY',
                'hi-IN': 'INR',
                'pt-BR': 'BRL'
            };
            
            // Check if we have a mapping for this locale
            if (locale in localeCurrencyMap) {
                const detectedCurrency = localeCurrencyMap[locale];
                
                // Only use if we have exchange rates for it
                if (this.isValidCurrency(detectedCurrency)) {
                    this.currentCurrency = detectedCurrency;
                }
            }
        } catch (e) {
            console.error('Error detecting user currency:', e);
        }
    }

    isValidCurrency(currency) {
        return currency in this.exchangeRates || currency === this.defaultCurrency;
    }

    setupEventListeners() {
        // Handle dropdown select
        if (this.currencySelect) {
            this.currencySelect.addEventListener('change', () => {
                this.changeCurrency(this.currencySelect.value);
            });
        }
        
        // Handle currency option clicks
        if (this.currencyOptions.length) {
            this.currencyOptions.forEach(option => {
                option.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.changeCurrency(option.dataset.currency);
                });
            });
        }
    }

    changeCurrency(newCurrency) {
        if (!this.isValidCurrency(newCurrency) || newCurrency === this.currentCurrency) {
            return;
        }
        
        this.currentCurrency = newCurrency;
        this.saveCurrency();
        this.updateCurrencyUI();
        this.updatePrices();
        
        // Trigger event for other components
        document.dispatchEvent(new CustomEvent('currencyChanged', {
            detail: { currency: this.currentCurrency }
        }));
    }

    saveCurrency() {
        localStorage.setItem(this.storageKey, this.currentCurrency);
    }

    updateCurrencyUI() {
        // Update select dropdown if it exists
        if (this.currencySelect) {
            this.currencySelect.value = this.currentCurrency;
        }
        
        // Update currency options
        if (this.currencyOptions.length) {
            this.currencyOptions.forEach(option => {
                if (option.dataset.currency === this.currentCurrency) {
                    option.classList.add('active');
                } else {
                    option.classList.remove('active');
                }
            });
        }
        
        // Update currency display elements
        const currencyDisplays = document.querySelectorAll('.currency-display');
        currencyDisplays.forEach(display => {
            display.textContent = this.currentCurrency;
        });
        
        // Update currency symbol elements
        const symbolDisplays = document.querySelectorAll('.currency-symbol');
        const symbol = this.getSymbol(this.currentCurrency);
        symbolDisplays.forEach(display => {
            display.textContent = symbol;
        });
    }

    updatePrices() {
        if (!this.priceElements.length) {
            return;
        }
        
        this.priceElements.forEach(priceElement => {
            // Skip if already in current currency
            if (priceElement.dataset.currency === this.currentCurrency) {
                return;
            }
            
            // Get original price and currency
            const originalCurrency = priceElement.dataset.currency || this.defaultCurrency;
            const originalPrice = parseFloat(priceElement.dataset.originalPrice || priceElement.dataset.price);
            
            if (isNaN(originalPrice)) {
                return;
            }
            
            // Convert price
            const convertedPrice = this.convertPrice(originalPrice, originalCurrency, this.currentCurrency);
            
            // Format price
            const formattedPrice = this.formatPrice(convertedPrice, this.currentCurrency);
            
            // Update price element
            priceElement.innerHTML = formattedPrice;
            
            // Store current currency
            priceElement.dataset.currency = this.currentCurrency;
            
            // Store original price if not already stored
            if (!priceElement.dataset.originalPrice) {
                priceElement.dataset.originalPrice = originalPrice;
            }
        });
    }

    convertPrice(price, fromCurrency, toCurrency) {
        // No conversion needed if currencies are the same
        if (fromCurrency === toCurrency) {
            return price;
        }
        
        // Convert to base currency (USD) first if needed
        let basePrice = price;
        if (fromCurrency !== this.defaultCurrency) {
            basePrice = price / this.exchangeRates[fromCurrency];
        }
        
        // Convert from base currency to target currency
        if (toCurrency === this.defaultCurrency) {
            return basePrice;
        } else {
            return basePrice * this.exchangeRates[toCurrency];
        }
    }

    formatPrice(price, currency) {
        const symbol = this.getSymbol(currency);
        
        // Format based on currency
        let formattedPrice;
        
        switch (currency) {
            case 'JPY':
            case 'CNY':
                // No decimal places for JPY and CNY
                formattedPrice = Math.round(price).toLocaleString();
                break;
            default:
                // 2 decimal places for most currencies
                formattedPrice = price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
        
        // Position symbol based on currency
        if (['USD', 'CAD', 'AUD', 'HKD', 'SGD'].includes(currency)) {
            return `${symbol}${formattedPrice}`;
        } else {
            return `${formattedPrice} ${symbol}`;
        }
    }

    getSymbol(currency) {
        return this.symbols[currency] || currency;
    }
}

export default CurrencySwitcher;