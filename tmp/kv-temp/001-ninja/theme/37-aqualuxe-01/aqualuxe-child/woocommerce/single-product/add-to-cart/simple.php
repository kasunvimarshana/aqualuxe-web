<?php
/**
 * Simple product add to cart
 *
 * This template overrides WooCommerce's simple.php template for the add to cart section.
 *
 * @package AquaLuxe_Child
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart custom-cart-form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input(
			array(
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
			)
		);

		do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>

		<!-- Custom child theme addition: Product guarantee -->
		<div class="product-guarantee">
			<i class="fas fa-shield-alt"></i>
			<span><?php echo esc_html( apply_filters( 'aqualuxe_child_product_guarantee', __( '30-Day Money Back Guarantee', 'aqualuxe-child' ) ) ); ?></span>
		</div>

		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>">
			<span class="button-text"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></span>
			<span class="button-icon"><i class="fas fa-shopping-cart"></i></span>
		</button>

		<!-- Custom child theme addition: Wishlist button -->
		<?php if ( function_exists( 'aqualuxe_wishlist_button' ) ) : ?>
			<?php aqualuxe_wishlist_button(); ?>
		<?php else : ?>
			<button type="button" class="wishlist-button">
				<i class="far fa-heart"></i>
				<span class="screen-reader-text"><?php esc_html_e( 'Add to Wishlist', 'aqualuxe-child' ); ?></span>
			</button>
		<?php endif; ?>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>