<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header mb-8">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title text-3xl font-bold mb-4"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>

<div class="shop-content grid grid-cols-1 <?php echo is_active_sidebar( 'shop-sidebar' ) ? 'lg:grid-cols-4 gap-8' : ''; ?>">
	<?php
	// Get the shop sidebar position
	$shop_sidebar = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
	
	// Show sidebar if it's active and not set to 'none'
	if ( is_active_sidebar( 'shop-sidebar' ) && 'none' !== $shop_sidebar ) :
		if ( 'left' === $shop_sidebar ) :
	?>
		<div class="shop-sidebar">
			<?php dynamic_sidebar( 'shop-sidebar' ); ?>
		</div>
	<?php
		endif;
	?>
	
	<div class="shop-main <?php echo is_active_sidebar( 'shop-sidebar' ) && 'none' !== $shop_sidebar ? 'lg:col-span-3' : ''; ?>">
	<?php
	if ( woocommerce_product_loop() ) {

		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );

		/**
		 * Hook: aqualuxe_before_shop_loop_products.
		 *
		 * @hooked aqualuxe_shop_filters_button - 10
		 * @hooked aqualuxe_shop_view_switcher - 20
		 */
		do_action( 'aqualuxe_before_shop_loop_products' );

		woocommerce_product_loop_start();

		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();

				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );
			}
		}

		woocommerce_product_loop_end();

		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );
	} else {
		/**
		 * Hook: woocommerce_no_products_found.
		 *
		 * @hooked wc_no_products_found - 10
		 */
		do_action( 'woocommerce_no_products_found' );
	}
	?>
	</div>
	
	<?php
		if ( is_active_sidebar( 'shop-sidebar' ) && 'right' === $shop_sidebar ) :
	?>
		<div class="shop-sidebar">
			<?php dynamic_sidebar( 'shop-sidebar' ); ?>
		</div>
	<?php
		endif;
	else :
	?>
	
	<div class="shop-main">
	<?php
	if ( woocommerce_product_loop() ) {

		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );

		/**
		 * Hook: aqualuxe_before_shop_loop_products.
		 *
		 * @hooked aqualuxe_shop_filters_button - 10
		 * @hooked aqualuxe_shop_view_switcher - 20
		 */
		do_action( 'aqualuxe_before_shop_loop_products' );

		woocommerce_product_loop_start();

		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();

				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );
			}
		}

		woocommerce_product_loop_end();

		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );
	} else {
		/**
		 * Hook: woocommerce_no_products_found.
		 *
		 * @hooked wc_no_products_found - 10
		 */
		do_action( 'woocommerce_no_products_found' );
	}
	?>
	</div>
	
	<?php endif; ?>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
// We're handling the sidebar manually above, so we don't need this hook
// do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );