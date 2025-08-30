/**
 * AquaLuxe Theme - WooCommerce Wishlist
 *
 * This file handles the wishlist functionality for WooCommerce.
 */

(function($) {
  'use strict';

  // Initialize wishlist
  function initWishlist() {
    // Check if wishlist exists
    if (!$('.wishlist-toggle').length) {
      return;
    }

    // Handle add to wishlist
    $(document).on('click', '.add-to-wishlist', function(e) {
      e.preventDefault();
      
      const $button = $(this);
      const productId = $button.data('product-id');
      
      // Skip if already processing
      if ($button.hasClass('loading')) {
        return;
      }
      
      // Add loading class
      $button.addClass('loading');
      
      // Send AJAX request
      $.ajax({
        url: aqualuxeVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'aqualuxe_add_to_wishlist',
          product_id: productId,
          nonce: aqualuxeVars.nonce
        },
        success: function(response) {
          // Remove loading class
          $button.removeClass('loading');
          
          if (response.success) {
            // Update button
            $button.addClass('added').attr('title', 'Remove from Wishlist');
            $button.find('.wishlist-text').text('Remove from Wishlist');
            
            // Update wishlist count
            updateWishlistCount(response.data.count);
            
            // Show success message
            showMessage('success', response.data.message);
          } else {
            // Show error message
            showMessage('error', response.data.message);
          }
        },
        error: function() {
          // Remove loading class
          $button.removeClass('loading');
          
          // Show error message
          showMessage('error', 'Failed to add to wishlist. Please try again.');
        }
      });
    });

    // Handle remove from wishlist
    $(document).on('click', '.remove-from-wishlist', function(e) {
      e.preventDefault();
      
      const $button = $(this);
      const productId = $button.data('product-id');
      
      // Skip if already processing
      if ($button.hasClass('loading')) {
        return;
      }
      
      // Add loading class
      $button.addClass('loading');
      
      // Send AJAX request
      $.ajax({
        url: aqualuxeVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'aqualuxe_remove_from_wishlist',
          product_id: productId,
          nonce: aqualuxeVars.nonce
        },
        success: function(response) {
          // Remove loading class
          $button.removeClass('loading');
          
          if (response.success) {
            // If on wishlist page, remove product
            if ($('.wishlist-table').length) {
              $button.closest('tr').fadeOut(300, function() {
                $(this).remove();
                
                // If no products left, show empty message
                if ($('.wishlist-table tbody tr').length === 0) {
                  $('.wishlist-table').replaceWith('<p class="wishlist-empty">Your wishlist is empty.</p>');
                }
              });
            } else {
              // Update button
              $button.removeClass('added').attr('title', 'Add to Wishlist');
              $button.find('.wishlist-text').text('Add to Wishlist');
            }
            
            // Update wishlist count
            updateWishlistCount(response.data.count);
            
            // Show success message
            showMessage('success', response.data.message);
          } else {
            // Show error message
            showMessage('error', response.data.message);
          }
        },
        error: function() {
          // Remove loading class
          $button.removeClass('loading');
          
          // Show error message
          showMessage('error', 'Failed to remove from wishlist. Please try again.');
        }
      });
    });

    // Handle add all to cart
    $('.add-all-to-cart').on('click', function(e) {
      e.preventDefault();
      
      const $button = $(this);
      
      // Skip if already processing
      if ($button.hasClass('loading')) {
        return;
      }
      
      // Add loading class
      $button.addClass('loading');
      
      // Send AJAX request
      $.ajax({
        url: aqualuxeVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'aqualuxe_add_wishlist_to_cart',
          nonce: aqualuxeVars.nonce
        },
        success: function(response) {
          // Remove loading class
          $button.removeClass('loading');
          
          if (response.success) {
            // Show success message
            showMessage('success', response.data.message);
            
            // Update cart fragments
            $(document.body).trigger('wc_fragment_refresh');
            
            // Redirect to cart page
            window.location.href = response.data.cart_url;
          } else {
            // Show error message
            showMessage('error', response.data.message);
          }
        },
        error: function() {
          // Remove loading class
          $button.removeClass('loading');
          
          // Show error message
          showMessage('error', 'Failed to add items to cart. Please try again.');
        }
      });
    });

    // Handle clear wishlist
    $('.clear-wishlist').on('click', function(e) {
      e.preventDefault();
      
      const $button = $(this);
      
      // Skip if already processing
      if ($button.hasClass('loading')) {
        return;
      }
      
      // Confirm action
      if (!confirm('Are you sure you want to clear your wishlist?')) {
        return;
      }
      
      // Add loading class
      $button.addClass('loading');
      
      // Send AJAX request
      $.ajax({
        url: aqualuxeVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'aqualuxe_clear_wishlist',
          nonce: aqualuxeVars.nonce
        },
        success: function(response) {
          // Remove loading class
          $button.removeClass('loading');
          
          if (response.success) {
            // Replace table with empty message
            $('.wishlist-table').replaceWith('<p class="wishlist-empty">Your wishlist is empty.</p>');
            
            // Hide wishlist actions
            $('.wishlist-actions').hide();
            
            // Update wishlist count
            updateWishlistCount(0);
            
            // Show success message
            showMessage('success', response.data.message);
          } else {
            // Show error message
            showMessage('error', response.data.message);
          }
        },
        error: function() {
          // Remove loading class
          $button.removeClass('loading');
          
          // Show error message
          showMessage('error', 'Failed to clear wishlist. Please try again.');
        }
      });
    });

    // Update wishlist count
    function updateWishlistCount(count) {
      const $wishlistCount = $('.wishlist-count');
      
      if ($wishlistCount.length) {
        $wishlistCount.text(count);
        
        if (count > 0) {
          $wishlistCount.show();
        } else {
          $wishlistCount.hide();
        }
      }
    }

    // Show message
    function showMessage(type, message) {
      // Remove existing messages
      $('.woocommerce-message, .woocommerce-error, .woocommerce-info').remove();
      
      // Create message
      let $message;
      
      if (type === 'success') {
        $message = $('<div class="woocommerce-message" role="alert">' + message + '</div>');
      } else {
        $message = $('<div class="woocommerce-error" role="alert"><li>' + message + '</li></div>');
      }
      
      // Add message to page
      $('.woocommerce').prepend($message);
      
      // Scroll to message
      $('html, body').animate({
        scrollTop: $message.offset().top - 100
      }, 500);
      
      // Remove message after 5 seconds
      setTimeout(function() {
        $message.fadeOut(300, function() {
          $(this).remove();
        });
      }, 5000);
    }
  }

  // Initialize when document is ready
  $(document).ready(function() {
    initWishlist();
  });
})(jQuery);