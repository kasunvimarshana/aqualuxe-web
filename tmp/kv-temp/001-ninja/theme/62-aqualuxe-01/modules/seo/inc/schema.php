<?php
/**
 * AquaLuxe SEO Schema Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/SEO
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Get schema markup
 *
 * @return array
 */
function aqualuxe_seo_get_schema_markup() {
    // Get schema type
    $schema_type = aqualuxe_seo_get_schema_type();
    
    // Get schema markup based on type
    switch ( $schema_type ) {
        case 'article':
            return aqualuxe_seo_get_article_schema();
        case 'product':
            return aqualuxe_seo_get_product_schema();
        case 'review':
            return aqualuxe_seo_get_review_schema();
        case 'event':
            return aqualuxe_seo_get_event_schema();
        case 'person':
            return aqualuxe_seo_get_person_schema();
        case 'organization':
            return aqualuxe_seo_get_organization_schema();
        case 'website':
            return aqualuxe_seo_get_website_schema();
        case 'breadcrumb':
            return aqualuxe_seo_get_breadcrumb_schema();
        default:
            return aqualuxe_seo_get_website_schema();
    }
}

/**
 * Get schema type
 *
 * @return string
 */
function aqualuxe_seo_get_schema_type() {
    // Check if we're on a single post or page
    if ( is_singular( 'post' ) ) {
        return 'article';
    } elseif ( is_singular( 'product' ) && class_exists( 'WooCommerce' ) ) {
        return 'product';
    } elseif ( is_author() ) {
        return 'person';
    } elseif ( is_front_page() ) {
        return 'website';
    } else {
        return 'website';
    }
}

/**
 * Get article schema
 *
 * @return array
 */
function aqualuxe_seo_get_article_schema() {
    // Get post ID
    $post_id = get_queried_object_id();
    
    // Get post data
    $post_title = aqualuxe_seo_get_post_title( $post_id );
    $post_excerpt = aqualuxe_seo_get_post_excerpt( $post_id );
    $post_content = aqualuxe_seo_get_post_content( $post_id );
    $post_url = aqualuxe_seo_get_post_url( $post_id );
    $post_date = aqualuxe_seo_get_post_date( $post_id );
    $post_author = aqualuxe_seo_get_post_author( $post_id );
    $post_thumbnail = aqualuxe_seo_get_post_thumbnail( $post_id );
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => $post_url,
        ),
        'headline' => $post_title,
        'description' => $post_excerpt,
        'datePublished' => $post_date['published'],
        'dateModified' => $post_date['modified'],
        'author' => array(
            '@type' => 'Person',
            'name' => $post_author['name'],
            'url' => $post_author['url'],
        ),
        'publisher' => aqualuxe_seo_get_publisher_schema(),
    );
    
    // Add image if available
    if ( ! empty( $post_thumbnail ) ) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $post_thumbnail['url'],
            'width' => $post_thumbnail['width'],
            'height' => $post_thumbnail['height'],
        );
    }
    
    return $schema;
}

/**
 * Get product schema
 *
 * @return array
 */
function aqualuxe_seo_get_product_schema() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }
    
    // Get post ID
    $post_id = get_queried_object_id();
    
    // Get product
    $product = wc_get_product( $post_id );
    
    // Check if product exists
    if ( ! $product ) {
        return array();
    }
    
    // Get product data
    $product_name = $product->get_name();
    $product_description = $product->get_description();
    $product_short_description = $product->get_short_description();
    $product_url = get_permalink( $post_id );
    $product_sku = $product->get_sku();
    $product_price = $product->get_price();
    $product_regular_price = $product->get_regular_price();
    $product_sale_price = $product->get_sale_price();
    $product_availability = $product->is_in_stock() ? 'InStock' : 'OutOfStock';
    $product_thumbnail = aqualuxe_seo_get_post_thumbnail( $post_id );
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product_name,
        'description' => ! empty( $product_short_description ) ? $product_short_description : $product_description,
        'sku' => $product_sku,
        'offers' => array(
            '@type' => 'Offer',
            'price' => $product_price,
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => 'https://schema.org/' . $product_availability,
            'url' => $product_url,
        ),
    );
    
    // Add image if available
    if ( ! empty( $product_thumbnail ) ) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $product_thumbnail['url'],
            'width' => $product_thumbnail['width'],
            'height' => $product_thumbnail['height'],
        );
    }
    
    // Add brand if available
    $brand_taxonomy = 'pa_brand';
    $brand_terms = get_the_terms( $post_id, $brand_taxonomy );
    if ( ! empty( $brand_terms ) && ! is_wp_error( $brand_terms ) ) {
        $schema['brand'] = array(
            '@type' => 'Brand',
            'name' => $brand_terms[0]->name,
        );
    }
    
    // Add review data if available
    if ( $product->get_review_count() > 0 ) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
        );
    }
    
    return $schema;
}

/**
 * Get review schema
 *
 * @return array
 */
function aqualuxe_seo_get_review_schema() {
    // Get post ID
    $post_id = get_queried_object_id();
    
    // Get post data
    $post_title = aqualuxe_seo_get_post_title( $post_id );
    $post_excerpt = aqualuxe_seo_get_post_excerpt( $post_id );
    $post_content = aqualuxe_seo_get_post_content( $post_id );
    $post_url = aqualuxe_seo_get_post_url( $post_id );
    $post_date = aqualuxe_seo_get_post_date( $post_id );
    $post_author = aqualuxe_seo_get_post_author( $post_id );
    $post_thumbnail = aqualuxe_seo_get_post_thumbnail( $post_id );
    
    // Get review data
    $review_rating = get_post_meta( $post_id, '_aqualuxe_review_rating', true );
    $review_item_name = get_post_meta( $post_id, '_aqualuxe_review_item_name', true );
    $review_item_type = get_post_meta( $post_id, '_aqualuxe_review_item_type', true );
    
    // Check if review data exists
    if ( empty( $review_rating ) || empty( $review_item_name ) ) {
        return array();
    }
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Review',
        'reviewRating' => array(
            '@type' => 'Rating',
            'ratingValue' => $review_rating,
            'bestRating' => '5',
        ),
        'name' => $post_title,
        'author' => array(
            '@type' => 'Person',
            'name' => $post_author['name'],
        ),
        'datePublished' => $post_date['published'],
        'reviewBody' => $post_excerpt,
        'itemReviewed' => array(
            '@type' => ! empty( $review_item_type ) ? $review_item_type : 'Product',
            'name' => $review_item_name,
        ),
    );
    
    // Add image if available
    if ( ! empty( $post_thumbnail ) ) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $post_thumbnail['url'],
            'width' => $post_thumbnail['width'],
            'height' => $post_thumbnail['height'],
        );
    }
    
    return $schema;
}

/**
 * Get event schema
 *
 * @return array
 */
function aqualuxe_seo_get_event_schema() {
    // Get post ID
    $post_id = get_queried_object_id();
    
    // Get event data
    $event_name = get_post_meta( $post_id, '_aqualuxe_event_name', true );
    $event_description = get_post_meta( $post_id, '_aqualuxe_event_description', true );
    $event_start_date = get_post_meta( $post_id, '_aqualuxe_event_start_date', true );
    $event_end_date = get_post_meta( $post_id, '_aqualuxe_event_end_date', true );
    $event_location_name = get_post_meta( $post_id, '_aqualuxe_event_location_name', true );
    $event_location_address = get_post_meta( $post_id, '_aqualuxe_event_location_address', true );
    $event_url = get_post_meta( $post_id, '_aqualuxe_event_url', true );
    $event_price = get_post_meta( $post_id, '_aqualuxe_event_price', true );
    $event_currency = get_post_meta( $post_id, '_aqualuxe_event_currency', true );
    $event_availability = get_post_meta( $post_id, '_aqualuxe_event_availability', true );
    
    // Check if event data exists
    if ( empty( $event_name ) || empty( $event_start_date ) ) {
        return array();
    }
    
    // Get post data
    $post_title = aqualuxe_seo_get_post_title( $post_id );
    $post_excerpt = aqualuxe_seo_get_post_excerpt( $post_id );
    $post_url = aqualuxe_seo_get_post_url( $post_id );
    $post_thumbnail = aqualuxe_seo_get_post_thumbnail( $post_id );
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => ! empty( $event_name ) ? $event_name : $post_title,
        'description' => ! empty( $event_description ) ? $event_description : $post_excerpt,
        'startDate' => $event_start_date,
        'url' => ! empty( $event_url ) ? $event_url : $post_url,
    );
    
    // Add end date if available
    if ( ! empty( $event_end_date ) ) {
        $schema['endDate'] = $event_end_date;
    }
    
    // Add location if available
    if ( ! empty( $event_location_name ) ) {
        $schema['location'] = array(
            '@type' => 'Place',
            'name' => $event_location_name,
        );
        
        // Add address if available
        if ( ! empty( $event_location_address ) ) {
            $schema['location']['address'] = $event_location_address;
        }
    }
    
    // Add offers if available
    if ( ! empty( $event_price ) ) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => $event_price,
            'priceCurrency' => ! empty( $event_currency ) ? $event_currency : 'USD',
        );
        
        // Add availability if available
        if ( ! empty( $event_availability ) ) {
            $schema['offers']['availability'] = 'https://schema.org/' . $event_availability;
        }
    }
    
    // Add image if available
    if ( ! empty( $post_thumbnail ) ) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $post_thumbnail['url'],
            'width' => $post_thumbnail['width'],
            'height' => $post_thumbnail['height'],
        );
    }
    
    return $schema;
}

/**
 * Get person schema
 *
 * @return array
 */
function aqualuxe_seo_get_person_schema() {
    // Get author ID
    $author_id = get_queried_object_id();
    
    // Get author data
    $author_name = aqualuxe_seo_get_author_name( $author_id );
    $author_description = aqualuxe_seo_get_author_description( $author_id );
    $author_url = aqualuxe_seo_get_author_url( $author_id );
    $author_avatar = aqualuxe_seo_get_author_avatar( $author_id );
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $author_name,
        'url' => $author_url,
    );
    
    // Add description if available
    if ( ! empty( $author_description ) ) {
        $schema['description'] = $author_description;
    }
    
    // Add image if available
    if ( ! empty( $author_avatar ) ) {
        $schema['image'] = $author_avatar;
    }
    
    // Add social profiles if available
    $social_profiles = array();
    $social_fields = array(
        'facebook' => get_the_author_meta( 'facebook', $author_id ),
        'twitter' => get_the_author_meta( 'twitter', $author_id ),
        'instagram' => get_the_author_meta( 'instagram', $author_id ),
        'linkedin' => get_the_author_meta( 'linkedin', $author_id ),
        'youtube' => get_the_author_meta( 'youtube', $author_id ),
        'pinterest' => get_the_author_meta( 'pinterest', $author_id ),
    );
    
    foreach ( $social_fields as $social_field => $social_url ) {
        if ( ! empty( $social_url ) ) {
            $social_profiles[] = $social_url;
        }
    }
    
    if ( ! empty( $social_profiles ) ) {
        $schema['sameAs'] = $social_profiles;
    }
    
    return $schema;
}

/**
 * Get organization schema
 *
 * @return array
 */
function aqualuxe_seo_get_organization_schema() {
    // Get site info
    $site_info = aqualuxe_seo_get_site_info();
    
    // Get social profiles
    $social_profiles = aqualuxe_seo_get_social_profiles();
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $site_info['name'],
        'url' => $site_info['url'],
    );
    
    // Add description if available
    if ( ! empty( $site_info['description'] ) ) {
        $schema['description'] = $site_info['description'];
    }
    
    // Add logo if available
    if ( ! empty( $site_info['logo'] ) ) {
        $schema['logo'] = $site_info['logo'];
    }
    
    // Add social profiles if available
    if ( ! empty( $social_profiles ) ) {
        $schema['sameAs'] = array_values( $social_profiles );
    }
    
    return $schema;
}

/**
 * Get website schema
 *
 * @return array
 */
function aqualuxe_seo_get_website_schema() {
    // Get site info
    $site_info = aqualuxe_seo_get_site_info();
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $site_info['name'],
        'url' => $site_info['url'],
    );
    
    // Add description if available
    if ( ! empty( $site_info['description'] ) ) {
        $schema['description'] = $site_info['description'];
    }
    
    // Add search action
    $schema['potentialAction'] = array(
        '@type' => 'SearchAction',
        'target' => $site_info['url'] . '?s={search_term_string}',
        'query-input' => 'required name=search_term_string',
    );
    
    return $schema;
}

/**
 * Get breadcrumb schema
 *
 * @return array
 */
function aqualuxe_seo_get_breadcrumb_schema() {
    // Get breadcrumbs
    $breadcrumbs = aqualuxe_seo_get_current_page_breadcrumbs();
    
    // Check if breadcrumbs exist
    if ( empty( $breadcrumbs ) ) {
        return array();
    }
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(),
    );
    
    // Add breadcrumb items
    foreach ( $breadcrumbs as $index => $breadcrumb ) {
        $schema['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $breadcrumb['name'],
            'item' => $breadcrumb['url'],
        );
    }
    
    return $schema;
}

/**
 * Get publisher schema
 *
 * @return array
 */
function aqualuxe_seo_get_publisher_schema() {
    // Get site info
    $site_info = aqualuxe_seo_get_site_info();
    
    // Build schema
    $schema = array(
        '@type' => 'Organization',
        'name' => $site_info['name'],
        'url' => $site_info['url'],
    );
    
    // Add logo if available
    if ( ! empty( $site_info['logo'] ) ) {
        $schema['logo'] = array(
            '@type' => 'ImageObject',
            'url' => $site_info['logo'],
        );
    }
    
    return $schema;
}

/**
 * Get local business schema
 *
 * @return array
 */
function aqualuxe_seo_get_local_business_schema() {
    // Get business data
    $business_name = get_option( 'aqualuxe_seo_business_name', '' );
    $business_type = get_option( 'aqualuxe_seo_business_type', 'LocalBusiness' );
    $business_description = get_option( 'aqualuxe_seo_business_description', '' );
    $business_url = get_option( 'aqualuxe_seo_business_url', '' );
    $business_logo = get_option( 'aqualuxe_seo_business_logo', '' );
    $business_image = get_option( 'aqualuxe_seo_business_image', '' );
    $business_telephone = get_option( 'aqualuxe_seo_business_telephone', '' );
    $business_email = get_option( 'aqualuxe_seo_business_email', '' );
    $business_address_street = get_option( 'aqualuxe_seo_business_address_street', '' );
    $business_address_locality = get_option( 'aqualuxe_seo_business_address_locality', '' );
    $business_address_region = get_option( 'aqualuxe_seo_business_address_region', '' );
    $business_address_postal_code = get_option( 'aqualuxe_seo_business_address_postal_code', '' );
    $business_address_country = get_option( 'aqualuxe_seo_business_address_country', '' );
    $business_geo_latitude = get_option( 'aqualuxe_seo_business_geo_latitude', '' );
    $business_geo_longitude = get_option( 'aqualuxe_seo_business_geo_longitude', '' );
    $business_opening_hours = get_option( 'aqualuxe_seo_business_opening_hours', array() );
    $business_price_range = get_option( 'aqualuxe_seo_business_price_range', '' );
    
    // Check if business data exists
    if ( empty( $business_name ) ) {
        return array();
    }
    
    // Get site info
    $site_info = aqualuxe_seo_get_site_info();
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => $business_type,
        'name' => $business_name,
        'url' => ! empty( $business_url ) ? $business_url : $site_info['url'],
    );
    
    // Add description if available
    if ( ! empty( $business_description ) ) {
        $schema['description'] = $business_description;
    }
    
    // Add logo if available
    if ( ! empty( $business_logo ) ) {
        $schema['logo'] = $business_logo;
    }
    
    // Add image if available
    if ( ! empty( $business_image ) ) {
        $schema['image'] = $business_image;
    }
    
    // Add telephone if available
    if ( ! empty( $business_telephone ) ) {
        $schema['telephone'] = $business_telephone;
    }
    
    // Add email if available
    if ( ! empty( $business_email ) ) {
        $schema['email'] = $business_email;
    }
    
    // Add address if available
    if ( ! empty( $business_address_street ) && ! empty( $business_address_locality ) ) {
        $schema['address'] = array(
            '@type' => 'PostalAddress',
            'streetAddress' => $business_address_street,
            'addressLocality' => $business_address_locality,
        );
        
        // Add region if available
        if ( ! empty( $business_address_region ) ) {
            $schema['address']['addressRegion'] = $business_address_region;
        }
        
        // Add postal code if available
        if ( ! empty( $business_address_postal_code ) ) {
            $schema['address']['postalCode'] = $business_address_postal_code;
        }
        
        // Add country if available
        if ( ! empty( $business_address_country ) ) {
            $schema['address']['addressCountry'] = $business_address_country;
        }
    }
    
    // Add geo coordinates if available
    if ( ! empty( $business_geo_latitude ) && ! empty( $business_geo_longitude ) ) {
        $schema['geo'] = array(
            '@type' => 'GeoCoordinates',
            'latitude' => $business_geo_latitude,
            'longitude' => $business_geo_longitude,
        );
    }
    
    // Add opening hours if available
    if ( ! empty( $business_opening_hours ) ) {
        $schema['openingHoursSpecification'] = array();
        
        foreach ( $business_opening_hours as $opening_hour ) {
            $schema['openingHoursSpecification'][] = array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => $opening_hour['day'],
                'opens' => $opening_hour['opens'],
                'closes' => $opening_hour['closes'],
            );
        }
    }
    
    // Add price range if available
    if ( ! empty( $business_price_range ) ) {
        $schema['priceRange'] = $business_price_range;
    }
    
    return $schema;
}

/**
 * Get FAQ schema
 *
 * @param array $faqs FAQ items
 * @return array
 */
function aqualuxe_seo_get_faq_schema( $faqs ) {
    // Check if FAQs exist
    if ( empty( $faqs ) ) {
        return array();
    }
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array(),
    );
    
    // Add FAQ items
    foreach ( $faqs as $faq ) {
        $schema['mainEntity'][] = array(
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq['answer'],
            ),
        );
    }
    
    return $schema;
}

/**
 * Get how-to schema
 *
 * @param array $how_to How-to data
 * @return array
 */
function aqualuxe_seo_get_how_to_schema( $how_to ) {
    // Check if how-to data exists
    if ( empty( $how_to ) || empty( $how_to['name'] ) || empty( $how_to['steps'] ) ) {
        return array();
    }
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'HowTo',
        'name' => $how_to['name'],
    );
    
    // Add description if available
    if ( ! empty( $how_to['description'] ) ) {
        $schema['description'] = $how_to['description'];
    }
    
    // Add image if available
    if ( ! empty( $how_to['image'] ) ) {
        $schema['image'] = $how_to['image'];
    }
    
    // Add estimated time if available
    if ( ! empty( $how_to['estimated_time'] ) ) {
        $schema['estimatedCost'] = array(
            '@type' => 'Duration',
            'value' => $how_to['estimated_time'],
        );
    }
    
    // Add tools if available
    if ( ! empty( $how_to['tools'] ) ) {
        $schema['tool'] = array();
        
        foreach ( $how_to['tools'] as $tool ) {
            $schema['tool'][] = array(
                '@type' => 'HowToTool',
                'name' => $tool,
            );
        }
    }
    
    // Add materials if available
    if ( ! empty( $how_to['materials'] ) ) {
        $schema['supply'] = array();
        
        foreach ( $how_to['materials'] as $material ) {
            $schema['supply'][] = array(
                '@type' => 'HowToSupply',
                'name' => $material,
            );
        }
    }
    
    // Add steps
    $schema['step'] = array();
    
    foreach ( $how_to['steps'] as $index => $step ) {
        $step_schema = array(
            '@type' => 'HowToStep',
            'position' => $index + 1,
            'name' => $step['name'],
            'text' => $step['text'],
        );
        
        // Add image if available
        if ( ! empty( $step['image'] ) ) {
            $step_schema['image'] = $step['image'];
        }
        
        // Add URL if available
        if ( ! empty( $step['url'] ) ) {
            $step_schema['url'] = $step['url'];
        }
        
        $schema['step'][] = $step_schema;
    }
    
    return $schema;
}

/**
 * Get recipe schema
 *
 * @param array $recipe Recipe data
 * @return array
 */
function aqualuxe_seo_get_recipe_schema( $recipe ) {
    // Check if recipe data exists
    if ( empty( $recipe ) || empty( $recipe['name'] ) ) {
        return array();
    }
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Recipe',
        'name' => $recipe['name'],
    );
    
    // Add description if available
    if ( ! empty( $recipe['description'] ) ) {
        $schema['description'] = $recipe['description'];
    }
    
    // Add image if available
    if ( ! empty( $recipe['image'] ) ) {
        $schema['image'] = $recipe['image'];
    }
    
    // Add author if available
    if ( ! empty( $recipe['author'] ) ) {
        $schema['author'] = array(
            '@type' => 'Person',
            'name' => $recipe['author'],
        );
    }
    
    // Add date published if available
    if ( ! empty( $recipe['date_published'] ) ) {
        $schema['datePublished'] = $recipe['date_published'];
    }
    
    // Add prep time if available
    if ( ! empty( $recipe['prep_time'] ) ) {
        $schema['prepTime'] = $recipe['prep_time'];
    }
    
    // Add cook time if available
    if ( ! empty( $recipe['cook_time'] ) ) {
        $schema['cookTime'] = $recipe['cook_time'];
    }
    
    // Add total time if available
    if ( ! empty( $recipe['total_time'] ) ) {
        $schema['totalTime'] = $recipe['total_time'];
    }
    
    // Add keywords if available
    if ( ! empty( $recipe['keywords'] ) ) {
        $schema['keywords'] = $recipe['keywords'];
    }
    
    // Add recipe yield if available
    if ( ! empty( $recipe['yield'] ) ) {
        $schema['recipeYield'] = $recipe['yield'];
    }
    
    // Add recipe category if available
    if ( ! empty( $recipe['category'] ) ) {
        $schema['recipeCategory'] = $recipe['category'];
    }
    
    // Add recipe cuisine if available
    if ( ! empty( $recipe['cuisine'] ) ) {
        $schema['recipeCuisine'] = $recipe['cuisine'];
    }
    
    // Add nutrition if available
    if ( ! empty( $recipe['nutrition'] ) ) {
        $schema['nutrition'] = array(
            '@type' => 'NutritionInformation',
        );
        
        foreach ( $recipe['nutrition'] as $key => $value ) {
            $schema['nutrition'][ $key ] = $value;
        }
    }
    
    // Add ingredients if available
    if ( ! empty( $recipe['ingredients'] ) ) {
        $schema['recipeIngredient'] = $recipe['ingredients'];
    }
    
    // Add instructions if available
    if ( ! empty( $recipe['instructions'] ) ) {
        $schema['recipeInstructions'] = array();
        
        foreach ( $recipe['instructions'] as $index => $instruction ) {
            $schema['recipeInstructions'][] = array(
                '@type' => 'HowToStep',
                'position' => $index + 1,
                'text' => $instruction,
            );
        }
    }
    
    // Add video if available
    if ( ! empty( $recipe['video'] ) ) {
        $schema['video'] = array(
            '@type' => 'VideoObject',
            'name' => ! empty( $recipe['video']['name'] ) ? $recipe['video']['name'] : $recipe['name'],
            'description' => ! empty( $recipe['video']['description'] ) ? $recipe['video']['description'] : $recipe['description'],
            'thumbnailUrl' => $recipe['video']['thumbnail_url'],
            'contentUrl' => $recipe['video']['content_url'],
            'embedUrl' => ! empty( $recipe['video']['embed_url'] ) ? $recipe['video']['embed_url'] : '',
            'uploadDate' => $recipe['video']['upload_date'],
            'duration' => ! empty( $recipe['video']['duration'] ) ? $recipe['video']['duration'] : '',
        );
    }
    
    return $schema;
}

/**
 * Get job posting schema
 *
 * @param array $job_posting Job posting data
 * @return array
 */
function aqualuxe_seo_get_job_posting_schema( $job_posting ) {
    // Check if job posting data exists
    if ( empty( $job_posting ) || empty( $job_posting['title'] ) ) {
        return array();
    }
    
    // Build schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        'title' => $job_posting['title'],
    );
    
    // Add description if available
    if ( ! empty( $job_posting['description'] ) ) {
        $schema['description'] = $job_posting['description'];
    }
    
    // Add date posted if available
    if ( ! empty( $job_posting['date_posted'] ) ) {
        $schema['datePosted'] = $job_posting['date_posted'];
    }
    
    // Add valid through if available
    if ( ! empty( $job_posting['valid_through'] ) ) {
        $schema['validThrough'] = $job_posting['valid_through'];
    }
    
    // Add employment type if available
    if ( ! empty( $job_posting['employment_type'] ) ) {
        $schema['employmentType'] = $job_posting['employment_type'];
    }
    
    // Add hiring organization if available
    if ( ! empty( $job_posting['hiring_organization'] ) ) {
        $schema['hiringOrganization'] = array(
            '@type' => 'Organization',
            'name' => $job_posting['hiring_organization']['name'],
        );
        
        // Add logo if available
        if ( ! empty( $job_posting['hiring_organization']['logo'] ) ) {
            $schema['hiringOrganization']['logo'] = $job_posting['hiring_organization']['logo'];
        }
        
        // Add URL if available
        if ( ! empty( $job_posting['hiring_organization']['url'] ) ) {
            $schema['hiringOrganization']['url'] = $job_posting['hiring_organization']['url'];
        }
    }
    
    // Add job location if available
    if ( ! empty( $job_posting['job_location'] ) ) {
        $schema['jobLocation'] = array(
            '@type' => 'Place',
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => $job_posting['job_location']['street_address'],
                'addressLocality' => $job_posting['job_location']['address_locality'],
                'addressRegion' => $job_posting['job_location']['address_region'],
                'postalCode' => $job_posting['job_location']['postal_code'],
                'addressCountry' => $job_posting['job_location']['address_country'],
            ),
        );
    }
    
    // Add base salary if available
    if ( ! empty( $job_posting['base_salary'] ) ) {
        $schema['baseSalary'] = array(
            '@type' => 'MonetaryAmount',
            'currency' => $job_posting['base_salary']['currency'],
            'value' => array(
                '@type' => 'QuantitativeValue',
                'value' => $job_posting['base_salary']['value'],
                'unitText' => $job_posting['base_salary']['unit_text'],
            ),
        );
    }
    
    return $schema;
}