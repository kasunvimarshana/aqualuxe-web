<?php
/**
 * AquaLuxe WooCommerce Template Hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Header
 *
 * @see aqualuxe_woocommerce_header_cart()
 * @see aqualuxe_woocommerce_header_account()
 * @see aqualuxe_woocommerce_header_wishlist()
 * @see aqualuxe_woocommerce_header_search()
 */
add_action('aqualuxe_header_icons', 'aqualuxe_woocommerce_header_search', 10);
add_action('aqualuxe_header_icons', 'aqualuxe_woocommerce_header_account', 20);
add_action('aqualuxe_header_icons', 'aqualuxe_woocommerce_header_wishlist', 30);
add_action('aqualuxe_header_icons', 'aqualuxe_woocommerce_header_cart', 40);

/**
 * Product Loop
 *
 * @see aqualuxe_woocommerce_product_badges()
 * @see aqualuxe_woocommerce_quick_view_button()
 * @see aqualuxe_woocommerce_wishlist_button()
 */
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_product_badges', 5);
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15);
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20);

/**
 * Single Product
 *
 * @see aqualuxe_woocommerce_product_badges()
 * @see aqualuxe_woocommerce_product_stock_status()
 * @see aqualuxe_woocommerce_product_share()
 * @see aqualuxe_woocommerce_product_video()
 * @see aqualuxe_woocommerce_product_size_chart()
 * @see aqualuxe_woocommerce_recently_viewed_products()
 */
add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_badges', 5);
add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_video', 20);
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_stock_status', 25);
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_size_chart', 26);
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_share', 50);
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_recently_viewed_products', 20);

/**
 * Product Tabs
 *
 * @see aqualuxe_woocommerce_product_shipping_tab_content()
 * @see aqualuxe_woocommerce_product_care_tab_content()
 * @see aqualuxe_woocommerce_product_custom_tab_content()
 */
add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs_filter');

/**
 * Add custom product tabs
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function aqualuxe_woocommerce_product_tabs_filter($tabs) {
    global $product;
    
    // Shipping tab
    $tabs['shipping'] = array(
        'title'    => esc_html__('Shipping', 'aqualuxe'),
        'priority' => 40,
        'callback' => 'aqualuxe_woocommerce_product_shipping_tab_content',
    );
    
    // Care guide tab
    $tabs['care'] = array(
        'title'    => esc_html__('Care Guide', 'aqualuxe'),
        'priority' => 50,
        'callback' => 'aqualuxe_woocommerce_product_care_tab_content',
    );
    
    // Custom tab
    $custom_tab_title = get_post_meta($product->get_id(), '_custom_tab_title', true);
    $custom_tab_content = get_post_meta($product->get_id(), '_custom_tab_content', true);
    
    if ($custom_tab_title && $custom_tab_content) {
        $tabs['custom'] = array(
            'title'    => $custom_tab_title,
            'priority' => 60,
            'callback' => 'aqualuxe_woocommerce_product_custom_tab_content',
        );
    }
    
    return $tabs;
}

/**
 * Cart
 *
 * @see aqualuxe_woocommerce_cart_progress()
 * @see aqualuxe_woocommerce_cart_cross_sells()
 * @see aqualuxe_woocommerce_cart_totals()
 */
add_action('woocommerce_before_cart', 'aqualuxe_woocommerce_cart_progress', 10);

// Remove default WooCommerce cross-sells
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

// Add custom cross-sells
add_action('woocommerce_cart_collaterals', 'aqualuxe_woocommerce_cart_cross_sells', 10);

/**
 * Checkout
 *
 * @see aqualuxe_woocommerce_checkout_progress()
 * @see aqualuxe_woocommerce_checkout_coupon_form()
 * @see aqualuxe_woocommerce_checkout_login_form()
 * @see aqualuxe_woocommerce_checkout_billing_form()
 * @see aqualuxe_woocommerce_checkout_shipping_form()
 * @see aqualuxe_woocommerce_checkout_payment()
 * @see aqualuxe_woocommerce_checkout_order_review()
 */
add_action('woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_progress', 5);

// Remove default WooCommerce checkout coupon form
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

// Add custom checkout coupon form
add_action('woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_coupon_form', 10);

/**
 * Account
 *
 * @see aqualuxe_woocommerce_account_navigation()
 * @see aqualuxe_woocommerce_account_content()
 * @see aqualuxe_woocommerce_account_dashboard()
 * @see aqualuxe_woocommerce_account_orders()
 * @see aqualuxe_woocommerce_account_downloads()
 * @see aqualuxe_woocommerce_account_edit_address()
 * @see aqualuxe_woocommerce_account_payment_methods()
 * @see aqualuxe_woocommerce_account_edit_account()
 * @see aqualuxe_woocommerce_account_view_order()
 * @see aqualuxe_woocommerce_account_wishlist()
 */
add_action('woocommerce_account_wishlist_endpoint', 'aqualuxe_woocommerce_account_wishlist');

/**
 * Add wishlist endpoint
 */
function aqualuxe_woocommerce_add_wishlist_endpoint() {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
}
add_action('init', 'aqualuxe_woocommerce_add_wishlist_endpoint');

/**
 * Add wishlist query var
 *
 * @param array $vars Query vars.
 * @return array
 */
function aqualuxe_woocommerce_wishlist_query_vars($vars) {
    $vars[] = 'wishlist';
    return $vars;
}
add_filter('query_vars', 'aqualuxe_woocommerce_wishlist_query_vars', 0);

/**
 * Add wishlist to account menu items
 *
 * @param array $items Menu items.
 * @return array
 */
function aqualuxe_woocommerce_wishlist_menu_item($items) {
    // Add wishlist item after dashboard
    $new_items = array();
    
    foreach ($items as $key => $value) {
        $new_items[$key] = $value;
        
        if ($key === 'dashboard') {
            $new_items['wishlist'] = esc_html__('Wishlist', 'aqualuxe');
        }
    }
    
    return $new_items;
}
add_filter('woocommerce_account_menu_items', 'aqualuxe_woocommerce_wishlist_menu_item');

/**
 * Shop
 *
 * @see aqualuxe_woocommerce_shop_filter_toggle()
 * @see aqualuxe_woocommerce_shop_active_filters()
 */
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_filter_toggle', 15);
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_active_filters', 20);

/**
 * Change number of products per row
 *
 * @return int
 */
function aqualuxe_woocommerce_loop_columns() {
    return aqualuxe_get_option('products_per_row', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Change number of products per page
 *
 * @return int
 */
function aqualuxe_woocommerce_products_per_page() {
    return aqualuxe_get_option('products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Change related products args
 *
 * @param array $args Related products args.
 * @return array
 */
function aqualuxe_woocommerce_related_products_args($args) {
    $args['posts_per_page'] = aqualuxe_get_option('related_products_count', 4);
    $args['columns'] = min(4, $args['posts_per_page']);
    
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

/**
 * Change upsell products args
 *
 * @param array $args Upsell products args.
 * @return array
 */
function aqualuxe_woocommerce_upsell_products_args($args) {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    
    return $args;
}
add_filter('woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsell_products_args');

/**
 * Change cross-sells columns
 *
 * @return int
 */
function aqualuxe_woocommerce_cross_sells_columns() {
    return 2;
}
add_filter('woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sells_columns');

/**
 * Change cross-sells total
 *
 * @return int
 */
function aqualuxe_woocommerce_cross_sells_total() {
    return 2;
}
add_filter('woocommerce_cross_sells_total', 'aqualuxe_woocommerce_cross_sells_total');

/**
 * Change checkout fields
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function aqualuxe_woocommerce_checkout_fields($fields) {
    // Make the order notes field optional
    if (!aqualuxe_get_option('show_order_notes', true)) {
        unset($fields['order']['order_comments']);
    }
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields');

/**
 * Add custom body classes
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_woocommerce_body_classes($classes) {
    // Add class for shop layout
    if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
        $shop_layout = aqualuxe_get_option('shop_layout', 'grid');
        $classes[] = 'shop-layout-' . $shop_layout;
        
        // Add class for shop sidebar
        if (aqualuxe_get_option('show_shop_sidebar', true)) {
            $shop_sidebar_position = aqualuxe_get_option('shop_sidebar_position', 'right');
            $classes[] = 'shop-sidebar-' . $shop_sidebar_position;
        } else {
            $classes[] = 'shop-sidebar-none';
        }
    }
    
    // Add class for product layout
    if (is_product()) {
        $product_layout = aqualuxe_get_option('product_layout', 'standard');
        $classes[] = 'product-layout-' . $product_layout;
        
        // Add class for product sidebar
        if (aqualuxe_get_option('show_product_sidebar', false)) {
            $classes[] = 'product-has-sidebar';
        } else {
            $classes[] = 'product-no-sidebar';
        }
    }
    
    // Add class for cart layout
    if (is_cart()) {
        $cart_layout = aqualuxe_get_option('cart_layout', 'standard');
        $classes[] = 'cart-layout-' . $cart_layout;
    }
    
    // Add class for checkout layout
    if (is_checkout()) {
        $checkout_layout = aqualuxe_get_option('checkout_layout', 'standard');
        $classes[] = 'checkout-layout-' . $checkout_layout;
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_woocommerce_body_classes');

/**
 * Add custom product classes
 *
 * @param array $classes Product classes.
 * @return array
 */
function aqualuxe_woocommerce_product_classes($classes) {
    global $product;
    
    if (!$product) {
        return $classes;
    }
    
    // Add class for product stock status
    if ($product->is_in_stock()) {
        $classes[] = 'in-stock';
    } else {
        $classes[] = 'out-of-stock';
    }
    
    // Add class for product featured status
    if ($product->is_featured()) {
        $classes[] = 'featured';
    }
    
    // Add class for product sale status
    if ($product->is_on_sale()) {
        $classes[] = 'on-sale';
    }
    
    // Add class for product type
    $classes[] = 'product-type-' . $product->get_type();
    
    return $classes;
}
add_filter('woocommerce_post_class', 'aqualuxe_woocommerce_product_classes');

/**
 * Modify sale flash
 *
 * @param string $html    Sale flash HTML.
 * @param object $post    Post object.
 * @param object $product Product object.
 * @return string
 */
function aqualuxe_woocommerce_sale_flash($html, $post, $product) {
    if ($product->is_on_sale()) {
        $sale_text = esc_html__('Sale', 'aqualuxe');
        
        // If the product has a sale percentage, show it
        if ($product->get_type() === 'variable') {
            $percentages = array();
            
            // Get all variation prices
            $prices = $product->get_variation_prices();
            
            // Loop through variation prices
            foreach ($prices['price'] as $key => $price) {
                // Only on sale variations
                if ($prices['regular_price'][$key] !== $price) {
                    // Calculate and set percentage
                    $percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
                }
            }
            
            // Get highest percentage for variable products
            if (!empty($percentages)) {
                $percentage = max($percentages) . '%';
                $sale_text = sprintf(esc_html__('-%s', 'aqualuxe'), $percentage);
            }
        } elseif ($product->get_type() === 'simple') {
            $regular_price = (float) $product->get_regular_price();
            $sale_price = (float) $product->get_sale_price();
            
            if ($regular_price > 0) {
                $percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';
                $sale_text = sprintf(esc_html__('-%s', 'aqualuxe'), $percentage);
            }
        }
        
        $html = '<span class="onsale">' . $sale_text . '</span>';
    }
    
    return $html;
}
add_filter('woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3);

/**
 * Add recently viewed products to cookie
 */
function aqualuxe_woocommerce_track_product_view() {
    if (!is_singular('product')) {
        return;
    }
    
    global $post;
    
    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = array();
    } else {
        $viewed_products = (array) explode('|', $_COOKIE['woocommerce_recently_viewed']);
    }
    
    // Remove current product from the array
    $viewed_products = array_diff($viewed_products, array($post->ID));
    
    // Add current product to the start of the array
    array_unshift($viewed_products, $post->ID);
    
    // Limit to 15 products
    if (count($viewed_products) > 15) {
        $viewed_products = array_slice($viewed_products, 0, 15);
    }
    
    // Store the cookie
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}
add_action('template_redirect', 'aqualuxe_woocommerce_track_product_view', 20);

/**
 * Add AJAX handlers
 */

/**
 * Quick view AJAX handler
 */
function aqualuxe_woocommerce_quick_view_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-quick-view-nonce')) {
        wp_send_json_error(array('message' => esc_html__('Invalid nonce', 'aqualuxe')));
    }
    
    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error(array('message' => esc_html__('Invalid product ID', 'aqualuxe')));
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array('message' => esc_html__('Invalid product', 'aqualuxe')));
    }
    
    // Get quick view template
    ob_start();
    include get_template_directory() . '/woocommerce/quick-view.php';
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view_ajax');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view_ajax');

/**
 * Add to wishlist AJAX handler
 */
function aqualuxe_woocommerce_add_to_wishlist_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-wishlist-nonce')) {
        wp_send_json_error(array('message' => esc_html__('Invalid nonce', 'aqualuxe')));
    }
    
    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error(array('message' => esc_html__('Invalid product ID', 'aqualuxe')));
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array('message' => esc_html__('Invalid product', 'aqualuxe')));
    }
    
    // Get user ID
    $user_id = get_current_user_id();
    
    // If user is logged in, save to user meta
    if ($user_id) {
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        $wishlist = $wishlist ? $wishlist : array();
        
        if (in_array($product_id, $wishlist)) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, array($product_id));
            update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
            
            wp_send_json_success(array(
                'message' => esc_html__('Product removed from wishlist', 'aqualuxe'),
                'action'  => 'removed',
            ));
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
            
            wp_send_json_success(array(
                'message' => esc_html__('Product added to wishlist', 'aqualuxe'),
                'action'  => 'added',
            ));
        }
    } else {
        // If user is not logged in, save to cookie
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? explode(',', $_COOKIE['aqualuxe_wishlist']) : array();
        
        if (in_array($product_id, $wishlist)) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, array($product_id));
            setcookie('aqualuxe_wishlist', implode(',', $wishlist), time() + (86400 * 30), '/');
            
            wp_send_json_success(array(
                'message' => esc_html__('Product removed from wishlist', 'aqualuxe'),
                'action'  => 'removed',
            ));
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            setcookie('aqualuxe_wishlist', implode(',', $wishlist), time() + (86400 * 30), '/');
            
            wp_send_json_success(array(
                'message' => esc_html__('Product added to wishlist', 'aqualuxe'),
                'action'  => 'added',
            ));
        }
    }
}
add_action('wp_ajax_aqualuxe_add_to_wishlist', 'aqualuxe_woocommerce_add_to_wishlist_ajax');
add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', 'aqualuxe_woocommerce_add_to_wishlist_ajax');

/**
 * Add shortcodes
 */

/**
 * Featured products shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_woocommerce_featured_products_shortcode($atts) {
    $atts = shortcode_atts(array(
        'per_page' => 4,
        'columns'  => 4,
        'orderby'  => 'date',
        'order'    => 'desc',
        'category' => '',
        'operator' => 'IN',
    ), $atts, 'aqualuxe_featured_products');
    
    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => $atts['per_page'],
        'orderby'             => $atts['orderby'],
        'order'               => $atts['order'],
        'tax_query'           => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            ),
        ),
    );
    
    if (!empty($atts['category'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => explode(',', $atts['category']),
            'operator' => $atts['operator'],
        );
    }
    
    ob_start();
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
    } else {
        echo '<p>' . esc_html__('No featured products found', 'aqualuxe') . '</p>';
    }
    
    wp_reset_postdata();
    
    return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
}
add_shortcode('aqualuxe_featured_products', 'aqualuxe_woocommerce_featured_products_shortcode');

/**
 * New products shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_woocommerce_new_products_shortcode($atts) {
    $atts = shortcode_atts(array(
        'per_page' => 4,
        'columns'  => 4,
        'orderby'  => 'date',
        'order'    => 'desc',
        'category' => '',
        'operator' => 'IN',
    ), $atts, 'aqualuxe_new_products');
    
    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => $atts['per_page'],
        'orderby'             => $atts['orderby'],
        'order'               => $atts['order'],
    );
    
    if (!empty($atts['category'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => explode(',', $atts['category']),
            'operator' => $atts['operator'],
        );
    }
    
    ob_start();
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
    } else {
        echo '<p>' . esc_html__('No products found', 'aqualuxe') . '</p>';
    }
    
    wp_reset_postdata();
    
    return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
}
add_shortcode('aqualuxe_new_products', 'aqualuxe_woocommerce_new_products_shortcode');

/**
 * Sale products shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_woocommerce_sale_products_shortcode($atts) {
    $atts = shortcode_atts(array(
        'per_page' => 4,
        'columns'  => 4,
        'orderby'  => 'date',
        'order'    => 'desc',
        'category' => '',
        'operator' => 'IN',
    ), $atts, 'aqualuxe_sale_products');
    
    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => $atts['per_page'],
        'orderby'             => $atts['orderby'],
        'order'               => $atts['order'],
        'meta_query'          => array(
            'relation' => 'OR',
            array(
                'key'     => '_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ),
            array(
                'key'     => '_min_variation_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ),
        ),
    );
    
    if (!empty($atts['category'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => explode(',', $atts['category']),
            'operator' => $atts['operator'],
        );
    }
    
    ob_start();
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
    } else {
        echo '<p>' . esc_html__('No sale products found', 'aqualuxe') . '</p>';
    }
    
    wp_reset_postdata();
    
    return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
}
add_shortcode('aqualuxe_sale_products', 'aqualuxe_woocommerce_sale_products_shortcode');

/**
 * Best selling products shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_woocommerce_best_selling_products_shortcode($atts) {
    $atts = shortcode_atts(array(
        'per_page' => 4,
        'columns'  => 4,
        'category' => '',
        'operator' => 'IN',
    ), $atts, 'aqualuxe_best_selling_products');
    
    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => $atts['per_page'],
        'meta_key'            => 'total_sales',
        'orderby'             => 'meta_value_num',
        'order'               => 'desc',
    );
    
    if (!empty($atts['category'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => explode(',', $atts['category']),
            'operator' => $atts['operator'],
        );
    }
    
    ob_start();
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
    } else {
        echo '<p>' . esc_html__('No products found', 'aqualuxe') . '</p>';
    }
    
    wp_reset_postdata();
    
    return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
}
add_shortcode('aqualuxe_best_selling_products', 'aqualuxe_woocommerce_best_selling_products_shortcode');

/**
 * Product categories shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_woocommerce_product_categories_shortcode($atts) {
    $atts = shortcode_atts(array(
        'number'     => null,
        'orderby'    => 'name',
        'order'      => 'ASC',
        'columns'    => 4,
        'hide_empty' => 1,
        'parent'     => '',
        'ids'        => '',
    ), $atts, 'aqualuxe_product_categories');
    
    $args = array(
        'orderby'    => $atts['orderby'],
        'order'      => $atts['order'],
        'hide_empty' => $atts['hide_empty'],
        'include'    => $atts['ids'] ? explode(',', $atts['ids']) : array(),
        'pad_counts' => true,
        'number'     => $atts['number'],
    );
    
    if ('' !== $atts['parent']) {
        $args['parent'] = $atts['parent'];
    }
    
    $categories = get_terms('product_cat', $args);
    
    if (is_wp_error($categories)) {
        return '';
    }
    
    if (empty($categories)) {
        return '<p>' . esc_html__('No product categories found', 'aqualuxe') . '</p>';
    }
    
    ob_start();
    
    echo '<div class="woocommerce columns-' . $atts['columns'] . '">';
    echo '<ul class="products columns-' . $atts['columns'] . ' product-categories">';
    
    foreach ($categories as $category) {
        wc_get_template('content-product_cat.php', array(
            'category' => $category,
        ));
    }
    
    echo '</ul>';
    echo '</div>';
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_product_categories', 'aqualuxe_woocommerce_product_categories_shortcode');

/**
 * Add widgets
 */

/**
 * Register widgets
 */
function aqualuxe_woocommerce_register_widgets() {
    // Register custom widgets
    register_widget('AquaLuxe_Widget_Featured_Products');
    register_widget('AquaLuxe_Widget_New_Products');
    register_widget('AquaLuxe_Widget_Sale_Products');
    register_widget('AquaLuxe_Widget_Best_Selling_Products');
    register_widget('AquaLuxe_Widget_Product_Categories');
    register_widget('AquaLuxe_Widget_Product_Filter');
    register_widget('AquaLuxe_Widget_Product_Search');
    register_widget('AquaLuxe_Widget_Product_Tags');
    register_widget('AquaLuxe_Widget_Product_Brands');
    register_widget('AquaLuxe_Widget_Product_Price_Filter');
    register_widget('AquaLuxe_Widget_Product_Rating_Filter');
    register_widget('AquaLuxe_Widget_Product_Attribute_Filter');
    register_widget('AquaLuxe_Widget_Product_Recently_Viewed');
    register_widget('AquaLuxe_Widget_Product_Wishlist');
    register_widget('AquaLuxe_Widget_Product_Compare');
}
add_action('widgets_init', 'aqualuxe_woocommerce_register_widgets');