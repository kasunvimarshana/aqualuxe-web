<?php
/**
 * Template part for displaying the product search form
 *
 * @package AquaLuxe
 */

defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    return;
}
?>

<form role="search" method="get" class="woocommerce-product-search relative" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="sr-only" for="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>">
        <?php esc_html_e('Search for:', 'aqualuxe'); ?>
    </label>
    <div class="flex">
        <input type="search" 
            id="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>" 
            class="search-field w-full px-4 py-2 border border-secondary-300 dark:border-secondary-700 rounded-l-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-secondary-800 dark:text-white" 
            placeholder="<?php echo esc_attr__('Search products&hellip;', 'aqualuxe'); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
        />
        <button type="submit" class="search-submit px-4 py-2 bg-primary-500 text-white rounded-r-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <span class="sr-only"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </div>
    <input type="hidden" name="post_type" value="product" />
</form>