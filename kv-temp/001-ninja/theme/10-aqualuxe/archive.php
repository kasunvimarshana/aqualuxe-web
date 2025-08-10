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

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if ( have_posts() ) : ?>

                    <header class="page-header">
                        <?php
                        the_archive_title( '<h1 class="page-title">', '</h1>' );
                        the_archive_description( '<div class="archive-description">', '</div>' );
                        ?>
                    </header><!-- .page-header -->

                    <?php
                    /* Start the Loop */
                    while ( have_posts() ) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part( 'templates/parts/content', get_post_type() );

                    endwhile;

                    the_posts_pagination(
                        array(
                            'prev_text'          => '<i class="fas fa-chevron-left"></i><span class="screen-reader-text">' . esc_html__( 'Previous page', 'aqualuxe' ) . '</span>',
                            'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'aqualuxe' ) . '</span><i class="fas fa-chevron-right"></i>',
                            'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'aqualuxe' ) . ' </span>',
                            'mid_size'           => 2,
                        )
                    );

                else :

                    get_template_part( 'templates/parts/content', 'none' );

                endif;
                ?>
            </div>
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();