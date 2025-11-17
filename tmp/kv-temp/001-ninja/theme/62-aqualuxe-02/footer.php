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

	<footer id="colophon" class="site-footer">
		<div class="container mx-auto px-4">
			<div class="site-footer-widgets py-8">
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
					<div class="site-footer-widget">
						<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
							<?php dynamic_sidebar( 'footer-1' ); ?>
						<?php endif; ?>
					</div>
					<div class="site-footer-widget">
						<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
							<?php dynamic_sidebar( 'footer-2' ); ?>
						<?php endif; ?>
					</div>
					<div class="site-footer-widget">
						<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
							<?php dynamic_sidebar( 'footer-3' ); ?>
						<?php endif; ?>
					</div>
					<div class="site-footer-widget">
						<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
							<?php dynamic_sidebar( 'footer-4' ); ?>
						<?php endif; ?>
					</div>
				</div>
			</div><!-- .site-footer-widgets -->

			<div class="site-footer-bottom py-4 border-t border-gray-200">
				<div class="flex flex-col md:flex-row justify-between items-center">
					<div class="site-footer-info mb-4 md:mb-0">
						<?php aqualuxe_copyright_text(); ?>
					</div><!-- .site-footer-info -->

					<div class="site-footer-navigation">
						<?php aqualuxe_footer_navigation(); ?>
					</div><!-- .site-footer-navigation -->

					<div class="site-footer-social">
						<?php aqualuxe_social_links(); ?>
					</div><!-- .site-footer-social -->
				</div>
			</div><!-- .site-footer-bottom -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
	<div id="aqualuxe-quick-view-modal" class="aqualuxe-modal">
		<div class="aqualuxe-modal-overlay"></div>
		<div class="aqualuxe-modal-container">
			<div class="aqualuxe-modal-content">
				<button class="aqualuxe-modal-close">
					<span class="screen-reader-text"><?php esc_html_e( 'Close', 'aqualuxe' ); ?></span>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
				</button>
				<div class="aqualuxe-modal-body">
					<div class="aqualuxe-quick-view-content"></div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>