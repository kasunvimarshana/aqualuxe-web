/**
 * AquaLuxe WooCommerce JavaScript
 *
 * JavaScript functionality for WooCommerce features.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
  'use strict';

  // AquaLuxe WooCommerce object
  const AquaLuxeWooCommerce = {
    /**
     * Initialize
     */
    init: function() {
      this.initMiniCart();
      this.initQuantityButtons();
      this.initProductGallery();
      this.initQuickView();
      this.initWishlist();
      this.initStickyAddToCart();
      this.initShopFilters();
      this.initAjaxAddToCart();
      this.initCheckoutSteps();
      this.initPriceSlider();
    },

    /**
     * Initialize mini cart
     */
    initMiniCart: function() {
      // Toggle mini cart dropdown
      $('.mini-cart-button').on('click', function(e) {
        e.preventDefault();
        $('.mini-cart-dropdown').toggleClass('active');
        $('.mini-cart-overlay').toggleClass('active');
      });

      // Close mini cart when clicking outside
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.mini-cart').length) {
          $('.mini-cart-dropdown').removeClass('active');
          $('.mini-cart-overlay').removeClass('active');
        }
      });

      // Close mini cart when clicking close button
      $('.mini-cart-close').on('click', function(e) {
        e.preventDefault();
        $('.mini-cart-dropdown').removeClass('active');
        $('.mini-cart-overlay').removeClass('active');
      });

      // Off-canvas mini cart
      $('.off-canvas-mini-cart-toggle').on('click', function(e) {
        e.preventDefault();
        $('.off-canvas-mini-cart').addClass('active');
        $('.mini-cart-overlay').addClass('active');
      });

      // Close off-canvas mini cart
      $('.off-canvas-mini-cart-close').on('click', function(e) {
        e.preventDefault();
        $('.off-canvas-mini-cart').removeClass('active');
        $('.mini-cart-overlay').removeClass('active');
      });

      // Close off-canvas mini cart when clicking overlay
      $('.mini-cart-overlay').on('click', function() {
        $('.off-canvas-mini-cart').removeClass('active');
        $('.mini-cart-overlay').removeClass('active');
      });
    },

    /**
     * Initialize quantity buttons
     */
    initQuantityButtons: function() {
      // Quantity plus button
      $(document).on('click', '.quantity-plus', function(e) {
        e.preventDefault();
        const input = $(this).siblings('input.qty');
        const val = parseInt(input.val());
        const max = parseInt(input.attr('max'));
        
        if (max && val >= max) {
          input.val(max);
        } else {
          input.val(val + 1);
        }
        
        input.trigger('change');
      });

      // Quantity minus button
      $(document).on('click', '.quantity-minus', function(e) {
        e.preventDefault();
        const input = $(this).siblings('input.qty');
        const val = parseInt(input.val());
        const min = parseInt(input.attr('min'));
        
        if (min && val <= min) {
          input.val(min);
        } else if (val > 1) {
          input.val(val - 1);
        }
        
        input.trigger('change');
      });

      // Add quantity buttons to inputs
      this.addQuantityButtons();
      
      // Re-add quantity buttons after cart update
      $(document.body).on('updated_cart_totals', function() {
        AquaLuxeWooCommerce.addQuantityButtons();
      });
    },

    /**
     * Add quantity buttons to inputs
     */
    addQuantityButtons: function() {
      $('.quantity:not(.buttons-added)').each(function() {
        const $this = $(this);
        const input = $this.find('input.qty');
        
        if (input.length) {
          input.wrap('<div class="quantity-wrapper"></div>');
          input.before('<button type="button" class="quantity-minus">-</button>');
          input.after('<button type="button" class="quantity-plus">+</button>');
          $this.addClass('buttons-added');
        }
      });
    },

    /**
     * Initialize product gallery
     */
    initProductGallery: function() {
      // Check if product gallery exists and WooCommerce zoom/lightbox/slider is enabled
      if ($('.woocommerce-product-gallery').length) {
        // Add additional functionality if needed
        $('.woocommerce-product-gallery').on('wc-product-gallery-after-init', function() {
          // Custom gallery functionality
        });
      }
    },

    /**
     * Initialize quick view
     */
    initQuickView: function() {
      // Check if quick view is enabled
      if (!aqualuxeWooCommerce.quickView) {
        return;
      }

      // Quick view button click
      $(document).on('click', '.quick-view-button', function(e) {
        e.preventDefault();
        
        const productId = $(this).data('product-id');
        const $modal = $('#quick-view-modal');
        const $content = $modal.find('.quick-view-modal-content');
        
        // Show loading
        $content.html('<div class="quick-view-loading">Loading...</div>');
        $modal.attr('aria-hidden', 'false').addClass('active');
        
        // Get product data
        $.ajax({
          url: aqualuxeWooCommerce.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_quick_view',
            product_id: productId,
            nonce: aqualuxeWooCommerce.nonce
          },
          success: function(response) {
            if (response.success) {
              $content.html(response.data.html);
              
              // Initialize quantity buttons
              AquaLuxeWooCommerce.addQuantityButtons();
              
              // Initialize gallery if needed
              if ($content.find('.woocommerce-product-gallery').length) {
                $content.find('.woocommerce-product-gallery').each(function() {
                  $(this).wc_product_gallery();
                });
              }
              
              // Initialize variations if needed
              if ($content.find('.variations_form').length) {
                $content.find('.variations_form').each(function() {
                  $(this).wc_variation_form();
                });
              }
            } else {
              $content.html('<div class="quick-view-error">Error loading product.</div>');
            }
          },
          error: function() {
            $content.html('<div class="quick-view-error">Error loading product.</div>');
          }
        });
      });

      // Close quick view
      $(document).on('click', '.quick-view-modal-close, .quick-view-modal-backdrop', function(e) {
        e.preventDefault();
        $('#quick-view-modal').attr('aria-hidden', 'true').removeClass('active');
      });

      // Close quick view with escape key
      $(document).on('keyup', function(e) {
        if (e.key === 'Escape' && $('#quick-view-modal').hasClass('active')) {
          $('#quick-view-modal').attr('aria-hidden', 'true').removeClass('active');
        }
      });
    },

    /**
     * Initialize wishlist
     */
    initWishlist: function() {
      // Check if wishlist is enabled
      if (!aqualuxeWooCommerce.wishlist) {
        return;
      }

      // Wishlist button click
      $(document).on('click', '.wishlist-button', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        // Add loading state
        $button.addClass('loading');
        
        // Update wishlist
        $.ajax({
          url: aqualuxeWooCommerce.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_wishlist',
            product_id: productId,
            nonce: aqualuxeWooCommerce.nonce
          },
          success: function(response) {
            if (response.success) {
              // Update button state
              $button.removeClass('loading');
              
              if (response.data.in_wishlist) {
                $button.addClass('in-wishlist');
                $button.find('span').text(aqualuxeWooCommerce.i18n.removeFromWishlist);
              } else {
                $button.removeClass('in-wishlist');
                $button.find('span').text(aqualuxeWooCommerce.i18n.addToWishlist);
              }
              
              // Update wishlist count
              $('.wishlist-count').text(response.data.wishlist_count);
              
              // Show message
              AquaLuxeWooCommerce.showMessage(response.data.message);
            }
          },
          error: function() {
            $button.removeClass('loading');
            AquaLuxeWooCommerce.showMessage('Error updating wishlist.', 'error');
          }
        });
      });
    },

    /**
     * Initialize sticky add to cart
     */
    initStickyAddToCart: function() {
      // Check if sticky add to cart is enabled
      if (!aqualuxeWooCommerce.stickyAddToCart) {
        return;
      }

      const $stickyAddToCart = $('.sticky-add-to-cart');
      
      if ($stickyAddToCart.length) {
        const $productSummary = $('.product .summary');
        
        if ($productSummary.length) {
          $(window).on('scroll', function() {
            const windowTop = $(window).scrollTop();
            const summaryTop = $productSummary.offset().top;
            const summaryBottom = summaryTop + $productSummary.outerHeight();
            
            if (windowTop > summaryBottom) {
              $stickyAddToCart.addClass('visible');
            } else {
              $stickyAddToCart.removeClass('visible');
            }
          });
        }
      }
    },

    /**
     * Initialize shop filters
     */
    initShopFilters: function() {
      // Toggle shop filters
      $('.shop-filters-toggle').on('click', function() {
        const $button = $(this);
        const $content = $('.shop-filters-content');
        const isExpanded = $button.attr('aria-expanded') === 'true';
        
        $button.attr('aria-expanded', !isExpanded);
        $content.toggleClass('hidden');
      });

      // Price slider
      this.initPriceSlider();
    },

    /**
     * Initialize price slider
     */
    initPriceSlider: function() {
      // Check if price slider exists
      if ($('.price_slider').length) {
        // WooCommerce price slider is initialized automatically
        // Add custom functionality if needed
      }
    },

    /**
     * Initialize AJAX add to cart
     */
    initAjaxAddToCart: function() {
      // AJAX add to cart on single product page
      $('form.cart').on('submit', function(e) {
        // Only if AJAX add to cart is enabled for single products
        if ($('body').hasClass('single-product') && aqualuxeWooCommerce.ajaxAddToCart) {
          e.preventDefault();
          
          const $form = $(this);
          const $button = $form.find('button[type="submit"]');
          
          // Add loading state
          $button.addClass('loading');
          
          // Get form data
          const formData = new FormData($form[0]);
          formData.append('action', 'aqualuxe_ajax_add_to_cart');
          formData.append('nonce', aqualuxeWooCommerce.nonce);
          
          // Add to cart
          $.ajax({
            url: aqualuxeWooCommerce.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
              $button.removeClass('loading');
              
              if (response.success) {
                // Update fragments
                if (response.data.fragments) {
                  $.each(response.data.fragments, function(key, value) {
                    $(key).replaceWith(value);
                  });
                }
                
                // Show message
                AquaLuxeWooCommerce.showMessage(response.data.message);
                
                // Trigger event
                $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, $button]);
              } else {
                AquaLuxeWooCommerce.showMessage(response.data.message, 'error');
              }
            },
            error: function() {
              $button.removeClass('loading');
              AquaLuxeWooCommerce.showMessage('Error adding to cart.', 'error');
            }
          });
        }
      });
    },

    /**
     * Initialize checkout steps
     */
    initCheckoutSteps: function() {
      // Check if multi-step checkout is enabled
      if ($('.multi-step-checkout').length) {
        const $steps = $('.checkout-step');
        const $contents = $('.checkout-step-content');
        const $navigation = $('.checkout-navigation');
        
        // Step click
        $steps.on('click', function() {
          const $step = $(this);
          const stepId = $step.data('step');
          
          // Only allow clicking on completed steps or the next step
          if ($step.hasClass('completed') || $step.hasClass('active')) {
            // Update active step
            $steps.removeClass('active');
            $step.addClass('active');
            
            // Update active content
            $contents.removeClass('active');
            $('#' + stepId).addClass('active');
            
            // Update navigation
            AquaLuxeWooCommerce.updateCheckoutNavigation();
          }
        });
        
        // Next button click
        $navigation.on('click', '.next', function(e) {
          e.preventDefault();
          
          const $activeStep = $steps.filter('.active');
          const $nextStep = $activeStep.next('.checkout-step');
          
          if ($nextStep.length) {
            // Validate current step
            if (AquaLuxeWooCommerce.validateCheckoutStep($activeStep)) {
              // Mark current step as completed
              $activeStep.removeClass('active').addClass('completed');
              
              // Activate next step
              $nextStep.addClass('active');
              
              // Update active content
              $contents.removeClass('active');
              $('#' + $nextStep.data('step')).addClass('active');
              
              // Update navigation
              AquaLuxeWooCommerce.updateCheckoutNavigation();
            }
          }
        });
        
        // Previous button click
        $navigation.on('click', '.prev', function(e) {
          e.preventDefault();
          
          const $activeStep = $steps.filter('.active');
          const $prevStep = $activeStep.prev('.checkout-step');
          
          if ($prevStep.length) {
            // Update active step
            $activeStep.removeClass('active');
            $prevStep.addClass('active');
            
            // Update active content
            $contents.removeClass('active');
            $('#' + $prevStep.data('step')).addClass('active');
            
            // Update navigation
            AquaLuxeWooCommerce.updateCheckoutNavigation();
          }
        });
        
        // Initialize navigation
        AquaLuxeWooCommerce.updateCheckoutNavigation();
      }
    },

    /**
     * Update checkout navigation
     */
    updateCheckoutNavigation: function() {
      const $steps = $('.checkout-step');
      const $activeStep = $steps.filter('.active');
      const $navigation = $('.checkout-navigation');
      const $prevButton = $navigation.find('.prev');
      const $nextButton = $navigation.find('.next');
      
      // Update previous button
      if ($activeStep.prev('.checkout-step').length) {
        $prevButton.show();
      } else {
        $prevButton.hide();
      }
      
      // Update next button
      if ($activeStep.next('.checkout-step').length) {
        $nextButton.text('Continue to ' + $activeStep.next('.checkout-step').text());
      } else {
        $nextButton.text('Place Order');
      }
    },

    /**
     * Validate checkout step
     *
     * @param {jQuery} $step Step element
     * @return {boolean} Whether the step is valid
     */
    validateCheckoutStep: function($step) {
      const stepId = $step.data('step');
      const $content = $('#' + stepId);
      const $inputs = $content.find('input, select, textarea').not('[optional]');
      let isValid = true;
      
      // Check required fields
      $inputs.each(function() {
        const $input = $(this);
        
        if ($input.attr('required') && !$input.val()) {
          isValid = false;
          $input.addClass('error');
        } else {
          $input.removeClass('error');
        }
      });
      
      // Show error message if needed
      if (!isValid) {
        AquaLuxeWooCommerce.showMessage('Please fill in all required fields.', 'error');
      }
      
      return isValid;
    },

    /**
     * Show message
     *
     * @param {string} message Message text
     * @param {string} type Message type (success, error, info)
     */
    showMessage: function(message, type = 'success') {
      // Remove existing messages
      $('.woocommerce-message, .woocommerce-error, .woocommerce-info').remove();
      
      // Create message element
      let $message;
      
      if (type === 'error') {
        $message = $('<div class="woocommerce-error" role="alert"><li>' + message + '</li></div>');
      } else if (type === 'info') {
        $message = $('<div class="woocommerce-info">' + message + '</div>');
      } else {
        $message = $('<div class="woocommerce-message" role="status">' + message + '</div>');
      }
      
      // Add message to page
      $('.woocommerce-notices-wrapper').first().append($message);
      
      // Scroll to message
      $('html, body').animate({
        scrollTop: ($message.offset().top - 100)
      }, 500);
      
      // Remove message after 5 seconds
      setTimeout(function() {
        $message.fadeOut(500, function() {
          $(this).remove();
        });
      }, 5000);
    }
  };

  // Initialize on document ready
  $(document).ready(function() {
    AquaLuxeWooCommerce.init();
  });

})(jQuery);