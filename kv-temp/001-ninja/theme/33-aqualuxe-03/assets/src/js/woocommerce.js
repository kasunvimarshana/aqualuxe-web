/**
 * AquaLuxe Theme - WooCommerce Scripts
 *
 * This file contains WooCommerce specific functionality including:
 * - AJAX add to cart
 * - Product gallery
 * - Product quantity controls
 * - Product variations
 * - Mini cart updates
 */

(function() {
  'use strict';
  
  // Check if WooCommerce is active
  const isWooCommerceActive = document.body.classList.contains('woocommerce') || document.body.classList.contains('woocommerce-page');
  
  if (!isWooCommerceActive) {
    return;
  }
  
  /**
   * Initialize all WooCommerce functionality
   */
  function init() {
    initProductGallery();
    initAjaxAddToCart();
    initMiniCart();
    initProductTabs();
    initQuantityControls();
    initVariationSwatches();
    initQuickView();
    initWishlist();
    initProductFilters();
  }
  
  /**
   * Initialize product gallery
   */
  function initProductGallery() {
    const galleries = document.querySelectorAll('.woocommerce-product-gallery');
    
    galleries.forEach(function(gallery) {
      const mainImage = gallery.querySelector('.woocommerce-product-gallery__image');
      const thumbnails = gallery.querySelectorAll('.flex-control-thumbs li img');
      
      if (!mainImage || !thumbnails.length) {
        return;
      }
      
      // Set up thumbnails
      thumbnails.forEach(function(thumbnail, index) {
        thumbnail.addEventListener('click', function(event) {
          event.preventDefault();
          
          // Get full size image URL
          const fullSizeUrl = this.getAttribute('data-large_image');
          const fullSizeWidth = this.getAttribute('data-large_image_width');
          const fullSizeHeight = this.getAttribute('data-large_image_height');
          
          if (fullSizeUrl) {
            // Update main image
            const mainImg = mainImage.querySelector('img');
            if (mainImg) {
              mainImg.src = fullSizeUrl;
              mainImg.setAttribute('srcset', '');
              mainImg.setAttribute('data-src', fullSizeUrl);
              mainImg.setAttribute('data-large_image', fullSizeUrl);
              mainImg.setAttribute('data-large_image_width', fullSizeWidth);
              mainImg.setAttribute('data-large_image_height', fullSizeHeight);
              mainImg.width = fullSizeWidth;
              mainImg.height = fullSizeHeight;
            }
            
            // Update active thumbnail
            thumbnails.forEach(function(thumb) {
              thumb.classList.remove('flex-active');
            });
            this.classList.add('flex-active');
          }
        });
      });
      
      // Initialize zoom if available
      if (typeof jQuery !== 'undefined' && jQuery.fn.zoom) {
        jQuery('.woocommerce-product-gallery__image').zoom({
          touch: false
        });
      }
      
      // Initialize lightbox if available
      if (typeof jQuery !== 'undefined' && jQuery.fn.prettyPhoto) {
        jQuery('.woocommerce-product-gallery__wrapper').find('a').prettyPhoto({
          hook: 'data-rel',
          social_tools: false,
          theme: 'pp_woocommerce',
          horizontal_padding: 20,
          opacity: 0.8,
          deeplinking: false
        });
      }
    });
  }
  
  /**
   * Initialize AJAX add to cart
   */
  function initAjaxAddToCart() {
    const addToCartButtons = document.querySelectorAll('.add_to_cart_button:not(.product_type_variable):not(.product_type_grouped)');
    
    addToCartButtons.forEach(function(button) {
      button.addEventListener('click', function(event) {
        if (!button.classList.contains('ajax_add_to_cart')) {
          return;
        }
        
        event.preventDefault();
        
        const productId = button.getAttribute('data-product_id');
        const quantity = button.getAttribute('data-quantity') || 1;
        
        if (!productId) {
          return;
        }
        
        // Add loading state
        button.classList.add('loading');
        
        // Create form data
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('add-to-cart', productId);
        
        // Send AJAX request
        fetch(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        })
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          if (!data || data.error) {
            throw new Error(data.error || 'Error adding to cart');
          }
          
          // Update cart fragments
          if (data.fragments) {
            updateCartFragments(data.fragments);
          }
          
          // Remove loading state
          button.classList.remove('loading');
          button.classList.add('added');
          
          // Add view cart text
          if (!button.nextElementSibling || !button.nextElementSibling.classList.contains('added_to_cart')) {
            const viewCartLink = document.createElement('a');
            viewCartLink.href = wc_add_to_cart_params.cart_url;
            viewCartLink.className = 'added_to_cart wc-forward';
            viewCartLink.textContent = wc_add_to_cart_params.i18n_view_cart;
            button.insertAdjacentElement('afterend', viewCartLink);
          }
          
          // Trigger event
          document.body.dispatchEvent(new CustomEvent('wc_fragment_refresh'));
          document.body.dispatchEvent(new CustomEvent('added_to_cart', {
            detail: {
              button: button,
              product_id: productId
            }
          }));
        })
        .catch(function(error) {
          console.error('Error adding to cart:', error);
          button.classList.remove('loading');
          
          // Show error message
          const errorMessage = document.createElement('div');
          errorMessage.className = 'woocommerce-error';
          errorMessage.textContent = error.message || 'Error adding to cart. Please try again.';
          
          const noticesContainer = document.querySelector('.woocommerce-notices-wrapper');
          if (noticesContainer) {
            noticesContainer.appendChild(errorMessage);
            
            // Remove error after 5 seconds
            setTimeout(function() {
              errorMessage.remove();
            }, 5000);
          }
        });
      });
    });
    
    /**
     * Update cart fragments
     * @param {Object} fragments - Cart fragments to update
     */
    function updateCartFragments(fragments) {
      if (typeof fragments !== 'object') {
        return;
      }
      
      Object.keys(fragments).forEach(function(key) {
        const fragment = fragments[key];
        const elements = document.querySelectorAll(key);
        
        elements.forEach(function(element) {
          const newElement = document.createRange().createContextualFragment(fragment);
          element.parentNode.replaceChild(newElement, element);
        });
      });
      
      // Store fragments in session storage
      try {
        sessionStorage.setItem('wc_fragments', JSON.stringify(fragments));
        sessionStorage.setItem('wc_fragments_expire', Date.now() + (86400 * 1000));
      } catch (e) {
        console.error('Session storage error:', e);
      }
    }
  }
  
  /**
   * Initialize mini cart
   */
  function initMiniCart() {
    const miniCart = document.querySelector('.mini-cart');
    const cartToggle = document.querySelector('.cart-toggle');
    
    if (!miniCart || !cartToggle) {
      return;
    }
    
    // Hide mini cart initially
    miniCart.hidden = true;
    
    // Toggle mini cart
    cartToggle.addEventListener('click', function(event) {
      event.preventDefault();
      
      const isExpanded = cartToggle.getAttribute('aria-expanded') === 'true';
      
      cartToggle.setAttribute('aria-expanded', !isExpanded);
      miniCart.hidden = isExpanded;
      
      if (!isExpanded) {
        // Add overlay
        const overlay = document.createElement('div');
        overlay.className = 'mini-cart-overlay';
        document.body.appendChild(overlay);
        
        // Close mini cart when clicking overlay
        overlay.addEventListener('click', function() {
          cartToggle.setAttribute('aria-expanded', 'false');
          miniCart.hidden = true;
          overlay.remove();
        });
      } else {
        // Remove overlay
        const overlay = document.querySelector('.mini-cart-overlay');
        if (overlay) {
          overlay.remove();
        }
      }
    });
    
    // Close mini cart when pressing Escape
    document.addEventListener('keyup', function(event) {
      if (event.key === 'Escape' && !miniCart.hidden) {
        cartToggle.setAttribute('aria-expanded', 'false');
        miniCart.hidden = true;
        
        // Remove overlay
        const overlay = document.querySelector('.mini-cart-overlay');
        if (overlay) {
          overlay.remove();
        }
      }
    });
    
    // Update mini cart on fragment refresh
    document.body.addEventListener('wc_fragment_refresh', function() {
      // Refresh cart fragments
      refreshCartFragments();
    });
    
    /**
     * Refresh cart fragments
     */
    function refreshCartFragments() {
      fetch(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'), {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      })
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        if (data && data.fragments) {
          // Update cart fragments
          Object.keys(data.fragments).forEach(function(key) {
            const fragment = data.fragments[key];
            const elements = document.querySelectorAll(key);
            
            elements.forEach(function(element) {
              const newElement = document.createRange().createContextualFragment(fragment);
              element.parentNode.replaceChild(newElement, element);
            });
          });
          
          // Store fragments in session storage
          try {
            sessionStorage.setItem('wc_fragments', JSON.stringify(data.fragments));
            sessionStorage.setItem('wc_fragments_expire', Date.now() + (86400 * 1000));
          } catch (e) {
            console.error('Session storage error:', e);
          }
        }
      })
      .catch(function(error) {
        console.error('Error refreshing cart fragments:', error);
      });
    }
  }
  
  /**
   * Initialize product tabs
   */
  function initProductTabs() {
    const tabContainers = document.querySelectorAll('.woocommerce-tabs');
    
    tabContainers.forEach(function(container) {
      const tabList = container.querySelector('.tabs');
      const tabButtons = container.querySelectorAll('.tabs li a');
      const tabPanels = container.querySelectorAll('.woocommerce-Tabs-panel');
      
      if (!tabList || !tabButtons.length || !tabPanels.length) {
        return;
      }
      
      // Set ARIA roles and attributes
      tabList.setAttribute('role', 'tablist');
      
      tabButtons.forEach(function(button, index) {
        const panel = tabPanels[index];
        
        if (!panel) {
          return;
        }
        
        const id = panel.id || `tab-panel-${index}`;
        panel.id = id;
        
        button.setAttribute('role', 'tab');
        button.setAttribute('aria-controls', id);
        button.setAttribute('id', `tab-button-${index}`);
        button.setAttribute('tabindex', button.parentNode.classList.contains('active') ? '0' : '-1');
        button.setAttribute('aria-selected', button.parentNode.classList.contains('active') ? 'true' : 'false');
        
        panel.setAttribute('role', 'tabpanel');
        panel.setAttribute('aria-labelledby', `tab-button-${index}`);
        panel.setAttribute('tabindex', '0');
        
        if (!button.parentNode.classList.contains('active')) {
          panel.hidden = true;
        }
        
        // Handle click events
        button.addEventListener('click', function(event) {
          event.preventDefault();
          activateTab(button, container);
        });
        
        // Handle keyboard navigation
        button.addEventListener('keydown', function(event) {
          const buttons = Array.from(tabButtons);
          const index = buttons.indexOf(button);
          let targetButton;
          
          switch (event.key) {
            case 'ArrowLeft':
              targetButton = buttons[index - 1] || buttons[buttons.length - 1];
              break;
            case 'ArrowRight':
              targetButton = buttons[index + 1] || buttons[0];
              break;
            case 'Home':
              targetButton = buttons[0];
              break;
            case 'End':
              targetButton = buttons[buttons.length - 1];
              break;
            default:
              return;
          }
          
          event.preventDefault();
          targetButton.focus();
          activateTab(targetButton, container);
        });
      });
    });
    
    /**
     * Activate a tab
     * @param {Element} tab - The tab button to activate
     * @param {Element} container - The tab container
     */
    function activateTab(tab, container) {
      const tabButtons = container.querySelectorAll('.tabs li a');
      const tabPanels = container.querySelectorAll('.woocommerce-Tabs-panel');
      const targetPanel = container.querySelector(`#${tab.getAttribute('aria-controls')}`);
      
      // Deactivate all tabs
      tabButtons.forEach(function(button) {
        button.parentNode.classList.remove('active');
        button.setAttribute('aria-selected', 'false');
        button.setAttribute('tabindex', '-1');
      });
      
      // Hide all panels
      tabPanels.forEach(function(panel) {
        panel.hidden = true;
      });
      
      // Activate selected tab
      tab.parentNode.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      tab.setAttribute('tabindex', '0');
      
      // Show selected panel
      if (targetPanel) {
        targetPanel.hidden = false;
      }
    }
  }
  
  /**
   * Initialize quantity controls
   */
  function initQuantityControls() {
    const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
    
    quantityInputs.forEach(function(input) {
      const wrapper = input.parentNode;
      
      // Create minus button if it doesn't exist
      if (!wrapper.querySelector('.quantity-minus')) {
        const minusButton = document.createElement('button');
        minusButton.type = 'button';
        minusButton.className = 'quantity-minus';
        minusButton.textContent = '-';
        minusButton.setAttribute('aria-label', 'Decrease quantity');
        wrapper.insertBefore(minusButton, input);
        
        // Decrease quantity when clicked
        minusButton.addEventListener('click', function() {
          const currentValue = parseInt(input.value, 10);
          const min = input.hasAttribute('min') ? parseInt(input.getAttribute('min'), 10) : 1;
          
          if (currentValue > min) {
            input.value = currentValue - 1;
            input.dispatchEvent(new Event('change', { bubbles: true }));
          }
        });
      }
      
      // Create plus button if it doesn't exist
      if (!wrapper.querySelector('.quantity-plus')) {
        const plusButton = document.createElement('button');
        plusButton.type = 'button';
        plusButton.className = 'quantity-plus';
        plusButton.textContent = '+';
        plusButton.setAttribute('aria-label', 'Increase quantity');
        wrapper.appendChild(plusButton);
        
        // Increase quantity when clicked
        plusButton.addEventListener('click', function() {
          const currentValue = parseInt(input.value, 10);
          const max = input.hasAttribute('max') ? parseInt(input.getAttribute('max'), 10) : null;
          
          if (max === null || currentValue < max) {
            input.value = currentValue + 1;
            input.dispatchEvent(new Event('change', { bubbles: true }));
          }
        });
      }
    });
  }
  
  /**
   * Initialize variation swatches
   */
  function initVariationSwatches() {
    const variationForms = document.querySelectorAll('.variations_form');
    
    variationForms.forEach(function(form) {
      const variationRows = form.querySelectorAll('.variations tr');
      
      variationRows.forEach(function(row) {
        const label = row.querySelector('label');
        const select = row.querySelector('select');
        
        if (!label || !select) {
          return;
        }
        
        const attributeName = select.getAttribute('name');
        const swatchesContainer = document.createElement('div');
        swatchesContainer.className = 'variation-swatches';
        
        // Get options
        const options = Array.from(select.options).filter(option => option.value);
        
        // Create swatches
        options.forEach(function(option) {
          const swatch = document.createElement('div');
          swatch.className = 'variation-swatch';
          swatch.setAttribute('data-value', option.value);
          swatch.setAttribute('role', 'radio');
          swatch.setAttribute('aria-checked', 'false');
          swatch.setAttribute('tabindex', '0');
          swatch.setAttribute('aria-label', option.textContent);
          
          // Check if it's a color swatch
          if (attributeName.includes('color') || attributeName.includes('colour')) {
            swatch.className += ' color-swatch';
            swatch.style.backgroundColor = option.value.toLowerCase();
            
            // Add tooltip
            const tooltip = document.createElement('span');
            tooltip.className = 'swatch-tooltip';
            tooltip.textContent = option.textContent;
            swatch.appendChild(tooltip);
          } else {
            swatch.textContent = option.textContent;
          }
          
          // Select variation when swatch is clicked
          swatch.addEventListener('click', function() {
            selectVariation(select, option.value);
            updateSwatchSelection(swatchesContainer, this);
          });
          
          // Handle keyboard navigation
          swatch.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
              event.preventDefault();
              this.click();
            }
          });
          
          swatchesContainer.appendChild(swatch);
        });
        
        // Add reset button
        const resetButton = document.createElement('div');
        resetButton.className = 'variation-swatch reset-variation';
        resetButton.textContent = 'Clear';
        resetButton.setAttribute('role', 'button');
        resetButton.setAttribute('tabindex', '0');
        resetButton.setAttribute('aria-label', 'Clear selection');
        
        resetButton.addEventListener('click', function() {
          selectVariation(select, '');
          updateSwatchSelection(swatchesContainer, null);
        });
        
        resetButton.addEventListener('keydown', function(event) {
          if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            this.click();
          }
        });
        
        swatchesContainer.appendChild(resetButton);
        
        // Hide select and add swatches
        select.style.display = 'none';
        select.parentNode.appendChild(swatchesContainer);
        
        // Update swatches when select changes
        select.addEventListener('change', function() {
          const value = this.value;
          const swatch = swatchesContainer.querySelector(`[data-value="${value}"]`);
          updateSwatchSelection(swatchesContainer, swatch);
        });
      });
    });
    
    /**
     * Select a variation
     * @param {Element} select - The select element
     * @param {string} value - The value to select
     */
    function selectVariation(select, value) {
      select.value = value;
      select.dispatchEvent(new Event('change', { bubbles: true }));
    }
    
    /**
     * Update swatch selection
     * @param {Element} container - The swatches container
     * @param {Element} selectedSwatch - The selected swatch
     */
    function updateSwatchSelection(container, selectedSwatch) {
      const swatches = container.querySelectorAll('.variation-swatch');
      
      swatches.forEach(function(swatch) {
        swatch.classList.remove('selected');
        swatch.setAttribute('aria-checked', 'false');
      });
      
      if (selectedSwatch) {
        selectedSwatch.classList.add('selected');
        selectedSwatch.setAttribute('aria-checked', 'true');
      }
    }
  }
  
  /**
   * Initialize quick view
   */
  function initQuickView() {
    const quickViewButtons = document.querySelectorAll('.quick-view-button');
    
    quickViewButtons.forEach(function(button) {
      button.addEventListener('click', function(event) {
        event.preventDefault();
        
        const productId = button.getAttribute('data-product-id');
        
        if (!productId) {
          return;
        }
        
        // Add loading state
        button.classList.add('loading');
        
        // Create form data
        const formData = new FormData();
        formData.append('action', 'aqualuxe_quick_view');
        formData.append('product_id', productId);
        formData.append('security', aqualuxe_params.quick_view_nonce);
        
        // Send AJAX request
        fetch(aqualuxe_params.ajax_url, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        })
        .then(function(response) {
          return response.text();
        })
        .then(function(html) {
          // Remove loading state
          button.classList.remove('loading');
          
          // Create modal
          const modal = document.createElement('div');
          modal.className = 'quick-view-modal';
          modal.setAttribute('role', 'dialog');
          modal.setAttribute('aria-modal', 'true');
          modal.setAttribute('aria-labelledby', 'quick-view-title');
          
          // Add content
          const content = document.createElement('div');
          content.className = 'quick-view-content';
          content.innerHTML = html;
          
          // Add close button
          const closeButton = document.createElement('button');
          closeButton.className = 'quick-view-close';
          closeButton.setAttribute('aria-label', 'Close');
          closeButton.innerHTML = '&times;';
          content.appendChild(closeButton);
          
          modal.appendChild(content);
          document.body.appendChild(modal);
          
          // Initialize product gallery
          initProductGallery();
          
          // Initialize quantity controls
          initQuantityControls();
          
          // Initialize variation swatches
          initVariationSwatches();
          
          // Close modal when close button is clicked
          closeButton.addEventListener('click', function() {
            modal.remove();
          });
          
          // Close modal when clicking outside content
          modal.addEventListener('click', function(event) {
            if (event.target === this) {
              modal.remove();
            }
          });
          
          // Close modal when pressing Escape
          document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
              modal.remove();
            }
          });
          
          // Trap focus inside modal
          modal.addEventListener('keydown', trapFocus);
          
          // Focus first focusable element
          const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
          if (focusableElements.length) {
            focusableElements[0].focus();
          }
        })
        .catch(function(error) {
          console.error('Error loading quick view:', error);
          button.classList.remove('loading');
        });
      });
    });
    
    /**
     * Trap focus inside modal
     * @param {Event} event - The keydown event
     */
    function trapFocus(event) {
      if (event.key !== 'Tab') {
        return;
      }
      
      const modal = event.currentTarget;
      const focusableElements = Array.from(modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'));
      
      if (focusableElements.length === 0) {
        return;
      }
      
      const firstElement = focusableElements[0];
      const lastElement = focusableElements[focusableElements.length - 1];
      
      if (event.shiftKey && document.activeElement === firstElement) {
        event.preventDefault();
        lastElement.focus();
      } else if (!event.shiftKey && document.activeElement === lastElement) {
        event.preventDefault();
        firstElement.focus();
      }
    }
  }
  
  /**
   * Initialize wishlist
   */
  function initWishlist() {
    const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
    
    wishlistButtons.forEach(function(button) {
      button.addEventListener('click', function(event) {
        event.preventDefault();
        
        const productId = button.getAttribute('data-product-id');
        
        if (!productId) {
          return;
        }
        
        // Add loading state
        button.classList.add('loading');
        
        // Create form data
        const formData = new FormData();
        formData.append('action', 'aqualuxe_toggle_wishlist');
        formData.append('product_id', productId);
        formData.append('security', aqualuxe_params.wishlist_nonce);
        
        // Send AJAX request
        fetch(aqualuxe_params.ajax_url, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        })
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          // Remove loading state
          button.classList.remove('loading');
          
          if (data.success) {
            // Toggle button state
            button.classList.toggle('in-wishlist', data.in_wishlist);
            
            // Update button text
            const textElement = button.querySelector('.wishlist-text');
            if (textElement) {
              textElement.textContent = data.in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist';
            }
            
            // Update button icon
            const iconElement = button.querySelector('.wishlist-icon');
            if (iconElement) {
              iconElement.innerHTML = data.in_wishlist ? 
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>' : 
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg>';
            }
            
            // Update wishlist count
            const wishlistCount = document.querySelector('.wishlist-count');
            if (wishlistCount && data.count !== undefined) {
              wishlistCount.textContent = data.count;
              wishlistCount.classList.toggle('hidden', data.count === 0);
            }
            
            // Show message
            const message = document.createElement('div');
            message.className = 'woocommerce-message';
            message.textContent = data.message;
            
            const noticesContainer = document.querySelector('.woocommerce-notices-wrapper');
            if (noticesContainer) {
              noticesContainer.appendChild(message);
              
              // Remove message after 3 seconds
              setTimeout(function() {
                message.remove();
              }, 3000);
            }
          } else {
            // Show error message
            const errorMessage = document.createElement('div');
            errorMessage.className = 'woocommerce-error';
            errorMessage.textContent = data.message || 'Error updating wishlist. Please try again.';
            
            const noticesContainer = document.querySelector('.woocommerce-notices-wrapper');
            if (noticesContainer) {
              noticesContainer.appendChild(errorMessage);
              
              // Remove error after 3 seconds
              setTimeout(function() {
                errorMessage.remove();
              }, 3000);
            }
          }
        })
        .catch(function(error) {
          console.error('Error updating wishlist:', error);
          button.classList.remove('loading');
        });
      });
    });
  }
  
  /**
   * Initialize product filters
   */
  function initProductFilters() {
    const filterForm = document.querySelector('.product-filters-form');
    const filterToggle = document.querySelector('.filter-toggle');
    const filterContainer = document.querySelector('.product-filters');
    
    if (filterToggle && filterContainer) {
      // Toggle filters on mobile
      filterToggle.addEventListener('click', function(event) {
        event.preventDefault();
        
        const isExpanded = filterToggle.getAttribute('aria-expanded') === 'true';
        
        filterToggle.setAttribute('aria-expanded', !isExpanded);
        filterContainer.classList.toggle('is-active', !isExpanded);
        
        // Add overlay on mobile
        if (!isExpanded) {
          const overlay = document.createElement('div');
          overlay.className = 'filter-overlay';
          document.body.appendChild(overlay);
          
          // Close filters when clicking overlay
          overlay.addEventListener('click', function() {
            filterToggle.setAttribute('aria-expanded', 'false');
            filterContainer.classList.remove('is-active');
            overlay.remove();
          });
        } else {
          // Remove overlay
          const overlay = document.querySelector('.filter-overlay');
          if (overlay) {
            overlay.remove();
          }
        }
      });
    }
    
    if (filterForm) {
      // Initialize price slider
      const priceSlider = filterForm.querySelector('.price-slider');
      
      if (priceSlider && typeof jQuery !== 'undefined' && jQuery.fn.slider) {
        const minPrice = parseInt(priceSlider.getAttribute('data-min'), 10);
        const maxPrice = parseInt(priceSlider.getAttribute('data-max'), 10);
        const currentMinPrice = parseInt(priceSlider.getAttribute('data-current-min'), 10) || minPrice;
        const currentMaxPrice = parseInt(priceSlider.getAttribute('data-current-max'), 10) || maxPrice;
        
        const minInput = filterForm.querySelector('.min_price');
        const maxInput = filterForm.querySelector('.max_price');
        
        if (minInput && maxInput) {
          jQuery(priceSlider).slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [currentMinPrice, currentMaxPrice],
            slide: function(event, ui) {
              minInput.value = ui.values[0];
              maxInput.value = ui.values[1];
              
              // Update display values
              const minDisplay = filterForm.querySelector('.min-price-display');
              const maxDisplay = filterForm.querySelector('.max-price-display');
              
              if (minDisplay) {
                minDisplay.textContent = formatPrice(ui.values[0]);
              }
              
              if (maxDisplay) {
                maxDisplay.textContent = formatPrice(ui.values[1]);
              }
            }
          });
          
          // Update inputs when slider changes
          minInput.addEventListener('change', function() {
            jQuery(priceSlider).slider('values', 0, this.value);
          });
          
          maxInput.addEventListener('change', function() {
            jQuery(priceSlider).slider('values', 1, this.value);
          });
        }
      }
      
      // AJAX filter submission
      const applyFiltersButton = filterForm.querySelector('.apply-filters');
      
      if (applyFiltersButton) {
        applyFiltersButton.addEventListener('click', function(event) {
          event.preventDefault();
          
          // Show loading state
          const productsContainer = document.querySelector('.products');
          
          if (productsContainer) {
            productsContainer.classList.add('loading');
          }
          
          // Get form data
          const formData = new FormData(filterForm);
          formData.append('action', 'aqualuxe_filter_products');
          formData.append('security', aqualuxe_params.filter_nonce);
          
          // Send AJAX request
          fetch(aqualuxe_params.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
          })
          .then(function(response) {
            return response.text();
          })
          .then(function(html) {
            // Update products container
            if (productsContainer) {
              productsContainer.innerHTML = html;
              productsContainer.classList.remove('loading');
            }
            
            // Close filters on mobile
            if (filterToggle && filterContainer && window.innerWidth < 1024) {
              filterToggle.setAttribute('aria-expanded', 'false');
              filterContainer.classList.remove('is-active');
              
              // Remove overlay
              const overlay = document.querySelector('.filter-overlay');
              if (overlay) {
                overlay.remove();
              }
            }
            
            // Update URL with filter parameters
            const url = new URL(window.location);
            
            for (const [key, value] of formData.entries()) {
              if (value) {
                url.searchParams.set(key, value);
              } else {
                url.searchParams.delete(key);
              }
            }
            
            history.pushState({}, '', url);
            
            // Reinitialize product-related functionality
            initAjaxAddToCart();
            initQuickView();
            initWishlist();
          })
          .catch(function(error) {
            console.error('Error filtering products:', error);
            
            if (productsContainer) {
              productsContainer.classList.remove('loading');
            }
          });
        });
      }
      
      // Reset filters
      const resetFiltersButton = filterForm.querySelector('.reset-filters');
      
      if (resetFiltersButton) {
        resetFiltersButton.addEventListener('click', function(event) {
          event.preventDefault();
          
          // Reset form fields
          filterForm.reset();
          
          // Reset price slider
          if (priceSlider && typeof jQuery !== 'undefined' && jQuery.fn.slider) {
            const minPrice = parseInt(priceSlider.getAttribute('data-min'), 10);
            const maxPrice = parseInt(priceSlider.getAttribute('data-max'), 10);
            
            jQuery(priceSlider).slider('values', 0, minPrice);
            jQuery(priceSlider).slider('values', 1, maxPrice);
            
            // Update display values
            const minDisplay = filterForm.querySelector('.min-price-display');
            const maxDisplay = filterForm.querySelector('.max-price-display');
            
            if (minDisplay) {
              minDisplay.textContent = formatPrice(minPrice);
            }
            
            if (maxDisplay) {
              maxDisplay.textContent = formatPrice(maxPrice);
            }
          }
          
          // Trigger filter submission
          const applyFiltersButton = filterForm.querySelector('.apply-filters');
          if (applyFiltersButton) {
            applyFiltersButton.click();
          }
        });
      }
    }
    
    /**
     * Format price
     * @param {number} price - The price to format
     * @return {string} Formatted price
     */
    function formatPrice(price) {
      return aqualuxe_params.currency_format
        .replace('{price}', price.toFixed(2))
        .replace('{symbol}', aqualuxe_params.currency_symbol);
    }
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();