<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Customizer Class
 */
class AquaLuxe_Customizer {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Customizer
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Customizer
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Register customizer
        add_action( 'customize_register', [ $this, 'register' ] );
        
        // Enqueue customizer scripts
        add_action( 'customize_preview_init', [ $this, 'preview_scripts' ] );
        add_action( 'customize_controls_enqueue_scripts', [ $this, 'control_scripts' ] );
        
        // Output custom CSS
        add_action( 'wp_head', [ $this, 'output_css' ] );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function register( $wp_customize ) {
        // Add panels
        $this->add_panels( $wp_customize );
        
        // Add sections
        $this->add_sections( $wp_customize );
        
        // Add settings
        $this->add_settings( $wp_customize );
        
        // Add controls
        $this->add_controls( $wp_customize );
    }

    /**
     * Add panels
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    private function add_panels( $wp_customize ) {
        // Theme options panel
        $wp_customize->add_panel(
            'aqualuxe_options',
            [
                'title' => esc_html__( 'AquaLuxe Options', 'aqualuxe' ),
                'priority' => 10,
            ]
        );
        
        // Header panel
        $wp_customize->add_panel(
            'aqualuxe_header',
            [
                'title' => esc_html__( 'Header', 'aqualuxe' ),
                'priority' => 20,
                'panel' => 'aqualuxe_options',
            ]
        );
        
        // Footer panel
        $wp_customize->add_panel(
            'aqualuxe_footer',
            [
                'title' => esc_html__( 'Footer', 'aqualuxe' ),
                'priority' => 30,
                'panel' => 'aqualuxe_options',
            ]
        );
        
        // Blog panel
        $wp_customize->add_panel(
            'aqualuxe_blog',
            [
                'title' => esc_html__( 'Blog', 'aqualuxe' ),
                'priority' => 40,
                'panel' => 'aqualuxe_options',
            ]
        );
        
        // WooCommerce panel
        if ( aqualuxe_is_woocommerce_active() ) {
            $wp_customize->add_panel(
                'aqualuxe_woocommerce',
                [
                    'title' => esc_html__( 'WooCommerce', 'aqualuxe' ),
                    'priority' => 50,
                    'panel' => 'aqualuxe_options',
                ]
            );
        }
    }

    /**
     * Add sections
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    private function add_sections( $wp_customize ) {
        // General section
        $wp_customize->add_section(
            'aqualuxe_general',
            [
                'title' => esc_html__( 'General', 'aqualuxe' ),
                'priority' => 10,
                'panel' => 'aqualuxe_options',
            ]
        );
        
        // Colors section
        $wp_customize->add_section(
            'aqualuxe_colors',
            [
                'title' => esc_html__( 'Colors', 'aqualuxe' ),
                'priority' => 20,
                'panel' => 'aqualuxe_options',
            ]
        );
        
        // Typography section
        $wp_customize->add_section(
            'aqualuxe_typography',
            [
                'title' => esc_html__( 'Typography', 'aqualuxe' ),
                'priority' => 30,
                'panel' => 'aqualuxe_options',
            ]
        );
        
        // Layout section
        $wp_customize->add_section(
            'aqualuxe_layout',
            [
                'title' => esc_html__( 'Layout', 'aqualuxe' ),
                'priority' => 40,
                'panel' => 'aqualuxe_options',
            ]
        );
        
        // Social section
        $wp_customize->add_section(
            'aqualuxe_social',
            [
                'title' => esc_html__( 'Social Media', 'aqualuxe' ),
                'priority' => 50,
                'panel' => 'aqualuxe_options',
            ]
        );
        
        // Contact section
        $wp_customize->add_section(
            'aqualuxe_contact',
            [
                'title' => esc_html__( 'Contact Information', 'aqualuxe' ),
                'priority' => 60,
                'panel' => 'aqualuxe_options',
            ]
        );
        
        // Header sections
        $wp_customize->add_section(
            'aqualuxe_header_general',
            [
                'title' => esc_html__( 'General', 'aqualuxe' ),
                'priority' => 10,
                'panel' => 'aqualuxe_header',
            ]
        );
        
        $wp_customize->add_section(
            'aqualuxe_header_top_bar',
            [
                'title' => esc_html__( 'Top Bar', 'aqualuxe' ),
                'priority' => 20,
                'panel' => 'aqualuxe_header',
            ]
        );
        
        $wp_customize->add_section(
            'aqualuxe_header_main',
            [
                'title' => esc_html__( 'Main Header', 'aqualuxe' ),
                'priority' => 30,
                'panel' => 'aqualuxe_header',
            ]
        );
        
        $wp_customize->add_section(
            'aqualuxe_header_mobile',
            [
                'title' => esc_html__( 'Mobile Header', 'aqualuxe' ),
                'priority' => 40,
                'panel' => 'aqualuxe_header',
            ]
        );
        
        // Footer sections
        $wp_customize->add_section(
            'aqualuxe_footer_general',
            [
                'title' => esc_html__( 'General', 'aqualuxe' ),
                'priority' => 10,
                'panel' => 'aqualuxe_footer',
            ]
        );
        
        $wp_customize->add_section(
            'aqualuxe_footer_widgets',
            [
                'title' => esc_html__( 'Footer Widgets', 'aqualuxe' ),
                'priority' => 20,
                'panel' => 'aqualuxe_footer',
            ]
        );
        
        $wp_customize->add_section(
            'aqualuxe_footer_bottom',
            [
                'title' => esc_html__( 'Footer Bottom', 'aqualuxe' ),
                'priority' => 30,
                'panel' => 'aqualuxe_footer',
            ]
        );
        
        // Blog sections
        $wp_customize->add_section(
            'aqualuxe_blog_archive',
            [
                'title' => esc_html__( 'Blog Archive', 'aqualuxe' ),
                'priority' => 10,
                'panel' => 'aqualuxe_blog',
            ]
        );
        
        $wp_customize->add_section(
            'aqualuxe_blog_single',
            [
                'title' => esc_html__( 'Single Post', 'aqualuxe' ),
                'priority' => 20,
                'panel' => 'aqualuxe_blog',
            ]
        );
        
        // WooCommerce sections
        if ( aqualuxe_is_woocommerce_active() ) {
            $wp_customize->add_section(
                'aqualuxe_woocommerce_general',
                [
                    'title' => esc_html__( 'General', 'aqualuxe' ),
                    'priority' => 10,
                    'panel' => 'aqualuxe_woocommerce',
                ]
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_shop',
                [
                    'title' => esc_html__( 'Shop', 'aqualuxe' ),
                    'priority' => 20,
                    'panel' => 'aqualuxe_woocommerce',
                ]
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_product',
                [
                    'title' => esc_html__( 'Product', 'aqualuxe' ),
                    'priority' => 30,
                    'panel' => 'aqualuxe_woocommerce',
                ]
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_cart',
                [
                    'title' => esc_html__( 'Cart', 'aqualuxe' ),
                    'priority' => 40,
                    'panel' => 'aqualuxe_woocommerce',
                ]
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_checkout',
                [
                    'title' => esc_html__( 'Checkout', 'aqualuxe' ),
                    'priority' => 50,
                    'panel' => 'aqualuxe_woocommerce',
                ]
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_account',
                [
                    'title' => esc_html__( 'Account', 'aqualuxe' ),
                    'priority' => 60,
                    'panel' => 'aqualuxe_woocommerce',
                ]
            );
        }
    }

    /**
     * Add settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    private function add_settings( $wp_customize ) {
        // General settings
        $wp_customize->add_setting(
            'aqualuxe_options[logo_light]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[container_width]',
            [
                'default' => '1200',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[enable_preloader]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[enable_back_to_top]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        // Color settings
        $wp_customize->add_setting(
            'aqualuxe_options[color_primary]',
            [
                'default' => '#0073aa',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[color_secondary]',
            [
                'default' => '#23282d',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[color_accent]',
            [
                'default' => '#00a0d2',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[color_text]',
            [
                'default' => '#333333',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[color_heading]',
            [
                'default' => '#23282d',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[color_background]',
            [
                'default' => '#ffffff',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[color_dark_background]',
            [
                'default' => '#121212',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[color_dark_text]',
            [
                'default' => '#f1f1f1',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        // Typography settings
        $wp_customize->add_setting(
            'aqualuxe_options[font_body]',
            [
                'default' => 'Roboto, sans-serif',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[font_heading]',
            [
                'default' => 'Playfair Display, serif',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[font_size_base]',
            [
                'default' => '16',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[line_height_base]',
            [
                'default' => '1.6',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_float',
            ]
        );
        
        // Layout settings
        $wp_customize->add_setting(
            'aqualuxe_options[sidebar_position]',
            [
                'default' => 'right',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[enable_sticky_header]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        // Social settings
        $wp_customize->add_setting(
            'aqualuxe_options[social_facebook]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[social_twitter]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[social_instagram]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[social_youtube]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[social_linkedin]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        // Contact settings
        $wp_customize->add_setting(
            'aqualuxe_options[contact_phone]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[contact_email]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_email',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[contact_address]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[contact_hours]',
            [
                'default' => '',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        // Header settings
        $wp_customize->add_setting(
            'aqualuxe_options[header_layout]',
            [
                'default' => 'default',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[enable_header_top_bar]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[enable_header_search]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        // Footer settings
        $wp_customize->add_setting(
            'aqualuxe_options[footer_layout]',
            [
                'default' => 'default',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[footer_widgets_columns]',
            [
                'default' => '4',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[footer_copyright]',
            [
                'default' => sprintf( esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ), date( 'Y' ), get_bloginfo( 'name' ) ),
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'wp_kses_post',
            ]
        );
        
        // Blog settings
        $wp_customize->add_setting(
            'aqualuxe_options[blog_layout]',
            [
                'default' => 'grid',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_columns]',
            [
                'default' => '3',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_excerpt_length]',
            [
                'default' => '55',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_show_author]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_show_date]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_show_categories]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_show_comments]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_show_tags]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_show_author_bio]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_show_related_posts]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_setting(
            'aqualuxe_options[blog_show_social_sharing]',
            [
                'default' => true,
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        // WooCommerce settings
        if ( aqualuxe_is_woocommerce_active() ) {
            $wp_customize->add_setting(
                'aqualuxe_options[shop_layout]',
                [
                    'default' => 'grid',
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_select',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[shop_columns]',
                [
                    'default' => '4',
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'absint',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[shop_products_per_page]',
                [
                    'default' => '12',
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'absint',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_shop_sidebar]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_shop_filters]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_product_quick_view]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_product_wishlist]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[product_gallery_layout]',
                [
                    'default' => 'horizontal',
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_select',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_product_sticky_add_to_cart]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_product_tabs]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_product_related]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_cart_cross_sells]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_checkout_coupon]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_checkout_login]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_checkout_order_notes]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_setting(
                'aqualuxe_options[enable_account_sidebar]',
                [
                    'default' => true,
                    'type' => 'theme_mod',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
        }
    }

    /**
     * Add controls
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    private function add_controls( $wp_customize ) {
        // General controls
        $wp_customize->add_control(
            new WP_Customize_Media_Control(
                $wp_customize,
                'aqualuxe_options[logo_light]',
                [
                    'label' => esc_html__( 'Light Logo', 'aqualuxe' ),
                    'description' => esc_html__( 'Upload a light version of your logo for dark backgrounds.', 'aqualuxe' ),
                    'section' => 'aqualuxe_general',
                    'settings' => 'aqualuxe_options[logo_light]',
                    'mime_type' => 'image',
                ]
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[container_width]',
            [
                'label' => esc_html__( 'Container Width', 'aqualuxe' ),
                'description' => esc_html__( 'Width of the main container in pixels.', 'aqualuxe' ),
                'section' => 'aqualuxe_general',
                'settings' => 'aqualuxe_options[container_width]',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 960,
                    'max' => 1920,
                    'step' => 10,
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[enable_preloader]',
            [
                'label' => esc_html__( 'Enable Preloader', 'aqualuxe' ),
                'description' => esc_html__( 'Show a loading animation until the page is ready.', 'aqualuxe' ),
                'section' => 'aqualuxe_general',
                'settings' => 'aqualuxe_options[enable_preloader]',
                'type' => 'checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[enable_back_to_top]',
            [
                'label' => esc_html__( 'Enable Back to Top', 'aqualuxe' ),
                'description' => esc_html__( 'Show a button to scroll back to the top of the page.', 'aqualuxe' ),
                'section' => 'aqualuxe_general',
                'settings' => 'aqualuxe_options[enable_back_to_top]',
                'type' => 'checkbox',
            ]
        );
        
        // Color controls
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_options[color_primary]',
                [
                    'label' => esc_html__( 'Primary Color', 'aqualuxe' ),
                    'description' => esc_html__( 'Main brand color used for links and buttons.', 'aqualuxe' ),
                    'section' => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_options[color_primary]',
                ]
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_options[color_secondary]',
                [
                    'label' => esc_html__( 'Secondary Color', 'aqualuxe' ),
                    'description' => esc_html__( 'Secondary brand color used for accents and highlights.', 'aqualuxe' ),
                    'section' => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_options[color_secondary]',
                ]
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_options[color_accent]',
                [
                    'label' => esc_html__( 'Accent Color', 'aqualuxe' ),
                    'description' => esc_html__( 'Used for highlighting active elements.', 'aqualuxe' ),
                    'section' => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_options[color_accent]',
                ]
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_options[color_text]',
                [
                    'label' => esc_html__( 'Text Color', 'aqualuxe' ),
                    'description' => esc_html__( 'Main text color.', 'aqualuxe' ),
                    'section' => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_options[color_text]',
                ]
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_options[color_heading]',
                [
                    'label' => esc_html__( 'Heading Color', 'aqualuxe' ),
                    'description' => esc_html__( 'Color for headings.', 'aqualuxe' ),
                    'section' => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_options[color_heading]',
                ]
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_options[color_background]',
                [
                    'label' => esc_html__( 'Background Color', 'aqualuxe' ),
                    'description' => esc_html__( 'Main background color.', 'aqualuxe' ),
                    'section' => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_options[color_background]',
                ]
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_options[color_dark_background]',
                [
                    'label' => esc_html__( 'Dark Mode Background', 'aqualuxe' ),
                    'description' => esc_html__( 'Background color for dark mode.', 'aqualuxe' ),
                    'section' => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_options[color_dark_background]',
                ]
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_options[color_dark_text]',
                [
                    'label' => esc_html__( 'Dark Mode Text', 'aqualuxe' ),
                    'description' => esc_html__( 'Text color for dark mode.', 'aqualuxe' ),
                    'section' => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_options[color_dark_text]',
                ]
            )
        );
        
        // Typography controls
        $wp_customize->add_control(
            'aqualuxe_options[font_body]',
            [
                'label' => esc_html__( 'Body Font', 'aqualuxe' ),
                'description' => esc_html__( 'Font family for body text.', 'aqualuxe' ),
                'section' => 'aqualuxe_typography',
                'settings' => 'aqualuxe_options[font_body]',
                'type' => 'select',
                'choices' => [
                    'Roboto, sans-serif' => esc_html__( 'Roboto', 'aqualuxe' ),
                    'Open Sans, sans-serif' => esc_html__( 'Open Sans', 'aqualuxe' ),
                    'Lato, sans-serif' => esc_html__( 'Lato', 'aqualuxe' ),
                    'Montserrat, sans-serif' => esc_html__( 'Montserrat', 'aqualuxe' ),
                    'Raleway, sans-serif' => esc_html__( 'Raleway', 'aqualuxe' ),
                    'Nunito, sans-serif' => esc_html__( 'Nunito', 'aqualuxe' ),
                    'Poppins, sans-serif' => esc_html__( 'Poppins', 'aqualuxe' ),
                    'Quicksand, sans-serif' => esc_html__( 'Quicksand', 'aqualuxe' ),
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[font_heading]',
            [
                'label' => esc_html__( 'Heading Font', 'aqualuxe' ),
                'description' => esc_html__( 'Font family for headings.', 'aqualuxe' ),
                'section' => 'aqualuxe_typography',
                'settings' => 'aqualuxe_options[font_heading]',
                'type' => 'select',
                'choices' => [
                    'Playfair Display, serif' => esc_html__( 'Playfair Display', 'aqualuxe' ),
                    'Merriweather, serif' => esc_html__( 'Merriweather', 'aqualuxe' ),
                    'Roboto Slab, serif' => esc_html__( 'Roboto Slab', 'aqualuxe' ),
                    'Montserrat, sans-serif' => esc_html__( 'Montserrat', 'aqualuxe' ),
                    'Oswald, sans-serif' => esc_html__( 'Oswald', 'aqualuxe' ),
                    'Lora, serif' => esc_html__( 'Lora', 'aqualuxe' ),
                    'Cormorant Garamond, serif' => esc_html__( 'Cormorant Garamond', 'aqualuxe' ),
                    'Libre Baskerville, serif' => esc_html__( 'Libre Baskerville', 'aqualuxe' ),
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[font_size_base]',
            [
                'label' => esc_html__( 'Base Font Size', 'aqualuxe' ),
                'description' => esc_html__( 'Base font size in pixels.', 'aqualuxe' ),
                'section' => 'aqualuxe_typography',
                'settings' => 'aqualuxe_options[font_size_base]',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 12,
                    'max' => 24,
                    'step' => 1,
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[line_height_base]',
            [
                'label' => esc_html__( 'Base Line Height', 'aqualuxe' ),
                'description' => esc_html__( 'Base line height.', 'aqualuxe' ),
                'section' => 'aqualuxe_typography',
                'settings' => 'aqualuxe_options[line_height_base]',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 1,
                    'max' => 2,
                    'step' => 0.1,
                ],
            ]
        );
        
        // Layout controls
        $wp_customize->add_control(
            'aqualuxe_options[sidebar_position]',
            [
                'label' => esc_html__( 'Sidebar Position', 'aqualuxe' ),
                'description' => esc_html__( 'Position of the sidebar.', 'aqualuxe' ),
                'section' => 'aqualuxe_layout',
                'settings' => 'aqualuxe_options[sidebar_position]',
                'type' => 'select',
                'choices' => [
                    'right' => esc_html__( 'Right', 'aqualuxe' ),
                    'left' => esc_html__( 'Left', 'aqualuxe' ),
                    'none' => esc_html__( 'None', 'aqualuxe' ),
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[enable_sticky_header]',
            [
                'label' => esc_html__( 'Enable Sticky Header', 'aqualuxe' ),
                'description' => esc_html__( 'Keep the header visible when scrolling down.', 'aqualuxe' ),
                'section' => 'aqualuxe_layout',
                'settings' => 'aqualuxe_options[enable_sticky_header]',
                'type' => 'checkbox',
            ]
        );
        
        // Social controls
        $wp_customize->add_control(
            'aqualuxe_options[social_facebook]',
            [
                'label' => esc_html__( 'Facebook URL', 'aqualuxe' ),
                'section' => 'aqualuxe_social',
                'settings' => 'aqualuxe_options[social_facebook]',
                'type' => 'url',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[social_twitter]',
            [
                'label' => esc_html__( 'Twitter URL', 'aqualuxe' ),
                'section' => 'aqualuxe_social',
                'settings' => 'aqualuxe_options[social_twitter]',
                'type' => 'url',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[social_instagram]',
            [
                'label' => esc_html__( 'Instagram URL', 'aqualuxe' ),
                'section' => 'aqualuxe_social',
                'settings' => 'aqualuxe_options[social_instagram]',
                'type' => 'url',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[social_youtube]',
            [
                'label' => esc_html__( 'YouTube URL', 'aqualuxe' ),
                'section' => 'aqualuxe_social',
                'settings' => 'aqualuxe_options[social_youtube]',
                'type' => 'url',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[social_linkedin]',
            [
                'label' => esc_html__( 'LinkedIn URL', 'aqualuxe' ),
                'section' => 'aqualuxe_social',
                'settings' => 'aqualuxe_options[social_linkedin]',
                'type' => 'url',
            ]
        );
        
        // Contact controls
        $wp_customize->add_control(
            'aqualuxe_options[contact_phone]',
            [
                'label' => esc_html__( 'Phone Number', 'aqualuxe' ),
                'section' => 'aqualuxe_contact',
                'settings' => 'aqualuxe_options[contact_phone]',
                'type' => 'text',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[contact_email]',
            [
                'label' => esc_html__( 'Email Address', 'aqualuxe' ),
                'section' => 'aqualuxe_contact',
                'settings' => 'aqualuxe_options[contact_email]',
                'type' => 'email',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[contact_address]',
            [
                'label' => esc_html__( 'Address', 'aqualuxe' ),
                'section' => 'aqualuxe_contact',
                'settings' => 'aqualuxe_options[contact_address]',
                'type' => 'text',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[contact_hours]',
            [
                'label' => esc_html__( 'Business Hours', 'aqualuxe' ),
                'section' => 'aqualuxe_contact',
                'settings' => 'aqualuxe_options[contact_hours]',
                'type' => 'text',
            ]
        );
        
        // Header controls
        $wp_customize->add_control(
            'aqualuxe_options[header_layout]',
            [
                'label' => esc_html__( 'Header Layout', 'aqualuxe' ),
                'section' => 'aqualuxe_header_general',
                'settings' => 'aqualuxe_options[header_layout]',
                'type' => 'select',
                'choices' => [
                    'default' => esc_html__( 'Default', 'aqualuxe' ),
                    'centered' => esc_html__( 'Centered', 'aqualuxe' ),
                    'minimal' => esc_html__( 'Minimal', 'aqualuxe' ),
                    'split' => esc_html__( 'Split', 'aqualuxe' ),
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[enable_header_top_bar]',
            [
                'label' => esc_html__( 'Enable Top Bar', 'aqualuxe' ),
                'section' => 'aqualuxe_header_top_bar',
                'settings' => 'aqualuxe_options[enable_header_top_bar]',
                'type' => 'checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[enable_header_search]',
            [
                'label' => esc_html__( 'Enable Search', 'aqualuxe' ),
                'section' => 'aqualuxe_header_main',
                'settings' => 'aqualuxe_options[enable_header_search]',
                'type' => 'checkbox',
            ]
        );
        
        // Footer controls
        $wp_customize->add_control(
            'aqualuxe_options[footer_layout]',
            [
                'label' => esc_html__( 'Footer Layout', 'aqualuxe' ),
                'section' => 'aqualuxe_footer_general',
                'settings' => 'aqualuxe_options[footer_layout]',
                'type' => 'select',
                'choices' => [
                    'default' => esc_html__( 'Default', 'aqualuxe' ),
                    'centered' => esc_html__( 'Centered', 'aqualuxe' ),
                    'minimal' => esc_html__( 'Minimal', 'aqualuxe' ),
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[footer_widgets_columns]',
            [
                'label' => esc_html__( 'Footer Widgets Columns', 'aqualuxe' ),
                'section' => 'aqualuxe_footer_widgets',
                'settings' => 'aqualuxe_options[footer_widgets_columns]',
                'type' => 'select',
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[footer_copyright]',
            [
                'label' => esc_html__( 'Copyright Text', 'aqualuxe' ),
                'section' => 'aqualuxe_footer_bottom',
                'settings' => 'aqualuxe_options[footer_copyright]',
                'type' => 'textarea',
            ]
        );
        
        // Blog controls
        $wp_customize->add_control(
            'aqualuxe_options[blog_layout]',
            [
                'label' => esc_html__( 'Blog Layout', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_options[blog_layout]',
                'type' => 'select',
                'choices' => [
                    'grid' => esc_html__( 'Grid', 'aqualuxe' ),
                    'list' => esc_html__( 'List', 'aqualuxe' ),
                    'masonry' => esc_html__( 'Masonry', 'aqualuxe' ),
                    'standard' => esc_html__( 'Standard', 'aqualuxe' ),
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_columns]',
            [
                'label' => esc_html__( 'Blog Columns', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_options[blog_columns]',
                'type' => 'select',
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_excerpt_length]',
            [
                'label' => esc_html__( 'Excerpt Length', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_options[blog_excerpt_length]',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 10,
                    'max' => 200,
                    'step' => 5,
                ],
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_show_author]',
            [
                'label' => esc_html__( 'Show Author', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_options[blog_show_author]',
                'type' => 'checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_show_date]',
            [
                'label' => esc_html__( 'Show Date', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_options[blog_show_date]',
                'type' => 'checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_show_categories]',
            [
                'label' => esc_html__( 'Show Categories', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_options[blog_show_categories]',
                'type' => 'checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_show_comments]',
            [
                'label' => esc_html__( 'Show Comments', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_options[blog_show_comments]',
                'type' => 'checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_show_tags]',
            [
                'label' => esc_html__( 'Show Tags', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_options[blog_show_tags]',
                'type' => 'checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_show_author_bio]',
            [
                'label' => esc_html__( 'Show Author Bio', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_options[blog_show_author_bio]',
                'type' => 'checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_show_related_posts]',
            [
                'label' => esc_html__( 'Show Related Posts', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_options[blog_show_related_posts]',
                'type' => 'checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_options[blog_show_social_sharing]',
            [
                'label' => esc_html__( 'Show Social Sharing', 'aqualuxe' ),
                'section' => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_options[blog_show_social_sharing]',
                'type' => 'checkbox',
            ]
        );
        
        // WooCommerce controls
        if ( aqualuxe_is_woocommerce_active() ) {
            $wp_customize->add_control(
                'aqualuxe_options[shop_layout]',
                [
                    'label' => esc_html__( 'Shop Layout', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_options[shop_layout]',
                    'type' => 'select',
                    'choices' => [
                        'grid' => esc_html__( 'Grid', 'aqualuxe' ),
                        'list' => esc_html__( 'List', 'aqualuxe' ),
                        'masonry' => esc_html__( 'Masonry', 'aqualuxe' ),
                    ],
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[shop_columns]',
                [
                    'label' => esc_html__( 'Shop Columns', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_options[shop_columns]',
                    'type' => 'select',
                    'choices' => [
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                    ],
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[shop_products_per_page]',
                [
                    'label' => esc_html__( 'Products Per Page', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_options[shop_products_per_page]',
                    'type' => 'number',
                    'input_attrs' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_shop_sidebar]',
                [
                    'label' => esc_html__( 'Enable Shop Sidebar', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_options[enable_shop_sidebar]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_shop_filters]',
                [
                    'label' => esc_html__( 'Enable Shop Filters', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_options[enable_shop_filters]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_product_quick_view]',
                [
                    'label' => esc_html__( 'Enable Quick View', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_options[enable_product_quick_view]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_product_wishlist]',
                [
                    'label' => esc_html__( 'Enable Wishlist', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_options[enable_product_wishlist]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[product_gallery_layout]',
                [
                    'label' => esc_html__( 'Product Gallery Layout', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_options[product_gallery_layout]',
                    'type' => 'select',
                    'choices' => [
                        'horizontal' => esc_html__( 'Horizontal', 'aqualuxe' ),
                        'vertical' => esc_html__( 'Vertical', 'aqualuxe' ),
                        'grid' => esc_html__( 'Grid', 'aqualuxe' ),
                    ],
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_product_sticky_add_to_cart]',
                [
                    'label' => esc_html__( 'Enable Sticky Add to Cart', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_options[enable_product_sticky_add_to_cart]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_product_tabs]',
                [
                    'label' => esc_html__( 'Enable Product Tabs', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_options[enable_product_tabs]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_product_related]',
                [
                    'label' => esc_html__( 'Enable Related Products', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_options[enable_product_related]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_cart_cross_sells]',
                [
                    'label' => esc_html__( 'Enable Cross-Sells', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_cart',
                    'settings' => 'aqualuxe_options[enable_cart_cross_sells]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_checkout_coupon]',
                [
                    'label' => esc_html__( 'Enable Coupon Form', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_checkout',
                    'settings' => 'aqualuxe_options[enable_checkout_coupon]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_checkout_login]',
                [
                    'label' => esc_html__( 'Enable Login Form', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_checkout',
                    'settings' => 'aqualuxe_options[enable_checkout_login]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_checkout_order_notes]',
                [
                    'label' => esc_html__( 'Enable Order Notes', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_checkout',
                    'settings' => 'aqualuxe_options[enable_checkout_order_notes]',
                    'type' => 'checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_options[enable_account_sidebar]',
                [
                    'label' => esc_html__( 'Enable Account Sidebar', 'aqualuxe' ),
                    'section' => 'aqualuxe_woocommerce_account',
                    'settings' => 'aqualuxe_options[enable_account_sidebar]',
                    'type' => 'checkbox',
                ]
            );
        }
    }

    /**
     * Enqueue customizer preview scripts
     */
    public function preview_scripts() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . 'js/customizer-preview.js',
            [ 'customize-preview', 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue customizer control scripts
     */
    public function control_scripts() {
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            AQUALUXE_ASSETS_URI . 'js/customizer-controls.js',
            [ 'customize-controls', 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'aqualuxe-customizer-style',
            AQUALUXE_ASSETS_URI . 'css/customizer.css',
            [],
            AQUALUXE_VERSION
        );
    }

    /**
     * Output custom CSS
     */
    public function output_css() {
        // Get options
        $primary_color = aqualuxe_get_color( 'primary', '#0073aa' );
        $secondary_color = aqualuxe_get_color( 'secondary', '#23282d' );
        $accent_color = aqualuxe_get_color( 'accent', '#00a0d2' );
        $text_color = aqualuxe_get_color( 'text', '#333333' );
        $heading_color = aqualuxe_get_color( 'heading', '#23282d' );
        $background_color = aqualuxe_get_color( 'background', '#ffffff' );
        $dark_background_color = aqualuxe_get_color( 'dark_background', '#121212' );
        $dark_text_color = aqualuxe_get_color( 'dark_text', '#f1f1f1' );
        
        $body_font = aqualuxe_get_font( 'body', 'Roboto, sans-serif' );
        $heading_font = aqualuxe_get_font( 'heading', 'Playfair Display, serif' );
        $font_size_base = aqualuxe_get_option( 'font_size_base', '16' );
        $line_height_base = aqualuxe_get_option( 'line_height_base', '1.6' );
        
        $container_width = aqualuxe_get_option( 'container_width', '1200' );
        
        // Output CSS
        ?>
        <style id="aqualuxe-custom-css">
            :root {
                --color-primary: <?php echo esc_attr( $primary_color ); ?>;
                --color-secondary: <?php echo esc_attr( $secondary_color ); ?>;
                --color-accent: <?php echo esc_attr( $accent_color ); ?>;
                --color-text: <?php echo esc_attr( $text_color ); ?>;
                --color-heading: <?php echo esc_attr( $heading_color ); ?>;
                --color-background: <?php echo esc_attr( $background_color ); ?>;
                --color-dark-background: <?php echo esc_attr( $dark_background_color ); ?>;
                --color-dark-text: <?php echo esc_attr( $dark_text_color ); ?>;
                
                --font-body: <?php echo esc_attr( $body_font ); ?>;
                --font-heading: <?php echo esc_attr( $heading_font ); ?>;
                --font-size-base: <?php echo esc_attr( $font_size_base ); ?>px;
                --line-height-base: <?php echo esc_attr( $line_height_base ); ?>;
                
                --container-width: <?php echo esc_attr( $container_width ); ?>px;
            }
            
            body {
                font-family: var(--font-body);
                font-size: var(--font-size-base);
                line-height: var(--line-height-base);
                color: var(--color-text);
                background-color: var(--color-background);
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: var(--font-heading);
                color: var(--color-heading);
            }
            
            a {
                color: var(--color-primary);
            }
            
            a:hover {
                color: var(--color-accent);
            }
            
            .button, button, input[type="button"], input[type="reset"], input[type="submit"] {
                background-color: var(--color-primary);
                color: #ffffff;
            }
            
            .button:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover {
                background-color: var(--color-accent);
            }
            
            .container {
                max-width: var(--container-width);
            }
            
            /* Dark mode styles */
            body.dark-mode {
                color: var(--color-dark-text);
                background-color: var(--color-dark-background);
            }
            
            body.dark-mode h1, body.dark-mode h2, body.dark-mode h3, body.dark-mode h4, body.dark-mode h5, body.dark-mode h6 {
                color: var(--color-dark-text);
            }
        </style>
        <?php
    }
}

// Initialize customizer
AquaLuxe_Customizer::instance();

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( isset( $checked ) && true === $checked ) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input Select value
 * @param WP_Customize_Setting $setting Setting instance
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get list of choices from the control
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // Return input if valid or return default option
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize float
 *
 * @param float $number Number to sanitize
 * @return float
 */
function aqualuxe_sanitize_float( $number ) {
    return filter_var( $number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}