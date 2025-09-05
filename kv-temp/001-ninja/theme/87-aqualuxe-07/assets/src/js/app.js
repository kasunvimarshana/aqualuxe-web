import '../css/app.css';

// Dark mode (split chunk)
if (document.getElementById('darkModeToggle')) {
  import('./modules/dark-mode').then(m => m.initDarkMode());
}

// Mobile menu (split chunk)
if (document.getElementById('mobileMenuToggle')) {
  import('./modules/mobile-menu').then(m => m.initMobileMenu());
}

// Progressive enhancement: lazy loading images
document.querySelectorAll('img[loading="lazy"]').forEach(img => {
  img.decoding = 'async';
});

// Quick View (split chunk)
if (document.getElementById('qv-modal')) {
  import('./modules/quick-view').then(m => m.initQuickView());
}

// Aquatic hero (split chunk)
if (document.getElementById('aquatic-canvas')) {
  import('./modules/aquatic-hero').then(m => m.initAquaticHero());
}

// D3 sparklines (split chunk)
if (document.querySelector('[data-sparkline]')) {
  import('./modules/d3-sparkline').then(m => m.initSparklines());
}
