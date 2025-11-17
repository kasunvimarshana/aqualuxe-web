/**
 * Product Filter Module
 * 
 * Handles the advanced product filtering functionality for WooCommerce.
 * Enhanced with accessibility features for keyboard navigation and screen readers.
 */

const ProductFilter = {
    /**
     * Initialize the product filter functionality
     */
    init() {
        // Only initialize on shop pages
        if (!document.querySelector('.product-filters')) {
            return;
        }

        this.cacheDOM();
        this.setupPriceSlider();
        this.bindEvents();
        this.setupA11y();
    },

    /**
     * Cache DOM elements
     */
    cacheDOM() {
        this.body = document.body;
        this.filterContainer = document.getElementById('filter-container');
        this.filterToggle = document.querySelector('.filter-toggle-button');
        this.filterClose = document.querySelector('.filter-close');
        this.filterForm = document.querySelector('.product-filters form');
        this.filterInputs = document.querySelectorAll('.product-filters input, .product-filters select');
        this.priceSlider = document.querySelector('.price-slider');
        this.filterApply = document.querySelector('.filter-apply');
        this.filterClear = document.querySelector('.filter-clear-all');
        this.activeFilters = document.querySelector('.active-filters');
        this.clearAllFilters = document.querySelector('.clear-all-filters');
        this.productsContainer = document.querySelector('.products');
        this.paginationContainer = document.querySelector('.woocommerce-pagination');
        this.orderby = document.querySelector('.woocommerce-ordering select');
        this.a11yAnnounce = document.getElementById('a11y-status-message') || this.createA11yAnnouncer();
    },

    /**
     * Create accessibility announcer element for screen readers
     * 
     * @returns {HTMLElement} The announcer element
     */
    createA11yAnnouncer() {
        const announcer = document.createElement('div');
        announcer.id = 'a11y-status-message';
        announcer.className = 'screen-reader-text';
        announcer.setAttribute('aria-live', 'polite');
        announcer.setAttribute('aria-atomic', 'true');
        document.body.appendChild(announcer);
        return announcer;
    },

    /**
     * Set up accessibility features
     */
    setupA11y() {
        // Add keyboard navigation for filter widgets
        this.setupFilterWidgetA11y();
        
        // Add keyboard navigation for price slider
        this.setupPriceSliderA11y();
        
        // Add keyboard navigation for active filters
        this.setupActiveFiltersA11y();
        
        // Add keyboard navigation for filter toggle
        this.setupFilterToggleA11y();
    },

    /**
     * Set up accessibility for filter widgets
     */
    setupFilterWidgetA11y() {
        // Make filter widget headers focusable if they have collapsible content
        const filterHeaders = document.querySelectorAll('.filter-widget-title[data-collapsible]');
        filterHeaders.forEach(header => {
            header.setAttribute('tabindex', '0');
            header.setAttribute('role', 'button');
            header.setAttribute('aria-expanded', 'true');
            
            // Add keyboard event listener
            header.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleFilterWidget(header);
                }
            });
            
            // Add click event listener
            header.addEventListener('click', () => {
                this.toggleFilterWidget(header);
            });
        });
    },

    /**
     * Toggle filter widget visibility
     * 
     * @param {HTMLElement} header The filter widget header
     */
    toggleFilterWidget(header) {
        const content = header.nextElementSibling;
        const isExpanded = header.getAttribute('aria-expanded') === 'true';
        
        header.setAttribute('aria-expanded', !isExpanded);
        content.style.display = isExpanded ? 'none' : 'block';
        
        // Announce state change to screen readers
        this.announceToScreenReader(
            isExpanded 
                ? `${header.textContent} filter collapsed` 
                : `${header.textContent} filter expanded`
        );
    },

    /**
     * Set up accessibility for price slider
     */
    setupPriceSliderA11y() {
        if (!this.priceSlider || typeof noUiSlider === 'undefined') {
            return;
        }
        
        // Add ARIA attributes to price slider
        this.priceSlider.setAttribute('role', 'group');
        this.priceSlider.setAttribute('aria-label', 'Price range slider');
        
        // Add keyboard support for price slider
        const minHandle = this.priceSlider.querySelector('.noUi-handle-lower');
        const maxHandle = this.priceSlider.querySelector('.noUi-handle-upper');
        
        if (minHandle && maxHandle) {
            // Make handles focusable
            minHandle.setAttribute('tabindex', '0');
            maxHandle.setAttribute('tabindex', '0');
            
            // Add ARIA labels
            minHandle.setAttribute('role', 'slider');
            minHandle.setAttribute('aria-label', 'Minimum price');
            maxHandle.setAttribute('role', 'slider');
            maxHandle.setAttribute('aria-label', 'Maximum price');
            
            // Add keyboard event listeners
            minHandle.addEventListener('keydown', (e) => this.handlePriceSliderKeydown(e, 'min'));
            maxHandle.addEventListener('keydown', (e) => this.handlePriceSliderKeydown(e, 'max'));
        }
    },

    /**
     * Handle keyboard events for price slider
     * 
     * @param {KeyboardEvent} e The keyboard event
     * @param {string} handle The handle type ('min' or 'max')
     */
    handlePriceSliderKeydown(e, handle) {
        if (!this.priceSlider || !this.priceSlider.noUiSlider) {
            return;
        }
        
        const values = this.priceSlider.noUiSlider.get();
        const min = parseFloat(this.priceSlider.dataset.min);
        const max = parseFloat(this.priceSlider.dataset.max);
        const step = (max - min) / 100; // 1% of range
        const smallStep = step;
        const largeStep = step * 10;
        
        let newValue;
        const handleIndex = handle === 'min' ? 0 : 1;
        const currentValue = parseFloat(values[handleIndex]);
        
        switch (e.key) {
            case 'ArrowLeft':
            case 'ArrowDown':
                e.preventDefault();
                newValue = Math.max(min, currentValue - smallStep);
                break;
            case 'ArrowRight':
            case 'ArrowUp':
                e.preventDefault();
                newValue = Math.min(max, currentValue + smallStep);
                break;
            case 'PageDown':
                e.preventDefault();
                newValue = Math.max(min, currentValue - largeStep);
                break;
            case 'PageUp':
                e.preventDefault();
                newValue = Math.min(max, currentValue + largeStep);
                break;
            case 'Home':
                e.preventDefault();
                newValue = min;
                break;
            case 'End':
                e.preventDefault();
                newValue = max;
                break;
            default:
                return;
        }
        
        // Update slider value
        if (newValue !== undefined) {
            const newValues = [...values];
            newValues[handleIndex] = newValue.toString();
            
            // Ensure min handle doesn't exceed max handle
            if (handleIndex === 0 && parseFloat(newValues[0]) > parseFloat(newValues[1])) {
                newValues[0] = newValues[1];
            }
            
            // Ensure max handle doesn't go below min handle
            if (handleIndex === 1 && parseFloat(newValues[1]) < parseFloat(newValues[0])) {
                newValues[1] = newValues[0];
            }
            
            this.priceSlider.noUiSlider.set(newValues);
            
            // Announce new value to screen readers
            const currency = document.querySelector('.price-slider-min').textContent.replace(/[0-9.]/g, '').trim();
            this.announceToScreenReader(
                handle === 'min' 
                    ? `Minimum price set to ${currency}${newValues[0]}` 
                    : `Maximum price set to ${currency}${newValues[1]}`
            );
        }
    },

    /**
     * Set up accessibility for active filters
     */
    setupActiveFiltersA11y() {
        // Add keyboard support for remove filter buttons
        const removeButtons = document.querySelectorAll('.remove-filter');
        
        removeButtons.forEach(button => {
            button.setAttribute('role', 'button');
            button.setAttribute('tabindex', '0');
            
            // Add keyboard event listener
            button.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    button.click();
                }
            });
        });
    },

    /**
     * Set up accessibility for filter toggle button
     */
    setupFilterToggleA11y() {
        if (!this.filterToggle) {
            return;
        }
        
        // Ensure proper ARIA attributes
        this.filterToggle.setAttribute('aria-haspopup', 'true');
        this.filterToggle.setAttribute('aria-expanded', 'false');
        this.filterToggle.setAttribute('aria-controls', 'filter-container');
        
        // Add keyboard event listener for Escape key to close filter
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.filterContainer && this.filterContainer.classList.contains('active')) {
                this.closeFilter();
                this.filterToggle.focus();
            }
        });
    },

    /**
     * Announce message to screen readers
     * 
     * @param {string} message The message to announce
     */
    announceToScreenReader(message) {
        if (this.a11yAnnounce) {
            this.a11yAnnounce.textContent = message;
        }
    },

    /**
     * Bind events
     */
    bindEvents() {
        // Toggle filter on mobile
        if (this.filterToggle) {
            this.filterToggle.addEventListener('click', () => this.toggleFilter());
        }

        // Close filter on mobile
        if (this.filterClose) {
            this.filterClose.addEventListener('click', () => this.closeFilter());
        }

        // Apply filters button (for off-canvas layout)
        if (this.filterApply) {
            this.filterApply.addEventListener('click', (e) => {
                e.preventDefault();
                this.applyFilters();
            });
        }

        // Clear all filters button (for off-canvas layout)
        if (this.filterClear) {
            this.filterClear.addEventListener('click', (e) => {
                e.preventDefault();
                this.clearFilters();
            });
        }

        // Clear all filters link in active filters
        if (this.clearAllFilters) {
            this.clearAllFilters.addEventListener('click', (e) => {
                e.preventDefault();
                this.clearFilters();
            });
        }

        // Filter inputs change event (for sidebar and horizontal layouts)
        if (this.filterInputs.length) {
            this.filterInputs.forEach(input => {
                input.addEventListener('change', () => {
                    // Don't auto-apply for off-canvas layout
                    if (!document.querySelector('.filter-layout-offcanvas')) {
                        this.applyFilters();
                    }
                    
                    // Announce filter change to screen readers
                    const inputLabel = this.getInputLabel(input);
                    const isChecked = input.type === 'checkbox' ? input.checked : true;
                    
                    if (inputLabel) {
                        this.announceToScreenReader(
                            isChecked 
                                ? `Filter applied: ${inputLabel}` 
                                : `Filter removed: ${inputLabel}`
                        );
                    }
                });
            });
        }

        // Orderby select change
        if (this.orderby) {
            this.orderby.addEventListener('change', () => {
                // Get current filter state
                const filterData = this.getFilterData();
                
                // Add orderby value
                filterData.orderby = this.orderby.value;
                
                // Apply filters with orderby
                this.applyFilters(filterData);
                
                // Announce sort change to screen readers
                const selectedOption = this.orderby.options[this.orderby.selectedIndex];
                this.announceToScreenReader(`Products sorted by ${selectedOption.text}`);
            });
        }

        // Handle pagination clicks
        if (this.paginationContainer) {
            this.paginationContainer.addEventListener('click', (e) => {
                const pageLink = e.target.closest('a');
                
                if (pageLink && aqualuxeFilter.enableAjax) {
                    e.preventDefault();
                    
                    // Get page number from URL
                    const url = new URL(pageLink.href);
                    const page = url.searchParams.get('paged') || 1;
                    
                    // Get current filter state
                    const filterData = this.getFilterData();
                    
                    // Add page number
                    filterData.paged = page;
                    
                    // Apply filters with pagination
                    this.applyFilters(filterData);
                    
                    // Scroll to top of products
                    this.scrollToProducts();
                    
                    // Announce page change to screen readers
                    this.announceToScreenReader(`Navigated to page ${page}`);
                }
            });
        }

        // Close filter when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768 && 
                this.filterContainer && 
                this.filterContainer.classList.contains('active') && 
                !this.filterContainer.contains(e.target) && 
                !this.filterToggle.contains(e.target)) {
                this.closeFilter();
            }
        });

        // Handle browser back/forward buttons with AJAX
        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.filterData) {
                this.updateFiltersFromState(e.state.filterData);
                this.fetchFilteredProducts(e.state.filterData, false);
                
                // Announce navigation to screen readers
                this.announceToScreenReader('Navigated to previous filter state');
            } else if (e.state === null) {
                // Back to initial state
                this.clearFilters(false);
                this.fetchFilteredProducts({}, false);
                
                // Announce navigation to screen readers
                this.announceToScreenReader('Filters cleared');
            }
        });
    },

    /**
     * Get label text for an input element
     * 
     * @param {HTMLInputElement} input The input element
     * @returns {string|null} The label text or null if not found
     */
    getInputLabel(input) {
        // Check for associated label
        const id = input.id;
        if (id) {
            const label = document.querySelector(`label[for="${id}"]`);
            if (label) {
                return label.textContent.trim();
            }
        }
        
        // Check for parent label
        const parentLabel = input.closest('label');
        if (parentLabel) {
            // Return text content excluding any nested input text
            return parentLabel.textContent.trim();
        }
        
        // Check for aria-label
        if (input.getAttribute('aria-label')) {
            return input.getAttribute('aria-label');
        }
        
        // Check for name attribute as fallback
        if (input.name) {
            return input.name.replace(/[\[\]_-]/g, ' ').trim();
        }
        
        return null;
    },

    /**
     * Set up price slider
     */
    setupPriceSlider() {
        if (!this.priceSlider || typeof noUiSlider === 'undefined') {
            return;
        }

        const minPrice = parseFloat(this.priceSlider.dataset.min);
        const maxPrice = parseFloat(this.priceSlider.dataset.max);
        const currentMinPrice = parseFloat(this.priceSlider.dataset.currentMin);
        const currentMaxPrice = parseFloat(this.priceSlider.dataset.currentMax);
        
        const minPriceInput = document.querySelector('.price-slider-min input');
        const maxPriceInput = document.querySelector('.price-slider-max input');
        const minPriceDisplay = document.querySelector('.price-slider-min span');
        const maxPriceDisplay = document.querySelector('.price-slider-max span');

        // Create the slider
        noUiSlider.create(this.priceSlider, {
            start: [currentMinPrice, currentMaxPrice],
            connect: true,
            step: 1,
            range: {
                'min': minPrice,
                'max': maxPrice
            },
            format: {
                to: function (value) {
                    return Math.round(value);
                },
                from: function (value) {
                    return Math.round(value);
                }
            }
        });

        // Update the display and inputs when slider changes
        this.priceSlider.noUiSlider.on('update', (values, handle) => {
            const value = values[handle];
            
            if (handle === 0) {
                minPriceDisplay.textContent = value;
                minPriceInput.value = value;
                
                // Update ARIA attributes for accessibility
                const minHandle = this.priceSlider.querySelector('.noUi-handle-lower');
                if (minHandle) {
                    minHandle.setAttribute('aria-valuemin', minPrice);
                    minHandle.setAttribute('aria-valuemax', maxPrice);
                    minHandle.setAttribute('aria-valuenow', value);
                    minHandle.setAttribute('aria-valuetext', `${value} ${aqualuxeFilter.i18n.min}`);
                }
            } else {
                maxPriceDisplay.textContent = value;
                maxPriceInput.value = value;
                
                // Update ARIA attributes for accessibility
                const maxHandle = this.priceSlider.querySelector('.noUi-handle-upper');
                if (maxHandle) {
                    maxHandle.setAttribute('aria-valuemin', minPrice);
                    maxHandle.setAttribute('aria-valuemax', maxPrice);
                    maxHandle.setAttribute('aria-valuenow', value);
                    maxHandle.setAttribute('aria-valuetext', `${value} ${aqualuxeFilter.i18n.max}`);
                }
            }
        });

        // Apply filters when slider stops
        this.priceSlider.noUiSlider.on('change', (values) => {
            // Don't auto-apply for off-canvas layout
            if (!document.querySelector('.filter-layout-offcanvas')) {
                this.applyFilters();
            }
            
            // Announce price change to screen readers
            const currency = document.querySelector('.price-slider-min').textContent.replace(/[0-9.]/g, '').trim();
            this.announceToScreenReader(`Price range set to ${currency}${values[0]} - ${currency}${values[1]}`);
        });
    },

    /**
     * Toggle filter visibility on mobile
     */
    toggleFilter() {
        if (this.filterContainer) {
            this.filterContainer.classList.toggle('active');
            this.body.classList.toggle('filter-active');
            
            if (this.filterToggle) {
                const isExpanded = this.filterContainer.classList.contains('active');
                this.filterToggle.setAttribute('aria-expanded', isExpanded);
                
                // Announce state change to screen readers
                this.announceToScreenReader(isExpanded ? 'Filter panel opened' : 'Filter panel closed');
                
                // Focus first focusable element in filter container when opened
                if (isExpanded) {
                    setTimeout(() => {
                        const firstFocusable = this.filterContainer.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                        if (firstFocusable) {
                            firstFocusable.focus();
                        }
                    }, 100);
                }
            }
        }
    },

    /**
     * Close filter on mobile
     */
    closeFilter() {
        if (this.filterContainer) {
            this.filterContainer.classList.remove('active');
            this.body.classList.remove('filter-active');
            
            if (this.filterToggle) {
                this.filterToggle.setAttribute('aria-expanded', 'false');
                
                // Announce state change to screen readers
                this.announceToScreenReader('Filter panel closed');
            }
        }
    },

    /**
     * Apply filters
     * 
     * @param {Object} filterData Optional filter data to use instead of form data
     */
    applyFilters(filterData = null) {
        // Close filter on mobile
        this.closeFilter();
        
        // Get filter data from form if not provided
        if (!filterData) {
            filterData = this.getFilterData();
        }
        
        // Check if AJAX filtering is enabled
        if (aqualuxeFilter.enableAjax) {
            // Update URL
            this.updateUrl(filterData);
            
            // Fetch filtered products
            this.fetchFilteredProducts(filterData);
        } else {
            // Submit form normally
            this.submitFilterForm(filterData);
        }
    },

    /**
     * Get filter data from form
     * 
     * @returns {Object} Filter data
     */
    getFilterData() {
        const filterData = {};
        
        // Get all form inputs
        const formData = new FormData(this.filterForm);
        
        // Convert FormData to object
        for (const [key, value] of formData.entries()) {
            // Handle array values (checkboxes with same name)
            if (key.endsWith('[]')) {
                const cleanKey = key.slice(0, -2);
                if (!filterData[cleanKey]) {
                    filterData[cleanKey] = [];
                }
                filterData[cleanKey].push(value);
            } else {
                filterData[key] = value;
            }
        }
        
        return filterData;
    },

    /**
     * Update URL with filter parameters
     * 
     * @param {Object} filterData Filter data
     */
    updateUrl(filterData) {
        // Get current URL
        const url = new URL(window.location.href);
        
        // Remove existing parameters
        url.search = '';
        
        // Add filter parameters
        for (const [key, value] of Object.entries(filterData)) {
            if (Array.isArray(value)) {
                value.forEach(val => {
                    url.searchParams.append(`${key}[]`, val);
                });
            } else {
                url.searchParams.append(key, value);
            }
        }
        
        // Update browser history
        window.history.pushState({ filterData }, '', url.toString());
    },

    /**
     * Fetch filtered products via AJAX
     * 
     * @param {Object} filterData Filter data
     * @param {boolean} showLoader Whether to show the loading indicator
     */
    fetchFilteredProducts(filterData, showLoader = true) {
        // Show loading state
        if (showLoader) {
            this.showLoader();
        }
        
        // Announce loading state to screen readers
        this.announceToScreenReader(aqualuxeFilter.i18n.loading);
        
        // Prepare AJAX data
        const ajaxData = {
            action: 'aqualuxe_filter_products',
            nonce: aqualuxeFilter.nonce,
            filter_data: filterData
        };
        
        // Send AJAX request
        fetch(aqualuxeFilter.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(this.objectToFormData(ajaxData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update products
                if (this.productsContainer) {
                    this.productsContainer.innerHTML = data.data.products;
                }
                
                // Update pagination
                if (this.paginationContainer) {
                    this.paginationContainer.innerHTML = data.data.pagination;
                }
                
                // Update product count
                this.updateProductCount(data.data.count);
                
                // Hide loader
                this.hideLoader();
                
                // Announce completion to screen readers
                const productCount = data.data.count;
                this.announceToScreenReader(
                    productCount > 0 
                        ? `${productCount} products found` 
                        : aqualuxeFilter.i18n.noProducts
                );
                
                // Trigger event for other scripts
                document.dispatchEvent(new CustomEvent('aqualuxe:products-filtered', {
                    detail: {
                        count: data.data.count,
                        maxPages: data.data.max_num_pages,
                        filterData: filterData
                    }
                }));
                
                // Set up accessibility for active filters
                setTimeout(() => {
                    this.setupActiveFiltersA11y();
                }, 100);
            } else {
                console.error('Error filtering products:', data.data.message);
                this.hideLoader();
                
                // Announce error to screen readers
                this.announceToScreenReader('Error loading products. Please try again.');
            }
        })
        .catch(error => {
            console.error('AJAX error:', error);
            this.hideLoader();
            
            // Announce error to screen readers
            this.announceToScreenReader('Error loading products. Please try again.');
        });
    },

    /**
     * Convert object to FormData compatible format
     * 
     * @param {Object} obj Object to convert
     * @param {string} namespace Optional namespace for nested objects
     * @returns {Object} FormData compatible object
     */
    objectToFormData(obj, namespace = '') {
        const formData = {};
        
        for (const key in obj) {
            if (obj.hasOwnProperty(key)) {
                const formKey = namespace ? `${namespace}[${key}]` : key;
                
                if (obj[key] !== null && typeof obj[key] === 'object' && !(obj[key] instanceof File)) {
                    Object.assign(formData, this.objectToFormData(obj[key], formKey));
                } else {
                    formData[formKey] = obj[key];
                }
            }
        }
        
        return formData;
    },

    /**
     * Submit filter form normally (non-AJAX)
     * 
     * @param {Object} filterData Filter data
     */
    submitFilterForm(filterData) {
        // Create a temporary form
        const form = document.createElement('form');
        form.method = 'get';
        form.action = window.location.pathname;
        
        // Add filter data as hidden inputs
        for (const [key, value] of Object.entries(filterData)) {
            if (Array.isArray(value)) {
                value.forEach(val => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `${key}[]`;
                    input.value = val;
                    form.appendChild(input);
                });
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
        }
        
        // Append form to body and submit
        document.body.appendChild(form);
        form.submit();
    },

    /**
     * Clear all filters
     * 
     * @param {boolean} applyAfterClear Whether to apply filters after clearing
     */
    clearFilters(applyAfterClear = true) {
        // Reset checkboxes
        const checkboxes = document.querySelectorAll('.product-filters input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Reset price slider
        if (this.priceSlider && this.priceSlider.noUiSlider) {
            const minPrice = parseFloat(this.priceSlider.dataset.min);
            const maxPrice = parseFloat(this.priceSlider.dataset.max);
            this.priceSlider.noUiSlider.set([minPrice, maxPrice]);
        }
        
        // Announce to screen readers
        this.announceToScreenReader('All filters cleared');
        
        // Apply filters if needed
        if (applyAfterClear) {
            this.applyFilters({});
        }
    },

    /**
     * Update filters from state (for browser back/forward)
     * 
     * @param {Object} filterData Filter data
     */
    updateFiltersFromState(filterData) {
        // Reset all filters first
        this.clearFilters(false);
        
        // Update checkboxes
        for (const [key, value] of Object.entries(filterData)) {
            if (Array.isArray(value)) {
                value.forEach(val => {
                    const checkbox = document.querySelector(`.product-filters input[name="${key}[]"][value="${val}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }
        }
        
        // Update price slider
        if (this.priceSlider && this.priceSlider.noUiSlider) {
            const minPrice = filterData.min_price || parseFloat(this.priceSlider.dataset.min);
            const maxPrice = filterData.max_price || parseFloat(this.priceSlider.dataset.max);
            this.priceSlider.noUiSlider.set([minPrice, maxPrice]);
        }
    },

    /**
     * Show loading indicator
     */
    showLoader() {
        // Remove existing loader
        this.hideLoader();
        
        // Create loader
        const loader = document.createElement('div');
        loader.className = 'products-loader';
        loader.setAttribute('aria-live', 'polite');
        loader.innerHTML = `
            <div class="loader-spinner" aria-hidden="true"></div>
            <div class="loader-text">${aqualuxeFilter.i18n.loading}</div>
        `;
        
        // Add loader before products
        if (this.productsContainer) {
            this.productsContainer.parentNode.insertBefore(loader, this.productsContainer);
            this.productsContainer.style.opacity = '0.5';
            this.productsContainer.setAttribute('aria-busy', 'true');
        }
    },

    /**
     * Hide loading indicator
     */
    hideLoader() {
        const loader = document.querySelector('.products-loader');
        if (loader) {
            loader.remove();
        }
        
        if (this.productsContainer) {
            this.productsContainer.style.opacity = '1';
            this.productsContainer.setAttribute('aria-busy', 'false');
        }
    },

    /**
     * Update product count
     * 
     * @param {number} count Product count
     */
    updateProductCount(count) {
        const countElement = document.querySelector('.woocommerce-result-count');
        if (countElement && count !== undefined) {
            // Get the current text
            const text = countElement.textContent;
            
            // Replace the count number
            const newText = text.replace(/\d+(?=\s+products?)/g, count);
            
            // Update the text
            countElement.textContent = newText;
        }
    },

    /**
     * Scroll to products container
     */
    scrollToProducts() {
        if (this.productsContainer) {
            // Get the top position of the products container
            const rect = this.productsContainer.getBoundingClientRect();
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const top = rect.top + scrollTop;
            
            // Scroll to products with offset for header
            window.scrollTo({
                top: top - 100, // Adjust offset as needed
                behavior: 'smooth'
            });
            
            // Set focus to the first product for keyboard users
            setTimeout(() => {
                const firstProduct = this.productsContainer.querySelector('li.product a');
                if (firstProduct) {
                    firstProduct.focus();
                }
            }, 500);
        }
    }
};

export default ProductFilter;