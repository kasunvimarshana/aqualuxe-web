<?php
/**
 * AquaLuxe SEO Class
 * 
 * Handles SEO optimizations and structured data.
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_SEO {
    
    /**
     * Initialize SEO features
     */
    public static function init() {
        add_action('wp_head', [__CLASS__, 'add_meta_tags']);
        add_action('wp_head', [__CLASS__, 'add_structured_data']);
        add_action('wp_head', [__CLASS__, 'add_open_graph_tags']);
        add_action('wp_head', [__CLASS__, 'add_twitter_cards']);
        add_filter('document_title_parts', [__CLASS__, 'customize_title']);
        add_filter('the_content', [__CLASS__, 'optimize_content_seo']);
        
        // XML Sitemap
        add_action('init', [__CLASS__, 'create_xml_sitemap']);
        
        // Breadcrumbs
        add_action('aqualuxe_breadcrumbs', [__CLASS__, 'display_breadcrumbs']);
        
        // Image optimization for SEO
        add_filter('wp_get_attachment_image_attributes', [__CLASS__, 'optimize_image_seo'], 10, 3);
    }
    
    /**
     * Add meta tags
     */
    public static function add_meta_tags() {
        global $post;
        
        // Basic meta description
        $description = '';
        if (is_singular()) {
            $description = get_post_meta($post->ID, '_aqualuxe_meta_description', true);
            if (empty($description)) {
                $description = wp_trim_words(strip_tags($post->post_content), 30);
            }
        } elseif (is_home() || is_front_page()) {
            $description = get_theme_mod('aqualuxe_site_description', get_bloginfo('description'));
        } elseif (is_category()) {
            $description = category_description();
        } elseif (is_tag()) {
            $description = tag_description();
        }
        
        if ($description) {
            echo '<meta name="description" content="' . esc_attr(wp_trim_words($description, 30)) . '">' . "\n";
        }
        
        // Keywords
        if (is_singular()) {
            $keywords = get_post_meta($post->ID, '_aqualuxe_meta_keywords', true);
            if ($keywords) {
                echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
            }
        }
        
        // Robots meta
        $robots = 'index,follow';
        if (is_singular()) {
            $noindex = get_post_meta($post->ID, '_aqualuxe_noindex', true);
            if ($noindex) {
                $robots = 'noindex,nofollow';
            }
        }
        echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
        
        // Canonical URL
        $canonical = '';
        if (is_singular()) {
            $canonical = get_permalink($post->ID);
        } elseif (is_home()) {
            $canonical = home_url('/');
        } elseif (is_category()) {
            $canonical = get_category_link(get_queried_object_id());
        } elseif (is_tag()) {
            $canonical = get_tag_link(get_queried_object_id());
        }
        
        if ($canonical) {
            echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
        }
    }
    
    /**
     * Add structured data
     */
    public static function add_structured_data() {
        $schema = [];
        
        if (is_front_page()) {
            $schema = self::get_organization_schema();
        } elseif (is_singular('post')) {
            $schema = self::get_article_schema();
        } elseif (is_singular('product')) {
            $schema = self::get_product_schema();
        } elseif (is_page()) {
            $schema = self::get_webpage_schema();
        }
        
        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
        }
    }
    
    /**
     * Get organization schema
     */
    private static function get_organization_schema() {
        $logo_id = get_theme_mod('custom_logo');
        $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'description' => get_bloginfo('description'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo_url
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => get_theme_mod('aqualuxe_contact_phone', ''),
                'contactType' => 'customer service',
                'email' => get_theme_mod('aqualuxe_contact_email', '')
            ],
            'sameAs' => array_filter([
                get_theme_mod('aqualuxe_social_facebook', ''),
                get_theme_mod('aqualuxe_social_instagram', ''),
                get_theme_mod('aqualuxe_social_twitter', ''),
                get_theme_mod('aqualuxe_social_youtube', '')
            ])
        ];
    }
    
    /**
     * Get article schema
     */
    private static function get_article_schema() {
        global $post;
        
        $author = get_userdata($post->post_author);
        $featured_image = get_the_post_thumbnail_url($post->ID, 'full');
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => wp_trim_words(strip_tags($post->post_content), 30),
            'image' => $featured_image,
            'author' => [
                '@type' => 'Person',
                'name' => $author->display_name
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full')
                ]
            ],
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => get_permalink()
            ]
        ];
    }
    
    /**
     * Get product schema (WooCommerce)
     */
    private static function get_product_schema() {
        if (!function_exists('wc_get_product')) {
            return [];
        }
        
        global $post;
        $product = wc_get_product($post->ID);
        
        if (!$product) {
            return [];
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_trim_words(strip_tags($product->get_description()), 30),
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
            'sku' => $product->get_sku(),
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink()
            ]
        ];
        
        // Add brand if available
        $brand = get_post_meta($post->ID, '_aqualuxe_product_brand', true);
        if ($brand) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $brand
            ];
        }
        
        // Add reviews
        $reviews = get_comments([
            'post_id' => $post->ID,
            'meta_query' => [
                [
                    'key' => 'rating',
                    'compare' => 'EXISTS'
                ]
            ]
        ]);
        
        if ($reviews) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count()
            ];
        }
        
        return $schema;
    }
    
    /**
     * Get webpage schema
     */
    private static function get_webpage_schema() {
        global $post;
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_the_title(),
            'description' => wp_trim_words(strip_tags($post->post_content), 30),
            'url' => get_permalink(),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'url' => home_url()
            ]
        ];
    }
    
    /**
     * Add Open Graph tags
     */
    public static function add_open_graph_tags() {
        global $post;
        
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        
        if (is_singular()) {
            echo '<meta property="og:type" content="article">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr(wp_trim_words(strip_tags($post->post_content), 30)) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    echo '<meta property="og:image" content="' . esc_url($image[0]) . '">' . "\n";
                    echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '">' . "\n";
                    echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '">' . "\n";
                }
            }
        } elseif (is_home() || is_front_page()) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(home_url()) . '">' . "\n";
        }
    }
    
    /**
     * Add Twitter Card tags
     */
    public static function add_twitter_cards() {
        global $post;
        
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        
        $twitter_handle = get_theme_mod('aqualuxe_social_twitter', '');
        if ($twitter_handle) {
            $twitter_handle = str_replace('https://twitter.com/', '@', $twitter_handle);
            echo '<meta name="twitter:site" content="' . esc_attr($twitter_handle) . '">' . "\n";
        }
        
        if (is_singular()) {
            echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr(wp_trim_words(strip_tags($post->post_content), 30)) . '">' . "\n";
            
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '">' . "\n";
                }
            }
        }
    }
    
    /**
     * Customize document title
     */
    public static function customize_title($title) {
        if (is_home() && !is_front_page()) {
            $title['title'] = get_the_title(get_option('page_for_posts'));
        } elseif (is_singular('product')) {
            global $post;
            $product = wc_get_product($post->ID);
            if ($product && $product->get_sku()) {
                $title['title'] = $product->get_name() . ' - SKU: ' . $product->get_sku();
            }
        }
        
        return $title;
    }
    
    /**
     * Optimize content for SEO
     */
    public static function optimize_content_seo($content) {
        if (is_singular()) {
            // Add alt text to images without it
            $content = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
                $img_tag = $matches[0];
                
                // Check if alt attribute exists
                if (strpos($img_tag, 'alt=') === false) {
                    global $post;
                    $alt_text = get_the_title($post->ID);
                    $img_tag = str_replace('<img', '<img alt="' . esc_attr($alt_text) . '"', $img_tag);
                }
                
                return $img_tag;
            }, $content);
            
            // Add rel="noopener" to external links
            $content = preg_replace_callback('/<a([^>]+)href=["\']([^"\']+)["\']([^>]*)>/i', function($matches) {
                $url = $matches[2];
                $full_tag = $matches[0];
                
                // Check if it's an external link
                if (strpos($url, home_url()) === false && (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0)) {
                    // Add target="_blank" and rel="noopener" if not present
                    if (strpos($full_tag, 'target=') === false) {
                        $full_tag = str_replace('>', ' target="_blank">', $full_tag);
                    }
                    if (strpos($full_tag, 'rel=') === false) {
                        $full_tag = str_replace('>', ' rel="noopener">', $full_tag);
                    }
                }
                
                return $full_tag;
            }, $content);
        }
        
        return $content;
    }
    
    /**
     * Create XML sitemap
     */
    public static function create_xml_sitemap() {
        add_action('init', function() {
            add_rewrite_rule('^sitemap\.xml$', 'index.php?aqualuxe_sitemap=1', 'top');
        });
        
        add_filter('query_vars', function($vars) {
            $vars[] = 'aqualuxe_sitemap';
            return $vars;
        });
        
        add_action('template_redirect', function() {
            if (get_query_var('aqualuxe_sitemap')) {
                self::generate_sitemap();
                exit;
            }
        });
    }
    
    /**
     * Generate XML sitemap
     */
    private static function generate_sitemap() {
        header('Content-Type: application/xml; charset=utf-8');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        echo '<url>' . "\n";
        echo '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n";
        echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
        echo '<changefreq>daily</changefreq>' . "\n";
        echo '<priority>1.0</priority>' . "\n";
        echo '</url>' . "\n";
        
        // Pages
        $pages = get_pages();
        foreach ($pages as $page) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($page->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c', strtotime($page->post_modified)) . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.8</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        // Posts
        $posts = get_posts(['numberposts' => -1]);
        foreach ($posts as $post) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c', strtotime($post->post_modified)) . '</lastmod>' . "\n";
            echo '<changefreq>monthly</changefreq>' . "\n";
            echo '<priority>0.6</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        // Products (if WooCommerce is active)
        if (function_exists('wc_get_products')) {
            $products = wc_get_products(['limit' => -1]);
            foreach ($products as $product) {
                echo '<url>' . "\n";
                echo '<loc>' . esc_url(get_permalink($product->get_id())) . '</loc>' . "\n";
                echo '<lastmod>' . date('c', strtotime($product->get_date_modified())) . '</lastmod>' . "\n";
                echo '<changefreq>weekly</changefreq>' . "\n";
                echo '<priority>0.7</priority>' . "\n";
                echo '</url>' . "\n";
            }
        }
        
        echo '</urlset>';
    }
    
    /**
     * Display breadcrumbs
     */
    public static function display_breadcrumbs() {
        if (is_home() || is_front_page()) {
            return;
        }
        
        echo '<nav aria-label="breadcrumb" class="breadcrumbs">';
        echo '<ol class="breadcrumb-list">';
        
        // Home link
        echo '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
        
        if (is_category()) {
            $category = get_queried_object();
            echo '<li class="breadcrumb-item active">' . esc_html($category->name) . '</li>';
        } elseif (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></li>';
            }
            echo '<li class="breadcrumb-item active">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_page()) {
            $post = get_queried_object();
            if ($post->post_parent) {
                $parents = get_post_ancestors($post->ID);
                $parents = array_reverse($parents);
                foreach ($parents as $parent) {
                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($parent)) . '">' . esc_html(get_the_title($parent)) . '</a></li>';
                }
            }
            echo '<li class="breadcrumb-item active">' . esc_html(get_the_title()) . '</li>';
        }
        
        echo '</ol>';
        echo '</nav>';
    }
    
    /**
     * Optimize image SEO
     */
    public static function optimize_image_seo($attr, $attachment, $size) {
        // Add proper alt text if missing
        if (empty($attr['alt'])) {
            $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            if (empty($alt_text)) {
                $alt_text = $attachment->post_title;
            }
            $attr['alt'] = $alt_text;
        }
        
        return $attr;
    }
}
