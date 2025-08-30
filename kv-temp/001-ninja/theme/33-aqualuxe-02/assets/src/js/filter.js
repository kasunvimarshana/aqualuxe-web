/**
 * AquaLuxe Theme - WooCommerce AJAX Product Filter
 *
 * This file handles the AJAX product filtering functionality for WooCommerce.
 */

(function($) {
  'use strict';

  // Initialize product filters
  function initProductFilters() {
    // Check if product filters exist
    const $productFilters = $('.product-filters');
    
    if (!$productFilters.length) {
      return;
    }

    // Get filter form
    const $filterForm = $productFilters.find('form');
    
    // Get products container
    const $productsContainer = $('.products');
    
    // Get pagination container
    const $paginationContainer = $('.woocommerce-pagination');
    
    // Get ordering container
    const $orderingContainer = $('.woocommerce-ordering');
    
    // Get result count container
    const $resultCountContainer = $('.woocommerce-result-count');
    
    // Handle filter form submission
    $filterForm.on('submit', function(e) {
      e.preventDefault();
      
      // Show loading overlay
      $('body').append('<div class="filter-loading-overlay"><div class="filter-loading-spinner"></div></div>');
      
      // Get form data
      const formData = $(this).serialize();
      
      // Get current URL
      const currentUrl = window.location.href.split('?')[0];
      
      // Send AJAX request
      $.ajax({
        url: currentUrl,
        type: 'GET',
        data: formData,
        success: function(response) {
          // Parse HTML response
          const $html = $(response);
          
          // Update products
          $productsContainer.html($html.find('.products').html());
          
          // Update pagination
          $paginationContainer.html($html.find('.woocommerce-pagination').html());
          
          // Update ordering
          $orderingContainer.html($html.find('.woocommerce-ordering').html());
          
          // Update result count
          $resultCountContainer.html($html.find('.woocommerce-result-count').html());
          
          // Update URL
          const newUrl = currentUrl + '?' + formData;
          window.history.pushState({}, '', newUrl);
          
          // Remove loading overlay
          $('.filter-loading-overlay').remove();
          
          // Scroll to top of products
          $('html, body').animate({
            scrollTop: $productsContainer.offset().top - 100
          }, 500);
          
          // Trigger custom event
          $(document.body).trigger('aqualuxe_products_filtered');
        },
        error: function() {
          // Remove loading overlay
          $('.filter-loading-overlay').remove();
          
          // Show error message
          alert('Failed to filter products. Please try again.');
        }
      });
    });

    // Handle filter changes
    $filterForm.find('input[type="checkbox"], input[type="radio"], select').on('change', function() {
      // Submit form
      $filterForm.submit();
    });

    // Handle price slider
    const $priceSlider = $filterForm.find('.price-slider');
    
    if ($priceSlider.length) {
      const $minPrice = $filterForm.find('input[name="min_price"]');
      const $maxPrice = $filterForm.find('input[name="max_price"]');
      const $minPriceDisplay = $filterForm.find('.min-price-display');
      const $maxPriceDisplay = $filterForm.find('.max-price-display');
      const minPrice = parseInt($minPrice.data('min'), 10);
      const maxPrice = parseInt($maxPrice.data('max'), 10);
      const currentMinPrice = parseInt($minPrice.val(), 10) || minPrice;
      const currentMaxPrice = parseInt($maxPrice.val(), 10) || maxPrice;
      
      // Initialize price slider
      $priceSlider.slider({
        range: true,
        min: minPrice,
        max: maxPrice,
        values: [currentMinPrice, currentMaxPrice],
        slide: function(event, ui) {
          $minPrice.val(ui.values[0]);
          $maxPrice.val(ui.values[1]);
          $minPriceDisplay.text(formatPrice(ui.values[0]));
          $maxPriceDisplay.text(formatPrice(ui.values[1]));
        },
        change: function() {
          // Submit form
          $filterForm.submit();
        }
      });
      
      // Update price display
      $minPriceDisplay.text(formatPrice(currentMinPrice));
      $maxPriceDisplay.text(formatPrice(currentMaxPrice));
      
      // Format price
      function formatPrice(price) {
        return aqualuxeVars.currencySymbol + price.toFixed(2);
      }
    }

    // Handle mobile filter toggle
    const $filterToggle = $('.filter-toggle');
    
    if ($filterToggle.length) {
      $filterToggle.on('click', function(e) {
        e.preventDefault();
        
        $productFilters.toggleClass('active');
        
        if ($productFilters.hasClass('active')) {
          $filterToggle.text('Hide Filters');
        } else {
          $filterToggle.text('Show Filters');
        }
      });
    }

    // Handle clear filters
    const $clearFilters = $('.clear-filters');
    
    if ($clearFilters.length) {
      $clearFilters.on('click', function(e) {
        e.preventDefault();
        
        // Reset form
        $filterForm[0].reset();
        
        // Reset price slider
        if ($priceSlider.length) {
          const minPrice = parseInt($minPrice.data('min'), 10);
          const maxPrice = parseInt($maxPrice.data('max'), 10);
          
          $priceSlider.slider('values', [minPrice, maxPrice]);
          $minPrice.val(minPrice);
          $maxPrice.val(maxPrice);
          $minPriceDisplay.text(formatPrice(minPrice));
          $maxPriceDisplay.text(formatPrice(maxPrice));
        }
        
        // Submit form
        $filterForm.submit();
      });
    }
  }

  // Initialize when document is ready
  $(document).ready(function() {
    initProductFilters();
  });
})(jQuery);