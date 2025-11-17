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

<main id="primary" class="site-main">

	<?php
	/**
	 * Hook: aqualuxe_before_main_content.
	 *
	 * @hooked aqualuxe_breadcrumbs - 10
	 */
	do_action( 'aqualuxe_before_main_content' );
	?>

	<div class="container">
		<div class="content-area">
			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
						?>
					</h1>
				</header><!-- .page-header -->

				<div class="posts-grid">
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
				</div><!-- .posts-grid -->

				<?php
				/**
				 * Hook: aqualuxe_pagination.
				 *
				 * @hooked aqualuxe_numeric_pagination - 10
				 */
				do_action( 'aqualuxe_pagination' );

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</div><!-- .content-area -->

		<?php
		/**
		 * Hook: aqualuxe_sidebar.
		 *
		 * @hooked aqualuxe_get_sidebar - 10
		 */
		do_action( 'aqualuxe_sidebar' );
		?>
	</div><!-- .container -->

	<?php
	/**
	 * Hook: aqualuxe_after_main_content.
	 */
	do_action( 'aqualuxe_after_main_content' );
	?>

</main><!-- #primary -->

<?php
get_footer();