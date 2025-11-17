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
		<div class="container">
			<?php if ( aqualuxe_has_sidebar() && is_active_sidebar( 'sidebar-1' ) ) : ?>
				<div class="row has-sidebar">
					<div class="col-content">
			<?php else : ?>
				<div class="row">
					<div class="col-full">
			<?php endif; ?>

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

				<div class="posts-wrapper posts-layout-<?php echo esc_attr( $blog_layout ); ?>">
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
				</div><!-- .posts-wrapper -->

				<?php
				aqualuxe_pagination();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

					</div><!-- .col-content or .col-full -->
					
					<?php if ( aqualuxe_has_sidebar() && is_active_sidebar( 'sidebar-1' ) ) : ?>
						<div class="col-sidebar">
							<?php get_sidebar(); ?>
						</div>
					<?php endif; ?>
				</div><!-- .row -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();