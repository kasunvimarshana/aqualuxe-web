<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

// Get the layout setting
$sidebar_layout = get_theme_mod( 'aqualuxe_single_post_layout', 'right-sidebar' );
$container_class = 'no-sidebar' === $sidebar_layout ? 'container-fluid' : 'container';
?>

	<main id="primary" class="site-main <?php echo esc_attr( $container_class ); ?>">
		<div class="row">
			<div class="<?php echo esc_attr( aqualuxe_get_content_classes( $sidebar_layout ) ); ?>">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'templates/content/content', 'single' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

					// Post navigation
					$prev_post = get_previous_post();
					$next_post = get_next_post();

					if ( $prev_post || $next_post ) :
						?>
						<nav class="navigation post-navigation" role="navigation">
							<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'aqualuxe' ); ?></h2>
							<div class="nav-links">
								<?php if ( $prev_post ) : ?>
									<div class="nav-previous">
										<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" rel="prev">
											<span class="nav-subtitle"><?php esc_html_e( 'Previous:', 'aqualuxe' ); ?></span>
											<span class="nav-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
										</a>
									</div>
								<?php endif; ?>

								<?php if ( $next_post ) : ?>
									<div class="nav-next">
										<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next">
											<span class="nav-subtitle"><?php esc_html_e( 'Next:', 'aqualuxe' ); ?></span>
											<span class="nav-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</nav>
						<?php
					endif;

					// Related posts
					if ( get_theme_mod( 'aqualuxe_related_posts', true ) ) :
						get_template_part( 'templates/components/related-posts' );
					endif;

				endwhile; // End of the loop.
				?>
			</div>

			<?php
			// Include sidebar if layout is not 'no-sidebar'
			if ( 'no-sidebar' !== $sidebar_layout && 'full-width' !== $sidebar_layout ) {
				get_sidebar();
			}
			?>
		</div>
	</main><!-- #main -->

<?php
get_footer();