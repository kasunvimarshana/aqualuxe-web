<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * @package AquaLuxe
 * @since 1.0.0
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
<main id="main" class="site-main flex-1" role="main">
    <div class="container mx-auto px-4 py-8 lg:py-12">
        
        <header class="woocommerce-products-header mb-8">
            <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                <h1 class="woocommerce-products-header__title page-title text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-100 mb-4">
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
        </header>
        
        <div class="woocommerce-content">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Sidebar -->
                <aside class="woocommerce-sidebar lg:col-span-1" role="complementary">
                    <?php if (is_active_sidebar('shop-sidebar')) : ?>
                        <div class="shop-sidebar space-y-6">
                            <?php dynamic_sidebar('shop-sidebar'); ?>
                        </div>
                    <?php else : ?>
                        <div class="default-shop-filters space-y-6">
                            <!-- Product Categories -->
                            <div class="filter-group">
                                <h3 class="filter-title text-lg font-semibold text-secondary-900 dark:text-secondary-100 mb-4">
                                    <?php esc_html_e('Categories', 'aqualuxe'); ?>
                                </h3>
                                <?php
                                wp_list_categories(array(
                                    'taxonomy' => 'product_cat',
                                    'hide_empty' => true,
                                    'show_count' => true,
                                    'title_li' => '',
                                    'walker' => new AquaLuxe_Product_Category_Walker(),
                                ));
                                ?>
                            </div>
                            
                            <!-- Price Filter -->
                            <div class="filter-group">
                                <h3 class="filter-title text-lg font-semibold text-secondary-900 dark:text-secondary-100 mb-4">
                                    <?php esc_html_e('Filter by Price', 'aqualuxe'); ?>
                                </h3>
                                <?php the_widget('WC_Widget_Price_Filter'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </aside>
                
                <!-- Main Content -->
                <div class="woocommerce-main lg:col-span-3">
                    
                    <?php if (woocommerce_product_loop()) : ?>
                        
                        <!-- Toolbar -->
                        <div class="woocommerce-toolbar flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 p-4 bg-secondary-50 dark:bg-secondary-800 rounded-lg">
                            <div class="woocommerce-result-count">
                                <?php
                                /**
                                 * Hook: woocommerce_before_shop_loop.
                                 *
                                 * @hooked woocommerce_output_all_notices - 10
                                 * @hooked woocommerce_result_count - 20
                                 */
                                do_action('woocommerce_before_shop_loop');
                                ?>
                            </div>
                            
                            <div class="woocommerce-ordering">
                                <?php
                                /**
                                 * Hook: woocommerce_before_shop_loop.
                                 *
                                 * @hooked woocommerce_catalog_ordering - 30
                                 */
                                woocommerce_catalog_ordering();
                                ?>
                            </div>
                        </div>
                        
                        <!-- Products Loop -->
                        <div class="woocommerce-products-wrapper">
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
                        
                        <!-- Pagination -->
                        <div class="woocommerce-pagination mt-8">
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
                        
                        <!-- No Products Found -->
                        <div class="woocommerce-no-products-found text-center py-12">
                            <div class="max-w-md mx-auto">
                                <div class="no-products-icon mb-6">
                                    <svg class="w-16 h-16 mx-auto text-secondary-400 dark:text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M9 5l7 7m-7 0l7-7"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-secondary-900 dark:text-secondary-100 mb-4">
                                    <?php esc_html_e('No products found', 'aqualuxe'); ?>
                                </h2>
                                <p class="text-secondary-600 dark:text-secondary-400 mb-6">
                                    <?php esc_html_e('Sorry, no products were found matching your selection.', 'aqualuxe'); ?>
                                </p>
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-primary">
                                    <?php esc_html_e('View All Products', 'aqualuxe'); ?>
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
                </div>
            </div>
        </div>
    </div>
</main>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

get_footer('shop');