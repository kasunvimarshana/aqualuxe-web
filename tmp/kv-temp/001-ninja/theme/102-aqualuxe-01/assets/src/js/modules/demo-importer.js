/**
 * Demo Importer Module JavaScript
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class DemoImporterModule {
        constructor() {
            this.progressInterval = null;
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            $(document).on('click', '.demo-import-btn', this.startImport.bind(this));
            $(document).on('click', '.demo-reset-btn', this.resetContent.bind(this));
            $(document).on('change', '.import-options input', this.updateImportOptions.bind(this));
        }

        startImport(e) {
            e.preventDefault();
            
            const button = $(e.currentTarget);
            const demoId = button.data('demo-id');
            const options = this.getImportOptions();
            
            if (!this.confirmImport()) {
                return;
            }
            
            this.showProgressModal();
            this.disableButtons();
            
            $.ajax({
                url: aqualuxe_demo_importer.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    demo_id: demoId,
                    options: options,
                    nonce: aqualuxe_demo_importer.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateProgress(100);
                        this.showSuccess(response.data.message);
                        setTimeout(() => {
                            this.hideProgressModal();
                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            }
                        }, 2000);
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: (xhr, status, error) => {
                    this.showError('Import failed: ' + error);
                },
                complete: () => {
                    this.enableButtons();
                    this.stopProgressTracking();
                }
            });
            
            // Start progress tracking
            this.startProgressTracking();
        }

        resetContent(e) {
            e.preventDefault();
            
            if (!this.confirmReset()) {
                return;
            }
            
            this.showProgressModal();
            this.disableButtons();
            
            $.ajax({
                url: aqualuxe_demo_importer.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_reset_content',
                    nonce: aqualuxe_demo_importer.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showSuccess(response.data.message);
                        setTimeout(() => {
                            this.hideProgressModal();
                            window.location.reload();
                        }, 2000);
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: (xhr, status, error) => {
                    this.showError('Reset failed: ' + error);
                },
                complete: () => {
                    this.enableButtons();
                }
            });
        }

        getImportOptions() {
            const options = {};
            $('.import-options input:checked').each(function() {
                options[$(this).attr('name')] = true;
            });
            return options;
        }

        updateImportOptions() {
            // Update import preview based on selected options
            this.updateImportPreview();
        }

        updateImportPreview() {
            const options = this.getImportOptions();
            const preview = $('.import-preview');
            
            let previewText = 'The following will be imported:\n';
            
            if (options.content) previewText += '• Posts, Pages, and Media\n';
            if (options.customizer) previewText += '• Theme Settings\n';
            if (options.widgets) previewText += '• Widgets\n';
            if (options.options) previewText += '• Theme Options\n';
            
            preview.text(previewText);
        }

        confirmImport() {
            return confirm('Are you sure you want to import this demo content? This will replace your existing content.');
        }

        confirmReset() {
            return confirm('Are you sure you want to reset all content? This action cannot be undone.');
        }

        showProgressModal() {
            if (!$('#demo-import-progress').length) {
                $('body').append(`
                    <div id="demo-import-progress" class="modal active">
                        <div class="modal-backdrop"></div>
                        <div class="modal-container">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">Importing Demo Content</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 0%"></div>
                                    </div>
                                    <div class="progress-text">Initializing import...</div>
                                    <div class="progress-log"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            } else {
                $('#demo-import-progress').addClass('active');
            }
        }

        hideProgressModal() {
            $('#demo-import-progress').removeClass('active');
        }

        updateProgress(percentage, message = '') {
            $('#demo-import-progress .progress-fill').css('width', percentage + '%');
            if (message) {
                $('#demo-import-progress .progress-text').text(message);
            }
        }

        addProgressLog(message) {
            const log = $('#demo-import-progress .progress-log');
            log.append('<div class="log-entry">' + message + '</div>');
            log.scrollTop(log[0].scrollHeight);
        }

        startProgressTracking() {
            this.progressInterval = setInterval(() => {
                this.checkImportProgress();
            }, 2000);
        }

        stopProgressTracking() {
            if (this.progressInterval) {
                clearInterval(this.progressInterval);
                this.progressInterval = null;
            }
        }

        checkImportProgress() {
            $.ajax({
                url: aqualuxe_demo_importer.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_progress',
                    nonce: aqualuxe_demo_importer.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateProgress(response.data.percentage, response.data.message);
                        if (response.data.log) {
                            this.addProgressLog(response.data.log);
                        }
                    }
                }
            });
        }

        showSuccess(message) {
            $('#demo-import-progress .progress-text').html('<span style="color: green;">' + message + '</span>');
        }

        showError(message) {
            $('#demo-import-progress .progress-text').html('<span style="color: red;">' + message + '</span>');
        }

        disableButtons() {
            $('.demo-import-btn, .demo-reset-btn').prop('disabled', true);
        }

        enableButtons() {
            $('.demo-import-btn, .demo-reset-btn').prop('disabled', false);
        }
    }

    // Initialize when ready
    $(document).ready(function() {
        if (typeof aqualuxe_demo_importer !== 'undefined') {
            new DemoImporterModule();
        }
    });

})(jQuery);