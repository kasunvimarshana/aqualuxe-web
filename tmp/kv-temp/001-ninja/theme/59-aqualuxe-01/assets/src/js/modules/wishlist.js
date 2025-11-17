/**
 * Wishlist Module
 * 
 * Handles wishlist functionality for products
 */

(function($) {
  'use strict';

  const AquaLuxeWishlist = {
    /**
     * Initialize wishlist
     */
    init: function() {
      this.initWishlistButtons();
      this.initWishlistEvents();
      this.initWishlistPage();
    },

    /**
     * Initialize wishlist buttons
     */
    initWishlistButtons: function() {
      // Add wishlist buttons to products
      if ($('.products .product').length && !$('.products .product .wishlist-button').length) {
        $('.products .product').each(function() {
          const $product = $(this);
          const productId = $product.data('product-id') || $product.find('.add_to_cart_button').data('product_id');
          
          if (productId) {
            // Check if product is in wishlist
            const wishlist = AquaLuxeWishlist.getWishlist();
            const inWishlist = wishlist.includes(productId.toString());
            
            // Create wishlist button
            const $wishlistButton = $(
              '<a href="#" class="button wishlist-button' + (inWishlist ? ' added' : '') + '" data-product-id="' + productId + '">' +
              '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">' +
              '<path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />' +
              '</svg>' +
              '<span>' + (inWishlist ? aqualuxe_wishlist.i18n_remove_from_wishlist : aqualuxe_wishlist.i18n_add_to_wishlist) + '</span>' +
              '</a>'
            );
            
            // Add button to product actions
            if ($product.find('.product-card__actions').length) {
              $product.find('.product-card__actions').append($wishlistButton);
            } else {
              $product.find('.product-card__footer').append($wishlistButton);
            }
          }
        });
      }
    },

    /**
     * Initialize wishlist events
     */
    initWishlistEvents: function() {
      // Wishlist button click
      $(document).on('click', '.wishlist-button', function(e) {
        e.preventDefault();

        const $button = $(this);
        const productId = $button.data('product-id');

        // Send AJAX request
        $.ajax({
          url: aqualuxe_wishlist.ajax_url,
          type: 'POST',
          data: {
            action: 'aqualuxe_wishlist',
            product_id: productId,
            nonce: aqualuxe_wishlist.nonce,
          },
          success: function(response) {
            if (response.success) {
              if (response.data.action === 'added') {
                $button.addClass('added');
                $button.find('span').text(aqualuxe_wishlist.i18n_remove_from_wishlist);
                
                // Show notification
                AquaLuxeWishlist.showNotification(aqualuxe_wishlist.i18n_added_to_wishlist);
              } else {
                $button.removeClass('added');
                $button.find('span').text(aqualuxe_wishlist.i18n_add_to_wishlist);
                
                // Show notification
                AquaLuxeWishlist.showNotification(aqualuxe_wishlist.i18n_removed_from_wishlist);
              }

              // Update wishlist count
              $('.wishlist-count').text(response.data.count);
              
              // Update wishlist cookie
              AquaLuxeWishlist.updateWishlistCookie(response.data.wishlist);
            }
          },
        });
      });
    },

    /**
     * Initialize wishlist page
     */
    initWishlistPage: function() {
      // Remove from wishlist
      $(document).on('click', '.remove-from-wishlist', function(e) {
        e.preventDefault();

        const $button = $(this);
        const productId = $button.data('product-id');
        const $row = $button.closest('tr');

        // Send AJAX request
        $.ajax({
          url: aqualuxe_wishlist.ajax_url,
          type: 'POST',
          data: {
            action: 'aqualuxe_wishlist',
            product_id: productId,
            nonce: aqualuxe_wishlist.nonce,
          },
          success: function(response) {
            if (response.success) {
              $row.fadeOut(300, function() {
                $row.remove();

                // Check if wishlist is empty
                if ($('.wishlist-table tbody tr').length === 0) {
                  $('.wishlist-products').replaceWith(
                    '<div class="wishlist-empty">' +
                    '<p>' + aqualuxe_wishlist.i18n_empty_wishlist + '</p>' +
                    '<p><a class="button" href="' + aqualuxe_wishlist.shop_url + '">' + aqualuxe_wishlist.i18n_return_to_shop + '</a></p>' +
                    '</div>'
                  );
                }
              });

              // Update wishlist count
              $('.wishlist-count').text(response.data.count);
              
              // Update wishlist cookie
              AquaLuxeWishlist.updateWishlistCookie(response.data.wishlist);
              
              // Show notification
              AquaLuxeWishlist.showNotification(aqualuxe_wishlist.i18n_removed_from_wishlist);
            }
          },
        });
      });
      
      // Add all to cart
      $(document).on('click', '.add-all-to-cart', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const $products = $('.wishlist-table tbody tr');
        
        if ($products.length === 0) {
          return;
        }
        
        // Add loading state
        $button.addClass('loading');
        
        // Get product IDs
        const productIds = [];
        $products.each(function() {
          const productId = $(this).find('.remove-from-wishlist').data('product-id');
          if (productId) {
            productIds.push(productId);
          }
        });
        
        // Add products to cart
        AquaLuxeWishlist.addProductsToCart(productIds, 0, $button);
      });
    },
    
    /**
     * Add products to cart recursively
     * 
     * @param {Array} productIds - Product IDs
     * @param {number} index - Current index
     * @param {jQuery} $button - Button element
     */
    addProductsToCart: function(productIds, index, $button) {
      if (index >= productIds.length) {
        // All products added
        $button.removeClass('loading');
        
        // Show notification
        AquaLuxeWishlist.showNotification(aqualuxe_wishlist.i18n_all_added_to_cart);
        
        // Redirect to cart
        window.location.href = aqualuxe_wishlist.cart_url;
        return;
      }
      
      // Add product to cart
      $.ajax({
        url: aqualuxe_wishlist.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_add_to_cart',
          product_id: productIds[index],
          quantity: 1,
          nonce: aqualuxe_wishlist.nonce,
        },
        success: function() {
          // Add next product
          AquaLuxeWishlist.addProductsToCart(productIds, index + 1, $button);
        },
        error: function() {
          // Add next product
          AquaLuxeWishlist.addProductsToCart(productIds, index + 1, $button);
        },
      });
    },
    
    /**
     * Get wishlist from cookie
     * 
     * @return {Array} Wishlist
     */
    getWishlist: function() {
      const wishlistCookie = AquaLuxeWishlist.getCookie('aqualuxe_wishlist');
      return wishlistCookie ? wishlistCookie.split(',') : [];
    },
    
    /**
     * Update wishlist cookie
     * 
     * @param {Array} wishlist - Wishlist
     */
    updateWishlistCookie: function(wishlist) {
      if (Array.isArray(wishlist)) {
        AquaLuxeWishlist.setCookie('aqualuxe_wishlist', wishlist.join(','), 30);
      }
    },
    
    /**
     * Get cookie
     * 
     * @param {string} name - Cookie name
     * @return {string} Cookie value
     */
    getCookie: function(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(';').shift();
      return '';
    },
    
    /**
     * Set cookie
     * 
     * @param {string} name - Cookie name
     * @param {string} value - Cookie value
     * @param {number} days - Cookie expiration in days
     */
    setCookie: function(name, value, days) {
      const date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      const expires = `expires=${date.toUTCString()}`;
      document.cookie = `${name}=${value};${expires};path=/`;
    },
    
    /**
     * Show notification
     * 
     * @param {string} message - Notification message
     */
    showNotification: function(message) {
      // Create notification if it doesn't exist
      if (!$('.aqualuxe-notification').length) {
        $('body').append('<div class="aqualuxe-notification"></div>');
      }
      
      // Show notification
      const $notification = $('.aqualuxe-notification');
      $notification.text(message).addClass('active');
      
      // Hide notification after 3 seconds
      setTimeout(function() {
        $notification.removeClass('active');
      }, 3000);
    },
  };

  // Initialize on document ready
  $(document).ready(function() {
    AquaLuxeWishlist.init();
  });

})(jQuery);