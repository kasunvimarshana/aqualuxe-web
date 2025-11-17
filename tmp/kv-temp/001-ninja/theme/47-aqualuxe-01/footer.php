<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>
			<?php do_action( 'aqualuxe_after_main_content' ); ?>
		</div><!-- .container -->
	</div><!-- #content -->

	<?php do_action( 'aqualuxe_before_footer' ); ?>

	<footer id="colophon" class="site-footer bg-primary-900 text-white pt-12 pb-6">
		<div class="container mx-auto px-4">
			<div class="footer-widgets grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<div class="footer-widget-1">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<div class="footer-widget-2">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<div class="footer-widget-3">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
					<div class="footer-widget-4">
						<?php dynamic_sidebar( 'footer-4' ); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<div class="footer-navigation mb-6">
					<nav class="footer-nav">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'menu_id'        => 'footer-menu',
								'container'      => false,
								'menu_class'     => 'flex flex-wrap justify-center space-x-6',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				</div>
			<?php endif; ?>

			<?php if ( aqualuxe_get_theme_option( 'footer_payment_icons' ) ) : ?>
				<div class="payment-methods flex justify-center mb-6">
					<?php aqualuxe_payment_icons(); ?>
				</div>
			<?php endif; ?>

			<?php if ( aqualuxe_get_theme_option( 'footer_social_icons' ) ) : ?>
				<div class="social-icons flex justify-center mb-6">
					<?php aqualuxe_social_icons(); ?>
				</div>
			<?php endif; ?>

			<div class="site-info text-center text-sm opacity-80">
				<?php
				$copyright_text = aqualuxe_get_theme_option( 'footer_copyright' );
				if ( $copyright_text ) {
					echo wp_kses_post( str_replace( '{year}', date( 'Y' ), $copyright_text ) );
				} else {
					printf(
						/* translators: %1$s: Theme name, %2$s: Theme author website */
						esc_html__( '© %1$s %2$s. All rights reserved. Powered by %3$s', 'aqualuxe' ),
						date( 'Y' ),
						'<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>',
						'<a href="https://wordpress.org/">WordPress</a>'
					);
				}
				?>
			</div><!-- .site-info -->

			<?php if ( aqualuxe_get_theme_option( 'back_to_top' ) ) : ?>
				<div class="back-to-top text-center mt-4">
					<button id="back-to-top-btn" class="back-to-top-btn inline-flex items-center justify-center p-2 rounded-full bg-primary-700 hover:bg-primary-600 transition-colors">
						<?php aqualuxe_svg_icon( 'arrow-up', array( 'class' => 'w-5 h-5' ) ); ?>
						<span class="sr-only"><?php esc_html_e( 'Back to top', 'aqualuxe' ); ?></span>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</footer><!-- #colophon -->

	<?php do_action( 'aqualuxe_after_footer' ); ?>
</div><!-- #page -->

<?php if ( aqualuxe_is_woocommerce_active() && function_exists( 'aqualuxe_quick_view_modal' ) ) : ?>
	<?php aqualuxe_quick_view_modal(); ?>
<?php endif; ?>

<?php if ( aqualuxe_is_woocommerce_active() && function_exists( 'aqualuxe_cart_drawer' ) ) : ?>
	<?php aqualuxe_cart_drawer(); ?>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>