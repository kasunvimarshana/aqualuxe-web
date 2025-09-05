document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('aqlx-importer');
  if (!el) return;
  el.innerHTML = `
    <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
      <button id="aqlx-run" class="button button-primary">Import Demo</button>
      <button id="aqlx-flush" class="button">Flush Demo</button>
    </div>
    <div id="aqlx-progress" style="height:8px;background:#eee;border-radius:4px;overflow:hidden">
      <div id="aqlx-bar" style="height:8px;width:0;background:#0ea5e9;"></div>
    </div>
    <pre id="aqlx-log" style="margin-top:8px;max-height:220px;overflow:auto;background:#fafafa;border:1px solid #eee;padding:8px"></pre>
  `;
  const bar = document.getElementById('aqlx-bar');
  const log = document.getElementById('aqlx-log');

  function post(action) {
    const form = new FormData();
    form.append('action', action);
    form.append('nonce', (window.AQLX && AQLX.nonce) || '');
    fetch((window.ajaxurl) || (window.AQLX && AQLX.ajaxUrl) || '/wp-admin/admin-ajax.php', {
      method: 'POST',
      credentials: 'same-origin',
      body: form
    }).then(r => r.json()).then(d => {
      if (d && d.success) {
        if (d.data && typeof d.data.progress === 'number') {
          bar.style.width = d.data.progress + '%';
        }
        log.textContent += `\n` + (d.data && d.data.message ? d.data.message : 'Done');
      } else {
        log.textContent += `\nError`;
      }
    }).catch(e => {
      log.textContent += `\n` + e.message;
    });
  }

  document.getElementById('aqlx-run').addEventListener('click', () => post('aqlx_import_demo'));
  document.getElementById('aqlx-flush').addEventListener('click', () => post('aqlx_flush_demo'));
});
