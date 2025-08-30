/**
 * AquaLuxe Theme - WooCommerce Checkout Steps
 *
 * This file handles the multi-step checkout process for WooCommerce.
 */

(function($) {
  'use strict';

  // Initialize checkout steps
  function initCheckoutSteps() {
    if (!$('.woocommerce-checkout').length) {
      return;
    }

    // Create step navigation
    const $checkoutForm = $('.woocommerce-checkout');
    const $steps = [
      {
        id: 'customer',
        title: 'Customer Information',
        selector: '.woocommerce-billing-fields, .woocommerce-shipping-fields, .woocommerce-account-fields'
      },
      {
        id: 'shipping',
        title: 'Shipping Method',
        selector: '.woocommerce-shipping-methods'
      },
      {
        id: 'payment',
        title: 'Payment Method',
        selector: '#payment'
      },
      {
        id: 'review',
        title: 'Review Order',
        selector: '.woocommerce-checkout-review-order-table'
      }
    ];

    // Create step navigation HTML
    let stepsHtml = '<div class="checkout-steps">';
    stepsHtml += '<ul class="checkout-steps-list">';
    
    $steps.forEach(function(step, index) {
      stepsHtml += `<li class="checkout-step ${index === 0 ? 'active' : ''}" data-step="${step.id}">`;
      stepsHtml += `<span class="step-number">${index + 1}</span>`;
      stepsHtml += `<span class="step-title">${step.title}</span>`;
      stepsHtml += '</li>';
    });
    
    stepsHtml += '</ul>';
    stepsHtml += '</div>';

    // Insert step navigation before checkout form
    $checkoutForm.before(stepsHtml);

    // Create step containers
    $steps.forEach(function(step, index) {
      const $stepElements = $checkoutForm.find(step.selector);
      
      if ($stepElements.length) {
        $stepElements.wrapAll(`<div class="checkout-step-content" id="step-${step.id}" ${index === 0 ? '' : 'style="display: none;"'}></div>`);
      }
    });

    // Add navigation buttons
    $('.checkout-step-content').each(function(index) {
      const $stepContent = $(this);
      const isFirst = index === 0;
      const isLast = index === $steps.length - 1;
      let buttonsHtml = '<div class="checkout-step-buttons">';
      
      if (!isFirst) {
        buttonsHtml += '<button type="button" class="button alt prev-step">Previous</button>';
      }
      
      if (!isLast) {
        buttonsHtml += '<button type="button" class="button alt next-step">Continue</button>';
      }
      
      buttonsHtml += '</div>';
      
      $stepContent.append(buttonsHtml);
    });

    // Handle next step button click
    $('.next-step').on('click', function() {
      const $currentStep = $(this).closest('.checkout-step-content');
      const currentIndex = $('.checkout-step-content').index($currentStep);
      const $nextStep = $('.checkout-step-content').eq(currentIndex + 1);
      const nextStepId = $nextStep.attr('id').replace('step-', '');
      
      // Validate current step
      if (validateStep($currentStep)) {
        // Hide current step
        $currentStep.hide();
        
        // Show next step
        $nextStep.show();
        
        // Update step navigation
        $('.checkout-step').removeClass('active');
        $(`.checkout-step[data-step="${nextStepId}"]`).addClass('active');
        
        // Scroll to top of checkout
        $('html, body').animate({
          scrollTop: $('.checkout-steps').offset().top - 50
        }, 500);
      }
    });

    // Handle previous step button click
    $('.prev-step').on('click', function() {
      const $currentStep = $(this).closest('.checkout-step-content');
      const currentIndex = $('.checkout-step-content').index($currentStep);
      const $prevStep = $('.checkout-step-content').eq(currentIndex - 1);
      const prevStepId = $prevStep.attr('id').replace('step-', '');
      
      // Hide current step
      $currentStep.hide();
      
      // Show previous step
      $prevStep.show();
      
      // Update step navigation
      $('.checkout-step').removeClass('active');
      $(`.checkout-step[data-step="${prevStepId}"]`).addClass('active');
      
      // Scroll to top of checkout
      $('html, body').animate({
        scrollTop: $('.checkout-steps').offset().top - 50
      }, 500);
    });

    // Handle step navigation click
    $('.checkout-step').on('click', function() {
      const stepId = $(this).data('step');
      const $targetStep = $(`#step-${stepId}`);
      const targetIndex = $('.checkout-step-content').index($targetStep);
      const $currentStep = $('.checkout-step-content:visible');
      const currentIndex = $('.checkout-step-content').index($currentStep);
      
      // Only allow going back to previous steps
      if (targetIndex < currentIndex) {
        // Hide current step
        $currentStep.hide();
        
        // Show target step
        $targetStep.show();
        
        // Update step navigation
        $('.checkout-step').removeClass('active');
        $(this).addClass('active');
        
        // Scroll to top of checkout
        $('html, body').animate({
          scrollTop: $('.checkout-steps').offset().top - 50
        }, 500);
      }
    });

    // Move the place order button to the last step
    $('#place_order').appendTo('#step-review .checkout-step-buttons');

    // Validate step
    function validateStep($step) {
      let isValid = true;
      
      // Check required fields
      $step.find('.validate-required input, .validate-required select, .validate-required textarea').each(function() {
        if ($(this).val() === '') {
          isValid = false;
          $(this).addClass('error').after('<span class="error-message">This field is required.</span>');
        } else {
          $(this).removeClass('error').next('.error-message').remove();
        }
      });
      
      return isValid;
    }
  }

  // Initialize when document is ready
  $(document).ready(function() {
    initCheckoutSteps();
  });
})(jQuery);