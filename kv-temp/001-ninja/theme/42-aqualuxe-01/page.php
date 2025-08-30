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

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<?php
			while ( have_posts() ) :
				the_post();

				// Breadcrumbs
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<div class="breadcrumbs text-sm text-gray-600 mb-6">', '</div>' );
				} else {
					aqualuxe_breadcrumbs();
				}
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header mb-8">
						<?php the_title( '<h1 class="entry-title text-4xl font-bold text-primary-800">', '</h1>' ); ?>
					</header><!-- .entry-header -->

					<?php if ( has_post_thumbnail() && ! get_theme_mod( 'aqualuxe_hide_page_featured_image', false ) ) : ?>
						<div class="entry-thumbnail mb-8">
							<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto rounded-lg shadow-lg' ) ); ?>
						</div>
					<?php endif; ?>

					<div class="entry-content prose prose-lg max-w-none">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200">' . esc_html__( 'Pages:', 'aqualuxe' ),
								'after'  => '</div>',
							)
						);
						?>
					</div><!-- .entry-content -->

					<?php if ( get_edit_post_link() ) : ?>
						<footer class="entry-footer mt-8 pt-4 border-t border-gray-200 text-sm text-gray-600">
							<?php
							edit_post_link(
								sprintf(
									wp_kses(
										/* translators: %s: Name of current post. Only visible to screen readers */
										__( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
										array(
											'span' => array(
												'class' => array(),
											),
										)
									),
									wp_kses_post( get_the_title() )
								),
								'<span class="edit-link">',
								'</span>'
							);
							?>
						</footer><!-- .entry-footer -->
					<?php endif; ?>
				</article><!-- #post-<?php the_ID(); ?> -->

				<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>

			<?php endwhile; // End of the loop. ?>
		</div>
	</main><!-- #main -->

<?php
get_footer();