<?php
/**
 * Theme Support Functions
 *
 * Additional theme support and feature registration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Disable WooCommerce default stylesheets (handled by theme)
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Custom WooCommerce wrapper start
 */
function aqualuxe_woocommerce_wrapper_start() {
    echo '<div id="primary" class="content-area container mx-auto px-4 py-8">';
    echo '<main id="main" class="site-main" role="main">';
}

/**
 * Custom WooCommerce wrapper end
 */
function aqualuxe_woocommerce_wrapper_end() {
    echo '</main>';
    echo '</div>';
}

// Only add WooCommerce hooks if WooCommerce is active
if (class_exists('WooCommerce')) {
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start', 10);
    add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end', 10);
}

/**
 * Custom WooCommerce breadcrumb args
 */
function aqualuxe_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => ' <span class="separator">/</span> ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumb text-sm text-gray-600 dark:text-gray-400 mb-4">',
        'wrap_after'  => '</nav>',
        'before'      => '<span class="breadcrumb-item">',
        'after'       => '</span>',
        'home'        => _x('Home', 'breadcrumb', 'aqualuxe'),
    );
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumbs');

/**
 * Custom pagination for WooCommerce
 */
function aqualuxe_woocommerce_pagination_args($args) {
    $args['prev_text'] = '<span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
    $args['next_text'] = '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>';
    return $args;
}
add_filter('woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args');

/**
 * Customize WooCommerce loop product structure
 */
function aqualuxe_woocommerce_loop_product_link_open() {
    global $product;
    
    $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
    echo '<a href="' . esc_url($link) . '" class="woocommerce-LoopProduct-link block group">';
}

function aqualuxe_woocommerce_loop_product_link_close() {
    echo '</a>';
}

// Replace default WooCommerce loop product link hooks
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
add_action('woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_loop_product_link_open', 10);
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_loop_product_link_close', 5);

/**
 * Enable shortcodes in text widgets
 */
add_filter('widget_text', 'do_shortcode');

/**
 * Add support for Gutenberg wide and full alignments
 */
function aqualuxe_gutenberg_support() {
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    
    // Add custom color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Primary Blue', 'aqualuxe'),
            'slug'  => 'primary-blue',
            'color' => '#0891b2',
        ),
        array(
            'name'  => __('Secondary Teal', 'aqualuxe'),
            'slug'  => 'secondary-teal',
            'color' => '#14b8a6',
        ),
        array(
            'name'  => __('Accent Cyan', 'aqualuxe'),
            'slug'  => 'accent-cyan',
            'color' => '#06b6d4',
        ),
        array(
            'name'  => __('Dark Blue', 'aqualuxe'),
            'slug'  => 'dark-blue',
            'color' => '#1e3a8a',
        ),
        array(
            'name'  => __('Light Gray', 'aqualuxe'),
            'slug'  => 'light-gray',
            'color' => '#f8fafc',
        ),
        array(
            'name'  => __('Medium Gray', 'aqualuxe'),
            'slug'  => 'medium-gray',
            'color' => '#64748b',
        ),
        array(
            'name'  => __('Dark Gray', 'aqualuxe'),
            'slug'  => 'dark-gray',
            'color' => '#1e293b',
        ),
    ));
    
    // Add custom font sizes
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('Small', 'aqualuxe'),
            'size' => 12,
            'slug' => 'small'
        ),
        array(
            'name' => __('Normal', 'aqualuxe'),
            'size' => 16,
            'slug' => 'normal'
        ),
        array(
            'name' => __('Medium', 'aqualuxe'),
            'size' => 20,
            'slug' => 'medium'
        ),
        array(
            'name' => __('Large', 'aqualuxe'),
            'size' => 24,
            'slug' => 'large'
        ),
        array(
            'name' => __('Extra Large', 'aqualuxe'),
            'size' => 32,
            'slug' => 'extra-large'
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_gutenberg_support');

/**
 * Enqueue Gutenberg block editor styles
 */
function aqualuxe_block_editor_styles() {
    wp_enqueue_style(
        'aqualuxe-block-editor-styles',
        AQUALUXE_ASSETS_URI . '/css/editor.css',
        array(),
        AQUALUXE_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'aqualuxe_block_editor_styles');

/**
 * Custom login styles
 */
function aqualuxe_login_styles() {
    wp_enqueue_style(
        'aqualuxe-login',
        AQUALUXE_ASSETS_URI . '/css/login.css',
        array(),
        AQUALUXE_VERSION
    );
}
add_action('login_enqueue_scripts', 'aqualuxe_login_styles');

/**
 * Custom login logo URL
 */
function aqualuxe_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'aqualuxe_login_logo_url');

/**
 * Custom login logo title
 */
function aqualuxe_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'aqualuxe_login_logo_url_title');