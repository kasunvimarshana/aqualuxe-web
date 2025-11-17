<?php
/**
 * Dark Mode functionality for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize dark mode functionality
 */
function aqualuxe_dark_mode_init() {
    // Check if dark mode is enabled in theme options
    $enable_dark_mode = get_theme_mod( 'aqualuxe_enable_dark_mode', true );
    
    if ( $enable_dark_mode ) {
        // Add dark mode toggle script
        add_action( 'wp_enqueue_scripts', 'aqualuxe_dark_mode_scripts' );
        
        // Add dark mode body class
        add_filter( 'body_class', 'aqualuxe_dark_mode_body_class' );
        
        // Add dark mode toggle AJAX handler
        add_action( 'wp_ajax_aqualuxe_toggle_dark_mode', 'aqualuxe_toggle_dark_mode_ajax' );
        add_action( 'wp_ajax_nopriv_aqualuxe_toggle_dark_mode', 'aqualuxe_toggle_dark_mode_ajax' );
    }
}
add_action( 'init', 'aqualuxe_dark_mode_init' );

/**
 * Enqueue dark mode scripts and styles
 */
function aqualuxe_dark_mode_scripts() {
    wp_enqueue_style( 'aqualuxe-dark-mode', AQUALUXE_URI . 'assets/css/dark-mode.css', array(), AQUALUXE_VERSION );
    
    wp_enqueue_script( 'aqualuxe-dark-mode', AQUALUXE_URI . 'assets/js/dark-mode.js', array( 'jquery' ), AQUALUXE_VERSION, true );
    
    wp_localize_script( 'aqualuxe-dark-mode', 'aqualuxeDarkMode', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'aqualuxe_dark_mode_nonce' ),
    ) );
}

/**
 * Add dark mode class to body if enabled
 */
function aqualuxe_dark_mode_body_class( $classes ) {
    if ( aqualuxe_is_dark_mode_active() ) {
        $classes[] = 'dark-mode';
    }
    
    return $classes;
}

/**
 * Check if dark mode is active
 */
function aqualuxe_is_dark_mode_active() {
    // Check cookie first
    if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
        return $_COOKIE['aqualuxe_dark_mode'] === 'true';
    }
    
    // Check system preference as fallback
    return aqualuxe_is_system_dark_mode();
}

/**
 * Check if system dark mode is enabled
 */
function aqualuxe_is_system_dark_mode() {
    // This is a server-side function, but we can't detect system preference server-side
    // We'll default to false and let JavaScript handle it
    return false;
}

/**
 * AJAX handler for toggling dark mode
 */
function aqualuxe_toggle_dark_mode_ajax() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_dark_mode_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed', 'aqualuxe' ) ) );
        die();
    }
    
    // Get dark mode state
    $dark_mode = isset( $_POST['darkMode'] ) ? $_POST['darkMode'] === 'true' : false;
    
    // Set cookie (1 year expiration)
    $expiration = time() + YEAR_IN_SECONDS;
    setcookie( 'aqualuxe_dark_mode', $dark_mode ? 'true' : 'false', $expiration, COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array(
        'darkMode' => $dark_mode,
        'message'  => $dark_mode ? __( 'Dark mode enabled', 'aqualuxe' ) : __( 'Dark mode disabled', 'aqualuxe' ),
    ) );
    
    die();
}

/**
 * Generate dark mode toggle button
 */
function aqualuxe_dark_mode_toggle_button() {
    $is_dark_mode = aqualuxe_is_dark_mode_active();
    ?>
    <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
        <span class="dark-mode-toggle-icon sun<?php echo $is_dark_mode ? ' active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
            </svg>
        </span>
        <span class="dark-mode-toggle-icon moon<?php echo ! $is_dark_mode ? ' active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </span>
    </button>
    <?php
}

/**
 * Add dark mode toggle to header
 */
function aqualuxe_add_dark_mode_toggle_to_header() {
    // Check if dark mode is enabled in theme options
    $enable_dark_mode = get_theme_mod( 'aqualuxe_enable_dark_mode', true );
    
    if ( $enable_dark_mode ) {
        aqualuxe_dark_mode_toggle_button();
    }
}
add_action( 'aqualuxe_header_top_right', 'aqualuxe_add_dark_mode_toggle_to_header' );

/**
 * Add dark mode script to head for early detection
 */
function aqualuxe_dark_mode_early_detection() {
    // Check if dark mode is enabled in theme options
    $enable_dark_mode = get_theme_mod( 'aqualuxe_enable_dark_mode', true );
    
    if ( $enable_dark_mode ) {
        ?>
        <script>
            // Check for dark mode preference
            (function() {
                var darkMode = localStorage.getItem('aqualuxeDarkMode');
                
                // If no preference is set, check system preference
                if (darkMode === null) {
                    darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                } else {
                    darkMode = darkMode === 'true';
                }
                
                // Apply dark mode class if needed
                if (darkMode) {
                    document.documentElement.classList.add('dark-mode');
                }
            })();
        </script>
        <?php
    }
}
add_action( 'wp_head', 'aqualuxe_dark_mode_early_detection', 0 );

/**
 * Add dark mode CSS variables
 */
function aqualuxe_dark_mode_css_variables() {
    // Check if dark mode is enabled in theme options
    $enable_dark_mode = get_theme_mod( 'aqualuxe_enable_dark_mode', true );
    
    if ( $enable_dark_mode ) {
        $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#1e40af' );
        $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#2563eb' );
        $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#0ea5e9' );
        
        // Generate lighter versions for dark mode
        $primary_light = aqualuxe_lighten_color( $primary_color, 20 );
        $secondary_light = aqualuxe_lighten_color( $secondary_color, 20 );
        $accent_light = aqualuxe_lighten_color( $accent_color, 20 );
        
        ?>
        <style>
            :root {
                --dark-bg-color: #121212;
                --dark-bg-color-light: #1e1e1e;
                --dark-bg-color-lighter: #2a2a2a;
                --dark-text-color: #e0e0e0;
                --dark-text-color-light: #a0a0a0;
                --dark-border-color: #333333;
                --dark-primary-color: <?php echo esc_attr( $primary_light ); ?>;
                --dark-secondary-color: <?php echo esc_attr( $secondary_light ); ?>;
                --dark-accent-color: <?php echo esc_attr( $accent_light ); ?>;
            }
            
            .dark-mode {
                --bg-color: var(--dark-bg-color);
                --bg-color-light: var(--dark-bg-color-light);
                --bg-color-lighter: var(--dark-bg-color-lighter);
                --text-color: var(--dark-text-color);
                --text-color-light: var(--dark-text-color-light);
                --border-color: var(--dark-border-color);
                --primary-color: var(--dark-primary-color);
                --secondary-color: var(--dark-secondary-color);
                --accent-color: var(--dark-accent-color);
            }
        </style>
        <?php
    }
}
add_action( 'wp_head', 'aqualuxe_dark_mode_css_variables', 10 );

/**
 * Helper function to lighten a color
 */
function aqualuxe_lighten_color( $hex, $percent ) {
    // Remove # if present
    $hex = ltrim( $hex, '#' );
    
    // Convert to RGB
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );
    
    // Lighten
    $r = min( 255, $r + $r * $percent / 100 );
    $g = min( 255, $g + $g * $percent / 100 );
    $b = min( 255, $b + $b * $percent / 100 );
    
    // Convert back to hex
    return sprintf( '#%02x%02x%02x', $r, $g, $b );
}

/**
 * Add dark mode toggle to mobile menu
 */
function aqualuxe_add_dark_mode_toggle_to_mobile_menu( $items, $args ) {
    // Check if dark mode is enabled in theme options
    $enable_dark_mode = get_theme_mod( 'aqualuxe_enable_dark_mode', true );
    
    if ( $enable_dark_mode && $args->theme_location == 'primary' && wp_is_mobile() ) {
        ob_start();
        ?>
        <li class="menu-item menu-item-dark-mode">
            <div class="mobile-dark-mode-toggle">
                <span><?php esc_html_e( 'Dark Mode', 'aqualuxe' ); ?></span>
                <?php aqualuxe_dark_mode_toggle_button(); ?>
            </div>
        </li>
        <?php
        $items .= ob_get_clean();
    }
    
    return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_add_dark_mode_toggle_to_mobile_menu', 20, 2 );

/**
 * Add dark mode toggle to customizer
 */
function aqualuxe_dark_mode_customizer_settings( $wp_customize ) {
    // Add dark mode toggle setting
    $wp_customize->add_setting( 'aqualuxe_enable_dark_mode', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );
    
    // Add dark mode toggle control
    $wp_customize->add_control( 'aqualuxe_enable_dark_mode', array(
        'label'       => __( 'Enable Dark Mode Toggle', 'aqualuxe' ),
        'description' => __( 'Allow users to switch between light and dark mode', 'aqualuxe' ),
        'section'     => 'aqualuxe_advanced_options',
        'type'        => 'checkbox',
    ) );
    
    // Add dark mode default setting
    $wp_customize->add_setting( 'aqualuxe_dark_mode_default', array(
        'default'           => 'system',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );
    
    // Add dark mode default control
    $wp_customize->add_control( 'aqualuxe_dark_mode_default', array(
        'label'       => __( 'Default Dark Mode Setting', 'aqualuxe' ),
        'description' => __( 'Choose the default dark mode behavior for new visitors', 'aqualuxe' ),
        'section'     => 'aqualuxe_advanced_options',
        'type'        => 'select',
        'choices'     => array(
            'light'  => __( 'Light Mode', 'aqualuxe' ),
            'dark'   => __( 'Dark Mode', 'aqualuxe' ),
            'system' => __( 'Follow System Preference', 'aqualuxe' ),
        ),
    ) );
}
add_action( 'customize_register', 'aqualuxe_dark_mode_customizer_settings' );

/**
 * Add dark mode script to footer
 */
function aqualuxe_dark_mode_footer_script() {
    // Check if dark mode is enabled in theme options
    $enable_dark_mode = get_theme_mod( 'aqualuxe_enable_dark_mode', true );
    
    if ( $enable_dark_mode ) {
        $default_mode = get_theme_mod( 'aqualuxe_dark_mode_default', 'system' );
        ?>
        <script>
            // Dark mode initialization
            (function($) {
                $(document).ready(function() {
                    var darkModeToggle = $('#dark-mode-toggle');
                    var defaultMode = '<?php echo esc_js( $default_mode ); ?>';
                    var darkMode = localStorage.getItem('aqualuxeDarkMode');
                    
                    // If no preference is set, use default
                    if (darkMode === null) {
                        if (defaultMode === 'dark') {
                            darkMode = true;
                        } else if (defaultMode === 'light') {
                            darkMode = false;
                        } else {
                            // System preference
                            darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        }
                        
                        // Save preference
                        localStorage.setItem('aqualuxeDarkMode', darkMode);
                    } else {
                        darkMode = darkMode === 'true';
                    }
                    
                    // Apply dark mode class
                    if (darkMode) {
                        $('html').addClass('dark-mode');
                        $('.dark-mode-toggle-icon.moon').addClass('active');
                        $('.dark-mode-toggle-icon.sun').removeClass('active');
                    } else {
                        $('html').removeClass('dark-mode');
                        $('.dark-mode-toggle-icon.sun').addClass('active');
                        $('.dark-mode-toggle-icon.moon').removeClass('active');
                    }
                    
                    // Toggle dark mode on click
                    darkModeToggle.on('click', function() {
                        darkMode = !darkMode;
                        
                        // Save preference
                        localStorage.setItem('aqualuxeDarkMode', darkMode);
                        
                        // Apply dark mode class
                        if (darkMode) {
                            $('html').addClass('dark-mode');
                            $('.dark-mode-toggle-icon.moon').addClass('active');
                            $('.dark-mode-toggle-icon.sun').removeClass('active');
                        } else {
                            $('html').removeClass('dark-mode');
                            $('.dark-mode-toggle-icon.sun').addClass('active');
                            $('.dark-mode-toggle-icon.moon').removeClass('active');
                        }
                        
                        // Save preference via AJAX
                        $.ajax({
                            url: aqualuxeDarkMode.ajaxUrl,
                            type: 'POST',
                            data: {
                                action: 'aqualuxe_toggle_dark_mode',
                                darkMode: darkMode,
                                nonce: aqualuxeDarkMode.nonce
                            }
                        });
                    });
                    
                    // Listen for system preference changes
                    if (window.matchMedia) {
                        var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                        
                        if (mediaQuery.addEventListener) {
                            mediaQuery.addEventListener('change', function(e) {
                                // Only update if user hasn't set a preference
                                if (localStorage.getItem('aqualuxeDarkMode') === null) {
                                    darkMode = e.matches;
                                    
                                    // Apply dark mode class
                                    if (darkMode) {
                                        $('html').addClass('dark-mode');
                                        $('.dark-mode-toggle-icon.moon').addClass('active');
                                        $('.dark-mode-toggle-icon.sun').removeClass('active');
                                    } else {
                                        $('html').removeClass('dark-mode');
                                        $('.dark-mode-toggle-icon.sun').addClass('active');
                                        $('.dark-mode-toggle-icon.moon').removeClass('active');
                                    }
                                }
                            });
                        }
                    }
                });
            })(jQuery);
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'aqualuxe_dark_mode_footer_script' );