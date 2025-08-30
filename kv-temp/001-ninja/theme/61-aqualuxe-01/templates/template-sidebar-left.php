<?php
/**
 * Template Name: Left Sidebar
 * Template Post Type: page
 *
 * A template with the sidebar on the left side.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="container">
    <div class="row sidebar-left-layout">
        <div class="col-lg-4 sidebar-column">
            <?php get_sidebar(); ?>
        </div>
        
        <div class="col-lg-8 content-column">
            <main id="primary" class="site-main">
                <?php
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'templates/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>
            </main><!-- #main -->
        </div>
    </div>
</div>

<?php
get_footer();