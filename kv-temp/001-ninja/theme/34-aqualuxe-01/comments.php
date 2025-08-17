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

<div id="comments" class="comments-area mt-12 pt-8 border-t border-gray-200">

    <?php
    // You can start editing here -- including this comment!
    if (have_comments()) :
    ?>
        <h2 class="comments-title text-2xl font-bold mb-6">
            <?php
            $aqualuxe_comment_count = get_comments_number();
            if ('1' === $aqualuxe_comment_count) {
                printf(
                    /* translators: 1: title. */
                    esc_html__('One comment on &ldquo;%1$s&rdquo;', 'aqualuxe'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count number, 2: title. */
                    esc_html(_nx('%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $aqualuxe_comment_count, 'comments title', 'aqualuxe')),
                    number_format_i18n($aqualuxe_comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2><!-- .comments-title -->

        <?php the_comments_navigation(); ?>

        <ol class="comment-list space-y-6">
            <?php
            wp_list_comments(
                array(
                    'style'      => 'ol',
                    'short_ping' => true,
                    'avatar_size' => 60,
                    'callback' => 'aqualuxe_comment_callback',
                )
            );
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open()) :
        ?>
            <p class="no-comments mt-6 p-4 bg-gray-100 rounded"><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></p>
        <?php
        endif;

    endif; // Check for have_comments().

    comment_form(
        array(
            'class_form' => 'comment-form mt-8',
            'title_reply' => '<span class="text-2xl font-bold">' . __('Leave a Comment', 'aqualuxe') . '</span>',
            'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title mb-4">',
            'title_reply_after' => '</h3>',
            'comment_notes_before' => '<p class="comment-notes mb-4">' . __('Your email address will not be published. Required fields are marked *', 'aqualuxe') . '</p>',
            'comment_field' => '<div class="comment-form-comment mb-4"><label for="comment" class="block mb-2">' . __('Comment', 'aqualuxe') . ' <span class="required">*</span></label><textarea id="comment" name="comment" class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" rows="5" required></textarea></div>',
            'submit_button' => '<input name="%1$s" type="submit" id="%2$s" class="%3$s bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors" value="%4$s" />',
        )
    );
    ?>

</div><!-- #comments -->