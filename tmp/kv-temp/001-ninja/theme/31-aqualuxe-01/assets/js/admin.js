/**
 * Demo Content Importer Admin Scripts
 */

(function($) {
    'use strict';

    // Global variables
    var DCI = {
        isImporting: false,
        importData: {},
        currentStep: '',
        totalSteps: 0,
        progress: 0,
        logs: []
    };

    // Initialize
    $(document).ready(function() {
        initTabs();
        initDemoImport();
        initBackupRestore();
        initReset();
        initSettings();
    });

    /**
     * Initialize tabs.
     */
    function initTabs() {
        // Show active tab on page load
        var hash = window.location.hash;
        if (hash) {
            showTab(hash.substring(1));
        } else {
            showTab('demos');
        }

        // Tab click event
        $('.dci-admin-tab').on('click', function(e) {
            e.preventDefault();
            var tabId = $(this).attr('href').substring(1);
            showTab(tabId);
            
            // Update URL hash without scrolling
            history.pushState(null, null, '#' + tabId);
        });

        // Handle back/forward browser buttons
        $(window).on('popstate', function() {
            var hash = window.location.hash;
            if (hash) {
                showTab(hash.substring(1));
            } else {
                showTab('demos');
            }
        });
    }

    /**
     * Show tab.
     *
     * @param {string} tabId Tab ID.
     */
    function showTab(tabId) {
        // Hide all tabs
        $('.dci-admin-tab-content').removeClass('active');
        $('.dci-admin-tab').removeClass('active');

        // Show active tab
        $('#' + tabId).addClass('active');
        $('a[href="#' + tabId + '"]').addClass('active');
    }

    /**
     * Initialize demo import.
     */
    function initDemoImport() {
        // Import button click
        $('.dci-import-demo').on('click', function() {
            var demoId = $(this).data('demo-id');
            openImportModal(demoId);
        });

        // Modal close
        $(document).on('click', '.dci-modal-close', function() {
            closeModal();
        });

        // Close modal when clicking outside
        $(document).on('click', '.dci-modal', function(e) {
            if ($(e.target).hasClass('dci-modal')) {
                closeModal();
            }
        });

        // Start import button
        $(document).on('click', '#dci-start-import', function() {
            startImport();
        });

        // Cancel import button
        $(document).on('click', '#dci-cancel-import', function() {
            cancelImport();
        });
    }

    /**
     * Open import modal.
     *
     * @param {string} demoId Demo ID.
     */
    function openImportModal(demoId) {
        // Get demo data
        var $demoItem = $('.dci-demo-item[data-demo-id="' + demoId + '"]');
        var demoName = $demoItem.find('.dci-demo-name').text();
        var demoDescription = $demoItem.find('.dci-demo-description').text();
        var demoScreenshot = $demoItem.find('.dci-demo-screenshot img').attr('src');

        // Set modal content
        var modalContent = `
            <div class="dci-modal-content">
                <span class="dci-modal-close">&times;</span>
                <h2>${demoName}</h2>
                
                <div class="dci-import-preview">
                    <img src="${demoScreenshot}" alt="${demoName}" style="max-width: 100%; height: auto; margin-bottom: 20px;">
                    <p>${demoDescription}</p>
                </div>
                
                <div class="dci-import-options">
                    <h3>Import Options</h3>
                    
                    <div class="dci-import-option">
                        <div class="dci-import-option-header">
                            <input type="checkbox" id="dci-import-content" checked>
                            <label for="dci-import-content" class="dci-import-option-title">Content</label>
                        </div>
                        <p class="dci-import-option-description">Import posts, pages, and custom post types.</p>
                    </div>
                    
                    <div class="dci-import-option">
                        <div class="dci-import-option-header">
                            <input type="checkbox" id="dci-import-media" checked>
                            <label for="dci-import-media" class="dci-import-option-title">Media</label>
                        </div>
                        <p class="dci-import-option-description">Import images and other media files.</p>
                    </div>
                    
                    <div class="dci-import-option">
                        <div class="dci-import-option-header">
                            <input type="checkbox" id="dci-import-widgets" checked>
                            <label for="dci-import-widgets" class="dci-import-option-title">Widgets</label>
                        </div>
                        <p class="dci-import-option-description">Import widget configurations.</p>
                    </div>
                    
                    <div class="dci-import-option">
                        <div class="dci-import-option-header">
                            <input type="checkbox" id="dci-import-customizer" checked>
                            <label for="dci-import-customizer" class="dci-import-option-title">Customizer Settings</label>
                        </div>
                        <p class="dci-import-option-description">Import theme customizer settings.</p>
                    </div>
                    
                    <div class="dci-import-option">
                        <div class="dci-import-option-header">
                            <input type="checkbox" id="dci-import-options" checked>
                            <label for="dci-import-options" class="dci-import-option-title">Theme Options</label>
                        </div>
                        <p class="dci-import-option-description">Import theme options and settings.</p>
                    </div>
                    
                    <div class="dci-import-option">
                        <div class="dci-import-option-header">
                            <input type="checkbox" id="dci-import-menus" checked>
                            <label for="dci-import-menus" class="dci-import-option-title">Menus</label>
                        </div>
                        <p class="dci-import-option-description">Set up navigation menus.</p>
                    </div>
                    
                    <div class="dci-import-option">
                        <div class="dci-import-option-header">
                            <input type="checkbox" id="dci-import-plugins" checked>
                            <label for="dci-import-plugins" class="dci-import-option-title">Plugins</label>
                        </div>
                        <p class="dci-import-option-description">Install and activate required plugins.</p>
                    </div>
                    
                    <div class="dci-import-option">
                        <div class="dci-import-option-header">
                            <input type="checkbox" id="dci-import-replace">
                            <label for="dci-import-replace" class="dci-import-option-title">Replace Existing Content</label>
                        </div>
                        <p class="dci-import-option-description">Replace existing content with demo content. <strong>Warning:</strong> This will delete your existing content.</p>
                    </div>
                </div>
                
                <div class="dci-modal-actions" style="margin-top: 20px; text-align: right;">
                    <button id="dci-cancel-import" class="button">Cancel</button>
                    <button id="dci-start-import" class="button button-primary" data-demo-id="${demoId}">Start Import</button>
                </div>
            </div>
        `;

        // Show modal
        $('#dci-import-modal').html(modalContent).show();
    }

    /**
     * Close modal.
     */
    function closeModal() {
        $('.dci-modal').hide();
        
        // If importing, confirm before closing
        if (DCI.isImporting) {
            if (confirm('Import is in progress. Are you sure you want to cancel?')) {
                cancelImport();
            } else {
                $('#dci-import-modal').show();
            }
        }
    }

    /**
     * Start import.
     */
    function startImport() {
        // Get demo ID
        var demoId = $('#dci-start-import').data('demo-id');
        
        // Get import options
        var importOptions = {
            content: $('#dci-import-content').is(':checked'),
            media: $('#dci-import-media').is(':checked'),
            widgets: $('#dci-import-widgets').is(':checked'),
            customizer: $('#dci-import-customizer').is(':checked'),
            options: $('#dci-import-options').is(':checked'),
            menus: $('#dci-import-menus').is(':checked'),
            plugins: $('#dci-import-plugins').is(':checked'),
            replace: $('#dci-import-replace').is(':checked')
        };
        
        // Set import data
        DCI.importData = {
            demoId: demoId,
            options: importOptions
        };
        
        // Update modal content
        updateModalForImport();
        
        // Start import process
        DCI.isImporting = true;
        DCI.currentStep = 'preparing';
        DCI.totalSteps = calculateTotalSteps(importOptions);
        DCI.progress = 0;
        DCI.logs = [];
        
        // Add initial log
        addLog('Starting import process for demo: ' + demoId, 'info');
        
        // Run import steps
        runImportStep();
    }

    /**
     * Update modal for import.
     */
    function updateModalForImport() {
        var modalContent = `
            <div class="dci-modal-content">
                <h2>Importing Demo Content</h2>
                
                <div class="dci-progress">
                    <div class="dci-progress-bar">
                        <div class="dci-progress-bar-inner" style="width: 0%"></div>
                    </div>
                    <div class="dci-progress-status">
                        <span class="dci-progress-step">Preparing...</span>
                        <span class="dci-progress-percentage">0%</span>
                    </div>
                </div>
                
                <div class="dci-logs"></div>
                
                <div class="dci-modal-actions" style="margin-top: 20px; text-align: right;">
                    <button id="dci-cancel-import" class="button">Cancel</button>
                </div>
            </div>
        `;
        
        // Update modal content
        $('#dci-import-modal').html(modalContent);
    }

    /**
     * Calculate total import steps.
     *
     * @param {Object} options Import options.
     * @return {number} Total steps.
     */
    function calculateTotalSteps(options) {
        var steps = 1; // Preparing step
        
        if (options.plugins) steps++;
        if (options.content) steps++;
        if (options.media) steps++;
        if (options.widgets) steps++;
        if (options.customizer) steps++;
        if (options.options) steps++;
        if (options.menus) steps++;
        
        steps++; // Finalizing step
        
        return steps;
    }

    /**
     * Run import step.
     */
    function runImportStep() {
        // Update progress
        updateProgress();
        
        // Simulate import steps (in a real implementation, these would be AJAX calls)
        setTimeout(function() {
            switch (DCI.currentStep) {
                case 'preparing':
                    addLog('Preparing for import...', 'info');
                    if (DCI.importData.options.replace) {
                        addLog('Backup will be created before replacing content', 'info');
                    }
                    DCI.currentStep = DCI.importData.options.plugins ? 'plugins' : nextStep('plugins');
                    runImportStep();
                    break;
                    
                case 'plugins':
                    addLog('Installing and activating required plugins...', 'info');
                    // Simulate plugin installation
                    setTimeout(function() {
                        addLog('Installed and activated WooCommerce', 'success');
                        addLog('Installed and activated Contact Form 7', 'success');
                        DCI.currentStep = DCI.importData.options.content ? 'content' : nextStep('content');
                        runImportStep();
                    }, 2000);
                    break;
                    
                case 'content':
                    addLog('Importing content...', 'info');
                    // Simulate content import
                    setTimeout(function() {
                        addLog('Imported 5 pages', 'success');
                        addLog('Imported 10 posts', 'success');
                        addLog('Imported 3 custom post types', 'success');
                        DCI.currentStep = DCI.importData.options.media ? 'media' : nextStep('media');
                        runImportStep();
                    }, 3000);
                    break;
                    
                case 'media':
                    addLog('Importing media...', 'info');
                    // Simulate media import
                    setTimeout(function() {
                        addLog('Imported 25 images', 'success');
                        addLog('Imported 2 videos', 'success');
                        DCI.currentStep = DCI.importData.options.widgets ? 'widgets' : nextStep('widgets');
                        runImportStep();
                    }, 2500);
                    break;
                    
                case 'widgets':
                    addLog('Importing widgets...', 'info');
                    // Simulate widget import
                    setTimeout(function() {
                        addLog('Imported widgets for 5 widget areas', 'success');
                        DCI.currentStep = DCI.importData.options.customizer ? 'customizer' : nextStep('customizer');
                        runImportStep();
                    }, 1500);
                    break;
                    
                case 'customizer':
                    addLog('Importing customizer settings...', 'info');
                    // Simulate customizer import
                    setTimeout(function() {
                        addLog('Imported customizer settings', 'success');
                        DCI.currentStep = DCI.importData.options.options ? 'options' : nextStep('options');
                        runImportStep();
                    }, 1500);
                    break;
                    
                case 'options':
                    addLog('Importing theme options...', 'info');
                    // Simulate options import
                    setTimeout(function() {
                        addLog('Imported theme options', 'success');
                        DCI.currentStep = DCI.importData.options.menus ? 'menus' : nextStep('menus');
                        runImportStep();
                    }, 1500);
                    break;
                    
                case 'menus':
                    addLog('Setting up menus...', 'info');
                    // Simulate menu setup
                    setTimeout(function() {
                        addLog('Set up 2 menus', 'success');
                        addLog('Assigned menus to 2 locations', 'success');
                        DCI.currentStep = 'finalizing';
                        runImportStep();
                    }, 1500);
                    break;
                    
                case 'finalizing':
                    addLog('Finalizing import...', 'info');
                    // Simulate finalization
                    setTimeout(function() {
                        addLog('Clearing cache', 'info');
                        addLog('Regenerating thumbnails', 'info');
                        addLog('Import completed successfully!', 'success');
                        completeImport();
                    }, 2000);
                    break;
            }
        }, 500);
    }

    /**
     * Get next import step.
     *
     * @param {string} currentStep Current step.
     * @return {string} Next step.
     */
    function nextStep(currentStep) {
        var steps = ['plugins', 'content', 'media', 'widgets', 'customizer', 'options', 'menus', 'finalizing'];
        var currentIndex = steps.indexOf(currentStep);
        
        for (var i = currentIndex + 1; i < steps.length; i++) {
            var step = steps[i];
            if (step === 'finalizing' || DCI.importData.options[step]) {
                return step;
            }
        }
        
        return 'finalizing';
    }

    /**
     * Update import progress.
     */
    function updateProgress() {
        // Calculate progress percentage
        var stepsCompleted = 0;
        var steps = ['preparing', 'plugins', 'content', 'media', 'widgets', 'customizer', 'options', 'menus', 'finalizing'];
        var currentIndex = steps.indexOf(DCI.currentStep);
        
        for (var i = 0; i < currentIndex; i++) {
            var step = steps[i];
            if (step === 'preparing' || step === 'finalizing' || DCI.importData.options[step]) {
                stepsCompleted++;
            }
        }
        
        DCI.progress = Math.round((stepsCompleted / DCI.totalSteps) * 100);
        
        // Update progress bar
        $('.dci-progress-bar-inner').css('width', DCI.progress + '%');
        $('.dci-progress-percentage').text(DCI.progress + '%');
        
        // Update step text
        var stepText = DCI.currentStep.charAt(0).toUpperCase() + DCI.currentStep.slice(1);
        $('.dci-progress-step').text(stepText + '...');
    }

    /**
     * Add log entry.
     *
     * @param {string} message Log message.
     * @param {string} type Log type (info, success, warning, error).
     */
    function addLog(message, type) {
        // Add to logs array
        DCI.logs.push({
            message: message,
            type: type,
            time: new Date().toLocaleTimeString()
        });
        
        // Update logs display
        var $logs = $('.dci-logs');
        var logHtml = '<div class="dci-log-entry ' + type + '">' +
            '<span class="dci-log-time">[' + DCI.logs[DCI.logs.length - 1].time + ']</span> ' +
            message +
            '</div>';
        
        $logs.append(logHtml);
        $logs.scrollTop($logs[0].scrollHeight);
    }

    /**
     * Complete import.
     */
    function completeImport() {
        // Update progress to 100%
        DCI.progress = 100;
        $('.dci-progress-bar-inner').css('width', '100%');
        $('.dci-progress-percentage').text('100%');
        $('.dci-progress-step').text('Completed');
        
        // Update modal
        var modalContent = `
            <div class="dci-modal-content">
                <h2>Import Completed</h2>
                
                <div class="dci-notice dci-notice-success">
                    <p>Demo content has been imported successfully!</p>
                </div>
                
                <div class="dci-import-summary">
                    <h3>Import Summary</h3>
                    <div class="dci-logs" style="max-height: 300px;"></div>
                </div>
                
                <div class="dci-modal-actions" style="margin-top: 20px; text-align: right;">
                    <a href="${window.location.href}" class="button">Close</a>
                    <a href="${window.location.origin}" class="button button-primary" target="_blank">View Site</a>
                </div>
            </div>
        `;
        
        // Update modal content
        $('#dci-import-modal').html(modalContent);
        
        // Add logs to summary
        var $logs = $('.dci-logs');
        for (var i = 0; i < DCI.logs.length; i++) {
            var log = DCI.logs[i];
            var logHtml = '<div class="dci-log-entry ' + log.type + '">' +
                '<span class="dci-log-time">[' + log.time + ']</span> ' +
                log.message +
                '</div>';
            
            $logs.append(logHtml);
        }
        
        // Reset import state
        DCI.isImporting = false;
    }

    /**
     * Cancel import.
     */
    function cancelImport() {
        if (DCI.isImporting) {
            // Add cancellation log
            addLog('Import cancelled by user', 'warning');
            
            // Reset import state
            DCI.isImporting = false;
        }
        
        // Close modal
        $('.dci-modal').hide();
    }

    /**
     * Initialize backup and restore.
     */
    function initBackupRestore() {
        // Create backup button
        $(document).on('click', '#dci-create-backup', function() {
            createBackup();
        });
        
        // Restore backup button
        $(document).on('click', '.dci-restore-backup', function() {
            var backupId = $(this).data('backup-id');
            restoreBackup(backupId);
        });
        
        // Download backup button
        $(document).on('click', '.dci-download-backup', function() {
            var backupId = $(this).data('backup-id');
            downloadBackup(backupId);
        });
        
        // Delete backup button
        $(document).on('click', '.dci-delete-backup', function() {
            var backupId = $(this).data('backup-id');
            deleteBackup(backupId);
        });
    }

    /**
     * Create backup.
     */
    function createBackup() {
        // Get backup options
        var backupOptions = {
            database: $('#dci-backup-database').is(':checked'),
            files: $('#dci-backup-files').is(':checked'),
            name: $('#dci-backup-name').val()
        };
        
        // Validate options
        if (!backupOptions.database && !backupOptions.files) {
            alert('Please select at least one backup option (Database or Files).');
            return;
        }
        
        // Show backup progress
        var $backupOptions = $('.dci-backup-options');
        $backupOptions.html(`
            <h3>Creating Backup</h3>
            
            <div class="dci-progress">
                <div class="dci-progress-bar">
                    <div class="dci-progress-bar-inner" style="width: 0%"></div>
                </div>
                <div class="dci-progress-status">
                    <span class="dci-progress-step">Preparing...</span>
                    <span class="dci-progress-percentage">0%</span>
                </div>
            </div>
            
            <div class="dci-logs" style="margin-top: 20px;"></div>
        `);
        
        // Simulate backup process
        var progress = 0;
        var backupInterval = setInterval(function() {
            progress += 10;
            
            // Update progress bar
            $('.dci-progress-bar-inner').css('width', progress + '%');
            $('.dci-progress-percentage').text(progress + '%');
            
            // Add log entry
            if (progress === 10) {
                addBackupLog('Starting backup...', 'info');
            } else if (progress === 30) {
                addBackupLog('Backing up database...', 'info');
            } else if (progress === 50) {
                addBackupLog('Database backup completed', 'success');
            } else if (progress === 60) {
                addBackupLog('Backing up files...', 'info');
            } else if (progress === 90) {
                addBackupLog('Files backup completed', 'success');
            } else if (progress >= 100) {
                clearInterval(backupInterval);
                addBackupLog('Backup completed successfully!', 'success');
                
                // Show completion message
                setTimeout(function() {
                    // Reload page to show new backup
                    window.location.href = window.location.href.split('#')[0] + '#backup';
                    window.location.reload();
                }, 1500);
            }
        }, 500);
    }

    /**
     * Add backup log entry.
     *
     * @param {string} message Log message.
     * @param {string} type Log type (info, success, warning, error).
     */
    function addBackupLog(message, type) {
        var $logs = $('.dci-logs');
        var logHtml = '<div class="dci-log-entry ' + type + '">' +
            '<span class="dci-log-time">[' + new Date().toLocaleTimeString() + ']</span> ' +
            message +
            '</div>';
        
        $logs.append(logHtml);
        $logs.scrollTop($logs[0].scrollHeight);
    }

    /**
     * Restore backup.
     *
     * @param {string} backupId Backup ID.
     */
    function restoreBackup(backupId) {
        // Confirm restore
        if (!confirm('Are you sure you want to restore this backup? This will replace your current content.')) {
            return;
        }
        
        // Show restore progress
        var $restoreOptions = $('.dci-restore-options');
        $restoreOptions.html(`
            <h3>Restoring Backup</h3>
            
            <div class="dci-progress">
                <div class="dci-progress-bar">
                    <div class="dci-progress-bar-inner" style="width: 0%"></div>
                </div>
                <div class="dci-progress-status">
                    <span class="dci-progress-step">Preparing...</span>
                    <span class="dci-progress-percentage">0%</span>
                </div>
            </div>
            
            <div class="dci-logs" style="margin-top: 20px;"></div>
        `);
        
        // Simulate restore process
        var progress = 0;
        var restoreInterval = setInterval(function() {
            progress += 10;
            
            // Update progress bar
            $('.dci-progress-bar-inner').css('width', progress + '%');
            $('.dci-progress-percentage').text(progress + '%');
            
            // Add log entry
            if (progress === 10) {
                addBackupLog('Starting restore...', 'info');
            } else if (progress === 30) {
                addBackupLog('Restoring database...', 'info');
            } else if (progress === 50) {
                addBackupLog('Database restore completed', 'success');
            } else if (progress === 60) {
                addBackupLog('Restoring files...', 'info');
            } else if (progress === 90) {
                addBackupLog('Files restore completed', 'success');
            } else if (progress >= 100) {
                clearInterval(restoreInterval);
                addBackupLog('Restore completed successfully!', 'success');
                
                // Show completion message
                setTimeout(function() {
                    // Reload page
                    window.location.href = window.location.href.split('#')[0] + '#backup';
                    window.location.reload();
                }, 1500);
            }
        }, 500);
    }

    /**
     * Download backup.
     *
     * @param {string} backupId Backup ID.
     */
    function downloadBackup(backupId) {
        // In a real implementation, this would trigger a download
        alert('Downloading backup: ' + backupId);
    }

    /**
     * Delete backup.
     *
     * @param {string} backupId Backup ID.
     */
    function deleteBackup(backupId) {
        // Confirm delete
        if (!confirm('Are you sure you want to delete this backup?')) {
            return;
        }
        
        // In a real implementation, this would delete the backup via AJAX
        // For now, just remove the element from the DOM
        $('.dci-backup-item[data-backup-id="' + backupId + '"]').fadeOut(300, function() {
            $(this).remove();
        });
    }

    /**
     * Initialize reset.
     */
    function initReset() {
        // Reset site button
        $(document).on('click', '#dci-reset-site', function() {
            resetSite();
        });
    }

    /**
     * Reset site.
     */
    function resetSite() {
        // Get reset options
        var resetOptions = {
            content: $('#dci-reset-content').is(':checked'),
            media: $('#dci-reset-media').is(':checked'),
            widgets: $('#dci-reset-widgets').is(':checked'),
            customizer: $('#dci-reset-customizer').is(':checked'),
            options: $('#dci-reset-options').is(':checked'),
            menus: $('#dci-reset-menus').is(':checked'),
            plugins: $('#dci-reset-plugins').is(':checked')
        };
        
        // Validate options
        var hasOption = false;
        for (var key in resetOptions) {
            if (resetOptions[key]) {
                hasOption = true;
                break;
            }
        }
        
        if (!hasOption) {
            alert('Please select at least one reset option.');
            return;
        }
        
        // Confirm reset
        if (!confirm('Are you sure you want to reset your site? This will delete selected content and cannot be undone.')) {
            return;
        }
        
        // Double confirm
        if (!confirm('This is your last chance to cancel. Are you REALLY sure you want to reset your site?')) {
            return;
        }
        
        // Show reset progress
        var $resetOptions = $('.dci-reset-options');
        $resetOptions.html(`
            <h3>Resetting Site</h3>
            
            <div class="dci-progress">
                <div class="dci-progress-bar">
                    <div class="dci-progress-bar-inner" style="width: 0%"></div>
                </div>
                <div class="dci-progress-status">
                    <span class="dci-progress-step">Preparing...</span>
                    <span class="dci-progress-percentage">0%</span>
                </div>
            </div>
            
            <div class="dci-logs" style="margin-top: 20px;"></div>
        `);
        
        // Simulate reset process
        var progress = 0;
        var resetInterval = setInterval(function() {
            progress += 10;
            
            // Update progress bar
            $('.dci-progress-bar-inner').css('width', progress + '%');
            $('.dci-progress-percentage').text(progress + '%');
            
            // Add log entry
            if (progress === 10) {
                addBackupLog('Starting reset...', 'info');
            } else if (progress === 20 && resetOptions.content) {
                addBackupLog('Resetting content...', 'info');
            } else if (progress === 30 && resetOptions.media) {
                addBackupLog('Resetting media...', 'info');
            } else if (progress === 40 && resetOptions.widgets) {
                addBackupLog('Resetting widgets...', 'info');
            } else if (progress === 50 && resetOptions.customizer) {
                addBackupLog('Resetting customizer settings...', 'info');
            } else if (progress === 60 && resetOptions.options) {
                addBackupLog('Resetting theme options...', 'info');
            } else if (progress === 70 && resetOptions.menus) {
                addBackupLog('Resetting menus...', 'info');
            } else if (progress === 80 && resetOptions.plugins) {
                addBackupLog('Resetting plugins...', 'info');
            } else if (progress === 90) {
                addBackupLog('Cleaning up...', 'info');
            } else if (progress >= 100) {
                clearInterval(resetInterval);
                addBackupLog('Reset completed successfully!', 'success');
                
                // Show completion message
                setTimeout(function() {
                    // Reload page
                    window.location.href = window.location.href.split('#')[0] + '#reset';
                    window.location.reload();
                }, 1500);
            }
        }, 500);
    }

    /**
     * Initialize settings.
     */
    function initSettings() {
        // Save settings button
        $(document).on('click', '#dci-save-settings', function() {
            saveSettings();
        });
    }

    /**
     * Save settings.
     */
    function saveSettings() {
        // Get settings
        var settings = {
            importTimeout: $('#dci-setting-import-timeout').val(),
            backupDir: $('#dci-setting-backup-dir').val(),
            logLevel: $('#dci-setting-log-level').val(),
            mediaImport: $('#dci-setting-media-import').val()
        };
        
        // Show saving message
        var $saveButton = $('#dci-save-settings');
        var originalText = $saveButton.text();
        $saveButton.text('Saving...').prop('disabled', true);
        
        // Simulate saving settings
        setTimeout(function() {
            // Show success message
            $('.dci-settings-form').prepend(`
                <div class="dci-notice dci-notice-success" style="display: none;">
                    <p>Settings saved successfully!</p>
                </div>
            `);
            
            $('.dci-notice').fadeIn(300);
            
            // Reset button
            $saveButton.text(originalText).prop('disabled', false);
            
            // Hide message after 3 seconds
            setTimeout(function() {
                $('.dci-notice').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }, 1000);
    }

})(jQuery);