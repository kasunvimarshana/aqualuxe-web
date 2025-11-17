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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

	</div><!-- #content -->

	<?php aqualuxe_after_content(); ?>

	<?php aqualuxe_before_footer(); ?>

	<footer id="colophon" class="site-footer bg-gray-900 text-white">
		<?php get_template_part( 'template-parts/footer/newsletter' ); ?>
		
		<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
			<?php aqualuxe_before_footer_widgets(); ?>
			<div class="footer-widgets py-12 border-b border-gray-800">
				<div class="container mx-auto px-4">
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
						<?php get_template_part( 'template-parts/footer/widgets' ); ?>
					</div>
				</div>
			</div>
			<?php aqualuxe_after_footer_widgets(); ?>
		<?php endif; ?>
		
		<?php aqualuxe_before_footer_bottom(); ?>
		<div class="footer-bottom py-6">
			<div class="container mx-auto px-4">
				<div class="flex flex-col md:flex-row justify-between items-center">
					<div class="site-info mb-4 md:mb-0 text-center md:text-left">
						<?php aqualuxe_before_footer_copyright(); ?>
						<?php
						printf(
							/* translators: %s: Theme name. */
							esc_html__( '© %1$s %2$s. Powered by %3$s.', 'aqualuxe' ),
							date_i18n( 'Y' ),
							get_bloginfo( 'name' ),
							'<a href="https://wordpress.org/" target="_blank" rel="noopener noreferrer">WordPress</a>'
						);
						?>
						<?php aqualuxe_after_footer_copyright(); ?>
					</div><!-- .site-info -->
					
					<?php aqualuxe_before_footer_menu(); ?>
					<?php get_template_part( 'template-parts/navigation/footer-menu' ); ?>
					<?php aqualuxe_after_footer_menu(); ?>
				</div>
				
				<?php get_template_part( 'template-parts/footer/bottom' ); ?>
			</div>
		</div>
		<?php aqualuxe_after_footer_bottom(); ?>
	</footer><!-- #colophon -->

	<?php aqualuxe_after_footer(); ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
<?php aqualuxe_after_html(); ?>
</html>