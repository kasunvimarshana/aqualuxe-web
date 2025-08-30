<?php
/**
 * The template for displaying WooCommerce pages
 *
 * This is the template that displays all WooCommerce pages by default.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 *
 * @package AquaLuxe
 */

get_header( 'shop' );
?>

<main id="primary" class="site-main">

	<?php
	/**
	 * Hook: aqualuxe_before_main_content.
	 *
	 * @hooked aqualuxe_breadcrumbs - 10
	 */
	do_action( 'aqualuxe_before_main_content' );
	?>

	<div class="container">
		<div class="content-area">
			<?php woocommerce_content(); ?>
		</div><!-- .content-area -->

		<?php
		/**
		 * Hook: aqualuxe_woocommerce_sidebar.
		 *
		 * @hooked aqualuxe_get_sidebar - 10
		 */
		do_action( 'aqualuxe_woocommerce_sidebar' );
		?>
	</div><!-- .container -->

	<?php
	/**
	 * Hook: aqualuxe_after_main_content.
	 */
	do_action( 'aqualuxe_after_main_content' );
	?>

</main><!-- #primary -->

<?php
get_footer( 'shop' );