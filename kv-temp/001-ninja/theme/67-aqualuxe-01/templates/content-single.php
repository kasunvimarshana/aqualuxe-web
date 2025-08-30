<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
    <?php do_action( 'aqualuxe_post_before_content' ); ?>

    <?php aqualuxe_post_thumbnail(); ?>

    <div class="post-content">
        <header class="entry-header">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            
            <?php aqualuxe_post_meta(); ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php
            do_action( 'aqualuxe_post_content_before' );

            the_content();

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
                    'after'  => '</div>',
                )
            );

            do_action( 'aqualuxe_post_content_after' );
            ?>
        </div><!-- .entry-content -->

        <footer class="entry-footer">
            <?php aqualuxe_post_tags(); ?>
            
            <?php if ( function_exists( 'aqualuxe_social_sharing' ) ) : ?>
                <div class="post-sharing">
                    <h3><?php esc_html_e( 'Share This Post', 'aqualuxe' ); ?></h3>
                    <?php aqualuxe_social_sharing(); ?>
                </div>
            <?php endif; ?>
        </footer><!-- .entry-footer -->
    </div><!-- .post-content -->

    <?php do_action( 'aqualuxe_post_after_content' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->