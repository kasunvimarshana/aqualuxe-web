<?php
/**
 * AquaLuxe Customizer Social Section
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register social section
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 * @return void
 */
function aqualuxe_customizer_register_social_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'aqualuxe_social', array(
        'title' => __( 'Social Media Settings', 'aqualuxe' ),
        'description' => __( 'Configure social media profiles and sharing options.', 'aqualuxe' ),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 80,
    ) );
    
    // Social profiles
    $social_networks = aqualuxe_get_social_networks();
    
    foreach ( $social_networks as $network => $label ) {
        $wp_customize->add_setting( 'aqualuxe_social_' . $network, array(
            'default' => '',
            'sanitize_callback' => 'aqualuxe_sanitize_url',
            'transport' => 'refresh',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_social_' . $network, array(
            'label' => $label,
            'section' => 'aqualuxe_social',
            'type' => 'url',
            'input_attrs' => array(
                'placeholder' => __( 'https://', 'aqualuxe' ),
            ),
        ) );
    }
    
    // Social icons in header
    $wp_customize->add_setting( 'aqualuxe_social_header', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_header', array(
        'label' => __( 'Show Social Icons in Header', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'checkbox',
    ) );
    
    // Social icons in footer
    $wp_customize->add_setting( 'aqualuxe_social_footer', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_footer', array(
        'label' => __( 'Show Social Icons in Footer', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'checkbox',
    ) );
    
    // Social icons in mobile menu
    $wp_customize->add_setting( 'aqualuxe_social_mobile', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_mobile', array(
        'label' => __( 'Show Social Icons in Mobile Menu', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'checkbox',
    ) );
    
    // Social icons style
    $wp_customize->add_setting( 'aqualuxe_social_style', array(
        'default' => 'icon',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_style', array(
        'label' => __( 'Social Icons Style', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'icon' => __( 'Icon Only', 'aqualuxe' ),
            'text' => __( 'Text Only', 'aqualuxe' ),
            'icon-text' => __( 'Icon + Text', 'aqualuxe' ),
        ),
    ) );
    
    // Social icons shape
    $wp_customize->add_setting( 'aqualuxe_social_shape', array(
        'default' => 'rounded',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_shape', array(
        'label' => __( 'Social Icons Shape', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'square' => __( 'Square', 'aqualuxe' ),
            'rounded' => __( 'Rounded', 'aqualuxe' ),
            'circle' => __( 'Circle', 'aqualuxe' ),
            'none' => __( 'None', 'aqualuxe' ),
        ),
    ) );
    
    // Social icons size
    $wp_customize->add_setting( 'aqualuxe_social_size', array(
        'default' => 'medium',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_size', array(
        'label' => __( 'Social Icons Size', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'small' => __( 'Small', 'aqualuxe' ),
            'medium' => __( 'Medium', 'aqualuxe' ),
            'large' => __( 'Large', 'aqualuxe' ),
        ),
    ) );
    
    // Social icons color
    $wp_customize->add_setting( 'aqualuxe_social_color', array(
        'default' => 'brand',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_color', array(
        'label' => __( 'Social Icons Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'brand' => __( 'Brand Colors', 'aqualuxe' ),
            'custom' => __( 'Custom Color', 'aqualuxe' ),
        ),
    ) );
    
    // Social icons custom color
    $wp_customize->add_setting( 'aqualuxe_social_custom_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_custom_color', array(
        'label' => __( 'Social Icons Custom Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social icons hover color
    $wp_customize->add_setting( 'aqualuxe_social_hover_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_hover_color', array(
        'label' => __( 'Social Icons Hover Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social icons background color
    $wp_customize->add_setting( 'aqualuxe_social_bg_color', array(
        'default' => 'transparent',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_bg_color', array(
        'label' => __( 'Social Icons Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social icons background hover color
    $wp_customize->add_setting( 'aqualuxe_social_bg_hover_color', array(
        'default' => 'transparent',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_bg_hover_color', array(
        'label' => __( 'Social Icons Background Hover Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social icons border color
    $wp_customize->add_setting( 'aqualuxe_social_border_color', array(
        'default' => 'transparent',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_border_color', array(
        'label' => __( 'Social Icons Border Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social icons border hover color
    $wp_customize->add_setting( 'aqualuxe_social_border_hover_color', array(
        'default' => 'transparent',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_border_hover_color', array(
        'label' => __( 'Social Icons Border Hover Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social sharing
    $wp_customize->add_setting( 'aqualuxe_social_sharing', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_sharing', array(
        'label' => __( 'Enable Social Sharing', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'checkbox',
    ) );
    
    // Social sharing position
    $wp_customize->add_setting( 'aqualuxe_social_sharing_position', array(
        'default' => 'after',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_sharing_position', array(
        'label' => __( 'Social Sharing Position', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'before' => __( 'Before Content', 'aqualuxe' ),
            'after' => __( 'After Content', 'aqualuxe' ),
            'both' => __( 'Before and After Content', 'aqualuxe' ),
            'floating' => __( 'Floating', 'aqualuxe' ),
        ),
    ) );
    
    // Social sharing networks
    $wp_customize->add_setting( 'aqualuxe_social_sharing_networks', array(
        'default' => array( 'facebook', 'twitter', 'linkedin', 'pinterest' ),
        'sanitize_callback' => 'aqualuxe_sanitize_sortable',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_sharing_networks', array(
        'label' => __( 'Social Sharing Networks', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'facebook' => __( 'Facebook', 'aqualuxe' ),
            'twitter' => __( 'Twitter', 'aqualuxe' ),
            'linkedin' => __( 'LinkedIn', 'aqualuxe' ),
            'pinterest' => __( 'Pinterest', 'aqualuxe' ),
            'reddit' => __( 'Reddit', 'aqualuxe' ),
            'tumblr' => __( 'Tumblr', 'aqualuxe' ),
            'whatsapp' => __( 'WhatsApp', 'aqualuxe' ),
            'telegram' => __( 'Telegram', 'aqualuxe' ),
            'email' => __( 'Email', 'aqualuxe' ),
        ),
        'multiple' => true,
    ) );
    
    // Social sharing style
    $wp_customize->add_setting( 'aqualuxe_social_sharing_style', array(
        'default' => 'icon-text',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_sharing_style', array(
        'label' => __( 'Social Sharing Style', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'icon' => __( 'Icon Only', 'aqualuxe' ),
            'text' => __( 'Text Only', 'aqualuxe' ),
            'icon-text' => __( 'Icon + Text', 'aqualuxe' ),
        ),
    ) );
    
    // Social sharing shape
    $wp_customize->add_setting( 'aqualuxe_social_sharing_shape', array(
        'default' => 'rounded',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_sharing_shape', array(
        'label' => __( 'Social Sharing Shape', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'square' => __( 'Square', 'aqualuxe' ),
            'rounded' => __( 'Rounded', 'aqualuxe' ),
            'circle' => __( 'Circle', 'aqualuxe' ),
            'none' => __( 'None', 'aqualuxe' ),
        ),
    ) );
    
    // Social sharing size
    $wp_customize->add_setting( 'aqualuxe_social_sharing_size', array(
        'default' => 'medium',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_sharing_size', array(
        'label' => __( 'Social Sharing Size', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'small' => __( 'Small', 'aqualuxe' ),
            'medium' => __( 'Medium', 'aqualuxe' ),
            'large' => __( 'Large', 'aqualuxe' ),
        ),
    ) );
    
    // Social sharing color
    $wp_customize->add_setting( 'aqualuxe_social_sharing_color', array(
        'default' => 'brand',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_sharing_color', array(
        'label' => __( 'Social Sharing Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => array(
            'brand' => __( 'Brand Colors', 'aqualuxe' ),
            'custom' => __( 'Custom Color', 'aqualuxe' ),
        ),
    ) );
    
    // Social sharing custom color
    $wp_customize->add_setting( 'aqualuxe_social_sharing_custom_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_sharing_custom_color', array(
        'label' => __( 'Social Sharing Custom Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social sharing hover color
    $wp_customize->add_setting( 'aqualuxe_social_sharing_hover_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_sharing_hover_color', array(
        'label' => __( 'Social Sharing Hover Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social sharing background color
    $wp_customize->add_setting( 'aqualuxe_social_sharing_bg_color', array(
        'default' => 'transparent',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_sharing_bg_color', array(
        'label' => __( 'Social Sharing Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social sharing background hover color
    $wp_customize->add_setting( 'aqualuxe_social_sharing_bg_hover_color', array(
        'default' => 'transparent',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_sharing_bg_hover_color', array(
        'label' => __( 'Social Sharing Background Hover Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social sharing border color
    $wp_customize->add_setting( 'aqualuxe_social_sharing_border_color', array(
        'default' => 'transparent',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_sharing_border_color', array(
        'label' => __( 'Social Sharing Border Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social sharing border hover color
    $wp_customize->add_setting( 'aqualuxe_social_sharing_border_hover_color', array(
        'default' => 'transparent',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_social_sharing_border_hover_color', array(
        'label' => __( 'Social Sharing Border Hover Color', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
    ) ) );
    
    // Social sharing title
    $wp_customize->add_setting( 'aqualuxe_social_sharing_title', array(
        'default' => __( 'Share This', 'aqualuxe' ),
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_social_sharing_title', array(
        'label' => __( 'Social Sharing Title', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'text',
    ) );
    
    // Social sharing post types
    $wp_customize->add_setting( 'aqualuxe_social_sharing_post_types', array(
        'default' => array( 'post' ),
        'sanitize_callback' => 'aqualuxe_sanitize_sortable',
        'transport' => 'refresh',
    ) );
    
    $post_types = get_post_types( array( 'public' => true ), 'objects' );
    $post_type_choices = array();
    
    foreach ( $post_types as $post_type ) {
        $post_type_choices[ $post_type->name ] = $post_type->label;
    }
    
    $wp_customize->add_control( 'aqualuxe_social_sharing_post_types', array(
        'label' => __( 'Social Sharing Post Types', 'aqualuxe' ),
        'section' => 'aqualuxe_social',
        'type' => 'select',
        'choices' => $post_type_choices,
        'multiple' => true,
    ) );
}