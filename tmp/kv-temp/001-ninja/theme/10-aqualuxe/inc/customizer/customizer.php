<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            )
        );
    }

    // Add theme options panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title'       => esc_html__( 'AquaLuxe Theme Options', 'aqualuxe' ),
            'description' => esc_html__( 'Customize your AquaLuxe theme settings', 'aqualuxe' ),
            'priority'    => 130,
        )
    );

    // Add sections
    aqualuxe_customize_general_section( $wp_customize );
    aqualuxe_customize_header_section( $wp_customize );
    aqualuxe_customize_footer_section( $wp_customize );
    aqualuxe_customize_colors_section( $wp_customize );
    aqualuxe_customize_typography_section( $wp_customize );
    aqualuxe_customize_layout_section( $wp_customize );
    aqualuxe_customize_blog_section( $wp_customize );
    aqualuxe_customize_woocommerce_section( $wp_customize );
    aqualuxe_customize_social_section( $wp_customize );
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * General Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_general_section( $wp_customize ) {
    // General Settings Section
    $wp_customize->add_section(
        'aqualuxe_general_settings',
        array(
            'title'    => esc_html__( 'General Settings', 'aqualuxe' ),
            'priority' => 10,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Site Layout
    $wp_customize->add_setting(
        'aqualuxe_site_layout',
        array(
            'default'           => 'wide',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_site_layout',
        array(
            'label'    => esc_html__( 'Site Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_general_settings',
            'type'     => 'select',
            'choices'  => array(
                'wide'    => esc_html__( 'Wide', 'aqualuxe' ),
                'boxed'   => esc_html__( 'Boxed', 'aqualuxe' ),
                'framed'  => esc_html__( 'Framed', 'aqualuxe' ),
            ),
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1200',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'    => esc_html__( 'Container Width (px)', 'aqualuxe' ),
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
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_back_to_top',
        array(
            'label'    => esc_html__( 'Show Back to Top Button', 'aqualuxe' ),
            'section'  => 'aqualuxe_general_settings',
            'type'     => 'checkbox',
        )
    );

    // Preloader
    $wp_customize->add_setting(
        'aqualuxe_preloader',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_preloader',
        array(
            'label'    => esc_html__( 'Show Page Preloader', 'aqualuxe' ),
            'section'  => 'aqualuxe_general_settings',
            'type'     => 'checkbox',
        )
    );

    // Preloader Style
    $wp_customize->add_setting(
        'aqualuxe_preloader_style',
        array(
            'default'           => 'spinner',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_preloader_style',
        array(
            'label'    => esc_html__( 'Preloader Style', 'aqualuxe' ),
            'section'  => 'aqualuxe_general_settings',
            'type'     => 'select',
            'choices'  => array(
                'spinner'    => esc_html__( 'Spinner', 'aqualuxe' ),
                'dots'       => esc_html__( 'Dots', 'aqualuxe' ),
                'circle'     => esc_html__( 'Circle', 'aqualuxe' ),
                'fish'       => esc_html__( 'Fish Animation', 'aqualuxe' ),
                'custom'     => esc_html__( 'Custom Image', 'aqualuxe' ),
            ),
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_preloader', true );
            },
        )
    );

    // Custom Preloader Image
    $wp_customize->add_setting(
        'aqualuxe_preloader_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_preloader_image',
            array(
                'label'    => esc_html__( 'Custom Preloader Image', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
                'active_callback' => function() {
                    return get_theme_mod( 'aqualuxe_preloader', true ) && get_theme_mod( 'aqualuxe_preloader_style', 'spinner' ) === 'custom';
                },
            )
        )
    );

    // Default Featured Image
    $wp_customize->add_setting(
        'aqualuxe_default_featured_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_default_featured_image',
            array(
                'label'    => esc_html__( 'Default Featured Image', 'aqualuxe' ),
                'description' => esc_html__( 'This image will be used when a post or product does not have a featured image.', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
            )
        )
    );

    // Default OpenGraph Image
    $wp_customize->add_setting(
        'aqualuxe_default_opengraph_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_default_opengraph_image',
            array(
                'label'    => esc_html__( 'Default OpenGraph Image', 'aqualuxe' ),
                'description' => esc_html__( 'This image will be used for social sharing when no featured image is available.', 'aqualuxe' ),
                'section'  => 'aqualuxe_general_settings',
            )
        )
    );
}

/**
 * Header Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header_section( $wp_customize ) {
    // Header Settings Section
    $wp_customize->add_section(
        'aqualuxe_header_settings',
        array(
            'title'    => esc_html__( 'Header Settings', 'aqualuxe' ),
            'priority' => 20,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Header Layout
    $wp_customize->add_setting(
        'aqualuxe_header_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_layout',
        array(
            'label'    => esc_html__( 'Header Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_settings',
            'type'     => 'select',
            'choices'  => array(
                'default'    => esc_html__( 'Default', 'aqualuxe' ),
                'centered'   => esc_html__( 'Centered', 'aqualuxe' ),
                'minimal'    => esc_html__( 'Minimal', 'aqualuxe' ),
                'split'      => esc_html__( 'Split Menu', 'aqualuxe' ),
            ),
        )
    );

    // Sticky Header
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
            'label'    => esc_html__( 'Enable Sticky Header', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_settings',
            'type'     => 'checkbox',
        )
    );

    // Transparent Header on Homepage
    $wp_customize->add_setting(
        'aqualuxe_transparent_header_home',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_transparent_header_home',
        array(
            'label'    => esc_html__( 'Transparent Header on Homepage', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Search in Header
    $wp_customize->add_setting(
        'aqualuxe_header_search',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_search',
        array(
            'label'    => esc_html__( 'Show Search in Header', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_settings',
            'type'     => 'checkbox',
        )
    );

    // Phone Number
    $wp_customize->add_setting(
        'aqualuxe_phone_number',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_phone_number',
        array(
            'label'    => esc_html__( 'Phone Number', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_settings',
            'type'     => 'text',
        )
    );

    // Email Address
    $wp_customize->add_setting(
        'aqualuxe_email_address',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_email_address',
        array(
            'label'    => esc_html__( 'Email Address', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_settings',
            'type'     => 'email',
        )
    );

    // Header Top Bar
    $wp_customize->add_setting(
        'aqualuxe_header_top_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_top_bar',
        array(
            'label'    => esc_html__( 'Show Header Top Bar', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_settings',
            'type'     => 'checkbox',
        )
    );

    // Header Top Bar Text
    $wp_customize->add_setting(
        'aqualuxe_header_top_bar_text',
        array(
            'default'           => esc_html__( 'Welcome to AquaLuxe - Premium Ornamental Fish Farm', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_top_bar_text',
        array(
            'label'    => esc_html__( 'Top Bar Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_settings',
            'type'     => 'text',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_header_top_bar', true );
            },
        )
    );
}

/**
 * Footer Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer_section( $wp_customize ) {
    // Footer Settings Section
    $wp_customize->add_section(
        'aqualuxe_footer_settings',
        array(
            'title'    => esc_html__( 'Footer Settings', 'aqualuxe' ),
            'priority' => 30,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Footer Layout
    $wp_customize->add_setting(
        'aqualuxe_footer_layout',
        array(
            'default'           => '4-columns',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_layout',
        array(
            'label'    => esc_html__( 'Footer Widget Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer_settings',
            'type'     => 'select',
            'choices'  => array(
                '4-columns'    => esc_html__( '4 Columns', 'aqualuxe' ),
                '3-columns'    => esc_html__( '3 Columns', 'aqualuxe' ),
                '2-columns'    => esc_html__( '2 Columns', 'aqualuxe' ),
                '1-column'     => esc_html__( '1 Column', 'aqualuxe' ),
                'custom'       => esc_html__( 'Custom Layout', 'aqualuxe' ),
            ),
        )
    );

    // Footer Custom Layout
    $wp_customize->add_setting(
        'aqualuxe_footer_custom_layout',
        array(
            'default'           => '3+3+3+3',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_custom_layout',
        array(
            'label'    => esc_html__( 'Custom Layout (Bootstrap columns)', 'aqualuxe' ),
            'description' => esc_html__( 'Enter column widths separated by + (total should be 12). Example: 3+3+3+3', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer_settings',
            'type'     => 'text',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_footer_layout', '4-columns' ) === 'custom';
            },
        )
    );

    // Footer Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_footer_copyright',
        array(
            'default'           => sprintf( esc_html__( '© %1$s AquaLuxe. Premium WordPress Theme by %2$s.', 'aqualuxe' ), date( 'Y' ), '<a href="https://ninjatech.ai/">NinjaTech AI</a>' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_copyright',
        array(
            'label'    => esc_html__( 'Footer Copyright Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer_settings',
            'type'     => 'textarea',
        )
    );

    // Show Payment Icons
    $wp_customize->add_setting(
        'aqualuxe_show_payment_icons',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_payment_icons',
        array(
            'label'    => esc_html__( 'Show Payment Icons', 'aqualuxe' ),
            'section'  => 'aqualuxe_footer_settings',
            'type'     => 'checkbox',
        )
    );

    // Payment Methods
    $payment_methods = array(
        'visa'       => esc_html__( 'Visa', 'aqualuxe' ),
        'mastercard' => esc_html__( 'Mastercard', 'aqualuxe' ),
        'amex'       => esc_html__( 'American Express', 'aqualuxe' ),
        'discover'   => esc_html__( 'Discover', 'aqualuxe' ),
        'paypal'     => esc_html__( 'PayPal', 'aqualuxe' ),
        'apple-pay'  => esc_html__( 'Apple Pay', 'aqualuxe' ),
        'google-pay' => esc_html__( 'Google Pay', 'aqualuxe' ),
    );

    foreach ( $payment_methods as $method => $label ) {
        $wp_customize->add_setting(
            'aqualuxe_payment_' . $method,
            array(
                'default'           => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_payment_' . $method,
            array(
                'label'    => $label,
                'section'  => 'aqualuxe_footer_settings',
                'type'     => 'checkbox',
                'active_callback' => function() {
                    return get_theme_mod( 'aqualuxe_show_payment_icons', true );
                },
            )
        );
    }

    // Footer Background Image
    $wp_customize->add_setting(
        'aqualuxe_footer_bg_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_footer_bg_image',
            array(
                'label'    => esc_html__( 'Footer Background Image', 'aqualuxe' ),
                'section'  => 'aqualuxe_footer_settings',
            )
        )
    );
}

/**
 * Colors Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors_section( $wp_customize ) {
    // Colors Settings Section
    $wp_customize->add_section(
        'aqualuxe_colors_settings',
        array(
            'title'    => esc_html__( 'Colors', 'aqualuxe' ),
            'priority' => 40,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0088cc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'    => esc_html__( 'Primary Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#005580',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'    => esc_html__( 'Secondary Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Accent Color
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default'           => '#00ccff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label'    => esc_html__( 'Accent Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Text Color
    $wp_customize->add_setting(
        'aqualuxe_text_color',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color',
            array(
                'label'    => esc_html__( 'Text Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Heading Color
    $wp_customize->add_setting(
        'aqualuxe_heading_color',
        array(
            'default'           => '#222222',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_heading_color',
            array(
                'label'    => esc_html__( 'Heading Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Background Color
    $wp_customize->add_setting(
        'aqualuxe_background_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_background_color',
            array(
                'label'    => esc_html__( 'Background Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Header Background Color
    $wp_customize->add_setting(
        'aqualuxe_header_bg_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_header_bg_color',
            array(
                'label'    => esc_html__( 'Header Background Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Footer Background Color
    $wp_customize->add_setting(
        'aqualuxe_footer_bg_color',
        array(
            'default'           => '#222222',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_bg_color',
            array(
                'label'    => esc_html__( 'Footer Background Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Footer Text Color
    $wp_customize->add_setting(
        'aqualuxe_footer_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_text_color',
            array(
                'label'    => esc_html__( 'Footer Text Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Button Background Color
    $wp_customize->add_setting(
        'aqualuxe_button_bg_color',
        array(
            'default'           => '#0088cc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_bg_color',
            array(
                'label'    => esc_html__( 'Button Background Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Button Text Color
    $wp_customize->add_setting(
        'aqualuxe_button_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_text_color',
            array(
                'label'    => esc_html__( 'Button Text Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Button Hover Background Color
    $wp_customize->add_setting(
        'aqualuxe_button_hover_bg_color',
        array(
            'default'           => '#005580',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_hover_bg_color',
            array(
                'label'    => esc_html__( 'Button Hover Background Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );

    // Button Hover Text Color
    $wp_customize->add_setting(
        'aqualuxe_button_hover_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_hover_text_color',
            array(
                'label'    => esc_html__( 'Button Hover Text Color', 'aqualuxe' ),
                'section'  => 'aqualuxe_colors_settings',
            )
        )
    );
}

/**
 * Typography Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography_section( $wp_customize ) {
    // Typography Settings Section
    $wp_customize->add_section(
        'aqualuxe_typography_settings',
        array(
            'title'    => esc_html__( 'Typography', 'aqualuxe' ),
            'priority' => 50,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Body Font Family
    $wp_customize->add_setting(
        'aqualuxe_body_font_family',
        array(
            'default'           => 'Montserrat',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_family',
        array(
            'label'    => esc_html__( 'Body Font Family', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'select',
            'choices'  => array(
                'Montserrat'      => 'Montserrat',
                'Open Sans'       => 'Open Sans',
                'Roboto'          => 'Roboto',
                'Lato'            => 'Lato',
                'Poppins'         => 'Poppins',
                'Raleway'         => 'Raleway',
                'Nunito'          => 'Nunito',
                'Source Sans Pro' => 'Source Sans Pro',
                'PT Sans'         => 'PT Sans',
                'Rubik'           => 'Rubik',
            ),
        )
    );

    // Heading Font Family
    $wp_customize->add_setting(
        'aqualuxe_heading_font_family',
        array(
            'default'           => 'Playfair Display',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_family',
        array(
            'label'    => esc_html__( 'Heading Font Family', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'select',
            'choices'  => array(
                'Playfair Display' => 'Playfair Display',
                'Montserrat'       => 'Montserrat',
                'Roboto'           => 'Roboto',
                'Lato'             => 'Lato',
                'Poppins'          => 'Poppins',
                'Raleway'          => 'Raleway',
                'Merriweather'     => 'Merriweather',
                'Oswald'           => 'Oswald',
                'Lora'             => 'Lora',
                'Cormorant Garamond' => 'Cormorant Garamond',
            ),
        )
    );

    // Body Font Size
    $wp_customize->add_setting(
        'aqualuxe_body_font_size',
        array(
            'default'           => '16',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_size',
        array(
            'label'    => esc_html__( 'Body Font Size (px)', 'aqualuxe' ),
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
            'default'           => '1.6',
            'sanitize_callback' => 'aqualuxe_sanitize_float',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_line_height',
        array(
            'label'    => esc_html__( 'Body Line Height', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 3,
                'step' => 0.1,
            ),
        )
    );

    // H1 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h1_font_size',
        array(
            'default'           => '36',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h1_font_size',
        array(
            'label'    => esc_html__( 'H1 Font Size (px)', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 20,
                'max'  => 72,
                'step' => 1,
            ),
        )
    );

    // H2 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h2_font_size',
        array(
            'default'           => '30',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h2_font_size',
        array(
            'label'    => esc_html__( 'H2 Font Size (px)', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 18,
                'max'  => 60,
                'step' => 1,
            ),
        )
    );

    // H3 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h3_font_size',
        array(
            'default'           => '24',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h3_font_size',
        array(
            'label'    => esc_html__( 'H3 Font Size (px)', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 16,
                'max'  => 48,
                'step' => 1,
            ),
        )
    );

    // H4 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h4_font_size',
        array(
            'default'           => '20',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h4_font_size',
        array(
            'label'    => esc_html__( 'H4 Font Size (px)', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 14,
                'max'  => 36,
                'step' => 1,
            ),
        )
    );

    // H5 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h5_font_size',
        array(
            'default'           => '18',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h5_font_size',
        array(
            'label'    => esc_html__( 'H5 Font Size (px)', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 30,
                'step' => 1,
            ),
        )
    );

    // H6 Font Size
    $wp_customize->add_setting(
        'aqualuxe_h6_font_size',
        array(
            'default'           => '16',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_h6_font_size',
        array(
            'label'    => esc_html__( 'H6 Font Size (px)', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 24,
                'step' => 1,
            ),
        )
    );

    // Heading Line Height
    $wp_customize->add_setting(
        'aqualuxe_heading_line_height',
        array(
            'default'           => '1.2',
            'sanitize_callback' => 'aqualuxe_sanitize_float',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_line_height',
        array(
            'label'    => esc_html__( 'Heading Line Height', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 2,
                'step' => 0.1,
            ),
        )
    );

    // Font Weight
    $wp_customize->add_setting(
        'aqualuxe_body_font_weight',
        array(
            'default'           => '400',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_weight',
        array(
            'label'    => esc_html__( 'Body Font Weight', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'select',
            'choices'  => array(
                '300' => esc_html__( 'Light (300)', 'aqualuxe' ),
                '400' => esc_html__( 'Regular (400)', 'aqualuxe' ),
                '500' => esc_html__( 'Medium (500)', 'aqualuxe' ),
                '600' => esc_html__( 'Semi-Bold (600)', 'aqualuxe' ),
                '700' => esc_html__( 'Bold (700)', 'aqualuxe' ),
            ),
        )
    );

    // Heading Font Weight
    $wp_customize->add_setting(
        'aqualuxe_heading_font_weight',
        array(
            'default'           => '600',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_weight',
        array(
            'label'    => esc_html__( 'Heading Font Weight', 'aqualuxe' ),
            'section'  => 'aqualuxe_typography_settings',
            'type'     => 'select',
            'choices'  => array(
                '400' => esc_html__( 'Regular (400)', 'aqualuxe' ),
                '500' => esc_html__( 'Medium (500)', 'aqualuxe' ),
                '600' => esc_html__( 'Semi-Bold (600)', 'aqualuxe' ),
                '700' => esc_html__( 'Bold (700)', 'aqualuxe' ),
            ),
        )
    );
}

/**
 * Layout Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_layout_section( $wp_customize ) {
    // Layout Settings Section
    $wp_customize->add_section(
        'aqualuxe_layout_settings',
        array(
            'title'    => esc_html__( 'Layout Settings', 'aqualuxe' ),
            'priority' => 60,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Page Layout
    $wp_customize->add_setting(
        'aqualuxe_page_layout',
        array(
            'default'           => 'full-width',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_page_layout',
        array(
            'label'    => esc_html__( 'Default Page Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_settings',
            'type'     => 'select',
            'choices'  => array(
                'full-width'    => esc_html__( 'Full Width', 'aqualuxe' ),
                'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
            ),
        )
    );

    // Blog Layout
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_layout',
        array(
            'label'    => esc_html__( 'Blog Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_settings',
            'type'     => 'select',
            'choices'  => array(
                'full-width'    => esc_html__( 'Full Width', 'aqualuxe' ),
                'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
            ),
        )
    );

    // Single Post Layout
    $wp_customize->add_setting(
        'aqualuxe_single_post_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_single_post_layout',
        array(
            'label'    => esc_html__( 'Single Post Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_settings',
            'type'     => 'select',
            'choices'  => array(
                'full-width'    => esc_html__( 'Full Width', 'aqualuxe' ),
                'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
            ),
        )
    );

    // Archive Layout
    $wp_customize->add_setting(
        'aqualuxe_archive_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_archive_layout',
        array(
            'label'    => esc_html__( 'Archive Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_settings',
            'type'     => 'select',
            'choices'  => array(
                'full-width'    => esc_html__( 'Full Width', 'aqualuxe' ),
                'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
            ),
        )
    );

    // Search Layout
    $wp_customize->add_setting(
        'aqualuxe_search_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_search_layout',
        array(
            'label'    => esc_html__( 'Search Results Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_settings',
            'type'     => 'select',
            'choices'  => array(
                'full-width'    => esc_html__( 'Full Width', 'aqualuxe' ),
                'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
            ),
        )
    );

    // WooCommerce Shop Layout
    $wp_customize->add_setting(
        'aqualuxe_shop_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_layout',
        array(
            'label'    => esc_html__( 'Shop Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_settings',
            'type'     => 'select',
            'choices'  => array(
                'full-width'    => esc_html__( 'Full Width', 'aqualuxe' ),
                'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
            ),
            'active_callback' => function() {
                return class_exists( 'WooCommerce' );
            },
        )
    );

    // WooCommerce Single Product Layout
    $wp_customize->add_setting(
        'aqualuxe_product_layout',
        array(
            'default'           => 'full-width',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_layout',
        array(
            'label'    => esc_html__( 'Single Product Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_settings',
            'type'     => 'select',
            'choices'  => array(
                'full-width'    => esc_html__( 'Full Width', 'aqualuxe' ),
                'right-sidebar' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'aqualuxe' ),
            ),
            'active_callback' => function() {
                return class_exists( 'WooCommerce' );
            },
        )
    );

    // Content Width
    $wp_customize->add_setting(
        'aqualuxe_content_width',
        array(
            'default'           => '67',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_content_width',
        array(
            'label'    => esc_html__( 'Content Width (%)', 'aqualuxe' ),
            'description' => esc_html__( 'Width of the content area when sidebar is present.', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 50,
                'max'  => 80,
                'step' => 1,
            ),
        )
    );

    // Sidebar Width
    $wp_customize->add_setting(
        'aqualuxe_sidebar_width',
        array(
            'default'           => '33',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sidebar_width',
        array(
            'label'    => esc_html__( 'Sidebar Width (%)', 'aqualuxe' ),
            'description' => esc_html__( 'Width of the sidebar when present.', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 20,
                'max'  => 50,
                'step' => 1,
            ),
        )
    );
}

/**
 * Blog Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog_section( $wp_customize ) {
    // Blog Settings Section
    $wp_customize->add_section(
        'aqualuxe_blog_settings',
        array(
            'title'    => esc_html__( 'Blog Settings', 'aqualuxe' ),
            'priority' => 70,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Blog Style
    $wp_customize->add_setting(
        'aqualuxe_blog_style',
        array(
            'default'           => 'standard',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_style',
        array(
            'label'    => esc_html__( 'Blog Style', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'select',
            'choices'  => array(
                'standard'    => esc_html__( 'Standard', 'aqualuxe' ),
                'grid'        => esc_html__( 'Grid', 'aqualuxe' ),
                'masonry'     => esc_html__( 'Masonry', 'aqualuxe' ),
                'list'        => esc_html__( 'List', 'aqualuxe' ),
            ),
        )
    );

    // Grid Columns
    $wp_customize->add_setting(
        'aqualuxe_blog_grid_columns',
        array(
            'default'           => '2',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_grid_columns',
        array(
            'label'    => esc_html__( 'Grid/Masonry Columns', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'select',
            'choices'  => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ),
            'active_callback' => function() {
                $style = get_theme_mod( 'aqualuxe_blog_style', 'standard' );
                return ( $style === 'grid' || $style === 'masonry' );
            },
        )
    );

    // Posts Per Page
    $wp_customize->add_setting(
        'aqualuxe_posts_per_page',
        array(
            'default'           => get_option( 'posts_per_page' ),
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_posts_per_page',
        array(
            'label'    => esc_html__( 'Posts Per Page', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 50,
                'step' => 1,
            ),
        )
    );

    // Excerpt Length
    $wp_customize->add_setting(
        'aqualuxe_excerpt_length',
        array(
            'default'           => '55',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_excerpt_length',
        array(
            'label'    => esc_html__( 'Excerpt Length (words)', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 200,
                'step' => 5,
            ),
        )
    );

    // Read More Text
    $wp_customize->add_setting(
        'aqualuxe_read_more_text',
        array(
            'default'           => esc_html__( 'Read More', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_read_more_text',
        array(
            'label'    => esc_html__( 'Read More Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'text',
        )
    );

    // Show Featured Image
    $wp_customize->add_setting(
        'aqualuxe_show_featured_image',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_featured_image',
        array(
            'label'    => esc_html__( 'Show Featured Image', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Post Date
    $wp_customize->add_setting(
        'aqualuxe_show_post_date',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_date',
        array(
            'label'    => esc_html__( 'Show Post Date', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Post Author
    $wp_customize->add_setting(
        'aqualuxe_show_post_author',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_author',
        array(
            'label'    => esc_html__( 'Show Post Author', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Post Categories
    $wp_customize->add_setting(
        'aqualuxe_show_post_categories',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_categories',
        array(
            'label'    => esc_html__( 'Show Post Categories', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Post Tags
    $wp_customize->add_setting(
        'aqualuxe_show_post_tags',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_tags',
        array(
            'label'    => esc_html__( 'Show Post Tags', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Post Comments Count
    $wp_customize->add_setting(
        'aqualuxe_show_post_comments',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_comments',
        array(
            'label'    => esc_html__( 'Show Post Comments Count', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Related Posts
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
            'label'    => esc_html__( 'Show Related Posts', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Author Bio
    $wp_customize->add_setting(
        'aqualuxe_show_author_bio',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_author_bio',
        array(
            'label'    => esc_html__( 'Show Author Bio', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Post Navigation
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
            'label'    => esc_html__( 'Show Post Navigation', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Social Sharing
    $wp_customize->add_setting(
        'aqualuxe_show_social_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_social_sharing',
        array(
            'label'    => esc_html__( 'Show Social Sharing', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_settings',
            'type'     => 'checkbox',
        )
    );
}

/**
 * WooCommerce Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce_section( $wp_customize ) {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // WooCommerce Settings Section
    $wp_customize->add_section(
        'aqualuxe_woocommerce_settings',
        array(
            'title'    => esc_html__( 'WooCommerce Settings', 'aqualuxe' ),
            'priority' => 80,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Products Per Page
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
            'label'    => esc_html__( 'Products Per Page', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 48,
                'step' => 1,
            ),
        )
    );

    // Shop Columns
    $wp_customize->add_setting(
        'aqualuxe_shop_columns',
        array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_columns',
        array(
            'label'    => esc_html__( 'Shop Columns', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'select',
            'choices'  => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
            ),
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
            'label'    => esc_html__( 'Related Products Count', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 12,
                'step' => 1,
            ),
        )
    );

    // Related Products Columns
    $wp_customize->add_setting(
        'aqualuxe_related_products_columns',
        array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_products_columns',
        array(
            'label'    => esc_html__( 'Related Products Columns', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'select',
            'choices'  => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
            ),
        )
    );

    // Product Gallery Zoom
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
            'label'    => esc_html__( 'Enable Product Gallery Zoom', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'checkbox',
        )
    );

    // Product Gallery Lightbox
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
            'label'    => esc_html__( 'Enable Product Gallery Lightbox', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'checkbox',
        )
    );

    // Product Gallery Slider
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
            'label'    => esc_html__( 'Enable Product Gallery Slider', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'checkbox',
        )
    );

    // AJAX Add to Cart
    $wp_customize->add_setting(
        'aqualuxe_ajax_add_to_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_ajax_add_to_cart',
        array(
            'label'    => esc_html__( 'Enable AJAX Add to Cart', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'checkbox',
        )
    );

    // Quick View
    $wp_customize->add_setting(
        'aqualuxe_quick_view',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_quick_view',
        array(
            'label'    => esc_html__( 'Enable Quick View', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'checkbox',
        )
    );

    // Wishlist
    $wp_customize->add_setting(
        'aqualuxe_wishlist',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_wishlist',
        array(
            'label'    => esc_html__( 'Enable Wishlist', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'checkbox',
        )
    );

    // Product Comparison
    $wp_customize->add_setting(
        'aqualuxe_product_comparison',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_comparison',
        array(
            'label'    => esc_html__( 'Enable Product Comparison', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'checkbox',
        )
    );

    // Sale Badge Style
    $wp_customize->add_setting(
        'aqualuxe_sale_badge_style',
        array(
            'default'           => 'percentage',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sale_badge_style',
        array(
            'label'    => esc_html__( 'Sale Badge Style', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'select',
            'choices'  => array(
                'standard'    => esc_html__( 'Standard', 'aqualuxe' ),
                'percentage'  => esc_html__( 'Percentage', 'aqualuxe' ),
                'text'        => esc_html__( 'Custom Text', 'aqualuxe' ),
            ),
        )
    );

    // Sale Badge Text
    $wp_customize->add_setting(
        'aqualuxe_sale_badge_text',
        array(
            'default'           => esc_html__( 'Sale!', 'aqualuxe' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sale_badge_text',
        array(
            'label'    => esc_html__( 'Sale Badge Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'text',
            'active_callback' => function() {
                return get_theme_mod( 'aqualuxe_sale_badge_style', 'percentage' ) === 'text';
            },
        )
    );

    // Product Hover Effect
    $wp_customize->add_setting(
        'aqualuxe_product_hover_effect',
        array(
            'default'           => 'zoom',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_hover_effect',
        array(
            'label'    => esc_html__( 'Product Image Hover Effect', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'select',
            'choices'  => array(
                'none'        => esc_html__( 'None', 'aqualuxe' ),
                'zoom'        => esc_html__( 'Zoom', 'aqualuxe' ),
                'fade'        => esc_html__( 'Fade', 'aqualuxe' ),
                'flip'        => esc_html__( 'Flip', 'aqualuxe' ),
                'second-image' => esc_html__( 'Second Image', 'aqualuxe' ),
            ),
        )
    );

    // Enable Product Inquiry
    $wp_customize->add_setting(
        'aqualuxe_enable_product_inquiry',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_product_inquiry',
        array(
            'label'    => esc_html__( 'Enable Product Inquiry Form', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'checkbox',
        )
    );

    // Delivery Min Days
    $wp_customize->add_setting(
        'aqualuxe_delivery_min_days',
        array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_delivery_min_days',
        array(
            'label'    => esc_html__( 'Minimum Delivery Days', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 30,
                'step' => 1,
            ),
        )
    );

    // Delivery Max Days
    $wp_customize->add_setting(
        'aqualuxe_delivery_max_days',
        array(
            'default'           => '7',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_delivery_max_days',
        array(
            'label'    => esc_html__( 'Maximum Delivery Days', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 30,
                'step' => 1,
            ),
        )
    );

    // Product Guarantee
    $wp_customize->add_setting(
        'aqualuxe_product_guarantee',
        array(
            'default'           => esc_html__( 'Live Arrival Guarantee: We guarantee that your fish will arrive alive and healthy.', 'aqualuxe' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_guarantee',
        array(
            'label'    => esc_html__( 'Product Guarantee Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'textarea',
        )
    );

    // Product Shipping Info
    $wp_customize->add_setting(
        'aqualuxe_product_shipping_info',
        array(
            'default'           => esc_html__( 'Free shipping on orders over $100. Expedited shipping options available at checkout.', 'aqualuxe' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_shipping_info',
        array(
            'label'    => esc_html__( 'Product Shipping Info Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'textarea',
        )
    );

    // Product Return Policy
    $wp_customize->add_setting(
        'aqualuxe_product_return_policy',
        array(
            'default'           => esc_html__( '30-day return policy for non-living products. Live arrival guarantee for all fish.', 'aqualuxe' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_return_policy',
        array(
            'label'    => esc_html__( 'Product Return Policy Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'textarea',
        )
    );

    // Product Secure Payment
    $wp_customize->add_setting(
        'aqualuxe_product_secure_payment',
        array(
            'default'           => esc_html__( 'Secure payment processing. Your payment information is never stored.', 'aqualuxe' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_secure_payment',
        array(
            'label'    => esc_html__( 'Product Secure Payment Text', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'textarea',
        )
    );

    // Shipping Tab Content
    $wp_customize->add_setting(
        'aqualuxe_shipping_tab_content',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shipping_tab_content',
        array(
            'label'    => esc_html__( 'Default Shipping Tab Content', 'aqualuxe' ),
            'description' => esc_html__( 'Default content for the Shipping & Returns tab on product pages. Leave empty to use the theme default.', 'aqualuxe' ),
            'section'  => 'aqualuxe_woocommerce_settings',
            'type'     => 'textarea',
        )
    );
}

/**
 * Social Media Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_social_section( $wp_customize ) {
    // Social Media Settings Section
    $wp_customize->add_section(
        'aqualuxe_social_settings',
        array(
            'title'    => esc_html__( 'Social Media', 'aqualuxe' ),
            'priority' => 90,
            'panel'    => 'aqualuxe_theme_options',
        )
    );

    // Show Social Icons in Header
    $wp_customize->add_setting(
        'aqualuxe_social_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_header',
        array(
            'label'    => esc_html__( 'Show Social Icons in Header', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_settings',
            'type'     => 'checkbox',
        )
    );

    // Show Social Icons in Footer
    $wp_customize->add_setting(
        'aqualuxe_social_footer',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_footer',
        array(
            'label'    => esc_html__( 'Show Social Icons in Footer', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_settings',
            'type'     => 'checkbox',
        )
    );

    // Social Media Profiles
    $social_platforms = array(
        'facebook'   => esc_html__( 'Facebook', 'aqualuxe' ),
        'twitter'    => esc_html__( 'Twitter', 'aqualuxe' ),
        'instagram'  => esc_html__( 'Instagram', 'aqualuxe' ),
        'youtube'    => esc_html__( 'YouTube', 'aqualuxe' ),
        'pinterest'  => esc_html__( 'Pinterest', 'aqualuxe' ),
        'linkedin'   => esc_html__( 'LinkedIn', 'aqualuxe' ),
        'tiktok'     => esc_html__( 'TikTok', 'aqualuxe' ),
        'snapchat'   => esc_html__( 'Snapchat', 'aqualuxe' ),
        'whatsapp'   => esc_html__( 'WhatsApp', 'aqualuxe' ),
        'telegram'   => esc_html__( 'Telegram', 'aqualuxe' ),
    );

    foreach ( $social_platforms as $platform => $label ) {
        $wp_customize->add_setting(
            'aqualuxe_social_' . $platform,
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_social_' . $platform,
            array(
                'label'    => $label,
                'section'  => 'aqualuxe_social_settings',
                'type'     => 'url',
            )
        );
    }

    // Social Icon Style
    $wp_customize->add_setting(
        'aqualuxe_social_icon_style',
        array(
            'default'           => 'rounded',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_icon_style',
        array(
            'label'    => esc_html__( 'Social Icon Style', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_settings',
            'type'     => 'select',
            'choices'  => array(
                'rounded'    => esc_html__( 'Rounded', 'aqualuxe' ),
                'square'     => esc_html__( 'Square', 'aqualuxe' ),
                'circle'     => esc_html__( 'Circle', 'aqualuxe' ),
                'plain'      => esc_html__( 'Plain', 'aqualuxe' ),
            ),
        )
    );

    // Social Icon Size
    $wp_customize->add_setting(
        'aqualuxe_social_icon_size',
        array(
            'default'           => 'medium',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_icon_size',
        array(
            'label'    => esc_html__( 'Social Icon Size', 'aqualuxe' ),
            'section'  => 'aqualuxe_social_settings',
            'type'     => 'select',
            'choices'  => array(
                'small'     => esc_html__( 'Small', 'aqualuxe' ),
                'medium'    => esc_html__( 'Medium', 'aqualuxe' ),
                'large'     => esc_html__( 'Large', 'aqualuxe' ),
            ),
        )
    );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script( 'aqualuxe-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Sanitize select.
 *
 * @param string $input The input from the setting.
 * @param object $setting The selected setting.
 * @return string The sanitized input.
 */
function aqualuxe_sanitize_select( $input, $setting = null ) {
    // Get the list of possible select options
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // Return input if valid or return default option
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize float.
 *
 * @param float $input The input from the setting.
 * @return float The sanitized input.
 */
function aqualuxe_sanitize_float( $input ) {
    return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}

/**
 * Generate CSS from customizer settings
 */
function aqualuxe_generate_customizer_css() {
    $css = '';

    // Primary Color
    $primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0088cc' );
    if ( $primary_color ) {
        $css .= '
            :root {
                --aqualuxe-primary-color: ' . esc_attr( $primary_color ) . ';
            }
            
            a,
            .site-title a:hover,
            .main-navigation ul li:hover > a,
            .main-navigation ul li.current-menu-item > a,
            .entry-meta a:hover,
            .entry-footer a:hover,
            .comment-metadata a:hover,
            .widget a:hover,
            .social-links a:hover {
                color: ' . esc_attr( $primary_color ) . ';
            }
            
            button,
            input[type="button"],
            input[type="reset"],
            input[type="submit"],
            .button,
            .pagination .current,
            .pagination a:hover,
            .page-links a:hover,
            .widget_tag_cloud a:hover,
            .back-to-top,
            .woocommerce #respond input#submit, 
            .woocommerce a.button, 
            .woocommerce button.button, 
            .woocommerce input.button,
            .woocommerce span.onsale,
            .woocommerce nav.woocommerce-pagination ul li a:hover,
            .woocommerce nav.woocommerce-pagination ul li span.current {
                background-color: ' . esc_attr( $primary_color ) . ';
            }
            
            blockquote,
            .pagination .current,
            .pagination a:hover,
            .page-links a:hover,
            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="url"]:focus,
            input[type="password"]:focus,
            input[type="search"]:focus,
            input[type="number"]:focus,
            input[type="tel"]:focus,
            input[type="range"]:focus,
            input[type="date"]:focus,
            input[type="month"]:focus,
            input[type="week"]:focus,
            input[type="time"]:focus,
            input[type="datetime"]:focus,
            input[type="datetime-local"]:focus,
            input[type="color"]:focus,
            textarea:focus {
                border-color: ' . esc_attr( $primary_color ) . ';
            }
        ';
    }

    // Secondary Color
    $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#005580' );
    if ( $secondary_color ) {
        $css .= '
            :root {
                --aqualuxe-secondary-color: ' . esc_attr( $secondary_color ) . ';
            }
            
            a:hover,
            a:focus,
            a:active {
                color: ' . esc_attr( $secondary_color ) . ';
            }
            
            button:hover,
            input[type="button"]:hover,
            input[type="reset"]:hover,
            input[type="submit"]:hover,
            .button:hover,
            .back-to-top:hover,
            .woocommerce #respond input#submit:hover, 
            .woocommerce a.button:hover, 
            .woocommerce button.button:hover, 
            .woocommerce input.button:hover {
                background-color: ' . esc_attr( $secondary_color ) . ';
            }
        ';
    }

    // Accent Color
    $accent_color = get_theme_mod( 'aqualuxe_accent_color', '#00ccff' );
    if ( $accent_color ) {
        $css .= '
            :root {
                --aqualuxe-accent-color: ' . esc_attr( $accent_color ) . ';
            }
            
            .header-top,
            .product-badge.new-badge,
            .woocommerce ul.products li.product .product-actions .quick-view-button:hover,
            .woocommerce div.product .woocommerce-tabs ul.tabs li.active {
                background-color: ' . esc_attr( $accent_color ) . ';
            }
        ';
    }

    // Text Color
    $text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
    if ( $text_color ) {
        $css .= '
            :root {
                --aqualuxe-text-color: ' . esc_attr( $text_color ) . ';
            }
            
            body,
            button,
            input,
            select,
            optgroup,
            textarea {
                color: ' . esc_attr( $text_color ) . ';
            }
        ';
    }

    // Heading Color
    $heading_color = get_theme_mod( 'aqualuxe_heading_color', '#222222' );
    if ( $heading_color ) {
        $css .= '
            :root {
                --aqualuxe-heading-color: ' . esc_attr( $heading_color ) . ';
            }
            
            h1, h2, h3, h4, h5, h6 {
                color: ' . esc_attr( $heading_color ) . ';
            }
        ';
    }

    // Background Color
    $background_color = get_theme_mod( 'aqualuxe_background_color', '#ffffff' );
    if ( $background_color ) {
        $css .= '
            :root {
                --aqualuxe-background-color: ' . esc_attr( $background_color ) . ';
            }
            
            body {
                background-color: ' . esc_attr( $background_color ) . ';
            }
        ';
    }

    // Header Background Color
    $header_bg_color = get_theme_mod( 'aqualuxe_header_bg_color', '#ffffff' );
    if ( $header_bg_color ) {
        $css .= '
            :root {
                --aqualuxe-header-bg-color: ' . esc_attr( $header_bg_color ) . ';
            }
            
            .site-header {
                background-color: ' . esc_attr( $header_bg_color ) . ';
            }
        ';
    }

    // Footer Background Color
    $footer_bg_color = get_theme_mod( 'aqualuxe_footer_bg_color', '#222222' );
    if ( $footer_bg_color ) {
        $css .= '
            :root {
                --aqualuxe-footer-bg-color: ' . esc_attr( $footer_bg_color ) . ';
            }
            
            .site-footer {
                background-color: ' . esc_attr( $footer_bg_color ) . ';
            }
        ';
    }

    // Footer Text Color
    $footer_text_color = get_theme_mod( 'aqualuxe_footer_text_color', '#ffffff' );
    if ( $footer_text_color ) {
        $css .= '
            :root {
                --aqualuxe-footer-text-color: ' . esc_attr( $footer_text_color ) . ';
            }
            
            .site-footer {
                color: ' . esc_attr( $footer_text_color ) . ';
            }
            
            .site-footer a {
                color: ' . esc_attr( $footer_text_color ) . ';
                opacity: 0.8;
            }
            
            .site-footer a:hover {
                opacity: 1;
            }
        ';
    }

    // Button Colors
    $button_bg_color = get_theme_mod( 'aqualuxe_button_bg_color', '#0088cc' );
    $button_text_color = get_theme_mod( 'aqualuxe_button_text_color', '#ffffff' );
    $button_hover_bg_color = get_theme_mod( 'aqualuxe_button_hover_bg_color', '#005580' );
    $button_hover_text_color = get_theme_mod( 'aqualuxe_button_hover_text_color', '#ffffff' );

    if ( $button_bg_color || $button_text_color || $button_hover_bg_color || $button_hover_text_color ) {
        $css .= '
            :root {
                --aqualuxe-button-bg-color: ' . esc_attr( $button_bg_color ) . ';
                --aqualuxe-button-text-color: ' . esc_attr( $button_text_color ) . ';
                --aqualuxe-button-hover-bg-color: ' . esc_attr( $button_hover_bg_color ) . ';
                --aqualuxe-button-hover-text-color: ' . esc_attr( $button_hover_text_color ) . ';
            }
            
            button,
            input[type="button"],
            input[type="reset"],
            input[type="submit"],
            .button,
            .woocommerce #respond input#submit, 
            .woocommerce a.button, 
            .woocommerce button.button, 
            .woocommerce input.button {
                background-color: ' . esc_attr( $button_bg_color ) . ';
                color: ' . esc_attr( $button_text_color ) . ';
            }
            
            button:hover,
            input[type="button"]:hover,
            input[type="reset"]:hover,
            input[type="submit"]:hover,
            .button:hover,
            .woocommerce #respond input#submit:hover, 
            .woocommerce a.button:hover, 
            .woocommerce button.button:hover, 
            .woocommerce input.button:hover {
                background-color: ' . esc_attr( $button_hover_bg_color ) . ';
                color: ' . esc_attr( $button_hover_text_color ) . ';
            }
        ';
    }

    // Typography
    $body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Montserrat' );
    $heading_font_family = get_theme_mod( 'aqualuxe_heading_font_family', 'Playfair Display' );
    $body_font_size = get_theme_mod( 'aqualuxe_body_font_size', '16' );
    $body_line_height = get_theme_mod( 'aqualuxe_body_line_height', '1.6' );
    $heading_line_height = get_theme_mod( 'aqualuxe_heading_line_height', '1.2' );
    $body_font_weight = get_theme_mod( 'aqualuxe_body_font_weight', '400' );
    $heading_font_weight = get_theme_mod( 'aqualuxe_heading_font_weight', '600' );

    if ( $body_font_family || $heading_font_family || $body_font_size || $body_line_height || $heading_line_height || $body_font_weight || $heading_font_weight ) {
        $css .= '
            :root {
                --aqualuxe-body-font-family: "' . esc_attr( $body_font_family ) . '", sans-serif;
                --aqualuxe-heading-font-family: "' . esc_attr( $heading_font_family ) . '", serif;
                --aqualuxe-body-font-size: ' . esc_attr( $body_font_size ) . 'px;
                --aqualuxe-body-line-height: ' . esc_attr( $body_line_height ) . ';
                --aqualuxe-heading-line-height: ' . esc_attr( $heading_line_height ) . ';
                --aqualuxe-body-font-weight: ' . esc_attr( $body_font_weight ) . ';
                --aqualuxe-heading-font-weight: ' . esc_attr( $heading_font_weight ) . ';
            }
            
            body,
            button,
            input,
            select,
            optgroup,
            textarea {
                font-family: "' . esc_attr( $body_font_family ) . '", sans-serif;
                font-size: ' . esc_attr( $body_font_size ) . 'px;
                line-height: ' . esc_attr( $body_line_height ) . ';
                font-weight: ' . esc_attr( $body_font_weight ) . ';
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: "' . esc_attr( $heading_font_family ) . '", serif;
                line-height: ' . esc_attr( $heading_line_height ) . ';
                font-weight: ' . esc_attr( $heading_font_weight ) . ';
            }
        ';
    }

    // Heading Font Sizes
    $h1_font_size = get_theme_mod( 'aqualuxe_h1_font_size', '36' );
    $h2_font_size = get_theme_mod( 'aqualuxe_h2_font_size', '30' );
    $h3_font_size = get_theme_mod( 'aqualuxe_h3_font_size', '24' );
    $h4_font_size = get_theme_mod( 'aqualuxe_h4_font_size', '20' );
    $h5_font_size = get_theme_mod( 'aqualuxe_h5_font_size', '18' );
    $h6_font_size = get_theme_mod( 'aqualuxe_h6_font_size', '16' );

    if ( $h1_font_size || $h2_font_size || $h3_font_size || $h4_font_size || $h5_font_size || $h6_font_size ) {
        $css .= '
            :root {
                --aqualuxe-h1-font-size: ' . esc_attr( $h1_font_size ) . 'px;
                --aqualuxe-h2-font-size: ' . esc_attr( $h2_font_size ) . 'px;
                --aqualuxe-h3-font-size: ' . esc_attr( $h3_font_size ) . 'px;
                --aqualuxe-h4-font-size: ' . esc_attr( $h4_font_size ) . 'px;
                --aqualuxe-h5-font-size: ' . esc_attr( $h5_font_size ) . 'px;
                --aqualuxe-h6-font-size: ' . esc_attr( $h6_font_size ) . 'px;
            }
            
            h1 {
                font-size: ' . esc_attr( $h1_font_size ) . 'px;
            }
            
            h2 {
                font-size: ' . esc_attr( $h2_font_size ) . 'px;
            }
            
            h3 {
                font-size: ' . esc_attr( $h3_font_size ) . 'px;
            }
            
            h4 {
                font-size: ' . esc_attr( $h4_font_size ) . 'px;
            }
            
            h5 {
                font-size: ' . esc_attr( $h5_font_size ) . 'px;
            }
            
            h6 {
                font-size: ' . esc_attr( $h6_font_size ) . 'px;
            }
        ';
    }

    // Container Width
    $container_width = get_theme_mod( 'aqualuxe_container_width', '1200' );
    if ( $container_width ) {
        $css .= '
            :root {
                --aqualuxe-container-width: ' . esc_attr( $container_width ) . 'px;
            }
            
            .container {
                max-width: ' . esc_attr( $container_width ) . 'px;
            }
        ';
    }

    // Content and Sidebar Width
    $content_width = get_theme_mod( 'aqualuxe_content_width', '67' );
    $sidebar_width = get_theme_mod( 'aqualuxe_sidebar_width', '33' );
    if ( $content_width || $sidebar_width ) {
        $css .= '
            :root {
                --aqualuxe-content-width: ' . esc_attr( $content_width ) . '%;
                --aqualuxe-sidebar-width: ' . esc_attr( $sidebar_width ) . '%;
            }
            
            @media (min-width: 992px) {
                .right-sidebar .content-area,
                .left-sidebar .content-area {
                    width: ' . esc_attr( $content_width ) . '%;
                }
                
                .right-sidebar .widget-area,
                .left-sidebar .widget-area {
                    width: ' . esc_attr( $sidebar_width ) . '%;
                }
            }
        ';
    }

    // Footer Background Image
    $footer_bg_image = get_theme_mod( 'aqualuxe_footer_bg_image', '' );
    if ( $footer_bg_image ) {
        $css .= '
            .site-footer {
                background-image: url(' . esc_url( $footer_bg_image ) . ');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
        ';
    }

    // Social Icon Style
    $social_icon_style = get_theme_mod( 'aqualuxe_social_icon_style', 'rounded' );
    $social_icon_size = get_theme_mod( 'aqualuxe_social_icon_size', 'medium' );
    
    if ( $social_icon_style || $social_icon_size ) {
        $css .= '
            .social-links a {
        ';
        
        if ( $social_icon_style === 'rounded' ) {
            $css .= '
                border-radius: 5px;
            ';
        } elseif ( $social_icon_style === 'circle' ) {
            $css .= '
                border-radius: 50%;
            ';
        } elseif ( $social_icon_style === 'square' ) {
            $css .= '
                border-radius: 0;
            ';
        } elseif ( $social_icon_style === 'plain' ) {
            $css .= '
                background-color: transparent;
                color: var(--aqualuxe-text-color);
            ';
        }
        
        if ( $social_icon_size === 'small' ) {
            $css .= '
                width: 30px;
                height: 30px;
                font-size: 14px;
            ';
        } elseif ( $social_icon_size === 'medium' ) {
            $css .= '
                width: 40px;
                height: 40px;
                font-size: 16px;
            ';
        } elseif ( $social_icon_size === 'large' ) {
            $css .= '
                width: 50px;
                height: 50px;
                font-size: 18px;
            ';
        }
        
        $css .= '
            }
        ';
    }

    // WooCommerce Shop Columns
    $shop_columns = get_theme_mod( 'aqualuxe_shop_columns', '3' );
    if ( $shop_columns && class_exists( 'WooCommerce' ) ) {
        $css .= '
            :root {
                --aqualuxe-shop-columns: ' . esc_attr( $shop_columns ) . ';
            }
            
            @media (min-width: 768px) {
                .woocommerce ul.products li.product, 
                .woocommerce-page ul.products li.product {
                    width: calc((100% - ((var(--aqualuxe-shop-columns) - 1) * 30px)) / var(--aqualuxe-shop-columns));
                    margin-right: 30px;
                    margin-bottom: 30px;
                }
                
                .woocommerce ul.products li.product:nth-child(' . esc_attr( $shop_columns ) . 'n), 
                .woocommerce-page ul.products li.product:nth-child(' . esc_attr( $shop_columns ) . 'n) {
                    margin-right: 0;
                }
            }
        ';
    }

    // Product Hover Effect
    $product_hover_effect = get_theme_mod( 'aqualuxe_product_hover_effect', 'zoom' );
    if ( $product_hover_effect && class_exists( 'WooCommerce' ) ) {
        if ( $product_hover_effect === 'zoom' ) {
            $css .= '
                .woocommerce ul.products li.product .product-thumbnail-wrapper img {
                    transition: transform 0.5s ease;
                }
                
                .woocommerce ul.products li.product:hover .product-thumbnail-wrapper img {
                    transform: scale(1.1);
                }
            ';
        } elseif ( $product_hover_effect === 'fade' ) {
            $css .= '
                .woocommerce ul.products li.product .product-thumbnail-wrapper img {
                    transition: opacity 0.5s ease;
                }
                
                .woocommerce ul.products li.product:hover .product-thumbnail-wrapper img {
                    opacity: 0.7;
                }
            ';
        } elseif ( $product_hover_effect === 'flip' ) {
            $css .= '
                .woocommerce ul.products li.product .product-thumbnail-wrapper {
                    perspective: 1000px;
                }
                
                .woocommerce ul.products li.product .product-thumbnail-wrapper img {
                    transition: transform 0.6s;
                    transform-style: preserve-3d;
                }
                
                .woocommerce ul.products li.product:hover .product-thumbnail-wrapper img {
                    transform: rotateY(180deg);
                }
            ';
        }
    }

    return $css;
}

/**
 * Enqueue customizer CSS
 */
function aqualuxe_customizer_css() {
    $css = aqualuxe_generate_customizer_css();
    
    if ( ! empty( $css ) ) {
        wp_add_inline_style( 'aqualuxe-styles', $css );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_customizer_css', 20 );