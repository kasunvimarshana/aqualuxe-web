<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="<?php echo aqualuxe_get_main_content_class(); ?>">

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
			$blog_layout = aqualuxe_get_blog_layout();
			$blog_columns = aqualuxe_get_blog_columns();
			
			if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) :
				echo '<div class="blog-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-' . esc_attr( $blog_columns ) . ' gap-8">';
			endif;

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'templates/content', 'search' );

			endwhile;
			
			if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) :
				echo '</div>';
			endif;

			aqualuxe_pagination();

		else :

			get_template_part( 'templates/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
if ( aqualuxe_display_sidebar() ) :
	get_sidebar();
endif;
get_footer();