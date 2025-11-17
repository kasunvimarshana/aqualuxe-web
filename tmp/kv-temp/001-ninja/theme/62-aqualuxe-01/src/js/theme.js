/**
 * AquaLuxe Theme JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Import dependencies
import './components/navigation';
import './components/accessibility';
import './components/animations';
import './components/responsive';

/**
 * AquaLuxe Theme Class
 */
class AquaLuxeTheme {
  /**
   * Constructor
   */
  constructor() {
    this.initializeComponents();
    this.bindEvents();
  }

  /**
   * Initialize components
   */
  initializeComponents() {
    // Initialize responsive navigation
    this.navigation = new AquaLuxeNavigation();
    
    // Initialize accessibility features
    this.accessibility = new AquaLuxeAccessibility();
    
    // Initialize animations
    this.animations = new AquaLuxeAnimations();
    
    // Initialize responsive features
    this.responsive = new AquaLuxeResponsive();
  }

  /**
   * Bind events
   */
  bindEvents() {
    // Document ready
    document.addEventListener('DOMContentLoaded', () => this.onDocumentReady());
    
    // Window load
    window.addEventListener('load', () => this.onWindowLoad());
    
    // Window resize
    window.addEventListener('resize', () => this.onWindowResize());
    
    // Window scroll
    window.addEventListener('scroll', () => this.onWindowScroll());
  }

  /**
   * Document ready event handler
   */
  onDocumentReady() {
    // Initialize components that require DOM to be ready
    this.navigation.init();
    this.accessibility.init();
  }

  /**
   * Window load event handler
   */
  onWindowLoad() {
    // Initialize components that require all resources to be loaded
    this.animations.init();
    
    // Remove loading state
    document.body.classList.remove('is-loading');
    document.body.classList.add('is-loaded');
  }

  /**
   * Window resize event handler
   */
  onWindowResize() {
    // Handle responsive adjustments
    this.responsive.handleResize();
    
    // Update navigation
    this.navigation.handleResize();
  }

  /**
   * Window scroll event handler
   */
  onWindowScroll() {
    // Handle scroll-based animations
    this.animations.handleScroll();
    
    // Handle sticky header
    this.handleStickyHeader();
  }

  /**
   * Handle sticky header
   */
  handleStickyHeader() {
    const header = document.querySelector('.site-header');
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (!header) {
      return;
    }
    
    // Add sticky class when scrolled
    if (scrollTop > 100) {
      header.classList.add('is-sticky');
    } else {
      header.classList.remove('is-sticky');
    }
  }
}

// Initialize theme
const aqualuxeTheme = new AquaLuxeTheme();

// Export for potential external use
export default aqualuxeTheme;