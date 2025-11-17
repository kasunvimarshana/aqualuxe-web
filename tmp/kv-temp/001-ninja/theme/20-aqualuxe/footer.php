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

		<?php aqualuxe_after_content(); ?>
	</div><!-- #content -->

	<?php aqualuxe_before_footer(); ?>

	<footer id="colophon" class="site-footer">
		<?php aqualuxe_footer(); ?>
	</footer><!-- #colophon -->

	<?php aqualuxe_after_footer(); ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>