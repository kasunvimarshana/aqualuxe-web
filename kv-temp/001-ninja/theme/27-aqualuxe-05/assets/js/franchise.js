/**
 * AquaLuxe Franchise Inquiry System
 * Handles franchise inquiry form submission and validation
 */

(function($) {
  'use strict';
  
  // Franchise Inquiry System Object
  const AquaLuxeFranchise = {
    // Cache DOM elements
    dom: {
      franchiseContainer: $('.franchise-container'),
      franchiseForm: $('.franchise-inquiry-form'),
      franchiseResult: $('.franchise-inquiry-result'),
      submitButton: $('.franchise-inquiry-submit'),
      loadingOverlay: $('.franchise-loading'),
      progressSteps: $('.franchise-form-progress-step'),
      formSections: $('.franchise-form-section'),
      nextButtons: $('.franchise-form-next'),
      prevButtons: $('.franchise-form-prev'),
      progressBar: $('.franchise-form-progress-bar')
    },
    
    // Current form step
    currentStep: 0,
    totalSteps: 0,
    
    // Initialize franchise inquiry system
    init: function() {
      const self = this;
      
      // Skip if franchise container doesn't exist
      if (!this.dom.franchiseContainer.length) return;
      
      // Set total steps
      this.totalSteps = this.dom.formSections.length;
      
      // Handle form submission
      if (this.dom.franchiseForm.length) {
        this.dom.franchiseForm.on('submit', function(e) {
          e.preventDefault();
          self.submitInquiry();
        });
      }
      
      // Handle next button clicks
      this.dom.nextButtons.on('click', function(e) {
        e.preventDefault();
        self.nextStep();
      });
      
      // Handle previous button clicks
      this.dom.prevButtons.on('click', function(e) {
        e.preventDefault();
        self.prevStep();
      });
      
      // Initialize form
      this.showStep(0);
      this.updateProgressBar();
    },
    
    // Show form step
    showStep: function(stepIndex) {
      // Hide all sections
      this.dom.formSections.addClass('hidden');
      
      // Show current section
      this.dom.formSections.eq(stepIndex).removeClass('hidden');
      
      // Update progress steps
      this.dom.progressSteps.removeClass('active completed');
      
      // Mark current and previous steps
      for (let i = 0; i <= stepIndex; i++) {
        if (i < stepIndex) {
          this.dom.progressSteps.eq(i).addClass('completed');
        } else {
          this.dom.progressSteps.eq(i).addClass('active');
        }
      }
      
      // Update current step
      this.currentStep = stepIndex;
      
      // Update progress bar
      this.updateProgressBar();
      
      // Scroll to top of form
      $('html, body').animate({
        scrollTop: this.dom.franchiseForm.offset().top - 100
      }, 500);
    },
    
    // Go to next step
    nextStep: function() {
      // Validate current step
      if (!this.validateStep(this.currentStep)) {
        return;
      }
      
      // Go to next step if not last
      if (this.currentStep < this.totalSteps - 1) {
        this.showStep(this.currentStep + 1);
      }
    },
    
    // Go to previous step
    prevStep: function() {
      // Go to previous step if not first
      if (this.currentStep > 0) {
        this.showStep(this.currentStep - 1);
      }
    },
    
    // Update progress bar
    updateProgressBar: function() {
      const progress = ((this.currentStep + 1) / this.totalSteps) * 100;
      this.dom.progressBar.css('width', progress + '%');
    },
    
    // Validate current step
    validateStep: function(stepIndex) {
      const currentSection = this.dom.formSections.eq(stepIndex);
      let isValid = true;
      
      // Check required fields in current section
      currentSection.find('[required]').each(function() {
        if (!$(this).val()) {
          isValid = false;
          $(this).addClass('error');
          
          // Show error message
          const fieldName = $(this).attr('name');
          const errorElement = currentSection.find('.error-' + fieldName);
          
          if (errorElement.length) {
            errorElement.removeClass('hidden');
          }
        } else {
          $(this).removeClass('error');
          
          // Hide error message
          const fieldName = $(this).attr('name');
          const errorElement = currentSection.find('.error-' + fieldName);
          
          if (errorElement.length) {
            errorElement.addClass('hidden');
          }
        }
      });
      
      // Validate email if present
      const emailField = currentSection.find('input[type="email"]');
      if (emailField.length && emailField.val()) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailField.val())) {
          isValid = false;
          emailField.addClass('error');
          
          // Show error message
          const fieldName = emailField.attr('name');
          const errorElement = currentSection.find('.error-' + fieldName);
          
          if (errorElement.length) {
            errorElement.removeClass('hidden').text('Please enter a valid email address.');
          }
        }
      }
      
      // Validate phone if present
      const phoneField = currentSection.find('input[name="phone"]');
      if (phoneField.length && phoneField.val()) {
        const phonePattern = /^[0-9\-\(\)\s\+\.]+$/;
        if (!phonePattern.test(phoneField.val())) {
          isValid = false;
          phoneField.addClass('error');
          
          // Show error message
          const errorElement = currentSection.find('.error-phone');
          
          if (errorElement.length) {
            errorElement.removeClass('hidden').text('Please enter a valid phone number.');
          }
        }
      }
      
      return isValid;
    },
    
    // Submit franchise inquiry
    submitInquiry: function() {
      const self = this;
      
      // Validate all steps
      let isValid = true;
      for (let i = 0; i < this.totalSteps; i++) {
        if (!this.validateStep(i)) {
          isValid = false;
          this.showStep(i);
          break;
        }
      }
      
      if (!isValid) {
        return;
      }
      
      // Show loading overlay
      this.dom.loadingOverlay.removeClass('hidden');
      
      // Get form data
      const formData = this.dom.franchiseForm.serialize();
      
      // Submit inquiry to the server
      $.ajax({
        url: aqualuxeFranchiseSettings.ajaxUrl,
        type: 'POST',
        data: formData + '&action=aqualuxe_submit_franchise_inquiry&nonce=' + aqualuxeFranchiseSettings.nonce,
        success: function(response) {
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
          
          if (response.success) {
            // Show success message
            self.showInquiryResult(true, response.data.message);
            
            // Reset form
            self.dom.franchiseForm[0].reset();
            
            // Go to first step
            self.showStep(0);
            
            // Hide form
            self.dom.franchiseForm.addClass('hidden');
          } else {
            // Show error message
            self.showInquiryResult(false, response.data.message);
          }
        },
        error: function(xhr, status, error) {
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
          
          // Show error message
          self.showInquiryResult(false, 'An error occurred while processing your inquiry. Please try again.');
          console.error('AJAX error:', error);
        }
      });
    },
    
    // Show inquiry result message
    showInquiryResult: function(success, message) {
      const resultElement = this.dom.franchiseResult;
      
      // Skip if result element doesn't exist
      if (!resultElement.length) return;
      
      // Set message and class
      resultElement.text(message);
      resultElement.removeClass('hidden success error');
      resultElement.addClass(success ? 'success' : 'error');
      
      // Scroll to result message
      $('html, body').animate({
        scrollTop: resultElement.offset().top - 100
      }, 500);
    }
  };
  
  // Initialize franchise inquiry system when document is ready
  $(document).ready(function() {
    AquaLuxeFranchise.init();
  });
  
})(jQuery);