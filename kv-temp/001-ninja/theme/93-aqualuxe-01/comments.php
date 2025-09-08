<?php
/**
 * Comments Template
 *
 * Displays comments list and comment form with modern styling,
 * accessibility features, and threaded comment support.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
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

$comment_count = get_comments_number();
?>

<section id="comments" class="comments-area max-w-4xl mx-auto px-4 py-8" aria-label="<?php esc_attr_e('Comments section', 'aqualuxe'); ?>">
    
    <?php if (have_comments()) : ?>
        
        <!-- Comments Header -->
        <header class="comments-header mb-8">
            <h2 class="comments-title text-2xl font-bold text-gray-900 dark:text-white mb-4">
                <?php
                if ($comment_count === 1) {
                    printf(
                        esc_html__('One comment on "%s"', 'aqualuxe'),
                        '<span class="post-title">' . wp_kses_post(get_the_title()) . '</span>'
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
                        '<span class="post-title">' . wp_kses_post(get_the_title()) . '</span>'
                    );
                }
                ?>
            </h2>
            
            <!-- Comments Navigation (Top) -->
            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                <nav class="comment-navigation comment-navigation-top mb-6" aria-label="<?php esc_attr_e('Comments navigation', 'aqualuxe'); ?>">
                    <div class="flex justify-between items-center">
                        <div class="nav-previous">
                            <?php previous_comments_link(
                                sprintf(
                                    '<span class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-blue-400 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors duration-200">%s %s</span>',
                                    '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                                    esc_html__('Older Comments', 'aqualuxe')
                                )
                            ); ?>
                        </div>
                        <div class="nav-next">
                            <?php next_comments_link(
                                sprintf(
                                    '<span class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-blue-400 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors duration-200">%s %s</span>',
                                    esc_html__('Newer Comments', 'aqualuxe'),
                                    '<svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>'
                                )
                            ); ?>
                        </div>
                    </div>
                </nav>
            <?php endif; ?>
        </header>
        
        <!-- Comments List -->
        <ol class="comment-list space-y-6 mb-8">
            <?php
            wp_list_comments([
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 60,
                'callback' => 'aqualuxe_comment_callback',
                'end-callback' => 'aqualuxe_comment_end_callback',
                'format' => 'html5'
            ]);
            ?>
        </ol>
        
        <!-- Comments Navigation (Bottom) -->
        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comment-navigation comment-navigation-bottom mb-8" aria-label="<?php esc_attr_e('Comments navigation', 'aqualuxe'); ?>">
                <div class="flex justify-between items-center">
                    <div class="nav-previous">
                        <?php previous_comments_link(
                            sprintf(
                                '<span class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-blue-400 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors duration-200">%s %s</span>',
                                '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                                esc_html__('Older Comments', 'aqualuxe')
                            )
                        ); ?>
                    </div>
                    <div class="nav-next">
                        <?php next_comments_link(
                            sprintf(
                                '<span class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-blue-400 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors duration-200">%s %s</span>',
                                esc_html__('Newer Comments', 'aqualuxe'),
                                '<svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>'
                            )
                        ); ?>
                    </div>
                </div>
            </nav>
        <?php endif; ?>
        
    <?php endif; ?>
    
    <!-- Comments Closed Message -->
    <?php if (!comments_open() && $comment_count && post_type_supports(get_post_type(), 'comments')) : ?>
        <div class="no-comments bg-yellow-50 border border-yellow-200 rounded-md p-4 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-200">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></span>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Comment Form -->
    <?php
    if (comments_open()) {
        // Custom comment form arguments
        $comment_form_args = [
            'title_reply' => esc_html__('Leave a Comment', 'aqualuxe'),
            'title_reply_to' => esc_html__('Leave a Reply to %s', 'aqualuxe'),
            'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-2xl font-bold text-gray-900 dark:text-white mb-6">',
            'title_reply_after' => '</h3>',
            'cancel_reply_before' => ' <small>',
            'cancel_reply_after' => '</small>',
            'cancel_reply_link' => '<span class="inline-flex items-center px-3 py-1 text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors duration-200"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>' . esc_html__('Cancel reply', 'aqualuxe') . '</span>',
            'label_submit' => esc_html__('Post Comment', 'aqualuxe'),
            'submit_button' => '<input name="%1$s" type="submit" id="%2$s" class="%3$s inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" value="%4$s" />',
            'submit_field' => '<p class="form-submit flex items-center justify-between mt-6">%1$s %2$s</p>',
            'format' => 'html5',
            'comment_field' => sprintf(
                '<div class="comment-form-comment mb-6"><label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">%s <span class="required text-red-500" aria-label="%s">*</span></label><textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required="required" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 transition-colors duration-200" placeholder="%s" aria-describedby="comment-notes"></textarea></div>',
                esc_html__('Comment', 'aqualuxe'),
                esc_attr__('Required field', 'aqualuxe'),
                esc_attr__('Share your thoughts...', 'aqualuxe')
            ),
            'fields' => [
                'author' => sprintf(
                    '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"><div class="comment-form-author"><label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">%s <span class="required text-red-500" aria-label="%s">*</span></label><input id="author" name="author" type="text" value="%s" size="30" maxlength="245" autocomplete="name" required="required" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 transition-colors duration-200" placeholder="%s" /></div>',
                    esc_html__('Name', 'aqualuxe'),
                    esc_attr__('Required field', 'aqualuxe'),
                    esc_attr($commenter['comment_author']),
                    esc_attr__('Your name', 'aqualuxe')
                ),
                'email' => sprintf(
                    '<div class="comment-form-email"><label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">%s <span class="required text-red-500" aria-label="%s">*</span></label><input id="email" name="email" type="email" value="%s" size="30" maxlength="100" aria-describedby="email-notes" autocomplete="email" required="required" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 transition-colors duration-200" placeholder="%s" /></div></div>',
                    esc_html__('Email', 'aqualuxe'),
                    esc_attr__('Required field', 'aqualuxe'),
                    esc_attr($commenter['comment_author_email']),
                    esc_attr__('your.email@example.com', 'aqualuxe')
                ),
                'url' => sprintf(
                    '<div class="comment-form-url mb-6"><label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">%s</label><input id="url" name="url" type="url" value="%s" size="30" maxlength="200" autocomplete="url" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500 transition-colors duration-200" placeholder="%s" /></div>',
                    esc_html__('Website', 'aqualuxe'),
                    esc_attr($commenter['comment_author_url']),
                    esc_attr__('https://yourwebsite.com', 'aqualuxe')
                ),
                'cookies' => sprintf(
                    '<div class="comment-form-cookies-consent mb-6"><div class="flex items-start"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"%s class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600" /><label for="wp-comment-cookies-consent" class="ml-3 text-sm text-gray-700 dark:text-gray-300">%s</label></div></div>',
                    empty($commenter['comment_author_email']) ? '' : ' checked="checked"',
                    esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe')
                ),
            ],
            'comment_notes_before' => sprintf(
                '<div class="comment-notes mb-6"><p id="comment-notes" class="text-sm text-gray-600 dark:text-gray-400">%s</p><p id="email-notes" class="text-sm text-gray-600 dark:text-gray-400">%s</p></div>',
                esc_html__('Your email address will not be published.', 'aqualuxe'),
                esc_html__('Required fields are marked with an asterisk (*).', 'aqualuxe')
            ),
            'comment_notes_after' => '',
            'class_container' => 'comment-respond bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700',
            'class_form' => 'comment-form',
        ];
        
        comment_form($comment_form_args);
    }
    ?>
    
</section>

<?php
/**
 * Custom comment callback function
 */
if (!function_exists('aqualuxe_comment_callback')) {
    function aqualuxe_comment_callback($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        
        switch ($comment->comment_type) {
            case 'pingback':
            case 'trackback':
                ?>
                <li <?php comment_class('pingback bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700'); ?> id="comment-<?php comment_ID(); ?>">
                    <div class="comment-body">
                        <span class="pingback-title text-sm font-medium text-gray-700 dark:text-gray-300">
                            <?php esc_html_e('Pingback:', 'aqualuxe'); ?>
                        </span>
                        <?php comment_author_link(); ?>
                        <?php edit_comment_link(
                            sprintf(
                                '<span class="edit-link ml-2 text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">%s</span>',
                                esc_html__('Edit', 'aqualuxe')
                            )
                        ); ?>
                    </div>
                <?php
                break;
                
            default:
                $comment_classes = [
                    'comment',
                    'bg-white',
                    'dark:bg-gray-800',
                    'rounded-lg',
                    'shadow-sm',
                    'border',
                    'border-gray-200',
                    'dark:border-gray-700',
                    'p-6'
                ];
                
                if ($depth > 1) {
                    $comment_classes[] = 'ml-8';
                    $comment_classes[] = 'mt-4';
                }
                ?>
                <li <?php comment_class(implode(' ', $comment_classes)); ?> id="comment-<?php comment_ID(); ?>">
                    <article class="comment-body" itemscope itemtype="https://schema.org/Comment">
                        
                        <header class="comment-header flex items-start space-x-4 mb-4">
                            <div class="comment-avatar flex-shrink-0">
                                <?php echo get_avatar($comment, $args['avatar_size'], '', '', [
                                    'class' => 'rounded-full'
                                ]); ?>
                            </div>
                            
                            <div class="comment-meta flex-grow">
                                <div class="comment-author vcard flex items-center mb-1" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                    <span class="fn font-semibold text-gray-900 dark:text-white" itemprop="name">
                                        <?php comment_author_link(); ?>
                                    </span>
                                    
                                    <?php if (get_comment_meta($comment->comment_ID, 'comment_author_verified', true)) : ?>
                                        <span class="verified-badge ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200" title="<?php esc_attr_e('Verified user', 'aqualuxe'); ?>">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <?php esc_html_e('Verified', 'aqualuxe'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="comment-metadata text-sm text-gray-600 dark:text-gray-400">
                                    <time datetime="<?php comment_time('c'); ?>" itemprop="datePublished">
                                        <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>" class="comment-permalink hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                            <?php
                                            printf(
                                                esc_html__('%1$s at %2$s', 'aqualuxe'),
                                                get_comment_date(),
                                                get_comment_time()
                                            );
                                            ?>
                                        </a>
                                    </time>
                                    
                                    <?php edit_comment_link(
                                        sprintf(
                                            '<span class="edit-link ml-3 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">%s</span>',
                                            esc_html__('Edit', 'aqualuxe')
                                        )
                                    ); ?>
                                </div>
                            </div>
                        </header>
                        
                        <div class="comment-content prose prose-sm max-w-none dark:prose-invert" itemprop="text">
                            <?php if ('0' == $comment->comment_approved) : ?>
                                <div class="comment-awaiting-moderation bg-yellow-50 border border-yellow-200 rounded-md p-3 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-200 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm"><?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php comment_text(); ?>
                        </div>
                        
                        <footer class="comment-footer mt-4">
                            <?php
                            comment_reply_link(array_merge($args, [
                                'add_below' => 'comment',
                                'depth' => $depth,
                                'max_depth' => $args['max_depth'],
                                'before' => '<div class="reply">',
                                'after' => '</div>',
                                'reply_text' => sprintf(
                                    '<span class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">%s %s</span>',
                                    '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>',
                                    esc_html__('Reply', 'aqualuxe')
                                )
                            ]));
                            ?>
                        </footer>
                        
                    </article>
                <?php
                break;
        }
    }
}

/**
 * Custom comment end callback function
 */
if (!function_exists('aqualuxe_comment_end_callback')) {
    function aqualuxe_comment_end_callback($comment, $args, $depth) {
        echo '</li>';
    }
}
