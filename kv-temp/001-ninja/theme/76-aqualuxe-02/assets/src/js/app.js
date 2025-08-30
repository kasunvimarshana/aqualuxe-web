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

// Trade-in modal logic
const ti = document.getElementById('aqlx-tradein');
const tiOverlay = document.getElementById('aqlx-tradein-overlay');
const tiClose = document.getElementById('aqlx-tradein-close');
const tiCancel = document.getElementById('aqlx-tradein-cancel');
const tiProdId = document.getElementById('aqlx-tradein-product-id');
const tiProdName = document.getElementById('aqlx-tradein-product-name');

const openTI = (id, name) => {
  if (!ti) return;
  ti.classList.remove('hidden');
  ti.classList.add('flex');
  document.documentElement.classList.add('aqlx-modal-open');
  if (tiProdId) tiProdId.value = id || '';
  if (tiProdName) tiProdName.value = name || '';
};
const closeTI = () => {
  if (!ti) return;
  ti.classList.add('hidden');
  ti.classList.remove('flex');
  document.documentElement.classList.remove('aqlx-modal-open');
};

document.addEventListener('click', (e) => {
  const btn = e.target.closest('.aqlx-tradein-open');
  if (btn) {
    e.preventDefault();
    const id = btn.getAttribute('data-product-id');
    const name = btn.getAttribute('data-product-name');
    openTI(id, name);
  }
  if (e.target === tiOverlay || e.target === tiClose || e.target === tiCancel) {
    closeTI();
  }
});

// Lightweight PJAX for Woo archive filters/pagination
const shopGrid = document.getElementById('aqlx-shop-grid');
if (shopGrid) {
  document.addEventListener('click', async (e) => {
    const link = e.target.closest('a');
    if (!link) return;
    // Only intercept Woo widgets or pagination within shop area
    const isFilter = !!link.closest('#aqlx-shop-filters, .widget_layered_nav, .widget_price_filter, .woocommerce-widget-layered-nav');
    const isPaginate = !!link.closest('.woocommerce-pagination');
    if (!isFilter && !isPaginate) return;
    const url = new URL(link.href, window.location.origin);
    // Keep it same-origin and path
    if (url.origin !== window.location.origin) return;
    e.preventDefault();
    try {
      shopGrid.setAttribute('aria-busy', 'true');
      const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const text = await res.text();
      // Parse and extract new grid markup
      const doc = new DOMParser().parseFromString(text, 'text/html');
      const next = doc.getElementById('aqlx-shop-grid');
      const nextFilters = doc.getElementById('aqlx-shop-filters');
      if (next) {
        shopGrid.innerHTML = next.innerHTML;
        window.history.pushState({}, '', url.toString());
      }
      if (nextFilters) {
        const filters = document.getElementById('aqlx-shop-filters');
        if (filters) filters.innerHTML = nextFilters.innerHTML;
      }
    } catch (err) {
      // ignore
    } finally {
      shopGrid.setAttribute('aria-busy', 'false');
    }
  });
  // Handle back/forward
  window.addEventListener('popstate', async () => {
    try {
      shopGrid.setAttribute('aria-busy', 'true');
      const res = await fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const text = await res.text();
      const doc = new DOMParser().parseFromString(text, 'text/html');
      const next = doc.getElementById('aqlx-shop-grid');
      const nextFilters = doc.getElementById('aqlx-shop-filters');
      if (next) shopGrid.innerHTML = next.innerHTML;
      if (nextFilters) {
        const filters = document.getElementById('aqlx-shop-filters');
        if (filters) filters.innerHTML = nextFilters.innerHTML;
      }
    } catch (e) {
      // ignore
    } finally {
      shopGrid.setAttribute('aria-busy', 'false');
    }
  });
}

// B2B quote modal logic
const qm = document.getElementById('aqlx-quote');
const qmOverlay = document.getElementById('aqlx-quote-overlay');
const qmClose = document.getElementById('aqlx-quote-close');
const qmCancel = document.getElementById('aqlx-quote-cancel');
const qmProdId = document.getElementById('aqlx-quote-product-id');
const qmProdName = document.getElementById('aqlx-quote-product-name');

const openQM = (id, name) => {
  if (!qm) return;
  qm.classList.remove('hidden');
  qm.classList.add('flex');
  document.documentElement.classList.add('aqlx-modal-open');
  if (qmProdId) qmProdId.value = id || '';
  if (qmProdName) qmProdName.value = name || '';
};
const closeQM = () => {
  if (!qm) return;
  qm.classList.add('hidden');
  qm.classList.remove('flex');
  document.documentElement.classList.remove('aqlx-modal-open');
};

document.addEventListener('click', (e) => {
  const btn = e.target.closest('.aqlx-quote-open');
  if (btn) {
    e.preventDefault();
    const id = btn.getAttribute('data-product-id');
    const name = btn.getAttribute('data-product-name');
    openQM(id, name);
  }
  if (e.target === qmOverlay || e.target === qmClose || e.target === qmCancel) {
    closeQM();
  }
});

// Simple toast for ?sent=1 or ?error=...
const showToast = (msg, type = 'success') => {
  const wrap = document.createElement('div');
  wrap.className = 'fixed top-4 right-4 z-[100]';
  const bg = type === 'error' ? 'bg-red-600' : 'bg-emerald-600';
  wrap.innerHTML = `<div role="alert" class="${bg} text-white px-4 py-3 rounded shadow">${msg}</div>`;
  document.body.appendChild(wrap);
  setTimeout(() => wrap.remove(), 4000);
};
(() => {
  try {
    const url = new URL(window.location.href);
    const sent = url.searchParams.get('sent');
    const err = url.searchParams.get('error');
    if (sent === '1') {
      showToast('Thanks! Your request was sent.', 'success');
      url.searchParams.delete('sent');
      window.history.replaceState({}, '', url.toString());
    } else if (err) {
      showToast(err, 'error');
      url.searchParams.delete('error');
      window.history.replaceState({}, '', url.toString());
    }
  } catch (e) {
    // ignore
  }
})();
