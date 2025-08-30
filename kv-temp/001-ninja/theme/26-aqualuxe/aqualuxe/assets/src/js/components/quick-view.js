/**
 * Quick View functionality
 * 
 * Handles product quick view modal functionality for WooCommerce
 */

export default (function() {
  // Initialize quick view when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initQuickView();
  });

  /**
   * Initialize quick view functionality
   */
  function initQuickView() {
    // Add event listeners to quick view buttons
    document.addEventListener('click', function(e) {
      const quickViewButton = e.target.closest('.quick-view');
      
      if (quickViewButton) {
        e.preventDefault();
        openQuickView(quickViewButton);
      }
    });
  }
  
  /**
   * Open quick view modal
   * 
   * @param {HTMLElement} button - Quick view button
   */
  function openQuickView(button) {
    const productId = button.dataset.productId;
    
    if (!productId) {
      console.error('Product ID not found');
      return;
    }
    
    // Create modal if it doesn't exist
    let modal = document.getElementById('quick-view-modal');
    
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'quick-view-modal';
      modal.className = 'quick-view-modal';
      modal.innerHTML = `
        <div class="quick-view-container">
          <div class="quick-view-content"></div>
          <button class="quick-view-close" aria-label="Close">&times;</button>
        </div>
      `;
      document.body.appendChild(modal);
      
      // Add event listener to close button
      modal.querySelector('.quick-view-close').addEventListener('click', closeQuickView);
      
      // Close modal when clicking outside content
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          closeQuickView();
        }
      });
      
      // Close modal on ESC key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) {
          closeQuickView();
        }
      });
    }
    
    const content = modal.querySelector('.quick-view-content');
    
    // Show loading state
    content.innerHTML = `
      <div class="quick-view-loading">
        <div class="spinner"></div>
        <p>${window.aqualuxeQuickView?.i18n?.loading || 'Loading...'}</p>
      </div>
    `;
    
    // Open modal
    modal.classList.add('open');
    document.body.classList.add('quick-view-open');
    
    // Fetch product data
    fetchProductData(productId)
      .then(response => {
        if (response.success) {
          content.innerHTML = response.data.content;
          
          // Initialize product gallery if available
          initQuickViewGallery();
          
          // Initialize product variations if available
          initQuickViewVariations();
          
          // Initialize quantity buttons
          initQuickViewQuantityButtons();
          
          // Initialize add to cart
          initQuickViewAddToCart();
        } else {
          content.innerHTML = `
            <div class="quick-view-error">
              <p>${response.data.message || 'Error loading product data.'}</p>
            </div>
          `;
        }
      })
      .catch(error => {
        console.error('Quick view error:', error);
        content.innerHTML = `
          <div class="quick-view-error">
            <p>Error loading product data. Please try again.</p>
          </div>
        `;
      });
  }
  
  /**
   * Close quick view modal
   */
  function closeQuickView() {
    const modal = document.getElementById('quick-view-modal');
    
    if (!modal) {
      return;
    }
    
    modal.classList.remove('open');
    document.body.classList.remove('quick-view-open');
    
    // Clear content after animation
    setTimeout(() => {
      const content = modal.querySelector('.quick-view-content');
      if (content) {
        content.innerHTML = '';
      }
    }, 300);
  }
  
  /**
   * Fetch product data via AJAX
   * 
   * @param {string|number} productId - Product ID
   * @return {Promise} - Promise resolving to product data
   */
  function fetchProductData(productId) {
    // Create form data
    const formData = new FormData();
    formData.append('action', 'aqualuxe_quick_view');
    formData.append('product_id', productId);
    formData.append('nonce', window.aqualuxeQuickView?.nonce || '');
    
    // Send AJAX request
    return fetch(window.aqualuxeQuickView?.ajaxurl || window.ajaxurl, {
      method: 'POST',
      credentials: 'same-origin',
      body: formData
    })
    .then(response => response.json());
  }
  
  /**
   * Initialize quick view gallery
   */
  function initQuickViewGallery() {
    const gallery = document.querySelector('.quick-view-content .woocommerce-product-gallery');
    
    if (!gallery) {
      return;
    }
    
    // Initialize flexslider if available
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.flexslider === 'function') {
      jQuery('.quick-view-gallery').flexslider({
        animation: 'slide',
        controlNav: 'thumbnails'
      });
    }
    
    // Initialize zoom if available
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.zoom === 'function') {
      jQuery('.woocommerce-product-gallery__image').each(function() {
        jQuery(this).zoom({
          url: jQuery(this).find('img').attr('data-large_image'),
          touch: false
        });
      });
    }
  }
  
  /**
   * Initialize quick view variations
   */
  function initQuickViewVariations() {
    const variationForm = document.querySelector('.quick-view-content .variations_form');
    
    if (!variationForm || typeof jQuery === 'undefined' || typeof jQuery.fn.wc_variation_form === 'undefined') {
      return;
    }
    
    jQuery(variationForm).wc_variation_form();
  }
  
  /**
   * Initialize quick view quantity buttons
   */
  function initQuickViewQuantityButtons() {
    const quantityWrapper = document.querySelector('.quick-view-content .quantity');
    
    if (!quantityWrapper) {
      return;
    }
    
    const input = quantityWrapper.querySelector('input.qty');
    
    if (!input) {
      return;
    }
    
    // Check if buttons already exist
    if (quantityWrapper.querySelector('.quantity-button')) {
      return;
    }
    
    // Create buttons
    const minusButton = document.createElement('button');
    minusButton.type = 'button';
    minusButton.className = 'quantity-button minus';
    minusButton.textContent = '-';
    
    const plusButton = document.createElement('button');
    plusButton.type = 'button';
    plusButton.className = 'quantity-button plus';
    plusButton.textContent = '+';
    
    // Add buttons to DOM
    input.parentNode.insertBefore(minusButton, input);
    input.parentNode.insertBefore(plusButton, input.nextSibling);
    
    // Handle minus button click
    minusButton.addEventListener('click', function() {
      const currentValue = parseFloat(input.value);
      const min = parseFloat(input.getAttribute('min')) || 1;
      
      if (currentValue > min) {
        input.value = currentValue - 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
    
    // Handle plus button click
    plusButton.addEventListener('click', function() {
      const currentValue = parseFloat(input.value);
      const max = parseFloat(input.getAttribute('max'));
      
      if (!max || currentValue < max) {
        input.value = currentValue + 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
  }
  
  /**
   * Initialize quick view add to cart
   */
  function initQuickViewAddToCart() {
    const form = document.querySelector('.quick-view-content .cart');
    
    if (!form) {
      return;
    }
    
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const button = form.querySelector('button[type="submit"]');
      
      // Show loading state
      if (button) {
        button.classList.add('loading');
        button.disabled = true;
      }
      
      // Get form data
      const formData = new FormData(form);
      formData.append('add-to-cart', formData.get('product_id') || form.querySelector('[name="product_id"]').value);
      
      // Send AJAX request
      fetch(window.wc_add_to_cart_params?.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart') || window.location.href, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      })
      .then(response => response.json())
      .then(response => {
        // Handle error
        if (response.error) {
          throw new Error(response.error);
        }
        
        // Show success message
        const modal = document.getElementById('quick-view-modal');
        const content = modal.querySelector('.quick-view-content');
        
        content.innerHTML = `
          <div class="quick-view-success">
            <p>${window.wc_add_to_cart_params?.i18n_added_to_cart || 'Product added to cart.'}</p>
            <div class="quick-view-buttons">
              <a href="${window.wc_add_to_cart_params?.cart_url || '/cart/'}" class="button view-cart">
                ${window.wc_add_to_cart_params?.i18n_view_cart || 'View Cart'}
              </a>
              <a href="#" class="button continue-shopping">
                ${window.wc_add_to_cart_params?.i18n_continue_shopping || 'Continue Shopping'}
              </a>
            </div>
          </div>
        `;
        
        // Add event listener to continue shopping button
        const continueButton = content.querySelector('.continue-shopping');
        if (continueButton) {
          continueButton.addEventListener('click', function(e) {
            e.preventDefault();
            closeQuickView();
          });
        }
        
        // Update cart fragments
        if (response.fragments) {
          updateCartFragments(response.fragments);
        }
        
        // Trigger added_to_cart event
        document.body.dispatchEvent(new CustomEvent('added_to_cart', {
          detail: {
            fragments: response.fragments,
            cart_hash: response.cart_hash,
            button: button
          }
        }));
      })
      .catch(error => {
        console.error('Add to cart error:', error);
        
        // Reset button state
        if (button) {
          button.classList.remove('loading');
          button.disabled = false;
        }
        
        // Show error message
        alert(error.message || window.wc_add_to_cart_params?.i18n_ajax_error || 'Error adding to cart. Please try again.');
      });
    });
  }
  
  /**
   * Update cart fragments
   * 
   * @param {Object} fragments - WooCommerce fragments
   */
  function updateCartFragments(fragments) {
    if (!fragments) {
      return;
    }
    
    Object.keys(fragments).forEach(selector => {
      const fragment = fragments[selector];
      const elements = document.querySelectorAll(selector);
      
      elements.forEach(element => {
        element.outerHTML = fragment;
      });
    });
  }
  
  // Return public methods
  return {
    initQuickView,
    openQuickView,
    closeQuickView
  };
})();