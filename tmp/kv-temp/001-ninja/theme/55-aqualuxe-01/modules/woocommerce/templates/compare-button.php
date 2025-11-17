<?php
/**
 * Compare Button
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

// Check if compare is enabled
if (!$module->get_option('compare', true)) {
    return;
}

// Get compare
$compare = $module->get_compare();

// Check if product is in compare
$in_compare = in_array($product->get_id(), $compare);

// Determine if we're on the single product page
$is_single = is_singular('product');
?>

<a href="#" class="compare-button <?php echo $in_compare ? 'in-compare' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php echo $in_compare ? esc_attr__('Remove from Compare', 'aqualuxe') : esc_attr__('Add to Compare', 'aqualuxe'); ?>">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16 16v-4l5 5-5 5v-4H4v-2h12zM8 2v3.999L20 6v2H8v4L3 7l5-5z"/></svg>
    <?php if ($is_single) : ?>
        <span><?php echo $in_compare ? esc_html__('Remove from Compare', 'aqualuxe') : esc_html__('Add to Compare', 'aqualuxe'); ?></span>
    <?php else : ?>
        <span class="screen-reader-text"><?php echo $in_compare ? esc_html__('Remove from Compare', 'aqualuxe') : esc_html__('Add to Compare', 'aqualuxe'); ?></span>
    <?php endif; ?>
</a>