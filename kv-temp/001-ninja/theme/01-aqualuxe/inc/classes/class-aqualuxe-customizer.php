<?php
/**
 * AquaLuxe Customizer Class
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Customizer class
 */
class AquaLuxe_Customizer {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('customize_register', array($this, 'register_customizer_settings'));
        add_action('customize_preview_init', array($this, 'customizer_preview_js'));
        add_action('wp_head', array($this, 'output_customizer_css'));
        add_action('customize_controls_enqueue_scripts', array($this, 'customizer_controls_js'));
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function register_customizer_settings($wp_customize) {
        // Add Theme Options Panel
        $wp_customize->add_panel(
            'aqualuxe_theme_options',
            array(
                'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
                'description' => __('Theme Options for AquaLuxe', 'aqualuxe'),
                'priority'    => 130,
            )
        );

        // Header Section
        $this->add_header_section($wp_customize);

        // Footer Section
        $this->add_footer_section($wp_customize);

        // Social Media Section
        $this->add_social_section($wp_customize);

        // Colors Section
        $this->add_colors_section($wp_customize);

        // Typography Section
        $this->add_typography_section($wp_customize);

        // Layout Section
        $this->add_layout_section($wp_customize);

        // WooCommerce Section
        if (class_exists('WooCommerce')) {
            $this->add_woocommerce_section($wp_customize);
        }

        // Performance Section
        $this->add_performance_section($wp_customize);

        // Advanced Section
        $this->add_advanced_section($wp_customize);
    }

    /**
     * Add header section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_header_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_header_options',
            array(
                'title'       => __('Header Options', 'aqualuxe'),
                'description' => __('Customize the header section', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 10,
            )
        );

        // Sticky Header
        $wp_customize->add_setting(
            'aqualuxe_sticky_header',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_sticky_header',
            array(
                'label'       => __('Enable Sticky Header', 'aqualuxe'),
                'section'     => 'aqualuxe_header_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable sticky header on scroll', 'aqualuxe'),
            )
        );

        // Top Bar
        $wp_customize->add_setting(
            'aqualuxe_enable_topbar',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_topbar',
            array(
                'label'       => __('Enable Top Bar', 'aqualuxe'),
                'section'     => 'aqualuxe_header_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable the top bar above the main header', 'aqualuxe'),
            )
        );

        // Phone Number
        $wp_customize->add_setting(
            'aqualuxe_phone_number',
            array(
                'default'           => '+1 (234) 567-8900',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_phone_number',
            array(
                'label'       => __('Phone Number', 'aqualuxe'),
                'section'     => 'aqualuxe_header_options',
                'type'        => 'text',
                'description' => __('Enter your phone number to display in the header', 'aqualuxe'),
            )
        );

        // Email Address
        $wp_customize->add_setting(
            'aqualuxe_email_address',
            array(
                'default'           => 'info@aqualuxe.com',
                'sanitize_callback' => 'sanitize_email',
                'transport'         => 'postMessage',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_email_address',
            array(
                'label'       => __('Email Address', 'aqualuxe'),
                'section'     => 'aqualuxe_header_options',
                'type'        => 'email',
                'description' => __('Enter your email address to display in the header', 'aqualuxe'),
            )
        );

        // Header Layout
        $wp_customize->add_setting(
            'aqualuxe_header_layout',
            array(
                'default'           => 'default',
                'sanitize_callback' => array($this, 'sanitize_select'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_header_layout',
            array(
                'label'       => __('Header Layout', 'aqualuxe'),
                'section'     => 'aqualuxe_header_options',
                'type'        => 'select',
                'choices'     => array(
                    'default' => __('Default', 'aqualuxe'),
                    'centered' => __('Centered', 'aqualuxe'),
                    'minimal' => __('Minimal', 'aqualuxe'),
                    'split' => __('Split', 'aqualuxe'),
                ),
                'description' => __('Select the header layout style', 'aqualuxe'),
            )
        );

        // Search in Header
        $wp_customize->add_setting(
            'aqualuxe_header_search',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_header_search',
            array(
                'label'       => __('Show Search in Header', 'aqualuxe'),
                'section'     => 'aqualuxe_header_options',
                'type'        => 'checkbox',
                'description' => __('Check to display search icon in header', 'aqualuxe'),
            )
        );

        // Cart in Header
        $wp_customize->add_setting(
            'aqualuxe_header_cart',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_header_cart',
            array(
                'label'       => __('Show Cart in Header', 'aqualuxe'),
                'section'     => 'aqualuxe_header_options',
                'type'        => 'checkbox',
                'description' => __('Check to display cart icon in header', 'aqualuxe'),
            )
        );

        // Account in Header
        $wp_customize->add_setting(
            'aqualuxe_header_account',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_header_account',
            array(
                'label'       => __('Show Account in Header', 'aqualuxe'),
                'section'     => 'aqualuxe_header_options',
                'type'        => 'checkbox',
                'description' => __('Check to display account icon in header', 'aqualuxe'),
            )
        );
    }

    /**
     * Add footer section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_footer_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_footer_options',
            array(
                'title'       => __('Footer Options', 'aqualuxe'),
                'description' => __('Customize the footer section', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 20,
            )
        );

        // Footer Logo
        $wp_customize->add_setting(
            'aqualuxe_footer_logo',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_footer_logo',
                array(
                    'label'       => __('Footer Logo', 'aqualuxe'),
                    'section'     => 'aqualuxe_footer_options',
                    'description' => __('Upload a logo for the footer. If not set, the main logo will be used.', 'aqualuxe'),
                )
            )
        );

        // Copyright Text
        $wp_customize->add_setting(
            'aqualuxe_copyright_text',
            array(
                'default'           => sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')),
                'sanitize_callback' => 'wp_kses_post',
                'transport'         => 'postMessage',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_copyright_text',
            array(
                'label'       => __('Copyright Text', 'aqualuxe'),
                'section'     => 'aqualuxe_footer_options',
                'type'        => 'textarea',
                'description' => __('Enter your copyright text for the footer. Use {year} to dynamically display the current year.', 'aqualuxe'),
            )
        );

        // Footer Columns
        $wp_customize->add_setting(
            'aqualuxe_footer_columns',
            array(
                'default'           => '4',
                'sanitize_callback' => array($this, 'sanitize_select'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_footer_columns',
            array(
                'label'       => __('Footer Widget Columns', 'aqualuxe'),
                'section'     => 'aqualuxe_footer_options',
                'type'        => 'select',
                'choices'     => array(
                    '1' => __('1 Column', 'aqualuxe'),
                    '2' => __('2 Columns', 'aqualuxe'),
                    '3' => __('3 Columns', 'aqualuxe'),
                    '4' => __('4 Columns', 'aqualuxe'),
                ),
                'description' => __('Select the number of widget columns to display in the footer', 'aqualuxe'),
            )
        );

        // Footer Background
        $wp_customize->add_setting(
            'aqualuxe_footer_background',
            array(
                'default'           => '#0A1128',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_footer_background',
                array(
                    'label'       => __('Footer Background Color', 'aqualuxe'),
                    'section'     => 'aqualuxe_footer_options',
                    'description' => __('Choose the background color for the footer', 'aqualuxe'),
                )
            )
        );

        // Footer Text Color
        $wp_customize->add_setting(
            'aqualuxe_footer_text_color',
            array(
                'default'           => '#FFFFFF',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_footer_text_color',
                array(
                    'label'       => __('Footer Text Color', 'aqualuxe'),
                    'section'     => 'aqualuxe_footer_options',
                    'description' => __('Choose the text color for the footer', 'aqualuxe'),
                )
            )
        );

        // Payment Icons
        $wp_customize->add_setting(
            'aqualuxe_show_payment_icons',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_payment_icons',
            array(
                'label'       => __('Show Payment Icons', 'aqualuxe'),
                'section'     => 'aqualuxe_footer_options',
                'type'        => 'checkbox',
                'description' => __('Check to display payment method icons in the footer', 'aqualuxe'),
            )
        );
    }

    /**
     * Add social media section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_social_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_social_options',
            array(
                'title'       => __('Social Media', 'aqualuxe'),
                'description' => __('Add your social media links', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 30,
            )
        );

        // Facebook
        $wp_customize->add_setting(
            'aqualuxe_facebook_url',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_facebook_url',
            array(
                'label'       => __('Facebook URL', 'aqualuxe'),
                'section'     => 'aqualuxe_social_options',
                'type'        => 'url',
                'description' => __('Enter your Facebook profile/page URL', 'aqualuxe'),
            )
        );

        // Twitter
        $wp_customize->add_setting(
            'aqualuxe_twitter_url',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_twitter_url',
            array(
                'label'       => __('Twitter URL', 'aqualuxe'),
                'section'     => 'aqualuxe_social_options',
                'type'        => 'url',
                'description' => __('Enter your Twitter profile URL', 'aqualuxe'),
            )
        );

        // Instagram
        $wp_customize->add_setting(
            'aqualuxe_instagram_url',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_instagram_url',
            array(
                'label'       => __('Instagram URL', 'aqualuxe'),
                'section'     => 'aqualuxe_social_options',
                'type'        => 'url',
                'description' => __('Enter your Instagram profile URL', 'aqualuxe'),
            )
        );

        // YouTube
        $wp_customize->add_setting(
            'aqualuxe_youtube_url',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_youtube_url',
            array(
                'label'       => __('YouTube URL', 'aqualuxe'),
                'section'     => 'aqualuxe_social_options',
                'type'        => 'url',
                'description' => __('Enter your YouTube channel URL', 'aqualuxe'),
            )
        );

        // LinkedIn
        $wp_customize->add_setting(
            'aqualuxe_linkedin_url',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_linkedin_url',
            array(
                'label'       => __('LinkedIn URL', 'aqualuxe'),
                'section'     => 'aqualuxe_social_options',
                'type'        => 'url',
                'description' => __('Enter your LinkedIn profile/company URL', 'aqualuxe'),
            )
        );

        // Pinterest
        $wp_customize->add_setting(
            'aqualuxe_pinterest_url',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_pinterest_url',
            array(
                'label'       => __('Pinterest URL', 'aqualuxe'),
                'section'     => 'aqualuxe_social_options',
                'type'        => 'url',
                'description' => __('Enter your Pinterest profile URL', 'aqualuxe'),
            )
        );

        // Social Icons in Header
        $wp_customize->add_setting(
            'aqualuxe_social_icons_header',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_social_icons_header',
            array(
                'label'       => __('Show Social Icons in Header', 'aqualuxe'),
                'section'     => 'aqualuxe_social_options',
                'type'        => 'checkbox',
                'description' => __('Check to display social media icons in the header', 'aqualuxe'),
            )
        );

        // Social Icons in Footer
        $wp_customize->add_setting(
            'aqualuxe_social_icons_footer',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_social_icons_footer',
            array(
                'label'       => __('Show Social Icons in Footer', 'aqualuxe'),
                'section'     => 'aqualuxe_social_options',
                'type'        => 'checkbox',
                'description' => __('Check to display social media icons in the footer', 'aqualuxe'),
            )
        );
    }

    /**
     * Add colors section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_colors_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_colors_options',
            array(
                'title'       => __('Theme Colors', 'aqualuxe'),
                'description' => __('Customize the theme colors', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 40,
            )
        );

        // Primary Color
        $wp_customize->add_setting(
            'aqualuxe_primary_color',
            array(
                'default'           => '#0077B6',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_primary_color',
                array(
                    'label'       => __('Primary Color', 'aqualuxe'),
                    'section'     => 'aqualuxe_colors_options',
                    'description' => __('Choose the primary color for the theme', 'aqualuxe'),
                )
            )
        );

        // Secondary Color
        $wp_customize->add_setting(
            'aqualuxe_secondary_color',
            array(
                'default'           => '#00B4D8',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_secondary_color',
                array(
                    'label'       => __('Secondary Color', 'aqualuxe'),
                    'section'     => 'aqualuxe_colors_options',
                    'description' => __('Choose the secondary color for the theme', 'aqualuxe'),
                )
            )
        );

        // Accent Color
        $wp_customize->add_setting(
            'aqualuxe_accent_color',
            array(
                'default'           => '#FFD700',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_accent_color',
                array(
                    'label'       => __('Accent Color', 'aqualuxe'),
                    'section'     => 'aqualuxe_colors_options',
                    'description' => __('Choose the accent color for the theme', 'aqualuxe'),
                )
            )
        );

        // Dark Color
        $wp_customize->add_setting(
            'aqualuxe_dark_color',
            array(
                'default'           => '#0A1128',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_dark_color',
                array(
                    'label'       => __('Dark Color', 'aqualuxe'),
                    'section'     => 'aqualuxe_colors_options',
                    'description' => __('Choose the dark color for the theme', 'aqualuxe'),
                )
            )
        );

        // Light Color
        $wp_customize->add_setting(
            'aqualuxe_light_color',
            array(
                'default'           => '#F8F9FA',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_light_color',
                array(
                    'label'       => __('Light Color', 'aqualuxe'),
                    'section'     => 'aqualuxe_colors_options',
                    'description' => __('Choose the light color for the theme', 'aqualuxe'),
                )
            )
        );

        // Dark Mode Default
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_default',
            array(
                'default'           => false,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_dark_mode_default',
            array(
                'label'       => __('Enable Dark Mode by Default', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable dark mode by default. Users can still toggle between light and dark mode.', 'aqualuxe'),
            )
        );

        // Button Style
        $wp_customize->add_setting(
            'aqualuxe_button_style',
            array(
                'default'           => 'rounded',
                'sanitize_callback' => array($this, 'sanitize_select'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_button_style',
            array(
                'label'       => __('Button Style', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_options',
                'type'        => 'select',
                'choices'     => array(
                    'rounded' => __('Rounded', 'aqualuxe'),
                    'pill' => __('Pill', 'aqualuxe'),
                    'square' => __('Square', 'aqualuxe'),
                ),
                'description' => __('Select the button style for the theme', 'aqualuxe'),
            )
        );
    }

    /**
     * Add typography section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_typography_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_typography_options',
            array(
                'title'       => __('Typography', 'aqualuxe'),
                'description' => __('Customize the typography settings', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 50,
            )
        );

        // Heading Font
        $wp_customize->add_setting(
            'aqualuxe_heading_font',
            array(
                'default'           => 'Playfair Display',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_heading_font',
            array(
                'label'       => __('Heading Font', 'aqualuxe'),
                'section'     => 'aqualuxe_typography_options',
                'type'        => 'select',
                'choices'     => array(
                    'Playfair Display' => 'Playfair Display',
                    'Montserrat'       => 'Montserrat',
                    'Roboto'           => 'Roboto',
                    'Open Sans'        => 'Open Sans',
                    'Lato'             => 'Lato',
                    'Poppins'          => 'Poppins',
                    'Merriweather'     => 'Merriweather',
                    'Raleway'          => 'Raleway',
                ),
                'description' => __('Select the font for headings', 'aqualuxe'),
            )
        );

        // Body Font
        $wp_customize->add_setting(
            'aqualuxe_body_font',
            array(
                'default'           => 'Montserrat',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_body_font',
            array(
                'label'       => __('Body Font', 'aqualuxe'),
                'section'     => 'aqualuxe_typography_options',
                'type'        => 'select',
                'choices'     => array(
                    'Montserrat'       => 'Montserrat',
                    'Roboto'           => 'Roboto',
                    'Open Sans'        => 'Open Sans',
                    'Lato'             => 'Lato',
                    'Poppins'          => 'Poppins',
                    'Raleway'          => 'Raleway',
                    'Source Sans Pro'  => 'Source Sans Pro',
                    'Nunito'           => 'Nunito',
                ),
                'description' => __('Select the font for body text', 'aqualuxe'),
            )
        );

        // Base Font Size
        $wp_customize->add_setting(
            'aqualuxe_base_font_size',
            array(
                'default'           => '16',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_base_font_size',
            array(
                'label'       => __('Base Font Size (px)', 'aqualuxe'),
                'section'     => 'aqualuxe_typography_options',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 12,
                    'max'  => 24,
                    'step' => 1,
                ),
                'description' => __('Set the base font size in pixels', 'aqualuxe'),
            )
        );

        // Heading Scale
        $wp_customize->add_setting(
            'aqualuxe_heading_scale',
            array(
                'default'           => 'medium',
                'sanitize_callback' => array($this, 'sanitize_select'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_heading_scale',
            array(
                'label'       => __('Heading Scale', 'aqualuxe'),
                'section'     => 'aqualuxe_typography_options',
                'type'        => 'select',
                'choices'     => array(
                    'small'  => __('Small', 'aqualuxe'),
                    'medium' => __('Medium', 'aqualuxe'),
                    'large'  => __('Large', 'aqualuxe'),
                ),
                'description' => __('Select the scale for heading sizes', 'aqualuxe'),
            )
        );

        // Line Height
        $wp_customize->add_setting(
            'aqualuxe_line_height',
            array(
                'default'           => '1.5',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_line_height',
            array(
                'label'       => __('Line Height', 'aqualuxe'),
                'section'     => 'aqualuxe_typography_options',
                'type'        => 'select',
                'choices'     => array(
                    '1.3' => __('Tight (1.3)', 'aqualuxe'),
                    '1.5' => __('Normal (1.5)', 'aqualuxe'),
                    '1.7' => __('Relaxed (1.7)', 'aqualuxe'),
                    '2'   => __('Loose (2)', 'aqualuxe'),
                ),
                'description' => __('Select the line height for body text', 'aqualuxe'),
            )
        );
    }

    /**
     * Add layout section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_layout_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_layout_options',
            array(
                'title'       => __('Layout Options', 'aqualuxe'),
                'description' => __('Customize the layout settings', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 60,
            )
        );

        // Container Width
        $wp_customize->add_setting(
            'aqualuxe_container_width',
            array(
                'default'           => '1280',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_container_width',
            array(
                'label'       => __('Container Width (px)', 'aqualuxe'),
                'section'     => 'aqualuxe_layout_options',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 960,
                    'max'  => 1920,
                    'step' => 10,
                ),
                'description' => __('Set the maximum width of the content container in pixels', 'aqualuxe'),
            )
        );

        // Blog Layout
        $wp_customize->add_setting(
            'aqualuxe_blog_layout',
            array(
                'default'           => 'grid',
                'sanitize_callback' => array($this, 'sanitize_select'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_blog_layout',
            array(
                'label'       => __('Blog Layout', 'aqualuxe'),
                'section'     => 'aqualuxe_layout_options',
                'type'        => 'select',
                'choices'     => array(
                    'grid'    => __('Grid', 'aqualuxe'),
                    'list'    => __('List', 'aqualuxe'),
                    'masonry' => __('Masonry', 'aqualuxe'),
                ),
                'description' => __('Select the layout for the blog archive pages', 'aqualuxe'),
            )
        );

        // Shop Layout
        $wp_customize->add_setting(
            'aqualuxe_shop_layout',
            array(
                'default'           => 'grid',
                'sanitize_callback' => array($this, 'sanitize_select'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_layout',
            array(
                'label'       => __('Shop Layout', 'aqualuxe'),
                'section'     => 'aqualuxe_layout_options',
                'type'        => 'select',
                'choices'     => array(
                    'grid'    => __('Grid', 'aqualuxe'),
                    'list'    => __('List', 'aqualuxe'),
                    'masonry' => __('Masonry', 'aqualuxe'),
                ),
                'description' => __('Select the layout for the shop archive pages', 'aqualuxe'),
            )
        );

        // Sidebar Position
        $wp_customize->add_setting(
            'aqualuxe_sidebar_position',
            array(
                'default'           => 'right',
                'sanitize_callback' => array($this, 'sanitize_select'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_sidebar_position',
            array(
                'label'       => __('Sidebar Position', 'aqualuxe'),
                'section'     => 'aqualuxe_layout_options',
                'type'        => 'select',
                'choices'     => array(
                    'right' => __('Right', 'aqualuxe'),
                    'left'  => __('Left', 'aqualuxe'),
                    'none'  => __('No Sidebar', 'aqualuxe'),
                ),
                'description' => __('Select the position of the sidebar', 'aqualuxe'),
            )
        );

        // Page Header Style
        $wp_customize->add_setting(
            'aqualuxe_page_header_style',
            array(
                'default'           => 'default',
                'sanitize_callback' => array($this, 'sanitize_select'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_page_header_style',
            array(
                'label'       => __('Page Header Style', 'aqualuxe'),
                'section'     => 'aqualuxe_layout_options',
                'type'        => 'select',
                'choices'     => array(
                    'default'   => __('Default', 'aqualuxe'),
                    'centered'  => __('Centered', 'aqualuxe'),
                    'minimal'   => __('Minimal', 'aqualuxe'),
                    'hero'      => __('Hero Image', 'aqualuxe'),
                    'none'      => __('None', 'aqualuxe'),
                ),
                'description' => __('Select the style for page headers', 'aqualuxe'),
            )
        );

        // Page Header Background
        $wp_customize->add_setting(
            'aqualuxe_page_header_background',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_page_header_background',
                array(
                    'label'       => __('Page Header Background Image', 'aqualuxe'),
                    'section'     => 'aqualuxe_layout_options',
                    'description' => __('Upload a background image for the page header (used with Hero Image style)', 'aqualuxe'),
                )
            )
        );
    }

    /**
     * Add WooCommerce section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_woocommerce_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_woocommerce_options',
            array(
                'title'       => __('WooCommerce Options', 'aqualuxe'),
                'description' => __('Customize WooCommerce settings', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 70,
            )
        );

        // Products per row
        $wp_customize->add_setting(
            'aqualuxe_products_per_row',
            array(
                'default'           => '3',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_row',
            array(
                'label'       => __('Products per Row', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 6,
                    'step' => 1,
                ),
                'description' => __('Set the number of products per row in the shop', 'aqualuxe'),
            )
        );

        // Products per page
        $wp_customize->add_setting(
            'aqualuxe_products_per_page',
            array(
                'default'           => '12',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_products_per_page',
            array(
                'label'       => __('Products per Page', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 100,
                    'step' => 1,
                ),
                'description' => __('Set the number of products per page in the shop', 'aqualuxe'),
            )
        );

        // Related Products Count
        $wp_customize->add_setting(
            'aqualuxe_related_products_count',
            array(
                'default'           => '4',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products_count',
            array(
                'label'       => __('Related Products Count', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 0,
                    'max'  => 12,
                    'step' => 1,
                ),
                'description' => __('Set the number of related products (0 to disable)', 'aqualuxe'),
            )
        );

        // Quick View
        $wp_customize->add_setting(
            'aqualuxe_enable_quick_view',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_quick_view',
            array(
                'label'       => __('Enable Quick View', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable quick view feature for products', 'aqualuxe'),
            )
        );

        // Wishlist
        $wp_customize->add_setting(
            'aqualuxe_enable_wishlist',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_wishlist',
            array(
                'label'       => __('Enable Wishlist', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable wishlist feature for products', 'aqualuxe'),
            )
        );

        // Product Image Zoom
        $wp_customize->add_setting(
            'aqualuxe_product_image_zoom',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_image_zoom',
            array(
                'label'       => __('Enable Product Image Zoom', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable zoom feature for product images', 'aqualuxe'),
            )
        );

        // Product Image Lightbox
        $wp_customize->add_setting(
            'aqualuxe_product_image_lightbox',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_image_lightbox',
            array(
                'label'       => __('Enable Product Image Lightbox', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable lightbox feature for product images', 'aqualuxe'),
            )
        );

        // Product Image Slider
        $wp_customize->add_setting(
            'aqualuxe_product_image_slider',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_image_slider',
            array(
                'label'       => __('Enable Product Image Slider', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable slider feature for product images', 'aqualuxe'),
            )
        );

        // Shop Sidebar
        $wp_customize->add_setting(
            'aqualuxe_shop_sidebar',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_sidebar',
            array(
                'label'       => __('Show Shop Sidebar', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
                'description' => __('Check to display sidebar on shop pages', 'aqualuxe'),
            )
        );

        // Product Sidebar
        $wp_customize->add_setting(
            'aqualuxe_product_sidebar',
            array(
                'default'           => false,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_sidebar',
            array(
                'label'       => __('Show Product Sidebar', 'aqualuxe'),
                'section'     => 'aqualuxe_woocommerce_options',
                'type'        => 'checkbox',
                'description' => __('Check to display sidebar on product pages', 'aqualuxe'),
            )
        );
    }

    /**
     * Add performance section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_performance_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_performance_options',
            array(
                'title'       => __('Performance Options', 'aqualuxe'),
                'description' => __('Optimize theme performance', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 80,
            )
        );

        // Lazy Loading
        $wp_customize->add_setting(
            'aqualuxe_enable_lazy_loading',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_enable_lazy_loading',
            array(
                'label'       => __('Enable Lazy Loading', 'aqualuxe'),
                'section'     => 'aqualuxe_performance_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable lazy loading for images', 'aqualuxe'),
            )
        );

        // Minify CSS
        $wp_customize->add_setting(
            'aqualuxe_minify_css',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_minify_css',
            array(
                'label'       => __('Minify CSS', 'aqualuxe'),
                'section'     => 'aqualuxe_performance_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable CSS minification', 'aqualuxe'),
            )
        );

        // Minify JS
        $wp_customize->add_setting(
            'aqualuxe_minify_js',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_minify_js',
            array(
                'label'       => __('Minify JavaScript', 'aqualuxe'),
                'section'     => 'aqualuxe_performance_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable JavaScript minification', 'aqualuxe'),
            )
        );

        // Preload Key Resources
        $wp_customize->add_setting(
            'aqualuxe_preload_resources',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_preload_resources',
            array(
                'label'       => __('Preload Key Resources', 'aqualuxe'),
                'section'     => 'aqualuxe_performance_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable preloading of key resources', 'aqualuxe'),
            )
        );

        // Cache Busting
        $wp_customize->add_setting(
            'aqualuxe_cache_busting',
            array(
                'default'           => true,
                'sanitize_callback' => array($this, 'sanitize_checkbox'),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_cache_busting',
            array(
                'label'       => __('Enable Cache Busting', 'aqualuxe'),
                'section'     => 'aqualuxe_performance_options',
                'type'        => 'checkbox',
                'description' => __('Check to enable cache busting for assets', 'aqualuxe'),
            )
        );
    }

    /**
     * Add advanced section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function add_advanced_section($wp_customize) {
        $wp_customize->add_section(
            'aqualuxe_advanced_options',
            array(
                'title'       => __('Advanced Options', 'aqualuxe'),
                'description' => __('Advanced theme settings', 'aqualuxe'),
                'panel'       => 'aqualuxe_theme_options',
                'priority'    => 90,
            )
        );

        // Custom CSS
        $wp_customize->add_setting(
            'aqualuxe_custom_css',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_strip_all_tags',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_custom_css',
            array(
                'label'       => __('Custom CSS', 'aqualuxe'),
                'section'     => 'aqualuxe_advanced_options',
                'type'        => 'textarea',
                'description' => __('Add your custom CSS here', 'aqualuxe'),
            )
        );

        // Custom JavaScript
        $wp_customize->add_setting(
            'aqualuxe_custom_js',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_strip_all_tags',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_custom_js',
            array(
                'label'       => __('Custom JavaScript', 'aqualuxe'),
                'section'     => 'aqualuxe_advanced_options',
                'type'        => 'textarea',
                'description' => __('Add your custom JavaScript here (without script tags)', 'aqualuxe'),
            )
        );

        // Google Analytics
        $wp_customize->add_setting(
            'aqualuxe_google_analytics',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_strip_all_tags',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_google_analytics',
            array(
                'label'       => __('Google Analytics Code', 'aqualuxe'),
                'section'     => 'aqualuxe_advanced_options',
                'type'        => 'textarea',
                'description' => __('Add your Google Analytics tracking code here (with script tags)', 'aqualuxe'),
            )
        );

        // Header Scripts
        $wp_customize->add_setting(
            'aqualuxe_header_scripts',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_header_scripts',
            array(
                'label'       => __('Header Scripts', 'aqualuxe'),
                'section'     => 'aqualuxe_advanced_options',
                'type'        => 'textarea',
                'description' => __('Add scripts to the header (before closing head tag)', 'aqualuxe'),
            )
        );

        // Footer Scripts
        $wp_customize->add_setting(
            'aqualuxe_footer_scripts',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_footer_scripts',
            array(
                'label'       => __('Footer Scripts', 'aqualuxe'),
                'section'     => 'aqualuxe_advanced_options',
                'type'        => 'textarea',
                'description' => __('Add scripts to the footer (before closing body tag)', 'aqualuxe'),
            )
        );
    }

    /**
     * Enqueue customizer preview JS
     */
    public function customizer_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . 'js/customizer.js',
            array('customize-preview'),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue customizer controls JS
     */
    public function customizer_controls_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            AQUALUXE_ASSETS_URI . 'js/customizer-controls.js',
            array('customize-controls', 'jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Output customizer CSS
     */
    public function output_customizer_css() {
        $primary_color   = get_theme_mod('aqualuxe_primary_color', '#0077B6');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#00B4D8');
        $accent_color    = get_theme_mod('aqualuxe_accent_color', '#FFD700');
        $dark_color      = get_theme_mod('aqualuxe_dark_color', '#0A1128');
        $light_color     = get_theme_mod('aqualuxe_light_color', '#F8F9FA');
        $heading_font    = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
        $body_font       = get_theme_mod('aqualuxe_body_font', 'Montserrat');
        $base_font_size  = get_theme_mod('aqualuxe_base_font_size', '16');
        $line_height     = get_theme_mod('aqualuxe_line_height', '1.5');
        $container_width = get_theme_mod('aqualuxe_container_width', '1280');
        $button_style    = get_theme_mod('aqualuxe_button_style', 'rounded');
        $footer_bg       = get_theme_mod('aqualuxe_footer_background', '#0A1128');
        $footer_text     = get_theme_mod('aqualuxe_footer_text_color', '#FFFFFF');
        $custom_css      = get_theme_mod('aqualuxe_custom_css', '');

        // Button border radius based on style
        $button_radius = '0.375rem'; // Default rounded
        if ($button_style === 'pill') {
            $button_radius = '9999px';
        } elseif ($button_style === 'square') {
            $button_radius = '0';
        }

        // Heading scale
        $heading_scale = get_theme_mod('aqualuxe_heading_scale', 'medium');
        $h1_size = '2.25rem';
        $h2_size = '1.875rem';
        $h3_size = '1.5rem';
        $h4_size = '1.25rem';
        $h5_size = '1.125rem';
        $h6_size = '1rem';

        if ($heading_scale === 'small') {
            $h1_size = '2rem';
            $h2_size = '1.75rem';
            $h3_size = '1.375rem';
            $h4_size = '1.125rem';
            $h5_size = '1rem';
            $h6_size = '0.875rem';
        } elseif ($heading_scale === 'large') {
            $h1_size = '2.5rem';
            $h2_size = '2.125rem';
            $h3_size = '1.75rem';
            $h4_size = '1.5rem';
            $h5_size = '1.25rem';
            $h6_size = '1.125rem';
        }

        $css = "
            :root {
                --color-primary: {$primary_color};
                --color-primary-light: " . $this->adjust_brightness($primary_color, 30) . ";
                --color-primary-dark: " . $this->adjust_brightness($primary_color, -30) . ";
                --color-secondary: {$secondary_color};
                --color-secondary-light: " . $this->adjust_brightness($secondary_color, 30) . ";
                --color-secondary-dark: " . $this->adjust_brightness($secondary_color, -30) . ";
                --color-accent: {$accent_color};
                --color-accent-light: " . $this->adjust_brightness($accent_color, 30) . ";
                --color-accent-dark: " . $this->adjust_brightness($accent_color, -30) . ";
                --color-dark: {$dark_color};
                --color-dark-light: " . $this->adjust_brightness($dark_color, 30) . ";
                --color-dark-lighter: " . $this->adjust_brightness($dark_color, 60) . ";
                --color-light: {$light_color};
                --color-light-dark: " . $this->adjust_brightness($light_color, -10) . ";
                --color-light-darker: " . $this->adjust_brightness($light_color, -20) . ";
                --font-heading: '{$heading_font}', serif;
                --font-body: '{$body_font}', sans-serif;
                --container-width: {$container_width}px;
                --button-radius: {$button_radius};
            }

            body {
                font-family: var(--font-body);
                font-size: {$base_font_size}px;
                line-height: {$line_height};
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: var(--font-heading);
            }

            h1 { font-size: {$h1_size}; }
            h2 { font-size: {$h2_size}; }
            h3 { font-size: {$h3_size}; }
            h4 { font-size: {$h4_size}; }
            h5 { font-size: {$h5_size}; }
            h6 { font-size: {$h6_size}; }

            .btn {
                border-radius: var(--button-radius);
            }

            .site-footer {
                background-color: {$footer_bg};
                color: {$footer_text};
            }

            .site-footer a {
                color: " . $this->adjust_brightness($footer_text, -20) . ";
            }

            .site-footer a:hover {
                color: {$footer_text};
            }

            .site-footer .widget-title {
                color: {$footer_text};
            }
        ";

        // Add custom CSS
        if ($custom_css) {
            $css .= $custom_css;
        }

        if ($css) {
            echo '<style id="aqualuxe-customizer-css">' . wp_strip_all_tags($css) . '</style>';
        }
    }

    /**
     * Adjust color brightness
     *
     * @param string $hex   Hex color code.
     * @param int    $steps Steps to adjust brightness (-255 to 255).
     * @return string
     */
    private function adjust_brightness($hex, $steps) {
        // Remove # if present
        $hex = ltrim($hex, '#');

        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Adjust brightness
        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        // Convert back to hex
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Sanitize checkbox
     *
     * @param bool $checked Whether the checkbox is checked.
     * @return bool
     */
    public function sanitize_checkbox($checked) {
        return ((isset($checked) && true == $checked) ? true : false);
    }

    /**
     * Sanitize select
     *
     * @param string $input   The input from the setting.
     * @param object $setting The selected setting.
     * @return string
     */
    public function sanitize_select($input, $setting) {
        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control($setting->id)->choices;
        
        // If the input is a valid key, return it; otherwise, return the default.
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }
}

// Initialize the customizer
new AquaLuxe_Customizer();