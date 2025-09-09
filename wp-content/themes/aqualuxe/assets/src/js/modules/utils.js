/**
 * Utility Module
 * 
 * Common utility functions and helpers
 * 
 * @package AquaLuxe
 * @since 2.0.0
 */

export class Utils {
    constructor(config = {}) {
        this.config = {
            debug: false,
            ...config
        };
    }

    /**
     * Debounce function
     */
    static debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    }

    /**
     * Throttle function
     */
    static throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Check if element is in viewport
     */
    static isInViewport(element, threshold = 0) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= -threshold &&
            rect.left >= -threshold &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) + threshold &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth) + threshold
        );
    }

    /**
     * Smooth scroll to element
     */
    static scrollTo(target, offset = 0) {
        const element = typeof target === 'string' ? document.querySelector(target) : target;
        if (element) {
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }

    /**
     * Get URL parameters
     */
    static getUrlParams(url = window.location.href) {
        const params = new URLSearchParams(new URL(url).search);
        const result = {};
        for (const [key, value] of params) {
            result[key] = value;
        }
        return result;
    }
}