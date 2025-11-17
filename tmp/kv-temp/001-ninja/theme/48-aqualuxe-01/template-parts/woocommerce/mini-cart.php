<?php
/**
 * Template part for displaying the mini cart
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Check if WooCommerce is active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}

$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_url = wc_get_cart_url();
$checkout_url = wc_get_checkout_url();
?>

<div class="mini-cart relative">
    <a href="<?php echo esc_url( $cart_url ); ?>" class="mini-cart-link flex items-center justify-center w-10 h-10 rounded-full bg-primary-light text-primary hover:bg-primary hover:text-white transition-colors relative">
        <i class="fas fa-shopping-cart"></i>
        <?php if ( $cart_count > 0 ) : ?>
            <span class="mini-cart-count absolute -top-1 -right-1 w-5 h-5 flex items-center justify-center bg-accent text-dark text-xs font-bold rounded-full">
                <?php echo esc_html( $cart_count ); ?>
            </span>
        <?php endif; ?>
    </a>
    
    <div class="mini-cart-dropdown absolute right-0 top-full mt-2 w-80 bg-white dark:bg-dark-light rounded-lg shadow-lg z-50 hidden">
        <div class="mini-cart-header border-b border-gray-200 dark:border-gray-700 p-4 flex justify-between items-center">
            <h3 class="text-lg font-medium">
                <?php esc_html_e( 'Shopping Cart', 'aqualuxe' ); ?>
            </h3>
            <span class="mini-cart-count-text text-sm">
                <?php
                echo sprintf(
                    /* translators: %d: number of items in cart */
                    _n( '%d item', '%d items', $cart_count, 'aqualuxe' ),
                    $cart_count
                );
                ?>
            </span>
        </div>
        
        <div class="mini-cart-content">
            <?php if ( $cart_count > 0 ) : ?>
                <div class="mini-cart-products max-h-80 overflow-y-auto p-4">
                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
                        <?php
                        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                        
                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                            $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                            <div class="mini-cart-item flex items-center py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                <div class="mini-cart-item-image w-16 h-16 rounded overflow-hidden flex-shrink-0">
                                    <?php if ( ! empty( $product_permalink ) ) : ?>
                                        <a href="<?php echo esc_url( $product_permalink ); ?>">
                                            <?php echo $thumbnail; ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo $thumbnail; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mini-cart-item-details flex-grow px-3">
                                    <h4 class="mini-cart-item-title text-sm font-medium mb-1">
                                        <?php if ( ! empty( $product_permalink ) ) : ?>
                                            <a href="<?php echo esc_url( $product_permalink ); ?>" class="hover:text-primary transition-colors">
                                                <?php echo esc_html( $product_name ); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo esc_html( $product_name ); ?>
                                        <?php endif; ?>
                                    </h4>
                                    
                                    <div class="mini-cart-item-quantity text-xs text-gray-600 dark:text-gray-400">
                                        <?php echo sprintf( esc_html__( 'Qty: %s', 'aqualuxe' ), $cart_item['quantity'] ); ?>
                                    </div>
                                    
                                    <div class="mini-cart-item-price text-sm font-medium text-primary mt-1">
                                        <?php echo $product_price; ?>
                                    </div>
                                </div>
                                
                                <div class="mini-cart-item-remove">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="remove-item text-gray-400 hover:text-red-500 transition-colors" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><i class="fas fa-times"></i></a>',
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
                        <?php } ?>
                    <?php endforeach; ?>
                </div>
                
                <div class="mini-cart-subtotal border-t border-gray-200 dark:border-gray-700 p-4 flex justify-between items-center">
                    <span class="font-medium"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></span>
                    <span class="font-bold text-primary"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                </div>
                
                <div class="mini-cart-actions p-4 pt-0 grid grid-cols-2 gap-2">
                    <a href="<?php echo esc_url( $cart_url ); ?>" class="btn-view-cart py-2 px-4 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-center rounded transition-colors">
                        <?php esc_html_e( 'View Cart', 'aqualuxe' ); ?>
                    </a>
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn-checkout py-2 px-4 bg-primary hover:bg-primary-dark text-white text-center rounded transition-colors">
                        <?php esc_html_e( 'Checkout', 'aqualuxe' ); ?>
                    </a>
                </div>
                
            <?php else : ?>
                <div class="mini-cart-empty p-6 text-center">
                    <div class="mini-cart-empty-icon text-4xl text-gray-300 dark:text-gray-600 mb-3">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <p class="mini-cart-empty-text text-gray-600 dark:text-gray-400 mb-4">
                        <?php esc_html_e( 'Your cart is currently empty.', 'aqualuxe' ); ?>
                    </p>
                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn-shop-now py-2 px-4 bg-primary hover:bg-primary-dark text-white text-center rounded inline-block transition-colors">
                        <?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>