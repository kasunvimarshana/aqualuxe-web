// Demo Importer JavaScript
(function($) {
    'use strict';
    
    const DemoImporter = {
        
        // Initialize
        init: function() {
            this.bindEvents();
        },
        
        // Bind events
        bindEvents: function() {
            $(document).on('click', '.demo-option', this.selectDemo.bind(this));
            $(document).on('click', '#import-demo-btn', this.importDemo.bind(this));
            $(document).on('click', '#reset-site-btn', this.resetSite.bind(this));
        },
        
        // Select demo
        selectDemo: function(e) {
            const $option = $(e.currentTarget);
            const demoType = $option.data('demo');
            
            // Update UI
            $('.demo-option').removeClass('selected');
            $option.addClass('selected');
            
            // Enable import button
            $('#import-demo-btn').prop('disabled', false).data('demo-type', demoType);
        },
        
        // Import demo
        importDemo: function(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const demoType = $button.data('demo-type');
            
            if (!demoType) {
                alert('Please select a demo to import.');
                return;
            }
            
            // Get import options
            const options = {
                import_content: $('input[name="import_content"]').is(':checked'),
                import_customizer: $('input[name="import_customizer"]').is(':checked'),
                import_widgets: $('input[name="import_widgets"]').is(':checked'),
                import_menus: $('input[name="import_menus"]').is(':checked'),
            };
            
            // Show progress
            this.showProgress();
            this.updateProgress(0, aqualuxe_demo_importer.strings.importing);
            
            // Disable button
            $button.prop('disabled', true);
            
            // Start import
            this.performImport(demoType, options);
        },
        
        // Perform import
        performImport: function(demoType, options) {
            $.ajax({
                url: aqualuxe_demo_importer.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    nonce: aqualuxe_demo_importer.nonce,
                    demo_type: demoType,
                    ...options
                },
                success: function(response) {
                    if (response.success) {
                        this.updateProgress(100, aqualuxe_demo_importer.strings.success);
                        this.showLog(response.data.log || []);
                        
                        // Show success message
                        setTimeout(() => {
                            alert(aqualuxe_demo_importer.strings.success);
                            this.hideProgress();
                        }, 1000);
                    } else {
                        this.updateProgress(0, aqualuxe_demo_importer.strings.error);
                        setTimeout(() => {
                            alert(response.data || aqualuxe_demo_importer.strings.error);
                            this.hideProgress();
                        }, 1000);
                    }
                }.bind(this),
                error: function() {
                    this.updateProgress(0, aqualuxe_demo_importer.strings.error);
                    setTimeout(() => {
                        alert(aqualuxe_demo_importer.strings.error);
                        this.hideProgress();
                    }, 1000);
                }.bind(this),
                complete: function() {
                    $('#import-demo-btn').prop('disabled', false);
                }
            });
        },
        
        // Reset site
        resetSite: function(e) {
            e.preventDefault();
            
            if (!confirm(aqualuxe_demo_importer.strings.confirm_reset)) {
                return;
            }
            
            const $button = $(e.currentTarget);
            
            // Show progress
            this.showProgress();
            this.updateProgress(0, aqualuxe_demo_importer.strings.resetting);
            
            // Disable button
            $button.prop('disabled', true);
            
            $.ajax({
                url: aqualuxe_demo_importer.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_reset_demo',
                    nonce: aqualuxe_demo_importer.nonce
                },
                success: function(response) {
                    if (response.success) {
                        this.updateProgress(100, aqualuxe_demo_importer.strings.reset_success);
                        
                        setTimeout(() => {
                            alert(aqualuxe_demo_importer.strings.reset_success);
                            this.hideProgress();
                            location.reload();
                        }, 1000);
                    } else {
                        this.updateProgress(0, aqualuxe_demo_importer.strings.reset_error);
                        setTimeout(() => {
                            alert(response.data || aqualuxe_demo_importer.strings.reset_error);
                            this.hideProgress();
                        }, 1000);
                    }
                }.bind(this),
                error: function() {
                    this.updateProgress(0, aqualuxe_demo_importer.strings.reset_error);
                    setTimeout(() => {
                        alert(aqualuxe_demo_importer.strings.reset_error);
                        this.hideProgress();
                    }, 1000);
                }.bind(this),
                complete: function() {
                    $button.prop('disabled', false);
                }
            });
        },
        
        // Show progress
        showProgress: function() {
            $('.import-progress').show();
            $('.import-log').hide();
        },
        
        // Hide progress
        hideProgress: function() {
            $('.import-progress').hide();
        },
        
        // Update progress
        updateProgress: function(percent, text) {
            $('.progress-fill').css('width', percent + '%');
            $('.progress-text').text(text);
        },
        
        // Show log
        showLog: function(log) {
            if (log && log.length > 0) {
                const logContent = log.join('\n');
                $('.log-content').text(logContent);
                $('.import-log').show();
            }
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        DemoImporter.init();
    });
    
})(jQuery);