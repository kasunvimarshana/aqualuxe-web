<?php
/**
 * Product Loop Content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<div <?php wc_product_class('product-item group relative bg-white dark:bg-secondary-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:transform hover:scale-105', $product); ?>>
    
    <!-- Product Image -->
    <div class="product-image relative overflow-hidden">
        <a href="<?php the_permalink(); ?>" class="block">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
        </a>
        
        <!-- Sale Badge -->
        <?php if ($product->is_on_sale()) : ?>
            <div class="sale-badge absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full z-10">
                <?php esc_html_e('Sale!', 'aqualuxe'); ?>
            </div>
        <?php endif; ?>
        
        <!-- Product Actions -->
        <div class="product-actions absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 space-y-2">
            
            <!-- Quick View -->
            <?php if (get_theme_mod('show_quick_view', true)) : ?>
                <button type="button" 
                        class="quick-view-btn btn-ghost btn-sm bg-white dark:bg-secondary-800 text-secondary-600 dark:text-secondary-400 hover:bg-primary-600 hover:text-white rounded-full p-2 shadow-md"
                        data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                        title="<?php esc_attr_e('Quick View', 'aqualuxe'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span class="screen-reader-text"><?php esc_html_e('Quick View', 'aqualuxe'); ?></span>
                </button>
            <?php endif; ?>
            
            <!-- Wishlist -->
            <button type="button" 
                    class="wishlist-btn btn-ghost btn-sm bg-white dark:bg-secondary-800 text-secondary-600 dark:text-secondary-400 hover:bg-red-600 hover:text-white rounded-full p-2 shadow-md"
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                    title="<?php esc_attr_e('Add to Wishlist', 'aqualuxe'); ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <span class="screen-reader-text"><?php esc_html_e('Add to Wishlist', 'aqualuxe'); ?></span>
            </button>
        </div>
        
        <!-- Out of Stock Overlay -->
        <?php if (!$product->is_in_stock()) : ?>
            <div class="out-of-stock-overlay absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <span class="text-white font-bold text-lg">
                    <?php esc_html_e('Out of Stock', 'aqualuxe'); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Product Info -->
    <div class="product-info p-4">
        
        <!-- Product Categories -->
        <?php
        $product_cats = wc_get_product_category_list($product->get_id(), ', ');
        if ($product_cats) :
        ?>
            <div class="product-categories text-xs text-secondary-500 dark:text-secondary-400 mb-2">
                <?php echo $product_cats; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        <?php endif; ?>
        
        <!-- Product Title -->
        <h3 class="product-title text-lg font-semibold text-secondary-900 dark:text-secondary-100 mb-2 line-clamp-2">
            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                <?php
                /**
                 * Hook: woocommerce_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_product_title - 10
                 */
                do_action('woocommerce_shop_loop_item_title');
                ?>
            </a>
        </h3>
        
        <!-- Product Rating -->
        <?php if (wc_review_ratings_enabled()) : ?>
            <div class="product-rating mb-2">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 */
                woocommerce_template_loop_rating();
                ?>
            </div>
        <?php endif; ?>
        
        <!-- Product Price -->
        <div class="product-price mb-4">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action('woocommerce_after_shop_loop_item_title');
            ?>
        </div>
        
        <!-- Product Excerpt -->
        <?php if ($product->get_short_description()) : ?>
            <div class="product-excerpt text-sm text-secondary-600 dark:text-secondary-400 mb-4 line-clamp-2">
                <?php echo wp_kses_post($product->get_short_description()); ?>
            </div>
        <?php endif; ?>
        
        <!-- Add to Cart Button -->
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
</div>