<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * AquaLuxe Theme Customizer class
 */
class AquaLuxe_Customizer {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register' ) );
        add_action( 'customize_preview_init', array( $this, 'preview_js' ) );
        add_action( 'wp_head', array( $this, 'output_css' ) );
    }

    /**
     * Register customizer options
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function register( $wp_customize ) {
        // Add postMessage support for site title and description
        $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
        $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
        $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial(
                'blogname',
                array(
                    'selector'        => '.site-title a',
                    'render_callback' => array( $this, 'customize_partial_blogname' ),
                )
            );
            $wp_customize->selective_refresh->add_partial(
                'blogdescription',
                array(
                    'selector'        => '.site-description',
                    'render_callback' => array( $this, 'customize_partial_blogdescription' ),
                )
            );
        }

        // Register custom sections and settings
        $this->register_general_settings( $wp_customize );
        $this->register_header_settings( $wp_customize );
        $this->register_footer_settings( $wp_customize );
        $this->register_homepage_settings( $wp_customize );
        $this->register_blog_settings( $wp_customize );
        $this->register_shop_settings( $wp_customize );
        $this->register_product_settings( $wp_customize );
        $this->register_typography_settings( $wp_customize );
        $this->register_color_settings( $wp_customize );
        $this->register_social_settings( $wp_customize );
    }

    /**
     * Register general settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_general_settings( $wp_customize ) {
        // General Settings Section
        $wp_customize->add_section(
            'aqualuxe_general_settings',
            array(
                'title'    => __( 'General Settings', 'aqualuxe' ),
                'priority' => 30,
            )
        );

        // Theme Layout
        $wp_customize->add_setting(
            'aqualuxe_layout',
            array(
                'default'           => 'wide',
                'sanitize_callback' => array( $this, 'sanitize_select' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_layout',
            array(
                'label'    => __( 'Theme Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'select',
                'choices'  => array(
                    'wide'  => __( 'Wide', 'aqualuxe' ),
                    'boxed' => __( 'Boxed', 'aqualuxe' ),
                ),
            )
        );

        // Container Width
        $wp_customize->add_setting(
            'aqualuxe_container_width',
            array(
                'default'           => '1200',
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_container_width',
            array(
                'label'    => __( 'Container Width (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 960,
                    'max'  => 1600,
                    'step' => 10,
                ),
            )
        );

        // Back to Top Button
        $wp_customize->add_setting(
            'aqualuxe_back_to_top',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_back_to_top',
            array(
                'label'    => __( 'Show Back to Top Button', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'checkbox',
            )
        );

        // Preloader
        $wp_customize->add_setting(
            'aqualuxe_preloader',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_preloader',
            array(
                'label'    => __( 'Show Preloader', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'checkbox',
            )
        );

        // Breadcrumbs
        $wp_customize->add_setting(
            'aqualuxe_breadcrumbs',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_breadcrumbs',
            array(
                'label'    => __( 'Show Breadcrumbs', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'checkbox',
            )
        );

        // Contact Information
        $wp_customize->add_setting(
            'aqualuxe_phone',
            array(
                'default'           => '+1 (555) 123-4567',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_phone',
            array(
                'label'    => __( 'Phone Number', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'text',
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_email',
            array(
                'default'           => 'info@aqualuxe.com',
                'sanitize_callback' => 'sanitize_email',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_email',
            array(
                'label'    => __( 'Email Address', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'email',
            )
        );

        $wp_customize->add_setting(
            'aqualuxe_address',
            array(
                'default'           => '123 Aquarium Street, Ocean City, CA 90210',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_address',
            array(
                'label'    => __( 'Address', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'text',
            )
        );

        // Google Maps API Key
        $wp_customize->add_setting(
            'aqualuxe_google_maps_api_key',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_google_maps_api_key',
            array(
                'label'    => __( 'Google Maps API Key', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'text',
                'description' => __( 'Enter your Google Maps API key to enable maps on the contact page.', 'aqualuxe' ),
            )
        );

        // Google Maps Latitude
        $wp_customize->add_setting(
            'aqualuxe_google_maps_latitude',
            array(
                'default'           => '34.052235',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_google_maps_latitude',
            array(
                'label'    => __( 'Google Maps Latitude', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'text',
            )
        );

        // Google Maps Longitude
        $wp_customize->add_setting(
            'aqualuxe_google_maps_longitude',
            array(
                'default'           => '-118.243683',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_google_maps_longitude',
            array(
                'label'    => __( 'Google Maps Longitude', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'text',
            )
        );

        // Google Maps Zoom
        $wp_customize->add_setting(
            'aqualuxe_google_maps_zoom',
            array(
                'default'           => '15',
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_google_maps_zoom',
            array(
                'label'    => __( 'Google Maps Zoom Level', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 20,
                    'step' => 1,
                ),
            )
        );
    }

    /**
     * Register header settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_header_settings( $wp_customize ) {
        // Header Settings Section
        $wp_customize->add_section(
            'aqualuxe_header_settings',
            array(
                'title'    => __( 'Header Settings', 'aqualuxe' ),
                'priority' => 40,
            )
        );

        // Header Style
        $wp_customize->add_setting(
            'aqualuxe_header_style',
            array(
                'default'           => 'default',
                'sanitize_callback' => array( $this, 'sanitize_select' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_header_style',
            array(
                'label'    => __( 'Header Style', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_settings',
                'type'     => 'select',
                'choices'  => array(
                    'default' => __( 'Default', 'aqualuxe' ),
                    'transparent' => __( 'Transparent', 'aqualuxe' ),
                    'centered' => __( 'Centered', 'aqualuxe' ),
                    'minimal' => __( 'Minimal', 'aqualuxe' ),
                ),
            )
        );

        // Sticky Header
        $wp_customize->add_setting(
            'aqualuxe_sticky_header',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_sticky_header',
            array(
                'label'    => __( 'Enable Sticky Header', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_settings',
                'type'     => 'checkbox',
            )
        );

        // Top Bar
        $wp_customize->add_setting(
            'aqualuxe_show_top_bar',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_top_bar',
            array(
                'label'    => __( 'Show Top Bar', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_settings',
                'type'     => 'checkbox',
            )
        );

        // Header Search
        $wp_customize->add_setting(
            'aqualuxe_show_header_search',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_header_search',
            array(
                'label'    => __( 'Show Header Search', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_settings',
                'type'     => 'checkbox',
            )
        );

        // Header Cart
        $wp_customize->add_setting(
            'aqualuxe_show_header_cart',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_header_cart',
            array(
                'label'    => __( 'Show Header Cart', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_settings',
                'type'     => 'checkbox',
            )
        );

        // Header Account
        $wp_customize->add_setting(
            'aqualuxe_show_header_account',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_header_account',
            array(
                'label'    => __( 'Show Header Account', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_settings',
                'type'     => 'checkbox',
            )
        );

        // Header Background Color
        $wp_customize->add_setting(
            'aqualuxe_header_background_color',
            array(
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_header_background_color',
                array(
                    'label'    => __( 'Header Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_header_settings',
                )
            )
        );

        // Header Text Color
        $wp_customize->add_setting(
            'aqualuxe_header_text_color',
            array(
                'default'           => '#333333',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_header_text_color',
                array(
                    'label'    => __( 'Header Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_header_settings',
                )
            )
        );

        // Top Bar Background Color
        $wp_customize->add_setting(
            'aqualuxe_top_bar_background_color',
            array(
                'default'           => '#0e7c7b',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_top_bar_background_color',
                array(
                    'label'    => __( 'Top Bar Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_header_settings',
                )
            )
        );

        // Top Bar Text Color
        $wp_customize->add_setting(
            'aqualuxe_top_bar_text_color',
            array(
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_top_bar_text_color',
                array(
                    'label'    => __( 'Top Bar Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_header_settings',
                )
            )
        );
    }

    /**
     * Register footer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_footer_settings( $wp_customize ) {
        // Footer Settings Section
        $wp_customize->add_section(
            'aqualuxe_footer_settings',
            array(
                'title'    => __( 'Footer Settings', 'aqualuxe' ),
                'priority' => 50,
            )
        );

        // Footer Style
        $wp_customize->add_setting(
            'aqualuxe_footer_style',
            array(
                'default'           => 'default',
                'sanitize_callback' => array( $this, 'sanitize_select' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_footer_style',
            array(
                'label'    => __( 'Footer Style', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_settings',
                'type'     => 'select',
                'choices'  => array(
                    'default' => __( 'Default', 'aqualuxe' ),
                    'centered' => __( 'Centered', 'aqualuxe' ),
                    'minimal' => __( 'Minimal', 'aqualuxe' ),
                    'dark' => __( 'Dark', 'aqualuxe' ),
                ),
            )
        );

        // Footer Widgets
        $wp_customize->add_setting(
            'aqualuxe_footer_widgets',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_footer_widgets',
            array(
                'label'    => __( 'Show Footer Widgets', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_settings',
                'type'     => 'checkbox',
            )
        );

        // Footer Newsletter
        $wp_customize->add_setting(
            'aqualuxe_show_footer_newsletter',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_footer_newsletter',
            array(
                'label'    => __( 'Show Footer Newsletter', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_settings',
                'type'     => 'checkbox',
            )
        );

        // Newsletter Title
        $wp_customize->add_setting(
            'aqualuxe_newsletter_title',
            array(
                'default'           => __( 'Subscribe to Our Newsletter', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_newsletter_title',
            array(
                'label'    => __( 'Newsletter Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_settings',
                'type'     => 'text',
            )
        );

        // Newsletter Text
        $wp_customize->add_setting(
            'aqualuxe_newsletter_text',
            array(
                'default'           => __( 'Stay updated with our latest products, offers, and aquatic care tips.', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_newsletter_text',
            array(
                'label'    => __( 'Newsletter Text', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_settings',
                'type'     => 'text',
            )
        );

        // Newsletter Shortcode
        $wp_customize->add_setting(
            'aqualuxe_newsletter_shortcode',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_newsletter_shortcode',
            array(
                'label'    => __( 'Newsletter Shortcode', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_settings',
                'type'     => 'text',
                'description' => __( 'Enter the shortcode for your newsletter form (e.g., from MailChimp, Contact Form 7, etc.).', 'aqualuxe' ),
            )
        );

        // Copyright Text
        $wp_customize->add_setting(
            'aqualuxe_copyright_text',
            array(
                'default'           => '&copy; ' . date( 'Y' ) . ' AquaLuxe. All Rights Reserved.',
                'sanitize_callback' => array( $this, 'sanitize_html' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_copyright_text',
            array(
                'label'    => __( 'Copyright Text', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_settings',
                'type'     => 'textarea',
            )
        );

        // Payment Icons
        $wp_customize->add_setting(
            'aqualuxe_show_payment_icons',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_payment_icons',
            array(
                'label'    => __( 'Show Payment Icons', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_settings',
                'type'     => 'checkbox',
            )
        );

        // Footer Background Color
        $wp_customize->add_setting(
            'aqualuxe_footer_background_color',
            array(
                'default'           => '#0e3b3b',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_footer_background_color',
                array(
                    'label'    => __( 'Footer Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_footer_settings',
                )
            )
        );

        // Footer Text Color
        $wp_customize->add_setting(
            'aqualuxe_footer_text_color',
            array(
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_footer_text_color',
                array(
                    'label'    => __( 'Footer Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_footer_settings',
                )
            )
        );

        // Footer Bottom Background Color
        $wp_customize->add_setting(
            'aqualuxe_footer_bottom_background_color',
            array(
                'default'           => '#072727',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_footer_bottom_background_color',
                array(
                    'label'    => __( 'Footer Bottom Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_footer_settings',
                )
            )
        );

        // Footer Bottom Text Color
        $wp_customize->add_setting(
            'aqualuxe_footer_bottom_text_color',
            array(
                'default'           => '#cccccc',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_footer_bottom_text_color',
                array(
                    'label'    => __( 'Footer Bottom Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_footer_settings',
                )
            )
        );
    }

    /**
     * Register homepage settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_homepage_settings( $wp_customize ) {
        // Homepage Settings Section
        $wp_customize->add_section(
            'aqualuxe_homepage_settings',
            array(
                'title'    => __( 'Homepage Settings', 'aqualuxe' ),
                'priority' => 60,
            )
        );

        // Hero Section
        $wp_customize->add_setting(
            'aqualuxe_show_homepage_hero',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_homepage_hero',
            array(
                'label'    => __( 'Show Hero Section', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'checkbox',
            )
        );

        // Hero Title
        $wp_customize->add_setting(
            'aqualuxe_hero_title',
            array(
                'default'           => __( 'Discover the Beauty of Exotic Aquatic Life', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_hero_title',
            array(
                'label'    => __( 'Hero Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'text',
            )
        );

        // Hero Subtitle
        $wp_customize->add_setting(
            'aqualuxe_hero_subtitle',
            array(
                'default'           => __( 'Premium ornamental fish and aquatic products for enthusiasts and collectors', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_hero_subtitle',
            array(
                'label'    => __( 'Hero Subtitle', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'text',
            )
        );

        // Hero Button Text
        $wp_customize->add_setting(
            'aqualuxe_hero_button_text',
            array(
                'default'           => __( 'Shop Now', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_hero_button_text',
            array(
                'label'    => __( 'Hero Button Text', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'text',
            )
        );

        // Hero Button URL
        $wp_customize->add_setting(
            'aqualuxe_hero_button_url',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_hero_button_url',
            array(
                'label'    => __( 'Hero Button URL', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'url',
            )
        );

        // Hero Background Image
        $wp_customize->add_setting(
            'aqualuxe_hero_background',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_hero_background',
                array(
                    'label'    => __( 'Hero Background Image', 'aqualuxe' ),
                    'section'  => 'aqualuxe_homepage_settings',
                )
            )
        );

        // Featured Products Section
        $wp_customize->add_setting(
            'aqualuxe_show_homepage_featured_products',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_homepage_featured_products',
            array(
                'label'    => __( 'Show Featured Products Section', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'checkbox',
            )
        );

        // Featured Products Title
        $wp_customize->add_setting(
            'aqualuxe_featured_products_title',
            array(
                'default'           => __( 'Featured Products', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_featured_products_title',
            array(
                'label'    => __( 'Featured Products Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'text',
            )
        );

        // Featured Products Count
        $wp_customize->add_setting(
            'aqualuxe_featured_products_count',
            array(
                'default'           => 4,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_featured_products_count',
            array(
                'label'    => __( 'Number of Featured Products', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 12,
                    'step' => 1,
                ),
            )
        );

        // Categories Section
        $wp_customize->add_setting(
            'aqualuxe_show_homepage_categories',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_homepage_categories',
            array(
                'label'    => __( 'Show Categories Section', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'checkbox',
            )
        );

        // Categories Title
        $wp_customize->add_setting(
            'aqualuxe_categories_title',
            array(
                'default'           => __( 'Shop by Category', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_categories_title',
            array(
                'label'    => __( 'Categories Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'text',
            )
        );

        // Categories Count
        $wp_customize->add_setting(
            'aqualuxe_categories_count',
            array(
                'default'           => 4,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_categories_count',
            array(
                'label'    => __( 'Number of Categories', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 12,
                    'step' => 1,
                ),
            )
        );

        // About Section
        $wp_customize->add_setting(
            'aqualuxe_show_homepage_about',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_homepage_about',
            array(
                'label'    => __( 'Show About Section', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'checkbox',
            )
        );

        // About Title
        $wp_customize->add_setting(
            'aqualuxe_about_title',
            array(
                'default'           => __( 'About AquaLuxe', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_about_title',
            array(
                'label'    => __( 'About Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'text',
            )
        );

        // About Content
        $wp_customize->add_setting(
            'aqualuxe_about_content',
            array(
                'default'           => __( 'AquaLuxe is a premium ornamental fish farming business dedicated to providing the highest quality exotic fish species and aquatic products to enthusiasts and collectors worldwide.', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_textarea_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_about_content',
            array(
                'label'    => __( 'About Content', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'textarea',
            )
        );

        // About Image
        $wp_customize->add_setting(
            'aqualuxe_about_image',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_about_image',
                array(
                    'label'    => __( 'About Image', 'aqualuxe' ),
                    'section'  => 'aqualuxe_homepage_settings',
                )
            )
        );

        // About Button Text
        $wp_customize->add_setting(
            'aqualuxe_about_button_text',
            array(
                'default'           => __( 'Learn More', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_about_button_text',
            array(
                'label'    => __( 'About Button Text', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'text',
            )
        );

        // About Button URL
        $wp_customize->add_setting(
            'aqualuxe_about_button_url',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_about_button_url',
            array(
                'label'    => __( 'About Button URL', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'url',
            )
        );

        // Testimonials Section
        $wp_customize->add_setting(
            'aqualuxe_show_homepage_testimonials',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_homepage_testimonials',
            array(
                'label'    => __( 'Show Testimonials Section', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'checkbox',
            )
        );

        // Testimonials Title
        $wp_customize->add_setting(
            'aqualuxe_testimonials_title',
            array(
                'default'           => __( 'What Our Customers Say', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_testimonials_title',
            array(
                'label'    => __( 'Testimonials Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'text',
            )
        );

        // Testimonials Count
        $wp_customize->add_setting(
            'aqualuxe_testimonials_count',
            array(
                'default'           => 3,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_testimonials_count',
            array(
                'label'    => __( 'Number of Testimonials', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 10,
                    'step' => 1,
                ),
            )
        );

        // Latest Posts Section
        $wp_customize->add_setting(
            'aqualuxe_show_homepage_latest_posts',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_homepage_latest_posts',
            array(
                'label'    => __( 'Show Latest Posts Section', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'checkbox',
            )
        );

        // Latest Posts Title
        $wp_customize->add_setting(
            'aqualuxe_latest_posts_title',
            array(
                'default'           => __( 'Latest From Our Blog', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_latest_posts_title',
            array(
                'label'    => __( 'Latest Posts Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'text',
            )
        );

        // Latest Posts Count
        $wp_customize->add_setting(
            'aqualuxe_latest_posts_count',
            array(
                'default'           => 3,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_latest_posts_count',
            array(
                'label'    => __( 'Number of Latest Posts', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 10,
                    'step' => 1,
                ),
            )
        );

        // Homepage Newsletter
        $wp_customize->add_setting(
            'aqualuxe_show_homepage_newsletter',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_homepage_newsletter',
            array(
                'label'    => __( 'Show Homepage Newsletter', 'aqualuxe' ),
                'section'  => 'aqualuxe_homepage_settings',
                'type'     => 'checkbox',
            )
        );
    }

    /**
     * Register blog settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_blog_settings( $wp_customize ) {
        // Blog Settings Section
        $wp_customize->add_section(
            'aqualuxe_blog_settings',
            array(
                'title'    => __( 'Blog Settings', 'aqualuxe' ),
                'priority' => 70,
            )
        );

        // Blog Layout
        $wp_customize->add_setting(
            'aqualuxe_blog_layout',
            array(
                'default'           => 'right-sidebar',
                'sanitize_callback' => array( $this, 'sanitize_select' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_blog_layout',
            array(
                'label'    => __( 'Blog Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_settings',
                'type'     => 'select',
                'choices'  => array(
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
                ),
            )
        );

        // Blog Style
        $wp_customize->add_setting(
            'aqualuxe_blog_style',
            array(
                'default'           => 'default',
                'sanitize_callback' => array( $this, 'sanitize_select' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_blog_style',
            array(
                'label'    => __( 'Blog Style', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_settings',
                'type'     => 'select',
                'choices'  => array(
                    'default' => __( 'Default', 'aqualuxe' ),
                    'grid'    => __( 'Grid', 'aqualuxe' ),
                    'masonry' => __( 'Masonry', 'aqualuxe' ),
                ),
            )
        );

        // Excerpt Length
        $wp_customize->add_setting(
            'aqualuxe_excerpt_length',
            array(
                'default'           => 30,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_excerpt_length',
            array(
                'label'    => __( 'Excerpt Length', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 10,
                    'max'  => 100,
                    'step' => 5,
                ),
            )
        );

        // Show Featured Image
        $wp_customize->add_setting(
            'aqualuxe_show_featured_image',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_featured_image',
            array(
                'label'    => __( 'Show Featured Image', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Post Meta
        $wp_customize->add_setting(
            'aqualuxe_show_post_meta',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_post_meta',
            array(
                'label'    => __( 'Show Post Meta', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Author Box
        $wp_customize->add_setting(
            'aqualuxe_show_author_box',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_author_box',
            array(
                'label'    => __( 'Show Author Box', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Related Posts
        $wp_customize->add_setting(
            'aqualuxe_show_related_posts',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_related_posts',
            array(
                'label'    => __( 'Show Related Posts', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Post Navigation
        $wp_customize->add_setting(
            'aqualuxe_show_post_navigation',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_post_navigation',
            array(
                'label'    => __( 'Show Post Navigation', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_settings',
                'type'     => 'checkbox',
            )
        );

        // Related Posts Count
        $wp_customize->add_setting(
            'aqualuxe_related_posts_count',
            array(
                'default'           => 3,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_posts_count',
            array(
                'label'    => __( 'Number of Related Posts', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 10,
                    'step' => 1,
                ),
            )
        );
    }

    /**
     * Register shop settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_shop_settings( $wp_customize ) {
        // Shop Settings Section
        $wp_customize->add_section(
            'aqualuxe_shop_settings',
            array(
                'title'    => __( 'Shop Settings', 'aqualuxe' ),
                'priority' => 80,
            )
        );

        // Shop Layout
        $wp_customize->add_setting(
            'aqualuxe_shop_layout',
            array(
                'default'           => 'right-sidebar',
                'sanitize_callback' => array( $this, 'sanitize_select' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_layout',
            array(
                'label'    => __( 'Shop Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'select',
                'choices'  => array(
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'no-sidebar'    => __( 'No Sidebar', 'aqualuxe' ),
                ),
            )
        );

        // Shop Sidebar
        $wp_customize->add_setting(
            'aqualuxe_shop_sidebar',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_sidebar',
            array(
                'label'    => __( 'Show Shop Sidebar', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'checkbox',
            )
        );

        // Shop Columns
        $wp_customize->add_setting(
            'aqualuxe_shop_columns',
            array(
                'default'           => 3,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_columns',
            array(
                'label'    => __( 'Shop Columns', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 2,
                    'max'  => 6,
                    'step' => 1,
                ),
            )
        );

        // Products Per Page
        $wp_customize->add_setting(
            'aqualuxe_shop_products_per_page',
            array(
                'default'           => 12,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_products_per_page',
            array(
                'label'    => __( 'Products Per Page', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 100,
                    'step' => 1,
                ),
            )
        );

        // Show Sale Badge
        $wp_customize->add_setting(
            'aqualuxe_show_sale_badge',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_sale_badge',
            array(
                'label'    => __( 'Show Sale Badge', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'checkbox',
            )
        );

        // Sale Badge Style
        $wp_customize->add_setting(
            'aqualuxe_sale_badge_style',
            array(
                'default'           => 'percentage',
                'sanitize_callback' => array( $this, 'sanitize_select' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_sale_badge_style',
            array(
                'label'    => __( 'Sale Badge Style', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'select',
                'choices'  => array(
                    'percentage' => __( 'Percentage', 'aqualuxe' ),
                    'text'       => __( 'Text', 'aqualuxe' ),
                ),
            )
        );

        // Show Featured Badge
        $wp_customize->add_setting(
            'aqualuxe_show_featured_badge',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_featured_badge',
            array(
                'label'    => __( 'Show Featured Badge', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'checkbox',
            )
        );

        // New Badge Days
        $wp_customize->add_setting(
            'aqualuxe_new_badge_days',
            array(
                'default'           => 7,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_new_badge_days',
            array(
                'label'    => __( 'Show "New" Badge for (days)', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 30,
                    'step' => 1,
                ),
            )
        );

        // Shop Page Title
        $wp_customize->add_setting(
            'aqualuxe_shop_page_title',
            array(
                'default'           => __( 'Shop', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_page_title',
            array(
                'label'    => __( 'Shop Page Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'text',
            )
        );

        // Shop Page Description
        $wp_customize->add_setting(
            'aqualuxe_shop_page_description',
            array(
                'default'           => __( 'Browse our collection of premium ornamental fish and aquatic products.', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_textarea_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_shop_page_description',
            array(
                'label'    => __( 'Shop Page Description', 'aqualuxe' ),
                'section'  => 'aqualuxe_shop_settings',
                'type'     => 'textarea',
            )
        );
    }

    /**
     * Register product settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_product_settings( $wp_customize ) {
        // Product Settings Section
        $wp_customize->add_section(
            'aqualuxe_product_settings',
            array(
                'title'    => __( 'Product Settings', 'aqualuxe' ),
                'priority' => 90,
            )
        );

        // Product Layout
        $wp_customize->add_setting(
            'aqualuxe_product_layout',
            array(
                'default'           => 'default',
                'sanitize_callback' => array( $this, 'sanitize_select' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_product_layout',
            array(
                'label'    => __( 'Product Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'select',
                'choices'  => array(
                    'default'  => __( 'Default', 'aqualuxe' ),
                    'gallery'  => __( 'Gallery', 'aqualuxe' ),
                    'stacked'  => __( 'Stacked', 'aqualuxe' ),
                    'expanded' => __( 'Expanded', 'aqualuxe' ),
                ),
            )
        );

        // Show Product Quick View
        $wp_customize->add_setting(
            'aqualuxe_show_product_quick_view',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_product_quick_view',
            array(
                'label'    => __( 'Show Product Quick View', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Product Wishlist
        $wp_customize->add_setting(
            'aqualuxe_show_product_wishlist',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_product_wishlist',
            array(
                'label'    => __( 'Show Product Wishlist', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Product Compare
        $wp_customize->add_setting(
            'aqualuxe_show_product_compare',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_product_compare',
            array(
                'label'    => __( 'Show Product Compare', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Description Tab
        $wp_customize->add_setting(
            'aqualuxe_show_description_tab',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_description_tab',
            array(
                'label'    => __( 'Show Description Tab', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Additional Information Tab
        $wp_customize->add_setting(
            'aqualuxe_show_additional_information_tab',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_additional_information_tab',
            array(
                'label'    => __( 'Show Additional Information Tab', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Reviews Tab
        $wp_customize->add_setting(
            'aqualuxe_show_reviews_tab',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_reviews_tab',
            array(
                'label'    => __( 'Show Reviews Tab', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Custom Tab
        $wp_customize->add_setting(
            'aqualuxe_show_custom_tab',
            array(
                'default'           => false,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_custom_tab',
            array(
                'label'    => __( 'Show Custom Tab', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'checkbox',
            )
        );

        // Custom Tab Title
        $wp_customize->add_setting(
            'aqualuxe_custom_tab_title',
            array(
                'default'           => __( 'Custom Tab', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_custom_tab_title',
            array(
                'label'    => __( 'Custom Tab Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'text',
            )
        );

        // Custom Tab Content
        $wp_customize->add_setting(
            'aqualuxe_custom_tab_content',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_custom_tab_content',
            array(
                'label'    => __( 'Custom Tab Content', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'textarea',
            )
        );

        // Related Products
        $wp_customize->add_setting(
            'aqualuxe_related_products_count',
            array(
                'default'           => 4,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products_count',
            array(
                'label'    => __( 'Number of Related Products', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 12,
                    'step' => 1,
                ),
            )
        );

        // Related Products Columns
        $wp_customize->add_setting(
            'aqualuxe_related_products_columns',
            array(
                'default'           => 4,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_related_products_columns',
            array(
                'label'    => __( 'Related Products Columns', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 6,
                    'step' => 1,
                ),
            )
        );

        // Upsell Products
        $wp_customize->add_setting(
            'aqualuxe_upsell_products_count',
            array(
                'default'           => 4,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_upsell_products_count',
            array(
                'label'    => __( 'Number of Upsell Products', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 12,
                    'step' => 1,
                ),
            )
        );

        // Upsell Products Columns
        $wp_customize->add_setting(
            'aqualuxe_upsell_products_columns',
            array(
                'default'           => 4,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_upsell_products_columns',
            array(
                'label'    => __( 'Upsell Products Columns', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 6,
                    'step' => 1,
                ),
            )
        );

        // Cross-Sell Products Columns
        $wp_customize->add_setting(
            'aqualuxe_cross_sell_columns',
            array(
                'default'           => 2,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_cross_sell_columns',
            array(
                'label'    => __( 'Cross-Sell Products Columns', 'aqualuxe' ),
                'section'  => 'aqualuxe_product_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 4,
                    'step' => 1,
                ),
            )
        );
    }

    /**
     * Register typography settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_typography_settings( $wp_customize ) {
        // Typography Settings Section
        $wp_customize->add_section(
            'aqualuxe_typography_settings',
            array(
                'title'    => __( 'Typography Settings', 'aqualuxe' ),
                'priority' => 100,
            )
        );

        // Body Font Family
        $wp_customize->add_setting(
            'aqualuxe_body_font_family',
            array(
                'default'           => 'Poppins',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_body_font_family',
            array(
                'label'    => __( 'Body Font Family', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'select',
                'choices'  => array(
                    'Poppins'       => 'Poppins',
                    'Roboto'        => 'Roboto',
                    'Open Sans'     => 'Open Sans',
                    'Lato'          => 'Lato',
                    'Montserrat'    => 'Montserrat',
                    'Source Sans Pro' => 'Source Sans Pro',
                    'Raleway'       => 'Raleway',
                    'PT Sans'       => 'PT Sans',
                    'Nunito'        => 'Nunito',
                    'Nunito Sans'   => 'Nunito Sans',
                ),
            )
        );

        // Headings Font Family
        $wp_customize->add_setting(
            'aqualuxe_headings_font_family',
            array(
                'default'           => 'Playfair Display',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_headings_font_family',
            array(
                'label'    => __( 'Headings Font Family', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'select',
                'choices'  => array(
                    'Playfair Display' => 'Playfair Display',
                    'Poppins'       => 'Poppins',
                    'Roboto'        => 'Roboto',
                    'Open Sans'     => 'Open Sans',
                    'Lato'          => 'Lato',
                    'Montserrat'    => 'Montserrat',
                    'Source Sans Pro' => 'Source Sans Pro',
                    'Raleway'       => 'Raleway',
                    'PT Sans'       => 'PT Sans',
                    'Merriweather'  => 'Merriweather',
                    'Lora'          => 'Lora',
                ),
            )
        );

        // Body Font Size
        $wp_customize->add_setting(
            'aqualuxe_body_font_size',
            array(
                'default'           => 16,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_body_font_size',
            array(
                'label'    => __( 'Body Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 12,
                    'max'  => 24,
                    'step' => 1,
                ),
            )
        );

        // Body Line Height
        $wp_customize->add_setting(
            'aqualuxe_body_line_height',
            array(
                'default'           => 1.6,
                'sanitize_callback' => 'aqualuxe_sanitize_float',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_body_line_height',
            array(
                'label'    => __( 'Body Line Height', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 2,
                    'step' => 0.1,
                ),
            )
        );

        // H1 Font Size
        $wp_customize->add_setting(
            'aqualuxe_h1_font_size',
            array(
                'default'           => 36,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_h1_font_size',
            array(
                'label'    => __( 'H1 Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 20,
                    'max'  => 60,
                    'step' => 1,
                ),
            )
        );

        // H2 Font Size
        $wp_customize->add_setting(
            'aqualuxe_h2_font_size',
            array(
                'default'           => 30,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_h2_font_size',
            array(
                'label'    => __( 'H2 Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 18,
                    'max'  => 50,
                    'step' => 1,
                ),
            )
        );

        // H3 Font Size
        $wp_customize->add_setting(
            'aqualuxe_h3_font_size',
            array(
                'default'           => 24,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_h3_font_size',
            array(
                'label'    => __( 'H3 Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 16,
                    'max'  => 40,
                    'step' => 1,
                ),
            )
        );

        // H4 Font Size
        $wp_customize->add_setting(
            'aqualuxe_h4_font_size',
            array(
                'default'           => 20,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_h4_font_size',
            array(
                'label'    => __( 'H4 Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 14,
                    'max'  => 30,
                    'step' => 1,
                ),
            )
        );

        // H5 Font Size
        $wp_customize->add_setting(
            'aqualuxe_h5_font_size',
            array(
                'default'           => 18,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_h5_font_size',
            array(
                'label'    => __( 'H5 Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 12,
                    'max'  => 24,
                    'step' => 1,
                ),
            )
        );

        // H6 Font Size
        $wp_customize->add_setting(
            'aqualuxe_h6_font_size',
            array(
                'default'           => 16,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_h6_font_size',
            array(
                'label'    => __( 'H6 Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 10,
                    'max'  => 20,
                    'step' => 1,
                ),
            )
        );

        // Headings Line Height
        $wp_customize->add_setting(
            'aqualuxe_headings_line_height',
            array(
                'default'           => 1.3,
                'sanitize_callback' => 'aqualuxe_sanitize_float',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_headings_line_height',
            array(
                'label'    => __( 'Headings Line Height', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 2,
                    'step' => 0.1,
                ),
            )
        );

        // Headings Font Weight
        $wp_customize->add_setting(
            'aqualuxe_headings_font_weight',
            array(
                'default'           => '600',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_headings_font_weight',
            array(
                'label'    => __( 'Headings Font Weight', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography_settings',
                'type'     => 'select',
                'choices'  => array(
                    '300' => __( 'Light (300)', 'aqualuxe' ),
                    '400' => __( 'Regular (400)', 'aqualuxe' ),
                    '500' => __( 'Medium (500)', 'aqualuxe' ),
                    '600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
                    '700' => __( 'Bold (700)', 'aqualuxe' ),
                ),
            )
        );
    }

    /**
     * Register color settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_color_settings( $wp_customize ) {
        // Color Settings Section
        $wp_customize->add_section(
            'aqualuxe_color_settings',
            array(
                'title'    => __( 'Color Settings', 'aqualuxe' ),
                'priority' => 110,
            )
        );

        // Color Scheme
        $wp_customize->add_setting(
            'aqualuxe_color_scheme',
            array(
                'default'           => 'default',
                'sanitize_callback' => array( $this, 'sanitize_select' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_color_scheme',
            array(
                'label'    => __( 'Color Scheme', 'aqualuxe' ),
                'section'  => 'aqualuxe_color_settings',
                'type'     => 'select',
                'choices'  => array(
                    'default' => __( 'Default (Teal)', 'aqualuxe' ),
                    'blue'    => __( 'Blue', 'aqualuxe' ),
                    'green'   => __( 'Green', 'aqualuxe' ),
                    'purple'  => __( 'Purple', 'aqualuxe' ),
                    'custom'  => __( 'Custom', 'aqualuxe' ),
                ),
            )
        );

        // Primary Color
        $wp_customize->add_setting(
            'aqualuxe_primary_color',
            array(
                'default'           => '#0e7c7b',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_primary_color',
                array(
                    'label'    => __( 'Primary Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Secondary Color
        $wp_customize->add_setting(
            'aqualuxe_secondary_color',
            array(
                'default'           => '#0e3b3b',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_secondary_color',
                array(
                    'label'    => __( 'Secondary Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Accent Color
        $wp_customize->add_setting(
            'aqualuxe_accent_color',
            array(
                'default'           => '#f9bf29',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_accent_color',
                array(
                    'label'    => __( 'Accent Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Text Color
        $wp_customize->add_setting(
            'aqualuxe_text_color',
            array(
                'default'           => '#333333',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_text_color',
                array(
                    'label'    => __( 'Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Heading Color
        $wp_customize->add_setting(
            'aqualuxe_heading_color',
            array(
                'default'           => '#0e3b3b',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_heading_color',
                array(
                    'label'    => __( 'Heading Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Link Color
        $wp_customize->add_setting(
            'aqualuxe_link_color',
            array(
                'default'           => '#0e7c7b',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_link_color',
                array(
                    'label'    => __( 'Link Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Link Hover Color
        $wp_customize->add_setting(
            'aqualuxe_link_hover_color',
            array(
                'default'           => '#0a5e5d',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_link_hover_color',
                array(
                    'label'    => __( 'Link Hover Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Button Background Color
        $wp_customize->add_setting(
            'aqualuxe_button_background_color',
            array(
                'default'           => '#0e7c7b',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_button_background_color',
                array(
                    'label'    => __( 'Button Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Button Text Color
        $wp_customize->add_setting(
            'aqualuxe_button_text_color',
            array(
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_button_text_color',
                array(
                    'label'    => __( 'Button Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Button Hover Background Color
        $wp_customize->add_setting(
            'aqualuxe_button_hover_background_color',
            array(
                'default'           => '#0a5e5d',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_button_hover_background_color',
                array(
                    'label'    => __( 'Button Hover Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Button Hover Text Color
        $wp_customize->add_setting(
            'aqualuxe_button_hover_text_color',
            array(
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_button_hover_text_color',
                array(
                    'label'    => __( 'Button Hover Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );

        // Body Background Color
        $wp_customize->add_setting(
            'aqualuxe_body_background_color',
            array(
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_body_background_color',
                array(
                    'label'    => __( 'Body Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_color_settings',
                )
            )
        );
    }

    /**
     * Register social settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function register_social_settings( $wp_customize ) {
        // Social Settings Section
        $wp_customize->add_section(
            'aqualuxe_social_settings',
            array(
                'title'    => __( 'Social Media Settings', 'aqualuxe' ),
                'priority' => 120,
            )
        );

        // Facebook
        $wp_customize->add_setting(
            'aqualuxe_facebook',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_facebook',
            array(
                'label'    => __( 'Facebook URL', 'aqualuxe' ),
                'section'  => 'aqualuxe_social_settings',
                'type'     => 'url',
            )
        );

        // Twitter
        $wp_customize->add_setting(
            'aqualuxe_twitter',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_twitter',
            array(
                'label'    => __( 'Twitter URL', 'aqualuxe' ),
                'section'  => 'aqualuxe_social_settings',
                'type'     => 'url',
            )
        );

        // Instagram
        $wp_customize->add_setting(
            'aqualuxe_instagram',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_instagram',
            array(
                'label'    => __( 'Instagram URL', 'aqualuxe' ),
                'section'  => 'aqualuxe_social_settings',
                'type'     => 'url',
            )
        );

        // YouTube
        $wp_customize->add_setting(
            'aqualuxe_youtube',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_youtube',
            array(
                'label'    => __( 'YouTube URL', 'aqualuxe' ),
                'section'  => 'aqualuxe_social_settings',
                'type'     => 'url',
            )
        );

        // Pinterest
        $wp_customize->add_setting(
            'aqualuxe_pinterest',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_pinterest',
            array(
                'label'    => __( 'Pinterest URL', 'aqualuxe' ),
                'section'  => 'aqualuxe_social_settings',
                'type'     => 'url',
            )
        );

        // LinkedIn
        $wp_customize->add_setting(
            'aqualuxe_linkedin',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_linkedin',
            array(
                'label'    => __( 'LinkedIn URL', 'aqualuxe' ),
                'section'  => 'aqualuxe_social_settings',
                'type'     => 'url',
            )
        );

        // Show Social Icons in Header
        $wp_customize->add_setting(
            'aqualuxe_show_social_header',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_social_header',
            array(
                'label'    => __( 'Show Social Icons in Header', 'aqualuxe' ),
                'section'  => 'aqualuxe_social_settings',
                'type'     => 'checkbox',
            )
        );

        // Show Social Icons in Footer
        $wp_customize->add_setting(
            'aqualuxe_show_social_footer',
            array(
                'default'           => true,
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
            )
        );

        $wp_customize->add_control(
            'aqualuxe_show_social_footer',
            array(
                'label'    => __( 'Show Social Icons in Footer', 'aqualuxe' ),
                'section'  => 'aqualuxe_social_settings',
                'type'     => 'checkbox',
            )
        );
    }

    /**
     * Render the site title for the selective refresh partial.
     *
     * @return void
     */
    public function customize_partial_blogname() {
        bloginfo( 'name' );
    }

    /**
     * Render the site tagline for the selective refresh partial.
     *
     * @return void
     */
    public function customize_partial_blogdescription() {
        bloginfo( 'description' );
    }

    /**
     * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
     */
    public function preview_js() {
        wp_enqueue_script( 'aqualuxe-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
    }

    /**
     * Output CSS based on customizer settings
     */
    public function output_css() {
        // Get color scheme
        $color_scheme = get_theme_mod( 'aqualuxe_color_scheme', 'default' );

        // Set colors based on color scheme
        if ( 'default' === $color_scheme ) {
            $primary_color = '#0e7c7b';
            $secondary_color = '#0e3b3b';
            $accent_color = '#f9bf29';
        } elseif ( 'blue' === $color_scheme ) {
            $primary_color = '#1e73be';
            $secondary_color = '#0d3d63';
            $accent_color = '#f9bf29';
        } elseif ( 'green' === $color_scheme ) {
            $primary_color = '#2e7d32';
            $secondary_color = '#1b5e20';
            $accent_color = '#ffc107';
        } elseif ( 'purple' === $color_scheme ) {
            $primary_color = '#7b1fa2';
            $secondary_color = '#4a148c';
            $accent_color = '#ff9800';
        } else {
            // Custom colors
            $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0e7c7b' );
            $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#0e3b3b' );
            $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#f9bf29' );
        }

        // Get typography settings
        $body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Poppins' );
        $headings_font_family = get_theme_mod( 'aqualuxe_headings_font_family', 'Playfair Display' );
        $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', 16 );
        $body_line_height = get_theme_mod( 'aqualuxe_body_line_height', 1.6 );
        $h1_font_size = get_theme_mod( 'aqualuxe_h1_font_size', 36 );
        $h2_font_size = get_theme_mod( 'aqualuxe_h2_font_size', 30 );
        $h3_font_size = get_theme_mod( 'aqualuxe_h3_font_size', 24 );
        $h4_font_size = get_theme_mod( 'aqualuxe_h4_font_size', 20 );
        $h5_font_size = get_theme_mod( 'aqualuxe_h5_font_size', 18 );
        $h6_font_size = get_theme_mod( 'aqualuxe_h6_font_size', 16 );
        $headings_line_height = get_theme_mod( 'aqualuxe_headings_line_height', 1.3 );
        $headings_font_weight = get_theme_mod( 'aqualuxe_headings_font_weight', '600' );

        // Get color settings
        $text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
        $heading_color = get_theme_mod( 'aqualuxe_heading_color', '#0e3b3b' );
        $link_color = get_theme_mod( 'aqualuxe_link_color', '#0e7c7b' );
        $link_hover_color = get_theme_mod( 'aqualuxe_link_hover_color', '#0a5e5d' );
        $button_background_color = get_theme_mod( 'aqualuxe_button_background_color', '#0e7c7b' );
        $button_text_color = get_theme_mod( 'aqualuxe_button_text_color', '#ffffff' );
        $button_hover_background_color = get_theme_mod( 'aqualuxe_button_hover_background_color', '#0a5e5d' );
        $button_hover_text_color = get_theme_mod( 'aqualuxe_button_hover_text_color', '#ffffff' );
        $body_background_color = get_theme_mod( 'aqualuxe_body_background_color', '#ffffff' );

        // Get header settings
        $header_background_color = get_theme_mod( 'aqualuxe_header_background_color', '#ffffff' );
        $header_text_color = get_theme_mod( 'aqualuxe_header_text_color', '#333333' );
        $top_bar_background_color = get_theme_mod( 'aqualuxe_top_bar_background_color', '#0e7c7b' );
        $top_bar_text_color = get_theme_mod( 'aqualuxe_top_bar_text_color', '#ffffff' );

        // Get footer settings
        $footer_background_color = get_theme_mod( 'aqualuxe_footer_background_color', '#0e3b3b' );
        $footer_text_color = get_theme_mod( 'aqualuxe_footer_text_color', '#ffffff' );
        $footer_bottom_background_color = get_theme_mod( 'aqualuxe_footer_bottom_background_color', '#072727' );
        $footer_bottom_text_color = get_theme_mod( 'aqualuxe_footer_bottom_text_color', '#cccccc' );

        // Get layout settings
        $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
        ?>
        <style type="text/css">
            :root {
                --primary-color: <?php echo esc_attr( $primary_color ); ?>;
                --secondary-color: <?php echo esc_attr( $secondary_color ); ?>;
                --accent-color: <?php echo esc_attr( $accent_color ); ?>;
                --text-color: <?php echo esc_attr( $text_color ); ?>;
                --heading-color: <?php echo esc_attr( $heading_color ); ?>;
                --link-color: <?php echo esc_attr( $link_color ); ?>;
                --link-hover-color: <?php echo esc_attr( $link_hover_color ); ?>;
                --button-background-color: <?php echo esc_attr( $button_background_color ); ?>;
                --button-text-color: <?php echo esc_attr( $button_text_color ); ?>;
                --button-hover-background-color: <?php echo esc_attr( $button_hover_background_color ); ?>;
                --button-hover-text-color: <?php echo esc_attr( $button_hover_text_color ); ?>;
                --body-background-color: <?php echo esc_attr( $body_background_color ); ?>;
                --header-background-color: <?php echo esc_attr( $header_background_color ); ?>;
                --header-text-color: <?php echo esc_attr( $header_text_color ); ?>;
                --top-bar-background-color: <?php echo esc_attr( $top_bar_background_color ); ?>;
                --top-bar-text-color: <?php echo esc_attr( $top_bar_text_color ); ?>;
                --footer-background-color: <?php echo esc_attr( $footer_background_color ); ?>;
                --footer-text-color: <?php echo esc_attr( $footer_text_color ); ?>;
                --footer-bottom-background-color: <?php echo esc_attr( $footer_bottom_background_color ); ?>;
                --footer-bottom-text-color: <?php echo esc_attr( $footer_bottom_text_color ); ?>;
                --body-font-family: '<?php echo esc_attr( $body_font_family ); ?>', sans-serif;
                --headings-font-family: '<?php echo esc_attr( $headings_font_family ); ?>', serif;
                --body-font-size: <?php echo esc_attr( $body_font_size ); ?>px;
                --body-line-height: <?php echo esc_attr( $body_line_height ); ?>;
                --h1-font-size: <?php echo esc_attr( $h1_font_size ); ?>px;
                --h2-font-size: <?php echo esc_attr( $h2_font_size ); ?>px;
                --h3-font-size: <?php echo esc_attr( $h3_font_size ); ?>px;
                --h4-font-size: <?php echo esc_attr( $h4_font_size ); ?>px;
                --h5-font-size: <?php echo esc_attr( $h5_font_size ); ?>px;
                --h6-font-size: <?php echo esc_attr( $h6_font_size ); ?>px;
                --headings-line-height: <?php echo esc_attr( $headings_line_height ); ?>;
                --headings-font-weight: <?php echo esc_attr( $headings_font_weight ); ?>;
                --container-width: <?php echo esc_attr( $container_width ); ?>px;
            }

            body {
                font-family: var(--body-font-family);
                font-size: var(--body-font-size);
                line-height: var(--body-line-height);
                color: var(--text-color);
                background-color: var(--body-background-color);
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: var(--headings-font-family);
                line-height: var(--headings-line-height);
                font-weight: var(--headings-font-weight);
                color: var(--heading-color);
            }

            h1 {
                font-size: var(--h1-font-size);
            }

            h2 {
                font-size: var(--h2-font-size);
            }

            h3 {
                font-size: var(--h3-font-size);
            }

            h4 {
                font-size: var(--h4-font-size);
            }

            h5 {
                font-size: var(--h5-font-size);
            }

            h6 {
                font-size: var(--h6-font-size);
            }

            a {
                color: var(--link-color);
            }

            a:hover,
            a:focus {
                color: var(--link-hover-color);
            }

            .button,
            button,
            input[type="button"],
            input[type="reset"],
            input[type="submit"],
            .woocommerce #respond input#submit,
            .woocommerce a.button,
            .woocommerce button.button,
            .woocommerce input.button,
            .woocommerce #respond input#submit.alt,
            .woocommerce a.button.alt,
            .woocommerce button.button.alt,
            .woocommerce input.button.alt {
                background-color: var(--button-background-color);
                color: var(--button-text-color);
            }

            .button:hover,
            button:hover,
            input[type="button"]:hover,
            input[type="reset"]:hover,
            input[type="submit"]:hover,
            .woocommerce #respond input#submit:hover,
            .woocommerce a.button:hover,
            .woocommerce button.button:hover,
            .woocommerce input.button:hover,
            .woocommerce #respond input#submit.alt:hover,
            .woocommerce a.button.alt:hover,
            .woocommerce button.button.alt:hover,
            .woocommerce input.button.alt:hover {
                background-color: var(--button-hover-background-color);
                color: var(--button-hover-text-color);
            }

            .container {
                max-width: var(--container-width);
            }

            .site-header {
                background-color: var(--header-background-color);
                color: var(--header-text-color);
            }

            .top-bar {
                background-color: var(--top-bar-background-color);
                color: var(--top-bar-text-color);
            }

            .site-footer {
                background-color: var(--footer-background-color);
                color: var(--footer-text-color);
            }

            .footer-bottom {
                background-color: var(--footer-bottom-background-color);
                color: var(--footer-bottom-text-color);
            }

            /* Primary Color Elements */
            .primary-color,
            .main-navigation ul li.current-menu-item > a,
            .main-navigation ul li.current-menu-ancestor > a,
            .main-navigation ul li.current_page_item > a,
            .main-navigation ul li.current_page_ancestor > a,
            .entry-meta a:hover,
            .entry-title a:hover,
            .widget a:hover,
            .woocommerce ul.products li.product .price,
            .woocommerce div.product p.price,
            .woocommerce div.product span.price,
            .woocommerce .star-rating span:before {
                color: var(--primary-color);
            }

            .bg-primary,
            .btn-primary,
            .pagination .current,
            .woocommerce span.onsale,
            .woocommerce nav.woocommerce-pagination ul li span.current,
            .woocommerce #respond input#submit.alt,
            .woocommerce a.button.alt,
            .woocommerce button.button.alt,
            .woocommerce input.button.alt {
                background-color: var(--primary-color);
            }

            .border-primary,
            .btn-outline-primary,
            .woocommerce .woocommerce-info,
            .woocommerce .woocommerce-message {
                border-color: var(--primary-color);
            }

            /* Secondary Color Elements */
            .secondary-color {
                color: var(--secondary-color);
            }

            .bg-secondary,
            .btn-secondary {
                background-color: var(--secondary-color);
            }

            .border-secondary,
            .btn-outline-secondary {
                border-color: var(--secondary-color);
            }

            /* Accent Color Elements */
            .accent-color {
                color: var(--accent-color);
            }

            .bg-accent,
            .btn-accent,
            .badge-featured {
                background-color: var(--accent-color);
            }

            .border-accent,
            .btn-outline-accent {
                border-color: var(--accent-color);
            }
        </style>
        <?php
    }

    /**
     * Sanitize checkbox
     *
     * @param bool $input Input value.
     * @return bool
     */
    public function sanitize_checkbox( $input ) {
        return ( isset( $input ) && true === $input ) ? true : false;
    }

    /**
     * Sanitize select
     *
     * @param string $input Input value.
     * @param object $setting Setting object.
     * @return string
     */
    public function sanitize_select( $input, $setting ) {
        $input = sanitize_key( $input );
        $choices = $setting->manager->get_control( $setting->id )->choices;
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
    }

    /**
     * Sanitize HTML
     *
     * @param string $input Input value.
     * @return string
     */
    public function sanitize_html( $input ) {
        return wp_kses_post( $input );
    }
}

// Initialize the customizer
new AquaLuxe_Customizer();

/**
 * Sanitize float
 *
 * @param float $input Input value.
 * @return float
 */
function aqualuxe_sanitize_float( $input ) {
    return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}