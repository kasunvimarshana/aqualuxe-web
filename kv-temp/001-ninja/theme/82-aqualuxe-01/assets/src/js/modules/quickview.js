// Quick view modal (basic) – fetches product link and opens inline iframe
(function(){
  function open(src){
    const overlay=document.createElement('div'); overlay.className='fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4'; overlay.setAttribute('role','dialog'); overlay.setAttribute('aria-modal','true');
    const wrap=document.createElement('div'); wrap.className='bg-white rounded-md overflow-hidden w-full max-w-3xl h-[70vh]';
    const iframe=document.createElement('iframe'); iframe.src=src; iframe.className='w-full h-full'; iframe.setAttribute('title','Quick view');
    const close=document.createElement('button'); close.className='absolute top-4 right-4 btn btn-ghost'; close.textContent='×'; close.addEventListener('click', ()=>document.body.removeChild(overlay));
    overlay.appendChild(wrap); wrap.appendChild(close); wrap.appendChild(iframe); document.body.appendChild(overlay);
  }
  document.addEventListener('click', e=>{
    const a=e.target.closest('[data-quick-view]'); if(!a) return; e.preventDefault(); open(a.href);
  });
})();
