/**
 * Cookie Notice Module
 *
 * This file contains the JavaScript code for the cookie notice functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get cookie notice element
const cookieNotice = document.getElementById('cookie-notice');
const acceptButton = document.getElementById('cookie-notice-accept');
const declineButton = document.getElementById('cookie-notice-decline');

// Set cookie
const setCookie = (name, value, days) => {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = '; expires=' + date.toUTCString();
    document.cookie = name + '=' + value + expires + '; path=/; SameSite=Lax';
};

// Get cookie
const getCookie = (name) => {
    const nameEQ = name + '=';
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
};

// Hide cookie notice
const hideCookieNotice = () => {
    if (!cookieNotice) return;
    
    cookieNotice.classList.add('hidden');
    setTimeout(() => {
        cookieNotice.style.display = 'none';
    }, 500);
};

// Accept cookies
const acceptCookies = () => {
    setCookie('aqualuxe_cookie_notice', 'accepted', 365);
    hideCookieNotice();
    
    // Enable analytics if needed
    if (typeof enableAnalytics === 'function') {
        enableAnalytics();
    }
};

// Decline cookies
const declineCookies = () => {
    setCookie('aqualuxe_cookie_notice', 'declined', 365);
    hideCookieNotice();
};

// Initialize cookie notice functionality
const initCookieNotice = () => {
    if (!cookieNotice) return;
    
    // Check if user has already responded to cookie notice
    const cookieNoticeStatus = getCookie('aqualuxe_cookie_notice');
    
    if (cookieNoticeStatus) {
        // Hide cookie notice if user has already responded
        cookieNotice.style.display = 'none';
        
        // Enable analytics if cookies were accepted
        if (cookieNoticeStatus === 'accepted' && typeof enableAnalytics === 'function') {
            enableAnalytics();
        }
    } else {
        // Show cookie notice if user hasn't responded yet
        cookieNotice.style.display = 'block';
        
        // Add event listeners to buttons
        if (acceptButton) {
            acceptButton.addEventListener('click', acceptCookies);
        }
        
        if (declineButton) {
            declineButton.addEventListener('click', declineCookies);
        }
    }
};

// Enable analytics
const enableAnalytics = () => {
    // Google Analytics
    if (typeof gtag === 'function' && aqualuxeSettings && aqualuxeSettings.googleAnalyticsId) {
        gtag('consent', 'update', {
            'analytics_storage': 'granted'
        });
    }
    
    // Facebook Pixel
    if (typeof fbq === 'function' && aqualuxeSettings && aqualuxeSettings.facebookPixelId) {
        fbq('consent', 'grant');
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initCookieNotice);

// Export module
export default {
    initCookieNotice,
    acceptCookies,
    declineCookies,
    setCookie,
    getCookie
};