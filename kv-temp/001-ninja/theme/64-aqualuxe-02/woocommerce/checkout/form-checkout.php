<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

// Check if multi-step checkout is enabled
$is_multi_step = aqualuxe_get_option( 'enable_multi_step_checkout', false );

// Custom hook before checkout content
do_action( 'aqualuxe_before_checkout_content' );

// Check if checkout registration is enabled
if ( ! $is_multi_step && ! is_user_logged_in() && 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
    do_action( 'woocommerce_before_checkout_form', $checkout );
}

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'aqualuxe' ) ) );
    return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout <?php echo $is_multi_step ? 'multi-step-checkout' : ''; ?>" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <?php if ( $is_multi_step ) : ?>
        <div class="checkout-steps">
            <div class="checkout-step active" data-step="checkout-customer-info">
                <?php esc_html_e( 'Customer Info', 'aqualuxe' ); ?>
            </div>
            <div class="checkout-step" data-step="checkout-shipping">
                <?php esc_html_e( 'Shipping', 'aqualuxe' ); ?>
            </div>
            <div class="checkout-step" data-step="checkout-payment">
                <?php esc_html_e( 'Payment', 'aqualuxe' ); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ( $checkout->get_checkout_fields() ) : ?>

        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

        <?php if ( $is_multi_step ) : ?>
            <div id="checkout-customer-info" class="checkout-step-content active">
                <div class="col2-set" id="customer_details">
                    <div class="col-1">
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>

                    <div class="col-2">
                        <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                    </div>
                </div>

                <div class="checkout-navigation">
                    <button type="button" class="button next"><?php esc_html_e( 'Continue to Shipping', 'aqualuxe' ); ?></button>
                </div>
            </div>

            <div id="checkout-shipping" class="checkout-step-content">
                <h3><?php esc_html_e( 'Shipping Method', 'aqualuxe' ); ?></h3>
                <?php wc_cart_totals_shipping_html(); ?>

                <div class="checkout-navigation">
                    <button type="button" class="button prev"><?php esc_html_e( 'Back to Customer Info', 'aqualuxe' ); ?></button>
                    <button type="button" class="button next"><?php esc_html_e( 'Continue to Payment', 'aqualuxe' ); ?></button>
                </div>
            </div>

            <div id="checkout-payment" class="checkout-step-content">
                <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                
                <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
                
                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                </div>

                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

                <div class="checkout-navigation">
                    <button type="button" class="button prev"><?php esc_html_e( 'Back to Shipping', 'aqualuxe' ); ?></button>
                </div>
            </div>
        <?php else : ?>
            <div class="col2-set" id="customer_details">
                <div class="col-1">
                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                </div>

                <div class="col-2">
                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                </div>
            </div>

            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
            
            <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
            
            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        <?php endif; ?>

        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

    <?php endif; ?>
</form>

<?php
// Custom hook after checkout content
do_action( 'aqualuxe_after_checkout_content' );

do_action( 'woocommerce_after_checkout_form', $checkout );