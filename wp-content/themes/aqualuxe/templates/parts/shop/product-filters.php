<?php
/**
 * Advanced Product Filter System
 *
 * @package AquaLuxe
 */

// Get current filters
$current_category = get_query_var('product_cat', '');
$current_price_min = get_query_var('min_price', '');
$current_price_max = get_query_var('max_price', '');
$current_stock = get_query_var('stock_status', '');

// Get product categories
$product_categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'parent' => 0
]);

// Get price ranges
$price_ranges = [
    ['min' => 0, 'max' => 50, 'label' => '$0 - $50'],
    ['min' => 50, 'max' => 100, 'label' => '$50 - $100'],
    ['min' => 100, 'max' => 200, 'label' => '$100 - $200'],
    ['min' => 200, 'max' => 500, 'label' => '$200 - $500'],
    ['min' => 500, 'max' => 999999, 'label' => '$500+']
];
?>

<div class="product-filters bg-white border border-gray-200 rounded-lg shadow-sm mb-8 overflow-hidden" id="product-filters">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Filter Products</h3>
            <button class="lg:hidden text-gray-500 hover:text-gray-700" id="toggle-filters">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707v6.586a1 1 0 01-1.447.894l-4-2A1 1 0 018 18.586v-4.586a1 1 0 00-.293-.707L1.293 7.293A1 1 0 011 6.586V4z" />
                </svg>
            </button>
        </div>
    </div>

    <div class="filter-content hidden lg:block" id="filter-content">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 p-6">

            <!-- Category Filter -->
            <div class="filter-group">
                <h4 class="font-medium text-gray-900 mb-3">Categories</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    <?php foreach ($product_categories as $category): ?>
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox"
                                   name="product_cat[]"
                                   value="<?php echo esc_attr($category->slug); ?>"
                                   <?php checked(in_array($category->slug, explode(',', $current_category))); ?>
                                   class="form-checkbox h-4 w-4 text-cyan-600 rounded border-gray-300 focus:ring-cyan-500">
                            <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">
                                <?php echo esc_html($category->name); ?>
                                <span class="text-gray-400">(<?php echo $category->count; ?>)</span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Price Range Filter -->
            <div class="filter-group">
                <h4 class="font-medium text-gray-900 mb-3">Price Range</h4>
                <div class="space-y-2">
                    <?php foreach ($price_ranges as $range): ?>
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio"
                                   name="price_range"
                                   value="<?php echo $range['min'] . '-' . $range['max']; ?>"
                                   class="form-radio h-4 w-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                            <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">
                                <?php echo esc_html($range['label']); ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- Custom Price Range -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Custom Range</label>
                    <div class="flex items-center space-x-2">
                        <input type="number"
                               name="min_price"
                               placeholder="Min"
                               value="<?php echo esc_attr($current_price_min); ?>"
                               class="block w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                        <span class="text-gray-400">to</span>
                        <input type="number"
                               name="max_price"
                               placeholder="Max"
                               value="<?php echo esc_attr($current_price_max); ?>"
                               class="block w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                    </div>
                </div>
            </div>

            <!-- Stock Status Filter -->
            <div class="filter-group">
                <h4 class="font-medium text-gray-900 mb-3">Availability</h4>
                <div class="space-y-2">
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio"
                               name="stock_status"
                               value=""
                               <?php checked($current_stock, ''); ?>
                               class="form-radio h-4 w-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                        <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">All Products</span>
                    </label>
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio"
                               name="stock_status"
                               value="instock"
                               <?php checked($current_stock, 'instock'); ?>
                               class="form-radio h-4 w-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                        <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">
                            <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                            In Stock
                        </span>
                    </label>
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio"
                               name="stock_status"
                               value="outofstock"
                               <?php checked($current_stock, 'outofstock'); ?>
                               class="form-radio h-4 w-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                        <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">
                            <span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                            Out of Stock
                        </span>
                    </label>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="filter-group">
                <h4 class="font-medium text-gray-900 mb-3">Sort By</h4>
                <select name="orderby" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 text-sm">
                    <option value="menu_order">Default Sorting</option>
                    <option value="popularity">Sort by Popularity</option>
                    <option value="rating">Sort by Average Rating</option>
                    <option value="date">Sort by Latest</option>
                    <option value="price">Sort by Price: Low to High</option>
                    <option value="price-desc">Sort by Price: High to Low</option>
                </select>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <button type="button" class="text-sm text-gray-500 hover:text-gray-700" id="clear-filters">
                    Clear All Filters
                </button>
                <div class="flex space-x-3">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-cyan-600 border border-transparent rounded-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500" id="apply-filters">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Filters Display -->
<div class="active-filters mb-6" id="active-filters" style="display: none;">
    <h4 class="text-sm font-medium text-gray-900 mb-2">Active Filters:</h4>
    <div class="flex flex-wrap gap-2" id="active-filters-list">
        <!-- Active filters will be populated via JavaScript -->
    </div>
</div>

<style>
.filter-content {
    transition: all 0.3s ease-in-out;
}

.filter-group h4 {
    position: relative;
}

.filter-group h4::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 20px;
    height: 2px;
    background: linear-gradient(to right, #0891b2, #06b6d4);
}

.form-checkbox:checked {
    background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='m13.854 3.646-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L6.5 9.586l6.646-6.64a.5.5 0 0 1 .708.708z'/%3e%3c/svg%3e");
}

.form-radio:checked {
    background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
}

@media (max-width: 1023px) {
    .filter-content.hidden {
        max-height: 0;
        overflow: hidden;
        padding: 0;
    }

    .filter-content.show {
        max-height: 1000px;
        padding: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle filters on mobile
    const toggleBtn = document.getElementById('toggle-filters');
    const filterContent = document.getElementById('filter-content');

    if (toggleBtn && filterContent) {
        toggleBtn.addEventListener('click', function() {
            filterContent.classList.toggle('hidden');
            filterContent.classList.toggle('show');
        });
    }

    // Clear filters functionality
    const clearFiltersBtn = document.getElementById('clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Clear all form inputs
            const form = document.getElementById('product-filters');
            if (form) {
                const inputs = form.querySelectorAll('input, select');
                inputs.forEach(input => {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        input.checked = false;
                    } else {
                        input.value = '';
                    }
                });

                // Submit form to clear filters
                applyFilters();
            }
        });
    }

    // Apply filters functionality
    const applyFiltersBtn = document.getElementById('apply-filters');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', applyFilters);
    }

    // Auto-apply filters on change
    const filterInputs = document.querySelectorAll('#product-filters input, #product-filters select');
    filterInputs.forEach(input => {
        input.addEventListener('change', debounce(applyFilters, 300));
    });

    function applyFilters() {
        const form = document.getElementById('product-filters');
        const formData = new FormData(form);
        const params = new URLSearchParams();

        // Build query parameters
        for (let [key, value] of formData.entries()) {
            if (value) {
                if (key === 'product_cat[]') {
                    const existingCats = params.get('product_cat') || '';
                    params.set('product_cat', existingCats ? existingCats + ',' + value : value);
                } else if (key === 'price_range') {
                    const [min, max] = value.split('-');
                    params.set('min_price', min);
                    params.set('max_price', max);
                } else {
                    params.set(key, value);
                }
            }
        }

        // Update URL and reload page
        const currentUrl = new URL(window.location);
        const newUrl = currentUrl.pathname + '?' + params.toString();
        window.location.href = newUrl;
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Update active filters display
    updateActiveFilters();

    function updateActiveFilters() {
        const activeFiltersContainer = document.getElementById('active-filters');
        const activeFiltersList = document.getElementById('active-filters-list');

        if (!activeFiltersContainer || !activeFiltersList) return;

        const urlParams = new URLSearchParams(window.location.search);
        const activeFilters = [];

        // Check for active filters
        if (urlParams.get('product_cat')) {
            const categories = urlParams.get('product_cat').split(',');
            categories.forEach(cat => {
                activeFilters.push({
                    type: 'category',
                    value: cat,
                    label: cat.charAt(0).toUpperCase() + cat.slice(1),
                    param: 'product_cat'
                });
            });
        }

        if (urlParams.get('min_price') || urlParams.get('max_price')) {
            const min = urlParams.get('min_price') || '0';
            const max = urlParams.get('max_price') || '∞';
            activeFilters.push({
                type: 'price',
                value: min + '-' + max,
                label: `$${min} - $${max}`,
                param: 'price'
            });
        }

        if (urlParams.get('stock_status')) {
            activeFilters.push({
                type: 'stock',
                value: urlParams.get('stock_status'),
                label: urlParams.get('stock_status') === 'instock' ? 'In Stock' : 'Out of Stock',
                param: 'stock_status'
            });
        }

        // Display active filters
        if (activeFilters.length > 0) {
            activeFiltersList.innerHTML = activeFilters.map(filter => `
                <span class="inline-flex items-center px-3 py-1 text-sm bg-cyan-100 text-cyan-800 rounded-full">
                    ${filter.label}
                    <button type="button" class="ml-2 text-cyan-600 hover:text-cyan-800" onclick="removeFilter('${filter.param}', '${filter.value}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
            `).join('');
            activeFiltersContainer.style.display = 'block';
        } else {
            activeFiltersContainer.style.display = 'none';
        }
    }

    // Make removeFilter function global
    window.removeFilter = function(param, value) {
        const urlParams = new URLSearchParams(window.location.search);

        if (param === 'product_cat') {
            const categories = (urlParams.get('product_cat') || '').split(',').filter(cat => cat !== value);
            if (categories.length > 0) {
                urlParams.set('product_cat', categories.join(','));
            } else {
                urlParams.delete('product_cat');
            }
        } else if (param === 'price') {
            urlParams.delete('min_price');
            urlParams.delete('max_price');
        } else {
            urlParams.delete(param);
        }

        const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.location.href = newUrl;
    };
});
</script>
