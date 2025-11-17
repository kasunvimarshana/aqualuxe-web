<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

get_header();

// Get blog layout
$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );

// Get sidebar position
$sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );

// Set content class based on sidebar position
$content_class = 'none' === $sidebar_position ? 'content-area-full' : 'content-area';
?>

	<div class="site-content-wrap <?php echo esc_attr( 'sidebar-' . $sidebar_position ); ?>">
		<main id="primary" class="<?php echo esc_attr( $content_class ); ?>">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
						?>
					</h1>
				</header><!-- .page-header -->

				<div class="posts-wrap posts-layout-<?php echo esc_attr( $blog_layout ); ?>">
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );

					endwhile;
					?>
				</div><!-- .posts-wrap -->

				<?php
				// Pagination
				aqualuxe_pagination();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

		</main><!-- #main -->

		<?php
		// Sidebar
		if ( 'none' !== $sidebar_position ) {
			get_sidebar();
		}
		?>
	</div><!-- .site-content-wrap -->

<?php
get_footer();