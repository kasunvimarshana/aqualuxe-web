<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */

defined( 'ABSPATH' ) || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'aqualuxe' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'aqualuxe' );

?>
<div class="quantity-buttons">
	<button type="button" class="quantity-button minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'aqualuxe' ); ?>">
		<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
			<path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
		</svg>
	</button>
	
	<div class="quantity">
		<?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
		<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $label ); ?></label>
		<input
			type="<?php echo esc_attr( $type ); ?>"
			<?php echo $readonly ? 'readonly="readonly"' : ''; ?>
			id="<?php echo esc_attr( $input_id ); ?>"
			class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
			name="<?php echo esc_attr( $input_name ); ?>"
			value="<?php echo esc_attr( $input_value ); ?>"
			aria-label="<?php esc_attr_e( 'Product quantity', 'aqualuxe' ); ?>"
			size="4"
			min="<?php echo esc_attr( $min_value ); ?>"
			max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
			<?php if ( ! $readonly ) : ?>
				step="<?php echo esc_attr( $step ); ?>"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				inputmode="<?php echo esc_attr( $inputmode ); ?>"
				autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
			<?php endif; ?>
		/>
		<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
	</div>
	
	<button type="button" class="quantity-button plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'aqualuxe' ); ?>">
		<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
			<path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
		</svg>
	</button>
</div>

<style>
	.quantity-buttons {
		display: flex;
		align-items: center;
		border: 1px solid #e2e8f0;
		border-radius: 0.375rem;
		overflow: hidden;
		width: fit-content;
	}
	
	.dark .quantity-buttons {
		border-color: #334155;
	}
	
	.quantity-button {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 2rem;
		height: 2.5rem;
		background-color: #f8fafc;
		color: #64748b;
		border: none;
		cursor: pointer;
		transition: all 0.2s;
	}
	
	.dark .quantity-button {
		background-color: #1e293b;
		color: #94a3b8;
	}
	
	.quantity-button:hover {
		background-color: #f1f5f9;
		color: #0ea5e9;
	}
	
	.dark .quantity-button:hover {
		background-color: #334155;
		color: #38bdf8;
	}
	
	.quantity-buttons .quantity {
		width: 3rem;
	}
	
	.quantity-buttons input[type="number"] {
		-moz-appearance: textfield;
		width: 100%;
		height: 2.5rem;
		border: none;
		text-align: center;
		font-size: 0.875rem;
		padding: 0;
		background-color: #ffffff;
	}
	
	.dark .quantity-buttons input[type="number"] {
		background-color: #0f172a;
		color: #e2e8f0;
	}
	
	.quantity-buttons input[type="number"]::-webkit-outer-spin-button,
	.quantity-buttons input[type="number"]::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
	
	.quantity-buttons input[type="number"]:focus {
		outline: none;
		box-shadow: none;
	}
</style>

<script>
	(function() {
		document.addEventListener('DOMContentLoaded', function() {
			const quantityButtons = document.querySelectorAll('.quantity-buttons');
			
			quantityButtons.forEach(function(container) {
				const minusButton = container.querySelector('.minus');
				const plusButton = container.querySelector('.plus');
				const input = container.querySelector('input[type="number"]');
				
				if (!minusButton || !plusButton || !input) return;
				
				minusButton.addEventListener('click', function() {
					const currentValue = parseInt(input.value, 10);
					const minValue = parseInt(input.getAttribute('min'), 10) || 1;
					
					if (currentValue > minValue) {
						input.value = currentValue - 1;
						input.dispatchEvent(new Event('change', { bubbles: true }));
					}
				});
				
				plusButton.addEventListener('click', function() {
					const currentValue = parseInt(input.value, 10);
					const maxValue = parseInt(input.getAttribute('max'), 10) || 9999;
					
					if (!maxValue || currentValue < maxValue) {
						input.value = currentValue + 1;
						input.dispatchEvent(new Event('change', { bubbles: true }));
					}
				});
			});
		});
	})();
</script>