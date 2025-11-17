<?php
/**
 * Template Name: Full Width
 * Template Post Type: page
 *
 * A full-width template with no sidebar.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main full-width">
    <div class="container-fluid">
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
    </div>
</main><!-- #main -->

<?php
get_footer();