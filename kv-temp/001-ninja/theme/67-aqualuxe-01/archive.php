<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php
        do_action( 'aqualuxe_archive_before' );

        if ( have_posts() ) :
            ?>
            <header class="page-header">
                <?php
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
                ?>
            </header><!-- .page-header -->

            <div class="posts-wrapper blog-layout-<?php echo esc_attr( aqualuxe_get_blog_layout() ); ?>">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'templates/content', get_post_type() );

                endwhile;
                ?>
            </div><!-- .posts-wrapper -->

            <?php
            the_posts_pagination( array(
                'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg><span class="screen-reader-text">' . esc_html__( 'Previous page', 'aqualuxe' ) . '</span>',
                'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'aqualuxe' ) . '</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
            ) );

        else :

            get_template_part( 'templates/content', 'none' );

        endif;

        do_action( 'aqualuxe_archive_after' );
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
if ( aqualuxe_has_sidebar() ) {
    get_sidebar();
}
get_footer();