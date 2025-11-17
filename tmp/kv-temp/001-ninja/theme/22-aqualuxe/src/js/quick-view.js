/**
 * AquaLuxe Theme Quick View
 *
 * Handles the product quick view functionality.
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize quick view
  initQuickView();
});

/**
 * Initialize quick view functionality
 */
function initQuickView() {
  const quickViewButtons = document.querySelectorAll('.aqualuxe-quick-view-button');
  
  quickViewButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      const productId = this.getAttribute('data-product-id');
      
      // Add loading state
      this.classList.add('loading');
      
      // AJAX request to get product data
      const data = new FormData();
      data.append('action', 'aqualuxe_quick_view');
      data.append('product_id', productId);
      data.append('nonce', aqualuxeQuickView.nonce);
      
      fetch(aqualuxeQuickView.ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        body: data
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Create modal
          createQuickViewModal(data.html);
          
          // Initialize product gallery in quick view
          initQuickViewGallery();
          
          // Initialize quantity inputs
          initQuickViewQuantity();
          
          // Initialize variations form
          if (typeof jQuery !== 'undefined' && jQuery.fn.wc_variation_form) {
            jQuery('.variations_form').wc_variation_form();
          }
        } else {
          console.error('Error loading quick view:', data.message);
        }
      })
      .catch(error => {
        console.error('Error loading quick view:', error);
      })
      .finally(() => {
        // Remove loading state
        this.classList.remove('loading');
      });
    });
  });
}

/**
 * Create quick view modal
 * 
 * @param {string} html - Modal content HTML
 */
function createQuickViewModal(html) {
  // Remove existing modal if any
  const existingModal = document.querySelector('.aqualuxe-quick-view-modal');
  if (existingModal) {
    document.body.removeChild(existingModal);
  }
  
  // Create modal container
  const modal = document.createElement('div');
  modal.className = 'aqualuxe-quick-view-modal';
  modal.setAttribute('aria-modal', 'true');
  modal.setAttribute('role', 'dialog');
  
  // Create modal content
  const modalContent = document.createElement('div');
  modalContent.className = 'aqualuxe-quick-view-content p-6 relative';
  modalContent.innerHTML = html;
  
  // Create close button
  const closeButton = document.createElement('button');
  closeButton.className = 'aqualuxe-quick-view-close';
  closeButton.setAttribute('aria-label', 'Close quick view');
  closeButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
  
  // Add close button to modal content
  modalContent.appendChild(closeButton);
  
  // Add modal content to modal
  modal.appendChild(modalContent);
  
  // Add modal to DOM
  document.body.appendChild(modal);
  
  // Prevent body scrolling
  document.body.classList.add('overflow-hidden');
  
  // Add event listener to close button
  closeButton.addEventListener('click', closeQuickViewModal);
  
  // Close modal when clicking outside content
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeQuickViewModal();
    }
  });
  
  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeQuickViewModal();
    }
  });
  
  // Trap focus in modal
  trapFocus(modal);
}

/**
 * Close quick view modal
 */
function closeQuickViewModal() {
  const modal = document.querySelector('.aqualuxe-quick-view-modal');
  
  if (modal) {
    document.body.removeChild(modal);
  }
  
  // Allow body scrolling
  document.body.classList.remove('overflow-hidden');
}

/**
 * Initialize product gallery in quick view
 */
function initQuickViewGallery() {
  const gallery = document.querySelector('.aqualuxe-quick-view-gallery');
  
  if (!gallery) return;
  
  const mainImage = gallery.querySelector('.aqualuxe-quick-view-main-image');
  const thumbnails = gallery.querySelectorAll('.aqualuxe-quick-view-thumbnail');
  
  thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', function(e) {
      e.preventDefault();
      
      const fullImage = this.getAttribute('data-full-image');
      
      // Update main image
      if (mainImage) {
        mainImage.src = fullImage;
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
 * Initialize quantity inputs in quick view
 */
function initQuickViewQuantity() {
  const quantityInput = document.querySelector('.aqualuxe-quick-view-content .quantity input');
  
  if (!quantityInput) return;
  
  const minusBtn = document.createElement('button');
  const plusBtn = document.createElement('button');
  
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
    const currentValue = parseInt(quantityInput.value, 10);
    const minValue = parseInt(quantityInput.min, 10) || 1;
    
    if (currentValue > minValue) {
      quantityInput.value = currentValue - 1;
      quantityInput.dispatchEvent(new Event('change', { bubbles: true }));
    }
  });
  
  // Add event listener for plus button
  plusBtn.addEventListener('click', () => {
    const currentValue = parseInt(quantityInput.value, 10);
    const maxValue = parseInt(quantityInput.max, 10) || 999;
    
    if (currentValue < maxValue) {
      quantityInput.value = currentValue + 1;
      quantityInput.dispatchEvent(new Event('change', { bubbles: true }));
    }
  });
  
  // Insert buttons
  const quantityWrapper = quantityInput.parentNode;
  quantityWrapper.insertBefore(minusBtn, quantityInput);
  quantityWrapper.appendChild(plusBtn);
}

/**
 * Trap focus in modal
 * 
 * @param {HTMLElement} element - Modal element
 */
function trapFocus(element) {
  const focusableElements = element.querySelectorAll(
    'a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
  );
  
  const firstFocusableElement = focusableElements[0];
  const lastFocusableElement = focusableElements[focusableElements.length - 1];
  
  // Set focus on first element
  firstFocusableElement.focus();
  
  element.addEventListener('keydown', function(event) {
    if (event.key === 'Tab') {
      // Shift + Tab
      if (event.shiftKey) {
        if (document.activeElement === firstFocusableElement) {
          lastFocusableElement.focus();
          event.preventDefault();
        }
      } 
      // Tab
      else {
        if (document.activeElement === lastFocusableElement) {
          firstFocusableElement.focus();
          event.preventDefault();
        }
      }
    }
  });
}