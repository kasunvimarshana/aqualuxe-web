/**
 * AquaLuxe Theme WooCommerce JavaScript
 * 
 * Contains all the WooCommerce specific JavaScript functionality
 */

(function() {
    'use strict';

    /**
     * Initialize all WooCommerce scripts
     */
    function init() {
        setupQuickView();
        setupAjaxCart();
        setupWishlist();
        setupProductFilters();
        setupQuantityButtons();
        setupProductGallery();
        setupVariationSwatches();
    }

    /**
     * Quick View functionality
     */
    function setupQuickView() {
        const quickViewButtons = document.querySelectorAll('.quick-view-button');
        if (!quickViewButtons.length) return;

        // Create modal container if it doesn't exist
        let quickViewModal = document.getElementById('quick-view-modal');
        if (!quickViewModal) {
            quickViewModal = document.createElement('div');
            quickViewModal.id = 'quick-view-modal';
            quickViewModal.className = 'quick-view-modal';
            quickViewModal.innerHTML = `
                <div class="quick-view-container">
                    <div class="quick-view-content"></div>
                    <button class="quick-view-close" aria-label="Close quick view">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(quickViewModal);

            // Close modal when clicking close button or outside the modal
            const closeButton = quickViewModal.querySelector('.quick-view-close');
            closeButton.addEventListener('click', () => {
                quickViewModal.classList.remove('open');
                document.body.classList.remove('modal-open');
            });

            quickViewModal.addEventListener('click', (e) => {
                if (e.target === quickViewModal) {
                    quickViewModal.classList.remove('open');
                    document.body.classList.remove('modal-open');
                }
            });

            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && quickViewModal.classList.contains('open')) {
                    quickViewModal.classList.remove('open');
                    document.body.classList.remove('modal-open');
                }
            });
        }

        // Add click event to quick view buttons
        quickViewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');
                
                // Show loading state
                const contentContainer = quickViewModal.querySelector('.quick-view-content');
                contentContainer.innerHTML = '<div class="loading-spinner"></div>';
                quickViewModal.classList.add('open');
                document.body.classList.add('modal-open');
                
                // AJAX request to get product data
                // This is a placeholder - you would need to implement the actual AJAX call
                // using the WordPress AJAX API
                
                // Simulate AJAX response for demo purposes
                setTimeout(() => {
                    // This would be replaced with actual product data from the server
                    contentContainer.innerHTML = `
                        <div class="product-quick-view">
                            <div class="product-image">
                                <img src="https://via.placeholder.com/400x400" alt="Product Image">
                            </div>
                            <div class="product-details">
                                <h2 class="product-title">Product #${productId}</h2>
                                <div class="product-price">$99.99</div>
                                <div class="product-description">
                                    <p>This is a placeholder for the product description. In a real implementation, this would show the actual product details fetched from the server.</p>
                                </div>
                                <form class="cart">
                                    <div class="quantity">
                                        <label for="quantity-${productId}">Quantity</label>
                                        <input type="number" id="quantity-${productId}" class="input-text qty text" step="1" min="1" max="10" value="1" title="Qty" size="4">
                                    </div>
                                    <button type="submit" class="single_add_to_cart_button button alt">Add to cart</button>
                                </form>
                                <div class="product-meta">
                                    <span class="sku">SKU: PROD-${productId}</span>
                                    <span class="category">Category: Sample</span>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Initialize quantity buttons in the modal
                    setupQuantityButtons(contentContainer);
                }, 1000);
            });
        });
    }

    /**
     * AJAX Cart functionality
     */
    function setupAjaxCart() {
        const addToCartButtons = document.querySelectorAll('.add_to_cart_button');
        if (!addToCartButtons.length) return;

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!this.classList.contains('product_type_simple')) return;
                
                e.preventDefault();
                const productId = this.getAttribute('data-product_id');
                const quantity = 1; // Default quantity
                
                // Add loading state
                this.classList.add('loading');
                
                // AJAX request to add to cart
                // This is a placeholder - you would need to implement the actual AJAX call
                
                // Simulate AJAX response for demo purposes
                setTimeout(() => {
                    this.classList.remove('loading');
                    this.classList.add('added');
                    
                    // Update cart count in header
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        const currentCount = parseInt(cartCount.textContent);
                        cartCount.textContent = currentCount + quantity;
                    }
                    
                    // Show added to cart message
                    showNotification('Product added to cart successfully!', 'success');
                }, 1000);
            });
        });
        
        // Mini cart functionality
        const cartLink = document.querySelector('.cart-link');
        const miniCart = document.querySelector('.mini-cart');
        
        if (cartLink && miniCart) {
            cartLink.addEventListener('click', function(e) {
                e.preventDefault();
                miniCart.classList.toggle('open');
            });
            
            // Close mini cart when clicking outside
            document.addEventListener('click', function(e) {
                if (miniCart.classList.contains('open') && 
                    !miniCart.contains(e.target) && 
                    !cartLink.contains(e.target)) {
                    miniCart.classList.remove('open');
                }
            });
        }
    }

    /**
     * Wishlist functionality
     */
    function setupWishlist() {
        const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
        if (!wishlistButtons.length) return;

        wishlistButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');
                
                // Toggle wishlist state
                this.classList.toggle('added');
                
                if (this.classList.contains('added')) {
                    // AJAX request to add to wishlist
                    // This is a placeholder - you would need to implement the actual AJAX call
                    
                    showNotification('Product added to wishlist!', 'success');
                } else {
                    // AJAX request to remove from wishlist
                    // This is a placeholder - you would need to implement the actual AJAX call
                    
                    showNotification('Product removed from wishlist!', 'info');
                }
            });
        });
    }

    /**
     * Product Filters functionality
     */
    function setupProductFilters() {
        const filterForm = document.querySelector('.product-filters-form');
        if (!filterForm) return;

        const filterInputs = filterForm.querySelectorAll('input, select');
        const productsContainer = document.querySelector('.products');
        
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Show loading state
                if (productsContainer) {
                    productsContainer.classList.add('loading');
                }
                
                // Get all form data
                const formData = new FormData(filterForm);
                
                // AJAX request to filter products
                // This is a placeholder - you would need to implement the actual AJAX call
                
                // Simulate AJAX response for demo purposes
                setTimeout(() => {
                    if (productsContainer) {
                        productsContainer.classList.remove('loading');
                    }
                    
                    // Update URL with filter parameters
                    const params = new URLSearchParams(formData);
                    const newUrl = `${window.location.pathname}?${params.toString()}`;
                    window.history.pushState({}, '', newUrl);
                    
                    showNotification('Products filtered!', 'info');
                }, 1000);
            });
        });
        
        // Reset filters
        const resetButton = filterForm.querySelector('.reset-filters');
        if (resetButton) {
            resetButton.addEventListener('click', function(e) {
                e.preventDefault();
                filterForm.reset();
                
                // Trigger change event on first input to apply reset
                if (filterInputs.length) {
                    filterInputs[0].dispatchEvent(new Event('change'));
                }
            });
        }
    }

    /**
     * Quantity Buttons functionality
     */
    function setupQuantityButtons(container = document) {
        const quantityInputs = container.querySelectorAll('.quantity input[type="number"]');
        if (!quantityInputs.length) return;

        quantityInputs.forEach(input => {
            // Check if buttons are already added
            if (input.parentNode.querySelector('.quantity-btn')) return;
            
            const minusBtn = document.createElement('button');
            minusBtn.className = 'quantity-btn minus';
            minusBtn.type = 'button';
            minusBtn.textContent = '-';
            
            const plusBtn = document.createElement('button');
            plusBtn.className = 'quantity-btn plus';
            plusBtn.type = 'button';
            plusBtn.textContent = '+';
            
            input.insertAdjacentElement('beforebegin', minusBtn);
            input.insertAdjacentElement('afterend', plusBtn);
            
            minusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value);
                const minValue = parseInt(input.getAttribute('min')) || 1;
                if (currentValue > minValue) {
                    input.value = currentValue - 1;
                    triggerEvent(input, 'change');
                }
            });
            
            plusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.getAttribute('max')) || 999;
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    triggerEvent(input, 'change');
                }
            });
        });
    }

    /**
     * Product Gallery functionality
     */
    function setupProductGallery() {
        const productGallery = document.querySelector('.woocommerce-product-gallery');
        if (!productGallery) return;

        const mainImage = productGallery.querySelector('.woocommerce-product-gallery__image');
        const thumbnails = productGallery.querySelectorAll('.woocommerce-product-gallery__image a');
        
        if (!mainImage || !thumbnails.length) return;
        
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function(e) {
                e.preventDefault();
                const fullSizeUrl = this.getAttribute('href');
                const imgSrc = this.querySelector('img').getAttribute('src');
                const imgAlt = this.querySelector('img').getAttribute('alt');
                
                // Update main image
                const mainImageLink = mainImage.querySelector('a');
                const mainImageImg = mainImage.querySelector('img');
                
                if (mainImageLink) mainImageLink.setAttribute('href', fullSizeUrl);
                if (mainImageImg) {
                    mainImageImg.setAttribute('src', imgSrc);
                    mainImageImg.setAttribute('alt', imgAlt);
                    
                    // Trigger image zoom if available
                    if (typeof jQuery !== 'undefined' && jQuery.fn.zoom) {
                        jQuery(mainImage).trigger('zoom.destroy');
                        jQuery(mainImage).zoom({
                            url: fullSizeUrl
                        });
                    }
                }
                
                // Update active thumbnail
                thumbnails.forEach(t => t.parentNode.classList.remove('active'));
                this.parentNode.classList.add('active');
            });
        });
        
        // Initialize zoom if jQuery is available
        if (typeof jQuery !== 'undefined' && jQuery.fn.zoom) {
            jQuery(mainImage).zoom({
                url: mainImage.querySelector('a').getAttribute('href')
            });
        }
    }

    /**
     * Variation Swatches functionality
     */
    function setupVariationSwatches() {
        const variationForms = document.querySelectorAll('.variations_form');
        if (!variationForms.length) return;

        variationForms.forEach(form => {
            const swatchContainers = form.querySelectorAll('.swatch-control');
            
            swatchContainers.forEach(container => {
                const swatches = container.querySelectorAll('.swatch');
                const selectField = container.querySelector('select');
                
                if (!swatches.length || !selectField) return;
                
                swatches.forEach(swatch => {
                    swatch.addEventListener('click', function() {
                        const value = this.getAttribute('data-value');
                        
                        // Update select field
                        selectField.value = value;
                        triggerEvent(selectField, 'change');
                        
                        // Update active swatch
                        swatches.forEach(s => s.classList.remove('selected'));
                        this.classList.add('selected');
                    });
                });
                
                // Hide the original select field
                selectField.style.display = 'none';
            });
        });
    }

    /**
     * Show notification message
     */
    function showNotification(message, type = 'info') {
        // Create notification element if it doesn't exist
        let notificationContainer = document.querySelector('.woocommerce-notifications');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'woocommerce-notifications';
            document.body.appendChild(notificationContainer);
        }
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `woocommerce-notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">${message}</div>
            <button class="notification-close" aria-label="Close notification">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        `;
        
        // Add to container
        notificationContainer.appendChild(notification);
        
        // Add close functionality
        const closeButton = notification.querySelector('.notification-close');
        closeButton.addEventListener('click', () => {
            notification.classList.add('closing');
            setTimeout(() => {
                notificationContainer.removeChild(notification);
            }, 300);
        });
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.add('closing');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notificationContainer.removeChild(notification);
                    }
                }, 300);
            }
        }, 5000);
    }

    /**
     * Helper function to trigger events on elements
     */
    function triggerEvent(element, eventName) {
        const event = new Event(eventName, { bubbles: true });
        element.dispatchEvent(event);
    }

    // Initialize when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();