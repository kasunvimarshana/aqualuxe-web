// Simple mobile nav toggle
(function(){
  function ready(fn){ if(document.readyState!=='loading') fn(); else document.addEventListener('DOMContentLoaded', fn); }
  ready(function(){
    const btn = document.querySelector('[data-toggle-nav]');
    const menu = document.getElementById('primary-menu');
    if(!btn || !menu) return;
    let lastFocus = null;
    function open(){
      lastFocus = document.activeElement;
      menu.classList.remove('hidden');
      menu.setAttribute('aria-hidden','false');
      btn.setAttribute('aria-expanded','true');
      const firstLink = menu.querySelector('a,button');
      if(firstLink) firstLink.focus();
      document.addEventListener('keydown', onKey);
      document.addEventListener('click', onDocClick);
    }
    function close(){
      menu.classList.add('hidden');
      menu.setAttribute('aria-hidden','true');
      btn.setAttribute('aria-expanded','false');
      document.removeEventListener('keydown', onKey);
      document.removeEventListener('click', onDocClick);
      if(lastFocus && typeof lastFocus.focus==='function') { lastFocus.focus(); }
    }
    function onKey(e){ if(e.key==='Escape'){ close(); } }
    function onDocClick(e){ if(menu.contains(e.target) || btn.contains(e.target)) return; close(); }
    btn.addEventListener('click', ()=>{
      const isOpen = !menu.classList.contains('hidden');
      isOpen ? close() : open();
    });
    menu.addEventListener('click', e=>{
      const link = e.target.closest('a'); if(!link) return;
      // collapse after selecting a link on mobile
      if(window.innerWidth < 768) close();
    });
  });
})();
