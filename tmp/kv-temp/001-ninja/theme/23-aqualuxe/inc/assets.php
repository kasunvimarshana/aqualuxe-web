<?php
/**
 * Enqueue scripts and styles for the theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Register and enqueue styles
    wp_enqueue_style(
        'aqualuxe-style',
        get_stylesheet_uri(),
        array(),
        AQUALUXE_VERSION
    );

    // Main Tailwind CSS
    wp_enqueue_style(
        'aqualuxe-tailwind',
        AQUALUXE_URI . 'assets/css/tailwind.css',
        array(),
        AQUALUXE_VERSION
    );

    // Custom styles
    wp_enqueue_style(
        'aqualuxe-custom',
        AQUALUXE_URI . 'assets/css/custom.css',
        array('aqualuxe-tailwind'),
        AQUALUXE_VERSION
    );

    // Register and enqueue scripts
    wp_enqueue_script(
        'aqualuxe-navigation',
        AQUALUXE_URI . 'assets/js/navigation.js',
        array(),
        AQUALUXE_VERSION,
        true
    );

    // Main theme script
    wp_enqueue_script(
        'aqualuxe-main',
        AQUALUXE_URI . 'assets/js/main.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Dark mode script
    wp_enqueue_script(
        'aqualuxe-dark-mode',
        AQUALUXE_URI . 'assets/js/dark-mode.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Localize script for dark mode
    wp_localize_script(
        'aqualuxe-dark-mode',
        'aqualuxeSettings',
        array(
            'defaultColorScheme' => get_theme_mod( 'aqualuxe_default_color_scheme', 'light' ),
        )
    );

    // WooCommerce specific scripts
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            AQUALUXE_URI . 'assets/js/woocommerce.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
    // Admin styles
    wp_enqueue_style(
        'aqualuxe-admin-style',
        AQUALUXE_URI . 'assets/css/admin.css',
        array(),
        AQUALUXE_VERSION
    );

    // Admin scripts
    wp_enqueue_script(
        'aqualuxe-admin-script',
        AQUALUXE_URI . 'assets/js/admin.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
    // Block editor styles
    wp_enqueue_style(
        'aqualuxe-editor-style',
        AQUALUXE_URI . 'assets/css/editor.css',
        array(),
        AQUALUXE_VERSION
    );
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_assets' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
    if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Add async/defer attributes to enqueued scripts where needed.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Script HTML string.
 */
function aqualuxe_script_loader_tag( $tag, $handle ) {
    // Add async attribute to specific scripts
    $scripts_to_async = array( 'aqualuxe-dark-mode' );
    foreach ( $scripts_to_async as $async_script ) {
        if ( $async_script === $handle ) {
            return str_replace( ' src', ' async src', $tag );
        }
    }

    // Add defer attribute to specific scripts
    $scripts_to_defer = array( 'aqualuxe-navigation' );
    foreach ( $scripts_to_defer as $defer_script ) {
        if ( $defer_script === $handle ) {
            return str_replace( ' src', ' defer src', $tag );
        }
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2 );

/**
 * Add preload for critical assets.
 */
function aqualuxe_preload_assets() {
    // Preload main CSS file
    echo '<link rel="preload" href="' . esc_url( AQUALUXE_URI . 'assets/css/tailwind.css' ) . '" as="style">';
    
    // Preload logo if available
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_info = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( $logo_info ) {
            echo '<link rel="preload" href="' . esc_url( $logo_info[0] ) . '" as="image">';
        }
    }
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );