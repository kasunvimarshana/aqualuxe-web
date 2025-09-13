<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$allowed_html = array(
    'a' => array(
        'href' => array(),
    ),
);
?>

<div class="account-dashboard">
    
    <!-- Welcome Section -->
    <div class="dashboard-welcome bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-800 dark:to-gray-900 rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <?php
            printf(
                /* translators: 1: user display name */
                esc_html__('Hello %1$s', 'aqualuxe'),
                '<strong>' . esc_html($current_user->display_name) . '</strong>'
            );
            ?>
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
            <?php esc_html_e('Welcome to your AquaLuxe account dashboard. Manage your orders, addresses, and account settings from here.', 'aqualuxe'); ?>
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="dashboard-stats grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Total Orders -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100 dark:bg-blue-900 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <?php echo esc_html(wc_get_customer_order_count(get_current_user_id())); ?>
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Total Orders', 'aqualuxe'); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100 dark:bg-green-900 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <?php echo wp_kses_post(wc_price(wc_get_customer_total_spent(get_current_user_id()))); ?>
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Total Spent', 'aqualuxe'); ?></p>
                </div>
            </div>
        </div>

        <!-- Wishlist Items -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="stat-icon bg-red-100 dark:bg-red-900 p-3 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div>
                    <?php
                    $wishlist = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
                    $wishlist_count = is_array($wishlist) ? count($wishlist) : 0;
                    ?>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <?php echo esc_html($wishlist_count); ?>
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Wishlist Items', 'aqualuxe'); ?></p>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Orders -->
    <?php
    $recent_orders = wc_get_orders(array(
        'customer' => get_current_user_id(),
        'limit' => 5,
        'status' => array('wc-processing', 'wc-on-hold', 'wc-completed'),
    ));

    if ($recent_orders) : ?>
        <div class="recent-orders bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <?php esc_html_e('Recent Orders', 'aqualuxe'); ?>
                </h3>
                <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    <?php esc_html_e('View All Orders', 'aqualuxe'); ?>
                </a>
            </div>
            
            <div class="orders-list space-y-4">
                <?php foreach ($recent_orders as $order) : ?>
                    <div class="order-item flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="order-info">
                            <h4 class="font-medium text-gray-900 dark:text-white">
                                <?php printf(esc_html__('Order #%s', 'aqualuxe'), esc_html($order->get_order_number())); ?>
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <?php echo esc_html($order->get_date_created()->date_i18n(get_option('date_format'))); ?>
                            </p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php
                                $status = $order->get_status();
                                switch ($status) {
                                    case 'processing':
                                        echo 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100';
                                        break;
                                    case 'completed':
                                        echo 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
                                        break;
                                    case 'on-hold':
                                        echo 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100';
                                        break;
                                    default:
                                        echo 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                                }
                                ?>">
                                <?php echo esc_html(wc_get_order_status_name($status)); ?>
                            </span>
                        </div>
                        <div class="order-total">
                            <span class="font-semibold text-gray-900 dark:text-white">
                                <?php echo wp_kses_post($order->get_formatted_order_total()); ?>
                            </span>
                        </div>
                        <div class="order-actions">
                            <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                <?php esc_html_e('View', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
            <?php esc_html_e('Quick Actions', 'aqualuxe'); ?>
        </h3>
        
        <div class="actions-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- View Orders -->
            <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="action-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="action-icon bg-blue-100 dark:bg-blue-900 p-3 rounded-lg mb-4 w-fit">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2"><?php esc_html_e('View Orders', 'aqualuxe'); ?></h4>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Track your order history and status', 'aqualuxe'); ?></p>
            </a>

            <!-- Edit Addresses -->
            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address')); ?>" class="action-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="action-icon bg-green-100 dark:bg-green-900 p-3 rounded-lg mb-4 w-fit">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2"><?php esc_html_e('Addresses', 'aqualuxe'); ?></h4>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Manage your billing and shipping addresses', 'aqualuxe'); ?></p>
            </a>

            <!-- Account Details -->
            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="action-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="action-icon bg-purple-100 dark:bg-purple-900 p-3 rounded-lg mb-4 w-fit">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2"><?php esc_html_e('Account Details', 'aqualuxe'); ?></h4>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Update your personal information', 'aqualuxe'); ?></p>
            </a>

            <!-- Browse Products -->
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="action-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="action-icon bg-cyan-100 dark:bg-cyan-900 p-3 rounded-lg mb-4 w-fit">
                    <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-2"><?php esc_html_e('Browse Products', 'aqualuxe'); ?></h4>
                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Discover our luxury aquatic collection', 'aqualuxe'); ?></p>
            </a>

        </div>
    </div>

</div>

<p>
    <?php
    printf(
        /* translators: 1: Orders URL 2: Address URL 3: Account URL. */
        wp_kses(__('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'aqualuxe'), $allowed_html),
        esc_url(wc_get_endpoint_url('orders')),
        esc_url(wc_get_endpoint_url('edit-address')),
        esc_url(wc_get_endpoint_url('edit-account'))
    );
    ?>
</p>