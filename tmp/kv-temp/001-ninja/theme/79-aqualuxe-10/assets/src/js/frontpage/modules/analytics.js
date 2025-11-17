// Lightweight analytics hooks (privacy-respecting)
function send(data) {
  try {
    const base = (window.AQUALUXE_SETTINGS && window.AQUALUXE_SETTINGS.restUrl) ? window.AQUALUXE_SETTINGS.restUrl.replace(/\/$/, '') : `${window.location.origin}/wp-json`;
    const url = `${base}/aqlx/v1/analytics`;
    const payload = JSON.stringify({ event: data });
    if (navigator.sendBeacon) {
      const blob = new Blob([payload], { type: 'application/json' });
      navigator.sendBeacon(url, blob);
    } else {
      const headers = { 'Content-Type': 'application/json' };
      if (window.AQUALUXE_SETTINGS && window.AQUALUXE_SETTINGS.restNonce) { headers['X-WP-Nonce'] = window.AQUALUXE_SETTINGS.restNonce; }
      fetch(url, { method: 'POST', headers, body: payload, keepalive: true }).catch(()=>{});
    }
  } catch (_) {}
}

export function hookCtaClicks(root = document) {
  root.addEventListener('click', (e) => {
    const a = e.target.closest('[data-cta]');
    if (!a) return;
    const name = a.getAttribute('data-cta') || 'cta';
  send({ type: 'cta_click', name, ts: Date.now() });
  });
}
