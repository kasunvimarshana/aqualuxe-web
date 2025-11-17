<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post, $product;

// Get sale badge settings from theme customizer
$badge_style = get_theme_mod( 'aqualuxe_product_badge_style', 'default' );
$show_sale_badge = get_theme_mod( 'aqualuxe_product_sale_badge', true );
$show_new_badge = get_theme_mod( 'aqualuxe_product_new_badge', true );
$show_featured_badge = get_theme_mod( 'aqualuxe_product_featured_badge', true );
$show_out_of_stock_badge = get_theme_mod( 'aqualuxe_product_out_of_stock_badge', true );
$show_discount_percentage = get_theme_mod( 'aqualuxe_product_discount_percentage', true );
$new_product_days = get_theme_mod( 'aqualuxe_product_new_days', 30 );

// Check if product is on sale
$on_sale = $product->is_on_sale();

// Check if product is new (published within the last X days)
$is_new = false;
if ( $show_new_badge ) {
    $post_date = get_the_time( 'U' );
    $current_date = current_time( 'timestamp' );
    $days_diff = floor( ( $current_date - $post_date ) / ( 60 * 60 * 24 ) );
    $is_new = $days_diff < $new_product_days;
}

// Check if product is featured
$is_featured = $show_featured_badge && $product->is_featured();

// Check if product is out of stock
$is_out_of_stock = $show_out_of_stock_badge && ! $product->is_in_stock();

// Calculate discount percentage if needed
$discount_percentage = 0;
if ( $on_sale && $show_discount_percentage && $product->get_regular_price() ) {
    $regular_price = (float) $product->get_regular_price();
    $sale_price = (float) $product->get_sale_price();
    
    if ( $regular_price > 0 ) {
        $discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
    }
}

// Only proceed if we have at least one badge to show
if ( $on_sale || $is_new || $is_featured || $is_out_of_stock ) :
?>
<div class="aqualuxe-product-badges aqualuxe-badge-style-<?php echo esc_attr( $badge_style ); ?>">
    <?php if ( $is_out_of_stock ) : ?>
        <span class="aqualuxe-badge aqualuxe-out-of-stock-badge">
            <?php esc_html_e( 'Out of stock', 'aqualuxe' ); ?>
        </span>
    <?php endif; ?>
    
    <?php if ( $on_sale && $show_sale_badge ) : ?>
        <span class="aqualuxe-badge aqualuxe-sale-badge">
            <?php 
            if ( $show_discount_percentage && $discount_percentage > 0 ) {
                echo esc_html( sprintf( __( '%s%% Off', 'aqualuxe' ), $discount_percentage ) );
            } else {
                esc_html_e( 'Sale', 'aqualuxe' );
            }
            ?>
        </span>
    <?php endif; ?>
    
    <?php if ( $is_new ) : ?>
        <span class="aqualuxe-badge aqualuxe-new-badge">
            <?php esc_html_e( 'New', 'aqualuxe' ); ?>
        </span>
    <?php endif; ?>
    
    <?php if ( $is_featured ) : ?>
        <span class="aqualuxe-badge aqualuxe-featured-badge">
            <?php esc_html_e( 'Featured', 'aqualuxe' ); ?>
        </span>
    <?php endif; ?>
</div>
<?php endif; ?>