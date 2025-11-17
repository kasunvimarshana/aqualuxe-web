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

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			// Check if the page has a custom template
			$page_template = get_page_template_slug();
			
			if ( $page_template ) {
				// Custom template is being used, let it handle the layout
				get_template_part( 'template-parts/content/content', 'page' );
			} else {
				// Default page layout
				?>
				<div class="container mx-auto px-4 py-12">
					<header class="entry-header mb-8">
						<?php the_title( '<h1 class="entry-title text-4xl font-serif font-bold text-dark-900 dark:text-white">', '</h1>' ); ?>
						
						<?php
						if ( function_exists( 'yoast_breadcrumb' ) ) {
							yoast_breadcrumb( '<div class="breadcrumbs text-sm text-dark-500 dark:text-dark-400 mt-4">', '</div>' );
						}
						?>
					</header><!-- .entry-header -->

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="featured-image mb-8">
							<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto rounded-lg shadow-lg' ) ); ?>
						</div>
					<?php endif; ?>

					<div class="entry-content prose prose-lg dark:prose-dark max-w-none">
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

					<?php if ( get_edit_post_link() ) : ?>
						<footer class="entry-footer mt-8 text-sm text-dark-500 dark:text-dark-400">
							<?php
							edit_post_link(
								sprintf(
									wp_kses(
										/* translators: %s: Name of current post. Only visible to screen readers */
										__( 'Edit <span class="sr-only">%s</span>', 'aqualuxe' ),
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
				</div>
				<?php
			}

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();