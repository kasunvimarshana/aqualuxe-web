<?php
/**
 * The template for displaying search forms
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
    <div class="relative">
        <input type="search" class="search-field w-full py-3 px-4 pr-12 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit absolute right-0 top-0 h-full px-4 text-gray-600 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="screen-reader-text"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
        </button>
    </div>
</form>