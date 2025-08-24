// Multicurrency switcher JS (optional: reloads page on switch for instant update)
document.addEventListener('DOMContentLoaded', function() {
  var switcher = document.querySelector('.aqualuxe-currency-switcher');
  if (switcher) {
    switcher.addEventListener('submit', function(e) {
      // Let the form submit normally (GET), or use AJAX if desired
      // e.preventDefault();
      // location.search = '?aqualuxe_currency=' + encodeURIComponent(e.target.aqualuxe_currency.value);
    });
  }
});
