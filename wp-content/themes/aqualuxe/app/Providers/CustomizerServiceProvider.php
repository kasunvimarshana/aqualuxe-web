<?php
/**
 * Theme Customizer Service Provider
 *
 * This service provider registers all theme customization options and controls
 * in the WordPress Customizer with live preview functionality.
 *
 * @package AquaLuxe\Providers
 */

namespace App\Providers;

use App\Core\ServiceProvider;
use WP_Customize_Manager;
use WP_Customize_Color_Control;
use WP_Customize_Image_Control;

class CustomizerServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void {
        add_action('customize_register', [$this, 'register_customizer_settings']);
        add_action('wp_head', [$this, 'output_customizer_css']);
        add_action('customize_preview_init', [$this, 'enqueue_customizer_scripts']);
    }

    /**
     * Register all customizer settings and controls.
     *
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    public function register_customizer_settings(WP_Customize_Manager $wp_customize): void {
        // Enable postMessage for default settings
        $wp_customize->get_setting('blogname')->transport = 'postMessage';
        $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
        $wp_customize->get_section('title_tagline')->priority = 0;

        // Add selective refresh partials
        if (isset($wp_customize->selective_refresh)) {
            $wp_customize->selective_refresh->add_partial('blogname', [
                'selector' => '.site-title a',
                'render_callback' => [$this, 'customize_partial_blogname']
            ]);

            $wp_customize->selective_refresh->add_partial('blogdescription', [
                'selector' => '.site-description',
                'render_callback' => [$this, 'customize_partial_blogdescription']
            ]);
        }

        // Register custom sections
        $this->register_brand_section($wp_customize);
        $this->register_hero_section($wp_customize);
        $this->register_layout_section($wp_customize);
        $this->register_footer_section($wp_customize);
        $this->register_performance_section($wp_customize);

        if (class_exists('WooCommerce')) {
            $this->register_woocommerce_section($wp_customize);
        }
    }

    /**
     * Register brand and colors section.
     *
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    private function register_brand_section(WP_Customize_Manager $wp_customize): void {
        // Brand Section
        $wp_customize->add_section('aqualuxe_brand', [
            'title' => __('Brand & Colors', 'aqualuxe'),
            'priority' => 30,
            'description' => __('Customize your brand colors and visual identity.', 'aqualuxe')
        ]);

        // Primary Color
        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default' => '#3b82f6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
            'label' => __('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_brand',
            'settings' => 'aqualuxe_primary_color'
        ]));

        // Secondary Color
        $wp_customize->add_setting('aqualuxe_secondary_color', [
            'default' => '#06b6d4',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', [
            'label' => __('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_brand',
            'settings' => 'aqualuxe_secondary_color'
        ]));

        // Accent Color
        $wp_customize->add_setting('aqualuxe_accent_color', [
            'default' => '#f59e0b',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', [
            'label' => __('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_brand',
            'settings' => 'aqualuxe_accent_color'
        ]));
    }

    /**
     * Register hero section.
     *
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    private function register_hero_section(WP_Customize_Manager $wp_customize): void {
        // Hero Section
        $wp_customize->add_section('aqualuxe_hero', [
            'title' => __('Hero Section', 'aqualuxe'),
            'priority' => 50,
            'description' => __('Customize the homepage hero section.', 'aqualuxe')
        ]);

        // Hero Title
        $wp_customize->add_setting('aqualuxe_hero_title', [
            'default' => __('Welcome to AquaLuxe', 'aqualuxe'),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('aqualuxe_hero_title', [
            'label' => __('Hero Title', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'text'
        ]);

        // Hero Subtitle
        $wp_customize->add_setting('aqualuxe_hero_subtitle', [
            'default' => __('Discover premium water luxury solutions for your home and business.', 'aqualuxe'),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('aqualuxe_hero_subtitle', [
            'label' => __('Hero Subtitle', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'textarea'
        ]);

        // Hero Button Text
        $wp_customize->add_setting('aqualuxe_hero_button_text', [
            'default' => __('Explore Products', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('aqualuxe_hero_button_text', [
            'label' => __('Button Text', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'text'
        ]);

        // Hero Button URL
        $wp_customize->add_setting('aqualuxe_hero_button_url', [
            'default' => '/shop',
            'sanitize_callback' => 'esc_url_raw'
        ]);

        $wp_customize->add_control('aqualuxe_hero_button_url', [
            'label' => __('Button URL', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'url'
        ]);

        // Hero Background Image
        $wp_customize->add_setting('aqualuxe_hero_background', [
            'sanitize_callback' => 'esc_url_raw'
        ]);

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_hero_background', [
            'label' => __('Hero Background Image', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'settings' => 'aqualuxe_hero_background'
        ]));
    }

    /**
     * Register layout section.
     *
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    private function register_layout_section(WP_Customize_Manager $wp_customize): void {
        // Layout Section
        $wp_customize->add_section('aqualuxe_layout', [
            'title' => __('Layout Options', 'aqualuxe'),
            'priority' => 60,
            'description' => __('Configure layout and spacing options.', 'aqualuxe')
        ]);

        // Container Width
        $wp_customize->add_setting('aqualuxe_container_width', [
            'default' => 1400,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('aqualuxe_container_width', [
            'label' => __('Container Max Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'range',
            'input_attrs' => [
                'min' => 1000,
                'max' => 1800,
                'step' => 50
            ]
        ]);
    }

    /**
     * Register footer section.
     *
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    private function register_footer_section(WP_Customize_Manager $wp_customize): void {
        // Footer Section
        $wp_customize->add_section('aqualuxe_footer', [
            'title' => __('Footer Settings', 'aqualuxe'),
            'priority' => 70,
            'description' => __('Customize footer content.', 'aqualuxe')
        ]);

        // Footer Text
        $wp_customize->add_setting('aqualuxe_footer_text', [
            'default' => __('AquaLuxe - Premium Water Solutions', 'aqualuxe'),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage'
        ]);

        $wp_customize->add_control('aqualuxe_footer_text', [
            'label' => __('Footer Description', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'textarea'
        ]);
    }

    /**
     * Register performance section.
     *
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    private function register_performance_section(WP_Customize_Manager $wp_customize): void {
        // Performance Section
        $wp_customize->add_section('aqualuxe_performance', [
            'title' => __('Performance', 'aqualuxe'),
            'priority' => 80,
            'description' => __('Optimize theme performance.', 'aqualuxe')
        ]);

        // Enable Animations
        $wp_customize->add_setting('aqualuxe_enable_animations', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean'
        ]);

        $wp_customize->add_control('aqualuxe_enable_animations', [
            'label' => __('Enable Animations', 'aqualuxe'),
            'section' => 'aqualuxe_performance',
            'type' => 'checkbox'
        ]);
    }

    /**
     * Register WooCommerce section.
     *
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    private function register_woocommerce_section(WP_Customize_Manager $wp_customize): void {
        // WooCommerce Section
        $wp_customize->add_section('aqualuxe_woocommerce', [
            'title' => __('WooCommerce', 'aqualuxe'),
            'priority' => 90,
            'description' => __('Customize shop settings.', 'aqualuxe')
        ]);

        // Shop Columns
        $wp_customize->add_setting('aqualuxe_shop_columns', [
            'default' => '3',
            'sanitize_callback' => [$this, 'sanitize_shop_columns']
        ]);

        $wp_customize->add_control('aqualuxe_shop_columns', [
            'label' => __('Shop Columns', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => [
                '2' => __('2 Columns', 'aqualuxe'),
                '3' => __('3 Columns', 'aqualuxe'),
                '4' => __('4 Columns', 'aqualuxe')
            ]
        ]);
    }

    /**
     * Output custom CSS based on customizer settings.
     *
     * @return void
     */
    public function output_customizer_css(): void {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#3b82f6');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#06b6d4');
        $accent_color = get_theme_mod('aqualuxe_accent_color', '#f59e0b');
        $container_width = get_theme_mod('aqualuxe_container_width', 1400);

        $css = "
        <style type=\"text/css\" id=\"aqualuxe-customizer-css\">
            :root {
                --color-primary: {$primary_color};
                --color-secondary: {$secondary_color};
                --color-accent: {$accent_color};
                --container-max-width: {$container_width}px;
            }
            .container { max-width: var(--container-max-width); }
        </style>
        ";

        echo $css;
    }

    /**
     * Enqueue customizer scripts.
     *
     * @return void
     */
    public function enqueue_customizer_scripts(): void {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_URL . '/assets/dist/js/customizer.js',
            ['customize-preview', 'jquery'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Render the site title for selective refresh partial.
     *
     * @return void
     */
    public function customize_partial_blogname(): void {
        bloginfo('name');
    }

    /**
     * Render the site tagline for selective refresh partial.
     *
     * @return void
     */
    public function customize_partial_blogdescription(): void {
        bloginfo('description');
    }

    /**
     * Sanitize shop columns.
     *
     * @param string $input
     * @return string
     */
    public function sanitize_shop_columns(string $input): string {
        $valid = ['2', '3', '4'];
        return in_array($input, $valid, true) ? $input : '3';
    }
}
