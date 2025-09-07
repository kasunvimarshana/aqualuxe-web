export function initDarkMode() {
  const toggle = document.getElementById('darkModeToggle');
  if (!toggle) return;
  const setPressed = (on) => toggle.setAttribute('aria-pressed', on ? 'true' : 'false');
  const hasDark = () => document.documentElement.classList.contains('dark');
  const persist = (on) => { try { localStorage.setItem('aqualuxe:theme', on ? 'dark' : 'light'); } catch (e) {} };
  setPressed(hasDark());
  toggle.addEventListener('click', () => {
    const on = !hasDark();
    document.documentElement.classList.toggle('dark', on);
    setPressed(on);
    persist(on);
  });
}
