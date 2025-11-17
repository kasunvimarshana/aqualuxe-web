'use strict';

(function(){
  if (!window.AquaLuxeWoo) return;

  // Quick View
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-quickview]');
    if (!btn) return;
    e.preventDefault();
    const id = btn.getAttribute('data-product-id');
    const res = await fetch(`${AquaLuxeWoo.ajax_url}?action=aqualuxe_quickview&id=${id}&_ajax_nonce=${AquaLuxeWoo.nonce}`);
    const json = await res.json();
    if (json.success) {
      showModal(json.data.html);
    }
  });

  function showModal(html){
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4';
    overlay.innerHTML = `<div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl max-h-[90vh] overflow-auto">${html}</div>`;
    overlay.addEventListener('click', (e)=>{ if (e.target === overlay) overlay.remove(); });
    document.body.appendChild(overlay);
  }

  // Wishlist (localStorage + optional server persistence)
  const WKEY = 'aqualuxe:wishlist';
  document.addEventListener('click', async (e)=>{
    const btn = e.target.closest('[data-wishlist-toggle]');
    if (!btn) return;
    e.preventDefault();
    const id = btn.getAttribute('data-product-id');
    const list = new Set(JSON.parse(localStorage.getItem(WKEY) || '[]'));
    if (list.has(id)) list.delete(id); else list.add(id);
    localStorage.setItem(WKEY, JSON.stringify(Array.from(list)));
    btn.classList.toggle('opacity-60');

    // Server persist if available
    if (AquaLuxeWoo.wishlist_ajax && AquaLuxeWoo.wishlist_nonce) {
      try {
        const form = new URLSearchParams();
        form.set('id', id);
        form.set('_ajax_nonce', AquaLuxeWoo.wishlist_nonce);
        await fetch(AquaLuxeWoo.wishlist_ajax, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: form.toString() });
      } catch(_){}
    }
  });
})();
