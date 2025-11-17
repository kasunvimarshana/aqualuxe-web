<?php
/**
 * WooCommerce hooks
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Remove default WooCommerce styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Remove default WooCommerce hooks
 */
function aqualuxe_remove_woocommerce_hooks() {
    // Remove breadcrumb (we'll add our own)
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

    // Remove sidebar (we'll add our own)
    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

    // Remove default wrapper (we'll add our own)
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

    // Remove result count and catalog ordering
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

    // Reposition result count and catalog ordering
    add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 10 );
    add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 20 );

    // Remove product link open/close (we'll add our own)
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

    // Remove add to cart button (we'll add our own)
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

    // Reposition add to cart button
    add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 15 );

    // Remove sale flash (we'll add our own)
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

    // Add sale flash
    add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_show_product_loop_sale_flash', 10 );
    add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_show_product_sale_flash', 10 );

    // Remove product thumbnail (we'll add our own)
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

    // Add product thumbnail
    add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_thumbnail', 10 );

    // Remove product title (we'll add our own)
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

    // Add product title
    add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_title', 10 );

    // Remove product price (we'll add our own)
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

    // Add product price
    add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_price', 10 );

    // Remove product rating (we'll add our own)
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

    // Add product rating
    add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_rating', 5 );

    // Add product categories
    add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_categories', 5 );

    // Add product short description
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_short_description', 5 );

    // Add quick view button
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_quick_view_button', 20 );

    // Add wishlist button
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_wishlist_button', 25 );

    // Add compare button
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_compare_button', 30 );

    // Add product link wrapper
    add_action( 'woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_link_open', 10 );
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_link_close', 35 );

    // Add product wrapper
    add_action( 'woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_wrapper_open', 5 );
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_wrapper_close', 40 );

    // Add product image wrapper
    add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_image_wrapper_open', 5 );
    add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_image_wrapper_close', 15 );

    // Add product content wrapper
    add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_content_wrapper_open', 1 );
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_content_wrapper_close', 45 );

    // Add product actions wrapper
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_actions_wrapper_open', 14 );
    add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_template_loop_product_actions_wrapper_close', 31 );

    // Single product hooks
    // Remove product images (we'll add our own)
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

    // Add product images
    add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_show_product_images', 20 );

    // Remove product title (we'll add our own)
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

    // Add product title
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_title', 5 );

    // Remove product rating (we'll add our own)
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

    // Add product rating
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_rating', 10 );

    // Remove product price (we'll add our own)
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

    // Add product price
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_price', 15 );

    // Remove product excerpt (we'll add our own)
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

    // Add product excerpt
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_excerpt', 20 );

    // Remove add to cart (we'll add our own)
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

    // Add add to cart
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_add_to_cart', 30 );

    // Remove meta (we'll add our own)
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

    // Add meta
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_meta', 40 );

    // Remove sharing (we'll add our own)
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

    // Add sharing
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_sharing', 50 );

    // Add wishlist button
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_wishlist_button', 35 );

    // Add compare button
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_compare_button', 36 );

    // Add product categories
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_categories', 4 );

    // Add product tags
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_tags', 45 );

    // Add product additional information
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_additional_information', 55 );

    // Add product stock status
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_stock_status', 25 );

    // Add product dimensions
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_dimensions', 26 );

    // Add product weight
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_weight', 27 );

    // Add product shipping class
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_shipping_class', 28 );

    // Add product SKU
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_sku', 29 );

    // Add product reviews
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_reviews', 60 );

    // Add product related products
    add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_template_single_related_products', 20 );

    // Add product upsells
    add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_template_single_upsells', 15 );

    // Add product cross-sells
    add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_template_single_cross_sells', 25 );

    // Add product recently viewed
    add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_template_single_recently_viewed', 30 );

    // Add product tabs
    add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs', 98 );

    // Cart hooks
    // Remove cart cross-sells (we'll add our own)
    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

    // Add cart cross-sells
    add_action( 'woocommerce_after_cart', 'aqualuxe_woocommerce_cart_cross_sells', 10 );

    // Add cart totals wrapper
    add_action( 'woocommerce_before_cart_totals', 'aqualuxe_woocommerce_cart_totals_wrapper_open', 1 );
    add_action( 'woocommerce_after_cart_totals', 'aqualuxe_woocommerce_cart_totals_wrapper_close', 100 );

    // Add cart table wrapper
    add_action( 'woocommerce_before_cart_table', 'aqualuxe_woocommerce_cart_table_wrapper_open', 1 );
    add_action( 'woocommerce_after_cart_table', 'aqualuxe_woocommerce_cart_table_wrapper_close', 100 );

    // Add cart actions wrapper
    add_action( 'woocommerce_cart_actions', 'aqualuxe_woocommerce_cart_actions_wrapper_open', 1 );
    add_action( 'woocommerce_cart_actions', 'aqualuxe_woocommerce_cart_actions_wrapper_close', 100 );

    // Add cart coupon wrapper
    add_action( 'woocommerce_before_cart_table', 'aqualuxe_woocommerce_cart_coupon_wrapper_open', 5 );
    add_action( 'woocommerce_before_cart_table', 'aqualuxe_woocommerce_cart_coupon_wrapper_close', 15 );

    // Checkout hooks
    // Add checkout wrapper
    add_action( 'woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_wrapper_open', 1 );
    add_action( 'woocommerce_after_checkout_form', 'aqualuxe_woocommerce_checkout_wrapper_close', 100 );

    // Add checkout customer details wrapper
    add_action( 'woocommerce_checkout_before_customer_details', 'aqualuxe_woocommerce_checkout_customer_details_wrapper_open', 1 );
    add_action( 'woocommerce_checkout_after_customer_details', 'aqualuxe_woocommerce_checkout_customer_details_wrapper_close', 100 );

    // Add checkout order review wrapper
    add_action( 'woocommerce_checkout_before_order_review_heading', 'aqualuxe_woocommerce_checkout_order_review_wrapper_open', 1 );
    add_action( 'woocommerce_checkout_after_order_review', 'aqualuxe_woocommerce_checkout_order_review_wrapper_close', 100 );

    // Account hooks
    // Add account wrapper
    add_action( 'woocommerce_before_account_navigation', 'aqualuxe_woocommerce_account_wrapper_open', 1 );
    add_action( 'woocommerce_after_account_navigation', 'aqualuxe_woocommerce_account_wrapper_close', 100 );

    // Add account content wrapper
    add_action( 'woocommerce_account_content', 'aqualuxe_woocommerce_account_content_wrapper_open', 1 );
    add_action( 'woocommerce_account_content', 'aqualuxe_woocommerce_account_content_wrapper_close', 100 );

    // Add account navigation wrapper
    add_action( 'woocommerce_before_account_navigation', 'aqualuxe_woocommerce_account_navigation_wrapper_open', 5 );
    add_action( 'woocommerce_after_account_navigation', 'aqualuxe_woocommerce_account_navigation_wrapper_close', 95 );

    // Add account dashboard wrapper
    add_action( 'woocommerce_account_dashboard', 'aqualuxe_woocommerce_account_dashboard_wrapper_open', 1 );
    add_action( 'woocommerce_account_dashboard', 'aqualuxe_woocommerce_account_dashboard_wrapper_close', 100 );

    // Add account orders wrapper
    add_action( 'woocommerce_before_account_orders', 'aqualuxe_woocommerce_account_orders_wrapper_open', 1 );
    add_action( 'woocommerce_after_account_orders', 'aqualuxe_woocommerce_account_orders_wrapper_close', 100 );

    // Add account downloads wrapper
    add_action( 'woocommerce_before_account_downloads', 'aqualuxe_woocommerce_account_downloads_wrapper_open', 1 );
    add_action( 'woocommerce_after_account_downloads', 'aqualuxe_woocommerce_account_downloads_wrapper_close', 100 );

    // Add account addresses wrapper
    add_action( 'woocommerce_before_edit_account_address_form', 'aqualuxe_woocommerce_account_addresses_wrapper_open', 1 );
    add_action( 'woocommerce_after_edit_account_address_form', 'aqualuxe_woocommerce_account_addresses_wrapper_close', 100 );

    // Add account details wrapper
    add_action( 'woocommerce_before_edit_account_form', 'aqualuxe_woocommerce_account_details_wrapper_open', 1 );
    add_action( 'woocommerce_after_edit_account_form', 'aqualuxe_woocommerce_account_details_wrapper_close', 100 );

    // Add account payment methods wrapper
    add_action( 'woocommerce_before_account_payment_methods', 'aqualuxe_woocommerce_account_payment_methods_wrapper_open', 1 );
    add_action( 'woocommerce_after_account_payment_methods', 'aqualuxe_woocommerce_account_payment_methods_wrapper_close', 100 );
}
add_action( 'init', 'aqualuxe_remove_woocommerce_hooks' );

/**
 * Show sale flash
 */
function aqualuxe_woocommerce_show_product_loop_sale_flash() {
    global $product;

    if ( ! $product ) {
        return;
    }

    if ( ! $product->is_on_sale() ) {
        return;
    }

    echo '<span class="onsale">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
}

/**
 * Show sale flash on single product page
 */
function aqualuxe_woocommerce_show_product_sale_flash() {
    global $product;

    if ( ! $product ) {
        return;
    }

    if ( ! $product->is_on_sale() ) {
        return;
    }

    echo '<span class="onsale">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
}

/**
 * Product thumbnail
 */
function aqualuxe_woocommerce_template_loop_product_thumbnail() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo '<div class="product-thumbnail">';
    echo woocommerce_get_product_thumbnail();

    // Second image on hover
    $attachment_ids = $product->get_gallery_image_ids();

    if ( ! empty( $attachment_ids ) ) {
        echo wp_get_attachment_image( $attachment_ids[0], 'woocommerce_thumbnail', false, array( 'class' => 'product-thumbnail-hover' ) );
    }

    echo '</div>';
}

/**
 * Product title
 */
function aqualuxe_woocommerce_template_loop_product_title() {
    echo '<h2 class="woocommerce-loop-product__title"><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></h2>';
}

/**
 * Product price
 */
function aqualuxe_woocommerce_template_loop_price() {
    woocommerce_template_loop_price();
}

/**
 * Product rating
 */
function aqualuxe_woocommerce_template_loop_rating() {
    woocommerce_template_loop_rating();
}

/**
 * Product categories
 */
function aqualuxe_woocommerce_template_loop_product_categories() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="product-categories">', '</span>' );
}

/**
 * Product short description
 */
function aqualuxe_woocommerce_template_loop_product_short_description() {
    global $product;

    if ( ! $product ) {
        return;
    }

    if ( ! $product->get_short_description() ) {
        return;
    }

    echo '<div class="product-short-description">' . wp_kses_post( $product->get_short_description() ) . '</div>';
}

/**
 * Quick view button
 */
function aqualuxe_woocommerce_template_loop_quick_view_button() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo '<a href="#" class="button quick-view-button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
}

/**
 * Wishlist button
 */
function aqualuxe_woocommerce_template_loop_wishlist_button() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo '<a href="#" class="button wishlist-button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Wishlist', 'aqualuxe' ) . '</a>';
}

/**
 * Compare button
 */
function aqualuxe_woocommerce_template_loop_compare_button() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo '<a href="#" class="button compare-button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Compare', 'aqualuxe' ) . '</a>';
}

/**
 * Product link open
 */
function aqualuxe_woocommerce_template_loop_product_link_open() {
    echo '<div class="product-link">';
}

/**
 * Product link close
 */
function aqualuxe_woocommerce_template_loop_product_link_close() {
    echo '</div>';
}

/**
 * Product wrapper open
 */
function aqualuxe_woocommerce_template_loop_product_wrapper_open() {
    echo '<div class="product-wrapper">';
}

/**
 * Product wrapper close
 */
function aqualuxe_woocommerce_template_loop_product_wrapper_close() {
    echo '</div>';
}

/**
 * Product image wrapper open
 */
function aqualuxe_woocommerce_template_loop_product_image_wrapper_open() {
    echo '<div class="product-image-wrapper">';
}

/**
 * Product image wrapper close
 */
function aqualuxe_woocommerce_template_loop_product_image_wrapper_close() {
    echo '</div>';
}

/**
 * Product content wrapper open
 */
function aqualuxe_woocommerce_template_loop_product_content_wrapper_open() {
    echo '<div class="product-content-wrapper">';
}

/**
 * Product content wrapper close
 */
function aqualuxe_woocommerce_template_loop_product_content_wrapper_close() {
    echo '</div>';
}

/**
 * Product actions wrapper open
 */
function aqualuxe_woocommerce_template_loop_product_actions_wrapper_open() {
    echo '<div class="product-actions-wrapper">';
}

/**
 * Product actions wrapper close
 */
function aqualuxe_woocommerce_template_loop_product_actions_wrapper_close() {
    echo '</div>';
}

/**
 * Show product images
 */
function aqualuxe_woocommerce_show_product_images() {
    woocommerce_show_product_images();
}

/**
 * Single product title
 */
function aqualuxe_woocommerce_template_single_title() {
    echo '<h1 class="product_title entry-title">' . get_the_title() . '</h1>';
}

/**
 * Single product rating
 */
function aqualuxe_woocommerce_template_single_rating() {
    woocommerce_template_single_rating();
}

/**
 * Single product price
 */
function aqualuxe_woocommerce_template_single_price() {
    woocommerce_template_single_price();
}

/**
 * Single product excerpt
 */
function aqualuxe_woocommerce_template_single_excerpt() {
    woocommerce_template_single_excerpt();
}

/**
 * Single product add to cart
 */
function aqualuxe_woocommerce_template_single_add_to_cart() {
    woocommerce_template_single_add_to_cart();
}

/**
 * Single product meta
 */
function aqualuxe_woocommerce_template_single_meta() {
    woocommerce_template_single_meta();
}

/**
 * Single product sharing
 */
function aqualuxe_woocommerce_template_single_sharing() {
    woocommerce_template_single_sharing();
}

/**
 * Single product wishlist button
 */
function aqualuxe_woocommerce_template_single_wishlist_button() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo '<div class="product-wishlist">';
    echo '<a href="#" class="button wishlist-button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Add to Wishlist', 'aqualuxe' ) . '</a>';
    echo '</div>';
}

/**
 * Single product compare button
 */
function aqualuxe_woocommerce_template_single_compare_button() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo '<div class="product-compare">';
    echo '<a href="#" class="button compare-button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Add to Compare', 'aqualuxe' ) . '</a>';
    echo '</div>';
}

/**
 * Single product categories
 */
function aqualuxe_woocommerce_template_single_categories() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo '<div class="product-categories">';
    echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="product-category-label">' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span> <span class="product-categories-list">', '</span>' );
    echo '</div>';
}

/**
 * Single product tags
 */
function aqualuxe_woocommerce_template_single_tags() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo '<div class="product-tags">';
    echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="product-tag-label">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span> <span class="product-tags-list">', '</span>' );
    echo '</div>';
}

/**
 * Single product additional information
 */
function aqualuxe_woocommerce_template_single_additional_information() {
    global $product;

    if ( ! $product ) {
        return;
    }

    $attributes = $product->get_attributes();

    if ( empty( $attributes ) ) {
        return;
    }

    echo '<div class="product-additional-information">';
    echo '<h3>' . esc_html__( 'Additional Information', 'aqualuxe' ) . '</h3>';
    echo '<table class="shop_attributes">';

    foreach ( $attributes as $attribute ) {
        if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
            continue;
        }

        echo '<tr>';
        echo '<th>' . wc_attribute_label( $attribute['name'] ) . '</th>';
        echo '<td>';

        if ( $attribute['is_taxonomy'] ) {
            $values = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'names' ) );
            echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
        } else {
            $values = $attribute['value'];
            echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( $values ) ), $attribute, $values );
        }

        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';
    echo '</div>';
}

/**
 * Single product stock status
 */
function aqualuxe_woocommerce_template_single_stock_status() {
    global $product;

    if ( ! $product ) {
        return;
    }

    echo '<div class="product-stock-status">';
    echo '<span class="stock-status-label">' . esc_html__( 'Availability:', 'aqualuxe' ) . '</span> ';

    if ( $product->is_in_stock() ) {
        echo '<span class="stock-status in-stock">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</span>';
    } else {
        echo '<span class="stock-status out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
    }

    echo '</div>';
}

/**
 * Single product dimensions
 */
function aqualuxe_woocommerce_template_single_dimensions() {
    global $product;

    if ( ! $product ) {
        return;
    }

    if ( ! $product->has_dimensions() ) {
        return;
    }

    echo '<div class="product-dimensions">';
    echo '<span class="dimensions-label">' . esc_html__( 'Dimensions:', 'aqualuxe' ) . '</span> ';
    echo '<span class="dimensions">' . esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) ) . '</span>';
    echo '</div>';
}

/**
 * Single product weight
 */
function aqualuxe_woocommerce_template_single_weight() {
    global $product;

    if ( ! $product ) {
        return;
    }

    if ( ! $product->has_weight() ) {
        return;
    }

    echo '<div class="product-weight">';
    echo '<span class="weight-label">' . esc_html__( 'Weight:', 'aqualuxe' ) . '</span> ';
    echo '<span class="weight">' . esc_html( wc_format_weight( $product->get_weight() ) ) . '</span>';
    echo '</div>';
}

/**
 * Single product shipping class
 */
function aqualuxe_woocommerce_template_single_shipping_class() {
    global $product;

    if ( ! $product ) {
        return;
    }

    $shipping_class_id = $product->get_shipping_class_id();

    if ( ! $shipping_class_id ) {
        return;
    }

    $shipping_class = get_term( $shipping_class_id, 'product_shipping_class' );

    if ( ! $shipping_class ) {
        return;
    }

    echo '<div class="product-shipping-class">';
    echo '<span class="shipping-class-label">' . esc_html__( 'Shipping Class:', 'aqualuxe' ) . '</span> ';
    echo '<span class="shipping-class">' . esc_html( $shipping_class->name ) . '</span>';
    echo '</div>';
}

/**
 * Single product SKU
 */
function aqualuxe_woocommerce_template_single_sku() {
    global $product;

    if ( ! $product ) {
        return;
    }

    if ( ! $product->get_sku() ) {
        return;
    }

    echo '<div class="product-sku">';
    echo '<span class="sku-label">' . esc_html__( 'SKU:', 'aqualuxe' ) . '</span> ';
    echo '<span class="sku">' . esc_html( $product->get_sku() ) . '</span>';
    echo '</div>';
}

/**
 * Single product reviews
 */
function aqualuxe_woocommerce_template_single_reviews() {
    global $product;

    if ( ! $product ) {
        return;
    }

    if ( ! comments_open() ) {
        return;
    }

    echo '<div class="product-reviews">';
    echo '<a href="#reviews" class="reviews-link">' . esc_html__( 'Reviews', 'aqualuxe' ) . '</a>';
    echo '</div>';
}

/**
 * Single product related products
 */
function aqualuxe_woocommerce_template_single_related_products() {
    woocommerce_output_related_products();
}

/**
 * Single product upsells
 */
function aqualuxe_woocommerce_template_single_upsells() {
    woocommerce_upsell_display();
}

/**
 * Single product cross-sells
 */
function aqualuxe_woocommerce_template_single_cross_sells() {
    // Cross-sells are displayed on the cart page
}

/**
 * Single product recently viewed
 */
function aqualuxe_woocommerce_template_single_recently_viewed() {
    // Recently viewed products
    if ( ! function_exists( 'wc_track_product_view' ) ) {
        return;
    }

    wc_track_product_view();

    $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
    $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

    if ( empty( $viewed_products ) ) {
        return;
    }

    $current_product_id = get_the_ID();
    $viewed_products = array_diff( $viewed_products, array( $current_product_id ) );

    if ( empty( $viewed_products ) ) {
        return;
    }

    $args = array(
        'posts_per_page' => 4,
        'no_found_rows'  => 1,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'post__in'       => $viewed_products,
        'orderby'        => 'post__in',
    );

    $products = new WP_Query( $args );

    if ( ! $products->have_posts() ) {
        return;
    }

    echo '<section class="related products recently-viewed-products">';
    echo '<h2>' . esc_html__( 'Recently Viewed Products', 'aqualuxe' ) . '</h2>';
    echo '<ul class="products columns-4">';

    while ( $products->have_posts() ) {
        $products->the_post();
        wc_get_template_part( 'content', 'product' );
    }

    echo '</ul>';
    echo '</section>';

    wp_reset_postdata();
}

/**
 * Product tabs
 *
 * @param array $tabs Product tabs
 * @return array Modified product tabs
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
    // Add custom tabs here
    return $tabs;
}

/**
 * Cart cross-sells
 */
function aqualuxe_woocommerce_cart_cross_sells() {
    woocommerce_cross_sell_display();
}

/**
 * Cart totals wrapper open
 */
function aqualuxe_woocommerce_cart_totals_wrapper_open() {
    echo '<div class="cart-totals-wrapper">';
}

/**
 * Cart totals wrapper close
 */
function aqualuxe_woocommerce_cart_totals_wrapper_close() {
    echo '</div>';
}

/**
 * Cart table wrapper open
 */
function aqualuxe_woocommerce_cart_table_wrapper_open() {
    echo '<div class="cart-table-wrapper">';
}

/**
 * Cart table wrapper close
 */
function aqualuxe_woocommerce_cart_table_wrapper_close() {
    echo '</div>';
}

/**
 * Cart actions wrapper open
 */
function aqualuxe_woocommerce_cart_actions_wrapper_open() {
    echo '<div class="cart-actions-wrapper">';
}

/**
 * Cart actions wrapper close
 */
function aqualuxe_woocommerce_cart_actions_wrapper_close() {
    echo '</div>';
}

/**
 * Cart coupon wrapper open
 */
function aqualuxe_woocommerce_cart_coupon_wrapper_open() {
    echo '<div class="cart-coupon-wrapper">';
}

/**
 * Cart coupon wrapper close
 */
function aqualuxe_woocommerce_cart_coupon_wrapper_close() {
    echo '</div>';
}

/**
 * Checkout wrapper open
 */
function aqualuxe_woocommerce_checkout_wrapper_open() {
    echo '<div class="checkout-wrapper">';
}

/**
 * Checkout wrapper close
 */
function aqualuxe_woocommerce_checkout_wrapper_close() {
    echo '</div>';
}

/**
 * Checkout customer details wrapper open
 */
function aqualuxe_woocommerce_checkout_customer_details_wrapper_open() {
    echo '<div class="checkout-customer-details-wrapper">';
}

/**
 * Checkout customer details wrapper close
 */
function aqualuxe_woocommerce_checkout_customer_details_wrapper_close() {
    echo '</div>';
}

/**
 * Checkout order review wrapper open
 */
function aqualuxe_woocommerce_checkout_order_review_wrapper_open() {
    echo '<div class="checkout-order-review-wrapper">';
}

/**
 * Checkout order review wrapper close
 */
function aqualuxe_woocommerce_checkout_order_review_wrapper_close() {
    echo '</div>';
}

/**
 * Account wrapper open
 */
function aqualuxe_woocommerce_account_wrapper_open() {
    echo '<div class="account-wrapper">';
}

/**
 * Account wrapper close
 */
function aqualuxe_woocommerce_account_wrapper_close() {
    echo '</div>';
}

/**
 * Account content wrapper open
 */
function aqualuxe_woocommerce_account_content_wrapper_open() {
    echo '<div class="account-content-wrapper">';
}

/**
 * Account content wrapper close
 */
function aqualuxe_woocommerce_account_content_wrapper_close() {
    echo '</div>';
}

/**
 * Account navigation wrapper open
 */
function aqualuxe_woocommerce_account_navigation_wrapper_open() {
    echo '<div class="account-navigation-wrapper">';
}

/**
 * Account navigation wrapper close
 */
function aqualuxe_woocommerce_account_navigation_wrapper_close() {
    echo '</div>';
}

/**
 * Account dashboard wrapper open
 */
function aqualuxe_woocommerce_account_dashboard_wrapper_open() {
    echo '<div class="account-dashboard-wrapper">';
}

/**
 * Account dashboard wrapper close
 */
function aqualuxe_woocommerce_account_dashboard_wrapper_close() {
    echo '</div>';
}

/**
 * Account orders wrapper open
 */
function aqualuxe_woocommerce_account_orders_wrapper_open() {
    echo '<div class="account-orders-wrapper">';
}

/**
 * Account orders wrapper close
 */
function aqualuxe_woocommerce_account_orders_wrapper_close() {
    echo '</div>';
}

/**
 * Account downloads wrapper open
 */
function aqualuxe_woocommerce_account_downloads_wrapper_open() {
    echo '<div class="account-downloads-wrapper">';
}

/**
 * Account downloads wrapper close
 */
function aqualuxe_woocommerce_account_downloads_wrapper_close() {
    echo '</div>';
}

/**
 * Account addresses wrapper open
 */
function aqualuxe_woocommerce_account_addresses_wrapper_open() {
    echo '<div class="account-addresses-wrapper">';
}

/**
 * Account addresses wrapper close
 */
function aqualuxe_woocommerce_account_addresses_wrapper_close() {
    echo '</div>';
}

/**
 * Account details wrapper open
 */
function aqualuxe_woocommerce_account_details_wrapper_open() {
    echo '<div class="account-details-wrapper">';
}

/**
 * Account details wrapper close
 */
function aqualuxe_woocommerce_account_details_wrapper_close() {
    echo '</div>';
}

/**
 * Account payment methods wrapper open
 */
function aqualuxe_woocommerce_account_payment_methods_wrapper_open() {
    echo '<div class="account-payment-methods-wrapper">';
}

/**
 * Account payment methods wrapper close
 */
function aqualuxe_woocommerce_account_payment_methods_wrapper_close() {
    echo '</div>';
}