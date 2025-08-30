/**
 * AquaLuxe Site JavaScript
 */
(function () {
  'use strict';

  // Document ready
  document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu toggle
    var menuToggle = document.querySelector('.menu-toggle');
    var navigation = document.querySelector('.main-navigation');

    if (menuToggle && navigation) {
      menuToggle.addEventListener('click', function () {
        navigation.classList.toggle('toggled');
        menuToggle.setAttribute(
          'aria-expanded',
          navigation.classList.contains('toggled') ? 'true' : 'false'
        );
      });
    }

    // Smooth scroll for anchor links
    var anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(function (link) {
      link.addEventListener('click', function (e) {
        var targetId = this.getAttribute('href');

        if (targetId !== '#') {
          e.preventDefault();

          var targetElement = document.querySelector(targetId);

          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop,
              behavior: 'smooth',
            });
          }
        }
      });
    });
  });
})();
