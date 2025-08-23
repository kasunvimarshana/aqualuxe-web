<?php
/**
 * Customizer Class
 *
 * @package AquaLuxe
 * @subpackage Customizer
 * @since 1.0.0
 */

namespace AquaLuxe\Customizer;

/**
 * Customizer Class
 * 
 * This class handles theme customization options.
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
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        add_action( 'customize_register', [ $this, 'register_customizer_sections' ] );
        add_action( 'customize_preview_init', [ $this, 'enqueue_customizer_preview_js' ] );
        add_action( 'wp_head', [ $this, 'output_customizer_css' ] );
    }

    /**
     * Register customizer sections
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    public function register_customizer_sections( $wp_customize ) {
        // Load customizer sections
        $this->load_customizer_sections();
        
        // Register sections
        $this->register_general_section( $wp_customize );
        $this->register_header_section( $wp_customize );
        $this->register_footer_section( $wp_customize );
        $this->register_colors_section( $wp_customize );
        $this->register_typography_section( $wp_customize );
        $this->register_layout_section( $wp_customize );
        $this->register_blog_section( $wp_customize );
        $this->register_social_section( $wp_customize );
        
        // Register WooCommerce section if active
        if ( class_exists( 'WooCommerce' ) ) {
            $this->register_woocommerce_section( $wp_customize );
        }
        
        // Allow modules to register their customizer sections
        do_action( 'aqualuxe_register_customizer_sections', $wp_customize );
    }

    /**
     * Load customizer sections
     *
     * @return void
     */
    private function load_customizer_sections() {
        $sections = [
            'general',
            'header',
            'footer',
            'colors',
            'typography',
            'layout',
            'blog',
            'social',
        ];
        
        // Load WooCommerce section if active
        if ( class_exists( 'WooCommerce' ) ) {
            $sections[] = 'woocommerce';
        }
        
        // Load each section
        foreach ( $sections as $section ) {
            $file = AQUALUXE_INC_DIR . 'customizer/sections/class-' . $section . '.php';
            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }
    }

    /**
     * Register general section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    private function register_general_section( $wp_customize ) {
        // General section
        $wp_customize->add_section(
            'aqualuxe_general',
            [
                'title'    => esc_html__( 'General Settings', 'aqualuxe' ),
                'priority' => 10,
            ]
        );
        
        // Site logo
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
                    'label'    => esc_html__( 'Logo', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general',
                    'settings' => 'aqualuxe_logo',
                    'priority' => 10,
                ]
            )
        );
        
        // Dark mode logo
        $wp_customize->add_setting(
            'aqualuxe_logo_dark',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_logo_dark',
                [
                    'label'    => esc_html__( 'Dark Mode Logo', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general',
                    'settings' => 'aqualuxe_logo_dark',
                    'priority' => 20,
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
                    'label'    => esc_html__( 'Favicon', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general',
                    'settings' => 'aqualuxe_favicon',
                    'priority' => 30,
                ]
            )
        );
        
        // Dark mode toggle
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
                'label'    => esc_html__( 'Enable Dark Mode Toggle', 'aqualuxe' ),
                'section'  => 'aqualuxe_general',
                'settings' => 'aqualuxe_dark_mode_toggle',
                'type'     => 'checkbox',
                'priority' => 40,
            ]
        );
        
        // Dark mode default
        $wp_customize->add_setting(
            'aqualuxe_dark_mode_default',
            [
                'default'           => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_dark_mode_default',
            [
                'label'    => esc_html__( 'Dark Mode Default', 'aqualuxe' ),
                'section'  => 'aqualuxe_general',
                'settings' => 'aqualuxe_dark_mode_default',
                'type'     => 'checkbox',
                'priority' => 50,
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
                'label'    => esc_html__( 'Enable Preloader', 'aqualuxe' ),
                'section'  => 'aqualuxe_general',
                'settings' => 'aqualuxe_preloader',
                'type'     => 'checkbox',
                'priority' => 60,
            ]
        );
        
        // Back to top button
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
                'label'    => esc_html__( 'Enable Back to Top Button', 'aqualuxe' ),
                'section'  => 'aqualuxe_general',
                'settings' => 'aqualuxe_back_to_top',
                'type'     => 'checkbox',
                'priority' => 70,
            ]
        );
    }

    /**
     * Register header section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    private function register_header_section( $wp_customize ) {
        // Header section
        $wp_customize->add_section(
            'aqualuxe_header',
            [
                'title'    => esc_html__( 'Header Settings', 'aqualuxe' ),
                'priority' => 20,
            ]
        );
        
        // Header layout
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
                'label'    => esc_html__( 'Header Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_header',
                'settings' => 'aqualuxe_header_layout',
                'type'     => 'select',
                'choices'  => [
                    'default'     => esc_html__( 'Default', 'aqualuxe' ),
                    'centered'    => esc_html__( 'Centered', 'aqualuxe' ),
                    'transparent' => esc_html__( 'Transparent', 'aqualuxe' ),
                    'minimal'     => esc_html__( 'Minimal', 'aqualuxe' ),
                    'split'       => esc_html__( 'Split', 'aqualuxe' ),
                ],
                'priority' => 10,
            ]
        );
        
        // Sticky header
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
                'label'    => esc_html__( 'Enable Sticky Header', 'aqualuxe' ),
                'section'  => 'aqualuxe_header',
                'settings' => 'aqualuxe_sticky_header',
                'type'     => 'checkbox',
                'priority' => 20,
            ]
        );
        
        // Top bar
        $wp_customize->add_setting(
            'aqualuxe_top_bar',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_top_bar',
            [
                'label'    => esc_html__( 'Enable Top Bar', 'aqualuxe' ),
                'section'  => 'aqualuxe_header',
                'settings' => 'aqualuxe_top_bar',
                'type'     => 'checkbox',
                'priority' => 30,
            ]
        );
        
        // Top bar content
        $wp_customize->add_setting(
            'aqualuxe_top_bar_content',
            [
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_top_bar_content',
            [
                'label'    => esc_html__( 'Top Bar Content', 'aqualuxe' ),
                'section'  => 'aqualuxe_header',
                'settings' => 'aqualuxe_top_bar_content',
                'type'     => 'textarea',
                'priority' => 40,
            ]
        );
        
        // Search in header
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
                'label'    => esc_html__( 'Enable Search in Header', 'aqualuxe' ),
                'section'  => 'aqualuxe_header',
                'settings' => 'aqualuxe_header_search',
                'type'     => 'checkbox',
                'priority' => 50,
            ]
        );
        
        // Cart in header
        if ( class_exists( 'WooCommerce' ) ) {
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
                    'label'    => esc_html__( 'Enable Cart in Header', 'aqualuxe' ),
                    'section'  => 'aqualuxe_header',
                    'settings' => 'aqualuxe_header_cart',
                    'type'     => 'checkbox',
                    'priority' => 60,
                ]
            );
        }
        
        // Account in header
        if ( class_exists( 'WooCommerce' ) ) {
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
                    'label'    => esc_html__( 'Enable Account in Header', 'aqualuxe' ),
                    'section'  => 'aqualuxe_header',
                    'settings' => 'aqualuxe_header_account',
                    'type'     => 'checkbox',
                    'priority' => 70,
                ]
            );
        }
        
        // Wishlist in header
        if ( class_exists( 'WooCommerce' ) ) {
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
                    'label'    => esc_html__( 'Enable Wishlist in Header', 'aqualuxe' ),
                    'section'  => 'aqualuxe_header',
                    'settings' => 'aqualuxe_header_wishlist',
                    'type'     => 'checkbox',
                    'priority' => 80,
                ]
            );
        }
    }

    /**
     * Register footer section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    private function register_footer_section( $wp_customize ) {
        // Footer section
        $wp_customize->add_section(
            'aqualuxe_footer',
            [
                'title'    => esc_html__( 'Footer Settings', 'aqualuxe' ),
                'priority' => 30,
            ]
        );
        
        // Footer layout
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
                'label'    => esc_html__( 'Footer Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_layout',
                'type'     => 'select',
                'choices'  => [
                    'default'  => esc_html__( 'Default', 'aqualuxe' ),
                    'centered' => esc_html__( 'Centered', 'aqualuxe' ),
                    'minimal'  => esc_html__( 'Minimal', 'aqualuxe' ),
                    'expanded' => esc_html__( 'Expanded', 'aqualuxe' ),
                ],
                'priority' => 10,
            ]
        );
        
        // Footer widgets
        $wp_customize->add_setting(
            'aqualuxe_footer_widgets',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_widgets',
            [
                'label'    => esc_html__( 'Enable Footer Widgets', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_widgets',
                'type'     => 'checkbox',
                'priority' => 20,
            ]
        );
        
        // Footer widgets columns
        $wp_customize->add_setting(
            'aqualuxe_footer_widgets_columns',
            [
                'default'           => '4',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_widgets_columns',
            [
                'label'    => esc_html__( 'Footer Widgets Columns', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_widgets_columns',
                'type'     => 'select',
                'choices'  => [
                    '1' => esc_html__( '1 Column', 'aqualuxe' ),
                    '2' => esc_html__( '2 Columns', 'aqualuxe' ),
                    '3' => esc_html__( '3 Columns', 'aqualuxe' ),
                    '4' => esc_html__( '4 Columns', 'aqualuxe' ),
                ],
                'priority' => 30,
            ]
        );
        
        // Footer copyright
        $wp_customize->add_setting(
            'aqualuxe_footer_copyright',
            [
                'default'           => sprintf( esc_html__( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ),
                'sanitize_callback' => 'wp_kses_post',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_copyright',
            [
                'label'    => esc_html__( 'Footer Copyright', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_copyright',
                'type'     => 'textarea',
                'priority' => 40,
            ]
        );
        
        // Footer menu
        $wp_customize->add_setting(
            'aqualuxe_footer_menu',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_footer_menu',
            [
                'label'    => esc_html__( 'Enable Footer Menu', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_menu',
                'type'     => 'checkbox',
                'priority' => 50,
            ]
        );
        
        // Footer social icons
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
                'label'    => esc_html__( 'Enable Footer Social Icons', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer',
                'settings' => 'aqualuxe_footer_social',
                'type'     => 'checkbox',
                'priority' => 60,
            ]
        );
        
        // Footer payment icons
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_setting(
                'aqualuxe_footer_payment',
                [
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_footer_payment',
                [
                    'label'    => esc_html__( 'Enable Footer Payment Icons', 'aqualuxe' ),
                    'section'  => 'aqualuxe_footer',
                    'settings' => 'aqualuxe_footer_payment',
                    'type'     => 'checkbox',
                    'priority' => 70,
                ]
            );
        }
    }

    /**
     * Register colors section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    private function register_colors_section( $wp_customize ) {
        // Colors section
        $wp_customize->add_section(
            'aqualuxe_colors',
            [
                'title'    => esc_html__( 'Colors', 'aqualuxe' ),
                'priority' => 40,
            ]
        );
        
        // Primary color
        $wp_customize->add_setting(
            'aqualuxe_primary_color',
            [
                'default'           => '#0077b6',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_primary_color',
                [
                    'label'    => esc_html__( 'Primary Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_primary_color',
                    'priority' => 10,
                ]
            )
        );
        
        // Secondary color
        $wp_customize->add_setting(
            'aqualuxe_secondary_color',
            [
                'default'           => '#00b4d8',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_secondary_color',
                [
                    'label'    => esc_html__( 'Secondary Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_secondary_color',
                    'priority' => 20,
                ]
            )
        );
        
        // Accent color
        $wp_customize->add_setting(
            'aqualuxe_accent_color',
            [
                'default'           => '#90e0ef',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_accent_color',
                [
                    'label'    => esc_html__( 'Accent Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_accent_color',
                    'priority' => 30,
                ]
            )
        );
        
        // Text color
        $wp_customize->add_setting(
            'aqualuxe_text_color',
            [
                'default'           => '#333333',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_text_color',
                [
                    'label'    => esc_html__( 'Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_text_color',
                    'priority' => 40,
                ]
            )
        );
        
        // Heading color
        $wp_customize->add_setting(
            'aqualuxe_heading_color',
            [
                'default'           => '#111111',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_heading_color',
                [
                    'label'    => esc_html__( 'Heading Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_heading_color',
                    'priority' => 50,
                ]
            )
        );
        
        // Background color
        $wp_customize->add_setting(
            'aqualuxe_background_color',
            [
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_background_color',
                [
                    'label'    => esc_html__( 'Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_background_color',
                    'priority' => 60,
                ]
            )
        );
        
        // Dark mode colors
        $wp_customize->add_setting(
            'aqualuxe_dark_background_color',
            [
                'default'           => '#121212',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_dark_background_color',
                [
                    'label'    => esc_html__( 'Dark Mode Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_dark_background_color',
                    'priority' => 70,
                ]
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_dark_text_color',
            [
                'default'           => '#e0e0e0',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );
        
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_dark_text_color',
                [
                    'label'    => esc_html__( 'Dark Mode Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_colors',
                    'settings' => 'aqualuxe_dark_text_color',
                    'priority' => 80,
                ]
            )
        );
    }

    /**
     * Register typography section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    private function register_typography_section( $wp_customize ) {
        // Typography section
        $wp_customize->add_section(
            'aqualuxe_typography',
            [
                'title'    => esc_html__( 'Typography', 'aqualuxe' ),
                'priority' => 50,
            ]
        );
        
        // Body font family
        $wp_customize->add_setting(
            'aqualuxe_body_font_family',
            [
                'default'           => 'Roboto',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_body_font_family',
            [
                'label'    => esc_html__( 'Body Font Family', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography',
                'settings' => 'aqualuxe_body_font_family',
                'type'     => 'select',
                'choices'  => [
                    'Roboto'       => 'Roboto',
                    'Open Sans'    => 'Open Sans',
                    'Lato'         => 'Lato',
                    'Montserrat'   => 'Montserrat',
                    'Raleway'      => 'Raleway',
                    'Poppins'      => 'Poppins',
                    'Nunito'       => 'Nunito',
                    'Nunito Sans'  => 'Nunito Sans',
                    'Rubik'        => 'Rubik',
                    'Work Sans'    => 'Work Sans',
                ],
                'priority' => 10,
            ]
        );
        
        // Heading font family
        $wp_customize->add_setting(
            'aqualuxe_heading_font_family',
            [
                'default'           => 'Montserrat',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_heading_font_family',
            [
                'label'    => esc_html__( 'Heading Font Family', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography',
                'settings' => 'aqualuxe_heading_font_family',
                'type'     => 'select',
                'choices'  => [
                    'Montserrat'   => 'Montserrat',
                    'Roboto'       => 'Roboto',
                    'Open Sans'    => 'Open Sans',
                    'Lato'         => 'Lato',
                    'Raleway'      => 'Raleway',
                    'Poppins'      => 'Poppins',
                    'Playfair Display' => 'Playfair Display',
                    'Merriweather' => 'Merriweather',
                    'Nunito'       => 'Nunito',
                    'Nunito Sans'  => 'Nunito Sans',
                ],
                'priority' => 20,
            ]
        );
        
        // Body font size
        $wp_customize->add_setting(
            'aqualuxe_body_font_size',
            [
                'default'           => '16',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_body_font_size',
            [
                'label'    => esc_html__( 'Body Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography',
                'settings' => 'aqualuxe_body_font_size',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 12,
                    'max'  => 24,
                    'step' => 1,
                ],
                'priority' => 30,
            ]
        );
        
        // Line height
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
                'label'    => esc_html__( 'Line Height', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography',
                'settings' => 'aqualuxe_line_height',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 2,
                    'step' => 0.1,
                ],
                'priority' => 40,
            ]
        );
        
        // Heading line height
        $wp_customize->add_setting(
            'aqualuxe_heading_line_height',
            [
                'default'           => '1.2',
                'sanitize_callback' => 'aqualuxe_sanitize_float',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_heading_line_height',
            [
                'label'    => esc_html__( 'Heading Line Height', 'aqualuxe' ),
                'section'  => 'aqualuxe_typography',
                'settings' => 'aqualuxe_heading_line_height',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 2,
                    'step' => 0.1,
                ],
                'priority' => 50,
            ]
        );
    }

    /**
     * Register layout section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    private function register_layout_section( $wp_customize ) {
        // Layout section
        $wp_customize->add_section(
            'aqualuxe_layout',
            [
                'title'    => esc_html__( 'Layout Settings', 'aqualuxe' ),
                'priority' => 60,
            ]
        );
        
        // Container width
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
                'label'    => esc_html__( 'Container Width (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout',
                'settings' => 'aqualuxe_container_width',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 960,
                    'max'  => 1600,
                    'step' => 10,
                ],
                'priority' => 10,
            ]
        );
        
        // Sidebar position
        $wp_customize->add_setting(
            'aqualuxe_sidebar_position',
            [
                'default'           => 'right',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_sidebar_position',
            [
                'label'    => esc_html__( 'Sidebar Position', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout',
                'settings' => 'aqualuxe_sidebar_position',
                'type'     => 'select',
                'choices'  => [
                    'right' => esc_html__( 'Right', 'aqualuxe' ),
                    'left'  => esc_html__( 'Left', 'aqualuxe' ),
                    'none'  => esc_html__( 'No Sidebar', 'aqualuxe' ),
                ],
                'priority' => 20,
            ]
        );
        
        // Shop sidebar position
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_setting(
                'aqualuxe_shop_sidebar_position',
                [
                    'default'           => 'left',
                    'sanitize_callback' => 'aqualuxe_sanitize_select',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_shop_sidebar_position',
                [
                    'label'    => esc_html__( 'Shop Sidebar Position', 'aqualuxe' ),
                    'section'  => 'aqualuxe_layout',
                    'settings' => 'aqualuxe_shop_sidebar_position',
                    'type'     => 'select',
                    'choices'  => [
                        'right' => esc_html__( 'Right', 'aqualuxe' ),
                        'left'  => esc_html__( 'Left', 'aqualuxe' ),
                        'none'  => esc_html__( 'No Sidebar', 'aqualuxe' ),
                    ],
                    'priority' => 30,
                ]
            );
        }
        
        // Page layout
        $wp_customize->add_setting(
            'aqualuxe_page_layout',
            [
                'default'           => 'default',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_page_layout',
            [
                'label'    => esc_html__( 'Page Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout',
                'settings' => 'aqualuxe_page_layout',
                'type'     => 'select',
                'choices'  => [
                    'default'     => esc_html__( 'Default', 'aqualuxe' ),
                    'full-width'  => esc_html__( 'Full Width', 'aqualuxe' ),
                    'boxed'       => esc_html__( 'Boxed', 'aqualuxe' ),
                    'contained'   => esc_html__( 'Contained', 'aqualuxe' ),
                ],
                'priority' => 40,
            ]
        );
        
        // Blog layout
        $wp_customize->add_setting(
            'aqualuxe_blog_layout',
            [
                'default'           => 'standard',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_layout',
            [
                'label'    => esc_html__( 'Blog Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_layout',
                'settings' => 'aqualuxe_blog_layout',
                'type'     => 'select',
                'choices'  => [
                    'standard' => esc_html__( 'Standard', 'aqualuxe' ),
                    'grid'     => esc_html__( 'Grid', 'aqualuxe' ),
                    'masonry'  => esc_html__( 'Masonry', 'aqualuxe' ),
                    'list'     => esc_html__( 'List', 'aqualuxe' ),
                ],
                'priority' => 50,
            ]
        );
        
        // Shop layout
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_setting(
                'aqualuxe_shop_layout',
                [
                    'default'           => 'grid',
                    'sanitize_callback' => 'aqualuxe_sanitize_select',
                ]
            );
            
            $wp_customize->add_control(
                'aqualuxe_shop_layout',
                [
                    'label'    => esc_html__( 'Shop Layout', 'aqualuxe' ),
                    'section'  => 'aqualuxe_layout',
                    'settings' => 'aqualuxe_shop_layout',
                    'type'     => 'select',
                    'choices'  => [
                        'grid'     => esc_html__( 'Grid', 'aqualuxe' ),
                        'list'     => esc_html__( 'List', 'aqualuxe' ),
                        'masonry'  => esc_html__( 'Masonry', 'aqualuxe' ),
                    ],
                    'priority' => 60,
                ]
            );
        }
    }

    /**
     * Register blog section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    private function register_blog_section( $wp_customize ) {
        // Blog section
        $wp_customize->add_section(
            'aqualuxe_blog',
            [
                'title'    => esc_html__( 'Blog Settings', 'aqualuxe' ),
                'priority' => 70,
            ]
        );
        
        // Blog page title
        $wp_customize->add_setting(
            'aqualuxe_blog_title',
            [
                'default'           => esc_html__( 'Blog', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_title',
            [
                'label'    => esc_html__( 'Blog Page Title', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_title',
                'type'     => 'text',
                'priority' => 10,
            ]
        );
        
        // Blog page subtitle
        $wp_customize->add_setting(
            'aqualuxe_blog_subtitle',
            [
                'default'           => esc_html__( 'Latest News & Articles', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_subtitle',
            [
                'label'    => esc_html__( 'Blog Page Subtitle', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_subtitle',
                'type'     => 'text',
                'priority' => 20,
            ]
        );
        
        // Blog featured image
        $wp_customize->add_setting(
            'aqualuxe_blog_featured_image',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_featured_image',
            [
                'label'    => esc_html__( 'Show Featured Image', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_featured_image',
                'type'     => 'checkbox',
                'priority' => 30,
            ]
        );
        
        // Blog excerpt
        $wp_customize->add_setting(
            'aqualuxe_blog_excerpt',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_excerpt',
            [
                'label'    => esc_html__( 'Show Excerpt', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_excerpt',
                'type'     => 'checkbox',
                'priority' => 40,
            ]
        );
        
        // Blog excerpt length
        $wp_customize->add_setting(
            'aqualuxe_blog_excerpt_length',
            [
                'default'           => '55',
                'sanitize_callback' => 'absint',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_excerpt_length',
            [
                'label'    => esc_html__( 'Excerpt Length', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_excerpt_length',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 10,
                    'max'  => 200,
                    'step' => 5,
                ],
                'priority' => 50,
            ]
        );
        
        // Blog meta
        $wp_customize->add_setting(
            'aqualuxe_blog_meta',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_meta',
            [
                'label'    => esc_html__( 'Show Meta', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_meta',
                'type'     => 'checkbox',
                'priority' => 60,
            ]
        );
        
        // Blog author
        $wp_customize->add_setting(
            'aqualuxe_blog_author',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_author',
            [
                'label'    => esc_html__( 'Show Author', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_author',
                'type'     => 'checkbox',
                'priority' => 70,
            ]
        );
        
        // Blog date
        $wp_customize->add_setting(
            'aqualuxe_blog_date',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_date',
            [
                'label'    => esc_html__( 'Show Date', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_date',
                'type'     => 'checkbox',
                'priority' => 80,
            ]
        );
        
        // Blog categories
        $wp_customize->add_setting(
            'aqualuxe_blog_categories',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_categories',
            [
                'label'    => esc_html__( 'Show Categories', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_categories',
                'type'     => 'checkbox',
                'priority' => 90,
            ]
        );
        
        // Blog tags
        $wp_customize->add_setting(
            'aqualuxe_blog_tags',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_tags',
            [
                'label'    => esc_html__( 'Show Tags', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_tags',
                'type'     => 'checkbox',
                'priority' => 100,
            ]
        );
        
        // Blog comments
        $wp_customize->add_setting(
            'aqualuxe_blog_comments',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_comments',
            [
                'label'    => esc_html__( 'Show Comments', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_comments',
                'type'     => 'checkbox',
                'priority' => 110,
            ]
        );
        
        // Blog read more
        $wp_customize->add_setting(
            'aqualuxe_blog_read_more',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_read_more',
            [
                'label'    => esc_html__( 'Show Read More', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_read_more',
                'type'     => 'checkbox',
                'priority' => 120,
            ]
        );
        
        // Blog read more text
        $wp_customize->add_setting(
            'aqualuxe_blog_read_more_text',
            [
                'default'           => esc_html__( 'Read More', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_read_more_text',
            [
                'label'    => esc_html__( 'Read More Text', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog',
                'settings' => 'aqualuxe_blog_read_more_text',
                'type'     => 'text',
                'priority' => 130,
            ]
        );
    }

    /**
     * Register social section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    private function register_social_section( $wp_customize ) {
        // Social section
        $wp_customize->add_section(
            'aqualuxe_social',
            [
                'title'    => esc_html__( 'Social Media', 'aqualuxe' ),
                'priority' => 80,
            ]
        );
        
        // Facebook
        $wp_customize->add_setting(
            'aqualuxe_facebook',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_facebook',
            [
                'label'    => esc_html__( 'Facebook', 'aqualuxe' ),
                'section'  => 'aqualuxe_social',
                'settings' => 'aqualuxe_facebook',
                'type'     => 'url',
                'priority' => 10,
            ]
        );
        
        // Twitter
        $wp_customize->add_setting(
            'aqualuxe_twitter',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_twitter',
            [
                'label'    => esc_html__( 'Twitter', 'aqualuxe' ),
                'section'  => 'aqualuxe_social',
                'settings' => 'aqualuxe_twitter',
                'type'     => 'url',
                'priority' => 20,
            ]
        );
        
        // Instagram
        $wp_customize->add_setting(
            'aqualuxe_instagram',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_instagram',
            [
                'label'    => esc_html__( 'Instagram', 'aqualuxe' ),
                'section'  => 'aqualuxe_social',
                'settings' => 'aqualuxe_instagram',
                'type'     => 'url',
                'priority' => 30,
            ]
        );
        
        // LinkedIn
        $wp_customize->add_setting(
            'aqualuxe_linkedin',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_linkedin',
            [
                'label'    => esc_html__( 'LinkedIn', 'aqualuxe' ),
                'section'  => 'aqualuxe_social',
                'settings' => 'aqualuxe_linkedin',
                'type'     => 'url',
                'priority' => 40,
            ]
        );
        
        // YouTube
        $wp_customize->add_setting(
            'aqualuxe_youtube',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_youtube',
            [
                'label'    => esc_html__( 'YouTube', 'aqualuxe' ),
                'section'  => 'aqualuxe_social',
                'settings' => 'aqualuxe_youtube',
                'type'     => 'url',
                'priority' => 50,
            ]
        );
        
        // Pinterest
        $wp_customize->add_setting(
            'aqualuxe_pinterest',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_pinterest',
            [
                'label'    => esc_html__( 'Pinterest', 'aqualuxe' ),
                'section'  => 'aqualuxe_social',
                'settings' => 'aqualuxe_pinterest',
                'type'     => 'url',
                'priority' => 60,
            ]
        );
        
        // TikTok
        $wp_customize->add_setting(
            'aqualuxe_tiktok',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_tiktok',
            [
                'label'    => esc_html__( 'TikTok', 'aqualuxe' ),
                'section'  => 'aqualuxe_social',
                'settings' => 'aqualuxe_tiktok',
                'type'     => 'url',
                'priority' => 70,
            ]
        );
        
        // WhatsApp
        $wp_customize->add_setting(
            'aqualuxe_whatsapp',
            [
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_whatsapp',
            [
                'label'    => esc_html__( 'WhatsApp', 'aqualuxe' ),
                'section'  => 'aqualuxe_social',
                'settings' => 'aqualuxe_whatsapp',
                'type'     => 'url',
                'priority' => 80,
            ]
        );
    }

    /**
     * Register WooCommerce section
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    private function register_woocommerce_section( $wp_customize ) {
        // WooCommerce section
        $wp_customize->add_section(
            'aqualuxe_woocommerce',
            [
                'title'    => esc_html__( 'WooCommerce', 'aqualuxe' ),
                'priority' => 90,
            ]
        );
        
        // Products per page
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
                'label'    => esc_html__( 'Products Per Page', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_products_per_page',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 100,
                    'step' => 1,
                ],
                'priority' => 10,
            ]
        );
        
        // Product columns
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
                'label'    => esc_html__( 'Product Columns', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_product_columns',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 6,
                    'step' => 1,
                ],
                'priority' => 20,
            ]
        );
        
        // Related products
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
                'label'    => esc_html__( 'Show Related Products', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_related_products',
                'type'     => 'checkbox',
                'priority' => 30,
            ]
        );
        
        // Related products count
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
                'label'    => esc_html__( 'Related Products Count', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_related_products_count',
                'type'     => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 12,
                    'step' => 1,
                ],
                'priority' => 40,
            ]
        );
        
        // Upsells
        $wp_customize->add_setting(
            'aqualuxe_upsells',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_upsells',
            [
                'label'    => esc_html__( 'Show Upsells', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_upsells',
                'type'     => 'checkbox',
                'priority' => 50,
            ]
        );
        
        // Cross-sells
        $wp_customize->add_setting(
            'aqualuxe_cross_sells',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_cross_sells',
            [
                'label'    => esc_html__( 'Show Cross-Sells', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_cross_sells',
                'type'     => 'checkbox',
                'priority' => 60,
            ]
        );
        
        // Product gallery zoom
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
                'label'    => esc_html__( 'Enable Product Gallery Zoom', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_product_gallery_zoom',
                'type'     => 'checkbox',
                'priority' => 70,
            ]
        );
        
        // Product gallery lightbox
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
                'label'    => esc_html__( 'Enable Product Gallery Lightbox', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_product_gallery_lightbox',
                'type'     => 'checkbox',
                'priority' => 80,
            ]
        );
        
        // Product gallery slider
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
                'label'    => esc_html__( 'Enable Product Gallery Slider', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_product_gallery_slider',
                'type'     => 'checkbox',
                'priority' => 90,
            ]
        );
        
        // Quick view
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
                'label'    => esc_html__( 'Enable Quick View', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_quick_view',
                'type'     => 'checkbox',
                'priority' => 100,
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
                'label'    => esc_html__( 'Enable Wishlist', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_wishlist',
                'type'     => 'checkbox',
                'priority' => 110,
            ]
        );
        
        // AJAX add to cart
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
                'label'    => esc_html__( 'Enable AJAX Add to Cart', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_ajax_add_to_cart',
                'type'     => 'checkbox',
                'priority' => 120,
            ]
        );
        
        // Sticky add to cart
        $wp_customize->add_setting(
            'aqualuxe_sticky_add_to_cart',
            [
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_sticky_add_to_cart',
            [
                'label'    => esc_html__( 'Enable Sticky Add to Cart', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_sticky_add_to_cart',
                'type'     => 'checkbox',
                'priority' => 130,
            ]
        );
        
        // Product hover effect
        $wp_customize->add_setting(
            'aqualuxe_product_hover_effect',
            [
                'default'           => 'zoom',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_product_hover_effect',
            [
                'label'    => esc_html__( 'Product Hover Effect', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_product_hover_effect',
                'type'     => 'select',
                'choices'  => [
                    'none'       => esc_html__( 'None', 'aqualuxe' ),
                    'zoom'       => esc_html__( 'Zoom', 'aqualuxe' ),
                    'fade'       => esc_html__( 'Fade', 'aqualuxe' ),
                    'flip'       => esc_html__( 'Flip', 'aqualuxe' ),
                    'slide'      => esc_html__( 'Slide', 'aqualuxe' ),
                    'alternate'  => esc_html__( 'Alternate Image', 'aqualuxe' ),
                ],
                'priority' => 140,
            ]
        );
        
        // Sale badge style
        $wp_customize->add_setting(
            'aqualuxe_sale_badge_style',
            [
                'default'           => 'circle',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_sale_badge_style',
            [
                'label'    => esc_html__( 'Sale Badge Style', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_sale_badge_style',
                'type'     => 'select',
                'choices'  => [
                    'circle'     => esc_html__( 'Circle', 'aqualuxe' ),
                    'square'     => esc_html__( 'Square', 'aqualuxe' ),
                    'ribbon'     => esc_html__( 'Ribbon', 'aqualuxe' ),
                    'tag'        => esc_html__( 'Tag', 'aqualuxe' ),
                    'percentage' => esc_html__( 'Percentage', 'aqualuxe' ),
                ],
                'priority' => 150,
            ]
        );
        
        // Checkout layout
        $wp_customize->add_setting(
            'aqualuxe_checkout_layout',
            [
                'default'           => 'default',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
            ]
        );
        
        $wp_customize->add_control(
            'aqualuxe_checkout_layout',
            [
                'label'    => esc_html__( 'Checkout Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_woocommerce',
                'settings' => 'aqualuxe_checkout_layout',
                'type'     => 'select',
                'choices'  => [
                    'default'   => esc_html__( 'Default', 'aqualuxe' ),
                    'two-column' => esc_html__( 'Two Column', 'aqualuxe' ),
                    'one-column' => esc_html__( 'One Column', 'aqualuxe' ),
                    'multistep' => esc_html__( 'Multi-Step', 'aqualuxe' ),
                ],
                'priority' => 160,
            ]
        );
    }

    /**
     * Enqueue customizer preview JS
     *
     * @return void
     */
    public function enqueue_customizer_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . 'js/customizer.js',
            [ 'customize-preview' ],
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
        // Get customizer values
        $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0077b6' );
        $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#00b4d8' );
        $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#90e0ef' );
        $text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
        $heading_color = get_theme_mod( 'aqualuxe_heading_color', '#111111' );
        $background_color = get_theme_mod( 'aqualuxe_background_color', '#ffffff' );
        $dark_background_color = get_theme_mod( 'aqualuxe_dark_background_color', '#121212' );
        $dark_text_color = get_theme_mod( 'aqualuxe_dark_text_color', '#e0e0e0' );
        $body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Roboto' );
        $heading_font_family = get_theme_mod( 'aqualuxe_heading_font_family', 'Montserrat' );
        $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', '16' );
        $line_height = get_theme_mod( 'aqualuxe_line_height', '1.6' );
        $heading_line_height = get_theme_mod( 'aqualuxe_heading_line_height', '1.2' );
        $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
        
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
                --aqualuxe-dark-background-color: <?php echo esc_attr( $dark_background_color ); ?>;
                --aqualuxe-dark-text-color: <?php echo esc_attr( $dark_text_color ); ?>;
                --aqualuxe-body-font-family: '<?php echo esc_attr( $body_font_family ); ?>', sans-serif;
                --aqualuxe-heading-font-family: '<?php echo esc_attr( $heading_font_family ); ?>', sans-serif;
                --aqualuxe-body-font-size: <?php echo esc_attr( $body_font_size ); ?>px;
                --aqualuxe-line-height: <?php echo esc_attr( $line_height ); ?>;
                --aqualuxe-heading-line-height: <?php echo esc_attr( $heading_line_height ); ?>;
                --aqualuxe-container-width: <?php echo esc_attr( $container_width ); ?>px;
            }
            
            body {
                font-family: var(--aqualuxe-body-font-family);
                font-size: var(--aqualuxe-body-font-size);
                line-height: var(--aqualuxe-line-height);
                color: var(--aqualuxe-text-color);
                background-color: var(--aqualuxe-background-color);
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: var(--aqualuxe-heading-font-family);
                line-height: var(--aqualuxe-heading-line-height);
                color: var(--aqualuxe-heading-color);
            }
            
            a {
                color: var(--aqualuxe-primary-color);
            }
            
            a:hover {
                color: var(--aqualuxe-secondary-color);
            }
            
            .container {
                max-width: var(--aqualuxe-container-width);
            }
            
            .button, button, input[type="button"], input[type="reset"], input[type="submit"] {
                background-color: var(--aqualuxe-primary-color);
                color: #ffffff;
            }
            
            .button:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover {
                background-color: var(--aqualuxe-secondary-color);
            }
            
            /* Dark mode styles */
            body.dark-mode {
                background-color: var(--aqualuxe-dark-background-color);
                color: var(--aqualuxe-dark-text-color);
            }
            
            body.dark-mode h1, body.dark-mode h2, body.dark-mode h3, body.dark-mode h4, body.dark-mode h5, body.dark-mode h6 {
                color: var(--aqualuxe-dark-text-color);
            }
            
            /* WooCommerce styles */
            .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button {
                background-color: var(--aqualuxe-primary-color);
                color: #ffffff;
            }
            
            .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover {
                background-color: var(--aqualuxe-secondary-color);
                color: #ffffff;
            }
            
            .woocommerce span.onsale {
                background-color: var(--aqualuxe-secondary-color);
            }
            
            .woocommerce ul.products li.product .price, .woocommerce div.product p.price, .woocommerce div.product span.price {
                color: var(--aqualuxe-primary-color);
            }
            
            .woocommerce-message, .woocommerce-info {
                border-top-color: var(--aqualuxe-primary-color);
            }
            
            .woocommerce-message::before, .woocommerce-info::before {
                color: var(--aqualuxe-primary-color);
            }
            
            /* Custom CSS from customizer */
            <?php echo wp_kses_post( get_theme_mod( 'aqualuxe_custom_css', '' ) ); ?>
        </style>
        <?php
    }
}