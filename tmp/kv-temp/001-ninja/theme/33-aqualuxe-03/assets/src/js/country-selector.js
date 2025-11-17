/**
 * AquaLuxe Theme - WooCommerce Country Selector
 *
 * This file handles the enhanced country selector for WooCommerce.
 */

(function($) {
  'use strict';

  // Initialize country selector
  function initCountrySelector() {
    // Check if WooCommerce country selectors exist
    const $countrySelectors = $('#billing_country, #shipping_country, #calc_shipping_country');
    
    if (!$countrySelectors.length) {
      return;
    }

    // Add flag icons to country selectors
    $countrySelectors.each(function() {
      const $select = $(this);
      
      // Skip if already enhanced
      if ($select.hasClass('country-select-enhanced')) {
        return;
      }
      
      // Add enhanced class
      $select.addClass('country-select-enhanced');
      
      // Create wrapper
      $select.wrap('<div class="country-select-wrapper"></div>');
      
      // Add flag icon
      $select.before('<span class="country-flag"></span>');
      
      // Update flag icon when country changes
      $select.on('change', function() {
        const countryCode = $(this).val().toLowerCase();
        const $flag = $(this).prev('.country-flag');
        
        if (countryCode) {
          $flag.css('background-image', `url('../images/flags/${countryCode}.svg')`);
        } else {
          $flag.css('background-image', 'none');
        }
      });
      
      // Trigger change to set initial flag
      $select.trigger('change');
    });

    // Add search functionality to country selectors
    $countrySelectors.each(function() {
      const $select = $(this);
      const $wrapper = $select.parent('.country-select-wrapper');
      
      // Create search input
      $wrapper.append('<input type="text" class="country-search" placeholder="Search country..." />');
      
      // Create dropdown
      $wrapper.append('<div class="country-dropdown"></div>');
      
      // Populate dropdown
      const $dropdown = $wrapper.find('.country-dropdown');
      
      $select.find('option').each(function() {
        const value = $(this).val();
        const text = $(this).text();
        
        if (value) {
          $dropdown.append(`<div class="country-option" data-value="${value}">${text}</div>`);
        }
      });
      
      // Show dropdown when clicking on select
      $select.on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        $dropdown.toggle();
        $wrapper.find('.country-search').focus();
      });
      
      // Filter countries when typing in search input
      $wrapper.find('.country-search').on('input', function() {
        const searchText = $(this).val().toLowerCase();
        
        $dropdown.find('.country-option').each(function() {
          const countryName = $(this).text().toLowerCase();
          
          if (countryName.indexOf(searchText) > -1) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      });
      
      // Select country when clicking on option
      $dropdown.on('click', '.country-option', function() {
        const value = $(this).data('value');
        
        $select.val(value).trigger('change');
        $dropdown.hide();
      });
      
      // Hide dropdown when clicking outside
      $(document).on('click', function(e) {
        if (!$wrapper.is(e.target) && $wrapper.has(e.target).length === 0) {
          $dropdown.hide();
        }
      });
    });
  }

  // Initialize when document is ready
  $(document).ready(function() {
    initCountrySelector();
    
    // Also initialize when checkout is updated
    $(document.body).on('updated_checkout', function() {
      initCountrySelector();
    });
  });
})(jQuery);