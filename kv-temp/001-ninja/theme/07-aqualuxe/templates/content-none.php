<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-white dark:bg-dark-800 rounded-lg shadow-soft p-8 text-center">
	<header class="page-header mb-6">
		<h1 class="page-title text-2xl font-serif font-bold text-gray-900 dark:text-gray-100">
			<?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?>
		</h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :

			printf(
				'<p class="text-gray-700 dark:text-gray-300 mb-6">' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe' ),
					array(
						'a' => array(
							'href' => array(),
							'class' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);

		elseif ( is_search() ) :
			?>

			<p class="text-gray-700 dark:text-gray-300 mb-6">
				<?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?>
			</p>
			
			<div class="search-form-container max-w-lg mx-auto mb-8">
				<?php get_search_form(); ?>
			</div>

			<div class="search-suggestions">
				<h2 class="text-xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-4">
					<?php esc_html_e( 'Search Suggestions:', 'aqualuxe' ); ?>
				</h2>
				<ul class="list-disc list-inside text-gray-700 dark:text-gray-300 text-left max-w-md mx-auto">
					<li><?php esc_html_e( 'Check your spelling.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try more general keywords.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try different keywords that mean the same thing.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try searching with short and simple keywords.', 'aqualuxe' ); ?></li>
				</ul>
			</div>

		<?php else : ?>

			<p class="text-gray-700 dark:text-gray-300 mb-6">
				<?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?>
			</p>
			
			<div class="search-form-container max-w-lg mx-auto">
				<?php get_search_form(); ?>
			</div>

		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->