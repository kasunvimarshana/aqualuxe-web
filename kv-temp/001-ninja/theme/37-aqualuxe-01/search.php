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

	<main id="primary" class="site-main" role="main">

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
					get_template_part( 'templates/content', 'search' );

				endwhile;
				?>
			</div><!-- .posts-grid -->

			<?php
			the_posts_pagination(
				array(
					'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M7.828 11H20v2H7.828l5.364 5.364-1.414 1.414L4 12l7.778-7.778 1.414 1.414z"/></svg> <span class="screen-reader-text">' . esc_html__( 'Previous', 'aqualuxe' ) . '</span>',
					'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next', 'aqualuxe' ) . '</span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"/></svg>',
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'aqualuxe' ) . ' </span>',
				)
			);

		else :

			?>
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'No results found', 'aqualuxe' ); ?></h1>
			</header><!-- .page-header -->

			<div class="no-results">
				<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .no-results -->

		<?php endif; ?>

	</main><!-- #primary -->

<?php
get_sidebar();
get_footer();