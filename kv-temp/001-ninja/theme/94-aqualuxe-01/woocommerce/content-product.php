<?php
/**
 * Template for product content within loops with AquaLuxe actions
 */
if (!defined('ABSPATH')) { exit; }

global $product;
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('group relative bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden', $product); ?>
    itemscope itemtype="https://schema.org/Product">
    <?php
    do_action('woocommerce_before_shop_loop_item');

    echo '<div class="aspect-square overflow-hidden">';
    do_action('woocommerce_before_shop_loop_item_title');
    echo '</div>';

    echo '<div class="p-4 flex flex-col gap-2">';
    do_action('woocommerce_shop_loop_item_title');
    do_action('woocommerce_after_shop_loop_item_title');

    echo '<div class="flex items-center gap-2 mt-2">';
    do_action('woocommerce_after_shop_loop_item');
    echo '<button type="button" class="al_quick_view btn btn_secondary" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
    echo '<button type="button" class="al_wishlist_toggle btn btn_secondary" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Wishlist', 'aqualuxe') . '</button>';
    echo '</div>';
    echo '</div>';
    ?>
</li>
