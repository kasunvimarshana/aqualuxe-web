<?php
/**
 * AquaLuxe SEO Class
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEO Optimization Class
 *
 * @class AquaLuxe_SEO
 */
class AquaLuxe_SEO {

    /**
     * Single instance of the class
     *
     * @var AquaLuxe_SEO
     */
    protected static $_instance = null;

    /**
     * Main AquaLuxe_SEO Instance
     *
     * @static
     * @return AquaLuxe_SEO - Main instance
     */
    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters
     */
    private function init_hooks() {
        // Meta tags
        add_action('wp_head', array($this, 'add_meta_tags'), 1);
        add_action('wp_head', array($this, 'add_open_graph_tags'), 5);
        add_action('wp_head', array($this, 'add_twitter_cards'), 6);
        add_action('wp_head', array($this, 'add_schema_markup'), 7);
        
        // Canonical URLs
        add_action('wp_head', array($this, 'add_canonical_url'), 2);
        
        // Sitemap
        add_action('init', array($this, 'add_sitemap_support'));
        
        // Robots.txt
        add_filter('robots_txt', array($this, 'customize_robots_txt'), 10, 2);
        
        // Breadcrumbs
        add_action('aqualuxe_breadcrumbs', array($this, 'display_breadcrumbs'));
        
        // Image SEO
        add_filter('wp_get_attachment_image_attributes', array($this, 'optimize_image_attributes'), 10, 3);
        
        // Clean URLs
        add_filter('redirect_canonical', array($this, 'remove_trailing_slash_redirect'), 10, 2);
        
        // RSS optimization
        add_filter('the_excerpt_rss', array($this, 'optimize_rss_excerpt'));
        add_filter('the_content_feed', array($this, 'optimize_rss_content'));
    }

    /**
     * Add meta tags
     */
    public function add_meta_tags() {
        // Viewport
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">' . "\n";
        
        // Theme color
        echo '<meta name="theme-color" content="#06b6d4">' . "\n";
        
        // Description
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
        
        // Keywords (only if set)
        $keywords = $this->get_meta_keywords();
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        // Author
        if (is_single()) {
            $author = get_the_author();
            if ($author) {
                echo '<meta name="author" content="' . esc_attr($author) . '">' . "\n";
            }
        }
        
        // Robots
        $robots = $this->get_robots_meta();
        if ($robots) {
            echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
        }
        
        // Language
        $language = get_bloginfo('language');
        if ($language) {
            echo '<meta name="language" content="' . esc_attr($language) . '">' . "\n";
        }
    }

    /**
     * Add Open Graph tags
     */
    public function add_open_graph_tags() {
        global $post;
        
        // Basic Open Graph tags
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        
        if (is_home() || is_front_page()) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '">' . "\n";
        } elseif (is_single()) {
            echo '<meta property="og:type" content="article">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
            
            // Author
            $author_id = get_the_author_meta('ID');
            if ($author_id) {
                echo '<meta property="article:author" content="' . esc_url(get_author_posts_url($author_id)) . '">' . "\n";
            }
            
            // Categories and tags
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
        } elseif (is_page()) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        }
        
        // Featured image
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            if ($image) {
                echo '<meta property="og:image" content="' . esc_url($image[0]) . '">' . "\n";
                echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '">' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '">' . "\n";
                echo '<meta property="og:image:alt" content="' . esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) . '">' . "\n";
            }
        }
    }

    /**
     * Add Twitter Card tags
     */
    public function add_twitter_cards() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        
        $twitter_handle = get_theme_mod('aqualuxe_twitter_handle', '');
        if ($twitter_handle) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_handle) . '">' . "\n";
        }
        
        if (is_single()) {
            $author_twitter = get_the_author_meta('twitter');
            if ($author_twitter) {
                echo '<meta name="twitter:creator" content="@' . esc_attr($author_twitter) . '">' . "\n";
            }
        }
        
        echo '<meta name="twitter:title" content="' . esc_attr($this->get_page_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            if ($image) {
                echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '">' . "\n";
            }
        }
    }

    /**
     * Add Schema.org markup
     */
    public function add_schema_markup() {
        $schema = array();
        
        if (is_home() || is_front_page()) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'url' => home_url(),
                'description' => get_bloginfo('description'),
                'potentialAction' => array(
                    '@type' => 'SearchAction',
                    'target' => home_url('/?s={search_term_string}'),
                    'query-input' => 'required name=search_term_string'
                )
            );
            
            // Organization schema
            $organization = array(
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url(),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => wp_get_attachment_url(get_theme_mod('custom_logo'))
                )
            );
            
            $social_links = array();
            $social_platforms = array('facebook', 'twitter', 'instagram', 'linkedin', 'youtube');
            foreach ($social_platforms as $platform) {
                $url = get_theme_mod('aqualuxe_' . $platform . '_url', '');
                if ($url) {
                    $social_links[] = $url;
                }
            }
            
            if (!empty($social_links)) {
                $organization['sameAs'] = $social_links;
            }
            
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
            echo '<script type="application/ld+json">' . wp_json_encode($organization) . '</script>' . "\n";
            
        } elseif (is_single()) {
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
                    'name' => get_the_author(),
                    'url' => get_author_posts_url(get_the_author_meta('ID'))
                ),
                'publisher' => array(
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                    'url' => home_url(),
                    'logo' => array(
                        '@type' => 'ImageObject',
                        'url' => wp_get_attachment_url(get_theme_mod('custom_logo'))
                    )
                )
            );
            
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    $schema['image'] = array(
                        '@type' => 'ImageObject',
                        'url' => $image[0],
                        'width' => $image[1],
                        'height' => $image[2]
                    );
                }
            }
            
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
        
        // Product schema for WooCommerce
        if (function_exists('is_product') && is_product()) {
            global $product;
            
            $product_schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->get_name(),
                'description' => wp_strip_all_tags($product->get_description()),
                'sku' => $product->get_sku(),
                'url' => get_permalink(),
                'offers' => array(
                    '@type' => 'Offer',
                    'price' => $product->get_price(),
                    'priceCurrency' => get_woocommerce_currency(),
                    'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    'url' => get_permalink()
                )
            );
            
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    $product_schema['image'] = $image[0];
                }
            }
            
            echo '<script type="application/ld+json">' . wp_json_encode($product_schema) . '</script>' . "\n";
        }
    }

    /**
     * Add canonical URL
     */
    public function add_canonical_url() {
        $canonical_url = '';
        
        if (is_home() || is_front_page()) {
            $canonical_url = home_url('/');
        } elseif (is_single() || is_page()) {
            $canonical_url = get_permalink();
        } elseif (is_category()) {
            $canonical_url = get_category_link(get_queried_object_id());
        } elseif (is_tag()) {
            $canonical_url = get_tag_link(get_queried_object_id());
        } elseif (is_author()) {
            $canonical_url = get_author_posts_url(get_queried_object_id());
        } elseif (is_archive()) {
            $canonical_url = get_post_type_archive_link(get_post_type());
        }
        
        if ($canonical_url) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
        }
    }

    /**
     * Get meta description
     */
    private function get_meta_description() {
        $description = '';
        
        if (is_home() || is_front_page()) {
            $description = get_bloginfo('description');
        } elseif (is_single() || is_page()) {
            // Try custom meta description first
            $custom_description = get_post_meta(get_the_ID(), '_aqualuxe_meta_description', true);
            if ($custom_description) {
                $description = $custom_description;
            } else {
                // Use excerpt or content
                $excerpt = get_the_excerpt();
                if ($excerpt) {
                    $description = wp_strip_all_tags($excerpt);
                } else {
                    $content = get_the_content();
                    $description = wp_trim_words(wp_strip_all_tags($content), 25, '');
                }
            }
        } elseif (is_category()) {
            $description = category_description();
        } elseif (is_tag()) {
            $description = tag_description();
        } elseif (is_author()) {
            $description = get_the_author_meta('description');
        }
        
        return wp_trim_words($description, 25, '');
    }

    /**
     * Get meta keywords
     */
    private function get_meta_keywords() {
        $keywords = '';
        
        if (is_single()) {
            $custom_keywords = get_post_meta(get_the_ID(), '_aqualuxe_meta_keywords', true);
            if ($custom_keywords) {
                $keywords = $custom_keywords;
            } else {
                $tags = get_the_tags();
                if ($tags) {
                    $tag_names = array();
                    foreach ($tags as $tag) {
                        $tag_names[] = $tag->name;
                    }
                    $keywords = implode(', ', $tag_names);
                }
            }
        }
        
        return $keywords;
    }

    /**
     * Get robots meta
     */
    private function get_robots_meta() {
        $robots = array();
        
        if (is_search() || is_404()) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        } elseif (is_archive() && !is_category() && !is_tag()) {
            $robots[] = 'noindex';
            $robots[] = 'follow';
        } else {
            $robots[] = 'index';
            $robots[] = 'follow';
        }
        
        // Check for custom robots meta
        if (is_single() || is_page()) {
            $custom_robots = get_post_meta(get_the_ID(), '_aqualuxe_robots_meta', true);
            if ($custom_robots) {
                $robots = explode(',', $custom_robots);
            }
        }
        
        return implode(', ', array_map('trim', $robots));
    }

    /**
     * Get page title
     */
    private function get_page_title() {
        if (is_home() || is_front_page()) {
            return get_bloginfo('name');
        } elseif (is_single() || is_page()) {
            return get_the_title();
        } elseif (is_category()) {
            return single_cat_title('', false);
        } elseif (is_tag()) {
            return single_tag_title('', false);
        } elseif (is_author()) {
            return get_the_author();
        } elseif (is_search()) {
            return sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query());
        } elseif (is_404()) {
            return esc_html__('Page Not Found', 'aqualuxe');
        }
        
        return get_bloginfo('name');
    }

    /**
     * Add sitemap support
     */
    public function add_sitemap_support() {
        // WordPress core sitemap support (WordPress 5.5+)
        add_filter('wp_sitemaps_enabled', '__return_true');
        
        // Add custom post types to sitemap
        add_filter('wp_sitemaps_post_types', array($this, 'add_custom_post_types_to_sitemap'));
    }

    /**
     * Add custom post types to sitemap
     */
    public function add_custom_post_types_to_sitemap($post_types) {
        // Add custom post types here
        $custom_post_types = array('product', 'service', 'event');
        
        foreach ($custom_post_types as $post_type) {
            if (post_type_exists($post_type)) {
                $post_types[$post_type] = get_post_type_object($post_type);
            }
        }
        
        return $post_types;
    }

    /**
     * Customize robots.txt
     */
    public function customize_robots_txt($output, $public) {
        if ($public) {
            $output .= "Sitemap: " . home_url('/wp-sitemap.xml') . "\n";
            
            // Block access to admin and include directories
            $output .= "Disallow: /wp-admin/\n";
            $output .= "Disallow: /wp-includes/\n";
            $output .= "Disallow: /wp-content/plugins/\n";
            $output .= "Disallow: /wp-content/themes/\n";
            $output .= "Disallow: /?s=\n";
            $output .= "Disallow: /search/\n";
        }
        
        return $output;
    }

    /**
     * Display breadcrumbs
     */
    public function display_breadcrumbs() {
        if (!is_home() && !is_front_page()) {
            echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
            echo '<ol class="breadcrumb-list">';
            
            // Home link
            echo '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
            
            if (is_single()) {
                $categories = get_the_category();
                if ($categories) {
                    $category = $categories[0];
                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                }
                echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
            } elseif (is_page()) {
                $parents = array();
                $parent_id = wp_get_post_parent_id();
                
                while ($parent_id) {
                    $parents[] = $parent_id;
                    $parent_id = wp_get_post_parent_id($parent_id);
                }
                
                $parents = array_reverse($parents);
                
                foreach ($parents as $parent) {
                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($parent)) . '">' . esc_html(get_the_title($parent)) . '</a></li>';
                }
                
                echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
            } elseif (is_category()) {
                echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(single_cat_title('', false)) . '</li>';
            } elseif (is_tag()) {
                echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(single_tag_title('', false)) . '</li>';
            } elseif (is_search()) {
                echo '<li class="breadcrumb-item active" aria-current="page">' . sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query()) . '</li>';
            }
            
            echo '</ol>';
            echo '</nav>';
        }
    }

    /**
     * Optimize image attributes for SEO
     */
    public function optimize_image_attributes($attr, $attachment, $size) {
        $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        
        if (empty($alt_text)) {
            // Generate alt text from filename or title
            $alt_text = get_the_title($attachment->ID);
            if (empty($alt_text)) {
                $alt_text = pathinfo(get_attached_file($attachment->ID), PATHINFO_FILENAME);
                $alt_text = str_replace(array('-', '_'), ' ', $alt_text);
            }
        }
        
        $attr['alt'] = $alt_text;
        
        return $attr;
    }

    /**
     * Remove trailing slash redirect for better SEO
     */
    public function remove_trailing_slash_redirect($redirect_url, $requested_url) {
        if (is_admin() || is_feed() || is_trackback()) {
            return $redirect_url;
        }
        
        return null;
    }

    /**
     * Optimize RSS excerpt
     */
    public function optimize_rss_excerpt($excerpt) {
        return wp_trim_words($excerpt, 50, '...');
    }

    /**
     * Optimize RSS content
     */
    public function optimize_rss_content($content) {
        // Add link to full article
        $content .= '<p><a href="' . esc_url(get_permalink()) . '">' . esc_html__('Read the full article', 'aqualuxe') . '</a></p>';
        
        return $content;
    }
}