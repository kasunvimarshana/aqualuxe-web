<?php

/**
 * AquaLuxe AJAX Cart
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Ajax_Cart
{

    public function __construct()
    {
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'update_cart_fragment']);
    }

    public function update_cart_fragment($fragments)
    {
        ob_start(); ?>
        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
<?php
        $fragments['span.cart-count'] = ob_get_clean();
        return $fragments;
    }
}
