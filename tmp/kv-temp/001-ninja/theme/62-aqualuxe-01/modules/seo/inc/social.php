<?php
/**
 * AquaLuxe SEO Social Media Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/SEO
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Get social media meta tags
 *
 * @return string
 */
function aqualuxe_seo_get_social_meta_tags() {
    // Get Open Graph meta tags
    $open_graph = aqualuxe_seo_get_open_graph_meta_tags();
    
    // Get Twitter Card meta tags
    $twitter_card = aqualuxe_seo_get_twitter_card_meta_tags();
    
    // Combine meta tags
    $meta_tags = $open_graph . $twitter_card;
    
    return $meta_tags;
}

/**
 * Get Open Graph meta tags
 *
 * @return string
 */
function aqualuxe_seo_get_open_graph_meta_tags() {
    // Get Open Graph data
    $og_data = aqualuxe_seo_get_open_graph_data();
    
    // Check if Open Graph data exists
    if ( empty( $og_data ) ) {
        return '';
    }
    
    // Build meta tags
    $meta_tags = '';
    
    // Add meta tags
    foreach ( $og_data as $property => $content ) {
        if ( ! empty( $content ) ) {
            $meta_tags .= '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
        }
    }
    
    return $meta_tags;
}

/**
 * Get Twitter Card meta tags
 *
 * @return string
 */
function aqualuxe_seo_get_twitter_card_meta_tags() {
    // Get Twitter Card data
    $twitter_data = aqualuxe_seo_get_twitter_card_data();
    
    // Check if Twitter Card data exists
    if ( empty( $twitter_data ) ) {
        return '';
    }
    
    // Build meta tags
    $meta_tags = '';
    
    // Add meta tags
    foreach ( $twitter_data as $name => $content ) {
        if ( ! empty( $content ) ) {
            $meta_tags .= '<meta name="' . esc_attr( $name ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
        }
    }
    
    return $meta_tags;
}

/**
 * Get Open Graph data
 *
 * @return array
 */
function aqualuxe_seo_get_open_graph_data() {
    // Get page type
    $page_type = aqualuxe_seo_get_current_page_type();
    
    // Get page title
    $title = aqualuxe_seo_get_current_page_title();
    
    // Get page description
    $description = aqualuxe_seo_get_current_page_description();
    
    // Get page URL
    $url = aqualuxe_seo_get_current_url();
    
    // Get page image
    $image = aqualuxe_seo_get_current_page_image();
    
    // Get site name
    $site_name = get_bloginfo( 'name' );
    
    // Get locale
    $locale = aqualuxe_seo_get_current_page_locale();
    
    // Build Open Graph data
    $og_data = array(
        'og:type' => $page_type,
        'og:title' => $title,
        'og:description' => $description,
        'og:url' => $url,
        'og:site_name' => $site_name,
        'og:locale' => $locale,
    );
    
    // Add image if available
    if ( ! empty( $image ) ) {
        $og_data['og:image'] = $image;
    }
    
    // Add Facebook App ID if available
    $fb_app_id = get_option( 'aqualuxe_seo_facebook_app_id', '' );
    if ( ! empty( $fb_app_id ) ) {
        $og_data['fb:app_id'] = $fb_app_id;
    }
    
    // Add article data if page type is article
    if ( $page_type === 'article' && is_singular() ) {
        // Get post data
        $post_id = get_queried_object_id();
        $post_date = aqualuxe_seo_get_post_date( $post_id );
        $post_author = aqualuxe_seo_get_post_author( $post_id );
        
        // Add article data
        $og_data['article:published_time'] = $post_date['published'];
        $og_data['article:modified_time'] = $post_date['modified'];
        $og_data['article:author'] = $post_author['name'];
        
        // Add article section if available
        $categories = aqualuxe_seo_get_post_categories( $post_id );
        if ( ! empty( $categories ) ) {
            $og_data['article:section'] = $categories[0]['name'];
        }
        
        // Add article tags if available
        $tags = aqualuxe_seo_get_post_tags( $post_id );
        if ( ! empty( $tags ) ) {
            foreach ( $tags as $tag ) {
                $og_data['article:tag'] = $tag['name'];
            }
        }
    }
    
    // Add product data if page type is product
    if ( $page_type === 'product' && is_singular( 'product' ) && class_exists( 'WooCommerce' ) ) {
        // Get product
        $post_id = get_queried_object_id();
        $product = wc_get_product( $post_id );
        
        // Check if product exists
        if ( $product ) {
            // Add product data
            $og_data['product:price:amount'] = $product->get_price();
            $og_data['product:price:currency'] = get_woocommerce_currency();
            
            // Add product availability
            $og_data['product:availability'] = $product->is_in_stock() ? 'instock' : 'oos';
        }
    }
    
    return $og_data;
}

/**
 * Get Twitter Card data
 *
 * @return array
 */
function aqualuxe_seo_get_twitter_card_data() {
    // Get Twitter Card type
    $card_type = get_option( 'aqualuxe_seo_twitter_card_type', 'summary_large_image' );
    
    // Get page title
    $title = aqualuxe_seo_get_current_page_title();
    
    // Get page description
    $description = aqualuxe_seo_get_current_page_description();
    
    // Get page image
    $image = aqualuxe_seo_get_current_page_image();
    
    // Get Twitter username
    $twitter_username = get_option( 'aqualuxe_seo_twitter_username', '' );
    
    // Build Twitter Card data
    $twitter_data = array(
        'twitter:card' => $card_type,
        'twitter:title' => $title,
        'twitter:description' => $description,
    );
    
    // Add image if available
    if ( ! empty( $image ) ) {
        $twitter_data['twitter:image'] = $image;
    }
    
    // Add Twitter username if available
    if ( ! empty( $twitter_username ) ) {
        $twitter_data['twitter:site'] = '@' . $twitter_username;
        
        // Add Twitter creator if on a single post
        if ( is_singular() ) {
            $post_id = get_queried_object_id();
            $post_author = aqualuxe_seo_get_post_author( $post_id );
            
            // Check if author has Twitter username
            $author_twitter = get_the_author_meta( 'twitter', get_post_field( 'post_author', $post_id ) );
            if ( ! empty( $author_twitter ) ) {
                $twitter_data['twitter:creator'] = '@' . $author_twitter;
            } else {
                $twitter_data['twitter:creator'] = '@' . $twitter_username;
            }
        }
    }
    
    return $twitter_data;
}

/**
 * Get Facebook Open Graph image
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_facebook_image( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Check if post has featured image
    if ( has_post_thumbnail( $post_id ) ) {
        // Get featured image URL
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
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
 * Get Twitter Card image
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_twitter_image( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Check if post has featured image
    if ( has_post_thumbnail( $post_id ) ) {
        // Get featured image URL
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
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
 * Get Facebook Open Graph type
 *
 * @return string
 */
function aqualuxe_seo_get_facebook_type() {
    // Check if we're on a single post or page
    if ( is_singular( 'post' ) ) {
        return 'article';
    } elseif ( is_singular( 'product' ) && class_exists( 'WooCommerce' ) ) {
        return 'product';
    } elseif ( is_author() ) {
        return 'profile';
    } elseif ( is_front_page() || is_home() ) {
        return 'website';
    } else {
        return 'website';
    }
}

/**
 * Get Twitter Card type
 *
 * @return string
 */
function aqualuxe_seo_get_twitter_card_type() {
    // Get Twitter Card type
    $card_type = get_option( 'aqualuxe_seo_twitter_card_type', 'summary_large_image' );
    
    return $card_type;
}

/**
 * Get Facebook Open Graph title
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_facebook_title( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
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
 * Get Twitter Card title
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_twitter_title( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
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
 * Get Facebook Open Graph description
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_facebook_description( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
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
 * Get Twitter Card description
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_twitter_description( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
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
 * Get Facebook Open Graph URL
 *
 * @return string
 */
function aqualuxe_seo_get_facebook_url() {
    // Get canonical URL
    return aqualuxe_seo_get_canonical_url();
}

/**
 * Get Twitter Card URL
 *
 * @return string
 */
function aqualuxe_seo_get_twitter_url() {
    // Get canonical URL
    return aqualuxe_seo_get_canonical_url();
}

/**
 * Get Facebook Open Graph locale
 *
 * @return string
 */
function aqualuxe_seo_get_facebook_locale() {
    // Get locale
    $locale = get_locale();
    
    // Format locale for Facebook
    $locale = str_replace( '_', '-', $locale );
    
    return $locale;
}

/**
 * Get Twitter Card site
 *
 * @return string
 */
function aqualuxe_seo_get_twitter_site() {
    // Get Twitter username
    $twitter_username = get_option( 'aqualuxe_seo_twitter_username', '' );
    
    // Check if Twitter username exists
    if ( ! empty( $twitter_username ) ) {
        return '@' . $twitter_username;
    }
    
    // Default to empty
    return '';
}

/**
 * Get Twitter Card creator
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_seo_get_twitter_creator( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Check if we're on a single post or page
    if ( is_singular() ) {
        // Get post author ID
        $author_id = get_post_field( 'post_author', $post_id );
        
        // Get author Twitter username
        $author_twitter = get_the_author_meta( 'twitter', $author_id );
        
        // Check if author has Twitter username
        if ( ! empty( $author_twitter ) ) {
            return '@' . $author_twitter;
        }
    }
    
    // Default to site Twitter username
    $twitter_username = get_option( 'aqualuxe_seo_twitter_username', '' );
    
    // Check if Twitter username exists
    if ( ! empty( $twitter_username ) ) {
        return '@' . $twitter_username;
    }
    
    // Default to empty
    return '';
}

/**
 * Get Facebook App ID
 *
 * @return string
 */
function aqualuxe_seo_get_facebook_app_id() {
    // Get Facebook App ID
    $fb_app_id = get_option( 'aqualuxe_seo_facebook_app_id', '' );
    
    return $fb_app_id;
}

/**
 * Get Facebook Page URL
 *
 * @return string
 */
function aqualuxe_seo_get_facebook_page_url() {
    // Get Facebook Page URL
    $fb_page_url = get_option( 'aqualuxe_seo_facebook_page', '' );
    
    return $fb_page_url;
}

/**
 * Get Twitter username
 *
 * @return string
 */
function aqualuxe_seo_get_twitter_username() {
    // Get Twitter username
    $twitter_username = get_option( 'aqualuxe_seo_twitter_username', '' );
    
    return $twitter_username;
}

/**
 * Get social sharing links
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_seo_get_social_sharing_links( $post_id = null ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get post data
    $title = urlencode( aqualuxe_seo_get_post_title( $post_id ) );
    $url = urlencode( aqualuxe_seo_get_post_url( $post_id ) );
    $summary = urlencode( aqualuxe_seo_get_post_excerpt( $post_id, 15 ) );
    $image = urlencode( aqualuxe_seo_get_featured_image_url( $post_id ) );
    
    // Build sharing links
    $links = array(
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
        'twitter' => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
        'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title . '&summary=' . $summary,
        'pinterest' => 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $image . '&description=' . $title,
        'email' => 'mailto:?subject=' . $title . '&body=' . $summary . '%20' . $url,
    );
    
    return $links;
}

/**
 * Display social sharing buttons
 *
 * @param int $post_id Post ID
 * @param array $networks Social networks to display
 * @return string
 */
function aqualuxe_seo_display_social_sharing_buttons( $post_id = null, $networks = array( 'facebook', 'twitter', 'linkedin', 'pinterest', 'email' ) ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }
    
    // Get social sharing links
    $links = aqualuxe_seo_get_social_sharing_links( $post_id );
    
    // Build output
    $output = '<div class="aqualuxe-social-sharing">';
    $output .= '<span class="aqualuxe-social-sharing-title">' . __( 'Share:', 'aqualuxe' ) . '</span>';
    $output .= '<ul class="aqualuxe-social-sharing-buttons">';
    
    // Add buttons
    foreach ( $networks as $network ) {
        if ( isset( $links[ $network ] ) ) {
            $output .= '<li class="aqualuxe-social-sharing-' . $network . '">';
            $output .= '<a href="' . esc_url( $links[ $network ] ) . '" target="_blank" rel="nofollow noopener">';
            $output .= '<span class="aqualuxe-social-sharing-icon aqualuxe-social-sharing-icon-' . $network . '"></span>';
            $output .= '<span class="screen-reader-text">' . sprintf( __( 'Share on %s', 'aqualuxe' ), ucfirst( $network ) ) . '</span>';
            $output .= '</a>';
            $output .= '</li>';
        }
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Get social profile links
 *
 * @return array
 */
function aqualuxe_seo_get_social_profile_links() {
    // Get social profile links
    $links = array(
        'facebook' => get_option( 'aqualuxe_seo_facebook_page', '' ),
        'twitter' => 'https://twitter.com/' . get_option( 'aqualuxe_seo_twitter_username', '' ),
    );
    
    // Filter empty values
    $links = array_filter( $links );
    
    return $links;
}

/**
 * Display social profile links
 *
 * @param array $networks Social networks to display
 * @return string
 */
function aqualuxe_seo_display_social_profile_links( $networks = array( 'facebook', 'twitter' ) ) {
    // Get social profile links
    $links = aqualuxe_seo_get_social_profile_links();
    
    // Build output
    $output = '<div class="aqualuxe-social-profiles">';
    $output .= '<ul class="aqualuxe-social-profiles-list">';
    
    // Add links
    foreach ( $networks as $network ) {
        if ( isset( $links[ $network ] ) && ! empty( $links[ $network ] ) ) {
            $output .= '<li class="aqualuxe-social-profiles-' . $network . '">';
            $output .= '<a href="' . esc_url( $links[ $network ] ) . '" target="_blank" rel="nofollow noopener">';
            $output .= '<span class="aqualuxe-social-profiles-icon aqualuxe-social-profiles-icon-' . $network . '"></span>';
            $output .= '<span class="screen-reader-text">' . sprintf( __( 'Follow us on %s', 'aqualuxe' ), ucfirst( $network ) ) . '</span>';
            $output .= '</a>';
            $output .= '</li>';
        }
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}