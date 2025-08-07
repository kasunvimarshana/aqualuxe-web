<?php
/**
 * Custom template hooks for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add quick view button to product loop
 */
function aqualuxe_add_quick_view_button() {
    global $product;
    echo '<div class="product-quick-view">';
    echo '<button class="button quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 15);

/**
 * Add schema markup to products
 */
function aqualuxe_add_product_schema() {
    if (is_product()) {
        global $product;
        
        $schema = array(
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'image' => wp_get_attachment_url($product->get_image_id()),
            'description' => $product->get_description(),
            'sku' => $product->get_sku(),
            'offers' => array(
                '@type' => 'Offer',
                'priceCurrency' => get_woocommerce_currency(),
                'price' => $product->get_price(),
                'availability' => 'https://schema.org/' . ($product->is_in_stock() ? 'InStock' : 'OutOfStock'),
            ),
        );
        
        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'aqualuxe_add_product_schema');

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_open_graph_meta() {
    if (is_product()) {
        global $product;
        
        echo '<meta property="og:type" content="product" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($product->get_name()) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($product->get_short_description()) . '" />' . "\n";
        echo '<meta property="og:image" content="' . esc_url(wp_get_attachment_url($product->get_image_id())) . '" />' . "\n";
        echo '<meta property="product:price:amount" content="' . esc_attr($product->get_price()) . '" />' . "\n";
        echo '<meta property="product:price:currency" content="' . esc_attr(get_woocommerce_currency()) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_open_graph_meta');

/**
 * Add lazy loading to images
 */
function aqualuxe_add_lazy_loading($attr) {
    $attr['loading'] = 'lazy';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_add_lazy_loading');

/**
 * Register custom widget areas
 */
function aqualuxe_widgets_init() {
    register_sidebar(array(
        'name'          => __('Shop Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-shop',
        'description'   => __('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Product Filters', 'aqualuxe'),
        'id'            => 'product-filters',
        'description'   => __('Add filter widgets here to appear on the shop page.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'aqualuxe_widgets_init');