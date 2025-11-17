import * as d3 from 'd3';

export function startPlankton(container){
  const cvs = document.createElement('canvas');
  cvs.className = 'absolute inset-0 w-full h-full pointer-events-none';
  cvs.style.zIndex = '5';
  container.appendChild(cvs);
  const ctx = cvs.getContext('2d');

  const DPR = Math.min(window.devicePixelRatio||1, 2);
  function resize(){
    cvs.width = container.clientWidth * DPR;
    cvs.height = container.clientHeight * DPR;
  }
  resize();
  window.addEventListener('resize', resize);

  const COUNT = 80;
  const plankton = d3.range(COUNT).map(()=>({
    x: Math.random(), y: Math.random(), s: Math.random()*1.5+0.5, v: 0.01+Math.random()*0.03
  }));

  const reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const timer = d3.timer((elapsed)=>{
    if (reduced) return; // freeze frame
    ctx.clearRect(0,0,cvs.width,cvs.height);
    ctx.fillStyle = 'rgba(255,255,255,0.25)';
    plankton.forEach(p=>{
      p.y -= p.v * 0.016; if (p.y < -0.05) { p.y = 1.05; p.x = Math.random(); }
      const x = p.x * cvs.width; const y = p.y * cvs.height;
      ctx.beginPath(); ctx.arc(x, y, p.s*DPR, 0, Math.PI*2); ctx.fill();
    });
  });
  return ()=>{ timer.stop(); window.removeEventListener('resize', resize); container.removeChild(cvs); };
}
