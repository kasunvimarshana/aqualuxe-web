<?php
/**
 * WooCommerce Product Archive Template
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <div class="woocommerce-shop max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Page Header -->
            <header class="shop-header mb-8">
                <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                    <h1 class="shop-title text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php woocommerce_page_title(); ?>
                    </h1>
                <?php endif; ?>
                
                <?php
                /**
                 * Archive description
                 */
                do_action('woocommerce_archive_description');
                ?>
            </header>
            
            <?php if (woocommerce_product_loop()) : ?>
                
                <!-- Shop Toolbar -->
                <div class="shop-toolbar flex flex-col md:flex-row justify-between items-center mb-8 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                    
                    <!-- Result Count -->
                    <div class="result-count mb-4 md:mb-0">
                        <?php woocommerce_result_count(); ?>
                    </div>
                    
                    <!-- Catalog Ordering -->
                    <div class="catalog-ordering">
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                    
                </div>
                
                <!-- Product Loop Start -->
                <?php woocommerce_product_loop_start(); ?>
                
                <div class="products-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    
                    <?php
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
                    ?>
                    
                </div>
                
                <!-- Product Loop End -->
                <?php woocommerce_product_loop_end(); ?>
                
                <!-- Pagination -->
                <div class="shop-pagination mt-12">
                    <?php woocommerce_pagination(); ?>
                </div>
                
            <?php else : ?>
                
                <!-- No Products Found -->
                <div class="no-products text-center py-16">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('No products found', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe'); ?>
                    </p>
                    <div class="max-w-md mx-auto">
                        <?php get_product_search_form(); ?>
                    </div>
                </div>
                
            <?php endif; ?>
            
        </div>
        
    </main>
</div>

<?php get_footer('shop'); ?>