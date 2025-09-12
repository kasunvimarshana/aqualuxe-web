<?php
/**
 * Search form template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

$unique_id = uniqid('search-form-');
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-form-inner relative">
        <label for="<?php echo esc_attr($unique_id); ?>" class="sr-only">
            <?php esc_html_e('Search for:', 'aqualuxe'); ?>
        </label>
        
        <input 
            type="search" 
            id="<?php echo esc_attr($unique_id); ?>"
            class="search-field form-input pr-12" 
            placeholder="<?php esc_attr_e('Search products, articles, and more...', 'aqualuxe'); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
            autocomplete="off"
        />
        
        <button type="submit" class="search-submit absolute right-3 top-1/2 transform -translate-y-1/2 text-neutral-500 hover:text-primary-600 transition-colors">
            <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    </div>
</form>