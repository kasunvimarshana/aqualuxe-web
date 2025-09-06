import '../css/app.css';
import { initDarkMode } from './modules/dark_mode';
import { initQuickView } from './modules/quick_view';
import { initWishlist } from './modules/wishlist';
// Progressive enhancements only. Avoid breaking no-JS flows.

window.addEventListener('DOMContentLoaded', () => {
  initDarkMode();
  initQuickView();
  initWishlist();
  // A11Y: focus skip-link target when present
  const skip = document.querySelector('.alx-skip-link');
  if (skip) {
    skip.addEventListener('click', () => {
      const main = document.querySelector('main');
      if (main) main.setAttribute('tabindex', '-1');
      main?.focus();
    });
  }
});
