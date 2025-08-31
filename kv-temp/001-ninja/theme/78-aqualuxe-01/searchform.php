<?php
/**
 * Search form template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$search_query = get_search_query();
$placeholder = apply_filters('aqualuxe_search_placeholder', __('Search...', 'aqualuxe'));
$form_id = 'searchform-' . wp_rand();
?>

<form role="search" method="get" class="search-form group" action="<?php echo esc_url(home_url('/')); ?>" id="<?php echo esc_attr($form_id); ?>">
    <div class="search-form-wrapper relative">
        
        <!-- Search Input -->
        <label for="search-field-<?php echo esc_attr($form_id); ?>" class="sr-only">
            <?php esc_html_e('Search for:', 'aqualuxe'); ?>
        </label>
        
        <input type="search" 
               id="search-field-<?php echo esc_attr($form_id); ?>"
               class="search-field w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300"
               placeholder="<?php echo esc_attr($placeholder); ?>" 
               value="<?php echo esc_attr($search_query); ?>" 
               name="s" 
               autocomplete="off"
               required>

        <!-- Search Icon -->
        <div class="search-icon absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="search-submit absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary hover:bg-primary-dark text-white p-2 rounded-md transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
            <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>

        <!-- Clear Button (shown when there's a search query) -->
        <?php if ($search_query) : ?>
            <button type="button" class="search-clear absolute right-12 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1 transition-colors" title="<?php esc_attr_e('Clear search', 'aqualuxe'); ?>">
                <span class="sr-only"><?php esc_html_e('Clear search', 'aqualuxe'); ?></span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        <?php endif; ?>

    </div>

    <!-- Search Filters (optional, hidden by default) -->
    <div class="search-filters hidden mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
        
        <!-- Content Type Filter -->
        <div class="filter-group mb-4">
            <label for="search-post-type-<?php echo esc_attr($form_id); ?>" class="block text-sm font-medium text-gray-700 mb-2">
                <?php esc_html_e('Search in:', 'aqualuxe'); ?>
            </label>
            <select name="post_type" id="search-post-type-<?php echo esc_attr($form_id); ?>" class="filter-select w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                <option value=""><?php esc_html_e('All Content', 'aqualuxe'); ?></option>
                <option value="post" <?php selected(get_query_var('post_type'), 'post'); ?>><?php esc_html_e('Blog Posts', 'aqualuxe'); ?></option>
                <option value="page" <?php selected(get_query_var('post_type'), 'page'); ?>><?php esc_html_e('Pages', 'aqualuxe'); ?></option>
                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                    <option value="product" <?php selected(get_query_var('post_type'), 'product'); ?>><?php esc_html_e('Products', 'aqualuxe'); ?></option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Category Filter (for posts) -->
        <div class="filter-group mb-4">
            <label for="search-category-<?php echo esc_attr($form_id); ?>" class="block text-sm font-medium text-gray-700 mb-2">
                <?php esc_html_e('Category:', 'aqualuxe'); ?>
            </label>
            <?php
            wp_dropdown_categories([
                'show_option_all' => __('All Categories', 'aqualuxe'),
                'name' => 'category_name',
                'id' => 'search-category-' . $form_id,
                'class' => 'filter-select w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary',
                'selected' => get_query_var('category_name'),
                'value_field' => 'slug',
                'hide_empty' => true,
            ]);
            ?>
        </div>

        <!-- Date Range Filter -->
        <div class="filter-group">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <?php esc_html_e('Date Range:', 'aqualuxe'); ?>
            </label>
            <div class="flex space-x-2">
                <select name="date_query_year" class="filter-select flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value=""><?php esc_html_e('Any Year', 'aqualuxe'); ?></option>
                    <?php
                    $current_year = date('Y');
                    for ($year = $current_year; $year >= $current_year - 10; $year--) :
                        $selected = selected(get_query_var('year'), $year, false);
                        ?>
                        <option value="<?php echo esc_attr($year); ?>" <?php echo $selected; ?>><?php echo esc_html($year); ?></option>
                        <?php
                    endfor;
                    ?>
                </select>
                
                <select name="date_query_month" class="filter-select flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value=""><?php esc_html_e('Any Month', 'aqualuxe'); ?></option>
                    <?php
                    global $wp_locale;
                    for ($month = 1; $month <= 12; $month++) :
                        $selected = selected(get_query_var('monthnum'), $month, false);
                        ?>
                        <option value="<?php echo esc_attr($month); ?>" <?php echo $selected; ?>><?php echo esc_html($wp_locale->get_month($month)); ?></option>
                        <?php
                    endfor;
                    ?>
                </select>
            </div>
        </div>

    </div>

    <!-- Advanced Search Toggle -->
    <div class="search-toggle mt-3">
        <button type="button" class="search-filters-toggle text-sm text-gray-600 hover:text-primary transition-colors flex items-center" aria-expanded="false">
            <svg class="w-4 h-4 mr-1 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            <span class="toggle-text"><?php esc_html_e('Advanced Search', 'aqualuxe'); ?></span>
        </button>
    </div>

</form>

<!-- Search Suggestions (if enabled) -->
<?php if (apply_filters('aqualuxe_enable_search_suggestions', true)) : ?>
    <div class="search-suggestions hidden absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-lg z-50 max-h-64 overflow-y-auto">
        <div class="suggestions-list">
            <!-- Populated by JavaScript -->
        </div>
    </div>
<?php endif; ?>

<script>
// Search form functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('<?php echo esc_js($form_id); ?>');
    const searchField = searchForm.querySelector('.search-field');
    const searchFilters = searchForm.querySelector('.search-filters');
    const filtersToggle = searchForm.querySelector('.search-filters-toggle');
    const toggleIcon = filtersToggle?.querySelector('svg');
    const toggleText = filtersToggle?.querySelector('.toggle-text');
    const clearButton = searchForm.querySelector('.search-clear');
    
    // Advanced search toggle
    if (filtersToggle && searchFilters) {
        filtersToggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Toggle filters visibility
            searchFilters.classList.toggle('hidden');
            
            // Update aria-expanded
            this.setAttribute('aria-expanded', !isExpanded);
            
            // Rotate icon
            if (toggleIcon) {
                toggleIcon.classList.toggle('rotate-180');
            }
            
            // Update text
            if (toggleText) {
                toggleText.textContent = isExpanded ? 
                    '<?php esc_html_e('Advanced Search', 'aqualuxe'); ?>' : 
                    '<?php esc_html_e('Simple Search', 'aqualuxe'); ?>';
            }
        });
    }
    
    // Clear search functionality
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            searchField.value = '';
            searchField.focus();
            
            // Reset filters
            const filters = searchFilters?.querySelectorAll('select');
            if (filters) {
                filters.forEach(filter => filter.selectedIndex = 0);
            }
        });
    }
    
    // Search suggestions (basic implementation)
    let suggestionsTimeout;
    const suggestionsContainer = document.querySelector('.search-suggestions');
    const suggestionsList = suggestionsContainer?.querySelector('.suggestions-list');
    
    if (suggestionsContainer && suggestionsList) {
        searchField.addEventListener('input', function() {
            const query = this.value.trim();
            
            clearTimeout(suggestionsTimeout);
            
            if (query.length >= 2) {
                suggestionsTimeout = setTimeout(() => {
                    fetchSearchSuggestions(query, suggestionsList, suggestionsContainer);
                }, 300);
            } else {
                suggestionsContainer.classList.add('hidden');
            }
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchForm.contains(e.target)) {
                suggestionsContainer.classList.add('hidden');
            }
        });
    }
    
    // Fetch search suggestions (placeholder function)
    function fetchSearchSuggestions(query, container, wrapper) {
        // This would typically make an AJAX request to get suggestions
        // For now, we'll show some basic suggestions
        const suggestions = [
            'Aquarium setup',
            'Fish care',
            'Water parameters',
            'Filtration systems',
            'Aquascaping tips'
        ].filter(suggestion => 
            suggestion.toLowerCase().includes(query.toLowerCase())
        );
        
        if (suggestions.length > 0) {
            container.innerHTML = suggestions.map(suggestion => `
                <div class="suggestion-item px-4 py-2 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" data-suggestion="${suggestion}">
                    ${suggestion}
                </div>
            `).join('');
            
            wrapper.classList.remove('hidden');
            
            // Add click handlers to suggestions
            container.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', function() {
                    searchField.value = this.getAttribute('data-suggestion');
                    wrapper.classList.add('hidden');
                    searchForm.submit();
                });
            });
        } else {
            wrapper.classList.add('hidden');
        }
    }
    
    // Keyboard navigation for suggestions
    searchField.addEventListener('keydown', function(e) {
        const suggestions = suggestionsList?.querySelectorAll('.suggestion-item');
        const activeSuggestion = suggestionsList?.querySelector('.suggestion-item.active');
        
        if (!suggestions || suggestions.length === 0) return;
        
        let nextActive;
        
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (activeSuggestion) {
                    activeSuggestion.classList.remove('active');
                    nextActive = activeSuggestion.nextElementSibling || suggestions[0];
                } else {
                    nextActive = suggestions[0];
                }
                nextActive.classList.add('active');
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                if (activeSuggestion) {
                    activeSuggestion.classList.remove('active');
                    nextActive = activeSuggestion.previousElementSibling || suggestions[suggestions.length - 1];
                } else {
                    nextActive = suggestions[suggestions.length - 1];
                }
                nextActive.classList.add('active');
                break;
                
            case 'Enter':
                if (activeSuggestion) {
                    e.preventDefault();
                    this.value = activeSuggestion.getAttribute('data-suggestion');
                    suggestionsContainer.classList.add('hidden');
                }
                break;
                
            case 'Escape':
                suggestionsContainer.classList.add('hidden');
                break;
        }
    });
});
</script>

<style>
/* Search form specific styles */
.search-form {
    position: relative;
}

.search-field:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-submit:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-filters-toggle svg {
    transition: transform 0.2s ease;
}

.search-filters-toggle svg.rotate-180 {
    transform: rotate(180deg);
}

.filter-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    appearance: none;
}

.suggestion-item.active {
    background-color: #f3f4f6;
}

/* RTL Support */
[dir="rtl"] .search-icon {
    left: auto;
    right: 1rem;
}

[dir="rtl"] .search-submit {
    right: auto;
    left: 0.5rem;
}

[dir="rtl"] .search-clear {
    right: auto;
    left: 3rem;
}

[dir="rtl"] .search-field {
    padding-left: 3rem;
    padding-right: 1rem;
}

[dir="rtl"] .filter-select {
    background-position: left 0.5rem center;
    padding-left: 2.5rem;
    padding-right: 0.75rem;
}
</style>
