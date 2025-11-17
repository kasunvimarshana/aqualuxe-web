/**
 * AquaLuxe Dark Mode Module
 *
 * Theme switching functionality with user preference detection
 * Smooth transitions and proper accessibility support
 *
 * @package AquaLuxe
 * @since 1.2.0
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
     * @param {Object} config Dark mode configuration
     * @param {EventBus} eventBus Event bus instance
     */
    constructor(config = {}, eventBus = null) {
        this.config = {
            debug: false,
            enableTransitions: true,
            enableSystemPreference: true,
            enableLocalStorage: true,
            storageKey: 'aqualuxe-theme',
            transitionDuration: 300,
            themes: ['light', 'dark', 'auto'],
            defaultTheme: 'auto',
            ...config
        };
        
        this.eventBus = eventBus;
        this.isInitialized = false;
        
        // Current state
        this.currentTheme = null;
        this.systemPreference = null;
        this.resolvedTheme = null; // The actual theme being used (light/dark)
        
        // DOM elements
        this.toggleButton = null;
        this.themeSelector = null;
        
        // Media query for system preference
        this.mediaQuery = null;
        
        // Bound methods
        this.handleSystemPreferenceChange = this.handleSystemPreferenceChange.bind(this);
        this.handleToggleClick = this.handleToggleClick.bind(this);
        this.handleSelectorChange = this.handleSelectorChange.bind(this);
        
        this.init();
    }

    /**
     * Initialize dark mode functionality
     */
    async init() {
        if (this.isInitialized) {
            return;
        }
        
        try {
            // Detect system preference
            this.detectSystemPreference();
            
            // Load saved preference
            this.loadSavedPreference();
            
            // Set up UI elements
            this.setupToggleButton();
            this.setupThemeSelector();
            
            // Apply initial theme
            this.applyTheme(this.currentTheme);
            
            // Bind events
            this.bindEvents();
            
            this.isInitialized = true;
            
            // Emit initialization event
            if (this.eventBus) {
                this.eventBus.emit('darkmode:initialized', {
                    currentTheme: this.currentTheme,
                    resolvedTheme: this.resolvedTheme,
                    systemPreference: this.systemPreference
                });
            }
            
            if (this.config.debug) {
                console.log('🌙 Dark mode initialized:', {
                    currentTheme: this.currentTheme,
                    resolvedTheme: this.resolvedTheme,
                    systemPreference: this.systemPreference
                });
            }
            
        } catch (error) {
            console.error('❌ Dark mode initialization failed:', error);
        }
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
        this.mediaQuery.addListener(this.handleSystemPreferenceChange);
        
        if (this.config.debug) {
            console.log('🌙 System preference detected:', this.systemPreference);
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
     * Load saved preference from local storage
     */
    loadSavedPreference() {
        if (!this.config.enableLocalStorage) {
            this.currentTheme = this.config.defaultTheme;
            return;
        }
        
        try {
            const saved = localStorage.getItem(this.config.storageKey);
            
            if (saved && this.config.themes.includes(saved)) {
                this.currentTheme = saved;
            } else {
                this.currentTheme = this.config.defaultTheme;
            }
            
        } catch (error) {
            console.warn('🌙 Could not load saved theme preference:', error);
            this.currentTheme = this.config.defaultTheme;
        }
        
        if (this.config.debug) {
            console.log('🌙 Loaded theme preference:', this.currentTheme);
        }
    }

    /**
     * Save preference to local storage
     * 
     * @param {string} theme Theme to save
     */
    savePreference(theme) {
        if (!this.config.enableLocalStorage) return;
        
        try {
            localStorage.setItem(this.config.storageKey, theme);
            
            if (this.config.debug) {
                console.log('🌙 Saved theme preference:', theme);
            }
            
        } catch (error) {
            console.warn('🌙 Could not save theme preference:', error);
        }
    }

    /**
     * Set up toggle button
     */
    setupToggleButton() {
        // Find existing toggle button
        this.toggleButton = document.querySelector('.theme-toggle, [data-theme-toggle]');
        
        if (!this.toggleButton) {
            // Create toggle button if it doesn't exist
            this.createToggleButton();
        }
        
        if (this.toggleButton) {
            // Set initial state
            this.updateToggleButton();
            
            // Add click listener
            this.toggleButton.addEventListener('click', this.handleToggleClick);
        }
    }

    /**
     * Create toggle button
     */
    createToggleButton() {
        // Check if header navigation exists
        const nav = document.querySelector('.site-navigation, .main-navigation, nav');
        if (!nav) return;
        
        // Create button container
        const container = document.createElement('div');
        container.className = 'theme-toggle-container';
        
        // Create toggle button
        this.toggleButton = document.createElement('button');
        this.toggleButton.className = 'theme-toggle';
        this.toggleButton.setAttribute('type', 'button');
        this.toggleButton.setAttribute('aria-label', 'Toggle dark mode');
        this.toggleButton.setAttribute('data-theme-toggle', 'true');
        
        // Add icon
        this.toggleButton.innerHTML = `
            <span class="theme-toggle-icon sun-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 17q-2.075 0-3.537-1.463Q7 14.075 7 12t1.463-3.538Q9.925 7 12 7t3.538 1.462Q17 9.925 17 12q0 2.075-1.462 3.537Q14.075 17 12 17ZM2 13q-.425 0-.712-.288Q1 12.425 1 12t.288-.713Q1.575 11 2 11h2q.425 0 .713.287Q5 11.575 5 12t-.287.712Q4.425 13 4 13Zm18 0q-.425 0-.712-.288Q19 12.425 19 12t.288-.713Q19.575 11 20 11h2q.425 0 .712.287Q23 11.575 23 12t-.288.712Q22.425 13 22 13ZM12 5q-.425 0-.712-.288Q11 4.425 11 4V2q0-.425.288-.713Q11.575 1 12 1t.713.287Q13 1.575 13 2v2q0 .425-.287.712Q12.425 5 12 5Zm0 18q-.425 0-.712-.288Q11 22.425 11 22v-2q0-.425.288-.712Q11.575 19 12 19t.713.288Q13 19.575 13 20v2q0 .425-.287.712Q12.425 23 12 23ZM5.65 7.05 4.575 6q-.3-.275-.3-.7t.3-.725q.275-.3.7-.3t.725.3L7.05 5.65q.275.3.275.7t-.275.7q-.3.275-.7.275t-.7-.275Zm12.7 12.7L17.3 18.7q-.275-.3-.275-.7t.275-.7q.3-.275.7-.275t.7.275l1.075 1.05q.3.275.3.7t-.3.725q-.275.3-.7.3t-.725-.3ZM18.35 7.05q-.3.275-.7.275t-.7-.275q-.275-.3-.275-.7t.275-.7L18.05 4.6q.275-.3.7-.3t.725.3q.3.275.3.7t-.3.725ZM5.65 19.75q-.3.275-.7.275t-.7-.275q-.275-.3-.275-.725t.275-.7L5.3 17.3q.3-.275.7-.275t.7.275q.275.3.275.7t-.275.7Z"/>
                </svg>
            </span>
            <span class="theme-toggle-icon moon-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 21q-3.75 0-6.375-2.625T3 12q0-3.75 2.625-6.375T12 3q.35 0 .688.025t.662.075q-1.025.725-1.637 1.887Q11.1 6.15 11.1 7.5q0 2.25 1.575 3.825Q14.25 12.9 16.5 12.9q1.375 0 2.525-.613T20.9 10.65q.05.325.075.662Q21 11.65 21 12q0 3.75-2.625 6.375T12 21Z"/>
                </svg>
            </span>
            <span class="theme-toggle-text sr-only">
                <span class="light-mode-text">Switch to dark mode</span>
                <span class="dark-mode-text">Switch to light mode</span>
            </span>
        `;
        
        container.appendChild(this.toggleButton);
        
        // Add to navigation
        nav.appendChild(container);
    }

    /**
     * Set up theme selector dropdown
     */
    setupThemeSelector() {
        // Find existing theme selector
        this.themeSelector = document.querySelector('.theme-selector, [data-theme-selector]');
        
        if (!this.themeSelector) {
            // Create theme selector if requested
            this.createThemeSelector();
        }
        
        if (this.themeSelector) {
            // Set initial value
            this.updateThemeSelector();
            
            // Add change listener
            this.themeSelector.addEventListener('change', this.handleSelectorChange);
        }
    }

    /**
     * Create theme selector
     */
    createThemeSelector() {
        // Only create if there's a settings area or form
        const settingsArea = document.querySelector('.theme-settings, .user-preferences, .settings-panel');
        if (!settingsArea) return;
        
        // Create selector container
        const container = document.createElement('div');
        container.className = 'theme-selector-container';
        
        // Create label
        const label = document.createElement('label');
        label.className = 'theme-selector-label';
        label.textContent = 'Theme:';
        label.setAttribute('for', 'theme-selector');
        
        // Create select
        this.themeSelector = document.createElement('select');
        this.themeSelector.id = 'theme-selector';
        this.themeSelector.className = 'theme-selector';
        this.themeSelector.setAttribute('data-theme-selector', 'true');
        
        // Add options
        const options = [
            { value: 'auto', text: 'Auto (System)' },
            { value: 'light', text: 'Light' },
            { value: 'dark', text: 'Dark' }
        ];
        
        options.forEach(({ value, text }) => {
            if (this.config.themes.includes(value)) {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = text;
                this.themeSelector.appendChild(option);
            }
        });
        
        container.appendChild(label);
        container.appendChild(this.themeSelector);
        settingsArea.appendChild(container);
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
    handleSelectorChange(e) {
        const newTheme = e.target.value;
        this.setTheme(newTheme);
    }

    /**
     * Set theme
     * 
     * @param {string} theme Theme name
     */
    setTheme(theme) {
        if (!this.config.themes.includes(theme)) {
            console.warn(`🌙 Invalid theme: ${theme}`);
            return;
        }
        
        // Save current theme
        const previousTheme = this.currentTheme;
        const previousResolvedTheme = this.resolvedTheme;
        
        // Update current theme
        this.currentTheme = theme;
        
        // Apply theme
        this.applyTheme(theme);
        
        // Save preference
        this.savePreference(theme);
        
        // Update UI elements
        this.updateToggleButton();
        this.updateThemeSelector();
        
        // Emit theme change event
        if (this.eventBus) {
            this.eventBus.emit('darkmode:theme-changed', {
                previousTheme,
                currentTheme: this.currentTheme,
                previousResolvedTheme,
                resolvedTheme: this.resolvedTheme
            });
        }
        
        if (this.config.debug) {
            console.log('🌙 Theme changed:', {
                previousTheme,
                currentTheme: this.currentTheme,
                resolvedTheme: this.resolvedTheme
            });
        }
    }

    /**
     * Apply theme to the document
     * 
     * @param {string} theme Theme name
     */
    applyTheme(theme) {
        // Resolve theme to actual light/dark value
        let resolvedTheme;
        
        if (theme === 'auto') {
            resolvedTheme = this.systemPreference || 'light';
        } else {
            resolvedTheme = theme;
        }
        
        this.resolvedTheme = resolvedTheme;
        
        // Enable transitions if configured
        if (this.config.enableTransitions) {
            this.enableThemeTransitions();
        }
        
        // Update document attributes
        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.setAttribute('data-resolved-theme', resolvedTheme);
        
        // Update body classes
        document.body.classList.remove('theme-light', 'theme-dark', 'theme-auto');
        document.body.classList.add(`theme-${theme}`);
        
        document.body.classList.remove('resolved-theme-light', 'resolved-theme-dark');
        document.body.classList.add(`resolved-theme-${resolvedTheme}`);
        
        // Update meta theme-color
        this.updateMetaThemeColor(resolvedTheme);
        
        // Disable transitions after a delay
        if (this.config.enableTransitions) {
            setTimeout(() => {
                this.disableThemeTransitions();
            }, this.config.transitionDuration);
        }
        
        if (this.config.debug) {
            console.log('🌙 Theme applied:', {
                theme,
                resolvedTheme,
                systemPreference: this.systemPreference
            });
        }
    }

    /**
     * Enable theme transitions
     */
    enableThemeTransitions() {
        // Add transition class to body
        document.body.classList.add('theme-transitioning');
        
        // Add CSS for transitions
        if (!document.getElementById('theme-transitions')) {
            const style = document.createElement('style');
            style.id = 'theme-transitions';
            style.textContent = `
                .theme-transitioning,
                .theme-transitioning *,
                .theme-transitioning *::before,
                .theme-transitioning *::after {
                    transition: background-color ${this.config.transitionDuration}ms ease,
                                border-color ${this.config.transitionDuration}ms ease,
                                color ${this.config.transitionDuration}ms ease,
                                fill ${this.config.transitionDuration}ms ease,
                                stroke ${this.config.transitionDuration}ms ease,
                                opacity ${this.config.transitionDuration}ms ease,
                                box-shadow ${this.config.transitionDuration}ms ease !important;
                }
                
                .theme-transitioning img,
                .theme-transitioning video,
                .theme-transitioning iframe,
                .theme-transitioning object,
                .theme-transitioning embed {
                    transition: opacity ${this.config.transitionDuration}ms ease !important;
                }
            `;
            document.head.appendChild(style);
        }
    }

    /**
     * Disable theme transitions
     */
    disableThemeTransitions() {
        document.body.classList.remove('theme-transitioning');
    }

    /**
     * Update meta theme-color for mobile browsers
     * 
     * @param {string} theme Resolved theme
     */
    updateMetaThemeColor(theme) {
        let themeColor = '#ffffff'; // Default light theme color
        
        if (theme === 'dark') {
            themeColor = '#0a0e27'; // Dark theme color from our SCSS variables
        }
        
        // Update existing meta tag or create new one
        let metaThemeColor = document.querySelector('meta[name="theme-color"]');
        
        if (!metaThemeColor) {
            metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            document.head.appendChild(metaThemeColor);
        }
        
        metaThemeColor.content = themeColor;
    }

    /**
     * Update toggle button state
     */
    updateToggleButton() {
        if (!this.toggleButton) return;
        
        // Update ARIA label
        const isDark = this.resolvedTheme === 'dark';
        const newLabel = isDark ? 'Switch to light mode' : 'Switch to dark mode';
        this.toggleButton.setAttribute('aria-label', newLabel);
        
        // Update pressed state
        this.toggleButton.setAttribute('aria-pressed', isDark.toString());
        
        // Update visual state
        this.toggleButton.classList.toggle('is-dark', isDark);
        
        // Update icons visibility
        const sunIcon = this.toggleButton.querySelector('.sun-icon');
        const moonIcon = this.toggleButton.querySelector('.moon-icon');
        
        if (sunIcon && moonIcon) {
            sunIcon.style.display = isDark ? 'none' : 'block';
            moonIcon.style.display = isDark ? 'block' : 'none';
        }
    }

    /**
     * Update theme selector state
     */
    updateThemeSelector() {
        if (!this.themeSelector) return;
        
        this.themeSelector.value = this.currentTheme;
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Listen for storage changes (multiple tabs)
        if (this.config.enableLocalStorage) {
            window.addEventListener('storage', (e) => {
                if (e.key === this.config.storageKey && e.newValue !== this.currentTheme) {
                    this.setTheme(e.newValue || this.config.defaultTheme);
                }
            });
        }
        
        // Listen for app events
        if (this.eventBus) {
            this.eventBus.on('app:visibility-changed', this.handleVisibilityChange.bind(this));
        }
    }

    /**
     * Handle page visibility changes
     * 
     * @param {Object} event Visibility change event
     */
    handleVisibilityChange(event) {
        // Re-check system preference when page becomes visible
        if (event.data.visible && this.currentTheme === 'auto') {
            const currentSystemPreference = this.mediaQuery ? 
                (this.mediaQuery.matches ? 'dark' : 'light') : 'light';
                
            if (currentSystemPreference !== this.systemPreference) {
                this.systemPreference = currentSystemPreference;
                this.applyTheme('auto');
            }
        }
    }

    /**
     * Get current theme information
     * 
     * @return {Object} Theme information
     */
    getThemeInfo() {
        return {
            currentTheme: this.currentTheme,
            resolvedTheme: this.resolvedTheme,
            systemPreference: this.systemPreference,
            availableThemes: this.config.themes,
            isSystemPreferenceEnabled: this.config.enableSystemPreference,
            isLocalStorageEnabled: this.config.enableLocalStorage
        };
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
     * Check if auto mode is active
     * 
     * @return {boolean} True if auto mode is active
     */
    isAuto() {
        return this.currentTheme === 'auto';
    }

    /**
     * Force refresh theme application
     */
    refresh() {
        this.applyTheme(this.currentTheme);
        this.updateToggleButton();
        this.updateThemeSelector();
    }

    /**
     * Cleanup dark mode functionality
     */
    cleanup() {
        // Remove event listeners
        if (this.mediaQuery) {
            this.mediaQuery.removeListener(this.handleSystemPreferenceChange);
        }
        
        if (this.toggleButton) {
            this.toggleButton.removeEventListener('click', this.handleToggleClick);
        }
        
        if (this.themeSelector) {
            this.themeSelector.removeEventListener('change', this.handleSelectorChange);
        }
        
        // Remove transition styles
        const transitionStyles = document.getElementById('theme-transitions');
        if (transitionStyles) {
            transitionStyles.remove();
        }
        
        // Remove theme classes
        document.body.classList.remove(
            'theme-light', 'theme-dark', 'theme-auto',
            'resolved-theme-light', 'resolved-theme-dark',
            'theme-transitioning'
        );
        
        // Reset document attributes
        document.documentElement.removeAttribute('data-theme');
        document.documentElement.removeAttribute('data-resolved-theme');
        
        if (this.config.debug) {
            console.log('🌙 Dark mode cleaned up');
        }
    }
}

// Export for module loader
export default DarkMode;
