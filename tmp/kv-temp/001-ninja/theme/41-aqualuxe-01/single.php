<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main container mx-auto px-4 py-12">

		<?php
		while ( have_posts() ) :
			the_post();

			// Get the post type
			$post_type = get_post_type();

			// Check if it's a custom post type with a specific template
			if ( $post_type !== 'post' && locate_template( 'template-parts/content/content-single-' . $post_type . '.php' ) ) {
				get_template_part( 'template-parts/content/content-single', $post_type );
			} else {
				// Default post layout
				?>
				<div class="max-w-4xl mx-auto">
					<header class="entry-header mb-8">
						<?php
						if ( function_exists( 'yoast_breadcrumb' ) ) {
							yoast_breadcrumb( '<div class="breadcrumbs text-sm text-dark-500 dark:text-dark-400 mb-4">', '</div>' );
						}
						?>
						
						<?php the_title( '<h1 class="entry-title text-4xl font-serif font-bold text-dark-900 dark:text-white mb-4">', '</h1>' ); ?>
						
						<div class="entry-meta flex flex-wrap items-center text-sm text-dark-500 dark:text-dark-400 mb-4">
							<?php
							aqualuxe_posted_by();
							aqualuxe_posted_on();
							?>
						</div><!-- .entry-meta -->
						
						<?php
						// Display categories
						$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
						if ( $categories_list ) {
							echo '<div class="entry-categories mb-4">';
							echo '<span class="sr-only">' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span>';
							echo '<div class="flex flex-wrap gap-2">';
							
							$categories = get_the_category();
							foreach ( $categories as $category ) {
								echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="inline-block bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 text-xs px-3 py-1 rounded-full">' . esc_html( $category->name ) . '</a>';
							}
							
							echo '</div>';
							echo '</div>';
						}
						?>
					</header><!-- .entry-header -->

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="featured-image mb-8">
							<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto rounded-lg shadow-lg' ) ); ?>
							<?php if ( $caption = get_the_post_thumbnail_caption() ) : ?>
								<div class="text-sm text-center text-dark-500 dark:text-dark-400 mt-2 italic"><?php echo esc_html( $caption ); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="entry-content prose prose-lg dark:prose-dark max-w-none">
						<?php
						the_content(
							sprintf(
								wp_kses(
									/* translators: %s: Name of current post. Only visible to screen readers */
									__( 'Continue reading<span class="sr-only"> "%s"</span>', 'aqualuxe' ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								),
								wp_kses_post( get_the_title() )
							)
						);

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
								'after'  => '</div>',
							)
						);
						?>
					</div><!-- .entry-content -->

					<footer class="entry-footer mt-8 pt-8 border-t border-gray-200 dark:border-dark-700">
						<?php
						// Display tags
						$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
						if ( $tags_list ) {
							echo '<div class="entry-tags mb-6">';
							echo '<span class="font-medium text-dark-900 dark:text-white mr-2">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span>';
							echo '<span class="text-dark-600 dark:text-dark-300">' . $tags_list . '</span>';
							echo '</div>';
						}
						?>
						
						<div class="post-navigation mt-8">
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<div class="prev-post">
									<?php
									$prev_post = get_previous_post();
									if ( ! empty( $prev_post ) ) :
										?>
										<span class="block text-sm text-dark-500 dark:text-dark-400 mb-1"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></span>
										<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="text-lg font-medium text-primary-600 dark:text-primary-400 hover:underline">
											<?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
										</a>
									<?php endif; ?>
								</div>
								
								<div class="next-post text-right">
									<?php
									$next_post = get_next_post();
									if ( ! empty( $next_post ) ) :
										?>
										<span class="block text-sm text-dark-500 dark:text-dark-400 mb-1"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></span>
										<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="text-lg font-medium text-primary-600 dark:text-primary-400 hover:underline">
											<?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
						
						<?php
						// Author bio
						if ( get_the_author_meta( 'description' ) ) :
							get_template_part( 'template-parts/content/author-bio' );
						endif;
						?>
					</footer><!-- .entry-footer -->
				</div>
				<?php
			}

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				?>
				<div class="max-w-4xl mx-auto mt-12">
					<?php comments_template(); ?>
				</div>
				<?php
			endif;

			// Related posts
			get_template_part( 'template-parts/content/related-posts' );

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();