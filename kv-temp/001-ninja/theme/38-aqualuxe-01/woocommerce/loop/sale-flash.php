<?php
/**
 * Product loop sale flash
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post, $product;

// Check if product is on sale
if ( $product->is_on_sale() ) {
    echo apply_filters(
        'woocommerce_sale_flash',
        '<span class="onsale">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>',
        $post,
        $product
    );
}

// Check if product is featured
if ( $product->is_featured() ) {
    echo '<span class="featured">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
}

// Check if product is new (published within the last 30 days)
$days_as_new = apply_filters( 'aqualuxe_days_as_new', 30 );
$post_date = get_the_time( 'U' );
$current_date = current_time( 'timestamp' );
$seconds_in_day = 86400; // 60 * 60 * 24

if ( ( $current_date - $post_date ) < ( $days_as_new * $seconds_in_day ) ) {
    echo '<span class="new">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
}

// Check if product is out of stock
if ( ! $product->is_in_stock() ) {
    echo '<span class="out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
}

// Check if product is on backorder
if ( $product->is_on_backorder() ) {
    echo '<span class="backorder">' . esc_html__( 'Backorder', 'aqualuxe' ) . '</span>';
}

// Additional product badges (can be added via hooks)
do_action( 'aqualuxe_product_badges', $product );