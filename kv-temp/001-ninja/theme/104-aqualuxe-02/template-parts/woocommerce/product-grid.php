<?php
/**
 * Template part for displaying product grid
 *
 * @package AquaLuxe
 */

if (!aqualuxe_is_woocommerce_active()) {
    return;
}

// Get products query args
$args = isset($args) ? $args : array();
$default_args = array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => 8,
    'meta_query'     => array(
        array(
            'key'     => '_visibility',
            'value'   => array('catalog', 'visible'),
            'compare' => 'IN'
        )
    )
);

$query_args = wp_parse_args($args, $default_args);
$products = new WP_Query($query_args);

if (!$products->have_posts()) {
    return;
}

$grid_columns = isset($grid_columns) ? $grid_columns : 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4';
$show_filters = isset($show_filters) ? $show_filters : false;
?>

<div class="product-grid-wrapper">
    
    <?php if ($show_filters) : ?>
        <!-- Product Filters -->
        <div class="product-filters mb-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                
                <!-- Category Filter -->
                <div class="filter-group">
                    <label for="product-category-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <?php esc_html_e('Category', 'aqualuxe'); ?>
                    </label>
                    <select id="product-category-filter" class="form-select">
                        <option value=""><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                        ));
                        
                        if (!is_wp_error($categories)) :
                            foreach ($categories as $category) :
                                ?>
                                <option value="<?php echo esc_attr($category->slug); ?>">
                                    <?php echo esc_html($category->name); ?>
                                </option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                
                <!-- Price Filter -->
                <div class="filter-group">
                    <label for="product-price-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <?php esc_html_e('Sort by Price', 'aqualuxe'); ?>
                    </label>
                    <select id="product-price-filter" class="form-select">
                        <option value=""><?php esc_html_e('Default', 'aqualuxe'); ?></option>
                        <option value="price-low"><?php esc_html_e('Price: Low to High', 'aqualuxe'); ?></option>
                        <option value="price-high"><?php esc_html_e('Price: High to Low', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <!-- View Toggle -->
                <div class="view-toggle flex items-center space-x-2">
                    <button class="view-grid active p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 rounded" data-view="grid">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button class="view-list p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 rounded" data-view="list">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Products Grid -->
    <div class="products-grid grid <?php echo esc_attr($grid_columns); ?> gap-6" data-view="grid">
        <?php while ($products->have_posts()) : $products->the_post(); ?>
            <?php aqualuxe_get_template_part('woocommerce/product-card'); ?>
        <?php endwhile; ?>
    </div>
    
    <!-- Load More / Pagination -->
    <?php if ($products->max_num_pages > 1) : ?>
        <div class="product-pagination mt-8 text-center">
            <button 
                class="load-more-products btn btn-outline"
                data-page="1"
                data-max-pages="<?php echo esc_attr($products->max_num_pages); ?>"
            >
                <?php esc_html_e('Load More Products', 'aqualuxe'); ?>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
        </div>
    <?php endif; ?>
</div>

<?php
wp_reset_postdata();
?>