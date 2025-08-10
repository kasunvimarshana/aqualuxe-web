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

// Exit if accessed directly
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

<div id="comments" class="comments-area mt-12 bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none p-6 md:p-8">

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

        <ol class="comment-list divide-y divide-gray-200 dark:divide-gray-700">
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
            <p class="no-comments mt-6 p-4 bg-gray-100 dark:bg-gray-800 rounded-md text-center"><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    // Custom comment form
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');
    $consent = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';

    $fields = array(
        'author' => '<div class="comment-form-author mb-4">
                        <label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . __('Name', 'aqualuxe') . ($req ? ' <span class="required text-red-600">*</span>' : '') . '</label>
                        <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $aria_req . ' class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary" />
                    </div>',
        'email'  => '<div class="comment-form-email mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . __('Email', 'aqualuxe') . ($req ? ' <span class="required text-red-600">*</span>' : '') . '</label>
                        <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary" />
                    </div>',
        'url'    => '<div class="comment-form-url mb-4">
                        <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . __('Website', 'aqualuxe') . '</label>
                        <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary" />
                    </div>',
        'cookies' => '<div class="comment-form-cookies-consent flex items-start mb-4">
                        <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' class="mt-1 mr-2 rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary" />
                        <label for="wp-comment-cookies-consent" class="text-sm text-gray-700 dark:text-gray-300">' . __('Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe') . '</label>
                    </div>',
    );

    $comments_args = array(
        'fields' => $fields,
        'comment_field' => '<div class="comment-form-comment mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . _x('Comment', 'noun', 'aqualuxe') . ' <span class="required text-red-600">*</span></label>
                                <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary"></textarea>
                            </div>',
        'class_form' => 'comment-form space-y-4',
        'class_submit' => 'btn btn-primary',
        'title_reply' => esc_html__('Leave a Comment', 'aqualuxe'),
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-xl font-bold mt-8 mb-4">',
        'title_reply_after' => '</h3>',
        'comment_notes_before' => '<p class="comment-notes text-sm text-gray-600 dark:text-gray-400 mb-4">' . sprintf(
            /* translators: 1: Your email address will not be published. 2: Opening and closing abbr tags. */
            __('Your email address will not be published. %1$sRequired fields are marked%2$s.', 'aqualuxe'),
            '<span class="required text-red-600">*</span>',
            ''
        ) . '</p>',
        'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
    );

    comment_form($comments_args);
    ?>

</div><!-- #comments -->