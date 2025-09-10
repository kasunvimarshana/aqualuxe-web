<?php
/**
 * The footer for our theme
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>
		</div><!-- .container -->
	</div><!-- #content -->
	<footer id="colophon" class="site-footer bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 mt-auto">
		<div class="container mx-auto px-4 py-8">
			<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
				<div>
					<h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4"><?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?></h3>
					<p><?php bloginfo( 'description' ); ?></p>
				</div>
				<div>
					<h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'container'      => false,
							'depth'          => 1,
						)
					);
					?>
				</div>
				<div>
					<h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4"><?php esc_html_e( 'Connect With Us', 'aqualuxe' ); ?></h3>
					<?php // Add social media links here ?>
					<p><?php esc_html_e( 'Follow us on social media for the latest updates.', 'aqualuxe' ); ?></p>
				</div>
			</div>
			<div class="site-info border-t border-gray-200 dark:border-gray-700 mt-8 pt-4 text-center text-sm">
				<p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All Rights Reserved.', 'aqualuxe' ); ?></p>
				<p class="mt-2">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'aqualuxe' ) ); ?>">
						<?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( 'Proudly powered by %s', 'aqualuxe' ), 'WordPress' );
						?>
					</a>
					<span class="sep"> | </span>
					<?php
					/* translators: 1: Theme name, 2: Theme author. */
					printf( esc_html__( 'Theme: %1$s by %2$s.', 'aqualuxe' ), 'AquaLuxe', '<a href="https://kasunvimarshana.github.io/" class="hover:underline">Kasun Vimarshana</a>' );
					?>
				</p>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
