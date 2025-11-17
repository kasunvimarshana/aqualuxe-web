/**
 * AquaLuxe Theme JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
  'use strict';

  // Document ready
  $(function() {
    // Initialize theme functionality
    AquaLuxe.init();
  });

  // AquaLuxe object
  var AquaLuxe = {
    /**
     * Initialize theme functionality
     */
    init: function() {
      this.bindEvents();
      this.initComponents();
    },

    /**
     * Bind events
     */
    bindEvents: function() {
      // Mobile menu toggle
      $(document).on('click', '.menu-toggle', this.toggleMobileMenu);

      // Add to cart AJAX
      $(document).on('click', '.add_to_cart_button', this.addToCart);

      // Quick view
      $(document).on('click', '.quick-view-button', this.quickView);

      // Wishlist toggle
      $(document).on('click', '.wishlist-button', this.toggleWishlist);

      // Product filter
      $(document).on('submit', '.filter-form', this.filterProducts);

      // Product view toggle
      $(document).on('click', '.product-view-toggle a', this.toggleProductView);

      // Back to top
      $(window).scroll(this.toggleBackToTop);
      $(document).on('click', '.back-to-top', this.backToTop);
    },

    /**
     * Initialize components
     */
    initComponents: function() {
      // Initialize any components here
      this.initStickyHeader();
      this.initProductSlider();
    },

    /**
     * Toggle mobile menu
     */
    toggleMobileMenu: function(e) {
      e.preventDefault();
      $('.main-navigation').toggleClass('toggled');
    },

    /**
     * Add to cart with AJAX
     */
    addToCart: function(e) {
      e.preventDefault();

      var $thisbutton = $(this),
          product_id = $thisbutton.data('product_id'),
          variation_id = $thisbutton.data('variation_id'),
          quantity = $thisbutton.data('quantity') || 1;

      // Add loading class
      $thisbutton.addClass('loading');

      // AJAX request
      $.ajax({
        url: aqualuxe_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_add_to_cart',
          product_id: product_id,
          variation_id: variation_id,
          quantity: quantity,
          nonce: aqualuxe_ajax.nonce
        },
        success: function(response) {
          if (response.success) {
            // Update cart count
            $('.cart-count').text(response.cart_count);
            
            // Show success message
            alert(response.message);
          } else {
            // Show error message
            alert(response.message);
          }
        },
        error: function() {
          // Show error message
          alert('An error occurred. Please try again.');
        },
        complete: function() {
          // Remove loading class
          $thisbutton.removeClass('loading');
        }
      });
    },

    /**
     * Quick view
     */
    quickView: function(e) {
      e.preventDefault();

      var $this = $(this),
          product_id = $this.data('product_id');

      // Add loading class
      $this.addClass('loading');

      // AJAX request
      $.ajax({
        url: aqualuxe_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_quick_view',
          product_id: product_id,
          nonce: aqualuxe_ajax.nonce
        },
        success: function(response) {
          if (response.success) {
            // Show quick view modal
            AquaLuxe.showQuickViewModal(response.content);
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
          // Remove loading class
          $this.removeClass('loading');
        }
      });
    },

    /**
     * Show quick view modal
     */
    showQuickViewModal: function(content) {
      // Create modal if it doesn't exist
      if ($('#quick-view-modal').length === 0) {
        $('body').append('<div id="quick-view-modal" class="modal"><div class="modal-content">' + content + '</div></div>');
      } else {
        $('#quick-view-modal .modal-content').html(content);
      }

      // Show modal
      $('#quick-view-modal').addClass('show');
    },

    /**
     * Toggle wishlist
     */
    toggleWishlist: function(e) {
      e.preventDefault();

      var $this = $(this),
          product_id = $this.data('product_id');

      // Add loading class
      $this.addClass('loading');

      // AJAX request
      $.ajax({
        url: aqualuxe_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_toggle_wishlist',
          product_id: product_id,
          nonce: aqualuxe_ajax.nonce
        },
        success: function(response) {
          if (response.success) {
            // Update button text and class
            if (response.added) {
              $this.addClass('in-wishlist').text('Remove from Wishlist');
            } else {
              $this.removeClass('in-wishlist').text('Add to Wishlist');
            }

            // Show success message
            alert(response.message);
          } else {
            // Show error message
            alert(response.message);
          }
        },
        error: function() {
          // Show error message
          alert('An error occurred. Please try again.');
        },
        complete: function() {
          // Remove loading class
          $this.removeClass('loading');
        }
      });
    },

    /**
     * Filter products
     */
    filterProducts: function(e) {
      e.preventDefault();

      var $form = $(this),
          data = $form.serialize();

      // Add loading class
      $form.addClass('loading');

      // AJAX request
      $.ajax({
        url: aqualuxe_ajax.ajax_url,
        type: 'POST',
        data: data + '&action=aqualuxe_product_filter&nonce=' + aqualuxe_ajax.nonce,
        success: function(response) {
          if (response.success) {
            // Update product grid
            $('.products').html(response.content);
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
          // Remove loading class
          $form.removeClass('loading');
        }
      });
    },

    /**
     * Toggle product view
     */
    toggleProductView: function(e) {
      e.preventDefault();

      var $this = $(this);

      // Toggle active class
      $('.product-view-toggle a').removeClass('active');
      $this.addClass('active');

      // Toggle view class on products container
      if ($this.hasClass('list-view')) {
        $('.products').addClass('list-view');
      } else {
        $('.products').removeClass('list-view');
      }
    },

    /**
     * Initialize sticky header
     */
    initStickyHeader: function() {
      var header = $('.site-header');
      var sticky = header.offset().top;

      $(window).scroll(function() {
        if ($(window).scrollTop() >= sticky) {
          header.addClass('sticky');
        } else {
          header.removeClass('sticky');
        }
      });
    },

    /**
     * Initialize product slider
     */
    initProductSlider: function() {
      // Initialize any product sliders here
      // This would typically use a library like Swiper or Slick
    },

    /**
     * Toggle back to top button
     */
    toggleBackToTop: function() {
      if ($(window).scrollTop() > 300) {
        $('.back-to-top').addClass('show');
      } else {
        $('.back-to-top').removeClass('show');
      }
    },

    /**
     * Back to top
     */
    backToTop: function(e) {
      e.preventDefault();
      $('html, body').animate({ scrollTop: 0 }, 'slow');
    }
  };

})(jQuery);