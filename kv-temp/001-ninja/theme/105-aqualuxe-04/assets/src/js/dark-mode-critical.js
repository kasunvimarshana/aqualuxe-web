/**
 * Critical Dark Mode Detection Script
 * Must be loaded inline for performance (prevents flashing)
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function () {
  'use strict';

  // Check for saved dark mode preference or default to system preference
  const darkMode =
    localStorage.getItem('aqualuxe_dark_mode') ||
    (window.matchMedia &&
    window.matchMedia('(prefers-color-scheme: dark)').matches
      ? 'enabled'
      : 'disabled');

  // Apply dark mode class immediately to prevent flash
  if (darkMode === 'enabled') {
    document.documentElement.classList.add('dark-mode');
    document.body.classList.add('dark-mode');
  }

  // Store the current preference
  localStorage.setItem('aqualuxe_dark_mode', darkMode);
})();
