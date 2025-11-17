<?php
/**
 * Custom comment callback for styling comments
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Custom comment callback for styling comments
 *
 * @param object $comment The comment object.
 * @param array  $args    Comment arguments.
 * @param int    $depth   Comment depth.
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    $commenter = wp_get_current_commenter();
    $show_pending_links = ! empty( $commenter['comment_author'] );
    
    if ( $commenter['comment_author_email'] ) {
        $moderation_note = __( 'Your comment is awaiting moderation.', 'aqualuxe' );
    } else {
        $moderation_note = __( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'aqualuxe' );
    }
    ?>
    
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item bg-white dark:bg-dark-700 rounded-xl shadow-soft p-6', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta mb-4">
                <div class="comment-author vcard flex items-center">
                    <?php
                    if ( 0 != $args['avatar_size'] ) {
                        echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full mr-4' ) );
                    }
                    ?>
                    <div>
                        <?php
                        printf(
                            /* translators: %s: comment author link */
                            '<h4 class="font-bold text-dark-800 dark:text-white">%s</h4>',
                            get_comment_author_link( $comment )
                        );
                        ?>
                        <div class="comment-metadata text-sm text-dark-500 dark:text-dark-300">
                            <?php
                            printf(
                                '<time datetime="%1$s">%2$s</time>',
                                get_comment_time( 'c' ),
                                sprintf(
                                    /* translators: 1: comment date, 2: comment time */
                                    __( '%1$s at %2$s', 'aqualuxe' ),
                                    get_comment_date( '', $comment ),
                                    get_comment_time()
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div><!-- .comment-author -->

                <?php if ( '0' == $comment->comment_approved ) : ?>
                <div class="comment-awaiting-moderation mt-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-200 text-sm rounded-lg">
                    <?php echo $moderation_note; ?>
                </div>
                <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content prose dark:prose-invert">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <div class="reply mt-4 flex justify-end">
                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '<div class="reply-link">',
                            'after'     => '</div>',
                        )
                    )
                );
                ?>
                
                <?php edit_comment_link( __( 'Edit', 'aqualuxe' ), '<div class="edit-link ml-4">', '</div>' ); ?>
            </div>
        </article><!-- .comment-body -->
    <?php
}