(function($) {
    'use strict';

    $(function() {
        $('#aqualuxe-trade-in-form').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $responseContainer = $('#aqualuxe-trade-in-response');
            const $submitButton = $form.find('button[type="submit"]');
            const originalButtonText = $submitButton.html();

            $submitButton.prop('disabled', true).html('Submitting...');
            $responseContainer.html('').removeClass('form-success form-error');

            const formData = $form.serialize() + '&action=aqualuxe_submit_trade_in&nonce=' + aqualuxeTradeIn.nonce;

            $.post(aqualuxeTradeIn.ajax_url, formData)
                .done(function(response) {
                    if (response.success) {
                        $responseContainer.html('<p>' + response.data.message + '</p>').addClass('form-success');
                        $form[0].reset();
                    } else {
                        $responseContainer.html('<p>' + response.data.message + '</p>').addClass('form-error');
                    }
                })
                .fail(function() {
                    $responseContainer.html('<p>An unexpected error occurred. Please try again later.</p>').addClass('form-error');
                })
                .always(function() {
                    $submitButton.prop('disabled', false).html(originalButtonText);
                });
        });
    });

})(jQuery);
