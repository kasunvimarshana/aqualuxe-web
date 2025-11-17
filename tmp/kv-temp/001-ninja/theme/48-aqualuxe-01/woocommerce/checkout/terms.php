<?php
/**
 * Checkout terms and conditions area.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
    do_action( 'woocommerce_checkout_before_terms_and_conditions' );

    ?>
    <div class="woocommerce-terms-and-conditions-wrapper my-6">
        <?php
        /**
         * Terms and conditions hook used to inject content.
         *
         * @since 3.4.0.
         * @hooked wc_checkout_privacy_policy_text - 10
         * @hooked wc_checkout_terms_and_conditions_page_content - 20
         */
        do_action( 'woocommerce_checkout_terms_and_conditions' );
        ?>

        <?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
            <div class="woocommerce-terms-and-conditions-checkbox-wrapper flex items-start mb-4">
                <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mt-1 mr-2" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms" />
                <label for="terms" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox text-sm">
                    <?php wc_terms_and_conditions_checkbox_text(); ?>
                    <abbr class="required text-red-500" title="<?php esc_attr_e( 'required', 'aqualuxe' ); ?>">*</abbr>
                </label>
                <input type="hidden" name="terms-field" value="1" />
            </div>
        <?php endif; ?>
    </div>
    <?php

    do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}