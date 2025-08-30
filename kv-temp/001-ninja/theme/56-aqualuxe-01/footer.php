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

		<?php
		/**
		 * Hook: aqualuxe_after_main_content.
		 */
		do_action( 'aqualuxe_after_main_content' );
		?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer bg-dark-800 text-light-500">
		<?php
		/**
		 * Hook: aqualuxe_footer.
		 *
		 * @hooked aqualuxe_footer_before - 5
		 * @hooked aqualuxe_footer_top - 10
		 * @hooked aqualuxe_footer_main - 20
		 * @hooked aqualuxe_footer_bottom - 30
		 * @hooked aqualuxe_footer_after - 35
		 */
		do_action( 'aqualuxe_footer' );
		?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>