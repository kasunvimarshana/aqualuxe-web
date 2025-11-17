<?php
/**
 * Wishlist button template for product loops
 * Place in template-parts/wishlist/button.php
 */
if ( ! defined( 'ABSPATH' ) ) exit;
global $product;
if ( ! $product ) return;
echo do_shortcode('[aqualuxe_wishlist_button product_id="' . $product->get_id() . '"]');
