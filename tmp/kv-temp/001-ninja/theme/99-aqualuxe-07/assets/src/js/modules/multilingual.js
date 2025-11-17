/**
 * Multilingual Module JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

class MultilingualSupport {
    constructor() {
        this.storageKey = 'aqualuxe-language';
        this.init();
    }

    init() {
        this.createLanguageSwitcher();
        this.bindEvents();
        this.applyStoredLanguage();
        this.initRTLSupport();
    }

    createLanguageSwitcher() {
        const existingSwitcher = document.querySelector('.language-switcher');
        if (existingSwitcher) return;

        // Get available languages from global object
        const languages = window.AQUALUXE?.languages || this.getDefaultLanguages();
        
        if (Object.keys(languages).length <= 1) return;

        const switcher = document.createElement('div');
        switcher.className = 'language-switcher relative inline-block';
        
        const currentLang = this.getCurrentLanguage();
        const currentLangData = languages[currentLang] || languages[Object.keys(languages)[0]];

        switcher.innerHTML = `
            <button class="language-toggle flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                <span class="language-flag">${currentLangData.flag}</span>
                <span class="language-name">${currentLangData.name}</span>
                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div class="language-dropdown absolute top-full left-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg min-w-48 z-50 hidden">
                ${Object.entries(languages).map(([code, lang]) => `
                    <a href="#" 
                       class="language-option flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 ${code === currentLang ? 'bg-primary-50 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : ''}"
                       data-language="${code}">
                        <span class="text-lg">${lang.flag}</span>
                        <span class="flex-1">${lang.name}</span>
                        ${code === currentLang ? '<svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' : ''}
                    </a>
                `).join('')}
            </div>
        `;

        // Add to header navigation
        const nav = document.querySelector('.primary-navigation') || document.querySelector('nav') || document.body;
        nav.appendChild(switcher);
    }

    bindEvents() {
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('.language-toggle');
            const option = e.target.closest('.language-option');
            const switcher = e.target.closest('.language-switcher');

            if (toggle) {
                e.preventDefault();
                this.toggleDropdown();
            } else if (option) {
                e.preventDefault();
                const language = option.dataset.language;
                this.switchLanguage(language);
            } else if (!switcher) {
                this.closeDropdown();
            }
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeDropdown();
            }
        });

        // Listen for language change events
        document.addEventListener('languageChanged', (e) => {
            this.handleLanguageChange(e.detail.language);
        });
    }

    toggleDropdown() {
        const dropdown = document.querySelector('.language-dropdown');
        const toggle = document.querySelector('.language-toggle svg');
        
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
        const dropdown = document.querySelector('.language-dropdown');
        const toggle = document.querySelector('.language-toggle svg');
        
        if (dropdown && toggle) {
            dropdown.classList.remove('hidden');
            toggle.style.transform = 'rotate(180deg)';
        }
    }

    closeDropdown() {
        const dropdown = document.querySelector('.language-dropdown');
        const toggle = document.querySelector('.language-toggle svg');
        
        if (dropdown && toggle) {
            dropdown.classList.add('hidden');
            toggle.style.transform = 'rotate(0deg)';
        }
    }

    switchLanguage(language) {
        // Store the selected language
        localStorage.setItem(this.storageKey, language);
        
        // Update the UI immediately
        this.updateLanguageDisplay(language);
        
        // Send AJAX request to switch language
        this.sendLanguageChangeRequest(language);
        
        // Trigger custom event
        document.dispatchEvent(new CustomEvent('languageChanged', {
            detail: { language }
        }));
        
        this.closeDropdown();
    }

    sendLanguageChangeRequest(language) {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_switch_language');
        formData.append('language', language);
        formData.append('nonce', window.AQUALUXE?.nonce || '');

        fetch(window.AQUALUXE?.ajaxUrl || '/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.reload) {
                // Reload the page to apply language changes
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Language switch error:', error);
        });
    }

    updateLanguageDisplay(language) {
        const languages = window.AQUALUXE?.languages || this.getDefaultLanguages();
        const langData = languages[language];
        
        if (!langData) return;

        const flag = document.querySelector('.language-flag');
        const name = document.querySelector('.language-name');
        
        if (flag) flag.textContent = langData.flag;
        if (name) name.textContent = langData.name;

        // Update active state in dropdown
        const options = document.querySelectorAll('.language-option');
        options.forEach(option => {
            const isActive = option.dataset.language === language;
            option.classList.toggle('bg-primary-50', isActive);
            option.classList.toggle('dark:bg-primary-900', isActive);
            option.classList.toggle('text-primary-700', isActive);
            option.classList.toggle('dark:text-primary-300', isActive);
            
            const checkmark = option.querySelector('svg');
            if (checkmark) {
                checkmark.style.display = isActive ? 'block' : 'none';
            }
        });

        // Update document language attribute
        document.documentElement.lang = language;
    }

    applyStoredLanguage() {
        const stored = localStorage.getItem(this.storageKey);
        if (stored) {
            this.updateLanguageDisplay(stored);
        }
    }

    getCurrentLanguage() {
        return localStorage.getItem(this.storageKey) || 
               document.documentElement.lang || 
               'en';
    }

    initRTLSupport() {
        const rtlLanguages = ['ar', 'he', 'fa', 'ur'];
        const currentLang = this.getCurrentLanguage();
        
        if (rtlLanguages.includes(currentLang)) {
            document.documentElement.dir = 'rtl';
            document.body.classList.add('rtl');
        } else {
            document.documentElement.dir = 'ltr';
            document.body.classList.remove('rtl');
        }
    }

    handleLanguageChange(language) {
        this.initRTLSupport();
        
        // Update any language-dependent content
        this.updateCurrencyFormat(language);
        this.updateDateFormat(language);
        this.updateNumberFormat(language);
    }

    updateCurrencyFormat(language) {
        // Update currency display based on language
        const currencyElements = document.querySelectorAll('[data-currency]');
        currencyElements.forEach(element => {
            const amount = parseFloat(element.dataset.amount);
            const currency = element.dataset.currency;
            
            if (!isNaN(amount)) {
                element.textContent = this.formatCurrency(amount, currency, language);
            }
        });
    }

    formatCurrency(amount, currency, language) {
        try {
            return new Intl.NumberFormat(language, {
                style: 'currency',
                currency: currency
            }).format(amount);
        } catch (error) {
            return `${currency} ${amount.toFixed(2)}`;
        }
    }

    updateDateFormat(language) {
        const dateElements = document.querySelectorAll('[data-date]');
        dateElements.forEach(element => {
            const date = new Date(element.dataset.date);
            
            if (!isNaN(date.getTime())) {
                element.textContent = this.formatDate(date, language);
            }
        });
    }

    formatDate(date, language) {
        try {
            return new Intl.DateTimeFormat(language).format(date);
        } catch (error) {
            return date.toLocaleDateString();
        }
    }

    updateNumberFormat(language) {
        const numberElements = document.querySelectorAll('[data-number]');
        numberElements.forEach(element => {
            const number = parseFloat(element.dataset.number);
            
            if (!isNaN(number)) {
                element.textContent = this.formatNumber(number, language);
            }
        });
    }

    formatNumber(number, language) {
        try {
            return new Intl.NumberFormat(language).format(number);
        } catch (error) {
            return number.toLocaleString();
        }
    }

    getDefaultLanguages() {
        return {
            'en': { name: 'English', flag: '🇺🇸' },
            'es': { name: 'Español', flag: '🇪🇸' },
            'fr': { name: 'Français', flag: '🇫🇷' },
            'de': { name: 'Deutsch', flag: '🇩🇪' },
            'it': { name: 'Italiano', flag: '🇮🇹' },
            'pt': { name: 'Português', flag: '🇵🇹' },
            'ar': { name: 'العربية', flag: '🇸🇦' },
            'zh': { name: '中文', flag: '🇨🇳' },
            'ja': { name: '日本語', flag: '🇯🇵' },
            'ko': { name: '한국어', flag: '🇰🇷' }
        };
    }

    // Public API methods
    getAvailableLanguages() {
        return window.AQUALUXE?.languages || this.getDefaultLanguages();
    }

    setLanguage(language) {
        this.switchLanguage(language);
    }

    getLanguage() {
        return this.getCurrentLanguage();
    }
}

// Initialize multilingual support
document.addEventListener('DOMContentLoaded', () => {
    window.multilingualSupport = new MultilingualSupport();
});

// Export for global access
window.MultilingualSupport = MultilingualSupport;