/* AquaLuxe Dark Mode Toggle
 * Persist user preference in localStorage and reflect as .dark on <html>.
 * Also updates aria-pressed for accessibility.
 */
(function () {
  function setTheme(theme) {
    try {
      const d = document.documentElement;
      if (theme === 'dark') {
        d.classList.add('dark');
      } else {
        d.classList.remove('dark');
      }
      localStorage.setItem('aqualuxe:theme', theme);
      const btn = document.querySelector('[data-dark-toggle]');
      if (btn) {
        const isDark = theme === 'dark';
        btn.setAttribute('aria-pressed', String(isDark));
        btn.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
      }

      // Optional: toggle header icons if present
      const moon = document.getElementById('moon-icon');
      const sun = document.getElementById('sun-icon');
      if (moon && sun) {
        if (theme === 'dark') {
          moon.classList.add('hidden');
          sun.classList.remove('hidden');
        } else {
          moon.classList.remove('hidden');
          sun.classList.add('hidden');
        }
      }
    } catch (_) {
      // noop
    }
  }

  function getTheme() {
    try {
      const v = localStorage.getItem('aqualuxe:theme');
      if (v) return v;
      const m = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      return m ? 'dark' : 'light';
    } catch (_) {
      return 'light';
    }
  }

  function init() {
    const current = getTheme();
    setTheme(current);
    const btn = document.querySelector('[data-dark-toggle]');
    if (!btn) return;
    btn.addEventListener('click', function () {
      const next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
      setTheme(next);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
