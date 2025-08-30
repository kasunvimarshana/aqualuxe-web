/**
 * AquaLuxe Theme Dark Mode
 * 
 * Handles dark mode functionality
 */

// Dark Mode Controller
const AquaLuxeDarkMode = {
    init: function() {
        this.setupDarkMode();
    },

    setupDarkMode: function() {
        // Check for saved theme preference or respect OS preference
        const savedTheme = localStorage.getItem('aqualuxe-theme');
        const defaultTheme = document.documentElement.getAttribute('data-default-theme') || 'light';
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Set initial theme based on priority:
        // 1. User's saved preference
        // 2. Theme default setting from customizer
        // 3. OS preference
        if (savedTheme) {
            // User has a saved preference
            this.setTheme(savedTheme);
        } else if (defaultTheme === 'auto') {
            // Theme is set to follow OS preference
            this.setTheme(prefersDark ? 'dark' : 'light');
        } else {
            // Use theme default
            this.setTheme(defaultTheme);
        }
        
        // Listen for OS preference changes if in auto mode
        if (defaultTheme === 'auto' && !savedTheme) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                this.setTheme(e.matches ? 'dark' : 'light');
            });
        }
        
        // Setup toggle button
        this.setupToggleButton();
    },
    
    setTheme: function(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            
            // Update toggle buttons
            const toggles = document.querySelectorAll('.dark-mode-toggle');
            toggles.forEach(toggle => toggle.classList.add('active'));
            
            // Store preference
            localStorage.setItem('aqualuxe-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            
            // Update toggle buttons
            const toggles = document.querySelectorAll('.dark-mode-toggle');
            toggles.forEach(toggle => toggle.classList.remove('active'));
            
            // Store preference
            localStorage.setItem('aqualuxe-theme', 'light');
        }
        
        // Dispatch event for other scripts
        document.dispatchEvent(new CustomEvent('aqualuxeThemeChanged', { 
            detail: { theme: theme }
        }));
    },
    
    setupToggleButton: function() {
        const toggles = document.querySelectorAll('.dark-mode-toggle');
        
        toggles.forEach(toggle => {
            toggle.addEventListener('click', e => {
                e.preventDefault();
                
                const isDark = document.documentElement.classList.contains('dark');
                this.setTheme(isDark ? 'light' : 'dark');
            });
        });
    }
};

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    AquaLuxeDarkMode.init();
});