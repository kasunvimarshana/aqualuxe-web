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
	<footer id="colophon" class="site-footer bg-gray-800 text-white mt-8">
		<div class="container mx-auto p-4 text-center">
			<div class="site-info">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'aqualuxe' ) ); ?>" class="text-blue-400 hover:text-blue-300">
					<?php
					/* translators: %s: CMS name, i.e. WordPress. */
					printf( esc_html__( 'Proudly powered by %s', 'aqualuxe' ), 'WordPress' );
					?>
				</a>
				<span class="sep"> | </span>
					<?php
					/* translators: 1: Theme name, 2: Theme author. */
					printf( esc_html__( 'Theme: %1$s by %2$s.', 'aqualuxe' ), 'aqualuxe', '<a href="https://aqualuxe.com" class="text-blue-400 hover:text-blue-300">AquaLuxe Team</a>' );
					?>
			</div><!-- .site-info -->
			<?php do_action('aqualuxe_footer_bottom'); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
