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

    // Add a class if dark mode is enabled
    if ( aqualuxe_is_dark_mode() ) {
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
 * Check if dark mode is enabled
 *
 * @return boolean
 */
function aqualuxe_is_dark_mode() {
    $default = 'light';
    
    // Check for user preference in cookie
    if ( isset( $_COOKIE['aqualuxe_color_scheme'] ) ) {
        $mode = sanitize_text_field( $_COOKIE['aqualuxe_color_scheme'] );
        return $mode === 'dark';
    }
    
    // Check for theme default setting
    $default_mode = get_theme_mod( 'aqualuxe_default_color_scheme', $default );
    return $default_mode === 'dark';
}

/**
 * Add Open Graph Meta Tags
 */
function aqualuxe_add_open_graph_tags() {
    global $post;

    if ( is_singular() && $post ) {
        echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
        
        if ( has_post_thumbnail( $post->ID ) ) {
            $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
            echo '<meta property="og:image" content="' . esc_url( $thumbnail_src[0] ) . '" />' . "\n";
        }
        
        $excerpt = get_the_excerpt();
        if ( ! $excerpt ) {
            $excerpt = wp_trim_words( $post->post_content, 55, '...' );
        }
        echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_open_graph_tags' );

/**
 * Add Schema.org markup
 */
function aqualuxe_add_schema_markup() {
    // Basic site schema
    $schema = array(
        '@context'  => 'https://schema.org',
        '@type'     => 'WebSite',
        'name'      => get_bloginfo( 'name' ),
        'url'       => home_url(),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => home_url( '/?s={search_term_string}' ),
            'query-input' => 'required name=search_term_string',
        ),
    );

    // Add specific schema for single posts/pages
    if ( is_singular() ) {
        global $post;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Article',
            'headline' => get_the_title(),
            'url'      => get_permalink(),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'author' => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
                'logo'  => array(
                    '@type' => 'ImageObject',
                    'url'   => get_custom_logo() ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] : '',
                ),
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => get_permalink(),
            ),
        );

        // Add featured image if available
        if ( has_post_thumbnail() ) {
            $image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
            if ( $image_data ) {
                $schema['image'] = array(
                    '@type'  => 'ImageObject',
                    'url'    => $image_data[0],
                    'width'  => $image_data[1],
                    'height' => $image_data[2],
                );
            }
        }
    }

    // Add organization schema for the homepage
    if ( is_front_page() ) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => get_bloginfo( 'name' ),
            'url'      => home_url(),
            'logo'     => get_custom_logo() ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] : '',
            'contactPoint' => array(
                '@type'       => 'ContactPoint',
                'telephone'   => get_theme_mod( 'aqualuxe_contact_phone', '' ),
                'contactType' => 'customer service',
            ),
            'sameAs' => array(
                get_theme_mod( 'aqualuxe_social_facebook', '' ),
                get_theme_mod( 'aqualuxe_social_twitter', '' ),
                get_theme_mod( 'aqualuxe_social_instagram', '' ),
                get_theme_mod( 'aqualuxe_social_linkedin', '' ),
            ),
        );
    }

    // Output the schema markup
    if ( ! empty( $schema ) ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_schema_markup' );

/**
 * Modify the excerpt length
 */
function aqualuxe_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Modify the excerpt more string
 */
function aqualuxe_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Get related products based on category
 *
 * @param int $post_id
 * @param int $related_count
 * @param string $post_type
 * @return array
 */
function aqualuxe_get_related_posts( $post_id, $related_count, $post_type = 'post' ) {
    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => $related_count,
        'post_status'    => 'publish',
        'post__not_in'   => array( $post_id ),
        'orderby'        => 'rand',
    );

    // For posts, get related by category
    if ( $post_type === 'post' ) {
        $categories = get_the_category( $post_id );
        if ( $categories ) {
            $category_ids = array();
            foreach ( $categories as $category ) {
                $category_ids[] = $category->term_id;
            }
            $args['category__in'] = $category_ids;
        }
    }

    // For products, get related by product category
    if ( $post_type === 'product' && function_exists( 'wc_get_product_term_ids' ) ) {
        $product_cats = wc_get_product_term_ids( $post_id, 'product_cat' );
        if ( $product_cats ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $product_cats,
                ),
            );
        }
    }

    return get_posts( $args );
}

/**
 * Get hierarchical taxonomy terms
 *
 * @param int $post_id
 * @param string $taxonomy
 * @return array
 */
function aqualuxe_get_hierarchical_terms( $post_id, $taxonomy ) {
    $terms = get_the_terms( $post_id, $taxonomy );
    if ( ! $terms || is_wp_error( $terms ) ) {
        return array();
    }

    $sorted_terms = array();
    foreach ( $terms as $term ) {
        if ( $term->parent == 0 ) {
            $sorted_terms[] = $term;
            $children = get_term_children( $term->term_id, $taxonomy );
            foreach ( $children as $child_id ) {
                foreach ( $terms as $term_child ) {
                    if ( $term_child->term_id == $child_id ) {
                        $sorted_terms[] = $term_child;
                    }
                }
            }
        }
    }

    return $sorted_terms;
}