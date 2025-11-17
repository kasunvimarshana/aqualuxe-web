<?php
/**
 * AquaLuxe Customizer Settings
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AquaLuxe_Customizer {
    
    /**
     * Initialize customizer
     */
    public static function init() {
        add_action( 'customize_register', array( __CLASS__, 'register' ) );
        add_action( 'customize_preview_init', array( __CLASS__, 'customize_preview_js' ) );
    }
    
    /**
     * Register customizer settings and controls
     */
    public static function register( $wp_customize ) {
        // Add color settings
        $wp_customize->add_setting( 'aqualuxe_primary_color', array(
            'default'           => '#0077BE',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_primary_color', array(
            'label'    => __( 'Primary Color', 'aqualuxe' ),
            'section'  => 'colors',
            'settings' => 'aqualuxe_primary_color',
        ) ) );
        
        // Add typography settings
        $wp_customize->add_setting( 'aqualuxe_body_font', array(
            'default'           => 'Inter, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ) );
        
        $wp_customize->add_control( 'aqualuxe_body_font', array(
            'label'    => __( 'Body Font', 'aqualuxe' ),
            'section'  => 'typography',
            'type'     => 'text',
        ) );
        
        // Add logo upload
        $wp_customize->add_setting( 'aqualuxe_logo', array(
            'sanitize_callback' => 'esc_url_raw',
        ) );
        
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_logo', array(
            'label'    => __( 'Logo', 'aqualuxe' ),
            'section'  => 'title_tagline',
            'settings' => 'aqualuxe_logo',
        ) ) );
    }
    
    /**
     * Enqueue customizer preview JS
     */
    public static function customize_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer',
            get_stylesheet_directory_uri() . '/assets/js/customizer.js',
            array( 'customize-preview' ),
            '1.0.0',
            true
        );
    }
}

AquaLuxe_Customizer::init();
