/**
 * AquaLuxe Theme Main JavaScript
 * 
 * Handles theme functionality including navigation, search, dark mode,
 * and general UI interactions.
 */

// Import Alpine.js for reactivity
import Alpine from 'alpinejs';

// Import Swiper for carousels
import { Swiper, Navigation, Pagination, Autoplay } from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Configure Swiper modules
Swiper.use([Navigation, Pagination, Autoplay]);

// Start Alpine
window.Alpine = Alpine;
Alpine.start();

// Main theme object
const AquaLuxe = {
  
  /**
   * Initialize theme
   */
  init() {
    this.initNavigation();
    this.initSearch();
    this.initDarkMode();
    this.initCarousels();
    this.initModals();
    this.initForms();
    this.initScrollEffects();
    this.initLazyLoading();
    this.initAccessibility();
    
    // WooCommerce specific functionality
    if (window.wc_add_to_cart_params) {
      this.initWooCommerce();
    }

    // Trigger loaded event
    document.dispatchEvent(new CustomEvent('aqualuxe:loaded'));
  },

  /**
   * Initialize navigation
   */
  initNavigation() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (mobileToggle && mobileMenu) {
      mobileToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        mobileToggle.setAttribute('aria-expanded', 
          mobileMenu.classList.contains('hidden') ? 'false' : 'true'
        );
      });
    }

    // Close mobile menu on outside click
    document.addEventListener('click', (e) => {
      if (mobileMenu && !mobileMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
        mobileMenu.classList.add('hidden');
        mobileToggle.setAttribute('aria-expanded', 'false');
      }
    });

    // Close mobile menu on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.add('hidden');
        mobileToggle.setAttribute('aria-expanded', 'false');
      }
    });
  },

  /**
   * Initialize search functionality
   */
  initSearch() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchContainer = document.querySelector('.search-form-container');
    const searchInput = document.querySelector('#s');

    if (searchToggle && searchContainer) {
      searchToggle.addEventListener('click', () => {
        searchContainer.classList.toggle('hidden');
        if (!searchContainer.classList.contains('hidden') && searchInput) {
          searchInput.focus();
        }
      });
    }

    // Close search on escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && searchContainer && !searchContainer.classList.contains('hidden')) {
        searchContainer.classList.add('hidden');
      }
    });
  },

  /**
   * Initialize dark mode
   */
  initDarkMode() {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const html = document.documentElement;
    
    // Check for saved preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    if (currentTheme === 'dark') {
      html.classList.add('dark');
    }

    if (darkModeToggle) {
      darkModeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        
        // Save preference
        const theme = html.classList.contains('dark') ? 'dark' : 'light';
        localStorage.setItem('theme', theme);
        
        // Trigger event for other scripts
        document.dispatchEvent(new CustomEvent('aqualuxe:theme-changed', { 
          detail: { theme } 
        }));
      });
    }
  },

  /**
   * Initialize carousels
   */
  initCarousels() {
    // Hero carousel
    const heroSwiper = new Swiper('.hero-swiper', {
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
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

    // Product carousels
    const productSwipers = document.querySelectorAll('.product-swiper');
    productSwipers.forEach(swiper => {
      new Swiper(swiper, {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
          nextEl: swiper.querySelector('.swiper-button-next'),
          prevEl: swiper.querySelector('.swiper-button-prev'),
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
    });
  },

  /**
   * Initialize modals
   */
  initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal]');
    
    modalTriggers.forEach(trigger => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        const modalId = trigger.getAttribute('data-modal');
        const modal = document.getElementById(modalId);
        
        if (modal) {
          this.openModal(modal);
        }
      });
    });

    // Close modal on outside click
    document.addEventListener('click', (e) => {
      const modal = e.target.closest('.modal');
      if (modal && e.target === modal) {
        this.closeModal(modal);
      }
    });

    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        const openModal = document.querySelector('.modal:not(.hidden)');
        if (openModal) {
          this.closeModal(openModal);
        }
      }
    });
  },

  /**
   * Open modal
   */
  openModal(modal) {
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus first focusable element
    const focusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    if (focusable) {
      focusable.focus();
    }
  },

  /**
   * Close modal
   */
  closeModal(modal) {
    modal.classList.add('hidden');
    document.body.style.overflow = '';
  },

  /**
   * Initialize forms
   */
  initForms() {
    // AJAX form handling
    const ajaxForms = document.querySelectorAll('.ajax-form');
    
    ajaxForms.forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleAjaxForm(form);
      });
    });
  },

  /**
   * Handle AJAX form submission
   */
  async handleAjaxForm(form) {
    const formData = new FormData(form);
    const submitBtn = form.querySelector('[type="submit"]');
    const originalText = submitBtn.textContent;
    
    // Show loading state
    submitBtn.textContent = window.aqualuxe_ajax.strings.loading;
    submitBtn.disabled = true;
    
    try {
      const response = await fetch(window.aqualuxe_ajax.ajax_url, {
        method: 'POST',
        body: formData
      });
      
      const result = await response.json();
      
      if (result.success) {
        this.showNotification(result.data.message, 'success');
        if (result.data.redirect) {
          window.location.href = result.data.redirect;
        }
      } else {
        this.showNotification(result.data.message || window.aqualuxe_ajax.strings.error, 'error');
      }
    } catch (error) {
      this.showNotification(window.aqualuxe_ajax.strings.error, 'error');
    } finally {
      // Reset button
      submitBtn.textContent = originalText;
      submitBtn.disabled = false;
    }
  },

  /**
   * Show notification
   */
  showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
      type === 'success' ? 'bg-green-500 text-white' :
      type === 'error' ? 'bg-red-500 text-white' :
      'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
      notification.remove();
    }, 5000);
  },

  /**
   * Initialize scroll effects
   */
  initScrollEffects() {
    // Scroll to top button
    const scrollToTop = document.querySelector('.scroll-to-top');
    
    if (scrollToTop) {
      window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
          scrollToTop.classList.remove('hidden');
        } else {
          scrollToTop.classList.add('hidden');
        }
      });

      scrollToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    // Intersection Observer for animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fade-in');
        }
      });
    }, observerOptions);

    // Observe elements with fade-in class
    document.querySelectorAll('.fade-in').forEach(el => {
      observer.observe(el);
    });
  },

  /**
   * Initialize lazy loading
   */
  initLazyLoading() {
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.remove('lazy');
            imageObserver.unobserve(img);
          }
        });
      });

      document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
      });
    }
  },

  /**
   * Initialize accessibility features
   */
  initAccessibility() {
    // Skip links
    const skipLinks = document.querySelectorAll('.skip-link');
    skipLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        const target = document.querySelector(link.getAttribute('href'));
        if (target) {
          target.focus();
        }
      });
    });

    // Focus management for dropdowns
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
      const trigger = dropdown.querySelector('.dropdown-trigger');
      const menu = dropdown.querySelector('.dropdown-menu');
      
      if (trigger && menu) {
        trigger.addEventListener('click', () => {
          const isOpen = !menu.classList.contains('hidden');
          if (isOpen) {
            const firstItem = menu.querySelector('a, button');
            if (firstItem) {
              firstItem.focus();
            }
          }
        });
      }
    });
  },

  /**
   * Initialize WooCommerce functionality
   */
  initWooCommerce() {
    // Add to cart handling
    document.addEventListener('click', (e) => {
      if (e.target.matches('.ajax_add_to_cart')) {
        e.preventDefault();
        this.handleAddToCart(e.target);
      }
    });

    // Quick view
    const quickViewButtons = document.querySelectorAll('.quick-view');
    quickViewButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const productId = button.dataset.productId;
        this.openQuickView(productId);
      });
    });
  },

  /**
   * Handle add to cart
   */
  async handleAddToCart(button) {
    const productId = button.dataset.productId;
    const quantity = button.dataset.quantity || 1;
    
    try {
      const response = await fetch(window.wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=${quantity}`
      });
      
      const result = await response.json();
      
      if (result.error) {
        this.showNotification(result.error_message, 'error');
      } else {
        this.showNotification('Product added to cart!', 'success');
        // Update cart count
        this.updateCartCount();
      }
    } catch (error) {
      this.showNotification('Failed to add product to cart', 'error');
    }
  },

  /**
   * Update cart count
   */
  updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
      // This would typically be updated via AJAX to get the actual count
      // For now, we'll increment the current count
      const current = parseInt(cartCount.textContent) || 0;
      cartCount.textContent = current + 1;
    }
  },

  /**
   * Open quick view modal
   */
  async openQuickView(productId) {
    // Implementation would load product data via AJAX
    // and display in a modal
    console.log('Opening quick view for product:', productId);
  }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => AquaLuxe.init());
} else {
  AquaLuxe.init();
}

// Export for use in other scripts
window.AquaLuxe = AquaLuxe;