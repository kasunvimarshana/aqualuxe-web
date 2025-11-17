/* AquaLuxe App JS - no globals, modular, progressive enhancement */
(function () {
  const docEl = document.documentElement;
  const body = document.body;

  // Dark mode persistence via localStorage; sync with cookie-based PHP fallback.
  try {
    const pref = localStorage.getItem('alx_dark');
    if (pref === '1') {
      body.classList.add('theme-dark');
      document.cookie = 'alx_dark=1; path=/; SameSite=Lax; max-age=31536000';
    }
  } catch (_) {}

  const toggle = document.getElementById('alx-dark-toggle');
  if (toggle) {
    toggle.addEventListener('click', function (e) {
      if (toggle.hasAttribute('href')) {
        // Allow PHP fallback when JS disabled.
      }
      e.preventDefault();
      const isDark = body.classList.toggle('theme-dark');
      try {
        localStorage.setItem('alx_dark', isDark ? '1' : '0');
        document.cookie = `alx_dark=${isDark ? '1' : '0'}; path=/; SameSite=Lax; max-age=31536000`;
      } catch (_) {}
    });
  }

  // Quick view & wishlist (if WooCommerce active, otherwise no-op)
  const rest = (window.AquaLuxe && window.AquaLuxe.restUrl) || '';
  const nonce = (window.AquaLuxe && window.AquaLuxe.nonce) || '';
  function delegate(selector, type, handler) {
    document.addEventListener(type, (e) => {
      const el = e.target.closest(selector);
      if (el) handler(e, el);
    });
  }
  async function get(path) {
    const r = await fetch(`${rest}${path}`, { headers: { 'X-WP-Nonce': nonce } });
    return r.json();
  }
  async function post(path, body) {
    const r = await fetch(`${rest}${path}`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': nonce }, body: JSON.stringify(body || {}) });
    return r.json();
  }
  delegate('.alx-quickview', 'click', async (e, el) => {
    e.preventDefault();
    const id = el.getAttribute('data-product');
    try {
      const res = await get(`/quickview?id=${encodeURIComponent(id)}`);
      if (res.ok && res.html) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50';
        modal.innerHTML = `<div role="dialog" aria-modal="true" class="bg-white dark:bg-slate-900 rounded shadow max-w-lg w-full">${res.html}<button class="alx-close m-3">Close</button></div>`;
        document.body.appendChild(modal);
        modal.querySelector('.alx-close').addEventListener('click', () => modal.remove());
        modal.addEventListener('click', (ev) => { if (ev.target === modal) modal.remove(); });
      }
    } catch (_) {}
  });
  delegate('.alx-wishlist', 'click', async (e, el) => {
    e.preventDefault();
    const id = parseInt(el.getAttribute('data-product'), 10);
    try {
      const res = await post('/wishlist', { id });
      el.textContent = res.action === 'added' ? 'Wishlisted' : 'Wishlist';
    } catch (_) {}
  });
})();
