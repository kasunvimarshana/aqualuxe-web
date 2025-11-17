(function(){
  const $ = (sel, root=document) => root.querySelector(sel);
  const $$ = (sel, root=document) => Array.from(root.querySelectorAll(sel));
  const app = $('#aqlxdi-app'); if (!app) return;
  const selected = () => $$('.aqlxdi .aqlxdi-controls input[type="checkbox"]:checked').map(i=>i.getAttribute('data-entity'));
  const status = $('#aqlxdi-status'); const bar = $('#aqlxdi-progress'); const auditLink = $('#aqlxdi-audit');

  function setProgress(p){ bar.style.width = (p||0) + '%'; }
  function showStatus(s){ status.textContent = s; }

  async function call(path, method='GET', body){
    const res = await fetch(AQLXDI.rest + path, { method, headers: { 'Content-Type':'application/json', 'X-WP-Nonce': AQLXDI.nonce }, body: body?JSON.stringify(body):undefined });
    if (!res.ok) { const t = await res.text(); throw new Error(t||('HTTP '+res.status)); }
    return res.json();
  }

  let running = false, timer = 0;
  async function tick(){
    if (!running) return;
    try {
      const st = await call('step', 'POST', {});
      setProgress(st.progress); showStatus(`Step: ${st.step||'-'} | ${st.progress}%`);
      if (st.audit_url) { auditLink.href = st.audit_url; auditLink.style.display = 'inline'; }
      if (!st.done) { timer = setTimeout(tick, 500); } else { running = false; showStatus('Done'); }
    } catch (e) { running = false; showStatus('Error: ' + e.message); }
  }

  $('#aqlxdi-start').addEventListener('click', async ()=>{
    try {
      const body = { entities: selected(), volume: parseInt($('#aqlxdi-volume').value,10)||10, policy: $('#aqlxdi-policy').value, locale: $('#aqlxdi-locale').value, provider: $('#aqlxdi-provider').value };
      const r = await call('start','POST', body);
      if (r && r.state && r.state.audit_url) { auditLink.href = r.state.audit_url; auditLink.style.display = 'inline'; }
      running = true; setProgress(0); showStatus('Starting...'); tick();
    } catch(e){ showStatus('Error: ' + e.message); }
  });

  $('#aqlxdi-preview').addEventListener('click', async ()=>{
    try { const r = await call('preview','POST',{ entities: selected() }); showStatus('Preview OK: ' + JSON.stringify(r.entities)); }
    catch(e){ showStatus('Error: ' + e.message); }
  });

  $('#aqlxdi-export').addEventListener('click', async ()=>{
    try { const r = await call('export','POST',{}); showStatus('Export OK'); }
    catch(e){ showStatus('Error: ' + e.message); }
  });

  $('#aqlxdi-schedule').addEventListener('click', async ()=>{
    try {
      const body = { entities: selected(), volume: parseInt($('#aqlxdi-volume').value,10)||10, policy: $('#aqlxdi-policy').value, locale: $('#aqlxdi-locale').value, provider: $('#aqlxdi-provider').value };
      await call('schedule','POST', body); showStatus('Scheduled daily run');
    } catch(e){ showStatus('Error: ' + e.message); }
  });
  $('#aqlxdi-schedule-clear').addEventListener('click', async ()=>{
    try { await call('schedule/clear','POST',{}); showStatus('Schedule cleared'); }
    catch(e){ showStatus('Error: ' + e.message); }
  });

  $('#aqlxdi-pause').addEventListener('click', async ()=>{ try { await call('pause','POST',{}); running=false; showStatus('Paused'); } catch(e){ showStatus('Error: ' + e.message); } });
  $('#aqlxdi-resume').addEventListener('click', async ()=>{ try { await call('resume','POST',{}); if (!running){ running=true; tick(); } } catch(e){ showStatus('Error: ' + e.message); } });
  $('#aqlxdi-cancel').addEventListener('click', async ()=>{ try { await call('cancel','POST',{}); running=false; setProgress(0); showStatus('Cancelled and rolled back'); } catch(e){ showStatus('Error: ' + e.message); } });
  $('#aqlxdi-flush').addEventListener('click', async ()=>{ if (!confirm('This will wipe content. Continue?')) return; try { await call('flush','POST',{}); running=false; setProgress(0); showStatus('Flushed'); } catch(e){ showStatus('Error: ' + e.message); } });
})();
