/**
 * Utility functions
 * 
 * General utility functions used throughout the theme
 */

export default (function() {
  // Initialize utilities when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initBackToTop();
    initResponsiveTables();
    initResponsiveEmbeds();
    initTooltips();
  });

  /**
   * Initialize back to top button
   */
  function initBackToTop() {
    const backToTopButton = document.getElementById('back-to-top');
    
    if (!backToTopButton) {
      return;
    }
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        backToTopButton.classList.add('show');
      } else {
        backToTopButton.classList.remove('show');
      }
    });
    
    // Scroll to top when clicked
    backToTopButton.addEventListener('click', function(e) {
      e.preventDefault();
      
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }
  
  /**
   * Initialize responsive tables
   */
  function initResponsiveTables() {
    const tables = document.querySelectorAll('table:not(.no-responsive)');
    
    tables.forEach(table => {
      if (!table.parentElement.classList.contains('table-responsive')) {
        const wrapper = document.createElement('div');
        wrapper.className = 'table-responsive';
        
        // Wrap table in responsive container
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
      }
    });
  }
  
  /**
   * Initialize responsive embeds
   */
  function initResponsiveEmbeds() {
    const embeds = document.querySelectorAll('iframe[src*="youtube.com"], iframe[src*="vimeo.com"]');
    
    embeds.forEach(embed => {
      if (!embed.parentElement.classList.contains('responsive-embed')) {
        const wrapper = document.createElement('div');
        wrapper.className = 'responsive-embed';
        
        // Wrap embed in responsive container
        embed.parentNode.insertBefore(wrapper, embed);
        wrapper.appendChild(embed);
      }
    });
  }
  
  /**
   * Initialize tooltips
   */
  function initTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
      element.addEventListener('mouseenter', function() {
        const tooltipText = this.getAttribute('data-tooltip');
        
        // Create tooltip element
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = tooltipText;
        document.body.appendChild(tooltip);
        
        // Position tooltip
        const elementRect = this.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();
        
        tooltip.style.top = (elementRect.top - tooltipRect.height - 10) + 'px';
        tooltip.style.left = (elementRect.left + (elementRect.width / 2) - (tooltipRect.width / 2)) + 'px';
        
        // Show tooltip with slight delay
        setTimeout(() => {
          tooltip.classList.add('show');
        }, 10);
        
        // Remove tooltip on mouseleave
        this.addEventListener('mouseleave', function() {
          tooltip.classList.remove('show');
          
          // Remove tooltip element after fade out
          setTimeout(() => {
            if (tooltip.parentNode) {
              tooltip.parentNode.removeChild(tooltip);
            }
          }, 300);
        }, { once: true });
      });
    });
  }
  
  /**
   * Debounce function to limit function calls
   * 
   * @param {Function} func - Function to debounce
   * @param {number} wait - Wait time in milliseconds
   * @return {Function} - Debounced function
   */
  function debounce(func, wait) {
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
   * Throttle function to limit function calls
   * 
   * @param {Function} func - Function to throttle
   * @param {number} limit - Limit in milliseconds
   * @return {Function} - Throttled function
   */
  function throttle(func, limit) {
    let inThrottle;
    
    return function executedFunction(...args) {
      if (!inThrottle) {
        func(...args);
        inThrottle = true;
        
        setTimeout(() => {
          inThrottle = false;
        }, limit);
      }
    };
  }
  
  /**
   * Format price with currency symbol
   * 
   * @param {number} price - Price to format
   * @param {string} currencySymbol - Currency symbol
   * @param {string} currencyPosition - Position of currency symbol (before|after)
   * @return {string} - Formatted price
   */
  function formatPrice(price, currencySymbol = '$', currencyPosition = 'before') {
    const formattedPrice = parseFloat(price).toFixed(2);
    
    if (currencyPosition === 'before') {
      return currencySymbol + formattedPrice;
    } else {
      return formattedPrice + currencySymbol;
    }
  }
  
  /**
   * Get URL parameter by name
   * 
   * @param {string} name - Parameter name
   * @param {string} url - URL to search (defaults to current URL)
   * @return {string|null} - Parameter value or null if not found
   */
  function getUrlParameter(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
    const results = regex.exec(url);
    
    if (!results) return null;
    if (!results[2]) return '';
    
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }
  
  /**
   * Set cookie
   * 
   * @param {string} name - Cookie name
   * @param {string} value - Cookie value
   * @param {number} days - Expiry in days
   */
  function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "; expires=" + date.toUTCString();
    document.cookie = name + "=" + value + expires + "; path=/; SameSite=Lax";
  }
  
  /**
   * Get cookie by name
   * 
   * @param {string} name - Cookie name
   * @return {string|null} - Cookie value or null if not found
   */
  function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    
    return null;
  }
  
  /**
   * Delete cookie
   * 
   * @param {string} name - Cookie name
   */
  function deleteCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  }
  
  // Return public methods and utilities
  return {
    initBackToTop,
    initResponsiveTables,
    initResponsiveEmbeds,
    initTooltips,
    debounce,
    throttle,
    formatPrice,
    getUrlParameter,
    setCookie,
    getCookie,
    deleteCookie
  };
})();