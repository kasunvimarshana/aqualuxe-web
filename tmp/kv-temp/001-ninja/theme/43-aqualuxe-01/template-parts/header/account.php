<?php
/**
 * Template part for displaying the account icon in the header
 *
 * @package AquaLuxe
 */

// This template part should only be included when WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}
?>

<div class="header-account ml-4 relative">
    <button id="account-toggle" class="account-toggle" aria-label="<?php esc_attr_e( 'My Account', 'aqualuxe' ); ?>" aria-expanded="false" aria-haspopup="true">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </button>
    
    <div id="account-dropdown" class="account-dropdown absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 hidden">
        <div class="py-2">
            <?php if ( is_user_logged_in() ) : ?>
                <div class="account-info px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium">
                        <?php
                        $current_user = wp_get_current_user();
                        echo esc_html( sprintf( __( 'Hello, %s', 'aqualuxe' ), $current_user->display_name ) );
                        ?>
                    </p>
                </div>
                
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                    <?php esc_html_e( 'Dashboard', 'aqualuxe' ); ?>
                </a>
                
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                    <?php esc_html_e( 'Orders', 'aqualuxe' ); ?>
                </a>
                
                <?php if ( class_exists( 'WC_Wishlist_Plugin' ) ) : ?>
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'wishlist' ) ); ?>" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                        <?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?>
                    </a>
                <?php endif; ?>
                
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                    <?php esc_html_e( 'Account Details', 'aqualuxe' ); ?>
                </a>
                
                <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                    <?php esc_html_e( 'Logout', 'aqualuxe' ); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                    <?php esc_html_e( 'Login', 'aqualuxe' ); ?>
                </a>
                
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                    <?php esc_html_e( 'Register', 'aqualuxe' ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>