/**
 * Dark Mode Module
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    const DarkMode = {
        init: function() {
            this.bindEvents();
            this.loadPreference();
            this.updateToggleState();
        },

        bindEvents: function() {
            $(document).on('click', '#dark-mode-toggle', this.toggle.bind(this));
            
            // Listen for system preference changes
            if (window.matchMedia) {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                mediaQuery.addListener(this.onSystemPreferenceChange.bind(this));
            }
        },

        toggle: function(e) {
            e.preventDefault();
            
            const $body = $('body');
            const $html = $('html');
            const isDark = $html.hasClass('dark');
            
            if (isDark) {
                this.disable();
            } else {
                this.enable();
            }
            
            this.savePreference(!isDark);
            this.updateToggleState();
        },

        enable: function() {
            $('html').addClass('dark');
            this.updateMetaThemeColor('#0f172a');
            $(document).trigger('aqualuxe:dark-mode-enabled');
        },

        disable: function() {
            $('html').removeClass('dark');
            this.updateMetaThemeColor('#ffffff');
            $(document).trigger('aqualuxe:dark-mode-disabled');
        },

        loadPreference: function() {
            let preference = localStorage.getItem('aqualuxe-dark-mode');
            
            // If no preference stored, check system preference
            if (preference === null) {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    preference = 'enabled';
                } else {
                    preference = 'disabled';
                }
            }
            
            if (preference === 'enabled') {
                this.enable();
            } else {
                this.disable();
            }
        },

        savePreference: function(enabled) {
            localStorage.setItem('aqualuxe-dark-mode', enabled ? 'enabled' : 'disabled');
            
            // Also send to server for logged-in users
            if (window.aqualuxe && window.aqualuxe.ajaxurl) {
                $.ajax({
                    url: window.aqualuxe.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_save_dark_mode_preference',
                        enabled: enabled,
                        nonce: window.aqualuxe.nonce
                    }
                });
            }
        },

        updateToggleState: function() {
            const $toggle = $('#dark-mode-toggle');
            const isDark = $('html').hasClass('dark');
            
            $toggle.attr('aria-pressed', isDark);
            
            // Update icon
            const lightIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>';
            const darkIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>';
            
            $toggle.find('svg path').attr('d', isDark ? lightIcon.match(/d="([^"]*)"/) ? lightIcon.match(/d="([^"]*)"/)[1] : '' : darkIcon.match(/d="([^"]*)"/) ? darkIcon.match(/d="([^"]*)"/)[1] : '');
        },

        updateMetaThemeColor: function(color) {
            let $meta = $('meta[name="theme-color"]');
            if ($meta.length === 0) {
                $meta = $('<meta name="theme-color">').appendTo('head');
            }
            $meta.attr('content', color);
        },

        onSystemPreferenceChange: function(e) {
            // Only auto-switch if user hasn't manually set a preference
            const userPreference = localStorage.getItem('aqualuxe-dark-mode');
            if (userPreference === null) {
                if (e.matches) {
                    this.enable();
                } else {
                    this.disable();
                }
                this.updateToggleState();
            }
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        DarkMode.init();
    });

    // Make globally available
    window.AquaLuxeDarkMode = DarkMode;

})(jQuery);