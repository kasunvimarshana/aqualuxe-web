<?php
/**
 * Template part for displaying shop header
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Return if WooCommerce is not active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}
?>

<div class="shop-header">
    <div class="shop-header-inner">
        <div class="shop-header-left">
            <?php woocommerce_result_count(); ?>
        </div>

        <div class="shop-header-right">
            <div class="shop-view-switcher">
                <button class="shop-view-button shop-view-grid active" data-view="grid" aria-label="<?php esc_attr_e( 'Grid View', 'aqualuxe' ); ?>">
                    <i class="fas fa-th" aria-hidden="true"></i>
                </button>
                <button class="shop-view-button shop-view-list" data-view="list" aria-label="<?php esc_attr_e( 'List View', 'aqualuxe' ); ?>">
                    <i class="fas fa-list" aria-hidden="true"></i>
                </button>
            </div>

            <?php woocommerce_catalog_ordering(); ?>
        </div>
    </div>
</div>