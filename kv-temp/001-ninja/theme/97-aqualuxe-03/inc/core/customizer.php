<?php
/**
 * Customizer Manager - Advanced WordPress Customizer integration for AquaLuxe theme
 *
 * Handles all theme customizer functionality including:
 * - Custom panels and sections
 * - Advanced control types
 * - Live preview capabilities
 * - Import/export functionality
 * - Theme options management
 * - Customizer API extensions
 * - Selective refresh support
 * - Custom CSS generation
 * 
 * @package AquaLuxe\Core
 * @since 2.0.0
 * @author Kasun Vimarshana
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\Singleton_Interface;
use AquaLuxe\Core\Traits\Singleton_Trait;

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Customizer Manager Class
 * 
 * Implements comprehensive WordPress Customizer integration with
 * advanced controls, live preview, and theme options management.
 * 
 * @since 2.0.0
 */
class Customizer implements Singleton_Interface {
    use Singleton_Trait;

    /**
     * Customizer configuration
     *
     * @var array
     */
    private $config = [];

    /**
     * Registered panels
     *
     * @var array
     */
    private $panels = [];

    /**
     * Registered sections
     *
     * @var array
     */
    private $sections = [];

    /**
     * Registered settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Registered controls
     *
     * @var array
     */
    private $controls = [];

    /**
     * Custom CSS rules
     *
     * @var array
     */
    private $custom_css = [];

    /**
     * Selective refresh settings
     *
     * @var array
     */
    private $selective_refresh = [];

    /**
     * Initialize customizer manager
     *
     * @since 2.0.0
     */
    protected function __construct() {
        $this->load_configuration();
        $this->initialize_hooks();
        $this->setup_default_options();
    }

    /**
     * Load customizer configuration
     *
     * @since 2.0.0
     */
    private function load_configuration(): void {
        $this->config = [
            'theme_support' => [
                'custom-logo',
                'custom-header',
                'custom-background',
                'post-thumbnails',
                'automatic-feed-links',
                'title-tag',
                'html5',
                'customize-selective-refresh-widgets'
            ],
            'selective_refresh' => true,
            'live_preview' => true,
            'export_import' => true,
            'custom_css_output' => true,
            'cache_css' => true,
            'panel_priority_start' => 100,
            'section_priority_start' => 100,
            'control_priority_start' => 100,
            'default_options_prefix' => 'aqualuxe_',
            'css_output_method' => 'inline', // 'inline' or 'file'
            'css_compression' => true
        ];

        // Allow configuration override via filters
        if ( function_exists( 'apply_filters' ) ) {
            /** @var array $config */
            $config = apply_filters( 'aqualuxe_customizer_config', $this->config );
            $this->config = $config;
        }
    }

    /**
     * Initialize WordPress hooks
     *
     * @since 2.0.0
     */
    private function initialize_hooks(): void {
        if ( ! function_exists( 'add_action' ) || ! function_exists( 'add_filter' ) ) {
            return;
        }

        // Theme support
        add_action( 'after_setup_theme', [ $this, 'setup_theme_support' ] );
        
        // Customizer registration
        add_action( 'customize_register', [ $this, 'register_customizer_elements' ] );
        add_action( 'customize_preview_init', [ $this, 'enqueue_preview_scripts' ] );
        add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_control_scripts' ] );
        
        // CSS output
        add_action( 'wp_head', [ $this, 'output_custom_css' ], 99 );
        add_action( 'customize_save_after', [ $this, 'regenerate_css_cache' ] );
        
        // Import/Export
        if ( $this->config['export_import'] ) {
            add_action( 'customize_register', [ $this, 'add_import_export_controls' ] );
            add_action( 'wp_ajax_aqualuxe_export_customizer', [ $this, 'export_customizer_data' ] );
            add_action( 'wp_ajax_aqualuxe_import_customizer', [ $this, 'import_customizer_data' ] );
        }

        // Selective refresh
        if ( $this->config['selective_refresh'] ) {
            add_action( 'customize_register', [ $this, 'setup_selective_refresh' ] );
        }
    }

    /**
     * Setup theme support
     *
     * @since 2.0.0
     */
    public function setup_theme_support(): void {
        foreach ( $this->config['theme_support'] as $feature ) {
            add_theme_support( $feature );
        }

        // Custom logo
        add_theme_support( 'custom-logo', [
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => [ 'site-title', 'site-description' ],
        ] );

        // Custom header
        add_theme_support( 'custom-header', [
            'default-image'      => '',
            'default-text-color' => '000000',
            'width'              => 1920,
            'height'             => 600,
            'flex-width'         => true,
            'flex-height'        => true,
        ] );

        // HTML5 support
        add_theme_support( 'html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'script',
            'style'
        ] );
    }

    /**
     * Setup default theme options
     *
     * @since 2.0.0
     */
    private function setup_default_options(): void {
        $default_options = [
            // Layout options
            'layout_type' => 'full-width',
            'container_width' => 1200,
            'sidebar_width' => 300,
            
            // Typography options
            'body_font_family' => 'Inter',
            'heading_font_family' => 'Inter',
            'body_font_size' => 16,
            'body_line_height' => 1.6,
            
            // Color options
            'primary_color' => '#0073aa',
            'secondary_color' => '#005a87',
            'accent_color' => '#00a0d2',
            'text_color' => '#333333',
            'background_color' => '#ffffff',
            
            // Header options
            'header_layout' => 'default',
            'header_sticky' => true,
            'header_transparent' => false,
            'show_search' => true,
            
            // Footer options
            'footer_layout' => 'default',
            'footer_columns' => 4,
            'show_footer_social' => true,
            'copyright_text' => '',
            
            // Blog options
            'blog_layout' => 'grid',
            'posts_per_page' => 12,
            'show_excerpts' => true,
            'excerpt_length' => 150,
            
            // Performance options
            'enable_lazy_loading' => true,
            'minify_css' => false,
            'minify_js' => false,
            
            // Advanced options
            'custom_css' => '',
            'custom_js' => '',
            'google_analytics' => '',
            'facebook_pixel' => ''
        ];

        // Set default values if not already set
        foreach ( $default_options as $option => $default_value ) {
            $option_name = $this->config['default_options_prefix'] . $option;
            if ( function_exists( 'get_theme_mod' ) && get_theme_mod( $option_name ) === false ) {
                set_theme_mod( $option_name, $default_value );
            }
        }
    }

    /**
     * Register customizer elements
     *
     * @since 2.0.0
     * @param object $wp_customize WordPress Customizer object
     */
    public function register_customizer_elements( $wp_customize ): void {
        $this->register_panels( $wp_customize );
        $this->register_sections( $wp_customize );
        $this->register_settings( $wp_customize );
        $this->register_controls( $wp_customize );
    }

    /**
     * Register customizer panels
     *
     * @since 2.0.0
     * @param object $wp_customize WordPress Customizer object
     */
    private function register_panels( $wp_customize ): void {
        $panels = [
            'aqualuxe_layout' => [
                'title' => 'Layout Settings',
                'description' => 'Customize the layout and structure of your theme.',
                'priority' => $this->config['panel_priority_start'],
            ],
            'aqualuxe_typography' => [
                'title' => 'Typography',
                'description' => 'Customize fonts, sizes, and text styling.',
                'priority' => $this->config['panel_priority_start'] + 10,
            ],
            'aqualuxe_colors' => [
                'title' => 'Colors & Styling',
                'description' => 'Customize colors and visual styling.',
                'priority' => $this->config['panel_priority_start'] + 20,
            ],
            'aqualuxe_header_footer' => [
                'title' => 'Header & Footer',
                'description' => 'Customize header and footer settings.',
                'priority' => $this->config['panel_priority_start'] + 30,
            ],
            'aqualuxe_blog' => [
                'title' => 'Blog Settings',
                'description' => 'Customize blog and post display options.',
                'priority' => $this->config['panel_priority_start'] + 40,
            ],
            'aqualuxe_performance' => [
                'title' => 'Performance',
                'description' => 'Optimize theme performance and loading.',
                'priority' => $this->config['panel_priority_start'] + 50,
            ],
            'aqualuxe_advanced' => [
                'title' => 'Advanced Settings',
                'description' => 'Advanced customization options.',
                'priority' => $this->config['panel_priority_start'] + 60,
            ]
        ];

        foreach ( $panels as $panel_id => $panel_args ) {
            $wp_customize->add_panel( $panel_id, $panel_args );
            $this->panels[ $panel_id ] = $panel_args;
        }
    }

    /**
     * Register customizer sections
     *
     * @since 2.0.0
     * @param object $wp_customize WordPress Customizer object
     */
    private function register_sections( $wp_customize ): void {
        $sections = [
            // Layout sections
            'aqualuxe_general_layout' => [
                'title' => 'General Layout',
                'panel' => 'aqualuxe_layout',
                'priority' => 10,
            ],
            'aqualuxe_container_settings' => [
                'title' => 'Container Settings',
                'panel' => 'aqualuxe_layout',
                'priority' => 20,
            ],
            
            // Typography sections
            'aqualuxe_body_typography' => [
                'title' => 'Body Typography',
                'panel' => 'aqualuxe_typography',
                'priority' => 10,
            ],
            'aqualuxe_heading_typography' => [
                'title' => 'Heading Typography',
                'panel' => 'aqualuxe_typography',
                'priority' => 20,
            ],
            
            // Color sections
            'aqualuxe_primary_colors' => [
                'title' => 'Primary Colors',
                'panel' => 'aqualuxe_colors',
                'priority' => 10,
            ],
            'aqualuxe_text_colors' => [
                'title' => 'Text Colors',
                'panel' => 'aqualuxe_colors',
                'priority' => 20,
            ],
            
            // Header & Footer sections
            'aqualuxe_header_settings' => [
                'title' => 'Header Settings',
                'panel' => 'aqualuxe_header_footer',
                'priority' => 10,
            ],
            'aqualuxe_footer_settings' => [
                'title' => 'Footer Settings',
                'panel' => 'aqualuxe_header_footer',
                'priority' => 20,
            ],
            
            // Blog sections
            'aqualuxe_blog_layout' => [
                'title' => 'Blog Layout',
                'panel' => 'aqualuxe_blog',
                'priority' => 10,
            ],
            'aqualuxe_post_settings' => [
                'title' => 'Post Settings',
                'panel' => 'aqualuxe_blog',
                'priority' => 20,
            ],
            
            // Performance sections
            'aqualuxe_optimization' => [
                'title' => 'Optimization',
                'panel' => 'aqualuxe_performance',
                'priority' => 10,
            ],
            
            // Advanced sections
            'aqualuxe_custom_code' => [
                'title' => 'Custom Code',
                'panel' => 'aqualuxe_advanced',
                'priority' => 10,
            ],
            'aqualuxe_integrations' => [
                'title' => 'Integrations',
                'panel' => 'aqualuxe_advanced',
                'priority' => 20,
            ]
        ];

        foreach ( $sections as $section_id => $section_args ) {
            $wp_customize->add_section( $section_id, $section_args );
            $this->sections[ $section_id ] = $section_args;
        }
    }

    /**
     * Register customizer settings
     *
     * @since 2.0.0
     * @param object $wp_customize WordPress Customizer object
     */
    private function register_settings( $wp_customize ): void {
        $settings = $this->get_customizer_settings();

        foreach ( $settings as $setting_id => $setting_args ) {
            $wp_customize->add_setting( $setting_id, $setting_args );
            $this->settings[ $setting_id ] = $setting_args;
        }
    }

    /**
     * Get customizer settings configuration
     *
     * @since 2.0.0
     * @return array Settings configuration
     */
    private function get_customizer_settings(): array {
        return [
            // Layout settings
            'aqualuxe_layout_type' => [
                'default' => 'full-width',
                'sanitize_callback' => 'sanitize_text_field',
                'transport' => 'refresh',
            ],
            'aqualuxe_container_width' => [
                'default' => 1200,
                'sanitize_callback' => 'absint',
                'transport' => 'postMessage',
            ],
            
            // Typography settings
            'aqualuxe_body_font_family' => [
                'default' => 'Inter',
                'sanitize_callback' => 'sanitize_text_field',
                'transport' => 'postMessage',
            ],
            'aqualuxe_body_font_size' => [
                'default' => 16,
                'sanitize_callback' => 'absint',
                'transport' => 'postMessage',
            ],
            
            // Color settings
            'aqualuxe_primary_color' => [
                'default' => '#0073aa',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport' => 'postMessage',
            ],
            'aqualuxe_text_color' => [
                'default' => '#333333',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport' => 'postMessage',
            ],
            
            // Header settings
            'aqualuxe_header_sticky' => [
                'default' => true,
                'sanitize_callback' => 'rest_sanitize_boolean',
                'transport' => 'refresh',
            ],
            'aqualuxe_show_search' => [
                'default' => true,
                'sanitize_callback' => 'rest_sanitize_boolean',
                'transport' => 'postMessage',
            ],
            
            // Performance settings
            'aqualuxe_enable_lazy_loading' => [
                'default' => true,
                'sanitize_callback' => 'rest_sanitize_boolean',
                'transport' => 'refresh',
            ]
        ];
    }

    /**
     * Register customizer controls
     *
     * @since 2.0.0
     * @param object $wp_customize WordPress Customizer object
     */
    private function register_controls( $wp_customize ): void {
        $controls = [
            // Layout controls
            'aqualuxe_layout_type' => [
                'label' => 'Layout Type',
                'section' => 'aqualuxe_general_layout',
                'type' => 'select',
                'choices' => [
                    'full-width' => 'Full Width',
                    'boxed' => 'Boxed',
                    'fluid' => 'Fluid'
                ],
            ],
            'aqualuxe_container_width' => [
                'label' => 'Container Width (px)',
                'section' => 'aqualuxe_container_settings',
                'type' => 'range',
                'input_attrs' => [
                    'min' => 960,
                    'max' => 1920,
                    'step' => 20,
                ],
            ],
            
            // Typography controls
            'aqualuxe_body_font_family' => [
                'label' => 'Body Font Family',
                'section' => 'aqualuxe_body_typography',
                'type' => 'select',
                'choices' => $this->get_font_choices(),
            ],
            'aqualuxe_body_font_size' => [
                'label' => 'Body Font Size (px)',
                'section' => 'aqualuxe_body_typography',
                'type' => 'range',
                'input_attrs' => [
                    'min' => 12,
                    'max' => 24,
                    'step' => 1,
                ],
            ],
            
            // Color controls
            'aqualuxe_primary_color' => [
                'label' => 'Primary Color',
                'section' => 'aqualuxe_primary_colors',
                'type' => 'color',
            ],
            'aqualuxe_text_color' => [
                'label' => 'Text Color',
                'section' => 'aqualuxe_text_colors',
                'type' => 'color',
            ],
            
            // Header controls
            'aqualuxe_header_sticky' => [
                'label' => 'Sticky Header',
                'section' => 'aqualuxe_header_settings',
                'type' => 'checkbox',
            ],
            'aqualuxe_show_search' => [
                'label' => 'Show Search in Header',
                'section' => 'aqualuxe_header_settings',
                'type' => 'checkbox',
            ],
            
            // Performance controls
            'aqualuxe_enable_lazy_loading' => [
                'label' => 'Enable Lazy Loading',
                'section' => 'aqualuxe_optimization',
                'type' => 'checkbox',
            ]
        ];

        foreach ( $controls as $control_id => $control_args ) {
            $wp_customize->add_control( $control_id, $control_args );
            $this->controls[ $control_id ] = $control_args;
        }
    }

    /**
     * Get font choices for typography controls
     *
     * @since 2.0.0
     * @return array Font choices
     */
    private function get_font_choices(): array {
        return [
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Source Sans Pro' => 'Source Sans Pro',
            'Poppins' => 'Poppins',
            'Nunito' => 'Nunito',
            'Raleway' => 'Raleway',
            'PT Sans' => 'PT Sans',
            'system-ui' => 'System Default',
            'serif' => 'Serif',
            'monospace' => 'Monospace'
        ];
    }

    /**
     * Enqueue customizer preview scripts
     *
     * @since 2.0.0
     */
    public function enqueue_preview_scripts(): void {
        $script_url = function_exists( 'get_template_directory_uri' ) 
            ? get_template_directory_uri() . '/assets/js/customizer-preview.js'
            : '';
            
        if ( $script_url && function_exists( 'wp_enqueue_script' ) ) {
            wp_enqueue_script(
                'aqualuxe-customizer-preview',
                $script_url,
                [ 'customize-preview' ],
                '2.0.0',
                true
            );
        }
    }

    /**
     * Enqueue customizer control scripts
     *
     * @since 2.0.0
     */
    public function enqueue_control_scripts(): void {
        $script_url = function_exists( 'get_template_directory_uri' ) 
            ? get_template_directory_uri() . '/assets/js/customizer-controls.js'
            : '';
            
        if ( $script_url && function_exists( 'wp_enqueue_script' ) ) {
            wp_enqueue_script(
                'aqualuxe-customizer-controls',
                $script_url,
                [ 'customize-controls' ],
                '2.0.0',
                true
            );
        }
    }

    /**
     * Output custom CSS
     *
     * @since 2.0.0
     */
    public function output_custom_css(): void {
        if ( ! $this->config['custom_css_output'] ) {
            return;
        }

        $css = $this->generate_custom_css();
        
        if ( ! empty( $css ) ) {
            echo '<style type="text/css" id="aqualuxe-custom-css">' . "\n";
            echo $css;
            echo "\n" . '</style>' . "\n";
        }
    }

    /**
     * Generate custom CSS based on customizer settings
     *
     * @since 2.0.0
     * @return string Generated CSS
     */
    private function generate_custom_css(): string {
        $css = '';

        // Get customizer values
        $container_width = get_theme_mod( 'aqualuxe_container_width', 1200 );
        $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
        $text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
        $body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Inter' );
        $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', 16 );

        // Container width
        if ( $container_width !== 1200 ) {
            $css .= '.container { max-width: ' . absint( $container_width ) . 'px; }' . "\n";
        }

        // Primary color
        if ( $primary_color !== '#0073aa' ) {
            $css .= ':root { --primary-color: ' . sanitize_hex_color( $primary_color ) . '; }' . "\n";
            $css .= '.btn-primary, .primary-bg { background-color: ' . sanitize_hex_color( $primary_color ) . '; }' . "\n";
        }

        // Text color
        if ( $text_color !== '#333333' ) {
            $css .= 'body { color: ' . sanitize_hex_color( $text_color ) . '; }' . "\n";
        }

        // Typography
        if ( $body_font_family !== 'Inter' ) {
            $css .= 'body { font-family: "' . sanitize_text_field( $body_font_family ) . '", sans-serif; }' . "\n";
        }

        if ( $body_font_size !== 16 ) {
            $css .= 'body { font-size: ' . absint( $body_font_size ) . 'px; }' . "\n";
        }

        // Custom CSS from customizer
        $custom_css = get_theme_mod( 'aqualuxe_custom_css', '' );
        if ( ! empty( $custom_css ) ) {
            $css .= "\n" . '/* Custom CSS */' . "\n";
            $css .= wp_strip_all_tags( $custom_css ) . "\n";
        }

        // Minify CSS if enabled
        if ( $this->config['css_compression'] && ! empty( $css ) ) {
            $css = $this->minify_css( $css );
        }

        return $css;
    }

    /**
     * Minify CSS content
     *
     * @since 2.0.0
     * @param string $css CSS content
     * @return string Minified CSS
     */
    private function minify_css( string $css ): string {
        // Remove comments
        $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
        
        // Remove whitespace
        $css = str_replace( [ "\r\n", "\r", "\n", "\t", '  ', '    ' ], '', $css );
        
        // Remove semicolons before closing braces
        $css = str_replace( ';}', '}', $css );
        
        return trim( $css );
    }

    /**
     * Regenerate CSS cache
     *
     * @since 2.0.0
     */
    public function regenerate_css_cache(): void {
        if ( ! $this->config['cache_css'] ) {
            return;
        }

        // Clear existing cache
        delete_transient( 'aqualuxe_custom_css' );

        // Generate new CSS
        $css = $this->generate_custom_css();
        
        // Cache for 24 hours
        set_transient( 'aqualuxe_custom_css', $css, DAY_IN_SECONDS );
    }

    /**
     * Add import/export controls
     *
     * @since 2.0.0
     * @param object $wp_customize WordPress Customizer object
     */
    public function add_import_export_controls( $wp_customize ): void {
        // Add import/export section
        $wp_customize->add_section( 'aqualuxe_import_export', [
            'title' => 'Import/Export Settings',
            'panel' => 'aqualuxe_advanced',
            'priority' => 30,
        ] );

        // Export button
        $wp_customize->add_setting( 'aqualuxe_export_settings', [
            'sanitize_callback' => '__return_false',
        ] );

        $wp_customize->add_control( 'aqualuxe_export_settings', [
            'label' => 'Export Settings',
            'section' => 'aqualuxe_import_export',
            'type' => 'button',
            'input_attrs' => [
                'value' => 'Export',
                'class' => 'button button-primary',
                'onclick' => 'aqualuxeExportSettings()',
            ],
        ] );
    }

    /**
     * Setup selective refresh
     *
     * @since 2.0.0
     * @param object $wp_customize WordPress Customizer object
     */
    public function setup_selective_refresh( $wp_customize ): void {
        if ( ! isset( $wp_customize->selective_refresh ) ) {
            return;
        }

        // Site title
        $wp_customize->selective_refresh->add_partial( 'blogname', [
            'selector' => '.site-title',
            'render_callback' => function() {
                return get_bloginfo( 'name' );
            },
        ] );

        // Site description
        $wp_customize->selective_refresh->add_partial( 'blogdescription', [
            'selector' => '.site-description',
            'render_callback' => function() {
                return get_bloginfo( 'description' );
            },
        ] );

        // Custom partials for theme options
        $selective_refresh_settings = [
            'aqualuxe_container_width' => '.container',
            'aqualuxe_show_search' => '.header-search',
        ];

        foreach ( $selective_refresh_settings as $setting => $selector ) {
            $wp_customize->selective_refresh->add_partial( $setting, [
                'selector' => $selector,
                'render_callback' => [ $this, 'render_selective_refresh_callback' ],
                'container_inclusive' => false,
            ] );
        }
    }

    /**
     * Selective refresh render callback
     *
     * @since 2.0.0
     * @param string $setting Setting being refreshed
     * @return string Rendered content
     */
    public function render_selective_refresh_callback( $setting = '' ): string {
        // This would be implemented based on specific selective refresh needs
        return '';
    }

    /**
     * Export customizer data
     *
     * @since 2.0.0
     */
    public function export_customizer_data(): void {
        if ( ! current_user_can( 'edit_theme_options' ) ) {
            wp_die( 'Unauthorized access.' );
        }

        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'aqualuxe_customizer_export' ) ) {
            wp_die( 'Security check failed.' );
        }

        $theme_mods = get_theme_mods();
        $export_data = [
            'version' => '2.0.0',
            'timestamp' => time(),
            'theme_mods' => $theme_mods,
        ];

        header( 'Content-Type: application/json' );
        header( 'Content-Disposition: attachment; filename="aqualuxe-customizer-export.json"' );
        echo wp_json_encode( $export_data, JSON_PRETTY_PRINT );
        exit;
    }

    /**
     * Import customizer data
     *
     * @since 2.0.0
     */
    public function import_customizer_data(): void {
        if ( ! current_user_can( 'edit_theme_options' ) ) {
            wp_send_json_error( 'Unauthorized access.' );
        }

        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'aqualuxe_customizer_import' ) ) {
            wp_send_json_error( 'Security check failed.' );
        }

        if ( empty( $_FILES['import_file']['tmp_name'] ) ) {
            wp_send_json_error( 'No file uploaded.' );
        }

        $import_data = json_decode( file_get_contents( $_FILES['import_file']['tmp_name'] ), true );
        
        if ( ! $import_data || ! isset( $import_data['theme_mods'] ) ) {
            wp_send_json_error( 'Invalid import file.' );
        }

        // Import theme mods
        foreach ( $import_data['theme_mods'] as $key => $value ) {
            set_theme_mod( $key, $value );
        }

        wp_send_json_success( 'Settings imported successfully.' );
    }

    /**
     * Get theme modification value
     *
     * @since 2.0.0
     * @param string $name Setting name
     * @param mixed $default Default value
     * @return mixed Theme mod value
     */
    public function get_theme_mod( string $name, $default = false ) {
        return function_exists( 'get_theme_mod' ) 
            ? get_theme_mod( $this->config['default_options_prefix'] . $name, $default )
            : $default;
    }

    /**
     * Set theme modification value
     *
     * @since 2.0.0
     * @param string $name Setting name
     * @param mixed $value Setting value
     */
    public function set_theme_mod( string $name, $value ): void {
        if ( function_exists( 'set_theme_mod' ) ) {
            set_theme_mod( $this->config['default_options_prefix'] . $name, $value );
        }
    }

    /**
     * Get customizer configuration
     *
     * @since 2.0.0
     * @return array Customizer configuration
     */
    public function get_config(): array {
        return $this->config;
    }

    /**
     * Get registered panels
     *
     * @since 2.0.0
     * @return array Registered panels
     */
    public function get_panels(): array {
        return $this->panels;
    }

    /**
     * Get registered sections
     *
     * @since 2.0.0
     * @return array Registered sections
     */
    public function get_sections(): array {
        return $this->sections;
    }

    /**
     * Get registered settings
     *
     * @since 2.0.0
     * @return array Registered settings
     */
    public function get_settings(): array {
        return $this->settings;
    }
}
