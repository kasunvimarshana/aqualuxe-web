/**
 * Multilingual JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 */

(function($) {
    'use strict';

    // Initialize multilingual functionality on document ready
    $(document).ready(function() {
        // This is just a placeholder as the main functionality is handled by Alpine.js
        // in the template files. This file is for any additional JavaScript functionality.
        
        // Handle language direction changes
        const htmlElement = document.documentElement;
        const isRtl = htmlElement.getAttribute('dir') === 'rtl';
        
        // Add RTL class to body if needed
        if (isRtl) {
            document.body.classList.add('is-rtl');
        }
        
        // Handle language-specific adjustments
        const currentLanguage = htmlElement.getAttribute('data-language');
        if (currentLanguage) {
            document.body.classList.add(`lang-${currentLanguage}`);
            
            // Dispatch language change event for other scripts
            const event = new CustomEvent('languageChange', {
                detail: {
                    language: currentLanguage,
                    isRtl: isRtl
                }
            });
            document.dispatchEvent(event);
        }
    });
})(jQuery);