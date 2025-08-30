<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Customizer Class
 * 
 * Handles the theme customizer settings
 */
class AquaLuxe_Customizer {
    /**
     * Constructor
     */
    public function __construct() {
        // Register customizer settings
        add_action('customize_register', array($this, 'register_customizer_settings'));
        
        // Add customizer preview script
        add_action('customize_preview_init', array($this, 'customize_preview_js'));
        
        // Add customizer controls script
        add_action('customize_controls_enqueue_scripts', array($this, 'customize_controls_js'));
    }

    /**
     * Register customizer settings
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function register_customizer_settings($wp_customize) {
        // Add panels
        $this->add_panels($wp_customize);
        
        // Add sections
        $this->add_sections($wp_customize);
        
        // Add settings and controls
        $this->add_general_settings($wp_customize);
        $this->add_header_settings($wp_customize);
        $this->add_footer_settings($wp_customize);
        $this->add_typography_settings($wp_customize);
        $this->add_color_settings($wp_customize);
        $this->add_blog_settings($wp_customize);
        
        // Add WooCommerce settings if WooCommerce is active
        if (class_exists('WooCommerce')) {
            $this->add_woocommerce_settings($wp_customize);
        }
    }

    /**
     * Add panels to the customizer
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_panels($wp_customize) {
        // General Panel
        $wp_customize->add_panel('aqualuxe_general_panel', array(
            'title'       => esc_html__('AquaLuxe General Settings', 'aqualuxe'),
            'description' => esc_html__('Customize general theme settings', 'aqualuxe'),
            'priority'    => 10,
        ));
        
        // Header Panel
        $wp_customize->add_panel('aqualuxe_header_panel', array(
            'title'       => esc_html__('Header Settings', 'aqualuxe'),
            'description' => esc_html__('Customize header layout and elements', 'aqualuxe'),
            'priority'    => 20,
        ));
        
        // Footer Panel
        $wp_customize->add_panel('aqualuxe_footer_panel', array(
            'title'       => esc_html__('Footer Settings', 'aqualuxe'),
            'description' => esc_html__('Customize footer layout and elements', 'aqualuxe'),
            'priority'    => 30,
        ));
        
        // Typography Panel
        $wp_customize->add_panel('aqualuxe_typography_panel', array(
            'title'       => esc_html__('Typography', 'aqualuxe'),
            'description' => esc_html__('Customize fonts and typography', 'aqualuxe'),
            'priority'    => 40,
        ));
        
        // Colors Panel
        $wp_customize->add_panel('aqualuxe_colors_panel', array(
            'title'       => esc_html__('Colors', 'aqualuxe'),
            'description' => esc_html__('Customize theme colors', 'aqualuxe'),
            'priority'    => 50,
        ));
        
        // Blog Panel
        $wp_customize->add_panel('aqualuxe_blog_panel', array(
            'title'       => esc_html__('Blog Settings', 'aqualuxe'),
            'description' => esc_html__('Customize blog layout and elements', 'aqualuxe'),
            'priority'    => 60,
        ));
        
        // WooCommerce Panel (if WooCommerce is active)
        if (class_exists('WooCommerce')) {
            $wp_customize->add_panel('aqualuxe_woocommerce_panel', array(
                'title'       => esc_html__('AquaLuxe Shop Settings', 'aqualuxe'),
                'description' => esc_html__('Customize shop layout and elements', 'aqualuxe'),
                'priority'    => 70,
            ));
        }
    }

    /**
     * Add sections to the customizer
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_sections($wp_customize) {
        // General Sections
        $wp_customize->add_section('aqualuxe_general_layout', array(
            'title'       => esc_html__('Layout', 'aqualuxe'),
            'description' => esc_html__('Configure general layout settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_general_panel',
            'priority'    => 10,
        ));
        
        $wp_customize->add_section('aqualuxe_general_buttons', array(
            'title'       => esc_html__('Buttons', 'aqualuxe'),
            'description' => esc_html__('Configure button styles', 'aqualuxe'),
            'panel'       => 'aqualuxe_general_panel',
            'priority'    => 20,
        ));
        
        $wp_customize->add_section('aqualuxe_general_breadcrumbs', array(
            'title'       => esc_html__('Breadcrumbs', 'aqualuxe'),
            'description' => esc_html__('Configure breadcrumb settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_general_panel',
            'priority'    => 30,
        ));
        
        // Header Sections
        $wp_customize->add_section('aqualuxe_header_layout', array(
            'title'       => esc_html__('Header Layout', 'aqualuxe'),
            'description' => esc_html__('Configure header layout', 'aqualuxe'),
            'panel'       => 'aqualuxe_header_panel',
            'priority'    => 10,
        ));
        
        $wp_customize->add_section('aqualuxe_header_logo', array(
            'title'       => esc_html__('Logo & Site Identity', 'aqualuxe'),
            'description' => esc_html__('Configure logo and site identity', 'aqualuxe'),
            'panel'       => 'aqualuxe_header_panel',
            'priority'    => 20,
        ));
        
        $wp_customize->add_section('aqualuxe_header_navigation', array(
            'title'       => esc_html__('Navigation', 'aqualuxe'),
            'description' => esc_html__('Configure navigation settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_header_panel',
            'priority'    => 30,
        ));
        
        // Footer Sections
        $wp_customize->add_section('aqualuxe_footer_layout', array(
            'title'       => esc_html__('Footer Layout', 'aqualuxe'),
            'description' => esc_html__('Configure footer layout', 'aqualuxe'),
            'panel'       => 'aqualuxe_footer_panel',
            'priority'    => 10,
        ));
        
        $wp_customize->add_section('aqualuxe_footer_widgets', array(
            'title'       => esc_html__('Footer Widgets', 'aqualuxe'),
            'description' => esc_html__('Configure footer widgets', 'aqualuxe'),
            'panel'       => 'aqualuxe_footer_panel',
            'priority'    => 20,
        ));
        
        $wp_customize->add_section('aqualuxe_footer_copyright', array(
            'title'       => esc_html__('Copyright', 'aqualuxe'),
            'description' => esc_html__('Configure copyright text', 'aqualuxe'),
            'panel'       => 'aqualuxe_footer_panel',
            'priority'    => 30,
        ));
        
        // Typography Sections
        $wp_customize->add_section('aqualuxe_typography_general', array(
            'title'       => esc_html__('General Typography', 'aqualuxe'),
            'description' => esc_html__('Configure general typography settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_typography_panel',
            'priority'    => 10,
        ));
        
        $wp_customize->add_section('aqualuxe_typography_headings', array(
            'title'       => esc_html__('Headings', 'aqualuxe'),
            'description' => esc_html__('Configure headings typography', 'aqualuxe'),
            'panel'       => 'aqualuxe_typography_panel',
            'priority'    => 20,
        ));
        
        // Colors Sections
        $wp_customize->add_section('aqualuxe_colors_general', array(
            'title'       => esc_html__('General Colors', 'aqualuxe'),
            'description' => esc_html__('Configure general color settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_colors_panel',
            'priority'    => 10,
        ));
        
        $wp_customize->add_section('aqualuxe_colors_header', array(
            'title'       => esc_html__('Header Colors', 'aqualuxe'),
            'description' => esc_html__('Configure header colors', 'aqualuxe'),
            'panel'       => 'aqualuxe_colors_panel',
            'priority'    => 20,
        ));
        
        $wp_customize->add_section('aqualuxe_colors_footer', array(
            'title'       => esc_html__('Footer Colors', 'aqualuxe'),
            'description' => esc_html__('Configure footer colors', 'aqualuxe'),
            'panel'       => 'aqualuxe_colors_panel',
            'priority'    => 30,
        ));
        
        // Blog Sections
        $wp_customize->add_section('aqualuxe_blog_archive', array(
            'title'       => esc_html__('Blog Archive', 'aqualuxe'),
            'description' => esc_html__('Configure blog archive settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_blog_panel',
            'priority'    => 10,
        ));
        
        $wp_customize->add_section('aqualuxe_blog_single', array(
            'title'       => esc_html__('Single Post', 'aqualuxe'),
            'description' => esc_html__('Configure single post settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_blog_panel',
            'priority'    => 20,
        ));
        
        // WooCommerce Sections (if WooCommerce is active)
        if (class_exists('WooCommerce')) {
            $wp_customize->add_section('aqualuxe_woocommerce_shop', array(
                'title'       => esc_html__('Shop Page', 'aqualuxe'),
                'description' => esc_html__('Configure shop page settings', 'aqualuxe'),
                'panel'       => 'aqualuxe_woocommerce_panel',
                'priority'    => 10,
            ));
            
            $wp_customize->add_section('aqualuxe_woocommerce_product', array(
                'title'       => esc_html__('Product Page', 'aqualuxe'),
                'description' => esc_html__('Configure product page settings', 'aqualuxe'),
                'panel'       => 'aqualuxe_woocommerce_panel',
                'priority'    => 20,
            ));
            
            $wp_customize->add_section('aqualuxe_woocommerce_cart', array(
                'title'       => esc_html__('Cart & Checkout', 'aqualuxe'),
                'description' => esc_html__('Configure cart and checkout settings', 'aqualuxe'),
                'panel'       => 'aqualuxe_woocommerce_panel',
                'priority'    => 30,
            ));
        }
    }

    /**
     * Add general settings to the customizer
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_general_settings($wp_customize) {
        // Container Width
        $wp_customize->add_setting('aqualuxe_options[container_width]', array(
            'default'           => '1200',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_container_width', array(
            'label'       => esc_html__('Container Width (px)', 'aqualuxe'),
            'description' => esc_html__('Set the width of the main container in pixels', 'aqualuxe'),
            'section'     => 'aqualuxe_general_layout',
            'settings'    => 'aqualuxe_options[container_width]',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1920,
                'step' => 10,
            ),
        ));
        
        // Sidebar Position
        $wp_customize->add_setting('aqualuxe_options[sidebar_position]', array(
            'default'           => 'right',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_sidebar_position', array(
            'label'       => esc_html__('Sidebar Position', 'aqualuxe'),
            'description' => esc_html__('Choose the default sidebar position', 'aqualuxe'),
            'section'     => 'aqualuxe_general_layout',
            'settings'    => 'aqualuxe_options[sidebar_position]',
            'type'        => 'select',
            'choices'     => array(
                'right' => esc_html__('Right', 'aqualuxe'),
                'left'  => esc_html__('Left', 'aqualuxe'),
                'none'  => esc_html__('No Sidebar', 'aqualuxe'),
            ),
        ));
        
        // Button Style
        $wp_customize->add_setting('aqualuxe_options[button_style]', array(
            'default'           => 'rounded',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_button_style', array(
            'label'       => esc_html__('Button Style', 'aqualuxe'),
            'description' => esc_html__('Choose the default button style', 'aqualuxe'),
            'section'     => 'aqualuxe_general_buttons',
            'settings'    => 'aqualuxe_options[button_style]',
            'type'        => 'select',
            'choices'     => array(
                'rounded'    => esc_html__('Rounded', 'aqualuxe'),
                'pill'       => esc_html__('Pill', 'aqualuxe'),
                'square'     => esc_html__('Square', 'aqualuxe'),
                'underlined' => esc_html__('Underlined', 'aqualuxe'),
            ),
        ));
        
        // Enable Breadcrumbs
        $wp_customize->add_setting('aqualuxe_options[enable_breadcrumbs]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_enable_breadcrumbs', array(
            'label'       => esc_html__('Enable Breadcrumbs', 'aqualuxe'),
            'description' => esc_html__('Show breadcrumbs on pages and posts', 'aqualuxe'),
            'section'     => 'aqualuxe_general_breadcrumbs',
            'settings'    => 'aqualuxe_options[enable_breadcrumbs]',
            'type'        => 'checkbox',
        ));
    }

    /**
     * Add header settings to the customizer
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_header_settings($wp_customize) {
        // Header Layout
        $wp_customize->add_setting('aqualuxe_options[header_layout]', array(
            'default'           => 'default',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_header_layout', array(
            'label'       => esc_html__('Header Layout', 'aqualuxe'),
            'description' => esc_html__('Choose the header layout', 'aqualuxe'),
            'section'     => 'aqualuxe_header_layout',
            'settings'    => 'aqualuxe_options[header_layout]',
            'type'        => 'select',
            'choices'     => array(
                'default'      => esc_html__('Default', 'aqualuxe'),
                'centered'     => esc_html__('Centered', 'aqualuxe'),
                'transparent'  => esc_html__('Transparent', 'aqualuxe'),
                'split'        => esc_html__('Split Menu', 'aqualuxe'),
                'hamburger'    => esc_html__('Hamburger Menu', 'aqualuxe'),
            ),
        ));
        
        // Sticky Header
        $wp_customize->add_setting('aqualuxe_options[sticky_header]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_sticky_header', array(
            'label'       => esc_html__('Sticky Header', 'aqualuxe'),
            'description' => esc_html__('Enable sticky header on scroll', 'aqualuxe'),
            'section'     => 'aqualuxe_header_layout',
            'settings'    => 'aqualuxe_options[sticky_header]',
            'type'        => 'checkbox',
        ));
        
        // Header Top Bar
        $wp_customize->add_setting('aqualuxe_options[header_top_bar]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_header_top_bar', array(
            'label'       => esc_html__('Enable Top Bar', 'aqualuxe'),
            'description' => esc_html__('Show top bar above header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_layout',
            'settings'    => 'aqualuxe_options[header_top_bar]',
            'type'        => 'checkbox',
        ));
        
        // Top Bar Content
        $wp_customize->add_setting('aqualuxe_options[top_bar_content]', array(
            'default'           => esc_html__('Welcome to AquaLuxe - Premium Ornamental Aquatic Solutions', 'aqualuxe'),
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_top_bar_content', array(
            'label'       => esc_html__('Top Bar Content', 'aqualuxe'),
            'description' => esc_html__('Text to display in the top bar', 'aqualuxe'),
            'section'     => 'aqualuxe_header_layout',
            'settings'    => 'aqualuxe_options[top_bar_content]',
            'type'        => 'text',
            'active_callback' => function() {
                return aqualuxe_get_option('header_top_bar', true);
            },
        ));
        
        // Header Contact Phone
        $wp_customize->add_setting('aqualuxe_options[header_phone]', array(
            'default'           => '+1 (555) 123-4567',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_header_phone', array(
            'label'       => esc_html__('Header Phone Number', 'aqualuxe'),
            'description' => esc_html__('Phone number to display in header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_layout',
            'settings'    => 'aqualuxe_options[header_phone]',
            'type'        => 'text',
        ));
        
        // Header Contact Email
        $wp_customize->add_setting('aqualuxe_options[header_email]', array(
            'default'           => 'info@aqualuxe.com',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_header_email', array(
            'label'       => esc_html__('Header Email', 'aqualuxe'),
            'description' => esc_html__('Email address to display in header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_layout',
            'settings'    => 'aqualuxe_options[header_email]',
            'type'        => 'email',
        ));
        
        // Show Search in Header
        $wp_customize->add_setting('aqualuxe_options[header_search]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_header_search', array(
            'label'       => esc_html__('Show Search', 'aqualuxe'),
            'description' => esc_html__('Display search icon in header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_layout',
            'settings'    => 'aqualuxe_options[header_search]',
            'type'        => 'checkbox',
        ));
        
        // Show Cart in Header (if WooCommerce is active)
        if (class_exists('WooCommerce')) {
            $wp_customize->add_setting('aqualuxe_options[header_cart]', array(
                'default'           => true,
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
            ));
            
            $wp_customize->add_control('aqualuxe_header_cart', array(
                'label'       => esc_html__('Show Cart', 'aqualuxe'),
                'description' => esc_html__('Display cart icon in header', 'aqualuxe'),
                'section'     => 'aqualuxe_header_layout',
                'settings'    => 'aqualuxe_options[header_cart]',
                'type'        => 'checkbox',
            ));
        }
        
        // Show Account in Header (if WooCommerce is active)
        if (class_exists('WooCommerce')) {
            $wp_customize->add_setting('aqualuxe_options[header_account]', array(
                'default'           => true,
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
            ));
            
            $wp_customize->add_control('aqualuxe_header_account', array(
                'label'       => esc_html__('Show Account', 'aqualuxe'),
                'description' => esc_html__('Display account icon in header', 'aqualuxe'),
                'section'     => 'aqualuxe_header_layout',
                'settings'    => 'aqualuxe_options[header_account]',
                'type'        => 'checkbox',
            ));
        }
        
        // Show Wishlist in Header (if WooCommerce is active)
        if (class_exists('WooCommerce')) {
            $wp_customize->add_setting('aqualuxe_options[header_wishlist]', array(
                'default'           => true,
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
            ));
            
            $wp_customize->add_control('aqualuxe_header_wishlist', array(
                'label'       => esc_html__('Show Wishlist', 'aqualuxe'),
                'description' => esc_html__('Display wishlist icon in header', 'aqualuxe'),
                'section'     => 'aqualuxe_header_layout',
                'settings'    => 'aqualuxe_options[header_wishlist]',
                'type'        => 'checkbox',
            ));
        }
        
        // Show Dark Mode Toggle
        $wp_customize->add_setting('aqualuxe_options[dark_mode_toggle]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_dark_mode_toggle', array(
            'label'       => esc_html__('Show Dark Mode Toggle', 'aqualuxe'),
            'description' => esc_html__('Display dark mode toggle in header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_layout',
            'settings'    => 'aqualuxe_options[dark_mode_toggle]',
            'type'        => 'checkbox',
        ));
    }

    /**
     * Add footer settings to the customizer
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_footer_settings($wp_customize) {
        // Footer Layout
        $wp_customize->add_setting('aqualuxe_options[footer_layout]', array(
            'default'           => '4-columns',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_footer_layout', array(
            'label'       => esc_html__('Footer Layout', 'aqualuxe'),
            'description' => esc_html__('Choose the footer widget layout', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_layout',
            'settings'    => 'aqualuxe_options[footer_layout]',
            'type'        => 'select',
            'choices'     => array(
                '4-columns' => esc_html__('4 Columns', 'aqualuxe'),
                '3-columns' => esc_html__('3 Columns', 'aqualuxe'),
                '2-columns' => esc_html__('2 Columns', 'aqualuxe'),
                '1-column'  => esc_html__('1 Column', 'aqualuxe'),
                'none'      => esc_html__('No Footer Widgets', 'aqualuxe'),
            ),
        ));
        
        // Footer Background
        $wp_customize->add_setting('aqualuxe_options[footer_background]', array(
            'default'           => 'dark',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_footer_background', array(
            'label'       => esc_html__('Footer Background', 'aqualuxe'),
            'description' => esc_html__('Choose the footer background style', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_layout',
            'settings'    => 'aqualuxe_options[footer_background]',
            'type'        => 'select',
            'choices'     => array(
                'dark'    => esc_html__('Dark', 'aqualuxe'),
                'light'   => esc_html__('Light', 'aqualuxe'),
                'primary' => esc_html__('Primary Color', 'aqualuxe'),
                'custom'  => esc_html__('Custom Color', 'aqualuxe'),
            ),
        ));
        
        // Footer Custom Background Color (if custom is selected)
        $wp_customize->add_setting('aqualuxe_options[footer_custom_bg]', array(
            'default'           => '#0077b6',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_custom_bg', array(
            'label'       => esc_html__('Footer Custom Background Color', 'aqualuxe'),
            'description' => esc_html__('Select a custom background color for the footer', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_layout',
            'settings'    => 'aqualuxe_options[footer_custom_bg]',
            'active_callback' => function() {
                return aqualuxe_get_option('footer_background', 'dark') === 'custom';
            },
        )));
        
        // Footer Logo
        $wp_customize->add_setting('aqualuxe_options[footer_logo]', array(
            'default'           => '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_footer_logo', array(
            'label'       => esc_html__('Footer Logo', 'aqualuxe'),
            'description' => esc_html__('Upload a logo for the footer', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_layout',
            'settings'    => 'aqualuxe_options[footer_logo]',
        )));
        
        // Copyright Text
        $wp_customize->add_setting('aqualuxe_options[copyright_text]', array(
            'default'           => sprintf(esc_html__('© %s AquaLuxe. All Rights Reserved.', 'aqualuxe'), date('Y')),
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_copyright_text', array(
            'label'       => esc_html__('Copyright Text', 'aqualuxe'),
            'description' => esc_html__('Enter your copyright text. Use {year} for dynamic year.', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_copyright',
            'settings'    => 'aqualuxe_options[copyright_text]',
            'type'        => 'textarea',
        ));
        
        // Payment Icons
        $wp_customize->add_setting('aqualuxe_options[show_payment_icons]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_payment_icons', array(
            'label'       => esc_html__('Show Payment Icons', 'aqualuxe'),
            'description' => esc_html__('Display payment method icons in the footer', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_copyright',
            'settings'    => 'aqualuxe_options[show_payment_icons]',
            'type'        => 'checkbox',
        ));
    }

    /**
     * Add typography settings to the customizer
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_typography_settings($wp_customize) {
        // Body Font Family
        $wp_customize->add_setting('aqualuxe_options[body_font_family]', array(
            'default'           => 'Montserrat',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_body_font_family', array(
            'label'       => esc_html__('Body Font Family', 'aqualuxe'),
            'description' => esc_html__('Select the main font family for body text', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_general',
            'settings'    => 'aqualuxe_options[body_font_family]',
            'type'        => 'select',
            'choices'     => array(
                'Montserrat'      => 'Montserrat',
                'Open Sans'       => 'Open Sans',
                'Roboto'          => 'Roboto',
                'Lato'            => 'Lato',
                'Poppins'         => 'Poppins',
                'Source Sans Pro' => 'Source Sans Pro',
                'Raleway'         => 'Raleway',
                'PT Sans'         => 'PT Sans',
                'Nunito'          => 'Nunito',
                'Nunito Sans'     => 'Nunito Sans',
            ),
        ));
        
        // Body Font Size
        $wp_customize->add_setting('aqualuxe_options[body_font_size]', array(
            'default'           => '16',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_body_font_size', array(
            'label'       => esc_html__('Body Font Size (px)', 'aqualuxe'),
            'description' => esc_html__('Set the base font size in pixels', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_general',
            'settings'    => 'aqualuxe_options[body_font_size]',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        ));
        
        // Body Line Height
        $wp_customize->add_setting('aqualuxe_options[body_line_height]', array(
            'default'           => '1.6',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_float'),
        ));
        
        $wp_customize->add_control('aqualuxe_body_line_height', array(
            'label'       => esc_html__('Body Line Height', 'aqualuxe'),
            'description' => esc_html__('Set the line height for body text', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_general',
            'settings'    => 'aqualuxe_options[body_line_height]',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 2,
                'step' => 0.1,
            ),
        ));
        
        // Headings Font Family
        $wp_customize->add_setting('aqualuxe_options[headings_font_family]', array(
            'default'           => 'Playfair Display',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_headings_font_family', array(
            'label'       => esc_html__('Headings Font Family', 'aqualuxe'),
            'description' => esc_html__('Select the font family for headings', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_headings',
            'settings'    => 'aqualuxe_options[headings_font_family]',
            'type'        => 'select',
            'choices'     => array(
                'Playfair Display' => 'Playfair Display',
                'Montserrat'       => 'Montserrat',
                'Roboto'           => 'Roboto',
                'Lato'             => 'Lato',
                'Poppins'          => 'Poppins',
                'Merriweather'     => 'Merriweather',
                'Raleway'          => 'Raleway',
                'PT Serif'         => 'PT Serif',
                'Lora'             => 'Lora',
                'Cormorant Garamond' => 'Cormorant Garamond',
            ),
        ));
        
        // Headings Font Weight
        $wp_customize->add_setting('aqualuxe_options[headings_font_weight]', array(
            'default'           => '600',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_headings_font_weight', array(
            'label'       => esc_html__('Headings Font Weight', 'aqualuxe'),
            'description' => esc_html__('Select the font weight for headings', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_headings',
            'settings'    => 'aqualuxe_options[headings_font_weight]',
            'type'        => 'select',
            'choices'     => array(
                '300' => esc_html__('Light (300)', 'aqualuxe'),
                '400' => esc_html__('Regular (400)', 'aqualuxe'),
                '500' => esc_html__('Medium (500)', 'aqualuxe'),
                '600' => esc_html__('Semi-Bold (600)', 'aqualuxe'),
                '700' => esc_html__('Bold (700)', 'aqualuxe'),
            ),
        ));
        
        // Headings Text Transform
        $wp_customize->add_setting('aqualuxe_options[headings_text_transform]', array(
            'default'           => 'none',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_headings_text_transform', array(
            'label'       => esc_html__('Headings Text Transform', 'aqualuxe'),
            'description' => esc_html__('Select the text transform for headings', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_headings',
            'settings'    => 'aqualuxe_options[headings_text_transform]',
            'type'        => 'select',
            'choices'     => array(
                'none'       => esc_html__('None', 'aqualuxe'),
                'capitalize' => esc_html__('Capitalize', 'aqualuxe'),
                'uppercase'  => esc_html__('Uppercase', 'aqualuxe'),
                'lowercase'  => esc_html__('Lowercase', 'aqualuxe'),
            ),
        ));
    }

    /**
     * Add color settings to the customizer
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_color_settings($wp_customize) {
        // Primary Color
        $wp_customize->add_setting('aqualuxe_options[primary_color]', array(
            'default'           => '#0077b6',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label'       => esc_html__('Primary Color', 'aqualuxe'),
            'description' => esc_html__('Select the primary color for the theme', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_general',
            'settings'    => 'aqualuxe_options[primary_color]',
        )));
        
        // Secondary Color
        $wp_customize->add_setting('aqualuxe_options[secondary_color]', array(
            'default'           => '#00b4d8',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label'       => esc_html__('Secondary Color', 'aqualuxe'),
            'description' => esc_html__('Select the secondary color for the theme', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_general',
            'settings'    => 'aqualuxe_options[secondary_color]',
        )));
        
        // Text Color
        $wp_customize->add_setting('aqualuxe_options[text_color]', array(
            'default'           => '#333333',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', array(
            'label'       => esc_html__('Text Color', 'aqualuxe'),
            'description' => esc_html__('Select the main text color', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_general',
            'settings'    => 'aqualuxe_options[text_color]',
        )));
        
        // Heading Color
        $wp_customize->add_setting('aqualuxe_options[heading_color]', array(
            'default'           => '#222222',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_heading_color', array(
            'label'       => esc_html__('Heading Color', 'aqualuxe'),
            'description' => esc_html__('Select the color for headings', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_general',
            'settings'    => 'aqualuxe_options[heading_color]',
        )));
        
        // Link Color
        $wp_customize->add_setting('aqualuxe_options[link_color]', array(
            'default'           => '#0077b6',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_color', array(
            'label'       => esc_html__('Link Color', 'aqualuxe'),
            'description' => esc_html__('Select the color for links', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_general',
            'settings'    => 'aqualuxe_options[link_color]',
        )));
        
        // Link Hover Color
        $wp_customize->add_setting('aqualuxe_options[link_hover_color]', array(
            'default'           => '#00b4d8',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_hover_color', array(
            'label'       => esc_html__('Link Hover Color', 'aqualuxe'),
            'description' => esc_html__('Select the hover color for links', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_general',
            'settings'    => 'aqualuxe_options[link_hover_color]',
        )));
        
        // Button Text Color
        $wp_customize->add_setting('aqualuxe_options[button_text_color]', array(
            'default'           => '#ffffff',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_text_color', array(
            'label'       => esc_html__('Button Text Color', 'aqualuxe'),
            'description' => esc_html__('Select the text color for buttons', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_general',
            'settings'    => 'aqualuxe_options[button_text_color]',
        )));
        
        // Button Background Color
        $wp_customize->add_setting('aqualuxe_options[button_bg_color]', array(
            'default'           => '#0077b6',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_bg_color', array(
            'label'       => esc_html__('Button Background Color', 'aqualuxe'),
            'description' => esc_html__('Select the background color for buttons', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_general',
            'settings'    => 'aqualuxe_options[button_bg_color]',
        )));
        
        // Button Hover Background Color
        $wp_customize->add_setting('aqualuxe_options[button_hover_bg_color]', array(
            'default'           => '#00b4d8',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_hover_bg_color', array(
            'label'       => esc_html__('Button Hover Background Color', 'aqualuxe'),
            'description' => esc_html__('Select the hover background color for buttons', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_general',
            'settings'    => 'aqualuxe_options[button_hover_bg_color]',
        )));
        
        // Header Background Color
        $wp_customize->add_setting('aqualuxe_options[header_bg_color]', array(
            'default'           => '#ffffff',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_bg_color', array(
            'label'       => esc_html__('Header Background Color', 'aqualuxe'),
            'description' => esc_html__('Select the background color for the header', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_header',
            'settings'    => 'aqualuxe_options[header_bg_color]',
        )));
        
        // Header Text Color
        $wp_customize->add_setting('aqualuxe_options[header_text_color]', array(
            'default'           => '#333333',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_text_color', array(
            'label'       => esc_html__('Header Text Color', 'aqualuxe'),
            'description' => esc_html__('Select the text color for the header', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_header',
            'settings'    => 'aqualuxe_options[header_text_color]',
        )));
        
        // Navigation Link Color
        $wp_customize->add_setting('aqualuxe_options[nav_link_color]', array(
            'default'           => '#333333',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_nav_link_color', array(
            'label'       => esc_html__('Navigation Link Color', 'aqualuxe'),
            'description' => esc_html__('Select the color for navigation links', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_header',
            'settings'    => 'aqualuxe_options[nav_link_color]',
        )));
        
        // Navigation Link Hover Color
        $wp_customize->add_setting('aqualuxe_options[nav_link_hover_color]', array(
            'default'           => '#0077b6',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_nav_link_hover_color', array(
            'label'       => esc_html__('Navigation Link Hover Color', 'aqualuxe'),
            'description' => esc_html__('Select the hover color for navigation links', 'aqualuxe'),
            'section'     => 'aqualuxe_colors_header',
            'settings'    => 'aqualuxe_options[nav_link_hover_color]',
        )));
    }

    /**
     * Add blog settings to the customizer
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_blog_settings($wp_customize) {
        // Blog Layout
        $wp_customize->add_setting('aqualuxe_options[blog_layout]', array(
            'default'           => 'grid',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_blog_layout', array(
            'label'       => esc_html__('Blog Layout', 'aqualuxe'),
            'description' => esc_html__('Choose the layout for the blog archive', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_archive',
            'settings'    => 'aqualuxe_options[blog_layout]',
            'type'        => 'select',
            'choices'     => array(
                'grid'    => esc_html__('Grid', 'aqualuxe'),
                'list'    => esc_html__('List', 'aqualuxe'),
                'masonry' => esc_html__('Masonry', 'aqualuxe'),
                'classic' => esc_html__('Classic', 'aqualuxe'),
            ),
        ));
        
        // Posts Per Row
        $wp_customize->add_setting('aqualuxe_options[posts_per_row]', array(
            'default'           => '3',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_posts_per_row', array(
            'label'       => esc_html__('Posts Per Row', 'aqualuxe'),
            'description' => esc_html__('Number of posts per row in grid or masonry layout', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_archive',
            'settings'    => 'aqualuxe_options[posts_per_row]',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 4,
                'step' => 1,
            ),
            'active_callback' => function() {
                $blog_layout = aqualuxe_get_option('blog_layout', 'grid');
                return $blog_layout === 'grid' || $blog_layout === 'masonry';
            },
        ));
        
        // Show Featured Image
        $wp_customize->add_setting('aqualuxe_options[show_featured_image]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_featured_image', array(
            'label'       => esc_html__('Show Featured Image', 'aqualuxe'),
            'description' => esc_html__('Display featured image in blog posts', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_archive',
            'settings'    => 'aqualuxe_options[show_featured_image]',
            'type'        => 'checkbox',
        ));
        
        // Show Post Meta
        $wp_customize->add_setting('aqualuxe_options[show_post_meta]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_post_meta', array(
            'label'       => esc_html__('Show Post Meta', 'aqualuxe'),
            'description' => esc_html__('Display post meta information (date, author, etc.)', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_archive',
            'settings'    => 'aqualuxe_options[show_post_meta]',
            'type'        => 'checkbox',
        ));
        
        // Show Excerpt
        $wp_customize->add_setting('aqualuxe_options[show_excerpt]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_excerpt', array(
            'label'       => esc_html__('Show Excerpt', 'aqualuxe'),
            'description' => esc_html__('Display post excerpt in blog archive', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_archive',
            'settings'    => 'aqualuxe_options[show_excerpt]',
            'type'        => 'checkbox',
        ));
        
        // Excerpt Length
        $wp_customize->add_setting('aqualuxe_options[excerpt_length]', array(
            'default'           => '30',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_excerpt_length', array(
            'label'       => esc_html__('Excerpt Length', 'aqualuxe'),
            'description' => esc_html__('Number of words in the excerpt', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_archive',
            'settings'    => 'aqualuxe_options[excerpt_length]',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 100,
                'step' => 5,
            ),
            'active_callback' => function() {
                return aqualuxe_get_option('show_excerpt', true);
            },
        ));
        
        // Show Read More Button
        $wp_customize->add_setting('aqualuxe_options[show_read_more]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_read_more', array(
            'label'       => esc_html__('Show Read More Button', 'aqualuxe'),
            'description' => esc_html__('Display read more button in blog archive', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_archive',
            'settings'    => 'aqualuxe_options[show_read_more]',
            'type'        => 'checkbox',
        ));
        
        // Read More Text
        $wp_customize->add_setting('aqualuxe_options[read_more_text]', array(
            'default'           => esc_html__('Read More', 'aqualuxe'),
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_read_more_text', array(
            'label'       => esc_html__('Read More Text', 'aqualuxe'),
            'description' => esc_html__('Text for the read more button', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_archive',
            'settings'    => 'aqualuxe_options[read_more_text]',
            'type'        => 'text',
            'active_callback' => function() {
                return aqualuxe_get_option('show_read_more', true);
            },
        ));
        
        // Single Post Layout
        $wp_customize->add_setting('aqualuxe_options[single_post_layout]', array(
            'default'           => 'standard',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_single_post_layout', array(
            'label'       => esc_html__('Single Post Layout', 'aqualuxe'),
            'description' => esc_html__('Choose the layout for single posts', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_single',
            'settings'    => 'aqualuxe_options[single_post_layout]',
            'type'        => 'select',
            'choices'     => array(
                'standard'   => esc_html__('Standard', 'aqualuxe'),
                'wide'       => esc_html__('Wide', 'aqualuxe'),
                'full-width' => esc_html__('Full Width', 'aqualuxe'),
                'centered'   => esc_html__('Centered', 'aqualuxe'),
            ),
        ));
        
        // Show Featured Image in Single Post
        $wp_customize->add_setting('aqualuxe_options[single_show_featured_image]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_single_show_featured_image', array(
            'label'       => esc_html__('Show Featured Image', 'aqualuxe'),
            'description' => esc_html__('Display featured image in single post', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_single',
            'settings'    => 'aqualuxe_options[single_show_featured_image]',
            'type'        => 'checkbox',
        ));
        
        // Show Post Meta in Single Post
        $wp_customize->add_setting('aqualuxe_options[single_show_post_meta]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_single_show_post_meta', array(
            'label'       => esc_html__('Show Post Meta', 'aqualuxe'),
            'description' => esc_html__('Display post meta information in single post', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_single',
            'settings'    => 'aqualuxe_options[single_show_post_meta]',
            'type'        => 'checkbox',
        ));
        
        // Show Author Bio
        $wp_customize->add_setting('aqualuxe_options[show_author_bio]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_author_bio', array(
            'label'       => esc_html__('Show Author Bio', 'aqualuxe'),
            'description' => esc_html__('Display author biography in single post', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_single',
            'settings'    => 'aqualuxe_options[show_author_bio]',
            'type'        => 'checkbox',
        ));
        
        // Show Post Navigation
        $wp_customize->add_setting('aqualuxe_options[show_post_nav]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_post_nav', array(
            'label'       => esc_html__('Show Post Navigation', 'aqualuxe'),
            'description' => esc_html__('Display previous/next post navigation', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_single',
            'settings'    => 'aqualuxe_options[show_post_nav]',
            'type'        => 'checkbox',
        ));
        
        // Show Related Posts
        $wp_customize->add_setting('aqualuxe_options[show_related_posts]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_related_posts', array(
            'label'       => esc_html__('Show Related Posts', 'aqualuxe'),
            'description' => esc_html__('Display related posts in single post', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_single',
            'settings'    => 'aqualuxe_options[show_related_posts]',
            'type'        => 'checkbox',
        ));
        
        // Number of Related Posts
        $wp_customize->add_setting('aqualuxe_options[related_posts_count]', array(
            'default'           => '3',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_related_posts_count', array(
            'label'       => esc_html__('Number of Related Posts', 'aqualuxe'),
            'description' => esc_html__('Number of related posts to display', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_single',
            'settings'    => 'aqualuxe_options[related_posts_count]',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 6,
                'step' => 1,
            ),
            'active_callback' => function() {
                return aqualuxe_get_option('show_related_posts', true);
            },
        ));
    }

    /**
     * Add WooCommerce settings to the customizer
     * 
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_woocommerce_settings($wp_customize) {
        // Shop Layout
        $wp_customize->add_setting('aqualuxe_options[shop_layout]', array(
            'default'           => 'grid',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_shop_layout', array(
            'label'       => esc_html__('Shop Layout', 'aqualuxe'),
            'description' => esc_html__('Choose the layout for the shop page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_shop',
            'settings'    => 'aqualuxe_options[shop_layout]',
            'type'        => 'select',
            'choices'     => array(
                'grid'    => esc_html__('Grid', 'aqualuxe'),
                'list'    => esc_html__('List', 'aqualuxe'),
                'masonry' => esc_html__('Masonry', 'aqualuxe'),
            ),
        ));
        
        // Products Per Row
        $wp_customize->add_setting('aqualuxe_options[products_per_row]', array(
            'default'           => '3',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_products_per_row', array(
            'label'       => esc_html__('Products Per Row', 'aqualuxe'),
            'description' => esc_html__('Number of products per row in shop page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_shop',
            'settings'    => 'aqualuxe_options[products_per_row]',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 2,
                'max'  => 6,
                'step' => 1,
            ),
        ));
        
        // Products Per Page
        $wp_customize->add_setting('aqualuxe_options[products_per_page]', array(
            'default'           => '12',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_products_per_page', array(
            'label'       => esc_html__('Products Per Page', 'aqualuxe'),
            'description' => esc_html__('Number of products to display per page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_shop',
            'settings'    => 'aqualuxe_options[products_per_page]',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 4,
                'max'  => 48,
                'step' => 4,
            ),
        ));
        
        // Show Shop Sidebar
        $wp_customize->add_setting('aqualuxe_options[show_shop_sidebar]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_shop_sidebar', array(
            'label'       => esc_html__('Show Shop Sidebar', 'aqualuxe'),
            'description' => esc_html__('Display sidebar on shop page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_shop',
            'settings'    => 'aqualuxe_options[show_shop_sidebar]',
            'type'        => 'checkbox',
        ));
        
        // Shop Sidebar Position
        $wp_customize->add_setting('aqualuxe_options[shop_sidebar_position]', array(
            'default'           => 'right',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_shop_sidebar_position', array(
            'label'       => esc_html__('Shop Sidebar Position', 'aqualuxe'),
            'description' => esc_html__('Choose the sidebar position for shop page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_shop',
            'settings'    => 'aqualuxe_options[shop_sidebar_position]',
            'type'        => 'select',
            'choices'     => array(
                'right' => esc_html__('Right', 'aqualuxe'),
                'left'  => esc_html__('Left', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return aqualuxe_get_option('show_shop_sidebar', true);
            },
        ));
        
        // Show Quick View
        $wp_customize->add_setting('aqualuxe_options[show_quick_view]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_quick_view', array(
            'label'       => esc_html__('Show Quick View', 'aqualuxe'),
            'description' => esc_html__('Display quick view button on products', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_shop',
            'settings'    => 'aqualuxe_options[show_quick_view]',
            'type'        => 'checkbox',
        ));
        
        // Show Wishlist
        $wp_customize->add_setting('aqualuxe_options[show_wishlist]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_wishlist', array(
            'label'       => esc_html__('Show Wishlist', 'aqualuxe'),
            'description' => esc_html__('Display wishlist button on products', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_shop',
            'settings'    => 'aqualuxe_options[show_wishlist]',
            'type'        => 'checkbox',
        ));
        
        // Product Page Layout
        $wp_customize->add_setting('aqualuxe_options[product_layout]', array(
            'default'           => 'standard',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_product_layout', array(
            'label'       => esc_html__('Product Page Layout', 'aqualuxe'),
            'description' => esc_html__('Choose the layout for single product pages', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_product',
            'settings'    => 'aqualuxe_options[product_layout]',
            'type'        => 'select',
            'choices'     => array(
                'standard'   => esc_html__('Standard', 'aqualuxe'),
                'wide'       => esc_html__('Wide', 'aqualuxe'),
                'full-width' => esc_html__('Full Width', 'aqualuxe'),
                'sticky'     => esc_html__('Sticky Info', 'aqualuxe'),
                'gallery'    => esc_html__('Gallery', 'aqualuxe'),
            ),
        ));
        
        // Show Product Sidebar
        $wp_customize->add_setting('aqualuxe_options[show_product_sidebar]', array(
            'default'           => false,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_product_sidebar', array(
            'label'       => esc_html__('Show Product Sidebar', 'aqualuxe'),
            'description' => esc_html__('Display sidebar on product page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_product',
            'settings'    => 'aqualuxe_options[show_product_sidebar]',
            'type'        => 'checkbox',
        ));
        
        // Show Related Products
        $wp_customize->add_setting('aqualuxe_options[show_related_products]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_related_products', array(
            'label'       => esc_html__('Show Related Products', 'aqualuxe'),
            'description' => esc_html__('Display related products on product page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_product',
            'settings'    => 'aqualuxe_options[show_related_products]',
            'type'        => 'checkbox',
        ));
        
        // Number of Related Products
        $wp_customize->add_setting('aqualuxe_options[related_products_count]', array(
            'default'           => '4',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('aqualuxe_related_products_count', array(
            'label'       => esc_html__('Number of Related Products', 'aqualuxe'),
            'description' => esc_html__('Number of related products to display', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_product',
            'settings'    => 'aqualuxe_options[related_products_count]',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 8,
                'step' => 1,
            ),
            'active_callback' => function() {
                return aqualuxe_get_option('show_related_products', true);
            },
        ));
        
        // Show Upsells
        $wp_customize->add_setting('aqualuxe_options[show_upsells]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_upsells', array(
            'label'       => esc_html__('Show Upsells', 'aqualuxe'),
            'description' => esc_html__('Display upsell products on product page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_product',
            'settings'    => 'aqualuxe_options[show_upsells]',
            'type'        => 'checkbox',
        ));
        
        // Cart Layout
        $wp_customize->add_setting('aqualuxe_options[cart_layout]', array(
            'default'           => 'standard',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_cart_layout', array(
            'label'       => esc_html__('Cart Layout', 'aqualuxe'),
            'description' => esc_html__('Choose the layout for the cart page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_cart',
            'settings'    => 'aqualuxe_options[cart_layout]',
            'type'        => 'select',
            'choices'     => array(
                'standard'   => esc_html__('Standard', 'aqualuxe'),
                'modern'     => esc_html__('Modern', 'aqualuxe'),
                'minimal'    => esc_html__('Minimal', 'aqualuxe'),
            ),
        ));
        
        // Show Cross-Sells
        $wp_customize->add_setting('aqualuxe_options[show_cross_sells]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_cross_sells', array(
            'label'       => esc_html__('Show Cross-Sells', 'aqualuxe'),
            'description' => esc_html__('Display cross-sell products on cart page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_cart',
            'settings'    => 'aqualuxe_options[show_cross_sells]',
            'type'        => 'checkbox',
        ));
        
        // Checkout Layout
        $wp_customize->add_setting('aqualuxe_options[checkout_layout]', array(
            'default'           => 'standard',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_select'),
        ));
        
        $wp_customize->add_control('aqualuxe_checkout_layout', array(
            'label'       => esc_html__('Checkout Layout', 'aqualuxe'),
            'description' => esc_html__('Choose the layout for the checkout page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_cart',
            'settings'    => 'aqualuxe_options[checkout_layout]',
            'type'        => 'select',
            'choices'     => array(
                'standard'   => esc_html__('Standard', 'aqualuxe'),
                'modern'     => esc_html__('Modern', 'aqualuxe'),
                'minimal'    => esc_html__('Minimal', 'aqualuxe'),
                'two-column' => esc_html__('Two Column', 'aqualuxe'),
            ),
        ));
        
        // Show Order Notes
        $wp_customize->add_setting('aqualuxe_options[show_order_notes]', array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
        ));
        
        $wp_customize->add_control('aqualuxe_show_order_notes', array(
            'label'       => esc_html__('Show Order Notes', 'aqualuxe'),
            'description' => esc_html__('Display order notes field on checkout page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_cart',
            'settings'    => 'aqualuxe_options[show_order_notes]',
            'type'        => 'checkbox',
        ));
    }

    /**
     * Add customizer preview script
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_URI . 'assets/js/customizer-preview.js',
            array('customize-preview', 'jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add customizer controls script
     */
    public function customize_controls_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            AQUALUXE_URI . 'assets/js/customizer-controls.js',
            array('customize-controls', 'jquery'),
            AQUALUXE_VERSION,
            true
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
     * @param string $input   The input from the setting.
     * @param object $setting The selected setting.
     * @return string
     */
    public function sanitize_select($input, $setting) {
        // Get the list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control($setting->id)->choices;
        
        // If the input is a valid key, return it; otherwise, return the default.
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }

    /**
     * Sanitize float
     * 
     * @param float $number The number to sanitize.
     * @return float
     */
    public function sanitize_float($number) {
        return filter_var($number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
}

// Initialize the class
new AquaLuxe_Customizer();