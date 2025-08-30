<?php
/**
 * Template part for displaying shop pagination
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
?>

<div class="shop-pagination">
    <?php woocommerce_pagination(); ?>
</div>