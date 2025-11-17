<?php
/**
 * AquaLuxe Dark Mode Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Dark Mode Module Class
 */
class AquaLuxe_Module_Dark_Mode {
    /**
     * Constructor
     */
    public function __construct() {
        // Add dark mode toggle to header
        add_action( 'aqualuxe_header_top_bar_right', [ $this, 'dark_mode_toggle' ] );
        
        // Add dark mode class to body
        add_filter( 'body_class', [ $this, 'body_class' ] );
        
        // Register scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        
        // Add AJAX handler
        add_action( 'wp_ajax_aqualuxe_toggle_dark_mode', [ $this, 'toggle_dark_mode_ajax' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_toggle_dark_mode', [ $this, 'toggle_dark_mode_ajax' ] );
    }

    /**
     * Check if dark mode is active
     *
     * @return bool
     */
    public function is_dark_mode() {
        // Check if cookie is set
        if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
            return $_COOKIE['aqualuxe_dark_mode'] === 'true';
        }
        
        // Check if user prefers dark mode
        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'], 'prefers-color-scheme:dark' ) !== false ) {
            return true;
        }
        
        // Default to false
        return false;
    }

    /**
     * Dark mode toggle
     */
    public function dark_mode_toggle() {
        // Check if dark mode is active
        $is_dark = $this->is_dark_mode();
        
        // Output dark mode toggle
        ?>
        <div class="dark-mode-toggle-wrapper">
            <button class="dark-mode-toggle" aria-pressed="<?php echo $is_dark ? 'true' : 'false'; ?>">
                <span class="dark-mode-toggle-icon dark-mode-toggle-icon-light"><?php echo aqualuxe_get_icon( 'sun' ); ?></span>
                <span class="dark-mode-toggle-icon dark-mode-toggle-icon-dark"><?php echo aqualuxe_get_icon( 'moon' ); ?></span>
                <span class="screen-reader-text"><?php esc_html_e( 'Toggle dark mode', 'aqualuxe' ); ?></span>
            </button>
        </div>
        <?php
    }

    /**
     * Add dark mode class to body
     *
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public function body_class( $classes ) {
        if ( $this->is_dark_mode() ) {
            $classes[] = 'dark-mode';
        }
        
        return $classes;
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Get the asset manager instance
        $assets = AquaLuxe_Assets::instance();
        
        // Enqueue script
        $assets->enqueue_script( 'aqualuxe-dark-mode', 'js/dark-mode.js', [ 'jquery' ], true );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'aqualuxe-dark-mode' ),
                'isDarkMode' => $this->is_dark_mode(),
            ]
        );
    }

    /**
     * Toggle dark mode AJAX
     */
    public function toggle_dark_mode_ajax() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-dark-mode', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ] );
        }
        
        // Get dark mode status
        $is_dark = isset( $_POST['is_dark'] ) ? $_POST['is_dark'] === 'true' : false;
        
        // Set cookie
        setcookie( 'aqualuxe_dark_mode', $is_dark ? 'true' : 'false', time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        
        wp_send_json_success( [ 'is_dark' => $is_dark ] );
    }
}