<?php
/**
 * Dark Mode Script Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Dark_Mode
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('darkMode', () => ({
            isDark: false,
            mode: 'light',
            modeText: aqualuxeDarkMode.i18n.darkMode,
            
            init() {
                // Get stored mode or use default
                const storedMode = localStorage.getItem('aqualuxeDarkMode');
                this.mode = storedMode || aqualuxeDarkMode.defaultMode;
                
                // Initialize based on mode
                if (this.mode === 'dark') {
                    this.enableDarkMode();
                } else if (this.mode === 'auto') {
                    this.enableAutoMode();
                } else {
                    this.enableLightMode();
                }
                
                // Listen for system preference changes if in auto mode
                if (this.mode === 'auto') {
                    this.setupMediaQueryListener();
                }
            },
            
            toggleMode() {
                if (this.mode === 'light') {
                    this.mode = 'dark';
                    this.enableDarkMode();
                } else if (this.mode === 'dark') {
                    this.mode = 'auto';
                    this.enableAutoMode();
                } else {
                    this.mode = 'light';
                    this.enableLightMode();
                }
                
                // Save preference
                localStorage.setItem('aqualuxeDarkMode', this.mode);
            },
            
            enableLightMode() {
                document.documentElement.classList.remove('dark');
                this.isDark = false;
                this.modeText = aqualuxeDarkMode.i18n.darkMode;
            },
            
            enableDarkMode() {
                document.documentElement.classList.add('dark');
                this.isDark = true;
                this.modeText = aqualuxeDarkMode.i18n.lightMode;
            },
            
            enableAutoMode() {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                
                if (prefersDark) {
                    this.enableDarkMode();
                } else {
                    this.enableLightMode();
                }
                
                this.modeText = aqualuxeDarkMode.i18n.autoMode;
            },
            
            setupMediaQueryListener() {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                mediaQuery.addEventListener('change', (e) => {
                    if (this.mode === 'auto') {
                        if (e.matches) {
                            this.enableDarkMode();
                        } else {
                            this.enableLightMode();
                        }
                        this.modeText = aqualuxeDarkMode.i18n.autoMode;
                    }
                });
            }
        }));
    });
</script>