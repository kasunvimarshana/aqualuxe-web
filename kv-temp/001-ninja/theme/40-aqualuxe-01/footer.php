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

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php get_template_part( 'template-parts/footer/footer', get_theme_mod( 'aqualuxe_footer_layout', '4-columns' ) ); ?>

		<div class="site-info">
			<div class="container">
				<div class="copyright">
					<?php echo wp_kses_post( get_theme_mod( 'aqualuxe_footer_copyright', '© ' . date( 'Y' ) . ' AquaLuxe. All rights reserved.' ) ); ?>
				</div><!-- .copyright -->

				<?php if ( has_nav_menu( 'legal' ) ) : ?>
					<div class="legal-links">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'legal',
								'menu_id'        => 'legal-menu',
								'depth'          => 1,
								'container'      => false,
							)
						);
						?>
					</div><!-- .legal-links -->
				<?php endif; ?>

				<?php if ( get_theme_mod( 'aqualuxe_footer_payment_icons', true ) ) : ?>
					<div class="payment-icons">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/payment-icons.png' ); ?>" alt="<?php esc_attr_e( 'Payment Methods', 'aqualuxe' ); ?>">
					</div><!-- .payment-icons -->
				<?php endif; ?>
			</div><!-- .container -->
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
<?php