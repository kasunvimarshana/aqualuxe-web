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
		<div class="container">
			<div class="footer-widgets">
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
			</div><!-- .footer-widgets -->

			<div class="footer-bottom">
				<div class="site-info">
					<?php
					$footer_copyright = get_theme_mod( 'aqualuxe_footer_copyright', sprintf(
						/* translators: %1$s: Current year, %2$s: Site name */
						esc_html__( '© %1$s %2$s. All Rights Reserved.', 'aqualuxe' ),
						date_i18n( 'Y' ),
						get_bloginfo( 'name' )
					) );
					
					echo wp_kses_post( $footer_copyright );
					?>
					<span class="sep"> | </span>
					<?php
					/* translators: %1$s: Theme name, %2$s: Theme author website */
					printf( esc_html__( 'Theme: %1$s by %2$s', 'aqualuxe' ), 'AquaLuxe', '<a href="https://ninjatech.ai/">NinjaTech AI</a>' );
					?>
				</div><!-- .site-info -->

				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<nav class="footer-navigation">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'menu_class'     => 'footer-menu',
								'depth'          => 1,
								'container'      => false,
							)
						);
						?>
					</nav>
				<?php endif; ?>

				<?php if ( function_exists( 'aqualuxe_social_icons' ) ) : ?>
					<div class="footer-social">
						<?php aqualuxe_social_icons(); ?>
					</div>
				<?php endif; ?>
			</div><!-- .footer-bottom -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>