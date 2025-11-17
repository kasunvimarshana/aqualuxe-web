import { postJSON } from '../utils/http';

export function initDarkMode(root = document) {
  const btn = root.getElementById('alx-dark-toggle');
  if (!btn) return;
  const setState = (isDark) => {
    document.documentElement.classList.toggle('theme-dark', isDark);
    btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
  };
  const current = document.documentElement.classList.contains('theme-dark');
  setState(current);
  btn.addEventListener('click', async () => {
    const next = !document.documentElement.classList.contains('theme-dark');
    setState(next);
    try {
      if (window.__AQUALUXE__?.rest_url) {
        await postJSON(`${window.__AQUALUXE__.rest_url}/prefs`, { dark: next }, window.__AQUALUXE__.nonce);
      } else {
        document.cookie = `alx_dark=${next ? '1' : '0'};path=/;max-age=31536000`;
      }
    } catch (e) { /* silent */ }
  });
}
