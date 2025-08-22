/**
 * AquaLuxe Demo Importer Admin JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/Demo_Importer
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // Demo Importer
    var AquaLuxeDemoImporter = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Import demo button click
            $('.aqualuxe-import-demo').on('click', this.importDemo);
            
            // Modal close button click
            $('.aqualuxe-demo-importer-modal-close').on('click', this.closeModal);
            
            // Close modal when clicking outside
            $(window).on('click', function(event) {
                if ($(event.target).hasClass('aqualuxe-demo-importer-modal')) {
                    AquaLuxeDemoImporter.closeModal();
                }
            });
        },
        
        /**
         * Import demo
         */
        importDemo: function(e) {
            e.preventDefault();
            
            // Get demo ID
            var demoId = $(this).data('demo-id');
            
            // Confirm import
            if (!confirm(aqualuxeDemoImporter.i18n.confirm)) {
                return;
            }
            
            // Show modal
            AquaLuxeDemoImporter.showModal();
            
            // Update status
            AquaLuxeDemoImporter.updateStatus(aqualuxeDemoImporter.i18n.importing);
            
            // Update progress bar
            AquaLuxeDemoImporter.updateProgressBar(10);
            
            // Import demo
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    nonce: aqualuxeDemoImporter.nonce,
                    demo_id: demoId
                },
                beforeSend: function() {
                    // Disable import button
                    $('.aqualuxe-import-demo').prop('disabled', true);
                    
                    // Update progress bar
                    AquaLuxeDemoImporter.updateProgressBar(30);
                },
                success: function(response) {
                    // Update progress bar
                    AquaLuxeDemoImporter.updateProgressBar(100);
                    
                    if (response.success) {
                        // Update status
                        AquaLuxeDemoImporter.updateStatus(response.data.message);
                        
                        // Redirect to homepage after 3 seconds
                        setTimeout(function() {
                            window.location.href = window.location.href;
                        }, 3000);
                    } else {
                        // Update status
                        AquaLuxeDemoImporter.updateStatus(response.data.message);
                        
                        // Enable import button
                        $('.aqualuxe-import-demo').prop('disabled', false);
                    }
                },
                error: function() {
                    // Update status
                    AquaLuxeDemoImporter.updateStatus(aqualuxeDemoImporter.i18n.error);
                    
                    // Update progress bar
                    AquaLuxeDemoImporter.updateProgressBar(0);
                    
                    // Enable import button
                    $('.aqualuxe-import-demo').prop('disabled', false);
                }
            });
        },
        
        /**
         * Show modal
         */
        showModal: function() {
            $('.aqualuxe-demo-importer-modal').css('display', 'block');
        },
        
        /**
         * Close modal
         */
        closeModal: function() {
            $('.aqualuxe-demo-importer-modal').css('display', 'none');
        },
        
        /**
         * Update status
         */
        updateStatus: function(message) {
            $('.aqualuxe-demo-importer-modal-status').html(message);
        },
        
        /**
         * Update progress bar
         */
        updateProgressBar: function(percentage) {
            $('.aqualuxe-demo-importer-progress-bar').css('width', percentage + '%');
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeDemoImporter.init();
    });
    
})(jQuery);