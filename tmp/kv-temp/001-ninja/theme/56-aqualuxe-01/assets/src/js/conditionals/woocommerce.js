/**
 * WooCommerce Conditional Module
 * 
 * This module is loaded only when WooCommerce is active.
 * It handles WooCommerce-specific functionality.
 */

// Check if WooCommerce is active
if (typeof woocommerce !== 'undefined' || document.body.classList.contains('woocommerce') || document.body.classList.contains('woocommerce-page')) {
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize WooCommerce features
    initWooCommerceFeatures();
  });
}

/**
 * Initialize WooCommerce Features
 */
function initWooCommerceFeatures() {
  // Quick view functionality
  initQuickView();
  
  // Add to cart functionality
  initAddToCart();
  
  // Quantity input functionality
  initQuantityInputs();
  
  // Product gallery
  initProductGallery();
  
  // Product filters
  initProductFilters();
  
  // Wishlist functionality
  initWishlist();
  
  // Mini cart functionality
  initMiniCart();
  
  // Checkout enhancements
  initCheckoutEnhancements();
}

/**
 * Initialize Quick View
 */
function initQuickView() {
  const quickViewButtons = document.querySelectorAll('.quick-view-button');
  
  quickViewButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const productId = this.getAttribute('data-product-id');
      
      // Show loading state
      const modal = document.getElementById('quick-view-modal');
      if (!modal) {
        // Create modal if it doesn't exist
        createQuickViewModal();
      }
      
      const modalContent = document.querySelector('#quick-view-modal .modal-content');
      modalContent.innerHTML = '<div class="flex justify-center items-center p-8"><div class="loader"></div></div>';
      
      document.getElementById('quick-view-modal').classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
      
      // Fetch product data
      fetch(`/wp-json/aqualuxe/v1/products/${productId}/quick-view`)
        .then(response => response.json())
        .then(data => {
          // Populate modal with product data
          modalContent.innerHTML = data.html;
          
          // Initialize gallery in quick view
          initProductGallery('#quick-view-modal');
          
          // Initialize quantity inputs in quick view
          initQuantityInputs('#quick-view-modal');
          
          // Initialize variations in quick view
          if (typeof wc_add_to_cart_variation_params !== 'undefined') {
            jQuery('#quick-view-modal .variations_form').wc_variation_form();
          }
        })
        .catch(error => {
          modalContent.innerHTML = `<div class="p-6 text-center">Error loading product data. Please try again.</div>`;
        });
    });
  });
}

/**
 * Create Quick View Modal
 */
function createQuickViewModal() {
  const modal = document.createElement('div');
  modal.id = 'quick-view-modal';
  modal.className = 'modal fixed inset-0 z-50 overflow-auto hidden';
  modal.setAttribute('aria-modal', 'true');
  modal.setAttribute('role', 'dialog');
  
  modal.innerHTML = `
    <div class="modal-backdrop absolute inset-0 bg-dark-900 bg-opacity-75"></div>
    <div class="modal-container relative min-h-screen flex items-center justify-center p-4">
      <div class="modal-dialog bg-white dark:bg-dark-700 rounded-lg shadow-xl w-full max-w-4xl">
        <div class="modal-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-dark-600">
          <h3 class="modal-title text-lg font-medium">Quick View</h3>
          <button type="button" class="modal-close text-gray-400 hover:text-gray-500" data-modal-close aria-label="Close">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="modal-content p-6"></div>
      </div>
    </div>
  `;
  
  document.body.appendChild(modal);
  
  // Add event listeners
  const closeButton = modal.querySelector('.modal-close');
  const backdrop = modal.querySelector('.modal-backdrop');
  
  closeButton.addEventListener('click', function() {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  });
  
  backdrop.addEventListener('click', function() {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  });
  
  // Close on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      modal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });
}

/**
 * Initialize Add to Cart
 */
function initAddToCart() {
  // Ajax add to cart for single products
  const addToCartForms = document.querySelectorAll('form.cart');
  
  addToCartForms.forEach(form => {
    if (!form.classList.contains('variations_form') && !form.classList.contains('grouped_form')) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const addToCartButton = form.querySelector('.single_add_to_cart_button');
        if (addToCartButton.classList.contains('disabled')) {
          return;
        }
        
        // Show loading state
        addToCartButton.classList.add('loading');
        const originalText = addToCartButton.textContent;
        addToCartButton.textContent = 'Adding...';
        
        // Get form data
        const formData = new FormData(form);
        formData.append('action', 'aqualuxe_ajax_add_to_cart');
        
        // Send AJAX request
        fetch(aqualuxe_params.ajax_url, {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.error) {
              throw new Error(data.error);
            }
            
            // Update button state
            addToCartButton.classList.remove('loading');
            addToCartButton.textContent = 'Added to Cart';
            
            // Reset button text after 2 seconds
            setTimeout(() => {
              addToCartButton.textContent = originalText;
            }, 2000);
            
            // Update mini cart
            if (data.fragments) {
              for (const key in data.fragments) {
                const fragment = document.querySelector(key);
                if (fragment) {
                  fragment.outerHTML = data.fragments[key];
                }
              }
            }
            
            // Show notification
            showNotification('Product added to cart successfully!', 'success');
            
            // Trigger event for other scripts
            document.dispatchEvent(new CustomEvent('aqualuxe_added_to_cart', {
              detail: { response: data }
            }));
          })
          .catch(error => {
            // Update button state
            addToCartButton.classList.remove('loading');
            addToCartButton.textContent = originalText;
            
            // Show error notification
            showNotification(error.message || 'Error adding product to cart. Please try again.', 'error');
          });
      });
    }
  });
  
  // Ajax add to cart for product archives
  document.addEventListener('click', function(e) {
    const addToCartButton = e.target.closest('.add_to_cart_button:not(.product_type_variable):not(.product_type_grouped)');
    
    if (addToCartButton) {
      e.preventDefault();
      
      if (addToCartButton.classList.contains('ajax_add_to_cart')) {
        // WooCommerce already handles this with its own AJAX
        return;
      }
      
      // Show loading state
      addToCartButton.classList.add('loading');
      const originalText = addToCartButton.textContent;
      addToCartButton.textContent = 'Adding...';
      
      // Get product data
      const productId = addToCartButton.getAttribute('data-product_id');
      const quantity = addToCartButton.getAttribute('data-quantity') || 1;
      
      // Prepare form data
      const formData = new FormData();
      formData.append('action', 'aqualuxe_ajax_add_to_cart');
      formData.append('product_id', productId);
      formData.append('quantity', quantity);
      
      // Send AJAX request
      fetch(aqualuxe_params.ajax_url, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            throw new Error(data.error);
          }
          
          // Update button state
          addToCartButton.classList.remove('loading');
          addToCartButton.textContent = 'Added to Cart';
          
          // Reset button text after 2 seconds
          setTimeout(() => {
            addToCartButton.textContent = originalText;
          }, 2000);
          
          // Update mini cart
          if (data.fragments) {
            for (const key in data.fragments) {
              const fragment = document.querySelector(key);
              if (fragment) {
                fragment.outerHTML = data.fragments[key];
              }
            }
          }
          
          // Show notification
          showNotification('Product added to cart successfully!', 'success');
          
          // Trigger event for other scripts
          document.dispatchEvent(new CustomEvent('aqualuxe_added_to_cart', {
            detail: { response: data }
          }));
        })
        .catch(error => {
          // Update button state
          addToCartButton.classList.remove('loading');
          addToCartButton.textContent = originalText;
          
          // Show error notification
          showNotification(error.message || 'Error adding product to cart. Please try again.', 'error');
        });
    }
  });
}

/**
 * Initialize Quantity Inputs
 */
function initQuantityInputs(container = 'body') {
  const quantityInputs = document.querySelectorAll(`${container} .quantity input[type="number"]`);
  
  quantityInputs.forEach(input => {
    // Add increment/decrement buttons if they don't exist
    if (!input.parentElement.querySelector('.quantity-button')) {
      const decrementButton = document.createElement('button');
      decrementButton.type = 'button';
      decrementButton.className = 'quantity-button decrement';
      decrementButton.textContent = '-';
      decrementButton.setAttribute('aria-label', 'Decrease quantity');
      
      const incrementButton = document.createElement('button');
      incrementButton.type = 'button';
      incrementButton.className = 'quantity-button increment';
      incrementButton.textContent = '+';
      incrementButton.setAttribute('aria-label', 'Increase quantity');
      
      input.parentElement.insertBefore(decrementButton, input);
      input.parentElement.appendChild(incrementButton);
      
      // Add event listeners
      decrementButton.addEventListener('click', function() {
        const currentValue = parseInt(input.value);
        const minValue = parseInt(input.getAttribute('min')) || 1;
        
        if (currentValue > minValue) {
          input.value = currentValue - 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
      
      incrementButton.addEventListener('click', function() {
        const currentValue = parseInt(input.value);
        const maxValue = parseInt(input.getAttribute('max'));
        
        if (!maxValue || currentValue < maxValue) {
          input.value = currentValue + 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
    }
    
    // Update cart when quantity changes in cart page
    if (document.body.classList.contains('woocommerce-cart')) {
      input.addEventListener('change', function() {
        const updateCartButton = document.querySelector('[name="update_cart"]');
        if (updateCartButton) {
          updateCartButton.disabled = false;
          updateCartButton.setAttribute('aria-disabled', 'false');
        }
      });
    }
  });
}

/**
 * Initialize Product Gallery
 */
function initProductGallery(container = '.woocommerce-product-gallery') {
  const galleries = document.querySelectorAll(container);
  
  galleries.forEach(gallery => {
    // Main image
    const mainImage = gallery.querySelector('.woocommerce-product-gallery__image');
    
    // Thumbnails
    const thumbnails = gallery.querySelectorAll('.woocommerce-product-gallery__image:not(:first-child), .woocommerce-product-gallery__thumb');
    
    thumbnails.forEach(thumbnail => {
      thumbnail.addEventListener('click', function(e) {
        e.preventDefault();
        
        const fullSizeUrl = this.getAttribute('data-full-size') || this.querySelector('a').getAttribute('href');
        const thumbnailUrl = this.querySelector('img').getAttribute('src');
        const caption = this.querySelector('img').getAttribute('alt');
        
        // Update main image
        const mainImg = mainImage.querySelector('img');
        mainImg.setAttribute('src', thumbnailUrl);
        mainImg.setAttribute('data-large_image', fullSizeUrl);
        mainImg.setAttribute('alt', caption);
        
        // Update zoom image if zoom is enabled
        const zoomTarget = mainImage.querySelector('.zoomImg');
        if (zoomTarget) {
          zoomTarget.setAttribute('src', fullSizeUrl);
        }
        
        // Update link
        const mainLink = mainImage.querySelector('a');
        if (mainLink) {
          mainLink.setAttribute('href', fullSizeUrl);
        }
        
        // Remove active class from all thumbnails
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        
        // Add active class to clicked thumbnail
        this.classList.add('active');
      });
    });
    
    // Initialize zoom if available
    if (typeof jQuery !== 'undefined' && jQuery.fn.zoom && window.innerWidth > 768) {
      jQuery(mainImage).zoom({
        touch: false
      });
    }
    
    // Initialize lightbox if available
    if (typeof jQuery !== 'undefined' && jQuery.fn.magnificPopup) {
      jQuery(gallery).find('a').magnificPopup({
        type: 'image',
        gallery: {
          enabled: true
        },
        image: {
          titleSrc: function(item) {
            return item.el.find('img').attr('alt');
          }
        }
      });
    }
  });
}

/**
 * Initialize Product Filters
 */
function initProductFilters() {
  const filterForm = document.querySelector('.aqualuxe-product-filters');
  
  if (filterForm) {
    // Price range slider
    const priceRange = filterForm.querySelector('.price-range');
    if (priceRange && typeof noUiSlider !== 'undefined') {
      const minPrice = parseInt(priceRange.getAttribute('data-min')) || 0;
      const maxPrice = parseInt(priceRange.getAttribute('data-max')) || 1000;
      const currentMinPrice = parseInt(priceRange.getAttribute('data-current-min')) || minPrice;
      const currentMaxPrice = parseInt(priceRange.getAttribute('data-current-max')) || maxPrice;
      
      noUiSlider.create(priceRange, {
        start: [currentMinPrice, currentMaxPrice],
        connect: true,
        step: 1,
        range: {
          'min': minPrice,
          'max': maxPrice
        },
        format: {
          to: value => Math.round(value),
          from: value => Math.round(value)
        }
      });
      
      const minPriceInput = document.getElementById('min_price');
      const maxPriceInput = document.getElementById('max_price');
      
      priceRange.noUiSlider.on('update', function(values, handle) {
        if (handle === 0) {
          minPriceInput.value = values[0];
        } else {
          maxPriceInput.value = values[1];
        }
        
        // Update price display
        const priceDisplay = document.querySelector('.price-range-display');
        if (priceDisplay) {
          const currencySymbol = priceDisplay.getAttribute('data-currency-symbol') || '$';
          priceDisplay.textContent = `${currencySymbol}${values[0]} - ${currencySymbol}${values[1]}`;
        }
      });
      
      // Update slider when inputs change
      minPriceInput.addEventListener('change', function() {
        priceRange.noUiSlider.set([this.value, null]);
      });
      
      maxPriceInput.addEventListener('change', function() {
        priceRange.noUiSlider.set([null, this.value]);
      });
    }
    
    // Ajax filtering
    filterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Show loading state
      const productsContainer = document.querySelector('.products');
      if (productsContainer) {
        productsContainer.classList.add('loading');
        productsContainer.style.opacity = '0.5';
      }
      
      // Get form data
      const formData = new FormData(filterForm);
      formData.append('action', 'aqualuxe_filter_products');
      
      // Send AJAX request
      fetch(aqualuxe_params.ajax_url, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            throw new Error(data.error);
          }
          
          // Update products container
          productsContainer.innerHTML = data.html;
          productsContainer.classList.remove('loading');
          productsContainer.style.opacity = '1';
          
          // Update URL
          if (data.url) {
            window.history.pushState({}, '', data.url);
          }
          
          // Update product count
          const productCount = document.querySelector('.woocommerce-result-count');
          if (productCount && data.count) {
            productCount.textContent = data.count;
          }
          
          // Trigger event for other scripts
          document.dispatchEvent(new CustomEvent('aqualuxe_products_filtered', {
            detail: { response: data }
          }));
        })
        .catch(error => {
          // Remove loading state
          productsContainer.classList.remove('loading');
          productsContainer.style.opacity = '1';
          
          // Show error notification
          showNotification(error.message || 'Error filtering products. Please try again.', 'error');
        });
    });
    
    // Instant filtering for checkboxes and radio buttons
    const instantFilters = filterForm.querySelectorAll('input[type="checkbox"], input[type="radio"]');
    instantFilters.forEach(filter => {
      filter.addEventListener('change', function() {
        filterForm.dispatchEvent(new Event('submit', { bubbles: true }));
      });
    });
    
    // Debounced filtering for text inputs
    const textFilters = filterForm.querySelectorAll('input[type="text"]:not([name="s"])');
    let debounceTimer;
    
    textFilters.forEach(filter => {
      filter.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
          filterForm.dispatchEvent(new Event('submit', { bubbles: true }));
        }, 500);
      });
    });
    
    // Reset filters
    const resetButton = filterForm.querySelector('.reset-filters');
    if (resetButton) {
      resetButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Reset form
        filterForm.reset();
        
        // Reset price range slider
        if (priceRange && priceRange.noUiSlider) {
          const minPrice = parseInt(priceRange.getAttribute('data-min')) || 0;
          const maxPrice = parseInt(priceRange.getAttribute('data-max')) || 1000;
          priceRange.noUiSlider.set([minPrice, maxPrice]);
        }
        
        // Submit form
        filterForm.dispatchEvent(new Event('submit', { bubbles: true }));
      });
    }
  }
}

/**
 * Initialize Wishlist
 */
function initWishlist() {
  // Toggle wishlist items
  document.addEventListener('click', function(e) {
    const wishlistToggle = e.target.closest('.wishlist-toggle');
    
    if (wishlistToggle) {
      e.preventDefault();
      
      const productId = wishlistToggle.getAttribute('data-product-id');
      
      // Show loading state
      wishlistToggle.classList.add('loading');
      
      // Prepare form data
      const formData = new FormData();
      formData.append('action', 'aqualuxe_toggle_wishlist');
      formData.append('product_id', productId);
      formData.append('security', aqualuxe_params.wishlist_nonce);
      
      // Send AJAX request
      fetch(aqualuxe_params.ajax_url, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            throw new Error(data.error);
          }
          
          // Update button state
          wishlistToggle.classList.remove('loading');
          
          if (data.status === 'added') {
            wishlistToggle.classList.add('in-wishlist');
            wishlistToggle.setAttribute('title', 'Remove from Wishlist');
            wishlistToggle.setAttribute('aria-label', 'Remove from Wishlist');
          } else {
            wishlistToggle.classList.remove('in-wishlist');
            wishlistToggle.setAttribute('title', 'Add to Wishlist');
            wishlistToggle.setAttribute('aria-label', 'Add to Wishlist');
          }
          
          // Update wishlist count
          const wishlistCount = document.querySelector('.wishlist-count');
          if (wishlistCount) {
            wishlistCount.textContent = data.count;
          }
          
          // Show notification
          showNotification(data.message, 'success');
          
          // Trigger event for other scripts
          document.dispatchEvent(new CustomEvent('aqualuxe_wishlist_updated', {
            detail: { response: data }
          }));
        })
        .catch(error => {
          // Update button state
          wishlistToggle.classList.remove('loading');
          
          // Show error notification
          showNotification(error.message || 'Error updating wishlist. Please try again.', 'error');
        });
    }
  });
}

/**
 * Initialize Mini Cart
 */
function initMiniCart() {
  const miniCartToggle = document.querySelector('.mini-cart-toggle');
  const miniCart = document.querySelector('.mini-cart');
  
  if (miniCartToggle && miniCart) {
    // Toggle mini cart
    miniCartToggle.addEventListener('click', function(e) {
      e.preventDefault();
      
      const isExpanded = miniCartToggle.getAttribute('aria-expanded') === 'true';
      miniCartToggle.setAttribute('aria-expanded', !isExpanded);
      miniCart.classList.toggle('hidden');
      
      // Prevent body scroll when mini cart is open
      if (!isExpanded) {
        document.body.classList.add('overflow-hidden');
      } else {
        document.body.classList.remove('overflow-hidden');
      }
    });
    
    // Close mini cart when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.mini-cart') && !e.target.closest('.mini-cart-toggle')) {
        miniCartToggle.setAttribute('aria-expanded', 'false');
        miniCart.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    });
    
    // Close mini cart on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && miniCartToggle.getAttribute('aria-expanded') === 'true') {
        miniCartToggle.setAttribute('aria-expanded', 'false');
        miniCart.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    });
    
    // Remove item from mini cart
    document.addEventListener('click', function(e) {
      const removeButton = e.target.closest('.mini-cart-item-remove');
      
      if (removeButton) {
        e.preventDefault();
        
        const cartItemKey = removeButton.getAttribute('data-cart-item-key');
        
        // Show loading state
        const cartItem = removeButton.closest('.mini-cart-item');
        cartItem.classList.add('loading');
        
        // Prepare form data
        const formData = new FormData();
        formData.append('action', 'aqualuxe_remove_from_cart');
        formData.append('cart_item_key', cartItemKey);
        formData.append('security', aqualuxe_params.cart_nonce);
        
        // Send AJAX request
        fetch(aqualuxe_params.ajax_url, {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.error) {
              throw new Error(data.error);
            }
            
            // Update mini cart
            if (data.fragments) {
              for (const key in data.fragments) {
                const fragment = document.querySelector(key);
                if (fragment) {
                  fragment.outerHTML = data.fragments[key];
                }
              }
            }
            
            // Show notification
            showNotification('Item removed from cart.', 'success');
            
            // Trigger event for other scripts
            document.dispatchEvent(new CustomEvent('aqualuxe_cart_updated', {
              detail: { response: data }
            }));
          })
          .catch(error => {
            // Remove loading state
            cartItem.classList.remove('loading');
            
            // Show error notification
            showNotification(error.message || 'Error removing item from cart. Please try again.', 'error');
          });
      }
    });
  }
}

/**
 * Initialize Checkout Enhancements
 */
function initCheckoutEnhancements() {
  if (document.body.classList.contains('woocommerce-checkout')) {
    // Toggle login form
    const showLoginLink = document.querySelector('.showlogin');
    if (showLoginLink) {
      showLoginLink.addEventListener('click', function(e) {
        e.preventDefault();
        const loginForm = document.querySelector('.login');
        loginForm.classList.toggle('hidden');
      });
    }
    
    // Toggle coupon form
    const showCouponLink = document.querySelector('.showcoupon');
    if (showCouponLink) {
      showCouponLink.addEventListener('click', function(e) {
        e.preventDefault();
        const couponForm = document.querySelector('.checkout_coupon');
        couponForm.classList.toggle('hidden');
      });
    }
    
    // Toggle shipping address
    const shipToDifferentAddressCheckbox = document.getElementById('ship-to-different-address-checkbox');
    if (shipToDifferentAddressCheckbox) {
      shipToDifferentAddressCheckbox.addEventListener('change', function() {
        const shippingAddress = document.querySelector('.shipping_address');
        if (this.checked) {
          shippingAddress.style.display = 'block';
        } else {
          shippingAddress.style.display = 'none';
        }
      });
      
      // Trigger on page load
      shipToDifferentAddressCheckbox.dispatchEvent(new Event('change'));
    }
    
    // Payment method selection
    const paymentMethods = document.querySelectorAll('.wc_payment_method input[type="radio"]');
    paymentMethods.forEach(method => {
      method.addEventListener('change', function() {
        const paymentBoxes = document.querySelectorAll('.payment_box');
        paymentBoxes.forEach(box => box.style.display = 'none');
        
        const selectedPaymentBox = document.querySelector(`.payment_box.payment_method_${this.value}`);
        if (selectedPaymentBox) {
          selectedPaymentBox.style.display = 'block';
        }
      });
    });
  }
}

/**
 * Show Notification
 */
function showNotification(message, type = 'success', duration = 3000) {
  // Remove existing notifications
  const existingNotifications = document.querySelectorAll('.aqualuxe-notification');
  existingNotifications.forEach(notification => {
    notification.classList.add('fade-out');
    setTimeout(() => {
      notification.remove();
    }, 300);
  });
  
  // Create notification element
  const notification = document.createElement('div');
  notification.className = `aqualuxe-notification ${type} fixed top-4 right-4 p-4 rounded shadow-md z-50 fade-in`;
  
  // Set background color based on type
  if (type === 'success') {
    notification.classList.add('bg-green-500', 'text-white');
  } else if (type === 'error') {
    notification.classList.add('bg-red-500', 'text-white');
  } else if (type === 'info') {
    notification.classList.add('bg-blue-500', 'text-white');
  } else if (type === 'warning') {
    notification.classList.add('bg-yellow-500', 'text-white');
  }
  
  // Add message
  notification.textContent = message;
  
  // Add close button
  const closeButton = document.createElement('button');
  closeButton.className = 'absolute top-1 right-1 text-white';
  closeButton.innerHTML = '&times;';
  closeButton.setAttribute('aria-label', 'Close notification');
  closeButton.addEventListener('click', function() {
    notification.classList.add('fade-out');
    setTimeout(() => {
      notification.remove();
    }, 300);
  });
  
  notification.appendChild(closeButton);
  
  // Add to DOM
  document.body.appendChild(notification);
  
  // Remove after duration
  setTimeout(() => {
    notification.classList.add('fade-out');
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, duration);
}

// Export functions
export {
  initWooCommerceFeatures,
  initQuickView,
  initAddToCart,
  initQuantityInputs,
  initProductGallery,
  initProductFilters,
  initWishlist,
  initMiniCart,
  initCheckoutEnhancements,
  showNotification
};