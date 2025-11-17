<?php
/**
 * Template part for displaying the cart icon in the header
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Only display if WooCommerce is active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}
?>

<div class="cart-icon relative" x-data="{ cartOpen: false }">
    <button 
        @click="cartOpen = !cartOpen" 
        class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors"
        aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>"
    >
        <svg class="w-5 h-5 text-dark-700 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        
        <?php if ( WC()->cart && WC()->cart->get_cart_contents_count() > 0 ) : ?>
            <span class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                <?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
            </span>
        <?php endif; ?>
    </button>
    
    <!-- Mini cart dropdown -->
    <div 
        x-cloak
        x-show="cartOpen" 
        @click.away="cartOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white dark:bg-dark-800 rounded-lg shadow-lg z-50"
    >
        <div class="p-4">
            <?php if ( WC()->cart && WC()->cart->is_empty() ) : ?>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-dark-400 dark:text-dark-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-dark-600 dark:text-dark-400"><?php esc_html_e( 'Your cart is empty', 'aqualuxe' ); ?></p>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-block mt-4 px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                        <?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="mini-cart-items space-y-4 max-h-80 overflow-y-auto">
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
                            <div class="mini-cart-item flex items-center">
                                <div class="mini-cart-item-thumbnail w-16 h-16 flex-shrink-0 mr-4">
                                    <?php if ( ! $_product->is_visible() ) : ?>
                                        <?php echo $thumbnail; ?>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url( $product_permalink ); ?>">
                                            <?php echo $thumbnail; ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="mini-cart-item-details flex-grow">
                                    <h4 class="mini-cart-item-title text-sm font-medium text-dark-900 dark:text-white">
                                        <?php if ( ! $_product->is_visible() ) : ?>
                                            <?php echo wp_kses_post( $product_name ); ?>
                                        <?php else : ?>
                                            <a href="<?php echo esc_url( $product_permalink ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                                <?php echo wp_kses_post( $product_name ); ?>
                                            </a>
                                        <?php endif; ?>
                                    </h4>
                                    <div class="mini-cart-item-quantity text-xs text-dark-500 dark:text-dark-400">
                                        <?php echo esc_html( $cart_item['quantity'] ); ?> &times; <?php echo wp_kses_post( $product_price ); ?>
                                    </div>
                                </div>
                                <div class="mini-cart-item-remove">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="remove text-dark-400 hover:text-red-600 dark:text-dark-500 dark:hover:text-red-400" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_html__( 'Remove this item', 'aqualuxe' ),
                                            esc_attr( $product_id ),
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

                <div class="mini-cart-subtotal flex justify-between items-center mt-4 pt-4 border-t border-gray-200 dark:border-dark-700">
                    <span class="text-dark-900 dark:text-white font-medium"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></span>
                    <span class="text-dark-900 dark:text-white font-bold"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                </div>

                <div class="mini-cart-actions grid grid-cols-2 gap-2 mt-4">
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-outline text-center">
                        <?php esc_html_e( 'View Cart', 'aqualuxe' ); ?>
                    </a>
                    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-primary text-center">
                        <?php esc_html_e( 'Checkout', 'aqualuxe' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>