<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<?php
		/**
		 * Hook: aqualuxe_content_before.
		 */
		do_action( 'aqualuxe_content_before' );
		?>

		<div class="container mx-auto px-4 py-8">
			<div class="flex flex-wrap -mx-4">
				<?php
				// Get the layout.
				$layout = aqualuxe_get_layout();
				$has_sidebar = aqualuxe_has_sidebar();
				$content_class = $has_sidebar ? 'lg:w-2/3' : 'w-full';
				$sidebar_class = 'lg:w-1/3';

				// Adjust classes based on sidebar position.
				if ( $has_sidebar && 'left-sidebar' === $layout ) {
					?>
					<div class="w-full <?php echo esc_attr( $sidebar_class ); ?> px-4 order-2 lg:order-1">
						<?php get_sidebar(); ?>
					</div>
					<div class="w-full <?php echo esc_attr( $content_class ); ?> px-4 order-1 lg:order-2">
					<?php
				} else {
					?>
					<div class="w-full <?php echo esc_attr( $content_class ); ?> px-4">
					<?php
				}
				?>

					<?php
					/**
					 * Hook: aqualuxe_content_top.
					 */
					do_action( 'aqualuxe_content_top' );
					?>

					<?php if ( have_posts() ) : ?>

						<header class="page-header mb-8">
							<?php
							if ( is_home() && ! is_front_page() ) :
								$blog_title = get_the_title( get_option( 'page_for_posts', true ) );
								?>
								<h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500">
									<?php echo esc_html( $blog_title ); ?>
								</h1>
								<?php
							else :
								?>
								<h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500">
									<?php esc_html_e( 'Latest Posts', 'aqualuxe' ); ?>
								</h1>
								<?php
							endif;
							?>
						</header><!-- .page-header -->

						<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo $has_sidebar ? '2' : '3'; ?> gap-8">
							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/*
								 * Include the Post-Type-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
								 */
								get_template_part( 'templates/parts/content/content', get_post_type() );

							endwhile;
							?>
						</div>

						<?php
						/**
						 * Hook: aqualuxe_pagination.
						 */
						do_action( 'aqualuxe_pagination' );
						?>

					<?php else : ?>

						<?php get_template_part( 'templates/parts/content/content', 'none' ); ?>

					<?php endif; ?>

					<?php
					/**
					 * Hook: aqualuxe_content_bottom.
					 */
					do_action( 'aqualuxe_content_bottom' );
					?>

				</div><!-- .content-column -->

				<?php
				// Add sidebar for right sidebar layout.
				if ( $has_sidebar && 'right-sidebar' === $layout ) :
					?>
					<div class="w-full <?php echo esc_attr( $sidebar_class ); ?> px-4">
						<?php get_sidebar(); ?>
					</div>
					<?php
				endif;
				?>
			</div><!-- .row -->
		</div><!-- .container -->

		<?php
		/**
		 * Hook: aqualuxe_content_after.
		 */
		do_action( 'aqualuxe_content_after' );
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();