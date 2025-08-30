// Dark mode preference persistence
(function(){
  const key = 'aqlx_theme';
  const root = document.documentElement; // html element
  const setMode = (mode) => {
    if (mode === 'dark') root.classList.add('dark');
    else root.classList.remove('dark');
    localStorage.setItem(key, mode);
  };
  const detect = () => localStorage.getItem(key) || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  setMode(detect());
  document.addEventListener('click', (e)=>{
    const btn = e.target.closest('[data-aqlx-toggle-dark]');
    if (!btn) return;
    const next = root.classList.contains('dark') ? 'light' : 'dark';
    setMode(next);
  });
})();
