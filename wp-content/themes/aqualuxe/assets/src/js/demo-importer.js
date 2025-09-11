/**
 * Demo Importer JavaScript
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class DemoImporter {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            $(document).on('click', '.import-demo-btn', this.importDemo.bind(this));
            $(document).on('click', '.reset-demo-btn', this.resetDemo.bind(this));
        }

        importDemo(e) {
            e.preventDefault();
            
            const button = $(e.currentTarget);
            const demoId = button.data('demo-id');
            
            if (!confirm('Are you sure you want to import this demo? This will replace your current content.')) {
                return;
            }
            
            this.showProgress();
            
            $.ajax({
                url: aqualuxe_demo_importer.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    demo_id: demoId,
                    nonce: aqualuxe_demo_importer.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showSuccess('Demo imported successfully!');
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: () => {
                    this.showError('Import failed. Please try again.');
                },
                complete: () => {
                    this.hideProgress();
                }
            });
        }

        resetDemo(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to reset all demo content? This action cannot be undone.')) {
                return;
            }
            
            this.showProgress();
            
            $.ajax({
                url: aqualuxe_demo_importer.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_reset_demo',
                    nonce: aqualuxe_demo_importer.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showSuccess('Demo content reset successfully!');
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: () => {
                    this.showError('Reset failed. Please try again.');
                },
                complete: () => {
                    this.hideProgress();
                }
            });
        }

        showProgress() {
            $('.demo-importer-progress').show();
        }

        hideProgress() {
            $('.demo-importer-progress').hide();
        }

        showSuccess(message) {
            this.showNotice(message, 'success');
        }

        showError(message) {
            this.showNotice(message, 'error');
        }

        showNotice(message, type) {
            const notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
            $('.demo-importer-notices').html(notice);
        }
    }

    // Initialize when ready
    $(document).ready(function() {
        if (typeof aqualuxe_demo_importer !== 'undefined') {
            new DemoImporter();
        }
    });

})(jQuery);