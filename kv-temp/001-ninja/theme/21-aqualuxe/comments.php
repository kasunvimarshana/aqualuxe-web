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

<div id="comments" class="comments-area mt-12 pt-8 border-t border-gray-200 dark:border-dark-600">

    <?php
    // You can start editing here -- including this comment!
    if (have_comments()) :
        ?>
        <h2 class="comments-title text-2xl font-bold text-dark-800 dark:text-white mb-6">
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
            <p class="no-comments mt-6 p-4 bg-gray-100 dark:bg-dark-600 rounded-lg text-center"><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    // Custom comment form
    $commenter = wp_get_current_commenter();
    $consent = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
    
    $fields = array(
        'author' => sprintf(
            '<div class="comment-form-author mb-4"><label for="author" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1">%s%s</label><input id="author" name="author" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white" value="%s" size="30" maxlength="245" %s /></div>',
            __('Name', 'aqualuxe'),
            ' <span class="required text-red-600">*</span>',
            esc_attr($commenter['comment_author']),
            'required'
        ),
        'email' => sprintf(
            '<div class="comment-form-email mb-4"><label for="email" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1">%s%s</label><input id="email" name="email" type="email" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white" value="%s" size="30" maxlength="100" aria-describedby="email-notes" %s /></div>',
            __('Email', 'aqualuxe'),
            ' <span class="required text-red-600">*</span>',
            esc_attr($commenter['comment_author_email']),
            'required'
        ),
        'url' => sprintf(
            '<div class="comment-form-url mb-4"><label for="url" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1">%s</label><input id="url" name="url" type="url" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white" value="%s" size="30" maxlength="200" /></div>',
            __('Website', 'aqualuxe'),
            esc_attr($commenter['comment_author_url'])
        ),
        'cookies' => sprintf(
            '<div class="comment-form-cookies-consent flex items-center mb-4"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" class="mr-2" value="yes"%s /><label for="wp-comment-cookies-consent" class="text-sm text-dark-600 dark:text-dark-300">%s</label></div>',
            $consent,
            __('Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe')
        ),
    );

    $comments_args = array(
        'fields' => $fields,
        'comment_field' => sprintf(
            '<div class="comment-form-comment mb-4"><label for="comment" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1">%s%s</label><textarea id="comment" name="comment" class="w-full px-4 py-2 border border-gray-300 dark:border-dark-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white" rows="6" maxlength="65525" required></textarea></div>',
            __('Comment', 'aqualuxe'),
            ' <span class="required text-red-600">*</span>'
        ),
        'class_form' => 'comment-form space-y-4',
        'class_submit' => 'btn btn-primary',
        'title_reply' => __('Leave a Comment', 'aqualuxe'),
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-xl font-bold text-dark-800 dark:text-white mb-4">',
        'title_reply_after' => '</h3>',
        'comment_notes_before' => sprintf(
            '<p class="comment-notes mb-4 text-sm text-dark-600 dark:text-dark-300"><span id="email-notes">%s</span> %s</p>',
            __('Your email address will not be published.', 'aqualuxe'),
            __('Required fields are marked <span class="required text-red-600">*</span>', 'aqualuxe')
        ),
        'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
    );

    comment_form($comments_args);
    ?>

</div><!-- #comments -->