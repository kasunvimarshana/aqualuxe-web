/**
 * Product Filter Module
 * 
 * Handles WooCommerce product filtering functionality:
 * - AJAX filtering
 * - Price range sliders
 * - Attribute filters
 * - Category filters
 * - Sorting options
 */

class ProductFilter {
    constructor() {
        this.filterForm = document.querySelector('.product-filter-form');
        this.productsContainer = document.querySelector('.products');
        this.priceRangeSliders = document.querySelectorAll('.price-range-slider');
        this.attributeFilters = document.querySelectorAll('.attribute-filter');
        this.categoryFilters = document.querySelectorAll('.category-filter');
        this.sortingSelect = document.querySelector('.woocommerce-ordering select');
        this.resetButton = document.querySelector('.reset-filters');
        this.activeFiltersContainer = document.querySelector('.active-filters');
        this.loadingClass = 'loading';
        this.ajaxUrl = window.aqualuxe?.ajaxUrl || '/wp-admin/admin-ajax.php';
        this.nonce = window.aqualuxe?.nonce || '';
    }

    init() {
        if (!this.filterForm || !this.productsContainer) {
            return;
        }

        this.setupFilterForm();
        this.setupPriceRangeSliders();
        this.setupAttributeFilters();
        this.setupCategoryFilters();
        this.setupSortingSelect();
        this.setupResetButton();
        this.setupActiveFilters();
        this.setupMobileFilters();
    }

    setupFilterForm() {
        this.filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });
        
        // Auto-submit on input change if data-auto-submit is set
        const autoSubmitInputs = this.filterForm.querySelectorAll('[data-auto-submit]');
        autoSubmitInputs.forEach(input => {
            const eventType = input.tagName === 'SELECT' ? 'change' : 'input';
            
            input.addEventListener(eventType, () => {
                // Add small delay for better UX
                clearTimeout(input.autoSubmitTimeout);
                input.autoSubmitTimeout = setTimeout(() => {
                    this.applyFilters();
                }, 500);
            });
        });
    }

    setupPriceRangeSliders() {
        if (!this.priceRangeSliders.length || !window.noUiSlider) {
            return;
        }

        this.priceRangeSliders.forEach(slider => {
            const minInput = document.getElementById(slider.dataset.minInput);
            const maxInput = document.getElementById(slider.dataset.maxInput);
            const minValue = parseFloat(slider.dataset.min);
            const maxValue = parseFloat(slider.dataset.max);
            const currentMinValue = parseFloat(minInput.value) || minValue;
            const currentMaxValue = parseFloat(maxInput.value) || maxValue;
            
            // Initialize noUiSlider
            noUiSlider.create(slider, {
                start: [currentMinValue, currentMaxValue],
                connect: true,
                step: 1,
                range: {
                    'min': minValue,
                    'max': maxValue
                },
                format: {
                    to: value => Math.round(value),
                    from: value => Math.round(value)
                }
            });
            
            // Update inputs when slider changes
            slider.noUiSlider.on('update', (values, handle) => {
                const value = values[handle];
                
                if (handle === 0) {
                    minInput.value = value;
                } else {
                    maxInput.value = value;
                }
            });
            
            // Apply filters when slider changes
            slider.noUiSlider.on('change', () => {
                if (slider.dataset.autoSubmit === 'true') {
                    this.applyFilters();
                }
            });
            
            // Update slider when inputs change
            minInput.addEventListener('change', () => {
                slider.noUiSlider.set([minInput.value, null]);
                
                if (slider.dataset.autoSubmit === 'true') {
                    this.applyFilters();
                }
            });
            
            maxInput.addEventListener('change', () => {
                slider.noUiSlider.set([null, maxInput.value]);
                
                if (slider.dataset.autoSubmit === 'true') {
                    this.applyFilters();
                }
            });
        });
    }

    setupAttributeFilters() {
        if (!this.attributeFilters.length) {
            return;
        }

        this.attributeFilters.forEach(filter => {
            const inputs = filter.querySelectorAll('input[type="checkbox"], input[type="radio"]');
            
            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    if (filter.dataset.autoSubmit === 'true') {
                        this.applyFilters();
                    }
                });
            });
        });
    }

    setupCategoryFilters() {
        if (!this.categoryFilters.length) {
            return;
        }

        this.categoryFilters.forEach(filter => {
            const links = filter.querySelectorAll('a');
            
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    // Update hidden input with category ID
                    const categoryInput = document.getElementById(filter.dataset.input);
                    if (categoryInput) {
                        categoryInput.value = link.dataset.categoryId;
                    }
                    
                    // Update active class
                    links.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                    
                    if (filter.dataset.autoSubmit === 'true') {
                        this.applyFilters();
                    }
                });
            });
        });
    }

    setupSortingSelect() {
        if (!this.sortingSelect) {
            return;
        }

        this.sortingSelect.addEventListener('change', () => {
            this.applyFilters();
        });
    }

    setupResetButton() {
        if (!this.resetButton) {
            return;
        }

        this.resetButton.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Reset form inputs
            this.filterForm.reset();
            
            // Reset price range sliders
            this.priceRangeSliders.forEach(slider => {
                if (slider.noUiSlider) {
                    slider.noUiSlider.set([
                        parseFloat(slider.dataset.min),
                        parseFloat(slider.dataset.max)
                    ]);
                }
            });
            
            // Reset category filters
            this.categoryFilters.forEach(filter => {
                const links = filter.querySelectorAll('a');
                links.forEach(l => l.classList.remove('active'));
                
                // Reset hidden input
                const categoryInput = document.getElementById(filter.dataset.input);
                if (categoryInput) {
                    categoryInput.value = '';
                }
            });
            
            // Apply reset filters
            this.applyFilters();
        });
    }

    setupActiveFilters() {
        if (!this.activeFiltersContainer) {
            return;
        }

        // This will be populated after filters are applied
        document.addEventListener('filtersApplied', () => {
            this.updateActiveFilters();
        });
    }

    setupMobileFilters() {
        const mobileToggle = document.querySelector('.mobile-filter-toggle');
        const filterSidebar = document.querySelector('.filter-sidebar');
        
        if (!mobileToggle || !filterSidebar) {
            return;
        }
        
        mobileToggle.addEventListener('click', (e) => {
            e.preventDefault();
            filterSidebar.classList.toggle('active');
            document.body.classList.toggle('filter-sidebar-open');
        });
        
        // Close button
        const closeButton = filterSidebar.querySelector('.close-filters');
        if (closeButton) {
            closeButton.addEventListener('click', (e) => {
                e.preventDefault();
                filterSidebar.classList.remove('active');
                document.body.classList.remove('filter-sidebar-open');
            });
        }
        
        // Close on overlay click
        document.addEventListener('click', (e) => {
            if (filterSidebar.classList.contains('active') && 
                !filterSidebar.contains(e.target) && 
                e.target !== mobileToggle) {
                filterSidebar.classList.remove('active');
                document.body.classList.remove('filter-sidebar-open');
            }
        });
    }

    applyFilters() {
        // Add loading state
        this.productsContainer.classList.add(this.loadingClass);
        
        // Append loading indicator
        const loadingElement = document.createElement('div');
        loadingElement.className = 'loading-indicator';
        loadingElement.innerHTML = '<div class="loading-spinner"><span class="screen-reader-text">Loading...</span></div>';
        this.productsContainer.appendChild(loadingElement);
        
        // Get form data
        const formData = new FormData(this.filterForm);
        formData.append('action', 'filter_products');
        formData.append('nonce', this.nonce);
        
        // Add sorting if available
        if (this.sortingSelect) {
            formData.append('orderby', this.sortingSelect.value);
        }
        
        // Make AJAX request
        fetch(this.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(response => {
            // Remove loading indicator
            loadingElement.remove();
            
            if (response.success && response.data) {
                // Replace products container content
                this.productsContainer.innerHTML = response.data.products;
                
                // Update product count
                const countElement = document.querySelector('.woocommerce-result-count');
                if (countElement && response.data.count) {
                    countElement.innerHTML = response.data.count;
                }
                
                // Update pagination
                const paginationElement = document.querySelector('.woocommerce-pagination');
                if (paginationElement && response.data.pagination) {
                    paginationElement.innerHTML = response.data.pagination;
                }
                
                // Update URL if needed
                if (window.history && window.history.pushState) {
                    window.history.pushState({}, '', response.data.url);
                }
                
                // Trigger event for other scripts
                document.dispatchEvent(new CustomEvent('filtersApplied', {
                    detail: { response: response.data }
                }));
            } else {
                console.error('AJAX filter failed:', response.data?.message || 'Unknown error');
                this.productsContainer.classList.add('ajax-error');
                
                // Show no results message
                this.productsContainer.innerHTML = '<p class="woocommerce-info">No products were found matching your selection.</p>';
            }
        })
        .catch(error => {
            console.error('AJAX error:', error);
            this.productsContainer.classList.add('ajax-error');
            loadingElement.remove();
            
            // Show error message
            this.productsContainer.innerHTML = '<p class="woocommerce-error">Error loading products. Please try again.</p>';
        })
        .finally(() => {
            // Reset loading state
            this.productsContainer.classList.remove(this.loadingClass);
        });
    }

    updateActiveFilters() {
        if (!this.activeFiltersContainer) {
            return;
        }

        const activeFilters = [];
        
        // Check price filters
        this.priceRangeSliders.forEach(slider => {
            const minInput = document.getElementById(slider.dataset.minInput);
            const maxInput = document.getElementById(slider.dataset.maxInput);
            const minValue = parseFloat(minInput.value);
            const maxValue = parseFloat(maxInput.value);
            const defaultMin = parseFloat(slider.dataset.min);
            const defaultMax = parseFloat(slider.dataset.max);
            
            if (minValue > defaultMin || maxValue < defaultMax) {
                activeFilters.push({
                    name: 'Price',
                    value: `${minValue} - ${maxValue}`,
                    param: `${minInput.name}=${minValue}&${maxInput.name}=${maxValue}`,
                    clear: () => {
                        minInput.value = defaultMin;
                        maxInput.value = defaultMax;
                        slider.noUiSlider.set([defaultMin, defaultMax]);
                        this.applyFilters();
                    }
                });
            }
        });
        
        // Check attribute filters
        this.attributeFilters.forEach(filter => {
            const inputs = filter.querySelectorAll('input[type="checkbox"]:checked, input[type="radio"]:checked');
            const filterName = filter.dataset.name || 'Attribute';
            
            inputs.forEach(input => {
                const label = filter.querySelector(`label[for="${input.id}"]`);
                
                activeFilters.push({
                    name: filterName,
                    value: label ? label.textContent : input.value,
                    param: `${input.name}=${input.value}`,
                    clear: () => {
                        input.checked = false;
                        this.applyFilters();
                    }
                });
            });
        });
        
        // Check category filters
        this.categoryFilters.forEach(filter => {
            const activeLink = filter.querySelector('a.active');
            
            if (activeLink) {
                const categoryInput = document.getElementById(filter.dataset.input);
                
                activeFilters.push({
                    name: 'Category',
                    value: activeLink.textContent,
                    param: `${categoryInput.name}=${categoryInput.value}`,
                    clear: () => {
                        activeLink.classList.remove('active');
                        categoryInput.value = '';
                        this.applyFilters();
                    }
                });
            }
        });
        
        // Update active filters display
        if (activeFilters.length > 0) {
            let html = '<ul class="active-filter-list">';
            
            activeFilters.forEach(filter => {
                html += `
                    <li>
                        <span class="filter-name">${filter.name}:</span>
                        <span class="filter-value">${filter.value}</span>
                        <button type="button" class="remove-filter" data-param="${filter.param}">
                            <span class="screen-reader-text">Remove filter</span>
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </li>
                `;
            });
            
            html += '</ul>';
            
            if (this.resetButton) {
                html += '<button type="button" class="clear-all-filters">Clear all filters</button>';
            }
            
            this.activeFiltersContainer.innerHTML = html;
            this.activeFiltersContainer.style.display = 'block';
            
            // Add event listeners to remove buttons
            const removeButtons = this.activeFiltersContainer.querySelectorAll('.remove-filter');
            removeButtons.forEach((button, index) => {
                button.addEventListener('click', () => {
                    activeFilters[index].clear();
                });
            });
            
            // Add event listener to clear all button
            const clearAllButton = this.activeFiltersContainer.querySelector('.clear-all-filters');
            if (clearAllButton && this.resetButton) {
                clearAllButton.addEventListener('click', () => {
                    this.resetButton.click();
                });
            }
        } else {
            this.activeFiltersContainer.innerHTML = '';
            this.activeFiltersContainer.style.display = 'none';
        }
    }
}

export default ProductFilter;