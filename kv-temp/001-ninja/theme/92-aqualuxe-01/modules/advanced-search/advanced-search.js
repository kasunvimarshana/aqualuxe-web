(function($) {
    'use strict';

    $(document).ready(function() {
        const $modal = $('#aqualuxe-search-modal');
        const $trigger = $('#aqualuxe-search-trigger');
        const $close = $('#aqualuxe-search-close');
        const $input = $('#aqualuxe-search-input');
        const $results = $('#aqualuxe-search-results');
        const $body = $('body');
        let searchTimeout;

        function openModal() {
            $body.addClass('overflow-hidden');
            $modal.addClass('is-visible');
            $input.focus();
        }

        function closeModal() {
            $body.removeClass('overflow-hidden');
            $modal.removeClass('is-visible');
            $input.val('');
            $results.html('').hide();
        }

        $trigger.on('click', function(e) {
            e.preventDefault();
            openModal();
        });

        $close.on('click', closeModal);
        $modal.on('click', function(e) {
            if ($(e.target).is($modal)) {
                closeModal();
            }
        });

        $(document).on('keydown', function(e) {
            if (e.key === "Escape" && $modal.hasClass('is-visible')) {
                closeModal();
            }
        });

        $input.on('keyup', function() {
            clearTimeout(searchTimeout);
            const query = $(this).val();

            if (query.length < 3) {
                $results.html('').hide();
                return;
            }

            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: aqualuxe_search_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_advanced_search',
                        nonce: aqualuxe_search_params.nonce,
                        query: query
                    },
                    beforeSend: function() {
                        $results.html('<div class="p-4 text-center text-gray-500">Searching...</div>').show();
                    },
                    success: function(response) {
                        if (response.success) {
                            $results.html(response.data).show();
                        } else {
                            $results.html('<div class="p-4 text-center text-red-500">An error occurred.</div>').show();
                        }
                    },
                    error: function() {
                        $results.html('<div class="p-4 text-center text-red-500">Request failed.</div>').show();
                    }
                });
            }, 500); // Debounce
        });
    });

})(jQuery);
