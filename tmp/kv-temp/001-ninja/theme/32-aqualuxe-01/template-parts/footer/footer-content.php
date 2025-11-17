<?php
/**
 * Template part for displaying the footer content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<footer id="colophon" class="site-footer bg-blue-900 text-white py-12">
	<div class="container mx-auto px-4">
		<div class="footer-widgets grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
			<?php
			if ( is_active_sidebar( 'footer-1' ) ) {
				?>
				<div class="footer-widget">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div>
				<?php
			}
			if ( is_active_sidebar( 'footer-2' ) ) {
				?>
				<div class="footer-widget">
					<?php dynamic_sidebar( 'footer-2' ); ?>
				</div>
				<?php
			}
			if ( is_active_sidebar( 'footer-3' ) ) {
				?>
				<div class="footer-widget">
					<?php dynamic_sidebar( 'footer-3' ); ?>
				</div>
				<?php
			}
			if ( is_active_sidebar( 'footer-4' ) ) {
				?>
				<div class="footer-widget">
					<?php dynamic_sidebar( 'footer-4' ); ?>
				</div>
				<?php
			}
			?>
		</div>

		<div class="site-info mt-8 pt-8 border-t border-blue-800 text-center">
			<div class="footer-menu mb-4">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_id'        => 'footer-menu',
						'container'      => false,
						'menu_class'     => 'footer-menu flex justify-center flex-wrap',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>
			<div class="copyright">
				<?php
				/* translators: %1$s: Theme name, %2$s: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'aqualuxe' ), 'AquaLuxe', '<a href="https://ninjatech.ai/">NinjaTech AI</a>' );
				?>
			</div>
			<div class="site-credits mt-2">
				<?php
				/* translators: %1$s: Copyright year, %2$s: Site name. */
				printf( esc_html__( '© %1$s %2$s. All Rights Reserved.', 'aqualuxe' ), date_i18n( 'Y' ), get_bloginfo( 'name' ) );
				?>
			</div>
		</div><!-- .site-info -->
	</div>
</footer><!-- #colophon -->