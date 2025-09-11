/**
 * Shop-specific JavaScript functionality
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

class AquaLuxeShop {
    constructor() {
        this.init();
    }

    init() {
        this.initProductHover();
        this.initProductComparison();
        this.initGridToggle();
        this.initSortingFilters();
        this.initLoadMore();
    }

    initProductHover() {
        const productItems = document.querySelectorAll('.product-item');
        
        productItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                const hoverImage = item.querySelector('.product-hover-image');
                if (hoverImage) {
                    hoverImage.style.opacity = '1';
                }
            });

            item.addEventListener('mouseleave', () => {
                const hoverImage = item.querySelector('.product-hover-image');
                if (hoverImage) {
                    hoverImage.style.opacity = '0';
                }
            });
        });
    }

    initProductComparison() {
        const compareButtons = document.querySelectorAll('.compare-btn');
        
        compareButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleComparison(button);
            });
        });
    }

    toggleComparison(button) {
        const productId = button.dataset.productId;
        const isInComparison = button.classList.contains('in-comparison');
        
        if (isInComparison) {
            this.removeFromComparison(productId, button);
        } else {
            this.addToComparison(productId, button);
        }
    }

    addToComparison(productId, button) {
        const comparison = this.getComparison();
        
        if (comparison.length >= 4) {
            alert('You can compare up to 4 products only.');
            return;
        }

        comparison.push(productId);
        localStorage.setItem('aqualuxe_comparison', JSON.stringify(comparison));
        
        button.classList.add('in-comparison');
        button.title = 'Remove from comparison';
        
        this.updateComparisonCounter();
    }

    removeFromComparison(productId, button) {
        let comparison = this.getComparison();
        comparison = comparison.filter(id => id !== productId);
        
        localStorage.setItem('aqualuxe_comparison', JSON.stringify(comparison));
        
        button.classList.remove('in-comparison');
        button.title = 'Add to comparison';
        
        this.updateComparisonCounter();
    }

    getComparison() {
        const stored = localStorage.getItem('aqualuxe_comparison');
        return stored ? JSON.parse(stored) : [];
    }

    updateComparisonCounter() {
        const comparison = this.getComparison();
        const counters = document.querySelectorAll('.comparison-counter');
        
        counters.forEach(counter => {
            counter.textContent = comparison.length;
            counter.style.display = comparison.length > 0 ? 'inline' : 'none';
        });
    }

    initGridToggle() {
        const gridButtons = document.querySelectorAll('.grid-toggle-btn');
        const productsGrid = document.querySelector('.products-grid');
        
        gridButtons.forEach(button => {
            button.addEventListener('click', () => {
                const columns = button.dataset.columns;
                
                // Remove active class from all buttons
                gridButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Update grid layout
                if (productsGrid) {
                    productsGrid.className = `products-grid grid-cols-${columns}`;
                }
                
                // Store preference
                localStorage.setItem('aqualuxe_grid_columns', columns);
            });
        });

        // Restore saved preference
        const savedColumns = localStorage.getItem('aqualuxe_grid_columns');
        if (savedColumns) {
            const targetButton = document.querySelector(`[data-columns="${savedColumns}"]`);
            if (targetButton) {
                targetButton.click();
            }
        }
    }

    initSortingFilters() {
        const sortSelect = document.querySelector('.product-sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', () => {
                this.applySorting(sortSelect.value);
            });
        }

        // Price range filter
        const priceRangeInputs = document.querySelectorAll('.price-range-input');
        priceRangeInputs.forEach(input => {
            input.addEventListener('change', () => {
                this.applyPriceFilter();
            });
        });

        // Category filters
        const categoryFilters = document.querySelectorAll('.category-filter');
        categoryFilters.forEach(filter => {
            filter.addEventListener('change', () => {
                this.applyCategoryFilter();
            });
        });
    }

    applySorting(sortBy) {
        const productsGrid = document.querySelector('.products-grid');
        if (!productsGrid) return;

        productsGrid.classList.add('loading');

        const formData = new FormData();
        formData.append('action', 'aqualuxe_sort_products');
        formData.append('sort_by', sortBy);
        formData.append('nonce', window.AQUALUXE.nonce);

        fetch(window.AQUALUXE.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                productsGrid.innerHTML = data.data.html;
                this.initProductHover(); // Re-initialize hover effects
            }
        })
        .catch(error => {
            console.error('Sorting error:', error);
        })
        .finally(() => {
            productsGrid.classList.remove('loading');
        });
    }

    applyPriceFilter() {
        const minPrice = document.querySelector('.price-range-min')?.value || 0;
        const maxPrice = document.querySelector('.price-range-max')?.value || 99999;
        
        this.filterProducts({ min_price: minPrice, max_price: maxPrice });
    }

    applyCategoryFilter() {
        const selectedCategories = [];
        const categoryCheckboxes = document.querySelectorAll('.category-filter:checked');
        
        categoryCheckboxes.forEach(checkbox => {
            selectedCategories.push(checkbox.value);
        });

        this.filterProducts({ categories: selectedCategories });
    }

    filterProducts(filters) {
        const productsGrid = document.querySelector('.products-grid');
        if (!productsGrid) return;

        productsGrid.classList.add('loading');

        const formData = new FormData();
        formData.append('action', 'aqualuxe_filter_products');
        formData.append('filters', JSON.stringify(filters));
        formData.append('nonce', window.AQUALUXE.nonce);

        fetch(window.AQUALUXE.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                productsGrid.innerHTML = data.data.html;
                this.initProductHover(); // Re-initialize hover effects
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
        })
        .finally(() => {
            productsGrid.classList.remove('loading');
        });
    }

    initLoadMore() {
        const loadMoreBtn = document.querySelector('.load-more-products');
        if (!loadMoreBtn) return;

        loadMoreBtn.addEventListener('click', () => {
            this.loadMoreProducts();
        });
    }

    loadMoreProducts() {
        const loadMoreBtn = document.querySelector('.load-more-products');
        const productsGrid = document.querySelector('.products-grid');
        
        if (!loadMoreBtn || !productsGrid) return;

        const currentPage = parseInt(loadMoreBtn.dataset.page) || 1;
        const nextPage = currentPage + 1;

        loadMoreBtn.classList.add('loading');
        loadMoreBtn.disabled = true;

        const formData = new FormData();
        formData.append('action', 'aqualuxe_load_more_products');
        formData.append('page', nextPage);
        formData.append('nonce', window.AQUALUXE.nonce);

        fetch(window.AQUALUXE.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.html) {
                // Append new products
                productsGrid.insertAdjacentHTML('beforeend', data.data.html);
                
                // Update page number
                loadMoreBtn.dataset.page = nextPage;
                
                // Hide button if no more products
                if (!data.data.has_more) {
                    loadMoreBtn.style.display = 'none';
                }

                // Re-initialize effects for new products
                this.initProductHover();
            } else {
                loadMoreBtn.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Load more error:', error);
        })
        .finally(() => {
            loadMoreBtn.classList.remove('loading');
            loadMoreBtn.disabled = false;
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new AquaLuxeShop();
});