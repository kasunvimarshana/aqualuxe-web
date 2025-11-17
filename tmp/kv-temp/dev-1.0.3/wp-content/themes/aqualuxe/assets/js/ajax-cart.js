(function ($) {
  'use strict';

  $(document).on('added_to_cart', function () {
    // Update cart count when product added via AJAX
    $.get(
      wc_cart_fragments_params.wc_ajax_url.replace(
        '%%endpoint%%',
        'get_refreshed_fragments'
      ),
      function (fragments) {
        if (fragments['span.cart-count']) {
          $('span.cart-count').replaceWith(fragments['span.cart-count']);
        }
      }
    );
  });
})(jQuery);
