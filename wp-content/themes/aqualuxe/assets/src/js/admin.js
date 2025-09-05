// AquaLuxe Admin scripts
document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('alx-importer');
  if (!root) return;

  const rest = root.getAttribute('data-rest');
  const nonce = root.getAttribute('data-nonce');
  const btnFlush = document.getElementById('alx-do-flush');
  const btnStart = document.getElementById('alx-start');
  const progress = document.getElementById('alx-progress');
  const status = document.getElementById('alx-status');

  const post = async (path, body) => {
    const res = await fetch(`${rest}${path}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': nonce },
      body: JSON.stringify(body || {})
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  };

  btnFlush?.addEventListener('click', async () => {
    try {
      status.textContent = 'Flushing…';
      const scope = {
        posts: document.getElementById('alx-flush-posts').checked,
        media: document.getElementById('alx-flush-media').checked,
        tax: document.getElementById('alx-flush-tax').checked,
      };
      const r = await post('/import/flush', scope);
      status.textContent = 'Flushed: ' + JSON.stringify(r.report || {});
    } catch (e) {
      status.textContent = 'Flush failed: ' + e.message;
    }
  });

  btnStart?.addEventListener('click', async () => {
    try {
      status.textContent = 'Starting import…';
      const volume = parseInt(document.getElementById('alx-volume').value, 10) || 20;
      const include_products = document.getElementById('alx-products').checked;
      const start = await post('/import/start', { volume, include_products });
      const total = start.job?.total || volume;
      progress.max = 100;
      progress.value = 0;

      const tick = async () => {
        const step = await post('/import/step');
        const done = step.job?.done || 0;
        const pct = Math.min(100, Math.round((done / total) * 100));
        progress.value = pct;
        status.textContent = `Imported ${done}/${total}`;
        if (!step.complete) {
          setTimeout(tick, 150);
        } else {
          status.textContent = 'Import complete';
        }
      };
      tick();
    } catch (e) {
      status.textContent = 'Import failed: ' + e.message;
    }
  });
});
