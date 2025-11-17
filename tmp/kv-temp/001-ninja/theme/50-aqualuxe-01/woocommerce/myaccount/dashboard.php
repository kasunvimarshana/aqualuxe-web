<?php
/**
 * My Account Dashboard
 *
 * Shows the dashboard section of the My Account page.
 *
 * This template overrides /woocommerce/templates/myaccount/dashboard.php
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$current_user = wp_get_current_user();
$first_name = $current_user->first_name;
$display_name = $current_user->display_name;
$user_name = !empty($first_name) ? $first_name : $display_name;

// Get order counts
$customer_orders = wc_get_orders( array(
    'customer' => get_current_user_id(),
    'limit'    => -1,
) );

$order_count = count($customer_orders);
$completed_orders = 0;
$processing_orders = 0;

foreach ($customer_orders as $order) {
    if ($order->get_status() === 'completed') {
        $completed_orders++;
    } elseif ($order->get_status() === 'processing') {
        $processing_orders++;
    }
}

// Get download counts
$downloads = WC()->customer->get_downloadable_products();
$download_count = count($downloads);

// Get wishlist count if enabled
$wishlist_count = 0;
if (function_exists('aqualuxe_get_wishlist_count')) {
    $wishlist_count = aqualuxe_get_wishlist_count();
}
?>

<div class="woocommerce-welcome-message">
    <div class="user-avatar">
        <?php echo get_avatar($current_user->ID, 60); ?>
    </div>
    <div class="welcome-text">
        <h3><?php printf(esc_html__('Hello, %s!', 'aqualuxe'), esc_html($user_name)); ?></h3>
        <p><?php esc_html_e('Welcome to your account dashboard', 'aqualuxe'); ?></p>
    </div>
</div>

<div class="dashboard-overview">
    <div class="dashboard-card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h4><?php esc_html_e('Orders', 'aqualuxe'); ?></h4>
        </div>
        <div class="card-content">
            <div class="stat"><?php echo esc_html($order_count); ?></div>
            <div class="description"><?php esc_html_e('Total Orders', 'aqualuxe'); ?></div>
        </div>
        <div class="card-footer">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">
                <?php esc_html_e('View Orders', 'aqualuxe'); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <?php if ($processing_orders > 0) : ?>
    <div class="dashboard-card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h4><?php esc_html_e('Processing', 'aqualuxe'); ?></h4>
        </div>
        <div class="card-content">
            <div class="stat"><?php echo esc_html($processing_orders); ?></div>
            <div class="description"><?php esc_html_e('Orders in Progress', 'aqualuxe'); ?></div>
        </div>
        <div class="card-footer">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">
                <?php esc_html_e('Track Orders', 'aqualuxe'); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($download_count > 0) : ?>
    <div class="dashboard-card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fas fa-download"></i>
            </div>
            <h4><?php esc_html_e('Downloads', 'aqualuxe'); ?></h4>
        </div>
        <div class="card-content">
            <div class="stat"><?php echo esc_html($download_count); ?></div>
            <div class="description"><?php esc_html_e('Available Downloads', 'aqualuxe'); ?></div>
        </div>
        <div class="card-footer">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('downloads')); ?>">
                <?php esc_html_e('View Downloads', 'aqualuxe'); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($wishlist_count > 0) : ?>
    <div class="dashboard-card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h4><?php esc_html_e('Wishlist', 'aqualuxe'); ?></h4>
        </div>
        <div class="card-content">
            <div class="stat"><?php echo esc_html($wishlist_count); ?></div>
            <div class="description"><?php esc_html_e('Saved Items', 'aqualuxe'); ?></div>
        </div>
        <div class="card-footer">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('wishlist')); ?>">
                <?php esc_html_e('View Wishlist', 'aqualuxe'); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="dashboard-card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h4><?php esc_html_e('Addresses', 'aqualuxe'); ?></h4>
        </div>
        <div class="card-content">
            <div class="description"><?php esc_html_e('Manage your shipping and billing addresses', 'aqualuxe'); ?></div>
        </div>
        <div class="card-footer">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>">
                <?php esc_html_e('Manage Addresses', 'aqualuxe'); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <div class="dashboard-card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <h4><?php esc_html_e('Account Details', 'aqualuxe'); ?></h4>
        </div>
        <div class="card-content">
            <div class="description"><?php esc_html_e('Update your account information and password', 'aqualuxe'); ?></div>
        </div>
        <div class="card-footer">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>">
                <?php esc_html_e('Edit Details', 'aqualuxe'); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
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