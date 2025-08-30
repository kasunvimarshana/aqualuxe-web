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

// Get checkout layout from theme customizer
$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
$checkout_sticky_summary = get_theme_mod( 'aqualuxe_checkout_sticky_summary', true );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'aqualuxe' ) ) );
    return;
}

?>

<div class="aqualuxe-checkout-layout aqualuxe-checkout-layout-<?php echo esc_attr( $checkout_layout ); ?> <?php echo $checkout_sticky_summary ? 'aqualuxe-checkout-sticky-summary' : ''; ?>">
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <?php if ( $checkout->get_checkout_fields() ) : ?>

            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

            <div class="aqualuxe-checkout-container" id="customer_details">
                <div class="aqualuxe-checkout-form-column">
                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                    
                    <?php do_action( 'aqualuxe_checkout_before_payment' ); ?>
                    
                    <?php if ( 'standard' === $checkout_layout ) : ?>
                        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                        
                        <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
                        
                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                    <?php endif; ?>
                </div>
                
                <?php if ( 'two-column' === $checkout_layout ) : ?>
                <div class="aqualuxe-checkout-summary-column">
                    <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                    
                    <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
                    
                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </div>

                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                </div>
                <?php endif; ?>
            </div>

            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

        <?php endif; ?>
        
        <?php if ( 'multistep' === $checkout_layout ) : ?>
            <div class="aqualuxe-checkout-step aqualuxe-checkout-step-review">
                <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                
                <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
                
                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                </div>

                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
            </div>
        <?php endif; ?>

    </form>

    <?php do_action( 'aqualuxe_after_checkout_form' ); ?>
</div>