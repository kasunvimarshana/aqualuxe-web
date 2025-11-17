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

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md transition-shadow hover:shadow-lg mb-8'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-64 object-cover')); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content p-6">
        <header class="entry-header mb-4">
            <?php
            if (is_singular()) :
                the_title('<h1 class="entry-title text-2xl font-bold text-gray-900 dark:text-white mb-2">', '</h1>');
            else :
                the_title('<h2 class="entry-title text-xl font-bold text-gray-900 dark:text-white mb-2"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="hover:text-teal-600 dark:hover:text-teal-400 transition-colors">', '</a></h2>');
            endif;

            if ('post' === get_post_type()) :
            ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                    <?php
                    aqualuxe_posted_on();
                    aqualuxe_posted_by();
                    ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->

        <?php if (is_singular()) : ?>
            <div class="entry-content prose dark:prose-invert max-w-none">
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
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                        'after'  => '</div>',
                    )
                );
                ?>
            </div><!-- .entry-content -->
        <?php else : ?>
            <div class="entry-summary text-gray-700 dark:text-gray-300">
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->
        <?php endif; ?>

        <footer class="entry-footer mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400">
                <?php aqualuxe_entry_footer(); ?>
            </div>
        </footer><!-- .entry-footer -->
    </div>
</article><!-- #post-<?php the_ID(); ?> -->