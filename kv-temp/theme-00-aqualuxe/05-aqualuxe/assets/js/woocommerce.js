/**
 * AquaLuxe WooCommerce Scripts
 *
 * This file contains custom JavaScript for WooCommerce functionality in the AquaLuxe theme.
 */

(function($) {
  'use strict';

  /**
   * Initialize WooCommerce functionality
   */
  $(document).ready(function() {
    // Initialize WooCommerce components
    AquaLuxeWoo.init();
  });

  /**
   * AquaLuxe WooCommerce Object
   */
  window.AquaLuxeWoo = {
    /**
     * Initialize WooCommerce functionality
     */
    init: function() {
      this.bindEvents();
      this.initComponents();
    },

    /**
     * Bind event handlers
     */
    bindEvents: function() {
      // Quantity buttons
      $(document).on('click', '.quantity .plus, .quantity .minus', this.handleQuantityChange);
      
      // Product gallery
      $(document).on('click', '.product-gallery-thumb', this.handleGalleryThumbClick);
      
      // Variation selection
      $(document).on('change', '.variations select', this.handleVariationChange);
    },

    /**
     * Initialize components
     */
    initComponents: function() {
      // Initialize any components here
      this.initQuantityButtons();
      this.initProductGallery();
    },

    /**
     * Initialize quantity buttons
     */
    initQuantityButtons: function() {
      // Add plus and minus buttons to quantity inputs
      $('.quantity').each(function() {
        var $quantity = $(this);
        var $input = $quantity.find('input.qty');
        
        if ($input.length && !$quantity.find('.plus').length) {
          $input.after('<button type="button" class="plus">+</button>');
          $input.before('<button type="button" class="minus">-</button>');
        }
      });
    },

    /**
     * Handle quantity change
     */
    handleQuantityChange: function(e) {
      e.preventDefault();
      
      var $button = $(this);
      var $input = $button.siblings('input.qty');
      var currentValue = parseInt($input.val()) || 0;
      var step = parseInt($input.attr('step')) || 1;
      var min = parseInt($input.attr('min')) || 0;
      var max = parseInt($input.attr('max')) || Infinity;
      
      if ($button.hasClass('plus')) {
        if (currentValue + step <= max) {
          $input.val(currentValue + step).trigger('change');
        }
      } else if ($button.hasClass('minus')) {
        if (currentValue - step >= min) {
          $input.val(currentValue - step).trigger('change');
        }
      }
    },

    /**
     * Initialize product gallery
     */
    initProductGallery: function() {
      // Add thumbnail navigation to product gallery
      $('.woocommerce-product-gallery').each(function() {
        var $gallery = $(this);
        var $thumbs = $gallery.find('.flex-control-thumbs');
        
        if ($thumbs.length) {
          $thumbs.addClass('product-gallery-thumbs');
        }
      });
    },

    /**
     * Handle gallery thumbnail click
     */
    handleGalleryThumbClick: function(e) {
      e.preventDefault();
      
      var $thumb = $(this);
      var largeImageSrc = $thumb.data('large-image');
      
      // Update main image
      $('.woocommerce-product-gallery__image img').attr('src', largeImageSrc);
      
      // Update active class
      $('.product-gallery-thumb').removeClass('active');
      $thumb.addClass('active');
    },

    /**
     * Handle variation change
     */
    handleVariationChange: function() {
      var $select = $(this);
      var $form = $select.closest('.variations_form');
      var variationId = $form.find('input[name="variation_id"]').val();
      
      // Update add to cart button
      if (variationId) {
        $('.single_add_to_cart_button').removeClass('disabled').removeAttr('disabled');
      } else {
        $('.single_add_to_cart_button').addClass('disabled').attr('disabled', 'disabled');
      }
    }
  };

})(jQuery);