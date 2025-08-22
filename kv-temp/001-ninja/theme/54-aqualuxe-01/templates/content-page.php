<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden'); ?>>
    <?php if (has_post_thumbnail() && !is_front_page()) : ?>
        <div class="entry-thumbnail">
            <?php the_post_thumbnail('full', ['class' => 'w-full h-auto']); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content p-8">
        <header class="entry-header mb-6">
            <?php
            if (!is_front_page()) :
                the_title('<h1 class="entry-title text-3xl font-bold">', '</h1>');
            endif;
            ?>
        </header><!-- .entry-header -->

        <div class="prose dark:prose-invert max-w-none">
            <?php
            the_content();

            wp_link_pages(
                [
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                    'after'  => '</div>',
                ]
            );
            ?>
        </div><!-- .entry-content -->

        <?php if (get_edit_post_link()) : ?>
            <footer class="entry-footer mt-6 text-sm text-gray-600 dark:text-gray-400">
                <?php
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
                ?>
            </footer><!-- .entry-footer -->
        <?php endif; ?>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->