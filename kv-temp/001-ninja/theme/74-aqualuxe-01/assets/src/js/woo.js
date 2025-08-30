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

  // Wishlist (localStorage only)
  const WKEY = 'aqualuxe:wishlist';
  document.addEventListener('click', (e)=>{
    const btn = e.target.closest('[data-wishlist-toggle]');
    if (!btn) return;
    e.preventDefault();
    const id = btn.getAttribute('data-product-id');
    const list = new Set(JSON.parse(localStorage.getItem(WKEY) || '[]'));
    if (list.has(id)) list.delete(id); else list.add(id);
    localStorage.setItem(WKEY, JSON.stringify(Array.from(list)));
    btn.classList.toggle('opacity-60');
  });
})();
