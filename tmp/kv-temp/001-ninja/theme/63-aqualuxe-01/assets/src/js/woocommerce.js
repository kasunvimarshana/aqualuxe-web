/**
 * WooCommerce functionality for AquaLuxe theme
 */

(function($) {
  'use strict';
  
  // AquaLuxe namespace
  window.AquaLuxe = window.AquaLuxe || {};
  
  // DOM ready
  $(function() {
    AquaLuxe.wooCommerce.init();
  });
  
  /**
   * WooCommerce functionality
   */
  AquaLuxe.wooCommerce = {
    /**
     * Initialize WooCommerce functionality
     */
    init: function() {
      this.setupShopView();
      this.setupQuantityButtons();
      this.setupAjaxCart();
      this.setupQuickView();
      this.setupWishlist();
      this.setupCompare();
      this.setupFilters();
      this.setupVariationSwatches();
      this.setupGallery();
    },
    
    /**
     * Setup shop view switcher
     */
    setupShopView: function() {
      const $viewButtons = $('.shop-view-button');
      const $productList = $('ul.products');
      
      if (!$viewButtons.length || !$productList.length) {
        return;
      }
      
      // Get current view from cookie or default to grid
      const currentView = this.getCookie('aqualuxe_shop_view') || 'grid';
      
      // Set initial view
      $productList.removeClass('grid-view list-view').addClass(currentView + '-view');
      $viewButtons.removeClass('active');
      $viewButtons.filter('.' + currentView + '-view').addClass('active');
      
      // Handle view switching
      $viewButtons.on('click', function(e) {
        e.preventDefault();
        
        const view = $(this).data('view');
        
        // Update buttons
        $viewButtons.removeClass('active');
        $(this).addClass('active');
        
        // Update product list
        $productList.removeClass('grid-view list-view').addClass(view + '-view');
        
        // Save preference
        AquaLuxe.wooCommerce.setCookie('aqualuxe_shop_view', view, 30);
      });
    },
    
    /**
     * Setup quantity buttons
     */
    setupQuantityButtons: function() {
      // Add quantity buttons
      $('.quantity').each(function() {
        const $quantity = $(this);
        const $input = $quantity.find('input[type="number"]');
        
        if (!$quantity.find('.quantity-button').length) {
          $quantity.prepend('<button type="button" class="quantity-button minus">-</button>');
          $quantity.append('<button type="button" class="quantity-button plus">+</button>');
        }
      });
      
      // Handle quantity button clicks
      $(document).on('click', '.quantity-button', function() {
        const $button = $(this);
        const $input = $button.parent().find('input[type="number"]');
        const step = $input.attr('step') ? parseFloat($input.attr('step')) : 1;
        const min = $input.attr('min') ? parseFloat($input.attr('min')) : 1;
        const max = $input.attr('max') ? parseFloat($input.attr('max')) : '';
        let value = parseFloat($input.val());
        
        if (isNaN(value)) {
          value = min;
        }
        
        if ($button.hasClass('minus')) {
          value = value - step;
        } else {
          value = value + step;
        }
        
        // Ensure value is within bounds
        if (min && value < min) {
          value = min;
        }
        
        if (max && value > max) {
          value = max;
        }
        
        $input.val(value).trigger('change');
      });
      
      // Update quantity buttons on cart page when quantities change
      $(document.body).on('updated_cart_totals', function() {
        AquaLuxe.wooCommerce.setupQuantityButtons();
      });
    },
    
    /**
     * Setup AJAX cart functionality
     */
    setupAjaxCart: function() {
      const self = this;
      
      // Add to cart via AJAX
      $(document).on('click', '.ajax_add_to_cart', function(e) {
        const $button = $(this);
        
        if ($button.hasClass('product_type_variable') || $button.hasClass('product_type_grouped')) {
          return;
        }
        
        e.preventDefault();
        
        $button.addClass('loading');
        
        const data = {
          action: 'aqualuxe_add_to_cart',
          product_id: $button.data('product_id'),
          quantity: $button.data('quantity') || 1,
          variation_id: $button.data('variation_id') || 0,
          nonce: aqualuxeWooCommerce.nonce
        };
        
        $.ajax({
          url: aqualuxeWooCommerce.ajaxUrl,
          type: 'POST',
          data: data,
          success: function(response) {
            if (response.success) {
              // Update cart fragments
              if (response.fragments) {
                $.each(response.fragments, function(key, value) {
                  $(key).replaceWith(value);
                });
              }
              
              // Update cart count
              if (response.cart_count) {
                $('.header-cart-count').html(response.cart_count);
              }
              
              // Show mini cart
              $('.header-cart-dropdown').addClass('active');
              
              // Auto-hide mini cart after 3 seconds
              setTimeout(function() {
                $('.header-cart-dropdown').removeClass('active');
              }, 3000);
              
              // Trigger event
              $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
            } else {
              console.error('Error adding to cart:', response.message);
            }
            
            $button.removeClass('loading').addClass('added');
          },
          error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            $button.removeClass('loading');
          }
        });
      });
      
      // Update cart via AJAX
      $(document).on('change', '.woocommerce-cart-form .qty', function() {
        const $form = $(this).closest('form');
        
        // Delay to allow for multiple quantity changes
        clearTimeout(self.updateCartTimeout);
        
        self.updateCartTimeout = setTimeout(function() {
          $form.find('button[name="update_cart"]').trigger('click');
        }, 500);
      });
    },
    
    /**
     * Setup quick view functionality
     */
    setupQuickView: function() {
      const self = this;
      
      // Quick view button click
      $(document).on('click', '.quick-view-button', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        $button.addClass('loading');
        
        // Get product data via AJAX
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
              // Create modal
              self.createQuickViewModal(response.data);
              
              // Initialize product gallery
              self.initQuickViewGallery();
              
              // Initialize add to cart form
              self.initQuickViewAddToCart();
            } else {
              console.error('Error loading quick view:', response.message);
            }
            
            $button.removeClass('loading');
          },
          error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            $button.removeClass('loading');
          }
        });
      });
      
      // Close quick view modal
      $(document).on('click', '.quick-view-close, .quick-view-modal-overlay', function(e) {
        if ($(e.target).is('.quick-view-modal-overlay') || $(e.target).is('.quick-view-close')) {
          self.closeQuickViewModal();
        }
      });
      
      // Close on escape key
      $(document).on('keyup', function(e) {
        if (e.key === 'Escape' && $('.quick-view-modal').length) {
          self.closeQuickViewModal();
        }
      });
    },
    
    /**
     * Create quick view modal
     * 
     * @param {string} content Modal content
     */
    createQuickViewModal: function(content) {
      // Remove existing modal
      $('.quick-view-modal').remove();
      
      // Create new modal
      const $modal = $('<div class="quick-view-modal"><div class="quick-view-modal-overlay"></div><div class="quick-view-content"><button class="quick-view-close">&times;</button>' + content + '</div></div>');
      
      // Add to body
      $('body').append($modal).addClass('quick-view-open');
      
      // Prevent body scrolling
      $('body').css('overflow', 'hidden');
      
      // Focus on close button
      setTimeout(function() {
        $('.quick-view-close').focus();
      }, 100);
    },
    
    /**
     * Close quick view modal
     */
    closeQuickViewModal: function() {
      $('.quick-view-modal').fadeOut(200, function() {
        $(this).remove();
        $('body').removeClass('quick-view-open').css('overflow', '');
      });
    },
    
    /**
     * Initialize quick view gallery
     */
    initQuickViewGallery: function() {
      const $gallery = $('.quick-view-modal .woocommerce-product-gallery');
      
      if (!$gallery.length) {
        return;
      }
      
      // Initialize gallery
      $gallery.each(function() {
        const $this = $(this);
        const $mainImage = $this.find('.woocommerce-product-gallery__image');
        const $thumbnails = $this.find('.woocommerce-product-gallery__thumbnails');
        
        // Handle thumbnail clicks
        $thumbnails.on('click', 'a', function(e) {
          e.preventDefault();
          
          const $link = $(this);
          const fullSrc = $link.attr('href');
          const imgSrc = $link.find('img').attr('src');
          const imgSrcset = $link.find('img').attr('srcset') || '';
          const imgAlt = $link.find('img').attr('alt') || '';
          
          // Update main image
          $mainImage.find('a').attr('href', fullSrc);
          $mainImage.find('img').attr({
            'src': imgSrc,
            'srcset': imgSrcset,
            'alt': imgAlt
          });
          
          // Update active thumbnail
          $thumbnails.find('.active').removeClass('active');
          $link.parent().addClass('active');
        });
      });
    },
    
    /**
     * Initialize quick view add to cart form
     */
    initQuickViewAddToCart: function() {
      const $form = $('.quick-view-modal .cart');
      
      if (!$form.length) {
        return;
      }
      
      // Initialize quantity buttons
      this.setupQuantityButtons();
      
      // Initialize variations form
      if ($form.hasClass('variations_form')) {
        $form.wc_variation_form();
      }
    },
    
    /**
     * Setup wishlist functionality
     */
    setupWishlist: function() {
      const self = this;
      
      // Wishlist button click
      $(document).on('click', '.wishlist-button', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        $button.addClass('loading');
        
        // Toggle wishlist via AJAX
        $.ajax({
          url: aqualuxeWooCommerce.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_toggle_wishlist',
            product_id: productId,
            nonce: aqualuxeWooCommerce.nonce
          },
          success: function(response) {
            if (response.success) {
              // Update button state
              if (response.data.in_wishlist) {
                $button.addClass('in-wishlist');
                $button.find('i').removeClass('far').addClass('fas');
              } else {
                $button.removeClass('in-wishlist');
                $button.find('i').removeClass('fas').addClass('far');
              }
              
              // Update wishlist count
              $('.wishlist-count').html(response.data.count);
              
              // Show notification
              self.showNotification(response.data.message);
              
              // If on wishlist page, remove item
              if ($('.woocommerce-wishlist').length && !response.data.in_wishlist) {
                $button.closest('tr').fadeOut(300, function() {
                  $(this).remove();
                  
                  // If no items left, reload page
                  if ($('.wishlist-items').children().length === 0) {
                    window.location.reload();
                  }
                });
              }
            } else {
              console.error('Error updating wishlist:', response.message);
              self.showNotification(response.message, 'error');
            }
            
            $button.removeClass('loading');
          },
          error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            $button.removeClass('loading');
            self.showNotification('Error updating wishlist. Please try again.', 'error');
          }
        });
      });
    },
    
    /**
     * Setup compare functionality
     */
    setupCompare: function() {
      const self = this;
      
      // Compare button click
      $(document).on('click', '.compare-button', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        $button.addClass('loading');
        
        // Toggle compare via AJAX
        $.ajax({
          url: aqualuxeWooCommerce.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_toggle_compare',
            product_id: productId,
            nonce: aqualuxeWooCommerce.nonce
          },
          success: function(response) {
            if (response.success) {
              // Update button state
              if (response.data.in_compare) {
                $button.addClass('in-compare');
              } else {
                $button.removeClass('in-compare');
              }
              
              // Update compare count
              $('.compare-count').html(response.data.count);
              
              // Show notification
              self.showNotification(response.data.message);
              
              // If on compare page, remove item
              if ($('.woocommerce-compare').length && !response.data.in_compare) {
                window.location.reload();
              }
            } else {
              console.error('Error updating compare list:', response.message);
              self.showNotification(response.message, 'error');
            }
            
            $button.removeClass('loading');
          },
          error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            $button.removeClass('loading');
            self.showNotification('Error updating compare list. Please try again.', 'error');
          }
        });
      });
    },
    
    /**
     * Setup product filters
     */
    setupFilters: function() {
      const $filterForm = $('.shop-filters form');
      
      if (!$filterForm.length) {
        return;
      }
      
      // Price range slider
      const $priceRange = $filterForm.find('.price-range-slider');
      
      if ($priceRange.length && $.fn.slider) {
        $priceRange.each(function() {
          const $this = $(this);
          const $minInput = $this.find('.min-price');
          const $maxInput = $this.find('.max-price');
          const minPrice = parseFloat($this.data('min'));
          const maxPrice = parseFloat($this.data('max'));
          const currentMinPrice = parseFloat($minInput.val()) || minPrice;
          const currentMaxPrice = parseFloat($maxInput.val()) || maxPrice;
          
          $this.find('.price-slider').slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [currentMinPrice, currentMaxPrice],
            slide: function(event, ui) {
              $minInput.val(ui.values[0]);
              $maxInput.val(ui.values[1]);
              
              $this.find('.price-amount').html(
                aqualuxeWooCommerce.currency + ui.values[0] + ' - ' + aqualuxeWooCommerce.currency + ui.values[1]
              );
            }
          });
        });
      }
      
      // AJAX filtering
      if (aqualuxeWooCommerce.ajaxFiltering) {
        $filterForm.on('change', 'input, select', function() {
          const $form = $(this).closest('form');
          
          // Delay to prevent multiple requests
          clearTimeout(AquaLuxe.wooCommerce.filterTimeout);
          
          AquaLuxe.wooCommerce.filterTimeout = setTimeout(function() {
            $form.submit();
          }, 500);
        });
        
        $filterForm.on('submit', function(e) {
          e.preventDefault();
          
          const $form = $(this);
          const $shopContainer = $('.woocommerce-shop');
          
          $shopContainer.addClass('loading');
          
          // Get form data
          const formData = $form.serialize();
          
          // Update URL
          const currentUrl = window.location.href.split('?')[0];
          const newUrl = currentUrl + '?' + formData;
          window.history.pushState({}, '', newUrl);
          
          // Get filtered products via AJAX
          $.ajax({
            url: aqualuxeWooCommerce.ajaxUrl,
            type: 'POST',
            data: {
              action: 'aqualuxe_filter_products',
              form_data: formData,
              nonce: aqualuxeWooCommerce.nonce
            },
            success: function(response) {
              if (response.success) {
                // Update products
                $('.woocommerce-shop .products-wrapper').html(response.data.products);
                
                // Update pagination
                $('.woocommerce-shop .woocommerce-pagination').html(response.data.pagination);
                
                // Update result count
                $('.woocommerce-shop .woocommerce-result-count').html(response.data.result_count);
                
                // Scroll to top of products
                $('html, body').animate({
                  scrollTop: $('.woocommerce-shop').offset().top - 100
                }, 500);
              } else {
                console.error('Error filtering products:', response.message);
              }
              
              $shopContainer.removeClass('loading');
            },
            error: function(xhr, status, error) {
              console.error('AJAX error:', error);
              $shopContainer.removeClass('loading');
            }
          });
        });
      }
    },
    
    /**
     * Setup variation swatches
     */
    setupVariationSwatches: function() {
      const $variationForms = $('.variations_form');
      
      if (!$variationForms.length) {
        return;
      }
      
      $variationForms.each(function() {
        const $form = $(this);
        const $variations = $form.find('.variations');
        
        // Convert dropdowns to swatches if they have swatch data
        $variations.find('select').each(function() {
          const $select = $(this);
          const attributeName = $select.attr('name');
          
          // Check if this attribute has swatch data
          if ($select.data('swatches')) {
            const swatchType = $select.data('swatch-type') || 'color';
            const $swatchContainer = $('<div class="swatch-container" data-attribute="' + attributeName + '"></div>');
            
            // Create swatches for each option
            $select.find('option').each(function() {
              const $option = $(this);
              const value = $option.val();
              const text = $option.text();
              
              // Skip empty option
              if (!value) {
                return;
              }
              
              let $swatch;
              
              if (swatchType === 'color') {
                const color = $option.data('color') || '#eeeeee';
                $swatch = $('<div class="swatch swatch-color" data-value="' + value + '" style="background-color: ' + color + '" title="' + text + '"></div>');
              } else if (swatchType === 'image') {
                const image = $option.data('image') || '';
                $swatch = $('<div class="swatch swatch-image" data-value="' + value + '" title="' + text + '"><img src="' + image + '" alt="' + text + '"></div>');
              } else {
                $swatch = $('<div class="swatch swatch-label" data-value="' + value + '">' + text + '</div>');
              }
              
              // Mark selected swatch
              if ($option.is(':selected')) {
                $swatch.addClass('selected');
              }
              
              $swatchContainer.append($swatch);
            });
            
            // Replace select with swatches
            $select.after($swatchContainer).hide();
            
            // Handle swatch click
            $swatchContainer.on('click', '.swatch', function() {
              const $swatch = $(this);
              const value = $swatch.data('value');
              
              // Update select
              $select.val(value).trigger('change');
              
              // Update selected swatch
              $swatchContainer.find('.swatch').removeClass('selected');
              $swatch.addClass('selected');
            });
          }
        });
        
        // Update swatches when variation changes
        $form.on('woocommerce_variation_has_changed', function() {
          $variations.find('.swatch-container').each(function() {
            const $container = $(this);
            const attributeName = $container.data('attribute');
            const $select = $variations.find('select[name="' + attributeName + '"]');
            const value = $select.val();
            
            // Update selected swatch
            $container.find('.swatch').removeClass('selected');
            $container.find('.swatch[data-value="' + value + '"]').addClass('selected');
          });
        });
      });
    },
    
    /**
     * Setup product gallery
     */
    setupGallery: function() {
      const $gallery = $('.woocommerce-product-gallery');
      
      if (!$gallery.length) {
        return;
      }
      
      $gallery.each(function() {
        const $this = $(this);
        const $mainImage = $this.find('.woocommerce-product-gallery__image');
        const $thumbnails = $this.find('.woocommerce-product-gallery__thumbnails');
        
        // Handle thumbnail clicks
        $thumbnails.on('click', 'a', function(e) {
          e.preventDefault();
          
          const $link = $(this);
          const fullSrc = $link.attr('href');
          const imgSrc = $link.find('img').attr('src');
          const imgSrcset = $link.find('img').attr('srcset') || '';
          const imgAlt = $link.find('img').attr('alt') || '';
          
          // Update main image
          $mainImage.find('a').attr('href', fullSrc);
          $mainImage.find('img').attr({
            'src': imgSrc,
            'srcset': imgSrcset,
            'alt': imgAlt
          });
          
          // Update active thumbnail
          $thumbnails.find('.active').removeClass('active');
          $link.parent().addClass('active');
        });
        
        // Initialize zoom if enabled
        if ($mainImage.data('zoom') && $.fn.zoom) {
          $mainImage.zoom({
            url: $mainImage.find('a').attr('href'),
            touch: false
          });
        }
        
        // Initialize lightbox if enabled
        if ($mainImage.data('lightbox') && $.fn.magnificPopup) {
          $mainImage.find('a').magnificPopup({
            type: 'image',
            gallery: {
              enabled: true
            },
            image: {
              titleSrc: function(item) {
                return item.el.find('img').attr('alt');
              }
            }
          });
        }
      });
    },
    
    /**
     * Show notification
     * 
     * @param {string} message Notification message
     * @param {string} type Notification type (success, error, info, warning)
     */
    showNotification: function(message, type = 'success') {
      // Remove existing notifications
      $('.aqualuxe-notification').remove();
      
      // Create notification
      const $notification = $('<div class="aqualuxe-notification ' + type + '">' + message + '<button class="notification-close">&times;</button></div>');
      
      // Add to body
      $('body').append($notification);
      
      // Show notification
      setTimeout(function() {
        $notification.addClass('show');
      }, 10);
      
      // Auto-hide after 3 seconds
      setTimeout(function() {
        $notification.removeClass('show');
        
        setTimeout(function() {
          $notification.remove();
        }, 300);
      }, 3000);
      
      // Close button
      $notification.find('.notification-close').on('click', function() {
        $notification.removeClass('show');
        
        setTimeout(function() {
          $notification.remove();
        }, 300);
      });
    },
    
    /**
     * Set cookie
     * 
     * @param {string} name Cookie name
     * @param {string} value Cookie value
     * @param {number} days Cookie expiration in days
     */
    setCookie: function(name, value, days) {
      let expires = '';
      
      if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
      }
      
      document.cookie = name + '=' + value + expires + '; path=/; SameSite=Lax';
    },
    
    /**
     * Get cookie
     * 
     * @param {string} name Cookie name
     * @return {string|null} Cookie value or null if not found
     */
    getCookie: function(name) {
      const nameEQ = name + '=';
      const ca = document.cookie.split(';');
      
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
          c = c.substring(1, c.length);
        }
        if (c.indexOf(nameEQ) === 0) {
          return c.substring(nameEQ.length, c.length);
        }
      }
      
      return null;
    }
  };
  
})(jQuery);