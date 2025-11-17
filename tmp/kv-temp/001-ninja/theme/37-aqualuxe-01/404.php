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

	<main id="primary" class="site-main" role="main">

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Page Not Found', 'aqualuxe' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php esc_html_e( 'The page you are looking for does not exist or has been moved.', 'aqualuxe' ); ?></p>

				<div class="error-404-actions">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Return to Home', 'aqualuxe' ); ?></a>
				</div>

				<div class="error-404-search">
					<h2><?php esc_html_e( 'Search Our Site', 'aqualuxe' ); ?></h2>
					<?php get_search_form(); ?>
				</div>

				<div class="error-404-widgets">
					<div class="error-404-widget">
						<h2><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
						<ul>
							<?php
							$recent_posts = wp_get_recent_posts(
								array(
									'numberposts' => 5,
									'post_status' => 'publish',
								)
							);
							foreach ( $recent_posts as $post ) {
								echo '<li><a href="' . esc_url( get_permalink( $post['ID'] ) ) . '">' . esc_html( $post['post_title'] ) . '</a></li>';
							}
							wp_reset_postdata();
							?>
						</ul>
					</div>

					<div class="error-404-widget">
						<h2><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h2>
						<ul>
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
				</div>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #primary -->

<?php
get_footer();