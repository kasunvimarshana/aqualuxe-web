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

	<div class="site-content-wrap sidebar-none">
		<main id="primary" class="content-area-full">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<div class="error-404-content">
						<div class="error-404-image">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/404.svg' ); ?>" alt="<?php esc_attr_e( '404 Error', 'aqualuxe' ); ?>">
						</div>

						<div class="error-404-text">
							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

							<?php get_search_form(); ?>

							<div class="error-404-actions">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?></a>
								
								<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
									<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Browse Shop', 'aqualuxe' ); ?></a>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<div class="error-404-widgets">
						<div class="error-404-widget">
							<h2><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
							<ul>
								<?php
								$recent_posts = wp_get_recent_posts( array(
									'numberposts' => 5,
									'post_status' => 'publish',
								) );
								
								foreach ( $recent_posts as $post ) {
									echo '<li><a href="' . esc_url( get_permalink( $post['ID'] ) ) . '">' . esc_html( $post['post_title'] ) . '</a></li>';
								}
								
								wp_reset_postdata();
								?>
							</ul>
						</div>

						<div class="error-404-widget">
							<h2><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
							<ul>
								<?php
								wp_list_categories( array(
									'orderby'    => 'count',
									'order'      => 'DESC',
									'show_count' => 1,
									'title_li'   => '',
									'number'     => 5,
								) );
								?>
							</ul>
						</div>

						<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
							<div class="error-404-widget">
								<h2><?php esc_html_e( 'Popular Products', 'aqualuxe' ); ?></h2>
								<ul>
									<?php
									$popular_products = new WP_Query( array(
										'post_type'      => 'product',
										'posts_per_page' => 5,
										'meta_key'       => 'total_sales',
										'orderby'        => 'meta_value_num',
										'order'          => 'DESC',
									) );
									
									while ( $popular_products->have_posts() ) {
										$popular_products->the_post();
										echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
									}
									
									wp_reset_postdata();
									?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- .site-content-wrap -->

<?php
get_footer();