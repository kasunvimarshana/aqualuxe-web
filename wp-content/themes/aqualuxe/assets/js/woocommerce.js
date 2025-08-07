/**
 * AquaLuxe WooCommerce JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
  'use strict';
  
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
      this.responsiveAdjustments();
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
    },
    
    /**
     * Responsive adjustments for product components
     */
    responsiveAdjustments: function() {
      // Adjust product grid based on screen size
      function adjustProductGrid() {
        var windowWidth = $(window).width();
        var $products = $('.woocommerce ul.products');
        
        if (windowWidth < 480) {
          $products.removeClass('columns-3 columns-4').addClass('columns-1');
        } else if (windowWidth < 768) {
          $products.removeClass('columns-1 columns-4').addClass('columns-2');
        } else {
          $products.removeClass('columns-1 columns-2').addClass('columns-3');
        }
      }
      
      // Initial adjustment
      adjustProductGrid();
      
      // Adjust on window resize
      $(window).resize(function() {
        adjustProductGrid();
      });
      
      // Ensure consistent product card heights
      function equalizeProductCardHeights() {
        $('.woocommerce ul.products li.product').each(function() {
          // Reset height
          $(this).find('.woocommerce-loop-product__title').css('height', 'auto');
          
          // Find max height
          var maxHeight = 0;
          $('.woocommerce ul.products li.product .woocommerce-loop-product__title').each(function() {
            var height = $(this).outerHeight();
            if (height > maxHeight) {
              maxHeight = height;
            }
          });
          
          // Apply max height
          $('.woocommerce ul.products li.product .woocommerce-loop-product__title').css('height', maxHeight + 'px');
        });
      }
      
      // Initial equalization
      equalizeProductCardHeights();
      
      // Equalize on window resize
      $(window).resize(function() {
        equalizeProductCardHeights();
      });
    }
  };
  
})(jQuery);