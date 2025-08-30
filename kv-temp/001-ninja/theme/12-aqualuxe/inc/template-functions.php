<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 * @since 1.0.0
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

    // Add class for the header style
    $header_style = get_theme_mod( 'aqualuxe_header_style', 'default' );
    $classes[] = 'header-style-' . $header_style;

    // Add class for the footer style
    $footer_style = get_theme_mod( 'aqualuxe_footer_style', 'default' );
    $classes[] = 'footer-style-' . $footer_style;

    // Add class for the color scheme
    $color_scheme = get_theme_mod( 'aqualuxe_color_scheme', 'default' );
    $classes[] = 'color-scheme-' . $color_scheme;

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
 * Adds custom meta tags to the header
 */
function aqualuxe_meta_tags() {
    // Add viewport meta tag
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    
    // Add theme color meta tag
    $theme_color = get_theme_mod( 'aqualuxe_theme_color', '#0e7c7b' );
    echo '<meta name="theme-color" content="' . esc_attr( $theme_color ) . '">' . "\n";
    
    // Add Open Graph meta tags
    if ( is_singular() ) {
        echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
        
        if ( has_post_thumbnail() ) {
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'large' );
            echo '<meta property="og:image" content="' . esc_url( $thumbnail_src[0] ) . '">' . "\n";
        }
        
        $excerpt = get_the_excerpt();
        if ( ! empty( $excerpt ) ) {
            echo '<meta property="og:description" content="' . esc_attr( wp_strip_all_tags( $excerpt ) ) . '">' . "\n";
        }
    }
    
    // Add site name
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
}
add_action( 'wp_head', 'aqualuxe_meta_tags', 1 );

/**
 * Adds schema.org structured data
 */
function aqualuxe_schema_org() {
    // Add WebSite schema
    echo '<script type="application/ld+json">';
    echo json_encode(
        array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'url' => esc_url( home_url( '/' ) ),
            'name' => esc_html( get_bloginfo( 'name' ) ),
            'description' => esc_html( get_bloginfo( 'description' ) ),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => esc_url( home_url( '/' ) ) . '?s={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ),
        )
    );
    echo '</script>';
    
    // Add Organization schema
    $logo = get_theme_mod( 'custom_logo' );
    $logo_url = wp_get_attachment_image_url( $logo, 'full' );
    
    echo '<script type="application/ld+json">';
    echo json_encode(
        array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'url' => esc_url( home_url( '/' ) ),
            'name' => esc_html( get_bloginfo( 'name' ) ),
            'logo' => $logo_url ? esc_url( $logo_url ) : '',
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'telephone' => esc_html( get_theme_mod( 'aqualuxe_phone', '+1 (555) 123-4567' ) ),
                'contactType' => 'customer service',
            ),
            'sameAs' => array(
                get_theme_mod( 'aqualuxe_facebook', '' ),
                get_theme_mod( 'aqualuxe_twitter', '' ),
                get_theme_mod( 'aqualuxe_instagram', '' ),
                get_theme_mod( 'aqualuxe_youtube', '' ),
                get_theme_mod( 'aqualuxe_pinterest', '' ),
                get_theme_mod( 'aqualuxe_linkedin', '' ),
            ),
        )
    );
    echo '</script>';
    
    // Add Article schema for single posts
    if ( is_singular( 'post' ) ) {
        global $post;
        $thumbnail_id = get_post_thumbnail_id( $post->ID );
        $thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'large' );
        
        echo '<script type="application/ld+json">';
        echo json_encode(
            array(
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'mainEntityOfPage' => array(
                    '@type' => 'WebPage',
                    '@id' => esc_url( get_permalink() ),
                ),
                'headline' => esc_html( get_the_title() ),
                'image' => $thumbnail_src ? esc_url( $thumbnail_src[0] ) : '',
                'datePublished' => esc_html( get_the_date( 'c' ) ),
                'dateModified' => esc_html( get_the_modified_date( 'c' ) ),
                'author' => array(
                    '@type' => 'Person',
                    'name' => esc_html( get_the_author() ),
                ),
                'publisher' => array(
                    '@type' => 'Organization',
                    'name' => esc_html( get_bloginfo( 'name' ) ),
                    'logo' => array(
                        '@type' => 'ImageObject',
                        'url' => $logo_url ? esc_url( $logo_url ) : '',
                    ),
                ),
                'description' => esc_html( wp_strip_all_tags( get_the_excerpt() ) ),
            )
        );
        echo '</script>';
    }
    
    // Add Product schema for WooCommerce products
    if ( class_exists( 'WooCommerce' ) && is_product() ) {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $image_id = $product->get_image_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'large' );
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => esc_html( $product->get_name() ),
            'image' => $image_url ? esc_url( $image_url ) : '',
            'description' => esc_html( wp_strip_all_tags( $product->get_short_description() ) ),
            'sku' => esc_html( $product->get_sku() ),
            'brand' => array(
                '@type' => 'Brand',
                'name' => esc_html( get_bloginfo( 'name' ) ),
            ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => esc_html( $product->get_price() ),
                'priceCurrency' => esc_html( get_woocommerce_currency() ),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => esc_url( get_permalink() ),
            ),
        );
        
        // Add review data if available
        if ( $product->get_review_count() > 0 ) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => esc_html( $product->get_average_rating() ),
                'reviewCount' => esc_html( $product->get_review_count() ),
            );
        }
        
        echo '<script type="application/ld+json">';
        echo json_encode( $schema );
        echo '</script>';
    }
}
add_action( 'wp_head', 'aqualuxe_schema_org' );

/**
 * Displays the posted date
 */
function aqualuxe_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x( 'Posted on %s', 'post date', 'aqualuxe' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on"><i class="far fa-calendar-alt"></i> ' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays the post author
 */
function aqualuxe_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );

    echo '<span class="byline"><i class="far fa-user"></i> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays the post categories and tags
 */
function aqualuxe_entry_footer() {
    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
        if ( $categories_list ) {
            /* translators: 1: list of categories. */
            printf( '<span class="cat-links"><i class="fas fa-folder"></i> ' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
        if ( $tags_list ) {
            /* translators: 1: list of tags. */
            printf( '<span class="tags-links"><i class="fas fa-tags"></i> ' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo '<span class="comments-link"><i class="far fa-comment"></i> ';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            )
        );
        echo '</span>';
    }

    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            wp_kses_post( get_the_title() )
        ),
        '<span class="edit-link"><i class="fas fa-edit"></i> ',
        '</span>'
    );
}

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length( $length ) {
    return get_theme_mod( 'aqualuxe_excerpt_length', 30 );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length', 999 );

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return array The filtered attributes for the image markup.
 */
function aqualuxe_image_sizes_attr( $attr, $attachment, $size ) {
    if ( is_array( $size ) ) {
        $attr['sizes'] = '100vw';
    } else {
        $attr['sizes'] = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw';
    }

    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_image_sizes_attr', 10, 3 );

/**
 * Add lazy loading attribute to images
 */
function aqualuxe_add_lazyload( $content ) {
    if ( is_admin() || is_feed() || is_preview() ) {
        return $content;
    }

    // Don't lazy-load if the content has already been processed
    if ( false !== strpos( $content, 'data-src' ) ) {
        return $content;
    }

    // Replace the src attribute with data-src
    $content = preg_replace( '/<img(.*?)src=[\'"](.*?)[\'"]/', '<img$1src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="$2" loading="lazy"', $content );

    // Add the lazy class to the img element
    $content = preg_replace( '/<img(.*?)class=[\'"]/', '<img$1class="lazyload ', $content );
    $content = preg_replace( '/<img(.*?)(?!\bclass=)/', '<img$1 class="lazyload"', $content );

    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_lazyload', 99 );
add_filter( 'post_thumbnail_html', 'aqualuxe_add_lazyload', 99 );
add_filter( 'get_avatar', 'aqualuxe_add_lazyload', 99 );

/**
 * Add responsive container to embeds
 */
function aqualuxe_embed_wrapper( $html ) {
    return '<div class="responsive-embed">' . $html . '</div>';
}
add_filter( 'embed_oembed_html', 'aqualuxe_embed_wrapper', 10 );

/**
 * Sanitize HTML with allowed tags
 */
function aqualuxe_sanitize_html( $input ) {
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
            'rel' => array(),
            'class' => array(),
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'span' => array(
            'class' => array(),
        ),
        'i' => array(
            'class' => array(),
        ),
    );

    return wp_kses( $input, $allowed_html );
}

/**
 * Get related products based on categories
 */
function aqualuxe_get_related_products( $product_id, $number = 4 ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    $product_categories = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $number,
        'post__not_in' => array( $product_id ),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $product_categories,
            ),
        ),
    );
    
    return wc_get_products( $args );
}

/**
 * Get featured products
 */
function aqualuxe_get_featured_products( $number = 4 ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $number,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
            ),
        ),
    );
    
    return wc_get_products( $args );
}

/**
 * Get best selling products
 */
function aqualuxe_get_best_selling_products( $number = 4 ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $number,
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
    );
    
    return wc_get_products( $args );
}

/**
 * Get new products
 */
function aqualuxe_get_new_products( $number = 4 ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $number,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    return wc_get_products( $args );
}

/**
 * Get sale products
 */
function aqualuxe_get_sale_products( $number = 4 ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $number,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'numeric',
            ),
            array(
                'key' => '_min_variation_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'numeric',
            ),
        ),
    );
    
    return wc_get_products( $args );
}

/**
 * Get product categories
 */
function aqualuxe_get_product_categories( $number = 4 ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    $args = array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'number' => $number,
    );
    
    return get_terms( $args );
}

/**
 * Get testimonials
 */
function aqualuxe_get_testimonials( $number = 3 ) {
    $args = array(
        'post_type' => 'testimonial',
        'posts_per_page' => $number,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    return get_posts( $args );
}

/**
 * Get team members
 */
function aqualuxe_get_team_members( $number = 4 ) {
    $args = array(
        'post_type' => 'team',
        'posts_per_page' => $number,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    
    return get_posts( $args );
}

/**
 * Get FAQs
 */
function aqualuxe_get_faqs( $number = -1 ) {
    $args = array(
        'post_type' => 'faq',
        'posts_per_page' => $number,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    
    return get_posts( $args );
}

/**
 * Get recent blog posts
 */
function aqualuxe_get_recent_posts( $number = 3 ) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $number,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    return get_posts( $args );
}