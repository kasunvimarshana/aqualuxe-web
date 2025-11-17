<?php
/**
 * SEO Optimization Class
 *
 * @package AquaLuxe\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEO optimization features
 */
class AquaLuxe_SEO {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_head', array($this, 'add_meta_tags'), 1);
        add_action('wp_head', array($this, 'add_schema_markup'), 5);
        add_action('wp_head', array($this, 'add_open_graph_tags'), 10);
        add_action('wp_head', array($this, 'add_twitter_cards'), 15);
        
        // Sitemap generation
        add_action('init', array($this, 'generate_sitemap'));
        add_action('publish_post', array($this, 'update_sitemap'));
        add_action('publish_page', array($this, 'update_sitemap'));
        
        // Optimize titles and descriptions
        add_filter('wp_title', array($this, 'optimize_title'), 10, 2);
        add_filter('document_title_parts', array($this, 'optimize_document_title'));
        
        // Add canonical URLs
        add_action('wp_head', array($this, 'add_canonical_url'), 2);
        
        // Optimize images for SEO
        add_filter('wp_get_attachment_image_attributes', array($this, 'optimize_image_attributes'), 10, 3);
    }
    
    /**
     * Add essential meta tags
     */
    public function add_meta_tags() {
        global $post;
        
        // Meta description
        $description = '';
        if (is_front_page()) {
            $description = get_bloginfo('description');
        } elseif (is_single() || is_page()) {
            if ($post && $post->post_excerpt) {
                $description = $post->post_excerpt;
            } else {
                $description = wp_trim_words(strip_tags($post->post_content), 25, '...');
            }
        } elseif (is_category()) {
            $description = category_description();
        } elseif (is_tag()) {
            $description = tag_description();
        } elseif (is_author()) {
            $author = get_queried_object();
            $description = get_the_author_meta('description', $author->ID);
        }
        
        if ($description) {
            echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($description)) . '">' . "\n";
        }
        
        // Meta keywords (if enabled)
        if (aqualuxe_get_option('enable_meta_keywords', false)) {
            $keywords = $this->get_meta_keywords();
            if ($keywords) {
                echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
            }
        }
        
        // Robots meta
        $robots = $this->get_robots_meta();
        if ($robots) {
            echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
        }
        
        // Language and locale
        echo '<meta name="language" content="' . esc_attr(get_locale()) . '">' . "\n";
        
        // Author meta
        if (is_single()) {
            $author_name = get_the_author_meta('display_name', $post->post_author);
            echo '<meta name="author" content="' . esc_attr($author_name) . '">' . "\n";
        }
    }
    
    /**
     * Add schema.org structured data
     */
    public function add_schema_markup() {
        global $post;
        
        $schema_data = array();
        
        if (is_front_page()) {
            $schema_data = $this->get_organization_schema();
        } elseif (is_single()) {
            if (get_post_type() === 'post') {
                $schema_data = $this->get_article_schema($post);
            } elseif (get_post_type() === 'aqualuxe_service') {
                $schema_data = $this->get_service_schema($post);
            } elseif (get_post_type() === 'aqualuxe_event') {
                $schema_data = $this->get_event_schema($post);
            }
        } elseif (is_page()) {
            $schema_data = $this->get_webpage_schema($post);
        } elseif (aqualuxe_is_woocommerce_active() && is_product()) {
            $schema_data = $this->get_product_schema($post);
        } elseif (aqualuxe_is_woocommerce_active() && is_shop()) {
            $schema_data = $this->get_store_schema();
        }
        
        if (!empty($schema_data)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema_data, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
        }
    }
    
    /**
     * Add Open Graph tags
     */
    public function add_open_graph_tags() {
        global $post;
        
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        
        if (is_front_page()) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(home_url()) . '">' . "\n";
        } elseif (is_single() || is_page()) {
            $og_type = is_single() ? 'article' : 'website';
            echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            
            $description = $post->post_excerpt ?: wp_trim_words(strip_tags($post->post_content), 25, '...');
            echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            
            // Featured image
            if (has_post_thumbnail()) {
                $image_url = get_the_post_thumbnail_url($post->ID, 'aqualuxe-featured');
                $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'aqualuxe-featured');
                
                echo '<meta property="og:image" content="' . esc_url($image_url) . '">' . "\n";
                if ($image_data) {
                    echo '<meta property="og:image:width" content="' . esc_attr($image_data[1]) . '">' . "\n";
                    echo '<meta property="og:image:height" content="' . esc_attr($image_data[2]) . '">' . "\n";
                }
            }
            
            // Article specific tags
            if (is_single() && get_post_type() === 'post') {
                echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
                echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
                echo '<meta property="article:author" content="' . esc_attr(get_the_author()) . '">' . "\n";
                
                $categories = get_the_category();
                if ($categories) {
                    echo '<meta property="article:section" content="' . esc_attr($categories[0]->name) . '">' . "\n";
                }
                
                $tags = get_the_tags();
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                    }
                }
            }
        }
    }
    
    /**
     * Add Twitter Card tags
     */
    public function add_twitter_cards() {
        global $post;
        
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        
        $twitter_handle = get_theme_mod('social_twitter_handle');
        if ($twitter_handle) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_handle) . '">' . "\n";
        }
        
        if (is_front_page()) {
            echo '<meta name="twitter:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
        } elseif (is_single() || is_page()) {
            echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            
            $description = $post->post_excerpt ?: wp_trim_words(strip_tags($post->post_content), 25, '...');
            echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
            
            if (has_post_thumbnail()) {
                $image_url = get_the_post_thumbnail_url($post->ID, 'aqualuxe-featured');
                echo '<meta name="twitter:image" content="' . esc_url($image_url) . '">' . "\n";
            }
        }
    }
    
    /**
     * Get organization schema
     */
    private function get_organization_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'description' => get_bloginfo('description'),
            'foundingDate' => get_theme_mod('company_founding_date', '2020-01-01'),
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'telephone' => get_theme_mod('contact_phone'),
                'contactType' => 'customer service',
                'availableLanguage' => array('English')
            )
        );
        
        // Add logo
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_url = wp_get_attachment_image_url($logo_id, 'full');
            $schema['logo'] = $logo_url;
        }
        
        // Add social media profiles
        $social_profiles = array();
        $social_networks = array('facebook', 'twitter', 'instagram', 'linkedin', 'youtube');
        
        foreach ($social_networks as $network) {
            $url = get_theme_mod('social_' . $network . '_url');
            if ($url) {
                $social_profiles[] = $url;
            }
        }
        
        if (!empty($social_profiles)) {
            $schema['sameAs'] = $social_profiles;
        }
        
        return $schema;
    }
    
    /**
     * Get article schema
     */
    private function get_article_schema($post) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title($post->ID),
            'description' => $post->post_excerpt ?: wp_trim_words(strip_tags($post->post_content), 25, '...'),
            'datePublished' => get_the_date('c', $post->ID),
            'dateModified' => get_the_modified_date('c', $post->ID),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author_meta('display_name', $post->post_author)
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url()
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink($post->ID)
            )
        );
        
        // Add featured image
        if (has_post_thumbnail($post->ID)) {
            $image_url = get_the_post_thumbnail_url($post->ID, 'aqualuxe-featured');
            $schema['image'] = $image_url;
        }
        
        // Add category
        $categories = get_the_category($post->ID);
        if ($categories) {
            $schema['articleSection'] = $categories[0]->name;
        }
        
        return $schema;
    }
    
    /**
     * Get product schema for WooCommerce
     */
    private function get_product_schema($post) {
        if (!function_exists('wc_get_product')) {
            return array();
        }
        
        $product = wc_get_product($post->ID);
        if (!$product) {
            return array();
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags($product->get_description()),
            'sku' => $product->get_sku(),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink($post->ID)
            )
        );
        
        // Add product image
        $image_id = $product->get_image_id();
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            $schema['image'] = $image_url;
        }
        
        // Add brand if available
        $brand = get_post_meta($post->ID, '_product_brand', true);
        if ($brand) {
            $schema['brand'] = array(
                '@type' => 'Brand',
                'name' => $brand
            );
        }
        
        // Add reviews aggregate if available
        $review_count = $product->get_review_count();
        $average_rating = $product->get_average_rating();
        
        if ($review_count > 0 && $average_rating > 0) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $average_rating,
                'reviewCount' => $review_count,
                'bestRating' => 5,
                'worstRating' => 1
            );
        }
        
        return $schema;
    }
    
    /**
     * Get meta keywords
     */
    private function get_meta_keywords() {
        global $post;
        
        $keywords = array();
        
        if (is_single() || is_page()) {
            // Get tags
            $tags = get_the_tags($post->ID);
            if ($tags) {
                foreach ($tags as $tag) {
                    $keywords[] = $tag->name;
                }
            }
            
            // Get categories
            $categories = get_the_category($post->ID);
            if ($categories) {
                foreach ($categories as $category) {
                    $keywords[] = $category->name;
                }
            }
            
            // Get custom keywords meta
            $custom_keywords = get_post_meta($post->ID, '_aqualuxe_seo_keywords', true);
            if ($custom_keywords) {
                $keywords = array_merge($keywords, explode(',', $custom_keywords));
            }
        }
        
        $keywords = array_map('trim', $keywords);
        $keywords = array_filter($keywords);
        $keywords = array_unique($keywords);
        
        return implode(', ', $keywords);
    }
    
    /**
     * Get robots meta content
     */
    private function get_robots_meta() {
        $robots = array();
        
        if (is_home() || is_front_page()) {
            $robots[] = 'index';
            $robots[] = 'follow';
        } elseif (is_single() || is_page()) {
            $robots[] = 'index';
            $robots[] = 'follow';
        } elseif (is_category() || is_tag() || is_archive()) {
            if (aqualuxe_get_option('noindex_archives', false)) {
                $robots[] = 'noindex';
            } else {
                $robots[] = 'index';
            }
            $robots[] = 'follow';
        } elseif (is_search()) {
            $robots[] = 'noindex';
            $robots[] = 'follow';
        } else {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        }
        
        return implode(', ', $robots);
    }
    
    /**
     * Add canonical URL
     */
    public function add_canonical_url() {
        $canonical_url = '';
        
        if (is_front_page()) {
            $canonical_url = home_url();
        } elseif (is_single() || is_page()) {
            $canonical_url = get_permalink();
        } elseif (is_category()) {
            $canonical_url = get_category_link(get_queried_object_id());
        } elseif (is_tag()) {
            $canonical_url = get_tag_link(get_queried_object_id());
        } elseif (is_author()) {
            $canonical_url = get_author_posts_url(get_queried_object_id());
        }
        
        if ($canonical_url) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
        }
    }
    
    /**
     * Generate XML sitemap
     */
    public function generate_sitemap() {
        if (get_option('aqualuxe_generate_sitemap', true)) {
            add_action('wp_loaded', array($this, 'handle_sitemap_request'));
        }
    }
    
    /**
     * Handle sitemap requests
     */
    public function handle_sitemap_request() {
        if (isset($_GET['sitemap']) && $_GET['sitemap'] === 'xml') {
            $this->output_sitemap();
            exit;
        }
    }
    
    /**
     * Output XML sitemap
     */
    private function output_sitemap() {
        header('Content-Type: application/xml; charset=utf-8');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        echo '<url>';
        echo '<loc>' . esc_url(home_url()) . '</loc>';
        echo '<changefreq>daily</changefreq>';
        echo '<priority>1.0</priority>';
        echo '</url>' . "\n";
        
        // Pages
        $pages = get_pages();
        foreach ($pages as $page) {
            echo '<url>';
            echo '<loc>' . esc_url(get_permalink($page->ID)) . '</loc>';
            echo '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', $page->post_modified_gmt) . '</lastmod>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>' . "\n";
        }
        
        // Posts
        $posts = get_posts(array('numberposts' => -1));
        foreach ($posts as $post) {
            echo '<url>';
            echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>';
            echo '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', $post->post_modified_gmt) . '</lastmod>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.6</priority>';
            echo '</url>' . "\n";
        }
        
        echo '</urlset>';
    }
    
    /**
     * Update sitemap when content is published
     */
    public function update_sitemap($post_id) {
        // Clear any sitemap cache here if you implement caching
        delete_transient('aqualuxe_sitemap_cache');
    }
    
    /**
     * Optimize document title
     */
    public function optimize_document_title($title) {
        if (is_front_page()) {
            $title['title'] = get_bloginfo('name');
            $title['tagline'] = get_bloginfo('description');
        }
        
        return $title;
    }
    
    /**
     * Optimize image attributes for SEO
     */
    public function optimize_image_attributes($attr, $attachment, $size) {
        // Add missing alt text
        if (empty($attr['alt'])) {
            $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            if (!$alt_text) {
                $alt_text = $attachment->post_title;
            }
            $attr['alt'] = $alt_text;
        }
        
        return $attr;
    }
}

// Initialize SEO features
new AquaLuxe_SEO();