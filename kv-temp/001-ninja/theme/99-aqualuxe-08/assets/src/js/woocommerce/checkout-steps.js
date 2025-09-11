// Checkout step functionality
(function($) {
    'use strict';
    
    const CheckoutSteps = {
        init: function() {
            this.createStepIndicator();
            this.bindEvents();
        },
        
        createStepIndicator: function() {
            if ($('.woocommerce-checkout').length) {
                const steps = [
                    { id: 'billing', label: 'Billing Details' },
                    { id: 'shipping', label: 'Shipping' },
                    { id: 'payment', label: 'Payment' }
                ];
                
                const $indicator = $('<div class="checkout-steps"></div>');
                
                steps.forEach((step, index) => {
                    const $step = $(`
                        <div class="checkout-step" data-step="${step.id}">
                            <div class="step-number">${index + 1}</div>
                            <div class="step-label">${step.label}</div>
                        </div>
                    `);
                    $indicator.append($step);
                });
                
                $('.woocommerce-checkout').prepend($indicator);
                this.updateActiveStep();
            }
        },
        
        bindEvents: function() {
            // Update active step on field changes
            $(document).on('change', 'input, select', () => {
                this.updateActiveStep();
            });
        },
        
        updateActiveStep: function() {
            const $steps = $('.checkout-step');
            $steps.removeClass('active completed');
            
            // Simple logic to determine current step
            if ($('#billing_email').val()) {
                $('.checkout-step[data-step="billing"]').addClass('completed');
            }
            
            if ($('#shipping_method').length && $('#shipping_method').val()) {
                $('.checkout-step[data-step="shipping"]').addClass('completed');
            }
            
            // Mark current step as active
            const $incomplete = $steps.not('.completed').first();
            if ($incomplete.length) {
                $incomplete.addClass('active');
            } else {
                $('.checkout-step[data-step="payment"]').addClass('active');
            }
        }
    };
    
    $(document).ready(function() {
        CheckoutSteps.init();
    });
    
})(jQuery);