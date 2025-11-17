(function ($) {
  'use strict';

  $(document).ready(function () {
    // Mobile menu toggle
    $('.mobile-menu-toggle').on('click', function () {
      $('.primary-menu').slideToggle();
    });

    // Smooth scroll for anchor links
    $('a[href*="#"]').on('click', function (e) {
      if (this.hash !== '') {
        e.preventDefault();
        var target = this.hash;
        $('html, body').animate(
          {
            scrollTop: $(target).offset().top,
          },
          600
        );
      }
    });
  });
})(jQuery);
