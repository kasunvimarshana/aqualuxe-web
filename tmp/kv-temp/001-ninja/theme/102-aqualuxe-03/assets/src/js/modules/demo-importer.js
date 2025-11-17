/**
 * Demo Content Importer JavaScript Module
 * 
 * Handles demo content import functionality.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Demo Importer object
    const DemoImporter = {
        
        /**
         * Initialize demo importer functionality
         */
        init() {
            this.bindEvents();
            this.initProgressBar();
        },
        
        /**
         * Bind events
         */
        bindEvents() {
            // Import button
            $(document).on('click', '.demo-import-btn', this.handleImport.bind(this));
            
            // Reset/flush button
            $(document).on('click', '.demo-reset-btn', this.handleReset.bind(this));
            
            // Preview button
            $(document).on('click', '.demo-preview-btn', this.handlePreview.bind(this));
        },
        
        /**
         * Initialize progress bar
         */
        initProgressBar() {
            this.$progressBar = $('.demo-import-progress');
            this.$progressText = $('.demo-import-progress-text');
            this.$progressPercent = $('.demo-import-progress-percent');
        },
        
        /**
         * Handle demo import
         */
        handleImport(e) {
            e.preventDefault();
            
            const $btn = $(e.currentTarget);
            const demoType = $btn.data('demo-type') || 'default';
            
            // Confirm import
            if (!confirm('This will import demo content. Continue?')) {
                return;
            }
            
            this.startImport($btn, demoType);
        },
        
        /**
         * Start import process
         */
        startImport($btn, demoType) {
            // Disable button and show loading
            $btn.prop('disabled', true).text('Importing...');
            
            // Show progress bar
            this.showProgress();
            
            // Start import via AJAX
            this.importStep('posts', demoType, 0);
        },
        
        /**
         * Import step
         */
        importStep(step, demoType, progress) {
            const steps = ['posts', 'pages', 'media', 'customizer', 'widgets', 'menus'];
            const currentIndex = steps.indexOf(step);
            const totalSteps = steps.length;
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    step: step,
                    demo_type: demoType,
                    nonce: $('#demo-import-nonce').val()
                },
                success: (response) => {
                    if (response.success) {
                        const newProgress = ((currentIndex + 1) / totalSteps) * 100;
                        this.updateProgress(newProgress, response.data.message || `Importing ${step}...`);
                        
                        // Continue to next step
                        if (currentIndex < totalSteps - 1) {
                            const nextStep = steps[currentIndex + 1];
                            setTimeout(() => {
                                this.importStep(nextStep, demoType, newProgress);
                            }, 1000);
                        } else {
                            this.completeImport();
                        }
                    } else {
                        this.handleError(response.data || 'Import failed');
                    }
                },
                error: () => {
                    this.handleError('Network error during import');
                }
            });
        },
        
        /**
         * Complete import
         */
        completeImport() {
            this.updateProgress(100, 'Import completed successfully!');
            
            setTimeout(() => {
                this.hideProgress();
                $('.demo-import-btn').prop('disabled', false).text('Import Demo Content');
                
                // Show success message
                this.showMessage('Demo content imported successfully!', 'success');
                
                // Optionally reload page
                if (confirm('Demo content imported! Reload page to see changes?')) {
                    window.location.reload();
                }
            }, 2000);
        },
        
        /**
         * Handle demo reset
         */
        handleReset(e) {
            e.preventDefault();
            
            if (!confirm('This will remove all demo content. Are you sure?')) {
                return;
            }
            
            const $btn = $(e.currentTarget);
            $btn.prop('disabled', true).text('Resetting...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_reset_demo',
                    nonce: $('#demo-reset-nonce').val()
                },
                success: (response) => {
                    if (response.success) {
                        this.showMessage('Demo content reset successfully!', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        this.handleError(response.data || 'Reset failed');
                    }
                },
                error: () => {
                    this.handleError('Network error during reset');
                },
                complete: () => {
                    $btn.prop('disabled', false).text('Reset Demo Content');
                }
            });
        },
        
        /**
         * Handle demo preview
         */
        handlePreview(e) {
            e.preventDefault();
            
            const $btn = $(e.currentTarget);
            const previewUrl = $btn.data('preview-url');
            
            if (previewUrl) {
                window.open(previewUrl, '_blank');
            }
        },
        
        /**
         * Show progress bar
         */
        showProgress() {
            this.$progressBar.removeClass('hidden');
            this.updateProgress(0, 'Starting import...');
        },
        
        /**
         * Hide progress bar
         */
        hideProgress() {
            this.$progressBar.addClass('hidden');
        },
        
        /**
         * Update progress
         */
        updateProgress(percent, text) {
            if (this.$progressBar.length) {
                this.$progressBar.find('.aqualuxe-progress-bar').css('width', percent + '%');
                
                if (this.$progressPercent.length) {
                    this.$progressPercent.text(Math.round(percent) + '%');
                }
                
                if (this.$progressText.length && text) {
                    this.$progressText.text(text);
                }
            }
        },
        
        /**
         * Handle errors
         */
        handleError(message) {
            this.hideProgress();
            $('.demo-import-btn').prop('disabled', false).text('Import Demo Content');
            this.showMessage(message, 'error');
        },
        
        /**
         * Show message
         */
        showMessage(message, type = 'info') {
            const $notice = $(`
                <div class="aqualuxe-notice aqualuxe-notice-${type}">
                    <p>${message}</p>
                    <button type="button" class="notice-dismiss">&times;</button>
                </div>
            `);
            
            $('.demo-importer-messages').append($notice);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $notice.fadeOut(() => $notice.remove());
            }, 5000);
            
            // Manual dismiss
            $notice.find('.notice-dismiss').on('click', () => {
                $notice.fadeOut(() => $notice.remove());
            });
        }
    };
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        if ($('.demo-importer').length) {
            DemoImporter.init();
        }
    });
    
    // Expose to global scope
    window.AquaLuxeDemoImporter = DemoImporter;
    
})(jQuery);