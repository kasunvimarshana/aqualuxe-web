// Dark Mode functionality
(function($) {
    'use strict';
    
    const DarkMode = {
        
        // Initialize dark mode
        init: function() {
            this.bindEvents();
            this.setInitialMode();
        },
        
        // Bind events
        bindEvents: function() {
            $(document).on('click', '#dark-mode-toggle button', this.toggle.bind(this));
        },
        
        // Set initial mode based on preference or system
        setInitialMode: function() {
            let mode = this.getStoredMode();
            
            // If no stored preference, check system preference
            if (!mode) {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    mode = 'dark';
                } else {
                    mode = 'light';
                }
            }
            
            this.setMode(mode);
        },
        
        // Get stored mode from cookie
        getStoredMode: function() {
            const cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                const [name, value] = cookie.trim().split('=');
                if (name === 'aqualuxe_dark_mode') {
                    return value;
                }
            }
            return null;
        },
        
        // Toggle dark mode
        toggle: function(e) {
            e.preventDefault();
            
            const currentMode = $('html').hasClass('dark') ? 'dark' : 'light';
            const newMode = currentMode === 'dark' ? 'light' : 'dark';
            
            this.setMode(newMode);
            this.savePreference(newMode);
            
            // Show toast notification
            this.showToast(newMode === 'dark' ? 'Dark mode enabled' : 'Light mode enabled');
        },
        
        // Set mode
        setMode: function(mode) {
            const $html = $('html');
            const $body = $('body');
            
            if (mode === 'dark') {
                $html.addClass('dark');
                $body.addClass('dark');
            } else {
                $html.removeClass('dark');
                $body.removeClass('dark');
            }
            
            // Update toggle button aria-label
            const toggleButton = $('#dark-mode-toggle button');
            const newLabel = mode === 'dark' ? 'Switch to light mode' : 'Switch to dark mode';
            toggleButton.attr('aria-label', newLabel).attr('title', newLabel);
            
            // Trigger custom event
            $(document).trigger('aqualuxe:dark-mode-changed', [mode]);
        },
        
        // Save preference via AJAX
        savePreference: function(mode) {
            if (typeof aqualuxe_dark_mode === 'undefined') return;
            
            $.ajax({
                url: aqualuxe_dark_mode.ajax_url,
                type: 'POST',
                data: {
                    action: 'toggle_dark_mode',
                    mode: mode,
                    nonce: aqualuxe_dark_mode.nonce
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Dark mode preference saved:', mode);
                    }
                },
                error: function() {
                    console.log('Failed to save dark mode preference');
                }
            });
        },
        
        // Show toast notification
        showToast: function(message) {
            // Remove existing toast
            $('.dark-mode-toast').remove();
            
            const toast = $(`
                <div class="dark-mode-toast fixed bottom-20 right-6 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 px-4 py-2 rounded-lg shadow-lg z-50 opacity-0 transform translate-y-2 transition-all duration-300">
                    ${message}
                </div>
            `);
            
            $('body').append(toast);
            
            // Animate in
            setTimeout(() => {
                toast.addClass('opacity-100').removeClass('translate-y-2');
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.removeClass('opacity-100').addClass('translate-y-2');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        DarkMode.init();
    });
    
    // Handle system preference changes
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            // Only auto-switch if user hasn't set a preference
            if (!DarkMode.getStoredMode()) {
                DarkMode.setMode(e.matches ? 'dark' : 'light');
            }
        });
    }
    
})(jQuery);