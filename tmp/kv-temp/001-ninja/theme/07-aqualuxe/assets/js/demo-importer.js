/**
 * AquaLuxe Demo Importer Scripts
 */
(function($) {
    'use strict';

    // Demo Importer Object
    var AquaLuxeDemoImporter = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            // Import demo button click
            $('.aqualuxe-import-demo').on('click', this.openImportModal);
            
            // Close modal
            $('.aqualuxe-demo-importer-modal-close, .aqualuxe-demo-importer-modal-close-btn').on('click', this.closeImportModal);
            
            // Install plugins button click
            $('.aqualuxe-install-plugins').on('click', this.installPlugins);
            
            // Retry import button click
            $('.aqualuxe-retry-import').on('click', this.retryImport);
            
            // Close modal when clicking outside
            $(window).on('click', function(e) {
                if ($(e.target).hasClass('aqualuxe-demo-importer-modal')) {
                    AquaLuxeDemoImporter.closeImportModal();
                }
            });
        },

        openImportModal: function() {
            var demoId = $(this).closest('.aqualuxe-demo-importer-item').data('demo-id');
            
            // Store the demo ID
            AquaLuxeDemoImporter.currentDemoId = demoId;
            
            // Reset modal state
            AquaLuxeDemoImporter.resetModalState();
            
            // Show modal
            $('.aqualuxe-demo-importer-modal').css('display', 'block');
            
            // Check required plugins
            AquaLuxeDemoImporter.checkRequiredPlugins();
        },

        closeImportModal: function() {
            $('.aqualuxe-demo-importer-modal').css('display', 'none');
        },

        resetModalState: function() {
            // Hide all sections
            $('.aqualuxe-demo-importer-modal-plugins').show();
            $('.aqualuxe-demo-importer-modal-progress').hide();
            $('.aqualuxe-demo-importer-modal-complete').hide();
            $('.aqualuxe-demo-importer-modal-complete-success').hide();
            $('.aqualuxe-demo-importer-modal-complete-error').hide();
            
            // Reset progress bar
            $('.aqualuxe-demo-importer-progress-bar-inner').css('width', '0%');
            $('.aqualuxe-demo-importer-progress-percentage').text('0%');
            $('.aqualuxe-demo-importer-progress-step').text(aqualuxeDemoImporter.importing);
            
            // Clear plugin list
            $('.aqualuxe-demo-importer-plugin-list').empty();
            
            // Enable buttons
            $('.aqualuxe-install-plugins').prop('disabled', false).text(aqualuxeDemoImporter.installPlugins);
        },

        checkRequiredPlugins: function() {
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    nonce: aqualuxeDemoImporter.nonce,
                    demo_id: AquaLuxeDemoImporter.currentDemoId,
                    step: 'check_plugins'
                },
                beforeSend: function() {
                    // Show loading state
                    $('.aqualuxe-demo-importer-plugin-list').html('<li class="aqualuxe-demo-importer-loading">Checking required plugins...</li>');
                },
                success: function(response) {
                    if (response.success) {
                        // Clear plugin list
                        $('.aqualuxe-demo-importer-plugin-list').empty();
                        
                        // Check if there are any plugins to install
                        var plugins = response.data.plugins;
                        var allPluginsActive = true;
                        
                        if (plugins.length > 0) {
                            // Add plugins to the list
                            $.each(plugins, function(index, plugin) {
                                var statusClass = plugin.is_active ? 'active' : (plugin.is_installed ? 'installed' : 'not-installed');
                                var statusText = plugin.is_active ? 'Active' : (plugin.is_installed ? 'Installed' : 'Not Installed');
                                
                                $('.aqualuxe-demo-importer-plugin-list').append(
                                    '<li data-plugin-slug="' + plugin.slug + '">' +
                                    '<span class="aqualuxe-demo-importer-plugin-name">' + plugin.name + '</span>' +
                                    '<span class="aqualuxe-demo-importer-plugin-status ' + statusClass + '">' + statusText + '</span>' +
                                    '</li>'
                                );
                                
                                if (!plugin.is_active) {
                                    allPluginsActive = false;
                                }
                            });
                            
                            // If all plugins are active, hide the install button
                            if (allPluginsActive) {
                                $('.aqualuxe-install-plugins').hide();
                                AquaLuxeDemoImporter.startImport();
                            } else {
                                $('.aqualuxe-install-plugins').show();
                            }
                        } else {
                            // No plugins required, start import
                            $('.aqualuxe-demo-importer-modal-plugins').hide();
                            AquaLuxeDemoImporter.startImport();
                        }
                    } else {
                        // Show error message
                        AquaLuxeDemoImporter.showError(response.data.message);
                    }
                },
                error: function() {
                    // Show error message
                    AquaLuxeDemoImporter.showError(aqualuxeDemoImporter.importError);
                }
            });
        },

        installPlugins: function() {
            // Disable button
            $(this).prop('disabled', true).text(aqualuxeDemoImporter.installingPlugins);
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    nonce: aqualuxeDemoImporter.nonce,
                    demo_id: AquaLuxeDemoImporter.currentDemoId,
                    step: 'install_plugins'
                },
                success: function(response) {
                    if (response.success) {
                        // Update plugin status
                        $.each(response.data.installed_plugins, function(index, pluginName) {
                            $('.aqualuxe-demo-importer-plugin-list li').each(function() {
                                if ($(this).find('.aqualuxe-demo-importer-plugin-name').text() === pluginName) {
                                    $(this).find('.aqualuxe-demo-importer-plugin-status')
                                        .removeClass('not-installed installed')
                                        .addClass('active')
                                        .text('Active');
                                }
                            });
                        });
                        
                        // Start import
                        AquaLuxeDemoImporter.startImport();
                    } else {
                        // Show error message
                        AquaLuxeDemoImporter.showError(response.data.message);
                    }
                },
                error: function() {
                    // Show error message
                    AquaLuxeDemoImporter.showError(aqualuxeDemoImporter.importError);
                }
            });
        },

        startImport: function() {
            // Hide plugins section
            $('.aqualuxe-demo-importer-modal-plugins').hide();
            
            // Show progress section
            $('.aqualuxe-demo-importer-modal-progress').show();
            
            // Start import process
            AquaLuxeDemoImporter.importContent();
        },

        importContent: function() {
            // Update progress
            AquaLuxeDemoImporter.updateProgress(20, aqualuxeDemoImporter.importing + ' ' + aqualuxeDemoImporter.importingContent);
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    nonce: aqualuxeDemoImporter.nonce,
                    demo_id: AquaLuxeDemoImporter.currentDemoId,
                    step: 'import_content'
                },
                success: function(response) {
                    if (response.success) {
                        // Import widgets
                        AquaLuxeDemoImporter.importWidgets();
                    } else {
                        // Show error message
                        AquaLuxeDemoImporter.showError(response.data.message);
                    }
                },
                error: function() {
                    // Show error message
                    AquaLuxeDemoImporter.showError(aqualuxeDemoImporter.importError);
                }
            });
        },

        importWidgets: function() {
            // Update progress
            AquaLuxeDemoImporter.updateProgress(40, aqualuxeDemoImporter.importing + ' ' + aqualuxeDemoImporter.importingWidgets);
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    nonce: aqualuxeDemoImporter.nonce,
                    demo_id: AquaLuxeDemoImporter.currentDemoId,
                    step: 'import_widgets'
                },
                success: function(response) {
                    if (response.success) {
                        // Import customizer
                        AquaLuxeDemoImporter.importCustomizer();
                    } else {
                        // Show error message
                        AquaLuxeDemoImporter.showError(response.data.message);
                    }
                },
                error: function() {
                    // Show error message
                    AquaLuxeDemoImporter.showError(aqualuxeDemoImporter.importError);
                }
            });
        },

        importCustomizer: function() {
            // Update progress
            AquaLuxeDemoImporter.updateProgress(60, aqualuxeDemoImporter.importing + ' ' + aqualuxeDemoImporter.importingCustomizer);
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    nonce: aqualuxeDemoImporter.nonce,
                    demo_id: AquaLuxeDemoImporter.currentDemoId,
                    step: 'import_customizer'
                },
                success: function(response) {
                    if (response.success) {
                        // Setup pages
                        AquaLuxeDemoImporter.setupPages();
                    } else {
                        // Show error message
                        AquaLuxeDemoImporter.showError(response.data.message);
                    }
                },
                error: function() {
                    // Show error message
                    AquaLuxeDemoImporter.showError(aqualuxeDemoImporter.importError);
                }
            });
        },

        setupPages: function() {
            // Update progress
            AquaLuxeDemoImporter.updateProgress(80, aqualuxeDemoImporter.importing + ' ' + aqualuxeDemoImporter.settingUpPages);
            
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    nonce: aqualuxeDemoImporter.nonce,
                    demo_id: AquaLuxeDemoImporter.currentDemoId,
                    step: 'setup_pages'
                },
                success: function(response) {
                    if (response.success) {
                        // Update progress
                        AquaLuxeDemoImporter.updateProgress(100, aqualuxeDemoImporter.importSuccess);
                        
                        // Show success message
                        setTimeout(function() {
                            AquaLuxeDemoImporter.showSuccess();
                            
                            // Redirect if specified
                            if (response.data.redirect) {
                                setTimeout(function() {
                                    window.location.href = response.data.redirect;
                                }, 2000);
                            }
                        }, 500);
                    } else {
                        // Show error message
                        AquaLuxeDemoImporter.showError(response.data.message);
                    }
                },
                error: function() {
                    // Show error message
                    AquaLuxeDemoImporter.showError(aqualuxeDemoImporter.importError);
                }
            });
        },

        updateProgress: function(percentage, step) {
            $('.aqualuxe-demo-importer-progress-bar-inner').css('width', percentage + '%');
            $('.aqualuxe-demo-importer-progress-percentage').text(percentage + '%');
            $('.aqualuxe-demo-importer-progress-step').text(step);
        },

        showSuccess: function() {
            // Hide progress section
            $('.aqualuxe-demo-importer-modal-progress').hide();
            
            // Show complete section
            $('.aqualuxe-demo-importer-modal-complete').show();
            $('.aqualuxe-demo-importer-modal-complete-success').show();
        },

        showError: function(message) {
            // Hide progress section
            $('.aqualuxe-demo-importer-modal-progress').hide();
            
            // Show error section
            $('.aqualuxe-demo-importer-modal-complete').show();
            $('.aqualuxe-demo-importer-modal-complete-error').show();
            $('.aqualuxe-demo-importer-error-message').text(message);
        },

        retryImport: function() {
            // Hide complete section
            $('.aqualuxe-demo-importer-modal-complete').hide();
            
            // Reset modal state
            AquaLuxeDemoImporter.resetModalState();
            
            // Check required plugins again
            AquaLuxeDemoImporter.checkRequiredPlugins();
        }
    };

    // Initialize Demo Importer
    $(document).ready(function() {
        AquaLuxeDemoImporter.init();
    });

})(jQuery);