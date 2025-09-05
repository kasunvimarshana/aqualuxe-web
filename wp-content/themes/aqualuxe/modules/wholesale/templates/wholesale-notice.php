<?php
/**
 * Template for displaying a wholesale notice to non-wholesale customers.
 */

$login_url = \wp_login_url(\get_permalink());
$register_url = \get_permalink(\get_option('woocommerce_myaccount_page_id')); // Or a dedicated registration page
?>
<div class="aqualuxe-wholesale-notice mt-4 p-4 border-l-4 border-blue-500 bg-blue-50 dark:bg-gray-800">
    <div class="flex">
        <div class="flex-shrink-0">
            <!-- Heroicon name: solid/information-circle -->
            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700 dark:text-blue-300">
                <?php \_e('Special pricing available for wholesale customers.', 'aqualuxe'); ?>
                <a href="<?php echo \esc_url($login_url); ?>" class="font-medium underline">
                    <?php \_e('Log in', 'aqualuxe'); ?>
                </a>
                or
                <a href="<?php echo \esc_url($register_url); ?>" class="font-medium underline">
                    <?php \_e('register', 'aqualuxe'); ?>
                </a>
                <?php \_e('to see your price.', 'aqualuxe'); ?>
            </p>
        </div>
    </div>
</div>
