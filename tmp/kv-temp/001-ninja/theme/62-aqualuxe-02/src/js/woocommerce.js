/**
 * AquaLuxe WooCommerce JavaScript
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 * @since 1.0.0
 */

/**
 * AquaLuxe WooCommerce Class
 */
class AquaLuxeWooCommerce {
  /**
   * Constructor
   */
  constructor() {
    this.isInitialized = false;
    
    // Elements
    this.body = document.body;
    this.addToCartButtons = null;
    this.quantityInputs = null;
    this.cartForm = null;
    this.miniCart = null;
    this.quickViewButtons = null;
    this.quickViewModal = null;
    this.variationForms = null;
    this.productGalleries = null;
    
    // Initialize
    this.init();
  }

  /**
   * Initialize WooCommerce functionality
   */
  init() {
    // Skip if already initialized
    if (this.isInitialized) {
      return;
    }
    
    // Get elements
    this.addToCartButtons = document.querySelectorAll('.add_to_cart_button');
    this.quantityInputs = document.querySelectorAll('.quantity input');
    this.cartForm = document.querySelector('.woocommerce-cart-form');
    this.miniCart = document.querySelector('.mini-cart');
    this.quickViewButtons = document.querySelectorAll('.quick-view-button');
    this.quickViewModal = document.querySelector('.quick-view-modal');
    this.variationForms = document.querySelectorAll('.variations_form');
    this.productGalleries = document.querySelectorAll('.woocommerce-product-gallery');
    
    // Bind events
    this.bindEvents();
    
    // Initialize components
    this.initQuantityButtons();
    this.initMiniCart();
    this.initQuickView();
    this.initProductGallery();
    this.initVariationForms();
    
    // Set initialized flag
    this.isInitialized = true;
  }

  /**
   * Bind events
   */
  bindEvents() {
    // Add to cart button click
    if (this.addToCartButtons) {
      this.addToCartButtons.forEach((button) => {
        button.addEventListener('click', (e) => this.handleAddToCart(e, button));
      });
    }
    
    // Quantity input change
    if (this.quantityInputs) {
      this.quantityInputs.forEach((input) => {
        input.addEventListener('change', () => this.handleQuantityChange(input));
      });
    }
    
    // Cart form update
    if (this.cartForm) {
      this.cartForm.addEventListener('submit', (e) => this.handleCartUpdate(e));
    }
    
    // Quick view button click
    if (this.quickViewButtons) {
      this.quickViewButtons.forEach((button) => {
        button.addEventListener('click', (e) => this.handleQuickView(e, button));
      });
    }
    
    // Document events
    document.addEventListener('added_to_cart', (e) => this.handleAddedToCart(e));
    document.addEventListener('removed_from_cart', (e) => this.handleRemovedFromCart(e));
    document.addEventListener('wc_fragments_loaded', () => this.handleFragmentsLoaded());
    document.addEventListener('wc_fragments_refreshed', () => this.handleFragmentsRefreshed());
  }

  /**
   * Initialize quantity buttons
   */
  initQuantityButtons() {
    // Get all quantity inputs
    const quantityInputs = document.querySelectorAll('.quantity:not(.buttons-added) input.qty');
    
    // Skip if no quantity inputs
    if (!quantityInputs.length) {
      return;
    }
    
    // Add buttons to each quantity input
    quantityInputs.forEach((input) => {
      // Get parent
      const parent = input.parentNode;
      
      // Skip if already has buttons
      if (parent.classList.contains('buttons-added')) {
        return;
      }
      
      // Add buttons-added class
      parent.classList.add('buttons-added');
      
      // Create minus button
      const minusButton = document.createElement('button');
      minusButton.type = 'button';
      minusButton.className = 'minus';
      minusButton.textContent = '-';
      
      // Create plus button
      const plusButton = document.createElement('button');
      plusButton.type = 'button';
      plusButton.className = 'plus';
      plusButton.textContent = '+';
      
      // Add buttons
      parent.insertBefore(minusButton, input);
      parent.appendChild(plusButton);
      
      // Add event listeners
      minusButton.addEventListener('click', () => this.decreaseQuantity(input));
      plusButton.addEventListener('click', () => this.increaseQuantity(input));
    });
  }

  /**
   * Initialize mini cart
   */
  initMiniCart() {
    // Skip if no mini cart
    if (!this.miniCart) {
      return;
    }
    
    // Get elements
    const toggle = this.miniCart.querySelector('.mini-cart-toggle');
    const content = this.miniCart.querySelector('.mini-cart-content');
    
    // Skip if no toggle or content
    if (!toggle || !content) {
      return;
    }
    
    // Add click event to toggle
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      
      // Toggle active class
      this.miniCart.classList.toggle('is-active');
      
      // Update aria-expanded
      const isExpanded = this.miniCart.classList.contains('is-active');
      toggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
    });
    
    // Close mini cart when clicking outside
    document.addEventListener('click', (e) => {
      if (!this.miniCart.contains(e.target)) {
        this.miniCart.classList.remove('is-active');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
    
    // Close mini cart on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.miniCart.classList.contains('is-active')) {
        this.miniCart.classList.remove('is-active');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /**
   * Initialize quick view
   */
  initQuickView() {
    // Skip if no quick view buttons or modal
    if (!this.quickViewButtons.length || !this.quickViewModal) {
      return;
    }
    
    // Get elements
    const closeButton = this.quickViewModal.querySelector('.quick-view-close');
    const content = this.quickViewModal.querySelector('.quick-view-content');
    
    // Add click event to close button
    if (closeButton) {
      closeButton.addEventListener('click', (e) => {
        e.preventDefault();
        this.closeQuickView();
      });
    }
    
    // Close quick view when clicking outside content
    this.quickViewModal.addEventListener('click', (e) => {
      if (content && !content.contains(e.target) && e.target !== content) {
        this.closeQuickView();
      }
    });
    
    // Close quick view on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.quickViewModal.classList.contains('is-active')) {
        this.closeQuickView();
      }
    });
  }

  /**
   * Initialize product gallery
   */
  initProductGallery() {
    // Skip if no product galleries
    if (!this.productGalleries.length) {
      return;
    }
    
    // Initialize each gallery
    this.productGalleries.forEach((gallery) => {
      // Get elements
      const mainImage = gallery.querySelector('.woocommerce-product-gallery__image');
      const thumbnails = gallery.querySelectorAll('.woocommerce-product-gallery__image:not(:first-child), .woocommerce-product-gallery__thumb');
      
      // Skip if no main image or thumbnails
      if (!mainImage || !thumbnails.length) {
        return;
      }
      
      // Add click event to thumbnails
      thumbnails.forEach((thumbnail) => {
        thumbnail.addEventListener('click', (e) => {
          e.preventDefault();
          
          // Get image data
          const image = thumbnail.querySelector('img');
          const fullSrc = thumbnail.getAttribute('data-full-src') || thumbnail.getAttribute('href') || image.getAttribute('data-large_image');
          const fullWidth = thumbnail.getAttribute('data-full-width') || image.getAttribute('data-large_image_width');
          const fullHeight = thumbnail.getAttribute('data-full-height') || image.getAttribute('data-large_image_height');
          const caption = thumbnail.getAttribute('data-caption') || image.getAttribute('data-caption');
          
          // Update main image
          const mainImg = mainImage.querySelector('img');
          const mainLink = mainImage.querySelector('a');
          
          if (mainImg) {
            mainImg.setAttribute('src', fullSrc);
            mainImg.setAttribute('srcset', '');
            mainImg.setAttribute('sizes', '');
            mainImg.setAttribute('data-large_image', fullSrc);
            mainImg.setAttribute('data-large_image_width', fullWidth);
            mainImg.setAttribute('data-large_image_height', fullHeight);
            
            if (caption) {
              mainImg.setAttribute('data-caption', caption);
            }
          }
          
          if (mainLink) {
            mainLink.setAttribute('href', fullSrc);
            
            if (caption) {
              mainLink.setAttribute('data-caption', caption);
            }
          }
          
          // Remove active class from all thumbnails
          thumbnails.forEach((thumb) => {
            thumb.classList.remove('is-active');
          });
          
          // Add active class to clicked thumbnail
          thumbnail.classList.add('is-active');
        });
      });
    });
  }

  /**
   * Initialize variation forms
   */
  initVariationForms() {
    // Skip if no variation forms
    if (!this.variationForms.length) {
      return;
    }
    
    // Initialize each form
    this.variationForms.forEach((form) => {
      // Get elements
      const selects = form.querySelectorAll('.variations select');
      const resetButton = form.querySelector('.reset_variations');
      const variationData = form.getAttribute('data-product_variations');
      
      // Skip if no selects or variation data
      if (!selects.length || !variationData) {
        return;
      }
      
      // Parse variation data
      let variations = [];
      try {
        variations = JSON.parse(variationData);
      } catch (e) {
        console.error('Error parsing variation data:', e);
        return;
      }
      
      // Add change event to selects
      selects.forEach((select) => {
        select.addEventListener('change', () => this.handleVariationChange(form, selects, variations));
      });
      
      // Add click event to reset button
      if (resetButton) {
        resetButton.addEventListener('click', (e) => {
          e.preventDefault();
          this.resetVariations(form, selects);
        });
      }
      
      // Initial check
      this.handleVariationChange(form, selects, variations);
    });
  }

  /**
   * Handle add to cart
   *
   * @param {Event} e Click event
   * @param {HTMLElement} button Add to cart button
   */
  handleAddToCart(e, button) {
    // Skip if button is disabled
    if (button.classList.contains('disabled') || button.disabled) {
      e.preventDefault();
      return;
    }
    
    // Skip if not AJAX add to cart
    if (!button.classList.contains('ajax_add_to_cart')) {
      return;
    }
    
    // Prevent default
    e.preventDefault();
    
    // Add loading class
    button.classList.add('loading');
    
    // Get product data
    const productId = button.getAttribute('data-product_id');
    const quantity = button.getAttribute('data-quantity') || 1;
    
    // Add to cart via AJAX
    this.ajaxAddToCart(productId, quantity, button);
  }

  /**
   * AJAX add to cart
   *
   * @param {string} productId Product ID
   * @param {number} quantity Quantity
   * @param {HTMLElement} button Add to cart button
   */
  ajaxAddToCart(productId, quantity, button) {
    // Create form data
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('add-to-cart', productId);
    
    // Send AJAX request
    fetch(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
    })
      .then((response) => response.json())
      .then((data) => {
        // Remove loading class
        button.classList.remove('loading');
        
        // Check for error
        if (data.error) {
          // Add error class
          button.classList.add('error');
          
          // Show error message
          this.showNotice(data.error, 'error');
          
          // Remove error class after delay
          setTimeout(() => {
            button.classList.remove('error');
          }, 3000);
          
          return;
        }
        
        // Add added class
        button.classList.add('added');
        
        // Update fragments
        this.updateFragments(data.fragments);
        
        // Trigger added_to_cart event
        const event = new CustomEvent('added_to_cart', {
          detail: {
            button: button,
            product_id: productId,
            quantity: quantity,
            fragments: data.fragments,
            cart_hash: data.cart_hash,
          },
        });
        
        document.dispatchEvent(event);
        
        // Show success message
        if (data.message) {
          this.showNotice(data.message, 'success');
        }
      })
      .catch((error) => {
        console.error('Error adding to cart:', error);
        
        // Remove loading class
        button.classList.remove('loading');
        
        // Add error class
        button.classList.add('error');
        
        // Show error message
        this.showNotice('Error adding to cart. Please try again.', 'error');
        
        // Remove error class after delay
        setTimeout(() => {
          button.classList.remove('error');
        }, 3000);
      });
  }

  /**
   * Handle quantity change
   *
   * @param {HTMLElement} input Quantity input
   */
  handleQuantityChange(input) {
    // Get min and max values
    const min = parseFloat(input.getAttribute('min') || 0);
    const max = parseFloat(input.getAttribute('max') || 0);
    const step = parseFloat(input.getAttribute('step') || 1);
    
    // Get current value
    let value = parseFloat(input.value);
    
    // Validate value
    if (isNaN(value) || value < min) {
      value = min;
    }
    
    if (max > 0 && value > max) {
      value = max;
    }
    
    // Round to step
    value = Math.round(value / step) * step;
    
    // Update input value
    input.value = value;
  }

  /**
   * Decrease quantity
   *
   * @param {HTMLElement} input Quantity input
   */
  decreaseQuantity(input) {
    // Get current value
    let value = parseFloat(input.value);
    
    // Get min value and step
    const min = parseFloat(input.getAttribute('min') || 0);
    const step = parseFloat(input.getAttribute('step') || 1);
    
    // Decrease value
    value -= step;
    
    // Validate value
    if (isNaN(value) || value < min) {
      value = min;
    }
    
    // Update input value
    input.value = value;
    
    // Trigger change event
    input.dispatchEvent(new Event('change', { bubbles: true }));
  }

  /**
   * Increase quantity
   *
   * @param {HTMLElement} input Quantity input
   */
  increaseQuantity(input) {
    // Get current value
    let value = parseFloat(input.value);
    
    // Get max value and step
    const max = parseFloat(input.getAttribute('max') || 0);
    const step = parseFloat(input.getAttribute('step') || 1);
    
    // Increase value
    value += step;
    
    // Validate value
    if (isNaN(value)) {
      value = step;
    }
    
    if (max > 0 && value > max) {
      value = max;
    }
    
    // Update input value
    input.value = value;
    
    // Trigger change event
    input.dispatchEvent(new Event('change', { bubbles: true }));
  }

  /**
   * Handle cart update
   *
   * @param {Event} e Submit event
   */
  handleCartUpdate(e) {
    // Skip if not AJAX cart
    if (!this.body.classList.contains('woocommerce-cart') || !this.body.classList.contains('ajax-cart')) {
      return;
    }
    
    // Prevent default
    e.preventDefault();
    
    // Add loading class
    this.cartForm.classList.add('processing');
    
    // Get form data
    const formData = new FormData(this.cartForm);
    formData.append('_wp_http_referer', window.location.pathname);
    
    // Send AJAX request
    fetch(wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'update_cart'), {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
    })
      .then((response) => response.json())
      .then((data) => {
        // Remove loading class
        this.cartForm.classList.remove('processing');
        
        // Check for error
        if (data.error) {
          // Show error message
          this.showNotice(data.error, 'error');
          return;
        }
        
        // Update fragments
        this.updateFragments(data.fragments);
        
        // Update cart hash
        this.updateCartHash(data.cart_hash);
        
        // Show success message
        if (data.message) {
          this.showNotice(data.message, 'success');
        }
      })
      .catch((error) => {
        console.error('Error updating cart:', error);
        
        // Remove loading class
        this.cartForm.classList.remove('processing');
        
        // Show error message
        this.showNotice('Error updating cart. Please try again.', 'error');
      });
  }

  /**
   * Handle quick view
   *
   * @param {Event} e Click event
   * @param {HTMLElement} button Quick view button
   */
  handleQuickView(e, button) {
    // Prevent default
    e.preventDefault();
    
    // Skip if no quick view modal
    if (!this.quickViewModal) {
      return;
    }
    
    // Get product ID
    const productId = button.getAttribute('data-product_id');
    
    // Skip if no product ID
    if (!productId) {
      return;
    }
    
    // Add loading class
    button.classList.add('loading');
    
    // Get quick view content
    this.getQuickViewContent(productId)
      .then((content) => {
        // Remove loading class
        button.classList.remove('loading');
        
        // Update modal content
        const contentContainer = this.quickViewModal.querySelector('.quick-view-content');
        if (contentContainer) {
          contentContainer.innerHTML = content;
        }
        
        // Show modal
        this.quickViewModal.classList.add('is-active');
        
        // Initialize components
        this.initQuantityButtons();
        this.initProductGallery();
        this.initVariationForms();
      })
      .catch((error) => {
        console.error('Error loading quick view:', error);
        
        // Remove loading class
        button.classList.remove('loading');
        
        // Show error message
        this.showNotice('Error loading quick view. Please try again.', 'error');
      });
  }

  /**
   * Get quick view content
   *
   * @param {string} productId Product ID
   * @returns {Promise<string>} Quick view content
   */
  getQuickViewContent(productId) {
    // Create form data
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('action', 'aqualuxe_quick_view');
    formData.append('security', aqualuxe_woocommerce_params.quick_view_nonce);
    
    // Send AJAX request
    return fetch(aqualuxe_woocommerce_params.ajax_url, {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
    })
      .then((response) => response.text());
  }

  /**
   * Close quick view
   */
  closeQuickView() {
    // Skip if no quick view modal
    if (!this.quickViewModal) {
      return;
    }
    
    // Hide modal
    this.quickViewModal.classList.remove('is-active');
    
    // Clear content after animation
    setTimeout(() => {
      const contentContainer = this.quickViewModal.querySelector('.quick-view-content');
      if (contentContainer) {
        contentContainer.innerHTML = '';
      }
    }, 300);
  }

  /**
   * Handle variation change
   *
   * @param {HTMLElement} form Variation form
   * @param {NodeList} selects Variation selects
   * @param {Array} variations Product variations
   */
  handleVariationChange(form, selects, variations) {
    // Get selected attributes
    const selectedAttributes = {};
    let allSelected = true;
    
    selects.forEach((select) => {
      const attribute = select.getAttribute('name');
      const value = select.value;
      
      selectedAttributes[attribute] = value;
      
      if (!value) {
        allSelected = false;
      }
    });
    
    // Find matching variation
    let matchingVariation = null;
    
    if (allSelected) {
      matchingVariation = this.findMatchingVariation(variations, selectedAttributes);
    }
    
    // Update form
    this.updateVariationForm(form, matchingVariation, allSelected);
  }

  /**
   * Find matching variation
   *
   * @param {Array} variations Product variations
   * @param {Object} selectedAttributes Selected attributes
   * @returns {Object|null} Matching variation or null
   */
  findMatchingVariation(variations, selectedAttributes) {
    // Loop through variations
    for (let i = 0; i < variations.length; i++) {
      const variation = variations[i];
      const attributes = variation.attributes;
      let match = true;
      
      // Check if all selected attributes match
      for (const attribute in selectedAttributes) {
        if (!selectedAttributes.hasOwnProperty(attribute)) {
          continue;
        }
        
        const value = selectedAttributes[attribute];
        const variationValue = attributes[attribute];
        
        // Skip if variation doesn't have this attribute
        if (typeof variationValue === 'undefined') {
          match = false;
          break;
        }
        
        // If variation has any value or matches the selected value
        if (variationValue === '' || variationValue === value) {
          continue;
        }
        
        match = false;
        break;
      }
      
      // Return variation if match found
      if (match) {
        return variation;
      }
    }
    
    return null;
  }

  /**
   * Update variation form
   *
   * @param {HTMLElement} form Variation form
   * @param {Object|null} variation Matching variation or null
   * @param {boolean} allSelected Whether all attributes are selected
   */
  updateVariationForm(form, variation, allSelected) {
    // Get elements
    const addToCartButton = form.querySelector('.single_add_to_cart_button');
    const priceElement = form.closest('.product').querySelector('.price');
    const availabilityElement = form.querySelector('.woocommerce-variation-availability');
    const variationIdInput = form.querySelector('input[name="variation_id"]');
    const resetButton = form.querySelector('.reset_variations');
    
    // Show or hide reset button
    if (resetButton) {
      resetButton.style.display = allSelected ? 'inline-block' : 'none';
    }
    
    // No variation selected
    if (!allSelected) {
      // Reset variation ID
      if (variationIdInput) {
        variationIdInput.value = '';
      }
      
      // Disable add to cart button
      if (addToCartButton) {
        addToCartButton.classList.add('disabled');
        addToCartButton.setAttribute('disabled', 'disabled');
      }
      
      return;
    }
    
    // No matching variation
    if (!variation) {
      // Reset variation ID
      if (variationIdInput) {
        variationIdInput.value = '';
      }
      
      // Disable add to cart button
      if (addToCartButton) {
        addToCartButton.classList.add('disabled');
        addToCartButton.setAttribute('disabled', 'disabled');
      }
      
      // Show not available message
      if (availabilityElement) {
        availabilityElement.innerHTML = '<p class="stock out-of-stock">This product is unavailable. Please select a different combination.</p>';
      }
      
      return;
    }
    
    // Set variation ID
    if (variationIdInput) {
      variationIdInput.value = variation.variation_id;
    }
    
    // Update price
    if (priceElement && variation.price_html) {
      priceElement.innerHTML = variation.price_html;
    }
    
    // Update availability
    if (availabilityElement) {
      if (variation.is_in_stock) {
        availabilityElement.innerHTML = '<p class="stock in-stock">' + variation.availability_html + '</p>';
      } else {
        availabilityElement.innerHTML = '<p class="stock out-of-stock">' + variation.availability_html + '</p>';
      }
    }
    
    // Update add to cart button
    if (addToCartButton) {
      if (variation.is_purchasable && variation.is_in_stock) {
        addToCartButton.classList.remove('disabled');
        addToCartButton.removeAttribute('disabled');
      } else {
        addToCartButton.classList.add('disabled');
        addToCartButton.setAttribute('disabled', 'disabled');
      }
    }
    
    // Update image
    if (variation.image && variation.image.src) {
      this.updateVariationImage(form, variation.image);
    }
  }

  /**
   * Update variation image
   *
   * @param {HTMLElement} form Variation form
   * @param {Object} image Variation image
   */
  updateVariationImage(form, image) {
    // Get product gallery
    const gallery = form.closest('.product').querySelector('.woocommerce-product-gallery');
    
    // Skip if no gallery
    if (!gallery) {
      return;
    }
    
    // Get main image
    const mainImage = gallery.querySelector('.woocommerce-product-gallery__image');
    
    // Skip if no main image
    if (!mainImage) {
      return;
    }
    
    // Update main image
    const mainImg = mainImage.querySelector('img');
    const mainLink = mainImage.querySelector('a');
    
    if (mainImg) {
      mainImg.setAttribute('src', image.src);
      mainImg.setAttribute('srcset', image.srcset || '');
      mainImg.setAttribute('sizes', image.sizes || '');
      mainImg.setAttribute('data-large_image', image.full_src);
      mainImg.setAttribute('data-large_image_width', image.full_src_w);
      mainImg.setAttribute('data-large_image_height', image.full_src_h);
      
      if (image.caption) {
        mainImg.setAttribute('data-caption', image.caption);
      }
    }
    
    if (mainLink) {
      mainLink.setAttribute('href', image.full_src);
      
      if (image.caption) {
        mainLink.setAttribute('data-caption', image.caption);
      }
    }
  }

  /**
   * Reset variations
   *
   * @param {HTMLElement} form Variation form
   * @param {NodeList} selects Variation selects
   */
  resetVariations(form, selects) {
    // Reset selects
    selects.forEach((select) => {
      select.value = '';
    });
    
    // Trigger change event on first select
    if (selects.length) {
      selects[0].dispatchEvent(new Event('change', { bubbles: true }));
    }
  }

  /**
   * Handle added to cart
   *
   * @param {CustomEvent} e Added to cart event
   */
  handleAddedToCart(e) {
    // Open mini cart
    if (this.miniCart) {
      this.miniCart.classList.add('is-active');
      
      // Update aria-expanded
      const toggle = this.miniCart.querySelector('.mini-cart-toggle');
      if (toggle) {
        toggle.setAttribute('aria-expanded', 'true');
      }
      
      // Close mini cart after delay
      setTimeout(() => {
        this.miniCart.classList.remove('is-active');
        
        if (toggle) {
          toggle.setAttribute('aria-expanded', 'false');
        }
      }, 5000);
    }
  }

  /**
   * Handle removed from cart
   *
   * @param {CustomEvent} e Removed from cart event
   */
  handleRemovedFromCart(e) {
    // Update fragments
    if (e.detail && e.detail.fragments) {
      this.updateFragments(e.detail.fragments);
    }
  }

  /**
   * Handle fragments loaded
   */
  handleFragmentsLoaded() {
    // Re-initialize mini cart
    this.initMiniCart();
  }

  /**
   * Handle fragments refreshed
   */
  handleFragmentsRefreshed() {
    // Re-initialize mini cart
    this.initMiniCart();
  }

  /**
   * Update fragments
   *
   * @param {Object} fragments Cart fragments
   */
  updateFragments(fragments) {
    // Skip if no fragments
    if (!fragments) {
      return;
    }
    
    // Update each fragment
    for (const selector in fragments) {
      if (!fragments.hasOwnProperty(selector)) {
        continue;
      }
      
      const fragment = fragments[selector];
      const elements = document.querySelectorAll(selector);
      
      // Skip if no elements
      if (!elements.length) {
        continue;
      }
      
      // Update each element
      elements.forEach((element) => {
        // Create temporary container
        const temp = document.createElement('div');
        temp.innerHTML = fragment;
        
        // Get fragment content
        const fragmentContent = temp.firstChild;
        
        // Replace element with fragment
        if (fragmentContent) {
          element.parentNode.replaceChild(fragmentContent, element);
        }
      });
    }
    
    // Trigger fragments refreshed event
    document.dispatchEvent(new Event('wc_fragments_refreshed'));
  }

  /**
   * Update cart hash
   *
   * @param {string} cartHash Cart hash
   */
  updateCartHash(cartHash) {
    // Skip if no cart hash
    if (!cartHash) {
      return;
    }
    
    // Set cart hash in session storage
    sessionStorage.setItem('wc_cart_hash', cartHash);
  }

  /**
   * Show notice
   *
   * @param {string} message Notice message
   * @param {string} type Notice type (success, error, info)
   */
  showNotice(message, type = 'success') {
    // Create notice element
    const notice = document.createElement('div');
    notice.className = `woocommerce-notice woocommerce-${type}`;
    notice.innerHTML = message;
    
    // Get notices container
    let noticesContainer = document.querySelector('.woocommerce-notices-wrapper');
    
    // Create container if it doesn't exist
    if (!noticesContainer) {
      noticesContainer = document.createElement('div');
      noticesContainer.className = 'woocommerce-notices-wrapper';
      document.body.insertBefore(noticesContainer, document.body.firstChild);
    }
    
    // Add notice to container
    noticesContainer.appendChild(notice);
    
    // Remove notice after delay
    setTimeout(() => {
      notice.classList.add('is-hidden');
      
      // Remove from DOM after animation
      setTimeout(() => {
        notice.remove();
      }, 300);
    }, 5000);
  }
}

// Initialize WooCommerce functionality
document.addEventListener('DOMContentLoaded', () => {
  window.aqualuxeWooCommerce = new AquaLuxeWooCommerce();
});