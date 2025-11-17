/**
 * AquaLuxe Navigation JavaScript
 */
(function () {
  'use strict';

  // Document ready
  document.addEventListener('DOMContentLoaded', function () {
    // Navigation accessibility
    var menuItems = document.querySelectorAll('.main-navigation li');

    menuItems.forEach(function (item) {
      var link = item.querySelector('a');
      var subMenu = item.querySelector('.sub-menu');

      if (link && subMenu) {
        link.addEventListener('focus', function () {
          item.classList.add('focus');
        });

        link.addEventListener('blur', function () {
          item.classList.remove('focus');
        });

        // Add toggle for sub-menus on mobile
        var toggle = document.createElement('button');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.classList.add('dropdown-toggle');
        toggle.innerHTML =
          '<span class="screen-reader-text">' +
          aqualuxeScreenReaderText.expand +
          '</span>';

        link.parentNode.insertBefore(toggle, link.nextSibling);

        toggle.addEventListener('click', function () {
          item.classList.toggle('toggled');
          toggle.setAttribute(
            'aria-expanded',
            item.classList.contains('toggled') ? 'true' : 'false'
          );
        });
      }
    });
  });
})();
