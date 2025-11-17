<?php
/**
 * The template for displaying the contact page.
 *
 * Template Name: Contact Page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			the_title( '<h1 class="entry-title text-center text-4xl font-bold my-8">', '</h1>' );
			the_content();
		endwhile;
		?>

        <div class="container mx-auto py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
				<?php get_template_part( 'template-parts/pages/contact/contact-form' ); ?>
                <div class="contact-info-map-container">
					<?php get_template_part( 'template-parts/pages/contact/contact-info' ); ?>
					<?php get_template_part( 'template-parts/pages/contact/google-map' ); ?>
                </div>
            </div>
        </div>

	</main><!-- #main -->

<?php
get_footer();
