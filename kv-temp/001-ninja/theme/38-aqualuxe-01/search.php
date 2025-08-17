<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

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
			$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
			?>

			<div class="posts-<?php echo esc_attr( $blog_layout ); ?>">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'templates/parts/content/content', 'search' );

				endwhile;
				?>
			</div>

			<?php
			aqualuxe_pagination();

		else :

			get_template_part( 'templates/parts/content/content', 'none' );

		endif;
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
if ( 'full-width' !== $layout ) {
	get_sidebar();
}

get_footer();