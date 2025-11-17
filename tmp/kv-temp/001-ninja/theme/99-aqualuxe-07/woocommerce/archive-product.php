<?php
/**
 * Shop page template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

<div class="shop-container">
    <div class="shop-header">
        <div class="container">
            <div class="shop-title-area">
                <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
                <?php if (woocommerce_get_page_id('shop') === get_queried_object_id()) : ?>
                    <div class="shop-description">
                        <?php
                        $shop_page = get_post(wc_get_page_id('shop'));
                        if ($shop_page && $shop_page->post_content) {
                            echo '<p>' . wp_kses_post($shop_page->post_content) . '</p>';
                        } else {
                            echo '<p>' . esc_html__('Discover our premium collection of aquatic products for all your underwater needs.', 'aqualuxe') . '</p>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="shop-controls">
                <div class="shop-controls-left">
                    <!-- Product count and view toggle -->
                    <div class="shop-view-toggle">
                        <button class="grid-toggle-btn active" data-columns="3" aria-label="<?php esc_attr_e('Grid view', 'aqualuxe'); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button class="grid-toggle-btn" data-columns="4" aria-label="<?php esc_attr_e('Compact grid view', 'aqualuxe'); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <?php
                    $total_products = wc_get_loop_prop('total');
                    if ($total_products) : ?>
                        <div class="shop-results-count">
                            <?php woocommerce_result_count(); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="shop-controls-right">
                    <!-- Sorting -->
                    <div class="shop-sorting">
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="shop-content">
        <div class="container">
            <div class="shop-layout">
                <!-- Sidebar with filters -->
                <aside class="shop-sidebar">
                    <div class="shop-filters">
                        <h3 class="filter-title"><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
                        
                        <!-- Category filter -->
                        <?php
                        $product_categories = get_terms([
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'parent' => 0
                        ]);
                        
                        if ($product_categories && !is_wp_error($product_categories)) : ?>
                            <div class="filter-group">
                                <h4 class="filter-group-title"><?php esc_html_e('Categories', 'aqualuxe'); ?></h4>
                                <div class="filter-options">
                                    <?php foreach ($product_categories as $category) : ?>
                                        <label class="filter-option">
                                            <input type="checkbox" class="category-filter" value="<?php echo esc_attr($category->term_id); ?>">
                                            <span class="filter-label"><?php echo esc_html($category->name); ?></span>
                                            <span class="filter-count">(<?php echo esc_html($category->count); ?>)</span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Price filter -->
                        <div class="filter-group">
                            <h4 class="filter-group-title"><?php esc_html_e('Price Range', 'aqualuxe'); ?></h4>
                            <div class="price-filter">
                                <div class="price-inputs">
                                    <input type="number" class="price-range-min" placeholder="<?php esc_attr_e('Min', 'aqualuxe'); ?>" min="0">
                                    <span class="price-separator">-</span>
                                    <input type="number" class="price-range-max" placeholder="<?php esc_attr_e('Max', 'aqualuxe'); ?>" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Stock status filter -->
                        <div class="filter-group">
                            <h4 class="filter-group-title"><?php esc_html_e('Availability', 'aqualuxe'); ?></h4>
                            <div class="filter-options">
                                <label class="filter-option">
                                    <input type="checkbox" class="stock-filter" value="instock">
                                    <span class="filter-label"><?php esc_html_e('In Stock', 'aqualuxe'); ?></span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" class="stock-filter" value="onbackorder">
                                    <span class="filter-label"><?php esc_html_e('On Backorder', 'aqualuxe'); ?></span>
                                </label>
                            </div>
                        </div>

                        <!-- Clear filters -->
                        <div class="filter-actions">
                            <button type="button" class="clear-filters-btn">
                                <?php esc_html_e('Clear All Filters', 'aqualuxe'); ?>
                            </button>
                        </div>
                    </div>
                </aside>

                <!-- Main products area -->
                <main class="shop-main">
                    <?php if (woocommerce_product_loop()) : ?>
                        
                        <?php woocommerce_product_loop_start(); ?>

                        <div class="products-grid grid-cols-3">
                            <?php
                            if (wc_get_loop_prop('is_shortcode')) {
                                $columns = absint(wc_get_loop_prop('columns'));
                            } else {
                                $columns = wc_get_default_products_per_row();
                            }

                            while (have_posts()) :
                                the_post();
                                
                                /**
                                 * Hook: woocommerce_shop_loop.
                                 */
                                do_action('woocommerce_shop_loop');

                                wc_get_template_part('content', 'product');
                            endwhile;
                            ?>
                        </div>

                        <?php woocommerce_product_loop_end(); ?>

                        <!-- Load more button -->
                        <?php if (get_next_posts_link()) : ?>
                            <div class="shop-load-more">
                                <button class="load-more-products button" data-page="1">
                                    <?php esc_html_e('Load More Products', 'aqualuxe'); ?>
                                </button>
                            </div>
                        <?php endif; ?>

                        <?php
                        /**
                         * Hook: woocommerce_after_shop_loop.
                         */
                        do_action('woocommerce_after_shop_loop');
                        ?>

                    <?php else : ?>
                        
                        <div class="no-products-found">
                            <div class="no-products-content">
                                <h2><?php esc_html_e('No products found', 'aqualuxe'); ?></h2>
                                <p><?php esc_html_e('We couldn\'t find any products matching your criteria. Try adjusting your filters or browse our categories.', 'aqualuxe'); ?></p>
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button">
                                    <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>

                        <?php
                        /**
                         * Hook: woocommerce_no_products_found.
                         */
                        do_action('woocommerce_no_products_found');
                        ?>

                    <?php endif; ?>

                    <?php
                    /**
                     * Hook: woocommerce_after_main_content.
                     */
                    do_action('woocommerce_after_main_content');
                    ?>
                </main>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Hook: woocommerce_sidebar.
 */
// do_action('woocommerce_sidebar'); // We're using our custom sidebar

get_footer('shop');
?>