import './styles/theme.scss';

(function () {
  // Progressive enhancement: minimal JS boot
  const doc = document.documentElement;
  doc.classList.add('js');

  const key = 'aqlx-theme';
  const prefersDark = () =>
    typeof window.matchMedia === 'function' && window.matchMedia('(prefers-color-scheme: dark)').matches;

  const apply = (mode) => {
    if (mode === 'dark') {
      doc.classList.add('dark');
    } else {
      doc.classList.remove('dark');
    }
  };

  const saved = localStorage.getItem(key);
  const def = (window.AQLX && window.AQLX.darkModeDefault) || 'system';
  let initial;
  if (saved === 'dark' || saved === 'light') {
    initial = saved;
  } else if (def === 'dark' || def === 'light') {
    initial = def;
  } else {
    initial = prefersDark() ? 'dark' : 'light';
  }
  apply(initial);

  // React to system changes when using system default and no saved preference
  if (!saved && def === 'system' && typeof window.matchMedia === 'function') {
    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    const onChange = (e) => apply(e.matches ? 'dark' : 'light');
    try { mq.addEventListener('change', onChange); } catch (_) { mq.addListener(onChange); }
  }

  const btn = document.getElementById('aqlx-dark-toggle');
  if (btn) {
    btn.setAttribute('aria-pressed', String(doc.classList.contains('dark')));
    btn.addEventListener('click', () => {
      const next = doc.classList.contains('dark') ? 'light' : 'dark';
      localStorage.setItem(key, next);
      apply(next);
      btn.setAttribute('aria-pressed', String(next === 'dark'));
    });
  }
})();
