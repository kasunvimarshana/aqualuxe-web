<?php
/**
 * AquaLuxe SEO Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/SEO
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Get meta title
 *
 * @return string
 */
function aqualuxe_seo_get_meta_title() {
    // Get post ID
    $post_id = get_queried_object_id();
    
    // Check if we're on a single post or page
    if ( is_singular() ) {
        // Get custom title
        $title = get_post_meta( $post_id, '_aqualuxe_seo_title', true );
        
        // If custom title exists, use it
        if ( ! empty( $title ) ) {
            return $title;
        }
        
        // Get post title
        return get_the_title( $post_id );
    }
    
    // Check if we're on the homepage
    if ( is_home() || is_front_page() ) {
        // Get custom title
        $title = get_option( 'aqualuxe_seo_home_title', '' );
        
        // If custom title exists, use it
        if ( ! empty( $title ) ) {
            return $title;
        }
        
        // Get site name
        return get_bloginfo( 'name' );
    }
    
    // Check if we're on a category or tag archive
    if ( is_category() || is_tag() || is_tax() ) {
        // Get term name
        $term = get_queried_object();
        if ( ! empty( $term->name ) ) {
            return $term->name;
        }
    }
    
    // Check if we're on a search page
    if ( is_search() ) {
        return sprintf(
            /* translators: %s: search query */
            __( 'Search Results for "%s"', 'aqualuxe' ),
            get_search_query()
        );
    }
    
    // Check if we're on an author page
    if ( is_author() ) {
        return sprintf(
            /* translators: %s: author name */
            __( 'Author: %s', 'aqualuxe' ),
            get_the_author_meta( 'display_name', get_queried_object_id() )
        );
    }
    
    // Check if we're on a date archive
    if ( is_date() ) {
        if ( is_day() ) {
            return sprintf(
                /* translators: %s: date */
                __( 'Day: %s', 'aqualuxe' ),
                get_the_date()
            );
        } elseif ( is_month() ) {
            return sprintf(
                /* translators: %s: date */
                __( 'Month: %s', 'aqualuxe' ),
                get_the_date( 'F Y' )
            );
        } elseif ( is_year() ) {
            return sprintf(
                /* translators: %s: date */
                __( 'Year: %s', 'aqualuxe' ),
                get_the_date( 'Y' )
            );
        }
    }
    
    // Default to site name
    return get_bloginfo( 'name' );
}

/**
 * Get meta description
 *
 * @return string
 */
function aqualuxe_seo_get_meta_description() {
    // Get post ID
    $post_id = get_queried_object_id();
    
    // Check if we're on a single post or page
    if ( is_singular() ) {
        // Get custom description
        $description = get_post_meta( $post_id, '_aqualuxe_seo_description', true );
        
        // If custom description exists, use it
        if ( ! empty( $description ) ) {
            return $description;
        }
        
        // Get post excerpt
        $post = get_post( $post_id );
        if ( ! empty( $post->post_excerpt ) ) {
            return $post->post_excerpt;
        }
        
        // Get post content
        $content = $post->post_content;
        
        // Strip shortcodes and tags
        $content = strip_shortcodes( $content );
        $content = wp_strip_all_tags( $content );
        
        // Trim to 160 characters
        $content = wp_trim_words( $content, 30, '...' );
        
        return $content;
    }
    
    // Check if we're on the homepage
    if ( is_home() || is_front_page() ) {
        // Get custom description
        $description = get_option( 'aqualuxe_seo_home_description', '' );
        
        // If custom description exists, use it
        if ( ! empty( $description ) ) {
            return $description;
        }
        
        // Get site description
        return get_bloginfo( 'description' );
    }
    
    // Check if we're on a category or tag archive
    if ( is_category() || is_tag() || is_tax() ) {
        // Get term description
        $term = get_queried_object();
        if ( ! empty( $term->description ) ) {
            return $term->description;
        }
    }
    
    // Default to site description
    return get_bloginfo( 'description' );
}

/**
 * Get meta keywords
 *
 * @return string
 */
function aqualuxe_seo_get_meta_keywords() {
    // Get post ID
    $post_id = get_queried_object_id();
    
    // Check if we're on a single post or page
    if ( is_singular() ) {
        // Get custom keywords
        $keywords = get_post_meta( $post_id, '_aqualuxe_seo_keywords', true );
        
        // If custom keywords exist, use them
        if ( ! empty( $keywords ) ) {
            return $keywords;
        }
        
        // Get post tags
        $tags = get_the_tags( $post_id );
        if ( ! empty( $tags ) ) {
            $tag_names = array();
            foreach ( $tags as $tag ) {
                $tag_names[] = $tag->name;
            }
            return implode( ', ', $tag_names );
        }
        
        // Get post categories
        $categories = get_the_category( $post_id );
        if ( ! empty( $categories ) ) {
            $category_names = array();
            foreach ( $categories as $category ) {
                $category_names[] = $category->name;
            }
            return implode( ', ', $category_names );
        }
    }
    
    // Check if we're on the homepage
    if ( is_home() || is_front_page() ) {
        // Get custom keywords
        $keywords = get_option( 'aqualuxe_seo_home_keywords', '' );
        
        // If custom keywords exist, use them
        if ( ! empty( $keywords ) ) {
            return $keywords;
        }
    }
    
    // Check if we're on a category or tag archive
    if ( is_category() || is_tag() || is_tax() ) {
        // Get term name
        $term = get_queried_object();
        if ( ! empty( $term->name ) ) {
            return $term->name;
        }
    }
    
    // Default to empty
    return '';
}

/**
 * Get robots meta
 *
 * @return string
 */
function aqualuxe_seo_get_robots_meta() {
    // Get post ID
    $post_id = get_queried_object_id();
    
    // Check if we're on a single post or page
    if ( is_singular() ) {
        // Get custom robots
        $robots = get_post_meta( $post_id, '_aqualuxe_seo_robots', true );
        
        // If custom robots exist, use them
        if ( ! empty( $robots ) ) {
            return $robots;
        }
    }
    
    // Check if we're on a search page
    if ( is_search() ) {
        return 'noindex,follow';
    }
    
    // Check if we're on an archive page
    if ( is_archive() ) {
        // Check if pagination is greater than 1
        if ( get_query_var( 'paged' ) > 1 ) {
            return 'noindex,follow';
        }
    }
    
    // Default to index,follow
    return 'index,follow';
}

/**
 * Get canonical URL
 *
 * @return string
 */
function aqualuxe_seo_get_canonical_url() {
    // Get post ID
    $post_id = get_queried_object_id();
    
    // Check if we're on a single post or page
    if ( is_singular() ) {
        // Get custom canonical URL
        $canonical = get_post_meta( $post_id, '_aqualuxe_seo_canonical', true );
        
        // If custom canonical URL exists, use it
        if ( ! empty( $canonical ) ) {
            return $canonical;
        }
        
        // Get permalink
        return get_permalink( $post_id );
    }
    
    // Check if we're on the homepage
    if ( is_home() || is_front_page() ) {
        return home_url( '/' );
    }
    
    // Check if we're on a category or tag archive
    if ( is_category() || is_tag() || is_tax() ) {
        return get_term_link( get_queried_object() );
    }
    
    // Check if we're on a search page
    if ( is_search() ) {
        return get_search_link( get_search_query() );
    }
    
    // Check if we're on an author page
    if ( is_author() ) {
        return get_author_posts_url( get_queried_object_id() );
    }
    
    // Check if we're on a date archive
    if ( is_date() ) {
        if ( is_day() ) {
            return get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
        } elseif ( is_month() ) {
            return get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
        } elseif ( is_year() ) {
            return get_year_link( get_query_var( 'year' ) );
        }
    }
    
    // Default to current URL
    global $wp;
    return home_url( $wp->request );
}

/**
 * Get featured image URL
 *
 * @param int $post_id Post ID
 * @param string $size Image size
 * @return string
 */
function aqualuxe_seo_get_featured_image_url( $post_id = null, $size = 'full' ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Check if post has featured image
    if ( has_post_thumbnail( $post_id ) ) {
        // Get featured image URL
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
        if ( ! empty( $image[0] ) ) {
            return $image[0];
        }
    }
    
    // Get default image
    $default_image = get_option( 'aqualuxe_seo_default_image', '' );
    if ( ! empty( $default_image ) ) {
        return $default_image;
    }
    
    // Default to empty
    return '';
}

/**
 * Get site logo URL
 *
 * @return string
 */
function aqualuxe_seo_get_site_logo_url() {
    // Check if site has custom logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        // Get logo URL
        $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( ! empty( $logo[0] ) ) {
            return $logo[0];
        }
    }
    
    // Default to empty
    return '';
}

/**
 * Get site icon URL
 *
 * @return string
 */
function aqualuxe_seo_get_site_icon_url() {
    // Check if site has site icon
    $site_icon_id = get_option( 'site_icon' );
    if ( $site_icon_id ) {
        // Get site icon URL
        $site_icon = wp_get_attachment_image_src( $site_icon_id, 'full' );
        if ( ! empty( $site_icon[0] ) ) {
            return $site_icon[0];
        }
    }
    
    // Default to empty
    return '';
}

/**
 * Get post author
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_seo_get_post_author( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post author ID
    $author_id = get_post_field( 'post_author', $post_id );
    
    // Get author data
    $author = array(
        'name' => get_the_author_meta( 'display_name', $author_id ),
        'url' => get_author_posts_url( $author_id ),
    );
    
    return $author;
}

/**
 * Get post date
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_seo_get_post_date( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post date
    $date = array(
        'published' => get_the_date( 'c', $post_id ),
        'modified' => get_the_modified_date( 'c', $post_id ),
    );
    
    return $date;
}

/**
 * Get post type
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_post_type( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post type
    $post_type = get_post_type( $post_id );
    
    // Get post type object
    $post_type_obj = get_post_type_object( $post_type );
    
    // Return post type label
    return $post_type_obj->labels->singular_name;
}

/**
 * Get site information
 *
 * @return array
 */
function aqualuxe_seo_get_site_info() {
    // Get site info
    $site_info = array(
        'name' => get_bloginfo( 'name' ),
        'description' => get_bloginfo( 'description' ),
        'url' => home_url( '/' ),
        'logo' => aqualuxe_seo_get_site_logo_url(),
        'icon' => aqualuxe_seo_get_site_icon_url(),
    );
    
    return $site_info;
}

/**
 * Get social media profiles
 *
 * @return array
 */
function aqualuxe_seo_get_social_profiles() {
    // Get social media profiles
    $social_profiles = array(
        'facebook' => get_option( 'aqualuxe_seo_facebook_page', '' ),
        'twitter' => 'https://twitter.com/' . get_option( 'aqualuxe_seo_twitter_username', '' ),
    );
    
    // Filter empty values
    $social_profiles = array_filter( $social_profiles );
    
    return $social_profiles;
}

/**
 * Get page number
 *
 * @return int
 */
function aqualuxe_seo_get_page_number() {
    // Get page number
    $page = get_query_var( 'paged' );
    if ( ! $page ) {
        $page = get_query_var( 'page' );
    }
    
    // Default to 1
    return $page ? $page : 1;
}

/**
 * Check if current page is paginated
 *
 * @return bool
 */
function aqualuxe_seo_is_paginated() {
    // Get page number
    $page = aqualuxe_seo_get_page_number();
    
    // Check if page is greater than 1
    return $page > 1;
}

/**
 * Get pagination links
 *
 * @return array
 */
function aqualuxe_seo_get_pagination_links() {
    // Get pagination links
    $links = array();
    
    // Get current URL
    $current_url = aqualuxe_seo_get_canonical_url();
    
    // Get page number
    $page = aqualuxe_seo_get_page_number();
    
    // Check if page is greater than 1
    if ( $page > 1 ) {
        // Add prev link
        $links['prev'] = add_query_arg( 'paged', $page - 1, $current_url );
        
        // Add first link
        $links['first'] = remove_query_arg( 'paged', $current_url );
    }
    
    // Check if there are more pages
    global $wp_query;
    if ( $page < $wp_query->max_num_pages ) {
        // Add next link
        $links['next'] = add_query_arg( 'paged', $page + 1, $current_url );
        
        // Add last link
        $links['last'] = add_query_arg( 'paged', $wp_query->max_num_pages, $current_url );
    }
    
    return $links;
}

/**
 * Get post categories
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_seo_get_post_categories( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post categories
    $categories = get_the_category( $post_id );
    
    // Format categories
    $formatted_categories = array();
    if ( ! empty( $categories ) ) {
        foreach ( $categories as $category ) {
            $formatted_categories[] = array(
                'name' => $category->name,
                'url' => get_category_link( $category->term_id ),
            );
        }
    }
    
    return $formatted_categories;
}

/**
 * Get post tags
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_seo_get_post_tags( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post tags
    $tags = get_the_tags( $post_id );
    
    // Format tags
    $formatted_tags = array();
    if ( ! empty( $tags ) ) {
        foreach ( $tags as $tag ) {
            $formatted_tags[] = array(
                'name' => $tag->name,
                'url' => get_tag_link( $tag->term_id ),
            );
        }
    }
    
    return $formatted_tags;
}

/**
 * Get post comments count
 *
 * @param int $post_id Post ID
 * @return int
 */
function aqualuxe_seo_get_post_comments_count( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get comments count
    $comments_count = get_comments_number( $post_id );
    
    return $comments_count;
}

/**
 * Get post excerpt
 *
 * @param int $post_id Post ID
 * @param int $length Excerpt length
 * @return string
 */
function aqualuxe_seo_get_post_excerpt( $post_id = null, $length = 55 ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post
    $post = get_post( $post_id );
    
    // Check if post has excerpt
    if ( ! empty( $post->post_excerpt ) ) {
        return $post->post_excerpt;
    }
    
    // Get post content
    $content = $post->post_content;
    
    // Strip shortcodes and tags
    $content = strip_shortcodes( $content );
    $content = wp_strip_all_tags( $content );
    
    // Trim to specified length
    $content = wp_trim_words( $content, $length, '...' );
    
    return $content;
}

/**
 * Get post content
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_post_content( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post
    $post = get_post( $post_id );
    
    // Get post content
    $content = $post->post_content;
    
    // Apply filters
    $content = apply_filters( 'the_content', $content );
    
    return $content;
}

/**
 * Get post images
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_seo_get_post_images( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post
    $post = get_post( $post_id );
    
    // Get post content
    $content = $post->post_content;
    
    // Get images from content
    $images = array();
    
    // Check if post has featured image
    if ( has_post_thumbnail( $post_id ) ) {
        // Get featured image URL
        $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
        if ( ! empty( $featured_image[0] ) ) {
            $images[] = $featured_image[0];
        }
    }
    
    // Get images from content
    preg_match_all( '/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $content, $matches );
    
    // Add images to array
    if ( ! empty( $matches[1] ) ) {
        $images = array_merge( $images, $matches[1] );
    }
    
    // Remove duplicates
    $images = array_unique( $images );
    
    return $images;
}

/**
 * Get post videos
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_seo_get_post_videos( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post
    $post = get_post( $post_id );
    
    // Get post content
    $content = $post->post_content;
    
    // Get videos from content
    $videos = array();
    
    // Get YouTube videos
    preg_match_all( '/<iframe[^>]+src=[\'"]([^\'"]+youtube[^\'"]+)[\'"][^>]*>/i', $content, $matches );
    
    // Add videos to array
    if ( ! empty( $matches[1] ) ) {
        $videos = array_merge( $videos, $matches[1] );
    }
    
    // Get Vimeo videos
    preg_match_all( '/<iframe[^>]+src=[\'"]([^\'"]+vimeo[^\'"]+)[\'"][^>]*>/i', $content, $matches );
    
    // Add videos to array
    if ( ! empty( $matches[1] ) ) {
        $videos = array_merge( $videos, $matches[1] );
    }
    
    // Remove duplicates
    $videos = array_unique( $videos );
    
    return $videos;
}

/**
 * Get post audio
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_seo_get_post_audio( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post
    $post = get_post( $post_id );
    
    // Get post content
    $content = $post->post_content;
    
    // Get audio from content
    $audio = array();
    
    // Get audio tags
    preg_match_all( '/<audio[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $content, $matches );
    
    // Add audio to array
    if ( ! empty( $matches[1] ) ) {
        $audio = array_merge( $audio, $matches[1] );
    }
    
    // Get audio shortcodes
    preg_match_all( '/\[audio[^\]]+src=[\'"]([^\'"]+)[\'"][^\]]*\]/i', $content, $matches );
    
    // Add audio to array
    if ( ! empty( $matches[1] ) ) {
        $audio = array_merge( $audio, $matches[1] );
    }
    
    // Remove duplicates
    $audio = array_unique( $audio );
    
    return $audio;
}

/**
 * Get post attachments
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_seo_get_post_attachments( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post attachments
    $attachments = get_attached_media( '', $post_id );
    
    // Format attachments
    $formatted_attachments = array();
    if ( ! empty( $attachments ) ) {
        foreach ( $attachments as $attachment ) {
            $formatted_attachments[] = array(
                'id' => $attachment->ID,
                'title' => $attachment->post_title,
                'url' => wp_get_attachment_url( $attachment->ID ),
                'type' => $attachment->post_mime_type,
            );
        }
    }
    
    return $formatted_attachments;
}

/**
 * Get post thumbnail
 *
 * @param int $post_id Post ID
 * @param string $size Image size
 * @return array
 */
function aqualuxe_seo_get_post_thumbnail( $post_id = null, $size = 'full' ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Check if post has featured image
    if ( has_post_thumbnail( $post_id ) ) {
        // Get featured image ID
        $thumbnail_id = get_post_thumbnail_id( $post_id );
        
        // Get featured image URL
        $thumbnail = wp_get_attachment_image_src( $thumbnail_id, $size );
        
        // Get featured image metadata
        $thumbnail_meta = wp_get_attachment_metadata( $thumbnail_id );
        
        // Format thumbnail
        $formatted_thumbnail = array(
            'id' => $thumbnail_id,
            'url' => $thumbnail[0],
            'width' => $thumbnail[1],
            'height' => $thumbnail[2],
            'alt' => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ),
            'caption' => wp_get_attachment_caption( $thumbnail_id ),
        );
        
        return $formatted_thumbnail;
    }
    
    // Default to empty
    return array();
}

/**
 * Get post URL
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_post_url( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post URL
    return get_permalink( $post_id );
}

/**
 * Get post title
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_post_title( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get custom title
    $title = get_post_meta( $post_id, '_aqualuxe_seo_title', true );
    
    // If custom title exists, use it
    if ( ! empty( $title ) ) {
        return $title;
    }
    
    // Get post title
    return get_the_title( $post_id );
}

/**
 * Get post type archive URL
 *
 * @param string $post_type Post type
 * @return string
 */
function aqualuxe_seo_get_post_type_archive_url( $post_type ) {
    // Get post type archive URL
    return get_post_type_archive_link( $post_type );
}

/**
 * Get post type archive title
 *
 * @param string $post_type Post type
 * @return string
 */
function aqualuxe_seo_get_post_type_archive_title( $post_type ) {
    // Get post type object
    $post_type_obj = get_post_type_object( $post_type );
    
    // Return post type label
    return $post_type_obj->labels->name;
}

/**
 * Get term URL
 *
 * @param int $term_id Term ID
 * @param string $taxonomy Taxonomy
 * @return string
 */
function aqualuxe_seo_get_term_url( $term_id, $taxonomy ) {
    // Get term URL
    return get_term_link( $term_id, $taxonomy );
}

/**
 * Get term title
 *
 * @param int $term_id Term ID
 * @param string $taxonomy Taxonomy
 * @return string
 */
function aqualuxe_seo_get_term_title( $term_id, $taxonomy ) {
    // Get term
    $term = get_term( $term_id, $taxonomy );
    
    // Return term name
    return $term->name;
}

/**
 * Get term description
 *
 * @param int $term_id Term ID
 * @param string $taxonomy Taxonomy
 * @return string
 */
function aqualuxe_seo_get_term_description( $term_id, $taxonomy ) {
    // Get term
    $term = get_term( $term_id, $taxonomy );
    
    // Return term description
    return $term->description;
}

/**
 * Get author URL
 *
 * @param int $author_id Author ID
 * @return string
 */
function aqualuxe_seo_get_author_url( $author_id ) {
    // Get author URL
    return get_author_posts_url( $author_id );
}

/**
 * Get author name
 *
 * @param int $author_id Author ID
 * @return string
 */
function aqualuxe_seo_get_author_name( $author_id ) {
    // Get author name
    return get_the_author_meta( 'display_name', $author_id );
}

/**
 * Get author description
 *
 * @param int $author_id Author ID
 * @return string
 */
function aqualuxe_seo_get_author_description( $author_id ) {
    // Get author description
    return get_the_author_meta( 'description', $author_id );
}

/**
 * Get author avatar
 *
 * @param int $author_id Author ID
 * @param int $size Avatar size
 * @return string
 */
function aqualuxe_seo_get_author_avatar( $author_id, $size = 96 ) {
    // Get author avatar
    return get_avatar_url( $author_id, array( 'size' => $size ) );
}

/**
 * Get search URL
 *
 * @param string $query Search query
 * @return string
 */
function aqualuxe_seo_get_search_url( $query ) {
    // Get search URL
    return get_search_link( $query );
}

/**
 * Get search title
 *
 * @param string $query Search query
 * @return string
 */
function aqualuxe_seo_get_search_title( $query ) {
    // Get search title
    return sprintf(
        /* translators: %s: search query */
        __( 'Search Results for "%s"', 'aqualuxe' ),
        $query
    );
}

/**
 * Get date archive URL
 *
 * @param int $year Year
 * @param int $month Month
 * @param int $day Day
 * @return string
 */
function aqualuxe_seo_get_date_archive_url( $year, $month = 0, $day = 0 ) {
    // Get date archive URL
    if ( $day > 0 ) {
        return get_day_link( $year, $month, $day );
    } elseif ( $month > 0 ) {
        return get_month_link( $year, $month );
    } else {
        return get_year_link( $year );
    }
}

/**
 * Get date archive title
 *
 * @param int $year Year
 * @param int $month Month
 * @param int $day Day
 * @return string
 */
function aqualuxe_seo_get_date_archive_title( $year, $month = 0, $day = 0 ) {
    // Get date archive title
    if ( $day > 0 ) {
        return sprintf(
            /* translators: %s: date */
            __( 'Day: %s', 'aqualuxe' ),
            date_i18n( get_option( 'date_format' ), strtotime( "$year-$month-$day" ) )
        );
    } elseif ( $month > 0 ) {
        return sprintf(
            /* translators: %s: date */
            __( 'Month: %s', 'aqualuxe' ),
            date_i18n( 'F Y', strtotime( "$year-$month-01" ) )
        );
    } else {
        return sprintf(
            /* translators: %s: date */
            __( 'Year: %s', 'aqualuxe' ),
            $year
        );
    }
}

/**
 * Get current URL
 *
 * @return string
 */
function aqualuxe_seo_get_current_url() {
    // Get current URL
    global $wp;
    return home_url( $wp->request );
}

/**
 * Get current page title
 *
 * @return string
 */
function aqualuxe_seo_get_current_page_title() {
    // Get current page title
    return wp_get_document_title();
}

/**
 * Get current page description
 *
 * @return string
 */
function aqualuxe_seo_get_current_page_description() {
    // Get current page description
    return aqualuxe_seo_get_meta_description();
}

/**
 * Get current page keywords
 *
 * @return string
 */
function aqualuxe_seo_get_current_page_keywords() {
    // Get current page keywords
    return aqualuxe_seo_get_meta_keywords();
}

/**
 * Get current page robots
 *
 * @return string
 */
function aqualuxe_seo_get_current_page_robots() {
    // Get current page robots
    return aqualuxe_seo_get_robots_meta();
}

/**
 * Get current page canonical URL
 *
 * @return string
 */
function aqualuxe_seo_get_current_page_canonical() {
    // Get current page canonical URL
    return aqualuxe_seo_get_canonical_url();
}

/**
 * Get current page type
 *
 * @return string
 */
function aqualuxe_seo_get_current_page_type() {
    // Get current page type
    if ( is_front_page() || is_home() ) {
        return 'website';
    } elseif ( is_singular() ) {
        return 'article';
    } elseif ( is_category() || is_tag() || is_tax() ) {
        return 'website';
    } elseif ( is_search() ) {
        return 'website';
    } elseif ( is_author() ) {
        return 'profile';
    } elseif ( is_date() ) {
        return 'website';
    } else {
        return 'website';
    }
}

/**
 * Get current page image
 *
 * @return string
 */
function aqualuxe_seo_get_current_page_image() {
    // Get current page image
    if ( is_singular() ) {
        return aqualuxe_seo_get_featured_image_url();
    } else {
        return get_option( 'aqualuxe_seo_default_image', '' );
    }
}

/**
 * Get current page locale
 *
 * @return string
 */
function aqualuxe_seo_get_current_page_locale() {
    // Get current page locale
    return get_locale();
}

/**
 * Get current page language
 *
 * @return string
 */
function aqualuxe_seo_get_current_page_language() {
    // Get current page language
    return substr( get_locale(), 0, 2 );
}

/**
 * Get current page author
 *
 * @return array
 */
function aqualuxe_seo_get_current_page_author() {
    // Get current page author
    if ( is_singular() ) {
        return aqualuxe_seo_get_post_author();
    } elseif ( is_author() ) {
        return array(
            'name' => aqualuxe_seo_get_author_name( get_queried_object_id() ),
            'url' => aqualuxe_seo_get_author_url( get_queried_object_id() ),
        );
    } else {
        return array();
    }
}

/**
 * Get current page date
 *
 * @return array
 */
function aqualuxe_seo_get_current_page_date() {
    // Get current page date
    if ( is_singular() ) {
        return aqualuxe_seo_get_post_date();
    } else {
        return array();
    }
}

/**
 * Get current page breadcrumbs
 *
 * @return array
 */
function aqualuxe_seo_get_current_page_breadcrumbs() {
    // Get current page breadcrumbs
    $breadcrumbs = array();
    
    // Add home
    $breadcrumbs[] = array(
        'name' => __( 'Home', 'aqualuxe' ),
        'url' => home_url( '/' ),
    );
    
    // Check if we're on a single post or page
    if ( is_singular() ) {
        // Get post type
        $post_type = get_post_type();
        
        // Check if post type has archive
        if ( $post_type !== 'page' && get_post_type_archive_link( $post_type ) ) {
            // Add post type archive
            $breadcrumbs[] = array(
                'name' => aqualuxe_seo_get_post_type_archive_title( $post_type ),
                'url' => aqualuxe_seo_get_post_type_archive_url( $post_type ),
            );
        }
        
        // Add post
        $breadcrumbs[] = array(
            'name' => get_the_title(),
            'url' => get_permalink(),
        );
    }
    
    // Check if we're on a category or tag archive
    if ( is_category() || is_tag() || is_tax() ) {
        // Get term
        $term = get_queried_object();
        
        // Add term
        $breadcrumbs[] = array(
            'name' => $term->name,
            'url' => get_term_link( $term ),
        );
    }
    
    // Check if we're on a search page
    if ( is_search() ) {
        // Add search
        $breadcrumbs[] = array(
            'name' => sprintf(
                /* translators: %s: search query */
                __( 'Search Results for "%s"', 'aqualuxe' ),
                get_search_query()
            ),
            'url' => get_search_link( get_search_query() ),
        );
    }
    
    // Check if we're on an author page
    if ( is_author() ) {
        // Add author
        $breadcrumbs[] = array(
            'name' => sprintf(
                /* translators: %s: author name */
                __( 'Author: %s', 'aqualuxe' ),
                get_the_author_meta( 'display_name', get_queried_object_id() )
            ),
            'url' => get_author_posts_url( get_queried_object_id() ),
        );
    }
    
    // Check if we're on a date archive
    if ( is_date() ) {
        if ( is_day() ) {
            // Add year
            $breadcrumbs[] = array(
                'name' => get_the_date( 'Y' ),
                'url' => get_year_link( get_query_var( 'year' ) ),
            );
            
            // Add month
            $breadcrumbs[] = array(
                'name' => get_the_date( 'F' ),
                'url' => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
            );
            
            // Add day
            $breadcrumbs[] = array(
                'name' => get_the_date( 'j' ),
                'url' => get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) ),
            );
        } elseif ( is_month() ) {
            // Add year
            $breadcrumbs[] = array(
                'name' => get_the_date( 'Y' ),
                'url' => get_year_link( get_query_var( 'year' ) ),
            );
            
            // Add month
            $breadcrumbs[] = array(
                'name' => get_the_date( 'F' ),
                'url' => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
            );
        } elseif ( is_year() ) {
            // Add year
            $breadcrumbs[] = array(
                'name' => get_the_date( 'Y' ),
                'url' => get_year_link( get_query_var( 'year' ) ),
            );
        }
    }
    
    return $breadcrumbs;
}