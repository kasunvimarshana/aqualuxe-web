/**
 * AquaLuxe Theme WooCommerce JavaScript
 * 
 * This file contains WooCommerce-specific functionality for the AquaLuxe theme.
 */

document.addEventListener('DOMContentLoaded', () => {
  // Quick view modal functionality
  initQuickView();
  
  // Quantity input enhancements
  initQuantityInputs();
  
  // Product image gallery
  initProductGallery();
  
  // Advanced filtering
  initAdvancedFiltering();
  
  // Wishlist functionality
  initWishlist();
  
  // Product tabs
  initProductTabs();
  
  // Ajax add to cart
  initAjaxAddToCart();
});

/**
 * Initialize quick view modal functionality
 */
function initQuickView() {
  const quickViewButtons = document.querySelectorAll('.quick-view-button');
  
  if (quickViewButtons.length === 0) return;
  
  quickViewButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      
      const productId = button.dataset.productId;
      const modal = document.getElementById('quick-view-modal');
      const modalContent = document.getElementById('quick-view-content');
      
      if (!modal || !modalContent) return;
      
      // Show loading state
      modalContent.innerHTML = '<div class="flex justify-center items-center p-12"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div></div>';
      modal.classList.remove('hidden');
      
      // Fetch product data
      fetch(`/wp-json/wc/v3/products/${productId}`)
        .then(response => response.json())
        .then(data => {
          // Populate modal with product data
          modalContent.innerHTML = createQuickViewHTML(data);
          
          // Initialize gallery in quick view
          initQuickViewGallery();
          
          // Initialize quantity inputs in quick view
          initQuantityInputs(modalContent);
        })
        .catch(error => {
          console.error('Error fetching product data:', error);
          modalContent.innerHTML = '<p class="text-red-500 p-6">Error loading product data. Please try again.</p>';
        });
    });
  });
  
  // Close modal on background click or close button
  const modal = document.getElementById('quick-view-modal');
  const closeButton = document.getElementById('quick-view-close');
  
  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
      }
    });
  }
  
  if (closeButton) {
    closeButton.addEventListener('click', () => {
      modal.classList.add('hidden');
    });
  }
  
  // Close on escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
      modal.classList.add('hidden');
    }
  });
}

/**
 * Create HTML for quick view modal
 */
function createQuickViewHTML(product) {
  return `
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="quick-view-gallery">
        <div class="main-image mb-4">
          <img src="${product.images[0]?.src}" alt="${product.name}" class="w-full h-auto rounded-lg">
        </div>
        <div class="thumbnails flex space-x-2">
          ${product.images.map(image => `
            <div class="thumbnail cursor-pointer">
              <img src="${image.src}" alt="" class="w-16 h-16 object-cover rounded-md">
            </div>
          `).join('')}
        </div>
      </div>
      <div class="product-info">
        <h2 class="text-2xl font-bold mb-2">${product.name}</h2>
        <div class="price text-xl mb-4">${product.price_html}</div>
        <div class="description mb-6">${product.short_description}</div>
        <form class="cart mb-4">
          <div class="quantity-wrapper flex items-center mb-4">
            <label for="quantity" class="mr-4">Quantity</label>
            <div class="quantity flex">
              <button type="button" class="quantity-minus bg-gray-200 dark:bg-dark-600 px-3 py-1 rounded-l-md">-</button>
              <input type="number" id="quantity" class="quantity-input w-16 text-center border-gray-200 dark:border-dark-600 border-y" value="1" min="1">
              <button type="button" class="quantity-plus bg-gray-200 dark:bg-dark-600 px-3 py-1 rounded-r-md">+</button>
            </div>
          </div>
          <button type="submit" class="btn-primary add-to-cart w-full" data-product-id="${product.id}">
            Add to Cart
          </button>
        </form>
        <div class="meta text-sm text-gray-600 dark:text-gray-400">
          <p class="sku mb-1"><span class="font-medium">SKU:</span> ${product.sku}</p>
          <p class="categories mb-1"><span class="font-medium">Category:</span> ${product.categories.map(cat => cat.name).join(', ')}</p>
          <p class="tags"><span class="font-medium">Tags:</span> ${product.tags.map(tag => tag.name).join(', ')}</p>
        </div>
      </div>
    </div>
  `;
}

/**
 * Initialize gallery in quick view modal
 */
function initQuickViewGallery() {
  const gallery = document.querySelector('.quick-view-gallery');
  
  if (!gallery) return;
  
  const mainImage = gallery.querySelector('.main-image img');
  const thumbnails = gallery.querySelectorAll('.thumbnail img');
  
  thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', () => {
      mainImage.src = thumbnail.src;
    });
  });
}

/**
 * Initialize quantity input functionality
 */
function initQuantityInputs(container = document) {
  const quantityWrappers = container.querySelectorAll('.quantity');
  
  quantityWrappers.forEach(wrapper => {
    const minusButton = wrapper.querySelector('.quantity-minus');
    const plusButton = wrapper.querySelector('.quantity-plus');
    const input = wrapper.querySelector('.quantity-input');
    
    if (!minusButton || !plusButton || !input) return;
    
    minusButton.addEventListener('click', () => {
      const currentValue = parseInt(input.value);
      if (currentValue > parseInt(input.min)) {
        input.value = currentValue - 1;
        input.dispatchEvent(new Event('change'));
      }
    });
    
    plusButton.addEventListener('click', () => {
      const currentValue = parseInt(input.value);
      input.value = currentValue + 1;
      input.dispatchEvent(new Event('change'));
    });
  });
}

/**
 * Initialize product gallery with zoom and lightbox
 */
function initProductGallery() {
  const gallery = document.querySelector('.woocommerce-product-gallery');
  
  if (!gallery) return;
  
  const mainImage = gallery.querySelector('.woocommerce-product-gallery__image img');
  const thumbnails = gallery.querySelectorAll('.woocommerce-product-gallery__thumbnails img');
  
  // Handle thumbnail clicks
  thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', () => {
      const fullSizeUrl = thumbnail.dataset.large;
      mainImage.src = fullSizeUrl;
      mainImage.dataset.large = fullSizeUrl;
      
      // Update active thumbnail
      thumbnails.forEach(t => t.parentElement.classList.remove('active'));
      thumbnail.parentElement.classList.add('active');
    });
  });
  
  // Image zoom on hover (desktop only)
  if (window.innerWidth > 768 && mainImage) {
    mainImage.addEventListener('mousemove', (e) => {
      const { left, top, width, height } = mainImage.getBoundingClientRect();
      const x = (e.clientX - left) / width * 100;
      const y = (e.clientY - top) / height * 100;
      
      mainImage.style.transformOrigin = `${x}% ${y}%`;
    });
    
    mainImage.addEventListener('mouseenter', () => {
      mainImage.style.transform = 'scale(1.5)';
    });
    
    mainImage.addEventListener('mouseleave', () => {
      mainImage.style.transform = 'scale(1)';
    });
  }
  
  // Lightbox functionality
  if (mainImage) {
    mainImage.addEventListener('click', () => {
      const lightbox = document.createElement('div');
      lightbox.className = 'product-lightbox fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center';
      
      const img = document.createElement('img');
      img.src = mainImage.dataset.large || mainImage.src;
      img.className = 'max-h-[90vh] max-w-[90vw] object-contain';
      
      const close = document.createElement('button');
      close.innerHTML = '&times;';
      close.className = 'absolute top-4 right-4 text-white text-4xl';
      close.addEventListener('click', () => {
        document.body.removeChild(lightbox);
      });
      
      lightbox.appendChild(img);
      lightbox.appendChild(close);
      
      lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
          document.body.removeChild(lightbox);
        }
      });
      
      document.body.appendChild(lightbox);
    });
  }
}

/**
 * Initialize advanced filtering for shop pages
 */
function initAdvancedFiltering() {
  const filterForm = document.querySelector('.shop-filters');
  
  if (!filterForm) return;
  
  // Price range slider
  const priceSlider = filterForm.querySelector('.price-range-slider');
  
  if (priceSlider) {
    const minInput = filterForm.querySelector('input[name="min_price"]');
    const maxInput = filterForm.querySelector('input[name="max_price"]');
    const minDisplay = filterForm.querySelector('.min-price-display');
    const maxDisplay = filterForm.querySelector('.max-price-display');
    
    // Initialize price slider (using noUiSlider or similar)
    // This is a placeholder - you would need to include noUiSlider library
    if (window.noUiSlider) {
      noUiSlider.create(priceSlider, {
        start: [parseInt(minInput.value), parseInt(maxInput.value)],
        connect: true,
        range: {
          'min': parseInt(priceSlider.dataset.min),
          'max': parseInt(priceSlider.dataset.max)
        }
      });
      
      priceSlider.noUiSlider.on('update', (values, handle) => {
        const value = Math.round(values[handle]);
        
        if (handle === 0) {
          minInput.value = value;
          minDisplay.textContent = value;
        } else {
          maxInput.value = value;
          maxDisplay.textContent = value;
        }
      });
    }
  }
  
  // AJAX filtering
  const filterInputs = filterForm.querySelectorAll('input[type="checkbox"], input[type="radio"], select');
  
  filterInputs.forEach(input => {
    input.addEventListener('change', () => {
      applyFilters();
    });
  });
  
  // Filter button
  const filterButton = filterForm.querySelector('.apply-filters');
  
  if (filterButton) {
    filterButton.addEventListener('click', (e) => {
      e.preventDefault();
      applyFilters();
    });
  }
  
  // Reset filters
  const resetButton = filterForm.querySelector('.reset-filters');
  
  if (resetButton) {
    resetButton.addEventListener('click', (e) => {
      e.preventDefault();
      filterForm.reset();
      
      // Reset price slider if it exists
      if (priceSlider && window.noUiSlider) {
        priceSlider.noUiSlider.set([
          parseInt(priceSlider.dataset.min),
          parseInt(priceSlider.dataset.max)
        ]);
      }
      
      applyFilters();
    });
  }
}

/**
 * Apply filters via AJAX
 */
function applyFilters() {
  const filterForm = document.querySelector('.shop-filters');
  const productsContainer = document.querySelector('.products');
  const loadingOverlay = document.querySelector('.filter-loading');
  
  if (!filterForm || !productsContainer) return;
  
  // Show loading state
  if (loadingOverlay) {
    loadingOverlay.classList.remove('hidden');
  }
  
  // Get form data
  const formData = new FormData(filterForm);
  
  // Convert to URL parameters
  const params = new URLSearchParams(formData);
  
  // Add action for WordPress
  params.append('action', 'aqualuxe_filter_products');
  
  // Fetch filtered products
  fetch(aqualuxe_params.ajax_url, {
    method: 'POST',
    body: params
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update products
        productsContainer.innerHTML = data.products;
        
        // Update URL to maintain state (without page reload)
        const newUrl = new URL(window.location);
        
        for (const [key, value] of formData.entries()) {
          if (value) {
            newUrl.searchParams.set(key, value);
          } else {
            newUrl.searchParams.delete(key);
          }
        }
        
        history.pushState({}, '', newUrl);
        
        // Update product count
        const countElement = document.querySelector('.woocommerce-result-count');
        if (countElement && data.count) {
          countElement.textContent = data.count;
        }
      } else {
        console.error('Error filtering products:', data.message);
      }
    })
    .catch(error => {
      console.error('Error filtering products:', error);
    })
    .finally(() => {
      // Hide loading state
      if (loadingOverlay) {
        loadingOverlay.classList.add('hidden');
      }
    });
}

/**
 * Initialize wishlist functionality
 */
function initWishlist() {
  const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
  
  wishlistButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      
      const productId = button.dataset.productId;
      
      // Toggle wishlist state
      button.classList.toggle('in-wishlist');
      
      // Update icon
      const icon = button.querySelector('i');
      if (icon) {
        icon.classList.toggle('far');
        icon.classList.toggle('fas');
      }
      
      // Send AJAX request to update wishlist
      const formData = new FormData();
      formData.append('action', 'aqualuxe_toggle_wishlist');
      formData.append('product_id', productId);
      formData.append('security', aqualuxe_params.nonce);
      
      fetch(aqualuxe_params.ajax_url, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update wishlist count
            const wishlistCount = document.querySelector('.wishlist-count');
            if (wishlistCount) {
              wishlistCount.textContent = data.count;
            }
            
            // Show notification
            showNotification(data.message);
          } else {
            console.error('Error updating wishlist:', data.message);
            
            // Revert button state
            button.classList.toggle('in-wishlist');
            
            if (icon) {
              icon.classList.toggle('far');
              icon.classList.toggle('fas');
            }
          }
        })
        .catch(error => {
          console.error('Error updating wishlist:', error);
          
          // Revert button state
          button.classList.toggle('in-wishlist');
          
          if (icon) {
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
          }
        });
    });
  });
}

/**
 * Initialize product tabs
 */
function initProductTabs() {
  const tabsContainer = document.querySelector('.woocommerce-tabs');
  
  if (!tabsContainer) return;
  
  const tabs = tabsContainer.querySelectorAll('.tabs li');
  const panels = tabsContainer.querySelectorAll('.wc-tab');
  
  tabs.forEach(tab => {
    tab.addEventListener('click', (e) => {
      e.preventDefault();
      
      const target = tab.querySelector('a').getAttribute('href').substring(1);
      
      // Update active tab
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      
      // Show active panel
      panels.forEach(panel => {
        if (panel.id === target) {
          panel.classList.remove('hidden');
        } else {
          panel.classList.add('hidden');
        }
      });
    });
  });
}

/**
 * Initialize AJAX add to cart
 */
function initAjaxAddToCart() {
  const addToCartButtons = document.querySelectorAll('.add_to_cart_button');
  
  addToCartButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      if (button.classList.contains('product_type_simple')) {
        e.preventDefault();
        
        const productId = button.dataset.productId;
        
        // Show loading state
        button.classList.add('loading');
        
        // Send AJAX request
        const formData = new FormData();
        formData.append('action', 'aqualuxe_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', 1);
        formData.append('security', aqualuxe_params.nonce);
        
        fetch(aqualuxe_params.ajax_url, {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Update cart fragments
              if (data.fragments) {
                for (const [key, value] of Object.entries(data.fragments)) {
                  const element = document.querySelector(key);
                  if (element) {
                    element.outerHTML = value;
                  }
                }
              }
              
              // Show notification
              showNotification(data.message);
              
              // Update button state
              button.classList.remove('loading');
              button.classList.add('added');
            } else {
              console.error('Error adding to cart:', data.message);
              button.classList.remove('loading');
              showNotification(data.message, 'error');
            }
          })
          .catch(error => {
            console.error('Error adding to cart:', error);
            button.classList.remove('loading');
            showNotification('Error adding to cart. Please try again.', 'error');
          });
      }
    });
  });
  
  // Single product add to cart
  const singleAddToCartForm = document.querySelector('.single-product form.cart');
  
  if (singleAddToCartForm) {
    singleAddToCartForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const submitButton = singleAddToCartForm.querySelector('button[type="submit"]');
      
      // Show loading state
      if (submitButton) {
        submitButton.classList.add('loading');
      }
      
      // Get form data
      const formData = new FormData(singleAddToCartForm);
      formData.append('action', 'aqualuxe_add_to_cart');
      formData.append('security', aqualuxe_params.nonce);
      
      fetch(aqualuxe_params.ajax_url, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update cart fragments
            if (data.fragments) {
              for (const [key, value] of Object.entries(data.fragments)) {
                const element = document.querySelector(key);
                if (element) {
                  element.outerHTML = value;
                }
              }
            }
            
            // Show notification
            showNotification(data.message);
          } else {
            console.error('Error adding to cart:', data.message);
            showNotification(data.message, 'error');
          }
        })
        .catch(error => {
          console.error('Error adding to cart:', error);
          showNotification('Error adding to cart. Please try again.', 'error');
        })
        .finally(() => {
          // Remove loading state
          if (submitButton) {
            submitButton.classList.remove('loading');
          }
        });
    });
  }
}

/**
 * Show notification message
 */
function showNotification(message, type = 'success') {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = `notification notification-${type} fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300`;
  notification.innerHTML = message;
  
  // Add appropriate styling based on type
  if (type === 'success') {
    notification.classList.add('bg-green-500', 'text-white');
  } else if (type === 'error') {
    notification.classList.add('bg-red-500', 'text-white');
  } else if (type === 'info') {
    notification.classList.add('bg-blue-500', 'text-white');
  }
  
  // Add to DOM
  document.body.appendChild(notification);
  
  // Animate in
  setTimeout(() => {
    notification.classList.remove('translate-x-full');
  }, 10);
  
  // Remove after delay
  setTimeout(() => {
    notification.classList.add('translate-x-full');
    
    // Remove from DOM after animation completes
    setTimeout(() => {
      document.body.removeChild(notification);
    }, 300);
  }, 3000);
}