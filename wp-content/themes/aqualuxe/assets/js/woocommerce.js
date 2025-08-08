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
     * Quick view functionality
     */
    quickView: function() {
      // Quick view button click
      $(document).on('click', '.aqualuxe-quick-view', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var productId = $button.data('product_id');
        
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
              // Create modal
              var $modal = $('<div class="aqualuxe-quick-view-modal"><div class="aqualuxe-quick-view-content">' + response.data.content + '<button class="aqualuxe-quick-view-close">&times;</button></div></div>');
              
              // Add to body
              $('body').append($modal);
              
              // Show modal
              setTimeout(function() {
                $modal.addClass('active');
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
          $('.aqualuxe-quick-view-modal').removeClass('active');
          
          // Remove modal after animation
          setTimeout(function() {
            $('.aqualuxe-quick-view-modal').remove();
          }, 300);
        }
      });
      
      // Close modal with ESC key
      $(document).on('keyup', function(e) {
        if (e.keyCode === 27) {
          $('.aqualuxe-quick-view-modal').removeClass('active');
          
          // Remove modal after animation
          setTimeout(function() {
            $('.aqualuxe-quick-view-modal').remove();
          }, 300);
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
    }
  };
  
})(jQuery);