/**
 * AquaLuxe Custom JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
  'use strict';
  
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
    },
    
    /**
     * Sticky header functionality
     */
    stickyHeader: function() {
      // Add sticky header class to body if enabled
      if ($('body').hasClass('aqualuxe-sticky-header')) {
        var header = $('.site-header');
        var headerHeight = header.outerHeight();
        
        $(window).scroll(function() {
          if ($(window).scrollTop() > headerHeight) {
            header.addClass('sticky');
          } else {
            header.removeClass('sticky');
          }
        });
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
    }
  };
  
})(jQuery);