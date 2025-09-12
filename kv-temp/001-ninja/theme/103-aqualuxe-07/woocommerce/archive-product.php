<?php
/**
 * WooCommerce Archive Product template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<div class="woocommerce-shop-wrapper">
    <div class="container mx-auto px-4 py-8">
        
        <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
            <header class="woocommerce-products-header">
                <h1 class="woocommerce-products-header__title page-title">
                    <?php woocommerce_page_title(); ?>
                </h1>
                
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
        <?php endif; ?>
        
        <div class="shop-layout grid gap-8 lg:grid-cols-4">
            
            <!-- Sidebar with filters -->
            <aside class="shop-sidebar lg:col-span-1">
                <?php
                /**
                 * Hook: woocommerce_sidebar.
                 *
                 * @hooked woocommerce_get_sidebar - 10
                 */
                do_action('woocommerce_sidebar');
                ?>
                
                <!-- Custom filters can be added here -->
                <div class="custom-filters mt-8">
                    <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
                    
                    <!-- Price filter -->
                    <div class="filter-section mb-6">
                        <h4 class="font-medium mb-3"><?php esc_html_e('Price Range', 'aqualuxe'); ?></h4>
                        <div class="price-filter">
                            <!-- Price filter widget will be inserted here via WooCommerce -->
                        </div>
                    </div>
                    
                    <!-- Category filter -->
                    <div class="filter-section mb-6">
                        <h4 class="font-medium mb-3"><?php esc_html_e('Categories', 'aqualuxe'); ?></h4>
                        <div class="category-filter">
                            <?php
                            $product_categories = get_terms([
                                'taxonomy' => 'product_cat',
                                'hide_empty' => true,
                                'parent' => 0,
                            ]);
                            
                            if ($product_categories) :
                                echo '<ul class="space-y-2">';
                                foreach ($product_categories as $category) :
                                    $category_link = get_term_link($category);
                                    echo '<li>';
                                    echo '<a href="' . esc_url($category_link) . '" class="text-neutral-600 hover:text-primary-600 transition-colors dark:text-neutral-300 dark:hover:text-primary-400">';
                                    echo esc_html($category->name);
                                    echo ' <span class="text-sm text-neutral-500">(' . $category->count . ')</span>';
                                    echo '</a>';
                                    echo '</li>';
                                endforeach;
                                echo '</ul>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- Main product area -->
            <main class="shop-main lg:col-span-3">
                
                <?php if (woocommerce_product_loop()) : ?>
                    
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
                    
                    <div class="shop-controls flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                        <div class="shop-view-toggle flex items-center space-x-2">
                            <button class="view-toggle grid-view active" data-view="grid" aria-label="<?php esc_attr_e('Grid View', 'aqualuxe'); ?>">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button class="view-toggle list-view" data-view="list" aria-label="<?php esc_attr_e('List View', 'aqualuxe'); ?>">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="shop-actions flex items-center space-x-4">
                            <!-- Products per page selector -->
                            <select class="products-per-page form-select text-sm" onchange="window.location.href=this.value">
                                <?php
                                $per_page_options = [12, 24, 48];
                                $current_per_page = wc_get_loop_prop('per_page');
                                
                                foreach ($per_page_options as $option) :
                                    $url = add_query_arg('per_page', $option);
                                    $selected = ($current_per_page == $option) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo esc_url($url); ?>" <?php echo $selected; ?>>
                                        <?php printf(esc_html__('Show %d', 'aqualuxe'), $option); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <?php woocommerce_product_loop_start(); ?>
                    
                    <?php
                    if (wc_get_loop_prop('is_shortcode')) {
                        $columns = absint(wc_get_loop_prop('columns'));
                    } else {
                        $columns = wc_get_default_products_per_row();
                    }
                    ?>
                    
                    <?php while (have_posts()) : ?>
                        <?php
                        the_post();
                        
                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action('woocommerce_shop_loop');
                        
                        wc_get_template_part('content', 'product');
                        ?>
                    <?php endwhile; ?>
                    
                    <?php woocommerce_product_loop_end(); ?>
                    
                    <?php
                    /**
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action('woocommerce_after_shop_loop');
                    ?>
                    
                <?php else : ?>
                    
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

<?php get_footer('shop'); ?>