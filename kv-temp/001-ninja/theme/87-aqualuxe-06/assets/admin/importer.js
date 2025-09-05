(function(){
  const $ = (id)=>document.getElementById(id);
  const q = (s,ctx=document)=>ctx.querySelector(s);
  const qa = (s,ctx=document)=>Array.from(ctx.querySelectorAll(s));
  const restRoot = (window.AQLX_ADMIN && AQLX_ADMIN.restUrl) ? AQLX_ADMIN.restUrl.replace(/\/?$/,'') + '/aqualuxe/v1' : (window.wpApiSettings?.root || '') + 'aqualuxe/v1';
  const nonce = (window.AQLX_ADMIN && AQLX_ADMIN.nonce) || (window.wpApiSettings?.nonce) || '';
  const i18n = (window.AQLX_ADMIN && AQLX_ADMIN.i18n) || {};
  const labels = (i18n && i18n.labels) || {};
  function api(p, method, body){
    return fetch(restRoot + p, { method: method||'GET', headers:{'X-WP-Nonce': nonce, 'Content-Type':'application/json'}, body: body? JSON.stringify(body): undefined }).then(r=>r.json());
  }

  // Elements
  const status = $('aqlx-status');
  const logEl = $('aqlx-log');
  const bar = $('aqlx-progress-bar');
  const progress = $('aqlx-progress');
  const auditEl = $('aqlx-audit');
  const nextEl = $('aqlx-next');
  const schedEl = $('aqlx-sched');
  const lastEl = $('aqlx-last');
  const spinner = $('aqlx-spinner');
  const startBtn = $('aqlx-start');
  const previewBtn = $('aqlx-preview');
  const exportBtn = $('aqlx-export');
  const exportLinkEl = $('aqlx-export-link');
  const downloadExportBtn = $('aqlx-download-export');
  const pauseBtn = $('aqlx-pause');
  const resumeBtn = $('aqlx-resume');
  const cancelBtn = $('aqlx-cancel');
  const scheduleBtn = $('aqlx-schedule');
  const clearScheduleBtn = $('aqlx-clear-schedule');
  const scheduledBadge = $('aqlx-scheduled-badge');

  const collectEntities = ()=> qa('.aqlx-entity:checked').map(i=>i.value);
  function appendLog(msg){ if(!msg) return; const cur = logEl.textContent? logEl.textContent.split('\n'):[]; cur.push(msg); logEl.textContent = cur.slice(-1000).join('\n') + '\n'; logEl.scrollTop = logEl.scrollHeight; }
  function setProgress(v){ v = Math.max(0, Math.min(100, v||0)); if(bar) bar.style.width = v + '%'; if(progress) progress.setAttribute('aria-valuenow', String(Math.round(v))); }
  function setRunning(on){ [startBtn, previewBtn, exportBtn, scheduleBtn, clearScheduleBtn].forEach(b=>{ if(b) b.disabled = !!on; }); if(pauseBtn) pauseBtn.disabled = !on; if(resumeBtn) resumeBtn.disabled = !on; if(cancelBtn) cancelBtn.disabled = !on; if(spinner) spinner.hidden = !on; }

  async function run(){
  const entities = collectEntities(); if(!entities.length){ appendLog(i18n.selectEntity || 'Select at least one entity to import.'); return; }
  const reset = $('aqlx-reset').checked;
  const volume = parseInt($('aqlx-volume').value || '10', 10);
    const policy = q('#aqlx-policy')?.value || 'skip';
    const locale = q('#aqlx-locale')?.value || 'en_US';
    const currency = q('#aqlx-currency')?.value || 'USD';
    const provider = q('#aqlx-asset-provider')?.value || 'local_svg';
    const customUrls = (q('#aqlx-asset-custom-urls')?.value || '')
      .split(/\r?\n/)
      .map(s=>s.trim())
      .filter(Boolean);
    const assets = {
      provider,
      query: q('#aqlx-asset-query')?.value || '',
      count: parseInt(q('#aqlx-asset-count')?.value || '8', 10),
      urls: provider==='custom'? customUrls : []
    };
    const from = q('#aqlx-from')?.value || '';
    const to = q('#aqlx-to')?.value || '';
    const localesExtra = (q('#aqlx-locales-extra')?.value || '')
      .split(',')
      .map(s=>s.trim())
      .filter(Boolean);
    if(from && to){ const s = new Date(from); const e = new Date(to); if(isFinite(s) && isFinite(e) && s>e){ appendLog(i18n.invalidRange || 'From date must be before To date.'); return; } }
    status.textContent = 'Starting…'; setProgress(0); logEl.textContent=''; setRunning(true);
    const start = await api('/import/start','POST',{entities, reset, volume, policy, locale, localesExtra, range:{from,to}, assets, currency}).catch(()=>({error:'start_failed'}));
    if(start?.error){ status.textContent='Failed to start'; appendLog(start.error); setRunning(false); return; }
    if(start?.audit_url){ auditEl.href = start.audit_url; auditEl.classList.remove('is-hidden'); }
    let done=false; let pv=0;
    while(!done){
      const step = await api('/import/step','POST',{}).catch(()=>({error:'step_failed'}));
      if(step?.error){ status.textContent='Error'; appendLog(step.error); break; }
      if(typeof step.progress==='number'){ pv = step.progress; setProgress(pv); }
      if(Array.isArray(step.log)) step.log.forEach(appendLog);
      if(step?.audit_url){ auditEl.href = step.audit_url; auditEl.classList.remove('is-hidden'); }
      const paused = !!step.paused; done = !!step.done; status.textContent = done? 'Completed' : (paused? 'Paused' : 'Working…');
      if(!done) await new Promise(r=>setTimeout(r, paused?1000:400));
    }
    setRunning(false);
    try{ await finalizeRunSummary(); }catch(e){}
    try{ await loadRecentAudits(); }catch(e){}
    refreshStateSummary();
  }

  async function preview(){
    const entities = collectEntities();
  const volume = parseInt($('aqlx-volume').value || '10', 10);
    const res = await api('/import/preview','POST',{entities, volume}).catch(()=>({error:'preview_failed'}));
    if(res?.error){ appendLog(res.error); return; }
    appendLog('Preview:'); appendLog(JSON.stringify(res,null,2));
  }

  async function exportDemo(){
    const entities = collectEntities();
    const res = await api('/import/export','POST',{entities}).catch(()=>({error:'export_failed'}));
    if(res?.url){ appendLog('Export ready: ' + res.url); if(exportLinkEl){ exportLinkEl.href=res.url; exportLinkEl.classList.remove('is-hidden'); } if(downloadExportBtn){ downloadExportBtn.classList.remove('is-hidden'); } window.open(res.url,'_blank'); }
    else{ appendLog(res?.error || 'Export failed'); }
  }
  async function downloadExport(){ const url = exportLinkEl?.href; if(!url){ appendLog(i18n.noExport || 'No export available.'); return; } try{ const r = await fetch(url,{credentials:'same-origin'}); const blob = await r.blob(); const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download=(url.split('/').pop()||'aqualuxe-export.json'); document.body.appendChild(a); a.click(); setTimeout(()=>{ URL.revokeObjectURL(a.href); document.body.removeChild(a); },2000);}catch(e){ window.open(url,'_blank'); }}
  async function copyLog(){ const txt = logEl.textContent||''; try{ if(navigator?.clipboard?.writeText){ await navigator.clipboard.writeText(txt); appendLog(i18n.copyOk||'Copied.'); } else { const ta=document.createElement('textarea'); ta.value=txt; ta.style.position='fixed'; ta.style.opacity='0'; document.body.appendChild(ta); ta.focus(); ta.select(); document.execCommand('copy'); document.body.removeChild(ta); appendLog(i18n.copyOk||'Copied.'); } } catch(e){ appendLog(i18n.copyFail||'Copy failed.'); } }
  function clearLog(){ logEl.textContent=''; }
  function getSelectedAudit(){ const sel = $('aqlx-audits'); if(!sel) return null; const opt = sel.options[sel.selectedIndex]; if(!opt||!opt.value) return null; return { url:opt.value, name:opt.text, mtime: opt.getAttribute('data-mtime')}; }
  async function openAudit(){ const res = await api('/import/state','GET').catch(()=>({error:'state_failed'})); if(!res||res.error){ appendLog(res?.error||'State fetch failed'); return; } const url = res.audit_url || (res.state?.audit_url); if(url){ auditEl.href=url; auditEl.classList.remove('is-hidden'); window.open(url,'_blank'); } else { appendLog(i18n.noAudit || 'No audit log available yet.'); } }
  async function downloadAudit(){ let url = auditEl?.href; if(!url){ const res = await api('/import/state','GET').catch(()=>({error:'state_failed'})); if(res && !res.error){ url = res.audit_url || (res.state?.audit_url); } } if(!url){ appendLog(i18n.noAudit || 'No audit log available yet.'); return; } try{ const r = await fetch(url,{credentials:'same-origin'}); const blob = await r.blob(); const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download=(url.split('/').pop()||'aqualuxe-audit.jsonl'); document.body.appendChild(a); a.click(); setTimeout(()=>{ URL.revokeObjectURL(a.href); document.body.removeChild(a); },2000); appendLog('Audit downloaded.'); } catch(e){ appendLog(i18n.auditDlFail || 'Download failed. Opening instead…'); window.open(url,'_blank'); } }

  async function refreshSchedule(){
    const res = await api('/import/schedule/state','GET').catch(()=>({error:'schedule_state_failed'}));
    if(res?.ok){
      if(res.next){ const dt = new Date(res.next*1000); const label = nextEl?.dataset?.label || 'Next run:'; nextEl.textContent = label + ' ' + dt.toLocaleString(); } else { nextEl.textContent=''; }
      const cfg = res.schedule || {}; const rec = cfg.recurrence ? (labels.recurrence?.[cfg.recurrence] || cfg.recurrence) : '';
      const ents = Array.isArray(cfg.entities) ? cfg.entities.map(e=> labels.entities?.[e] || e) : [];
      const reset = cfg.reset ? (i18n.yes || 'Yes') : (i18n.no || 'No');
      if(rec || ents.length){ const sLabel = schedEl?.dataset?.label || 'Schedule:'; schedEl.textContent = sLabel + ' ' + [rec, (ents.length? ' • ' + ents.join(', ') : ''), ' • ' + (i18n.reset || 'Reset:') + ' ' + reset].join(''); if(scheduledBadge) scheduledBadge.classList.remove('is-hidden'); if(startBtn){ startBtn.disabled = true; startBtn.title = i18n.scheduledActive || ''; } } else { schedEl.textContent=''; if(scheduledBadge) scheduledBadge.classList.add('is-hidden'); if(startBtn){ startBtn.disabled=false; startBtn.title=''; } }
    }
  }
  async function refreshStateSummary(){ const res = await api('/import/state','GET').catch(()=>({error:'state_failed'})); if(res?.ok && res?.state?.started){ const dt = new Date((res.state.started||0)*1000); const label = lastEl?.dataset?.label || 'Last run:'; lastEl.textContent = label + ' ' + dt.toLocaleString(); } }
  async function finalizeRunSummary(){ const res = await api('/import/state','GET').catch(()=>({error:'state_failed'})); if(!res||!res.ok) return; const st = res.state||{}; const counts={ posts: Array.isArray(st.created_posts)? st.created_posts.length : (res.created_posts||0), terms: Array.isArray(st.created_terms)? st.created_terms.length : (res.created_terms||0), users: Array.isArray(st.created_users)? st.created_users.length : 0, roles: Array.isArray(st.created_roles)? st.created_roles.length : 0, widgets: Array.isArray(st.created_widgets)? st.created_widgets.length : 0,}; const parts=['Summary:',' - Created posts: '+counts.posts,' - Created terms: '+counts.terms]; if(counts.users) parts.push(' - Created users: '+counts.users); if(counts.roles) parts.push(' - Created roles: '+counts.roles); if(counts.widgets) parts.push(' - Created widgets: '+counts.widgets); appendLog(parts.join('\n')); }
  async function loadRecentAudits(){ const res = await api('/import/audits','GET').catch(()=>({error:'audits_failed'})); const sel = $('aqlx-audits'); if(!sel) return; while(sel.options.length>1){ sel.remove(1);} if(res && res.ok && Array.isArray(res.items)){ res.items.forEach(it=>{ const o=document.createElement('option'); const when = it.mtime ? (new Date(it.mtime*1000)).toLocaleString() : ''; o.value=it.url; o.text=(it.name||'audit') + (when?(' — '+when):''); if(it.mtime) o.setAttribute('data-mtime', String(it.mtime)); sel.appendChild(o); }); } }
  // getSelectedAudit already defined above; keep a single definition
  function openSelectedAudit(){ const it = getSelectedAudit(); if(!it){ appendLog('Select an audit first.'); return; } window.open(it.url,'_blank'); }
  async function downloadSelectedAudit(){ const it = getSelectedAudit(); if(!it){ appendLog('Select an audit first.'); return; } try{ const r=await fetch(it.url,{credentials:'same-origin'}); const blob=await r.blob(); const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download=it.name||'aqualuxe-audit.jsonl'; document.body.appendChild(a); a.click(); setTimeout(()=>{ URL.revokeObjectURL(a.href); document.body.removeChild(a); },2000);}catch(e){ window.open(it.url,'_blank'); } }
  function fillLast30(){ const to = new Date(); const from = new Date(Date.now()-29*24*60*60*1000); const fmt=(d)=>d.toISOString().slice(0,10); const fe=$('aqlx-from'); if(fe) fe.value = fmt(from); const te=$('aqlx-to'); if(te) te.value = fmt(to); }
  async function scheduleRun(){ const entities = collectEntities(); const reset = $('aqlx-reset').checked; const volume = parseInt($('aqlx-volume').value || '10', 10); const recurrence = q('#aqlx-recurrence')?.value || 'daily'; const res = await api('/import/schedule','POST',{entities, reset, volume, recurrence}).catch(()=>({error:'schedule_failed'})); if(res?.ok || res?.scheduled){ appendLog('Scheduled: ' + (res.recurrence || recurrence)); status.textContent='Scheduled'; } else { appendLog(res?.error || 'Schedule failed'); } refreshSchedule(); }
  async function clearSchedule(){ const res = await api('/import/schedule/clear','POST',{}).catch(()=>({error:'clear_failed'})); if(res?.ok){ appendLog('Cleared schedule.'); } else { appendLog(res?.error||'Clear schedule failed'); } refreshSchedule(); }
  async function flushAll(){ if(!confirm(i18n.confirmFlush || 'This will delete demo content and reset state. Continue?')) return; const res = await api('/import/flush','POST',{}).catch(()=>({error:'flush_failed'})); if(res?.ok){ appendLog('Flushed.'); setProgress(0); status.textContent='Flushed'; } else { appendLog(res?.error || 'Flush failed'); } }
  async function pauseRun(){ const res = await api('/import/pause','POST',{}).catch(()=>({error:'pause_failed'})); if(res?.ok){ status.textContent='Paused'; appendLog('Paused.'); } else { appendLog(res?.error || 'Pause failed'); } }
  async function resumeRun(){ const res = await api('/import/resume','POST',{}).catch(()=>({error:'resume_failed'})); if(res?.ok){ status.textContent='Working…'; appendLog('Resumed.'); } else { appendLog(res?.error || 'Resume failed'); } }
  async function cancelRun(){ if(!confirm(i18n.confirmCancel || 'Cancel will rollback created items and clear state. Continue?')) return; const res = await api('/import/cancel','POST',{}).catch(()=>({error:'cancel_failed'})); if(res?.ok){ status.textContent='Canceled'; appendLog('Canceled and rolled back.'); setProgress(0); } else { appendLog(res?.error || 'Cancel failed'); } }

  // Wire events
  if(startBtn) startBtn.addEventListener('click', run);
  if(previewBtn) previewBtn.addEventListener('click', preview);
  if(exportBtn) exportBtn.addEventListener('click', exportDemo);
  if(downloadExportBtn) downloadExportBtn.addEventListener('click', downloadExport);
  const stateBtn = $('aqlx-state'); if(stateBtn) stateBtn.addEventListener('click', async()=>{ const res = await api('/import/state','GET').catch(()=>({error:'state_failed'})); if(res?.ok){ const s=res; const info={ progress:s.progress, paused:!!s.paused, done:!!s.done, created_posts:s.created_posts, created_terms:s.created_terms }; appendLog('State: ' + JSON.stringify(info)); }});
  const copyBtn = $('aqlx-copy-log'); if(copyBtn) copyBtn.addEventListener('click', copyLog);
  const clearBtn = $('aqlx-clear-log'); if(clearBtn) clearBtn.addEventListener('click', clearLog);
  const flushBtn = $('aqlx-flush'); if(flushBtn) flushBtn.addEventListener('click', flushAll);
  const openAuditBtn = $('aqlx-open-audit'); if(openAuditBtn) openAuditBtn.addEventListener('click', openAudit);
  const downloadAuditBtn = $('aqlx-download-audit'); if(downloadAuditBtn) downloadAuditBtn.addEventListener('click', downloadAudit);
  if(pauseBtn) pauseBtn.addEventListener('click', pauseRun);
  if(resumeBtn) resumeBtn.addEventListener('click', resumeRun);
  if(cancelBtn) cancelBtn.addEventListener('click', cancelRun);
  if(scheduleBtn) scheduleBtn.addEventListener('click', scheduleRun);
  if(clearScheduleBtn) clearScheduleBtn.addEventListener('click', clearSchedule);
  const last30 = $('aqlx-last30'); if(last30) last30.addEventListener('click', fillLast30);
  const openSelBtn = $('aqlx-open-selected-audit'); if(openSelBtn) openSelBtn.addEventListener('click', openSelectedAudit);
  const dlSelBtn = $('aqlx-download-selected-audit'); if(dlSelBtn) dlSelBtn.addEventListener('click', downloadSelectedAudit);
  const entitiesAll = $('aqlx-entities-all'); if(entitiesAll){ entitiesAll.addEventListener('change',(e)=>{ const on = !!e.target.checked; qa('.aqlx-entity').forEach(cb=>{ cb.checked = on; }); }); }

  // Initial
  refreshSchedule();
  refreshStateSummary();
  loadRecentAudits();
})();
