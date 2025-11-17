/**
 * AquaLuxe Theme WooCommerce JavaScript
 *
 * This file contains the WooCommerce specific functionality for the AquaLuxe theme.
 */

(function($) {
  'use strict';

  /**
   * WooCommerce functionality
   */
  const WooCommerce = {
    /**
     * Initialize WooCommerce functionality
     */
    init: function() {
      this.setupAjaxAddToCart();
      this.setupQuantityButtons();
      this.setupQuickView();
      this.setupWishlist();
      this.setupAdvancedFilters();
      this.setupStickyAddToCart();
      this.setupProductGallery();
      this.setupVariationSwatches();
      this.setupPriceSlider();
    },

    /**
     * Setup AJAX add to cart
     */
    setupAjaxAddToCart: function() {
      $(document).on('click', '.ajax_add_to_cart', function(e) {
        const $button = $(this);
        
        // Don't proceed if already loading or added
        if ($button.is('.loading')) {
          return;
        }
        
        // Add loading class
        $button.addClass('loading');
        
        // Get product data
        const productId = $button.data('product_id');
        const quantity = $button.data('quantity') || 1;
        const variationId = $button.data('variation_id') || 0;
        const variations = $button.data('variations') || {};
        
        // Add to cart via AJAX
        $.ajax({
          url: typeof wc_add_to_cart_params !== 'undefined' ? wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart') : '/?wc-ajax=add_to_cart',
          type: 'POST',
          data: {
            product_id: productId,
            quantity: quantity,
            variation_id: variationId,
            variations: variations
          },
          success: function(response) {
            if (!response) {
              return;
            }
            
            if (response.error && response.product_url) {
              window.location = response.product_url;
              return;
            }
            
            // Trigger event so themes can refresh other areas
            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
            
            // Remove loading class
            $button.removeClass('loading').addClass('added');
          },
          error: function() {
            // Remove loading class
            $button.removeClass('loading');
            
            // Show error message
            if (typeof wc_add_to_cart_params !== 'undefined') {
              window.location = wc_add_to_cart_params.cart_url;
              return;
            }
          }
        });
      });
      
      // Update cart fragments when added to cart
      $(document.body).on('added_to_cart', function(e, fragments, cart_hash, $button) {
        // Update cart fragments
        if (fragments) {
          $.each(fragments, function(key, value) {
            $(key).replaceWith(value);
          });
        }
        
        // Show mini cart if available
        const $miniCart = $('.mini-cart');
        if ($miniCart.length) {
          $miniCart.addClass('active');
          
          // Hide mini cart after 5 seconds
          setTimeout(function() {
            $miniCart.removeClass('active');
          }, 5000);
        }
        
        // Show notification
        WooCommerce.showNotification('success', typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.i18n.addedToCart : 'Product added to cart');
      });
    },

    /**
     * Setup quantity buttons
     */
    setupQuantityButtons: function() {
      // Add quantity buttons if they don't exist
      $('.quantity').each(function() {
        const $quantity = $(this);
        const $input = $quantity.find('.qty');
        
        if (!$quantity.find('.quantity-button').length) {
          $quantity.addClass('quantity-with-buttons');
          $input.before('<button type="button" class="quantity-button quantity-down">-</button>');
          $input.after('<button type="button" class="quantity-button quantity-up">+</button>');
        }
      });
      
      // Handle quantity buttons click
      $(document).on('click', '.quantity-button', function() {
        const $button = $(this);
        const $input = $button.closest('.quantity').find('.qty');
        const min = $input.attr('min') ? parseInt($input.attr('min')) : 1;
        const max = $input.attr('max') ? parseInt($input.attr('max')) : '';
        const step = $input.attr('step') ? parseInt($input.attr('step')) : 1;
        let value = parseInt($input.val());
        
        if (isNaN(value)) {
          value = min;
        }
        
        if ($button.hasClass('quantity-up')) {
          value += step;
          
          if (max && value > max) {
            value = max;
          }
        } else {
          value -= step;
          
          if (value < min) {
            value = min;
          }
        }
        
        $input.val(value).trigger('change');
      });
      
      // Update quantity when input changes
      $(document).on('change', '.qty', function() {
        const $input = $(this);
        const min = $input.attr('min') ? parseInt($input.attr('min')) : 1;
        const max = $input.attr('max') ? parseInt($input.attr('max')) : '';
        let value = parseInt($input.val());
        
        if (isNaN(value) || value < min) {
          value = min;
          $input.val(min);
        }
        
        if (max && value > max) {
          value = max;
          $input.val(max);
        }
        
        // Trigger update cart if on cart page
        if ($input.closest('.woocommerce-cart-form').length) {
          const $updateCartButton = $('button[name="update_cart"]');
          $updateCartButton.prop('disabled', false).trigger('click');
        }
      });
    },

    /**
     * Setup quick view
     */
    setupQuickView: function() {
      // Add quick view button to products
      $('.products .product').each(function() {
        const $product = $(this);
        const productId = $product.data('product-id');
        
        if (!$product.find('.quick-view-button').length && productId) {
          $product.find('.woocommerce-loop-product__link').append('<button type="button" class="quick-view-button" data-product-id="' + productId + '" aria-label="Quick view"><i class="icon icon-eye"></i></button>');
        }
      });
      
      // Handle quick view button click
      $(document).on('click', '.quick-view-button', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        // Show loading state
        $button.addClass('loading');
        
        // Get quick view content via AJAX
        $.ajax({
          url: typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.ajaxUrl : '/wp-admin/admin-ajax.php',
          type: 'POST',
          data: {
            action: 'aqualuxe_quick_view',
            product_id: productId,
            nonce: typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.nonce : ''
          },
          success: function(response) {
            // Remove loading state
            $button.removeClass('loading');
            
            if (response.success) {
              // Create modal if it doesn't exist
              if (!$('.quick-view-modal').length) {
                $('body').append('<div class="quick-view-modal"><div class="quick-view-content"></div></div>');
              }
              
              // Add content to modal
              $('.quick-view-content').html(response.data.html);
              
              // Show modal
              $('.quick-view-modal').addClass('active');
              $('body').addClass('modal-open');
              
              // Initialize product gallery
              WooCommerce.initProductGallery('.quick-view-modal .woocommerce-product-gallery');
              
              // Initialize variation form
              if (typeof $.fn.wc_variation_form !== 'undefined') {
                $('.quick-view-modal .variations_form').wc_variation_form();
              }
              
              // Trigger event
              $(document.body).trigger('aqualuxe:quickview:opened', [productId]);
            } else {
              // Show error message
              WooCommerce.showNotification('error', response.data.message || 'Error loading quick view');
            }
          },
          error: function() {
            // Remove loading state
            $button.removeClass('loading');
            
            // Show error message
            WooCommerce.showNotification('error', 'Error loading quick view');
          }
        });
      });
      
      // Close quick view modal
      $(document).on('click', '.quick-view-close', function() {
        $('.quick-view-modal').removeClass('active');
        $('body').removeClass('modal-open');
      });
      
      // Close quick view modal when clicking outside
      $(document).on('click', '.quick-view-modal', function(e) {
        if ($(e.target).hasClass('quick-view-modal')) {
          $('.quick-view-modal').removeClass('active');
          $('body').removeClass('modal-open');
        }
      });
    },

    /**
     * Setup wishlist
     */
    setupWishlist: function() {
      // Add wishlist button to products
      $('.products .product').each(function() {
        const $product = $(this);
        const productId = $product.data('product-id');
        
        if (!$product.find('.add-to-wishlist').length && productId) {
          const isInWishlist = WooCommerce.isInWishlist(productId);
          const buttonClass = isInWishlist ? 'add-to-wishlist in-wishlist' : 'add-to-wishlist';
          const iconClass = isInWishlist ? 'icon-heart' : 'icon-heart-o';
          
          $product.find('.woocommerce-loop-product__link').append('<button type="button" class="' + buttonClass + '" data-product-id="' + productId + '" aria-label="Add to wishlist"><i class="icon ' + iconClass + '"></i></button>');
        }
      });
      
      // Handle wishlist button click
      $(document).on('click', '.add-to-wishlist', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        const isInWishlist = $button.hasClass('in-wishlist');
        
        // Show loading state
        $button.addClass('loading');
        
        // Toggle wishlist via AJAX
        $.ajax({
          url: typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.ajaxUrl : '/wp-admin/admin-ajax.php',
          type: 'POST',
          data: {
            action: isInWishlist ? 'aqualuxe_remove_from_wishlist' : 'aqualuxe_add_to_wishlist',
            product_id: productId,
            nonce: typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.nonce : ''
          },
          success: function(response) {
            // Remove loading state
            $button.removeClass('loading');
            
            if (response.success) {
              if (isInWishlist) {
                // Remove from wishlist
                $button.removeClass('in-wishlist');
                $button.find('.icon').removeClass('icon-heart').addClass('icon-heart-o');
                
                // Show notification
                WooCommerce.showNotification('success', response.data.message || 'Product removed from wishlist');
              } else {
                // Add to wishlist
                $button.addClass('in-wishlist');
                $button.find('.icon').removeClass('icon-heart-o').addClass('icon-heart');
                
                // Show notification
                WooCommerce.showNotification('success', response.data.message || 'Product added to wishlist');
              }
              
              // Update wishlist count
              if (response.data.count !== undefined) {
                $('.wishlist-count').text(response.data.count);
              }
              
              // Trigger event
              $(document.body).trigger('aqualuxe:wishlist:updated', [productId, !isInWishlist]);
            } else {
              // Show error message
              WooCommerce.showNotification('error', response.data.message || 'Error updating wishlist');
            }
          },
          error: function() {
            // Remove loading state
            $button.removeClass('loading');
            
            // Show error message
            WooCommerce.showNotification('error', 'Error updating wishlist');
          }
        });
      });
    },

    /**
     * Check if product is in wishlist
     * 
     * @param {number} productId Product ID
     * @return {boolean} Whether product is in wishlist
     */
    isInWishlist: function(productId) {
      // Get wishlist from localStorage
      let wishlist = localStorage.getItem('aqualuxe_wishlist');
      
      if (wishlist) {
        try {
          wishlist = JSON.parse(wishlist);
          return wishlist.includes(parseInt(productId));
        } catch (e) {
          return false;
        }
      }
      
      return false;
    },

    /**
     * Setup advanced filters
     */
    setupAdvancedFilters: function() {
      // Toggle mobile filters
      $('.mobile-filter-toggle').on('click', function() {
        $('.mobile-filters').addClass('active');
      });
      
      $('.close-filters').on('click', function() {
        $('.mobile-filters').removeClass('active');
      });
      
      // Toggle filter groups
      $('.filter-group-title').on('click', function() {
        $(this).next('.filter-options').slideToggle(300);
        $(this).parent('.filter-group').toggleClass('active');
      });
      
      // Handle filter changes
      $('.product-filters input[type="checkbox"], .product-filters input[type="radio"]').on('change', function() {
        const $form = $(this).closest('form');
        
        // Don't submit form automatically if using AJAX filtering
        if ($form.hasClass('ajax-filter')) {
          WooCommerce.applyFilters($form);
        }
      });
      
      // Handle filter form submission
      $('.product-filters form').on('submit', function(e) {
        const $form = $(this);
        
        // Don't submit form if using AJAX filtering
        if ($form.hasClass('ajax-filter')) {
          e.preventDefault();
          WooCommerce.applyFilters($form);
        }
      });
      
      // Reset filters
      $('.reset-filters').on('click', function(e) {
        e.preventDefault();
        
        const $form = $(this).closest('form');
        
        // Reset form
        $form[0].reset();
        
        // Reset price slider if exists
        if ($form.find('.price-slider').length) {
          const $slider = $form.find('.ui-slider');
          const min = $slider.data('min');
          const max = $slider.data('max');
          
          $slider.slider('values', 0, min);
          $slider.slider('values', 1, max);
          
          $form.find('input[name="min_price"]').val(min);
          $form.find('input[name="max_price"]').val(max);
        }
        
        // Apply filters
        if ($form.hasClass('ajax-filter')) {
          WooCommerce.applyFilters($form);
        } else {
          $form.submit();
        }
      });
      
      // Initialize price slider
      if (typeof $.fn.slider !== 'undefined' && $('.price-slider').length) {
        $('.price-slider .ui-slider').each(function() {
          const $slider = $(this);
          const $form = $slider.closest('form');
          const min = parseFloat($slider.data('min'));
          const max = parseFloat($slider.data('max'));
          const currentMin = parseFloat($slider.data('current-min')) || min;
          const currentMax = parseFloat($slider.data('current-max')) || max;
          
          $slider.slider({
            range: true,
            min: min,
            max: max,
            values: [currentMin, currentMax],
            slide: function(event, ui) {
              $form.find('input[name="min_price"]').val(ui.values[0]);
              $form.find('input[name="max_price"]').val(ui.values[1]);
              
              $form.find('.price-amount').html(
                '<span class="from">' + WooCommerce.formatPrice(ui.values[0]) + '</span> - <span class="to">' + WooCommerce.formatPrice(ui.values[1]) + '</span>'
              );
            },
            change: function(event, ui) {
              if ($form.hasClass('ajax-filter')) {
                WooCommerce.applyFilters($form);
              }
            }
          });
          
          // Initialize price display
          $form.find('.price-amount').html(
            '<span class="from">' + WooCommerce.formatPrice(currentMin) + '</span> - <span class="to">' + WooCommerce.formatPrice(currentMax) + '</span>'
          );
          
          // Update slider when inputs change
          $form.find('input[name="min_price"], input[name="max_price"]').on('change', function() {
            const minPrice = parseFloat($form.find('input[name="min_price"]').val());
            const maxPrice = parseFloat($form.find('input[name="max_price"]').val());
            
            $slider.slider('values', 0, minPrice);
            $slider.slider('values', 1, maxPrice);
          });
        });
      }
    },

    /**
     * Apply filters via AJAX
     * 
     * @param {jQuery} $form Filter form
     */
    applyFilters: function($form) {
      const $productsContainer = $('.products');
      const $loadingOverlay = $('.products-loading-overlay');
      
      // Show loading overlay
      if (!$loadingOverlay.length) {
        $productsContainer.before('<div class="products-loading-overlay"><div class="spinner"></div></div>');
      } else {
        $loadingOverlay.show();
      }
      
      // Get form data
      const formData = new FormData($form[0]);
      formData.append('action', 'aqualuxe_filter_products');
      formData.append('nonce', typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.nonce : '');
      
      // Send AJAX request
      $.ajax({
        url: typeof aqualuxeSettings !== 'undefined' ? aqualuxeSettings.ajaxUrl : '/wp-admin/admin-ajax.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          // Hide loading overlay
          $('.products-loading-overlay').hide();
          
          if (response.success) {
            // Update products
            $productsContainer.html(response.data.products);
            
            // Update pagination
            $('.woocommerce-pagination').html(response.data.pagination);
            
            // Update result count
            $('.woocommerce-result-count').html(response.data.result_count);
            
            // Update browser URL
            if (window.history && window.history.pushState) {
              window.history.pushState({}, '', response.data.url);
            }
            
            // Close mobile filters
            $('.mobile-filters').removeClass('active');
            
            // Scroll to top of products
            $('html, body').animate({
              scrollTop: $productsContainer.offset().top - 100
            }, 500);
            
            // Trigger event
            $(document.body).trigger('aqualuxe:filters:applied', [response.data]);
          } else {
            // Show error message
            WooCommerce.showNotification('error', response.data.message || 'Error applying filters');
          }
        },
        error: function() {
          // Hide loading overlay
          $('.products-loading-overlay').hide();
          
          // Show error message
          WooCommerce.showNotification('error', 'Error applying filters');
        }
      });
    },

    /**
     * Format price
     * 
     * @param {number} price Price to format
     * @return {string} Formatted price
     */
    formatPrice: function(price) {
      // Use WooCommerce price format if available
      if (typeof woocommerce_price_slider_params !== 'undefined') {
        return accounting.formatMoney(
          price,
          woocommerce_price_slider_params.currency_format_symbol,
          woocommerce_price_slider_params.currency_format_num_decimals,
          woocommerce_price_slider_params.currency_format_thousand_sep,
          woocommerce_price_slider_params.currency_format_decimal_sep,
          woocommerce_price_slider_params.currency_format
        );
      }
      
      // Fallback to simple formatting
      return '$' + price.toFixed(2);
    },

    /**
     * Setup sticky add to cart
     */
    setupStickyAddToCart: function() {
      const $stickyAddToCart = $('.sticky-add-to-cart');
      
      if ($stickyAddToCart.length && $('.single-product').length) {
        const $productSummary = $('.single-product .summary');
        const productSummaryOffset = $productSummary.offset().top;
        const productSummaryHeight = $productSummary.outerHeight();
        const windowHeight = $(window).height();
        
        $(window).on('scroll', function() {
          const scrollTop = $(this).scrollTop();
          
          // Show sticky add to cart when scrolling past product summary
          if (scrollTop > productSummaryOffset + productSummaryHeight - windowHeight) {
            $stickyAddToCart.addClass('visible');
          } else {
            $stickyAddToCart.removeClass('visible');
          }
        });
        
        // Update sticky add to cart when variations change
        $(document.body).on('found_variation', function(e, variation) {
          if (variation.price_html) {
            $stickyAddToCart.find('.product-price').html(variation.price_html);
          }
          
          if (variation.image && variation.image.src) {
            $stickyAddToCart.find('.product-image img').attr('src', variation.image.src);
          }
          
          $stickyAddToCart.find('.single_add_to_cart_button').prop('disabled', false);
        });
        
        $(document.body).on('reset_data', function() {
          const $originalPrice = $('.single-product .summary .price').clone();
          const $originalImage = $('.single-product .woocommerce-product-gallery__image:first img').clone();
          
          $stickyAddToCart.find('.product-price').html($originalPrice);
          $stickyAddToCart.find('.product-image img').attr('src', $originalImage.attr('src'));
          $stickyAddToCart.find('.single_add_to_cart_button').prop('disabled', false);
        });
        
        // Sync quantity input
        $stickyAddToCart.find('.qty').on('change', function() {
          $('.single-product .summary .qty').val($(this).val()).trigger('change');
        });
        
        $('.single-product .summary .qty').on('change', function() {
          $stickyAddToCart.find('.qty').val($(this).val());
        });
        
        // Handle add to cart button click
        $stickyAddToCart.find('.single_add_to_cart_button').on('click', function(e) {
          e.preventDefault();
          $('.single-product .summary .single_add_to_cart_button').trigger('click');
        });
      }
    },

    /**
     * Setup product gallery
     */
    setupProductGallery: function() {
      // Initialize product gallery on single product pages
      if ($('.woocommerce-product-gallery').length) {
        WooCommerce.initProductGallery('.woocommerce-product-gallery');
      }
    },

    /**
     * Initialize product gallery
     * 
     * @param {string} selector Gallery selector
     */
    initProductGallery: function(selector) {
      const $gallery = $(selector);
      
      if (!$gallery.length) {
        return;
      }
      
      // Check if PhotoSwipe is available
      const hasPhotoSwipe = typeof PhotoSwipe !== 'undefined' && typeof PhotoSwipeUI_Default !== 'undefined';
      
      // Add gallery trigger
      if (hasPhotoSwipe && !$gallery.find('.woocommerce-product-gallery__trigger').length) {
        $gallery.prepend('<a href="#" class="woocommerce-product-gallery__trigger">🔍</a>');
      }
      
      // Setup gallery images
      const $mainImage = $gallery.find('.woocommerce-product-gallery__image:first');
      const $thumbnails = $gallery.find('.flex-control-thumbs li img');
      
      // Handle thumbnail click
      $thumbnails.on('click', function() {
        const $thumbnail = $(this);
        const index = $thumbnail.parent().index();
        const $image = $gallery.find('.woocommerce-product-gallery__image').eq(index);
        const imageUrl = $image.find('img').attr('src');
        const fullImageUrl = $image.find('a').attr('href');
        
        // Update active thumbnail
        $thumbnails.removeClass('flex-active');
        $thumbnail.addClass('flex-active');
        
        // Update main image
        $mainImage.find('img').attr('src', imageUrl);
        $mainImage.find('a').attr('href', fullImageUrl);
        
        return false;
      });
      
      // Setup PhotoSwipe
      if (hasPhotoSwipe) {
        // Handle gallery trigger click
        $gallery.on('click', '.woocommerce-product-gallery__trigger', function(e) {
          e.preventDefault();
          
          const items = [];
          
          $gallery.find('.woocommerce-product-gallery__image a').each(function() {
            const $link = $(this);
            const item = {
              src: $link.attr('href'),
              w: $link.data('width') || 800,
              h: $link.data('height') || 800,
              title: $link.data('caption') || ''
            };
            
            items.push(item);
          });
          
          const options = {
            index: 0,
            shareEl: false,
            closeOnScroll: false,
            history: false,
            hideAnimationDuration: 0,
            showAnimationDuration: 0
          };
          
          // Get index of active image
          const activeIndex = $gallery.find('.flex-active').parent().index();
          if (activeIndex >= 0) {
            options.index = activeIndex;
          }
          
          // Create PhotoSwipe gallery
          const gallery = new PhotoSwipe(
            $('.pswp')[0],
            PhotoSwipeUI_Default,
            items,
            options
          );
          
          gallery.init();
        });
        
        // Handle image click
        $gallery.on('click', '.woocommerce-product-gallery__image a', function(e) {
          e.preventDefault();
          $gallery.find('.woocommerce-product-gallery__trigger').trigger('click');
        });
      }
      
      // Mark gallery as initialized
      $gallery.addClass('initialized');
    },

    /**
     * Setup variation swatches
     */
    setupVariationSwatches: function() {
      // Check if variation form exists
      if (!$('.variations_form').length) {
        return;
      }
      
      // Convert dropdowns to swatches
      $('.variations_form .variations select').each(function() {
        const $select = $(this);
        const attributeName = $select.attr('name');
        const $wrapper = $select.closest('td.value');
        const $label = $select.closest('tr').find('td.label label');
        
        // Skip if already converted
        if ($wrapper.find('.variation-swatches').length) {
          return;
        }
        
        // Create swatches container
        const $swatches = $('<div class="variation-swatches" data-attribute="' + attributeName + '"></div>');
        
        // Add swatches
        $select.find('option').each(function() {
          const $option = $(this);
          const value = $option.val();
          const text = $option.text();
          
          // Skip empty option
          if (!value) {
            return;
          }
          
          // Create swatch
          let $swatch;
          
          // Check if it's a color attribute
          if (attributeName.indexOf('color') > -1 || attributeName.indexOf('colour') > -1) {
            // Try to get color from data attribute
            let color = $option.data('color') || '';
            
            // If no color data, try to extract from text
            if (!color) {
              // Common color names
              const colorMap = {
                'black': '#000000',
                'white': '#ffffff',
                'red': '#ff0000',
                'green': '#00ff00',
                'blue': '#0000ff',
                'yellow': '#ffff00',
                'purple': '#800080',
                'pink': '#ffc0cb',
                'orange': '#ffa500',
                'brown': '#a52a2a',
                'gray': '#808080',
                'grey': '#808080'
              };
              
              // Check if text matches a common color name
              const lowerText = text.toLowerCase();
              for (const colorName in colorMap) {
                if (lowerText.indexOf(colorName) > -1) {
                  color = colorMap[colorName];
                  break;
                }
              }
              
              // Default color if no match
              if (!color) {
                color = '#eeeeee';
              }
            }
            
            $swatch = $('<div class="variation-swatch color-swatch" data-value="' + value + '" title="' + text + '"><span style="background-color: ' + color + ';"></span></div>');
          } else if (attributeName.indexOf('image') > -1) {
            // Image swatch
            const imageUrl = $option.data('image') || '';
            $swatch = $('<div class="variation-swatch image-swatch" data-value="' + value + '" title="' + text + '"><img src="' + imageUrl + '" alt="' + text + '"></div>');
          } else {
            // Text swatch
            $swatch = $('<div class="variation-swatch text-swatch" data-value="' + value + '">' + text + '</div>');
          }
          
          $swatches.append($swatch);
        });
        
        // Add reset link
        $swatches.append('<a href="#" class="reset-variations">Clear</a>');
        
        // Hide select
        $select.hide().after($swatches);
        
        // Handle swatch click
        $swatches.on('click', '.variation-swatch', function() {
          const $swatch = $(this);
          const value = $swatch.data('value');
          
          // Update select value
          $select.val(value).trigger('change');
          
          // Update active state
          $swatches.find('.variation-swatch').removeClass('active');
          $swatch.addClass('active');
          
          return false;
        });
        
        // Handle reset click
        $swatches.on('click', '.reset-variations', function(e) {
          e.preventDefault();
          
          // Reset select value
          $select.val('').trigger('change');
          
          // Remove active state
          $swatches.find('.variation-swatch').removeClass('active');
        });
        
        // Update swatches when select changes
        $select.on('change', function() {
          const value = $select.val();
          
          $swatches.find('.variation-swatch').removeClass('active');
          
          if (value) {
            $swatches.find('.variation-swatch[data-value="' + value + '"]').addClass('active');
          }
        });
      });
    },

    /**
     * Setup price slider
     */
    setupPriceSlider: function() {
      // Check if price slider exists and jQuery UI slider is available
      if ($('.price_slider').length && typeof $.fn.slider !== 'undefined') {
        // Initialize price slider
        $('.price_slider').slider({
          range: true,
          animate: true,
          min: parseFloat($('.price_slider').data('min')),
          max: parseFloat($('.price_slider').data('max')),
          values: [
            parseFloat($('.price_slider').data('min')),
            parseFloat($('.price_slider').data('max'))
          ],
          create: function() {
            $('.price_slider_amount #min_price').val($('.price_slider').slider('values', 0));
            $('.price_slider_amount #max_price').val($('.price_slider').slider('values', 1));
            
            $(document.body).trigger('price_slider_create', [
              $('.price_slider').slider('values', 0),
              $('.price_slider').slider('values', 1)
            ]);
          },
          slide: function(event, ui) {
            $('.price_slider_amount #min_price').val(ui.values[0]);
            $('.price_slider_amount #max_price').val(ui.values[1]);
            
            $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
          },
          change: function(event, ui) {
            $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
          }
        });
      }
    },

    /**
     * Show notification
     * 
     * @param {string} type Notification type (success, error, info, warning)
     * @param {string} message Notification message
     * @param {number} duration Duration in milliseconds
     */
    showNotification: function(type, message, duration = 3000) {
      // Create notification container if it doesn't exist
      if (!$('.aqualuxe-notifications').length) {
        $('body').append('<div class="aqualuxe-notifications"></div>');
      }
      
      // Create notification
      const $notification = $('<div class="aqualuxe-notification notification-' + type + '">' + message + '<button type="button" class="notification-close">&times;</button></div>');
      
      // Add notification to container
      $('.aqualuxe-notifications').append($notification);
      
      // Show notification
      setTimeout(function() {
        $notification.addClass('active');
      }, 10);
      
      // Hide notification after duration
      setTimeout(function() {
        $notification.removeClass('active');
        
        // Remove notification after animation
        setTimeout(function() {
          $notification.remove();
        }, 300);
      }, duration);
      
      // Handle close button click
      $notification.find('.notification-close').on('click', function() {
        $notification.removeClass('active');
        
        // Remove notification after animation
        setTimeout(function() {
          $notification.remove();
        }, 300);
      });
    }
  };

  // Initialize WooCommerce when DOM is ready
  $(document).ready(function() {
    WooCommerce.init();
  });

  // Expose WooCommerce to global scope
  if (typeof window.AquaLuxe === 'undefined') {
    window.AquaLuxe = {};
  }
  
  window.AquaLuxe.WooCommerce = WooCommerce;

})(jQuery);