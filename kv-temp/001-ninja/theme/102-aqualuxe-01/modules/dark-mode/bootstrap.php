<?php
/**
 * Dark Mode Module Bootstrap
 *
 * @package AquaLuxe
 * @subpackage Modules\DarkMode
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Dark Mode Module Class
 */
class AquaLuxe_Dark_Mode {
    
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;
    
    /**
     * Initialize the module.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_footer', array( $this, 'dark_mode_toggle' ) );
        add_action( 'wp_ajax_aqualuxe_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_toggle_dark_mode', array( $this, 'ajax_toggle_dark_mode' ) );
        add_action( 'init', array( $this, 'init' ) );
    }
    
    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize module.
     */
    public function init() {
        // Set dark mode cookie on initial load if system preference detected
        if ( ! isset( $_COOKIE['aqualuxe-dark-mode'] ) ) {
            $this->detect_system_preference();
        }
    }
    
    /**
     * Enqueue module scripts and styles.
     */
    public function enqueue_scripts() {
        // Get mix manifest for cache busting
        $mix_manifest = $this->get_mix_manifest();
        
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            $this->get_asset_url( 'js/modules/dark-mode.js', $mix_manifest ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script( 'aqualuxe-dark-mode', 'aqualuxe_dark_mode', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_dark_mode_nonce' ),
            'strings'  => array(
                'enable_dark_mode'  => esc_html__( 'Enable Dark Mode', 'aqualuxe' ),
                'disable_dark_mode' => esc_html__( 'Disable Dark Mode', 'aqualuxe' ),
                'light_mode'        => esc_html__( 'Light Mode', 'aqualuxe' ),
                'dark_mode'         => esc_html__( 'Dark Mode', 'aqualuxe' ),
                'system_mode'       => esc_html__( 'System Mode', 'aqualuxe' ),
            ),
        ) );
        
        // Add inline styles for theme transition
        wp_add_inline_style( 'aqualuxe-style', $this->get_transition_styles() );
    }
    
    /**
     * Display dark mode toggle button.
     */
    public function dark_mode_toggle() {
        $current_mode = $this->get_current_mode();
        ?>
        <div id="dark-mode-toggle-wrapper" class="fixed bottom-6 left-6 z-50">
            <button 
                id="dark-mode-toggle" 
                class="dark-mode-toggle bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl p-3 rounded-full transition-all duration-300 border border-gray-200 dark:border-gray-700"
                aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>"
                data-current-mode="<?php echo esc_attr( $current_mode ); ?>"
            >
                <!-- Light mode icon -->
                <svg class="sun-icon w-5 h-5 text-yellow-500 <?php echo $current_mode === 'dark' ? 'hidden' : ''; ?>" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                </svg>
                
                <!-- Dark mode icon -->
                <svg class="moon-icon w-5 h-5 text-blue-500 <?php echo $current_mode === 'light' ? 'hidden' : ''; ?>" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
            </button>
        </div>
        <?php
    }
    
    /**
     * AJAX handler for toggling dark mode.
     */
    public function ajax_toggle_dark_mode() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_dark_mode_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'aqualuxe' ) );
        }
        
        $mode = sanitize_text_field( $_POST['mode'] );
        
        if ( in_array( $mode, array( 'light', 'dark', 'system' ) ) ) {
            // Set cookie for 1 year
            setcookie( 'aqualuxe-dark-mode', $mode, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
            
            wp_send_json_success( array(
                'mode'    => $mode,
                'message' => esc_html__( 'Dark mode preference saved.', 'aqualuxe' ),
            ) );
        }
        
        wp_send_json_error( array(
            'message' => esc_html__( 'Invalid mode specified.', 'aqualuxe' ),
        ) );
    }
    
    /**
     * Get current dark mode setting.
     *
     * @return string
     */
    public function get_current_mode() {
        if ( isset( $_COOKIE['aqualuxe-dark-mode'] ) ) {
            $mode = $_COOKIE['aqualuxe-dark-mode'];
            if ( in_array( $mode, array( 'light', 'dark', 'system' ) ) ) {
                return $mode;
            }
        }
        
        return 'system';
    }
    
    /**
     * Check if dark mode is active.
     *
     * @return bool
     */
    public function is_dark_mode_active() {
        $mode = $this->get_current_mode();
        
        if ( $mode === 'dark' ) {
            return true;
        }
        
        if ( $mode === 'system' ) {
            // Would need JavaScript to detect system preference
            // For server-side rendering, we default to light mode
            return false;
        }
        
        return false;
    }
    
    /**
     * Detect system preference (placeholder for JavaScript implementation).
     */
    private function detect_system_preference() {
        // This would be handled by JavaScript on the frontend
        // Server-side detection is not reliable for this feature
    }
    
    /**
     * Get transition styles for smooth dark mode switching.
     *
     * @return string
     */
    private function get_transition_styles() {
        return '
            .dark-mode-transition,
            .dark-mode-transition *,
            .dark-mode-transition *:before,
            .dark-mode-transition *:after {
                transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease !important;
                transition-delay: 0 !important;
            }
            
            #dark-mode-toggle {
                transition: all 0.3s ease;
            }
            
            #dark-mode-toggle:hover {
                transform: scale(1.05);
            }
            
            #dark-mode-toggle:active {
                transform: scale(0.95);
            }
            
            .sun-icon,
            .moon-icon {
                transition: opacity 0.3s ease, transform 0.3s ease;
            }
            
            @media (prefers-reduced-motion: reduce) {
                .dark-mode-transition,
                .dark-mode-transition *,
                .dark-mode-transition *:before,
                .dark-mode-transition *:after {
                    transition: none !important;
                }
                
                #dark-mode-toggle {
                    transition: none !important;
                }
                
                #dark-mode-toggle:hover {
                    transform: none !important;
                }
            }
        ';
    }
    
    /**
     * Get mix manifest for asset versioning.
     *
     * @return array
     */
    private function get_mix_manifest() {
        $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            return json_decode( file_get_contents( $manifest_path ), true );
        }
        
        return array();
    }
    
    /**
     * Get asset URL with proper versioning.
     *
     * @param string $asset
     * @param array  $manifest
     * @return string
     */
    private function get_asset_url( $asset, $manifest = array() ) {
        $asset_path = '/' . $asset;
        
        if ( isset( $manifest[ $asset_path ] ) ) {
            return AQUALUXE_ASSETS_URI . '/dist' . $manifest[ $asset_path ];
        }
        
        return AQUALUXE_ASSETS_URI . '/dist' . $asset_path;
    }
}

// Initialize dark mode module
AquaLuxe_Dark_Mode::get_instance();

/**
 * Helper function to get dark mode instance.
 *
 * @return AquaLuxe_Dark_Mode
 */
function aqualuxe_dark_mode() {
    return AquaLuxe_Dark_Mode::get_instance();
}

/**
 * Template function to display dark mode toggle.
 */
function aqualuxe_dark_mode_toggle() {
    aqualuxe_dark_mode()->dark_mode_toggle();
}

/**
 * Check if dark mode is active.
 *
 * @return bool
 */
function aqualuxe_is_dark_mode_active() {
    return aqualuxe_dark_mode()->is_dark_mode_active();
}