<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
	<header class="page-header mb-6">
		<h1 class="page-title text-2xl font-bold text-gray-900 dark:text-gray-100">
			<?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?>
		</h1>
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

			<p class="mb-6"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
			
			<div class="search-form-container">
				<?php get_search_form(); ?>
			</div>
			
			<div class="mt-8">
				<h2 class="text-xl font-medium mb-4"><?php esc_html_e( 'Search Tips:', 'aqualuxe' ); ?></h2>
				<ul class="list-disc pl-5 space-y-2">
					<li><?php esc_html_e( 'Check your spelling.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try more general keywords.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try different keywords that mean the same thing.', 'aqualuxe' ); ?></li>
				</ul>
			</div>

		<?php else : ?>

			<p class="mb-6"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
		
		<div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
			<h3 class="text-lg font-medium mb-4"><?php esc_html_e( 'Browse Popular Categories', 'aqualuxe' ); ?></h3>
			<div class="flex flex-wrap gap-2">
				<?php
				$categories = get_categories( array(
					'orderby' => 'count',
					'order'   => 'DESC',
					'number'  => 6,
				) );
				
				foreach ( $categories as $category ) {
					printf(
						'<a href="%1$s" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-full text-sm transition-colors">%2$s</a>',
						esc_url( get_category_link( $category->term_id ) ),
						esc_html( $category->name )
					);
				}
				?>
			</div>
		</div>
	</div><!-- .page-content -->
</section><!-- .no-results -->