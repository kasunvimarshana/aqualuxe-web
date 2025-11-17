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
    ),
);

// Get account dashboard settings from theme customizer
$show_account_stats = get_theme_mod( 'aqualuxe_account_stats', true );
$show_recent_orders = get_theme_mod( 'aqualuxe_account_recent_orders', true );
$show_account_info = get_theme_mod( 'aqualuxe_account_info', true );
$show_wishlist = get_theme_mod( 'aqualuxe_account_wishlist', true );
$recent_orders_count = get_theme_mod( 'aqualuxe_account_recent_orders_count', 5 );
?>

<div class="aqualuxe-account-dashboard">
    <div class="aqualuxe-account-welcome">
        <h2><?php
            printf(
                /* translators: 1: user display name 2: logout url */
                wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'aqualuxe' ), $allowed_html ),
                '<strong>' . esc_html( $current_user->display_name ) . '</strong>',
                esc_url( wc_logout_url() )
            );
        ?></h2>

        <p><?php
            printf(
                /* translators: 1: Orders URL 2: Address URL 3: Account URL. */
                __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'aqualuxe' ),
                esc_url( wc_get_endpoint_url( 'orders' ) ),
                esc_url( wc_get_endpoint_url( 'edit-address' ) ),
                esc_url( wc_get_endpoint_url( 'edit-account' ) )
            );
        ?></p>
    </div>

    <?php if ( $show_account_stats ) : ?>
    <div class="aqualuxe-account-stats">
        <div class="aqualuxe-account-stat-item">
            <div class="aqualuxe-account-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            </div>
            <div class="aqualuxe-account-stat-content">
                <h4><?php esc_html_e( 'Orders', 'aqualuxe' ); ?></h4>
                <p><?php 
                    $order_count = wc_get_customer_order_count( $current_user->ID );
                    echo esc_html( $order_count );
                ?></p>
            </div>
        </div>
        
        <div class="aqualuxe-account-stat-item">
            <div class="aqualuxe-account-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            </div>
            <div class="aqualuxe-account-stat-content">
                <h4><?php esc_html_e( 'Total Spent', 'aqualuxe' ); ?></h4>
                <p><?php 
                    $total_spent = wc_get_customer_total_spent( $current_user->ID );
                    echo wp_kses_post( wc_price( $total_spent ) );
                ?></p>
            </div>
        </div>
        
        <?php if ( $show_wishlist && function_exists( 'aqualuxe_get_wishlist_count' ) ) : ?>
        <div class="aqualuxe-account-stat-item">
            <div class="aqualuxe-account-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </div>
            <div class="aqualuxe-account-stat-content">
                <h4><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></h4>
                <p><?php 
                    $wishlist_count = aqualuxe_get_wishlist_count( $current_user->ID );
                    echo esc_html( $wishlist_count );
                ?></p>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="aqualuxe-account-stat-item">
            <div class="aqualuxe-account-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="aqualuxe-account-stat-content">
                <h4><?php esc_html_e( 'Member Since', 'aqualuxe' ); ?></h4>
                <p><?php 
                    $user_registered = $current_user->user_registered;
                    echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $user_registered ) ) );
                ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ( $show_recent_orders ) : ?>
    <div class="aqualuxe-account-recent-orders">
        <h3><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h3>
        <?php
        $customer_orders = wc_get_orders( array(
            'customer' => $current_user->ID,
            'limit' => $recent_orders_count,
            'orderby' => 'date',
            'order' => 'DESC',
        ) );

        if ( ! empty( $customer_orders ) ) : ?>
            <table class="aqualuxe-account-orders-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Order', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Date', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Total', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ( $customer_orders as $customer_order ) {
                        $order = wc_get_order( $customer_order );
                        $item_count = $order->get_item_count();
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                                    <?php echo esc_html( _x( '#', 'hash before order number', 'aqualuxe' ) . $order->get_order_number() ); ?>
                                </a>
                            </td>
                            <td>
                                <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
                            </td>
                            <td>
                                <span class="aqualuxe-order-status aqualuxe-order-status-<?php echo esc_attr( $order->get_status() ); ?>">
                                    <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                /* translators: 1: formatted order total 2: total order items */
                                printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'aqualuxe' ), $order->get_formatted_order_total(), $item_count );
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="aqualuxe-button aqualuxe-button-small">
                                    <?php esc_html_e( 'View', 'aqualuxe' ); ?>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="aqualuxe-button">
                <?php esc_html_e( 'View All Orders', 'aqualuxe' ); ?>
            </a>
        <?php else : ?>
            <div class="aqualuxe-no-orders">
                <p><?php esc_html_e( 'No order has been made yet.', 'aqualuxe' ); ?></p>
                <a class="aqualuxe-button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                    <?php esc_html_e( 'Browse products', 'aqualuxe' ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ( $show_account_info ) : ?>
    <div class="aqualuxe-account-info">
        <h3><?php esc_html_e( 'Account Information', 'aqualuxe' ); ?></h3>
        <div class="aqualuxe-account-info-container">
            <div class="aqualuxe-account-info-column">
                <h4><?php esc_html_e( 'Contact Information', 'aqualuxe' ); ?></h4>
                <p>
                    <strong><?php echo esc_html( $current_user->display_name ); ?></strong><br>
                    <?php echo esc_html( $current_user->user_email ); ?>
                </p>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="aqualuxe-edit-link">
                    <?php esc_html_e( 'Edit', 'aqualuxe' ); ?>
                </a>
            </div>
            
            <div class="aqualuxe-account-info-column">
                <h4><?php esc_html_e( 'Default Billing Address', 'aqualuxe' ); ?></h4>
                <?php
                $billing_address = wc_get_account_formatted_address( 'billing' );
                if ( $billing_address ) {
                    echo wp_kses_post( $billing_address );
                } else {
                    esc_html_e( 'You have not set up this type of address yet.', 'aqualuxe' );
                }
                ?>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" class="aqualuxe-edit-link">
                    <?php esc_html_e( 'Edit', 'aqualuxe' ); ?>
                </a>
            </div>
            
            <div class="aqualuxe-account-info-column">
                <h4><?php esc_html_e( 'Default Shipping Address', 'aqualuxe' ); ?></h4>
                <?php
                $shipping_address = wc_get_account_formatted_address( 'shipping' );
                if ( $shipping_address ) {
                    echo wp_kses_post( $shipping_address );
                } else {
                    esc_html_e( 'You have not set up this type of address yet.', 'aqualuxe' );
                }
                ?>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping' ) ); ?>" class="aqualuxe-edit-link">
                    <?php esc_html_e( 'Edit', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

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
</div>