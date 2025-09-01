import * as d3 from 'd3';

(function(){
  const svg = d3.select('#ax-bubbles');
  if (svg.empty()) return;
  const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;

  const width = () => parseInt(svg.style('width')) || window.innerWidth;
  const height = () => parseInt(svg.style('height')) || window.innerHeight;

  function randomBubble(){
    const r = Math.random()*6 + 3; // 3-9 px
    const x = Math.random()*width();
    const y = height() + r + Math.random()*40;
    const dur = 4000 + Math.random()*5000;
    const drift = (Math.random()-0.5)*40;
    return { r, x, y, dur, drift };
  }

  function spawn(){
    const b = randomBubble();
    const g = svg.append('circle')
      .attr('cx', b.x)
      .attr('cy', b.y)
      .attr('r', b.r)
      .attr('fill', 'rgba(255,255,255,0.25)');
    g.transition()
      .duration(b.dur)
      .ease(d3.easeCubicOut)
      .attr('cy', -10)
      .attr('cx', b.x + b.drift)
      .attr('r', Math.max(1.5, b.r*0.5))
      .style('opacity', 0)
      .remove();
  }

  let running = true;
  function loop(){ if (!running) return; spawn(); setTimeout(loop, 200 + Math.random()*400); }
  loop();
  document.addEventListener('visibilitychange', ()=>{ running = !document.hidden; if (running) loop(); });
})();
