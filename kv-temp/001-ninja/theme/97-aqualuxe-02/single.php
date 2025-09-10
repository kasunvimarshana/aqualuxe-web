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

	<main id="primary" class="site-main py-12">
    <div class="container mx-auto px-4">
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'templates/content', get_post_type() );

			the_post_navigation(
				array(
					'prev_text' => '<div class="flex justify-between items-center"><span class="nav-subtitle text-sm font-bold uppercase tracking-wider">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</span><span class="nav-title text-lg font-semibold">%title</span></div>',
					'next_text' => '<div class="flex justify-between items-center"><span class="nav-subtitle text-sm font-bold uppercase tracking-wider">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</span><span class="nav-title text-lg font-semibold">%title</span></div>',
				)
			);

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
