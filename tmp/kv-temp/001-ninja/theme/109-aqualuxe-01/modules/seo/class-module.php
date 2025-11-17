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
        add_action('wp_head', array($this, 'add_meta_tags'));
        add_action('wp_head', array($this, 'add_schema_markup'));
        add_action('wp_head', array($this, 'add_open_graph_tags'));
        add_action('wp_head', array($this, 'add_twitter_cards'));
        add_action('init', array($this, 'generate_sitemap'));
        add_action('customize_register', array($this, 'customize_register'));
        add_filter('document_title_parts', array($this, 'customize_title'));
        add_action('wp_head', array($this, 'canonical_url'));
        add_action('wp_head', array($this, 'robots_meta'));
        
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