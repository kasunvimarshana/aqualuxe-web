<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$allowed_html = array(
    'a' => array(
        'href' => array(),
        'class' => array(),
    ),
);
?>

<div class="woocommerce-account-dashboard">
    <div class="account-welcome bg-white dark:bg-dark-light rounded-lg shadow-soft p-6 mb-8">
        <div class="account-welcome-header flex items-center mb-4">
            <div class="account-avatar w-16 h-16 rounded-full bg-primary text-white flex items-center justify-center text-2xl mr-4">
                <?php echo esc_html( substr( $current_user->display_name, 0, 1 ) ); ?>
            </div>
            <div class="account-welcome-text">
                <h1 class="text-2xl font-serif font-bold mb-1">
                    <?php
                    printf(
                        /* translators: %s: Customer username */
                        esc_html__( 'Hello %s,', 'aqualuxe' ),
                        '<span class="text-primary">' . esc_html( $current_user->display_name ) . '</span>'
                    );
                    ?>
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    <?php
                    printf(
                        /* translators: 1: Orders URL 2: Address URL 3: Account URL. */
                        __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'aqualuxe' ),
                        esc_url( wc_get_endpoint_url( 'orders' ) ),
                        esc_url( wc_get_endpoint_url( 'edit-address' ) ),
                        esc_url( wc_get_endpoint_url( 'edit-account' ) )
                    );
                    ?>
                </p>
            </div>
        </div>
    </div>

    <div class="account-overview grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="account-overview-item bg-white dark:bg-dark-light rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
            <div class="account-overview-icon text-3xl text-primary mb-4">
                <i class="fas fa-box"></i>
            </div>
            <h3 class="account-overview-title text-lg font-bold mb-2"><?php esc_html_e( 'Orders', 'aqualuxe' ); ?></h3>
            <p class="account-overview-description text-gray-600 dark:text-gray-400 mb-4"><?php esc_html_e( 'View and track your orders and download items', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="account-overview-link inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                <?php esc_html_e( 'View Orders', 'aqualuxe' ); ?>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="account-overview-item bg-white dark:bg-dark-light rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
            <div class="account-overview-icon text-3xl text-primary mb-4">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h3 class="account-overview-title text-lg font-bold mb-2"><?php esc_html_e( 'Addresses', 'aqualuxe' ); ?></h3>
            <p class="account-overview-description text-gray-600 dark:text-gray-400 mb-4"><?php esc_html_e( 'Manage your billing and shipping addresses', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="account-overview-link inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                <?php esc_html_e( 'Manage Addresses', 'aqualuxe' ); ?>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="account-overview-item bg-white dark:bg-dark-light rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
            <div class="account-overview-icon text-3xl text-primary mb-4">
                <i class="fas fa-user"></i>
            </div>
            <h3 class="account-overview-title text-lg font-bold mb-2"><?php esc_html_e( 'Account Details', 'aqualuxe' ); ?></h3>
            <p class="account-overview-description text-gray-600 dark:text-gray-400 mb-4"><?php esc_html_e( 'Update your profile and password', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="account-overview-link inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                <?php esc_html_e( 'Edit Details', 'aqualuxe' ); ?>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="account-overview-item bg-white dark:bg-dark-light rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
            <div class="account-overview-icon text-3xl text-primary mb-4">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h3 class="account-overview-title text-lg font-bold mb-2"><?php esc_html_e( 'Logout', 'aqualuxe' ); ?></h3>
            <p class="account-overview-description text-gray-600 dark:text-gray-400 mb-4"><?php esc_html_e( 'Securely log out of your account', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="account-overview-link inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                <?php esc_html_e( 'Logout', 'aqualuxe' ); ?>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <?php
        /**
         * My Account dashboard.
         *
         * @since 2.6.0
         */
        do_action( 'woocommerce_account_dashboard' );

        /**
         * Deprecated woocommerce_before_my_account action.
         *
         * @deprecated 2.6.0
         */
        do_action( 'woocommerce_before_my_account' );

        /**
         * Deprecated woocommerce_after_my_account action.
         *
         * @deprecated 2.6.0
         */
        do_action( 'woocommerce_after_my_account' );
    ?>

    <?php if ( wc_get_customer_order_count( get_current_user_id() ) > 0 ) : ?>
        <div class="recent-orders bg-white dark:bg-dark-light rounded-lg shadow-soft p-6">
            <h2 class="text-xl font-serif font-bold mb-4"><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h2>
            <?php
            $args = array(
                'customer_id' => get_current_user_id(),
                'limit'       => 5,
            );
            $customer_orders = wc_get_orders( $args );

            if ( ! empty( $customer_orders ) ) : ?>
                <div class="recent-orders-table overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 px-4"><?php esc_html_e( 'Order', 'aqualuxe' ); ?></th>
                                <th class="text-left py-3 px-4"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></th>
                                <th class="text-left py-3 px-4"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
                                <th class="text-left py-3 px-4"><?php esc_html_e( 'Total', 'aqualuxe' ); ?></th>
                                <th class="text-left py-3 px-4"><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $customer_orders as $customer_order ) :
                                $order = wc_get_order( $customer_order );
                                $item_count = $order->get_item_count();
                                ?>
                                <tr class="border-b border-gray-200 dark:border-gray-700 last:border-0">
                                    <td class="py-3 px-4">
                                        <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="text-primary hover:text-primary-dark transition-colors">
                                            <?php echo esc_html( _x( '#', 'hash before order number', 'aqualuxe' ) . $order->get_order_number() ); ?>
                                        </a>
                                    </td>
                                    <td class="py-3 px-4">
                                        <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php
                                        $status = $order->get_status();
                                        $status_class = '';
                                        
                                        switch ( $status ) {
                                            case 'completed':
                                                $status_class = 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
                                                break;
                                            case 'processing':
                                                $status_class = 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400';
                                                break;
                                            case 'on-hold':
                                                $status_class = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400';
                                                break;
                                            case 'pending':
                                                $status_class = 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400';
                                                break;
                                            case 'cancelled':
                                            case 'refunded':
                                            case 'failed':
                                                $status_class = 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400';
                                                break;
                                            default:
                                                $status_class = 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                                        }
                                        ?>
                                        <span class="order-status inline-block px-2 py-1 rounded-full text-xs font-medium <?php echo esc_attr( $status_class ); ?>">
                                            <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php
                                        /* translators: 1: formatted order total 2: total order items */
                                        printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'aqualuxe' ), $order->get_formatted_order_total(), $item_count );
                                        ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="inline-block px-3 py-1 bg-primary hover:bg-primary-dark text-white text-sm rounded transition-colors">
                                            <?php esc_html_e( 'View', 'aqualuxe' ); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="view-all-orders mt-4 text-right">
                    <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                        <?php esc_html_e( 'View All Orders', 'aqualuxe' ); ?>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            <?php else : ?>
                <div class="no-orders text-center py-8">
                    <div class="no-orders-icon text-4xl text-gray-300 dark:text-gray-600 mb-3">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <p class="no-orders-text text-gray-600 dark:text-gray-400 mb-4">
                        <?php esc_html_e( 'You haven\'t placed any orders yet.', 'aqualuxe' ); ?>
                    </p>
                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                        <?php esc_html_e( 'Browse Products', 'aqualuxe' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>