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

<div id="comments" class="comments-area mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">

    <?php
    // You can start editing here -- including this comment!
    if (have_comments()) :
    ?>
        <h2 class="comments-title text-2xl font-bold text-gray-900 dark:text-white mb-6">
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
                    'callback'   => 'aqualuxe_comment_callback',
                )
            );
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open()) :
        ?>
            <p class="no-comments mt-6 p-4 bg-gray-100 dark:bg-gray-800 rounded-md text-gray-700 dark:text-gray-300"><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></p>
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
            '<div class="comment-form-author mb-4">
                <label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">%s%s</label>
                <input id="author" name="author" type="text" value="%s" size="30" maxlength="245" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark"%s />
            </div>',
            __('Name', 'aqualuxe'),
            ($req ? ' <span class="required text-red-500">*</span>' : ''),
            esc_attr($commenter['comment_author']),
            $html_req
        ),
        'email'  => sprintf(
            '<div class="comment-form-email mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">%s%s</label>
                <input id="email" name="email" type="email" value="%s" size="30" maxlength="100" aria-describedby="email-notes" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark"%s />
            </div>',
            __('Email', 'aqualuxe'),
            ($req ? ' <span class="required text-red-500">*</span>' : ''),
            esc_attr($commenter['comment_author_email']),
            $html_req
        ),
        'url'    => sprintf(
            '<div class="comment-form-url mb-4">
                <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">%s</label>
                <input id="url" name="url" type="url" value="%s" size="30" maxlength="200" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark" />
            </div>',
            __('Website', 'aqualuxe'),
            esc_attr($commenter['comment_author_url'])
        ),
        'cookies' => sprintf(
            '<div class="comment-form-cookies-consent mb-4 flex items-start">
                <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"%s class="mt-1 mr-2" />
                <label for="wp-comment-cookies-consent" class="text-sm text-gray-700 dark:text-gray-300">%s</label>
            </div>',
            $consent,
            __('Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe')
        ),
    );

    $comments_args = array(
        'fields'               => $fields,
        'comment_field'        => sprintf(
            '<div class="comment-form-comment mb-4">
                <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">%s <span class="required text-red-500">*</span></label>
                <textarea id="comment" name="comment" cols="45" rows="8" required="required" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark"></textarea>
            </div>',
            _x('Comment', 'noun', 'aqualuxe')
        ),
        'class_form'           => 'comment-form bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors duration-300',
        'title_reply'          => __('Leave a Comment', 'aqualuxe'),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title text-xl font-bold text-gray-900 dark:text-white mb-4">',
        'title_reply_after'    => '</h3>',
        'logged_in_as'         => sprintf(
            '<p class="logged-in-as mb-4 text-sm text-gray-600 dark:text-gray-400">%s</p>',
            sprintf(
                /* translators: 1: edit user link, 2: accessibility text, 3: user name, 4: logout URL */
                __('Logged in as <a href="%1$s" aria-label="%2$s" class="text-primary dark:text-primary-dark hover:underline">%3$s</a>. <a href="%4$s" class="text-primary dark:text-primary-dark hover:underline">Log out?</a>', 'aqualuxe'),
                get_edit_user_link(),
                /* translators: %s: user name */
                esc_attr(sprintf(__('Logged in as %s. Edit your profile.', 'aqualuxe'), $user_identity)),
                $user_identity,
                wp_logout_url(apply_filters('the_permalink', get_permalink()))
            )
        ),
        'comment_notes_before' => sprintf(
            '<p class="comment-notes mb-4 text-sm text-gray-600 dark:text-gray-400">%s%s</p>',
            sprintf(
                '<span id="email-notes">%s</span> ',
                __('Your email address will not be published.', 'aqualuxe')
            ),
            ($req ? sprintf(' <span class="required-field-message">%s</span>', __('Required fields are marked <span class="required text-red-500">*</span>', 'aqualuxe')) : '')
        ),
        'class_submit'         => 'submit bg-primary hover:bg-primary-dark text-white font-medium py-2 px-6 rounded-md transition-colors duration-300',
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
    );

    comment_form($comments_args);
    ?>

</div><!-- #comments -->