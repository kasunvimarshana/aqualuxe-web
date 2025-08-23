<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap -mx-4">
        <div class="w-full lg:w-2/3 px-4">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">

                <?php if ( have_posts() ) : ?>

                    <header class="page-header mb-8">
                        <?php
                        the_archive_title( '<h1 class="page-title text-3xl font-bold">', '</h1>' );
                        the_archive_description( '<div class="archive-description mt-4 prose dark:prose-invert">', '</div>' );
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
                        get_template_part( 'templates/content/content', get_post_type() );

                    endwhile;

                    ?>
                    <div class="pagination mt-8">
                        <?php the_posts_pagination( array(
                            'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"/></svg>' . esc_html__( 'Previous', 'aqualuxe' ),
                            'next_text' => esc_html__( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>',
                            'class' => 'flex justify-center',
                        ) ); ?>
                    </div>

                <?php else :

                    get_template_part( 'templates/content/content', 'none' );

                endif;
                ?>

                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- .w-full -->

        <div class="w-full lg:w-1/3 px-4">
            <?php get_sidebar(); ?>
        </div><!-- .w-full -->
    </div><!-- .flex -->
</div><!-- .container -->

<?php
get_footer();