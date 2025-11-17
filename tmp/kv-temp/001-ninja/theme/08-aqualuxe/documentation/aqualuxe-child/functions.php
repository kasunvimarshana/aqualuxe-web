<?php
/**
 * AquaLuxe Child Theme functions and definitions
 *
 * @package AquaLuxe Child
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue parent and child theme styles
 */
function aqualuxe_child_enqueue_styles() {
    // Enqueue parent theme style
    wp_enqueue_style( 'aqualuxe-style', 
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme( 'aqualuxe' )->get( 'Version' )
    );
    
    // Enqueue child theme style
    wp_enqueue_style( 'aqualuxe-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'aqualuxe-style' ),
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles' );

/**
 * Enqueue child theme scripts
 */
function aqualuxe_child_enqueue_scripts() {
    // Enqueue child theme script
    wp_enqueue_script(
        'aqualuxe-child-script',
        get_stylesheet_directory_uri() . '/js/custom.js',
        array( 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_child_enqueue_scripts' );

/**
 * Add your custom functions below this line
 */

// Example: Add a new widget area
function aqualuxe_child_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Custom Sidebar', 'aqualuxe-child' ),
        'id'            => 'custom-sidebar',
        'description'   => __( 'Add widgets here to appear in your custom sidebar.', 'aqualuxe-child' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
// Uncomment the line below to register the custom sidebar
// add_action( 'widgets_init', 'aqualuxe_child_widgets_init' );

// Example: Add a custom image size
function aqualuxe_child_add_image_sizes() {
    add_image_size( 'aqualuxe-child-custom', 800, 600, true );
}
// Uncomment the line below to add the custom image size
// add_action( 'after_setup_theme', 'aqualuxe_child_add_image_sizes' );

// Example: Override a parent theme function
// function aqualuxe_custom_function() {
//     // Your custom implementation here
// }
// add_action( 'init', 'aqualuxe_custom_function', 20 );

// Example: Add a new shortcode
function aqualuxe_child_custom_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'title' => '',
    ), $atts );
    
    $output = '<div class="custom-shortcode">';
    if ( ! empty( $atts['title'] ) ) {
        $output .= '<h3>' . esc_html( $atts['title'] ) . '</h3>';
    }
    $output .= '<div class="content">' . do_shortcode( $content ) . '</div>';
    $output .= '</div>';
    
    return $output;
}
// Uncomment the line below to register the custom shortcode
// add_shortcode( 'custom', 'aqualuxe_child_custom_shortcode' );