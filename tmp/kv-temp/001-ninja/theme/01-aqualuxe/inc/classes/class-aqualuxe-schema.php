<?php
/**
 * AquaLuxe Schema.org Implementation
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Schema class
 * 
 * Handles Schema.org structured data implementation
 */
class AquaLuxe_Schema {
    /**
     * Constructor
     */
    public function __construct() {
        // Add schema to HTML tag
        add_filter('language_attributes', array($this, 'html_schema_attributes'));
        
        // Add schema to head
        add_action('wp_head', array($this, 'output_schema_organization'), 10);
        add_action('wp_head', array($this, 'output_schema_website'), 20);
        
        // Add schema to content
        add_filter('the_content', array($this, 'add_article_schema'), 10);
        
        // Add schema to products
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_single_product_summary', array($this, 'output_product_schema'), 60);
        }
        
        // Add breadcrumb schema
        add_filter('aqualuxe_breadcrumb_data', array($this, 'add_breadcrumb_schema'));
    }

    /**
     * Add schema attributes to HTML tag
     *
     * @param string $output The language attributes.
     * @return string
     */
    public function html_schema_attributes($output) {
        return $output . ' itemscope itemtype="https://schema.org/WebPage"';
    }

    /**
     * Output Organization schema
     */
    public function output_schema_organization() {
        $schema = array(
            '@context'  => 'https://schema.org',
            '@type'     => 'Organization',
            '@id'       => home_url('/#organization'),
            'name'      => get_bloginfo('name'),
            'url'       => home_url('/'),
        );
        
        // Add logo if available
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $logo_image = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($logo_image) {
                $schema['logo'] = array(
                    '@type'     => 'ImageObject',
                    'url'       => $logo_image[0],
                    'width'     => $logo_image[1],
                    'height'    => $logo_image[2],
                );
            }
        }
        
        // Add social profiles if available
        $social_profiles = array();
        $facebook = get_theme_mod('aqualuxe_facebook', '');
        $twitter = get_theme_mod('aqualuxe_twitter', '');
        $instagram = get_theme_mod('aqualuxe_instagram', '');
        $linkedin = get_theme_mod('aqualuxe_linkedin', '');
        $youtube = get_theme_mod('aqualuxe_youtube', '');
        $pinterest = get_theme_mod('aqualuxe_pinterest', '');
        
        if ($facebook) {
            $social_profiles[] = $facebook;
        }
        if ($twitter) {
            $social_profiles[] = $twitter;
        }
        if ($instagram) {
            $social_profiles[] = $instagram;
        }
        if ($linkedin) {
            $social_profiles[] = $linkedin;
        }
        if ($youtube) {
            $social_profiles[] = $youtube;
        }
        if ($pinterest) {
            $social_profiles[] = $pinterest;
        }
        
        if (!empty($social_profiles)) {
            $schema['sameAs'] = $social_profiles;
        }
        
        // Add contact information if available
        $phone = get_theme_mod('aqualuxe_phone', '');
        $email = get_theme_mod('aqualuxe_email', '');
        $address = get_theme_mod('aqualuxe_address', '');
        $city = get_theme_mod('aqualuxe_city', '');
        $state = get_theme_mod('aqualuxe_state', '');
        $postal_code = get_theme_mod('aqualuxe_postal_code', '');
        $country = get_theme_mod('aqualuxe_country', '');
        
        if ($phone || $email || ($address && $city && $state && $postal_code && $country)) {
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
            
            if ($address && $city && $state && $postal_code && $country) {
                $schema['address'] = array(
                    '@type' => 'PostalAddress',
                    'streetAddress' => $address,
                    'addressLocality' => $city,
                    'addressRegion' => $state,
                    'postalCode' => $postal_code,
                    'addressCountry' => $country,
                );
            }
        }
        
        // Output schema
        $this->output_schema($schema);
    }

    /**
     * Output WebSite schema
     */
    public function output_schema_website() {
        $schema = array(
            '@context'  => 'https://schema.org',
            '@type'     => 'WebSite',
            '@id'       => home_url('/#website'),
            'url'       => home_url('/'),
            'name'      => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'publisher' => array(
                '@id' => home_url('/#organization'),
            ),
        );
        
        // Add search action
        $schema['potentialAction'] = array(
            '@type'       => 'SearchAction',
            'target'      => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string',
        );
        
        // Output schema
        $this->output_schema($schema);
    }

    /**
     * Add Article schema to content
     *
     * @param string $content The content.
     * @return string
     */
    public function add_article_schema($content) {
        if (!is_singular('post')) {
            return $content;
        }
        
        global $post;
        
        $schema = array(
            '@context'  => 'https://schema.org',
            '@type'     => 'Article',
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => get_permalink(),
            ),
            'headline'  => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'author'    => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
            ),
            'publisher' => array(
                '@id' => home_url('/#organization'),
            ),
        );
        
        // Add featured image if available
        if (has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image = wp_get_attachment_image_src($image_id, 'full');
            
            if ($image) {
                $schema['image'] = array(
                    '@type'  => 'ImageObject',
                    'url'    => $image[0],
                    'width'  => $image[1],
                    'height' => $image[2],
                );
            }
        }
        
        // Add schema to content
        $schema_html = '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
        
        return $content . $schema_html;
    }

    /**
     * Output Product schema
     */
    public function output_product_schema() {
        if (!is_product()) {
            return;
        }
        
        global $product;
        
        if (!$product) {
            return;
        }
        
        $schema = array(
            '@context'  => 'https://schema.org',
            '@type'     => 'Product',
            'name'      => $product->get_name(),
            'url'       => get_permalink($product->get_id()),
            'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
        );
        
        // Add SKU
        if ($product->get_sku()) {
            $schema['sku'] = $product->get_sku();
        }
        
        // Add brand if available
        $brands = wp_get_post_terms($product->get_id(), 'pa_brand');
        if (!empty($brands) && !is_wp_error($brands)) {
            $schema['brand'] = array(
                '@type' => 'Brand',
                'name'  => $brands[0]->name,
            );
        }
        
        // Add image
        if (has_post_thumbnail($product->get_id())) {
            $image_id = get_post_thumbnail_id($product->get_id());
            $image = wp_get_attachment_image_src($image_id, 'full');
            
            if ($image) {
                $schema['image'] = $image[0];
            }
        }
        
        // Add price
        if ($product->get_price()) {
            $schema['offers'] = array(
                '@type'         => 'Offer',
                'price'         => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url'           => get_permalink($product->get_id()),
                'priceValidUntil' => date('Y-12-31', current_time('timestamp')),
            );
        }
        
        // Add reviews
        if ($product->get_review_count()) {
            $schema['aggregateRating'] = array(
                '@type'       => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            );
            
            // Add individual reviews
            $reviews = get_comments(array(
                'post_id' => $product->get_id(),
                'status'  => 'approve',
                'type'    => 'review',
                'number'  => 5, // Limit to 5 reviews
            ));
            
            if (!empty($reviews)) {
                $schema['review'] = array();
                
                foreach ($reviews as $review) {
                    $rating = get_comment_meta($review->comment_ID, 'rating', true);
                    
                    if ($rating) {
                        $schema['review'][] = array(
                            '@type'         => 'Review',
                            'reviewRating'  => array(
                                '@type'       => 'Rating',
                                'ratingValue' => $rating,
                            ),
                            'author'        => array(
                                '@type' => 'Person',
                                'name'  => $review->comment_author,
                            ),
                            'datePublished' => get_comment_date('c', $review->comment_ID),
                            'reviewBody'    => $review->comment_content,
                        );
                    }
                }
            }
        }
        
        // Output schema
        $this->output_schema($schema);
    }

    /**
     * Add Breadcrumb schema
     *
     * @param array $breadcrumbs The breadcrumbs.
     * @return array
     */
    public function add_breadcrumb_schema($breadcrumbs) {
        if (empty($breadcrumbs)) {
            return $breadcrumbs;
        }
        
        $schema = array(
            '@context'  => 'https://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => array(),
        );
        
        $position = 1;
        
        foreach ($breadcrumbs as $breadcrumb) {
            $schema['itemListElement'][] = array(
                '@type'    => 'ListItem',
                'position' => $position,
                'item'     => array(
                    '@id'  => $breadcrumb['url'],
                    'name' => $breadcrumb['text'],
                ),
            );
            
            $position++;
        }
        
        // Add schema to breadcrumbs
        $breadcrumbs['schema'] = $schema;
        
        return $breadcrumbs;
    }

    /**
     * Output schema
     *
     * @param array $schema The schema.
     */
    private function output_schema($schema) {
        if (empty($schema)) {
            return;
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
}

// Initialize the schema class
new AquaLuxe_Schema();