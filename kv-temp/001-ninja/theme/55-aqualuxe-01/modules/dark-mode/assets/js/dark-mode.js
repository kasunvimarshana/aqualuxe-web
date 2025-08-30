/**
 * Dark Mode JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/Dark_Mode
 */

(function($) {
    'use strict';

    // Initialize dark mode on document ready
    $(document).ready(function() {
        // This is just a placeholder as the main functionality is handled by Alpine.js
        // in the script template. This file is for any additional JavaScript functionality.
        
        // Dispatch an event when dark mode changes
        function dispatchDarkModeEvent(isDark) {
            const event = new CustomEvent('darkModeChange', {
                detail: {
                    isDark: isDark
                }
            });
            document.dispatchEvent(event);
        }
        
        // Listen for dark mode changes from Alpine.js
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    const isDark = document.documentElement.classList.contains('dark');
                    dispatchDarkModeEvent(isDark);
                }
            });
        });
        
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    });
})(jQuery);