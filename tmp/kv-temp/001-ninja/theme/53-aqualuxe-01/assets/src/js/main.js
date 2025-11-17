/**
 * AquaLuxe Theme JavaScript
 * 
 * Main JavaScript file for the AquaLuxe WordPress theme.
 * This file initializes all modules and sets up event listeners.
 */

// Import modules
import Navigation from './modules/navigation';
import DarkMode from './modules/dark-mode';
import ScrollEffects from './modules/scroll-effects';
import FormValidation from './modules/form-validation';
import AjaxLoader from './modules/ajax-loader';
import ProductFilter from './modules/product-filter';
import CurrencySwitcher from './modules/currency-switcher';
import LanguageSwitcher from './modules/language-switcher';

// Initialize when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize navigation
    const navigation = new Navigation();
    navigation.init();
    
    // Initialize dark mode
    const darkMode = new DarkMode();
    darkMode.init();
    
    // Initialize scroll effects
    const scrollEffects = new ScrollEffects();
    scrollEffects.init();
    
    // Initialize form validation
    const formValidation = new FormValidation();
    formValidation.init();
    
    // Initialize AJAX loader
    const ajaxLoader = new AjaxLoader();
    ajaxLoader.init();
    
    // Initialize WooCommerce product filter
    if (document.querySelector('.woocommerce')) {
        const productFilter = new ProductFilter();
        productFilter.init();
    }
    
    // Initialize currency switcher
    if (document.querySelector('.currency-switcher')) {
        const currencySwitcher = new CurrencySwitcher();
        currencySwitcher.init();
    }
    
    // Initialize language switcher
    if (document.querySelector('.language-switcher')) {
        const languageSwitcher = new LanguageSwitcher();
        languageSwitcher.init();
    }
});

// Handle service worker for offline capabilities
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js').then(registration => {
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }).catch(err => {
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}