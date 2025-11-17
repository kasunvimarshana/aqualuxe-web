<?php
/**
 * Template part for displaying the currency switcher in the footer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Only display if WooCommerce and multi-currency are active
if ( ! aqualuxe_is_woocommerce_active() || ! aqualuxe_is_multi_currency_active() ) {
    return;
}

// Get available currencies
$currencies = aqualuxe_get_available_currencies();
$current_currency = aqualuxe_get_current_currency();

// If no currencies or only one currency, don't show switcher
if ( empty( $currencies ) || count( $currencies ) <= 1 ) {
    return;
}
?>

<div class="currency-switcher" x-data="{ currencyOpen: false }">
    <button 
        @click="currencyOpen = !currencyOpen" 
        class="flex items-center justify-center space-x-2 px-4 py-2 rounded-md bg-dark-800 hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors"
        aria-label="<?php esc_attr_e( 'Switch currency', 'aqualuxe' ); ?>"
    >
        <span class="text-white text-sm font-medium">
            <?php echo esc_html( $current_currency['symbol'] . ' ' . $current_currency['code'] ); ?>
        </span>
        <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    <!-- Currency dropdown -->
    <div 
        x-cloak
        x-show="currencyOpen" 
        @click.away="currencyOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute bottom-full mb-2 w-48 bg-white dark:bg-dark-800 rounded-lg shadow-lg z-50"
    >
        <div class="py-2">
            <?php foreach ( $currencies as $code => $currency ) : ?>
                <?php
                $is_current = $code === $current_currency['code'];
                $url = $currency['url'] ?? aqualuxe_get_currency_url( $code );
                ?>
                <a 
                    href="<?php echo esc_url( $url ); ?>" 
                    class="flex items-center justify-between px-4 py-2 text-sm <?php echo $is_current ? 'bg-gray-100 dark:bg-dark-700 text-primary-600 dark:text-primary-400' : 'text-dark-700 dark:text-dark-300 hover:bg-gray-50 dark:hover:bg-dark-700'; ?>"
                    <?php echo $is_current ? 'aria-current="true"' : ''; ?>
                >
                    <span><?php echo esc_html( $currency['symbol'] . ' ' . $currency['code'] ); ?></span>
                    <?php if ( $is_current ) : ?>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>