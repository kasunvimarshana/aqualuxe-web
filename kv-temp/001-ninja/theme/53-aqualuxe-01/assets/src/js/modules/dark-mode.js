/**
 * Dark Mode Module
 * 
 * Handles the dark mode toggle functionality, persists user preferences,
 * and applies the appropriate theme based on user preference or system settings.
 */

class DarkMode {
    constructor() {
        this.darkModeToggle = document.querySelector('.dark-mode-toggle');
        this.darkModeClass = 'dark-mode';
        this.storageKey = 'aqualuxe_dark_mode';
        this.isDarkMode = false;
    }

    init() {
        // Skip if toggle doesn't exist
        if (!this.darkModeToggle) {
            return;
        }

        this.setupInitialState();
        this.setupEventListeners();
        this.setupSystemPreferenceListener();
    }

    setupInitialState() {
        // Check for saved preference
        const savedPreference = localStorage.getItem(this.storageKey);
        
        if (savedPreference !== null) {
            // Use saved preference
            this.isDarkMode = savedPreference === 'true';
        } else {
            // Check system preference
            this.isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        }

        // Apply initial state
        this.applyTheme();
    }

    setupEventListeners() {
        // Toggle dark mode on click
        this.darkModeToggle.addEventListener('click', () => {
            this.isDarkMode = !this.isDarkMode;
            this.applyTheme();
            this.savePreference();
        });
    }

    setupSystemPreferenceListener() {
        // Listen for system preference changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            // Only apply if user hasn't set a preference
            if (localStorage.getItem(this.storageKey) === null) {
                this.isDarkMode = e.matches;
                this.applyTheme();
            }
        });
    }

    applyTheme() {
        if (this.isDarkMode) {
            document.documentElement.classList.add(this.darkModeClass);
            this.darkModeToggle.setAttribute('aria-pressed', 'true');
            this.darkModeToggle.setAttribute('aria-label', 'Switch to light mode');
        } else {
            document.documentElement.classList.remove(this.darkModeClass);
            this.darkModeToggle.setAttribute('aria-pressed', 'false');
            this.darkModeToggle.setAttribute('aria-label', 'Switch to dark mode');
        }

        // Update toggle icon if it exists
        const toggleIcon = this.darkModeToggle.querySelector('i, svg, img');
        if (toggleIcon) {
            if (this.isDarkMode) {
                toggleIcon.classList.remove('icon-moon');
                toggleIcon.classList.add('icon-sun');
            } else {
                toggleIcon.classList.remove('icon-sun');
                toggleIcon.classList.add('icon-moon');
            }
        }

        // Dispatch event for other components to react
        document.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { darkMode: this.isDarkMode }
        }));
    }

    savePreference() {
        localStorage.setItem(this.storageKey, this.isDarkMode.toString());
    }
}

export default DarkMode;