<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="woocommerce-cart-wrapper bg-white dark:bg-dark-light rounded-lg shadow-soft p-6 mb-8">
    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <?php do_action( 'woocommerce_before_cart_table' ); ?>

        <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
            <div class="cart-header hidden md:grid grid-cols-12 gap-4 pb-4 border-b border-gray-200 dark:border-gray-700 mb-4">
                <div class="cart-header-product col-span-6 font-medium"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></div>
                <div class="cart-header-price col-span-2 font-medium text-center"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></div>
                <div class="cart-header-quantity col-span-2 font-medium text-center"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></div>
                <div class="cart-header-subtotal col-span-2 font-medium text-right"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></div>
            </div>

            <?php do_action( 'woocommerce_before_cart_contents' ); ?>

            <div class="cart-items">
                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        <div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> grid grid-cols-1 md:grid-cols-12 gap-4 py-6 border-b border-gray-200 dark:border-gray-700 last:border-0">
                            
                            <!-- Product -->
                            <div class="cart-item-product col-span-1 md:col-span-6 flex items-center">
                                <!-- Remove Button -->
                                <div class="cart-item-remove mr-4">
                                    <?php
                                    echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                        '<a href="%s" class="remove text-gray-400 hover:text-red-500 transition-colors" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fas fa-times"></i></a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_html__( 'Remove this item', 'aqualuxe' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() )
                                    ), $cart_item_key );
                                    ?>
                                </div>
                                
                                <!-- Product Thumbnail -->
                                <div class="cart-item-thumbnail w-20 h-20 rounded overflow-hidden flex-shrink-0 mr-4">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );

                                    if ( ! $product_permalink ) {
                                        echo $thumbnail; // PHPCS: XSS ok.
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                    }
                                    ?>
                                </div>
                                
                                <!-- Product Name -->
                                <div class="cart-item-name">
                                    <h3 class="text-lg font-medium mb-1">
                                        <?php
                                        if ( ! $product_permalink ) {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                        } else {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="hover:text-primary transition-colors">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                        }

                                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                        // Meta data.
                                        echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                        // Backorder notification.
                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-amber-600 dark:text-amber-400">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</p>', $product_id ) );
                                        }
                                        ?>
                                    </h3>
                                    
                                    <!-- Mobile Price -->
                                    <div class="cart-item-price-mobile md:hidden text-sm mb-2">
                                        <span class="font-medium"><?php esc_html_e( 'Price:', 'aqualuxe' ); ?></span>
                                        <span class="text-primary">
                                            <?php
                                            echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="cart-item-price hidden md:flex col-span-2 items-center justify-center text-primary">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                ?>
                            </div>

                            <!-- Quantity -->
                            <div class="cart-item-quantity col-span-1 md:col-span-2 flex items-center md:justify-center">
                                <div class="quantity-wrapper flex items-center border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                    } else {
                                        $product_quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                'min_value'    => '0',
                                                'product_name' => $_product->get_name(),
                                                'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text', 'w-16', 'text-center', 'border-0', 'focus:outline-none', 'focus:ring-0', 'dark:bg-dark-light', 'dark:text-white' ), $product_id ),
                                            ),
                                            $_product,
                                            false
                                        );
                                    }

                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                    ?>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="cart-item-subtotal col-span-1 md:col-span-2 flex items-center justify-end font-medium text-primary">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <?php do_action( 'woocommerce_cart_contents' ); ?>

            <div class="cart-actions flex flex-wrap justify-between items-center mt-6">
                <div class="cart-coupon flex flex-wrap gap-2">
                    <?php if ( wc_coupons_enabled() ) { ?>
                        <div class="coupon flex flex-wrap md:flex-nowrap gap-2">
                            <input type="text" name="coupon_code" class="input-text w-full md:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'aqualuxe' ); ?>" />
                            <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?>"><?php esc_html_e( 'Apply coupon', 'aqualuxe' ); ?></button>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="cart-update">
                    <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'aqualuxe' ); ?>"><?php esc_html_e( 'Update cart', 'aqualuxe' ); ?></button>
                </div>

                <?php do_action( 'woocommerce_cart_actions' ); ?>

                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </div>

            <?php do_action( 'woocommerce_after_cart_contents' ); ?>
        </div>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>
</div>

<div class="cart-collaterals">
    <?php
        /**
         * Cart collaterals hook.
         *
         * @hooked woocommerce_cross_sell_display
         * @hooked woocommerce_cart_totals - 10
         */
        do_action( 'woocommerce_cart_collaterals' );
    ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>