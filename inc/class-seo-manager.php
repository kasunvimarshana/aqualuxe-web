<?php
/**
 * SEO Optimization System
 * Schema.org, OpenGraph, Meta Tags, and SEO Analytics
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

defined('ABSPATH') || exit;

/**
 * SEO Manager Class
 */
class SEO_Manager {

    /**
     * Instance
     *
     * @var SEO_Manager
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return SEO_Manager
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize SEO features
     */
    public function init() {
        add_action('wp_head', array($this, 'add_meta_tags'), 1);
        add_action('wp_head', array($this, 'add_structured_data'), 5);
        add_action('wp_head', array($this, 'add_social_meta_tags'), 10);
        add_action('wp_head', array($this, 'add_preconnect_links'), 2);
        add_filter('document_title_parts', array($this, 'optimize_title'));
        add_filter('wp_title', array($this, 'optimize_wp_title'), 10, 2);
        add_action('init', array($this, 'add_sitemap_endpoints'));
        add_action('template_redirect', array($this, 'handle_sitemap_requests'));
        
        // Admin SEO tools
        if (is_admin()) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('add_meta_boxes', array($this, 'add_seo_meta_boxes'));
            add_action('save_post', array($this, 'save_seo_meta_data'));
            add_action('wp_ajax_aqualuxe_seo_analysis', array($this, 'ajax_seo_analysis'));
            add_action('wp_ajax_aqualuxe_generate_sitemap', array($this, 'ajax_generate_sitemap'));
        }
    }

    /**
     * Add essential meta tags
     */
    public function add_meta_tags() {
        // Viewport meta tag for mobile responsiveness
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">' . "\n";
        
        // Charset meta tag
        echo '<meta charset="' . get_bloginfo('charset') . '">' . "\n";
        
        // Description meta tag
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
        
        // Keywords meta tag (less important but still useful)
        $keywords = $this->get_meta_keywords();
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        // Robots meta tag
        $robots = $this->get_robots_meta();
        echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
        
        // Canonical URL
        $canonical = $this->get_canonical_url();
        if ($canonical) {
            echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
        }
        
        // Author meta tag
        if (is_single() || is_page()) {
            $author = get_the_author_meta('display_name');
            if ($author) {
                echo '<meta name="author" content="' . esc_attr($author) . '">' . "\n";
            }
        }
        
        // Language meta tag
        $language = get_bloginfo('language');
        echo '<meta name="language" content="' . esc_attr($language) . '">' . "\n";
        
        // Theme and site information
        echo '<meta name="generator" content="AquaLuxe Theme ' . AQUALUXE_VERSION . '">' . "\n";
    }

    /**
     * Add structured data (Schema.org)
     */
    public function add_structured_data() {
        $structured_data = array();
        
        // Website schema
        $structured_data[] = $this->get_website_schema();
        
        // Organization schema
        $structured_data[] = $this->get_organization_schema();
        
        // Page-specific schemas
        if (is_single() || is_page()) {
            $structured_data[] = $this->get_article_schema();
        }
        
        if (is_product() || get_post_type() === 'aqualuxe_product') {
            $structured_data[] = $this->get_product_schema();
        }
        
        if (get_post_type() === 'aqualuxe_service') {
            $structured_data[] = $this->get_service_schema();
        }
        
        if (is_author()) {
            $structured_data[] = $this->get_person_schema();
        }
        
        // Breadcrumb schema
        if (!is_front_page()) {
            $structured_data[] = $this->get_breadcrumb_schema();
        }
        
        // Local business schema (if applicable)
        if (is_front_page() || is_page('contact')) {
            $business_schema = $this->get_local_business_schema();
            if ($business_schema) {
                $structured_data[] = $business_schema;
            }
        }
        
        // Filter out empty schemas and output
        $structured_data = array_filter($structured_data);
        
        if (!empty($structured_data)) {
            echo '<script type="application/ld+json">' . "\n";
            echo wp_json_encode($structured_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            echo "\n" . '</script>' . "\n";
        }
    }

    /**
     * Add social media meta tags (OpenGraph, Twitter Cards)
     */
    public function add_social_meta_tags() {
        // OpenGraph meta tags
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:type" content="' . esc_attr($this->get_og_type()) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($this->get_canonical_url()) . '">' . "\n";
        
        $og_image = $this->get_og_image();
        if ($og_image) {
            echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
            echo '<meta property="og:image:width" content="1200">' . "\n";
            echo '<meta property="og:image:height" content="630">' . "\n";
        }
        
        // Twitter Card meta tags
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        
        if ($og_image) {
            echo '<meta name="twitter:image" content="' . esc_url($og_image) . '">' . "\n";
        }
        
        $twitter_handle = get_theme_mod('aqualuxe_twitter_handle');
        if ($twitter_handle) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_handle) . '">' . "\n";
            echo '<meta name="twitter:creator" content="@' . esc_attr($twitter_handle) . '">' . "\n";
        }
        
        // Additional Facebook meta tags
        $facebook_app_id = get_theme_mod('aqualuxe_facebook_app_id');
        if ($facebook_app_id) {
            echo '<meta property="fb:app_id" content="' . esc_attr($facebook_app_id) . '">' . "\n";
        }
    }

    /**
     * Add preconnect links for performance
     */
    public function add_preconnect_links() {
        // Preconnect to common external domains
        $preconnects = array(
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
            'https://www.google-analytics.com',
            'https://www.googletagmanager.com',
        );
        
        $preconnects = apply_filters('aqualuxe_seo_preconnects', $preconnects);
        
        foreach ($preconnects as $url) {
            echo '<link rel="preconnect" href="' . esc_url($url) . '" crossorigin>' . "\n";
        }
        
        // DNS prefetch for additional performance
        $dns_prefetch = array(
            'https://www.youtube.com',
            'https://player.vimeo.com',
        );
        
        $dns_prefetch = apply_filters('aqualuxe_seo_dns_prefetch', $dns_prefetch);
        
        foreach ($dns_prefetch as $url) {
            echo '<link rel="dns-prefetch" href="' . esc_url($url) . '">' . "\n";
        }
    }

    /**
     * Get meta description
     */
    private function get_meta_description() {
        if (is_singular()) {
            $custom_description = get_post_meta(get_the_ID(), '_aqualuxe_meta_description', true);
            if ($custom_description) {
                return $custom_description;
            }
            
            if (has_excerpt()) {
                return wp_strip_all_tags(get_the_excerpt());
            }
            
            return wp_trim_words(wp_strip_all_tags(get_the_content()), 25);
        }
        
        if (is_category() || is_tag() || is_tax()) {
            $term_description = term_description();
            if ($term_description) {
                return wp_strip_all_tags($term_description);
            }
        }
        
        if (is_author()) {
            return sprintf(__('Posts by %s', 'aqualuxe'), get_the_author_meta('display_name'));
        }
        
        if (is_home() || is_front_page()) {
            $site_description = get_bloginfo('description');
            if ($site_description) {
                return $site_description;
            }
        }
        
        return get_bloginfo('description');
    }

    /**
     * Get meta keywords
     */
    private function get_meta_keywords() {
        if (is_singular()) {
            $custom_keywords = get_post_meta(get_the_ID(), '_aqualuxe_meta_keywords', true);
            if ($custom_keywords) {
                return $custom_keywords;
            }
            
            // Auto-generate from tags and categories
            $tags = get_the_tags();
            $categories = get_the_category();
            $keywords = array();
            
            if ($tags) {
                foreach ($tags as $tag) {
                    $keywords[] = $tag->name;
                }
            }
            
            if ($categories) {
                foreach ($categories as $category) {
                    $keywords[] = $category->name;
                }
            }
            
            return implode(', ', $keywords);
        }
        
        return '';
    }

    /**
     * Get robots meta
     */
    private function get_robots_meta() {
        $robots = array();
        
        if (is_singular()) {
            $noindex = get_post_meta(get_the_ID(), '_aqualuxe_noindex', true);
            $nofollow = get_post_meta(get_the_ID(), '_aqualuxe_nofollow', true);
            
            if ($noindex) {
                $robots[] = 'noindex';
            } else {
                $robots[] = 'index';
            }
            
            if ($nofollow) {
                $robots[] = 'nofollow';
            } else {
                $robots[] = 'follow';
            }
        } else {
            $robots[] = 'index';
            $robots[] = 'follow';
        }
        
        // Additional robots directives
        if (!is_attachment()) {
            $robots[] = 'max-snippet:-1';
            $robots[] = 'max-image-preview:large';
            $robots[] = 'max-video-preview:-1';
        }
        
        return implode(', ', $robots);
    }

    /**
     * Get canonical URL
     */
    private function get_canonical_url() {
        if (is_singular()) {
            $custom_canonical = get_post_meta(get_the_ID(), '_aqualuxe_canonical_url', true);
            if ($custom_canonical) {
                return $custom_canonical;
            }
            return get_permalink();
        }
        
        if (is_category() || is_tag() || is_tax()) {
            return get_term_link(get_queried_object());
        }
        
        if (is_author()) {
            return get_author_posts_url(get_query_var('author'), get_query_var('author_name'));
        }
        
        if (is_home()) {
            return home_url('/');
        }
        
        return null;
    }

    /**
     * Get OpenGraph type
     */
    private function get_og_type() {
        if (is_front_page()) {
            return 'website';
        }
        
        if (is_singular()) {
            return 'article';
        }
        
        return 'website';
    }

    /**
     * Get OpenGraph title
     */
    private function get_og_title() {
        if (is_singular()) {
            $custom_title = get_post_meta(get_the_ID(), '_aqualuxe_og_title', true);
            if ($custom_title) {
                return $custom_title;
            }
            return get_the_title();
        }
        
        return wp_get_document_title();
    }

    /**
     * Get OpenGraph image
     */
    private function get_og_image() {
        if (is_singular()) {
            $custom_image = get_post_meta(get_the_ID(), '_aqualuxe_og_image', true);
            if ($custom_image) {
                return $custom_image;
            }
            
            if (has_post_thumbnail()) {
                return get_the_post_thumbnail_url(null, 'large');
            }
        }
        
        // Fallback to site logo or default image
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            return wp_get_attachment_image_url($custom_logo_id, 'large');
        }
        
        // Default OG image from theme
        $default_og_image = get_theme_mod('aqualuxe_default_og_image');
        if ($default_og_image) {
            return $default_og_image;
        }
        
        return null;
    }

    /**
     * Get website schema
     */
    private function get_website_schema() {
        return array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url(),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => array(
                    '@type' => 'EntryPoint',
                    'urlTemplate' => home_url('/?s={search_term_string}')
                ),
                'query-input' => 'required name=search_term_string'
            )
        );
    }

    /**
     * Get organization schema
     */
    private function get_organization_schema() {
        $logo_id = get_theme_mod('custom_logo');
        $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : null;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'description' => get_bloginfo('description'),
        );
        
        if ($logo_url) {
            $schema['logo'] = $logo_url;
        }
        
        // Add social media profiles
        $social_profiles = array();
        $facebook_url = get_theme_mod('aqualuxe_facebook_url');
        $twitter_url = get_theme_mod('aqualuxe_twitter_url');
        $instagram_url = get_theme_mod('aqualuxe_instagram_url');
        $linkedin_url = get_theme_mod('aqualuxe_linkedin_url');
        
        if ($facebook_url) $social_profiles[] = $facebook_url;
        if ($twitter_url) $social_profiles[] = $twitter_url;
        if ($instagram_url) $social_profiles[] = $instagram_url;
        if ($linkedin_url) $social_profiles[] = $linkedin_url;
        
        if (!empty($social_profiles)) {
            $schema['sameAs'] = $social_profiles;
        }
        
        return $schema;
    }

    /**
     * Get article schema
     */
    private function get_article_schema() {
        if (!is_singular()) {
            return null;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => $this->get_meta_description(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author_meta('display_name'),
                'url' => get_author_posts_url(get_the_author_meta('ID'))
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url()
            )
        );
        
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'large');
        }
        
        return $schema;
    }

    /**
     * Get product schema
     */
    private function get_product_schema() {
        if (!is_singular()) {
            return null;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => get_the_title(),
            'description' => $this->get_meta_description(),
            'url' => get_permalink(),
        );
        
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'large');
        }
        
        // Add price information if available
        $price = get_post_meta(get_the_ID(), '_aqualuxe_product_price', true);
        $currency = get_post_meta(get_the_ID(), '_aqualuxe_product_currency', true) ?: 'USD';
        
        if ($price) {
            $schema['offers'] = array(
                '@type' => 'Offer',
                'price' => $price,
                'priceCurrency' => $currency,
                'availability' => 'https://schema.org/InStock'
            );
        }
        
        return $schema;
    }

    /**
     * Get service schema
     */
    private function get_service_schema() {
        if (!is_singular() || get_post_type() !== 'aqualuxe_service') {
            return null;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => get_the_title(),
            'description' => $this->get_meta_description(),
            'url' => get_permalink(),
            'provider' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url()
            )
        );
        
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'large');
        }
        
        return $schema;
    }

    /**
     * Get person schema
     */
    private function get_person_schema() {
        if (!is_author()) {
            return null;
        }
        
        $author_id = get_query_var('author');
        
        return array(
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name', $author_id),
            'description' => get_the_author_meta('description', $author_id),
            'url' => get_author_posts_url($author_id),
            'sameAs' => array_filter(array(
                get_the_author_meta('user_url', $author_id),
                get_the_author_meta('twitter', $author_id),
                get_the_author_meta('facebook', $author_id),
            ))
        );
    }

    /**
     * Get breadcrumb schema
     */
    private function get_breadcrumb_schema() {
        $breadcrumbs = array();
        $position = 1;
        
        // Home
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => get_bloginfo('name'),
            'item' => home_url()
        );
        
        if (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $term->name,
                'item' => get_term_link($term)
            );
        } elseif (is_singular()) {
            $post_type = get_post_type();
            $post_type_object = get_post_type_object($post_type);
            
            if ($post_type !== 'post' && $post_type !== 'page' && $post_type_object->public) {
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $post_type_object->labels->name,
                    'item' => get_post_type_archive_link($post_type)
                );
            }
            
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
        }
        
        if (count($breadcrumbs) <= 1) {
            return null;
        }
        
        return array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        );
    }

    /**
     * Get local business schema
     */
    private function get_local_business_schema() {
        $business_name = get_theme_mod('aqualuxe_business_name');
        $business_address = get_theme_mod('aqualuxe_business_address');
        $business_phone = get_theme_mod('aqualuxe_business_phone');
        
        if (!$business_name && !$business_address && !$business_phone) {
            return null;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $business_name ?: get_bloginfo('name'),
            'url' => home_url(),
        );
        
        if ($business_address) {
            $schema['address'] = array(
                '@type' => 'PostalAddress',
                'streetAddress' => $business_address
            );
        }
        
        if ($business_phone) {
            $schema['telephone'] = $business_phone;
        }
        
        return $schema;
    }

    /**
     * Optimize document title
     */
    public function optimize_title($title_parts) {
        // Customize title format
        if (is_front_page()) {
            // For homepage, use: Site Name | Tagline
            $title_parts = array(
                'title' => get_bloginfo('name'),
                'tagline' => get_bloginfo('description'),
            );
        }
        
        return $title_parts;
    }

    /**
     * Optimize wp_title for older WordPress versions
     */
    public function optimize_wp_title($title, $sep) {
        if (is_feed()) {
            return $title;
        }
        
        // Add site name to title
        $title .= get_bloginfo('name', 'display');
        
        // Add description for homepage
        if (is_front_page()) {
            $site_description = get_bloginfo('description', 'display');
            if ($site_description) {
                $title .= " $sep $site_description";
            }
        }
        
        return $title;
    }

    /**
     * Add sitemap endpoints
     */
    public function add_sitemap_endpoints() {
        add_rewrite_rule('^sitemap\.xml$', 'index.php?aqualuxe_sitemap=main', 'top');
        add_rewrite_rule('^sitemap-posts\.xml$', 'index.php?aqualuxe_sitemap=posts', 'top');
        add_rewrite_rule('^sitemap-pages\.xml$', 'index.php?aqualuxe_sitemap=pages', 'top');
        add_rewrite_rule('^sitemap-categories\.xml$', 'index.php?aqualuxe_sitemap=categories', 'top');
        
        // Add query vars
        add_filter('query_vars', function($vars) {
            $vars[] = 'aqualuxe_sitemap';
            return $vars;
        });
    }

    /**
     * Handle sitemap requests
     */
    public function handle_sitemap_requests() {
        $sitemap_type = get_query_var('aqualuxe_sitemap');
        
        if (!$sitemap_type) {
            return;
        }
        
        header('Content-Type: application/xml; charset=utf-8');
        
        switch ($sitemap_type) {
            case 'main':
                $this->generate_main_sitemap();
                break;
            case 'posts':
                $this->generate_posts_sitemap();
                break;
            case 'pages':
                $this->generate_pages_sitemap();
                break;
            case 'categories':
                $this->generate_categories_sitemap();
                break;
        }
        
        exit;
    }

    /**
     * Generate main sitemap
     */
    private function generate_main_sitemap() {
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        $sitemaps = array(
            'sitemap-posts.xml',
            'sitemap-pages.xml',
            'sitemap-categories.xml',
        );
        
        foreach ($sitemaps as $sitemap) {
            echo '<sitemap>' . "\n";
            echo '<loc>' . home_url($sitemap) . '</loc>' . "\n";
            echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
            echo '</sitemap>' . "\n";
        }
        
        echo '</sitemapindex>' . "\n";
    }

    /**
     * Generate posts sitemap
     */
    private function generate_posts_sitemap() {
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        $posts = get_posts(array(
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type' => array('post', 'aqualuxe_service', 'aqualuxe_product'),
        ));
        
        foreach ($posts as $post) {
            echo '<url>' . "\n";
            echo '<loc>' . get_permalink($post) . '</loc>' . "\n";
            echo '<lastmod>' . date('c', strtotime($post->post_modified)) . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.8</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>' . "\n";
    }

    /**
     * Generate pages sitemap
     */
    private function generate_pages_sitemap() {
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        $pages = get_pages(array(
            'post_status' => 'publish',
        ));
        
        foreach ($pages as $page) {
            echo '<url>' . "\n";
            echo '<loc>' . get_permalink($page) . '</loc>' . "\n";
            echo '<lastmod>' . date('c', strtotime($page->post_modified)) . '</lastmod>' . "\n";
            echo '<changefreq>monthly</changefreq>' . "\n";
            echo '<priority>0.6</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>' . "\n";
    }

    /**
     * Generate categories sitemap
     */
    private function generate_categories_sitemap() {
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        $categories = get_categories(array(
            'hide_empty' => true,
        ));
        
        foreach ($categories as $category) {
            echo '<url>' . "\n";
            echo '<loc>' . get_category_link($category) . '</loc>' . "\n";
            echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.5</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>' . "\n";
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__('SEO Tools', 'aqualuxe'),
            esc_html__('SEO Tools', 'aqualuxe'),
            'manage_options',
            'aqualuxe-seo-tools',
            array($this, 'admin_page')
        );
    }

    /**
     * Admin page for SEO tools
     */
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-seo-tools">
            <h1><?php esc_html_e('AquaLuxe SEO Tools', 'aqualuxe'); ?></h1>
            
            <div class="seo-section">
                <h3><?php esc_html_e('SEO Analysis', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Analyze your site\'s SEO performance and get recommendations for improvement.', 'aqualuxe'); ?></p>
                
                <button type="button" id="run-seo-analysis" class="button button-primary">
                    <?php esc_html_e('Run SEO Analysis', 'aqualuxe'); ?>
                </button>
                
                <div id="seo-analysis-results" class="seo-results"></div>
            </div>

            <div class="seo-section">
                <h3><?php esc_html_e('Sitemap Management', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Generate and manage XML sitemaps for search engines.', 'aqualuxe'); ?></p>
                
                <p>
                    <strong><?php esc_html_e('Main Sitemap:', 'aqualuxe'); ?></strong>
                    <a href="<?php echo home_url('sitemap.xml'); ?>" target="_blank">
                        <?php echo home_url('sitemap.xml'); ?>
                    </a>
                </p>
                
                <button type="button" id="regenerate-sitemap" class="button button-secondary">
                    <?php esc_html_e('Regenerate Sitemap', 'aqualuxe'); ?>
                </button>
            </div>

            <div class="seo-section">
                <h3><?php esc_html_e('Structured Data', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Test and validate your structured data implementation.', 'aqualuxe'); ?></p>
                
                <div class="structured-data-info">
                    <p><strong><?php esc_html_e('Active Schema Types:', 'aqualuxe'); ?></strong></p>
                    <ul>
                        <li>✓ Website</li>
                        <li>✓ Organization</li>
                        <li>✓ Article (for posts)</li>
                        <li>✓ Product (for products)</li>
                        <li>✓ Service (for services)</li>
                        <li>✓ BreadcrumbList</li>
                    </ul>
                </div>
            </div>
        </div>

        <style>
        .aqualuxe-seo-tools .seo-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .seo-results {
            margin-top: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #0073aa;
            min-height: 50px;
        }
        .structured-data-info ul {
            list-style: none;
            padding: 0;
        }
        .structured-data-info li {
            padding: 5px 0;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('#run-seo-analysis').on('click', function() {
                var button = $(this);
                button.prop('disabled', true).text('<?php esc_js(_e('Running Analysis...', 'aqualuxe')); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_seo_analysis',
                        nonce: '<?php echo wp_create_nonce('aqualuxe_seo_tools'); ?>'
                    },
                    success: function(response) {
                        $('#seo-analysis-results').html(response.data);
                        button.prop('disabled', false).text('<?php esc_js(_e('Run SEO Analysis', 'aqualuxe')); ?>');
                    },
                    error: function() {
                        $('#seo-analysis-results').html('<p style="color: red;"><?php esc_js(_e('Analysis failed. Please try again.', 'aqualuxe')); ?></p>');
                        button.prop('disabled', false).text('<?php esc_js(_e('Run SEO Analysis', 'aqualuxe')); ?>');
                    }
                });
            });

            $('#regenerate-sitemap').on('click', function() {
                var button = $(this);
                button.prop('disabled', true).text('<?php esc_js(_e('Regenerating...', 'aqualuxe')); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_generate_sitemap',
                        nonce: '<?php echo wp_create_nonce('aqualuxe_seo_tools'); ?>'
                    },
                    success: function(response) {
                        alert('<?php esc_js(_e('Sitemap regenerated successfully!', 'aqualuxe')); ?>');
                        button.prop('disabled', false).text('<?php esc_js(_e('Regenerate Sitemap', 'aqualuxe')); ?>');
                    },
                    error: function() {
                        alert('<?php esc_js(_e('Sitemap regeneration failed. Please try again.', 'aqualuxe')); ?>');
                        button.prop('disabled', false).text('<?php esc_js(_e('Regenerate Sitemap', 'aqualuxe')); ?>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Add SEO meta boxes
     */
    public function add_seo_meta_boxes() {
        $post_types = array('post', 'page', 'aqualuxe_service', 'aqualuxe_product');
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'aqualuxe_seo_meta',
                __('SEO Settings', 'aqualuxe'),
                array($this, 'seo_meta_box_callback'),
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * SEO meta box callback
     */
    public function seo_meta_box_callback($post) {
        wp_nonce_field('aqualuxe_seo_meta', 'aqualuxe_seo_meta_nonce');
        
        $meta_description = get_post_meta($post->ID, '_aqualuxe_meta_description', true);
        $meta_keywords = get_post_meta($post->ID, '_aqualuxe_meta_keywords', true);
        $og_title = get_post_meta($post->ID, '_aqualuxe_og_title', true);
        $og_image = get_post_meta($post->ID, '_aqualuxe_og_image', true);
        $canonical_url = get_post_meta($post->ID, '_aqualuxe_canonical_url', true);
        $noindex = get_post_meta($post->ID, '_aqualuxe_noindex', true);
        $nofollow = get_post_meta($post->ID, '_aqualuxe_nofollow', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="aqualuxe_meta_description"><?php esc_html_e('Meta Description', 'aqualuxe'); ?></label></th>
                <td>
                    <textarea id="aqualuxe_meta_description" name="aqualuxe_meta_description" rows="3" class="large-text"><?php echo esc_textarea($meta_description); ?></textarea>
                    <p class="description"><?php esc_html_e('Brief description for search engines (150-160 characters recommended)', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_meta_keywords"><?php esc_html_e('Meta Keywords', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="text" id="aqualuxe_meta_keywords" name="aqualuxe_meta_keywords" value="<?php echo esc_attr($meta_keywords); ?>" class="large-text">
                    <p class="description"><?php esc_html_e('Comma-separated keywords (optional)', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_og_title"><?php esc_html_e('Social Media Title', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="text" id="aqualuxe_og_title" name="aqualuxe_og_title" value="<?php echo esc_attr($og_title); ?>" class="large-text">
                    <p class="description"><?php esc_html_e('Title for social media sharing (leave blank to use post title)', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_og_image"><?php esc_html_e('Social Media Image', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="url" id="aqualuxe_og_image" name="aqualuxe_og_image" value="<?php echo esc_url($og_image); ?>" class="large-text">
                    <p class="description"><?php esc_html_e('Image URL for social media sharing (leave blank to use featured image)', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_canonical_url"><?php esc_html_e('Canonical URL', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="url" id="aqualuxe_canonical_url" name="aqualuxe_canonical_url" value="<?php echo esc_url($canonical_url); ?>" class="large-text">
                    <p class="description"><?php esc_html_e('Canonical URL (leave blank to use default)', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e('Search Engine Visibility', 'aqualuxe'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="aqualuxe_noindex" value="1" <?php checked($noindex, 1); ?>>
                        <?php esc_html_e('Discourage search engines from indexing this page', 'aqualuxe'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="aqualuxe_nofollow" value="1" <?php checked($nofollow, 1); ?>>
                        <?php esc_html_e('Discourage search engines from following links on this page', 'aqualuxe'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save SEO meta data
     */
    public function save_seo_meta_data($post_id) {
        if (!isset($_POST['aqualuxe_seo_meta_nonce']) || !wp_verify_nonce($_POST['aqualuxe_seo_meta_nonce'], 'aqualuxe_seo_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $fields = array(
            'aqualuxe_meta_description',
            'aqualuxe_meta_keywords',
            'aqualuxe_og_title',
            'aqualuxe_og_image',
            'aqualuxe_canonical_url',
            'aqualuxe_noindex',
            'aqualuxe_nofollow',
        );
        
        foreach ($fields as $field) {
            $value = isset($_POST[$field]) ? $_POST[$field] : '';
            
            if ($field === 'aqualuxe_meta_description') {
                $value = sanitize_textarea_field($value);
            } elseif ($field === 'aqualuxe_og_image' || $field === 'aqualuxe_canonical_url') {
                $value = esc_url_raw($value);
            } elseif ($field === 'aqualuxe_noindex' || $field === 'aqualuxe_nofollow') {
                $value = $value ? 1 : 0;
            } else {
                $value = sanitize_text_field($value);
            }
            
            update_post_meta($post_id, '_' . $field, $value);
        }
    }

    /**
     * AJAX SEO analysis
     */
    public function ajax_seo_analysis() {
        check_ajax_referer('aqualuxe_seo_tools', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'aqualuxe'));
        }

        $analysis_results = $this->run_seo_analysis();
        wp_send_json_success($analysis_results);
    }

    /**
     * AJAX generate sitemap
     */
    public function ajax_generate_sitemap() {
        check_ajax_referer('aqualuxe_seo_tools', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'aqualuxe'));
        }

        // Flush rewrite rules to ensure sitemap endpoints work
        flush_rewrite_rules();
        
        wp_send_json_success('Sitemap regenerated successfully!');
    }

    /**
     * Run SEO analysis
     */
    private function run_seo_analysis() {
        $issues = array();
        $recommendations = array();
        
        // Check for missing meta descriptions
        $posts_without_meta = $this->get_posts_without_meta_description();
        if (!empty($posts_without_meta)) {
            $issues[] = sprintf(__('%d posts/pages are missing meta descriptions', 'aqualuxe'), count($posts_without_meta));
        }
        
        // Check for duplicate titles
        $duplicate_titles = $this->find_duplicate_titles();
        if (!empty($duplicate_titles)) {
            $issues[] = sprintf(__('%d duplicate titles found', 'aqualuxe'), count($duplicate_titles));
        }
        
        // Check for missing alt text on images
        $images_without_alt = $this->get_images_without_alt();
        if (!empty($images_without_alt)) {
            $issues[] = sprintf(__('%d images are missing alt text', 'aqualuxe'), count($images_without_alt));
        }
        
        // Recommendations
        $recommendations[] = __('Use descriptive, keyword-rich titles and meta descriptions', 'aqualuxe');
        $recommendations[] = __('Optimize images with proper alt text and file names', 'aqualuxe');
        $recommendations[] = __('Create internal links between related content', 'aqualuxe');
        $recommendations[] = __('Submit your sitemap to Google Search Console', 'aqualuxe');
        
        $html = '<div class="seo-analysis-results">';
        
        if (!empty($issues)) {
            $html .= '<h4>' . __('Issues Found:', 'aqualuxe') . '</h4>';
            $html .= '<ul>';
            foreach ($issues as $issue) {
                $html .= '<li style="color: #d63638;">⚠ ' . esc_html($issue) . '</li>';
            }
            $html .= '</ul>';
        } else {
            $html .= '<p style="color: #00a32a;">✓ ' . __('No critical SEO issues found!', 'aqualuxe') . '</p>';
        }
        
        $html .= '<h4>' . __('Recommendations:', 'aqualuxe') . '</h4>';
        $html .= '<ul>';
        foreach ($recommendations as $recommendation) {
            $html .= '<li>💡 ' . esc_html($recommendation) . '</li>';
        }
        $html .= '</ul>';
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Get posts without meta description
     */
    private function get_posts_without_meta_description() {
        global $wpdb;
        
        $posts = $wpdb->get_results("
            SELECT p.ID, p.post_title 
            FROM {$wpdb->posts} p 
            WHERE p.post_status = 'publish' 
            AND p.post_type IN ('post', 'page', 'aqualuxe_service', 'aqualuxe_product')
            AND p.ID NOT IN (
                SELECT pm.post_id 
                FROM {$wpdb->postmeta} pm 
                WHERE pm.meta_key = '_aqualuxe_meta_description' 
                AND pm.meta_value != ''
            )
            LIMIT 20
        ");
        
        return $posts;
    }

    /**
     * Find duplicate titles
     */
    private function find_duplicate_titles() {
        global $wpdb;
        
        $duplicates = $wpdb->get_results("
            SELECT post_title, COUNT(*) as count 
            FROM {$wpdb->posts} 
            WHERE post_status = 'publish' 
            AND post_type IN ('post', 'page', 'aqualuxe_service', 'aqualuxe_product')
            GROUP BY post_title 
            HAVING count > 1
        ");
        
        return $duplicates;
    }

    /**
     * Get images without alt text
     */
    private function get_images_without_alt() {
        global $wpdb;
        
        $images = $wpdb->get_results("
            SELECT p.ID, p.post_title 
            FROM {$wpdb->posts} p 
            WHERE p.post_type = 'attachment' 
            AND p.post_mime_type LIKE 'image%'
            AND p.ID NOT IN (
                SELECT pm.post_id 
                FROM {$wpdb->postmeta} pm 
                WHERE pm.meta_key = '_wp_attachment_image_alt' 
                AND pm.meta_value != ''
            )
            LIMIT 20
        ");
        
        return $images;
    }
}