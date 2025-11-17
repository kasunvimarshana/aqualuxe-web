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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$allowed_html = array(
    'a' => array(
        'href' => array(),
        'class' => array(),
    ),
);
?>

<div class="account-dashboard">
    <div class="account-dashboard__welcome">
        <div class="account-dashboard__welcome-content">
            <h2 class="account-dashboard__welcome-title">
                <?php
                printf(
                    /* translators: 1: user display name 2: logout url */
                    wp_kses(__('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'aqualuxe'), $allowed_html),
                    '<span class="account-dashboard__welcome-name">' . esc_html($current_user->display_name) . '</span>',
                    esc_url(wc_logout_url())
                );
                ?>
            </h2>
            <p class="account-dashboard__welcome-text">
                <?php
                printf(
                    /* translators: 1: Orders URL 2: Address URL 3: Account URL. */
                    wp_kses(__('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'aqualuxe'), $allowed_html),
                    esc_url(wc_get_endpoint_url('orders')),
                    esc_url(wc_get_endpoint_url('edit-address')),
                    esc_url(wc_get_endpoint_url('edit-account'))
                );
                ?>
            </p>
        </div>
        <div class="account-dashboard__welcome-image">
            <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/account-welcome.jpg')); ?>" alt="<?php esc_attr_e('Welcome', 'aqualuxe'); ?>">
        </div>
    </div>

    <div class="account-dashboard__quick-links">
        <div class="account-dashboard__quick-link">
            <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="account-dashboard__quick-link-inner">
                <div class="account-dashboard__quick-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h8v2H8v-2z"/></svg>
                </div>
                <h3 class="account-dashboard__quick-link-title"><?php esc_html_e('Orders', 'aqualuxe'); ?></h3>
                <p class="account-dashboard__quick-link-text"><?php esc_html_e('View and track your orders', 'aqualuxe'); ?></p>
            </a>
        </div>

        <div class="account-dashboard__quick-link">
            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address')); ?>" class="account-dashboard__quick-link-inner">
                <div class="account-dashboard__quick-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 20.9l4.95-4.95a7 7 0 1 0-9.9 0L12 20.9zm0 2.828l-6.364-6.364a9 9 0 1 1 12.728 0L12 23.728zM12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/></svg>
                </div>
                <h3 class="account-dashboard__quick-link-title"><?php esc_html_e('Addresses', 'aqualuxe'); ?></h3>
                <p class="account-dashboard__quick-link-text"><?php esc_html_e('Manage your shipping and billing addresses', 'aqualuxe'); ?></p>
            </a>
        </div>

        <div class="account-dashboard__quick-link">
            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="account-dashboard__quick-link-inner">
                <div class="account-dashboard__quick-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-4.987-3.744A7.966 7.966 0 0 0 12 20c1.97 0 3.773-.712 5.167-1.892A6.979 6.979 0 0 0 12.16 16a6.981 6.981 0 0 0-5.147 2.256zM5.616 16.82A8.975 8.975 0 0 1 12.16 14a8.972 8.972 0 0 1 6.362 2.634 8 8 0 1 0-12.906.187zM12 13a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                </div>
                <h3 class="account-dashboard__quick-link-title"><?php esc_html_e('Account Details', 'aqualuxe'); ?></h3>
                <p class="account-dashboard__quick-link-text"><?php esc_html_e('Update your profile and password', 'aqualuxe'); ?></p>
            </a>
        </div>

        <div class="account-dashboard__quick-link">
            <a href="<?php echo esc_url(wc_get_endpoint_url('wishlist')); ?>" class="account-dashboard__quick-link-inner">
                <div class="account-dashboard__quick-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228zm6.826 1.641c-1.5-1.502-3.92-1.563-5.49-.153l-1.335 1.198-1.336-1.197c-1.575-1.412-3.99-1.35-5.494.154-1.49 1.49-1.565 3.875-.192 5.451L12 18.654l7.02-7.03c1.374-1.577 1.299-3.959-.193-5.454z"/></svg>
                </div>
                <h3 class="account-dashboard__quick-link-title"><?php esc_html_e('Wishlist', 'aqualuxe'); ?></h3>
                <p class="account-dashboard__quick-link-text"><?php esc_html_e('View your saved items', 'aqualuxe'); ?></p>
            </a>
        </div>
    </div>

    <?php if (wc_get_customer_order_count($current_user->ID) > 0) : ?>
        <div class="account-dashboard__recent-orders">
            <h3 class="account-dashboard__section-title"><?php esc_html_e('Recent Orders', 'aqualuxe'); ?></h3>
            
            <?php
            $customer_orders = wc_get_orders(array(
                'customer' => $current_user->ID,
                'limit' => 5,
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            if (!empty($customer_orders)) : ?>
                <div class="account-dashboard__orders-table">
                    <table>
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Order', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Total', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($customer_orders as $customer_order) :
                                $order = wc_get_order($customer_order);
                                $item_count = $order->get_item_count();
                                ?>
                                <tr>
                                    <td data-title="<?php esc_attr_e('Order', 'aqualuxe'); ?>">
                                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                                            <?php echo esc_html(_x('#', 'hash before order number', 'aqualuxe') . $order->get_order_number()); ?>
                                        </a>
                                    </td>
                                    <td data-title="<?php esc_attr_e('Date', 'aqualuxe'); ?>">
                                        <time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></time>
                                    </td>
                                    <td data-title="<?php esc_attr_e('Status', 'aqualuxe'); ?>">
                                        <span class="order-status order-status--<?php echo esc_attr($order->get_status()); ?>">
                                            <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                                        </span>
                                    </td>
                                    <td data-title="<?php esc_attr_e('Total', 'aqualuxe'); ?>">
                                        <?php
                                        /* translators: 1: formatted order total 2: total order items */
                                        printf(_n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'aqualuxe'), $order->get_formatted_order_total(), $item_count);
                                        ?>
                                    </td>
                                    <td data-title="<?php esc_attr_e('Actions', 'aqualuxe'); ?>">
                                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn btn-sm btn-outline">
                                            <?php esc_html_e('View', 'aqualuxe'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="account-dashboard__view-all">
                    <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="btn btn-primary">
                        <?php esc_html_e('View All Orders', 'aqualuxe'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="account-dashboard__sections">
        <div class="account-dashboard__section">
            <div class="account-dashboard__section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm17 8H4v8h16v-8zm0-2V5H4v4h16zm-6 6h4v2h-4v-2z"/></svg>
            </div>
            <h3 class="account-dashboard__section-title"><?php esc_html_e('Payment Methods', 'aqualuxe'); ?></h3>
            <p class="account-dashboard__section-text"><?php esc_html_e('Add or manage your payment methods for faster checkout.', 'aqualuxe'); ?></p>
            <a href="<?php echo esc_url(wc_get_endpoint_url('payment-methods')); ?>" class="account-dashboard__section-link">
                <?php esc_html_e('Manage Payment Methods', 'aqualuxe'); ?>
            </a>
        </div>

        <div class="account-dashboard__section">
            <div class="account-dashboard__section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22 20H2v-2h1v-6.969C3 6.043 7.03 2 12 2s9 4.043 9 9.031V18h1v2zM5 18h14v-6.969C19 7.148 15.866 4 12 4s-7 3.148-7 7.031V18zm4.5 3h5a2.5 2.5 0 1 1-5 0z"/></svg>
            </div>
            <h3 class="account-dashboard__section-title"><?php esc_html_e('Notifications', 'aqualuxe'); ?></h3>
            <p class="account-dashboard__section-text"><?php esc_html_e('Manage your email preferences and notifications.', 'aqualuxe'); ?></p>
            <a href="<?php echo esc_url(wc_get_endpoint_url('notifications')); ?>" class="account-dashboard__section-link">
                <?php esc_html_e('Manage Notifications', 'aqualuxe'); ?>
            </a>
        </div>

        <div class="account-dashboard__section">
            <div class="account-dashboard__section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M19.938 8H21a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-1.062A8.001 8.001 0 0 1 12 23v-2a6 6 0 0 0 6-6V9A6 6 0 1 0 6 9v7H3a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h1.062a8.001 8.001 0 0 1 15.876 0zM3 10v4h1v-4H3zm17 0v4h1v-4h-1zM7.76 15.785l1.06-1.696A5.972 5.972 0 0 0 12 15a5.972 5.972 0 0 0 3.18-.911l1.06 1.696A7.963 7.963 0 0 1 12 17a7.963 7.963 0 0 1-4.24-1.215z"/></svg>
            </div>
            <h3 class="account-dashboard__section-title"><?php esc_html_e('Support', 'aqualuxe'); ?></h3>
            <p class="account-dashboard__section-text"><?php esc_html_e('Need help? Contact our customer support team.', 'aqualuxe'); ?></p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="account-dashboard__section-link">
                <?php esc_html_e('Get Support', 'aqualuxe'); ?>
            </a>
        </div>
    </div>

    <?php
    /**
     * My Account dashboard.
     *
     * @since 2.6.0
     */
    do_action('woocommerce_account_dashboard');

    /**
     * Deprecated woocommerce_before_my_account action.
     *
     * @deprecated 2.6.0
     */
    do_action('woocommerce_before_my_account');

    /**
     * Deprecated woocommerce_after_my_account action.
     *
     * @deprecated 2.6.0
     */
    do_action('woocommerce_after_my_account');
    ?>
</div>