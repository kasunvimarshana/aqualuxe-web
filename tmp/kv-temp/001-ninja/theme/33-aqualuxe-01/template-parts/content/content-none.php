<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-white dark:bg-dark-800 rounded-lg overflow-hidden shadow-sm border border-gray-100 dark:border-dark-700 p-6 sm:p-8">
	<header class="page-header mb-6">
		<h1 class="page-title text-2xl sm:text-3xl font-serif font-bold text-dark-800 dark:text-white">
			<?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?>
		</h1>
	</header><!-- .page-header -->

	<div class="page-content prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-200">
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

			<div class="flex flex-col items-center text-center py-6">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
				</svg>
				
				<p class="mb-6 text-lg"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
				
				<div class="search-form-container max-w-lg w-full">
					<?php get_search_form(); ?>
				</div>
				
				<div class="search-suggestions mt-8">
					<h3 class="text-lg font-medium mb-3"><?php esc_html_e( 'Search Suggestions:', 'aqualuxe' ); ?></h3>
					<ul class="list-disc list-inside space-y-1 text-left">
						<li><?php esc_html_e( 'Check your spelling.', 'aqualuxe' ); ?></li>
						<li><?php esc_html_e( 'Try more general keywords.', 'aqualuxe' ); ?></li>
						<li><?php esc_html_e( 'Try different keywords.', 'aqualuxe' ); ?></li>
						<li><?php esc_html_e( 'Try fewer keywords.', 'aqualuxe' ); ?></li>
					</ul>
				</div>
			</div>

			<?php
		else :
			?>

			<div class="flex flex-col items-center text-center py-6">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
				</svg>
				
				<p class="mb-6 text-lg"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
				
				<div class="search-form-container max-w-lg w-full">
					<?php get_search_form(); ?>
				</div>
				
				<div class="mt-8">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-md transition-colors duration-200 inline-flex items-center">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
						</svg>
						<?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
					</a>
				</div>
			</div>

		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->