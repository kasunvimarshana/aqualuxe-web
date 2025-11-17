<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

    <?php
    if ( $order ) :

        do_action( 'woocommerce_before_thankyou', $order->get_id() );
        ?>

        <?php if ( $order->has_status( 'failed' ) ) : ?>

            <div class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 p-4 rounded-lg mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
                    <h2 class="text-xl font-bold"><?php esc_html_e( 'Order Failed', 'aqualuxe' ); ?></h2>
                </div>
                <p><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'aqualuxe' ); ?></p>
            </div>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions mb-6">
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors mr-2"><?php esc_html_e( 'Pay', 'aqualuxe' ); ?></a>
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay inline-block px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg transition-colors"><?php esc_html_e( 'My account', 'aqualuxe' ); ?></a>
                <?php endif; ?>
            </p>

        <?php else : ?>

            <div class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 p-6 rounded-lg text-center mb-8">
                <div class="order-success-icon text-4xl mb-4">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-serif font-bold mb-2">
                    <?php esc_html_e( 'Thank You!', 'aqualuxe' ); ?>
                </h1>
                <p class="text-lg">
                    <?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been received.', 'aqualuxe' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </p>
            </div>

            <div class="order-overview-wrapper grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="order-overview-item bg-white dark:bg-dark-light rounded-lg shadow-soft p-4">
                    <div class="order-overview-label text-sm text-gray-600 dark:text-gray-400 mb-1">
                        <?php esc_html_e( 'Order number:', 'aqualuxe' ); ?>
                    </div>
                    <div class="order-overview-value font-medium">
                        <?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                </div>

                <div class="order-overview-item bg-white dark:bg-dark-light rounded-lg shadow-soft p-4">
                    <div class="order-overview-label text-sm text-gray-600 dark:text-gray-400 mb-1">
                        <?php esc_html_e( 'Date:', 'aqualuxe' ); ?>
                    </div>
                    <div class="order-overview-value font-medium">
                        <?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                </div>

                <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                    <div class="order-overview-item bg-white dark:bg-dark-light rounded-lg shadow-soft p-4">
                        <div class="order-overview-label text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <?php esc_html_e( 'Email:', 'aqualuxe' ); ?>
                        </div>
                        <div class="order-overview-value font-medium">
                            <?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="order-overview-item bg-white dark:bg-dark-light rounded-lg shadow-soft p-4">
                    <div class="order-overview-label text-sm text-gray-600 dark:text-gray-400 mb-1">
                        <?php esc_html_e( 'Total:', 'aqualuxe' ); ?>
                    </div>
                    <div class="order-overview-value font-medium text-primary">
                        <?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                </div>

                <?php if ( $order->get_payment_method_title() ) : ?>
                    <div class="order-overview-item bg-white dark:bg-dark-light rounded-lg shadow-soft p-4">
                        <div class="order-overview-label text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <?php esc_html_e( 'Payment method:', 'aqualuxe' ); ?>
                        </div>
                        <div class="order-overview-value font-medium">
                            <?php echo wp_kses_post( $order->get_payment_method_title() ); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; ?>

        <div class="order-details-wrapper bg-white dark:bg-dark-light rounded-lg shadow-soft p-6 mb-8">
            <h2 class="text-xl font-serif font-bold mb-4"><?php esc_html_e( 'Order Details', 'aqualuxe' ); ?></h2>
            <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
            <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
        </div>

    <?php else : ?>

        <div class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 p-6 rounded-lg text-center mb-8">
            <div class="order-success-icon text-4xl mb-4">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-serif font-bold mb-2">
                <?php esc_html_e( 'Thank You!', 'aqualuxe' ); ?>
            </h1>
            <p class="text-lg">
                <?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been received.', 'aqualuxe' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </p>
        </div>

    <?php endif; ?>

</div>