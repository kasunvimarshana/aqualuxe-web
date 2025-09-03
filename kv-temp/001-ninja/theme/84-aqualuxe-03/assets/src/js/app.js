/* Progressive enhancement bootstrap */
document.documentElement.classList.remove('no-js');

// Dark mode toggle with persistence
const THEME_KEY = 'aqlx-theme';
const body = document.body;
try {
  const saved = localStorage.getItem(THEME_KEY);
  if (saved === 'dark') body.classList.add('skin-dark');
  if (saved === 'default') body.classList.add('skin-default');
} catch {}
document.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-theme-toggle]');
  if (!btn) return;
  const isDark = body.classList.toggle('skin-dark');
  if (isDark) body.classList.remove('skin-default');
  try { localStorage.setItem(THEME_KEY, isDark ? 'dark' : 'default'); } catch {}
});

// Example: AJAX search enhancement with graceful fallback if fetch fails.
const searchForms = document.querySelectorAll('form[data-aqlx-search]');
searchForms.forEach((form) => {
  form.addEventListener('submit', async (e) => {
    const ajaxUrl = (window.Aqualuxe && window.Aqualuxe.ajaxUrl) || null;
    const nonce = (window.Aqualuxe && window.Aqualuxe.nonce) || null;
    if (!ajaxUrl || !nonce) return; // fallback to normal submit
    e.preventDefault();
    const fd = new FormData(form);
    fd.append('action', 'aqlx_action');
    fd.append('nonce', nonce);
    fd.append('subaction', 'search_listings');
    try {
      const res = await fetch(ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' });
      const data = await res.json();
      const out = form.querySelector('[data-aqlx-results]');
      if (data.success && out) {
        out.innerHTML = '';
        data.data.items.forEach((item) => {
          const li = document.createElement('li');
          li.innerHTML = `<a href="${item.link}">${item.title}</a>`;
          out.appendChild(li);
        });
      }
    } catch (err) {
      // fallback silently
      form.submit();
    }
  });
});
