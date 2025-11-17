<?php
/**
 * SEO Class
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEO optimization class
 */
class SEO {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Meta tags
        add_action('wp_head', [$this, 'add_meta_tags'], 5);
        
        // Open Graph tags
        add_action('wp_head', [$this, 'add_open_graph_tags'], 10);
        
        // Twitter Card tags
        add_action('wp_head', [$this, 'add_twitter_card_tags'], 15);
        
        // JSON-LD structured data
        add_action('wp_head', [$this, 'add_json_ld_schema'], 20);
        
        // Canonical URLs
        add_action('wp_head', [$this, 'add_canonical_url'], 25);
        
        // Sitemap
        add_action('init', [$this, 'init_sitemap']);
        
        // Robots.txt
        add_filter('robots_txt', [$this, 'customize_robots_txt'], 10, 2);
        
        // Title optimization
        add_filter('wp_title', [$this, 'optimize_title'], 10, 2);
        add_filter('document_title_parts', [$this, 'optimize_document_title']);
        
        // Meta description
        add_action('wp_head', [$this, 'add_meta_description'], 6);
        
        // Breadcrumbs schema
        add_action('wp_head', [$this, 'add_breadcrumb_schema'], 30);
        
        // Image optimization for SEO
        add_filter('wp_get_attachment_image_attributes', [$this, 'optimize_image_attributes'], 10, 3);
        
        // RSS optimization
        add_filter('the_excerpt_rss', [$this, 'optimize_rss_excerpt']);
        add_filter('the_content_feed', [$this, 'optimize_rss_content']);
        
        // Search optimization
        add_filter('posts_search', [$this, 'improve_search_query'], 10, 2);
        
        // URL optimization
        add_filter('post_link', [$this, 'optimize_post_links'], 10, 2);
        
        // WooCommerce SEO
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_single_product_summary', [$this, 'add_product_schema'], 5);
        }
    }
    
    /**
     * Add basic meta tags
     */
    public function add_meta_tags() {
        // Viewport
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">' . "\n";
        
        // Author
        if (is_singular()) {
            $author_id = get_post_field('post_author', get_queried_object_id());
            $author_name = get_the_author_meta('display_name', $author_id);
            echo '<meta name="author" content="' . esc_attr($author_name) . '">' . "\n";
        }
        
        // Robots
        $robots = $this->get_robots_meta();
        if ($robots) {
            echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
        }
        
        // Language
        $language = get_locale();
        echo '<meta name="language" content="' . esc_attr($language) . '">' . "\n";
        
        // Theme color for mobile browsers
        $theme_color = get_theme_mod('aqualuxe_theme_color', '#0ea5e9');
        echo '<meta name="theme-color" content="' . esc_attr($theme_color) . '">' . "\n";
        echo '<meta name="msapplication-TileColor" content="' . esc_attr($theme_color) . '">' . "\n";
    }
    
    /**
     * Add meta description
     */
    public function add_meta_description() {
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
    }
    
    /**
     * Get meta description for current page
     */
    private function get_meta_description() {
        $description = '';
        
        if (is_front_page()) {
            $description = get_theme_mod('aqualuxe_site_description', get_bloginfo('description'));
        } elseif (is_singular()) {
            // Check for custom meta description
            $custom_description = get_post_meta(get_the_ID(), 'aqualuxe_meta_description', true);
            if ($custom_description) {
                $description = $custom_description;
            } else {
                $excerpt = get_the_excerpt();
                if ($excerpt) {
                    $description = wp_strip_all_tags($excerpt);
                } else {
                    $content = get_the_content();
                    $description = wp_strip_all_tags($content);
                    $description = wp_trim_words($description, 25);
                }
            }
        } elseif (is_category()) {
            $description = category_description();
            if (!$description) {
                $description = sprintf(__('Browse our %s category for the latest posts and updates.', 'aqualuxe'), single_cat_title('', false));
            }
        } elseif (is_tag()) {
            $description = tag_description();
            if (!$description) {
                $description = sprintf(__('Posts tagged with %s.', 'aqualuxe'), single_tag_title('', false));
            }
        } elseif (is_author()) {
            $author = get_queried_object();
            $description = get_the_author_meta('description', $author->ID);
            if (!$description) {
                $description = sprintf(__('Posts by %s.', 'aqualuxe'), $author->display_name);
            }
        } elseif (is_archive()) {
            $description = get_the_archive_description();
        }
        
        // Clean and limit description
        $description = wp_strip_all_tags($description);
        $description = preg_replace('/\s+/', ' ', $description);
        $description = trim($description);
        
        if (strlen($description) > 160) {
            $description = wp_trim_words($description, 25);
        }
        
        return $description;
    }
    
    /**
     * Add Open Graph tags
     */
    public function add_open_graph_tags() {
        echo '<meta property="og:type" content="' . esc_attr($this->get_og_type()) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($this->get_canonical_url()) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        
        // Image
        $image_url = $this->get_og_image();
        if ($image_url) {
            echo '<meta property="og:image" content="' . esc_url($image_url) . '">' . "\n";
            echo '<meta property="og:image:width" content="1200">' . "\n";
            echo '<meta property="og:image:height" content="630">' . "\n";
            echo '<meta property="og:image:alt" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        }
        
        // Article specific tags
        if (is_singular('post')) {
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
            
            $author_id = get_post_field('post_author', get_the_ID());
            echo '<meta property="article:author" content="' . esc_attr(get_the_author_meta('display_name', $author_id)) . '">' . "\n";
            
            $categories = get_the_category();
            foreach ($categories as $category) {
                echo '<meta property="article:section" content="' . esc_attr($category->name) . '">' . "\n";
            }
            
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                }
            }
        }
    }
    
    /**
     * Add Twitter Card tags
     */
    public function add_twitter_card_tags() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        
        $twitter_handle = get_theme_mod('aqualuxe_twitter_handle');
        if ($twitter_handle) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_handle) . '">' . "\n";
            echo '<meta name="twitter:creator" content="@' . esc_attr($twitter_handle) . '">' . "\n";
        }
        
        $image_url = $this->get_og_image();
        if ($image_url) {
            echo '<meta name="twitter:image" content="' . esc_url($image_url) . '">' . "\n";
            echo '<meta name="twitter:image:alt" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        }
    }
    
    /**
     * Add JSON-LD structured data
     */
    public function add_json_ld_schema() {
        $schema = [];
        
        if (is_front_page()) {
            $schema = $this->get_organization_schema();
        } elseif (is_singular('post')) {
            $schema = $this->get_article_schema();
        } elseif (is_page()) {
            $schema = $this->get_webpage_schema();
        } elseif (is_singular('product') && class_exists('WooCommerce')) {
            $schema = $this->get_product_schema();
        } elseif (is_author()) {
            $schema = $this->get_person_schema();
        }
        
        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
    }
    
    /**
     * Get Organization schema
     */
    private function get_organization_schema() {
        $logo_url = get_theme_mod('aqualuxe_logo', get_template_directory_uri() . '/assets/images/logo.png');
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url(),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo_url,
            ],
            'sameAs' => $this->get_social_profiles(),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'email' => get_option('admin_email'),
            ],
        ];
    }
    
    /**
     * Get Article schema
     */
    private function get_article_schema() {
        $post_id = get_the_ID();
        $author_id = get_post_field('post_author', $post_id);
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => $this->get_meta_description(),
            'image' => $this->get_og_image(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author_meta('display_name', $author_id),
                'url' => get_author_posts_url($author_id),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => get_theme_mod('aqualuxe_logo', get_template_directory_uri() . '/assets/images/logo.png'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => get_permalink(),
            ],
        ];
    }
    
    /**
     * Get WebPage schema
     */
    private function get_webpage_schema() {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_the_title(),
            'description' => $this->get_meta_description(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
            ],
        ];
    }
    
    /**
     * Get Product schema (WooCommerce)
     */
    private function get_product_schema() {
        if (!class_exists('WooCommerce')) {
            return [];
        }
        
        global $product;
        if (!$product) {
            return [];
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => $product->get_short_description() ?: $product->get_description(),
            'sku' => $product->get_sku(),
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'large'),
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                ],
            ],
        ];
        
        // Add rating if available
        if ($product->get_rating_count() > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            ];
        }
        
        // Add brand if available
        $brand = get_post_meta($product->get_id(), 'aqualuxe_product_brand', true);
        if ($brand) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $brand,
            ];
        }
        
        return $schema;
    }
    
    /**
     * Get Person schema
     */
    private function get_person_schema() {
        $author = get_queried_object();
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $author->display_name,
            'description' => get_the_author_meta('description', $author->ID),
            'url' => get_author_posts_url($author->ID),
            'sameAs' => [
                get_the_author_meta('url', $author->ID),
            ],
        ];
    }
    
    /**
     * Add canonical URL
     */
    public function add_canonical_url() {
        $canonical_url = $this->get_canonical_url();
        if ($canonical_url) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
        }
    }
    
    /**
     * Get canonical URL
     */
    private function get_canonical_url() {
        if (is_front_page()) {
            return home_url('/');
        } elseif (is_singular()) {
            return get_permalink();
        } elseif (is_category()) {
            return get_category_link(get_queried_object_id());
        } elseif (is_tag()) {
            return get_tag_link(get_queried_object_id());
        } elseif (is_author()) {
            return get_author_posts_url(get_queried_object_id());
        } elseif (is_archive()) {
            return get_post_type_archive_link(get_post_type());
        }
        
        return '';
    }
    
    /**
     * Add breadcrumb schema
     */
    public function add_breadcrumb_schema() {
        if (is_front_page()) {
            return;
        }
        
        $breadcrumbs = $this->get_breadcrumb_items();
        
        if (empty($breadcrumbs)) {
            return;
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['title'],
                'item' => $breadcrumb['url'],
            ];
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
    
    /**
     * Get breadcrumb items
     */
    private function get_breadcrumb_items() {
        $breadcrumbs = [];
        
        // Home
        $breadcrumbs[] = [
            'title' => __('Home', 'aqualuxe'),
            'url' => home_url('/'),
        ];
        
        if (is_category()) {
            $category = get_queried_object();
            $ancestors = get_ancestors($category->term_id, 'category');
            
            foreach (array_reverse($ancestors) as $ancestor_id) {
                $ancestor = get_category($ancestor_id);
                $breadcrumbs[] = [
                    'title' => $ancestor->name,
                    'url' => get_category_link($ancestor_id),
                ];
            }
            
            $breadcrumbs[] = [
                'title' => $category->name,
                'url' => get_category_link($category->term_id),
            ];
        } elseif (is_single()) {
            $post_type = get_post_type();
            
            if ($post_type === 'post') {
                $categories = get_the_category();
                if (!empty($categories)) {
                    $category = $categories[0];
                    $breadcrumbs[] = [
                        'title' => $category->name,
                        'url' => get_category_link($category->term_id),
                    ];
                }
            } elseif ($post_type === 'product' && class_exists('WooCommerce')) {
                $breadcrumbs[] = [
                    'title' => __('Shop', 'aqualuxe'),
                    'url' => wc_get_page_permalink('shop'),
                ];
                
                $product_categories = get_the_terms(get_the_ID(), 'product_cat');
                if (!empty($product_categories)) {
                    $category = $product_categories[0];
                    $breadcrumbs[] = [
                        'title' => $category->name,
                        'url' => get_term_link($category),
                    ];
                }
            }
            
            $breadcrumbs[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
            ];
        } elseif (is_page()) {
            $ancestors = get_post_ancestors(get_the_ID());
            
            foreach (array_reverse($ancestors) as $ancestor_id) {
                $breadcrumbs[] = [
                    'title' => get_the_title($ancestor_id),
                    'url' => get_permalink($ancestor_id),
                ];
            }
            
            $breadcrumbs[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
            ];
        }
        
        return $breadcrumbs;
    }
    
    /**
     * Initialize sitemap
     */
    public function init_sitemap() {
        if (get_theme_mod('aqualuxe_enable_sitemap', true)) {
            add_action('init', [$this, 'generate_sitemap']);
            add_rewrite_rule('^sitemap\.xml$', 'index.php?sitemap=1', 'top');
            add_filter('query_vars', function($vars) {
                $vars[] = 'sitemap';
                return $vars;
            });
            
            add_action('template_redirect', function() {
                if (get_query_var('sitemap')) {
                    $this->output_sitemap();
                    exit;
                }
            });
        }
    }
    
    /**
     * Generate sitemap
     */
    public function generate_sitemap() {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        $sitemap .= $this->get_sitemap_url(home_url('/'), get_lastpostmodified('gmt'), '1.0', 'daily');
        
        // Posts
        $posts = get_posts([
            'post_type' => ['post', 'page'],
            'post_status' => 'publish',
            'numberposts' => -1,
        ]);
        
        foreach ($posts as $post) {
            $priority = is_page($post->ID) ? '0.8' : '0.6';
            $changefreq = $post->post_type === 'page' ? 'weekly' : 'monthly';
            
            $sitemap .= $this->get_sitemap_url(
                get_permalink($post->ID),
                $post->post_modified_gmt,
                $priority,
                $changefreq
            );
        }
        
        // Categories
        $categories = get_categories(['hide_empty' => true]);
        foreach ($categories as $category) {
            $sitemap .= $this->get_sitemap_url(
                get_category_link($category->term_id),
                '',
                '0.4',
                'weekly'
            );
        }
        
        // WooCommerce
        if (class_exists('WooCommerce')) {
            // Products
            $products = get_posts([
                'post_type' => 'product',
                'post_status' => 'publish',
                'numberposts' => -1,
            ]);
            
            foreach ($products as $product) {
                $sitemap .= $this->get_sitemap_url(
                    get_permalink($product->ID),
                    $product->post_modified_gmt,
                    '0.7',
                    'weekly'
                );
            }
            
            // Product categories
            $product_categories = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
            ]);
            
            foreach ($product_categories as $category) {
                $sitemap .= $this->get_sitemap_url(
                    get_term_link($category),
                    '',
                    '0.5',
                    'weekly'
                );
            }
        }
        
        $sitemap .= '</urlset>';
        
        // Cache sitemap
        update_option('aqualuxe_sitemap', $sitemap);
        update_option('aqualuxe_sitemap_generated', time());
    }
    
    /**
     * Get sitemap URL entry
     */
    private function get_sitemap_url($url, $lastmod = '', $priority = '0.5', $changefreq = 'monthly') {
        $entry = '  <url>' . "\n";
        $entry .= '    <loc>' . esc_url($url) . '</loc>' . "\n";
        
        if ($lastmod) {
            $entry .= '    <lastmod>' . date('c', strtotime($lastmod)) . '</lastmod>' . "\n";
        }
        
        $entry .= '    <changefreq>' . $changefreq . '</changefreq>' . "\n";
        $entry .= '    <priority>' . $priority . '</priority>' . "\n";
        $entry .= '  </url>' . "\n";
        
        return $entry;
    }
    
    /**
     * Output sitemap
     */
    public function output_sitemap() {
        $sitemap = get_option('aqualuxe_sitemap');
        $generated = get_option('aqualuxe_sitemap_generated', 0);
        
        // Regenerate if older than 24 hours
        if (!$sitemap || (time() - $generated) > 86400) {
            $this->generate_sitemap();
            $sitemap = get_option('aqualuxe_sitemap');
        }
        
        header('Content-Type: application/xml; charset=utf-8');
        echo $sitemap;
    }
    
    /**
     * Customize robots.txt
     */
    public function customize_robots_txt($output, $public) {
        if ($public) {
            $output .= "\n";
            $output .= "# AquaLuxe Theme Robots.txt\n";
            $output .= "Sitemap: " . home_url('/sitemap.xml') . "\n";
            $output .= "\n";
            $output .= "# Block access to theme files\n";
            $output .= "Disallow: /wp-content/themes/*/assets/src/\n";
            $output .= "Disallow: /wp-content/themes/*/node_modules/\n";
            $output .= "Disallow: /wp-content/themes/*/*.md\n";
            $output .= "\n";
            $output .= "# Allow crawling of important files\n";
            $output .= "Allow: /wp-content/themes/*/assets/dist/\n";
            $output .= "Allow: /wp-content/uploads/\n";
        }
        
        return $output;
    }
    
    /**
     * Optimize title
     */
    public function optimize_title($title, $sep) {
        // This is for older WordPress versions
        return $title;
    }
    
    /**
     * Optimize document title
     */
    public function optimize_document_title($title) {
        if (is_front_page()) {
            $custom_title = get_theme_mod('aqualuxe_site_title');
            if ($custom_title) {
                $title['title'] = $custom_title;
            }
        }
        
        // Remove unnecessary parts
        if (isset($title['site']) && isset($title['title']) && $title['site'] === $title['title']) {
            unset($title['title']);
        }
        
        return $title;
    }
    
    /**
     * Optimize image attributes for SEO
     */
    public function optimize_image_attributes($attr, $attachment, $size) {
        // Add alt text if missing
        if (empty($attr['alt'])) {
            $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            if (!$alt_text) {
                $alt_text = $attachment->post_title;
            }
            $attr['alt'] = $alt_text;
        }
        
        return $attr;
    }
    
    /**
     * Optimize RSS excerpt
     */
    public function optimize_rss_excerpt($excerpt) {
        // Add read more link
        if ($excerpt) {
            $excerpt .= ' <a href="' . get_permalink() . '">' . __('Read more...', 'aqualuxe') . '</a>';
        }
        
        return $excerpt;
    }
    
    /**
     * Optimize RSS content
     */
    public function optimize_rss_content($content) {
        // Add featured image to RSS
        if (has_post_thumbnail()) {
            $featured_image = get_the_post_thumbnail(null, 'medium');
            $content = $featured_image . $content;
        }
        
        return $content;
    }
    
    /**
     * Improve search query
     */
    public function improve_search_query($search, $query) {
        if (!is_admin() && $query->is_main_query() && $query->is_search()) {
            global $wpdb;
            
            $search_term = $query->get('s');
            if ($search_term) {
                // Search in title, content, and excerpt
                $search = " AND (
                    ({$wpdb->posts}.post_title LIKE '%{$search_term}%') OR 
                    ({$wpdb->posts}.post_content LIKE '%{$search_term}%') OR 
                    ({$wpdb->posts}.post_excerpt LIKE '%{$search_term}%')
                )";
            }
        }
        
        return $search;
    }
    
    /**
     * Optimize post links
     */
    public function optimize_post_links($permalink, $post) {
        // Remove unnecessary parameters
        $permalink = remove_query_arg(['utm_source', 'utm_medium', 'utm_campaign'], $permalink);
        
        return $permalink;
    }
    
    /**
     * Add product schema for WooCommerce
     */
    public function add_product_schema() {
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        $schema = $this->get_product_schema();
        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
    }
    
    /**
     * Helper methods
     */
    
    private function get_robots_meta() {
        $robots = [];
        
        if (is_search() || is_404()) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        } elseif (is_archive() && !is_category() && !is_tag()) {
            $robots[] = 'noindex';
        }
        
        // Custom meta robots
        if (is_singular()) {
            $custom_robots = get_post_meta(get_the_ID(), 'aqualuxe_meta_robots', true);
            if ($custom_robots) {
                $robots = explode(',', $custom_robots);
            }
        }
        
        return implode(', ', array_filter($robots));
    }
    
    private function get_og_type() {
        if (is_singular('post')) {
            return 'article';
        } elseif (is_singular('product')) {
            return 'product';
        } else {
            return 'website';
        }
    }
    
    private function get_og_title() {
        if (is_front_page()) {
            return get_theme_mod('aqualuxe_site_title', get_bloginfo('name'));
        } else {
            return get_the_title();
        }
    }
    
    private function get_og_image() {
        $image_url = '';
        
        if (is_singular() && has_post_thumbnail()) {
            $image_url = get_the_post_thumbnail_url(null, 'large');
        } else {
            $image_url = get_theme_mod('aqualuxe_default_og_image', get_template_directory_uri() . '/assets/images/og-default.jpg');
        }
        
        return $image_url;
    }
    
    private function get_social_profiles() {
        $profiles = [];
        
        $social_networks = [
            'facebook' => get_theme_mod('aqualuxe_facebook_url'),
            'twitter' => get_theme_mod('aqualuxe_twitter_url'),
            'instagram' => get_theme_mod('aqualuxe_instagram_url'),
            'linkedin' => get_theme_mod('aqualuxe_linkedin_url'),
            'youtube' => get_theme_mod('aqualuxe_youtube_url'),
        ];
        
        foreach ($social_networks as $network => $url) {
            if ($url) {
                $profiles[] = $url;
            }
        }
        
        return $profiles;
    }
}
