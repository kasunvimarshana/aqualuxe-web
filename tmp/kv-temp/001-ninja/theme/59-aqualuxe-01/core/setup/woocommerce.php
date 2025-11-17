<?php
/**
 * AquaLuxe WooCommerce Setup
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * WooCommerce setup
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');

    // Remove default WooCommerce breadcrumbs
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

    // Remove default WooCommerce sidebar
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

    // Add custom breadcrumbs
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_breadcrumbs', 20);

    // Add custom sidebar
    add_action('woocommerce_sidebar', 'aqualuxe_woocommerce_sidebar', 10);

    // Modify product loop
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

    // Add custom product loop
    add_action('woocommerce_before_shop_loop_item', 'aqualuxe_template_loop_product_link_open', 10);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_template_loop_product_link_close', 5);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_template_loop_add_to_cart', 10);
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_show_product_loop_sale_flash', 10);
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_template_loop_product_thumbnail', 10);
    add_action('woocommerce_shop_loop_item_title', 'aqualuxe_template_loop_product_title', 10);
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_template_loop_rating', 5);
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_template_loop_price', 10);

    // Add quick view button
    add_action('aqualuxe_after_shop_loop_item', 'aqualuxe_quick_view_button', 10);

    // Add wishlist button
    add_action('aqualuxe_after_shop_loop_item', 'aqualuxe_wishlist_button', 20);

    // Add compare button
    add_action('aqualuxe_after_shop_loop_item', 'aqualuxe_compare_button', 30);

    // Modify single product
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

    // Add custom single product
    add_action('woocommerce_before_single_product_summary', 'aqualuxe_show_product_sale_flash', 10);
    add_action('woocommerce_single_product_summary', 'aqualuxe_template_single_title', 5);
    add_action('woocommerce_single_product_summary', 'aqualuxe_template_single_rating', 10);
    add_action('woocommerce_single_product_summary', 'aqualuxe_template_single_price', 10);
    add_action('woocommerce_single_product_summary', 'aqualuxe_template_single_excerpt', 20);
    add_action('woocommerce_single_product_summary', 'aqualuxe_template_single_add_to_cart', 30);
    add_action('woocommerce_single_product_summary', 'aqualuxe_template_single_meta', 40);
    add_action('woocommerce_single_product_summary', 'aqualuxe_template_single_sharing', 50);

    // Add wishlist button to single product
    add_action('woocommerce_after_add_to_cart_button', 'aqualuxe_single_wishlist_button', 10);

    // Add compare button to single product
    add_action('woocommerce_after_add_to_cart_button', 'aqualuxe_single_compare_button', 20);

    // Add social share buttons to single product
    add_action('woocommerce_share', 'aqualuxe_social_share_buttons', 10);

    // Add custom tabs to single product
    add_filter('woocommerce_product_tabs', 'aqualuxe_product_tabs', 10);

    // Add related products
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_output_related_products', 20);

    // Add upsells
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_upsell_display', 15);

    // Modify cart
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
    add_action('woocommerce_after_cart', 'aqualuxe_cross_sell_display');

    // Modify checkout
    add_filter('woocommerce_checkout_fields', 'aqualuxe_checkout_fields');

    // Modify account
    add_filter('woocommerce_account_menu_items', 'aqualuxe_account_menu_items');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce breadcrumbs
 */
function aqualuxe_woocommerce_breadcrumbs() {
    woocommerce_breadcrumb([
        'delimiter'   => '<span class="breadcrumb-separator">' . aqualuxe_get_svg_icon('chevron-right') . '</span>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb">',
        'wrap_after'  => '</nav>',
        'home'        => esc_html__('Home', 'aqualuxe'),
    ]);
}

/**
 * WooCommerce sidebar
 */
function aqualuxe_woocommerce_sidebar() {
    if (is_active_sidebar('shop-sidebar')) {
        ?>
        <div id="secondary" class="widget-area shop-sidebar" role="complementary">
            <?php dynamic_sidebar('shop-sidebar'); ?>
        </div>
        <?php
    }
}

/**
 * Product link open
 */
function aqualuxe_template_loop_product_link_open() {
    global $product;
    
    $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
    
    echo '<div class="product-inner">';
    echo '<div class="product-thumbnail">';
    echo '<a href="' . esc_url($link) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
}

/**
 * Product link close
 */
function aqualuxe_template_loop_product_link_close() {
    echo '</a>';
    echo '</div>'; // .product-thumbnail
    echo '<div class="product-content">';
}

/**
 * Product add to cart
 */
function aqualuxe_template_loop_add_to_cart() {
    woocommerce_template_loop_add_to_cart();
    
    do_action('aqualuxe_after_shop_loop_item');
    
    echo '</div>'; // .product-content
    echo '</div>'; // .product-inner
}

/**
 * Product sale flash
 */
function aqualuxe_show_product_loop_sale_flash() {
    woocommerce_show_product_loop_sale_flash();
}

/**
 * Product thumbnail
 */
function aqualuxe_template_loop_product_thumbnail() {
    global $product;
    
    $image_size = apply_filters('aqualuxe_product_thumbnail_size', 'woocommerce_thumbnail');
    
    echo woocommerce_get_product_thumbnail($image_size);
    
    // Second image on hover
    $attachment_ids = $product->get_gallery_image_ids();
    
    if (!empty($attachment_ids)) {
        $second_image_id = reset($attachment_ids);
        
        echo wp_get_attachment_image($second_image_id, $image_size, false, [
            'class' => 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail product-thumbnail-hover',
        ]);
    }
}

/**
 * Product title
 */
function aqualuxe_template_loop_product_title() {
    echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';
}

/**
 * Product rating
 */
function aqualuxe_template_loop_rating() {
    woocommerce_template_loop_rating();
}

/**
 * Product price
 */
function aqualuxe_template_loop_price() {
    woocommerce_template_loop_price();
}

/**
 * Quick view button
 */
function aqualuxe_quick_view_button() {
    global $product;
    
    if (aqualuxe_is_module_active('quick-view')) {
        echo '<button class="aqualuxe-quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">';
        aqualuxe_svg_icon('eye');
        echo '<span>' . esc_html__('Quick View', 'aqualuxe') . '</span>';
        echo '</button>';
    }
}

/**
 * Wishlist button
 */
function aqualuxe_wishlist_button() {
    global $product;
    
    if (aqualuxe_is_module_active('wishlist')) {
        $wishlist = aqualuxe_get_module('wishlist');
        $in_wishlist = $wishlist->is_product_in_wishlist($product->get_id());
        
        echo '<button class="aqualuxe-wishlist-button' . ($in_wishlist ? ' in-wishlist' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
        aqualuxe_svg_icon($in_wishlist ? 'heart-fill' : 'heart');
        echo '<span>' . esc_html($in_wishlist ? __('Remove from Wishlist', 'aqualuxe') : __('Add to Wishlist', 'aqualuxe')) . '</span>';
        echo '</button>';
    }
}

/**
 * Compare button
 */
function aqualuxe_compare_button() {
    global $product;
    
    if (aqualuxe_is_module_active('compare')) {
        $compare = aqualuxe_get_module('compare');
        $in_compare = $compare->is_product_in_compare($product->get_id());
        
        echo '<button class="aqualuxe-compare-button' . ($in_compare ? ' in-compare' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
        aqualuxe_svg_icon('refresh-cw');
        echo '<span>' . esc_html($in_compare ? __('Remove from Compare', 'aqualuxe') : __('Add to Compare', 'aqualuxe')) . '</span>';
        echo '</button>';
    }
}

/**
 * Product sale flash
 */
function aqualuxe_show_product_sale_flash() {
    woocommerce_show_product_sale_flash();
}

/**
 * Single product title
 */
function aqualuxe_template_single_title() {
    the_title('<h1 class="product_title entry-title">', '</h1>');
}

/**
 * Single product rating
 */
function aqualuxe_template_single_rating() {
    woocommerce_template_single_rating();
}

/**
 * Single product price
 */
function aqualuxe_template_single_price() {
    woocommerce_template_single_price();
}

/**
 * Single product excerpt
 */
function aqualuxe_template_single_excerpt() {
    woocommerce_template_single_excerpt();
}

/**
 * Single product add to cart
 */
function aqualuxe_template_single_add_to_cart() {
    woocommerce_template_single_add_to_cart();
}

/**
 * Single product meta
 */
function aqualuxe_template_single_meta() {
    woocommerce_template_single_meta();
}

/**
 * Single product sharing
 */
function aqualuxe_template_single_sharing() {
    woocommerce_template_single_sharing();
}

/**
 * Single product wishlist button
 */
function aqualuxe_single_wishlist_button() {
    global $product;
    
    if (aqualuxe_is_module_active('wishlist')) {
        $wishlist = aqualuxe_get_module('wishlist');
        $in_wishlist = $wishlist->is_product_in_wishlist($product->get_id());
        
        echo '<button class="aqualuxe-single-wishlist-button' . ($in_wishlist ? ' in-wishlist' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
        aqualuxe_svg_icon($in_wishlist ? 'heart-fill' : 'heart');
        echo '<span>' . esc_html($in_wishlist ? __('Remove from Wishlist', 'aqualuxe') : __('Add to Wishlist', 'aqualuxe')) . '</span>';
        echo '</button>';
    }
}

/**
 * Single product compare button
 */
function aqualuxe_single_compare_button() {
    global $product;
    
    if (aqualuxe_is_module_active('compare')) {
        $compare = aqualuxe_get_module('compare');
        $in_compare = $compare->is_product_in_compare($product->get_id());
        
        echo '<button class="aqualuxe-single-compare-button' . ($in_compare ? ' in-compare' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
        aqualuxe_svg_icon('refresh-cw');
        echo '<span>' . esc_html($in_compare ? __('Remove from Compare', 'aqualuxe') : __('Add to Compare', 'aqualuxe')) . '</span>';
        echo '</button>';
    }
}

/**
 * Social share buttons
 */
function aqualuxe_social_share_buttons() {
    global $product;
    
    $share_url = get_permalink();
    $share_title = get_the_title();
    $share_image = wp_get_attachment_url($product->get_image_id());
    
    echo '<div class="aqualuxe-social-share">';
    echo '<span class="aqualuxe-social-share-title">' . esc_html__('Share:', 'aqualuxe') . '</span>';
    echo '<div class="aqualuxe-social-share-buttons">';
    
    // Facebook
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url($share_url) . '" class="aqualuxe-social-share-button aqualuxe-social-share-facebook" target="_blank" rel="noopener noreferrer">';
    aqualuxe_svg_icon('facebook');
    echo '<span class="screen-reader-text">' . esc_html__('Share on Facebook', 'aqualuxe') . '</span>';
    echo '</a>';
    
    // Twitter
    echo '<a href="https://twitter.com/intent/tweet?url=' . esc_url($share_url) . '&text=' . esc_attr($share_title) . '" class="aqualuxe-social-share-button aqualuxe-social-share-twitter" target="_blank" rel="noopener noreferrer">';
    aqualuxe_svg_icon('twitter');
    echo '<span class="screen-reader-text">' . esc_html__('Share on Twitter', 'aqualuxe') . '</span>';
    echo '</a>';
    
    // Pinterest
    echo '<a href="https://pinterest.com/pin/create/button/?url=' . esc_url($share_url) . '&media=' . esc_url($share_image) . '&description=' . esc_attr($share_title) . '" class="aqualuxe-social-share-button aqualuxe-social-share-pinterest" target="_blank" rel="noopener noreferrer">';
    aqualuxe_svg_icon('pinterest');
    echo '<span class="screen-reader-text">' . esc_html__('Share on Pinterest', 'aqualuxe') . '</span>';
    echo '</a>';
    
    // LinkedIn
    echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . esc_url($share_url) . '&title=' . esc_attr($share_title) . '" class="aqualuxe-social-share-button aqualuxe-social-share-linkedin" target="_blank" rel="noopener noreferrer">';
    aqualuxe_svg_icon('linkedin');
    echo '<span class="screen-reader-text">' . esc_html__('Share on LinkedIn', 'aqualuxe') . '</span>';
    echo '</a>';
    
    // Email
    echo '<a href="mailto:?subject=' . esc_attr($share_title) . '&body=' . esc_url($share_url) . '" class="aqualuxe-social-share-button aqualuxe-social-share-email">';
    aqualuxe_svg_icon('mail');
    echo '<span class="screen-reader-text">' . esc_html__('Share via Email', 'aqualuxe') . '</span>';
    echo '</a>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Product tabs
 *
 * @param array $tabs Product tabs
 * @return array
 */
function aqualuxe_product_tabs($tabs) {
    global $product;
    
    // Add custom tabs
    if ($product->get_type() === 'simple' || $product->get_type() === 'variable') {
        // Add shipping tab
        $tabs['shipping'] = [
            'title'    => esc_html__('Shipping & Returns', 'aqualuxe'),
            'priority' => 30,
            'callback' => 'aqualuxe_shipping_tab_content',
        ];
        
        // Add size guide tab
        if (has_term(['clothing', 'accessories'], 'product_cat', $product->get_id())) {
            $tabs['size_guide'] = [
                'title'    => esc_html__('Size Guide', 'aqualuxe'),
                'priority' => 40,
                'callback' => 'aqualuxe_size_guide_tab_content',
            ];
        }
    }
    
    return $tabs;
}

/**
 * Shipping tab content
 */
function aqualuxe_shipping_tab_content() {
    // Get shipping content from theme mod
    $shipping_content = aqualuxe_get_theme_mod('product_shipping_content', '');
    
    if (empty($shipping_content)) {
        $shipping_content = '<h3>' . esc_html__('Shipping Information', 'aqualuxe') . '</h3>';
        $shipping_content .= '<p>' . esc_html__('We ship worldwide with premium carriers like DHL, FedEx, and UPS. All orders are processed within 24-48 hours.', 'aqualuxe') . '</p>';
        $shipping_content .= '<h4>' . esc_html__('Shipping Times', 'aqualuxe') . '</h4>';
        $shipping_content .= '<ul>';
        $shipping_content .= '<li>' . esc_html__('Domestic: 2-5 business days', 'aqualuxe') . '</li>';
        $shipping_content .= '<li>' . esc_html__('International: 7-14 business days', 'aqualuxe') . '</li>';
        $shipping_content .= '</ul>';
        $shipping_content .= '<h3>' . esc_html__('Returns & Exchanges', 'aqualuxe') . '</h3>';
        $shipping_content .= '<p>' . esc_html__('If you are not completely satisfied with your purchase, you may return it within 30 days for a full refund or exchange.', 'aqualuxe') . '</p>';
        $shipping_content .= '<p>' . esc_html__('Please note that all returned items must be in their original condition with tags attached.', 'aqualuxe') . '</p>';
    }
    
    echo wp_kses_post($shipping_content);
}

/**
 * Size guide tab content
 */
function aqualuxe_size_guide_tab_content() {
    // Get size guide content from theme mod
    $size_guide_content = aqualuxe_get_theme_mod('product_size_guide_content', '');
    
    if (empty($size_guide_content)) {
        $size_guide_content = '<h3>' . esc_html__('Size Guide', 'aqualuxe') . '</h3>';
        $size_guide_content .= '<p>' . esc_html__('Please refer to the size chart below to find your perfect fit.', 'aqualuxe') . '</p>';
        $size_guide_content .= '<table class="size-guide-table">';
        $size_guide_content .= '<thead>';
        $size_guide_content .= '<tr>';
        $size_guide_content .= '<th>' . esc_html__('Size', 'aqualuxe') . '</th>';
        $size_guide_content .= '<th>' . esc_html__('Chest (inches)', 'aqualuxe') . '</th>';
        $size_guide_content .= '<th>' . esc_html__('Waist (inches)', 'aqualuxe') . '</th>';
        $size_guide_content .= '<th>' . esc_html__('Hips (inches)', 'aqualuxe') . '</th>';
        $size_guide_content .= '</tr>';
        $size_guide_content .= '</thead>';
        $size_guide_content .= '<tbody>';
        $size_guide_content .= '<tr>';
        $size_guide_content .= '<td>S</td>';
        $size_guide_content .= '<td>34-36</td>';
        $size_guide_content .= '<td>28-30</td>';
        $size_guide_content .= '<td>34-36</td>';
        $size_guide_content .= '</tr>';
        $size_guide_content .= '<tr>';
        $size_guide_content .= '<td>M</td>';
        $size_guide_content .= '<td>38-40</td>';
        $size_guide_content .= '<td>32-34</td>';
        $size_guide_content .= '<td>38-40</td>';
        $size_guide_content .= '</tr>';
        $size_guide_content .= '<tr>';
        $size_guide_content .= '<td>L</td>';
        $size_guide_content .= '<td>42-44</td>';
        $size_guide_content .= '<td>36-38</td>';
        $size_guide_content .= '<td>42-44</td>';
        $size_guide_content .= '</tr>';
        $size_guide_content .= '<tr>';
        $size_guide_content .= '<td>XL</td>';
        $size_guide_content .= '<td>46-48</td>';
        $size_guide_content .= '<td>40-42</td>';
        $size_guide_content .= '<td>46-48</td>';
        $size_guide_content .= '</tr>';
        $size_guide_content .= '</tbody>';
        $size_guide_content .= '</table>';
        $size_guide_content .= '<p>' . esc_html__('If you are between sizes, we recommend sizing up for a more comfortable fit.', 'aqualuxe') . '</p>';
    }
    
    echo wp_kses_post($size_guide_content);
}

/**
 * Output related products
 */
function aqualuxe_output_related_products() {
    $args = [
        'posts_per_page' => 4,
        'columns'        => 4,
        'orderby'        => 'rand',
    ];
    
    woocommerce_related_products($args);
}

/**
 * Output upsells
 */
function aqualuxe_upsell_display() {
    woocommerce_upsell_display(4, 4);
}

/**
 * Output cross sells
 */
function aqualuxe_cross_sell_display() {
    woocommerce_cross_sell_display(4, 4);
}

/**
 * Checkout fields
 *
 * @param array $fields Checkout fields
 * @return array
 */
function aqualuxe_checkout_fields($fields) {
    // Add placeholder to fields
    foreach ($fields as $section => $section_fields) {
        foreach ($section_fields as $key => $field) {
            if (!isset($field['placeholder']) && isset($field['label'])) {
                $fields[$section][$key]['placeholder'] = $field['label'];
            }
        }
    }
    
    return $fields;
}

/**
 * Account menu items
 *
 * @param array $items Account menu items
 * @return array
 */
function aqualuxe_account_menu_items($items) {
    // Add custom menu items
    $items['wishlist'] = esc_html__('Wishlist', 'aqualuxe');
    
    // Reorder items
    $ordered_items = [
        'dashboard'       => $items['dashboard'],
        'orders'          => $items['orders'],
        'wishlist'        => $items['wishlist'],
        'downloads'       => $items['downloads'],
        'edit-address'    => $items['edit-address'],
        'edit-account'    => $items['edit-account'],
        'customer-logout' => $items['customer-logout'],
    ];
    
    return $ordered_items;
}

/**
 * Add endpoint for wishlist
 */
function aqualuxe_add_wishlist_endpoint() {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
}
add_action('init', 'aqualuxe_add_wishlist_endpoint');

/**
 * Wishlist content
 */
function aqualuxe_wishlist_content() {
    if (aqualuxe_is_module_active('wishlist')) {
        $wishlist = aqualuxe_get_module('wishlist');
        $wishlist->display_wishlist();
    }
}
add_action('woocommerce_account_wishlist_endpoint', 'aqualuxe_wishlist_content');

/**
 * Add to cart fragments
 *
 * @param array $fragments Fragments
 * @return array
 */
function aqualuxe_cart_fragments($fragments) {
    $fragments['.aqualuxe-cart-count'] = '<span class="aqualuxe-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_cart_fragments');

/**
 * Add to cart AJAX
 */
function aqualuxe_add_to_cart_ajax() {
    check_ajax_referer('aqualuxe-nonce', 'nonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $variation = isset($_POST['variation']) ? (array) $_POST['variation'] : [];
    
    if ($product_id) {
        $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
        
        if ($added) {
            $data = [
                'success'  => true,
                'message'  => esc_html__('Product added to cart.', 'aqualuxe'),
                'cart_url' => wc_get_cart_url(),
                'fragments' => apply_filters('woocommerce_add_to_cart_fragments', []),
            ];
        } else {
            $data = [
                'success' => false,
                'message' => esc_html__('Failed to add product to cart.', 'aqualuxe'),
            ];
        }
    } else {
        $data = [
            'success' => false,
            'message' => esc_html__('Invalid product ID.', 'aqualuxe'),
        ];
    }
    
    wp_send_json($data);
}
add_action('wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_add_to_cart_ajax');
add_action('wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_add_to_cart_ajax');

/**
 * Quick view AJAX
 */
function aqualuxe_quick_view_ajax() {
    check_ajax_referer('aqualuxe-nonce', 'nonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if ($product_id) {
        $product = wc_get_product($product_id);
        
        if ($product) {
            ob_start();
            include AQUALUXE_TEMPLATES_DIR . 'woocommerce/quick-view.php';
            $html = ob_get_clean();
            
            $data = [
                'success' => true,
                'html'    => $html,
            ];
        } else {
            $data = [
                'success' => false,
                'message' => esc_html__('Product not found.', 'aqualuxe'),
            ];
        }
    } else {
        $data = [
            'success' => false,
            'message' => esc_html__('Invalid product ID.', 'aqualuxe'),
        ];
    }
    
    wp_send_json($data);
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax');

/**
 * Wishlist AJAX
 */
function aqualuxe_wishlist_ajax() {
    check_ajax_referer('aqualuxe-nonce', 'nonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if ($product_id && aqualuxe_is_module_active('wishlist')) {
        $wishlist = aqualuxe_get_module('wishlist');
        $result = $wishlist->toggle_product($product_id);
        
        if ($result) {
            $data = [
                'success'     => true,
                'message'     => $result['added'] ? esc_html__('Product added to wishlist.', 'aqualuxe') : esc_html__('Product removed from wishlist.', 'aqualuxe'),
                'in_wishlist' => $result['added'],
            ];
        } else {
            $data = [
                'success' => false,
                'message' => esc_html__('Failed to update wishlist.', 'aqualuxe'),
            ];
        }
    } else {
        $data = [
            'success' => false,
            'message' => esc_html__('Invalid product ID or wishlist module not active.', 'aqualuxe'),
        ];
    }
    
    wp_send_json($data);
}
add_action('wp_ajax_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax');
add_action('wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax');

/**
 * Compare AJAX
 */
function aqualuxe_compare_ajax() {
    check_ajax_referer('aqualuxe-nonce', 'nonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if ($product_id && aqualuxe_is_module_active('compare')) {
        $compare = aqualuxe_get_module('compare');
        $result = $compare->toggle_product($product_id);
        
        if ($result) {
            $data = [
                'success'    => true,
                'message'    => $result['added'] ? esc_html__('Product added to compare.', 'aqualuxe') : esc_html__('Product removed from compare.', 'aqualuxe'),
                'in_compare' => $result['added'],
            ];
        } else {
            $data = [
                'success' => false,
                'message' => esc_html__('Failed to update compare.', 'aqualuxe'),
            ];
        }
    } else {
        $data = [
            'success' => false,
            'message' => esc_html__('Invalid product ID or compare module not active.', 'aqualuxe'),
        ];
    }
    
    wp_send_json($data);
}
add_action('wp_ajax_aqualuxe_compare', 'aqualuxe_compare_ajax');
add_action('wp_ajax_nopriv_aqualuxe_compare', 'aqualuxe_compare_ajax');

/**
 * Filter products AJAX
 */
function aqualuxe_filter_products_ajax() {
    check_ajax_referer('aqualuxe-nonce', 'nonce');
    
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $price_min = isset($_POST['price_min']) ? floatval($_POST['price_min']) : 0;
    $price_max = isset($_POST['price_max']) ? floatval($_POST['price_max']) : 0;
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'menu_order';
    $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
    
    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => get_option('woocommerce_catalog_columns', 4) * get_option('woocommerce_catalog_rows', 4),
        'paged'          => $paged,
    ];
    
    // Category filter
    if (!empty($category)) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => explode(',', $category),
        ];
    }
    
    // Tag filter
    if (!empty($tag)) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_tag',
            'field'    => 'slug',
            'terms'    => explode(',', $tag),
        ];
    }
    
    // Price filter
    if ($price_min > 0 || $price_max > 0) {
        $args['meta_query'][] = [
            'key'     => '_price',
            'value'   => [$price_min, $price_max],
            'compare' => 'BETWEEN',
            'type'    => 'NUMERIC',
        ];
    }
    
    // Order by
    switch ($orderby) {
        case 'price':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;
        case 'price-desc':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'date':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'popularity':
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'rating':
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        default:
            $args['orderby'] = 'menu_order title';
            $args['order'] = 'ASC';
            break;
    }
    
    $products = new WP_Query($args);
    
    ob_start();
    
    if ($products->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        
        woocommerce_pagination();
    } else {
        echo '<p class="woocommerce-info">' . esc_html__('No products found.', 'aqualuxe') . '</p>';
    }
    
    $html = ob_get_clean();
    
    wp_reset_postdata();
    
    $data = [
        'success'     => true,
        'html'        => $html,
        'found_posts' => $products->found_posts,
        'max_pages'   => $products->max_num_pages,
    ];
    
    wp_send_json($data);
}
add_action('wp_ajax_aqualuxe_filter_products', 'aqualuxe_filter_products_ajax');
add_action('wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_filter_products_ajax');

/**
 * Load more products AJAX
 */
function aqualuxe_load_more_products_ajax() {
    check_ajax_referer('aqualuxe-nonce', 'nonce');
    
    $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 2;
    
    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => get_option('woocommerce_catalog_columns', 4) * get_option('woocommerce_catalog_rows', 4),
        'paged'          => $paged,
    ];
    
    $products = new WP_Query($args);
    
    ob_start();
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
    }
    
    $html = ob_get_clean();
    
    wp_reset_postdata();
    
    $data = [
        'success'     => true,
        'html'        => $html,
        'found_posts' => $products->found_posts,
        'max_pages'   => $products->max_num_pages,
    ];
    
    wp_send_json($data);
}
add_action('wp_ajax_aqualuxe_load_more_products', 'aqualuxe_load_more_products_ajax');
add_action('wp_ajax_nopriv_aqualuxe_load_more_products', 'aqualuxe_load_more_products_ajax');

/**
 * Add currency switcher
 */
function aqualuxe_currency_switcher() {
    if (aqualuxe_is_module_active('multicurrency')) {
        $multicurrency = aqualuxe_get_module('multicurrency');
        $multicurrency->display_currency_switcher();
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_currency_switcher', 20);
add_action('woocommerce_before_single_product', 'aqualuxe_currency_switcher', 20);
add_action('woocommerce_before_cart', 'aqualuxe_currency_switcher', 20);
add_action('woocommerce_before_checkout_form', 'aqualuxe_currency_switcher', 20);

/**
 * Add product filters
 */
function aqualuxe_product_filters() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        aqualuxe_get_template_part('woocommerce/product-filters');
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_filters', 10);

/**
 * Add product sorting
 */
function aqualuxe_product_sorting() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        aqualuxe_get_template_part('woocommerce/product-sorting');
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_sorting', 15);

/**
 * Add product view switcher
 */
function aqualuxe_product_view_switcher() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        aqualuxe_get_template_part('woocommerce/product-view-switcher');
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_view_switcher', 25);

/**
 * Add product results count
 */
function aqualuxe_product_results_count() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        woocommerce_result_count();
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_results_count', 30);

/**
 * Add product catalog ordering
 */
function aqualuxe_product_catalog_ordering() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        woocommerce_catalog_ordering();
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_catalog_ordering', 35);

/**
 * Add product category description
 */
function aqualuxe_product_category_description() {
    if (is_product_category()) {
        $category = get_queried_object();
        $description = term_description($category->term_id, 'product_cat');
        
        if ($description) {
            echo '<div class="aqualuxe-category-description">' . wp_kses_post($description) . '</div>';
        }
    }
}
add_action('woocommerce_archive_description', 'aqualuxe_product_category_description', 10);

/**
 * Add product tag description
 */
function aqualuxe_product_tag_description() {
    if (is_product_tag()) {
        $tag = get_queried_object();
        $description = term_description($tag->term_id, 'product_tag');
        
        if ($description) {
            echo '<div class="aqualuxe-tag-description">' . wp_kses_post($description) . '</div>';
        }
    }
}
add_action('woocommerce_archive_description', 'aqualuxe_product_tag_description', 10);

/**
 * Add product category image
 */
function aqualuxe_product_category_image() {
    if (is_product_category()) {
        $category = get_queried_object();
        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
        
        if ($thumbnail_id) {
            echo '<div class="aqualuxe-category-image">';
            echo wp_get_attachment_image($thumbnail_id, 'full');
            echo '</div>';
        }
    }
}
add_action('woocommerce_archive_description', 'aqualuxe_product_category_image', 5);

/**
 * Add product subcategories
 */
function aqualuxe_product_subcategories() {
    if (is_shop() || is_product_category()) {
        $parent_id = 0;
        
        if (is_product_category()) {
            $category = get_queried_object();
            $parent_id = $category->term_id;
        }
        
        $subcategories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => $parent_id,
        ]);
        
        if (!empty($subcategories) && !is_wp_error($subcategories)) {
            echo '<div class="aqualuxe-product-subcategories">';
            
            foreach ($subcategories as $subcategory) {
                $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                $image = '';
                
                if ($thumbnail_id) {
                    $image = wp_get_attachment_image($thumbnail_id, 'thumbnail');
                }
                
                echo '<div class="aqualuxe-product-subcategory">';
                echo '<a href="' . esc_url(get_term_link($subcategory)) . '">';
                
                if ($image) {
                    echo '<div class="aqualuxe-product-subcategory-image">' . $image . '</div>';
                }
                
                echo '<h3 class="aqualuxe-product-subcategory-title">' . esc_html($subcategory->name) . '</h3>';
                echo '</a>';
                echo '</div>';
            }
            
            echo '</div>';
        }
    }
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_subcategories', 5);

/**
 * Add product brands
 */
function aqualuxe_product_brands() {
    if (taxonomy_exists('product_brand')) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $brands = get_terms([
                'taxonomy'   => 'product_brand',
                'hide_empty' => true,
            ]);
            
            if (!empty($brands) && !is_wp_error($brands)) {
                echo '<div class="aqualuxe-product-brands">';
                echo '<h3 class="aqualuxe-product-brands-title">' . esc_html__('Brands', 'aqualuxe') . '</h3>';
                echo '<div class="aqualuxe-product-brands-list">';
                
                foreach ($brands as $brand) {
                    $thumbnail_id = get_term_meta($brand->term_id, 'thumbnail_id', true);
                    $image = '';
                    
                    if ($thumbnail_id) {
                        $image = wp_get_attachment_image($thumbnail_id, 'thumbnail');
                    }
                    
                    echo '<div class="aqualuxe-product-brand">';
                    echo '<a href="' . esc_url(get_term_link($brand)) . '">';
                    
                    if ($image) {
                        echo '<div class="aqualuxe-product-brand-image">' . $image . '</div>';
                    } else {
                        echo '<h4 class="aqualuxe-product-brand-title">' . esc_html($brand->name) . '</h4>';
                    }
                    
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</div>';
            }
        }
    }
}
add_action('woocommerce_sidebar', 'aqualuxe_product_brands', 5);