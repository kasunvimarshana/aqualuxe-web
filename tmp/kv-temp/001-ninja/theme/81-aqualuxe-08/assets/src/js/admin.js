(function(){
  function qs(sel, root=document){ return root.querySelector(sel); }
  function qsa(sel, root=document){ return Array.from(root.querySelectorAll(sel)); }
  function valCounts(){
    const counts = {};
    qsa('.ax-count').forEach(i=>{ counts[i.dataset.key] = parseInt(i.value || '0', 10); });
    return counts;
  }
  function selectedEntities(){ return qsa('.ax-entity:checked').map(i=>i.value); }
  function ajax(action, data){
    data = data || {}; data.action = action; data.nonce = qs('#ax-nonce')?.value;
    return fetch(ajaxurl, { method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'}, body: new URLSearchParams(data) }).then(r=>r.json());
  }
  function log(msg){ const el = qs('#ax-log'); if (!el) return; el.textContent += (typeof msg==='string'? msg : JSON.stringify(msg)) + '\n'; el.scrollTop = el.scrollHeight; }
  function setProgress(p){ const bar = qs('#ax-bar'); if (bar) bar.style.width = Math.max(0, Math.min(100, p)) + '%'; }

  function buildSteps(entities){
    const steps = [];
    if (entities.includes('roles')) steps.push('roles');
    steps.push('taxonomies');
    if (entities.includes('media')) steps.push('media');
    if (entities.includes('pages')) steps.push('pages');
    if (entities.includes('posts')) steps.push('posts');
    if (entities.includes('services')) steps.push('services');
    if (entities.includes('events')) steps.push('events');
    if (entities.includes('products')) { steps.push('products_simple'); if (qs('#ax-variations')?.checked) steps.push('products_variable'); }
    if (entities.includes('menus')) steps.push('menus');
    steps.push('settings');
    steps.push('schedule');
    return steps;
  }

  function runSteps(steps, idx){
    if (idx >= steps.length) { setProgress(100); log('Done.'); return; }
    const step = steps[idx];
    const opts = {
      posts: valCounts().posts||6,
      products: valCounts().products||12,
      media: valCounts().media||10,
      schedule: qs('#ax-schedule')?.checked ? '1':'0',
      interval: qs('#ax-schedule-interval')?.value||'daily',
      seed: qs('#ax-seed')?.value || '42',
      locale: qs('#ax-locale')?.value || 'en_US',
      conflict: qs('#ax-conflict')?.value || 'skip',
      variations: qs('#ax-variations')?.checked ? '1' : '0'
    };
    ajax('aqualuxe_import_step', { step, opts: JSON.stringify(opts) })
      .then(res=>{
        if (!res || !res.success) throw new Error(res?.data?.message || 'Step failed');
        log('✓ ' + step);
        setProgress(Math.round(((idx+1)/steps.length)*100));
        runSteps(steps, idx+1);
      })
      .catch(err=>{ log('Error on ' + step + ': ' + err.message); });
  }

  window.addEventListener('load', function(){
    const previewBtn = qs('#ax-preview');
    const runBtn = qs('#ax-run');
    const exportBtn = qs('#ax-export');
    const flushBtn = qs('#ax-flush-btn');
    if (previewBtn) previewBtn.addEventListener('click', function(e){
      e.preventDefault();
      ajax('aqualuxe_import_preview', { counts: JSON.stringify(valCounts()) })
        .then(res=>{ if (res.success) log(res.data.preview); else log(res); });
    });
    if (runBtn) runBtn.addEventListener('click', function(e){
      e.preventDefault();
      qs('#ax-log').textContent = '';
      setProgress(0);
      const steps = buildSteps(selectedEntities());
      runSteps(steps, 0);
    });
    if (exportBtn) exportBtn.addEventListener('click', function(e){
      e.preventDefault();
      ajax('aqualuxe_export').then(res=>{ if (res.success) { log('Exported: ' + res.data.file); } else { log(res); } });
    });
    if (flushBtn) flushBtn.addEventListener('click', function(e){
      e.preventDefault();
      const parts = qsa('.ax-flush-part:checked').map(i=>i.value);
      if (!confirm('This will permanently delete demo content. Continue?')) return;
      ajax('aqualuxe_flush', { parts: JSON.stringify(parts) })
        .then(res=>{ if (res.success) log(res.data.flushed); else log(res); });
    });
  });
})();
