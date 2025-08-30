<?php
/**
 * Output a single payment method
 *
 * @package AquaLuxe
 */

defined( 'ABSPATH' ) || exit;
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
    <input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

    <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" class="inline-flex items-center cursor-pointer ml-2 text-gray-900 dark:text-gray-100 font-medium">
        <?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
    </label>
    
    <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
        <div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?> mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-md text-gray-700 dark:text-gray-300 text-sm <?php echo $gateway->chosen ? '' : 'hidden'; ?>">
            <?php $gateway->payment_fields(); ?>
        </div>
    <?php endif; ?>
</li>