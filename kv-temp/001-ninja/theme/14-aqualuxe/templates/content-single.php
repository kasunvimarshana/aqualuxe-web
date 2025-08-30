<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail('full', array('class' => 'w-full h-auto object-cover')); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content p-8">
        <header class="entry-header mb-6">
            <h1 class="entry-title text-3xl font-bold text-gray-900 dark:text-white mb-4">
                <?php the_title(); ?>
            </h1>

            <div class="entry-meta flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400 mb-6">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span><?php echo get_the_date(); ?></span>
                </div>

                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span><?php the_author_posts_link(); ?></span>
                </div>

                <?php if (has_category()) : ?>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span><?php the_category(', '); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (has_tag()) : ?>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        <span><?php the_tags('', ', '); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </header><!-- .entry-header -->

        <div class="prose dark:prose-invert max-w-none">
            <?php
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                )
            );

            wp_link_pages(
                array(
                    'before' => '<div class="page-links mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">' . esc_html__('Pages:', 'aqualuxe'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div>

        <?php if (function_exists('aqualuxe_post_sharing')) : ?>
            <div class="post-sharing mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white"><?php esc_html_e('Share This Post', 'aqualuxe'); ?></h3>
                <?php aqualuxe_post_sharing(); ?>
            </div>
        <?php endif; ?>

        <?php if (function_exists('aqualuxe_related_posts')) : ?>
            <div class="related-posts mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
                <?php aqualuxe_related_posts(); ?>
            </div>
        <?php endif; ?>

        <div class="post-navigation mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap justify-between">
                <div class="prev-post mb-4 md:mb-0">
                    <?php previous_post_link('%link', '<span class="block text-sm text-gray-600 dark:text-gray-400 mb-1">' . __('Previous Post', 'aqualuxe') . '</span><span class="font-medium text-primary dark:text-primary-dark hover:underline">%title</span>'); ?>
                </div>
                <div class="next-post text-right">
                    <?php next_post_link('%link', '<span class="block text-sm text-gray-600 dark:text-gray-400 mb-1">' . __('Next Post', 'aqualuxe') . '</span><span class="font-medium text-primary dark:text-primary-dark hover:underline">%title</span>'); ?>
                </div>
            </div>
        </div>

        <?php if (function_exists('aqualuxe_author_bio')) : ?>
            <div class="author-bio mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <?php aqualuxe_author_bio(); ?>
            </div>
        <?php endif; ?>
    </div><!-- .entry-content -->

    <?php if (get_edit_post_link()) : ?>
        <footer class="entry-footer px-8 pb-6 text-sm text-gray-600 dark:text-gray-400">
            <?php
            edit_post_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                ),
                '<span class="edit-link">',
                '</span>'
            );
            ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->