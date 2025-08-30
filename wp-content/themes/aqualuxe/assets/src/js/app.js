'use strict';

(function(){
  const root = document.documentElement;

  // Dark mode toggle with localStorage persistence
  const toggle = document.querySelector('[data-theme-toggle]');
  const STORAGE_KEY = 'aqualuxe:theme';
  const persisted = localStorage.getItem(STORAGE_KEY);
  if (persisted === 'dark' || (!persisted && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    root.classList.add('dark');
  }
  if (toggle) {
    toggle.addEventListener('click', function(){
      root.classList.toggle('dark');
      localStorage.setItem(STORAGE_KEY, root.classList.contains('dark') ? 'dark' : 'light');
    });
  }

  // Mobile nav
  const navBtn = document.querySelector('[data-nav-toggle]');
  const nav = document.getElementById('primary-menu');
  if (navBtn && nav) {
    navBtn.addEventListener('click', () => {
      const expanded = navBtn.getAttribute('aria-expanded') === 'true' || false;
      navBtn.setAttribute('aria-expanded', String(!expanded));
      nav.classList.toggle('hidden');
    });
  }

  // Lazy load images with loading=lazy as default handled in PHP; intersection observer for bg images
  const lazyEls = document.querySelectorAll('[data-lazy-bg]');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const el = entry.target;
          const bg = el.getAttribute('data-lazy-bg');
          if (bg) el.style.backgroundImage = `url(${bg})`;
          observer.unobserve(el);
        }
      });
    });
    lazyEls.forEach(el => io.observe(el));
  }
})();
