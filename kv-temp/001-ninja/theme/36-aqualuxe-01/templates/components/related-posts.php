<?php
/**
 * Template part for displaying related posts
 *
 * @package AquaLuxe
 */

// Get current post ID
$post_id = get_the_ID();

// Get current post categories
$categories = get_the_category( $post_id );

if ( $categories ) {
	$category_ids = array();
	foreach ( $categories as $category ) {
		$category_ids[] = $category->term_id;
	}
	
	// Get related posts based on category
	$related_args = array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'post__not_in'   => array( $post_id ),
		'category__in'   => $category_ids,
		'orderby'        => 'rand',
	);
	
	$related_query = new WP_Query( $related_args );
	
	if ( $related_query->have_posts() ) :
		?>
		<div class="related-posts mt-12 pt-8 border-t border-gray-200">
			<h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
			
			<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
				<?php
				while ( $related_query->have_posts() ) :
					$related_query->the_post();
					?>
					<article class="related-post bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="block">
								<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
							</a>
						<?php endif; ?>
						
						<div class="p-4">
							<h4 class="text-lg font-bold mb-2">
								<a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-primary transition-colors">
									<?php the_title(); ?>
								</a>
							</h4>
							
							<div class="entry-meta text-sm text-gray-600 mb-2">
								<span class="post-date">
									<?php echo get_the_date(); ?>
								</span>
							</div>
							
							<div class="entry-excerpt text-sm mb-4">
								<?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
							</div>
							
							<a href="<?php the_permalink(); ?>" class="read-more text-primary hover:underline text-sm font-medium">
								<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
								<span aria-hidden="true">&rarr;</span>
							</a>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php
	endif;
}