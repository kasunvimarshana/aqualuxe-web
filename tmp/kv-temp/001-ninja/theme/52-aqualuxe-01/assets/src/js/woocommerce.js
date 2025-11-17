/**
 * AquaLuxe Theme WooCommerce JavaScript
 * 
 * Contains all WooCommerce-specific functionality
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all WooCommerce components
    AquaLuxeWooCommerce.init();
});

// WooCommerce functionality
const AquaLuxeWooCommerce = {
    // Initialize all components
    init: function() {
        this.setupAjaxAddToCart();
        this.setupMiniCart();
        this.setupQuantityButtons();
        this.setupProductGallery();
        this.setupQuickView();
        this.setupVariationSwatches();
        this.setupFilterAccordions();
    },

    // Ajax Add to Cart
    setupAjaxAddToCart: function() {
        // Only run on single product pages
        if (!document.body.classList.contains('single-product')) return;
        
        const addToCartForm = document.querySelector('form.cart');
        if (!addToCartForm) return;
        
        addToCartForm.addEventListener('submit', function(e) {
            // Only proceed if AJAX add to cart is enabled
            if (!aqualuxeVars || !aqualuxeVars.ajax_add_to_cart) return;
            
            e.preventDefault();
            
            const addToCartButton = this.querySelector('button[type="submit"]');
            const formData = new FormData(this);
            
            // Add required action
            formData.append('action', 'aqualuxe_ajax_add_to_cart');
            formData.append('security', aqualuxeVars.nonce);
            
            // Show loading state
            if (addToCartButton) {
                addToCartButton.classList.add('loading');
                addToCartButton.disabled = true;
            }
            
            // Send AJAX request
            fetch(aqualuxeVars.ajaxurl, {
                method: 'POST',
                credentials: 'same-origin',
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    // Show success message
                    this.insertAdjacentHTML('afterend', `
                        <div class="woocommerce-message" role="alert">
                            ${response.data.message}
                            <a href="${response.data.cart_url}" class="button wc-forward">${aqualuxeVars.view_cart_text}</a>
                        </div>
                    `);
                    
                    // Update mini cart
                    if (response.data.fragments) {
                        this.updateCartFragments(response.data.fragments);
                    }
                    
                    // Trigger event for other scripts
                    document.dispatchEvent(new CustomEvent('aqualuxeAddedToCart', { 
                        detail: response.data 
                    }));
                } else {
                    // Show error message
                    this.insertAdjacentHTML('afterend', `
                        <div class="woocommerce-error" role="alert">
                            ${response.data.message}
                        </div>
                    `);
                }
                
                // Reset button state
                if (addToCartButton) {
                    addToCartButton.classList.remove('loading');
                    addToCartButton.disabled = false;
                }
                
                // Remove messages after delay
                setTimeout(() => {
                    const messages = document.querySelectorAll('.woocommerce-message, .woocommerce-error');
                    messages.forEach(message => {
                        message.remove();
                    });
                }, 5000);
            })
            .catch(error => {
                console.error('Add to cart error:', error);
                
                // Reset button state
                if (addToCartButton) {
                    addToCartButton.classList.remove('loading');
                    addToCartButton.disabled = false;
                }
            });
        });
    },

    // Update cart fragments
    updateCartFragments: function(fragments) {
        if (!fragments) return;
        
        Object.keys(fragments).forEach(selector => {
            const fragment = fragments[selector];
            const elements = document.querySelectorAll(selector);
            
            elements.forEach(element => {
                element.outerHTML = fragment;
            });
        });
    },

    // Mini Cart
    setupMiniCart: function() {
        const cartToggle = document.querySelector('.cart-toggle');
        const miniCart = document.querySelector('.mini-cart');
        const closeCart = document.querySelector('.close-mini-cart');
        const cartOverlay = document.querySelector('.cart-overlay');
        
        if (!cartToggle || !miniCart) return;
        
        cartToggle.addEventListener('click', function(e) {
            e.preventDefault();
            miniCart.classList.add('active');
            document.body.classList.add('mini-cart-open');
            if (cartOverlay) cartOverlay.classList.add('active');
        });
        
        if (closeCart) {
            closeCart.addEventListener('click', function(e) {
                e.preventDefault();
                miniCart.classList.remove('active');
                document.body.classList.remove('mini-cart-open');
                if (cartOverlay) cartOverlay.classList.remove('active');
            });
        }
        
        if (cartOverlay) {
            cartOverlay.addEventListener('click', function() {
                miniCart.classList.remove('active');
                document.body.classList.remove('mini-cart-open');
                cartOverlay.classList.remove('active');
            });
        }
        
        // Remove item from cart via AJAX
        const removeButtons = document.querySelectorAll('.mini-cart .remove_from_cart_button');
        
        removeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const cartItem = this.closest('.mini-cart-item');
                if (cartItem) {
                    cartItem.classList.add('removing');
                }
            });
        });
        
        // Listen for fragment refresh events
        document.addEventListener('wc_fragments_refreshed', function() {
            AquaLuxeWooCommerce.setupMiniCart();
        });
    },

    // Quantity Buttons
    setupQuantityButtons: function() {
        const quantities = document.querySelectorAll('.quantity:not(.buttons-added)');
        
        quantities.forEach(function(quantity) {
            const input = quantity.querySelector('input.qty');
            if (!input) return;
            
            // Mark as processed
            quantity.classList.add('buttons-added');
            
            // Create minus button
            const minusBtn = document.createElement('button');
            minusBtn.type = 'button';
            minusBtn.className = 'quantity-button minus';
            minusBtn.setAttribute('aria-label', 'Decrease quantity');
            minusBtn.innerHTML = '−';
            
            // Create plus button
            const plusBtn = document.createElement('button');
            plusBtn.type = 'button';
            plusBtn.className = 'quantity-button plus';
            plusBtn.setAttribute('aria-label', 'Increase quantity');
            plusBtn.innerHTML = '+';
            
            // Add buttons to DOM
            input.insertAdjacentElement('beforebegin', minusBtn);
            input.insertAdjacentElement('afterend', plusBtn);
            
            // Add event listeners
            minusBtn.addEventListener('click', function() {
                const currentValue = parseInt(input.value);
                const minValue = parseInt(input.getAttribute('min')) || 1;
                
                if (currentValue > minValue) {
                    input.value = currentValue - 1;
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });
            
            plusBtn.addEventListener('click', function() {
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.getAttribute('max')) || 999;
                
                if (!maxValue || currentValue < maxValue) {
                    input.value = currentValue + 1;
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });
        });
    },

    // Product Gallery
    setupProductGallery: function() {
        // Check if we're on a product page
        const productGallery = document.querySelector('.woocommerce-product-gallery');
        if (!productGallery) return;
        
        // Check if lightbox is enabled
        const lightboxEnabled = productGallery.classList.contains('lightbox-enabled');
        
        // Setup lightbox if enabled
        if (lightboxEnabled) {
            const galleryImages = productGallery.querySelectorAll('.woocommerce-product-gallery__image > a');
            
            galleryImages.forEach(image => {
                image.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const fullImage = this.getAttribute('href');
                    const caption = this.getAttribute('data-caption') || '';
                    
                    // Create lightbox
                    const lightbox = document.createElement('div');
                    lightbox.className = 'product-lightbox';
                    lightbox.innerHTML = `
                        <div class="lightbox-container">
                            <button class="lightbox-close" aria-label="Close">×</button>
                            <img src="${fullImage}" alt="" class="lightbox-image">
                            ${caption ? `<div class="lightbox-caption">${caption}</div>` : ''}
                        </div>
                    `;
                    
                    document.body.appendChild(lightbox);
                    document.body.classList.add('lightbox-open');
                    
                    // Add active class after a small delay to trigger animation
                    setTimeout(() => {
                        lightbox.classList.add('active');
                    }, 10);
                    
                    // Setup close button
                    const closeBtn = lightbox.querySelector('.lightbox-close');
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function() {
                            lightbox.classList.remove('active');
                            document.body.classList.remove('lightbox-open');
                            
                            // Remove lightbox after animation
                            setTimeout(() => {
                                document.body.removeChild(lightbox);
                            }, 300);
                        });
                    }
                    
                    // Close on click outside
                    lightbox.addEventListener('click', function(e) {
                        if (e.target === lightbox) {
                            lightbox.classList.remove('active');
                            document.body.classList.remove('lightbox-open');
                            
                            // Remove lightbox after animation
                            setTimeout(() => {
                                document.body.removeChild(lightbox);
                            }, 300);
                        }
                    });
                });
            });
        }
        
        // Check if zoom is enabled
        const zoomEnabled = productGallery.classList.contains('zoom-enabled');
        
        // Setup zoom if enabled
        if (zoomEnabled) {
            const galleryImages = productGallery.querySelectorAll('.woocommerce-product-gallery__image');
            
            galleryImages.forEach(image => {
                const img = image.querySelector('img');
                if (!img) return;
                
                const fullImage = image.getAttribute('data-large_image');
                if (!fullImage) return;
                
                // Add zoom container
                image.classList.add('zoom-enabled');
                
                // Handle mouse events
                image.addEventListener('mouseenter', function() {
                    // Create zoom image if it doesn't exist
                    if (!this.querySelector('.zoom-image')) {
                        const zoomImg = document.createElement('div');
                        zoomImg.className = 'zoom-image';
                        zoomImg.style.backgroundImage = `url(${fullImage})`;
                        this.appendChild(zoomImg);
                    }
                });
                
                image.addEventListener('mousemove', function(e) {
                    const zoomImg = this.querySelector('.zoom-image');
                    if (!zoomImg) return;
                    
                    // Calculate zoom position
                    const rect = this.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width * 100;
                    const y = (e.clientY - rect.top) / rect.height * 100;
                    
                    zoomImg.style.backgroundPosition = `${x}% ${y}%`;
                });
                
                image.addEventListener('mouseleave', function() {
                    const zoomImg = this.querySelector('.zoom-image');
                    if (zoomImg) {
                        this.removeChild(zoomImg);
                    }
                });
            });
        }
    },

    // Quick View
    setupQuickView: function() {
        const quickViewButtons = document.querySelectorAll('.quick-view-btn');
        if (!quickViewButtons.length) return;
        
        quickViewButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');
                
                if (!productId) return;
                
                // Show loading overlay
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'loading-overlay';
                loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
                document.body.appendChild(loadingOverlay);
                
                // Fetch product data via AJAX
                const data = new FormData();
                data.append('action', 'aqualuxe_quick_view');
                data.append('product_id', productId);
                data.append('security', aqualuxeVars.nonce);
                
                fetch(aqualuxeVars.ajaxurl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: data
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        // Create modal
                        const modal = document.createElement('div');
                        modal.className = 'quick-view-modal';
                        modal.innerHTML = `
                            <div class="quick-view-container">
                                <div class="quick-view-content">
                                    <button class="quick-view-close" aria-label="Close">×</button>
                                    <div class="quick-view-inner">
                                        ${response.data.html}
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        document.body.appendChild(modal);
                        
                        // Add active class after a small delay to trigger animation
                        setTimeout(() => {
                            modal.classList.add('active');
                        }, 10);
                        
                        // Setup close button
                        const closeBtn = modal.querySelector('.quick-view-close');
                        if (closeBtn) {
                            closeBtn.addEventListener('click', function() {
                                modal.classList.remove('active');
                                
                                // Remove modal after animation
                                setTimeout(() => {
                                    document.body.removeChild(modal);
                                }, 300);
                            });
                        }
                        
                        // Close on click outside
                        modal.addEventListener('click', function(e) {
                            if (e.target === modal) {
                                modal.classList.remove('active');
                                
                                // Remove modal after animation
                                setTimeout(() => {
                                    document.body.removeChild(modal);
                                }, 300);
                            }
                        });
                        
                        // Initialize quantity buttons in quick view
                        AquaLuxeWooCommerce.setupQuantityButtons();
                        
                        // Initialize variation form if exists
                        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                            const variationForms = modal.querySelectorAll('.variations_form');
                            variationForms.forEach(form => {
                                jQuery(form).wc_variation_form();
                            });
                        }
                    }
                    
                    // Remove loading overlay
                    document.body.removeChild(loadingOverlay);
                })
                .catch(error => {
                    console.error('Quick view error:', error);
                    
                    // Remove loading overlay
                    document.body.removeChild(loadingOverlay);
                });
            });
        });
    },

    // Variation Swatches
    setupVariationSwatches: function() {
        // Check if we have variation swatches
        const swatches = document.querySelectorAll('.variation-swatches');
        if (!swatches.length) return;
        
        swatches.forEach(function(swatch) {
            const swatchItems = swatch.querySelectorAll('.swatch-item');
            const selectField = document.getElementById(swatch.getAttribute('data-attribute'));
            
            if (!selectField) return;
            
            swatchItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    swatchItems.forEach(i => i.classList.remove('active'));
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Update select field
                    const value = this.getAttribute('data-value');
                    selectField.value = value;
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    selectField.dispatchEvent(event);
                });
            });
            
            // Set initial active state based on select value
            const initialValue = selectField.value;
            if (initialValue) {
                const activeItem = swatch.querySelector(`.swatch-item[data-value="${initialValue}"]`);
                if (activeItem) {
                    activeItem.classList.add('active');
                }
            }
            
            // Listen for reset events
            document.addEventListener('reset_data', function() {
                swatchItems.forEach(i => i.classList.remove('active'));
            });
        });
    },

    // Filter Accordions
    setupFilterAccordions: function() {
        const filterWidgets = document.querySelectorAll('.widget_layered_nav, .widget_product_categories, .widget_price_filter');
        
        filterWidgets.forEach(function(widget) {
            const widgetTitle = widget.querySelector('.widget-title');
            if (!widgetTitle) return;
            
            // Add toggle button
            const toggleBtn = document.createElement('span');
            toggleBtn.className = 'filter-toggle';
            toggleBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            widgetTitle.appendChild(toggleBtn);
            
            // Get widget content
            const widgetContent = widget.querySelector('ul, form');
            if (!widgetContent) return;
            
            // Set initial state - open on desktop, closed on mobile
            if (window.innerWidth < 768) {
                widgetContent.style.display = 'none';
                widget.classList.add('closed');
            }
            
            // Toggle on click
            widgetTitle.addEventListener('click', function() {
                if (widget.classList.contains('closed')) {
                    widgetContent.style.display = 'block';
                    widget.classList.remove('closed');
                } else {
                    widgetContent.style.display = 'none';
                    widget.classList.add('closed');
                }
            });
        });
    }
};