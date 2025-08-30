<?php
/**
 * Cart Drawer Template
 *
 * This template displays the cart drawer.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get cart items
$cart_items = WC()->cart->get_cart();
$cart_count = WC()->cart->get_cart_contents_count();
$cart_total = WC()->cart->get_cart_total();
$is_empty = WC()->cart->is_empty();
?>

<div id="cart-drawer" class="cart-drawer">
    <div class="drawer-container">
        <div class="drawer-header">
            <h3 class="drawer-title">
                <?php esc_html_e('Your Cart', 'aqualuxe'); ?>
                <span class="cart-count"><?php echo esc_html($cart_count); ?></span>
            </h3>
            <button class="drawer-close" aria-label="<?php esc_attr_e('Close cart', 'aqualuxe'); ?>">
                <?php aqualuxe_svg_icon('close', array('class' => 'w-6 h-6')); ?>
            </button>
        </div>

        <div class="drawer-content">
            <?php if ($is_empty) : ?>
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <?php aqualuxe_svg_icon('shopping-bag', array('class' => 'w-16 h-16 text-gray-300')); ?>
                    </div>
                    <p class="empty-cart-message"><?php esc_html_e('Your cart is currently empty.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="continue-shopping-btn">
                        <?php esc_html_e('Continue Shopping', 'aqualuxe'); ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="cart-items">
                    <?php foreach ($cart_items as $cart_item_key => $cart_item) : ?>
                        <?php
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        ?>
                            <div class="cart-item">
                                <div class="cart-item-thumbnail">
                                    <?php
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                    if (!$product_permalink) {
                                        echo $thumbnail; // PHPCS: XSS ok.
                                    } else {
                                        printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                    }
                                    ?>
                                </div>

                                <div class="cart-item-details">
                                    <h4 class="cart-item-title">
                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                        } else {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }
                                        ?>
                                    </h4>

                                    <div class="cart-item-price">
                                        <?php
                                        echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                        ?>
                                    </div>

                                    <?php
                                    // Meta data
                                    echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.
                                    ?>

                                    <div class="cart-item-quantity">
                                        <?php
                                        if ($_product->is_sold_individually()) {
                                            $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                        } else {
                                            $product_quantity = woocommerce_quantity_input(
                                                array(
                                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                                    'input_value'  => $cart_item['quantity'],
                                                    'max_value'    => $_product->get_max_purchase_quantity(),
                                                    'min_value'    => '0',
                                                    'product_name' => $_product->get_name(),
                                                ),
                                                $_product,
                                                false
                                            );
                                        }

                                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                        ?>
                                    </div>
                                </div>

                                <div class="cart-item-remove">
                                    <?php
                                    echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                                            esc_html__('Remove this item', 'aqualuxe'),
                                            esc_attr($product_id),
                                            esc_attr($_product->get_sku())
                                        ),
                                        $cart_item_key
                                    );
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    <?php endforeach; ?>
                </div>

                <div class="cart-subtotal">
                    <span class="subtotal-label"><?php esc_html_e('Subtotal', 'aqualuxe'); ?></span>
                    <span class="subtotal-value"><?php echo $cart_total; ?></span>
                </div>

                <?php if (wc_coupons_enabled()) : ?>
                    <div class="cart-coupon">
                        <form class="coupon-form">
                            <input type="text" name="coupon_code" class="input-text" id="drawer-coupon-code" value="" placeholder="<?php esc_attr_e('Coupon code', 'aqualuxe'); ?>" />
                            <button type="submit" class="button apply-coupon-btn" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'aqualuxe'); ?>"><?php esc_attr_e('Apply', 'aqualuxe'); ?></button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php
                // Display shipping information if available
                if (function_exists('aqualuxe_cart_shipping_info')) :
                    aqualuxe_cart_shipping_info();
                endif;
                ?>

                <div class="cart-actions">
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="view-cart-btn">
                        <?php esc_html_e('View Cart', 'aqualuxe'); ?>
                    </a>
                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-btn">
                        <?php esc_html_e('Checkout', 'aqualuxe'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!$is_empty && function_exists('aqualuxe_cart_cross_sells')) : ?>
            <div class="drawer-footer">
                <div class="cross-sells">
                    <h4 class="cross-sells-title"><?php esc_html_e('You may also like', 'aqualuxe'); ?></h4>
                    <?php aqualuxe_cart_cross_sells(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>