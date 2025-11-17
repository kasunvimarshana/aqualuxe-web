<?php
/**
 * Cart page template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<div class="cart-wrapper">
    <div class="container mx-auto px-4 py-8">
        
        <header class="cart-header mb-8">
            <h1 class="text-3xl font-bold text-center"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></h1>
            <div class="cart-breadcrumb text-center mt-2">
                <span class="text-neutral-600 dark:text-neutral-300"><?php esc_html_e('Review your items before checkout', 'aqualuxe'); ?></span>
            </div>
        </header>

        <?php do_action('woocommerce_before_cart'); ?>

        <div class="woocommerce-cart-form">
            
            <?php if (WC()->cart->is_empty()) : ?>
                
                <!-- Empty Cart -->
                <div class="cart-empty text-center py-16">
                    <div class="empty-cart-icon mb-8">
                        <svg class="w-24 h-24 mx-auto text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e('Your cart is empty', 'aqualuxe'); ?></h2>
                    <p class="text-neutral-600 dark:text-neutral-300 mb-8"><?php esc_html_e('Looks like you haven\'t added any items to your cart yet.', 'aqualuxe'); ?></p>
                    
                    <div class="empty-cart-actions">
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <?php esc_html_e('Continue Shopping', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
                
            <?php else : ?>
                
                <!-- Cart with Items -->
                <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                    
                    <div class="cart-layout grid gap-8 lg:grid-cols-3">
                        
                        <!-- Cart Items -->
                        <div class="cart-items lg:col-span-2">
                            
                            <div class="cart-items-header bg-neutral-50 dark:bg-neutral-800 rounded-t-lg p-4">
                                <div class="grid grid-cols-12 gap-4 text-sm font-medium text-neutral-600 dark:text-neutral-300">
                                    <div class="col-span-6"><?php esc_html_e('Product', 'aqualuxe'); ?></div>
                                    <div class="col-span-2 text-center"><?php esc_html_e('Price', 'aqualuxe'); ?></div>
                                    <div class="col-span-2 text-center"><?php esc_html_e('Quantity', 'aqualuxe'); ?></div>
                                    <div class="col-span-2 text-center"><?php esc_html_e('Total', 'aqualuxe'); ?></div>
                                </div>
                            </div>
                            
                            <div class="cart-items-body bg-white dark:bg-neutral-800 border border-t-0 border-neutral-200 dark:border-neutral-700 rounded-b-lg">
                                
                                <?php do_action('woocommerce_before_cart_table'); ?>
                                
                                <?php
                                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                ?>
                                        <div class="cart-item border-b border-neutral-200 dark:border-neutral-700 p-4 last:border-b-0">
                                            <div class="grid grid-cols-12 gap-4 items-center">
                                                
                                                <!-- Product Info -->
                                                <div class="col-span-6">
                                                    <div class="flex items-center space-x-4">
                                                        
                                                        <!-- Product Image -->
                                                        <div class="product-thumbnail flex-shrink-0">
                                                            <?php
                                                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
                                                            
                                                            if (!$product_permalink) {
                                                                echo $thumbnail;
                                                            } else {
                                                                printf('<a href="%s" class="block w-16 h-16 overflow-hidden rounded-lg">%s</a>', esc_url($product_permalink), $thumbnail);
                                                            }
                                                            ?>
                                                        </div>
                                                        
                                                        <!-- Product Details -->
                                                        <div class="product-details flex-1">
                                                            <h3 class="product-name font-medium">
                                                                <?php
                                                                if (!$product_permalink) {
                                                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                                                } else {
                                                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                                                }
                                                                ?>
                                                            </h3>
                                                            
                                                            <!-- Product Meta -->
                                                            <div class="product-meta text-sm text-neutral-600 dark:text-neutral-300 mt-1">
                                                                <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                
                                                <!-- Price -->
                                                <div class="col-span-2 text-center hidden md:block">
                                                    <span class="product-price font-medium">
                                                        <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                                                    </span>
                                                </div>
                                                
                                                <!-- Quantity -->
                                                <div class="col-span-2 text-center">
                                                    <?php
                                                    if ($_product->is_sold_individually()) {
                                                        $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                                    } else {
                                                        $product_quantity = woocommerce_quantity_input([
                                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                                            'input_value'  => $cart_item['quantity'],
                                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                                            'min_value'    => '0',
                                                            'product_name' => $_product->get_name(),
                                                        ], $_product, false);
                                                    }
                                                    
                                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                                    ?>
                                                </div>
                                                
                                                <!-- Subtotal -->
                                                <div class="col-span-2 text-center">
                                                    <span class="product-subtotal font-semibold">
                                                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                                    </span>
                                                    
                                                    <!-- Remove Item -->
                                                    <div class="remove-item mt-2">
                                                        <?php
                                                        echo apply_filters(
                                                            'woocommerce_cart_item_remove_link',
                                                            sprintf(
                                                                '<a href="%s" class="remove text-red-600 hover:text-red-800 transition-colors" aria-label="%s">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                </a>',
                                                                esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                                esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($_product->get_name())))
                                                            ),
                                                            $cart_item_key
                                                        );
                                                        ?>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                                
                                <?php do_action('woocommerce_after_cart_table'); ?>
                                
                            </div>
                            
                        </div>
                        
                        <!-- Cart Totals -->
                        <div class="cart-totals lg:col-span-1">
                            <div class="cart-totals-wrapper bg-neutral-50 dark:bg-neutral-800 rounded-lg p-6 sticky top-4">
                                
                                <?php do_action('woocommerce_before_cart_totals'); ?>
                                
                                <h3 class="text-lg font-semibold mb-6"><?php esc_html_e('Order Summary', 'aqualuxe'); ?></h3>
                                
                                <div class="cart-totals-content">
                                    <?php do_action('woocommerce_cart_totals'); ?>
                                    
                                    <div class="proceed-to-checkout mt-6">
                                        <?php do_action('woocommerce_proceed_to_checkout'); ?>
                                    </div>
                                </div>
                                
                                <?php do_action('woocommerce_after_cart_totals'); ?>
                                
                            </div>
                        </div>
                        
                    </div>
                    
                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                    
                </form>
                
            <?php endif; ?>
            
        </div>

        <?php do_action('woocommerce_after_cart'); ?>
        
    </div>
</div>

<?php get_footer('shop'); ?>