/**
 * AquaLuxe Multilingual Module Scripts
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Language Switcher
     */
    var LanguageSwitcher = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initAccessibility();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Dropdown language switcher
            $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__current').on('click', this.toggleDropdown);
            
            // Close dropdown on click outside
            $(document).on('click', this.closeDropdowns);
            
            // Close dropdown on ESC key
            $(document).on('keyup', this.handleEscKey);
        },
        
        /**
         * Initialize accessibility
         */
        initAccessibility: function() {
            // Add ARIA attributes to dropdown
            $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__current').attr({
                'aria-haspopup': 'true',
                'aria-expanded': 'false',
                'role': 'button',
                'tabindex': '0'
            });
            
            // Add ARIA attributes to dropdown list
            $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__list').attr({
                'role': 'menu',
                'aria-hidden': 'true'
            });
            
            // Add ARIA attributes to dropdown items
            $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__item').attr('role', 'none');
            $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__link').attr('role', 'menuitem');
            
            // Add keyboard navigation
            $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__current').on('keydown', this.handleDropdownKeydown);
            $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__link').on('keydown', this.handleItemKeydown);
        },
        
        /**
         * Toggle dropdown
         *
         * @param {Event} e Event
         */
        toggleDropdown: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $this = $(this);
            var $dropdown = $this.closest('.aqualuxe-language-switcher--dropdown');
            var $list = $dropdown.find('.aqualuxe-language-switcher__list');
            var isExpanded = $this.attr('aria-expanded') === 'true';
            
            // Close all other dropdowns
            $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__current').not($this).attr('aria-expanded', 'false');
            $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__list').not($list).attr('aria-hidden', 'true');
            
            // Toggle current dropdown
            $this.attr('aria-expanded', !isExpanded);
            $list.attr('aria-hidden', isExpanded);
            
            // Toggle active class
            $dropdown.toggleClass('is-active');
            
            // Focus first item if opening
            if (!isExpanded) {
                setTimeout(function() {
                    $list.find('.aqualuxe-language-switcher__link').first().focus();
                }, 100);
            }
        },
        
        /**
         * Close all dropdowns
         *
         * @param {Event} e Event
         */
        closeDropdowns: function(e) {
            if (!$(e.target).closest('.aqualuxe-language-switcher--dropdown').length) {
                $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__current').attr('aria-expanded', 'false');
                $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__list').attr('aria-hidden', 'true');
                $('.aqualuxe-language-switcher--dropdown').removeClass('is-active');
            }
        },
        
        /**
         * Handle ESC key
         *
         * @param {Event} e Event
         */
        handleEscKey: function(e) {
            if (e.keyCode === 27) {
                $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__current').attr('aria-expanded', 'false');
                $('.aqualuxe-language-switcher--dropdown .aqualuxe-language-switcher__list').attr('aria-hidden', 'true');
                $('.aqualuxe-language-switcher--dropdown').removeClass('is-active');
                
                // Focus the current button
                var $activeDropdown = $('.aqualuxe-language-switcher--dropdown.is-active');
                if ($activeDropdown.length) {
                    $activeDropdown.find('.aqualuxe-language-switcher__current').focus();
                }
            }
        },
        
        /**
         * Handle dropdown keydown
         *
         * @param {Event} e Event
         */
        handleDropdownKeydown: function(e) {
            var $this = $(this);
            var $dropdown = $this.closest('.aqualuxe-language-switcher--dropdown');
            var $list = $dropdown.find('.aqualuxe-language-switcher__list');
            var isExpanded = $this.attr('aria-expanded') === 'true';
            
            // Enter or Space
            if (e.keyCode === 13 || e.keyCode === 32) {
                e.preventDefault();
                LanguageSwitcher.toggleDropdown.call(this, e);
            }
            
            // Down Arrow
            if (e.keyCode === 40) {
                e.preventDefault();
                
                if (!isExpanded) {
                    LanguageSwitcher.toggleDropdown.call(this, e);
                } else {
                    $list.find('.aqualuxe-language-switcher__link').first().focus();
                }
            }
        },
        
        /**
         * Handle item keydown
         *
         * @param {Event} e Event
         */
        handleItemKeydown: function(e) {
            var $this = $(this);
            var $dropdown = $this.closest('.aqualuxe-language-switcher--dropdown');
            var $items = $dropdown.find('.aqualuxe-language-switcher__link');
            var $current = $dropdown.find('.aqualuxe-language-switcher__current');
            var index = $items.index($this);
            
            // Enter or Space
            if (e.keyCode === 13 || e.keyCode === 32) {
                e.preventDefault();
                window.location.href = $this.attr('href');
            }
            
            // Up Arrow
            if (e.keyCode === 38) {
                e.preventDefault();
                
                if (index > 0) {
                    $items.eq(index - 1).focus();
                } else {
                    $current.focus();
                    $current.attr('aria-expanded', 'false');
                    $dropdown.find('.aqualuxe-language-switcher__list').attr('aria-hidden', 'true');
                    $dropdown.removeClass('is-active');
                }
            }
            
            // Down Arrow
            if (e.keyCode === 40) {
                e.preventDefault();
                
                if (index < $items.length - 1) {
                    $items.eq(index + 1).focus();
                }
            }
            
            // Tab
            if (e.keyCode === 9 && !e.shiftKey) {
                if (index === $items.length - 1) {
                    $current.attr('aria-expanded', 'false');
                    $dropdown.find('.aqualuxe-language-switcher__list').attr('aria-hidden', 'true');
                    $dropdown.removeClass('is-active');
                }
            }
            
            // Shift + Tab
            if (e.keyCode === 9 && e.shiftKey) {
                if (index === 0) {
                    $current.attr('aria-expanded', 'false');
                    $dropdown.find('.aqualuxe-language-switcher__list').attr('aria-hidden', 'true');
                    $dropdown.removeClass('is-active');
                }
            }
        }
    };
    
    /**
     * Cookie Helper
     */
    var CookieHelper = {
        /**
         * Set cookie
         *
         * @param {string} name Cookie name
         * @param {string} value Cookie value
         * @param {number} days Expiration days
         */
        setCookie: function(name, value, days) {
            var expires = '';
            
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + value + expires + '; path=/';
        },
        
        /**
         * Get cookie
         *
         * @param {string} name Cookie name
         * @return {string|null}
         */
        getCookie: function(name) {
            var nameEQ = name + '=';
            var ca = document.cookie.split(';');
            
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            
            return null;
        },
        
        /**
         * Erase cookie
         *
         * @param {string} name Cookie name
         */
        eraseCookie: function(name) {
            this.setCookie(name, '', -1);
        }
    };
    
    /**
     * Language Redirect
     */
    var LanguageRedirect = {
        /**
         * Initialize
         */
        init: function() {
            this.maybeRedirect();
        },
        
        /**
         * Maybe redirect to preferred language
         */
        maybeRedirect: function() {
            // Check if we should redirect
            if (!aqualuxeMultilingual.autoRedirect) {
                return;
            }
            
            // Check if user has already selected a language
            var selectedLanguage = CookieHelper.getCookie('aqualuxe_language');
            if (selectedLanguage) {
                return;
            }
            
            // Get browser language
            var browserLanguage = this.getBrowserLanguage();
            
            // Check if browser language is available
            if (browserLanguage && aqualuxeMultilingual.languages[browserLanguage]) {
                // Check if current language is different from browser language
                if (browserLanguage !== aqualuxeMultilingual.currentLang) {
                    // Redirect to browser language
                    window.location.href = aqualuxeMultilingual.languages[browserLanguage].url;
                }
            }
        },
        
        /**
         * Get browser language
         *
         * @return {string|null}
         */
        getBrowserLanguage: function() {
            var language = navigator.language || navigator.userLanguage;
            
            if (language) {
                // Get language code (e.g. 'en' from 'en-US')
                language = language.split('-')[0];
                
                // Check if language is available
                for (var code in aqualuxeMultilingual.languages) {
                    if (code === language) {
                        return code;
                    }
                }
            }
            
            return null;
        }
    };
    
    /**
     * Language Persistence
     */
    var LanguagePersistence = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.saveCurrentLanguage();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Save language preference when clicking on language link
            $('.aqualuxe-language-switcher__link').on('click', this.handleLanguageClick);
        },
        
        /**
         * Save current language
         */
        saveCurrentLanguage: function() {
            CookieHelper.setCookie('aqualuxe_language', aqualuxeMultilingual.currentLang, 30);
        },
        
        /**
         * Handle language click
         *
         * @param {Event} e Event
         */
        handleLanguageClick: function(e) {
            var $this = $(this);
            var href = $this.attr('href');
            var language = '';
            
            // Try to extract language from URL
            for (var code in aqualuxeMultilingual.languages) {
                if (aqualuxeMultilingual.languages[code].url === href) {
                    language = code;
                    break;
                }
            }
            
            // Save language preference
            if (language) {
                CookieHelper.setCookie('aqualuxe_language', language, 30);
            }
        }
    };
    
    /**
     * Document ready
     */
    $(document).ready(function() {
        LanguageSwitcher.init();
        LanguageRedirect.init();
        LanguagePersistence.init();
    });
    
})(jQuery);