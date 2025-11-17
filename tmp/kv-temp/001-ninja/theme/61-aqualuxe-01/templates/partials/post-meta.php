<?php
/**
 * Template part for displaying post meta
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( 'post' !== get_post_type() ) {
    return;
}
?>

<div class="post-meta">
    <?php if ( get_theme_mod( 'aqualuxe_blog_single_author', true ) ) : ?>
        <span class="post-author">
            <?php echo aqualuxe_get_post_author(); ?>
        </span>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'aqualuxe_blog_single_date', true ) ) : ?>
        <span class="post-date">
            <?php echo aqualuxe_get_post_date(); ?>
        </span>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'aqualuxe_blog_single_categories', true ) ) : ?>
        <span class="post-categories">
            <?php echo aqualuxe_get_post_categories(); ?>
        </span>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'aqualuxe_blog_single_comments', true ) && ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
        <span class="post-comments">
            <?php echo aqualuxe_get_post_comments(); ?>
        </span>
    <?php endif; ?>
</div>