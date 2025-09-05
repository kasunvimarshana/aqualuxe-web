function getFocusable(root){
  return Array.from(root.querySelectorAll(
    'a[href], area[href], input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])'
  )).filter(el => el.offsetParent !== null || el === document.activeElement);
}

export function initQuickView(){
  const qvModal = document.getElementById('qv-modal');
  const qvBackdrop = document.getElementById('qv-backdrop');
  const qvContent = document.getElementById('qv-content');
  const qvClose = document.getElementById('qv-close');
  const a11yLive = document.getElementById('a11y-live');
  let lastFocus = null;
  if (!qvModal || !qvBackdrop || !qvContent) return;

  const isOpen = () => !qvModal.hasAttribute('hidden');
  const setLive = (msg) => { if (a11yLive) a11yLive.textContent = msg; };
  const open = (html='') => {
    qvContent.innerHTML = html;
    qvContent.setAttribute('aria-busy', 'false');
    qvModal.removeAttribute('hidden');
    qvBackdrop.removeAttribute('hidden');
    qvModal.classList.add('open');
    qvBackdrop.classList.add('open');
    lastFocus = document.activeElement;
    const focusables = getFocusable(qvModal);
    (focusables[0] || qvClose || qvModal).focus();
    setLive('Quick view opened');
  };
  const hide = () => {
    qvModal.classList.remove('open');
    qvBackdrop.classList.remove('open');
    qvModal.setAttribute('hidden','');
    qvBackdrop.setAttribute('hidden','');
    setLive('Quick view closed');
    if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
  };

  qvClose && qvClose.addEventListener('click', hide);
  qvBackdrop && qvBackdrop.addEventListener('click', hide);
  document.addEventListener('keydown', (e) => {
    if (!isOpen()) return;
    if (e.key === 'Escape') { e.stopPropagation(); hide(); }
    if (e.key === 'Tab') {
      const focusables = getFocusable(qvModal);
      if (focusables.length === 0) { e.preventDefault(); (qvClose || qvModal).focus(); return; }
      const [first, last] = [focusables[0], focusables[focusables.length - 1]];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-qv-id]');
    if (!trigger) return;
    e.preventDefault();
    const id = trigger.getAttribute('data-qv-id');
    const urlBase = (window.AQUALUXE?.restUrl || '').replace(/\/$/, '');
    const url = urlBase + '/quickview/' + encodeURIComponent(id);
    qvContent.setAttribute('aria-busy','true');
    setLive('Loading product details…');
    fetch(url, { headers: { 'X-WP-Nonce': window.AQUALUXE?.nonce || '' }})
      .then(r => r.json())
      .then(({ html }) => open(html || ''))
      .catch(() => { setLive('Failed to load product details'); open('<div class="p-6">Error</div>'); });
  });
}
