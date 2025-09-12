<?php
/**
 * SEO Class
 * 
 * Handles SEO optimizations and meta tags
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_SEO {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize SEO features
     */
    private function init() {
        // Meta tags
        add_action('wp_head', [$this, 'add_meta_tags'], 1);
        
        // Open Graph tags
        add_action('wp_head', [$this, 'add_open_graph_tags'], 5);
        
        // Twitter Card tags
        add_action('wp_head', [$this, 'add_twitter_card_tags'], 6);
        
        // JSON-LD Schema
        add_action('wp_head', [$this, 'add_json_ld_schema'], 10);
        
        // Canonical URLs
        add_action('wp_head', [$this, 'add_canonical_url'], 2);
        
        // Sitemap
        add_action('init', [$this, 'generate_sitemap']);
        
        // Robots.txt
        add_filter('robots_txt', [$this, 'custom_robots_txt'], 10, 2);
        
        // Clean URLs
        add_action('init', [$this, 'clean_urls']);
        
        // Breadcrumbs
        add_action('aqualuxe_breadcrumbs', [$this, 'display_breadcrumbs']);
    }
    
    /**
     * Add basic meta tags
     */
    public function add_meta_tags() {
        global $post;
        
        // Description
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
        
        // Keywords (if set in customizer)
        $keywords = aqualuxe_get_option('meta_keywords');
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        // Author
        if (is_single() && $post) {
            $author = get_the_author_meta('display_name', $post->post_author);
            echo '<meta name="author" content="' . esc_attr($author) . '">' . "\n";
        }
        
        // Viewport
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">' . "\n";
        
        // Theme color
        echo '<meta name="theme-color" content="' . aqualuxe_get_option('primary_color', '#0ea5e9') . '">' . "\n";
        
        // Robots
        $robots = $this->get_robots_meta();
        if ($robots) {
            echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
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
            echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '">' . "\n";
        } elseif (is_singular()) {
            echo '<meta property="og:type" content="article">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    echo '<meta property="og:image" content="' . esc_url($image[0]) . '">' . "\n";
                    echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '">' . "\n";
                    echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '">' . "\n";
                }
            }
            
            // Article specific tags
            if (is_single()) {
                echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
                echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
                echo '<meta property="article:author" content="' . esc_attr(get_the_author()) . '">' . "\n";
                
                $categories = get_the_category();
                if ($categories) {
                    foreach ($categories as $category) {
                        echo '<meta property="article:section" content="' . esc_attr($category->name) . '">' . "\n";
                    }
                }
                
                $tags = get_the_tags();
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                    }
                }
            }
        }
        
        // Default image if no featured image
        if (!has_post_thumbnail() || !is_singular()) {
            $default_image = aqualuxe_get_option('default_og_image');
            if ($default_image) {
                echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
            }
        }
    }
    
    /**
     * Add Twitter Card tags
     */
    public function add_twitter_card_tags() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        
        $twitter_handle = aqualuxe_get_option('twitter_handle');
        if ($twitter_handle) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_handle) . '">' . "\n";
        }
        
        if (is_singular()) {
            echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
            
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '">' . "\n";
                }
            }
        }
    }
    
    /**
     * Add JSON-LD Schema markup
     */
    public function add_json_ld_schema() {
        $schema = [];
        
        if (is_front_page()) {
            $schema = $this->get_organization_schema();
        } elseif (is_singular('post')) {
            $schema = $this->get_article_schema();
        } elseif (is_singular('product') && function_exists('wc_get_product')) {
            $schema = $this->get_product_schema();
        } elseif (is_author()) {
            $schema = $this->get_person_schema();
        }
        
        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
        }
    }
    
    /**
     * Get organization schema
     */
    private function get_organization_schema() {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => get_custom_logo() ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => aqualuxe_get_option('phone'),
                'contactType' => 'customer service',
                'email' => aqualuxe_get_option('email'),
            ],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => aqualuxe_get_option('address'),
            ],
            'sameAs' => array_filter([
                aqualuxe_get_option('facebook_url'),
                aqualuxe_get_option('instagram_url'),
                aqualuxe_get_option('twitter_url'),
                aqualuxe_get_option('youtube_url'),
            ])
        ];
    }
    
    /**
     * Get article schema
     */
    private function get_article_schema() {
        global $post;
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => $this->get_meta_description(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author(),
                'url' => get_author_posts_url(get_the_author_meta('ID')),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => get_custom_logo() ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
                ]
            ]
        ];
        
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            if ($image) {
                $schema['image'] = [
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                ];
            }
        }
        
        return $schema;
    }
    
    /**
     * Get product schema for WooCommerce
     */
    private function get_product_schema() {
        if (!function_exists('wc_get_product')) {
            return [];
        }
        
        $product = wc_get_product(get_the_ID());
        if (!$product) {
            return [];
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags($product->get_description()),
            'sku' => $product->get_sku(),
            'url' => get_permalink(),
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink(),
            ]
        ];
        
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            if ($image) {
                $schema['image'] = $image[0];
            }
        }
        
        return $schema;
    }
    
    /**
     * Get person schema
     */
    private function get_person_schema() {
        $author_id = get_query_var('author');
        $author = get_userdata($author_id);
        
        if (!$author) {
            return [];
        }
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $author->display_name,
            'description' => get_the_author_meta('description', $author_id),
            'url' => get_author_posts_url($author_id),
            'sameAs' => array_filter([
                get_the_author_meta('url', $author_id),
                get_the_author_meta('facebook', $author_id),
                get_the_author_meta('twitter', $author_id),
            ])
        ];
    }
    
    /**
     * Add canonical URL
     */
    public function add_canonical_url() {
        $canonical_url = '';
        
        if (is_front_page()) {
            $canonical_url = home_url('/');
        } elseif (is_singular()) {
            $canonical_url = get_permalink();
        } elseif (is_category()) {
            $canonical_url = get_category_link(get_query_var('cat'));
        } elseif (is_tag()) {
            $canonical_url = get_tag_link(get_query_var('tag_id'));
        } elseif (is_author()) {
            $canonical_url = get_author_posts_url(get_query_var('author'));
        }
        
        if ($canonical_url) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
        }
    }
    
    /**
     * Generate sitemap
     */
    public function generate_sitemap() {
        add_action('wp_ajax_nopriv_aqualuxe_sitemap', [$this, 'output_sitemap']);
        add_action('wp_ajax_aqualuxe_sitemap', [$this, 'output_sitemap']);
        
        // Add rewrite rule for sitemap
        add_rewrite_rule('^sitemap\.xml$', 'index.php?aqualuxe_sitemap=1', 'top');
        add_filter('query_vars', function($vars) {
            $vars[] = 'aqualuxe_sitemap';
            return $vars;
        });
        
        add_action('template_redirect', [$this, 'handle_sitemap_request']);
    }
    
    /**
     * Handle sitemap request
     */
    public function handle_sitemap_request() {
        if (get_query_var('aqualuxe_sitemap')) {
            $this->output_sitemap();
            exit;
        }
    }
    
    /**
     * Output XML sitemap
     */
    public function output_sitemap() {
        header('Content-Type: application/xml; charset=utf-8');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        echo '<url>' . "\n";
        echo '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n";
        echo '<changefreq>daily</changefreq>' . "\n";
        echo '<priority>1.0</priority>' . "\n";
        echo '</url>' . "\n";
        
        // Posts and pages
        $posts = get_posts([
            'numberposts' => -1,
            'post_type' => ['post', 'page'],
            'post_status' => 'publish',
        ]);
        
        foreach ($posts as $post) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
            echo '<lastmod>' . esc_html(get_the_modified_date('c', $post->ID)) . '</lastmod>' . "\n";
            echo '<changefreq>' . ($post->post_type === 'post' ? 'weekly' : 'monthly') . '</changefreq>' . "\n";
            echo '<priority>' . ($post->post_type === 'page' ? '0.8' : '0.6') . '</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>' . "\n";
    }
    
    /**
     * Custom robots.txt
     */
    public function custom_robots_txt($output, $public) {
        if ($public) {
            $output .= "User-agent: *\n";
            $output .= "Disallow: /wp-admin/\n";
            $output .= "Disallow: /wp-includes/\n";
            $output .= "Disallow: /wp-content/plugins/\n";
            $output .= "Disallow: /wp-content/themes/\n";
            $output .= "Disallow: /trackback/\n";
            $output .= "Disallow: /comments/\n";
            $output .= "Disallow: */trackback/\n";
            $output .= "Disallow: */comments/\n";
            $output .= "Disallow: *?*\n";
            $output .= "Allow: /wp-content/uploads/\n";
            $output .= "\n";
            $output .= "Sitemap: " . home_url('/sitemap.xml') . "\n";
        }
        
        return $output;
    }
    
    /**
     * Clean URLs
     */
    public function clean_urls() {
        // Remove unnecessary query parameters
        add_filter('redirect_canonical', [$this, 'remove_unnecessary_params']);
    }
    
    /**
     * Remove unnecessary parameters from URLs
     */
    public function remove_unnecessary_params($redirect_url) {
        $unnecessary_params = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid'];
        
        if ($redirect_url) {
            $url_parts = parse_url($redirect_url);
            if (isset($url_parts['query'])) {
                parse_str($url_parts['query'], $query_params);
                
                foreach ($unnecessary_params as $param) {
                    unset($query_params[$param]);
                }
                
                $url_parts['query'] = http_build_query($query_params);
                $redirect_url = $this->build_url($url_parts);
            }
        }
        
        return $redirect_url;
    }
    
    /**
     * Build URL from parts
     */
    private function build_url($parts) {
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') .
               ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') .
               (isset($parts['user']) ? $parts['user'] : '') .
               (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
               (isset($parts['user']) ? '@' : '') .
               (isset($parts['host']) ? $parts['host'] : '') .
               (isset($parts['port']) ? ":{$parts['port']}" : '') .
               (isset($parts['path']) ? $parts['path'] : '') .
               (isset($parts['query']) && !empty($parts['query']) ? "?{$parts['query']}" : '') .
               (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }
    
    /**
     * Display breadcrumbs
     */
    public function display_breadcrumbs() {
        if (is_front_page()) {
            return;
        }
        
        echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
        echo '<ol class="breadcrumb-list flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">';
        
        // Home link
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . esc_url(home_url('/')) . '" class="hover:text-primary-600">' . esc_html__('Home', 'aqualuxe') . '</a>';
        echo '</li>';
        
        if (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();
            if ($term) {
                echo '<li class="breadcrumb-separator">/</li>';
                echo '<li class="breadcrumb-item font-medium text-gray-900 dark:text-white">';
                echo esc_html($term->name);
                echo '</li>';
            }
        } elseif (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                echo '<li class="breadcrumb-separator">/</li>';
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="hover:text-primary-600">';
                echo esc_html($categories[0]->name);
                echo '</a>';
                echo '</li>';
            }
            
            echo '<li class="breadcrumb-separator">/</li>';
            echo '<li class="breadcrumb-item font-medium text-gray-900 dark:text-white">';
            echo esc_html(get_the_title());
            echo '</li>';
        } elseif (is_page()) {
            $post = get_queried_object();
            if ($post && $post->post_parent) {
                $parents = [];
                $parent_id = $post->post_parent;
                
                while ($parent_id) {
                    $parent = get_post($parent_id);
                    $parents[] = $parent;
                    $parent_id = $parent->post_parent;
                }
                
                $parents = array_reverse($parents);
                
                foreach ($parents as $parent) {
                    echo '<li class="breadcrumb-separator">/</li>';
                    echo '<li class="breadcrumb-item">';
                    echo '<a href="' . esc_url(get_permalink($parent->ID)) . '" class="hover:text-primary-600">';
                    echo esc_html($parent->post_title);
                    echo '</a>';
                    echo '</li>';
                }
            }
            
            echo '<li class="breadcrumb-separator">/</li>';
            echo '<li class="breadcrumb-item font-medium text-gray-900 dark:text-white">';
            echo esc_html(get_the_title());
            echo '</li>';
        }
        
        echo '</ol>';
        echo '</nav>';
    }
    
    /**
     * Get meta description
     */
    private function get_meta_description() {
        global $post;
        
        if (is_front_page()) {
            return get_bloginfo('description');
        } elseif (is_singular()) {
            $excerpt = get_the_excerpt($post);
            return $excerpt ? wp_trim_words($excerpt, 30) : wp_trim_words(get_the_content(), 30);
        } elseif (is_category()) {
            $description = category_description();
            return $description ? wp_trim_words(strip_tags($description), 30) : '';
        } elseif (is_tag()) {
            $description = tag_description();
            return $description ? wp_trim_words(strip_tags($description), 30) : '';
        }
        
        return '';
    }
    
    /**
     * Get robots meta content
     */
    private function get_robots_meta() {
        if (is_search() || is_404()) {
            return 'noindex, nofollow';
        } elseif (is_category() || is_tag() || is_author()) {
            return 'index, follow, noarchive';
        }
        
        return 'index, follow';
    }
}