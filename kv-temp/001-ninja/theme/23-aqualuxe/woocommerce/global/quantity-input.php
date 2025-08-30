<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 7.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
	?>
	<div class="quantity hidden">
		<input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
	</div>
	<?php
} else {
	/* translators: %s: Quantity. */
	$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'aqualuxe' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'aqualuxe' );
	?>
	<div class="quantity-wrapper relative">
		<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $label ); ?></label>
		<button type="button" class="quantity-button minus absolute left-0 top-0 w-8 h-full flex items-center justify-center bg-gray-100 border border-gray-300 text-gray-700 hover:bg-gray-200 transition-colors duration-200 rounded-l-md">
			<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
			</svg>
		</button>
		<input
			type="number"
			id="<?php echo esc_attr( $input_id ); ?>"
			class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?> pl-8 pr-8 text-center"
			step="<?php echo esc_attr( $step ); ?>"
			min="<?php echo esc_attr( $min_value ); ?>"
			max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
			name="<?php echo esc_attr( $input_name ); ?>"
			value="<?php echo esc_attr( $input_value ); ?>"
			title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'aqualuxe' ); ?>"
			size="4"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			inputmode="<?php echo esc_attr( $inputmode ); ?>"
			autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'off' ); ?>"
		/>
		<button type="button" class="quantity-button plus absolute right-0 top-0 w-8 h-full flex items-center justify-center bg-gray-100 border border-gray-300 text-gray-700 hover:bg-gray-200 transition-colors duration-200 rounded-r-md">
			<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
			</svg>
		</button>
	</div>
	<?php
}
?>

<script type="text/javascript">
	// Quantity buttons functionality
	document.addEventListener('DOMContentLoaded', function() {
		const quantityWrapper = document.querySelector('.quantity-wrapper');
		
		if (quantityWrapper) {
			const minusBtn = quantityWrapper.querySelector('.minus');
			const plusBtn = quantityWrapper.querySelector('.plus');
			const input = quantityWrapper.querySelector('input.qty');
			
			if (minusBtn && plusBtn && input) {
				// Minus button click
				minusBtn.addEventListener('click', function() {
					const currentValue = parseInt(input.value);
					const min = parseInt(input.min);
					
					if (currentValue > min) {
						input.value = currentValue - 1;
						input.dispatchEvent(new Event('change', { bubbles: true }));
					}
				});
				
				// Plus button click
				plusBtn.addEventListener('click', function() {
					const currentValue = parseInt(input.value);
					const max = input.max ? parseInt(input.max) : Infinity;
					
					if (currentValue < max) {
						input.value = currentValue + 1;
						input.dispatchEvent(new Event('change', { bubbles: true }));
					}
				});
			}
		}
	});
</script>