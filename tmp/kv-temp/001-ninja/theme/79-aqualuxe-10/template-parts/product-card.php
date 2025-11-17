<?php
/**
 * Product card template part
 *
 * Usage:
 * get_template_part('template-parts/product', 'card', ['product_id' => 123]);
 *
 * Safe to use without WooCommerce; price will be omitted.
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$pid = 0;
if (isset($args['product_id'])) {
    $pid = (int) $args['product_id'];
} elseif (isset($args['post_id'])) {
    $pid = (int) $args['post_id'];
}

if (! $pid) {
    return;
}

$product = null;
if (function_exists('wc_get_product')) {
    $product = call_user_func('wc_get_product', $pid);
}
?>
<a href="<?php echo esc_url(get_permalink($pid)); ?>" class="block border rounded p-4 hover-lift">
  <?php if (has_post_thumbnail($pid)) { echo get_the_post_thumbnail($pid, 'medium', ['class' => 'w-full h-48 object-cover mb-3 rounded']); } ?>
  <h3 class="font-medium mb-1"><?php echo esc_html(get_the_title($pid)); ?></h3>
  <?php if ($product) : ?>
    <div class="opacity-80 text-sm"><?php echo wp_kses_post($product->get_price_html()); ?></div>
  <?php endif; ?>
</a>
