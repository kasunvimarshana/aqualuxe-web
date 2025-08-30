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
 * Display the post date/time.
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

    echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display the post author.
 */
function aqualuxe_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display the post categories and tags.
 */
function aqualuxe_entry_footer() {
    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
        if ( $categories_list ) {
            /* translators: 1: list of categories. */
            printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
        if ( $tags_list ) {
            /* translators: 1: list of tags. */
            printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo '<span class="comments-link">';
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
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Custom comment callback.
 *
 * @param object $comment Comment object.
 * @param array  $args Comment arguments.
 * @param int    $depth Comment depth.
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body bg-gray-50 dark:bg-gray-800 p-6 rounded-lg mb-6">
            <div class="comment-meta mb-4">
                <div class="flex items-center">
                    <div class="comment-author vcard mr-4">
                        <?php
                        if ( 0 !== $args['avatar_size'] ) {
                            echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full' ) );
                        }
                        ?>
                    </div><!-- .comment-author -->

                    <div>
                        <?php
                        printf(
                            '<h4 class="fn font-bold">%s</h4>',
                            get_comment_author_link( $comment )
                        );
                        ?>
                        <div class="comment-metadata text-sm text-gray-600 dark:text-gray-400">
                            <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                                <time datetime="<?php comment_time( 'c' ); ?>">
                                    <?php
                                    /* translators: 1: comment date, 2: comment time */
                                    printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), esc_html( get_comment_date( '', $comment ) ), esc_html( get_comment_time() ) );
                                    ?>
                                </time>
                            </a>
                            <?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), '<span class="edit-link ml-2">', '</span>' ); ?>
                        </div><!-- .comment-metadata -->
                    </div>
                </div>

                <?php if ( '0' === $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation mt-2 text-yellow-600 dark:text-yellow-400"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></p>
                <?php endif; ?>
            </div><!-- .comment-meta -->

            <div class="comment-content prose dark:prose-invert max-w-none">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <?php
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
            ?>
        </article><!-- .comment-body -->
    <?php
}

/**
 * Implement the Custom Header feature.
 */
function aqualuxe_custom_header_setup() {
    add_theme_support(
        'custom-header',
        apply_filters(
            'aqualuxe_custom_header_args',
            array(
                'default-image'      => '',
                'default-text-color' => '000000',
                'width'              => 1920,
                'height'             => 250,
                'flex-height'        => true,
                'wp-head-callback'   => 'aqualuxe_header_style',
            )
        )
    );
}
add_action( 'after_setup_theme', 'aqualuxe_custom_header_setup' );

/**
 * Styles the header image and text displayed on the blog.
 *
 * @see aqualuxe_custom_header_setup().
 */
function aqualuxe_header_style() {
    $header_text_color = get_header_textcolor();

    /*
     * If no custom options for text are set, let's bail.
     * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
     */
    if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
        return;
    }

    // If we get this far, we have custom styles. Let's do this.
    ?>
    <style type="text/css">
    <?php
    // Has the text been hidden?
    if ( ! display_header_text() ) :
        ?>
        .site-title,
        .site-description {
            position: absolute;
            clip: rect(1px, 1px, 1px, 1px);
        }
    <?php
    // If the user has set a custom color for the text use that.
    else :
        ?>
        .site-title a,
        .site-description {
            color: #<?php echo esc_attr( $header_text_color ); ?>;
        }
    <?php endif; ?>
    </style>
    <?php
}

/**
 * Add custom image sizes.
 */
function aqualuxe_add_image_sizes() {
    add_image_size( 'aqualuxe-featured', 1200, 675, true );
    add_image_size( 'aqualuxe-thumbnail', 600, 400, true );
    add_image_size( 'aqualuxe-square', 600, 600, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to the media library.
 *
 * @param array $sizes Image sizes.
 * @return array
 */
function aqualuxe_custom_image_sizes( $sizes ) {
    return array_merge(
        $sizes,
        array(
            'aqualuxe-featured'  => esc_html__( 'AquaLuxe Featured', 'aqualuxe' ),
            'aqualuxe-thumbnail' => esc_html__( 'AquaLuxe Thumbnail', 'aqualuxe' ),
            'aqualuxe-square'    => esc_html__( 'AquaLuxe Square', 'aqualuxe' ),
        )
    );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Customize excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int
 */
function aqualuxe_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Customize excerpt more.
 *
 * @param string $more More string.
 * @return string
 */
function aqualuxe_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add SVG support.
 *
 * @param array $mimes Mime types.
 * @return array
 */
function aqualuxe_mime_types( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'aqualuxe_mime_types' );

/**
 * Add schema markup to the body element.
 *
 * @param array $attr Body attributes.
 * @return array
 */
function aqualuxe_body_itemtype( $attr ) {
    if ( ! is_singular() ) {
        $attr['itemtype']  = 'https://schema.org/WebPage';
        $attr['itemscope'] = 'itemscope';
    } elseif ( is_page() ) {
        $attr['itemtype']  = 'https://schema.org/WebPage';
        $attr['itemscope'] = 'itemscope';
    } elseif ( is_singular( 'post' ) ) {
        $attr['itemtype']  = 'https://schema.org/Blog';
        $attr['itemscope'] = 'itemscope';
    } elseif ( is_singular( 'product' ) && class_exists( 'WooCommerce' ) ) {
        $attr['itemtype']  = 'https://schema.org/Product';
        $attr['itemscope'] = 'itemscope';
    }

    return $attr;
}
add_filter( 'aqualuxe_body_attributes', 'aqualuxe_body_itemtype' );

/**
 * Add responsive embed wrapper around oEmbed content.
 *
 * @param string $html oEmbed HTML.
 * @return string
 */
function aqualuxe_responsive_embeds( $html ) {
    return '<div class="responsive-embed">' . $html . '</div>';
}
add_filter( 'embed_oembed_html', 'aqualuxe_responsive_embeds', 10, 3 );