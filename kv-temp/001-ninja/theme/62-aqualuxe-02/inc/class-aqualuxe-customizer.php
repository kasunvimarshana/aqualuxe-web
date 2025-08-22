<?php
/**
 * AquaLuxe Customizer Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
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
    private static $instance;

    /**
     * Main customizer instance
     *
     * @return AquaLuxe_Customizer
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AquaLuxe_Customizer ) ) {
            self::$instance = new AquaLuxe_Customizer();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Register customizer settings
        add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
        
        // Enqueue customizer scripts
        add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview_scripts' ) );
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_controls_scripts' ) );
        
        // Output custom CSS
        add_action( 'wp_head', array( $this, 'output_custom_css' ), 100 );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add panels
        $this->add_panels( $wp_customize );
        
        // Add sections
        $this->add_sections( $wp_customize );
        
        // Add settings
        $this->add_settings( $wp_customize );
    }

    /**
     * Add panels
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_panels( $wp_customize ) {
        // General Panel
        $wp_customize->add_panel(
            'aqualuxe_general_panel',
            array(
                'title'       => __( 'AquaLuxe General Settings', 'aqualuxe' ),
                'description' => __( 'Customize general settings for AquaLuxe theme.', 'aqualuxe' ),
                'priority'    => 10,
            )
        );
        
        // Header Panel
        $wp_customize->add_panel(
            'aqualuxe_header_panel',
            array(
                'title'       => __( 'AquaLuxe Header', 'aqualuxe' ),
                'description' => __( 'Customize header settings for AquaLuxe theme.', 'aqualuxe' ),
                'priority'    => 20,
            )
        );
        
        // Footer Panel
        $wp_customize->add_panel(
            'aqualuxe_footer_panel',
            array(
                'title'       => __( 'AquaLuxe Footer', 'aqualuxe' ),
                'description' => __( 'Customize footer settings for AquaLuxe theme.', 'aqualuxe' ),
                'priority'    => 30,
            )
        );
        
        // Blog Panel
        $wp_customize->add_panel(
            'aqualuxe_blog_panel',
            array(
                'title'       => __( 'AquaLuxe Blog', 'aqualuxe' ),
                'description' => __( 'Customize blog settings for AquaLuxe theme.', 'aqualuxe' ),
                'priority'    => 40,
            )
        );
        
        // WooCommerce Panel
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_panel(
                'aqualuxe_woocommerce_panel',
                array(
                    'title'       => __( 'AquaLuxe WooCommerce', 'aqualuxe' ),
                    'description' => __( 'Customize WooCommerce settings for AquaLuxe theme.', 'aqualuxe' ),
                    'priority'    => 50,
                )
            );
        }
    }

    /**
     * Add sections
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_sections( $wp_customize ) {
        // General Sections
        $wp_customize->add_section(
            'aqualuxe_general_colors',
            array(
                'title'       => __( 'Colors', 'aqualuxe' ),
                'description' => __( 'Customize colors for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_general_typography',
            array(
                'title'       => __( 'Typography', 'aqualuxe' ),
                'description' => __( 'Customize typography for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 20,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_general_layout',
            array(
                'title'       => __( 'Layout', 'aqualuxe' ),
                'description' => __( 'Customize layout for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_general_panel',
                'priority'    => 30,
            )
        );
        
        // Header Sections
        $wp_customize->add_section(
            'aqualuxe_header_general',
            array(
                'title'       => __( 'General', 'aqualuxe' ),
                'description' => __( 'Customize general header settings for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_header_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_header_logo',
            array(
                'title'       => __( 'Logo', 'aqualuxe' ),
                'description' => __( 'Customize logo settings for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_header_panel',
                'priority'    => 20,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_header_navigation',
            array(
                'title'       => __( 'Navigation', 'aqualuxe' ),
                'description' => __( 'Customize navigation settings for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_header_panel',
                'priority'    => 30,
            )
        );
        
        // Footer Sections
        $wp_customize->add_section(
            'aqualuxe_footer_general',
            array(
                'title'       => __( 'General', 'aqualuxe' ),
                'description' => __( 'Customize general footer settings for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_footer_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_footer_widgets',
            array(
                'title'       => __( 'Widgets', 'aqualuxe' ),
                'description' => __( 'Customize footer widgets settings for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_footer_panel',
                'priority'    => 20,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_footer_copyright',
            array(
                'title'       => __( 'Copyright', 'aqualuxe' ),
                'description' => __( 'Customize copyright settings for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_footer_panel',
                'priority'    => 30,
            )
        );
        
        // Blog Sections
        $wp_customize->add_section(
            'aqualuxe_blog_archive',
            array(
                'title'       => __( 'Archive', 'aqualuxe' ),
                'description' => __( 'Customize blog archive settings for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_blog_panel',
                'priority'    => 10,
            )
        );
        
        $wp_customize->add_section(
            'aqualuxe_blog_single',
            array(
                'title'       => __( 'Single Post', 'aqualuxe' ),
                'description' => __( 'Customize single post settings for AquaLuxe theme.', 'aqualuxe' ),
                'panel'       => 'aqualuxe_blog_panel',
                'priority'    => 20,
            )
        );
        
        // WooCommerce Sections
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_section(
                'aqualuxe_woocommerce_shop',
                array(
                    'title'       => __( 'Shop', 'aqualuxe' ),
                    'description' => __( 'Customize shop settings for AquaLuxe theme.', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 10,
                )
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_product',
                array(
                    'title'       => __( 'Product', 'aqualuxe' ),
                    'description' => __( 'Customize product settings for AquaLuxe theme.', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 20,
                )
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_cart',
                array(
                    'title'       => __( 'Cart', 'aqualuxe' ),
                    'description' => __( 'Customize cart settings for AquaLuxe theme.', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 30,
                )
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_checkout',
                array(
                    'title'       => __( 'Checkout', 'aqualuxe' ),
                    'description' => __( 'Customize checkout settings for AquaLuxe theme.', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 40,
                )
            );
            
            $wp_customize->add_section(
                'aqualuxe_woocommerce_account',
                array(
                    'title'       => __( 'Account', 'aqualuxe' ),
                    'description' => __( 'Customize account settings for AquaLuxe theme.', 'aqualuxe' ),
                    'panel'       => 'aqualuxe_woocommerce_panel',
                    'priority'    => 50,
                )
            );
        }
    }

    /**
     * Add settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    private function add_settings( $wp_customize ) {
        // General Colors
        $wp_customize->add_setting(
            'aqualuxe_primary_color',
            array(
                'default'           => '#0073aa',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_primary_color',
                array(
                    'label'    => __( 'Primary Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_primary_color',
                    'priority' => 10,
                )
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_secondary_color',
            array(
                'default'           => '#23282d',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_secondary_color',
                array(
                    'label'    => __( 'Secondary Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_secondary_color',
                    'priority' => 20,
                )
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_accent_color',
            array(
                'default'           => '#00a0d2',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_accent_color',
                array(
                    'label'    => __( 'Accent Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_accent_color',
                    'priority' => 30,
                )
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_text_color',
            array(
                'default'           => '#333333',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_text_color',
                array(
                    'label'    => __( 'Text Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_text_color',
                    'priority' => 40,
                )
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_background_color',
            array(
                'default'           => '#ffffff',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'aqualuxe_background_color',
                array(
                    'label'    => __( 'Background Color', 'aqualuxe' ),
                    'section'  => 'aqualuxe_general_colors',
                    'settings' => 'aqualuxe_background_color',
                    'priority' => 50,
                )
            )
        );
        
        // General Typography
        $wp_customize->add_setting(
            'aqualuxe_body_font_family',
            array(
                'default'           => 'Roboto, sans-serif',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_body_font_family',
            array(
                'label'    => __( 'Body Font Family', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_typography',
                'settings' => 'aqualuxe_body_font_family',
                'type'     => 'select',
                'choices'  => array(
                    'Roboto, sans-serif'      => __( 'Roboto', 'aqualuxe' ),
                    'Open Sans, sans-serif'   => __( 'Open Sans', 'aqualuxe' ),
                    'Lato, sans-serif'        => __( 'Lato', 'aqualuxe' ),
                    'Montserrat, sans-serif'  => __( 'Montserrat', 'aqualuxe' ),
                    'Raleway, sans-serif'     => __( 'Raleway', 'aqualuxe' ),
                    'Poppins, sans-serif'     => __( 'Poppins', 'aqualuxe' ),
                    'Nunito, sans-serif'      => __( 'Nunito', 'aqualuxe' ),
                    'Playfair Display, serif' => __( 'Playfair Display', 'aqualuxe' ),
                    'Merriweather, serif'     => __( 'Merriweather', 'aqualuxe' ),
                ),
                'priority' => 10,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_heading_font_family',
            array(
                'default'           => 'Montserrat, sans-serif',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_heading_font_family',
            array(
                'label'    => __( 'Heading Font Family', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_typography',
                'settings' => 'aqualuxe_heading_font_family',
                'type'     => 'select',
                'choices'  => array(
                    'Montserrat, sans-serif'  => __( 'Montserrat', 'aqualuxe' ),
                    'Roboto, sans-serif'      => __( 'Roboto', 'aqualuxe' ),
                    'Open Sans, sans-serif'   => __( 'Open Sans', 'aqualuxe' ),
                    'Lato, sans-serif'        => __( 'Lato', 'aqualuxe' ),
                    'Raleway, sans-serif'     => __( 'Raleway', 'aqualuxe' ),
                    'Poppins, sans-serif'     => __( 'Poppins', 'aqualuxe' ),
                    'Nunito, sans-serif'      => __( 'Nunito', 'aqualuxe' ),
                    'Playfair Display, serif' => __( 'Playfair Display', 'aqualuxe' ),
                    'Merriweather, serif'     => __( 'Merriweather', 'aqualuxe' ),
                ),
                'priority' => 20,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_body_font_size',
            array(
                'default'           => '16',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_body_font_size',
            array(
                'label'    => __( 'Body Font Size (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_typography',
                'settings' => 'aqualuxe_body_font_size',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 12,
                    'max'  => 24,
                    'step' => 1,
                ),
                'priority' => 30,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_line_height',
            array(
                'default'           => '1.6',
                'sanitize_callback' => 'aqualuxe_sanitize_float',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_line_height',
            array(
                'label'    => __( 'Line Height', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_typography',
                'settings' => 'aqualuxe_line_height',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 2,
                    'step' => 0.1,
                ),
                'priority' => 40,
            )
        );
        
        // General Layout
        $wp_customize->add_setting(
            'aqualuxe_container_width',
            array(
                'default'           => '1200',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_container_width',
            array(
                'label'    => __( 'Container Width (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_layout',
                'settings' => 'aqualuxe_container_width',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 960,
                    'max'  => 1600,
                    'step' => 10,
                ),
                'priority' => 10,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_sidebar_position',
            array(
                'default'           => 'right',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_sidebar_position',
            array(
                'label'    => __( 'Sidebar Position', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_layout',
                'settings' => 'aqualuxe_sidebar_position',
                'type'     => 'select',
                'choices'  => array(
                    'left'  => __( 'Left', 'aqualuxe' ),
                    'right' => __( 'Right', 'aqualuxe' ),
                    'none'  => __( 'None', 'aqualuxe' ),
                ),
                'priority' => 20,
            )
        );
        
        // Header Logo
        $wp_customize->add_setting(
            'aqualuxe_logo_width',
            array(
                'default'           => '200',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_logo_width',
            array(
                'label'    => __( 'Logo Width (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_logo',
                'settings' => 'aqualuxe_logo_width',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 100,
                    'max'  => 500,
                    'step' => 10,
                ),
                'priority' => 10,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_logo_height',
            array(
                'default'           => '80',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_logo_height',
            array(
                'label'    => __( 'Logo Height (px)', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_logo',
                'settings' => 'aqualuxe_logo_height',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 50,
                    'max'  => 200,
                    'step' => 10,
                ),
                'priority' => 20,
            )
        );
        
        // Header Navigation
        $wp_customize->add_setting(
            'aqualuxe_sticky_header',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_sticky_header',
            array(
                'label'    => __( 'Enable Sticky Header', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_navigation',
                'settings' => 'aqualuxe_sticky_header',
                'type'     => 'checkbox',
                'priority' => 10,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_menu_position',
            array(
                'default'           => 'right',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_menu_position',
            array(
                'label'    => __( 'Menu Position', 'aqualuxe' ),
                'section'  => 'aqualuxe_header_navigation',
                'settings' => 'aqualuxe_menu_position',
                'type'     => 'select',
                'choices'  => array(
                    'left'   => __( 'Left', 'aqualuxe' ),
                    'center' => __( 'Center', 'aqualuxe' ),
                    'right'  => __( 'Right', 'aqualuxe' ),
                ),
                'priority' => 20,
            )
        );
        
        // Footer Copyright
        $wp_customize->add_setting(
            'aqualuxe_copyright_text',
            array(
                'default'           => sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ),
                'sanitize_callback' => 'wp_kses_post',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_copyright_text',
            array(
                'label'    => __( 'Copyright Text', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_copyright',
                'settings' => 'aqualuxe_copyright_text',
                'type'     => 'textarea',
                'priority' => 10,
            )
        );
        
        // Blog Archive
        $wp_customize->add_setting(
            'aqualuxe_blog_layout',
            array(
                'default'           => 'grid',
                'sanitize_callback' => 'aqualuxe_sanitize_select',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_layout',
            array(
                'label'    => __( 'Blog Layout', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_blog_layout',
                'type'     => 'select',
                'choices'  => array(
                    'grid'   => __( 'Grid', 'aqualuxe' ),
                    'list'   => __( 'List', 'aqualuxe' ),
                    'masonry' => __( 'Masonry', 'aqualuxe' ),
                ),
                'priority' => 10,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_blog_columns',
            array(
                'default'           => '3',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_columns',
            array(
                'label'    => __( 'Blog Columns', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_blog_columns',
                'type'     => 'select',
                'choices'  => array(
                    '1' => __( '1', 'aqualuxe' ),
                    '2' => __( '2', 'aqualuxe' ),
                    '3' => __( '3', 'aqualuxe' ),
                    '4' => __( '4', 'aqualuxe' ),
                ),
                'priority' => 20,
                'active_callback' => function() {
                    return get_theme_mod( 'aqualuxe_blog_layout', 'grid' ) !== 'list';
                },
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_blog_excerpt_length',
            array(
                'default'           => '25',
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_blog_excerpt_length',
            array(
                'label'    => __( 'Excerpt Length', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_archive',
                'settings' => 'aqualuxe_blog_excerpt_length',
                'type'     => 'number',
                'input_attrs' => array(
                    'min'  => 10,
                    'max'  => 100,
                    'step' => 5,
                ),
                'priority' => 30,
            )
        );
        
        // Blog Single
        $wp_customize->add_setting(
            'aqualuxe_show_post_thumbnail',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_post_thumbnail',
            array(
                'label'    => __( 'Show Featured Image', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_show_post_thumbnail',
                'type'     => 'checkbox',
                'priority' => 10,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_show_post_meta',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_post_meta',
            array(
                'label'    => __( 'Show Post Meta', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_show_post_meta',
                'type'     => 'checkbox',
                'priority' => 20,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_show_post_navigation',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_post_navigation',
            array(
                'label'    => __( 'Show Post Navigation', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_show_post_navigation',
                'type'     => 'checkbox',
                'priority' => 30,
            )
        );
        
        $wp_customize->add_setting(
            'aqualuxe_show_related_posts',
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );
        
        $wp_customize->add_control(
            'aqualuxe_show_related_posts',
            array(
                'label'    => __( 'Show Related Posts', 'aqualuxe' ),
                'section'  => 'aqualuxe_blog_single',
                'settings' => 'aqualuxe_show_related_posts',
                'type'     => 'checkbox',
                'priority' => 40,
            )
        );
        
        // WooCommerce Shop
        if ( class_exists( 'WooCommerce' ) ) {
            $wp_customize->add_setting(
                'aqualuxe_shop_columns',
                array(
                    'default'           => '4',
                    'sanitize_callback' => 'absint',
                    'transport'         => 'refresh',
                )
            );
            
            $wp_customize->add_control(
                'aqualuxe_shop_columns',
                array(
                    'label'    => __( 'Shop Columns', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_shop_columns',
                    'type'     => 'select',
                    'choices'  => array(
                        '2' => __( '2', 'aqualuxe' ),
                        '3' => __( '3', 'aqualuxe' ),
                        '4' => __( '4', 'aqualuxe' ),
                        '5' => __( '5', 'aqualuxe' ),
                    ),
                    'priority' => 10,
                )
            );
            
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
                    'label'    => __( 'Products Per Page', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_products_per_page',
                    'type'     => 'number',
                    'input_attrs' => array(
                        'min'  => 4,
                        'max'  => 48,
                        'step' => 4,
                    ),
                    'priority' => 20,
                )
            );
            
            $wp_customize->add_setting(
                'aqualuxe_shop_sidebar',
                array(
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                )
            );
            
            $wp_customize->add_control(
                'aqualuxe_shop_sidebar',
                array(
                    'label'    => __( 'Show Shop Sidebar', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_shop',
                    'settings' => 'aqualuxe_shop_sidebar',
                    'type'     => 'checkbox',
                    'priority' => 30,
                )
            );
            
            // WooCommerce Product
            $wp_customize->add_setting(
                'aqualuxe_product_gallery_zoom',
                array(
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                )
            );
            
            $wp_customize->add_control(
                'aqualuxe_product_gallery_zoom',
                array(
                    'label'    => __( 'Enable Gallery Zoom', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_product_gallery_zoom',
                    'type'     => 'checkbox',
                    'priority' => 10,
                )
            );
            
            $wp_customize->add_setting(
                'aqualuxe_product_gallery_lightbox',
                array(
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                )
            );
            
            $wp_customize->add_control(
                'aqualuxe_product_gallery_lightbox',
                array(
                    'label'    => __( 'Enable Gallery Lightbox', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_product_gallery_lightbox',
                    'type'     => 'checkbox',
                    'priority' => 20,
                )
            );
            
            $wp_customize->add_setting(
                'aqualuxe_product_gallery_slider',
                array(
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                )
            );
            
            $wp_customize->add_control(
                'aqualuxe_product_gallery_slider',
                array(
                    'label'    => __( 'Enable Gallery Slider', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_product_gallery_slider',
                    'type'     => 'checkbox',
                    'priority' => 30,
                )
            );
            
            $wp_customize->add_setting(
                'aqualuxe_related_products',
                array(
                    'default'           => true,
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                    'transport'         => 'refresh',
                )
            );
            
            $wp_customize->add_control(
                'aqualuxe_related_products',
                array(
                    'label'    => __( 'Show Related Products', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_related_products',
                    'type'     => 'checkbox',
                    'priority' => 40,
                )
            );
            
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
                    'label'    => __( 'Related Products Count', 'aqualuxe' ),
                    'section'  => 'aqualuxe_woocommerce_product',
                    'settings' => 'aqualuxe_related_products_count',
                    'type'     => 'number',
                    'input_attrs' => array(
                        'min'  => 2,
                        'max'  => 8,
                        'step' => 1,
                    ),
                    'priority' => 50,
                    'active_callback' => function() {
                        return get_theme_mod( 'aqualuxe_related_products', true );
                    },
                )
            );
        }
    }

    /**
     * Enqueue customizer preview scripts
     *
     * @return void
     */
    public function enqueue_customizer_preview_scripts() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            AQUALUXE_ASSETS_URI . 'js/customizer-preview.js',
            array( 'customize-preview', 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue customizer controls scripts
     *
     * @return void
     */
    public function enqueue_customizer_controls_scripts() {
        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            AQUALUXE_ASSETS_URI . 'js/customizer-controls.js',
            array( 'customize-controls', 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'aqualuxe-customizer-style',
            AQUALUXE_ASSETS_URI . 'css/customizer.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Output custom CSS
     *
     * @return void
     */
    public function output_custom_css() {
        $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
        $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#23282d' );
        $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#00a0d2' );
        $text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
        $background_color = get_theme_mod( 'aqualuxe_background_color', '#ffffff' );
        
        $body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Roboto, sans-serif' );
        $heading_font_family = get_theme_mod( 'aqualuxe_heading_font_family', 'Montserrat, sans-serif' );
        $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', '16' );
        $line_height = get_theme_mod( 'aqualuxe_line_height', '1.6' );
        
        $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
        
        $logo_width = get_theme_mod( 'aqualuxe_logo_width', '200' );
        $logo_height = get_theme_mod( 'aqualuxe_logo_height', '80' );
        
        $css = "
            :root {
                --aqualuxe-primary-color: {$primary_color};
                --aqualuxe-secondary-color: {$secondary_color};
                --aqualuxe-accent-color: {$accent_color};
                --aqualuxe-text-color: {$text_color};
                --aqualuxe-background-color: {$background_color};
                --aqualuxe-body-font-family: {$body_font_family};
                --aqualuxe-heading-font-family: {$heading_font_family};
                --aqualuxe-body-font-size: {$body_font_size}px;
                --aqualuxe-line-height: {$line_height};
                --aqualuxe-container-width: {$container_width}px;
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
            }
            
            .container {
                max-width: var(--aqualuxe-container-width);
            }
            
            a {
                color: var(--aqualuxe-primary-color);
            }
            
            a:hover {
                color: var(--aqualuxe-accent-color);
            }
            
            .custom-logo {
                width: {$logo_width}px;
                height: {$logo_height}px;
            }
            
            .button, button, input[type='button'], input[type='reset'], input[type='submit'] {
                background-color: var(--aqualuxe-primary-color);
                color: #ffffff;
            }
            
            .button:hover, button:hover, input[type='button']:hover, input[type='reset']:hover, input[type='submit']:hover {
                background-color: var(--aqualuxe-accent-color);
            }
        ";
        
        echo '<style id="aqualuxe-custom-css">' . wp_strip_all_tags( $css ) . '</style>';
    }
}

/**
 * Sanitize float
 *
 * @param float $input Float to sanitize
 * @return float
 */
function aqualuxe_sanitize_float( $input ) {
    return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}

/**
 * Sanitize select
 *
 * @param string $input   Value to sanitize
 * @param object $setting Setting object
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}