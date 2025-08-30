(function ($) {
  'use strict';
  $(window).on('scroll', function () {
    if ($(this).scrollTop() > 100) {
      $('.site-header').addClass('sticky');
    } else {
      $('.site-header').removeClass('sticky');
    }
  });
})(jQuery);
