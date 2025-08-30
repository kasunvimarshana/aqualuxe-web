<?php
/**
 * Product Badges
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (!$product) {
    return;
}

$badges = [];

// Sale badge
if ($product->is_on_sale()) {
    $badges[] = '<span class="badge badge-sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
}

// Out of stock badge
if (!$product->is_in_stock()) {
    $badges[] = '<span class="badge badge-out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
}

// Featured badge
if ($product->is_featured()) {
    $badges[] = '<span class="badge badge-featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
}

// New badge (products less than 30 days old)
$days_since_creation = (time() - strtotime($product->get_date_created())) / DAY_IN_SECONDS;
if ($days_since_creation < 30) {
    $badges[] = '<span class="badge badge-new">' . esc_html__('New', 'aqualuxe') . '</span>';
}

// Apply filters to allow adding custom badges
$badges = apply_filters('aqualuxe_wc_product_badges', $badges, $product);

if (!empty($badges)) {
    echo '<div class="product-badges">' . implode('', $badges) . '</div>';
}