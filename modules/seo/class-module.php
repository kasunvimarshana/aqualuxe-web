<?php
/**
 * SEO Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\SEO;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * SEO Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'SEO';

    /**
     * Instance
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize module
     */
    public function init() {
        // Meta tags and basic SEO
        add_action('wp_head', array($this, 'add_meta_tags'), 1);
        add_action('wp_head', array($this, 'add_canonical_url'), 2);
        add_action('wp_head', array($this, 'add_robots_meta'), 3);
        add_filter('document_title_parts', array($this, 'customize_title'));
        add_filter('wp_title', array($this, 'enhance_title'), 10, 2);
        
        // Open Graph and social media
        add_action('wp_head', array($this, 'add_open_graph_tags'), 5);
        add_action('wp_head', array($this, 'add_twitter_cards'), 6);
        add_action('wp_head', array($this, 'add_facebook_meta'), 7);
        
        // Schema.org structured data
        add_action('wp_head', array($this, 'add_schema_markup'), 8);
        add_action('wp_footer', array($this, 'add_product_schema'));
        add_action('wp_footer', array($this, 'add_breadcrumb_schema'));
        add_action('wp_footer', array($this, 'add_organization_schema'));
        
        // XML sitemap
        add_action('init', array($this, 'handle_sitemap_request'));
        add_action('wp_loaded', array($this, 'generate_sitemap_files'));
        
        // Content optimization
        add_filter('the_content', array($this, 'optimize_content_seo'));
        add_filter('wp_head', array($this, 'add_preconnect_dns_prefetch'));
        add_action('wp_head', array($this, 'add_hreflang_tags'));
        
        // Performance for SEO
        add_action('wp_head', array($this, 'add_web_vitals_optimization'));
        add_filter('wp_headers', array($this, 'add_seo_headers'));
        
        // Analytics and tracking
        add_action('wp_footer', array($this, 'add_analytics_tracking'));
        add_action('wp_head', array($this, 'add_search_console_verification'));
        
        // Image optimization for SEO
        add_filter('wp_get_attachment_image_attributes', array($this, 'optimize_image_seo'), 10, 3);
        add_filter('get_avatar', array($this, 'add_avatar_structured_data'), 10, 5);
        
        // Rich snippets and FAQ schema
        add_action('wp_footer', array($this, 'add_faq_schema'));
        add_action('wp_footer', array($this, 'add_article_schema'));
        
        // Local SEO
        add_action('wp_footer', array($this, 'add_local_business_schema'));
        
        // Customizer integration
        add_action('customize_register', array($this, 'customize_register'));
        
        // Admin meta boxes
        add_action('add_meta_boxes', array($this, 'add_seo_meta_boxes'));
        add_action('save_post', array($this, 'save_seo_meta'));
    }

    /**
     * Add comprehensive meta tags
     */
    public function add_meta_tags() {
        global $post;
        
        // Viewport meta (critical for mobile SEO)
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">' . "\n";
        
        // Theme color for mobile browsers
        echo '<meta name="theme-color" content="' . esc_attr(get_theme_mod('aqualuxe_primary_color', '#0ea5e9')) . '">' . "\n";
        
        // Description meta tag
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
        
        // Keywords meta tag (still useful for some search engines)
        $keywords = $this->get_meta_keywords();
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        // Author meta tag
        if (is_single()) {
            $author = get_the_author_meta('display_name');
            echo '<meta name="author" content="' . esc_attr($author) . '">' . "\n";
        }
        
        // Publisher meta tag
        echo '<meta name="publisher" content="AquaLuxe">' . "\n";
        
        // Language meta tag
        echo '<meta name="language" content="' . esc_attr(get_bloginfo('language')) . '">' . "\n";
        
        // Content type
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
        
        // Revisit after for search engines
        echo '<meta name="revisit-after" content="7 days">' . "\n";
        
        // Distribution
        echo '<meta name="distribution" content="global">' . "\n";
        
        // Rating
        echo '<meta name="rating" content="general">' . "\n";
    }

    /**
     * Get optimized meta description
     */
    private function get_meta_description() {
        global $post;
        
        // Check for custom meta description
        if (is_singular() && $custom_desc = get_post_meta($post->ID, '_aqualuxe_meta_description', true)) {
            return wp_trim_words($custom_desc, 25, '...');
        }
        
        // Generate description based on content type
        if (is_home() || is_front_page()) {
            return get_theme_mod('aqualuxe_site_description', 'Bringing elegance to aquatic life – globally. Premium aquarium products, rare fish species, and expert services.');
        }
        
        if (is_single() || is_page()) {
            $excerpt = get_the_excerpt($post);
            if ($excerpt) {
                return wp_trim_words($excerpt, 25, '...');
            }
            
            $content = strip_tags(get_the_content());
            return wp_trim_words($content, 25, '...');
        }
        
        if (is_category()) {
            $description = category_description();
            if ($description) {
                return wp_trim_words(strip_tags($description), 25, '...');
            }
            return 'Browse our ' . single_cat_title('', false) . ' collection at AquaLuxe.';
        }
        
        if (is_tag()) {
            return 'Explore content tagged with ' . single_tag_title('', false) . ' at AquaLuxe.';
        }
        
        if (is_author()) {
            return 'Articles by ' . get_the_author_meta('display_name') . ' at AquaLuxe.';
        }
        
        return get_bloginfo('description');
    }

    /**
     * Get meta keywords
     */
    private function get_meta_keywords() {
        global $post;
        
        $keywords = array();
        
        // Custom keywords
        if (is_singular() && $custom_keywords = get_post_meta($post->ID, '_aqualuxe_meta_keywords', true)) {
            return $custom_keywords;
        }
        
        // Generate keywords from tags and categories
        if (is_single()) {
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    $keywords[] = $tag->name;
                }
            }
            
            $categories = get_the_category();
            if ($categories) {
                foreach ($categories as $category) {
                    $keywords[] = $category->name;
                }
            }
        }
        
        // Add brand keywords
        $keywords[] = 'AquaLuxe';
        $keywords[] = 'aquarium';
        $keywords[] = 'marine fish';
        $keywords[] = 'aquatic plants';
        
        return implode(', ', array_unique($keywords));
    }

    /**
     * Enhanced Open Graph tags
     */
    public function add_open_graph_tags() {
        global $post;
        
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        
        if (is_home() || is_front_page()) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '">' . "\n";
        } else if (is_single()) {
            echo '<meta property="og:type" content="article">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
            echo '<meta property="article:author" content="' . esc_attr(get_the_author()) . '">' . "\n";
            
            // Article section
            $categories = get_the_category();
            if ($categories) {
                echo '<meta property="article:section" content="' . esc_attr($categories[0]->name) . '">' . "\n";
            }
            
            // Article tags
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                }
            }
        } else if (is_page()) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        }
        
        // Description
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
        }
        
        // Image
        $image = $this->get_social_image();
        if ($image) {
            echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
            echo '<meta property="og:image:width" content="1200">' . "\n";
            echo '<meta property="og:image:height" content="630">' . "\n";
            echo '<meta property="og:image:type" content="image/jpeg">' . "\n";
        }
    }

    /**
     * Enhanced Twitter Card tags
     */
    public function add_twitter_cards() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:site" content="@AquaLuxe">' . "\n";
        
        if (is_single()) {
            echo '<meta name="twitter:creator" content="@' . esc_attr(get_the_author_meta('twitter')) . '">' . "\n";
        }
        
        echo '<meta name="twitter:title" content="' . esc_attr($this->get_page_title()) . '">' . "\n";
        
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
        }
        
        $image = $this->get_social_image();
        if ($image) {
            echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
            echo '<meta name="twitter:image:alt" content="' . esc_attr($this->get_page_title()) . '">' . "\n";
        }
    }

    /**
     * Comprehensive Schema.org markup
     */
    public function add_schema_markup() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'description' => get_bloginfo('description'),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => 'AquaLuxe',
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : get_template_directory_uri() . '/assets/dist/images/logo.png'
                )
            ),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string'
            )
        );
        
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    /**
     * Add product schema for WooCommerce products
     */
    public function add_product_schema() {
        if (!is_product() || !function_exists('wc_get_product')) {
            return;
        }
        
        global $product;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => get_the_title(),
            'description' => wp_strip_all_tags(get_the_excerpt()),
            'sku' => $product->get_sku(),
            'brand' => array(
                '@type' => 'Brand',
                'name' => 'AquaLuxe'
            ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => array(
                    '@type' => 'Organization',
                    'name' => 'AquaLuxe'
                )
            )
        );
        
        // Add image
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'full');
        }
        
        // Add reviews if available
        $reviews = get_comments(array('post_id' => get_the_ID(), 'type' => 'review'));
        if ($reviews) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count()
            );
        }
        
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    /**
     * Generate and handle XML sitemap
     */
    public function handle_sitemap_request() {
        if (isset($_GET['sitemap']) && $_GET['sitemap'] === 'xml') {
            $this->generate_xml_sitemap();
            exit;
        }
        
        if (isset($_GET['sitemap']) && $_GET['sitemap'] === 'news') {
            $this->generate_news_sitemap();
            exit;
        }
        
        if (isset($_GET['sitemap']) && $_GET['sitemap'] === 'images') {
            $this->generate_image_sitemap();
            exit;
        }
    }

    /**
     * Generate XML sitemap
     */
    private function generate_xml_sitemap() {
        header('Content-Type: application/xml; charset=utf-8');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";
        
        // Homepage
        echo '<url>' . "\n";
        echo '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n";
        echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
        echo '<changefreq>daily</changefreq>' . "\n";
        echo '<priority>1.0</priority>' . "\n";
        echo '</url>' . "\n";
        
        // Pages
        $pages = get_pages(array('post_status' => 'publish'));
        foreach ($pages as $page) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($page->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c', strtotime($page->post_modified)) . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.8</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        // Posts
        $posts = get_posts(array('numberposts' => -1, 'post_status' => 'publish'));
        foreach ($posts as $post) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c', strtotime($post->post_modified)) . '</lastmod>' . "\n";
            echo '<changefreq>monthly</changefreq>' . "\n";
            echo '<priority>0.6</priority>' . "\n";
            
            // Add images
            if (has_post_thumbnail($post->ID)) {
                $image_url = get_the_post_thumbnail_url($post->ID, 'full');
                echo '<image:image>' . "\n";
                echo '<image:loc>' . esc_url($image_url) . '</image:loc>' . "\n";
                echo '<image:caption>' . esc_html(get_the_title($post->ID)) . '</image:caption>' . "\n";
                echo '</image:image>' . "\n";
            }
            
            echo '</url>' . "\n";
        }
        
        // WooCommerce products
        if (function_exists('wc_get_products')) {
            $products = wc_get_products(array('limit' => -1, 'status' => 'publish'));
            foreach ($products as $product) {
                echo '<url>' . "\n";
                echo '<loc>' . esc_url(get_permalink($product->get_id())) . '</loc>' . "\n";
                echo '<lastmod>' . date('c', strtotime($product->get_date_modified())) . '</lastmod>' . "\n";
                echo '<changefreq>weekly</changefreq>' . "\n";
                echo '<priority>0.7</priority>' . "\n";
                echo '</url>' . "\n";
            }
        }
        
        echo '</urlset>' . "\n";
    }

    /**
     * Add hreflang tags for internationalization
     */
    public function add_hreflang_tags() {
        // Add hreflang tags if multilingual support is enabled
        $languages = apply_filters('aqualuxe_supported_languages', array(
            'en' => 'en-US',
            'es' => 'es-ES',
            'fr' => 'fr-FR',
            'de' => 'de-DE',
            'ja' => 'ja-JP'
        ));
        
        $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        foreach ($languages as $lang_code => $hreflang) {
            $lang_url = apply_filters('aqualuxe_language_url', $current_url, $lang_code);
            echo '<link rel="alternate" hreflang="' . esc_attr($hreflang) . '" href="' . esc_url($lang_url) . '">' . "\n";
        }
        
        // Add x-default
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($current_url) . '">' . "\n";
    }
        
        // Structured data
        add_action('wp_footer', array($this, 'organization_schema'));
        add_action('wp_footer', array($this, 'breadcrumb_schema'));
        
        // Performance for SEO
        add_filter('wp_calculate_image_srcset', array($this, 'optimize_images_for_seo'));
    }

    /**
     * Add meta tags
     */
    public function add_meta_tags() {
        // Meta description
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
        
        // Meta keywords (less important now but still useful)
        $keywords = $this->get_meta_keywords();
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        // Author
        if (is_single()) {
            $author = get_the_author();
            echo '<meta name="author" content="' . esc_attr($author) . '">' . "\n";
        }
        
        // Viewport meta tag
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
        
        // Theme color
        echo '<meta name="theme-color" content="' . esc_attr(get_theme_mod('aqualuxe_primary_color', '#06b6d4')) . '">' . "\n";
    }

    /**
     * Add structured data (Schema.org)
     */
    public function add_schema_markup() {
        if (is_front_page()) {
            $this->website_schema();
        } elseif (is_single()) {
            $this->article_schema();
        } elseif (is_page()) {
            $this->webpage_schema();
        } elseif (aqualuxe_is_woocommerce_activated() && is_product()) {
            $this->product_schema();
        }
    }

    /**
     * Website schema
     */
    private function website_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => get_bloginfo('name'),
            'url'      => home_url(),
            'description' => get_bloginfo('description'),
            'potentialAction' => array(
                '@type'       => 'SearchAction',
                'target'      => array(
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => home_url('/?s={search_term_string}'),
                ),
                'query-input' => 'required name=search_term_string',
            ),
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Article schema
     */
    private function article_schema() {
        global $post;
        
        $schema = array(
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'description'   => $this->get_meta_description(),
            'author'        => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
            ),
            'publisher'     => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo('name'),
                'logo'  => array(
                    '@type' => 'ImageObject',
                    'url'   => get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : '',
                ),
            ),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => get_permalink(),
            ),
        );
        
        // Add image if available
        if (has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            $image_meta = wp_get_attachment_metadata($image_id);
            
            $schema['image'] = array(
                '@type'  => 'ImageObject',
                'url'    => $image_url,
                'width'  => $image_meta['width'] ?? 1200,
                'height' => $image_meta['height'] ?? 630,
            );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * WebPage schema
     */
    private function webpage_schema() {
        $schema = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'WebPage',
            'name'        => get_the_title(),
            'description' => $this->get_meta_description(),
            'url'         => get_permalink(),
            'inLanguage'  => get_locale(),
            'isPartOf'    => array(
                '@type' => 'WebSite',
                'name'  => get_bloginfo('name'),
                'url'   => home_url(),
            ),
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Product schema (WooCommerce)
     */
    private function product_schema() {
        if (!function_exists('wc_get_product')) {
            return;
        }
        
        $product = wc_get_product();
        if (!$product) {
            return;
        }
        
        $schema = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $product->get_name(),
            'description' => $product->get_short_description() ?: $product->get_description(),
            'sku'         => $product->get_sku(),
            'image'       => wp_get_attachment_url($product->get_image_id()),
            'offers'      => array(
                '@type'         => 'Offer',
                'price'         => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url'           => get_permalink(),
            ),
        );
        
        // Add brand if available
        $brand = get_post_meta($product->get_id(), '_brand', true);
        if ($brand) {
            $schema['brand'] = array(
                '@type' => 'Brand',
                'name'  => $brand,
            );
        }
        
        // Add reviews if available
        $reviews = get_comments(array(
            'post_id' => $product->get_id(),
            'status'  => 'approve',
            'type'    => 'review',
            'number'  => 5,
        ));
        
        if ($reviews) {
            $schema['review'] = array();
            foreach ($reviews as $review) {
                $rating = get_comment_meta($review->comment_ID, 'rating', true);
                $schema['review'][] = array(
                    '@type'        => 'Review',
                    'author'       => array(
                        '@type' => 'Person',
                        'name'  => $review->comment_author,
                    ),
                    'reviewBody'   => $review->comment_content,
                    'datePublished' => $review->comment_date,
                    'reviewRating' => array(
                        '@type'       => 'Rating',
                        'ratingValue' => $rating ?: 5,
                        'bestRating'  => 5,
                    ),
                );
            }
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Add Open Graph tags
     */
    public function add_open_graph_tags() {
        echo '<meta property="og:title" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        echo '<meta property="og:type" content="' . esc_attr($this->get_og_type()) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($this->get_canonical_url()) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(str_replace('-', '_', get_locale())) . '">' . "\n";
        
        // OG Image
        $image = $this->get_og_image();
        if ($image) {
            echo '<meta property="og:image" content="' . esc_url($image['url']) . '">' . "\n";
            echo '<meta property="og:image:width" content="' . esc_attr($image['width']) . '">' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr($image['height']) . '">' . "\n";
            echo '<meta property="og:image:alt" content="' . esc_attr($image['alt']) . '">' . "\n";
        }
    }

    /**
     * Add Twitter Card tags
     */
    public function add_twitter_cards() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        
        $twitter_handle = get_theme_mod('aqualuxe_twitter_handle');
        if ($twitter_handle) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_handle) . '">' . "\n";
        }
        
        $image = $this->get_og_image();
        if ($image) {
            echo '<meta name="twitter:image" content="' . esc_url($image['url']) . '">' . "\n";
            echo '<meta name="twitter:image:alt" content="' . esc_attr($image['alt']) . '">' . "\n";
        }
    }

    /**
     * Add customizer controls
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function customize_register($wp_customize) {
        // SEO section
        $wp_customize->add_section('aqualuxe_seo', array(
            'title'    => __('SEO Settings', 'aqualuxe'),
            'priority' => 140,
        ));

        // Site description
        $wp_customize->add_setting('aqualuxe_meta_description', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_textarea_field',
        ));

        $wp_customize->add_control('aqualuxe_meta_description', array(
            'label'       => __('Default Meta Description', 'aqualuxe'),
            'description' => __('Used when no specific description is set for a page.', 'aqualuxe'),
            'section'     => 'aqualuxe_seo',
            'type'        => 'textarea',
        ));

        // Twitter handle
        $wp_customize->add_setting('aqualuxe_twitter_handle', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('aqualuxe_twitter_handle', array(
            'label'       => __('Twitter Handle', 'aqualuxe'),
            'description' => __('Without the @ symbol.', 'aqualuxe'),
            'section'     => 'aqualuxe_seo',
            'type'        => 'text',
        ));
    }

    /**
     * Get meta description
     *
     * @return string
     */
    private function get_meta_description() {
        if (is_singular()) {
            $meta = get_post_meta(get_the_ID(), '_aqualuxe_meta_description', true);
            if ($meta) {
                return $meta;
            }
            
            $excerpt = get_the_excerpt();
            if ($excerpt) {
                return wp_trim_words($excerpt, 25);
            }
        }
        
        $default = get_theme_mod('aqualuxe_meta_description', get_bloginfo('description'));
        return $default ?: get_bloginfo('description');
    }

    /**
     * Get meta keywords
     *
     * @return string
     */
    private function get_meta_keywords() {
        if (is_singular()) {
            return get_post_meta(get_the_ID(), '_aqualuxe_meta_keywords', true);
        }
        
        return '';
    }

    /**
     * Get Open Graph title
     *
     * @return string
     */
    private function get_og_title() {
        if (is_singular()) {
            return get_the_title();
        }
        
        return get_bloginfo('name');
    }

    /**
     * Get Open Graph type
     *
     * @return string
     */
    private function get_og_type() {
        if (is_front_page()) {
            return 'website';
        } elseif (is_singular()) {
            return 'article';
        }
        
        return 'website';
    }

    /**
     * Get Open Graph image
     *
     * @return array|null
     */
    private function get_og_image() {
        if (is_singular() && has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            $image_meta = wp_get_attachment_metadata($image_id);
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            
            return array(
                'url'    => $image_url,
                'width'  => $image_meta['width'] ?? 1200,
                'height' => $image_meta['height'] ?? 630,
                'alt'    => $image_alt ?: get_the_title(),
            );
        }
        
        // Default image
        $default_image = get_theme_mod('aqualuxe_default_og_image');
        if ($default_image) {
            return array(
                'url'    => $default_image,
                'width'  => 1200,
                'height' => 630,
                'alt'    => get_bloginfo('name'),
            );
        }
        
        return null;
    }

    /**
     * Get canonical URL
     *
     * @return string
     */
    private function get_canonical_url() {
        if (is_singular()) {
            return get_permalink();
        }
        
        return home_url(add_query_arg(array(), $_SERVER['REQUEST_URI']));
    }

    /**
     * Add canonical URL
     */
    public function canonical_url() {
        echo '<link rel="canonical" href="' . esc_url($this->get_canonical_url()) . '">' . "\n";
    }

    /**
     * Add robots meta tag
     */
    public function robots_meta() {
        $robots = array();
        
        if (is_search() || is_404()) {
            $robots[] = 'noindex';
        }
        
        if (is_attachment()) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        }
        
        if (!empty($robots)) {
            echo '<meta name="robots" content="' . esc_attr(implode(', ', $robots)) . '">' . "\n";
        }
    }

    /**
     * Generate XML sitemap
     */
    public function generate_sitemap() {
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
        echo '<url>' . "\n";
        echo '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n";
        echo '<changefreq>daily</changefreq>' . "\n";
        echo '<priority>1.0</priority>' . "\n";
        echo '</url>' . "\n";
        
        // Pages
        $pages = get_posts(array(
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ));
        
        foreach ($pages as $page) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($page->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . esc_html(get_the_modified_date('c', $page->ID)) . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.8</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        // Posts
        $posts = get_posts(array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ));
        
        foreach ($posts as $post) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . esc_html(get_the_modified_date('c', $post->ID)) . '</lastmod>' . "\n";
            echo '<changefreq>monthly</changefreq>' . "\n";
            echo '<priority>0.6</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>';
    }

    /**
     * Organization schema in footer
     */
    public function organization_schema() {
        if (!is_front_page()) {
            return;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => get_bloginfo('name'),
            'url'      => home_url(),
            'logo'     => array(
                '@type' => 'ImageObject',
                'url'   => get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : '',
            ),
            'sameAs'   => array_filter(array(
                get_theme_mod('aqualuxe_facebook_url'),
                get_theme_mod('aqualuxe_twitter_url'),
                get_theme_mod('aqualuxe_instagram_url'),
                get_theme_mod('aqualuxe_linkedin_url'),
            )),
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Breadcrumb schema
     */
    public function breadcrumb_schema() {
        if (is_front_page()) {
            return;
        }
        
        $breadcrumbs = $this->get_breadcrumb_data();
        if (empty($breadcrumbs)) {
            return;
        }
        
        $schema = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array(),
        );
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $schema['itemListElement'][] = array(
                '@type'    => 'ListItem',
                'position' => $index + 1,
                'name'     => $breadcrumb['name'],
                'item'     => $breadcrumb['url'],
            );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Get breadcrumb data
     *
     * @return array
     */
    private function get_breadcrumb_data() {
        $breadcrumbs = array();
        
        $breadcrumbs[] = array(
            'name' => __('Home', 'aqualuxe'),
            'url'  => home_url('/'),
        );
        
        if (is_category() || is_single()) {
            $category = get_the_category();
            if ($category) {
                $breadcrumbs[] = array(
                    'name' => $category[0]->name,
                    'url'  => get_category_link($category[0]->term_id),
                );
            }
            
            if (is_single()) {
                $breadcrumbs[] = array(
                    'name' => get_the_title(),
                    'url'  => get_permalink(),
                );
            }
        } elseif (is_page()) {
            $breadcrumbs[] = array(
                'name' => get_the_title(),
                'url'  => get_permalink(),
            );
        }
        
        return $breadcrumbs;
    }

    /**
     * Customize title
     *
     * @param array $title Title parts
     * @return array
     */
    public function customize_title($title) {
        if (is_front_page()) {
            $title['title'] = get_bloginfo('name');
            $title['tagline'] = get_bloginfo('description');
        }
        
        return $title;
    }

    /**
     * Optimize images for SEO
     *
     * @param array $sources Image sources
     * @return array
     */
    public function optimize_images_for_seo($sources) {
        // Ensure all images have proper alt attributes
        // This filter allows us to modify image sources for SEO
        return $sources;
    }
}