<?php
/**
 * The template for displaying all single posts
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/blog/content', get_post_format() );

			get_template_part( 'template-parts/blog/post-navigation' );

			if ( get_the_author_meta( 'description' ) ) {
				get_template_part( 'template-parts/blog/author-bio' );
			}

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
