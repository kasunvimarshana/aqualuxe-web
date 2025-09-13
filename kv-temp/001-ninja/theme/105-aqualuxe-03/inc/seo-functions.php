<?php
/**
 * SEO and Metadata Functions
 *
 * Enhanced SEO functionality with schema.org and OpenGraph support
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add enhanced meta tags and schema markup
 */
function aqualuxe_add_seo_meta_tags() {
    global $post;
    
    // Basic meta tags
    echo '<meta name="generator" content="AquaLuxe WordPress Theme v' . AQUALUXE_VERSION . '">' . "\n";
    
    // Viewport and mobile optimization
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">' . "\n";
    echo '<meta name="format-detection" content="telephone=no">' . "\n";
    
    // Theme color for mobile browsers
    echo '<meta name="theme-color" content="#0891b2">' . "\n";
    echo '<meta name="msapplication-TileColor" content="#0891b2">' . "\n";
    
    // OpenGraph meta tags
    aqualuxe_output_opengraph_meta();
    
    // Twitter Card meta tags
    aqualuxe_output_twitter_meta();
    
    // Schema.org JSON-LD
    aqualuxe_output_schema_markup();
}
add_action('wp_head', 'aqualuxe_add_seo_meta_tags', 1);

/**
 * Output OpenGraph meta tags
 */
function aqualuxe_output_opengraph_meta() {
    global $post;
    
    // Site name
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    
    // Type
    $og_type = is_single() || is_page() ? 'article' : 'website';
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
    
    // Title
    $og_title = '';
    if (is_single() || is_page()) {
        $og_title = get_the_title();
    } elseif (is_category()) {
        $og_title = single_cat_title('', false);
    } elseif (is_tag()) {
        $og_title = single_tag_title('', false);
    } elseif (is_archive()) {
        $og_title = get_the_archive_title();
    } else {
        $og_title = get_bloginfo('name');
    }
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
    
    // Description
    $og_description = '';
    if (is_single() || is_page()) {
        $og_description = get_the_excerpt() ?: wp_trim_words(get_the_content(), 20);
    } else {
        $og_description = get_bloginfo('description');
    }
    if ($og_description) {
        echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($og_description)) . '">' . "\n";
    }
    
    // URL
    $og_url = is_single() || is_page() ? get_permalink() : home_url($_SERVER['REQUEST_URI']);
    echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
    
    // Image
    $og_image = '';
    if (is_single() || is_page()) {
        if (has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        }
    }
    
    // Fallback to site logo or default image
    if (!$og_image) {
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $og_image = wp_get_attachment_image_url($custom_logo_id, 'large');
        } else {
            // Default branded image
            $og_image = AQUALUXE_ASSETS_URI . '/images/aqualuxe-og-image.jpg';
        }
    }
    
    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
        echo '<meta property="og:image:width" content="1200">' . "\n";
        echo '<meta property="og:image:height" content="630">' . "\n";
    }
    
    // Locale
    echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
}

/**
 * Output Twitter Card meta tags
 */
function aqualuxe_output_twitter_meta() {
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    
    // Twitter handle if set
    $twitter_handle = get_theme_mod('aqualuxe_twitter_handle', '');
    if ($twitter_handle) {
        echo '<meta name="twitter:site" content="@' . esc_attr(ltrim($twitter_handle, '@')) . '">' . "\n";
    }
}

/**
 * Output Schema.org JSON-LD markup
 */
function aqualuxe_output_schema_markup() {
    $schema_data = array();
    
    // Organization schema
    $schema_data[] = aqualuxe_get_organization_schema();
    
    // WebSite schema
    $schema_data[] = aqualuxe_get_website_schema();
    
    // Page-specific schema
    if (is_single() || is_page()) {
        if (is_single()) {
            $schema_data[] = aqualuxe_get_article_schema();
        } else {
            $schema_data[] = aqualuxe_get_webpage_schema();
        }
    }
    
    // Product schema for WooCommerce
    if (function_exists('is_product') && is_product()) {
        $schema_data[] = aqualuxe_get_product_schema();
    }
    
    // Output the schema
    if (!empty($schema_data)) {
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode(array(
            '@context' => 'https://schema.org',
            '@graph' => $schema_data
        ), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo "\n" . '</script>' . "\n";
    }
}

/**
 * Get Organization schema
 */
function aqualuxe_get_organization_schema() {
    $logo_url = '';
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
    }
    
    return array(
        '@type' => 'Organization',
        '@id' => home_url('/#organization'),
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => home_url('/'),
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => $logo_url,
        ),
        'sameAs' => aqualuxe_get_social_media_urls(),
    );
}

/**
 * Get WebSite schema
 */
function aqualuxe_get_website_schema() {
    return array(
        '@type' => 'WebSite',
        '@id' => home_url('/#website'),
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => home_url('/'),
        'publisher' => array(
            '@id' => home_url('/#organization')
        ),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}')
            ),
            'query-input' => 'required name=search_term_string'
        ),
    );
}

/**
 * Get Article schema for blog posts
 */
function aqualuxe_get_article_schema() {
    global $post;
    
    $schema = array(
        '@type' => 'Article',
        '@id' => get_permalink() . '#article',
        'headline' => get_the_title(),
        'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 20),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author(),
            'url' => get_author_posts_url(get_the_author_meta('ID')),
        ),
        'publisher' => array(
            '@id' => home_url('/#organization')
        ),
    );
    
    // Add featured image if available
    if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_data = wp_get_attachment_image_src($image_id, 'large');
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_data[0],
            'width' => $image_data[1],
            'height' => $image_data[2],
        );
    }
    
    return $schema;
}

/**
 * Get WebPage schema
 */
function aqualuxe_get_webpage_schema() {
    return array(
        '@type' => 'WebPage',
        '@id' => get_permalink() . '#webpage',
        'name' => get_the_title(),
        'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 20),
        'url' => get_permalink(),
        'isPartOf' => array(
            '@id' => home_url('/#website')
        ),
    );
}

/**
 * Get Product schema for WooCommerce products
 */
function aqualuxe_get_product_schema() {
    if (!function_exists('wc_get_product')) {
        return array();
    }
    
    global $product;
    
    if (!$product) {
        return array();
    }
    
    $schema = array(
        '@type' => 'Product',
        '@id' => get_permalink() . '#product',
        'name' => get_the_title(),
        'description' => $product->get_short_description() ?: wp_trim_words(get_the_content(), 20),
        'url' => get_permalink(),
        'sku' => $product->get_sku(),
        'brand' => array(
            '@type' => 'Brand',
            'name' => get_bloginfo('name'),
        ),
    );
    
    // Add product image
    $image_id = $product->get_image_id();
    if ($image_id) {
        $image_data = wp_get_attachment_image_src($image_id, 'large');
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_data[0],
            'width' => $image_data[1],
            'height' => $image_data[2],
        );
    }
    
    // Add offers
    if ($product->is_purchasable()) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => get_permalink(),
            'seller' => array(
                '@id' => home_url('/#organization')
            ),
        );
    }
    
    return $schema;
}

/**
 * Get social media URLs
 */
function aqualuxe_get_social_media_urls() {
    $social_urls = array();
    
    $social_platforms = array(
        'facebook' => get_theme_mod('aqualuxe_facebook_url', ''),
        'twitter' => get_theme_mod('aqualuxe_twitter_url', ''),
        'instagram' => get_theme_mod('aqualuxe_instagram_url', ''),
        'linkedin' => get_theme_mod('aqualuxe_linkedin_url', ''),
        'youtube' => get_theme_mod('aqualuxe_youtube_url', ''),
    );
    
    foreach ($social_platforms as $platform => $url) {
        if (!empty($url)) {
            $social_urls[] = esc_url($url);
        }
    }
    
    return $social_urls;
}

/**
 * Add preload hints for critical resources
 */
function aqualuxe_add_preload_hints() {
    // Preload critical fonts
    $critical_fonts = apply_filters('aqualuxe_critical_fonts', array(
        AQUALUXE_ASSETS_URI . '/fonts/inter-variable.woff2',
    ));
    
    foreach ($critical_fonts as $font_url) {
        echo '<link rel="preload" href="' . esc_url($font_url) . '" as="font" type="font/woff2" crossorigin>' . "\n";
    }
    
    // Preload critical CSS
    $critical_css_file = AQUALUXE_THEME_DIR . '/assets/dist/css/critical.css';
    if (file_exists($critical_css_file)) {
        echo '<link rel="preload" href="' . AQUALUXE_ASSETS_URI . '/css/critical.css" as="style">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_preload_hints', 0);

/**
 * Add DNS prefetch for external resources
 */
function aqualuxe_add_dns_prefetch() {
    $external_domains = apply_filters('aqualuxe_dns_prefetch_domains', array(
        '//fonts.googleapis.com',
        '//fonts.gstatic.com',
        '//www.google-analytics.com',
        '//www.googletagmanager.com',
    ));
    
    foreach ($external_domains as $domain) {
        echo '<link rel="dns-prefetch" href="' . esc_url($domain) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_dns_prefetch', 0);

/**
 * Generate sitemap (basic implementation)
 */
function aqualuxe_generate_sitemap() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $sitemap_entries = array();
    
    // Homepage
    $sitemap_entries[] = array(
        'loc' => home_url('/'),
        'lastmod' => get_lastpostmodified('gmt'),
        'changefreq' => 'daily',
        'priority' => '1.0',
    );
    
    // Posts and pages
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => array('post', 'page'),
        'post_status' => 'publish',
    ));
    
    foreach ($posts as $post) {
        $sitemap_entries[] = array(
            'loc' => get_permalink($post->ID),
            'lastmod' => $post->post_modified_gmt,
            'changefreq' => $post->post_type === 'page' ? 'monthly' : 'weekly',
            'priority' => $post->post_type === 'page' ? '0.8' : '0.6',
        );
    }
    
    // Generate XML
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($sitemap_entries as $entry) {
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . esc_url($entry['loc']) . '</loc>' . "\n";
        $xml .= '    <lastmod>' . date('Y-m-d\TH:i:s+00:00', strtotime($entry['lastmod'])) . '</lastmod>' . "\n";
        $xml .= '    <changefreq>' . esc_xml($entry['changefreq']) . '</changefreq>' . "\n";
        $xml .= '    <priority>' . esc_xml($entry['priority']) . '</priority>' . "\n";
        $xml .= '  </url>' . "\n";
    }
    
    $xml .= '</urlset>';
    
    // Save sitemap
    $upload_dir = wp_upload_dir();
    $sitemap_path = $upload_dir['basedir'] . '/sitemap.xml';
    file_put_contents($sitemap_path, $xml);
    
    return $upload_dir['baseurl'] . '/sitemap.xml';
}

/**
 * Add robots.txt rules
 */
function aqualuxe_robots_txt($output) {
    $upload_dir = wp_upload_dir();
    $sitemap_url = $upload_dir['baseurl'] . '/sitemap.xml';
    
    $output .= "\n# AquaLuxe Theme Rules\n";
    $output .= "Sitemap: " . $sitemap_url . "\n";
    $output .= "\n# Optimize crawling\n";
    $output .= "Disallow: /wp-admin/\n";
    $output .= "Disallow: /wp-includes/\n";
    $output .= "Disallow: /?s=\n";
    $output .= "Disallow: /search/\n";
    
    return $output;
}
add_filter('robots_txt', 'aqualuxe_robots_txt');