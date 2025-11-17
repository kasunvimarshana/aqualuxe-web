/**
 * Demo Importer JavaScript
 * 
 * @package AquaLuxe
 */

jQuery(document).ready(function($) {
    'use strict';
    
    /**
     * Demo Importer functionality
     */
    const AquaLuxeDemoImporter = {
        
        /**
         * Initialize
         */
        init: function() {
            this.setupImportOptions();
            this.setupImportProcess();
            this.setupResetProcess();
        },
        
        /**
         * Setup import options
         */
        setupImportOptions: function() {
            $('.import-option').on('click', function() {
                const $option = $(this);
                const isSelected = $option.hasClass('selected');
                
                if (isSelected) {
                    $option.removeClass('selected');
                } else {
                    $option.addClass('selected');
                }
                
                // Update import button state
                const hasSelected = $('.import-option.selected').length > 0;
                $('.start-import').prop('disabled', !hasSelected);
            });
        },
        
        /**
         * Setup import process
         */
        setupImportProcess: function() {
            $('.start-import').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const selectedOptions = $('.import-option.selected').map(function() {
                    return $(this).data('import-type');
                }).get();
                
                if (selectedOptions.length === 0) {
                    alert('Please select at least one import option.');
                    return;
                }
                
                // Confirm import
                if (!confirm('This will import demo content and may overwrite existing content. Continue?')) {
                    return;
                }
                
                AquaLuxeDemoImporter.startImport(selectedOptions);
            });
        },
        
        /**
         * Setup reset process
         */
        setupResetProcess: function() {
            $('.reset-data').on('click', function(e) {
                e.preventDefault();
                
                const confirmed = confirm('This will permanently delete all imported demo content and reset your site. This action cannot be undone. Are you sure?');
                
                if (confirmed) {
                    const doubleConfirmed = confirm('Are you absolutely sure? This will delete all posts, pages, products, and media files imported during demo import.');
                    
                    if (doubleConfirmed) {
                        AquaLuxeDemoImporter.startReset();
                    }
                }
            });
        },
        
        /**
         * Start import process
         */
        startImport: function(options) {
            const $progress = $('.import-progress');
            const $progressBar = $('.progress-fill');
            const $progressLabel = $('.progress-label');
            const $progressStatus = $('.progress-status');
            const $log = $('.import-log');
            const $button = $('.start-import');
            
            // Show progress and disable button
            $progress.show();
            $button.prop('disabled', true).text('Importing...');
            
            // Reset progress
            $progressBar.css('width', '0%');
            $progressLabel.find('.progress-current').text('0');
            $progressStatus.text('Preparing import...');
            $log.empty();
            
            // Start import
            this.processImport(options, 0);
        },
        
        /**
         * Process import steps
         */
        processImport: function(options, currentStep) {
            const totalSteps = options.length;
            const currentOption = options[currentStep];
            
            if (currentStep >= totalSteps) {
                this.completeImport();
                return;
            }
            
            const progress = Math.round(((currentStep + 1) / totalSteps) * 100);
            
            // Update progress
            $('.progress-fill').css('width', progress + '%');
            $('.progress-current').text(currentStep + 1);
            $('.progress-total').text(totalSteps);
            $('.progress-status').text(`Importing ${currentOption}...`);
            
            // Add log entry
            this.addLogEntry(`Starting ${currentOption} import...`, 'info');
            
            // Perform AJAX import
            $.post(aqualuxe_admin_vars.ajax_url, {
                action: 'aqualuxe_import_demo_content',
                import_type: currentOption,
                nonce: aqualuxe_admin_vars.nonce
            })
            .done((response) => {
                if (response.success) {
                    this.addLogEntry(`${currentOption} import completed successfully.`, 'success');
                    
                    // Process next step
                    setTimeout(() => {
                        this.processImport(options, currentStep + 1);
                    }, 500);
                } else {
                    this.addLogEntry(`${currentOption} import failed: ${response.data}`, 'error');
                    this.failImport(`Import failed at step: ${currentOption}`);
                }
            })
            .fail(() => {
                this.addLogEntry(`${currentOption} import failed: Network error`, 'error');
                this.failImport('Network error during import.');
            });
        },
        
        /**
         * Complete import
         */
        completeImport: function() {
            $('.progress-status').text('Import completed successfully!');
            $('.start-import').prop('disabled', false).text('Start Import');
            
            this.addLogEntry('Demo import completed successfully!', 'success');
            this.addLogEntry('You can now visit your site to see the imported content.', 'info');
            
            // Show success notice
            this.showNotice('Demo content imported successfully!', 'success');
        },
        
        /**
         * Fail import
         */
        failImport: function(message) {
            $('.progress-status').text('Import failed.');
            $('.start-import').prop('disabled', false).text('Start Import');
            
            this.addLogEntry(message, 'error');
            this.showNotice('Import failed. Please check the log for details.', 'error');
        },
        
        /**
         * Start reset process
         */
        startReset: function() {
            const $button = $('.reset-data');
            $button.prop('disabled', true).text('Resetting...');
            
            this.addLogEntry('Starting data reset...', 'info');
            
            $.post(aqualuxe_admin_vars.ajax_url, {
                action: 'aqualuxe_reset_demo_content',
                nonce: aqualuxe_admin_vars.nonce
            })
            .done((response) => {
                if (response.success) {
                    this.addLogEntry('Data reset completed successfully.', 'success');
                    this.showNotice('All demo content has been removed.', 'success');
                } else {
                    this.addLogEntry(`Reset failed: ${response.data}`, 'error');
                    this.showNotice('Reset failed. Please try again.', 'error');
                }
            })
            .fail(() => {
                this.addLogEntry('Reset failed: Network error', 'error');
                this.showNotice('Network error during reset.', 'error');
            })
            .always(() => {
                $button.prop('disabled', false).text('Reset All Data');
            });
        },
        
        /**
         * Add log entry
         */
        addLogEntry: function(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const $log = $('.import-log');
            
            const logEntry = $(`<div class="log-entry ${type}">[${timestamp}] ${message}</div>`);
            $log.append(logEntry);
            
            // Auto-scroll to bottom
            $log.scrollTop($log[0].scrollHeight);
        },
        
        /**
         * Show notice
         */
        showNotice: function(message, type) {
            const notice = $(`
                <div class="notice notice-${type} is-dismissible">
                    <p>${message}</p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            `);
            
            $('.wrap > h1').after(notice);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                notice.fadeOut(() => notice.remove());
            }, 5000);
            
            // Manual dismiss
            notice.find('.notice-dismiss').on('click', function() {
                notice.fadeOut(() => notice.remove());
            });
        }
    };
    
    // Initialize demo importer
    AquaLuxeDemoImporter.init();
    
    // Make it globally available
    window.AquaLuxeDemoImporter = AquaLuxeDemoImporter;
});