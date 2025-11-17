import gsap from 'gsap';

(function(){
  const w1 = document.getElementById('ax-wave-1');
  const w2 = document.getElementById('ax-wave-2');
  const w3 = document.getElementById('ax-wave-3');
  if (!w1 || !w2 || !w3) return;
  const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;
  let lowPower = false;
  let t1, t2, t3;
  function start(){
    const a1 = lowPower ? -4 : -8;
    const a2 = lowPower ? -6 : -12;
    const a3 = lowPower ? -8 : -16;
    t1 = gsap.to(w1, { duration: 8, y: a1, repeat: -1, yoyo: true, ease: 'sine.inOut' });
    t2 = gsap.to(w2, { duration: 10, y: a2, repeat: -1, yoyo: true, ease: 'sine.inOut' });
    t3 = gsap.to(w3, { duration: 12, y: a3, repeat: -1, yoyo: true, ease: 'sine.inOut' });
  }
  function restart(){ if(t1) t1.kill(); if(t2) t2.kill(); if(t3) t3.kill(); start(); }
  start();
  window.addEventListener('ax:lowpower', (e)=>{ lowPower = !!(e?.detail?.on); restart(); });
})();
