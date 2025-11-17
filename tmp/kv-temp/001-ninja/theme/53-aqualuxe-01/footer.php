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
			</div><!-- .<?php echo esc_attr( aqualuxe_get_content_class() ); ?> -->
		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
			<div class="footer-widgets">
				<div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
					<div class="footer-widgets-inner flex flex-wrap">
						<?php
						$footer_columns = aqualuxe_get_footer_columns();
						$column_class = aqualuxe_get_footer_column_class();

						for ( $i = 1; $i <= $footer_columns; $i++ ) :
							if ( is_active_sidebar( 'footer-' . $i ) ) :
								?>
								<div class="footer-widget <?php echo esc_attr( $column_class ); ?>">
									<?php dynamic_sidebar( 'footer-' . $i ); ?>
								</div>
								<?php
							endif;
						endfor;
						?>
					</div>
				</div>
			</div><!-- .footer-widgets -->
		<?php endif; ?>

		<div class="site-info">
			<div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
				<div class="site-info-inner flex flex-wrap items-center justify-between">
					<div class="site-info-left">
						<?php aqualuxe_footer_text(); ?>
					</div>

					<div class="site-info-right">
						<?php aqualuxe_footer_navigation(); ?>
						<?php aqualuxe_social_links(); ?>
					</div>
				</div>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>