/**
 * Scroll Effects Module
 * 
 * Handles various scroll-based effects for the theme.
 */

const ScrollEffects = {
  /**
   * Initialize the scroll effects
   */
  init() {
    this.cacheDOM();
    this.bindEvents();
    this.setupScrollReveal();
    this.setupParallax();
    this.setupSmoothScroll();
  },

  /**
   * Cache DOM elements
   */
  cacheDOM() {
    this.scrollRevealElements = document.querySelectorAll('.scroll-reveal');
    this.parallaxElements = document.querySelectorAll('.parallax');
    this.smoothScrollLinks = document.querySelectorAll('a[href^="#"]:not([href="#"])');
    this.backToTopButton = document.querySelector('.back-to-top');
  },

  /**
   * Bind events
   */
  bindEvents() {
    // Handle scroll events
    document.addEventListener('aqualuxe:scroll', event => {
      this.handleScrollEffects(event.detail.position);
    });

    // Back to top button
    if (this.backToTopButton) {
      this.backToTopButton.addEventListener('click', event => {
        event.preventDefault();
        this.scrollToTop();
      });
    }
  },

  /**
   * Handle scroll effects
   * 
   * @param {number} scrollPosition - Current scroll position
   */
  handleScrollEffects(scrollPosition) {
    // Show/hide back to top button
    if (this.backToTopButton) {
      if (scrollPosition > 300) {
        this.backToTopButton.classList.add('visible');
      } else {
        this.backToTopButton.classList.remove('visible');
      }
    }

    // Handle scroll reveal elements
    this.handleScrollReveal();

    // Handle parallax elements
    this.handleParallax(scrollPosition);
  },

  /**
   * Setup scroll reveal animation
   */
  setupScrollReveal() {
    // Add initial state to elements
    this.scrollRevealElements.forEach(element => {
      // Get animation properties from data attributes
      const delay = element.dataset.delay || 0;
      const duration = element.dataset.duration || '0.6s';
      const offset = element.dataset.offset || '100px';
      const easing = element.dataset.easing || 'cubic-bezier(0.5, 0, 0, 1)';

      // Set initial styles
      element.style.opacity = '0';
      element.style.transform = `translateY(${offset})`;
      element.style.transition = `opacity ${duration} ${easing} ${delay}, transform ${duration} ${easing} ${delay}`;
    });

    // Trigger initial check
    this.handleScrollReveal();
  },

  /**
   * Handle scroll reveal animation
   */
  handleScrollReveal() {
    this.scrollRevealElements.forEach(element => {
      if (this.isElementInViewport(element) && !element.classList.contains('revealed')) {
        // Add revealed class
        element.classList.add('revealed');
        
        // Apply revealed styles
        element.style.opacity = '1';
        element.style.transform = 'translateY(0)';
      }
    });
  },

  /**
   * Setup parallax effect
   */
  setupParallax() {
    // Add initial state to elements
    this.parallaxElements.forEach(element => {
      // Get parallax properties from data attributes
      const speed = parseFloat(element.dataset.speed || 0.5);
      const direction = element.dataset.direction || 'up';
      
      // Store properties on element
      element.parallaxSpeed = speed;
      element.parallaxDirection = direction;
    });
  },

  /**
   * Handle parallax effect
   * 
   * @param {number} scrollPosition - Current scroll position
   */
  handleParallax(scrollPosition) {
    this.parallaxElements.forEach(element => {
      if (this.isElementInViewport(element, 1.2)) {
        const elementTop = element.getBoundingClientRect().top + scrollPosition;
        const elementHeight = element.offsetHeight;
        const viewportHeight = window.innerHeight;
        
        // Calculate how far the element is from the top of the viewport
        const distanceFromTop = elementTop - scrollPosition;
        
        // Calculate the percentage of the element that is visible
        const visiblePercentage = 1 - (distanceFromTop / viewportHeight);
        
        // Calculate the parallax offset
        let offset = (visiblePercentage * element.parallaxSpeed * 100);
        
        // Apply direction
        if (element.parallaxDirection === 'down') {
          offset = -offset;
        }
        
        // Apply transform
        element.style.transform = `translateY(${offset}px)`;
      }
    });
  },

  /**
   * Setup smooth scroll for anchor links
   */
  setupSmoothScroll() {
    this.smoothScrollLinks.forEach(link => {
      link.addEventListener('click', event => {
        event.preventDefault();
        
        const targetId = link.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
          this.scrollToElement(targetElement);
        }
      });
    });
  },

  /**
   * Scroll to element
   * 
   * @param {HTMLElement} element - Target element to scroll to
   */
  scrollToElement(element) {
    // Get element position
    const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
    
    // Get header height for offset
    const header = document.querySelector('.site-header');
    const headerHeight = header ? header.offsetHeight : 0;
    
    // Calculate target position
    const targetPosition = elementPosition - headerHeight - 20;
    
    // Scroll to target
    window.scrollTo({
      top: targetPosition,
      behavior: 'smooth'
    });
  },

  /**
   * Scroll to top
   */
  scrollToTop() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  },

  /**
   * Check if element is in viewport
   * 
   * @param {HTMLElement} element - Element to check
   * @param {number} threshold - Threshold multiplier (1 = 100% of element must be visible)
   * @returns {boolean} - Whether element is in viewport
   */
  isElementInViewport(element, threshold = 0.2) {
    const rect = element.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    
    // Calculate the percentage of the element that needs to be in view
    const thresholdPixels = rect.height * threshold;
    
    return (
      rect.top + thresholdPixels <= windowHeight &&
      rect.bottom - thresholdPixels >= 0
    );
  }
};

export default ScrollEffects;