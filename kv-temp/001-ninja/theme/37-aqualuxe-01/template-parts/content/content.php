<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-12'); ?>>
    <header class="entry-header mb-6">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title text-3xl md:text-4xl font-bold mb-2">', '</h1>');
        else :
            the_title('<h2 class="entry-title text-2xl md:text-3xl font-bold mb-2"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="hover:text-primary-500 transition-colors">', '</a></h2>');
        endif;

        if ('post' === get_post_type()) :
            ?>
            <div class="entry-meta text-sm text-secondary-500 dark:text-secondary-400 mb-4">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail() && !is_singular()) : ?>
        <div class="post-thumbnail mb-6">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('large', array('class' => 'rounded-lg w-full h-auto')); ?>
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
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                    'after'  => '</div>',
                )
            );
        else :
            the_excerpt();
            ?>
            <div class="mt-4">
                <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium bg-primary-500 text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer mt-6 pt-4 border-t border-secondary-200 dark:border-secondary-700 text-sm text-secondary-500 dark:text-secondary-400">
        <?php aqualuxe_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->