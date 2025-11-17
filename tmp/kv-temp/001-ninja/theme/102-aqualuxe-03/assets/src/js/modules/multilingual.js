/**
 * Multilingual JavaScript Module
 * 
 * Handles multilingual functionality and language switching.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Multilingual object
    const Multilingual = {
        
        /**
         * Initialize multilingual functionality
         */
        init() {
            this.bindEvents();
            this.initLanguageSwitcher();
            this.handleRTL();
        },
        
        /**
         * Bind events
         */
        bindEvents() {
            // Language switcher
            $(document).on('click', '.language-switcher a', this.handleLanguageSwitch.bind(this));
            
            // Direction toggle for RTL languages
            $(document).on('click', '.rtl-toggle', this.toggleRTL.bind(this));
        },
        
        /**
         * Initialize language switcher
         */
        initLanguageSwitcher() {
            const $switcher = $('.language-switcher');
            
            if ($switcher.length) {
                // Add accessibility attributes
                $switcher.attr('role', 'navigation').attr('aria-label', 'Language selector');
                
                // Mark current language
                const currentLang = document.documentElement.lang || 'en';
                $switcher.find(`[hreflang="${currentLang}"]`).addClass('current').attr('aria-current', 'page');
            }
        },
        
        /**
         * Handle language switch
         */
        handleLanguageSwitch(e) {
            const $link = $(e.currentTarget);
            const lang = $link.attr('hreflang');
            const url = $link.attr('href');
            
            // Store language preference
            localStorage.setItem('aqualuxe-language', lang);
            
            // Update page language attribute
            document.documentElement.lang = lang;
            
            // Announce change to screen readers
            this.announceLanguageChange(lang);
            
            // Allow normal navigation
            return true;
        },
        
        /**
         * Handle RTL languages
         */
        handleRTL() {
            const rtlLanguages = ['ar', 'he', 'fa', 'ur', 'ps', 'sd'];
            const currentLang = document.documentElement.lang || 'en';
            
            if (rtlLanguages.includes(currentLang.substring(0, 2))) {
                document.documentElement.dir = 'rtl';
                document.body.classList.add('rtl');
            } else {
                document.documentElement.dir = 'ltr';
                document.body.classList.remove('rtl');
            }
        },
        
        /**
         * Toggle RTL direction
         */
        toggleRTL(e) {
            e.preventDefault();
            
            const $btn = $(e.currentTarget);
            const isRTL = document.documentElement.dir === 'rtl';
            
            if (isRTL) {
                document.documentElement.dir = 'ltr';
                document.body.classList.remove('rtl');
                $btn.attr('aria-pressed', 'false');
            } else {
                document.documentElement.dir = 'rtl';
                document.body.classList.add('rtl');
                $btn.attr('aria-pressed', 'true');
            }
            
            // Store preference
            localStorage.setItem('aqualuxe-rtl', !isRTL);
        },
        
        /**
         * Announce language change to screen readers
         */
        announceLanguageChange(lang) {
            const langNames = {
                'en': 'English',
                'es': 'Español',
                'fr': 'Français',
                'de': 'Deutsch',
                'it': 'Italiano',
                'pt': 'Português',
                'ru': 'Русский',
                'zh': '中文',
                'ja': '日本語',
                'ko': '한국어',
                'ar': 'العربية',
                'he': 'עברית'
            };
            
            const langName = langNames[lang] || lang;
            const message = `Language changed to ${langName}`;
            
            // Create or update live region for announcements
            let $liveRegion = $('#language-announcements');
            if (!$liveRegion.length) {
                $liveRegion = $('<div>', {
                    id: 'language-announcements',
                    'aria-live': 'polite',
                    'aria-atomic': 'true',
                    class: 'screen-reader-text'
                }).appendTo('body');
            }
            
            $liveRegion.text(message);
        },
        
        /**
         * Get current language
         */
        getCurrentLanguage() {
            return document.documentElement.lang || 'en';
        },
        
        /**
         * Get saved language preference
         */
        getSavedLanguage() {
            return localStorage.getItem('aqualuxe-language');
        },
        
        /**
         * Check if current language is RTL
         */
        isRTL() {
            return document.documentElement.dir === 'rtl';
        }
    };
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        Multilingual.init();
    });
    
    // Expose to global scope
    window.AquaLuxeMultilingual = Multilingual;
    
})(jQuery);