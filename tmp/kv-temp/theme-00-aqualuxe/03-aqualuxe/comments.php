<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf(
                    esc_html__('One thought on &ldquo;%1$s&rdquo;', 'aqualuxe'),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf(
                    esc_html(_nx(
                        '%1$s thought on &ldquo;%2$s&rdquo;',
                        '%1$s thoughts on &ldquo;%2$s&rdquo;',
                        $comment_count,
                        'comments title',
                        'aqualuxe'
                    )),
                    number_format_i18n($comment_count),
                    '<span>' . get_the_title() . '</span>'
                );
            }
            ?>
        </h2>

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ol',
                'short_ping' => true,
                'callback'   => 'aqualuxe_comment_callback',
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    // Comment form
    $comment_form_args = array(
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h3>',
        'title_reply'        => esc_html__('Leave a Reply', 'aqualuxe'),
        'title_reply_to'     => esc_html__('Leave a Reply to %s', 'aqualuxe'),
        'cancel_reply_link'  => esc_html__('Cancel Reply', 'aqualuxe'),
        'label_submit'       => esc_html__('Post Comment', 'aqualuxe'),
        'submit_button'      => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
        'submit_field'       => '<p class="form-submit">%1$s %2$s</p>',
        'format'             => 'xhtml',
        'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . esc_html__('Comment', 'aqualuxe') . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea></p>',
        'must_log_in'        => '<p class="must-log-in">' . sprintf(
            wp_kses(
                __('You must be <a href="%s">logged in</a> to post a comment.', 'aqualuxe'),
                array(
                    'a' => array(
                        'href' => array(),
                    ),
                )
            ),
            wp_login_url(apply_filters('the_permalink', get_permalink(get_the_ID())))
        ) . '</p>',
        'logged_in_as'       => '<p class="logged-in-as">' . sprintf(
            wp_kses(
                __('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'aqualuxe'),
                array(
                    'a' => array(
                        'href'  => array(),
                        'title' => array(),
                    ),
                )
            ),
            get_edit_user_link(),
            $user_identity,
            wp_logout_url(apply_filters('the_permalink', get_permalink(get_the_ID())))
        ) . '</p>',
        'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html__('Your email address will not be published.', 'aqualuxe') . '</span> ' . (get_option('require_name_email') ? '<span class="required-field-message">' . esc_html__('Required fields are marked *', 'aqualuxe') . '</span>' : '') . '</p>',
        'comment_notes_after'  => '',
        'id_form'              => 'commentform',
        'id_submit'            => 'submit',
        'class_form'           => 'comment-form',
        'class_submit'         => 'submit',
        'name_submit'          => 'submit',
    );

    comment_form($comment_form_args);
    ?>

</div><!-- #comments -->