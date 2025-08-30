<?php
/**
 * Auction outbid email (plain text)
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\n\n";

echo sprintf(__('Hi %s,', 'aqualuxe'), $user->display_name) . "\n\n";

echo sprintf(__('You have been outbid on %s.', 'aqualuxe'), $product->get_title()) . "\n\n";

echo sprintf(__('The current highest bid is %s.', 'aqualuxe'), wc_price($amount)) . "\n\n";

echo __('Place a new bid to get back in the auction!', 'aqualuxe') . "\n\n";

echo __('Place New Bid:', 'aqualuxe') . " " . get_permalink($product->get_id()) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));