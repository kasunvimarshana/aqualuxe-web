(function($) {
    'use strict';

    $(document).ready(function() {
        const $panel = $('#aqualuxe-slide-in-panel');
        const $trigger = $('#aqualuxe-panel-trigger');
        const $close = $('#aqualuxe-panel-close');
        const $overlay = $('#aqualuxe-panel-overlay');
        const $body = $('body');

        function openPanel() {
            $body.addClass('overflow-hidden');
            $panel.removeAttr('hidden').addClass('is-open');
            $overlay.addClass('is-visible');
            $trigger.attr('aria-expanded', 'true');
            $panel.find('a, button').first().focus();
        }

        function closePanel() {
            $body.removeClass('overflow-hidden');
            $panel.removeClass('is-open');
            $overlay.removeClass('is-visible');
            $trigger.attr('aria-expanded', 'false').focus();
            // Hide after transition
            setTimeout(function() {
                $panel.attr('hidden', true);
            }, 300);
        }

        $trigger.on('click', openPanel);
        $close.on('click', closePanel);
        $overlay.on('click', closePanel);

        // Close on escape key
        $(document).on('keydown', function(e) {
            if (e.key === "Escape" && $panel.hasClass('is-open')) {
                closePanel();
            }
        });
    });

})(jQuery);
