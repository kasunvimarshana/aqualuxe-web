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

	<?php
	/**
	 * Hook: aqualuxe_before_footer
	 *
	 * @hooked aqualuxe_display_footer_widgets - 10
	 */
	do_action( 'aqualuxe_before_footer' );
	?>

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="site-footer-inner">
				<div class="site-info">
					<?php
					$copyright = get_theme_mod( 'aqualuxe_footer_copyright', sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ) );
					echo wp_kses_post( $copyright );
					?>
				</div><!-- .site-info -->

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
					</nav><!-- .footer-navigation -->
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
					</nav><!-- .social-navigation -->
				<?php endif; ?>
			</div><!-- .site-footer-inner -->
		</div><!-- .container -->
	</footer><!-- #colophon -->

	<?php
	/**
	 * Hook: aqualuxe_after_footer
	 *
	 * @hooked aqualuxe_back_to_top - 10
	 */
	do_action( 'aqualuxe_after_footer' );
	?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>