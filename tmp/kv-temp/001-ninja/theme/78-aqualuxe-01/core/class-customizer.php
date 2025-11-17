<?php
/**
 * Theme customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Customizer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('customize_register', [$this, 'register_customizer_settings']);
        add_action('customize_preview_init', [$this, 'customize_preview_js']);
        add_action('wp_head', [$this, 'output_customizer_css']);
    }
    
    /**
     * Register customizer settings
     */
    public function register_customizer_settings($wp_customize) {
        
        // Add AquaLuxe panel
        $wp_customize->add_panel('aqualuxe_panel', [
            'title' => esc_html__('AquaLuxe Options', 'aqualuxe'),
            'priority' => 30,
        ]);
        
        // Colors section
        $this->add_colors_section($wp_customize);
        
        // Typography section
        $this->add_typography_section($wp_customize);
        
        // Layout section
        $this->add_layout_section($wp_customize);
        
        // Header section
        $this->add_header_section($wp_customize);
        
        // Footer section
        $this->add_footer_section($wp_customize);
        
        // Modules section
        $this->add_modules_section($wp_customize);
        
        // Hero section
        $this->add_hero_section($wp_customize);
    }
    
    /**
     * Add colors section
     */
    private function add_colors_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_colors', [
            'title' => esc_html__('Colors', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 10,
        ]);
        
        // Primary color
        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default' => '#14b8a6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
            'label' => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_primary_color',
        ]));
        
        // Secondary color
        $wp_customize->add_setting('aqualuxe_secondary_color', [
            'default' => '#a855f7',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', [
            'label' => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_secondary_color',
        ]));
        
        // Accent color
        $wp_customize->add_setting('aqualuxe_accent_color', [
            'default' => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', [
            'label' => esc_html__('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_accent_color',
        ]));
    }
    
    /**
     * Add typography section
     */
    private function add_typography_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_typography', [
            'title' => esc_html__('Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 20,
        ]);
        
        // Primary font
        $wp_customize->add_setting('aqualuxe_primary_font', [
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('aqualuxe_primary_font', [
            'label' => esc_html__('Primary Font', 'aqualuxe'),
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
        
        // Heading font
        $wp_customize->add_setting('aqualuxe_heading_font', [
            'default' => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('aqualuxe_heading_font', [
            'label' => esc_html__('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => [
                'Playfair Display' => 'Playfair Display',
                'Merriweather' => 'Merriweather',
                'Lora' => 'Lora',
                'Crimson Text' => 'Crimson Text',
                'Libre Baskerville' => 'Libre Baskerville',
            ],
        ]);
        
        // Base font size
        $wp_customize->add_setting('aqualuxe_base_font_size', [
            'default' => 16,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('aqualuxe_base_font_size', [
            'label' => esc_html__('Base Font Size (px)', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'number',
            'input_attrs' => [
                'min' => 12,
                'max' => 24,
                'step' => 1,
            ],
        ]);
    }
    
    /**
     * Add layout section
     */
    private function add_layout_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_layout', [
            'title' => esc_html__('Layout', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 30,
        ]);
        
        // Container width
        $wp_customize->add_setting('aqualuxe_container_width', [
            'default' => 1200,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('aqualuxe_container_width', [
            'label' => esc_html__('Container Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'number',
            'input_attrs' => [
                'min' => 960,
                'max' => 1600,
                'step' => 20,
            ],
        ]);
        
        // Sidebar position
        $wp_customize->add_setting('aqualuxe_sidebar_position', [
            'default' => 'right',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_control('aqualuxe_sidebar_position', [
            'label' => esc_html__('Sidebar Position', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'select',
            'choices' => [
                'left' => esc_html__('Left', 'aqualuxe'),
                'right' => esc_html__('Right', 'aqualuxe'),
                'none' => esc_html__('No Sidebar', 'aqualuxe'),
            ],
        ]);
    }
    
    /**
     * Add header section
     */
    private function add_header_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_header', [
            'title' => esc_html__('Header', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 40,
        ]);
        
        // Header layout
        $wp_customize->add_setting('aqualuxe_header_layout', [
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_control('aqualuxe_header_layout', [
            'label' => esc_html__('Header Layout', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'select',
            'choices' => [
                'default' => esc_html__('Default', 'aqualuxe'),
                'centered' => esc_html__('Centered', 'aqualuxe'),
                'minimal' => esc_html__('Minimal', 'aqualuxe'),
            ],
        ]);
        
        // Sticky header
        $wp_customize->add_setting('aqualuxe_sticky_header', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ]);
        
        $wp_customize->add_control('aqualuxe_sticky_header', [
            'label' => esc_html__('Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ]);
        
        // Header transparency
        $wp_customize->add_setting('aqualuxe_header_transparent', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ]);
        
        $wp_customize->add_control('aqualuxe_header_transparent', [
            'label' => esc_html__('Transparent Header on Homepage', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ]);
    }
    
    /**
     * Add footer section
     */
    private function add_footer_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_footer', [
            'title' => esc_html__('Footer', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 50,
        ]);
        
        // Footer text
        $wp_customize->add_setting('aqualuxe_footer_text', [
            'default' => '© 2024 AquaLuxe. All rights reserved.',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('aqualuxe_footer_text', [
            'label' => esc_html__('Footer Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'textarea',
        ]);
        
        // Footer social links
        $social_networks = [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'linkedin' => 'LinkedIn',
        ];
        
        foreach ($social_networks as $network => $label) {
            $wp_customize->add_setting("aqualuxe_social_{$network}", [
                'default' => '',
                'sanitize_callback' => 'esc_url_raw',
            ]);
            
            $wp_customize->add_control("aqualuxe_social_{$network}", [
                'label' => sprintf(esc_html__('%s URL', 'aqualuxe'), $label),
                'section' => 'aqualuxe_footer',
                'type' => 'url',
            ]);
        }
    }
    
    /**
     * Add modules section
     */
    private function add_modules_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_modules', [
            'title' => esc_html__('Theme Modules', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 60,
        ]);
        
        $modules = [
            'dark_mode' => esc_html__('Dark Mode Toggle', 'aqualuxe'),
            'multilingual' => esc_html__('Multilingual Support', 'aqualuxe'),
            'performance' => esc_html__('Performance Optimization', 'aqualuxe'),
            'seo' => esc_html__('SEO Enhancements', 'aqualuxe'),
            'security' => esc_html__('Security Features', 'aqualuxe'),
            'accessibility' => esc_html__('Accessibility Features', 'aqualuxe'),
            'booking' => esc_html__('Booking System', 'aqualuxe'),
            'events' => esc_html__('Events Calendar', 'aqualuxe'),
            'subscriptions' => esc_html__('Subscriptions', 'aqualuxe'),
            'franchise' => esc_html__('Franchise Portal', 'aqualuxe'),
            'wholesale' => esc_html__('Wholesale Features', 'aqualuxe'),
            'auctions' => esc_html__('Auction System', 'aqualuxe'),
            'affiliates' => esc_html__('Affiliate Program', 'aqualuxe'),
        ];
        
        foreach ($modules as $module => $label) {
            $wp_customize->add_setting("aqualuxe_module_{$module}", [
                'default' => true,
                'sanitize_callback' => 'rest_sanitize_boolean',
            ]);
            
            $wp_customize->add_control("aqualuxe_module_{$module}", [
                'label' => $label,
                'section' => 'aqualuxe_modules',
                'type' => 'checkbox',
            ]);
        }
    }
    
    /**
     * Add hero section
     */
    private function add_hero_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_hero', [
            'title' => esc_html__('Homepage Hero', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 70,
        ]);
        
        // Hero title
        $wp_customize->add_setting('aqualuxe_hero_title', [
            'default' => 'Bringing elegance to aquatic life',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('aqualuxe_hero_title', [
            'label' => esc_html__('Hero Title', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'text',
        ]);
        
        // Hero subtitle
        $wp_customize->add_setting('aqualuxe_hero_subtitle', [
            'default' => 'Discover premium fish, plants, and aquarium equipment for enthusiasts worldwide',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('aqualuxe_hero_subtitle', [
            'label' => esc_html__('Hero Subtitle', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'textarea',
        ]);
        
        // Hero button text
        $wp_customize->add_setting('aqualuxe_hero_button_text', [
            'default' => 'Shop Now',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('aqualuxe_hero_button_text', [
            'label' => esc_html__('Hero Button Text', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'text',
        ]);
        
        // Hero button URL
        $wp_customize->add_setting('aqualuxe_hero_button_url', [
            'default' => '/shop',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        
        $wp_customize->add_control('aqualuxe_hero_button_url', [
            'label' => esc_html__('Hero Button URL', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'type' => 'url',
        ]);
        
        // Hero background image
        $wp_customize->add_setting('aqualuxe_hero_background', [
            'default' => '',
            'sanitize_callback' => 'absint',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_hero_background', [
            'label' => esc_html__('Hero Background Image', 'aqualuxe'),
            'section' => 'aqualuxe_hero',
            'mime_type' => 'image',
        ]));
    }
    
    /**
     * Customizer preview JavaScript
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . '/dist/js/customizer.js',
            ['customize-preview'],
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Output customizer CSS
     */
    public function output_customizer_css() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#14b8a6');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#a855f7');
        $accent_color = get_theme_mod('aqualuxe_accent_color', '#0ea5e9');
        $primary_font = get_theme_mod('aqualuxe_primary_font', 'Inter');
        $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
        $base_font_size = get_theme_mod('aqualuxe_base_font_size', 16);
        $container_width = get_theme_mod('aqualuxe_container_width', 1200);
        
        $css = "
        <style type='text/css'>
        :root {
            --aql-primary-color: {$primary_color};
            --aql-secondary-color: {$secondary_color};
            --aql-accent-color: {$accent_color};
            --aql-primary-font: '{$primary_font}', sans-serif;
            --aql-heading-font: '{$heading_font}', serif;
            --aql-base-font-size: {$base_font_size}px;
            --aql-container-width: {$container_width}px;
        }
        
        body {
            font-family: var(--aql-primary-font);
            font-size: var(--aql-base-font-size);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--aql-heading-font);
        }
        
        .container {
            max-width: var(--aql-container-width);
        }
        
        .btn-primary,
        .button-primary {
            background-color: var(--aql-primary-color);
        }
        
        .btn-secondary,
        .button-secondary {
            background-color: var(--aql-secondary-color);
        }
        
        .btn-accent,
        .button-accent {
            background-color: var(--aql-accent-color);
        }
        
        .text-primary {
            color: var(--aql-primary-color);
        }
        
        .text-secondary {
            color: var(--aql-secondary-color);
        }
        
        .text-accent {
            color: var(--aql-accent-color);
        }
        </style>
        ";
        
        echo $css;
    }
}

// Initialize
new AquaLuxe_Customizer();
