<?php
/**
 * Template part for displaying blog posts on the homepage
 *
 * @package AquaLuxe
 */

?>

<section class="blog-posts py-16 bg-gray-50 dark:bg-dark-850">
	<div class="container mx-auto px-4">
		<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
			<div>
				<h2 class="text-3xl md:text-4xl font-serif font-medium mb-2"><?php esc_html_e( 'Latest Articles', 'aqualuxe' ); ?></h2>
				<p class="text-lg text-dark-600 dark:text-dark-300"><?php esc_html_e( 'Expert tips and insights from our aquatic specialists', 'aqualuxe' ); ?></p>
			</div>
			<div class="mt-4 md:mt-0">
				<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-outline">
					<?php esc_html_e( 'View All Articles', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
		</div>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php
			$args = array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'orderby'        => 'date',
				'order'          => 'DESC',
			);
			$recent_posts = new WP_Query( $args );
			
			if ( $recent_posts->have_posts() ) :
				while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
					?>
					<article class="post-card bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden transition-all duration-300 hover:shadow-medium">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="block overflow-hidden">
								<img src="<?php the_post_thumbnail_url( 'medium_large' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-48 object-cover transition-transform duration-500 hover:scale-110">
							</a>
						<?php endif; ?>
						
						<div class="p-6">
							<div class="post-meta flex items-center text-sm text-dark-500 dark:text-dark-300 mb-3">
								<span class="post-date flex items-center">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
									</svg>
									<?php echo get_the_date(); ?>
								</span>
								
								<?php if ( has_category() ) : ?>
									<span class="post-categories flex items-center ml-4">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
										</svg>
										<?php
										$categories = get_the_category();
										if ( ! empty( $categories ) ) {
											echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">' . esc_html( $categories[0]->name ) . '</a>';
										}
										?>
									</span>
								<?php endif; ?>
							</div>
							
							<h3 class="post-title text-xl font-serif font-medium mb-3">
								<a href="<?php the_permalink(); ?>" class="text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
									<?php the_title(); ?>
								</a>
							</h3>
							
							<div class="post-excerpt text-dark-600 dark:text-dark-300 mb-4">
								<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
							</div>
							
							<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
								<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
								</svg>
							</a>
						</div>
					</article>
					<?php
				endwhile;
				
				wp_reset_postdata();
			else :
				?>
				<div class="col-span-3 text-center py-8">
					<p><?php esc_html_e( 'No posts found.', 'aqualuxe' ); ?></p>
				</div>
				<?php
			endif;
			?>
		</div>
	</div>
</section>