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
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="woocommerce-account-dashboard">
    <div class="dashboard-welcome">
        <div class="welcome-content">
            <h2><?php
                /* translators: %s: customer first name */
                printf( esc_html__( 'Welcome %s!', 'aqualuxe' ), esc_html( $current_user->display_name ) );
            ?></h2>
            <p><?php
                printf(
                    /* translators: 1: user display name 2: logout url */
                    wp_kses( __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'aqualuxe' ), $allowed_html ),
                    esc_url( wc_get_endpoint_url( 'orders' ) ),
                    esc_url( wc_get_endpoint_url( 'edit-address' ) ),
                    esc_url( wc_get_endpoint_url( 'edit-account' ) )
                );
            ?></p>
        </div>
        <div class="welcome-image">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/dashboard-welcome.svg' ); ?>" alt="<?php esc_attr_e( 'Welcome', 'aqualuxe' ); ?>">
        </div>
    </div>

    <div class="dashboard-cards">
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="card-content">
                <h3><?php esc_html_e( 'Orders', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'View and track your orders', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="card-link"><?php esc_html_e( 'View Orders', 'aqualuxe' ); ?></a>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="card-content">
                <h3><?php esc_html_e( 'Addresses', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'Manage your shipping and billing addresses', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="card-link"><?php esc_html_e( 'Manage Addresses', 'aqualuxe' ); ?></a>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="card-content">
                <h3><?php esc_html_e( 'Account Details', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'Update your account information', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="card-link"><?php esc_html_e( 'Edit Details', 'aqualuxe' ); ?></a>
            </div>
        </div>
        
        <?php if ( class_exists( 'YITH_WCWL' ) ) : ?>
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-heart"></i>
            </div>
            <div class="card-content">
                <h3><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'View your saved items', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>" class="card-link"><?php esc_html_e( 'View Wishlist', 'aqualuxe' ); ?></a>
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
    
    <div class="dashboard-help">
        <h3><?php esc_html_e( 'Need Help?', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'Our customer service team is here to assist you with any questions about your orders, products, or account.', 'aqualuxe' ); ?></p>
        <div class="help-options">
            <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_help_page_id' ) ) ); ?>" class="help-option">
                <i class="fas fa-question-circle"></i>
                <span><?php esc_html_e( 'Help Center', 'aqualuxe' ); ?></span>
            </a>
            <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_support_endpoint', get_option( 'woocommerce_help_page_id' ) ) ) ); ?>" class="help-option">
                <i class="fas fa-ticket-alt"></i>
                <span><?php esc_html_e( 'Support Tickets', 'aqualuxe' ); ?></span>
            </a>
            <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_contact_page_id' ) ) ); ?>" class="help-option">
                <i class="fas fa-envelope"></i>
                <span><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></span>
            </a>
        </div>
    </div>
</div>