/**
 * WooCommerce JavaScript for AquaLuxe Theme
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

/**
 * AquaLuxe WooCommerce Integration
 */
class AquaLuxeWooCommerce {
    constructor() {
        this.init();
    }

    /**
     * Initialize WooCommerce functionality
     */
    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.onReady());
        } else {
            this.onReady();
        }
    }

    /**
     * Execute when DOM is ready
     */
    onReady() {
        this.initProductGallery();
        this.initQuickView();
        this.initWishlist();
        this.initCartFunctionality();
        this.initCheckoutEnhancements();
        this.initProductFilters();
        this.initProductSearch();
        this.initProductCompare();
        this.initVariationHandling();
        this.initShippingCalculator();
        
        console.log('AquaLuxe WooCommerce initialized successfully');
    }

    /**
     * Initialize product gallery enhancements
     */
    initProductGallery() {
        // Zoom functionality
        const galleryImages = document.querySelectorAll('.woocommerce-product-gallery__image img');
        
        galleryImages.forEach(image => {
            image.addEventListener('mouseenter', () => {
                image.style.transform = 'scale(1.2)';
            });
            
            image.addEventListener('mouseleave', () => {
                image.style.transform = 'scale(1)';
            });
        });

        // Thumbnail navigation
        const thumbs = document.querySelectorAll('.flex-control-thumbs img');
        const mainImage = document.querySelector('.woocommerce-product-gallery__image img');
        
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active class from all thumbs
                thumbs.forEach(t => t.classList.remove('flex-active'));
                
                // Add active class to clicked thumb
                thumb.classList.add('flex-active');
                
                // Update main image
                if (mainImage) {
                    mainImage.src = thumb.dataset.large || thumb.src;
                    mainImage.srcset = thumb.dataset.srcset || '';
                }
            });
        });
    }

    /**
     * Initialize quick view functionality
     */
    initQuickView() {
        const quickViewButtons = document.querySelectorAll('[data-quick-view]');
        
        quickViewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const productId = button.dataset.productId;
                this.openQuickView(productId);
            });
        });
    }

    /**
     * Open quick view modal
     */
    openQuickView(productId) {
        // Show loading state
        this.showNotification('Loading product details...', 'info');
        
        // Make AJAX request to get product details
        if (typeof aqualuxe_ajax !== 'undefined') {
            fetch(aqualuxe_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    nonce: aqualuxe_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.displayQuickView(data.data);
                } else {
                    this.showNotification('Failed to load product details', 'error');
                }
            })
            .catch(error => {
                console.error('Quick view error:', error);
                this.showNotification('An error occurred', 'error');
            });
        }
    }

    /**
     * Display quick view modal
     */
    displayQuickView(productData) {
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'quick-view-modal fixed inset-0 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="modal-overlay absolute inset-0 bg-black/50" data-close-modal></div>
            <div class="modal-content relative bg-white dark:bg-neutral-900 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <button class="absolute top-4 right-4 z-10 text-neutral-500 hover:text-neutral-700" data-close-modal>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="p-6">
                    ${productData.html}
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        document.body.classList.add('modal-open');
        
        // Add close functionality
        const closeButtons = modal.querySelectorAll('[data-close-modal]');
        closeButtons.forEach(button => {
            button.addEventListener('click', () => this.closeQuickView(modal));
        });
        
        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeQuickView(modal);
            }
        });
    }

    /**
     * Close quick view modal
     */
    closeQuickView(modal) {
        modal.remove();
        document.body.classList.remove('modal-open');
    }

    /**
     * Initialize wishlist functionality
     */
    initWishlist() {
        const wishlistButtons = document.querySelectorAll('[data-wishlist]');
        
        wishlistButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const productId = button.dataset.productId;
                const isInWishlist = button.classList.contains('in-wishlist');
                
                this.toggleWishlist(productId, !isInWishlist, button);
            });
        });
    }

    /**
     * Toggle wishlist item
     */
    toggleWishlist(productId, add, button) {
        if (typeof aqualuxe_ajax !== 'undefined') {
            fetch(aqualuxe_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_toggle_wishlist',
                    product_id: productId,
                    add: add ? '1' : '0',
                    nonce: aqualuxe_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.toggle('in-wishlist', add);
                    button.setAttribute('aria-label', add ? 'Remove from wishlist' : 'Add to wishlist');
                    
                    const message = add ? 'Added to wishlist' : 'Removed from wishlist';
                    this.showNotification(message, 'success');
                    
                    // Update wishlist count
                    this.updateWishlistCount(data.data.count);
                } else {
                    this.showNotification(data.data.message || 'Failed to update wishlist', 'error');
                }
            })
            .catch(error => {
                console.error('Wishlist error:', error);
                this.showNotification('An error occurred', 'error');
            });
        }
    }

    /**
     * Update wishlist count in header
     */
    updateWishlistCount(count) {
        const countElement = document.querySelector('.wishlist-count');
        if (countElement) {
            countElement.textContent = count;
            countElement.style.display = count > 0 ? 'block' : 'none';
        }
    }

    /**
     * Initialize enhanced cart functionality
     */
    initCartFunctionality() {
        // Add to cart with AJAX
        const addToCartButtons = document.querySelectorAll('.add_to_cart_button');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                if (button.classList.contains('product_type_simple')) {
                    e.preventDefault();
                    this.addToCartAjax(button);
                }
            });
        });

        // Update cart quantities
        const quantityInputs = document.querySelectorAll('.cart .qty');
        
        quantityInputs.forEach(input => {
            input.addEventListener('change', () => {
                this.updateCartQuantity(input);
            });
        });

        // Remove cart items
        const removeButtons = document.querySelectorAll('.cart .remove');
        
        removeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.removeCartItem(button);
            });
        });
    }

    /**
     * Add product to cart via AJAX
     */
    addToCartAjax(button) {
        const productId = button.dataset.productId;
        const quantity = button.dataset.quantity || 1;
        
        // Show loading state
        const originalText = button.textContent;
        button.textContent = 'Adding...';
        button.disabled = true;
        button.classList.add('loading');
        
        if (typeof aqualuxe_ajax !== 'undefined') {
            fetch(aqualuxe_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    nonce: aqualuxe_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = 'Added!';
                    button.classList.add('added');
                    
                    // Update cart count
                    this.updateCartCount(data.data.cart_count);
                    
                    // Show success message
                    this.showNotification('Product added to cart!', 'success');
                    
                    // Reset button after delay
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                        button.classList.remove('loading', 'added');
                    }, 2000);
                } else {
                    button.textContent = originalText;
                    button.disabled = false;
                    button.classList.remove('loading');
                    
                    this.showNotification(data.data.message || 'Failed to add product to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Add to cart error:', error);
                
                button.textContent = originalText;
                button.disabled = false;
                button.classList.remove('loading');
                
                this.showNotification('An error occurred', 'error');
            });
        }
    }

    /**
     * Update cart count in header
     */
    updateCartCount(count) {
        const countElements = document.querySelectorAll('.cart-count');
        countElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'flex' : 'none';
        });
    }

    /**
     * Initialize checkout enhancements
     */
    initCheckoutEnhancements() {
        // Real-time form validation
        const checkoutForm = document.querySelector('.woocommerce-checkout form');
        
        if (checkoutForm) {
            const inputs = checkoutForm.querySelectorAll('input, select, textarea');
            
            inputs.forEach(input => {
                input.addEventListener('blur', () => this.validateCheckoutField(input));
                input.addEventListener('input', () => this.clearCheckoutFieldError(input));
            });
        }

        // Payment method selection
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', () => {
                this.updatePaymentMethod(method);
            });
        });
    }

    /**
     * Validate checkout field
     */
    validateCheckoutField(field) {
        const value = field.value.trim();
        const required = field.hasAttribute('required');
        let isValid = true;
        let message = '';

        if (required && !value) {
            isValid = false;
            message = 'This field is required.';
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                message = 'Please enter a valid email address.';
            }
        }

        this.showCheckoutFieldError(field, isValid ? '' : message);
        return isValid;
    }

    /**
     * Show checkout field error
     */
    showCheckoutFieldError(field, message) {
        this.clearCheckoutFieldError(field);

        if (message) {
            field.classList.add('error');
            
            const errorElement = document.createElement('div');
            errorElement.className = 'checkout-field-error text-red-600 text-sm mt-1';
            errorElement.textContent = message;
            
            field.parentNode.appendChild(errorElement);
        }
    }

    /**
     * Clear checkout field error
     */
    clearCheckoutFieldError(field) {
        field.classList.remove('error');
        
        const errorElement = field.parentNode.querySelector('.checkout-field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Initialize product filters
     */
    initProductFilters() {
        const filterForm = document.querySelector('.woocommerce-widget-layered-nav form');
        
        if (filterForm) {
            const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    // Auto-submit filter form
                    setTimeout(() => filterForm.submit(), 100);
                });
            });
        }

        // Price range filter
        const priceSlider = document.querySelector('.price-slider');
        
        if (priceSlider) {
            this.initPriceSlider(priceSlider);
        }
    }

    /**
     * Initialize price slider
     */
    initPriceSlider(slider) {
        const minInput = slider.querySelector('.price-min');
        const maxInput = slider.querySelector('.price-max');
        const minValue = parseInt(minInput.min);
        const maxValue = parseInt(maxInput.max);
        
        // Update slider on input change
        [minInput, maxInput].forEach(input => {
            input.addEventListener('input', () => {
                const min = parseInt(minInput.value);
                const max = parseInt(maxInput.value);
                
                if (min > max) {
                    if (input === minInput) {
                        maxInput.value = min;
                    } else {
                        minInput.value = max;
                    }
                }
                
                this.updatePriceSliderVisual(slider, min, max, minValue, maxValue);
            });
        });
    }

    /**
     * Update price slider visual
     */
    updatePriceSliderVisual(slider, min, max, minValue, maxValue) {
        const range = slider.querySelector('.price-range');
        
        if (range) {
            const minPercent = ((min - minValue) / (maxValue - minValue)) * 100;
            const maxPercent = ((max - minValue) / (maxValue - minValue)) * 100;
            
            range.style.left = minPercent + '%';
            range.style.width = (maxPercent - minPercent) + '%';
        }
    }

    /**
     * Initialize product search enhancements
     */
    initProductSearch() {
        const searchInput = document.querySelector('.woocommerce-product-search input');
        
        if (searchInput) {
            let searchTimeout;
            
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                
                if (query.length >= 3) {
                    searchTimeout = setTimeout(() => {
                        this.performProductSearch(query);
                    }, 300);
                }
            });
        }
    }

    /**
     * Perform product search
     */
    performProductSearch(query) {
        if (typeof aqualuxe_ajax !== 'undefined') {
            fetch(aqualuxe_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_product_search',
                    query: query,
                    nonce: aqualuxe_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.displayProductSearchResults(data.data);
                }
            })
            .catch(error => {
                console.error('Product search error:', error);
            });
        }
    }

    /**
     * Initialize product comparison
     */
    initProductCompare() {
        const compareButtons = document.querySelectorAll('[data-compare]');
        
        compareButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const productId = button.dataset.productId;
                this.toggleCompare(productId, button);
            });
        });
    }

    /**
     * Toggle product comparison
     */
    toggleCompare(productId, button) {
        const isInCompare = button.classList.contains('in-compare');
        
        if (typeof aqualuxe_ajax !== 'undefined') {
            fetch(aqualuxe_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_toggle_compare',
                    product_id: productId,
                    add: !isInCompare ? '1' : '0',
                    nonce: aqualuxe_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.toggle('in-compare');
                    
                    const message = !isInCompare ? 'Added to comparison' : 'Removed from comparison';
                    this.showNotification(message, 'success');
                    
                    this.updateCompareCount(data.data.count);
                }
            })
            .catch(error => {
                console.error('Compare error:', error);
            });
        }
    }

    /**
     * Update compare count
     */
    updateCompareCount(count) {
        const countElement = document.querySelector('.compare-count');
        if (countElement) {
            countElement.textContent = count;
            countElement.style.display = count > 0 ? 'block' : 'none';
        }
    }

    /**
     * Initialize variation handling
     */
    initVariationHandling() {
        const variationForms = document.querySelectorAll('.variations_form');
        
        variationForms.forEach(form => {
            const selects = form.querySelectorAll('select');
            
            selects.forEach(select => {
                select.addEventListener('change', () => {
                    this.updateVariationPrice(form);
                });
            });
        });
    }

    /**
     * Update variation price
     */
    updateVariationPrice(form) {
        const priceElement = form.querySelector('.price');
        const addToCartButton = form.querySelector('.single_add_to_cart_button');
        
        // Get selected variation
        const formData = new FormData(form);
        const productId = formData.get('product_id');
        
        if (typeof aqualuxe_ajax !== 'undefined') {
            fetch(aqualuxe_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_get_variation',
                    product_id: productId,
                    variation_data: JSON.stringify(Object.fromEntries(formData)),
                    nonce: aqualuxe_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.variation) {
                    const variation = data.data.variation;
                    
                    // Update price
                    if (priceElement && variation.price_html) {
                        priceElement.innerHTML = variation.price_html;
                    }
                    
                    // Update add to cart button
                    if (addToCartButton) {
                        addToCartButton.disabled = !variation.is_purchasable;
                        addToCartButton.textContent = variation.is_purchasable ? 'Add to cart' : 'Out of stock';
                    }
                }
            })
            .catch(error => {
                console.error('Variation error:', error);
            });
        }
    }

    /**
     * Initialize shipping calculator
     */
    initShippingCalculator() {
        const calculatorForm = document.querySelector('.shipping-calculator-form');
        
        if (calculatorForm) {
            calculatorForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.calculateShipping(calculatorForm);
            });
        }
    }

    /**
     * Calculate shipping costs
     */
    calculateShipping(form) {
        const formData = new FormData(form);
        
        if (typeof aqualuxe_ajax !== 'undefined') {
            fetch(aqualuxe_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_calculate_shipping',
                    country: formData.get('calc_shipping_country'),
                    state: formData.get('calc_shipping_state'),
                    postcode: formData.get('calc_shipping_postcode'),
                    nonce: aqualuxe_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.displayShippingResults(data.data);
                } else {
                    this.showNotification('Failed to calculate shipping', 'error');
                }
            })
            .catch(error => {
                console.error('Shipping calculation error:', error);
                this.showNotification('An error occurred', 'error');
            });
        }
    }

    /**
     * Display shipping calculation results
     */
    displayShippingResults(results) {
        const resultsContainer = document.querySelector('.shipping-calculator-results');
        
        if (resultsContainer && results.methods) {
            let html = '<h4>Available Shipping Methods:</h4><ul>';
            
            results.methods.forEach(method => {
                html += `<li>${method.label}: ${method.cost}</li>`;
            });
            
            html += '</ul>';
            resultsContainer.innerHTML = html;
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `wc-notification wc-notification-${type} fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => notification.classList.add('notification-visible'), 10);

        // Remove after delay
        setTimeout(() => {
            notification.classList.remove('notification-visible');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Initialize WooCommerce functionality
new AquaLuxeWooCommerce();