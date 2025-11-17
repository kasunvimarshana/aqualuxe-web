<?php
/**
 * DarkMode Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * DarkMode Class
 * 
 * Handles dark mode functionality
 */
class DarkMode {
    /**
     * Instance of this class
     *
     * @var DarkMode
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return DarkMode
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Register hooks
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'wp_head', [ $this, 'add_dark_mode_toggle_script' ] );
        add_action( 'wp_footer', [ $this, 'add_dark_mode_toggle' ] );
        add_action( 'admin_menu', [ $this, 'add_dark_mode_menu' ] );
        add_action( 'admin_init', [ $this, 'register_dark_mode_settings' ] );
        add_action( 'wp_ajax_aqualuxe_toggle_dark_mode', [ $this, 'ajax_toggle_dark_mode' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_toggle_dark_mode', [ $this, 'ajax_toggle_dark_mode' ] );
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        if ( ! $this->is_dark_mode_enabled() ) {
            return;
        }
        
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_ASSETS_URI . 'js/dark-mode.js',
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            [
                'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
                'nonce'     => wp_create_nonce( 'aqualuxe-dark-mode-nonce' ),
                'enabled'   => $this->is_dark_mode_enabled(),
                'auto'      => $this->is_auto_dark_mode(),
                'darkClass' => 'dark-mode',
                'lightClass' => 'light-mode',
                'cookieName' => 'aqualuxe_dark_mode',
                'cookieExpiry' => 30, // Days
                'toggleText' => [
                    'dark'  => esc_html__( 'Switch to Light Mode', 'aqualuxe' ),
                    'light' => esc_html__( 'Switch to Dark Mode', 'aqualuxe' ),
                ],
                'toggleIcon' => [
                    'dark'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>',
                    'light' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>',
                ],
            ]
        );
    }

    /**
     * Add dark mode toggle script
     */
    public function add_dark_mode_toggle_script() {
        if ( ! $this->is_dark_mode_enabled() ) {
            return;
        }
        
        ?>
        <script>
            // Dark mode initialization script
            (function() {
                // Function to get cookie value
                function getCookie(name) {
                    const value = `; ${document.cookie}`;
                    const parts = value.split(`; ${name}=`);
                    if (parts.length === 2) return parts.pop().split(';').shift();
                    return null;
                }
                
                // Function to set dark mode
                function setDarkMode(isDark) {
                    if (isDark) {
                        document.documentElement.classList.add('dark-mode');
                        document.documentElement.classList.remove('light-mode');
                    } else {
                        document.documentElement.classList.add('light-mode');
                        document.documentElement.classList.remove('dark-mode');
                    }
                }
                
                // Check if dark mode is enabled
                const darkModeEnabled = <?php echo $this->is_dark_mode_enabled() ? 'true' : 'false'; ?>;
                
                if (darkModeEnabled) {
                    // Check if auto dark mode is enabled
                    const autoDarkMode = <?php echo $this->is_auto_dark_mode() ? 'true' : 'false'; ?>;
                    
                    // Get dark mode preference from cookie
                    const darkModeCookie = getCookie('aqualuxe_dark_mode');
                    
                    // Set dark mode based on preference
                    if (darkModeCookie !== null) {
                        setDarkMode(darkModeCookie === 'dark');
                    } else if (autoDarkMode) {
                        // Check if user prefers dark mode
                        const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        setDarkMode(prefersDarkMode);
                    }
                }
            })();
        </script>
        <?php
    }

    /**
     * Add dark mode toggle
     */
    public function add_dark_mode_toggle() {
        if ( ! $this->is_dark_mode_enabled() ) {
            return;
        }
        
        // Get dark mode toggle template
        Template::get_template_part( 'templates/global/dark-mode-toggle' );
    }

    /**
     * Add dark mode menu
     */
    public function add_dark_mode_menu() {
        add_submenu_page(
            'aqualuxe',
            esc_html__( 'Dark Mode', 'aqualuxe' ),
            esc_html__( 'Dark Mode', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-dark-mode',
            [ $this, 'dark_mode_settings_page' ]
        );
    }

    /**
     * Register dark mode settings
     */
    public function register_dark_mode_settings() {
        register_setting(
            'aqualuxe_dark_mode_settings',
            'aqualuxe_dark_mode_settings',
            [
                'sanitize_callback' => [ $this, 'sanitize_dark_mode_settings' ],
            ]
        );
    }

    /**
     * Sanitize dark mode settings
     *
     * @param array $settings Dark mode settings
     * @return array
     */
    public function sanitize_dark_mode_settings( $settings ) {
        // Sanitize dark mode settings
        return $settings;
    }

    /**
     * Dark mode settings page
     */
    public function dark_mode_settings_page() {
        // Dark mode settings page content
        include AQUALUXE_TEMPLATES_DIR . 'admin/dark-mode-settings.php';
    }

    /**
     * AJAX toggle dark mode
     */
    public function ajax_toggle_dark_mode() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-dark-mode-nonce' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        
        // Check mode
        if ( ! isset( $_POST['mode'] ) ) {
            wp_send_json_error( 'Invalid mode' );
        }
        
        // Get mode
        $mode = sanitize_text_field( wp_unslash( $_POST['mode'] ) );
        
        // Set cookie
        setcookie( 'aqualuxe_dark_mode', $mode, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
        
        // Send success
        wp_send_json_success( [
            'mode' => $mode,
        ] );
    }

    /**
     * Check if dark mode is enabled
     *
     * @return bool
     */
    public function is_dark_mode_enabled() {
        $theme_options = get_option( 'aqualuxe_theme_options', [] );
        
        return isset( $theme_options['enable_dark_mode'] ) && $theme_options['enable_dark_mode'];
    }

    /**
     * Check if auto dark mode is enabled
     *
     * @return bool
     */
    public function is_auto_dark_mode() {
        $theme_options = get_option( 'aqualuxe_theme_options', [] );
        
        return isset( $theme_options['auto_dark_mode'] ) && $theme_options['auto_dark_mode'];
    }

    /**
     * Get dark mode preference
     *
     * @return string
     */
    public function get_dark_mode_preference() {
        // Check if dark mode is enabled
        if ( ! $this->is_dark_mode_enabled() ) {
            return 'light';
        }
        
        // Check if cookie is set
        if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
            return sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_dark_mode'] ) );
        }
        
        // Check if auto dark mode is enabled
        if ( $this->is_auto_dark_mode() ) {
            // We can't detect user preference server-side, so default to light
            return 'light';
        }
        
        // Default to light mode
        return 'light';
    }

    /**
     * Check if dark mode is active
     *
     * @return bool
     */
    public function is_dark_mode_active() {
        return $this->get_dark_mode_preference() === 'dark';
    }
}