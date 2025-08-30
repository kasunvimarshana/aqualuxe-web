/**
 * WooCommerce functionality
 * 
 * Handles WooCommerce specific JavaScript functionality
 */

export default (function() {
  // Initialize WooCommerce functionality when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initQuantityButtons();
    initCartUpdates();
    initProductGallery();
    initProductFilters();
    initPriceRangeSlider();
  });

  /**
   * Initialize quantity buttons
   */
  function initQuantityButtons() {
    const quantities = document.querySelectorAll('.quantity');
    
    quantities.forEach(quantity => {
      // Skip if buttons already exist
      if (quantity.querySelector('.quantity-button')) {
        return;
      }
      
      const input = quantity.querySelector('input.qty');
      
      if (!input) {
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
    });
  }
  
  /**
   * Initialize cart updates
   */
  function initCartUpdates() {
    // Update cart count when products are added to cart
    document.body.addEventListener('added_to_cart', function(event, fragments) {
      updateCartCount(fragments);
    });
    
    // Update cart count when fragments are loaded or refreshed
    document.body.addEventListener('wc_fragments_loaded', function(event, fragments) {
      updateCartCount(fragments);
    });
    
    document.body.addEventListener('wc_fragments_refreshed', function(event, fragments) {
      updateCartCount(fragments);
    });
    
    /**
     * Update cart count from fragments
     * 
     * @param {Object} fragments - WooCommerce fragments
     */
    function updateCartCount(fragments) {
      if (!fragments) {
        return;
      }
      
      const cartCountElements = document.querySelectorAll('.cart-count');
      
      if (cartCountElements.length === 0) {
        return;
      }
      
      // Get cart count from fragments
      let count = 0;
      
      if (fragments['div.widget_shopping_cart_content']) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = fragments['div.widget_shopping_cart_content'];
        
        // Count items in cart
        const cartItems = tempDiv.querySelectorAll('.mini_cart_item');
        count = cartItems.length;
      }
      
      // Update all cart count elements
      cartCountElements.forEach(element => {
        element.textContent = count;
        
        // Add animation
        element.classList.add('pulse');
        setTimeout(() => {
          element.classList.remove('pulse');
        }, 1000);
      });
    }
  }
  
  /**
   * Initialize product gallery
   */
  function initProductGallery() {
    const galleries = document.querySelectorAll('.woocommerce-product-gallery');
    
    if (galleries.length === 0) {
      return;
    }
    
    galleries.forEach(gallery => {
      // Initialize zoom if available
      if (typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.zoom === 'function') {
        const zoomImages = gallery.querySelectorAll('.woocommerce-product-gallery__image');
        
        zoomImages.forEach(image => {
          const img = image.querySelector('img');
          
          if (img && img.getAttribute('data-large_image')) {
            window.jQuery(image).zoom({
              url: img.getAttribute('data-large_image'),
              touch: false
            });
          }
        });
      }
      
      // Initialize lightbox if available
      if (typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.lightbox === 'function') {
        window.jQuery('.woocommerce-product-gallery__image a').lightbox({
          navigationOnTop: true
        });
      }
    });
  }
  
  /**
   * Initialize product filters
   */
  function initProductFilters() {
    const filterWidgets = document.querySelectorAll('.aqualuxe-filter-widget');
    
    filterWidgets.forEach(widget => {
      const title = widget.querySelector('.filter-title');
      const content = widget.querySelector('.filter-content');
      
      if (!title || !content) {
        return;
      }
      
      // Toggle filter content on title click
      title.addEventListener('click', function() {
        title.classList.toggle('active');
        
        if (content.style.display === 'none' || getComputedStyle(content).display === 'none') {
          content.style.display = 'block';
        } else {
          content.style.display = 'none';
        }
      });
    });
    
    // Handle filter form submission
    const filterForm = document.querySelector('.aqualuxe-filter-form');
    
    if (filterForm) {
      filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const productsContainer = document.querySelector('.products');
        
        if (!productsContainer) {
          return;
        }
        
        // Add loading overlay
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'aqualuxe-loading-overlay';
        loadingOverlay.innerHTML = '<div class="spinner"></div>';
        productsContainer.appendChild(loadingOverlay);
        
        // Get form data
        const formData = new FormData(filterForm);
        formData.append('action', 'aqualuxe_filter_products');
        
        // Convert FormData to URL params
        const params = new URLSearchParams(formData);
        
        // Send AJAX request
        fetch(woocommerce_params.ajax_url, {
          method: 'POST',
          credentials: 'same-origin',
          body: params
        })
        .then(response => response.text())
        .then(html => {
          // Replace products
          productsContainer.innerHTML = html;
          
          // Scroll to top of products
          productsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
          
          // Update URL
          const newUrl = filterForm.getAttribute('action') + '?' + params.toString();
          window.history.pushState({}, '', newUrl);
        })
        .catch(error => {
          console.error('Error filtering products:', error);
          loadingOverlay.remove();
          alert('Error filtering products. Please try again.');
        });
      });
    }
  }
  
  /**
   * Initialize price range slider
   */
  function initPriceRangeSlider() {
    const priceRangeSliders = document.querySelectorAll('.price-range-slider');
    
    if (priceRangeSliders.length === 0 || typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.slider === 'undefined') {
      return;
    }
    
    priceRangeSliders.forEach(slider => {
      const minInput = document.querySelector(slider.getAttribute('data-min-input'));
      const maxInput = document.querySelector(slider.getAttribute('data-max-input'));
      const displayElement = document.querySelector(slider.getAttribute('data-display'));
      
      if (!minInput || !maxInput) {
        return;
      }
      
      const min = parseFloat(slider.getAttribute('data-min')) || 0;
      const max = parseFloat(slider.getAttribute('data-max')) || 1000;
      const currentMin = parseFloat(minInput.value) || min;
      const currentMax = parseFloat(maxInput.value) || max;
      
      // Initialize slider
      window.jQuery(slider).slider({
        range: true,
        min: min,
        max: max,
        values: [currentMin, currentMax],
        slide: function(event, ui) {
          minInput.value = ui.values[0];
          maxInput.value = ui.values[1];
          
          // Update display if available
          if (displayElement) {
            displayElement.innerHTML = formatPrice(ui.values[0]) + ' - ' + formatPrice(ui.values[1]);
          }
        }
      });
      
      // Update slider when inputs change
      minInput.addEventListener('change', function() {
        const value = parseFloat(this.value) || min;
        window.jQuery(slider).slider('values', 0, value);
        
        // Update display if available
        if (displayElement) {
          displayElement.innerHTML = formatPrice(value) + ' - ' + formatPrice(window.jQuery(slider).slider('values', 1));
        }
      });
      
      maxInput.addEventListener('change', function() {
        const value = parseFloat(this.value) || max;
        window.jQuery(slider).slider('values', 1, value);
        
        // Update display if available
        if (displayElement) {
          displayElement.innerHTML = formatPrice(window.jQuery(slider).slider('values', 0)) + ' - ' + formatPrice(value);
        }
      });
      
      // Initialize display if available
      if (displayElement) {
        displayElement.innerHTML = formatPrice(currentMin) + ' - ' + formatPrice(currentMax);
      }
    });
  }
  
  /**
   * Format price with currency symbol
   * 
   * @param {number} price - Price to format
   * @return {string} - Formatted price
   */
  function formatPrice(price) {
    const currencySymbol = woocommerce_params.currency_symbol || '$';
    const formattedPrice = parseFloat(price).toFixed(2);
    
    return currencySymbol + formattedPrice;
  }
  
  // Return public methods
  return {
    initQuantityButtons,
    initCartUpdates,
    initProductGallery,
    initProductFilters,
    initPriceRangeSlider
  };
})();