<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php aqualuxe_attr( 'article' ); ?>>
    <?php
    /**
     * Hook: aqualuxe_before_post
     */
    aqualuxe_do_before_post();
    ?>

    <header class="entry-header">
        <?php
        if ( is_singular() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        endif;

        if ( 'post' === get_post_type() ) :
            ?>
            <div class="entry-meta">
                <?php
                aqualuxe_post_meta();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php aqualuxe_post_thumbnail(); ?>

    <?php
    /**
     * Hook: aqualuxe_post_top
     */
    aqualuxe_do_post_top();
    ?>

    <div class="entry-content">
        <?php
        /**
         * Hook: aqualuxe_post_content_before
         */
        aqualuxe_do_post_content_before();
        ?>

        <?php
        if ( is_singular() ) :
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
                        [
                            'span' => [
                                'class' => [],
                            ],
                        ]
                    ),
                    wp_kses_post( get_the_title() )
                )
            );

            wp_link_pages(
                [
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
                    'after'  => '</div>',
                ]
            );
        else :
            the_excerpt();
            ?>
            <div class="entry-read-more">
                <a href="<?php the_permalink(); ?>" class="read-more-link">
                    <?php echo esc_html__( 'Read More', 'aqualuxe' ); ?>
                    <?php echo aqualuxe_get_icon( 'chevron-right' ); ?>
                </a>
            </div>
        <?php endif; ?>

        <?php
        /**
         * Hook: aqualuxe_post_content_after
         */
        aqualuxe_do_post_content_after();
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
        /**
         * Hook: aqualuxe_post_bottom
         */
        aqualuxe_do_post_bottom();
        ?>
    </footer><!-- .entry-footer -->

    <?php
    /**
     * Hook: aqualuxe_after_post
     */
    aqualuxe_do_after_post();
    ?>
</article><!-- #post-<?php the_ID(); ?> -->