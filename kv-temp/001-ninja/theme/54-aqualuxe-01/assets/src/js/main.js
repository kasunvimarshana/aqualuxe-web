/**
 * AquaLuxe Theme Main JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Import Alpine.js
import Alpine from 'alpinejs';

// Import Swiper for sliders
import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Configure Swiper
Swiper.use([Navigation, Pagination, Autoplay]);

/**
 * AquaLuxe Theme Class
 */
class AquaLuxeTheme {
    /**
     * Constructor
     */
    constructor() {
        this.initializeComponents();
        this.setupEventListeners();
    }

    /**
     * Initialize components
     */
    initializeComponents() {
        this.initializeHeader();
        this.initializeSliders();
        this.initializeDarkMode();
        this.initializeBackToTop();
        
        // Initialize WooCommerce components if WooCommerce is active
        if (typeof wc_add_to_cart_params !== 'undefined') {
            this.initializeWooCommerce();
        }
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.onDomReady();
        });
    }

    /**
     * On DOM ready
     */
    onDomReady() {
        // Add any DOM ready functionality here
    }

    /**
     * Initialize header
     */
    initializeHeader() {
        // Header search toggle
        const searchToggle = document.querySelector('.header-search-toggle');
        const headerSearch = document.getElementById('header-search');
        
        if (searchToggle && headerSearch) {
            searchToggle.addEventListener('click', () => {
                headerSearch.classList.toggle('hidden');
                searchToggle.setAttribute(
                    'aria-expanded',
                    headerSearch.classList.contains('hidden') ? 'false' : 'true'
                );
            });
        }

        // Mobile menu toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        const mobileMenuContainer = document.querySelector('.mobile-menu-container');
        
        if (mobileMenuToggle && mobileMenu && mobileMenuClose) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenuContainer.classList.remove('-translate-x-full');
                }, 10);
                mobileMenuToggle.setAttribute('aria-expanded', 'true');
            });
            
            mobileMenuClose.addEventListener('click', () => {
                mobileMenuContainer.classList.add('-translate-x-full');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            });
            
            mobileMenu.addEventListener('click', (e) => {
                if (e.target === mobileMenu) {
                    mobileMenuContainer.classList.add('-translate-x-full');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Sticky header
        const stickyHeader = document.querySelector('.sticky');
        
        if (stickyHeader) {
            let lastScrollTop = 0;
            
            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 100) {
                    stickyHeader.classList.add('shadow-md', 'bg-white/95', 'dark:bg-gray-900/95', 'backdrop-blur-sm');
                } else {
                    stickyHeader.classList.remove('shadow-md', 'bg-white/95', 'dark:bg-gray-900/95', 'backdrop-blur-sm');
                }
                
                lastScrollTop = scrollTop;
            });
        }
    }

    /**
     * Initialize sliders
     */
    initializeSliders() {
        // Hero slider
        const heroSlider = document.querySelector('.hero-slider');
        
        if (heroSlider) {
            new Swiper(heroSlider, {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.hero-slider-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.hero-slider-next',
                    prevEl: '.hero-slider-prev',
                },
            });
        }

        // Featured products slider
        const featuredProductsSlider = document.querySelector('.featured-products-slider');
        
        if (featuredProductsSlider) {
            new Swiper(featuredProductsSlider, {
                slidesPerView: 1,
                spaceBetween: 16,
                loop: true,
                pagination: {
                    el: '.featured-products-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.featured-products-next',
                    prevEl: '.featured-products-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 3,
                    },
                    1024: {
                        slidesPerView: 4,
                    },
                },
            });
        }

        // Testimonials slider
        const testimonialsSlider = document.querySelector('.testimonials-slider');
        
        if (testimonialsSlider) {
            new Swiper(testimonialsSlider, {
                slidesPerView: 1,
                spaceBetween: 16,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.testimonials-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.testimonials-next',
                    prevEl: '.testimonials-prev',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
        }
    }

    /**
     * Initialize dark mode
     */
    initializeDarkMode() {
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        
        if (darkModeToggle) {
            const sunIcon = darkModeToggle.querySelector('.sun-icon');
            const moonIcon = darkModeToggle.querySelector('.moon-icon');
            const currentMode = darkModeToggle.getAttribute('data-current-mode');
            
            // Set initial state
            if (currentMode === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Toggle dark mode
            darkModeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                
                const isDarkMode = document.documentElement.classList.contains('dark');
                
                // Update icons
                if (isDarkMode) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
                
                // Save preference
                const expiryDate = new Date();
                expiryDate.setTime(expiryDate.getTime() + (365 * 24 * 60 * 60 * 1000)); // 1 year
                document.cookie = `aqualuxe_color_scheme=${isDarkMode ? 'dark' : 'light'}; expires=${expiryDate.toUTCString()}; path=/`;
            });
        }
    }

    /**
     * Initialize back to top button
     */
    initializeBackToTop() {
        const backToTopButton = document.getElementById('back-to-top');
        
        if (backToTopButton) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('opacity-100');
                } else {
                    backToTopButton.classList.remove('opacity-100');
                }
            });
            
            backToTopButton.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth',
                });
            });
        }
    }

    /**
     * Initialize WooCommerce components
     */
    initializeWooCommerce() {
        this.initializeCartDrawer();
        this.initializeQuickView();
        this.initializeWishlist();
        this.initializeQuantityButtons();
    }

    /**
     * Initialize cart drawer
     */
    initializeCartDrawer() {
        const cartToggle = document.querySelector('.header-cart-toggle');
        const cartDrawer = document.getElementById('cart-drawer');
        const cartDrawerClose = document.querySelector('.cart-drawer-close');
        const cartDrawerContainer = document.querySelector('.cart-drawer-container');
        
        if (cartToggle && cartDrawer && cartDrawerClose) {
            cartToggle.addEventListener('click', () => {
                cartDrawer.classList.remove('hidden');
                setTimeout(() => {
                    cartDrawerContainer.classList.remove('translate-x-full');
                }, 10);
                cartToggle.setAttribute('aria-expanded', 'true');
            });
            
            cartDrawerClose.addEventListener('click', () => {
                cartDrawerContainer.classList.add('translate-x-full');
                setTimeout(() => {
                    cartDrawer.classList.add('hidden');
                }, 300);
                cartToggle.setAttribute('aria-expanded', 'false');
            });
            
            cartDrawer.addEventListener('click', (e) => {
                if (e.target === cartDrawer) {
                    cartDrawerContainer.classList.add('translate-x-full');
                    setTimeout(() => {
                        cartDrawer.classList.add('hidden');
                    }, 300);
                    cartToggle.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Cart item quantity buttons
            cartDrawer.addEventListener('click', (e) => {
                if (e.target.classList.contains('cart-item-quantity-minus') || e.target.closest('.cart-item-quantity-minus')) {
                    const button = e.target.classList.contains('cart-item-quantity-minus') ? e.target : e.target.closest('.cart-item-quantity-minus');
                    const cartItemKey = button.getAttribute('data-cart-item-key');
                    const input = button.nextElementSibling;
                    const currentValue = parseInt(input.value);
                    
                    if (currentValue > 1) {
                        input.value = currentValue - 1;
                        this.updateCartItemQuantity(cartItemKey, currentValue - 1);
                    }
                }
                
                if (e.target.classList.contains('cart-item-quantity-plus') || e.target.closest('.cart-item-quantity-plus')) {
                    const button = e.target.classList.contains('cart-item-quantity-plus') ? e.target : e.target.closest('.cart-item-quantity-plus');
                    const cartItemKey = button.getAttribute('data-cart-item-key');
                    const input = button.previousElementSibling;
                    const currentValue = parseInt(input.value);
                    
                    input.value = currentValue + 1;
                    this.updateCartItemQuantity(cartItemKey, currentValue + 1);
                }
                
                if (e.target.classList.contains('cart-item-remove') || e.target.closest('.cart-item-remove')) {
                    const button = e.target.classList.contains('cart-item-remove') ? e.target : e.target.closest('.cart-item-remove');
                    const cartItemKey = button.getAttribute('data-cart-item-key');
                    
                    this.removeCartItem(cartItemKey);
                }
            });
            
            // Cart item quantity input
            cartDrawer.addEventListener('change', (e) => {
                if (e.target.classList.contains('cart-item-quantity-input')) {
                    const input = e.target;
                    const cartItemKey = input.getAttribute('data-cart-item-key');
                    const value = parseInt(input.value);
                    
                    if (value > 0) {
                        this.updateCartItemQuantity(cartItemKey, value);
                    } else {
                        input.value = 1;
                        this.updateCartItemQuantity(cartItemKey, 1);
                    }
                }
            });
        }
    }

    /**
     * Update cart item quantity
     *
     * @param {string} cartItemKey Cart item key
     * @param {number} quantity Quantity
     */
    updateCartItemQuantity(cartItemKey, quantity) {
        const data = {
            action: 'aqualuxe_update_cart_item_quantity',
            cart_item_key: cartItemKey,
            quantity: quantity,
            security: aqualuxeData.nonce,
        };
        
        fetch(aqualuxeData.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Cache-Control': 'no-cache',
            },
            body: new URLSearchParams(data),
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                // Update cart count
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = response.data.cart_count;
                }
                
                // Update cart totals
                const cartSubtotal = document.querySelector('.cart-totals .subtotal');
                if (cartSubtotal) {
                    cartSubtotal.innerHTML = response.data.cart_subtotal;
                }
                
                const cartTotal = document.querySelector('.cart-totals .total');
                if (cartTotal) {
                    cartTotal.innerHTML = response.data.cart_total;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    /**
     * Remove cart item
     *
     * @param {string} cartItemKey Cart item key
     */
    removeCartItem(cartItemKey) {
        const data = {
            action: 'aqualuxe_remove_cart_item',
            cart_item_key: cartItemKey,
            security: aqualuxeData.nonce,
        };
        
        fetch(aqualuxeData.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Cache-Control': 'no-cache',
            },
            body: new URLSearchParams(data),
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                // Remove cart item from DOM
                const cartItem = document.querySelector(`.cart-item[data-cart-item-key="${cartItemKey}"]`);
                if (cartItem) {
                    cartItem.remove();
                }
                
                // Update cart count
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = response.data.cart_count;
                }
                
                // Update cart totals
                const cartSubtotal = document.querySelector('.cart-totals .subtotal');
                if (cartSubtotal) {
                    cartSubtotal.innerHTML = response.data.cart_subtotal;
                }
                
                const cartTotal = document.querySelector('.cart-totals .total');
                if (cartTotal) {
                    cartTotal.innerHTML = response.data.cart_total;
                }
                
                // Show empty cart message if cart is empty
                if (response.data.cart_count === 0) {
                    const cartItems = document.querySelector('.cart-items');
                    const cartTotals = document.querySelector('.cart-totals');
                    const cartActions = document.querySelector('.cart-actions');
                    
                    if (cartItems) {
                        cartItems.remove();
                    }
                    
                    if (cartTotals) {
                        cartTotals.remove();
                    }
                    
                    if (cartActions) {
                        cartActions.remove();
                    }
                    
                    const cartDrawerContent = document.querySelector('.cart-drawer-content');
                    if (cartDrawerContent) {
                        cartDrawerContent.innerHTML = `
                            <div class="empty-cart text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                
                                <h3 class="text-xl font-semibold mb-2">${aqualuxeData.i18n.emptyCart}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">${aqualuxeData.i18n.emptyCartMessage}</p>
                                
                                <a href="${aqualuxeData.shopUrl}" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    ${aqualuxeData.i18n.startShopping}
                                </a>
                            </div>
                        `;
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    /**
     * Initialize quick view
     */
    initializeQuickView() {
        const quickViewButtons = document.querySelectorAll('.aqualuxe-quick-view');
        
        if (quickViewButtons.length > 0) {
            quickViewButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    const productId = button.getAttribute('data-product-id');
                    
                    // Create modal
                    const modal = document.createElement('div');
                    modal.classList.add('quick-view-modal', 'fixed', 'inset-0', 'bg-gray-900', 'bg-opacity-50', 'z-50', 'flex', 'items-center', 'justify-center', 'p-4');
                    
                    modal.innerHTML = `
                        <div class="quick-view-modal-content bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative">
                            <button class="quick-view-modal-close absolute top-4 right-4 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="sr-only">${aqualuxeData.i18n.close}</span>
                            </button>
                            <div class="quick-view-modal-body p-6">
                                <div class="quick-view-loading flex items-center justify-center py-12">
                                    <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(modal);
                    
                    // Close modal on click outside
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            modal.remove();
                        }
                    });
                    
                    // Close modal on close button click
                    const closeButton = modal.querySelector('.quick-view-modal-close');
                    closeButton.addEventListener('click', () => {
                        modal.remove();
                    });
                    
                    // Load product data
                    const data = {
                        action: 'aqualuxe_quick_view',
                        product_id: productId,
                        security: aqualuxeData.nonce,
                    };
                    
                    fetch(aqualuxeData.ajaxUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Cache-Control': 'no-cache',
                        },
                        body: new URLSearchParams(data),
                    })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            const modalBody = modal.querySelector('.quick-view-modal-body');
                            modalBody.innerHTML = response.data;
                            
                            // Initialize quick view gallery
                            const mainImage = modalBody.querySelector('.quick-view-main-image img');
                            const thumbnails = modalBody.querySelectorAll('.quick-view-thumbnail');
                            
                            if (mainImage && thumbnails.length > 0) {
                                thumbnails.forEach(thumbnail => {
                                    thumbnail.addEventListener('click', () => {
                                        const thumbnailImg = thumbnail.querySelector('img');
                                        const thumbnailSrc = thumbnailImg.getAttribute('src');
                                        const thumbnailSrcset = thumbnailImg.getAttribute('srcset');
                                        const thumbnailSizes = thumbnailImg.getAttribute('sizes');
                                        
                                        // Update main image
                                        mainImage.setAttribute('src', thumbnailSrc.replace('-150x150', ''));
                                        
                                        if (thumbnailSrcset) {
                                            mainImage.setAttribute('srcset', thumbnailSrcset.replace('-150x150', ''));
                                        }
                                        
                                        if (thumbnailSizes) {
                                            mainImage.setAttribute('sizes', thumbnailSizes);
                                        }
                                        
                                        // Update active thumbnail
                                        thumbnails.forEach(t => {
                                            t.classList.remove('border-primary-600');
                                            t.classList.add('border-transparent');
                                        });
                                        
                                        thumbnail.classList.remove('border-transparent');
                                        thumbnail.classList.add('border-primary-600');
                                    });
                                });
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        }
    }

    /**
     * Initialize wishlist
     */
    initializeWishlist() {
        const wishlistButtons = document.querySelectorAll('.aqualuxe-wishlist');
        
        if (wishlistButtons.length > 0) {
            wishlistButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    const productId = button.getAttribute('data-product-id');
                    
                    const data = {
                        action: 'aqualuxe_wishlist',
                        product_id: productId,
                        security: aqualuxeData.nonce,
                    };
                    
                    fetch(aqualuxeData.ajaxUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Cache-Control': 'no-cache',
                        },
                        body: new URLSearchParams(data),
                    })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            if (response.data.action === 'added') {
                                button.classList.add('aqualuxe-wishlist-added');
                                button.textContent = aqualuxeData.i18n.removeFromWishlist;
                            } else {
                                button.classList.remove('aqualuxe-wishlist-added');
                                button.textContent = aqualuxeData.i18n.addToWishlist;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        }
    }

    /**
     * Initialize quantity buttons
     */
    initializeQuantityButtons() {
        const quantityInputs = document.querySelectorAll('.quantity');
        
        if (quantityInputs.length > 0) {
            quantityInputs.forEach(quantity => {
                const input = quantity.querySelector('input.qty');
                
                if (input) {
                    // Create minus button
                    const minusButton = document.createElement('button');
                    minusButton.type = 'button';
                    minusButton.classList.add('quantity-minus', 'bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'w-8', 'h-8', 'flex', 'items-center', 'justify-center', 'rounded-l');
                    minusButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        <span class="sr-only">${aqualuxeData.i18n.decrease}</span>
                    `;
                    
                    // Create plus button
                    const plusButton = document.createElement('button');
                    plusButton.type = 'button';
                    plusButton.classList.add('quantity-plus', 'bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'w-8', 'h-8', 'flex', 'items-center', 'justify-center', 'rounded-r');
                    plusButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="sr-only">${aqualuxeData.i18n.increase}</span>
                    `;
                    
                    // Add buttons to quantity
                    input.parentNode.insertBefore(minusButton, input);
                    input.parentNode.appendChild(plusButton);
                    
                    // Add event listeners
                    minusButton.addEventListener('click', () => {
                        const currentValue = parseInt(input.value);
                        
                        if (currentValue > input.min) {
                            input.value = currentValue - 1;
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                    
                    plusButton.addEventListener('click', () => {
                        const currentValue = parseInt(input.value);
                        
                        if (input.max === '' || currentValue < parseInt(input.max)) {
                            input.value = currentValue + 1;
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                }
            });
        }
    }
}

// Initialize AquaLuxe Theme
document.addEventListener('DOMContentLoaded', () => {
    new AquaLuxeTheme();
});