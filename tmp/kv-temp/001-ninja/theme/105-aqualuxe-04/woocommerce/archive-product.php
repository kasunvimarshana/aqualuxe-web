<?php
/**
 * Shop Page Template
 * 
 * Custom shop layout for AquaLuxe theme featuring luxury aquatic products
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop'); ?>

<div class="woocommerce-shop-wrapper">
    
    <!-- Shop Header -->
    <div class="shop-header bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-800 dark:to-gray-900 py-12">
        <div class="container mx-auto px-4">
            
            <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                <h1 class="woocommerce-products-header__title page-title text-4xl font-bold text-center mb-6 text-gray-900 dark:text-white">
                    <?php woocommerce_page_title(); ?>
                </h1>
            <?php endif; ?>

            <?php
            /**
             * Hook: woocommerce_archive_description
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action('woocommerce_archive_description');
            ?>

        </div>
    </div>

    <!-- Shop Content -->
    <div class="shop-content py-8">
        <div class="container mx-auto px-4">

            <?php
            /**
             * Hook: woocommerce_before_shop_loop
             *
             * @hooked woocommerce_output_all_notices - 10
             * @hooked woocommerce_result_count - 20
             * @hooked woocommerce_catalog_ordering - 30
             */
            do_action('woocommerce_before_shop_loop');
            ?>

            <div class="shop-layout flex flex-wrap -mx-4">
                
                <!-- Sidebar -->
                <aside class="shop-sidebar w-full lg:w-1/4 px-4 mb-8 lg:mb-0">
                    <div class="sidebar-content bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                        
                        <!-- Product Categories -->
                        <div class="widget-product-categories mb-8">
                            <h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                                <?php _e('Product Categories', 'aqualuxe'); ?>
                            </h3>
                            <?php
                            $categories = get_terms(array(
                                'taxonomy' => 'product_cat',
                                'hide_empty' => true,
                                'parent' => 0,
                            ));
                            
                            if ($categories) :
                                echo '<ul class="product-categories-list space-y-2">';
                                foreach ($categories as $category) :
                                    $category_link = get_term_link($category);
                                    echo '<li>';
                                    echo '<a href="' . esc_url($category_link) . '" class="flex items-center justify-between p-2 rounded hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors">';
                                    echo '<span class="text-gray-700 dark:text-gray-300">' . esc_html($category->name) . '</span>';
                                    echo '<span class="text-sm text-gray-500 dark:text-gray-400">(' . $category->count . ')</span>';
                                    echo '</a>';
                                    echo '</li>';
                                endforeach;
                                echo '</ul>';
                            endif;
                            ?>
                        </div>

                        <?php
                        /**
                         * Hook: woocommerce_sidebar
                         *
                         * @hooked woocommerce_get_sidebar - 10
                         */
                        do_action('woocommerce_sidebar');
                        ?>

                    </div>
                </aside>

                <!-- Products Grid -->
                <main class="shop-main w-full lg:w-3/4 px-4">
                    
                    <?php if (woocommerce_product_loop()) : ?>

                        <div class="products-grid">
                            
                            <?php
                            woocommerce_product_loop_start();

                            if (wc_get_loop_prop('is_shortcode')) {
                                $columns = absint(wc_get_loop_prop('columns'));
                            } else {
                                $columns = wc_get_default_products_per_row();
                            }
                            ?>

                            <div class="products-container grid gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                                
                                <?php
                                if (woocommerce_product_loop()) {
                                    while (have_posts()) {
                                        the_post();

                                        /**
                                         * Hook: woocommerce_shop_loop
                                         */
                                        do_action('woocommerce_shop_loop');

                                        wc_get_template_part('content', 'product');
                                    }
                                }
                                ?>

                            </div>

                            <?php
                            woocommerce_product_loop_end();
                            ?>

                        </div>

                        <?php
                        /**
                         * Hook: woocommerce_after_shop_loop
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action('woocommerce_after_shop_loop');
                        ?>

                    <?php else : ?>

                        <div class="no-products-found text-center py-12">
                            <div class="no-products-content">
                                <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">
                                    <?php _e('No products found', 'aqualuxe'); ?>
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    <?php _e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe'); ?>
                                </p>
                                <?php get_search_form(); ?>
                            </div>
                        </div>

                    <?php endif; ?>

                </main>

            </div>

        </div>
    </div>

</div>

<?php get_footer('shop');