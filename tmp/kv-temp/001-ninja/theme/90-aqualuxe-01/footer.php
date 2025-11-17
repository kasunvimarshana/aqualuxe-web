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
	<footer id="colophon" class="site-footer bg-gray-800 text-white mt-auto">
		<div class="container mx-auto px-4 py-8">
			<div class="site-info text-center">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'aqualuxe' ) ); ?>" class="text-blue-400 hover:text-blue-300">
					<?php
					/* translators: %s: CMS name, i.e. WordPress. */
					printf( esc_html__( 'Proudly powered by %s', 'aqualuxe' ), 'WordPress' );
					?>
				</a>
				<span class="sep text-gray-500"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'aqualuxe' ), 'aqualuxe', '<a href="https://github.com/kasunvimarshana" class="text-blue-400 hover:text-blue-300">Kasun Vimarshana</a>' );
				?>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
