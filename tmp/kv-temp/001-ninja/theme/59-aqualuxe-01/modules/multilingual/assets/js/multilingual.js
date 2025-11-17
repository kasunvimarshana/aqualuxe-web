/**
 * Multilingual Module
 * 
 * Handles language switcher functionality.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Language switcher dropdown
    const languageSwitchers = document.querySelectorAll('.aqualuxe-language-switcher--dropdown');
    
    languageSwitchers.forEach(function(switcher) {
        const toggle = switcher.querySelector('.aqualuxe-language-switcher__toggle');
        const dropdown = switcher.querySelector('.aqualuxe-language-switcher__list');
        
        if (toggle && dropdown) {
            // Toggle dropdown
            toggle.addEventListener('click', function() {
                const expanded = toggle.getAttribute('aria-expanded') === 'true';
                
                toggle.setAttribute('aria-expanded', !expanded);
                dropdown.hidden = expanded;
                
                // Close dropdown when clicking outside
                if (!expanded) {
                    document.addEventListener('click', closeDropdown);
                }
            });
            
            // Close dropdown function
            function closeDropdown(event) {
                if (!switcher.contains(event.target)) {
                    toggle.setAttribute('aria-expanded', 'false');
                    dropdown.hidden = true;
                    document.removeEventListener('click', closeDropdown);
                }
            }
            
            // Close dropdown on escape key
            dropdown.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    toggle.setAttribute('aria-expanded', 'false');
                    dropdown.hidden = true;
                    toggle.focus();
                }
            });
            
            // Keyboard navigation
            dropdown.addEventListener('keydown', function(event) {
                const items = dropdown.querySelectorAll('.aqualuxe-language-switcher__item');
                const currentIndex = Array.from(items).indexOf(document.activeElement);
                
                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    if (currentIndex < items.length - 1) {
                        items[currentIndex + 1].focus();
                    } else {
                        items[0].focus();
                    }
                } else if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    if (currentIndex > 0) {
                        items[currentIndex - 1].focus();
                    } else {
                        items[items.length - 1].focus();
                    }
                } else if (event.key === 'Home') {
                    event.preventDefault();
                    items[0].focus();
                } else if (event.key === 'End') {
                    event.preventDefault();
                    items[items.length - 1].focus();
                }
            });
        }
    });
    
    // Add language to body class
    const html = document.documentElement;
    const language = html.getAttribute('data-language');
    
    if (language) {
        document.body.classList.add('language-' + language);
    }
    
    // Handle RTL languages
    const isRTL = document.dir === 'rtl';
    if (isRTL) {
        document.body.classList.add('rtl');
    }
    
    // Handle language-specific styles
    const currentLanguage = document.documentElement.lang;
    if (currentLanguage) {
        document.body.classList.add('lang-' + currentLanguage);
    }
    
    // Handle language change events
    const languageLinks = document.querySelectorAll('.aqualuxe-language-switcher__item a, .aqualuxe-footer-language-switcher__item a');
    
    languageLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            // Add loading class to body
            document.body.classList.add('language-switching');
            
            // Store scroll position
            const scrollPosition = window.scrollY;
            sessionStorage.setItem('aqualuxe_scroll_position', scrollPosition);
        });
    });
    
    // Restore scroll position after language change
    if (sessionStorage.getItem('aqualuxe_scroll_position')) {
        const scrollPosition = parseInt(sessionStorage.getItem('aqualuxe_scroll_position'), 10);
        window.scrollTo(0, scrollPosition);
        sessionStorage.removeItem('aqualuxe_scroll_position');
    }
    
    // Handle language-specific content
    const languageElements = document.querySelectorAll('[data-language]');
    
    languageElements.forEach(function(element) {
        const elementLanguage = element.getAttribute('data-language');
        
        if (elementLanguage && elementLanguage !== language) {
            element.style.display = 'none';
        }
    });
});

/**
 * Helper function to get URL with language parameter
 * 
 * @param {string} url - The URL to modify
 * @param {string} language - The language code
 * @return {string} - The modified URL
 */
function aqualuxeGetUrlWithLanguage(url, language) {
    // Check if URL already has query parameters
    const hasQuery = url.indexOf('?') !== -1;
    
    // Add language parameter
    if (hasQuery) {
        // Check if URL already has language parameter
        if (url.indexOf('lang=') !== -1) {
            // Replace language parameter
            return url.replace(/lang=[^&]+/, 'lang=' + language);
        } else {
            // Add language parameter
            return url + '&lang=' + language;
        }
    } else {
        // Add language parameter
        return url + '?lang=' + language;
    }
}