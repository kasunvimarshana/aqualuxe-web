<?php
/**
 * Template part for displaying related posts
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get current post categories
$categories = get_the_category();

if ( $categories ) {
	$category_ids = array();
	foreach ( $categories as $category ) {
		$category_ids[] = $category->term_id;
	}

	// Query related posts
	$related_posts_count = get_theme_mod( 'aqualuxe_related_posts_count', 3 );
	$related_posts_args = array(
		'category__in'        => $category_ids,
		'post__not_in'        => array( get_the_ID() ),
		'posts_per_page'      => $related_posts_count,
		'ignore_sticky_posts' => 1,
		'orderby'             => 'rand',
	);

	$related_posts_query = new WP_Query( $related_posts_args );

	if ( $related_posts_query->have_posts() ) :
		?>
		<div class="related-posts mt-12">
			<h3 class="related-posts-title text-2xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-6">
				<?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?>
			</h3>

			<div class="related-posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php
				while ( $related_posts_query->have_posts() ) :
					$related_posts_query->the_post();
					?>
					<article class="related-post bg-white dark:bg-dark-800 rounded-lg shadow-soft overflow-hidden transition-shadow duration-300 hover:shadow-medium">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="block aspect-w-16 aspect-h-9 overflow-hidden">
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover transition-transform duration-300 hover:scale-105' ) ); ?>
							</a>
						<?php endif; ?>

						<div class="p-4">
							<h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
								<a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
									<?php the_title(); ?>
								</a>
							</h4>

							<div class="entry-meta flex flex-wrap items-center text-xs text-gray-600 dark:text-gray-400 mb-3">
								<?php aqualuxe_posted_on(); ?>
							</div>

							<div class="entry-excerpt text-sm text-gray-700 dark:text-gray-300 mb-3">
								<?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
							</div>

							<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-300">
								<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
								</svg>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		</div>
		<?php
		wp_reset_postdata();
	endif;
}