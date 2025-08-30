/**
 * Quick View Module
 * 
 * Handles the quick view functionality for WooCommerce products.
 */

const QuickView = {
    /**
     * Initialize the quick view functionality
     */
    init() {
        this.cacheDOM();
        this.createModal();
        this.bindEvents();
    },

    /**
     * Cache DOM elements
     */
    cacheDOM() {
        this.body = document.body;
        this.quickViewButtons = document.querySelectorAll('.quick-view-button');
    },

    /**
     * Create the quick view modal
     */
    createModal() {
        // Create modal container if it doesn't exist
        if (!document.querySelector('.quick-view-modal')) {
            const modal = document.createElement('div');
            modal.className = 'quick-view-modal';
            modal.innerHTML = `
                <div class="quick-view-container">
                    <div class="quick-view-content">
                        <div class="quick-view-loader">
                            <div class="spinner"></div>
                        </div>
                    </div>
                    <button class="quick-view-close" aria-label="Close quick view">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
                    </button>
                </div>
            `;
            this.body.appendChild(modal);
            this.modal = modal;
            this.modalContainer = modal.querySelector('.quick-view-container');
            this.modalContent = modal.querySelector('.quick-view-content');
            this.closeButton = modal.querySelector('.quick-view-close');
            
            // Add event listener to close button
            this.closeButton.addEventListener('click', () => this.closeModal());
            
            // Close modal when clicking outside
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.closeModal();
                }
            });
            
            // Close modal on ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                    this.closeModal();
                }
            });
        } else {
            this.modal = document.querySelector('.quick-view-modal');
            this.modalContainer = this.modal.querySelector('.quick-view-container');
            this.modalContent = this.modal.querySelector('.quick-view-content');
            this.closeButton = this.modal.querySelector('.quick-view-close');
        }
    },

    /**
     * Bind events
     */
    bindEvents() {
        // Add click event to quick view buttons
        this.quickViewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = button.dataset.productId;
                if (productId) {
                    this.openQuickView(productId);
                }
            });
        });

        // Re-initialize when new products are loaded via AJAX
        document.addEventListener('aqualuxe:products-loaded', () => {
            this.quickViewButtons = document.querySelectorAll('.quick-view-button');
            this.bindEvents();
        });
    },

    /**
     * Open quick view modal
     * 
     * @param {string} productId - Product ID
     */
    openQuickView(productId) {
        // Show modal with loading state
        this.modal.classList.add('active');
        this.body.classList.add('quick-view-active');
        this.modalContent.innerHTML = `
            <div class="quick-view-loader">
                <div class="spinner"></div>
            </div>
        `;

        // Fetch product data
        this.fetchProductData(productId)
            .then(response => {
                if (response.success) {
                    this.modalContent.innerHTML = response.data;
                    this.initializeProductFunctionality();
                } else {
                    this.showError('Error loading product data.');
                }
            })
            .catch(error => {
                console.error('Quick View Error:', error);
                this.showError('Error loading product data.');
            });
    },

    /**
     * Fetch product data via AJAX
     * 
     * @param {string} productId - Product ID
     * @returns {Promise} - Promise with response data
     */
    fetchProductData(productId) {
        // Create form data
        const formData = new FormData();
        formData.append('action', 'aqualuxe_quick_view');
        formData.append('product_id', productId);
        formData.append('security', aqualuxeData.nonce);

        // Send AJAX request
        return fetch(aqualuxeData.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => response.json());
    },

    /**
     * Initialize product functionality after loading
     */
    initializeProductFunctionality() {
        // Initialize quantity buttons
        this.initQuantityButtons();

        // Initialize product gallery
        this.initProductGallery();

        // Initialize add to cart functionality
        this.initAddToCart();

        // Initialize variations if available
        if (typeof jQuery !== 'undefined' && jQuery.fn.wc_variation_form) {
            jQuery('.variations_form').wc_variation_form();
        }
    },

    /**
     * Initialize quantity buttons
     */
    initQuantityButtons() {
        const quantityInputs = this.modalContent.querySelectorAll('.quantity input[type="number"]');
        
        quantityInputs.forEach(input => {
            // Create wrapper if it doesn't exist
            if (!input.parentNode.classList.contains('quantity-buttons')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'quantity-buttons';
                
                // Create decrease button
                const decreaseButton = document.createElement('button');
                decreaseButton.type = 'button';
                decreaseButton.className = 'quantity-button quantity-down';
                decreaseButton.textContent = '-';
                decreaseButton.addEventListener('click', () => {
                    const currentValue = parseInt(input.value, 10);
                    const min = parseInt(input.getAttribute('min'), 10) || 1;
                    
                    if (currentValue > min) {
                        input.value = currentValue - 1;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
                
                // Create increase button
                const increaseButton = document.createElement('button');
                increaseButton.type = 'button';
                increaseButton.className = 'quantity-button quantity-up';
                increaseButton.textContent = '+';
                increaseButton.addEventListener('click', () => {
                    const currentValue = parseInt(input.value, 10);
                    const max = parseInt(input.getAttribute('max'), 10) || 999;
                    
                    if (!max || currentValue < max) {
                        input.value = currentValue + 1;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
                
                // Insert buttons
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(decreaseButton);
                wrapper.appendChild(input);
                wrapper.appendChild(increaseButton);
            }
        });
    },

    /**
     * Initialize product gallery
     */
    initProductGallery() {
        const gallery = this.modalContent.querySelector('.woocommerce-product-gallery');
        
        if (gallery) {
            const mainImage = gallery.querySelector('.woocommerce-product-gallery__image');
            const thumbnails = gallery.querySelectorAll('.woocommerce-product-gallery__thumbs img');
            
            // Add click event to thumbnails
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', () => {
                    const fullSrc = thumb.dataset.large;
                    const fullSrcset = thumb.dataset.srcset;
                    const fullSizes = thumb.dataset.sizes;
                    
                    const mainImg = mainImage.querySelector('img');
                    mainImg.src = fullSrc;
                    
                    if (fullSrcset) {
                        mainImg.srcset = fullSrcset;
                    }
                    
                    if (fullSizes) {
                        mainImg.sizes = fullSizes;
                    }
                    
                    // Update active thumbnail
                    thumbnails.forEach(t => t.classList.remove('active'));
                    thumb.classList.add('active');
                });
            });
            
            // Add zoom effect on hover
            if (window.innerWidth > 768) {
                const zoomImage = mainImage.querySelector('img');
                
                if (zoomImage && zoomImage.dataset.large) {
                    mainImage.addEventListener('mouseenter', () => {
                        mainImage.style.backgroundImage = `url(${zoomImage.dataset.large})`;
                        mainImage.classList.add('zoom-active');
                    });
                    
                    mainImage.addEventListener('mousemove', event => {
                        if (mainImage.classList.contains('zoom-active')) {
                            const { left, top, width, height } = mainImage.getBoundingClientRect();
                            const x = (event.clientX - left) / width * 100;
                            const y = (event.clientY - top) / height * 100;
                            
                            mainImage.style.backgroundPosition = `${x}% ${y}%`;
                        }
                    });
                    
                    mainImage.addEventListener('mouseleave', () => {
                        mainImage.classList.remove('zoom-active');
                    });
                }
            }
        }
    },

    /**
     * Initialize add to cart functionality
     */
    initAddToCart() {
        const addToCartForm = this.modalContent.querySelector('form.cart');
        
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', e => {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(addToCartForm);
                formData.append('action', 'aqualuxe_ajax_add_to_cart');
                
                // Add loading state
                const submitButton = addToCartForm.querySelector('button[type="submit"]');
                submitButton.classList.add('loading');
                
                // Send AJAX request
                fetch(aqualuxeData.ajaxUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    submitButton.classList.remove('loading');
                    
                    if (data.success) {
                        // Update cart fragments
                        if (data.fragments) {
                            this.updateCartFragments(data.fragments);
                        }
                        
                        // Show success message
                        this.showSuccessMessage();
                        
                        // Close modal after a delay
                        setTimeout(() => {
                            this.closeModal();
                        }, 2000);
                    } else {
                        // Show error message
                        this.showError(data.message || 'Error adding to cart');
                    }
                })
                .catch(error => {
                    console.error('AJAX Add to Cart Error:', error);
                    submitButton.classList.remove('loading');
                    this.showError('Error adding to cart');
                });
            });
        }
    },

    /**
     * Update cart fragments
     * 
     * @param {Object} fragments - Cart fragments
     */
    updateCartFragments(fragments) {
        if (!fragments) return;
        
        // Update each fragment
        Object.keys(fragments).forEach(key => {
            const element = document.querySelector(key);
            if (element) {
                element.outerHTML = fragments[key];
            }
        });
        
        // Trigger event
        document.dispatchEvent(new CustomEvent('aqualuxe:cart-updated'));
    },

    /**
     * Show success message
     */
    showSuccessMessage() {
        const successMessage = document.createElement('div');
        successMessage.className = 'quick-view-message quick-view-message--success';
        successMessage.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z"/></svg>
            <span>Product added to cart!</span>
        `;
        
        this.modalContent.appendChild(successMessage);
        
        // Remove message after delay
        setTimeout(() => {
            successMessage.remove();
        }, 3000);
    },

    /**
     * Show error message
     * 
     * @param {string} message - Error message
     */
    showError(message) {
        this.modalContent.innerHTML = `
            <div class="quick-view-error">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm0-8v6h2V7h-2z"/></svg>
                <p>${message}</p>
                <button class="btn btn-primary btn-sm quick-view-close-error">Close</button>
            </div>
        `;
        
        const closeErrorButton = this.modalContent.querySelector('.quick-view-close-error');
        if (closeErrorButton) {
            closeErrorButton.addEventListener('click', () => this.closeModal());
        }
    },

    /**
     * Close quick view modal
     */
    closeModal() {
        this.modal.classList.remove('active');
        this.body.classList.remove('quick-view-active');
        
        // Clear content after animation
        setTimeout(() => {
            this.modalContent.innerHTML = '';
        }, 300);
    }
};

export default QuickView;