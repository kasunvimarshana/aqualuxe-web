<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> mb-4 last:mb-0">
	<div class="payment-method-header bg-gray-50 dark:bg-dark-750 rounded-md p-4 flex items-center">
		<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio mr-2" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

		<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" class="flex-grow">
			<?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
		</label>
	</div>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?> bg-gray-50 dark:bg-dark-750 rounded-md p-4 mt-2 <?php echo $gateway->chosen ? '' : 'hidden'; ?>">
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>