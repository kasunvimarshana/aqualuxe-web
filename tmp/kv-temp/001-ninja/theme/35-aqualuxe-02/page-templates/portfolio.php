<?php
/**
 * Template Name: Portfolio
 *
 * A template for displaying portfolio items in a grid layout.
 *
 * @package AquaLuxe
 */

get_header();

// Get portfolio settings
$columns = get_theme_mod( 'aqualuxe_portfolio_columns', 3 );
$items_per_page = get_theme_mod( 'aqualuxe_portfolio_items_per_page', 12 );
$layout = get_theme_mod( 'aqualuxe_portfolio_layout', 'grid' );
$show_filters = get_theme_mod( 'aqualuxe_portfolio_show_filters', true );

// Get portfolio categories
$portfolio_categories = get_terms( array(
	'taxonomy' => 'portfolio_category',
	'hide_empty' => true,
) );

// Get current page
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// Get portfolio items
$portfolio_args = array(
	'post_type' => 'portfolio',
	'posts_per_page' => $items_per_page,
	'paged' => $paged,
);

// Check if filtering by category
$current_category = isset( $_GET['portfolio_category'] ) ? sanitize_text_field( $_GET['portfolio_category'] ) : '';
if ( ! empty( $current_category ) ) {
	$portfolio_args['tax_query'] = array(
		array(
			'taxonomy' => 'portfolio_category',
			'field' => 'slug',
			'terms' => $current_category,
		),
	);
}

$portfolio_query = new WP_Query( $portfolio_args );
?>

<div class="aqualuxe-container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				// Display page content if any
				if ( '' !== get_the_content() ) :
					?>
					<div class="aqualuxe-portfolio-intro">
						<?php the_content(); ?>
					</div>
					<?php
				endif;

			endwhile; // End of the loop.
			?>

			<?php if ( $show_filters && ! empty( $portfolio_categories ) && ! is_wp_error( $portfolio_categories ) ) : ?>
				<div class="aqualuxe-portfolio-filters">
					<ul>
						<li>
							<a href="<?php echo esc_url( remove_query_arg( 'portfolio_category' ) ); ?>" class="<?php echo empty( $current_category ) ? 'active' : ''; ?>">
								<?php esc_html_e( 'All', 'aqualuxe' ); ?>
							</a>
						</li>
						<?php foreach ( $portfolio_categories as $category ) : ?>
							<li>
								<a href="<?php echo esc_url( add_query_arg( 'portfolio_category', $category->slug ) ); ?>" class="<?php echo $current_category === $category->slug ? 'active' : ''; ?>">
									<?php echo esc_html( $category->name ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $portfolio_query->have_posts() ) : ?>
				<div class="aqualuxe-portfolio-items aqualuxe-portfolio-<?php echo esc_attr( $layout ); ?> aqualuxe-portfolio-columns-<?php echo esc_attr( $columns ); ?>">
					<?php
					while ( $portfolio_query->have_posts() ) :
						$portfolio_query->the_post();

						// Get portfolio categories for this item
						$item_categories = get_the_terms( get_the_ID(), 'portfolio_category' );
						$category_classes = '';
						$category_names = array();

						if ( ! empty( $item_categories ) && ! is_wp_error( $item_categories ) ) {
							foreach ( $item_categories as $category ) {
								$category_classes .= ' portfolio-category-' . $category->slug;
								$category_names[] = $category->name;
							}
						}
						?>
						<div class="aqualuxe-portfolio-item<?php echo esc_attr( $category_classes ); ?>">
							<div class="aqualuxe-portfolio-item-inner">
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="aqualuxe-portfolio-item-image">
										<a href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail( 'aqualuxe-portfolio' ); ?>
										</a>
										<div class="aqualuxe-portfolio-item-overlay">
											<div class="aqualuxe-portfolio-item-actions">
												<a href="<?php the_permalink(); ?>" class="aqualuxe-portfolio-item-link">
													<span class="screen-reader-text"><?php esc_html_e( 'View Project', 'aqualuxe' ); ?></span>
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
												</a>
												<?php if ( has_post_thumbnail() ) : ?>
													<a href="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>" class="aqualuxe-portfolio-item-lightbox">
														<span class="screen-reader-text"><?php esc_html_e( 'View Image', 'aqualuxe' ); ?></span>
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
													</a>
												<?php endif; ?>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<div class="aqualuxe-portfolio-item-content">
									<h3 class="aqualuxe-portfolio-item-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
									<?php if ( ! empty( $category_names ) ) : ?>
										<div class="aqualuxe-portfolio-item-categories">
											<?php echo esc_html( implode( ', ', $category_names ) ); ?>
										</div>
									<?php endif; ?>
									<div class="aqualuxe-portfolio-item-excerpt">
										<?php the_excerpt(); ?>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>

				<?php
				// Pagination
				$total_pages = $portfolio_query->max_num_pages;
				if ( $total_pages > 1 ) :
					?>
					<div class="aqualuxe-pagination">
						<?php
						echo paginate_links( array(
							'base' => get_pagenum_link( 1 ) . '%_%',
							'format' => 'page/%#%',
							'current' => $paged,
							'total' => $total_pages,
							'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
							'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
						) );
						?>
					</div>
				<?php endif; ?>

			<?php else : ?>
				<div class="aqualuxe-portfolio-empty">
					<p><?php esc_html_e( 'No portfolio items found.', 'aqualuxe' ); ?></p>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .aqualuxe-container -->

<?php
get_footer();