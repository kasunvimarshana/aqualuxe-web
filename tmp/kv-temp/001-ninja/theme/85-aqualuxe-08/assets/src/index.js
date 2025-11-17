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

  // Woo Extras: Quick View modal and Wishlist toggle
  const on = (root, event, selector, handler) => {
    root.addEventListener(event, (e) => {
      const target = e.target.closest(selector);
      if (target) handler(e, target);
    });
  };

  const ajax = async (params) => {
    const url = (window.AQLX && AQLX.ajaxUrl) || '/wp-admin/admin-ajax.php';
    const body = new URLSearchParams({ ...params, nonce: (window.AQLX && AQLX.nonce) || '' });
    const res = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body });
    return res.json();
  };

  const closeModal = (overlay) => {
    overlay.remove();
    doc.classList.remove('aqlx-modal-open');
  };

  const openModal = (html) => {
    const overlay = document.createElement('div');
    overlay.className = 'aqlx-modal-overlay';
    overlay.innerHTML = '<div class="aqlx-modal"><button class="aqlx-modal__close" aria-label="Close">×</button><div class="aqlx-modal__body"></div></div>';
    overlay.querySelector('.aqlx-modal__body').innerHTML = html;
    document.body.appendChild(overlay);
    doc.classList.add('aqlx-modal-open');
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay || e.target.closest('.aqlx-modal__close')) closeModal(overlay);
    });
    document.addEventListener('keydown', function esc(e){ if (e.key === 'Escape') { closeModal(overlay); document.removeEventListener('keydown', esc); } });
  };

  // Quick View
  on(document, 'click', '.aqlx-quick-view', async (e, el) => {
    const pid = el.getAttribute('data-product');
    try {
      const json = await ajax({ action: 'aqlx_quick_view', product: pid });
      if (json && json.success && json.data && json.data.html) {
        openModal(json.data.html);
      }
    } catch (_) {}
  });

  // Wishlist toggle (guests use localStorage)
  const getWish = () => {
    try { return JSON.parse(localStorage.getItem('aqlx-wishlist') || '[]'); } catch { return []; }
  };
  const setWish = (arr) => { try { localStorage.setItem('aqlx-wishlist', JSON.stringify(arr)); } catch {} };

  const updateBadge = (count) => {
    const el = document.querySelector('.aqlx-wishlist-count');
    if (el) { el.textContent = String(count || 0); }
  };

  // Initialize count on load
  if (window.AQLX && typeof AQLX.wishlistCount === 'number') {
    updateBadge(AQLX.wishlistCount);
  } else {
    updateBadge(getWish().length);
  }

  on(document, 'click', '.aqlx-wishlist-toggle', async (e, el) => {
    const pid = parseInt(el.getAttribute('data-product'), 10);
    if (!pid) return;
    if (typeof window.wp !== 'undefined' && (window.AQLX && AQLX.nonce)) {
      try {
        const json = await ajax({ action: 'aqlx_toggle_wishlist', product: String(pid) });
        if (json && json.success) {
          el.classList.toggle('is-active', json.data && json.data.state === 'added');
          el.setAttribute('aria-pressed', String(el.classList.contains('is-active')));
      if (json.data && typeof json.data.count === 'number') updateBadge(json.data.count);
        }
      } catch (_) {}
    } else {
      const list = getWish();
      const i = list.indexOf(pid);
      if (i >= 0) { list.splice(i, 1); el.classList.remove('is-active'); }
      else { list.push(pid); el.classList.add('is-active'); }
      setWish(list);
      el.setAttribute('aria-pressed', String(el.classList.contains('is-active')));
    updateBadge(list.length);
    }
  });
})();
