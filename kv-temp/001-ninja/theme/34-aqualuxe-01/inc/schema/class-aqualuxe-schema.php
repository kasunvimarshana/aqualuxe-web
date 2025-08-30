<?php
/**
 * AquaLuxe Schema.org Implementation
 *
 * Adds structured data to the theme for better SEO and rich snippets.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Schema Class
 */
class AquaLuxe_Schema {

    /**
     * The single instance of the class.
     *
     * @var AquaLuxe_Schema
     */
    protected static $_instance = null;

    /**
     * Main AquaLuxe_Schema Instance.
     *
     * Ensures only one instance of AquaLuxe_Schema is loaded or can be loaded.
     *
     * @return AquaLuxe_Schema - Main instance.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks.
     */
    public function init_hooks() {
        // Add schema to head
        add_action('wp_head', array($this, 'output_schema'));
        
        // Add customizer options
        add_action('customize_register', array($this, 'add_customizer_options'));
    }

    /**
     * Output schema markup in the head.
     */
    public function output_schema() {
        // Get schema data based on current page
        $schema_data = $this->get_schema_data();
        
        // Output schema as JSON-LD if we have data
        if (!empty($schema_data)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema_data) . '</script>';
        }
    }

    /**
     * Get schema data based on current page.
     *
     * @return array Schema data.
     */
    private function get_schema_data() {
        $schema_data = array();
        
        // Always add organization schema
        $organization_schema = $this->get_organization_schema();
        if ($organization_schema) {
            $schema_data[] = $organization_schema;
        }
        
        // Always add website schema
        $website_schema = $this->get_website_schema();
        if ($website_schema) {
            $schema_data[] = $website_schema;
        }
        
        // Add breadcrumb schema if available
        if ($this->has_breadcrumbs()) {
            $breadcrumb_schema = $this->get_breadcrumb_schema();
            if ($breadcrumb_schema) {
                $schema_data[] = $breadcrumb_schema;
            }
        }
        
        // Add page-specific schema
        if (is_product()) {
            // Product schema
            $product_schema = $this->get_product_schema();
            if ($product_schema) {
                $schema_data[] = $product_schema;
            }
        } elseif (is_product_category() || is_shop()) {
            // ItemList schema for product archives
            $itemlist_schema = $this->get_itemlist_schema();
            if ($itemlist_schema) {
                $schema_data[] = $itemlist_schema;
            }
        } elseif (is_singular('post')) {
            // BlogPosting schema for single posts
            $blogposting_schema = $this->get_blogposting_schema();
            if ($blogposting_schema) {
                $schema_data[] = $blogposting_schema;
            }
        } elseif (is_page()) {
            // WebPage schema for regular pages
            $webpage_schema = $this->get_webpage_schema();
            if ($webpage_schema) {
                $schema_data[] = $webpage_schema;
            }
        }
        
        return $schema_data;
    }

    /**
     * Get Organization schema.
     *
     * @return array Organization schema data.
     */
    private function get_organization_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => home_url('/#organization'),
            'name' => get_theme_mod('aqualuxe_organization_name', get_bloginfo('name')),
            'url' => home_url('/'),
        );
        
        // Add logo if available
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_image = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo_image) {
                $schema['logo'] = array(
                    '@type' => 'ImageObject',
                    'url' => $logo_image[0],
                    'width' => $logo_image[1],
                    'height' => $logo_image[2],
                );
                $schema['image'] = $logo_image[0];
            }
        }
        
        // Add contact information
        $phone = get_theme_mod('aqualuxe_organization_phone', '');
        $email = get_theme_mod('aqualuxe_organization_email', '');
        
        if ($phone || $email) {
            $schema['contactPoint'] = array(
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
            );
            
            if ($phone) {
                $schema['contactPoint']['telephone'] = $phone;
            }
            
            if ($email) {
                $schema['contactPoint']['email'] = $email;
            }
        }
        
        // Add social profiles
        $social_profiles = array(
            'facebook' => get_theme_mod('aqualuxe_social_facebook', ''),
            'twitter' => get_theme_mod('aqualuxe_social_twitter', ''),
            'instagram' => get_theme_mod('aqualuxe_social_instagram', ''),
            'linkedin' => get_theme_mod('aqualuxe_social_linkedin', ''),
            'youtube' => get_theme_mod('aqualuxe_social_youtube', ''),
            'pinterest' => get_theme_mod('aqualuxe_social_pinterest', ''),
        );
        
        $same_as = array();
        foreach ($social_profiles as $profile) {
            if (!empty($profile)) {
                $same_as[] = $profile;
            }
        }
        
        if (!empty($same_as)) {
            $schema['sameAs'] = $same_as;
        }
        
        return $schema;
    }

    /**
     * Get WebSite schema.
     *
     * @return array WebSite schema data.
     */
    private function get_website_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => home_url('/#website'),
            'url' => home_url('/'),
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
        );
        
        // Add search action if search is enabled
        if (get_theme_mod('aqualuxe_enable_search', true)) {
            $schema['potentialAction'] = array(
                '@type' => 'SearchAction',
                'target' => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            );
        }
        
        return $schema;
    }

    /**
     * Get WebPage schema.
     *
     * @return array WebPage schema data.
     */
    private function get_webpage_schema() {
        global $post;
        
        if (!$post) {
            return false;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => get_permalink() . '#webpage',
            'url' => get_permalink(),
            'name' => get_the_title(),
            'isPartOf' => array(
                '@id' => home_url('/#website'),
            ),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
        );
        
        // Add description if available
        if (has_excerpt()) {
            $schema['description'] = get_the_excerpt();
        }
        
        // Add featured image if available
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                $schema['image'] = $image[0];
            }
        }
        
        return $schema;
    }

    /**
     * Get BlogPosting schema.
     *
     * @return array BlogPosting schema data.
     */
    private function get_blogposting_schema() {
        global $post;
        
        if (!$post) {
            return false;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            '@id' => get_permalink() . '#blogposting',
            'headline' => get_the_title(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink() . '#webpage',
            ),
            'publisher' => array(
                '@id' => home_url('/#organization'),
            ),
        );
        
        // Add author
        $author_id = $post->post_author;
        if ($author_id) {
            $schema['author'] = array(
                '@type' => 'Person',
                'name' => get_the_author_meta('display_name', $author_id),
                'url' => get_author_posts_url($author_id),
            );
        }
        
        // Add featured image
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                $schema['image'] = array(
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                );
            }
        }
        
        // Add description/excerpt
        if (has_excerpt()) {
            $schema['description'] = get_the_excerpt();
        }
        
        // Add article body (content)
        $content = get_the_content();
        if (!empty($content)) {
            $schema['articleBody'] = wp_strip_all_tags($content);
        }
        
        return $schema;
    }

    /**
     * Get Product schema.
     *
     * @return array Product schema data.
     */
    private function get_product_schema() {
        global $product;
        
        if (!is_object($product) || !is_a($product, 'WC_Product')) {
            $product = wc_get_product(get_the_ID());
            if (!$product) {
                return false;
            }
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            '@id' => get_permalink() . '#product',
            'name' => $product->get_name(),
            'url' => get_permalink(),
            'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
        );
        
        // Add SKU
        if ($product->get_sku()) {
            $schema['sku'] = $product->get_sku();
        }
        
        // Add brand if available (using product categories as fallback)
        $brands = wp_get_post_terms($product->get_id(), 'product_brand');
        if (!empty($brands) && !is_wp_error($brands)) {
            $schema['brand'] = array(
                '@type' => 'Brand',
                'name' => $brands[0]->name,
            );
        } else {
            $categories = wp_get_post_terms($product->get_id(), 'product_cat');
            if (!empty($categories) && !is_wp_error($categories)) {
                $schema['brand'] = array(
                    '@type' => 'Brand',
                    'name' => $categories[0]->name,
                );
            }
        }
        
        // Add product images
        $image_ids = $product->get_gallery_image_ids();
        array_unshift($image_ids, $product->get_image_id());
        $image_ids = array_filter($image_ids);
        
        if (!empty($image_ids)) {
            $images = array();
            foreach ($image_ids as $image_id) {
                $image = wp_get_attachment_image_src($image_id, 'full');
                if ($image) {
                    $images[] = $image[0];
                }
            }
            
            if (!empty($images)) {
                $schema['image'] = count($images) === 1 ? $images[0] : $images;
            }
        }
        
        // Add offers
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'url' => get_permalink(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'priceValidUntil' => date('Y-12-31', strtotime('+1 year')),
        );
        
        // Add seller
        $schema['offers']['seller'] = array(
            '@type' => 'Organization',
            '@id' => home_url('/#organization'),
            'name' => get_theme_mod('aqualuxe_organization_name', get_bloginfo('name')),
        );
        
        // Add aggregate rating if available
        if ($product->get_rating_count() > 0) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            );
        }
        
        // Add reviews if available
        if ($product->get_review_count() > 0) {
            $comments = get_comments(array(
                'post_id' => $product->get_id(),
                'status' => 'approve',
                'type' => 'review',
            ));
            
            if (!empty($comments)) {
                $schema['review'] = array();
                
                foreach ($comments as $comment) {
                    $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                    
                    if ($rating) {
                        $review = array(
                            '@type' => 'Review',
                            'reviewRating' => array(
                                '@type' => 'Rating',
                                'ratingValue' => $rating,
                            ),
                            'author' => array(
                                '@type' => 'Person',
                                'name' => $comment->comment_author,
                            ),
                            'datePublished' => get_comment_date('c', $comment->comment_ID),
                            'reviewBody' => $comment->comment_content,
                        );
                        
                        $schema['review'][] = $review;
                    }
                }
            }
        }
        
        return $schema;
    }

    /**
     * Get ItemList schema for product archives.
     *
     * @return array ItemList schema data.
     */
    private function get_itemlist_schema() {
        global $wp_query;
        
        if (!$wp_query->posts) {
            return false;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            '@id' => get_permalink() . '#itemlist',
            'url' => get_permalink(),
            'numberOfItems' => count($wp_query->posts),
            'itemListElement' => array(),
        );
        
        $position = 1;
        
        foreach ($wp_query->posts as $post) {
            $product = wc_get_product($post->ID);
            
            if (!$product) {
                continue;
            }
            
            $schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'url' => get_permalink($post->ID),
                'item' => array(
                    '@type' => 'Product',
                    'name' => $product->get_name(),
                    'url' => get_permalink($post->ID),
                ),
            );
            
            $position++;
        }
        
        return $schema;
    }

    /**
     * Get BreadcrumbList schema.
     *
     * @return array BreadcrumbList schema data.
     */
    private function get_breadcrumb_schema() {
        // Check if WooCommerce breadcrumbs are available
        if (!function_exists('wc_get_breadcrumb')) {
            return false;
        }
        
        $breadcrumbs = wc_get_breadcrumb();
        
        if (empty($breadcrumbs)) {
            return false;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            '@id' => get_permalink() . '#breadcrumb',
            'itemListElement' => array(),
        );
        
        $position = 1;
        
        foreach ($breadcrumbs as $breadcrumb) {
            if (isset($breadcrumb[0]) && isset($breadcrumb[1])) {
                $schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => array(
                        '@id' => $breadcrumb[1],
                        'name' => $breadcrumb[0],
                    ),
                );
                
                $position++;
            }
        }
        
        return $schema;
    }

    /**
     * Check if breadcrumbs are displayed.
     *
     * @return bool True if breadcrumbs are displayed.
     */
    private function has_breadcrumbs() {
        // Check if we're on a WooCommerce page
        if (function_exists('is_woocommerce') && is_woocommerce()) {
            return true;
        }
        
        // Check if we're on a singular post/page with breadcrumbs enabled
        if (is_singular() && get_theme_mod('aqualuxe_show_breadcrumbs', true)) {
            return true;
        }
        
        return false;
    }

    /**
     * Add customizer options for schema.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function add_customizer_options($wp_customize) {
        // Add section
        $wp_customize->add_section('aqualuxe_schema', array(
            'title' => __('SEO & Schema', 'aqualuxe'),
            'description' => __('Settings for SEO and structured data (schema.org).', 'aqualuxe'),
            'priority' => 160,
        ));
        
        // Organization name
        $wp_customize->add_setting('aqualuxe_organization_name', array(
            'default' => get_bloginfo('name'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_organization_name', array(
            'label' => __('Organization Name', 'aqualuxe'),
            'description' => __('The name of your organization or business.', 'aqualuxe'),
            'section' => 'aqualuxe_schema',
            'type' => 'text',
        ));
        
        // Organization phone
        $wp_customize->add_setting('aqualuxe_organization_phone', array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_organization_phone', array(
            'label' => __('Organization Phone', 'aqualuxe'),
            'description' => __('The phone number for your organization.', 'aqualuxe'),
            'section' => 'aqualuxe_schema',
            'type' => 'text',
        ));
        
        // Organization email
        $wp_customize->add_setting('aqualuxe_organization_email', array(
            'default' => '',
            'sanitize_callback' => 'sanitize_email',
        ));
        
        $wp_customize->add_control('aqualuxe_organization_email', array(
            'label' => __('Organization Email', 'aqualuxe'),
            'description' => __('The email address for your organization.', 'aqualuxe'),
            'section' => 'aqualuxe_schema',
            'type' => 'email',
        ));
        
        // Social profiles
        $social_platforms = array(
            'facebook' => __('Facebook URL', 'aqualuxe'),
            'twitter' => __('Twitter URL', 'aqualuxe'),
            'instagram' => __('Instagram URL', 'aqualuxe'),
            'linkedin' => __('LinkedIn URL', 'aqualuxe'),
            'youtube' => __('YouTube URL', 'aqualuxe'),
            'pinterest' => __('Pinterest URL', 'aqualuxe'),
        );
        
        foreach ($social_platforms as $platform => $label) {
            $wp_customize->add_setting('aqualuxe_social_' . $platform, array(
                'default' => '',
                'sanitize_callback' => 'esc_url_raw',
            ));
            
            $wp_customize->add_control('aqualuxe_social_' . $platform, array(
                'label' => $label,
                'section' => 'aqualuxe_schema',
                'type' => 'url',
            ));
        }
        
        // Enable search schema
        $wp_customize->add_setting('aqualuxe_enable_search', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_search', array(
            'label' => __('Enable Search Schema', 'aqualuxe'),
            'description' => __('Add search action schema to help search engines understand your site search.', 'aqualuxe'),
            'section' => 'aqualuxe_schema',
            'type' => 'checkbox',
        ));
        
        // Show breadcrumbs
        $wp_customize->add_setting('aqualuxe_show_breadcrumbs', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_show_breadcrumbs', array(
            'label' => __('Show Breadcrumbs', 'aqualuxe'),
            'description' => __('Display breadcrumb navigation on singular posts and pages.', 'aqualuxe'),
            'section' => 'aqualuxe_schema',
            'type' => 'checkbox',
        ));
    }
}

// Initialize the class
AquaLuxe_Schema::instance();

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}