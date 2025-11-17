<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

				<?php get_search_form(); ?>

				<div class="error-404-widgets">
					<div class="error-404-widget">
						<h2><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
						<ul>
							<?php
							wp_get_archives(
								array(
									'type'  => 'postbypost',
									'limit' => 10,
								)
							);
							?>
						</ul>
					</div>

					<div class="error-404-widget">
						<h2><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
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

					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<div class="error-404-widget">
							<h2><?php esc_html_e( 'Popular Products', 'aqualuxe' ); ?></h2>
							<?php
							$args = array(
								'post_type'      => 'product',
								'posts_per_page' => 4,
								'meta_key'       => 'total_sales',
								'orderby'        => 'meta_value_num',
							);
							$loop = new WP_Query( $args );
							if ( $loop->have_posts() ) {
								echo '<ul>';
								while ( $loop->have_posts() ) {
									$loop->the_post();
									echo '<li><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></li>';
								}
								echo '</ul>';
								wp_reset_postdata();
							}
							?>
						</div>
					<?php endif; ?>
				</div>

				<div class="back-to-home">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button"><?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?></a>
				</div>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();