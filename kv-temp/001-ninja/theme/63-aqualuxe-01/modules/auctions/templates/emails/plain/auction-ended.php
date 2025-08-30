<?php
/**
 * Auction ended email (plain text)
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\n\n";

echo sprintf(__('Hi %s,', 'aqualuxe'), $user->display_name) . "\n\n";

echo sprintf(__('The auction for %s has ended.', 'aqualuxe'), $product->get_title()) . "\n\n";

if ($is_winner) {
    echo __('Congratulations! You are the winner of this auction.', 'aqualuxe') . "\n\n";
    
    echo __('To complete your purchase, please visit the link below.', 'aqualuxe') . "\n\n";
    
    echo __('Complete Purchase:', 'aqualuxe') . " " . add_query_arg('add-to-cart', $product->get_id(), wc_get_cart_url()) . "\n";
} else {
    if ($winner) {
        echo __('Unfortunately, you did not win this auction.', 'aqualuxe') . "\n\n";
    } else {
        echo __('This auction ended without a winner.', 'aqualuxe') . "\n\n";
    }
    
    echo __('Thank you for your participation.', 'aqualuxe') . "\n\n";
    
    echo __('Browse More Auctions:', 'aqualuxe') . " " . aqualuxe_auctions_get_archive_url() . "\n";
}

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));