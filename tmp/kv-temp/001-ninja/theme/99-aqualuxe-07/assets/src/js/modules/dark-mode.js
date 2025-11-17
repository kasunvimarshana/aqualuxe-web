/**
 * Dark Mode Module JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

class DarkModeToggle {
    constructor() {
        this.storageKey = 'aqualuxe-dark-mode';
        this.init();
    }

    init() {
        this.createToggleButton();
        this.bindEvents();
        this.applyStoredPreference();
    }

    createToggleButton() {
        // Check if toggle already exists
        if (document.querySelector('.dark-mode-toggle')) return;

        const toggle = document.createElement('button');
        toggle.className = 'dark-mode-toggle fixed bottom-4 right-4 z-40 p-3 bg-white dark:bg-gray-800 rounded-full shadow-lg hover:shadow-xl transition-all duration-300';
        toggle.setAttribute('aria-label', 'Toggle dark mode');
        toggle.innerHTML = `
            <svg class="w-6 h-6 text-gray-800 dark:text-yellow-400 dark-mode-icon-sun hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
            <svg class="w-6 h-6 text-gray-800 dark:text-blue-400 dark-mode-icon-moon block dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        `;

        document.body.appendChild(toggle);
    }

    bindEvents() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.dark-mode-toggle')) {
                this.toggle();
            }
        });

        // Listen for system preference changes
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            mediaQuery.addEventListener('change', () => {
                if (!this.hasStoredPreference()) {
                    this.applySystemPreference();
                }
            });
        }

        // Listen for keyboard shortcut (Ctrl/Cmd + Shift + D)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                this.toggle();
            }
        });
    }

    toggle() {
        const isDark = document.documentElement.classList.contains('dark');
        
        if (isDark) {
            this.disable();
        } else {
            this.enable();
        }

        // Animate the transition
        this.animateTransition();
    }

    enable() {
        document.documentElement.classList.add('dark');
        localStorage.setItem(this.storageKey, 'dark');
        
        // Trigger custom event
        document.dispatchEvent(new CustomEvent('darkModeEnabled'));
        
        // Update meta theme color for mobile browsers
        this.updateThemeColor('#1f2937');
    }

    disable() {
        document.documentElement.classList.remove('dark');
        localStorage.setItem(this.storageKey, 'light');
        
        // Trigger custom event
        document.dispatchEvent(new CustomEvent('darkModeDisabled'));
        
        // Update meta theme color for mobile browsers
        this.updateThemeColor('#ffffff');
    }

    applyStoredPreference() {
        const stored = localStorage.getItem(this.storageKey);
        
        if (stored === 'dark') {
            this.enable();
        } else if (stored === 'light') {
            this.disable();
        } else {
            // No stored preference, use system preference
            this.applySystemPreference();
        }
    }

    applySystemPreference() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
            this.updateThemeColor('#1f2937');
        } else {
            document.documentElement.classList.remove('dark');
            this.updateThemeColor('#ffffff');
        }
    }

    hasStoredPreference() {
        return localStorage.getItem(this.storageKey) !== null;
    }

    updateThemeColor(color) {
        let themeColorMeta = document.querySelector('meta[name="theme-color"]');
        
        if (!themeColorMeta) {
            themeColorMeta = document.createElement('meta');
            themeColorMeta.name = 'theme-color';
            document.head.appendChild(themeColorMeta);
        }
        
        themeColorMeta.content = color;
    }

    animateTransition() {
        // Add a smooth transition effect
        document.documentElement.style.transition = 'background-color 0.3s ease, color 0.3s ease';
        
        setTimeout(() => {
            document.documentElement.style.transition = '';
        }, 300);
    }

    // Public API methods
    isDarkMode() {
        return document.documentElement.classList.contains('dark');
    }

    getStoredPreference() {
        return localStorage.getItem(this.storageKey);
    }

    clearStoredPreference() {
        localStorage.removeItem(this.storageKey);
        this.applySystemPreference();
    }
}

// Initialize dark mode
document.addEventListener('DOMContentLoaded', () => {
    window.darkModeToggle = new DarkModeToggle();
});

// Export for global access
window.DarkModeToggle = DarkModeToggle;