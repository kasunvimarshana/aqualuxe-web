<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

get_header();

// Get the layout setting
$sidebar_layout = get_theme_mod( 'aqualuxe_search_layout', 'right-sidebar' );
$container_class = 'no-sidebar' === $sidebar_layout ? 'container-fluid' : 'container';
?>

	<main id="primary" class="site-main <?php echo esc_attr( $container_class ); ?>">
		<div class="row">
			<div class="<?php echo esc_attr( aqualuxe_get_content_classes( $sidebar_layout ) ); ?>">
				<?php if ( have_posts() ) : ?>

					<header class="page-header">
						<h1 class="page-title">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
							?>
						</h1>
					</header><!-- .page-header -->

					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'templates/content/content', 'search' );

					endwhile;

					// Pagination
					aqualuxe_pagination();

				else :

					get_template_part( 'templates/content/content', 'none' );

				endif;
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