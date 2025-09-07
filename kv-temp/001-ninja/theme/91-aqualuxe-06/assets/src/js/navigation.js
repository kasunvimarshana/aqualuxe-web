// Navigation JavaScript
(function ($) {
    'use strict';

    $(function () {
        const menuToggle = $('.menu-toggle');
        const responsiveMenu = $('.responsive-menu');

        menuToggle.on('click', function () {
            responsiveMenu.toggleClass('toggled-on');
        });
    });

})(jQuery);
