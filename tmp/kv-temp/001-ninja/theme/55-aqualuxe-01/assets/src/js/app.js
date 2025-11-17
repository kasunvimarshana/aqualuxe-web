/**
 * AquaLuxe Theme Main JavaScript
 * 
 * This file contains the main JavaScript functionality for the AquaLuxe theme.
 */

// Import dependencies
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import Swiper, { Navigation, Pagination, Autoplay, EffectFade, Thumbs } from 'swiper';

// Register Alpine plugins
Alpine.plugin(persist);

// Register Swiper modules
Swiper.use([Navigation, Pagination, Autoplay, EffectFade, Thumbs]);

// Import components
import './components/header';
import './components/navigation';
import './components/search';
import './components/cart';
import './components/product-gallery';
import './components/product-filters';
import './components/quick-view';
import './components/accordion';
import './components/tabs';
import './components/modal';
import './components/newsletter';
import './components/testimonials';
import './components/animations';
import './components/scroll-to-top';
import './components/lazy-loading';

// Import utilities
import './utils/helpers';
import './utils/focus-trap';
import './utils/accessibility';

/**
 * Document Ready
 */
document.addEventListener('DOMContentLoaded', () => {
  // Initialize Alpine.js
  window.Alpine = Alpine;
  Alpine.start();
  
  // Initialize components
  initializeComponents();
  
  // Handle dark mode
  setupDarkMode();
  
  // Handle responsive behaviors
  handleResponsiveBehaviors();
  
  // Initialize sliders
  initializeSliders();
  
  // Initialize WooCommerce specific functionality if available
  if (typeof wc_add_to_cart_params !== 'undefined') {
    initializeWooCommerce();
  }
});

/**
 * Initialize all components
 */
function initializeComponents() {
  // Initialize header behavior
  const header = document.querySelector('.site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });
  }
  
  // Initialize mobile menu toggle
  const menuToggle = document.querySelector('.menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
      menuToggle.classList.toggle('active');
      mobileMenu.classList.toggle('active');
      document.body.classList.toggle('menu-open');
      
      // Toggle aria-expanded
      const expanded = menuToggle.getAttribute('aria-expanded') === 'true' || false;
      menuToggle.setAttribute('aria-expanded', !expanded);
    });
  }
  
  // Initialize dropdowns
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      const parent = toggle.closest('.menu-item-has-children');
      parent.classList.toggle('active');
      
      // Toggle aria-expanded
      const expanded = toggle.getAttribute('aria-expanded') === 'true' || false;
      toggle.setAttribute('aria-expanded', !expanded);
    });
  });
  
  // Initialize scroll to top
  const scrollToTop = document.querySelector('.scroll-to-top');
  if (scrollToTop) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        scrollToTop.classList.add('active');
      } else {
        scrollToTop.classList.remove('active');
      }
    });
    
    scrollToTop.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }
  
  // Initialize accordions
  const accordionToggles = document.querySelectorAll('.accordion-toggle');
  accordionToggles.forEach(toggle => {
    toggle.addEventListener('click', () => {
      const parent = toggle.closest('.accordion-item');
      const content = parent.querySelector('.accordion-content');
      
      // Toggle active class
      parent.classList.toggle('active');
      
      // Toggle aria-expanded
      const expanded = toggle.getAttribute('aria-expanded') === 'true' || false;
      toggle.setAttribute('aria-expanded', !expanded);
      
      // Toggle content height
      if (parent.classList.contains('active')) {
        content.style.maxHeight = content.scrollHeight + 'px';
      } else {
        content.style.maxHeight = '0';
      }
    });
  });
  
  // Initialize tabs
  const tabLinks = document.querySelectorAll('.tab-link');
  tabLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      
      const tabId = link.getAttribute('href');
      const tabContent = document.querySelector(tabId);
      
      // Remove active class from all tab links and content
      document.querySelectorAll('.tab-link').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
      
      // Add active class to current tab link and content
      link.classList.add('active');
      tabContent.classList.add('active');
      
      // Update aria-selected
      document.querySelectorAll('.tab-link').forEach(el => el.setAttribute('aria-selected', 'false'));
      link.setAttribute('aria-selected', 'true');
    });
  });
  
  // Initialize modals
  const modalTriggers = document.querySelectorAll('[data-modal]');
  modalTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      
      const modalId = trigger.getAttribute('data-modal');
      const modal = document.querySelector(modalId);
      
      if (modal) {
        modal.classList.add('active');
        document.body.classList.add('modal-open');
        
        // Focus first focusable element
        const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusableElements.length) {
          focusableElements[0].focus();
        }
      }
    });
  });
  
  const modalCloseButtons = document.querySelectorAll('.modal-close');
  modalCloseButtons.forEach(button => {
    button.addEventListener('click', () => {
      const modal = button.closest('.modal');
      modal.classList.remove('active');
      document.body.classList.remove('modal-open');
    });
  });
  
  // Close modal when clicking outside
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
      e.target.classList.remove('active');
      document.body.classList.remove('modal-open');
    }
  });
  
  // Close modal with Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const activeModal = document.querySelector('.modal.active');
      if (activeModal) {
        activeModal.classList.remove('active');
        document.body.classList.remove('modal-open');
      }
    }
  });
  
  // Initialize newsletter form
  const newsletterForm = document.querySelector('.newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const emailInput = newsletterForm.querySelector('input[type="email"]');
      const submitButton = newsletterForm.querySelector('button[type="submit"]');
      const successMessage = newsletterForm.querySelector('.success-message');
      const errorMessage = newsletterForm.querySelector('.error-message');
      
      // Simple validation
      if (emailInput.value.trim() === '' || !emailInput.value.includes('@')) {
        errorMessage.style.display = 'block';
        successMessage.style.display = 'none';
        return;
      }
      
      // Simulate form submission
      submitButton.disabled = true;
      submitButton.innerHTML = 'Sending...';
      
      // Simulate API call
      setTimeout(() => {
        submitButton.disabled = false;
        submitButton.innerHTML = 'Subscribe';
        emailInput.value = '';
        successMessage.style.display = 'block';
        errorMessage.style.display = 'none';
      }, 1000);
    });
  }
}

/**
 * Setup dark mode functionality
 */
function setupDarkMode() {
  // Check for saved theme preference or respect OS preference
  const savedTheme = localStorage.getItem('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
  
  // Initialize theme toggle
  const themeToggle = document.querySelector('.theme-toggle');
  if (themeToggle) {
    // Update toggle state
    updateThemeToggle(themeToggle);
    
    // Handle toggle click
    themeToggle.addEventListener('click', () => {
      if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
      } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      }
      
      // Update toggle state
      updateThemeToggle(themeToggle);
    });
  }
}

/**
 * Update theme toggle button state
 */
function updateThemeToggle(toggle) {
  const isDark = document.documentElement.classList.contains('dark');
  const lightIcon = toggle.querySelector('.light-icon');
  const darkIcon = toggle.querySelector('.dark-icon');
  
  if (isDark) {
    toggle.setAttribute('aria-label', 'Switch to light mode');
    toggle.setAttribute('title', 'Switch to light mode');
    lightIcon.style.display = 'block';
    darkIcon.style.display = 'none';
  } else {
    toggle.setAttribute('aria-label', 'Switch to dark mode');
    toggle.setAttribute('title', 'Switch to dark mode');
    lightIcon.style.display = 'none';
    darkIcon.style.display = 'block';
  }
}

/**
 * Handle responsive behaviors
 */
function handleResponsiveBehaviors() {
  const handleResize = () => {
    // Handle menu behavior on resize
    const mobileMenu = document.querySelector('.mobile-menu');
    const menuToggle = document.querySelector('.menu-toggle');
    
    if (window.innerWidth >= 1024 && mobileMenu && mobileMenu.classList.contains('active')) {
      mobileMenu.classList.remove('active');
      menuToggle.classList.remove('active');
      document.body.classList.remove('menu-open');
    }
  };
  
  // Initial call
  handleResize();
  
  // Add resize event listener
  window.addEventListener('resize', handleResize);
}

/**
 * Initialize sliders
 */
function initializeSliders() {
  // Hero slider
  const heroSlider = document.querySelector('.hero-slider');
  if (heroSlider) {
    new Swiper(heroSlider, {
      slidesPerView: 1,
      spaceBetween: 0,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      effect: 'fade',
      fadeEffect: {
        crossFade: true
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  }
  
  // Featured products slider
  const featuredSlider = document.querySelector('.featured-products-slider');
  if (featuredSlider) {
    new Swiper(featuredSlider, {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 4,
        },
      },
    });
  }
  
  // Testimonials slider
  const testimonialsSlider = document.querySelector('.testimonials-slider');
  if (testimonialsSlider) {
    new Swiper(testimonialsSlider, {
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      },
    });
  }
  
  // Product gallery slider
  const productGallery = document.querySelector('.product-gallery-slider');
  const productThumbs = document.querySelector('.product-gallery-thumbs');
  
  if (productGallery && productThumbs) {
    const thumbsSwiper = new Swiper(productThumbs, {
      spaceBetween: 10,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });
    
    new Swiper(productGallery, {
      spaceBetween: 0,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      thumbs: {
        swiper: thumbsSwiper,
      },
    });
  }
}

/**
 * Initialize WooCommerce specific functionality
 */
function initializeWooCommerce() {
  // Product quantity input
  const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
  quantityInputs.forEach(input => {
    const minusBtn = document.createElement('button');
    minusBtn.type = 'button';
    minusBtn.className = 'quantity-btn minus';
    minusBtn.innerHTML = '−';
    minusBtn.setAttribute('aria-label', 'Decrease quantity');
    
    const plusBtn = document.createElement('button');
    plusBtn.type = 'button';
    plusBtn.className = 'quantity-btn plus';
    plusBtn.innerHTML = '+';
    plusBtn.setAttribute('aria-label', 'Increase quantity');
    
    input.parentNode.insertBefore(minusBtn, input);
    input.parentNode.appendChild(plusBtn);
    
    minusBtn.addEventListener('click', () => {
      const currentValue = parseInt(input.value);
      const minValue = parseInt(input.getAttribute('min')) || 1;
      if (currentValue > minValue) {
        input.value = currentValue - 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
    
    plusBtn.addEventListener('click', () => {
      const currentValue = parseInt(input.value);
      const maxValue = parseInt(input.getAttribute('max'));
      if (!maxValue || currentValue < maxValue) {
        input.value = currentValue + 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
  });
  
  // Quick view functionality
  const quickViewButtons = document.querySelectorAll('.quick-view-button');
  quickViewButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      
      const productId = button.getAttribute('data-product-id');
      const modal = document.querySelector('#quick-view-modal');
      const modalContent = modal.querySelector('.modal-content');
      
      // Show loading state
      modalContent.innerHTML = '<div class="loading">Loading...</div>';
      modal.classList.add('active');
      document.body.classList.add('modal-open');
      
      // Fetch product data (in a real implementation, this would be an AJAX call)
      // For demo purposes, we'll simulate a delay
      setTimeout(() => {
        // This would normally be populated with AJAX response data
        modalContent.innerHTML = `
          <button class="modal-close" aria-label="Close modal">&times;</button>
          <div class="product-quick-view">
            <div class="product-quick-view-image">
              <img src="https://via.placeholder.com/600x600" alt="Product Image">
            </div>
            <div class="product-quick-view-details">
              <h2>Product Name</h2>
              <div class="price">$99.99</div>
              <div class="rating">★★★★☆</div>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl vel ultricies lacinia, nisl nisl aliquam nisl, eget aliquam nisl nisl sit amet nisl.</p>
              <form class="cart">
                <div class="quantity">
                  <input type="number" min="1" value="1">
                </div>
                <button type="submit" class="btn">Add to Cart</button>
              </form>
              <div class="meta">
                <span>SKU: 12345</span>
                <span>Category: <a href="#">Fish</a></span>
              </div>
            </div>
          </div>
        `;
        
        // Reinitialize quantity buttons
        initializeComponents();
      }, 500);
    });
  });
  
  // Add to cart AJAX
  const addToCartForms = document.querySelectorAll('form.cart');
  addToCartForms.forEach(form => {
    form.addEventListener('submit', (e) => {
      // In a real implementation, this would use the WooCommerce AJAX add to cart functionality
      // For demo purposes, we'll just prevent the default form submission
      if (!form.classList.contains('variations_form')) {
        e.preventDefault();
        
        const addToCartButton = form.querySelector('button[type="submit"]');
        const originalButtonText = addToCartButton.innerHTML;
        
        // Show loading state
        addToCartButton.disabled = true;
        addToCartButton.innerHTML = 'Adding...';
        
        // Simulate AJAX request
        setTimeout(() => {
          // Reset button
          addToCartButton.disabled = false;
          addToCartButton.innerHTML = originalButtonText;
          
          // Show success message
          const successMessage = document.createElement('div');
          successMessage.className = 'added-to-cart-message';
          successMessage.innerHTML = 'Product added to cart!';
          form.appendChild(successMessage);
          
          // Remove message after 3 seconds
          setTimeout(() => {
            successMessage.remove();
          }, 3000);
          
          // Update cart count (this would normally come from the AJAX response)
          const cartCount = document.querySelector('.cart-count');
          if (cartCount) {
            const currentCount = parseInt(cartCount.textContent);
            cartCount.textContent = currentCount + 1;
          }
        }, 800);
      }
    });
  });
  
  // Product filters
  const filterToggles = document.querySelectorAll('.filter-toggle');
  filterToggles.forEach(toggle => {
    toggle.addEventListener('click', () => {
      const filterWidget = toggle.nextElementSibling;
      toggle.classList.toggle('active');
      
      if (filterWidget.style.maxHeight) {
        filterWidget.style.maxHeight = null;
      } else {
        filterWidget.style.maxHeight = filterWidget.scrollHeight + 'px';
      }
    });
  });
  
  // Price range slider (simplified version)
  const priceRange = document.querySelector('.price-range');
  if (priceRange) {
    const minInput = priceRange.querySelector('.min-price');
    const maxInput = priceRange.querySelector('.max-price');
    const rangeInputs = priceRange.querySelectorAll('input[type="range"]');
    
    rangeInputs.forEach(input => {
      input.addEventListener('input', () => {
        const minVal = parseInt(rangeInputs[0].value);
        const maxVal = parseInt(rangeInputs[1].value);
        
        if (maxVal - minVal < 10) {
          if (input === rangeInputs[0]) {
            rangeInputs[0].value = maxVal - 10;
          } else {
            rangeInputs[1].value = minVal + 10;
          }
        } else {
          minInput.value = minVal;
          maxInput.value = maxVal;
        }
      });
    });
    
    minInput.addEventListener('change', () => {
      rangeInputs[0].value = minInput.value;
    });
    
    maxInput.addEventListener('change', () => {
      rangeInputs[1].value = maxInput.value;
    });
  }
}

// Export for use in other files
export {
  initializeComponents,
  setupDarkMode,
  handleResponsiveBehaviors,
  initializeSliders,
  initializeWooCommerce
};