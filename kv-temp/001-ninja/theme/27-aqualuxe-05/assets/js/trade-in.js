/**
 * AquaLuxe Trade-In System
 * Handles trade-in functionality including request form submission
 */

(function($) {
  'use strict';
  
  // Trade-In System Object
  const AquaLuxeTradeIn = {
    // Cache DOM elements
    dom: {
      tradeInContainer: $('.trade-in-container'),
      tradeInForm: $('.trade-in-request-form'),
      tradeInResult: $('.trade-in-request-result'),
      itemTypeSelect: $('#item_type'),
      itemConditionSelect: $('#item_condition'),
      preferredValueSelect: $('#preferred_value'),
      submitButton: $('.trade-in-request-submit'),
      loadingOverlay: $('.trade-in-loading')
    },
    
    // Initialize trade-in system
    init: function() {
      const self = this;
      
      // Skip if trade-in container doesn't exist
      if (!this.dom.tradeInContainer.length) return;
      
      // Handle form submission
      if (this.dom.tradeInForm.length) {
        this.dom.tradeInForm.on('submit', function(e) {
          e.preventDefault();
          self.submitTradeInRequest();
        });
      }
      
      // Handle item type change
      if (this.dom.itemTypeSelect.length) {
        this.dom.itemTypeSelect.on('change', function() {
          self.updateFormFields();
        });
      }
      
      // Initialize form fields
      this.updateFormFields();
      
      // Initialize item gallery
      this.initItemGallery();
    },
    
    // Update form fields based on item type
    updateFormFields: function() {
      const itemType = this.dom.itemTypeSelect.val();
      
      // Show/hide fields based on item type
      $('.field-fish-specific').toggleClass('hidden', itemType !== 'fish');
      $('.field-equipment-specific').toggleClass('hidden', itemType !== 'equipment');
      $('.field-aquarium-specific').toggleClass('hidden', itemType !== 'aquarium');
      
      // Update condition options based on item type
      this.updateConditionOptions(itemType);
    },
    
    // Update condition options based on item type
    updateConditionOptions: function(itemType) {
      const conditionSelect = this.dom.itemConditionSelect;
      
      // Skip if select doesn't exist
      if (!conditionSelect.length) return;
      
      // Clear existing options except the placeholder
      conditionSelect.find('option:not([value=""])').remove();
      
      // Add options based on item type
      if (itemType === 'fish') {
        conditionSelect.append('<option value="excellent">Excellent - Healthy, vibrant colors</option>');
        conditionSelect.append('<option value="good">Good - Healthy, normal coloration</option>');
        conditionSelect.append('<option value="fair">Fair - Healthy, but some issues</option>');
      } else {
        conditionSelect.append('<option value="new">New - Never used</option>');
        conditionSelect.append('<option value="like-new">Like New - Used once or twice</option>');
        conditionSelect.append('<option value="excellent">Excellent - Minimal wear</option>');
        conditionSelect.append('<option value="good">Good - Normal wear</option>');
        conditionSelect.append('<option value="fair">Fair - Visible wear</option>');
        conditionSelect.append('<option value="poor">Poor - Significant wear</option>');
      }
    },
    
    // Initialize item gallery
    initItemGallery: function() {
      const gallery = $('.trade-in-item-gallery');
      
      // Skip if gallery doesn't exist
      if (!gallery.length) return;
      
      // Initialize lightbox if available
      if ($.fn.lightGallery) {
        gallery.lightGallery({
          selector: '.gallery-item',
          thumbnail: true,
          download: false
        });
      }
      
      // Initialize slider if available
      if ($.fn.slick) {
        $('.trade-in-item-slider').slick({
          dots: true,
          arrows: true,
          infinite: true,
          speed: 500,
          slidesToShow: 1,
          adaptiveHeight: true
        });
      }
    },
    
    // Submit trade-in request
    submitTradeInRequest: function() {
      const self = this;
      
      // Validate form
      if (!this.validateForm()) {
        return;
      }
      
      // Show loading overlay
      this.dom.loadingOverlay.removeClass('hidden');
      
      // Get form data
      const formData = this.dom.tradeInForm.serialize();
      
      // Submit request to the server
      $.ajax({
        url: aqualuxeTradeInSettings.ajaxUrl,
        type: 'POST',
        data: formData + '&action=aqualuxe_submit_trade_in_request&nonce=' + aqualuxeTradeInSettings.nonce,
        success: function(response) {
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
          
          if (response.success) {
            // Show success message
            self.showRequestResult(true, response.data.message);
            
            // Reset form
            self.dom.tradeInForm[0].reset();
            
            // Scroll to result message
            $('html, body').animate({
              scrollTop: self.dom.tradeInResult.offset().top - 100
            }, 500);
          } else {
            // Show error message
            self.showRequestResult(false, response.data.message);
          }
        },
        error: function(xhr, status, error) {
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
          
          // Show error message
          self.showRequestResult(false, 'An error occurred while processing your request. Please try again.');
          console.error('AJAX error:', error);
        }
      });
    },
    
    // Validate form
    validateForm: function() {
      const form = this.dom.tradeInForm[0];
      
      // Use HTML5 validation if available
      if (form.checkValidity) {
        return form.checkValidity();
      }
      
      // Manual validation as fallback
      let isValid = true;
      
      // Check required fields
      this.dom.tradeInForm.find('[required]').each(function() {
        if (!$(this).val()) {
          isValid = false;
          $(this).addClass('error');
        } else {
          $(this).removeClass('error');
        }
      });
      
      return isValid;
    },
    
    // Show request result message
    showRequestResult: function(success, message) {
      const resultElement = this.dom.tradeInResult;
      
      // Skip if result element doesn't exist
      if (!resultElement.length) return;
      
      // Set message and class
      resultElement.text(message);
      resultElement.removeClass('hidden success error');
      resultElement.addClass(success ? 'success' : 'error');
      
      // Hide message after 10 seconds if it's a success message
      if (success) {
        setTimeout(function() {
          resultElement.addClass('hidden');
        }, 10000);
      }
    }
  };
  
  // Initialize trade-in system when document is ready
  $(document).ready(function() {
    AquaLuxeTradeIn.init();
  });
  
})(jQuery);