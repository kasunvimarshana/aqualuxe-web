<?php
/**
 * AquaLuxe Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe_Child
 */

/**
 * Enqueue parent and child theme styles
 */
function aqualuxe_child_enqueue_styles() {
    // Enqueue parent theme's style.css
    wp_enqueue_style(
        'aqualuxe-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('aqualuxe')->get('Version')
    );

    // Enqueue child theme's style.css
    wp_enqueue_style(
        'aqualuxe-child-style',
        get_stylesheet_uri(),
        array('aqualuxe-style'),
        wp_get_theme()->get('Version')
    );

    // Enqueue custom CSS file
    wp_enqueue_style(
        'aqualuxe-child-custom',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        array('aqualuxe-child-style'),
        wp_get_theme()->get('Version')
    );

    // Enqueue custom JS file
    wp_enqueue_script(
        'aqualuxe-child-custom',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

/**
 * Add custom image sizes
 */
function aqualuxe_child_add_image_sizes() {
    // Add custom image sizes if needed
    // add_image_size('custom-size', 1200, 800, true);
}
add_action('after_setup_theme', 'aqualuxe_child_add_image_sizes');

/**
 * Add custom theme options to the customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_child_customize_register($wp_customize) {
    // Add custom customizer settings here
    
    // Example: Add a new section
    /*
    $wp_customize->add_section('aqualuxe_child_options', array(
        'title'    => __('Child Theme Options', 'aqualuxe-child'),
        'priority' => 130,
    ));

    // Add a setting
    $wp_customize->add_setting('aqualuxe_child_custom_setting', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add a control
    $wp_customize->add_control('aqualuxe_child_custom_setting', array(
        'label'    => __('Custom Setting', 'aqualuxe-child'),
        'section'  => 'aqualuxe_child_options',
        'type'     => 'text',
    ));
    */
}
add_action('customize_register', 'aqualuxe_child_customize_register');

/**
 * Override parent theme functions
 * 
 * To override a function from the parent theme, copy it here and modify it.
 * Make sure to check if the function exists before declaring it.
 */

/**
 * Example of overriding a parent theme function
 */
/*
if (!function_exists('aqualuxe_child_custom_function')) {
    function aqualuxe_child_custom_function() {
        // Your custom implementation here
    }
}
add_action('init', 'aqualuxe_child_custom_function', 20); // Higher priority to run after parent theme
*/

/**
 * Add custom hooks and filters
 */

// Example: Add content before the footer
/*
function aqualuxe_child_before_footer() {
    echo '<div class="pre-footer-content">Custom content before footer</div>';
}
add_action('aqualuxe_before_footer', 'aqualuxe_child_before_footer');
*/

// Example: Modify body classes
/*
function aqualuxe_child_body_classes($classes) {
    $classes[] = 'custom-class';
    return $classes;
}
add_filter('aqualuxe_body_classes', 'aqualuxe_child_body_classes');
*/

/**
 * Include additional files
 */
// require get_stylesheet_directory() . '/inc/custom-functions.php';