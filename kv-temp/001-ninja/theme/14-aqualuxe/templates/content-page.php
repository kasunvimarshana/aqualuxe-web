<?php
/**
 * Template part for displaying page content
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
            <h1 class="entry-title text-3xl font-bold text-gray-900 dark:text-white">
                <?php the_title(); ?>
            </h1>
        </header><!-- .entry-header -->

        <div class="prose dark:prose-invert max-w-none">
            <?php
            the_content();

            wp_link_pages(
                array(
                    'before' => '<div class="page-links mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">' . esc_html__('Pages:', 'aqualuxe'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div>
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