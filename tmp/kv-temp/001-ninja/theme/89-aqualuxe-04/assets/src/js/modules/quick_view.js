export function initQuickView(root = document) {
  root.querySelectorAll('[data-alx-quick-view]')?.forEach(el => {
    el.addEventListener('click', async (e) => {
      e.preventDefault();
      const id = el.getAttribute('data-product-id');
      if (!id) return;
      const res = await fetch(`${window.__AQUALUXE__?.ajax_url}?action=alx_quick_view&id=${id}`);
      const json = await res.json();
      if (json?.success) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50';
        modal.innerHTML = `<div class="bg-white max-w-lg w-full rounded shadow">${json.data.html}<button aria-label="Close" class="m-2 px-3 py-1 border rounded alx-close">Close</button></div>`;
        modal.addEventListener('click', (ev) => {
          if (ev.target === modal || ev.target.classList.contains('alx-close')) modal.remove();
        });
        document.body.appendChild(modal);
      }
    });
  });
}
