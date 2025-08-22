<?php
/**
 * AquaLuxe Customizer Class
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Customizer Class
 */
class AquaLuxe_Customizer {

    /**
     * Instance
     *
     * @access private
     * @var object
     */
    private static $instance;

    /**
     * Customizer settings
     *
     * @access private
     * @var array
     */
    private $settings = array();

    /**
     * Customizer panels
     *
     * @access private
     * @var array
     */
    private $panels = array();

    /**
     * Customizer sections
     *
     * @access private
     * @var array
     */
    private $sections = array();

    /**
     * Customizer controls
     *
     * @access private
     * @var array
     */
    private $controls = array();

    /**
     * Initiator
     *
     * @return object
     */
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_customizer' ) );
        add_action( 'customize_preview_init', array( $this, 'preview_init' ) );
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ) );
        add_action( 'wp_head', array( $this, 'output_styles' ), 999 );
        add_action( 'after_setup_theme', array( $this, 'setup_customizer' ) );
    }

    /**
     * Setup customizer
     */
    public function setup_customizer() {
        $this->register_panels();
        $this->register_sections();
        $this->register_settings();
        $this->register_controls();
    }

    /**
     * Register customizer
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function register_customizer( $wp_customize ) {
        // Register panels
        foreach ( $this->panels as $panel_id => $panel ) {
            $wp_customize->add_panel( $panel_id, $panel );
        }

        // Register sections
        foreach ( $this->sections as $section_id => $section ) {
            $wp_customize->add_section( $section_id, $section );
        }

        // Register settings
        foreach ( $this->settings as $setting_id => $setting ) {
            $wp_customize->add_setting( $setting_id, $setting );
        }

        // Register controls
        foreach ( $this->controls as $control_id => $control ) {
            $type = isset( $control['type'] ) ? $control['type'] : '';
            $control_args = $control;

            // Remove type from control args
            if ( isset( $control_args['type'] ) ) {
                unset( $control_args['type'] );
            }

            switch ( $type ) {
                case 'color':
                    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control_id, $control_args ) );
                    break;

                case 'image':
                    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $control_id, $control_args ) );
                    break;

                case 'upload':
                    $wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, $control_id, $control_args ) );
                    break;

                case 'media':
                    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $control_id, $control_args ) );
                    break;

                case 'cropped_image':
                    $wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, $control_id, $control_args ) );
                    break;

                case 'code_editor':
                    $wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, $control_id, $control_args ) );
                    break;

                case 'date_time':
                    $wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, $control_id, $control_args ) );
                    break;

                case 'dropdown_pages':
                    $wp_customize->add_control( new WP_Customize_Dropdown_Pages_Control( $wp_customize, $control_id, $control_args ) );
                    break;

                case 'range':
                    if ( class_exists( 'AquaLuxe_Customize_Range_Control' ) ) {
                        $wp_customize->add_control( new AquaLuxe_Customize_Range_Control( $wp_customize, $control_id, $control_args ) );
                    } else {
                        $wp_customize->add_control( $control_id, $control_args );
                    }
                    break;

                case 'typography':
                    if ( class_exists( 'AquaLuxe_Customize_Typography_Control' ) ) {
                        $wp_customize->add_control( new AquaLuxe_Customize_Typography_Control( $wp_customize, $control_id, $control_args ) );
                    } else {
                        $wp_customize->add_control( $control_id, $control_args );
                    }
                    break;

                case 'dimensions':
                    if ( class_exists( 'AquaLuxe_Customize_Dimensions_Control' ) ) {
                        $wp_customize->add_control( new AquaLuxe_Customize_Dimensions_Control( $wp_customize, $control_id, $control_args ) );
                    } else {
                        $wp_customize->add_control( $control_id, $control_args );
                    }
                    break;

                case 'sortable':
                    if ( class_exists( 'AquaLuxe_Customize_Sortable_Control' ) ) {
                        $wp_customize->add_control( new AquaLuxe_Customize_Sortable_Control( $wp_customize, $control_id, $control_args ) );
                    } else {
                        $wp_customize->add_control( $control_id, $control_args );
                    }
                    break;

                case 'toggle':
                    if ( class_exists( 'AquaLuxe_Customize_Toggle_Control' ) ) {
                        $wp_customize->add_control( new AquaLuxe_Customize_Toggle_Control( $wp_customize, $control_id, $control_args ) );
                    } else {
                        $wp_customize->add_control( $control_id, $control_args );
                    }
                    break;

                case 'radio_image':
                    if ( class_exists( 'AquaLuxe_Customize_Radio_Image_Control' ) ) {
                        $wp_customize->add_control( new AquaLuxe_Customize_Radio_Image_Control( $wp_customize, $control_id, $control_args ) );
                    } else {
                        $wp_customize->add_control( $control_id, $control_args );
                    }
                    break;

                case 'color_palette':
                    if ( class_exists( 'AquaLuxe_Customize_Color_Palette_Control' ) ) {
                        $wp_customize->add_control( new AquaLuxe_Customize_Color_Palette_Control( $wp_customize, $control_id, $control_args ) );
                    } else {
                        $wp_customize->add_control( $control_id, $control_args );
                    }
                    break;

                default:
                    $wp_customize->add_control( $control_id, $control_args );
                    break;
            }
        }

        // Add selective refresh support
        $this->add_selective_refresh( $wp_customize );
    }

    /**
     * Register panels
     */
    public function register_panels() {
        $this->panels = array(
            'aqualuxe_theme_options' => array(
                'title'    => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
                'priority' => 10,
            ),
            'aqualuxe_header_options' => array(
                'title'    => __( 'Header Options', 'aqualuxe' ),
                'priority' => 20,
                'panel'    => 'aqualuxe_theme_options',
            ),
            'aqualuxe_footer_options' => array(
                'title'    => __( 'Footer Options', 'aqualuxe' ),
                'priority' => 30,
                'panel'    => 'aqualuxe_theme_options',
            ),
            'aqualuxe_blog_options' => array(
                'title'    => __( 'Blog Options', 'aqualuxe' ),
                'priority' => 40,
                'panel'    => 'aqualuxe_theme_options',
            ),
            'aqualuxe_styling_options' => array(
                'title'    => __( 'Styling Options', 'aqualuxe' ),
                'priority' => 50,
                'panel'    => 'aqualuxe_theme_options',
            ),
            'aqualuxe_typography_options' => array(
                'title'    => __( 'Typography Options', 'aqualuxe' ),
                'priority' => 60,
                'panel'    => 'aqualuxe_theme_options',
            ),
            'aqualuxe_woocommerce_options' => array(
                'title'    => __( 'WooCommerce Options', 'aqualuxe' ),
                'priority' => 70,
                'panel'    => 'aqualuxe_theme_options',
                'active_callback' => function() {
                    return class_exists( 'WooCommerce' );
                },
            ),
        );

        // Apply filters to panels
        $this->panels = apply_filters( 'aqualuxe_customizer_panels', $this->panels );
    }

    /**
     * Register sections
     */
    public function register_sections() {
        $this->sections = array(
            // General
            'aqualuxe_general_options' => array(
                'title'    => __( 'General Options', 'aqualuxe' ),
                'priority' => 10,
                'panel'    => 'aqualuxe_theme_options',
            ),
            'aqualuxe_layout_options' => array(
                'title'    => __( 'Layout Options', 'aqualuxe' ),
                'priority' => 20,
                'panel'    => 'aqualuxe_theme_options',
            ),

            // Header
            'aqualuxe_header_general' => array(
                'title'    => __( 'General', 'aqualuxe' ),
                'priority' => 10,
                'panel'    => 'aqualuxe_header_options',
            ),
            'aqualuxe_header_main' => array(
                'title'    => __( 'Main Header', 'aqualuxe' ),
                'priority' => 20,
                'panel'    => 'aqualuxe_header_options',
            ),
            'aqualuxe_header_top_bar' => array(
                'title'    => __( 'Top Bar', 'aqualuxe' ),
                'priority' => 30,
                'panel'    => 'aqualuxe_header_options',
            ),
            'aqualuxe_header_mobile' => array(
                'title'    => __( 'Mobile Header', 'aqualuxe' ),
                'priority' => 40,
                'panel'    => 'aqualuxe_header_options',
            ),
            'aqualuxe_header_page_title' => array(
                'title'    => __( 'Page Title', 'aqualuxe' ),
                'priority' => 50,
                'panel'    => 'aqualuxe_header_options',
            ),

            // Footer
            'aqualuxe_footer_general' => array(
                'title'    => __( 'General', 'aqualuxe' ),
                'priority' => 10,
                'panel'    => 'aqualuxe_footer_options',
            ),
            'aqualuxe_footer_widgets' => array(
                'title'    => __( 'Footer Widgets', 'aqualuxe' ),
                'priority' => 20,
                'panel'    => 'aqualuxe_footer_options',
            ),
            'aqualuxe_footer_bottom' => array(
                'title'    => __( 'Footer Bottom', 'aqualuxe' ),
                'priority' => 30,
                'panel'    => 'aqualuxe_footer_options',
            ),

            // Blog
            'aqualuxe_blog_archive' => array(
                'title'    => __( 'Blog Archive', 'aqualuxe' ),
                'priority' => 10,
                'panel'    => 'aqualuxe_blog_options',
            ),
            'aqualuxe_blog_single' => array(
                'title'    => __( 'Single Post', 'aqualuxe' ),
                'priority' => 20,
                'panel'    => 'aqualuxe_blog_options',
            ),
            'aqualuxe_blog_featured' => array(
                'title'    => __( 'Featured Posts', 'aqualuxe' ),
                'priority' => 30,
                'panel'    => 'aqualuxe_blog_options',
            ),

            // Styling
            'aqualuxe_colors' => array(
                'title'    => __( 'Colors', 'aqualuxe' ),
                'priority' => 10,
                'panel'    => 'aqualuxe_styling_options',
            ),
            'aqualuxe_buttons' => array(
                'title'    => __( 'Buttons', 'aqualuxe' ),
                'priority' => 20,
                'panel'    => 'aqualuxe_styling_options',
            ),
            'aqualuxe_forms' => array(
                'title'    => __( 'Forms', 'aqualuxe' ),
                'priority' => 30,
                'panel'    => 'aqualuxe_styling_options',
            ),

            // Typography
            'aqualuxe_typography_general' => array(
                'title'    => __( 'General', 'aqualuxe' ),
                'priority' => 10,
                'panel'    => 'aqualuxe_typography_options',
            ),
            'aqualuxe_typography_headings' => array(
                'title'    => __( 'Headings', 'aqualuxe' ),
                'priority' => 20,
                'panel'    => 'aqualuxe_typography_options',
            ),
            'aqualuxe_typography_menu' => array(
                'title'    => __( 'Menu', 'aqualuxe' ),
                'priority' => 30,
                'panel'    => 'aqualuxe_typography_options',
            ),
            'aqualuxe_typography_buttons' => array(
                'title'    => __( 'Buttons', 'aqualuxe' ),
                'priority' => 40,
                'panel'    => 'aqualuxe_typography_options',
            ),

            // WooCommerce
            'aqualuxe_woocommerce_general' => array(
                'title'    => __( 'General', 'aqualuxe' ),
                'priority' => 10,
                'panel'    => 'aqualuxe_woocommerce_options',
                'active_callback' => function() {
                    return class_exists( 'WooCommerce' );
                },
            ),
            'aqualuxe_woocommerce_product_archive' => array(
                'title'    => __( 'Product Archive', 'aqualuxe' ),
                'priority' => 20,
                'panel'    => 'aqualuxe_woocommerce_options',
                'active_callback' => function() {
                    return class_exists( 'WooCommerce' );
                },
            ),
            'aqualuxe_woocommerce_single_product' => array(
                'title'    => __( 'Single Product', 'aqualuxe' ),
                'priority' => 30,
                'panel'    => 'aqualuxe_woocommerce_options',
                'active_callback' => function() {
                    return class_exists( 'WooCommerce' );
                },
            ),
            'aqualuxe_woocommerce_cart' => array(
                'title'    => __( 'Cart', 'aqualuxe' ),
                'priority' => 40,
                'panel'    => 'aqualuxe_woocommerce_options',
                'active_callback' => function() {
                    return class_exists( 'WooCommerce' );
                },
            ),
            'aqualuxe_woocommerce_checkout' => array(
                'title'    => __( 'Checkout', 'aqualuxe' ),
                'priority' => 50,
                'panel'    => 'aqualuxe_woocommerce_options',
                'active_callback' => function() {
                    return class_exists( 'WooCommerce' );
                },
            ),
        );

        // Apply filters to sections
        $this->sections = apply_filters( 'aqualuxe_customizer_sections', $this->sections );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // General settings
        $this->settings['aqualuxe_container_width'] = array(
            'default'           => 1140,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        );

        $this->settings['aqualuxe_enable_preloader'] = array(
            'default'           => false,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_enable_back_to_top'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        // Layout settings
        $this->settings['aqualuxe_site_layout'] = array(
            'default'           => 'full-width',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        );

        $this->settings['aqualuxe_content_layout'] = array(
            'default'           => 'right-sidebar',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        );

        $this->settings['aqualuxe_content_width'] = array(
            'default'           => 70,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        );

        // Header settings
        $this->settings['aqualuxe_header_style'] = array(
            'default'           => 'default',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        );

        $this->settings['aqualuxe_enable_sticky_header'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_enable_top_bar'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_enable_header_search'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_enable_header_cta'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_header_cta_text'] = array(
            'default'           => __( 'Get a Quote', 'aqualuxe' ),
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        );

        $this->settings['aqualuxe_header_cta_url'] = array(
            'default'           => '#',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        );

        $this->settings['aqualuxe_header_cta_target'] = array(
            'default'           => false,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_header_height'] = array(
            'default'           => 80,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        );

        $this->settings['aqualuxe_mobile_header_height'] = array(
            'default'           => 60,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        );

        $this->settings['aqualuxe_header_background'] = array(
            'default'           => '#ffffff',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_header_text_color'] = array(
            'default'           => '#333333',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        // Footer settings
        $this->settings['aqualuxe_footer_style'] = array(
            'default'           => 'default',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        );

        $this->settings['aqualuxe_footer_widgets'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_footer_widgets_columns'] = array(
            'default'           => 4,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        );

        $this->settings['aqualuxe_footer_background'] = array(
            'default'           => '#f8f9fa',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_footer_text_color'] = array(
            'default'           => '#333333',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_footer_heading_color'] = array(
            'default'           => '#212529',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_footer_link_color'] = array(
            'default'           => '#0073aa',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_copyright_text'] = array(
            'default'           => sprintf( __( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ),
            'transport'         => 'postMessage',
            'sanitize_callback' => 'wp_kses_post',
        );

        // Blog settings
        $this->settings['aqualuxe_blog_layout'] = array(
            'default'           => 'grid',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        );

        $this->settings['aqualuxe_blog_columns'] = array(
            'default'           => 2,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        );

        $this->settings['aqualuxe_excerpt_length'] = array(
            'default'           => 20,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        );

        $this->settings['aqualuxe_read_more_text'] = array(
            'default'           => __( 'Read More', 'aqualuxe' ),
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        );

        $this->settings['aqualuxe_show_post_thumbnail'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_show_post_meta'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_show_post_author'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_show_post_date'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_show_post_comments'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_show_post_categories'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_show_post_tags'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_show_author_bio'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_show_post_nav'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_show_related_posts'] = array(
            'default'           => true,
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        );

        $this->settings['aqualuxe_related_posts_count'] = array(
            'default'           => 3,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        );

        // Colors
        $this->settings['aqualuxe_primary_color'] = array(
            'default'           => '#0073aa',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_secondary_color'] = array(
            'default'           => '#005177',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_accent_color'] = array(
            'default'           => '#f7a100',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_text_color'] = array(
            'default'           => '#333333',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_heading_color'] = array(
            'default'           => '#212529',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_link_color'] = array(
            'default'           => '#0073aa',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_link_hover_color'] = array(
            'default'           => '#005177',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        $this->settings['aqualuxe_body_background'] = array(
            'default'           => '#ffffff',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        );

        // Typography
        $this->settings['aqualuxe_body_font_family'] = array(
            'default'           => 'Inter',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        );

        $this->settings['aqualuxe_body_font_size'] = array(
            'default'           => 16,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        );

        $this->settings['aqualuxe_body_line_height'] = array(
            'default'           => 1.5,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_float',
        );

        $this->settings['aqualuxe_body_font_weight'] = array(
            'default'           => '400',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        );

        $this->settings['aqualuxe_headings_font_family'] = array(
            'default'           => 'Inter',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        );

        $this->settings['aqualuxe_headings_font_weight'] = array(
            'default'           => '700',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        );

        $this->settings['aqualuxe_headings_line_height'] = array(
            'default'           => 1.2,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'aqualuxe_sanitize_float',
        );

        $this->settings['aqualuxe_menu_font_family'] = array(
            'default'           => 'Inter',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        );

        $this->settings['aqualuxe_menu_font_size'] = array(
            'default'           => 16,
            'transport'         => 'postMessage',
            'sanitize_callback' => 'absint',
        );

        $this->settings['aqualuxe_menu_font_weight'] = array(
            'default'           => '500',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        );

        $this->settings['aqualuxe_menu_text_transform'] = array(
            'default'           => 'none',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        );

        // WooCommerce
        if ( class_exists( 'WooCommerce' ) ) {
            $this->settings['aqualuxe_products_per_page'] = array(
                'default'           => 12,
                'transport'         => 'refresh',
                'sanitize_callback' => 'absint',
            );

            $this->settings['aqualuxe_product_columns'] = array(
                'default'           => 3,
                'transport'         => 'refresh',
                'sanitize_callback' => 'absint',
            );

            $this->settings['aqualuxe_enable_shop_sidebar'] = array(
                'default'           => true,
                'transport'         => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            );

            $this->settings['aqualuxe_enable_product_sidebar'] = array(
                'default'           => false,
                'transport'         => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            );

            $this->settings['aqualuxe_shop_layout'] = array(
                'default'           => 'grid',
                'transport'         => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            );

            $this->settings['aqualuxe_product_hover_style'] = array(
                'default'           => 'zoom',
                'transport'         => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            );

            $this->settings['aqualuxe_enable_quick_view'] = array(
                'default'           => true,
                'transport'         => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            );

            $this->settings['aqualuxe_enable_wishlist'] = array(
                'default'           => true,
                'transport'         => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            );

            $this->settings['aqualuxe_enable_ajax_add_to_cart'] = array(
                'default'           => true,
                'transport'         => 'refresh',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            );
        }

        // Apply filters to settings
        $this->settings = apply_filters( 'aqualuxe_customizer_settings', $this->settings );
    }

    /**
     * Register controls
     */
    public function register_controls() {
        // General
        $this->controls['aqualuxe_container_width'] = array(
            'type'        => 'range',
            'section'     => 'aqualuxe_general_options',
            'label'       => __( 'Container Width (px)', 'aqualuxe' ),
            'description' => __( 'Set the width of the main container.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_container_width',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1600,
                'step' => 10,
            ),
        );

        $this->controls['aqualuxe_enable_preloader'] = array(
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_general_options',
            'label'       => __( 'Enable Preloader', 'aqualuxe' ),
            'description' => __( 'Show a loading animation until the page is ready.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_enable_preloader',
        );

        $this->controls['aqualuxe_enable_back_to_top'] = array(
            'type'        => 'checkbox',
            'section'     => 'aqualuxe_general_options',
            'label'       => __( 'Enable Back to Top Button', 'aqualuxe' ),
            'description' => __( 'Show a button to scroll back to the top of the page.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_enable_back_to_top',
        );

        // Layout
        $this->controls['aqualuxe_site_layout'] = array(
            'type'        => 'radio_image',
            'section'     => 'aqualuxe_layout_options',
            'label'       => __( 'Site Layout', 'aqualuxe' ),
            'description' => __( 'Choose the layout for your site.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_site_layout',
            'choices'     => array(
                'full-width' => array(
                    'label' => __( 'Full Width', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/layout-full-width.png',
                ),
                'boxed'      => array(
                    'label' => __( 'Boxed', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/layout-boxed.png',
                ),
                'framed'     => array(
                    'label' => __( 'Framed', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/layout-framed.png',
                ),
            ),
        );

        $this->controls['aqualuxe_content_layout'] = array(
            'type'        => 'radio_image',
            'section'     => 'aqualuxe_layout_options',
            'label'       => __( 'Content Layout', 'aqualuxe' ),
            'description' => __( 'Choose the layout for your content.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_content_layout',
            'choices'     => array(
                'right-sidebar' => array(
                    'label' => __( 'Right Sidebar', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/layout-right-sidebar.png',
                ),
                'left-sidebar'  => array(
                    'label' => __( 'Left Sidebar', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/layout-left-sidebar.png',
                ),
                'no-sidebar'    => array(
                    'label' => __( 'No Sidebar', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/layout-no-sidebar.png',
                ),
            ),
        );

        $this->controls['aqualuxe_content_width'] = array(
            'type'        => 'range',
            'section'     => 'aqualuxe_layout_options',
            'label'       => __( 'Content Width (%)', 'aqualuxe' ),
            'description' => __( 'Set the width of the content area when sidebar is enabled.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_content_width',
            'input_attrs' => array(
                'min'  => 50,
                'max'  => 85,
                'step' => 1,
            ),
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' ) !== 'no-sidebar';
            },
        );

        // Header
        $this->controls['aqualuxe_header_style'] = array(
            'type'        => 'radio_image',
            'section'     => 'aqualuxe_header_general',
            'label'       => __( 'Header Style', 'aqualuxe' ),
            'description' => __( 'Choose the style for your header.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_header_style',
            'choices'     => array(
                'default'     => array(
                    'label' => __( 'Default', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/header-default.png',
                ),
                'centered'    => array(
                    'label' => __( 'Centered', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/header-centered.png',
                ),
                'transparent' => array(
                    'label' => __( 'Transparent', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/header-transparent.png',
                ),
                'minimal'     => array(
                    'label' => __( 'Minimal', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/header-minimal.png',
                ),
                'split'       => array(
                    'label' => __( 'Split', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/header-split.png',
                ),
                'stacked'     => array(
                    'label' => __( 'Stacked', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/header-stacked.png',
                ),
            ),
        );

        $this->controls['aqualuxe_enable_sticky_header'] = array(
            'type'        => 'toggle',
            'section'     => 'aqualuxe_header_general',
            'label'       => __( 'Enable Sticky Header', 'aqualuxe' ),
            'description' => __( 'Keep the header visible when scrolling down.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_enable_sticky_header',
        );

        $this->controls['aqualuxe_enable_top_bar'] = array(
            'type'        => 'toggle',
            'section'     => 'aqualuxe_header_top_bar',
            'label'       => __( 'Enable Top Bar', 'aqualuxe' ),
            'description' => __( 'Show a top bar above the main header.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_enable_top_bar',
        );

        $this->controls['aqualuxe_enable_header_search'] = array(
            'type'        => 'toggle',
            'section'     => 'aqualuxe_header_main',
            'label'       => __( 'Enable Header Search', 'aqualuxe' ),
            'description' => __( 'Show a search icon in the header.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_enable_header_search',
        );

        $this->controls['aqualuxe_enable_header_cta'] = array(
            'type'        => 'toggle',
            'section'     => 'aqualuxe_header_main',
            'label'       => __( 'Enable Header CTA Button', 'aqualuxe' ),
            'description' => __( 'Show a call-to-action button in the header.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_enable_header_cta',
        );

        $this->controls['aqualuxe_header_cta_text'] = array(
            'type'            => 'text',
            'section'         => 'aqualuxe_header_main',
            'label'           => __( 'CTA Button Text', 'aqualuxe' ),
            'settings'        => 'aqualuxe_header_cta_text',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_enable_header_cta', true );
            },
        );

        $this->controls['aqualuxe_header_cta_url'] = array(
            'type'            => 'url',
            'section'         => 'aqualuxe_header_main',
            'label'           => __( 'CTA Button URL', 'aqualuxe' ),
            'settings'        => 'aqualuxe_header_cta_url',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_enable_header_cta', true );
            },
        );

        $this->controls['aqualuxe_header_cta_target'] = array(
            'type'            => 'checkbox',
            'section'         => 'aqualuxe_header_main',
            'label'           => __( 'Open CTA in New Tab', 'aqualuxe' ),
            'settings'        => 'aqualuxe_header_cta_target',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_enable_header_cta', true );
            },
        );

        $this->controls['aqualuxe_header_height'] = array(
            'type'        => 'range',
            'section'     => 'aqualuxe_header_main',
            'label'       => __( 'Header Height (px)', 'aqualuxe' ),
            'settings'    => 'aqualuxe_header_height',
            'input_attrs' => array(
                'min'  => 50,
                'max'  => 200,
                'step' => 5,
            ),
        );

        $this->controls['aqualuxe_mobile_header_height'] = array(
            'type'        => 'range',
            'section'     => 'aqualuxe_header_mobile',
            'label'       => __( 'Mobile Header Height (px)', 'aqualuxe' ),
            'settings'    => 'aqualuxe_mobile_header_height',
            'input_attrs' => array(
                'min'  => 40,
                'max'  => 150,
                'step' => 5,
            ),
        );

        $this->controls['aqualuxe_header_background'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_header_main',
            'label'    => __( 'Header Background Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_header_background',
        );

        $this->controls['aqualuxe_header_text_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_header_main',
            'label'    => __( 'Header Text Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_header_text_color',
        );

        // Footer
        $this->controls['aqualuxe_footer_style'] = array(
            'type'        => 'radio_image',
            'section'     => 'aqualuxe_footer_general',
            'label'       => __( 'Footer Style', 'aqualuxe' ),
            'description' => __( 'Choose the style for your footer.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_footer_style',
            'choices'     => array(
                'default'   => array(
                    'label' => __( 'Default', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/footer-default.png',
                ),
                'centered'  => array(
                    'label' => __( 'Centered', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/footer-centered.png',
                ),
                'dark'      => array(
                    'label' => __( 'Dark', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/footer-dark.png',
                ),
                'light'     => array(
                    'label' => __( 'Light', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/footer-light.png',
                ),
                'minimal'   => array(
                    'label' => __( 'Minimal', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/footer-minimal.png',
                ),
            ),
        );

        $this->controls['aqualuxe_footer_widgets'] = array(
            'type'        => 'toggle',
            'section'     => 'aqualuxe_footer_widgets',
            'label'       => __( 'Enable Footer Widgets', 'aqualuxe' ),
            'description' => __( 'Show widget areas in the footer.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_footer_widgets',
        );

        $this->controls['aqualuxe_footer_widgets_columns'] = array(
            'type'            => 'select',
            'section'         => 'aqualuxe_footer_widgets',
            'label'           => __( 'Footer Widgets Columns', 'aqualuxe' ),
            'description'     => __( 'Choose the number of widget columns in the footer.', 'aqualuxe' ),
            'settings'        => 'aqualuxe_footer_widgets_columns',
            'choices'         => array(
                1 => __( '1 Column', 'aqualuxe' ),
                2 => __( '2 Columns', 'aqualuxe' ),
                3 => __( '3 Columns', 'aqualuxe' ),
                4 => __( '4 Columns', 'aqualuxe' ),
                5 => __( '5 Columns', 'aqualuxe' ),
                6 => __( '6 Columns', 'aqualuxe' ),
            ),
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_footer_widgets', true );
            },
        );

        $this->controls['aqualuxe_footer_background'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_footer_general',
            'label'    => __( 'Footer Background Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_footer_background',
        );

        $this->controls['aqualuxe_footer_text_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_footer_general',
            'label'    => __( 'Footer Text Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_footer_text_color',
        );

        $this->controls['aqualuxe_footer_heading_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_footer_general',
            'label'    => __( 'Footer Heading Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_footer_heading_color',
        );

        $this->controls['aqualuxe_footer_link_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_footer_general',
            'label'    => __( 'Footer Link Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_footer_link_color',
        );

        $this->controls['aqualuxe_copyright_text'] = array(
            'type'     => 'textarea',
            'section'  => 'aqualuxe_footer_bottom',
            'label'    => __( 'Copyright Text', 'aqualuxe' ),
            'settings' => 'aqualuxe_copyright_text',
        );

        // Blog
        $this->controls['aqualuxe_blog_layout'] = array(
            'type'        => 'radio_image',
            'section'     => 'aqualuxe_blog_archive',
            'label'       => __( 'Blog Layout', 'aqualuxe' ),
            'description' => __( 'Choose the layout for your blog archive.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_blog_layout',
            'choices'     => array(
                'grid'    => array(
                    'label' => __( 'Grid', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/blog-grid.png',
                ),
                'list'    => array(
                    'label' => __( 'List', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/blog-list.png',
                ),
                'masonry' => array(
                    'label' => __( 'Masonry', 'aqualuxe' ),
                    'url'   => AQUALUXE_ASSETS_URI . 'images/admin/blog-masonry.png',
                ),
            ),
        );

        $this->controls['aqualuxe_blog_columns'] = array(
            'type'            => 'select',
            'section'         => 'aqualuxe_blog_archive',
            'label'           => __( 'Blog Columns', 'aqualuxe' ),
            'description'     => __( 'Choose the number of columns for grid and masonry layouts.', 'aqualuxe' ),
            'settings'        => 'aqualuxe_blog_columns',
            'choices'         => array(
                1 => __( '1 Column', 'aqualuxe' ),
                2 => __( '2 Columns', 'aqualuxe' ),
                3 => __( '3 Columns', 'aqualuxe' ),
                4 => __( '4 Columns', 'aqualuxe' ),
            ),
            'active_callback' => function() {
                $layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
                return $layout === 'grid' || $layout === 'masonry';
            },
        );

        $this->controls['aqualuxe_excerpt_length'] = array(
            'type'        => 'number',
            'section'     => 'aqualuxe_blog_archive',
            'label'       => __( 'Excerpt Length', 'aqualuxe' ),
            'description' => __( 'Number of words to show in the excerpt.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_excerpt_length',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 100,
                'step' => 5,
            ),
        );

        $this->controls['aqualuxe_read_more_text'] = array(
            'type'     => 'text',
            'section'  => 'aqualuxe_blog_archive',
            'label'    => __( 'Read More Text', 'aqualuxe' ),
            'settings' => 'aqualuxe_read_more_text',
        );

        $this->controls['aqualuxe_show_post_thumbnail'] = array(
            'type'     => 'toggle',
            'section'  => 'aqualuxe_blog_archive',
            'label'    => __( 'Show Featured Image', 'aqualuxe' ),
            'settings' => 'aqualuxe_show_post_thumbnail',
        );

        $this->controls['aqualuxe_show_post_meta'] = array(
            'type'     => 'toggle',
            'section'  => 'aqualuxe_blog_archive',
            'label'    => __( 'Show Post Meta', 'aqualuxe' ),
            'settings' => 'aqualuxe_show_post_meta',
        );

        $this->controls['aqualuxe_show_post_author'] = array(
            'type'            => 'toggle',
            'section'         => 'aqualuxe_blog_archive',
            'label'           => __( 'Show Author', 'aqualuxe' ),
            'settings'        => 'aqualuxe_show_post_author',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_show_post_meta', true );
            },
        );

        $this->controls['aqualuxe_show_post_date'] = array(
            'type'            => 'toggle',
            'section'         => 'aqualuxe_blog_archive',
            'label'           => __( 'Show Date', 'aqualuxe' ),
            'settings'        => 'aqualuxe_show_post_date',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_show_post_meta', true );
            },
        );

        $this->controls['aqualuxe_show_post_comments'] = array(
            'type'            => 'toggle',
            'section'         => 'aqualuxe_blog_archive',
            'label'           => __( 'Show Comments Count', 'aqualuxe' ),
            'settings'        => 'aqualuxe_show_post_comments',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_show_post_meta', true );
            },
        );

        $this->controls['aqualuxe_show_post_categories'] = array(
            'type'            => 'toggle',
            'section'         => 'aqualuxe_blog_archive',
            'label'           => __( 'Show Categories', 'aqualuxe' ),
            'settings'        => 'aqualuxe_show_post_categories',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_show_post_meta', true );
            },
        );

        $this->controls['aqualuxe_show_post_tags'] = array(
            'type'     => 'toggle',
            'section'  => 'aqualuxe_blog_single',
            'label'    => __( 'Show Tags', 'aqualuxe' ),
            'settings' => 'aqualuxe_show_post_tags',
        );

        $this->controls['aqualuxe_show_author_bio'] = array(
            'type'     => 'toggle',
            'section'  => 'aqualuxe_blog_single',
            'label'    => __( 'Show Author Bio', 'aqualuxe' ),
            'settings' => 'aqualuxe_show_author_bio',
        );

        $this->controls['aqualuxe_show_post_nav'] = array(
            'type'     => 'toggle',
            'section'  => 'aqualuxe_blog_single',
            'label'    => __( 'Show Post Navigation', 'aqualuxe' ),
            'settings' => 'aqualuxe_show_post_nav',
        );

        $this->controls['aqualuxe_show_related_posts'] = array(
            'type'     => 'toggle',
            'section'  => 'aqualuxe_blog_single',
            'label'    => __( 'Show Related Posts', 'aqualuxe' ),
            'settings' => 'aqualuxe_show_related_posts',
        );

        $this->controls['aqualuxe_related_posts_count'] = array(
            'type'            => 'number',
            'section'         => 'aqualuxe_blog_single',
            'label'           => __( 'Related Posts Count', 'aqualuxe' ),
            'settings'        => 'aqualuxe_related_posts_count',
            'input_attrs'     => array(
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ),
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_show_related_posts', true );
            },
        );

        // Colors
        $this->controls['aqualuxe_primary_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_colors',
            'label'    => __( 'Primary Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_primary_color',
        );

        $this->controls['aqualuxe_secondary_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_colors',
            'label'    => __( 'Secondary Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_secondary_color',
        );

        $this->controls['aqualuxe_accent_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_colors',
            'label'    => __( 'Accent Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_accent_color',
        );

        $this->controls['aqualuxe_text_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_colors',
            'label'    => __( 'Text Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_text_color',
        );

        $this->controls['aqualuxe_heading_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_colors',
            'label'    => __( 'Heading Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_heading_color',
        );

        $this->controls['aqualuxe_link_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_colors',
            'label'    => __( 'Link Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_link_color',
        );

        $this->controls['aqualuxe_link_hover_color'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_colors',
            'label'    => __( 'Link Hover Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_link_hover_color',
        );

        $this->controls['aqualuxe_body_background'] = array(
            'type'     => 'color',
            'section'  => 'aqualuxe_colors',
            'label'    => __( 'Body Background Color', 'aqualuxe' ),
            'settings' => 'aqualuxe_body_background',
        );

        // Typography
        $this->controls['aqualuxe_body_font_family'] = array(
            'type'        => 'typography',
            'section'     => 'aqualuxe_typography_general',
            'label'       => __( 'Body Font Family', 'aqualuxe' ),
            'description' => __( 'Select the font family for the body text.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_body_font_family',
            'choices'     => aqualuxe_get_google_fonts(),
        );

        $this->controls['aqualuxe_body_font_size'] = array(
            'type'        => 'range',
            'section'     => 'aqualuxe_typography_general',
            'label'       => __( 'Body Font Size (px)', 'aqualuxe' ),
            'settings'    => 'aqualuxe_body_font_size',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        );

        $this->controls['aqualuxe_body_line_height'] = array(
            'type'        => 'range',
            'section'     => 'aqualuxe_typography_general',
            'label'       => __( 'Body Line Height', 'aqualuxe' ),
            'settings'    => 'aqualuxe_body_line_height',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 2,
                'step' => 0.1,
            ),
        );

        $this->controls['aqualuxe_body_font_weight'] = array(
            'type'     => 'select',
            'section'  => 'aqualuxe_typography_general',
            'label'    => __( 'Body Font Weight', 'aqualuxe' ),
            'settings' => 'aqualuxe_body_font_weight',
            'choices'  => array(
                '300' => __( 'Light (300)', 'aqualuxe' ),
                '400' => __( 'Regular (400)', 'aqualuxe' ),
                '500' => __( 'Medium (500)', 'aqualuxe' ),
                '600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
                '700' => __( 'Bold (700)', 'aqualuxe' ),
            ),
        );

        $this->controls['aqualuxe_headings_font_family'] = array(
            'type'        => 'typography',
            'section'     => 'aqualuxe_typography_headings',
            'label'       => __( 'Headings Font Family', 'aqualuxe' ),
            'description' => __( 'Select the font family for headings.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_headings_font_family',
            'choices'     => aqualuxe_get_google_fonts(),
        );

        $this->controls['aqualuxe_headings_font_weight'] = array(
            'type'     => 'select',
            'section'  => 'aqualuxe_typography_headings',
            'label'    => __( 'Headings Font Weight', 'aqualuxe' ),
            'settings' => 'aqualuxe_headings_font_weight',
            'choices'  => array(
                '300' => __( 'Light (300)', 'aqualuxe' ),
                '400' => __( 'Regular (400)', 'aqualuxe' ),
                '500' => __( 'Medium (500)', 'aqualuxe' ),
                '600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
                '700' => __( 'Bold (700)', 'aqualuxe' ),
            ),
        );

        $this->controls['aqualuxe_headings_line_height'] = array(
            'type'        => 'range',
            'section'     => 'aqualuxe_typography_headings',
            'label'       => __( 'Headings Line Height', 'aqualuxe' ),
            'settings'    => 'aqualuxe_headings_line_height',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 2,
                'step' => 0.1,
            ),
        );

        $this->controls['aqualuxe_menu_font_family'] = array(
            'type'        => 'typography',
            'section'     => 'aqualuxe_typography_menu',
            'label'       => __( 'Menu Font Family', 'aqualuxe' ),
            'description' => __( 'Select the font family for the menu.', 'aqualuxe' ),
            'settings'    => 'aqualuxe_menu_font_family',
            'choices'     => aqualuxe_get_google_fonts(),
        );

        $this->controls['aqualuxe_menu_font_size'] = array(
            'type'        => 'range',
            'section'     => 'aqualuxe_typography_menu',
            'label'       => __( 'Menu Font Size (px)', 'aqualuxe' ),
            'settings'    => 'aqualuxe_menu_font_size',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        );

        $this->controls['aqualuxe_menu_font_weight'] = array(
            'type'     => 'select',
            'section'  => 'aqualuxe_typography_menu',
            'label'    => __( 'Menu Font Weight', 'aqualuxe' ),
            'settings' => 'aqualuxe_menu_font_weight',
            'choices'  => array(
                '300' => __( 'Light (300)', 'aqualuxe' ),
                '400' => __( 'Regular (400)', 'aqualuxe' ),
                '500' => __( 'Medium (500)', 'aqualuxe' ),
                '600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
                '700' => __( 'Bold (700)', 'aqualuxe' ),
            ),
        );

        $this->controls['aqualuxe_menu_text_transform'] = array(
            'type'     => 'select',
            'section'  => 'aqualuxe_typography_menu',
            'label'    => __( 'Menu Text Transform', 'aqualuxe' ),
            'settings' => 'aqualuxe_menu_text_transform',
            'choices'  => array(
                'none'       => __( 'None', 'aqualuxe' ),
                'capitalize' => __( 'Capitalize', 'aqualuxe' ),
                'uppercase'  => __( 'Uppercase', 'aqualuxe' ),
                'lowercase'  => __( 'Lowercase', 'aqualuxe' ),
            ),
        );

        // WooCommerce
        if ( class_exists( 'WooCommerce' ) ) {
            $this->controls['aqualuxe_products_per_page'] = array(
                'type'        => 'number',
                'section'     => 'aqualuxe_woocommerce_general',
                'label'       => __( 'Products Per Page', 'aqualuxe' ),
                'description' => __( 'Number of products to display per page.', 'aqualuxe' ),
                'settings'    => 'aqualuxe_products_per_page',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 100,
                    'step' => 1,
                ),
            );

            $this->controls['aqualuxe_product_columns'] = array(
                'type'        => 'number',
                'section'     => 'aqualuxe_woocommerce_product_archive',
                'label'       => __( 'Product Columns', 'aqualuxe' ),
                'description' => __( 'Number of columns to display products in.', 'aqualuxe' ),
                'settings'    => 'aqualuxe_product_columns',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 6,
                    'step' => 1,
                ),
            );

            $this->controls['aqualuxe_enable_shop_sidebar'] = array(
                'type'     => 'toggle',
                'section'  => 'aqualuxe_woocommerce_product_archive',
                'label'    => __( 'Enable Shop Sidebar', 'aqualuxe' ),
                'settings' => 'aqualuxe_enable_shop_sidebar',
            );

            $this->controls['aqualuxe_enable_product_sidebar'] = array(
                'type'     => 'toggle',
                'section'  => 'aqualuxe_woocommerce_single_product',
                'label'    => __( 'Enable Product Sidebar', 'aqualuxe' ),
                'settings' => 'aqualuxe_enable_product_sidebar',
            );

            $this->controls['aqualuxe_shop_layout'] = array(
                'type'        => 'radio_image',
                'section'     => 'aqualuxe_woocommerce_product_archive',
                'label'       => __( 'Shop Layout', 'aqualuxe' ),
                'description' => __( 'Choose the layout for your shop.', 'aqualuxe' ),
                'settings'    => 'aqualuxe_shop_layout',
                'choices'     => array(
                    'grid'    => array(
                        'label' => __( 'Grid', 'aqualuxe' ),
                        'url'   => AQUALUXE_ASSETS_URI . 'images/admin/shop-grid.png',
                    ),
                    'list'    => array(
                        'label' => __( 'List', 'aqualuxe' ),
                        'url'   => AQUALUXE_ASSETS_URI . 'images/admin/shop-list.png',
                    ),
                    'masonry' => array(
                        'label' => __( 'Masonry', 'aqualuxe' ),
                        'url'   => AQUALUXE_ASSETS_URI . 'images/admin/shop-masonry.png',
                    ),
                ),
            );

            $this->controls['aqualuxe_product_hover_style'] = array(
                'type'        => 'select',
                'section'     => 'aqualuxe_woocommerce_product_archive',
                'label'       => __( 'Product Hover Style', 'aqualuxe' ),
                'description' => __( 'Choose the hover effect for products.', 'aqualuxe' ),
                'settings'    => 'aqualuxe_product_hover_style',
                'choices'     => array(
                    'none'     => __( 'None', 'aqualuxe' ),
                    'zoom'     => __( 'Zoom', 'aqualuxe' ),
                    'swap'     => __( 'Image Swap', 'aqualuxe' ),
                    'flip'     => __( 'Flip', 'aqualuxe' ),
                    'slide'    => __( 'Slide', 'aqualuxe' ),
                    'fade'     => __( 'Fade', 'aqualuxe' ),
                    'overlay'  => __( 'Overlay', 'aqualuxe' ),
                ),
            );

            $this->controls['aqualuxe_enable_quick_view'] = array(
                'type'     => 'toggle',
                'section'  => 'aqualuxe_woocommerce_product_archive',
                'label'    => __( 'Enable Quick View', 'aqualuxe' ),
                'settings' => 'aqualuxe_enable_quick_view',
            );

            $this->controls['aqualuxe_enable_wishlist'] = array(
                'type'     => 'toggle',
                'section'  => 'aqualuxe_woocommerce_product_archive',
                'label'    => __( 'Enable Wishlist', 'aqualuxe' ),
                'settings' => 'aqualuxe_enable_wishlist',
            );

            $this->controls['aqualuxe_enable_ajax_add_to_cart'] = array(
                'type'     => 'toggle',
                'section'  => 'aqualuxe_woocommerce_product_archive',
                'label'    => __( 'Enable AJAX Add to Cart', 'aqualuxe' ),
                'settings' => 'aqualuxe_enable_ajax_add_to_cart',
            );
        }

        // Apply filters to controls
        $this->controls = apply_filters( 'aqualuxe_customizer_controls', $this->controls );
    }

    /**
     * Add selective refresh support
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function add_selective_refresh( $wp_customize ) {
        // Site title and description
        $wp_customize->selective_refresh->add_partial( 'blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => function() {
                return get_bloginfo( 'name' );
            },
        ) );

        $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => function() {
                return get_bloginfo( 'description' );
            },
        ) );

        // Copyright text
        $wp_customize->selective_refresh->add_partial( 'aqualuxe_copyright_text', array(
            'selector'        => '.copyright-text',
            'render_callback' => function() {
                return get_theme_mod( 'aqualuxe_copyright_text', sprintf( __( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ) );
            },
        ) );

        // Header CTA
        $wp_customize->selective_refresh->add_partial( 'aqualuxe_header_cta_text', array(
            'selector'        => '.header-cta',
            'render_callback' => function() {
                $text   = get_theme_mod( 'aqualuxe_header_cta_text', __( 'Get a Quote', 'aqualuxe' ) );
                $url    = get_theme_mod( 'aqualuxe_header_cta_url', '#' );
                $target = get_theme_mod( 'aqualuxe_header_cta_target', false ) ? ' target="_blank"' : '';
                
                return '<a href="' . esc_url( $url ) . '" class="header-cta btn btn-primary"' . $target . '>' . esc_html( $text ) . '</a>';
            },
        ) );
    }

    /**
     * Enqueue preview scripts
     */
    public function preview_init() {
        wp_enqueue_script( 'aqualuxe-customizer-preview', AQUALUXE_ASSETS_URI . 'js/customizer.js', array( 'customize-preview', 'jquery' ), AQUALUXE_VERSION, true );
    }

    /**
     * Enqueue controls scripts
     */
    public function controls_scripts() {
        wp_enqueue_script( 'aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'js/customizer-controls.js', array( 'customize-controls', 'jquery' ), AQUALUXE_VERSION, true );
        
        // Pass data to script
        wp_localize_script( 'aqualuxe-customizer-controls', 'aqualuxeCustomizer', array(
            'fontWeights'     => aqualuxe_get_font_weights(),
            'fontWeightNames' => aqualuxe_get_font_weight_names(),
            'googleFonts'     => array_keys( aqualuxe_get_google_fonts() ),
        ) );
    }

    /**
     * Output custom styles
     */
    public function output_styles() {
        $css = '';

        // Container width
        $container_width = get_theme_mod( 'aqualuxe_container_width', 1140 );
        $css .= ':root { --aqualuxe-container-width: ' . esc_attr( $container_width ) . 'px; }';

        // Colors
        $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
        $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#005177' );
        $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#f7a100' );
        $text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
        $heading_color = get_theme_mod( 'aqualuxe_heading_color', '#212529' );
        $link_color = get_theme_mod( 'aqualuxe_link_color', '#0073aa' );
        $link_hover_color = get_theme_mod( 'aqualuxe_link_hover_color', '#005177' );
        $body_background = get_theme_mod( 'aqualuxe_body_background', '#ffffff' );

        $css .= ':root {';
        $css .= '--aqualuxe-primary-color: ' . esc_attr( $primary_color ) . ';';
        $css .= '--aqualuxe-secondary-color: ' . esc_attr( $secondary_color ) . ';';
        $css .= '--aqualuxe-accent-color: ' . esc_attr( $accent_color ) . ';';
        $css .= '--aqualuxe-text-color: ' . esc_attr( $text_color ) . ';';
        $css .= '--aqualuxe-heading-color: ' . esc_attr( $heading_color ) . ';';
        $css .= '--aqualuxe-link-color: ' . esc_attr( $link_color ) . ';';
        $css .= '--aqualuxe-link-hover-color: ' . esc_attr( $link_hover_color ) . ';';
        $css .= '}';

        $css .= 'body { background-color: ' . esc_attr( $body_background ) . '; }';

        // Header
        $header_background = get_theme_mod( 'aqualuxe_header_background', '#ffffff' );
        $header_text_color = get_theme_mod( 'aqualuxe_header_text_color', '#333333' );
        $header_height = get_theme_mod( 'aqualuxe_header_height', 80 );
        $mobile_header_height = get_theme_mod( 'aqualuxe_mobile_header_height', 60 );

        $css .= '.site-header {';
        $css .= 'background-color: ' . esc_attr( $header_background ) . ';';
        $css .= 'color: ' . esc_attr( $header_text_color ) . ';';
        $css .= 'height: ' . esc_attr( $header_height ) . 'px;';
        $css .= '}';

        $css .= '@media (max-width: 991px) {';
        $css .= '.site-header {';
        $css .= 'height: ' . esc_attr( $mobile_header_height ) . 'px;';
        $css .= '}';
        $css .= '}';

        // Footer
        $footer_background = get_theme_mod( 'aqualuxe_footer_background', '#f8f9fa' );
        $footer_text_color = get_theme_mod( 'aqualuxe_footer_text_color', '#333333' );
        $footer_heading_color = get_theme_mod( 'aqualuxe_footer_heading_color', '#212529' );
        $footer_link_color = get_theme_mod( 'aqualuxe_footer_link_color', '#0073aa' );

        $css .= '.site-footer {';
        $css .= 'background-color: ' . esc_attr( $footer_background ) . ';';
        $css .= 'color: ' . esc_attr( $footer_text_color ) . ';';
        $css .= '}';

        $css .= '.site-footer h1, .site-footer h2, .site-footer h3, .site-footer h4, .site-footer h5, .site-footer h6 {';
        $css .= 'color: ' . esc_attr( $footer_heading_color ) . ';';
        $css .= '}';

        $css .= '.site-footer a {';
        $css .= 'color: ' . esc_attr( $footer_link_color ) . ';';
        $css .= '}';

        // Typography
        $body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Inter' );
        $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', 16 );
        $body_line_height = get_theme_mod( 'aqualuxe_body_line_height', 1.5 );
        $body_font_weight = get_theme_mod( 'aqualuxe_body_font_weight', '400' );
        $headings_font_family = get_theme_mod( 'aqualuxe_headings_font_family', 'Inter' );
        $headings_font_weight = get_theme_mod( 'aqualuxe_headings_font_weight', '700' );
        $headings_line_height = get_theme_mod( 'aqualuxe_headings_line_height', 1.2 );
        $menu_font_family = get_theme_mod( 'aqualuxe_menu_font_family', 'Inter' );
        $menu_font_size = get_theme_mod( 'aqualuxe_menu_font_size', 16 );
        $menu_font_weight = get_theme_mod( 'aqualuxe_menu_font_weight', '500' );
        $menu_text_transform = get_theme_mod( 'aqualuxe_menu_text_transform', 'none' );

        $css .= ':root {';
        $css .= '--aqualuxe-body-font: "' . esc_attr( $body_font_family ) . '", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;';
        $css .= '--aqualuxe-headings-font: "' . esc_attr( $headings_font_family ) . '", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;';
        $css .= '--aqualuxe-body-font-size: ' . esc_attr( $body_font_size ) . 'px;';
        $css .= '--aqualuxe-body-line-height: ' . esc_attr( $body_line_height ) . ';';
        $css .= '--aqualuxe-font-weight-normal: ' . esc_attr( $body_font_weight ) . ';';
        $css .= '--aqualuxe-font-weight-bold: ' . esc_attr( $headings_font_weight ) . ';';
        $css .= '}';

        $css .= 'body {';
        $css .= 'font-family: var(--aqualuxe-body-font);';
        $css .= 'font-size: var(--aqualuxe-body-font-size);';
        $css .= 'line-height: var(--aqualuxe-body-line-height);';
        $css .= 'font-weight: var(--aqualuxe-font-weight-normal);';
        $css .= '}';

        $css .= 'h1, h2, h3, h4, h5, h6 {';
        $css .= 'font-family: var(--aqualuxe-headings-font);';
        $css .= 'font-weight: var(--aqualuxe-font-weight-bold);';
        $css .= 'line-height: ' . esc_attr( $headings_line_height ) . ';';
        $css .= '}';

        $css .= '.main-navigation .menu > li > a {';
        $css .= 'font-family: "' . esc_attr( $menu_font_family ) . '", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;';
        $css .= 'font-size: ' . esc_attr( $menu_font_size ) . 'px;';
        $css .= 'font-weight: ' . esc_attr( $menu_font_weight ) . ';';
        $css .= 'text-transform: ' . esc_attr( $menu_text_transform ) . ';';
        $css .= '}';

        // Output the CSS
        if ( ! empty( $css ) ) {
            echo '<style id="aqualuxe-customizer-styles">' . $css . '</style>';
        }
    }
}

// Initialize the customizer
AquaLuxe_Customizer::get_instance();

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize select
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize hex color
 *
 * @param string $color The color to sanitize.
 * @return string Sanitized color.
 */
function aqualuxe_sanitize_hex_color( $color ) {
    if ( '' === $color ) {
        return '';
    }
    
    // 3 or 6 hex digits, or the empty string.
    if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
        return $color;
    }
    
    return '';
}

/**
 * Sanitize float
 *
 * @param float $input The input to sanitize.
 * @return float Sanitized input.
 */
function aqualuxe_sanitize_float( $input ) {
    return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}

/**
 * Get Google fonts
 *
 * @return array Google fonts.
 */
function aqualuxe_get_google_fonts() {
    return array(
        'Inter'         => 'Inter',
        'Roboto'        => 'Roboto',
        'Open Sans'     => 'Open Sans',
        'Lato'          => 'Lato',
        'Montserrat'    => 'Montserrat',
        'Poppins'       => 'Poppins',
        'Raleway'       => 'Raleway',
        'Nunito'        => 'Nunito',
        'Playfair Display' => 'Playfair Display',
        'Merriweather'  => 'Merriweather',
        'Source Sans Pro' => 'Source Sans Pro',
        'Rubik'         => 'Rubik',
        'Work Sans'     => 'Work Sans',
        'Mulish'        => 'Mulish',
        'Nunito Sans'   => 'Nunito Sans',
        'Quicksand'     => 'Quicksand',
        'Karla'         => 'Karla',
        'Josefin Sans'  => 'Josefin Sans',
        'Fira Sans'     => 'Fira Sans',
        'Ubuntu'        => 'Ubuntu',
    );
}

/**
 * Get font weights
 *
 * @return array Font weights.
 */
function aqualuxe_get_font_weights() {
    return array(
        'Inter'         => array( '300', '400', '500', '600', '700' ),
        'Roboto'        => array( '300', '400', '500', '700' ),
        'Open Sans'     => array( '300', '400', '600', '700' ),
        'Lato'          => array( '300', '400', '700' ),
        'Montserrat'    => array( '300', '400', '500', '600', '700' ),
        'Poppins'       => array( '300', '400', '500', '600', '700' ),
        'Raleway'       => array( '300', '400', '500', '600', '700' ),
        'Nunito'        => array( '300', '400', '600', '700' ),
        'Playfair Display' => array( '400', '500', '600', '700' ),
        'Merriweather'  => array( '300', '400', '700' ),
        'Source Sans Pro' => array( '300', '400', '600', '700' ),
        'Rubik'         => array( '300', '400', '500', '600', '700' ),
        'Work Sans'     => array( '300', '400', '500', '600', '700' ),
        'Mulish'        => array( '300', '400', '500', '600', '700' ),
        'Nunito Sans'   => array( '300', '400', '600', '700' ),
        'Quicksand'     => array( '300', '400', '500', '600', '700' ),
        'Karla'         => array( '400', '500', '600', '700' ),
        'Josefin Sans'  => array( '300', '400', '500', '600', '700' ),
        'Fira Sans'     => array( '300', '400', '500', '600', '700' ),
        'Ubuntu'        => array( '300', '400', '500', '700' ),
    );
}

/**
 * Get font weight names
 *
 * @return array Font weight names.
 */
function aqualuxe_get_font_weight_names() {
    return array(
        '300' => __( 'Light (300)', 'aqualuxe' ),
        '400' => __( 'Regular (400)', 'aqualuxe' ),
        '500' => __( 'Medium (500)', 'aqualuxe' ),
        '600' => __( 'Semi-Bold (600)', 'aqualuxe' ),
        '700' => __( 'Bold (700)', 'aqualuxe' ),
    );
}