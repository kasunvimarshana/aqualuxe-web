/**
 * AquaLuxe Theme Dark Mode JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Dark mode toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            toggleDarkMode();
        });
    }
    
    // Initialize dark mode
    initDarkMode();
});

/**
 * Initialize dark mode
 */
function initDarkMode() {
    // Check if dark mode is enabled in theme options
    const darkModeEnabled = aqualuxeDarkMode.enabled || false;
    
    if (!darkModeEnabled) {
        return;
    }
    
    // Check if auto dark mode is enabled
    const autoDarkMode = aqualuxeDarkMode.auto || false;
    
    // Get dark mode preference from cookie
    const darkModeCookie = getCookie(aqualuxeDarkMode.cookieName);
    
    // Set dark mode based on preference
    if (darkModeCookie !== null) {
        setDarkMode(darkModeCookie === 'dark');
    } else if (autoDarkMode) {
        // Check if user prefers dark mode
        const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        setDarkMode(prefersDarkMode);
    }
    
    // Update toggle button
    updateDarkModeToggle();
    
    // Listen for changes in system preference
    if (autoDarkMode) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            // Only change if no cookie is set
            if (getCookie(aqualuxeDarkMode.cookieName) === null) {
                setDarkMode(e.matches);
                updateDarkModeToggle();
            }
        });
    }
}

/**
 * Toggle dark mode
 */
function toggleDarkMode() {
    const isDarkMode = document.documentElement.classList.contains('dark-mode');
    setDarkMode(!isDarkMode);
    updateDarkModeToggle();
    
    // Save preference in cookie
    setCookie(aqualuxeDarkMode.cookieName, !isDarkMode ? 'dark' : 'light', aqualuxeDarkMode.cookieExpiry);
    
    // Send AJAX request to save preference
    const xhr = new XMLHttpRequest();
    xhr.open('POST', aqualuxeDarkMode.ajaxUrl, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(
        'action=aqualuxe_toggle_dark_mode' +
        '&nonce=' + encodeURIComponent(aqualuxeDarkMode.nonce) +
        '&mode=' + encodeURIComponent(!isDarkMode ? 'dark' : 'light')
    );
}

/**
 * Set dark mode
 *
 * @param {boolean} isDark Whether to enable dark mode
 */
function setDarkMode(isDark) {
    if (isDark) {
        document.documentElement.classList.add('dark-mode');
        document.documentElement.classList.remove('light-mode');
    } else {
        document.documentElement.classList.add('light-mode');
        document.documentElement.classList.remove('dark-mode');
    }
}

/**
 * Update dark mode toggle
 */
function updateDarkModeToggle() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    
    if (!darkModeToggle) {
        return;
    }
    
    const isDarkMode = document.documentElement.classList.contains('dark-mode');
    
    // Update toggle text
    darkModeToggle.setAttribute('aria-label', isDarkMode ? aqualuxeDarkMode.toggleText.dark : aqualuxeDarkMode.toggleText.light);
    
    // Update toggle icon
    darkModeToggle.innerHTML = isDarkMode ? aqualuxeDarkMode.toggleIcon.dark : aqualuxeDarkMode.toggleIcon.light;
}

/**
 * Get cookie value
 *
 * @param {string} name Cookie name
 * @return {string|null} Cookie value
 */
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

/**
 * Set cookie
 *
 * @param {string} name Cookie name
 * @param {string} value Cookie value
 * @param {number} days Cookie expiry in days
 */
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = `expires=${date.toUTCString()}`;
    document.cookie = `${name}=${value};${expires};path=/`;
}