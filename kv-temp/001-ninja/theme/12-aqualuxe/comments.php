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
                    esc_html__('One Comment on &ldquo;%1$s&rdquo;', 'aqualuxe'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count number, 2: title. */
                    esc_html(_nx('%1$s Comment on &ldquo;%2$s&rdquo;', '%1$s Comments on &ldquo;%2$s&rdquo;', $aqualuxe_comment_count, 'comments title', 'aqualuxe')),
                    number_format_i18n($aqualuxe_comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2><!-- .comments-title -->

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
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
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></p>
        <?php
        endif;

    endif; // Check for have_comments().

    // Custom comment form
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $html_req = ($req ? " required='required'" : '');
    $consent = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';

    $fields = array(
        'author' => '<div class="comment-form-author form-group">' .
            '<label for="author">' . esc_html__('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>' .
            '<input id="author" class="form-control" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $html_req . ' />' .
            '</div>',
        'email'  => '<div class="comment-form-email form-group">' .
            '<label for="email">' . esc_html__('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>' .
            '<input id="email" class="form-control" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $html_req . ' />' .
            '</div>',
        'url'    => '<div class="comment-form-url form-group">' .
            '<label for="url">' . esc_html__('Website', 'aqualuxe') . '</label>' .
            '<input id="url" class="form-control" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" />' .
            '</div>',
        'cookies' => '<div class="comment-form-cookies-consent form-group">' .
            '<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
            '<label for="wp-comment-cookies-consent">' . esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe') . '</label>' .
            '</div>',
    );

    $comment_field = '<div class="comment-form-comment form-group">' .
        '<label for="comment">' . esc_html__('Comment', 'aqualuxe') . ' <span class="required">*</span></label>' .
        '<textarea id="comment" class="form-control" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea>' .
        '</div>';

    $comment_form_args = array(
        'fields'               => $fields,
        'comment_field'        => $comment_field,
        'class_form'           => 'comment-form',
        'class_submit'         => 'btn btn-primary',
        'title_reply'          => esc_html__('Leave a Comment', 'aqualuxe'),
        'title_reply_to'       => esc_html__('Leave a Reply to %s', 'aqualuxe'),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'cancel_reply_before'  => '<span class="cancel-reply">',
        'cancel_reply_after'   => '</span>',
        'cancel_reply_link'    => esc_html__('Cancel Reply', 'aqualuxe'),
        'label_submit'         => esc_html__('Post Comment', 'aqualuxe'),
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
        'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html__('Your email address will not be published.', 'aqualuxe') . '</span>' . ($req ? ' <span class="required-field-message">' . esc_html__('Required fields are marked', 'aqualuxe') . ' <span class="required">*</span></span>' : '') . '</p>',
    );

    comment_form($comment_form_args);
    ?>

</div><!-- #comments -->