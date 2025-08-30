<?php
/**
 * Template part for displaying shop filters
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Return if WooCommerce is not active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}

// Return if not on shop page
if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
    return;
}

// Check if we have active widgets in the shop sidebar
if ( ! is_active_sidebar( 'shop-sidebar' ) ) {
    return;
}
?>

<div class="shop-filters">
    <div class="shop-filters-inner">
        <button class="shop-filters-toggle">
            <i class="fas fa-filter" aria-hidden="true"></i>
            <span><?php esc_html_e( 'Filter Products', 'aqualuxe' ); ?></span>
        </button>

        <div class="shop-filters-content">
            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
        </div>
    </div>
</div>