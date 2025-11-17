/**
 * Demo Import Admin Scripts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Demo Import functionality
    $(document).ready(function() {
        // Import button click handler
        $('#aqualuxe-import-demo').on('click', function(e) {
            e.preventDefault();
            
            // Confirm import
            if (!confirm('Are you sure you want to import the demo content? This may overwrite your existing content.')) {
                return;
            }
            
            // Disable the button
            var $button = $(this);
            $button.prop('disabled', true).text(aqualuxeImport.importing);
            
            // Show progress bar
            $('#aqualuxe-import-progress').show();
            
            // Start the import process
            importDemoContent();
        });
        
        // Function to import demo content
        function importDemoContent() {
            // Update progress
            updateProgress(10, 'Preparing import...');
            
            // AJAX request to import demo content
            $.ajax({
                url: aqualuxeImport.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo_content',
                    nonce: aqualuxeImport.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update progress to 100%
                        updateProgress(100, response.data.message);
                        
                        // Show success message
                        $('#aqualuxe-import-status')
                            .addClass('success')
                            .text(aqualuxeImport.complete + ' ' + response.data.message);
                        
                        // Re-enable the button
                        $('#aqualuxe-import-demo')
                            .prop('disabled', false)
                            .text('Import Completed');
                    } else {
                        // Show error message
                        handleError(response.data.message || aqualuxeImport.error);
                    }
                },
                error: function(xhr, status, error) {
                    // Show error message
                    handleError(aqualuxeImport.error + ': ' + error);
                },
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    
                    // Upload progress
                    xhr.upload.addEventListener('progress', function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.min(90, Math.round((evt.loaded / evt.total) * 100));
                            updateProgress(percentComplete, 'Importing content...');
                        }
                    }, false);
                    
                    return xhr;
                }
            });
        }
        
        // Function to update progress bar
        function updateProgress(percent, message) {
            $('.aqualuxe-import-progress-bar').css('width', percent + '%');
            $('.aqualuxe-import-progress-text').text(percent + '%');
            
            if (message) {
                $('#aqualuxe-import-status').text(message);
            }
        }
        
        // Function to handle errors
        function handleError(message) {
            // Update progress
            updateProgress(0, '');
            
            // Show error message
            $('#aqualuxe-import-status')
                .addClass('error')
                .text(message);
            
            // Re-enable the button
            $('#aqualuxe-import-demo')
                .prop('disabled', false)
                .text('Try Again');
        }
    });

})(jQuery);