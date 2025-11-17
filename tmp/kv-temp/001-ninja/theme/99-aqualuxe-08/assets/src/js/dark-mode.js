// Dark mode toggle functionality
(function() {
    'use strict';
    
    const DarkMode = {
        init: function() {
            this.setInitialMode();
            this.bindEvents();
        },
        
        setInitialMode: function() {
            const savedMode = localStorage.getItem('aqualuxe_dark_mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            const mode = savedMode || (prefersDark ? 'dark' : 'light');
            this.setMode(mode);
        },
        
        bindEvents: function() {
            // Listen for system preference changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('aqualuxe_dark_mode')) {
                    this.setMode(e.matches ? 'dark' : 'light');
                }
            });
        },
        
        setMode: function(mode) {
            const html = document.documentElement;
            const body = document.body;
            
            if (mode === 'dark') {
                html.classList.add('dark');
                body.classList.add('dark');
            } else {
                html.classList.remove('dark');
                body.classList.remove('dark');
            }
            
            localStorage.setItem('aqualuxe_dark_mode', mode);
        },
        
        toggle: function() {
            const isDark = document.documentElement.classList.contains('dark');
            this.setMode(isDark ? 'light' : 'dark');
        }
    };
    
    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => DarkMode.init());
    } else {
        DarkMode.init();
    }
    
    // Expose toggle method globally
    window.AquaLuxeDarkMode = DarkMode;
})();