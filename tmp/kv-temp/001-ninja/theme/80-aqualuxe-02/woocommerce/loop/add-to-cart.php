<?php
/** Override add to cart with wishlist and quick view buttons */
if (!defined('ABSPATH')) exit;

global $product;
if (!$product) return;

// Build attribute string manually to avoid wc_implode_html_attributes dependency
$attr_str = '';
if (!empty($args['attributes']) && is_array($args['attributes'])) {
    foreach ($args['attributes'] as $k=>$v) { $attr_str .= ' ' . esc_attr($k) . '="' . esc_attr($v) . '"'; }
}

echo apply_filters(
    'woocommerce_loop_add_to_cart_link',
    sprintf(
        '<a href="%s" data-quantity="%s" class="%s"%s>%s</a>',
        esc_url($product->add_to_cart_url()),
        esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
        esc_attr(isset($args['class']) ? $args['class'] : 'button'),
        $attr_str,
        esc_html($product->add_to_cart_text())
    ),
    $product,
        isset($args) ? $args : []
    );
    // Extra controls
    ?>
    <button data-wishlist data-product-id="<?php echo esc_attr($product->get_id()); ?>" class="ml-2 text-sm underline"><?php esc_html_e('Wishlist','aqualuxe'); ?></button>
    <button data-qv="<?php echo esc_url(get_permalink($product->get_id())); ?>?quickview=1" class="ml-2 text-sm underline"><?php esc_html_e('Quick View','aqualuxe'); ?></button>
