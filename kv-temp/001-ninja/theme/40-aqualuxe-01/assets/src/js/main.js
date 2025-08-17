/**
 * Main JavaScript file for AquaLuxe theme
 *
 * This file handles all the interactive functionality of the theme.
 */

// Import Alpine.js
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();

// Document Ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme components
    initializeTheme();
});

/**
 * Initialize all theme components
 */
function initializeTheme() {
    // Initialize mobile menu
    initializeMobileMenu();
    
    // Initialize sticky header
    initializeStickyHeader();
    
    // Initialize search modal
    initializeSearchModal();
    
    // Initialize dark mode toggle
    initializeDarkMode();
    
    // Initialize dropdown menus
    initializeDropdowns();
    
    // Initialize WooCommerce features if available
    if (typeof woocommerce !== 'undefined') {
        initializeWooCommerce();
    }
}

/**
 * Initialize mobile menu functionality
 */
function initializeMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const primaryMenu = document.querySelector('.primary-menu');
    
    if (!menuToggle || !primaryMenu) return;
    
    menuToggle.addEventListener('click', function() {
        const expanded = menuToggle.getAttribute('aria-expanded') === 'true' || false;
        menuToggle.setAttribute('aria-expanded', !expanded);
        primaryMenu.classList.toggle('active');
        menuToggle.classList.toggle('active');
    });
    
    // Add dropdown toggles to mobile menu items with children
    const menuItemsWithChildren = document.querySelectorAll('.menu-item-has-children');
    
    menuItemsWithChildren.forEach(function(item) {
        const dropdownToggle = document.createElement('button');
        dropdownToggle.className = 'dropdown-toggle';
        dropdownToggle.setAttribute('aria-expanded', 'false');
        dropdownToggle.innerHTML = '<span class="screen-reader-text">Expand child menu</span>';
        
        item.appendChild(dropdownToggle);
        
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            this.setAttribute('aria-expanded', !expanded);
            this.parentNode.querySelector('.sub-menu').classList.toggle('active');
        });
    });
    
    // Close mobile menu on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            menuToggle.setAttribute('aria-expanded', 'false');
            primaryMenu.classList.remove('active');
            menuToggle.classList.remove('active');
        }
    });
}

/**
 * Initialize sticky header functionality
 */
function initializeStickyHeader() {
    const header = document.querySelector('.sticky-header');
    
    if (!header) return;
    
    let lastScrollTop = 0;
    const scrollThreshold = 100;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > scrollThreshold) {
            header.classList.add('is-sticky');
            
            // Hide header when scrolling down, show when scrolling up
            if (scrollTop > lastScrollTop) {
                header.classList.add('is-hidden');
            } else {
                header.classList.remove('is-hidden');
            }
        } else {
            header.classList.remove('is-sticky');
            header.classList.remove('is-hidden');
        }
        
        lastScrollTop = scrollTop;
    });
}

/**
 * Initialize search modal functionality
 */
function initializeSearchModal() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchModal = document.querySelector('.search-modal');
    const searchModalClose = document.querySelector('.search-modal-close');
    const searchField = searchModal ? searchModal.querySelector('.search-field') : null;
    
    if (!searchToggle || !searchModal || !searchModalClose) return;
    
    searchToggle.addEventListener('click', function(e) {
        e.preventDefault();
        searchModal.classList.add('active');
        document.body.classList.add('modal-open');
        
        // Focus on search field
        if (searchField) {
            setTimeout(function() {
                searchField.focus();
            }, 100);
        }
    });
    
    searchModalClose.addEventListener('click', function() {
        searchModal.classList.remove('active');
        document.body.classList.remove('modal-open');
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchModal.classList.contains('active')) {
            searchModal.classList.remove('active');
            document.body.classList.remove('modal-open');
        }
    });
    
    // Close modal on outside click
    searchModal.addEventListener('click', function(e) {
        if (e.target === searchModal) {
            searchModal.classList.remove('active');
            document.body.classList.remove('modal-open');
        }
    });
}

/**
 * Initialize dark mode functionality
 */
function initializeDarkMode() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    
    if (!darkModeToggle) return;
    
    // Check for saved theme preference or respect OS preference
    const savedTheme = localStorage.getItem('aqualuxe-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        document.documentElement.classList.add('dark');
    }
    
    // Toggle dark mode on button click
    darkModeToggle.addEventListener('click', function() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('aqualuxe-theme', isDark ? 'dark' : 'light');
    });
    
    // Listen for OS theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        if (localStorage.getItem('aqualuxe-theme')) return;
        
        if (e.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });
}

/**
 * Initialize dropdown menus
 */
function initializeDropdowns() {
    const dropdowns = document.querySelectorAll('.header-actions .header-search, .header-actions .header-cart');
    
    dropdowns.forEach(function(dropdown) {
        const button = dropdown.querySelector('button, a');
        const content = dropdown.querySelector('.search-dropdown, .cart-dropdown');
        
        if (!button || !content) return;
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close all other dropdowns
            dropdowns.forEach(function(otherDropdown) {
                if (otherDropdown !== dropdown) {
                    otherDropdown.querySelector('.search-dropdown, .cart-dropdown')?.classList.remove('active');
                }
            });
            
            content.classList.toggle('active');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        dropdowns.forEach(function(dropdown) {
            const content = dropdown.querySelector('.search-dropdown, .cart-dropdown');
            if (content && !dropdown.contains(e.target)) {
                content.classList.remove('active');
            }
        });
    });
}

/**
 * Initialize WooCommerce specific functionality
 */
function initializeWooCommerce() {
    // Product quantity input
    const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
    
    quantityInputs.forEach(function(input) {
        const minusBtn = document.createElement('button');
        minusBtn.type = 'button';
        minusBtn.className = 'quantity-button minus';
        minusBtn.textContent = '-';
        
        const plusBtn = document.createElement('button');
        plusBtn.type = 'button';
        plusBtn.className = 'quantity-button plus';
        plusBtn.textContent = '+';
        
        input.parentNode.insertBefore(minusBtn, input);
        input.parentNode.appendChild(plusBtn);
        
        minusBtn.addEventListener('click', function() {
            const currentValue = parseInt(input.value);
            const minValue = parseInt(input.min) || 1;
            
            if (currentValue > minValue) {
                input.value = currentValue - 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        
        plusBtn.addEventListener('click', function() {
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.max);
            
            if (!maxValue || currentValue < maxValue) {
                input.value = currentValue + 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
    
    // Product gallery
    initializeProductGallery();
    
    // Quick view
    initializeQuickView();
    
    // Ajax add to cart
    initializeAjaxAddToCart();
}

/**
 * Initialize product gallery
 */
function initializeProductGallery() {
    const productGallery = document.querySelector('.woocommerce-product-gallery');
    
    if (!productGallery) return;
    
    // Main image
    const mainImage = productGallery.querySelector('.woocommerce-product-gallery__image');
    
    // Thumbnails
    const thumbnails = productGallery.querySelectorAll('.woocommerce-product-gallery__image:not(:first-child), .woocommerce-product-gallery__thumb');
    
    thumbnails.forEach(function(thumbnail) {
        thumbnail.addEventListener('click', function(e) {
            e.preventDefault();
            
            const fullSizeUrl = this.getAttribute('data-large-image') || this.querySelector('a').getAttribute('href');
            const imageCaption = this.getAttribute('data-caption') || '';
            
            // Update main image
            mainImage.querySelector('img').setAttribute('src', fullSizeUrl);
            mainImage.querySelector('img').setAttribute('data-large-image', fullSizeUrl);
            mainImage.querySelector('a').setAttribute('href', fullSizeUrl);
            
            if (imageCaption) {
                mainImage.querySelector('a').setAttribute('data-caption', imageCaption);
            }
            
            // Update active state
            thumbnails.forEach(function(thumb) {
                thumb.classList.remove('active');
            });
            
            this.classList.add('active');
        });
    });
}

/**
 * Initialize quick view functionality
 */
function initializeQuickView() {
    const quickViewButtons = document.querySelectorAll('.quick-view-button');
    
    if (!quickViewButtons.length) return;
    
    quickViewButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product-id');
            
            if (!productId) return;
            
            // Show loading state
            document.body.classList.add('loading');
            
            // Fetch product data via AJAX
            fetch(aqualuxeData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_quick_view',
                    product_id: productId,
                    security: aqualuxeData.quickViewNonce,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Create modal
                    const modal = document.createElement('div');
                    modal.className = 'quick-view-modal';
                    modal.innerHTML = `
                        <div class="quick-view-modal-inner">
                            <div class="quick-view-content">
                                <button class="quick-view-close">&times;</button>
                                <div class="quick-view-content-inner">
                                    ${data.data.html}
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(modal);
                    
                    // Show modal
                    setTimeout(function() {
                        modal.classList.add('active');
                        document.body.classList.add('modal-open');
                        document.body.classList.remove('loading');
                    }, 100);
                    
                    // Close modal on button click
                    modal.querySelector('.quick-view-close').addEventListener('click', function() {
                        modal.classList.remove('active');
                        document.body.classList.remove('modal-open');
                        
                        setTimeout(function() {
                            modal.remove();
                        }, 300);
                    });
                    
                    // Close modal on outside click
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal || e.target === modal.querySelector('.quick-view-modal-inner')) {
                            modal.classList.remove('active');
                            document.body.classList.remove('modal-open');
                            
                            setTimeout(function() {
                                modal.remove();
                            }, 300);
                        }
                    });
                    
                    // Close modal on escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && modal.classList.contains('active')) {
                            modal.classList.remove('active');
                            document.body.classList.remove('modal-open');
                            
                            setTimeout(function() {
                                modal.remove();
                            }, 300);
                        }
                    });
                    
                    // Initialize product gallery in quick view
                    initializeQuickViewGallery(modal);
                    
                    // Initialize quantity inputs in quick view
                    initializeQuantityInputs(modal);
                    
                    // Initialize variation form if exists
                    if (typeof jQuery !== 'undefined' && jQuery.fn.wc_variation_form) {
                        const variationForm = modal.querySelector('.variations_form');
                        if (variationForm) {
                            jQuery(variationForm).wc_variation_form();
                        }
                    }
                    
                    // Initialize add to cart button
                    initializeQuickViewAddToCart(modal);
                    
                } else {
                    console.error('Error loading quick view:', data.data);
                    document.body.classList.remove('loading');
                }
            })
            .catch(error => {
                console.error('Error loading quick view:', error);
                document.body.classList.remove('loading');
            });
        });
    });
}

/**
 * Initialize quick view gallery
 */
function initializeQuickViewGallery(modal) {
    if (!modal) return;
    
    const mainImage = modal.querySelector('.quick-view-product-image .woocommerce-product-gallery__image');
    if (!mainImage) return;
    
    const thumbnails = modal.querySelectorAll('.quick-view-thumbnails .woocommerce-product-gallery__image');
    
    thumbnails.forEach(function(thumbnail) {
        thumbnail.addEventListener('click', function(e) {
            e.preventDefault();
            
            const fullSizeUrl = this.getAttribute('data-large-image') || this.querySelector('a')?.getAttribute('href');
            if (!fullSizeUrl) return;
            
            const imageCaption = this.getAttribute('data-caption') || '';
            
            // Update main image
            const mainImg = mainImage.querySelector('img');
            if (mainImg) {
                mainImg.setAttribute('src', fullSizeUrl);
                mainImg.setAttribute('data-large-image', fullSizeUrl);
            }
            
            const mainLink = mainImage.querySelector('a');
            if (mainLink) {
                mainLink.setAttribute('href', fullSizeUrl);
                
                if (imageCaption) {
                    mainLink.setAttribute('data-caption', imageCaption);
                }
            }
            
            // Update active state
            thumbnails.forEach(function(thumb) {
                thumb.classList.remove('active');
            });
            
            this.classList.add('active');
        });
    });
}

/**
 * Initialize quantity inputs in quick view
 */
function initializeQuantityInputs(container) {
    if (!container) return;
    
    const quantityInputs = container.querySelectorAll('.quantity input[type="number"]');
    
    quantityInputs.forEach(function(input) {
        // Check if buttons already exist
        const parent = input.parentNode;
        if (parent.querySelector('.quantity-button')) return;
        
        const minusBtn = document.createElement('button');
        minusBtn.type = 'button';
        minusBtn.className = 'quantity-button minus';
        minusBtn.textContent = '-';
        
        const plusBtn = document.createElement('button');
        plusBtn.type = 'button';
        plusBtn.className = 'quantity-button plus';
        plusBtn.textContent = '+';
        
        parent.insertBefore(minusBtn, input);
        parent.appendChild(plusBtn);
        
        minusBtn.addEventListener('click', function() {
            const currentValue = parseInt(input.value);
            const minValue = parseInt(input.min) || 1;
            
            if (currentValue > minValue) {
                input.value = currentValue - 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        
        plusBtn.addEventListener('click', function() {
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.max);
            
            if (!maxValue || currentValue < maxValue) {
                input.value = currentValue + 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
}

/**
 * Initialize add to cart button in quick view
 */
function initializeQuickViewAddToCart(modal) {
    if (!modal) return;
    
    const addToCartForm = modal.querySelector('form.cart');
    if (!addToCartForm) return;
    
    // For simple products with direct add to cart
    const addToCartButton = addToCartForm.querySelector('.single_add_to_cart_button');
    if (!addToCartButton) return;
    
    // If it's not a variable product, add AJAX functionality
    if (!addToCartForm.classList.contains('variations_form')) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = addToCartButton.value;
            const quantity = addToCartForm.querySelector('input[name="quantity"]').value;
            
            // Show loading state
            addToCartButton.classList.add('loading');
            
            // Add to cart via AJAX
            fetch(aqualuxeData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    security: aqualuxeData.addToCartNonce,
                }),
            })
            .then(response => response.json())
            .then(data => {
                addToCartButton.classList.remove('loading');
                
                if (data.success) {
                    // Show success message
                    const notification = document.createElement('div');
                    notification.className = 'aqualuxe-notification success';
                    notification.innerHTML = `
                        <div class="notification-content">
                            <div class="notification-icon">✓</div>
                            <div class="notification-message">${data.data.message || 'Product added to cart!'}</div>
                        </div>
                        <button class="notification-close">&times;</button>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(function() {
                        notification.classList.add('active');
                    }, 100);
                    
                    setTimeout(function() {
                        notification.classList.remove('active');
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    }, 3000);
                    
                    notification.querySelector('.notification-close').addEventListener('click', function() {
                        notification.classList.remove('active');
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    });
                    
                    // Update cart fragments
                    if (data.fragments) {
                        jQuery.each(data.fragments, function(key, value) {
                            jQuery(key).replaceWith(value);
                        });
                    }
                    
                    // Close the quick view modal after a short delay
                    setTimeout(function() {
                        modal.classList.remove('active');
                        document.body.classList.remove('modal-open');
                        
                        setTimeout(function() {
                            modal.remove();
                        }, 300);
                    }, 1000);
                } else {
                    // Show error message
                    const notification = document.createElement('div');
                    notification.className = 'aqualuxe-notification error';
                    notification.innerHTML = `
                        <div class="notification-content">
                            <div class="notification-icon">✕</div>
                            <div class="notification-message">${data.data.message || 'Error adding to cart.'}</div>
                        </div>
                        <button class="notification-close">&times;</button>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(function() {
                        notification.classList.add('active');
                    }, 100);
                    
                    setTimeout(function() {
                        notification.classList.remove('active');
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    }, 3000);
                    
                    notification.querySelector('.notification-close').addEventListener('click', function() {
                        notification.classList.remove('active');
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    });
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                addToCartButton.classList.remove('loading');
            });
        });
    }
}

/**
 * Initialize AJAX add to cart functionality
 */
function initializeAjaxAddToCart() {
    const addToCartButtons = document.querySelectorAll('.add_to_cart_button:not(.product_type_variable):not(.product_type_grouped)');
    
    if (!addToCartButtons.length) return;
    
    addToCartButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product_id');
            const quantity = this.closest('form') ? this.closest('form').querySelector('input[name="quantity"]')?.value || 1 : 1;
            
            if (!productId) return;
            
            // Show loading state
            this.classList.add('loading');
            
            // Add to cart via AJAX
            fetch(aqualuxeData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'aqualuxe_ajax_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    security: aqualuxeData.addToCartNonce,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart fragments
                    if (data.data.fragments) {
                        jQuery.each(data.data.fragments, function(key, value) {
                            jQuery(key).replaceWith(value);
                        });
                    }
                    
                    // Show success message
                    const notification = document.createElement('div');
                    notification.className = 'aqualuxe-notification success';
                    notification.innerHTML = `
                        <div class="notification-content">
                            <div class="notification-icon">✓</div>
                            <div class="notification-message">${data.data.message}</div>
                        </div>
                        <button class="notification-close">&times;</button>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(function() {
                        notification.classList.add('active');
                    }, 100);
                    
                    setTimeout(function() {
                        notification.classList.remove('active');
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    }, 3000);
                    
                    notification.querySelector('.notification-close').addEventListener('click', function() {
                        notification.classList.remove('active');
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    });
                } else {
                    // Show error message
                    const notification = document.createElement('div');
                    notification.className = 'aqualuxe-notification error';
                    notification.innerHTML = `
                        <div class="notification-content">
                            <div class="notification-icon">✕</div>
                            <div class="notification-message">${data.data.message}</div>
                        </div>
                        <button class="notification-close">&times;</button>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(function() {
                        notification.classList.add('active');
                    }, 100);
                    
                    setTimeout(function() {
                        notification.classList.remove('active');
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    }, 3000);
                    
                    notification.querySelector('.notification-close').addEventListener('click', function() {
                        notification.classList.remove('active');
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    });
                }
                
                // Remove loading state
                this.classList.remove('loading');
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                this.classList.remove('loading');
            });
        });
    });
}