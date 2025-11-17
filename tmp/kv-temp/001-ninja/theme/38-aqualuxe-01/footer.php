<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>
		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php
		$footer_columns = get_theme_mod( 'aqualuxe_footer_columns', '4' );
		
		if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) :
		?>
		<div class="footer-widgets">
			<div class="container">
				<div class="footer-widgets-inner footer-widgets-columns-<?php echo esc_attr( $footer_columns ); ?>">
					<?php
					for ( $i = 1; $i <= $footer_columns; $i++ ) :
						if ( is_active_sidebar( 'footer-' . $i ) ) :
							?>
							<div class="footer-widget footer-widget-<?php echo esc_attr( $i ); ?>">
								<?php dynamic_sidebar( 'footer-' . $i ); ?>
							</div>
							<?php
						endif;
					endfor;
					?>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="site-info">
			<div class="container">
				<div class="site-info-inner">
					<div class="copyright">
						<?php
						$copyright_text = get_theme_mod( 'aqualuxe_copyright_text', sprintf( esc_html__( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ) );
						echo wp_kses_post( str_replace( '{year}', date( 'Y' ), $copyright_text ) );
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
									'menu_class'     => 'footer-menu',
								)
							);
							?>
						</nav>
					<?php endif; ?>

					<?php if ( has_nav_menu( 'social' ) ) : ?>
						<nav class="social-navigation">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'social',
									'menu_id'        => 'social-menu',
									'depth'          => 1,
									'container'      => false,
									'menu_class'     => 'social-menu',
									'link_before'    => '<span class="screen-reader-text">',
									'link_after'     => '</span>',
								)
							);
							?>
						</nav>
					<?php else : ?>
						<?php aqualuxe_social_links(); ?>
					<?php endif; ?>
				</div>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php if ( get_theme_mod( 'aqualuxe_back_to_top', true ) ) : ?>
<a href="#page" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M11.47 7.72a.75.75 0 011.06 0l7.5 7.5a.75.75 0 11-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 01-1.06-1.06l7.5-7.5z" clip-rule="evenodd" /></svg>
</a>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>