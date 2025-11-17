<?php
/**
 * The template for displaying WooCommerce content
 *
 * This is the template that displays all WooCommerce pages by default.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 *
 * @package AquaLuxe
 */

get_header( 'shop' );
?>

<div class="container mx-auto px-4 py-8">
	<div class="flex flex-wrap -mx-4">
		<?php if ( is_active_sidebar( 'sidebar-shop' ) && ! is_product() ) : ?>
			<main id="primary" class="site-main w-full lg:w-3/4 px-4">
				<?php woocommerce_content(); ?>
			</main><!-- #main -->

			<aside id="secondary" class="widget-area w-full lg:w-1/4 px-4 mt-8 lg:mt-0">
				<?php dynamic_sidebar( 'sidebar-shop' ); ?>
			</aside><!-- #secondary -->
		<?php else : ?>
			<main id="primary" class="site-main w-full px-4">
				<?php woocommerce_content(); ?>
			</main><!-- #main -->
		<?php endif; ?>
	</div>
</div>

<?php
get_footer( 'shop' );