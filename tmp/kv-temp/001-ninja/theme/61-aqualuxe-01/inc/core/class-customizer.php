<?php
/**
 * Customizer class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Customizer class
 */
class Customizer {
    /**
     * Constructor
     */
    public function __construct() {
        // Register customizer
        add_action( 'customize_register', [ $this, 'register_customizer' ] );
        
        // Enqueue customizer scripts
        add_action( 'customize_preview_init', [ $this, 'customize_preview_js' ] );
        
        // Enqueue customizer controls scripts
        add_action( 'customize_controls_enqueue_scripts', [ $this, 'customize_controls_js' ] );
        
        // Output customizer CSS
        add_action( 'wp_head', [ $this, 'output_customizer_css' ] );
    }

    /**
     * Register customizer
     *
     * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager instance.
     */
    public function register_customizer( $wp_customize ) {
        // Add panels
        $this->add_panels( $wp_customize );
        
        // Add sections
        $this->add_sections( $wp_customize );
        
        // Add settings and controls
        $this->add_settings_and_controls( $wp_customize );
    }

    /**
     * Add panels
     *
     * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager instance.
     */
    private function add_panels( $wp_customize ) {
        // General panel
        $wp_customize->add_panel(
            'aqualuxe_general_panel',
            [
                'title'       => esc_html__( 'General Settings', 'aqualuxe' ),
                'description' => esc_html__( 'General theme settings', 'aqualuxe' ),
                'priority'    => 10,
            ]
        );

        // Header panel
        $wp_customize->add_panel(
            'aqualuxe_header_panel',
            [
                'title'       => esc_html__( 'Header Settings', 'aqualuxe' ),
                'description' => esc_html__( 'Header settings', 'aqualuxe' ),
                'priority'    => 20,
            ]
        );

        // Footer panel
        $wp_customize->add_panel(
            'aqualuxe_footer_panel',
            [
                'title'       => esc_html__( 'Footer Settings', 'aqualuxe' ),
                'description' => esc_html__( 'Footer settings', 'aqualuxe' ),
                'priority'    => 30,
            ]
        );

        // Blog panel
        $wp_customize->add_panel(
            'aqualuxe_blog_panel',
            [
                'title'       => esc_html__( 'Blog Settings', 'aqualuxe' ),
                'description' => esc_html__( 'Blog settings', 'aqualuxe' ),
                'priority'    => 40,
            ]
        );

        // WooCommerce panel
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_panel(
                'aqualuxe_woocommerce_panel',
                [
                    'title'       => esc_html__( 'WooCommerce Settings', 'aqualuxe' ),
                    'description' => esc_html__( 'WooCommerce settings', 'aqualuxe' ),
                    'priority'    => 50,
                ]
            );
        }

        // Advanced panel
        $wp_customize->add_panel(
            'aqualuxe_advanced_panel',
            [
                'title'       => esc_html__( 'Advanced Settings', 'aqualuxe' ),
                'description' => esc_html__( 'Advanced settings', 'aqualuxe' ),
                'priority'    => 60,
            ]
        );
    }

    /**
     * Add sections
     *
     * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager instance.
     */
    private function add_sections( $wp_customize ) {
        // General sections
        $wp_customize->add_section(
            'aqualuxe_general_colors',
            [
                'title'       => esc_html__( 'Colors', 'aqualuxe' ),
                'description' => esc_html__( 'Color settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 10,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_general_typography',
            [
                'title'       => esc_html__( 'Typography', 'aqualuxe' ),
                'description' => esc_html__( 'Typography settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 20,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_general_layout',
            [
                'title'       => esc_html__( 'Layout', 'aqualuxe' ),
                'description' => esc_html__( 'Layout settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 30,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_general_buttons',
            [
                'title'       => esc_html__( 'Buttons', 'aqualuxe' ),
                'description' => esc_html__( 'Button settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 40,
            ]
        );

        // Header sections
        $wp_customize->add_section(
            'aqualuxe_header_layout',
            [
                'title'       => esc_html__( 'Header Layout', 'aqualuxe' ),
                'description' => esc_html__( 'Header layout settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_header_panel',
                'priority'    => 10,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_header_logo',
            [
                'title'       => esc_html__( 'Logo', 'aqualuxe' ),
                'description' => esc_html__( 'Logo settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_header_panel',
                'priority'    => 20,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_header_navigation',
            [
                'title'       => esc_html__( 'Navigation', 'aqualuxe' ),
                'description' => esc_html__( 'Navigation settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_header_panel',
                'priority'    => 30,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_header_topbar',
            [
                'title'       => esc_html__( 'Top Bar', 'aqualuxe' ),
                'description' => esc_html__( 'Top bar settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_header_panel',
                'priority'    => 40,
            ]
        );

        // Footer sections
        $wp_customize->add_section(
            'aqualuxe_footer_layout',
            [
                'title'       => esc_html__( 'Footer Layout', 'aqualuxe' ),
                'description' => esc_html__( 'Footer layout settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_footer_panel',
                'priority'    => 10,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_footer_widgets',
            [
                'title'       => esc_html__( 'Footer Widgets', 'aqualuxe' ),
                'description' => esc_html__( 'Footer widgets settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_footer_panel',
                'priority'    => 20,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_footer_copyright',
            [
                'title'       => esc_html__( 'Copyright', 'aqualuxe' ),
                'description' => esc_html__( 'Copyright settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_footer_panel',
                'priority'    => 30,
            ]
        );

        // Blog sections
        $wp_customize->add_section(
            'aqualuxe_blog_archive',
            [
                'title'       => esc_html__( 'Blog Archive', 'aqualuxe' ),
                'description' => esc_html__( 'Blog archive settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_blog_panel',
                'priority'    => 10,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_blog_single',
            [
                'title'       => esc_html__( 'Single Post', 'aqualuxe' ),
                'description' => esc_html__( 'Single post settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_blog_panel',
                'priority'    => 20,
            ]
        );

        // WooCommerce sections
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_section(
                'aqualuxe_woocommerce_shop',
                [
                    'title'       => esc_html__( 'Shop', 'aqualuxe' ),
                    'description' => esc_html__( 'Shop settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 10,
                ]
            );

            $wp_customize->add_section(
                'aqualuxe_woocommerce_product',
                [
                    'title'       => esc_html__( 'Product', 'aqualuxe' ),
                    'description' => esc_html__( 'Product settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 20,
                ]
            );

            $wp_customize->add_section(
                'aqualuxe_woocommerce_cart',
                [
                    'title'       => esc_html__( 'Cart', 'aqualuxe' ),
                    'description' => esc_html__( 'Cart settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 30,
                ]
            );

            $wp_customize->add_section(
                'aqualuxe_woocommerce_checkout',
                [
                    'title'       => esc_html__( 'Checkout', 'aqualuxe' ),
                    'description' => esc_html__( 'Checkout settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 40,
                ]
            );
        }

        // Advanced sections
        $wp_customize->add_section(
            'aqualuxe_advanced_scripts',
            [
                'title'       => esc_html__( 'Custom Scripts', 'aqualuxe' ),
                'description' => esc_html__( 'Custom scripts settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_advanced_panel',
                'priority'    => 10,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_advanced_css',
            [
                'title'       => esc_html__( 'Custom CSS', 'aqualuxe' ),
                'description' => esc_html__( 'Custom CSS settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_advanced_panel',
                'priority'    => 20,
            ]
        );

        $wp_customize->add_section(
            'aqualuxe_advanced_performance',
            [
                'title'       => esc_html__( 'Performance', 'aqualuxe' ),
                'description' => esc_html__( 'Performance settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_advanced_panel',
                'priority'    => 30,
            ]
        );
    }

    /**
     * Add settings and controls
     *
     * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager instance.
     */
    private function add_settings_and_controls( $wp_customize ) {
        // General colors
        $wp_customize->add_setting(
            'aqualuxe_primary_color',
            [
                'default'           => '#0077B6',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_primary_color',
                [
                    'label'    => esc_html__( 'Primary Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_primary_color',
                    'priority' => 10,
                ]
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_secondary_color',
            [
                'default'           => '#00B4D8',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_secondary_color',
                [
                    'label'    => esc_html__( 'Secondary Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_secondary_color',
                    'priority' => 20,
                ]
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_accent_color',
            [
                'default'           => '#90E0EF',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_accent_color',
                [
                    'label'    => esc_html__( 'Accent Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_accent_color',
                    'priority' => 30,
                ]
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_text_color',
            [
                'default'           => '#333333',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_text_color',
                [
                    'label'    => esc_html__( 'Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_text_color',
                    'priority' => 40,
                ]
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_heading_color',
            [
                'default'           => '#222222',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_heading_color',
                [
                    'label'    => esc_html__( 'Heading Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_heading_color',
                    'priority' => 50,
                ]
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_link_color',
            [
                'default'           => '#0077B6',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_link_color',
                [
                    'label'    => esc_html__( 'Link Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_link_color',
                    'priority' => 60,
                ]
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_link_hover_color',
            [
                'default'           => '#00B4D8',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_link_hover_color',
                [
                    'label'    => esc_html__( 'Link Hover Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_link_hover_color',
                    'priority' => 70,
                ]
            )
        );

        // General typography
        $wp_customize->add_setting(
            'aqualuxe_body_font_family',
            [
                'default'           => 'Montserrat',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_body_font_family',
            [
                'label'    => esc_html__( 'Body Font Family', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_typography',
                'settings' => 'aqualuxe_body_font_family',
                'type'     => 'select',
                'choices'  => [
                    'Montserrat'      => 'Montserrat',
                    'Open Sans'       => 'Open Sans',
                    'Roboto'          => 'Roboto',
                    'Lato'            => 'Lato',
                    'Poppins'         => 'Poppins',
                    'Source Sans Pro' => 'Source Sans Pro',
                    'Raleway'         => 'Raleway',
                    'Ubuntu'          => 'Ubuntu',
                    'Merriweather'    => 'Merriweather',
                ],
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_heading_font_family',
            [
                'default'           => 'Playfair Display',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_heading_font_family',
            [
                'label'    => esc_html__( 'Heading Font Family', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_typography',
                'settings' => 'aqualuxe_heading_font_family',
                'type'     => 'select',
                'choices'  => [
                    'Playfair Display' => 'Playfair Display',
                    'Montserrat'       => 'Montserrat',
                    'Open Sans'        => 'Open Sans',
                    'Roboto'           => 'Roboto',
                    'Lato'             => 'Lato',
                    'Poppins'          => 'Poppins',
                    'Source Sans Pro'  => 'Source Sans Pro',
                    'Raleway'          => 'Raleway',
                    'Ubuntu'           => 'Ubuntu',
                    'Merriweather'     => 'Merriweather',
                ],
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_body_font_size',
            [
                'default'           => '16',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_body_font_size',
            [
                'label'    => esc_html__( 'Body Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_typography',
                'settings' => 'aqualuxe_body_font_size',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 12,
                    'max'  => 24,
                    'step' => 1,
                ],
                'priority' => 30,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_body_line_height',
            [
                'default'           => '1.6',
                'sanitize_callback' => 'aqualuxe_sanitize_float',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_body_line_height',
            [
                'label'    => esc_html__( 'Body Line Height', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_typography',
                'settings' => 'aqualuxe_body_line_height',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 2,
                    'step' => 0.1,
                ],
                'priority' => 40,
            ]
        );

        // General layout
        $wp_customize->add_setting(
            'aqualuxe_layout',
            [
                'default'           => 'right-sidebar',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_layout',
            [
                'label'    => esc_html__( 'Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_layout',
                'settings' => 'aqualuxe_layout',
                'type'     => 'select',
                'choices'  => [
                    'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
                    'no-sidebar'    => esc_html__( 'No Sidebar', 'aqualuxe' ),
                ],
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_container_width',
            [
                'default'           => '1200',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_container_width',
            [
                'label'    => esc_html__( 'Container Width (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_layout',
                'settings' => 'aqualuxe_container_width',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 960,
                    'max'  => 1600,
                    'step' => 10,
                ],
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_sidebar_width',
            [
                'default'           => '25',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_sidebar_width',
            [
                'label'    => esc_html__( 'Sidebar Width (%)', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_layout',
                'settings' => 'aqualuxe_sidebar_width',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 20,
                    'max'  => 40,
                    'step' => 1,
                ],
                'priority' => 30,
            ]
        );

        // General buttons
        $wp_customize->add_setting(
            'aqualuxe_button_border_radius',
            [
                'default'           => '4',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_button_border_radius',
            [
                'label'    => esc_html__( 'Button Border Radius (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_buttons',
                'settings' => 'aqualuxe_button_border_radius',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 0,
                    'max'  => 50,
                    'step' => 1,
                ],
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_button_padding_y',
            [
                'default'           => '10',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_button_padding_y',
            [
                'label'    => esc_html__( 'Button Vertical Padding (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_buttons',
                'settings' => 'aqualuxe_button_padding_y',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 5,
                    'max'  => 30,
                    'step' => 1,
                ],
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_button_padding_x',
            [
                'default'           => '20',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_button_padding_x',
            [
                'label'    => esc_html__( 'Button Horizontal Padding (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_buttons',
                'settings' => 'aqualuxe_button_padding_x',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 10,
                    'max'  => 50,
                    'step' => 1,
                ],
                'priority' => 30,
            ]
        );

        // Header layout
        $wp_customize->add_setting(
            'aqualuxe_header_layout',
            [
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_header_layout',
            [
                'label'    => esc_html__( 'Header Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_layout',
                'settings' => 'aqualuxe_header_layout',
                'type'     => 'select',
                'choices'  => [
                    'default'      => esc_html__( 'Default', 'aqualuxe' ),
                    'centered'     => esc_html__( 'Centered', 'aqualuxe' ),
                    'transparent'  => esc_html__( 'Transparent', 'aqualuxe' ),
                    'sticky'       => esc_html__( 'Sticky', 'aqualuxe' ),
                    'minimal'      => esc_html__( 'Minimal', 'aqualuxe' ),
                ],
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_header_width',
            [
                'default'           => 'container',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_header_width',
            [
                'label'    => esc_html__( 'Header Width', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_layout',
                'settings' => 'aqualuxe_header_width',
                'type'     => 'select',
                'choices'  => [
                    'container'     => esc_html__( 'Container', 'aqualuxe' ),
                    'container-fluid' => esc_html__( 'Full Width', 'aqualuxe' ),
                ],
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_header_padding_y',
            [
                'default'           => '20',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_header_padding_y',
            [
                'label'    => esc_html__( 'Header Vertical Padding (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_layout',
                'settings' => 'aqualuxe_header_padding_y',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 0,
                    'max'  => 50,
                    'step' => 1,
                ],
                'priority' => 30,
            ]
        );

        // Header logo
        $wp_customize->add_setting(
            'aqualuxe_logo_width',
            [
                'default'           => '200',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_logo_width',
            [
                'label'    => esc_html__( 'Logo Width (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_logo',
                'settings' => 'aqualuxe_logo_width',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 100,
                    'max'  => 500,
                    'step' => 10,
                ],
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_logo_height',
            [
                'default'           => '60',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_logo_height',
            [
                'label'    => esc_html__( 'Logo Height (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_logo',
                'settings' => 'aqualuxe_logo_height',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 30,
                    'max'  => 200,
                    'step' => 10,
                ],
                'priority' => 20,
            ]
        );

        // Header navigation
        $wp_customize->add_setting(
            'aqualuxe_nav_item_spacing',
            [
                'default'           => '20',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_nav_item_spacing',
            [
                'label'    => esc_html__( 'Navigation Item Spacing (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_navigation',
                'settings' => 'aqualuxe_nav_item_spacing',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 10,
                    'max'  => 50,
                    'step' => 1,
                ],
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_nav_font_size',
            [
                'default'           => '16',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_nav_font_size',
            [
                'label'    => esc_html__( 'Navigation Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_navigation',
                'settings' => 'aqualuxe_nav_font_size',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 12,
                    'max'  => 24,
                    'step' => 1,
                ],
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_nav_text_transform',
            [
                'default'           => 'none',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_nav_text_transform',
            [
                'label'    => esc_html__( 'Navigation Text Transform', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_navigation',
                'settings' => 'aqualuxe_nav_text_transform',
                'type'     => 'select',
                'choices'  => [
                    'none'       => esc_html__( 'None', 'aqualuxe' ),
                    'capitalize' => esc_html__( 'Capitalize', 'aqualuxe' ),
                    'uppercase'  => esc_html__( 'Uppercase', 'aqualuxe' ),
                    'lowercase'  => esc_html__( 'Lowercase', 'aqualuxe' ),
                ],
                'priority' => 30,
            ]
        );

        // Header topbar
        $wp_customize->add_setting(
            'aqualuxe_topbar_enable',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_topbar_enable',
            [
                'label'    => esc_html__( 'Enable Top Bar', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_topbar',
                'settings' => 'aqualuxe_topbar_enable',
                'type'     => 'checkbox',
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_topbar_phone',
            [
                'default'           => '+1 (555) 123-4567',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_topbar_phone',
            [
                'label'    => esc_html__( 'Phone Number', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_topbar',
                'settings' => 'aqualuxe_topbar_phone',
                'type'     => 'text',
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_topbar_email',
            [
                'default'           => 'info@aqualuxe.com',
                'sanitize_callback' => 'sanitize_email',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_topbar_email',
            [
                'label'    => esc_html__( 'Email Address', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_topbar',
                'settings' => 'aqualuxe_topbar_email',
                'type'     => 'email',
                'priority' => 30,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_topbar_address',
            [
                'default'           => '123 Aquarium St, Ocean City',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_topbar_address',
            [
                'label'    => esc_html__( 'Address', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_topbar',
                'settings' => 'aqualuxe_topbar_address',
                'type'     => 'text',
                'priority' => 40,
            ]
        );

        // Footer layout
        $wp_customize->add_setting(
            'aqualuxe_footer_layout',
            [
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_footer_layout',
            [
                'label'    => esc_html__( 'Footer Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_layout',
                'settings' => 'aqualuxe_footer_layout',
                'type'     => 'select',
                'choices'  => [
                    'default'      => esc_html__( 'Default', 'aqualuxe' ),
                    'centered'     => esc_html__( 'Centered', 'aqualuxe' ),
                    'minimal'      => esc_html__( 'Minimal', 'aqualuxe' ),
                    'expanded'     => esc_html__( 'Expanded', 'aqualuxe' ),
                ],
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_footer_width',
            [
                'default'           => 'container',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_footer_width',
            [
                'label'    => esc_html__( 'Footer Width', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_layout',
                'settings' => 'aqualuxe_footer_width',
                'type'     => 'select',
                'choices'  => [
                    'container'     => esc_html__( 'Container', 'aqualuxe' ),
                    'container-fluid' => esc_html__( 'Full Width', 'aqualuxe' ),
                ],
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_footer_padding_y',
            [
                'default'           => '60',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_footer_padding_y',
            [
                'label'    => esc_html__( 'Footer Vertical Padding (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_layout',
                'settings' => 'aqualuxe_footer_padding_y',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 20,
                    'max'  => 100,
                    'step' => 10,
                ],
                'priority' => 30,
            ]
        );

        // Footer widgets
        $wp_customize->add_setting(
            'aqualuxe_footer_widgets_columns',
            [
                'default'           => '4',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_footer_widgets_columns',
            [
                'label'    => esc_html__( 'Footer Widgets Columns', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_widgets',
                'settings' => 'aqualuxe_footer_widgets_columns',
                'type'     => 'select',
                'choices'  => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_footer_widgets_spacing',
            [
                'default'           => '30',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_footer_widgets_spacing',
            [
                'label'    => esc_html__( 'Footer Widgets Spacing (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_widgets',
                'settings' => 'aqualuxe_footer_widgets_spacing',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 10,
                    'max'  => 100,
                    'step' => 5,
                ],
                'priority' => 20,
            ]
        );

        // Footer copyright
        $wp_customize->add_setting(
            'aqualuxe_footer_copyright',
            [
                'default'           => '© ' . date( 'Y' ) . ' AquaLuxe. All rights reserved.',
                'sanitize_callback' => 'wp_kses_post',
                'transport'         => 'postMessage',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_footer_copyright',
            [
                'label'    => esc_html__( 'Copyright Text', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_copyright',
                'settings' => 'aqualuxe_footer_copyright',
                'type'     => 'textarea',
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_footer_payment_icons',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_footer_payment_icons',
            [
                'label'    => esc_html__( 'Show Payment Icons', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_copyright',
                'settings' => 'aqualuxe_footer_payment_icons',
                'type'     => 'checkbox',
                'priority' => 20,
            ]
        );

        // Blog archive
        $wp_customize->add_setting(
            'aqualuxe_blog_layout',
            [
                'default'           => 'grid',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_layout',
            [
                'label'    => esc_html__( 'Blog Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_blog_layout',
                'type'     => 'select',
                'choices'  => [
                    'grid'    => esc_html__( 'Grid', 'aqualuxe' ),
                    'list'    => esc_html__( 'List', 'aqualuxe' ),
                    'masonry' => esc_html__( 'Masonry', 'aqualuxe' ),
                ],
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_blog_columns',
            [
                'default'           => '3',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_columns',
            [
                'label'    => esc_html__( 'Blog Columns', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_blog_columns',
                'type'     => 'select',
                'choices'  => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_blog_excerpt_length',
            [
                'default'           => '30',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_excerpt_length',
            [
                'label'    => esc_html__( 'Excerpt Length (words)', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_blog_excerpt_length',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 10,
                    'max'  => 100,
                    'step' => 5,
                ],
                'priority' => 30,
            ]
        );

        // Blog single
        $wp_customize->add_setting(
            'aqualuxe_blog_single_featured_image',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_single_featured_image',
            [
                'label'    => esc_html__( 'Show Featured Image', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_blog_single_featured_image',
                'type'     => 'checkbox',
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_blog_single_author',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_single_author',
            [
                'label'    => esc_html__( 'Show Author', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_blog_single_author',
                'type'     => 'checkbox',
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_blog_single_date',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_single_date',
            [
                'label'    => esc_html__( 'Show Date', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_blog_single_date',
                'type'     => 'checkbox',
                'priority' => 30,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_blog_single_categories',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_single_categories',
            [
                'label'    => esc_html__( 'Show Categories', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_blog_single_categories',
                'type'     => 'checkbox',
                'priority' => 40,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_blog_single_tags',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_single_tags',
            [
                'label'    => esc_html__( 'Show Tags', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_blog_single_tags',
                'type'     => 'checkbox',
                'priority' => 50,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_blog_single_comments',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_single_comments',
            [
                'label'    => esc_html__( 'Show Comments', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_blog_single_comments',
                'type'     => 'checkbox',
                'priority' => 60,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_blog_single_related_posts',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_blog_single_related_posts',
            [
                'label'    => esc_html__( 'Show Related Posts', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_blog_single_related_posts',
                'type'     => 'checkbox',
                'priority' => 70,
            ]
        );

        // WooCommerce shop
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_setting(
                'aqualuxe_shop_layout',
                [
                    'default'           => 'grid',
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_shop_layout',
                [
                    'label'    => esc_html__( 'Shop Layout', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_shop_layout',
                    'type'     => 'select',
                    'choices'  => [
                        'grid'    => esc_html__( 'Grid', 'aqualuxe' ),
                        'list'    => esc_html__( 'List', 'aqualuxe' ),
                        'masonry' => esc_html__( 'Masonry', 'aqualuxe' ),
                    ],
                    'priority' => 10,
                ]
            );

            $wp_customize->add_setting(
                'aqualuxe_shop_columns',
                [
                    'default'           => '3',
                    'sanitize_callback' => 'absint',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_shop_columns',
                [
                    'label'    => esc_html__( 'Shop Columns', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_shop_columns',
                    'type'     => 'select',
                    'choices'  => [
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                    ],
                    'priority' => 20,
                ]
            );

            $wp_customize->add_setting(
                'aqualuxe_shop_products_per_page',
                [
                    'default'           => '12',
                    'sanitize_callback' => 'absint',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_shop_products_per_page',
                [
                    'label'    => esc_html__( 'Products Per Page', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_shop_products_per_page',
                    'type'     => 'number',
                    'input_attrs' => [
                        'min'  => 4,
                        'max'  => 48,
                        'step' => 4,
                    ],
                    'priority' => 30,
                ]
            );

            // WooCommerce product
            $wp_customize->add_setting(
                'aqualuxe_product_gallery_layout',
                [
                    'default'           => 'horizontal',
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_product_gallery_layout',
                [
                    'label'    => esc_html__( 'Product Gallery Layout', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_product_gallery_layout',
                    'type'     => 'select',
                    'choices'  => [
                        'horizontal' => esc_html__( 'Horizontal', 'aqualuxe' ),
                        'vertical'   => esc_html__( 'Vertical', 'aqualuxe' ),
                        'grid'       => esc_html__( 'Grid', 'aqualuxe' ),
                    ],
                    'priority' => 10,
                ]
            );

            $wp_customize->add_setting(
                'aqualuxe_product_sticky_add_to_cart',
                [
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_product_sticky_add_to_cart',
                [
                    'label'    => esc_html__( 'Sticky Add to Cart', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_product_sticky_add_to_cart',
                    'type'     => 'checkbox',
                    'priority' => 20,
                ]
            );

            $wp_customize->add_setting(
                'aqualuxe_product_related_products',
                [
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_product_related_products',
                [
                    'label'    => esc_html__( 'Show Related Products', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_product_related_products',
                    'type'     => 'checkbox',
                    'priority' => 30,
                ]
            );

            $wp_customize->add_setting(
                'aqualuxe_product_upsells',
                [
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_product_upsells',
                [
                    'label'    => esc_html__( 'Show Upsells', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_product_upsells',
                    'type'     => 'checkbox',
                    'priority' => 40,
                ]
            );

            // WooCommerce cart
            $wp_customize->add_setting(
                'aqualuxe_cart_cross_sells',
                [
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_cart_cross_sells',
                [
                    'label'    => esc_html__( 'Show Cross Sells', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_cart',
                    'settings' => 'aqualuxe_cart_cross_sells',
                    'type'     => 'checkbox',
                    'priority' => 10,
                ]
            );

            $wp_customize->add_setting(
                'aqualuxe_cart_coupon',
                [
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_cart_coupon',
                [
                    'label'    => esc_html__( 'Show Coupon Form', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_cart',
                    'settings' => 'aqualuxe_cart_coupon',
                    'type'     => 'checkbox',
                    'priority' => 20,
                ]
            );

            // WooCommerce checkout
            $wp_customize->add_setting(
                'aqualuxe_checkout_layout',
                [
                    'default'           => 'default',
                    'sanitize_callback' => 'sanitize_text_field',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_checkout_layout',
                [
                    'label'    => esc_html__( 'Checkout Layout', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_checkout',
                    'settings' => 'aqualuxe_checkout_layout',
                    'type'     => 'select',
                    'choices'  => [
                        'default'   => esc_html__( 'Default', 'aqualuxe' ),
                        'modern'    => esc_html__( 'Modern', 'aqualuxe' ),
                        'multistep' => esc_html__( 'Multi-step', 'aqualuxe' ),
                    ],
                    'priority' => 10,
                ]
            );

            $wp_customize->add_setting(
                'aqualuxe_checkout_order_notes',
                [
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                ]
            );

            $wp_customize->add_control(
                'aqualuxe_checkout_order_notes',
                [
                    'label'    => esc_html__( 'Show Order Notes', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_checkout',
                    'settings' => 'aqualuxe_checkout_order_notes',
                    'type'     => 'checkbox',
                    'priority' => 20,
                ]
            );
        }

        // Advanced scripts
        $wp_customize->add_setting(
            'aqualuxe_header_scripts',
            [
                'default'           => '',
                'sanitize_callback' => 'aqualuxe_sanitize_js',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_header_scripts',
            [
                'label'    => esc_html__( 'Header Scripts', 'aqualuxe' ),
                'description' => esc_html__( 'Add custom scripts to the header. Will be added before the closing </head> tag.', 'aqualuxe' ),
                'section'  => 'aqualuxe_advanced_scripts',
                'settings' => 'aqualuxe_header_scripts',
                'type'     => 'textarea',
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_footer_scripts',
            [
                'default'           => '',
                'sanitize_callback' => 'aqualuxe_sanitize_js',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_footer_scripts',
            [
                'label'    => esc_html__( 'Footer Scripts', 'aqualuxe' ),
                'description' => esc_html__( 'Add custom scripts to the footer. Will be added before the closing </body> tag.', 'aqualuxe' ),
                'section'  => 'aqualuxe_advanced_scripts',
                'settings' => 'aqualuxe_footer_scripts',
                'type'     => 'textarea',
                'priority' => 20,
            ]
        );

        // Advanced CSS
        $wp_customize->add_setting(
            'aqualuxe_custom_css',
            [
                'default'           => '',
                'sanitize_callback' => 'wp_strip_all_tags',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_custom_css',
            [
                'label'    => esc_html__( 'Custom CSS', 'aqualuxe' ),
                'description' => esc_html__( 'Add custom CSS to customize the theme.', 'aqualuxe' ),
                'section'  => 'aqualuxe_advanced_css',
                'settings' => 'aqualuxe_custom_css',
                'type'     => 'textarea',
                'priority' => 10,
            ]
        );

        // Advanced performance
        $wp_customize->add_setting(
            'aqualuxe_minify_html',
            [
                'default'           => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_minify_html',
            [
                'label'    => esc_html__( 'Minify HTML', 'aqualuxe' ),
                'description' => esc_html__( 'Minify HTML output to improve performance.', 'aqualuxe' ),
                'section'  => 'aqualuxe_advanced_performance',
                'settings' => 'aqualuxe_minify_html',
                'type'     => 'checkbox',
                'priority' => 10,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_defer_js',
            [
                'default'           => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_defer_js',
            [
                'label'    => esc_html__( 'Defer JavaScript', 'aqualuxe' ),
                'description' => esc_html__( 'Add defer attribute to JavaScript files to improve performance.', 'aqualuxe' ),
                'section'  => 'aqualuxe_advanced_performance',
                'settings' => 'aqualuxe_defer_js',
                'type'     => 'checkbox',
                'priority' => 20,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_preload_fonts',
            [
                'default'           => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_preload_fonts',
            [
                'label'    => esc_html__( 'Preload Fonts', 'aqualuxe' ),
                'description' => esc_html__( 'Preload fonts to improve performance.', 'aqualuxe' ),
                'section'  => 'aqualuxe_advanced_performance',
                'settings' => 'aqualuxe_preload_fonts',
                'type'     => 'checkbox',
                'priority' => 30,
            ]
        );

        $wp_customize->add_setting(
            'aqualuxe_lazy_load_images',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            ]
        );

        $wp_customize->add_control(
            'aqualuxe_lazy_load_images',
            [
                'label'    => esc_html__( 'Lazy Load Images', 'aqualuxe' ),
                'description' => esc_html__( 'Lazy load images to improve performance.', 'aqualuxe' ),
                'section'  => 'aqualuxe_advanced_performance',
                'settings' => 'aqualuxe_lazy_load_images',
                'type'     => 'checkbox',
                'priority' => 40,
            ]
        );
    }

    /**
     * Enqueue customizer preview scripts
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . 'js/customizer.js',
            [ 'customize-preview', 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue customizer controls scripts
     */
    public function customize_controls_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            AQUALUXE_ASSETS_URI . 'js/customizer-controls.js',
            [ 'customize-controls', 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Output customizer CSS
     */
    public function output_customizer_css() {
        // Get customizer values
        $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0077B6' );
        $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#00B4D8' );
        $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#90E0EF' );
        $text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
        $heading_color = get_theme_mod( 'aqualuxe_heading_color', '#222222' );
        $link_color = get_theme_mod( 'aqualuxe_link_color', '#0077B6' );
        $link_hover_color = get_theme_mod( 'aqualuxe_link_hover_color', '#00B4D8' );
        $body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Montserrat' );
        $heading_font_family = get_theme_mod( 'aqualuxe_heading_font_family', 'Playfair Display' );
        $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', '16' );
        $body_line_height = get_theme_mod( 'aqualuxe_body_line_height', '1.6' );
        $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
        $sidebar_width = get_theme_mod( 'aqualuxe_sidebar_width', '25' );
        $button_border_radius = get_theme_mod( 'aqualuxe_button_border_radius', '4' );
        $button_padding_y = get_theme_mod( 'aqualuxe_button_padding_y', '10' );
        $button_padding_x = get_theme_mod( 'aqualuxe_button_padding_x', '20' );
        $header_padding_y = get_theme_mod( 'aqualuxe_header_padding_y', '20' );
        $logo_width = get_theme_mod( 'aqualuxe_logo_width', '200' );
        $logo_height = get_theme_mod( 'aqualuxe_logo_height', '60' );
        $nav_item_spacing = get_theme_mod( 'aqualuxe_nav_item_spacing', '20' );
        $nav_font_size = get_theme_mod( 'aqualuxe_nav_font_size', '16' );
        $nav_text_transform = get_theme_mod( 'aqualuxe_nav_text_transform', 'none' );
        $footer_padding_y = get_theme_mod( 'aqualuxe_footer_padding_y', '60' );
        $footer_widgets_spacing = get_theme_mod( 'aqualuxe_footer_widgets_spacing', '30' );
        $custom_css = get_theme_mod( 'aqualuxe_custom_css', '' );

        // Output CSS
        ?>
        <style id="aqualuxe-customizer-css">
            :root {
                --aqualuxe-primary-color: <?php echo esc_attr( $primary_color ); ?>;
                --aqualuxe-secondary-color: <?php echo esc_attr( $secondary_color ); ?>;
                --aqualuxe-accent-color: <?php echo esc_attr( $accent_color ); ?>;
                --aqualuxe-text-color: <?php echo esc_attr( $text_color ); ?>;
                --aqualuxe-heading-color: <?php echo esc_attr( $heading_color ); ?>;
                --aqualuxe-link-color: <?php echo esc_attr( $link_color ); ?>;
                --aqualuxe-link-hover-color: <?php echo esc_attr( $link_hover_color ); ?>;
                --aqualuxe-body-font-family: '<?php echo esc_attr( $body_font_family ); ?>', sans-serif;
                --aqualuxe-heading-font-family: '<?php echo esc_attr( $heading_font_family ); ?>', serif;
                --aqualuxe-body-font-size: <?php echo esc_attr( $body_font_size ); ?>px;
                --aqualuxe-body-line-height: <?php echo esc_attr( $body_line_height ); ?>;
                --aqualuxe-container-width: <?php echo esc_attr( $container_width ); ?>px;
                --aqualuxe-sidebar-width: <?php echo esc_attr( $sidebar_width ); ?>%;
                --aqualuxe-content-width: calc(100% - <?php echo esc_attr( $sidebar_width ); ?>%);
                --aqualuxe-button-border-radius: <?php echo esc_attr( $button_border_radius ); ?>px;
                --aqualuxe-button-padding-y: <?php echo esc_attr( $button_padding_y ); ?>px;
                --aqualuxe-button-padding-x: <?php echo esc_attr( $button_padding_x ); ?>px;
                --aqualuxe-header-padding-y: <?php echo esc_attr( $header_padding_y ); ?>px;
                --aqualuxe-logo-width: <?php echo esc_attr( $logo_width ); ?>px;
                --aqualuxe-logo-height: <?php echo esc_attr( $logo_height ); ?>px;
                --aqualuxe-nav-item-spacing: <?php echo esc_attr( $nav_item_spacing ); ?>px;
                --aqualuxe-nav-font-size: <?php echo esc_attr( $nav_font_size ); ?>px;
                --aqualuxe-nav-text-transform: <?php echo esc_attr( $nav_text_transform ); ?>;
                --aqualuxe-footer-padding-y: <?php echo esc_attr( $footer_padding_y ); ?>px;
                --aqualuxe-footer-widgets-spacing: <?php echo esc_attr( $footer_widgets_spacing ); ?>px;
            }

            body {
                font-family: var(--aqualuxe-body-font-family);
                font-size: var(--aqualuxe-body-font-size);
                line-height: var(--aqualuxe-body-line-height);
                color: var(--aqualuxe-text-color);
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: var(--aqualuxe-heading-font-family);
                color: var(--aqualuxe-heading-color);
            }

            a {
                color: var(--aqualuxe-link-color);
            }

            a:hover {
                color: var(--aqualuxe-link-hover-color);
            }

            .container {
                max-width: var(--aqualuxe-container-width);
            }

            .btn,
            button,
            input[type="button"],
            input[type="reset"],
            input[type="submit"] {
                border-radius: var(--aqualuxe-button-border-radius);
                padding: var(--aqualuxe-button-padding-y) var(--aqualuxe-button-padding-x);
            }

            .site-header {
                padding-top: var(--aqualuxe-header-padding-y);
                padding-bottom: var(--aqualuxe-header-padding-y);
            }

            .site-logo {
                max-width: var(--aqualuxe-logo-width);
                max-height: var(--aqualuxe-logo-height);
            }

            .main-navigation ul li {
                margin-right: var(--aqualuxe-nav-item-spacing);
            }

            .main-navigation ul li a {
                font-size: var(--aqualuxe-nav-font-size);
                text-transform: var(--aqualuxe-nav-text-transform);
            }

            .site-footer {
                padding-top: var(--aqualuxe-footer-padding-y);
                padding-bottom: var(--aqualuxe-footer-padding-y);
            }

            .footer-widgets .widget {
                margin-bottom: var(--aqualuxe-footer-widgets-spacing);
            }

            <?php echo wp_strip_all_tags( $custom_css ); ?>
        </style>
        <?php
    }
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Sanitize float
 *
 * @param float $input The input to sanitize.
 * @return float
 */
function aqualuxe_sanitize_float( $input ) {
    return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}

/**
 * Sanitize JavaScript
 *
 * @param string $input The input to sanitize.
 * @return string
 */
function aqualuxe_sanitize_js( $input ) {
    return $input; // No sanitization for JavaScript
}

// Initialize the class
new Customizer();