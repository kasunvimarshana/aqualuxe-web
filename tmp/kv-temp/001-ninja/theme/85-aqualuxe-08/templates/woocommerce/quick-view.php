<?php
/**
 * Minimal Quick View template for Woo_Extras_Service_Provider AJAX.
 * Expects global $post set to a product.
 */
if (!defined('ABSPATH')) { exit; }

$fn = function(string $name, ...$args) {
    return function_exists($name) ? call_user_func_array($name, $args) : null;
};

global $product;
if (!$product) {
    $id = (int) ($fn('get_the_ID') ?: 0);
    if ($id) { $product = $fn('wc_get_product', $id); }
}
$title = (string) ($fn('get_the_title') ?: '');
$aria = $fn('esc_attr', $title) ?: $title;
?>
<div class="aqlx-quick-view-modal" role="dialog" aria-modal="true" aria-label="<?php echo $aria; ?>">
  <div class="aqlx-quick-view-media">
    <?php if ($fn('has_post_thumbnail')) { $fn('the_post_thumbnail', 'large', ['loading' => 'lazy']); } ?>
  </div>
  <div class="aqlx-quick-view-content">
    <h2 class="product_title entry-title"><?php if ($title) { echo $title; } ?></h2>
    <div class="price"><?php echo $product ? $product->get_price_html() : ''; ?></div>
    <div class="summary"><?php if ($fn('the_excerpt') !== null) { /* printed */ } ?></div>
    <form class="cart" method="post" action="<?php echo (string) ($fn('esc_url', (string) $fn('apply_filters', 'woocommerce_add_to_cart_form_action', $product ? $product->get_permalink() : '')) ?: ''); ?>">
      <input type="hidden" name="add-to-cart" value="<?php echo (int) ($fn('get_the_ID') ?: 0); ?>" />
      <button type="submit" class="aqlx-btn aqlx-btn--primary"><?php echo (string) ($fn('esc_html__', 'Add to cart', 'aqualuxe') ?: 'Add to cart'); ?></button>
    </form>
  </div>
</div>
