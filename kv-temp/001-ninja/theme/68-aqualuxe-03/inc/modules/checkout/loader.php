<?php
/**
 * Checkout Enhancements Module Loader
 *
 * @package AquaLuxe\Modules\Checkout
 */
namespace AquaLuxe\Modules\Checkout;

if ( ! defined( 'ABSPATH' ) ) exit;

class Loader {
    public static function init() {
        add_filter( 'woocommerce_checkout_fields', [ __CLASS__, 'customize_fields' ] );
        add_action( 'woocommerce_review_order_before_submit', [ __CLASS__, 'add_terms_checkbox' ] );
        add_action( 'woocommerce_checkout_process', [ __CLASS__, 'validate_terms_checkbox' ] );
        add_action( 'woocommerce_after_checkout_form', [ __CLASS__, 'add_trust_badges' ] );
        add_filter( 'woocommerce_order_button_html', [ __CLASS__, 'custom_order_button' ] );
    }

    /**
     * Customize checkout fields (reorder, placeholders, required, etc.)
     */
    public static function customize_fields( $fields ) {
        // Example: Make phone optional, add placeholder
        if ( isset($fields['billing']['billing_phone']) ) {
            $fields['billing']['billing_phone']['required'] = false;
            $fields['billing']['billing_phone']['placeholder'] = __( 'Optional', 'aqualuxe' );
        }
        // Example: Move email to top
        if ( isset($fields['billing']['billing_email']) ) {
            $email = $fields['billing']['billing_email'];
            unset($fields['billing']['billing_email']);
            $fields['billing'] = array_merge( [ 'billing_email' => $email ], $fields['billing'] );
        }
        return $fields;
    }

    /**
     * Add terms and privacy checkbox
     */
    public static function add_terms_checkbox() {
        echo '<p class="form-row terms">'
            . '<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">'
            . '<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="aqualuxe_terms" id="aqualuxe_terms"> '
            . __( 'I agree to the <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>', 'aqualuxe' )
            . ' <span class="required">*</span></label></p>';
    }

    public static function validate_terms_checkbox() {
        if ( empty($_POST['aqualuxe_terms']) ) {
            wc_add_notice( __( 'You must agree to the terms and privacy policy to continue.', 'aqualuxe' ), 'error' );
        }
    }

    /**
     * Add trust badges below checkout form
     */
    public static function add_trust_badges() {
        echo '<div class="aqualuxe-trust-badges" style="margin:2em 0;text-align:center;">'
            . '<img src="/assets/images/badges/ssl.svg" alt="SSL Secure" style="height:32px;margin:0 1em;">'
            . '<img src="/assets/images/badges/guarantee.svg" alt="Money Back Guarantee" style="height:32px;margin:0 1em;">'
            . '<img src="/assets/images/badges/shipping.svg" alt="Fast Shipping" style="height:32px;margin:0 1em;">'
            . '</div>';
    }

    /**
     * Customize order button (add icon, text)
     */
    public static function custom_order_button( $button ) {
        $button = str_replace('type="submit"', 'type="submit" style="background:#1e40af;color:#fff;font-weight:700;font-size:1.2em;padding:1em 2em;border-radius:6px;"', $button);
        $button = str_replace('>','><span style="margin-right:0.5em;">🛒</span>', $button);
        return $button;
    }
}

Loader::init();
