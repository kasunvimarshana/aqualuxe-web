<?php
/**
 * Advanced Live Search Functionality
 *
 * @package AquaLuxe
 */

/**
 * Advanced Search Widget with Live Results
 */
function aqualuxe_advanced_search_widget($echo = true) {
    ob_start();
    ?>

    <div class="advanced-search-widget relative" id="advanced-search-widget">
        <!-- Search Form -->
        <form role="search" method="get" class="search-form relative" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="search-input-container relative">
                <input type="search"
                       class="search-field w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200"
                       placeholder="Search products, categories..."
                       value="<?php echo get_search_query(); ?>"
                       name="s"
                       autocomplete="off"
                       id="live-search-input">

                <!-- Search Icon -->
                <div class="search-icon absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Loading Spinner -->
                <div class="search-loading absolute right-12 top-1/2 transform -translate-y-1/2 hidden" id="search-loading">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-cyan-600"></div>
                </div>

                <!-- Clear Button -->
                <button type="button"
                        class="clear-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden"
                        id="clear-search">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Hidden fields for WooCommerce -->
            <input type="hidden" name="post_type" value="product">
        </form>

        <!-- Live Search Results -->
        <div class="search-results absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-xl z-50 hidden" id="search-results">
            <!-- Results Header -->
            <div class="results-header px-4 py-3 border-b border-gray-100 hidden">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900" id="results-count"></span>
                    <button class="text-xs text-cyan-600 hover:text-cyan-800" id="view-all-results">
                        View All Results
                    </button>
                </div>
            </div>

            <!-- Search Suggestions -->
            <div class="search-suggestions px-4 py-2 border-b border-gray-50 hidden" id="search-suggestions">
                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Suggestions</h4>
                <div class="suggestions-list space-y-1" id="suggestions-list">
                    <!-- Populated via JavaScript -->
                </div>
            </div>

            <!-- Product Results -->
            <div class="product-results max-h-96 overflow-y-auto" id="product-results">
                <!-- Populated via JavaScript -->
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions px-4 py-3 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Press Enter to search, Esc to close</span>
                    <div class="flex space-x-2">
                        <button class="advanced-search-btn text-xs text-cyan-600 hover:text-cyan-800" id="advanced-search-toggle">
                            Advanced Search
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Search Panel -->
        <div class="advanced-search-panel absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-xl z-50 hidden" id="advanced-search-panel">
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Advanced Search</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Category Filter -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="search_category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="">All Categories</option>
                            <?php
                            $categories = get_terms([
                                'taxonomy' => 'product_cat',
                                'hide_empty' => true
                            ]);
                            foreach ($categories as $category) {
                                echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <div class="flex space-x-2">
                            <input type="number" name="min_price" placeholder="Min" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <input type="number" name="max_price" placeholder="Max" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>
                    </div>

                    <!-- Availability -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="stock_status" value="" class="form-radio h-4 w-4 text-cyan-600" checked>
                                <span class="ml-2 text-sm text-gray-700">All</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="stock_status" value="instock" class="form-radio h-4 w-4 text-cyan-600">
                                <span class="ml-2 text-sm text-gray-700">In Stock</span>
                            </label>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Rating</label>
                        <select name="min_rating" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="">Any Rating</option>
                            <option value="4">4+ Stars</option>
                            <option value="3">3+ Stars</option>
                            <option value="2">2+ Stars</option>
                            <option value="1">1+ Stars</option>
                        </select>
                    </div>
                </div>

                <!-- Advanced Search Actions -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50" id="reset-advanced-search">
                        Reset
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-cyan-600 border border-transparent rounded-md hover:bg-cyan-700" id="apply-advanced-search">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
    .search-results {
        animation: fadeInDown 0.2s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-result-item {
        transition: all 0.2s ease;
    }

    .product-result-item:hover {
        background-color: #f9fafb;
    }

    .suggestion-item {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background-color: #f3f4f6;
        color: #0891b2;
    }

    .search-field:focus + .search-icon svg {
        color: #0891b2;
    }

    /* Keyboard navigation styles */
    .result-item.selected {
        background-color: #f0f9ff;
        border-left: 3px solid #0891b2;
    }

    /* Mobile optimizations */
    @media (max-width: 768px) {
        .search-results,
        .advanced-search-panel {
            left: -20px;
            right: -20px;
            width: calc(100vw - 40px);
        }
    }

    /* Loading animation */
    .search-loading .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('live-search-input');
        const searchResults = document.getElementById('search-results');
        const searchWidget = document.getElementById('advanced-search-widget');
        const clearButton = document.getElementById('clear-search');
        const searchLoading = document.getElementById('search-loading');
        const advancedPanel = document.getElementById('advanced-search-panel');

        let searchTimeout;
        let currentResults = [];
        let selectedIndex = -1;

        // Search input handlers
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            if (query.length >= 2) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => performLiveSearch(query), 300);
                clearButton.classList.remove('hidden');
            } else {
                hideSearchResults();
                clearButton.classList.add('hidden');
            }
        });

        // Clear search
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            hideSearchResults();
            this.classList.add('hidden');
            searchInput.focus();
        });

        // Keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const resultItems = searchResults.querySelectorAll('.result-item');

            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, resultItems.length - 1);
                    updateSelectedResult(resultItems);
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelectedResult(resultItems);
                    break;

                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0 && resultItems[selectedIndex]) {
                        const link = resultItems[selectedIndex].querySelector('a');
                        if (link) link.click();
                    } else {
                        this.closest('form').submit();
                    }
                    break;

                case 'Escape':
                    hideSearchResults();
                    this.blur();
                    break;
            }
        });

        // Close results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchWidget.contains(e.target)) {
                hideSearchResults();
                hideAdvancedPanel();
            }
        });

        // Advanced search toggle
        document.getElementById('advanced-search-toggle')?.addEventListener('click', function() {
            advancedPanel.classList.toggle('hidden');
            searchResults.classList.add('hidden');
        });

        function performLiveSearch(query) {
            showLoading(true);

            fetch(`${aqualuxe_search.ajax_url}?action=aqualuxe_live_search&query=${encodeURIComponent(query)}&nonce=${aqualuxe_search.nonce}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displaySearchResults(data.data);
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                })
                .finally(() => {
                    showLoading(false);
                });
        }

        function displaySearchResults(results) {
            currentResults = results;
            selectedIndex = -1;

            const productResults = document.getElementById('product-results');
            const resultsHeader = document.querySelector('.results-header');
            const resultsCount = document.getElementById('results-count');

            // Clear previous results
            productResults.innerHTML = '';

            if (results.products && results.products.length > 0) {
                // Update results count
                resultsCount.textContent = `${results.products.length} products found`;
                resultsHeader.classList.remove('hidden');

                // Display products
                results.products.forEach((product, index) => {
                    const productHtml = `
                        <div class="product-result-item result-item flex items-center p-3 border-b border-gray-50 last:border-0" data-index="${index}">
                            <div class="product-image flex-shrink-0 mr-3">
                                <img src="${product.image}" alt="${product.title}" class="w-12 h-12 object-cover rounded">
                            </div>
                            <div class="product-info flex-1">
                                <a href="${product.url}" class="block">
                                    <h4 class="text-sm font-medium text-gray-900 hover:text-cyan-600 transition-colors duration-200">
                                        ${product.title}
                                    </h4>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm font-medium text-cyan-600">${product.price}</span>
                                        ${product.rating ? `
                                            <div class="flex items-center text-yellow-400 text-xs">
                                                ${'★'.repeat(Math.floor(product.rating))}
                                                <span class="text-gray-500 ml-1">(${product.reviews})</span>
                                            </div>
                                        ` : ''}
                                    </div>
                                </a>
                            </div>
                        </div>
                    `;
                    productResults.insertAdjacentHTML('beforeend', productHtml);
                });

                showSearchResults();
            } else {
                // No results found
                productResults.innerHTML = `
                    <div class="no-results text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <p class="text-sm">No products found for "${searchInput.value}"</p>
                        <button class="text-cyan-600 hover:text-cyan-800 text-sm mt-2" onclick="document.getElementById('advanced-search-toggle').click()">
                            Try Advanced Search
                        </button>
                    </div>
                `;
                resultsHeader.classList.add('hidden');
                showSearchResults();
            }
        }

        function showSearchResults() {
            searchResults.classList.remove('hidden');
            hideAdvancedPanel();
        }

        function hideSearchResults() {
            searchResults.classList.add('hidden');
        }

        function hideAdvancedPanel() {
            advancedPanel.classList.add('hidden');
        }

        function showLoading(show) {
            if (show) {
                searchLoading.classList.remove('hidden');
            } else {
                searchLoading.classList.add('hidden');
            }
        }

        function updateSelectedResult(items) {
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('selected');
                    item.scrollIntoView({ block: 'nearest' });
                } else {
                    item.classList.remove('selected');
                }
            });
        }
    });
    </script>

    <?php
    $output = ob_get_clean();

    if ($echo) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * AJAX handler for live search
 */
add_action('wp_ajax_aqualuxe_live_search', 'aqualuxe_ajax_live_search');
add_action('wp_ajax_nopriv_aqualuxe_live_search', 'aqualuxe_ajax_live_search');

function aqualuxe_ajax_live_search() {
    // Security check
    if (!wp_verify_nonce($_GET['nonce'], 'aqualuxe_search_nonce')) {
        wp_send_json_error('Security check failed');
    }

    $query = sanitize_text_field($_GET['query']);

    if (empty($query) || strlen($query) < 2) {
        wp_send_json_error('Query too short');
    }

    // Search for products
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 8,
        's' => $query,
        'meta_query' => array(
            array(
                'key' => '_visibility',
                'value' => array('catalog', 'visible'),
                'compare' => 'IN'
            )
        )
    );

    $products_query = new WP_Query($args);
    $products = array();

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            global $product;

            $products[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_permalink(),
                'price' => $product->get_price_html(),
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail') ?: wc_placeholder_img_src(),
                'rating' => $product->get_average_rating(),
                'reviews' => $product->get_review_count(),
                'in_stock' => $product->is_in_stock()
            );
        }
    }

    wp_reset_postdata();

    $response = array(
        'products' => $products,
        'total_found' => $products_query->found_posts
    );

    wp_send_json_success($response);
}

/**
 * Enqueue search scripts
 */
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_search_scripts');

function aqualuxe_enqueue_search_scripts() {
    wp_localize_script('aqualuxe-main', 'aqualuxe_search', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe_search_nonce'),
        'messages' => array(
            'no_results' => __('No products found', 'aqualuxe'),
            'searching' => __('Searching...', 'aqualuxe')
        )
    ));
}
?>
