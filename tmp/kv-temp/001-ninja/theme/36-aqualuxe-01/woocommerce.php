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

// Check if WooCommerce is active
if ( ! aqualuxe_is_woocommerce_active() ) {
	get_template_part( 'templates/content/content', 'woocommerce-inactive' );
	return;
}

get_header();

// Get the layout setting
if ( is_shop() || is_product_category() || is_product_tag() ) {
	$sidebar_layout = get_theme_mod( 'aqualuxe_shop_layout', 'right-sidebar' );
} elseif ( is_product() ) {
	$sidebar_layout = get_theme_mod( 'aqualuxe_product_layout', 'no-sidebar' );
} else {
	$sidebar_layout = get_theme_mod( 'aqualuxe_woocommerce_layout', 'right-sidebar' );
}

$container_class = 'no-sidebar' === $sidebar_layout ? 'container-fluid' : 'container';
?>

	<main id="primary" class="site-main <?php echo esc_attr( $container_class ); ?>">
		<div class="row">
			<div class="<?php echo esc_attr( aqualuxe_get_content_classes( $sidebar_layout ) ); ?>">
				<?php woocommerce_content(); ?>
			</div>

			<?php
			// Include sidebar if layout is not 'no-sidebar'
			if ( 'no-sidebar' !== $sidebar_layout && 'full-width' !== $sidebar_layout ) {
				if ( is_shop() || is_product_category() || is_product_tag() ) {
					get_sidebar( 'shop' );
				} elseif ( is_product() ) {
					get_sidebar( 'product' );
				} else {
					get_sidebar( 'woocommerce' );
				}
			}
			?>
		</div>
	</main><!-- #main -->

<?php
get_footer();