/**
 * AquaLuxe Demo Importer Scripts
 *
 * JavaScript functionality for the demo importer interface.
 */

(function($) {
    'use strict';

    // Demo Importer Object
    const AquaLuxeDemoImporter = {
        init: function() {
            this.checkPluginStatus();
            this.bindEvents();
        },

        bindEvents: function() {
            // Import demo button click
            $('.aqualuxe-import-demo').on('click', this.showImportOptions.bind(this));
            
            // Start import button click
            $('.aqualuxe-start-import').on('click', this.startImport.bind(this));
            
            // Cancel selective import button click
            $('.aqualuxe-cancel-selective').on('click', this.hideImportOptions.bind(this));
            
            // Cancel import button click
            $('.aqualuxe-cancel-import').on('click', this.cancelImport.bind(this));
            
            // Clear cache button click
            $('.aqualuxe-clear-cache').on('click', this.clearCache.bind(this));
            
            // Modal close button click
            $('.aqualuxe-demo-importer-modal-close').on('click', this.closeModal.bind(this));
        },

        checkPluginStatus: function() {
            $('.aqualuxe-plugin-item').each(function() {
                const $item = $(this);
                const pluginSlug = $item.data('plugin');
                
                $.ajax({
                    url: aqualuxeDemoImporter.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_check_plugin_status',
                        nonce: aqualuxeDemoImporter.nonce,
                        plugin: pluginSlug
                    },
                    success: function(response) {
                        if (response.success) {
                            const status = response.data.status;
                            const plugin = response.data.plugin;
                            
                            // Update status text
                            $item.find('.aqualuxe-plugin-status')
                                .removeClass('active inactive not-installed')
                                .addClass(status);
                            
                            if (status === 'active') {
                                $item.find('.aqualuxe-plugin-status').text('Active');
                                $item.find('.aqualuxe-plugin-actions').empty();
                            } else if (status === 'inactive') {
                                $item.find('.aqualuxe-plugin-status').text('Inactive');
                                $item.find('.aqualuxe-plugin-actions').html(
                                    '<button class="button aqualuxe-activate-plugin" data-plugin="' + pluginSlug + '">Activate</button>'
                                );
                                
                                // Bind activate plugin button click
                                $item.find('.aqualuxe-activate-plugin').on('click', AquaLuxeDemoImporter.activatePlugin.bind(AquaLuxeDemoImporter));
                            } else {
                                $item.find('.aqualuxe-plugin-status').text('Not Installed');
                                $item.find('.aqualuxe-plugin-actions').html(
                                    '<button class="button aqualuxe-install-plugin" data-plugin="' + pluginSlug + '">Install & Activate</button>'
                                );
                                
                                // Bind install plugin button click
                                $item.find('.aqualuxe-install-plugin').on('click', AquaLuxeDemoImporter.installPlugin.bind(AquaLuxeDemoImporter));
                            }
                            
                            // Add required label if plugin is required
                            if (plugin.required) {
                                $item.find('.aqualuxe-plugin-name').append(' <span class="aqualuxe-plugin-required">*</span>');
                            }
                        }
                    },
                    error: function() {
                        $item.find('.aqualuxe-plugin-status').text('Error checking status');
                    }
                });
            });
        },

        installPlugin: function(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const pluginSlug = $button.data('plugin');
            const $item = $button.closest('.aqualuxe-plugin-item');
            
            $button.prop('disabled', true).text(aqualuxeDemoImporter.installingPlugin);
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_install_plugin',
                    nonce: aqualuxeDemoImporter.nonce,
                    plugin: pluginSlug
                },
                success: function(response) {
                    if (response.success) {
                        $item.find('.aqualuxe-plugin-status')
                            .removeClass('not-installed inactive')
                            .addClass('active')
                            .text('Active');
                        
                        $button.remove();
                    } else {
                        $button.prop('disabled', false).text('Install & Activate');
                        alert(response.data.message || aqualuxeDemoImporter.pluginFailed);
                    }
                },
                error: function() {
                    $button.prop('disabled', false).text('Install & Activate');
                    alert(aqualuxeDemoImporter.pluginFailed);
                }
            });
        },

        activatePlugin: function(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const pluginSlug = $button.data('plugin');
            const $item = $button.closest('.aqualuxe-plugin-item');
            
            $button.prop('disabled', true).text(aqualuxeDemoImporter.activatingPlugin);
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_activate_plugin',
                    nonce: aqualuxeDemoImporter.nonce,
                    plugin: pluginSlug
                },
                success: function(response) {
                    if (response.success) {
                        $item.find('.aqualuxe-plugin-status')
                            .removeClass('inactive')
                            .addClass('active')
                            .text('Active');
                        
                        $button.remove();
                    } else {
                        $button.prop('disabled', false).text('Activate');
                        alert(response.data.message || aqualuxeDemoImporter.pluginFailed);
                    }
                },
                error: function() {
                    $button.prop('disabled', false).text('Activate');
                    alert(aqualuxeDemoImporter.pluginFailed);
                }
            });
        },

        showImportOptions: function(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const demoId = $button.data('demo-id');
            
            // Store the selected demo ID
            this.selectedDemoId = demoId;
            
            // Show the modal
            $('.aqualuxe-demo-importer-modal').show();
            
            // Show selective import options
            $('.aqualuxe-demo-importer-modal-selective').show();
            $('.aqualuxe-demo-importer-modal-progress').hide();
            $('.aqualuxe-demo-importer-modal-log').hide();
            $('.aqualuxe-demo-importer-modal-complete').hide();
            
            // Reset form
            $('.aqualuxe-selective-import-form')[0].reset();
        },

        hideImportOptions: function(e) {
            e.preventDefault();
            $('.aqualuxe-demo-importer-modal-selective').hide();
            this.closeModal();
        },

        startImport: function(e) {
            e.preventDefault();
            
            // Confirm import
            if (!confirm(aqualuxeDemoImporter.confirmImport)) {
                return;
            }
            
            // Hide selective import options
            $('.aqualuxe-demo-importer-modal-selective').hide();
            
            // Show progress
            $('.aqualuxe-demo-importer-modal-progress').show();
            $('.aqualuxe-demo-importer-modal-log').show();
            
            // Reset progress
            $('.aqualuxe-demo-importer-progress-bar-inner').css('width', '0%');
            $('.aqualuxe-demo-importer-progress-percentage').text('0%');
            $('.aqualuxe-demo-importer-progress-step').text('Preparing import...');
            $('.aqualuxe-demo-importer-log-content').empty();
            
            // Get selective import options
            const selectiveImport = {
                import_pages: $('input[name="import_pages"]').is(':checked'),
                import_posts: $('input[name="import_posts"]').is(':checked'),
                import_products: $('input[name="import_products"]').is(':checked'),
                import_media: $('input[name="import_media"]').is(':checked'),
                import_menus: $('input[name="import_menus"]').is(':checked'),
                import_widgets: $('input[name="import_widgets"]').is(':checked'),
                import_customizer: $('input[name="import_customizer"]').is(':checked'),
                import_options: $('input[name="import_options"]').is(':checked')
            };
            
            // Start import
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo_data',
                    nonce: aqualuxeDemoImporter.nonce,
                    demo_id: this.selectedDemoId,
                    selective_import: selectiveImport
                },
                success: function(response) {
                    if (response.success) {
                        // Store session ID
                        AquaLuxeDemoImporter.importSessionId = response.data.session_id;
                        
                        // Start checking progress
                        AquaLuxeDemoImporter.checkImportProgress();
                    } else {
                        alert(response.data.message || 'Import failed to start.');
                        AquaLuxeDemoImporter.closeModal();
                    }
                },
                error: function() {
                    alert('Import failed to start due to a server error.');
                    AquaLuxeDemoImporter.closeModal();
                }
            });
        },

        checkImportProgress: function() {
            if (!this.importSessionId) {
                return;
            }
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_progress',
                    nonce: aqualuxeDemoImporter.nonce,
                    session_id: this.importSessionId
                },
                success: function(response) {
                    if (response.success) {
                        const progress = response.data;
                        
                        // Update progress bar
                        $('.aqualuxe-demo-importer-progress-bar-inner').css('width', progress.percentage + '%');
                        $('.aqualuxe-demo-importer-progress-percentage').text(progress.percentage + '%');
                        $('.aqualuxe-demo-importer-progress-step').text(AquaLuxeDemoImporter.getStepLabel(progress.step));
                        
                        // Update log
                        const logContent = progress.log.join('\n');
                        $('.aqualuxe-demo-importer-log-content').html(logContent);
                        $('.aqualuxe-demo-importer-log-content').scrollTop($('.aqualuxe-demo-importer-log-content')[0].scrollHeight);
                        
                        // Check if import is complete
                        if (progress.step === 'complete') {
                            // Show complete message
                            $('.aqualuxe-demo-importer-modal-progress').hide();
                            $('.aqualuxe-demo-importer-modal-complete').show();
                        } else {
                            // Continue checking progress
                            setTimeout(AquaLuxeDemoImporter.checkImportProgress.bind(AquaLuxeDemoImporter), 2000);
                        }
                    } else {
                        alert(response.data.message || 'Failed to check import progress.');
                    }
                },
                error: function() {
                    alert('Failed to check import progress due to a server error.');
                }
            });
        },

        getStepLabel: function(step) {
            const steps = {
                'prepare': 'Preparing import...',
                'content': 'Importing content...',
                'widgets': 'Importing widgets...',
                'customizer': 'Importing customizer settings...',
                'options': 'Importing options...',
                'settings': 'Configuring settings...',
                'complete': 'Import completed!'
            };
            
            return steps[step] || step;
        },

        cancelImport: function(e) {
            e.preventDefault();
            
            if (!this.importSessionId) {
                this.closeModal();
                return;
            }
            
            if (!confirm(aqualuxeDemoImporter.cancelImport)) {
                return;
            }
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_cancel_import',
                    nonce: aqualuxeDemoImporter.nonce,
                    session_id: this.importSessionId
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message || 'Import cancelled.');
                    } else {
                        alert(response.data.message || 'Failed to cancel import.');
                    }
                    
                    AquaLuxeDemoImporter.closeModal();
                },
                error: function() {
                    alert('Failed to cancel import due to a server error.');
                    AquaLuxeDemoImporter.closeModal();
                }
            });
        },

        clearCache: function(e) {
            e.preventDefault();
            
            if (!confirm(aqualuxeDemoImporter.clearCache)) {
                return;
            }
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_clear_import_cache',
                    nonce: aqualuxeDemoImporter.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message || 'Cache cleared successfully.');
                    } else {
                        alert(response.data.message || 'Failed to clear cache.');
                    }
                },
                error: function() {
                    alert('Failed to clear cache due to a server error.');
                }
            });
        },

        closeModal: function(e) {
            if (e) {
                e.preventDefault();
            }
            
            $('.aqualuxe-demo-importer-modal').hide();
            this.selectedDemoId = null;
            this.importSessionId = null;
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeDemoImporter.init();
    });

})(jQuery);