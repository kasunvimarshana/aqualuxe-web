/**
 * AquaLuxe Theme WooCommerce JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize WooCommerce components
    initWooCommerce();
});

/**
 * Initialize WooCommerce components
 */
function initWooCommerce() {
    // Quick view
    initQuickView();
    
    // Wishlist
    initWishlist();
    
    // AJAX add to cart
    initAjaxAddToCart();
    
    // Quantity input
    initQuantityInput();
    
    // Product gallery
    initProductGallery();
    
    // Product tabs
    initProductTabs();
    
    // Product inquiry form
    initProductInquiryForm();
}

/**
 * Initialize quick view
 */
function initQuickView() {
    const quickViewButtons = document.querySelectorAll('.quick-view-button');
    
    quickViewButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product-id');
            
            // Show loading
            this.innerHTML = aqualuxeWoocommerce.i18n.loading;
            this.disabled = true;
            
            // Send AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', aqualuxeWoocommerce.ajaxUrl, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        
                        if (response.success) {
                            // Create modal
                            const modal = document.createElement('div');
                            modal.classList.add('modal', 'fixed', 'inset-0', 'flex', 'items-center', 'justify-center', 'z-50', 'bg-black', 'bg-opacity-50');
                            modal.innerHTML = `
                                <div class="modal-content bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                                    <div class="modal-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="text-xl font-bold">${aqualuxeWoocommerce.i18n.quickView}</h3>
                                        <button class="modal-close text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
                                        </button>
                                    </div>
                                    <div class="modal-body p-4">
                                        ${response.data.html}
                                    </div>
                                </div>
                            `;
                            
                            // Add modal to body
                            document.body.appendChild(modal);
                            
                            // Add event listener to close button
                            modal.querySelector('.modal-close').addEventListener('click', function() {
                                document.body.removeChild(modal);
                            });
                            
                            // Add event listener to modal background
                            modal.addEventListener('click', function(e) {
                                if (e.target === this) {
                                    document.body.removeChild(modal);
                                }
                            });
                            
                            // Initialize product gallery
                            initProductGallery(modal);
                            
                            // Initialize quantity input
                            initQuantityInput(modal);
                            
                            // Initialize AJAX add to cart
                            initAjaxAddToCart(modal);
                        } else {
                            console.error(response.data);
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }
                
                // Reset button
                button.innerHTML = aqualuxeWoocommerce.i18n.quickView;
                button.disabled = false;
            };
            xhr.send(
                'action=aqualuxe_quick_view' +
                '&nonce=' + encodeURIComponent(aqualuxeWoocommerce.nonce) +
                '&product_id=' + encodeURIComponent(productId)
            );
        });
    });
}

/**
 * Initialize wishlist
 */
function initWishlist() {
    const wishlistButtons = document.querySelectorAll('.wishlist-button');
    
    wishlistButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product-id');
            const isAddToWishlist = this.classList.contains('add-to-wishlist');
            
            // Show loading
            const originalText = this.innerHTML;
            this.innerHTML = aqualuxeWoocommerce.i18n.loading;
            this.disabled = true;
            
            // Send AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', aqualuxeWoocommerce.ajaxUrl, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        
                        if (response.success) {
                            // Update button
                            if (isAddToWishlist) {
                                button.classList.remove('add-to-wishlist');
                                button.classList.add('remove-from-wishlist');
                                button.innerHTML = aqualuxeWoocommerce.i18n.removeFromWishlist;
                                
                                // Show notification
                                showNotification(aqualuxeWoocommerce.i18n.addedToWishlist, 'success');
                            } else {
                                button.classList.remove('remove-from-wishlist');
                                button.classList.add('add-to-wishlist');
                                button.innerHTML = aqualuxeWoocommerce.i18n.addToWishlist;
                                
                                // Show notification
                                showNotification(aqualuxeWoocommerce.i18n.removedFromWishlist, 'success');
                            }
                        } else {
                            console.error(response.data);
                            
                            // Reset button
                            button.innerHTML = originalText;
                            
                            // Show notification
                            showNotification(aqualuxeWoocommerce.i18n.error, 'error');
                        }
                    } catch (e) {
                        console.error(e);
                        
                        // Reset button
                        button.innerHTML = originalText;
                        
                        // Show notification
                        showNotification(aqualuxeWoocommerce.i18n.error, 'error');
                    }
                }
                
                // Enable button
                button.disabled = false;
            };
            xhr.send(
                'action=' + (isAddToWishlist ? 'aqualuxe_add_to_wishlist' : 'aqualuxe_remove_from_wishlist') +
                '&nonce=' + encodeURIComponent(aqualuxeWoocommerce.nonce) +
                '&product_id=' + encodeURIComponent(productId)
            );
        });
    });
}

/**
 * Initialize AJAX add to cart
 *
 * @param {Element} context Context element
 */
function initAjaxAddToCart(context = document) {
    const addToCartButtons = context.querySelectorAll('.ajax_add_to_cart');
    
    addToCartButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product_id');
            const quantity = this.getAttribute('data-quantity') || 1;
            const variationId = this.getAttribute('data-variation_id') || 0;
            const variations = {};
            
            // Get variations
            if (variationId) {
                const form = this.closest('form');
                
                if (form) {
                    const variationInputs = form.querySelectorAll('[name^="attribute_"]');
                    
                    variationInputs.forEach(function(input) {
                        const name = input.getAttribute('name');
                        const value = input.value;
                        
                        variations[name] = value;
                    });
                }
            }
            
            // Show loading
            const originalText = this.innerHTML;
            this.innerHTML = aqualuxeWoocommerce.i18n.addingToCart;
            this.disabled = true;
            
            // Send AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', aqualuxeWoocommerce.ajaxUrl, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        
                        if (response.success) {
                            // Update cart fragments
                            updateCartFragments(response.data.fragments);
                            
                            // Update button
                            button.innerHTML = aqualuxeWoocommerce.i18n.addedToCart;
                            
                            // Show notification
                            showNotification(aqualuxeWoocommerce.i18n.addedToCart, 'success');
                            
                            // Reset button after 2 seconds
                            setTimeout(function() {
                                button.innerHTML = originalText;
                                button.disabled = false;
                            }, 2000);
                        } else {
                            console.error(response.data);
                            
                            // Reset button
                            button.innerHTML = originalText;
                            button.disabled = false;
                            
                            // Show notification
                            showNotification(aqualuxeWoocommerce.i18n.error, 'error');
                        }
                    } catch (e) {
                        console.error(e);
                        
                        // Reset button
                        button.innerHTML = originalText;
                        button.disabled = false;
                        
                        // Show notification
                        showNotification(aqualuxeWoocommerce.i18n.error, 'error');
                    }
                }
            };
            xhr.send(
                'action=aqualuxe_add_to_cart' +
                '&nonce=' + encodeURIComponent(aqualuxeWoocommerce.nonce) +
                '&product_id=' + encodeURIComponent(productId) +
                '&quantity=' + encodeURIComponent(quantity) +
                '&variation_id=' + encodeURIComponent(variationId) +
                '&variation=' + encodeURIComponent(JSON.stringify(variations))
            );
        });
    });
}

/**
 * Initialize quantity input
 *
 * @param {Element} context Context element
 */
function initQuantityInput(context = document) {
    const quantityInputs = context.querySelectorAll('.quantity');
    
    quantityInputs.forEach(function(quantity) {
        const input = quantity.querySelector('input[type="number"]');
        
        if (!input) {
            return;
        }
        
        // Add decrement button
        const decrementButton = document.createElement('button');
        decrementButton.type = 'button';
        decrementButton.classList.add('quantity-button', 'quantity-down');
        decrementButton.innerHTML = '-';
        decrementButton.addEventListener('click', function() {
            const currentValue = parseInt(input.value, 10);
            const min = parseInt(input.getAttribute('min'), 10) || 1;
            
            if (currentValue > min) {
                input.value = currentValue - 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        
        // Add increment button
        const incrementButton = document.createElement('button');
        incrementButton.type = 'button';
        incrementButton.classList.add('quantity-button', 'quantity-up');
        incrementButton.innerHTML = '+';
        incrementButton.addEventListener('click', function() {
            const currentValue = parseInt(input.value, 10);
            const max = parseInt(input.getAttribute('max'), 10) || 0;
            
            if (max === 0 || currentValue < max) {
                input.value = currentValue + 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        
        // Add buttons to quantity
        quantity.insertBefore(decrementButton, input);
        quantity.appendChild(incrementButton);
    });
}

/**
 * Initialize product gallery
 *
 * @param {Element} context Context element
 */
function initProductGallery(context = document) {
    const galleries = context.querySelectorAll('.woocommerce-product-gallery');
    
    galleries.forEach(function(gallery) {
        const images = gallery.querySelectorAll('.woocommerce-product-gallery__image');
        const thumbnails = gallery.querySelectorAll('.woocommerce-product-gallery__thumbnail');
        
        if (images.length <= 1) {
            return;
        }
        
        // Add navigation buttons
        const prevButton = document.createElement('button');
        prevButton.type = 'button';
        prevButton.classList.add('gallery-nav', 'gallery-prev');
        prevButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"/></svg>';
        
        const nextButton = document.createElement('button');
        nextButton.type = 'button';
        nextButton.classList.add('gallery-nav', 'gallery-next');
        nextButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>';
        
        gallery.querySelector('.woocommerce-product-gallery__wrapper').appendChild(prevButton);
        gallery.querySelector('.woocommerce-product-gallery__wrapper').appendChild(nextButton);
        
        // Initialize gallery
        let currentIndex = 0;
        
        // Show image
        function showImage(index) {
            images.forEach(function(image, i) {
                if (i === index) {
                    image.style.display = 'block';
                } else {
                    image.style.display = 'none';
                }
            });
            
            thumbnails.forEach(function(thumbnail, i) {
                if (i === index) {
                    thumbnail.classList.add('active');
                } else {
                    thumbnail.classList.remove('active');
                }
            });
            
            currentIndex = index;
        }
        
        // Show first image
        showImage(0);
        
        // Add event listeners to thumbnails
        thumbnails.forEach(function(thumbnail, index) {
            thumbnail.addEventListener('click', function() {
                showImage(index);
            });
        });
        
        // Add event listeners to navigation buttons
        prevButton.addEventListener('click', function() {
            const prevIndex = currentIndex === 0 ? images.length - 1 : currentIndex - 1;
            showImage(prevIndex);
        });
        
        nextButton.addEventListener('click', function() {
            const nextIndex = currentIndex === images.length - 1 ? 0 : currentIndex + 1;
            showImage(nextIndex);
        });
    });
}

/**
 * Initialize product tabs
 */
function initProductTabs() {
    const tabsContainer = document.querySelector('.woocommerce-tabs');
    
    if (!tabsContainer) {
        return;
    }
    
    const tabs = tabsContainer.querySelectorAll('.tabs li');
    const panels = tabsContainer.querySelectorAll('.woocommerce-Tabs-panel');
    
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('aria-controls');
            
            // Remove active class from all tabs
            tabs.forEach(function(tab) {
                tab.classList.remove('active');
                tab.setAttribute('aria-selected', 'false');
            });
            
            // Add active class to current tab
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            // Hide all panels
            panels.forEach(function(panel) {
                panel.style.display = 'none';
            });
            
            // Show current panel
            document.getElementById(target).style.display = 'block';
        });
    });
}

/**
 * Initialize product inquiry form
 */
function initProductInquiryForm() {
    const inquiryForm = document.querySelector('.inquiry-form');
    
    if (!inquiryForm) {
        return;
    }
    
    inquiryForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        const data = {};
        
        for (const [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        // Show loading
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = aqualuxeWoocommerce.i18n.loading;
        submitButton.disabled = true;
        
        // Send AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', aqualuxeWoocommerce.ajaxUrl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    if (response.success) {
                        // Show success message
                        showNotification(response.data.message, 'success');
                        
                        // Reset form
                        inquiryForm.reset();
                    } else {
                        // Show error message
                        showNotification(response.data, 'error');
                    }
                } catch (e) {
                    console.error(e);
                    
                    // Show error message
                    showNotification(aqualuxeWoocommerce.i18n.error, 'error');
                }
            }
            
            // Reset button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        };
        xhr.send(
            'action=aqualuxe_product_inquiry' +
            '&nonce=' + encodeURIComponent(aqualuxeWoocommerce.nonce) +
            '&product_id=' + encodeURIComponent(data.product_id) +
            '&product_name=' + encodeURIComponent(data.product_name) +
            '&inquiry_name=' + encodeURIComponent(data.inquiry_name) +
            '&inquiry_email=' + encodeURIComponent(data.inquiry_email) +
            '&inquiry_message=' + encodeURIComponent(data.inquiry_message)
        );
    });
}

/**
 * Update cart fragments
 *
 * @param {Object} fragments Cart fragments
 */
function updateCartFragments(fragments) {
    if (!fragments) {
        return;
    }
    
    Object.keys(fragments).forEach(function(selector) {
        const fragment = fragments[selector];
        const elements = document.querySelectorAll(selector);
        
        elements.forEach(function(element) {
            element.outerHTML = fragment;
        });
    });
}

/**
 * Show notification
 *
 * @param {string} message Notification message
 * @param {string} type Notification type
 */
function showNotification(message, type = 'info') {
    // Create notification
    const notification = document.createElement('div');
    notification.classList.add('notification', `notification-${type}`, 'fixed', 'top-4', 'right-4', 'z-50', 'p-4', 'rounded-lg', 'shadow-lg', 'flex', 'items-center', 'justify-between', 'max-w-md', 'w-full', 'transform', 'translate-x-full', 'transition-transform', 'duration-300');
    
    // Set background color based on type
    switch (type) {
        case 'success':
            notification.classList.add('bg-green-500', 'text-white');
            break;
        case 'error':
            notification.classList.add('bg-red-500', 'text-white');
            break;
        case 'warning':
            notification.classList.add('bg-yellow-500', 'text-white');
            break;
        default:
            notification.classList.add('bg-blue-500', 'text-white');
            break;
    }
    
    // Add content
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="notification-icon mr-3">
                ${getNotificationIcon(type)}
            </div>
            <div class="notification-content">
                <div class="notification-message">${message}</div>
            </div>
        </div>
        <button class="notification-close ml-4 text-white hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
        </button>
    `;
    
    // Add notification to body
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(function() {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    // Add event listener to close button
    notification.querySelector('.notification-close').addEventListener('click', function() {
        hideNotification(notification);
    });
    
    // Auto hide after 5 seconds
    setTimeout(function() {
        hideNotification(notification);
    }, 5000);
}

/**
 * Hide notification
 *
 * @param {Element} notification Notification element
 */
function hideNotification(notification) {
    notification.classList.add('translate-x-full');
    
    setTimeout(function() {
        document.body.removeChild(notification);
    }, 300);
}

/**
 * Get notification icon
 *
 * @param {string} type Notification type
 * @return {string} Notification icon
 */
function getNotificationIcon(type) {
    switch (type) {
        case 'success':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z"/></svg>';
        case 'error':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm0-8v6h2V7h-2z"/></svg>';
        case 'warning':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm0-8v6h2V7h-2z"/></svg>';
        default:
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"/></svg>';
    }
}