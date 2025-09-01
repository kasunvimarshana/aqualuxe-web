import gsap from 'gsap';

(function(){
  const w1 = document.getElementById('ax-wave-1');
  const w2 = document.getElementById('ax-wave-2');
  const w3 = document.getElementById('ax-wave-3');
  if (!w1 || !w2 || !w3) return;
  const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;
  // Gentle vertical bobbing and horizontal parallax
  gsap.to(w1, { duration: 8, y: -8, repeat: -1, yoyo: true, ease: 'sine.inOut' });
  gsap.to(w2, { duration: 10, y: -12, repeat: -1, yoyo: true, ease: 'sine.inOut' });
  gsap.to(w3, { duration: 12, y: -16, repeat: -1, yoyo: true, ease: 'sine.inOut' });
})();
