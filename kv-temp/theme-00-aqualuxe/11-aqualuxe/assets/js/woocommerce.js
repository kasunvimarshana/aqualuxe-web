/**
 * AquaLuxe WooCommerce JavaScript - Luxury Ornamental Fish Theme
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
      this.productFilters();
      this.luxuryCheckout();
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
              
              // Show success message with luxury styling
              $('body').append('<div class="luxury-notice luxury-notice-success">' + response.data.message + '</div>');
              $('.luxury-notice').fadeIn().delay(3000).fadeOut(function() {
                $(this).remove();
              });
              
              // Update cart widget if it exists
              if (typeof wc_cart_fragments_params !== 'undefined') {
                $.get(wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'), function(data) {
                  if (data && data.fragments) {
                    $.each(data.fragments, function(key, value) {
                      $(key).replaceWith(value);
                    });
                  }
                });
              }
            } else {
              // Show error message with luxury styling
              $('body').append('<div class="luxury-notice luxury-notice-error">' + response.data.message + '</div>');
              $('.luxury-notice').fadeIn().delay(3000).fadeOut(function() {
                $(this).remove();
              });
            }
          },
          error: function() {
            // Show error message with luxury styling
            $('body').append('<div class="luxury-notice luxury-notice-error">' + aqualuxe_ajax.i18n.added_to_cart_error + '</div>');
            $('.luxury-notice').fadeIn().delay(3000).fadeOut(function() {
              $(this).remove();
            });
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
      // Check if quick view is enabled
      if (typeof aqualuxe_ajax !== 'undefined' && aqualuxe_ajax.quick_view_enabled !== '1') {
        return;
      }
      
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
              // Create modal with luxury styling
              var $modal = $('<div class="aqualuxe-quick-view-modal"><div class="aqualuxe-quick-view-content">' + response.data.content + '<button class="aqualuxe-quick-view-close">&times;</button></div></div>');
              
              // Add to body
              $('body').append($modal);
              
              // Show modal with animation
              setTimeout(function() {
                $modal.addClass('active');
              }, 10);
              
              // Initialize any necessary scripts in the modal
              $modal.find('.variations_form').wc_variation_form();
              $modal.find('.quantity input').trigger('change');
            } else {
              // Show error message
              alert(aqualuxe_ajax.i18n.quick_view_error);
            }
          },
          error: function() {
            // Show error message
            alert(aqualuxe_ajax.i18n.quick_view_error);
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
        $('.woocommerce-product-gallery__image').zoom({
          magnify: 1.2
        });
      }
      
      // Add lightbox functionality to product images
      if ($.fn.magnificPopup) {
        $('.woocommerce-product-gallery__image a').magnificPopup({
          type: 'image',
          gallery: {
            enabled: true
          },
          mainClass: 'mfp-with-zoom',
          zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out'
          }
        });
      }
    },
    
    /**
     * Product filters
     */
    productFilters: function() {
      // Handle filter form submission
      $('.woocommerce-ordering select').on('change', function() {
        $(this).closest('form').submit();
      });
      
      // Handle category filter links
      $('.product-categories a').on('click', function(e) {
        // Add loading state
        $(this).addClass('loading');
      });
    },
    
    /**
     * Luxury checkout enhancements
     */
    luxuryCheckout: function() {
      // Add luxury styling to checkout fields
      $('.woocommerce-checkout .form-row input, .woocommerce-checkout .form-row select, .woocommerce-checkout .form-row textarea').each(function() {
        var $field = $(this);
        var $parent = $field.closest('.form-row');
        
        // Add focus effect
        $field.on('focus', function() {
          $parent.addClass('focused');
        });
        
        $field.on('blur', function() {
          $parent.removeClass('focused');
        });
      });
      
      // Handle payment method selection
      $(document).on('change', 'input[name="payment_method"]', function() {
        $('.payment_box').slideUp(250);
        $(this).closest('li').find('.payment_box').slideDown(250);
      });
    }
  };
  
})(jQuery);