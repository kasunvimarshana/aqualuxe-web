<?php
/**
 * Search form template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

$unique_id = wp_unique_id('search-form-');
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="<?php echo esc_attr($unique_id); ?>" class="sr-only">
        <?php esc_html_e('Search for:', 'aqualuxe'); ?>
    </label>
    
    <div class="search-input-wrapper relative">
        <input 
            type="search" 
            id="<?php echo esc_attr($unique_id); ?>" 
            class="search-field w-full px-4 py-3 pr-12 border-2 border-gray-300 rounded-lg focus:border-aqua-500 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
            placeholder="<?php esc_attr_e('Search...', 'aqualuxe'); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
            required
        />
        
        <button 
            type="submit" 
            class="search-submit absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-aqua-600 transition-colors"
            aria-label="<?php esc_attr_e('Search', 'aqualuxe'); ?>"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    </div>
</form>