<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

                <?php
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'templates/content/content', 'single' );

                endwhile; // End of the loop.
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