/**
 * AquaLuxe Customizer JavaScript
 */
(function () {
  'use strict';

  // Live update for primary color
  wp.customize('aqualuxe_primary_color', function (value) {
    value.bind(function (to) {
      document.documentElement.style.setProperty('--primary', to);
    });
  });

  // Live update for secondary color
  wp.customize('aqualuxe_secondary_color', function (value) {
    value.bind(function (to) {
      document.documentElement.style.setProperty('--secondary', to);
    });
  });

  // Live update for accent color
  wp.customize('aqualuxe_accent_color', function (value) {
    value.bind(function (to) {
      document.documentElement.style.setProperty('--accent', to);
    });
  });
})();
