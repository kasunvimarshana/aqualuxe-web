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

// Get shop display options
$shop_layout = get_theme_mod('aqualuxe_shop_layout', 'grid');
$show_rating = get_theme_mod('aqualuxe_show_product_rating', true);
$show_price = get_theme_mod('aqualuxe_show_product_price', true);
$show_categories = get_theme_mod('aqualuxe_show_product_categories', true);
$show_stock = get_theme_mod('aqualuxe_show_product_stock', true);
$enable_quick_view = get_theme_mod('aqualuxe_enable_quick_view', true);
$enable_wishlist = get_theme_mod('aqualuxe_enable_wishlist', true);
$enable_compare = get_theme_mod('aqualuxe_enable_compare', true);

// Additional classes for product items
$classes = array('bg-white', 'dark:bg-gray-700', 'rounded-lg', 'overflow-hidden', 'shadow-md', 'hover:shadow-lg', 'transition-shadow');

// Add list-item class if in list view
if ($shop_layout === 'list') {
    $classes[] = 'list-item';
}
?>
<li <?php wc_product_class($classes, $product); ?>>
    <div class="product-inner h-full flex flex-col">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>

        <div class="product-thumbnail relative">
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
            
            <?php if ($product->is_on_sale()) : ?>
                <span class="onsale absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                    <?php esc_html_e('Sale!', 'aqualuxe'); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($product->is_featured()) : ?>
                <span class="featured absolute top-2 right-2 bg-primary-500 text-white text-xs font-bold px-2 py-1 rounded">
                    <?php esc_html_e('Featured', 'aqualuxe'); ?>
                </span>
            <?php endif; ?>
            
            <div class="product-actions absolute bottom-0 left-0 right-0 p-2 bg-black bg-opacity-50 flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <?php if ($enable_quick_view) : ?>
                    <button class="quick-view-btn p-2 bg-white hover:bg-gray-100 rounded-full text-gray-800" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Quick view', 'aqualuxe'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                <?php endif; ?>
                
                <?php if ($enable_wishlist && function_exists('YITH_WCWL')) : ?>
                    <div class="wishlist-btn">
                        <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($enable_compare && function_exists('YITH_WOOCOMPARE')) : ?>
                    <div class="compare-btn">
                        <?php echo do_shortcode('[yith_compare_button]'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-details p-4 flex-grow flex flex-col">
            <?php if ($show_categories) : ?>
                <div class="product-categories text-xs text-gray-500 dark:text-gray-400 mb-1">
                    <?php echo wc_get_product_category_list($product->get_id(), ', '); ?>
                </div>
            <?php endif; ?>
            
            <h2 class="woocommerce-loop-product__title text-lg font-bold mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                    <?php the_title(); ?>
                </a>
            </h2>
            
            <?php if ($show_rating && wc_review_ratings_enabled()) : ?>
                <div class="product-rating mb-2">
                    <?php woocommerce_template_loop_rating(); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($show_price) : ?>
                <div class="product-price mb-3 text-primary-600 dark:text-primary-400 font-medium">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($show_stock) : ?>
                <div class="product-stock mb-3">
                    <?php if ($product->is_in_stock()) : ?>
                        <span class="in-stock text-sm text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <?php esc_html_e('In Stock', 'aqualuxe'); ?>
                        </span>
                    <?php else : ?>
                        <span class="out-of-stock text-sm text-red-600 dark:text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <?php esc_html_e('Out of Stock', 'aqualuxe'); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($shop_layout === 'list') : ?>
                <div class="product-description mb-4 text-gray-600 dark:text-gray-300">
                    <?php echo wp_trim_words($product->get_short_description(), 30); ?>
                </div>
            <?php endif; ?>
            
            <div class="product-add-to-cart mt-auto">
                <?php woocommerce_template_loop_add_to_cart(array(
                    'class' => 'w-full text-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors',
                )); ?>
            </div>
        </div>

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
</li>