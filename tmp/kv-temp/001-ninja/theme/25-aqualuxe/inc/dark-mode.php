<?php
/**
 * Dark Mode functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize Dark Mode functionality
 */
function aqualuxe_dark_mode_init() {
    // Only proceed if dark mode is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
        return;
    }

    // Add dark mode toggle script
    add_action('wp_footer', 'aqualuxe_dark_mode_script');
    
    // Add dark mode toggle button to header
    add_action('aqualuxe_after_header', 'aqualuxe_dark_mode_toggle_button');
}
add_action('init', 'aqualuxe_dark_mode_init');

/**
 * Add dark mode toggle button to header
 */
function aqualuxe_dark_mode_toggle_button() {
    // Only show if dark mode is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
        return;
    }
    
    aqualuxe_before_dark_mode_toggle();
    ?>
    <div class="dark-mode-toggle-wrapper">
        <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
            <span class="dark-mode-toggle-icon light">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current">
                    <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" />
                </svg>
            </span>
            <span class="dark-mode-toggle-icon dark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current">
                    <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    </div>
    <?php
    aqualuxe_after_dark_mode_toggle();
}

/**
 * Add dark mode toggle script to footer
 */
function aqualuxe_dark_mode_script() {
    // Only include if dark mode is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
        return;
    }
    
    $default_scheme = get_theme_mod('aqualuxe_default_color_scheme', 'light');
    ?>
    <script>
    (function() {
        // Dark mode functionality
        function aqualuxeDarkMode() {
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const htmlElement = document.documentElement;
            const darkModeStorageKey = 'aqualuxe_color_scheme';
            const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            // Get stored color scheme
            const getColorScheme = () => {
                // Check for stored preference
                const storedScheme = localStorage.getItem(darkModeStorageKey);
                if (storedScheme) {
                    return storedScheme;
                }
                
                // Check for system preference
                if (darkModeMediaQuery.matches) {
                    return 'dark';
                }
                
                // Use default from theme settings
                return '<?php echo esc_js($default_scheme); ?>';
            };
            
            // Apply color scheme
            const applyColorScheme = (scheme) => {
                if (scheme === 'dark') {
                    htmlElement.classList.add('dark');
                    document.cookie = `${darkModeStorageKey}=dark;path=/;max-age=31536000`;
                } else {
                    htmlElement.classList.remove('dark');
                    document.cookie = `${darkModeStorageKey}=light;path=/;max-age=31536000`;
                }
                localStorage.setItem(darkModeStorageKey, scheme);
            };
            
            // Toggle color scheme
            const toggleColorScheme = () => {
                const currentScheme = getColorScheme();
                const newScheme = currentScheme === 'dark' ? 'light' : 'dark';
                applyColorScheme(newScheme);
            };
            
            // Initialize
            const init = () => {
                // Apply initial color scheme
                applyColorScheme(getColorScheme());
                
                // Add toggle event listener
                if (darkModeToggle) {
                    darkModeToggle.addEventListener('click', toggleColorScheme);
                }
                
                // Listen for system preference changes
                darkModeMediaQuery.addEventListener('change', (e) => {
                    // Only apply if user hasn't manually set a preference
                    if (!localStorage.getItem(darkModeStorageKey)) {
                        applyColorScheme(e.matches ? 'dark' : 'light');
                    }
                });
            };
            
            // Run initialization
            init();
        }
        
        // Run when DOM is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', aqualuxeDarkMode);
        } else {
            aqualuxeDarkMode();
        }
    })();
    </script>
    <?php
}

/**
 * Add dark mode styles to head
 */
function aqualuxe_dark_mode_styles() {
    // Only include if dark mode is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
        return;
    }
    
    // Get color settings
    $text_color_dark = get_theme_mod('aqualuxe_text_color_dark', '#e0e0e0');
    $background_color_dark = get_theme_mod('aqualuxe_background_color_dark', '#121212');
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0077b6');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00b4d8');
    $accent_color = get_theme_mod('aqualuxe_accent_color', '#90e0ef');
    
    // Output custom CSS variables for dark mode
    ?>
    <style id="aqualuxe-dark-mode-styles">
        :root {
            --color-dark-text: <?php echo esc_attr($text_color_dark); ?>;
            --color-dark-background: <?php echo esc_attr($background_color_dark); ?>;
            --color-dark-primary: <?php echo esc_attr($primary_color); ?>;
            --color-dark-secondary: <?php echo esc_attr($secondary_color); ?>;
            --color-dark-accent: <?php echo esc_attr($accent_color); ?>;
        }
        
        /* Dark mode styles */
        .dark {
            color-scheme: dark;
            --color-text: var(--color-dark-text);
            --color-background: var(--color-dark-background);
            --color-primary: var(--color-dark-primary);
            --color-secondary: var(--color-dark-secondary);
            --color-accent: var(--color-dark-accent);
            --color-border: rgba(255, 255, 255, 0.1);
            --color-card-bg: rgba(255, 255, 255, 0.05);
            --color-input-bg: rgba(255, 255, 255, 0.05);
            --color-input-text: var(--color-dark-text);
            --color-input-border: rgba(255, 255, 255, 0.2);
            --color-header-bg: rgba(18, 18, 18, 0.95);
            --color-footer-bg: rgba(18, 18, 18, 0.98);
        }
        
        /* Dark mode toggle button styles */
        .dark-mode-toggle-wrapper {
            display: inline-flex;
            align-items: center;
            margin-left: 1rem;
        }
        
        .dark-mode-toggle {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text);
            transition: background-color 0.3s ease;
        }
        
        .dark-mode-toggle:hover {
            background-color: rgba(128, 128, 128, 0.2);
        }
        
        .dark-mode-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--color-primary);
        }
        
        .dark-mode-toggle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .dark-mode-toggle-icon.light {
            display: none;
        }
        
        .dark-mode-toggle-icon.dark {
            display: flex;
        }
        
        .dark .dark-mode-toggle-icon.light {
            display: flex;
        }
        
        .dark .dark-mode-toggle-icon.dark {
            display: none;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_dark_mode_styles');

/**
 * Check if dark mode is active
 *
 * @return bool True if dark mode is active, false otherwise
 */
function aqualuxe_is_dark_mode_active() {
    // Check if dark mode is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
        return false;
    }
    
    // Get default color scheme from theme options
    $default_scheme = get_theme_mod('aqualuxe_default_color_scheme', 'light');
    
    // Check for cookie preference
    if (isset($_COOKIE['aqualuxe_color_scheme'])) {
        return sanitize_text_field($_COOKIE['aqualuxe_color_scheme']) === 'dark';
    }
    
    // Check for system preference if no cookie is set
    if (isset($_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'])) {
        return $_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'] === 'dark';
    }
    
    // Fall back to default theme setting
    return $default_scheme === 'dark';
}