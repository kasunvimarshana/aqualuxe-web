/**
 * Dark Mode JavaScript Module
 * 
 * Handles dark mode toggle functionality with persistent preference.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Dark Mode object
    const DarkMode = {
        
        /**
         * Initialize dark mode
         */
        init() {
            this.bindEvents();
            this.updateToggleState();
            this.handleSystemPreferenceChange();
        },
        
        /**
         * Bind events
         */
        bindEvents() {
            // Toggle button click
            $(document).on('click', '[data-toggle="dark-mode"], .dark-mode-toggle', this.toggle.bind(this));
            
            // Keyboard shortcut (Ctrl/Cmd + Shift + D)
            $(document).on('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                    e.preventDefault();
                    DarkMode.toggle();
                }
            });
        },
        
        /**
         * Toggle dark mode
         */
        toggle(e) {
            if (e) {
                e.preventDefault();
            }
            
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            const newMode = isDark ? 'light' : 'dark';
            
            // Update DOM
            html.classList.toggle('dark', !isDark);
            html.style.colorScheme = newMode;
            
            // Save preference
            localStorage.setItem('aqualuxe-dark-mode', newMode);
            
            // Update toggle buttons
            this.updateToggleState();
            
            // Trigger custom event
            $(document).trigger('darkModeToggled', [newMode]);
            
            // Send to server (optional, for logged-in users)
            if (typeof aqualuxe_dark_mode !== 'undefined') {
                $.ajax({
                    url: aqualuxe_dark_mode.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'toggle_dark_mode',
                        mode: newMode,
                        nonce: aqualuxe_dark_mode.nonce
                    },
                    success: function(response) {
                        if (response.success && response.data.message) {
                            // Show success message (optional)
                            DarkMode.showMessage(response.data.message);
                        }
                    },
                    error: function() {
                        // Silently fail for non-critical functionality
                        console.log('Dark mode preference could not be saved to server');
                    }
                });
            }
            
            // Announce to screen readers
            this.announceChange(newMode);
        },
        
        /**
         * Update toggle button states
         */
        updateToggleState() {
            const isDark = document.documentElement.classList.contains('dark');
            const $toggles = $('.dark-mode-toggle, [data-toggle="dark-mode"]');
            
            $toggles.each(function() {
                const $toggle = $(this);
                const $lightIcon = $toggle.find('.dark-mode-icon-light');
                const $darkIcon = $toggle.find('.dark-mode-icon-dark');
                
                if (isDark) {
                    $lightIcon.addClass('hidden');
                    $darkIcon.removeClass('hidden');
                    $toggle.attr('aria-pressed', 'true');
                } else {
                    $lightIcon.removeClass('hidden');
                    $darkIcon.addClass('hidden');
                    $toggle.attr('aria-pressed', 'false');
                }
            });
        },
        
        /**
         * Handle system preference changes
         */
        handleSystemPreferenceChange() {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            mediaQuery.addEventListener('change', (e) => {
                // Only apply system preference if user hasn't set a manual preference
                const savedTheme = localStorage.getItem('aqualuxe-dark-mode');
                if (!savedTheme) {
                    const html = document.documentElement;
                    html.classList.toggle('dark', e.matches);
                    html.style.colorScheme = e.matches ? 'dark' : 'light';
                    this.updateToggleState();
                }
            });
        },
        
        /**
         * Get current mode
         */
        getCurrentMode() {
            return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        },
        
        /**
         * Set mode programmatically
         */
        setMode(mode) {
            if (mode !== 'light' && mode !== 'dark') {
                return false;
            }
            
            const html = document.documentElement;
            html.classList.toggle('dark', mode === 'dark');
            html.style.colorScheme = mode;
            
            // Save preference
            localStorage.setItem('aqualuxe-dark-mode', mode);
            
            // Update toggle buttons
            this.updateToggleState();
            
            // Trigger custom event
            $(document).trigger('darkModeChanged', [mode]);
            
            return true;
        },
        
        /**
         * Show message to user
         */
        showMessage(message) {
            // Create or update live region for announcements
            let $liveRegion = $('#dark-mode-announcements');
            if (!$liveRegion.length) {
                $liveRegion = $('<div>', {
                    id: 'dark-mode-announcements',
                    'aria-live': 'polite',
                    'aria-atomic': 'true',
                    class: 'screen-reader-text'
                }).appendTo('body');
            }
            
            $liveRegion.text(message);
        },
        
        /**
         * Announce mode change to screen readers
         */
        announceChange(mode) {
            const message = mode === 'dark' 
                ? 'Dark mode enabled' 
                : 'Light mode enabled';
            
            this.showMessage(message);
        },
        
        /**
         * Initialize on page load based on saved preference or system preference
         */
        initializeFromPreference() {
            const savedTheme = localStorage.getItem('aqualuxe-dark-mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            let shouldBeDark = false;
            
            if (savedTheme) {
                shouldBeDark = savedTheme === 'dark';
            } else if (prefersDark) {
                shouldBeDark = true;
            }
            
            const html = document.documentElement;
            html.classList.toggle('dark', shouldBeDark);
            html.style.colorScheme = shouldBeDark ? 'dark' : 'light';
            
            this.updateToggleState();
        }
    };
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        DarkMode.init();
        
        // Make sure state is correct after page load
        DarkMode.initializeFromPreference();
    });
    
    // Expose to global scope
    window.AquaLuxeDarkMode = DarkMode;
    
})(jQuery);