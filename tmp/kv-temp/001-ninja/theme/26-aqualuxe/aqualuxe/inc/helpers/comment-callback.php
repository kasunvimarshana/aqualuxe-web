<?php
/**
 * Custom comment callback function
 *
 * @package AquaLuxe
 */

if ( ! function_exists( 'aqualuxe_comment_callback' ) ) :
    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @param object $comment Comment to display.
     * @param array  $args    Arguments passed to wp_list_comments().
     * @param int    $depth   Depth of comment.
     */
    function aqualuxe_comment_callback( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        $comment_type = get_comment_type();

        if ( 'pingback' === $comment_type || 'trackback' === $comment_type ) :
        ?>
        <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'bg-gray-50 dark:bg-gray-800 p-4 rounded-md' ); ?>>
            <div class="comment-body flex">
                <div class="comment-meta">
                    <div class="comment-author vcard">
                        <?php esc_html_e( 'Pingback:', 'aqualuxe' ); ?>
                        <?php comment_author_link(); ?>
                    </div>
                </div>

                <div class="comment-metadata ml-auto">
                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                        <time datetime="<?php comment_time( 'c' ); ?>">
                            <?php
                            /* translators: 1: date, 2: time */
                            printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), get_comment_date(), get_comment_time() );
                            ?>
                        </time>
                    </a>
                    <?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), '<span class="edit-link ml-2">', '</span>' ); ?>
                </div>
            </div>
        </li>
        <?php
        else :
        ?>
        <li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <div class="comment-meta mb-4">
                    <div class="comment-author vcard flex items-center">
                        <?php
                        if ( 0 !== $args['avatar_size'] ) {
                            echo '<div class="comment-avatar mr-4">';
                            echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full' ) );
                            echo '</div>';
                        }
                        ?>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                <?php comment_author_link(); ?>
                            </div>
                            <div class="comment-metadata text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                                    <time datetime="<?php comment_time( 'c' ); ?>">
                                        <?php
                                        /* translators: 1: date, 2: time */
                                        printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), get_comment_date(), get_comment_time() );
                                        ?>
                                    </time>
                                </a>
                                <?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), '<span class="edit-link ml-2">', '</span>' ); ?>
                            </div>
                        </div>
                    </div><!-- .comment-author -->

                    <?php if ( '0' === $comment->comment_approved ) : ?>
                    <div class="comment-awaiting-moderation mt-2 p-3 bg-yellow-50 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200 rounded-md">
                        <?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?>
                    </div>
                    <?php endif; ?>
                </div><!-- .comment-meta -->

                <div class="comment-content prose dark:prose-invert max-w-none">
                    <?php comment_text(); ?>
                </div><!-- .comment-content -->

                <?php
                comment_reply_link( array_merge( $args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<div class="reply mt-4">',
                    'after'     => '</div>',
                    'reply_text' => sprintf(
                        '<span class="inline-flex items-center text-sm font-medium text-primary hover:text-primary-dark transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            %s
                        </span>',
                        esc_html__( 'Reply', 'aqualuxe' )
                    ),
                ) ) );
                ?>
            </article><!-- .comment-body -->
        </li>
        <?php
        endif;
    }
endif;