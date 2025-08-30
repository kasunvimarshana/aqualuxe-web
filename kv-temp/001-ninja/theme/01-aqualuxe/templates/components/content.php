<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-12 pb-12 border-b border-gray-200 dark:border-gray-700'); ?>>
    <header class="entry-header mb-6">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title text-3xl md:text-4xl font-bold mb-4">', '</h1>');
        else :
            the_title('<h2 class="entry-title text-2xl md:text-3xl font-bold mb-4"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400">', '</a></h2>');
        endif;

        if ('post' === get_post_type()) :
        ?>
            <div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
                <span class="post-date mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <?php echo get_the_date(); ?>
                </span>
                
                <span class="post-author mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                        <?php the_author(); ?>
                    </a>
                </span>
                
                <?php if (has_category()) : ?>
                <span class="post-categories mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <?php the_category(', '); ?>
                </span>
                <?php endif; ?>
                
                <?php if (has_tag()) : ?>
                <span class="post-tags">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                    <?php the_tags('', ', '); ?>
                </span>
                <?php endif; ?>
                
                <?php if (comments_open() || get_comments_number()) : ?>
                <span class="post-comments ml-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <a href="<?php comments_link(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                        <?php comments_number(__('No Comments', 'aqualuxe'), __('1 Comment', 'aqualuxe'), __('% Comments', 'aqualuxe')); ?>
                    </a>
                </span>
                <?php endif; ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail() && !is_singular()) : ?>
        <div class="post-thumbnail mb-6">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'w-full h-auto rounded-lg')); ?>
            </a>
        </div><!-- .post-thumbnail -->
    <?php endif; ?>

    <div class="entry-content prose dark:prose-invert max-w-none">
        <?php
        if (is_singular()) :
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Continue reading<span class="sr-only"> "%s"</span>', 'aqualuxe'),
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
                    'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">' . esc_html__('Pages:', 'aqualuxe'),
                    'after'  => '</div>',
                )
            );
        else :
            the_excerpt();
        ?>
            <div class="mt-4">
                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                    <?php esc_html_e('Continue Reading', 'aqualuxe'); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        <?php
        endif;
        ?>
    </div><!-- .entry-content -->

    <?php if (is_singular() && 'post' === get_post_type()) : ?>
        <footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <?php aqualuxe_entry_footer(); ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->