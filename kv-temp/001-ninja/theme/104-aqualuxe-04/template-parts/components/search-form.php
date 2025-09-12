<?php
/**
 * Template part for displaying search form
 *
 * @package AquaLuxe
 */

$search_id = isset($search_id) ? $search_id : 'search-form-' . wp_rand();
$placeholder = isset($placeholder) ? $placeholder : __('Search...', 'aqualuxe');
$show_button = isset($show_button) ? $show_button : true;
$button_text = isset($button_text) ? $button_text : __('Search', 'aqualuxe');
$form_class = isset($form_class) ? $form_class : 'search-form flex items-center';
$input_class = isset($input_class) ? $input_class : 'search-input flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-l-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent';
$button_class = isset($button_class) ? $button_class : 'search-submit px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-r-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2';
?>

<form role="search" method="get" class="<?php echo esc_attr($form_class); ?>" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="<?php echo esc_attr($search_id); ?>" class="sr-only">
        <?php esc_html_e('Search for:', 'aqualuxe'); ?>
    </label>
    
    <div class="search-field-wrapper flex-1 relative">
        <input 
            type="search" 
            id="<?php echo esc_attr($search_id); ?>" 
            class="<?php echo esc_attr($input_class); ?>" 
            placeholder="<?php echo esc_attr($placeholder); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
            autocomplete="off"
            required
        />
        
        <!-- Search Icon (when no button) -->
        <?php if (!$show_button) : ?>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ($show_button) : ?>
        <button type="submit" class="<?php echo esc_attr($button_class); ?>">
            <span class="sr-only sm:not-sr-only"><?php echo esc_html($button_text); ?></span>
            <svg class="w-5 h-5 sm:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    <?php endif; ?>
</form>

<!-- Search Modal (if needed) -->
<div id="search-modal" class="search-modal fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300" aria-hidden="true">
    <div class="search-modal-content flex items-start justify-center min-h-screen pt-20">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl mx-4 overflow-hidden">
            
            <!-- Modal Header -->
            <div class="search-modal-header flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <?php esc_html_e('Search', 'aqualuxe'); ?>
                </h2>
                <button 
                    type="button" 
                    id="close-search-modal" 
                    class="close-search-modal p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                    aria-label="<?php esc_attr_e('Close search', 'aqualuxe'); ?>"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="search-modal-body p-6">
                <?php
                aqualuxe_get_template_part('components/search-form', null, [
                    'search_id' => 'modal-search-form',
                    'placeholder' => __('What are you looking for?', 'aqualuxe'),
                    'form_class' => 'search-form mb-6',
                    'input_class' => 'search-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg',
                    'show_button' => false,
                ]);
                ?>
                
                <!-- Search Suggestions -->
                <div class="search-suggestions">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">
                        <?php esc_html_e('Popular Searches', 'aqualuxe'); ?>
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <?php
                        $popular_searches = apply_filters('aqualuxe_popular_searches', [
                            __('Tropical Fish', 'aqualuxe'),
                            __('Aquarium Plants', 'aqualuxe'),
                            __('Fish Food', 'aqualuxe'),
                            __('Water Treatment', 'aqualuxe'),
                            __('Aquarium Setup', 'aqualuxe'),
                        ]);
                        
                        foreach ($popular_searches as $search_term) :
                            ?>
                            <a href="<?php echo esc_url(home_url('/?s=' . urlencode($search_term))); ?>" 
                               class="inline-block px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-primary-100 dark:hover:bg-primary-900 hover:text-primary-700 dark:hover:text-primary-300 transition-colors duration-200">
                                <?php echo esc_html($search_term); ?>
                            </a>
                            <?php
                        endforeach;
                        ?>
                    </div>
                </div>
                
                <!-- Recent Searches (if user has search history) -->
                <?php if (is_user_logged_in()) : ?>
                    <div class="recent-searches mt-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">
                            <?php esc_html_e('Recent Searches', 'aqualuxe'); ?>
                        </h3>
                        <div id="recent-searches-list" class="space-y-2">
                            <!-- Recent searches will be populated via JavaScript -->
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search modal functionality
    const searchToggle = document.getElementById('search-toggle');
    const searchModal = document.getElementById('search-modal');
    const closeSearchModal = document.getElementById('close-search-modal');
    const modalSearchInput = document.querySelector('#modal-search-form input[type="search"]');
    
    if (searchToggle && searchModal) {
        searchToggle.addEventListener('click', function() {
            searchModal.classList.remove('hidden');
            searchModal.setAttribute('aria-hidden', 'false');
            if (modalSearchInput) {
                modalSearchInput.focus();
            }
        });
        
        if (closeSearchModal) {
            closeSearchModal.addEventListener('click', function() {
                searchModal.classList.add('hidden');
                searchModal.setAttribute('aria-hidden', 'true');
            });
        }
        
        // Close on backdrop click
        searchModal.addEventListener('click', function(e) {
            if (e.target === searchModal) {
                searchModal.classList.add('hidden');
                searchModal.setAttribute('aria-hidden', 'true');
            }
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                searchModal.classList.add('hidden');
                searchModal.setAttribute('aria-hidden', 'true');
            }
        });
    }
    
    // Auto-submit on suggestion click
    const suggestionLinks = document.querySelectorAll('.search-suggestions a');
    suggestionLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const searchTerm = this.textContent.trim();
            if (modalSearchInput) {
                modalSearchInput.value = searchTerm;
                modalSearchInput.form.submit();
            }
        });
    });
});
</script>