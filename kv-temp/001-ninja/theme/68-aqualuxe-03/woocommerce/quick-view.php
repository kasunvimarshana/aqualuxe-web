<?php
/**
 * Product Quick View Modal Template
 * To be loaded via AJAX
 */
if ( ! defined( 'ABSPATH' ) ) exit;

global $product;
if ( ! $product ) return;
?>
<div class="aqualuxe-quick-view-modal">
    <div class="quick-view-header">
        <h2><?php echo esc_html( $product->get_name() ); ?></h2>
        <button class="quick-view-close" aria-label="Close">&times;</button>
    </div>
    <div class="quick-view-content">
        <div class="quick-view-image">
            <?php echo $product->get_image( 'large' ); ?>
        </div>
        <div class="quick-view-summary">
            <div class="quick-view-price">
                <?php echo $product->get_price_html(); ?>
            </div>
            <div class="quick-view-short-desc">
                <?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ); ?>
            </div>
            <form class="cart" method="post" enctype="multipart/form-data">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </form>
            <?php
            // Show wishlist button in quick view
            get_template_part( 'template-parts/wishlist/button' );
            ?>
        </div>
    </div>
</div>
