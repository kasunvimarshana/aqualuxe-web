<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<div <?php wc_product_class('product-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 group', $product); ?>>
    
    <div class="product-image-wrapper relative">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>
        
        <div class="product-thumbnail relative overflow-hidden">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
            
            <!-- Product badges -->
            <div class="product-badges absolute top-3 left-3 z-10">
                <?php if ($product->is_on_sale()) : ?>
                    <span class="sale-badge bg-accent-500 text-white px-2 py-1 text-xs font-medium rounded-full mb-2 block">
                        <?php _e('Sale', 'aqualuxe'); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($product->is_featured()) : ?>
                    <span class="featured-badge bg-primary-500 text-white px-2 py-1 text-xs font-medium rounded-full mb-2 block">
                        <?php _e('Featured', 'aqualuxe'); ?>
                    </span>
                <?php endif; ?>
                
                <?php if (!$product->is_in_stock()) : ?>
                    <span class="out-of-stock-badge bg-gray-500 text-white px-2 py-1 text-xs font-medium rounded-full">
                        <?php _e('Out of Stock', 'aqualuxe'); ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <!-- Quick actions overlay -->
            <div class="product-actions absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <div class="action-buttons flex space-x-2">
                    <!-- Quick view button -->
                    <button class="quick-view-btn btn btn-sm btn-primary" 
                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                            title="<?php _e('Quick View', 'aqualuxe'); ?>">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <!-- Wishlist button -->
                    <button class="wishlist-btn btn btn-sm btn-secondary" 
                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                            title="<?php _e('Add to Wishlist', 'aqualuxe'); ?>">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="product-content p-4">
        <?php
        /**
         * Hook: woocommerce_shop_loop_item_title.
         *
         * @hooked woocommerce_template_loop_product_title - 10
         */
        do_action('woocommerce_shop_loop_item_title');
        ?>

        <h3 class="product-title text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors duration-300">
                <?php the_title(); ?>
            </a>
        </h3>

        <!-- Product rating -->
        <div class="product-rating mb-2">
            <?php
            if ($rating_html = wc_get_rating_html($product->get_average_rating())) {
                echo $rating_html;
            } else {
                echo '<div class="star-rating text-gray-300">';
                for ($i = 1; $i <= 5; $i++) {
                    echo '<svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                }
                echo '</div>';
            }
            ?>
        </div>

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

        <!-- Product excerpt -->
        <?php if ($product->get_short_description()) : ?>
            <div class="product-excerpt text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                <?php echo wp_kses_post($product->get_short_description()); ?>
            </div>
        <?php endif; ?>

        <!-- Add to cart button -->
        <div class="product-add-to-cart">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item.
             *
             * @hooked woocommerce_template_loop_add_to_cart - 10
             */
            do_action('woocommerce_after_shop_loop_item');
            ?>
        </div>
    </div>

    <?php
    /**
     * Hook: woocommerce_after_shop_loop_item.
     *
     * This hook is already called above but we need to close the link.
     */
    ?>

</div>