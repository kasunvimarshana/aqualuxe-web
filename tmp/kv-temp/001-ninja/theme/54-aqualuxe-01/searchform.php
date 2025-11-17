<?php
/**
 * The template for displaying search forms
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="sr-only"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
    <div class="flex">
        <input type="search" class="search-field flex-grow border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-l-md focus:ring-primary-500 focus:border-primary-500" placeholder="<?php echo esc_attr_x('Search&hellip;', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        
        <?php if (aqualuxe_is_woocommerce_active() && get_theme_mod('aqualuxe_search_products_only', false)) : ?>
            <input type="hidden" name="post_type" value="product" />
        <?php endif; ?>
        
        <button type="submit" class="search-submit bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-r-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="sr-only"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
        </button>
    </div>
</form>