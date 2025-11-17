/**
 * AquaLuxe Checkout Steps
 * 
 * Handles checkout steps functionality for the AquaLuxe theme
 */
(function($) {
    'use strict';

    // Main checkout steps object
    const AquaLuxeCheckoutSteps = {
        // Initialize the checkout steps
        init: function() {
            this.checkoutForm = $('form.woocommerce-checkout');
            this.checkoutSteps = $('.aqualuxe-checkout-steps');
            this.checkoutStep = $('.aqualuxe-checkout-step');
            this.currentStep = 1;
            this.onePageCheckout = aqualuxeCheckout.onePageCheckout === '1';
            
            // Create step containers
            this.createStepContainers();
            
            // Bind events
            this.bindEvents();
            
            // Initialize steps
            this.initSteps();
        },

        // Create step containers
        createStepContainers: function() {
            // Skip if one-page checkout is enabled
            if (this.onePageCheckout) {
                return;
            }
            
            // Create step containers
            const customerInfoFields = this.checkoutForm.find('.woocommerce-billing-fields, .woocommerce-account-fields, .woocommerce-additional-fields');
            const shippingFields = this.checkoutForm.find('.woocommerce-shipping-fields');
            const paymentFields = this.checkoutForm.find('#payment');
            const reviewFields = this.checkoutForm.find('.woocommerce-checkout-review-order');
            
            // Wrap fields in step containers
            customerInfoFields.wrapAll('<div class="aqualuxe-checkout-step-container" id="aqualuxe-checkout-step-1"></div>');
            shippingFields.wrapAll('<div class="aqualuxe-checkout-step-container" id="aqualuxe-checkout-step-2"></div>');
            paymentFields.wrapAll('<div class="aqualuxe-checkout-step-container" id="aqualuxe-checkout-step-3"></div>');
            reviewFields.wrapAll('<div class="aqualuxe-checkout-step-container" id="aqualuxe-checkout-step-4"></div>');
            
            // Add navigation buttons
            $('#aqualuxe-checkout-step-1').append('<div class="aqualuxe-checkout-step-buttons"><button type="button" class="button aqualuxe-checkout-next-step" data-step="1">' + aqualuxeCheckout.i18n.continue_to_shipping + '</button></div>');
            $('#aqualuxe-checkout-step-2').append('<div class="aqualuxe-checkout-step-buttons"><button type="button" class="button aqualuxe-checkout-prev-step" data-step="2">' + aqualuxeCheckout.i18n.previous + '</button><button type="button" class="button aqualuxe-checkout-next-step" data-step="2">' + aqualuxeCheckout.i18n.continue_to_payment + '</button></div>');
            $('#aqualuxe-checkout-step-3').append('<div class="aqualuxe-checkout-step-buttons"><button type="button" class="button aqualuxe-checkout-prev-step" data-step="3">' + aqualuxeCheckout.i18n.previous + '</button><button type="button" class="button aqualuxe-checkout-next-step" data-step="3">' + aqualuxeCheckout.i18n.continue_to_review + '</button></div>');
            $('#aqualuxe-checkout-step-4').append('<div class="aqualuxe-checkout-step-buttons"><button type="button" class="button aqualuxe-checkout-prev-step" data-step="4">' + aqualuxeCheckout.i18n.previous + '</button></div>');
            
            // Hide all step containers except the first one
            $('.aqualuxe-checkout-step-container').not('#aqualuxe-checkout-step-1').hide();
        },

        // Bind all events
        bindEvents: function() {
            const self = this;
            
            // Skip if one-page checkout is enabled
            if (this.onePageCheckout) {
                return;
            }
            
            // Step click
            this.checkoutStep.on('click', function() {
                const step = parseInt($(this).data('step'));
                
                // Only allow clicking on completed steps
                if ($(this).hasClass('completed')) {
                    self.goToStep(step);
                }
            });
            
            // Next step button click
            $(document).on('click', '.aqualuxe-checkout-next-step', function() {
                const step = parseInt($(this).data('step'));
                
                // Validate step fields
                if (self.validateStep(step)) {
                    self.goToStep(step + 1);
                }
            });
            
            // Previous step button click
            $(document).on('click', '.aqualuxe-checkout-prev-step', function() {
                const step = parseInt($(this).data('step'));
                
                self.goToStep(step - 1);
            });
            
            // Place order button click
            $(document).on('click', '#place_order', function(e) {
                // Skip if one-page checkout is enabled
                if (self.onePageCheckout) {
                    return;
                }
                
                // Prevent default action
                e.preventDefault();
                
                // Validate all steps
                if (self.validateAllSteps()) {
                    // Submit the form
                    self.checkoutForm.submit();
                } else {
                    // Go to the first invalid step
                    for (let i = 1; i <= 4; i++) {
                        if (!self.validateStep(i)) {
                            self.goToStep(i);
                            break;
                        }
                    }
                }
            });
        },

        // Initialize steps
        initSteps: function() {
            // Skip if one-page checkout is enabled
            if (this.onePageCheckout) {
                return;
            }
            
            // Get step from URL
            const urlParams = new URLSearchParams(window.location.search);
            const step = urlParams.get('step');
            
            if (step) {
                this.goToStep(parseInt(step));
            }
        },

        // Go to step
        goToStep: function(step) {
            // Skip if one-page checkout is enabled
            if (this.onePageCheckout) {
                return;
            }
            
            // Hide all step containers
            $('.aqualuxe-checkout-step-container').hide();
            
            // Show the selected step container
            $('#aqualuxe-checkout-step-' + step).show();
            
            // Update step classes
            this.checkoutStep.removeClass('active');
            this.checkoutStep.filter('[data-step="' + step + '"]').addClass('active');
            
            // Update completed steps
            this.checkoutStep.each(function() {
                const stepNum = parseInt($(this).data('step'));
                
                if (stepNum < step) {
                    $(this).addClass('completed');
                } else {
                    $(this).removeClass('completed');
                }
            });
            
            // Update current step
            this.currentStep = step;
            
            // Update URL
            this.updateUrl(step);
            
            // Scroll to top of checkout form
            $('html, body').animate({
                scrollTop: this.checkoutForm.offset().top - 50
            }, 500);
            
            // Trigger step change event
            $(document.body).trigger('aqualuxe_checkout_step_changed', [step]);
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCheckout.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_update_checkout_step',
                    nonce: aqualuxeCheckout.nonce,
                    step: step
                },
                success: function(response) {
                    // Success
                }
            });
        },

        // Validate step
        validateStep: function(step) {
            // Skip validation for one-page checkout
            if (this.onePageCheckout) {
                return true;
            }
            
            let isValid = true;
            
            // Get fields for the current step
            const $stepContainer = $('#aqualuxe-checkout-step-' + step);
            const $requiredFields = $stepContainer.find('.validate-required :input[required]');
            
            // Validate required fields
            $requiredFields.each(function() {
                const $field = $(this);
                const $parent = $field.closest('.form-row');
                
                if ($field.val() === '') {
                    $parent.addClass('woocommerce-invalid');
                    isValid = false;
                } else {
                    $parent.removeClass('woocommerce-invalid');
                }
            });
            
            // Show validation errors
            if (!isValid) {
                // Scroll to first invalid field
                const $firstInvalid = $stepContainer.find('.woocommerce-invalid').first();
                
                if ($firstInvalid.length) {
                    $('html, body').animate({
                        scrollTop: $firstInvalid.offset().top - 100
                    }, 500);
                }
            }
            
            return isValid;
        },

        // Validate all steps
        validateAllSteps: function() {
            // Skip validation for one-page checkout
            if (this.onePageCheckout) {
                return true;
            }
            
            let isValid = true;
            
            // Validate each step
            for (let i = 1; i <= 4; i++) {
                if (!this.validateStep(i)) {
                    isValid = false;
                }
            }
            
            return isValid;
        },

        // Update URL
        updateUrl: function(step) {
            // Skip for one-page checkout
            if (this.onePageCheckout) {
                return;
            }
            
            // Get current URL
            const url = new URL(window.location.href);
            
            // Update step parameter
            url.searchParams.set('step', step);
            
            // Update URL without reloading the page
            window.history.pushState({}, '', url);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        // Initialize checkout steps if on checkout page
        if ($('form.woocommerce-checkout').length) {
            AquaLuxeCheckoutSteps.init();
        }
    });

})(jQuery);