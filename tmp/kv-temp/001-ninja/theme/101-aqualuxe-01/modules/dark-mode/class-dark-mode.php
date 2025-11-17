<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Dark Mode functionality
 */
class AquaLuxe_Dark_Mode {

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'wp_head', array( $this, 'add_dark_mode_script' ) );
        add_action( 'wp_footer', array( $this, 'add_dark_mode_toggle' ) );
        add_action( 'customize_register', array( $this, 'customize_register' ) );
        add_action( 'wp_ajax_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
        add_action( 'wp_ajax_nopriv_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
    }

    /**
     * Add dark mode initialization script to head
     */
    public function add_dark_mode_script() {
        ?>
        <script>
        (function() {
            // Check for saved theme preference or default to light mode
            const currentTheme = localStorage.getItem('aqualuxe_theme') || 'light';
            
            if (currentTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }
            
            // Remove no-js class
            document.documentElement.classList.remove('no-js');
        })();
        </script>
        <?php
    }

    /**
     * Add dark mode toggle button
     */
    public function add_dark_mode_toggle() {
        if ( ! get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
            return;
        }
        ?>
        <button 
            id="dark-mode-toggle" 
            class="dark-mode-toggle fixed top-4 right-4 z-50 w-12 h-12 rounded-full bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200"
            aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>"
            data-dark-mode-toggle
        >
            <svg class="sun-icon w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
            <svg class="moon-icon w-5 h-5 block dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>
        <?php
    }

    /**
     * Add customizer settings
     */
    public function customize_register( $wp_customize ) {
        // Add dark mode section
        $wp_customize->add_section( 'aqualuxe_dark_mode', array(
            'title'      => esc_html__( 'Dark Mode', 'aqualuxe' ),
            'priority'   => 50,
        ) );

        // Enable dark mode setting
        $wp_customize->add_setting( 'aqualuxe_enable_dark_mode', array(
            'default'           => true,
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
        ) );

        $wp_customize->add_control( 'aqualuxe_enable_dark_mode', array(
            'label'   => esc_html__( 'Enable Dark Mode Toggle', 'aqualuxe' ),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'checkbox',
        ) );

        // Auto dark mode setting
        $wp_customize->add_setting( 'aqualuxe_auto_dark_mode', array(
            'default'           => false,
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
        ) );

        $wp_customize->add_control( 'aqualuxe_auto_dark_mode', array(
            'label'       => esc_html__( 'Auto Dark Mode', 'aqualuxe' ),
            'description' => esc_html__( 'Automatically switch to dark mode based on user\'s system preference', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ) );

        // Dark mode schedule
        $wp_customize->add_setting( 'aqualuxe_scheduled_dark_mode', array(
            'default'           => false,
            'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
        ) );

        $wp_customize->add_control( 'aqualuxe_scheduled_dark_mode', array(
            'label'       => esc_html__( 'Scheduled Dark Mode', 'aqualuxe' ),
            'description' => esc_html__( 'Automatically switch to dark mode during evening hours', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ) );

        // Dark mode start time
        $wp_customize->add_setting( 'aqualuxe_dark_mode_start_time', array(
            'default'           => '18:00',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_start_time', array(
            'label'           => esc_html__( 'Dark Mode Start Time', 'aqualuxe' ),
            'section'         => 'aqualuxe_dark_mode',
            'type'            => 'time',
            'active_callback' => array( $this, 'is_scheduled_dark_mode_enabled' ),
        ) );

        // Dark mode end time
        $wp_customize->add_setting( 'aqualuxe_dark_mode_end_time', array(
            'default'           => '06:00',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( 'aqualuxe_dark_mode_end_time', array(
            'label'           => esc_html__( 'Dark Mode End Time', 'aqualuxe' ),
            'section'         => 'aqualuxe_dark_mode',
            'type'            => 'time',
            'active_callback' => array( $this, 'is_scheduled_dark_mode_enabled' ),
        ) );
    }

    /**
     * Sanitize checkbox
     */
    public function sanitize_checkbox( $checked ) {
        return ( isset( $checked ) && true === $checked ) ? true : false;
    }

    /**
     * Check if scheduled dark mode is enabled
     */
    public function is_scheduled_dark_mode_enabled() {
        return get_theme_mod( 'aqualuxe_scheduled_dark_mode', false );
    }

    /**
     * AJAX handler for dark mode toggle
     */
    public function ajax_toggle_dark_mode() {
        check_ajax_referer( 'aqualuxe_nonce', 'nonce' );

        $mode = sanitize_text_field( $_POST['mode'] );
        
        if ( ! in_array( $mode, array( 'light', 'dark' ), true ) ) {
            wp_send_json_error( esc_html__( 'Invalid mode', 'aqualuxe' ) );
        }

        // Set cookie for 30 days
        setcookie( 'aqualuxe_dark_mode', $mode === 'dark' ? 'true' : 'false', time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );

        wp_send_json_success( array( 'mode' => $mode ) );
    }

    /**
     * Get current dark mode preference
     */
    public function get_current_mode() {
        // Check cookie first
        if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
            return $_COOKIE['aqualuxe_dark_mode'] === 'true' ? 'dark' : 'light';
        }

        // Check if auto mode is enabled
        if ( get_theme_mod( 'aqualuxe_auto_dark_mode', false ) ) {
            return 'auto';
        }

        // Check if scheduled mode is enabled
        if ( get_theme_mod( 'aqualuxe_scheduled_dark_mode', false ) ) {
            $start_time = get_theme_mod( 'aqualuxe_dark_mode_start_time', '18:00' );
            $end_time   = get_theme_mod( 'aqualuxe_dark_mode_end_time', '06:00' );
            $current_time = current_time( 'H:i' );

            if ( $start_time > $end_time ) {
                // Overnight schedule (e.g., 18:00 to 06:00)
                if ( $current_time >= $start_time || $current_time <= $end_time ) {
                    return 'dark';
                }
            } else {
                // Same day schedule (e.g., 18:00 to 22:00)
                if ( $current_time >= $start_time && $current_time <= $end_time ) {
                    return 'dark';
                }
            }
        }

        return 'light';
    }

    /**
     * Add enhanced dark mode script
     */
    public function add_enhanced_dark_mode_script() {
        $auto_mode = get_theme_mod( 'aqualuxe_auto_dark_mode', false );
        $scheduled_mode = get_theme_mod( 'aqualuxe_scheduled_dark_mode', false );
        $start_time = get_theme_mod( 'aqualuxe_dark_mode_start_time', '18:00' );
        $end_time = get_theme_mod( 'aqualuxe_dark_mode_end_time', '06:00' );
        ?>
        <script>
        (function() {
            const autoMode = <?php echo $auto_mode ? 'true' : 'false'; ?>;
            const scheduledMode = <?php echo $scheduled_mode ? 'true' : 'false'; ?>;
            const startTime = '<?php echo esc_js( $start_time ); ?>';
            const endTime = '<?php echo esc_js( $end_time ); ?>';
            
            function getCurrentMode() {
                // Check for saved preference
                const savedMode = localStorage.getItem('aqualuxe_theme');
                if (savedMode && savedMode !== 'auto') {
                    return savedMode;
                }
                
                if (autoMode) {
                    // Use system preference
                    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                
                if (scheduledMode) {
                    const now = new Date();
                    const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                    
                    if (startTime > endTime) {
                        // Overnight schedule
                        return (currentTime >= startTime || currentTime <= endTime) ? 'dark' : 'light';
                    } else {
                        // Same day schedule
                        return (currentTime >= startTime && currentTime <= endTime) ? 'dark' : 'light';
                    }
                }
                
                return 'light';
            }
            
            const currentMode = getCurrentMode();
            
            if (currentMode === 'dark') {
                document.documentElement.classList.add('dark');
            }
            
            // Listen for system theme changes
            if (autoMode) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    const savedMode = localStorage.getItem('aqualuxe_theme');
                    if (!savedMode || savedMode === 'auto') {
                        if (e.matches) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                });
            }
        })();
        </script>
        <?php
    }
}