// Lightweight analytics hooks (no external services). Example: log CTA clicks.
(function(){
  function send(event, payload){
    try { window.dispatchEvent(new CustomEvent('alx:analytics', { detail: { event, payload, ts: Date.now() } })); } catch(e){}
  }
  document.addEventListener('click', e => {
    const cta = e.target.closest('[data-cta]'); if(!cta) return;
    send('cta_click', { id: cta.getAttribute('data-cta'), href: cta.getAttribute('href') });
  });
})();
