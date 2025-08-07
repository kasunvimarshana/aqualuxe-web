<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$allowed_html = array(
    'a' => array(
        'href' => array(),
    ),
);
?>

<div class="woocommerce-MyAccount-dashboard">
    
    <div class="dashboard-welcome">
        <h2><?php printf(esc_html__('Hello %1$s', 'aqualuxe'), '<strong>' . esc_html($current_user->display_name) . '</strong>'); ?></h2>
        
        <p>
            <?php
            printf(
                wp_kses(__('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'aqualuxe'), $allowed_html),
                esc_url(wc_get_endpoint_url('orders')),
                esc_url(wc_get_endpoint_url('edit-address')),
                esc_url(wc_get_endpoint_url('edit-account'))
            );
            ?>
        </p>
    </div>

    <div class="dashboard-navigation">
        <div class="dashboard-nav-grid">
            <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="dashboard-nav-item">
                <div class="nav-item-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                </div>
                <div class="nav-item-content">
                    <h3><?php esc_html_e('Orders', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('View your order history', 'aqualuxe'); ?></p>
                </div>
            </a>

            <a href="<?php echo esc_url(wc_get_endpoint_url('downloads')); ?>" class="dashboard-nav-item">
                <div class="nav-item-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7,10 12,15 17,10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                </div>
                <div class="nav-item-content">
                    <h3><?php esc_html_e('Downloads', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Access your downloads', 'aqualuxe'); ?></p>
                </div>
            </a>

            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address')); ?>" class="dashboard-nav-item">
                <div class="nav-item-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="nav-item-content">
                    <h3><?php esc_html_e('Addresses', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Manage billing & shipping', 'aqualuxe'); ?></p>
                </div>
            </a>

            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="dashboard-nav-item">
                <div class="nav-item-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="nav-item-content">
                    <h3><?php esc_html_e('Account Details', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Edit your account info', 'aqualuxe'); ?></p>
                </div>
            </a>

            <?php if (wc_get_page_id('myaccount') && wc_get_endpoint_url('customer-logout')) : ?>
                <a href="<?php echo esc_url(wc_get_endpoint_url('customer-logout')); ?>" class="dashboard-nav-item logout-item">
                    <div class="nav-item-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16,17 21,12 16,7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </div>
                    <div class="nav-item-content">
                        <h3><?php esc_html_e('Logout', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Sign out of your account', 'aqualuxe'); ?></p>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php
        /**
         * My Account dashboard hook.
         *
         * @hooked WC_Shortcode_My_Account::output - 10
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