/**
 * Dark Mode Module
 * 
 * Manages theme switching between light, dark, and auto modes
 * Provides smooth transitions and accessibility support
 * 
 * @package AquaLuxe
 * @since 2.0.0
 */

/**
 * DarkMode Class
 * 
 * Manages theme switching between light, dark, and auto modes
 * Provides smooth transitions and accessibility support
 */
export class DarkMode {
    /**
     * Constructor
     * 
     * @param {Object} config Configuration options
     */
    constructor(config = {}) {
        this.config = {
            mode: 'auto', // 'light', 'dark', 'auto'
            storageKey: 'aqualuxe_dark_mode',
            enableTransitions: true,
            enableSystemPreference: true,
            debug: false,
            ...config
        };

        this.currentTheme = null;
        this.systemPreference = null;
        this.resolvedTheme = null;
        this.mediaQuery = null;
        this.eventBus = config.eventBus || null;

        this.init();
    }

    /**
     * Initialize dark mode functionality
     */
    init() {
        this.log('🌙 Dark Mode module initializing...');

        // Detect system preference
        this.detectSystemPreference();

        // Load saved preference
        this.loadSavedPreference();

        // Apply initial theme
        this.applyTheme(this.currentTheme);

        // Setup UI controls
        this.setupControls();

        // Setup event listeners
        this.setupEventListeners();

        this.log('✅ Dark Mode module initialized');
    }

    /**
     * Detect system preference for dark mode
     */
    detectSystemPreference() {
        if (!this.config.enableSystemPreference) return;
        
        // Create media query for dark mode preference
        this.mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        this.systemPreference = this.mediaQuery.matches ? 'dark' : 'light';
        
        // Listen for changes
        this.mediaQuery.addListener(this.handleSystemPreferenceChange.bind(this));
        
        if (this.config.debug) {
            console.log('🌙 System preference detected:', this.systemPreference);
        }
    }

    /**
     * Load saved preference from localStorage
     */
    loadSavedPreference() {
        try {
            const saved = localStorage.getItem(this.config.storageKey);
            if (saved && ['light', 'dark', 'auto'].includes(saved)) {
                this.currentTheme = saved;
            } else {
                this.currentTheme = this.config.mode;
            }
        } catch (error) {
            this.log('Could not access localStorage, using default theme');
            this.currentTheme = this.config.mode;
        }
    }

    /**
     * Save preference to localStorage
     * 
     * @param {string} theme Theme preference
     */
    savePreference(theme) {
        try {
            localStorage.setItem(this.config.storageKey, theme);
        } catch (error) {
            this.log('Could not save theme preference to localStorage');
        }
    }

    /**
     * Apply theme to document
     * 
     * @param {string} theme Theme to apply ('light', 'dark', 'auto')
     */
    applyTheme(theme) {
        if (!theme) return;

        this.currentTheme = theme;
        
        // Resolve actual theme (convert 'auto' to 'light' or 'dark')
        this.resolvedTheme = theme === 'auto' ? this.systemPreference : theme;
        
        // Apply theme class to document
        this.updateDocumentClass();
        
        // Update UI controls
        this.updateControls();
        
        // Save preference
        this.savePreference(theme);
        
        // Emit theme change event
        if (this.eventBus) {
            this.eventBus.emit('darkmode:changed', {
                theme: this.currentTheme,
                resolved: this.resolvedTheme,
                systemPreference: this.systemPreference
            });
        }

        if (this.config.debug) {
            console.log(`🌙 Theme applied: ${theme} (resolved: ${this.resolvedTheme})`);
        }
    }

    /**
     * Update document classes for theme
     */
    updateDocumentClass() {
        const html = document.documentElement;
        
        // Enable transitions temporarily if disabled
        if (this.config.enableTransitions) {
            html.classList.add('theme-transition');
        }
        
        // Remove existing theme classes
        html.classList.remove('light', 'dark');
        
        // Add new theme class
        html.classList.add(this.resolvedTheme);
        
        // Update data attribute for CSS
        html.setAttribute('data-theme', this.resolvedTheme);
        
        // Remove transition class after animation
        if (this.config.enableTransitions) {
            setTimeout(() => {
                html.classList.remove('theme-transition');
            }, 300);
        }
    }

    /**
     * Setup theme toggle controls
     */
    setupControls() {
        // Find toggle buttons
        this.toggleButtons = document.querySelectorAll('[data-theme-toggle]');
        this.themeSelects = document.querySelectorAll('[data-theme-select]');
        
        // Setup toggle buttons
        this.toggleButtons.forEach(button => {
            button.addEventListener('click', this.handleToggleClick.bind(this));
            button.setAttribute('aria-label', this.getToggleLabel());
        });
        
        // Setup theme selectors
        this.themeSelects.forEach(select => {
            select.addEventListener('change', this.handleSelectChange.bind(this));
        });
    }

    /**
     * Update control states
     */
    updateControls() {
        // Update toggle buttons
        this.toggleButtons.forEach(button => {
            button.setAttribute('aria-label', this.getToggleLabel());
            button.setAttribute('data-current-theme', this.resolvedTheme);
            
            // Update icon if present
            const icon = button.querySelector('[data-theme-icon]');
            if (icon) {
                this.updateToggleIcon(icon);
            }
        });
        
        // Update theme selectors
        this.themeSelects.forEach(select => {
            select.value = this.currentTheme;
        });
    }

    /**
     * Update toggle button icon
     * 
     * @param {HTMLElement} icon Icon element
     */
    updateToggleIcon(icon) {
        // Remove existing classes
        icon.classList.remove('icon-sun', 'icon-moon', 'icon-auto');
        
        // Add appropriate class
        switch (this.resolvedTheme) {
            case 'dark':
                icon.classList.add('icon-moon');
                break;
            case 'light':
                icon.classList.add('icon-sun');
                break;
            default:
                icon.classList.add('icon-auto');
        }
    }

    /**
     * Get toggle button label
     * 
     * @return {string} Aria label for toggle button
     */
    getToggleLabel() {
        switch (this.resolvedTheme) {
            case 'dark':
                return 'Switch to light mode';
            case 'light':
                return 'Switch to dark mode';
            default:
                return 'Toggle theme';
        }
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Listen for keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + Shift + D to toggle dark mode
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                this.toggle();
            }
        });
        
        // Listen for custom events
        if (this.eventBus) {
            this.eventBus.on('darkmode:toggle', () => this.toggle());
            this.eventBus.on('darkmode:set', (data) => this.setTheme(data.theme));
        }
    }

    /**
     * Handle toggle button click
     * 
     * @param {Event} e Click event
     */
    handleToggleClick(e) {
        e.preventDefault();
        
        // Simple toggle between light and dark
        let newTheme;
        
        if (this.currentTheme === 'auto') {
            // If auto, switch to opposite of current system preference
            newTheme = this.systemPreference === 'dark' ? 'light' : 'dark';
        } else {
            // Toggle between light and dark
            newTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
        }
        
        this.setTheme(newTheme);
        
        // Announce change to screen readers
        if (this.eventBus) {
            this.eventBus.emit('accessibility:announce', {
                message: `Switched to ${newTheme} mode`,
                priority: 'polite'
            });
        }
    }

    /**
     * Handle theme selector change
     * 
     * @param {Event} e Change event
     */
    handleSelectChange(e) {
        const theme = e.target.value;
        if (['light', 'dark', 'auto'].includes(theme)) {
            this.setTheme(theme);
        }
    }

    /**
     * Handle system preference changes
     * 
     * @param {MediaQueryListEvent} e Media query event
     */
    handleSystemPreferenceChange(e) {
        this.systemPreference = e.matches ? 'dark' : 'light';
        
        // If using auto mode, apply the new system preference
        if (this.currentTheme === 'auto') {
            this.applyTheme('auto');
        }
        
        // Emit system preference change event
        if (this.eventBus) {
            this.eventBus.emit('darkmode:system-preference-changed', {
                systemPreference: this.systemPreference,
                currentTheme: this.currentTheme,
                resolvedTheme: this.resolvedTheme
            });
        }
        
        if (this.config.debug) {
            console.log('🌙 System preference changed:', this.systemPreference);
        }
    }

    /**
     * Set theme
     * 
     * @param {string} theme Theme to set
     */
    setTheme(theme) {
        if (['light', 'dark', 'auto'].includes(theme)) {
            this.applyTheme(theme);
        }
    }

    /**
     * Toggle between light and dark
     */
    toggle() {
        const newTheme = this.resolvedTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }

    /**
     * Get current theme
     * 
     * @return {string} Current theme setting
     */
    getTheme() {
        return this.currentTheme;
    }

    /**
     * Get resolved theme (actual applied theme)
     * 
     * @return {string} Resolved theme
     */
    getResolvedTheme() {
        return this.resolvedTheme;
    }

    /**
     * Check if dark mode is active
     * 
     * @return {boolean} True if dark mode is active
     */
    isDark() {
        return this.resolvedTheme === 'dark';
    }

    /**
     * Check if light mode is active
     * 
     * @return {boolean} True if light mode is active
     */
    isLight() {
        return this.resolvedTheme === 'light';
    }

    /**
     * Logging helper
     * 
     * @param {string} message Log message
     */
    log(message) {
        if (this.config.debug) {
            console.log(`🌙 DarkMode: ${message}`);
        }
    }
}