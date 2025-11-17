<?php
/**
 * Template part for displaying page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php aqualuxe_attr( 'article' ); ?>>
    <?php
    /**
     * Hook: aqualuxe_before_page
     */
    aqualuxe_do_before_page();
    ?>

    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header><!-- .entry-header -->

    <?php aqualuxe_post_thumbnail(); ?>

    <?php
    /**
     * Hook: aqualuxe_page_top
     */
    aqualuxe_do_page_top();
    ?>

    <div class="entry-content">
        <?php
        /**
         * Hook: aqualuxe_page_content_before
         */
        aqualuxe_do_page_content_before();
        ?>

        <?php
        the_content();

        wp_link_pages(
            [
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
                'after'  => '</div>',
            ]
        );
        ?>

        <?php
        /**
         * Hook: aqualuxe_page_content_after
         */
        aqualuxe_do_page_content_after();
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
        /**
         * Hook: aqualuxe_page_bottom
         */
        aqualuxe_do_page_bottom();
        ?>
    </footer><!-- .entry-footer -->

    <?php
    /**
     * Hook: aqualuxe_after_page
     */
    aqualuxe_do_after_page();
    ?>
</article><!-- #post-<?php the_ID(); ?> -->