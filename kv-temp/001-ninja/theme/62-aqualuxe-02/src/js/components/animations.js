/**
 * AquaLuxe Animations Component
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * AquaLuxe Animations Class
 */
class AquaLuxeAnimations {
  /**
   * Constructor
   */
  constructor() {
    this.isInitialized = false;
    this.animatedElements = [];
    this.scrollAnimations = [];
    this.intersectionObserver = null;
    this.hasReducedMotion = false;
  }

  /**
   * Initialize animations
   */
  init() {
    // Skip if already initialized
    if (this.isInitialized) {
      return;
    }
    
    // Check for reduced motion preference
    this.checkReducedMotion();
    
    // Skip animations if reduced motion is preferred
    if (this.hasReducedMotion) {
      this.disableAnimations();
      return;
    }
    
    // Get animated elements
    this.getAnimatedElements();
    
    // Setup intersection observer
    this.setupIntersectionObserver();
    
    // Setup scroll animations
    this.setupScrollAnimations();
    
    // Set initialized flag
    this.isInitialized = true;
  }

  /**
   * Check for reduced motion preference
   */
  checkReducedMotion() {
    // Check for prefers-reduced-motion media query
    const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    this.hasReducedMotion = mediaQuery.matches;
    
    // Listen for changes
    mediaQuery.addEventListener('change', () => {
      this.hasReducedMotion = mediaQuery.matches;
      
      // Disable or enable animations based on preference
      if (this.hasReducedMotion) {
        this.disableAnimations();
      } else {
        this.enableAnimations();
      }
    });
  }

  /**
   * Disable animations
   */
  disableAnimations() {
    // Add class to body
    document.body.classList.add('reduced-motion');
    
    // Remove animation classes from elements
    this.animatedElements.forEach((element) => {
      element.classList.remove('animate', 'animate-in', 'animate-out');
      element.classList.add('no-animation');
    });
    
    // Disconnect intersection observer
    if (this.intersectionObserver) {
      this.intersectionObserver.disconnect();
    }
  }

  /**
   * Enable animations
   */
  enableAnimations() {
    // Remove class from body
    document.body.classList.remove('reduced-motion');
    
    // Re-initialize animations
    this.getAnimatedElements();
    this.setupIntersectionObserver();
  }

  /**
   * Get animated elements
   */
  getAnimatedElements() {
    // Get all elements with animation classes
    this.animatedElements = Array.from(document.querySelectorAll(
      '.fade-in, .fade-up, .fade-down, .fade-left, .fade-right, ' +
      '.scale-in, .scale-up, .scale-down, ' +
      '.slide-in, .slide-up, .slide-down, .slide-left, .slide-right, ' +
      '.animate, .animate-on-scroll, .animate-on-load, .animate-on-hover'
    ));
    
    // Add animation-ready class
    this.animatedElements.forEach((element) => {
      element.classList.add('animation-ready');
    });
  }

  /**
   * Setup intersection observer
   */
  setupIntersectionObserver() {
    // Skip if no animated elements
    if (!this.animatedElements.length) {
      return;
    }
    
    // Create intersection observer
    this.intersectionObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        // Skip if not intersecting
        if (!entry.isIntersecting) {
          return;
        }
        
        // Add animation class
        entry.target.classList.add('animate-in');
        
        // Remove from observer if not repeating
        if (!entry.target.classList.contains('animate-repeat')) {
          this.intersectionObserver.unobserve(entry.target);
        }
      });
    }, {
      root: null,
      rootMargin: '0px',
      threshold: 0.1,
    });
    
    // Observe elements
    this.animatedElements.forEach((element) => {
      // Skip elements that animate on hover
      if (element.classList.contains('animate-on-hover')) {
        return;
      }
      
      // Skip elements that animate on load
      if (element.classList.contains('animate-on-load')) {
        setTimeout(() => {
          element.classList.add('animate-in');
        }, 100);
        return;
      }
      
      // Observe element
      this.intersectionObserver.observe(element);
    });
  }

  /**
   * Setup scroll animations
   */
  setupScrollAnimations() {
    // Get all elements with scroll animation classes
    this.scrollAnimations = Array.from(document.querySelectorAll(
      '.parallax, .sticky-element, .progress-bar, ' +
      '.scroll-fade, .scroll-scale, .scroll-rotate, ' +
      '.scroll-progress'
    ));
    
    // Skip if no scroll animations
    if (!this.scrollAnimations.length) {
      return;
    }
    
    // Initialize scroll animations
    this.scrollAnimations.forEach((element) => {
      // Add scroll-animation class
      element.classList.add('scroll-animation');
      
      // Store initial position
      const rect = element.getBoundingClientRect();
      element.dataset.initialTop = rect.top + window.pageYOffset;
      element.dataset.initialHeight = rect.height;
    });
  }

  /**
   * Handle scroll
   */
  handleScroll() {
    // Skip if reduced motion is preferred
    if (this.hasReducedMotion) {
      return;
    }
    
    // Skip if no scroll animations
    if (!this.scrollAnimations.length) {
      return;
    }
    
    // Get scroll position
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const windowHeight = window.innerHeight;
    
    // Update scroll animations
    this.scrollAnimations.forEach((element) => {
      // Get element position
      const rect = element.getBoundingClientRect();
      const elementTop = rect.top + scrollTop;
      const elementHeight = rect.height;
      
      // Calculate scroll progress
      const scrollProgress = Math.max(0, Math.min(1, (scrollTop + windowHeight - elementTop) / (windowHeight + elementHeight)));
      
      // Update element based on animation type
      if (element.classList.contains('parallax')) {
        this.updateParallax(element, scrollProgress);
      } else if (element.classList.contains('sticky-element')) {
        this.updateStickyElement(element, scrollTop);
      } else if (element.classList.contains('progress-bar')) {
        this.updateProgressBar(element, scrollProgress);
      } else if (element.classList.contains('scroll-fade')) {
        this.updateScrollFade(element, scrollProgress);
      } else if (element.classList.contains('scroll-scale')) {
        this.updateScrollScale(element, scrollProgress);
      } else if (element.classList.contains('scroll-rotate')) {
        this.updateScrollRotate(element, scrollProgress);
      } else if (element.classList.contains('scroll-progress')) {
        this.updateScrollProgress(element, scrollProgress);
      }
    });
  }

  /**
   * Update parallax element
   *
   * @param {HTMLElement} element Element to update
   * @param {number} scrollProgress Scroll progress (0-1)
   */
  updateParallax(element, scrollProgress) {
    // Get parallax speed
    const speed = parseFloat(element.dataset.parallaxSpeed || 0.5);
    
    // Calculate parallax offset
    const offset = (scrollProgress - 0.5) * speed * 100;
    
    // Apply transform
    element.style.transform = `translateY(${offset}px)`;
  }

  /**
   * Update sticky element
   *
   * @param {HTMLElement} element Element to update
   * @param {number} scrollTop Scroll position
   */
  updateStickyElement(element, scrollTop) {
    // Get sticky offset
    const offset = parseInt(element.dataset.stickyOffset || 0, 10);
    
    // Get sticky until element
    const stickyUntilSelector = element.dataset.stickyUntil;
    let stickyUntilPosition = Number.MAX_SAFE_INTEGER;
    
    if (stickyUntilSelector) {
      const stickyUntilElement = document.querySelector(stickyUntilSelector);
      if (stickyUntilElement) {
        stickyUntilPosition = stickyUntilElement.getBoundingClientRect().top + scrollTop;
      }
    }
    
    // Get initial position
    const initialTop = parseInt(element.dataset.initialTop, 10);
    
    // Check if should be sticky
    if (scrollTop >= initialTop - offset && scrollTop < stickyUntilPosition - offset) {
      // Add sticky class
      element.classList.add('is-sticky');
      element.style.position = 'fixed';
      element.style.top = `${offset}px`;
    } else if (scrollTop >= stickyUntilPosition - offset) {
      // Add sticky-end class
      element.classList.add('is-sticky-end');
      element.classList.remove('is-sticky');
      element.style.position = 'absolute';
      element.style.top = `${stickyUntilPosition - initialTop}px`;
    } else {
      // Remove sticky classes
      element.classList.remove('is-sticky', 'is-sticky-end');
      element.style.position = '';
      element.style.top = '';
    }
  }

  /**
   * Update progress bar
   *
   * @param {HTMLElement} element Element to update
   * @param {number} scrollProgress Scroll progress (0-1)
   */
  updateProgressBar(element, scrollProgress) {
    // Get progress bar
    const progressBar = element.querySelector('.progress-bar-fill') || element;
    
    // Update width
    progressBar.style.width = `${scrollProgress * 100}%`;
  }

  /**
   * Update scroll fade
   *
   * @param {HTMLElement} element Element to update
   * @param {number} scrollProgress Scroll progress (0-1)
   */
  updateScrollFade(element, scrollProgress) {
    // Get fade direction
    const direction = element.dataset.fadeDirection || 'in';
    
    // Calculate opacity
    let opacity = 0;
    
    if (direction === 'in') {
      opacity = Math.min(1, scrollProgress * 2);
    } else if (direction === 'out') {
      opacity = Math.max(0, 1 - scrollProgress * 2);
    } else if (direction === 'in-out') {
      opacity = scrollProgress < 0.5
        ? Math.min(1, scrollProgress * 4)
        : Math.max(0, 1 - (scrollProgress - 0.5) * 4);
    }
    
    // Apply opacity
    element.style.opacity = opacity;
  }

  /**
   * Update scroll scale
   *
   * @param {HTMLElement} element Element to update
   * @param {number} scrollProgress Scroll progress (0-1)
   */
  updateScrollScale(element, scrollProgress) {
    // Get scale direction
    const direction = element.dataset.scaleDirection || 'up';
    
    // Get scale range
    const minScale = parseFloat(element.dataset.minScale || 0.5);
    const maxScale = parseFloat(element.dataset.maxScale || 1.5);
    
    // Calculate scale
    let scale = 1;
    
    if (direction === 'up') {
      scale = minScale + (maxScale - minScale) * scrollProgress;
    } else if (direction === 'down') {
      scale = maxScale - (maxScale - minScale) * scrollProgress;
    } else if (direction === 'in-out') {
      scale = scrollProgress < 0.5
        ? minScale + (maxScale - minScale) * (scrollProgress * 2)
        : maxScale - (maxScale - minScale) * ((scrollProgress - 0.5) * 2);
    }
    
    // Apply transform
    element.style.transform = `scale(${scale})`;
  }

  /**
   * Update scroll rotate
   *
   * @param {HTMLElement} element Element to update
   * @param {number} scrollProgress Scroll progress (0-1)
   */
  updateScrollRotate(element, scrollProgress) {
    // Get rotation range
    const minRotation = parseFloat(element.dataset.minRotation || 0);
    const maxRotation = parseFloat(element.dataset.maxRotation || 360);
    
    // Calculate rotation
    const rotation = minRotation + (maxRotation - minRotation) * scrollProgress;
    
    // Apply transform
    element.style.transform = `rotate(${rotation}deg)`;
  }

  /**
   * Update scroll progress
   *
   * @param {HTMLElement} element Element to update
   * @param {number} scrollProgress Scroll progress (0-1)
   */
  updateScrollProgress(element, scrollProgress) {
    // Update progress attribute
    element.style.setProperty('--scroll-progress', scrollProgress);
    
    // Update progress text if exists
    const progressText = element.querySelector('.progress-text');
    if (progressText) {
      progressText.textContent = `${Math.round(scrollProgress * 100)}%`;
    }
  }
}

// Export for use in theme.js
export default AquaLuxeAnimations;