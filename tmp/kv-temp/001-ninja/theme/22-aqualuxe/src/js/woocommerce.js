/**
 * AquaLuxe Theme WooCommerce JavaScript
 *
 * Handles WooCommerce specific functionality.
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize quantity inputs
  initQuantityInputs();
  
  // Initialize AJAX add to cart
  initAjaxAddToCart();
  
  // Initialize wishlist functionality
  initWishlist();
  
  // Initialize product gallery
  initProductGallery();
  
  // Initialize product filters
  initProductFilters();
  
  // Initialize price range slider
  initPriceRangeSlider();
});

/**
 * Initialize quantity inputs
 */
function initQuantityInputs() {
  const quantityInputs = document.querySelectorAll('.quantity');
  
  quantityInputs.forEach(wrapper => {
    const input = wrapper.querySelector('input[type="number"]');
    const minusBtn = document.createElement('button');
    const plusBtn = document.createElement('button');
    
    if (!input) return;
    
    // Set attributes for minus button
    minusBtn.type = 'button';
    minusBtn.className = 'quantity-btn quantity-btn-minus';
    minusBtn.textContent = '-';
    minusBtn.setAttribute('aria-label', 'Decrease quantity');
    
    // Set attributes for plus button
    plusBtn.type = 'button';
    plusBtn.className = 'quantity-btn quantity-btn-plus';
    plusBtn.textContent = '+';
    plusBtn.setAttribute('aria-label', 'Increase quantity');
    
    // Add event listener for minus button
    minusBtn.addEventListener('click', () => {
      const currentValue = parseInt(input.value, 10);
      const minValue = parseInt(input.min, 10) || 1;
      
      if (currentValue > minValue) {
        input.value = currentValue - 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
    
    // Add event listener for plus button
    plusBtn.addEventListener('click', () => {
      const currentValue = parseInt(input.value, 10);
      const maxValue = parseInt(input.max, 10) || 999;
      
      if (currentValue < maxValue) {
        input.value = currentValue + 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
    
    // Insert buttons
    wrapper.insertBefore(minusBtn, input);
    wrapper.appendChild(plusBtn);
  });
}

/**
 * Initialize AJAX add to cart
 */
function initAjaxAddToCart() {
  const addToCartButtons = document.querySelectorAll('.ajax_add_to_cart');
  
  addToCartButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      const productId = this.getAttribute('data-product_id');
      const quantity = this.getAttribute('data-quantity') || 1;
      
      // Add loading state
      this.classList.add('loading');
      
      // AJAX request to add to cart
      const data = new FormData();
      data.append('action', 'aqualuxe_ajax_add_to_cart');
      data.append('product_id', productId);
      data.append('quantity', quantity);
      
      fetch(aqualuxe_woocommerce.ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        body: data
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update cart fragments
          if (data.fragments) {
            jQuery.each(data.fragments, function(key, value) {
              jQuery(key).replaceWith(value);
            });
          }
          
          // Trigger event for other scripts
          document.dispatchEvent(new CustomEvent('aqualuxeAddedToCart', { 
            detail: { 
              productId: productId,
              quantity: quantity
            } 
          }));
          
          // Show success message
          showNotification('Product added to cart', 'success');
        } else {
          // Show error message
          showNotification(data.message || 'Error adding product to cart', 'error');
        }
      })
      .catch(error => {
        console.error('Error adding to cart:', error);
        showNotification('Error adding product to cart', 'error');
      })
      .finally(() => {
        // Remove loading state
        this.classList.remove('loading');
      });
    });
  });
}

/**
 * Initialize wishlist functionality
 */
function initWishlist() {
  const wishlistButtons = document.querySelectorAll('.aqualuxe-wishlist-button');
  
  wishlistButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      const productId = this.getAttribute('data-product-id');
      const isInWishlist = this.classList.contains('added');
      
      // Add loading state
      this.classList.add('loading');
      
      // AJAX request to toggle wishlist
      const data = new FormData();
      data.append('action', 'aqualuxe_toggle_wishlist');
      data.append('product_id', productId);
      data.append('remove', isInWishlist ? '1' : '0');
      
      fetch(aqualuxe_woocommerce.ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        body: data
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Toggle wishlist state
          this.classList.toggle('added');
          
          // Update icon
          if (data.in_wishlist) {
            this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>';
            showNotification('Product added to wishlist', 'success');
          } else {
            this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>';
            showNotification('Product removed from wishlist', 'success');
          }
          
          // Update wishlist count
          const wishlistCount = document.querySelector('.wishlist-count');
          if (wishlistCount) {
            wishlistCount.textContent = data.wishlist_count;
          }
        } else {
          showNotification(data.message || 'Error updating wishlist', 'error');
        }
      })
      .catch(error => {
        console.error('Error updating wishlist:', error);
        showNotification('Error updating wishlist', 'error');
      })
      .finally(() => {
        // Remove loading state
        this.classList.remove('loading');
      });
    });
  });
}

/**
 * Initialize product gallery
 */
function initProductGallery() {
  const productGallery = document.querySelector('.woocommerce-product-gallery');
  
  if (!productGallery) return;
  
  const mainImage = productGallery.querySelector('.woocommerce-product-gallery__image');
  const thumbnails = productGallery.querySelectorAll('.woocommerce-product-gallery__thumbnail');
  
  thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', function(e) {
      e.preventDefault();
      
      const fullImage = this.getAttribute('data-full-image');
      const fullImageSrc = this.getAttribute('data-full-image-src');
      const caption = this.getAttribute('data-caption');
      
      // Update main image
      if (mainImage) {
        const mainImageLink = mainImage.querySelector('a');
        const mainImageImg = mainImage.querySelector('img');
        
        if (mainImageLink) {
          mainImageLink.href = fullImage;
        }
        
        if (mainImageImg) {
          mainImageImg.src = fullImageSrc;
          mainImageImg.alt = caption || '';
        }
      }
      
      // Update active thumbnail
      thumbnails.forEach(thumb => {
        thumb.classList.remove('active');
      });
      
      this.classList.add('active');
    });
  });
}

/**
 * Initialize product filters
 */
function initProductFilters() {
  const filterForm = document.querySelector('.aqualuxe-product-filters');
  
  if (!filterForm) return;
  
  const filterInputs = filterForm.querySelectorAll('input, select');
  
  filterInputs.forEach(input => {
    input.addEventListener('change', function() {
      // Submit form on change
      filterForm.submit();
    });
  });
}

/**
 * Initialize price range slider
 */
function initPriceRangeSlider() {
  const priceSlider = document.querySelector('.price_slider');
  
  if (!priceSlider) return;
  
  // This functionality typically relies on jQuery UI
  // For a custom implementation, we would need to use a range slider library
  // or implement our own range slider
}

/**
 * Show notification
 * 
 * @param {string} message - Notification message
 * @param {string} type - Notification type (success, error, info)
 */
function showNotification(message, type = 'info') {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = `aqualuxe-notification aqualuxe-notification-${type}`;
  notification.textContent = message;
  
  // Add close button
  const closeButton = document.createElement('button');
  closeButton.className = 'aqualuxe-notification-close';
  closeButton.innerHTML = '&times;';
  closeButton.setAttribute('aria-label', 'Close notification');
  
  closeButton.addEventListener('click', () => {
    document.body.removeChild(notification);
  });
  
  notification.appendChild(closeButton);
  
  // Add to DOM
  document.body.appendChild(notification);
  
  // Auto-remove after 5 seconds
  setTimeout(() => {
    if (document.body.contains(notification)) {
      document.body.removeChild(notification);
    }
  }, 5000);
}