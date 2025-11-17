<?php
/**
 * AquaLuxe Customizer Footer Section
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register footer section
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 * @return void
 */
function aqualuxe_customizer_register_footer_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'aqualuxe_footer', array(
        'title' => __( 'Footer Settings', 'aqualuxe' ),
        'description' => __( 'Customize the footer section.', 'aqualuxe' ),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 30,
    ) );
    
    // Footer layout
    $wp_customize->add_setting( 'aqualuxe_footer_layout', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_layout', array(
        'label' => __( 'Footer Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'select',
        'choices' => aqualuxe_get_footer_layouts(),
    ) );
    
    // Footer background color
    $wp_customize->add_setting( 'aqualuxe_footer_bg_color', array(
        'default' => '#f5f5f5',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_footer_bg_color', array(
        'label' => __( 'Footer Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
    ) ) );
    
    // Footer text color
    $wp_customize->add_setting( 'aqualuxe_footer_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_footer_text_color', array(
        'label' => __( 'Footer Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
    ) ) );
    
    // Footer link color
    $wp_customize->add_setting( 'aqualuxe_footer_link_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_footer_link_color', array(
        'label' => __( 'Footer Link Color', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
    ) ) );
    
    // Footer link hover color
    $wp_customize->add_setting( 'aqualuxe_footer_link_hover_color', array(
        'default' => '#00a0d2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_footer_link_hover_color', array(
        'label' => __( 'Footer Link Hover Color', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
    ) ) );
    
    // Footer border
    $wp_customize->add_setting( 'aqualuxe_footer_border', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_border', array(
        'label' => __( 'Show Footer Border', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'checkbox',
    ) );
    
    // Footer border color
    $wp_customize->add_setting( 'aqualuxe_footer_border_color', array(
        'default' => '#eeeeee',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_footer_border_color', array(
        'label' => __( 'Footer Border Color', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
    ) ) );
    
    // Footer widgets
    $wp_customize->add_setting( 'aqualuxe_footer_widgets', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_widgets', array(
        'label' => __( 'Show Footer Widgets', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'checkbox',
    ) );
    
    // Footer widget columns
    $wp_customize->add_setting( 'aqualuxe_footer_widget_columns', array(
        'default' => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_widget_columns', array(
        'label' => __( 'Footer Widget Columns', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'select',
        'choices' => array(
            '1' => __( '1 Column', 'aqualuxe' ),
            '2' => __( '2 Columns', 'aqualuxe' ),
            '3' => __( '3 Columns', 'aqualuxe' ),
            '4' => __( '4 Columns', 'aqualuxe' ),
        ),
    ) );
    
    // Footer logo
    $wp_customize->add_setting( 'aqualuxe_footer_logo', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_image',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_footer_logo', array(
        'label' => __( 'Footer Logo', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'settings' => 'aqualuxe_footer_logo',
    ) ) );
    
    // Footer logo size
    $wp_customize->add_setting( 'aqualuxe_footer_logo_size', array(
        'default' => '150',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_logo_size', array(
        'label' => __( 'Footer Logo Max Width (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 50,
            'max' => 500,
            'step' => 5,
        ),
    ) );
    
    // Footer copyright
    $wp_customize->add_setting( 'aqualuxe_footer_copyright', array(
        'default' => '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.',
        'sanitize_callback' => 'aqualuxe_sanitize_html',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_copyright', array(
        'label' => __( 'Footer Copyright', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'textarea',
    ) );
    
    // Footer menu
    $wp_customize->add_setting( 'aqualuxe_footer_menu', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_menu', array(
        'label' => __( 'Show Footer Menu', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'checkbox',
    ) );
    
    // Footer social icons
    $wp_customize->add_setting( 'aqualuxe_footer_social', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_social', array(
        'label' => __( 'Show Social Icons', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'checkbox',
    ) );
    
    // Footer payment icons
    $wp_customize->add_setting( 'aqualuxe_footer_payment', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_payment', array(
        'label' => __( 'Show Payment Icons', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'checkbox',
    ) );
    
    // Footer payment icons list
    $wp_customize->add_setting( 'aqualuxe_footer_payment_icons', array(
        'default' => array( 'visa', 'mastercard', 'amex', 'paypal' ),
        'sanitize_callback' => 'aqualuxe_sanitize_sortable',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_payment_icons', array(
        'label' => __( 'Payment Icons', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'select',
        'choices' => array(
            'visa' => __( 'Visa', 'aqualuxe' ),
            'mastercard' => __( 'Mastercard', 'aqualuxe' ),
            'amex' => __( 'American Express', 'aqualuxe' ),
            'discover' => __( 'Discover', 'aqualuxe' ),
            'paypal' => __( 'PayPal', 'aqualuxe' ),
            'apple-pay' => __( 'Apple Pay', 'aqualuxe' ),
            'google-pay' => __( 'Google Pay', 'aqualuxe' ),
            'stripe' => __( 'Stripe', 'aqualuxe' ),
        ),
        'multiple' => true,
    ) );
    
    // Footer address
    $wp_customize->add_setting( 'aqualuxe_footer_address', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_textarea',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_address', array(
        'label' => __( 'Footer Address', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'textarea',
    ) );
    
    // Footer phone
    $wp_customize->add_setting( 'aqualuxe_footer_phone', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_phone', array(
        'label' => __( 'Footer Phone', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'text',
    ) );
    
    // Footer email
    $wp_customize->add_setting( 'aqualuxe_footer_email', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_email',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_email', array(
        'label' => __( 'Footer Email', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'email',
    ) );
    
    // Footer bottom background color
    $wp_customize->add_setting( 'aqualuxe_footer_bottom_bg_color', array(
        'default' => '#eeeeee',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_footer_bottom_bg_color', array(
        'label' => __( 'Footer Bottom Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
    ) ) );
    
    // Footer bottom text color
    $wp_customize->add_setting( 'aqualuxe_footer_bottom_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_footer_bottom_text_color', array(
        'label' => __( 'Footer Bottom Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
    ) ) );
    
    // Footer scripts
    $wp_customize->add_setting( 'aqualuxe_footer_scripts', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_js',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_footer_scripts', array(
        'label' => __( 'Footer Scripts', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type' => 'textarea',
        'description' => __( 'Add custom scripts to the footer. Do not include &lt;script&gt; tags.', 'aqualuxe' ),
    ) );
}