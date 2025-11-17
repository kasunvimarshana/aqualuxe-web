<?php
/**
 * Search Form Template
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="sr-only" for="s"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
    <div class="search-form-wrapper flex">
        <input type="search" 
               id="s" 
               name="s" 
               class="search-field flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
               placeholder="<?php esc_attr_e('Search...', 'aqualuxe'); ?>" 
               value="<?php echo get_search_query(); ?>" 
               required>
        <button type="submit" 
                class="search-submit px-6 py-3 bg-primary-600 text-white border border-primary-600 rounded-r-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
            <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    </div>
</form>