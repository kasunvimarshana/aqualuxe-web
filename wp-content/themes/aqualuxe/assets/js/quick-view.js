(function ($) {
  'use strict';

  $(document).ready(function () {
    $('.quick-view-btn').on('click', function (e) {
      e.preventDefault();
      const productId = $(this).data('product_id');

      $.ajax({
        url: aqualuxe_quickview.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_quick_view',
          product_id: productId,
        },
        beforeSend: function () {
          $('body').append('<div class="quick-view-loading">Loading...</div>');
        },
        success: function (response) {
          $('.quick-view-loading').remove();
          $('body').append(
            '<div class="quick-view-modal">' + response + '</div>'
          );
        },
      });
    });

    // Close quick view
    $(document).on('click', '.quick-view-close', function () {
      $('.quick-view-modal').remove();
    });
  });
})(jQuery);
