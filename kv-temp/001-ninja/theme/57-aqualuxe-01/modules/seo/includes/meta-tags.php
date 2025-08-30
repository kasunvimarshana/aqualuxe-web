<?php
/**
 * Meta Tags Implementation
 *
 * @package AquaLuxe
 * @subpackage Modules/SEO
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add meta tags to head
 *
 * @return void
 */
function aqualuxe_seo_add_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Get current post/page
    $post_id = get_queried_object_id();

    // Get meta title
    $meta_title = aqualuxe_get_meta_title( $post_id );

    // Get meta description
    $meta_description = aqualuxe_get_meta_description( $post_id );

    // Get meta keywords
    $meta_keywords = aqualuxe_get_meta_keywords( $post_id );

    // Output meta tags
    if ( $meta_title ) {
        echo '<meta name="title" content="' . esc_attr( $meta_title ) . '">' . "\n";
    }

    if ( $meta_description ) {
        echo '<meta name="description" content="' . esc_attr( $meta_description ) . '">' . "\n";
    }

    if ( $meta_keywords ) {
        echo '<meta name="keywords" content="' . esc_attr( $meta_keywords ) . '">' . "\n";
    }

    // Add viewport meta tag
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";

    // Add robots meta tag
    aqualuxe_seo_add_robots_meta_tag();

    // Add canonical URL
    aqualuxe_seo_add_canonical_url();

    // Add prev/next links for paginated content
    aqualuxe_seo_add_pagination_links();
}
add_action( 'wp_head', 'aqualuxe_seo_add_meta_tags', 1 );

/**
 * Add robots meta tag
 *
 * @return void
 */
function aqualuxe_seo_add_robots_meta_tag() {
    // Check if page should be noindexed
    if ( aqualuxe_is_noindex() ) {
        echo '<meta name="robots" content="noindex,follow">' . "\n";
        return;
    }

    // Default robots meta tag
    echo '<meta name="robots" content="index,follow">' . "\n";
}

/**
 * Add canonical URL
 *
 * @return void
 */
function aqualuxe_seo_add_canonical_url() {
    // Get canonical URL
    $canonical_url = aqualuxe_get_canonical_url();

    // Output canonical URL
    if ( $canonical_url ) {
        echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '">' . "\n";
    }
}

/**
 * Add pagination links
 *
 * @return void
 */
function aqualuxe_seo_add_pagination_links() {
    global $wp_query;

    // Check if we're on a paginated page
    if ( ! is_singular() && $wp_query->max_num_pages > 1 ) {
        $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
        
        // Add prev link
        if ( $paged > 1 ) {
            $prev_link = get_pagenum_link( $paged - 1 );
            echo '<link rel="prev" href="' . esc_url( $prev_link ) . '">' . "\n";
        }
        
        // Add next link
        if ( $paged < $wp_query->max_num_pages ) {
            $next_link = get_pagenum_link( $paged + 1 );
            echo '<link rel="next" href="' . esc_url( $next_link ) . '">' . "\n";
        }
    } elseif ( is_singular() && get_query_var( 'page' ) ) {
        // Check if we're on a paginated post/page
        $page = get_query_var( 'page' );
        $numpages = substr_count( $wp_query->post->post_content, '<!--nextpage-->' ) + 1;
        
        // Add prev link
        if ( $page > 1 ) {
            $prev_link = get_permalink() . ( $page - 1 === 1 ? '' : $page - 1 );
            echo '<link rel="prev" href="' . esc_url( $prev_link ) . '">' . "\n";
        }
        
        // Add next link
        if ( $page < $numpages ) {
            $next_link = get_permalink() . ( $page + 1 );
            echo '<link rel="next" href="' . esc_url( $next_link ) . '">' . "\n";
        }
    }
}

/**
 * Filter document title
 *
 * @param string $title Document title.
 * @return string
 */
function aqualuxe_seo_filter_document_title( $title ) {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return $title;
    }

    // Get current post/page
    $post_id = get_queried_object_id();

    // Get meta title
    $meta_title = aqualuxe_get_meta_title( $post_id );

    // Return meta title if available
    if ( $meta_title ) {
        return $meta_title;
    }

    return $title;
}
add_filter( 'document_title', 'aqualuxe_seo_filter_document_title' );

/**
 * Filter document title parts
 *
 * @param array $title_parts Document title parts.
 * @return array
 */
function aqualuxe_seo_filter_document_title_parts( $title_parts ) {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return $title_parts;
    }

    // Get current post/page
    $post_id = get_queried_object_id();

    // Get meta title
    $meta_title = aqualuxe_get_meta_title( $post_id );

    // Return meta title if available
    if ( $meta_title ) {
        $title_parts['title'] = $meta_title;
        unset( $title_parts['site'] );
        unset( $title_parts['tagline'] );
        unset( $title_parts['page'] );
    }

    return $title_parts;
}
add_filter( 'document_title_parts', 'aqualuxe_seo_filter_document_title_parts' );

/**
 * Filter document title separator
 *
 * @param string $separator Document title separator.
 * @return string
 */
function aqualuxe_seo_filter_document_title_separator( $separator ) {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return $separator;
    }

    // Return custom separator
    return '|';
}
add_filter( 'document_title_separator', 'aqualuxe_seo_filter_document_title_separator' );

/**
 * Add meta tags to login page
 *
 * @return void
 */
function aqualuxe_seo_login_meta_tags() {
    // Add noindex meta tag
    echo '<meta name="robots" content="noindex,nofollow">' . "\n";
}
add_action( 'login_head', 'aqualuxe_seo_login_meta_tags' );

/**
 * Add meta tags to admin pages
 *
 * @return void
 */
function aqualuxe_seo_admin_meta_tags() {
    // Add noindex meta tag
    echo '<meta name="robots" content="noindex,nofollow">' . "\n";
}
add_action( 'admin_head', 'aqualuxe_seo_admin_meta_tags' );

/**
 * Add meta tags to feed
 *
 * @return void
 */
function aqualuxe_seo_feed_meta_tags() {
    // Add noindex meta tag
    echo '<xhtml:meta xmlns:xhtml="http://www.w3.org/1999/xhtml" name="robots" content="noindex,nofollow" />' . "\n";
}
add_action( 'rss2_head', 'aqualuxe_seo_feed_meta_tags' );
add_action( 'atom_head', 'aqualuxe_seo_feed_meta_tags' );
add_action( 'rdf_header', 'aqualuxe_seo_feed_meta_tags' );

/**
 * Add meta description to RSS feed
 *
 * @param string $content Feed content.
 * @return string
 */
function aqualuxe_seo_rss_description( $content ) {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return $content;
    }

    // Get current post/page
    $post_id = get_the_ID();

    // Get meta description
    $meta_description = aqualuxe_get_meta_description( $post_id );

    // Return meta description if available
    if ( $meta_description ) {
        return $meta_description;
    }

    return $content;
}
add_filter( 'the_excerpt_rss', 'aqualuxe_seo_rss_description' );

/**
 * Add language attributes to html tag
 *
 * @param string $output Language attributes.
 * @return string
 */
function aqualuxe_seo_language_attributes( $output ) {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return $output;
    }

    // Add prefix for schema markup
    $output .= ' prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# article: https://ogp.me/ns/article#"';

    return $output;
}
add_filter( 'language_attributes', 'aqualuxe_seo_language_attributes' );

/**
 * Add meta description to excerpt
 *
 * @param string $excerpt Excerpt.
 * @return string
 */
function aqualuxe_seo_excerpt( $excerpt ) {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return $excerpt;
    }

    // Get current post/page
    $post_id = get_the_ID();

    // Get meta description
    $meta_description = get_post_meta( $post_id, '_aqualuxe_seo_description', true );

    // Return meta description if available
    if ( $meta_description ) {
        return $meta_description;
    }

    return $excerpt;
}
add_filter( 'get_the_excerpt', 'aqualuxe_seo_excerpt', 10, 1 );

/**
 * Add meta tags to attachment pages
 *
 * @return void
 */
function aqualuxe_seo_attachment_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on an attachment page
    if ( is_attachment() ) {
        // Add noindex meta tag
        echo '<meta name="robots" content="noindex,follow">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_attachment_meta_tags', 0 );

/**
 * Add meta tags to author pages
 *
 * @return void
 */
function aqualuxe_seo_author_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on an author page
    if ( is_author() ) {
        // Get author data
        $author = get_queried_object();
        
        // Add author meta tags
        echo '<meta name="author" content="' . esc_attr( $author->display_name ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_author_meta_tags', 1 );

/**
 * Add meta tags to date archives
 *
 * @return void
 */
function aqualuxe_seo_date_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on a date archive
    if ( is_date() ) {
        // Add date meta tags
        if ( is_year() ) {
            echo '<meta name="date" content="' . esc_attr( get_the_date( 'Y' ) ) . '">' . "\n";
        } elseif ( is_month() ) {
            echo '<meta name="date" content="' . esc_attr( get_the_date( 'Y-m' ) ) . '">' . "\n";
        } elseif ( is_day() ) {
            echo '<meta name="date" content="' . esc_attr( get_the_date( 'Y-m-d' ) ) . '">' . "\n";
        }
    }
}
add_action( 'wp_head', 'aqualuxe_seo_date_meta_tags', 1 );

/**
 * Add meta tags to singular posts
 *
 * @return void
 */
function aqualuxe_seo_singular_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on a singular post
    if ( is_singular() ) {
        // Get post data
        $post = get_queried_object();
        
        // Add published date meta tag
        echo '<meta name="published_date" content="' . esc_attr( get_the_date( 'c', $post ) ) . '">' . "\n";
        
        // Add modified date meta tag
        echo '<meta name="modified_date" content="' . esc_attr( get_the_modified_date( 'c', $post ) ) . '">' . "\n";
        
        // Add author meta tag
        $author = get_the_author_meta( 'display_name', $post->post_author );
        echo '<meta name="author" content="' . esc_attr( $author ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_singular_meta_tags', 1 );

/**
 * Add meta tags to search pages
 *
 * @return void
 */
function aqualuxe_seo_search_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on a search page
    if ( is_search() ) {
        // Add search meta tags
        echo '<meta name="robots" content="noindex,follow">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_search_meta_tags', 0 );

/**
 * Add meta tags to 404 pages
 *
 * @return void
 */
function aqualuxe_seo_404_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on a 404 page
    if ( is_404() ) {
        // Add 404 meta tags
        echo '<meta name="robots" content="noindex,follow">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_404_meta_tags', 0 );

/**
 * Add meta tags to paginated pages
 *
 * @return void
 */
function aqualuxe_seo_paginated_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on a paginated page
    if ( is_paged() ) {
        // Add paginated meta tags
        echo '<meta name="robots" content="noindex,follow">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_paginated_meta_tags', 0 );

/**
 * Add meta tags to category pages
 *
 * @return void
 */
function aqualuxe_seo_category_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on a category page
    if ( is_category() ) {
        // Get category data
        $category = get_queried_object();
        
        // Add category meta tags
        echo '<meta name="category" content="' . esc_attr( $category->name ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_category_meta_tags', 1 );

/**
 * Add meta tags to tag pages
 *
 * @return void
 */
function aqualuxe_seo_tag_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on a tag page
    if ( is_tag() ) {
        // Get tag data
        $tag = get_queried_object();
        
        // Add tag meta tags
        echo '<meta name="keywords" content="' . esc_attr( $tag->name ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_tag_meta_tags', 1 );

/**
 * Add meta tags to taxonomy pages
 *
 * @return void
 */
function aqualuxe_seo_taxonomy_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on a taxonomy page
    if ( is_tax() ) {
        // Get taxonomy data
        $term = get_queried_object();
        
        // Add taxonomy meta tags
        echo '<meta name="taxonomy" content="' . esc_attr( $term->taxonomy ) . '">' . "\n";
        echo '<meta name="term" content="' . esc_attr( $term->name ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_taxonomy_meta_tags', 1 );

/**
 * Add meta tags to post type archive pages
 *
 * @return void
 */
function aqualuxe_seo_post_type_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on a post type archive page
    if ( is_post_type_archive() ) {
        // Get post type data
        $post_type = get_queried_object();
        
        // Add post type meta tags
        echo '<meta name="post_type" content="' . esc_attr( $post_type->name ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_post_type_meta_tags', 1 );

/**
 * Add meta tags to home page
 *
 * @return void
 */
function aqualuxe_seo_home_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if we're on the home page
    if ( is_home() || is_front_page() ) {
        // Add home page meta tags
        echo '<meta name="application-name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_home_meta_tags', 1 );

/**
 * Add meta tags to WooCommerce pages
 *
 * @return void
 */
function aqualuxe_seo_woocommerce_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if WooCommerce is active
    if ( ! function_exists( 'is_woocommerce' ) ) {
        return;
    }

    // Check if we're on a WooCommerce page
    if ( is_woocommerce() ) {
        // Add WooCommerce meta tags
        if ( is_product() ) {
            // Get product data
            global $product;
            
            // Add product meta tags
            echo '<meta name="product:price:amount" content="' . esc_attr( $product->get_price() ) . '">' . "\n";
            echo '<meta name="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '">' . "\n";
            
            if ( $product->is_in_stock() ) {
                echo '<meta name="product:availability" content="in stock">' . "\n";
            } else {
                echo '<meta name="product:availability" content="out of stock">' . "\n";
            }
        }
    }
}
add_action( 'wp_head', 'aqualuxe_seo_woocommerce_meta_tags', 1 );

/**
 * Add meta tags to AMP pages
 *
 * @return void
 */
function aqualuxe_seo_amp_meta_tags() {
    // Check if meta tags are enabled
    if ( ! aqualuxe_is_meta_tags_enabled() ) {
        return;
    }

    // Check if AMP is active
    if ( ! function_exists( 'is_amp_endpoint' ) ) {
        return;
    }

    // Check if we're on an AMP page
    if ( is_amp_endpoint() ) {
        // Add AMP meta tags
        echo '<meta name="amp-version" content="v0">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_seo_amp_meta_tags', 1 );