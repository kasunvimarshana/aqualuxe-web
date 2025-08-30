<?php
/**
 * Customizer setup
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core\Customizer;

/**
 * Customizer setup class
 */
class Customizer {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('customize_register', [$this, 'register']);
        add_action('customize_preview_init', [$this, 'preview_init']);
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function register($wp_customize) {
        // Add panels
        $this->add_panels($wp_customize);

        // Add sections
        $this->add_sections($wp_customize);

        // Add settings
        $this->add_settings($wp_customize);
    }

    /**
     * Add panels
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_panels($wp_customize) {
        // Theme options panel
        $wp_customize->add_panel('aqualuxe_theme_options', [
            'title' => esc_html__('Theme Options', 'aqualuxe'),
            'description' => esc_html__('Theme options for AquaLuxe.', 'aqualuxe'),
            'priority' => 130,
        ]);

        // Header panel
        $wp_customize->add_panel('aqualuxe_header', [
            'title' => esc_html__('Header', 'aqualuxe'),
            'description' => esc_html__('Header options for AquaLuxe.', 'aqualuxe'),
            'priority' => 140,
        ]);

        // Footer panel
        $wp_customize->add_panel('aqualuxe_footer', [
            'title' => esc_html__('Footer', 'aqualuxe'),
            'description' => esc_html__('Footer options for AquaLuxe.', 'aqualuxe'),
            'priority' => 150,
        ]);
    }

    /**
     * Add sections
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_sections($wp_customize) {
        // Colors section
        $wp_customize->add_section('aqualuxe_colors', [
            'title' => esc_html__('Colors', 'aqualuxe'),
            'description' => esc_html__('Color options for AquaLuxe.', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 10,
        ]);

        // Typography section
        $wp_customize->add_section('aqualuxe_typography', [
            'title' => esc_html__('Typography', 'aqualuxe'),
            'description' => esc_html__('Typography options for AquaLuxe.', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 20,
        ]);

        // Layout section
        $wp_customize->add_section('aqualuxe_layout', [
            'title' => esc_html__('Layout', 'aqualuxe'),
            'description' => esc_html__('Layout options for AquaLuxe.', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 30,
        ]);

        // Header layout section
        $wp_customize->add_section('aqualuxe_header_layout', [
            'title' => esc_html__('Layout', 'aqualuxe'),
            'description' => esc_html__('Header layout options for AquaLuxe.', 'aqualuxe'),
            'panel' => 'aqualuxe_header',
            'priority' => 10,
        ]);

        // Header styles section
        $wp_customize->add_section('aqualuxe_header_styles', [
            'title' => esc_html__('Styles', 'aqualuxe'),
            'description' => esc_html__('Header style options for AquaLuxe.', 'aqualuxe'),
            'panel' => 'aqualuxe_header',
            'priority' => 20,
        ]);

        // Footer layout section
        $wp_customize->add_section('aqualuxe_footer_layout', [
            'title' => esc_html__('Layout', 'aqualuxe'),
            'description' => esc_html__('Footer layout options for AquaLuxe.', 'aqualuxe'),
            'panel' => 'aqualuxe_footer',
            'priority' => 10,
        ]);

        // Footer styles section
        $wp_customize->add_section('aqualuxe_footer_styles', [
            'title' => esc_html__('Styles', 'aqualuxe'),
            'description' => esc_html__('Footer style options for AquaLuxe.', 'aqualuxe'),
            'panel' => 'aqualuxe_footer',
            'priority' => 20,
        ]);

        // Blog section
        $wp_customize->add_section('aqualuxe_blog', [
            'title' => esc_html__('Blog', 'aqualuxe'),
            'description' => esc_html__('Blog options for AquaLuxe.', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 40,
        ]);

        // WooCommerce section
        if (class_exists('WooCommerce')) {
            $wp_customize->add_section('aqualuxe_woocommerce', [
                'title' => esc_html__('WooCommerce', 'aqualuxe'),
                'description' => esc_html__('WooCommerce options for AquaLuxe.', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 50,
            ]);
        }
    }

    /**
     * Add settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_settings($wp_customize) {
        // Colors
        $wp_customize->add_setting('primary_color', [
            'default' => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'primary_color', [
            'label' => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'primary_color',
        ]));

        $wp_customize->add_setting('secondary_color', [
            'default' => '#1e293b',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'secondary_color', [
            'label' => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'secondary_color',
        ]));

        $wp_customize->add_setting('accent_color', [
            'default' => '#f59e0b',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'accent_color', [
            'label' => esc_html__('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'accent_color',
        ]));

        // Typography
        $wp_customize->add_setting('body_font', [
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('body_font', [
            'label' => esc_html__('Body Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => [
                'Inter' => 'Inter',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
            ],
        ]);

        $wp_customize->add_setting('heading_font', [
            'default' => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('heading_font', [
            'label' => esc_html__('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => [
                'Playfair Display' => 'Playfair Display',
                'Merriweather' => 'Merriweather',
                'Roboto Slab' => 'Roboto Slab',
                'Lora' => 'Lora',
                'Cormorant Garamond' => 'Cormorant Garamond',
            ],
        ]);

        // Layout
        $wp_customize->add_setting('container_width', [
            'default' => '1280',
            'sanitize_callback' => 'absint',
        ]);

        $wp_customize->add_control('container_width', [
            'label' => esc_html__('Container Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'number',
            'input_attrs' => [
                'min' => 960,
                'max' => 1920,
                'step' => 10,
            ],
        ]);

        $wp_customize->add_setting('sidebar_position', [
            'default' => 'right',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('sidebar_position', [
            'label' => esc_html__('Sidebar Position', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'radio',
            'choices' => [
                'left' => esc_html__('Left', 'aqualuxe'),
                'right' => esc_html__('Right', 'aqualuxe'),
                'none' => esc_html__('No Sidebar', 'aqualuxe'),
            ],
        ]);

        // Header
        $wp_customize->add_setting('header_layout', [
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('header_layout', [
            'label' => esc_html__('Header Layout', 'aqualuxe'),
            'section' => 'aqualuxe_header_layout',
            'type' => 'radio',
            'choices' => [
                'default' => esc_html__('Default', 'aqualuxe'),
                'centered' => esc_html__('Centered', 'aqualuxe'),
                'split' => esc_html__('Split', 'aqualuxe'),
                'minimal' => esc_html__('Minimal', 'aqualuxe'),
            ],
        ]);

        $wp_customize->add_setting('sticky_header', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        $wp_customize->add_control('sticky_header', [
            'label' => esc_html__('Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header_layout',
            'type' => 'checkbox',
        ]);

        // Footer
        $wp_customize->add_setting('footer_columns', [
            'default' => '4',
            'sanitize_callback' => 'absint',
        ]);

        $wp_customize->add_control('footer_columns', [
            'label' => esc_html__('Footer Columns', 'aqualuxe'),
            'section' => 'aqualuxe_footer_layout',
            'type' => 'select',
            'choices' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
        ]);

        $wp_customize->add_setting('footer_text', [
            'default' => sprintf(
                /* translators: %s: Current year and site name */
                esc_html__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'),
                date('Y')
            ),
            'sanitize_callback' => 'wp_kses_post',
        ]);

        $wp_customize->add_control('footer_text', [
            'label' => esc_html__('Footer Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer_layout',
            'type' => 'textarea',
        ]);

        // Blog
        $wp_customize->add_setting('blog_layout', [
            'default' => 'grid',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('blog_layout', [
            'label' => esc_html__('Blog Layout', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'radio',
            'choices' => [
                'grid' => esc_html__('Grid', 'aqualuxe'),
                'list' => esc_html__('List', 'aqualuxe'),
                'masonry' => esc_html__('Masonry', 'aqualuxe'),
            ],
        ]);

        $wp_customize->add_setting('posts_per_page', [
            'default' => get_option('posts_per_page'),
            'sanitize_callback' => 'absint',
        ]);

        $wp_customize->add_control('posts_per_page', [
            'label' => esc_html__('Posts Per Page', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'number',
            'input_attrs' => [
                'min' => 1,
                'max' => 50,
                'step' => 1,
            ],
        ]);

        // WooCommerce
        if (class_exists('WooCommerce')) {
            $wp_customize->add_setting('shop_columns', [
                'default' => '4',
                'sanitize_callback' => 'absint',
            ]);

            $wp_customize->add_control('shop_columns', [
                'label' => esc_html__('Shop Columns', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce',
                'type' => 'select',
                'choices' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
            ]);

            $wp_customize->add_setting('products_per_page', [
                'default' => '12',
                'sanitize_callback' => 'absint',
            ]);

            $wp_customize->add_control('products_per_page', [
                'label' => esc_html__('Products Per Page', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 1,
                    'max' => 50,
                    'step' => 1,
                ],
            ]);

            $wp_customize->add_setting('related_products', [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]);

            $wp_customize->add_control('related_products', [
                'label' => esc_html__('Show Related Products', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce',
                'type' => 'checkbox',
            ]);
        }
    }

    /**
     * Initialize customizer preview
     *
     * @return void
     */
    public function preview_init() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            aqualuxe_asset('js/customizer.js'),
            ['customize-preview'],
            AQUALUXE_VERSION,
            true
        );
    }
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (bool) $checked;
}