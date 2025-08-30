/**
 * WooCommerce specific JavaScript for AquaLuxe theme
 */

(function($) {
  'use strict';

  // DOM elements
  const $window = $(window);
  const $document = $(document);
  const $body = $('body');
  const isWooCommerce = $body.hasClass('woocommerce') || $body.hasClass('woocommerce-page');

  /**
   * Initialize WooCommerce functionality
   */
  function initWooCommerce() {
    if (!isWooCommerce) return;

    setupQuantityButtons();
    setupProductGallery();
    setupQuickView();
    setupWishlist();
    setupAjaxCart();
    setupCartDrawer();
    setupProductFilters();
    setupCurrencySwitcher();
    setupVariationSwatches();
    setupProductTabs();
    setupProductReviews();
  }

  /**
   * Custom quantity buttons
   */
  function setupQuantityButtons() {
    // Add plus and minus buttons to quantity inputs
    function addQuantityButtons() {
      $('.quantity:not(.buttons-added)').addClass('buttons-added').append('<button type="button" class="plus" aria-label="Increase quantity">+</button>').prepend('<button type="button" class="minus" aria-label="Decrease quantity">-</button>');
    }

    // Handle quantity button clicks
    function handleQuantityButtons() {
      $document.on('click', '.plus, .minus', function() {
        const $button = $(this);
        const $input = $button.parent().find('input[type="number"]');
        const oldValue = parseFloat($input.val());
        let newVal = oldValue;
        const step = $input.attr('step') ? parseFloat($input.attr('step')) : 1;
        const min = $input.attr('min') ? parseFloat($input.attr('min')) : 0;
        const max = $input.attr('max') ? parseFloat($input.attr('max')) : '';

        if ($button.hasClass('plus')) {
          if (max && (max === oldValue || oldValue > max)) {
            newVal = max;
          } else {
            newVal = oldValue + step;
          }
        } else {
          if (min && (min === oldValue || oldValue < min)) {
            newVal = min;
          } else if (oldValue > 0) {
            newVal = oldValue - step;
          }
        }

        $input.val(newVal).trigger('change');
      });
    }

    // Initialize quantity buttons
    addQuantityButtons();
    handleQuantityButtons();

    // Re-initialize after AJAX events
    $body.on('updated_cart_totals', addQuantityButtons);
    $document.on('wc_fragments_refreshed', addQuantityButtons);
  }

  /**
   * Product gallery enhancements
   */
  function setupProductGallery() {
    if (!$('.woocommerce-product-gallery').length) return;

    // Initialize zoom on product images
    if ($.fn.zoom) {
      $('.woocommerce-product-gallery__image').each(function() {
        $(this).zoom({
          url: $(this).find('img').attr('data-large_image'),
          touch: false
        });
      });
    }

    // Handle gallery thumbnail clicks
    $('.flex-control-thumbs li img').on('click', function() {
      const index = $(this).parent().index();
      $('.woocommerce-product-gallery__image').eq(index).find('a').trigger('click');
    });

    // Add lightbox functionality
    if ($.fn.magnificPopup) {
      $('.woocommerce-product-gallery__image a').magnificPopup({
        type: 'image',
        gallery: {
          enabled: true,
          navigateByImgClick: true,
        },
        image: {
          titleSrc: function(item) {
            return item.el.attr('title');
          }
        }
      });
    }
  }

  /**
   * Quick view functionality
   */
  function setupQuickView() {
    // Add quick view buttons to products
    $('.product').each(function() {
      const $product = $(this);
      const productId = $product.data('product-id');
      
      if (!productId) return;
      
      const $actions = $product.find('.product-actions');
      
      if ($actions.length) {
        $actions.append('<button class="quick-view-button" data-product-id="' + productId + '" aria-label="Quick view">' +
          '<svg class="svg-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>' +
          '<span>Quick View</span>' +
        '</button>');
      }
    });

    // Handle quick view button clicks
    $document.on('click', '.quick-view-button', function(e) {
      e.preventDefault();
      const productId = $(this).data('product-id');
      
      if (!productId) return;
      
      openQuickView(productId);
    });

    // Open quick view modal
    function openQuickView(productId) {
      // Show loading overlay
      $body.append('<div class="quick-view-loading"><div class="spinner"></div></div>');
      
      // Fetch product data via AJAX
      $.ajax({
        url: aqualuxeData.ajaxUrl,
        type: 'POST',
        data: {
          action: 'aqualuxe_quick_view',
          product_id: productId,
          nonce: aqualuxeData.nonce
        },
        success: function(response) {
          // Remove loading overlay
          $('.quick-view-loading').remove();
          
          if (response.success) {
            // Append modal to body
            $body.append(response.data.html);
            
            // Initialize modal
            const $modal = $('#quick-view-modal');
            
            // Show modal
            $modal.addClass('active');
            $body.addClass('modal-open');
            
            // Initialize product gallery
            if ($.fn.flexslider) {
              $modal.find('.woocommerce-product-gallery').flexslider({
                animation: 'slide',
                controlNav: 'thumbnails'
              });
            }
            
            // Initialize quantity buttons
            setupQuantityButtons();
            
            // Handle close button
            $modal.find('.modal-close').on('click', function() {
              closeQuickView();
            });
            
            // Close on overlay click
            $modal.on('click', function(e) {
              if ($(e.target).is($modal)) {
                closeQuickView();
              }
            });
            
            // Close on ESC key
            $document.on('keydown.quickView', function(e) {
              if (e.key === 'Escape') {
                closeQuickView();
              }
            });
          }
        },
        error: function() {
          // Remove loading overlay
          $('.quick-view-loading').remove();
          
          // Show error message
          $body.append('<div class="woocommerce-error">Error loading product. Please try again.</div>');
          
          // Remove error message after 3 seconds
          setTimeout(function() {
            $('.woocommerce-error').fadeOut(function() {
              $(this).remove();
            });
          }, 3000);
        }
      });
    }

    // Close quick view modal
    function closeQuickView() {
      const $modal = $('#quick-view-modal');
      
      $modal.removeClass('active');
      $body.removeClass('modal-open');
      
      // Remove modal after animation
      setTimeout(function() {
        $modal.remove();
      }, 300);
      
      // Remove ESC key handler
      $document.off('keydown.quickView');
    }
  }

  /**
   * Wishlist functionality
   */
  function setupWishlist() {
    // Handle wishlist button clicks
    $document.on('click', '.wishlist-button', function(e) {
      e.preventDefault();
      
      const $button = $(this);
      const productId = $button.data('product-id');
      
      if (!productId) return;
      
      // Toggle wishlist state
      $button.addClass('loading');
      
      $.ajax({
        url: aqualuxeData.ajaxUrl,
        type: 'POST',
        data: {
          action: 'aqualuxe_toggle_wishlist',
          product_id: productId,
          nonce: aqualuxeData.nonce
        },
        success: function(response) {
          $button.removeClass('loading');
          
          if (response.success) {
            if (response.data.in_wishlist) {
              $button.addClass('in-wishlist');
              $button.attr('aria-label', 'Remove from wishlist');
            } else {
              $button.removeClass('in-wishlist');
              $button.attr('aria-label', 'Add to wishlist');
            }
            
            // Update wishlist count
            $('.wishlist-count').text(response.data.count);
            
            // Show notification
            showNotification(response.data.message);
          }
        },
        error: function() {
          $button.removeClass('loading');
          showNotification('Error updating wishlist. Please try again.', 'error');
        }
      });
    });
  }

  /**
   * AJAX cart functionality
   */
  function setupAjaxCart() {
    // Add to cart via AJAX
    $document.on('click', '.ajax_add_to_cart', function(e) {
      $(this).addClass('loading');
    });

    // Update cart count and show notification when product is added to cart
    $body.on('added_to_cart', function(e, fragments, cart_hash, $button) {
      $button.removeClass('loading');
      
      if (fragments && fragments['div.widget_shopping_cart_content']) {
        showNotification('Product added to cart successfully.');
      }
    });
  }

  /**
   * Cart drawer functionality
   */
  function setupCartDrawer() {
    // Open cart drawer when clicking cart icon
    $document.on('click', '.header-cart-toggle', function(e) {
      e.preventDefault();
      openCartDrawer();
    });

    // Open cart drawer after adding to cart
    $body.on('added_to_cart', function() {
      openCartDrawer();
    });

    // Open cart drawer
    function openCartDrawer() {
      const $cartDrawer = $('#cart-drawer');
      
      if (!$cartDrawer.length) return;
      
      $cartDrawer.addClass('active');
      $body.addClass('drawer-open');
      
      // Handle close button
      $cartDrawer.find('.drawer-close').on('click', function() {
        closeCartDrawer();
      });
      
      // Close on overlay click
      $cartDrawer.on('click', function(e) {
        if ($(e.target).is($cartDrawer)) {
          closeCartDrawer();
        }
      });
      
      // Close on ESC key
      $document.on('keydown.cartDrawer', function(e) {
        if (e.key === 'Escape') {
          closeCartDrawer();
        }
      });
    }

    // Close cart drawer
    function closeCartDrawer() {
      const $cartDrawer = $('#cart-drawer');
      
      $cartDrawer.removeClass('active');
      $body.removeClass('drawer-open');
      
      // Remove ESC key handler
      $document.off('keydown.cartDrawer');
    }
  }

  /**
   * Product filters functionality
   */
  function setupProductFilters() {
    if (!$('.shop-filters').length) return;

    // Toggle filter visibility on mobile
    $('.filter-toggle').on('click', function(e) {
      e.preventDefault();
      $('.shop-filters').toggleClass('active');
      $body.toggleClass('filters-open');
    });

    // Close filters on window resize (if desktop view)
    $window.on('resize', function() {
      if ($window.width() >= 1024 && $('.shop-filters').hasClass('active')) {
        $('.shop-filters').removeClass('active');
        $body.removeClass('filters-open');
      }
    });

    // AJAX filtering
    if ($('.shop-filters form').length) {
      $('.shop-filters form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const formData = $form.serialize();
        const shopUrl = $form.attr('action');
        
        // Show loading overlay
        $body.append('<div class="shop-loading"><div class="spinner"></div></div>');
        
        // Update URL with filter parameters
        window.history.pushState({}, '', shopUrl + '?' + formData);
        
        // Fetch filtered products via AJAX
        $.ajax({
          url: shopUrl,
          data: formData,
          success: function(response) {
            // Extract products HTML from response
            const $response = $(response);
            const $products = $response.find('.products');
            const $pagination = $response.find('.woocommerce-pagination');
            const $result_count = $response.find('.woocommerce-result-count');
            
            // Update products
            $('.products').html($products.html());
            
            // Update pagination
            if ($pagination.length) {
              $('.woocommerce-pagination').html($pagination.html());
            } else {
              $('.woocommerce-pagination').empty();
            }
            
            // Update result count
            if ($result_count.length) {
              $('.woocommerce-result-count').html($result_count.html());
            }
            
            // Scroll to top of products
            $('html, body').animate({
              scrollTop: $('.products').offset().top - 100
            }, 500);
            
            // Remove loading overlay
            $('.shop-loading').remove();
          },
          error: function() {
            // Remove loading overlay
            $('.shop-loading').remove();
            
            // Show error message
            showNotification('Error loading products. Please try again.', 'error');
          }
        });
      });

      // Trigger form submit when filter inputs change
      $('.shop-filters input, .shop-filters select').on('change', function() {
        $(this).closest('form').submit();
      });
    }
  }

  /**
   * Currency switcher functionality
   */
  function setupCurrencySwitcher() {
    $('.currency-switcher select').on('change', function() {
      const currency = $(this).val();
      
      if (!currency) return;
      
      // Show loading overlay
      $body.append('<div class="currency-loading"><div class="spinner"></div></div>');
      
      $.ajax({
        url: aqualuxeData.ajaxUrl,
        type: 'POST',
        data: {
          action: 'aqualuxe_switch_currency',
          currency: currency,
          nonce: aqualuxeData.nonce
        },
        success: function(response) {
          if (response.success) {
            // Reload page to update prices
            window.location.reload();
          } else {
            // Remove loading overlay
            $('.currency-loading').remove();
            
            // Show error message
            showNotification('Error switching currency. Please try again.', 'error');
          }
        },
        error: function() {
          // Remove loading overlay
          $('.currency-loading').remove();
          
          // Show error message
          showNotification('Error switching currency. Please try again.', 'error');
        }
      });
    });
  }

  /**
   * Variation swatches functionality
   */
  function setupVariationSwatches() {
    if (!$('.variations').length) return;

    // Convert dropdowns to swatches
    $('.variations select').each(function() {
      const $select = $(this);
      const attributeName = $select.attr('name');
      
      // Skip if already converted
      if ($select.parent().find('.variation-swatches').length) return;
      
      // Create swatches container
      const $swatchesContainer = $('<div class="variation-swatches" data-attribute="' + attributeName + '"></div>');
      
      // Add swatches for each option
      $select.find('option').each(function() {
        const $option = $(this);
        const value = $option.val();
        const text = $option.text();
        
        // Skip empty option
        if (!value) return;
        
        // Create swatch
        const $swatch = $('<div class="swatch" data-value="' + value + '" role="radio" aria-checked="false" tabindex="0">' + text + '</div>');
        
        // Add color/image class if applicable
        if (attributeName.indexOf('color') > -1 || attributeName.indexOf('colour') > -1) {
          $swatch.addClass('swatch-color').css('background-color', value);
          $swatch.html('');
        }
        
        $swatchesContainer.append($swatch);
      });
      
      // Insert swatches after select
      $select.after($swatchesContainer);
      
      // Hide select
      $select.hide();
    });

    // Handle swatch clicks
    $document.on('click', '.swatch', function() {
      const $swatch = $(this);
      const $swatchesContainer = $swatch.parent();
      const attributeName = $swatchesContainer.data('attribute');
      const value = $swatch.data('value');
      const $select = $swatchesContainer.siblings('select[name="' + attributeName + '"]');
      
      // Update select value
      $select.val(value).trigger('change');
      
      // Update swatch states
      $swatchesContainer.find('.swatch').removeClass('selected').attr('aria-checked', 'false');
      $swatch.addClass('selected').attr('aria-checked', 'true');
    });

    // Handle keyboard navigation
    $document.on('keydown', '.swatch', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        $(this).click();
      }
    });

    // Set initial selected swatches
    $('.variations select').each(function() {
      const $select = $(this);
      const value = $select.val();
      const $swatchesContainer = $select.parent().find('.variation-swatches');
      
      if (value) {
        $swatchesContainer.find('.swatch[data-value="' + value + '"]').addClass('selected').attr('aria-checked', 'true');
      }
    });
  }

  /**
   * Product tabs functionality
   */
  function setupProductTabs() {
    if (!$('.woocommerce-tabs').length) return;

    // Handle tab clicks
    $('.woocommerce-tabs .tabs li a').on('click', function(e) {
      e.preventDefault();
      
      const $tab = $(this).parent();
      const tabId = $(this).attr('href');
      
      // Update active tab
      $('.woocommerce-tabs .tabs li').removeClass('active');
      $tab.addClass('active');
      
      // Show active tab content
      $('.woocommerce-Tabs-panel').hide();
      $(tabId).show();
    });

    // Set initial active tab
    if (window.location.hash && $(window.location.hash).length) {
      const $tab = $('.woocommerce-tabs .tabs li a[href="' + window.location.hash + '"]');
      
      if ($tab.length) {
        $tab.trigger('click');
      }
    } else {
      $('.woocommerce-tabs .tabs li:first-child a').trigger('click');
    }
  }

  /**
   * Product reviews functionality
   */
  function setupProductReviews() {
    // Handle rating selection
    $('.comment-form-rating .stars a').on('click', function(e) {
      e.preventDefault();
      
      const $star = $(this);
      const rating = $star.text();
      
      // Update hidden input
      $('#rating').val(rating);
      
      // Update stars
      $('.comment-form-rating .stars a').removeClass('active');
      $star.addClass('active').prevAll().addClass('active');
    });
  }

  /**
   * Show notification
   */
  function showNotification(message, type = 'success') {
    // Remove existing notifications
    $('.aqualuxe-notification').remove();
    
    // Create notification
    const $notification = $('<div class="aqualuxe-notification ' + type + '">' + message + '</div>');
    
    // Append to body
    $body.append($notification);
    
    // Show notification
    setTimeout(function() {
      $notification.addClass('active');
    }, 10);
    
    // Hide notification after 3 seconds
    setTimeout(function() {
      $notification.removeClass('active');
      
      // Remove notification after animation
      setTimeout(function() {
        $notification.remove();
      }, 300);
    }, 3000);
  }

  // Initialize when DOM is ready
  $(document).ready(function() {
    initWooCommerce();
  });

})(jQuery);