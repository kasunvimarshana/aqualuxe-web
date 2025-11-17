<?php
/**
 * WooCommerce Product Content Template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<li <?php wc_product_class('product-card group', $product); ?>>
    
    <div class="product-inner">
        
        <!-- Product Image -->
        <div class="product-image-wrapper relative">
            
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item.
             *
             * @hooked woocommerce_template_loop_product_link_open - 10
             */
            do_action('woocommerce_before_shop_loop_item');
            ?>
            
            <div class="product-image aspect-square overflow-hidden bg-neutral-100 dark:bg-neutral-700 rounded-lg">
                <?php
                /**
                 * Hook: woocommerce_before_shop_loop_item_title.
                 *
                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                 * @hooked woocommerce_template_loop_product_thumbnail - 10
                 */
                do_action('woocommerce_before_shop_loop_item_title');
                ?>
            </div>
            
            <!-- Product badges -->
            <div class="product-badges absolute top-2 left-2 flex flex-col space-y-1">
                <?php
                // Sale badge
                if ($product->is_on_sale()) :
                    echo '<span class="badge sale-badge bg-red-500 text-white text-xs px-2 py-1 rounded">' . esc_html__('Sale!', 'woocommerce') . '</span>';
                endif;
                
                // New badge (products created within last 30 days)
                $created = strtotime($product->get_date_created());
                if ((time() - $created) < (30 * 24 * 60 * 60)) :
                    echo '<span class="badge new-badge bg-green-500 text-white text-xs px-2 py-1 rounded">' . esc_html__('New!', 'aqualuxe') . '</span>';
                endif;
                
                // Out of stock badge
                if (!$product->is_in_stock()) :
                    echo '<span class="badge stock-badge bg-gray-500 text-white text-xs px-2 py-1 rounded">' . esc_html__('Out of Stock', 'woocommerce') . '</span>';
                endif;
                ?>
            </div>
            
            <!-- Quick actions -->
            <div class="product-actions absolute top-2 right-2 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity">
                
                <!-- Quick view -->
                <button 
                    class="quick-view-btn bg-white dark:bg-neutral-800 p-2 rounded-full shadow-md hover:bg-primary-50 dark:hover:bg-primary-900 transition-colors"
                    data-quick-view
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                    aria-label="<?php esc_attr_e('Quick View', 'aqualuxe'); ?>"
                >
                    <svg class="w-4 h-4 text-neutral-600 dark:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
                
                <!-- Wishlist -->
                <button 
                    class="wishlist-btn bg-white dark:bg-neutral-800 p-2 rounded-full shadow-md hover:bg-primary-50 dark:hover:bg-primary-900 transition-colors"
                    data-wishlist
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                    aria-label="<?php esc_attr_e('Add to Wishlist', 'aqualuxe'); ?>"
                >
                    <svg class="w-4 h-4 text-neutral-600 dark:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
                
                <!-- Compare -->
                <button 
                    class="compare-btn bg-white dark:bg-neutral-800 p-2 rounded-full shadow-md hover:bg-primary-50 dark:hover:bg-primary-900 transition-colors"
                    data-compare
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                    aria-label="<?php esc_attr_e('Add to Compare', 'aqualuxe'); ?>"
                >
                    <svg class="w-4 h-4 text-neutral-600 dark:text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </button>
                
            </div>
            
        </div>
        
        <!-- Product Info -->
        <div class="product-info p-4">
            
            <?php
            /**
             * Hook: woocommerce_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_product_title - 10
             */
            do_action('woocommerce_shop_loop_item_title');
            ?>
            
            <!-- Product excerpt -->
            <?php if ($product->get_short_description()) : ?>
                <div class="product-excerpt text-sm text-neutral-600 dark:text-neutral-300 mb-3 line-clamp-2">
                    <?php echo wp_trim_words($product->get_short_description(), 15); ?>
                </div>
            <?php endif; ?>
            
            <!-- Product rating -->
            <?php if (wc_review_ratings_enabled()) : ?>
                <div class="product-rating mb-3">
                    <?php
                    $rating_count = $product->get_rating_count();
                    $review_count = $product->get_review_count();
                    $average      = $product->get_average_rating();
                    
                    if ($rating_count > 0) :
                    ?>
                        <div class="star-rating flex items-center space-x-1">
                            <?php echo wc_get_rating_html($average, $rating_count); ?>
                            <span class="rating-count text-sm text-neutral-500">
                                (<?php printf(_n('%s review', '%s reviews', $review_count, 'woocommerce'), $review_count); ?>)
                            </span>
                        </div>
                    <?php else : ?>
                        <div class="no-rating text-sm text-neutral-400">
                            <?php esc_html_e('No reviews yet', 'woocommerce'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Product price -->
            <div class="product-price mb-4">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 * @hooked woocommerce_template_loop_price - 10
                 */
                do_action('woocommerce_after_shop_loop_item_title');
                ?>
            </div>
            
            <!-- Product attributes/variations preview -->
            <?php if ($product->is_type('variable')) : ?>
                <div class="product-variations mb-4">
                    <?php
                    $available_variations = $product->get_available_variations();
                    $attributes = $product->get_variation_attributes();
                    
                    foreach ($attributes as $attribute_name => $options) :
                        if (count($options) <= 4) : // Only show if 4 or fewer options
                            $attribute_label = wc_attribute_label($attribute_name);
                    ?>
                            <div class="variation-preview mb-2">
                                <span class="attribute-label text-xs font-medium text-neutral-600 dark:text-neutral-300">
                                    <?php echo esc_html($attribute_label); ?>:
                                </span>
                                <div class="attribute-options flex flex-wrap gap-1 mt-1">
                                    <?php foreach (array_slice($options, 0, 4) as $option) : ?>
                                        <span class="option-preview text-xs bg-neutral-100 dark:bg-neutral-700 px-2 py-1 rounded">
                                            <?php echo esc_html($option); ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (count($options) > 4) : ?>
                                        <span class="more-options text-xs text-neutral-500">
                                            +<?php echo count($options) - 4; ?> more
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Add to cart -->
        <div class="product-actions-bottom p-4 pt-0">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item.
             *
             * @hooked woocommerce_template_loop_product_link_close - 5
             * @hooked woocommerce_template_loop_add_to_cart - 10
             */
            do_action('woocommerce_after_shop_loop_item');
            ?>
        </div>
        
    </div>
    
</li>