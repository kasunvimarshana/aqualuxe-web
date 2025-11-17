(function($) {
    'use strict';

    $(function() {
        const $copyButton = $('#aqualuxe-copy-referral-url');
        const $referralUrlInput = $('#aqualuxe-referral-url');
        const $copyFeedback = $('#aqualuxe-copy-feedback');

        if (!$copyButton.length) {
            return;
        }

        $copyButton.on('click', function() {
            // Select the text
            $referralUrlInput.get(0).select();
            $referralUrlInput.get(0).setSelectionRange(0, 99999); // For mobile devices

            try {
                // Copy the text
                document.execCommand('copy');
                
                // Provide feedback
                $copyFeedback.text('Copied to clipboard!');
                $copyButton.text('Copied!');

                setTimeout(function() {
                    $copyFeedback.text('');
                    $copyButton.text('Copy');
                }, 2000);

            } catch (err) {
                $copyFeedback.text('Failed to copy. Please copy manually.');
            }
        });
    });

})(jQuery);
