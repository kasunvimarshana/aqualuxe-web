<?php
/**
 * Dark Mode functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Dark Mode Class
 */
class AquaLuxe_Dark_Mode {
    /**
     * Constructor
     */
    public function __construct() {
        // Add dark mode script
        add_action('wp_enqueue_scripts', array($this, 'enqueue_dark_mode_script'));
        
        // Add dark mode toggle to customizer
        add_action('customize_register', array($this, 'customize_register'));
        
        // Add dark mode class to body
        add_filter('body_class', array($this, 'body_class'));
        
        // Add dark mode script to head
        add_action('wp_head', array($this, 'dark_mode_script'), 100);
    }

    /**
     * Enqueue dark mode script
     */
    public function enqueue_dark_mode_script() {
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            get_template_directory_uri() . '/assets/js/dark-mode.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            array(
                'enabled' => get_theme_mod('dark_mode_enabled', true),
                'auto' => get_theme_mod('dark_mode_auto', true),
                'saveInCookies' => get_theme_mod('dark_mode_cookies', true),
                'cookieName' => 'aqualuxe_dark_mode',
                'cookieExpires' => 30, // days
            )
        );
    }

    /**
     * Add dark mode options to customizer
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function customize_register($wp_customize) {
        // Dark Mode Section
        $wp_customize->add_section(
            'aqualuxe_dark_mode',
            array(
                'title' => __('Dark Mode', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 35,
            )
        );
        
        // Enable Dark Mode
        $wp_customize->add_setting(
            'dark_mode_enabled',
            array(
                'default' => true,
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
            )
        );
        
        $wp_customize->add_control(
            'dark_mode_enabled',
            array(
                'label' => __('Enable Dark Mode Toggle', 'aqualuxe'),
                'section' => 'aqualuxe_dark_mode',
                'type' => 'checkbox',
            )
        );
        
        // Auto Dark Mode
        $wp_customize->add_setting(
            'dark_mode_auto',
            array(
                'default' => true,
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
            )
        );
        
        $wp_customize->add_control(
            'dark_mode_auto',
            array(
                'label' => __('Auto Dark Mode (based on user system preferences)', 'aqualuxe'),
                'section' => 'aqualuxe_dark_mode',
                'type' => 'checkbox',
            )
        );
        
        // Save in Cookies
        $wp_customize->add_setting(
            'dark_mode_cookies',
            array(
                'default' => true,
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
            )
        );
        
        $wp_customize->add_control(
            'dark_mode_cookies',
            array(
                'label' => __('Save Dark Mode Preference in Cookies', 'aqualuxe'),
                'section' => 'aqualuxe_dark_mode',
                'type' => 'checkbox',
            )
        );
        
        // Default Mode
        $wp_customize->add_setting(
            'dark_mode_default',
            array(
                'default' => 'light',
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_select'),
            )
        );
        
        $wp_customize->add_control(
            'dark_mode_default',
            array(
                'label' => __('Default Mode', 'aqualuxe'),
                'section' => 'aqualuxe_dark_mode',
                'type' => 'select',
                'choices' => array(
                    'light' => __('Light', 'aqualuxe'),
                    'dark' => __('Dark', 'aqualuxe'),
                ),
            )
        );
        
        // Dark Mode Primary Color
        $wp_customize->add_setting(
            'dark_mode_primary_color',
            array(
                'default' => '#00b4d8',
                'transport' => 'refresh',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'dark_mode_primary_color',
                array(
                    'label' => __('Dark Mode Primary Color', 'aqualuxe'),
                    'section' => 'aqualuxe_dark_mode',
                )
            )
        );
        
        // Dark Mode Background Color
        $wp_customize->add_setting(
            'dark_mode_bg_color',
            array(
                'default' => '#121212',
                'transport' => 'refresh',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'dark_mode_bg_color',
                array(
                    'label' => __('Dark Mode Background Color', 'aqualuxe'),
                    'section' => 'aqualuxe_dark_mode',
                )
            )
        );
        
        // Dark Mode Text Color
        $wp_customize->add_setting(
            'dark_mode_text_color',
            array(
                'default' => '#f8f9fa',
                'transport' => 'refresh',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'dark_mode_text_color',
                array(
                    'label' => __('Dark Mode Text Color', 'aqualuxe'),
                    'section' => 'aqualuxe_dark_mode',
                )
            )
        );
    }

    /**
     * Add dark mode class to body
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function body_class($classes) {
        // Add dark-mode-toggle class if dark mode is enabled
        if (get_theme_mod('dark_mode_enabled', true)) {
            $classes[] = 'dark-mode-toggle';
        }

        return $classes;
    }

    /**
     * Add dark mode script to head
     */
    public function dark_mode_script() {
        // Only add script if dark mode is enabled
        if (!get_theme_mod('dark_mode_enabled', true)) {
            return;
        }

        // Get dark mode settings
        $auto = get_theme_mod('dark_mode_auto', true);
        $default = get_theme_mod('dark_mode_default', 'light');
        $cookies = get_theme_mod('dark_mode_cookies', true);

        // Script to handle dark mode
        ?>
        <script>
            (function() {
                // Dark mode variables
                var darkModeEnabled = <?php echo json_encode(get_theme_mod('dark_mode_enabled', true)); ?>;
                var darkModeAuto = <?php echo json_encode($auto); ?>;
                var darkModeDefault = '<?php echo esc_js($default); ?>';
                var darkModeCookies = <?php echo json_encode($cookies); ?>;
                var darkModeCookieName = 'aqualuxe_dark_mode';
                var darkModeCookieExpires = 30; // days

                // Function to get cookie value
                function getCookie(name) {
                    var value = "; " + document.cookie;
                    var parts = value.split("; " + name + "=");
                    if (parts.length === 2) return parts.pop().split(";").shift();
                    return null;
                }

                // Function to set cookie
                function setCookie(name, value, days) {
                    var expires = "";
                    if (days) {
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        expires = "; expires=" + date.toUTCString();
                    }
                    document.cookie = name + "=" + (value || "") + expires + "; path=/";
                }

                // Function to apply dark mode
                function applyDarkMode(isDark) {
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    
                    // Save preference in cookie if enabled
                    if (darkModeCookies) {
                        setCookie(darkModeCookieName, isDark ? 'dark' : 'light', darkModeCookieExpires);
                    }
                }

                // Check for saved preference
                var savedMode = darkModeCookies ? getCookie(darkModeCookieName) : null;
                
                // Determine initial dark mode state
                var isDarkMode = false;
                
                if (savedMode) {
                    // Use saved preference if available
                    isDarkMode = savedMode === 'dark';
                } else if (darkModeAuto) {
                    // Check system preference if auto is enabled
                    isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                } else {
                    // Use default setting
                    isDarkMode = darkModeDefault === 'dark';
                }
                
                // Apply initial dark mode
                applyDarkMode(isDarkMode);
                
                // Listen for system preference changes if auto is enabled
                if (darkModeAuto && window.matchMedia) {
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                        applyDarkMode(e.matches);
                    });
                }
                
                // Make functions available globally for the toggle button
                window.aqualuxeDarkMode = {
                    toggle: function() {
                        var isDark = document.documentElement.classList.contains('dark');
                        applyDarkMode(!isDark);
                    },
                    setDarkMode: function(isDark) {
                        applyDarkMode(isDark);
                    },
                    isDarkMode: function() {
                        return document.documentElement.classList.contains('dark');
                    }
                };
            })();
        </script>
        <?php
    }

    /**
     * Sanitize checkbox
     *
     * @param bool $checked Whether the checkbox is checked.
     * @return bool
     */
    public function sanitize_checkbox($checked) {
        return (isset($checked) && true === $checked) ? true : false;
    }

    /**
     * Sanitize select
     *
     * @param string $input The input from the setting.
     * @param object $setting The selected setting.
     * @return string The sanitized input.
     */
    public function sanitize_select($input, $setting) {
        // Get the list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control($setting->id)->choices;
        
        // Return input if valid or return default option.
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }
}

// Initialize the class
new AquaLuxe_Dark_Mode();