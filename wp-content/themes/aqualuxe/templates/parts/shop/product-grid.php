<?php
/**
 * Advanced Product Grid Display
 *
 * @package AquaLuxe
 */

// Get current view mode (grid or list)
$view_mode = isset($_COOKIE['aqualuxe_view_mode']) ? $_COOKIE['aqualuxe_view_mode'] : 'grid';
$products_per_page = get_option('posts_per_page', 12);
$current_page = max(1, get_query_var('paged'));

// Get total products count
global $wp_query;
$total_products = $wp_query->found_posts;
$showing_start = (($current_page - 1) * $products_per_page) + 1;
$showing_end = min($current_page * $products_per_page, $total_products);
?>

<div class="product-grid-container">
    <!-- Toolbar -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-6 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="flex items-center space-x-4 mb-4 lg:mb-0">
            <!-- Results Count -->
            <div class="text-sm text-gray-600">
                <?php if ($total_products > 0): ?>
                    Showing <span class="font-medium"><?php echo $showing_start; ?>-<?php echo $showing_end; ?></span>
                    of <span class="font-medium"><?php echo $total_products; ?></span> products
                <?php else: ?>
                    No products found
                <?php endif; ?>
            </div>

            <!-- Products Per Page -->
            <select name="products_per_page" id="products-per-page" class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                <option value="12" <?php selected($products_per_page, 12); ?>>12 per page</option>
                <option value="24" <?php selected($products_per_page, 24); ?>>24 per page</option>
                <option value="36" <?php selected($products_per_page, 36); ?>>36 per page</option>
                <option value="48" <?php selected($products_per_page, 48); ?>>48 per page</option>
            </select>
        </div>

        <div class="flex items-center space-x-4">
            <!-- View Mode Toggle -->
            <div class="flex items-center bg-gray-100 rounded-lg p-1">
                <button class="view-mode-btn <?php echo $view_mode === 'grid' ? 'active' : ''; ?>"
                        data-mode="grid"
                        title="Grid View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
                <button class="view-mode-btn <?php echo $view_mode === 'list' ? 'active' : ''; ?>"
                        data-mode="list"
                        title="List View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Sort Options -->
            <select name="orderby" id="product-sort" class="text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                <option value="menu_order">Default Sorting</option>
                <option value="popularity">Sort by Popularity</option>
                <option value="rating">Sort by Average Rating</option>
                <option value="date">Sort by Latest</option>
                <option value="price">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
            </select>
        </div>
    </div>

    <!-- Product Grid/List -->
    <div class="products-container <?php echo $view_mode; ?>-view" id="products-container">
        <?php if (woocommerce_product_loop()): ?>
            <ul class="products grid gap-6 <?php echo $view_mode === 'grid' ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4' : 'grid-cols-1'; ?>">
                <?php while (have_posts()): the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <!-- No Products Found -->
            <div class="no-products-found text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-600 mb-6">We couldn't find any products matching your current filters. Try adjusting your search criteria.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button onclick="clearAllFilters()" class="px-6 py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors duration-200">
                            Clear All Filters
                        </button>
                        <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Browse All Products
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay overlay-hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" id="loading-overlay">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-cyan-600"></div>
            <span class="text-gray-700">Loading products...</span>
        </div>
    </div>
</div>

<style>
.view-mode-btn {
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.2s;
    color: #6b7280;
}

.view-mode-btn:hover {
    color: #374151;
    background-color: #f3f4f6;
}

.view-mode-btn.active {
    color: #0891b2;
    background-color: white;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

/* Grid View Styles */
.products.grid-cols-1 .product {
    display: block;
}

/* List View Styles */
.list-view .products {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.list-view .product {
    display: flex !important;
    flex-direction: row;
    align-items: center;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: white;
    transition: all 0.2s;
}

.list-view .product:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transform: translateY(-1px);
}

.list-view .product-image {
    width: 120px;
    height: 120px;
    flex-shrink: 0;
    margin-right: 1.5rem;
}

.list-view .product-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.list-view .product-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.list-view .product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0891b2;
    margin-bottom: 0.75rem;
}

.list-view .product-excerpt {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.list-view .product-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Animation for view transitions */
.products-container {
    transition: all 0.3s ease-in-out;
}

.product {
    transition: all 0.2s ease-in-out;
}

/* Loading states */
.loading-overlay {
    backdrop-filter: blur(2px);
}

.overlay-hidden {
    display: none;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .list-view .product {
        flex-direction: column;
        text-align: center;
    }

    .list-view .product-image {
        width: 100%;
        height: 200px;
        margin-right: 0;
        margin-bottom: 1rem;
    }
}

/* Product hover effects */
.product:hover .product-image img {
    transform: scale(1.05);
}

.product .product-image img {
    transition: transform 0.3s ease;
}

/* Custom scrollbar for better UX */
.products-container::-webkit-scrollbar {
    width: 6px;
}

.products-container::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.products-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.products-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View mode toggle functionality
    const viewModeButtons = document.querySelectorAll('.view-mode-btn');
    const productsContainer = document.getElementById('products-container');

    viewModeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const mode = this.dataset.mode;

            // Update button states
            viewModeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Update container class
            productsContainer.className = productsContainer.className.replace(/(grid|list)-view/, mode + '-view');

            // Save preference
            document.cookie = `aqualuxe_view_mode=${mode}; path=/; max-age=2592000`; // 30 days

            // Trigger layout update
            triggerLayoutUpdate();
        });
    });

    // Products per page change
    const productsPerPageSelect = document.getElementById('products-per-page');
    if (productsPerPageSelect) {
        productsPerPageSelect.addEventListener('change', function() {
            updateUrlAndReload('posts_per_page', this.value);
        });
    }

    // Sort change
    const sortSelect = document.getElementById('product-sort');
    if (sortSelect) {
        // Set current sort value
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('orderby') || 'menu_order';
        sortSelect.value = currentSort;

        sortSelect.addEventListener('change', function() {
            updateUrlAndReload('orderby', this.value);
        });
    }

    // AJAX product loading (for future enhancement)
    function loadProductsAjax(params) {
        const loadingOverlay = document.getElementById('loading-overlay');
        const productsContainer = document.getElementById('products-container');

        loadingOverlay.classList.remove('overlay-hidden');

        // Simulate AJAX call (replace with actual AJAX implementation)
        fetch(window.location.pathname + '?' + params, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            // Update products container with new content
            // This would need server-side support to return just the products HTML
            loadingOverlay.classList.add('overlay-hidden');
        })
        .catch(error => {
            console.error('Error loading products:', error);
            loadingOverlay.classList.add('overlay-hidden');
        });
    }

    function updateUrlAndReload(param, value) {
        const urlParams = new URLSearchParams(window.location.search);
        if (value) {
            urlParams.set(param, value);
        } else {
            urlParams.delete(param);
        }

        // Remove page parameter when changing filters
        if (param !== 'paged') {
            urlParams.delete('paged');
        }

        const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.location.href = newUrl;
    }

    function triggerLayoutUpdate() {
        // Trigger any necessary layout recalculations
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: no-preference)').matches) {
            const products = document.querySelectorAll('.product');
            products.forEach((product, index) => {
                product.style.animationDelay = `${index * 50}ms`;
                product.classList.add('animate-fade-in');
            });
        }
    }

    // Clear all filters function (global)
    window.clearAllFilters = function() {
        // Remove all query parameters except basic ones
        const newUrl = window.location.pathname;
        window.location.href = newUrl;
    };

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case '1':
                    e.preventDefault();
                    document.querySelector('[data-mode="grid"]')?.click();
                    break;
                case '2':
                    e.preventDefault();
                    document.querySelector('[data-mode="list"]')?.click();
                    break;
            }
        }
    });

    // Initialize layout
    triggerLayoutUpdate();
});
</script>
