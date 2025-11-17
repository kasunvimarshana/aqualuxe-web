/**
 * AquaLuxe Theme - WooCommerce Currency Switcher
 *
 * This file handles the currency switcher functionality for WooCommerce.
 */

(function($) {
  'use strict';

  // Initialize currency switcher
  function initCurrencySwitcher() {
    // Check if currency switcher exists
    const $currencySwitcher = $('.currency-switcher');
    
    if (!$currencySwitcher.length) {
      return;
    }

    // Get current currency
    const currentCurrency = $currencySwitcher.data('current-currency') || 'USD';
    
    // Set current currency as active
    $currencySwitcher.find(`[data-currency="${currentCurrency}"]`).addClass('active');
    
    // Handle currency switch
    $currencySwitcher.on('click', '.currency-option', function(e) {
      e.preventDefault();
      
      const currency = $(this).data('currency');
      
      // Skip if already active
      if ($(this).hasClass('active')) {
        return;
      }
      
      // Update active class
      $currencySwitcher.find('.currency-option').removeClass('active');
      $(this).addClass('active');
      
      // Show loading overlay
      $('body').append('<div class="currency-loading-overlay"><div class="currency-loading-spinner"></div></div>');
      
      // Send AJAX request to switch currency
      $.ajax({
        url: aqualuxeVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'aqualuxe_switch_currency',
          currency: currency,
          nonce: aqualuxeVars.nonce
        },
        success: function(response) {
          if (response.success) {
            // Reload page to update prices
            window.location.reload();
          } else {
            // Remove loading overlay
            $('.currency-loading-overlay').remove();
            
            // Show error message
            alert('Failed to switch currency. Please try again.');
          }
        },
        error: function() {
          // Remove loading overlay
          $('.currency-loading-overlay').remove();
          
          // Show error message
          alert('Failed to switch currency. Please try again.');
        }
      });
    });

    // Create dropdown currency switcher
    const $dropdownSwitcher = $('.currency-switcher-dropdown');
    
    if ($dropdownSwitcher.length) {
      // Get current currency
      const currentCurrency = $dropdownSwitcher.data('current-currency') || 'USD';
      
      // Create select element
      const $select = $('<select class="currency-select"></select>');
      
      // Add options
      $dropdownSwitcher.find('.currency-option').each(function() {
        const currency = $(this).data('currency');
        const symbol = $(this).data('symbol');
        const selected = currency === currentCurrency ? ' selected' : '';
        
        $select.append(`<option value="${currency}"${selected}>${currency} (${symbol})</option>`);
      });
      
      // Replace dropdown with select
      $dropdownSwitcher.html($select);
      
      // Handle currency switch
      $select.on('change', function() {
        const currency = $(this).val();
        
        // Show loading overlay
        $('body').append('<div class="currency-loading-overlay"><div class="currency-loading-spinner"></div></div>');
        
        // Send AJAX request to switch currency
        $.ajax({
          url: aqualuxeVars.ajaxurl,
          type: 'POST',
          data: {
            action: 'aqualuxe_switch_currency',
            currency: currency,
            nonce: aqualuxeVars.nonce
          },
          success: function(response) {
            if (response.success) {
              // Reload page to update prices
              window.location.reload();
            } else {
              // Remove loading overlay
              $('.currency-loading-overlay').remove();
              
              // Show error message
              alert('Failed to switch currency. Please try again.');
            }
          },
          error: function() {
            // Remove loading overlay
            $('.currency-loading-overlay').remove();
            
            // Show error message
            alert('Failed to switch currency. Please try again.');
          }
        });
      });
    }
  }

  // Initialize when document is ready
  $(document).ready(function() {
    initCurrencySwitcher();
  });
})(jQuery);