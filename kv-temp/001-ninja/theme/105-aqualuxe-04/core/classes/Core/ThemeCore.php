<?php
/**
 * Theme Core Class
 *
 * Main theme initialization and management
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Class ThemeCore
 *
 * Singleton class that handles theme initialization
 */
class ThemeCore {
    
    /**
     * Single instance of the class
     *
     * @var ThemeCore
     */
    private static $instance = null;

    /**
     * Theme options
     *
     * @var array
     */
    private $options = array();

    /**
     * Get instance
     *
     * @return ThemeCore
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
        $this->load_options();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_meta_tags'));
        add_action('wp_footer', array($this, 'add_schema_markup'));
        add_filter('body_class', array($this, 'add_body_classes'));
        
        // Register secure AJAX handler for dark mode
        aqualuxe_secure_ajax_handler('toggle_dark_mode_legacy', array($this, 'handle_dark_mode_toggle'), false, true);
    }

    /**
     * Load theme options
     */
    private function load_options() {
        $this->options = get_option('aqualuxe_theme_options', array());
    }

    /**
     * Get theme option
     *
     * @param string $key Option key
     * @param mixed $default Default value
     * @return mixed
     */
    public function get_option($key, $default = null) {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    /**
     * Set theme option
     *
     * @param string $key Option key
     * @param mixed $value Option value
     */
    public function set_option($key, $value) {
        $this->options[$key] = $value;
        update_option('aqualuxe_theme_options', $this->options);
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Get mix manifest for cache busting
        $manifest = $this->get_mix_manifest();

        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            AQUALUXE_ASSETS_URI . '/css/' . $this->get_manifest_file($manifest, 'css/app.css'),
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue main JavaScript
        wp_enqueue_script(
            'aqualuxe-script',
            AQUALUXE_ASSETS_URI . '/js/' . $this->get_manifest_file($manifest, 'js/app.js'),
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        // Localize script for AJAX
        wp_localize_script('aqualuxe-script', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => aqualuxe_create_nonce('theme_core'),
            'dark_mode' => $this->is_dark_mode_enabled(),
        ));

        // Enqueue conditional scripts
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Get mix manifest
     *
     * @return array
     */
    private function get_mix_manifest() {
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
            return is_array($manifest) ? $manifest : array();
        }
        
        return array();
    }

    /**
     * Get manifest file
     *
     * @param array $manifest Mix manifest
     * @param string $file File path
     * @return string
     */
    private function get_manifest_file($manifest, $file) {
        $file_key = '/' . ltrim($file, '/');
        
        if (isset($manifest[$file_key])) {
            return ltrim($manifest[$file_key], '/');
        }
        
        return $file;
    }

    /**
     * Add enhanced meta tags to head
     */
    public function add_meta_tags() {
        // Charset
        echo '<meta charset="' . esc_attr(get_bloginfo('charset')) . '">' . "\n";
        
        // Viewport
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">' . "\n";
        
        // Theme color
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0891b2');
        echo '<meta name="theme-color" content="' . esc_attr($primary_color) . '">' . "\n";
        echo '<meta name="msapplication-TileColor" content="' . esc_attr($primary_color) . '">' . "\n";
        
        // SEO Meta Tags
        if (is_singular()) {
            global $post;
            
            $title = get_the_title();
            $description = wp_strip_all_tags(get_the_excerpt());
            $url = get_permalink();
            $image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
            
            // Description
            if ($description) {
                echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
            }
            
            // Keywords (if custom field exists)
            $keywords = get_post_meta(get_the_ID(), '_seo_keywords', true);
            if ($keywords) {
                echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
            }
            
            // Author
            echo '<meta name="author" content="' . esc_attr(get_the_author()) . '">' . "\n";
            
            // Article specific tags
            if (is_single()) {
                echo '<meta name="article:author" content="' . esc_attr(get_the_author()) . '">' . "\n";
                echo '<meta name="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
                echo '<meta name="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
                
                // Article categories
                $categories = get_the_category();
                if ($categories) {
                    foreach ($categories as $category) {
                        echo '<meta name="article:section" content="' . esc_attr($category->name) . '">' . "\n";
                    }
                }
                
                // Article tags
                $tags = get_the_tags();
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<meta name="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                    }
                }
            }
            
        } else {
            // Archive/home page description
            $description = get_bloginfo('description');
            if (is_archive()) {
                $description = get_the_archive_description();
            } elseif (is_search()) {
                $description = sprintf(__('Search results for: %s', 'aqualuxe'), get_search_query());
            }
            
            if ($description) {
                echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($description)) . '">' . "\n";
            }
        }
        
        // Canonical URL
        $canonical_url = is_singular() ? get_permalink() : '';
        if (is_home() && !is_front_page()) {
            $canonical_url = get_permalink(get_option('page_for_posts'));
        } elseif (is_front_page()) {
            $canonical_url = home_url('/');
        } elseif (is_archive()) {
            $canonical_url = get_term_link(get_queried_object());
        }
        
        if ($canonical_url) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
        }
        
        // Open Graph tags
        $this->add_open_graph_tags();
        
        // Twitter Card tags
        $this->add_twitter_card_tags();
        
        // Robots meta
        $this->add_robots_meta();
    }

    /**
     * Add Open Graph tags
     */
    private function add_open_graph_tags() {
        if (is_singular()) {
            global $post;
            
            $title = get_the_title();
            $description = wp_strip_all_tags(get_the_excerpt());
            $url = get_permalink();
            $image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
            $site_name = get_bloginfo('name');
            
            echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
            echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
            
            if (is_single()) {
                echo '<meta property="og:type" content="article">' . "\n";
                echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
                echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
                echo '<meta property="article:author" content="' . esc_attr(get_the_author()) . '">' . "\n";
            } else {
                echo '<meta property="og:type" content="website">' . "\n";
            }
            
            if ($image) {
                echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
                
                // Get image dimensions
                $attachment_id = get_post_thumbnail_id();
                if ($attachment_id) {
                    $image_meta = wp_get_attachment_metadata($attachment_id);
                    if (isset($image_meta['width'], $image_meta['height'])) {
                        echo '<meta property="og:image:width" content="' . esc_attr($image_meta['width']) . '">' . "\n";
                        echo '<meta property="og:image:height" content="' . esc_attr($image_meta['height']) . '">' . "\n";
                    }
                }
                
                $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                if ($image_alt) {
                    echo '<meta property="og:image:alt" content="' . esc_attr($image_alt) . '">' . "\n";
                }
            }
        }
    }

    /**
     * Add Twitter Card tags
     */
    private function add_twitter_card_tags() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        
        $twitter_handle = get_theme_mod('aqualuxe_social_twitter');
        if ($twitter_handle) {
            $twitter_handle = str_replace('@', '', $twitter_handle);
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_handle) . '">' . "\n";
        }
        
        if (is_singular()) {
            $title = get_the_title();
            $description = wp_strip_all_tags(get_the_excerpt());
            $image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
            
            echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
            
            if ($image) {
                echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
            }
        }
    }

    /**
     * Add robots meta tag
     */
    private function add_robots_meta() {
        $robots = array();
        
        if (is_search() || is_404()) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        } elseif (is_singular() && get_post_meta(get_the_ID(), '_seo_noindex', true)) {
            $robots[] = 'noindex';
        } else {
            $robots[] = 'index';
            $robots[] = 'follow';
        }
        
        // Add additional directives
        $robots[] = 'max-snippet:-1';
        $robots[] = 'max-image-preview:large';
        $robots[] = 'max-video-preview:-1';
        
        echo '<meta name="robots" content="' . esc_attr(implode(', ', $robots)) . '">' . "\n";
    }

    /**
     * Add schema markup to footer
     */
    public function add_schema_markup() {
        if (is_singular('post')) {
            $this->add_article_schema();
        } elseif (is_page()) {
            $this->add_webpage_schema();
        } elseif (is_home() || is_front_page()) {
            $this->add_website_schema();
        }
    }

    /**
     * Add article schema with enhanced markup
     */
    private function add_article_schema() {
        global $post;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => wp_strip_all_tags(get_the_excerpt()),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'wordCount' => str_word_count(strip_tags(get_the_content())),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author(),
                'url' => get_author_posts_url(get_the_author_meta('ID')),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url(),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : '',
                ),
            ),
        );
        
        if (has_post_thumbnail()) {
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $thumbnail_meta = wp_get_attachment_metadata($thumbnail_id);
            
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $thumbnail_url,
                'width' => $thumbnail_meta['width'] ?? '',
                'height' => $thumbnail_meta['height'] ?? '',
            );
        }
        
        // Add breadcrumb schema
        $schema['breadcrumb'] = $this->get_breadcrumb_schema();
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    /**
     * Add webpage schema with enhanced markup
     */
    private function add_webpage_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_the_title(),
            'description' => wp_strip_all_tags(get_the_excerpt()),
            'url' => get_permalink(),
            'inLanguage' => get_locale(),
            'isPartOf' => array(
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'url' => home_url(),
            ),
        );
        
        if (has_post_thumbnail()) {
            $schema['primaryImageOfPage'] = array(
                '@type' => 'ImageObject',
                'url' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    /**
     * Add website schema with enhanced markup
     */
    private function add_website_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url(),
            'inLanguage' => get_locale(),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => array(
                    '@type' => 'EntryPoint',
                    'urlTemplate' => home_url('/?s={search_term_string}'),
                ),
                'query-input' => 'required name=search_term_string',
            ),
        );
        
        // Add social media profiles if configured
        $social_profiles = array();
        $social_platforms = array('facebook', 'twitter', 'instagram', 'linkedin', 'youtube');
        
        foreach ($social_platforms as $platform) {
            $url = get_theme_mod('aqualuxe_social_' . $platform);
            if ($url) {
                $social_profiles[] = $url;
            }
        }
        
        if (!empty($social_profiles)) {
            $schema['sameAs'] = $social_profiles;
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
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
            'name' => __('Home', 'aqualuxe'),
            'item' => home_url(),
        );
        
        if (is_category() || is_single()) {
            $categories = get_the_category();
            if ($categories) {
                foreach ($categories as $category) {
                    $breadcrumbs[] = array(
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => $category->name,
                        'item' => get_category_link($category->term_id),
                    );
                }
            }
        }
        
        if (is_single()) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title(),
                'item' => get_permalink(),
            );
        }
        
        return array(
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs,
        );
    }

    /**
     * Add body classes
     *
     * @param array $classes Existing body classes
     * @return array
     */
    public function add_body_classes($classes) {
        // Add dark mode class
        if ($this->is_dark_mode_enabled()) {
            $classes[] = 'dark-mode';
        }
        
        // Add WooCommerce detection class
        if (class_exists('WooCommerce')) {
            $classes[] = 'has-woocommerce';
        } else {
            $classes[] = 'no-woocommerce';
        }
        
        // Add custom classes based on page type
        if (is_page_template()) {
            $template = get_page_template_slug();
            $classes[] = 'page-template-' . sanitize_html_class(str_replace('.php', '', basename($template)));
        }
        
        return $classes;
    }

    /**
     * Check if dark mode is enabled
     *
     * @return bool
     */
    public function is_dark_mode_enabled() {
        // Check user preference from cookie
        if (isset($_COOKIE['aqualuxe_dark_mode'])) {
            return $_COOKIE['aqualuxe_dark_mode'] === 'enabled';
        }
        
        // Default to system preference detection via CSS
        return false;
    }

    /**
     * Handle dark mode toggle AJAX request (legacy support)
     */
    public function handle_dark_mode_toggle($data) {
        $enabled = isset($data['enabled']) && $data['enabled'] === 'true';
        
        // Set secure cookie for 30 days
        $cookie_value = $enabled ? 'enabled' : 'disabled';
        $secure = is_ssl();
        $httponly = true;
        
        setcookie(
            'aqualuxe_dark_mode', 
            $cookie_value, 
            time() + (30 * DAY_IN_SECONDS), 
            '/', 
            '', 
            $secure, 
            $httponly
        );
        
        wp_send_json_success(array('dark_mode' => $enabled));
    }
}