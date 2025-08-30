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
		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer bg-primary text-white">
		<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
			<div class="footer-widgets py-12">
				<div class="container mx-auto px-4">
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
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
				</div>
			</div>
		<?php endif; ?>

		<div class="site-info py-6 border-t border-white border-opacity-10">
			<div class="container mx-auto px-4">
				<div class="flex flex-col md:flex-row justify-between items-center">
					<div class="copyright mb-4 md:mb-0">
						<?php
						$copyright_text = get_theme_mod( 'aqualuxe_copyright_text', '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. ' . __( 'All rights reserved.', 'aqualuxe' ) );
						echo wp_kses_post( $copyright_text );
						?>
					</div>

					<?php if ( has_nav_menu( 'footer' ) ) : ?>
						<nav class="footer-navigation">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer',
									'menu_id'        => 'footer-menu',
									'depth'          => 1,
									'container'      => false,
									'menu_class'     => 'footer-menu flex flex-wrap justify-center',
								)
							);
							?>
						</nav>
					<?php endif; ?>
				</div>

				<?php if ( get_theme_mod( 'aqualuxe_footer_tagline', true ) ) : ?>
					<div class="footer-tagline text-center mt-4 text-sm opacity-75">
						<?php echo esc_html( get_theme_mod( 'aqualuxe_footer_tagline_text', 'Bringing elegance to aquatic life – globally.' ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( get_theme_mod( 'aqualuxe_footer_social', true ) ) : ?>
					<div class="footer-social flex justify-center mt-4">
						<?php
						$social_links = array(
							'facebook'  => array(
								'label' => __( 'Facebook', 'aqualuxe' ),
								'icon'  => 'fab fa-facebook-f',
							),
							'twitter'   => array(
								'label' => __( 'Twitter', 'aqualuxe' ),
								'icon'  => 'fab fa-twitter',
							),
							'instagram' => array(
								'label' => __( 'Instagram', 'aqualuxe' ),
								'icon'  => 'fab fa-instagram',
							),
							'youtube'   => array(
								'label' => __( 'YouTube', 'aqualuxe' ),
								'icon'  => 'fab fa-youtube',
							),
							'pinterest' => array(
								'label' => __( 'Pinterest', 'aqualuxe' ),
								'icon'  => 'fab fa-pinterest-p',
							),
							'linkedin'  => array(
								'label' => __( 'LinkedIn', 'aqualuxe' ),
								'icon'  => 'fab fa-linkedin-in',
							),
						);

						foreach ( $social_links as $network => $data ) {
							$url = get_theme_mod( 'aqualuxe_social_' . $network );
							if ( $url ) {
								echo '<a href="' . esc_url( $url ) . '" class="social-link ' . esc_attr( $network ) . ' mx-2" target="_blank" rel="noopener noreferrer">';
								echo '<span class="screen-reader-text">' . esc_html( $data['label'] ) . '</span>';
								echo '<i class="' . esc_attr( $data['icon'] ) . '" aria-hidden="true"></i>';
								echo '</a>';
							}
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php if ( get_theme_mod( 'aqualuxe_back_to_top', true ) ) : ?>
	<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
		<i class="fas fa-chevron-up" aria-hidden="true"></i>
	</button>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>