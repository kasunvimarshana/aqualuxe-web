import * as d3 from 'd3';

export function initSparklines(){
  const nodes = document.querySelectorAll('[data-sparkline]');
  nodes.forEach(node => {
    const values = (node.getAttribute('data-values')||'').split(',').map(Number).filter(n => !isNaN(n));
    if (!values.length) return;
    const w = 120, h = 36, m = 2;
    const x = d3.scaleLinear().domain([0, values.length - 1]).range([m, w - m]);
    const y = d3.scaleLinear().domain([d3.min(values), d3.max(values)]).nice().range([h - m, m]);
    const line = d3.line().x((_, i) => x(i)).y(d => y(d)).curve(d3.curveMonotoneX);
    const svg = d3.create('svg').attr('width', w).attr('height', h).attr('role','img').attr('aria-label','trend');
    svg.append('path').attr('d', line(values)).attr('fill','none').attr('stroke','currentColor').attr('stroke-width', 2);
    node.innerHTML = '';
    node.appendChild(svg.node());
  });
}
