<?php
/**
 * Auction bid placed email (plain text)
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\n\n";

echo sprintf(__('Hi %s,', 'aqualuxe'), $user->display_name) . "\n\n";

echo sprintf(__('Your bid of %1$s on %2$s has been placed successfully.', 'aqualuxe'), wc_price($amount), $product->get_title()) . "\n\n";

echo __('You will be notified if you are outbid.', 'aqualuxe') . "\n\n";

echo __('View Auction:', 'aqualuxe') . " " . get_permalink($product->get_id()) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));