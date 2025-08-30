/**
 * Quick View Module
 * 
 * Handles quick view functionality for products
 */

(function($) {
  'use strict';

  const AquaLuxeQuickView = {
    /**
     * Initialize quick view
     */
    init: function() {
      this.initQuickViewButtons();
      this.initQuickViewModal();
      this.initQuickViewEvents();
    },

    /**
     * Initialize quick view buttons
     */
    initQuickViewButtons: function() {
      // Add quick view buttons to products
      if ($('.products .product').length && !$('.products .product .quick-view-button').length) {
        $('.products .product').each(function() {
          const $product = $(this);
          const productId = $product.data('product-id') || $product.find('.add_to_cart_button').data('product_id');
          
          if (productId) {
            // Create quick view button
            const $quickViewButton = $('<a href="#" class="button quick-view-button" data-product-id="' + productId + '">' + aqualuxe_quick_view.i18n_quick_view + '</a>');
            
            // Add button to product actions
            if ($product.find('.product-card__actions').length) {
              $product.find('.product-card__actions').append($quickViewButton);
            } else {
              $product.find('.product-card__footer').append($quickViewButton);
            }
          }
        });
      }
    },

    /**
     * Initialize quick view modal
     */
    initQuickViewModal: function() {
      // Create modal if it doesn't exist
      if (!$('#quick-view-modal').length) {
        const modal = `
          <div id="quick-view-modal" class="modal quick-view-modal" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1" data-close></div>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="quick-view-title">
              <div class="modal__header">
                <h2 id="quick-view-title" class="modal__title">${aqualuxe_quick_view.i18n_quick_view}</h2>
                <button class="modal__close" aria-label="${aqualuxe_quick_view.i18n_close}" data-close>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                    <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
              <div class="modal__content">
                <div class="quick-view-content"></div>
              </div>
            </div>
          </div>
        `;
        
        $('body').append(modal);
      }
    },

    /**
     * Initialize quick view events
     */
    initQuickViewEvents: function() {
      // Quick view button click
      $(document).on('click', '.quick-view-button', function(e) {
        e.preventDefault();

        const $button = $(this);
        const productId = $button.data('product-id');
        const $modal = $('#quick-view-modal');
        const $content = $modal.find('.quick-view-content');

        // Show loading
        $content.html('<div class="quick-view-loading">' + aqualuxe_quick_view.i18n_loading + '</div>');

        // Open modal
        $modal.attr('aria-hidden', 'false');
        $('body').addClass('modal-open');

        // Get product data
        $.ajax({
          url: aqualuxe_quick_view.ajax_url,
          type: 'POST',
          data: {
            action: 'aqualuxe_quick_view',
            product_id: productId,
            nonce: aqualuxe_quick_view.nonce,
          },
          success: function(response) {
            if (response.success) {
              $content.html(response.data.content);
              AquaLuxeQuickView.initQuickViewContent();
            } else {
              $content.html('<div class="quick-view-error">' + aqualuxe_quick_view.i18n_error + '</div>');
            }
          },
          error: function() {
            $content.html('<div class="quick-view-error">' + aqualuxe_quick_view.i18n_error + '</div>');
          },
        });
      });

      // Close modal
      $(document).on('click', '.modal__close, .modal__overlay', function(e) {
        e.preventDefault();
        const $modal = $(this).closest('.modal');
        $modal.attr('aria-hidden', 'true');
        $('body').removeClass('modal-open');
      });

      // Close modal on ESC key
      $(document).on('keyup', function(e) {
        if (e.key === 'Escape' && $('.modal[aria-hidden="false"]').length) {
          $('.modal[aria-hidden="false"]').attr('aria-hidden', 'true');
          $('body').removeClass('modal-open');
        }
      });
    },

    /**
     * Initialize quick view content
     */
    initQuickViewContent: function() {
      // Initialize quantity buttons
      this.initQuantityButtons();
      
      // Initialize gallery
      this.initGallery();
      
      // Initialize add to cart
      this.initAddToCart();
      
      // Initialize variations
      this.initVariations();
    },

    /**
     * Initialize quantity buttons
     */
    initQuantityButtons: function() {
      // Quantity buttons
      $(document).on('click', '.quick-view-product .quantity-button', function() {
        const $button = $(this);
        const $input = $button.parent().find('.qty');
        const oldValue = parseFloat($input.val());
        let newVal = oldValue;

        if ($button.hasClass('quantity-up')) {
          const max = parseFloat($input.attr('max'));
          if (max && oldValue >= max) {
            newVal = max;
          } else {
            newVal = oldValue + 1;
          }
        } else {
          const min = parseFloat($input.attr('min'));
          if (min && oldValue <= min) {
            newVal = min;
          } else if (oldValue > 0) {
            newVal = oldValue - 1;
          }
        }

        $input.val(newVal).trigger('change');
      });
    },

    /**
     * Initialize gallery
     */
    initGallery: function() {
      // Gallery thumbnails
      $(document).on('click', '.quick-view-product__gallery-image', function() {
        const $this = $(this);
        const $mainImage = $this.closest('.quick-view-product__images').find('.quick-view-product__image');
        const src = $this.attr('src');
        const srcset = $this.attr('srcset');

        $mainImage.attr('src', src);
        if (srcset) {
          $mainImage.attr('srcset', srcset);
        }

        $this.siblings().removeClass('active');
        $this.addClass('active');
      });
    },

    /**
     * Initialize add to cart
     */
    initAddToCart: function() {
      // AJAX add to cart
      $(document).on('click', '.quick-view-product .single_add_to_cart_button', function(e) {
        const $button = $(this);
        const $form = $button.closest('form');

        // Check if button is disabled or form is invalid
        if ($button.is('.disabled') || $form.length === 0) {
          return;
        }

        // Check if it's a variable product
        if ($form.find('input[name="variation_id"]').length > 0 && $form.find('input[name="variation_id"]').val() === '0') {
          return;
        }

        e.preventDefault();

        // Get form data
        const formData = new FormData($form[0]);
        formData.append('action', 'aqualuxe_ajax_add_to_cart');
        formData.append('nonce', aqualuxe_ajax_add_to_cart.nonce);

        // Add loading state
        $button.addClass('loading');

        // Send AJAX request
        $.ajax({
          url: aqualuxe_ajax_add_to_cart.ajax_url,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            // Remove loading state
            $button.removeClass('loading');

            if (response.success) {
              // Update fragments
              if (response.data.fragments) {
                $.each(response.data.fragments, function(key, value) {
                  $(key).replaceWith(value);
                });
              }

              // Trigger event
              $(document.body).trigger('wc_fragment_refresh');
              $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, $button]);

              // Close quick view modal
              $('#quick-view-modal').attr('aria-hidden', 'true');
              $('body').removeClass('modal-open');

              // Show mini cart
              $('.mini-cart').addClass('active');
              $('body').addClass('mini-cart-open');
            } else {
              // Show error message
              if ($form.find('.woocommerce-error').length === 0) {
                $form.prepend('<div class="woocommerce-error">' + aqualuxe_ajax_add_to_cart.i18n_error + '</div>');
              }
            }
          },
          error: function() {
            // Remove loading state
            $button.removeClass('loading');

            // Show error message
            if ($form.find('.woocommerce-error').length === 0) {
              $form.prepend('<div class="woocommerce-error">' + aqualuxe_ajax_add_to_cart.i18n_error + '</div>');
            }
          },
        });
      });
    },

    /**
     * Initialize variations
     */
    initVariations: function() {
      // Initialize variation form
      const $variationForm = $('.quick-view-product .variations_form');
      
      if ($variationForm.length) {
        $variationForm.wc_variation_form();
        
        // Trigger change event on select
        $variationForm.find('.variations select').trigger('change');
      }
    },
  };

  // Initialize on document ready
  $(document).ready(function() {
    AquaLuxeQuickView.init();
  });

})(jQuery);