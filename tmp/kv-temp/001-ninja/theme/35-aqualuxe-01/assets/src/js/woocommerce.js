/**
 * AquaLuxe WordPress Theme
 * WooCommerce JavaScript File
 */

// Import Alpine.js
import Alpine from 'alpinejs';

/**
 * Document Ready Function
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize WooCommerce functionality
  AquaLuxeWooCommerce.init();
});

/**
 * AquaLuxe WooCommerce Object
 */
const AquaLuxeWooCommerce = {
  /**
   * Initialize WooCommerce functionality
   */
  init: function() {
    this.setupEventListeners();
    this.initializeComponents();
    this.setupAjaxHandlers();
  },

  /**
   * Set up event listeners
   */
  setupEventListeners: function() {
    // Quantity input buttons
    const quantityInputs = document.querySelectorAll('.quantity');
    if (quantityInputs.length) {
      quantityInputs.forEach(container => {
        const input = container.querySelector('input[type="number"]');
        const minusBtn = container.querySelector('.quantity-minus');
        const plusBtn = container.querySelector('.quantity-plus');
        
        if (input && minusBtn && plusBtn) {
          minusBtn.addEventListener('click', function() {
            const currentValue = parseInt(input.value);
            const minValue = parseInt(input.getAttribute('min')) || 1;
            if (currentValue > minValue) {
              input.value = currentValue - 1;
              input.dispatchEvent(new Event('change', { bubbles: true }));
            }
          });
          
          plusBtn.addEventListener('click', function() {
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.getAttribute('max'));
            if (!maxValue || currentValue < maxValue) {
              input.value = currentValue + 1;
              input.dispatchEvent(new Event('change', { bubbles: true }));
            }
          });
        }
      });
    }

    // Product gallery
    const productGallery = document.querySelector('.woocommerce-product-gallery');
    if (productGallery) {
      const mainImage = productGallery.querySelector('.woocommerce-product-gallery__image');
      const thumbnails = productGallery.querySelectorAll('.woocommerce-product-gallery__thumbnail');
      
      thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function(e) {
          e.preventDefault();
          const fullSizeUrl = this.getAttribute('data-full-size');
          const image = this.querySelector('img');
          
          if (fullSizeUrl && mainImage) {
            const mainImg = mainImage.querySelector('img');
            const mainLink = mainImage.querySelector('a');
            
            if (mainImg) {
              mainImg.src = fullSizeUrl;
              mainImg.srcset = '';
            }
            
            if (mainLink) {
              mainLink.href = fullSizeUrl;
            }
            
            // Update active state
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            this.classList.add('active');
          }
        });
      });
    }

    // Product tabs
    const productTabs = document.querySelector('.woocommerce-tabs');
    if (productTabs) {
      const tabs = productTabs.querySelectorAll('.tabs li');
      const panels = productTabs.querySelectorAll('.woocommerce-Tabs-panel');
      
      tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
          e.preventDefault();
          const target = this.querySelector('a').getAttribute('href');
          
          // Update active tab
          tabs.forEach(t => t.classList.remove('active'));
          this.classList.add('active');
          
          // Show target panel
          panels.forEach(panel => {
            panel.style.display = panel.id === target.substring(1) ? 'block' : 'none';
          });
        });
      });
    }
  },

  /**
   * Initialize components
   */
  initializeComponents: function() {
    // Initialize mini cart
    this.initMiniCart();
    
    // Initialize product quick view
    this.initQuickView();
    
    // Initialize wishlist
    this.initWishlist();
    
    // Initialize product filters
    this.initProductFilters();
  },

  /**
   * Set up AJAX handlers
   */
  setupAjaxHandlers: function() {
    // Add to cart AJAX
    const addToCartButtons = document.querySelectorAll('.ajax_add_to_cart');
    if (addToCartButtons.length) {
      addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          
          button.classList.add('loading');
          
          const productId = button.getAttribute('data-product_id');
          const quantity = button.getAttribute('data-quantity') || 1;
          
          // AJAX add to cart
          fetch(woocommerce_params.ajax_url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
              action: 'aqualuxe_ajax_add_to_cart',
              product_id: productId,
              quantity: quantity,
              nonce: woocommerce_params.add_to_cart_nonce
            })
          })
          .then(response => response.json())
          .then(data => {
            button.classList.remove('loading');
            
            if (data.success) {
              // Update cart fragments
              if (data.fragments) {
                jQuery.each(data.fragments, function(key, value) {
                  jQuery(key).replaceWith(value);
                });
              }
              
              // Show mini cart
              const miniCart = document.querySelector('.mini-cart');
              if (miniCart) {
                miniCart.classList.add('is-active');
                
                // Auto hide after 3 seconds
                setTimeout(function() {
                  miniCart.classList.remove('is-active');
                }, 3000);
              }
              
              // Trigger event
              document.dispatchEvent(new CustomEvent('added_to_cart', {
                detail: {
                  product_id: productId,
                  quantity: quantity
                }
              }));
            } else {
              console.error('Error adding to cart:', data.message);
            }
          })
          .catch(error => {
            button.classList.remove('loading');
            console.error('Error:', error);
          });
        });
      });
    }
  },

  /**
   * Initialize mini cart
   */
  initMiniCart: function() {
    const miniCartToggle = document.querySelector('.mini-cart-toggle');
    const miniCart = document.querySelector('.mini-cart');
    
    if (miniCartToggle && miniCart) {
      miniCartToggle.addEventListener('click', function(e) {
        e.preventDefault();
        miniCart.classList.toggle('is-active');
      });
      
      // Close mini cart when clicking outside
      document.addEventListener('click', function(e) {
        if (!miniCart.contains(e.target) && !miniCartToggle.contains(e.target)) {
          miniCart.classList.remove('is-active');
        }
      });
      
      // Update mini cart with Alpine.js
      Alpine.data('miniCart', () => ({
        isOpen: false,
        itemCount: 0,
        
        init() {
          this.itemCount = parseInt(miniCartToggle.getAttribute('data-count')) || 0;
          
          // Listen for cart updates
          document.addEventListener('added_to_cart', () => {
            this.itemCount++;
          });
        },
        
        toggle() {
          this.isOpen = !this.isOpen;
        },
        
        close() {
          this.isOpen = false;
        }
      }));
    }
  },

  /**
   * Initialize product quick view
   */
  initQuickView: function() {
    const quickViewButtons = document.querySelectorAll('.quick-view-button');
    
    if (quickViewButtons.length) {
      quickViewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          
          const productId = this.getAttribute('data-product-id');
          
          // Show loading overlay
          const modal = document.getElementById('quick-view-modal');
          const modalContent = modal.querySelector('.modal-content');
          
          modal.classList.add('is-active');
          modalContent.innerHTML = '<div class="loading-spinner"></div>';
          
          // Fetch product data
          fetch(woocommerce_params.ajax_url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
              action: 'aqualuxe_quick_view',
              product_id: productId,
              nonce: woocommerce_params.quick_view_nonce
            })
          })
          .then(response => response.text())
          .then(html => {
            modalContent.innerHTML = html;
            
            // Initialize quantity buttons
            AquaLuxeWooCommerce.setupEventListeners();
            
            // Initialize product gallery
            const gallery = modal.querySelector('.woocommerce-product-gallery');
            if (gallery) {
              AquaLuxeWooCommerce.initProductGallery(gallery);
            }
          })
          .catch(error => {
            modalContent.innerHTML = '<p>Error loading product data. Please try again.</p>';
            console.error('Error:', error);
          });
        });
      });
      
      // Close modal
      const closeButtons = document.querySelectorAll('.modal-close');
      closeButtons.forEach(button => {
        button.addEventListener('click', function() {
          const modal = this.closest('.modal');
          if (modal) {
            modal.classList.remove('is-active');
          }
        });
      });
    }
  },

  /**
   * Initialize wishlist
   */
  initWishlist: function() {
    const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
    
    if (wishlistButtons.length) {
      wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          
          const productId = this.getAttribute('data-product-id');
          const isInWishlist = this.classList.contains('in-wishlist');
          
          // Toggle wishlist state
          fetch(woocommerce_params.ajax_url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
              action: isInWishlist ? 'aqualuxe_remove_from_wishlist' : 'aqualuxe_add_to_wishlist',
              product_id: productId,
              nonce: woocommerce_params.wishlist_nonce
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Update button state
              if (isInWishlist) {
                button.classList.remove('in-wishlist');
                button.setAttribute('title', 'Add to Wishlist');
              } else {
                button.classList.add('in-wishlist');
                button.setAttribute('title', 'Remove from Wishlist');
              }
              
              // Update wishlist count
              const wishlistCount = document.querySelector('.wishlist-count');
              if (wishlistCount) {
                wishlistCount.textContent = data.count;
              }
              
              // Show notification
              AquaLuxe.showNotification(data.message);
            } else {
              console.error('Error:', data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
          });
        });
      });
    }
  },

  /**
   * Initialize product filters
   */
  initProductFilters: function() {
    const filterForm = document.querySelector('.aqualuxe-product-filters');
    
    if (filterForm) {
      // Price range slider
      const priceRange = filterForm.querySelector('.price-range-slider');
      if (priceRange) {
        const minInput = filterForm.querySelector('input[name="min_price"]');
        const maxInput = filterForm.querySelector('input[name="max_price"]');
        const minValue = parseInt(priceRange.getAttribute('data-min')) || 0;
        const maxValue = parseInt(priceRange.getAttribute('data-max')) || 1000;
        const currentMin = parseInt(minInput.value) || minValue;
        const currentMax = parseInt(maxInput.value) || maxValue;
        
        // Initialize price slider
        // Note: This is a placeholder. In a real implementation, you would use a library like noUiSlider
        console.log('Price range slider initialized with:', {
          min: minValue,
          max: maxValue,
          currentMin: currentMin,
          currentMax: currentMax
        });
      }
      
      // AJAX filtering
      filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'aqualuxe_filter_products');
        formData.append('nonce', woocommerce_params.filter_nonce);
        
        // Show loading state
        const productsContainer = document.querySelector('.products');
        if (productsContainer) {
          productsContainer.classList.add('is-loading');
        }
        
        // Update URL with filter parameters
        const params = new URLSearchParams(formData);
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);
        
        // Fetch filtered products
        fetch(woocommerce_params.ajax_url, {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(html => {
          if (productsContainer) {
            productsContainer.innerHTML = html;
            productsContainer.classList.remove('is-loading');
            
            // Reinitialize components
            AquaLuxeWooCommerce.setupEventListeners();
          }
        })
        .catch(error => {
          if (productsContainer) {
            productsContainer.classList.remove('is-loading');
          }
          console.error('Error:', error);
        });
      });
      
      // Instant filtering for checkboxes and radio buttons
      const instantFilters = filterForm.querySelectorAll('input[type="checkbox"], input[type="radio"]');
      instantFilters.forEach(filter => {
        filter.addEventListener('change', function() {
          filterForm.dispatchEvent(new Event('submit'));
        });
      });
    }
  },

  /**
   * Initialize product gallery
   * @param {HTMLElement} gallery - Gallery element
   */
  initProductGallery: function(gallery) {
    const mainImage = gallery.querySelector('.woocommerce-product-gallery__image');
    const thumbnails = gallery.querySelectorAll('.woocommerce-product-gallery__thumbnail');
    
    thumbnails.forEach(thumbnail => {
      thumbnail.addEventListener('click', function(e) {
        e.preventDefault();
        const fullSizeUrl = this.getAttribute('data-full-size');
        
        if (fullSizeUrl && mainImage) {
          const mainImg = mainImage.querySelector('img');
          const mainLink = mainImage.querySelector('a');
          
          if (mainImg) {
            mainImg.src = fullSizeUrl;
            mainImg.srcset = '';
          }
          
          if (mainLink) {
            mainLink.href = fullSizeUrl;
          }
          
          // Update active state
          thumbnails.forEach(thumb => thumb.classList.remove('active'));
          this.classList.add('active');
        }
      });
    });
  }
};

// Export AquaLuxeWooCommerce object
window.AquaLuxeWooCommerce = AquaLuxeWooCommerce;