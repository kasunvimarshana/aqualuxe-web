<?php
/**
 * The template for displaying comments
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    
    <?php if (have_comments()) : ?>
        <h2 class="comments-title text-2xl font-heading font-semibold mb-6">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf(
                    esc_html__('One comment on "%1$s"', 'aqualuxe'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    esc_html(_nx(
                        '%1$s comment on "%2$s"',
                        '%1$s comments on "%2$s"',
                        $comment_count,
                        'comments title',
                        'aqualuxe'
                    )),
                    number_format_i18n($comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2>
        
        <?php the_comments_navigation(); ?>
        
        <ol class="comment-list space-y-6 mb-8">
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
        
        if (!comments_open()) :
            ?>
            <p class="no-comments text-gray-600 italic">
                <?php esc_html_e('Comments are closed.', 'aqualuxe'); ?>
            </p>
            <?php
        endif;
        
    endif; // Check for have_comments().
    
    // Comment form
    comment_form(array(
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-xl font-heading font-semibold mb-6">',
        'title_reply_after'  => '</h3>',
        'class_form'         => 'comment-form bg-gray-50 dark:bg-gray-800 p-6 rounded-lg',
        'class_submit'       => 'btn-primary',
        'submit_button'      => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
        'fields' => array(
            'author' => '<div class="form-group mb-4">' .
                       '<label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . 
                       esc_html__('Name', 'aqualuxe') . 
                       (get_option('require_name_email') ? ' <span class="required text-coral-600">*</span>' : '') . 
                       '</label>' .
                       '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . 
                       '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-aqua-500 focus:ring-aqua-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"' . 
                       (get_option('require_name_email') ? ' required' : '') . ' />' .
                       '</div>',
            
            'email'  => '<div class="form-group mb-4">' .
                       '<label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . 
                       esc_html__('Email', 'aqualuxe') . 
                       (get_option('require_name_email') ? ' <span class="required text-coral-600">*</span>' : '') . 
                       '</label>' .
                       '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . 
                       '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-aqua-500 focus:ring-aqua-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"' . 
                       (get_option('require_name_email') ? ' required' : '') . ' />' .
                       '</div>',
            
            'url'    => '<div class="form-group mb-4">' .
                       '<label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . 
                       esc_html__('Website', 'aqualuxe') . '</label>' .
                       '<input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . 
                       '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-aqua-500 focus:ring-aqua-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />' .
                       '</div>',
        ),
        'comment_field' => '<div class="form-group mb-6">' .
                          '<label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . 
                          esc_html__('Comment', 'aqualuxe') . ' <span class="required text-coral-600">*</span></label>' .
                          '<textarea id="comment" name="comment" rows="6" required ' .
                          'class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-aqua-500 focus:ring-aqua-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" ' .
                          'placeholder="' . esc_attr__('Enter your comment...', 'aqualuxe') . '"></textarea>' .
                          '</div>',
        'logged_in_as' => '<p class="logged-in-as text-sm text-gray-600 dark:text-gray-400 mb-4">' .
                         sprintf(
                             __('Logged in as <a href="%1$s" class="text-aqua-600 hover:text-aqua-800">%2$s</a>. <a href="%3$s" title="Log out of this account" class="text-coral-600 hover:text-coral-800">Log out?</a>', 'aqualuxe'),
                             get_edit_user_link(),
                             $user_identity,
                             wp_logout_url(apply_filters('the_permalink', get_permalink()))
                         ) . '</p>',
        'comment_notes_before' => '<p class="comment-notes text-sm text-gray-600 dark:text-gray-400 mb-4">' .
                                 esc_html__('Your email address will not be published.', 'aqualuxe') . 
                                 (get_option('require_name_email') ? ' ' . esc_html__('Required fields are marked *', 'aqualuxe') : '') .
                                 '</p>',
        'comment_notes_after' => '',
    ));
    ?>
    
</div>

<?php
/**
 * Custom comment callback function
 */
function aqualuxe_comment_callback($comment, $args, $depth) {
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? 'comment' : 'parent comment'); ?>>
        
        <article class="comment-body bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
            
            <header class="comment-meta flex items-start space-x-4 mb-4">
                <div class="comment-author-avatar flex-shrink-0">
                    <?php echo get_avatar($comment, 60, '', '', array('class' => 'rounded-full')); ?>
                </div>
                
                <div class="comment-metadata flex-1">
                    <div class="comment-author-name">
                        <?php if (get_comment_author_url()) : ?>
                            <a href="<?php echo esc_url(get_comment_author_url()); ?>" class="text-ocean-800 dark:text-gray-200 font-semibold hover:text-aqua-600">
                                <?php comment_author(); ?>
                            </a>
                        <?php else : ?>
                            <span class="text-ocean-800 dark:text-gray-200 font-semibold">
                                <?php comment_author(); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (get_comment_meta(get_comment_ID(), 'verified_purchase', true)) : ?>
                            <span class="verified-purchase inline-flex items-center ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <?php esc_html_e('Verified Purchase', 'aqualuxe'); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="comment-date-time text-sm text-gray-500 dark:text-gray-400">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php
                            printf(
                                esc_html__('%1$s at %2$s', 'aqualuxe'),
                                get_comment_date(),
                                get_comment_time()
                            );
                            ?>
                        </time>
                        
                        <?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), ' <span class="edit-link">', '</span>'); ?>
                    </div>
                </div>
            </header>
            
            <div class="comment-content prose prose-sm max-w-none dark:prose-invert">
                <?php if ('0' == $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation bg-yellow-100 text-yellow-800 px-3 py-2 rounded text-sm">
                        <?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?>
                    </p>
                <?php endif; ?>
                
                <?php comment_text(); ?>
            </div>
            
            <footer class="comment-footer mt-4 flex items-center justify-between">
                <div class="comment-reply">
                    <?php
                    comment_reply_link(array_merge($args, array(
                        'add_below' => 'div-comment',
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'class'     => 'text-aqua-600 hover:text-aqua-800 text-sm font-medium'
                    )));
                    ?>
                </div>
                
                <?php if (function_exists('get_comment_meta')) : ?>
                    <div class="comment-rating">
                        <?php
                        $rating = get_comment_meta(get_comment_ID(), 'rating', true);
                        if ($rating) :
                            ?>
                            <div class="stars flex items-center text-yellow-400">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <svg class="w-4 h-4 <?php echo $i <= $rating ? 'fill-current' : 'text-gray-300'; ?>" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endfor; ?>
                                <span class="ml-2 text-sm text-gray-600"><?php echo esc_html($rating); ?>/5</span>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
            </footer>
            
        </article>
        
    <?php
    // Don't close the <li> tag here - WordPress will handle it
}
?>