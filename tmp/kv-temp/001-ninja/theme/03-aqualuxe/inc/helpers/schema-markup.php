<?php
/**
 * Schema.org markup functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add schema markup to HTML tag
 */
function aqualuxe_html_schema() {
    $schema = 'https://schema.org/';
    
    // Check what type of page we're on
    if ( is_singular( 'product' ) ) {
        $type = 'Product';
    } elseif ( is_author() ) {
        $type = 'ProfilePage';
    } elseif ( is_search() ) {
        $type = 'SearchResultsPage';
    } elseif ( is_singular( 'post' ) ) {
        $type = 'Article';
    } elseif ( is_singular( 'page' ) ) {
        $type = 'WebPage';
    } elseif ( is_archive() ) {
        $type = 'CollectionPage';
    } else {
        $type = 'WebPage';
    }
    
    echo 'itemscope itemtype="' . esc_attr( $schema ) . esc_attr( $type ) . '"';
}

/**
 * Generate Organization schema
 */
function aqualuxe_organization_schema() {
    $schema = array(
        '@context'  => 'https://schema.org',
        '@type'     => 'Organization',
        'name'      => get_bloginfo( 'name' ),
        'url'       => home_url( '/' ),
        'logo'      => aqualuxe_get_logo_schema(),
    );
    
    // Add social profiles if available
    $social_profiles = aqualuxe_get_social_profiles();
    
    if ( ! empty( $social_profiles ) ) {
        $schema['sameAs'] = $social_profiles;
    }
    
    // Add contact information
    $schema['contactPoint'] = array(
        '@type'             => 'ContactPoint',
        'telephone'         => get_theme_mod( 'aqualuxe_contact_phone', '' ),
        'email'             => get_theme_mod( 'aqualuxe_contact_email', '' ),
        'contactType'       => 'customer service',
        'availableLanguage' => get_theme_mod( 'aqualuxe_contact_languages', array( 'English' ) ),
    );
    
    return $schema;
}

/**
 * Generate WebSite schema
 */
function aqualuxe_website_schema() {
    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'WebSite',
        'name'          => get_bloginfo( 'name' ),
        'description'   => get_bloginfo( 'description' ),
        'url'           => home_url( '/' ),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => home_url( '/' ) . '?s={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ),
    );
    
    return $schema;
}

/**
 * Generate Article schema for posts
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_article_schema( $post_id ) {
    $post = get_post( $post_id );
    
    if ( ! $post ) {
        return array();
    }
    
    $author_id = $post->post_author;
    $author = get_userdata( $author_id );
    
    $schema = array(
        '@context'         => 'https://schema.org',
        '@type'            => 'Article',
        'headline'         => get_the_title( $post_id ),
        'datePublished'    => get_the_date( 'c', $post_id ),
        'dateModified'     => get_the_modified_date( 'c', $post_id ),
        'author'           => array(
            '@type'  => 'Person',
            'name'   => $author->display_name,
            'url'    => get_author_posts_url( $author_id ),
        ),
        'publisher'        => array(
            '@type'  => 'Organization',
            'name'   => get_bloginfo( 'name' ),
            'logo'   => aqualuxe_get_logo_schema(),
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id'   => get_permalink( $post_id ),
        ),
    );
    
    // Add featured image if available
    if ( has_post_thumbnail( $post_id ) ) {
        $image_id = get_post_thumbnail_id( $post_id );
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
        $image_meta = wp_get_attachment_metadata( $image_id );
        
        $schema['image'] = array(
            '@type'  => 'ImageObject',
            'url'    => $image_url,
            'width'  => $image_meta['width'],
            'height' => $image_meta['height'],
        );
    }
    
    // Add article body
    $schema['articleBody'] = wp_strip_all_tags( $post->post_content );
    
    // Add article section and keywords
    $categories = get_the_category( $post_id );
    $tags = get_the_tags( $post_id );
    
    if ( ! empty( $categories ) ) {
        $category_names = wp_list_pluck( $categories, 'name' );
        $schema['articleSection'] = implode( ', ', $category_names );
    }
    
    if ( ! empty( $tags ) ) {
        $tag_names = wp_list_pluck( $tags, 'name' );
        $schema['keywords'] = implode( ', ', $tag_names );
    }
    
    return $schema;
}

/**
 * Generate Product schema for WooCommerce products
 *
 * @param int $product_id Product ID.
 */
function aqualuxe_product_schema( $product_id ) {
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return array();
    }
    
    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $product->get_name(),
        'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
        'sku'         => $product->get_sku(),
        'brand'       => array(
            '@type' => 'Brand',
            'name'  => get_bloginfo( 'name' ),
        ),
        'offers'      => array(
            '@type'         => 'Offer',
            'price'         => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url'           => get_permalink( $product_id ),
            'priceValidUntil' => date( 'Y-m-d', strtotime( '+1 year' ) ),
        ),
    );
    
    // Add product image
    if ( has_post_thumbnail( $product_id ) ) {
        $image_id = get_post_thumbnail_id( $product_id );
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
        $image_meta = wp_get_attachment_metadata( $image_id );
        
        $schema['image'] = array(
            '@type'  => 'ImageObject',
            'url'    => $image_url,
            'width'  => $image_meta['width'],
            'height' => $image_meta['height'],
        );
    }
    
    // Add product gallery images
    $gallery_image_ids = $product->get_gallery_image_ids();
    
    if ( ! empty( $gallery_image_ids ) ) {
        $additional_images = array();
        
        foreach ( $gallery_image_ids as $gallery_image_id ) {
            $gallery_image_url = wp_get_attachment_image_url( $gallery_image_id, 'full' );
            $additional_images[] = $gallery_image_url;
        }
        
        if ( ! empty( $additional_images ) ) {
            $schema['additionalProperty'] = array(
                '@type'     => 'PropertyValue',
                'name'      => 'additionalImages',
                'value'     => $additional_images,
            );
        }
    }
    
    // Add product categories
    $product_categories = get_the_terms( $product_id, 'product_cat' );
    
    if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
        $category_names = wp_list_pluck( $product_categories, 'name' );
        $schema['category'] = implode( ', ', $category_names );
    }
    
    // Add product rating
    if ( $product->get_rating_count() > 0 ) {
        $schema['aggregateRating'] = array(
            '@type'       => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
            'bestRating'  => '5',
            'worstRating' => '1',
        );
    }
    
    // Add product reviews
    $reviews = get_comments( array(
        'post_id' => $product_id,
        'status'  => 'approve',
        'type'    => 'review',
    ) );
    
    if ( ! empty( $reviews ) ) {
        $schema_reviews = array();
        
        foreach ( $reviews as $review ) {
            $rating = get_comment_meta( $review->comment_ID, 'rating', true );
            
            if ( $rating ) {
                $schema_reviews[] = array(
                    '@type'         => 'Review',
                    'reviewRating'  => array(
                        '@type'       => 'Rating',
                        'ratingValue' => $rating,
                        'bestRating'  => '5',
                        'worstRating' => '1',
                    ),
                    'author'        => array(
                        '@type' => 'Person',
                        'name'  => $review->comment_author,
                    ),
                    'datePublished' => get_comment_date( 'c', $review->comment_ID ),
                    'reviewBody'    => $review->comment_content,
                );
            }
        }
        
        if ( ! empty( $schema_reviews ) ) {
            $schema['review'] = $schema_reviews;
        }
    }
    
    return $schema;
}

/**
 * Generate BreadcrumbList schema
 */
function aqualuxe_breadcrumb_schema() {
    // Get breadcrumb items
    $breadcrumbs = aqualuxe_get_breadcrumbs();
    
    if ( empty( $breadcrumbs ) ) {
        return array();
    }
    
    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => array(),
    );
    
    $position = 1;
    
    foreach ( $breadcrumbs as $breadcrumb ) {
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
    
    return $schema;
}

/**
 * Get breadcrumb items
 *
 * @return array Breadcrumb items.
 */
function aqualuxe_get_breadcrumbs() {
    $breadcrumbs = array();
    
    // Add home
    $breadcrumbs[] = array(
        'text' => __( 'Home', 'aqualuxe' ),
        'url'  => home_url( '/' ),
    );
    
    // Add breadcrumb items based on page type
    if ( is_category() || is_single() ) {
        // Add category
        if ( is_category() ) {
            $breadcrumbs[] = array(
                'text' => single_cat_title( '', false ),
                'url'  => get_category_link( get_query_var( 'cat' ) ),
            );
        } elseif ( is_single() ) {
            $categories = get_the_category();
            
            if ( ! empty( $categories ) ) {
                $category = $categories[0];
                
                $breadcrumbs[] = array(
                    'text' => $category->name,
                    'url'  => get_category_link( $category->term_id ),
                );
            }
            
            // Add post title
            $breadcrumbs[] = array(
                'text' => get_the_title(),
                'url'  => get_permalink(),
            );
        }
    } elseif ( is_page() ) {
        // Check if page has parent
        if ( get_post_parent() ) {
            $parent_id = get_post_ancestor_id( get_the_ID(), 'page' );
            
            $breadcrumbs[] = array(
                'text' => get_the_title( $parent_id ),
                'url'  => get_permalink( $parent_id ),
            );
        }
        
        // Add page title
        $breadcrumbs[] = array(
            'text' => get_the_title(),
            'url'  => get_permalink(),
        );
    } elseif ( is_tag() ) {
        // Add tag name
        $breadcrumbs[] = array(
            'text' => single_tag_title( '', false ),
            'url'  => get_tag_link( get_query_var( 'tag_id' ) ),
        );
    } elseif ( is_author() ) {
        // Add author name
        $breadcrumbs[] = array(
            'text' => get_the_author(),
            'url'  => get_author_posts_url( get_the_author_meta( 'ID' ) ),
        );
    } elseif ( is_search() ) {
        // Add search query
        $breadcrumbs[] = array(
            'text' => __( 'Search Results for: ', 'aqualuxe' ) . get_search_query(),
            'url'  => home_url( '/?s=' . get_search_query() ),
        );
    } elseif ( is_404() ) {
        // Add 404 page
        $breadcrumbs[] = array(
            'text' => __( '404 Error', 'aqualuxe' ),
            'url'  => home_url( $_SERVER['REQUEST_URI'] ),
        );
    }
    
    // WooCommerce breadcrumbs
    if ( class_exists( 'WooCommerce' ) ) {
        if ( is_shop() ) {
            $breadcrumbs[] = array(
                'text' => __( 'Shop', 'aqualuxe' ),
                'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
            );
        } elseif ( is_product_category() ) {
            $breadcrumbs[] = array(
                'text' => __( 'Shop', 'aqualuxe' ),
                'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
            );
            
            $breadcrumbs[] = array(
                'text' => single_cat_title( '', false ),
                'url'  => get_term_link( get_queried_object() ),
            );
        } elseif ( is_product_tag() ) {
            $breadcrumbs[] = array(
                'text' => __( 'Shop', 'aqualuxe' ),
                'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
            );
            
            $breadcrumbs[] = array(
                'text' => single_tag_title( '', false ),
                'url'  => get_term_link( get_queried_object() ),
            );
        } elseif ( is_product() ) {
            $breadcrumbs[] = array(
                'text' => __( 'Shop', 'aqualuxe' ),
                'url'  => get_permalink( wc_get_page_id( 'shop' ) ),
            );
            
            $terms = wc_get_product_terms( get_the_ID(), 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
            
            if ( ! empty( $terms ) ) {
                $main_term = $terms[0];
                
                $breadcrumbs[] = array(
                    'text' => $main_term->name,
                    'url'  => get_term_link( $main_term ),
                );
            }
            
            $breadcrumbs[] = array(
                'text' => get_the_title(),
                'url'  => get_permalink(),
            );
        }
    }
    
    return $breadcrumbs;
}

/**
 * Get logo schema
 *
 * @return array Logo schema.
 */
function aqualuxe_get_logo_schema() {
    $logo_id = get_theme_mod( 'custom_logo' );
    
    if ( ! $logo_id ) {
        return array();
    }
    
    $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
    $logo_meta = wp_get_attachment_metadata( $logo_id );
    
    return array(
        '@type'  => 'ImageObject',
        'url'    => $logo_url,
        'width'  => $logo_meta['width'],
        'height' => $logo_meta['height'],
    );
}

/**
 * Get social profiles
 *
 * @return array Social profile URLs.
 */
function aqualuxe_get_social_profiles() {
    $social_profiles = array();
    
    $facebook = get_theme_mod( 'aqualuxe_social_facebook', '' );
    $twitter = get_theme_mod( 'aqualuxe_social_twitter', '' );
    $instagram = get_theme_mod( 'aqualuxe_social_instagram', '' );
    $linkedin = get_theme_mod( 'aqualuxe_social_linkedin', '' );
    $youtube = get_theme_mod( 'aqualuxe_social_youtube', '' );
    $pinterest = get_theme_mod( 'aqualuxe_social_pinterest', '' );
    
    if ( $facebook ) {
        $social_profiles[] = $facebook;
    }
    
    if ( $twitter ) {
        $social_profiles[] = $twitter;
    }
    
    if ( $instagram ) {
        $social_profiles[] = $instagram;
    }
    
    if ( $linkedin ) {
        $social_profiles[] = $linkedin;
    }
    
    if ( $youtube ) {
        $social_profiles[] = $youtube;
    }
    
    if ( $pinterest ) {
        $social_profiles[] = $pinterest;
    }
    
    return $social_profiles;
}

/**
 * Output schema markup
 *
 * @param array $schema Schema data.
 */
function aqualuxe_output_schema( $schema ) {
    if ( empty( $schema ) ) {
        return;
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}

/**
 * Add schema markup to head
 */
function aqualuxe_add_schema_to_head() {
    // Add organization schema
    aqualuxe_output_schema( aqualuxe_organization_schema() );
    
    // Add website schema
    aqualuxe_output_schema( aqualuxe_website_schema() );
    
    // Add breadcrumb schema
    aqualuxe_output_schema( aqualuxe_breadcrumb_schema() );
    
    // Add page-specific schema
    if ( is_singular( 'post' ) ) {
        aqualuxe_output_schema( aqualuxe_article_schema( get_the_ID() ) );
    } elseif ( is_singular( 'product' ) ) {
        aqualuxe_output_schema( aqualuxe_product_schema( get_the_ID() ) );
    }
}
add_action( 'wp_head', 'aqualuxe_add_schema_to_head' );