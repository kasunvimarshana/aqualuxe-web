<?php
/**
 * Template part for displaying the mini cart
 *
 * @package AquaLuxe
 */

defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    return;
}
?>

<div id="mini-cart" class="mini-cart hidden absolute right-4 top-full mt-2 w-80 bg-white dark:bg-secondary-800 rounded-lg shadow-lg z-50">
    <div class="mini-cart-header flex items-center justify-between p-4 border-b border-secondary-200 dark:border-secondary-700">
        <h3 class="text-lg font-medium"><?php esc_html_e('Your Cart', 'aqualuxe'); ?></h3>
        <button id="mini-cart-close" class="mini-cart-close text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-white">
            <span class="sr-only"><?php esc_html_e('Close', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="mini-cart-content p-4">
        <?php woocommerce_mini_cart(); ?>
    </div>
</div>

<style>
    /* Custom mini cart styles */
    .mini-cart .woocommerce-mini-cart {
        @apply max-h-80 overflow-y-auto;
    }
    
    .mini-cart .woocommerce-mini-cart-item {
        @apply flex items-center py-3 border-b border-secondary-200 dark:border-secondary-700 last:border-b-0;
    }
    
    .mini-cart .woocommerce-mini-cart-item a:not(.remove) {
        @apply text-secondary-900 dark:text-white hover:text-primary-500 dark:hover:text-primary-400 font-medium;
    }
    
    .mini-cart .woocommerce-mini-cart-item img {
        @apply w-16 h-16 object-cover rounded mr-3;
    }
    
    .mini-cart .woocommerce-mini-cart-item .remove {
        @apply absolute top-0 left-0 w-5 h-5 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900 text-red-500 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800;
    }
    
    .mini-cart .woocommerce-mini-cart__total {
        @apply flex justify-between items-center py-3 border-t border-b border-secondary-200 dark:border-secondary-700 font-medium;
    }
    
    .mini-cart .woocommerce-mini-cart__buttons {
        @apply grid grid-cols-2 gap-2 mt-4;
    }
    
    .mini-cart .woocommerce-mini-cart__buttons .button {
        @apply inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium;
    }
    
    .mini-cart .woocommerce-mini-cart__buttons .button:first-child {
        @apply bg-white dark:bg-secondary-700 border-secondary-300 dark:border-secondary-600 text-secondary-700 dark:text-white hover:bg-secondary-50 dark:hover:bg-secondary-600;
    }
    
    .mini-cart .woocommerce-mini-cart__buttons .button.checkout {
        @apply bg-primary-500 text-white hover:bg-primary-600;
    }
    
    .mini-cart .woocommerce-mini-cart__empty-message {
        @apply py-6 text-center text-secondary-500 dark:text-secondary-400;
    }
</style>