<?php
/**
 * AquaLuxe SEO Enhancements
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_open_graph_tags() {
    if (!is_singular()) {
        return;
    }
    
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
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
    
    if (!empty($image)) {
        echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
    }
    
    // For products
    if (is_product()) {
        global $product;
        if ($product) {
            $price = $product->get_price();
            $currency = get_woocommerce_currency();
            
            echo '<meta property="og:type" content="product" />' . "\n";
            echo '<meta property="product:price:amount" content="' . esc_attr($price) . '" />' . "\n";
            echo '<meta property="product:price:currency" content="' . esc_attr($currency) . '" />' . "\n";
        }
    } else {
        echo '<meta property="og:type" content="website" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_open_graph_tags', 5);

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_add_twitter_cards() {
    if (!is_singular()) {
        return;
    }
    
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
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
    
    if (!empty($image)) {
        echo '<meta name="twitter:image" content="' . esc_url($image) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_twitter_cards', 5);

/**
 * Add schema markup for products
 */
function aqualuxe_add_product_schema() {
    if (!is_product()) {
        return;
    }
    
    global $product;
    if (!$product) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => wp_strip_all_tags(do_shortcode($product->get_description())),
        'sku' => $product->get_sku(),
        'offers' => array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => get_permalink(),
        ),
    );
    
    // Add image if available
    if (has_post_thumbnail()) {
        $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        $schema['image'] = $image_data[0];
    }
    
    // Add brand if available
    $brand = get_theme_mod('aqualuxe_brand_name', '');
    if (!empty($brand)) {
        $schema['brand'] = array(
            '@type' => 'Brand',
            'name' => $brand,
        );
    }
    
    // Output schema markup
    echo '<script type="application/ld+json">' . json_encode($schema) . '</script>' . "\n";
}
add_action('wp_head', 'aqualuxe_add_product_schema', 40);

/**
 * Add schema markup for organization
 */
function aqualuxe_add_organization_schema() {
    $schema = array(
        '@context' => 'https://schema.org/',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'logo' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
    );
    
    // Output schema markup
    echo '<script type="application/ld+json">' . json_encode($schema) . '</script>' . "\n";
}
add_action('wp_head', 'aqualuxe_add_organization_schema', 40);

/**
 * Add schema markup for breadcrumbs
 */
function aqualuxe_add_breadcrumb_schema() {
    if (!is_product() && !is_shop() && !is_product_category()) {
        return;
    }
    
    $items = array();
    $items[] = array(
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Home',
        'item' => home_url(),
    );
    
    if (is_shop()) {
        $items[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Shop',
        );
    } elseif (is_product_category()) {
        $items[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Shop',
            'item' => get_permalink(wc_get_page_id('shop')),
        );
        
        $term = get_queried_object();
        $items[] = array(
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $term->name,
        );
    } elseif (is_product()) {
        $items[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Shop',
            'item' => get_permalink(wc_get_page_id('shop')),
        );
        
        global $product;
        if ($product) {
            $categories = get_the_terms($product->get_id(), 'product_cat');
            if ($categories && !is_wp_error($categories)) {
                $category = reset($categories);
                $items[] = array(
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $category->name,
                    'item' => get_term_link($category),
                );
            }
            
            $items[] = array(
                '@type' => 'ListItem',
                'position' => 4,
                'name' => $product->get_name(),
            );
        }
    }
    
    $schema = array(
        '@context' => 'https://schema.org/',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    );
    
    // Output schema markup
    echo '<script type="application/ld+json">' . json_encode($schema) . '</script>' . "\n";
}
add_action('wp_head', 'aqualuxe_add_breadcrumb_schema', 40);

/**
 * Optimize title tags
 */
function aqualuxe_optimize_title($title) {
    if (is_home() || is_front_page()) {
        return get_bloginfo('name') . ' | ' . get_bloginfo('description');
    }
    
    return $title;
}
add_filter('wp_title', 'aqualuxe_optimize_title', 10, 2);

/**
 * Add meta description
 */
function aqualuxe_add_meta_description() {
    $description = '';
    
    if (is_home() || is_front_page()) {
        $description = get_bloginfo('description');
    } elseif (is_singular()) {
        $description = wp_trim_words(strip_tags(get_the_excerpt()), 30);
    } elseif (is_category() || is_tag() || is_tax()) {
        $description = get_term_field('description', get_queried_object());
    }
    
    if (!empty($description)) {
        echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_meta_description', 1);