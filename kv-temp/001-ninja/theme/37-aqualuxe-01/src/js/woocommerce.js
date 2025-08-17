/**
 * AquaLuxe Theme WooCommerce JavaScript
 * This file handles WooCommerce specific functionality
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
  // Initialize WooCommerce functionality
  AquaLuxeWooCommerce.init();
});

/**
 * AquaLuxe WooCommerce Namespace
 * Contains all WooCommerce-specific functionality
 */
const AquaLuxeWooCommerce = {
  /**
   * Initialize all WooCommerce functionality
   */
  init() {
    this.setupProductGallery();
    this.setupQuantityButtons();
    this.setupAjaxCart();
    this.setupMiniCart();
    this.setupCheckout();
    this.setupProductFilters();
    this.setupQuickView();
    this.setupWishlist();
    this.setupProductVariations();
  },
  
  /**
   * Setup enhanced product gallery
   */
  setupProductGallery() {
    // Check if we're on a product page
    if (!document.body.classList.contains('single-product')) {
      return;
    }
    
    const gallery = document.querySelector('.woocommerce-product-gallery');
    if (!gallery) {
      return;
    }
    
    // Add zoom effect to product images
    const productImages = gallery.querySelectorAll('.woocommerce-product-gallery__image');
    productImages.forEach(image => {
      // Create zoom container
      const zoomContainer = document.createElement('div');
      zoomContainer.className = 'product-image-zoom';
      
      // Setup zoom functionality
      const img = image.querySelector('img');
      if (img) {
        image.addEventListener('mousemove', (e) => {
          const { left, top, width, height } = image.getBoundingClientRect();
          const x = (e.clientX - left) / width * 100;
          const y = (e.clientY - top) / height * 100;
          
          img.style.transformOrigin = `${x}% ${y}%`;
        });
        
        image.addEventListener('mouseenter', () => {
          img.style.transform = 'scale(1.5)';
        });
        
        image.addEventListener('mouseleave', () => {
          img.style.transform = 'scale(1)';
        });
      }
    });
    
    // Enhanced gallery navigation
    const thumbnails = gallery.querySelectorAll('.flex-control-thumbs li img');
    if (thumbnails.length > 0) {
      thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => {
          // Add active class to clicked thumbnail
          thumbnails.forEach(t => t.classList.remove('active'));
          thumb.classList.add('active');
          
          // Scroll to active thumbnail if needed
          const thumbsContainer = gallery.querySelector('.flex-control-thumbs');
          if (thumbsContainer) {
            const thumbWidth = thumb.offsetWidth + parseInt(window.getComputedStyle(thumb).marginRight);
            thumbsContainer.scrollTo({
              left: index * thumbWidth - (thumbsContainer.offsetWidth / 2) + (thumbWidth / 2),
              behavior: 'smooth'
            });
          }
        });
      });
      
      // Add navigation arrows if there are multiple thumbnails
      if (thumbnails.length > 4) {
        const prevButton = document.createElement('button');
        prevButton.className = 'gallery-prev-button';
        prevButton.innerHTML = '&larr;';
        prevButton.setAttribute('aria-label', 'Previous image');
        
        const nextButton = document.createElement('button');
        nextButton.className = 'gallery-next-button';
        nextButton.innerHTML = '&rarr;';
        nextButton.setAttribute('aria-label', 'Next image');
        
        const thumbsContainer = gallery.querySelector('.flex-control-thumbs');
        if (thumbsContainer) {
          thumbsContainer.parentNode.insertBefore(prevButton, thumbsContainer);
          thumbsContainer.parentNode.insertBefore(nextButton, thumbsContainer.nextSibling);
          
          prevButton.addEventListener('click', () => {
            const activeThumb = gallery.querySelector('.flex-control-thumbs li img.active') || thumbnails[0];
            const activeIndex = Array.from(thumbnails).indexOf(activeThumb);
            const prevIndex = activeIndex > 0 ? activeIndex - 1 : thumbnails.length - 1;
            thumbnails[prevIndex].click();
          });
          
          nextButton.addEventListener('click', () => {
            const activeThumb = gallery.querySelector('.flex-control-thumbs li img.active') || thumbnails[0];
            const activeIndex = Array.from(thumbnails).indexOf(activeThumb);
            const nextIndex = activeIndex < thumbnails.length - 1 ? activeIndex + 1 : 0;
            thumbnails[nextIndex].click();
          });
        }
      }
    }
  },
  
  /**
   * Setup quantity buttons
   */
  setupQuantityButtons() {
    const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
    quantityInputs.forEach(input => {
      // Create wrapper if it doesn't exist
      let wrapper = input.closest('.quantity-wrapper');
      if (!wrapper) {
        wrapper = document.createElement('div');
        wrapper.className = 'quantity-wrapper flex items-center border border-secondary-300 dark:border-secondary-700 rounded-md overflow-hidden';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
      }
      
      // Create minus button if it doesn't exist
      let minusBtn = wrapper.querySelector('.quantity-minus');
      if (!minusBtn) {
        minusBtn = document.createElement('button');
        minusBtn.type = 'button';
        minusBtn.className = 'quantity-minus px-3 py-1 bg-secondary-100 dark:bg-secondary-800 text-secondary-700 dark:text-white hover:bg-secondary-200 dark:hover:bg-secondary-700';
        minusBtn.textContent = '-';
        minusBtn.setAttribute('aria-label', 'Decrease quantity');
        wrapper.insertBefore(minusBtn, input);
      }
      
      // Create plus button if it doesn't exist
      let plusBtn = wrapper.querySelector('.quantity-plus');
      if (!plusBtn) {
        plusBtn = document.createElement('button');
        plusBtn.type = 'button';
        plusBtn.className = 'quantity-plus px-3 py-1 bg-secondary-100 dark:bg-secondary-800 text-secondary-700 dark:text-white hover:bg-secondary-200 dark:hover:bg-secondary-700';
        plusBtn.textContent = '+';
        plusBtn.setAttribute('aria-label', 'Increase quantity');
        wrapper.appendChild(plusBtn);
      }
      
      // Add event listeners
      minusBtn.addEventListener('click', () => {
        const currentValue = parseInt(input.value);
        const min = input.hasAttribute('min') ? parseInt(input.getAttribute('min')) : 1;
        if (currentValue > min) {
          input.value = currentValue - 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
      
      plusBtn.addEventListener('click', () => {
        const currentValue = parseInt(input.value);
        const max = input.hasAttribute('max') ? parseInt(input.getAttribute('max')) : '';
        if (!max || currentValue < max) {
          input.value = currentValue + 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
    });
  },
  
  /**
   * Setup AJAX cart functionality
   */
  setupAjaxCart() {
    // Add loading state to add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add_to_cart_button');
    addToCartButtons.forEach(button => {
      button.addEventListener('click', () => {
        button.classList.add('loading');
      });
    });
    
    // Listen for added_to_cart event
    document.body.addEventListener('added_to_cart', (e, fragments, cart_hash, button) => {
      if (button) {
        button.classList.remove('loading');
        button.classList.add('added');
      }
      
      // Show notification
      this.showNotification('Product added to cart', 'success');
      
      // Update mini cart
      this.updateMiniCart(fragments);
    });
    
    // Handle remove from cart
    document.body.addEventListener('click', (e) => {
      const removeLink = e.target.closest('.remove_from_cart_button');
      if (removeLink) {
        e.preventDefault();
        
        const productKey = removeLink.getAttribute('data-product_key');
        const cartItemKey = removeLink.getAttribute('data-cart_item_key');
        
        if (productKey || cartItemKey) {
          removeLink.classList.add('loading');
          
          // Send AJAX request to remove item
          const data = {
            action: 'aqualuxe_remove_from_cart',
            product_key: productKey || cartItemKey
          };
          
          fetch(aqualuxe_ajax.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Cache-Control': 'no-cache',
            },
            body: new URLSearchParams(data)
          })
          .then(response => response.json())
          .then(response => {
            if (response.success) {
              // Update fragments
              this.updateMiniCart(response.fragments);
              
              // Show notification
              this.showNotification('Product removed from cart', 'info');
            } else {
              this.showNotification('Error removing product', 'error');
            }
            
            removeLink.classList.remove('loading');
          })
          .catch(error => {
            console.error('Error:', error);
            removeLink.classList.remove('loading');
            this.showNotification('Error removing product', 'error');
          });
        }
      }
    });
  },
  
  /**
   * Setup mini cart functionality
   */
  setupMiniCart() {
    const miniCartToggle = document.getElementById('mini-cart-toggle');
    const miniCart = document.getElementById('mini-cart');
    
    if (miniCartToggle && miniCart) {
      // Toggle mini cart
      miniCartToggle.addEventListener('click', (e) => {
        e.preventDefault();
        miniCart.classList.toggle('hidden');
        
        // Set aria-expanded
        const expanded = miniCart.classList.contains('hidden') ? 'false' : 'true';
        miniCartToggle.setAttribute('aria-expanded', expanded);
        
        // Focus first element when opened
        if (expanded === 'true') {
          const firstFocusable = miniCart.querySelector('a, button, input');
          if (firstFocusable) {
            firstFocusable.focus();
          }
        }
      });
      
      // Close mini cart when clicking outside
      document.addEventListener('click', (e) => {
        if (!miniCart.classList.contains('hidden') && 
            !miniCart.contains(e.target) && 
            !miniCartToggle.contains(e.target)) {
          miniCart.classList.add('hidden');
          miniCartToggle.setAttribute('aria-expanded', 'false');
        }
      });
      
      // Close mini cart on escape key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !miniCart.classList.contains('hidden')) {
          miniCart.classList.add('hidden');
          miniCartToggle.setAttribute('aria-expanded', 'false');
          miniCartToggle.focus();
        }
      });
    }
  },
  
  /**
   * Update mini cart with fragments
   */
  updateMiniCart(fragments) {
    if (!fragments) return;
    
    // Update each fragment
    Object.keys(fragments).forEach(selector => {
      const element = document.querySelector(selector);
      if (element) {
        element.outerHTML = fragments[selector];
      }
    });
    
    // Reinitialize quantity buttons
    this.setupQuantityButtons();
    
    // Animate cart count
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
      cartCount.classList.add('animate-pulse');
      setTimeout(() => {
        cartCount.classList.remove('animate-pulse');
      }, 1000);
    }
  },
  
  /**
   * Setup checkout enhancements
   */
  setupCheckout() {
    // Check if we're on the checkout page
    if (!document.body.classList.contains('woocommerce-checkout')) {
      return;
    }
    
    // Add validation to checkout form
    const checkoutForm = document.querySelector('form.checkout');
    if (checkoutForm) {
      const requiredFields = checkoutForm.querySelectorAll('.validate-required input, .validate-required select, .validate-required textarea');
      
      requiredFields.forEach(field => {
        field.addEventListener('blur', () => {
          const wrapper = field.closest('.form-row');
          if (wrapper) {
            if (field.value.trim() === '') {
              wrapper.classList.add('woocommerce-invalid');
              wrapper.classList.remove('woocommerce-validated');
            } else {
              wrapper.classList.remove('woocommerce-invalid');
              wrapper.classList.add('woocommerce-validated');
            }
          }
        });
      });
      
      // Email validation
      const emailField = checkoutForm.querySelector('#billing_email');
      if (emailField) {
        emailField.addEventListener('blur', () => {
          const wrapper = emailField.closest('.form-row');
          if (wrapper) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailField.value.trim() === '' || !emailRegex.test(emailField.value)) {
              wrapper.classList.add('woocommerce-invalid');
              wrapper.classList.remove('woocommerce-validated');
            } else {
              wrapper.classList.remove('woocommerce-invalid');
              wrapper.classList.add('woocommerce-validated');
            }
          }
        });
      }
    }
    
    // Add order summary toggle for mobile
    const orderReview = document.getElementById('order_review');
    const orderReviewHeading = document.getElementById('order_review_heading');
    
    if (orderReview && orderReviewHeading && window.innerWidth < 768) {
      // Create toggle button
      const toggleButton = document.createElement('button');
      toggleButton.type = 'button';
      toggleButton.className = 'order-summary-toggle flex items-center justify-between w-full py-2 px-4 bg-secondary-100 dark:bg-secondary-800 rounded-md mb-4';
      toggleButton.innerHTML = `
        <span>Show order summary</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      `;
      
      // Insert toggle before order review
      orderReviewHeading.parentNode.insertBefore(toggleButton, orderReviewHeading);
      
      // Hide order review initially on mobile
      orderReviewHeading.style.display = 'none';
      orderReview.style.display = 'none';
      
      // Toggle order review
      toggleButton.addEventListener('click', () => {
        const isHidden = orderReview.style.display === 'none';
        
        if (isHidden) {
          orderReviewHeading.style.display = 'block';
          orderReview.style.display = 'block';
          toggleButton.querySelector('span').textContent = 'Hide order summary';
          toggleButton.querySelector('svg').style.transform = 'rotate(180deg)';
        } else {
          orderReviewHeading.style.display = 'none';
          orderReview.style.display = 'none';
          toggleButton.querySelector('span').textContent = 'Show order summary';
          toggleButton.querySelector('svg').style.transform = 'rotate(0)';
        }
      });
    }
  },
  
  /**
   * Setup product filters
   */
  setupProductFilters() {
    // Check if we're on a shop or archive page
    if (!document.body.classList.contains('woocommerce-shop') && 
        !document.body.classList.contains('woocommerce-archive')) {
      return;
    }
    
    // Add filter toggle for mobile
    const sidebar = document.querySelector('.shop-sidebar');
    if (sidebar && window.innerWidth < 768) {
      // Create toggle button
      const toggleButton = document.createElement('button');
      toggleButton.type = 'button';
      toggleButton.className = 'filter-toggle flex items-center justify-center px-4 py-2 mb-4 bg-secondary-100 dark:bg-secondary-800 rounded-md text-secondary-700 dark:text-white';
      toggleButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
        </svg>
        <span>Show Filters</span>
      `;
      
      // Insert toggle before sidebar
      sidebar.parentNode.insertBefore(toggleButton, sidebar);
      
      // Hide sidebar initially on mobile
      sidebar.classList.add('hidden', 'lg:block');
      
      // Toggle sidebar
      toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        
        const isHidden = sidebar.classList.contains('hidden');
        toggleButton.querySelector('span').textContent = isHidden ? 'Show Filters' : 'Hide Filters';
      });
    }
    
    // AJAX filter handling
    const filterForms = document.querySelectorAll('.widget_price_filter form, .widget_product_categories form');
    filterForms.forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const searchParams = new URLSearchParams(formData);
        
        // Get current URL and update with new parameters
        const url = new URL(window.location.href);
        
        // Remove existing parameters that match the form
        for (const [key] of formData.entries()) {
          url.searchParams.delete(key);
        }
        
        // Add new parameters
        for (const [key, value] of searchParams.entries()) {
          url.searchParams.append(key, value);
        }
        
        // Navigate to filtered URL
        window.location.href = url.toString();
      });
    });
  },
  
  /**
   * Setup quick view functionality
   */
  setupQuickView() {
    // Add quick view buttons to products
    const products = document.querySelectorAll('.products .product');
    products.forEach(product => {
      // Check if quick view button already exists
      if (product.querySelector('.quick-view-button')) {
        return;
      }
      
      // Get product ID
      const productId = product.classList.toString()
        .split(' ')
        .find(className => className.startsWith('post-'))
        ?.replace('post-', '');
      
      if (!productId) {
        return;
      }
      
      // Create quick view button
      const quickViewButton = document.createElement('button');
      quickViewButton.type = 'button';
      quickViewButton.className = 'quick-view-button absolute top-2 right-2 z-10 p-2 bg-white dark:bg-secondary-800 rounded-full shadow-md text-secondary-700 dark:text-white hover:bg-secondary-50 dark:hover:bg-secondary-700';
      quickViewButton.setAttribute('data-product-id', productId);
      quickViewButton.setAttribute('aria-label', 'Quick view');
      quickViewButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
          <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
        </svg>
      `;
      
      // Add button to product
      const imageWrapper = product.querySelector('.woocommerce-loop-product__link');
      if (imageWrapper) {
        imageWrapper.style.position = 'relative';
        imageWrapper.appendChild(quickViewButton);
      }
      
      // Add click event
      quickViewButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        this.openQuickView(productId);
      });
    });
  },
  
  /**
   * Open quick view modal
   */
  openQuickView(productId) {
    // Create modal if it doesn't exist
    let quickViewModal = document.getElementById('quick-view-modal');
    if (!quickViewModal) {
      quickViewModal = document.createElement('div');
      quickViewModal.id = 'quick-view-modal';
      quickViewModal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75 hidden';
      quickViewModal.setAttribute('aria-modal', 'true');
      quickViewModal.setAttribute('role', 'dialog');
      
      quickViewModal.innerHTML = `
        <div class="relative bg-white dark:bg-secondary-900 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
          <button type="button" class="quick-view-close absolute top-4 right-4 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="sr-only">Close</span>
          </button>
          <div class="quick-view-content p-6">
            <div class="flex justify-center items-center h-64">
              <svg class="animate-spin h-8 w-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
          </div>
        </div>
      `;
      
      document.body.appendChild(quickViewModal);
      
      // Add close event
      const closeButton = quickViewModal.querySelector('.quick-view-close');
      if (closeButton) {
        closeButton.addEventListener('click', () => {
          quickViewModal.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
        });
      }
      
      // Close on outside click
      quickViewModal.addEventListener('click', (e) => {
        if (e.target === quickViewModal) {
          quickViewModal.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
        }
      });
      
      // Close on escape key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !quickViewModal.classList.contains('hidden')) {
          quickViewModal.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
        }
      });
    }
    
    // Show modal
    quickViewModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Get content container
    const contentContainer = quickViewModal.querySelector('.quick-view-content');
    if (!contentContainer) return;
    
    // Show loading state
    contentContainer.innerHTML = `
      <div class="flex justify-center items-center h-64">
        <svg class="animate-spin h-8 w-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    `;
    
    // Fetch product data
    const data = {
      action: 'aqualuxe_quick_view',
      product_id: productId
    };
    
    fetch(aqualuxe_ajax.ajax_url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Cache-Control': 'no-cache',
      },
      body: new URLSearchParams(data)
    })
    .then(response => response.json())
    .then(response => {
      if (response.success && response.data) {
        contentContainer.innerHTML = response.data;
        
        // Initialize product gallery
        this.setupProductGallery();
        
        // Initialize quantity buttons
        this.setupQuantityButtons();
        
        // Initialize variations
        this.setupProductVariations();
      } else {
        contentContainer.innerHTML = '<p class="text-center text-red-500">Error loading product data</p>';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      contentContainer.innerHTML = '<p class="text-center text-red-500">Error loading product data</p>';
    });
  },
  
  /**
   * Setup wishlist functionality
   */
  setupWishlist() {
    // Add wishlist buttons to products
    const products = document.querySelectorAll('.products .product');
    products.forEach(product => {
      // Check if wishlist button already exists
      if (product.querySelector('.wishlist-button')) {
        return;
      }
      
      // Get product ID
      const productId = product.classList.toString()
        .split(' ')
        .find(className => className.startsWith('post-'))
        ?.replace('post-', '');
      
      if (!productId) {
        return;
      }
      
      // Check if product is in wishlist
      const wishlist = JSON.parse(localStorage.getItem('aqualuxe_wishlist') || '[]');
      const isInWishlist = wishlist.includes(productId);
      
      // Create wishlist button
      const wishlistButton = document.createElement('button');
      wishlistButton.type = 'button';
      wishlistButton.className = 'wishlist-button absolute top-2 left-2 z-10 p-2 bg-white dark:bg-secondary-800 rounded-full shadow-md hover:bg-secondary-50 dark:hover:bg-secondary-700';
      wishlistButton.setAttribute('data-product-id', productId);
      wishlistButton.setAttribute('aria-label', isInWishlist ? 'Remove from wishlist' : 'Add to wishlist');
      wishlistButton.innerHTML = isInWishlist ? 
        `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
        </svg>` :
        `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-secondary-500 dark:text-secondary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>`;
      
      // Add button to product
      const imageWrapper = product.querySelector('.woocommerce-loop-product__link');
      if (imageWrapper) {
        imageWrapper.style.position = 'relative';
        imageWrapper.appendChild(wishlistButton);
      }
      
      // Add click event
      wishlistButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        this.toggleWishlist(productId, wishlistButton);
      });
    });
    
    // Update wishlist count
    this.updateWishlistCount();
  },
  
  /**
   * Toggle product in wishlist
   */
  toggleWishlist(productId, button) {
    // Get current wishlist
    const wishlist = JSON.parse(localStorage.getItem('aqualuxe_wishlist') || '[]');
    
    // Check if product is in wishlist
    const index = wishlist.indexOf(productId);
    
    if (index === -1) {
      // Add to wishlist
      wishlist.push(productId);
      
      // Update button
      button.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
        </svg>
      `;
      button.setAttribute('aria-label', 'Remove from wishlist');
      
      // Show notification
      this.showNotification('Product added to wishlist', 'success');
    } else {
      // Remove from wishlist
      wishlist.splice(index, 1);
      
      // Update button
      button.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-secondary-500 dark:text-secondary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
      `;
      button.setAttribute('aria-label', 'Add to wishlist');
      
      // Show notification
      this.showNotification('Product removed from wishlist', 'info');
    }
    
    // Save wishlist
    localStorage.setItem('aqualuxe_wishlist', JSON.stringify(wishlist));
    
    // Update wishlist count
    this.updateWishlistCount();
  },
  
  /**
   * Update wishlist count
   */
  updateWishlistCount() {
    const wishlist = JSON.parse(localStorage.getItem('aqualuxe_wishlist') || '[]');
    const count = wishlist.length;
    
    // Update count element
    const countElement = document.querySelector('.wishlist-count');
    if (countElement) {
      countElement.textContent = count;
      
      // Show/hide based on count
      if (count > 0) {
        countElement.classList.remove('hidden');
      } else {
        countElement.classList.add('hidden');
      }
    }
  },
  
  /**
   * Setup product variations
   */
  setupProductVariations() {
    // Check if we're on a variable product page
    const variationForm = document.querySelector('.variations_form');
    if (!variationForm) {
      return;
    }
    
    // Get variation data
    const variationData = JSON.parse(variationForm.getAttribute('data-product_variations') || '[]');
    if (!variationData.length) {
      return;
    }
    
    // Get variation selects
    const variationSelects = variationForm.querySelectorAll('.variations select');
    
    // Add custom styling to variation selects
    variationSelects.forEach(select => {
      // Create wrapper if it doesn't exist
      let wrapper = select.closest('.custom-select-wrapper');
      if (!wrapper) {
        wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper relative';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        
        // Add custom styling
        select.className = 'appearance-none w-full px-3 py-2 border border-secondary-300 dark:border-secondary-700 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-secondary-800 dark:text-white pr-10';
        
        // Add arrow icon
        const arrow = document.createElement('div');
        arrow.className = 'pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-secondary-500 dark:text-secondary-400';
        arrow.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        `;
        wrapper.appendChild(arrow);
      }
    });
    
    // Add swatches for color variations
    const colorSelect = variationForm.querySelector('.variations select[name="attribute_pa_color"]');
    if (colorSelect) {
      // Get color options
      const colorOptions = Array.from(colorSelect.options).slice(1); // Skip the "Choose an option" option
      
      // Create swatches container
      const swatchesContainer = document.createElement('div');
      swatchesContainer.className = 'color-swatches flex flex-wrap gap-2 mt-2';
      
      // Add swatches
      colorOptions.forEach(option => {
        const color = option.value;
        const label = option.textContent;
        
        // Create swatch
        const swatch = document.createElement('button');
        swatch.type = 'button';
        swatch.className = 'color-swatch w-8 h-8 rounded-full border border-secondary-300 dark:border-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500';
        swatch.style.backgroundColor = color;
        swatch.setAttribute('data-value', color);
        swatch.setAttribute('aria-label', label);
        
        // Add selected state
        if (colorSelect.value === color) {
          swatch.classList.add('ring-2', 'ring-primary-500', 'ring-offset-2');
        }
        
        // Add click event
        swatch.addEventListener('click', () => {
          // Update select value
          colorSelect.value = color;
          colorSelect.dispatchEvent(new Event('change', { bubbles: true }));
          
          // Update selected state
          swatchesContainer.querySelectorAll('.color-swatch').forEach(s => {
            s.classList.remove('ring-2', 'ring-primary-500', 'ring-offset-2');
          });
          swatch.classList.add('ring-2', 'ring-primary-500', 'ring-offset-2');
        });
        
        // Add to container
        swatchesContainer.appendChild(swatch);
      });
      
      // Add swatches after select
      colorSelect.parentNode.appendChild(swatchesContainer);
      
      // Hide original select
      colorSelect.style.display = 'none';
    }
    
    // Add image swatches for other attributes if images are available
    variationSelects.forEach(select => {
      // Skip color select as we already handled it
      if (select.name === 'attribute_pa_color') {
        return;
      }
      
      // Check if we have images for this attribute
      const attributeName = select.name.replace('attribute_', '');
      const hasImages = variationData.some(variation => 
        variation.image && 
        variation.attributes && 
        variation.attributes[attributeName]
      );
      
      if (hasImages) {
        // Get options
        const options = Array.from(select.options).slice(1); // Skip the "Choose an option" option
        
        // Create swatches container
        const swatchesContainer = document.createElement('div');
        swatchesContainer.className = 'image-swatches flex flex-wrap gap-2 mt-2';
        
        // Add swatches
        options.forEach(option => {
          const value = option.value;
          const label = option.textContent;
          
          // Find variation with this attribute value
          const variation = variationData.find(v => 
            v.attributes && 
            v.attributes[attributeName] === value
          );
          
          // Create swatch
          const swatch = document.createElement('button');
          swatch.type = 'button';
          swatch.className = 'image-swatch w-12 h-12 rounded-md border border-secondary-300 dark:border-secondary-700 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500';
          swatch.setAttribute('data-value', value);
          swatch.setAttribute('aria-label', label);
          
          // Add image if available
          if (variation && variation.image) {
            swatch.innerHTML = `<img src="${variation.image.thumb_src}" alt="${label}" class="w-full h-full object-cover">`;
          } else {
            swatch.textContent = label;
          }
          
          // Add selected state
          if (select.value === value) {
            swatch.classList.add('ring-2', 'ring-primary-500', 'ring-offset-2');
          }
          
          // Add click event
          swatch.addEventListener('click', () => {
            // Update select value
            select.value = value;
            select.dispatchEvent(new Event('change', { bubbles: true }));
            
            // Update selected state
            swatchesContainer.querySelectorAll('.image-swatch').forEach(s => {
              s.classList.remove('ring-2', 'ring-primary-500', 'ring-offset-2');
            });
            swatch.classList.add('ring-2', 'ring-primary-500', 'ring-offset-2');
          });
          
          // Add to container
          swatchesContainer.appendChild(swatch);
        });
        
        // Add swatches after select
        select.parentNode.appendChild(swatchesContainer);
        
        // Hide original select
        select.style.display = 'none';
      }
    });
  },
  
  /**
   * Show notification
   */
  showNotification(message, type = 'info') {
    // Create notification container if it doesn't exist
    let notificationContainer = document.getElementById('aqualuxe-notifications');
    if (!notificationContainer) {
      notificationContainer = document.createElement('div');
      notificationContainer.id = 'aqualuxe-notifications';
      notificationContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col gap-2';
      document.body.appendChild(notificationContainer);
    }
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = 'notification flex items-center p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full';
    
    // Add type-specific classes and icon
    switch (type) {
      case 'success':
        notification.classList.add('bg-green-500', 'text-white');
        notification.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        `;
        break;
      case 'error':
        notification.classList.add('bg-red-500', 'text-white');
        notification.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        `;
        break;
      case 'warning':
        notification.classList.add('bg-yellow-500', 'text-white');
        notification.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
        `;
        break;
      default:
        notification.classList.add('bg-blue-500', 'text-white');
        notification.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
        `;
    }
    
    // Add message
    notification.innerHTML += `<span>${message}</span>`;
    
    // Add close button
    const closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'ml-auto pl-3 text-white opacity-75 hover:opacity-100 focus:outline-none';
    closeButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    `;
    closeButton.addEventListener('click', () => {
      notification.classList.add('opacity-0', 'translate-x-full');
      setTimeout(() => {
        notification.remove();
      }, 300);
    });
    notification.appendChild(closeButton);
    
    // Add to container
    notificationContainer.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
      notification.classList.remove('translate-x-full');
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
      notification.classList.add('opacity-0', 'translate-x-full');
      setTimeout(() => {
        notification.remove();
      }, 300);
    }, 5000);
  }
};

// Export for potential use in other modules
export default AquaLuxeWooCommerce;