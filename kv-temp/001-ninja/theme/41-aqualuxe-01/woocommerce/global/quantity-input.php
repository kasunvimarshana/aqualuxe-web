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

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'aqualuxe' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'aqualuxe' );

// In some cases we wish to display the quantity but not allow for it to be changed.
if ( $max_value && $min_value === $max_value ) {
	$is_readonly = true;
	$input_value = $min_value;
} else {
	$is_readonly = false;
}
?>
<div class="quantity woocommerce-quantity-wrapper">
	<?php
	/**
	 * Hook to output something before the quantity input field.
	 *
	 * @since 7.2.0
	 */
	do_action( 'woocommerce_before_quantity_input_field' );
	?>
	<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $label ); ?></label>
	<div class="quantity-input-group flex items-center">
		<?php if ( ! $is_readonly ) : ?>
			<button type="button" class="quantity-decrease bg-gray-200 dark:bg-dark-700 text-dark-900 dark:text-white w-8 h-8 flex items-center justify-center rounded-l-md focus:outline-none hover:bg-gray-300 dark:hover:bg-dark-600 transition-colors" aria-label="<?php esc_attr_e( 'Decrease quantity', 'aqualuxe' ); ?>">
				<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
				</svg>
			</button>
		<?php endif; ?>
		
		<input
			type="<?php echo $is_readonly ? 'text' : 'number'; ?>"
			<?php echo $is_readonly ? 'readonly="readonly"' : ''; ?>
			id="<?php echo esc_attr( $input_id ); ?>"
			class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?> bg-white dark:bg-dark-800 text-dark-900 dark:text-white border-gray-300 dark:border-dark-600 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-500 dark:focus:border-primary-500 text-center w-14 h-8 <?php echo $is_readonly ? '' : 'rounded-none'; ?>"
			name="<?php echo esc_attr( $input_name ); ?>"
			value="<?php echo esc_attr( $input_value ); ?>"
			aria-label="<?php esc_attr_e( 'Product quantity', 'aqualuxe' ); ?>"
			size="4"
			min="<?php echo esc_attr( $min_value ); ?>"
			max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
			<?php if ( ! $is_readonly ): ?>
			step="<?php echo esc_attr( $step ); ?>"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			inputmode="<?php echo esc_attr( $inputmode ); ?>"
			autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'off' ); ?>"
			<?php endif; ?>
		/>
		
		<?php if ( ! $is_readonly ) : ?>
			<button type="button" class="quantity-increase bg-gray-200 dark:bg-dark-700 text-dark-900 dark:text-white w-8 h-8 flex items-center justify-center rounded-r-md focus:outline-none hover:bg-gray-300 dark:hover:bg-dark-600 transition-colors" aria-label="<?php esc_attr_e( 'Increase quantity', 'aqualuxe' ); ?>">
				<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
				</svg>
			</button>
		<?php endif; ?>
	</div>
	<?php
	/**
	 * Hook to output something after quantity input field
	 *
	 * @since 3.6.0
	 */
	do_action( 'woocommerce_after_quantity_input_field' );
	?>
</div>
<?php if ( ! $is_readonly ) : ?>
<script type="text/javascript">
	jQuery(function($) {
		// Quantity buttons
		$(document).on('click', '.quantity-decrease', function() {
			var $input = $(this).closest('.quantity-input-group').find('input[type="number"]');
			var value = parseInt($input.val()) - 1;
			value = value < $input.attr('min') ? $input.attr('min') : value;
			$input.val(value).trigger('change');
		});
		
		$(document).on('click', '.quantity-increase', function() {
			var $input = $(this).closest('.quantity-input-group').find('input[type="number"]');
			var value = parseInt($input.val()) + 1;
			var max = $input.attr('max') ? parseInt($input.attr('max')) : '';
			value = (max && value > max) ? max : value;
			$input.val(value).trigger('change');
		});
	});
</script>
<?php endif; ?>