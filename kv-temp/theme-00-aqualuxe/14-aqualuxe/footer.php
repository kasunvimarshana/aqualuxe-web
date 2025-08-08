<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package AquaLuxe
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<div class="footer-widgets">
				<div class="footer-widget-area">
					<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
						<?php dynamic_sidebar( 'footer-1' ); ?>
					<?php endif; ?>
				</div>
				<div class="footer-widget-area">
					<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-2' ); ?>
					<?php endif; ?>
				</div>
				<div class="footer-widget-area">
					<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-3' ); ?>
					<?php endif; ?>
				</div>
				<div class="footer-widget-area">
					<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
						<?php dynamic_sidebar( 'footer-4' ); ?>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="site-copyright">
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( '© %1$s %2$s. All rights reserved.', 'aqualuxe' ), date( 'Y' ), get_bloginfo( 'name' ) );
				?>
			</div>
			
			<nav class="footer-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-2',
						'menu_id'        => 'footer-menu',
					)
				);
				?>
			</nav><!-- .footer-navigation -->
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>