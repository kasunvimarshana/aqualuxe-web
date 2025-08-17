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

	<main id="primary" class="site-main">
		<div class="container">
			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<div class="error-404-content">
						<div class="error-404-image">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/404-fish.svg' ); ?>" alt="<?php esc_attr_e( '404 Error', 'aqualuxe' ); ?>">
						</div>
						
						<div class="error-404-text">
							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>
							
							<?php get_search_form(); ?>
							
							<div class="error-404-links">
								<div class="error-404-widget">
									<h2><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
									<ul>
										<?php
										wp_get_archives(
											array(
												'type'      => 'postbypost',
												'limit'     => 5,
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
											'posts_per_page' => 5,
											'meta_key'       => 'total_sales',
											'orderby'        => 'meta_value_num',
										);
										$loop = new WP_Query( $args );
										if ( $loop->have_posts() ) {
											echo '<ul>';
											while ( $loop->have_posts() ) : $loop->the_post();
												echo '<li><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></li>';
											endwhile;
											echo '</ul>';
											wp_reset_postdata();
										}
										?>
									</div>
								<?php endif; ?>
								
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
												'number'     => 5,
											)
										);
										?>
									</ul>
								</div>
							</div>
							
							<div class="error-404-button">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?></a>
							</div>
						</div>
					</div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();