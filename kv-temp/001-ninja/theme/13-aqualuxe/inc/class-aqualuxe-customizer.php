<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
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
        // Register customizer settings
        add_action('customize_register', array($this, 'register_customizer_settings'));
        
        // Enqueue customizer scripts
        add_action('customize_preview_init', array($this, 'customize_preview_js'));
        
        // Enqueue customizer controls scripts
        add_action('customize_controls_enqueue_scripts', array($this, 'customize_controls_js'));
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function register_customizer_settings($wp_customize) {
        /**
         * Site Identity Section
         */
        $wp_customize->get_section('title_tagline')->priority = 10;
        
        // Site Logo Size
        $wp_customize->add_setting(
            'logo_size',
            array(
                'default' => 'medium',
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_select'),
            )
        );
        
        $wp_customize->add_control(
            'logo_size',
            array(
                'label' => __('Logo Size', 'aqualuxe'),
                'section' => 'title_tagline',
                'type' => 'select',
                'choices' => array(
                    'small' => __('Small', 'aqualuxe'),
                    'medium' => __('Medium', 'aqualuxe'),
                    'large' => __('Large', 'aqualuxe'),
                ),
                'priority' => 9,
            )
        );

        /**
         * Theme Options Panel
         */
        $wp_customize->add_panel(
            'aqualuxe_theme_options',
            array(
                'title' => __('Theme Options', 'aqualuxe'),
                'description' => __('Customize your theme settings', 'aqualuxe'),
                'priority' => 30,
            )
        );

        /**
         * Colors Section
         */
        $wp_customize->add_section(
            'aqualuxe_colors',
            array(
                'title' => __('Colors', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 10,
            )
        );
        
        // Primary Color
        $wp_customize->add_setting(
            'primary_color',
            array(
                'default' => '#0077b6',
                'transport' => 'refresh',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'primary_color',
                array(
                    'label' => __('Primary Color', 'aqualuxe'),
                    'section' => 'aqualuxe_colors',
                )
            )
        );
        
        // Secondary Color
        $wp_customize->add_setting(
            'secondary_color',
            array(
                'default' => '#00b4d8',
                'transport' => 'refresh',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'secondary_color',
                array(
                    'label' => __('Secondary Color', 'aqualuxe'),
                    'section' => 'aqualuxe_colors',
                )
            )
        );
        
        // Text Color
        $wp_customize->add_setting(
            'text_color',
            array(
                'default' => '#333333',
                'transport' => 'refresh',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'text_color',
                array(
                    'label' => __('Text Color', 'aqualuxe'),
                    'section' => 'aqualuxe_colors',
                )
            )
        );
        
        // Link Color
        $wp_customize->add_setting(
            'link_color',
            array(
                'default' => '#0077b6',
                'transport' => 'refresh',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'link_color',
                array(
                    'label' => __('Link Color', 'aqualuxe'),
                    'section' => 'aqualuxe_colors',
                )
            )
        );

        /**
         * Typography Section
         */
        $wp_customize->add_section(
            'aqualuxe_typography',
            array(
                'title' => __('Typography', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 20,
            )
        );
        
        // Heading Font
        $wp_customize->add_setting(
            'heading_font',
            array(
                'default' => 'sans',
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_select'),
            )
        );
        
        $wp_customize->add_control(
            'heading_font',
            array(
                'label' => __('Heading Font', 'aqualuxe'),
                'section' => 'aqualuxe_typography',
                'type' => 'select',
                'choices' => array(
                    'sans' => __('Sans-serif', 'aqualuxe'),
                    'serif' => __('Serif', 'aqualuxe'),
                    'mono' => __('Monospace', 'aqualuxe'),
                ),
            )
        );
        
        // Body Font
        $wp_customize->add_setting(
            'body_font',
            array(
                'default' => 'sans',
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_select'),
            )
        );
        
        $wp_customize->add_control(
            'body_font',
            array(
                'label' => __('Body Font', 'aqualuxe'),
                'section' => 'aqualuxe_typography',
                'type' => 'select',
                'choices' => array(
                    'sans' => __('Sans-serif', 'aqualuxe'),
                    'serif' => __('Serif', 'aqualuxe'),
                    'mono' => __('Monospace', 'aqualuxe'),
                ),
            )
        );
        
        // Base Font Size
        $wp_customize->add_setting(
            'base_font_size',
            array(
                'default' => '16',
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        
        $wp_customize->add_control(
            'base_font_size',
            array(
                'label' => __('Base Font Size (px)', 'aqualuxe'),
                'section' => 'aqualuxe_typography',
                'type' => 'number',
                'input_attrs' => array(
                    'min' => 12,
                    'max' => 24,
                    'step' => 1,
                ),
            )
        );

        /**
         * Layout Section
         */
        $wp_customize->add_section(
            'aqualuxe_layout',
            array(
                'title' => __('Layout', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 30,
            )
        );
        
        // Container Width
        $wp_customize->add_setting(
            'container_width',
            array(
                'default' => '1280',
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        
        $wp_customize->add_control(
            'container_width',
            array(
                'label' => __('Container Width (px)', 'aqualuxe'),
                'section' => 'aqualuxe_layout',
                'type' => 'number',
                'input_attrs' => array(
                    'min' => 960,
                    'max' => 1920,
                    'step' => 10,
                ),
            )
        );
        
        // Sidebar Position
        $wp_customize->add_setting(
            'sidebar_position',
            array(
                'default' => 'right',
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_select'),
            )
        );
        
        $wp_customize->add_control(
            'sidebar_position',
            array(
                'label' => __('Sidebar Position', 'aqualuxe'),
                'section' => 'aqualuxe_layout',
                'type' => 'select',
                'choices' => array(
                    'left' => __('Left', 'aqualuxe'),
                    'right' => __('Right', 'aqualuxe'),
                ),
            )
        );

        /**
         * Header Section
         */
        $wp_customize->add_section(
            'aqualuxe_header',
            array(
                'title' => __('Header', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 40,
            )
        );
        
        // Header Style
        $wp_customize->add_setting(
            'header_style',
            array(
                'default' => 'standard',
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_select'),
            )
        );
        
        $wp_customize->add_control(
            'header_style',
            array(
                'label' => __('Header Style', 'aqualuxe'),
                'section' => 'aqualuxe_header',
                'type' => 'select',
                'choices' => array(
                    'standard' => __('Standard', 'aqualuxe'),
                    'centered' => __('Centered', 'aqualuxe'),
                    'minimal' => __('Minimal', 'aqualuxe'),
                ),
            )
        );
        
        // Sticky Header
        $wp_customize->add_setting(
            'sticky_header',
            array(
                'default' => true,
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
            )
        );
        
        $wp_customize->add_control(
            'sticky_header',
            array(
                'label' => __('Enable Sticky Header', 'aqualuxe'),
                'section' => 'aqualuxe_header',
                'type' => 'checkbox',
            )
        );

        /**
         * Footer Section
         */
        $wp_customize->add_section(
            'aqualuxe_footer',
            array(
                'title' => __('Footer', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 50,
            )
        );
        
        // Footer Columns
        $wp_customize->add_setting(
            'footer_columns',
            array(
                'default' => '4',
                'transport' => 'refresh',
                'sanitize_callback' => array($this, 'sanitize_select'),
            )
        );
        
        $wp_customize->add_control(
            'footer_columns',
            array(
                'label' => __('Footer Columns', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
                'type' => 'select',
                'choices' => array(
                    '1' => __('1 Column', 'aqualuxe'),
                    '2' => __('2 Columns', 'aqualuxe'),
                    '3' => __('3 Columns', 'aqualuxe'),
                    '4' => __('4 Columns', 'aqualuxe'),
                ),
            )
        );
        
        // Copyright Text
        $wp_customize->add_setting(
            'copyright_text',
            array(
                'default' => sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')),
                'transport' => 'refresh',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control(
            'copyright_text',
            array(
                'label' => __('Copyright Text', 'aqualuxe'),
                'section' => 'aqualuxe_footer',
                'type' => 'textarea',
            )
        );

        /**
         * WooCommerce Section
         */
        if (class_exists('WooCommerce')) {
            $wp_customize->add_section(
                'aqualuxe_woocommerce',
                array(
                    'title' => __('WooCommerce', 'aqualuxe'),
                    'panel' => 'aqualuxe_theme_options',
                    'priority' => 60,
                )
            );
            
            // Products Per Page
            $wp_customize->add_setting(
                'products_per_page',
                array(
                    'default' => '12',
                    'transport' => 'refresh',
                    'sanitize_callback' => 'absint',
                )
            );
            
            $wp_customize->add_control(
                'products_per_page',
                array(
                    'label' => __('Products Per Page', 'aqualuxe'),
                    'section' => 'aqualuxe_woocommerce',
                    'type' => 'number',
                    'input_attrs' => array(
                        'min' => 4,
                        'max' => 48,
                        'step' => 4,
                    ),
                )
            );
            
            // Product Columns
            $wp_customize->add_setting(
                'product_columns',
                array(
                    'default' => '4',
                    'transport' => 'refresh',
                    'sanitize_callback' => 'absint',
                )
            );
            
            $wp_customize->add_control(
                'product_columns',
                array(
                    'label' => __('Product Columns', 'aqualuxe'),
                    'section' => 'aqualuxe_woocommerce',
                    'type' => 'select',
                    'choices' => array(
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                    ),
                )
            );
            
            // Related Products
            $wp_customize->add_setting(
                'related_products',
                array(
                    'default' => true,
                    'transport' => 'refresh',
                    'sanitize_callback' => array($this, 'sanitize_checkbox'),
                )
            );
            
            $wp_customize->add_control(
                'related_products',
                array(
                    'label' => __('Show Related Products', 'aqualuxe'),
                    'section' => 'aqualuxe_woocommerce',
                    'type' => 'checkbox',
                )
            );
            
            // Quick View
            $wp_customize->add_setting(
                'quick_view',
                array(
                    'default' => true,
                    'transport' => 'refresh',
                    'sanitize_callback' => array($this, 'sanitize_checkbox'),
                )
            );
            
            $wp_customize->add_control(
                'quick_view',
                array(
                    'label' => __('Enable Quick View', 'aqualuxe'),
                    'section' => 'aqualuxe_woocommerce',
                    'type' => 'checkbox',
                )
            );
            
            // Wishlist
            $wp_customize->add_setting(
                'wishlist',
                array(
                    'default' => true,
                    'transport' => 'refresh',
                    'sanitize_callback' => array($this, 'sanitize_checkbox'),
                )
            );
            
            $wp_customize->add_control(
                'wishlist',
                array(
                    'label' => __('Enable Wishlist', 'aqualuxe'),
                    'section' => 'aqualuxe_woocommerce',
                    'type' => 'checkbox',
                )
            );
        }

        /**
         * Advanced Section
         */
        $wp_customize->add_section(
            'aqualuxe_advanced',
            array(
                'title' => __('Advanced', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 70,
            )
        );
        
        // Custom CSS
        $wp_customize->add_setting(
            'custom_css',
            array(
                'default' => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'wp_strip_all_tags',
            )
        );
        
        $wp_customize->add_control(
            'custom_css',
            array(
                'label' => __('Custom CSS', 'aqualuxe'),
                'section' => 'aqualuxe_advanced',
                'type' => 'textarea',
            )
        );
        
        // Custom JavaScript
        $wp_customize->add_setting(
            'custom_js',
            array(
                'default' => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'wp_strip_all_tags',
            )
        );
        
        $wp_customize->add_control(
            'custom_js',
            array(
                'label' => __('Custom JavaScript', 'aqualuxe'),
                'section' => 'aqualuxe_advanced',
                'type' => 'textarea',
            )
        );
    }

    /**
     * Enqueue scripts for customizer preview
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            get_template_directory_uri() . '/assets/js/customizer-preview.js',
            array('customize-preview', 'jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue scripts for customizer controls
     */
    public function customize_controls_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            get_template_directory_uri() . '/assets/js/customizer-controls.js',
            array('customize-controls', 'jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'aqualuxe-customizer-style',
            get_template_directory_uri() . '/assets/css/customizer.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Sanitize checkbox
     *
     * @param bool $checked Whether the checkbox is checked.
     * @return bool
     */
    public function sanitize_checkbox($checked) {
        return (isset($checked) && true === $checked) ? true : false;
    }

    /**
     * Sanitize select
     *
     * @param string $input The input from the setting.
     * @param object $setting The selected setting.
     * @return string The sanitized input.
     */
    public function sanitize_select($input, $setting) {
        // Get the list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control($setting->id)->choices;
        
        // Return input if valid or return default option.
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }
}

// Initialize the class
new AquaLuxe_Customizer();