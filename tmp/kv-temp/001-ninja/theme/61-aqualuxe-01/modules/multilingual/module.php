<?php
/**
 * Multilingual Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Multilingual
 * @since 1.0.0
 *
 * Module Name: Multilingual
 * Description: Adds multilingual functionality to the theme.
 * Version: 1.0.0
 * Author: AquaLuxe
 * Author URI: https://aqualuxe.com
 * Module URI: https://aqualuxe.com/modules/multilingual
 * Requires: 1.0.0
 * Tags: multilingual, language, translation, wpml, polylang
 */

namespace AquaLuxe\Modules\Multilingual;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Multilingual Module class
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
        // Add language switcher to header
        add_action( 'aqualuxe_header_actions', [ $this, 'language_switcher' ] );

        // Add language switcher to footer
        add_action( 'aqualuxe_footer_info', [ $this, 'language_switcher_footer' ] );

        // Add language switcher to mobile menu
        add_action( 'aqualuxe_mobile_menu_after', [ $this, 'language_switcher_mobile' ] );

        // Add language switcher to customizer
        add_action( 'customize_register', [ $this, 'customize_register' ] );

        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Filter body classes
        add_filter( 'body_class', [ $this, 'body_class' ] );
    }

    /**
     * Add language switcher to header
     */
    public function language_switcher() {
        // Return if language switcher is disabled
        if ( ! get_theme_mod( 'aqualuxe_language_switcher_header', true ) ) {
            return;
        }

        // Return if no language switcher is available
        if ( ! $this->is_multilingual_active() ) {
            return;
        }
        ?>
        <div class="language-switcher">
            <?php echo $this->get_language_switcher(); ?>
        </div>
        <?php
    }

    /**
     * Add language switcher to footer
     */
    public function language_switcher_footer() {
        // Return if language switcher is disabled
        if ( ! get_theme_mod( 'aqualuxe_language_switcher_footer', true ) ) {
            return;
        }

        // Return if no language switcher is available
        if ( ! $this->is_multilingual_active() ) {
            return;
        }
        ?>
        <div class="language-switcher-footer">
            <?php echo $this->get_language_switcher(); ?>
        </div>
        <?php
    }

    /**
     * Add language switcher to mobile menu
     */
    public function language_switcher_mobile() {
        // Return if language switcher is disabled
        if ( ! get_theme_mod( 'aqualuxe_language_switcher_mobile', true ) ) {
            return;
        }

        // Return if no language switcher is available
        if ( ! $this->is_multilingual_active() ) {
            return;
        }
        ?>
        <div class="language-switcher-mobile">
            <?php echo $this->get_language_switcher(); ?>
        </div>
        <?php
    }

    /**
     * Add language switcher to customizer
     *
     * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager instance.
     */
    public function customize_register( $wp_customize ) {
        // Add multilingual section
        $wp_customize->add_section(
            'aqualuxe_multilingual',
            [
                'title'       => esc_html__( 'Multilingual', 'aqualuxe' ),
                'description' => esc_html__( 'Multilingual settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 60,
            ]
        );

        // Add language switcher header setting
        $wp_customize->add_setting(
            'aqualuxe_language_switcher_header',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        // Add language switcher header control
        $wp_customize->add_control(
            'aqualuxe_language_switcher_header',
            [
                'label'    => esc_html__( 'Show Language Switcher in Header', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'settings' => 'aqualuxe_language_switcher_header',
                'type'     => 'checkbox',
                'priority' => 10,
            ]
        );

        // Add language switcher footer setting
        $wp_customize->add_setting(
            'aqualuxe_language_switcher_footer',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        // Add language switcher footer control
        $wp_customize->add_control(
            'aqualuxe_language_switcher_footer',
            [
                'label'    => esc_html__( 'Show Language Switcher in Footer', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'settings' => 'aqualuxe_language_switcher_footer',
                'type'     => 'checkbox',
                'priority' => 20,
            ]
        );

        // Add language switcher mobile setting
        $wp_customize->add_setting(
            'aqualuxe_language_switcher_mobile',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        // Add language switcher mobile control
        $wp_customize->add_control(
            'aqualuxe_language_switcher_mobile',
            [
                'label'    => esc_html__( 'Show Language Switcher in Mobile Menu', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'settings' => 'aqualuxe_language_switcher_mobile',
                'type'     => 'checkbox',
                'priority' => 30,
            ]
        );

        // Add language switcher style setting
        $wp_customize->add_setting(
            'aqualuxe_language_switcher_style',
            [
                'default'           => 'dropdown',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ]
        );

        // Add language switcher style control
        $wp_customize->add_control(
            'aqualuxe_language_switcher_style',
            [
                'label'    => esc_html__( 'Language Switcher Style', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'settings' => 'aqualuxe_language_switcher_style',
                'type'     => 'select',
                'choices'  => [
                    'dropdown' => esc_html__( 'Dropdown', 'aqualuxe' ),
                    'list'     => esc_html__( 'List', 'aqualuxe' ),
                    'flags'    => esc_html__( 'Flags Only', 'aqualuxe' ),
                ],
                'priority' => 40,
            ]
        );

        // Add language switcher show flags setting
        $wp_customize->add_setting(
            'aqualuxe_language_switcher_show_flags',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        // Add language switcher show flags control
        $wp_customize->add_control(
            'aqualuxe_language_switcher_show_flags',
            [
                'label'    => esc_html__( 'Show Flags', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'settings' => 'aqualuxe_language_switcher_show_flags',
                'type'     => 'checkbox',
                'priority' => 50,
            ]
        );

        // Add language switcher show names setting
        $wp_customize->add_setting(
            'aqualuxe_language_switcher_show_names',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        // Add language switcher show names control
        $wp_customize->add_control(
            'aqualuxe_language_switcher_show_names',
            [
                'label'    => esc_html__( 'Show Language Names', 'aqualuxe' ),
                'section'  => 'aqualuxe_multilingual',
                'settings' => 'aqualuxe_language_switcher_show_names',
                'type'     => 'checkbox',
                'priority' => 60,
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

        // Enqueue multilingual styles
        wp_enqueue_style(
            'aqualuxe-multilingual',
            $get_asset( '/css/modules/multilingual.css' ),
            [ 'aqualuxe-main' ],
            AQUALUXE_VERSION
        );

        // Enqueue multilingual scripts
        wp_enqueue_script(
            'aqualuxe-multilingual',
            $get_asset( '/js/modules/multilingual.js' ),
            [ 'jquery', 'aqualuxe-main' ],
            AQUALUXE_VERSION,
            true
        );

        // Add multilingual data
        wp_localize_script(
            'aqualuxe-multilingual',
            'aqualuxeMultilingual',
            [
                'style'     => get_theme_mod( 'aqualuxe_language_switcher_style', 'dropdown' ),
                'showFlags' => get_theme_mod( 'aqualuxe_language_switcher_show_flags', true ),
                'showNames' => get_theme_mod( 'aqualuxe_language_switcher_show_names', true ),
                'i18n'      => [
                    'selectLanguage' => esc_html__( 'Select Language', 'aqualuxe' ),
                ],
            ]
        );
    }

    /**
     * Add body class
     *
     * @param array $classes The body classes.
     * @return array The modified body classes.
     */
    public function body_class( $classes ) {
        // Add multilingual class if active
        if ( $this->is_multilingual_active() ) {
            $classes[] = 'multilingual-active';
        }

        // Add current language class
        $current_language = $this->get_current_language();
        if ( $current_language ) {
            $classes[] = 'lang-' . sanitize_html_class( $current_language );
        }

        return $classes;
    }

    /**
     * Check if multilingual is active
     *
     * @return bool Whether multilingual is active.
     */
    public function is_multilingual_active() {
        return $this->is_wpml_active() || $this->is_polylang_active();
    }

    /**
     * Check if WPML is active
     *
     * @return bool Whether WPML is active.
     */
    public function is_wpml_active() {
        return class_exists( 'SitePress' );
    }

    /**
     * Check if Polylang is active
     *
     * @return bool Whether Polylang is active.
     */
    public function is_polylang_active() {
        return function_exists( 'pll_current_language' );
    }

    /**
     * Get current language
     *
     * @return string The current language.
     */
    public function get_current_language() {
        if ( $this->is_wpml_active() ) {
            return apply_filters( 'wpml_current_language', '' );
        } elseif ( $this->is_polylang_active() ) {
            return pll_current_language();
        }

        return '';
    }

    /**
     * Get language switcher
     *
     * @return string The language switcher.
     */
    public function get_language_switcher() {
        if ( $this->is_wpml_active() ) {
            ob_start();
            do_action( 'wpml_add_language_selector' );
            return ob_get_clean();
        } elseif ( $this->is_polylang_active() && function_exists( 'pll_the_languages' ) ) {
            $args = [
                'show_flags' => get_theme_mod( 'aqualuxe_language_switcher_show_flags', true ),
                'show_names' => get_theme_mod( 'aqualuxe_language_switcher_show_names', true ),
                'echo'       => 0,
            ];
            return '<div class="language-switcher">' . pll_the_languages( $args ) . '</div>';
        }

        return '';
    }
}

// Initialize the module
new Module();