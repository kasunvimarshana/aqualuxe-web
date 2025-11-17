/**
 * Dark Mode Module
 * Handles dark mode toggle functionality with persistent preferences
 */

class DarkMode {
    constructor() {
        this.storageKey = 'aqualuxe-theme';
        this.init();
    }
    
    init() {
        this.setInitialTheme();
        this.initToggle();
        this.watchSystemPreference();
    }
    
    /**
     * Set initial theme based on stored preference or system preference
     */
    setInitialTheme() {
        const stored = localStorage.getItem(this.storageKey);
        
        if (stored) {
            this.setTheme(stored);
        } else {
            // Check system preference
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.setTheme(prefersDark ? 'dark' : 'light');
        }
    }
    
    /**
     * Initialize toggle functionality
     */
    initToggle() {
        const toggleButtons = document.querySelectorAll('[data-dark-mode-toggle]');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggle();
            });
            
            // Keyboard support
            button.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggle();
                }
            });
        });
    }
    
    /**
     * Watch system preference changes
     */
    watchSystemPreference() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        mediaQuery.addEventListener('change', (e) => {
            // Only auto-switch if user hasn't set a preference
            if (!localStorage.getItem(this.storageKey)) {
                this.setTheme(e.matches ? 'dark' : 'light');
            }
        });
    }
    
    /**
     * Toggle between light and dark themes
     */
    toggle() {
        const current = this.getCurrentTheme();
        const newTheme = current === 'dark' ? 'light' : 'dark';
        
        this.setTheme(newTheme);
        this.savePreference(newTheme);
        
        // Announce change to screen readers
        this.announceThemeChange(newTheme);
    }
    
    /**
     * Set theme
     */
    setTheme(theme) {
        const root = document.documentElement;
        const body = document.body;
        
        if (theme === 'dark') {
            root.classList.add('dark');
            body.setAttribute('data-theme', 'dark');
        } else {
            root.classList.remove('dark');
            body.setAttribute('data-theme', 'light');
        }
        
        // Update toggle button states
        this.updateToggleButtons(theme);
        
        // Emit custom event
        const event = new CustomEvent('theme-changed', {
            detail: { theme },
            bubbles: true
        });
        document.dispatchEvent(event);
    }
    
    /**
     * Get current theme
     */
    getCurrentTheme() {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    }
    
    /**
     * Save theme preference to localStorage
     */
    savePreference(theme) {
        try {
            localStorage.setItem(this.storageKey, theme);
        } catch (error) {
            console.warn('Could not save theme preference:', error);
        }
    }
    
    /**
     * Update toggle button states
     */
    updateToggleButtons(theme) {
        const toggleButtons = document.querySelectorAll('[data-dark-mode-toggle]');
        
        toggleButtons.forEach(button => {
            // Update aria-label
            const label = theme === 'dark' 
                ? 'Switch to light mode' 
                : 'Switch to dark mode';
            button.setAttribute('aria-label', label);
            
            // Update icon if present
            const sunIcon = button.querySelector('.icon-sun');
            const moonIcon = button.querySelector('.icon-moon');
            
            if (sunIcon && moonIcon) {
                if (theme === 'dark') {
                    sunIcon.style.display = 'block';
                    moonIcon.style.display = 'none';
                } else {
                    sunIcon.style.display = 'none';
                    moonIcon.style.display = 'block';
                }
            }
            
            // Update button state
            button.classList.toggle('is-dark', theme === 'dark');
        });
    }
    
    /**
     * Announce theme change to screen readers
     */
    announceThemeChange(theme) {
        const message = `Switched to ${theme} mode`;
        
        // Create or update live region
        let liveRegion = document.getElementById('theme-announcer');
        if (!liveRegion) {
            liveRegion = document.createElement('div');
            liveRegion.id = 'theme-announcer';
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'sr-only';
            document.body.appendChild(liveRegion);
        }
        
        liveRegion.textContent = message;
        
        // Clear after announcement
        setTimeout(() => {
            liveRegion.textContent = '';
        }, 1000);
    }
    
    /**
     * Get system preference
     */
    getSystemPreference() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    
    /**
     * Reset to system preference
     */
    resetToSystem() {
        localStorage.removeItem(this.storageKey);
        const systemTheme = this.getSystemPreference();
        this.setTheme(systemTheme);
        this.announceThemeChange(systemTheme);
    }
}

export default DarkMode;