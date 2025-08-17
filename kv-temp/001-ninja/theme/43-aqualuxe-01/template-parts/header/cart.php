<?php
/**
 * Template part for displaying the cart icon in the header
 *
 * @package AquaLuxe
 */

// This template part should only be included when WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}
?>

<div class="header-cart ml-4 relative">
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents" aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
        <span class="cart-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </span>
        <span class="cart-count absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
            <?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
        </span>
    </a>
    
    <div class="cart-dropdown absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 hidden">
        <div class="p-4">
            <?php
            $cart_empty = WC()->cart->is_empty();
            if ( $cart_empty ) {
                ?>
                <div class="cart-empty text-center py-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="mt-2"><?php esc_html_e( 'Your cart is empty', 'aqualuxe' ); ?></p>
                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="inline-block mt-4 px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark">
                        <?php esc_html_e( 'Start Shopping', 'aqualuxe' ); ?>
                    </a>
                </div>
                <?php
            } else {
                ?>
                <div class="cart-items">
                    <h3 class="text-lg font-bold mb-4"><?php esc_html_e( 'Your Cart', 'aqualuxe' ); ?></h3>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                ?>
                                <li class="py-3 flex items-center">
                                    <div class="cart-item-thumbnail w-16 h-16 flex-shrink-0">
                                        <?php
                                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 64, 64 ) ), $cart_item, $cart_item_key );

                                        if ( ! $product_permalink ) {
                                            echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        } else {
                                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        }
                                        ?>
                                    </div>
                                    <div class="cart-item-details ml-4 flex-1">
                                        <h4 class="text-sm font-medium">
                                            <?php
                                            if ( ! $product_permalink ) {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                            } else {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                            }
                                            ?>
                                        </h4>
                                        <div class="cart-item-quantity text-sm text-gray-600 dark:text-gray-400">
                                            <?php echo esc_html( sprintf( '%s × %s', $cart_item['quantity'], wc_price( $_product->get_price() ) ) ); ?>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                    
                    <div class="cart-subtotal flex justify-between items-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <span class="font-medium"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></span>
                        <span class="font-bold"><?php echo WC()->cart->get_cart_subtotal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    </div>
                    
                    <div class="cart-actions mt-4 grid grid-cols-2 gap-2">
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="text-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            <?php esc_html_e( 'View Cart', 'aqualuxe' ); ?>
                        </a>
                        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="text-center px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark">
                            <?php esc_html_e( 'Checkout', 'aqualuxe' ); ?>
                        </a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>