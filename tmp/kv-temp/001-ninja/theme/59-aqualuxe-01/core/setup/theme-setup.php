<?php
/**
 * AquaLuxe Theme Setup
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Theme setup
 */
function aqualuxe_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add custom image sizes
    add_image_size('aqualuxe-featured', 1200, 800, true);
    add_image_size('aqualuxe-blog', 800, 600, true);
    add_image_size('aqualuxe-thumbnail', 400, 300, true);
    add_image_size('aqualuxe-square', 600, 600, true);
    add_image_size('aqualuxe-portrait', 600, 800, true);
    add_image_size('aqualuxe-landscape', 800, 600, true);

    // Register navigation menus
    register_nav_menus([
        'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
        'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
        'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
        'social'    => esc_html__('Social Menu', 'aqualuxe'),
    ]);

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Set up the WordPress core custom background feature
    add_theme_support('custom-background', [
        'default-color' => 'ffffff',
        'default-image' => '',
    ]);

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for custom line height controls
    add_theme_support('custom-line-height');

    // Add support for custom spacing
    add_theme_support('custom-spacing');

    // Add support for custom units
    add_theme_support('custom-units');

    // Add support for experimental link color control
    add_theme_support('experimental-link-color');

    // Add support for experimental cover block spacing
    add_theme_support('experimental-custom-spacing');

    // Add support for block templates
    add_theme_support('block-templates');

    // Add support for block template parts
    add_theme_support('block-template-parts');

    // Add support for custom logo
    add_theme_support('custom-logo', [
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    // Add support for post formats
    add_theme_support('post-formats', [
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
    ]);

    // Add support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Add support for editor color palette
    add_theme_support('editor-color-palette', [
        [
            'name'  => esc_html__('Primary', 'aqualuxe'),
            'slug'  => 'primary',
            'color' => '#0077b6',
        ],
        [
            'name'  => esc_html__('Secondary', 'aqualuxe'),
            'slug'  => 'secondary',
            'color' => '#00b4d8',
        ],
        [
            'name'  => esc_html__('Accent', 'aqualuxe'),
            'slug'  => 'accent',
            'color' => '#48cae4',
        ],
        [
            'name'  => esc_html__('Dark', 'aqualuxe'),
            'slug'  => 'dark',
            'color' => '#03045e',
        ],
        [
            'name'  => esc_html__('Light', 'aqualuxe'),
            'slug'  => 'light',
            'color' => '#caf0f8',
        ],
        [
            'name'  => esc_html__('White', 'aqualuxe'),
            'slug'  => 'white',
            'color' => '#ffffff',
        ],
        [
            'name'  => esc_html__('Black', 'aqualuxe'),
            'slug'  => 'black',
            'color' => '#000814',
        ],
        [
            'name'  => esc_html__('Gold', 'aqualuxe'),
            'slug'  => 'gold',
            'color' => '#d4af37',
        ],
    ]);

    // Add support for editor font sizes
    add_theme_support('editor-font-sizes', [
        [
            'name'      => esc_html__('Small', 'aqualuxe'),
            'shortName' => esc_html__('S', 'aqualuxe'),
            'size'      => 14,
            'slug'      => 'small',
        ],
        [
            'name'      => esc_html__('Normal', 'aqualuxe'),
            'shortName' => esc_html__('M', 'aqualuxe'),
            'size'      => 16,
            'slug'      => 'normal',
        ],
        [
            'name'      => esc_html__('Large', 'aqualuxe'),
            'shortName' => esc_html__('L', 'aqualuxe'),
            'size'      => 18,
            'slug'      => 'large',
        ],
        [
            'name'      => esc_html__('Larger', 'aqualuxe'),
            'shortName' => esc_html__('XL', 'aqualuxe'),
            'size'      => 24,
            'slug'      => 'larger',
        ],
    ]);

    // Add support for editor gradient presets
    add_theme_support('editor-gradient-presets', [
        [
            'name'     => esc_html__('Ocean Gradient', 'aqualuxe'),
            'gradient' => 'linear-gradient(135deg, #0077b6 0%, #00b4d8 100%)',
            'slug'     => 'ocean-gradient',
        ],
        [
            'name'     => esc_html__('Luxury Gradient', 'aqualuxe'),
            'gradient' => 'linear-gradient(135deg, #03045e 0%, #0077b6 100%)',
            'slug'     => 'luxury-gradient',
        ],
        [
            'name'     => esc_html__('Gold Gradient', 'aqualuxe'),
            'gradient' => 'linear-gradient(135deg, #d4af37 0%, #f5f5f5 100%)',
            'slug'     => 'gold-gradient',
        ],
    ]);

    // Add support for custom editor stylesheet
    add_editor_style('assets/dist/css/editor.css');

    // Set content width
    if (!isset($content_width)) {
        $content_width = 1140;
    }

    // Load theme textdomain
    load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');
}

/**
 * Register widget areas
 */
function aqualuxe_widgets_init() {
    register_sidebar([
        'name'          => esc_html__('Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => esc_html__('Footer 1', 'aqualuxe'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => esc_html__('Footer 2', 'aqualuxe'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => esc_html__('Footer 3', 'aqualuxe'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => esc_html__('Footer 4', 'aqualuxe'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    if (aqualuxe_is_woocommerce_active()) {
        register_sidebar([
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);
    }
}

/**
 * Add custom body classes
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Add a class if there is a custom header
    if (has_header_image()) {
        $classes[] = 'has-header-image';
    }

    // Add a class if there is a custom background
    if (get_background_image() || get_background_color() !== 'ffffff') {
        $classes[] = 'has-custom-background';
    }

    // Add a class if the sidebar is active
    if (is_active_sidebar('sidebar-1') && !is_page_template('templates/full-width.php')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add a class for the header layout
    $header_layout = aqualuxe_get_theme_mod('header_layout', 'default');
    $classes[] = 'header-layout-' . $header_layout;

    // Add a class for the footer layout
    $footer_layout = aqualuxe_get_theme_mod('footer_layout', 'default');
    $classes[] = 'footer-layout-' . $footer_layout;

    // Add a class for the blog layout
    if (is_home() || is_archive() || is_search()) {
        $blog_layout = aqualuxe_get_theme_mod('blog_layout', 'default');
        $classes[] = 'blog-layout-' . $blog_layout;
    }

    // Add a class for the shop layout
    if (aqualuxe_is_woocommerce_active() && (is_shop() || is_product_category() || is_product_tag())) {
        $shop_layout = aqualuxe_get_theme_mod('shop_layout', 'default');
        $classes[] = 'shop-layout-' . $shop_layout;
    }

    // Add a class for the product layout
    if (aqualuxe_is_woocommerce_active() && is_product()) {
        $product_layout = aqualuxe_get_theme_mod('product_layout', 'default');
        $classes[] = 'product-layout-' . $product_layout;
    }

    // Add a class for the dark mode
    if (aqualuxe_is_module_active('dark-mode') && aqualuxe_get_module('dark-mode')->is_dark_mode()) {
        $classes[] = 'dark-mode';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Change the excerpt length
 *
 * @param int $length Excerpt length
 * @return int
 */
function aqualuxe_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Change the excerpt more string
 *
 * @param string $more Excerpt more
 * @return string
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, [
        'aqualuxe-featured'  => esc_html__('Featured Image', 'aqualuxe'),
        'aqualuxe-blog'      => esc_html__('Blog Image', 'aqualuxe'),
        'aqualuxe-thumbnail' => esc_html__('Thumbnail', 'aqualuxe'),
        'aqualuxe-square'    => esc_html__('Square', 'aqualuxe'),
        'aqualuxe-portrait'  => esc_html__('Portrait', 'aqualuxe'),
        'aqualuxe-landscape' => esc_html__('Landscape', 'aqualuxe'),
    ]);
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add custom query vars
 *
 * @param array $vars Query vars
 * @return array
 */
function aqualuxe_query_vars($vars) {
    $vars[] = 'aqualuxe_dark_mode';
    return $vars;
}
add_filter('query_vars', 'aqualuxe_query_vars');