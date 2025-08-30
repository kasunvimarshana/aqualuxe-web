<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-white rounded-lg shadow-md overflow-hidden p-6 md:p-8">
	<header class="page-header mb-6">
		<h1 class="page-title text-2xl font-bold"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h1>
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
			
			<div class="search-form-container mt-6">
				<?php get_search_form(); ?>
			</div>

			<div class="search-suggestions mt-8">
				<h2 class="text-xl font-bold mb-4"><?php esc_html_e( 'Search Suggestions:', 'aqualuxe' ); ?></h2>
				<ul class="list-disc pl-5 space-y-2">
					<li><?php esc_html_e( 'Check your spelling.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try more general keywords.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try different keywords that mean the same thing.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try searching with short and simple keywords.', 'aqualuxe' ); ?></li>
				</ul>
			</div>

			<div class="popular-content mt-8">
				<h2 class="text-xl font-bold mb-4"><?php esc_html_e( 'Popular Content:', 'aqualuxe' ); ?></h2>
				
				<?php
				// Get popular posts
				$popular_posts = new WP_Query(
					array(
						'posts_per_page' => 4,
						'orderby'        => 'comment_count',
						'order'          => 'DESC',
					)
				);

				if ( $popular_posts->have_posts() ) :
					?>
					<ul class="popular-posts space-y-4">
						<?php
						while ( $popular_posts->have_posts() ) :
							$popular_posts->the_post();
							?>
							<li>
								<a href="<?php the_permalink(); ?>" class="text-primary hover:underline">
									<?php the_title(); ?>
								</a>
							</li>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</ul>
					<?php
				endif;
				?>
			</div>

			<?php
		else :
			?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
			
			<div class="search-form-container mt-6">
				<?php get_search_form(); ?>
			</div>

			<?php
		endif;
		?>
	</div><!-- .page-content -->
</section><!-- .no-results -->