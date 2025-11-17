<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!function_exists('aqualuxe_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function aqualuxe_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        echo '<span class="posted-on">';
        printf(
            /* translators: %s: post date. */
            esc_html_x('Posted on %s', 'post date', 'aqualuxe'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );
        echo '</span>';
    }
endif;

if (!function_exists('aqualuxe_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function aqualuxe_posted_by() {
        echo '<span class="byline"> ' . 
            sprintf(
                /* translators: %s: post author. */
                esc_html_x('by %s', 'post author', 'aqualuxe'),
                '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
            ) . 
        '</span>';
    }
endif;

if (!function_exists('aqualuxe_entry_footer')) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function aqualuxe_entry_footer() {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'aqualuxe') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html__(', ', 'aqualuxe'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'aqualuxe') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                        [
                            'span' => [
                                'class' => [],
                            ],
                        ]
                    ),
                    wp_kses_post(get_the_title())
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                    [
                        'span' => [
                            'class' => [],
                        ],
                    ]
                ),
                wp_kses_post(get_the_title())
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

if (!function_exists('aqualuxe_post_thumbnail')) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function aqualuxe_post_thumbnail() {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>

            <div class="post-thumbnail mb-6">
                <?php the_post_thumbnail('full', ['class' => 'rounded-lg w-full h-auto']); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail block mb-4" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    [
                        'class' => 'rounded-lg w-full h-auto',
                        'alt' => the_title_attribute([
                            'echo' => false,
                        ]),
                    ]
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('aqualuxe_comment')) :
    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @param object $comment Comment to display.
     * @param array  $args    Arguments passed to wp_list_comments().
     * @param int    $depth   Depth of comment.
     */
    function aqualuxe_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;

        if ('pingback' === $comment->comment_type || 'trackback' === $comment->comment_type) :
            ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class('bg-gray-50 dark:bg-gray-800 p-4 mb-4 rounded-lg'); ?>>
                <div class="comment-body">
                    <?php esc_html_e('Pingback:', 'aqualuxe'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), '<span class="edit-link">', '</span>'); ?>
                </div>
            </li>

        <?php else : ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class('bg-white dark:bg-gray-800 p-6 mb-6 rounded-lg shadow-sm'); ?>>
                <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                    <footer class="comment-meta mb-4">
                        <div class="comment-author vcard flex items-center">
                            <?php
                            if (0 !== $args['avatar_size']) {
                                echo '<div class="mr-4">';
                                echo get_avatar($comment, $args['avatar_size'], '', '', ['class' => 'rounded-full']);
                                echo '</div>';
                            }
                            ?>
                            <div>
                                <?php
                                printf(
                                    '<b class="fn">%s</b>',
                                    get_comment_author_link()
                                );
                                ?>
                                <div class="comment-metadata text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                        <time datetime="<?php comment_time('c'); ?>">
                                            <?php
                                            /* translators: 1: comment date, 2: comment time */
                                            printf(esc_html__('%1$s at %2$s', 'aqualuxe'), get_comment_date('', $comment), get_comment_time());
                                            ?>
                                        </time>
                                    </a>
                                    <?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), '<span class="edit-link ml-2">', '</span>'); ?>
                                </div>
                            </div>
                        </div><!-- .comment-author -->

                        <?php if ('0' === $comment->comment_approved) : ?>
                            <p class="comment-awaiting-moderation mt-2 text-yellow-600 dark:text-yellow-400"><?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?></p>
                        <?php endif; ?>
                    </footer><!-- .comment-meta -->

                    <div class="comment-content prose dark:prose-invert max-w-none">
                        <?php comment_text(); ?>
                    </div><!-- .comment-content -->

                    <?php
                    if ('1' === $comment->comment_approved) {
                        comment_reply_link(
                            array_merge(
                                $args,
                                [
                                    'add_below' => 'div-comment',
                                    'depth'     => $depth,
                                    'max_depth' => $args['max_depth'],
                                    'before'    => '<div class="reply mt-4">',
                                    'after'     => '</div>',
                                    'reply_text' => sprintf(
                                        '<span class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors">%s <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg></span>',
                                        esc_html__('Reply', 'aqualuxe')
                                    ),
                                ]
                            )
                        );
                    }
                    ?>
                </article><!-- .comment-body -->
            </li>

            <?php
        endif;
    }
endif;

if (!function_exists('aqualuxe_comment_form_fields')) :
    /**
     * Customize comment form fields
     *
     * @param array $fields The comment form fields.
     * @return array
     */
    function aqualuxe_comment_form_fields($fields) {
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ($req ? ' aria-required="true"' : '');

        $fields['author'] = '<div class="comment-form-author mb-4">' .
            '<label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__('Name', 'aqualuxe') . ($req ? ' <span class="required text-red-600">*</span>' : '') . '</label> ' .
            '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $aria_req . ' class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" /></div>';

        $fields['email'] = '<div class="comment-form-email mb-4">' .
            '<label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__('Email', 'aqualuxe') . ($req ? ' <span class="required text-red-600">*</span>' : '') . '</label> ' .
            '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" /></div>';

        $fields['url'] = '<div class="comment-form-url mb-4">' .
            '<label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__('Website', 'aqualuxe') . '</label> ' .
            '<input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" /></div>';

        return $fields;
    }
endif;
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

if (!function_exists('aqualuxe_comment_form_defaults')) :
    /**
     * Customize comment form defaults
     *
     * @param array $defaults The comment form defaults.
     * @return array
     */
    function aqualuxe_comment_form_defaults($defaults) {
        $defaults['comment_field'] = '<div class="comment-form-comment mb-4">' .
            '<label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__('Comment', 'aqualuxe') . ' <span class="required text-red-600">*</span></label> ' .
            '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"></textarea></div>';

        $defaults['class_submit'] = 'bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors';
        $defaults['submit_button'] = '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>';

        return $defaults;
    }
endif;
add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

if (!function_exists('aqualuxe_get_related_posts')) :
    /**
     * Get related posts
     *
     * @param int $post_id Post ID.
     * @param int $related_count Number of related posts to return.
     * @return WP_Query
     */
    function aqualuxe_get_related_posts($post_id, $related_count = 3) {
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $related_count,
            'post_status' => 'publish',
            'post__not_in' => [$post_id],
            'orderby' => 'rand',
        ];

        $categories = get_the_category($post_id);
        
        if (!empty($categories)) {
            $category_ids = [];
            
            foreach ($categories as $category) {
                $category_ids[] = $category->term_id;
            }
            
            $args['category__in'] = $category_ids;
        }
        
        return new WP_Query($args);
    }
endif;

if (!function_exists('aqualuxe_related_posts')) :
    /**
     * Display related posts
     *
     * @param int $post_id Post ID.
     * @param int $related_count Number of related posts to display.
     * @return void
     */
    function aqualuxe_related_posts($post_id = null, $related_count = 3) {
        if (null === $post_id) {
            $post_id = get_the_ID();
        }
        
        $related_query = aqualuxe_get_related_posts($post_id, $related_count);
        
        if ($related_query->have_posts()) :
            ?>
            <div class="related-posts mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    while ($related_query->have_posts()) :
                        $related_query->the_post();
                        ?>
                        <article class="related-post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform hover:-translate-y-1 hover:shadow-lg">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
                                </a>
                            <?php endif; ?>
                            
                            <div class="p-4">
                                <h3 class="text-lg font-semibold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <?php echo get_the_date(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors text-sm">
                                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
            <?php
        endif;
    }
endif;

if (!function_exists('aqualuxe_social_share')) :
    /**
     * Display social share buttons
     *
     * @param int $post_id Post ID.
     * @return void
     */
    function aqualuxe_social_share($post_id = null) {
        if (null === $post_id) {
            $post_id = get_the_ID();
        }
        
        $permalink = get_permalink($post_id);
        $title = get_the_title($post_id);
        $excerpt = aqualuxe_get_excerpt($post_id, 140);
        ?>
        <div class="social-share mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Share This Post', 'aqualuxe'); ?></h3>
            
            <div class="flex flex-wrap gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($permalink); ?>" target="_blank" rel="noopener noreferrer" class="social-share-button bg-[#3b5998] hover:bg-[#2d4373] text-white py-2 px-4 rounded-md inline-flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                    </svg>
                    <span><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                </a>
                
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($permalink); ?>&text=<?php echo urlencode($title); ?>" target="_blank" rel="noopener noreferrer" class="social-share-button bg-[#1da1f2] hover:bg-[#0c85d0] text-white py-2 px-4 rounded-md inline-flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                    </svg>
                    <span><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                </a>
                
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($permalink); ?>&title=<?php echo urlencode($title); ?>&summary=<?php echo urlencode($excerpt); ?>" target="_blank" rel="noopener noreferrer" class="social-share-button bg-[#0077b5] hover:bg-[#005582] text-white py-2 px-4 rounded-md inline-flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                    </svg>
                    <span><?php esc_html_e('LinkedIn', 'aqualuxe'); ?></span>
                </a>
                
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode($permalink); ?>&description=<?php echo urlencode($title); ?>&media=<?php echo urlencode(get_the_post_thumbnail_url($post_id, 'full')); ?>" target="_blank" rel="noopener noreferrer" class="social-share-button bg-[#bd081c] hover:bg-[#8c0615] text-white py-2 px-4 rounded-md inline-flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
                    </svg>
                    <span><?php esc_html_e('Pinterest', 'aqualuxe'); ?></span>
                </a>
                
                <a href="mailto:?subject=<?php echo urlencode($title); ?>&body=<?php echo urlencode($excerpt . "\n\n" . $permalink); ?>" class="social-share-button bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-md inline-flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span><?php esc_html_e('Email', 'aqualuxe'); ?></span>
                </a>
            </div>
        </div>
        <?php
    }
endif;