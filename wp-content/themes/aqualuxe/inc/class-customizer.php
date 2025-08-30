<?php
/**
 * AquaLuxe Theme Customizer
 * 
 * Handles theme customization options and live preview functionality.
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Customizer {
    
    /**
     * Initialize customizer
     */
    public static function init() {
        add_action('customize_register', [__CLASS__, 'register_customizer_options']);
        add_action('customize_preview_init', [__CLASS__, 'customize_preview_js']);
        add_action('wp_head', [__CLASS__, 'header_output']);
    }
    
    /**
     * Register customizer options
     */
    public static function register_customizer_options($wp_customize) {
        // Add AquaLuxe panel
        $wp_customize->add_panel('aqualuxe_panel', [
            'title' => __('AquaLuxe Options', 'aqualuxe'),
            'description' => __('Customize your AquaLuxe theme', 'aqualuxe'),
            'priority' => 30
        ]);
        
        // Colors section
        self::add_colors_section($wp_customize);
        
        // Typography section
        self::add_typography_section($wp_customize);
        
        // Header section
        self::add_header_section($wp_customize);
        
        // Footer section
        self::add_footer_section($wp_customize);
        
        // Homepage section
        self::add_homepage_section($wp_customize);
        
        // Contact section
        self::add_contact_section($wp_customize);
        
        // Social media section
        self::add_social_section($wp_customize);
        
        // Advanced section
        self::add_advanced_section($wp_customize);
    }
    
    /**
     * Add colors section
     */
    private static function add_colors_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_colors', [
            'title' => __('Colors', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 10
        ]);
        
        // Primary color
        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default' => '#0EA5E9',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
            'label' => __('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_primary_color'
        ]));
        
        // Secondary color
        $wp_customize->add_setting('aqualuxe_secondary_color', [
            'default' => '#06B6D4',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', [
            'label' => __('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_secondary_color'
        ]));
        
        // Accent color
        $wp_customize->add_setting('aqualuxe_accent_color', [
            'default' => '#F59E0B',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', [
            'label' => __('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_accent_color'
        ]));
    }
    
    /**
     * Add typography section
     */
    private static function add_typography_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_typography', [
            'title' => __('Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 20
        ]);
        
        // Primary font family
        $wp_customize->add_setting('aqualuxe_font_family', [
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_font_family', [
            'label' => __('Primary Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => [
                'Inter' => 'Inter',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
                'Poppins' => 'Poppins'
            ]
        ]);
        
        // Heading font family
        $wp_customize->add_setting('aqualuxe_heading_font_family', [
            'default' => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_heading_font_family', [
            'label' => __('Heading Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => [
                'Playfair Display' => 'Playfair Display',
                'Merriweather' => 'Merriweather',
                'Crimson Text' => 'Crimson Text',
                'Lora' => 'Lora',
                'Source Serif Pro' => 'Source Serif Pro'
            ]
        ]);
        
        // Base font size
        $wp_customize->add_setting('aqualuxe_base_font_size', [
            'default' => 16,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_base_font_size', [
            'label' => __('Base Font Size (px)', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'number',
            'input_attrs' => [
                'min' => 12,
                'max' => 24,
                'step' => 1
            ]
        ]);
    }
    
    /**
     * Add header section
     */
    private static function add_header_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_header', [
            'title' => __('Header', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 30
        ]);
        
        // Logo width
        $wp_customize->add_setting('aqualuxe_logo_width', [
            'default' => 200,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_logo_width', [
            'label' => __('Logo Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'number',
            'input_attrs' => [
                'min' => 100,
                'max' => 500,
                'step' => 10
            ]
        ]);
        
        // Header layout
        $wp_customize->add_setting('aqualuxe_header_layout', [
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_header_layout', [
            'label' => __('Header Layout', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'select',
            'choices' => [
                'default' => __('Default', 'aqualuxe'),
                'centered' => __('Centered', 'aqualuxe'),
                'minimal' => __('Minimal', 'aqualuxe')
            ]
        ]);
        
        // Sticky header
        $wp_customize->add_setting('aqualuxe_sticky_header', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_sticky_header', [
            'label' => __('Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox'
        ]);
    }
    
    /**
     * Add footer section
     */
    private static function add_footer_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_footer', [
            'title' => __('Footer', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 40
        ]);
        
        // Copyright text
        $wp_customize->add_setting('aqualuxe_copyright_text', [
            'default' => '© ' . date('Y') . ' AquaLuxe. All rights reserved.',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_copyright_text', [
            'label' => __('Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'textarea'
        ]);
        
        // Footer background color
        $wp_customize->add_setting('aqualuxe_footer_bg_color', [
            'default' => '#1F2937',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_bg_color', [
            'label' => __('Footer Background Color', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'settings' => 'aqualuxe_footer_bg_color'
        ]));
    }
    
    /**
     * Add homepage section
     */
    private static function add_homepage_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_homepage', [
            'title' => __('Homepage', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 50
        ]);
        
        // Hero title
        $wp_customize->add_setting('aqualuxe_hero_title', [
            'default' => __('Bringing Elegance to Aquatic Life', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_hero_title', [
            'label' => __('Hero Title', 'aqualuxe'),
            'section' => 'aqualuxe_homepage',
            'type' => 'text'
        ]);
        
        // Hero subtitle
        $wp_customize->add_setting('aqualuxe_hero_subtitle', [
            'default' => __('Premium ornamental fish, aquatic plants, and luxury aquarium solutions for enthusiasts worldwide.', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_hero_subtitle', [
            'label' => __('Hero Subtitle', 'aqualuxe'),
            'section' => 'aqualuxe_homepage',
            'type' => 'textarea'
        ]);
        
        // Hero button text
        $wp_customize->add_setting('aqualuxe_hero_button_text', [
            'default' => __('Explore Collection', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_hero_button_text', [
            'label' => __('Hero Button Text', 'aqualuxe'),
            'section' => 'aqualuxe_homepage',
            'type' => 'text'
        ]);
        
        // Hero button URL
        $wp_customize->add_setting('aqualuxe_hero_button_url', [
            'default' => '/shop',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_hero_button_url', [
            'label' => __('Hero Button URL', 'aqualuxe'),
            'section' => 'aqualuxe_homepage',
            'type' => 'url'
        ]);
    }
    
    /**
     * Add contact section
     */
    private static function add_contact_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_contact', [
            'title' => __('Contact Information', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 60
        ]);
        
        // Contact phone
        $wp_customize->add_setting('aqualuxe_contact_phone', [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_contact_phone', [
            'label' => __('Phone Number', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type' => 'tel'
        ]);
        
        // Contact email
        $wp_customize->add_setting('aqualuxe_contact_email', [
            'default' => '',
            'sanitize_callback' => 'sanitize_email',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_contact_email', [
            'label' => __('Email Address', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type' => 'email'
        ]);
        
        // Business address
        $wp_customize->add_setting('aqualuxe_business_address', [
            'default' => '',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_business_address', [
            'label' => __('Business Address', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type' => 'textarea'
        ]);
    }
    
    /**
     * Add social media section
     */
    private static function add_social_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_social', [
            'title' => __('Social Media', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 70
        ]);
        
        $social_networks = [
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'twitter' => 'Twitter',
            'youtube' => 'YouTube',
            'linkedin' => 'LinkedIn'
        ];
        
        foreach ($social_networks as $network => $label) {
            $wp_customize->add_setting('aqualuxe_social_' . $network, [
                'default' => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport' => 'refresh'
            ]);
            
            $wp_customize->add_control('aqualuxe_social_' . $network, [
                'label' => $label . ' ' . __('URL', 'aqualuxe'),
                'section' => 'aqualuxe_social',
                'type' => 'url'
            ]);
        }
    }
    
    /**
     * Add advanced section
     */
    private static function add_advanced_section($wp_customize) {
        $wp_customize->add_section('aqualuxe_advanced', [
            'title' => __('Advanced Options', 'aqualuxe'),
            'panel' => 'aqualuxe_panel',
            'priority' => 80
        ]);
        
        // Custom CSS
        $wp_customize->add_setting('aqualuxe_custom_css', [
            'default' => '',
            'sanitize_callback' => 'wp_strip_all_tags',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_custom_css', [
            'label' => __('Custom CSS', 'aqualuxe'),
            'section' => 'aqualuxe_advanced',
            'type' => 'textarea',
            'description' => __('Add custom CSS here. It will be loaded after the theme styles.', 'aqualuxe')
        ]);
        
        // Google Analytics
        $wp_customize->add_setting('aqualuxe_google_analytics', [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh'
        ]);
        
        $wp_customize->add_control('aqualuxe_google_analytics', [
            'label' => __('Google Analytics ID', 'aqualuxe'),
            'section' => 'aqualuxe_advanced',
            'type' => 'text',
            'description' => __('Enter your Google Analytics tracking ID (e.g., G-XXXXXXXXXX)', 'aqualuxe')
        ]);
    }
    
    /**
     * Load customizer preview script
     */
    public static function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URL . '/js/customizer-preview.js',
            ['customize-preview'],
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Output customizer styles in header
     */
    public static function header_output() {
        $custom_css = get_theme_mod('aqualuxe_custom_css', '');
        if (!empty($custom_css)) {
            echo '<style type="text/css" id="aqualuxe-custom-css">' . $custom_css . '</style>';
        }
        
        // Google Analytics
        $ga_id = get_theme_mod('aqualuxe_google_analytics', '');
        if (!empty($ga_id)) {
            echo "
            <!-- Google Analytics -->
            <script async src=\"https://www.googletagmanager.com/gtag/js?id={$ga_id}\"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{$ga_id}');
            </script>
            ";
        }
    }
    
    /**
     * Set default theme options
     */
    public static function set_defaults() {
        $defaults = [
            'aqualuxe_primary_color' => '#0EA5E9',
            'aqualuxe_secondary_color' => '#06B6D4',
            'aqualuxe_accent_color' => '#F59E0B',
            'aqualuxe_font_family' => 'Inter',
            'aqualuxe_heading_font_family' => 'Playfair Display',
            'aqualuxe_base_font_size' => 16,
            'aqualuxe_logo_width' => 200,
            'aqualuxe_header_layout' => 'default',
            'aqualuxe_sticky_header' => true,
            'aqualuxe_copyright_text' => '© ' . date('Y') . ' AquaLuxe. All rights reserved.',
            'aqualuxe_footer_bg_color' => '#1F2937',
            'aqualuxe_hero_title' => __('Bringing Elegance to Aquatic Life', 'aqualuxe'),
            'aqualuxe_hero_subtitle' => __('Premium ornamental fish, aquatic plants, and luxury aquarium solutions for enthusiasts worldwide.', 'aqualuxe'),
            'aqualuxe_hero_button_text' => __('Explore Collection', 'aqualuxe'),
            'aqualuxe_hero_button_url' => '/shop'
        ];
        
        foreach ($defaults as $option => $value) {
            if (get_theme_mod($option) === false) {
                set_theme_mod($option, $value);
            }
        }
    }
}
