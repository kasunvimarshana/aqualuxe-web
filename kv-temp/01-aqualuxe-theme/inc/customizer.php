<?php
/**
 * AquaLuxe Customizer Settings
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Extended Customizer Class
 */
class AquaLuxe_Extended_Customizer {
    
    public function __construct() {
        add_action('customize_register', array($this, 'register_extended_settings'));
        add_action('wp_head', array($this, 'output_custom_css'));
    }
    
    /**
     * Register extended customizer settings
     */
    public function register_extended_settings($wp_customize) {
        
        // Typography Section
        $wp_customize->add_section('aqualuxe_typography', array(
            'title' => __('Typography', 'aqualuxe'),
            'priority' => 35,
        ));
        
        // Body Font
        $wp_customize->add_setting('aqualuxe_body_font', array(
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_body_font', array(
            'label' => __('Body Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => array(
                'Inter' => 'Inter',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
            ),
        ));
        
        // Heading Font
        $wp_customize->add_setting('aqualuxe_heading_font', array(
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_heading_font', array(
            'label' => __('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => array(
                'Inter' => 'Inter',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
                'Playfair Display' => 'Playfair Display',
            ),
        ));
        
        // Layout Section
        $wp_customize->add_section('aqualuxe_layout', array(
            'title' => __('Layout Options', 'aqualuxe'),
            'priority' => 40,
        ));
        
        // Container Width
        $wp_customize->add_setting('aqualuxe_container_width', array(
            'default' => '1200',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_container_width', array(
            'label' => __('Container Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 960,
                'max' => 1400,
                'step' => 20,
            ),
        ));
        
        // Product Columns
        $wp_customize->add_setting('aqualuxe_product_columns', array(
            'default' => '3',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_product_columns', array(
            'label' => __('Product Columns', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'select',
            'choices' => array(
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns',
            ),
        ));
        
        // Hero Section
        $wp_customize->add_section('aqualuxe_hero', array(
            'title' => __('Hero Section', 'aqualuxe'),
            'priority' => 45,
        ));
        
        // Hero Title
        $wp_customize->add_setting('aqualuxe_hero_title', array(
            'default' => __('Premium Ornamental Fish', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_hero_title', array(
            'label' => __('Hero Title', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'text',
        ));
        
        // Hero Subtitle
        $wp_customize->add_setting('aqualuxe_hero_subtitle', array(
            'default' => __('Discover the beauty and elegance of our carefully selected ornamental fish collection.', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        
        $wp_customize->add_control('aqualuxe_hero_subtitle', array(
            'label' => __('Hero Subtitle', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'textarea',
        ));
        
        // Hero Button Text
        $wp_customize->add_setting('aqualuxe_hero_button_text', array(
            'default' => __('Shop Now', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_hero_button_text', array(
            'label' => __('Hero Button Text', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'text',
        ));
        
        // Hero Background Image
        $wp_customize->add_setting('aqualuxe_hero_bg_image', array(
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_hero_bg_image', array(
            'label' => __('Hero Background Image', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
        )));
    }
    
    /**
     * Output custom CSS
     */
    public function output_custom_css() {
        $body_font = get_theme_mod('aqualuxe_body_font', 'Inter');
        $heading_font = get_theme_mod('aqualuxe_heading_font', 'Inter');
        $container_width = get_theme_mod('aqualuxe_container_width', '1200');
        $product_columns = get_theme_mod('aqualuxe_product_columns', '3');
        $hero_bg_image = get_theme_mod('aqualuxe_hero_bg_image');
        
        echo "<style id='aqualuxe-custom-css'>";
        
        // Typography
        echo "body { font-family: '{$body_font}', sans-serif; }";
        echo "h1, h2, h3, h4, h5, h6 { font-family: '{$heading_font}', sans-serif; }";
        
        // Layout
        echo ".container { max-width: {$container_width}px; }";
        echo ".woocommerce ul.products { grid-template-columns: repeat({$product_columns}, 1fr); }";
        
        // Hero Background
        if ($hero_bg_image) {
            echo ".hero-section { background-image: linear-gradient(rgba(0, 119, 190, 0.8), rgba(0, 168, 204, 0.8)), url('{$hero_bg_image}'); }";
        }
        
        echo "</style>";
    }
}

new AquaLuxe_Extended_Customizer();
