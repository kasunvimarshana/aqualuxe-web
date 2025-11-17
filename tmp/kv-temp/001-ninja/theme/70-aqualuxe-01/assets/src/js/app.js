/**
 * Main Application JavaScript
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Import dependencies
import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Headroom from 'headroom.js';
import Swiper, { Navigation, Pagination, Autoplay, EffectFade } from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import 'swiper/css/effect-fade';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Configure Swiper modules
Swiper.use([Navigation, Pagination, Autoplay, EffectFade]);

// Make Alpine available globally
window.Alpine = Alpine;

/**
 * AquaLuxe Theme Class
 */
class AquaLuxeTheme {
  constructor() {
    this.init();
  }

  /**
   * Initialize theme
   */
  init() {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.setup());
    } else {
      this.setup();
    }
  }

  /**
   * Setup theme functionality
   */
  setup() {
    this.initAOS();
    this.initAlpine();
    this.initNavigation();
    this.initScrollEffects();
    this.initModals();
    this.initSliders();
    this.initForms();
    this.initLazyLoading();
    this.initSearchToggle();
    this.initMobileMenu();
    this.initBackToTop();
    this.initPreloader();
    this.initAnimations();
    this.initAccessibility();
    
    // Initialize dark mode if enabled
    if (aqualuxeAjax.dark_mode_enabled) {
      this.initDarkMode();
    }
    
    // Initialize WooCommerce features if active
    if (aqualuxeAjax.is_woocommerce_active) {
      this.initWooCommerce();
    }
    
    // Emit custom event
    this.emitEvent('aqualuxe:initialized');
  }

  /**
   * Initialize AOS (Animate On Scroll)
   */
  initAOS() {
    AOS.init({
      duration: 800,
      easing: 'ease-out-cubic',
      once: true,
      offset: 50,
      disable: function() {
        return window.innerWidth < 768;
      }
    });
  }

  /**
   * Initialize Alpine.js
   */
  initAlpine() {
    Alpine.start();
  }

  /**
   * Initialize navigation
   */
  initNavigation() {
    const header = document.querySelector('.site-header');
    if (header) {
      // Initialize Headroom for sticky header
      const headroom = new Headroom(header, {
        offset: 100,
        tolerance: 5,
        classes: {
          initial: 'headroom',
          pinned: 'headroom--pinned',
          unpinned: 'headroom--unpinned',
          top: 'headroom--top',
          notTop: 'headroom--not-top',
          bottom: 'headroom--bottom',
          notBottom: 'headroom--not-bottom'
        }
      });
      headroom.init();
    }

    // Dropdown menus
    this.initDropdownMenus();
    
    // Mega menu
    this.initMegaMenu();
  }

  /**
   * Initialize dropdown menus
   */
  initDropdownMenus() {
    const dropdowns = document.querySelectorAll('.menu-item-has-children');
    
    dropdowns.forEach(dropdown => {
      const link = dropdown.querySelector('a');
      const submenu = dropdown.querySelector('.sub-menu');
      
      if (link && submenu) {
        link.addEventListener('click', (e) => {
          if (window.innerWidth < 1024) {
            e.preventDefault();
            submenu.classList.toggle('open');
          }
        });

        // Hover effects for desktop
        if (window.innerWidth >= 1024) {
          dropdown.addEventListener('mouseenter', () => {
            submenu.classList.add('show');
          });

          dropdown.addEventListener('mouseleave', () => {
            submenu.classList.remove('show');
          });
        }
      }
    });
  }

  /**
   * Initialize mega menu
   */
  initMegaMenu() {
    const megaMenuItems = document.querySelectorAll('.mega-menu-item');
    
    megaMenuItems.forEach(item => {
      const megaMenu = item.querySelector('.mega-menu');
      
      if (megaMenu) {
        item.addEventListener('mouseenter', () => {
          megaMenu.classList.add('show');
        });

        item.addEventListener('mouseleave', () => {
          megaMenu.classList.remove('show');
        });
      }
    });
  }

  /**
   * Initialize scroll effects
   */
  initScrollEffects() {
    // Parallax effects
    const parallaxElements = document.querySelectorAll('.parallax');
    
    parallaxElements.forEach(element => {
      gsap.to(element, {
        yPercent: -50,
        ease: "none",
        scrollTrigger: {
          trigger: element,
          start: "top bottom",
          end: "bottom top",
          scrub: true
        }
      });
    });

    // Fade in animations
    const fadeElements = document.querySelectorAll('.fade-in');
    
    fadeElements.forEach(element => {
      gsap.fromTo(element, 
        { opacity: 0, y: 30 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          scrollTrigger: {
            trigger: element,
            start: "top 80%",
            toggleActions: "play none none reverse"
          }
        }
      );
    });
  }

  /**
   * Initialize modals
   */
  initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    const modals = document.querySelectorAll('.modal');
    
    modalTriggers.forEach(trigger => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        const targetModal = document.querySelector(trigger.dataset.modalTarget);
        if (targetModal) {
          this.openModal(targetModal);
        }
      });
    });

    modals.forEach(modal => {
      const closeButtons = modal.querySelectorAll('.modal-close');
      
      closeButtons.forEach(button => {
        button.addEventListener('click', () => {
          this.closeModal(modal);
        });
      });

      // Close on backdrop click
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          this.closeModal(modal);
        }
      });
    });

    // Close modals with Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        const openModal = document.querySelector('.modal.open');
        if (openModal) {
          this.closeModal(openModal);
        }
      }
    });
  }

  /**
   * Open modal
   */
  openModal(modal) {
    modal.classList.add('open');
    document.body.classList.add('modal-open');
    
    // Focus management
    const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    if (focusableElements.length > 0) {
      focusableElements[0].focus();
    }
  }

  /**
   * Close modal
   */
  closeModal(modal) {
    modal.classList.remove('open');
    document.body.classList.remove('modal-open');
  }

  /**
   * Initialize sliders
   */
  initSliders() {
    // Hero slider
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider) {
      new Swiper(heroSlider, {
        effect: 'fade',
        fadeEffect: {
          crossFade: true
        },
        autoplay: {
          delay: 5000,
          disableOnInteraction: false
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        }
      });
    }

    // Product sliders
    const productSliders = document.querySelectorAll('.product-slider');
    productSliders.forEach(slider => {
      new Swiper(slider, {
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        },
        breakpoints: {
          640: {
            slidesPerView: 2
          },
          768: {
            slidesPerView: 3
          },
          1024: {
            slidesPerView: 4
          }
        }
      });
    });

    // Testimonial slider
    const testimonialSlider = document.querySelector('.testimonial-slider');
    if (testimonialSlider) {
      new Swiper(testimonialSlider, {
        slidesPerView: 1,
        spaceBetween: 30,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        breakpoints: {
          768: {
            slidesPerView: 2
          },
          1024: {
            slidesPerView: 3
          }
        }
      });
    }
  }

  /**
   * Initialize forms
   */
  initForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
      // Add loading state to submit buttons
      form.addEventListener('submit', () => {
        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
          submitButton.disabled = true;
          submitButton.classList.add('loading');
        }
      });

      // Form validation
      this.initFormValidation(form);
    });

    // Newsletter forms
    this.initNewsletterForms();
    
    // Contact forms
    this.initContactForms();
  }

  /**
   * Initialize form validation
   */
  initFormValidation(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    inputs.forEach(input => {
      input.addEventListener('blur', () => {
        this.validateField(input);
      });

      input.addEventListener('input', () => {
        if (input.classList.contains('error')) {
          this.validateField(input);
        }
      });
    });
  }

  /**
   * Validate field
   */
  validateField(field) {
    const value = field.value.trim();
    const isValid = field.checkValidity();
    
    field.classList.toggle('error', !isValid);
    
    const errorMessage = field.parentNode.querySelector('.error-message');
    if (errorMessage) {
      errorMessage.style.display = isValid ? 'none' : 'block';
    }
    
    return isValid;
  }

  /**
   * Initialize newsletter forms
   */
  initNewsletterForms() {
    const newsletterForms = document.querySelectorAll('.newsletter-form');
    
    newsletterForms.forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleNewsletterSubmit(form);
      });
    });
  }

  /**
   * Handle newsletter submit
   */
  async handleNewsletterSubmit(form) {
    const formData = new FormData(form);
    formData.append('action', 'aqualuxe_newsletter_subscribe');
    formData.append('nonce', aqualuxeAjax.nonce);

    try {
      const response = await fetch(aqualuxeAjax.ajaxurl, {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        this.showNotification(aqualuxeAjax.strings.success, 'success');
        form.reset();
      } else {
        this.showNotification(result.data.message || aqualuxeAjax.strings.error, 'error');
      }
    } catch (error) {
      this.showNotification(aqualuxeAjax.strings.error, 'error');
    }
  }

  /**
   * Initialize contact forms
   */
  initContactForms() {
    const contactForms = document.querySelectorAll('.contact-form');
    
    contactForms.forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleContactSubmit(form);
      });
    });
  }

  /**
   * Handle contact form submit
   */
  async handleContactSubmit(form) {
    const formData = new FormData(form);
    formData.append('action', 'aqualuxe_contact_form');
    formData.append('nonce', aqualuxeAjax.nonce);

    try {
      const response = await fetch(aqualuxeAjax.ajaxurl, {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        this.showNotification(result.data.message || aqualuxeAjax.strings.success, 'success');
        form.reset();
      } else {
        this.showNotification(result.data.message || aqualuxeAjax.strings.error, 'error');
      }
    } catch (error) {
      this.showNotification(aqualuxeAjax.strings.error, 'error');
    }
  }

  /**
   * Initialize lazy loading
   */
  initLazyLoading() {
    // Lazy loading is handled by lazysizes plugin
    // Additional custom lazy loading can be added here
  }

  /**
   * Initialize search toggle
   */
  initSearchToggle() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchForm = document.querySelector('.search-form');
    const searchClose = document.querySelector('.search-close');
    
    if (searchToggle && searchForm) {
      searchToggle.addEventListener('click', (e) => {
        e.preventDefault();
        searchForm.classList.add('open');
        const searchInput = searchForm.querySelector('input[type="search"]');
        if (searchInput) {
          searchInput.focus();
        }
      });
    }

    if (searchClose && searchForm) {
      searchClose.addEventListener('click', () => {
        searchForm.classList.remove('open');
      });
    }

    // Close search on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && searchForm && searchForm.classList.contains('open')) {
        searchForm.classList.remove('open');
      }
    });
  }

  /**
   * Initialize mobile menu
   */
  initMobileMenu() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileClose = document.querySelector('.mobile-menu-close');
    
    if (mobileToggle && mobileMenu) {
      mobileToggle.addEventListener('click', () => {
        mobileMenu.classList.add('open');
        document.body.classList.add('mobile-menu-open');
      });
    }

    if (mobileClose && mobileMenu) {
      mobileClose.addEventListener('click', () => {
        mobileMenu.classList.remove('open');
        document.body.classList.remove('mobile-menu-open');
      });
    }

    // Close on backdrop click
    if (mobileMenu) {
      mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
          mobileMenu.classList.remove('open');
          document.body.classList.remove('mobile-menu-open');
        }
      });
    }
  }

  /**
   * Initialize back to top
   */
  initBackToTop() {
    const backToTop = document.querySelector('.back-to-top');
    
    if (backToTop) {
      window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
          backToTop.classList.add('show');
        } else {
          backToTop.classList.remove('show');
        }
      });

      backToTop.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    }
  }

  /**
   * Initialize preloader
   */
  initPreloader() {
    const preloader = document.querySelector('.preloader');
    
    if (preloader) {
      window.addEventListener('load', () => {
        preloader.classList.add('fade-out');
        setTimeout(() => {
          preloader.remove();
        }, 500);
      });
    }
  }

  /**
   * Initialize animations
   */
  initAnimations() {
    // Counter animations
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
      ScrollTrigger.create({
        trigger: counter,
        start: "top 80%",
        onEnter: () => {
          const target = parseInt(counter.dataset.target);
          const duration = 2;
          
          gsap.to(counter, {
            duration: duration,
            innerHTML: target,
            ease: "power2.out",
            snap: { innerHTML: 1 },
            onUpdate: function() {
              counter.innerHTML = Math.ceil(counter.innerHTML);
            }
          });
        }
      });
    });

    // Stagger animations
    const staggerElements = document.querySelectorAll('.stagger-animation');
    
    staggerElements.forEach(container => {
      const items = container.children;
      
      gsap.fromTo(items,
        { opacity: 0, y: 30 },
        {
          opacity: 1,
          y: 0,
          duration: 0.6,
          stagger: 0.1,
          scrollTrigger: {
            trigger: container,
            start: "top 80%",
            toggleActions: "play none none reverse"
          }
        }
      );
    });
  }

  /**
   * Initialize accessibility features
   */
  initAccessibility() {
    // Skip link
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
      skipLink.addEventListener('click', (e) => {
        e.preventDefault();
        const target = document.querySelector(skipLink.getAttribute('href'));
        if (target) {
          target.focus();
          target.scrollIntoView();
        }
      });
    }

    // Focus management for navigation
    this.initKeyboardNavigation();
  }

  /**
   * Initialize keyboard navigation
   */
  initKeyboardNavigation() {
    const menuItems = document.querySelectorAll('.menu-item > a');
    
    menuItems.forEach((item, index) => {
      item.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
          e.preventDefault();
          const nextItem = menuItems[index + 1];
          if (nextItem) {
            nextItem.focus();
          }
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
          e.preventDefault();
          const prevItem = menuItems[index - 1];
          if (prevItem) {
            prevItem.focus();
          }
        }
      });
    });
  }

  /**
   * Initialize dark mode
   */
  initDarkMode() {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    
    if (darkModeToggle) {
      darkModeToggle.addEventListener('click', () => {
        this.toggleDarkMode();
      });
    }

    // Apply saved dark mode preference
    const savedMode = localStorage.getItem('aqualuxe-dark-mode');
    if (savedMode === 'enabled') {
      document.documentElement.classList.add('dark-mode');
    }
  }

  /**
   * Toggle dark mode
   */
  toggleDarkMode() {
    const isDarkMode = document.documentElement.classList.toggle('dark-mode');
    
    localStorage.setItem('aqualuxe-dark-mode', isDarkMode ? 'enabled' : 'disabled');
    
    // Update toggle button
    const toggles = document.querySelectorAll('.dark-mode-toggle');
    toggles.forEach(toggle => {
      toggle.setAttribute('aria-pressed', isDarkMode);
    });
  }

  /**
   * Initialize WooCommerce features
   */
  initWooCommerce() {
    // This will be handled by the WooCommerce module
    // Import WooCommerce specific functionality here if needed
  }

  /**
   * Show notification
   */
  showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification--${type}`;
    notification.innerHTML = `
      <div class="notification__content">
        <span class="notification__message">${message}</span>
        <button class="notification__close" aria-label="${aqualuxeAjax.strings.close}">×</button>
      </div>
    `;

    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
      notification.classList.add('show');
    }, 100);

    // Auto-hide after 5 seconds
    setTimeout(() => {
      this.hideNotification(notification);
    }, 5000);

    // Close button
    const closeButton = notification.querySelector('.notification__close');
    closeButton.addEventListener('click', () => {
      this.hideNotification(notification);
    });
  }

  /**
   * Hide notification
   */
  hideNotification(notification) {
    notification.classList.remove('show');
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 300);
  }

  /**
   * Emit custom event
   */
  emitEvent(eventName, detail = {}) {
    const event = new CustomEvent(eventName, {
      detail: detail,
      bubbles: true,
      cancelable: true
    });
    document.dispatchEvent(event);
  }

  /**
   * Utility method to debounce function calls
   */
  debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  /**
   * Utility method to throttle function calls
   */
  throttle(func, limit) {
    let inThrottle;
    return function() {
      const args = arguments;
      const context = this;
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  }
}

// Initialize theme when DOM is ready
const aqualuxeTheme = new AquaLuxeTheme();

// Make theme instance available globally for debugging
window.AquaLuxeTheme = aqualuxeTheme;
