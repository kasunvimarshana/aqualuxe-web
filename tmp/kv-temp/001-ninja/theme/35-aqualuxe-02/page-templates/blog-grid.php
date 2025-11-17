<?php
/**
 * Template Name: Blog Grid
 *
 * A template for displaying blog posts in a grid layout.
 *
 * @package AquaLuxe
 */

get_header();

// Get blog settings
$columns = get_theme_mod( 'aqualuxe_blog_grid_columns', 3 );
$posts_per_page = get_theme_mod( 'aqualuxe_blog_grid_posts_per_page', 12 );
$show_excerpt = get_theme_mod( 'aqualuxe_blog_grid_show_excerpt', true );
$excerpt_length = get_theme_mod( 'aqualuxe_blog_grid_excerpt_length', 20 );
$show_meta = get_theme_mod( 'aqualuxe_blog_grid_show_meta', true );
$show_featured_image = get_theme_mod( 'aqualuxe_blog_grid_show_featured_image', true );
$show_categories = get_theme_mod( 'aqualuxe_blog_grid_show_categories', true );
$show_author = get_theme_mod( 'aqualuxe_blog_grid_show_author', true );
$show_date = get_theme_mod( 'aqualuxe_blog_grid_show_date', true );
$show_comments = get_theme_mod( 'aqualuxe_blog_grid_show_comments', true );

// Get current page
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// Get blog posts
$blog_args = array(
	'post_type' => 'post',
	'posts_per_page' => $posts_per_page,
	'paged' => $paged,
);

// Check if filtering by category
$current_category = isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '';
if ( ! empty( $current_category ) ) {
	$blog_args['category_name'] = $current_category;
}

$blog_query = new WP_Query( $blog_args );

// Get categories for filtering
$categories = get_categories( array(
	'orderby' => 'name',
	'order' => 'ASC',
	'hide_empty' => true,
) );
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
					<div class="aqualuxe-blog-intro">
						<?php the_content(); ?>
					</div>
					<?php
				endif;

			endwhile; // End of the loop.
			?>

			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
				<div class="aqualuxe-blog-filters">
					<ul>
						<li>
							<a href="<?php echo esc_url( remove_query_arg( 'category' ) ); ?>" class="<?php echo empty( $current_category ) ? 'active' : ''; ?>">
								<?php esc_html_e( 'All', 'aqualuxe' ); ?>
							</a>
						</li>
						<?php foreach ( $categories as $category ) : ?>
							<li>
								<a href="<?php echo esc_url( add_query_arg( 'category', $category->slug ) ); ?>" class="<?php echo $current_category === $category->slug ? 'active' : ''; ?>">
									<?php echo esc_html( $category->name ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $blog_query->have_posts() ) : ?>
				<div class="aqualuxe-blog-grid aqualuxe-blog-columns-<?php echo esc_attr( $columns ); ?>">
					<?php
					while ( $blog_query->have_posts() ) :
						$blog_query->the_post();

						// Get post categories
						$categories = get_the_category();
						$category_classes = '';
						$category_names = array();

						if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
							foreach ( $categories as $category ) {
								$category_classes .= ' category-' . $category->slug;
								$category_names[] = $category->name;
							}
						}
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'aqualuxe-blog-item' . $category_classes ); ?>>
							<div class="aqualuxe-blog-item-inner">
								<?php if ( $show_featured_image && has_post_thumbnail() ) : ?>
									<div class="aqualuxe-blog-item-image">
										<a href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail( 'aqualuxe-blog-grid' ); ?>
										</a>
									</div>
								<?php endif; ?>
								<div class="aqualuxe-blog-item-content">
									<?php if ( $show_categories && ! empty( $category_names ) ) : ?>
										<div class="aqualuxe-blog-item-categories">
											<?php the_category( ', ' ); ?>
										</div>
									<?php endif; ?>
									<h2 class="aqualuxe-blog-item-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h2>
									<?php if ( $show_meta ) : ?>
										<div class="aqualuxe-blog-item-meta">
											<?php if ( $show_author ) : ?>
												<span class="aqualuxe-blog-item-author">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
													<?php the_author_posts_link(); ?>
												</span>
											<?php endif; ?>
											<?php if ( $show_date ) : ?>
												<span class="aqualuxe-blog-item-date">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
													<?php echo get_the_date(); ?>
												</span>
											<?php endif; ?>
											<?php if ( $show_comments && comments_open() ) : ?>
												<span class="aqualuxe-blog-item-comments">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
													<a href="<?php comments_link(); ?>"><?php comments_number( '0', '1', '%' ); ?></a>
												</span>
											<?php endif; ?>
										</div>
									<?php endif; ?>
									<?php if ( $show_excerpt ) : ?>
										<div class="aqualuxe-blog-item-excerpt">
											<?php
											if ( has_excerpt() ) {
												the_excerpt();
											} else {
												echo wp_trim_words( get_the_content(), $excerpt_length, '...' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}
											?>
										</div>
									<?php endif; ?>
									<div class="aqualuxe-blog-item-more">
										<a href="<?php the_permalink(); ?>" class="aqualuxe-read-more">
											<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
										</a>
									</div>
								</div>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<?php
				// Pagination
				$total_pages = $blog_query->max_num_pages;
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
				<div class="aqualuxe-blog-empty">
					<p><?php esc_html_e( 'No posts found.', 'aqualuxe' ); ?></p>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .aqualuxe-container -->

<?php
get_footer();