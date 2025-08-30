<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-gray-50 p-8 rounded-lg">
	<header class="page-header mb-6">
		<h1 class="page-title text-2xl font-bold text-gray-800"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content prose max-w-none">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :

			printf(
				'<p>' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);

		elseif ( is_search() ) :
			?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
			
			<div class="search-suggestions mt-8">
				<h3 class="text-xl font-bold text-gray-800 mb-4"><?php esc_html_e( 'Search Suggestions:', 'aqualuxe' ); ?></h3>
				<ul class="list-disc pl-6 space-y-2 text-gray-700">
					<li><?php esc_html_e( 'Check your spelling.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try more general keywords.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try different keywords that mean the same thing.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try searching with short and simple keywords.', 'aqualuxe' ); ?></li>
				</ul>
			</div>
			
			<div class="mt-8">
				<?php get_search_form(); ?>
			</div>

			<?php
		elseif ( is_archive() ) :
			?>

			<p><?php esc_html_e( 'It seems we can\'t find any content in this archive. Perhaps searching can help find what you\'re looking for.', 'aqualuxe' ); ?></p>
			
			<div class="mt-8">
				<?php get_search_form(); ?>
			</div>
			
			<div class="mt-8">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
					<i class="fas fa-home mr-2"></i> <?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
				</a>
			</div>

			<?php
		else :
			?>

			<p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
			
			<div class="mt-8">
				<?php get_search_form(); ?>
			</div>
			
			<div class="mt-8">
				<h3 class="text-xl font-bold text-gray-800 mb-4"><?php esc_html_e( 'Popular Pages', 'aqualuxe' ); ?></h3>
				<ul class="space-y-3">
					<?php
					// Get most viewed pages
					$popular_pages = new WP_Query(
						array(
							'post_type'      => 'page',
							'posts_per_page' => 5,
							'orderby'        => 'comment_count',
							'order'          => 'DESC',
						)
					);

					if ( $popular_pages->have_posts() ) :
						while ( $popular_pages->have_posts() ) :
							$popular_pages->the_post();
							?>
							<li>
								<a href="<?php the_permalink(); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors">
									<i class="fas fa-angle-right mr-2"></i>
									<?php the_title(); ?>
								</a>
							</li>
							<?php
						endwhile;
						wp_reset_postdata();
					else :
						?>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
						<?php
					endif;
					?>
				</ul>
			</div>

		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->