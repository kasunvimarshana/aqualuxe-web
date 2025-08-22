<?php
/**
 * AquaLuxe Customizer Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Customizer Class
 */
class AquaLuxe_Customizer {
    /**
     * WP_Customize_Manager instance
     *
     * @var WP_Customize_Manager
     */
    private $wp_customize;

    /**
     * Constructor
     *
     * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance
     */
    public function __construct($wp_customize) {
        $this->wp_customize = $wp_customize;
        
        // Register customizer sections and settings
        $this->register_sections();
        $this->register_settings();
        
        // Add live preview script
        add_action('customize_preview_init', [$this, 'customize_preview_js']);
        
        // Add customizer controls script
        add_action('customize_controls_enqueue_scripts', [$this, 'customize_controls_js']);
    }

    /**
     * Register customizer sections
     */
    public function register_sections() {
        // Site Identity section is already registered by WordPress
        
        // General Settings
        $this->wp_customize->add_section('aqualuxe_general_settings', [
            'title' => esc_html__('General Settings', 'aqualuxe'),
            'priority' => 30,
        ]);
        
        // Header Settings
        $this->wp_customize->add_section('aqualuxe_header_settings', [
            'title' => esc_html__('Header Settings', 'aqualuxe'),
            'priority' => 40,
        ]);
        
        // Footer Settings
        $this->wp_customize->add_section('aqualuxe_footer_settings', [
            'title' => esc_html__('Footer Settings', 'aqualuxe'),
            'priority' => 50,
        ]);
        
        // Blog Settings
        $this->wp_customize->add_section('aqualuxe_blog_settings', [
            'title' => esc_html__('Blog Settings', 'aqualuxe'),
            'priority' => 60,
        ]);
        
        // WooCommerce Settings (only if WooCommerce is active)
        if (aqualuxe_is_woocommerce_active()) {
            $this->wp_customize->add_section('aqualuxe_woocommerce_settings', [
                'title' => esc_html__('WooCommerce Settings', 'aqualuxe'),
                'priority' => 70,
            ]);
        }
        
        // Typography Settings
        $this->wp_customize->add_section('aqualuxe_typography_settings', [
            'title' => esc_html__('Typography Settings', 'aqualuxe'),
            'priority' => 80,
        ]);
        
        // Color Settings
        $this->wp_customize->add_section('aqualuxe_color_settings', [
            'title' => esc_html__('Color Settings', 'aqualuxe'),
            'priority' => 90,
        ]);
        
        // Module Settings
        $this->wp_customize->add_section('aqualuxe_module_settings', [
            'title' => esc_html__('Module Settings', 'aqualuxe'),
            'priority' => 100,
        ]);
    }

    /**
     * Register customizer settings
     */
    public function register_settings() {
        // General Settings
        $this->add_setting('aqualuxe_container_width', [
            'default' => '1200px',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $this->add_control('aqualuxe_container_width', [
            'label' => esc_html__('Container Width', 'aqualuxe'),
            'section' => 'aqualuxe_general_settings',
            'type' => 'text',
        ]);
        
        $this->add_setting('aqualuxe_enable_dark_mode', [
            'default' => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $this->add_control('aqualuxe_enable_dark_mode', [
            'label' => esc_html__('Enable Dark Mode Toggle', 'aqualuxe'),
            'section' => 'aqualuxe_general_settings',
            'type' => 'checkbox',
        ]);
        
        $this->add_setting('aqualuxe_default_color_scheme', [
            'default' => 'light',
            'transport' => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ]);
        
        $this->add_control('aqualuxe_default_color_scheme', [
            'label' => esc_html__('Default Color Scheme', 'aqualuxe'),
            'section' => 'aqualuxe_general_settings',
            'type' => 'select',
            'choices' => [
                'light' => esc_html__('Light', 'aqualuxe'),
                'dark' => esc_html__('Dark', 'aqualuxe'),
            ],
        ]);
        
        // Header Settings
        $this->add_setting('aqualuxe_header_layout', [
            'default' => 'standard',
            'transport' => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ]);
        
        $this->add_control('aqualuxe_header_layout', [
            'label' => esc_html__('Header Layout', 'aqualuxe'),
            'section' => 'aqualuxe_header_settings',
            'type' => 'select',
            'choices' => [
                'standard' => esc_html__('Standard', 'aqualuxe'),
                'centered' => esc_html__('Centered', 'aqualuxe'),
                'split' => esc_html__('Split', 'aqualuxe'),
                'minimal' => esc_html__('Minimal', 'aqualuxe'),
            ],
        ]);
        
        $this->add_setting('aqualuxe_sticky_header', [
            'default' => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $this->add_control('aqualuxe_sticky_header', [
            'label' => esc_html__('Enable Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header_settings',
            'type' => 'checkbox',
        ]);
        
        $this->add_setting('aqualuxe_show_search', [
            'default' => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $this->add_control('aqualuxe_show_search', [
            'label' => esc_html__('Show Search in Header', 'aqualuxe'),
            'section' => 'aqualuxe_header_settings',
            'type' => 'checkbox',
        ]);
        
        // Footer Settings
        $this->add_setting('aqualuxe_footer_layout', [
            'default' => 'four-column',
            'transport' => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ]);
        
        $this->add_control('aqualuxe_footer_layout', [
            'label' => esc_html__('Footer Layout', 'aqualuxe'),
            'section' => 'aqualuxe_footer_settings',
            'type' => 'select',
            'choices' => [
                'one-column' => esc_html__('One Column', 'aqualuxe'),
                'two-column' => esc_html__('Two Columns', 'aqualuxe'),
                'three-column' => esc_html__('Three Columns', 'aqualuxe'),
                'four-column' => esc_html__('Four Columns', 'aqualuxe'),
            ],
        ]);
        
        $this->add_setting('aqualuxe_footer_copyright', [
            'default' => sprintf(
                /* translators: %1$s: Current year, %2$s: Site name */
                esc_html__('© %1$s %2$s. All rights reserved.', 'aqualuxe'),
                date('Y'),
                get_bloginfo('name')
            ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $this->add_control('aqualuxe_footer_copyright', [
            'label' => esc_html__('Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer_settings',
            'type' => 'textarea',
        ]);
        
        // Blog Settings
        $this->add_setting('aqualuxe_blog_layout', [
            'default' => 'grid',
            'transport' => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ]);
        
        $this->add_control('aqualuxe_blog_layout', [
            'label' => esc_html__('Blog Layout', 'aqualuxe'),
            'section' => 'aqualuxe_blog_settings',
            'type' => 'select',
            'choices' => [
                'grid' => esc_html__('Grid', 'aqualuxe'),
                'list' => esc_html__('List', 'aqualuxe'),
                'standard' => esc_html__('Standard', 'aqualuxe'),
            ],
        ]);
        
        $this->add_setting('aqualuxe_blog_columns', [
            'default' => '3',
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        ]);
        
        $this->add_control('aqualuxe_blog_columns', [
            'label' => esc_html__('Blog Grid Columns', 'aqualuxe'),
            'section' => 'aqualuxe_blog_settings',
            'type' => 'select',
            'choices' => [
                '2' => esc_html__('2 Columns', 'aqualuxe'),
                '3' => esc_html__('3 Columns', 'aqualuxe'),
                '4' => esc_html__('4 Columns', 'aqualuxe'),
            ],
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_blog_layout', 'grid') === 'grid';
            },
        ]);
        
        $this->add_setting('aqualuxe_excerpt_length', [
            'default' => 55,
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        ]);
        
        $this->add_control('aqualuxe_excerpt_length', [
            'label' => esc_html__('Excerpt Length', 'aqualuxe'),
            'section' => 'aqualuxe_blog_settings',
            'type' => 'number',
            'input_attrs' => [
                'min' => 10,
                'max' => 200,
                'step' => 5,
            ],
        ]);
        
        $this->add_setting('aqualuxe_show_related_posts', [
            'default' => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $this->add_control('aqualuxe_show_related_posts', [
            'label' => esc_html__('Show Related Posts', 'aqualuxe'),
            'section' => 'aqualuxe_blog_settings',
            'type' => 'checkbox',
        ]);
        
        // WooCommerce Settings (only if WooCommerce is active)
        if (aqualuxe_is_woocommerce_active()) {
            $this->add_setting('aqualuxe_shop_layout', [
                'default' => 'grid',
                'transport' => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]);
            
            $this->add_control('aqualuxe_shop_layout', [
                'label' => esc_html__('Shop Layout', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'select',
                'choices' => [
                    'grid' => esc_html__('Grid', 'aqualuxe'),
                    'list' => esc_html__('List', 'aqualuxe'),
                ],
            ]);
            
            $this->add_setting('aqualuxe_shop_columns', [
                'default' => '3',
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            ]);
            
            $this->add_control('aqualuxe_shop_columns', [
                'label' => esc_html__('Shop Grid Columns', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'select',
                'choices' => [
                    '2' => esc_html__('2 Columns', 'aqualuxe'),
                    '3' => esc_html__('3 Columns', 'aqualuxe'),
                    '4' => esc_html__('4 Columns', 'aqualuxe'),
                ],
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_shop_layout', 'grid') === 'grid';
                },
            ]);
            
            $this->add_setting('aqualuxe_products_per_page', [
                'default' => 12,
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            ]);
            
            $this->add_control('aqualuxe_products_per_page', [
                'label' => esc_html__('Products Per Page', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ]);
            
            $this->add_setting('aqualuxe_product_layout', [
                'default' => 'standard',
                'transport' => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]);
            
            $this->add_control('aqualuxe_product_layout', [
                'label' => esc_html__('Product Page Layout', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'select',
                'choices' => [
                    'standard' => esc_html__('Standard', 'aqualuxe'),
                    'full-width' => esc_html__('Full Width', 'aqualuxe'),
                    'sticky' => esc_html__('Sticky Info', 'aqualuxe'),
                    'gallery' => esc_html__('Gallery', 'aqualuxe'),
                ],
            ]);
            
            $this->add_setting('aqualuxe_enable_quick_view', [
                'default' => true,
                'transport' => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]);
            
            $this->add_control('aqualuxe_enable_quick_view', [
                'label' => esc_html__('Enable Quick View', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'checkbox',
            ]);
            
            $this->add_setting('aqualuxe_enable_wishlist', [
                'default' => true,
                'transport' => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]);
            
            $this->add_control('aqualuxe_enable_wishlist', [
                'label' => esc_html__('Enable Wishlist', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'checkbox',
            ]);
            
            $this->add_setting('aqualuxe_enable_ajax_add_to_cart', [
                'default' => true,
                'transport' => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]);
            
            $this->add_control('aqualuxe_enable_ajax_add_to_cart', [
                'label' => esc_html__('Enable AJAX Add to Cart', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_settings',
                'type' => 'checkbox',
            ]);
        }
        
        // Typography Settings
        $this->add_setting('aqualuxe_heading_font', [
            'default' => 'Montserrat, sans-serif',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $this->add_control('aqualuxe_heading_font', [
            'label' => esc_html__('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography_settings',
            'type' => 'select',
            'choices' => [
                'Montserrat, sans-serif' => esc_html__('Montserrat', 'aqualuxe'),
                'Roboto, sans-serif' => esc_html__('Roboto', 'aqualuxe'),
                'Lato, sans-serif' => esc_html__('Lato', 'aqualuxe'),
                'Open Sans, sans-serif' => esc_html__('Open Sans', 'aqualuxe'),
                'Playfair Display, serif' => esc_html__('Playfair Display', 'aqualuxe'),
                'Merriweather, serif' => esc_html__('Merriweather', 'aqualuxe'),
                'Poppins, sans-serif' => esc_html__('Poppins', 'aqualuxe'),
                'Raleway, sans-serif' => esc_html__('Raleway', 'aqualuxe'),
            ],
        ]);
        
        $this->add_setting('aqualuxe_body_font', [
            'default' => 'Open Sans, sans-serif',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $this->add_control('aqualuxe_body_font', [
            'label' => esc_html__('Body Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography_settings',
            'type' => 'select',
            'choices' => [
                'Open Sans, sans-serif' => esc_html__('Open Sans', 'aqualuxe'),
                'Roboto, sans-serif' => esc_html__('Roboto', 'aqualuxe'),
                'Lato, sans-serif' => esc_html__('Lato', 'aqualuxe'),
                'Montserrat, sans-serif' => esc_html__('Montserrat', 'aqualuxe'),
                'Raleway, sans-serif' => esc_html__('Raleway', 'aqualuxe'),
                'Poppins, sans-serif' => esc_html__('Poppins', 'aqualuxe'),
                'Nunito, sans-serif' => esc_html__('Nunito', 'aqualuxe'),
                'Source Sans Pro, sans-serif' => esc_html__('Source Sans Pro', 'aqualuxe'),
            ],
        ]);
        
        $this->add_setting('aqualuxe_base_font_size', [
            'default' => '16px',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $this->add_control('aqualuxe_base_font_size', [
            'label' => esc_html__('Base Font Size', 'aqualuxe'),
            'section' => 'aqualuxe_typography_settings',
            'type' => 'text',
        ]);
        
        // Color Settings
        $this->add_setting('aqualuxe_primary_color', [
            'default' => '#0077b6',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        
        $this->add_control('aqualuxe_primary_color', [
            'label' => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_color_settings',
            'type' => 'color',
        ]);
        
        $this->add_setting('aqualuxe_secondary_color', [
            'default' => '#00b4d8',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        
        $this->add_control('aqualuxe_secondary_color', [
            'label' => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_color_settings',
            'type' => 'color',
        ]);
        
        $this->add_setting('aqualuxe_accent_color', [
            'default' => '#90e0ef',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        
        $this->add_control('aqualuxe_accent_color', [
            'label' => esc_html__('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_color_settings',
            'type' => 'color',
        ]);
        
        $this->add_setting('aqualuxe_text_color', [
            'default' => '#333333',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        
        $this->add_control('aqualuxe_text_color', [
            'label' => esc_html__('Text Color', 'aqualuxe'),
            'section' => 'aqualuxe_color_settings',
            'type' => 'color',
        ]);
        
        $this->add_setting('aqualuxe_heading_color', [
            'default' => '#222222',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        
        $this->add_control('aqualuxe_heading_color', [
            'label' => esc_html__('Heading Color', 'aqualuxe'),
            'section' => 'aqualuxe_color_settings',
            'type' => 'color',
        ]);
        
        $this->add_setting('aqualuxe_link_color', [
            'default' => '#0077b6',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        
        $this->add_control('aqualuxe_link_color', [
            'label' => esc_html__('Link Color', 'aqualuxe'),
            'section' => 'aqualuxe_color_settings',
            'type' => 'color',
        ]);
        
        $this->add_setting('aqualuxe_link_hover_color', [
            'default' => '#00b4d8',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        
        $this->add_control('aqualuxe_link_hover_color', [
            'label' => esc_html__('Link Hover Color', 'aqualuxe'),
            'section' => 'aqualuxe_color_settings',
            'type' => 'color',
        ]);
        
        // Module Settings
        $modules = aqualuxe_get_all_modules();
        
        foreach ($modules as $module => $info) {
            $this->add_setting('aqualuxe_module_' . $module, [
                'default' => true,
                'transport' => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]);
            
            $this->add_control('aqualuxe_module_' . $module, [
                'label' => $info['title'],
                'description' => $info['description'],
                'section' => 'aqualuxe_module_settings',
                'type' => 'checkbox',
            ]);
        }
    }

    /**
     * Add setting
     *
     * @param string $id Setting ID
     * @param array $args Setting arguments
     */
    private function add_setting($id, $args = []) {
        $this->wp_customize->add_setting('aqualuxe_settings[' . $id . ']', $args);
    }

    /**
     * Add control
     *
     * @param string $id Control ID
     * @param array $args Control arguments
     */
    private function add_control($id, $args = []) {
        $args['settings'] = 'aqualuxe_settings[' . $id . ']';
        
        if (isset($args['type']) && $args['type'] === 'color') {
            $this->wp_customize->add_control(new WP_Customize_Color_Control(
                $this->wp_customize,
                'aqualuxe_settings[' . $id . ']',
                $args
            ));
        } else {
            $this->wp_customize->add_control('aqualuxe_settings[' . $id . ']', $args);
        }
    }

    /**
     * Enqueue customizer preview script
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . 'js/customizer-preview.js',
            ['customize-preview', 'jquery'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue customizer controls script
     */
    public function customize_controls_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            AQUALUXE_ASSETS_URI . 'js/customizer-controls.js',
            ['customize-controls', 'jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'aqualuxe-customizer-controls',
            AQUALUXE_ASSETS_URI . 'css/customizer-controls.css',
            [],
            AQUALUXE_VERSION
        );
    }
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input Select value
 * @param WP_Customize_Setting $setting Setting instance
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices)) ? $input : $setting->default;
}