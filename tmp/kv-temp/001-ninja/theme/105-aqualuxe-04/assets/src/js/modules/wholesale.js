/**
 * Wholesale Module JavaScript
 *
 * Handles wholesale and B2B frontend functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

class WholesaleModule {
  constructor() {
    this.form = null;
    this.quoteItems = [];
    this.init();
  }

  init() {
    this.bindEvents();
    this.initQuoteBuilder();
    this.loadSavedQuoteItems();
  }

  bindEvents() {
    // Wholesale application form
    document.addEventListener('DOMContentLoaded', () => {
      this.form = document.getElementById('wholesale-application-form');
      if (this.form) {
        this.form.addEventListener(
          'submit',
          this.handleApplicationSubmit.bind(this)
        );
      }

      // Quote request buttons
      const quoteButtons = document.querySelectorAll('.request-quote-btn');
      quoteButtons.forEach(button => {
        button.addEventListener('click', this.handleQuoteRequest.bind(this));
      });

      // Add to quote buttons
      const addToQuoteButtons = document.querySelectorAll('.add-to-quote-btn');
      addToQuoteButtons.forEach(button => {
        button.addEventListener('click', this.handleAddToQuote.bind(this));
      });

      // Bulk quote request
      const bulkQuoteForm = document.getElementById('bulk-quote-form');
      if (bulkQuoteForm) {
        bulkQuoteForm.addEventListener(
          'submit',
          this.handleBulkQuoteSubmit.bind(this)
        );
      }

      // Quote item management
      this.bindQuoteItemEvents();
    });
  }

  handleApplicationSubmit(event) {
    event.preventDefault();

    if (!this.validateApplicationForm()) {
      return;
    }

    const submitButton = this.form.querySelector('[type="submit"]');
    const originalText = submitButton.textContent;

    // Show loading state
    submitButton.disabled = true;
    submitButton.textContent = aqualuxeWholesale.strings.processing;
    this.form.classList.add('aqualuxe-loading');

    const formData = new FormData(this.form);
    formData.append('action', 'aqualuxe_wholesale_request');
    formData.append('wholesale_action', 'submit_application');
    formData.append('nonce', aqualuxeWholesale.nonce);

    fetch(aqualuxeWholesale.ajaxurl, {
      method: 'POST',
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          this.showNotification(data.data.message, 'success');
          this.form.reset();

          // Redirect or show success state
          setTimeout(() => {
            if (data.data.redirect_url) {
              window.location.href = data.data.redirect_url;
            }
          }, 2000);
        } else {
          this.showNotification(
            data.data || aqualuxeWholesale.strings.error,
            'error'
          );
        }
      })
      .catch(() => {
        // Error handled through notification
        this.showNotification(aqualuxeWholesale.strings.error, 'error');
      })
      .finally(() => {
        // Reset form state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        this.form.classList.remove('aqualuxe-loading');
      });
  }

  validateApplicationForm() {
    const requiredFields = this.form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        this.showFieldError(field, 'This field is required');
        isValid = false;
      } else {
        this.clearFieldError(field);
      }
    });

    // Validate email
    const emailField = this.form.querySelector('[type="email"]');
    if (
      emailField &&
      emailField.value &&
      !this.isValidEmail(emailField.value)
    ) {
      this.showFieldError(emailField, 'Please enter a valid email address');
      isValid = false;
    }

    // Validate company name
    const companyField = this.form.querySelector('[name="company_name"]');
    if (companyField && companyField.value.length < 2) {
      this.showFieldError(
        companyField,
        'Company name must be at least 2 characters'
      );
      isValid = false;
    }

    return isValid;
  }

  handleQuoteRequest(event) {
    event.preventDefault();

    const productId = event.target.dataset.productId;
    const productName = event.target.dataset.productName;
    const productPrice = event.target.dataset.productPrice;

    if (!productId) {
      this.showNotification('Product information not available', 'error');
      return;
    }

    this.addToQuote(productId, productName, productPrice, 1);
    this.showNotification(`${productName} added to quote request`, 'success');
  }

  handleAddToQuote(event) {
    event.preventDefault();

    const button = event.target;
    const productId = button.dataset.productId;
    const productName = button.dataset.productName;
    const productPrice = button.dataset.productPrice;

    // Get quantity from nearby input or default to 1
    const quantityInput = button
      .closest('.product')
      .querySelector('.quantity-input');
    const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

    if (!productId) {
      this.showNotification('Product information not available', 'error');
      return;
    }

    this.addToQuote(productId, productName, productPrice, quantity);
    this.updateAddToQuoteButton(button);
    this.showNotification(
      `${productName} added to quote (${quantity} items)`,
      'success'
    );
  }

  addToQuote(productId, productName, productPrice, quantity) {
    // Check if product already exists in quote
    const existingIndex = this.quoteItems.findIndex(
      item => item.id === productId
    );

    if (existingIndex !== -1) {
      // Update quantity
      this.quoteItems[existingIndex].quantity += quantity;
    } else {
      // Add new item
      this.quoteItems.push({
        id: productId,
        name: productName,
        price: productPrice,
        quantity: quantity,
      });
    }

    this.saveQuoteItems();
    this.updateQuoteDisplay();
  }

  removeFromQuote(productId) {
    this.quoteItems = this.quoteItems.filter(item => item.id !== productId);
    this.saveQuoteItems();
    this.updateQuoteDisplay();
  }

  updateQuoteQuantity(productId, newQuantity) {
    const item = this.quoteItems.find(item => item.id === productId);
    if (item) {
      if (newQuantity <= 0) {
        this.removeFromQuote(productId);
      } else {
        item.quantity = newQuantity;
        this.saveQuoteItems();
        this.updateQuoteDisplay();
      }
    }
  }

  handleBulkQuoteSubmit(event) {
    event.preventDefault();

    if (this.quoteItems.length === 0) {
      this.showNotification(
        'Please add products to your quote request',
        'error'
      );
      return;
    }

    const form = event.target;
    const submitButton = form.querySelector('[type="submit"]');
    const originalText = submitButton.textContent;

    // Show loading state
    submitButton.disabled = true;
    submitButton.textContent = aqualuxeWholesale.strings.processing;
    form.classList.add('aqualuxe-loading');

    const formData = new FormData(form);
    formData.append('action', 'aqualuxe_wholesale_request');
    formData.append('wholesale_action', 'request_quote');
    formData.append('products', JSON.stringify(this.quoteItems));
    formData.append('nonce', aqualuxeWholesale.nonce);

    fetch(aqualuxeWholesale.ajaxurl, {
      method: 'POST',
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          this.showNotification(data.data.message, 'success');

          // Clear quote items
          this.quoteItems = [];
          this.saveQuoteItems();
          this.updateQuoteDisplay();
          form.reset();

          // Redirect or show success state
          setTimeout(() => {
            if (data.data.redirect_url) {
              window.location.href = data.data.redirect_url;
            }
          }, 2000);
        } else {
          this.showNotification(
            data.data || aqualuxeWholesale.strings.error,
            'error'
          );
        }
      })
      .catch(() => {
        // Error handled through notification
        this.showNotification(aqualuxeWholesale.strings.error, 'error');
      })
      .finally(() => {
        // Reset form state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        form.classList.remove('aqualuxe-loading');
      });
  }

  initQuoteBuilder() {
    const quoteBuilder = document.getElementById('quote-builder');
    if (quoteBuilder) {
      this.updateQuoteDisplay();
    }
  }

  updateQuoteDisplay() {
    const quoteBuilder = document.getElementById('quote-builder');
    const quoteCounter = document.querySelector('.quote-counter');

    if (quoteCounter) {
      quoteCounter.textContent = this.quoteItems.length;
      quoteCounter.style.display =
        this.quoteItems.length > 0 ? 'inline' : 'none';
    }

    if (!quoteBuilder) {
      return;
    }

    const quoteList = quoteBuilder.querySelector('.quote-items-list');
    const quoteSummary = quoteBuilder.querySelector('.quote-summary');

    if (this.quoteItems.length === 0) {
      quoteList.innerHTML = '<p class="no-items">No items in quote request</p>';
      if (quoteSummary) {
        quoteSummary.style.display = 'none';
      }
      return;
    }

    // Generate quote items HTML
    let itemsHtml = '';
    let totalItems = 0;
    let estimatedTotal = 0;

    this.quoteItems.forEach(item => {
      totalItems += item.quantity;
      estimatedTotal += (parseFloat(item.price) || 0) * item.quantity;

      itemsHtml += `
                <div class="quote-item" data-product-id="${item.id}">
                    <div class="quote-item-info">
                        <h4 class="quote-item-name">${this.escapeHtml(item.name)}</h4>
                        <p class="quote-item-price">$${(parseFloat(item.price) || 0).toFixed(2)} each</p>
                    </div>
                    <div class="quote-item-controls">
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn quantity-decrease" data-product-id="${item.id}">-</button>
                            <input type="number" class="quantity-input" value="${item.quantity}" min="1" data-product-id="${item.id}">
                            <button type="button" class="quantity-btn quantity-increase" data-product-id="${item.id}">+</button>
                        </div>
                        <button type="button" class="remove-item-btn" data-product-id="${item.id}" title="Remove from quote">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            `;
    });

    quoteList.innerHTML = itemsHtml;

    // Update summary
    if (quoteSummary) {
      quoteSummary.innerHTML = `
                <div class="quote-summary-item">
                    <span>Total Items:</span>
                    <span>${totalItems}</span>
                </div>
                <div class="quote-summary-item">
                    <span>Estimated Total:</span>
                    <span>$${estimatedTotal.toFixed(2)}</span>
                </div>
                <p class="quote-disclaimer">
                    <small>* Estimated total based on retail prices. Wholesale pricing will be applied in your custom quote.</small>
                </p>
            `;
      quoteSummary.style.display = 'block';
    }

    // Rebind events for new elements
    this.bindQuoteItemEvents();
  }

  bindQuoteItemEvents() {
    // Quantity controls
    document.querySelectorAll('.quantity-decrease').forEach(button => {
      button.addEventListener('click', e => {
        const productId = e.target.dataset.productId;
        const item = this.quoteItems.find(item => item.id === productId);
        if (item && item.quantity > 1) {
          this.updateQuoteQuantity(productId, item.quantity - 1);
        }
      });
    });

    document.querySelectorAll('.quantity-increase').forEach(button => {
      button.addEventListener('click', e => {
        const productId = e.target.dataset.productId;
        const item = this.quoteItems.find(item => item.id === productId);
        if (item) {
          this.updateQuoteQuantity(productId, item.quantity + 1);
        }
      });
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
      input.addEventListener('change', e => {
        const productId = e.target.dataset.productId;
        const newQuantity = parseInt(e.target.value);
        if (newQuantity >= 1) {
          this.updateQuoteQuantity(productId, newQuantity);
        }
      });
    });

    // Remove buttons
    document.querySelectorAll('.remove-item-btn').forEach(button => {
      button.addEventListener('click', e => {
        const productId = e.target.dataset.productId;
        this.removeFromQuote(productId);
        this.showNotification('Item removed from quote', 'info');
      });
    });
  }

  updateAddToQuoteButton(button) {
    const originalText = button.textContent;
    button.textContent = 'Added!';
    button.disabled = true;

    setTimeout(() => {
      button.textContent = originalText;
      button.disabled = false;
    }, 2000);
  }

  saveQuoteItems() {
    localStorage.setItem(
      'aqualuxe_quote_items',
      JSON.stringify(this.quoteItems)
    );
  }

  loadSavedQuoteItems() {
    const saved = localStorage.getItem('aqualuxe_quote_items');
    if (saved) {
      try {
        this.quoteItems = JSON.parse(saved);
      } catch (e) {
        this.quoteItems = [];
      }
    }
  }

  clearQuote() {
    this.quoteItems = [];
    this.saveQuoteItems();
    this.updateQuoteDisplay();
    this.showNotification('Quote cleared', 'info');
  }

  showFieldError(field, message) {
    this.clearFieldError(field);

    field.classList.add('error');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
  }

  clearFieldError(field) {
    field.classList.remove('error');
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
      existingError.remove();
    }
  }

  showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `aqualuxe-notification aqualuxe-notification-${type}`;
    notification.innerHTML = `
            <span class="notification-message">${this.escapeHtml(message)}</span>
            <button type="button" class="notification-close" aria-label="Close">&times;</button>
        `;

    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
      notification.classList.add('show');
    }, 100);

    // Auto-hide after delay
    const hideTimeout = setTimeout(
      () => {
        this.hideNotification(notification);
      },
      type === 'error' ? 8000 : 5000
    );

    // Close button
    notification
      .querySelector('.notification-close')
      .addEventListener('click', () => {
        clearTimeout(hideTimeout);
        this.hideNotification(notification);
      });
  }

  hideNotification(notification) {
    notification.classList.remove('show');
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 300);
  }

  isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  escapeHtml(text) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;',
    };
    return text.replace(/[&<>"']/g, function (m) {
      return map[m];
    });
  }

  // Public API methods
  getQuoteItems() {
    return [...this.quoteItems];
  }

  getQuoteItemCount() {
    return this.quoteItems.reduce((total, item) => total + item.quantity, 0);
  }

  hasQuoteItems() {
    return this.quoteItems.length > 0;
  }
}

// Initialize the wholesale module
const aqualuxeWholesale = new WholesaleModule();

// Make it globally available
window.AquaLuxeWholesale = aqualuxeWholesale;

// Export for module systems (safely check for Node.js environment)
if (
  typeof window === 'undefined' &&
  typeof module !== 'undefined' &&
  module.exports
) {
  module.exports = WholesaleModule;
}
