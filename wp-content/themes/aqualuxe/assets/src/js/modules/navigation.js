// Navigation module
(function ($) {
  'use strict';

  // Mobile navigation toggle
  $(document).on('click', '[data-mobile-menu-toggle]', function () {
    const nav = $('.mobile-navigation');
    nav.toggleClass('hidden');

    const expanded = $(this).attr('aria-expanded') === 'true';
    $(this).attr('aria-expanded', !expanded);
  });
})(jQuery);
