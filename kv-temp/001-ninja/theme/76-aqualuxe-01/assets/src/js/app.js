import '../scss/app.scss';

// Dark mode toggle with cookie persistence using REST endpoint
const toggle = document.getElementById('aqlx-dark-toggle');
if (toggle) {
  toggle.addEventListener('click', async () => {
    const isDark = document.documentElement.classList.toggle('dark');
    try {
      await fetch((window.AquaLuxe && window.AquaLuxe.restUrl ? window.AquaLuxe.restUrl : '/wp-json/aqualuxe/v1') + '/dark-mode', {
        method: 'POST',
        headers: { 'X-WP-Nonce': (window.AquaLuxe ? window.AquaLuxe.nonce : '') },
        body: new URLSearchParams({ enabled: isDark ? '1' : '0' })
      });
    } catch (e) {
      // noop
    }
  });
}

// Accessibility: focus outline only when keyboard navigating
document.body.addEventListener('mousedown', () => {
  document.body.classList.add('using-mouse');
});
document.body.addEventListener('keydown', (e) => {
  if (e.key === 'Tab') document.body.classList.remove('using-mouse');
});

// Wishlist buttons
const onWishlistClick = async (btn) => {
  const id = btn.getAttribute('data-product-id');
  if (!id) return;
  try {
    const url = (window.AquaLuxe && window.AquaLuxe.restUrl ? window.AquaLuxe.restUrl : '/wp-json/aqualuxe/v1') + '/wishlist';
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'X-WP-Nonce': (window.AquaLuxe ? window.AquaLuxe.nonce : '') },
      body: new URLSearchParams({ id, action: 'toggle' })
    });
    const data = await res.json();
    btn.classList.toggle('text-red-600');
  } catch (e) {
    // ignore
  }
};

document.addEventListener('click', (e) => {
  const target = e.target.closest('.aqlx-wishlist-btn');
  if (target) {
    e.preventDefault();
    onWishlistClick(target);
  }
});

// Quick View
const qvOverlay = document.getElementById('aqlx-qv-overlay');
const qv = document.getElementById('aqlx-qv');
const qvContent = document.getElementById('aqlx-qv-content');
const qvClose = document.getElementById('aqlx-qv-close');

const openQV = async (id) => {
  if (!qv || !qvOverlay || !qvContent) return;
  qv.classList.remove('hidden');
  qv.classList.add('flex');
  qvOverlay.classList.remove('hidden');
  document.documentElement.classList.add('aqlx-modal-open');
  try {
    const url = (window.AquaLuxe && window.AquaLuxe.restUrl ? window.AquaLuxe.restUrl : '/wp-json/aqualuxe/v1') + '/quick-view?id=' + encodeURIComponent(id);
    const res = await fetch(url);
    const data = await res.json();
    if (data && data.html) qvContent.innerHTML = data.html;
  } catch (e) {
    qvContent.innerHTML = '<p>Failed to load.</p>';
  }
};

const closeQV = () => {
  if (!qv || !qvOverlay) return;
  qv.classList.add('hidden');
  qv.classList.remove('flex');
  qvOverlay.classList.add('hidden');
  document.documentElement.classList.remove('aqlx-modal-open');
};

document.addEventListener('click', (e) => {
  const btn = e.target.closest('.aqlx-quick-view');
  if (btn) {
    e.preventDefault();
    const id = btn.getAttribute('data-product-id');
    if (id) openQV(id);
  }
  if (e.target === qvOverlay || e.target === qvClose) {
    closeQV();
  }
});
