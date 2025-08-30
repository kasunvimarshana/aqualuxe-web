// Import Alpine.js
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();

// Dark mode functionality
document.addEventListener('DOMContentLoaded', () => {
  // Check for saved theme preference or respect OS preference
  const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
  const savedTheme = localStorage.getItem('theme');
  
  // Apply the saved theme or use the OS preference
  if (savedTheme === 'dark' || (!savedTheme && darkModeMediaQuery.matches)) {
    document.documentElement.classList.add('dark');
    document.querySelectorAll('.dark-mode-toggle input').forEach(toggle => {
      toggle.checked = true;
    });
  }
  
  // Set up dark mode toggle listeners
  document.querySelectorAll('.dark-mode-toggle input').forEach(toggle => {
    toggle.addEventListener('change', () => {
      if (toggle.checked) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
      }
    });
  });
  
  // Listen for OS changes
  darkModeMediaQuery.addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      if (e.matches) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    }
  });
});

// Mobile navigation
document.addEventListener('DOMContentLoaded', () => {
  const mobileMenuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  
  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }
});

// WooCommerce specific scripts
document.addEventListener('DOMContentLoaded', () => {
  // Quick view functionality
  const quickViewButtons = document.querySelectorAll('.quick-view-button');
  const quickViewModal = document.getElementById('quick-view-modal');
  const quickViewClose = document.getElementById('quick-view-close');
  const quickViewContent = document.getElementById('quick-view-content');
  
  if (quickViewButtons.length && quickViewModal && quickViewClose && quickViewContent) {
    quickViewButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const productId = button.getAttribute('data-product-id');
        
        // Show loading state
        quickViewContent.innerHTML = '<div class="flex justify-center items-center h-64"><div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div></div>';
        quickViewModal.classList.remove('hidden');
        
        // Fetch product data
        fetch(`/wp-json/aqualuxe/v1/product/${productId}`)
          .then(response => response.json())
          .then(data => {
            quickViewContent.innerHTML = data.html;
            
            // Initialize product gallery if needed
            if (typeof wc_single_product_params !== 'undefined') {
              $('.woocommerce-product-gallery').each(function() {
                $(this).wc_product_gallery(wc_single_product_params);
              });
            }
          })
          .catch(error => {
            quickViewContent.innerHTML = `<div class="p-4 text-center">Error loading product. Please try again.</div>`;
            console.error('Quick view error:', error);
          });
      });
    });
    
    quickViewClose.addEventListener('click', () => {
      quickViewModal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    quickViewModal.addEventListener('click', (e) => {
      if (e.target === quickViewModal) {
        quickViewModal.classList.add('hidden');
      }
    });
  }
  
  // Quantity input buttons
  const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
  
  if (quantityInputs.length) {
    quantityInputs.forEach(input => {
      const wrapper = document.createElement('div');
      wrapper.className = 'flex items-center';
      
      const minusButton = document.createElement('button');
      minusButton.type = 'button';
      minusButton.className = 'quantity-button minus bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-l';
      minusButton.textContent = '-';
      
      const plusButton = document.createElement('button');
      plusButton.type = 'button';
      plusButton.className = 'quantity-button plus bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-r';
      plusButton.textContent = '+';
      
      input.parentNode.insertBefore(wrapper, input);
      wrapper.appendChild(minusButton);
      wrapper.appendChild(input);
      wrapper.appendChild(plusButton);
      
      minusButton.addEventListener('click', () => {
        const currentValue = parseInt(input.value);
        const minValue = parseInt(input.getAttribute('min')) || 1;
        if (currentValue > minValue) {
          input.value = currentValue - 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
      
      plusButton.addEventListener('click', () => {
        const currentValue = parseInt(input.value);
        const maxValue = parseInt(input.getAttribute('max'));
        if (!maxValue || currentValue < maxValue) {
          input.value = currentValue + 1;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
    });
  }
  
  // Wishlist functionality
  const wishlistButtons = document.querySelectorAll('.wishlist-button');
  
  if (wishlistButtons.length) {
    wishlistButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const productId = button.getAttribute('data-product-id');
        
        // Toggle wishlist state
        button.classList.toggle('wishlist-active');
        
        // Send AJAX request to update wishlist
        fetch('/wp-json/aqualuxe/v1/wishlist', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': aqualuxeData.nonce
          },
          body: JSON.stringify({
            product_id: productId,
            action: button.classList.contains('wishlist-active') ? 'add' : 'remove'
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update wishlist count if needed
            const wishlistCount = document.querySelector('.wishlist-count');
            if (wishlistCount) {
              wishlistCount.textContent = data.count;
            }
          } else {
            // Revert button state on error
            button.classList.toggle('wishlist-active');
            console.error('Wishlist update failed');
          }
        })
        .catch(error => {
          // Revert button state on error
          button.classList.toggle('wishlist-active');
          console.error('Wishlist error:', error);
        });
      });
    });
  }
  
  // Product filters
  const filterForm = document.getElementById('product-filters');
  
  if (filterForm) {
    const filterInputs = filterForm.querySelectorAll('input, select');
    
    filterInputs.forEach(input => {
      input.addEventListener('change', () => {
        // Add loading overlay
        const productsContainer = document.querySelector('.products');
        if (productsContainer) {
          const loadingOverlay = document.createElement('div');
          loadingOverlay.className = 'absolute inset-0 bg-white bg-opacity-75 dark:bg-dark-bg dark:bg-opacity-75 flex items-center justify-center z-10';
          loadingOverlay.innerHTML = '<div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>';
          
          productsContainer.style.position = 'relative';
          productsContainer.appendChild(loadingOverlay);
        }
        
        // Submit form
        filterForm.submit();
      });
    });
  }
});

// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      
      if (href !== '#') {
        e.preventDefault();
        
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth'
          });
        }
      }
    });
  });
});

// Lazy loading images
document.addEventListener('DOMContentLoaded', () => {
  if ('loading' in HTMLImageElement.prototype) {
    // Browser supports native lazy loading
    const images = document.querySelectorAll('img.lazy');
    images.forEach(img => {
      img.src = img.dataset.src;
      img.classList.remove('lazy');
    });
  } else {
    // Fallback for browsers that don't support native lazy loading
    const lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));
    
    if ('IntersectionObserver' in window) {
      const lazyImageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            const lazyImage = entry.target;
            lazyImage.src = lazyImage.dataset.src;
            lazyImage.classList.remove('lazy');
            lazyImageObserver.unobserve(lazyImage);
          }
        });
      });
      
      lazyImages.forEach(function(lazyImage) {
        lazyImageObserver.observe(lazyImage);
      });
    }
  }
});

// Newsletter subscription
document.addEventListener('DOMContentLoaded', () => {
  const newsletterForm = document.getElementById('newsletter-form');
  
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const email = newsletterForm.querySelector('input[type="email"]').value;
      const submitButton = newsletterForm.querySelector('button[type="submit"]');
      const responseMessage = newsletterForm.querySelector('.response-message');
      
      // Disable button and show loading state
      submitButton.disabled = true;
      submitButton.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white mr-2"></span> Subscribing...';
      
      // Send AJAX request
      fetch('/wp-json/aqualuxe/v1/newsletter', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': aqualuxeData.nonce
        },
        body: JSON.stringify({
          email: email
        })
      })
      .then(response => response.json())
      .then(data => {
        // Reset button
        submitButton.disabled = false;
        submitButton.textContent = 'Subscribe';
        
        // Show response message
        if (data.success) {
          responseMessage.textContent = data.message;
          responseMessage.className = 'response-message text-green-600 dark:text-green-400 mt-2';
          newsletterForm.reset();
        } else {
          responseMessage.textContent = data.message;
          responseMessage.className = 'response-message text-red-600 dark:text-red-400 mt-2';
        }
      })
      .catch(error => {
        // Reset button
        submitButton.disabled = false;
        submitButton.textContent = 'Subscribe';
        
        // Show error message
        responseMessage.textContent = 'An error occurred. Please try again.';
        responseMessage.className = 'response-message text-red-600 dark:text-red-400 mt-2';
        console.error('Newsletter error:', error);
      });
    });
  }
});