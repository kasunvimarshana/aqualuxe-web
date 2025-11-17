// Tailwind base styles are imported via CSS. Here add enhancements.

// Dark mode toggle persistence
(function () {
  const btn = document.querySelector('.aqlx-dark-toggle');
  if (!btn) return;
  const root = document.documentElement;
  const key = 'aqlx:dark';
  const apply = (on) => {
    root.classList.toggle('dark', !!on);
    if (btn) btn.setAttribute('aria-pressed', String(!!on));
  };
  const saved = localStorage.getItem(key);
  const prefers = window.matchMedia('(prefers-color-scheme: dark)').matches;
  apply(saved ? saved === '1' : prefers);
  btn.addEventListener('click', () => {
    const isDark = root.classList.toggle('dark');
    localStorage.setItem(key, isDark ? '1' : '0');
    btn.setAttribute('aria-pressed', String(isDark));
  });
})();

// Progressive enhancement: prevent focus trap issues
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    // Close any open overlays in modules (placeholder)
  }
});

// Lazy-load ocean background on front page if canvas exists
document.addEventListener('DOMContentLoaded', async () => {
  const canvas = document.getElementById('aqlx-ocean');
  if (!canvas) return;
  const { mountOcean } = await import('./experience');
  mountOcean(canvas);
});
