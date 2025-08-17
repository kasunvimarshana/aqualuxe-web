<?php
/**
 * Template part for displaying the account icon in the header
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Only display if WooCommerce is active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}
?>

<div class="account-icon relative" x-data="{ accountOpen: false }">
    <button 
        @click="accountOpen = !accountOpen" 
        class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors"
        aria-label="<?php esc_attr_e( 'My account', 'aqualuxe' ); ?>"
    >
        <svg class="w-5 h-5 text-dark-700 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </button>
    
    <!-- Account dropdown -->
    <div 
        x-cloak
        x-show="accountOpen" 
        @click.away="accountOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-64 bg-white dark:bg-dark-800 rounded-lg shadow-lg z-50"
    >
        <div class="p-4">
            <?php if ( is_user_logged_in() ) : ?>
                <?php
                $current_user = wp_get_current_user();
                $account_page_url = wc_get_page_permalink( 'myaccount' );
                ?>
                <div class="user-info flex items-center mb-4 pb-4 border-b border-gray-200 dark:border-dark-700">
                    <div class="user-avatar mr-3">
                        <?php echo get_avatar( $current_user->ID, 40, '', '', array( 'class' => 'rounded-full' ) ); ?>
                    </div>
                    <div class="user-details">
                        <div class="user-name font-medium text-dark-900 dark:text-white">
                            <?php echo esc_html( $current_user->display_name ); ?>
                        </div>
                        <div class="user-email text-xs text-dark-500 dark:text-dark-400">
                            <?php echo esc_html( $current_user->user_email ); ?>
                        </div>
                    </div>
                </div>
                
                <nav class="account-navigation">
                    <ul class="space-y-2">
                        <li>
                            <a href="<?php echo esc_url( $account_page_url ); ?>" class="flex items-center text-dark-700 dark:text-dark-300 hover:text-primary-600 dark:hover:text-primary-400 py-1">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <?php esc_html_e( 'Dashboard', 'aqualuxe' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders', '', $account_page_url ) ); ?>" class="flex items-center text-dark-700 dark:text-dark-300 hover:text-primary-600 dark:hover:text-primary-400 py-1">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <?php esc_html_e( 'Orders', 'aqualuxe' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( wc_get_endpoint_url( 'downloads', '', $account_page_url ) ); ?>" class="flex items-center text-dark-700 dark:text-dark-300 hover:text-primary-600 dark:hover:text-primary-400 py-1">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <?php esc_html_e( 'Downloads', 'aqualuxe' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', '', $account_page_url ) ); ?>" class="flex items-center text-dark-700 dark:text-dark-300 hover:text-primary-600 dark:hover:text-primary-400 py-1">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <?php esc_html_e( 'Addresses', 'aqualuxe' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account', '', $account_page_url ) ); ?>" class="flex items-center text-dark-700 dark:text-dark-300 hover:text-primary-600 dark:hover:text-primary-400 py-1">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <?php esc_html_e( 'Account details', 'aqualuxe' ); ?>
                            </a>
                        </li>
                        <li class="pt-2 mt-2 border-t border-gray-200 dark:border-dark-700">
                            <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="flex items-center text-dark-700 dark:text-dark-300 hover:text-primary-600 dark:hover:text-primary-400 py-1">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <?php esc_html_e( 'Logout', 'aqualuxe' ); ?>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php else : ?>
                <div class="login-register space-y-4">
                    <h3 class="text-lg font-medium text-dark-900 dark:text-white mb-2"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></h3>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn-primary w-full text-center">
                        <?php esc_html_e( 'Login', 'aqualuxe' ); ?>
                    </a>
                    <div class="text-center text-sm text-dark-500 dark:text-dark-400">
                        <?php esc_html_e( 'Don\'t have an account?', 'aqualuxe' ); ?> 
                        <a href="<?php echo esc_url( add_query_arg( 'action', 'register', wc_get_page_permalink( 'myaccount' ) ) ); ?>" class="text-primary-600 dark:text-primary-400 hover:underline">
                            <?php esc_html_e( 'Register', 'aqualuxe' ); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>