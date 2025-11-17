// Set webpack public path for dynamic chunks (must be first)
import './public-path.js';
import './modules/dark-mode.js';
import './modules/reduced-motion.js';
import './modules/wishlist.js';
import './modules/quickview.js';
import './analytics.js';
import './modules/nav.js';
import './modules/minicart.js';

// Lazy-load the heavy hero when visible
const hero = document.getElementById('alx-hero-canvas');
if (hero && 'IntersectionObserver' in window) {
  const io = new IntersectionObserver(async entries => {
    const e = entries[0];
    if (e.isIntersecting) {
      io.disconnect();
      try { await import(/* webpackChunkName: "hero" */ './hero'); } catch (err) { console.error('Hero failed', err); }
    }
  });
  io.observe(hero);
}
