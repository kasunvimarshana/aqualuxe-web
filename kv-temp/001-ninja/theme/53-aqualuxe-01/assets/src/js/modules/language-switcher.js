/**
 * Language Switcher Module
 * 
 * Handles language switching functionality:
 * - Language selection
 * - Content translation
 * - RTL/LTR direction switching
 * - Persistence of language preference
 */

class LanguageSwitcher {
    constructor() {
        this.switcher = document.querySelector('.language-switcher');
        this.languageSelect = document.querySelector('.language-select');
        this.languageOptions = document.querySelectorAll('.language-option');
        this.storageKey = 'aqualuxe_language';
        this.defaultLanguage = 'en';
        this.currentLanguage = this.defaultLanguage;
        this.rtlLanguages = ['ar', 'he', 'fa', 'ur'];
        this.translations = {};
        this.ajaxUrl = window.aqualuxe?.ajaxUrl || '/wp-admin/admin-ajax.php';
        this.nonce = window.aqualuxe?.nonce || '';
    }

    init() {
        if (!this.switcher) {
            return;
        }

        this.loadTranslations()
            .then(() => {
                this.loadSavedLanguage();
                this.setupEventListeners();
            })
            .catch(error => {
                console.error('Failed to initialize language switcher:', error);
            });
    }

    loadTranslations() {
        // First check if translations are already available in window.aqualuxe
        if (window.aqualuxe?.translations) {
            this.translations = window.aqualuxe.translations;
            return Promise.resolve();
        }

        // Otherwise fetch from server
        return new Promise((resolve, reject) => {
            const data = new FormData();
            data.append('action', 'get_translations');
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
                    this.translations = response.data;
                    resolve();
                } else {
                    reject(new Error('Failed to load translations'));
                }
            })
            .catch(error => {
                console.error('AJAX error:', error);
                reject(error);
            });
        });
    }

    loadSavedLanguage() {
        const savedLanguage = localStorage.getItem(this.storageKey);
        
        if (savedLanguage && this.isValidLanguage(savedLanguage)) {
            this.currentLanguage = savedLanguage;
        } else {
            // Try to detect user's language based on browser locale
            this.detectUserLanguage();
        }
        
        // Apply the language
        this.applyLanguage();
        
        // Update UI to reflect current language
        this.updateLanguageUI();
    }

    detectUserLanguage() {
        try {
            const locale = navigator.language || navigator.userLanguage;
            
            // Extract language code from locale (e.g., 'en-US' -> 'en')
            const detectedLanguage = locale.split('-')[0];
            
            // Only use if we have translations for it
            if (this.isValidLanguage(detectedLanguage)) {
                this.currentLanguage = detectedLanguage;
            }
        } catch (e) {
            console.error('Error detecting user language:', e);
        }
    }

    isValidLanguage(language) {
        return language in this.translations || language === this.defaultLanguage;
    }

    setupEventListeners() {
        // Handle dropdown select
        if (this.languageSelect) {
            this.languageSelect.addEventListener('change', () => {
                this.changeLanguage(this.languageSelect.value);
            });
        }
        
        // Handle language option clicks
        if (this.languageOptions.length) {
            this.languageOptions.forEach(option => {
                option.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.changeLanguage(option.dataset.language);
                });
            });
        }
    }

    changeLanguage(newLanguage) {
        if (!this.isValidLanguage(newLanguage) || newLanguage === this.currentLanguage) {
            return;
        }
        
        this.currentLanguage = newLanguage;
        this.saveLanguage();
        this.applyLanguage();
        this.updateLanguageUI();
        
        // Trigger event for other components
        document.dispatchEvent(new CustomEvent('languageChanged', {
            detail: { language: this.currentLanguage }
        }));
    }

    saveLanguage() {
        localStorage.setItem(this.storageKey, this.currentLanguage);
    }

    applyLanguage() {
        // Set HTML lang attribute
        document.documentElement.setAttribute('lang', this.currentLanguage);
        
        // Handle RTL/LTR direction
        if (this.rtlLanguages.includes(this.currentLanguage)) {
            document.documentElement.setAttribute('dir', 'rtl');
            document.body.classList.add('rtl');
        } else {
            document.documentElement.setAttribute('dir', 'ltr');
            document.body.classList.remove('rtl');
        }
        
        // Apply translations to elements with data-translate attribute
        const translatableElements = document.querySelectorAll('[data-translate]');
        translatableElements.forEach(element => {
            const key = element.dataset.translate;
            
            if (this.hasTranslation(key)) {
                // Store original text if not already stored
                if (!element.dataset.originalText) {
                    element.dataset.originalText = element.textContent;
                }
                
                // Apply translation
                element.textContent = this.getTranslation(key);
            } else if (element.dataset.originalText && this.currentLanguage === this.defaultLanguage) {
                // Restore original text if switching back to default language
                element.textContent = element.dataset.originalText;
            }
        });
        
        // Apply translations to elements with data-translate-attr attribute
        const attrTranslatableElements = document.querySelectorAll('[data-translate-attr]');
        attrTranslatableElements.forEach(element => {
            const attrData = element.dataset.translateAttr.split(':');
            if (attrData.length !== 2) return;
            
            const attr = attrData[0];
            const key = attrData[1];
            
            if (this.hasTranslation(key)) {
                // Store original attribute if not already stored
                if (!element.dataset[`originalAttr${attr}`]) {
                    element.dataset[`originalAttr${attr}`] = element.getAttribute(attr);
                }
                
                // Apply translation to attribute
                element.setAttribute(attr, this.getTranslation(key));
            } else if (element.dataset[`originalAttr${attr}`] && this.currentLanguage === this.defaultLanguage) {
                // Restore original attribute if switching back to default language
                element.setAttribute(attr, element.dataset[`originalAttr${attr}`]);
            }
        });
        
        // Apply translations to placeholder attributes
        const placeholderElements = document.querySelectorAll('[data-translate-placeholder]');
        placeholderElements.forEach(element => {
            const key = element.dataset.translatePlaceholder;
            
            if (this.hasTranslation(key)) {
                // Store original placeholder if not already stored
                if (!element.dataset.originalPlaceholder) {
                    element.dataset.originalPlaceholder = element.getAttribute('placeholder');
                }
                
                // Apply translation to placeholder
                element.setAttribute('placeholder', this.getTranslation(key));
            } else if (element.dataset.originalPlaceholder && this.currentLanguage === this.defaultLanguage) {
                // Restore original placeholder if switching back to default language
                element.setAttribute('placeholder', element.dataset.originalPlaceholder);
            }
        });
    }

    updateLanguageUI() {
        // Update select dropdown if it exists
        if (this.languageSelect) {
            this.languageSelect.value = this.currentLanguage;
        }
        
        // Update language options
        if (this.languageOptions.length) {
            this.languageOptions.forEach(option => {
                if (option.dataset.language === this.currentLanguage) {
                    option.classList.add('active');
                } else {
                    option.classList.remove('active');
                }
            });
        }
        
        // Update language display elements
        const languageDisplays = document.querySelectorAll('.language-display');
        languageDisplays.forEach(display => {
            display.textContent = this.getLanguageName(this.currentLanguage);
        });
        
        // Update language flag elements
        const flagDisplays = document.querySelectorAll('.language-flag');
        flagDisplays.forEach(display => {
            if (display.tagName === 'IMG') {
                display.src = this.getFlagUrl(this.currentLanguage);
                display.alt = this.getLanguageName(this.currentLanguage);
            } else {
                display.style.backgroundImage = `url(${this.getFlagUrl(this.currentLanguage)})`;
            }
        });
    }

    hasTranslation(key) {
        if (this.currentLanguage === this.defaultLanguage) {
            return true; // Default language doesn't need translation
        }
        
        return this.translations[this.currentLanguage] && 
               this.translations[this.currentLanguage][key] !== undefined;
    }

    getTranslation(key) {
        if (this.currentLanguage === this.defaultLanguage) {
            return key; // For default language, key is the text
        }
        
        if (this.hasTranslation(key)) {
            return this.translations[this.currentLanguage][key];
        }
        
        return key; // Fallback to key if translation not found
    }

    getLanguageName(code) {
        const languageNames = {
            'en': 'English',
            'es': 'Español',
            'fr': 'Français',
            'de': 'Deutsch',
            'it': 'Italiano',
            'pt': 'Português',
            'ru': 'Русский',
            'zh': '中文',
            'ja': '日本語',
            'ar': 'العربية',
            'hi': 'हिन्दी',
            'ko': '한국어'
        };
        
        return languageNames[code] || code.toUpperCase();
    }

    getFlagUrl(code) {
        return `/wp-content/themes/aqualuxe/assets/images/flags/${code}.svg`;
    }
}

export default LanguageSwitcher;