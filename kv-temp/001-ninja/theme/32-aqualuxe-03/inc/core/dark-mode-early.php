<?php
/**
 * Early dark mode detection for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

if ( ! function_exists( 'aqualuxe_dark_mode_early_detection' ) ) {
    /**
     * Early detection of dark mode preference
     * This function is called in the header before any other scripts are loaded
     */
    function aqualuxe_dark_mode_early_detection() {
        // Check if dark mode is enabled in the customizer
        if ( function_exists( 'get_theme_mod' ) && ! get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
            return;
        }
        
        // Get default mode from the customizer
        $default_mode = function_exists( 'get_theme_mod' ) ? get_theme_mod( 'aqualuxe_dark_mode_default', 'auto' ) : 'auto';
        
        // Output inline script
        ?>
        <script>
            (function() {
                // Check for saved dark mode preference or use default
                const darkModePref = localStorage.getItem('aqualuxe_dark_mode') || '<?php echo esc_js($default_mode); ?>';
                const systemDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                
                // Apply dark mode if preference is dark or if auto and system is dark
                if (darkModePref === 'dark' || (darkModePref === 'auto' && systemDarkMode)) {
                    document.documentElement.classList.add('dark');
                    document.cookie = 'aqualuxe_dark_mode=dark;path=/;max-age=31536000';
                } else {
                    document.documentElement.classList.remove('dark');
                    document.cookie = 'aqualuxe_dark_mode=light;path=/;max-age=31536000';
                }
            })();
        </script>
        <?php
    }
}