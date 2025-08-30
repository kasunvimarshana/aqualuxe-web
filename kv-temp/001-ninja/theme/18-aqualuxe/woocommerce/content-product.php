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

// Get the product view mode (grid or list)
$view_mode = isset($_COOKIE['aqualuxe_product_view']) ? sanitize_text_field($_COOKIE['aqualuxe_product_view']) : 'grid';
$view_class = $view_mode === 'list' ? 'product-list-view' : 'product-grid-view';

// Get the column count
$columns = wc_get_loop_prop('columns');
$column_class = 'md:w-1/2 lg:w-1/' . $columns;
?>
<li <?php wc_product_class('mb-8 ' . $view_class . ' ' . ($view_mode === 'grid' ? $column_class : 'w-full'), $product); ?>>
    <div class="product-card bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden transition-transform duration-300 hover:-translate-y-1 h-full flex <?php echo $view_mode === 'list' ? 'flex-row' : 'flex-col'; ?>">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>

        <div class="product-image relative overflow-hidden <?php echo $view_mode === 'list' ? 'w-1/3' : 'w-full'; ?>">
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
            
            <?php if (function_exists('aqualuxe_product_hover_image')) : ?>
                <?php aqualuxe_product_hover_image(); ?>
            <?php endif; ?>
            
            <div class="product-actions absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/60 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex justify-center space-x-2">
                <?php if (function_exists('aqualuxe_quickview_button')) : ?>
                    <?php aqualuxe_quickview_button(); ?>
                <?php endif; ?>
                
                <?php if (function_exists('aqualuxe_wishlist_button')) : ?>
                    <?php aqualuxe_wishlist_button(); ?>
                <?php endif; ?>
                
                <?php if (function_exists('aqualuxe_compare_button')) : ?>
                    <?php aqualuxe_compare_button(); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-content p-4 flex flex-col <?php echo $view_mode === 'list' ? 'w-2/3' : 'w-full'; ?>">
            <?php
            // Display product categories
            echo wc_get_product_category_list($product->get_id(), ', ', '<div class="product-categories text-xs text-gray-500 dark:text-gray-400 mb-1">', '</div>');
            ?>
            
            <h2 class="product-title text-lg font-bold mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                    <?php the_title(); ?>
                </a>
            </h2>
            
            <?php if ($view_mode === 'list' && $product->get_short_description()) : ?>
                <div class="product-excerpt text-gray-600 dark:text-gray-300 mb-4">
                    <?php echo wp_trim_words($product->get_short_description(), 20, '...'); ?>
                </div>
            <?php endif; ?>
            
            <?php
            /**
             * Hook: woocommerce_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_product_title - 10
             */
            remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
            do_action('woocommerce_shop_loop_item_title');
            ?>

            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action('woocommerce_after_shop_loop_item_title');
            ?>
            
            <div class="product-meta mt-auto pt-4">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item.
                 *
                 * @hooked woocommerce_template_loop_product_link_close - 5
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
                do_action('woocommerce_after_shop_loop_item');
                ?>
            </div>
        </div>
    </div>
</li>