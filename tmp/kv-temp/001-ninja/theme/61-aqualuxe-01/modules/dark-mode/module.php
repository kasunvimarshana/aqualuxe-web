<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Dark_Mode
 * @since 1.0.0
 *
 * Module Name: Dark Mode
 * Description: Adds dark mode functionality to the theme.
 * Version: 1.0.0
 * Author: AquaLuxe
 * Author URI: https://aqualuxe.com
 * Module URI: https://aqualuxe.com/modules/dark-mode
 * Requires: 1.0.0
 * Tags: dark mode, theme, appearance
 */

namespace AquaLuxe\Modules\Dark_Mode;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Dark Mode Module class
 */
class Module {
    /**
     * Constructor
     */
    public function __construct() {
        // Register hooks
        $this->register_hooks();
    }

    /**
     * Register hooks
     */
    public function register_hooks() {
        // Add body class
        add_filter( 'body_class', [ $this, 'body_class' ] );

        // Add dark mode toggle to customizer
        add_action( 'customize_register', [ $this, 'customize_register' ] );

        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Add dark mode toggle to header
        add_action( 'aqualuxe_header_actions', [ $this, 'dark_mode_toggle' ] );
    }

    /**
     * Add body class
     *
     * @param array $classes The body classes.
     * @return array The modified body classes.
     */
    public function body_class( $classes ) {
        // Add dark mode class if enabled
        if ( $this->is_dark_mode() ) {
            $classes[] = 'dark-mode';
        }

        return $classes;
    }

    /**
     * Add dark mode toggle to customizer
     *
     * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager instance.
     */
    public function customize_register( $wp_customize ) {
        // Add dark mode section
        $wp_customize->add_section(
            'aqualuxe_dark_mode',
            [
                'title'       => esc_html__( 'Dark Mode', 'aqualuxe' ),
                'description' => esc_html__( 'Dark mode settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 50,
            ]
        );

        // Add dark mode default setting
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_default',
            [
                'default'           => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        // Add dark mode default control
        $wp_customize->add_control(
            'aqualuxe_dark_mode_default',
            [
                'label'    => esc_html__( 'Enable Dark Mode by Default', 'aqualuxe' ),
                'section'  => 'aqualuxe_dark_mode',
                'settings' => 'aqualuxe_dark_mode_default',
                'type'     => 'checkbox',
                'priority' => 10,
            ]
        );

        // Add dark mode toggle setting
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_toggle',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        // Add dark mode toggle control
        $wp_customize->add_control(
            'aqualuxe_dark_mode_toggle',
            [
                'label'    => esc_html__( 'Show Dark Mode Toggle', 'aqualuxe' ),
                'section'  => 'aqualuxe_dark_mode',
                'settings' => 'aqualuxe_dark_mode_toggle',
                'type'     => 'checkbox',
                'priority' => 20,
            ]
        );

        // Add dark mode remember setting
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_remember',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        // Add dark mode remember control
        $wp_customize->add_control(
            'aqualuxe_dark_mode_remember',
            [
                'label'    => esc_html__( 'Remember Dark Mode Preference', 'aqualuxe' ),
                'section'  => 'aqualuxe_dark_mode',
                'settings' => 'aqualuxe_dark_mode_remember',
                'type'     => 'checkbox',
                'priority' => 30,
            ]
        );
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Get the asset manifest
        $manifest_path = AQUALUXE_ASSETS_DIR . 'mix-manifest.json';
        $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];

        // Helper function to get versioned asset URL
        $get_asset = function( $path ) use ( $manifest ) {
            $versioned_path = isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
            return AQUALUXE_ASSETS_URI . ltrim( $versioned_path, '/' );
        };

        // Enqueue dark mode styles
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            $get_asset( '/css/modules/dark-mode.css' ),
            [ 'aqualuxe-main' ],
            AQUALUXE_VERSION
        );

        // Enqueue dark mode scripts
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            $get_asset( '/js/modules/dark-mode.js' ),
            [ 'jquery', 'aqualuxe-main' ],
            AQUALUXE_VERSION,
            true
        );

        // Add dark mode data
        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            [
                'default'  => get_theme_mod( 'aqualuxe_dark_mode_default', false ),
                'remember' => get_theme_mod( 'aqualuxe_dark_mode_remember', true ),
                'i18n'     => [
                    'darkMode'  => esc_html__( 'Dark Mode', 'aqualuxe' ),
                    'lightMode' => esc_html__( 'Light Mode', 'aqualuxe' ),
                ],
            ]
        );
    }

    /**
     * Add dark mode toggle to header
     */
    public function dark_mode_toggle() {
        // Return if dark mode toggle is disabled
        if ( ! get_theme_mod( 'aqualuxe_dark_mode_toggle', true ) ) {
            return;
        }
        ?>
        <div class="dark-mode-toggle">
            <button class="dark-mode-button" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
                <i class="fas fa-moon dark-icon" aria-hidden="true"></i>
                <i class="fas fa-sun light-icon" aria-hidden="true"></i>
            </button>
        </div>
        <?php
    }

    /**
     * Check if dark mode is enabled
     *
     * @return bool Whether dark mode is enabled.
     */
    public function is_dark_mode() {
        $default = get_theme_mod( 'aqualuxe_dark_mode_default', false );
        $cookie = isset( $_COOKIE['aqualuxe_dark_mode'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_dark_mode'] ) ) : '';

        if ( $cookie === 'true' ) {
            return true;
        } elseif ( $cookie === 'false' ) {
            return false;
        }

        return $default;
    }
}

// Initialize the module
new Module();