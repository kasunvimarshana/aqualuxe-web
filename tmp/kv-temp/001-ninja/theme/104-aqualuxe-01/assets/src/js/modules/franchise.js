/**
 * Franchise Module JavaScript
 * 
 * Handles franchise inquiries and partner portal functionality
 */

(function($) {
    'use strict';

    const FranchiseModule = {
        
        /**
         * Initialize module
         */
        init() {
            this.bindEvents();
            this.initFormValidation();
            this.calculateInvestment();
        },

        /**
         * Bind events
         */
        bindEvents() {
            // Franchise inquiry form
            $(document).on('submit', '#franchise-inquiry-form', this.handleInquirySubmission.bind(this));
            
            // Partner profile form
            $(document).on('submit', '#partner-profile-form', this.handleProfileUpdate.bind(this));
            
            // Investment calculator
            $(document).on('input', '.investment-calculator input', this.calculateInvestment.bind(this));
            
            // Territory search
            $(document).on('input', '#territory-search', this.searchTerritories.bind(this));
            
            // Form step navigation
            $(document).on('click', '.step-nav-btn', this.handleStepNavigation.bind(this));
        },

        /**
         * Handle inquiry form submission
         */
        handleInquirySubmission(e) {
            e.preventDefault();
            
            const $form = $(e.currentTarget);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalText = $submitBtn.text();
            
            if (!this.validateForm($form)) {
                return;
            }
            
            const formData = new FormData($form[0]);
            formData.append('action', 'submit_franchise_inquiry');
            formData.append('nonce', aqualuxeFranchise.nonce);

            $submitBtn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: aqualuxeFranchise.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        this.showSuccess(response.data.message);
                        this.showInquirySuccess($form, response.data);
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: () => {
                    this.showError(aqualuxeFranchise.messages.inquiryError);
                },
                complete: () => {
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        },

        /**
         * Handle partner profile update
         */
        handleProfileUpdate(e) {
            e.preventDefault();
            
            const $form = $(e.currentTarget);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalText = $submitBtn.text();
            
            const formData = new FormData($form[0]);
            formData.append('action', 'update_partner_profile');
            formData.append('nonce', aqualuxeFranchise.nonce);

            $submitBtn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: aqualuxeFranchise.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        this.showSuccess(response.data.message);
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: () => {
                    this.showError(aqualuxeFranchise.messages.profileError);
                },
                complete: () => {
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        },

        /**
         * Initialize form validation
         */
        initFormValidation() {
            // Real-time validation
            $('.franchise-form input, .franchise-form textarea, .franchise-form select').on('blur', (e) => {
                this.validateField($(e.currentTarget));
            });

            // Investment budget validation
            $('#investment_budget').on('input', (e) => {
                this.validateInvestmentBudget($(e.currentTarget));
            });

            // Territory size validation
            $('#territory_size').on('input', (e) => {
                this.validateTerritorySize($(e.currentTarget));
            });
        },

        /**
         * Validate entire form
         */
        validateForm($form) {
            let isValid = true;
            
            $form.find('input[required], textarea[required], select[required]').each((index, element) => {
                if (!this.validateField($(element))) {
                    isValid = false;
                }
            });

            return isValid;
        },

        /**
         * Validate individual field
         */
        validateField($field) {
            const value = $field.val().trim();
            const fieldType = $field.attr('type') || 'text';
            const isRequired = $field.prop('required');
            
            // Clear previous errors
            this.clearFieldError($field);

            // Required field validation
            if (isRequired && !value) {
                this.showFieldError($field, 'This field is required');
                return false;
            }

            // Email validation
            if (fieldType === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    this.showFieldError($field, 'Please enter a valid email address');
                    return false;
                }
            }

            // Phone validation
            if (fieldType === 'tel' && value) {
                const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
                if (!phoneRegex.test(value.replace(/\D/g, ''))) {
                    this.showFieldError($field, 'Please enter a valid phone number');
                    return false;
                }
            }

            return true;
        },

        /**
         * Validate investment budget
         */
        validateInvestmentBudget($field) {
            const value = parseFloat($field.val());
            const minRequired = aqualuxeFranchise.config.franchiseFee;
            
            this.clearFieldError($field);

            if (value && value < minRequired) {
                this.showFieldError($field, `Minimum investment required: $${minRequired.toLocaleString()}`);
                return false;
            }

            return true;
        },

        /**
         * Validate territory size
         */
        validateTerritorySize($field) {
            const value = parseInt($field.val());
            const minRequired = 10000; // Minimum territory population
            
            this.clearFieldError($field);

            if (value && value < minRequired) {
                this.showFieldError($field, `Minimum territory population: ${minRequired.toLocaleString()}`);
                return false;
            }

            return true;
        },

        /**
         * Calculate investment breakdown
         */
        calculateInvestment() {
            const $calculator = $('.investment-calculator');
            if ($calculator.length === 0) return;

            const franchiseFee = aqualuxeFranchise.config.franchiseFee;
            const equipmentCost = parseInt($('#equipment_cost').val()) || 0;
            const inventoryCost = parseInt($('#inventory_cost').val()) || 0;
            const marketingCost = parseInt($('#marketing_cost').val()) || 0;
            const workingCapital = parseInt($('#working_capital').val()) || 0;

            const totalInvestment = franchiseFee + equipmentCost + inventoryCost + marketingCost + workingCapital;
            const monthlyRoyalty = (totalInvestment * 0.01 * aqualuxeFranchise.config.royaltyPercentage) / 12;

            // Update display
            $('.franchise-fee-display').text(`$${franchiseFee.toLocaleString()}`);
            $('.total-investment-display').text(`$${totalInvestment.toLocaleString()}`);
            $('.monthly-royalty-display').text(`$${monthlyRoyalty.toLocaleString()}`);

            // Update breakdown chart if available
            this.updateInvestmentChart({
                franchiseFee,
                equipmentCost,
                inventoryCost,
                marketingCost,
                workingCapital
            });
        },

        /**
         * Update investment breakdown chart
         */
        updateInvestmentChart(data) {
            const $chart = $('#investment-chart');
            if ($chart.length === 0) return;

            // Simple bar chart representation
            const total = Object.values(data).reduce((sum, value) => sum + value, 0);
            
            Object.entries(data).forEach(([key, value]) => {
                const percentage = total > 0 ? (value / total) * 100 : 0;
                const $bar = $chart.find(`[data-category="${key}"]`);
                
                if ($bar.length > 0) {
                    $bar.css('width', `${percentage}%`);
                    $bar.find('.percentage').text(`${percentage.toFixed(1)}%`);
                    $bar.find('.amount').text(`$${value.toLocaleString()}`);
                }
            });
        },

        /**
         * Search territories
         */
        searchTerritories(e) {
            const query = $(e.currentTarget).val().toLowerCase();
            const $results = $('#territory-results');
            
            if (query.length < 3) {
                $results.hide();
                return;
            }

            // Mock territory data - in real implementation, this would be an AJAX call
            const mockTerritories = [
                { name: 'New York Metro', population: 8500000, status: 'available' },
                { name: 'Los Angeles Area', population: 4000000, status: 'taken' },
                { name: 'Chicago Suburbs', population: 2700000, status: 'available' },
                { name: 'Miami-Dade', population: 2700000, status: 'pending' }
            ];

            const filteredTerritories = mockTerritories.filter(territory => 
                territory.name.toLowerCase().includes(query)
            );

            if (filteredTerritories.length > 0) {
                let html = '<ul class="territory-list">';
                filteredTerritories.forEach(territory => {
                    html += `
                        <li class="territory-item ${territory.status}">
                            <span class="territory-name">${territory.name}</span>
                            <span class="territory-population">${territory.population.toLocaleString()} people</span>
                            <span class="territory-status status-${territory.status}">${territory.status}</span>
                        </li>
                    `;
                });
                html += '</ul>';
                
                $results.html(html).show();
            } else {
                $results.html('<p>No territories found</p>').show();
            }
        },

        /**
         * Handle step navigation in multi-step forms
         */
        handleStepNavigation(e) {
            e.preventDefault();
            
            const $btn = $(e.currentTarget);
            const action = $btn.data('action');
            const $form = $btn.closest('form');
            const $currentStep = $form.find('.form-step.active');
            const currentStepIndex = $currentStep.index();
            
            if (action === 'next') {
                // Validate current step before proceeding
                const $stepFields = $currentStep.find('input[required], textarea[required], select[required]');
                let stepValid = true;
                
                $stepFields.each((index, element) => {
                    if (!this.validateField($(element))) {
                        stepValid = false;
                    }
                });
                
                if (!stepValid) {
                    this.showError('Please complete all required fields before proceeding');
                    return;
                }
                
                this.showStep($form, currentStepIndex + 1);
            } else if (action === 'prev') {
                this.showStep($form, currentStepIndex - 1);
            }
        },

        /**
         * Show specific step in multi-step form
         */
        showStep($form, stepIndex) {
            const $steps = $form.find('.form-step');
            const $stepIndicators = $form.find('.step-indicator');
            
            // Hide all steps
            $steps.removeClass('active');
            $stepIndicators.removeClass('active completed');
            
            // Show target step
            $steps.eq(stepIndex).addClass('active');
            
            // Update step indicators
            $stepIndicators.each((index, element) => {
                if (index < stepIndex) {
                    $(element).addClass('completed');
                } else if (index === stepIndex) {
                    $(element).addClass('active');
                }
            });
            
            // Update navigation buttons
            const $prevBtn = $form.find('.step-nav-btn[data-action="prev"]');
            const $nextBtn = $form.find('.step-nav-btn[data-action="next"]');
            const $submitBtn = $form.find('button[type="submit"]');
            
            $prevBtn.toggle(stepIndex > 0);
            $nextBtn.toggle(stepIndex < $steps.length - 1);
            $submitBtn.toggle(stepIndex === $steps.length - 1);
        },

        /**
         * Show inquiry success state
         */
        showInquirySuccess($form, data) {
            const $successMessage = $(`
                <div class="inquiry-success">
                    <div class="success-icon">✓</div>
                    <h3>Thank You for Your Interest!</h3>
                    <p>Your franchise inquiry has been submitted successfully.</p>
                    <div class="next-steps">
                        <h4>What happens next?</h4>
                        <ul>
                            <li>Our franchise team will review your application</li>
                            <li>You'll receive a call within 48 hours</li>
                            <li>We'll schedule a discovery meeting</li>
                            <li>Receive our Franchise Disclosure Document</li>
                        </ul>
                    </div>
                    <div class="inquiry-details">
                        <p><strong>Reference ID:</strong> ${data.inquiry_id}</p>
                    </div>
                </div>
            `);
            
            $form.replaceWith($successMessage);
        },

        /**
         * Show field error
         */
        showFieldError($field, message) {
            this.clearFieldError($field);
            $field.addClass('error');
            $field.after(`<span class="field-error">${message}</span>`);
        },

        /**
         * Clear field error
         */
        clearFieldError($field) {
            $field.removeClass('error');
            $field.siblings('.field-error').remove();
        },

        /**
         * Show success message
         */
        showSuccess(message) {
            this.showNotification(message, 'success');
        },

        /**
         * Show error message
         */
        showError(message) {
            this.showNotification(message, 'error');
        },

        /**
         * Show notification
         */
        showNotification(message, type = 'info') {
            const $notification = $(`
                <div class="franchise-notification ${type}">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" aria-label="Close">&times;</button>
                </div>
            `);

            $('body').append($notification);

            setTimeout(() => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);

            $notification.find('.notification-close').on('click', () => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            });
        }
    };

    // Initialize when DOM is ready
    $(document).ready(() => {
        FranchiseModule.init();
    });

})(jQuery);