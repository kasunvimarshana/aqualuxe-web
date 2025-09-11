<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

		<?php
		/**
		 * Hook: aqualuxe_content_end
		 */
		do_action( 'aqualuxe_content_end' );
		?>
		
		</div><!-- .container -->
	</div><!-- #content -->

	<?php
	/**
	 * Hook: aqualuxe_before_footer
	 */
	do_action( 'aqualuxe_before_footer' );
	?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php
		/**
		 * Hook: aqualuxe_footer_start
		 */
		do_action( 'aqualuxe_footer_start' );
		?>

		<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
			<div class="footer-widgets">
				<div class="container">
					<div class="footer-widgets__grid">
						<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
							<?php if ( is_active_sidebar( "footer-{$i}" ) ) : ?>
								<div class="footer-widget-area footer-widget-<?php echo esc_attr( $i ); ?>">
									<?php dynamic_sidebar( "footer-{$i}" ); ?>
								</div>
							<?php endif; ?>
						<?php endfor; ?>
					</div><!-- .footer-widgets__grid -->
				</div><!-- .container -->
			</div><!-- .footer-widgets -->
		<?php endif; ?>

		<div class="site-info">
			<div class="container">
				<div class="site-info__inner">
					<div class="site-info__content">
						<?php
						$footer_text = get_theme_mod( 'aqualuxe_footer_text' );
						if ( $footer_text ) {
							echo wp_kses_post( $footer_text );
						} else {
							?>
							<p>
								<?php
								/* translators: 1: Theme name, 2: Theme author. */
								printf( esc_html__( '© %1$s %2$s. Bringing elegance to aquatic life – globally.', 'aqualuxe' ), 
									date( 'Y' ), 
									get_bloginfo( 'name' ) 
								);
								?>
							</p>
							<?php
						}
						?>
					</div><!-- .site-info__content -->

					<div class="site-info__navigation">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'menu_class'     => 'footer-menu',
								'container'      => false,
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</div><!-- .site-info__navigation -->

					<div class="site-info__social">
						<?php
						/**
						 * Hook: aqualuxe_footer_social
						 *
						 * @hooked aqualuxe_output_social_links - 10
						 */
						do_action( 'aqualuxe_footer_social' );
						?>
					</div><!-- .site-info__social -->

				</div><!-- .site-info__inner -->
			</div><!-- .container -->
		</div><!-- .site-info -->

		<?php
		/**
		 * Hook: aqualuxe_footer_end
		 */
		do_action( 'aqualuxe_footer_end' );
		?>
		
	</footer><!-- #colophon -->

	<?php
	/**
	 * Hook: aqualuxe_after_footer
	 */
	do_action( 'aqualuxe_after_footer' );
	?>

</div><!-- #page -->

<?php
/**
 * Hook: aqualuxe_before_closing_body
 */
do_action( 'aqualuxe_before_closing_body' );

wp_footer();
?>

</body>
</html>