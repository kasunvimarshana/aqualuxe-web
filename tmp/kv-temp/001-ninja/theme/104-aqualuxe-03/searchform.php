<?php
/**
 * Search Form Template
 * 
 * @package AquaLuxe
 */

$unique_id = uniqid('search-form-');
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-form-wrapper relative">
        <label for="<?php echo $unique_id; ?>" class="sr-only">
            <?php echo esc_html_x('Search for:', 'label', 'aqualuxe'); ?>
        </label>
        
        <div class="search-input-wrapper relative">
            <input type="search" 
                   id="<?php echo $unique_id; ?>" 
                   class="search-input w-full pl-10 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                   placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'aqualuxe'); ?>" 
                   value="<?php echo get_search_query(); ?>" 
                   name="s" />
            
            <div class="search-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <button type="submit" class="search-submit absolute right-2 top-1/2 transform -translate-y-1/2 p-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span class="sr-only"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
            </button>
        </div>
    </div>
</form>