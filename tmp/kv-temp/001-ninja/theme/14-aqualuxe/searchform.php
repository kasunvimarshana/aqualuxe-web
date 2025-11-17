<?php
/**
 * The template for displaying search forms
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="sr-only">
        <span class="screen-reader-text"><?php echo esc_html_x('Search for:', 'label', 'aqualuxe'); ?></span>
    </label>
    <div class="relative">
        <input type="search" class="search-field w-full px-4 py-3 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark transition-colors duration-300" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit absolute right-0 top-0 h-full px-4 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-dark transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="screen-reader-text"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
        </button>
    </div>
</form>