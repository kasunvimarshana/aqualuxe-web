<?php
/**
 * Auction winner email (plain text)
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\n\n";

echo sprintf(__('Hi %s,', 'aqualuxe'), $winner->display_name) . "\n\n";

echo sprintf(__('Congratulations! You won the auction for %s.', 'aqualuxe'), $product->get_title()) . "\n\n";

echo sprintf(__('Your winning bid was %s.', 'aqualuxe'), wc_price(aqualuxe_auctions_get_current_price($product))) . "\n\n";

echo __('To complete your purchase, please visit the link below.', 'aqualuxe') . "\n\n";

echo __('Complete Purchase:', 'aqualuxe') . " " . add_query_arg('add-to-cart', $product->get_id(), wc_get_cart_url()) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));