<?php
/**
 * Schema.org Markup for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Add schema.org markup to HTML tag
 */
function aqualuxe_html_schema() {
    $schema = 'https://schema.org/';
    
    // Check if search page
    if ( is_search() ) {
        $type = 'SearchResultsPage';
    } elseif ( is_author() ) {
        $type = 'ProfilePage';
    } elseif ( is_single() && 'post' === get_post_type() ) {
        $type = 'Article';
    } elseif ( is_page() ) {
        $type = 'WebPage';
    } elseif ( is_singular( 'product' ) ) {
        $type = 'Product';
    } elseif ( is_singular( 'service' ) ) {
        $type = 'Service';
    } elseif ( is_singular( 'event' ) ) {
        $type = 'Event';
    } elseif ( is_archive() || is_category() || is_tag() || is_tax() ) {
        $type = 'CollectionPage';
    } else {
        $type = 'WebPage';
    }
    
    echo 'itemscope itemtype="' . esc_attr( $schema ) . esc_attr( $type ) . '"';
}

/**
 * Add schema.org markup to head
 */
function aqualuxe_schema_head() {
    // Check if Yoast SEO is active and handling schema
    if ( class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'enable_schema_json_ld', true ) ) {
        return;
    }
    
    // Check if Rank Math is active and handling schema
    if ( class_exists( 'RankMath' ) ) {
        return;
    }
    
    // Check if Schema Pro is active
    if ( class_exists( 'BSF_AIOSRS_Pro_Schema_Template' ) ) {
        return;
    }
    
    // Organization schema
    $site_name = get_bloginfo( 'name' );
    $site_url = home_url( '/' );
    $site_logo = aqualuxe_get_site_logo_url();
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $site_name,
        'url' => $site_url,
    );
    
    if ( $site_logo ) {
        $schema['logo'] = $site_logo;
    }
    
    // Add social profiles if available
    $social_profiles = aqualuxe_get_social_profiles();
    if ( ! empty( $social_profiles ) ) {
        $schema['sameAs'] = $social_profiles;
    }
    
    // Add contact information if available
    $contact_info = aqualuxe_get_contact_info();
    if ( ! empty( $contact_info ) ) {
        $schema['contactPoint'] = array(
            '@type' => 'ContactPoint',
            'telephone' => isset( $contact_info['phone'] ) ? $contact_info['phone'] : '',
            'email' => isset( $contact_info['email'] ) ? $contact_info['email'] : '',
            'contactType' => 'customer service',
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
    
    // Website schema
    $website_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $site_name,
        'url' => $site_url,
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => $site_url . '?s={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ),
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode( $website_schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_schema_head' );

/**
 * Add schema.org markup for posts
 */
function aqualuxe_schema_article() {
    // Only add on single posts
    if ( ! is_singular( 'post' ) ) {
        return;
    }
    
    // Check if SEO plugins are handling schema
    if ( class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'enable_schema_json_ld', true ) ) {
        return;
    }
    
    if ( class_exists( 'RankMath' ) ) {
        return;
    }
    
    if ( class_exists( 'BSF_AIOSRS_Pro_Schema_Template' ) ) {
        return;
    }
    
    global $post;
    
    $author_id = $post->post_author;
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $author_url = get_author_posts_url( $author_id );
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'url' => get_permalink(),
        'datePublished' => get_the_date( 'c' ),
        'dateModified' => get_the_modified_date( 'c' ),
        'author' => array(
            '@type' => 'Person',
            'name' => $author_name,
            'url' => $author_url,
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => aqualuxe_get_site_logo_url(),
            ),
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink(),
        ),
    );
    
    // Add featured image if available
    if ( has_post_thumbnail() ) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
        $image_meta = wp_get_attachment_metadata( $image_id );
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
        );
        
        if ( isset( $image_meta['width'] ) && isset( $image_meta['height'] ) ) {
            $schema['image']['width'] = $image_meta['width'];
            $schema['image']['height'] = $image_meta['height'];
        }
    }
    
    // Add description if available
    $excerpt = get_the_excerpt();
    if ( ! empty( $excerpt ) ) {
        $schema['description'] = $excerpt;
    }
    
    // Add article body
    $content = get_the_content();
    if ( ! empty( $content ) ) {
        $schema['articleBody'] = wp_strip_all_tags( $content );
    }
    
    // Add article section and keywords
    $categories = get_the_category();
    if ( ! empty( $categories ) ) {
        $category_names = array();
        foreach ( $categories as $category ) {
            $category_names[] = $category->name;
        }
        $schema['articleSection'] = implode( ', ', $category_names );
    }
    
    $tags = get_the_tags();
    if ( ! empty( $tags ) ) {
        $tag_names = array();
        foreach ( $tags as $tag ) {
            $tag_names[] = $tag->name;
        }
        $schema['keywords'] = implode( ', ', $tag_names );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_schema_article' );

/**
 * Add schema.org markup for products
 */
function aqualuxe_schema_product() {
    // Only add on single products
    if ( ! is_singular( 'product' ) || ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Check if SEO plugins are handling schema
    if ( class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'enable_schema_json_ld', true ) ) {
        return;
    }
    
    if ( class_exists( 'RankMath' ) ) {
        return;
    }
    
    if ( class_exists( 'BSF_AIOSRS_Pro_Schema_Template' ) ) {
        return;
    }
    
    global $product;
    
    if ( ! is_object( $product ) ) {
        $product = wc_get_product( get_the_ID() );
    }
    
    if ( ! $product ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'url' => get_permalink(),
    );
    
    // Add description
    $description = $product->get_short_description() ? $product->get_short_description() : $product->get_description();
    if ( ! empty( $description ) ) {
        $schema['description'] = wp_strip_all_tags( $description );
    }
    
    // Add SKU
    $sku = $product->get_sku();
    if ( ! empty( $sku ) ) {
        $schema['sku'] = $sku;
    }
    
    // Add brand
    $brands = wp_get_post_terms( $product->get_id(), 'pa_brand' );
    if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
        $schema['brand'] = array(
            '@type' => 'Brand',
            'name' => $brands[0]->name,
        );
    }
    
    // Add image
    if ( $product->get_image_id() ) {
        $image_id = $product->get_image_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
        $image_meta = wp_get_attachment_metadata( $image_id );
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
        );
        
        if ( isset( $image_meta['width'] ) && isset( $image_meta['height'] ) ) {
            $schema['image']['width'] = $image_meta['width'];
            $schema['image']['height'] = $image_meta['height'];
        }
    }
    
    // Add offers
    if ( $product->is_in_stock() ) {
        $availability = 'https://schema.org/InStock';
    } else {
        $availability = 'https://schema.org/OutOfStock';
    }
    
    $schema['offers'] = array(
        '@type' => 'Offer',
        'price' => $product->get_price(),
        'priceCurrency' => get_woocommerce_currency(),
        'availability' => $availability,
        'url' => get_permalink(),
    );
    
    // Add review data
    if ( $product->get_review_count() > 0 ) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
        );
        
        // Add individual reviews
        $reviews = get_comments( array(
            'post_id' => $product->get_id(),
            'status' => 'approve',
            'type' => 'review',
        ) );
        
        if ( ! empty( $reviews ) ) {
            $schema_reviews = array();
            
            foreach ( $reviews as $review ) {
                $rating = get_comment_meta( $review->comment_ID, 'rating', true );
                
                if ( ! $rating ) {
                    continue;
                }
                
                $schema_reviews[] = array(
                    '@type' => 'Review',
                    'reviewRating' => array(
                        '@type' => 'Rating',
                        'ratingValue' => $rating,
                    ),
                    'author' => array(
                        '@type' => 'Person',
                        'name' => $review->comment_author,
                    ),
                    'reviewBody' => $review->comment_content,
                    'datePublished' => date( 'c', strtotime( $review->comment_date ) ),
                );
            }
            
            if ( ! empty( $schema_reviews ) ) {
                $schema['review'] = $schema_reviews;
            }
        }
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_schema_product' );

/**
 * Add schema.org markup for services
 */
function aqualuxe_schema_service() {
    // Only add on single services
    if ( ! is_singular( 'service' ) ) {
        return;
    }
    
    // Check if SEO plugins are handling schema
    if ( class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'enable_schema_json_ld', true ) ) {
        return;
    }
    
    if ( class_exists( 'RankMath' ) ) {
        return;
    }
    
    if ( class_exists( 'BSF_AIOSRS_Pro_Schema_Template' ) ) {
        return;
    }
    
    global $post;
    
    $service_price = get_post_meta( $post->ID, '_aqualuxe_service_price', true );
    $service_duration = get_post_meta( $post->ID, '_aqualuxe_service_duration', true );
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => get_the_title(),
        'url' => get_permalink(),
        'provider' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
            'url' => home_url( '/' ),
        ),
    );
    
    // Add description
    $excerpt = get_the_excerpt();
    if ( ! empty( $excerpt ) ) {
        $schema['description'] = $excerpt;
    }
    
    // Add service area
    $schema['areaServed'] = array(
        '@type' => 'GeoCircle',
        'geoMidpoint' => array(
            '@type' => 'GeoCoordinates',
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
        ),
        'geoRadius' => '50000',
    );
    
    // Add service type
    $categories = get_the_terms( $post->ID, 'service_category' );
    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
        $schema['serviceType'] = $categories[0]->name;
    }
    
    // Add offers
    if ( ! empty( $service_price ) ) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => preg_replace( '/[^0-9.]/', '', $service_price ),
            'priceCurrency' => get_woocommerce_currency(),
        );
    }
    
    // Add image
    if ( has_post_thumbnail() ) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
        $image_meta = wp_get_attachment_metadata( $image_id );
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
        );
        
        if ( isset( $image_meta['width'] ) && isset( $image_meta['height'] ) ) {
            $schema['image']['width'] = $image_meta['width'];
            $schema['image']['height'] = $image_meta['height'];
        }
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_schema_service' );

/**
 * Add schema.org markup for events
 */
function aqualuxe_schema_event() {
    // Only add on single events
    if ( ! is_singular( 'event' ) ) {
        return;
    }
    
    // Check if SEO plugins are handling schema
    if ( class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'enable_schema_json_ld', true ) ) {
        return;
    }
    
    if ( class_exists( 'RankMath' ) ) {
        return;
    }
    
    if ( class_exists( 'BSF_AIOSRS_Pro_Schema_Template' ) ) {
        return;
    }
    
    global $post;
    
    $event_date = get_post_meta( $post->ID, '_aqualuxe_event_date', true );
    $event_time = get_post_meta( $post->ID, '_aqualuxe_event_time', true );
    $event_location = get_post_meta( $post->ID, '_aqualuxe_event_location', true );
    $event_price = get_post_meta( $post->ID, '_aqualuxe_event_price', true );
    
    // Parse start and end time
    $start_time = '';
    $end_time = '';
    
    if ( ! empty( $event_time ) ) {
        $time_parts = explode( '-', $event_time );
        $start_time = isset( $time_parts[0] ) ? trim( $time_parts[0] ) : '';
        $end_time = isset( $time_parts[1] ) ? trim( $time_parts[1] ) : '';
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => get_the_title(),
        'url' => get_permalink(),
        'organizer' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
            'url' => home_url( '/' ),
        ),
    );
    
    // Add description
    $excerpt = get_the_excerpt();
    if ( ! empty( $excerpt ) ) {
        $schema['description'] = $excerpt;
    }
    
    // Add start date and end date
    if ( ! empty( $event_date ) ) {
        if ( ! empty( $start_time ) ) {
            $schema['startDate'] = date( 'c', strtotime( $event_date . ' ' . $start_time ) );
        } else {
            $schema['startDate'] = date( 'c', strtotime( $event_date ) );
        }
        
        if ( ! empty( $end_time ) ) {
            $schema['endDate'] = date( 'c', strtotime( $event_date . ' ' . $end_time ) );
        }
    }
    
    // Add location
    if ( ! empty( $event_location ) ) {
        $schema['location'] = array(
            '@type' => 'Place',
            'name' => $event_location,
            'address' => $event_location,
        );
    }
    
    // Add offers
    if ( ! empty( $event_price ) ) {
        $price = preg_replace( '/[^0-9.]/', '', $event_price );
        
        if ( ! empty( $price ) ) {
            $schema['offers'] = array(
                '@type' => 'Offer',
                'price' => $price,
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => 'https://schema.org/InStock',
                'url' => get_permalink(),
            );
        } elseif ( strtolower( $event_price ) === 'free' ) {
            $schema['offers'] = array(
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => 'https://schema.org/InStock',
                'url' => get_permalink(),
            );
        }
    }
    
    // Add image
    if ( has_post_thumbnail() ) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
        $image_meta = wp_get_attachment_metadata( $image_id );
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
        );
        
        if ( isset( $image_meta['width'] ) && isset( $image_meta['height'] ) ) {
            $schema['image']['width'] = $image_meta['width'];
            $schema['image']['height'] = $image_meta['height'];
        }
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_schema_event' );

/**
 * Add schema.org markup for breadcrumbs
 */
function aqualuxe_schema_breadcrumbs() {
    // Only add on singular pages
    if ( ! is_singular() ) {
        return;
    }
    
    // Check if SEO plugins are handling schema
    if ( class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'enable_schema_json_ld', true ) ) {
        return;
    }
    
    if ( class_exists( 'RankMath' ) ) {
        return;
    }
    
    if ( class_exists( 'BSF_AIOSRS_Pro_Schema_Template' ) ) {
        return;
    }
    
    global $post;
    
    $breadcrumbs = array();
    $breadcrumbs[] = array(
        '@type' => 'ListItem',
        'position' => 1,
        'item' => array(
            '@id' => home_url( '/' ),
            'name' => __( 'Home', 'aqualuxe' ),
        ),
    );
    
    $position = 2;
    
    // Add post type archive
    $post_type = get_post_type();
    $post_type_obj = get_post_type_object( $post_type );
    
    if ( $post_type_obj && $post_type !== 'post' && $post_type !== 'page' ) {
        $post_type_archive_link = get_post_type_archive_link( $post_type );
        
        if ( $post_type_archive_link ) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'item' => array(
                    '@id' => $post_type_archive_link,
                    'name' => $post_type_obj->labels->name,
                ),
            );
            
            $position++;
        }
    }
    
    // Add categories for posts
    if ( $post_type === 'post' ) {
        $categories = get_the_category();
        
        if ( ! empty( $categories ) ) {
            $category = $categories[0];
            
            // Add parent categories
            $parent_cats = array();
            $parent_id = $category->parent;
            
            while ( $parent_id ) {
                $parent_cat = get_term( $parent_id, 'category' );
                $parent_cats[] = $parent_cat;
                $parent_id = $parent_cat->parent;
            }
            
            $parent_cats = array_reverse( $parent_cats );
            
            foreach ( $parent_cats as $parent_cat ) {
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => array(
                        '@id' => get_term_link( $parent_cat ),
                        'name' => $parent_cat->name,
                    ),
                );
                
                $position++;
            }
            
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'item' => array(
                    '@id' => get_term_link( $category ),
                    'name' => $category->name,
                ),
            );
            
            $position++;
        }
    }
    
    // Add product categories for products
    if ( $post_type === 'product' && class_exists( 'WooCommerce' ) ) {
        $terms = get_the_terms( $post->ID, 'product_cat' );
        
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $term = $terms[0];
            
            // Add parent categories
            $parent_terms = array();
            $parent_id = $term->parent;
            
            while ( $parent_id ) {
                $parent_term = get_term( $parent_id, 'product_cat' );
                $parent_terms[] = $parent_term;
                $parent_id = $parent_term->parent;
            }
            
            $parent_terms = array_reverse( $parent_terms );
            
            foreach ( $parent_terms as $parent_term ) {
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => array(
                        '@id' => get_term_link( $parent_term ),
                        'name' => $parent_term->name,
                    ),
                );
                
                $position++;
            }
            
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'item' => array(
                    '@id' => get_term_link( $term ),
                    'name' => $term->name,
                ),
            );
            
            $position++;
        }
    }
    
    // Add current page
    $breadcrumbs[] = array(
        '@type' => 'ListItem',
        'position' => $position,
        'item' => array(
            '@id' => get_permalink(),
            'name' => get_the_title(),
        ),
    );
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs,
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_schema_breadcrumbs' );

/**
 * Add schema.org markup for FAQ page
 */
function aqualuxe_schema_faq() {
    // Only add on FAQ page template
    if ( ! is_page_template( 'templates/faq.php' ) ) {
        return;
    }
    
    // Check if SEO plugins are handling schema
    if ( class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'enable_schema_json_ld', true ) ) {
        return;
    }
    
    if ( class_exists( 'RankMath' ) ) {
        return;
    }
    
    if ( class_exists( 'BSF_AIOSRS_Pro_Schema_Template' ) ) {
        return;
    }
    
    // Get FAQs
    $args = array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    
    $faqs = get_posts( $args );
    
    if ( empty( $faqs ) ) {
        return;
    }
    
    $faq_items = array();
    
    foreach ( $faqs as $faq ) {
        $faq_items[] = array(
            '@type' => 'Question',
            'name' => $faq->post_title,
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => wp_strip_all_tags( $faq->post_content ),
            ),
        );
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faq_items,
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_schema_faq' );

/**
 * Add schema.org markup for contact page
 */
function aqualuxe_schema_contact() {
    // Only add on contact page template
    if ( ! is_page_template( 'templates/contact.php' ) ) {
        return;
    }
    
    // Check if SEO plugins are handling schema
    if ( class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'enable_schema_json_ld', true ) ) {
        return;
    }
    
    if ( class_exists( 'RankMath' ) ) {
        return;
    }
    
    if ( class_exists( 'BSF_AIOSRS_Pro_Schema_Template' ) ) {
        return;
    }
    
    $contact_info = aqualuxe_get_contact_info();
    
    if ( empty( $contact_info ) ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'url' => get_permalink(),
        'name' => get_the_title(),
        'description' => get_the_excerpt(),
    );
    
    // Add organization
    $schema['mainEntity'] = array(
        '@type' => 'Organization',
        'name' => get_bloginfo( 'name' ),
        'url' => home_url( '/' ),
    );
    
    // Add contact point
    if ( isset( $contact_info['phone'] ) || isset( $contact_info['email'] ) ) {
        $schema['mainEntity']['contactPoint'] = array(
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
        );
        
        if ( isset( $contact_info['phone'] ) ) {
            $schema['mainEntity']['contactPoint']['telephone'] = $contact_info['phone'];
        }
        
        if ( isset( $contact_info['email'] ) ) {
            $schema['mainEntity']['contactPoint']['email'] = $contact_info['email'];
        }
    }
    
    // Add address
    if ( isset( $contact_info['address'] ) ) {
        $schema['mainEntity']['address'] = array(
            '@type' => 'PostalAddress',
            'streetAddress' => $contact_info['address'],
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}
add_action( 'wp_head', 'aqualuxe_schema_contact' );

/**
 * Get site logo URL
 */
function aqualuxe_get_site_logo_url() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        return $logo_url;
    }
    
    // Check theme options
    $logo = aqualuxe_get_option( 'logo' );
    
    if ( $logo ) {
        return $logo;
    }
    
    return '';
}

/**
 * Get social profiles
 */
function aqualuxe_get_social_profiles() {
    $social_links = aqualuxe_get_option( 'social_links', array() );
    $profiles = array();
    
    if ( ! empty( $social_links ) ) {
        foreach ( $social_links as $network => $url ) {
            if ( ! empty( $url ) ) {
                $profiles[] = $url;
            }
        }
    }
    
    return $profiles;
}

/**
 * Get contact information
 */
function aqualuxe_get_contact_info() {
    $contact_info = array();
    
    $phone = aqualuxe_get_option( 'header_phone' );
    $email = aqualuxe_get_option( 'header_email' );
    $address = aqualuxe_get_option( 'header_address' );
    
    if ( $phone ) {
        $contact_info['phone'] = $phone;
    }
    
    if ( $email ) {
        $contact_info['email'] = $email;
    }
    
    if ( $address ) {
        $contact_info['address'] = $address;
    }
    
    return $contact_info;
}

/**
 * Add schema.org markup to comments
 */
function aqualuxe_schema_comments( $comment_text ) {
    if ( ! is_singular() ) {
        return $comment_text;
    }
    
    $comment_text = '<div itemprop="comment" itemscope itemtype="https://schema.org/Comment">' . $comment_text . '</div>';
    
    return $comment_text;
}
add_filter( 'comment_text', 'aqualuxe_schema_comments' );

/**
 * Add schema.org markup to comment author
 */
function aqualuxe_schema_comment_author( $author ) {
    if ( ! is_singular() ) {
        return $author;
    }
    
    $author = '<span itemprop="author" itemscope itemtype="https://schema.org/Person"><span itemprop="name">' . $author . '</span></span>';
    
    return $author;
}
add_filter( 'get_comment_author', 'aqualuxe_schema_comment_author' );

/**
 * Add schema.org markup to comment date
 */
function aqualuxe_schema_comment_date( $date ) {
    if ( ! is_singular() ) {
        return $date;
    }
    
    $date = '<time itemprop="datePublished" datetime="' . get_comment_date( 'c' ) . '">' . $date . '</time>';
    
    return $date;
}
add_filter( 'get_comment_date', 'aqualuxe_schema_comment_date' );