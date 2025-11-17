<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found">
	<header class="page-header mb-8">
		<h1 class="page-title text-3xl font-serif font-bold"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content prose dark:prose-invert max-w-none">
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
			
			<div class="mt-8">
				<?php get_search_form(); ?>
			</div>
			
			<div class="mt-8">
				<h3><?php esc_html_e( 'Popular Searches', 'aqualuxe' ); ?></h3>
				<div class="flex flex-wrap gap-2 mt-4">
					<?php
					// Get popular search terms or predefined ones
					$popular_searches = aqualuxe_get_option( 'popular_searches', array( 'Tropical Fish', 'Aquarium', 'Plants', 'Filters', 'Food' ) );
					
					if ( ! empty( $popular_searches ) && is_array( $popular_searches ) ) :
						foreach ( $popular_searches as $search ) :
							if ( ! empty( $search ) ) :
								?>
								<a href="<?php echo esc_url( add_query_arg( 's', urlencode( $search ), home_url( '/' ) ) ); ?>" class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full text-sm transition-colors duration-200">
									<?php echo esc_html( $search ); ?>
								</a>
								<?php
							endif;
						endforeach;
					endif;
					?>
				</div>
			</div>

			<?php
		else :
			?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
			
			<div class="mt-8">
				<?php get_search_form(); ?>
			</div>
			
			<div class="mt-8">
				<h3><?php esc_html_e( 'Browse Categories', 'aqualuxe' ); ?></h3>
				<ul class="mt-4">
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
			</div>

			<?php
		endif;
		?>
	</div><!-- .page-content -->
</section><!-- .no-results -->