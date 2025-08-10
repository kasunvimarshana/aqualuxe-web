/**
 * AquaLuxe Theme Wishlist
 * 
 * This file handles the wishlist functionality.
 */

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    initWishlist();
});

/**
 * Initialize wishlist functionality
 */
function initWishlist() {
    const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
    const wishlistCount = document.querySelector('.wishlist-count');
    
    if (wishlistButtons.length === 0) return;
    
    // Add click event to wishlist buttons
    wishlistButtons.forEach(button => {
        button.addEventListener('click', handleWishlistClick);
    });
    
    // Update wishlist button states on page load
    updateWishlistButtonStates();
    
    // Update wishlist count on page load
    updateWishlistCount();
}

/**
 * Handle wishlist button click
 * @param {Event} e - Click event
 */
function handleWishlistClick(e) {
    e.preventDefault();
    
    const button = e.currentTarget;
    const productId = button.getAttribute('data-product-id');
    
    if (!productId) {
        console.error('Product ID not found');
        return;
    }
    
    // Show loading state
    button.classList.add('loading');
    
    // Toggle wishlist item
    toggleWishlistItem(productId)
        .then(response => {
            // Remove loading state
            button.classList.remove('loading');
            
            if (response.success) {
                // Update button state
                updateWishlistButtonState(button, response.in_wishlist);
                
                // Update wishlist count
                updateWishlistCount(response.count);
                
                // Show notification
                showWishlistNotification(response.message);
            } else {
                console.error('Error toggling wishlist item:', response.error);
                showWishlistNotification(response.error, true);
            }
        })
        .catch(error => {
            // Remove loading state
            button.classList.remove('loading');
            console.error('Error toggling wishlist item:', error);
            showWishlistNotification('Error updating wishlist', true);
        });
}

/**
 * Toggle wishlist item using WordPress AJAX
 * @param {string} productId - Product ID
 * @returns {Promise} - Promise with response data
 */
function toggleWishlistItem(productId) {
    return new Promise((resolve, reject) => {
        // Create form data
        const formData = new FormData();
        formData.append('action', 'aqualuxe_toggle_wishlist');
        formData.append('product_id', productId);
        formData.append('nonce', aqualuxeData.nonce);
        
        // Send AJAX request
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
 * Update wishlist button states on page load
 */
function updateWishlistButtonStates() {
    // Get wishlist items from localStorage
    const wishlistItems = getWishlistItems();
    
    // Update button states
    const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
    wishlistButtons.forEach(button => {
        const productId = button.getAttribute('data-product-id');
        if (productId && wishlistItems.includes(productId)) {
            updateWishlistButtonState(button, true);
        } else {
            updateWishlistButtonState(button, false);
        }
    });
}

/**
 * Update wishlist button state
 * @param {HTMLElement} button - Wishlist button
 * @param {boolean} inWishlist - Whether product is in wishlist
 */
function updateWishlistButtonState(button, inWishlist) {
    const addToWishlistText = button.getAttribute('data-add-text') || 'Add to Wishlist';
    const removeFromWishlistText = button.getAttribute('data-remove-text') || 'Remove from Wishlist';
    
    if (inWishlist) {
        button.classList.add('in-wishlist');
        button.setAttribute('title', removeFromWishlistText);
        
        // Update button text if it has text content
        if (button.tagName.toLowerCase() !== 'a' || !button.querySelector('svg')) {
            button.textContent = removeFromWishlistText;
        }
        
        // Update icon if it exists
        const icon = button.querySelector('.wishlist-icon');
        if (icon) {
            icon.classList.add('filled');
        }
    } else {
        button.classList.remove('in-wishlist');
        button.setAttribute('title', addToWishlistText);
        
        // Update button text if it has text content
        if (button.tagName.toLowerCase() !== 'a' || !button.querySelector('svg')) {
            button.textContent = addToWishlistText;
        }
        
        // Update icon if it exists
        const icon = button.querySelector('.wishlist-icon');
        if (icon) {
            icon.classList.remove('filled');
        }
    }
}

/**
 * Update wishlist count
 * @param {number} count - Wishlist count (optional)
 */
function updateWishlistCount(count = null) {
    const wishlistCount = document.querySelector('.wishlist-count');
    if (!wishlistCount) return;
    
    // If count is not provided, calculate from localStorage
    if (count === null) {
        const wishlistItems = getWishlistItems();
        count = wishlistItems.length;
    }
    
    // Update count
    wishlistCount.textContent = count;
    
    // Show/hide count
    if (count > 0) {
        wishlistCount.classList.add('has-items');
    } else {
        wishlistCount.classList.remove('has-items');
    }
}

/**
 * Get wishlist items from localStorage
 * @returns {Array} - Array of product IDs
 */
function getWishlistItems() {
    const wishlistData = localStorage.getItem('aqualuxe_wishlist');
    return wishlistData ? JSON.parse(wishlistData) : [];
}

/**
 * Show wishlist notification
 * @param {string} message - Notification message
 * @param {boolean} isError - Whether notification is an error
 */
function showWishlistNotification(message, isError = false) {
    // Check if notification container exists
    let notificationContainer = document.querySelector('.wishlist-notification');
    
    if (!notificationContainer) {
        // Create notification container
        notificationContainer = document.createElement('div');
        notificationContainer.classList.add('wishlist-notification');
        document.body.appendChild(notificationContainer);
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.classList.add('notification');
    
    if (isError) {
        notification.classList.add('error');
    }
    
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" aria-label="Close notification">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    `;
    
    // Add notification to container
    notificationContainer.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('active');
    }, 10);
    
    // Add close event to close button
    const closeButton = notification.querySelector('.notification-close');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            notification.classList.remove('active');
            
            // Remove notification after animation
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
    }
    
    // Auto-remove notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('active');
        
        // Remove notification after animation
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

/**
 * Handle wishlist page actions
 */
function initWishlistPage() {
    const wishlistTable = document.querySelector('.wishlist-table');
    if (!wishlistTable) return;
    
    // Handle remove buttons
    const removeButtons = wishlistTable.querySelectorAll('.remove-from-wishlist');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product-id');
            const row = this.closest('tr');
            
            if (productId && row) {
                // Show loading state
                this.classList.add('loading');
                
                // Remove from wishlist
                toggleWishlistItem(productId)
                    .then(response => {
                        if (response.success) {
                            // Remove row with animation
                            row.classList.add('removing');
                            
                            setTimeout(() => {
                                row.remove();
                                
                                // Update wishlist count
                                updateWishlistCount(response.count);
                                
                                // Show empty message if no items left
                                if (response.count === 0) {
                                    const tbody = wishlistTable.querySelector('tbody');
                                    if (tbody) {
                                        tbody.innerHTML = `
                                            <tr class="wishlist-empty">
                                                <td colspan="6">Your wishlist is empty</td>
                                            </tr>
                                        `;
                                    }
                                }
                            }, 300);
                        } else {
                            // Remove loading state
                            this.classList.remove('loading');
                            console.error('Error removing from wishlist:', response.error);
                            showWishlistNotification(response.error, true);
                        }
                    })
                    .catch(error => {
                        // Remove loading state
                        this.classList.remove('loading');
                        console.error('Error removing from wishlist:', error);
                        showWishlistNotification('Error updating wishlist', true);
                    });
            }
        });
    });
    
    // Handle add to cart buttons
    const addToCartButtons = wishlistTable.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product-id');
            
            if (productId) {
                // Show loading state
                this.classList.add('loading');
                
                // Add to cart
                addToCart(productId)
                    .then(response => {
                        // Remove loading state
                        this.classList.remove('loading');
                        
                        if (response.success) {
                            // Show notification
                            showWishlistNotification(response.message);
                            
                            // Update cart fragments
                            if (response.fragments) {
                                for (const key in response.fragments) {
                                    const fragment = document.querySelector(key);
                                    if (fragment) {
                                        fragment.outerHTML = response.fragments[key];
                                    }
                                }
                            }
                        } else {
                            console.error('Error adding to cart:', response.error);
                            showWishlistNotification(response.error, true);
                        }
                    })
                    .catch(error => {
                        // Remove loading state
                        this.classList.remove('loading');
                        console.error('Error adding to cart:', error);
                        showWishlistNotification('Error adding to cart', true);
                    });
            }
        });
    });
}

/**
 * Add product to cart using WordPress AJAX
 * @param {string} productId - Product ID
 * @returns {Promise} - Promise with response data
 */
function addToCart(productId) {
    return new Promise((resolve, reject) => {
        // Create form data
        const formData = new FormData();
        formData.append('action', 'aqualuxe_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', 1);
        formData.append('nonce', aqualuxeData.nonce);
        
        // Send AJAX request
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

// Initialize wishlist page if on wishlist page
if (document.querySelector('.wishlist-table')) {
    initWishlistPage();
}

// Export functions for use in other files
export {
    initWishlist,
    toggleWishlistItem,
    updateWishlistCount
};