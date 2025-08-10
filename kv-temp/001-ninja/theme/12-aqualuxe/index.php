<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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
                <?php
                if ( have_posts() ) :

                    if ( is_home() && ! is_front_page() ) :
                        ?>
                        <header class="page-header">
                            <h1 class="page-title"><?php single_post_title(); ?></h1>
                        </header>
                        <?php
                    endif;

                    /* Start the Loop */
                    while ( have_posts() ) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part( 'template-parts/content/content', get_post_type() );

                    endwhile;

                    the_posts_navigation(
                        array(
                            'prev_text' => '<span class="nav-arrow">&larr;</span> ' . esc_html__( 'Older posts', 'aqualuxe' ),
                            'next_text' => esc_html__( 'Newer posts', 'aqualuxe' ) . ' <span class="nav-arrow">&rarr;</span>',
                        )
                    );

                else :

                    get_template_part( 'template-parts/content/content', 'none' );

                endif;
                ?>
            </div><!-- .col-lg-8 -->

            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div><!-- .col-lg-4 -->
        </div><!-- .row -->
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();