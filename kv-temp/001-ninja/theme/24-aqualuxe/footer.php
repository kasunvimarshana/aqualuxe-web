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

	<footer id="colophon" class="site-footer bg-gray-900 text-white py-12">
		<div class="container mx-auto px-4">
			<div class="footer-widgets grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo esc_attr( get_theme_mod( 'aqualuxe_footer_widget_columns', 4 ) ); ?> gap-8">
				<?php
				// Footer widget areas
				$footer_columns = get_theme_mod( 'aqualuxe_footer_widget_columns', 4 );
				
				for ( $i = 1; $i <= $footer_columns; $i++ ) {
					if ( is_active_sidebar( 'footer-' . $i ) ) {
						echo '<div class="footer-widget-area">';
						dynamic_sidebar( 'footer-' . $i );
						echo '</div>';
					}
				}
				?>
			</div><!-- .footer-widgets -->
			
			<div class="footer-bottom mt-8 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center">
				<div class="footer-info mb-4 md:mb-0">
					<?php
					$copyright = get_theme_mod( 'aqualuxe_footer_copyright', sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ) );
					echo wp_kses_post( $copyright );
					?>
				</div>
				
				<div class="footer-menu">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'menu_class'     => 'flex flex-wrap justify-center',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
							'add_li_class'   => 'mx-2',
							'link_class'     => 'text-gray-400 hover:text-white transition-colors',
						)
					);
					?>
				</div>
			</div><!-- .footer-bottom -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>