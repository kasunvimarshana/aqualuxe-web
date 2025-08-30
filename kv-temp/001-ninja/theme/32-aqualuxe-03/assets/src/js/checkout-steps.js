/**
 * AquaLuxe Theme - Checkout Steps
 *
 * Handles the multi-step checkout process.
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Check if we're on the checkout page
        if (!$('.woocommerce-checkout').length) {
            return;
        }

        // Define checkout steps
        const steps = [
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

        // Create steps UI
        const createStepsUI = () => {
            const stepsHtml = `
                <div class="checkout-steps">
                    <ul class="steps-list">
                        ${steps.map((step, index) => `
                            <li class="step-item ${index === 0 ? 'active' : ''}" data-step="${step.id}">
                                <span class="step-number">${index + 1}</span>
                                <span class="step-title">${step.title}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
            `;

            $('.woocommerce-checkout').prepend(stepsHtml);
        };

        // Initialize checkout steps
        const initCheckoutSteps = () => {
            createStepsUI();

            // Hide all steps except the first one
            steps.forEach((step, index) => {
                if (index > 0) {
                    $(step.selector).hide();
                }
            });

            // Add navigation buttons
            steps.forEach((step, index) => {
                const isFirst = index === 0;
                const isLast = index === steps.length - 1;
                
                const buttonsHtml = `
                    <div class="step-buttons" data-step="${step.id}">
                        ${!isFirst ? '<button type="button" class="button prev-step">Previous</button>' : ''}
                        ${!isLast ? '<button type="button" class="button next-step">Next</button>' : ''}
                    </div>
                `;
                
                $(step.selector).last().after(buttonsHtml);
            });

            // Hide the place order button initially
            $('#place_order').hide();

            // Show the place order button on the last step
            $('.step-buttons[data-step="review"]').append($('#place_order').show());

            // Handle next step button click
            $('.next-step').on('click', function() {
                const currentStep = $(this).closest('.step-buttons').data('step');
                const currentIndex = steps.findIndex(step => step.id === currentStep);
                const nextStep = steps[currentIndex + 1];
                
                // Validate current step
                if (validateStep(currentStep)) {
                    // Hide current step
                    $(steps[currentIndex].selector).hide();
                    $('.step-buttons[data-step="' + currentStep + '"]').hide();
                    
                    // Show next step
                    $(nextStep.selector).show();
                    $('.step-buttons[data-step="' + nextStep.id + '"]').show();
                    
                    // Update active step in UI
                    $('.step-item').removeClass('active');
                    $('.step-item[data-step="' + nextStep.id + '"]').addClass('active');
                    
                    // Scroll to top of checkout
                    $('html, body').animate({
                        scrollTop: $('.woocommerce-checkout').offset().top - 50
                    }, 500);
                }
            });

            // Handle previous step button click
            $('.prev-step').on('click', function() {
                const currentStep = $(this).closest('.step-buttons').data('step');
                const currentIndex = steps.findIndex(step => step.id === currentStep);
                const prevStep = steps[currentIndex - 1];
                
                // Hide current step
                $(steps[currentIndex].selector).hide();
                $('.step-buttons[data-step="' + currentStep + '"]').hide();
                
                // Show previous step
                $(prevStep.selector).show();
                $('.step-buttons[data-step="' + prevStep.id + '"]').show();
                
                // Update active step in UI
                $('.step-item').removeClass('active');
                $('.step-item[data-step="' + prevStep.id + '"]').addClass('active');
                
                // Scroll to top of checkout
                $('html, body').animate({
                    scrollTop: $('.woocommerce-checkout').offset().top - 50
                }, 500);
            });

            // Handle step navigation from the steps UI
            $('.step-item').on('click', function() {
                const clickedStep = $(this).data('step');
                const clickedIndex = steps.findIndex(step => step.id === clickedStep);
                const currentActive = $('.step-item.active').data('step');
                const currentIndex = steps.findIndex(step => step.id === currentActive);
                
                // Only allow going back to previous steps
                if (clickedIndex < currentIndex) {
                    // Hide current step
                    $(steps[currentIndex].selector).hide();
                    $('.step-buttons[data-step="' + currentActive + '"]').hide();
                    
                    // Show clicked step
                    $(steps[clickedIndex].selector).show();
                    $('.step-buttons[data-step="' + clickedStep + '"]').show();
                    
                    // Update active step in UI
                    $('.step-item').removeClass('active');
                    $(this).addClass('active');
                }
            });
        };

        // Validate step
        const validateStep = (stepId) => {
            let isValid = true;
            
            // Customer information validation
            if (stepId === 'customer') {
                const requiredFields = $('.woocommerce-billing-fields .validate-required input, .woocommerce-billing-fields .validate-required select');
                
                requiredFields.each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        $(this).addClass('error');
                    } else {
                        $(this).removeClass('error');
                    }
                });
                
                if (!isValid) {
                    alert('Please fill in all required fields.');
                }
            }
            
            // Shipping method validation
            if (stepId === 'shipping') {
                if ($('.woocommerce-shipping-methods input:checked').length === 0) {
                    isValid = false;
                    alert('Please select a shipping method.');
                }
            }
            
            // Payment method validation
            if (stepId === 'payment') {
                if ($('#payment input:checked').length === 0) {
                    isValid = false;
                    alert('Please select a payment method.');
                }
            }
            
            return isValid;
        };

        // Initialize
        initCheckoutSteps();
    });

})(jQuery);