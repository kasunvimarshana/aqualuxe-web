import './dark-mode';

// Quick View modal
(function(){
  const delegate = (e, selector, cb) => {
    const target = e.target.closest(selector);
    if (target) cb(target, e);
  };

  document.addEventListener('click', async (e) => {
    delegate(e, '[data-aqlx-quickview]', async (btn) => {
      e.preventDefault();
      const pid = btn.getAttribute('data-product-id');
      if (!pid) return;
      try {
        const res = await fetch(AquaLuxe.ajaxUrl + '?action=aqlx_quick_view&pid=' + encodeURIComponent(pid), {
          headers: { 'X-WP-Nonce': AquaLuxe.nonce }
        });
        const html = await res.text();
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4';
        modal.innerHTML = '<div class="bg-white dark:bg-slate-800 rounded-lg max-w-2xl w-full p-4 relative">' + html + '<button class="absolute top-2 right-2" aria-label="Close">✕</button></div>';
        document.body.appendChild(modal);
        modal.addEventListener('click', (ev)=>{ if (ev.target === modal || ev.target.closest('button[aria-label="Close"]')) modal.remove(); });
      } catch(err) { console.error(err); }
    });

    // Wishlist toggle
    delegate(e, '[data-aqlx-wishlist]', (btn) => {
      e.preventDefault();
      const pid = btn.getAttribute('data-product-id');
      if (!pid) return;
      const key = 'aqlx_wishlist';
      const list = new Set(JSON.parse(localStorage.getItem(key) || '[]'));
      if (list.has(pid)) { list.delete(pid); btn.setAttribute('aria-pressed','false'); btn.innerText=AquaLuxe.i18n.removedFromWishlist; }
      else { list.add(pid); btn.setAttribute('aria-pressed','true'); btn.innerText=AquaLuxe.i18n.addedToWishlist; }
      localStorage.setItem(key, JSON.stringify([...list]));
    });
  });
})();
