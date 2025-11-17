<?php
/**
 * Theme Customizer
 *
 * Handles WordPress Customizer integration with panels, sections, and controls
 *
 * @package AquaLuxe\Inc\Customizer
 * @version 1.0.0
 */

namespace AquaLuxe\Inc\Customizer;

/**
 * Customizer Class
 */
class Customizer {
    
    /**
     * Customizer manager instance
     *
     * @var \WP_Customize_Manager
     */
    private $wp_customize;
    
    /**
     * Constructor
     *
     * @param \WP_Customize_Manager $wp_customize
     */
    public function __construct($wp_customize) {
        $this->wp_customize = $wp_customize;
    }
    
    /**
     * Register customizer settings
     *
     * @return void
     */
    public function register(): void {
        // Remove default sections we don't need
        $this->remove_default_sections();
        
        // Add custom panels
        $this->add_panels();
        
        // Add custom sections
        $this->add_sections();
        
        // Add custom controls
        $this->add_controls();
    }
    
    /**
     * Remove default WordPress customizer sections
     *
     * @return void
     */
    private function remove_default_sections(): void {
        // Remove colors section (we'll create our own)
        $this->wp_customize->remove_section('colors');
        
        // Remove background image section (we'll handle this differently)
        $this->wp_customize->remove_section('background_image');
    }
    
    /**
     * Add customizer panels
     *
     * @return void
     */
    private function add_panels(): void {
        // Theme Options Panel
        $this->wp_customize->add_panel('aqualuxe_theme_options', [
            'title' => __('AquaLuxe Theme Options', 'aqualuxe'),
            'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
            'priority' => 30,
        ]);
        
        // Layout Panel
        $this->wp_customize->add_panel('aqualuxe_layout', [
            'title' => __('Layout Options', 'aqualuxe'),
            'description' => __('Configure layout and structure settings', 'aqualuxe'),
            'priority' => 40,
        ]);
        
        // Typography Panel
        $this->wp_customize->add_panel('aqualuxe_typography', [
            'title' => __('Typography', 'aqualuxe'),
            'description' => __('Customize fonts and typography settings', 'aqualuxe'),
            'priority' => 50,
        ]);
        
        // Performance Panel
        $this->wp_customize->add_panel('aqualuxe_performance', [
            'title' => __('Performance', 'aqualuxe'),
            'description' => __('Performance and optimization settings', 'aqualuxe'),
            'priority' => 60,
        ]);
    }
    
    /**
     * Add customizer sections
     *
     * @return void
     */
    private function add_sections(): void {
        // Brand Identity Section
        $this->wp_customize->add_section('aqualuxe_brand', [
            'title' => __('Brand Identity', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 10,
        ]);
        
        // Colors Section
        $this->wp_customize->add_section('aqualuxe_colors', [
            'title' => __('Colors', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 20,
        ]);
        
        // Dark Mode Section
        $this->wp_customize->add_section('aqualuxe_dark_mode', [
            'title' => __('Dark Mode', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 30,
        ]);
        
        // Header Section
        $this->wp_customize->add_section('aqualuxe_header', [
            'title' => __('Header Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_layout',
            'priority' => 10,
        ]);
        
        // Footer Section
        $this->wp_customize->add_section('aqualuxe_footer', [
            'title' => __('Footer Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_layout',
            'priority' => 20,
        ]);
        
        // Blog Section
        $this->wp_customize->add_section('aqualuxe_blog', [
            'title' => __('Blog Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_layout',
            'priority' => 30,
        ]);
        
        // Typography Settings
        $this->wp_customize->add_section('aqualuxe_typography_settings', [
            'title' => __('Typography Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_typography',
            'priority' => 10,
        ]);
    }
    
    /**
     * Add customizer controls
     *
     * @return void
     */
    private function add_controls(): void {
        $this->add_brand_controls();
        $this->add_color_controls();
        $this->add_dark_mode_controls();
        $this->add_header_controls();
        $this->add_footer_controls();
        $this->add_blog_controls();
        $this->add_typography_controls();
    }
    
    /**
     * Add brand identity controls
     *
     * @return void
     */
    private function add_brand_controls(): void {
        // Tagline
        $this->wp_customize->add_setting('aqualuxe_tagline', [
            'default' => __('Bringing elegance to aquatic life – globally', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_tagline', [
            'label' => __('Brand Tagline', 'aqualuxe'),
            'section' => 'aqualuxe_brand',
            'type' => 'textarea',
        ]);
        
        // Logo Size
        $this->wp_customize->add_setting('aqualuxe_logo_size', [
            'default' => 150,
            'sanitize_callback' => 'absint',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_logo_size', [
            'label' => __('Logo Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_brand',
            'type' => 'range',
            'input_attrs' => [
                'min' => 50,
                'max' => 300,
                'step' => 10,
            ],
        ]);
    }
    
    /**
     * Add color controls
     *
     * @return void
     */
    private function add_color_controls(): void {
        // Primary Color
        $this->wp_customize->add_setting('aqualuxe_primary_color', [
            'default' => '#06a9d6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $this->wp_customize->add_control(new \WP_Customize_Color_Control($this->wp_customize, 'aqualuxe_primary_color', [
            'label' => __('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
        ]));
        
        // Secondary Color
        $this->wp_customize->add_setting('aqualuxe_secondary_color', [
            'default' => '#64748b',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $this->wp_customize->add_control(new \WP_Customize_Color_Control($this->wp_customize, 'aqualuxe_secondary_color', [
            'label' => __('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
        ]));
        
        // Accent Color
        $this->wp_customize->add_setting('aqualuxe_accent_color', [
            'default' => '#f43f5e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $this->wp_customize->add_control(new \WP_Customize_Color_Control($this->wp_customize, 'aqualuxe_accent_color', [
            'label' => __('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
        ]));
    }
    
    /**
     * Add dark mode controls
     *
     * @return void
     */
    private function add_dark_mode_controls(): void {
        // Enable Dark Mode
        $this->wp_customize->add_setting('aqualuxe_dark_mode_enabled', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_dark_mode_enabled', [
            'label' => __('Enable Dark Mode Toggle', 'aqualuxe'),
            'description' => __('Allow users to switch between light and dark themes', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);
        
        // Default Dark Mode
        $this->wp_customize->add_setting('aqualuxe_default_dark_mode', [
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_default_dark_mode', [
            'label' => __('Default to Dark Mode', 'aqualuxe'),
            'description' => __('Start in dark mode by default (users can still toggle)', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);
    }
    
    /**
     * Add header controls
     *
     * @return void
     */
    private function add_header_controls(): void {
        // Sticky Header
        $this->wp_customize->add_setting('aqualuxe_sticky_header', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_sticky_header', [
            'label' => __('Sticky Header', 'aqualuxe'),
            'description' => __('Keep header visible when scrolling', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ]);
        
        // Show Top Bar
        $this->wp_customize->add_setting('aqualuxe_show_top_bar', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_show_top_bar', [
            'label' => __('Show Top Bar', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ]);
        
        // Header Layout
        $this->wp_customize->add_setting('aqualuxe_header_layout', [
            'default' => 'standard',
            'sanitize_callback' => [$this, 'sanitize_select'],
        ]);
        
        $this->wp_customize->add_control('aqualuxe_header_layout', [
            'label' => __('Header Layout', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'select',
            'choices' => [
                'standard' => __('Standard', 'aqualuxe'),
                'centered' => __('Centered', 'aqualuxe'),
                'minimal' => __('Minimal', 'aqualuxe'),
            ],
        ]);
    }
    
    /**
     * Add footer controls
     *
     * @return void
     */
    private function add_footer_controls(): void {
        // Footer Layout
        $this->wp_customize->add_setting('aqualuxe_footer_columns', [
            'default' => 4,
            'sanitize_callback' => 'absint',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_footer_columns', [
            'label' => __('Footer Columns', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'select',
            'choices' => [
                1 => __('1 Column', 'aqualuxe'),
                2 => __('2 Columns', 'aqualuxe'),
                3 => __('3 Columns', 'aqualuxe'),
                4 => __('4 Columns', 'aqualuxe'),
            ],
        ]);
        
        // Copyright Text
        $this->wp_customize->add_setting('aqualuxe_copyright_text', [
            'default' => sprintf(__('© %s %s. All rights reserved.', 'aqualuxe'), date('Y'), get_bloginfo('name')),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_copyright_text', [
            'label' => __('Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer',
            'type' => 'textarea',
        ]);
    }
    
    /**
     * Add blog controls
     *
     * @return void
     */
    private function add_blog_controls(): void {
        // Blog Layout
        $this->wp_customize->add_setting('aqualuxe_blog_layout', [
            'default' => 'grid',
            'sanitize_callback' => [$this, 'sanitize_select'],
        ]);
        
        $this->wp_customize->add_control('aqualuxe_blog_layout', [
            'label' => __('Blog Layout', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'select',
            'choices' => [
                'grid' => __('Grid', 'aqualuxe'),
                'list' => __('List', 'aqualuxe'),
                'masonry' => __('Masonry', 'aqualuxe'),
            ],
        ]);
        
        // Excerpt Length
        $this->wp_customize->add_setting('aqualuxe_excerpt_length', [
            'default' => 25,
            'sanitize_callback' => 'absint',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_excerpt_length', [
            'label' => __('Excerpt Length (words)', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'number',
            'input_attrs' => [
                'min' => 10,
                'max' => 100,
            ],
        ]);
        
        // Show Featured Images
        $this->wp_customize->add_setting('aqualuxe_show_featured_images', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_show_featured_images', [
            'label' => __('Show Featured Images', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'checkbox',
        ]);
    }
    
    /**
     * Add typography controls
     *
     * @return void
     */
    private function add_typography_controls(): void {
        // Body Font
        $this->wp_customize->add_setting('aqualuxe_body_font', [
            'default' => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_body_font', [
            'label' => __('Body Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography_settings',
            'type' => 'select',
            'choices' => [
                'Inter' => 'Inter',
                'Open Sans' => 'Open Sans',
                'Roboto' => 'Roboto',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
            ],
        ]);
        
        // Heading Font
        $this->wp_customize->add_setting('aqualuxe_heading_font', [
            'default' => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $this->wp_customize->add_control('aqualuxe_heading_font', [
            'label' => __('Heading Font', 'aqualuxe'),
            'section' => 'aqualuxe_typography_settings',
            'type' => 'select',
            'choices' => [
                'Playfair Display' => 'Playfair Display',
                'Merriweather' => 'Merriweather',
                'Lora' => 'Lora',
                'Crimson Text' => 'Crimson Text',
                'Inter' => 'Inter',
            ],
        ]);
        
        // Font Size Scale
        $this->wp_customize->add_setting('aqualuxe_font_scale', [
            'default' => 1.0,
            'sanitize_callback' => [$this, 'sanitize_float'],
        ]);
        
        $this->wp_customize->add_control('aqualuxe_font_scale', [
            'label' => __('Font Size Scale', 'aqualuxe'),
            'section' => 'aqualuxe_typography_settings',
            'type' => 'range',
            'input_attrs' => [
                'min' => 0.8,
                'max' => 1.4,
                'step' => 0.1,
            ],
        ]);
    }
    
    /**
     * Sanitize select options
     *
     * @param string $input
     * @param object $setting
     * @return string
     */
    public function sanitize_select($input, $setting = null) {
        // Get list of choices from the control associated with the setting
        $choices = [];
        if ($setting) {
            $control = $this->wp_customize->get_control($setting->id);
            if ($control && isset($control->choices)) {
                $choices = $control->choices;
            }
        }
        
        return array_key_exists($input, $choices) ? $input : '';
    }
    
    /**
     * Sanitize float values
     *
     * @param float $input
     * @return float
     */
    public function sanitize_float($input) {
        return floatval($input);
    }
}