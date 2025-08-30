/**
 * AquaLuxe Subscription Management
 * 
 * Handles functionality for the subscription management system
 */

(function($) {
    'use strict';

    // Main subscription object
    const SubscriptionManager = {
        // Store elements
        elements: {},
        
        // Initialize the subscription manager
        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.initializeUI();
        },
        
        // Cache DOM elements
        cacheElements: function() {
            // Subscription plan selection
            this.elements.planContainer = $('.subscription-plans-container');
            this.elements.planItems = $('.subscription-plan-item');
            this.elements.planRadios = $('.subscription-plan-radio');
            
            // Subscription form
            this.elements.subscriptionForm = $('#subscription-form');
            this.elements.planIdInput = $('#subscription_plan_id');
            this.elements.frequencySelect = $('#subscription_frequency');
            this.elements.startDateInput = $('#subscription_start_date');
            this.elements.paymentMethodSelect = $('#payment_method');
            this.elements.submitButton = $('#submit-subscription');
            
            // Customer dashboard
            this.elements.dashboardContainer = $('.subscription-dashboard-container');
            this.elements.activeSubscriptions = $('.active-subscriptions');
            this.elements.subscriptionDetails = $('.subscription-details');
            this.elements.paymentHistory = $('.payment-history');
            this.elements.cancelButton = $('.cancel-subscription-button');
            this.elements.pauseButton = $('.pause-subscription-button');
            this.elements.resumeButton = $('.resume-subscription-button');
            this.elements.updateButton = $('.update-subscription-button');
            
            // Modals
            this.elements.modalContainer = $('.subscription-modal-container');
            this.elements.modalContent = $('.subscription-modal-content');
            this.elements.modalClose = $('.subscription-modal-close');
            this.elements.confirmButton = $('.confirm-action-button');
            this.elements.cancelButton = $('.cancel-action-button');
            
            // Admin subscription management
            this.elements.adminBulkActions = $('#subscription-bulk-actions');
            this.elements.adminActionButton = $('#subscription-action-button');
            this.elements.adminCheckboxes = $('.subscription-checkbox');
            this.elements.adminFilterForm = $('#subscription-filter-form');
            this.elements.adminDateRangeStart = $('#date-range-start');
            this.elements.adminDateRangeEnd = $('#date-range-end');
            this.elements.adminFilterButton = $('#filter-subscriptions');
            this.elements.adminResetButton = $('#reset-filters');
            this.elements.adminExportButton = $('#export-subscriptions');
        },
        
        // Bind event handlers
        bindEvents: function() {
            const self = this;
            
            // Plan selection
            if (this.elements.planRadios.length) {
                this.elements.planRadios.on('change', function() {
                    self.handlePlanSelection($(this).val());
                });
                
                this.elements.planItems.on('click', function() {
                    const radioButton = $(this).find('input[type="radio"]');
                    radioButton.prop('checked', true).trigger('change');
                });
            }
            
            // Subscription form submission
            if (this.elements.subscriptionForm.length) {
                this.elements.subscriptionForm.on('submit', function(e) {
                    e.preventDefault();
                    self.handleFormSubmission();
                });
                
                this.elements.frequencySelect.on('change', function() {
                    self.updatePriceDisplay();
                });
            }
            
            // Customer dashboard actions
            if (this.elements.dashboardContainer.length) {
                // Cancel subscription
                this.elements.cancelButton.on('click', function(e) {
                    e.preventDefault();
                    const subscriptionId = $(this).data('subscription-id');
                    self.showConfirmationModal('cancel', subscriptionId);
                });
                
                // Pause subscription
                this.elements.pauseButton.on('click', function(e) {
                    e.preventDefault();
                    const subscriptionId = $(this).data('subscription-id');
                    self.showConfirmationModal('pause', subscriptionId);
                });
                
                // Resume subscription
                this.elements.resumeButton.on('click', function(e) {
                    e.preventDefault();
                    const subscriptionId = $(this).data('subscription-id');
                    self.showConfirmationModal('resume', subscriptionId);
                });
                
                // Update subscription
                this.elements.updateButton.on('click', function(e) {
                    e.preventDefault();
                    const subscriptionId = $(this).data('subscription-id');
                    self.showUpdateModal(subscriptionId);
                });
            }
            
            // Modal actions
            if (this.elements.modalContainer.length) {
                // Close modal
                this.elements.modalClose.on('click', function() {
                    self.closeModal();
                });
                
                // Click outside modal to close
                this.elements.modalContainer.on('click', function(e) {
                    if ($(e.target).is(this.elements.modalContainer)) {
                        self.closeModal();
                    }
                }.bind(this));
                
                // Confirm action
                this.elements.confirmButton.on('click', function() {
                    const action = $(this).data('action');
                    const subscriptionId = $(this).data('subscription-id');
                    self.processSubscriptionAction(action, subscriptionId);
                });
                
                // Cancel action
                this.elements.cancelButton.on('click', function() {
                    self.closeModal();
                });
            }
            
            // Admin subscription management
            if (this.elements.adminBulkActions.length) {
                // Bulk action button
                this.elements.adminActionButton.on('click', function(e) {
                    e.preventDefault();
                    self.processBulkAction();
                });
                
                // Select all checkboxes
                $('#select-all-subscriptions').on('change', function() {
                    const isChecked = $(this).prop('checked');
                    self.elements.adminCheckboxes.prop('checked', isChecked);
                });
                
                // Filter form submission
                this.elements.adminFilterForm.on('submit', function(e) {
                    e.preventDefault();
                    self.filterSubscriptions();
                });
                
                // Reset filters
                this.elements.adminResetButton.on('click', function(e) {
                    e.preventDefault();
                    self.resetFilters();
                });
                
                // Export subscriptions
                this.elements.adminExportButton.on('click', function(e) {
                    e.preventDefault();
                    self.exportSubscriptions();
                });
            }
        },
        
        // Initialize UI elements
        initializeUI: function() {
            // Initialize date pickers
            if ($.fn.datepicker && this.elements.startDateInput.length) {
                this.elements.startDateInput.datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: 0
                });
            }
            
            // Initialize admin date range pickers
            if ($.fn.datepicker && this.elements.adminDateRangeStart.length) {
                this.elements.adminDateRangeStart.datepicker({
                    dateFormat: 'yy-mm-dd',
                    onSelect: function(selectedDate) {
                        this.elements.adminDateRangeEnd.datepicker('option', 'minDate', selectedDate);
                    }.bind(this)
                });
                
                this.elements.adminDateRangeEnd.datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            }
            
            // Set initial plan selection if a plan is pre-selected
            const selectedPlan = this.elements.planRadios.filter(':checked');
            if (selectedPlan.length) {
                this.handlePlanSelection(selectedPlan.val());
            }
        },
        
        // Handle plan selection
        handlePlanSelection: function(planId) {
            // Update hidden input
            this.elements.planIdInput.val(planId);
            
            // Update UI
            this.elements.planItems.removeClass('selected');
            this.elements.planItems.filter('[data-plan-id="' + planId + '"]').addClass('selected');
            
            // Update price display
            this.updatePriceDisplay();
            
            // Enable submit button
            this.elements.submitButton.prop('disabled', false);
        },
        
        // Update price display based on selected plan and frequency
        updatePriceDisplay: function() {
            const planId = this.elements.planIdInput.val();
            const frequency = this.elements.frequencySelect.val();
            
            if (!planId || !frequency) {
                return;
            }
            
            const planItem = this.elements.planItems.filter('[data-plan-id="' + planId + '"]');
            const basePriceEl = planItem.find('.plan-price');
            const basePrice = parseFloat(basePriceEl.data('price'));
            
            // Get frequency multiplier
            let multiplier = 1;
            switch (frequency) {
                case 'weekly':
                    multiplier = 1;
                    break;
                case 'biweekly':
                    multiplier = 2;
                    break;
                case 'monthly':
                    multiplier = 4.33; // Average weeks in a month
                    break;
                case 'quarterly':
                    multiplier = 13; // 3 months
                    break;
                case 'biannually':
                    multiplier = 26; // 6 months
                    break;
                case 'annually':
                    multiplier = 52; // 12 months
                    break;
            }
            
            // Calculate price
            const price = basePrice * multiplier;
            
            // Update price display
            const priceDisplay = planItem.find('.plan-price-display');
            priceDisplay.text('$' + price.toFixed(2));
            
            // Update frequency display
            const frequencyDisplay = planItem.find('.plan-frequency');
            frequencyDisplay.text('/' + frequency);
        },
        
        // Handle subscription form submission
        handleFormSubmission: function() {
            // Validate form
            if (!this.validateSubscriptionForm()) {
                return;
            }
            
            // Show loading state
            this.elements.submitButton.prop('disabled', true).text('Processing...');
            
            // Get form data
            const formData = new FormData(this.elements.subscriptionForm[0]);
            formData.append('action', 'aqualuxe_process_subscription');
            formData.append('security', aqualuxe_subscription.nonce);
            
            // Send AJAX request
            $.ajax({
                url: aqualuxe_subscription.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Redirect to thank you page or dashboard
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        } else {
                            // Show success message
                            this.showMessage('success', response.data.message || 'Subscription created successfully!');
                            
                            // Reset form
                            this.elements.subscriptionForm[0].reset();
                            this.elements.planItems.removeClass('selected');
                            this.elements.submitButton.prop('disabled', false).text('Subscribe');
                        }
                    } else {
                        // Show error message
                        this.showMessage('error', response.data.message || 'An error occurred. Please try again.');
                        this.elements.submitButton.prop('disabled', false).text('Subscribe');
                    }
                }.bind(this),
                error: function() {
                    // Show error message
                    this.showMessage('error', 'Connection error. Please try again.');
                    this.elements.submitButton.prop('disabled', false).text('Subscribe');
                }.bind(this)
            });
        },
        
        // Validate subscription form
        validateSubscriptionForm: function() {
            let isValid = true;
            
            // Check if plan is selected
            const planId = this.elements.planIdInput.val();
            if (!planId) {
                this.showMessage('error', 'Please select a subscription plan.');
                isValid = false;
            }
            
            // Check if frequency is selected
            const frequency = this.elements.frequencySelect.val();
            if (!frequency) {
                this.showMessage('error', 'Please select a subscription frequency.');
                isValid = false;
            }
            
            // Check if payment method is selected
            const paymentMethod = this.elements.paymentMethodSelect.val();
            if (!paymentMethod) {
                this.showMessage('error', 'Please select a payment method.');
                isValid = false;
            }
            
            return isValid;
        },
        
        // Show confirmation modal
        showConfirmationModal: function(action, subscriptionId) {
            let title, message, confirmText, confirmClass;
            
            switch (action) {
                case 'cancel':
                    title = 'Cancel Subscription';
                    message = 'Are you sure you want to cancel this subscription? This action cannot be undone.';
                    confirmText = 'Cancel Subscription';
                    confirmClass = 'danger';
                    break;
                case 'pause':
                    title = 'Pause Subscription';
                    message = 'Are you sure you want to pause this subscription? You can resume it at any time.';
                    confirmText = 'Pause Subscription';
                    confirmClass = 'warning';
                    break;
                case 'resume':
                    title = 'Resume Subscription';
                    message = 'Are you sure you want to resume this subscription? You will be billed according to your subscription schedule.';
                    confirmText = 'Resume Subscription';
                    confirmClass = 'success';
                    break;
                default:
                    return;
            }
            
            // Set modal content
            this.elements.modalContent.find('.subscription-modal-title').text(title);
            this.elements.modalContent.find('.subscription-modal-message').text(message);
            this.elements.confirmButton.text(confirmText).data('action', action).data('subscription-id', subscriptionId).removeClass().addClass('button button-' + confirmClass);
            
            // Show modal
            this.elements.modalContainer.fadeIn(300);
        },
        
        // Show update modal
        showUpdateModal: function(subscriptionId) {
            // Get subscription details
            $.ajax({
                url: aqualuxe_subscription.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_subscription_details',
                    security: aqualuxe_subscription.nonce,
                    subscription_id: subscriptionId
                },
                success: function(response) {
                    if (response.success) {
                        // Build update form
                        let formHtml = '<form id="update-subscription-form">';
                        formHtml += '<input type="hidden" name="subscription_id" value="' + subscriptionId + '">';
                        
                        // Frequency select
                        formHtml += '<div class="form-group">';
                        formHtml += '<label for="update_frequency">Billing Frequency</label>';
                        formHtml += '<select id="update_frequency" name="frequency" class="form-control">';
                        
                        const frequencies = response.data.frequencies;
                        const currentFrequency = response.data.current_frequency;
                        
                        for (const key in frequencies) {
                            if (frequencies.hasOwnProperty(key)) {
                                const selected = key === currentFrequency ? ' selected' : '';
                                formHtml += '<option value="' + key + '"' + selected + '>' + frequencies[key] + '</option>';
                            }
                        }
                        
                        formHtml += '</select>';
                        formHtml += '</div>';
                        
                        // Payment method select
                        formHtml += '<div class="form-group">';
                        formHtml += '<label for="update_payment_method">Payment Method</label>';
                        formHtml += '<select id="update_payment_method" name="payment_method" class="form-control">';
                        
                        const paymentMethods = response.data.payment_methods;
                        const currentPaymentMethod = response.data.current_payment_method;
                        
                        for (const key in paymentMethods) {
                            if (paymentMethods.hasOwnProperty(key)) {
                                const selected = key === currentPaymentMethod ? ' selected' : '';
                                formHtml += '<option value="' + key + '"' + selected + '>' + paymentMethods[key] + '</option>';
                            }
                        }
                        
                        formHtml += '</select>';
                        formHtml += '</div>';
                        
                        // Next billing date
                        formHtml += '<div class="form-group">';
                        formHtml += '<label for="update_next_billing_date">Next Billing Date</label>';
                        formHtml += '<input type="text" id="update_next_billing_date" name="next_billing_date" class="form-control datepicker" value="' + response.data.next_billing_date + '">';
                        formHtml += '</div>';
                        
                        formHtml += '<div class="form-actions">';
                        formHtml += '<button type="submit" class="button button-primary">Update Subscription</button>';
                        formHtml += '</div>';
                        
                        formHtml += '</form>';
                        
                        // Set modal content
                        this.elements.modalContent.find('.subscription-modal-title').text('Update Subscription');
                        this.elements.modalContent.find('.subscription-modal-message').html(formHtml);
                        
                        // Hide default buttons
                        this.elements.confirmButton.hide();
                        this.elements.cancelButton.hide();
                        
                        // Initialize datepicker
                        if ($.fn.datepicker) {
                            $('.datepicker').datepicker({
                                dateFormat: 'yy-mm-dd',
                                minDate: 0
                            });
                        }
                        
                        // Bind form submission
                        $('#update-subscription-form').on('submit', function(e) {
                            e.preventDefault();
                            this.updateSubscription($(e.target));
                        }.bind(this));
                        
                        // Show modal
                        this.elements.modalContainer.fadeIn(300);
                    } else {
                        // Show error message
                        this.showMessage('error', response.data.message || 'Failed to load subscription details.');
                    }
                }.bind(this),
                error: function() {
                    // Show error message
                    this.showMessage('error', 'Connection error. Please try again.');
                }.bind(this)
            });
        },
        
        // Close modal
        closeModal: function() {
            this.elements.modalContainer.fadeOut(300);
            
            // Reset modal content after fade out
            setTimeout(function() {
                this.elements.confirmButton.show();
                this.elements.cancelButton.show();
            }.bind(this), 300);
        },
        
        // Process subscription action (cancel, pause, resume)
        processSubscriptionAction: function(action, subscriptionId) {
            // Show loading state
            this.elements.confirmButton.prop('disabled', true).text('Processing...');
            
            // Send AJAX request
            $.ajax({
                url: aqualuxe_subscription.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_process_subscription_action',
                    security: aqualuxe_subscription.nonce,
                    subscription_action: action,
                    subscription_id: subscriptionId
                },
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        this.closeModal();
                        
                        // Show success message
                        this.showMessage('success', response.data.message);
                        
                        // Reload page after a short delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        this.showMessage('error', response.data.message || 'An error occurred. Please try again.');
                        this.elements.confirmButton.prop('disabled', false).text('Confirm');
                    }
                }.bind(this),
                error: function() {
                    // Show error message
                    this.showMessage('error', 'Connection error. Please try again.');
                    this.elements.confirmButton.prop('disabled', false).text('Confirm');
                }.bind(this)
            });
        },
        
        // Update subscription
        updateSubscription: function($form) {
            // Show loading state
            const $submitButton = $form.find('button[type="submit"]');
            $submitButton.prop('disabled', true).text('Processing...');
            
            // Get form data
            const formData = new FormData($form[0]);
            formData.append('action', 'aqualuxe_update_subscription');
            formData.append('security', aqualuxe_subscription.nonce);
            
            // Send AJAX request
            $.ajax({
                url: aqualuxe_subscription.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        this.closeModal();
                        
                        // Show success message
                        this.showMessage('success', response.data.message);
                        
                        // Reload page after a short delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        this.showMessage('error', response.data.message || 'An error occurred. Please try again.');
                        $submitButton.prop('disabled', false).text('Update Subscription');
                    }
                }.bind(this),
                error: function() {
                    // Show error message
                    this.showMessage('error', 'Connection error. Please try again.');
                    $submitButton.prop('disabled', false).text('Update Subscription');
                }.bind(this)
            });
        },
        
        // Process bulk action for admin
        processBulkAction: function() {
            // Get selected action
            const action = this.elements.adminBulkActions.val();
            
            if (!action) {
                this.showMessage('error', 'Please select an action.');
                return;
            }
            
            // Get selected subscriptions
            const selectedSubscriptions = [];
            this.elements.adminCheckboxes.filter(':checked').each(function() {
                selectedSubscriptions.push($(this).val());
            });
            
            if (selectedSubscriptions.length === 0) {
                this.showMessage('error', 'Please select at least one subscription.');
                return;
            }
            
            // Confirm action
            if (!confirm('Are you sure you want to ' + action + ' the selected subscriptions?')) {
                return;
            }
            
            // Show loading state
            this.elements.adminActionButton.prop('disabled', true).text('Processing...');
            
            // Send AJAX request
            $.ajax({
                url: aqualuxe_subscription.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_process_bulk_action',
                    security: aqualuxe_subscription.nonce,
                    bulk_action: action,
                    subscription_ids: selectedSubscriptions
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        this.showMessage('success', response.data.message);
                        
                        // Reload page after a short delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        this.showMessage('error', response.data.message || 'An error occurred. Please try again.');
                        this.elements.adminActionButton.prop('disabled', false).text('Apply');
                    }
                }.bind(this),
                error: function() {
                    // Show error message
                    this.showMessage('error', 'Connection error. Please try again.');
                    this.elements.adminActionButton.prop('disabled', false).text('Apply');
                }.bind(this)
            });
        },
        
        // Filter subscriptions
        filterSubscriptions: function() {
            // Submit the form
            this.elements.adminFilterForm.submit();
        },
        
        // Reset filters
        resetFilters: function() {
            // Reset form fields
            this.elements.adminFilterForm[0].reset();
            
            // Submit the form
            this.elements.adminFilterForm.submit();
        },
        
        // Export subscriptions
        exportSubscriptions: function() {
            // Get filter values
            const formData = new FormData(this.elements.adminFilterForm[0]);
            formData.append('action', 'aqualuxe_export_subscriptions');
            formData.append('security', aqualuxe_subscription.nonce);
            
            // Show loading state
            this.elements.adminExportButton.prop('disabled', true).text('Exporting...');
            
            // Send AJAX request
            $.ajax({
                url: aqualuxe_subscription.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Create download link
                        const link = document.createElement('a');
                        link.href = response.data.file_url;
                        link.download = response.data.filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        // Reset button
                        this.elements.adminExportButton.prop('disabled', false).text('Export');
                    } else {
                        // Show error message
                        this.showMessage('error', response.data.message || 'An error occurred. Please try again.');
                        this.elements.adminExportButton.prop('disabled', false).text('Export');
                    }
                }.bind(this),
                error: function() {
                    // Show error message
                    this.showMessage('error', 'Connection error. Please try again.');
                    this.elements.adminExportButton.prop('disabled', false).text('Export');
                }.bind(this)
            });
        },
        
        // Show message
        showMessage: function(type, message) {
            // Create message element if it doesn't exist
            if ($('.subscription-message').length === 0) {
                $('body').append('<div class="subscription-message"></div>');
            }
            
            // Set message content and class
            const $message = $('.subscription-message');
            $message.text(message).removeClass().addClass('subscription-message ' + type).fadeIn(300);
            
            // Hide message after a delay
            clearTimeout(this.messageTimeout);
            this.messageTimeout = setTimeout(function() {
                $message.fadeOut(300);
            }, 5000);
        }
    };
    
    // Initialize subscription manager when document is ready
    $(document).ready(function() {
        SubscriptionManager.init();
    });

})(jQuery);