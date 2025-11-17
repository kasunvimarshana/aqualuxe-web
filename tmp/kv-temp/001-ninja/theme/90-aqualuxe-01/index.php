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
 */

get_header();
?>

	<main id="primary" class="site-main container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
            <?php
            if ( have_posts() ) :

                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header>
                        <h1 class="page-title text-3xl font-bold mb-8"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                /* Start the Loop */
                echo '<div class="space-y-12">';
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     */
                    get_template_part( 'template-parts/content', get_post_type() );

                endwhile;
                echo '</div>';

                the_posts_navigation(array(
                    'prev_text' => __( '&larr; Older posts', 'aqualuxe' ),
                    'next_text' => __( 'Newer posts &rarr;', 'aqualuxe' ),
                ));

            else :

                get_template_part( 'template-parts/content', 'none' );

            endif;
            ?>
        </div>
        <aside class="md:col-span-1">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</main><!-- #main -->
get_footer();
