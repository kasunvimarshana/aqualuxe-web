import '../css/app.css';

// Dark mode toggle with persistent preference
const toggle = document.getElementById('darkModeToggle');
if (toggle) {
  const setPressed = (on) => toggle.setAttribute('aria-pressed', on ? 'true' : 'false');
  const apply = (mode) => {
    const root = document.documentElement;
    if (mode === 'dark') {
      root.classList.add('dark');
      setPressed(true);
    } else {
      root.classList.remove('dark');
      setPressed(false);
    }
  };
  try {
    const pref = localStorage.getItem('aqualuxe:theme');
    apply(pref);
  } catch (e) {}
  toggle.addEventListener('click', () => {
    const isDark = document.documentElement.classList.toggle('dark');
    try { localStorage.setItem('aqualuxe:theme', isDark ? 'dark' : 'light'); } catch (e) {}
    setPressed(isDark);
  });
}

// Progressive enhancement: lazy loading images
document.querySelectorAll('img[loading="lazy"]').forEach(img => {
  img.decoding = 'async';
});
