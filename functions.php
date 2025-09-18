<?php
/**
 * AquaLuxe Theme Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());

/**
 * Autoloader for theme classes
 */
spl_autoload_register(function ($class) {
    // Check if it's an AquaLuxe class
    if (strpos($class, 'AquaLuxe\\') !== 0) {
        return;
    }

    // Convert namespace to file path
    $class_file = str_replace('AquaLuxe\\', '', $class);
    $class_file = str_replace('\\', '/', $class_file);
    
    // Convert to kebab-case for directories and add class prefix for files
    $parts = explode('/', $class_file);
    $filename = 'class-' . strtolower(str_replace('_', '-', array_pop($parts)));
    $path_parts = array_map(function($part) {
        return strtolower(str_replace('_', '-', $part));
    }, $parts);
    
    $file_path = AQUALUXE_THEME_DIR . '/' . implode('/', $path_parts) . '/' . $filename . '.php';
    
    if (file_exists($file_path)) {
        require_once $file_path;
    }
});

/**
 * Theme setup
 */
function aqualuxe_theme_setup() {
    // Initialize core theme setup
    \AquaLuxe\Core\Theme_Setup::get_instance();
}
add_action('after_setup_theme', 'aqualuxe_theme_setup', 0);

// Load theme components
require_once AQUALUXE_THEME_DIR . '/inc/customizer.php';
require_once AQUALUXE_THEME_DIR . '/inc/custom-post-types.php';
require_once AQUALUXE_THEME_DIR . '/inc/custom-taxonomies.php';
require_once AQUALUXE_THEME_DIR . '/inc/meta-fields.php';
require_once AQUALUXE_THEME_DIR . '/inc/template-hooks.php';
require_once AQUALUXE_THEME_DIR . '/inc/template-functions.php';
require_once AQUALUXE_THEME_DIR . '/inc/admin/admin-init.php';

// Load WooCommerce integration if WooCommerce is active
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_THEME_DIR . '/inc/woocommerce/wc-integration.php';
}

/**
 * Get theme version
 *
 * @return string
 */
function aqualuxe_get_version() {
    return AQUALUXE_VERSION;
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_activated() {
    return class_exists('WooCommerce');
}

/**
 * Display SVG icon
 *
 * @param string $icon Icon name
 * @param array  $args Icon arguments
 * @return string
 */
function aqualuxe_get_svg_icon($icon, $args = array()) {
    $defaults = array(
        'size'  => 24,
        'class' => '',
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $icons = array(
        'menu' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        'close' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        'search' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        'cart' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L6 5H3m4 8v6a1 1 0 001 1h9a1 1 0 001-1v-6M8 19h.01M20 19h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'user' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'heart' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'star' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
    );
    
    return isset($icons[$icon]) ? $icons[$icon] : '';
}

/**
 * Display SVG icon
 *
 * @param string $icon Icon name
 * @param array  $args Icon arguments
 */
function aqualuxe_svg_icon($icon, $args = array()) {
    echo aqualuxe_get_svg_icon($icon, $args);
}

/**
 * Custom excerpt length
 *
 * @param int $length Excerpt length
 * @return int
 */
function aqualuxe_excerpt_length($length) {
    return get_theme_mod('aqualuxe_excerpt_length', 25);
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Custom excerpt more
 *
 * @param string $more Excerpt more text
 * @return string
 */
function aqualuxe_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add theme support for WooCommerce
 */
if (aqualuxe_is_woocommerce_activated()) {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}