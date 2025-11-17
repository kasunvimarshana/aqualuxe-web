<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-white dark:bg-dark-800 rounded-lg shadow-sm p-8">
	<header class="page-header mb-6">
		<h1 class="page-title text-2xl font-serif font-bold text-dark-900 dark:text-white"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content prose dark:prose-dark">
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
			
			<div class="mt-6">
				<h2 class="text-xl font-serif font-bold text-dark-900 dark:text-white mb-4"><?php esc_html_e( 'Search Suggestions:', 'aqualuxe' ); ?></h2>
				<ul class="list-disc pl-5 space-y-2 text-dark-600 dark:text-dark-300">
					<li><?php esc_html_e( 'Check your spelling.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try more general keywords.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try different keywords that mean the same thing.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try searching by category or tag.', 'aqualuxe' ); ?></li>
				</ul>
			</div>

			<?php
			get_search_form();

		else :
			?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
			<?php
			get_search_form();

		endif;
		?>
	</div><!-- .page-content -->
</section><!-- .no-results -->