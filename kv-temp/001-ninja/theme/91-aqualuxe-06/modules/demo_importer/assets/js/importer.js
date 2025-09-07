(function($) {
    'use strict';

    $(function() {
        const $importBtn = $('#aqualuxe-import-btn');
        const $flushBtn = $('#aqualuxe-flush-btn');
        const $progress = $('#aqualuxe-importer-progress');
        const $progressText = $progress.find('.progress-text');
        const $log = $('#aqualuxe-importer-log');
        const $logPre = $log.find('pre');

        $importBtn.on('click', function() {
            $importBtn.prop('disabled', true);
            $flushBtn.prop('disabled', true);
            $progress.show();
            $log.hide();
            $logPre.empty();
            $progressText.text(aqualuxe_importer_params.texts.importing);

            $.post(aqualuxe_importer_params.ajax_url, {
                action: 'aqualuxe_import_demo_data',
                nonce: aqualuxe_importer_params.nonce
            })
            .done(function(response) {
                if (response.success) {
                    $progressText.text(aqualuxe_importer_params.texts.success);
                    $logPre.text(JSON.stringify(response.data, null, 2));
                    $log.show();
                } else {
                    $progressText.text(aqualuxe_importer_params.texts.error);
                    if (response.data) {
                        $logPre.text(JSON.stringify(response.data, null, 2));
                        $log.show();
                    }
                }
            })
            .fail(function() {
                $progressText.text(aqualuxe_importer_params.texts.error);
            })
            .always(function() {
                $importBtn.prop('disabled', false);
                $flushBtn.prop('disabled', false);
                $progress.find('.spinner').removeClass('is-active');
            });
        });

        $flushBtn.on('click', function() {
            if (!confirm(aqualuxe_importer_params.texts.confirm_flush)) {
                return;
            }

            $importBtn.prop('disabled', true);
            $flushBtn.prop('disabled', true);
            $progress.show();
            $log.hide();
            $logPre.empty();
            $progressText.text(aqualuxe_importer_params.texts.flushing);

            $.post(aqualuxe_importer_params.ajax_url, {
                action: 'aqualuxe_flush_data',
                nonce: aqualuxe_importer_params.nonce
            })
            .done(function(response) {
                if (response.success) {
                    $progressText.text(aqualuxe_importer_params.texts.flush_success);
                } else {
                    $progressText.text(aqualuxe_importer_params.texts.error);
                }
            })
            .fail(function() {
                $progressText.text(aqualuxe_importer_params.texts.error);
            })
            .always(function() {
                $importBtn.prop('disabled', false);
                $flushBtn.prop('disabled', false);
                $progress.find('.spinner').removeClass('is-active');
            });
        });
    });

})(jQuery);
