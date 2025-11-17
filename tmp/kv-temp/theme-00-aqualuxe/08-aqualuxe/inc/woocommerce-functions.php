<?php
/**
 * WooCommerce Functions
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce setup
 */
function aqualuxe_woocommerce_setup() {
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 700,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 10,
            'default_columns' => 3,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ));
    
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * Remove default WooCommerce wrapper
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Add custom WooCommerce wrapper
 */
function aqualuxe_woocommerce_wrapper_start() {
    echo '<main id="primary" class="site-main woocommerce-main" role="main">';
    echo '<div class="container">';
}
add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start', 10);

function aqualuxe_woocommerce_wrapper_end() {
    echo '</div>';
    echo '</main>';
}
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end', 10);

/**
 * Customize WooCommerce product loop
 */
function aqualuxe_woocommerce_loop_product_title() {
    echo '<h2 class="woocommerce-loop-product__title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h2>';
}
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_loop_product_title', 10);

/**
 * Add quick view button to product loop
 */
function aqualuxe_add_quick_view_button() {
    global $product;
    
    echo '<div class="product-actions">';
    echo '<button class="quick-view-btn" data-product-id="' . esc_attr($product->get_id()) . '" aria-label="' . esc_attr__('Quick View', 'aqualuxe') . '">';
    echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
    echo '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>';
    echo '<circle cx="12" cy="12" r="3"></circle>';
    echo '</svg>';
    echo esc_html__('Quick View', 'aqualuxe');
    echo '</button>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 15);

/**
 * Modify WooCommerce breadcrumbs
 */
function aqualuxe_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => ' / ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs" aria-label="' . esc_attr__('Breadcrumb Navigation', 'aqualuxe') . '">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => esc_html__('Home', 'aqualuxe'),
    );
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumbs');

/**
 * Change number of products per row
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod('aqualuxe_products_per_row', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Change number of products per page
 */
function aqualuxe_woocommerce_products_per_page() {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Add product badges
 */
function aqualuxe_product_badges() {
    global $product;
    
    echo '<div class="product-badges">';
    
    // Sale badge
    if ($product->is_on_sale()) {
        $percentage = '';
        if ($product->get_type() === 'simple' || $product->get_type() === 'external') {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            if ($regular_price && $sale_price) {
                $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                $percentage = '-' . $percentage . '%';
            }
        }
        echo '<span class="badge badge-sale">' . ($percentage ? $percentage : esc_html__('Sale', 'aqualuxe')) . '</span>';
    }
    
    // New badge (products newer than 30 days)
    $product_date = strtotime($product->get_date_created());
    $thirty_days_ago = strtotime('-30 days');
    if ($product_date > $thirty_days_ago) {
        echo '<span class="badge badge-new">' . esc_html__('New', 'aqualuxe') . '</span>';
    }
    
    // Out of stock badge
    if (!$product->is_in_stock()) {
        echo '<span class="badge badge-out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
    
    echo '</div>';
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_product_badges', 15);

/**
 * Customize single product page
 */
function aqualuxe_single_product_summary() {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
    
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 15);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 25);
    add_action('woocommerce_single_product_summary', 'aqualuxe_product_meta_custom', 30);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 35);
}
add_action('init', 'aqualuxe_single_product_summary');

/**
 * Custom product meta
 */
function aqualuxe_product_meta_custom() {
    global $product;
    
    echo '<div class="product_meta">';
    
    if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) {
        echo '<span class="sku_wrapper">' . esc_html__('SKU:', 'aqualuxe') . ' <span class="sku">' . ($product->get_sku() ? $product->get_sku() : esc_html__('N/A', 'aqualuxe')) . '</span></span>';
    }
    
    echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'aqualuxe') . ' ', '</span>');
    
    echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'aqualuxe') . ' ', '</span>');
    
    echo '</div>';
}

/**
 * Customize cart page
 */
function aqualuxe_cart_collaterals() {
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
    add_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 20);
}
add_action('init', 'aqualuxe_cart_collaterals');

/**
 * Add AJAX add to cart support
 */
function aqualuxe_ajax_add_to_cart() {
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die();
    }
    
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $variation = isset($_POST['variation']) ? $_POST['variation'] : array();
    
    if ($variation_id) {
        $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    } else {
        $added = WC()->cart->add_to_cart($product_id, $quantity);
    }
    
    if ($added) {
        wp_send_json_success(array(
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_subtotal(),
        ));
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart');
add_action('wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart');

/**
 * Update mini cart via AJAX
 */
function aqualuxe_update_mini_cart() {
    check_ajax_referer('aqualuxe_nonce', 'nonce');
    
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();
    
    wp_send_json_success(array(
        'mini_cart' => $mini_cart,
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_total' => WC()->cart->get_cart_subtotal(),
    ));
}
add_action('wp_ajax_aqualuxe_update_mini_cart', 'aqualuxe_update_mini_cart');
add_action('wp_ajax_nopriv_aqualuxe_update_mini_cart', 'aqualuxe_update_mini_cart');

/**
 * Customize checkout fields
 */
function aqualuxe_checkout_fields($fields) {
    // Reorder fields
    $fields['billing']['billing_first_name']['priority'] = 10;
    $fields['billing']['billing_last_name']['priority'] = 20;
    $fields['billing']['billing_company']['priority'] = 30;
    $fields['billing']['billing_country']['priority'] = 40;
    $fields['billing']['billing_address_1']['priority'] = 50;
    $fields['billing']['billing_address_2']['priority'] = 60;
    $fields['billing']['billing_city']['priority'] = 70;
    $fields['billing']['billing_state']['priority'] = 80;
    $fields['billing']['billing_postcode']['priority'] = 90;
    $fields['billing']['billing_phone']['priority'] = 100;
    $fields['billing']['billing_email']['priority'] = 110;
    
    // Add custom classes
    $fields['billing']['billing_first_name']['class'] = array('form-row-first');
    $fields['billing']['billing_last_name']['class'] = array('form-row-last');
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_checkout_fields');

/**
 * Hide related products if disabled in customizer
 */
function aqualuxe_output_related_products_args($args) {
    if (!get_theme_mod('aqualuxe_related_products', true)) {
        return array();
    }
    
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_output_related_products_args');

/**
 * Add schema markup for products
 */
function aqualuxe_product_schema_markup() {
    if (!is_product()) {
        return;
    }
    
    global $product;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => wp_strip_all_tags($product->get_short_description() ?: $product->get_description()),
        'sku' => $product->get_sku(),
        'offers' => array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => get_permalink($product->get_id()),
        ),
    );
    
    if ($product->get_image_id()) {
        $schema['image'] = wp_get_attachment_url($product->get_image_id());
    }
    
    if ($product->get_average_rating()) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}
add_action('wp_head', 'aqualuxe_product_schema_markup');

/**
 * Customize WooCommerce messages
 */
function aqualuxe_woocommerce_message_classes($classes) {
    $classes = str_replace('woocommerce-message', 'alert alert-success', $classes);
    $classes = str_replace('woocommerce-error', 'alert alert-danger', $classes);
    $classes = str_replace('woocommerce-info', 'alert alert-info', $classes);
    
    return $classes;
}
add_filter('wc_add_to_cart_message_html', 'aqualuxe_woocommerce_message_classes');