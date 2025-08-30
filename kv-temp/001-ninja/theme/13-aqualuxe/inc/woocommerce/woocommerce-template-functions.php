<?php
/**
 * WooCommerce template functions for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display product categories as a grid
 *
 * @param array $args Arguments.
 */
if (!function_exists('aqualuxe_woocommerce_product_categories')) {
    function aqualuxe_woocommerce_product_categories($args = array()) {
        $args = wp_parse_args($args, array(
            'before' => '<div class="product-categories grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">',
            'after' => '</div>',
            'parent_id' => 0,
        ));

        $product_categories = wc_get_product_categories(array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 1,
            'parent' => $args['parent_id'],
        ));

        if ($product_categories) {
            echo wp_kses_post($args['before']);

            foreach ($product_categories as $category) {
                wc_get_template('content-product-cat.php', array(
                    'category' => $category,
                ));
            }

            echo wp_kses_post($args['after']);
        }
    }
}

/**
 * Display featured products
 *
 * @param array $args Arguments.
 */
if (!function_exists('aqualuxe_woocommerce_featured_products')) {
    function aqualuxe_woocommerce_featured_products($args = array()) {
        $args = wp_parse_args($args, array(
            'limit' => 4,
            'columns' => 4,
            'title' => __('Featured Products', 'aqualuxe'),
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        $args['featured'] = true;

        echo '<div class="featured-products mb-12">';
        if ($args['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($args['title']) . '</h2>';
        }
        
        echo do_shortcode('[products featured="true" limit="' . esc_attr($args['limit']) . '" columns="' . esc_attr($args['columns']) . '" orderby="' . esc_attr($args['orderby']) . '" order="' . esc_attr($args['order']) . '"]');
        echo '</div>';
    }
}

/**
 * Display new products
 *
 * @param array $args Arguments.
 */
if (!function_exists('aqualuxe_woocommerce_new_products')) {
    function aqualuxe_woocommerce_new_products($args = array()) {
        $args = wp_parse_args($args, array(
            'limit' => 4,
            'columns' => 4,
            'title' => __('New Arrivals', 'aqualuxe'),
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        echo '<div class="new-products mb-12">';
        if ($args['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($args['title']) . '</h2>';
        }
        
        echo do_shortcode('[products limit="' . esc_attr($args['limit']) . '" columns="' . esc_attr($args['columns']) . '" orderby="' . esc_attr($args['orderby']) . '" order="' . esc_attr($args['order']) . '"]');
        echo '</div>';
    }
}

/**
 * Display sale products
 *
 * @param array $args Arguments.
 */
if (!function_exists('aqualuxe_woocommerce_sale_products')) {
    function aqualuxe_woocommerce_sale_products($args = array()) {
        $args = wp_parse_args($args, array(
            'limit' => 4,
            'columns' => 4,
            'title' => __('On Sale', 'aqualuxe'),
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        echo '<div class="sale-products mb-12">';
        if ($args['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($args['title']) . '</h2>';
        }
        
        echo do_shortcode('[products on_sale="true" limit="' . esc_attr($args['limit']) . '" columns="' . esc_attr($args['columns']) . '" orderby="' . esc_attr($args['orderby']) . '" order="' . esc_attr($args['order']) . '"]');
        echo '</div>';
    }
}

/**
 * Display best selling products
 *
 * @param array $args Arguments.
 */
if (!function_exists('aqualuxe_woocommerce_best_selling_products')) {
    function aqualuxe_woocommerce_best_selling_products($args = array()) {
        $args = wp_parse_args($args, array(
            'limit' => 4,
            'columns' => 4,
            'title' => __('Best Sellers', 'aqualuxe'),
        ));

        echo '<div class="best-selling-products mb-12">';
        if ($args['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($args['title']) . '</h2>';
        }
        
        echo do_shortcode('[products best_selling="true" limit="' . esc_attr($args['limit']) . '" columns="' . esc_attr($args['columns']) . '"]');
        echo '</div>';
    }
}

/**
 * Display product category tabs
 *
 * @param array $args Arguments.
 */
if (!function_exists('aqualuxe_woocommerce_product_category_tabs')) {
    function aqualuxe_woocommerce_product_category_tabs($args = array()) {
        $args = wp_parse_args($args, array(
            'limit' => 4,
            'columns' => 4,
            'title' => __('Shop by Category', 'aqualuxe'),
            'categories' => array(),
        ));

        if (empty($args['categories'])) {
            $product_categories = get_terms('product_cat', array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => 1,
                'parent' => 0,
                'number' => 4,
            ));

            $args['categories'] = wp_list_pluck($product_categories, 'slug');
        }

        echo '<div class="product-category-tabs mb-12">';
        if ($args['title']) {
            echo '<h2 class="text-2xl font-bold mb-6">' . esc_html($args['title']) . '</h2>';
        }

        echo '<div class="category-tabs-nav flex flex-wrap border-b border-gray-200 dark:border-gray-700 mb-6">';
        
        $first = true;
        foreach ($args['categories'] as $category_slug) {
            $category = get_term_by('slug', $category_slug, 'product_cat');
            if ($category) {
                $active_class = $first ? 'border-primary text-primary dark:border-primary-dark dark:text-primary-dark' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600';
                echo '<button class="category-tab-button px-4 py-2 font-medium text-sm border-b-2 ' . esc_attr($active_class) . ' transition-colors duration-300 mr-4 mb-2" data-category="' . esc_attr($category_slug) . '">' . esc_html($category->name) . '</button>';
                $first = false;
            }
        }
        
        echo '</div>';
        
        echo '<div class="category-tabs-content">';
        
        $first = true;
        foreach ($args['categories'] as $category_slug) {
            $display = $first ? 'block' : 'hidden';
            echo '<div class="category-tab-content ' . esc_attr($display) . '" data-category="' . esc_attr($category_slug) . '">';
            echo do_shortcode('[products category="' . esc_attr($category_slug) . '" limit="' . esc_attr($args['limit']) . '" columns="' . esc_attr($args['columns']) . '"]');
            echo '</div>';
            $first = false;
        }
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display product filters
 */
if (!function_exists('aqualuxe_woocommerce_product_filters')) {
    function aqualuxe_woocommerce_product_filters() {
        echo '<div class="product-filters bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 transition-colors duration-300">';
        
        // Price filter
        echo '<div class="filter-section mb-6">';
        echo '<h3 class="text-lg font-bold mb-4">' . esc_html__('Filter by Price', 'aqualuxe') . '</h3>';
        echo do_shortcode('[woocommerce_price_filter]');
        echo '</div>';
        
        // Category filter
        echo '<div class="filter-section mb-6">';
        echo '<h3 class="text-lg font-bold mb-4">' . esc_html__('Product Categories', 'aqualuxe') . '</h3>';
        echo do_shortcode('[product_categories number="10" hide_empty="1"]');
        echo '</div>';
        
        // Attribute filter
        echo '<div class="filter-section">';
        echo '<h3 class="text-lg font-bold mb-4">' . esc_html__('Filter by Attributes', 'aqualuxe') . '</h3>';
        
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if ($attribute_taxonomies) {
            foreach ($attribute_taxonomies as $attribute) {
                $attribute_name = wc_attribute_taxonomy_name($attribute->attribute_name);
                $terms = get_terms(array(
                    'taxonomy' => $attribute_name,
                    'hide_empty' => true,
                ));
                
                if ($terms) {
                    echo '<div class="attribute-filter mb-4">';
                    echo '<h4 class="font-medium mb-2">' . esc_html($attribute->attribute_label) . '</h4>';
                    echo '<div class="attribute-options space-y-2">';
                    
                    foreach ($terms as $term) {
                        echo '<label class="flex items-center">';
                        echo '<input type="checkbox" class="attribute-filter-checkbox mr-2" name="filter_' . esc_attr($attribute_name) . '" value="' . esc_attr($term->slug) . '">';
                        echo esc_html($term->name);
                        echo '</label>';
                    }
                    
                    echo '</div>';
                    echo '</div>';
                }
            }
        }
        
        echo '</div>';
        
        echo '<button class="apply-filters w-full mt-6 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300">';
        echo esc_html__('Apply Filters', 'aqualuxe');
        echo '</button>';
        
        echo '</div>';
    }
}

/**
 * Display product quick view modal
 */
if (!function_exists('aqualuxe_woocommerce_quick_view_modal')) {
    function aqualuxe_woocommerce_quick_view_modal() {
        // Only show if quick view is enabled
        if (!get_theme_mod('quick_view', true)) {
            return;
        }
        
        ?>
        <div id="quick-view-modal" class="quick-view-modal fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="modal-content bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-4xl mx-4 transition-colors duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold product-title"></h3>
                    <button id="quick-view-close" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="quick-view-content grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="product-image"></div>
                    <div class="product-details">
                        <div class="product-price mb-4"></div>
                        <div class="product-excerpt mb-4"></div>
                        <div class="product-add-to-cart"></div>
                        <div class="product-meta mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="#" class="view-full-details inline-flex items-center text-primary dark:text-primary-dark hover:underline transition-colors duration-300">
                                <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Display product wishlist button
 */
if (!function_exists('aqualuxe_woocommerce_wishlist_button')) {
    function aqualuxe_woocommerce_wishlist_button() {
        // Only show if wishlist is enabled
        if (!get_theme_mod('wishlist', true)) {
            return;
        }
        
        global $product;
        
        echo '<button class="wishlist-toggle inline-flex items-center p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-300" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 wishlist-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
        echo '</svg>';
        echo '<span class="sr-only">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
        echo '</button>';
    }
}

/**
 * Display product share buttons
 */
if (!function_exists('aqualuxe_woocommerce_product_share')) {
    function aqualuxe_woocommerce_product_share() {
        global $product;
        
        $share_url = get_permalink($product->get_id());
        $share_title = get_the_title($product->get_id());
        
        echo '<div class="product-share mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">';
        echo '<h3 class="text-lg font-bold mb-4">' . esc_html__('Share This Product', 'aqualuxe') . '</h3>';
        echo '<div class="flex space-x-4">';
        
        // Facebook
        echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url($share_url) . '" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300">';
        echo '<span class="sr-only">' . esc_html__('Share on Facebook', 'aqualuxe') . '</span>';
        echo '<svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">';
        echo '<path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />';
        echo '</svg>';
        echo '</a>';
        
        // Twitter
        echo '<a href="https://twitter.com/intent/tweet?text=' . esc_attr($share_title) . '&url=' . esc_url($share_url) . '" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300">';
        echo '<span class="sr-only">' . esc_html__('Share on Twitter', 'aqualuxe') . '</span>';
        echo '<svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">';
        echo '<path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />';
        echo '</svg>';
        echo '</a>';
        
        // Pinterest
        echo '<a href="https://pinterest.com/pin/create/button/?url=' . esc_url($share_url) . '&media=' . esc_url(get_the_post_thumbnail_url($product->get_id(), 'full')) . '&description=' . esc_attr($share_title) . '" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300">';
        echo '<span class="sr-only">' . esc_html__('Share on Pinterest', 'aqualuxe') . '</span>';
        echo '<svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">';
        echo '<path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z" />';
        echo '</svg>';
        echo '</a>';
        
        // Email
        echo '<a href="mailto:?subject=' . esc_attr($share_title) . '&body=' . esc_url($share_url) . '" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300">';
        echo '<span class="sr-only">' . esc_html__('Share via Email', 'aqualuxe') . '</span>';
        echo '<svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">';
        echo '<path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />';
        echo '<path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />';
        echo '</svg>';
        echo '</a>';
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display product specifications
 */
if (!function_exists('aqualuxe_woocommerce_product_specifications')) {
    function aqualuxe_woocommerce_product_specifications() {
        global $product;
        
        // Get product attributes
        $attributes = $product->get_attributes();
        
        if (!empty($attributes)) {
            echo '<div class="product-specifications mt-8">';
            echo '<h3 class="text-xl font-bold mb-4">' . esc_html__('Product Specifications', 'aqualuxe') . '</h3>';
            echo '<div class="bg-gray-50 dark:bg-gray-800 rounded-lg overflow-hidden transition-colors duration-300">';
            echo '<table class="w-full">';
            echo '<tbody>';
            
            foreach ($attributes as $attribute) {
                if ($attribute->get_visible()) {
                    echo '<tr class="border-b border-gray-200 dark:border-gray-700">';
                    echo '<th class="px-4 py-3 text-left text-sm font-medium text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 w-1/3">' . esc_html(wc_attribute_label($attribute->get_name())) . '</th>';
                    echo '<td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">';
                    
                    if ($attribute->is_taxonomy()) {
                        $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
                        echo esc_html(implode(', ', $values));
                    } else {
                        $values = $attribute->get_options();
                        echo esc_html(implode(', ', $values));
                    }
                    
                    echo '</td>';
                    echo '</tr>';
                }
            }
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
        }
    }
}

/**
 * Display product guarantee
 */
if (!function_exists('aqualuxe_woocommerce_product_guarantee')) {
    function aqualuxe_woocommerce_product_guarantee() {
        echo '<div class="product-guarantee mt-8 bg-blue-50 dark:bg-blue-900 rounded-lg p-6 transition-colors duration-300">';
        echo '<h3 class="text-xl font-bold mb-4 text-blue-800 dark:text-blue-200">' . esc_html__('Our Guarantee', 'aqualuxe') . '</h3>';
        echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
        
        // Shipping guarantee
        echo '<div class="flex">';
        echo '<div class="flex-shrink-0 mr-4">';
        echo '<svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />';
        echo '</svg>';
        echo '</div>';
        echo '<div>';
        echo '<h4 class="text-lg font-bold mb-2 text-blue-800 dark:text-blue-200">' . esc_html__('Safe Shipping', 'aqualuxe') . '</h4>';
        echo '<p class="text-blue-700 dark:text-blue-300">' . esc_html__('We guarantee safe arrival of all our aquatic species with specialized packaging and expedited shipping.', 'aqualuxe') . '</p>';
        echo '</div>';
        echo '</div>';
        
        // Quality guarantee
        echo '<div class="flex">';
        echo '<div class="flex-shrink-0 mr-4">';
        echo '<svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />';
        echo '</svg>';
        echo '</div>';
        echo '<div>';
        echo '<h4 class="text-lg font-bold mb-2 text-blue-800 dark:text-blue-200">' . esc_html__('Quality Assurance', 'aqualuxe') . '</h4>';
        echo '<p class="text-blue-700 dark:text-blue-300">' . esc_html__('All our fish are health-certified and acclimated before shipping. We guarantee their quality and health upon arrival.', 'aqualuxe') . '</p>';
        echo '</div>';
        echo '</div>';
        
        // Return policy
        echo '<div class="flex">';
        echo '<div class="flex-shrink-0 mr-4">';
        echo '<svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />';
        echo '</svg>';
        echo '</div>';
        echo '<div>';
        echo '<h4 class="text-lg font-bold mb-2 text-blue-800 dark:text-blue-200">' . esc_html__('Arrival Guarantee', 'aqualuxe') . '</h4>';
        echo '<p class="text-blue-700 dark:text-blue-300">' . esc_html__('If any issues occur during shipping, contact us within 24 hours with photos for a replacement or refund.', 'aqualuxe') . '</p>';
        echo '</div>';
        echo '</div>';
        
        // Support
        echo '<div class="flex">';
        echo '<div class="flex-shrink-0 mr-4">';
        echo '<svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />';
        echo '</svg>';
        echo '</div>';
        echo '<div>';
        echo '<h4 class="text-lg font-bold mb-2 text-blue-800 dark:text-blue-200">' . esc_html__('Expert Support', 'aqualuxe') . '</h4>';
        echo '<p class="text-blue-700 dark:text-blue-300">' . esc_html__('Our team of aquatic specialists is available to answer questions and provide care guidance for your new aquatic pets.', 'aqualuxe') . '</p>';
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display multi-currency selector
 */
if (!function_exists('aqualuxe_woocommerce_currency_selector')) {
    function aqualuxe_woocommerce_currency_selector() {
        // Check if WPML or WooCommerce Multi-currency is active
        if (class_exists('WCML_Multi_Currency')) {
            // WPML Multi-currency
            do_action('wcml_currency_switcher', array(
                'format' => '%code%',
                'switcher_style' => 'dropdown',
            ));
        } elseif (class_exists('WOOMC\App')) {
            // WooCommerce Multi-currency
            do_action('woomc_currency_selector');
        } else {
            // Default currency selector (for demo purposes)
            $currencies = array(
                'USD' => 'US Dollar',
                'EUR' => 'Euro',
                'GBP' => 'British Pound',
                'JPY' => 'Japanese Yen',
                'AUD' => 'Australian Dollar',
            );
            
            echo '<div class="currency-selector relative">';
            echo '<button class="flex items-center space-x-1 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-300">';
            echo '<span>' . esc_html(get_woocommerce_currency()) . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
            echo '</svg>';
            echo '</button>';
            echo '<div class="currency-dropdown absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 hidden z-10">';
            echo '<div class="py-1" role="menu" aria-orientation="vertical">';
            
            foreach ($currencies as $code => $name) {
                $active = (get_woocommerce_currency() === $code) ? ' font-bold' : '';
                echo '<a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300' . esc_attr($active) . '" role="menuitem" data-currency="' . esc_attr($code) . '">';
                echo esc_html($code) . ' - ' . esc_html($name);
                echo '</a>';
            }
            
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
}

/**
 * Display shipping calculator
 */
if (!function_exists('aqualuxe_woocommerce_shipping_calculator')) {
    function aqualuxe_woocommerce_shipping_calculator() {
        echo '<div class="shipping-calculator bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 transition-colors duration-300">';
        echo '<h3 class="text-xl font-bold mb-4">' . esc_html__('Shipping Calculator', 'aqualuxe') . '</h3>';
        
        echo do_shortcode('[woocommerce_shipping_calculator]');
        
        echo '</div>';
    }
}

/**
 * Display product reviews in a custom format
 */
if (!function_exists('aqualuxe_woocommerce_product_reviews')) {
    function aqualuxe_woocommerce_product_reviews() {
        global $product;
        
        $review_count = $product->get_review_count();
        $average_rating = $product->get_average_rating();
        
        echo '<div class="product-reviews mt-8">';
        echo '<div class="flex items-center justify-between mb-6">';
        echo '<h3 class="text-xl font-bold">' . esc_html__('Customer Reviews', 'aqualuxe') . '</h3>';
        echo '<a href="#review-form" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300">';
        echo esc_html__('Write a Review', 'aqualuxe');
        echo '</a>';
        echo '</div>';
        
        echo '<div class="review-summary flex flex-col md:flex-row mb-8">';
        
        // Rating summary
        echo '<div class="md:w-1/3 mb-6 md:mb-0">';
        echo '<div class="text-center">';
        echo '<div class="text-5xl font-bold mb-2">' . esc_html(number_format($average_rating, 1)) . '</div>';
        echo '<div class="flex justify-center mb-2">';
        
        // Display stars
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $average_rating) {
                echo '<svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">';
                echo '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />';
                echo '</svg>';
            } elseif ($i - 0.5 <= $average_rating) {
                echo '<svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">';
                echo '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />';
                echo '</svg>';
            } else {
                echo '<svg class="h-6 w-6 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">';
                echo '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />';
                echo '</svg>';
            }
        }
        
        echo '</div>';
        echo '<div class="text-sm text-gray-600 dark:text-gray-400">' . sprintf(_n('%s review', '%s reviews', $review_count, 'aqualuxe'), number_format_i18n($review_count)) . '</div>';
        echo '</div>';
        echo '</div>';
        
        // Rating breakdown
        echo '<div class="md:w-2/3 md:pl-8">';
        
        for ($i = 5; $i >= 1; $i--) {
            $percentage = ($review_count > 0) ? round(($product->get_rating_count($i) / $review_count) * 100) : 0;
            
            echo '<div class="flex items-center mb-2">';
            echo '<div class="w-12 text-sm font-medium">' . esc_html($i) . ' ' . esc_html__('star', 'aqualuxe') . '</div>';
            echo '<div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mx-2">';
            echo '<div class="bg-yellow-400 h-2.5 rounded-full" style="width: ' . esc_attr($percentage) . '%"></div>';
            echo '</div>';
            echo '<div class="w-12 text-sm text-right">' . esc_html($percentage) . '%</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
        
        // Review list
        comments_template();
        
        echo '</div>';
    }
}