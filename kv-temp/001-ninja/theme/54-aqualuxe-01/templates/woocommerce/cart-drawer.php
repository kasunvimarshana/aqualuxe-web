<?php
/**
 * Template part for displaying the WooCommerce cart drawer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Only show if WooCommerce is active
if (!aqualuxe_is_woocommerce_active()) {
    return;
}
?>

<div id="cart-drawer" class="cart-drawer fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden">
    <div class="cart-drawer-container bg-white dark:bg-gray-800 w-full max-w-md h-full ml-auto overflow-y-auto transform transition-transform duration-300 translate-x-full">
        <div class="cart-drawer-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold"><?php esc_html_e('Your Cart', 'aqualuxe'); ?></h2>
            
            <button class="cart-drawer-close text-gray-700 dark:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="sr-only"><?php esc_html_e('Close cart', 'aqualuxe'); ?></span>
            </button>
        </div>
        
        <div class="cart-drawer-content p-4">
            <?php
            // Get cart contents
            $cart_items = WC()->cart->get_cart();
            
            if (!empty($cart_items)) :
            ?>
                <div class="cart-items divide-y divide-gray-200 dark:divide-gray-700">
                    <?php foreach ($cart_items as $cart_item_key => $cart_item) : 
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                        
                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    ?>
                            <div class="cart-item py-4 flex">
                                <div class="cart-item-thumbnail w-20 h-20 flex-shrink-0">
                                    <?php
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(['class' => 'w-full h-full object-cover rounded']), $cart_item, $cart_item_key);
                                    
                                    if (!$product_permalink) {
                                        echo $thumbnail; // PHPCS: XSS ok.
                                    } else {
                                        printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                    }
                                    ?>
                                </div>
                                
                                <div class="cart-item-details ml-4 flex-grow">
                                    <h3 class="cart-item-title text-base font-medium">
                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                        } else {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }
                                        
                                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
                                        
                                        // Meta data.
                                        echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.
                                        
                                        // Backorder notification.
                                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'aqualuxe') . '</p>', $product_id));
                                        }
                                        ?>
                                    </h3>
                                    
                                    <div class="cart-item-price text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        <?php
                                        echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                        ?>
                                    </div>
                                    
                                    <div class="cart-item-quantity mt-2 flex items-center">
                                        <button class="cart-item-quantity-minus bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 w-6 h-6 flex items-center justify-center rounded-l" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        
                                        <input type="number" class="cart-item-quantity-input w-10 h-6 text-center border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-white" value="<?php echo esc_attr($cart_item['quantity']); ?>" min="1" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                                        
                                        <button class="cart-item-quantity-plus bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 w-6 h-6 flex items-center justify-center rounded-r" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                        
                                        <button class="cart-item-remove ml-4 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span class="sr-only"><?php esc_html_e('Remove', 'aqualuxe'); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
                
                <div class="cart-totals mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between mb-2">
                        <span><?php esc_html_e('Subtotal', 'aqualuxe'); ?></span>
                        <span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                    </div>
                    
                    <?php if (WC()->cart->get_cart_shipping_total()) : ?>
                        <div class="flex justify-between mb-2">
                            <span><?php esc_html_e('Shipping', 'aqualuxe'); ?></span>
                            <span><?php echo WC()->cart->get_cart_shipping_total(); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (WC()->cart->get_taxes_total()) : ?>
                        <div class="flex justify-between mb-2">
                            <span><?php esc_html_e('Tax', 'aqualuxe'); ?></span>
                            <span><?php echo wc_price(WC()->cart->get_taxes_total()); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex justify-between font-bold text-lg mt-4">
                        <span><?php esc_html_e('Total', 'aqualuxe'); ?></span>
                        <span><?php echo WC()->cart->get_total(); ?></span>
                    </div>
                </div>
                
                <div class="cart-actions mt-8">
                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center font-medium py-3 px-4 rounded-md mb-4 transition-colors">
                        <?php esc_html_e('Checkout', 'aqualuxe'); ?>
                    </a>
                    
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="block w-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-center font-medium py-3 px-4 rounded-md transition-colors">
                        <?php esc_html_e('View Cart', 'aqualuxe'); ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="empty-cart text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    
                    <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('Your cart is empty', 'aqualuxe'); ?></h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6"><?php esc_html_e('Looks like you haven\'t added any items to your cart yet.', 'aqualuxe'); ?></p>
                    
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <?php esc_html_e('Start Shopping', 'aqualuxe'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>