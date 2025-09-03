import './styles/theme.scss';

(function () {
  // Progressive enhancement: minimal JS boot
  document.documentElement.classList.add('js');
  const root = document.documentElement;
  const key = 'aqlx-theme';
  const apply = (mode) => {
    if (mode === 'dark') {
      root.classList.add('dark');
    } else {
      root.classList.remove('dark');
    }
  };
  apply(localStorage.getItem(key));
  const btn = document.getElementById('aqlx-dark-toggle');
  if (btn) {
    btn.addEventListener('click', () => {
      const next = root.classList.contains('dark') ? 'light' : 'dark';
      localStorage.setItem(key, next);
      apply(next);
      btn.setAttribute('aria-pressed', String(next === 'dark'));
    });
  }
})();
