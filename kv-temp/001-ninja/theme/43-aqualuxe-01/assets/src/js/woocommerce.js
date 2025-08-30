/**
 * AquaLuxe Theme WooCommerce JavaScript
 *
 * @package AquaLuxe
 */

/**
 * AJAX Add to Cart
 */
document.addEventListener('DOMContentLoaded', () => {
  const addToCartButtons = document.querySelectorAll('.add_to_cart_button:not(.product_type_variable):not(.product_type_grouped)');
  
  addToCartButtons.forEach(button => {
    button.addEventListener('click', async (e) => {
      e.preventDefault();
      
      const productId = button.dataset.productId;
      const quantity = button.dataset.quantity || 1;
      
      if (!productId) {
        return;
      }
      
      // Store original button text
      const originalText = button.innerHTML;
      
      // Update button state
      button.classList.add('loading');
      button.innerHTML = aqualuxeWooCommerce.addingToCartText;
      
      try {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('nonce', aqualuxeWooCommerce.nonce);
        
        const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Update cart fragments
          updateCartFragments(data);
          
          // Show success message
          button.classList.remove('loading');
          button.classList.add('added');
          button.innerHTML = aqualuxeWooCommerce.addedToCartText;
          
          // Show view cart button
          const viewCartButton = document.createElement('a');
          viewCartButton.href = data.data.cart_url;
          viewCartButton.className = 'added_to_cart wc-forward';
          viewCartButton.innerHTML = aqualuxeWooCommerce.viewCartText;
          button.insertAdjacentElement('afterend', viewCartButton);
          
          // Reset button text after delay
          setTimeout(() => {
            button.innerHTML = originalText;
          }, 3000);
          
          // Show mini cart if available
          const miniCart = document.querySelector('.cart-dropdown');
          if (miniCart) {
            miniCart.classList.add('active');
            
            // Hide mini cart after delay
            setTimeout(() => {
              miniCart.classList.remove('active');
            }, 5000);
          }
        } else {
          console.error(data.data.message);
          button.classList.remove('loading');
          button.innerHTML = originalText;
          
          // Show error message
          const errorMessage = document.createElement('span');
          errorMessage.className = 'woocommerce-error';
          errorMessage.innerHTML = data.data.message || aqualuxeWooCommerce.errorMessage;
          button.insertAdjacentElement('afterend', errorMessage);
          
          // Remove error message after delay
          setTimeout(() => {
            errorMessage.remove();
          }, 3000);
        }
      } catch (error) {
        console.error('Error adding to cart:', error);
        button.classList.remove('loading');
        button.innerHTML = originalText;
      }
    });
  });
  
  /**
   * Update cart fragments
   */
  function updateCartFragments(data) {
    if (!data.data) return;
    
    // Update cart count
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
      element.textContent = data.data.cart_count;
    });
    
    // Update cart total
    const cartTotalElements = document.querySelectorAll('.cart-total');
    cartTotalElements.forEach(element => {
      element.innerHTML = data.data.cart_total;
    });
  }
});

/**
 * Quick View Functionality
 */
document.addEventListener('DOMContentLoaded', () => {
  const quickViewButtons = document.querySelectorAll('.quick-view-button');
  const quickViewModal = document.getElementById('quick-view-modal');
  const quickViewClose = document.getElementById('quick-view-modal-close');
  const quickViewContent = document.querySelector('.quick-view-content');
  const quickViewLoading = document.querySelector('.quick-view-loading');
  
  if (!quickViewModal || !quickViewContent || !quickViewLoading) {
    return;
  }
  
  // Open quick view modal
  quickViewButtons.forEach(button => {
    button.addEventListener('click', async (e) => {
      e.preventDefault();
      
      const productId = button.dataset.productId;
      
      if (!productId) {
        return;
      }
      
      // Show modal and loading state
      quickViewModal.classList.remove('hidden');
      quickViewContent.innerHTML = '';
      quickViewLoading.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
      
      try {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_quick_view');
        formData.append('product_id', productId);
        formData.append('nonce', aqualuxeWooCommerce.nonce);
        
        const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Hide loading and show content
          quickViewLoading.classList.add('hidden');
          quickViewContent.innerHTML = data.data.content;
          
          // Initialize quantity buttons
          initQuantityButtons();
          
          // Initialize gallery
          initQuickViewGallery();
          
          // Initialize add to cart
          initQuickViewAddToCart();
        } else {
          console.error(data.data.message);
          quickViewLoading.classList.add('hidden');
          quickViewContent.innerHTML = `<p class="error">${data.data.message || aqualuxeWooCommerce.errorMessage}</p>`;
        }
      } catch (error) {
        console.error('Error loading quick view:', error);
        quickViewLoading.classList.add('hidden');
        quickViewContent.innerHTML = `<p class="error">${aqualuxeWooCommerce.errorMessage}</p>`;
      }
    });
  });
  
  // Close quick view modal
  if (quickViewClose) {
    quickViewClose.addEventListener('click', () => {
      quickViewModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    });
  }
  
  // Close on escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !quickViewModal.classList.contains('hidden')) {
      quickViewModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });
  
  // Close on click outside
  quickViewModal.addEventListener('click', (e) => {
    if (e.target === quickViewModal) {
      quickViewModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });
  
  /**
   * Initialize quantity buttons in quick view
   */
  function initQuantityButtons() {
    const quantityInputs = quickViewContent.querySelectorAll('.quantity input[type="number"]');
    
    quantityInputs.forEach(input => {
      // Add minus button
      const minusButton = document.createElement('button');
      minusButton.type = 'button';
      minusButton.className = 'quantity-minus';
      minusButton.textContent = '-';
      input.insertAdjacentElement('beforebegin', minusButton);
      
      // Add plus button
      const plusButton = document.createElement('button');
      plusButton.type = 'button';
      plusButton.className = 'quantity-plus';
      plusButton.textContent = '+';
      input.insertAdjacentElement('afterend', plusButton);
      
      // Minus button click
      minusButton.addEventListener('click', () => {
        const currentValue = parseInt(input.value, 10);
        const minValue = parseInt(input.min, 10) || 1;
        
        if (currentValue > minValue) {
          input.value = currentValue - 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
      
      // Plus button click
      plusButton.addEventListener('click', () => {
        const currentValue = parseInt(input.value, 10);
        const maxValue = parseInt(input.max, 10) || 999;
        
        if (currentValue < maxValue) {
          input.value = currentValue + 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
    });
  }
  
  /**
   * Initialize gallery in quick view
   */
  function initQuickViewGallery() {
    const mainImage = quickViewContent.querySelector('.quick-view-main-image');
    const galleryImages = quickViewContent.querySelectorAll('.quick-view-gallery-image');
    
    if (!mainImage || galleryImages.length === 0) {
      return;
    }
    
    galleryImages.forEach(image => {
      image.addEventListener('click', () => {
        // Update main image
        mainImage.src = image.src;
        
        // Update active state
        galleryImages.forEach(img => img.classList.remove('active'));
        image.classList.add('active');
      });
    });
  }
  
  /**
   * Initialize add to cart in quick view
   */
  function initQuickViewAddToCart() {
    const addToCartForm = quickViewContent.querySelector('form.cart');
    
    if (!addToCartForm) {
      return;
    }
    
    addToCartForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const submitButton = addToCartForm.querySelector('.single_add_to_cart_button');
      const productId = submitButton.value;
      const quantityInput = addToCartForm.querySelector('.quantity input[type="number"]');
      const quantity = quantityInput ? quantityInput.value : 1;
      
      if (!productId) {
        return;
      }
      
      // Store original button text
      const originalText = submitButton.innerHTML;
      
      // Update button state
      submitButton.classList.add('loading');
      submitButton.innerHTML = aqualuxeWooCommerce.addingToCartText;
      
      try {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('nonce', aqualuxeWooCommerce.nonce);
        
        const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Update cart fragments
          updateCartFragments(data);
          
          // Show success message
          submitButton.classList.remove('loading');
          submitButton.classList.add('added');
          submitButton.innerHTML = aqualuxeWooCommerce.addedToCartText;
          
          // Close modal after delay
          setTimeout(() => {
            quickViewModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            
            // Reset button text
            submitButton.innerHTML = originalText;
            submitButton.classList.remove('added');
          }, 2000);
        } else {
          console.error(data.data.message);
          submitButton.classList.remove('loading');
          submitButton.innerHTML = originalText;
          
          // Show error message
          const errorMessage = document.createElement('span');
          errorMessage.className = 'woocommerce-error';
          errorMessage.innerHTML = data.data.message || aqualuxeWooCommerce.errorMessage;
          submitButton.insertAdjacentElement('afterend', errorMessage);
          
          // Remove error message after delay
          setTimeout(() => {
            errorMessage.remove();
          }, 3000);
        }
      } catch (error) {
        console.error('Error adding to cart:', error);
        submitButton.classList.remove('loading');
        submitButton.innerHTML = originalText;
      }
    });
  }
  
  /**
   * Update cart fragments
   */
  function updateCartFragments(data) {
    if (!data.data) return;
    
    // Update cart count
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
      element.textContent = data.data.cart_count;
    });
    
    // Update cart total
    const cartTotalElements = document.querySelectorAll('.cart-total');
    cartTotalElements.forEach(element => {
      element.innerHTML = data.data.cart_total;
    });
  }
});

/**
 * Wishlist Functionality
 */
document.addEventListener('DOMContentLoaded', () => {
  const wishlistButtons = document.querySelectorAll('.wishlist-button');
  
  wishlistButtons.forEach(button => {
    button.addEventListener('click', async (e) => {
      e.preventDefault();
      
      const productId = button.dataset.productId;
      
      if (!productId) {
        return;
      }
      
      // Store original button text
      const originalText = button.innerHTML;
      
      // Update button state
      button.classList.add('loading');
      button.innerHTML = '<span class="loading-spinner"></span>';
      
      try {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_wishlist');
        formData.append('product_id', productId);
        formData.append('action_type', button.classList.contains('in-wishlist') ? 'remove' : 'add');
        formData.append('nonce', aqualuxeWooCommerce.nonce);
        
        const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Update button state
          button.classList.remove('loading');
          
          if (button.classList.contains('in-wishlist')) {
            button.classList.remove('in-wishlist');
            button.innerHTML = aqualuxeWooCommerce.i18n.addToWishlist;
            
            // Show removed message
            const message = document.createElement('span');
            message.className = 'wishlist-message removed';
            message.innerHTML = aqualuxeWooCommerce.i18n.removedFromWishlist;
            button.insertAdjacentElement('afterend', message);
            
            // Remove message after delay
            setTimeout(() => {
              message.remove();
            }, 3000);
          } else {
            button.classList.add('in-wishlist');
            button.innerHTML = aqualuxeWooCommerce.i18n.removeFromWishlist;
            
            // Show added message
            const message = document.createElement('span');
            message.className = 'wishlist-message added';
            message.innerHTML = aqualuxeWooCommerce.i18n.addedToWishlist;
            button.insertAdjacentElement('afterend', message);
            
            // Remove message after delay
            setTimeout(() => {
              message.remove();
            }, 3000);
          }
          
          // Update wishlist count if available
          const wishlistCount = document.querySelector('.wishlist-count');
          if (wishlistCount) {
            wishlistCount.textContent = data.data.count;
          }
        } else {
          console.error(data.data.message);
          button.classList.remove('loading');
          button.innerHTML = originalText;
        }
      } catch (error) {
        console.error('Error updating wishlist:', error);
        button.classList.remove('loading');
        button.innerHTML = originalText;
      }
    });
  });
  
  // Wishlist page remove buttons
  const wishlistRemoveButtons = document.querySelectorAll('.wishlist-remove');
  
  wishlistRemoveButtons.forEach(button => {
    button.addEventListener('click', async (e) => {
      e.preventDefault();
      
      const productId = button.dataset.productId;
      const row = button.closest('tr');
      
      if (!productId || !row) {
        return;
      }
      
      try {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_wishlist');
        formData.append('product_id', productId);
        formData.append('action_type', 'remove');
        formData.append('nonce', aqualuxeWooCommerce.nonce);
        
        const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Remove row with animation
          row.style.opacity = '0';
          setTimeout(() => {
            row.remove();
            
            // Check if table is empty
            const tbody = document.querySelector('.wishlist-table tbody');
            if (tbody && tbody.children.length === 0) {
              const table = document.querySelector('.wishlist-table');
              const emptyMessage = document.createElement('p');
              emptyMessage.innerHTML = aqualuxeWooCommerce.i18n.emptyWishlist;
              table.insertAdjacentElement('afterend', emptyMessage);
              table.remove();
            }
          }, 300);
          
          // Update wishlist count if available
          const wishlistCount = document.querySelector('.wishlist-count');
          if (wishlistCount) {
            wishlistCount.textContent = data.data.count;
          }
        } else {
          console.error(data.data.message);
        }
      } catch (error) {
        console.error('Error removing from wishlist:', error);
      }
    });
  });
});

/**
 * Product Comparison Functionality
 */
document.addEventListener('DOMContentLoaded', () => {
  const compareButtons = document.querySelectorAll('.compare-button');
  const compareModal = document.getElementById('compare-modal');
  const compareClose = document.getElementById('compare-modal-close');
  const compareContent = document.querySelector('.compare-content');
  const compareLoading = document.querySelector('.compare-loading');
  const compareEmpty = document.querySelector('.compare-empty');
  const compareTable = document.querySelector('.compare-table-wrapper');
  
  if (!compareModal || !compareContent) {
    return;
  }
  
  // Load current comparison products
  loadCompareProducts();
  
  // Add to compare
  compareButtons.forEach(button => {
    button.addEventListener('click', async (e) => {
      e.preventDefault();
      
      const productId = button.dataset.productId;
      
      if (!productId) {
        return;
      }
      
      // Store original button text
      const originalText = button.innerHTML;
      
      // Update button state
      button.classList.add('loading');
      button.innerHTML = '<span class="loading-spinner"></span>';
      
      try {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_compare');
        formData.append('product_id', productId);
        formData.append('action_type', button.classList.contains('in-compare') ? 'remove' : 'add');
        formData.append('nonce', aqualuxeWooCommerce.nonce);
        
        const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Update button state
          button.classList.remove('loading');
          
          if (button.classList.contains('in-compare')) {
            button.classList.remove('in-compare');
            button.innerHTML = aqualuxeWooCommerce.i18n.addToCompare;
            
            // Show removed message
            const message = document.createElement('span');
            message.className = 'compare-message removed';
            message.innerHTML = aqualuxeWooCommerce.i18n.removedFromCompare;
            button.insertAdjacentElement('afterend', message);
            
            // Remove message after delay
            setTimeout(() => {
              message.remove();
            }, 3000);
          } else {
            button.classList.add('in-compare');
            button.innerHTML = aqualuxeWooCommerce.i18n.removeFromCompare;
            
            // Show added message
            const message = document.createElement('span');
            message.className = 'compare-message added';
            message.innerHTML = aqualuxeWooCommerce.i18n.addedToCompare;
            button.insertAdjacentElement('afterend', message);
            
            // Remove message after delay
            setTimeout(() => {
              message.remove();
            }, 3000);
            
            // Show view compare button
            const viewCompareButton = document.createElement('a');
            viewCompareButton.href = '#';
            viewCompareButton.className = 'view-compare-button button';
            viewCompareButton.innerHTML = aqualuxeWooCommerce.i18n.viewCompare;
            button.insertAdjacentElement('afterend', viewCompareButton);
            
            // Open compare modal on click
            viewCompareButton.addEventListener('click', (e) => {
              e.preventDefault();
              compareModal.classList.remove('hidden');
              document.body.classList.add('overflow-hidden');
              loadCompareProducts();
            });
            
            // Remove view compare button after delay
            setTimeout(() => {
              viewCompareButton.remove();
            }, 5000);
          }
          
          // Update compare count if available
          const compareCount = document.querySelector('.compare-count');
          if (compareCount) {
            compareCount.textContent = data.data.count;
          }
          
          // Update compare table
          updateCompareTable(data.data.products);
        } else {
          console.error(data.data.message);
          button.classList.remove('loading');
          button.innerHTML = originalText;
          
          // Show error message
          const errorMessage = document.createElement('span');
          errorMessage.className = 'compare-error';
          errorMessage.innerHTML = data.data.message;
          button.insertAdjacentElement('afterend', errorMessage);
          
          // Remove error message after delay
          setTimeout(() => {
            errorMessage.remove();
          }, 3000);
        }
      } catch (error) {
        console.error('Error updating compare:', error);
        button.classList.remove('loading');
        button.innerHTML = originalText;
      }
    });
  });
  
  // Close compare modal
  if (compareClose) {
    compareClose.addEventListener('click', () => {
      compareModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    });
  }
  
  // Close on escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !compareModal.classList.contains('hidden')) {
      compareModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });
  
  // Close on click outside
  compareModal.addEventListener('click', (e) => {
    if (e.target === compareModal) {
      compareModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });
  
  /**
   * Load compare products
   */
  async function loadCompareProducts() {
    if (!compareLoading || !compareEmpty || !compareTable) {
      return;
    }
    
    // Show loading
    compareLoading.classList.remove('hidden');
    compareEmpty.classList.add('hidden');
    compareTable.classList.add('hidden');
    
    try {
      const formData = new FormData();
      formData.append('action', 'aqualuxe_get_compare_products');
      formData.append('nonce', aqualuxeWooCommerce.nonce);
      
      const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Hide loading
        compareLoading.classList.add('hidden');
        
        // Update compare table
        updateCompareTable(data.data.products);
      } else {
        console.error(data.data.message);
        compareLoading.classList.add('hidden');
        compareEmpty.classList.remove('hidden');
      }
    } catch (error) {
      console.error('Error loading compare products:', error);
      compareLoading.classList.add('hidden');
      compareEmpty.classList.remove('hidden');
    }
  }
  
  /**
   * Update compare table
   */
  function updateCompareTable(products) {
    if (!compareEmpty || !compareTable) {
      return;
    }
    
    if (!products || products.length === 0) {
      compareEmpty.classList.remove('hidden');
      compareTable.classList.add('hidden');
      return;
    }
    
    compareEmpty.classList.add('hidden');
    compareTable.classList.remove('hidden');
    
    // Update table headers
    for (let i = 0; i < 3; i++) {
      const header = document.querySelector(`.compare-product-${i + 1}`);
      if (header) {
        header.innerHTML = products[i] ? products[i].name : '';
      }
    }
    
    // Update table cells
    for (let i = 0; i < 3; i++) {
      // Image
      const imageCell = document.querySelector(`.compare-product-${i + 1}-image`);
      if (imageCell) {
        if (products[i] && products[i].image) {
          imageCell.innerHTML = `<img src="${products[i].image[0]}" alt="${products[i].name}" width="${products[i].image[1]}" height="${products[i].image[2]}" />`;
        } else {
          imageCell.innerHTML = '';
        }
      }
      
      // Name
      const nameCell = document.querySelector(`.compare-product-${i + 1}-name`);
      if (nameCell) {
        if (products[i]) {
          nameCell.innerHTML = `<a href="${products[i].url}">${products[i].name}</a>`;
        } else {
          nameCell.innerHTML = '';
        }
      }
      
      // Price
      const priceCell = document.querySelector(`.compare-product-${i + 1}-price`);
      if (priceCell) {
        priceCell.innerHTML = products[i] ? products[i].price : '';
      }
      
      // Description
      const descriptionCell = document.querySelector(`.compare-product-${i + 1}-description`);
      if (descriptionCell) {
        descriptionCell.innerHTML = products[i] ? products[i].description : '';
      }
      
      // Size
      const sizeCell = document.querySelector(`.compare-product-${i + 1}-size`);
      if (sizeCell) {
        sizeCell.innerHTML = products[i] && products[i].size ? products[i].size : '-';
      }
      
      // Tank Size
      const tankSizeCell = document.querySelector(`.compare-product-${i + 1}-tank-size`);
      if (tankSizeCell) {
        tankSizeCell.innerHTML = products[i] && products[i].tank_size ? products[i].tank_size : '-';
      }
      
      // Diet
      const dietCell = document.querySelector(`.compare-product-${i + 1}-diet`);
      if (dietCell) {
        dietCell.innerHTML = products[i] && products[i].diet ? products[i].diet : '-';
      }
      
      // Temperature
      const temperatureCell = document.querySelector(`.compare-product-${i + 1}-temperature`);
      if (temperatureCell) {
        temperatureCell.innerHTML = products[i] && products[i].temperature ? products[i].temperature : '-';
      }
      
      // pH
      const phCell = document.querySelector(`.compare-product-${i + 1}-ph`);
      if (phCell) {
        phCell.innerHTML = products[i] && products[i].ph ? products[i].ph : '-';
      }
      
      // Actions
      const actionsCell = document.querySelector(`.compare-product-${i + 1}-actions`);
      if (actionsCell) {
        if (products[i]) {
          actionsCell.innerHTML = `
            <a href="${products[i].url}" class="button view-product-button">View Product</a>
            <button type="button" class="button remove-compare-button" data-product-id="${products[i].id}">Remove</button>
          `;
          
          // Add event listener to remove button
          const removeButton = actionsCell.querySelector('.remove-compare-button');
          if (removeButton) {
            removeButton.addEventListener('click', async () => {
              try {
                const formData = new FormData();
                formData.append('action', 'aqualuxe_compare');
                formData.append('product_id', products[i].id);
                formData.append('action_type', 'remove');
                formData.append('nonce', aqualuxeWooCommerce.nonce);
                
                const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
                  method: 'POST',
                  credentials: 'same-origin',
                  body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                  // Update compare table
                  updateCompareTable(data.data.products);
                  
                  // Update compare count if available
                  const compareCount = document.querySelector('.compare-count');
                  if (compareCount) {
                    compareCount.textContent = data.data.count;
                  }
                  
                  // Update compare buttons
                  const compareButton = document.querySelector(`.compare-button[data-product-id="${products[i].id}"]`);
                  if (compareButton) {
                    compareButton.classList.remove('in-compare');
                    compareButton.innerHTML = aqualuxeWooCommerce.i18n.addToCompare;
                  }
                } else {
                  console.error(data.data.message);
                }
              } catch (error) {
                console.error('Error removing from compare:', error);
              }
            });
          }
        } else {
          actionsCell.innerHTML = '';
        }
      }
    }
  }
});

/**
 * Currency Switcher
 */
document.addEventListener('DOMContentLoaded', () => {
  const currencySwitcher = document.querySelector('.currency-switcher-select');
  
  if (currencySwitcher) {
    currencySwitcher.addEventListener('change', async () => {
      const currency = currencySwitcher.value;
      
      try {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_change_currency');
        formData.append('currency', currency);
        formData.append('nonce', aqualuxeWooCommerce.nonce);
        
        const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Reload page to update prices
          window.location.reload();
        } else {
          console.error(data.data.message);
        }
      } catch (error) {
        console.error('Error changing currency:', error);
      }
    });
  }
});

/**
 * Advanced Product Filtering
 */
document.addEventListener('DOMContentLoaded', () => {
  const filterForm = document.getElementById('product-filters-form');
  
  if (!filterForm) {
    return;
  }
  
  // Price range slider
  const priceRange = filterForm.querySelector('.price-range');
  const minPriceInput = filterForm.querySelector('input[name="min_price"]');
  const maxPriceInput = filterForm.querySelector('input[name="max_price"]');
  const priceDisplay = filterForm.querySelector('.price-display');
  
  if (priceRange && minPriceInput && maxPriceInput && priceDisplay) {
    // Initialize price range slider using noUiSlider or similar library
    // This is a placeholder for the actual implementation
    
    // Update price display
    function updatePriceDisplay() {
      priceDisplay.textContent = `${minPriceInput.value} - ${maxPriceInput.value}`;
    }
    
    // Update on input change
    minPriceInput.addEventListener('change', updatePriceDisplay);
    maxPriceInput.addEventListener('change', updatePriceDisplay);
    
    // Initial update
    updatePriceDisplay();
  }
  
  // AJAX filter submission
  filterForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const submitButton = filterForm.querySelector('button[type="submit"]');
    const resultsContainer = document.querySelector('.products');
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'loading-overlay';
    loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
    
    if (!resultsContainer) {
      return;
    }
    
    // Show loading state
    if (submitButton) {
      submitButton.disabled = true;
    }
    
    resultsContainer.appendChild(loadingOverlay);
    resultsContainer.classList.add('filtering');
    
    try {
      const formData = new FormData(filterForm);
      formData.append('action', 'aqualuxe_filter_products');
      formData.append('nonce', aqualuxeWooCommerce.nonce);
      
      const response = await fetch(aqualuxeWooCommerce.ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Update products
        resultsContainer.innerHTML = data.data.products;
        
        // Update URL with filters
        if (history.pushState) {
          const url = new URL(window.location);
          
          // Add filter parameters to URL
          for (const [key, value] of formData.entries()) {
            if (value) {
              url.searchParams.set(key, value);
            } else {
              url.searchParams.delete(key);
            }
          }
          
          window.history.pushState({}, '', url);
        }
        
        // Reinitialize product scripts
        initProductScripts();
      } else {
        console.error(data.data.message);
        resultsContainer.innerHTML = `<p class="woocommerce-info">${data.data.message || 'No products found matching your criteria.'}</p>`;
      }
    } catch (error) {
      console.error('Error filtering products:', error);
      resultsContainer.innerHTML = '<p class="woocommerce-error">An error occurred while filtering products. Please try again.</p>';
    } finally {
      // Remove loading state
      if (submitButton) {
        submitButton.disabled = false;
      }
      
      resultsContainer.classList.remove('filtering');
      
      // Scroll to results
      resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
  
  // Reset filters
  const resetButton = filterForm.querySelector('.reset-filters');
  
  if (resetButton) {
    resetButton.addEventListener('click', (e) => {
      e.preventDefault();
      
      // Reset form inputs
      filterForm.reset();
      
      // Reset price range if applicable
      if (priceRange) {
        // Reset price range slider
        // This is a placeholder for the actual implementation
      }
      
      // Submit form to reset filters
      filterForm.dispatchEvent(new Event('submit'));
    });
  }
  
  /**
   * Initialize product scripts after AJAX filtering
   */
  function initProductScripts() {
    // Reinitialize add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add_to_cart_button:not(.product_type_variable):not(.product_type_grouped)');
    
    addToCartButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        // The event handler is already defined in the main script
        // This just ensures the event is attached to new buttons
      });
    });
    
    // Reinitialize quick view buttons
    const quickViewButtons = document.querySelectorAll('.quick-view-button');
    
    quickViewButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        // The event handler is already defined in the main script
        // This just ensures the event is attached to new buttons
      });
    });
    
    // Reinitialize wishlist buttons
    const wishlistButtons = document.querySelectorAll('.wishlist-button');
    
    wishlistButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        // The event handler is already defined in the main script
        // This just ensures the event is attached to new buttons
      });
    });
    
    // Reinitialize compare buttons
    const compareButtons = document.querySelectorAll('.compare-button');
    
    compareButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        // The event handler is already defined in the main script
        // This just ensures the event is attached to new buttons
      });
    });
  }
});