/**
 * Demo Importer Admin JavaScript
 * Handles demo content import and reset functionality
 */

(function($) {
    'use strict';

    // Demo Importer Object
    const AquaLuxeDemoImporter = {
        
        // Initialize
        init: function() {
            this.bindEvents();
            this.initProgressTracking();
        },

        // Bind events
        bindEvents: function() {
            $('#import-demo').on('click', this.handleImport.bind(this));
            $('#reset-content').on('click', this.handleReset.bind(this));
            $('#export-content').on('click', this.handleExport.bind(this));
            $('#validate-system').on('click', this.handleValidation.bind(this));
        },

        // Initialize progress tracking
        initProgressTracking: function() {
            this.progressInterval = null;
        },

        // Handle demo import
        handleImport: function(e) {
            e.preventDefault();
            
            const $button = $(e.target);
            const $progress = $('.import-progress');
            
            if ($button.hasClass('importing')) {
                return;
            }

            // Confirm action
            if (!confirm(aqualuxeImporter.strings.confirmImport || 'Are you sure you want to import demo content? This may take a few minutes.')) {
                return;
            }

            // Prepare import data
            const importData = {
                action: 'aqualuxe_import_demo',
                nonce: aqualuxeImporter.nonce,
                import_content: $('#import-content').prop('checked'),
                import_media: $('#import-media').prop('checked'),
                import_customizer: $('#import-customizer').prop('checked'),
                import_widgets: $('#import-widgets').prop('checked')
            };

            // Update UI
            $button.addClass('importing').text(aqualuxeImporter.strings.importing || 'Importing...');
            $progress.show();
            this.updateProgress(0, aqualuxeImporter.strings.starting || 'Starting import...');

            // Start progress tracking
            this.startProgressTracking();

            // Perform import
            $.ajax({
                url: aqualuxeImporter.ajaxurl,
                type: 'POST',
                data: importData,
                timeout: 600000, // 10 minutes
                success: this.handleImportSuccess.bind(this),
                error: this.handleImportError.bind(this),
                complete: function() {
                    $button.removeClass('importing').text('Import Demo Content');
                    this.stopProgressTracking();
                }.bind(this)
            });
        },

        // Handle demo reset
        handleReset: function(e) {
            e.preventDefault();
            
            const $button = $(e.target);
            
            if ($button.hasClass('resetting')) {
                return;
            }

            // Strong confirmation for reset
            const confirmText = 'This will PERMANENTLY DELETE selected content. Type "DELETE" to confirm:';
            const userInput = prompt(confirmText);
            
            if (userInput !== 'DELETE') {
                alert('Reset cancelled. You must type "DELETE" exactly to confirm.');
                return;
            }

            // Prepare reset data
            const resetData = {
                action: 'aqualuxe_reset_content',
                nonce: aqualuxeImporter.nonce,
                reset_posts: $('#reset-posts').prop('checked'),
                reset_media: $('#reset-media').prop('checked'),
                reset_products: $('#reset-products').prop('checked'),
                reset_customizer: $('#reset-customizer').prop('checked')
            };

            // Update UI
            $button.addClass('resetting').text(aqualuxeImporter.strings.resetting || 'Resetting...');

            // Perform reset
            $.ajax({
                url: aqualuxeImporter.ajaxurl,
                type: 'POST',
                data: resetData,
                success: this.handleResetSuccess.bind(this),
                error: this.handleResetError.bind(this),
                complete: function() {
                    $button.removeClass('resetting').text('Reset Content');
                }.bind(this)
            });
        },

        // Start progress tracking
        startProgressTracking: function() {
            this.progressInterval = setInterval(this.checkProgress.bind(this), 2000);
        },

        // Stop progress tracking
        stopProgressTracking: function() {
            if (this.progressInterval) {
                clearInterval(this.progressInterval);
                this.progressInterval = null;
            }
        },

        // Check import progress
        checkProgress: function() {
            $.ajax({
                url: aqualuxeImporter.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_progress',
                    nonce: aqualuxeImporter.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        this.updateProgress(response.data.percentage, response.data.message);
                        
                        // Stop tracking if complete
                        if (response.data.percentage >= 100) {
                            this.stopProgressTracking();
                        }
                    }
                }.bind(this)
            });
        },

        // Update progress display
        updateProgress: function(percentage, message) {
            $('.progress-fill').css('width', percentage + '%');
            $('.progress-text').text(percentage + '%');
            $('.progress-status').text(message || '');
        },

        // Handle import success
        handleImportSuccess: function(response) {
            $('.import-progress').hide();
            
            if (response.success) {
                this.showNotice('success', response.data.message || aqualuxeImporter.strings.success);
                // Refresh page after successful import
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            } else {
                this.showNotice('error', response.data || aqualuxeImporter.strings.error);
            }
        },

        // Handle import error
        handleImportError: function(xhr, status, error) {
            $('.import-progress').hide();
            let errorMessage = aqualuxeImporter.strings.error;
            
            if (xhr.responseJSON && xhr.responseJSON.data) {
                errorMessage = xhr.responseJSON.data;
            } else if (error) {
                errorMessage = 'Import failed: ' + error;
            }
            
            this.showNotice('error', errorMessage);
        },

        // Handle reset success
        handleResetSuccess: function(response) {
            if (response.success) {
                this.showNotice('success', response.data.message || aqualuxeImporter.strings.reset_success);
                // Refresh page after successful reset
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            } else {
                this.showNotice('error', response.data || aqualuxeImporter.strings.reset_error);
            }
        },

        // Handle reset error
        handleResetError: function(xhr, status, error) {
            let errorMessage = aqualuxeImporter.strings.reset_error;
            
            if (xhr.responseJSON && xhr.responseJSON.data) {
                errorMessage = xhr.responseJSON.data;
            } else if (error) {
                errorMessage = 'Reset failed: ' + error;
            }
            
            this.showNotice('error', errorMessage);
        },

        // Handle export
        handleExport: function(e) {
            e.preventDefault();
            
            const $button = $(e.target);
            
            if ($button.hasClass('exporting')) {
                return;
            }

            // Confirm action
            if (!confirm(aqualuxeImporter.strings.confirmExport || 'Export current content for backup?')) {
                return;
            }

            // Prepare export data
            const exportData = {
                action: 'aqualuxe_export_content',
                nonce: aqualuxeImporter.nonce,
                export_posts: $('#export-posts').prop('checked'),
                export_products: $('#export-products').prop('checked'),
                export_media: $('#export-media').prop('checked'),
                export_customizer: $('#export-customizer').prop('checked')
            };

            // Update UI
            $button.addClass('exporting').text(aqualuxeImporter.strings.exporting || 'Exporting...');

            // Perform export
            $.ajax({
                url: aqualuxeImporter.ajaxurl,
                type: 'POST',
                data: exportData,
                success: this.handleExportSuccess.bind(this),
                error: this.handleExportError.bind(this),
                complete: function() {
                    $button.removeClass('exporting').text('Export Content');
                }.bind(this)
            });
        },

        // Handle validation
        handleValidation: function(e) {
            e.preventDefault();
            
            const $button = $(e.target);
            const $results = $('.validation-results');
            
            if ($button.hasClass('validating')) {
                return;
            }

            // Update UI
            $button.addClass('validating').text(aqualuxeImporter.strings.validating || 'Validating...');
            $results.show();

            // Perform validation
            $.ajax({
                url: aqualuxeImporter.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_validate_import',
                    nonce: aqualuxeImporter.nonce
                },
                success: this.handleValidationSuccess.bind(this),
                error: this.handleValidationError.bind(this),
                complete: function() {
                    $button.removeClass('validating').text('Run System Check');
                }.bind(this)
            });
        },

        // Handle export success
        handleExportSuccess: function(response) {
            if (response.success) {
                this.showNotice('success', response.data.message);
                
                // Create download link if file URL provided
                if (response.data.file_url) {
                    const downloadLink = '<p><a href="' + response.data.file_url + '" class="button" download>Download Export File</a></p>';
                    $('.wrap .notice:last').append(downloadLink);
                }
            } else {
                this.showNotice('error', response.data || 'Export failed');
            }
        },

        // Handle export error
        handleExportError: function(xhr, status, error) {
            let errorMessage = 'Export failed';
            
            if (xhr.responseJSON && xhr.responseJSON.data) {
                errorMessage = xhr.responseJSON.data;
            } else if (error) {
                errorMessage = 'Export failed: ' + error;
            }
            
            this.showNotice('error', errorMessage);
        },

        // Handle validation success
        handleValidationSuccess: function(response) {
            if (response.success && response.data.results) {
                this.displayValidationResults(response.data.results);
            } else {
                this.showNotice('error', 'Validation failed');
            }
        },

        // Handle validation error
        handleValidationError: function(xhr, status, error) {
            let errorMessage = 'Validation failed';
            
            if (xhr.responseJSON && xhr.responseJSON.data) {
                errorMessage = xhr.responseJSON.data;
            } else if (error) {
                errorMessage = 'Validation failed: ' + error;
            }
            
            this.showNotice('error', errorMessage);
        },

        // Display validation results
        displayValidationResults: function(results) {
            let output = '<table class="widefat"><thead><tr><th>Check</th><th>Value</th><th>Status</th><th>Message</th></tr></thead><tbody>';
            
            $.each(results, function(key, result) {
                const statusClass = result.status === 'pass' ? 'validation-pass' : 
                                  result.status === 'warning' ? 'validation-warning' : 
                                  result.status === 'fail' ? 'validation-fail' : 'validation-info';
                
                const statusIcon = result.status === 'pass' ? '✓' : 
                                 result.status === 'warning' ? '⚠' : 
                                 result.status === 'fail' ? '✗' : 'ℹ';
                
                output += '<tr>';
                output += '<td>' + result.label + '</td>';
                output += '<td>' + result.value + '</td>';
                output += '<td class="' + statusClass + '">' + statusIcon + ' ' + result.status.toUpperCase() + '</td>';
                output += '<td>' + result.message + '</td>';
                output += '</tr>';
            });
            
            output += '</tbody></table>';
            
            $('#validation-output').html(output);
            
            // Add CSS for validation results
            if (!$('#validation-styles').length) {
                $('head').append(`
                    <style id="validation-styles">
                        .validation-pass { color: #008000; }
                        .validation-warning { color: #ff8800; }
                        .validation-fail { color: #cc0000; }
                        .validation-info { color: #0073aa; }
                        .validation-results table { margin-top: 15px; }
                        .validation-results td { padding: 8px 12px; }
                    </style>
                `);
            }
        },

        // Show admin notice
        showNotice: function(type, message) {
            const noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
            const $notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');
            
            // Remove existing notices
            $('.wrap .notice').remove();
            
            // Add new notice
            $('.wrap h1').after($notice);
            
            // Scroll to top
            $('html, body').animate({ scrollTop: 0 }, 500);
            
            // Auto-dismiss success notices
            if (type === 'success') {
                setTimeout(function() {
                    $notice.fadeOut();
                }, 5000);
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeDemoImporter.init();
    });

})(jQuery);