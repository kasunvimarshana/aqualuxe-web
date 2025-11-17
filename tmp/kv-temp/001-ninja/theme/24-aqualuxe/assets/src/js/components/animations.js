/**
 * Animations functionality
 * 
 * Handles scroll-based animations and transitions
 */

export default (function() {
  // Initialize animations when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
    initParallaxElements();
  });

  /**
   * Initialize scroll-based animations
   */
  function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    if (animatedElements.length === 0) {
      return;
    }
    
    // Check if elements are in viewport on page load
    checkElementsInViewport();
    
    // Check on scroll
    window.addEventListener('scroll', checkElementsInViewport);
    
    // Check on resize
    window.addEventListener('resize', checkElementsInViewport);
    
    /**
     * Check if elements are in viewport and add animation class
     */
    function checkElementsInViewport() {
      animatedElements.forEach(element => {
        if (isElementInViewport(element)) {
          element.classList.add('animated');
        }
      });
    }
    
    /**
     * Check if element is in viewport
     * 
     * @param {HTMLElement} element - Element to check
     * @return {boolean} - True if element is in viewport
     */
    function isElementInViewport(element) {
      const rect = element.getBoundingClientRect();
      const windowHeight = window.innerHeight || document.documentElement.clientHeight;
      
      // Element is considered in viewport when it's top is in the bottom 80% of the screen
      // or when its bottom is in the top 80% of the screen
      return (
        rect.top < windowHeight * 0.8 &&
        rect.bottom > windowHeight * 0.2
      );
    }
  }
  
  /**
   * Initialize parallax scrolling elements
   */
  function initParallaxElements() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    if (parallaxElements.length === 0) {
      return;
    }
    
    // Update parallax positions on scroll
    window.addEventListener('scroll', updateParallaxPositions);
    
    // Initial update
    updateParallaxPositions();
    
    /**
     * Update positions of parallax elements based on scroll position
     */
    function updateParallaxPositions() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      parallaxElements.forEach(element => {
        const speed = parseFloat(element.dataset.parallaxSpeed || 0.5);
        const yPos = -(scrollTop * speed);
        
        element.style.transform = `translate3d(0, ${yPos}px, 0)`;
      });
    }
  }
})();