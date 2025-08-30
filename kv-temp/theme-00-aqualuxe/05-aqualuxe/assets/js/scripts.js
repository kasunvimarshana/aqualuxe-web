/**
 * AquaLuxe Theme Scripts
 *
 * This file contains custom JavaScript for the AquaLuxe theme.
 */

(function($) {
  'use strict';

  /**
   * Initialize theme functionality
   */
  $(document).ready(function() {
    // Initialize theme components
    AquaLuxe.init();
  });

  /**
   * AquaLuxe Theme Object
   */
  window.AquaLuxe = {
    /**
     * Initialize theme functionality
     */
    init: function() {
      this.bindEvents();
      this.initComponents();
    },

    /**
     * Bind event handlers
     */
    bindEvents: function() {
      // Mobile menu toggle
      $('.menu-toggle').on('click', this.toggleMobileMenu);
      
      // Add to cart functionality
      $(document).on('click', '.add_to_cart_button', this.handleAddToCart);
      
      // Product quick view
      $(document).on('click', '.quick-view-button', this.handleQuickView);
    },

    /**
     * Initialize theme components
     */
    initComponents: function() {
      // Initialize any components here
      this.initMobileMenu();
      this.initAccessibility();
    },

    /**
     * Toggle mobile menu
     */
    toggleMobileMenu: function(e) {
      e.preventDefault();
      $('.main-navigation').toggleClass('mobile-menu-open');
    },

    /**
     * Initialize mobile menu
     */
    initMobileMenu: function() {
      // Add mobile menu button if not already present
      if ($('.menu-toggle').length === 0) {
        $('.main-navigation').prepend('<button class="menu-toggle" aria-expanded="false"><span class="screen-reader-text">Menu</span></button>');
      }
    },

    /**
     * Initialize accessibility features
     */
    initAccessibility: function() {
      // Add ARIA attributes to navigation
      $('.main-navigation').attr('aria-label', 'Main navigation');
      
      // Add ARIA attributes to search form
      $('.search-form').attr('role', 'search');
    },

    /**
     * Handle add to cart functionality
     */
    handleAddToCart: function(e) {
      var $this = $(this);
      var $product = $this.closest('.product');
      
      // Add loading class
      $this.addClass('loading');
      
      // Send AJAX request
      $.ajax({
        url: aqualuxe_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_add_to_cart',
          product_id: $this.data('product_id'),
          quantity: $this.data('quantity') || 1,
          nonce: aqualuxe_ajax.nonce
        },
        success: function(response) {
          if (response.success) {
            // Show success message
            AquaLuxe.showMessage(response.data.message, 'success');
          } else {
            // Show error message
            AquaLuxe.showMessage(response.data.message, 'error');
          }
        },
        error: function() {
          // Show error message
          AquaLuxe.showMessage('An error occurred. Please try again.', 'error');
        },
        complete: function() {
          // Remove loading class
          $this.removeClass('loading');
        }
      });
    },

    /**
     * Handle quick view functionality
     */
    handleQuickView: function(e) {
      e.preventDefault();
      
      var $this = $(this);
      var productId = $this.data('product_id');
      
      // Add loading class
      $this.addClass('loading');
      
      // Show quick view modal
      AquaLuxe.showQuickView(productId);
    },

    /**
     * Show quick view modal
     */
    showQuickView: function(productId) {
      // Create modal HTML
      var modalHtml = '<div class="quick-view-modal" id="quick-view-modal">' +
        '<div class="modal-overlay"></div>' +
        '<div class="modal-content">' +
          '<button class="modal-close">&times;</button>' +
          '<div class="modal-body">' +
            '<div class="loading-spinner">Loading...</div>' +
          '</div>' +
        '</div>' +
      '</div>';
      
      // Append modal to body
      $('body').append(modalHtml);
      
      // Load product content via AJAX
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
            $('.modal-body').html(response.data.html);
          } else {
            $('.modal-body').html('<p>Error loading product.</p>');
          }
        },
        error: function() {
          $('.modal-body').html('<p>Error loading product.</p>');
        }
      });
      
      // Bind close events
      $('.modal-close, .modal-overlay').on('click', function() {
        $('#quick-view-modal').remove();
      });
    },

    /**
     * Show message to user
     */
    showMessage: function(message, type) {
      // Create message HTML
      var messageHtml = '<div class="aqualuxe-message aqualuxe-message-' + type + '">' + message + '</div>';
      
      // Append message to body
      $('body').append(messageHtml);
      
      // Show message
      $('.aqualuxe-message').fadeIn();
      
      // Auto hide message after 3 seconds
      setTimeout(function() {
        $('.aqualuxe-message').fadeOut(function() {
          $(this).remove();
        });
      }, 3000);
    }
  };

})(jQuery);