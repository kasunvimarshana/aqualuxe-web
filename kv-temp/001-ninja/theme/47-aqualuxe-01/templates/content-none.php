<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-white p-8 rounded-lg shadow-sm">
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

			<p class="mb-6"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
			
			<div class="search-suggestions mb-8">
				<h2 class="text-lg font-medium mb-3"><?php esc_html_e( 'Search Tips:', 'aqualuxe' ); ?></h2>
				<ul class="list-disc pl-5 space-y-2">
					<li><?php esc_html_e( 'Check your spelling.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try more general keywords.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try different keywords that mean the same thing.', 'aqualuxe' ); ?></li>
					<li><?php esc_html_e( 'Try searching with short and simple keywords.', 'aqualuxe' ); ?></li>
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

		<?php if ( aqualuxe_is_woocommerce_active() && ( is_search() || is_archive() ) ) : ?>
			<div class="featured-products mt-12 pt-8 border-t border-gray-200">
				<h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
				<?php
				if ( function_exists( 'aqualuxe_featured_products' ) ) {
					aqualuxe_featured_products( 4 );
				} else {
					echo do_shortcode( '[products limit="4" columns="4" visibility="featured"]' );
				}
				?>
			</div>
		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->