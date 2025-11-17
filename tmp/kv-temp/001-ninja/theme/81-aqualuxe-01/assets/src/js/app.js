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
      root.innerHTML = items.map(it => `<a class="ax-card p-3" href="${it.permalink}"><img alt="" src="${it.thumb}" class="w-full h-32 object-cover"/><div class="mt-2">${it.title}</div></a>`).join('');
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
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-qv]');
    if (btn) {
      const id = btn.getAttribute('data-qv');
      fetch(`${AQUALUXE.rest}quick-view/${id}`).then(r=>r.json()).then(d=>{
        content.innerHTML = d.html || '<p>Preview unavailable.</p>';
        modal.classList.remove('hidden');
      }).catch(()=>{ content.innerHTML = '<p>Failed to load.</p>'; modal.classList.remove('hidden'); });
    }
    if (e.target.closest('.ax-qv-close') || e.target === modal) {
      modal.classList.add('hidden');
    }
  });
})();

// Wishlist toggle
(function(){
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-wishlist]');
    if (!btn) return;
    const id = parseInt(btn.getAttribute('data-wishlist'), 10);
    let ids = JSON.parse(localStorage.getItem('ax:wishlist')||'[]');
    if (ids.includes(id)) { ids = ids.filter(x=>x!==id); btn.setAttribute('aria-pressed','false'); }
    else { ids.push(id); btn.setAttribute('aria-pressed','true'); }
    localStorage.setItem('ax:wishlist', JSON.stringify(ids));
  });
})();
