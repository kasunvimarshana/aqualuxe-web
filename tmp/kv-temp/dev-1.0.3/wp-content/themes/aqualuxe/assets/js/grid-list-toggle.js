(function ($) {
  'use strict';
  $(document).ready(function () {
    $('.view-toggle button').on('click', function () {
      var view = $(this).data('view');
      $('.products')
        .removeClass('grid-view list-view')
        .addClass(view + '-view');
      localStorage.setItem('aqualuxe_view', view);
    });

    // Load saved view
    var savedView = localStorage.getItem('aqualuxe_view');
    if (savedView) {
      $('.products')
        .removeClass('grid-view list-view')
        .addClass(savedView + '-view');
    }
  });
})(jQuery);
