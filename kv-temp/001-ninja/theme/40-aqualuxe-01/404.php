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
			<div class="row">
				<div class="col-full">

					<section class="error-404 not-found">
						<header class="page-header">
							<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
						</header><!-- .page-header -->

						<div class="page-content">
							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

							<?php
							get_search_form();

							the_widget( 'WP_Widget_Recent_Posts' );
							?>

							<div class="widget widget_categories">
								<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
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
							</div><!-- .widget -->

							<?php
							/* translators: %1$s: smiley */
							$aqualuxe_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'aqualuxe' ), convert_smilies( ':)' ) ) . '</p>';
							the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$aqualuxe_archive_content" );

							the_widget( 'WP_Widget_Tag_Cloud' );
							?>

							<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
								<div class="widget woocommerce widget_product_categories">
									<h2 class="widget-title"><?php esc_html_e( 'Product Categories', 'aqualuxe' ); ?></h2>
									<ul class="product-categories">
										<?php
										wp_list_categories(
											array(
												'taxonomy'   => 'product_cat',
												'orderby'    => 'name',
												'show_count' => 1,
												'title_li'   => '',
												'number'     => 10,
											)
										);
										?>
									</ul>
								</div><!-- .widget -->

								<div class="widget woocommerce">
									<h2 class="widget-title"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
									<?php
									$args = array(
										'posts_per_page' => 4,
										'columns'        => 4,
										'orderby'        => 'rand',
										'order'          => 'desc',
									);
									woocommerce_featured_products( $args );
									?>
								</div><!-- .widget -->
							<?php endif; ?>

						</div><!-- .page-content -->
					</section><!-- .error-404 -->

				</div><!-- .col-full -->
			</div><!-- .row -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();