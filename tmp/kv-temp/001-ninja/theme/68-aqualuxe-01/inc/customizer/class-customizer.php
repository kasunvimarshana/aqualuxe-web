<?php
/**
 * Customizer Class
 *
 * @package AquaLuxe
 * @subpackage Customizer
 * @since 1.0.0
 */

namespace AquaLuxe\Customizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Customizer Class
 * 
 * This class is responsible for setting up the theme customizer.
 */
class Customizer {
    /**
     * Instance of this class
     *
     * @var Customizer
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Customizer
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->setup_hooks();
    }

    /**
     * Setup hooks
     *
     * @return void
     */
    private function setup_hooks() {
        add_action( 'customize_register', [ $this, 'register_customizer' ] );
        add_action( 'customize_preview_init', [ $this, 'customize_preview_js' ] );
        add_action( 'wp_head', [ $this, 'output_customizer_css' ] );
    }

    /**
     * Register customizer settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function register_customizer( $wp_customize ) {
        // Add panels
        $this->add_panels( $wp_customize );
        
        // Add sections
        $this->add_sections( $wp_customize );
        
        // Add settings and controls
        $this->add_general_settings( $wp_customize );
        $this->add_header_settings( $wp_customize );
        $this->add_footer_settings( $wp_customize );
        $this->add_typography_settings( $wp_customize );
        $this->add_colors_settings( $wp_customize );
        $this->add_layout_settings( $wp_customize );
        $this->add_blog_settings( $wp_customize );
        $this->add_social_settings( $wp_customize );
        $this->add_contact_settings( $wp_customize );
        $this->add_woocommerce_settings( $wp_customize );
        $this->add_advanced_settings( $wp_customize );
    }

    /**
     * Add panels
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_panels( $wp_customize ) {
        // General Panel
        $wp_customize->add_panel(
            'aqualuxe_general_panel',
            [
                'title'       => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
                'description' => __( 'Customize your theme settings', 'aqualuxe' ),
                'priority'    => 10,
            ]
        );
    }

    /**
     * Add sections
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_sections( $wp_customize ) {
        // General Section
        $wp_customize->add_section(
            'aqualuxe_general_section',
            [
                'title'       => __( 'General Settings', 'aqualuxe' ),
                'description' => __( 'General theme settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 10,
            ]
        );
        
        // Header Section
        $wp_customize->add_section(
            'aqualuxe_header_section',
            [
                'title'       => __( 'Header Settings', 'aqualuxe' ),
                'description' => __( 'Customize the header', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 20,
            ]
        );
        
        // Footer Section
        $wp_customize->add_section(
            'aqualuxe_footer_section',
            [
                'title'       => __( 'Footer Settings', 'aqualuxe' ),
                'description' => __( 'Customize the footer', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 30,
            ]
        );
        
        // Typography Section
        $wp_customize->add_section(
            'aqualuxe_typography_section',
            [
                'title'       => __( 'Typography Settings', 'aqualuxe' ),
                'description' => __( 'Customize the typography', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 40,
            ]
        );
        
        // Colors Section
        $wp_customize->add_section(
            'aqualuxe_colors_section',
            [
                'title'       => __( 'Colors Settings', 'aqualuxe' ),
                'description' => __( 'Customize the colors', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 50,
            ]
        );
        
        // Layout Section
        $wp_customize->add_section(
            'aqualuxe_layout_section',
            [
                'title'       => __( 'Layout Settings', 'aqualuxe' ),
                'description' => __( 'Customize the layout', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 60,
            ]
        );
        
        // Blog Section
        $wp_customize->add_section(
            'aqualuxe_blog_section',
            [
                'title'       => __( 'Blog Settings', 'aqualuxe' ),
                'description' => __( 'Customize the blog', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 70,
            ]
        );
        
        // Social Section
        $wp_customize->add_section(
            'aqualuxe_social_section',
            [
                'title'       => __( 'Social Settings', 'aqualuxe' ),
                'description' => __( 'Customize the social links', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 80,
            ]
        );
        
        // Contact Section
        $wp_customize->add_section(
            'aqualuxe_contact_section',
            [
                'title'       => __( 'Contact Settings', 'aqualuxe' ),
                'description' => __( 'Customize the contact information', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 90,
            ]
        );
        
        // WooCommerce Section
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_section(
                'aqualuxe_woocommerce_section',
                [
                    'title'       => __( 'WooCommerce Settings', 'aqualuxe' ),
                    'description' => __( 'Customize the WooCommerce settings', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_general_panel',
                    'priority'    => 100,
                ]
            );
        }
        
        // Advanced Section
        $wp_customize->add_section(
            'aqualuxe_advanced_section',
            [
                'title'       => __( 'Advanced Settings', 'aqualuxe' ),
                'description' => __( 'Advanced theme settings', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 110,
            ]
        );
    }

    /**
     * Add general settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_general_settings( $wp_customize ) {
        // Site Logo
        $wp_customize->add_setting(
            'aqualuxe_logo',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_logo',
                [
                    'label'       => __( 'Site Logo', 'aqualuxe' ),
                    'description' => __( 'Upload your logo', 'aqualuxe' ),
                    'section'     => 'aqualuxe_general_section',
                    'settings'    => 'aqualuxe_logo',
                    'priority'    => 10,
                ]
            )
        );
        
        // Favicon
        $wp_customize->add_setting(
            'aqualuxe_favicon',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_favicon',
                [
                    'label'       => __( 'Favicon', 'aqualuxe' ),
                    'description' => __( 'Upload your favicon', 'aqualuxe' ),
                    'section'     => 'aqualuxe_general_section',
                    'settings'    => 'aqualuxe_favicon',
                    'priority'    => 20,
                ]
            )
        );
        
        // Dark Mode
        $wp_customize->add_setting(
            'aqualuxe_dark_mode',
            [
                'default'           => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_dark_mode',
            [
                'label'       => __( 'Enable Dark Mode', 'aqualuxe' ),
                'description' => __( 'Enable dark mode by default', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_section',
                'settings'    => 'aqualuxe_dark_mode',
                'type'        => 'checkbox',
                'priority'    => 30,
            ]
        );
        
        // Dark Mode Toggle
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_toggle',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_dark_mode_toggle',
            [
                'label'       => __( 'Show Dark Mode Toggle', 'aqualuxe' ),
                'description' => __( 'Show dark mode toggle button', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_section',
                'settings'    => 'aqualuxe_dark_mode_toggle',
                'type'        => 'checkbox',
                'priority'    => 40,
            ]
        );
        
        // Preloader
        $wp_customize->add_setting(
            'aqualuxe_preloader',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_preloader',
            [
                'label'       => __( 'Enable Preloader', 'aqualuxe' ),
                'description' => __( 'Show preloader animation', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_section',
                'settings'    => 'aqualuxe_preloader',
                'type'        => 'checkbox',
                'priority'    => 50,
            ]
        );
        
        // Back to Top
        $wp_customize->add_setting(
            'aqualuxe_back_to_top',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_back_to_top',
            [
                'label'       => __( 'Enable Back to Top', 'aqualuxe' ),
                'description' => __( 'Show back to top button', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_section',
                'settings'    => 'aqualuxe_back_to_top',
                'type'        => 'checkbox',
                'priority'    => 60,
            ]
        );
        
        // Smooth Scroll
        $wp_customize->add_setting(
            'aqualuxe_smooth_scroll',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_smooth_scroll',
            [
                'label'       => __( 'Enable Smooth Scroll', 'aqualuxe' ),
                'description' => __( 'Enable smooth scrolling', 'aqualuxe' ),
                'section'     => 'aqualuxe_general_section',
                'settings'    => 'aqualuxe_smooth_scroll',
                'type'        => 'checkbox',
                'priority'    => 70,
            ]
        );
    }

    /**
     * Add header settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_header_settings( $wp_customize ) {
        // Header Layout
        $wp_customize->add_setting(
            'aqualuxe_header_layout',
            [
                'default'           => 'default',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_header_layout',
            [
                'label'       => __( 'Header Layout', 'aqualuxe' ),
                'description' => __( 'Select the header layout', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_header_layout',
                'type'        => 'select',
                'choices'     => [
                    'default'      => __( 'Default', 'aqualuxe' ),
                    'centered'     => __( 'Centered', 'aqualuxe' ),
                    'transparent'  => __( 'Transparent', 'aqualuxe' ),
                    'split'        => __( 'Split', 'aqualuxe' ),
                    'minimal'      => __( 'Minimal', 'aqualuxe' ),
                ],
                'priority'    => 10,
            ]
        );
        
        // Sticky Header
        $wp_customize->add_setting(
            'aqualuxe_sticky_header',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_sticky_header',
            [
                'label'       => __( 'Sticky Header', 'aqualuxe' ),
                'description' => __( 'Enable sticky header', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_sticky_header',
                'type'        => 'checkbox',
                'priority'    => 20,
            ]
        );
        
        // Header Search
        $wp_customize->add_setting(
            'aqualuxe_header_search',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_header_search',
            [
                'label'       => __( 'Header Search', 'aqualuxe' ),
                'description' => __( 'Show search in header', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_header_search',
                'type'        => 'checkbox',
                'priority'    => 30,
            ]
        );
        
        // Header Cart
        $wp_customize->add_setting(
            'aqualuxe_header_cart',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_header_cart',
            [
                'label'       => __( 'Header Cart', 'aqualuxe' ),
                'description' => __( 'Show cart in header', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_header_cart',
                'type'        => 'checkbox',
                'priority'    => 40,
            ]
        );
        
        // Header Account
        $wp_customize->add_setting(
            'aqualuxe_header_account',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_header_account',
            [
                'label'       => __( 'Header Account', 'aqualuxe' ),
                'description' => __( 'Show account in header', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_header_account',
                'type'        => 'checkbox',
                'priority'    => 50,
            ]
        );
        
        // Header Wishlist
        $wp_customize->add_setting(
            'aqualuxe_header_wishlist',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_header_wishlist',
            [
                'label'       => __( 'Header Wishlist', 'aqualuxe' ),
                'description' => __( 'Show wishlist in header', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_header_wishlist',
                'type'        => 'checkbox',
                'priority'    => 60,
            ]
        );
        
        // Header Social Icons
        $wp_customize->add_setting(
            'aqualuxe_header_social',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_header_social',
            [
                'label'       => __( 'Header Social Icons', 'aqualuxe' ),
                'description' => __( 'Show social icons in header', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_header_social',
                'type'        => 'checkbox',
                'priority'    => 70,
            ]
        );
        
        // Header Contact Info
        $wp_customize->add_setting(
            'aqualuxe_header_contact',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_header_contact',
            [
                'label'       => __( 'Header Contact Info', 'aqualuxe' ),
                'description' => __( 'Show contact info in header', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_header_contact',
                'type'        => 'checkbox',
                'priority'    => 80,
            ]
        );
        
        // Header Top Bar
        $wp_customize->add_setting(
            'aqualuxe_header_top_bar',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_header_top_bar',
            [
                'label'       => __( 'Header Top Bar', 'aqualuxe' ),
                'description' => __( 'Show top bar in header', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_header_top_bar',
                'type'        => 'checkbox',
                'priority'    => 90,
            ]
        );
        
        // Header Top Bar Text
        $wp_customize->add_setting(
            'aqualuxe_header_top_bar_text',
            [
                'default'           => __( 'Welcome to AquaLuxe', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_header_top_bar_text',
            [
                'label'       => __( 'Top Bar Text', 'aqualuxe' ),
                'description' => __( 'Text to display in the top bar', 'aqualuxe' ),
                'section'     => 'aqualuxe_header_section',
                'settings'    => 'aqualuxe_header_top_bar_text',
                'type'        => 'text',
                'priority'    => 100,
            ]
        );
    }

    /**
     * Add footer settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_footer_settings( $wp_customize ) {
        // Footer Layout
        $wp_customize->add_setting(
            'aqualuxe_footer_layout',
            [
                'default'           => 'default',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_layout',
            [
                'label'       => __( 'Footer Layout', 'aqualuxe' ),
                'description' => __( 'Select the footer layout', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_section',
                'settings'    => 'aqualuxe_footer_layout',
                'type'        => 'select',
                'choices'     => [
                    'default'      => __( 'Default', 'aqualuxe' ),
                    'centered'     => __( 'Centered', 'aqualuxe' ),
                    'minimal'      => __( 'Minimal', 'aqualuxe' ),
                    'expanded'     => __( 'Expanded', 'aqualuxe' ),
                ],
                'priority'    => 10,
            ]
        );
        
        // Footer Columns
        $wp_customize->add_setting(
            'aqualuxe_footer_columns',
            [
                'default'           => '4',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_columns',
            [
                'label'       => __( 'Footer Columns', 'aqualuxe' ),
                'description' => __( 'Select the number of footer columns', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_section',
                'settings'    => 'aqualuxe_footer_columns',
                'type'        => 'select',
                'choices'     => [
                    '1' => __( '1 Column', 'aqualuxe' ),
                    '2' => __( '2 Columns', 'aqualuxe' ),
                    '3' => __( '3 Columns', 'aqualuxe' ),
                    '4' => __( '4 Columns', 'aqualuxe' ),
                ],
                'priority'    => 20,
            ]
        );
        
        // Footer Logo
        $wp_customize->add_setting(
            'aqualuxe_footer_logo',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_footer_logo',
                [
                    'label'       => __( 'Footer Logo', 'aqualuxe' ),
                    'description' => __( 'Upload your footer logo', 'aqualuxe' ),
                    'section'     => 'aqualuxe_footer_section',
                    'settings'    => 'aqualuxe_footer_logo',
                    'priority'    => 30,
                ]
            )
        );
        
        // Footer About Text
        $wp_customize->add_setting(
            'aqualuxe_footer_about',
            [
                'default'           => __( 'AquaLuxe is a premium aquatic business specializing in rare fish, aquatic plants, and custom aquariums.', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_textarea_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_about',
            [
                'label'       => __( 'Footer About Text', 'aqualuxe' ),
                'description' => __( 'Text to display in the footer about section', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_section',
                'settings'    => 'aqualuxe_footer_about',
                'type'        => 'textarea',
                'priority'    => 40,
            ]
        );
        
        // Footer Social Icons
        $wp_customize->add_setting(
            'aqualuxe_footer_social',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_social',
            [
                'label'       => __( 'Footer Social Icons', 'aqualuxe' ),
                'description' => __( 'Show social icons in footer', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_section',
                'settings'    => 'aqualuxe_footer_social',
                'type'        => 'checkbox',
                'priority'    => 50,
            ]
        );
        
        // Footer Contact Info
        $wp_customize->add_setting(
            'aqualuxe_footer_contact',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_contact',
            [
                'label'       => __( 'Footer Contact Info', 'aqualuxe' ),
                'description' => __( 'Show contact info in footer', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_section',
                'settings'    => 'aqualuxe_footer_contact',
                'type'        => 'checkbox',
                'priority'    => 60,
            ]
        );
        
        // Footer Newsletter
        $wp_customize->add_setting(
            'aqualuxe_footer_newsletter',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_newsletter',
            [
                'label'       => __( 'Footer Newsletter', 'aqualuxe' ),
                'description' => __( 'Show newsletter form in footer', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_section',
                'settings'    => 'aqualuxe_footer_newsletter',
                'type'        => 'checkbox',
                'priority'    => 70,
            ]
        );
        
        // Footer Newsletter Text
        $wp_customize->add_setting(
            'aqualuxe_footer_newsletter_text',
            [
                'default'           => __( 'Subscribe to our newsletter to get the latest updates', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_newsletter_text',
            [
                'label'       => __( 'Newsletter Text', 'aqualuxe' ),
                'description' => __( 'Text to display above the newsletter form', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_section',
                'settings'    => 'aqualuxe_footer_newsletter_text',
                'type'        => 'text',
                'priority'    => 80,
            ]
        );
        
        // Footer Copyright
        $wp_customize->add_setting(
            'aqualuxe_copyright',
            [
                'default'           => sprintf(
                    /* translators: %1$s: Current year, %2$s: Site name */
                    __( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ),
                    date( 'Y' ),
                    get_bloginfo( 'name' )
                ),
                'sanitize_callback' => 'wp_kses_post',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_copyright',
            [
                'label'       => __( 'Copyright Text', 'aqualuxe' ),
                'description' => __( 'Text to display in the copyright area', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_section',
                'settings'    => 'aqualuxe_copyright',
                'type'        => 'textarea',
                'priority'    => 90,
            ]
        );
        
        // Footer Text
        $wp_customize->add_setting(
            'aqualuxe_footer_text',
            [
                'default'           => sprintf(
                    /* translators: %1$s: Theme name, %2$s: Theme author */
                    __( 'Powered by %1$s theme by %2$s.', 'aqualuxe' ),
                    'AquaLuxe',
                    '<a href="https://ninjatech.ai" target="_blank" rel="noopener noreferrer">NinjaTech AI</a>'
                ),
                'sanitize_callback' => 'wp_kses_post',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_text',
            [
                'label'       => __( 'Footer Text', 'aqualuxe' ),
                'description' => __( 'Text to display in the footer', 'aqualuxe' ),
                'section'     => 'aqualuxe_footer_section',
                'settings'    => 'aqualuxe_footer_text',
                'type'        => 'textarea',
                'priority'    => 100,
            ]
        );
    }

    /**
     * Add typography settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_typography_settings( $wp_customize ) {
        // Body Font
        $wp_customize->add_setting(
            'aqualuxe_font_body',
            [
                'default'           => 'Inter',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_font_body',
            [
                'label'       => __( 'Body Font', 'aqualuxe' ),
                'description' => __( 'Select the body font', 'aqualuxe' ),
                'section'     => 'aqualuxe_typography_section',
                'settings'    => 'aqualuxe_font_body',
                'type'        => 'select',
                'choices'     => [
                    'Inter'       => 'Inter',
                    'Roboto'      => 'Roboto',
                    'Open Sans'   => 'Open Sans',
                    'Lato'        => 'Lato',
                    'Montserrat'  => 'Montserrat',
                    'Poppins'     => 'Poppins',
                    'Raleway'     => 'Raleway',
                    'Nunito'      => 'Nunito',
                    'Playfair Display' => 'Playfair Display',
                    'Merriweather' => 'Merriweather',
                ],
                'priority'    => 10,
            ]
        );
        
        // Heading Font
        $wp_customize->add_setting(
            'aqualuxe_font_heading',
            [
                'default'           => 'Playfair Display',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_font_heading',
            [
                'label'       => __( 'Heading Font', 'aqualuxe' ),
                'description' => __( 'Select the heading font', 'aqualuxe' ),
                'section'     => 'aqualuxe_typography_section',
                'settings'    => 'aqualuxe_font_heading',
                'type'        => 'select',
                'choices'     => [
                    'Inter'       => 'Inter',
                    'Roboto'      => 'Roboto',
                    'Open Sans'   => 'Open Sans',
                    'Lato'        => 'Lato',
                    'Montserrat'  => 'Montserrat',
                    'Poppins'     => 'Poppins',
                    'Raleway'     => 'Raleway',
                    'Nunito'      => 'Nunito',
                    'Playfair Display' => 'Playfair Display',
                    'Merriweather' => 'Merriweather',
                ],
                'priority'    => 20,
            ]
        );
        
        // Base Font Size
        $wp_customize->add_setting(
            'aqualuxe_font_size_base',
            [
                'default'           => '16',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_font_size_base',
            [
                'label'       => __( 'Base Font Size (px)', 'aqualuxe' ),
                'description' => __( 'Set the base font size', 'aqualuxe' ),
                'section'     => 'aqualuxe_typography_section',
                'settings'    => 'aqualuxe_font_size_base',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 12,
                    'max'  => 24,
                    'step' => 1,
                ],
                'priority'    => 30,
            ]
        );
        
        // Heading Scale
        $wp_customize->add_setting(
            'aqualuxe_heading_scale',
            [
                'default'           => '1.2',
                'sanitize_callback' => 'aqualuxe_sanitize_float',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_heading_scale',
            [
                'label'       => __( 'Heading Scale', 'aqualuxe' ),
                'description' => __( 'Set the heading scale ratio', 'aqualuxe' ),
                'section'     => 'aqualuxe_typography_section',
                'settings'    => 'aqualuxe_heading_scale',
                'type'        => 'select',
                'choices'     => [
                    '1.1' => '1.1 (Minor Second)',
                    '1.125' => '1.125 (Major Second)',
                    '1.2' => '1.2 (Minor Third)',
                    '1.25' => '1.25 (Major Third)',
                    '1.333' => '1.333 (Perfect Fourth)',
                    '1.414' => '1.414 (Augmented Fourth)',
                    '1.5' => '1.5 (Perfect Fifth)',
                    '1.618' => '1.618 (Golden Ratio)',
                ],
                'priority'    => 40,
            ]
        );
        
        // Line Height
        $wp_customize->add_setting(
            'aqualuxe_line_height',
            [
                'default'           => '1.6',
                'sanitize_callback' => 'aqualuxe_sanitize_float',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_line_height',
            [
                'label'       => __( 'Line Height', 'aqualuxe' ),
                'description' => __( 'Set the base line height', 'aqualuxe' ),
                'section'     => 'aqualuxe_typography_section',
                'settings'    => 'aqualuxe_line_height',
                'type'        => 'select',
                'choices'     => [
                    '1.2' => '1.2 (Tight)',
                    '1.4' => '1.4 (Compact)',
                    '1.6' => '1.6 (Normal)',
                    '1.8' => '1.8 (Relaxed)',
                    '2.0' => '2.0 (Loose)',
                ],
                'priority'    => 50,
            ]
        );
        
        // Font Weight
        $wp_customize->add_setting(
            'aqualuxe_font_weight',
            [
                'default'           => '400',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_font_weight',
            [
                'label'       => __( 'Base Font Weight', 'aqualuxe' ),
                'description' => __( 'Set the base font weight', 'aqualuxe' ),
                'section'     => 'aqualuxe_typography_section',
                'settings'    => 'aqualuxe_font_weight',
                'type'        => 'select',
                'choices'     => [
                    '300' => '300 (Light)',
                    '400' => '400 (Regular)',
                    '500' => '500 (Medium)',
                    '600' => '600 (Semi-Bold)',
                    '700' => '700 (Bold)',
                ],
                'priority'    => 60,
            ]
        );
        
        // Heading Font Weight
        $wp_customize->add_setting(
            'aqualuxe_heading_font_weight',
            [
                'default'           => '700',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_heading_font_weight',
            [
                'label'       => __( 'Heading Font Weight', 'aqualuxe' ),
                'description' => __( 'Set the heading font weight', 'aqualuxe' ),
                'section'     => 'aqualuxe_typography_section',
                'settings'    => 'aqualuxe_heading_font_weight',
                'type'        => 'select',
                'choices'     => [
                    '400' => '400 (Regular)',
                    '500' => '500 (Medium)',
                    '600' => '600 (Semi-Bold)',
                    '700' => '700 (Bold)',
                    '800' => '800 (Extra Bold)',
                ],
                'priority'    => 70,
            ]
        );
    }

    /**
     * Add colors settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_colors_settings( $wp_customize ) {
        // Primary Color
        $wp_customize->add_setting(
            'aqualuxe_color_primary',
            [
                'default'           => '#0073aa',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_color_primary',
                [
                    'label'       => __( 'Primary Color', 'aqualuxe' ),
                    'description' => __( 'Set the primary color', 'aqualuxe' ),
                    'section'     => 'aqualuxe_colors_section',
                    'settings'    => 'aqualuxe_color_primary',
                    'priority'    => 10,
                ]
            )
        );
        
        // Secondary Color
        $wp_customize->add_setting(
            'aqualuxe_color_secondary',
            [
                'default'           => '#23282d',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_color_secondary',
                [
                    'label'       => __( 'Secondary Color', 'aqualuxe' ),
                    'description' => __( 'Set the secondary color', 'aqualuxe' ),
                    'section'     => 'aqualuxe_colors_section',
                    'settings'    => 'aqualuxe_color_secondary',
                    'priority'    => 20,
                ]
            )
        );
        
        // Accent Color
        $wp_customize->add_setting(
            'aqualuxe_color_accent',
            [
                'default'           => '#00a0d2',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_color_accent',
                [
                    'label'       => __( 'Accent Color', 'aqualuxe' ),
                    'description' => __( 'Set the accent color', 'aqualuxe' ),
                    'section'     => 'aqualuxe_colors_section',
                    'settings'    => 'aqualuxe_color_accent',
                    'priority'    => 30,
                ]
            )
        );
        
        // Text Color
        $wp_customize->add_setting(
            'aqualuxe_color_text',
            [
                'default'           => '#333333',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_color_text',
                [
                    'label'       => __( 'Text Color', 'aqualuxe' ),
                    'description' => __( 'Set the text color', 'aqualuxe' ),
                    'section'     => 'aqualuxe_colors_section',
                    'settings'    => 'aqualuxe_color_text',
                    'priority'    => 40,
                ]
            )
        );
        
        // Heading Color
        $wp_customize->add_setting(
            'aqualuxe_color_heading',
            [
                'default'           => '#222222',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_color_heading',
                [
                    'label'       => __( 'Heading Color', 'aqualuxe' ),
                    'description' => __( 'Set the heading color', 'aqualuxe' ),
                    'section'     => 'aqualuxe_colors_section',
                    'settings'    => 'aqualuxe_color_heading',
                    'priority'    => 50,
                ]
            )
        );
        
        // Background Color
        $wp_customize->add_setting(
            'aqualuxe_color_background',
            [
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_color_background',
                [
                    'label'       => __( 'Background Color', 'aqualuxe' ),
                    'description' => __( 'Set the background color', 'aqualuxe' ),
                    'section'     => 'aqualuxe_colors_section',
                    'settings'    => 'aqualuxe_color_background',
                    'priority'    => 60,
                ]
            )
        );
        
        // Dark Mode Text Color
        $wp_customize->add_setting(
            'aqualuxe_color_dark_text',
            [
                'default'           => '#e0e0e0',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_color_dark_text',
                [
                    'label'       => __( 'Dark Mode Text Color', 'aqualuxe' ),
                    'description' => __( 'Set the text color for dark mode', 'aqualuxe' ),
                    'section'     => 'aqualuxe_colors_section',
                    'settings'    => 'aqualuxe_color_dark_text',
                    'priority'    => 70,
                ]
            )
        );
        
        // Dark Mode Heading Color
        $wp_customize->add_setting(
            'aqualuxe_color_dark_heading',
            [
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_color_dark_heading',
                [
                    'label'       => __( 'Dark Mode Heading Color', 'aqualuxe' ),
                    'description' => __( 'Set the heading color for dark mode', 'aqualuxe' ),
                    'section'     => 'aqualuxe_colors_section',
                    'settings'    => 'aqualuxe_color_dark_heading',
                    'priority'    => 80,
                ]
            )
        );
        
        // Dark Mode Background Color
        $wp_customize->add_setting(
            'aqualuxe_color_dark_background',
            [
                'default'           => '#121212',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_color_dark_background',
                [
                    'label'       => __( 'Dark Mode Background Color', 'aqualuxe' ),
                    'description' => __( 'Set the background color for dark mode', 'aqualuxe' ),
                    'section'     => 'aqualuxe_colors_section',
                    'settings'    => 'aqualuxe_color_dark_background',
                    'priority'    => 90,
                ]
            )
        );
    }

    /**
     * Add layout settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_layout_settings( $wp_customize ) {
        // Container Width
        $wp_customize->add_setting(
            'aqualuxe_container_width',
            [
                'default'           => '1200',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_container_width',
            [
                'label'       => __( 'Container Width (px)', 'aqualuxe' ),
                'description' => __( 'Set the container width', 'aqualuxe' ),
                'section'     => 'aqualuxe_layout_section',
                'settings'    => 'aqualuxe_container_width',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 960,
                    'max'  => 1600,
                    'step' => 10,
                ],
                'priority'    => 10,
            ]
        );
        
        // Content Layout
        $wp_customize->add_setting(
            'aqualuxe_layout',
            [
                'default'           => 'right-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_layout',
            [
                'label'       => __( 'Content Layout', 'aqualuxe' ),
                'description' => __( 'Select the content layout', 'aqualuxe' ),
                'section'     => 'aqualuxe_layout_section',
                'settings'    => 'aqualuxe_layout',
                'type'        => 'select',
                'choices'     => [
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'full-width'    => __( 'Full Width', 'aqualuxe' ),
                ],
                'priority'    => 20,
            ]
        );
        
        // Sidebar Width
        $wp_customize->add_setting(
            'aqualuxe_sidebar_width',
            [
                'default'           => '30',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_sidebar_width',
            [
                'label'       => __( 'Sidebar Width (%)', 'aqualuxe' ),
                'description' => __( 'Set the sidebar width', 'aqualuxe' ),
                'section'     => 'aqualuxe_layout_section',
                'settings'    => 'aqualuxe_sidebar_width',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 20,
                    'max'  => 40,
                    'step' => 1,
                ],
                'priority'    => 30,
            ]
        );
        
        // Shop Layout
        $wp_customize->add_setting(
            'aqualuxe_shop_layout',
            [
                'default'           => 'left-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_shop_layout',
            [
                'label'       => __( 'Shop Layout', 'aqualuxe' ),
                'description' => __( 'Select the shop layout', 'aqualuxe' ),
                'section'     => 'aqualuxe_layout_section',
                'settings'    => 'aqualuxe_shop_layout',
                'type'        => 'select',
                'choices'     => [
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'full-width'    => __( 'Full Width', 'aqualuxe' ),
                ],
                'priority'    => 40,
            ]
        );
        
        // Product Layout
        $wp_customize->add_setting(
            'aqualuxe_product_layout',
            [
                'default'           => 'full-width',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_product_layout',
            [
                'label'       => __( 'Product Layout', 'aqualuxe' ),
                'description' => __( 'Select the product layout', 'aqualuxe' ),
                'section'     => 'aqualuxe_layout_section',
                'settings'    => 'aqualuxe_product_layout',
                'type'        => 'select',
                'choices'     => [
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'full-width'    => __( 'Full Width', 'aqualuxe' ),
                ],
                'priority'    => 50,
            ]
        );
        
        // Page Layout
        $wp_customize->add_setting(
            'aqualuxe_page_layout',
            [
                'default'           => 'full-width',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_page_layout',
            [
                'label'       => __( 'Page Layout', 'aqualuxe' ),
                'description' => __( 'Select the page layout', 'aqualuxe' ),
                'section'     => 'aqualuxe_layout_section',
                'settings'    => 'aqualuxe_page_layout',
                'type'        => 'select',
                'choices'     => [
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'full-width'    => __( 'Full Width', 'aqualuxe' ),
                ],
                'priority'    => 60,
            ]
        );
        
        // Post Layout
        $wp_customize->add_setting(
            'aqualuxe_post_layout',
            [
                'default'           => 'right-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_post_layout',
            [
                'label'       => __( 'Post Layout', 'aqualuxe' ),
                'description' => __( 'Select the post layout', 'aqualuxe' ),
                'section'     => 'aqualuxe_layout_section',
                'settings'    => 'aqualuxe_post_layout',
                'type'        => 'select',
                'choices'     => [
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'full-width'    => __( 'Full Width', 'aqualuxe' ),
                ],
                'priority'    => 70,
            ]
        );
        
        // Archive Layout
        $wp_customize->add_setting(
            'aqualuxe_archive_layout',
            [
                'default'           => 'right-sidebar',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_archive_layout',
            [
                'label'       => __( 'Archive Layout', 'aqualuxe' ),
                'description' => __( 'Select the archive layout', 'aqualuxe' ),
                'section'     => 'aqualuxe_layout_section',
                'settings'    => 'aqualuxe_archive_layout',
                'type'        => 'select',
                'choices'     => [
                    'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'aqualuxe' ),
                    'full-width'    => __( 'Full Width', 'aqualuxe' ),
                ],
                'priority'    => 80,
            ]
        );
    }

    /**
     * Add blog settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_blog_settings( $wp_customize ) {
        // Blog Layout
        $wp_customize->add_setting(
            'aqualuxe_blog_layout',
            [
                'default'           => 'grid',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_layout',
            [
                'label'       => __( 'Blog Layout', 'aqualuxe' ),
                'description' => __( 'Select the blog layout', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_blog_layout',
                'type'        => 'select',
                'choices'     => [
                    'grid'     => __( 'Grid', 'aqualuxe' ),
                    'list'     => __( 'List', 'aqualuxe' ),
                    'masonry'  => __( 'Masonry', 'aqualuxe' ),
                    'standard' => __( 'Standard', 'aqualuxe' ),
                ],
                'priority'    => 10,
            ]
        );
        
        // Blog Columns
        $wp_customize->add_setting(
            'aqualuxe_blog_columns',
            [
                'default'           => '3',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_columns',
            [
                'label'       => __( 'Blog Columns', 'aqualuxe' ),
                'description' => __( 'Select the number of columns for grid and masonry layouts', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_blog_columns',
                'type'        => 'select',
                'choices'     => [
                    '2' => __( '2 Columns', 'aqualuxe' ),
                    '3' => __( '3 Columns', 'aqualuxe' ),
                    '4' => __( '4 Columns', 'aqualuxe' ),
                ],
                'priority'    => 20,
            ]
        );
        
        // Excerpt Length
        $wp_customize->add_setting(
            'aqualuxe_excerpt_length',
            [
                'default'           => '55',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_excerpt_length',
            [
                'label'       => __( 'Excerpt Length', 'aqualuxe' ),
                'description' => __( 'Set the excerpt length', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_excerpt_length',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 10,
                    'max'  => 200,
                    'step' => 5,
                ],
                'priority'    => 30,
            ]
        );
        
        // Read More Text
        $wp_customize->add_setting(
            'aqualuxe_read_more_text',
            [
                'default'           => __( 'Read More', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_read_more_text',
            [
                'label'       => __( 'Read More Text', 'aqualuxe' ),
                'description' => __( 'Set the read more text', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_read_more_text',
                'type'        => 'text',
                'priority'    => 40,
            ]
        );
        
        // Show Featured Image
        $wp_customize->add_setting(
            'aqualuxe_show_featured_image',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_featured_image',
            [
                'label'       => __( 'Show Featured Image', 'aqualuxe' ),
                'description' => __( 'Show featured image in blog posts', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_show_featured_image',
                'type'        => 'checkbox',
                'priority'    => 50,
            ]
        );
        
        // Show Post Meta
        $wp_customize->add_setting(
            'aqualuxe_show_post_meta',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_post_meta',
            [
                'label'       => __( 'Show Post Meta', 'aqualuxe' ),
                'description' => __( 'Show post meta information (author, date, etc.)', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_show_post_meta',
                'type'        => 'checkbox',
                'priority'    => 60,
            ]
        );
        
        // Show Author
        $wp_customize->add_setting(
            'aqualuxe_show_author',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_author',
            [
                'label'       => __( 'Show Author', 'aqualuxe' ),
                'description' => __( 'Show post author', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_show_author',
                'type'        => 'checkbox',
                'priority'    => 70,
            ]
        );
        
        // Show Date
        $wp_customize->add_setting(
            'aqualuxe_show_date',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_date',
            [
                'label'       => __( 'Show Date', 'aqualuxe' ),
                'description' => __( 'Show post date', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_show_date',
                'type'        => 'checkbox',
                'priority'    => 80,
            ]
        );
        
        // Show Categories
        $wp_customize->add_setting(
            'aqualuxe_show_categories',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_categories',
            [
                'label'       => __( 'Show Categories', 'aqualuxe' ),
                'description' => __( 'Show post categories', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_show_categories',
                'type'        => 'checkbox',
                'priority'    => 90,
            ]
        );
        
        // Show Tags
        $wp_customize->add_setting(
            'aqualuxe_show_tags',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_tags',
            [
                'label'       => __( 'Show Tags', 'aqualuxe' ),
                'description' => __( 'Show post tags', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_show_tags',
                'type'        => 'checkbox',
                'priority'    => 100,
            ]
        );
        
        // Show Comments Count
        $wp_customize->add_setting(
            'aqualuxe_show_comments_count',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_comments_count',
            [
                'label'       => __( 'Show Comments Count', 'aqualuxe' ),
                'description' => __( 'Show post comments count', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_show_comments_count',
                'type'        => 'checkbox',
                'priority'    => 110,
            ]
        );
        
        // Show Social Share
        $wp_customize->add_setting(
            'aqualuxe_show_social_share',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_social_share',
            [
                'label'       => __( 'Show Social Share', 'aqualuxe' ),
                'description' => __( 'Show social share buttons', 'aqualuxe' ),
                'section'     => 'aqualuxe_blog_section',
                'settings'    => 'aqualuxe_show_social_share',
                'type'        => 'checkbox',
                'priority'    => 120,
            ]
        );
    }

    /**
     * Add social settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_social_settings( $wp_customize ) {
        // Facebook
        $wp_customize->add_setting(
            'aqualuxe_social_facebook',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_social_facebook',
            [
                'label'       => __( 'Facebook', 'aqualuxe' ),
                'description' => __( 'Enter your Facebook URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_social_section',
                'settings'    => 'aqualuxe_social_facebook',
                'type'        => 'url',
                'priority'    => 10,
            ]
        );
        
        // Twitter
        $wp_customize->add_setting(
            'aqualuxe_social_twitter',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_social_twitter',
            [
                'label'       => __( 'Twitter', 'aqualuxe' ),
                'description' => __( 'Enter your Twitter URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_social_section',
                'settings'    => 'aqualuxe_social_twitter',
                'type'        => 'url',
                'priority'    => 20,
            ]
        );
        
        // Instagram
        $wp_customize->add_setting(
            'aqualuxe_social_instagram',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_social_instagram',
            [
                'label'       => __( 'Instagram', 'aqualuxe' ),
                'description' => __( 'Enter your Instagram URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_social_section',
                'settings'    => 'aqualuxe_social_instagram',
                'type'        => 'url',
                'priority'    => 30,
            ]
        );
        
        // LinkedIn
        $wp_customize->add_setting(
            'aqualuxe_social_linkedin',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_social_linkedin',
            [
                'label'       => __( 'LinkedIn', 'aqualuxe' ),
                'description' => __( 'Enter your LinkedIn URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_social_section',
                'settings'    => 'aqualuxe_social_linkedin',
                'type'        => 'url',
                'priority'    => 40,
            ]
        );
        
        // YouTube
        $wp_customize->add_setting(
            'aqualuxe_social_youtube',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_social_youtube',
            [
                'label'       => __( 'YouTube', 'aqualuxe' ),
                'description' => __( 'Enter your YouTube URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_social_section',
                'settings'    => 'aqualuxe_social_youtube',
                'type'        => 'url',
                'priority'    => 50,
            ]
        );
        
        // Pinterest
        $wp_customize->add_setting(
            'aqualuxe_social_pinterest',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_social_pinterest',
            [
                'label'       => __( 'Pinterest', 'aqualuxe' ),
                'description' => __( 'Enter your Pinterest URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_social_section',
                'settings'    => 'aqualuxe_social_pinterest',
                'type'        => 'url',
                'priority'    => 60,
            ]
        );
        
        // TikTok
        $wp_customize->add_setting(
            'aqualuxe_social_tiktok',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_social_tiktok',
            [
                'label'       => __( 'TikTok', 'aqualuxe' ),
                'description' => __( 'Enter your TikTok URL', 'aqualuxe' ),
                'section'     => 'aqualuxe_social_section',
                'settings'    => 'aqualuxe_social_tiktok',
                'type'        => 'url',
                'priority'    => 70,
            ]
        );
        
        // Social Share Networks
        $wp_customize->add_setting(
            'aqualuxe_social_share_networks',
            [
                'default'           => [ 'facebook', 'twitter', 'linkedin', 'pinterest', 'email' ],
                'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Control(
                $wp_customize,
                'aqualuxe_social_share_networks',
                [
                    'label'       => __( 'Social Share Networks', 'aqualuxe' ),
                    'description' => __( 'Select the social networks to display in the share buttons', 'aqualuxe' ),
                    'section'     => 'aqualuxe_social_section',
                    'settings'    => 'aqualuxe_social_share_networks',
                    'type'        => 'select',
                    'multiple'    => true,
                    'choices'     => [
                        'facebook'  => __( 'Facebook', 'aqualuxe' ),
                        'twitter'   => __( 'Twitter', 'aqualuxe' ),
                        'linkedin'  => __( 'LinkedIn', 'aqualuxe' ),
                        'pinterest' => __( 'Pinterest', 'aqualuxe' ),
                        'reddit'    => __( 'Reddit', 'aqualuxe' ),
                        'tumblr'    => __( 'Tumblr', 'aqualuxe' ),
                        'whatsapp'  => __( 'WhatsApp', 'aqualuxe' ),
                        'telegram'  => __( 'Telegram', 'aqualuxe' ),
                        'email'     => __( 'Email', 'aqualuxe' ),
                    ],
                    'priority'    => 80,
                ]
            )
        );
    }

    /**
     * Add contact settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_contact_settings( $wp_customize ) {
        // Phone
        $wp_customize->add_setting(
            'aqualuxe_contact_phone',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_contact_phone',
            [
                'label'       => __( 'Phone', 'aqualuxe' ),
                'description' => __( 'Enter your phone number', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_contact_phone',
                'type'        => 'text',
                'priority'    => 10,
            ]
        );
        
        // Email
        $wp_customize->add_setting(
            'aqualuxe_contact_email',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_email',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_contact_email',
            [
                'label'       => __( 'Email', 'aqualuxe' ),
                'description' => __( 'Enter your email address', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_contact_email',
                'type'        => 'email',
                'priority'    => 20,
            ]
        );
        
        // Address
        $wp_customize->add_setting(
            'aqualuxe_contact_address',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_textarea_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_contact_address',
            [
                'label'       => __( 'Address', 'aqualuxe' ),
                'description' => __( 'Enter your address', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_contact_address',
                'type'        => 'textarea',
                'priority'    => 30,
            ]
        );
        
        // Hours
        $wp_customize->add_setting(
            'aqualuxe_contact_hours',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_textarea_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_contact_hours',
            [
                'label'       => __( 'Hours', 'aqualuxe' ),
                'description' => __( 'Enter your business hours', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_contact_hours',
                'type'        => 'textarea',
                'priority'    => 40,
            ]
        );
        
        // Google Maps API Key
        $wp_customize->add_setting(
            'aqualuxe_google_maps_api_key',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_google_maps_api_key',
            [
                'label'       => __( 'Google Maps API Key', 'aqualuxe' ),
                'description' => __( 'Enter your Google Maps API key', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_google_maps_api_key',
                'type'        => 'text',
                'priority'    => 50,
            ]
        );
        
        // Google Maps Latitude
        $wp_customize->add_setting(
            'aqualuxe_google_maps_latitude',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_google_maps_latitude',
            [
                'label'       => __( 'Google Maps Latitude', 'aqualuxe' ),
                'description' => __( 'Enter your latitude', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_google_maps_latitude',
                'type'        => 'text',
                'priority'    => 60,
            ]
        );
        
        // Google Maps Longitude
        $wp_customize->add_setting(
            'aqualuxe_google_maps_longitude',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_google_maps_longitude',
            [
                'label'       => __( 'Google Maps Longitude', 'aqualuxe' ),
                'description' => __( 'Enter your longitude', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_google_maps_longitude',
                'type'        => 'text',
                'priority'    => 70,
            ]
        );
        
        // Google Maps Zoom
        $wp_customize->add_setting(
            'aqualuxe_google_maps_zoom',
            [
                'default'           => '15',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_google_maps_zoom',
            [
                'label'       => __( 'Google Maps Zoom', 'aqualuxe' ),
                'description' => __( 'Set the map zoom level', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_google_maps_zoom',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 20,
                    'step' => 1,
                ],
                'priority'    => 80,
            ]
        );
        
        // Contact Form 7 ID
        $wp_customize->add_setting(
            'aqualuxe_contact_form_id',
            [
                'default'           => '',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_contact_form_id',
            [
                'label'       => __( 'Contact Form 7 ID', 'aqualuxe' ),
                'description' => __( 'Enter your Contact Form 7 form ID', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_contact_form_id',
                'type'        => 'number',
                'priority'    => 90,
            ]
        );
        
        // reCAPTCHA Site Key
        $wp_customize->add_setting(
            'aqualuxe_recaptcha_site_key',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_recaptcha_site_key',
            [
                'label'       => __( 'reCAPTCHA Site Key', 'aqualuxe' ),
                'description' => __( 'Enter your reCAPTCHA site key', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_recaptcha_site_key',
                'type'        => 'text',
                'priority'    => 100,
            ]
        );
        
        // reCAPTCHA Secret Key
        $wp_customize->add_setting(
            'aqualuxe_recaptcha_secret_key',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_recaptcha_secret_key',
            [
                'label'       => __( 'reCAPTCHA Secret Key', 'aqualuxe' ),
                'description' => __( 'Enter your reCAPTCHA secret key', 'aqualuxe' ),
                'section'     => 'aqualuxe_contact_section',
                'settings'    => 'aqualuxe_recaptcha_secret_key',
                'type'        => 'text',
                'priority'    => 110,
            ]
        );
    }

    /**
     * Add WooCommerce settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_woocommerce_settings( $wp_customize ) {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        // Products Per Page
        $wp_customize->add_setting(
            'aqualuxe_products_per_page',
            [
                'default'           => '12',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_products_per_page',
            [
                'label'       => __( 'Products Per Page', 'aqualuxe' ),
                'description' => __( 'Set the number of products per page', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_products_per_page',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 4,
                    'max'  => 48,
                    'step' => 4,
                ],
                'priority'    => 10,
            ]
        );
        
        // Product Columns
        $wp_customize->add_setting(
            'aqualuxe_product_columns',
            [
                'default'           => '4',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_product_columns',
            [
                'label'       => __( 'Product Columns', 'aqualuxe' ),
                'description' => __( 'Set the number of product columns', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_product_columns',
                'type'        => 'select',
                'choices'     => [
                    '2' => __( '2 Columns', 'aqualuxe' ),
                    '3' => __( '3 Columns', 'aqualuxe' ),
                    '4' => __( '4 Columns', 'aqualuxe' ),
                    '5' => __( '5 Columns', 'aqualuxe' ),
                    '6' => __( '6 Columns', 'aqualuxe' ),
                ],
                'priority'    => 20,
            ]
        );
        
        // Related Products
        $wp_customize->add_setting(
            'aqualuxe_related_products',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_related_products',
            [
                'label'       => __( 'Show Related Products', 'aqualuxe' ),
                'description' => __( 'Show related products on product pages', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_related_products',
                'type'        => 'checkbox',
                'priority'    => 30,
            ]
        );
        
        // Related Products Count
        $wp_customize->add_setting(
            'aqualuxe_related_products_count',
            [
                'default'           => '4',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_related_products_count',
            [
                'label'       => __( 'Related Products Count', 'aqualuxe' ),
                'description' => __( 'Set the number of related products', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_related_products_count',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 12,
                    'step' => 1,
                ],
                'priority'    => 40,
            ]
        );
        
        // Product Gallery Zoom
        $wp_customize->add_setting(
            'aqualuxe_product_gallery_zoom',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_product_gallery_zoom',
            [
                'label'       => __( 'Product Gallery Zoom', 'aqualuxe' ),
                'description' => __( 'Enable product gallery zoom', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_product_gallery_zoom',
                'type'        => 'checkbox',
                'priority'    => 50,
            ]
        );
        
        // Product Gallery Lightbox
        $wp_customize->add_setting(
            'aqualuxe_product_gallery_lightbox',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_product_gallery_lightbox',
            [
                'label'       => __( 'Product Gallery Lightbox', 'aqualuxe' ),
                'description' => __( 'Enable product gallery lightbox', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_product_gallery_lightbox',
                'type'        => 'checkbox',
                'priority'    => 60,
            ]
        );
        
        // Product Gallery Slider
        $wp_customize->add_setting(
            'aqualuxe_product_gallery_slider',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_product_gallery_slider',
            [
                'label'       => __( 'Product Gallery Slider', 'aqualuxe' ),
                'description' => __( 'Enable product gallery slider', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_product_gallery_slider',
                'type'        => 'checkbox',
                'priority'    => 70,
            ]
        );
        
        // Quick View
        $wp_customize->add_setting(
            'aqualuxe_quick_view',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_quick_view',
            [
                'label'       => __( 'Quick View', 'aqualuxe' ),
                'description' => __( 'Enable quick view for products', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_quick_view',
                'type'        => 'checkbox',
                'priority'    => 80,
            ]
        );
        
        // Wishlist
        $wp_customize->add_setting(
            'aqualuxe_wishlist',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_wishlist',
            [
                'label'       => __( 'Wishlist', 'aqualuxe' ),
                'description' => __( 'Enable wishlist for products', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_wishlist',
                'type'        => 'checkbox',
                'priority'    => 90,
            ]
        );
        
        // Ajax Add to Cart
        $wp_customize->add_setting(
            'aqualuxe_ajax_add_to_cart',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_ajax_add_to_cart',
            [
                'label'       => __( 'Ajax Add to Cart', 'aqualuxe' ),
                'description' => __( 'Enable ajax add to cart', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_ajax_add_to_cart',
                'type'        => 'checkbox',
                'priority'    => 100,
            ]
        );
        
        // Multi Currency
        $wp_customize->add_setting(
            'aqualuxe_multi_currency',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_multi_currency',
            [
                'label'       => __( 'Multi Currency', 'aqualuxe' ),
                'description' => __( 'Enable multi currency support', 'aqualuxe' ),
                'section'     => 'aqualuxe_woocommerce_section',
                'settings'    => 'aqualuxe_multi_currency',
                'type'        => 'checkbox',
                'priority'    => 110,
            ]
        );
        
        // Currencies
        $wp_customize->add_setting(
            'aqualuxe_currencies',
            [
                'default'           => [ 'USD', 'EUR', 'GBP' ],
                'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Control(
                $wp_customize,
                'aqualuxe_currencies',
                [
                    'label'       => __( 'Currencies', 'aqualuxe' ),
                    'description' => __( 'Select the currencies to display', 'aqualuxe' ),
                    'section'     => 'aqualuxe_woocommerce_section',
                    'settings'    => 'aqualuxe_currencies',
                    'type'        => 'select',
                    'multiple'    => true,
                    'choices'     => [
                        'USD' => __( 'US Dollar', 'aqualuxe' ),
                        'EUR' => __( 'Euro', 'aqualuxe' ),
                        'GBP' => __( 'British Pound', 'aqualuxe' ),
                        'JPY' => __( 'Japanese Yen', 'aqualuxe' ),
                        'AUD' => __( 'Australian Dollar', 'aqualuxe' ),
                        'CAD' => __( 'Canadian Dollar', 'aqualuxe' ),
                        'CHF' => __( 'Swiss Franc', 'aqualuxe' ),
                        'CNY' => __( 'Chinese Yuan', 'aqualuxe' ),
                        'INR' => __( 'Indian Rupee', 'aqualuxe' ),
                        'BRL' => __( 'Brazilian Real', 'aqualuxe' ),
                    ],
                    'priority'    => 120,
                ]
            )
        );
    }

    /**
     * Add advanced settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_advanced_settings( $wp_customize ) {
        // Custom CSS
        $wp_customize->add_setting(
            'aqualuxe_custom_css',
            [
                'default'           => '',
                'sanitize_callback' => 'wp_strip_all_tags',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_custom_css',
            [
                'label'       => __( 'Custom CSS', 'aqualuxe' ),
                'description' => __( 'Add your custom CSS here', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_custom_css',
                'type'        => 'textarea',
                'priority'    => 10,
            ]
        );
        
        // Custom JavaScript
        $wp_customize->add_setting(
            'aqualuxe_custom_js',
            [
                'default'           => '',
                'sanitize_callback' => 'wp_strip_all_tags',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_custom_js',
            [
                'label'       => __( 'Custom JavaScript', 'aqualuxe' ),
                'description' => __( 'Add your custom JavaScript here', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_custom_js',
                'type'        => 'textarea',
                'priority'    => 20,
            ]
        );
        
        // Google Analytics
        $wp_customize->add_setting(
            'aqualuxe_google_analytics',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_google_analytics',
            [
                'label'       => __( 'Google Analytics ID', 'aqualuxe' ),
                'description' => __( 'Enter your Google Analytics ID (e.g. UA-XXXXX-Y or G-XXXXXXXX)', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_google_analytics',
                'type'        => 'text',
                'priority'    => 30,
            ]
        );
        
        // Facebook Pixel
        $wp_customize->add_setting(
            'aqualuxe_facebook_pixel',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_facebook_pixel',
            [
                'label'       => __( 'Facebook Pixel ID', 'aqualuxe' ),
                'description' => __( 'Enter your Facebook Pixel ID', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_facebook_pixel',
                'type'        => 'text',
                'priority'    => 40,
            ]
        );
        
        // Mailchimp API Key
        $wp_customize->add_setting(
            'aqualuxe_mailchimp_api_key',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_mailchimp_api_key',
            [
                'label'       => __( 'Mailchimp API Key', 'aqualuxe' ),
                'description' => __( 'Enter your Mailchimp API key', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_mailchimp_api_key',
                'type'        => 'text',
                'priority'    => 50,
            ]
        );
        
        // Mailchimp List ID
        $wp_customize->add_setting(
            'aqualuxe_mailchimp_list_id',
            [
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_mailchimp_list_id',
            [
                'label'       => __( 'Mailchimp List ID', 'aqualuxe' ),
                'description' => __( 'Enter your Mailchimp list ID', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_mailchimp_list_id',
                'type'        => 'text',
                'priority'    => 60,
            ]
        );
        
        // Maintenance Mode
        $wp_customize->add_setting(
            'aqualuxe_maintenance_mode',
            [
                'default'           => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_maintenance_mode',
            [
                'label'       => __( 'Maintenance Mode', 'aqualuxe' ),
                'description' => __( 'Enable maintenance mode', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_maintenance_mode',
                'type'        => 'checkbox',
                'priority'    => 70,
            ]
        );
        
        // Maintenance Message
        $wp_customize->add_setting(
            'aqualuxe_maintenance_message',
            [
                'default'           => __( 'We are currently undergoing scheduled maintenance. Please check back soon.', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_textarea_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_maintenance_message',
            [
                'label'       => __( 'Maintenance Message', 'aqualuxe' ),
                'description' => __( 'Enter the maintenance mode message', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_maintenance_message',
                'type'        => 'textarea',
                'priority'    => 80,
            ]
        );
        
        // Performance Optimization
        $wp_customize->add_setting(
            'aqualuxe_performance_optimization',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_performance_optimization',
            [
                'label'       => __( 'Performance Optimization', 'aqualuxe' ),
                'description' => __( 'Enable performance optimization', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_performance_optimization',
                'type'        => 'checkbox',
                'priority'    => 90,
            ]
        );
        
        // Minify CSS
        $wp_customize->add_setting(
            'aqualuxe_minify_css',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_minify_css',
            [
                'label'       => __( 'Minify CSS', 'aqualuxe' ),
                'description' => __( 'Minify CSS files', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_minify_css',
                'type'        => 'checkbox',
                'priority'    => 100,
            ]
        );
        
        // Minify JavaScript
        $wp_customize->add_setting(
            'aqualuxe_minify_js',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_minify_js',
            [
                'label'       => __( 'Minify JavaScript', 'aqualuxe' ),
                'description' => __( 'Minify JavaScript files', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_minify_js',
                'type'        => 'checkbox',
                'priority'    => 110,
            ]
        );
        
        // Lazy Loading
        $wp_customize->add_setting(
            'aqualuxe_lazy_loading',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_lazy_loading',
            [
                'label'       => __( 'Lazy Loading', 'aqualuxe' ),
                'description' => __( 'Enable lazy loading for images', 'aqualuxe' ),
                'section'     => 'aqualuxe_advanced_section',
                'settings'    => 'aqualuxe_lazy_loading',
                'type'        => 'checkbox',
                'priority'    => 120,
            ]
        );
    }

    /**
     * Enqueue customizer preview JavaScript
     *
     * @return void
     */
    public function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . 'js/customizer.js',
            [ 'customize-preview', 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Output customizer CSS
     *
     * @return void
     */
    public function output_customizer_css() {
        // Get customizer settings
        $primary_color = get_theme_mod( 'aqualuxe_color_primary', '#0073aa' );
        $secondary_color = get_theme_mod( 'aqualuxe_color_secondary', '#23282d' );
        $accent_color = get_theme_mod( 'aqualuxe_color_accent', '#00a0d2' );
        $text_color = get_theme_mod( 'aqualuxe_color_text', '#333333' );
        $heading_color = get_theme_mod( 'aqualuxe_color_heading', '#222222' );
        $background_color = get_theme_mod( 'aqualuxe_color_background', '#ffffff' );
        $dark_text_color = get_theme_mod( 'aqualuxe_color_dark_text', '#e0e0e0' );
        $dark_heading_color = get_theme_mod( 'aqualuxe_color_dark_heading', '#ffffff' );
        $dark_background_color = get_theme_mod( 'aqualuxe_color_dark_background', '#121212' );
        
        $body_font = get_theme_mod( 'aqualuxe_font_body', 'Inter' );
        $heading_font = get_theme_mod( 'aqualuxe_font_heading', 'Playfair Display' );
        $font_size_base = get_theme_mod( 'aqualuxe_font_size_base', '16' );
        $line_height = get_theme_mod( 'aqualuxe_line_height', '1.6' );
        $font_weight = get_theme_mod( 'aqualuxe_font_weight', '400' );
        $heading_font_weight = get_theme_mod( 'aqualuxe_heading_font_weight', '700' );
        
        $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
        $sidebar_width = get_theme_mod( 'aqualuxe_sidebar_width', '30' );
        $content_width = 100 - $sidebar_width;
        
        $custom_css = get_theme_mod( 'aqualuxe_custom_css', '' );
        
        // Output CSS
        ?>
        <style type="text/css">
            :root {
                --aqualuxe-primary-color: <?php echo esc_attr( $primary_color ); ?>;
                --aqualuxe-secondary-color: <?php echo esc_attr( $secondary_color ); ?>;
                --aqualuxe-accent-color: <?php echo esc_attr( $accent_color ); ?>;
                --aqualuxe-text-color: <?php echo esc_attr( $text_color ); ?>;
                --aqualuxe-heading-color: <?php echo esc_attr( $heading_color ); ?>;
                --aqualuxe-background-color: <?php echo esc_attr( $background_color ); ?>;
                --aqualuxe-dark-text-color: <?php echo esc_attr( $dark_text_color ); ?>;
                --aqualuxe-dark-heading-color: <?php echo esc_attr( $dark_heading_color ); ?>;
                --aqualuxe-dark-background-color: <?php echo esc_attr( $dark_background_color ); ?>;
                
                --aqualuxe-body-font: '<?php echo esc_attr( $body_font ); ?>', sans-serif;
                --aqualuxe-heading-font: '<?php echo esc_attr( $heading_font ); ?>', serif;
                --aqualuxe-font-size-base: <?php echo esc_attr( $font_size_base ); ?>px;
                --aqualuxe-line-height: <?php echo esc_attr( $line_height ); ?>;
                --aqualuxe-font-weight: <?php echo esc_attr( $font_weight ); ?>;
                --aqualuxe-heading-font-weight: <?php echo esc_attr( $heading_font_weight ); ?>;
                
                --aqualuxe-container-width: <?php echo esc_attr( $container_width ); ?>px;
                --aqualuxe-sidebar-width: <?php echo esc_attr( $sidebar_width ); ?>%;
                --aqualuxe-content-width: <?php echo esc_attr( $content_width ); ?>%;
            }
            
            body {
                font-family: var(--aqualuxe-body-font);
                font-size: var(--aqualuxe-font-size-base);
                line-height: var(--aqualuxe-line-height);
                font-weight: var(--aqualuxe-font-weight);
                color: var(--aqualuxe-text-color);
                background-color: var(--aqualuxe-background-color);
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: var(--aqualuxe-heading-font);
                font-weight: var(--aqualuxe-heading-font-weight);
                color: var(--aqualuxe-heading-color);
                line-height: 1.2;
            }
            
            a {
                color: var(--aqualuxe-primary-color);
            }
            
            a:hover {
                color: var(--aqualuxe-accent-color);
            }
            
            .container {
                max-width: var(--aqualuxe-container-width);
            }
            
            .layout-right-sidebar .content-area {
                width: var(--aqualuxe-content-width);
                float: left;
            }
            
            .layout-right-sidebar .widget-area {
                width: var(--aqualuxe-sidebar-width);
                float: right;
            }
            
            .layout-left-sidebar .content-area {
                width: var(--aqualuxe-content-width);
                float: right;
            }
            
            .layout-left-sidebar .widget-area {
                width: var(--aqualuxe-sidebar-width);
                float: left;
            }
            
            .layout-full-width .content-area {
                width: 100%;
                float: none;
            }
            
            .dark-mode {
                color: var(--aqualuxe-dark-text-color);
                background-color: var(--aqualuxe-dark-background-color);
            }
            
            .dark-mode h1, .dark-mode h2, .dark-mode h3, .dark-mode h4, .dark-mode h5, .dark-mode h6 {
                color: var(--aqualuxe-dark-heading-color);
            }
            
            .button, button, input[type="button"], input[type="reset"], input[type="submit"] {
                background-color: var(--aqualuxe-primary-color);
                color: #ffffff;
            }
            
            .button:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover {
                background-color: var(--aqualuxe-accent-color);
            }
            
            <?php echo wp_strip_all_tags( $custom_css ); ?>
        </style>
        <?php
    }
}

/**
 * Sanitize checkbox
 *
 * @param boolean $input Checkbox value
 * @return boolean
 */
function aqualuxe_sanitize_checkbox( $input ) {
    return ( isset( $input ) && true === $input ) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input Select value
 * @param \WP_Customize_Setting $setting Setting object
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize float
 *
 * @param float $input Float value
 * @return float
 */
function aqualuxe_sanitize_float( $input ) {
    return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}

/**
 * Sanitize multi select
 *
 * @param array $input Multi select value
 * @param \WP_Customize_Setting $setting Setting object
 * @return array
 */
function aqualuxe_sanitize_multi_select( $input, $setting ) {
    $choices = $setting->manager->get_control( $setting->id )->choices;
    $input = (array) $input;
    $output = [];
    
    foreach ( $input as $value ) {
        if ( array_key_exists( $value, $choices ) ) {
            $output[] = $value;
        }
    }
    
    return $output;
}