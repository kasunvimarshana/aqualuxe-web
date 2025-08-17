<?php
/**
 * AquaLuxe SEO Functions
 *
 * Implements SEO features for the AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add schema.org structured data
 */
function aqualuxe_add_schema_markup() {
    // Only add schema to public-facing pages
    if (is_admin() || is_feed()) {
        return;
    }
    
    // Basic WebSite schema for all pages
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => esc_attr(get_bloginfo('name')),
        'url' => esc_url(home_url('/')),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => esc_url(home_url('/?s={search_term_string}')),
            'query-input' => 'required name=search_term_string'
        )
    );
    
    // Add Organization schema to homepage
    if (is_front_page()) {
        $logo_id = get_theme_mod('custom_logo');
        $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => esc_attr(get_bloginfo('name')),
            'url' => esc_url(home_url('/')),
            'logo' => esc_url($logo_url),
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'telephone' => esc_attr(get_theme_mod('aqualuxe_contact_phone', '')),
                'contactType' => 'customer service'
            ),
            'sameAs' => array(
                esc_url(get_theme_mod('aqualuxe_social_facebook', '')),
                esc_url(get_theme_mod('aqualuxe_social_twitter', '')),
                esc_url(get_theme_mod('aqualuxe_social_instagram', '')),
                esc_url(get_theme_mod('aqualuxe_social_youtube', ''))
            )
        );
    }
    
    // Add Article schema to single posts
    if (is_singular('post')) {
        global $post;
        
        // Get featured image
        $image_id = get_post_thumbnail_id($post->ID);
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
        
        // Get author data
        $author_id = $post->post_author;
        $author_name = get_the_author_meta('display_name', $author_id);
        $author_url = get_author_posts_url($author_id);
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => esc_attr(get_the_title()),
            'image' => esc_url($image_url),
            'datePublished' => esc_attr(get_the_date('c')),
            'dateModified' => esc_attr(get_the_modified_date('c')),
            'author' => array(
                '@type' => 'Person',
                'name' => esc_attr($author_name),
                'url' => esc_url($author_url)
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => esc_attr(get_bloginfo('name')),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => esc_url(get_theme_mod('aqualuxe_publisher_logo', ''))
                )
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => esc_url(get_permalink())
            ),
            'description' => esc_attr(aqualuxe_get_excerpt($post->ID, 155))
        );
    }
    
    // Add Product schema for WooCommerce products
    if (function_exists('is_product') && is_product()) {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Get product image
        $image_id = $product->get_image_id();
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
        
        // Get product availability
        $availability = $product->get_availability();
        $stock_status = $availability['class'] ?? '';
        
        switch ($stock_status) {
            case 'in-stock':
                $availability_status = 'https://schema.org/InStock';
                break;
            case 'out-of-stock':
                $availability_status = 'https://schema.org/OutOfStock';
                break;
            case 'on-backorder':
                $availability_status = 'https://schema.org/BackOrder';
                break;
            default:
                $availability_status = 'https://schema.org/InStock';
        }
        
        // Get product rating
        $rating_count = $product->get_rating_count();
        $average_rating = $product->get_average_rating();
        $review_count = $product->get_review_count();
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => esc_attr($product->get_name()),
            'image' => esc_url($image_url),
            'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
            'sku' => esc_attr($product->get_sku()),
            'mpn' => esc_attr($product->get_meta('_aqualuxe_mpn', true)),
            'brand' => array(
                '@type' => 'Brand',
                'name' => esc_attr(aqualuxe_get_product_brand($product))
            ),
            'offers' => array(
                '@type' => 'Offer',
                'url' => esc_url(get_permalink()),
                'price' => esc_attr($product->get_price()),
                'priceCurrency' => esc_attr(get_woocommerce_currency()),
                'priceValidUntil' => esc_attr(date('Y-m-d', strtotime('+1 year'))),
                'availability' => $availability_status,
                'seller' => array(
                    '@type' => 'Organization',
                    'name' => esc_attr(get_bloginfo('name'))
                )
            )
        );
        
        // Add review data if available
        if ($rating_count > 0) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => esc_attr($average_rating),
                'reviewCount' => esc_attr($review_count)
            );
        }
    }
    
    // Allow filtering of schema data
    $schema = apply_filters('aqualuxe_schema_data', $schema);
    
    // Output schema as JSON-LD
    if (!empty($schema)) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_schema_markup', 10);

/**
 * Get a product brand (from taxonomy or attribute)
 */
function aqualuxe_get_product_brand($product) {
    // Try to get brand from product_brand taxonomy
    if (taxonomy_exists('product_brand')) {
        $terms = get_the_terms($product->get_id(), 'product_brand');
        if (!empty($terms) && !is_wp_error($terms)) {
            return $terms[0]->name;
        }
    }
    
    // Try to get brand from product attributes
    $attributes = $product->get_attributes();
    if (!empty($attributes) && isset($attributes['pa_brand'])) {
        $brand_terms = wc_get_product_terms($product->get_id(), 'pa_brand', array('fields' => 'names'));
        if (!empty($brand_terms)) {
            return $brand_terms[0];
        }
    }
    
    // Default to site name if no brand found
    return get_bloginfo('name');
}

/**
 * Get post excerpt with custom length
 */
function aqualuxe_get_excerpt($post_id, $length = 155) {
    $post = get_post($post_id);
    
    if (empty($post)) {
        return '';
    }
    
    if (has_excerpt($post_id)) {
        $excerpt = wp_strip_all_tags(get_the_excerpt($post_id));
    } else {
        $excerpt = wp_strip_all_tags($post->post_content);
    }
    
    $excerpt = wp_trim_words($excerpt, $length / 5, '...');
    
    return $excerpt;
}

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_opengraph_tags() {
    // Skip if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION') || class_exists('All_in_One_SEO_Pack')) {
        return;
    }
    
    // Basic Open Graph tags for all pages
    echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    
    if (is_singular()) {
        global $post;
        
        // Get featured image
        $image_id = get_post_thumbnail_id($post->ID);
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
        
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(aqualuxe_get_excerpt($post->ID, 155)) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        
        if ($image_url) {
            echo '<meta property="og:image" content="' . esc_url($image_url) . '" />' . "\n";
            
            // Get image dimensions
            $image_meta = wp_get_attachment_metadata($image_id);
            if (!empty($image_meta['width']) && !empty($image_meta['height'])) {
                echo '<meta property="og:image:width" content="' . esc_attr($image_meta['width']) . '" />' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr($image_meta['height']) . '" />' . "\n";
            }
        }
        
        // Article specific tags
        echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '" />' . "\n";
        echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '" />' . "\n";
        
        // Add tags as article:tag
        $tags = get_the_tags();
        if ($tags) {
            foreach ($tags as $tag) {
                echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '" />' . "\n";
            }
        }
        
        // Add categories as article:section
        $categories = get_the_category();
        if ($categories) {
            $primary_category = $categories[0];
            echo '<meta property="article:section" content="' . esc_attr($primary_category->name) . '" />' . "\n";
        }
    } elseif (is_front_page() || is_home()) {
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '" />' . "\n";
        
        // Use site logo as image
        $logo_id = get_theme_mod('custom_logo');
        $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
        
        if ($logo_url) {
            echo '<meta property="og:image" content="' . esc_url($logo_url) . '" />' . "\n";
        }
    } elseif (is_archive()) {
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_the_archive_title()) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(get_the_archive_description()) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_opengraph_tags', 5);

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_add_twitter_cards() {
    // Skip if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION') || class_exists('All_in_One_SEO_Pack')) {
        return;
    }
    
    // Default card type
    $card_type = 'summary_large_image';
    
    echo '<meta name="twitter:card" content="' . esc_attr($card_type) . '" />' . "\n";
    
    // Twitter username from theme options
    $twitter_username = get_theme_mod('aqualuxe_social_twitter_username', '');
    if ($twitter_username) {
        echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '" />' . "\n";
    }
    
    if (is_singular()) {
        global $post;
        
        // Get featured image
        $image_id = get_post_thumbnail_id($post->ID);
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
        
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr(aqualuxe_get_excerpt($post->ID, 155)) . '" />' . "\n";
        
        if ($image_url) {
            echo '<meta name="twitter:image" content="' . esc_url($image_url) . '" />' . "\n";
        }
        
        // Author Twitter handle
        $author_twitter = get_the_author_meta('twitter', $post->post_author);
        if ($author_twitter) {
            echo '<meta name="twitter:creator" content="@' . esc_attr($author_twitter) . '" />' . "\n";
        }
    } elseif (is_front_page() || is_home()) {
        echo '<meta name="twitter:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr(get_bloginfo('description')) . '" />' . "\n";
        
        // Use site logo as image
        $logo_id = get_theme_mod('custom_logo');
        $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
        
        if ($logo_url) {
            echo '<meta name="twitter:image" content="' . esc_url($logo_url) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_twitter_cards', 6);

/**
 * Add meta description if not provided by SEO plugins
 */
function aqualuxe_add_meta_description() {
    // Skip if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION') || class_exists('All_in_One_SEO_Pack')) {
        return;
    }
    
    $description = '';
    
    if (is_singular()) {
        global $post;
        $description = aqualuxe_get_excerpt($post->ID, 155);
    } elseif (is_front_page() || is_home()) {
        $description = get_bloginfo('description');
    } elseif (is_archive()) {
        $description = get_the_archive_description();
        $description = wp_strip_all_tags($description);
    }
    
    if ($description) {
        echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_meta_description', 1);

/**
 * Implement XML sitemap if not provided by plugins
 */
function aqualuxe_xml_sitemap() {
    // Skip if Yoast SEO, All in One SEO Pack, or similar plugins are active
    if (defined('WPSEO_VERSION') || class_exists('All_in_One_SEO_Pack') || class_exists('WPSEO_Sitemaps')) {
        return;
    }
    
    // Check if this is a sitemap request
    if (isset($_GET['aqualuxe_sitemap'])) {
        header('Content-Type: application/xml; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Add homepage
        echo '<url>' . "\n";
        echo '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n";
        echo '<lastmod>' . esc_attr(date('c', strtotime(get_lastpostmodified('GMT')))) . '</lastmod>' . "\n";
        echo '<changefreq>daily</changefreq>' . "\n";
        echo '<priority>1.0</priority>' . "\n";
        echo '</url>' . "\n";
        
        // Add posts
        $posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => -1,
        ));
        
        foreach ($posts as $post) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . esc_attr(date('c', strtotime($post->post_modified_gmt))) . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.8</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        // Add pages
        $pages = get_pages();
        
        foreach ($pages as $page) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($page->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . esc_attr(date('c', strtotime($page->post_modified_gmt))) . '</lastmod>' . "\n";
            echo '<changefreq>monthly</changefreq>' . "\n";
            echo '<priority>0.6</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        // Add custom post types
        $public_post_types = get_post_types(array('public' => true, '_builtin' => false));
        
        foreach ($public_post_types as $post_type) {
            $posts = get_posts(array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => -1,
            ));
            
            foreach ($posts as $post) {
                echo '<url>' . "\n";
                echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
                echo '<lastmod>' . esc_attr(date('c', strtotime($post->post_modified_gmt))) . '</lastmod>' . "\n";
                echo '<changefreq>weekly</changefreq>' . "\n";
                echo '<priority>0.7</priority>' . "\n";
                echo '</url>' . "\n";
            }
        }
        
        // Add category archives
        $categories = get_categories();
        
        foreach ($categories as $category) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_category_link($category->term_id)) . '</loc>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.5</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        // Add tag archives
        $tags = get_tags();
        
        if ($tags) {
            foreach ($tags as $tag) {
                echo '<url>' . "\n";
                echo '<loc>' . esc_url(get_tag_link($tag->term_id)) . '</loc>' . "\n";
                echo '<changefreq>weekly</changefreq>' . "\n";
                echo '<priority>0.4</priority>' . "\n";
                echo '</url>' . "\n";
            }
        }
        
        echo '</urlset>';
        exit;
    }
}
add_action('init', 'aqualuxe_xml_sitemap');

/**
 * Add sitemap URL to robots.txt
 */
function aqualuxe_robots_txt($content) {
    // Skip if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION') || class_exists('All_in_One_SEO_Pack')) {
        return $content;
    }
    
    $sitemap_url = home_url('/?aqualuxe_sitemap=1');
    $content .= "\nSitemap: " . $sitemap_url . "\n";
    
    return $content;
}
add_filter('robots_txt', 'aqualuxe_robots_txt', 20, 1);

/**
 * Implement breadcrumb navigation
 */
function aqualuxe_breadcrumbs() {
    // Skip if Yoast SEO breadcrumbs are active
    if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">', '</nav>');
        return;
    }
    
    // Skip if WooCommerce breadcrumbs are active and we're on a WooCommerce page
    if (function_exists('woocommerce_breadcrumb') && (is_woocommerce() || is_cart() || is_checkout())) {
        woocommerce_breadcrumb(array(
            'wrap_before' => '<nav class="breadcrumbs woocommerce-breadcrumb" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">',
            'wrap_after' => '</nav>',
        ));
        return;
    }
    
    // Custom breadcrumbs implementation
    $separator = '<span class="breadcrumb-separator">/</span>';
    $home_text = __('Home', 'aqualuxe');
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
    echo '<span class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html($home_text) . '</a></span>';
    
    if (is_category() || is_single()) {
        echo $separator;
        
        if (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                echo '<span class="breadcrumb-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></span>';
                echo $separator;
            }
            
            echo '<span class="breadcrumb-item current" aria-current="page">' . esc_html(get_the_title()) . '</span>';
        } else {
            echo '<span class="breadcrumb-item current" aria-current="page">' . esc_html(single_cat_title('', false)) . '</span>';
        }
    } elseif (is_page()) {
        global $post;
        
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                echo $separator;
                echo '<span class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a></span>';
            }
        }
        
        echo $separator;
        echo '<span class="breadcrumb-item current" aria-current="page">' . esc_html(get_the_title()) . '</span>';
    } elseif (is_tag()) {
        echo $separator;
        echo '<span class="breadcrumb-item current" aria-current="page">' . esc_html(single_tag_title('', false)) . '</span>';
    } elseif (is_author()) {
        echo $separator;
        echo '<span class="breadcrumb-item current" aria-current="page">' . esc_html(get_the_author()) . '</span>';
    } elseif (is_search()) {
        echo $separator;
        echo '<span class="breadcrumb-item current" aria-current="page">' . esc_html__('Search results for', 'aqualuxe') . ' "' . esc_html(get_search_query()) . '"</span>';
    } elseif (is_404()) {
        echo $separator;
        echo '<span class="breadcrumb-item current" aria-current="page">' . esc_html__('Page not found', 'aqualuxe') . '</span>';
    } elseif (is_archive()) {
        echo $separator;
        echo '<span class="breadcrumb-item current" aria-current="page">' . esc_html(get_the_archive_title()) . '</span>';
    }
    
    echo '</nav>';
}

/**
 * Add alt text enforcement for images
 */
function aqualuxe_check_image_alt($post_ID) {
    // Only run on attachment post types
    if (get_post_type($post_ID) !== 'attachment') {
        return;
    }
    
    // Check if this is an image
    if (!wp_attachment_is_image($post_ID)) {
        return;
    }
    
    // Get alt text
    $alt_text = get_post_meta($post_ID, '_wp_attachment_image_alt', true);
    
    // If no alt text, generate one from the filename
    if (empty($alt_text)) {
        $filename = basename(get_attached_file($post_ID));
        $filename = str_replace(array('-', '_'), ' ', $filename);
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $filename = ucwords($filename);
        
        update_post_meta($post_ID, '_wp_attachment_image_alt', $filename);
    }
}
add_action('add_attachment', 'aqualuxe_check_image_alt');
add_action('edit_attachment', 'aqualuxe_check_image_alt');

/**
 * Add canonical URL tag
 */
function aqualuxe_add_canonical_url() {
    // Skip if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION') || class_exists('All_in_One_SEO_Pack')) {
        return;
    }
    
    if (is_singular()) {
        $canonical_url = get_permalink();
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
    } elseif (is_home() || is_front_page()) {
        $canonical_url = home_url('/');
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
    } elseif (is_category() || is_tag() || is_tax()) {
        $canonical_url = get_term_link(get_queried_object());
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
    } elseif (is_post_type_archive()) {
        $canonical_url = get_post_type_archive_link(get_post_type());
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_canonical_url', 1);