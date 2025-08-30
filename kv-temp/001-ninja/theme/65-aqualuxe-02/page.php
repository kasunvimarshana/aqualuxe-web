<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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

                <?php
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'templates/content/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

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