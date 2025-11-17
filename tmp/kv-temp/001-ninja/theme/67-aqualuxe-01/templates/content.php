<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$blog_layout = aqualuxe_get_blog_layout();
$post_classes = array( 'post-item', 'post-layout-' . $blog_layout );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
    <?php do_action( 'aqualuxe_post_before_content' ); ?>

    <?php aqualuxe_post_thumbnail(); ?>

    <div class="post-content">
        <header class="entry-header">
            <?php
            if ( is_singular() ) :
                the_title( '<h1 class="entry-title">', '</h1>' );
            else :
                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            endif;

            if ( 'post' === get_post_type() ) :
                aqualuxe_post_meta();
            endif;
            ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php
            if ( is_singular() ) :
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );

                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
                        'after'  => '</div>',
                    )
                );
            else :
                aqualuxe_post_excerpt();
                aqualuxe_read_more_link();
            endif;
            ?>
        </div><!-- .entry-content -->

        <?php if ( is_singular() && 'post' === get_post_type() ) : ?>
            <footer class="entry-footer">
                <?php aqualuxe_post_tags(); ?>
            </footer><!-- .entry-footer -->
        <?php endif; ?>
    </div><!-- .post-content -->

    <?php do_action( 'aqualuxe_post_after_content' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->