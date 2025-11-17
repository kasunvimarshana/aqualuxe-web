<?php
/**
 * AquaLuxe WooCommerce Integration
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize WooCommerce support
 */
function aqualuxe_woocommerce_init() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce');
    
    // Add theme support for WooCommerce features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    
    // Add WooCommerce body classes
    add_filter('body_class', 'aqualuxe_woocommerce_body_classes');
    
    // Set up WooCommerce hooks
    aqualuxe_woocommerce_hooks_init();
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_init');

/**
 * Initialize WooCommerce hooks
 */
function aqualuxe_woocommerce_hooks_init() {
    // Remove default WooCommerce wrappers
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    
    // Add custom WooCommerce wrappers
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start', 10);
    add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end', 10);
    
    // Add custom WooCommerce sidebar
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    add_action('woocommerce_sidebar', 'aqualuxe_woocommerce_sidebar', 10);
    
    // Modify shop columns
    add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_shop_columns');
    
    // Modify products per page
    add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');
    
    // Modify related products
    add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');
    
    // Modify upsell products
    add_filter('woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsell_products_args');
    
    // Modify cross-sell products
    add_filter('woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sells_columns');
    add_filter('woocommerce_cross_sells_total', 'aqualuxe_woocommerce_cross_sells_total');
    
    // Add product quick view
    if (aqualuxe_get_option('aqualuxe_show_quick_view', true)) {
        add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_quick_view_button', 15);
        add_action('wp_ajax_aqualuxe_product_quick_view', 'aqualuxe_woocommerce_product_quick_view');
        add_action('wp_ajax_nopriv_aqualuxe_product_quick_view', 'aqualuxe_woocommerce_product_quick_view');
    }
    
    // Add wishlist button
    if (aqualuxe_get_option('aqualuxe_show_wishlist', true) && function_exists('YITH_WCWL')) {
        add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_wishlist_button', 20);
    }
    
    // Add compare button
    if (aqualuxe_get_option('aqualuxe_show_compare', true) && function_exists('yith_woocompare_constructor')) {
        add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_compare_button', 25);
    }
    
    // Modify product tabs
    add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs');
    
    // Add social share buttons to single product
    if (aqualuxe_get_option('aqualuxe_show_product_social_share', true)) {
        add_action('woocommerce_share', 'aqualuxe_woocommerce_social_share');
    }
    
    // Modify checkout fields
    add_filter('woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields');
    
    // Add custom order fields
    add_action('woocommerce_checkout_update_order_meta', 'aqualuxe_woocommerce_checkout_update_order_meta');
    
    // Add custom order details
    add_action('woocommerce_admin_order_data_after_billing_address', 'aqualuxe_woocommerce_admin_order_data');
    
    // Add custom account menu items
    add_filter('woocommerce_account_menu_items', 'aqualuxe_woocommerce_account_menu_items');
    
    // Add custom account endpoints
    add_action('init', 'aqualuxe_woocommerce_add_account_endpoints');
    
    // Add custom account endpoint content
    add_action('woocommerce_account_wishlist_endpoint', 'aqualuxe_woocommerce_account_wishlist_content');
    
    // Add mini cart fragments
    add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments');
    
    // Add header cart
    add_action('aqualuxe_header_extras', 'aqualuxe_woocommerce_header_cart', 10);
    
    // Add header account
    add_action('aqualuxe_header_extras', 'aqualuxe_woocommerce_header_account', 20);
    
    // Add header wishlist
    if (function_exists('YITH_WCWL')) {
        add_action('aqualuxe_header_extras', 'aqualuxe_woocommerce_header_wishlist', 30);
    }
    
    // Add header compare
    if (function_exists('yith_woocompare_constructor')) {
        add_action('aqualuxe_header_extras', 'aqualuxe_woocommerce_header_compare', 40);
    }
    
    // Add product filter widget
    add_action('widgets_init', 'aqualuxe_woocommerce_widgets_init');
    
    // Add product view switcher
    add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_view_switcher', 30);
    
    // Add product filter button
    add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_filter_button', 40);
    
    // Add product filter sidebar
    add_action('wp_footer', 'aqualuxe_woocommerce_product_filter_sidebar');
    
    // Add quick view modal
    if (aqualuxe_get_option('aqualuxe_show_quick_view', true)) {
        add_action('wp_footer', 'aqualuxe_woocommerce_quick_view_modal');
    }
    
    // Add size guide modal
    add_action('wp_footer', 'aqualuxe_woocommerce_size_guide_modal');
    
    // Add shipping info modal
    add_action('wp_footer', 'aqualuxe_woocommerce_shipping_info_modal');
    
    // Add product video
    add_action('woocommerce_product_thumbnails', 'aqualuxe_woocommerce_product_video', 30);
    
    // Add product 360 view
    add_action('woocommerce_product_thumbnails', 'aqualuxe_woocommerce_product_360_view', 40);
    
    // Add product countdown
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_product_countdown', 10);
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_countdown', 15);
    
    // Add product stock progress bar
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_stock_progress_bar', 30);
    
    // Add product extra information
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_extra_information', 40);
    
    // Add product brand
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_brand', 5);
    
    // Add product trust badges
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_trust_badges', 45);
    
    // Add product frequently bought together
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_frequently_bought_together', 15);
    
    // Add product recently viewed
    add_action('woocommerce_after_single_product', 'aqualuxe_woocommerce_product_recently_viewed', 10);
    
    // Add product categories carousel
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_product_categories_carousel', 5);
    
    // Add product brands carousel
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_product_brands_carousel', 10);
    
    // Add shop top banner
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_shop_top_banner', 15);
    
    // Add shop bottom banner
    add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_shop_bottom_banner', 15);
    
    // Add shop sidebar banner
    add_action('woocommerce_sidebar', 'aqualuxe_woocommerce_shop_sidebar_banner', 15);
    
    // Add cart cross-sells
    add_action('woocommerce_cart_collaterals', 'aqualuxe_woocommerce_cart_cross_sells', 5);
    
    // Add checkout coupon
    add_action('woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_coupon', 10);
    
    // Add checkout login
    add_action('woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_login', 5);
    
    // Add checkout order review
    add_action('woocommerce_checkout_before_order_review_heading', 'aqualuxe_woocommerce_checkout_order_review', 10);
    
    // Add checkout payment methods
    add_action('woocommerce_review_order_before_payment', 'aqualuxe_woocommerce_checkout_payment_methods', 10);
    
    // Add checkout trust badges
    add_action('woocommerce_review_order_after_payment', 'aqualuxe_woocommerce_checkout_trust_badges', 10);
    
    // Add thank you page details
    add_action('woocommerce_thankyou', 'aqualuxe_woocommerce_thankyou_details', 10);
    
    // Add order tracking form
    add_shortcode('aqualuxe_order_tracking', 'aqualuxe_woocommerce_order_tracking_shortcode');
    
    // Add product search form
    add_shortcode('aqualuxe_product_search', 'aqualuxe_woocommerce_product_search_shortcode');
    
    // Add product categories grid
    add_shortcode('aqualuxe_product_categories', 'aqualuxe_woocommerce_product_categories_shortcode');
    
    // Add products grid
    add_shortcode('aqualuxe_products', 'aqualuxe_woocommerce_products_shortcode');
    
    // Add products carousel
    add_shortcode('aqualuxe_products_carousel', 'aqualuxe_woocommerce_products_carousel_shortcode');
    
    // Add products tabs
    add_shortcode('aqualuxe_products_tabs', 'aqualuxe_woocommerce_products_tabs_shortcode');
    
    // Add sale countdown
    add_shortcode('aqualuxe_sale_countdown', 'aqualuxe_woocommerce_sale_countdown_shortcode');
    
    // Add product brands
    add_shortcode('aqualuxe_product_brands', 'aqualuxe_woocommerce_product_brands_shortcode');
    
    // Add product deals
    add_shortcode('aqualuxe_product_deals', 'aqualuxe_woocommerce_product_deals_shortcode');
    
    // Add product comparison
    add_shortcode('aqualuxe_product_comparison', 'aqualuxe_woocommerce_product_comparison_shortcode');
}

/**
 * Add WooCommerce body classes
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_woocommerce_body_classes($classes) {
    // Add shop layout class
    if (is_shop() || is_product_category() || is_product_tag()) {
        $classes[] = 'shop-layout-' . aqualuxe_get_option('aqualuxe_shop_layout', 'left-sidebar');
    }
    
    // Add product layout class
    if (is_product()) {
        $classes[] = 'product-layout-' . aqualuxe_get_option('aqualuxe_product_layout', 'no-sidebar');
    }
    
    // Add cart layout class
    if (is_cart()) {
        $classes[] = 'cart-layout-default';
    }
    
    // Add checkout layout class
    if (is_checkout()) {
        $classes[] = 'checkout-layout-default';
    }
    
    // Add account layout class
    if (is_account_page()) {
        $classes[] = 'account-layout-default';
    }
    
    return $classes;
}

/**
 * WooCommerce wrapper start
 */
function aqualuxe_woocommerce_wrapper_start() {
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
    <?php
}

/**
 * WooCommerce wrapper end
 */
function aqualuxe_woocommerce_wrapper_end() {
    ?>
        </main><!-- #main -->
    </div><!-- #primary -->
    <?php
}

/**
 * WooCommerce sidebar
 */
function aqualuxe_woocommerce_sidebar() {
    // Check if sidebar should be displayed
    $layout = '';
    
    if (is_shop() || is_product_category() || is_product_tag()) {
        $layout = aqualuxe_get_option('aqualuxe_shop_layout', 'left-sidebar');
    } elseif (is_product()) {
        $layout = aqualuxe_get_option('aqualuxe_product_layout', 'no-sidebar');
    }
    
    if ($layout === 'no-sidebar') {
        return;
    }
    
    // Display sidebar
    if (is_active_sidebar('shop-sidebar')) {
        ?>
        <aside id="secondary" class="widget-area shop-sidebar">
            <?php dynamic_sidebar('shop-sidebar'); ?>
        </aside><!-- #secondary -->
        <?php
    }
}

/**
 * WooCommerce loop shop columns
 *
 * @return int
 */
function aqualuxe_woocommerce_loop_shop_columns() {
    return aqualuxe_get_option('aqualuxe_product_columns', 4);
}

/**
 * WooCommerce products per page
 *
 * @return int
 */
function aqualuxe_woocommerce_products_per_page() {
    return aqualuxe_get_option('aqualuxe_products_per_page', 12);
}

/**
 * WooCommerce related products args
 *
 * @param array $args Related products args
 * @return array
 */
function aqualuxe_woocommerce_related_products_args($args) {
    $args['posts_per_page'] = aqualuxe_get_option('aqualuxe_related_products_count', 4);
    $args['columns'] = aqualuxe_get_option('aqualuxe_product_columns', 4);
    
    return $args;
}

/**
 * WooCommerce upsell products args
 *
 * @param array $args Upsell products args
 * @return array
 */
function aqualuxe_woocommerce_upsell_products_args($args) {
    $args['posts_per_page'] = aqualuxe_get_option('aqualuxe_upsell_products_count', 4);
    $args['columns'] = aqualuxe_get_option('aqualuxe_product_columns', 4);
    
    return $args;
}

/**
 * WooCommerce cross-sells columns
 *
 * @return int
 */
function aqualuxe_woocommerce_cross_sells_columns() {
    return aqualuxe_get_option('aqualuxe_product_columns', 4);
}

/**
 * WooCommerce cross-sells total
 *
 * @return int
 */
function aqualuxe_woocommerce_cross_sells_total() {
    return aqualuxe_get_option('aqualuxe_cross_sell_products_count', 4);
}

/**
 * WooCommerce quick view button
 */
function aqualuxe_woocommerce_quick_view_button() {
    global $product;
    
    echo '<div class="product-quick-view-button">';
    echo '<button type="button" class="button quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">';
    
    if (function_exists('aqualuxe_get_icon')) {
        echo aqualuxe_get_icon('eye');
    }
    
    echo esc_html__('Quick View', 'aqualuxe');
    echo '</button>';
    echo '</div>';
}

/**
 * WooCommerce product quick view
 */
function aqualuxe_woocommerce_product_quick_view() {
    if (!isset($_POST['product_id'])) {
        wp_die();
    }
    
    $product_id = absint($_POST['product_id']);
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_die();
    }
    
    // Get product data
    $data = [
        'id' => $product->get_id(),
        'title' => $product->get_name(),
        'price_html' => $product->get_price_html(),
        'short_description' => $product->get_short_description(),
        'rating_html' => wc_get_rating_html($product->get_average_rating()),
        'stock_html' => wc_get_stock_html($product),
        'add_to_cart_url' => $product->add_to_cart_url(),
        'add_to_cart_text' => $product->add_to_cart_text(),
        'images' => [],
        'attributes' => [],
        'variations' => [],
        'permalink' => get_permalink($product_id),
    ];
    
    // Get product images
    $attachment_ids = $product->get_gallery_image_ids();
    
    if ($product->get_image_id()) {
        array_unshift($attachment_ids, $product->get_image_id());
    }
    
    foreach ($attachment_ids as $attachment_id) {
        $data['images'][] = [
            'full' => wp_get_attachment_image_url($attachment_id, 'full'),
            'large' => wp_get_attachment_image_url($attachment_id, 'large'),
            'thumbnail' => wp_get_attachment_image_url($attachment_id, 'thumbnail'),
        ];
    }
    
    // Get product attributes
    if ($product->is_type('variable')) {
        $attributes = $product->get_variation_attributes();
        
        foreach ($attributes as $attribute_name => $attribute_values) {
            $data['attributes'][] = [
                'name' => wc_attribute_label($attribute_name),
                'slug' => $attribute_name,
                'values' => $attribute_values,
            ];
        }
        
        // Get product variations
        $variations = $product->get_available_variations();
        
        foreach ($variations as $variation) {
            $data['variations'][] = [
                'id' => $variation['variation_id'],
                'price_html' => $variation['price_html'],
                'attributes' => $variation['attributes'],
                'image' => $variation['image'],
            ];
        }
    }
    
    // Return product data
    wp_send_json_success($data);
}

/**
 * WooCommerce wishlist button
 */
function aqualuxe_woocommerce_wishlist_button() {
    if (function_exists('YITH_WCWL')) {
        echo do_shortcode('[yith_wcwl_add_to_wishlist]');
    }
}

/**
 * WooCommerce compare button
 */
function aqualuxe_woocommerce_compare_button() {
    if (function_exists('yith_woocompare_constructor')) {
        global $product;
        
        echo '<div class="product-compare-button">';
        echo do_shortcode('[yith_compare_button product_id="' . $product->get_id() . '"]');
        echo '</div>';
    }
}

/**
 * WooCommerce product tabs
 *
 * @param array $tabs Product tabs
 * @return array
 */
function aqualuxe_woocommerce_product_tabs($tabs) {
    // Add custom tabs
    $tabs['shipping'] = [
        'title' => __('Shipping & Returns', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    ];
    
    $tabs['size_guide'] = [
        'title' => __('Size Guide', 'aqualuxe'),
        'priority' => 40,
        'callback' => 'aqualuxe_woocommerce_size_guide_tab_content',
    ];
    
    return $tabs;
}

/**
 * WooCommerce shipping tab content
 */
function aqualuxe_woocommerce_shipping_tab_content() {
    // Get shipping content from theme options
    $shipping_content = aqualuxe_get_option('aqualuxe_shipping_content', '');
    
    if ($shipping_content) {
        echo wp_kses_post($shipping_content);
    } else {
        ?>
        <h3><?php esc_html_e('Shipping Information', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('We ship worldwide. All orders are processed within 1-2 business days. Shipping times vary based on location.', 'aqualuxe'); ?></p>
        
        <h3><?php esc_html_e('Returns & Exchanges', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('We offer a 30-day return policy for most items. Please contact us for return authorization before sending any items back.', 'aqualuxe'); ?></p>
        <?php
    }
}

/**
 * WooCommerce size guide tab content
 */
function aqualuxe_woocommerce_size_guide_tab_content() {
    // Get size guide content from theme options
    $size_guide_content = aqualuxe_get_option('aqualuxe_size_guide_content', '');
    
    if ($size_guide_content) {
        echo wp_kses_post($size_guide_content);
    } else {
        ?>
        <h3><?php esc_html_e('Size Guide', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('Please refer to the size guide below to find your perfect fit.', 'aqualuxe'); ?></p>
        
        <table class="size-guide-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Size', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Chest (in)', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Waist (in)', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Hips (in)', 'aqualuxe'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>S</td>
                    <td>36-38</td>
                    <td>28-30</td>
                    <td>35-37</td>
                </tr>
                <tr>
                    <td>M</td>
                    <td>39-41</td>
                    <td>31-33</td>
                    <td>38-40</td>
                </tr>
                <tr>
                    <td>L</td>
                    <td>42-44</td>
                    <td>34-36</td>
                    <td>41-43</td>
                </tr>
                <tr>
                    <td>XL</td>
                    <td>45-47</td>
                    <td>37-39</td>
                    <td>44-46</td>
                </tr>
            </tbody>
        </table>
        <?php
    }
}

/**
 * WooCommerce social share
 */
function aqualuxe_woocommerce_social_share() {
    aqualuxe_social_share([
        'title' => __('Share This Product', 'aqualuxe'),
        'networks' => ['facebook', 'twitter', 'pinterest', 'linkedin', 'email'],
    ]);
}

/**
 * WooCommerce checkout fields
 *
 * @param array $fields Checkout fields
 * @return array
 */
function aqualuxe_woocommerce_checkout_fields($fields) {
    // Add placeholder to fields
    foreach (['billing', 'shipping'] as $field_group) {
        foreach ($fields[$field_group] as $key => $field) {
            if (!isset($field['placeholder']) && isset($field['label'])) {
                $fields[$field_group][$key]['placeholder'] = $field['label'];
            }
        }
    }
    
    // Add custom fields
    $fields['billing']['billing_company']['priority'] = 25;
    $fields['billing']['billing_phone']['priority'] = 35;
    
    $fields['order']['order_comments']['placeholder'] = __('Notes about your order, e.g. special notes for delivery', 'aqualuxe');
    
    // Add delivery date field
    $fields['billing']['billing_delivery_date'] = [
        'label' => __('Preferred Delivery Date', 'aqualuxe'),
        'placeholder' => __('Select a date', 'aqualuxe'),
        'required' => false,
        'class' => ['form-row-wide'],
        'clear' => true,
        'type' => 'date',
        'priority' => 100,
    ];
    
    return $fields;
}

/**
 * WooCommerce checkout update order meta
 *
 * @param int $order_id Order ID
 */
function aqualuxe_woocommerce_checkout_update_order_meta($order_id) {
    if (isset($_POST['billing_delivery_date'])) {
        update_post_meta($order_id, '_billing_delivery_date', sanitize_text_field($_POST['billing_delivery_date']));
    }
}

/**
 * WooCommerce admin order data
 *
 * @param WC_Order $order Order object
 */
function aqualuxe_woocommerce_admin_order_data($order) {
    $delivery_date = get_post_meta($order->get_id(), '_billing_delivery_date', true);
    
    if ($delivery_date) {
        echo '<p><strong>' . esc_html__('Preferred Delivery Date:', 'aqualuxe') . '</strong> ' . esc_html($delivery_date) . '</p>';
    }
}

/**
 * WooCommerce account menu items
 *
 * @param array $items Account menu items
 * @return array
 */
function aqualuxe_woocommerce_account_menu_items($items) {
    // Add wishlist item
    if (function_exists('YITH_WCWL')) {
        $items['wishlist'] = __('Wishlist', 'aqualuxe');
    }
    
    return $items;
}

/**
 * WooCommerce add account endpoints
 */
function aqualuxe_woocommerce_add_account_endpoints() {
    // Add wishlist endpoint
    if (function_exists('YITH_WCWL')) {
        add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
    }
}

/**
 * WooCommerce account wishlist content
 */
function aqualuxe_woocommerce_account_wishlist_content() {
    if (function_exists('YITH_WCWL')) {
        echo do_shortcode('[yith_wcwl_wishlist]');
    }
}

/**
 * WooCommerce cart fragments
 *
 * @param array $fragments Cart fragments
 * @return array
 */
function aqualuxe_woocommerce_cart_fragments($fragments) {
    // Cart count
    ob_start();
    ?>
    <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
    <?php
    $fragments['.cart-count'] = ob_get_clean();
    
    // Cart total
    ob_start();
    ?>
    <span class="cart-total"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span>
    <?php
    $fragments['.cart-total'] = ob_get_clean();
    
    // Mini cart
    ob_start();
    woocommerce_mini_cart();
    $fragments['.widget_shopping_cart_content'] = ob_get_clean();
    
    return $fragments;
}

/**
 * WooCommerce header cart
 */
function aqualuxe_woocommerce_header_cart() {
    if (!aqualuxe_get_option('aqualuxe_header_show_cart', true)) {
        return;
    }
    
    ?>
    <div class="header-cart">
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-cart-link">
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('cart'); ?>
            <?php else : ?>
                <span class="header-cart-icon"></span>
            <?php endif; ?>
            <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
        </a>
        <div class="header-cart-dropdown">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce header account
 */
function aqualuxe_woocommerce_header_account() {
    if (!aqualuxe_get_option('aqualuxe_header_show_account', true)) {
        return;
    }
    
    ?>
    <div class="header-account">
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="header-account-link">
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('user'); ?>
            <?php else : ?>
                <span class="header-account-icon"></span>
            <?php endif; ?>
        </a>
        <div class="header-account-dropdown">
            <?php if (is_user_logged_in()) : ?>
                <ul class="header-account-links">
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">
                            <?php esc_html_e('Dashboard', 'aqualuxe'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">
                            <?php esc_html_e('Orders', 'aqualuxe'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>">
                            <?php esc_html_e('Account Details', 'aqualuxe'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_logout_url()); ?>">
                            <?php esc_html_e('Logout', 'aqualuxe'); ?>
                        </a>
                    </li>
                </ul>
            <?php else : ?>
                <div class="header-account-login">
                    <h3><?php esc_html_e('Login', 'aqualuxe'); ?></h3>
                    <form class="woocommerce-form woocommerce-form-login login" method="post">
                        <?php do_action('woocommerce_login_form_start'); ?>
                        
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="username"><?php esc_html_e('Username or email address', 'aqualuxe'); ?> <span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" />
                        </p>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="password"><?php esc_html_e('Password', 'aqualuxe'); ?> <span class="required">*</span></label>
                            <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
                        </p>
                        
                        <?php do_action('woocommerce_login_form'); ?>
                        
                        <p class="form-row">
                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e('Remember me', 'aqualuxe'); ?></span>
                            </label>
                            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                            <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e('Log in', 'aqualuxe'); ?>"><?php esc_html_e('Log in', 'aqualuxe'); ?></button>
                        </p>
                        <p class="woocommerce-LostPassword lost_password">
                            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'aqualuxe'); ?></a>
                        </p>
                        
                        <?php do_action('woocommerce_login_form_end'); ?>
                    </form>
                    
                    <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
                        <div class="header-account-register">
                            <p><?php esc_html_e('Don\'t have an account?', 'aqualuxe'); ?></p>
                            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="button"><?php esc_html_e('Register', 'aqualuxe'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce header wishlist
 */
function aqualuxe_woocommerce_header_wishlist() {
    if (!aqualuxe_get_option('aqualuxe_header_show_wishlist', true) || !function_exists('YITH_WCWL')) {
        return;
    }
    
    ?>
    <div class="header-wishlist">
        <a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>" class="header-wishlist-link">
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('heart'); ?>
            <?php else : ?>
                <span class="header-wishlist-icon"></span>
            <?php endif; ?>
            <span class="wishlist-count"><?php echo esc_html(yith_wcwl_count_all_products()); ?></span>
        </a>
    </div>
    <?php
}

/**
 * WooCommerce header compare
 */
function aqualuxe_woocommerce_header_compare() {
    if (!aqualuxe_get_option('aqualuxe_header_show_compare', true) || !function_exists('yith_woocompare_constructor')) {
        return;
    }
    
    global $yith_woocompare;
    
    ?>
    <div class="header-compare">
        <a href="<?php echo esc_url(add_query_arg(['action' => 'yith-woocompare-view-table', 'iframe' => 'yes'], site_url())); ?>" class="header-compare-link" data-fancybox data-type="iframe">
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('refresh'); ?>
            <?php else : ?>
                <span class="header-compare-icon"></span>
            <?php endif; ?>
            <span class="compare-count"><?php echo esc_html(count($yith_woocompare->obj->products_list)); ?></span>
        </a>
    </div>
    <?php
}

/**
 * WooCommerce widgets init
 */
function aqualuxe_woocommerce_widgets_init() {
    register_sidebar([
        'name' => __('Shop Sidebar', 'aqualuxe'),
        'id' => 'shop-sidebar',
        'description' => __('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ]);
    
    register_sidebar([
        'name' => __('Product Filter Sidebar', 'aqualuxe'),
        'id' => 'product-filter-sidebar',
        'description' => __('Add widgets here to appear in your product filter sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ]);
}

/**
 * WooCommerce product view switcher
 */
function aqualuxe_woocommerce_product_view_switcher() {
    ?>
    <div class="product-view-switcher">
        <button type="button" class="product-view-button product-view-grid active" data-view="grid" aria-label="<?php esc_attr_e('Grid view', 'aqualuxe'); ?>">
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('grid'); ?>
            <?php else : ?>
                <span class="product-view-icon product-view-grid-icon"></span>
            <?php endif; ?>
        </button>
        <button type="button" class="product-view-button product-view-list" data-view="list" aria-label="<?php esc_attr_e('List view', 'aqualuxe'); ?>">
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('list'); ?>
            <?php else : ?>
                <span class="product-view-icon product-view-list-icon"></span>
            <?php endif; ?>
        </button>
    </div>
    <?php
}

/**
 * WooCommerce product filter button
 */
function aqualuxe_woocommerce_product_filter_button() {
    ?>
    <button type="button" class="product-filter-button" aria-label="<?php esc_attr_e('Filter products', 'aqualuxe'); ?>">
        <?php if (function_exists('aqualuxe_get_icon')) : ?>
            <?php echo aqualuxe_get_icon('filter'); ?>
        <?php else : ?>
            <span class="product-filter-icon"></span>
        <?php endif; ?>
        <?php esc_html_e('Filter', 'aqualuxe'); ?>
    </button>
    <?php
}

/**
 * WooCommerce product filter sidebar
 */
function aqualuxe_woocommerce_product_filter_sidebar() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    ?>
    <div class="product-filter-sidebar">
        <div class="product-filter-sidebar-header">
            <h2><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h2>
            <button type="button" class="product-filter-close" aria-label="<?php esc_attr_e('Close filter', 'aqualuxe'); ?>">
                <?php if (function_exists('aqualuxe_get_icon')) : ?>
                    <?php echo aqualuxe_get_icon('close'); ?>
                <?php else : ?>
                    <span class="product-filter-close-icon"></span>
                <?php endif; ?>
            </button>
        </div>
        <div class="product-filter-sidebar-content">
            <?php dynamic_sidebar('product-filter-sidebar'); ?>
        </div>
        <div class="product-filter-sidebar-footer">
            <button type="button" class="button product-filter-apply"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
            <button type="button" class="button product-filter-reset"><?php esc_html_e('Reset Filters', 'aqualuxe'); ?></button>
        </div>
    </div>
    <div class="product-filter-overlay"></div>
    <?php
}

/**
 * WooCommerce quick view modal
 */
function aqualuxe_woocommerce_quick_view_modal() {
    ?>
    <div id="quick-view-modal" class="quick-view-modal">
        <div class="quick-view-modal-content">
            <button type="button" class="quick-view-close" aria-label="<?php esc_attr_e('Close quick view', 'aqualuxe'); ?>">
                <?php if (function_exists('aqualuxe_get_icon')) : ?>
                    <?php echo aqualuxe_get_icon('close'); ?>
                <?php else : ?>
                    <span class="quick-view-close-icon"></span>
                <?php endif; ?>
            </button>
            <div class="quick-view-content"></div>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce size guide modal
 */
function aqualuxe_woocommerce_size_guide_modal() {
    if (!is_product()) {
        return;
    }
    
    ?>
    <div id="size-guide-modal" class="size-guide-modal">
        <div class="size-guide-modal-content">
            <button type="button" class="size-guide-close" aria-label="<?php esc_attr_e('Close size guide', 'aqualuxe'); ?>">
                <?php if (function_exists('aqualuxe_get_icon')) : ?>
                    <?php echo aqualuxe_get_icon('close'); ?>
                <?php else : ?>
                    <span class="size-guide-close-icon"></span>
                <?php endif; ?>
            </button>
            <div class="size-guide-content">
                <?php aqualuxe_woocommerce_size_guide_tab_content(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce shipping info modal
 */
function aqualuxe_woocommerce_shipping_info_modal() {
    if (!is_product()) {
        return;
    }
    
    ?>
    <div id="shipping-info-modal" class="shipping-info-modal">
        <div class="shipping-info-modal-content">
            <button type="button" class="shipping-info-close" aria-label="<?php esc_attr_e('Close shipping info', 'aqualuxe'); ?>">
                <?php if (function_exists('aqualuxe_get_icon')) : ?>
                    <?php echo aqualuxe_get_icon('close'); ?>
                <?php else : ?>
                    <span class="shipping-info-close-icon"></span>
                <?php endif; ?>
            </button>
            <div class="shipping-info-content">
                <?php aqualuxe_woocommerce_shipping_tab_content(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce product video
 */
function aqualuxe_woocommerce_product_video() {
    global $product;
    
    $video_url = get_post_meta($product->get_id(), '_aqualuxe_product_video', true);
    
    if (!$video_url) {
        return;
    }
    
    ?>
    <div class="product-video">
        <a href="<?php echo esc_url($video_url); ?>" class="product-video-link" data-fancybox>
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('play'); ?>
            <?php else : ?>
                <span class="product-video-icon"></span>
            <?php endif; ?>
            <?php esc_html_e('Watch Video', 'aqualuxe'); ?>
        </a>
    </div>
    <?php
}

/**
 * WooCommerce product 360 view
 */
function aqualuxe_woocommerce_product_360_view() {
    global $product;
    
    $gallery_360 = get_post_meta($product->get_id(), '_aqualuxe_product_360_gallery', true);
    
    if (!$gallery_360) {
        return;
    }
    
    $gallery_360_ids = explode(',', $gallery_360);
    
    if (empty($gallery_360_ids)) {
        return;
    }
    
    ?>
    <div class="product-360-view">
        <a href="#product-360-view-modal" class="product-360-view-link" data-fancybox>
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('360'); ?>
            <?php else : ?>
                <span class="product-360-view-icon"></span>
            <?php endif; ?>
            <?php esc_html_e('360° View', 'aqualuxe'); ?>
        </a>
    </div>
    
    <div id="product-360-view-modal" class="product-360-view-modal" style="display: none;">
        <div class="product-360-view-container">
            <div class="product-360-view-images">
                <?php foreach ($gallery_360_ids as $image_id) : ?>
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'full')); ?>" alt="">
                <?php endforeach; ?>
            </div>
            <div class="product-360-view-controls">
                <button type="button" class="product-360-view-prev" aria-label="<?php esc_attr_e('Previous', 'aqualuxe'); ?>">
                    <?php if (function_exists('aqualuxe_get_icon')) : ?>
                        <?php echo aqualuxe_get_icon('chevron-left'); ?>
                    <?php else : ?>
                        <span class="product-360-view-prev-icon"></span>
                    <?php endif; ?>
                </button>
                <button type="button" class="product-360-view-next" aria-label="<?php esc_attr_e('Next', 'aqualuxe'); ?>">
                    <?php if (function_exists('aqualuxe_get_icon')) : ?>
                        <?php echo aqualuxe_get_icon('chevron-right'); ?>
                    <?php else : ?>
                        <span class="product-360-view-next-icon"></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce product countdown
 */
function aqualuxe_woocommerce_product_countdown() {
    global $product;
    
    if (!$product->is_on_sale()) {
        return;
    }
    
    $sale_price_dates_to = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
    
    if (!$sale_price_dates_to) {
        return;
    }
    
    $now = current_time('timestamp');
    
    if ($now > $sale_price_dates_to) {
        return;
    }
    
    ?>
    <div class="product-countdown" data-date="<?php echo esc_attr(date('Y/m/d H:i:s', $sale_price_dates_to)); ?>">
        <div class="product-countdown-title"><?php esc_html_e('Sale Ends In:', 'aqualuxe'); ?></div>
        <div class="product-countdown-timer">
            <div class="product-countdown-days">
                <span class="product-countdown-value">00</span>
                <span class="product-countdown-label"><?php esc_html_e('Days', 'aqualuxe'); ?></span>
            </div>
            <div class="product-countdown-hours">
                <span class="product-countdown-value">00</span>
                <span class="product-countdown-label"><?php esc_html_e('Hours', 'aqualuxe'); ?></span>
            </div>
            <div class="product-countdown-minutes">
                <span class="product-countdown-value">00</span>
                <span class="product-countdown-label"><?php esc_html_e('Minutes', 'aqualuxe'); ?></span>
            </div>
            <div class="product-countdown-seconds">
                <span class="product-countdown-value">00</span>
                <span class="product-countdown-label"><?php esc_html_e('Seconds', 'aqualuxe'); ?></span>
            </div>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce product stock progress bar
 */
function aqualuxe_woocommerce_product_stock_progress_bar() {
    global $product;
    
    if (!$product->managing_stock()) {
        return;
    }
    
    $stock_quantity = $product->get_stock_quantity();
    $low_stock_amount = get_option('woocommerce_notify_low_stock_amount');
    $total_stock = get_post_meta($product->get_id(), '_aqualuxe_total_stock', true);
    
    if (!$stock_quantity || !$total_stock) {
        return;
    }
    
    $percentage = ($stock_quantity / $total_stock) * 100;
    
    ?>
    <div class="product-stock-progress">
        <div class="product-stock-progress-bar">
            <div class="product-stock-progress-bar-inner" style="width: <?php echo esc_attr($percentage); ?>%"></div>
        </div>
        <div class="product-stock-progress-info">
            <?php if ($stock_quantity <= $low_stock_amount) : ?>
                <div class="product-stock-progress-text product-stock-low">
                    <?php esc_html_e('Hurry! Only', 'aqualuxe'); ?> <?php echo esc_html($stock_quantity); ?> <?php esc_html_e('left in stock', 'aqualuxe'); ?>
                </div>
            <?php else : ?>
                <div class="product-stock-progress-text">
                    <?php echo esc_html($stock_quantity); ?> <?php esc_html_e('in stock', 'aqualuxe'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce product extra information
 */
function aqualuxe_woocommerce_product_extra_information() {
    ?>
    <div class="product-extra-information">
        <div class="product-extra-information-item product-extra-shipping">
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('truck'); ?>
            <?php else : ?>
                <span class="product-extra-shipping-icon"></span>
            <?php endif; ?>
            <div class="product-extra-information-content">
                <h4><?php esc_html_e('Free Shipping', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('On orders over $50', 'aqualuxe'); ?></p>
            </div>
        </div>
        <div class="product-extra-information-item product-extra-returns">
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('refresh'); ?>
            <?php else : ?>
                <span class="product-extra-returns-icon"></span>
            <?php endif; ?>
            <div class="product-extra-information-content">
                <h4><?php esc_html_e('Easy Returns', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('30-day return policy', 'aqualuxe'); ?></p>
            </div>
        </div>
        <div class="product-extra-information-item product-extra-secure">
            <?php if (function_exists('aqualuxe_get_icon')) : ?>
                <?php echo aqualuxe_get_icon('lock'); ?>
            <?php else : ?>
                <span class="product-extra-secure-icon"></span>
            <?php endif; ?>
            <div class="product-extra-information-content">
                <h4><?php esc_html_e('Secure Checkout', 'aqualuxe'); ?></h4>
                <p><?php esc_html_e('100% secure payment', 'aqualuxe'); ?></p>
            </div>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce product brand
 */
function aqualuxe_woocommerce_product_brand() {
    global $product;
    
    $brand = get_post_meta($product->get_id(), '_aqualuxe_product_brand', true);
    
    if (!$brand) {
        return;
    }
    
    ?>
    <div class="product-brand">
        <?php echo esc_html($brand); ?>
    </div>
    <?php
}

/**
 * WooCommerce product trust badges
 */
function aqualuxe_woocommerce_product_trust_badges() {
    ?>
    <div class="product-trust-badges">
        <div class="product-trust-badges-title"><?php esc_html_e('Guaranteed Safe Checkout', 'aqualuxe'); ?></div>
        <div class="product-trust-badges-images">
            <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/visa.svg'); ?>" alt="Visa">
            <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/mastercard.svg'); ?>" alt="Mastercard">
            <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/amex.svg'); ?>" alt="American Express">
            <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/paypal.svg'); ?>" alt="PayPal">
            <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/apple-pay.svg'); ?>" alt="Apple Pay">
            <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/google-pay.svg'); ?>" alt="Google Pay">
        </div>
    </div>
    <?php
}

/**
 * WooCommerce product frequently bought together
 */
function aqualuxe_woocommerce_product_frequently_bought_together() {
    global $product;
    
    $frequently_bought_together = get_post_meta($product->get_id(), '_aqualuxe_frequently_bought_together', true);
    
    if (!$frequently_bought_together) {
        return;
    }
    
    $product_ids = explode(',', $frequently_bought_together);
    
    if (empty($product_ids)) {
        return;
    }
    
    ?>
    <div class="product-frequently-bought-together">
        <h2><?php esc_html_e('Frequently Bought Together', 'aqualuxe'); ?></h2>
        <div class="product-frequently-bought-together-content">
            <div class="product-frequently-bought-together-products">
                <div class="product-frequently-bought-together-product">
                    <div class="product-frequently-bought-together-product-image">
                        <?php echo $product->get_image(); ?>
                    </div>
                    <div class="product-frequently-bought-together-product-title">
                        <?php echo esc_html($product->get_name()); ?>
                    </div>
                    <div class="product-frequently-bought-together-product-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                </div>
                
                <?php foreach ($product_ids as $product_id) : ?>
                    <?php $related_product = wc_get_product($product_id); ?>
                    
                    <?php if ($related_product && $related_product->is_in_stock()) : ?>
                        <div class="product-frequently-bought-together-product">
                            <div class="product-frequently-bought-together-product-image">
                                <?php echo $related_product->get_image(); ?>
                            </div>
                            <div class="product-frequently-bought-together-product-title">
                                <?php echo esc_html($related_product->get_name()); ?>
                            </div>
                            <div class="product-frequently-bought-together-product-price">
                                <?php echo $related_product->get_price_html(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <div class="product-frequently-bought-together-total">
                <div class="product-frequently-bought-together-total-price">
                    <?php
                    $total_price = $product->get_price();
                    
                    foreach ($product_ids as $product_id) {
                        $related_product = wc_get_product($product_id);
                        
                        if ($related_product && $related_product->is_in_stock()) {
                            $total_price += $related_product->get_price();
                        }
                    }
                    
                    echo wc_price($total_price);
                    ?>
                </div>
                <button type="button" class="button product-frequently-bought-together-add-to-cart"><?php esc_html_e('Add All to Cart', 'aqualuxe'); ?></button>
            </div>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce product recently viewed
 */
function aqualuxe_woocommerce_product_recently_viewed() {
    if (!is_singular('product')) {
        return;
    }
    
    // Get current product ID
    $product_id = get_the_ID();
    
    // Get cookie
    $viewed_products = isset($_COOKIE['aqualuxe_recently_viewed_products']) ? explode(',', $_COOKIE['aqualuxe_recently_viewed_products']) : [];
    
    // Remove current product from the list
    $viewed_products = array_diff($viewed_products, [$product_id]);
    
    // Add current product to the beginning of the list
    array_unshift($viewed_products, $product_id);
    
    // Keep only the last 15 products
    $viewed_products = array_slice($viewed_products, 0, 15);
    
    // Save cookie
    setcookie('aqualuxe_recently_viewed_products', implode(',', $viewed_products), time() + 30 * DAY_IN_SECONDS, '/');
    
    // Get recently viewed products
    $recently_viewed = array_diff($viewed_products, [$product_id]);
    
    if (empty($recently_viewed)) {
        return;
    }
    
    // Limit to 4 products
    $recently_viewed = array_slice($recently_viewed, 0, 4);
    
    ?>
    <div class="product-recently-viewed">
        <h2><?php esc_html_e('Recently Viewed', 'aqualuxe'); ?></h2>
        <div class="product-recently-viewed-products">
            <?php foreach ($recently_viewed as $viewed_product_id) : ?>
                <?php $viewed_product = wc_get_product($viewed_product_id); ?>
                
                <?php if ($viewed_product) : ?>
                    <div class="product-recently-viewed-product">
                        <a href="<?php echo esc_url(get_permalink($viewed_product_id)); ?>" class="product-recently-viewed-product-image">
                            <?php echo $viewed_product->get_image(); ?>
                        </a>
                        <h3 class="product-recently-viewed-product-title">
                            <a href="<?php echo esc_url(get_permalink($viewed_product_id)); ?>">
                                <?php echo esc_html($viewed_product->get_name()); ?>
                            </a>
                        </h3>
                        <div class="product-recently-viewed-product-price">
                            <?php echo $viewed_product->get_price_html(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce product categories carousel
 */
function aqualuxe_woocommerce_product_categories_carousel() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
    ]);
    
    if (is_wp_error($categories) || empty($categories)) {
        return;
    }
    
    ?>
    <div class="product-categories-carousel">
        <div class="product-categories-carousel-inner">
            <?php foreach ($categories as $category) : ?>
                <div class="product-category-item">
                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="product-category-link">
                        <?php
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        
                        if ($thumbnail_id) {
                            echo wp_get_attachment_image($thumbnail_id, 'thumbnail');
                        }
                        ?>
                        <span class="product-category-title"><?php echo esc_html($category->name); ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce product brands carousel
 */
function aqualuxe_woocommerce_product_brands_carousel() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    // Check if product brands taxonomy exists
    if (!taxonomy_exists('product_brand')) {
        return;
    }
    
    $brands = get_terms([
        'taxonomy' => 'product_brand',
        'hide_empty' => true,
    ]);
    
    if (is_wp_error($brands) || empty($brands)) {
        return;
    }
    
    ?>
    <div class="product-brands-carousel">
        <div class="product-brands-carousel-inner">
            <?php foreach ($brands as $brand) : ?>
                <div class="product-brand-item">
                    <a href="<?php echo esc_url(get_term_link($brand)); ?>" class="product-brand-link">
                        <?php
                        $thumbnail_id = get_term_meta($brand->term_id, 'thumbnail_id', true);
                        
                        if ($thumbnail_id) {
                            echo wp_get_attachment_image($thumbnail_id, 'thumbnail');
                        } else {
                            echo esc_html($brand->name);
                        }
                        ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce shop top banner
 */
function aqualuxe_woocommerce_shop_top_banner() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    $banner_image = aqualuxe_get_option('aqualuxe_shop_top_banner_image', '');
    $banner_title = aqualuxe_get_option('aqualuxe_shop_top_banner_title', '');
    $banner_subtitle = aqualuxe_get_option('aqualuxe_shop_top_banner_subtitle', '');
    $banner_button_text = aqualuxe_get_option('aqualuxe_shop_top_banner_button_text', '');
    $banner_button_url = aqualuxe_get_option('aqualuxe_shop_top_banner_button_url', '');
    
    if (!$banner_image && !$banner_title && !$banner_subtitle) {
        return;
    }
    
    ?>
    <div class="shop-top-banner">
        <?php if ($banner_image) : ?>
            <div class="shop-top-banner-image">
                <img src="<?php echo esc_url($banner_image); ?>" alt="<?php echo esc_attr($banner_title); ?>">
            </div>
        <?php endif; ?>
        
        <div class="shop-top-banner-content">
            <?php if ($banner_title) : ?>
                <h2 class="shop-top-banner-title"><?php echo esc_html($banner_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($banner_subtitle) : ?>
                <p class="shop-top-banner-subtitle"><?php echo esc_html($banner_subtitle); ?></p>
            <?php endif; ?>
            
            <?php if ($banner_button_text && $banner_button_url) : ?>
                <a href="<?php echo esc_url($banner_button_url); ?>" class="button shop-top-banner-button"><?php echo esc_html($banner_button_text); ?></a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce shop bottom banner
 */
function aqualuxe_woocommerce_shop_bottom_banner() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    $banner_image = aqualuxe_get_option('aqualuxe_shop_bottom_banner_image', '');
    $banner_title = aqualuxe_get_option('aqualuxe_shop_bottom_banner_title', '');
    $banner_subtitle = aqualuxe_get_option('aqualuxe_shop_bottom_banner_subtitle', '');
    $banner_button_text = aqualuxe_get_option('aqualuxe_shop_bottom_banner_button_text', '');
    $banner_button_url = aqualuxe_get_option('aqualuxe_shop_bottom_banner_button_url', '');
    
    if (!$banner_image && !$banner_title && !$banner_subtitle) {
        return;
    }
    
    ?>
    <div class="shop-bottom-banner">
        <?php if ($banner_image) : ?>
            <div class="shop-bottom-banner-image">
                <img src="<?php echo esc_url($banner_image); ?>" alt="<?php echo esc_attr($banner_title); ?>">
            </div>
        <?php endif; ?>
        
        <div class="shop-bottom-banner-content">
            <?php if ($banner_title) : ?>
                <h2 class="shop-bottom-banner-title"><?php echo esc_html($banner_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($banner_subtitle) : ?>
                <p class="shop-bottom-banner-subtitle"><?php echo esc_html($banner_subtitle); ?></p>
            <?php endif; ?>
            
            <?php if ($banner_button_text && $banner_button_url) : ?>
                <a href="<?php echo esc_url($banner_button_url); ?>" class="button shop-bottom-banner-button"><?php echo esc_html($banner_button_text); ?></a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce shop sidebar banner
 */
function aqualuxe_woocommerce_shop_sidebar_banner() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    $banner_image = aqualuxe_get_option('aqualuxe_shop_sidebar_banner_image', '');
    $banner_title = aqualuxe_get_option('aqualuxe_shop_sidebar_banner_title', '');
    $banner_subtitle = aqualuxe_get_option('aqualuxe_shop_sidebar_banner_subtitle', '');
    $banner_button_text = aqualuxe_get_option('aqualuxe_shop_sidebar_banner_button_text', '');
    $banner_button_url = aqualuxe_get_option('aqualuxe_shop_sidebar_banner_button_url', '');
    
    if (!$banner_image && !$banner_title && !$banner_subtitle) {
        return;
    }
    
    ?>
    <div class="shop-sidebar-banner">
        <?php if ($banner_image) : ?>
            <div class="shop-sidebar-banner-image">
                <img src="<?php echo esc_url($banner_image); ?>" alt="<?php echo esc_attr($banner_title); ?>">
            </div>
        <?php endif; ?>
        
        <div class="shop-sidebar-banner-content">
            <?php if ($banner_title) : ?>
                <h3 class="shop-sidebar-banner-title"><?php echo esc_html($banner_title); ?></h3>
            <?php endif; ?>
            
            <?php if ($banner_subtitle) : ?>
                <p class="shop-sidebar-banner-subtitle"><?php echo esc_html($banner_subtitle); ?></p>
            <?php endif; ?>
            
            <?php if ($banner_button_text && $banner_button_url) : ?>
                <a href="<?php echo esc_url($banner_button_url); ?>" class="button shop-sidebar-banner-button"><?php echo esc_html($banner_button_text); ?></a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce cart cross-sells
 */
function aqualuxe_woocommerce_cart_cross_sells() {
    if (is_cart()) {
        woocommerce_cross_sell_display();
    }
}

/**
 * WooCommerce checkout coupon
 */
function aqualuxe_woocommerce_checkout_coupon() {
    if (is_checkout() && wc_coupons_enabled()) {
        wc_get_template('checkout/form-coupon.php', [
            'checkout' => WC()->checkout(),
        ]);
    }
}

/**
 * WooCommerce checkout login
 */
function aqualuxe_woocommerce_checkout_login() {
    if (is_checkout() && !is_user_logged_in() && 'yes' === get_option('woocommerce_enable_checkout_login_reminder')) {
        wc_get_template('checkout/form-login.php', [
            'checkout' => WC()->checkout(),
        ]);
    }
}

/**
 * WooCommerce checkout order review
 */
function aqualuxe_woocommerce_checkout_order_review() {
    if (is_checkout()) {
        wc_get_template('checkout/review-order.php', [
            'checkout' => WC()->checkout(),
        ]);
    }
}

/**
 * WooCommerce checkout payment methods
 */
function aqualuxe_woocommerce_checkout_payment_methods() {
    if (is_checkout()) {
        wc_get_template('checkout/payment.php', [
            'checkout' => WC()->checkout(),
        ]);
    }
}

/**
 * WooCommerce checkout trust badges
 */
function aqualuxe_woocommerce_checkout_trust_badges() {
    if (is_checkout()) {
        ?>
        <div class="checkout-trust-badges">
            <div class="checkout-trust-badges-title"><?php esc_html_e('Guaranteed Safe Checkout', 'aqualuxe'); ?></div>
            <div class="checkout-trust-badges-images">
                <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/visa.svg'); ?>" alt="Visa">
                <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/mastercard.svg'); ?>" alt="Mastercard">
                <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/amex.svg'); ?>" alt="American Express">
                <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/paypal.svg'); ?>" alt="PayPal">
                <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/apple-pay.svg'); ?>" alt="Apple Pay">
                <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment/google-pay.svg'); ?>" alt="Google Pay">
            </div>
        </div>
        <?php
    }
}

/**
 * WooCommerce thankyou details
 *
 * @param int $order_id Order ID
 */
function aqualuxe_woocommerce_thankyou_details($order_id) {
    if (!$order_id) {
        return;
    }
    
    $order = wc_get_order($order_id);
    
    if (!$order) {
        return;
    }
    
    ?>
    <div class="thankyou-details">
        <div class="thankyou-details-title"><?php esc_html_e('Order Details', 'aqualuxe'); ?></div>
        <div class="thankyou-details-content">
            <div class="thankyou-details-item">
                <div class="thankyou-details-label"><?php esc_html_e('Order Number:', 'aqualuxe'); ?></div>
                <div class="thankyou-details-value"><?php echo esc_html($order->get_order_number()); ?></div>
            </div>
            <div class="thankyou-details-item">
                <div class="thankyou-details-label"><?php esc_html_e('Date:', 'aqualuxe'); ?></div>
                <div class="thankyou-details-value"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></div>
            </div>
            <div class="thankyou-details-item">
                <div class="thankyou-details-label"><?php esc_html_e('Total:', 'aqualuxe'); ?></div>
                <div class="thankyou-details-value"><?php echo $order->get_formatted_order_total(); ?></div>
            </div>
            <div class="thankyou-details-item">
                <div class="thankyou-details-label"><?php esc_html_e('Payment Method:', 'aqualuxe'); ?></div>
                <div class="thankyou-details-value"><?php echo esc_html($order->get_payment_method_title()); ?></div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * WooCommerce order tracking shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_order_tracking_shortcode($atts) {
    ob_start();
    
    wc_get_template('order/form-tracking.php');
    
    return ob_get_clean();
}

/**
 * WooCommerce product search shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_product_search_shortcode($atts) {
    $atts = shortcode_atts([
        'placeholder' => __('Search products...', 'aqualuxe'),
        'button_text' => __('Search', 'aqualuxe'),
    ], $atts);
    
    ob_start();
    
    ?>
    <div class="product-search">
        <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
            <label class="screen-reader-text" for="woocommerce-product-search-field"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
            <input type="search" id="woocommerce-product-search-field" class="search-field" placeholder="<?php echo esc_attr($atts['placeholder']); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="search-submit"><?php echo esc_html($atts['button_text']); ?></button>
            <input type="hidden" name="post_type" value="product" />
        </form>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * WooCommerce product categories shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_product_categories_shortcode($atts) {
    $atts = shortcode_atts([
        'number' => 4,
        'columns' => 4,
        'hide_empty' => 1,
        'parent' => 0,
        'orderby' => 'name',
        'order' => 'ASC',
    ], $atts);
    
    $number = absint($atts['number']);
    $columns = absint($atts['columns']);
    $hide_empty = (bool) $atts['hide_empty'];
    $parent = absint($atts['parent']);
    
    $args = [
        'taxonomy' => 'product_cat',
        'hide_empty' => $hide_empty,
        'number' => $number,
        'parent' => $parent,
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    ];
    
    $categories = get_terms($args);
    
    if (is_wp_error($categories) || empty($categories)) {
        return '';
    }
    
    ob_start();
    
    ?>
    <div class="product-categories-grid columns-<?php echo esc_attr($columns); ?>">
        <?php foreach ($categories as $category) : ?>
            <div class="product-category">
                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="product-category-link">
                    <?php
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    
                    if ($thumbnail_id) {
                        echo wp_get_attachment_image($thumbnail_id, 'medium');
                    }
                    ?>
                    <h3 class="product-category-title"><?php echo esc_html($category->name); ?></h3>
                    <?php if ($category->count > 0) : ?>
                        <div class="product-category-count">
                            <?php echo esc_html(sprintf(_n('%s product', '%s products', $category->count, 'aqualuxe'), $category->count)); ?>
                        </div>
                    <?php endif; ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * WooCommerce products shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_products_shortcode($atts) {
    $atts = shortcode_atts([
        'limit' => 4,
        'columns' => 4,
        'orderby' => 'date',
        'order' => 'DESC',
        'category' => '',
        'tag' => '',
        'featured' => false,
        'best_selling' => false,
        'sale' => false,
        'ids' => '',
    ], $atts);
    
    $query_args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => $atts['limit'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    ];
    
    // Category
    if ($atts['category']) {
        $query_args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => explode(',', $atts['category']),
        ];
    }
    
    // Tag
    if ($atts['tag']) {
        $query_args['tax_query'][] = [
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => explode(',', $atts['tag']),
        ];
    }
    
    // Featured
    if ($atts['featured']) {
        $query_args['tax_query'][] = [
            'taxonomy' => 'product_visibility',
            'field' => 'name',
            'terms' => 'featured',
        ];
    }
    
    // Sale
    if ($atts['sale']) {
        $query_args['post__in'] = wc_get_product_ids_on_sale();
    }
    
    // Best selling
    if ($atts['best_selling']) {
        $query_args['meta_key'] = 'total_sales';
        $query_args['orderby'] = 'meta_value_num';
    }
    
    // IDs
    if ($atts['ids']) {
        $query_args['post__in'] = explode(',', $atts['ids']);
    }
    
    ob_start();
    
    $products = new WP_Query($query_args);
    
    if ($products->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}

/**
 * WooCommerce products carousel shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_products_carousel_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => '',
        'limit' => 8,
        'columns' => 4,
        'orderby' => 'date',
        'order' => 'DESC',
        'category' => '',
        'tag' => '',
        'featured' => false,
        'best_selling' => false,
        'sale' => false,
        'ids' => '',
    ], $atts);
    
    $query_args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => $atts['limit'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    ];
    
    // Category
    if ($atts['category']) {
        $query_args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => explode(',', $atts['category']),
        ];
    }
    
    // Tag
    if ($atts['tag']) {
        $query_args['tax_query'][] = [
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => explode(',', $atts['tag']),
        ];
    }
    
    // Featured
    if ($atts['featured']) {
        $query_args['tax_query'][] = [
            'taxonomy' => 'product_visibility',
            'field' => 'name',
            'terms' => 'featured',
        ];
    }
    
    // Sale
    if ($atts['sale']) {
        $query_args['post__in'] = wc_get_product_ids_on_sale();
    }
    
    // Best selling
    if ($atts['best_selling']) {
        $query_args['meta_key'] = 'total_sales';
        $query_args['orderby'] = 'meta_value_num';
    }
    
    // IDs
    if ($atts['ids']) {
        $query_args['post__in'] = explode(',', $atts['ids']);
    }
    
    ob_start();
    
    $products = new WP_Query($query_args);
    
    if ($products->have_posts()) {
        ?>
        <div class="products-carousel">
            <?php if ($atts['title']) : ?>
                <h2 class="products-carousel-title"><?php echo esc_html($atts['title']); ?></h2>
            <?php endif; ?>
            
            <div class="products-carousel-inner columns-<?php echo esc_attr($atts['columns']); ?>">
                <?php
                while ($products->have_posts()) {
                    $products->the_post();
                    wc_get_template_part('content', 'product');
                }
                ?>
            </div>
            
            <div class="products-carousel-nav">
                <button type="button" class="products-carousel-prev" aria-label="<?php esc_attr_e('Previous', 'aqualuxe'); ?>">
                    <?php if (function_exists('aqualuxe_get_icon')) : ?>
                        <?php echo aqualuxe_get_icon('chevron-left'); ?>
                    <?php else : ?>
                        <span class="products-carousel-prev-icon"></span>
                    <?php endif; ?>
                </button>
                <button type="button" class="products-carousel-next" aria-label="<?php esc_attr_e('Next', 'aqualuxe'); ?>">
                    <?php if (function_exists('aqualuxe_get_icon')) : ?>
                        <?php echo aqualuxe_get_icon('chevron-right'); ?>
                    <?php else : ?>
                        <span class="products-carousel-next-icon"></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
        <?php
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}

/**
 * WooCommerce products tabs shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_products_tabs_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => '',
        'limit' => 8,
        'columns' => 4,
    ], $atts);
    
    ob_start();
    
    ?>
    <div class="products-tabs">
        <?php if ($atts['title']) : ?>
            <h2 class="products-tabs-title"><?php echo esc_html($atts['title']); ?></h2>
        <?php endif; ?>
        
        <div class="products-tabs-nav">
            <button type="button" class="products-tabs-nav-item active" data-tab="featured"><?php esc_html_e('Featured', 'aqualuxe'); ?></button>
            <button type="button" class="products-tabs-nav-item" data-tab="best-selling"><?php esc_html_e('Best Selling', 'aqualuxe'); ?></button>
            <button type="button" class="products-tabs-nav-item" data-tab="sale"><?php esc_html_e('On Sale', 'aqualuxe'); ?></button>
            <button type="button" class="products-tabs-nav-item" data-tab="new"><?php esc_html_e('New Arrivals', 'aqualuxe'); ?></button>
        </div>
        
        <div class="products-tabs-content">
            <div class="products-tabs-panel active" data-tab="featured">
                <?php
                echo do_shortcode('[aqualuxe_products limit="' . esc_attr($atts['limit']) . '" columns="' . esc_attr($atts['columns']) . '" featured="true"]');
                ?>
            </div>
            <div class="products-tabs-panel" data-tab="best-selling">
                <?php
                echo do_shortcode('[aqualuxe_products limit="' . esc_attr($atts['limit']) . '" columns="' . esc_attr($atts['columns']) . '" best_selling="true"]');
                ?>
            </div>
            <div class="products-tabs-panel" data-tab="sale">
                <?php
                echo do_shortcode('[aqualuxe_products limit="' . esc_attr($atts['limit']) . '" columns="' . esc_attr($atts['columns']) . '" sale="true"]');
                ?>
            </div>
            <div class="products-tabs-panel" data-tab="new">
                <?php
                echo do_shortcode('[aqualuxe_products limit="' . esc_attr($atts['limit']) . '" columns="' . esc_attr($atts['columns']) . '" orderby="date" order="DESC"]');
                ?>
            </div>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * WooCommerce sale countdown shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_sale_countdown_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Deal of the Day', 'aqualuxe'),
        'product_id' => '',
        'end_date' => '',
    ], $atts);
    
    if (!$atts['product_id'] && !$atts['end_date']) {
        return '';
    }
    
    $product_id = $atts['product_id'];
    $end_date = $atts['end_date'];
    
    if ($product_id) {
        $product = wc_get_product($product_id);
        
        if (!$product || !$product->is_on_sale()) {
            return '';
        }
        
        $sale_price_dates_to = get_post_meta($product_id, '_sale_price_dates_to', true);
        
        if ($sale_price_dates_to) {
            $end_date = date('Y/m/d H:i:s', $sale_price_dates_to);
        }
    }
    
    if (!$end_date) {
        return '';
    }
    
    ob_start();
    
    ?>
    <div class="sale-countdown">
        <?php if ($atts['title']) : ?>
            <h2 class="sale-countdown-title"><?php echo esc_html($atts['title']); ?></h2>
        <?php endif; ?>
        
        <?php if ($product_id) : ?>
            <div class="sale-countdown-product">
                <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="sale-countdown-product-image">
                    <?php echo $product->get_image(); ?>
                </a>
                <div class="sale-countdown-product-content">
                    <h3 class="sale-countdown-product-title">
                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                            <?php echo esc_html($product->get_name()); ?>
                        </a>
                    </h3>
                    <div class="sale-countdown-product-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="sale-countdown-timer" data-date="<?php echo esc_attr($end_date); ?>">
            <div class="sale-countdown-days">
                <span class="sale-countdown-value">00</span>
                <span class="sale-countdown-label"><?php esc_html_e('Days', 'aqualuxe'); ?></span>
            </div>
            <div class="sale-countdown-hours">
                <span class="sale-countdown-value">00</span>
                <span class="sale-countdown-label"><?php esc_html_e('Hours', 'aqualuxe'); ?></span>
            </div>
            <div class="sale-countdown-minutes">
                <span class="sale-countdown-value">00</span>
                <span class="sale-countdown-label"><?php esc_html_e('Minutes', 'aqualuxe'); ?></span>
            </div>
            <div class="sale-countdown-seconds">
                <span class="sale-countdown-value">00</span>
                <span class="sale-countdown-label"><?php esc_html_e('Seconds', 'aqualuxe'); ?></span>
            </div>
        </div>
        
        <?php if ($product_id) : ?>
            <div class="sale-countdown-button">
                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="button"><?php echo esc_html($product->add_to_cart_text()); ?></a>
            </div>
        <?php endif; ?>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * WooCommerce product brands shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_product_brands_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Shop by Brand', 'aqualuxe'),
        'number' => 8,
        'columns' => 4,
        'hide_empty' => 1,
        'orderby' => 'name',
        'order' => 'ASC',
    ], $atts);
    
    // Check if product brands taxonomy exists
    if (!taxonomy_exists('product_brand')) {
        return '';
    }
    
    $args = [
        'taxonomy' => 'product_brand',
        'hide_empty' => (bool) $atts['hide_empty'],
        'number' => absint($atts['number']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    ];
    
    $brands = get_terms($args);
    
    if (is_wp_error($brands) || empty($brands)) {
        return '';
    }
    
    ob_start();
    
    ?>
    <div class="product-brands">
        <?php if ($atts['title']) : ?>
            <h2 class="product-brands-title"><?php echo esc_html($atts['title']); ?></h2>
        <?php endif; ?>
        
        <div class="product-brands-grid columns-<?php echo esc_attr($atts['columns']); ?>">
            <?php foreach ($brands as $brand) : ?>
                <div class="product-brand">
                    <a href="<?php echo esc_url(get_term_link($brand)); ?>" class="product-brand-link">
                        <?php
                        $thumbnail_id = get_term_meta($brand->term_id, 'thumbnail_id', true);
                        
                        if ($thumbnail_id) {
                            echo wp_get_attachment_image($thumbnail_id, 'thumbnail');
                        } else {
                            echo esc_html($brand->name);
                        }
                        ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * WooCommerce product deals shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_product_deals_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => __('Hot Deals', 'aqualuxe'),
        'limit' => 4,
        'columns' => 4,
        'orderby' => 'date',
        'order' => 'DESC',
    ], $atts);
    
    // Get products on sale
    $product_ids_on_sale = wc_get_product_ids_on_sale();
    
    if (empty($product_ids_on_sale)) {
        return '';
    }
    
    $query_args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => $atts['limit'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post__in' => $product_ids_on_sale,
        'meta_query' => [
            [
                'key' => '_sale_price_dates_to',
                'value' => time(),
                'compare' => '>',
                'type' => 'numeric',
            ],
        ],
    ];
    
    ob_start();
    
    $products = new WP_Query($query_args);
    
    if ($products->have_posts()) {
        ?>
        <div class="product-deals">
            <?php if ($atts['title']) : ?>
                <h2 class="product-deals-title"><?php echo esc_html($atts['title']); ?></h2>
            <?php endif; ?>
            
            <div class="product-deals-grid columns-<?php echo esc_attr($atts['columns']); ?>">
                <?php
                while ($products->have_posts()) {
                    $products->the_post();
                    wc_get_template_part('content', 'product');
                }
                ?>
            </div>
        </div>
        <?php
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}

/**
 * WooCommerce product comparison shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_woocommerce_product_comparison_shortcode($atts) {
    $atts = shortcode_atts([
        'ids' => '',
    ], $atts);
    
    if (!$atts['ids']) {
        return '';
    }
    
    $product_ids = explode(',', $atts['ids']);
    
    if (empty($product_ids)) {
        return '';
    }
    
    $products = [];
    
    foreach ($product_ids as $product_id) {
        $product = wc_get_product($product_id);
        
        if ($product) {
            $products[] = $product;
        }
    }
    
    if (count($products) < 2) {
        return '';
    }
    
    ob_start();
    
    ?>
    <div class="product-comparison">
        <h2 class="product-comparison-title"><?php esc_html_e('Product Comparison', 'aqualuxe'); ?></h2>
        
        <div class="product-comparison-table-container">
            <table class="product-comparison-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                        <?php foreach ($products as $product) : ?>
                            <th>
                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                    <?php echo $product->get_image(); ?>
                                    <span class="product-comparison-title"><?php echo esc_html($product->get_name()); ?></span>
                                </a>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                        <?php foreach ($products as $product) : ?>
                            <td><?php echo $product->get_price_html(); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Description', 'aqualuxe'); ?></th>
                        <?php foreach ($products as $product) : ?>
                            <td><?php echo wp_kses_post($product->get_short_description()); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Availability', 'aqualuxe'); ?></th>
                        <?php foreach ($products as $product) : ?>
                            <td><?php echo $product->is_in_stock() ? esc_html__('In Stock', 'aqualuxe') : esc_html__('Out of Stock', 'aqualuxe'); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Weight', 'aqualuxe'); ?></th>
                        <?php foreach ($products as $product) : ?>
                            <td>
                                <?php
                                $weight = $product->get_weight();
                                echo $weight ? esc_html($weight) . ' ' . get_option('woocommerce_weight_unit') : '&ndash;';
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Dimensions', 'aqualuxe'); ?></th>
                        <?php foreach ($products as $product) : ?>
                            <td>
                                <?php
                                $dimensions = $product->get_dimensions(false);
                                
                                if ($dimensions) {
                                    echo esc_html(implode(' × ', $dimensions)) . ' ' . get_option('woocommerce_dimension_unit');
                                } else {
                                    echo '&ndash;';
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Add to Cart', 'aqualuxe'); ?></th>
                        <?php foreach ($products as $product) : ?>
                            <td>
                                <?php
                                echo apply_filters(
                                    'woocommerce_loop_add_to_cart_link',
                                    sprintf(
                                        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                        esc_url($product->add_to_cart_url()),
                                        esc_attr(1),
                                        esc_attr('button'),
                                        'product_type="' . esc_attr($product->get_type()) . '"',
                                        esc_html($product->add_to_cart_text())
                                    ),
                                    $product
                                );
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}