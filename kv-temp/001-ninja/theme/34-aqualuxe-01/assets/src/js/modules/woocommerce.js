/**
 * WooCommerce Module
 * 
 * Handles WooCommerce-specific functionality for the theme.
 */

const WooCommerce = {
  /**
   * Initialize the WooCommerce functionality
   */
  init() {
    this.cacheDOM();
    this.bindEvents();
    this.setupQuantityButtons();
    this.setupProductGallery();
    this.setupAjaxAddToCart();
    this.setupQuickView();
    this.setupFilters();
  },

  /**
   * Cache DOM elements
   */
  cacheDOM() {
    // Product elements
    this.products = document.querySelectorAll('.product');
    this.quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
    
    // Gallery elements
    this.productGallery = document.querySelector('.woocommerce-product-gallery');
    this.galleryImages = this.productGallery ? this.productGallery.querySelectorAll('.woocommerce-product-gallery__image') : [];
    
    // Cart elements
    this.addToCartButtons = document.querySelectorAll('.add_to_cart_button');
    this.cartCount = document.querySelector('.site-header__cart-count');
    this.miniCart = document.querySelector('.widget_shopping_cart');
    
    // Quick view elements
    this.quickViewButtons = document.querySelectorAll('.quick-view-button');
    this.quickViewModal = document.querySelector('.quick-view-modal');
    
    // Filter elements
    this.filterToggles = document.querySelectorAll('.filter-toggle');
    this.filterWidgets = document.querySelectorAll('.widget-area .widget');
    this.priceSlider = document.querySelector('.price_slider');
  },

  /**
   * Bind events
   */
  bindEvents() {
    // Product hover effects
    this.products.forEach(product => {
      product.addEventListener('mouseenter', () => this.handleProductHover(product, true));
      product.addEventListener('mouseleave', () => this.handleProductHover(product, false));
    });

    // Handle AJAX add to cart response
    document.addEventListener('added_to_cart', (event, fragments, cart_hash, button) => {
      this.updateCartCount(fragments);
      this.showAddedToCartNotification(button);
    });

    // Filter toggles for mobile
    this.filterToggles.forEach(toggle => {
      toggle.addEventListener('click', event => {
        event.preventDefault();
        this.toggleFilters();
      });
    });

    // Update cart count when cart is updated
    document.addEventListener('wc_fragments_refreshed', event => {
      this.updateMiniCart();
    });
  },

  /**
   * Handle product hover effects
   * 
   * @param {HTMLElement} product - Product element
   * @param {boolean} isHovering - Whether the product is being hovered
   */
  handleProductHover(product, isHovering) {
    // Show/hide secondary image if available
    const images = product.querySelectorAll('.attachment-woocommerce_thumbnail');
    
    if (images.length > 1) {
      if (isHovering) {
        images[0].style.opacity = '0';
        images[1].style.opacity = '1';
      } else {
        images[0].style.opacity = '1';
        images[1].style.opacity = '0';
      }
    }
    
    // Show/hide add to cart button
    const addToCartButton = product.querySelector('.add_to_cart_button');
    if (addToCartButton) {
      if (isHovering) {
        addToCartButton.classList.add('visible');
      } else {
        addToCartButton.classList.remove('visible');
      }
    }
  },

  /**
   * Setup quantity buttons
   */
  setupQuantityButtons() {
    this.quantityInputs.forEach(input => {
      // Create wrapper
      const wrapper = document.createElement('div');
      wrapper.className = 'quantity-buttons';
      
      // Create decrease button
      const decreaseButton = document.createElement('button');
      decreaseButton.type = 'button';
      decreaseButton.className = 'quantity-button quantity-down';
      decreaseButton.textContent = '-';
      decreaseButton.addEventListener('click', () => {
        const currentValue = parseInt(input.value, 10);
        const min = parseInt(input.getAttribute('min'), 10) || 1;
        
        if (currentValue > min) {
          input.value = currentValue - 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
      
      // Create increase button
      const increaseButton = document.createElement('button');
      increaseButton.type = 'button';
      increaseButton.className = 'quantity-button quantity-up';
      increaseButton.textContent = '+';
      increaseButton.addEventListener('click', () => {
        const currentValue = parseInt(input.value, 10);
        const max = parseInt(input.getAttribute('max'), 10) || 999;
        
        if (!max || currentValue < max) {
          input.value = currentValue + 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
      
      // Insert buttons
      input.parentNode.insertBefore(wrapper, input);
      wrapper.appendChild(decreaseButton);
      wrapper.appendChild(input);
      wrapper.appendChild(increaseButton);
    });
  },

  /**
   * Setup product gallery
   */
  setupProductGallery() {
    if (!this.productGallery) return;
    
    // Get thumbnails
    const thumbnails = this.productGallery.querySelectorAll('.flex-control-thumbs li');
    
    // Add click event to thumbnails
    thumbnails.forEach((thumbnail, index) => {
      thumbnail.addEventListener('click', () => {
        // Remove active class from all images
        this.galleryImages.forEach(image => {
          image.classList.remove('flex-active');
        });
        
        // Add active class to selected image
        if (this.galleryImages[index]) {
          this.galleryImages[index].classList.add('flex-active');
        }
      });
    });
    
    // Setup zoom effect
    if (window.innerWidth > 768) {
      this.galleryImages.forEach(image => {
        image.addEventListener('mouseenter', () => {
          const img = image.querySelector('img');
          if (img && img.getAttribute('data-large_image')) {
            image.style.backgroundImage = `url(${img.getAttribute('data-large_image')})`;
            image.classList.add('zoom-active');
          }
        });
        
        image.addEventListener('mousemove', event => {
          if (image.classList.contains('zoom-active')) {
            const { left, top, width, height } = image.getBoundingClientRect();
            const x = (event.clientX - left) / width * 100;
            const y = (event.clientY - top) / height * 100;
            
            image.style.backgroundPosition = `${x}% ${y}%`;
          }
        });
        
        image.addEventListener('mouseleave', () => {
          image.classList.remove('zoom-active');
        });
      });
    }
  },

  /**
   * Setup AJAX add to cart
   */
  setupAjaxAddToCart() {
    // For single product pages
    const addToCartForm = document.querySelector('form.cart');
    
    if (addToCartForm) {
      addToCartForm.addEventListener('submit', event => {
        // Only handle AJAX if it's enabled
        if (addToCartForm.classList.contains('ajax-add-to-cart')) {
          event.preventDefault();
          
          // Get form data
          const formData = new FormData(addToCartForm);
          formData.append('action', 'aqualuxe_ajax_add_to_cart');
          
          // Add loading state
          const submitButton = addToCartForm.querySelector('button[type="submit"]');
          submitButton.classList.add('loading');
          
          // Send AJAX request
          if (window.aqualuxeData && window.aqualuxeData.ajaxUrl) {
            fetch(window.aqualuxeData.ajaxUrl, {
              method: 'POST',
              body: formData,
              credentials: 'same-origin'
            })
              .then(response => response.json())
              .then(data => {
                submitButton.classList.remove('loading');
                
                if (data.success) {
                  // Update cart fragments
                  if (data.fragments) {
                    this.updateCartFragments(data.fragments);
                  }
                  
                  // Show success message
                  this.showAddedToCartNotification(submitButton);
                } else {
                  // Show error message
                  this.showErrorNotification(data.message || 'Error adding to cart');
                }
              })
              .catch(error => {
                console.error('AJAX Add to Cart Error:', error);
                submitButton.classList.remove('loading');
                this.showErrorNotification('Error adding to cart');
              });
          }
        }
      });
    }
  },

  /**
   * Update cart fragments
   * 
   * @param {Object} fragments - Cart fragments
   */
  updateCartFragments(fragments) {
    if (!fragments) return;
    
    // Update each fragment
    Object.keys(fragments).forEach(key => {
      const element = document.querySelector(key);
      if (element) {
        element.outerHTML = fragments[key];
      }
    });
    
    // Update cart count
    this.updateCartCount(fragments);
    
    // Trigger event
    document.dispatchEvent(new CustomEvent('aqualuxe:cart-updated'));
  },

  /**
   * Update cart count
   * 
   * @param {Object} fragments - Cart fragments
   */
  updateCartCount(fragments) {
    if (this.cartCount && fragments && fragments['.site-header__cart-count']) {
      this.cartCount.outerHTML = fragments['.site-header__cart-count'];
      this.cartCount = document.querySelector('.site-header__cart-count');
    }
  },

  /**
   * Update mini cart
   */
  updateMiniCart() {
    // Re-cache mini cart
    this.miniCart = document.querySelector('.widget_shopping_cart');
    
    // Re-cache cart count
    this.cartCount = document.querySelector('.site-header__cart-count');
  },

  /**
   * Show added to cart notification
   * 
   * @param {HTMLElement} button - Add to cart button
   */
  showAddedToCartNotification(button) {
    // Create notification
    const notification = document.createElement('div');
    notification.className = 'added-to-cart-notification';
    notification.innerHTML = `
      <div class="notification-content">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" fill="currentColor"/></svg>
        <span>Product added to cart</span>
      </div>
      <div class="notification-actions">
        <a href="${window.aqualuxeData ? window.aqualuxeData.cartUrl || '#' : '#'}" class="view-cart">View Cart</a>
      </div>
    `;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
      notification.classList.add('active');
      
      // Hide after 3 seconds
      setTimeout(() => {
        notification.classList.remove('active');
        
        // Remove from DOM after animation
        setTimeout(() => {
          notification.remove();
        }, 300);
      }, 3000);
    }, 10);
  },

  /**
   * Show error notification
   * 
   * @param {string} message - Error message
   */
  showErrorNotification(message) {
    // Create notification
    const notification = document.createElement('div');
    notification.className = 'error-notification';
    notification.innerHTML = `
      <div class="notification-content">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2h-2v-2zm0-8h2v6h-2V7z" fill="currentColor"/></svg>
        <span>${message}</span>
      </div>
    `;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
      notification.classList.add('active');
      
      // Hide after 3 seconds
      setTimeout(() => {
        notification.classList.remove('active');
        
        // Remove from DOM after animation
        setTimeout(() => {
          notification.remove();
        }, 300);
      }, 3000);
    }, 10);
  },

  /**
   * Setup quick view functionality
   */
  setupQuickView() {
    if (!this.quickViewButtons.length) return;
    
    // Create modal if it doesn't exist
    if (!this.quickViewModal) {
      this.quickViewModal = document.createElement('div');
      this.quickViewModal.className = 'quick-view-modal';
      this.quickViewModal.innerHTML = `
        <div class="quick-view-container">
          <div class="quick-view-content"></div>
          <button class="quick-view-close">&times;</button>
        </div>
      `;
      document.body.appendChild(this.quickViewModal);
      
      // Add close button event
      const closeButton = this.quickViewModal.querySelector('.quick-view-close');
      closeButton.addEventListener('click', () => {
        this.closeQuickView();
      });
      
      // Close on click outside
      this.quickViewModal.addEventListener('click', event => {
        if (event.target === this.quickViewModal) {
          this.closeQuickView();
        }
      });
      
      // Close on escape key
      document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && this.quickViewModal.classList.contains('active')) {
          this.closeQuickView();
        }
      });
    }
    
    // Add click event to quick view buttons
    this.quickViewButtons.forEach(button => {
      button.addEventListener('click', event => {
        event.preventDefault();
        
        const productId = button.dataset.productId;
        if (productId) {
          this.openQuickView(productId);
        }
      });
    });
  },

  /**
   * Open quick view modal
   * 
   * @param {string} productId - Product ID
   */
  openQuickView(productId) {
    // Show loading state
    this.quickViewModal.classList.add('active', 'loading');
    
    // Get content container
    const contentContainer = this.quickViewModal.querySelector('.quick-view-content');
    contentContainer.innerHTML = '<div class="loading-spinner"></div>';
    
    // Send AJAX request
    if (window.aqualuxeData && window.aqualuxeData.ajaxUrl) {
      const formData = new FormData();
      formData.append('action', 'aqualuxe_quick_view');
      formData.append('product_id', productId);
      formData.append('nonce', window.aqualuxeData.nonce);
      
      fetch(window.aqualuxeData.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      })
        .then(response => response.json())
        .then(data => {
          this.quickViewModal.classList.remove('loading');
          
          if (data.success) {
            // Update content
            contentContainer.innerHTML = data.data;
            
            // Initialize product gallery
            this.setupProductGallery();
            
            // Initialize quantity buttons
            this.setupQuantityButtons();
            
            // Initialize variations
            if (typeof jQuery !== 'undefined' && jQuery.fn.wc_variation_form) {
              jQuery('.variations_form').wc_variation_form();
            }
          } else {
            // Show error
            contentContainer.innerHTML = '<div class="quick-view-error">Error loading product information.</div>';
          }
        })
        .catch(error => {
          console.error('Quick View Error:', error);
          this.quickViewModal.classList.remove('loading');
          contentContainer.innerHTML = '<div class="quick-view-error">Error loading product information.</div>';
        });
    }
  },

  /**
   * Close quick view modal
   */
  closeQuickView() {
    this.quickViewModal.classList.remove('active');
    
    // Clear content after animation
    setTimeout(() => {
      const contentContainer = this.quickViewModal.querySelector('.quick-view-content');
      contentContainer.innerHTML = '';
    }, 300);
  },

  /**
   * Setup product filters
   */
  setupFilters() {
    // Toggle filters on mobile
    if (this.filterToggles.length) {
      // Create filter container for mobile
      const filterContainer = document.createElement('div');
      filterContainer.className = 'mobile-filters';
      filterContainer.innerHTML = `
        <div class="mobile-filters__header">
          <h3>Filters</h3>
          <button class="mobile-filters__close">&times;</button>
        </div>
        <div class="mobile-filters__content"></div>
      `;
      document.body.appendChild(filterContainer);
      
      // Add close button event
      const closeButton = filterContainer.querySelector('.mobile-filters__close');
      closeButton.addEventListener('click', () => {
        this.closeFilters();
      });
      
      // Clone widgets to mobile container
      const filterContent = filterContainer.querySelector('.mobile-filters__content');
      this.filterWidgets.forEach(widget => {
        const clone = widget.cloneNode(true);
        filterContent.appendChild(clone);
      });
    }
    
    // Initialize price slider
    if (this.priceSlider && typeof jQuery !== 'undefined' && jQuery.fn.slider) {
      jQuery(document.body).trigger('init_price_filter');
    }
  },

  /**
   * Toggle filters on mobile
   */
  toggleFilters() {
    const mobileFilters = document.querySelector('.mobile-filters');
    
    if (mobileFilters) {
      mobileFilters.classList.toggle('active');
      document.body.classList.toggle('filters-active');
    }
  },

  /**
   * Close filters on mobile
   */
  closeFilters() {
    const mobileFilters = document.querySelector('.mobile-filters');
    
    if (mobileFilters) {
      mobileFilters.classList.remove('active');
      document.body.classList.remove('filters-active');
    }
  }
};

export default WooCommerce;