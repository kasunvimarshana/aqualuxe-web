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

	<footer id="colophon" class="site-footer bg-dark-900 text-white py-12">
		<div class="container mx-auto px-4">
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<div class="footer-widget">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
				<?php endif; ?>
				
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<div class="footer-widget">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
				<?php endif; ?>
				
				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<div class="footer-widget">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
				<?php endif; ?>
				
				<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
					<div class="footer-widget">
						<?php dynamic_sidebar( 'footer-4' ); ?>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="border-t border-dark-700 mt-8 pt-8">
				<div class="flex flex-col md:flex-row justify-between items-center">
					<div class="mb-4 md:mb-0">
						<?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( '© %1$s %2$s. All rights reserved.', 'aqualuxe' ), date('Y'), get_bloginfo( 'name' ) );
						?>
					</div>
					
					<?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
						<nav class="footer-navigation">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer-menu',
									'menu_id'        => 'footer-menu',
									'container'      => false,
									'menu_class'     => 'flex flex-wrap justify-center space-x-6',
									'depth'          => 1,
									'fallback_cb'    => false,
								)
							);
							?>
						</nav>
					<?php endif; ?>
				</div>
				
				<?php if ( aqualuxe_is_woocommerce_active() && aqualuxe_is_multi_currency_active() ) : ?>
					<div class="mt-6 flex justify-center">
						<?php get_template_part( 'template-parts/footer/currency-switcher' ); ?>
					</div>
				<?php endif; ?>
				
				<?php if ( aqualuxe_display_payment_methods() ) : ?>
					<div class="mt-6 flex justify-center">
						<?php get_template_part( 'template-parts/footer/payment-methods' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>