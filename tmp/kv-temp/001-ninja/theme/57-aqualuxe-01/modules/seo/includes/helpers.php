<?php
/**
 * SEO Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/SEO
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Check if meta tags are enabled
 *
 * @return bool
 */
function aqualuxe_is_meta_tags_enabled() {
    $seo_module = aqualuxe_get_module( 'seo' );
    
    if ( $seo_module && isset( $seo_module->settings['enable_meta_tags'] ) ) {
        return $seo_module->settings['enable_meta_tags'];
    }
    
    return false;
}

/**
 * Check if schema markup is enabled
 *
 * @return bool
 */
function aqualuxe_is_schema_markup_enabled() {
    $seo_module = aqualuxe_get_module( 'seo' );
    
    if ( $seo_module && isset( $seo_module->settings['enable_schema_markup'] ) ) {
        return $seo_module->settings['enable_schema_markup'];
    }
    
    return false;
}

/**
 * Check if sitemap is enabled
 *
 * @return bool
 */
function aqualuxe_is_sitemap_enabled() {
    $seo_module = aqualuxe_get_module( 'seo' );
    
    if ( $seo_module && isset( $seo_module->settings['enable_sitemap'] ) ) {
        return $seo_module->settings['enable_sitemap'];
    }
    
    return false;
}

/**
 * Check if breadcrumbs are enabled
 *
 * @return bool
 */
function aqualuxe_is_breadcrumbs_enabled() {
    $seo_module = aqualuxe_get_module( 'seo' );
    
    if ( $seo_module && isset( $seo_module->settings['enable_breadcrumbs'] ) ) {
        return $seo_module->settings['enable_breadcrumbs'];
    }
    
    return false;
}

/**
 * Check if social meta is enabled
 *
 * @return bool
 */
function aqualuxe_is_social_meta_enabled() {
    $seo_module = aqualuxe_get_module( 'seo' );
    
    if ( $seo_module && isset( $seo_module->settings['enable_social_meta'] ) ) {
        return $seo_module->settings['enable_social_meta'];
    }
    
    return false;
}

/**
 * Get meta title
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aqualuxe_get_meta_title( $post_id = 0 ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    // Get custom meta title
    $meta_title = get_post_meta( $post_id, '_aqualuxe_seo_title', true );

    // If custom meta title is set, return it
    if ( $meta_title ) {
        return $meta_title;
    }

    // Generate default meta title
    if ( is_home() || is_front_page() ) {
        // Home page
        $meta_title = get_bloginfo( 'name' );
        $tagline = get_bloginfo( 'description' );
        
        if ( $tagline ) {
            $meta_title .= ' - ' . $tagline;
        }
    } elseif ( is_singular() ) {
        // Single post or page
        $meta_title = get_the_title( $post_id ) . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_category() ) {
        // Category archive
        $meta_title = single_cat_title( '', false ) . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_tag() ) {
        // Tag archive
        $meta_title = single_tag_title( '', false ) . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_author() ) {
        // Author archive
        $meta_title = get_the_author() . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_year() ) {
        // Year archive
        $meta_title = get_the_date( 'Y' ) . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_month() ) {
        // Month archive
        $meta_title = get_the_date( 'F Y' ) . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_day() ) {
        // Day archive
        $meta_title = get_the_date() . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_tax() ) {
        // Custom taxonomy archive
        $meta_title = single_term_title( '', false ) . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_post_type_archive() ) {
        // Custom post type archive
        $meta_title = post_type_archive_title( '', false ) . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_search() ) {
        // Search results
        $meta_title = sprintf( __( 'Search Results for "%s"', 'aqualuxe' ), get_search_query() ) . ' - ' . get_bloginfo( 'name' );
    } elseif ( is_404() ) {
        // 404 page
        $meta_title = __( 'Page Not Found', 'aqualuxe' ) . ' - ' . get_bloginfo( 'name' );
    } else {
        // Fallback
        $meta_title = get_bloginfo( 'name' );
    }

    // Filter meta title
    return apply_filters( 'aqualuxe_meta_title', $meta_title, $post_id );
}

/**
 * Get meta description
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aqualuxe_get_meta_description( $post_id = 0 ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    // Get custom meta description
    $meta_description = get_post_meta( $post_id, '_aqualuxe_seo_description', true );

    // If custom meta description is set, return it
    if ( $meta_description ) {
        return $meta_description;
    }

    // Generate default meta description
    if ( is_home() || is_front_page() ) {
        // Home page
        $meta_description = get_bloginfo( 'description' );
    } elseif ( is_singular() ) {
        // Single post or page
        $post = get_post( $post_id );
        
        if ( $post ) {
            // Try to get excerpt
            $excerpt = get_the_excerpt( $post );
            
            if ( $excerpt ) {
                $meta_description = $excerpt;
            } else {
                // Get post content
                $content = $post->post_content;
                
                // Strip shortcodes
                $content = strip_shortcodes( $content );
                
                // Strip HTML
                $content = wp_strip_all_tags( $content );
                
                // Trim to 160 characters
                $meta_description = wp_trim_words( $content, 25, '...' );
            }
        }
    } elseif ( is_category() ) {
        // Category archive
        $meta_description = strip_tags( category_description() );
        
        if ( ! $meta_description ) {
            $meta_description = sprintf( __( 'Archive of posts filed under the %s category', 'aqualuxe' ), single_cat_title( '', false ) );
        }
    } elseif ( is_tag() ) {
        // Tag archive
        $meta_description = strip_tags( tag_description() );
        
        if ( ! $meta_description ) {
            $meta_description = sprintf( __( 'Archive of posts tagged with %s', 'aqualuxe' ), single_tag_title( '', false ) );
        }
    } elseif ( is_author() ) {
        // Author archive
        $meta_description = sprintf( __( 'Archive of posts by %s', 'aqualuxe' ), get_the_author() );
    } elseif ( is_year() ) {
        // Year archive
        $meta_description = sprintf( __( 'Archive of posts published in %s', 'aqualuxe' ), get_the_date( 'Y' ) );
    } elseif ( is_month() ) {
        // Month archive
        $meta_description = sprintf( __( 'Archive of posts published in %s', 'aqualuxe' ), get_the_date( 'F Y' ) );
    } elseif ( is_day() ) {
        // Day archive
        $meta_description = sprintf( __( 'Archive of posts published on %s', 'aqualuxe' ), get_the_date() );
    } elseif ( is_tax() ) {
        // Custom taxonomy archive
        $term = get_queried_object();
        
        if ( $term && ! is_wp_error( $term ) ) {
            $meta_description = strip_tags( term_description( $term->term_id, $term->taxonomy ) );
            
            if ( ! $meta_description ) {
                $meta_description = sprintf( __( 'Archive of posts filed under the %s taxonomy', 'aqualuxe' ), single_term_title( '', false ) );
            }
        }
    } elseif ( is_post_type_archive() ) {
        // Custom post type archive
        $post_type = get_queried_object();
        
        if ( $post_type && ! is_wp_error( $post_type ) ) {
            $meta_description = $post_type->description;
            
            if ( ! $meta_description ) {
                $meta_description = sprintf( __( 'Archive of %s', 'aqualuxe' ), post_type_archive_title( '', false ) );
            }
        }
    } elseif ( is_search() ) {
        // Search results
        $meta_description = sprintf( __( 'Search results for "%s"', 'aqualuxe' ), get_search_query() );
    } elseif ( is_404() ) {
        // 404 page
        $meta_description = __( 'The page you were looking for could not be found.', 'aqualuxe' );
    } else {
        // Fallback
        $meta_description = get_bloginfo( 'description' );
    }

    // Filter meta description
    return apply_filters( 'aqualuxe_meta_description', $meta_description, $post_id );
}

/**
 * Get meta keywords
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aqualuxe_get_meta_keywords( $post_id = 0 ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    // Get custom meta keywords
    $meta_keywords = get_post_meta( $post_id, '_aqualuxe_seo_keywords', true );

    // If custom meta keywords are set, return them
    if ( $meta_keywords ) {
        return $meta_keywords;
    }

    // Generate default meta keywords
    if ( is_singular() ) {
        // Get tags
        $tags = get_the_tags( $post_id );
        
        if ( $tags ) {
            $keywords = array();
            
            foreach ( $tags as $tag ) {
                $keywords[] = $tag->name;
            }
            
            $meta_keywords = implode( ', ', $keywords );
        }
        
        // Get categories
        if ( ! $meta_keywords ) {
            $categories = get_the_category( $post_id );
            
            if ( $categories ) {
                $keywords = array();
                
                foreach ( $categories as $category ) {
                    $keywords[] = $category->name;
                }
                
                $meta_keywords = implode( ', ', $keywords );
            }
        }
    } elseif ( is_category() ) {
        // Category archive
        $meta_keywords = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        // Tag archive
        $meta_keywords = single_tag_title( '', false );
    } elseif ( is_tax() ) {
        // Custom taxonomy archive
        $meta_keywords = single_term_title( '', false );
    }

    // Filter meta keywords
    return apply_filters( 'aqualuxe_meta_keywords', $meta_keywords, $post_id );
}

/**
 * Get canonical URL
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aqualuxe_get_canonical_url( $post_id = 0 ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    // Get custom canonical URL
    $canonical_url = get_post_meta( $post_id, '_aqualuxe_seo_canonical', true );

    // If custom canonical URL is set, return it
    if ( $canonical_url ) {
        return $canonical_url;
    }

    // Generate default canonical URL
    if ( is_home() || is_front_page() ) {
        // Home page
        $canonical_url = home_url( '/' );
    } elseif ( is_singular() ) {
        // Single post or page
        $canonical_url = get_permalink( $post_id );
    } elseif ( is_category() ) {
        // Category archive
        $canonical_url = get_category_link( get_queried_object_id() );
    } elseif ( is_tag() ) {
        // Tag archive
        $canonical_url = get_tag_link( get_queried_object_id() );
    } elseif ( is_author() ) {
        // Author archive
        $canonical_url = get_author_posts_url( get_queried_object_id() );
    } elseif ( is_year() ) {
        // Year archive
        $canonical_url = get_year_link( get_query_var( 'year' ) );
    } elseif ( is_month() ) {
        // Month archive
        $canonical_url = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
    } elseif ( is_day() ) {
        // Day archive
        $canonical_url = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
    } elseif ( is_tax() ) {
        // Custom taxonomy archive
        $term = get_queried_object();
        
        if ( $term && ! is_wp_error( $term ) ) {
            $canonical_url = get_term_link( $term );
        }
    } elseif ( is_post_type_archive() ) {
        // Custom post type archive
        $canonical_url = get_post_type_archive_link( get_queried_object()->name );
    } elseif ( is_search() ) {
        // Search results
        $canonical_url = get_search_link( get_search_query() );
    } else {
        // Fallback
        $canonical_url = home_url( $_SERVER['REQUEST_URI'] );
    }

    // Filter canonical URL
    return apply_filters( 'aqualuxe_canonical_url', $canonical_url, $post_id );
}

/**
 * Check if page should be noindexed
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function aqualuxe_is_noindex( $post_id = 0 ) {
    $seo_module = aqualuxe_get_module( 'seo' );
    
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    // Check custom noindex setting
    $noindex = get_post_meta( $post_id, '_aqualuxe_seo_noindex', true );
    
    if ( $noindex ) {
        return true;
    }

    // Check global settings
    if ( $seo_module ) {
        // Check archives
        if ( isset( $seo_module->settings['noindex_archives'] ) && $seo_module->settings['noindex_archives'] && ( is_category() || is_tag() || is_author() || is_date() ) ) {
            return true;
        }
        
        // Check search
        if ( isset( $seo_module->settings['noindex_search'] ) && $seo_module->settings['noindex_search'] && is_search() ) {
            return true;
        }
    }
    
    return false;
}

/**
 * Get featured image URL
 *
 * @param int    $post_id Post ID.
 * @param string $size Image size.
 * @return string
 */
function aqualuxe_get_featured_image_url( $post_id = 0, $size = 'full' ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    // Get featured image URL
    $image_url = '';
    
    if ( has_post_thumbnail( $post_id ) ) {
        $image_url = get_the_post_thumbnail_url( $post_id, $size );
    }
    
    return $image_url;
}

/**
 * Get Open Graph meta
 *
 * @param int $post_id Post ID.
 * @return array
 */
function aqualuxe_get_open_graph_meta( $post_id = 0 ) {
    $seo_module = aqualuxe_get_module( 'seo' );
    
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    // Initialize Open Graph meta
    $og_meta = array();

    // Set Open Graph type
    if ( is_singular() ) {
        $og_meta['og:type'] = 'article';
    } else {
        $og_meta['og:type'] = 'website';
    }

    // Set Open Graph site name
    $og_meta['og:site_name'] = get_bloginfo( 'name' );

    // Set Open Graph URL
    $og_meta['og:url'] = aqualuxe_get_canonical_url( $post_id );

    // Set Open Graph title
    $og_title = get_post_meta( $post_id, '_aqualuxe_og_title', true );
    
    if ( ! $og_title ) {
        $og_title = aqualuxe_get_meta_title( $post_id );
    }
    
    $og_meta['og:title'] = $og_title;

    // Set Open Graph description
    $og_description = get_post_meta( $post_id, '_aqualuxe_og_description', true );
    
    if ( ! $og_description ) {
        $og_description = aqualuxe_get_meta_description( $post_id );
    }
    
    $og_meta['og:description'] = $og_description;

    // Set Open Graph image
    $og_image = get_post_meta( $post_id, '_aqualuxe_og_image', true );
    
    if ( ! $og_image ) {
        $og_image = aqualuxe_get_featured_image_url( $post_id, 'large' );
    }
    
    if ( ! $og_image && $seo_module && isset( $seo_module->settings['default_facebook_image'] ) ) {
        $og_image = $seo_module->settings['default_facebook_image'];
    }
    
    if ( $og_image ) {
        $og_meta['og:image'] = $og_image;
        
        // Get image dimensions
        $image_id = attachment_url_to_postid( $og_image );
        
        if ( $image_id ) {
            $image_data = wp_get_attachment_image_src( $image_id, 'full' );
            
            if ( $image_data ) {
                $og_meta['og:image:width'] = $image_data[1];
                $og_meta['og:image:height'] = $image_data[2];
            }
        }
    }

    // Set Facebook app ID
    if ( $seo_module && isset( $seo_module->settings['facebook_app_id'] ) && $seo_module->settings['facebook_app_id'] ) {
        $og_meta['fb:app_id'] = $seo_module->settings['facebook_app_id'];
    }

    // Set article meta for singular posts
    if ( is_singular( 'post' ) ) {
        // Set article published time
        $og_meta['article:published_time'] = get_the_date( 'c', $post_id );
        
        // Set article modified time
        $og_meta['article:modified_time'] = get_the_modified_date( 'c', $post_id );
        
        // Set article author
        $author_id = get_post_field( 'post_author', $post_id );
        $og_meta['article:author'] = get_the_author_meta( 'display_name', $author_id );
        
        // Set article section
        $categories = get_the_category( $post_id );
        
        if ( $categories && ! is_wp_error( $categories ) ) {
            $og_meta['article:section'] = $categories[0]->name;
        }
        
        // Set article tags
        $tags = get_the_tags( $post_id );
        
        if ( $tags && ! is_wp_error( $tags ) ) {
            $tag_names = array();
            
            foreach ( $tags as $tag ) {
                $tag_names[] = $tag->name;
            }
            
            $og_meta['article:tag'] = implode( ', ', $tag_names );
        }
    }

    // Filter Open Graph meta
    return apply_filters( 'aqualuxe_open_graph_meta', $og_meta, $post_id );
}

/**
 * Get Twitter Card meta
 *
 * @param int $post_id Post ID.
 * @return array
 */
function aqualuxe_get_twitter_card_meta( $post_id = 0 ) {
    $seo_module = aqualuxe_get_module( 'seo' );
    
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    // Initialize Twitter Card meta
    $twitter_meta = array();

    // Set Twitter Card type
    $twitter_meta['twitter:card'] = 'summary_large_image';

    // Set Twitter site
    if ( $seo_module && isset( $seo_module->settings['twitter_site'] ) && $seo_module->settings['twitter_site'] ) {
        $twitter_site = $seo_module->settings['twitter_site'];
        
        // Add @ if not present
        if ( strpos( $twitter_site, '@' ) !== 0 ) {
            $twitter_site = '@' . $twitter_site;
        }
        
        $twitter_meta['twitter:site'] = $twitter_site;
    }

    // Set Twitter title
    $twitter_title = get_post_meta( $post_id, '_aqualuxe_twitter_title', true );
    
    if ( ! $twitter_title ) {
        $twitter_title = aqualuxe_get_meta_title( $post_id );
    }
    
    $twitter_meta['twitter:title'] = $twitter_title;

    // Set Twitter description
    $twitter_description = get_post_meta( $post_id, '_aqualuxe_twitter_description', true );
    
    if ( ! $twitter_description ) {
        $twitter_description = aqualuxe_get_meta_description( $post_id );
    }
    
    $twitter_meta['twitter:description'] = $twitter_description;

    // Set Twitter image
    $twitter_image = get_post_meta( $post_id, '_aqualuxe_twitter_image', true );
    
    if ( ! $twitter_image ) {
        $twitter_image = aqualuxe_get_featured_image_url( $post_id, 'large' );
    }
    
    if ( ! $twitter_image && $seo_module && isset( $seo_module->settings['default_twitter_image'] ) ) {
        $twitter_image = $seo_module->settings['default_twitter_image'];
    }
    
    if ( $twitter_image ) {
        $twitter_meta['twitter:image'] = $twitter_image;
    }

    // Filter Twitter Card meta
    return apply_filters( 'aqualuxe_twitter_card_meta', $twitter_meta, $post_id );
}

/**
 * Get schema markup
 *
 * @param int $post_id Post ID.
 * @return array
 */
function aqualuxe_get_schema_markup( $post_id = 0 ) {
    // If no post ID is provided, get the current post ID
    if ( ! $post_id ) {
        $post_id = get_queried_object_id();
    }

    // Initialize schema markup
    $schema_markup = array();

    // Set schema markup based on page type
    if ( is_home() || is_front_page() ) {
        // Website schema
        $schema_markup = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'url' => home_url( '/' ),
            'name' => get_bloginfo( 'name' ),
            'description' => get_bloginfo( 'description' ),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => home_url( '/?s={search_term_string}' ),
                'query-input' => 'required name=search_term_string',
            ),
        );
    } elseif ( is_singular( 'post' ) ) {
        // Article schema
        $schema_markup = aqualuxe_get_article_schema( $post_id );
    } elseif ( is_singular( 'page' ) ) {
        // WebPage schema
        $schema_markup = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'url' => get_permalink( $post_id ),
            'name' => get_the_title( $post_id ),
            'description' => aqualuxe_get_meta_description( $post_id ),
        );
    } elseif ( is_author() ) {
        // Person schema
        $schema_markup = aqualuxe_get_person_schema( get_queried_object_id() );
    }

    // Add organization schema to all pages
    if ( ! empty( $schema_markup ) ) {
        $schema_markup['publisher'] = aqualuxe_get_organization_schema();
    }

    // Filter schema markup
    return apply_filters( 'aqualuxe_schema_markup', $schema_markup, $post_id );
}

/**
 * Get article schema
 *
 * @param int $post_id Post ID.
 * @return array
 */
function aqualuxe_get_article_schema( $post_id ) {
    // Get post data
    $post = get_post( $post_id );
    
    if ( ! $post ) {
        return array();
    }

    // Get author data
    $author_id = $post->post_author;
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $author_url = get_author_posts_url( $author_id );

    // Get featured image
    $image_url = aqualuxe_get_featured_image_url( $post_id, 'full' );

    // Build article schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink( $post_id ),
        ),
        'headline' => get_the_title( $post_id ),
        'description' => aqualuxe_get_meta_description( $post_id ),
        'datePublished' => get_the_date( 'c', $post_id ),
        'dateModified' => get_the_modified_date( 'c', $post_id ),
        'author' => array(
            '@type' => 'Person',
            'name' => $author_name,
            'url' => $author_url,
        ),
    );

    // Add image if available
    if ( $image_url ) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
        );
        
        // Get image dimensions
        $image_id = get_post_thumbnail_id( $post_id );
        
        if ( $image_id ) {
            $image_data = wp_get_attachment_image_src( $image_id, 'full' );
            
            if ( $image_data ) {
                $schema['image']['width'] = $image_data[1];
                $schema['image']['height'] = $image_data[2];
            }
        }
    }

    return $schema;
}

/**
 * Get person schema
 *
 * @param int $author_id Author ID.
 * @return array
 */
function aqualuxe_get_person_schema( $author_id ) {
    // Get author data
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $author_url = get_author_posts_url( $author_id );
    $author_description = get_the_author_meta( 'description', $author_id );
    $author_email = get_the_author_meta( 'user_email', $author_id );
    $author_avatar = get_avatar_url( $author_id, array( 'size' => 96 ) );

    // Build person schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $author_name,
        'url' => $author_url,
    );

    // Add description if available
    if ( $author_description ) {
        $schema['description'] = $author_description;
    }

    // Add email if available
    if ( $author_email ) {
        $schema['email'] = $author_email;
    }

    // Add avatar if available
    if ( $author_avatar ) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $author_avatar,
        );
    }

    return $schema;
}

/**
 * Get organization schema
 *
 * @return array
 */
function aqualuxe_get_organization_schema() {
    // Get site data
    $site_name = get_bloginfo( 'name' );
    $site_url = home_url( '/' );
    $site_logo = get_custom_logo_url();

    // Build organization schema
    $schema = array(
        '@type' => 'Organization',
        'name' => $site_name,
        'url' => $site_url,
    );

    // Add logo if available
    if ( $site_logo ) {
        $schema['logo'] = array(
            '@type' => 'ImageObject',
            'url' => $site_logo,
        );
    }

    return $schema;
}

/**
 * Get custom logo URL
 *
 * @return string
 */
function get_custom_logo_url() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    
    if ( $custom_logo_id ) {
        $logo_data = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        
        if ( $logo_data ) {
            return $logo_data[0];
        }
    }
    
    return '';
}

/**
 * Get breadcrumbs
 *
 * @return string
 */
function aqualuxe_get_breadcrumbs() {
    // Check if breadcrumbs are enabled
    if ( ! aqualuxe_is_breadcrumbs_enabled() ) {
        return '';
    }

    // Initialize breadcrumbs
    $breadcrumbs = array();
    
    // Add home link
    $breadcrumbs[] = array(
        'url' => home_url( '/' ),
        'text' => __( 'Home', 'aqualuxe' ),
    );

    // Add breadcrumbs based on page type
    if ( is_category() ) {
        // Category archive
        $breadcrumbs[] = array(
            'url' => '',
            'text' => single_cat_title( '', false ),
        );
    } elseif ( is_tag() ) {
        // Tag archive
        $breadcrumbs[] = array(
            'url' => '',
            'text' => single_tag_title( '', false ),
        );
    } elseif ( is_author() ) {
        // Author archive
        $breadcrumbs[] = array(
            'url' => '',
            'text' => get_the_author(),
        );
    } elseif ( is_year() ) {
        // Year archive
        $breadcrumbs[] = array(
            'url' => '',
            'text' => get_the_date( 'Y' ),
        );
    } elseif ( is_month() ) {
        // Month archive
        $year_url = get_year_link( get_query_var( 'year' ) );
        
        $breadcrumbs[] = array(
            'url' => $year_url,
            'text' => get_the_date( 'Y' ),
        );
        
        $breadcrumbs[] = array(
            'url' => '',
            'text' => get_the_date( 'F' ),
        );
    } elseif ( is_day() ) {
        // Day archive
        $year_url = get_year_link( get_query_var( 'year' ) );
        
        $breadcrumbs[] = array(
            'url' => $year_url,
            'text' => get_the_date( 'Y' ),
        );
        
        $month_url = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
        
        $breadcrumbs[] = array(
            'url' => $month_url,
            'text' => get_the_date( 'F' ),
        );
        
        $breadcrumbs[] = array(
            'url' => '',
            'text' => get_the_date( 'j' ),
        );
    } elseif ( is_tax() ) {
        // Custom taxonomy archive
        $term = get_queried_object();
        
        if ( $term && ! is_wp_error( $term ) ) {
            $breadcrumbs[] = array(
                'url' => '',
                'text' => $term->name,
            );
        }
    } elseif ( is_post_type_archive() ) {
        // Custom post type archive
        $breadcrumbs[] = array(
            'url' => '',
            'text' => post_type_archive_title( '', false ),
        );
    } elseif ( is_singular( 'post' ) ) {
        // Single post
        $categories = get_the_category();
        
        if ( $categories ) {
            $category = $categories[0];
            
            $breadcrumbs[] = array(
                'url' => get_category_link( $category->term_id ),
                'text' => $category->name,
            );
        }
        
        $breadcrumbs[] = array(
            'url' => '',
            'text' => get_the_title(),
        );
    } elseif ( is_singular( 'page' ) ) {
        // Single page
        $ancestors = get_post_ancestors( get_the_ID() );
        
        if ( $ancestors ) {
            $ancestors = array_reverse( $ancestors );
            
            foreach ( $ancestors as $ancestor ) {
                $breadcrumbs[] = array(
                    'url' => get_permalink( $ancestor ),
                    'text' => get_the_title( $ancestor ),
                );
            }
        }
        
        $breadcrumbs[] = array(
            'url' => '',
            'text' => get_the_title(),
        );
    } elseif ( is_search() ) {
        // Search results
        $breadcrumbs[] = array(
            'url' => '',
            'text' => sprintf( __( 'Search Results for "%s"', 'aqualuxe' ), get_search_query() ),
        );
    } elseif ( is_404() ) {
        // 404 page
        $breadcrumbs[] = array(
            'url' => '',
            'text' => __( 'Page Not Found', 'aqualuxe' ),
        );
    }

    // Build breadcrumbs HTML
    $html = '';
    $count = count( $breadcrumbs );
    
    foreach ( $breadcrumbs as $i => $breadcrumb ) {
        if ( $i === 0 ) {
            $html .= '<span property="itemListElement" typeof="ListItem">';
            $html .= '<a property="item" typeof="WebPage" href="' . esc_url( $breadcrumb['url'] ) . '">';
            $html .= '<span property="name">' . esc_html( $breadcrumb['text'] ) . '</span>';
            $html .= '</a>';
            $html .= '<meta property="position" content="' . esc_attr( $i + 1 ) . '">';
            $html .= '</span>';
        } elseif ( $i === $count - 1 ) {
            $html .= ' &raquo; ';
            $html .= '<span property="itemListElement" typeof="ListItem">';
            $html .= '<span property="name">' . esc_html( $breadcrumb['text'] ) . '</span>';
            $html .= '<meta property="position" content="' . esc_attr( $i + 1 ) . '">';
            $html .= '</span>';
        } else {
            $html .= ' &raquo; ';
            $html .= '<span property="itemListElement" typeof="ListItem">';
            $html .= '<a property="item" typeof="WebPage" href="' . esc_url( $breadcrumb['url'] ) . '">';
            $html .= '<span property="name">' . esc_html( $breadcrumb['text'] ) . '</span>';
            $html .= '</a>';
            $html .= '<meta property="position" content="' . esc_attr( $i + 1 ) . '">';
            $html .= '</span>';
        }
    }

    // Filter breadcrumbs HTML
    return apply_filters( 'aqualuxe_breadcrumbs', $html );
}

/**
 * Get sitemap index
 *
 * @return string
 */
function aqualuxe_get_sitemap_index() {
    // Initialize sitemap index
    $sitemap_index = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap_index .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Add post types
    $post_types = get_post_types( array( 'public' => true ) );
    
    foreach ( $post_types as $post_type ) {
        $sitemap_index .= '<sitemap>';
        $sitemap_index .= '<loc>' . esc_url( home_url( '/sitemap-' . $post_type . '.xml' ) ) . '</loc>';
        $sitemap_index .= '<lastmod>' . date( 'c' ) . '</lastmod>';
        $sitemap_index .= '</sitemap>';
    }

    // Add taxonomies
    $taxonomies = get_taxonomies( array( 'public' => true ) );
    
    foreach ( $taxonomies as $taxonomy ) {
        $sitemap_index .= '<sitemap>';
        $sitemap_index .= '<loc>' . esc_url( home_url( '/sitemap-' . $taxonomy . '.xml' ) ) . '</loc>';
        $sitemap_index .= '<lastmod>' . date( 'c' ) . '</lastmod>';
        $sitemap_index .= '</sitemap>';
    }

    // Close sitemap index
    $sitemap_index .= '</sitemapindex>';

    return $sitemap_index;
}

/**
 * Get sitemap
 *
 * @param string $type Sitemap type.
 * @return string
 */
function aqualuxe_get_sitemap( $type ) {
    // Initialize sitemap
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Add URLs based on sitemap type
    if ( post_type_exists( $type ) ) {
        // Post type sitemap
        $posts = get_posts( array(
            'post_type' => $type,
            'post_status' => 'publish',
            'numberposts' => -1,
        ) );
        
        foreach ( $posts as $post ) {
            // Skip noindex posts
            if ( aqualuxe_is_noindex( $post->ID ) ) {
                continue;
            }
            
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . esc_url( get_permalink( $post->ID ) ) . '</loc>';
            $sitemap .= '<lastmod>' . get_the_modified_date( 'c', $post->ID ) . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.8</priority>';
            $sitemap .= '</url>';
        }
    } elseif ( taxonomy_exists( $type ) ) {
        // Taxonomy sitemap
        $terms = get_terms( array(
            'taxonomy' => $type,
            'hide_empty' => true,
        ) );
        
        foreach ( $terms as $term ) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . esc_url( get_term_link( $term ) ) . '</loc>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.6</priority>';
            $sitemap .= '</url>';
        }
    }

    // Close sitemap
    $sitemap .= '</urlset>';

    return $sitemap;
}