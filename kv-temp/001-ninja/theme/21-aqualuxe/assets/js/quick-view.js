/**
 * AquaLuxe Theme Quick View
 * 
 * This file handles the product quick view functionality.
 */

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    initQuickView();
});

/**
 * Initialize quick view functionality
 */
function initQuickView() {
    const quickViewButtons = document.querySelectorAll('.quick-view-button');
    
    if (quickViewButtons.length === 0) return;
    
    // Add click event to quick view buttons
    quickViewButtons.forEach(button => {
        button.addEventListener('click', handleQuickViewClick);
    });
    
    // Close quick view modal when clicking outside
    document.addEventListener('click', function(e) {
        const quickViewModal = document.querySelector('.quick-view-modal.active');
        if (quickViewModal && !quickViewModal.contains(e.target) && !e.target.classList.contains('quick-view-button')) {
            closeQuickViewModal();
        }
    });
    
    // Close quick view modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQuickViewModal();
        }
    });
}

/**
 * Handle quick view button click
 * @param {Event} e - Click event
 */
function handleQuickViewClick(e) {
    e.preventDefault();
    
    const button = e.currentTarget;
    const productId = button.getAttribute('data-product-id');
    
    if (!productId) {
        console.error('Product ID not found');
        return;
    }
    
    // Show loading spinner
    button.classList.add('loading');
    
    // Fetch product data using WordPress AJAX
    fetchProductData(productId)
        .then(response => {
            // Hide loading spinner
            button.classList.remove('loading');
            
            if (response.success) {
                // Create and show modal with product data
                createQuickViewModal(response.data);
            } else {
                console.error('Error fetching product data:', response.error);
            }
        })
        .catch(error => {
            // Hide loading spinner
            button.classList.remove('loading');
            console.error('Error fetching product data:', error);
        });
}

/**
 * Fetch product data using WordPress AJAX
 * @param {string} productId - Product ID
 * @returns {Promise} - Promise with product data
 */
function fetchProductData(productId) {
    return new Promise((resolve, reject) => {
        // Create form data
        const formData = new FormData();
        formData.append('action', 'aqualuxe_quick_view');
        formData.append('product_id', productId);
        formData.append('nonce', aqualuxeData.nonce);
        
        // Fetch product data
        fetch(aqualuxeData.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => response.json())
        .then(data => resolve(data))
        .catch(error => reject(error));
    });
}

/**
 * Create quick view modal with product data
 * @param {Object} productData - Product data
 */
function createQuickViewModal(productData) {
    // Check if modal already exists
    let quickViewModal = document.querySelector('.quick-view-modal');
    
    if (!quickViewModal) {
        // Create modal element
        quickViewModal = document.createElement('div');
        quickViewModal.classList.add('quick-view-modal');
        document.body.appendChild(quickViewModal);
    }
    
    // Add modal content
    quickViewModal.innerHTML = `
        <div class="quick-view-container">
            <button class="quick-view-close" aria-label="Close quick view">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <div class="quick-view-content">
                <div class="quick-view-images">
                    ${productData.gallery_html}
                </div>
                <div class="quick-view-details">
                    <h2 class="quick-view-title">${productData.title}</h2>
                    <div class="quick-view-price">${productData.price_html}</div>
                    <div class="quick-view-rating">${productData.rating_html}</div>
                    <div class="quick-view-description">${productData.short_description}</div>
                    <div class="quick-view-add-to-cart">
                        ${productData.add_to_cart_html}
                    </div>
                    <div class="quick-view-meta">
                        ${productData.meta_html}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Show modal
    setTimeout(() => {
        quickViewModal.classList.add('active');
        document.body.classList.add('quick-view-open');
        
        // Initialize product gallery slider
        initQuickViewGallery();
        
        // Initialize quantity input
        initQuantityInput();
        
        // Add close event to close button
        const closeButton = quickViewModal.querySelector('.quick-view-close');
        if (closeButton) {
            closeButton.addEventListener('click', closeQuickViewModal);
        }
        
        // Handle add to cart form submission
        const addToCartForm = quickViewModal.querySelector('.cart');
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', handleAddToCart);
        }
    }, 10);
}

/**
 * Initialize product gallery slider in quick view
 */
function initQuickViewGallery() {
    const galleryContainer = document.querySelector('.quick-view-images');
    if (!galleryContainer) return;
    
    const mainImage = galleryContainer.querySelector('.woocommerce-product-gallery__image');
    const thumbnails = galleryContainer.querySelectorAll('.woocommerce-product-gallery__thumbnail');
    
    if (thumbnails.length > 0) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get full size image URL
                const fullSizeUrl = this.getAttribute('data-full-size');
                const fullSizeWidth = this.getAttribute('data-width');
                const fullSizeHeight = this.getAttribute('data-height');
                
                // Update main image
                if (mainImage) {
                    const mainImageImg = mainImage.querySelector('img');
                    const mainImageLink = mainImage.querySelector('a');
                    
                    if (mainImageImg) {
                        mainImageImg.src = fullSizeUrl;
                        mainImageImg.setAttribute('srcset', '');
                        mainImageImg.setAttribute('data-src', fullSizeUrl);
                        mainImageImg.setAttribute('data-large_image', fullSizeUrl);
                        mainImageImg.setAttribute('data-large_image_width', fullSizeWidth);
                        mainImageImg.setAttribute('data-large_image_height', fullSizeHeight);
                    }
                    
                    if (mainImageLink) {
                        mainImageLink.href = fullSizeUrl;
                    }
                }
                
                // Update active thumbnail
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Activate first thumbnail by default
        thumbnails[0].classList.add('active');
    }
}

/**
 * Initialize quantity input in quick view
 */
function initQuantityInput() {
    const quantityInputs = document.querySelectorAll('.quick-view-modal .quantity');
    
    quantityInputs.forEach(wrapper => {
        const input = wrapper.querySelector('input[type="number"]');
        const minusButton = document.createElement('button');
        const plusButton = document.createElement('button');
        
        minusButton.type = 'button';
        minusButton.classList.add('quantity-button', 'minus');
        minusButton.textContent = '-';
        
        plusButton.type = 'button';
        plusButton.classList.add('quantity-button', 'plus');
        plusButton.textContent = '+';
        
        if (input) {
            // Add buttons to DOM
            wrapper.insertBefore(minusButton, input);
            wrapper.appendChild(plusButton);
            
            // Add event listeners
            minusButton.addEventListener('click', () => {
                const currentValue = parseInt(input.value);
                const minValue = parseInt(input.min) || 1;
                
                if (currentValue > minValue) {
                    input.value = currentValue - 1;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
            
            plusButton.addEventListener('click', () => {
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.max);
                
                if (!maxValue || currentValue < maxValue) {
                    input.value = currentValue + 1;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    });
}

/**
 * Handle add to cart form submission
 * @param {Event} e - Submit event
 */
function handleAddToCart(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Show loading state
    if (submitButton) {
        submitButton.classList.add('loading');
        submitButton.disabled = true;
    }
    
    // Create form data
    const formData = new FormData(form);
    formData.append('action', 'aqualuxe_add_to_cart');
    formData.append('nonce', aqualuxeData.nonce);
    
    // Send AJAX request
    fetch(aqualuxeData.ajax_url, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Reset loading state
        if (submitButton) {
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
        }
        
        if (data.success) {
            // Show success message
            const messageContainer = document.createElement('div');
            messageContainer.classList.add('woocommerce-message');
            messageContainer.textContent = data.message;
            
            const quickViewContent = document.querySelector('.quick-view-content');
            if (quickViewContent) {
                quickViewContent.insertBefore(messageContainer, quickViewContent.firstChild);
                
                // Remove message after 3 seconds
                setTimeout(() => {
                    messageContainer.remove();
                }, 3000);
            }
            
            // Update cart fragments
            if (data.fragments) {
                for (const key in data.fragments) {
                    const fragment = document.querySelector(key);
                    if (fragment) {
                        fragment.outerHTML = data.fragments[key];
                    }
                }
            }
            
            // Close modal after 2 seconds
            setTimeout(() => {
                closeQuickViewModal();
            }, 2000);
        } else {
            // Show error message
            const messageContainer = document.createElement('div');
            messageContainer.classList.add('woocommerce-error');
            messageContainer.textContent = data.message || 'Error adding to cart';
            
            const quickViewContent = document.querySelector('.quick-view-content');
            if (quickViewContent) {
                quickViewContent.insertBefore(messageContainer, quickViewContent.firstChild);
                
                // Remove message after 3 seconds
                setTimeout(() => {
                    messageContainer.remove();
                }, 3000);
            }
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        
        // Reset loading state
        if (submitButton) {
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
        }
    });
}

/**
 * Close quick view modal
 */
function closeQuickViewModal() {
    const quickViewModal = document.querySelector('.quick-view-modal');
    
    if (quickViewModal) {
        quickViewModal.classList.remove('active');
        document.body.classList.remove('quick-view-open');
        
        // Remove modal after animation
        setTimeout(() => {
            quickViewModal.remove();
        }, 300);
    }
}

// Export functions for use in other files
export {
    initQuickView,
    closeQuickViewModal
};