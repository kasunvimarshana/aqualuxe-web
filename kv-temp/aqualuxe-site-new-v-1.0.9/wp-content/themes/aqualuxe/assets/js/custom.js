/**
 * AquaLuxe Custom JavaScript - Luxury Ornamental Fish Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
  'use strict';
  
  // Throttle function to limit event handler execution
  function throttle(func, wait) {
    var timeout;
    return function() {
      var context = this, args = arguments;
      if (!timeout) {
        timeout = setTimeout(function() {
          timeout = null;
          func.apply(context, args);
        }, wait);
      }
    };
  }
  
  // Document ready
  $(document).ready(function() {
    // Initialize theme functionality
    AquaLuxe.init();
  });
  
  // AquaLuxe object
  window.AquaLuxe = {
    /**
     * Initialize theme functionality
     */
    init: function() {
      this.stickyHeader();
      this.mobileMenu();
      this.accessibility();
      this.smoothScroll();
      this.luxuryAnimations();
      this.productHoverEffects();
    },
    
    /**
     * Sticky header functionality
     */
    stickyHeader: function() {
      // Add sticky header class to body if enabled
      if ($('body').hasClass('aqualuxe-sticky-header')) {
        var header = $('.site-header');
        var headerHeight = header.outerHeight();
        
        // Throttle scroll event handler
        $(window).scroll(throttle(function() {
          if ($(window).scrollTop() > headerHeight) {
            header.addClass('sticky');
          } else {
            header.removeClass('sticky');
          }
        }, 100));
      }
    },
    
    /**
     * Mobile menu functionality
     */
    mobileMenu: function() {
      // Mobile menu toggle
      $('.menu-toggle').on('click', function(e) {
        e.preventDefault();
        $('.main-navigation').toggleClass('toggled');
        $(this).toggleClass('active');
      });
      
      // Close mobile menu when clicking outside
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.main-navigation').length &&
            !$(e.target).closest('.menu-toggle').length &&
            $('.main-navigation').hasClass('toggled')) {
          $('.main-navigation').removeClass('toggled');
          $('.menu-toggle').removeClass('active');
        }
      });
    },
    
    /**
     * Accessibility enhancements
     */
    accessibility: function() {
      // Add focus class to focused elements
      $('body').on('focus', 'a, button, input, textarea, select', function() {
        $(this).addClass('focus');
      });
      
      $('body').on('blur', 'a, button, input, textarea, select', function() {
        $(this).removeClass('focus');
      });
      
      // Skip link focus
      $('.skip-link').on('click', function(e) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
          target.attr('tabindex', '-1').focus();
        }
      });
    },
    
    /**
     * Smooth scrolling
     */
    smoothScroll: function() {
      // Smooth scroll for anchor links
      $('a[href*="#"]:not([href="#"])').on('click', function(e) {
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
            location.hostname === this.hostname) {
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
          if (target.length) {
            $('html, body').animate({
              scrollTop: target.offset().top - 100
            }, 1000);
            return false;
          }
        }
      });
    },
    
    /**
     * Luxury animations
     */
    luxuryAnimations: function() {
      // Throttle scroll event handler
      var throttledScrollHandler = throttle(function() {
        $('.fade-in').each(function() {
          var $element = $(this);
          var elementTop = $element.offset().top;
          var elementHeight = $element.outerHeight();
          var windowHeight = $(window).height();
          var scrollPos = $(window).scrollTop();
          
          // Check if element is in viewport
          if (elementTop < scrollPos + windowHeight - 100 && elementTop + elementHeight > scrollPos) {
            $element.addClass('animated');
          }
        });
      }, 100);
      
      // Attach throttled scroll handler
      $(window).scroll(throttledScrollHandler);
      
      // Initial check for elements in viewport
      throttledScrollHandler();
    },
    
    /**
     * Product hover effects
     */
    productHoverEffects: function() {
      // Use event delegation for hover effects instead of attaching to each image
      $('.woocommerce ul.products').on('mouseenter', '.woocommerce-loop-product__link', function() {
        var $container = $(this);
        if (!$container.find('.luxury-overlay').length) {
          $container.append('<div class="luxury-overlay"></div>');
        }
        $container.find('.luxury-overlay').addClass('active');
      });
      
      $('.woocommerce ul.products').on('mouseleave', '.woocommerce-loop-product__link', function() {
        var $container = $(this);
        $container.find('.luxury-overlay').removeClass('active');
      });
    }
  };
  
})(jQuery);