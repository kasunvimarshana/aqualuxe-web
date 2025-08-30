<?php
/**
 * WooCommerce Advanced Filters functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe WooCommerce Filters Class
 */
class AquaLuxe_WC_Filters {
    /**
     * Constructor
     */
    public function __construct() {
        // Add filter widget area
        add_action('widgets_init', array($this, 'register_filter_widget_area'));
        
        // Add filter sidebar
        add_action('woocommerce_before_shop_loop', array($this, 'add_filter_button'), 25);
        add_action('wp_footer', array($this, 'add_filter_sidebar'));
        
        // Add AJAX filter handlers
        add_action('wp_ajax_aqualuxe_filter_products', array($this, 'filter_products'));
        add_action('wp_ajax_nopriv_aqualuxe_filter_products', array($this, 'filter_products'));
        
        // Enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add price range filter
        add_action('aqualuxe_price_range_filter', array($this, 'price_range_filter'));
        
        // Add attribute filter
        add_action('aqualuxe_attribute_filter', array($this, 'attribute_filter'));
        
        // Add category filter
        add_action('aqualuxe_category_filter', array($this, 'category_filter'));
        
        // Add tag filter
        add_action('aqualuxe_tag_filter', array($this, 'tag_filter'));
        
        // Add rating filter
        add_action('aqualuxe_rating_filter', array($this, 'rating_filter'));
        
        // Add active filters
        add_action('aqualuxe_active_filters', array($this, 'active_filters'));
    }

    /**
     * Register filter widget area
     */
    public function register_filter_widget_area() {
        register_sidebar(array(
            'name' => __('Shop Filters', 'aqualuxe'),
            'id' => 'shop-filters',
            'description' => __('Add widgets here to appear in the shop filter sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title text-lg font-bold mb-4">',
            'after_title' => '</h3>',
        ));
    }

    /**
     * Add filter button
     */
    public function add_filter_button() {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        echo '<div class="filter-button-container flex items-center mb-6">';
        echo '<button id="filter-toggle" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />';
        echo '</svg>';
        echo esc_html__('Filter Products', 'aqualuxe');
        echo '</button>';
        
        // Active filters count
        $active_filters = $this->get_active_filters_count();
        if ($active_filters > 0) {
            echo '<span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary text-white">';
            echo esc_html($active_filters);
            echo '</span>';
        }
        
        echo '</div>';
    }

    /**
     * Add filter sidebar
     */
    public function add_filter_sidebar() {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        ?>
        <div id="filter-sidebar" class="filter-sidebar fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
            <div class="filter-sidebar-content bg-white dark:bg-gray-800 w-full max-w-md h-full overflow-y-auto ml-auto transition-colors duration-300">
                <div class="filter-sidebar-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold"><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
                    <button id="filter-close" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="filter-sidebar-body p-4">
                    <form id="product-filter-form" class="product-filter-form">
                        <?php
                        // Active filters
                        do_action('aqualuxe_active_filters');
                        
                        // Check if shop filters sidebar has widgets
                        if (is_active_sidebar('shop-filters')) {
                            dynamic_sidebar('shop-filters');
                        } else {
                            // Default filters
                            echo '<div class="filter-section mb-8">';
                            echo '<h4 class="text-lg font-bold mb-4">' . esc_html__('Price Range', 'aqualuxe') . '</h4>';
                            do_action('aqualuxe_price_range_filter');
                            echo '</div>';
                            
                            echo '<div class="filter-section mb-8">';
                            echo '<h4 class="text-lg font-bold mb-4">' . esc_html__('Product Categories', 'aqualuxe') . '</h4>';
                            do_action('aqualuxe_category_filter');
                            echo '</div>';
                            
                            // Attribute filters
                            $attribute_taxonomies = wc_get_attribute_taxonomies();
                            if ($attribute_taxonomies) {
                                foreach ($attribute_taxonomies as $attribute) {
                                    echo '<div class="filter-section mb-8">';
                                    echo '<h4 class="text-lg font-bold mb-4">' . esc_html($attribute->attribute_label) . '</h4>';
                                    do_action('aqualuxe_attribute_filter', $attribute->attribute_name);
                                    echo '</div>';
                                }
                            }
                            
                            echo '<div class="filter-section mb-8">';
                            echo '<h4 class="text-lg font-bold mb-4">' . esc_html__('Product Tags', 'aqualuxe') . '</h4>';
                            do_action('aqualuxe_tag_filter');
                            echo '</div>';
                            
                            echo '<div class="filter-section mb-8">';
                            echo '<h4 class="text-lg font-bold mb-4">' . esc_html__('Rating', 'aqualuxe') . '</h4>';
                            do_action('aqualuxe_rating_filter');
                            echo '</div>';
                        }
                        ?>
                        
                        <div class="filter-actions flex space-x-4 mt-8">
                            <button type="submit" class="flex-grow inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300">
                                <?php esc_html_e('Apply Filters', 'aqualuxe'); ?>
                            </button>
                            <button type="reset" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300">
                                <?php esc_html_e('Reset', 'aqualuxe'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Filter products
     */
    public function filter_products() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_filter_nonce')) {
            wp_send_json_error('Invalid nonce');
            exit;
        }
        
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => get_option('posts_per_page'),
            'paged' => isset($_POST['page']) ? absint($_POST['page']) : 1,
            'tax_query' => array(),
            'meta_query' => array(),
        );
        
        // Price range
        if (isset($_POST['min_price']) && isset($_POST['max_price'])) {
            $min_price = floatval($_POST['min_price']);
            $max_price = floatval($_POST['max_price']);
            
            if ($min_price > 0 || $max_price < PHP_INT_MAX) {
                $args['meta_query'][] = array(
                    'key' => '_price',
                    'value' => array($min_price, $max_price),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                );
            }
        }
        
        // Categories
        if (isset($_POST['categories']) && !empty($_POST['categories'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => array_map('absint', $_POST['categories']),
                'operator' => 'IN',
            );
        }
        
        // Tags
        if (isset($_POST['tags']) && !empty($_POST['tags'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field' => 'term_id',
                'terms' => array_map('absint', $_POST['tags']),
                'operator' => 'IN',
            );
        }
        
        // Attributes
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if ($attribute_taxonomies) {
            foreach ($attribute_taxonomies as $attribute) {
                $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
                $filter_key = 'attribute_' . $attribute->attribute_name;
                
                if (isset($_POST[$filter_key]) && !empty($_POST[$filter_key])) {
                    $args['tax_query'][] = array(
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => array_map('absint', $_POST[$filter_key]),
                        'operator' => 'IN',
                    );
                }
            }
        }
        
        // Rating
        if (isset($_POST['rating']) && !empty($_POST['rating'])) {
            $rating_filter = array('relation' => 'OR');
            
            foreach ($_POST['rating'] as $rating) {
                $rating_filter[] = array(
                    'key' => '_wc_average_rating',
                    'value' => array($rating - 0.5, $rating + 0.5),
                    'compare' => 'BETWEEN',
                    'type' => 'DECIMAL',
                );
            }
            
            $args['meta_query'][] = $rating_filter;
        }
        
        // Search
        if (isset($_POST['search']) && !empty($_POST['search'])) {
            $args['s'] = sanitize_text_field($_POST['search']);
        }
        
        // Ordering
        if (isset($_POST['orderby']) && !empty($_POST['orderby'])) {
            $ordering = $this->get_catalog_ordering_args($_POST['orderby']);
            $args['orderby'] = $ordering['orderby'];
            $args['order'] = $ordering['order'];
            
            if (isset($ordering['meta_key'])) {
                $args['meta_key'] = $ordering['meta_key'];
            }
        }
        
        // Multiple tax queries
        if (count($args['tax_query']) > 1) {
            $args['tax_query']['relation'] = 'AND';
        }
        
        // Multiple meta queries
        if (count($args['meta_query']) > 1) {
            $args['meta_query']['relation'] = 'AND';
        }
        
        // Get products
        $products_query = new WP_Query($args);
        
        ob_start();
        
        if ($products_query->have_posts()) {
            echo '<div class="products grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">';
            
            while ($products_query->have_posts()) {
                $products_query->the_post();
                wc_get_template_part('content', 'product');
            }
            
            echo '</div>';
            
            // Pagination
            $total_pages = $products_query->max_num_pages;
            
            if ($total_pages > 1) {
                echo '<div class="pagination flex justify-center mt-8">';
                echo paginate_links(array(
                    'base' => esc_url_raw(str_replace(999999999, '%#%', get_pagenum_link(999999999, false))),
                    'format' => '',
                    'current' => max(1, $args['paged']),
                    'total' => $total_pages,
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                    'type' => 'list',
                    'end_size' => 3,
                    'mid_size' => 3,
                ));
                echo '</div>';
            }
        } else {
            echo '<div class="woocommerce-info">' . esc_html__('No products found.', 'aqualuxe') . '</div>';
        }
        
        wp_reset_postdata();
        
        $products_html = ob_get_clean();
        
        wp_send_json_success(array(
            'products_html' => $products_html,
            'found_posts' => $products_query->found_posts,
        ));
        exit;
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        wp_enqueue_script('aqualuxe-filters', get_template_directory_uri() . '/assets/js/filters.js', array('jquery', 'jquery-ui-slider'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-filters', 'aqualuxeFilters', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_filter_nonce'),
            'i18n' => array(
                'loading' => __('Loading products...', 'aqualuxe'),
                'error' => __('Error loading products', 'aqualuxe'),
                'noProducts' => __('No products found', 'aqualuxe'),
                'priceRange' => __('Price: %s - %s', 'aqualuxe'),
            ),
        ));
    }

    /**
     * Price range filter
     */
    public function price_range_filter() {
        // Get min and max prices
        $min_price = floor(floatval(wc_get_min_price()));
        $max_price = ceil(floatval(wc_get_max_price()));
        
        // Default values
        $current_min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $min_price;
        $current_max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $max_price;
        
        // Format prices
        $formatted_min_price = wc_price($min_price);
        $formatted_max_price = wc_price($max_price);
        
        echo '<div class="price-range-filter">';
        echo '<div class="price-range-slider" data-min="' . esc_attr($min_price) . '" data-max="' . esc_attr($max_price) . '" data-current-min="' . esc_attr($current_min_price) . '" data-current-max="' . esc_attr($current_max_price) . '"></div>';
        echo '<div class="price-range-values flex justify-between mt-4">';
        echo '<span class="min-price">' . $formatted_min_price . '</span>';
        echo '<span class="max-price">' . $formatted_max_price . '</span>';
        echo '</div>';
        echo '<input type="hidden" name="min_price" value="' . esc_attr($current_min_price) . '">';
        echo '<input type="hidden" name="max_price" value="' . esc_attr($current_max_price) . '">';
        echo '</div>';
    }

    /**
     * Attribute filter
     *
     * @param string $attribute_name Attribute name.
     */
    public function attribute_filter($attribute_name) {
        $taxonomy = wc_attribute_taxonomy_name($attribute_name);
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ));
        
        if (!$terms || is_wp_error($terms)) {
            return;
        }
        
        $filter_key = 'attribute_' . $attribute_name;
        $current_values = isset($_GET[$filter_key]) ? explode(',', $_GET[$filter_key]) : array();
        
        echo '<div class="attribute-filter">';
        echo '<div class="space-y-2">';
        
        foreach ($terms as $term) {
            $checked = in_array($term->term_id, $current_values) ? 'checked' : '';
            
            echo '<div class="flex items-center">';
            echo '<input type="checkbox" id="' . esc_attr($filter_key . '_' . $term->term_id) . '" name="' . esc_attr($filter_key) . '[]" value="' . esc_attr($term->term_id) . '" ' . $checked . ' class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded transition-colors duration-300">';
            echo '<label for="' . esc_attr($filter_key . '_' . $term->term_id) . '" class="ml-2 block text-sm">' . esc_html($term->name) . '</label>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }

    /**
     * Category filter
     */
    public function category_filter() {
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0,
        ));
        
        if (!$categories || is_wp_error($categories)) {
            return;
        }
        
        $current_values = isset($_GET['categories']) ? explode(',', $_GET['categories']) : array();
        
        echo '<div class="category-filter">';
        echo '<div class="space-y-2">';
        
        foreach ($categories as $category) {
            $checked = in_array($category->term_id, $current_values) ? 'checked' : '';
            
            echo '<div class="flex items-center">';
            echo '<input type="checkbox" id="category_' . esc_attr($category->term_id) . '" name="categories[]" value="' . esc_attr($category->term_id) . '" ' . $checked . ' class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded transition-colors duration-300">';
            echo '<label for="category_' . esc_attr($category->term_id) . '" class="ml-2 block text-sm">' . esc_html($category->name) . '</label>';
            echo '</div>';
            
            // Get subcategories
            $subcategories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => $category->term_id,
            ));
            
            if ($subcategories && !is_wp_error($subcategories)) {
                echo '<div class="ml-6 space-y-2 mt-2">';
                
                foreach ($subcategories as $subcategory) {
                    $checked = in_array($subcategory->term_id, $current_values) ? 'checked' : '';
                    
                    echo '<div class="flex items-center">';
                    echo '<input type="checkbox" id="category_' . esc_attr($subcategory->term_id) . '" name="categories[]" value="' . esc_attr($subcategory->term_id) . '" ' . $checked . ' class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded transition-colors duration-300">';
                    echo '<label for="category_' . esc_attr($subcategory->term_id) . '" class="ml-2 block text-sm">' . esc_html($subcategory->name) . '</label>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
        }
        
        echo '</div>';
        echo '</div>';
    }

    /**
     * Tag filter
     */
    public function tag_filter() {
        $tags = get_terms(array(
            'taxonomy' => 'product_tag',
            'hide_empty' => true,
        ));
        
        if (!$tags || is_wp_error($tags)) {
            return;
        }
        
        $current_values = isset($_GET['tags']) ? explode(',', $_GET['tags']) : array();
        
        echo '<div class="tag-filter">';
        echo '<div class="flex flex-wrap gap-2">';
        
        foreach ($tags as $tag) {
            $active = in_array($tag->term_id, $current_values) ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
            
            echo '<label class="tag-label cursor-pointer">';
            echo '<input type="checkbox" name="tags[]" value="' . esc_attr($tag->term_id) . '" ' . checked(in_array($tag->term_id, $current_values), true, false) . ' class="hidden">';
            echo '<span class="inline-block px-3 py-1 rounded-full text-sm ' . esc_attr($active) . ' transition-colors duration-300">' . esc_html($tag->name) . '</span>';
            echo '</label>';
        }
        
        echo '</div>';
        echo '</div>';
    }

    /**
     * Rating filter
     */
    public function rating_filter() {
        $current_values = isset($_GET['rating']) ? explode(',', $_GET['rating']) : array();
        
        echo '<div class="rating-filter">';
        echo '<div class="space-y-2">';
        
        for ($i = 5; $i >= 1; $i--) {
            $checked = in_array($i, $current_values) ? 'checked' : '';
            
            echo '<div class="flex items-center">';
            echo '<input type="checkbox" id="rating_' . esc_attr($i) . '" name="rating[]" value="' . esc_attr($i) . '" ' . $checked . ' class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded transition-colors duration-300">';
            echo '<label for="rating_' . esc_attr($i) . '" class="ml-2 flex items-center">';
            
            // Display stars
            for ($j = 1; $j <= 5; $j++) {
                if ($j <= $i) {
                    echo '<svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">';
                    echo '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />';
                    echo '</svg>';
                } else {
                    echo '<svg class="h-5 w-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">';
                    echo '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />';
                    echo '</svg>';
                }
            }
            
            echo '<span class="ml-2 text-sm text-gray-600 dark:text-gray-400">' . sprintf(_n('(%s star)', '(%s stars)', $i, 'aqualuxe'), number_format_i18n($i)) . '</span>';
            echo '</label>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }

    /**
     * Active filters
     */
    public function active_filters() {
        $active_filters = $this->get_active_filters();
        
        if (empty($active_filters)) {
            return;
        }
        
        echo '<div class="active-filters mb-6">';
        echo '<h4 class="text-lg font-bold mb-2">' . esc_html__('Active Filters', 'aqualuxe') . '</h4>';
        echo '<div class="flex flex-wrap gap-2">';
        
        foreach ($active_filters as $filter) {
            echo '<div class="active-filter inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary text-white">';
            echo esc_html($filter['label']);
            echo '<button type="button" class="ml-2 remove-filter" data-filter="' . esc_attr($filter['key']) . '" data-value="' . esc_attr($filter['value']) . '">';
            echo '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
            echo '</svg>';
            echo '</button>';
            echo '</div>';
        }
        
        echo '<button type="button" class="clear-all-filters inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-300">';
        echo esc_html__('Clear All', 'aqualuxe');
        echo '</button>';
        
        echo '</div>';
        echo '</div>';
    }

    /**
     * Get active filters
     *
     * @return array
     */
    private function get_active_filters() {
        $active_filters = array();
        
        // Price range
        if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
            $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
            $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_INT_MAX;
            
            $min_price_default = floor(floatval(wc_get_min_price()));
            $max_price_default = ceil(floatval(wc_get_max_price()));
            
            if ($min_price > $min_price_default || $max_price < $max_price_default) {
                $active_filters[] = array(
                    'key' => 'price',
                    'value' => $min_price . '-' . $max_price,
                    'label' => sprintf(__('Price: %s - %s', 'aqualuxe'), wc_price($min_price), wc_price($max_price)),
                );
            }
        }
        
        // Categories
        if (isset($_GET['categories'])) {
            $categories = explode(',', $_GET['categories']);
            
            foreach ($categories as $category_id) {
                $term = get_term($category_id, 'product_cat');
                
                if ($term && !is_wp_error($term)) {
                    $active_filters[] = array(
                        'key' => 'categories',
                        'value' => $category_id,
                        'label' => $term->name,
                    );
                }
            }
        }
        
        // Tags
        if (isset($_GET['tags'])) {
            $tags = explode(',', $_GET['tags']);
            
            foreach ($tags as $tag_id) {
                $term = get_term($tag_id, 'product_tag');
                
                if ($term && !is_wp_error($term)) {
                    $active_filters[] = array(
                        'key' => 'tags',
                        'value' => $tag_id,
                        'label' => $term->name,
                    );
                }
            }
        }
        
        // Attributes
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if ($attribute_taxonomies) {
            foreach ($attribute_taxonomies as $attribute) {
                $filter_key = 'attribute_' . $attribute->attribute_name;
                
                if (isset($_GET[$filter_key])) {
                    $attribute_values = explode(',', $_GET[$filter_key]);
                    $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
                    
                    foreach ($attribute_values as $value) {
                        $term = get_term($value, $taxonomy);
                        
                        if ($term && !is_wp_error($term)) {
                            $active_filters[] = array(
                                'key' => $filter_key,
                                'value' => $value,
                                'label' => $attribute->attribute_label . ': ' . $term->name,
                            );
                        }
                    }
                }
            }
        }
        
        // Rating
        if (isset($_GET['rating'])) {
            $ratings = explode(',', $_GET['rating']);
            
            foreach ($ratings as $rating) {
                $active_filters[] = array(
                    'key' => 'rating',
                    'value' => $rating,
                    'label' => sprintf(_n('%s Star', '%s Stars', $rating, 'aqualuxe'), number_format_i18n($rating)),
                );
            }
        }
        
        return $active_filters;
    }

    /**
     * Get active filters count
     *
     * @return int
     */
    private function get_active_filters_count() {
        return count($this->get_active_filters());
    }

    /**
     * Get catalog ordering args
     *
     * @param string $orderby Order by.
     * @return array
     */
    private function get_catalog_ordering_args($orderby) {
        $args = array();
        
        switch ($orderby) {
            case 'price':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'ASC';
                break;
            case 'price-desc':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'DESC';
                break;
            case 'popularity':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'total_sales';
                $args['order'] = 'DESC';
                break;
            case 'rating':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_wc_average_rating';
                $args['order'] = 'DESC';
                break;
            case 'date':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
            default:
                $args['orderby'] = 'menu_order title';
                $args['order'] = 'ASC';
                break;
        }
        
        return $args;
    }
}

// Initialize the class
new AquaLuxe_WC_Filters();