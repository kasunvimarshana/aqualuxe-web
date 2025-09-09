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
		<div class="container mx-auto">
			<div class="footer-widgets grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<div class="widget-area">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<div class="widget-area">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<div class="widget-area">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="footer-bottom text-center border-t border-gray-700 pt-4">
				<div class="site-info">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'aqualuxe' ) ); ?>">
						<?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( 'Proudly powered by %s', 'aqualuxe' ), 'WordPress' );
						?>
					</a>
					<span class="sep"> | </span>
					<?php
					/* translators: 1: Theme name, 2: Theme author. */
					printf( esc_html__( 'Theme: %1$s by %2$s.', 'aqualuxe' ), 'AquaLuxe', '<a href="https://github.com/kasunvimarshana">Kasun Vimarshana</a>' );
					?>
				</div><!-- .site-info -->
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
