/**
 * WooCommerce Scripts
 *
 * Main WooCommerce scripts for AquaLuxe theme
 */

(function($) {
  'use strict';

  // Global variables
  const AquaLuxeWooCommerce = {
    /**
     * Initialize scripts
     */
    init: function() {
      this.initQuantityButtons();
      this.initMiniCart();
      this.initProductGallery();
      this.initProductTabs();
      this.initProductCountdown();
      this.initQuickView();
      this.initWishlist();
      this.initCompare();
      this.initSizeGuide();
      this.initFilterSidebar();
      this.initVariationSwatches();
      this.initStickyProductSummary();
      this.initAjaxAddToCart();
      this.initCurrencySwitcher();
    },

    /**
     * Initialize quantity buttons
     */
    initQuantityButtons: function() {
      // Quantity buttons
      $(document).on('click', '.quantity-button', function() {
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

      // Update quantity on input change
      $(document).on('change', '.qty', function() {
        const $input = $(this);
        const min = parseFloat($input.attr('min'));
        const max = parseFloat($input.attr('max'));
        let val = parseFloat($input.val());

        if (min && val < min) {
          $input.val(min).trigger('change');
        } else if (max && val > max) {
          $input.val(max).trigger('change');
        }
      });
    },

    /**
     * Initialize mini cart
     */
    initMiniCart: function() {
      // Open mini cart
      $(document).on('click', '.cart-contents', function(e) {
        e.preventDefault();
        $('.mini-cart').addClass('active');
        $('body').addClass('mini-cart-open');
      });

      // Close mini cart
      $(document).on('click', '.mini-cart__close, .mini-cart__overlay', function(e) {
        e.preventDefault();
        $('.mini-cart').removeClass('active');
        $('body').removeClass('mini-cart-open');
      });

      // Close mini cart on ESC key
      $(document).on('keyup', function(e) {
        if (e.key === 'Escape' && $('.mini-cart').hasClass('active')) {
          $('.mini-cart').removeClass('active');
          $('body').removeClass('mini-cart-open');
        }
      });

      // Update mini cart on fragment refresh
      $(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function() {
        AquaLuxeWooCommerce.updateMiniCartHeight();
      });

      // Update mini cart height
      this.updateMiniCartHeight();
    },

    /**
     * Update mini cart height
     */
    updateMiniCartHeight: function() {
      const $miniCart = $('.mini-cart__body');
      if ($miniCart.length) {
        const windowHeight = window.innerHeight;
        const headerHeight = $('.mini-cart__header').outerHeight() || 0;
        const maxHeight = windowHeight - headerHeight;
        $miniCart.css('max-height', maxHeight + 'px');
      }
    },

    /**
     * Initialize product gallery
     */
    initProductGallery: function() {
      // Product gallery
      if (typeof $.fn.wc_product_gallery !== 'undefined') {
        $('.woocommerce-product-gallery').each(function() {
          $(this).wc_product_gallery({
            flexslider: {
              animation: 'slide',
              animationLoop: false,
              controlNav: 'thumbnails',
              directionNav: true,
              slideshow: false,
              smoothHeight: true,
            },
            zoom: {
              enabled: true,
              duration: 300,
              target: false,
            },
          });
        });
      }

      // Product gallery thumbnails
      $('.woocommerce-product-gallery__wrapper').on('click', '.woocommerce-product-gallery__image a', function(e) {
        e.preventDefault();
      });

      // Quick view gallery
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
     * Initialize product tabs
     */
    initProductTabs: function() {
      // Product tabs
      $('.woocommerce-tabs').each(function() {
        const $tabs = $(this);
        const $tabLinks = $tabs.find('.tabs li a');
        const $tabPanels = $tabs.find('.woocommerce-Tabs-panel');

        // Hide all panels except the first one
        $tabPanels.not(':first').hide();

        // Set active class on first tab
        $tabLinks.first().parent().addClass('active');

        // Tab click event
        $tabLinks.on('click', function(e) {
          e.preventDefault();

          const $this = $(this);
          const $target = $($this.attr('href'));

          // Remove active class from all tabs
          $tabLinks.parent().removeClass('active');

          // Add active class to current tab
          $this.parent().addClass('active');

          // Hide all panels
          $tabPanels.hide();

          // Show target panel
          $target.show();

          // Update URL hash
          if (history.pushState) {
            history.pushState(null, null, $this.attr('href'));
          } else {
            location.hash = $this.attr('href');
          }
        });

        // Check URL hash on page load
        if (location.hash) {
          const $activeTab = $tabs.find('.tabs li a[href="' + location.hash + '"]');
          if ($activeTab.length) {
            $activeTab.trigger('click');
          }
        }
      });
    },

    /**
     * Initialize product countdown
     */
    initProductCountdown: function() {
      // Product countdown
      $('.product-countdown').each(function() {
        const $countdown = $(this);
        const endTime = parseInt($countdown.data('end-time'), 10) * 1000;
        const $days = $countdown.find('.product-countdown__days .product-countdown__value');
        const $hours = $countdown.find('.product-countdown__hours .product-countdown__value');
        const $minutes = $countdown.find('.product-countdown__minutes .product-countdown__value');
        const $seconds = $countdown.find('.product-countdown__seconds .product-countdown__value');

        // Update countdown
        function updateCountdown() {
          const now = new Date().getTime();
          const distance = endTime - now;

          if (distance < 0) {
            clearInterval(interval);
            $countdown.hide();
            return;
          }

          const days = Math.floor(distance / (1000 * 60 * 60 * 24));
          const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          const seconds = Math.floor((distance % (1000 * 60)) / 1000);

          $days.text(days < 10 ? '0' + days : days);
          $hours.text(hours < 10 ? '0' + hours : hours);
          $minutes.text(minutes < 10 ? '0' + minutes : minutes);
          $seconds.text(seconds < 10 ? '0' + seconds : seconds);
        }

        // Initial update
        updateCountdown();

        // Update every second
        const interval = setInterval(updateCountdown, 1000);
      });
    },

    /**
     * Initialize quick view
     */
    initQuickView: function() {
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
              AquaLuxeWooCommerce.initQuantityButtons();
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
     * Initialize wishlist
     */
    initWishlist: function() {
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
              } else {
                $button.removeClass('added');
                $button.find('span').text(aqualuxe_wishlist.i18n_add_to_wishlist);
              }

              // Update wishlist count
              $('.wishlist-count').text(response.data.count);
            }
          },
        });
      });

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
            }
          },
        });
      });
    },

    /**
     * Initialize compare
     */
    initCompare: function() {
      // Compare button click
      $(document).on('click', '.compare-button', function(e) {
        e.preventDefault();

        const $button = $(this);
        const productId = $button.data('product-id');

        // Send AJAX request
        $.ajax({
          url: aqualuxe_compare.ajax_url,
          type: 'POST',
          data: {
            action: 'aqualuxe_compare',
            product_id: productId,
            nonce: aqualuxe_compare.nonce,
          },
          success: function(response) {
            if (response.success) {
              if (response.data.action === 'added') {
                $button.addClass('added');
                $button.find('span').text(aqualuxe_compare.i18n_remove_from_compare);
              } else {
                $button.removeClass('added');
                $button.find('span').text(aqualuxe_compare.i18n_add_to_compare);
              }

              // Update compare count
              $('.compare-count').text(response.data.count);
            }
          },
        });
      });

      // Remove from compare
      $(document).on('click', '.remove-from-compare', function(e) {
        e.preventDefault();

        const $button = $(this);
        const productId = $button.data('product-id');
        const $column = $button.closest('th').index();
        const $table = $button.closest('table');

        // Send AJAX request
        $.ajax({
          url: aqualuxe_compare.ajax_url,
          type: 'POST',
          data: {
            action: 'aqualuxe_compare',
            product_id: productId,
            nonce: aqualuxe_compare.nonce,
          },
          success: function(response) {
            if (response.success) {
              // Remove column from table
              $table.find('tr').each(function() {
                $(this).find('td, th').eq($column).remove();
              });

              // Check if compare is empty
              if ($table.find('tr:first th').length <= 1) {
                $('.compare-products').replaceWith(
                  '<div class="compare-empty">' +
                  '<p>' + aqualuxe_compare.i18n_empty_compare + '</p>' +
                  '<p><a class="button" href="' + aqualuxe_compare.shop_url + '">' + aqualuxe_compare.i18n_return_to_shop + '</a></p>' +
                  '</div>'
                );
              }

              // Update compare count
              $('.compare-count').text(response.data.count);
            }
          },
        });
      });
    },

    /**
     * Initialize size guide
     */
    initSizeGuide: function() {
      // Size guide button click
      $(document).on('click', '.size-guide-button', function(e) {
        e.preventDefault();

        const $modal = $('#size-guide-modal');
        $modal.attr('aria-hidden', 'false');
        $('body').addClass('modal-open');
      });
    },

    /**
     * Initialize filter sidebar
     */
    initFilterSidebar: function() {
      // Filter button click
      $(document).on('click', '.filter-button', function(e) {
        e.preventDefault();

        const $sidebar = $('.filter-sidebar');
        const $overlay = $('.filter-sidebar__overlay');

        $sidebar.addClass('active');
        $overlay.addClass('active');
        $('body').addClass('filter-sidebar-open');
      });

      // Close filter sidebar
      $(document).on('click', '.filter-sidebar__close, .filter-sidebar__overlay', function(e) {
        e.preventDefault();

        const $sidebar = $('.filter-sidebar');
        const $overlay = $('.filter-sidebar__overlay');

        $sidebar.removeClass('active');
        $overlay.removeClass('active');
        $('body').removeClass('filter-sidebar-open');
      });

      // Close filter sidebar on ESC key
      $(document).on('keyup', function(e) {
        if (e.key === 'Escape' && $('.filter-sidebar').hasClass('active')) {
          $('.filter-sidebar').removeClass('active');
          $('.filter-sidebar__overlay').removeClass('active');
          $('body').removeClass('filter-sidebar-open');
        }
      });

      // Apply filters
      $(document).on('click', '.filter-sidebar__apply', function(e) {
        e.preventDefault();

        // Close filter sidebar
        $('.filter-sidebar').removeClass('active');
        $('.filter-sidebar__overlay').removeClass('active');
        $('body').removeClass('filter-sidebar-open');

        // Submit filter form
        $('.widget_price_filter form').submit();
      });
    },

    /**
     * Initialize variation swatches
     */
    initVariationSwatches: function() {
      // Check if variation swatches are enabled
      if ($('.variations_form').length && $('.variations_form').find('.variation-swatch').length) {
        // Variation swatch click
        $(document).on('click', '.variation-swatch', function(e) {
          e.preventDefault();

          const $swatch = $(this);
          const $select = $swatch.closest('.value').find('select');
          const value = $swatch.data('value');

          // Set select value
          $select.val(value).trigger('change');

          // Update active state
          $swatch.siblings().removeClass('active');
          $swatch.addClass('active');
        });

        // Update swatches on variation change
        $('.variations_form').on('woocommerce_variation_has_changed', function() {
          const $form = $(this);

          $form.find('.variations select').each(function() {
            const $select = $(this);
            const value = $select.val();
            const $swatches = $select.closest('.value').find('.variation-swatch');

            // Update active state
            $swatches.removeClass('active');
            $swatches.filter('[data-value="' + value + '"]').addClass('active');
          });
        });
      }
    },

    /**
     * Initialize sticky product summary
     */
    initStickyProductSummary: function() {
      // Check if sticky summary is enabled
      if ($('.product-single__summary').length && window.innerWidth >= 992) {
        const $summary = $('.product-single__summary');
        const $gallery = $('.product-single__gallery');
        const offset = 30;

        // Check if gallery is taller than summary
        if ($gallery.outerHeight() > $summary.outerHeight()) {
          // Initialize sticky kit
          $summary.stick_in_parent({
            offset_top: offset,
            recalc_every: 1,
          });

          // Update on window resize
          $(window).on('resize', function() {
            if (window.innerWidth < 992) {
              $summary.trigger('sticky_kit:detach');
            } else {
              $summary.stick_in_parent({
                offset_top: offset,
                recalc_every: 1,
              });
            }
          });
        }
      }
    },

    /**
     * Initialize AJAX add to cart
     */
    initAjaxAddToCart: function() {
      // AJAX add to cart on single product page
      $('.single_add_to_cart_button').on('click', function(e) {
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
              // Show success message
              if (!response.data.redirect) {
                // Update fragments
                if (response.data.fragments) {
                  $.each(response.data.fragments, function(key, value) {
                    $(key).replaceWith(value);
                  });
                }

                // Trigger event
                $(document.body).trigger('wc_fragment_refresh');
                $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, $button]);

                // Show mini cart
                $('.mini-cart').addClass('active');
                $('body').addClass('mini-cart-open');
              } else {
                // Redirect to cart page
                window.location.href = response.data.redirect;
              }
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
     * Initialize currency switcher
     */
    initCurrencySwitcher: function() {
      // Currency switcher dropdown
      const $currencySwitcher = $('.currency-switcher');
      
      if ($currencySwitcher.length) {
        const $toggle = $currencySwitcher.find('.currency-switcher__toggle');
        const $dropdown = $currencySwitcher.find('.currency-switcher__list');
        
        // Toggle dropdown
        $toggle.on('click', function(e) {
          e.preventDefault();
          
          const expanded = $toggle.attr('aria-expanded') === 'true';
          
          $toggle.attr('aria-expanded', !expanded);
          $dropdown.attr('hidden', expanded);
          
          // Close dropdown when clicking outside
          if (!expanded) {
            $(document).on('click.currency-switcher', function(event) {
              if (!$currencySwitcher.is(event.target) && $currencySwitcher.has(event.target).length === 0) {
                $toggle.attr('aria-expanded', 'false');
                $dropdown.attr('hidden', true);
                $(document).off('click.currency-switcher');
              }
            });
          }
        });
        
        // Close dropdown on escape key
        $dropdown.on('keydown', function(event) {
          if (event.key === 'Escape') {
            $toggle.attr('aria-expanded', 'false');
            $dropdown.attr('hidden', true);
            $toggle.focus();
          }
        });
      }
    },
  };

  // Initialize on document ready
  $(document).ready(function() {
    AquaLuxeWooCommerce.init();
  });

})(jQuery);