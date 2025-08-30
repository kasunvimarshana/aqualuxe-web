<?php
/**
 * Search form template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

$unique_id = wp_unique_id('search-form-');
$aria_label = ! empty($args['aria_label']) ? 'aria-label="' . esc_attr($args['aria_label']) . '"' : '';
?>

<form role="search" <?php echo $aria_label; ?> method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-form-container relative">
        
        <!-- Search Input -->
        <div class="relative">
            <input type="search" 
                   id="<?php echo esc_attr($unique_id); ?>"
                   class="search-field w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-dark-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-dark-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" 
                   placeholder="<?php echo esc_attr_x('Search...', 'placeholder text', 'aqualuxe'); ?>" 
                   value="<?php echo get_search_query(); ?>" 
                   name="s" 
                   required />
            
            <!-- Search Icon -->
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <!-- Clear Button (shown when there's a search query) -->
            <?php if (get_search_query()): ?>
                <button type="button" 
                        class="search-clear absolute inset-y-0 right-12 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        onclick="this.closest('form').querySelector('.search-field').value = ''; this.style.display = 'none';"
                        aria-label="<?php esc_attr_e('Clear search', 'aqualuxe'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            <?php endif; ?>
        </div>
        
        <!-- Search Submit Button -->
        <button type="submit" 
                class="search-submit btn btn-primary absolute right-1 top-1 bottom-1 px-4 rounded-md"
                aria-label="<?php esc_attr_e('Search', 'aqualuxe'); ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
        </button>
        
        <!-- Search Suggestions (if enabled) -->
        <?php if (get_theme_mod('enable_search_suggestions', true)): ?>
            <div class="search-suggestions absolute top-full left-0 right-0 mt-1 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-lg shadow-lg z-50 hidden">
                <div class="search-suggestions-content p-4">
                    <!-- Popular Searches -->
                    <div class="popular-searches mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <?php esc_html_e('Popular Searches', 'aqualuxe'); ?>
                        </h4>
                        <div class="popular-searches-list flex flex-wrap gap-2">
                            <?php
                            $popular_searches = get_theme_mod('popular_searches', [
                                'aquarium maintenance',
                                'fish care',
                                'water filters',
                                'aquatic plants',
                                'marine fish'
                            ]);
                            
                            if (is_array($popular_searches)):
                                foreach (array_slice($popular_searches, 0, 5) as $search):
                            ?>
                                <button type="button" 
                                        class="search-suggestion-item px-3 py-1 text-xs bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors"
                                        onclick="document.getElementById('<?php echo esc_attr($unique_id); ?>').value = this.textContent.trim(); this.closest('form').submit();">
                                    <?php echo esc_html($search); ?>
                                </button>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                    
                    <!-- Recent Searches (if user has searched before) -->
                    <?php if (isset($_COOKIE['aqualuxe_recent_searches'])): ?>
                        <div class="recent-searches">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <?php esc_html_e('Recent Searches', 'aqualuxe'); ?>
                            </h4>
                            <div class="recent-searches-list space-y-1">
                                <?php
                                $recent_searches = json_decode(stripslashes($_COOKIE['aqualuxe_recent_searches']), true);
                                if (is_array($recent_searches)):
                                    foreach (array_slice($recent_searches, 0, 3) as $search):
                                ?>
                                    <button type="button" 
                                            class="search-recent-item flex items-center w-full text-left px-2 py-1 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700 rounded transition-colors"
                                            onclick="document.getElementById('<?php echo esc_attr($unique_id); ?>').value = '<?php echo esc_attr($search); ?>'; this.closest('form').submit();">
                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo esc_html($search); ?>
                                    </button>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
    
    <!-- Advanced Search Toggle -->
    <?php if (get_theme_mod('enable_advanced_search', false)): ?>
        <div class="advanced-search-toggle mt-2">
            <button type="button" 
                    class="text-sm text-primary-600 hover:text-primary-700 transition-colors"
                    onclick="document.querySelector('.advanced-search-options').classList.toggle('hidden');">
                <?php esc_html_e('Advanced Search', 'aqualuxe'); ?>
                <svg class="w-3 h-3 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        
        <!-- Advanced Search Options -->
        <div class="advanced-search-options hidden mt-4 p-4 bg-gray-50 dark:bg-dark-800 rounded-lg">
            
            <!-- Content Type Filter -->
            <div class="search-filter mb-3">
                <label for="search-post-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <?php esc_html_e('Content Type', 'aqualuxe'); ?>
                </label>
                <select name="post_type" id="search-post-type" class="form-select w-full">
                    <option value=""><?php esc_html_e('All Content', 'aqualuxe'); ?></option>
                    <option value="post" <?php selected(get_query_var('post_type'), 'post'); ?>><?php esc_html_e('Blog Posts', 'aqualuxe'); ?></option>
                    <option value="page" <?php selected(get_query_var('post_type'), 'page'); ?>><?php esc_html_e('Pages', 'aqualuxe'); ?></option>
                    <?php if (class_exists('WooCommerce')): ?>
                        <option value="product" <?php selected(get_query_var('post_type'), 'product'); ?>><?php esc_html_e('Products', 'aqualuxe'); ?></option>
                    <?php endif; ?>
                    <option value="service" <?php selected(get_query_var('post_type'), 'service'); ?>><?php esc_html_e('Services', 'aqualuxe'); ?></option>
                    <option value="event" <?php selected(get_query_var('post_type'), 'event'); ?>><?php esc_html_e('Events', 'aqualuxe'); ?></option>
                </select>
            </div>
            
            <!-- Category Filter -->
            <div class="search-filter mb-3">
                <label for="search-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <?php esc_html_e('Category', 'aqualuxe'); ?>
                </label>
                <?php
                wp_dropdown_categories([
                    'name' => 'cat',
                    'id' => 'search-category',
                    'class' => 'form-select w-full',
                    'show_option_all' => __('All Categories', 'aqualuxe'),
                    'value_field' => 'term_id',
                    'selected' => get_query_var('cat'),
                    'hierarchical' => true,
                    'depth' => 3
                ]);
                ?>
            </div>
            
            <!-- Date Range Filter -->
            <div class="search-filter mb-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <?php esc_html_e('Date Range', 'aqualuxe'); ?>
                </label>
                <select name="date_range" class="form-select w-full">
                    <option value=""><?php esc_html_e('Any Date', 'aqualuxe'); ?></option>
                    <option value="week" <?php selected(get_query_var('date_range'), 'week'); ?>><?php esc_html_e('Past Week', 'aqualuxe'); ?></option>
                    <option value="month" <?php selected(get_query_var('date_range'), 'month'); ?>><?php esc_html_e('Past Month', 'aqualuxe'); ?></option>
                    <option value="year" <?php selected(get_query_var('date_range'), 'year'); ?>><?php esc_html_e('Past Year', 'aqualuxe'); ?></option>
                </select>
            </div>
            
            <!-- Search in Fields -->
            <div class="search-filter">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <?php esc_html_e('Search in', 'aqualuxe'); ?>
                </label>
                <div class="flex flex-wrap gap-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="search_fields[]" value="title" class="form-checkbox mr-2" checked>
                        <span class="text-sm"><?php esc_html_e('Title', 'aqualuxe'); ?></span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="search_fields[]" value="content" class="form-checkbox mr-2" checked>
                        <span class="text-sm"><?php esc_html_e('Content', 'aqualuxe'); ?></span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="search_fields[]" value="excerpt" class="form-checkbox mr-2">
                        <span class="text-sm"><?php esc_html_e('Excerpt', 'aqualuxe'); ?></span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="search_fields[]" value="tags" class="form-checkbox mr-2">
                        <span class="text-sm"><?php esc_html_e('Tags', 'aqualuxe'); ?></span>
                    </label>
                </div>
            </div>
            
        </div>
    <?php endif; ?>
    
</form>

<!-- Search Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.search-form');
    const searchField = searchForm.querySelector('.search-field');
    const searchSuggestions = searchForm.querySelector('.search-suggestions');
    
    // Show/hide suggestions on focus/blur
    if (searchSuggestions) {
        searchField.addEventListener('focus', function() {
            if (this.value.length === 0) {
                searchSuggestions.classList.remove('hidden');
            }
        });
        
        searchField.addEventListener('blur', function() {
            setTimeout(() => {
                searchSuggestions.classList.add('hidden');
            }, 200);
        });
        
        searchField.addEventListener('input', function() {
            if (this.value.length === 0) {
                searchSuggestions.classList.remove('hidden');
            } else {
                searchSuggestions.classList.add('hidden');
            }
        });
    }
    
    // Store recent searches
    searchForm.addEventListener('submit', function() {
        const query = searchField.value.trim();
        if (query) {
            let recentSearches = [];
            try {
                const stored = getCookie('aqualuxe_recent_searches');
                if (stored) {
                    recentSearches = JSON.parse(stored);
                }
            } catch (e) {
                recentSearches = [];
            }
            
            // Add new search to the beginning, remove duplicates
            recentSearches = recentSearches.filter(search => search !== query);
            recentSearches.unshift(query);
            
            // Keep only the last 5 searches
            recentSearches = recentSearches.slice(0, 5);
            
            // Store in cookie
            setCookie('aqualuxe_recent_searches', JSON.stringify(recentSearches), 30);
        }
    });
    
    // Cookie helper functions
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
    }
    
    function getCookie(name) {
        const nameEQ = name + '=';
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
});
</script>
