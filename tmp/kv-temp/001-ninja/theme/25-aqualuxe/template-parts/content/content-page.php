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
    <?php if (!get_post_meta(get_the_ID(), '_aqualuxe_hide_title', true)) : ?>
        <header class="entry-header mb-8">
            <?php the_title('<h1 class="entry-title text-3xl font-bold">', '</h1>'); ?>
        </header><!-- .entry-header -->
    <?php endif; ?>

    <?php if (has_post_thumbnail() && !get_post_meta(get_the_ID(), '_aqualuxe_hide_featured_image', true)) : ?>
        <div class="post-thumbnail mb-8">
            <?php the_post_thumbnail('full', array('class' => 'w-full h-auto rounded')); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content prose max-w-none">
        <?php
        the_content();

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <?php if (get_edit_post_link() && current_user_can('edit_post', get_the_ID())) : ?>
        <footer class="entry-footer mt-8 pt-4 border-t border-gray-200">
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