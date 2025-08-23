<?php
/**
 * Dark Mode Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Dark Mode Class
 * 
 * This class handles dark mode functionality for the theme.
 */
class Dark_Mode {
    /**
     * Instance of this class
     *
     * @var Dark_Mode
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Dark_Mode
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
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Add dark mode toggle to header
        add_action( 'aqualuxe_header_main', [ $this, 'add_dark_mode_toggle' ], 30 );
        
        // Add dark mode script
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_dark_mode_script' ] );
        
        // Add dark mode body class
        add_filter( 'body_class', [ $this, 'add_dark_mode_body_class' ] );
    }

    /**
     * Add dark mode toggle
     *
     * @return void
     */
    public function add_dark_mode_toggle() {
        // Check if dark mode toggle is enabled
        if ( ! get_theme_mod( 'aqualuxe_dark_mode_toggle', true ) ) {
            return;
        }
        
        // Get toggle style
        $style = get_theme_mod( 'aqualuxe_dark_mode_toggle_style', 'icon' );
        
        // Get toggle position
        $position = get_theme_mod( 'aqualuxe_dark_mode_toggle_position', 'header' );
        
        // Only show in header if position is header
        if ( $position !== 'header' && current_action() === 'aqualuxe_header_main' ) {
            return;
        }
        
        // Get current mode
        $is_dark_mode = $this->is_dark_mode();
        
        // Toggle classes
        $toggle_classes = [
            'dark-mode-toggle',
            'toggle-style-' . $style,
            $is_dark_mode ? 'dark-mode-active' : '',
        ];
        
        // Toggle attributes
        $toggle_attrs = [
            'id'                => 'dark-mode-toggle',
            'class'             => implode( ' ', array_filter( $toggle_classes ) ),
            'aria-label'        => $is_dark_mode ? esc_attr__( 'Switch to Light Mode', 'aqualuxe' ) : esc_attr__( 'Switch to Dark Mode', 'aqualuxe' ),
            'aria-pressed'      => $is_dark_mode ? 'true' : 'false',
            'data-dark-text'    => esc_attr__( 'Dark Mode', 'aqualuxe' ),
            'data-light-text'   => esc_attr__( 'Light Mode', 'aqualuxe' ),
        ];
        
        // Build attributes string
        $attrs_string = '';
        foreach ( $toggle_attrs as $attr => $value ) {
            $attrs_string .= ' ' . $attr . '="' . $value . '"';
        }
        
        // Output toggle
        echo '<button' . $attrs_string . '>';
        
        if ( $style === 'icon' || $style === 'icon-text' ) {
            echo '<span class="toggle-icon light-icon" aria-hidden="true">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
            echo '</span>';
            
            echo '<span class="toggle-icon dark-icon" aria-hidden="true">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
            echo '</span>';
        }
        
        if ( $style === 'text' || $style === 'icon-text' ) {
            echo '<span class="toggle-text">';
            echo $is_dark_mode ? esc_html__( 'Light Mode', 'aqualuxe' ) : esc_html__( 'Dark Mode', 'aqualuxe' );
            echo '</span>';
        }
        
        if ( $style === 'switch' ) {
            echo '<span class="toggle-switch" aria-hidden="true">';
            echo '<span class="toggle-track"></span>';
            echo '<span class="toggle-thumb"></span>';
            echo '</span>';
        }
        
        echo '</button>';
    }

    /**
     * Enqueue dark mode script
     *
     * @return void
     */
    public function enqueue_dark_mode_script() {
        // Check if dark mode toggle is enabled
        if ( ! get_theme_mod( 'aqualuxe_dark_mode_toggle', true ) ) {
            return;
        }
        
        // Enqueue dark mode script
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_ASSETS_URI . 'js/dark-mode.js',
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            [
                'cookieName'    => 'aqualuxe_dark_mode',
                'cookieExpiry'  => 30, // Days
                'defaultDark'   => get_theme_mod( 'aqualuxe_dark_mode_default', false ),
                'autoDetect'    => get_theme_mod( 'aqualuxe_dark_mode_auto_detect', true ),
                'toggleText'    => [
                    'dark'  => esc_html__( 'Light Mode', 'aqualuxe' ),
                    'light' => esc_html__( 'Dark Mode', 'aqualuxe' ),
                ],
            ]
        );
    }

    /**
     * Add dark mode body class
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function add_dark_mode_body_class( $classes ) {
        if ( $this->is_dark_mode() ) {
            $classes[] = 'dark-mode';
        }
        
        return $classes;
    }

    /**
     * Check if dark mode is enabled
     *
     * @return boolean
     */
    public function is_dark_mode() {
        // Check if dark mode toggle is enabled
        if ( ! get_theme_mod( 'aqualuxe_dark_mode_toggle', true ) ) {
            return false;
        }
        
        // Check user preference from cookie
        if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
            return $_COOKIE['aqualuxe_dark_mode'] === 'true';
        }
        
        // Check theme default
        return get_theme_mod( 'aqualuxe_dark_mode_default', false );
    }

    /**
     * Get dark mode toggle position
     *
     * @return string
     */
    public function get_toggle_position() {
        return get_theme_mod( 'aqualuxe_dark_mode_toggle_position', 'header' );
    }

    /**
     * Get dark mode toggle style
     *
     * @return string
     */
    public function get_toggle_style() {
        return get_theme_mod( 'aqualuxe_dark_mode_toggle_style', 'icon' );
    }
}