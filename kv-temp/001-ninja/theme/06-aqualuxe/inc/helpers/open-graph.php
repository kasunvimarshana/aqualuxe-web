<?php
/**
 * Open Graph metadata functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Open Graph metadata to head
 */
function aqualuxe_add_open_graph_tags() {
    // Check if Open Graph is enabled
    if ( ! get_theme_mod( 'aqualuxe_open_graph_enabled', true ) ) {
        return;
    }
    
    // Basic Open Graph tags
    echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
    
    // Page-specific Open Graph tags
    if ( is_singular() ) {
        aqualuxe_singular_open_graph_tags();
    } elseif ( is_archive() || is_home() ) {
        aqualuxe_archive_open_graph_tags();
    } else {
        aqualuxe_default_open_graph_tags();
    }
    
    // Twitter Card tags
    aqualuxe_twitter_card_tags();
}
add_action( 'wp_head', 'aqualuxe_add_open_graph_tags', 5 );

/**
 * Add Open Graph tags for singular pages
 */
function aqualuxe_singular_open_graph_tags() {
    global $post;
    
    // Get post data
    $post_title = get_the_title();
    $post_url = get_permalink();
    $post_type = get_post_type();
    
    // Set Open Graph type
    $og_type = 'article';
    
    if ( $post_type === 'product' ) {
        $og_type = 'product';
    } elseif ( $post_type === 'page' ) {
        $og_type = 'website';
    }
    
    // Output basic tags
    echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $post_title ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $post_url ) . '" />' . "\n";
    
    // Description
    $description = aqualuxe_get_open_graph_description();
    
    if ( $description ) {
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
    }
    
    // Image
    $image = aqualuxe_get_open_graph_image();
    
    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image['url'] ) . '" />' . "\n";
        
        if ( isset( $image['width'] ) && isset( $image['height'] ) ) {
            echo '<meta property="og:image:width" content="' . esc_attr( $image['width'] ) . '" />' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr( $image['height'] ) . '" />' . "\n";
        }
    }
    
    // Article specific tags
    if ( $og_type === 'article' ) {
        // Published time
        echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '" />' . "\n";
        
        // Modified time
        echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '" />' . "\n";
        
        // Author
        $author = get_the_author_meta( 'display_name', $post->post_author );
        
        if ( $author ) {
            echo '<meta property="article:author" content="' . esc_attr( $author ) . '" />' . "\n";
        }
        
        // Categories
        $categories = get_the_category();
        
        if ( $categories ) {
            foreach ( $categories as $category ) {
                echo '<meta property="article:section" content="' . esc_attr( $category->name ) . '" />' . "\n";
            }
        }
        
        // Tags
        $tags = get_the_tags();
        
        if ( $tags ) {
            foreach ( $tags as $tag ) {
                echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '" />' . "\n";
            }
        }
    }
    
    // Product specific tags
    if ( $og_type === 'product' && class_exists( 'WooCommerce' ) ) {
        $product = wc_get_product( $post->ID );
        
        if ( $product ) {
            // Price
            echo '<meta property="product:price:amount" content="' . esc_attr( $product->get_price() ) . '" />' . "\n";
            echo '<meta property="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '" />' . "\n";
            
            // Availability
            $availability = $product->is_in_stock() ? 'instock' : 'oos';
            echo '<meta property="product:availability" content="' . esc_attr( $availability ) . '" />' . "\n";
            
            // Product categories
            $product_categories = get_the_terms( $post->ID, 'product_cat' );
            
            if ( $product_categories && ! is_wp_error( $product_categories ) ) {
                foreach ( $product_categories as $category ) {
                    echo '<meta property="product:category" content="' . esc_attr( $category->name ) . '" />' . "\n";
                }
            }
        }
    }
}

/**
 * Add Open Graph tags for archive pages
 */
function aqualuxe_archive_open_graph_tags() {
    // Set Open Graph type
    $og_type = 'website';
    
    // Get archive title
    $title = aqualuxe_get_archive_title();
    
    // Get archive URL
    $url = aqualuxe_get_current_url();
    
    // Output basic tags
    echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
    
    // Description
    $description = aqualuxe_get_open_graph_description();
    
    if ( $description ) {
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
    }
    
    // Image
    $image = aqualuxe_get_open_graph_image();
    
    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image['url'] ) . '" />' . "\n";
        
        if ( isset( $image['width'] ) && isset( $image['height'] ) ) {
            echo '<meta property="og:image:width" content="' . esc_attr( $image['width'] ) . '" />' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr( $image['height'] ) . '" />' . "\n";
        }
    }
}

/**
 * Add Open Graph tags for default pages
 */
function aqualuxe_default_open_graph_tags() {
    // Set Open Graph type
    $og_type = 'website';
    
    // Get title
    $title = wp_get_document_title();
    
    // Get URL
    $url = aqualuxe_get_current_url();
    
    // Output basic tags
    echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
    
    // Description
    $description = get_bloginfo( 'description' );
    
    if ( $description ) {
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
    }
    
    // Image
    $image = aqualuxe_get_open_graph_image();
    
    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image['url'] ) . '" />' . "\n";
        
        if ( isset( $image['width'] ) && isset( $image['height'] ) ) {
            echo '<meta property="og:image:width" content="' . esc_attr( $image['width'] ) . '" />' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr( $image['height'] ) . '" />' . "\n";
        }
    }
}

/**
 * Add Twitter Card tags
 */
function aqualuxe_twitter_card_tags() {
    // Check if Twitter Card is enabled
    if ( ! get_theme_mod( 'aqualuxe_twitter_card_enabled', true ) ) {
        return;
    }
    
    // Get Twitter username
    $twitter_username = get_theme_mod( 'aqualuxe_twitter_username', '' );
    
    // Set card type
    $card_type = 'summary_large_image';
    
    // Output Twitter Card tags
    echo '<meta name="twitter:card" content="' . esc_attr( $card_type ) . '" />' . "\n";
    
    if ( $twitter_username ) {
        echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_username ) . '" />' . "\n";
    }
    
    // Title
    if ( is_singular() ) {
        echo '<meta name="twitter:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
    } elseif ( is_archive() || is_home() ) {
        echo '<meta name="twitter:title" content="' . esc_attr( aqualuxe_get_archive_title() ) . '" />' . "\n";
    } else {
        echo '<meta name="twitter:title" content="' . esc_attr( wp_get_document_title() ) . '" />' . "\n";
    }
    
    // Description
    $description = aqualuxe_get_open_graph_description();
    
    if ( $description ) {
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
    }
    
    // Image
    $image = aqualuxe_get_open_graph_image();
    
    if ( $image ) {
        echo '<meta name="twitter:image" content="' . esc_url( $image['url'] ) . '" />' . "\n";
    }
}

/**
 * Get Open Graph description
 *
 * @return string Open Graph description.
 */
function aqualuxe_get_open_graph_description() {
    $description = '';
    
    if ( is_singular() ) {
        // Get post excerpt
        $post_excerpt = get_the_excerpt();
        
        if ( $post_excerpt ) {
            $description = $post_excerpt;
        } else {
            // Get post content
            $post_content = get_the_content();
            
            if ( $post_content ) {
                $description = wp_trim_words( $post_content, 55, '...' );
            }
        }
    } elseif ( is_category() || is_tag() || is_tax() ) {
        // Get term description
        $term_description = term_description();
        
        if ( $term_description ) {
            $description = wp_strip_all_tags( $term_description );
        }
    } elseif ( is_author() ) {
        // Get author description
        $author_description = get_the_author_meta( 'description' );
        
        if ( $author_description ) {
            $description = $author_description;
        }
    }
    
    // Fallback to site description
    if ( ! $description ) {
        $description = get_bloginfo( 'description' );
    }
    
    return $description;
}

/**
 * Get Open Graph image
 *
 * @return array|false Open Graph image data or false if no image.
 */
function aqualuxe_get_open_graph_image() {
    $image = false;
    
    if ( is_singular() && has_post_thumbnail() ) {
        // Get featured image
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'large' );
        $image_meta = wp_get_attachment_metadata( $image_id );
        
        if ( $image_url && $image_meta ) {
            $image = array(
                'url'    => $image_url,
                'width'  => $image_meta['width'],
                'height' => $image_meta['height'],
            );
        }
    } elseif ( is_category() || is_tag() || is_tax() ) {
        // Get term image if available
        $term_id = get_queried_object_id();
        $term_image_id = get_term_meta( $term_id, 'thumbnail_id', true );
        
        if ( $term_image_id ) {
            $image_url = wp_get_attachment_image_url( $term_image_id, 'large' );
            $image_meta = wp_get_attachment_metadata( $term_image_id );
            
            if ( $image_url && $image_meta ) {
                $image = array(
                    'url'    => $image_url,
                    'width'  => $image_meta['width'],
                    'height' => $image_meta['height'],
                );
            }
        }
    }
    
    // Fallback to site logo
    if ( ! $image ) {
        $logo_id = get_theme_mod( 'custom_logo' );
        
        if ( $logo_id ) {
            $image_url = wp_get_attachment_image_url( $logo_id, 'full' );
            $image_meta = wp_get_attachment_metadata( $logo_id );
            
            if ( $image_url && $image_meta ) {
                $image = array(
                    'url'    => $image_url,
                    'width'  => $image_meta['width'],
                    'height' => $image_meta['height'],
                );
            }
        }
    }
    
    // Fallback to default Open Graph image
    if ( ! $image ) {
        $default_image_id = get_theme_mod( 'aqualuxe_default_og_image' );
        
        if ( $default_image_id ) {
            $image_url = wp_get_attachment_image_url( $default_image_id, 'large' );
            $image_meta = wp_get_attachment_metadata( $default_image_id );
            
            if ( $image_url && $image_meta ) {
                $image = array(
                    'url'    => $image_url,
                    'width'  => $image_meta['width'],
                    'height' => $image_meta['height'],
                );
            }
        }
    }
    
    return $image;
}

/**
 * Get archive title
 *
 * @return string Archive title.
 */
function aqualuxe_get_archive_title() {
    $title = '';
    
    if ( is_home() ) {
        $title = get_the_title( get_option( 'page_for_posts', true ) );
    } elseif ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = get_the_author();
    } elseif ( is_year() ) {
        $title = get_the_date( _x( 'Y', 'yearly archives date format', 'aqualuxe' ) );
    } elseif ( is_month() ) {
        $title = get_the_date( _x( 'F Y', 'monthly archives date format', 'aqualuxe' ) );
    } elseif ( is_day() ) {
        $title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'aqualuxe' ) );
    } elseif ( is_tax( 'post_format' ) ) {
        if ( is_tax( 'post_format', 'post-format-aside' ) ) {
            $title = _x( 'Asides', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
            $title = _x( 'Galleries', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
            $title = _x( 'Images', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
            $title = _x( 'Videos', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
            $title = _x( 'Quotes', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
            $title = _x( 'Links', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
            $title = _x( 'Statuses', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
            $title = _x( 'Audio', 'post format archive title', 'aqualuxe' );
        } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
            $title = _x( 'Chats', 'post format archive title', 'aqualuxe' );
        }
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    } elseif ( is_archive() ) {
        $title = __( 'Archives', 'aqualuxe' );
    }
    
    return $title;
}

/**
 * Get current URL
 *
 * @return string Current URL.
 */
function aqualuxe_get_current_url() {
    global $wp;
    
    return home_url( add_query_arg( array(), $wp->request ) );
}