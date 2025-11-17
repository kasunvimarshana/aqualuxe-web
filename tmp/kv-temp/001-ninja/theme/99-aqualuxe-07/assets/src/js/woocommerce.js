/**
 * WooCommerce Integration JavaScript
 * 
 * Handles WooCommerce-specific functionality
 * Cart, checkout, product interactions, etc.
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

class AquaLuxeWooCommerce {
    constructor() {
        this.init();
    }

    init() {
        this.initQuickView();
        this.initAjaxCart();
        this.initProductGallery();
        this.initWishlist();
        this.initProductFilters();
    }

    initQuickView() {
        const quickViewButtons = document.querySelectorAll('.quick-view-btn');
        
        quickViewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = button.dataset.productId;
                this.openQuickView(productId);
            });
        });
    }

    openQuickView(productId) {
        // Create and show quick view modal
        const modal = this.createQuickViewModal();
        document.body.appendChild(modal);
        
        // Load product data via AJAX
        this.loadQuickViewContent(productId, modal);
    }

    createQuickViewModal() {
        const modal = document.createElement('div');
        modal.className = 'quick-view-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
        modal.innerHTML = `
            <div class="quick-view-content bg-white rounded-lg max-w-4xl w-full m-4 max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Product Details</h3>
                        <button class="close-quick-view text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="quick-view-body">
                        <div class="loading text-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500 mx-auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Close modal functionality
        const closeBtn = modal.querySelector('.close-quick-view');
        closeBtn.addEventListener('click', () => {
            modal.remove();
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        return modal;
    }

    loadQuickViewContent(productId, modal) {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_quick_view');
        formData.append('product_id', productId);
        formData.append('nonce', window.AQUALUXE.nonce);

        fetch(window.AQUALUXE.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.querySelector('.quick-view-body').innerHTML = data.data.html;
                this.initQuickViewGallery(modal);
            } else {
                modal.querySelector('.quick-view-body').innerHTML = '<p class="text-red-500">Error loading product details.</p>';
            }
        })
        .catch(error => {
            console.error('Quick view error:', error);
            modal.querySelector('.quick-view-body').innerHTML = '<p class="text-red-500">Error loading product details.</p>';
        });
    }

    initQuickViewGallery(modal) {
        // Initialize product image gallery within quick view
        const galleryImages = modal.querySelectorAll('.product-gallery img');
        const mainImage = modal.querySelector('.main-product-image');

        galleryImages.forEach(img => {
            img.addEventListener('click', () => {
                if (mainImage) {
                    mainImage.src = img.src;
                    mainImage.alt = img.alt;
                }
            });
        });
    }

    initAjaxCart() {
        // Handle AJAX add to cart
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('ajax-add-to-cart')) {
                e.preventDefault();
                this.addToCartAjax(e.target);
            }
        });

        // Update cart fragments
        document.body.addEventListener('added_to_cart', () => {
            this.updateCartFragments();
        });
    }

    addToCartAjax(button) {
        const productId = button.dataset.productId;
        const quantity = button.dataset.quantity || 1;
        
        button.classList.add('loading');
        button.disabled = true;

        const formData = new FormData();
        formData.append('action', 'woocommerce_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        fetch(window.AQUALUXE.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Trigger WooCommerce added_to_cart event
                document.body.dispatchEvent(new CustomEvent('added_to_cart', {
                    detail: { productId, quantity }
                }));
                
                this.showCartNotification('Product added to cart!');
            } else {
                this.showCartNotification('Error adding product to cart.', 'error');
            }
        })
        .catch(error => {
            console.error('Add to cart error:', error);
            this.showCartNotification('Error adding product to cart.', 'error');
        })
        .finally(() => {
            button.classList.remove('loading');
            button.disabled = false;
        });
    }

    updateCartFragments() {
        // Update cart count and other fragments
        const formData = new FormData();
        formData.append('action', 'woocommerce_get_refreshed_fragments');

        fetch(window.AQUALUXE.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.fragments) {
                Object.keys(data.fragments).forEach(selector => {
                    const elements = document.querySelectorAll(selector);
                    elements.forEach(element => {
                        element.outerHTML = data.fragments[selector];
                    });
                });
            }
        });
    }

    showCartNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `cart-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    initProductGallery() {
        const productGalleries = document.querySelectorAll('.product-gallery');
        
        productGalleries.forEach(gallery => {
            this.setupProductGallery(gallery);
        });
    }

    setupProductGallery(gallery) {
        const thumbnails = gallery.querySelectorAll('.gallery-thumbnail');
        const mainImage = gallery.querySelector('.main-product-image');

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', () => {
                const fullSizeUrl = thumb.dataset.fullSize || thumb.src;
                if (mainImage) {
                    mainImage.src = fullSizeUrl;
                    mainImage.alt = thumb.alt;
                }

                // Update active thumbnail
                thumbnails.forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            });
        });
    }

    initWishlist() {
        const wishlistButtons = document.querySelectorAll('.wishlist-btn');
        
        wishlistButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleWishlist(button);
            });
        });
    }

    toggleWishlist(button) {
        const productId = button.dataset.productId;
        const isInWishlist = button.classList.contains('in-wishlist');
        
        button.classList.add('loading');

        const formData = new FormData();
        formData.append('action', 'aqualuxe_toggle_wishlist');
        formData.append('product_id', productId);
        formData.append('nonce', window.AQUALUXE.nonce);

        fetch(window.AQUALUXE.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data.added) {
                    button.classList.add('in-wishlist');
                    button.title = 'Remove from wishlist';
                } else {
                    button.classList.remove('in-wishlist');
                    button.title = 'Add to wishlist';
                }
            }
        })
        .catch(error => {
            console.error('Wishlist error:', error);
        })
        .finally(() => {
            button.classList.remove('loading');
        });
    }

    initProductFilters() {
        const filterForm = document.querySelector('.product-filters');
        if (!filterForm) return;

        const filterInputs = filterForm.querySelectorAll('input, select');
        
        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                this.applyFilters();
            });
        });
    }

    applyFilters() {
        const filterForm = document.querySelector('.product-filters');
        const formData = new FormData(filterForm);
        formData.append('action', 'aqualuxe_filter_products');
        
        const productsContainer = document.querySelector('.products-grid');
        if (productsContainer) {
            productsContainer.classList.add('loading');
        }

        fetch(window.AQUALUXE.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && productsContainer) {
                productsContainer.innerHTML = data.data.html;
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
        })
        .finally(() => {
            if (productsContainer) {
                productsContainer.classList.remove('loading');
            }
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new AquaLuxeWooCommerce();
});