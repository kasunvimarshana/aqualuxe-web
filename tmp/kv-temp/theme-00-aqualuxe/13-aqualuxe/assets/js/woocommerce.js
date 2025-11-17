/**
 * AquaLuxe WooCommerce JavaScript
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
    // Initialize WooCommerce functionality
    AquaLuxeWoo.init();
  });
  
  // AquaLuxeWoo object
  window.AquaLuxeWoo = {
    /**
     * Initialize WooCommerce functionality
     */
    init: function() {
      this.ajaxAddToCart();
      this.quickView();
      this.productGallery();
      
    },
    
    /**
     * AJAX add to cart functionality
     */
    ajaxAddToCart: function() {
      // Add to cart button click
      $(document).on('click', '.aqualuxe-ajax-add-to-cart', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var $product = $button.closest('.product, .product-type-variable');
        var productId = $button.data('product_id');
        var quantity = $product.find('.quantity input.qty').val() || 1;
        
        // Show loading indicator
        $button.addClass('loading');
        $button.siblings('.aqualuxe-ajax-loading').addClass('active');
        
        // AJAX request
        $.ajax({
          url: aqualuxe_ajax.ajax_url,
          type: 'POST',
          data: {
            action: 'aqualuxe_add_to_cart',
            product_id: productId,
            quantity: quantity,
            nonce: aqualuxe_ajax.nonce
          },
          success: function(response) {
            if (response.success) {
              // Update cart count in header
              $('.cart-count').text(response.data.cart_count);
              
              // Show success message
              alert(response.data.message);
            } else {
              // Show error message
              alert(response.data.message);
            }
          },
          error: function() {
            // Show error message
            alert('An error occurred. Please try again.');
          },
          complete: function() {
            // Hide loading indicator
            $button.removeClass('loading');
            $button.siblings('.aqualuxe-ajax-loading').removeClass('active');
          }
        });
      });
    },
    
    /**
     * Quick view functionality with enhanced accessibility
     */
    quickView: function() {
      // Quick view button click
      $(document).on('click', '.aqualuxe-quick-view', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var productId = $button.data('product_id');
        var $originalTrigger = $button; // Store original trigger for focus return
        
        // Show loading indicator
        $button.addClass('loading');
        
        // AJAX request
        $.ajax({
          url: aqualuxe_ajax.ajax_url,
          type: 'POST',
          data: {
            action: 'aqualuxe_quick_view',
            product_id: productId,
            nonce: aqualuxe_ajax.nonce
          },
          success: function(response) {
            if (response.success) {
              // Create modal with proper accessibility attributes
              var $modal = $('<div class="aqualuxe-quick-view-modal" role="dialog" aria-modal="true" aria-labelledby="quick-view-title"><div class="aqualuxe-quick-view-content">' + response.data.content + '<button class="aqualuxe-quick-view-close" aria-label="Close quick view">&times;</button></div></div>');
              
              // Add to body
              $('body').append($modal);
              
              // Show modal
              setTimeout(function() {
                $modal.addClass('active');
                
                // Focus management - move focus to modal
                $modal.find('h2.product-title').attr('id', 'quick-view-title');
                $modal.attr('tabindex', '-1').focus();
              }, 10);
            } else {
              // Show error message
              alert('An error occurred. Please try again.');
            }
          },
          error: function() {
            // Show error message
            alert('An error occurred. Please try again.');
          },
          complete: function() {
            // Hide loading indicator
            $button.removeClass('loading');
          }
        });
      });
      
      // Close modal
      $(document).on('click', '.aqualuxe-quick-view-close, .aqualuxe-quick-view-modal', function(e) {
        if (e.target === this) {
          var $modal = $('.aqualuxe-quick-view-modal');
          $modal.removeClass('active');
          
          // Return focus to original trigger
          var $trigger = $('.aqualuxe-quick-view[data-product_id="' + $modal.find('.product_id').val() + '"]');
          if ($trigger.length) {
            $trigger.focus();
          }
          
          // Remove modal after animation
          setTimeout(function() {
            $modal.remove();
          }, 300);
        }
      });
      
      // Close modal with ESC key
      $(document).on('keyup', function(e) {
        if (e.keyCode === 27) {
          var $modal = $('.aqualuxe-quick-view-modal');
          $modal.removeClass('active');
          
          // Return focus to original trigger
          var $trigger = $('.aqualuxe-quick-view[data-product_id="' + $modal.find('.product_id').val() + '"]');
          if ($trigger.length) {
            $trigger.focus();
          }
          
          // Remove modal after animation
          setTimeout(function() {
            $modal.remove();
          }, 300);
        }
      });
      
      // Trap focus within modal
      $(document).on('keydown', '.aqualuxe-quick-view-modal', function(e) {
        if (e.keyCode === 9) { // Tab key
          var $modal = $(this);
          var $focusableElements = $modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
          var $firstElement = $focusableElements.first();
          var $lastElement = $focusableElements.last();
          
          if (e.shiftKey) { // Shift + Tab
            if (document.activeElement === $firstElement[0]) {
              $lastElement.focus();
              e.preventDefault();
            }
          } else { // Tab
            if (document.activeElement === $lastElement[0]) {
              $firstElement.focus();
              e.preventDefault();
            }
          }
        }
      });
    },
    
    /**
     * Product gallery enhancements
     */
    productGallery: function() {
      // Add zoom functionality to product images
      if ($.fn.zoom) {
        $('.woocommerce-product-gallery__image').zoom();
      }
      
      // Add lightbox functionality to product images
      if ($.fn.magnificPopup) {
        $('.woocommerce-product-gallery__image a').magnificPopup({
          type: 'image',
          gallery: {
            enabled: true
          }
        });
      }
    },
    
    
  };
  
})(jQuery);