<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden p-6 mb-8">
	<header class="page-header mb-6">
		<h1 class="page-title text-2xl font-serif font-bold text-dark-600 dark:text-light-500"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content text-gray-600 dark:text-gray-400">
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
				<?php get_search_form(); ?>
			</div>

			<?php
		else :
			?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
			<div class="mt-6">
				<?php get_search_form(); ?>
			</div>

			<?php
		endif;
		?>
	</div><!-- .page-content -->
</section><!-- .no-results -->