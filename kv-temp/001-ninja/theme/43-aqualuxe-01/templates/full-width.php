<?php
/**
 * Template Name: Full Width
 * Template Post Type: page, post
 *
 * A template with no sidebar, spanning the full width of the container.
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-8">
        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content/content', 'page' );

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