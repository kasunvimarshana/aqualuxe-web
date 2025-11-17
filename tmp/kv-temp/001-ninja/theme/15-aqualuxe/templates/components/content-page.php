<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (!get_theme_mod('aqualuxe_hide_page_title', false)) : ?>
    <header class="entry-header mb-8">
        <?php the_title('<h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-bold">', '</h1>'); ?>
    </header><!-- .entry-header -->
    <?php endif; ?>

    <?php if (has_post_thumbnail() && get_theme_mod('aqualuxe_show_page_featured_image', true)) : ?>
        <div class="page-thumbnail mb-8">
            <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'w-full h-auto rounded-lg shadow-md')); ?>
            <?php if (get_the_post_thumbnail_caption()) : ?>
                <figcaption class="mt-2 text-sm text-center text-gray-500 dark:text-gray-400">
                    <?php echo wp_kses_post(get_the_post_thumbnail_caption()); ?>
                </figcaption>
            <?php endif; ?>
        </div><!-- .page-thumbnail -->
    <?php endif; ?>

    <div class="entry-content prose dark:prose-invert lg:prose-lg max-w-none">
        <?php
        the_content();

        wp_link_pages(
            array(
                'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">' . esc_html__('Pages:', 'aqualuxe'),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <?php if (get_edit_post_link() && get_theme_mod('aqualuxe_show_edit_link', true)) : ?>
        <footer class="entry-footer mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400">
            <?php
            edit_post_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Edit <span class="sr-only">%s</span>', 'aqualuxe'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                ),
                '<span class="edit-link flex items-center">',
                '</span>',
                null,
                'inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400'
            );
            ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->