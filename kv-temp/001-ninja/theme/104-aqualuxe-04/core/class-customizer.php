<?php
/**
 * Customizer Class
 * 
 * Handles theme customizer options
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Customizer {
    
    /**
     * Register customizer options
     */
    public function register($wp_customize) {
        // Site Identity enhancements
        $this->add_site_identity_options($wp_customize);
        
        // Colors section
        $this->add_color_options($wp_customize);
        
        // Typography section
        $this->add_typography_options($wp_customize);
        
        // Layout section
        $this->add_layout_options($wp_customize);
        
        // Header section
        $this->add_header_options($wp_customize);
        
        // Footer section
        $this->add_footer_options($wp_customize);
        
        // Contact section
        $this->add_contact_options($wp_customize);
        
        // Social media section
        $this->add_social_options($wp_customize);
        
        // WooCommerce section
        if (aqualuxe_is_woocommerce_active()) {
            $this->add_woocommerce_options($wp_customize);
        }
    }
    
    /**
     * Site Identity options
     */
    private function add_site_identity_options($wp_customize) {
        // Logo height
        $wp_customize->add_setting('aqualuxe_logo_height', [
            'default' => 60,
            'sanitize_callback' => 'absint'
        ]);
        
        $wp_customize->add_control('aqualuxe_logo_height', [
            'label' => esc_html__('Logo Height (px)', 'aqualuxe'),
            'section' => 'title_tagline',
            'type' => 'number',
            'input_attrs' => [
                'min' => 20,
                'max' => 200,
                'step' => 1
            ]
        ]);
    }
    
    /**
     * Color options
     */
    private function add_color_options($wp_customize) {
        $wp_customize->add_section('aqualuxe_colors', [
            'title' => esc_html__('AquaLuxe Colors', 'aqualuxe'),
            'priority' => 40
        ]);
        
        // Primary color
        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default' => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color'
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
            'label' => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors'
        ]));
        
        // Secondary color
        $wp_customize->add_setting('aqualuxe_secondary_color', [
            'default' => '#64748b',
            'sanitize_callback' => 'sanitize_hex_color'
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', [
            'label' => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors'
        ]));
        
        // Accent color
        $wp_customize->add_setting('aqualuxe_accent_color', [
            'default' => '#f37316',
            'sanitize_callback' => 'sanitize_hex_color'
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', [
            'label' => esc_html__('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors'
        ]));
    }
    
    /**
     * Typography options
     */
    private function add_typography_options($wp_customize) {
        $wp_customize->add_section('aqualuxe_typography', [
            'title' => esc_html__('Typography', 'aqualuxe'),
            'priority' => 50
        ]);
        
        // Base font size
        $wp_customize->add_setting('aqualuxe_base_font_size', [
            'default' => 16,
            'sanitize_callback' => 'absint'
        ]);
        
        $wp_customize->add_control('aqualuxe_base_font_size', [
            'label' => esc_html__('Base Font Size (px)', 'aqualuxe'),
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
     * Layout options
     */
    private function add_layout_options($wp_customize) {
        $wp_customize->add_section('aqualuxe_layout', [
            'title' => esc_html__('Layout', 'aqualuxe'),
            'priority' => 60
        ]);
        
        // Container width
        $wp_customize->add_setting('aqualuxe_container_width', [
            'default' => 1200,
            'sanitize_callback' => 'absint'
        ]);
        
        $wp_customize->add_control('aqualuxe_container_width', [
            'label' => esc_html__('Container Max Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'number',
            'input_attrs' => [
                'min' => 960,
                'max' => 1920,
                'step' => 10
            ]
        ]);
        
        // Sidebar position
        $wp_customize->add_setting('aqualuxe_sidebar_position', [
            'default' => 'right',
            'sanitize_callback' => [$this, 'sanitize_select']
        ]);
        
        $wp_customize->add_control('aqualuxe_sidebar_position', [
            'label' => esc_html__('Sidebar Position', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'select',
            'choices' => [
                'left' => esc_html__('Left', 'aqualuxe'),
                'right' => esc_html__('Right', 'aqualuxe'),
                'none' => esc_html__('No Sidebar', 'aqualuxe')
            ]
        ]);
    }
    
    /**
     * Header options
     */
    private function add_header_options($wp_customize) {
        $wp_customize->add_section('aqualuxe_header', [
            'title' => esc_html__('Header Options', 'aqualuxe'),
            'priority' => 70
        ]);
        
        // Sticky header
        $wp_customize->add_setting('aqualuxe_sticky_header', [
            'default' => true,
            'sanitize_callback' => [$this, 'sanitize_checkbox']
        ]);
        
        $wp_customize->add_control('aqualuxe_sticky_header', [
            'label' => esc_html__('Enable Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox'
        ]);
        
        // Header transparency
        $wp_customize->add_setting('aqualuxe_header_transparent', [
            'default' => false,
            'sanitize_callback' => [$this, 'sanitize_checkbox']
        ]);
        
        $wp_customize->add_control('aqualuxe_header_transparent', [
            'label' => esc_html__('Transparent Header on Homepage', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox'
        ]);
    }
    
    /**
     * Footer options
     */
    private function add_footer_options($wp_customize) {
        $wp_customize->add_section('aqualuxe_footer', [
            'title' => esc_html__('Footer Options', 'aqualuxe'),
            'priority' => 80
        ]);
        
        // Footer description
        $wp_customize->add_setting('aqualuxe_footer_description', [
            'default' => 'Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and luxury aquarium solutions.',
            'sanitize_callback' => 'wp_kses_post'
        ]);
        
        $wp_customize->add_control('aqualuxe_footer_description', [
            'label' => esc_html__('Footer Description', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'textarea'
        ]);
        
        // Copyright text
        $wp_customize->add_setting('aqualuxe_copyright_text', [
            'default' => '',
            'sanitize_callback' => 'wp_kses_post'
        ]);
        
        $wp_customize->add_control('aqualuxe_copyright_text', [
            'label' => esc_html__('Custom Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'textarea',
            'description' => esc_html__('Leave empty to use default copyright text.', 'aqualuxe')
        ]);
    }
    
    /**
     * Contact options
     */
    private function add_contact_options($wp_customize) {
        $wp_customize->add_section('aqualuxe_contact', [
            'title' => esc_html__('Contact Information', 'aqualuxe'),
            'priority' => 90
        ]);
        
        // Phone
        $wp_customize->add_setting('aqualuxe_phone', [
            'sanitize_callback' => 'sanitize_text_field'
        ]);
        
        $wp_customize->add_control('aqualuxe_phone', [
            'label' => esc_html__('Phone Number', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type' => 'tel'
        ]);
        
        // Email
        $wp_customize->add_setting('aqualuxe_email', [
            'sanitize_callback' => 'sanitize_email'
        ]);
        
        $wp_customize->add_control('aqualuxe_email', [
            'label' => esc_html__('Email Address', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type' => 'email'
        ]);
        
        // Address
        $wp_customize->add_setting('aqualuxe_address', [
            'sanitize_callback' => 'wp_kses_post'
        ]);
        
        $wp_customize->add_control('aqualuxe_address', [
            'label' => esc_html__('Address', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type' => 'textarea'
        ]);
        
        // Business hours
        $wp_customize->add_setting('aqualuxe_business_hours', [
            'sanitize_callback' => 'wp_kses_post'
        ]);
        
        $wp_customize->add_control('aqualuxe_business_hours', [
            'label' => esc_html__('Business Hours', 'aqualuxe'),
            'section' => 'aqualuxe_contact',
            'type' => 'textarea'
        ]);
    }
    
    /**
     * Social media options
     */
    private function add_social_options($wp_customize) {
        $wp_customize->add_section('aqualuxe_social', [
            'title' => esc_html__('Social Media', 'aqualuxe'),
            'priority' => 100
        ]);
        
        $social_networks = [
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'twitter' => 'Twitter',
            'youtube' => 'YouTube',
            'linkedin' => 'LinkedIn',
            'pinterest' => 'Pinterest'
        ];
        
        foreach ($social_networks as $network => $label) {
            $wp_customize->add_setting('aqualuxe_' . $network . '_url', [
                'sanitize_callback' => 'esc_url_raw'
            ]);
            
            $wp_customize->add_control('aqualuxe_' . $network . '_url', [
                'label' => $label . ' URL',
                'section' => 'aqualuxe_social',
                'type' => 'url'
            ]);
        }
    }
    
    /**
     * WooCommerce options
     */
    private function add_woocommerce_options($wp_customize) {
        $wp_customize->add_section('aqualuxe_woocommerce', [
            'title' => esc_html__('WooCommerce', 'aqualuxe'),
            'priority' => 110
        ]);
        
        // Products per page
        $wp_customize->add_setting('aqualuxe_products_per_page', [
            'default' => 12,
            'sanitize_callback' => 'absint'
        ]);
        
        $wp_customize->add_control('aqualuxe_products_per_page', [
            'label' => esc_html__('Products per Page', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'number',
            'input_attrs' => [
                'min' => 1,
                'max' => 48,
                'step' => 1
            ]
        ]);
        
        // Shop layout
        $wp_customize->add_setting('aqualuxe_shop_layout', [
            'default' => 'grid',
            'sanitize_callback' => [$this, 'sanitize_select']
        ]);
        
        $wp_customize->add_control('aqualuxe_shop_layout', [
            'label' => esc_html__('Shop Layout', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => [
                'grid' => esc_html__('Grid', 'aqualuxe'),
                'list' => esc_html__('List', 'aqualuxe')
            ]
        ]);
    }
    
    /**
     * Sanitize select
     */
    public function sanitize_select($input, $setting) {
        $input = sanitize_key($input);
        $choices = $setting->manager->get_control($setting->id)->choices;
        return array_key_exists($input, $choices) ? $input : $setting->default;
    }
    
    /**
     * Sanitize checkbox
     */
    public function sanitize_checkbox($checked) {
        return ((isset($checked) && true == $checked) ? true : false);
    }
}