(function ($) {
    'use strict';

    $(function () {
        const darkModeToggle = $('#dark-mode-toggle');
        const body = $('body');

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.addClass('dark-mode');
        }

        darkModeToggle.on('click', function () {
            body.toggleClass('dark-mode');

            // Save preference to localStorage
            if (body.hasClass('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    });

})(jQuery);
