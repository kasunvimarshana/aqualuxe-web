/**
 * Subscriptions Module JavaScript
 *
 * Handles subscription and membership functionality on the frontend
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Subscriptions Module
     */
    const AquaLuxeSubscriptions = {
        
        /**
         * Initialize the module
         */
        init() {
            this.bindEvents();
            this.initializeComponents();
        },

        /**
         * Bind event handlers
         */
        bindEvents() {
            // Subscription form submission
            $(document).on('submit', '#aqualuxe-subscription-form', this.handleSubscriptionForm.bind(this));
            
            // Membership plan selection
            $(document).on('click', '.subscription-btn', this.handlePlanSelection.bind(this));
            
            // Subscription management
            $(document).on('click', '.cancel-subscription-btn', this.handleCancellation.bind(this));
            
            // Plan comparison toggle
            $(document).on('click', '.compare-plans-btn', this.togglePlanComparison.bind(this));
            
            // Billing cycle change
            $(document).on('change', '.billing-cycle-selector', this.updatePlanPricing.bind(this));
        },

        /**
         * Initialize components
         */
        initializeComponents() {
            this.initializePlanCards();
            this.initializeSubscriptionForm();
            this.setupValidation();
        },

        /**
         * Initialize plan cards
         */
        initializePlanCards() {
            $('.membership-plan').each(function() {
                const $card = $(this);
                const plan = $card.data('plan');
                
                // Add hover effects
                $card.hover(
                    function() {
                        $(this).addClass('plan-hover');
                    },
                    function() {
                        $(this).removeClass('plan-hover');
                    }
                );

                // Highlight recommended plan
                if ($card.hasClass('recommended')) {
                    $card.addClass('plan-featured');
                }
            });
        },

        /**
         * Initialize subscription form
         */
        initializeSubscriptionForm() {
            const $form = $('#aqualuxe-subscription-form');
            
            if ($form.length) {
                // Pre-fill form if plan is specified
                const urlParams = new URLSearchParams(window.location.search);
                const selectedPlan = urlParams.get('plan');
                
                if (selectedPlan) {
                    $form.find('select[name="plan"]').val(selectedPlan);
                    this.updateFormForPlan(selectedPlan);
                }
            }
        },

        /**
         * Setup form validation
         */
        setupValidation() {
            // Email validation
            $(document).on('blur', 'input[type="email"]', function() {
                const email = $(this).val();
                const isValid = AquaLuxeSubscriptions.validateEmail(email);
                
                $(this).toggleClass('invalid', !isValid);
                
                if (!isValid && email) {
                    AquaLuxeSubscriptions.showFieldError($(this), aqualuxe_subscriptions.messages.invalid_email || 'Please enter a valid email address.');
                } else {
                    AquaLuxeSubscriptions.hideFieldError($(this));
                }
            });
        },

        /**
         * Handle subscription form submission
         */
        handleSubscriptionForm(e) {
            e.preventDefault();
            
            const $form = $(e.target);
            const $submitBtn = $form.find('button[type="submit"]');
            
            // Validate form
            if (!this.validateSubscriptionForm($form)) {
                return;
            }

            // Get form data
            const formData = new FormData($form[0]);
            formData.append('action', 'aqualuxe_subscribe');
            formData.append('nonce', aqualuxe_subscriptions.nonce);

            // Show loading state
            this.setButtonLoading($submitBtn, true);

            // Submit form
            $.ajax({
                url: aqualuxe_subscriptions.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        this.handleSubscriptionSuccess(response.data, $form);
                    } else {
                        this.handleSubscriptionError(response.data, $form);
                    }
                },
                error: () => {
                    this.handleSubscriptionError({
                        message: aqualuxe_subscriptions.messages.error
                    }, $form);
                },
                complete: () => {
                    this.setButtonLoading($submitBtn, false);
                }
            });
        },

        /**
         * Handle plan selection from buttons
         */
        handlePlanSelection(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            const plan = $btn.data('plan');
            
            if (!plan) return;

            // Update active state
            $('.subscription-btn').removeClass('active');
            $btn.addClass('active');

            // Show subscription form or redirect
            const $form = $('#aqualuxe-subscription-form');
            
            if ($form.length) {
                $form.find('input[name="plan"]').val(plan);
                this.updateFormForPlan(plan);
                
                // Scroll to form
                $('html, body').animate({
                    scrollTop: $form.offset().top - 100
                }, 500);
            } else {
                // Redirect to subscription page with plan parameter
                window.location.href = `${window.location.origin}/subscriptions/?plan=${plan}`;
            }
        },

        /**
         * Update form for selected plan
         */
        updateFormForPlan(plan) {
            const planData = aqualuxe_subscriptions.membership_tiers[plan];
            
            if (!planData) return;

            const $form = $('#aqualuxe-subscription-form');
            
            // Update plan display
            const $planDisplay = $form.find('.selected-plan-display');
            if ($planDisplay.length) {
                $planDisplay.html(`
                    <div class="selected-plan">
                        <h4>${planData.name}</h4>
                        <div class="plan-price">$${planData.price}/month</div>
                        <ul class="plan-features">
                            ${planData.features.map(feature => `<li>${this.formatFeatureName(feature)}</li>`).join('')}
                        </ul>
                    </div>
                `);
            }
        },

        /**
         * Handle subscription cancellation
         */
        handleCancellation(e) {
            e.preventDefault();
            
            if (!confirm(aqualuxe_subscriptions.messages.confirm_cancellation || 'Are you sure you want to cancel your subscription?')) {
                return;
            }

            const $btn = $(e.target);
            const subscriptionId = $btn.data('subscription-id');

            this.setButtonLoading($btn, true);

            $.ajax({
                url: aqualuxe_subscriptions.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_cancel_subscription',
                    subscription_id: subscriptionId,
                    nonce: aqualuxe_subscriptions.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showMessage(response.data.message, 'success');
                        
                        // Update UI
                        $btn.closest('.subscription-item').addClass('cancelled');
                        $btn.text(aqualuxe_subscriptions.messages.cancelled).prop('disabled', true);
                        
                    } else {
                        this.showMessage(response.data.message || aqualuxe_subscriptions.messages.error, 'error');
                    }
                },
                error: () => {
                    this.showMessage(aqualuxe_subscriptions.messages.error, 'error');
                },
                complete: () => {
                    this.setButtonLoading($btn, false);
                }
            });
        },

        /**
         * Toggle plan comparison
         */
        togglePlanComparison(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            const $comparisonTable = $('.plan-comparison-table');
            
            if ($comparisonTable.is(':visible')) {
                $comparisonTable.slideUp();
                $btn.text('Compare Plans');
            } else {
                $comparisonTable.slideDown();
                $btn.text('Hide Comparison');
            }
        },

        /**
         * Update plan pricing based on billing cycle
         */
        updatePlanPricing(e) {
            const $selector = $(e.target);
            const cycle = $selector.val();
            
            $('.membership-plan').each(function() {
                const $plan = $(this);
                const basePriceMonthly = parseFloat($plan.data('monthly-price'));
                
                let displayPrice = basePriceMonthly;
                let suffix = '/month';
                
                switch (cycle) {
                    case 'quarterly':
                        displayPrice = basePriceMonthly * 3 * 0.95; // 5% discount
                        suffix = '/quarter';
                        break;
                    case 'yearly':
                        displayPrice = basePriceMonthly * 12 * 0.85; // 15% discount
                        suffix = '/year';
                        break;
                }
                
                $plan.find('.price').html(`$${displayPrice.toFixed(2)}<span class="period">${suffix}</span>`);
            });
        },

        /**
         * Validate subscription form
         */
        validateSubscriptionForm($form) {
            let isValid = true;
            
            // Check required fields
            $form.find('[required]').each(function() {
                const $field = $(this);
                const value = $field.val().trim();
                
                if (!value) {
                    AquaLuxeSubscriptions.showFieldError($field, 'This field is required.');
                    isValid = false;
                } else {
                    AquaLuxeSubscriptions.hideFieldError($field);
                }
            });
            
            // Validate email
            const $emailField = $form.find('input[type="email"]');
            if ($emailField.length) {
                const email = $emailField.val();
                if (email && !this.validateEmail(email)) {
                    this.showFieldError($emailField, 'Please enter a valid email address.');
                    isValid = false;
                }
            }
            
            return isValid;
        },

        /**
         * Validate email address
         */
        validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        /**
         * Handle subscription success
         */
        handleSubscriptionSuccess(data, $form) {
            this.showMessage(data.message, 'success');
            
            // Reset form
            $form[0].reset();
            
            // Redirect to success page or show success content
            if (data.redirect_url) {
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 2000);
            } else {
                // Show success content
                $form.html(`
                    <div class="subscription-success">
                        <div class="success-icon">✓</div>
                        <h3>Subscription Successful!</h3>
                        <p>${data.message}</p>
                        ${data.subscription_id ? `<p class="subscription-id">Subscription ID: ${data.subscription_id}</p>` : ''}
                    </div>
                `);
            }
        },

        /**
         * Handle subscription error
         */
        handleSubscriptionError(data, $form) {
            this.showMessage(data.message || aqualuxe_subscriptions.messages.error, 'error');
        },

        /**
         * Set button loading state
         */
        setButtonLoading($btn, isLoading) {
            if (isLoading) {
                $btn.prop('disabled', true)
                    .data('original-text', $btn.text())
                    .html('<span class="spinner"></span> ' + aqualuxe_subscriptions.messages.subscribing);
            } else {
                $btn.prop('disabled', false)
                    .text($btn.data('original-text') || $btn.text());
            }
        },

        /**
         * Show field error
         */
        showFieldError($field, message) {
            this.hideFieldError($field);
            
            const $error = $('<div class="field-error">' + message + '</div>');
            $field.addClass('error').after($error);
        },

        /**
         * Hide field error
         */
        hideFieldError($field) {
            $field.removeClass('error').next('.field-error').remove();
        },

        /**
         * Show message
         */
        showMessage(message, type = 'info') {
            const $message = $(`
                <div class="aqualuxe-message aqualuxe-message-${type}">
                    <span class="message-text">${message}</span>
                    <button class="message-close">&times;</button>
                </div>
            `);
            
            // Remove existing messages
            $('.aqualuxe-message').remove();
            
            // Add new message
            $('body').prepend($message);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                $message.fadeOut(() => $message.remove());
            }, 5000);
            
            // Manual close
            $message.find('.message-close').on('click', () => {
                $message.fadeOut(() => $message.remove());
            });
        },

        /**
         * Format feature name
         */
        formatFeatureName(feature) {
            return feature.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }
    };

    /**
     * Initialize when document is ready
     */
    $(document).ready(() => {
        AquaLuxeSubscriptions.init();
    });

    // Make it globally available
    window.AquaLuxeSubscriptions = AquaLuxeSubscriptions;

})(jQuery);