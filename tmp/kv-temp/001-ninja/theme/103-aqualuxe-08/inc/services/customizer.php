<?php
/**
 * Customizer Service
 * 
 * Handles WordPress Customizer integration and theme options
 * following SOLID principles and WordPress best practices.
 *
 * @package AquaLuxe
 * @subpackage Services
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Services;

use AquaLuxe\Core\Base_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Customizer Service Class
 *
 * Responsible for:
 * - Theme customizer panels, sections, and controls
 * - Live preview functionality
 * - Sanitization and validation of customizer inputs
 * - Custom control types
 * - Theme options management
 *
 * @since 1.0.0
 */
class Customizer extends Base_Service {

    /**
     * Customizer configuration
     *
     * @var array
     */
    private $config = array();

    /**
     * Initialize the service.
     *
     * @return void
     */
    public function init(): void {
        $this->setup_config();
        $this->setup_hooks();
    }

    /**
     * Setup customizer configuration.
     *
     * @return void
     */
    private function setup_config(): void {
        $this->config = array(
            'panels' => array(
                'aqualuxe_theme' => array(
                    'title'       => esc_html__( 'AquaLuxe Theme', 'aqualuxe' ),
                    'description' => esc_html__( 'Customize your AquaLuxe theme settings.', 'aqualuxe' ),
                    'priority'    => 30,
                ),
            ),
            'sections' => array(
                'aqualuxe_general' => array(
                    'title'       => esc_html__( 'General Settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_theme',
                    'priority'    => 10,
                ),
                'aqualuxe_colors' => array(
                    'title'       => esc_html__( 'Colors', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_theme',
                    'priority'    => 20,
                ),
                'aqualuxe_typography' => array(
                    'title'       => esc_html__( 'Typography', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_theme',
                    'priority'    => 30,
                ),
                'aqualuxe_layout' => array(
                    'title'       => esc_html__( 'Layout', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_theme',
                    'priority'    => 40,
                ),
                'aqualuxe_performance' => array(
                    'title'       => esc_html__( 'Performance', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_theme',
                    'priority'    => 50,
                ),
            ),
        );

        $this->config = apply_filters( 'aqualuxe_customizer_config', $this->config );
    }

    /**
     * Setup WordPress hooks.
     *
     * @return void
     */
    private function setup_hooks(): void {
        add_action( 'customize_register', array( $this, 'register' ) );
        add_action( 'customize_preview_init', array( $this, 'preview_assets' ) );
        add_action( 'wp_head', array( $this, 'output_custom_css' ) );
        
        // Live preview handlers
        add_action( 'customize_save_after', array( $this, 'clear_cache_after_save' ) );
    }

    /**
     * Register customizer controls.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    public function register( \WP_Customize_Manager $wp_customize ): void {
        $this->register_panels( $wp_customize );
        $this->register_sections( $wp_customize );
        $this->register_settings( $wp_customize );
    }

    /**
     * Register customizer panels.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    private function register_panels( \WP_Customize_Manager $wp_customize ): void {
        foreach ( $this->config['panels'] as $panel_id => $panel_config ) {
            $wp_customize->add_panel( $panel_id, $panel_config );
        }
    }

    /**
     * Register customizer sections.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    private function register_sections( \WP_Customize_Manager $wp_customize ): void {
        foreach ( $this->config['sections'] as $section_id => $section_config ) {
            $wp_customize->add_section( $section_id, $section_config );
        }
    }

    /**
     * Register customizer settings and controls.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    private function register_settings( \WP_Customize_Manager $wp_customize ): void {
        // General Settings
        $this->register_general_settings( $wp_customize );
        
        // Color Settings
        $this->register_color_settings( $wp_customize );
        
        // Typography Settings
        $this->register_typography_settings( $wp_customize );
        
        // Layout Settings
        $this->register_layout_settings( $wp_customize );
        
        // Performance Settings
        $this->register_performance_settings( $wp_customize );
    }

    /**
     * Register general settings.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    private function register_general_settings( \WP_Customize_Manager $wp_customize ): void {
        // Site tagline display
        $wp_customize->add_setting( 'aqualuxe_display_tagline', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( 'aqualuxe_display_tagline', array(
            'label'   => esc_html__( 'Display Site Tagline', 'aqualuxe' ),
            'section' => 'aqualuxe_general',
            'type'    => 'checkbox',
        ) );

        // Copyright text
        $wp_customize->add_setting( 'aqualuxe_copyright_text', array(
            'default'           => sprintf( esc_html__( '© %d AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( 'aqualuxe_copyright_text', array(
            'label'       => esc_html__( 'Copyright Text', 'aqualuxe' ),
            'description' => esc_html__( 'Text displayed in the footer copyright section.', 'aqualuxe' ),
            'section'     => 'aqualuxe_general',
            'type'        => 'text',
        ) );

        // Social media links
        $social_networks = array(
            'facebook'  => esc_html__( 'Facebook', 'aqualuxe' ),
            'twitter'   => esc_html__( 'Twitter', 'aqualuxe' ),
            'instagram' => esc_html__( 'Instagram', 'aqualuxe' ),
            'youtube'   => esc_html__( 'YouTube', 'aqualuxe' ),
            'linkedin'  => esc_html__( 'LinkedIn', 'aqualuxe' ),
        );

        foreach ( $social_networks as $network => $label ) {
            $wp_customize->add_setting( "aqualuxe_social_{$network}", array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            ) );

            $wp_customize->add_control( "aqualuxe_social_{$network}", array(
                'label'   => $label . ' ' . esc_html__( 'URL', 'aqualuxe' ),
                'section' => 'aqualuxe_general',
                'type'    => 'url',
            ) );
        }
    }

    /**
     * Register color settings.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    private function register_color_settings( \WP_Customize_Manager $wp_customize ): void {
        // Primary color
        $wp_customize->add_setting( 'aqualuxe_primary_color', array(
            'default'           => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'aqualuxe_primary_color', array(
            'label'   => esc_html__( 'Primary Color', 'aqualuxe' ),
            'section' => 'aqualuxe_colors',
        ) ) );

        // Secondary color
        $wp_customize->add_setting( 'aqualuxe_secondary_color', array(
            'default'           => '#06b6d4',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'aqualuxe_secondary_color', array(
            'label'   => esc_html__( 'Secondary Color', 'aqualuxe' ),
            'section' => 'aqualuxe_colors',
        ) ) );

        // Accent color
        $wp_customize->add_setting( 'aqualuxe_accent_color', array(
            'default'           => '#f59e0b',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'aqualuxe_accent_color', array(
            'label'   => esc_html__( 'Accent Color', 'aqualuxe' ),
            'section' => 'aqualuxe_colors',
        ) ) );

        // Dark mode colors
        $wp_customize->add_setting( 'aqualuxe_dark_bg_color', array(
            'default'           => '#1f2937',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'aqualuxe_dark_bg_color', array(
            'label'   => esc_html__( 'Dark Mode Background', 'aqualuxe' ),
            'section' => 'aqualuxe_colors',
        ) ) );
    }

    /**
     * Register typography settings.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    private function register_typography_settings( \WP_Customize_Manager $wp_customize ): void {
        // Primary font family
        $wp_customize->add_setting( 'aqualuxe_primary_font', array(
            'default'           => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( 'aqualuxe_primary_font', array(
            'label'   => esc_html__( 'Primary Font Family', 'aqualuxe' ),
            'section' => 'aqualuxe_typography',
            'type'    => 'select',
            'choices' => array(
                'Inter'           => 'Inter',
                'Roboto'          => 'Roboto',
                'Open Sans'       => 'Open Sans',
                'Lato'            => 'Lato',
                'Montserrat'      => 'Montserrat',
                'Playfair Display' => 'Playfair Display',
            ),
        ) );

        // Heading font family
        $wp_customize->add_setting( 'aqualuxe_heading_font', array(
            'default'           => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( 'aqualuxe_heading_font', array(
            'label'   => esc_html__( 'Heading Font Family', 'aqualuxe' ),
            'section' => 'aqualuxe_typography',
            'type'    => 'select',
            'choices' => array(
                'Playfair Display' => 'Playfair Display',
                'Inter'           => 'Inter',
                'Roboto'          => 'Roboto',
                'Montserrat'      => 'Montserrat',
                'Lora'            => 'Lora',
            ),
        ) );

        // Font size scale
        $wp_customize->add_setting( 'aqualuxe_font_scale', array(
            'default'           => '1',
            'sanitize_callback' => array( $this, 'sanitize_font_scale' ),
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( 'aqualuxe_font_scale', array(
            'label'   => esc_html__( 'Font Size Scale', 'aqualuxe' ),
            'section' => 'aqualuxe_typography',
            'type'    => 'select',
            'choices' => array(
                '0.875' => esc_html__( 'Small', 'aqualuxe' ),
                '1'     => esc_html__( 'Normal', 'aqualuxe' ),
                '1.125' => esc_html__( 'Large', 'aqualuxe' ),
                '1.25'  => esc_html__( 'Extra Large', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Register layout settings.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    private function register_layout_settings( \WP_Customize_Manager $wp_customize ): void {
        // Container width
        $wp_customize->add_setting( 'aqualuxe_container_width', array(
            'default'           => 'max-w-7xl',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( 'aqualuxe_container_width', array(
            'label'   => esc_html__( 'Container Width', 'aqualuxe' ),
            'section' => 'aqualuxe_layout',
            'type'    => 'select',
            'choices' => array(
                'max-w-4xl'  => esc_html__( 'Small (896px)', 'aqualuxe' ),
                'max-w-5xl'  => esc_html__( 'Medium (1024px)', 'aqualuxe' ),
                'max-w-6xl'  => esc_html__( 'Large (1152px)', 'aqualuxe' ),
                'max-w-7xl'  => esc_html__( 'Extra Large (1280px)', 'aqualuxe' ),
                'max-w-full' => esc_html__( 'Full Width', 'aqualuxe' ),
            ),
        ) );

        // Sidebar position
        $wp_customize->add_setting( 'aqualuxe_sidebar_position', array(
            'default'           => 'right',
            'sanitize_callback' => array( $this, 'sanitize_sidebar_position' ),
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'aqualuxe_sidebar_position', array(
            'label'   => esc_html__( 'Sidebar Position', 'aqualuxe' ),
            'section' => 'aqualuxe_layout',
            'type'    => 'select',
            'choices' => array(
                'left'  => esc_html__( 'Left', 'aqualuxe' ),
                'right' => esc_html__( 'Right', 'aqualuxe' ),
                'none'  => esc_html__( 'No Sidebar', 'aqualuxe' ),
            ),
        ) );

        // Header layout
        $wp_customize->add_setting( 'aqualuxe_header_layout', array(
            'default'           => 'default',
            'sanitize_callback' => array( $this, 'sanitize_header_layout' ),
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'aqualuxe_header_layout', array(
            'label'   => esc_html__( 'Header Layout', 'aqualuxe' ),
            'section' => 'aqualuxe_layout',
            'type'    => 'select',
            'choices' => array(
                'default'  => esc_html__( 'Default', 'aqualuxe' ),
                'centered' => esc_html__( 'Centered', 'aqualuxe' ),
                'minimal'  => esc_html__( 'Minimal', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Register performance settings.
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    private function register_performance_settings( \WP_Customize_Manager $wp_customize ): void {
        // Enable lazy loading
        $wp_customize->add_setting( 'aqualuxe_lazy_loading', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'aqualuxe_lazy_loading', array(
            'label'       => esc_html__( 'Enable Lazy Loading', 'aqualuxe' ),
            'description' => esc_html__( 'Improves page load speed by loading images only when needed.', 'aqualuxe' ),
            'section'     => 'aqualuxe_performance',
            'type'        => 'checkbox',
        ) );

        // Preload critical resources
        $wp_customize->add_setting( 'aqualuxe_preload_resources', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'aqualuxe_preload_resources', array(
            'label'       => esc_html__( 'Preload Critical Resources', 'aqualuxe' ),
            'description' => esc_html__( 'Preloads fonts and critical CSS for faster rendering.', 'aqualuxe' ),
            'section'     => 'aqualuxe_performance',
            'type'        => 'checkbox',
        ) );
    }

    /**
     * Enqueue customizer preview assets.
     *
     * @return void
     */
    public function preview_assets(): void {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . '/dist/js/customizer.js',
            array( 'jquery', 'customize-preview' ),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Output custom CSS in head.
     *
     * @return void
     */
    public function output_custom_css(): void {
        $custom_css = $this->generate_custom_css();
        
        if ( $custom_css ) {
            echo '<style type="text/css" id="aqualuxe-custom-css">' . $custom_css . '</style>' . "\n";
        }
    }

    /**
     * Generate custom CSS from customizer settings.
     *
     * @return string Custom CSS.
     */
    private function generate_custom_css(): string {
        $css = '';

        // Color variables
        $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0ea5e9' );
        $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#06b6d4' );
        $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#f59e0b' );
        $dark_bg_color = get_theme_mod( 'aqualuxe_dark_bg_color', '#1f2937' );

        $css .= ':root {';
        $css .= '--color-primary: ' . $primary_color . ';';
        $css .= '--color-secondary: ' . $secondary_color . ';';
        $css .= '--color-accent: ' . $accent_color . ';';
        $css .= '--color-dark-bg: ' . $dark_bg_color . ';';
        $css .= '}';

        // Typography
        $primary_font = get_theme_mod( 'aqualuxe_primary_font', 'Inter' );
        $heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );
        $font_scale = get_theme_mod( 'aqualuxe_font_scale', '1' );

        $css .= 'body { font-family: "' . $primary_font . '", sans-serif; font-size: ' . $font_scale . 'rem; }';
        $css .= 'h1, h2, h3, h4, h5, h6 { font-family: "' . $heading_font . '", serif; }';

        return $css;
    }

    /**
     * Clear cache after customizer save.
     *
     * @return void
     */
    public function clear_cache_after_save(): void {
        // Clear any caches that might be affected by customizer changes
        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }
    }

    /**
     * Sanitize font scale.
     *
     * @param string $input Font scale value.
     * @return string Sanitized value.
     */
    public function sanitize_font_scale( string $input ): string {
        $valid_scales = array( '0.875', '1', '1.125', '1.25' );
        return in_array( $input, $valid_scales, true ) ? $input : '1';
    }

    /**
     * Sanitize sidebar position.
     *
     * @param string $input Sidebar position.
     * @return string Sanitized value.
     */
    public function sanitize_sidebar_position( string $input ): string {
        $valid_positions = array( 'left', 'right', 'none' );
        return in_array( $input, $valid_positions, true ) ? $input : 'right';
    }

    /**
     * Sanitize header layout.
     *
     * @param string $input Header layout.
     * @return string Sanitized value.
     */
    public function sanitize_header_layout( string $input ): string {
        $valid_layouts = array( 'default', 'centered', 'minimal' );
        return in_array( $input, $valid_layouts, true ) ? $input : 'default';
    }

    /**
     * Get service name for dependency injection.
     *
     * @return string Service name.
     */
    public function get_service_name(): string {
        return 'customizer';
    }
}