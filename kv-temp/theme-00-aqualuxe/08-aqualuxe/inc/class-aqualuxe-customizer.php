<?php
/**
 * Theme Customizer Class
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Customizer Class
 */
class AquaLuxe_Customizer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('customize_register', array($this, 'register_customizer_settings'));
        add_action('customize_preview_init', array($this, 'customize_preview_js'));
    }
    
    /**
     * Register customizer settings
     */
    public function register_customizer_settings($wp_customize) {
        // Colors Panel
        $this->add_colors_panel($wp_customize);
        
        // Typography Panel
        $this->add_typography_panel($wp_customize);
        
        // Layout Panel
        $this->add_layout_panel($wp_customize);
        
        // Header Panel
        $this->add_header_panel($wp_customize);
        
        // Footer Panel
        $this->add_footer_panel($wp_customize);
        
        // WooCommerce Panel
        if (class_exists('WooCommerce')) {
            $this->add_woocommerce_panel($wp_customize);
        }
    }
    
    /**
     * Add colors panel
     */
    private function add_colors_panel($wp_customize) {
        // Colors Panel
        $wp_customize->add_panel('aqualuxe_colors', array(
            'title'       => esc_html__('AquaLuxe Colors', 'aqualuxe'),
            'description' => esc_html__('Customize the color scheme of your website.', 'aqualuxe'),
            'priority'    => 30,
        ));
        
        // Primary Colors Section
        $wp_customize->add_section('aqualuxe_primary_colors', array(
            'title' => esc_html__('Primary Colors', 'aqualuxe'),
            'panel' => 'aqualuxe_colors',
        ));
        
        // Primary Color
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default'           => '#0066cc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label'   => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_primary_colors',
        )));
        
        // Secondary Color
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default'           => '#14b8a6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label'   => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_primary_colors',
        )));
        
        // Accent Color
        $wp_customize->add_setting('aqualuxe_accent_color', array(
            'default'           => '#f97316',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
            'label'   => esc_html__('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_primary_colors',
        )));
    }
    
    /**
     * Add typography panel
     */
    private function add_typography_panel($wp_customize) {
        // Typography Panel
        $wp_customize->add_panel('aqualuxe_typography', array(
            'title'       => esc_html__('AquaLuxe Typography', 'aqualuxe'),
            'description' => esc_html__('Customize the typography of your website.', 'aqualuxe'),
            'priority'    => 35,
        ));
        
        // Body Typography Section
        $wp_customize->add_section('aqualuxe_body_typography', array(
            'title' => esc_html__('Body Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_typography',
        ));
        
        // Body Font Family
        $wp_customize->add_setting('aqualuxe_body_font_family', array(
            'default'           => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_body_font_family', array(
            'label'   => esc_html__('Body Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_body_typography',
            'type'    => 'select',
            'choices' => array(
                'Inter'            => 'Inter',
                'Roboto'           => 'Roboto',
                'Open Sans'        => 'Open Sans',
                'Lato'             => 'Lato',
                'Source Sans Pro'  => 'Source Sans Pro',
            ),
        ));
        
        // Body Font Size
        $wp_customize->add_setting('aqualuxe_body_font_size', array(
            'default'           => '16',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_body_font_size', array(
            'label'       => esc_html__('Body Font Size (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_body_typography',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        ));
        
        // Heading Typography Section
        $wp_customize->add_section('aqualuxe_heading_typography', array(
            'title' => esc_html__('Heading Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_typography',
        ));
        
        // Heading Font Family
        $wp_customize->add_setting('aqualuxe_heading_font_family', array(
            'default'           => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_heading_font_family', array(
            'label'   => esc_html__('Heading Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_heading_typography',
            'type'    => 'select',
            'choices' => array(
                'Playfair Display' => 'Playfair Display',
                'Merriweather'     => 'Merriweather',
                'Lora'             => 'Lora',
                'Crimson Text'     => 'Crimson Text',
                'PT Serif'         => 'PT Serif',
            ),
        ));
    }
    
    /**
     * Add layout panel
     */
    private function add_layout_panel($wp_customize) {
        // Layout Panel
        $wp_customize->add_panel('aqualuxe_layout', array(
            'title'       => esc_html__('AquaLuxe Layout', 'aqualuxe'),
            'description' => esc_html__('Customize the layout of your website.', 'aqualuxe'),
            'priority'    => 40,
        ));
        
        // General Layout Section
        $wp_customize->add_section('aqualuxe_general_layout', array(
            'title' => esc_html__('General Layout', 'aqualuxe'),
            'panel' => 'aqualuxe_layout',
        ));
        
        // Container Width
        $wp_customize->add_setting('aqualuxe_container_width', array(
            'default'           => '1200',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_container_width', array(
            'label'       => esc_html__('Container Width (px)', 'aqualuxe'),
            'section'     => 'aqualuxe_general_layout',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 1000,
                'max'  => 1600,
                'step' => 50,
            ),
        ));
        
        // Sidebar Layout
        $wp_customize->add_setting('aqualuxe_sidebar_layout', array(
            'default'           => 'right',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_sidebar_layout', array(
            'label'   => esc_html__('Sidebar Layout', 'aqualuxe'),
            'section' => 'aqualuxe_general_layout',
            'type'    => 'select',
            'choices' => array(
                'left'  => esc_html__('Left Sidebar', 'aqualuxe'),
                'right' => esc_html__('Right Sidebar', 'aqualuxe'),
                'none'  => esc_html__('No Sidebar', 'aqualuxe'),
            ),
        ));
    }
    
    /**
     * Add header panel
     */
    private function add_header_panel($wp_customize) {
        // Header Section
        $wp_customize->add_section('aqualuxe_header', array(
            'title'    => esc_html__('Header Settings', 'aqualuxe'),
            'priority' => 45,
        ));
        
        // Sticky Header
        $wp_customize->add_setting('aqualuxe_sticky_header', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_sticky_header', array(
            'label'   => esc_html__('Enable Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'checkbox',
        ));
        
        // Header Layout
        $wp_customize->add_setting('aqualuxe_header_layout', array(
            'default'           => 'centered',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_header_layout', array(
            'label'   => esc_html__('Header Layout', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type'    => 'select',
            'choices' => array(
                'left'     => esc_html__('Logo Left, Menu Right', 'aqualuxe'),
                'centered' => esc_html__('Centered Layout', 'aqualuxe'),
                'stacked'  => esc_html__('Stacked Layout', 'aqualuxe'),
            ),
        ));
    }
    
    /**
     * Add footer panel
     */
    private function add_footer_panel($wp_customize) {
        // Footer Section
        $wp_customize->add_section('aqualuxe_footer', array(
            'title'    => esc_html__('Footer Settings', 'aqualuxe'),
            'priority' => 50,
        ));
        
        // Footer Text
        $wp_customize->add_setting('aqualuxe_footer_text', array(
            'default'           => sprintf(esc_html__('© %s %s. All rights reserved.', 'aqualuxe'), date('Y'), get_bloginfo('name')),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_footer_text', array(
            'label'   => esc_html__('Footer Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'text',
        ));
        
        // Footer Columns
        $wp_customize->add_setting('aqualuxe_footer_columns', array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_footer_columns', array(
            'label'   => esc_html__('Footer Widget Columns', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type'    => 'select',
            'choices' => array(
                '1' => esc_html__('1 Column', 'aqualuxe'),
                '2' => esc_html__('2 Columns', 'aqualuxe'),
                '3' => esc_html__('3 Columns', 'aqualuxe'),
                '4' => esc_html__('4 Columns', 'aqualuxe'),
            ),
        ));
    }
    
    /**
     * Add WooCommerce panel
     */
    private function add_woocommerce_panel($wp_customize) {
        // WooCommerce Panel
        $wp_customize->add_panel('aqualuxe_woocommerce', array(
            'title'       => esc_html__('AquaLuxe WooCommerce', 'aqualuxe'),
            'description' => esc_html__('Customize WooCommerce settings for your store.', 'aqualuxe'),
            'priority'    => 55,
        ));
        
        // Shop Page Section
        $wp_customize->add_section('aqualuxe_shop_page', array(
            'title' => esc_html__('Shop Page', 'aqualuxe'),
            'panel' => 'aqualuxe_woocommerce',
        ));
        
        // Products Per Row
        $wp_customize->add_setting('aqualuxe_products_per_row', array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_products_per_row', array(
            'label'   => esc_html__('Products Per Row', 'aqualuxe'),
            'section' => 'aqualuxe_shop_page',
            'type'    => 'select',
            'choices' => array(
                '2' => esc_html__('2 Products', 'aqualuxe'),
                '3' => esc_html__('3 Products', 'aqualuxe'),
                '4' => esc_html__('4 Products', 'aqualuxe'),
                '5' => esc_html__('5 Products', 'aqualuxe'),
            ),
        ));
        
        // Products Per Page
        $wp_customize->add_setting('aqualuxe_products_per_page', array(
            'default'           => '12',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_products_per_page', array(
            'label'   => esc_html__('Products Per Page', 'aqualuxe'),
            'section' => 'aqualuxe_shop_page',
            'type'    => 'number',
        ));
        
        // Product Page Section
        $wp_customize->add_section('aqualuxe_product_page', array(
            'title' => esc_html__('Product Page', 'aqualuxe'),
            'panel' => 'aqualuxe_woocommerce',
        ));
        
        // Related Products
        $wp_customize->add_setting('aqualuxe_related_products', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_related_products', array(
            'label'   => esc_html__('Show Related Products', 'aqualuxe'),
            'section' => 'aqualuxe_product_page',
            'type'    => 'checkbox',
        ));
    }
    
    /**
     * Binds JS handlers to make Theme Customizer preview reload changes asynchronously
     */
    public function customize_preview_js() {
        wp_enqueue_script('aqualuxe-customizer', get_theme_file_uri('/assets/js/customizer.js'), array('customize-preview'), '1.0.0', true);
    }
}

// Initialize customizer
new AquaLuxe_Customizer();