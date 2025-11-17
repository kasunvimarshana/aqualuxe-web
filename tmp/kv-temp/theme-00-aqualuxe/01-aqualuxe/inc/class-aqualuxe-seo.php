
<?php
/**
 * AquaLuxe SEO Class
 *
 * Handles all SEO optimizations
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe SEO Class
 */
class AquaLuxe_SEO {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_SEO
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_SEO
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Add meta tags
        add_action('wp_head', array($this, 'add_meta_tags'), 1);
        
        // Add Open Graph tags
        add_action('wp_head', array($this, 'add_open_graph_tags'), 2);
        
        // Add Twitter Card tags
        add_action('wp_head', array($this, 'add_twitter_card_tags'), 3);
        
        // Add schema markup
        add_action('wp_head', array($this, 'add_schema_markup'), 4);
        
        // Add canonical URL
        add_action('wp_head', array($this, 'add_canonical_url'), 5);
        
        // Add robots meta
        add_action('wp_head', array($this, 'add_robots_meta'), 6);
        
        // Add hreflang tags
        add_action('wp_head', array($this, 'add_hreflang_tags'), 7);
        
        // Add favicon
        add_action('wp_head', array($this, 'add_favicon'), 8);
        
        // Add mobile meta tags
        add_action('wp_head', array($this, 'add_mobile_meta_tags'), 9);
        
        // Add preconnect and dns-prefetch
        add_action('wp_head', array($this, 'add_resource_hints'), 0);
        
        // Remove WordPress version
        add_filter('the_generator', '__return_empty_string');
        
        // Clean up head
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
        
        // Add SEO title
        add_filter('wp_title', array($this, 'custom_wp_title'), 10, 2);
        add_filter('document_title_parts', array($this, 'custom_document_title_parts'));
        
        // Add SEO description
        add_filter('the_excerpt_rss', array($this, 'custom_excerpt_rss'));
        
        // Add SEO keywords
        add_action('wp_head', array($this, 'add_keywords_meta'), 1);
        
        // Add SEO author
        add_action('wp_head', array($this, 'add_author_meta'), 1);
        
        // Add SEO publisher
        add_action('wp_head', array($this, 'add_publisher_meta'), 1);
        
        // Add SEO image
        add_action('wp_head', array($this, 'add_image_meta'), 1);
        
        // Add SEO date
        add_action('wp_head', array($this, 'add_date_meta'), 1);
        
        // Add SEO site name
        add_action('wp_head', array($this, 'add_site_name_meta'), 1);
        
        // Add SEO URL
        add_action('wp_head', array($this, 'add_url_meta'), 1);
        
        // Add SEO type
        add_action('wp_head', array($this, 'add_type_meta'), 1);
        
        // Add SEO locale
        add_action('wp_head', array($this, 'add_locale_meta'), 1);
        
        // Add SEO site verification
        add_action('wp_head', array($this, 'add_site_verification'), 1);
        
        // Add XML sitemap support
        add_action('init', array($this, 'add_xml_sitemap'));
        
        // Add breadcrumbs support
        add_action('aqualuxe_before_content', array($this, 'add_breadcrumbs'), 20);
        
        // Add SEO settings to customizer
        add_action('customize_register', array($this, 'add_seo_customizer_settings'));
        
        // Add SEO settings to post/page editor
        add_action('add_meta_boxes', array($this, 'add_seo_meta_boxes'));
        add_action('save_post', array($this, 'save_seo_meta_boxes'));
        
        // Add SEO settings to category/tag editor
        add_action('category_add_form_fields', array($this, 'add_category_seo_fields'));
        add_action('category_edit_form_fields', array($this, 'edit_category_seo_fields'));
        add_action('created_category', array($this, 'save_category_seo_fields'));
        add_action('edited_category', array($this, 'save_category_seo_fields'));
        
        add_action('tag_add_form_fields', array($this, 'add_tag_seo_fields'));
        add_action('tag_edit_form_fields', array($this, 'edit_tag_seo_fields'));
        add_action('created_post_tag', array($this, 'save_tag_seo_fields'));
        add_action('edited_post_tag', array($this, 'save_tag_seo_fields'));
        
        // Add SEO settings to WooCommerce product categories
        if (class_exists('WooCommerce')) {
            add_action('product_cat_add_form_fields', array($this, 'add_product_cat_seo_fields'));
            add_action('product_cat_edit_form_fields', array($this, 'edit_product_cat_seo_fields'));
            add_action('created_product_cat', array($this, 'save_product_cat_seo_fields'));
            add_action('edited_product_cat', array($this, 'save_product_cat_seo_fields'));
        }
    }

    /**
     * Add meta tags
     */
    public function add_meta_tags() {
        // Add meta description
        if (is_singular()) {
            $meta_description = get_post_meta(get_the_ID(), '_aqualuxe_meta_description', true);
            
            if (empty($meta_description)) {
                $meta_description = get_the_excerpt();
                if (empty($meta_description)) {
                    $meta_description = wp_trim_words(get_the_content(), 30, '...');
                }
            }
            
            if (!empty($meta_description)) {
                echo '<meta name="description" content="' . esc_attr($meta_description) . '" />' . "\
";
            }
        } elseif (is_home() || is_front_page()) {
            $meta_description = get_theme_mod('aqualuxe_seo_home_description', get_bloginfo('description'));
            if (!empty($meta_description)) {
                echo '<meta name="description" content="' . esc_attr($meta_description) . '" />' . "\
";
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            $term_id = get_queried_object_id();
            $meta_description = get_term_meta($term_id, '_aqualuxe_meta_description', true);
            
            if (empty($meta_description)) {
                $meta_description = term_description();
            }
            
            if (!empty($meta_description)) {
                echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($meta_description)) . '" />' . "\
";
            }
        }
    }

    /**
     * Add Open Graph tags
     */
    public function add_open_graph_tags() {
        // Basic Open Graph tags
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '" />' . "\
";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\
";
        
        if (is_singular()) {
            echo '<meta property="og:type" content="article" />' . "\
";
            
            $og_title = get_post_meta(get_the_ID(), '_aqualuxe_og_title', true);
            if (empty($og_title)) {
                $og_title = get_the_title();
            }
            echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\
";
            
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\
";
            
            // Description
            $og_description = get_post_meta(get_the_ID(), '_aqualuxe_og_description', true);
            if (empty($og_description)) {
                $og_description = get_post_meta(get_the_ID(), '_aqualuxe_meta_description', true);
                if (empty($og_description)) {
                    $og_description = get_the_excerpt();
                    if (empty($og_description)) {
                        $og_description = wp_trim_words(get_the_content(), 30, '...');
                    }
                }
            }
            
            if (!empty($og_description)) {
                echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\
";
            }
            
            // Image
            $og_image = get_post_meta(get_the_ID(), '_aqualuxe_og_image', true);
            if (!empty($og_image)) {
                $image = wp_get_attachment_image_src($og_image, 'large');
                if ($image) {
                    echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />' . "\
";
                    echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '" />' . "\
";
                    echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '" />' . "\
";
                }
            } elseif (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />' . "\
";
                    echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '" />' . "\
";
                    echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '" />' . "\
";
                }
            }
            
            // Article tags
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '" />' . "\
";
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '" />' . "\
";
            
            // Author
            echo '<meta property="article:author" content="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" />' . "\
";
            
            // Categories and tags
            $categories = get_the_category();
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    echo '<meta property="article:section" content="' . esc_attr($category->name) . '" />' . "\
";
                }
            }
            
            $tags = get_the_tags();
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '" />' . "\
";
                }
            }
        } elseif (is_home() || is_front_page()) {
            echo '<meta property="og:type" content="website" />' . "\
";
            
            $og_title = get_theme_mod('aqualuxe_seo_og_home_title', get_bloginfo('name'));
            echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\
";
            
            echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '" />' . "\
";
            
            $og_description = get_theme_mod('aqualuxe_seo_og_home_description', get_bloginfo('description'));
            if (!empty($og_description)) {
                echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\
";
            }
            
            // Site logo
            $og_image = get_theme_mod('aqualuxe_seo_og_home_image');
            if (!empty($og_image)) {
                $image = wp_get_attachment_image_src($og_image, 'large');
                if ($image) {
                    echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />' . "\
";
                    echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '" />' . "\
";
                    echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '" />' . "\
";
                }
            } else {
                $custom_logo_id = get_theme_mod('custom_logo');
                if ($custom_logo_id) {
                    $image = wp_get_attachment_image_src($custom_logo_id, 'full');
                    if ($image) {
                        echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />' . "\
";
                        echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '" />' . "\
";
                        echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '" />' . "\
";
                    }
                }
            }
        } elseif (is_archive()) {
            echo '<meta property="og:type" content="website" />' . "\
";
            
            if (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                $term_id = $term->term_id;
                
                $og_title = get_term_meta($term_id, '_aqualuxe_og_title', true);
                if (empty($og_title)) {
                    $og_title = $term->name;
                }
                echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\
";
                
                echo '<meta property="og:url" content="' . esc_url(get_term_link($term)) . '" />' . "\
";
                
                $og_description = get_term_meta($term_id, '_aqualuxe_og_description', true);
                if (empty($og_description)) {
                    $og_description = get_term_meta($term_id, '_aqualuxe_meta_description', true);
                    if (empty($og_description)) {
                        $og_description = term_description();
                    }
                }
                
                if (!empty($og_description)) {
                    echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($og_description)) . '" />' . "\
";
                }
                
                // Image
                $og_image = get_term_meta($term_id, '_aqualuxe_og_image', true);
                if (!empty($og_image)) {
                    $image = wp_get_attachment_image_src($og_image, 'large');
                    if ($image) {
                        echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />' . "\
";
                        echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '" />' . "\
";
                        echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '" />' . "\
";
                    }
                }
            } else {
                echo '<meta property="og:title" content="' . esc_attr(get_the_archive_title()) . '" />' . "\
";
                echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\
";
            }
        }
    }

    /**
     * Add Twitter Card tags
     */
    public function add_twitter_card_tags() {
        // Basic Twitter Card tags
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\
";
        
        $twitter_username = get_theme_mod('aqualuxe_seo_twitter_username');
        if (!empty($twitter_username)) {
            echo '<meta name="twitter:site" content="@' . esc_attr(str_replace('@', '', $twitter_username)) . '" />' . "\
";
        }
        
        if (is_singular()) {
            $twitter_title = get_post_meta(get_the_ID(), '_aqualuxe_twitter_title', true);
            if (empty($twitter_title)) {
                $twitter_title = get_post_meta(get_the_ID(), '_aqualuxe_og_title', true);
                if (empty($twitter_title)) {
                    $twitter_title = get_the_title();
                }
            }
            echo '<meta name="twitter:title" content="' . esc_attr($twitter_title) . '" />' . "\
";
            
            // Description
            $twitter_description = get_post_meta(get_the_ID(), '_aqualuxe_twitter_description', true);
            if (empty($twitter_description)) {
                $twitter_description = get_post_meta(get_the_ID(), '_aqualuxe_og_description', true);
                if (empty($twitter_description)) {
                    $twitter_description = get_post_meta(get_the_ID(), '_aqualuxe_meta_description', true);
                    if (empty($twitter_description)) {
                        $twitter_description = get_the_excerpt();
                        if (empty($twitter_description)) {
                            $twitter_description = wp_trim_words(get_the_content(), 30, '...');
                        }
                    }
                }
            }
            
            if (!empty($twitter_description)) {
                echo '<meta name="twitter:description" content="' . esc_attr($twitter_description) . '" />' . "\
";
            }
            
            // Image
            $twitter_image = get_post_meta(get_the_ID(), '_aqualuxe_twitter_image', true);
            if (!empty($twitter_image)) {
                $image = wp_get_attachment_image_src($twitter_image, 'large');
                if ($image) {
                    echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '" />' . "\
";
                }
            } elseif (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '" />' . "\
";
                }
            }
            
            // Author
            $author_twitter = get_the_author_meta('twitter');
            if (!empty($author_twitter)) {
                echo '<meta name="twitter:creator" content="@' . esc_attr(str_replace('@', '', $author_twitter)) . '" />' . "\
";
            }
        } elseif (is_home() || is_front_page()) {
            $twitter_title = get_theme_mod('aqualuxe_seo_twitter_home_title', get_bloginfo('name'));
            echo '<meta name="twitter:title" content="' . esc_attr($twitter_title) . '" />' . "\
";
            
            $twitter_description = get_theme_mod('aqualuxe_seo_twitter_home_description', get_bloginfo('description'));
            if (!empty($twitter_description)) {
                echo '<meta name="twitter:description" content="' . esc_attr($twitter_description) . '" />' . "\
";
            }
            
            // Site logo
            $twitter_image = get_theme_mod('aqualuxe_seo_twitter_home_image');
            if (!empty($twitter_image)) {
                $image = wp_get_attachment_image_src($twitter_image, 'large');
                if ($image) {
                    echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '" />' . "\
";
                }
            } else {
                $custom_logo_id = get_theme_mod('custom_logo');
                if ($custom_logo_id) {
                    $image = wp_get_attachment_image_src($custom_logo_id, 'full');
                    if ($image) {
                        echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '" />' . "\
";
                    }
                }
            }
        } elseif (is_archive()) {
            if (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                $term_id = $term->term_id;
                
                $twitter_title = get_term_meta($term_id, '_aqualuxe_twitter_title', true);
                if (empty($twitter_title)) {
                    $twitter_title = get_term_meta($term_id, '_aqualuxe_og_title', true);
                    if (empty($twitter_title)) {
                        $twitter_title = $term->name;
                    }
                }
                echo '<meta name="twitter:title" content="' . esc_attr($twitter_title) . '" />' . "\
";
                
                $twitter_description = get_term_meta($term_id, '_aqualuxe_twitter_description', true);
                if (empty($twitter_description)) {
                    $twitter_description = get_term_meta($term_id, '_aqualuxe_og_description', true);
                    if (empty($twitter_description)) {
                        $twitter_description = get_term_meta($term_id, '_aqualuxe_meta_description', true);
                        if (empty($twitter_description)) {
                            $twitter_description = term_description();
                        }
                    }
                }
                
                if (!empty($twitter_description)) {
                    echo '<meta name="twitter:description" content="' . esc_attr(wp_strip_all_tags($twitter_description)) . '" />' . "\
";
                }
                
                // Image
                $twitter_image = get_term_meta($term_id, '_aqualuxe_twitter_image', true);
                if (!empty($twitter_image)) {
                    $image = wp_get_attachment_image_src($twitter_image, 'large');
                    if ($image) {
                        echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '" />' . "\
";
                    }
                }
            } else {
                echo '<meta name="twitter:title" content="' . esc_attr(get_the_archive_title()) . '" />' . "\
";
            }
        }
    }

    /**
     * Add schema markup
     */
    public function add_schema_markup() {
        // Website schema
        echo '<script type="application/ld+json">' . "\
";
        echo '{' . "\
";
        echo '  "@context": "https://schema.org",' . "\
";
        echo '  "@type": "WebSite",' . "\
";
        echo '  "name": "' . esc_js(get_bloginfo('name')) . '",' . "\
";
        echo '  "url": "' . esc_url(home_url('/')) . '",' . "\
";
        echo '  "potentialAction": {' . "\
";
        echo '    "@type": "SearchAction",' . "\
";
        echo '    "target": "' . esc_url(home_url('/')) . '?s={search_term_string}",' . "\
";
        echo '    "query-input": "required name=search_term_string"' . "\
";
        echo '  }' . "\
";
        echo '}' . "\
";
        echo '</script>' . "\
";
        
        // Organization schema
        echo '<script type="application/ld+json">' . "\
";
        echo '{' . "\
";
        echo '  "@context": "https://schema.org",' . "\
";
        echo '  "@type": "Organization",' . "\
";
        echo '  "name": "' . esc_js(get_bloginfo('name')) . '",' . "\
";
        echo '  "url": "' . esc_url(home_url('/')) . '",' . "\
";
        
        // Logo
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $image = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($image) {
                echo '  "logo": "' . esc_url($image[0]) . '",' . "\
";
            }
        }
        
        // Social profiles
        echo '  "sameAs": [' . "\
";
        
        $social_profiles = array();
        
        $facebook = get_theme_mod('aqualuxe_facebook_url', '');
        if (!empty($facebook)) {
            $social_profiles[] = $facebook;
        }
        
        $twitter = get_theme_mod('aqualuxe_twitter_url', '');
        if (!empty($twitter)) {
            $social_profiles[] = $twitter;
        }
        
        $instagram = get_theme_mod('aqualuxe_instagram_url', '');
        if (!empty($instagram)) {
            $social_profiles[] = $instagram;
        }
        
        $youtube = get_theme_mod('aqualuxe_youtube_url', '');
        if (!empty($youtube)) {
            $social_profiles[] = $youtube;
        }
        
        if (!empty($social_profiles)) {
            echo '    "' . implode('",
    "', array_map('esc_url', $social_profiles)) . '"' . "\
";
        }
        
        echo '  ],' . "\
";
        
        // Contact information
        echo '  "contactPoint": {' . "\
";
        echo '    "@type": "ContactPoint",' . "\
";
        echo '    "telephone": "' . esc_js(get_theme_mod('aqualuxe_phone', '')) . '",' . "\
";
        echo '    "contactType": "customer service"' . "\
";
        echo '  }' . "\
";
        
        echo '}' . "\
";
        echo '</script>' . "\
";
        
        // Article schema for single posts
        if (is_singular('post')) {
            echo '<script type="application/ld+json">' . "\
";
            echo '{' . "\
";
            echo '  "@context": "https://schema.org",' . "\
";
            echo '  "@type": "Article",' . "\
";
            echo '  "mainEntityOfPage": {' . "\
";
            echo '    "@type": "WebPage",' . "\
";
            echo '    "@id": "' . esc_url(get_permalink()) . '"' . "\
";
            echo '  },' . "\
";
            echo '  "headline": "' . esc_js(get_the_title()) . '",' . "\
";
            
            // Featured image
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                if ($image) {
                    echo '  "image": "' . esc_url($image[0]) . '",' . "\
";
                }
            }
            
            echo '  "datePublished": "' . esc_attr(get_the_date('c')) . '",' . "\
";
            echo '  "dateModified": "' . esc_attr(get_the_modified_date('c')) . '",' . "\
";
            
            // Author
            echo '  "author": {' . "\
";
            echo '    "@type": "Person",' . "\
";
            echo '    "name": "' . esc_js(get_the_author()) . '"' . "\
";
            echo '  },' . "\
";
            
            // Publisher
            echo '  "publisher": {' . "\
";
            echo '    "@type": "Organization",' . "\
";
            echo '    "name": "' . esc_js(get_bloginfo('name')) . '",' . "\
";
            
            // Logo
            if ($custom_logo_id) {
                $image = wp_get_attachment_image_src($custom_logo_id, 'full');
                if ($image) {
                    echo '    "logo": {' . "\
";
                    echo '      "@type": "ImageObject",' . "\
";
                    echo '      "url": "' . esc_url($image[0]) . '",' . "\
";
                    echo '      "width": "' . esc_attr($image[1]) . '",' . "\
";
                    echo '      "height": "' . esc_attr($image[2]) . '"' . "\
";
                    echo '    }' . "\
";
                }
            }
            
            echo '  }' . "\
";
            echo '}' . "\
";
            echo '</script>' . "\
";
        }
        
        // Product schema for WooCommerce products
        if (class_exists('WooCommerce') && is_product()) {
            global $product;
            
            echo '<script type="application/ld+json">' . "\
";
            echo '{' . "\
";
            echo '  "@context": "https://schema.org",' . "\
";
            echo '  "@type": "Product",' . "\
";
            echo '  "name": "' . esc_js(get_the_title()) . '",' . "\
";
            
            // Description
            $description = $product->get_short_description();
            if (empty($description)) {
                $description = $product->get_description();
            }
            
            if (!empty($description)) {
                echo '  "description": "' . esc_js(wp_strip_all_tags($description)) . '",' . "\
";
            }
            
            // SKU
            $sku = $product->get_sku();
            if (!empty($sku)) {
                echo '  "sku": "' . esc_js($sku) . '",' . "\
";
            }
            
            // MPN
            $mpn = get_post_meta($product->get_id(), '_mpn', true);
            if (!empty($mpn)) {
                echo '  "mpn": "' . esc_js($mpn) . '",' . "\
";
            }
            
            // Brand
            $brands = get_the_terms($product->get_id(), 'product_brand');
            if ($brands && !is_wp_error($brands)) {
                $brand = reset($brands);
                echo '  "brand": {' . "\
";
                echo '    "@type": "Brand",' . "\
";
                echo '    "name": "' . esc_js($brand->name) . '"' . "\
";
                echo '  },' . "\
";
            }
            
            // Image
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                if ($image) {
                    echo '  "image": "' . esc_url($image[0]) . '",' . "\
";
                }
            }
            
            // Offers
            echo '  "offers": {' . "\
";
            echo '    "@type": "Offer",' . "\
";
            echo '    "url": "' . esc_url(get_permalink()) . '",' . "\
";
            echo '    "priceCurrency": "' . esc_js(get_woocommerce_currency()) . '",' . "\
";
            echo '    "price": "' . esc_js($product->get_price()) . '",' . "\
";
            echo '    "priceValidUntil": "' . esc_js(date('Y-m-d', strtotime('+1 year'))) . '",' . "\
";
            echo '    "availability": "' . esc_js($product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock') . '"' . "\
";
            echo '  },' . "\
";
            
            // Rating
            if ($product->get_rating_count() > 0) {
                echo '  "aggregateRating": {' . "\
";
                echo '    "@type": "AggregateRating",' . "\
";
                echo '    "ratingValue": "' . esc_js($product->get_average_rating()) . '",' . "\
";
                echo '    "reviewCount": "' . esc_js($product->get_review_count()) . '"' . "\
";
                echo '  }' . "\
";
            }
            
            echo '}' . "\
";
            echo '</script>' . "\
";
        }
    }

    /**
     * Add canonical URL
     */
    public function add_canonical_url() {
        if (is_singular()) {
            $canonical_url = get_post_meta(get_the_ID(), '_aqualuxe_canonical_url', true);
            
            if (empty($canonical_url)) {
                $canonical_url = get_permalink();
            }
            
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\
";
        } elseif (is_home() || is_front_page()) {
            echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '" />' . "\
";
        } elseif (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();
            $canonical_url = get_term_meta($term->term_id, '_aqualuxe_canonical_url', true);
            
            if (empty($canonical_url)) {
                $canonical_url = get_term_link($term);
            }
            
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\
";
        } elseif (is_archive()) {
            echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '" />' . "\
";
        }
    }

    /**
     * Add robots meta
     */
    public function add_robots_meta() {
        $robots = array();
        
        if (is_singular()) {
            $robots_meta = get_post_meta(get_the_ID(), '_aqualuxe_robots_meta', true);
            
            if (!empty($robots_meta)) {
                $robots = explode(',', $robots_meta);
            } else {
                $robots = array('index', 'follow');
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();
            $robots_meta = get_term_meta($term->term_id, '_aqualuxe_robots_meta', true);
            
            if (!empty($robots_meta)) {
                $robots = explode(',', $robots_meta);
            } else {
                $robots = array('index', 'follow');
            }
        } elseif (is_search() || is_404()) {
            $robots = array('noindex', 'nofollow');
        } else {
            $robots = array('index', 'follow');
        }
        
        if (!empty($robots)) {
            echo '<meta name="robots" content="' . esc_attr(implode(', ', $robots)) . '" />' . "\
";
        }
    }

    /**
     * Add hreflang tags
     */
    public function add_hreflang_tags() {
        // Add hreflang tags if multilingual site
    }

    /**
     * Add favicon
     */
    public function add_favicon() {
        // WordPress handles favicon through site icon
    }

    /**
     * Add mobile meta tags
     */
    public function add_mobile_meta_tags() {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />' . "\
";
        echo '<meta name="mobile-web-app-capable" content="yes" />' . "\
";
        echo '<meta name="apple-mobile-web-app-capable" content="yes" />' . "\
";
        echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\
";
    }

    /**
     * Add resource hints
     */
    public function add_resource_hints() {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\
";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\
";
        echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" />' . "\
";
        
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com" />' . "\
";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com" />' . "\
";
        echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com" />' . "\
";
        echo '<link rel="dns-prefetch" href="//ajax.googleapis.com" />' . "\
";
        echo '<link rel="dns-prefetch" href="//s.w.org" />' . "\
";
    }

    /**
     * Custom wp title
     *
     * @param string $title   Page title.
     * @param string $sep     Title separator.
     * @return string Modified title.
     */
    public function custom_wp_title($title, $sep) {
        if (is_singular()) {
            $seo_title = get_post_meta(get_the_ID(), '_aqualuxe_seo_title', true);
            
            if (!empty($seo_title)) {
                return $seo_title;
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();
            $seo_title = get_term_meta($term->term_id, '_aqualuxe_seo_title', true);
            
            if (!empty($seo_title)) {
                return $seo_title;
            }
        } elseif (is_home() || is_front_page()) {
            $seo_title = get_theme_mod('aqualuxe_seo_home_title');
            
            if (!empty($seo_title)) {
                return $seo_title;
            }
        }
        
        return $title;
    }

    /**
     * Custom document title parts
     *
     * @param array $title_parts Title parts.
     * @return array Modified title parts.
     */
    public function custom_document_title_parts($title_parts) {
        if (is_singular()) {
            $seo_title = get_post_meta(get_the_ID(), '_aqualuxe_seo_title', true);
            
            if (!empty($seo_title)) {
                $title_parts['title'] = $seo_title;
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();
            $seo_title = get_term_meta($term->term_id, '_aqualuxe_seo_title', true);
            
            if (!empty($seo_title)) {
                $title_parts['title'] = $seo_title;
            }
        } elseif (is_home() || is_front_page()) {
            $seo_title = get_theme_mod('aqualuxe_seo_home_title');
            
            if (!empty($seo_title)) {
                $title_parts['title'] = $seo_title;
            }
        }
        
        return $title_parts;
    }

    /**
     * Custom excerpt RSS
     *
     * @param string $excerpt Excerpt.
     * @return string Modified excerpt.
     */
    public function custom_excerpt_rss($excerpt) {
        if (is_singular()) {
            $meta_description = get_post_meta(get_the_ID(), '_aqualuxe_meta_description', true);
            
            if (!empty($meta_description)) {
                return $meta_description;
            }
        }
        
        return $excerpt;
    }

    /**
     * Add keywords meta
     */
    public function add_keywords_meta() {
        if (is_singular()) {
            $keywords = get_post_meta(get_the_ID(), '_aqualuxe_keywords', true);
            
            if (!empty($keywords)) {
                echo '<meta name="keywords" content="' . esc_attr($keywords) . '" />' . "\
";
            } else {
                $keywords = array();
                
                // Get categories
                $categories = get_the_category();
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $keywords[] = $category->name;
                    }
                }
                
                // Get tags
                $tags = get_the_tags();
                if (!empty($tags)) {
                    foreach ($tags as $tag) {
                        $keywords[] = $tag->name;
                    }
                }
                
                if (!empty($keywords)) {
                    echo '<meta name="keywords" content="' . esc_attr(implode(', ', $keywords)) . '" />' . "\
";
                }
            }
        } elseif (is_home() || is_front_page()) {
            $keywords = get_theme_mod('aqualuxe_seo_home_keywords');
            
            if (!empty($keywords)) {
                echo '<meta name="keywords" content="' . esc_attr($keywords) . '" />' . "\
";
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();
            $keywords = get_term_meta($term->term_id, '_aqualuxe_keywords', true);
            
            if (!empty($keywords)) {
                echo '<meta name="keywords" content="' . esc_attr($keywords) . '" />' . "\
";
            }
        }
    }

    /**
     * Add author meta
     */
    public function add_author_meta() {
        if (is_singular()) {
            echo '<meta name="author" content="' . esc_attr(get_the_author()) . '" />' . "\
";
        } else {
            echo '<meta name="author" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\
";
        }
    }

    /**
     * Add publisher meta
     */
    public function add_publisher_meta() {
        echo '<meta name="publisher" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\
";
    }

    /**
     * Add image meta
     */
    public function add_image_meta() {
        if (is_singular() && has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            
            if ($image) {
                echo '<meta name="image" content="' . esc_url($image[0]) . '" />' . "\
";
            }
        } elseif (is_home() || is_front_page()) {
            $custom_logo_id = get_theme_mod('custom_logo');
            
            if ($custom_logo_id) {
                $image = wp_get_attachment_image_src($custom_logo_id, 'full');
                
                if ($image) {
                    echo '<meta name="image" content="' . esc_url($image[0]) . '" />' . "\
";
                }
            }
        }
    }

    /**
     * Add date meta
     */
    public function add_date_meta() {
        if (is_singular()) {
            echo '<meta name="date" content="' . esc_attr(get_the_date('c')) . '" />' . "\
";
        }
    }

    /**
     * Add site name meta
     */
    public function add_site_name_meta() {
        echo '<meta name="application-name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\
";
    }

    /**
     * Add URL meta
     */
    public function add_url_meta() {
        if (is_singular()) {
            echo '<meta name="url" content="' . esc_url(get_permalink()) . '" />' . "\
";
        } elseif (is_home() || is_front_page()) {
            echo '<meta name="url" content="' . esc_url(home_url('/')) . '" />' . "\
";
        }
    }

    /**
     * Add type meta
     */
    public function add_type_meta() {
        if (is_singular('post')) {
            echo '<meta name="type" content="article" />' . "\
";
        } elseif (is_singular('page')) {
            echo '<meta name="type" content="website" />' . "\
";
        } elseif (class_exists('WooCommerce') && is_product()) {
            echo '<meta name="type" content="product" />' . "\
";
        } else {
            echo '<meta name="type" content="website" />' . "\
";
        }
    }

    /**
     * Add locale meta
     */
    public function add_locale_meta() {
        echo '<meta name="locale" content="' . esc_attr(get_locale()) . '" />' . "\
";
    }

    /**
     * Add site verification
     */
    public function add_site_verification() {
        $google_verification = get_theme_mod('aqualuxe_seo_google_verification');
        if (!empty($google_verification)) {
            echo '<meta name="google-site-verification" content="' . esc_attr($google_verification) . '" />' . "\
";
        }
        
        $bing_verification = get_theme_mod('aqualuxe_seo_bing_verification');
        if (!empty($bing_verification)) {
            echo '<meta name="msvalidate.01" content="' . esc_attr($bing_verification) . '" />' . "\
";
        }
        
        $pinterest_verification = get_theme_mod('aqualuxe_seo_pinterest_verification');
        if (!empty($pinterest_verification)) {
            echo '<meta name="p:domain_verify" content="' . esc_attr($pinterest_verification) . '" />' . "\
";
        }
        
        $yandex_verification = get_theme_mod('aqualuxe_seo_yandex_verification');
        if (!empty($yandex_verification)) {
            echo '<meta name="yandex-verification" content="' . esc_attr($yandex_verification) . '" />' . "\
";
        }
    }

    /**
     * Add XML sitemap
     */
    public function add_xml_sitemap() {
        // WordPress handles XML sitemap since version 5.5
    }

    /**
     * Add breadcrumbs
     */
    public function add_breadcrumbs() {
        if (is_front_page()) {
            return;
        }
        
        // Use WooCommerce breadcrumbs if available
        if (class_exists('WooCommerce') && function_exists('woocommerce_breadcrumb') && (is_woocommerce() || is_cart() || is_checkout())) {
            woocommerce_breadcrumb();
            return;
        }
        
        // Custom breadcrumbs
        ?>
        <div class="aqualuxe-breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <nav aria-label="<?php esc_attr_e('Breadcrumbs', 'aqualuxe'); ?>">
                            <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" itemprop="item">
                                        <i class="fas fa-home"></i>
                                        <span itemprop="name"><?php esc_html_e('Home', 'aqualuxe'); ?></span>
                                    </a>
                                    <meta itemprop="position" content="1" />
                                </li>
                                
                                <?php
                                if (is_home()) {
                                    // Blog page
                                    echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<span itemprop="name">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</span>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                } elseif (is_category()) {
                                    // Category archive
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                    
                                    echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<span itemprop="name">' . esc_html(single_cat_title('', false)) . '</span>';
                                    echo '<meta itemprop="position" content="3" />';
                                    echo '</li>';
                                } elseif (is_tag()) {
                                    // Tag archive
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                    
                                    echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<span itemprop="name">' . esc_html(single_tag_title('', false)) . '</span>';
                                    echo '<meta itemprop="position" content="3" />';
                                    echo '</li>';
                                } elseif (is_author()) {
                                    // Author archive
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                    
                                    echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<span itemprop="name">' . esc_html(get_the_author()) . '</span>';
                                    echo '<meta itemprop="position" content="3" />';
                                    echo '</li>';
                                } elseif (is_year()) {
                                    // Year archive
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                    
                                    echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<span itemprop="name">' . esc_html(get_the_date('Y')) . '</span>';
                                    echo '<meta itemprop="position" content="3" />';
                                    echo '</li>';
                                } elseif (is_month()) {
                                    // Month archive
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                    
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_year_link(get_the_date('Y'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_date('Y')) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="3" />';
                                    echo '</li>';
                                    
                                    echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<span itemprop="name">' . esc_html(get_the_date('F')) . '</span>';
                                    echo '<meta itemprop="position" content="4" />';
                                    echo '</li>';
                                } elseif (is_day()) {
                                    // Day archive
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                    
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_year_link(get_the_date('Y'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_date('Y')) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="3" />';
                                    echo '</li>';
                                    
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_month_link(get_the_date('Y'), get_the_date('m'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_date('F')) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="4" />';
                                    echo '</li>';
                                    
                                    echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<span itemprop="name">' . esc_html(get_the_date('j')) . '</span>';
                                    echo '<meta itemprop="position" content="5" />';
                                    echo '</li>';
                                } elseif (is_search()) {
                                    // Search results
                                    echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<span itemprop="name">' . esc_html__('Search Results', 'aqualuxe') . '</span>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                } elseif (is_404()) {
                                    // 404 page
                                    echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<span itemprop="name">' . esc_html__('Page Not Found', 'aqualuxe') . '</span>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                } elseif (is_page()) {
                                    // Page
                                    $ancestors = get_post_ancestors(get_the_ID());
                                    if ($ancestors) {
                                        $ancestors = array_reverse($ancestors);
                                        $position = 2;
                                        
                                        foreach ($ancestors as $ancestor) {
                                            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                            echo '<a href="' . esc_url(get_permalink($ancestor)) . '" itemprop="item">';
                                            echo '<span itemprop="name">' . esc_html(get_the_title($ancestor)) . '</span>';
                                            echo '</a>';
                                            echo '<meta itemprop="position" content="' . esc_attr($position) . '" />';
                                            echo '</li>';
                                            
                                            $position++;
                                        }
                                        
                                        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                        echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
                                        echo '<meta itemprop="position" content="' . esc_attr($position) . '" />';
                                        echo '</li>';
                                    } else {
                                        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                        echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
                                        echo '<meta itemprop="position" content="2" />';
                                        echo '</li>';
                                    }
                                } elseif (is_singular('post')) {
                                    // Single post
                                    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                    echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" itemprop="item">';
                                    echo '<span itemprop="name">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</span>';
                                    echo '</a>';
                                    echo '<meta itemprop="position" content="2" />';
                                    echo '</li>';
                                    
                                    $categories = get_the_category();
                                    if ($categories) {
                                        $category = $categories[0];
                                        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" itemprop="item">';
                                        echo '<span itemprop="name">' . esc_html($category->name) . '</span>';
                                        echo '</a>';
                                        echo '<meta itemprop="position" content="3" />';
                                        echo '</li>';
                                        
                                        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                        echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
                                        echo '<meta itemprop="position" content="4" />';
                                        echo '</li>';
                                    } else {
                                        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                                        echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
                                        echo '<meta itemprop="position" content="3" />';
                                        echo '</li>';
                                    }
                                }
                                ?>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Add SEO customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function add_seo_customizer_settings($wp_customize) {
        // Add SEO section
        $wp_customize->add_section('aqualuxe_seo_section', array(
            'title'    => esc_html__('AquaLuxe SEO Settings', 'aqualuxe'),
            'priority' => 120,
        ));
        
        // Home page SEO title
        $wp_customize->add_setting('aqualuxe_seo_home_title', array(
            'default'           => get_bloginfo('name'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_home_title', array(
            'label'    => esc_html__('Home Page SEO Title', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'text',
        ));
        
        // Home page meta description
        $wp_customize->add_setting('aqualuxe_seo_home_description', array(
            'default'           => get_bloginfo('description'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_home_description', array(
            'label'    => esc_html__('Home Page Meta Description', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'textarea',
        ));
        
        // Home page keywords
        $wp_customize->add_setting('aqualuxe_seo_home_keywords', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_home_keywords', array(
            'label'    => esc_html__('Home Page Keywords', 'aqualuxe'),
            'description' => esc_html__('Comma separated list of keywords', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'text',
        ));
        
        // Open Graph home title
        $wp_customize->add_setting('aqualuxe_seo_og_home_title', array(
            'default'           => get_bloginfo('name'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_og_home_title', array(
            'label'    => esc_html__('Home Page Open Graph Title', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'text',
        ));
        
        // Open Graph home description
        $wp_customize->add_setting('aqualuxe_seo_og_home_description', array(
            'default'           => get_bloginfo('description'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_og_home_description', array(
            'label'    => esc_html__('Home Page Open Graph Description', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'textarea',
        ));
        
        // Open Graph home image
        $wp_customize->add_setting('aqualuxe_seo_og_home_image', array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_seo_og_home_image', array(
            'label'    => esc_html__('Home Page Open Graph Image', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'mime_type' => 'image',
        )));
        
        // Twitter home title
        $wp_customize->add_setting('aqualuxe_seo_twitter_home_title', array(
            'default'           => get_bloginfo('name'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_twitter_home_title', array(
            'label'    => esc_html__('Home Page Twitter Card Title', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'text',
        ));
        
        // Twitter home description
        $wp_customize->add_setting('aqualuxe_seo_twitter_home_description', array(
            'default'           => get_bloginfo('description'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_twitter_home_description', array(
            'label'    => esc_html__('Home Page Twitter Card Description', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'textarea',
        ));
        
        // Twitter home image
        $wp_customize->add_setting('aqualuxe_seo_twitter_home_image', array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_seo_twitter_home_image', array(
            'label'    => esc_html__('Home Page Twitter Card Image', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'mime_type' => 'image',
        )));
        
        // Twitter username
        $wp_customize->add_setting('aqualuxe_seo_twitter_username', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_twitter_username', array(
            'label'    => esc_html__('Twitter Username', 'aqualuxe'),
            'description' => esc_html__('Without @ symbol', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'text',
        ));
        
        // Google site verification
        $wp_customize->add_setting('aqualuxe_seo_google_verification', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_google_verification', array(
            'label'    => esc_html__('Google Site Verification', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'text',
        ));
        
        // Bing site verification
        $wp_customize->add_setting('aqualuxe_seo_bing_verification', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_bing_verification', array(
            'label'    => esc_html__('Bing Site Verification', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'text',
        ));
        
        // Pinterest site verification
        $wp_customize->add_setting('aqualuxe_seo_pinterest_verification', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_pinterest_verification', array(
            'label'    => esc_html__('Pinterest Site Verification', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'text',
        ));
        
        // Yandex site verification
        $wp_customize->add_setting('aqualuxe_seo_yandex_verification', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_seo_yandex_verification', array(
            'label'    => esc_html__('Yandex Site Verification', 'aqualuxe'),
            'section'  => 'aqualuxe_seo_section',
            'type'     => 'text',
        ));
    }

    /**
     * Add SEO meta boxes
     */
    public function add_seo_meta_boxes() {
        $post_types = array('post', 'page');
        
        if (class_exists('WooCommerce')) {
            $post_types[] = 'product';
        }
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'aqualuxe_seo_meta_box',
                esc_html__('AquaLuxe SEO Settings', 'aqualuxe'),
                array($this, 'render_seo_meta_box'),
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * Render SEO meta box
     *
     * @param WP_Post $post Post object.
     */
    public function render_seo_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_seo_meta_box', 'aqualuxe_seo_meta_box_nonce');
        
        // Get saved values
        $seo_title = get_post_meta($post->ID, '_aqualuxe_seo_title', true);
        $meta_description = get_post_meta($post->ID, '_aqualuxe_meta_description', true);
        $keywords = get_post_meta($post->ID, '_aqualuxe_keywords', true);
        $canonical_url = get_post_meta($post->ID, '_aqualuxe_canonical_url', true);
        $robots_meta = get_post_meta($post->ID, '_aqualuxe_robots_meta', true);
        $og_title = get_post_meta($post->ID, '_aqualuxe_og_title', true);
        $og_description = get_post_meta($post->ID, '_aqualuxe_og_description', true);
        $og_image = get_post_meta($post->ID, '_aqualuxe_og_image', true);
        $twitter_title = get_post_meta($post->ID, '_aqualuxe_twitter_title', true);
        $twitter_description = get_post_meta($post->ID, '_aqualuxe_twitter_description', true);
        $twitter_image = get_post_meta($post->ID, '_aqualuxe_twitter_image', true);
        
        // Default robots meta
        if (empty($robots_meta)) {
            $robots_meta = 'index,follow';
        }
        
        ?>
        <div class="aqualuxe-seo-meta-box">
            <p>
                <label for="aqualuxe_seo_title"><?php esc_html_e('SEO Title', 'aqualuxe'); ?></label>
                <input type="text" id="aqualuxe_seo_title" name="aqualuxe_seo_title" value="<?php echo esc_attr($seo_title); ?>" class="widefat" />
                <span class="description"><?php esc_html_e('Enter a custom SEO title. Leave blank to use the post title.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_meta_description"><?php esc_html_e('Meta Description', 'aqualuxe'); ?></label>
                <textarea id="aqualuxe_meta_description" name="aqualuxe_meta_description" rows="3" class="widefat"><?php echo esc_textarea($meta_description); ?></textarea>
                <span class="description"><?php esc_html_e('Enter a custom meta description. Leave blank to use the post excerpt.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_keywords"><?php esc_html_e('Keywords', 'aqualuxe'); ?></label>
                <input type="text" id="aqualuxe_keywords" name="aqualuxe_keywords" value="<?php echo esc_attr($keywords); ?>" class="widefat" />
                <span class="description"><?php esc_html_e('Enter a comma-separated list of keywords.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_canonical_url"><?php esc_html_e('Canonical URL', 'aqualuxe'); ?></label>
                <input type="url" id="aqualuxe_canonical_url" name="aqualuxe_canonical_url" value="<?php echo esc_url($canonical_url); ?>" class="widefat" />
                <span class="description"><?php esc_html_e('Enter a custom canonical URL. Leave blank to use the post permalink.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_robots_meta"><?php esc_html_e('Robots Meta', 'aqualuxe'); ?></label>
                <select id="aqualuxe_robots_meta" name="aqualuxe_robots_meta" class="widefat">
                    <option value="index,follow" <?php selected($robots_meta, 'index,follow'); ?>><?php esc_html_e('Index, Follow', 'aqualuxe'); ?></option>
                    <option value="index,nofollow" <?php selected($robots_meta, 'index,nofollow'); ?>><?php esc_html_e('Index, No Follow', 'aqualuxe'); ?></option>
                    <option value="noindex,follow" <?php selected($robots_meta, 'noindex,follow'); ?>><?php esc_html_e('No Index, Follow', 'aqualuxe'); ?></option>
                    <option value="noindex,nofollow" <?php selected($robots_meta, 'noindex,nofollow'); ?>><?php esc_html_e('No Index, No Follow', 'aqualuxe'); ?></option>
                </select>
                <span class="description"><?php esc_html_e('Select robots meta directives.', 'aqualuxe'); ?></span>
            </p>
            
            <h4><?php esc_html_e('Open Graph Settings', 'aqualuxe'); ?></h4>
            
            <p>
                <label for="aqualuxe_og_title"><?php esc_html_e('Open Graph Title', 'aqualuxe'); ?></label>
                <input type="text" id="aqualuxe_og_title" name="aqualuxe_og_title" value="<?php echo esc_attr($og_title); ?>" class="widefat" />
                <span class="description"><?php esc_html_e('Enter a custom Open Graph title. Leave blank to use the SEO title or post title.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_og_description"><?php esc_html_e('Open Graph Description', 'aqualuxe'); ?></label>
                <textarea id="aqualuxe_og_description" name="aqualuxe_og_description" rows="3" class="widefat"><?php echo esc_textarea($og_description); ?></textarea>
                <span class="description"><?php esc_html_e('Enter a custom Open Graph description. Leave blank to use the meta description.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_og_image"><?php esc_html_e('Open Graph Image', 'aqualuxe'); ?></label>
                <div class="aqualuxe-media-upload">
                    <input type="hidden" id="aqualuxe_og_image" name="aqualuxe_og_image" value="<?php echo esc_attr($og_image); ?>" />
                    <div class="aqualuxe-media-preview">
                        <?php if (!empty($og_image)) : ?>
                            <?php echo wp_get_attachment_image($og_image, 'medium'); ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button aqualuxe-media-upload-button" data-target="aqualuxe_og_image"><?php esc_html_e('Select Image', 'aqualuxe'); ?></button>
                    <button type="button" class="button aqualuxe-media-remove-button" data-target="aqualuxe_og_image"><?php esc_html_e('Remove Image', 'aqualuxe'); ?></button>
                </div>
                <span class="description"><?php esc_html_e('Select an image for Open Graph. Leave blank to use the featured image.', 'aqualuxe'); ?></span>
            </p>
            
            <h4><?php esc_html_e('Twitter Card Settings', 'aqualuxe'); ?></h4>
            
            <p>
                <label for="aqualuxe_twitter_title"><?php esc_html_e('Twitter Card Title', 'aqualuxe'); ?></label>
                <input type="text" id="aqualuxe_twitter_title" name="aqualuxe_twitter_title" value="<?php echo esc_attr($twitter_title); ?>" class="widefat" />
                <span class="description"><?php esc_html_e('Enter a custom Twitter Card title. Leave blank to use the Open Graph title.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_twitter_description"><?php esc_html_e('Twitter Card Description', 'aqualuxe'); ?></label>
                <textarea id="aqualuxe_twitter_description" name="aqualuxe_twitter_description" rows="3" class="widefat"><?php echo esc_textarea($twitter_description); ?></textarea>
                <span class="description"><?php esc_html_e('Enter a custom Twitter Card description. Leave blank to use the Open Graph description.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_twitter_image"><?php esc_html_e('Twitter Card Image', 'aqualuxe'); ?></label>
                <div class="aqualuxe-media-upload">
                    <input type="hidden" id="aqualuxe_twitter_image" name="aqualuxe_twitter_image" value="<?php echo esc_attr($twitter_image); ?>" />
                    <div class="aqualuxe-media-preview">
                        <?php if (!empty($twitter_image)) : ?>
                            <?php echo wp_get_attachment_image($twitter_image, 'medium'); ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button aqualuxe-media-upload-button" data-target="aqualuxe_twitter_image"><?php esc_html_e('Select Image', 'aqualuxe'); ?></button>
                    <button type="button" class="button aqualuxe-media-remove-button" data-target="aqualuxe_twitter_image"><?php esc_html_e('Remove Image', 'aqualuxe'); ?></button>
                </div>
                <span class="description"><?php esc_html_e('Select an image for Twitter Card. Leave blank to use the Open Graph image.', 'aqualuxe'); ?></span>
            </p>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Media upload
                $('.aqualuxe-media-upload-button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var target = button.data('target');
                    
                    var frame = wp.media({
                        title: '<?php esc_html_e('Select Image', 'aqualuxe'); ?>',
                        multiple: false,
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: '<?php esc_html_e('Use this image', 'aqualuxe'); ?>'
                        }
                    });
                    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        
                        $('#' + target).val(attachment.id);
                        
                        var preview = button.siblings('.aqualuxe-media-preview');
                        preview.html('<img src="' + attachment.url + '" alt="" />');
                    });
                    
                    frame.open();
                });
                
                // Media remove
                $('.aqualuxe-media-remove-button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var target = button.data('target');
                    
                    $('#' + target).val('');
                    
                    var preview = button.siblings('.aqualuxe-media-preview');
                    preview.html('');
                });
            });
        </script>
        
        <style>
            .aqualuxe-seo-meta-box p {
                margin-bottom: 15px;
            }
            
            .aqualuxe-seo-meta-box label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }
            
            .aqualuxe-seo-meta-box .description {
                display: block;
                color: #666;
                font-style: italic;
                margin-top: 3px;
            }
            
            .aqualuxe-seo-meta-box h4 {
                margin: 20px 0 10px;
                padding-bottom: 5px;
                border-bottom: 1px solid #eee;
            }
            
            .aqualuxe-media-preview {
                margin-bottom: 10px;
            }
            
            .aqualuxe-media-preview img {
                max-width: 150px;
                height: auto;
                border: 1px solid #ddd;
                padding: 3px;
            }
            
            .aqualuxe-media-remove-button {
                margin-left: 5px;
            }
        </style>
        <?php
    }

    /**
     * Save SEO meta boxes
     *
     * @param int $post_id Post ID.
     */
    public function save_seo_meta_boxes($post_id) {
        // Check if nonce is set
        if (!isset($_POST['aqualuxe_seo_meta_box_nonce'])) {
            return;
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['aqualuxe_seo_meta_box_nonce'], 'aqualuxe_seo_meta_box')) {
            return;
        }
        
        // Check if auto save
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if ('page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }
        
        // Save SEO title
        if (isset($_POST['aqualuxe_seo_title'])) {
            update_post_meta($post_id, '_aqualuxe_seo_title', sanitize_text_field($_POST['aqualuxe_seo_title']));
        }
        
        // Save meta description
        if (isset($_POST['aqualuxe_meta_description'])) {
            update_post_meta($post_id, '_aqualuxe_meta_description', sanitize_textarea_field($_POST['aqualuxe_meta_description']));
        }
        
        // Save keywords
        if (isset($_POST['aqualuxe_keywords'])) {
            update_post_meta($post_id, '_aqualuxe_keywords', sanitize_text_field($_POST['aqualuxe_keywords']));
        }
        
        // Save canonical URL
        if (isset($_POST['aqualuxe_canonical_url'])) {
            update_post_meta($post_id, '_aqualuxe_canonical_url', esc_url_raw($_POST['aqualuxe_canonical_url']));
        }
        
        // Save robots meta
        if (isset($_POST['aqualuxe_robots_meta'])) {
            update_post_meta($post_id, '_aqualuxe_robots_meta', sanitize_text_field($_POST['aqualuxe_robots_meta']));
        }
        
        // Save Open Graph title
        if (isset($_POST['aqualuxe_og_title'])) {
            update_post_meta($post_id, '_aqualuxe_og_title', sanitize_text_field($_POST['aqualuxe_og_title']));
        }
        
        // Save Open Graph description
        if (isset($_POST['aqualuxe_og_description'])) {
            update_post_meta($post_id, '_aqualuxe_og_description', sanitize_textarea_field($_POST['aqualuxe_og_description']));
        }
        
        // Save Open Graph image
        if (isset($_POST['aqualuxe_og_image'])) {
            update_post_meta($post_id, '_aqualuxe_og_image', absint($_POST['aqualuxe_og_image']));
        }
        
        // Save Twitter Card title
        if (isset($_POST['aqualuxe_twitter_title'])) {
            update_post_meta($post_id, '_aqualuxe_twitter_title', sanitize_text_field($_POST['aqualuxe_twitter_title']));
        }
        
        // Save Twitter Card description
        if (isset($_POST['aqualuxe_twitter_description'])) {
            update_post_meta($post_id, '_aqualuxe_twitter_description', sanitize_textarea_field($_POST['aqualuxe_twitter_description']));
        }
        
        // Save Twitter Card image
        if (isset($_POST['aqualuxe_twitter_image'])) {
            update_post_meta($post_id, '_aqualuxe_twitter_image', absint($_POST['aqualuxe_twitter_image']));
        }
    }

    /**
     * Add category SEO fields
     */
    public function add_category_seo_fields() {
        ?>
        <div class="form-field">
            <label for="aqualuxe_seo_title"><?php esc_html_e('SEO Title', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_seo_title" name="aqualuxe_seo_title" value="" />
            <p class="description"><?php esc_html_e('Enter a custom SEO title. Leave blank to use the category name.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_meta_description"><?php esc_html_e('Meta Description', 'aqualuxe'); ?></label>
            <textarea id="aqualuxe_meta_description" name="aqualuxe_meta_description" rows="3"></textarea>
            <p class="description"><?php esc_html_e('Enter a custom meta description. Leave blank to use the category description.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_keywords"><?php esc_html_e('Keywords', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_keywords" name="aqualuxe_keywords" value="" />
            <p class="description"><?php esc_html_e('Enter a comma-separated list of keywords.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_canonical_url"><?php esc_html_e('Canonical URL', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_canonical_url" name="aqualuxe_canonical_url" value="" />
            <p class="description"><?php esc_html_e('Enter a custom canonical URL. Leave blank to use the category URL.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_robots_meta"><?php esc_html_e('Robots Meta', 'aqualuxe'); ?></label>
            <select id="aqualuxe_robots_meta" name="aqualuxe_robots_meta">
                <option value="index,follow"><?php esc_html_e('Index, Follow', 'aqualuxe'); ?></option>
                <option value="index,nofollow"><?php esc_html_e('Index, No Follow', 'aqualuxe'); ?></option>
                <option value="noindex,follow"><?php esc_html_e('No Index, Follow', 'aqualuxe'); ?></option>
                <option value="noindex,nofollow"><?php esc_html_e('No Index, No Follow', 'aqualuxe'); ?></option>
            </select>
            <p class="description"><?php esc_html_e('Select robots meta directives.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_og_title"><?php esc_html_e('Open Graph Title', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_og_title" name="aqualuxe_og_title" value="" />
            <p class="description"><?php esc_html_e('Enter a custom Open Graph title. Leave blank to use the SEO title or category name.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_og_description"><?php esc_html_e('Open Graph Description', 'aqualuxe'); ?></label>
            <textarea id="aqualuxe_og_description" name="aqualuxe_og_description" rows="3"></textarea>
            <p class="description"><?php esc_html_e('Enter a custom Open Graph description. Leave blank to use the meta description.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_og_image"><?php esc_html_e('Open Graph Image', 'aqualuxe'); ?></label>
            <div class="aqualuxe-media-upload">
                <input type="hidden" id="aqualuxe_og_image" name="aqualuxe_og_image" value="" />
                <div class="aqualuxe-media-preview"></div>
                <button type="button" class="button aqualuxe-media-upload-button" data-target="aqualuxe_og_image"><?php esc_html_e('Select Image', 'aqualuxe'); ?></button>
                <button type="button" class="button aqualuxe-media-remove-button" data-target="aqualuxe_og_image"><?php esc_html_e('Remove Image', 'aqualuxe'); ?></button>
            </div>
            <p class="description"><?php esc_html_e('Select an image for Open Graph.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_twitter_title"><?php esc_html_e('Twitter Card Title', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_twitter_title" name="aqualuxe_twitter_title" value="" />
            <p class="description"><?php esc_html_e('Enter a custom Twitter Card title. Leave blank to use the Open Graph title.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_twitter_description"><?php esc_html_e('Twitter Card Description', 'aqualuxe'); ?></label>
            <textarea id="aqualuxe_twitter_description" name="aqualuxe_twitter_description" rows="3"></textarea>
            <p class="description"><?php esc_html_e('Enter a custom Twitter Card description. Leave blank to use the Open Graph description.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="aqualuxe_twitter_image"><?php esc_html_e('Twitter Card Image', 'aqualuxe'); ?></label>
            <div class="aqualuxe-media-upload">
                <input type="hidden" id="aqualuxe_twitter_image" name="aqualuxe_twitter_image" value="" />
                <div class="aqualuxe-media-preview"></div>
                <button type="button" class="button aqualuxe-media-upload-button" data-target="aqualuxe_twitter_image"><?php esc_html_e('Select Image', 'aqualuxe'); ?></button>
                <button type="button" class="button aqualuxe-media-remove-button" data-target="aqualuxe_twitter_image"><?php esc_html_e('Remove Image', 'aqualuxe'); ?></button>
            </div>
            <p class="description"><?php esc_html_e('Select an image for Twitter Card. Leave blank to use the Open Graph image.', 'aqualuxe'); ?></p>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Media upload
                $('.aqualuxe-media-upload-button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var target = button.data('target');
                    
                    var frame = wp.media({
                        title: '<?php esc_html_e('Select Image', 'aqualuxe'); ?>',
                        multiple: false,
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: '<?php esc_html_e('Use this image', 'aqualuxe'); ?>'
                        }
                    });
                    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        
                        $('#' + target).val(attachment.id);
                        
                        var preview = button.siblings('.aqualuxe-media-preview');
                        preview.html('<img src="' + attachment.url + '" alt="" />');
                    });
                    
                    frame.open();
                });
                
                // Media remove
                $('.aqualuxe-media-remove-button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var target = button.data('target');
                    
                    $('#' + target).val('');
                    
                    var preview = button.siblings('.aqualuxe-media-preview');
                    preview.html('');
                });
            });
        </script>
        
        <style>
            .aqualuxe-media-preview {
                margin-bottom: 10px;
            }
            
            .aqualuxe-media-preview img {
                max-width: 150px;
                height: auto;
                border: 1px solid #ddd;
                padding: 3px;
            }
            
            .aqualuxe-media-remove-button {
                margin-left: 5px;
            }
        </style>
        <?php
    }

    /**
     * Edit category SEO fields
     *
     * @param WP_Term $term Term object.
     */
    public function edit_category_seo_fields($term) {
        // Get saved values
        $seo_title = get_term_meta($term->term_id, '_aqualuxe_seo_title', true);
        $meta_description = get_term_meta($term->term_id, '_aqualuxe_meta_description', true);
        $keywords = get_term_meta($term->term_id, '_aqualuxe_keywords', true);
        $canonical_url = get_term_meta($term->term_id, '_aqualuxe_canonical_url', true);
        $robots_meta = get_term_meta($term->term_id, '_aqualuxe_robots_meta', true);
        $og_title = get_term_meta($term->term_id, '_aqualuxe_og_title', true);
        $og_description = get_term_meta($term->term_id, '_aqualuxe_og_description', true);
        $og_image = get_term_meta($term->term_id, '_aqualuxe_og_image', true);
        $twitter_title = get_term_meta($term->term_id, '_aqualuxe_twitter_title', true);
        $twitter_description = get_term_meta($term->term_id, '_aqualuxe_twitter_description', true);
        $twitter_image = get_term_meta($term->term_id, '_aqualuxe_twitter_image', true);
        
        // Default robots meta
        if (empty($robots_meta)) {
            $robots_meta = 'index,follow';
        }
        
        ?>
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_seo_title"><?php esc_html_e('SEO Title', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_seo_title" name="aqualuxe_seo_title" value="<?php echo esc_attr($seo_title); ?>" />
                <p class="description"><?php esc_html_e('Enter a custom SEO title. Leave blank to use the category name.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_meta_description"><?php esc_html_e('Meta Description', 'aqualuxe'); ?></label></th>
            <td>
                <textarea id="aqualuxe_meta_description" name="aqualuxe_meta_description" rows="3"><?php echo esc_textarea($meta_description); ?></textarea>
                <p class="description"><?php esc_html_e('Enter a custom meta description. Leave blank to use the category description.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_keywords"><?php esc_html_e('Keywords', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_keywords" name="aqualuxe_keywords" value="<?php echo esc_attr($keywords); ?>" />
                <p class="description"><?php esc_html_e('Enter a comma-separated list of keywords.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_canonical_url"><?php esc_html_e('Canonical URL', 'aqualuxe'); ?></label></th>
            <td>
                <input type="url" id="aqualuxe_canonical_url" name="aqualuxe_canonical_url" value="<?php echo esc_url($canonical_url); ?>" />
                <p class="description"><?php esc_html_e('Enter a custom canonical URL. Leave blank to use the category URL.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_robots_meta"><?php esc_html_e('Robots Meta', 'aqualuxe'); ?></label></th>
            <td>
                <select id="aqualuxe_robots_meta" name="aqualuxe_robots_meta">
                    <option value="index,follow" <?php selected($robots_meta, 'index,follow'); ?>><?php esc_html_e('Index, Follow', 'aqualuxe'); ?></option>
                    <option value="index,nofollow" <?php selected($robots_meta, 'index,nofollow'); ?>><?php esc_html_e('Index, No Follow', 'aqualuxe'); ?></option>
                    <option value="noindex,follow" <?php selected($robots_meta, 'noindex,follow'); ?>><?php esc_html_e('No Index, Follow', 'aqualuxe'); ?></option>
                    <option value="noindex,nofollow" <?php selected($robots_meta, 'noindex,nofollow'); ?>><?php esc_html_e('No Index, No Follow', 'aqualuxe'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Select robots meta directives.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_og_title"><?php esc_html_e('Open Graph Title', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_og_title" name="aqualuxe_og_title" value="<?php echo esc_attr($og_title); ?>" />
                <p class="description"><?php esc_html_e('Enter a custom Open Graph title. Leave blank to use the SEO title or category name.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_og_description"><?php esc_html_e('Open Graph Description', 'aqualuxe'); ?></label></th>
            <td>
                <textarea id="aqualuxe_og_description" name="aqualuxe_og_description" rows="3"><?php echo esc_textarea($og_description); ?></textarea>
                <p class="description"><?php esc_html_e('Enter a custom Open Graph description. Leave blank to use the meta description.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_og_image"><?php esc_html_e('Open Graph Image', 'aqualuxe'); ?></label></th>
            <td>
                <div class="aqualuxe-media-upload">
                    <input type="hidden" id="aqualuxe_og_image" name="aqualuxe_og_image" value="<?php echo esc_attr($og_image); ?>" />
                    <div class="aqualuxe-media-preview">
                        <?php if (!empty($og_image)) : ?>
                            <?php echo wp_get_attachment_image($og_image, 'medium'); ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button aqualuxe-media-upload-button" data-target="aqualuxe_og_image"><?php esc_html_e('Select Image', 'aqualuxe'); ?></button>
                    <button type="button" class="button aqualuxe-media-remove-button" data-target="aqualuxe_og_image"><?php esc_html_e('Remove Image', 'aqualuxe'); ?></button>
                </div>
                <p class="description"><?php esc_html_e('Select an image for Open Graph.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_twitter_title"><?php esc_html_e('Twitter Card Title', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_twitter_title" name="aqualuxe_twitter_title" value="<?php echo esc_attr($twitter_title); ?>" />
                <p class="description"><?php esc_html_e('Enter a custom Twitter Card title. Leave blank to use the Open Graph title.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_twitter_description"><?php esc_html_e('Twitter Card Description', 'aqualuxe'); ?></label></th>
            <td>
                <textarea id="aqualuxe_twitter_description" name="aqualuxe_twitter_description" rows="3"><?php echo esc_textarea($twitter_description); ?></textarea>
                <p class="description"><?php esc_html_e('Enter a custom Twitter Card description. Leave blank to use the Open Graph description.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row"><label for="aqualuxe_twitter_image"><?php esc_html_e('Twitter Card Image', 'aqualuxe'); ?></label></th>
            <td>
                <div class="aqualuxe-media-upload">
                    <input type="hidden" id="aqualuxe_twitter_image" name="aqualuxe_twitter_image" value="<?php echo esc_attr($twitter_image); ?>" />
                    <div class="aqualuxe-media-preview">
                        <?php if (!empty($twitter_image)) : ?>
                            <?php echo wp_get_attachment_image($twitter_image, 'medium'); ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button aqualuxe-media-upload-button" data-target="aqualuxe_twitter_image"><?php esc_html_e('Select Image', 'aqualuxe'); ?></button>
                    <button type="button" class="button aqualuxe-media-remove-button" data-target="aqualuxe_twitter_image"><?php esc_html_e('Remove Image', 'aqualuxe'); ?></button>
                </div>
                <p class="description"><?php esc_html_e('Select an image for Twitter Card. Leave blank to use the Open Graph image.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <script>
            jQuery(document).ready(function($) {
                // Media upload
                $('.aqualuxe-media-upload-button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var target = button.data('target');
                    
                    var frame = wp.media({
                        title: '<?php esc_html_e('Select Image', 'aqualuxe'); ?>',
                        multiple: false,
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: '<?php esc_html_e('Use this image', 'aqualuxe'); ?>'
                        }
                    });
                    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        
                        $('#' + target).val(attachment.id);
                        
                        var preview = button.siblings('.aqualuxe-media-preview');
                        preview.html('<img src="' + attachment.url + '" alt="" />');
                    });
                    
                    frame.open();
                });
                
                // Media remove
                $('.aqualuxe-media-remove-button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var target = button.data('target');
                    
                    $('#' + target).val('');
                    
                    var preview = button.siblings('.aqualuxe-media-preview');
                    preview.html('');
                });
            });
        </script>
        
        <style>
            .aqualuxe-media-preview {
                margin-bottom: 10px;
            }
            
            .aqualuxe-media-preview img {
                max-width: 150px;
                height: auto;
                border: 1px solid #ddd;
                padding: 3px;
            }
            
            .aqualuxe-media-remove-button {
                margin-left: 5px;
            }
        </style>
        <?php
    }

    /**
     * Save category SEO fields
     *
     * @param int $term_id Term ID.
     */
    public function save_category_seo_fields($term_id) {
        // Save SEO title
        if (isset($_POST['aqualuxe_seo_title'])) {
            update_term_meta($term_id, '_aqualuxe_seo_title', sanitize_text_field($_POST['aqualuxe_seo_title']));
        }
        
        // Save meta description
        if (isset($_POST['aqualuxe_meta_description'])) {
            update_term_meta($term_id, '_aqualuxe_meta_description', sanitize_textarea_field($_POST['aqualuxe_meta_description']));
        }
        
        // Save keywords
        if (isset($_POST['aqualuxe_keywords'])) {
            update_term_meta($term_id, '_aqualuxe_keywords', sanitize_text_field($_POST['aqualuxe_keywords']));
        }
        
        // Save canonical URL
        if (isset($_POST['aqualuxe_canonical_url'])) {
            update_term_meta($term_id, '_aqualuxe_canonical_url', esc_url_raw($_POST['aqualuxe_canonical_url']));
        }
        
        // Save robots meta
        if (isset($_POST['aqualuxe_robots_meta'])) {
            update_term_meta($term_id, '_aqualuxe_robots_meta', sanitize_text_field($_POST['aqualuxe_robots_meta']));
        }
        
        // Save Open Graph title
        if (isset($_POST['aqualuxe_og_title'])) {
            update_term_meta($term_id, '_aqualuxe_og_title', sanitize_text_field($_POST['aqualuxe_og_title']));
        }
        
        // Save Open Graph description
        if (isset($_POST['aqualuxe_og_description'])) {
            update_term_meta($term_id, '_aqualuxe_og_description', sanitize_textarea_field($_POST['aqualuxe_og_description']));
        }
        
        // Save Open Graph image
        if (isset($_POST['aqualuxe_og_image'])) {
            update_term_meta($term_id, '_aqualuxe_og_image', absint($_POST['aqualuxe_og_image']));
        }
        
        // Save Twitter Card title
        if (isset($_POST['aqualuxe_twitter_title'])) {
            update_term_meta($term_id, '_aqualuxe_twitter_title', sanitize_text_field($_POST['aqualuxe_twitter_title']));
        }
        
        // Save Twitter Card description
        if (isset($_POST['aqualuxe_twitter_description'])) {
            update_term_meta($term_id, '_aqualuxe_twitter_description', sanitize_textarea_field($_POST['aqualuxe_twitter_description']));
        }
        
        // Save Twitter Card image
        if (isset($_POST['aqualuxe_twitter_image'])) {
            update_term_meta($term_id, '_aqualuxe_twitter_image', absint($_POST['aqualuxe_twitter_image']));
        }
    }

    /**
     * Add tag SEO fields
     */
    public function add_tag_seo_fields() {
        // Same as category SEO fields
        $this->add_category_seo_fields();
    }

    /**
     * Edit tag SEO fields
     *
     * @param WP_Term $term Term object.
     */
    public function edit_tag_seo_fields($term) {
        // Same as category SEO fields
        $this->edit_category_seo_fields($term);
    }

    /**
     * Save tag SEO fields
     *
     * @param int $term_id Term ID.
     */
    public function save_tag_seo_fields($term_id) {
        // Same as category SEO fields
        $this->save_category_seo_fields($term_id);
    }

    /**
     * Add product category SEO fields
     */
    public function add_product_cat_seo_fields() {
        // Same as category SEO fields
        $this->add_category_seo_fields();
    }

    /**
     * Edit product category SEO fields
     *
     * @param WP_Term $term Term object.
     */
    public function edit_product_cat_seo_fields($term) {
        // Same as category SEO fields
        $this->edit_category_seo_fields($term);
    }

    /**
     * Save product category SEO fields
     *
     * @param int $term_id Term ID.
     */
    public function save_product_cat_seo_fields($term_id) {
        // Same as category SEO fields
        $this->save_category_seo_fields($term_id);
    }
}
