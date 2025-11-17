<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     AquaLuxe
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

// Get sidebar position
$sidebar_position = aqualuxe_get_theme_option('aqualuxe_product_sidebar', 'none');
$has_sidebar = ($sidebar_position !== 'none' && is_active_sidebar('shop-sidebar'));

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<div class="product-container <?php echo $has_sidebar ? 'has-sidebar' : 'no-sidebar'; ?>">
	<?php if ($has_sidebar && $sidebar_position === 'left') : ?>
		<div class="product-sidebar">
			<?php do_action('woocommerce_sidebar'); ?>
		</div>
	<?php endif; ?>

	<div class="product-main">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
	</div>

	<?php if ($has_sidebar && $sidebar_position === 'right') : ?>
		<div class="product-sidebar">
			<?php do_action('woocommerce_sidebar'); ?>
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

get_footer( 'shop' );