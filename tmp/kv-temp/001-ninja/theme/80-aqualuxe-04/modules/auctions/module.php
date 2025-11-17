<?php
/**
 * Auctions/Trade-ins module (lightweight):
 * - Tag auction products if using common auction plugins.
 *
 * Guarded and optional.
 */

if (!defined('ABSPATH')) { exit; }

add_filter('aqualuxe/product_badges', function(array $badges, $product){
    $is_auction = false;
    try {
        if (method_exists($product, 'is_type') && $product->is_type('auction')) {
            $is_auction = true;
        } elseif (class_exists('WC_Product_Auction') && is_a($product, 'WC_Product_Auction')) {
            $is_auction = true;
        }
    } catch (\Throwable $e) {}
    if ($is_auction) $badges[] = esc_html__('Auction','aqualuxe');
    return $badges;
}, 10, 2);
