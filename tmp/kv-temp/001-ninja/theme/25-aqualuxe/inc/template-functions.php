<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Add a class if dark mode is active
    if (aqualuxe_is_dark_mode_active()) {
        $classes[] = 'dark-mode';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Check if dark mode is active
 * 
 * @return bool
 */
function aqualuxe_is_dark_mode_active() {
    $default = get_theme_mod('aqualuxe_default_color_scheme', 'light');
    
    // Check for user preference cookie
    if (isset($_COOKIE['aqualuxe_color_scheme'])) {
        return sanitize_text_field($_COOKIE['aqualuxe_color_scheme']) === 'dark';
    }
    
    return $default === 'dark';
}

/**
 * Add schema.org structured data
 */
function aqualuxe_schema_org() {
    // Default website schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
    );
    
    if (is_singular('post')) {
        // Article schema for blog posts
        global $post;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author(),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_custom_logo_url(),
                ),
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink(),
            ),
        );
        
        // Add featured image if available
        if (has_post_thumbnail()) {
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image_data) {
                $schema['image'] = array(
                    '@type' => 'ImageObject',
                    'url' => $image_data[0],
                    'width' => $image_data[1],
                    'height' => $image_data[2],
                );
            }
        }
    } elseif (is_singular('product') && class_exists('WooCommerce')) {
        // Product schema for WooCommerce products
        global $product;
        
        if (!$product) {
            return;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
            'sku' => $product->get_sku(),
            'brand' => array(
                '@type' => 'Brand',
                'name' => get_bloginfo('name'),
            ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink(),
            ),
        );
        
        // Add product image
        if (has_post_thumbnail()) {
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image_data) {
                $schema['image'] = $image_data[0];
            }
        }
        
        // Add review data if available
        if ($product->get_review_count() > 0) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            );
        }
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}
add_action('wp_head', 'aqualuxe_schema_org');

/**
 * Get custom logo URL
 * 
 * @return string
 */
function get_custom_logo_url() {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_url = '';
    
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
    }
    
    return $logo_url ? $logo_url : '';
}

/**
 * Add Open Graph meta tags
 */
function aqualuxe_open_graph_tags() {
    global $post;
    
    if (is_singular()) {
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        
        if (has_post_thumbnail()) {
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image_data) {
                echo '<meta property="og:image" content="' . esc_url($image_data[0]) . '" />' . "\n";
            }
        }
        
        $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...');
        echo '<meta property="og:description" content="' . esc_attr($excerpt) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_open_graph_tags');

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_twitter_card_tags() {
    global $post;
    
    if (is_singular()) {
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        
        $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...');
        echo '<meta name="twitter:description" content="' . esc_attr($excerpt) . '" />' . "\n";
        
        if (has_post_thumbnail()) {
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image_data) {
                echo '<meta name="twitter:image" content="' . esc_url($image_data[0]) . '" />' . "\n";
            }
        }
        
        $twitter_username = get_theme_mod('aqualuxe_twitter_username', '');
        if ($twitter_username) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_twitter_card_tags');

/**
 * Sanitize and escape functions for security
 */

/**
 * Sanitize HTML content with allowed tags
 * 
 * @param string $content The content to sanitize
 * @return string Sanitized content
 */
function aqualuxe_sanitize_html($content) {
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
            'rel' => array(),
            'class' => array(),
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'p' => array(
            'class' => array(),
        ),
        'span' => array(
            'class' => array(),
        ),
        'div' => array(
            'class' => array(),
            'id' => array(),
        ),
        'h1' => array(
            'class' => array(),
        ),
        'h2' => array(
            'class' => array(),
        ),
        'h3' => array(
            'class' => array(),
        ),
        'h4' => array(
            'class' => array(),
        ),
        'h5' => array(
            'class' => array(),
        ),
        'h6' => array(
            'class' => array(),
        ),
        'img' => array(
            'src' => array(),
            'alt' => array(),
            'class' => array(),
            'height' => array(),
            'width' => array(),
            'loading' => array(),
        ),
        'ul' => array(
            'class' => array(),
        ),
        'ol' => array(
            'class' => array(),
        ),
        'li' => array(
            'class' => array(),
        ),
    );
    
    return wp_kses($content, $allowed_html);
}

/**
 * Sanitize checkbox
 * 
 * @param bool $checked Whether the checkbox is checked
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select
 * 
 * @param string $input The selected value
 * @param object $setting The setting object
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}