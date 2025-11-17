<?php
/**
 * The template for displaying legal pages (Privacy Policy, Terms, etc.)
 * This is a generic template that can be assigned to legal pages.
 *
 * Template Name: Legal Page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
						'after'  => '</div>',
					)
				);
				?>
			</div><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->

	</main><!-- #main -->

<?php
get_footer();
