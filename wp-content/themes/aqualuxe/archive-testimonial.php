<?php
/**
 * The template for displaying archive pages for Testimonials.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main py-12">
    <div class="container mx-auto px-4">
        <header class="page-header mb-8 text-center">
            <h1 class="page-title text-4xl font-extrabold text-gray-800 tracking-tight"><?php post_type_archive_title(); ?></h1>
            <?php
            the_archive_description( '<div class="archive-description text-lg text-gray-600 mt-2">', '</div>' );
            ?>
        </header><!-- .page-header -->

        <?php if ( have_posts() ) : ?>
            <div class="space-y-8">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'templates/parts/content', 'testimonial' );

                endwhile;
                ?>
            </div>
        <?php
            the_posts_navigation();

        else :

            get_template_part( 'templates/parts/content', 'none' );

        endif;
        ?>
    </div><!-- .container -->
</main><!-- #main -->

<?php
get_footer();
