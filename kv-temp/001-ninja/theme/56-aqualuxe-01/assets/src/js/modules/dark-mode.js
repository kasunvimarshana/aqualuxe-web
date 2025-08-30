/**
 * Dark Mode Module
 * 
 * Handles dark mode functionality with persistent user preference
 */

const DarkMode = {
    /**
     * Initialize the dark mode module
     */
    init() {
        this.setupDarkMode();
        this.addEventListeners();
    },

    /**
     * Setup dark mode based on user preference
     */
    setupDarkMode() {
        // Check for saved user preference
        const darkModeEnabled = localStorage.getItem('darkMode') === 'true';
        
        // Check for system preference if no saved preference
        const prefersDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Apply dark mode if enabled or system prefers it
        if (darkModeEnabled || (prefersDarkMode && localStorage.getItem('darkMode') === null)) {
            document.body.classList.add('dark-mode');
            this.updateToggleButtons(true);
        } else {
            document.body.classList.remove('dark-mode');
            this.updateToggleButtons(false);
        }
    },

    /**
     * Add event listeners for dark mode toggle
     */
    addEventListeners() {
        // Listen for toggle button clicks
        const toggleButtons = document.querySelectorAll('.dark-mode-toggle');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.toggleDarkMode();
            });
        });
        
        // Listen for system preference changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                // Only change if user hasn't explicitly set a preference
                if (localStorage.getItem('darkMode') === null) {
                    if (e.matches) {
                        document.body.classList.add('dark-mode');
                        this.updateToggleButtons(true);
                    } else {
                        document.body.classList.remove('dark-mode');
                        this.updateToggleButtons(false);
                    }
                }
            });
        }
    },

    /**
     * Toggle dark mode on/off
     */
    toggleDarkMode() {
        if (document.body.classList.contains('dark-mode')) {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'false');
            this.updateToggleButtons(false);
        } else {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'true');
            this.updateToggleButtons(true);
        }
        
        // Dispatch event for other components to react
        document.dispatchEvent(new CustomEvent('darkModeToggled', {
            detail: { darkModeEnabled: document.body.classList.contains('dark-mode') }
        }));
    },

    /**
     * Update toggle button states
     * 
     * @param {boolean} isDarkMode Whether dark mode is enabled
     */
    updateToggleButtons(isDarkMode) {
        const toggleButtons = document.querySelectorAll('.dark-mode-toggle');
        
        toggleButtons.forEach(button => {
            const sunIcon = button.querySelector('.sun-icon');
            const moonIcon = button.querySelector('.moon-icon');
            
            if (sunIcon && moonIcon) {
                if (isDarkMode) {
                    sunIcon.style.display = 'block';
                    moonIcon.style.display = 'none';
                } else {
                    sunIcon.style.display = 'none';
                    moonIcon.style.display = 'block';
                }
            }
            
            // Update aria-pressed attribute for accessibility
            button.setAttribute('aria-pressed', isDarkMode ? 'true' : 'false');
        });
    }
};

export default DarkMode;