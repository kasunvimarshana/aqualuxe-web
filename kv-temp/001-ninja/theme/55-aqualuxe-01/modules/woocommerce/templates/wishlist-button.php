<?php
/**
 * Wishlist Button
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

// Get module
$module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];

// Check if wishlist is enabled
if (!$module->get_option('wishlist', true)) {
    return;
}

// Get wishlist
$wishlist = $module->get_wishlist();

// Check if product is in wishlist
$in_wishlist = in_array($product->get_id(), $wishlist);

// Determine if we're on the single product page
$is_single = is_singular('product');
?>

<a href="#" class="wishlist-button <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php echo $in_wishlist ? esc_attr__('Remove from Wishlist', 'aqualuxe') : esc_attr__('Add to Wishlist', 'aqualuxe'); ?>">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228z"/></svg>
    <?php if ($is_single) : ?>
        <span><?php echo $in_wishlist ? esc_html__('Remove from Wishlist', 'aqualuxe') : esc_html__('Add to Wishlist', 'aqualuxe'); ?></span>
    <?php else : ?>
        <span class="screen-reader-text"><?php echo $in_wishlist ? esc_html__('Remove from Wishlist', 'aqualuxe') : esc_html__('Add to Wishlist', 'aqualuxe'); ?></span>
    <?php endif; ?>
</a>