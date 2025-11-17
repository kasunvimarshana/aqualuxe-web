/**
 * AquaLuxe Main Application Script
 *
 * Core functionality and performance optimizations
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Import dependencies
import Alpine from 'alpinejs';
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import 'lazysizes/plugins/native-loading/ls.native-loading';

// Import utilities
import { Utils } from './modules/utils';
import { AnimationUtils } from './modules/animations';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Main application class
class AquaLuxeApp {
  constructor() {
    this.utils = Utils;
    this.animations = AnimationUtils;
    this.init();
  }

  init() {
    this.setupDOMContentLoaded();
    this.registerServiceWorker();
    this.setupLazyLoading();
    this.setupIntersectionObserver();
    this.setupPerformanceOptimizations();
  }

  setupDOMContentLoaded() {
    document.addEventListener('DOMContentLoaded', () => {
      // AquaLuxe theme initialization complete
      document.body.classList.add('aqualuxe-loaded');

      // Initialize components
      this.initializeComponents();

      // Setup accessibility features
      this.setupAccessibility();

      // Setup dark mode toggle
      this.setupDarkMode();

      // Setup search functionality
      this.setupSearch();
    });
  }

  /**
   * Register service worker for PWA functionality
   */
  registerServiceWorker() {
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker
          .register(
            '/wp-content/themes/aqualuxe/assets/dist/js/service-worker.js'
          )
          .then(registration => {
            // Service Worker registered successfully

            // Check for updates
            registration.addEventListener('updatefound', () => {
              const newWorker = registration.installing;
              if (newWorker) {
                newWorker.addEventListener('statechange', () => {
                  if (
                    newWorker.state === 'installed' &&
                    navigator.serviceWorker.controller
                  ) {
                    // New content is available, notify user
                    this.showUpdateNotification();
                  }
                });
              }
            });
          })
          .catch(() => {
            // Service Worker registration failed - silently handle in production
          });
      });
    }
  }

  /**
   * Setup lazy loading for images and iframes
   */
  setupLazyLoading() {
    // Configure lazysizes
    window.lazySizesConfig = window.lazySizesConfig || {};
    window.lazySizesConfig.loadMode = 2;
    window.lazySizesConfig.loadHidden = false;
    window.lazySizesConfig.expand = 200;
    window.lazySizesConfig.expFactor = 1.5;

    // Add fade-in animation for loaded images
    document.addEventListener('lazyloaded', function (e) {
      e.target.classList.add('fade-in');
    });
  }

  /**
   * Setup intersection observer for animations
   */
  setupIntersectionObserver() {
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver(
        entries => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('in-view');

              // Trigger animations
              if (entry.target.dataset.animation) {
                this.animations.fadeIn(entry.target);
              }

              observer.unobserve(entry.target);
            }
          });
        },
        {
          rootMargin: '50px 0px',
          threshold: 0.1,
        }
      );

      // Observe elements with animation data attributes
      document.querySelectorAll('[data-animation]').forEach(el => {
        observer.observe(el);
      });
    }
  }

  /**
   * Performance optimizations
   */
  setupPerformanceOptimizations() {
    // Preload critical pages on hover
    this.setupLinkPreloading();

    // Optimize images
    this.optimizeImages();

    // Setup critical resource hints
    this.addResourceHints();

    // Monitor performance
    this.monitorPerformance();
  }

  /**
   * Preload links on hover for instant navigation
   */
  setupLinkPreloading() {
    const links = document.querySelectorAll('a[href^="/"]');
    const preloadedUrls = new Set();

    links.forEach(link => {
      link.addEventListener(
        'mouseenter',
        this.utils.debounce(() => {
          const href = link.getAttribute('href');
          if (href && !preloadedUrls.has(href)) {
            const linkEl = document.createElement('link');
            linkEl.rel = 'prefetch';
            linkEl.href = href;
            document.head.appendChild(linkEl);
            preloadedUrls.add(href);
          }
        }, 100)
      );
    });
  }

  /**
   * Optimize images for performance
   */
  optimizeImages() {
    // Add loading="lazy" to images without it
    document.querySelectorAll('img:not([loading])').forEach(img => {
      img.setAttribute('loading', 'lazy');
    });

    // Add proper alt text to images without it
    document.querySelectorAll('img:not([alt])').forEach(img => {
      img.setAttribute('alt', '');
    });
  }

  /**
   * Add resource hints for performance
   */
  addResourceHints() {
    // DNS prefetch for external domains
    const externalDomains = ['fonts.googleapis.com', 'fonts.gstatic.com'];
    externalDomains.forEach(domain => {
      if (!document.querySelector(`link[href*="${domain}"]`)) {
        const link = document.createElement('link');
        link.rel = 'dns-prefetch';
        link.href = `//${domain}`;
        document.head.appendChild(link);
      }
    });
  }

  /**
   * Monitor performance metrics
   */
  monitorPerformance() {
    if ('PerformanceObserver' in window) {
      // Monitor Largest Contentful Paint
      const lcpObserver = new PerformanceObserver(() => {
        // Track LCP performance metric
      });

      try {
        lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });
      } catch (e) {
        // Fallback for older browsers
      }

      // Monitor First Input Delay
      const fidObserver = new PerformanceObserver(list => {
        const firstInput = list.getEntries()[0];
        if (firstInput) {
          // Track FID performance metric
        }
      });

      try {
        fidObserver.observe({ entryTypes: ['first-input'] });
      } catch (e) {
        // Fallback for older browsers
      }
    }
  }

  /**
   * Initialize theme components
   */
  initializeComponents() {
    // Initialize navigation
    this.initNavigation();

    // Initialize modals
    this.initModals();

    // Initialize forms
    this.initForms();
  }

  /**
   * Initialize navigation functionality
   */
  initNavigation() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (mobileToggle && mobileMenu) {
      mobileToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        mobileToggle.classList.toggle('active');
        document.body.classList.toggle('menu-open');
      });
    }

    // Close mobile menu on escape key
    document.addEventListener('keydown', e => {
      if (
        e.key === 'Escape' &&
        mobileMenu &&
        mobileMenu.classList.contains('active')
      ) {
        mobileMenu.classList.remove('active');
        mobileToggle.classList.remove('active');
        document.body.classList.remove('menu-open');
      }
    });
  }

  /**
   * Initialize modal functionality
   */
  initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    const modals = document.querySelectorAll('.modal');

    modalTriggers.forEach(trigger => {
      trigger.addEventListener('click', e => {
        e.preventDefault();
        const targetId = trigger.dataset.modalTarget;
        const modal = document.getElementById(targetId);
        if (modal) {
          this.openModal(modal);
        }
      });
    });

    modals.forEach(modal => {
      const closeButton = modal.querySelector('.modal-close');
      if (closeButton) {
        closeButton.addEventListener('click', () => this.closeModal(modal));
      }

      // Close on backdrop click
      modal.addEventListener('click', e => {
        if (e.target === modal) {
          this.closeModal(modal);
        }
      });
    });
  }

  /**
   * Open modal
   */
  openModal(modal) {
    modal.classList.add('active');
    document.body.classList.add('modal-open');

    // Focus management
    const focusableElements = modal.querySelectorAll(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    if (focusableElements.length > 0) {
      focusableElements[0].focus();
    }
  }

  /**
   * Close modal
   */
  closeModal(modal) {
    modal.classList.remove('active');
    document.body.classList.remove('modal-open');
  }

  /**
   * Initialize form functionality
   */
  initForms() {
    const forms = document.querySelectorAll('.aqualuxe-form');

    forms.forEach(form => {
      form.addEventListener('submit', e => {
        e.preventDefault();
        this.handleFormSubmission(form);
      });
    });
  }

  /**
   * Handle form submission with validation
   */
  handleFormSubmission(form) {
    const submitButton = form.querySelector('[type="submit"]');
    const originalText = submitButton.textContent;

    // Show loading state
    submitButton.disabled = true;
    submitButton.textContent = 'Sending...';
    form.classList.add('loading');

    // Simulate form submission (replace with actual AJAX call)
    setTimeout(() => {
      // Reset form state
      submitButton.disabled = false;
      submitButton.textContent = originalText;
      form.classList.remove('loading');

      // Show success message
      this.showNotification('Form submitted successfully!', 'success');
    }, 2000);
  }

  /**
   * Setup accessibility features
   */
  setupAccessibility() {
    // Skip link functionality
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
      skipLink.addEventListener('click', e => {
        e.preventDefault();
        const target = document.querySelector(skipLink.getAttribute('href'));
        if (target) {
          target.focus();
          target.scrollIntoView();
        }
      });
    }

    // Keyboard navigation for dropdowns
    this.setupKeyboardNavigation();
  }

  /**
   * Setup keyboard navigation
   */
  setupKeyboardNavigation() {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
      const trigger = dropdown.querySelector('.dropdown-trigger');
      const menu = dropdown.querySelector('.dropdown-menu');

      if (trigger && menu) {
        trigger.addEventListener('keydown', e => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            menu.classList.toggle('active');
          }
        });
      }
    });
  }

  /**
   * Setup dark mode functionality
   */
  setupDarkMode() {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');

    if (darkModeToggle) {
      // Check for saved preference or system preference
      const savedTheme = localStorage.getItem('theme');
      const systemPrefersDark = window.matchMedia(
        '(prefers-color-scheme: dark)'
      ).matches;

      if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
        document.documentElement.classList.add('dark');
      }

      darkModeToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        const isDark = document.documentElement.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
      });
    }
  }

  /**
   * Setup search functionality
   */
  setupSearch() {
    const searchForms = document.querySelectorAll('.search-form');

    searchForms.forEach(form => {
      const input = form.querySelector('input[type="search"]');
      if (input) {
        input.addEventListener(
          'input',
          this.utils.debounce(e => {
            if (e.target.value.length > 2) {
              this.performSearch(e.target.value);
            }
          }, 300)
        );
      }
    });
  }

  /**
   * Perform search with live results
   */
  performSearch() {
    // Implement live search functionality
    // This would typically make an AJAX request to WordPress REST API
    // or a custom search endpoint
  }

  /**
   * Show update notification for service worker
   */
  showUpdateNotification() {
    const notification = document.createElement('div');
    notification.className = 'update-notification';
    notification.innerHTML = `
      <div class="notification-content">
        <span>A new version is available!</span>
        <button class="notification-button" onclick="location.reload()">Refresh</button>
        <button class="notification-close">&times;</button>
      </div>
    `;

    document.body.appendChild(notification);

    notification
      .querySelector('.notification-close')
      .addEventListener('click', () => {
        notification.remove();
      });
  }

  /**
   * Show notification message
   */
  showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.classList.add('show');
    }, 100);

    setTimeout(() => {
      notification.classList.remove('show');
      setTimeout(() => notification.remove(), 300);
    }, 5000);
  }
}

// Initialize the application
const app = new AquaLuxeApp();

// Make app instance globally available
window.AquaLuxeApp = app;
