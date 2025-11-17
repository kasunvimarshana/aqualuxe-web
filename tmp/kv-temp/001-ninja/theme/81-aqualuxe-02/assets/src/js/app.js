// Entry: app.js
import './modules/hero';

// Wishlist client rendering
(function(){
  const root = document.getElementById('ax-wishlist');
  if (!root) return;
  const ids = JSON.parse(localStorage.getItem('ax:wishlist')||'[]');
  if (!ids.length) { root.innerHTML = '<p class="opacity-70">Your wishlist is empty.</p>'; return; }
  fetch(`${AQUALUXE.rest}products?ids[]=` + ids.join('&ids[]='))
    .then(r=>r.json())
    .then(items=>{
      root.innerHTML = items.map(it => {
        const badge = (typeof it.inStock === 'boolean') ? `<span class="text-xs inline-block rounded px-2 py-0.5 ${it.inStock?'bg-green-600 text-white':'bg-rose-600 text-white'}">${it.inStock?AQUALUXE.i18n.in_stock:AQUALUXE.i18n.out_of_stock}</span>` : '';
        const price = (it.price && it.currency) ? `<div class="text-sm opacity-90 mt-1">${AQUALUXE.i18n.price}: ${it.price} ${it.currency}</div>` : '';
        return `<div class="ax-card p-3 block">
          <a href="${it.permalink}" class="block">
            <img alt="" src="${it.thumb}" class="w-full h-32 object-cover"/>
            <div class="mt-2 font-medium">${it.title}</div>
            <div class="mt-1 flex items-center gap-2">${badge}${price}</div>
          </a>
          <button class="mt-2 text-sm text-rose-700 hover:text-rose-800" data-wishlist-remove="${it.id}">${AQUALUXE.i18n.remove}</button>
        </div>`;
      }).join('');
      // Attach remove handlers
      root.querySelectorAll('[data-wishlist-remove]')?.forEach(btn=>{
        btn.addEventListener('click', (e)=>{
          const id = parseInt(btn.getAttribute('data-wishlist-remove'),10);
          let ids = JSON.parse(localStorage.getItem('ax:wishlist')||'[]');
          ids = ids.filter(x=>x!==id);
          localStorage.setItem('ax:wishlist', JSON.stringify(ids));
          // Try to sync to server if logged in
          if (AQUALUXE && AQUALUXE.loggedIn) {
            fetch(`${AQUALUXE.rest}wishlist`, {
              method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': AQUALUXE.nonce }, body: JSON.stringify({ ids })
            }).catch(()=>{});
          }
          // Remove from DOM
          const card = btn.closest('.ax-card');
          if (card) card.remove();
          if (!ids.length) { root.innerHTML = '<p class="opacity-70">Your wishlist is empty.</p>'; }
          // Sync any buttons elsewhere
          document.querySelectorAll(`[data-wishlist="${id}"]`)?.forEach(b=>b.setAttribute('aria-pressed','false'));
        });
      });
    })
    .catch(()=>{ root.innerHTML = '<p>Failed to load wishlist.</p>'; });
})();

// Featured grid fallback
(function(){
  const grid = document.getElementById('ax-featured');
  if (!grid) return;
  grid.innerHTML = '<div class="ax-card p-4">Premium Koi</div><div class="ax-card p-4">Rare Plants</div><div class="ax-card p-4">Bespoke Tanks</div><div class="ax-card p-4">Care Supplies</div>';
})();

// Quick View interactions
(function(){
  const modal = document.getElementById('ax-quick-view');
  if (!modal) return;
  const content = document.getElementById('ax-qv-content');
  let lastActive = null;
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-qv]');
    if (btn) {
      const id = btn.getAttribute('data-qv');
      lastActive = btn;
      fetch(`${AQUALUXE.rest}quick-view/${id}`).then(r=>r.json()).then(d=>{
        content.innerHTML = d.html || '<p>Preview unavailable.</p>';
        modal.classList.remove('hidden');
        // Move focus into modal
        setTimeout(()=>{
          const focusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
          (focusable||modal).focus && (focusable||modal).focus();
        }, 0);
      }).catch(()=>{ content.innerHTML = '<p>Failed to load.</p>'; modal.classList.remove('hidden'); });
    }
    if (e.target.closest('.ax-qv-close') || e.target === modal) {
      modal.classList.add('hidden');
      if (lastActive && lastActive.focus) lastActive.focus();
    }
  });
  document.addEventListener('keydown', (e)=>{
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      modal.classList.add('hidden');
      if (lastActive && lastActive.focus) lastActive.focus();
    }
  });
})();

// Wishlist toggle
(function(){
  // Initialize aria-pressed state for existing buttons
  function syncButtons(){
    const ids = new Set(JSON.parse(localStorage.getItem('ax:wishlist')||'[]'));
    document.querySelectorAll('[data-wishlist]')?.forEach(btn=>{
      const id = parseInt(btn.getAttribute('data-wishlist'), 10);
      btn.setAttribute('aria-pressed', ids.has(id) ? 'true' : 'false');
    });
  }
  document.addEventListener('DOMContentLoaded', syncButtons);

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-wishlist]');
    if (!btn) return;
    const id = parseInt(btn.getAttribute('data-wishlist'), 10);
    let ids = JSON.parse(localStorage.getItem('ax:wishlist')||'[]');
    if (ids.includes(id)) { ids = ids.filter(x=>x!==id); btn.setAttribute('aria-pressed','false'); }
    else { ids.push(id); btn.setAttribute('aria-pressed','true'); }
    localStorage.setItem('ax:wishlist', JSON.stringify(ids));
    // Try to sync to server if logged in
    if (AQUALUXE && AQUALUXE.loggedIn) {
      fetch(`${AQUALUXE.rest}wishlist`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': AQUALUXE.nonce },
        body: JSON.stringify({ ids })
      }).catch(()=>{});
    }
    // Update any other buttons representing same product
    syncButtons();
  });

  // On load, if logged in, load server wishlist and merge
  document.addEventListener('DOMContentLoaded', ()=>{
    if (!(AQUALUXE && AQUALUXE.loggedIn)) return;
    fetch(`${AQUALUXE.rest}wishlist`, { headers: { 'X-WP-Nonce': AQUALUXE.nonce }})
      .then(r=>r.ok?r.json():[])
      .then(serverIds=>{
        const local = JSON.parse(localStorage.getItem('ax:wishlist')||'[]');
        const merged = Array.from(new Set([ ...local, ...(Array.isArray(serverIds)?serverIds:[]) ]));
        localStorage.setItem('ax:wishlist', JSON.stringify(merged));
        // Push merged back to server to keep in sync
        return fetch(`${AQUALUXE.rest}wishlist`, {
          method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': AQUALUXE.nonce }, body: JSON.stringify({ ids: merged })
        });
      })
      .then(()=>{ try{ syncButtons(); }catch(e){} })
      .catch(()=>{});
  });
})();
