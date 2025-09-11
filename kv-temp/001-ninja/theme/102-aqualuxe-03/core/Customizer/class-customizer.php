<?php
/**
 * Theme Customizer Class
 *
 * Handles all theme customization options.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customizer Class
 */
class AquaLuxe_Customizer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('customize_register', array($this, 'register'));
        add_action('customize_preview_init', array($this, 'enqueue_preview_scripts'));
        add_action('wp_head', array($this, 'output_css'));
    }
    
    /**
     * Register customizer settings
     */
    public function register($wp_customize) {
        // Add panels
        $this->add_panels($wp_customize);
        
        // Add sections
        $this->add_sections($wp_customize);
        
        // Add settings and controls
        $this->add_site_identity_settings($wp_customize);
        $this->add_color_settings($wp_customize);
        $this->add_typography_settings($wp_customize);
        $this->add_layout_settings($wp_customize);
        $this->add_header_settings($wp_customize);
        $this->add_footer_settings($wp_customize);
        $this->add_blog_settings($wp_customize);
        $this->add_woocommerce_settings($wp_customize);
        $this->add_performance_settings($wp_customize);
        $this->add_social_settings($wp_customize);
    }
    
    /**
     * Add panels
     */
    private function add_panels($wp_customize) {
        // Theme Options Panel
        $wp_customize->add_panel('aqualuxe_theme_options', array(
            'title' => __('AquaLuxe Theme Options', 'aqualuxe'),
            'description' => __('Customize your AquaLuxe theme settings.', 'aqualuxe'),
            'priority' => 30,
        ));
        
        // Header Panel
        $wp_customize->add_panel('aqualuxe_header', array(
            'title' => __('Header Settings', 'aqualuxe'),
            'description' => __('Customize header appearance and functionality.', 'aqualuxe'),
            'priority' => 40,
        ));
        
        // Footer Panel
        $wp_customize->add_panel('aqualuxe_footer', array(
            'title' => __('Footer Settings', 'aqualuxe'),
            'description' => __('Customize footer appearance and content.', 'aqualuxe'),
            'priority' => 50,
        ));
    }
    
    /**
     * Add sections
     */
    private function add_sections($wp_customize) {
        // Colors Section
        $wp_customize->add_section('aqualuxe_colors', array(
            'title' => __('Colors', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 10,
        ));
        
        // Typography Section
        $wp_customize->add_section('aqualuxe_typography', array(
            'title' => __('Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 20,
        ));
        
        // Layout Section
        $wp_customize->add_section('aqualuxe_layout', array(
            'title' => __('Layout', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 30,
        ));
        
        // Header Sections
        $wp_customize->add_section('aqualuxe_header_layout', array(
            'title' => __('Header Layout', 'aqualuxe'),
            'panel' => 'aqualuxe_header',
            'priority' => 10,
        ));
        
        $wp_customize->add_section('aqualuxe_header_styles', array(
            'title' => __('Header Styles', 'aqualuxe'),
            'panel' => 'aqualuxe_header',
            'priority' => 20,
        ));
        
        // Footer Sections
        $wp_customize->add_section('aqualuxe_footer_layout', array(
            'title' => __('Footer Layout', 'aqualuxe'),
            'panel' => 'aqualuxe_footer',
            'priority' => 10,
        ));
        
        $wp_customize->add_section('aqualuxe_footer_styles', array(
            'title' => __('Footer Styles', 'aqualuxe'),
            'panel' => 'aqualuxe_footer',
            'priority' => 20,
        ));
        
        // Blog Section
        $wp_customize->add_section('aqualuxe_blog', array(
            'title' => __('Blog Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 40,
        ));
        
        // WooCommerce Section
        if (aqualuxe_is_woocommerce_activated()) {
            $wp_customize->add_section('aqualuxe_woocommerce', array(
                'title' => __('WooCommerce Settings', 'aqualuxe'),
                'panel' => 'aqualuxe_theme_options',
                'priority' => 50,
            ));
        }
        
        // Performance Section
        $wp_customize->add_section('aqualuxe_performance', array(
            'title' => __('Performance', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 60,
        ));
        
        // Social Media Section
        $wp_customize->add_section('aqualuxe_social', array(
            'title' => __('Social Media', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 70,
        ));
    }
    
    /**
     * Add site identity settings
     */
    private function add_site_identity_settings($wp_customize) {
        // Site tagline display
        $wp_customize->add_setting('display_tagline', array(
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $wp_customize->add_control('display_tagline', array(
            'label' => __('Display Tagline', 'aqualuxe'),
            'section' => 'title_tagline',
            'type' => 'checkbox',
        ));
    }
    
    /**
     * Add color settings
     */
    private function add_color_settings($wp_customize) {
        // Primary Color
        $wp_customize->add_setting('primary_color', array(
            'default' => '#14b8a6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
            'label' => __('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'description' => __('Used for buttons, links, and accents.', 'aqualuxe'),
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
            'description' => __('Used for text and neutral elements.', 'aqualuxe'),
        )));
        
        // Accent Color
        $wp_customize->add_setting('accent_color', array(
            'default' => '#d946ef',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
            'label' => __('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'description' => __('Used for highlights and special elements.', 'aqualuxe'),
        )));
    }
    
    /**
     * Add typography settings
     */
    private function add_typography_settings($wp_customize) {
        // Body Font Size
        $wp_customize->add_setting('body_font_size', array(
            'default' => '16',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control('body_font_size', array(
            'label' => __('Body Font Size (px)', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 12,
                'max' => 24,
                'step' => 1,
            ),
        ));
        
        // Heading Font Weight
        $wp_customize->add_setting('heading_font_weight', array(
            'default' => '600',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control('heading_font_weight', array(
            'label' => __('Heading Font Weight', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'select',
            'choices' => array(
                '400' => __('Normal', 'aqualuxe'),
                '500' => __('Medium', 'aqualuxe'),
                '600' => __('Semi Bold', 'aqualuxe'),
                '700' => __('Bold', 'aqualuxe'),
            ),
        ));
    }
    
    /**
     * Add layout settings
     */
    private function add_layout_settings($wp_customize) {
        // Container Width
        $wp_customize->add_setting('container_width', array(
            'default' => '1200',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control('container_width', array(
            'label' => __('Container Max Width (px)', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'range',
            'input_attrs' => array(
                'min' => 960,
                'max' => 1600,
                'step' => 40,
            ),
        ));
        
        // Sidebar Position
        $wp_customize->add_setting('sidebar_position', array(
            'default' => 'right',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
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
    }
    
    /**
     * Add header settings
     */
    private function add_header_settings($wp_customize) {
        // Header Style
        $wp_customize->add_setting('header_style', array(
            'default' => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ));
        
        $wp_customize->add_control('header_style', array(
            'label' => __('Header Style', 'aqualuxe'),
            'section' => 'aqualuxe_header_layout',
            'type' => 'select',
            'choices' => array(
                'default' => __('Default', 'aqualuxe'),
                'minimal' => __('Minimal', 'aqualuxe'),
                'centered' => __('Centered', 'aqualuxe'),
            ),
        ));
        
        // Sticky Header
        $wp_customize->add_setting('sticky_header', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $wp_customize->add_control('sticky_header', array(
            'label' => __('Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header_layout',
            'type' => 'checkbox',
        ));
        
        // Header Background Color
        $wp_customize->add_setting('header_background_color', array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_background_color', array(
            'label' => __('Header Background Color', 'aqualuxe'),
            'section' => 'aqualuxe_header_styles',
        )));
    }
    
    /**
     * Add footer settings
     */
    private function add_footer_settings($wp_customize) {
        // Footer Widget Columns
        $wp_customize->add_setting('footer_widget_columns', array(
            'default' => '4',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ));
        
        $wp_customize->add_control('footer_widget_columns', array(
            'label' => __('Footer Widget Columns', 'aqualuxe'),
            'section' => 'aqualuxe_footer_layout',
            'type' => 'select',
            'choices' => array(
                '1' => __('1 Column', 'aqualuxe'),
                '2' => __('2 Columns', 'aqualuxe'),
                '3' => __('3 Columns', 'aqualuxe'),
                '4' => __('4 Columns', 'aqualuxe'),
            ),
        ));
        
        // Copyright Text
        $wp_customize->add_setting('footer_copyright_text', array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control('footer_copyright_text', array(
            'label' => __('Copyright Text', 'aqualuxe'),
            'section' => 'aqualuxe_footer_layout',
            'type' => 'textarea',
            'description' => __('Leave empty to use default copyright text.', 'aqualuxe'),
        ));
    }
    
    /**
     * Add blog settings
     */
    private function add_blog_settings($wp_customize) {
        // Show Reading Time
        $wp_customize->add_setting('show_reading_time', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $wp_customize->add_control('show_reading_time', array(
            'label' => __('Show Reading Time', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'checkbox',
        ));
        
        // Show Reading Progress
        $wp_customize->add_setting('show_reading_progress', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $wp_customize->add_control('show_reading_progress', array(
            'label' => __('Show Reading Progress Bar', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'checkbox',
        ));
        
        // Related Posts Count
        $wp_customize->add_setting('related_posts_count', array(
            'default' => '3',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('related_posts_count', array(
            'label' => __('Related Posts Count', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 0,
                'max' => 12,
            ),
        ));
    }
    
    /**
     * Add WooCommerce settings
     */
    private function add_woocommerce_settings($wp_customize) {
        if (!aqualuxe_is_woocommerce_activated()) {
            return;
        }
        
        // Products per Row
        $wp_customize->add_setting('products_per_row', array(
            'default' => '4',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        ));
        
        $wp_customize->add_control('products_per_row', array(
            'label' => __('Products per Row', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => array(
                '2' => __('2 Products', 'aqualuxe'),
                '3' => __('3 Products', 'aqualuxe'),
                '4' => __('4 Products', 'aqualuxe'),
                '5' => __('5 Products', 'aqualuxe'),
            ),
        ));
        
        // Show Product Quick View
        $wp_customize->add_setting('show_quick_view', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $wp_customize->add_control('show_quick_view', array(
            'label' => __('Enable Quick View', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
        ));
    }
    
    /**
     * Add performance settings
     */
    private function add_performance_settings($wp_customize) {
        // Lazy Loading
        $wp_customize->add_setting('enable_lazy_loading', array(
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $wp_customize->add_control('enable_lazy_loading', array(
            'label' => __('Enable Lazy Loading', 'aqualuxe'),
            'section' => 'aqualuxe_performance',
            'type' => 'checkbox',
            'description' => __('Improves page load times by loading images when needed.', 'aqualuxe'),
        ));
        
        // Minify CSS
        $wp_customize->add_setting('minify_css', array(
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ));
        
        $wp_customize->add_control('minify_css', array(
            'label' => __('Minify CSS', 'aqualuxe'),
            'section' => 'aqualuxe_performance',
            'type' => 'checkbox',
            'description' => __('Reduces CSS file sizes for faster loading.', 'aqualuxe'),
        ));
    }
    
    /**
     * Add social settings
     */
    private function add_social_settings($wp_customize) {
        $social_networks = array(
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube',
            'pinterest' => 'Pinterest',
        );
        
        foreach ($social_networks as $network => $label) {
            $wp_customize->add_setting('social_' . $network, array(
                'default' => '',
                'sanitize_callback' => 'esc_url_raw',
            ));
            
            $wp_customize->add_control('social_' . $network, array(
                'label' => sprintf(__('%s URL', 'aqualuxe'), $label),
                'section' => 'aqualuxe_social',
                'type' => 'url',
            ));
        }
    }
    
    /**
     * Enqueue customizer preview scripts
     */
    public function enqueue_preview_scripts() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_THEME_URI . '/assets/dist/js/customizer.js',
            array('customize-preview'),
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Output custom CSS to head
     */
    public function output_css() {
        $css = '';
        
        // Primary color
        $primary_color = get_theme_mod('primary_color', '#14b8a6');
        if ($primary_color !== '#14b8a6') {
            $css .= ':root { --color-primary: ' . esc_attr($primary_color) . '; }';
        }
        
        // Secondary color
        $secondary_color = get_theme_mod('secondary_color', '#64748b');
        if ($secondary_color !== '#64748b') {
            $css .= ':root { --color-secondary: ' . esc_attr($secondary_color) . '; }';
        }
        
        // Accent color
        $accent_color = get_theme_mod('accent_color', '#d946ef');
        if ($accent_color !== '#d946ef') {
            $css .= ':root { --color-accent: ' . esc_attr($accent_color) . '; }';
        }
        
        // Body font size
        $body_font_size = get_theme_mod('body_font_size', 16);
        if ($body_font_size !== 16) {
            $css .= 'body { font-size: ' . absint($body_font_size) . 'px; }';
        }
        
        // Container width
        $container_width = get_theme_mod('container_width', 1200);
        if ($container_width !== 1200) {
            $css .= '.container { max-width: ' . absint($container_width) . 'px; }';
        }
        
        // Header background color
        $header_bg_color = get_theme_mod('header_background_color', '#ffffff');
        if ($header_bg_color !== '#ffffff') {
            $css .= '.site-header { background-color: ' . esc_attr($header_bg_color) . '; }';
        }
        
        if ($css) {
            echo '<style id="aqualuxe-customizer-css">' . aqualuxe_minify_css($css) . '</style>';
        }
    }
}

/**
 * Sanitize select fields
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

// Initialize customizer
new AquaLuxe_Customizer();