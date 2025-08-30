/**
 * AquaLuxe Theme Helper Functions
 *
 * Utility functions used throughout the theme's JavaScript.
 */

/**
 * Debounce function to limit how often a function can be called
 * 
 * @param {Function} func - The function to debounce
 * @param {number} wait - The time to wait in milliseconds
 * @param {boolean} immediate - Whether to call the function immediately
 * @returns {Function} - The debounced function
 */
export const debounce = (func, wait, immediate) => {
    let timeout;
    
    return function executedFunction() {
        const context = this;
        const args = arguments;
        
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        
        const callNow = immediate && !timeout;
        
        clearTimeout(timeout);
        
        timeout = setTimeout(later, wait);
        
        if (callNow) func.apply(context, args);
    };
};

/**
 * Throttle function to limit how often a function can be called
 * 
 * @param {Function} func - The function to throttle
 * @param {number} limit - The time limit in milliseconds
 * @returns {Function} - The throttled function
 */
export const throttle = (func, limit) => {
    let inThrottle;
    
    return function() {
        const args = arguments;
        const context = this;
        
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
};

/**
 * Check if an element is in the viewport
 * 
 * @param {HTMLElement} el - The element to check
 * @param {number} offset - Optional offset from the viewport edge
 * @returns {boolean} - Whether the element is in the viewport
 */
export const isInViewport = (el, offset = 0) => {
    if (!el) return false;
    
    const rect = el.getBoundingClientRect();
    
    return (
        rect.top >= 0 - offset &&
        rect.left >= 0 - offset &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) + offset &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) + offset
    );
};

/**
 * Get a cookie value by name
 * 
 * @param {string} name - The name of the cookie
 * @returns {string|null} - The cookie value or null if not found
 */
export const getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    
    if (parts.length === 2) {
        return parts.pop().split(';').shift();
    }
    
    return null;
};

/**
 * Set a cookie
 * 
 * @param {string} name - The name of the cookie
 * @param {string} value - The value of the cookie
 * @param {number} days - The number of days until the cookie expires
 */
export const setCookie = (name, value, days) => {
    let expires = '';
    
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = `; expires=${date.toUTCString()}`;
    }
    
    document.cookie = `${name}=${value || ''}${expires}; path=/`;
};

/**
 * Format a price according to the current locale
 * 
 * @param {number} price - The price to format
 * @param {string} currencyCode - The currency code (e.g., 'USD')
 * @returns {string} - The formatted price
 */
export const formatPrice = (price, currencyCode = 'USD') => {
    return new Intl.NumberFormat(document.documentElement.lang || 'en-US', {
        style: 'currency',
        currency: currencyCode
    }).format(price);
};

/**
 * Trap focus within an element (for modals, dropdowns, etc.)
 * 
 * @param {HTMLElement} element - The element to trap focus within
 */
export const trapFocus = (element) => {
    const focusableElements = element.querySelectorAll(
        'a[href], button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
    );
    
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];
    
    function handleTabKey(e) {
        const isTabPressed = e.key === 'Tab' || e.keyCode === 9;
        
        if (!isTabPressed) return;
        
        if (e.shiftKey) {
            if (document.activeElement === firstElement) {
                lastElement.focus();
                e.preventDefault();
            }
        } else {
            if (document.activeElement === lastElement) {
                firstElement.focus();
                e.preventDefault();
            }
        }
    }
    
    element.addEventListener('keydown', handleTabKey);
    
    return {
        release: () => {
            element.removeEventListener('keydown', handleTabKey);
        }
    };
};