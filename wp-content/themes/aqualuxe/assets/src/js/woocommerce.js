/**
 * AquaLuxe WooCommerce JavaScript
 *
 * Handles WooCommerce-specific functionality with advanced features
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  const AquaLuxeWooCommerce = {
    /**
     * Initialize WooCommerce functionality
     */
    init() {
      this.initQuickView();
      this.initAjaxAddToCart();
      this.initWishlist();
      this.initProductGallery();
      this.initQuantityButtons();
      this.initCartUpdates();
      this.initCheckoutEnhancements();
      this.initProductFilters();
      this.initAdvancedProductSearch();
      this.initProductComparison();
      this.initCurrencySelector();
      this.initShippingCalculator();
      this.initOrderTracking();
    },

    /**
     * Initialize enhanced quick view functionality
     */
    initQuickView() {
      $(document).on('click', '.quick-view-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        const productId = $btn.data('product-id');

        if (!productId) {
          return;
        }

        // Show loading state
        $btn.addClass('loading').prop('disabled', true);

        // Add loading spinner
        const loadingHtml =
          '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        $btn.html(loadingHtml);

        $.ajax({
          url: aqualuxe_ajax.ajax_url,
          type: 'POST',
          data: {
            action: 'aqualuxe_quick_view',
            product_id: productId,
            nonce: aqualuxe_ajax.nonce,
          },
          success: function (response) {
            if (response.success) {
              AquaLuxeWooCommerce.showQuickViewModal(
                response.data.content,
                response.data.title
              );
            } else {
              AquaLuxeWooCommerce.showNotice(
                'error',
                response.data || 'Failed to load product details.'
              );
            }
          },
          error: function () {
            AquaLuxeWooCommerce.showNotice(
              'error',
              'Failed to load product details.'
            );
          },
          complete: function () {
            $btn.removeClass('loading');
          },
        });
      });
    },

    /**
     * Show quick view modal
     */
    showQuickViewModal(content, title) {
      const modalHtml = `
                <div class="quick-view-modal fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="modal-backdrop absolute inset-0 bg-black bg-opacity-50" data-dismiss="modal"></div>
                    <div class="modal-content relative bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="modal-header flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">${title}</h3>
                            <button type="button" class="modal-close text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" data-dismiss="modal">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body p-6">
                            ${content}
                        </div>
                    </div>
                </div>
            `;

      $('body').append(modalHtml);
      $('body').addClass('modal-open overflow-hidden');

      // Close modal handlers
      $(document).on(
        'click',
        '[data-dismiss="modal"]',
        this.closeQuickViewModal
      );
      $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
          AquaLuxeWooCommerce.closeQuickViewModal();
        }
      });
    },

    /**
     * Close quick view modal
     */
    closeQuickViewModal() {
      $('.quick-view-modal').fadeOut(200, function () {
        $(this).remove();
        $('body').removeClass('modal-open overflow-hidden');
      });
    },

    /**
     * Initialize AJAX add to cart
     */
    initAjaxAddToCart() {
      $(document).on('submit', 'form.cart:not(.no-ajax)', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $form.find('[type="submit"]');
        const originalText = $btn.text();

        const formData = {
          action: 'aqualuxe_add_to_cart',
          product_id:
            $form.find('[name="add-to-cart"]').val() ||
            $form.find('[name="product_id"]').val(),
          quantity: $form.find('[name="quantity"]').val() || 1,
          nonce: aqualuxe_ajax.nonce,
        };

        // Add variation data if exists
        $form.find('[name^="attribute_"]').each(function () {
          formData[$(this).attr('name')] = $(this).val();
        });

        if ($form.find('[name="variation_id"]').length) {
          formData.variation_id = $form.find('[name="variation_id"]').val();
        }

        $btn.prop('disabled', true).text('Adding...');

        $.ajax({
          url: aqualuxe_ajax.ajax_url,
          type: 'POST',
          data: formData,
          success: function (response) {
            if (response.success) {
              AquaLuxeWooCommerce.showNotice('success', response.data.message);
              AquaLuxeWooCommerce.updateCartCount(response.data.cart_count);

              // Close quick view modal if open
              if ($form.closest('.quick-view-modal').length) {
                AquaLuxeWooCommerce.closeQuickViewModal();
              }

              // Trigger WooCommerce event
              $('body').trigger('added_to_cart', [
                response.data,
                formData.product_id,
                $btn,
              ]);
            } else {
              AquaLuxeWooCommerce.showNotice(
                'error',
                response.data || 'Failed to add product to cart.'
              );
            }
          },
          error: function () {
            AquaLuxeWooCommerce.showNotice(
              'error',
              'Failed to add product to cart.'
            );
          },
          complete: function () {
            $btn.prop('disabled', false).text(originalText);
          },
        });
      });
    },

    /**
     * Initialize wishlist functionality
     */
    initWishlist() {
      $(document).on('click', '.wishlist-toggle', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        const productId = $btn.data('product-id');
        const action = $btn.hasClass('in-wishlist') ? 'remove' : 'add';

        $.ajax({
          url: aqualuxe_ajax.ajax_url,
          type: 'POST',
          data: {
            action: 'aqualuxe_wishlist_toggle',
            product_id: productId,
            wishlist_action: action,
            nonce: aqualuxe_ajax.nonce,
          },
          success: function (response) {
            if (response.success) {
              $btn.toggleClass('in-wishlist', response.data.in_wishlist);
              AquaLuxeWooCommerce.showNotice('success', response.data.message);
              AquaLuxeWooCommerce.updateWishlistCount(
                response.data.wishlist_count
              );
            } else {
              AquaLuxeWooCommerce.showNotice(
                'error',
                response.data || 'Failed to update wishlist.'
              );
            }
          },
          error: function () {
            AquaLuxeWooCommerce.showNotice(
              'error',
              'Failed to update wishlist.'
            );
          },
        });
      });
    },

    /**
     * Initialize product gallery enhancements
     */
    initProductGallery() {
      // Zoom functionality
      $('.woocommerce-product-gallery__image')
        .on('mouseenter', function () {
          $(this).addClass('zoomed');
        })
        .on('mouseleave', function () {
          $(this).removeClass('zoomed');
        });

      // Thumbnail navigation
      $('.flex-control-thumbs li').on('click', function () {
        const $thumb = $(this);
        $thumb.siblings().removeClass('active');
        $thumb.addClass('active');
      });
    },

    /**
     * Initialize quantity buttons
     */
    initQuantityButtons() {
      // Add quantity buttons if they don't exist
      $('.woocommerce .quantity:not(.buttons_added)').each(function () {
        const $qty = $(this);
        const $input = $qty.find('.qty, input[type="number"]');

        if ($input.length) {
          $qty.addClass('buttons_added').append(`
                        <button type="button" class="minus">-</button>
                        <button type="button" class="plus">+</button>
                    `);
        }
      });

      // Handle quantity button clicks
      $(document).on(
        'click',
        '.quantity .minus, .quantity .plus',
        function (e) {
          e.preventDefault();

          const $btn = $(this);
          const $qty = $btn.closest('.quantity');
          const $input = $qty.find('.qty, input[type="number"]');
          const currentVal = parseFloat($input.val()) || 0;
          const max = parseFloat($input.attr('max')) || 999;
          const min = parseFloat($input.attr('min')) || 1;
          const step = parseFloat($input.attr('step')) || 1;

          let newVal;

          if ($btn.hasClass('plus')) {
            newVal = currentVal + step;
            newVal = newVal > max ? max : newVal;
          } else {
            newVal = currentVal - step;
            newVal = newVal < min ? min : newVal;
          }

          $input.val(newVal).trigger('change');
        }
      );
    },

    /**
     * Initialize cart updates
     */
    initCartUpdates() {
      // Auto-update cart on quantity change
      $(document).on('change', '.woocommerce-cart-form .qty', function () {
        const $form = $(this).closest('form');
        $form.find('[name="update_cart"]').prop('disabled', false);

        // Auto-submit after delay
        clearTimeout(this.updateTimer);
        this.updateTimer = setTimeout(function () {
          $form.find('[name="update_cart"]').trigger('click');
        }, 1000);
      });

      // Remove item confirmation
      $(document).on('click', '.woocommerce-cart-form .remove', function (e) {
        if (!confirm('Are you sure you want to remove this item?')) {
          e.preventDefault();
        }
      });
    },

    /**
     * Initialize checkout enhancements
     */
    initCheckoutEnhancements() {
      // Billing/shipping address toggle
      $(document).on(
        'change',
        '#ship-to-different-address-checkbox',
        function () {
          $('.shipping_address').slideToggle(200);
        }
      );

      // Form validation enhancement
      $('.woocommerce-checkout').on('submit', function (e) {
        const $form = $(this);
        let isValid = true;

        $form.find('[required]').each(function () {
          const $field = $(this);
          if (!$field.val().trim()) {
            $field.addClass('error');
            isValid = false;
          } else {
            $field.removeClass('error');
          }
        });

        if (!isValid) {
          e.preventDefault();
          AquaLuxeWooCommerce.showNotice(
            'error',
            'Please fill in all required fields.'
          );
          $('html, body').animate(
            {
              scrollTop: $form.find('.error').first().offset().top - 100,
            },
            500
          );
        }
      });
    },

    /**
     * Initialize product filters
     */
    initProductFilters() {
      // Price range filter
      if ($.fn.slider) {
        $('#price-range').slider({
          range: true,
          min: 0,
          max: 1000,
          values: [0, 1000],
          slide: function (event, ui) {
            $('#price-min').val(ui.values[0]);
            $('#price-max').val(ui.values[1]);
          },
        });
      }

      // Filter form submission
      $(document).on(
        'submit',
        '.woocommerce-widget-layered-nav form',
        function (e) {
          e.preventDefault();

          const $form = $(this);
          const formData = $form.serialize();
          const currentUrl = new URL(window.location);

          // Update URL with filter parameters
          const params = new URLSearchParams(formData);
          params.forEach((value, key) => {
            currentUrl.searchParams.set(key, value);
          });

          window.location = currentUrl.toString();
        }
      );
    },

    /**
     * Update cart count in header
     */
    updateCartCount(count) {
      $('.cart-count')
        .text(count)
        .toggleClass('hidden', count === 0);
    },

    /**
     * Update wishlist count
     */
    updateWishlistCount(count) {
      $('.wishlist-count')
        .text(count)
        .toggleClass('hidden', count === 0);
    },

    /**
     * Show notification
     */
    showNotice(type, message) {
      const $notice = $(`
                <div class="wc-notice notice-${type} fixed top-4 right-4 z-50 max-w-sm p-4 bg-white dark:bg-gray-800 border-l-4 border-${type === 'success' ? 'green' : 'red'}-500 shadow-lg rounded-md">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm text-gray-700 dark:text-gray-300">${message}</p>
                        </div>
                        <button type="button" class="ml-4 text-gray-400 hover:text-gray-600" data-dismiss="notice">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `);

      $('body').append($notice);

      // Auto-hide after 5 seconds
      setTimeout(function () {
        $notice.fadeOut(300, function () {
          $(this).remove();
        });
      }, 5000);

      // Manual dismiss
      $notice.find('[data-dismiss="notice"]').on('click', function () {
        $notice.fadeOut(300, function () {
          $(this).remove();
        });
      });
    },

    /**
     * Format price
     */
    formatPrice(price) {
      // This should ideally get the currency symbol from WooCommerce settings
      return '$' + parseFloat(price).toFixed(2);
    },
  };

  // Initialize when document is ready
  $(document).ready(function () {
    // Only initialize if WooCommerce elements are present
    if ($('.woocommerce').length || $('.woocommerce-page').length) {
      AquaLuxeWooCommerce.init();
    }
  });

  // Make globally available
  window.AquaLuxeWooCommerce = AquaLuxeWooCommerce;
})(jQuery);
