<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
?>

<div class="woocommerce-welcome-message">
    <?php
    printf(
        /* translators: 1: user display name 2: logout url */
        wp_kses_post( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'aqualuxe' ) ),
        '<strong>' . esc_html( $current_user->display_name ) . '</strong>',
        esc_url( wc_logout_url() )
    );
    ?>
</div>

<div class="dashboard-content">
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <div class="dashboard-card-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="dashboard-card-content">
                <h3><?php esc_html_e( 'Orders', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'View and track your orders', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="button"><?php esc_html_e( 'View Orders', 'aqualuxe' ); ?></a>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="dashboard-card-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="dashboard-card-content">
                <h3><?php esc_html_e( 'Addresses', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'Manage your shipping and billing addresses', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="button"><?php esc_html_e( 'View Addresses', 'aqualuxe' ); ?></a>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="dashboard-card-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="dashboard-card-content">
                <h3><?php esc_html_e( 'Account Details', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'Update your account information', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="button"><?php esc_html_e( 'Edit Account', 'aqualuxe' ); ?></a>
            </div>
        </div>
        
        <?php if ( class_exists( 'WC_Wishlist_Plugin' ) ) : ?>
        <div class="dashboard-card">
            <div class="dashboard-card-icon">
                <i class="fas fa-heart"></i>
            </div>
            <div class="dashboard-card-content">
                <h3><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'View your saved items', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'wishlist' ) ); ?>" class="button"><?php esc_html_e( 'View Wishlist', 'aqualuxe' ); ?></a>
            </div>
        </div>
        <?php endif; ?>
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
    
    <?php
    // Get recent orders
    $customer_orders = wc_get_orders( array(
        'customer' => get_current_user_id(),
        'limit'    => 5,
        'status'   => array( 'wc-processing', 'wc-completed' ),
    ) );
    
    if ( $customer_orders ) : ?>
        <div class="recent-orders">
            <h3><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h3>
            <table class="shop_table shop_table_responsive my_account_orders">
                <thead>
                    <tr>
                        <th class="order-number"><span class="nobr"><?php esc_html_e( 'Order', 'aqualuxe' ); ?></span></th>
                        <th class="order-date"><span class="nobr"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></span></th>
                        <th class="order-status"><span class="nobr"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></span></th>
                        <th class="order-total"><span class="nobr"><?php esc_html_e( 'Total', 'aqualuxe' ); ?></span></th>
                        <th class="order-actions"><span class="nobr"><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $customer_orders as $customer_order ) :
                        $order = wc_get_order( $customer_order );
                        $item_count = $order->get_item_count();
                        ?>
                        <tr class="order">
                            <td class="order-number" data-title="<?php esc_attr_e( 'Order', 'aqualuxe' ); ?>">
                                <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                                    <?php echo esc_html( _x( '#', 'hash before order number', 'aqualuxe' ) . $order->get_order_number() ); ?>
                                </a>
                            </td>
                            <td class="order-date" data-title="<?php esc_attr_e( 'Date', 'aqualuxe' ); ?>">
                                <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
                            </td>
                            <td class="order-status" data-title="<?php esc_attr_e( 'Status', 'aqualuxe' ); ?>">
                                <span class="woocommerce-OrderStatus status-<?php echo esc_attr( $order->get_status() ); ?>">
                                    <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                                </span>
                            </td>
                            <td class="order-total" data-title="<?php esc_attr_e( 'Total', 'aqualuxe' ); ?>">
                                <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
                            </td>
                            <td class="order-actions" data-title="<?php esc_attr_e( 'Actions', 'aqualuxe' ); ?>">
                                <?php
                                $actions = wc_get_account_orders_actions( $order );
                                
                                if ( ! empty( $actions ) ) {
                                    foreach ( $actions as $key => $action ) {
                                        echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="view-all-link"><?php esc_html_e( 'View all orders', 'aqualuxe' ); ?> <i class="fas fa-arrow-right"></i></a>
        </div>
    <?php endif; ?>
</div>