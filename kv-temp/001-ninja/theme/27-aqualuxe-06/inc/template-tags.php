<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if ( ! function_exists( 'aqualuxe_posted_on' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time.
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
endif;

if ( ! function_exists( 'aqualuxe_posted_by' ) ) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function aqualuxe_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }
endif;

if ( ! function_exists( 'aqualuxe_entry_footer' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
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
endif;

if ( ! function_exists( 'aqualuxe_post_thumbnail' ) ) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function aqualuxe_post_thumbnail() {
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }

        if ( is_singular() ) :
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail( 'full', array( 'class' => 'rounded-lg w-full h-auto' ) ); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                        'class' => 'rounded-lg w-full h-auto',
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
    /**
     * Shim for sites older than 5.2.
     *
     * @link https://core.trac.wordpress.org/ticket/12563
     */
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
endif;

if ( ! function_exists( 'aqualuxe_comment_callback' ) ) :
    /**
     * Custom comment callback
     */
    function aqualuxe_comment_callback( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        ?>
        <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'mb-6 pb-6 border-b border-gray-200 last:border-0' ); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                <footer class="comment-meta mb-2">
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
                                '<b class="fn">%s</b>',
                                get_comment_author_link()
                            );
                            ?>
                            <div class="comment-metadata text-sm text-gray-600">
                                <time datetime="<?php comment_time( 'c' ); ?>">
                                    <?php
                                    printf(
                                        /* translators: 1: comment date, 2: comment time */
                                        esc_html__( '%1$s at %2$s', 'aqualuxe' ),
                                        esc_html( get_comment_date() ),
                                        esc_html( get_comment_time() )
                                    );
                                    ?>
                                </time>
                                <?php edit_comment_link( esc_html__( '(Edit)', 'aqualuxe' ), ' <span class="edit-link">', '</span>' ); ?>
                            </div>
                        </div>
                    </div><!-- .comment-author -->

                    <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation text-sm text-yellow-600 mt-2"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></p>
                    <?php endif; ?>
                </footer><!-- .comment-meta -->

                <div class="comment-content prose max-w-none">
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
endif;