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

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'aqualuxe' ) ) );
    return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <div class="checkout-wrapper grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="checkout-details col-span-1 lg:col-span-7">
            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="customer-details bg-white dark:bg-dark-light rounded-lg shadow-soft p-6 mb-8" id="customer_details">
                    <div class="checkout-section mb-8">
                        <h3 class="text-xl font-serif font-bold mb-4"><?php esc_html_e( 'Billing Details', 'aqualuxe' ); ?></h3>
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>

                    <div class="checkout-section">
                        <h3 class="text-xl font-serif font-bold mb-4"><?php esc_html_e( 'Shipping Details', 'aqualuxe' ); ?></h3>
                        <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                    </div>
                </div>

                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <?php endif; ?>
            
            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
        </div>

        <div class="checkout-sidebar col-span-1 lg:col-span-5">
            <div class="order-review bg-white dark:bg-dark-light rounded-lg shadow-soft p-6 sticky top-8">
                <h3 id="order_review_heading" class="text-xl font-serif font-bold mb-4"><?php esc_html_e( 'Your Order', 'aqualuxe' ); ?></h3>
                
                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                </div>

                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
            </div>
        </div>
    </div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>