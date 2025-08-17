/**
 * AquaLuxe Demo Importer JavaScript
 */

(function($) {
    'use strict';

    // Demo Importer
    var AquaLuxeDemoImporter = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            $('.aqualuxe-demo-importer-import-button').on('click', this.importDemo);
        },

        importDemo: function(e) {
            e.preventDefault();

            var $button = $(this);
            var demoId = $button.data('demo-id');

            // Confirm import
            if (!confirm(aqualuxeImporter.confirmImport)) {
                return;
            }

            // Disable all import buttons
            $('.aqualuxe-demo-importer-import-button').prop('disabled', true);
            $button.text(aqualuxeImporter.importing);

            // Show progress bar
            $('.aqualuxe-demo-importer-progress').show();

            // Start import process
            AquaLuxeDemoImporter.processImport(demoId, 'start', 0);
        },

        processImport: function(demoId, step, progress) {
            $.ajax({
                url: aqualuxeImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo',
                    nonce: aqualuxeImporter.nonce,
                    demo_id: demoId,
                    step: step
                },
                success: function(response) {
                    if (response.success) {
                        // Update progress bar
                        $('.aqualuxe-demo-importer-progress-bar-inner').css('width', response.data.progress + '%');
                        $('.aqualuxe-demo-importer-progress-percentage').text(response.data.progress + '%');
                        $('.aqualuxe-demo-importer-progress-step').text(response.data.message);

                        // If not done, process next step
                        if (response.data.step !== 'done') {
                            AquaLuxeDemoImporter.processImport(demoId, response.data.step, response.data.progress);
                        } else {
                            // Import completed
                            setTimeout(function() {
                                alert(aqualuxeImporter.importSuccess);
                                window.location.href = response.data.homeUrl;
                            }, 1000);
                        }
                    } else {
                        // Error
                        alert(aqualuxeImporter.importError);
                        $('.aqualuxe-demo-importer-import-button').prop('disabled', false);
                        $button.text(aqualuxeImporter.import);
                    }
                },
                error: function() {
                    // AJAX error
                    alert(aqualuxeImporter.importError);
                    $('.aqualuxe-demo-importer-import-button').prop('disabled', false);
                    $button.text(aqualuxeImporter.import);
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeDemoImporter.init();
    });

})(jQuery);