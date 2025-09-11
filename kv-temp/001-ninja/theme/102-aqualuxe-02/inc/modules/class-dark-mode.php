<?php
/**
 * Dark Mode Module
 *
 * Handles theme dark mode functionality
 *
 * @package AquaLuxe\Modules
 * @since 1.0.0
 */

namespace AquaLuxe\Modules;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Dark_Mode
 *
 * Implements dark mode toggle and persistence
 *
 * @since 1.0.0
 */
class Dark_Mode {

    /**
     * Initialize the dark mode module
     *
     * @since 1.0.0
     */
    public function init() {
        add_action( 'wp_head', array( $this, 'add_dark_mode_script' ), 1 );
        add_action( 'wp_footer', array( $this, 'add_dark_mode_toggle' ) );
        add_action( 'customize_register', array( $this, 'add_customizer_settings' ) );
        add_action( 'wp_ajax_aqualuxe_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
        
        // Add body class
        add_filter( 'body_class', array( $this, 'add_body_class' ) );
        
        // Admin dark mode
        add_action( 'admin_head', array( $this, 'admin_dark_mode' ) );
    }

    /**
     * Add dark mode detection script in head
     *
     * @since 1.0.0
     */
    public function add_dark_mode_script() {
        $default_mode = get_theme_mod( 'aqualuxe_dark_mode_default', 'light' );
        ?>
        <script>
        (function() {
            // Check for saved preference or default to system preference
            const savedMode = localStorage.getItem('aqualuxe-color-mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const defaultMode = '<?php echo esc_js( $default_mode ); ?>';
            
            let colorMode = savedMode;
            
            if (!colorMode) {
                if (defaultMode === 'auto') {
                    colorMode = prefersDark ? 'dark' : 'light';
                } else {
                    colorMode = defaultMode;
                }
            }
            
            // Apply immediately to prevent flash
            if (colorMode === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Store the initial mode
            document.documentElement.setAttribute('data-color-mode', colorMode);
            
            // Listen for system preference changes if in auto mode
            if (defaultMode === 'auto' && !savedMode) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    const newMode = e.matches ? 'dark' : 'light';
                    document.documentElement.classList.toggle('dark', e.matches);
                    document.documentElement.setAttribute('data-color-mode', newMode);
                });
            }
        })();
        </script>
        <?php
    }

    /**
     * Add dark mode toggle button
     *
     * @since 1.0.0
     */
    public function add_dark_mode_toggle() {
        $show_toggle = get_theme_mod( 'aqualuxe_dark_mode_toggle', true );
        
        if ( ! $show_toggle ) {
            return;
        }
        
        ?>
        <div id="dark-mode-toggle" class="dark-mode-toggle fixed bottom-4 left-4 z-40">
            <button type="button" 
                    class="toggle-btn bg-white dark:bg-gray-800 shadow-lg rounded-full p-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-300 transform hover:scale-110"
                    aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>"
                    title="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
                
                <!-- Sun icon (visible in dark mode) -->
                <svg class="sun-icon w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                
                <!-- Moon icon (visible in light mode) -->
                <svg class="moon-icon w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const toggleBtn = darkModeToggle?.querySelector('.toggle-btn');
            
            if (!toggleBtn) return;
            
            toggleBtn.addEventListener('click', function() {
                toggleDarkMode();
            });
            
            // Keyboard support
            document.addEventListener('keydown', function(e) {
                // Toggle with Ctrl/Cmd + Shift + D
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                    e.preventDefault();
                    toggleDarkMode();
                }
            });
            
            function toggleDarkMode() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                const newMode = isDark ? 'light' : 'dark';
                
                // Add transition class
                html.style.transition = 'color 0.3s ease, background-color 0.3s ease';
                
                // Toggle dark class
                html.classList.toggle('dark', !isDark);
                html.setAttribute('data-color-mode', newMode);
                
                // Save preference
                localStorage.setItem('aqualuxe-color-mode', newMode);
                
                // Remove transition after animation
                setTimeout(() => {
                    html.style.transition = '';
                }, 300);
                
                // Trigger custom event
                window.dispatchEvent(new CustomEvent('darkModeToggled', {
                    detail: { mode: newMode }
                }));
                
                // Optional: Send to server for logged-in users
                if (typeof aqualuxe_vars !== 'undefined') {
                    fetch(aqualuxe_vars.ajax_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'aqualuxe_toggle_dark_mode',
                            mode: newMode,
                            nonce: aqualuxe_vars.nonce
                        })
                    });
                }
            }
            
            // Initialize toggle state
            updateToggleState();
            
            // Update toggle state when mode changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        updateToggleState();
                    }
                });
            });
            
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
            
            function updateToggleState() {
                const isDark = document.documentElement.classList.contains('dark');
                const sunIcon = toggleBtn.querySelector('.sun-icon');
                const moonIcon = toggleBtn.querySelector('.moon-icon');
                
                if (isDark) {
                    sunIcon?.classList.remove('hidden');
                    sunIcon?.classList.add('block');
                    moonIcon?.classList.remove('block');
                    moonIcon?.classList.add('hidden');
                    toggleBtn.setAttribute('aria-label', '<?php esc_attr_e( 'Switch to light mode', 'aqualuxe' ); ?>');
                    toggleBtn.setAttribute('title', '<?php esc_attr_e( 'Switch to light mode', 'aqualuxe' ); ?>');
                } else {
                    sunIcon?.classList.remove('block');
                    sunIcon?.classList.add('hidden');
                    moonIcon?.classList.remove('hidden');
                    moonIcon?.classList.add('block');
                    toggleBtn.setAttribute('aria-label', '<?php esc_attr_e( 'Switch to dark mode', 'aqualuxe' ); ?>');
                    toggleBtn.setAttribute('title', '<?php esc_attr_e( 'Switch to dark mode', 'aqualuxe' ); ?>');
                }
            }
        });
        </script>
        
        <style>
        .dark-mode-toggle .toggle-btn {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .dark-mode-toggle .toggle-btn:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .dark .dark-mode-toggle .toggle-btn:hover {
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.1);
        }
        
        /* Smooth transitions for dark mode */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-duration: 300ms;
            transition-timing-function: ease;
        }
        
        /* Reduce motion for users who prefer it */
        @media (prefers-reduced-motion: reduce) {
            * {
                transition-duration: 0ms !important;
            }
        }
        </style>
        <?php
    }

    /**
     * Add customizer settings for dark mode
     *
     * @since 1.0.0
     * @param WP_Customize_Manager $wp_customize Customizer instance
     */
    public function add_customizer_settings( $wp_customize ) {
        // Dark Mode Section
        $wp_customize->add_section( 'aqualuxe_dark_mode', array(
            'title'    => esc_html__( 'Dark Mode', 'aqualuxe' ),
            'panel'    => 'aqualuxe_theme_options',
            'priority' => 25,
        ) );

        // Default Mode
        $wp_customize->add_setting( 'aqualuxe_dark_mode_default', array(
            'default'           => 'light',
            'sanitize_callback' => array( $this, 'sanitize_dark_mode' ),
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_default', array(
            'label'    => esc_html__( 'Default Color Mode', 'aqualuxe' ),
            'section'  => 'aqualuxe_dark_mode',
            'type'     => 'select',
            'choices'  => array(
                'light' => esc_html__( 'Light', 'aqualuxe' ),
                'dark'  => esc_html__( 'Dark', 'aqualuxe' ),
                'auto'  => esc_html__( 'Auto (System Preference)', 'aqualuxe' ),
            ),
            'priority' => 10,
        ) );

        // Show Toggle
        $wp_customize->add_setting( 'aqualuxe_dark_mode_toggle', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_toggle', array(
            'label'    => esc_html__( 'Show Dark Mode Toggle', 'aqualuxe' ),
            'section'  => 'aqualuxe_dark_mode',
            'type'     => 'checkbox',
            'priority' => 20,
        ) );

        // Toggle Position
        $wp_customize->add_setting( 'aqualuxe_dark_mode_position', array(
            'default'           => 'bottom-left',
            'sanitize_callback' => array( $this, 'sanitize_toggle_position' ),
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_position', array(
            'label'    => esc_html__( 'Toggle Position', 'aqualuxe' ),
            'section'  => 'aqualuxe_dark_mode',
            'type'     => 'select',
            'choices'  => array(
                'bottom-left'  => esc_html__( 'Bottom Left', 'aqualuxe' ),
                'bottom-right' => esc_html__( 'Bottom Right', 'aqualuxe' ),
                'top-left'     => esc_html__( 'Top Left', 'aqualuxe' ),
                'top-right'    => esc_html__( 'Top Right', 'aqualuxe' ),
            ),
            'priority' => 30,
        ) );

        // Auto Mode Hours
        $wp_customize->add_setting( 'aqualuxe_dark_mode_auto_start', array(
            'default'           => '18:00',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_auto_start', array(
            'label'       => esc_html__( 'Auto Dark Mode Start Time', 'aqualuxe' ),
            'description' => esc_html__( 'Time when dark mode automatically starts (24-hour format)', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'time',
            'priority'    => 40,
        ) );

        $wp_customize->add_setting( 'aqualuxe_dark_mode_auto_end', array(
            'default'           => '06:00',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_auto_end', array(
            'label'       => esc_html__( 'Auto Dark Mode End Time', 'aqualuxe' ),
            'description' => esc_html__( 'Time when dark mode automatically ends (24-hour format)', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'time',
            'priority'    => 50,
        ) );
    }

    /**
     * Sanitize dark mode setting
     *
     * @since 1.0.0
     * @param string $input User input
     * @return string
     */
    public function sanitize_dark_mode( $input ) {
        $valid_modes = array( 'light', 'dark', 'auto' );
        return in_array( $input, $valid_modes, true ) ? $input : 'light';
    }

    /**
     * Sanitize toggle position setting
     *
     * @since 1.0.0
     * @param string $input User input
     * @return string
     */
    public function sanitize_toggle_position( $input ) {
        $valid_positions = array( 'bottom-left', 'bottom-right', 'top-left', 'top-right' );
        return in_array( $input, $valid_positions, true ) ? $input : 'bottom-left';
    }

    /**
     * Add body class for dark mode
     *
     * @since 1.0.0
     * @param array $classes Body classes
     * @return array
     */
    public function add_body_class( $classes ) {
        $default_mode = get_theme_mod( 'aqualuxe_dark_mode_default', 'light' );
        
        if ( $default_mode === 'dark' ) {
            $classes[] = 'dark-mode-default';
        } elseif ( $default_mode === 'auto' ) {
            $classes[] = 'dark-mode-auto';
        }
        
        return $classes;
    }

    /**
     * AJAX handler for dark mode toggle
     *
     * @since 1.0.0
     */
    public function ajax_toggle_dark_mode() {
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'aqualuxe_nonce' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
        }

        $mode = sanitize_key( $_POST['mode'] ?? '' );
        
        if ( ! in_array( $mode, array( 'light', 'dark' ), true ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid mode.', 'aqualuxe' ) ) );
        }

        // Save user preference if logged in
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'aqualuxe_dark_mode_preference', $mode );
        }

        wp_send_json_success( array(
            'message' => esc_html__( 'Dark mode preference saved.', 'aqualuxe' ),
            'mode'    => $mode,
        ) );
    }

    /**
     * Add admin dark mode support
     *
     * @since 1.0.0
     */
    public function admin_dark_mode() {
        if ( ! is_user_logged_in() ) {
            return;
        }

        $user_preference = get_user_meta( get_current_user_id(), 'aqualuxe_dark_mode_preference', true );
        
        if ( $user_preference === 'dark' ) {
            ?>
            <style>
            .wp-admin {
                filter: invert(1) hue-rotate(180deg);
            }
            .wp-admin img,
            .wp-admin video,
            .wp-admin iframe,
            .wp-admin svg,
            .wp-admin .mce-content-body {
                filter: invert(1) hue-rotate(180deg);
            }
            </style>
            <?php
        }
    }

    /**
     * Get current dark mode state
     *
     * @since 1.0.0
     * @return string
     */
    public function get_current_mode() {
        // Check user preference if logged in
        if ( is_user_logged_in() ) {
            $user_preference = get_user_meta( get_current_user_id(), 'aqualuxe_dark_mode_preference', true );
            if ( $user_preference ) {
                return $user_preference;
            }
        }

        // Check cookie
        if ( isset( $_COOKIE['aqualuxe-color-mode'] ) ) {
            $mode = sanitize_key( $_COOKIE['aqualuxe-color-mode'] );
            if ( in_array( $mode, array( 'light', 'dark' ), true ) ) {
                return $mode;
            }
        }

        // Return default
        return get_theme_mod( 'aqualuxe_dark_mode_default', 'light' );
    }

    /**
     * Check if dark mode is enabled
     *
     * @since 1.0.0
     * @return bool
     */
    public function is_dark_mode() {
        return $this->get_current_mode() === 'dark';
    }

    /**
     * Get dark mode classes for elements
     *
     * @since 1.0.0
     * @param string $light_classes Light mode classes
     * @param string $dark_classes  Dark mode classes
     * @return string
     */
    public function get_mode_classes( $light_classes, $dark_classes ) {
        return $light_classes . ' dark:' . $dark_classes;
    }
}