<?php
/**
 * WooCommerce shop page template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<main id="main" class="site-main shop-main" role="main">
    
    <!-- Shop Hero Section -->
    <section class="shop-hero bg-gradient-to-r from-primary-600 to-secondary-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php
                    if (apply_filters('woocommerce_show_page_title', true)):
                        woocommerce_page_title();
                    endif;
                    ?>
                </h1>
                
                <?php if (get_theme_mod('shop_hero_description')): ?>
                    <p class="text-xl opacity-90 mb-8">
                        <?php echo esc_html(get_theme_mod('shop_hero_description')); ?>
                    </p>
                <?php endif; ?>
                
                <!-- Shop Stats -->
                <div class="shop-stats grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
                    <div class="stat-item">
                        <div class="stat-number text-2xl font-bold">
                            <?php echo wp_count_posts('product')->publish; ?>+
                        </div>
                        <div class="stat-label text-sm opacity-75">
                            <?php esc_html_e('Products', 'aqualuxe'); ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number text-2xl font-bold">
                            <?php echo count(get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true])); ?>+
                        </div>
                        <div class="stat-label text-sm opacity-75">
                            <?php esc_html_e('Categories', 'aqualuxe'); ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number text-2xl font-bold">
                            <?php echo get_theme_mod('shop_years_experience', '15'); ?>+
                        </div>
                        <div class="stat-label text-sm opacity-75">
                            <?php esc_html_e('Years Experience', 'aqualuxe'); ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number text-2xl font-bold">
                            <?php echo get_theme_mod('shop_happy_customers', '10k'); ?>+
                        </div>
                        <div class="stat-label text-sm opacity-75">
                            <?php esc_html_e('Happy Customers', 'aqualuxe'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container mx-auto px-4 py-8">
        
        <!-- Breadcrumbs -->
        <?php
        if (function_exists('woocommerce_breadcrumb')):
            woocommerce_breadcrumb();
        endif;
        ?>
        
        <!-- Shop Notices -->
        <?php
        if (function_exists('woocommerce_output_all_notices')):
            woocommerce_output_all_notices();
        endif;
        ?>
        
        <div class="grid lg:grid-cols-4 gap-8">
            
            <!-- Shop Sidebar -->
            <div class="lg:col-span-1">
                <div class="shop-sidebar space-y-8">
                    
                    <!-- Search Products -->
                    <div class="widget product-search card p-6">
                        <h3 class="widget-title text-lg font-semibold mb-4">
                            <?php esc_html_e('Search Products', 'aqualuxe'); ?>
                        </h3>
                        <?php the_widget('WC_Widget_Product_Search'); ?>
                    </div>
                    
                    <!-- Product Categories -->
                    <div class="widget product-categories card p-6">
                        <h3 class="widget-title text-lg font-semibold mb-4">
                            <?php esc_html_e('Product Categories', 'aqualuxe'); ?>
                        </h3>
                        <?php the_widget('WC_Widget_Product_Categories'); ?>
                    </div>
                    
                    <!-- Price Filter -->
                    <div class="widget price-filter card p-6">
                        <h3 class="widget-title text-lg font-semibold mb-4">
                            <?php esc_html_e('Filter by Price', 'aqualuxe'); ?>
                        </h3>
                        <?php the_widget('WC_Widget_Price_Filter'); ?>
                    </div>
                    
                    <!-- Product Tags -->
                    <div class="widget product-tags card p-6">
                        <h3 class="widget-title text-lg font-semibold mb-4">
                            <?php esc_html_e('Product Tags', 'aqualuxe'); ?>
                        </h3>
                        <?php the_widget('WC_Widget_Product_Tag_Cloud'); ?>
                    </div>
                    
                    <!-- On Sale Products -->
                    <div class="widget sale-products card p-6">
                        <h3 class="widget-title text-lg font-semibold mb-4">
                            <?php esc_html_e('On Sale', 'aqualuxe'); ?>
                        </h3>
                        <?php
                        the_widget('WC_Widget_Products', [
                            'title' => '',
                            'show' => 'onsale',
                            'number' => 3
                        ]);
                        ?>
                    </div>
                    
                </div>
            </div>
            
            <!-- Shop Content -->
            <div class="lg:col-span-3">
                
                <!-- Shop Toolbar -->
                <div class="shop-toolbar flex flex-wrap items-center justify-between gap-4 mb-8 p-4 bg-gray-50 dark:bg-dark-800 rounded-lg">
                    
                    <!-- Results Count -->
                    <div class="results-count">
                        <?php
                        if (function_exists('woocommerce_result_count')):
                            woocommerce_result_count();
                        endif;
                        ?>
                    </div>
                    
                    <!-- Catalog Ordering -->
                    <div class="catalog-ordering">
                        <?php
                        if (function_exists('woocommerce_catalog_ordering')):
                            woocommerce_catalog_ordering();
                        endif;
                        ?>
                    </div>
                    
                    <!-- View Toggle -->
                    <div class="view-toggle">
                        <div class="btn-group" role="group" aria-label="<?php esc_attr_e('View toggle', 'aqualuxe'); ?>">
                            <button type="button" class="btn btn-outline btn-sm active" data-view="grid" aria-pressed="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                                <span class="sr-only"><?php esc_html_e('Grid view', 'aqualuxe'); ?></span>
                            </button>
                            <button type="button" class="btn btn-outline btn-sm" data-view="list" aria-pressed="false">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                <span class="sr-only"><?php esc_html_e('List view', 'aqualuxe'); ?></span>
                            </button>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Products Loop -->
                <?php if (woocommerce_product_loop()): ?>
                    
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
                    
                    <div class="products-grid" data-view="grid">
                        <?php
                        woocommerce_product_loop_start();
                        
                        if (wc_get_loop_prop('is_shortcode')):
                            $columns = absint(wc_get_loop_prop('columns'));
                        else:
                            $columns = wc_get_default_products_per_row();
                        endif;
                        
                        while (have_posts()):
                            the_post();
                            
                            /**
                             * Hook: woocommerce_shop_loop.
                             */
                            do_action('woocommerce_shop_loop');
                            
                            wc_get_template_part('content', 'product');
                            
                        endwhile;
                        
                        woocommerce_product_loop_end();
                        ?>
                    </div>
                    
                    <?php
                    /**
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action('woocommerce_after_shop_loop');
                    ?>
                    
                <?php else: ?>
                    
                    <!-- No Products Found -->
                    <div class="no-products text-center py-16">
                        <div class="max-w-lg mx-auto">
                            <div class="mb-8">
                                <svg class="w-24 h-24 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('No products found', 'aqualuxe'); ?></h2>
                            <p class="text-gray-600 dark:text-gray-400 mb-8">
                                <?php esc_html_e('Sorry, but we couldn\'t find any products matching your criteria. Try adjusting your filters or browse our categories.', 'aqualuxe'); ?>
                            </p>
                            
                            <!-- Quick Actions -->
                            <div class="space-y-4">
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary btn-lg">
                                    <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                                </a>
                                
                                <!-- Popular Categories -->
                                <?php
                                $popular_categories = get_terms([
                                    'taxonomy' => 'product_cat',
                                    'orderby' => 'count',
                                    'order' => 'DESC',
                                    'number' => 6,
                                    'hide_empty' => true
                                ]);
                                
                                if ($popular_categories && !is_wp_error($popular_categories)):
                                ?>
                                    <div class="popular-categories mt-8">
                                        <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Popular Categories', 'aqualuxe'); ?></h3>
                                        <div class="flex flex-wrap justify-center gap-2">
                                            <?php foreach ($popular_categories as $category): ?>
                                                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="btn btn-outline btn-sm">
                                                    <?php echo esc_html($category->name); ?>
                                                    <span class="ml-1 text-xs opacity-75">(<?php echo esc_html($category->count); ?>)</span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
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
    
    <!-- Featured Brands Section -->
    <?php if (get_theme_mod('show_shop_brands', true)): ?>
        <section class="featured-brands py-16 bg-gray-50 dark:bg-dark-800">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4"><?php esc_html_e('Featured Brands', 'aqualuxe'); ?></h2>
                    <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                        <?php esc_html_e('We partner with the most trusted names in the aquatic industry to bring you quality products.', 'aqualuxe'); ?>
                    </p>
                </div>
                
                <div class="brands-grid grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
                    <?php
                    $brands = get_theme_mod('shop_brands', []);
                    if (is_array($brands) && !empty($brands)):
                        foreach (array_slice($brands, 0, 12) as $brand):
                            if (!empty($brand['logo']) && !empty($brand['name'])):
                    ?>
                        <div class="brand-item">
                            <a href="<?php echo esc_url($brand['url'] ?? '#'); ?>" class="block p-4 bg-white dark:bg-dark-700 rounded-lg hover:shadow-lg transition-shadow">
                                <img src="<?php echo esc_url($brand['logo']); ?>" 
                                     alt="<?php echo esc_attr($brand['name']); ?>"
                                     class="w-full h-16 object-contain filter grayscale hover:grayscale-0 transition-all duration-300"
                                     loading="lazy">
                            </a>
                        </div>
                    <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
</main>

<?php
get_footer('shop');
?>
