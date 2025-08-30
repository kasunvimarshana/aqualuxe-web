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

defined( 'ABSPATH' ) || exit;

/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_dashboard' );

// Get customer information
$customer = wp_get_current_user();
$first_name = $customer->first_name;
$display_name = $customer->display_name;
$name = $first_name ? $first_name : $display_name;

// Get order count
$order_count = wc_get_customer_order_count( $customer->ID );

// Get recent orders
$recent_orders = wc_get_orders( array(
    'customer' => $customer->ID,
    'limit'    => 5,
    'orderby'  => 'date',
    'order'    => 'DESC',
) );

// Get downloads
$downloads = WC()->customer->get_downloadable_products();
?>

<div class="woocommerce-MyAccount-dashboard">
    <div class="woocommerce-welcome-message">
        <h2><?php printf( esc_html__( 'Hello %s', 'aqualuxe' ), esc_html( $name ) ); ?></h2>
        <p>
            <?php
            printf(
                /* translators: 1: user display name 2: logout url */
                wp_kses_post( __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'aqualuxe' ) ),
                esc_url( wc_get_endpoint_url( 'orders' ) ),
                esc_url( wc_get_endpoint_url( 'edit-address' ) ),
                esc_url( wc_get_endpoint_url( 'edit-account' ) )
            );
            ?>
        </p>
    </div>

    <div class="dashboard-widgets">
        <!-- Recent Orders Widget -->
        <div class="dashboard-widget recent-orders-widget">
            <h3><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h3>
            <?php if ( $recent_orders ) : ?>
                <table class="shop_table shop_table_responsive my_account_orders">
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
                        <?php foreach ( $recent_orders as $order ) : ?>
                            <tr>
                                <td data-title="<?php esc_attr_e( 'Order', 'aqualuxe' ); ?>">
                                    <?php echo esc_html( _x( '#', 'hash before order number', 'aqualuxe' ) . $order->get_order_number() ); ?>
                                </td>
                                <td data-title="<?php esc_attr_e( 'Date', 'aqualuxe' ); ?>">
                                    <?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
                                </td>
                                <td data-title="<?php esc_attr_e( 'Status', 'aqualuxe' ); ?>">
                                    <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                                </td>
                                <td data-title="<?php esc_attr_e( 'Total', 'aqualuxe' ); ?>">
                                    <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
                                </td>
                                <td data-title="<?php esc_attr_e( 'Actions', 'aqualuxe' ); ?>">
                                    <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="button"><?php esc_html_e( 'View', 'aqualuxe' ); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="button"><?php esc_html_e( 'View All Orders', 'aqualuxe' ); ?></a>
            <?php else : ?>
                <p><?php esc_html_e( 'No order has been made yet.', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="button"><?php esc_html_e( 'Browse Products', 'aqualuxe' ); ?></a>
            <?php endif; ?>
        </div>

        <!-- Account Info Widget -->
        <div class="dashboard-widget account-info-widget">
            <h3><?php esc_html_e( 'Account Information', 'aqualuxe' ); ?></h3>
            <p><strong><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $customer->user_email ); ?></p>
            
            <?php
            $billing_address = wc_get_account_formatted_address( 'billing' );
            $shipping_address = wc_get_account_formatted_address( 'shipping' );
            ?>
            
            <?php if ( $billing_address ) : ?>
                <p><strong><?php esc_html_e( 'Billing Address:', 'aqualuxe' ); ?></strong><br>
                <?php echo wp_kses_post( $billing_address ); ?></p>
            <?php endif; ?>
            
            <?php if ( $shipping_address && $shipping_address !== $billing_address ) : ?>
                <p><strong><?php esc_html_e( 'Shipping Address:', 'aqualuxe' ); ?></strong><br>
                <?php echo wp_kses_post( $shipping_address ); ?></p>
            <?php endif; ?>
            
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="button"><?php esc_html_e( 'Edit Account Details', 'aqualuxe' ); ?></a>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="button"><?php esc_html_e( 'Manage Addresses', 'aqualuxe' ); ?></a>
        </div>

        <!-- Downloads Widget -->
        <?php if ( $downloads ) : ?>
            <div class="dashboard-widget downloads-widget">
                <h3><?php esc_html_e( 'Available Downloads', 'aqualuxe' ); ?></h3>
                <ul class="digital-downloads">
                    <?php foreach ( $downloads as $download ) : ?>
                        <li>
                            <?php
                            do_action( 'woocommerce_available_download_start', $download );

                            if ( is_numeric( $download['downloads_remaining'] ) ) {
                                /* translators: %s: downloads remaining */
                                echo '<span class="downloads-remaining">' . sprintf( _n( '%s download remaining', '%s downloads remaining', $download['downloads_remaining'], 'aqualuxe' ), $download['downloads_remaining'] ) . '</span>';
                            }

                            echo '<a href="' . esc_url( $download['download_url'] ) . '">' . esc_html( $download['download_name'] ) . '</a>';

                            do_action( 'woocommerce_available_download_end', $download );
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'downloads' ) ); ?>" class="button"><?php esc_html_e( 'View All Downloads', 'aqualuxe' ); ?></a>
            </div>
        <?php endif; ?>

        <!-- Wishlist Widget -->
        <?php if ( function_exists( 'aqualuxe_get_wishlist' ) && aqualuxe_get_option( 'enable_product_wishlist', true ) ) : 
            $wishlist = aqualuxe_get_wishlist();
            if ( ! empty( $wishlist ) ) :
                $wishlist_products = wc_get_products( array(
                    'include' => $wishlist,
                    'limit'   => 5,
                ) );
            ?>
                <div class="dashboard-widget wishlist-widget">
                    <h3><?php esc_html_e( 'Your Wishlist', 'aqualuxe' ); ?></h3>
                    <?php if ( $wishlist_products ) : ?>
                        <ul class="wishlist-products">
                            <?php foreach ( $wishlist_products as $product ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                        <?php echo $product->get_image( 'thumbnail' ); ?>
                                        <span class="product-title"><?php echo esc_html( $product->get_name() ); ?></span>
                                    </a>
                                    <span class="product-price"><?php echo $product->get_price_html(); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?php echo esc_url( wc_get_endpoint_url( 'wishlist' ) ); ?>" class="button"><?php esc_html_e( 'View Full Wishlist', 'aqualuxe' ); ?></a>
                    <?php else : ?>
                        <p><?php esc_html_e( 'Your wishlist is empty.', 'aqualuxe' ); ?></p>
                        <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="button"><?php esc_html_e( 'Browse Products', 'aqualuxe' ); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_after_account_dashboard' );