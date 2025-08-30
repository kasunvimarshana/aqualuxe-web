<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php
    // You can start editing here -- including this comment!
    if (have_comments()) :
        ?>
        <h2 class="comments-title">
            <?php
            $aqualuxe_comment_count = get_comments_number();
            if ('1' === $aqualuxe_comment_count) {
                printf(
                    /* translators: 1: title. */
                    esc_html__('One thought on &ldquo;%1$s&rdquo;', 'aqualuxe'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count number, 2: title. */
                    esc_html(_nx('%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $aqualuxe_comment_count, 'comments title', 'aqualuxe')),
                    number_format_i18n($aqualuxe_comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2><!-- .comments-title -->

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size' => 60,
            ));
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    comment_form(array(
        'class_form'          => 'comment-form',
        'title_reply'         => esc_html__('Leave a Comment', 'aqualuxe'),
        'title_reply_before'  => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'   => '</h3>',
        'comment_notes_before' => '<p class="comment-notes">' . esc_html__('Your email address will not be published. Required fields are marked *', 'aqualuxe') . '</p>',
        'comment_field'       => '<div class="comment-form-comment form-group"><label for="comment">' . esc_html__('Comment', 'aqualuxe') . ' <span class="required">*</span></label><textarea id="comment" name="comment" class="form-control" rows="5" required></textarea></div>',
        'class_submit'        => 'submit button',
        'submit_button'       => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
        'submit_field'        => '<div class="form-submit">%1$s %2$s</div>',
    ));
    ?>

</div><!-- #comments -->