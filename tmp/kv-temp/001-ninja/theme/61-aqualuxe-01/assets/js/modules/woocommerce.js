/**
 * AquaLuxe Theme - WooCommerce Module
 * 
 * Handles WooCommerce specific functionality including:
 * - Product image gallery
 * - AJAX cart updates
 * - Quantity input controls
 * - Product filters
 */

const WooCommerce = (function() {
    'use strict';
    
    /**
     * Initialize WooCommerce functionality
     */
    function init() {
        // Initialize all WooCommerce features
        productGallery();
        quantityControls();
        ajaxCart();
        productFilters();
        quickView();
    }
    
    /**
     * Product gallery slider and zoom functionality
     */
    function productGallery() {
        const galleryContainer = document.querySelector('.woocommerce-product-gallery');
        if (!galleryContainer) return;
        
        // Main product image
        const mainImage = galleryContainer.querySelector('.woocommerce-product-gallery__image');
        
        // Thumbnail images
        const thumbnails = galleryContainer.querySelectorAll('.woocommerce-product-gallery__thumb');
        
        // Add click event to thumbnails
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get full size image URL and alt text
                const fullSizeUrl = this.getAttribute('data-full-size');
                const imageAlt = this.querySelector('img').getAttribute('alt') || '';
                
                // Update main image
                if (mainImage && fullSizeUrl) {
                    const mainImg = mainImage.querySelector('img');
                    if (mainImg) {
                        mainImg.setAttribute('src', fullSizeUrl);
                        mainImg.setAttribute('alt', imageAlt);
                        
                        // Update zoom image if available
                        const zoomTarget = mainImage.querySelector('.zoomImg');
                        if (zoomTarget) {
                            zoomTarget.setAttribute('src', fullSizeUrl);
                        }
                    }
                    
                    // Remove active class from all thumbnails and add to current
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
        
        // Initialize zoom functionality if available
        if (typeof jQuery !== 'undefined' && jQuery.fn.zoom) {
            jQuery('.woocommerce-product-gallery__image').zoom({
                touch: false
            });
        }
    }
    
    /**
     * Quantity input controls
     */
    function quantityControls() {
        const quantities = document.querySelectorAll('.quantity');
        
        quantities.forEach(quantity => {
            // Create increase/decrease buttons if they don't exist
            if (!quantity.querySelector('.qty-decrease') && !quantity.querySelector('.qty-increase')) {
                const decreaseBtn = document.createElement('button');
                decreaseBtn.type = 'button';
                decreaseBtn.className = 'qty-decrease';
                decreaseBtn.setAttribute('aria-label', 'Decrease quantity');
                decreaseBtn.innerHTML = '−';
                
                const increaseBtn = document.createElement('button');
                increaseBtn.type = 'button';
                increaseBtn.className = 'qty-increase';
                increaseBtn.setAttribute('aria-label', 'Increase quantity');
                increaseBtn.innerHTML = '+';
                
                // Add buttons to DOM
                const input = quantity.querySelector('input.qty');
                if (input) {
                    quantity.insertBefore(decreaseBtn, input);
                    quantity.appendChild(increaseBtn);
                    
                    // Add event listeners
                    decreaseBtn.addEventListener('click', function() {
                        const currentVal = parseInt(input.value);
                        const min = parseInt(input.getAttribute('min')) || 1;
                        
                        if (currentVal > min) {
                            input.value = currentVal - 1;
                            // Trigger change event for cart updates
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                    
                    increaseBtn.addEventListener('click', function() {
                        const currentVal = parseInt(input.value);
                        const max = parseInt(input.getAttribute('max')) || '';
                        
                        if (max === '' || currentVal < max) {
                            input.value = currentVal + 1;
                            // Trigger change event for cart updates
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                }
            }
        });
    }
    
    /**
     * AJAX cart functionality
     */
    function ajaxCart() {
        // Only proceed if we have the WooCommerce AJAX endpoint
        if (typeof wc_add_to_cart_params === 'undefined') return;
        
        // Add to cart buttons
        const addToCartButtons = document.querySelectorAll('.add_to_cart_button:not(.product_type_variable):not(.product_type_grouped)');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product_id');
                const quantity = this.closest('form') ? 
                    this.closest('form').querySelector('input.qty').value : 
                    this.getAttribute('data-quantity') || 1;
                
                // Add loading state
                this.classList.add('loading');
                
                // AJAX add to cart
                fetch(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=${quantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        // Show error
                        showNotification(data.error, 'error');
                    } else {
                        // Success - update cart fragments
                        updateCartFragments(data.fragments);
                        showNotification('Product added to cart', 'success');
                        
                        // Update button state
                        this.classList.remove('loading');
                        this.classList.add('added');
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    this.classList.remove('loading');
                    showNotification('Error adding product to cart', 'error');
                });
            });
        });
        
        // Cart quantity update
        const cartQuantityInputs = document.querySelectorAll('.woocommerce-cart-form .qty');
        
        cartQuantityInputs.forEach(input => {
            input.addEventListener('change', debounce(function() {
                const updateCartButton = document.querySelector('[name="update_cart"]');
                if (updateCartButton) {
                    updateCartButton.disabled = false;
                    updateCartButton.click();
                }
            }, 500));
        });
    }
    
    /**
     * Update cart fragments
     * 
     * @param {Object} fragments - Cart fragments to update
     */
    function updateCartFragments(fragments) {
        if (!fragments) return;
        
        Object.keys(fragments).forEach(selector => {
            const fragment = fragments[selector];
            const element = document.querySelector(selector);
            
            if (element) {
                element.outerHTML = fragment;
            }
        });
    }
    
    /**
     * Show notification
     * 
     * @param {string} message - Notification message
     * @param {string} type - Notification type (success, error, info)
     */
    function showNotification(message, type = 'info') {
        // Check if notification container exists, create if not
        let notificationContainer = document.querySelector('.woocommerce-notifications');
        
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'woocommerce-notifications';
            document.body.appendChild(notificationContainer);
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">${message}</div>
            <button class="notification-close" aria-label="Close notification">×</button>
        `;
        
        // Add to container
        notificationContainer.appendChild(notification);
        
        // Add close button functionality
        const closeButton = notification.querySelector('.notification-close');
        closeButton.addEventListener('click', function() {
            notification.classList.add('notification-hiding');
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('notification-hiding');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }
    
    /**
     * Product filters functionality
     */
    function productFilters() {
        const filterForm = document.querySelector('.woocommerce-filter-form');
        if (!filterForm) return;
        
        // Toggle filter visibility on mobile
        const filterToggle = document.querySelector('.filter-toggle');
        if (filterToggle) {
            filterToggle.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                this.setAttribute('aria-expanded', !expanded);
                filterForm.classList.toggle('filters-active');
            });
        }
        
        // AJAX filter submission
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const searchParams = new URLSearchParams(formData);
            
            // Update URL with filter parameters
            window.history.pushState({}, '', `${window.location.pathname}?${searchParams.toString()}`);
            
            // Show loading state
            const productsContainer = document.querySelector('.products');
            if (productsContainer) {
                productsContainer.classList.add('loading');
            }
            
            // Fetch filtered products
            fetch(`${window.location.pathname}?${searchParams.toString()}&ajax_filter=1`)
                .then(response => response.text())
                .then(html => {
                    // Extract products HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newProducts = doc.querySelector('.products');
                    
                    if (newProducts && productsContainer) {
                        productsContainer.innerHTML = newProducts.innerHTML;
                        productsContainer.classList.remove('loading');
                    }
                })
                .catch(error => {
                    console.error('Error filtering products:', error);
                    if (productsContainer) {
                        productsContainer.classList.remove('loading');
                    }
                });
        });
    }
    
    /**
     * Quick view functionality
     */
    function quickView() {
        const quickViewButtons = document.querySelectorAll('.quick-view-button');
        
        quickViewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product-id');
                if (!productId) return;
                
                // Show loading state
                this.classList.add('loading');
                
                // Create modal if it doesn't exist
                let quickViewModal = document.querySelector('.quick-view-modal');
                if (!quickViewModal) {
                    quickViewModal = document.createElement('div');
                    quickViewModal.className = 'quick-view-modal';
                    quickViewModal.innerHTML = `
                        <div class="quick-view-container">
                            <div class="quick-view-content"></div>
                            <button class="quick-view-close" aria-label="Close quick view">×</button>
                        </div>
                    `;
                    document.body.appendChild(quickViewModal);
                    
                    // Add close button functionality
                    const closeButton = quickViewModal.querySelector('.quick-view-close');
                    closeButton.addEventListener('click', function() {
                        quickViewModal.classList.remove('active');
                    });
                    
                    // Close on click outside content
                    quickViewModal.addEventListener('click', function(e) {
                        if (e.target === quickViewModal) {
                            quickViewModal.classList.remove('active');
                        }
                    });
                }
                
                // Fetch product data
                fetch(`?wc-ajax=quick-view&product_id=${productId}`)
                    .then(response => response.text())
                    .then(html => {
                        const content = quickViewModal.querySelector('.quick-view-content');
                        if (content) {
                            content.innerHTML = html;
                            
                            // Initialize product gallery in quick view
                            productGallery();
                            
                            // Initialize quantity controls
                            quantityControls();
                            
                            // Show modal
                            quickViewModal.classList.add('active');
                            
                            // Remove loading state
                            this.classList.remove('loading');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading quick view:', error);
                        this.classList.remove('loading');
                    });
            });
        });
    }
    
    /**
     * Debounce function to limit how often a function can run
     * 
     * @param {Function} func - Function to debounce
     * @param {number} wait - Wait time in milliseconds
     * @return {Function} - Debounced function
     */
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    
    // Public API
    return {
        init: init
    };
})();