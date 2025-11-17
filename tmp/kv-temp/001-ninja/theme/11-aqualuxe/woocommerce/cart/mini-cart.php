<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<div class="mini-cart-header">
    <h3><?php esc_html_e( 'Your Cart', 'aqualuxe' ); ?></h3>
    <span class="mini-cart-close">&times;</span>
</div>

<?php if ( ! WC()->cart->is_empty() ) : ?>

    <div class="mini-cart-items woocommerce-mini-cart cart_list product_list_widget">
        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                ?>
                <div class="mini-cart-item woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
                    <div class="mini-cart-item-image">
                        <?php if ( empty( $product_permalink ) ) : ?>
                            <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url( $product_permalink ); ?>">
                                <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mini-cart-item-details">
                        <div class="mini-cart-item-name">
                            <?php if ( empty( $product_permalink ) ) : ?>
                                <?php echo wp_kses_post( $product_name ); ?>
                            <?php else : ?>
                                <a href="<?php echo esc_url( $product_permalink ); ?>">
                                    <?php echo wp_kses_post( $product_name ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mini-cart-item-price">
                            <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                    </div>
                    
                    <div class="mini-cart-item-remove">
                        <?php
                        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                esc_attr__( 'Remove this item', 'aqualuxe' ),
                                esc_attr( $product_id ),
                                esc_attr( $cart_item_key ),
                                esc_attr( $_product->get_sku() )
                            ),
                            $cart_item_key
                        );
                        ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <div class="mini-cart-footer">
        <div class="mini-cart-subtotal">
            <span class="subtotal-label"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></span>
            <span class="subtotal-amount"><?php echo WC()->cart->get_cart_subtotal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
        </div>
        
        <?php if ( WC()->cart->get_cart_contents_count() > 0 ) : ?>
        <div class="mini-cart-shipping">
            <?php if ( WC()->cart->get_cart_contents_total() < 100 ) : ?>
                <div class="free-shipping-notice">
                    <?php
                    $remaining = 100 - WC()->cart->get_cart_contents_total();
                    printf(
                        /* translators: %s: Remaining amount for free shipping */
                        esc_html__( 'Add %s more for free shipping!', 'aqualuxe' ),
                        wc_price( $remaining )
                    );
                    ?>
                </div>
                <div class="shipping-progress-bar">
                    <div class="shipping-progress-fill" style="width: <?php echo esc_attr( WC()->cart->get_cart_contents_total() ); ?>%;"></div>
                </div>
            <?php else : ?>
                <div class="free-shipping-notice free-shipping-achieved">
                    <i class="fas fa-check-circle"></i> <?php esc_html_e( 'You\'ve got free shipping!', 'aqualuxe' ); ?>
                </div>
                <div class="shipping-progress-bar">
                    <div class="shipping-progress-fill" style="width: 100%;"></div>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="mini-cart-buttons">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="button view-cart-button"><?php esc_html_e( 'View Cart', 'aqualuxe' ); ?></a>
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="button checkout-button"><?php esc_html_e( 'Checkout', 'aqualuxe' ); ?></a>
        </div>
    </div>

<?php else : ?>

    <div class="mini-cart-empty">
        <div class="empty-cart-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'aqualuxe' ); ?></p>
        <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="button return-to-shop-button"><?php esc_html_e( 'Return to Shop', 'aqualuxe' ); ?></a>
    </div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>