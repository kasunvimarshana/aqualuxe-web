// Wishlist using localStorage
(function(){
  const KEY='alx-wishlist';
  function get(){ try{return JSON.parse(localStorage.getItem(KEY)||'[]');}catch(e){return [];} }
  function set(v){ localStorage.setItem(KEY, JSON.stringify(v)); }
  function toggle(id){ const list=get(); const i=list.indexOf(id); if(i>-1){ list.splice(i,1);} else { list.push(id);} set(list); }
  function init(){
    document.addEventListener('click', e=>{
      const b=e.target.closest('[data-wishlist]'); if(!b) return; const id=b.getAttribute('data-product-id'); toggle(id); b.classList.toggle('bg-white/20');
    });
  }
  document.addEventListener('DOMContentLoaded', init);
})();
