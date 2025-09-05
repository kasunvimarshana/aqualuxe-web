import '../css/app.css';
import { initDarkMode } from './modules/dark_mode';
import { initQuickView } from './modules/quick_view';
import { initWishlist } from './modules/wishlist';

window.addEventListener('DOMContentLoaded', () => {
  initDarkMode();
  initQuickView();
  initWishlist();
});
