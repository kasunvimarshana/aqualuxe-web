// Utility to add a class for reduced-motion users
(function(){
  function update(){ if(window.matchMedia('(prefers-reduced-motion: reduce)').matches){ document.documentElement.classList.add('reduced-motion'); } else { document.documentElement.classList.remove('reduced-motion'); } }
  update();
  try { window.matchMedia('(prefers-reduced-motion: reduce)').addEventListener('change', update);} catch(_){ /* Safari */ }
})();
