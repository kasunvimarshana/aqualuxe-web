<?php
/**
 * Comments Template
 *
 * @package AquaLuxe
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    
    <?php if (have_comments()) : ?>
        <h2 class="comments-title text-2xl lg:text-3xl font-bold font-secondary text-gray-900 mb-8">
            <?php
            $comment_count = get_comments_number();
            if ($comment_count === 1) {
                esc_html_e('1 Comment', 'aqualuxe');
            } else {
                printf(
                    /* translators: 1: comment count number. */
                    esc_html(_nx('%1$s Comment', '%1$s Comments', $comment_count, 'comments title', 'aqualuxe')),
                    number_format_i18n($comment_count)
                );
            }
            ?>
        </h2>

        <div class="comment-navigation mb-8">
            <?php the_comments_navigation(); ?>
        </div>

        <ol class="comment-list space-y-8">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'callback' => 'aqualuxe_comment_callback',
            ));
            ?>
        </ol>

        <div class="comment-navigation mt-8">
            <?php the_comments_navigation(); ?>
        </div>

        <?php if (!comments_open()) : ?>
            <p class="no-comments text-gray-600 p-6 bg-gray-50 rounded-lg text-center">
                <?php esc_html_e('Comments are closed.', 'aqualuxe'); ?>
            </p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title text-2xl lg:text-3xl font-bold font-secondary text-gray-900 mb-8">',
        'title_reply_after' => '</h2>',
        'class_form' => 'comment-form bg-white p-8 lg:p-12 rounded-2xl shadow-lg',
        'comment_field' => '<div class="comment-form-comment mb-6">
            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">' . esc_html__('Comment', 'aqualuxe') . ' <span class="required text-red-500">*</span></label>
            <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors resize-vertical" placeholder="' . esc_attr__('Write your comment here...', 'aqualuxe') . '"></textarea>
        </div>',
        'fields' => array(
            'author' => '<div class="comment-form-author mb-6">
                <label for="author" class="block text-sm font-medium text-gray-700 mb-2">' . esc_html__('Name', 'aqualuxe') . (get_option('require_name_email') ? ' <span class="required text-red-500">*</span>' : '') . '</label>
                <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . (get_option('require_name_email') ? ' required' : '') . ' class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors" />
            </div>',
            'email' => '<div class="comment-form-email mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">' . esc_html__('Email', 'aqualuxe') . (get_option('require_name_email') ? ' <span class="required text-red-500">*</span>' : '') . '</label>
                <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . (get_option('require_name_email') ? ' required' : '') . ' class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors" />
                <p id="email-notes" class="text-xs text-gray-500 mt-1">' . esc_html__('Your email address will not be published.', 'aqualuxe') . '</p>
            </div>',
            'url' => '<div class="comment-form-url mb-6">
                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">' . esc_html__('Website', 'aqualuxe') . '</label>
                <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors" />
            </div>',
            'cookies' => '<div class="comment-form-cookies-consent mb-6">
                <label class="flex items-start space-x-3">
                    <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" class="mt-1 w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500" />
                    <span class="text-sm text-gray-600">' . esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe') . '</span>
                </label>
            </div>',
        ),
        'submit_button' => '<input name="%1$s" type="submit" id="%2$s" class="%3$s btn btn-primary bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-full transition-colors focus:outline-none focus:ring-4 focus:ring-primary-300" value="%4$s" />',
        'submit_field' => '<div class="form-submit text-center">%1$s %2$s</div>',
    ));
    ?>

</div>

<?php
/**
 * Custom comment callback function
 */
function aqualuxe_comment_callback($comment, $args, $depth) {
    if ('div' === $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag; ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
    
    <?php if ('div' !== $args['style']) : ?>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-body bg-white p-6 lg:p-8 rounded-2xl shadow-soft border border-gray-100">
    <?php endif; ?>
    
    <div class="comment-content">
        <div class="comment-header flex items-start space-x-4 mb-4">
            <div class="comment-avatar flex-shrink-0">
                <?php echo get_avatar($comment, 60, '', '', array('class' => 'w-15 h-15 rounded-full border-2 border-gray-200')); ?>
            </div>
            
            <div class="comment-meta flex-1">
                <div class="comment-author-name font-semibold text-gray-900 mb-1">
                    <?php printf(__('%s', 'aqualuxe'), get_comment_author_link()); ?>
                </div>
                
                <div class="comment-metadata text-sm text-gray-500 mb-2">
                    <time datetime="<?php comment_time('c'); ?>">
                        <?php
                        /* translators: 1: date, 2: time */
                        printf(__('%1$s at %2$s', 'aqualuxe'), get_comment_date(), get_comment_time());
                        ?>
                    </time>
                    <?php edit_comment_link(__('Edit', 'aqualuxe'), ' <span class="edit-link mx-2">•</span> ', ''); ?>
                </div>
                
                <?php if ('0' == $comment->comment_approved) : ?>
                    <div class="comment-awaiting-moderation bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 rounded-lg text-sm mb-4">
                        <?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="comment-text prose prose-sm max-w-none text-gray-700 mb-4">
            <?php comment_text(); ?>
        </div>
        
        <div class="comment-actions flex items-center space-x-4">
            <?php
            comment_reply_link(array_merge($args, array(
                'add_below' => $add_below,
                'depth' => $depth,
                'max_depth' => $args['max_depth'],
                'class' => 'reply-link text-primary-600 hover:text-primary-700 text-sm font-medium transition-colors',
                'reply_text' => __('Reply', 'aqualuxe'),
            )));
            ?>
            
            <!-- Comment voting/rating (if needed) -->
            <div class="comment-voting flex items-center space-x-2 text-sm text-gray-500">
                <button class="vote-up hover:text-green-600 transition-colors" data-comment-id="<?php comment_ID(); ?>">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L10 4.414 4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <span class="vote-count">0</span>
                <button class="vote-down hover:text-red-600 transition-colors" data-comment-id="<?php comment_ID(); ?>">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L10 15.586l5.293-5.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <?php if ('div' !== $args['style']) : ?>
        </div>
    <?php endif; ?>
    <?php
}
?>
