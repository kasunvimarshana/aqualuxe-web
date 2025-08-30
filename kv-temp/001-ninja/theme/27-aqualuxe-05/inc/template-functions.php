<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }

    // Add a class if dark mode is active
    if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) && 'true' === $_COOKIE['aqualuxe_dark_mode'] ) {
        $classes[] = 'dark-mode';
    }

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Change the excerpt length
 */
function aqualuxe_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Change the excerpt more string
 */
function aqualuxe_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add schema markup to the authors post link
 */
function aqualuxe_schema_url( $url ) {
    $url = str_replace( 'rel="author"', 'rel="author" itemprop="url"', $url );
    return $url;
}
add_filter( 'the_author_posts_link', 'aqualuxe_schema_url' );

/**
 * Remove "Category:", "Tag:", "Author:" from the archive title
 */
function aqualuxe_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }

    return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_archive_title' );

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes() {
    add_image_size( 'aqualuxe-featured', 1200, 600, true );
    add_image_size( 'aqualuxe-square', 600, 600, true );
    add_image_size( 'aqualuxe-portrait', 600, 900, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to media library
 */
function aqualuxe_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
        'aqualuxe-square'   => __( 'Square', 'aqualuxe' ),
        'aqualuxe-portrait' => __( 'Portrait', 'aqualuxe' ),
    ) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_opengraph_tags() {
    global $post;

    if ( is_singular() && $post ) {
        echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
        
        if ( has_post_thumbnail( $post->ID ) ) {
            $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
            echo '<meta property="og:image" content="' . esc_url( $thumbnail_src[0] ) . '" />' . "\n";
        }
        
        $excerpt = strip_tags( get_the_excerpt() );
        echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_tags' );

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_add_twitter_card_tags() {
    global $post;

    if ( is_singular() && $post ) {
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
        
        $twitter_handle = get_theme_mod( 'aqualuxe_twitter_handle', '' );
        if ( $twitter_handle ) {
            echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_handle ) . '" />' . "\n";
        }
        
        if ( has_post_thumbnail( $post->ID ) ) {
            $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
            echo '<meta name="twitter:image" content="' . esc_url( $thumbnail_src[0] ) . '" />' . "\n";
        }
        
        $excerpt = strip_tags( get_the_excerpt() );
        echo '<meta name="twitter:description" content="' . esc_attr( $excerpt ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_twitter_card_tags' );

/**
 * Add schema.org markup
 */
function aqualuxe_add_schema_markup() {
    echo '<script type="application/ld+json">';
    echo '{';
    echo '"@context": "https://schema.org",';
    
    if ( is_singular( 'post' ) ) {
        echo '"@type": "BlogPosting",';
        echo '"headline": "' . esc_js( get_the_title() ) . '",';
        echo '"datePublished": "' . esc_js( get_the_date( 'c' ) ) . '",';
        echo '"dateModified": "' . esc_js( get_the_modified_date( 'c' ) ) . '",';
        
        if ( has_post_thumbnail() ) {
            $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
            echo '"image": "' . esc_url( $thumbnail_src[0] ) . '",';
        }
        
        echo '"author": {';
        echo '"@type": "Person",';
        echo '"name": "' . esc_js( get_the_author() ) . '"';
        echo '},';
        
        echo '"publisher": {';
        echo '"@type": "Organization",';
        echo '"name": "' . esc_js( get_bloginfo( 'name' ) ) . '",';
        
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo_src = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            echo '"logo": {';
            echo '"@type": "ImageObject",';
            echo '"url": "' . esc_url( $logo_src[0] ) . '"';
            echo '}';
        }
        
        echo '}';
    } elseif ( is_page() ) {
        echo '"@type": "WebPage",';
        echo '"name": "' . esc_js( get_the_title() ) . '",';
        echo '"description": "' . esc_js( get_the_excerpt() ) . '"';
    } elseif ( is_home() || is_archive() ) {
        echo '"@type": "Blog",';
        echo '"name": "' . esc_js( get_bloginfo( 'name' ) ) . '",';
        echo '"description": "' . esc_js( get_bloginfo( 'description' ) ) . '"';
    } else {
        echo '"@type": "WebSite",';
        echo '"name": "' . esc_js( get_bloginfo( 'name' ) ) . '",';
        echo '"description": "' . esc_js( get_bloginfo( 'description' ) ) . '",';
        echo '"url": "' . esc_js( home_url( '/' ) ) . '"';
    }
    
    echo '}';
    echo '</script>';
}
add_action( 'wp_head', 'aqualuxe_add_schema_markup' );

/**
 * Add custom classes to navigation menu items
 */
function aqualuxe_nav_menu_css_class( $classes, $item, $args, $depth ) {
    if ( 'primary' === $args->theme_location ) {
        $classes[] = 'nav-item';
        
        if ( in_array( 'menu-item-has-children', $classes ) ) {
            $classes[] = 'dropdown';
        }
    }
    
    return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 4 );

/**
 * Add custom classes to navigation menu links
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
    if ( 'primary' === $args->theme_location ) {
        $atts['class'] = 'nav-link hover:text-blue-600 transition-colors';
        
        if ( in_array( 'current-menu-item', $item->classes ) ) {
            $atts['class'] .= ' text-blue-600';
        }
        
        if ( in_array( 'menu-item-has-children', $item->classes ) ) {
            $atts['class'] .= ' dropdown-toggle';
            $atts['data-toggle'] = 'dropdown';
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }
    }
    
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4 );

/**
 * Add custom classes to navigation menu sub-menus
 */
function aqualuxe_nav_menu_submenu_css_class( $classes, $args, $depth ) {
    if ( 'primary' === $args->theme_location ) {
        $classes[] = 'dropdown-menu';
    }
    
    return $classes;
}
add_filter( 'nav_menu_submenu_css_class', 'aqualuxe_nav_menu_submenu_css_class', 10, 3 );