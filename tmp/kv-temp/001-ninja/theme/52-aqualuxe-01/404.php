<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-16">

	<section class="error-404 not-found text-center">
		<header class="page-header mb-8">
			<h1 class="page-title text-4xl font-bold mb-4"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
		</header><!-- .page-header -->

		<div class="page-content">
			<p class="text-xl mb-8"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

			<div class="max-w-md mx-auto mb-12">
				<?php get_search_form(); ?>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
				<div>
					<h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
					<ul class="list-disc pl-5">
						<?php
						wp_list_categories(
							array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							)
						);
						?>
					</ul>
				</div>

				<div>
					<h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
					<ul class="list-disc pl-5">
						<?php
						$recent_posts = wp_get_recent_posts(
							array(
								'numberposts' => 10,
								'post_status' => 'publish',
							)
						);
						foreach ( $recent_posts as $recent ) {
							printf(
								'<li><a href="%1$s">%2$s</a></li>',
								esc_url( get_permalink( $recent['ID'] ) ),
								esc_html( $recent['post_title'] )
							);
						}
						wp_reset_postdata();
						?>
					</ul>
				</div>
			</div>

		</div><!-- .page-content -->
	</section><!-- .error-404 -->

</main><!-- #main -->

<?php
get_footer();