<?php
/**
 * Template part for displaying post footer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Hide category and tag text for pages.
if ( 'post' !== get_post_type() ) {
    return;
}

// Don't display on archive pages
if ( ! is_singular() ) {
    return;
}
?>

<div class="post-footer">
    <?php if ( get_theme_mod( 'aqualuxe_blog_single_tags', true ) && has_tag() ) : ?>
        <div class="post-tags">
            <?php echo aqualuxe_get_post_tags(); ?>
        </div>
    <?php endif; ?>

    <?php
    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
                [
                    'span' => [
                        'class' => [],
                    ],
                ]
            ),
            wp_kses_post( get_the_title() )
        ),
        '<div class="edit-link">',
        '</div>'
    );
    ?>
</div>