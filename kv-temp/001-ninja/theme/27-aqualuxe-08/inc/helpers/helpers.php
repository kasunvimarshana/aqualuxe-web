<?php
/**
 * Helper functions for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Get theme option with default fallback
 *
 * @param string $option_name The option name.
 * @param mixed  $default     The default value.
 * @return mixed The option value or default.
 */
function aqualuxe_get_option( $option_name, $default = false ) {
    $option_value = get_theme_mod( $option_name, $default );
    
    // Check if multilingual support is active and translate if needed
    if ( function_exists( 'aqualuxe_translate_theme_mod' ) ) {
        $option_value = aqualuxe_translate_theme_mod( $option_name );
        
        if ( empty( $option_value ) ) {
            $option_value = $default;
        }
    }
    
    return $option_value;
}

/**
 * Get image URL by ID with fallback
 *
 * @param int    $attachment_id The attachment ID.
 * @param string $size          The image size.
 * @param string $fallback      The fallback image URL.
 * @return string The image URL.
 */
function aqualuxe_get_image_url( $attachment_id, $size = 'full', $fallback = '' ) {
    if ( ! empty( $attachment_id ) ) {
        $image = wp_get_attachment_image_src( $attachment_id, $size );
        
        if ( $image ) {
            return $image[0];
        }
    }
    
    return $fallback;
}

/**
 * Get image alt text by ID with fallback
 *
 * @param int    $attachment_id The attachment ID.
 * @param string $fallback      The fallback alt text.
 * @return string The alt text.
 */
function aqualuxe_get_image_alt( $attachment_id, $fallback = '' ) {
    if ( ! empty( $attachment_id ) ) {
        $alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
        
        if ( $alt ) {
            return $alt;
        }
    }
    
    return $fallback;
}

/**
 * Get post thumbnail with fallback
 *
 * @param int    $post_id  The post ID.
 * @param string $size     The image size.
 * @param string $fallback The fallback image URL.
 * @return string The HTML for the thumbnail.
 */
function aqualuxe_get_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $fallback = '' ) {
    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail( $post_id, $size );
    }
    
    if ( ! empty( $fallback ) ) {
        return '<img src="' . esc_url( $fallback ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '" class="wp-post-image" />';
    }
    
    return '';
}

/**
 * Get excerpt with custom length
 *
 * @param int    $length   The excerpt length.
 * @param string $more     The more text.
 * @param int    $post_id  The post ID.
 * @return string The excerpt.
 */
function aqualuxe_get_excerpt( $length = 55, $more = '&hellip;', $post_id = null ) {
    $post = get_post( $post_id );
    
    if ( ! $post ) {
        return '';
    }
    
    if ( has_excerpt( $post->ID ) ) {
        $excerpt = $post->post_excerpt;
    } else {
        $excerpt = $post->post_content;
    }
    
    $excerpt = strip_shortcodes( $excerpt );
    $excerpt = wp_strip_all_tags( $excerpt );
    $excerpt = wp_trim_words( $excerpt, $length, $more );
    
    return $excerpt;
}

/**
 * Get primary category for a post
 *
 * @param int    $post_id   The post ID.
 * @param string $taxonomy  The taxonomy name.
 * @return object|false The category object or false.
 */
function aqualuxe_get_primary_category( $post_id = null, $taxonomy = 'category' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    // Check for Yoast primary category
    if ( class_exists( 'WPSEO_Primary_Term' ) ) {
        $primary_term = new WPSEO_Primary_Term( $taxonomy, $post_id );
        $primary_term_id = $primary_term->get_primary_term();
        
        if ( $primary_term_id ) {
            return get_term( $primary_term_id, $taxonomy );
        }
    }
    
    // Fallback to first category
    $categories = get_the_terms( $post_id, $taxonomy );
    
    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
        return $categories[0];
    }
    
    return false;
}

/**
 * Get related posts
 *
 * @param int    $post_id   The post ID.
 * @param int    $count     The number of posts to get.
 * @param string $taxonomy  The taxonomy name.
 * @return array The related posts.
 */
function aqualuxe_get_related_posts( $post_id = null, $count = 3, $taxonomy = 'category' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $terms = get_the_terms( $post_id, $taxonomy );
    
    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return array();
    }
    
    $term_ids = wp_list_pluck( $terms, 'term_id' );
    
    $args = array(
        'post_type'      => get_post_type( $post_id ),
        'posts_per_page' => $count,
        'post__not_in'   => array( $post_id ),
        'tax_query'      => array(
            array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_ids,
            ),
        ),
    );
    
    $query = new WP_Query( $args );
    
    return $query->posts;
}

/**
 * Get social media links
 *
 * @return array The social media links.
 */
function aqualuxe_get_social_links() {
    $social_links = array();
    
    $social_platforms = array(
        'facebook'  => array(
            'label' => __( 'Facebook', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>',
        ),
        'twitter'   => array(
            'label' => __( 'Twitter', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>',
        ),
        'instagram' => array(
            'label' => __( 'Instagram', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
        ),
        'linkedin'  => array(
            'label' => __( 'LinkedIn', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>',
        ),
        'youtube'   => array(
            'label' => __( 'YouTube', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>',
        ),
        'pinterest' => array(
            'label' => __( 'Pinterest', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>',
        ),
    );
    
    foreach ( $social_platforms as $platform => $data ) {
        $url = aqualuxe_get_option( 'aqualuxe_' . $platform, '' );
        
        if ( ! empty( $url ) ) {
            $social_links[$platform] = array(
                'url'   => $url,
                'label' => $data['label'],
                'icon'  => $data['icon'],
            );
        }
    }
    
    return $social_links;
}

/**
 * Get contact information
 *
 * @return array The contact information.
 */
function aqualuxe_get_contact_info() {
    $contact_info = array();
    
    $phone = aqualuxe_get_option( 'aqualuxe_phone_number', '' );
    $email = aqualuxe_get_option( 'aqualuxe_email', '' );
    $address = aqualuxe_get_option( 'aqualuxe_address', '' );
    
    if ( ! empty( $phone ) ) {
        $contact_info['phone'] = array(
            'value' => $phone,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>',
            'url'   => 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ),
        );
    }
    
    if ( ! empty( $email ) ) {
        $contact_info['email'] = array(
            'value' => $email,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
            'url'   => 'mailto:' . $email,
        );
    }
    
    if ( ! empty( $address ) ) {
        $contact_info['address'] = array(
            'value' => $address,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
            'url'   => 'https://maps.google.com/?q=' . urlencode( $address ),
        );
    }
    
    return $contact_info;
}

/**
 * Get business hours
 *
 * @return array The business hours.
 */
function aqualuxe_get_business_hours() {
    $business_hours = array();
    
    $days = array(
        'monday'    => __( 'Monday', 'aqualuxe' ),
        'tuesday'   => __( 'Tuesday', 'aqualuxe' ),
        'wednesday' => __( 'Wednesday', 'aqualuxe' ),
        'thursday'  => __( 'Thursday', 'aqualuxe' ),
        'friday'    => __( 'Friday', 'aqualuxe' ),
        'saturday'  => __( 'Saturday', 'aqualuxe' ),
        'sunday'    => __( 'Sunday', 'aqualuxe' ),
    );
    
    foreach ( $days as $day_key => $day_label ) {
        $hours = aqualuxe_get_option( 'aqualuxe_' . $day_key . '_hours', '' );
        
        if ( ! empty( $hours ) ) {
            $business_hours[$day_key] = array(
                'day'   => $day_label,
                'hours' => $hours,
            );
        }
    }
    
    return $business_hours;
}

/**
 * Get breadcrumbs
 *
 * @return array The breadcrumbs.
 */
function aqualuxe_get_breadcrumbs() {
    $breadcrumbs = array();
    
    // Home
    $breadcrumbs[] = array(
        'title' => __( 'Home', 'aqualuxe' ),
        'url'   => home_url( '/' ),
    );
    
    if ( is_home() ) {
        // Blog
        $breadcrumbs[] = array(
            'title' => __( 'Blog', 'aqualuxe' ),
            'url'   => '',
        );
    } elseif ( is_category() ) {
        // Category
        $breadcrumbs[] = array(
            'title' => __( 'Blog', 'aqualuxe' ),
            'url'   => get_permalink( get_option( 'page_for_posts' ) ),
        );
        
        $breadcrumbs[] = array(
            'title' => single_cat_title( '', false ),
            'url'   => '',
        );
    } elseif ( is_tag() ) {
        // Tag
        $breadcrumbs[] = array(
            'title' => __( 'Blog', 'aqualuxe' ),
            'url'   => get_permalink( get_option( 'page_for_posts' ) ),
        );
        
        $breadcrumbs[] = array(
            'title' => single_tag_title( '', false ),
            'url'   => '',
        );
    } elseif ( is_author() ) {
        // Author
        $breadcrumbs[] = array(
            'title' => __( 'Blog', 'aqualuxe' ),
            'url'   => get_permalink( get_option( 'page_for_posts' ) ),
        );
        
        $breadcrumbs[] = array(
            'title' => get_the_author(),
            'url'   => '',
        );
    } elseif ( is_year() ) {
        // Year
        $breadcrumbs[] = array(
            'title' => __( 'Blog', 'aqualuxe' ),
            'url'   => get_permalink( get_option( 'page_for_posts' ) ),
        );
        
        $breadcrumbs[] = array(
            'title' => get_the_date( 'Y' ),
            'url'   => '',
        );
    } elseif ( is_month() ) {
        // Month
        $breadcrumbs[] = array(
            'title' => __( 'Blog', 'aqualuxe' ),
            'url'   => get_permalink( get_option( 'page_for_posts' ) ),
        );
        
        $breadcrumbs[] = array(
            'title' => get_the_date( 'Y' ),
            'url'   => get_year_link( get_the_date( 'Y' ) ),
        );
        
        $breadcrumbs[] = array(
            'title' => get_the_date( 'F' ),
            'url'   => '',
        );
    } elseif ( is_day() ) {
        // Day
        $breadcrumbs[] = array(
            'title' => __( 'Blog', 'aqualuxe' ),
            'url'   => get_permalink( get_option( 'page_for_posts' ) ),
        );
        
        $breadcrumbs[] = array(
            'title' => get_the_date( 'Y' ),
            'url'   => get_year_link( get_the_date( 'Y' ) ),
        );
        
        $breadcrumbs[] = array(
            'title' => get_the_date( 'F' ),
            'url'   => get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ),
        );
        
        $breadcrumbs[] = array(
            'title' => get_the_date( 'j' ),
            'url'   => '',
        );
    } elseif ( is_single() && ! is_attachment() ) {
        // Single post
        if ( get_post_type() === 'post' ) {
            $breadcrumbs[] = array(
                'title' => __( 'Blog', 'aqualuxe' ),
                'url'   => get_permalink( get_option( 'page_for_posts' ) ),
            );
            
            $category = aqualuxe_get_primary_category();
            
            if ( $category ) {
                $breadcrumbs[] = array(
                    'title' => $category->name,
                    'url'   => get_term_link( $category ),
                );
            }
        } elseif ( get_post_type() === 'product' && class_exists( 'WooCommerce' ) ) {
            // WooCommerce product
            $breadcrumbs[] = array(
                'title' => __( 'Shop', 'aqualuxe' ),
                'url'   => get_permalink( wc_get_page_id( 'shop' ) ),
            );
            
            $terms = wc_get_product_terms( get_the_ID(), 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
            
            if ( ! empty( $terms ) ) {
                $main_term = $terms[0];
                
                $breadcrumbs[] = array(
                    'title' => $main_term->name,
                    'url'   => get_term_link( $main_term ),
                );
            }
        } elseif ( get_post_type() === 'service' ) {
            // Service
            $breadcrumbs[] = array(
                'title' => __( 'Services', 'aqualuxe' ),
                'url'   => get_post_type_archive_link( 'service' ),
            );
            
            $terms = get_the_terms( get_the_ID(), 'service_category' );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $breadcrumbs[] = array(
                    'title' => $terms[0]->name,
                    'url'   => get_term_link( $terms[0] ),
                );
            }
        } elseif ( get_post_type() === 'project' ) {
            // Project
            $breadcrumbs[] = array(
                'title' => __( 'Projects', 'aqualuxe' ),
                'url'   => get_post_type_archive_link( 'project' ),
            );
            
            $terms = get_the_terms( get_the_ID(), 'project_category' );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $breadcrumbs[] = array(
                    'title' => $terms[0]->name,
                    'url'   => get_term_link( $terms[0] ),
                );
            }
        } elseif ( get_post_type() === 'event' ) {
            // Event
            $breadcrumbs[] = array(
                'title' => __( 'Events', 'aqualuxe' ),
                'url'   => get_post_type_archive_link( 'event' ),
            );
            
            $terms = get_the_terms( get_the_ID(), 'event_category' );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $breadcrumbs[] = array(
                    'title' => $terms[0]->name,
                    'url'   => get_term_link( $terms[0] ),
                );
            }
        }
        
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'url'   => '',
        );
    } elseif ( is_page() ) {
        // Page
        $ancestors = get_post_ancestors( get_the_ID() );
        
        if ( ! empty( $ancestors ) ) {
            $ancestors = array_reverse( $ancestors );
            
            foreach ( $ancestors as $ancestor ) {
                $breadcrumbs[] = array(
                    'title' => get_the_title( $ancestor ),
                    'url'   => get_permalink( $ancestor ),
                );
            }
        }
        
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'url'   => '',
        );
    } elseif ( is_search() ) {
        // Search
        $breadcrumbs[] = array(
            'title' => sprintf( __( 'Search Results for: %s', 'aqualuxe' ), get_search_query() ),
            'url'   => '',
        );
    } elseif ( is_404() ) {
        // 404
        $breadcrumbs[] = array(
            'title' => __( 'Page Not Found', 'aqualuxe' ),
            'url'   => '',
        );
    } elseif ( is_post_type_archive() ) {
        // Post type archive
        $breadcrumbs[] = array(
            'title' => post_type_archive_title( '', false ),
            'url'   => '',
        );
    } elseif ( is_tax() ) {
        // Taxonomy
        $term = get_queried_object();
        
        if ( $term->taxonomy === 'product_cat' && class_exists( 'WooCommerce' ) ) {
            // WooCommerce product category
            $breadcrumbs[] = array(
                'title' => __( 'Shop', 'aqualuxe' ),
                'url'   => get_permalink( wc_get_page_id( 'shop' ) ),
            );
        } elseif ( $term->taxonomy === 'service_category' ) {
            // Service category
            $breadcrumbs[] = array(
                'title' => __( 'Services', 'aqualuxe' ),
                'url'   => get_post_type_archive_link( 'service' ),
            );
        } elseif ( $term->taxonomy === 'project_category' ) {
            // Project category
            $breadcrumbs[] = array(
                'title' => __( 'Projects', 'aqualuxe' ),
                'url'   => get_post_type_archive_link( 'project' ),
            );
        } elseif ( $term->taxonomy === 'event_category' ) {
            // Event category
            $breadcrumbs[] = array(
                'title' => __( 'Events', 'aqualuxe' ),
                'url'   => get_post_type_archive_link( 'event' ),
            );
        }
        
        // Add parent terms
        $ancestors = get_ancestors( $term->term_id, $term->taxonomy );
        
        if ( ! empty( $ancestors ) ) {
            $ancestors = array_reverse( $ancestors );
            
            foreach ( $ancestors as $ancestor ) {
                $ancestor_term = get_term( $ancestor, $term->taxonomy );
                
                $breadcrumbs[] = array(
                    'title' => $ancestor_term->name,
                    'url'   => get_term_link( $ancestor_term ),
                );
            }
        }
        
        $breadcrumbs[] = array(
            'title' => $term->name,
            'url'   => '',
        );
    }
    
    return $breadcrumbs;
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    $breadcrumbs = aqualuxe_get_breadcrumbs();
    
    if ( empty( $breadcrumbs ) ) {
        return;
    }
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
    echo '<ol class="breadcrumbs-list flex flex-wrap items-center text-sm">';
    
    $count = count( $breadcrumbs );
    $i = 1;
    
    foreach ( $breadcrumbs as $breadcrumb ) {
        echo '<li class="breadcrumbs-item">';
        
        if ( ! empty( $breadcrumb['url'] ) ) {
            echo '<a href="' . esc_url( $breadcrumb['url'] ) . '" class="breadcrumbs-link">' . esc_html( $breadcrumb['title'] ) . '</a>';
        } else {
            echo '<span class="breadcrumbs-text">' . esc_html( $breadcrumb['title'] ) . '</span>';
        }
        
        if ( $i < $count ) {
            echo '<span class="breadcrumbs-separator mx-2">/</span>';
        }
        
        echo '</li>';
        
        $i++;
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Get pagination
 *
 * @param array $args The pagination arguments.
 */
function aqualuxe_pagination( $args = array() ) {
    $defaults = array(
        'total'     => 1,
        'current'   => 1,
        'show_all'  => false,
        'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
        'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
        'end_size'  => 1,
        'mid_size'  => 2,
        'type'      => 'list',
        'add_args'  => array(),
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $output = paginate_links( $args );
    
    if ( $output ) {
        echo '<nav class="pagination-nav" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
        echo $output;
        echo '</nav>';
    }
}

/**
 * Get post navigation
 */
function aqualuxe_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if ( ! $prev_post && ! $next_post ) {
        return;
    }
    
    echo '<nav class="post-navigation flex flex-col md:flex-row justify-between my-8 p-6 bg-gray-50 rounded-lg" aria-label="' . esc_attr__( 'Post Navigation', 'aqualuxe' ) . '">';
    
    if ( $prev_post ) {
        echo '<div class="post-navigation-prev mb-4 md:mb-0">';
        echo '<span class="post-navigation-label block text-sm text-gray-600 mb-1">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</span>';
        echo '<a href="' . esc_url( get_permalink( $prev_post ) ) . '" class="post-navigation-link font-medium hover:text-blue-600">' . esc_html( get_the_title( $prev_post ) ) . '</a>';
        echo '</div>';
    }
    
    if ( $next_post ) {
        echo '<div class="post-navigation-next text-right">';
        echo '<span class="post-navigation-label block text-sm text-gray-600 mb-1">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</span>';
        echo '<a href="' . esc_url( get_permalink( $next_post ) ) . '" class="post-navigation-link font-medium hover:text-blue-600">' . esc_html( get_the_title( $next_post ) ) . '</a>';
        echo '</div>';
    }
    
    echo '</nav>';
}

/**
 * Get social sharing links
 *
 * @param int|null $post_id The post ID.
 * @return array The social sharing links.
 */
function aqualuxe_get_social_sharing_links( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $permalink = get_permalink( $post_id );
    $title = get_the_title( $post_id );
    
    $sharing_links = array(
        'facebook' => array(
            'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $permalink ),
            'label' => __( 'Share on Facebook', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>',
        ),
        'twitter'  => array(
            'url'   => 'https://twitter.com/intent/tweet?url=' . urlencode( $permalink ) . '&text=' . urlencode( $title ),
            'label' => __( 'Share on Twitter', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>',
        ),
        'linkedin' => array(
            'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( $permalink ) . '&title=' . urlencode( $title ),
            'label' => __( 'Share on LinkedIn', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>',
        ),
        'pinterest' => array(
            'url'   => 'https://pinterest.com/pin/create/button/?url=' . urlencode( $permalink ) . '&description=' . urlencode( $title ),
            'label' => __( 'Share on Pinterest', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>',
        ),
        'email'    => array(
            'url'   => 'mailto:?subject=' . urlencode( $title ) . '&body=' . urlencode( $permalink ),
            'label' => __( 'Share via Email', 'aqualuxe' ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
        ),
    );
    
    return $sharing_links;
}

/**
 * Display social sharing links
 *
 * @param int|null $post_id The post ID.
 */
function aqualuxe_social_sharing( $post_id = null ) {
    $sharing_links = aqualuxe_get_social_sharing_links( $post_id );
    
    if ( empty( $sharing_links ) ) {
        return;
    }
    
    echo '<div class="social-sharing">';
    echo '<span class="social-sharing-label mr-2">' . esc_html__( 'Share:', 'aqualuxe' ) . '</span>';
    echo '<div class="social-sharing-links flex space-x-2">';
    
    foreach ( $sharing_links as $platform => $data ) {
        echo '<a href="' . esc_url( $data['url'] ) . '" class="social-sharing-link ' . esc_attr( $platform ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $data['label'] ) . '">';
        echo $data['icon'];
        echo '</a>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Get post author box
 *
 * @param int|null $post_id The post ID.
 */
function aqualuxe_author_box( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $author_id = get_post_field( 'post_author', $post_id );
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $author_bio = get_the_author_meta( 'description', $author_id );
    $author_url = get_author_posts_url( $author_id );
    
    if ( empty( $author_bio ) ) {
        return;
    }
    
    echo '<div class="author-box bg-gray-50 p-6 rounded-lg mt-8">';
    echo '<div class="author-box-header flex items-center mb-4">';
    echo '<div class="author-box-avatar mr-4">';
    echo get_avatar( $author_id, 60, '', $author_name, array( 'class' => 'rounded-full' ) );
    echo '</div>';
    echo '<div class="author-box-info">';
    echo '<h3 class="author-box-name text-lg font-bold">' . esc_html( $author_name ) . '</h3>';
    echo '<a href="' . esc_url( $author_url ) . '" class="author-box-link text-sm text-blue-600 hover:text-blue-800">' . esc_html__( 'View all posts', 'aqualuxe' ) . '</a>';
    echo '</div>';
    echo '</div>';
    echo '<div class="author-box-bio prose max-w-none">' . wpautop( $author_bio ) . '</div>';
    echo '</div>';
}

/**
 * Get related products
 *
 * @param int    $product_id The product ID.
 * @param int    $limit      The number of products to get.
 * @param string $columns    The number of columns.
 */
function aqualuxe_related_products( $product_id = null, $limit = 3, $columns = 3 ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return;
    }
    
    // Get related products
    $related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product_id, $limit ) ), 'wc_products_array_filter_visible' );
    
    if ( empty( $related_products ) ) {
        return;
    }
    
    echo '<section class="related-products mt-12">';
    echo '<h2 class="related-products-title text-2xl font-bold mb-6">' . esc_html__( 'Related Products', 'aqualuxe' ) . '</h2>';
    echo '<div class="related-products-grid grid grid-cols-1 md:grid-cols-' . esc_attr( $columns ) . ' gap-8">';
    
    foreach ( $related_products as $related_product ) {
        echo '<div class="related-product">';
        echo '<a href="' . esc_url( get_permalink( $related_product->get_id() ) ) . '" class="related-product-link block">';
        echo '<div class="related-product-image mb-4">';
        echo $related_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'w-full h-auto rounded-lg' ) );
        echo '</div>';
        echo '<h3 class="related-product-title text-lg font-medium mb-2">' . esc_html( $related_product->get_name() ) . '</h3>';
        echo '<div class="related-product-price text-blue-600 font-bold">' . $related_product->get_price_html() . '</div>';
        echo '</a>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</section>';
}

/**
 * Get product categories
 *
 * @param int    $limit   The number of categories to get.
 * @param string $columns The number of columns.
 */
function aqualuxe_product_categories( $limit = 3, $columns = 3 ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    $args = array(
        'taxonomy'     => 'product_cat',
        'orderby'      => 'name',
        'order'        => 'ASC',
        'hide_empty'   => true,
        'number'       => $limit,
        'hierarchical' => true,
        'parent'       => 0,
    );
    
    $product_categories = get_terms( $args );
    
    if ( empty( $product_categories ) || is_wp_error( $product_categories ) ) {
        return;
    }
    
    echo '<div class="product-categories grid grid-cols-1 md:grid-cols-' . esc_attr( $columns ) . ' gap-8">';
    
    foreach ( $product_categories as $category ) {
        $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
        $image = aqualuxe_get_image_url( $thumbnail_id, 'woocommerce_thumbnail', wc_placeholder_img_src() );
        
        echo '<div class="product-category bg-white rounded-lg shadow-md overflow-hidden">';
        echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="product-category-link block">';
        echo '<div class="product-category-image">';
        echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" class="w-full h-auto" />';
        echo '</div>';
        echo '<div class="product-category-content p-6">';
        echo '<h3 class="product-category-title text-xl font-bold mb-2">' . esc_html( $category->name ) . '</h3>';
        echo '<div class="product-category-count text-sm text-gray-600">' . sprintf( _n( '%s product', '%s products', $category->count, 'aqualuxe' ), $category->count ) . '</div>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Get featured products
 *
 * @param int    $limit   The number of products to get.
 * @param string $columns The number of columns.
 */
function aqualuxe_featured_products( $limit = 4, $columns = 4 ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'meta_query'          => array(
            array(
                'key'     => '_featured',
                'value'   => 'yes',
                'compare' => '=',
            ),
        ),
        'tax_query'           => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'exclude-from-catalog',
                'operator' => 'NOT IN',
            ),
        ),
    );
    
    $products = new WP_Query( $args );
    
    if ( ! $products->have_posts() ) {
        return;
    }
    
    echo '<div class="featured-products grid grid-cols-1 md:grid-cols-' . esc_attr( $columns ) . ' gap-8">';
    
    while ( $products->have_posts() ) {
        $products->the_post();
        
        global $product;
        
        echo '<div class="featured-product bg-white rounded-lg shadow-md overflow-hidden">';
        echo '<a href="' . esc_url( get_permalink() ) . '" class="featured-product-link block">';
        echo '<div class="featured-product-image">';
        echo woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' );
        echo '</div>';
        echo '<div class="featured-product-content p-6">';
        echo '<h3 class="featured-product-title text-lg font-medium mb-2">' . esc_html( get_the_title() ) . '</h3>';
        echo '<div class="featured-product-price text-blue-600 font-bold mb-4">' . $product->get_price_html() . '</div>';
        echo '<div class="featured-product-button bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors inline-block">' . esc_html__( 'View Product', 'aqualuxe' ) . '</div>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    echo '</div>';
}

/**
 * Get recent posts
 *
 * @param int    $count   The number of posts to get.
 * @param string $columns The number of columns.
 */
function aqualuxe_recent_posts( $count = 3, $columns = 3 ) {
    $args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $count,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
    );
    
    $recent_posts = new WP_Query( $args );
    
    if ( ! $recent_posts->have_posts() ) {
        return;
    }
    
    echo '<div class="recent-posts grid grid-cols-1 md:grid-cols-' . esc_attr( $columns ) . ' gap-8">';
    
    while ( $recent_posts->have_posts() ) {
        $recent_posts->the_post();
        
        echo '<div class="recent-post bg-white rounded-lg shadow-md overflow-hidden">';
        
        if ( has_post_thumbnail() ) {
            echo '<div class="recent-post-image">';
            echo '<a href="' . esc_url( get_permalink() ) . '">';
            the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto' ) );
            echo '</a>';
            echo '</div>';
        }
        
        echo '<div class="recent-post-content p-6">';
        echo '<div class="recent-post-meta text-sm text-gray-600 mb-2">' . get_the_date() . '</div>';
        echo '<h3 class="recent-post-title text-lg font-bold mb-2">';
        echo '<a href="' . esc_url( get_permalink() ) . '" class="hover:text-blue-600">' . esc_html( get_the_title() ) . '</a>';
        echo '</h3>';
        echo '<div class="recent-post-excerpt mb-4">' . aqualuxe_get_excerpt( 20 ) . '</div>';
        echo '<a href="' . esc_url( get_permalink() ) . '" class="recent-post-link inline-block text-blue-600 hover:text-blue-800 font-medium">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
        echo '</div>';
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    echo '</div>';
}

/**
 * Get testimonials
 *
 * @param int    $count   The number of testimonials to get.
 * @param string $columns The number of columns.
 */
function aqualuxe_testimonials( $count = 3, $columns = 3 ) {
    $args = array(
        'post_type'      => 'testimonial',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    $testimonials = new WP_Query( $args );
    
    if ( ! $testimonials->have_posts() ) {
        return;
    }
    
    echo '<div class="testimonials grid grid-cols-1 md:grid-cols-' . esc_attr( $columns ) . ' gap-8">';
    
    while ( $testimonials->have_posts() ) {
        $testimonials->the_post();
        
        $client_name = get_post_meta( get_the_ID(), '_client_name', true );
        $client_position = get_post_meta( get_the_ID(), '_client_position', true );
        $client_company = get_post_meta( get_the_ID(), '_client_company', true );
        $client_rating = get_post_meta( get_the_ID(), '_client_rating', true );
        
        echo '<div class="testimonial bg-white rounded-lg shadow-md p-6">';
        echo '<div class="testimonial-content mb-4">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-300 mb-2" fill="currentColor" viewBox="0 0 24 24">';
        echo '<path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>';
        echo '</svg>';
        echo get_the_content();
        echo '</div>';
        
        if ( ! empty( $client_rating ) ) {
            echo '<div class="testimonial-rating mb-4">';
            
            for ( $i = 1; $i <= 5; $i++ ) {
                if ( $i <= $client_rating ) {
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 inline-block" viewBox="0 0 20 20" fill="currentColor">';
                    echo '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />';
                    echo '</svg>';
                } else {
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 inline-block" viewBox="0 0 20 20" fill="currentColor">';
                    echo '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />';
                    echo '</svg>';
                }
            }
            
            echo '</div>';
        }
        
        echo '<div class="testimonial-author flex items-center">';
        
        if ( has_post_thumbnail() ) {
            echo '<div class="testimonial-avatar mr-4">';
            the_post_thumbnail( 'thumbnail', array( 'class' => 'rounded-full w-12 h-12 object-cover' ) );
            echo '</div>';
        }
        
        echo '<div class="testimonial-info">';
        
        if ( ! empty( $client_name ) ) {
            echo '<div class="testimonial-name font-bold">' . esc_html( $client_name ) . '</div>';
        } else {
            echo '<div class="testimonial-name font-bold">' . esc_html( get_the_title() ) . '</div>';
        }
        
        if ( ! empty( $client_position ) || ! empty( $client_company ) ) {
            echo '<div class="testimonial-position text-sm text-gray-600">';
            
            if ( ! empty( $client_position ) && ! empty( $client_company ) ) {
                echo esc_html( $client_position ) . ', ' . esc_html( $client_company );
            } elseif ( ! empty( $client_position ) ) {
                echo esc_html( $client_position );
            } elseif ( ! empty( $client_company ) ) {
                echo esc_html( $client_company );
            }
            
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    echo '</div>';
}

/**
 * Get team members
 *
 * @param int    $count   The number of team members to get.
 * @param string $columns The number of columns.
 */
function aqualuxe_team_members( $count = 4, $columns = 4 ) {
    $args = array(
        'post_type'      => 'team',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    );
    
    $team_members = new WP_Query( $args );
    
    if ( ! $team_members->have_posts() ) {
        return;
    }
    
    echo '<div class="team-members grid grid-cols-1 md:grid-cols-' . esc_attr( $columns ) . ' gap-8">';
    
    while ( $team_members->have_posts() ) {
        $team_members->the_post();
        
        $team_position = get_post_meta( get_the_ID(), '_team_position', true );
        $team_email = get_post_meta( get_the_ID(), '_team_email', true );
        $team_phone = get_post_meta( get_the_ID(), '_team_phone', true );
        $team_facebook = get_post_meta( get_the_ID(), '_team_facebook', true );
        $team_twitter = get_post_meta( get_the_ID(), '_team_twitter', true );
        $team_linkedin = get_post_meta( get_the_ID(), '_team_linkedin', true );
        $team_instagram = get_post_meta( get_the_ID(), '_team_instagram', true );
        
        echo '<div class="team-member bg-white rounded-lg shadow-md overflow-hidden">';
        
        if ( has_post_thumbnail() ) {
            echo '<div class="team-member-image">';
            the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto' ) );
            echo '</div>';
        }
        
        echo '<div class="team-member-content p-6">';
        echo '<h3 class="team-member-name text-xl font-bold mb-1">' . esc_html( get_the_title() ) . '</h3>';
        
        if ( ! empty( $team_position ) ) {
            echo '<div class="team-member-position text-blue-600 mb-4">' . esc_html( $team_position ) . '</div>';
        }
        
        echo '<div class="team-member-bio mb-4">' . get_the_excerpt() . '</div>';
        
        echo '<div class="team-member-contact mb-4">';
        
        if ( ! empty( $team_email ) ) {
            echo '<div class="team-member-email mb-1">';
            echo '<a href="mailto:' . esc_attr( $team_email ) . '" class="text-gray-600 hover:text-blue-600">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
            echo '</svg>';
            echo esc_html( $team_email );
            echo '</a>';
            echo '</div>';
        }
        
        if ( ! empty( $team_phone ) ) {
            echo '<div class="team-member-phone">';
            echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $team_phone ) ) . '" class="text-gray-600 hover:text-blue-600">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />';
            echo '</svg>';
            echo esc_html( $team_phone );
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        
        echo '<div class="team-member-social flex space-x-3">';
        
        if ( ! empty( $team_facebook ) ) {
            echo '<a href="' . esc_url( $team_facebook ) . '" class="text-gray-400 hover:text-blue-600" target="_blank" rel="noopener noreferrer">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">';
            echo '<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>';
            echo '</svg>';
            echo '</a>';
        }
        
        if ( ! empty( $team_twitter ) ) {
            echo '<a href="' . esc_url( $team_twitter ) . '" class="text-gray-400 hover:text-blue-400" target="_blank" rel="noopener noreferrer">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">';
            echo '<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>';
            echo '</svg>';
            echo '</a>';
        }
        
        if ( ! empty( $team_linkedin ) ) {
            echo '<a href="' . esc_url( $team_linkedin ) . '" class="text-gray-400 hover:text-blue-700" target="_blank" rel="noopener noreferrer">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">';
            echo '<path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>';
            echo '</svg>';
            echo '</a>';
        }
        
        if ( ! empty( $team_instagram ) ) {
            echo '<a href="' . esc_url( $team_instagram ) . '" class="text-gray-400 hover:text-pink-600" target="_blank" rel="noopener noreferrer">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">';
            echo '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>';
            echo '</svg>';
            echo '</a>';
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    echo '</div>';
}

/**
 * Get services
 *
 * @param int    $count   The number of services to get.
 * @param string $columns The number of columns.
 */
function aqualuxe_services( $count = 3, $columns = 3 ) {
    $args = array(
        'post_type'      => 'service',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    );
    
    $services = new WP_Query( $args );
    
    if ( ! $services->have_posts() ) {
        return;
    }
    
    echo '<div class="services grid grid-cols-1 md:grid-cols-' . esc_attr( $columns ) . ' gap-8">';
    
    while ( $services->have_posts() ) {
        $services->the_post();
        
        $service_price = get_post_meta( get_the_ID(), '_service_price', true );
        $service_duration = get_post_meta( get_the_ID(), '_service_duration', true );
        $service_icon = get_post_meta( get_the_ID(), '_service_icon', true );
        
        echo '<div class="service bg-white rounded-lg shadow-md overflow-hidden">';
        
        if ( has_post_thumbnail() ) {
            echo '<div class="service-image">';
            echo '<a href="' . esc_url( get_permalink() ) . '">';
            the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto' ) );
            echo '</a>';
            echo '</div>';
        }
        
        echo '<div class="service-content p-6">';
        
        if ( ! empty( $service_icon ) ) {
            echo '<div class="service-icon mb-4">';
            echo '<i class="' . esc_attr( $service_icon ) . '"></i>';
            echo '</div>';
        }
        
        echo '<h3 class="service-title text-xl font-bold mb-2">';
        echo '<a href="' . esc_url( get_permalink() ) . '" class="hover:text-blue-600">' . esc_html( get_the_title() ) . '</a>';
        echo '</h3>';
        
        echo '<div class="service-meta text-sm text-gray-600 mb-4">';
        
        if ( ! empty( $service_price ) ) {
            echo '<div class="service-price mb-1">';
            echo '<strong>' . esc_html__( 'Price:', 'aqualuxe' ) . '</strong> ' . esc_html( $service_price );
            echo '</div>';
        }
        
        if ( ! empty( $service_duration ) ) {
            echo '<div class="service-duration">';
            echo '<strong>' . esc_html__( 'Duration:', 'aqualuxe' ) . '</strong> ' . esc_html( $service_duration );
            echo '</div>';
        }
        
        echo '</div>';
        
        echo '<div class="service-excerpt mb-4">' . get_the_excerpt() . '</div>';
        
        echo '<a href="' . esc_url( get_permalink() ) . '" class="service-link inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">';
        echo esc_html__( 'Learn More', 'aqualuxe' );
        echo '</a>';
        
        echo '</div>';
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    echo '</div>';
}

/**
 * Get projects
 *
 * @param int    $count   The number of projects to get.
 * @param string $columns The number of columns.
 */
function aqualuxe_projects( $count = 6, $columns = 3 ) {
    $args = array(
        'post_type'      => 'project',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    $projects = new WP_Query( $args );
    
    if ( ! $projects->have_posts() ) {
        return;
    }
    
    echo '<div class="projects grid grid-cols-1 md:grid-cols-' . esc_attr( $columns ) . ' gap-8">';
    
    while ( $projects->have_posts() ) {
        $projects->the_post();
        
        $project_client = get_post_meta( get_the_ID(), '_project_client', true );
        $project_location = get_post_meta( get_the_ID(), '_project_location', true );
        $project_date = get_post_meta( get_the_ID(), '_project_date', true );
        
        // Format date
        $formatted_date = ! empty( $project_date ) ? date_i18n( get_option( 'date_format' ), strtotime( $project_date ) ) : '';
        
        echo '<div class="project bg-white rounded-lg shadow-md overflow-hidden">';
        
        if ( has_post_thumbnail() ) {
            echo '<div class="project-image relative">';
            the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto' ) );
            echo '<div class="project-overlay absolute inset-0 bg-blue-900 bg-opacity-75 opacity-0 hover:opacity-100 flex items-center justify-center transition-opacity duration-300">';
            echo '<a href="' . esc_url( get_permalink() ) . '" class="inline-block bg-white hover:bg-blue-50 text-blue-900 font-medium py-2 px-4 rounded transition-colors">';
            echo esc_html__( 'View Project', 'aqualuxe' );
            echo '</a>';
            echo '</div>';
            echo '</div>';
        }
        
        echo '<div class="project-content p-6">';
        echo '<h3 class="project-title text-xl font-bold mb-2">';
        echo '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
        echo '</h3>';
        
        echo '<div class="project-meta text-sm text-gray-600 mb-4">';
        
        if ( ! empty( $project_client ) ) {
            echo '<div class="project-client mb-1">';
            echo '<strong>' . esc_html__( 'Client:', 'aqualuxe' ) . '</strong> ' . esc_html( $project_client );
            echo '</div>';
        }
        
        if ( ! empty( $project_location ) ) {
            echo '<div class="project-location mb-1">';
            echo '<strong>' . esc_html__( 'Location:', 'aqualuxe' ) . '</strong> ' . esc_html( $project_location );
            echo '</div>';
        }
        
        if ( ! empty( $formatted_date ) ) {
            echo '<div class="project-date mb-1">';
            echo '<strong>' . esc_html__( 'Completed:', 'aqualuxe' ) . '</strong> ' . esc_html( $formatted_date );
            echo '</div>';
        }
        
        echo '</div>';
        
        echo '<div class="project-excerpt mb-4">' . get_the_excerpt() . '</div>';
        
        echo '<a href="' . esc_url( get_permalink() ) . '" class="project-link inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">';
        echo esc_html__( 'View Details', 'aqualuxe' );
        echo '</a>';
        
        echo '</div>';
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    echo '</div>';
}

/**
 * Get events
 *
 * @param int    $count     The number of events to get.
 * @param string $columns   The number of columns.
 * @param bool   $show_past Whether to show past events.
 */
function aqualuxe_events( $count = 3, $columns = 3, $show_past = false ) {
    $args = array(
        'post_type'      => 'event',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'meta_value',
        'meta_key'       => '_event_date',
        'order'          => 'ASC',
    );
    
    // Filter out past events if show_past is false
    if ( ! $show_past ) {
        $args['meta_query'] = array(
            array(
                'key'     => '_event_date',
                'value'   => date( 'Y-m-d' ),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        );
    }
    
    $events = new WP_Query( $args );
    
    if ( ! $events->have_posts() ) {
        return;
    }
    
    echo '<div class="events grid grid-cols-1 md:grid-cols-' . esc_attr( $columns ) . ' gap-8">';
    
    while ( $events->have_posts() ) {
        $events->the_post();
        
        $event_date = get_post_meta( get_the_ID(), '_event_date', true );
        $event_time = get_post_meta( get_the_ID(), '_event_time', true );
        $event_location = get_post_meta( get_the_ID(), '_event_location', true );
        $event_price = get_post_meta( get_the_ID(), '_event_price', true );
        $event_registration_url = get_post_meta( get_the_ID(), '_event_registration_url', true );
        
        // Format date
        $formatted_date = ! empty( $event_date ) ? date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) : '';
        
        echo '<div class="event bg-white rounded-lg shadow-md overflow-hidden">';
        
        if ( has_post_thumbnail() ) {
            echo '<div class="event-image">';
            the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto' ) );
            echo '</div>';
        }
        
        echo '<div class="event-content p-6">';
        
        echo '<div class="event-date-badge mb-4 inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">';
        echo esc_html( $formatted_date );
        
        if ( ! empty( $event_time ) ) {
            echo ' <span class="event-time ml-1">' . esc_html( $event_time ) . '</span>';
        }
        
        echo '</div>';
        
        echo '<h3 class="event-title text-xl font-bold mb-2">';
        echo '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
        echo '</h3>';
        
        if ( ! empty( $event_location ) ) {
            echo '<div class="event-location text-sm text-gray-600 mb-2">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
            echo '</svg>';
            echo esc_html( $event_location );
            echo '</div>';
        }
        
        if ( ! empty( $event_price ) ) {
            echo '<div class="event-price text-sm text-gray-600 mb-4">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
            echo '</svg>';
            echo esc_html( $event_price );
            echo '</div>';
        }
        
        echo '<div class="event-excerpt mb-4">' . get_the_excerpt() . '</div>';
        
        echo '<div class="event-actions">';
        echo '<a href="' . esc_url( get_permalink() ) . '" class="event-link inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors mr-2">';
        echo esc_html__( 'Details', 'aqualuxe' );
        echo '</a>';
        
        if ( ! empty( $event_registration_url ) ) {
            echo '<a href="' . esc_url( $event_registration_url ) . '" class="event-register inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition-colors">';
            echo esc_html__( 'Register', 'aqualuxe' );
            echo '</a>';
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    echo '</div>';
}

/**
 * Get FAQs
 *
 * @param int $count The number of FAQs to get.
 */
function aqualuxe_faqs( $count = -1 ) {
    $args = array(
        'post_type'      => 'faq',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    );
    
    $faqs = new WP_Query( $args );
    
    if ( ! $faqs->have_posts() ) {
        return;
    }
    
    echo '<div class="faqs">';
    echo '<div class="faq-accordion space-y-4">';
    
    $counter = 0;
    
    while ( $faqs->have_posts() ) {
        $faqs->the_post();
        $counter++;
        
        echo '<div class="faq-item bg-white rounded-lg shadow-md overflow-hidden">';
        echo '<button class="faq-question w-full text-left p-6 focus:outline-none flex justify-between items-center" aria-expanded="false" aria-controls="faq-answer-' . esc_attr( $counter ) . '">';
        echo '<h3 class="text-lg font-medium">' . esc_html( get_the_title() ) . '</h3>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="faq-icon h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
        echo '</svg>';
        echo '</button>';
        echo '<div id="faq-answer-' . esc_attr( $counter ) . '" class="faq-answer hidden p-6 pt-0 prose max-w-none">';
        the_content();
        echo '</div>';
        echo '</div>';
    }
    
    wp_reset_postdata();
    
    echo '</div>';
    echo '</div>';
    
    // Add JavaScript for accordion functionality
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const icon = this.querySelector('.faq-icon');
                    
                    // Toggle the answer visibility
                    answer.classList.toggle('hidden');
                    
                    // Toggle the icon rotation
                    icon.classList.toggle('rotate-180');
                    
                    // Update aria-expanded attribute
                    const isExpanded = answer.classList.contains('hidden') ? 'false' : 'true';
                    this.setAttribute('aria-expanded', isExpanded);
                });
            });
        });
    </script>
    <?php
}

/**
 * Get contact form
 */
function aqualuxe_contact_form() {
    if ( ! function_exists( 'wpcf7_contact_form_tag_func' ) ) {
        echo '<div class="contact-form bg-white rounded-lg shadow-md p-6">';
        echo '<p>' . esc_html__( 'Contact Form 7 plugin is required to display the contact form.', 'aqualuxe' ) . '</p>';
        echo '</div>';
        return;
    }
    
    $contact_form_id = aqualuxe_get_option( 'aqualuxe_contact_form_id', '' );
    
    if ( empty( $contact_form_id ) ) {
        echo '<div class="contact-form bg-white rounded-lg shadow-md p-6">';
        echo '<p>' . esc_html__( 'Please set a contact form ID in the theme options.', 'aqualuxe' ) . '</p>';
        echo '</div>';
        return;
    }
    
    echo '<div class="contact-form bg-white rounded-lg shadow-md p-6">';
    echo do_shortcode( '[contact-form-7 id="' . esc_attr( $contact_form_id ) . '"]' );
    echo '</div>';
}

/**
 * Get Google Map
 *
 * @param string $address The address to show on the map.
 * @param string $api_key The Google Maps API key.
 * @param int    $zoom    The zoom level.
 * @param int    $height  The height of the map.
 */
function aqualuxe_google_map( $address = '', $api_key = '', $zoom = 15, $height = 400 ) {
    if ( empty( $address ) ) {
        $address = aqualuxe_get_option( 'aqualuxe_address', '' );
    }
    
    if ( empty( $api_key ) ) {
        $api_key = aqualuxe_get_option( 'aqualuxe_google_maps_api_key', '' );
    }
    
    if ( empty( $address ) || empty( $api_key ) ) {
        echo '<div class="google-map bg-gray-100 rounded-lg" style="height: ' . esc_attr( $height ) . 'px;">';
        echo '<div class="flex items-center justify-center h-full">';
        echo '<p class="text-gray-500">' . esc_html__( 'Please set an address and Google Maps API key in the theme options.', 'aqualuxe' ) . '</p>';
        echo '</div>';
        echo '</div>';
        return;
    }
    
    $map_url = 'https://www.google.com/maps/embed/v1/place?key=' . urlencode( $api_key ) . '&q=' . urlencode( $address ) . '&zoom=' . absint( $zoom );
    
    echo '<div class="google-map rounded-lg overflow-hidden" style="height: ' . esc_attr( $height ) . 'px;">';
    echo '<iframe width="100%" height="100%" frameborder="0" style="border:0" src="' . esc_url( $map_url ) . '" allowfullscreen></iframe>';
    echo '</div>';
}

/**
 * Get newsletter form
 */
function aqualuxe_newsletter_form() {
    echo '<div class="newsletter-form bg-white rounded-lg shadow-md p-6">';
    echo '<h3 class="newsletter-title text-xl font-bold mb-4">' . esc_html__( 'Subscribe to Our Newsletter', 'aqualuxe' ) . '</h3>';
    echo '<p class="newsletter-description mb-4">' . esc_html__( 'Stay updated with our latest news, products, and offers.', 'aqualuxe' ) . '</p>';
    
    echo '<form class="newsletter-form-fields" action="#" method="post">';
    echo '<div class="flex flex-col md:flex-row gap-4">';
    echo '<input type="email" name="email" placeholder="' . esc_attr__( 'Your Email Address', 'aqualuxe' ) . '" required class="w-full md:flex-1 p-3 border border-gray-300 rounded" />';
    echo '<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded transition-colors">' . esc_html__( 'Subscribe', 'aqualuxe' ) . '</button>';
    echo '</div>';
    echo '<div class="newsletter-privacy mt-4 text-sm text-gray-600">';
    echo '<label class="flex items-start">';
    echo '<input type="checkbox" name="privacy" required class="mt-1 mr-2" />';
    echo '<span>' . esc_html__( 'I agree to the privacy policy and consent to having my email address processed to receive newsletters.', 'aqualuxe' ) . '</span>';
    echo '</label>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
}

/**
 * Get search form
 */
function aqualuxe_search_form() {
    echo '<div class="search-form">';
    get_search_form();
    echo '</div>';
}

/**
 * Get back to top button
 */
function aqualuxe_back_to_top() {
    echo '<button id="back-to-top" class="back-to-top fixed bottom-8 right-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 shadow-lg transition-all opacity-0 invisible z-50" aria-label="' . esc_attr__( 'Back to Top', 'aqualuxe' ) . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />';
    echo '</svg>';
    echo '</button>';
    
    // Add JavaScript for back to top button
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backToTopButton = document.getElementById('back-to-top');
            
            if (backToTopButton) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTopButton.classList.add('opacity-100', 'visible');
                        backToTopButton.classList.remove('opacity-0', 'invisible');
                    } else {
                        backToTopButton.classList.add('opacity-0', 'invisible');
                        backToTopButton.classList.remove('opacity-100', 'visible');
                    }
                });
                
                backToTopButton.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>
    <?php
}