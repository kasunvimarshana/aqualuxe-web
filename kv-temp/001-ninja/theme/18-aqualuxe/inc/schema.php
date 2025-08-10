<?php
/**
 * Schema.org markup implementation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Schema Class
 * 
 * Implements schema.org structured data for better SEO
 */
class AquaLuxe_Schema {
    /**
     * Constructor
     */
    public function __construct() {
        // Add schema to head
        add_action('wp_head', [$this, 'output_schema']);
        
        // Add specific schema types based on content type
        add_filter('aqualuxe_schema_organization', [$this, 'get_organization_schema']);
        add_filter('aqualuxe_schema_website', [$this, 'get_website_schema']);
        add_filter('aqualuxe_schema_webpage', [$this, 'get_webpage_schema']);
        add_filter('aqualuxe_schema_article', [$this, 'get_article_schema']);
        add_filter('aqualuxe_schema_product', [$this, 'get_product_schema']);
        add_filter('aqualuxe_schema_fish_species', [$this, 'get_fish_species_schema']);
        add_filter('aqualuxe_schema_care_guide', [$this, 'get_care_guide_schema']);
    }

    /**
     * Output schema markup in head
     */
    public function output_schema() {
        // Get all schema data
        $schema = $this->get_schema_data();
        
        // Output schema JSON-LD
        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
    }

    /**
     * Get all schema data based on current page
     * 
     * @return array Schema data
     */
    private function get_schema_data() {
        // Base schema that's included on all pages
        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                apply_filters('aqualuxe_schema_organization', []),
                apply_filters('aqualuxe_schema_website', []),
                apply_filters('aqualuxe_schema_webpage', []),
            ],
        ];
        
        // Add specific schema based on content type
        if (is_singular('post')) {
            $schema['@graph'][] = apply_filters('aqualuxe_schema_article', []);
        } elseif (is_singular('product')) {
            $schema['@graph'][] = apply_filters('aqualuxe_schema_product', []);
        } elseif (is_singular('fish-species')) {
            $schema['@graph'][] = apply_filters('aqualuxe_schema_fish_species', []);
        } elseif (is_singular('care-guide')) {
            $schema['@graph'][] = apply_filters('aqualuxe_schema_care_guide', []);
        }
        
        // Filter empty items
        $schema['@graph'] = array_filter($schema['@graph']);
        
        return $schema;
    }

    /**
     * Get Organization schema
     * 
     * @return array Organization schema
     */
    public function get_organization_schema() {
        $schema = [
            '@type' => 'Organization',
            '@id' => home_url() . '/#organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
        ];
        
        // Add logo if available
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_image = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo_image) {
                $schema['logo'] = [
                    '@type' => 'ImageObject',
                    'url' => $logo_image[0],
                    'width' => $logo_image[1],
                    'height' => $logo_image[2],
                ];
                $schema['image'] = [
                    '@type' => 'ImageObject',
                    'url' => $logo_image[0],
                    'width' => $logo_image[1],
                    'height' => $logo_image[2],
                ];
            }
        }
        
        // Add social profiles
        $social_profiles = [];
        $facebook = get_theme_mod('aqualuxe_facebook_url');
        $twitter = get_theme_mod('aqualuxe_twitter_url');
        $instagram = get_theme_mod('aqualuxe_instagram_url');
        $youtube = get_theme_mod('aqualuxe_youtube_url');
        
        if ($facebook) {
            $social_profiles[] = $facebook;
        }
        if ($twitter) {
            $social_profiles[] = $twitter;
        }
        if ($instagram) {
            $social_profiles[] = $instagram;
        }
        if ($youtube) {
            $social_profiles[] = $youtube;
        }
        
        if (!empty($social_profiles)) {
            $schema['sameAs'] = $social_profiles;
        }
        
        // Add contact information
        $contact_phone = get_theme_mod('aqualuxe_contact_phone');
        $contact_email = get_theme_mod('aqualuxe_contact_email');
        
        if ($contact_phone) {
            $schema['telephone'] = $contact_phone;
        }
        if ($contact_email) {
            $schema['email'] = $contact_email;
        }
        
        // Add address if available
        $address_street = get_theme_mod('aqualuxe_address_street');
        $address_city = get_theme_mod('aqualuxe_address_city');
        $address_state = get_theme_mod('aqualuxe_address_state');
        $address_zip = get_theme_mod('aqualuxe_address_zip');
        $address_country = get_theme_mod('aqualuxe_address_country');
        
        if ($address_street && $address_city) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $address_street,
                'addressLocality' => $address_city,
                'addressRegion' => $address_state,
                'postalCode' => $address_zip,
                'addressCountry' => $address_country,
            ];
        }
        
        return $schema;
    }

    /**
     * Get Website schema
     * 
     * @return array Website schema
     */
    public function get_website_schema() {
        return [
            '@type' => 'WebSite',
            '@id' => home_url() . '/#website',
            'url' => home_url(),
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'publisher' => [
                '@id' => home_url() . '/#organization',
            ],
            'potentialAction' => [
                [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => home_url('/?s={search_term_string}'),
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ];
    }

    /**
     * Get WebPage schema
     * 
     * @return array WebPage schema
     */
    public function get_webpage_schema() {
        global $wp_query;
        
        $schema = [
            '@type' => 'WebPage',
            '@id' => get_permalink() . '#webpage',
            'url' => get_permalink(),
            'name' => wp_get_document_title(),
            'isPartOf' => [
                '@id' => home_url() . '/#website',
            ],
            'inLanguage' => get_bloginfo('language'),
        ];
        
        // Add primary image if available
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                $schema['primaryImageOfPage'] = [
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                ];
            }
        }
        
        // Add date published and modified for single posts
        if (is_singular()) {
            $schema['datePublished'] = get_the_date('c');
            $schema['dateModified'] = get_the_modified_date('c');
        }
        
        // Add breadcrumb if it's not the homepage
        if (!is_front_page()) {
            $schema['breadcrumb'] = [
                '@id' => get_permalink() . '#breadcrumb',
            ];
            
            // Add breadcrumb schema
            $breadcrumb_schema = $this->get_breadcrumb_schema();
            if (!empty($breadcrumb_schema)) {
                $schema['@graph'][] = $breadcrumb_schema;
            }
        }
        
        return $schema;
    }

    /**
     * Get Article schema
     * 
     * @return array Article schema
     */
    public function get_article_schema() {
        global $post;
        
        if (!is_singular('post')) {
            return [];
        }
        
        $schema = [
            '@type' => 'Article',
            '@id' => get_permalink() . '#article',
            'isPartOf' => [
                '@id' => get_permalink() . '#webpage',
            ],
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author_meta('display_name', $post->post_author),
                'url' => get_author_posts_url($post->post_author),
            ],
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'commentCount' => get_comments_number(),
            'publisher' => [
                '@id' => home_url() . '/#organization',
            ],
            'mainEntityOfPage' => [
                '@id' => get_permalink() . '#webpage',
            ],
            'inLanguage' => get_bloginfo('language'),
        ];
        
        // Add featured image
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                $schema['image'] = [
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                ];
            }
        }
        
        // Add article section and keywords
        $categories = get_the_category();
        if (!empty($categories)) {
            $schema['articleSection'] = $categories[0]->name;
        }
        
        $tags = get_the_tags();
        if (!empty($tags)) {
            $keywords = [];
            foreach ($tags as $tag) {
                $keywords[] = $tag->name;
            }
            $schema['keywords'] = implode(', ', $keywords);
        }
        
        return $schema;
    }

    /**
     * Get Product schema for WooCommerce products
     * 
     * @return array Product schema
     */
    public function get_product_schema() {
        if (!is_singular('product') || !class_exists('WooCommerce')) {
            return [];
        }
        
        global $product;
        
        // If $product is not set or valid, return empty array
        if (!is_a($product, 'WC_Product')) {
            return [];
        }
        
        $schema = [
            '@type' => 'Product',
            '@id' => get_permalink() . '#product',
            'name' => $product->get_name(),
            'url' => get_permalink(),
            'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
        ];
        
        // Add product image
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                $schema['image'] = [
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                ];
            }
        }
        
        // Add SKU
        if ($product->get_sku()) {
            $schema['sku'] = $product->get_sku();
        }
        
        // Add brand if available (using product_brand taxonomy if it exists)
        $brands = get_the_terms($product->get_id(), 'product_brand');
        if ($brands && !is_wp_error($brands)) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $brands[0]->name,
            ];
        }
        
        // Add offers
        $schema['offers'] = [
            '@type' => 'Offer',
            'url' => get_permalink(),
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'priceValidUntil' => date('Y-12-31', time() + YEAR_IN_SECONDS),
        ];
        
        // Add rating if available
        if ($product->get_rating_count() > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            ];
        }
        
        return $schema;
    }

    /**
     * Get Fish Species schema
     * 
     * @return array Fish Species schema
     */
    public function get_fish_species_schema() {
        if (!is_singular('fish-species')) {
            return [];
        }
        
        $post_id = get_the_ID();
        
        // Base schema as Product
        $schema = [
            '@type' => 'Product',
            '@id' => get_permalink() . '#fish-species',
            'name' => get_the_title(),
            'description' => wp_strip_all_tags(get_the_excerpt()),
            'url' => get_permalink(),
        ];
        
        // Add featured image
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                $schema['image'] = [
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                ];
            }
        }
        
        // Add taxonomies as product attributes
        $taxonomies = ['fish-family', 'fish-origin', 'water-type', 'care-level'];
        $product_attributes = [];
        
        foreach ($taxonomies as $taxonomy) {
            $terms = get_the_terms($post_id, $taxonomy);
            if ($terms && !is_wp_error($terms)) {
                $term_names = [];
                foreach ($terms as $term) {
                    $term_names[] = $term->name;
                }
                
                $attribute_name = str_replace('-', ' ', $taxonomy);
                $attribute_name = ucwords($attribute_name);
                
                $product_attributes[] = [
                    '@type' => 'PropertyValue',
                    'propertyID' => $taxonomy,
                    'name' => $attribute_name,
                    'value' => implode(', ', $term_names),
                ];
            }
        }
        
        if (!empty($product_attributes)) {
            $schema['additionalProperty'] = $product_attributes;
        }
        
        // Add custom fields if they exist
        $custom_fields = [
            'scientific_name' => 'Scientific Name',
            'adult_size' => 'Adult Size',
            'lifespan' => 'Lifespan',
            'ph_range' => 'pH Range',
            'temperature' => 'Temperature',
            'tank_size' => 'Tank Size',
        ];
        
        foreach ($custom_fields as $field_key => $field_name) {
            $field_value = get_post_meta($post_id, $field_key, true);
            if ($field_value) {
                $product_attributes[] = [
                    '@type' => 'PropertyValue',
                    'propertyID' => $field_key,
                    'name' => $field_name,
                    'value' => $field_value,
                ];
            }
        }
        
        // Add related products if available
        $related_products = get_post_meta($post_id, 'related_products', true);
        if ($related_products && is_array($related_products) && class_exists('WooCommerce')) {
            $schema['isRelatedTo'] = [];
            
            foreach ($related_products as $product_id) {
                $product = wc_get_product($product_id);
                if ($product) {
                    $schema['isRelatedTo'][] = [
                        '@type' => 'Product',
                        'name' => $product->get_name(),
                        'url' => get_permalink($product_id),
                    ];
                }
            }
        }
        
        return $schema;
    }

    /**
     * Get Care Guide schema
     * 
     * @return array Care Guide schema
     */
    public function get_care_guide_schema() {
        if (!is_singular('care-guide')) {
            return [];
        }
        
        $post_id = get_the_ID();
        
        // Base schema as Article with HowTo elements
        $schema = [
            '@type' => ['Article', 'HowTo'],
            '@id' => get_permalink() . '#care-guide',
            'name' => get_the_title(),
            'description' => wp_strip_all_tags(get_the_excerpt()),
            'url' => get_permalink(),
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author_meta('display_name'),
            ],
            'publisher' => [
                '@id' => home_url() . '/#organization',
            ],
            'mainEntityOfPage' => [
                '@id' => get_permalink() . '#webpage',
            ],
        ];
        
        // Add featured image
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                $schema['image'] = [
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                ];
            }
        }
        
        // Add difficulty level if available
        $difficulty = get_post_meta($post_id, 'difficulty_level', true);
        if ($difficulty) {
            $schema['estimatedCost'] = [
                '@type' => 'MonetaryAmount',
                'currency' => get_woocommerce_currency(),
                'value' => $this->get_difficulty_cost($difficulty),
            ];
        }
        
        // Add materials needed if available
        $materials = get_post_meta($post_id, 'materials_needed', true);
        if ($materials && is_array($materials)) {
            $schema['tool'] = [];
            foreach ($materials as $material) {
                $schema['tool'][] = [
                    '@type' => 'HowToTool',
                    'name' => $material,
                ];
            }
        }
        
        // Add steps if available
        $steps = get_post_meta($post_id, 'guide_steps', true);
        if ($steps && is_array($steps)) {
            $schema['step'] = [];
            $step_number = 1;
            
            foreach ($steps as $step) {
                if (isset($step['title']) && isset($step['description'])) {
                    $schema['step'][] = [
                        '@type' => 'HowToStep',
                        'position' => $step_number,
                        'name' => $step['title'],
                        'text' => $step['description'],
                    ];
                    
                    $step_number++;
                }
            }
        }
        
        // Add time required if available
        $time_required = get_post_meta($post_id, 'time_required', true);
        if ($time_required) {
            $schema['totalTime'] = $this->format_time_to_iso8601($time_required);
        }
        
        return $schema;
    }

    /**
     * Get breadcrumb schema
     * 
     * @return array Breadcrumb schema
     */
    private function get_breadcrumb_schema() {
        if (is_front_page()) {
            return [];
        }
        
        $breadcrumbs = [];
        $position = 1;
        
        // Add home
        $breadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'item' => [
                '@id' => home_url(),
                'name' => __('Home', 'aqualuxe'),
            ],
        ];
        
        // Add breadcrumb items based on page type
        if (is_singular('post')) {
            // Add blog page if set
            $blog_page_id = get_option('page_for_posts');
            if ($blog_page_id) {
                $position++;
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => [
                        '@id' => get_permalink($blog_page_id),
                        'name' => get_the_title($blog_page_id),
                    ],
                ];
            }
            
            // Add categories
            $categories = get_the_category();
            if (!empty($categories)) {
                $position++;
                $category = $categories[0];
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => [
                        '@id' => get_category_link($category->term_id),
                        'name' => $category->name,
                    ],
                ];
            }
            
            // Add current post
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_permalink(),
                    'name' => get_the_title(),
                ],
            ];
        } elseif (is_singular('product')) {
            // Add shop page
            $shop_page_id = wc_get_page_id('shop');
            if ($shop_page_id > 0) {
                $position++;
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => [
                        '@id' => get_permalink($shop_page_id),
                        'name' => get_the_title($shop_page_id),
                    ],
                ];
            }
            
            // Add product categories
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            if ($terms && !is_wp_error($terms)) {
                $position++;
                $term = $terms[0];
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => [
                        '@id' => get_term_link($term),
                        'name' => $term->name,
                    ],
                ];
            }
            
            // Add current product
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_permalink(),
                    'name' => get_the_title(),
                ],
            ];
        } elseif (is_singular('fish-species') || is_singular('care-guide')) {
            // Get post type object
            $post_type = get_post_type_object(get_post_type());
            
            // Add post type archive
            if ($post_type && $post_type->has_archive) {
                $position++;
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => [
                        '@id' => get_post_type_archive_link(get_post_type()),
                        'name' => $post_type->labels->name,
                    ],
                ];
            }
            
            // Add current post
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_permalink(),
                    'name' => get_the_title(),
                ],
            ];
        } elseif (is_page()) {
            // Add parent pages if any
            $ancestors = get_post_ancestors(get_the_ID());
            if ($ancestors) {
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor) {
                    $position++;
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'item' => [
                            '@id' => get_permalink($ancestor),
                            'name' => get_the_title($ancestor),
                        ],
                    ];
                }
            }
            
            // Add current page
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_permalink(),
                    'name' => get_the_title(),
                ],
            ];
        } elseif (is_archive()) {
            if (is_category() || is_tag() || is_tax()) {
                $position++;
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => [
                        '@id' => get_term_link(get_queried_object()),
                        'name' => single_term_title('', false),
                    ],
                ];
            } elseif (is_post_type_archive()) {
                $position++;
                $post_type = get_post_type_object(get_post_type());
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => [
                        '@id' => get_post_type_archive_link(get_post_type()),
                        'name' => $post_type->labels->name,
                    ],
                ];
            }
        }
        
        // Return breadcrumb schema
        return [
            '@type' => 'BreadcrumbList',
            '@id' => get_permalink() . '#breadcrumb',
            'itemListElement' => $breadcrumbs,
        ];
    }

    /**
     * Convert difficulty level to estimated cost
     * 
     * @param string $difficulty Difficulty level
     * @return string Estimated cost
     */
    private function get_difficulty_cost($difficulty) {
        switch ($difficulty) {
            case 'easy':
                return '1';
            case 'medium':
                return '2';
            case 'hard':
                return '3';
            case 'expert':
                return '4';
            default:
                return '1';
        }
    }

    /**
     * Format time to ISO 8601 duration format
     * 
     * @param string $time Time string (e.g., "2 hours 30 minutes")
     * @return string ISO 8601 duration
     */
    private function format_time_to_iso8601($time) {
        // Default duration
        $duration = 'PT1H';
        
        // Try to parse the time string
        if (preg_match('/(\d+)\s*hour/i', $time, $hours) && preg_match('/(\d+)\s*minute/i', $time, $minutes)) {
            $duration = 'PT' . $hours[1] . 'H' . $minutes[1] . 'M';
        } elseif (preg_match('/(\d+)\s*hour/i', $time, $hours)) {
            $duration = 'PT' . $hours[1] . 'H';
        } elseif (preg_match('/(\d+)\s*minute/i', $time, $minutes)) {
            $duration = 'PT' . $minutes[1] . 'M';
        } elseif (preg_match('/(\d+)\s*day/i', $time, $days)) {
            $duration = 'P' . $days[1] . 'D';
        }
        
        return $duration;
    }
}

// Initialize the schema class
new AquaLuxe_Schema();