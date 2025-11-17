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
				<div class="error-404-content">
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
											'limit' => 5,
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
											'number'     => 5,
										)
									);
									?>
								</ul>
							</div>

							<?php if ( class_exists( 'WooCommerce' ) ) : ?>
							<div class="error-404-widget">
								<h2><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
								<?php
								$args = array(
									'posts_per_page' => 4,
									'columns'        => 2,
									'orderby'        => 'rand',
									'order'          => 'desc',
								);
								
								$shortcode = new WC_Shortcode_Products( $args, 'featured_products' );
								echo $shortcode->get_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>
							<?php endif; ?>
						</div>
					</div><!-- .page-content -->
				</div><!-- .error-404-content -->
			</section><!-- .error-404 -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();