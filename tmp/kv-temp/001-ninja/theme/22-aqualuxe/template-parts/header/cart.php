<?php
/**
 * Template part for displaying the cart icon
 *
 * @package AquaLuxe
 */

// Exit if WooCommerce is not active
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>

<div class="header-cart relative">
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
		<span class="sr-only"><?php esc_html_e( 'Cart', 'aqualuxe' ); ?></span>
		<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
		</svg>
		<span class="cart-count absolute -top-1 -right-1 bg-primary-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">
			<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
		</span>
	</a>
	
	<?php if ( aqualuxe_get_option( 'enable_mini_cart', true ) ) : ?>
		<div class="mini-cart hidden absolute right-0 top-full mt-2 w-80 bg-white dark:bg-dark-400 rounded-lg shadow-lg z-50 p-4">
			<div class="widget_shopping_cart_content">
				<?php woocommerce_mini_cart(); ?>
			</div>
		</div>
	<?php endif; ?>
</div>