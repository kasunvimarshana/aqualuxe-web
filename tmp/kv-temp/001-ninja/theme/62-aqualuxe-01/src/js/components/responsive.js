/**
 * AquaLuxe Responsive Component
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * AquaLuxe Responsive Class
 */
class AquaLuxeResponsive {
  /**
   * Constructor
   */
  constructor() {
    this.isInitialized = false;
    this.breakpoints = {
      xs: 0,
      sm: 576,
      md: 768,
      lg: 992,
      xl: 1200,
      xxl: 1400,
    };
    this.currentBreakpoint = '';
    this.isDesktop = false;
    this.isTablet = false;
    this.isMobile = false;
    this.isPortrait = false;
    this.isLandscape = false;
  }

  /**
   * Initialize responsive features
   */
  init() {
    // Skip if already initialized
    if (this.isInitialized) {
      return;
    }
    
    // Set initial state
    this.updateState();
    
    // Add resize event listener
    window.addEventListener('resize', () => this.handleResize());
    
    // Add orientation change event listener
    window.addEventListener('orientationchange', () => this.handleOrientationChange());
    
    // Set initialized flag
    this.isInitialized = true;
  }

  /**
   * Update state
   */
  updateState() {
    // Get window width
    const windowWidth = window.innerWidth;
    
    // Determine current breakpoint
    this.currentBreakpoint = this.getBreakpoint(windowWidth);
    
    // Set device flags
    this.isDesktop = windowWidth >= this.breakpoints.lg;
    this.isTablet = windowWidth >= this.breakpoints.md && windowWidth < this.breakpoints.lg;
    this.isMobile = windowWidth < this.breakpoints.md;
    
    // Set orientation flags
    this.isPortrait = window.innerHeight > window.innerWidth;
    this.isLandscape = window.innerWidth > window.innerHeight;
    
    // Update body classes
    this.updateBodyClasses();
  }

  /**
   * Get breakpoint name for width
   *
   * @param {number} width Window width
   * @returns {string} Breakpoint name
   */
  getBreakpoint(width) {
    if (width >= this.breakpoints.xxl) {
      return 'xxl';
    } else if (width >= this.breakpoints.xl) {
      return 'xl';
    } else if (width >= this.breakpoints.lg) {
      return 'lg';
    } else if (width >= this.breakpoints.md) {
      return 'md';
    } else if (width >= this.breakpoints.sm) {
      return 'sm';
    } else {
      return 'xs';
    }
  }

  /**
   * Update body classes
   */
  updateBodyClasses() {
    // Remove all breakpoint classes
    document.body.classList.remove(
      'breakpoint-xs',
      'breakpoint-sm',
      'breakpoint-md',
      'breakpoint-lg',
      'breakpoint-xl',
      'breakpoint-xxl'
    );
    
    // Add current breakpoint class
    document.body.classList.add(`breakpoint-${this.currentBreakpoint}`);
    
    // Remove device classes
    document.body.classList.remove('is-desktop', 'is-tablet', 'is-mobile');
    
    // Add current device class
    if (this.isDesktop) {
      document.body.classList.add('is-desktop');
    } else if (this.isTablet) {
      document.body.classList.add('is-tablet');
    } else if (this.isMobile) {
      document.body.classList.add('is-mobile');
    }
    
    // Remove orientation classes
    document.body.classList.remove('is-portrait', 'is-landscape');
    
    // Add current orientation class
    if (this.isPortrait) {
      document.body.classList.add('is-portrait');
    } else if (this.isLandscape) {
      document.body.classList.add('is-landscape');
    }
  }

  /**
   * Handle resize
   */
  handleResize() {
    // Debounce resize event
    clearTimeout(this.resizeTimer);
    this.resizeTimer = setTimeout(() => {
      this.updateState();
      this.dispatchResizeEvent();
    }, 250);
  }

  /**
   * Handle orientation change
   */
  handleOrientationChange() {
    // Update state
    this.updateState();
    
    // Dispatch orientation change event
    this.dispatchOrientationChangeEvent();
  }

  /**
   * Dispatch resize event
   */
  dispatchResizeEvent() {
    // Create custom event
    const event = new CustomEvent('aqualuxe:resize', {
      detail: {
        breakpoint: this.currentBreakpoint,
        isDesktop: this.isDesktop,
        isTablet: this.isTablet,
        isMobile: this.isMobile,
        width: window.innerWidth,
        height: window.innerHeight,
      },
    });
    
    // Dispatch event
    document.dispatchEvent(event);
  }

  /**
   * Dispatch orientation change event
   */
  dispatchOrientationChangeEvent() {
    // Create custom event
    const event = new CustomEvent('aqualuxe:orientationchange', {
      detail: {
        isPortrait: this.isPortrait,
        isLandscape: this.isLandscape,
        width: window.innerWidth,
        height: window.innerHeight,
      },
    });
    
    // Dispatch event
    document.dispatchEvent(event);
  }

  /**
   * Check if current breakpoint matches
   *
   * @param {string} breakpoint Breakpoint name
   * @returns {boolean} True if current breakpoint matches
   */
  isBreakpoint(breakpoint) {
    return this.currentBreakpoint === breakpoint;
  }

  /**
   * Check if current breakpoint is at least
   *
   * @param {string} breakpoint Breakpoint name
   * @returns {boolean} True if current breakpoint is at least the given breakpoint
   */
  isAtLeast(breakpoint) {
    const breakpoints = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];
    const currentIndex = breakpoints.indexOf(this.currentBreakpoint);
    const targetIndex = breakpoints.indexOf(breakpoint);
    
    return currentIndex >= targetIndex;
  }

  /**
   * Check if current breakpoint is at most
   *
   * @param {string} breakpoint Breakpoint name
   * @returns {boolean} True if current breakpoint is at most the given breakpoint
   */
  isAtMost(breakpoint) {
    const breakpoints = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];
    const currentIndex = breakpoints.indexOf(this.currentBreakpoint);
    const targetIndex = breakpoints.indexOf(breakpoint);
    
    return currentIndex <= targetIndex;
  }

  /**
   * Check if current breakpoint is between
   *
   * @param {string} min Minimum breakpoint name
   * @param {string} max Maximum breakpoint name
   * @returns {boolean} True if current breakpoint is between the given breakpoints
   */
  isBetween(min, max) {
    return this.isAtLeast(min) && this.isAtMost(max);
  }
}

// Export for use in theme.js
export default AquaLuxeResponsive;