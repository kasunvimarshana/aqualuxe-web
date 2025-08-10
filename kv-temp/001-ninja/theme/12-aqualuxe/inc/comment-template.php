<?php
/**
 * Custom comment template functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!function_exists('aqualuxe_comment_callback')) {
    /**
     * Custom comment callback
     *
     * @param object $comment Comment object.
     * @param array  $args    Comment arguments.
     * @param int    $depth   Comment depth.
     */
    function aqualuxe_comment_callback($comment, $args, $depth) {
        $tag = ('div' === $args['style']) ? 'div' : 'li';
        $commenter = wp_get_current_commenter();
        $show_pending_links = !empty($commenter['comment_author']);
        
        if ($commenter['comment_author_email']) {
            $moderation_note = esc_html__('Your comment is awaiting moderation.', 'aqualuxe');
        } else {
            $moderation_note = esc_html__('Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'aqualuxe');
        }
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? 'parent' : '', $comment); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                <div class="comment-meta">
                    <div class="comment-author vcard">
                        <?php
                        if (0 != $args['avatar_size']) {
                            echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'comment-avatar'));
                        }
                        ?>
                        <div class="comment-author-info">
                            <?php
                            $comment_author = get_comment_author_link($comment);
                            
                            if ('0' == $comment->comment_approved && !$show_pending_links) {
                                $comment_author = get_comment_author($comment);
                            }
                            
                            printf(
                                /* translators: %s: Comment author link. */
                                '<h4 class="fn">%s</h4>',
                                wp_kses_post($comment_author)
                            );
                            ?>
                            <div class="comment-metadata">
                                <?php
                                printf(
                                    '<a href="%s" class="comment-date"><time datetime="%s">%s</time></a>',
                                    esc_url(get_comment_link($comment, $args)),
                                    esc_attr(get_comment_time('c')),
                                    esc_html(sprintf(
                                        /* translators: 1: Comment date, 2: Comment time. */
                                        __('%1$s at %2$s', 'aqualuxe'),
                                        get_comment_date('', $comment),
                                        get_comment_time()
                                    ))
                                );
                                
                                edit_comment_link(esc_html__('Edit', 'aqualuxe'), ' <span class="edit-link">', '</span>');
                                ?>
                            </div>
                        </div>
                    </div><!-- .comment-author -->
                    
                    <?php if ('0' == $comment->comment_approved) : ?>
                        <div class="comment-awaiting-moderation">
                            <?php echo wp_kses_post($moderation_note); ?>
                        </div>
                    <?php endif; ?>
                </div><!-- .comment-meta -->
                
                <div class="comment-content">
                    <?php comment_text(); ?>
                </div><!-- .comment-content -->
                
                <div class="comment-footer">
                    <?php
                    if ('1' == $comment->comment_approved || $show_pending_links) {
                        comment_reply_link(
                            array_merge(
                                $args,
                                array(
                                    'add_below' => 'div-comment',
                                    'depth'     => $depth,
                                    'max_depth' => $args['max_depth'],
                                    'before'    => '<div class="reply">',
                                    'after'     => '</div>',
                                )
                            )
                        );
                    }
                    ?>
                </div><!-- .comment-footer -->
            </article><!-- .comment-body -->
        <?php
    }
}