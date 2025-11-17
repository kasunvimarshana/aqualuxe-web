/**
 * AquaLuxe WooCommerce JS
 * JavaScript functionality specific to WooCommerce integration
 */

(function($) {
  'use strict';
  
  // Check if WooCommerce is active
  if (typeof woocommerce_params === 'undefined') {
    return;
  }
  
  // Global variables
  const aqualuxeWooCommerce = {
    // Cache DOM elements
    dom: {
      body: $('body'),
      productGallery: $('.woocommerce-product-gallery'),
      quantityInputs: $('.quantity input'),
      addToCartButtons: $('.add_to_cart_button'),
      variationForms: $('.variations_form'),
      productTabs: $('.woocommerce-tabs'),
      reviewForm: $('#review_form'),
      cartForm: $('.woocommerce-cart-form'),
      checkoutForm: $('.woocommerce-checkout'),
      couponForm: $('.checkout_coupon'),
      wishlistButtons: $('.wishlist-toggle'),
      compareButtons: $('.compare-button'),
      quickViewButtons: $('.quick-view-button')
    },
    
    // Initialize all functions
    init: function() {
      this.enhanceProductGallery();
      this.enhanceQuantityInputs();
      this.quickView();
      this.wishlistToggle();
      this.compareProducts();
      this.ajaxAddToCart();
      this.enhanceVariationForms();
      this.enhanceCheckout();
    },
    
    // Enhance product gallery
    enhanceProductGallery: function() {
      const { productGallery } = this.dom;
      
      if (!productGallery.length) return;
      
      // Product gallery zoom effect
      productGallery.on('mousemove', '.woocommerce-product-gallery__image', function(e) {
        const $img = $(this).find('img');
        const offset = $(this).offset();
        const x = e.pageX - offset.left;
        const y = e.pageY - offset.top;
        const imgWidth = $img.width();
        const imgHeight = $img.height();
        
        // Calculate zoom position
        const xPercent = (x / imgWidth) * 100;
        const yPercent = (y / imgHeight) * 100;
        
        // Apply zoom effect
        $img.css('transform-origin', `${xPercent}% ${yPercent}%`);
      });
      
      productGallery.on('mouseenter', '.woocommerce-product-gallery__image', function() {
        $(this).find('img').addClass('zoomed');
      });
      
      productGallery.on('mouseleave', '.woocommerce-product-gallery__image', function() {
        $(this).find('img').removeClass('zoomed');
      });
    },
    
    // Enhance quantity inputs
    enhanceQuantityInputs: function() {
      const { quantityInputs } = this.dom;
      
      if (!quantityInputs.length) return;
      
      // Add increment/decrement buttons
      quantityInputs.each(function() {
        const $input = $(this);
        const $wrapper = $input.parent();
        
        if ($wrapper.find('.quantity-button').length) return; // Already enhanced
        
        $wrapper.addClass('quantity-buttons');
        $input.before('<button type="button" class="quantity-button minus" aria-label="Decrease quantity">-</button>');
        $input.after('<button type="button" class="quantity-button plus" aria-label="Increase quantity">+</button>');
      });
      
      // Handle button clicks
      $(document).on('click', '.quantity-button', function() {
        const $button = $(this);
        const $input = $button.parent().find('input');
        const oldValue = parseFloat($input.val());
        let newVal;
        
        if ($button.hasClass('plus')) {
          const max = parseFloat($input.attr('max'));
          newVal = oldValue + 1;
          if (max && newVal > max) newVal = max;
        } else {
          const min = parseFloat($input.attr('min'));
          newVal = oldValue - 1;
          if (min && newVal < min) newVal = min;
          if (!min && newVal < 1) newVal = 1;
        }
        
        $input.val(newVal).trigger('change');
      });
    },
    
    // Quick view functionality
    quickView: function() {
      const { quickViewButtons, body } = this.dom;
      
      if (!quickViewButtons.length) return;
      
      $(document).on('click', '.quick-view-button', function(e) {
        e.preventDefault();
        
        const productId = $(this).data('product-id');
        
        // Show loading state
        body.addClass('loading');
        
        // Fetch product data via AJAX
        $.ajax({
          url: aqualuxeSettings.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_quick_view',
            product_id: productId,
            nonce: aqualuxeSettings.nonce
          },
          success: function(response) {
            if (!response.success) {
              body.removeClass('loading');
              return;
            }
            
            // Create modal with product data
            const modal = $('<div class="quick-view-modal"></div>');
            modal.html(response.data);
            body.append(modal);
            
            // Initialize product gallery in modal
            if (typeof $.fn.wc_product_gallery === 'function') {
              modal.find('.woocommerce-product-gallery').each(function() {
                $(this).wc_product_gallery();
              });
            }
            
            // Show modal
            setTimeout(function() {
              modal.addClass('open');
              body.removeClass('loading');
            }, 100);
            
            // Close modal on click outside or ESC key
            modal.on('click', function(e) {
              if ($(e.target).is('.quick-view-modal') || $(e.target).is('.close-modal')) {
                closeModal();
              }
            });
            
            $(document).on('keydown.quickView', function(e) {
              if (e.key === 'Escape') {
                closeModal();
              }
            });
            
            function closeModal() {
              modal.removeClass('open');
              setTimeout(function() {
                modal.remove();
                $(document).off('keydown.quickView');
              }, 300);
            }
          },
          error: function() {
            body.removeClass('loading');
            console.error('Error loading product quick view.');
          }
        });
      });
    },
    
    // Wishlist toggle functionality
    wishlistToggle: function() {
      const { wishlistButtons } = this.dom;
      
      if (!wishlistButtons.length) return;
      
      $(document).on('click', '.wishlist-toggle', function(e) {
        e.preventDefault();
        
        // Check if user is logged in
        if ($(this).hasClass('wishlist-login-required')) {
          window.location.href = $(this).attr('href');
          return;
        }
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        $.ajax({
          url: aqualuxeSettings.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_wishlist',
            product_id: productId,
            nonce: aqualuxeSettings.nonce
          },
          success: function(response) {
            if (!response.success) {
              if (response.data && response.data.message) {
                alert(response.data.message);
              }
              return;
            }
            
            if (response.data.action === 'added') {
              $button.addClass('in-wishlist');
              $button.html('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>');
            } else {
              $button.removeClass('in-wishlist');
              $button.html('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>');
            }
          }
        });
      });
    },
    
    // Compare products functionality
    compareProducts: function() {
      const { compareButtons } = this.dom;
      
      if (!compareButtons.length) return;
      
      $(document).on('click', '.compare-button', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        $.ajax({
          url: aqualuxeSettings.ajaxUrl,
          type: 'POST',
          data: {
            action: 'aqualuxe_compare',
            product_id: productId,
            nonce: aqualuxeSettings.nonce
          },
          success: function(response) {
            if (!response.success) {
              if (response.data && response.data.message) {
                alert(response.data.message);
              }
              return;
            }
            
            if (response.data.action === 'added') {
              $button.addClass('in-compare');
              $button.text('Remove from Compare');
            } else {
              $button.removeClass('in-compare');
              $button.text('Compare');
            }
          }
        });
      });
    },
    
    // AJAX add to cart
    ajaxAddToCart: function() {
      const { body, addToCartButtons } = this.dom;
      
      if (!addToCartButtons.length) return;
      
      $(document).on('click', '.add_to_cart_button:not(.product_type_variable)', function(e) {
        // Default WooCommerce AJAX handling will take care of this
        // We just add some custom UI feedback
        
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
          // Show success message
          const $message = $('<div class="cart-notification">Product added to cart</div>');
          body.append($message);
          
          setTimeout(function() {
            $message.addClass('show');
          }, 100);
          
          setTimeout(function() {
            $message.removeClass('show');
            setTimeout(function() {
              $message.remove();
            }, 300);
          }, 3000);
        });
      });
    },
    
    // Enhance variation forms
    enhanceVariationForms: function() {
      const { variationForms } = this.dom;
      
      if (!variationForms.length) return;
      
      variationForms.each(function() {
        const $form = $(this);
        
        // Add custom styling to variation swatches if they exist
        $form.find('.variations select').each(function() {
          const $select = $(this);
          const attributeName = $select.attr('name');
          
          // Check if this is a color attribute
          if (attributeName.indexOf('color') > -1 || attributeName.indexOf('colour') > -1) {
            // Create color swatches
            const $wrapper = $select.parent();
            const $swatchContainer = $('<div class="color-swatches"></div>');
            
            $select.find('option').each(function() {
              const $option = $(this);
              const value = $option.attr('value');
              const text = $option.text();
              
              if (!value) return; // Skip empty option
              
              const $swatch = $('<div class="color-swatch" data-value="' + value + '" title="' + text + '"></div>');
              $swatch.css('background-color', value);
              $swatchContainer.append($swatch);
            });
            
            $wrapper.append($swatchContainer);
            
            // Handle swatch clicks
            $swatchContainer.on('click', '.color-swatch', function() {
              const value = $(this).data('value');
              $select.val(value).trigger('change');
              $swatchContainer.find('.color-swatch').removeClass('selected');
              $(this).addClass('selected');
            });
          }
        });
      });
    },
    
    // Enhance checkout
    enhanceCheckout: function() {
      const { checkoutForm, couponForm } = this.dom;
      
      if (!checkoutForm.length) return;
      
      // Toggle coupon form
      $('.showcoupon').on('click', function(e) {
        e.preventDefault();
        couponForm.slideToggle(300);
      });
      
      // Add custom validation
      checkoutForm.on('checkout_place_order', function() {
        // Custom validation logic could be added here
        return true;
      });
    }
  };
  
  // Initialize when DOM is loaded
  $(document).ready(function() {
    aqualuxeWooCommerce.init();
  });
  
})(jQuery);