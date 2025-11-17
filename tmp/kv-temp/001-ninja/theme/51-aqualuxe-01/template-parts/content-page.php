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

<article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-page'); ?>>
    <header class="aqualuxe-page-header">
        <?php the_title('<h1 class="aqualuxe-page-title">', '</h1>'); ?>
    </header>

    <?php if (has_post_thumbnail()) : ?>
        <div class="aqualuxe-page-thumbnail">
            <?php the_post_thumbnail('full'); ?>
        </div>
    <?php endif; ?>

    <div class="aqualuxe-page-content">
        <?php
        the_content();

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                'after'  => '</div>',
            )
        );
        ?>
    </div>

    <?php if (get_edit_post_link()) : ?>
        <footer class="aqualuxe-page-footer">
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
        </footer>
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->