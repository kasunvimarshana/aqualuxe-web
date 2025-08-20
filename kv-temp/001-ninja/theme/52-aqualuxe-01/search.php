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

<main id="primary" class="site-main container mx-auto px-4 py-8">

	<?php if ( have_posts() ) : ?>

		<header class="page-header mb-8">
			<h1 class="page-title text-3xl font-bold mb-4">
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
				?>
			</h1>
		</header><!-- .page-header -->

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
		</div>

		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 2,
				'prev_text' => sprintf(
					'<span class="nav-prev-text">%s</span>',
					esc_html__( 'Previous', 'aqualuxe' )
				),
				'next_text' => sprintf(
					'<span class="nav-next-text">%s</span>',
					esc_html__( 'Next', 'aqualuxe' )
				),
			)
		);

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();