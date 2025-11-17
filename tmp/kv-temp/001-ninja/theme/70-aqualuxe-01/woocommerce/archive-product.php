<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');
?>

<div class="shop-header">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="shop-header-content">
                <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                    <h1 class="shop-title text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        <?php woocommerce_page_title(); ?>
                    </h1>
                <?php endif; ?>

                <?php
                /**
                 * Hook: woocommerce_archive_description.
                 *
                 * @hooked woocommerce_taxonomy_archive_description - 10
                 * @hooked woocommerce_product_archive_description - 10
                 */
                do_action('woocommerce_archive_description');
                ?>
            </div>

            <div class="shop-controls flex flex-wrap items-center gap-4">
                <!-- View Toggle -->
                <div class="view-toggle flex bg-gray-100 rounded-lg p-1">
                    <button class="view-toggle-btn active grid-view p-2 rounded text-gray-600 hover:text-gray-900" data-view="grid" aria-label="Grid View">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button class="view-toggle-btn list-view p-2 rounded text-gray-600 hover:text-gray-900" data-view="list" aria-label="List View">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                    </button>
                </div>

                <!-- Sort Dropdown -->
                <?php woocommerce_catalog_ordering(); ?>

                <!-- Results Count -->
                <?php woocommerce_result_count(); ?>
            </div>
        </div>
    </div>
</div>

<div class="shop-content">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="lg:w-1/4">
                <div class="shop-sidebar">
                    <?php if (is_active_sidebar('shop-sidebar')) : ?>
                        <?php dynamic_sidebar('shop-sidebar'); ?>
                    <?php else : ?>
                        <!-- Default filters -->
                        <div class="widget widget_product_categories">
                            <h3 class="widget-title text-lg font-semibold mb-4">Product Categories</h3>
                            <?php
                            woocommerce_product_subcategories([
                                'before' => '<ul class="product-categories space-y-2">',
                                'after' => '</ul>'
                            ]);
                            ?>
                        </div>

                        <?php the_widget('WC_Widget_Price_Filter'); ?>
                        <?php the_widget('WC_Widget_Layered_Nav', ['title' => 'Filter by Brand', 'attribute' => 'pa_brand']); ?>
                    <?php endif; ?>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="lg:w-3/4">
                <?php if (woocommerce_product_loop()) : ?>
                    <div class="products-header mb-6">
                        <?php
                        /**
                         * Hook: woocommerce_before_shop_loop.
                         *
                         * @hooked woocommerce_output_all_notices - 10
                         * @hooked woocommerce_result_count - 20
                         * @hooked woocommerce_catalog_ordering - 30
                         */
                        do_action('woocommerce_before_shop_loop');
                        ?>
                    </div>

                    <div id="products-container" class="products-grid">
                        <?php
                        woocommerce_product_loop_start();

                        if (wc_get_loop_prop('is_shortcode')) {
                            $columns = absint(wc_get_loop_prop('columns'));
                        } else {
                            $columns = wc_get_default_products_per_row();
                        }

                        while (have_posts()) {
                            the_post();

                            /**
                             * Hook: woocommerce_shop_loop.
                             */
                            do_action('woocommerce_shop_loop');

                            wc_get_template_part('content', 'product');
                        }

                        woocommerce_product_loop_end();
                        ?>
                    </div>

                    <div class="products-footer mt-8">
                        <?php
                        /**
                         * Hook: woocommerce_after_shop_loop.
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action('woocommerce_after_shop_loop');
                        ?>
                    </div>

                <?php else : ?>
                    <div class="no-products-found text-center py-12">
                        <div class="max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-2">No products found</h2>
                            <p class="text-gray-600 mb-6">Sorry, no products matched your search criteria.</p>
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
                                Continue Shopping
                            </a>
                        </div>
                    </div>

                    <?php
                    /**
                     * Hook: woocommerce_no_products_found.
                     *
                     * @hooked wc_no_products_found - 10
                     */
                    do_action('woocommerce_no_products_found');
                    ?>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
// do_action( 'woocommerce_sidebar' ); // We're using our own sidebar

get_footer('shop');
?>
