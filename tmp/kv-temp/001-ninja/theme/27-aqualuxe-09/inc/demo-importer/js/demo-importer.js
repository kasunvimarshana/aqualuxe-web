/**
 * AquaLuxe Demo Importer JavaScript
 *
 * Handles the AJAX requests for the demo content importer.
 */
(function($) {
    'use strict';

    // Demo Importer object
    var AquaLuxeDemoImporter = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            $('#aqualuxe-demo-import-button').on('click', this.startImport);
        },

        startImport: function(e) {
            e.preventDefault();

            // Get selected content types
            var contentTypes = [];
            $('input[name="import_content[]"]:checked').each(function() {
                contentTypes.push($(this).val());
            });

            if (contentTypes.length === 0) {
                alert(aqualuxeDemoImporter.noContentSelected);
                return;
            }

            // Confirm import
            if (!confirm(aqualuxeDemoImporter.confirmImport)) {
                return;
            }

            // Show progress bar
            $('#aqualuxe-demo-import-progress').show();
            $('.aqualuxe-progress-bar-inner').width('0%');
            $('.aqualuxe-progress-percentage').text('0%');
            $('.aqualuxe-progress-step').text(aqualuxeDemoImporter.preparing);

            // Disable form
            $('#aqualuxe-demo-import-form input, #aqualuxe-demo-import-form button').prop('disabled', true);
            $('#aqualuxe-demo-import-button').text(aqualuxeDemoImporter.importing);
            $('.spinner').addClass('is-active');

            // Clear log
            $('#aqualuxe-demo-import-log').show();
            $('.aqualuxe-log-entries').empty();

            // Start import
            AquaLuxeDemoImporter.importContent(contentTypes);
        },

        importContent: function(contentTypes) {
            // Add initial log entry
            AquaLuxeDemoImporter.addLogEntry('Starting import process...');

            // Make AJAX request
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo_content',
                    content_types: contentTypes,
                    nonce: aqualuxeDemoImporter.nonce
                },
                beforeSend: function() {
                    // Update progress
                    $('.aqualuxe-progress-bar-inner').width('10%');
                    $('.aqualuxe-progress-percentage').text('10%');
                    $('.aqualuxe-progress-step').text(aqualuxeDemoImporter.importing);
                },
                success: function(response) {
                    if (response.success) {
                        // Update progress
                        $('.aqualuxe-progress-bar-inner').width('100%');
                        $('.aqualuxe-progress-percentage').text('100%');
                        $('.aqualuxe-progress-step').text(aqualuxeDemoImporter.complete);

                        // Add log entries
                        if (response.data.log && response.data.log.length) {
                            for (var i = 0; i < response.data.log.length; i++) {
                                AquaLuxeDemoImporter.addLogEntry(response.data.log[i]);
                            }
                        }

                        // Add error log entries
                        if (response.data.errors && response.data.errors.length) {
                            for (var i = 0; i < response.data.errors.length; i++) {
                                AquaLuxeDemoImporter.addLogEntry('Error: ' + response.data.errors[i], 'error');
                            }
                        }

                        // Add success message
                        AquaLuxeDemoImporter.addLogEntry('Import completed successfully!', 'success');

                        // Enable form
                        $('#aqualuxe-demo-import-form input, #aqualuxe-demo-import-form button').prop('disabled', false);
                        $('#aqualuxe-demo-import-button').text(aqualuxeDemoImporter.importAgain);
                        $('.spinner').removeClass('is-active');
                    } else {
                        // Update progress
                        $('.aqualuxe-progress-bar-inner').width('100%');
                        $('.aqualuxe-progress-percentage').text('100%');
                        $('.aqualuxe-progress-step').text(aqualuxeDemoImporter.error);

                        // Add error message
                        AquaLuxeDemoImporter.addLogEntry('Error: ' + response.data.message, 'error');

                        // Add log entries
                        if (response.data.log && response.data.log.length) {
                            for (var i = 0; i < response.data.log.length; i++) {
                                AquaLuxeDemoImporter.addLogEntry(response.data.log[i]);
                            }
                        }

                        // Add error log entries
                        if (response.data.errors && response.data.errors.length) {
                            for (var i = 0; i < response.data.errors.length; i++) {
                                AquaLuxeDemoImporter.addLogEntry('Error: ' + response.data.errors[i], 'error');
                            }
                        }

                        // Enable form
                        $('#aqualuxe-demo-import-form input, #aqualuxe-demo-import-form button').prop('disabled', false);
                        $('#aqualuxe-demo-import-button').text(aqualuxeDemoImporter.tryAgain);
                        $('.spinner').removeClass('is-active');
                    }
                },
                error: function(xhr, status, error) {
                    // Update progress
                    $('.aqualuxe-progress-bar-inner').width('100%');
                    $('.aqualuxe-progress-percentage').text('100%');
                    $('.aqualuxe-progress-step').text(aqualuxeDemoImporter.error);

                    // Add error message
                    AquaLuxeDemoImporter.addLogEntry('AJAX Error: ' + error, 'error');

                    // Enable form
                    $('#aqualuxe-demo-import-form input, #aqualuxe-demo-import-form button').prop('disabled', false);
                    $('#aqualuxe-demo-import-button').text(aqualuxeDemoImporter.tryAgain);
                    $('.spinner').removeClass('is-active');
                }
            });
        },

        addLogEntry: function(message, type) {
            type = type || 'info';
            var logClass = 'aqualuxe-log-' + type;
            var timestamp = new Date().toLocaleTimeString();
            var logEntry = $('<div class="aqualuxe-log-entry ' + logClass + '"><span class="aqualuxe-log-time">[' + timestamp + ']</span> ' + message + '</div>');
            $('.aqualuxe-log-entries').append(logEntry);
            
            // Scroll to bottom of log
            var logContainer = $('.aqualuxe-log-entries');
            logContainer.scrollTop(logContainer[0].scrollHeight);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeDemoImporter.init();
    });

})(jQuery);