<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-12'); ?>>
    <header class="entry-header mb-6">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title text-3xl font-bold mb-4">', '</h1>');
        else :
            the_title('<h2 class="entry-title text-2xl font-bold mb-4"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;

        if ('post' === get_post_type()) :
            ?>
            <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mb-4">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail() && !is_singular()) : ?>
        <div class="entry-thumbnail mb-6">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded-lg']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content prose dark:prose-invert max-w-none">
        <?php
        if (is_singular()) :
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                        [
                            'span' => [
                                'class' => [],
                            ],
                        ]
                    ),
                    wp_kses_post(get_the_title())
                )
            );

            wp_link_pages(
                [
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                    'after'  => '</div>',
                ]
            );
        else :
            the_excerpt();
            ?>
            <p class="mt-4">
                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors">
                    <?php esc_html_e('Continue Reading', 'aqualuxe'); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </p>
        <?php endif; ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer mt-6 text-sm text-gray-600 dark:text-gray-400">
        <?php aqualuxe_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->