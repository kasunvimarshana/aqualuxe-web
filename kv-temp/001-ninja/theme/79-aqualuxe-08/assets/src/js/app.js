/* AquaLuxe main JS */

// Dark mode toggle with persistent cookie
export function toggleDarkMode(force) {
  const isDark = typeof force === 'boolean' ? force : !document.documentElement.classList.contains('dark');
  document.documentElement.classList.toggle('dark', isDark);
  const expiry = new Date(Date.now() + 365*24*60*60*1000).toUTCString();
  document.cookie = 'aqlx_dm=' + (isDark ? '1' : '0') + '; path=/; SameSite=Lax; expires=' + expiry;
}

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.querySelector('[data-aqlx-toggle-dark]');
  if (btn) btn.addEventListener('click', () => toggleDarkMode());
});

// Lazy load images (native fallback)
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('img[loading="lazy"]').forEach(img => {
    // noop for modern browsers
  });
});
