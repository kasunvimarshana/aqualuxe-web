<?php
/**
 * Mini Cart
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get cart count
$cart_count = WC()->cart->get_cart_contents_count();
?>

<div class="mini-cart-wrapper" x-data="{ cartOpen: false }">
    <button class="cart-toggle" aria-expanded="false" @click="cartOpen = !cartOpen">
        <span class="screen-reader-text"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
        <span class="cart-count"><?php echo esc_html($cart_count); ?></span>
    </button>

    <div class="cart-modal" :class="{ 'is-active': cartOpen }" @click.away="cartOpen = false">
        <div class="cart-modal-container">
            <button class="cart-modal-close" @click="cartOpen = false">
                <span class="screen-reader-text"><?php esc_html_e('Close cart', 'aqualuxe'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
            </button>
            <div class="cart-modal-content">
                <div class="widget_shopping_cart_content">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        </div>
    </div>
</div>