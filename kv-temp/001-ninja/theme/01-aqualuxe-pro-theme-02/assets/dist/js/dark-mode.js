/******/ (() => { // webpackBootstrap
/*!************************************!*\
  !*** ./assets/src/js/dark-mode.js ***!
  \************************************/
// Dark mode toggle
document.addEventListener('DOMContentLoaded', function () {
  var toggleButton = document.getElementById('dark-mode-toggle');
  var html = document.documentElement;

  // Check for saved preference
  if (localStorage.getItem('aqualuxe_dark_mode') === 'true') {
    html.classList.add('dark');
  }
  toggleButton.addEventListener('click', function () {
    html.classList.toggle('dark');
    localStorage.setItem('aqualuxe_dark_mode', html.classList.contains('dark'));
  });
});
/******/ })()
;