<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?> bg-gray-50 dark:bg-gray-800 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
    <input id="payment_method_<?php echo esc_attr($gateway->id); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->chosen, true); ?> data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>" />

    <label for="payment_method_<?php echo esc_attr($gateway->id); ?>" class="flex items-center cursor-pointer">
        <span class="ml-2 font-medium"><?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></span>
        <?php if ($gateway->get_icon()) : ?>
            <span class="payment-method-icon ml-2"><?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></span>
        <?php endif; ?>
    </label>
    
    <?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
        <div class="payment_box payment_method_<?php echo esc_attr($gateway->id); ?> mt-4 bg-white dark:bg-gray-700 p-4 rounded-md <?php echo $gateway->chosen ? '' : 'hidden'; ?>">
            <?php $gateway->payment_fields(); ?>
        </div>
    <?php endif; ?>
</li>