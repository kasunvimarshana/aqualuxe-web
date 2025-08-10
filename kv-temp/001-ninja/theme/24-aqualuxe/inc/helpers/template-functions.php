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
    
    // Check for system preference if no cookie is set
    if ( isset( $_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'] ) ) {
        return $_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'] === 'dark';
    }
    
    // Fall back to theme default setting
    $default_scheme = get_theme_mod( 'aqualuxe_default_color_scheme', $default );
    return $default_scheme === 'dark';
}

/**
 * Add Open Graph Meta Tags
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
        
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
        
        $excerpt = get_the_excerpt();
        if ( ! $excerpt ) {
            $excerpt = wp_trim_words( $post->post_content, 55, '...' );
        }
        echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '" />' . "\n";
    } else {
        echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '" />' . "\n";
        
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo_src = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            echo '<meta property="og:image" content="' . esc_url( $logo_src[0] ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_tags', 5 );

/**
 * Add Schema.org markup
 */
function aqualuxe_add_schema_markup() {
    // Organization schema
    $schema = array(
        '@context'  => 'https://schema.org',
        '@type'     => 'Organization',
        'name'      => get_bloginfo( 'name' ),
        'url'       => home_url( '/' ),
        'logo'      => get_custom_logo_url(),
        'contactPoint' => array(
            '@type'             => 'ContactPoint',
            'telephone'         => get_theme_mod( 'aqualuxe_phone', '+1-234-567-8901' ),
            'contactType'       => 'customer service',
            'availableLanguage' => array( 'English' ),
        ),
        'sameAs' => array(
            get_theme_mod( 'aqualuxe_facebook', '#' ),
            get_theme_mod( 'aqualuxe_twitter', '#' ),
            get_theme_mod( 'aqualuxe_instagram', '#' ),
            get_theme_mod( 'aqualuxe_youtube', '#' ),
        )
    );

    // Add WebSite schema
    $website_schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'WebSite',
        'name'     => get_bloginfo( 'name' ),
        'url'      => home_url( '/' ),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => home_url( '/' ) . '?s={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ),
    );

    // Add additional schema for specific pages
    if ( is_singular( 'post' ) ) {
        global $post;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'BlogPosting',
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => get_permalink(),
            ),
            'headline'      => get_the_title(),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'author'        => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
            ),
            'publisher'     => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
                'logo'  => array(
                    '@type'  => 'ImageObject',
                    'url'    => get_custom_logo_url(),
                ),
            ),
        );

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
    } elseif ( is_product() ) {
        global $product;
        
        if ( $product ) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type'    => 'Product',
                'name'     => $product->get_name(),
                'description' => $product->get_short_description() ? $product->get_short_description() : $product->get_description(),
                'sku'      => $product->get_sku(),
                'brand'    => array(
                    '@type' => 'Brand',
                    'name'  => get_bloginfo( 'name' ),
                ),
                'offers'   => array(
                    '@type'         => 'Offer',
                    'price'         => $product->get_price(),
                    'priceCurrency' => get_woocommerce_currency(),
                    'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    'url'           => get_permalink(),
                ),
            );

            if ( $product->get_rating_count() > 0 ) {
                $schema['aggregateRating'] = array(
                    '@type'       => 'AggregateRating',
                    'ratingValue' => $product->get_average_rating(),
                    'reviewCount' => $product->get_review_count(),
                );
            }

            if ( has_post_thumbnail() ) {
                $image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                if ( $image_data ) {
                    $schema['image'] = $image_data[0];
                }
            }
        }
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
    
    if ( is_front_page() ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $website_schema ) . '</script>' . "\n";
    }
}
add_action( 'wp_footer', 'aqualuxe_add_schema_markup' );

/**
 * Helper function to get custom logo URL
 */
function get_custom_logo_url() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_src = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        return $logo_src[0];
    }
    return '';
}

/**
 * Add responsive image support
 */
function aqualuxe_responsive_image_sizes( $sizes, $size ) {
    return '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw';
}
add_filter( 'wp_calculate_image_sizes', 'aqualuxe_responsive_image_sizes', 10, 2 );

/**
 * Implement lazy loading for images
 */
function aqualuxe_lazy_load_images( $content ) {
    if ( is_admin() || is_feed() ) {
        return $content;
    }

    // Skip if the content already has lazy loading (WordPress 5.5+)
    if ( function_exists( 'wp_lazy_loading_enabled' ) && wp_lazy_loading_enabled( 'img', 'the_content' ) ) {
        return $content;
    }

    // Add loading="lazy" to img tags
    return preg_replace( '/<img(.*?)>/i', '<img$1 loading="lazy">', $content );
}
add_filter( 'the_content', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'post_thumbnail_html', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'get_avatar', 'aqualuxe_lazy_load_images', 99 );

/**
 * Custom comment callback
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    $commenter = wp_get_current_commenter();
    $show_pending_links = ! empty( $commenter['comment_author'] );
    
    if ( $commenter['comment_author_email'] ) {
        $moderation_note = esc_html__( 'Your comment is awaiting moderation.', 'aqualuxe' );
    } else {
        $moderation_note = esc_html__( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'aqualuxe' );
    }
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta mb-4">
                <div class="comment-author vcard flex items-center">
                    <?php
                    if ( 0 != $args['avatar_size'] ) {
                        echo '<div class="comment-avatar mr-4">';
                        echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full' ) );
                        echo '</div>';
                    }
                    ?>
                    <div>
                        <?php
                        printf(
                            '<h4 class="font-medium text-lg">%s</h4>',
                            get_comment_author_link( $comment )
                        );
                        ?>
                        <div class="comment-metadata flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <?php
                            printf(
                                '<time datetime="%1$s">%2$s</time>',
                                get_comment_time( 'c' ),
                                sprintf(
                                    /* translators: 1: Comment date, 2: Comment time */
                                    esc_html__( '%1$s at %2$s', 'aqualuxe' ),
                                    get_comment_date( '', $comment ),
                                    get_comment_time()
                                )
                            );
                            
                            edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), ' <span class="edit-link ml-2">', '</span>' );
                            ?>
                        </div>
                    </div>
                </div><!-- .comment-author -->

                <?php if ( '0' == $comment->comment_approved ) : ?>
                <div class="comment-awaiting-moderation mt-2 p-3 bg-yellow-50 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-md text-sm">
                    <?php echo $moderation_note; ?>
                </div>
                <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content prose dark:prose-invert max-w-none">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <?php
            if ( '1' == $comment->comment_approved || $show_pending_links ) {
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '<div class="reply mt-4">',
                            'after'     => '</div>',
                        )
                    )
                );
            }
            ?>
        </article><!-- .comment-body -->
    <?php
}