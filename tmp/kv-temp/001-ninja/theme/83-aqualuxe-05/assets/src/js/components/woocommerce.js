/**
 * WooCommerce Integration Module
 * 
 * Comprehensive e-commerce functionality including cart management,
 * checkout enhancements, product interactions, and AJAX operations.
 */

class WooCommerce {
    constructor() {
        this.cart = null;
        this.isInitialized = false;
        this.ajaxUrl = window.ajaxurl || '/wp-admin/admin-ajax.php';
        this.nonce = window.aqualuxe_nonce || '';
        
        this.init();
    }

    async init() {
        try {
            await this.initCart();
            this.initProductInteractions();
            this.initCheckoutEnhancements();
            this.initAccountFeatures();
            this.initWishlist();
            this.initCompareFeatures();
            this.initQuickView();
            this.initAjaxFilters();
            
            this.isInitialized = true;
            console.log('WooCommerce module initialized successfully');
            
        } catch (error) {
            console.error('WooCommerce initialization failed:', error);
        }
    }

    // Cart Management
    async initCart() {
        this.cart = new Cart();
        await this.cart.init();
        
        this.bindCartEvents();
    }

    bindCartEvents() {
        // Add to cart buttons
        document.addEventListener('click', async (e) => {
            if (e.target.matches('.add_to_cart_button:not(.product_type_variable)')) {
                e.preventDefault();
                await this.handleAddToCart(e.target);
            }
        });

        // Remove from cart
        document.addEventListener('click', async (e) => {
            if (e.target.matches('.remove_from_cart_button')) {
                e.preventDefault();
                await this.handleRemoveFromCart(e.target);
            }
        });

        // Update cart quantities
        document.addEventListener('change', async (e) => {
            if (e.target.matches('.cart-quantity input[type="number"]')) {
                await this.handleUpdateQuantity(e.target);
            }
        });

        // Mini cart toggle
        document.addEventListener('click', (e) => {
            if (e.target.matches('.cart-toggle')) {
                e.preventDefault();
                this.toggleMiniCart();
            }
        });
    }

    async handleAddToCart(button) {
        const productId = button.dataset.product_id;
        const quantity = button.dataset.quantity || 1;
        const variation = button.dataset.variation_id || '';
        
        try {
            // Show loading state
            button.classList.add('loading');
            button.disabled = true;
            
            const response = await this.ajaxRequest('add_to_cart', {
                product_id: productId,
                quantity: quantity,
                variation_id: variation
            });
            
            if (response.success) {
                // Update cart display
                await this.cart.refresh();
                
                // Show success notification
                this.showNotification('Product added to cart!', 'success');
                
                // Update button state
                button.classList.remove('loading');
                button.classList.add('added');
                button.textContent = 'Added to Cart';
                
                // Trigger cart drawer if enabled
                if (this.shouldShowCartDrawer()) {
                    this.showMiniCart();
                }
                
            } else {
                throw new Error(response.data || 'Failed to add product to cart');
            }
            
        } catch (error) {
            console.error('Add to cart error:', error);
            this.showNotification(error.message, 'error');
            
        } finally {
            button.classList.remove('loading');
            button.disabled = false;
        }
    }

    async handleRemoveFromCart(button) {
        const cartItemKey = button.dataset.cart_item_key;
        
        try {
            button.classList.add('loading');
            
            const response = await this.ajaxRequest('remove_from_cart', {
                cart_item_key: cartItemKey
            });
            
            if (response.success) {
                // Remove item from DOM with animation
                const cartItem = button.closest('.cart-item');
                if (cartItem) {
                    cartItem.style.opacity = '0';
                    cartItem.style.transform = 'translateX(-100%)';
                    setTimeout(() => cartItem.remove(), 300);
                }
                
                // Update cart totals
                await this.cart.refresh();
                
                this.showNotification('Item removed from cart', 'info');
                
            } else {
                throw new Error(response.data || 'Failed to remove item');
            }
            
        } catch (error) {
            console.error('Remove from cart error:', error);
            this.showNotification(error.message, 'error');
            
        } finally {
            button.classList.remove('loading');
        }
    }

    async handleUpdateQuantity(input) {
        const cartItemKey = input.dataset.cart_item_key;
        const quantity = parseInt(input.value);
        
        if (quantity < 0) return;
        
        try {
            const response = await this.ajaxRequest('update_cart_quantity', {
                cart_item_key: cartItemKey,
                quantity: quantity
            });
            
            if (response.success) {
                await this.cart.refresh();
                
                if (quantity === 0) {
                    this.showNotification('Item removed from cart', 'info');
                } else {
                    this.showNotification('Cart updated', 'success');
                }
                
            } else {
                throw new Error(response.data || 'Failed to update quantity');
            }
            
        } catch (error) {
            console.error('Update quantity error:', error);
            this.showNotification(error.message, 'error');
            // Revert input value
            input.value = input.dataset.original_quantity || 1;
        }
    }

    // Product Interactions
    initProductInteractions() {
        this.initVariationHandling();
        this.initProductGallery();
        this.initProductTabs();
        this.initRelatedProducts();
    }

    initVariationHandling() {
        document.addEventListener('change', (e) => {
            if (e.target.matches('.variations select')) {
                this.handleVariationChange(e.target);
            }
        });
    }

    handleVariationChange(select) {
        const form = select.closest('.variations_form');
        if (!form) return;
        
        const selectedOptions = {};
        const selects = form.querySelectorAll('.variations select');
        
        selects.forEach(s => {
            if (s.value) {
                selectedOptions[s.name] = s.value;
            }
        });
        
        // Find matching variation
        const variations = JSON.parse(form.dataset.product_variations || '[]');
        const matchingVariation = this.findMatchingVariation(variations, selectedOptions);
        
        if (matchingVariation) {
            this.updateVariationDisplay(form, matchingVariation);
        } else {
            this.clearVariationDisplay(form);
        }
    }

    findMatchingVariation(variations, selectedOptions) {
        return variations.find(variation => {
            return Object.keys(selectedOptions).every(key => {
                return variation.attributes[key] === selectedOptions[key] || 
                       variation.attributes[key] === '';
            });
        });
    }

    updateVariationDisplay(form, variation) {
        // Update price
        const priceEl = form.querySelector('.price');
        if (priceEl && variation.display_price) {
            priceEl.innerHTML = variation.price_html;
        }
        
        // Update availability
        const availabilityEl = form.querySelector('.stock');
        if (availabilityEl) {
            availabilityEl.textContent = variation.availability_html;
            availabilityEl.className = `stock ${variation.is_in_stock ? 'in-stock' : 'out-of-stock'}`;
        }
        
        // Update add to cart button
        const addToCartBtn = form.querySelector('.single_add_to_cart_button');
        if (addToCartBtn) {
            addToCartBtn.disabled = !variation.is_purchasable;
            addToCartBtn.dataset.variation_id = variation.variation_id;
        }
        
        // Update product image
        if (variation.image && variation.image.src) {
            const mainImage = document.querySelector('.product-main-image img');
            if (mainImage) {
                mainImage.src = variation.image.src;
                mainImage.srcset = variation.image.srcset || '';
            }
        }
    }

    clearVariationDisplay(form) {
        const addToCartBtn = form.querySelector('.single_add_to_cart_button');
        if (addToCartBtn) {
            addToCartBtn.disabled = true;
            delete addToCartBtn.dataset.variation_id;
        }
    }

    initProductGallery() {
        // Enhanced product gallery with zoom and lightbox
        const galleries = document.querySelectorAll('.product-gallery');
        
        galleries.forEach(gallery => {
            const mainImage = gallery.querySelector('.main-image');
            const thumbnails = gallery.querySelectorAll('.thumbnail');
            
            // Thumbnail click handlers
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.switchGalleryImage(mainImage, thumb);
                });
            });
            
            // Zoom functionality
            if (mainImage) {
                this.initImageZoom(mainImage);
            }
            
            // Lightbox functionality
            this.initLightbox(gallery);
        });
    }

    switchGalleryImage(mainImage, thumbnail) {
        const newSrc = thumbnail.dataset.large_image || thumbnail.href;
        const newSrcset = thumbnail.dataset.large_image_srcset || '';
        
        if (newSrc) {
            mainImage.src = newSrc;
            mainImage.srcset = newSrcset;
            
            // Update active thumbnail
            thumbnail.closest('.thumbnails').querySelectorAll('.thumbnail').forEach(t => {
                t.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }
    }

    initImageZoom(image) {
        let isZoomed = false;
        
        image.addEventListener('click', () => {
            if (!isZoomed) {
                image.style.transform = 'scale(2)';
                image.style.cursor = 'zoom-out';
                isZoomed = true;
            } else {
                image.style.transform = 'scale(1)';
                image.style.cursor = 'zoom-in';
                isZoomed = false;
            }
        });
        
        image.addEventListener('mouseleave', () => {
            if (isZoomed) {
                image.style.transform = 'scale(1)';
                image.style.cursor = 'zoom-in';
                isZoomed = false;
            }
        });
    }

    // Checkout Enhancements
    initCheckoutEnhancements() {
        if (!document.body.classList.contains('woocommerce-checkout')) return;
        
        this.initCheckoutValidation();
        this.initAddressAutocomplete();
        this.initPaymentMethods();
        this.initOrderReview();
    }

    initCheckoutValidation() {
        const form = document.querySelector('form.checkout');
        if (!form) return;
        
        form.addEventListener('submit', (e) => {
            if (!this.validateCheckoutForm(form)) {
                e.preventDefault();
            }
        });
        
        // Real-time validation
        const requiredFields = form.querySelectorAll('input[required], select[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', () => {
                this.validateField(field);
            });
        });
    }

    validateCheckoutForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('input[required], select[required]');
        
        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        const isValid = value !== '';
        
        const wrapper = field.closest('.form-row');
        if (wrapper) {
            wrapper.classList.toggle('has-error', !isValid);
            
            let errorMsg = wrapper.querySelector('.field-error');
            if (!isValid) {
                if (!errorMsg) {
                    errorMsg = document.createElement('span');
                    errorMsg.className = 'field-error';
                    wrapper.appendChild(errorMsg);
                }
                errorMsg.textContent = 'This field is required';
            } else {
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        }
        
        return isValid;
    }

    // Wishlist functionality
    initWishlist() {
        document.addEventListener('click', async (e) => {
            if (e.target.matches('.add-to-wishlist')) {
                e.preventDefault();
                await this.handleWishlistToggle(e.target);
            }
        });
    }

    async handleWishlistToggle(button) {
        const productId = button.dataset.product_id;
        const action = button.classList.contains('in-wishlist') ? 'remove' : 'add';
        
        try {
            button.classList.add('loading');
            
            const response = await this.ajaxRequest('toggle_wishlist', {
                product_id: productId,
                action: action
            });
            
            if (response.success) {
                button.classList.toggle('in-wishlist');
                const text = button.classList.contains('in-wishlist') ? 'Remove from Wishlist' : 'Add to Wishlist';
                button.textContent = text;
                
                this.showNotification(response.data.message, 'success');
                
            } else {
                throw new Error(response.data || 'Wishlist operation failed');
            }
            
        } catch (error) {
            console.error('Wishlist error:', error);
            this.showNotification(error.message, 'error');
            
        } finally {
            button.classList.remove('loading');
        }
    }

    // Product comparison
    initCompareFeatures() {
        document.addEventListener('click', async (e) => {
            if (e.target.matches('.add-to-compare')) {
                e.preventDefault();
                await this.handleCompareToggle(e.target);
            }
        });
    }

    async handleCompareToggle(button) {
        const productId = button.dataset.product_id;
        
        try {
            button.classList.add('loading');
            
            const response = await this.ajaxRequest('toggle_compare', {
                product_id: productId
            });
            
            if (response.success) {
                button.classList.toggle('in-compare');
                this.updateCompareCounter(response.data.count);
                this.showNotification(response.data.message, 'success');
                
            } else {
                throw new Error(response.data || 'Compare operation failed');
            }
            
        } catch (error) {
            console.error('Compare error:', error);
            this.showNotification(error.message, 'error');
            
        } finally {
            button.classList.remove('loading');
        }
    }

    updateCompareCounter(count) {
        const counter = document.querySelector('.compare-counter');
        if (counter) {
            counter.textContent = count;
            counter.style.display = count > 0 ? 'inline' : 'none';
        }
    }

    // Quick view functionality
    initQuickView() {
        document.addEventListener('click', async (e) => {
            if (e.target.matches('.quick-view-button')) {
                e.preventDefault();
                await this.showQuickView(e.target.dataset.product_id);
            }
        });
    }

    async showQuickView(productId) {
        try {
            const response = await this.ajaxRequest('get_quick_view', {
                product_id: productId
            });
            
            if (response.success) {
                this.openModal(response.data.html, 'quick-view-modal');
            } else {
                throw new Error(response.data || 'Failed to load quick view');
            }
            
        } catch (error) {
            console.error('Quick view error:', error);
            this.showNotification(error.message, 'error');
        }
    }

    // AJAX product filters
    initAjaxFilters() {
        const filterForms = document.querySelectorAll('.woocommerce-filter-form');
        
        filterForms.forEach(form => {
            form.addEventListener('change', () => {
                this.applyFilters(form);
            });
        });
    }

    async applyFilters(form) {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        try {
            const resultsContainer = document.querySelector('.products-container');
            if (resultsContainer) {
                resultsContainer.classList.add('loading');
            }
            
            const response = await fetch(`${window.location.pathname}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const newProducts = doc.querySelector('.products-container');
                if (newProducts && resultsContainer) {
                    resultsContainer.innerHTML = newProducts.innerHTML;
                }
                
                // Update URL without reload
                history.pushState(null, '', `${window.location.pathname}?${params}`);
                
            } else {
                throw new Error('Filter request failed');
            }
            
        } catch (error) {
            console.error('Filter error:', error);
            this.showNotification('Failed to apply filters', 'error');
            
        } finally {
            const resultsContainer = document.querySelector('.products-container');
            if (resultsContainer) {
                resultsContainer.classList.remove('loading');
            }
        }
    }

    // Mini cart functionality
    toggleMiniCart() {
        const miniCart = document.querySelector('.mini-cart');
        if (miniCart) {
            miniCart.classList.toggle('open');
        }
    }

    showMiniCart() {
        const miniCart = document.querySelector('.mini-cart');
        if (miniCart) {
            miniCart.classList.add('open');
        }
    }

    hideMiniCart() {
        const miniCart = document.querySelector('.mini-cart');
        if (miniCart) {
            miniCart.classList.remove('open');
        }
    }

    shouldShowCartDrawer() {
        return window.aqualuxe_settings?.show_cart_drawer !== false;
    }

    // Utility methods
    async ajaxRequest(action, data = {}) {
        const formData = new FormData();
        formData.append('action', `aqualuxe_${action}`);
        formData.append('nonce', this.nonce);
        
        Object.keys(data).forEach(key => {
            formData.append(key, data[key]);
        });
        
        const response = await fetch(this.ajaxUrl, {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <span class="message">${message}</span>
            <button class="close">&times;</button>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
        
        // Close button handler
        notification.querySelector('.close').addEventListener('click', () => {
            notification.remove();
        });
        
        // Trigger entrance animation
        requestAnimationFrame(() => {
            notification.classList.add('show');
        });
    }

    openModal(content, className = '') {
        const modal = document.createElement('div');
        modal.className = `modal ${className}`;
        modal.innerHTML = `
            <div class="modal-backdrop">
                <div class="modal-content">
                    <button class="modal-close">&times;</button>
                    <div class="modal-body">
                        ${content}
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close handlers
        modal.querySelector('.modal-close').addEventListener('click', () => {
            modal.remove();
        });
        
        modal.querySelector('.modal-backdrop').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                modal.remove();
            }
        });
        
        // Escape key handler
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                modal.remove();
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);
        
        // Show modal
        requestAnimationFrame(() => {
            modal.classList.add('show');
        });
    }
}

// Cart class for managing cart state
class Cart {
    constructor() {
        this.items = [];
        this.total = 0;
        this.count = 0;
    }

    async init() {
        await this.refresh();
        this.bindEvents();
    }

    async refresh() {
        try {
            const response = await fetch(`${window.location.origin}/wp-json/wc/store/cart`);
            if (response.ok) {
                const cartData = await response.json();
                this.updateFromData(cartData);
            }
        } catch (error) {
            console.error('Cart refresh error:', error);
        }
    }

    updateFromData(data) {
        this.items = data.items || [];
        this.total = data.totals?.total_price || 0;
        this.count = data.items_count || 0;
        
        this.updateDisplay();
    }

    updateDisplay() {
        // Update cart count in header
        const countElements = document.querySelectorAll('.cart-count');
        countElements.forEach(el => {
            el.textContent = this.count;
        });
        
        // Update cart total
        const totalElements = document.querySelectorAll('.cart-total');
        totalElements.forEach(el => {
            el.textContent = this.formatPrice(this.total);
        });
        
        // Update mini cart
        this.updateMiniCart();
    }

    updateMiniCart() {
        const miniCart = document.querySelector('.mini-cart .cart-items');
        if (!miniCart) return;
        
        if (this.items.length === 0) {
            miniCart.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
            return;
        }
        
        miniCart.innerHTML = this.items.map(item => `
            <div class="cart-item" data-key="${item.key}">
                <div class="item-image">
                    <img src="${item.images?.[0]?.src || ''}" alt="${item.name}">
                </div>
                <div class="item-details">
                    <h4>${item.name}</h4>
                    <div class="item-price">${this.formatPrice(item.prices.price)}</div>
                    <div class="item-quantity">
                        <input type="number" value="${item.quantity}" min="0" 
                               data-cart_item_key="${item.key}" class="cart-quantity">
                    </div>
                </div>
                <button class="remove-item" data-cart_item_key="${item.key}">&times;</button>
            </div>
        `).join('');
    }

    formatPrice(price) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(price / 100);
    }

    bindEvents() {
        // Cart updated event from WooCommerce
        document.body.addEventListener('wc_fragments_refreshed', () => {
            this.refresh();
        });
    }
}

export default WooCommerce;
