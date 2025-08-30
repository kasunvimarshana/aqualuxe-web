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
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 60,
                    'callback'    => 'aqualuxe_comment_callback',
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
        'author' => sprintf(
            '<div class="comment-form-author form-group"><label for="author">%1$s%2$s</label><input id="author" name="author" type="text" class="form-control" value="%3$s" size="30" maxlength="245"%4$s /></div>',
            esc_html__('Name', 'aqualuxe'),
            $req ? ' <span class="required">*</span>' : '',
            esc_attr($commenter['comment_author']),
            $html_req
        ),
        'email'  => sprintf(
            '<div class="comment-form-email form-group"><label for="email">%1$s%2$s</label><input id="email" name="email" type="email" class="form-control" value="%3$s" size="30" maxlength="100" aria-describedby="email-notes"%4$s /></div>',
            esc_html__('Email', 'aqualuxe'),
            $req ? ' <span class="required">*</span>' : '',
            esc_attr($commenter['comment_author_email']),
            $html_req
        ),
        'url'    => sprintf(
            '<div class="comment-form-url form-group"><label for="url">%1$s</label><input id="url" name="url" type="url" class="form-control" value="%2$s" size="30" maxlength="200" /></div>',
            esc_html__('Website', 'aqualuxe'),
            esc_attr($commenter['comment_author_url'])
        ),
        'cookies' => sprintf(
            '<div class="comment-form-cookies-consent form-group"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"%1$s /><label for="wp-comment-cookies-consent">%2$s</label></div>',
            $consent,
            esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe')
        ),
    );

    $comment_field = sprintf(
        '<div class="comment-form-comment form-group"><label for="comment">%1$s <span class="required">*</span></label><textarea id="comment" name="comment" class="form-control" rows="5" maxlength="65525" required="required"></textarea></div>',
        esc_html__('Comment', 'aqualuxe')
    );

    $submit_button = sprintf(
        '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
        'submit',
        'submit',
        'btn btn-primary',
        esc_html__('Post Comment', 'aqualuxe')
    );

    $args = array(
        'fields'               => $fields,
        'comment_field'        => $comment_field,
        'submit_button'        => $submit_button,
        'class_form'           => 'comment-form',
        'class_submit'         => 'form-submit',
        'title_reply'          => esc_html__('Leave a Comment', 'aqualuxe'),
        'title_reply_to'       => esc_html__('Leave a Reply to %s', 'aqualuxe'),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'cancel_reply_before'  => '<span class="cancel-reply">',
        'cancel_reply_after'   => '</span>',
        'cancel_reply_link'    => esc_html__('Cancel Reply', 'aqualuxe'),
        'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html__('Your email address will not be published.', 'aqualuxe') . '</span>' . ($req ? ' ' . esc_html__('Required fields are marked *', 'aqualuxe') : '') . '</p>',
        'comment_notes_after'  => '',
    );

    comment_form($args);
    ?>
</div><!-- #comments -->