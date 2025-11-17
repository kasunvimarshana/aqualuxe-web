<?php
/**
 * SEO Optimizations
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add schema markup to body
add_action('wp_head', 'aqualuxe_schema_markup');
if (!function_exists('aqualuxe_schema_markup')) {
    /**
     * Add schema markup to body tag
     *
     * @since 1.0.0
     */
    function aqualuxe_schema_markup() {
        if (is_home() || is_front_page()) {
            echo '<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebSite","url":"' . esc_url(home_url('/')) . '"}</script>' . "\n";
        }
    }
}

// Add Open Graph meta tags
add_action('wp_head', 'aqualuxe_opengraph_tags');
if (!function_exists('aqualuxe_opengraph_tags')) {
    /**
     * Add Open Graph meta tags
     *
     * @since 1.0.0
     */
    function aqualuxe_opengraph_tags() {
        if (is_singular()) {
            global $post;
            
            // Get post data
            $title = get_the_title();
            $description = wp_trim_words(strip_tags(get_the_excerpt()), 30);
            $url = get_permalink();
            $site_name = get_bloginfo('name');
            
            // Get featured image
            $image = '';
            if (has_post_thumbnail($post->ID)) {
                $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $image = $image_data[0];
            }
            
            // Output Open Graph tags
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
            
            if ($image) {
                echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
            }
        }
    }
}

// Add Twitter Card meta tags
add_action('wp_head', 'aqualuxe_twitter_cards');
if (!function_exists('aqualuxe_twitter_cards')) {
    /**
     * Add Twitter Card meta tags
     *
     * @since 1.0.0
     */
    function aqualuxe_twitter_cards() {
        if (is_singular()) {
            global $post;
            
            // Get post data
            $title = get_the_title();
            $description = wp_trim_words(strip_tags(get_the_excerpt()), 30);
            
            // Get featured image
            $image = '';
            if (has_post_thumbnail($post->ID)) {
                $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $image = $image_data[0];
            }
            
            // Output Twitter Card tags
            echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
            echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
            
            if ($image) {
                echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
            }
        }
    }
}

// Add product schema markup for WooCommerce products
add_action('wp_head', 'aqualuxe_product_schema_markup');
if (!function_exists('aqualuxe_product_schema_markup')) {
    /**
     * Add product schema markup for WooCommerce products
     *
     * @since 1.0.0
     */
    function aqualuxe_product_schema_markup() {
        if (class_exists('WooCommerce') && is_product()) {
            // Get the global product object
            global $product;
            
            // If $product is not set or is not a WC_Product object, try to get it properly
            if (!$product || !is_a($product, 'WC_Product')) {
                $product = wc_get_product(get_the_ID());
            }
            
            // If we still don't have a product, return
            if (!$product || !is_a($product, 'WC_Product')) {
                return;
            }
            
            $schema = array(
                '@context' => 'https://schema.org/',
                '@type' => 'Product',
                'name' => $product->get_name(),
                'description' => $product->get_short_description(),
                'sku' => $product->get_sku(),
                'offers' => array(
                    '@type' => 'Offer',
                    'price' => $product->get_price(),
                    'priceCurrency' => get_woocommerce_currency(),
                    'availability' => $product->is_in_stock() ? 'InStock' : 'OutOfStock',
                    'url' => $product->get_permalink()
                )
            );
            
            // Add image if available
            if (has_post_thumbnail($product->get_id())) {
                $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
                $schema['image'] = $image_data[0];
            }
            
            // Add brand if available
            $brand = $product->get_attribute('brand');
            if ($brand) {
                $schema['brand'] = array(
                    '@type' => 'Brand',
                    'name' => $brand
                );
            }
            
            echo '<script type="application/ld+json">' . json_encode($schema) . '</script>' . "\n";
        }
    }
}

// Optimize title tags
add_filter('wp_title', 'aqualuxe_wp_title', 10, 2);
if (!function_exists('aqualuxe_wp_title')) {
    /**
     * Optimize title tags
     *
     * @since 1.0.0
     */
    function aqualuxe_wp_title($title, $sep) {
        if (is_feed()) {
            return $title;
        }
        
        global $page, $paged;
        
        // Add the blog name
        $title .= get_bloginfo('name', 'display');
        
        // Add the blog description for the home/front page
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && (is_home() || is_front_page())) {
            $title .= " $sep $site_description";
        }
        
        // Add a page number if necessary
        if (($paged >= 2 || $page >= 2) && !is_404()) {
            $title .= " $sep " . sprintf(__('Page %s', 'aqualuxe'), max($paged, $page));
        }
        
        return $title;
    }
}

// Add noindex to search results and 404 pages
add_action('wp_head', 'aqualuxe_noindex_pages');
if (!function_exists('aqualuxe_noindex_pages')) {
    /**
     * Add noindex to search results and 404 pages
     *
     * @since 1.0.0
     */
    function aqualuxe_noindex_pages() {
        if (is_search() || is_404()) {
            echo '<meta name="robots" content="noindex,nofollow">' . "\n";
        }
    }
}