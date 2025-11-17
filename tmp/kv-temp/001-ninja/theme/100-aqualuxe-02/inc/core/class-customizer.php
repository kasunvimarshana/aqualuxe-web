<?php
/**
 * Theme Customizer
 *
 * @package AquaLuxe\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customizer class
 */
class AquaLuxe_Customizer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('customize_register', array($this, 'register_customizer_settings'));
        add_action('customize_preview_init', array($this, 'customize_preview_js'));
        add_action('customize_controls_enqueue_scripts', array($this, 'customize_controls_js'));
    }
    
    /**
     * Register customizer settings
     */
    public function register_customizer_settings($wp_customize) {
        
        // Colors Section
        $wp_customize->add_section('aqualuxe_colors', array(
            'title' => __('Colors', 'aqualuxe'),
            'priority' => 30,
        ));
        
        // Color Scheme
        $wp_customize->add_setting('color_scheme', array(
            'default' => 'aqua',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('color_scheme', array(
            'label' => __('Color Scheme', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'type' => 'select',
            'choices' => array(
                'aqua' => __('Aqua', 'aqualuxe'),
                'luxe' => __('Luxe', 'aqualuxe'),
                'ocean' => __('Ocean', 'aqualuxe'),
                'custom' => __('Custom', 'aqualuxe'),
            ),
        ));
        
        // Primary Color
        $wp_customize->add_setting('primary_color', array(
            'default' => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
            'label' => __('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
        )));
        
        // Secondary Color
        $wp_customize->add_setting('secondary_color', array(
            'default' => '#64748b',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
            'label' => __('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
        )));
        
        // Header Section
        $wp_customize->add_section('aqualuxe_header', array(
            'title' => __('Header', 'aqualuxe'),
            'priority' => 40,
        ));
        
        // Header Layout
        $wp_customize->add_setting('header_layout', array(
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('header_layout', array(
            'label' => __('Header Layout', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'select',
            'choices' => array(
                'default' => __('Default', 'aqualuxe'),
                'centered' => __('Centered', 'aqualuxe'),
                'minimal' => __('Minimal', 'aqualuxe'),
            ),
        ));
        
        // Header Background Color
        $wp_customize->add_setting('header_background_color', array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_background_color', array(
            'label' => __('Header Background Color', 'aqualuxe'),
            'section' => 'aqualuxe_header',
        )));
        
        // Header Search
        $wp_customize->add_setting('header_search', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('header_search', array(
            'label' => __('Show Search in Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ));
        
        // Typography Section
        $wp_customize->add_section('aqualuxe_typography', array(
            'title' => __('Typography', 'aqualuxe'),
            'priority' => 50,
        ));
        
        // Body Font
        $wp_customize->add_setting('typography_body_font', array(
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control('typography_body_font', array(
            'label' => __('Body Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => $this->get_font_choices(),
        ));
        
        // Heading Font
        $wp_customize->add_setting('typography_heading_font', array(
            'default' => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control('typography_heading_font', array(
            'label' => __('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => $this->get_font_choices(),
        ));
        
        // Footer Section
        $wp_customize->add_section('aqualuxe_footer', array(
            'title' => __('Footer', 'aqualuxe'),
            'priority' => 60,
        ));
        
        // Footer Widget Areas
        $wp_customize->add_setting('footer_widget_areas', array(
            'default' => 4,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control('footer_widget_areas', array(
            'label' => __('Footer Widget Areas', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'select',
            'choices' => array(
                1 => __('1 Column', 'aqualuxe'),
                2 => __('2 Columns', 'aqualuxe'),
                3 => __('3 Columns', 'aqualuxe'),
                4 => __('4 Columns', 'aqualuxe'),
            ),
        ));
        
        // Footer Copyright
        $wp_customize->add_setting('footer_copyright', array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        ));
        
        $wp_customize->add_control('footer_copyright', array(
            'label' => __('Footer Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'textarea',
        ));
        
        // Layout Section
        $wp_customize->add_section('aqualuxe_layout', array(
            'title' => __('Layout', 'aqualuxe'),
            'priority' => 70,
        ));
        
        // Container Width
        $wp_customize->add_setting('container_width', array(
            'default' => 1200,
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('container_width', array(
            'label' => __('Container Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 960,
                'max' => 1920,
                'step' => 10,
            ),
        ));
        
        // Sidebar Position
        $wp_customize->add_setting('sidebar_position', array(
            'default' => 'right',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('sidebar_position', array(
            'label' => __('Sidebar Position', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'select',
            'choices' => array(
                'left' => __('Left', 'aqualuxe'),
                'right' => __('Right', 'aqualuxe'),
                'none' => __('No Sidebar', 'aqualuxe'),
            ),
        ));
        
        // Blog Layout
        $wp_customize->add_setting('blog_layout', array(
            'default' => 'grid',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('blog_layout', array(
            'label' => __('Blog Layout', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'select',
            'choices' => array(
                'list' => __('List', 'aqualuxe'),
                'grid' => __('Grid', 'aqualuxe'),
                'masonry' => __('Masonry', 'aqualuxe'),
            ),
        ));
        
        // WooCommerce Section (if active)
        if (aqualuxe_is_woocommerce_active()) {
            $wp_customize->add_section('aqualuxe_woocommerce', array(
                'title' => __('WooCommerce', 'aqualuxe'),
                'priority' => 80,
            ));
            
            // Products per row
            $wp_customize->add_setting('woocommerce_products_per_row', array(
                'default' => 4,
                'sanitize_callback' => 'absint',
            ));
            
            $wp_customize->add_control('woocommerce_products_per_row', array(
                'label' => __('Products per Row', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce',
                'type' => 'select',
                'choices' => array(
                    2 => __('2 Columns', 'aqualuxe'),
                    3 => __('3 Columns', 'aqualuxe'),
                    4 => __('4 Columns', 'aqualuxe'),
                    5 => __('5 Columns', 'aqualuxe'),
                ),
            ));
            
            // Products per page
            $wp_customize->add_setting('woocommerce_products_per_page', array(
                'default' => 12,
                'sanitize_callback' => 'absint',
            ));
            
            $wp_customize->add_control('woocommerce_products_per_page', array(
                'label' => __('Products per Page', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce',
                'type' => 'number',
                'input_attrs' => array(
                    'min' => 4,
                    'max' => 48,
                    'step' => 4,
                ),
            ));
        }
    }
    
    /**
     * Get font choices
     */
    private function get_font_choices() {
        return array(
            'Inter' => __('Inter', 'aqualuxe'),
            'Playfair Display' => __('Playfair Display', 'aqualuxe'),
            'Roboto' => __('Roboto', 'aqualuxe'),
            'Open Sans' => __('Open Sans', 'aqualuxe'),
            'Lato' => __('Lato', 'aqualuxe'),
            'Montserrat' => __('Montserrat', 'aqualuxe'),
            'Source Sans Pro' => __('Source Sans Pro', 'aqualuxe'),
            'Poppins' => __('Poppins', 'aqualuxe'),
            'Nunito' => __('Nunito', 'aqualuxe'),
            'Merriweather' => __('Merriweather', 'aqualuxe'),
        );
    }
    
    /**
     * Enqueue customizer preview JavaScript
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            get_template_directory_uri() . '/assets/js/customizer-preview.js',
            array('jquery', 'customize-preview'),
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Enqueue customizer controls JavaScript
     */
    public function customize_controls_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            get_template_directory_uri() . '/assets/js/customizer.js',
            array('jquery', 'customize-controls'),
            AQUALUXE_VERSION,
            true
        );
    }
}

// Initialize customizer
new AquaLuxe_Customizer();