<?php
/**
 * AquaLuxe Related Products Algorithm
 *
 * Advanced algorithm for suggesting related products based on multiple factors
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Replace default WooCommerce related products with our enhanced algorithm
 */
function aqualuxe_remove_related_products() {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_enhanced_related_products', 20);
}
add_action('wp', 'aqualuxe_remove_related_products');

/**
 * Enhanced related products algorithm
 */
function aqualuxe_enhanced_related_products() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Get product ID
    $product_id = $product->get_id();
    
    // Get related products settings from customizer
    $related_count = get_theme_mod('aqualuxe_related_products_count', 4);
    $related_columns = get_theme_mod('aqualuxe_related_products_columns', 4);
    $related_algorithm = get_theme_mod('aqualuxe_related_products_algorithm', 'smart');
    
    // Get related products based on selected algorithm
    switch ($related_algorithm) {
        case 'category':
            $related_products = aqualuxe_get_related_by_category($product_id, $related_count);
            break;
        case 'tag':
            $related_products = aqualuxe_get_related_by_tag($product_id, $related_count);
            break;
        case 'attribute':
            $related_products = aqualuxe_get_related_by_attribute($product_id, $related_count);
            break;
        case 'purchased_together':
            $related_products = aqualuxe_get_frequently_purchased_together($product_id, $related_count);
            break;
        case 'viewed_together':
            $related_products = aqualuxe_get_frequently_viewed_together($product_id, $related_count);
            break;
        case 'smart':
        default:
            $related_products = aqualuxe_get_smart_related_products($product_id, $related_count);
            break;
    }
    
    // If no related products found, try the default WooCommerce algorithm
    if (empty($related_products)) {
        $related_products = array_filter(array_map('wc_get_product', wc_get_related_products($product_id, $related_count)));
    }
    
    // If still no related products, try to get products from the same category
    if (empty($related_products)) {
        $related_products = aqualuxe_get_related_by_category($product_id, $related_count);
    }
    
    // If we have related products, display them
    if ($related_products) {
        woocommerce_related_products(array(
            'posts_per_page' => $related_count,
            'columns'        => $related_columns,
            'orderby'        => 'rand',
            'ids'            => array_map(function($product) { return $product->get_id(); }, $related_products),
        ));
    }
}

/**
 * Get related products by category
 */
function aqualuxe_get_related_by_category($product_id, $limit = 4) {
    $product_categories = wc_get_product_term_ids($product_id, 'product_cat');
    
    if (empty($product_categories)) {
        return array();
    }
    
    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'orderby'             => 'rand',
        'post__not_in'        => array($product_id),
        'tax_query'           => array(
            array(
                'taxonomy'    => 'product_cat',
                'field'       => 'term_id',
                'terms'       => $product_categories,
            ),
        ),
    );
    
    $products = new WP_Query($args);
    
    return array_filter(array_map('wc_get_product', wp_list_pluck($products->posts, 'ID')));
}

/**
 * Get related products by tag
 */
function aqualuxe_get_related_by_tag($product_id, $limit = 4) {
    $product_tags = wc_get_product_term_ids($product_id, 'product_tag');
    
    if (empty($product_tags)) {
        return array();
    }
    
    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'orderby'             => 'rand',
        'post__not_in'        => array($product_id),
        'tax_query'           => array(
            array(
                'taxonomy'    => 'product_tag',
                'field'       => 'term_id',
                'terms'       => $product_tags,
            ),
        ),
    );
    
    $products = new WP_Query($args);
    
    return array_filter(array_map('wc_get_product', wp_list_pluck($products->posts, 'ID')));
}

/**
 * Get related products by attribute
 */
function aqualuxe_get_related_by_attribute($product_id, $limit = 4) {
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return array();
    }
    
    // Get product attributes
    $attributes = $product->get_attributes();
    
    if (empty($attributes)) {
        return array();
    }
    
    // Get the most specific attribute (with fewest terms)
    $best_attribute = null;
    $best_attribute_count = PHP_INT_MAX;
    
    foreach ($attributes as $attribute) {
        if ($attribute->is_taxonomy()) {
            $terms = $attribute->get_terms();
            $count = count($terms);
            
            if ($count > 0 && $count < $best_attribute_count) {
                $best_attribute = $attribute;
                $best_attribute_count = $count;
            }
        }
    }
    
    if (!$best_attribute) {
        return array();
    }
    
    // Get the taxonomy and terms
    $taxonomy = $best_attribute->get_taxonomy();
    $terms = wp_list_pluck($best_attribute->get_terms(), 'term_id');
    
    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'orderby'             => 'rand',
        'post__not_in'        => array($product_id),
        'tax_query'           => array(
            array(
                'taxonomy'    => $taxonomy,
                'field'       => 'term_id',
                'terms'       => $terms,
            ),
        ),
    );
    
    $products = new WP_Query($args);
    
    return array_filter(array_map('wc_get_product', wp_list_pluck($products->posts, 'ID')));
}

/**
 * Get products frequently purchased together
 */
function aqualuxe_get_frequently_purchased_together($product_id, $limit = 4) {
    global $wpdb;
    
    // Get order IDs containing this product
    $order_ids = $wpdb->get_col($wpdb->prepare("
        SELECT order_id
        FROM {$wpdb->prefix}woocommerce_order_items oi
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id
        WHERE oim.meta_key = '_product_id'
        AND oim.meta_value = %d
        AND oi.order_item_type = 'line_item'
    ", $product_id));
    
    if (empty($order_ids)) {
        return array();
    }
    
    // Get products from these orders, excluding the current product
    $product_ids = $wpdb->get_col("
        SELECT oim.meta_value
        FROM {$wpdb->prefix}woocommerce_order_items oi
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id
        WHERE oim.meta_key = '_product_id'
        AND oim.meta_value != {$product_id}
        AND oi.order_item_type = 'line_item'
        AND oi.order_id IN (" . implode(',', $order_ids) . ")
        GROUP BY oim.meta_value
        ORDER BY COUNT(oim.meta_value) DESC
        LIMIT {$limit}
    ");
    
    return array_filter(array_map('wc_get_product', $product_ids));
}

/**
 * Get products frequently viewed together
 */
function aqualuxe_get_frequently_viewed_together($product_id, $limit = 4) {
    // Get viewed together data from user session or cookies
    $viewed_together = get_option('aqualuxe_viewed_together', array());
    
    if (empty($viewed_together) || !isset($viewed_together[$product_id])) {
        return array();
    }
    
    // Sort by frequency and get top products
    arsort($viewed_together[$product_id]);
    $related_ids = array_keys(array_slice($viewed_together[$product_id], 0, $limit, true));
    
    return array_filter(array_map('wc_get_product', $related_ids));
}

/**
 * Smart algorithm combining multiple factors
 */
function aqualuxe_get_smart_related_products($product_id, $limit = 4) {
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return array();
    }
    
    // Get products from different sources
    $category_products = aqualuxe_get_related_by_category($product_id, $limit * 2);
    $tag_products = aqualuxe_get_related_by_tag($product_id, $limit * 2);
    $attribute_products = aqualuxe_get_related_by_attribute($product_id, $limit * 2);
    $purchased_together = aqualuxe_get_frequently_purchased_together($product_id, $limit * 2);
    $viewed_together = aqualuxe_get_frequently_viewed_together($product_id, $limit * 2);
    
    // Combine all products
    $all_products = array_merge(
        $purchased_together,  // Highest priority - purchased together
        $viewed_together,     // High priority - viewed together
        $attribute_products,  // Medium priority - same attributes
        $tag_products,        // Lower priority - same tags
        $category_products    // Lowest priority - same category
    );
    
    // Remove duplicates while preserving priority
    $unique_products = array();
    $unique_ids = array();
    
    foreach ($all_products as $related_product) {
        $related_id = $related_product->get_id();
        
        if (!in_array($related_id, $unique_ids) && $related_id != $product_id) {
            $unique_products[] = $related_product;
            $unique_ids[] = $related_id;
            
            if (count($unique_products) >= $limit) {
                break;
            }
        }
    }
    
    return $unique_products;
}

/**
 * Track products viewed together
 */
function aqualuxe_track_viewed_together() {
    // Only track on single product pages
    if (!is_product()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $current_id = $product->get_id();
    $viewed_products = WC()->session->get('recently_viewed_products', array());
    
    // Skip if this is the first product viewed
    if (empty($viewed_products)) {
        $viewed_products[] = $current_id;
        WC()->session->set('recently_viewed_products', $viewed_products);
        return;
    }
    
    // Add current product to recently viewed
    if (!in_array($current_id, $viewed_products)) {
        $viewed_products[] = $current_id;
        
        // Keep only the last 10 products
        if (count($viewed_products) > 10) {
            $viewed_products = array_slice($viewed_products, -10);
        }
        
        WC()->session->set('recently_viewed_products', $viewed_products);
    }
    
    // Update viewed together data
    $viewed_together = get_option('aqualuxe_viewed_together', array());
    
    // For each previously viewed product, increment the counter for the current product
    foreach ($viewed_products as $viewed_id) {
        if ($viewed_id == $current_id) {
            continue;
        }
        
        // Initialize arrays if needed
        if (!isset($viewed_together[$viewed_id])) {
            $viewed_together[$viewed_id] = array();
        }
        
        if (!isset($viewed_together[$viewed_id][$current_id])) {
            $viewed_together[$viewed_id][$current_id] = 0;
        }
        
        // Increment counter
        $viewed_together[$viewed_id][$current_id]++;
        
        // Do the same for the current product
        if (!isset($viewed_together[$current_id])) {
            $viewed_together[$current_id] = array();
        }
        
        if (!isset($viewed_together[$current_id][$viewed_id])) {
            $viewed_together[$current_id][$viewed_id] = 0;
        }
        
        $viewed_together[$current_id][$viewed_id]++;
    }
    
    // Save the data
    update_option('aqualuxe_viewed_together', $viewed_together);
}
add_action('template_redirect', 'aqualuxe_track_viewed_together');

/**
 * Add related products settings to customizer
 */
function aqualuxe_related_products_customizer($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_related_products', array(
        'title'    => __('Related Products', 'aqualuxe'),
        'priority' => 30,
        'panel'    => 'woocommerce',
    ));
    
    // Number of related products
    $wp_customize->add_setting('aqualuxe_related_products_count', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_related_products_count', array(
        'label'    => __('Number of Related Products', 'aqualuxe'),
        'section'  => 'aqualuxe_related_products',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ),
    ));
    
    // Number of columns
    $wp_customize->add_setting('aqualuxe_related_products_columns', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_related_products_columns', array(
        'label'    => __('Number of Columns', 'aqualuxe'),
        'section'  => 'aqualuxe_related_products',
        'type'     => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 6,
            'step' => 1,
        ),
    ));
    
    // Algorithm selection
    $wp_customize->add_setting('aqualuxe_related_products_algorithm', array(
        'default'           => 'smart',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_related_products_algorithm', array(
        'label'    => __('Related Products Algorithm', 'aqualuxe'),
        'section'  => 'aqualuxe_related_products',
        'type'     => 'select',
        'choices'  => array(
            'smart'              => __('Smart (Combined Factors)', 'aqualuxe'),
            'category'           => __('Same Category', 'aqualuxe'),
            'tag'                => __('Same Tags', 'aqualuxe'),
            'attribute'          => __('Same Attributes', 'aqualuxe'),
            'purchased_together' => __('Frequently Purchased Together', 'aqualuxe'),
            'viewed_together'    => __('Frequently Viewed Together', 'aqualuxe'),
        ),
    ));
}
add_action('customize_register', 'aqualuxe_related_products_customizer');