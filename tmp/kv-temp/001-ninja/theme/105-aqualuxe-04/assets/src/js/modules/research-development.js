/**
 * Research & Development Module JavaScript
 *
 * Handles R&D and sustainability functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Research & Development Module
     */
    const AquaLuxeRD = {
        
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
            // Research proposal form
            $(document).on('submit', '#research-proposal-form', this.handleProposalForm.bind(this));
            
            // Project collaboration
            $(document).on('click', '.join-project-btn', this.handleProjectJoin.bind(this));
            
            // Sustainability report submission
            $(document).on('submit', '#sustainability-report-form', this.handleSustainabilityReport.bind(this));
            
            // Innovation showcase interaction
            $(document).on('click', '.innovation-card', this.showInnovationDetails.bind(this));
            
            // Research area filter
            $(document).on('change', '.research-area-filter', this.filterProjects.bind(this));
            
            // Progress tracking
            $(document).on('click', '.update-progress-btn', this.updateProjectProgress.bind(this));
            
            // Impact calculator
            $(document).on('input', '.impact-calculator input', this.calculateImpact.bind(this));
        },

        /**
         * Initialize components
         */
        initializeComponents() {
            this.initializeProjectCards();
            this.initializeSustainabilityTracker();
            this.initializeImpactCalculator();
            this.setupProgressBars();
            this.loadLatestResearch();
        },

        /**
         * Initialize project cards
         */
        initializeProjectCards() {
            $('.research-project-card').each(function() {
                const $card = $(this);
                const progress = parseFloat($card.data('progress') || 0);
                
                // Initialize progress bar
                const $progressBar = $card.find('.progress-fill');
                if ($progressBar.length) {
                    $progressBar.css('width', '0%').animate({
                        width: progress + '%'
                    }, 1000);
                }

                // Add hover effects
                $card.hover(
                    function() {
                        $(this).addClass('card-hover');
                    },
                    function() {
                        $(this).removeClass('card-hover');
                    }
                );
            });
        },

        /**
         * Initialize sustainability tracker
         */
        initializeSustainabilityTracker() {
            $('.sustainability-tracker').each(function() {
                const $tracker = $(this);
                
                // Animate metrics on load
                $tracker.find('.metric-card').each(function(index) {
                    const $metric = $(this);
                    
                    setTimeout(() => {
                        $metric.addClass('metric-animate');
                        
                        const $progressBar = $metric.find('.progress-fill');
                        if ($progressBar.length) {
                            const progress = parseFloat($progressBar.data('progress') || 0);
                            $progressBar.css('width', '0%').animate({
                                width: progress + '%'
                            }, 800);
                        }
                    }, index * 200);
                });
            });
        },

        /**
         * Initialize impact calculator
         */
        initializeImpactCalculator() {
            const $calculator = $('.impact-calculator');
            
            if ($calculator.length) {
                // Set up real-time calculation
                $calculator.find('input, select').on('input change', this.calculateImpact.bind(this));
                
                // Initialize with default values
                this.calculateImpact();
            }
        },

        /**
         * Setup progress bars
         */
        setupProgressBars() {
            $('.progress-bar').each(function() {
                const $bar = $(this);
                const progress = parseFloat($bar.data('progress') || 0);
                const $fill = $bar.find('.progress-fill');
                
                // Animate progress bar
                setTimeout(() => {
                    $fill.css('width', progress + '%');
                }, 500);
            });
        },

        /**
         * Handle research proposal form
         */
        handleProposalForm(e) {
            e.preventDefault();
            
            const $form = $(e.target);
            const $submitBtn = $form.find('button[type="submit"]');
            
            // Validate form
            if (!this.validateProposalForm($form)) {
                return;
            }

            // Get form data
            const formData = new FormData($form[0]);
            formData.append('action', 'aqualuxe_submit_research_proposal');
            formData.append('nonce', aqualuxe_rd.nonce);

            // Show loading state
            this.setButtonLoading($submitBtn, true);

            // Submit form
            $.ajax({
                url: aqualuxe_rd.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        this.handleProposalSuccess(response.data, $form);
                    } else {
                        this.handleProposalError(response.data, $form);
                    }
                },
                error: () => {
                    this.handleProposalError({
                        message: aqualuxe_rd.messages.error
                    }, $form);
                },
                complete: () => {
                    this.setButtonLoading($submitBtn, false);
                }
            });
        },

        /**
         * Handle project join request
         */
        handleProjectJoin(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            const projectId = $btn.data('project-id');
            const userRole = $btn.data('role') || 'contributor';

            if (!projectId) {
                this.showMessage('Invalid project ID.', 'error');
                return;
            }

            this.setButtonLoading($btn, true);

            $.ajax({
                url: aqualuxe_rd.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_join_research_project',
                    project_id: projectId,
                    role: userRole,
                    nonce: aqualuxe_rd.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showMessage(aqualuxe_rd.messages.joined, 'success');
                        $btn.text('Request Sent').prop('disabled', true);
                    } else {
                        this.showMessage(response.data.message || aqualuxe_rd.messages.error, 'error');
                    }
                },
                error: () => {
                    this.showMessage(aqualuxe_rd.messages.error, 'error');
                },
                complete: () => {
                    this.setButtonLoading($btn, false);
                }
            });
        },

        /**
         * Handle sustainability report
         */
        handleSustainabilityReport(e) {
            e.preventDefault();
            
            const $form = $(e.target);
            const $submitBtn = $form.find('button[type="submit"]');
            
            // Get form data
            const formData = new FormData($form[0]);
            formData.append('action', 'aqualuxe_submit_sustainability_report');
            formData.append('nonce', aqualuxe_rd.nonce);

            this.setButtonLoading($submitBtn, true);

            $.ajax({
                url: aqualuxe_rd.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        this.showMessage('Sustainability report submitted successfully!', 'success');
                        $form[0].reset();
                    } else {
                        this.showMessage(response.data.message || 'Failed to submit report.', 'error');
                    }
                },
                error: () => {
                    this.showMessage('Failed to submit report.', 'error');
                },
                complete: () => {
                    this.setButtonLoading($submitBtn, false);
                }
            });
        },

        /**
         * Show innovation details
         */
        showInnovationDetails(e) {
            const $card = $(e.currentTarget);
            const innovationId = $card.data('innovation-id');
            
            if (!innovationId) return;

            // Load innovation details
            $.ajax({
                url: aqualuxe_rd.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_innovation_details',
                    innovation_id: innovationId,
                    nonce: aqualuxe_rd.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.displayInnovationModal(response.data);
                    }
                }
            });
        },

        /**
         * Filter research projects
         */
        filterProjects(e) {
            const $filter = $(e.target);
            const selectedArea = $filter.val();
            const $projects = $('.research-project-card');

            if (selectedArea === '') {
                $projects.show().addClass('fadeIn');
            } else {
                $projects.each(function() {
                    const $project = $(this);
                    const projectAreas = $project.data('research-areas') || '';
                    
                    if (projectAreas.includes(selectedArea)) {
                        $project.show().addClass('fadeIn');
                    } else {
                        $project.hide().removeClass('fadeIn');
                    }
                });
            }
        },

        /**
         * Update project progress
         */
        updateProjectProgress(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            const projectId = $btn.data('project-id');
            const newProgress = prompt('Enter new progress percentage (0-100):');
            
            if (newProgress === null || isNaN(newProgress) || newProgress < 0 || newProgress > 100) {
                this.showMessage('Please enter a valid progress percentage.', 'error');
                return;
            }

            this.setButtonLoading($btn, true);

            $.ajax({
                url: aqualuxe_rd.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_update_project_progress',
                    project_id: projectId,
                    progress: newProgress,
                    nonce: aqualuxe_rd.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateProgressBar(projectId, newProgress);
                        this.showMessage('Progress updated successfully!', 'success');
                    } else {
                        this.showMessage(response.data.message || 'Failed to update progress.', 'error');
                    }
                },
                error: () => {
                    this.showMessage('Failed to update progress.', 'error');
                },
                complete: () => {
                    this.setButtonLoading($btn, false);
                }
            });
        },

        /**
         * Calculate environmental impact
         */
        calculateImpact() {
            const $calculator = $('.impact-calculator');
            
            if (!$calculator.length) return;

            // Get input values
            const carbonReduction = parseFloat($calculator.find('input[name="carbon_reduction"]').val() || 0);
            const waterSaving = parseFloat($calculator.find('input[name="water_saving"]').val() || 0);
            const wasteReduction = parseFloat($calculator.find('input[name="waste_reduction"]').val() || 0);
            const energySaving = parseFloat($calculator.find('input[name="energy_saving"]').val() || 0);

            // Calculate total impact score
            const impactScore = this.calculateImpactScore(carbonReduction, waterSaving, wasteReduction, energySaving);
            
            // Update display
            $calculator.find('.impact-score').text(impactScore.toFixed(1));
            $calculator.find('.impact-rating').text(this.getImpactRating(impactScore));
            
            // Update progress bar
            const $progressBar = $calculator.find('.impact-progress .progress-fill');
            $progressBar.css('width', Math.min(impactScore * 10, 100) + '%');
        },

        /**
         * Calculate impact score
         */
        calculateImpactScore(carbon, water, waste, energy) {
            // Weighted calculation based on environmental priorities
            const weights = {
                carbon: 0.4,
                water: 0.25,
                waste: 0.2,
                energy: 0.15
            };

            return (carbon * weights.carbon + 
                   water * weights.water + 
                   waste * weights.waste + 
                   energy * weights.energy);
        },

        /**
         * Get impact rating
         */
        getImpactRating(score) {
            if (score >= 8) return 'Excellent';
            if (score >= 6) return 'Good';
            if (score >= 4) return 'Fair';
            if (score >= 2) return 'Poor';
            return 'Minimal';
        },

        /**
         * Load latest research
         */
        loadLatestResearch() {
            $.ajax({
                url: aqualuxe_rd.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_latest_research',
                    limit: 5,
                    nonce: aqualuxe_rd.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.displayLatestResearch(response.data);
                    }
                }
            });
        },

        /**
         * Display latest research
         */
        displayLatestResearch(research) {
            const $container = $('.latest-research-container');
            
            if (!$container.length) return;

            let html = '<h4>Latest Research Updates</h4><div class="research-updates">';
            
            research.forEach(item => {
                html += `
                    <div class="research-update">
                        <h5><a href="${item.link}">${item.title}</a></h5>
                        <p class="update-meta">
                            <span class="research-area">${item.research_area}</span>
                            <span class="update-date">${item.date}</span>
                        </p>
                        <p class="update-excerpt">${item.excerpt}</p>
                    </div>
                `;
            });
            
            html += '</div>';
            
            $container.html(html);
        },

        /**
         * Display innovation modal
         */
        displayInnovationModal(innovation) {
            const modalHtml = `
                <div class="innovation-modal">
                    <div class="modal-content">
                        <span class="modal-close">&times;</span>
                        <h2>${innovation.title}</h2>
                        <div class="innovation-details">
                            <div class="innovation-meta">
                                <span class="research-area">${innovation.research_area}</span>
                                <span class="impact-score">Impact: ${innovation.impact_score}/10</span>
                            </div>
                            <div class="innovation-description">
                                ${innovation.description}
                            </div>
                            <div class="innovation-team">
                                <h4>Research Team</h4>
                                <p>${innovation.team_lead}</p>
                            </div>
                            <div class="innovation-progress">
                                <h4>Progress</h4>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: ${innovation.progress}%"></div>
                                </div>
                                <span class="progress-text">${innovation.progress}% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal
            $('.innovation-modal').remove();
            
            // Add new modal
            $('body').append(modalHtml);
            
            // Show modal
            $('.innovation-modal').fadeIn();
            
            // Close modal handlers
            $('.modal-close, .innovation-modal').on('click', function(e) {
                if (e.target === this) {
                    $('.innovation-modal').fadeOut(() => {
                        $('.innovation-modal').remove();
                    });
                }
            });
        },

        /**
         * Update progress bar
         */
        updateProgressBar(projectId, progress) {
            const $project = $(`.research-project-card[data-project-id="${projectId}"]`);
            const $progressBar = $project.find('.progress-fill');
            const $progressText = $project.find('.progress-text');
            
            $progressBar.animate({ width: progress + '%' }, 500);
            $progressText.text(progress + '% Complete');
        },

        /**
         * Validate proposal form
         */
        validateProposalForm($form) {
            let isValid = true;
            
            // Check required fields
            $form.find('[required]').each(function() {
                const $field = $(this);
                const value = $field.val().trim();
                
                if (!value) {
                    AquaLuxeRD.showFieldError($field, 'This field is required.');
                    isValid = false;
                } else {
                    AquaLuxeRD.hideFieldError($field);
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
            
            // Validate description length
            const $descriptionField = $form.find('textarea[name="description"]');
            if ($descriptionField.length) {
                const description = $descriptionField.val();
                if (description && description.length < 100) {
                    this.showFieldError($descriptionField, 'Description must be at least 100 characters long.');
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
         * Handle proposal success
         */
        handleProposalSuccess(data, $form) {
            this.showMessage(data.message, 'success');
            
            // Replace form with success message
            $form.html(`
                <div class="proposal-success">
                    <div class="success-icon">🔬</div>
                    <h3>Proposal Submitted!</h3>
                    <p>${data.message}</p>
                    <p class="proposal-id">Proposal ID: ${data.proposal_id}</p>
                    <p class="next-steps">Our research team will review your proposal and contact you within 5-7 business days.</p>
                </div>
            `);
        },

        /**
         * Handle proposal error
         */
        handleProposalError(data, $form) {
            this.showMessage(data.message || aqualuxe_rd.messages.error, 'error');
        },

        /**
         * Set button loading state
         */
        setButtonLoading($btn, isLoading) {
            if (isLoading) {
                $btn.prop('disabled', true)
                    .data('original-text', $btn.text())
                    .html('<span class="spinner"></span> ' + aqualuxe_rd.messages.submitting);
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
        }
    };

    /**
     * Initialize when document is ready
     */
    $(document).ready(() => {
        AquaLuxeRD.init();
    });

    // Make it globally available
    window.AquaLuxeRD = AquaLuxeRD;

})(jQuery);