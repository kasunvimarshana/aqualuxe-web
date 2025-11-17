// Dark mode toggle with persistence
(function(){
  const key='alx-dark';
  const root=document.documentElement;
  function apply(){ root.classList.toggle('dark', localStorage.getItem(key)==='1'); }
  document.addEventListener('DOMContentLoaded', apply);
  document.addEventListener('click', e=>{
    const t=e.target.closest('[data-toggle-dark]'); if(!t) return;
    const cur=localStorage.getItem(key)==='1'; localStorage.setItem(key, cur?'0':'1'); apply();
  });
})();
