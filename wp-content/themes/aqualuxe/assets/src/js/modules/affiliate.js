/**
 * Affiliate Module JavaScript
 *
 * Handles affiliate marketing and referral functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Affiliate Module
     */
    const AquaLuxeAffiliate = {
        
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
            // Affiliate registration form
            $(document).on('submit', '#affiliate-registration-form', this.handleRegistrationForm.bind(this));
            
            // Referral link generation
            $(document).on('click', '.generate-referral-link', this.generateReferralLink.bind(this));
            
            // Copy referral link
            $(document).on('click', '.copy-link-btn', this.copyReferralLink.bind(this));
            
            // Dashboard stats refresh
            $(document).on('click', '.refresh-stats-btn', this.refreshStats.bind(this));
            
            // Payout request
            $(document).on('click', '.request-payout-btn', this.requestPayout.bind(this));
            
            // Marketing material download
            $(document).on('click', '.download-material-btn', this.downloadMarketingMaterial.bind(this));
        },

        /**
         * Initialize components
         */
        initializeComponents() {
            this.initializeReferralTracking();
            this.initializeDashboard();
            this.setupValidation();
            this.loadAffiliateStats();
        },

        /**
         * Initialize referral tracking
         */
        initializeReferralTracking() {
            // Track referral clicks
            $('a[href*="?ref="]').on('click', this.trackReferralClick.bind(this));
            
            // Update referral links on page
            this.updateReferralLinks();
        },

        /**
         * Initialize affiliate dashboard
         */
        initializeDashboard() {
            const $dashboard = $('.affiliate-dashboard');
            
            if ($dashboard.length) {
                this.initializeStatsCharts();
                this.setupRealTimeUpdates();
            }
        },

        /**
         * Setup form validation
         */
        setupValidation() {
            // Email validation
            $(document).on('blur', 'input[type="email"]', function() {
                const email = $(this).val();
                const isValid = AquaLuxeAffiliate.validateEmail(email);
                
                $(this).toggleClass('invalid', !isValid);
                
                if (!isValid && email) {
                    AquaLuxeAffiliate.showFieldError($(this), 'Please enter a valid email address.');
                } else {
                    AquaLuxeAffiliate.hideFieldError($(this));
                }
            });

            // Website URL validation
            $(document).on('blur', 'input[type="url"]', function() {
                const url = $(this).val();
                const isValid = AquaLuxeAffiliate.validateURL(url);
                
                $(this).toggleClass('invalid', !isValid && url);
                
                if (!isValid && url) {
                    AquaLuxeAffiliate.showFieldError($(this), 'Please enter a valid URL.');
                } else {
                    AquaLuxeAffiliate.hideFieldError($(this));
                }
            });
        },

        /**
         * Handle affiliate registration form
         */
        handleRegistrationForm(e) {
            e.preventDefault();
            
            const $form = $(e.target);
            const $submitBtn = $form.find('button[type="submit"]');
            
            // Validate form
            if (!this.validateRegistrationForm($form)) {
                return;
            }

            // Get form data
            const formData = new FormData($form[0]);
            formData.append('action', 'aqualuxe_register_affiliate');
            formData.append('nonce', aqualuxe_affiliate.nonce);

            // Show loading state
            this.setButtonLoading($submitBtn, true);

            // Submit form
            $.ajax({
                url: aqualuxe_affiliate.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        this.handleRegistrationSuccess(response.data, $form);
                    } else {
                        this.handleRegistrationError(response.data, $form);
                    }
                },
                error: () => {
                    this.handleRegistrationError({
                        message: aqualuxe_affiliate.messages.error
                    }, $form);
                },
                complete: () => {
                    this.setButtonLoading($submitBtn, false);
                }
            });
        },

        /**
         * Generate referral link
         */
        generateReferralLink(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            const productId = $btn.data('product-id');
            const pageUrl = $btn.data('page-url') || window.location.href;

            this.setButtonLoading($btn, true);

            $.ajax({
                url: aqualuxe_affiliate.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_generate_referral_link',
                    product_id: productId,
                    page_url: pageUrl,
                    nonce: aqualuxe_affiliate.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showReferralLink(response.data.referral_link, $btn);
                        this.showMessage(aqualuxe_affiliate.messages.link_generated, 'success');
                    } else {
                        this.showMessage(response.data.message || aqualuxe_affiliate.messages.error, 'error');
                    }
                },
                error: () => {
                    this.showMessage(aqualuxe_affiliate.messages.error, 'error');
                },
                complete: () => {
                    this.setButtonLoading($btn, false);
                }
            });
        },

        /**
         * Copy referral link to clipboard
         */
        copyReferralLink(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            const $linkInput = $btn.siblings('.referral-link');
            const link = $linkInput.val();

            if (!link) {
                this.showMessage('No referral link to copy.', 'error');
                return;
            }

            // Copy to clipboard
            if (navigator.clipboard) {
                navigator.clipboard.writeText(link).then(() => {
                    this.showMessage('Referral link copied to clipboard!', 'success');
                    this.updateButtonText($btn, 'Copied!', 2000);
                }).catch(() => {
                    this.fallbackCopyTextToClipboard(link, $btn);
                });
            } else {
                this.fallbackCopyTextToClipboard(link, $btn);
            }
        },

        /**
         * Fallback copy method
         */
        fallbackCopyTextToClipboard(text, $btn) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    this.showMessage('Referral link copied to clipboard!', 'success');
                    this.updateButtonText($btn, 'Copied!', 2000);
                } else {
                    this.showMessage('Unable to copy link. Please copy manually.', 'error');
                }
            } catch (err) {
                this.showMessage('Unable to copy link. Please copy manually.', 'error');
            }

            document.body.removeChild(textArea);
        },

        /**
         * Refresh affiliate statistics
         */
        refreshStats(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            this.setButtonLoading($btn, true);

            $.ajax({
                url: aqualuxe_affiliate.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_affiliate_stats',
                    nonce: aqualuxe_affiliate.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateDashboardStats(response.data);
                        this.showMessage('Statistics updated successfully!', 'success');
                    } else {
                        this.showMessage(response.data.message || 'Failed to refresh statistics.', 'error');
                    }
                },
                error: () => {
                    this.showMessage('Failed to refresh statistics.', 'error');
                },
                complete: () => {
                    this.setButtonLoading($btn, false);
                }
            });
        },

        /**
         * Request payout
         */
        requestPayout(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            const minPayout = parseFloat($btn.data('min-payout') || 50);
            const currentBalance = parseFloat($btn.data('current-balance') || 0);

            if (currentBalance < minPayout) {
                this.showMessage(`Minimum payout amount is $${minPayout.toFixed(2)}. Your current balance is $${currentBalance.toFixed(2)}.`, 'warning');
                return;
            }

            if (!confirm(`Request payout of $${currentBalance.toFixed(2)}?`)) {
                return;
            }

            this.setButtonLoading($btn, true);

            $.ajax({
                url: aqualuxe_affiliate.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_request_payout',
                    amount: currentBalance,
                    nonce: aqualuxe_affiliate.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showMessage(response.data.message, 'success');
                        this.updatePayoutStatus('requested');
                    } else {
                        this.showMessage(response.data.message || 'Failed to request payout.', 'error');
                    }
                },
                error: () => {
                    this.showMessage('Failed to request payout.', 'error');
                },
                complete: () => {
                    this.setButtonLoading($btn, false);
                }
            });
        },

        /**
         * Download marketing material
         */
        downloadMarketingMaterial(e) {
            e.preventDefault();
            
            const $btn = $(e.target);
            const materialType = $btn.data('material-type');
            const materialId = $btn.data('material-id');

            // Create download link
            const downloadUrl = `${aqualuxe_affiliate.ajax_url}?action=aqualuxe_download_material&type=${materialType}&id=${materialId}&nonce=${aqualuxe_affiliate.nonce}`;
            
            // Track download
            this.trackDownload(materialType, materialId);
            
            // Trigger download
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = `aqualuxe-${materialType}-${materialId}`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        /**
         * Track referral click
         */
        trackReferralClick(e) {
            const $link = $(e.target);
            const href = $link.attr('href');
            const refMatch = href.match(/[?&]ref=([^&]+)/);
            
            if (refMatch) {
                const affiliateCode = refMatch[1];
                
                // Track click (non-blocking)
                $.ajax({
                    url: aqualuxe_affiliate.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_track_click',
                        affiliate_code: affiliateCode,
                        target_url: href,
                        nonce: aqualuxe_affiliate.nonce
                    },
                    async: true
                });
            }
        },

        /**
         * Update referral links on page
         */
        updateReferralLinks() {
            const affiliateCode = aqualuxe_affiliate.affiliate_code;
            
            if (!affiliateCode) return;

            // Update product links
            $('.product-link, .add-to-cart-link').each(function() {
                const $link = $(this);
                const href = $link.attr('href');
                
                if (href && !href.includes('ref=')) {
                    const separator = href.includes('?') ? '&' : '?';
                    $link.attr('href', href + separator + 'ref=' + affiliateCode);
                }
            });
        },

        /**
         * Initialize stats charts
         */
        initializeStatsCharts() {
            // This would integrate with a charting library like Chart.js
            // For now, we'll create simple progress bars
            $('.stat-chart').each(function() {
                const $chart = $(this);
                const value = parseFloat($chart.data('value'));
                const max = parseFloat($chart.data('max'));
                const percentage = (value / max) * 100;
                
                $chart.find('.chart-bar').css('width', percentage + '%');
            });
        },

        /**
         * Setup real-time updates
         */
        setupRealTimeUpdates() {
            // Set up periodic updates for dashboard stats
            setInterval(() => {
                this.loadAffiliateStats(true); // Silent update
            }, 300000); // Every 5 minutes
        },

        /**
         * Load affiliate statistics
         */
        loadAffiliateStats(silent = false) {
            $.ajax({
                url: aqualuxe_affiliate.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_affiliate_stats',
                    nonce: aqualuxe_affiliate.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateDashboardStats(response.data);
                        
                        if (!silent) {
                            this.showMessage('Statistics loaded successfully!', 'success');
                        }
                    }
                },
                error: () => {
                    if (!silent) {
                        this.showMessage('Failed to load statistics.', 'error');
                    }
                }
            });
        },

        /**
         * Update dashboard statistics
         */
        updateDashboardStats(data) {
            // Update stat values
            Object.keys(data).forEach(key => {
                const $statValue = $(`.stat-value[data-stat="${key}"]`);
                if ($statValue.length) {
                    $statValue.text(this.formatStatValue(key, data[key]));
                }
            });

            // Update charts if present
            this.updateStatsCharts(data);
        },

        /**
         * Update stats charts
         */
        updateStatsCharts(data) {
            $('.stat-chart').each(function() {
                const $chart = $(this);
                const statKey = $chart.data('stat');
                
                if (data[statKey] !== undefined) {
                    const value = parseFloat(data[statKey]);
                    const max = parseFloat($chart.data('max'));
                    const percentage = Math.min((value / max) * 100, 100);
                    
                    $chart.find('.chart-bar').animate({
                        width: percentage + '%'
                    }, 500);
                }
            });
        },

        /**
         * Format stat value for display
         */
        formatStatValue(key, value) {
            if (key.includes('commission') || key.includes('sales') || key.includes('earning')) {
                return '$' + parseFloat(value).toFixed(2);
            } else if (key.includes('rate')) {
                return parseFloat(value).toFixed(1) + '%';
            } else {
                return value;
            }
        },

        /**
         * Show referral link
         */
        showReferralLink(link, $btn) {
            const $container = $btn.closest('.referral-link-generator');
            
            let $linkDisplay = $container.find('.generated-link');
            if (!$linkDisplay.length) {
                $linkDisplay = $(`
                    <div class="generated-link">
                        <input type="text" class="referral-link form-control" readonly>
                        <button class="copy-link-btn btn btn-secondary">Copy</button>
                    </div>
                `);
                $container.append($linkDisplay);
            }
            
            $linkDisplay.find('.referral-link').val(link);
            $linkDisplay.show();
        },

        /**
         * Update payout status
         */
        updatePayoutStatus(status) {
            const $payoutSection = $('.payout-section');
            $payoutSection.find('.payout-status').text(status.charAt(0).toUpperCase() + status.slice(1));
            
            if (status === 'requested') {
                $payoutSection.find('.request-payout-btn').prop('disabled', true).text('Payout Requested');
            }
        },

        /**
         * Track download
         */
        trackDownload(type, id) {
            $.ajax({
                url: aqualuxe_affiliate.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_track_download',
                    material_type: type,
                    material_id: id,
                    nonce: aqualuxe_affiliate.nonce
                },
                async: true
            });
        },

        /**
         * Validate registration form
         */
        validateRegistrationForm($form) {
            let isValid = true;
            
            // Check required fields
            $form.find('[required]').each(function() {
                const $field = $(this);
                const value = $field.val().trim();
                
                if (!value) {
                    AquaLuxeAffiliate.showFieldError($field, 'This field is required.');
                    isValid = false;
                } else {
                    AquaLuxeAffiliate.hideFieldError($field);
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
            
            // Validate URL if provided
            const $urlField = $form.find('input[type="url"]');
            if ($urlField.length) {
                const url = $urlField.val();
                if (url && !this.validateURL(url)) {
                    this.showFieldError($urlField, 'Please enter a valid URL.');
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
         * Validate URL
         */
        validateURL(url) {
            try {
                new URL(url);
                return true;
            } catch {
                return false;
            }
        },

        /**
         * Handle registration success
         */
        handleRegistrationSuccess(data, $form) {
            this.showMessage(data.message, 'success');
            
            // Replace form with success message
            $form.html(`
                <div class="registration-success">
                    <div class="success-icon">✓</div>
                    <h3>Registration Successful!</h3>
                    <p>${data.message}</p>
                    <p class="next-steps">You will receive an email once your application is approved.</p>
                </div>
            `);
        },

        /**
         * Handle registration error
         */
        handleRegistrationError(data, $form) {
            this.showMessage(data.message || aqualuxe_affiliate.messages.error, 'error');
        },

        /**
         * Set button loading state
         */
        setButtonLoading($btn, isLoading) {
            if (isLoading) {
                $btn.prop('disabled', true)
                    .data('original-text', $btn.text())
                    .html('<span class="spinner"></span> ' + aqualuxe_affiliate.messages.registering);
            } else {
                $btn.prop('disabled', false)
                    .text($btn.data('original-text') || $btn.text());
            }
        },

        /**
         * Update button text temporarily
         */
        updateButtonText($btn, newText, duration) {
            const originalText = $btn.text();
            $btn.text(newText);
            
            setTimeout(() => {
                $btn.text(originalText);
            }, duration);
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
        AquaLuxeAffiliate.init();
    });

    // Make it globally available
    window.AquaLuxeAffiliate = AquaLuxeAffiliate;

})(jQuery);