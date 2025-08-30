# AquaLuxe Theme - AJAX Product Filtering Technical Specification

## Overview

This document provides technical specifications for implementing AJAX-powered product filtering in the AquaLuxe WordPress theme version 2.1.0. This feature will enhance the user experience by allowing customers to filter products without page reloads, providing instant feedback and a smoother shopping experience.

## Requirements

### Functional Requirements

1. Filter products by multiple attributes without page reload
2. Update product count for each filter option dynamically
3. Support for price range filtering with slider
4. Allow filtering by product categories, tags, and custom taxonomies
5. Provide visual feedback during filter operations
6. Support URL parameter updates for shareable filtered results
7. Maintain filter state when navigating back/forward
8. Optimize for mobile devices with collapsible filter sections
9. Support for sorting options (price, popularity, rating, etc.)
10. Allow for multiple selection within the same attribute (OR relationship)
11. Allow for selection across different attributes (AND relationship)
12. Provide reset filters option

### Technical Requirements

1. Vanilla JavaScript implementation (no jQuery dependency)
2. Optimized AJAX requests with debouncing
3. Browser history management using History API
4. Accessible according to WCAG 2.1 AA standards
5. Compatible with major browsers (Chrome, Firefox, Safari, Edge)
6. Mobile-responsive design
7. Caching mechanism for improved performance
8. Fallback for non-JavaScript environments
9. Compatible with WooCommerce 6.0+
10. Support for product variations filtering

## Architecture

### Components

1. **Filter UI Component**
   - Renders filter options in sidebar/offcanvas
   - Handles user interactions
   - Updates UI based on selected filters

2. **AJAX Handler**
   - Processes filter requests
   - Returns filtered product HTML and counts
   - Handles error states

3. **URL Manager**
   - Updates URL parameters based on selected filters
   - Parses URL parameters on page load
   - Manages browser history

4. **Product Grid Updater**
   - Updates product grid with new results
   - Handles loading states
   - Manages pagination

5. **Filter State Manager**
   - Maintains current filter state
   - Provides methods to update/reset state
   - Syncs state with URL and UI

### Data Flow

1. User selects/changes filter options
2. Filter UI Component updates Filter State Manager
3. URL Manager updates the URL with new parameters
4. AJAX Handler sends request to server with filter parameters
5. Server processes request and returns filtered products and counts
6. Product Grid Updater refreshes the product display
7. Filter UI Component updates option counts and available filters

## Implementation Details

### PHP Implementation

#### 1. AJAX Endpoint

Create a dedicated AJAX endpoint to handle filter requests:

```php
/**
 * Register AJAX actions for product filtering
 */
function aqualuxe_register_filter_ajax_actions() {
    add_action('wp_ajax_aqualuxe_filter_products', 'aqualuxe_filter_products_ajax_handler');
    add_action('wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_filter_products_ajax_handler');
}
add_action('init', 'aqualuxe_register_filter_ajax_actions');

/**
 * AJAX handler for product filtering
 */
function aqualuxe_filter_products_ajax_handler() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_filter_nonce')) {
        wp_send_json_error(['message' => __('Security check failed.', 'aqualuxe')]);
    }

    // Parse filter parameters
    $filters = [];
    
    // Categories
    if (isset($_POST['categories']) && !empty($_POST['categories'])) {
        $filters['categories'] = array_map('absint', explode(',', $_POST['categories']));
    }
    
    // Attributes
    if (isset($_POST['attributes']) && !empty($_POST['attributes'])) {
        $filters['attributes'] = json_decode(stripslashes($_POST['attributes']), true);
    }
    
    // Price range
    if (isset($_POST['min_price']) && isset($_POST['max_price'])) {
        $filters['min_price'] = floatval($_POST['min_price']);
        $filters['max_price'] = floatval($_POST['max_price']);
    }
    
    // Tags
    if (isset($_POST['tags']) && !empty($_POST['tags'])) {
        $filters['tags'] = array_map('absint', explode(',', $_POST['tags']));
    }
    
    // Sorting
    if (isset($_POST['orderby']) && !empty($_POST['orderby'])) {
        $filters['orderby'] = sanitize_text_field($_POST['orderby']);
    }
    
    // Pagination
    $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
    
    // Build WP_Query arguments
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'paged' => $paged,
        'posts_per_page' => apply_filters('aqualuxe_products_per_page', get_option('posts_per_page')),
    ];
    
    // Apply category filter
    if (!empty($filters['categories'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $filters['categories'],
            'operator' => 'IN',
        ];
    }
    
    // Apply tag filter
    if (!empty($filters['tags'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_tag',
            'field' => 'term_id',
            'terms' => $filters['tags'],
            'operator' => 'IN',
        ];
    }
    
    // Apply attribute filters
    if (!empty($filters['attributes'])) {
        foreach ($filters['attributes'] as $taxonomy => $terms) {
            if (!empty($terms)) {
                $args['tax_query'][] = [
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => array_map('absint', $terms),
                    'operator' => 'IN',
                ];
            }
        }
    }
    
    // Apply price filter
    if (isset($filters['min_price']) && isset($filters['max_price'])) {
        $args['meta_query'][] = [
            'key' => '_price',
            'value' => [$filters['min_price'], $filters['max_price']],
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        ];
    }
    
    // Apply sorting
    if (!empty($filters['orderby'])) {
        switch ($filters['orderby']) {
            case 'price':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'rating':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
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
    }
    
    // Allow other plugins to modify the query
    $args = apply_filters('aqualuxe_filter_products_query_args', $args, $filters);
    
    // Run the query
    $query = new WP_Query($args);
    
    // Start output buffering
    ob_start();
    
    if ($query->have_posts()) {
        // Output product loop
        woocommerce_product_loop_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        
        // Pagination
        aqualuxe_filter_pagination($query);
    } else {
        // No products found
        echo '<p class="woocommerce-info">' . esc_html__('No products found matching your selection.', 'aqualuxe') . '</p>';
    }
    
    wp_reset_postdata();
    
    $products_html = ob_get_clean();
    
    // Get filter counts
    $filter_counts = aqualuxe_get_filter_counts($filters);
    
    // Send response
    wp_send_json_success([
        'products_html' => $products_html,
        'filter_counts' => $filter_counts,
        'total_products' => $query->found_posts,
        'max_pages' => $query->max_num_pages,
    ]);
}

/**
 * Generate pagination for filtered results
 */
function aqualuxe_filter_pagination($query) {
    $total = $query->max_num_pages;
    $current = max(1, $query->get('paged'));
    
    if ($total <= 1) {
        return;
    }
    
    echo '<nav class="aqualuxe-filter-pagination">';
    echo paginate_links([
        'base' => '%_%',
        'format' => '?paged=%#%',
        'current' => $current,
        'total' => $total,
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
        'type' => 'list',
    ]);
    echo '</nav>';
}

/**
 * Get counts for each filter option
 */
function aqualuxe_get_filter_counts($current_filters) {
    $counts = [
        'categories' => [],
        'attributes' => [],
        'tags' => [],
    ];
    
    // Get category counts
    $product_categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ]);
    
    foreach ($product_categories as $category) {
        // Clone the current filters but remove the category we're counting
        $temp_filters = $current_filters;
        if (isset($temp_filters['categories'])) {
            unset($temp_filters['categories']);
        }
        
        // Count products in this category with other filters applied
        $args = aqualuxe_build_filter_query_args($temp_filters);
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $category->term_id,
        ];
        
        $query = new WP_Query($args);
        $counts['categories'][$category->term_id] = $query->found_posts;
    }
    
    // Get attribute counts (similar approach for each attribute)
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    
    foreach ($attribute_taxonomies as $tax) {
        $taxonomy = 'pa_' . $tax->attribute_name;
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);
        
        $counts['attributes'][$taxonomy] = [];
        
        foreach ($terms as $term) {
            // Clone the current filters but remove the current attribute
            $temp_filters = $current_filters;
            if (isset($temp_filters['attributes'][$taxonomy])) {
                unset($temp_filters['attributes'][$taxonomy]);
            }
            
            // Count products with this attribute term and other filters applied
            $args = aqualuxe_build_filter_query_args($temp_filters);
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term->term_id,
            ];
            
            $query = new WP_Query($args);
            $counts['attributes'][$taxonomy][$term->term_id] = $query->found_posts;
        }
    }
    
    // Get tag counts
    $product_tags = get_terms([
        'taxonomy' => 'product_tag',
        'hide_empty' => true,
    ]);
    
    foreach ($product_tags as $tag) {
        // Clone the current filters but remove the tags
        $temp_filters = $current_filters;
        if (isset($temp_filters['tags'])) {
            unset($temp_filters['tags']);
        }
        
        // Count products with this tag and other filters applied
        $args = aqualuxe_build_filter_query_args($temp_filters);
        $args['tax_query'][] = [
            'taxonomy' => 'product_tag',
            'field' => 'term_id',
            'terms' => $tag->term_id,
        ];
        
        $query = new WP_Query($args);
        $counts['tags'][$tag->term_id] = $query->found_posts;
    }
    
    return $counts;
}

/**
 * Build query args from filters
 */
function aqualuxe_build_filter_query_args($filters) {
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1, // We only need counts
        'fields' => 'ids', // Only get post IDs for better performance
    ];
    
    // Apply category filter
    if (!empty($filters['categories'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $filters['categories'],
            'operator' => 'IN',
        ];
    }
    
    // Apply tag filter
    if (!empty($filters['tags'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_tag',
            'field' => 'term_id',
            'terms' => $filters['tags'],
            'operator' => 'IN',
        ];
    }
    
    // Apply attribute filters
    if (!empty($filters['attributes'])) {
        foreach ($filters['attributes'] as $taxonomy => $terms) {
            if (!empty($terms)) {
                $args['tax_query'][] = [
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => array_map('absint', $terms),
                    'operator' => 'IN',
                ];
            }
        }
    }
    
    // Apply price filter
    if (isset($filters['min_price']) && isset($filters['max_price'])) {
        $args['meta_query'][] = [
            'key' => '_price',
            'value' => [$filters['min_price'], $filters['max_price']],
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        ];
    }
    
    return $args;
}
```

#### 2. Filter Widget Template

Create a template for the filter widget:

```php
/**
 * Display product filter widget
 */
function aqualuxe_product_filter_widget() {
    // Only show on product archive pages
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    // Get min/max prices
    $min_price = floor(wc_get_price_to_display(wc_get_product(
        wc_get_min_price_product_id()
    )));
    
    $max_price = ceil(wc_get_price_to_display(wc_get_product(
        wc_get_max_price_product_id()
    )));
    
    // Get current category
    $current_category = is_product_category() ? get_queried_object_id() : 0;
    
    // Get categories
    $product_categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0, // Top level categories only
    ]);
    
    // Get attribute taxonomies
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    
    // Get product tags
    $product_tags = get_terms([
        'taxonomy' => 'product_tag',
        'hide_empty' => true,
    ]);
    
    // Create nonce
    $nonce = wp_create_nonce('aqualuxe_filter_nonce');
    
    ?>
    <div class="aqualuxe-product-filter" data-nonce="<?php echo esc_attr($nonce); ?>">
        <div class="aqualuxe-filter-header">
            <h3><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
            <button type="button" class="aqualuxe-filter-toggle-mobile">
                <span class="screen-reader-text"><?php esc_html_e('Toggle Filters', 'aqualuxe'); ?></span>
                <i class="fas fa-filter"></i>
            </button>
        </div>
        
        <div class="aqualuxe-filter-content">
            <?php if ($product_categories) : ?>
                <div class="aqualuxe-filter-section aqualuxe-filter-categories">
                    <h4 class="aqualuxe-filter-section-title">
                        <?php esc_html_e('Categories', 'aqualuxe'); ?>
                        <button type="button" class="aqualuxe-filter-section-toggle" aria-expanded="true">
                            <span class="screen-reader-text"><?php esc_html_e('Toggle Categories', 'aqualuxe'); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </h4>
                    
                    <div class="aqualuxe-filter-section-content">
                        <ul class="aqualuxe-filter-list">
                            <?php foreach ($product_categories as $category) : ?>
                                <li>
                                    <label class="aqualuxe-filter-checkbox">
                                        <input type="checkbox" name="categories[]" value="<?php echo esc_attr($category->term_id); ?>" 
                                            <?php checked($current_category, $category->term_id); ?> 
                                            data-filter-type="category">
                                        <span class="aqualuxe-filter-checkbox-text">
                                            <?php echo esc_html($category->name); ?>
                                            <span class="aqualuxe-filter-count">(<?php echo esc_html($category->count); ?>)</span>
                                        </span>
                                    </label>
                                    
                                    <?php
                                    // Display child categories
                                    $child_categories = get_terms([
                                        'taxonomy' => 'product_cat',
                                        'hide_empty' => true,
                                        'parent' => $category->term_id,
                                    ]);
                                    
                                    if ($child_categories) :
                                    ?>
                                        <ul class="aqualuxe-filter-children">
                                            <?php foreach ($child_categories as $child) : ?>
                                                <li>
                                                    <label class="aqualuxe-filter-checkbox">
                                                        <input type="checkbox" name="categories[]" value="<?php echo esc_attr($child->term_id); ?>" 
                                                            <?php checked($current_category, $child->term_id); ?> 
                                                            data-filter-type="category">
                                                        <span class="aqualuxe-filter-checkbox-text">
                                                            <?php echo esc_html($child->name); ?>
                                                            <span class="aqualuxe-filter-count">(<?php echo esc_html($child->count); ?>)</span>
                                                        </span>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($min_price < $max_price) : ?>
                <div class="aqualuxe-filter-section aqualuxe-filter-price">
                    <h4 class="aqualuxe-filter-section-title">
                        <?php esc_html_e('Price Range', 'aqualuxe'); ?>
                        <button type="button" class="aqualuxe-filter-section-toggle" aria-expanded="true">
                            <span class="screen-reader-text"><?php esc_html_e('Toggle Price Range', 'aqualuxe'); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </h4>
                    
                    <div class="aqualuxe-filter-section-content">
                        <div class="aqualuxe-price-slider-wrapper">
                            <div class="aqualuxe-price-slider" 
                                data-min="<?php echo esc_attr($min_price); ?>" 
                                data-max="<?php echo esc_attr($max_price); ?>" 
                                data-step="1">
                            </div>
                            
                            <div class="aqualuxe-price-slider-values">
                                <span class="aqualuxe-price-slider-min-value">
                                    <?php echo get_woocommerce_currency_symbol() . esc_html($min_price); ?>
                                </span>
                                <span class="aqualuxe-price-slider-max-value">
                                    <?php echo get_woocommerce_currency_symbol() . esc_html($max_price); ?>
                                </span>
                            </div>
                            
                            <input type="hidden" name="min_price" value="<?php echo esc_attr($min_price); ?>" data-filter-type="price">
                            <input type="hidden" name="max_price" value="<?php echo esc_attr($max_price); ?>" data-filter-type="price">
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($attribute_taxonomies) : ?>
                <?php foreach ($attribute_taxonomies as $tax) : ?>
                    <?php
                    $taxonomy = 'pa_' . $tax->attribute_name;
                    $terms = get_terms([
                        'taxonomy' => $taxonomy,
                        'hide_empty' => true,
                    ]);
                    
                    if (!$terms) {
                        continue;
                    }
                    ?>
                    
                    <div class="aqualuxe-filter-section aqualuxe-filter-attribute" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                        <h4 class="aqualuxe-filter-section-title">
                            <?php echo esc_html($tax->attribute_label); ?>
                            <button type="button" class="aqualuxe-filter-section-toggle" aria-expanded="true">
                                <span class="screen-reader-text"><?php echo esc_html(sprintf(__('Toggle %s', 'aqualuxe'), $tax->attribute_label)); ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </h4>
                        
                        <div class="aqualuxe-filter-section-content">
                            <ul class="aqualuxe-filter-list">
                                <?php foreach ($terms as $term) : ?>
                                    <li>
                                        <label class="aqualuxe-filter-checkbox">
                                            <input type="checkbox" name="<?php echo esc_attr($taxonomy); ?>[]" value="<?php echo esc_attr($term->term_id); ?>" data-filter-type="attribute" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                                            <span class="aqualuxe-filter-checkbox-text">
                                                <?php echo esc_html($term->name); ?>
                                                <span class="aqualuxe-filter-count">(<?php echo esc_html($term->count); ?>)</span>
                                            </span>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if ($product_tags) : ?>
                <div class="aqualuxe-filter-section aqualuxe-filter-tags">
                    <h4 class="aqualuxe-filter-section-title">
                        <?php esc_html_e('Tags', 'aqualuxe'); ?>
                        <button type="button" class="aqualuxe-filter-section-toggle" aria-expanded="true">
                            <span class="screen-reader-text"><?php esc_html_e('Toggle Tags', 'aqualuxe'); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </h4>
                    
                    <div class="aqualuxe-filter-section-content">
                        <ul class="aqualuxe-filter-list aqualuxe-filter-tags-list">
                            <?php foreach ($product_tags as $tag) : ?>
                                <li>
                                    <label class="aqualuxe-filter-checkbox">
                                        <input type="checkbox" name="tags[]" value="<?php echo esc_attr($tag->term_id); ?>" data-filter-type="tag">
                                        <span class="aqualuxe-filter-checkbox-text">
                                            <?php echo esc_html($tag->name); ?>
                                            <span class="aqualuxe-filter-count">(<?php echo esc_html($tag->count); ?>)</span>
                                        </span>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="aqualuxe-filter-actions">
                <button type="button" class="aqualuxe-filter-reset button">
                    <?php esc_html_e('Reset Filters', 'aqualuxe'); ?>
                </button>
            </div>
        </div>
    </div>
    <?php
}
```

### JavaScript Implementation

Create a JavaScript module for handling the AJAX filtering:

```javascript
/**
 * AquaLuxe Product Filter
 * 
 * Handles AJAX product filtering functionality
 */
class AqualuxeProductFilter {
    /**
     * Initialize the filter
     */
    constructor() {
        // Elements
        this.filterContainer = document.querySelector('.aqualuxe-product-filter');
        this.productsContainer = document.querySelector('.products');
        this.paginationContainer = document.querySelector('.woocommerce-pagination');
        this.orderbySelect = document.querySelector('.woocommerce-ordering select');
        
        // Skip if elements don't exist
        if (!this.filterContainer || !this.productsContainer) {
            return;
        }
        
        // State
        this.isLoading = false;
        this.filterState = {
            categories: [],
            attributes: {},
            tags: [],
            min_price: null,
            max_price: null,
            orderby: '',
            paged: 1
        };
        
        // Nonce
        this.nonce = this.filterContainer.dataset.nonce;
        
        // Initialize price slider if it exists
        this.initPriceSlider();
        
        // Initialize filter toggles
        this.initFilterToggles();
        
        // Bind events
        this.bindEvents();
        
        // Load state from URL
        this.loadStateFromUrl();
    }
    
    /**
     * Initialize price slider
     */
    initPriceSlider() {
        const priceSlider = document.querySelector('.aqualuxe-price-slider');
        
        if (!priceSlider) {
            return;
        }
        
        const min = parseInt(priceSlider.dataset.min, 10);
        const max = parseInt(priceSlider.dataset.max, 10);
        const step = parseInt(priceSlider.dataset.step, 10);
        
        const minInput = document.querySelector('input[name="min_price"]');
        const maxInput = document.querySelector('input[name="max_price"]');
        const minValueDisplay = document.querySelector('.aqualuxe-price-slider-min-value');
        const maxValueDisplay = document.querySelector('.aqualuxe-price-slider-max-value');
        const currencySymbol = minValueDisplay.textContent.replace(/[0-9,.]/g, '');
        
        // Set initial state
        this.filterState.min_price = min;
        this.filterState.max_price = max;
        
        // Create slider (using vanilla JS range slider implementation)
        // This is a simplified example - in a real implementation, you would use a proper range slider library
        // or implement a custom one with proper accessibility support
        
        // For this example, we'll assume a slider has been created and these event handlers are attached
        
        // Update on slider change
        const updatePriceDisplay = (minVal, maxVal) => {
            minInput.value = minVal;
            maxInput.value = maxVal;
            minValueDisplay.textContent = currencySymbol + minVal;
            maxValueDisplay.textContent = currencySymbol + maxVal;
            
            this.filterState.min_price = minVal;
            this.filterState.max_price = maxVal;
            
            this.debounceFilter();
        };
        
        // Mock event listener for price change
        priceSlider.addEventListener('price-change', (e) => {
            updatePriceDisplay(e.detail.min, e.detail.max);
        });
    }
    
    /**
     * Initialize filter section toggles
     */
    initFilterToggles() {
        const toggleButtons = document.querySelectorAll('.aqualuxe-filter-section-toggle');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                const section = button.closest('.aqualuxe-filter-section');
                const content = section.querySelector('.aqualuxe-filter-section-content');
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                
                button.setAttribute('aria-expanded', !isExpanded);
                content.style.display = isExpanded ? 'none' : 'block';
                button.querySelector('i').className = isExpanded ? 'fas fa-chevron-right' : 'fas fa-chevron-down';
            });
        });
        
        // Mobile filter toggle
        const mobileToggle = document.querySelector('.aqualuxe-filter-toggle-mobile');
        const filterContent = document.querySelector('.aqualuxe-filter-content');
        
        if (mobileToggle && filterContent) {
            mobileToggle.addEventListener('click', () => {
                const isVisible = filterContent.classList.contains('active');
                
                if (isVisible) {
                    filterContent.classList.remove('active');
                    document.body.classList.remove('aqualuxe-filter-open');
                } else {
                    filterContent.classList.add('active');
                    document.body.classList.add('aqualuxe-filter-open');
                }
            });
        }
    }
    
    /**
     * Bind events
     */
    bindEvents() {
        // Filter checkboxes
        const checkboxes = this.filterContainer.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.updateFilterState();
                this.filter();
            });
        });
        
        // Reset button
        const resetButton = this.filterContainer.querySelector('.aqualuxe-filter-reset');
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                this.resetFilters();
            });
        }
        
        // Sorting
        if (this.orderbySelect) {
            this.orderbySelect.addEventListener('change', () => {
                this.filterState.orderby = this.orderbySelect.value;
                this.filter();
            });
        }
        
        // Handle browser back/forward
        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.aqualuxeFilter) {
                this.filterState = e.state.aqualuxeFilter;
                this.applyFilterState();
                this.filter(false); // Don't update history when navigating history
            }
        });
    }
    
    /**
     * Update filter state from form inputs
     */
    updateFilterState() {
        // Categories
        const categoryInputs = this.filterContainer.querySelectorAll('input[data-filter-type="category"]:checked');
        this.filterState.categories = Array.from(categoryInputs).map(input => input.value);
        
        // Attributes
        this.filterState.attributes = {};
        const attributeInputs = this.filterContainer.querySelectorAll('input[data-filter-type="attribute"]:checked');
        attributeInputs.forEach(input => {
            const taxonomy = input.dataset.taxonomy;
            if (!this.filterState.attributes[taxonomy]) {
                this.filterState.attributes[taxonomy] = [];
            }
            this.filterState.attributes[taxonomy].push(input.value);
        });
        
        // Tags
        const tagInputs = this.filterContainer.querySelectorAll('input[data-filter-type="tag"]:checked');
        this.filterState.tags = Array.from(tagInputs).map(input => input.value);
        
        // Price is updated directly in the price slider handler
        
        // Sorting
        if (this.orderbySelect) {
            this.filterState.orderby = this.orderbySelect.value;
        }
        
        // Reset pagination when filters change
        this.filterState.paged = 1;
    }
    
    /**
     * Apply current filter state to form inputs
     */
    applyFilterState() {
        // Reset all checkboxes
        const checkboxes = this.filterContainer.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Apply categories
        this.filterState.categories.forEach(categoryId => {
            const input = this.filterContainer.querySelector(`input[data-filter-type="category"][value="${categoryId}"]`);
            if (input) {
                input.checked = true;
            }
        });
        
        // Apply attributes
        for (const [taxonomy, termIds] of Object.entries(this.filterState.attributes)) {
            termIds.forEach(termId => {
                const input = this.filterContainer.querySelector(`input[data-filter-type="attribute"][data-taxonomy="${taxonomy}"][value="${termId}"]`);
                if (input) {
                    input.checked = true;
                }
            });
        }
        
        // Apply tags
        this.filterState.tags.forEach(tagId => {
            const input = this.filterContainer.querySelector(`input[data-filter-type="tag"][value="${tagId}"]`);
            if (input) {
                input.checked = true;
            }
        });
        
        // Apply price
        if (this.filterState.min_price !== null && this.filterState.max_price !== null) {
            const minInput = document.querySelector('input[name="min_price"]');
            const maxInput = document.querySelector('input[name="max_price"]');
            const minValueDisplay = document.querySelector('.aqualuxe-price-slider-min-value');
            const maxValueDisplay = document.querySelector('.aqualuxe-price-slider-max-value');
            const currencySymbol = minValueDisplay ? minValueDisplay.textContent.replace(/[0-9,.]/g, '') : '$';
            
            if (minInput && maxInput) {
                minInput.value = this.filterState.min_price;
                maxInput.value = this.filterState.max_price;
            }
            
            if (minValueDisplay && maxValueDisplay) {
                minValueDisplay.textContent = currencySymbol + this.filterState.min_price;
                maxValueDisplay.textContent = currencySymbol + this.filterState.max_price;
            }
            
            // Update slider position (implementation depends on the slider library used)
            const priceSlider = document.querySelector('.aqualuxe-price-slider');
            if (priceSlider) {
                // Dispatch custom event to update slider
                const event = new CustomEvent('update-slider', {
                    detail: {
                        min: this.filterState.min_price,
                        max: this.filterState.max_price
                    }
                });
                priceSlider.dispatchEvent(event);
            }
        }
        
        // Apply sorting
        if (this.orderbySelect && this.filterState.orderby) {
            this.orderbySelect.value = this.filterState.orderby;
        }
    }
    
    /**
     * Reset all filters
     */
    resetFilters() {
        // Reset filter state
        this.filterState = {
            categories: [],
            attributes: {},
            tags: [],
            min_price: null,
            max_price: null,
            orderby: '',
            paged: 1
        };
        
        // Reset form inputs
        const checkboxes = this.filterContainer.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Reset price slider
        const priceSlider = document.querySelector('.aqualuxe-price-slider');
        if (priceSlider) {
            const min = parseInt(priceSlider.dataset.min, 10);
            const max = parseInt(priceSlider.dataset.max, 10);
            
            this.filterState.min_price = min;
            this.filterState.max_price = max;
            
            const minInput = document.querySelector('input[name="min_price"]');
            const maxInput = document.querySelector('input[name="max_price"]');
            const minValueDisplay = document.querySelector('.aqualuxe-price-slider-min-value');
            const maxValueDisplay = document.querySelector('.aqualuxe-price-slider-max-value');
            const currencySymbol = minValueDisplay.textContent.replace(/[0-9,.]/g, '');
            
            if (minInput && maxInput) {
                minInput.value = min;
                maxInput.value = max;
            }
            
            if (minValueDisplay && maxValueDisplay) {
                minValueDisplay.textContent = currencySymbol + min;
                maxValueDisplay.textContent = currencySymbol + max;
            }
            
            // Reset slider position
            const event = new CustomEvent('update-slider', {
                detail: {
                    min: min,
                    max: max
                }
            });
            priceSlider.dispatchEvent(event);
        }
        
        // Reset sorting
        if (this.orderbySelect) {
            this.orderbySelect.value = '';
        }
        
        // Apply filter
        this.filter();
    }
    
    /**
     * Load filter state from URL parameters
     */
    loadStateFromUrl() {
        const params = new URLSearchParams(window.location.search);
        
        // Categories
        if (params.has('categories')) {
            this.filterState.categories = params.get('categories').split(',');
        }
        
        // Attributes
        const attributeParams = Array.from(params.entries())
            .filter(([key]) => key.startsWith('pa_'));
        
        attributeParams.forEach(([taxonomy, values]) => {
            this.filterState.attributes[taxonomy] = values.split(',');
        });
        
        // Tags
        if (params.has('tags')) {
            this.filterState.tags = params.get('tags').split(',');
        }
        
        // Price
        if (params.has('min_price') && params.has('max_price')) {
            this.filterState.min_price = parseInt(params.get('min_price'), 10);
            this.filterState.max_price = parseInt(params.get('max_price'), 10);
        }
        
        // Sorting
        if (params.has('orderby')) {
            this.filterState.orderby = params.get('orderby');
        }
        
        // Pagination
        if (params.has('paged')) {
            this.filterState.paged = parseInt(params.get('paged'), 10);
        }
        
        // Apply state to form
        if (this.hasActiveFilters()) {
            this.applyFilterState();
        }
    }
    
    /**
     * Check if there are active filters
     */
    hasActiveFilters() {
        return (
            this.filterState.categories.length > 0 ||
            Object.keys(this.filterState.attributes).length > 0 ||
            this.filterState.tags.length > 0 ||
            (this.filterState.min_price !== null && this.filterState.max_price !== null) ||
            this.filterState.orderby !== ''
        );
    }
    
    /**
     * Update URL with current filter state
     */
    updateUrl() {
        const params = new URLSearchParams();
        
        // Categories
        if (this.filterState.categories.length > 0) {
            params.set('categories', this.filterState.categories.join(','));
        }
        
        // Attributes
        for (const [taxonomy, termIds] of Object.entries(this.filterState.attributes)) {
            if (termIds.length > 0) {
                params.set(taxonomy, termIds.join(','));
            }
        }
        
        // Tags
        if (this.filterState.tags.length > 0) {
            params.set('tags', this.filterState.tags.join(','));
        }
        
        // Price
        if (this.filterState.min_price !== null && this.filterState.max_price !== null) {
            params.set('min_price', this.filterState.min_price);
            params.set('max_price', this.filterState.max_price);
        }
        
        // Sorting
        if (this.filterState.orderby) {
            params.set('orderby', this.filterState.orderby);
        }
        
        // Pagination
        if (this.filterState.paged > 1) {
            params.set('paged', this.filterState.paged);
        }
        
        // Build URL
        const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        
        // Update browser history
        window.history.pushState(
            { aqualuxeFilter: this.filterState },
            '',
            url
        );
    }
    
    /**
     * Perform AJAX filter request
     */
    filter(updateHistory = true) {
        // Prevent multiple simultaneous requests
        if (this.isLoading) {
            return;
        }
        
        this.isLoading = true;
        
        // Show loading state
        this.showLoading();
        
        // Update URL if needed
        if (updateHistory) {
            this.updateUrl();
        }
        
        // Prepare data
        const formData = new FormData();
        formData.append('action', 'aqualuxe_filter_products');
        formData.append('nonce', this.nonce);
        
        // Categories
        if (this.filterState.categories.length > 0) {
            formData.append('categories', this.filterState.categories.join(','));
        }
        
        // Attributes
        if (Object.keys(this.filterState.attributes).length > 0) {
            formData.append('attributes', JSON.stringify(this.filterState.attributes));
        }
        
        // Tags
        if (this.filterState.tags.length > 0) {
            formData.append('tags', this.filterState.tags.join(','));
        }
        
        // Price
        if (this.filterState.min_price !== null && this.filterState.max_price !== null) {
            formData.append('min_price', this.filterState.min_price);
            formData.append('max_price', this.filterState.max_price);
        }
        
        // Sorting
        if (this.filterState.orderby) {
            formData.append('orderby', this.filterState.orderby);
        }
        
        // Pagination
        formData.append('paged', this.filterState.paged);
        
        // Send AJAX request
        fetch(aqualuxe_ajax.url, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update products
                this.productsContainer.innerHTML = data.data.products_html;
                
                // Update pagination if it exists
                if (this.paginationContainer) {
                    // Check if pagination HTML is included in the response
                    if (data.data.products_html.includes('aqualuxe-filter-pagination')) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.data.products_html;
                        const newPagination = tempDiv.querySelector('.aqualuxe-filter-pagination');
                        
                        if (newPagination) {
                            this.paginationContainer.innerHTML = newPagination.outerHTML;
                            this.bindPaginationEvents();
                        } else {
                            this.paginationContainer.innerHTML = '';
                        }
                    } else {
                        this.paginationContainer.innerHTML = '';
                    }
                }
                
                // Update filter counts
                this.updateFilterCounts(data.data.filter_counts);
                
                // Scroll to top of products if not on page 1
                if (this.filterState.paged > 1) {
                    const scrollTarget = this.productsContainer.closest('.products-wrapper') || this.productsContainer;
                    scrollTarget.scrollIntoView({ behavior: 'smooth' });
                }
                
                // Trigger custom event for other scripts
                const event = new CustomEvent('aqualuxe_products_filtered', {
                    detail: {
                        filterState: this.filterState,
                        totalProducts: data.data.total_products,
                        maxPages: data.data.max_pages
                    }
                });
                document.dispatchEvent(event);
            } else {
                console.error('Filter request failed:', data.data.message);
            }
        })
        .catch(error => {
            console.error('Filter request error:', error);
        })
        .finally(() => {
            this.hideLoading();
            this.isLoading = false;
        });
    }
    
    /**
     * Update filter counts
     */
    updateFilterCounts(counts) {
        // Update category counts
        if (counts.categories) {
            for (const [categoryId, count] of Object.entries(counts.categories)) {
                const countElement = this.filterContainer.querySelector(`input[data-filter-type="category"][value="${categoryId}"] + .aqualuxe-filter-checkbox-text .aqualuxe-filter-count`);
                if (countElement) {
                    countElement.textContent = `(${count})`;
                    
                    // Disable checkbox if count is 0 and not checked
                    const checkbox = this.filterContainer.querySelector(`input[data-filter-type="category"][value="${categoryId}"]`);
                    if (checkbox && !checkbox.checked && count === 0) {
                        checkbox.disabled = true;
                        checkbox.parentElement.classList.add('disabled');
                    } else if (checkbox) {
                        checkbox.disabled = false;
                        checkbox.parentElement.classList.remove('disabled');
                    }
                }
            }
        }
        
        // Update attribute counts
        if (counts.attributes) {
            for (const [taxonomy, terms] of Object.entries(counts.attributes)) {
                for (const [termId, count] of Object.entries(terms)) {
                    const countElement = this.filterContainer.querySelector(`input[data-filter-type="attribute"][data-taxonomy="${taxonomy}"][value="${termId}"] + .aqualuxe-filter-checkbox-text .aqualuxe-filter-count`);
                    if (countElement) {
                        countElement.textContent = `(${count})`;
                        
                        // Disable checkbox if count is 0 and not checked
                        const checkbox = this.filterContainer.querySelector(`input[data-filter-type="attribute"][data-taxonomy="${taxonomy}"][value="${termId}"]`);
                        if (checkbox && !checkbox.checked && count === 0) {
                            checkbox.disabled = true;
                            checkbox.parentElement.classList.add('disabled');
                        } else if (checkbox) {
                            checkbox.disabled = false;
                            checkbox.parentElement.classList.remove('disabled');
                        }
                    }
                }
            }
        }
        
        // Update tag counts
        if (counts.tags) {
            for (const [tagId, count] of Object.entries(counts.tags)) {
                const countElement = this.filterContainer.querySelector(`input[data-filter-type="tag"][value="${tagId}"] + .aqualuxe-filter-checkbox-text .aqualuxe-filter-count`);
                if (countElement) {
                    countElement.textContent = `(${count})`;
                    
                    // Disable checkbox if count is 0 and not checked
                    const checkbox = this.filterContainer.querySelector(`input[data-filter-type="tag"][value="${tagId}"]`);
                    if (checkbox && !checkbox.checked && count === 0) {
                        checkbox.disabled = true;
                        checkbox.parentElement.classList.add('disabled');
                    } else if (checkbox) {
                        checkbox.disabled = false;
                        checkbox.parentElement.classList.remove('disabled');
                    }
                }
            }
        }
    }
    
    /**
     * Bind pagination events
     */
    bindPaginationEvents() {
        const paginationLinks = document.querySelectorAll('.aqualuxe-filter-pagination a');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Extract page number from URL
                const href = link.getAttribute('href');
                const url = new URL(href, window.location.origin);
                const page = url.searchParams.get('paged') || 1;
                
                // Update filter state
                this.filterState.paged = parseInt(page, 10);
                
                // Apply filter
                this.filter();
            });
        });
    }
    
    /**
     * Show loading state
     */
    showLoading() {
        // Add loading class to products container
        this.productsContainer.classList.add('loading');
        
        // Create loading overlay if it doesn't exist
        if (!document.querySelector('.aqualuxe-filter-loading')) {
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'aqualuxe-filter-loading';
            loadingOverlay.innerHTML = '<div class="aqualuxe-filter-loading-spinner"></div>';
            this.productsContainer.parentNode.insertBefore(loadingOverlay, this.productsContainer.nextSibling);
        }
    }
    
    /**
     * Hide loading state
     */
    hideLoading() {
        // Remove loading class
        this.productsContainer.classList.remove('loading');
        
        // Remove loading overlay
        const loadingOverlay = document.querySelector('.aqualuxe-filter-loading');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
    }
    
    /**
     * Debounce filter function for price slider
     */
    debounceFilter() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            this.filter();
        }, 500);
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new AqualuxeProductFilter();
});
```

### CSS Implementation

```css
/**
 * AquaLuxe Product Filter Styles
 */

/* Filter Container */
.aqualuxe-product-filter {
    margin-bottom: 2rem;
    border: 1px solid #eee;
    border-radius: 4px;
    background-color: #f9f9f9;
}

/* Filter Header */
.aqualuxe-filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #eee;
    background-color: #f5f5f5;
}

.aqualuxe-filter-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.aqualuxe-filter-toggle-mobile {
    display: none;
    background: none;
    border: none;
    color: #333;
    font-size: 1.2rem;
    cursor: pointer;
}

/* Filter Content */
.aqualuxe-filter-content {
    padding: 1rem;
}

/* Filter Sections */
.aqualuxe-filter-section {
    margin-bottom: 1.5rem;
}

.aqualuxe-filter-section:last-child {
    margin-bottom: 0;
}

.aqualuxe-filter-section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0 0 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #eee;
    font-size: 1rem;
    font-weight: 600;
}

.aqualuxe-filter-section-toggle {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 0;
    font-size: 0.8rem;
}

/* Filter Lists */
.aqualuxe-filter-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.aqualuxe-filter-list li {
    margin-bottom: 0.5rem;
}

.aqualuxe-filter-list li:last-child {
    margin-bottom: 0;
}

.aqualuxe-filter-children {
    list-style: none;
    padding-left: 1.5rem;
    margin: 0.5rem 0 0;
}

/* Checkboxes */
.aqualuxe-filter-checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.aqualuxe-filter-checkbox input {
    margin-right: 0.5rem;
}

.aqualuxe-filter-checkbox-text {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex: 1;
}

.aqualuxe-filter-count {
    color: #999;
    font-size: 0.85em;
    margin-left: 0.5rem;
}

.aqualuxe-filter-checkbox.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Price Slider */
.aqualuxe-price-slider-wrapper {
    padding: 1rem 0.5rem;
}

.aqualuxe-price-slider {
    height: 5px;
    background-color: #ddd;
    border-radius: 3px;
    position: relative;
    margin-bottom: 1.5rem;
}

.aqualuxe-price-slider-values {
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
}

/* Filter Actions */
.aqualuxe-filter-actions {
    margin-top: 1.5rem;
    text-align: center;
}

.aqualuxe-filter-reset {
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    color: #666;
}

.aqualuxe-filter-reset:hover {
    background-color: #eee;
}

/* Loading State */
.products.loading {
    opacity: 0.5;
}

.aqualuxe-filter-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.aqualuxe-filter-loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: #0073aa;
    border-radius: 50%;
    animation: aqualuxe-filter-spin 1s linear infinite;
}

@keyframes aqualuxe-filter-spin {
    to {
        transform: rotate(360deg);
    }
}

/* Pagination */
.aqualuxe-filter-pagination {
    margin-top: 2rem;
    text-align: center;
}

.aqualuxe-filter-pagination ul {
    display: inline-flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.aqualuxe-filter-pagination li {
    margin: 0 0.25rem;
}

.aqualuxe-filter-pagination a,
.aqualuxe-filter-pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #333;
}

.aqualuxe-filter-pagination span.current {
    background-color: #0073aa;
    border-color: #0073aa;
    color: #fff;
}

.aqualuxe-filter-pagination a:hover {
    background-color: #f5f5f5;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .aqualuxe-filter-toggle-mobile {
        display: block;
    }
    
    .aqualuxe-filter-content {
        display: none;
    }
    
    .aqualuxe-filter-content.active {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 999;
        background-color: #fff;
        overflow-y: auto;
        padding: 1rem;
    }
    
    body.aqualuxe-filter-open {
        overflow: hidden;
    }
    
    .aqualuxe-filter-content.active .aqualuxe-filter-section-title {
        padding-right: 2.5rem;
    }
    
    .aqualuxe-filter-content.active .aqualuxe-filter-actions {
        position: sticky;
        bottom: 0;
        background-color: #fff;
        padding: 1rem 0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    }
}

/* Dark Mode Styles */
.dark-mode .aqualuxe-product-filter {
    border-color: #444;
    background-color: #222;
}

.dark-mode .aqualuxe-filter-header {
    border-color: #444;
    background-color: #333;
}

.dark-mode .aqualuxe-filter-section-title {
    border-color: #444;
}

.dark-mode .aqualuxe-filter-reset {
    background-color: #333;
    border-color: #444;
    color: #ccc;
}

.dark-mode .aqualuxe-filter-reset:hover {
    background-color: #444;
}

.dark-mode .aqualuxe-price-slider {
    background-color: #444;
}

.dark-mode .aqualuxe-filter-loading {
    background-color: rgba(0, 0, 0, 0.7);
}

.dark-mode .aqualuxe-filter-pagination a,
.dark-mode .aqualuxe-filter-pagination span {
    border-color: #444;
    color: #ccc;
}

.dark-mode .aqualuxe-filter-pagination a:hover {
    background-color: #333;
}
```

## Integration

### 1. Register Scripts and Styles

Add the following to `functions.php`:

```php
/**
 * Register AJAX filter scripts and styles
 */
function aqualuxe_register_filter_assets() {
    // Register styles
    wp_register_style(
        'aqualuxe-product-filter',
        get_template_directory_uri() . '/assets/css/product-filter.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Register scripts
    wp_register_script(
        'aqualuxe-product-filter',
        get_template_directory_uri() . '/assets/js/product-filter.js',
        array(),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script('aqualuxe-product-filter', 'aqualuxe_ajax', array(
        'url' => admin_url('admin-ajax.php'),
    ));
    
    // Enqueue on shop and archive pages
    if (is_shop() || is_product_category() || is_product_tag()) {
        wp_enqueue_style('aqualuxe-product-filter');
        wp_enqueue_script('aqualuxe-product-filter');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_register_filter_assets');
```

### 2. Add Filter Widget to Shop Sidebar

Add the following to `woocommerce/archive-product.php`:

```php
<?php
/**
 * The Template for displaying product archives
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 */
do_action('woocommerce_before_main_content');

?>
<header class="woocommerce-products-header">
    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
        <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
    <?php endif; ?>

    <?php
    /**
     * Hook: woocommerce_archive_description.
     */
    do_action('woocommerce_archive_description');
    ?>
</header>

<div class="aqualuxe-shop-container">
    <div class="aqualuxe-shop-sidebar">
        <?php aqualuxe_product_filter_widget(); ?>
    </div>
    
    <div class="aqualuxe-shop-content">
        <?php
        if (woocommerce_product_loop()) {
            /**
             * Hook: woocommerce_before_shop_loop.
             */
            do_action('woocommerce_before_shop_loop');

            woocommerce_product_loop_start();

            if (wc_get_loop_prop('total')) {
                while (have_posts()) {
                    the_post();

                    /**
                     * Hook: woocommerce_shop_loop.
                     */
                    do_action('woocommerce_shop_loop');

                    wc_get_template_part('content', 'product');
                }
            }

            woocommerce_product_loop_end();

            /**
             * Hook: woocommerce_after_shop_loop.
             */
            do_action('woocommerce_after_shop_loop');
        } else {
            /**
             * Hook: woocommerce_no_products_found.
             */
            do_action('woocommerce_no_products_found');
        }
        ?>
    </div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 */
do_action('woocommerce_after_main_content');

get_footer('shop');
```

## Testing Plan

1. **Functionality Testing**
   - Verify all filter options work correctly
   - Test multiple filter combinations
   - Verify count updates are accurate
   - Test price slider functionality
   - Verify pagination works with filters applied
   - Test filter reset functionality

2. **Performance Testing**
   - Test with large product catalogs (1000+ products)
   - Measure AJAX request response times
   - Test with multiple simultaneous users
   - Verify browser memory usage remains stable

3. **Browser Compatibility**
   - Test in Chrome, Firefox, Safari, and Edge
   - Test in mobile browsers (iOS Safari, Chrome for Android)
   - Verify functionality in older browser versions

4. **Accessibility Testing**
   - Test keyboard navigation
   - Verify screen reader compatibility
   - Check color contrast ratios
   - Test with browser zoom levels

5. **Mobile Testing**
   - Test on various screen sizes
   - Verify touch interactions work correctly
   - Test filter panel opening/closing
   - Verify responsive layout adjustments

## Future Enhancements

1. **Saved Filters**
   - Allow users to save filter combinations
   - Implement user-specific saved filters

2. **Filter Presets**
   - Create admin-defined filter presets
   - Add quick filter buttons for common combinations

3. **Visual Filter Builder**
   - Create admin interface for building custom filters
   - Allow drag-and-drop arrangement of filter sections

4. **Advanced Caching**
   - Implement server-side caching of filter results
   - Add browser-side caching for faster repeat filtering

5. **Analytics Integration**
   - Track most used filter combinations
   - Provide insights on filter usage patterns

## Conclusion

This AJAX product filtering system will significantly enhance the shopping experience on the AquaLuxe theme by allowing users to quickly find products that match their specific requirements without page reloads. The implementation follows best practices for performance, accessibility, and user experience, with a focus on mobile responsiveness and cross-browser compatibility.