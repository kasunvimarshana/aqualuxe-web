/* AquaLuxe front page interactive enhancements */

// Progressive enhancement guards
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const canWebGL = (() => {
  try {
    const c = document.createElement('canvas');
    return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl')));
  } catch (e) { return false; }
})();

function mount3DScene() {
  const canvas = document.getElementById('aqlx-hero-3d');
  if (!canvas || !canWebGL) return;
  // Lazy import Three.js and GSAP only when needed
  Promise.all([
    import(/* webpackChunkName: "three" */ 'three'),
    import(/* webpackChunkName: "gsap" */ 'gsap'),
  ]).then(([THREE, gsap]) => {
    const { Scene, PerspectiveCamera, WebGLRenderer, Color, FogExp2, Clock, AmbientLight, DirectionalLight, Points, BufferGeometry, BufferAttribute, PointsMaterial } = THREE;
    const scene = new Scene();
    scene.background = new Color('#0b1324');
    scene.fog = new FogExp2(0x0b1324, 0.035);

    const camera = new PerspectiveCamera(55, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
    camera.position.set(0, 0, 8);

    const renderer = new WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.5));
    renderer.setSize(canvas.clientWidth, canvas.clientHeight, false);

    const amb = new AmbientLight(0x88aaff, 0.6);
    const dir = new DirectionalLight(0xffffff, 0.6); dir.position.set(5, 10, 7);
    scene.add(amb, dir);

    // Simple particle field to simulate plankton
    const particles = new Float32Array(3000);
    for (let i = 0; i < particles.length; i += 3) {
      particles[i] = (Math.random() - 0.5) * 40;    // x
      particles[i+1] = (Math.random() - 0.5) * 20;  // y
      particles[i+2] = Math.random() * -40;         // z
    }
    const geom = new BufferGeometry(); geom.setAttribute('position', new BufferAttribute(particles, 3));
    const mat  = new PointsMaterial({ color: 0x66bbff, size: 0.05, transparent: true, opacity: 0.8 });
    const field = new Points(geom, mat); scene.add(field);

    // Resize handling
    function onResize() {
      const w = canvas.clientWidth, h = canvas.clientHeight;
      camera.aspect = w / h; camera.updateProjectionMatrix(); renderer.setSize(w, h, false);
    }
    window.addEventListener('resize', onResize);

    const clock = new Clock();
    function tick() {
      const t = clock.getElapsedTime();
      field.rotation.y = t * 0.03;
      field.position.y = Math.sin(t * 0.5) * 0.2;
      renderer.render(scene, camera);
      if (!prefersReducedMotion) requestAnimationFrame(tick);
    }
    tick();

    // GSAP text reveal
    if (!prefersReducedMotion) {
      gsap.gsap.fromTo('#hero-title', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 1, ease: 'power3.out' });
    }
  }).catch(() => { /* silently fail */ });
}

function mountD3Chart() {
  const el = document.getElementById('aqlx-d3-chart');
  if (!el) return;
  el.setAttribute('aria-busy', 'true');
  import(/* webpackChunkName: "d3" */ 'd3').then(d3 => {
    const width = Math.max(320, el.clientWidth);
    const height = Math.max(160, el.clientHeight);
    const padding = 28;
    const svg = d3.select(el).append('svg')
      .attr('viewBox', `0 0 ${width} ${height}`)
      .attr('width', '100%')
      .attr('height', '100%');
    const g = svg.append('g');

  const legend = document.createElement('div');
    legend.className = 'absolute top-2 right-2 flex gap-2';
    legend.setAttribute('role', 'group');
    legend.setAttribute('aria-label', 'Toggle chart series');
    el.style.position = 'relative';
    el.appendChild(legend);

    const state = { temperature: true, ph: true, ammonia: true };

    function makeBtn(name, color, title) {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'text-xs px-2 py-1 rounded border';
      b.style.borderColor = color; b.style.color = color;
      b.setAttribute('aria-pressed', 'true');
      b.title = `Toggle ${title}`;
      b.textContent = title;
      b.addEventListener('click', () => {
        state[name] = !state[name];
        b.setAttribute('aria-pressed', String(state[name]));
        render(currentPoints);
      });
      legend.appendChild(b);
    }

    // Okabe-Ito colorblind-friendly palette
    const colors = {
      temperature: '#0072B2', // blue
      ph: '#E69F00',          // orange
      ammonia: '#D55E00',     // vermillion
    };
    makeBtn('temperature', colors.temperature, 'Temp');
    makeBtn('ph', colors.ph, 'pH');
    makeBtn('ammonia', colors.ammonia, 'NH3');

    let currentPoints = [];

    function render(points) {
      currentPoints = points;
      g.selectAll('*').remove();

      if (!points || !points.length) return;
      const tMin = d3.min(points, d => d.t);
      const tMax = d3.max(points, d => d.t);
      const x = d3.scaleLinear().domain([tMin, tMax]).range([padding, width - padding]);

      // Determine domains from data (with small padding)
      const tempVals = points.map(d => d.temperature).filter(v => typeof v === 'number');
      const phVals = points.map(d => d.ph).filter(v => typeof v === 'number');
      const nh3Vals = points.map(d => d.ammonia).filter(v => typeof v === 'number');
      const pad = (min, max, p = 0.05) => [min - (max - min) * p, max + (max - min) * p];
      const yTemp = d3.scaleLinear().domain(pad(d3.min(tempVals) ?? 20, d3.max(tempVals) ?? 28)).range([height - padding, padding]);
      const yPh = d3.scaleLinear().domain(pad(d3.min(phVals) ?? 7.0, d3.max(phVals) ?? 7.4)).range([height - padding, padding]);
      const yNh3 = d3.scaleLinear().domain(pad(d3.min(nh3Vals) ?? 0, d3.max(nh3Vals) ?? 0.1)).range([height - padding, padding]);

      // Axes (x + temperature y axis only for readability)
      const xAxis = d3.axisBottom(x).ticks(6).tickSizeOuter(0);
      const yAxis = d3.axisLeft(yTemp).ticks(5).tickSizeOuter(0);
      g.append('g').attr('transform', `translate(0,${height - padding})`).call(xAxis);
      g.append('g').attr('transform', `translate(${padding},0)`).call(yAxis);

      const lines = {
        temperature: { color: colors.temperature, y: yTemp, access: d => d.temperature },
        ph: { color: colors.ph, y: yPh, access: d => d.ph },
        ammonia: { color: colors.ammonia, y: yNh3, access: d => d.ammonia },
      };
      Object.keys(lines).forEach(key => {
        if (!state[key]) return;
        const conf = lines[key];
        const line = d3.line()
          .x(d => x(d.t))
          .y(d => conf.y(conf.access(d)))
          .curve(d3.curveMonotoneX);
        g.append('path')
          .datum(points)
          .attr('fill', 'none')
          .attr('stroke', conf.color)
          .attr('stroke-width', 2)
          .attr('d', line);
      });

      // Tooltip overlay
      if (!el.querySelector('[data-aqlx-tooltip]')) {
        const tip = document.createElement('div');
        tip.setAttribute('data-aqlx-tooltip', '');
        tip.setAttribute('role', 'tooltip');
        tip.className = 'text-xs px-2 py-1 rounded border bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm';
        tip.style.position = 'absolute';
        tip.style.pointerEvents = 'none';
        tip.style.display = 'none';
        el.appendChild(tip);
      }
      const tip = el.querySelector('[data-aqlx-tooltip]');

      function showTooltip(mx, my) {
        if (!tip) return;
        const tVal = Math.round(x.invert(mx));
        const byT = points.find(d => d.t === tVal);
        if (!byT) { tip.style.display = 'none'; return; }
        const parts = [];
        if (state.temperature && typeof byT.temperature === 'number') parts.push(`Temp: ${byT.temperature.toFixed(1)}°C`);
        if (state.ph && typeof byT.ph === 'number') parts.push(`pH: ${byT.ph.toFixed(2)}`);
        if (state.ammonia && typeof byT.ammonia === 'number') parts.push(`NH3: ${byT.ammonia.toFixed(3)} mg/L`);
        if (!parts.length) { tip.style.display = 'none'; return; }
        tip.textContent = `t=${tVal} • ` + parts.join('  •  ');
        const pad = 8;
        const tx = Math.min(Math.max(mx + pad, 0), width - 120);
        const ty = Math.min(Math.max(my - 28, 0), height - 24);
        tip.style.left = tx + 'px';
        tip.style.top = ty + 'px';
        tip.style.display = 'block';
      }

      // Pointer handlers on SVG
      svg.on('pointermove', (event) => {
        const [mx, my] = d3.pointer(event);
        showTooltip(mx, my);
      });
      svg.on('pointerleave', () => { if (tip) tip.style.display = 'none'; });
    }

    // Try REST metrics first
    fetch(`${window.location.origin}/wp-json/aqlx/v1/metrics`).then(r => r.json()).then(json => {
      if (json && Array.isArray(json.points)) {
        render(json.points);
      } else if (json && Array.isArray(json.temperature)) {
        const points = json.temperature.map(d => ({ t: d.t, temperature: d.v, ph: 7.2, ammonia: 0.05 }));
        render(points);
      } else {
        throw new Error('no metrics');
      }
    }).catch(() => {
      // fallback to local sample with three series
      const points = Array.from({ length: 24 }, (_, i) => ({
        t: i,
        temperature: 24 + Math.sin(i/2) * 2,
        ph: 7.2 + Math.sin(i/3) * 0.2,
        ammonia: Math.max(0, 0.05 + Math.sin(i/4) * 0.02),
      }));
      render(points);
    }).finally(() => { el.setAttribute('aria-busy', 'false'); });
  }).catch(() => { el.setAttribute('aria-busy', 'false'); /* fail quietly */ });
}

function mountMiniGame() {
  const el = document.getElementById('aqlx-minigame');
  if (!el) return;
  let score = 0, food = [];
  const label = document.createElement('div');
  label.className = 'absolute top-2 right-2 text-xs px-2 py-1 rounded bg-white/70 dark:bg-slate-800/70';
  label.textContent = 'Score: 0'; el.appendChild(label);

  function dropFood(x) {
    food.push({ x, y: 0 });
  }
  function draw() {
    el.innerHTML = ''; el.appendChild(label);
    const w = el.clientWidth, h = el.clientHeight;
    // Draw fish as simple circles
    const fishCount = 8;
    for (let i = 0; i < fishCount; i++) {
      const f = document.createElement('div');
      f.className = 'absolute rounded-full bg-sky-500/80 dark:bg-sky-300/60';
      const fx = (i + ((Date.now()/1000 + i) % 1)) / fishCount * w;
      const fy = h/2 + Math.sin((Date.now()/600) + i) * (h/4);
      f.style.left = (fx - 8) + 'px'; f.style.top = (fy - 8) + 'px'; f.style.width = '16px'; f.style.height = '16px';
      el.appendChild(f);
    }
    // Draw food
    food.forEach((p) => {
      const d = document.createElement('div');
      d.className = 'absolute rounded-full bg-amber-400';
      d.style.left = (p.x - 4) + 'px'; d.style.top = (p.y - 4) + 'px'; d.style.width = '8px'; d.style.height = '8px';
      el.appendChild(d);
      p.y += 2; if (p.y > h) { score += 1; label.textContent = 'Score: ' + score; }
    });
    food = food.filter(p => p.y <= h);
    if (!prefersReducedMotion) requestAnimationFrame(draw);
  }
  draw();

  el.addEventListener('click', (e) => dropFood(e.offsetX));
  el.addEventListener('keydown', (e) => { if (e.code === 'Space') { e.preventDefault(); dropFood(el.clientWidth/2); } });
}

function setupAudioToggle() {
  const btn = document.getElementById('aqlx-audio-toggle');
  if (!btn) return;
  let ctx, source, oscGain;
  let on = false;
  btn.addEventListener('click', async () => {
    on = !on; btn.setAttribute('aria-pressed', String(on)); btn.textContent = 'Audio: ' + (on ? 'On' : 'Off');
    if (on) {
      ctx = new (window.AudioContext || window.webkitAudioContext)();
      const o = ctx.createOscillator(); oscGain = ctx.createGain();
      o.type = 'sine'; o.frequency.value = 120;
      oscGain.gain.value = 0.0008; o.connect(oscGain); oscGain.connect(ctx.destination); o.start(); source = o;
    } else if (source) { source.stop(); source.disconnect(); oscGain.disconnect(); }
  });
}

// Init on DOM ready
if (document.readyState !== 'loading') init(); else document.addEventListener('DOMContentLoaded', init);

function init() {
  mount3DScene();
  mountD3Chart();
  mountMiniGame();
  setupAudioToggle();
  mountScrollEffects();
  hydrateRecommendations();
}

function hydrateRecommendations() {
  const wrap = document.getElementById('aqlx-recos');
  if (!wrap) return;
  // Prefer our lightweight recos endpoint; fallback to WP posts
  fetch(`${window.location.origin}/wp-json/aqlx/v1/recos?limit=6`).then(r => r.json()).then(items => {
    if (!Array.isArray(items)) return;
    wrap.innerHTML = '';
    items.forEach(p => {
      const el = document.createElement('article');
      el.className = 'p-4 border rounded hover-lift';
  const title = (p.title && p.title.rendered) ? p.title.rendered : (p.title || '');
  const excerptRaw = (p.excerpt && p.excerpt.rendered) ? p.excerpt.rendered : (p.excerpt || '');
  const safeTitle = String(title).replace(/<[^>]+>/g,'');
  const img = typeof p.image === 'string' && p.image ? `<img src="${p.image}" alt="${safeTitle}" class="w-full h-36 object-cover rounded mb-3" loading="lazy" decoding="async"/>` : '';
  const price = typeof p.price === 'string' && p.price ? `<div class="mt-2 text-sm font-medium">${p.price}</div>` : '';
  el.innerHTML = `${img}<h3 class="font-semibold mb-2"><a class="hover:underline" href="${p.link}">${title}</a></h3>` +
         `<p class="text-sm opacity-80">${String(excerptRaw).replace(/<[^>]+>/g,'').slice(0,140)}...</p>${price}`;
      wrap.appendChild(el);
    });
    wrap.setAttribute('aria-busy', 'false');
  }).catch(() => {
    fetch(`${window.location.origin}/wp-json/wp/v2/posts?per_page=6&_fields=id,title,excerpt,link`).then(r => r.json()).then(items => {
      if (!Array.isArray(items)) throw new Error('no posts');
      wrap.innerHTML = '';
      items.forEach(p => {
        const el = document.createElement('article');
        el.className = 'p-4 border rounded hover-lift';
        el.innerHTML = `<h3 class=\"font-semibold mb-2\"><a class=\"hover:underline\" href=\"${p.link}\">${p.title.rendered}</a></h3>` +
                       `<p class=\"text-sm opacity-80\">${(p.excerpt?.rendered || '').replace(/<[^>]+>/g,'').slice(0,140)}...</p>`;
        wrap.appendChild(el);
      });
      wrap.setAttribute('aria-busy', 'false');
    }).catch(() => { wrap.setAttribute('aria-busy', 'false'); });
  });
}

function mountScrollEffects() {
  if (prefersReducedMotion) return;
  const targets = Array.from(document.querySelectorAll('[data-reveal]'));
  if (!targets.length) return;
  // Lazy-load GSAP + ScrollTrigger
  Promise.all([
    import(/* webpackChunkName: "gsap" */ 'gsap'),
    import(/* webpackChunkName: "scrolltrigger" */ 'gsap/ScrollTrigger'),
  ]).then(([gsapMod, stMod]) => {
    const gsap = gsapMod.gsap || gsapMod.default || gsapMod;
    const ScrollTrigger = stMod.ScrollTrigger || stMod.default || stMod;
    if (gsap && ScrollTrigger && gsap.registerPlugin) { gsap.registerPlugin(ScrollTrigger); }
    targets.forEach((el) => {
      // Set initial hidden state just-in-time to avoid FOUC without CSS dependency
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      gsap.to(el, {
        opacity: 1,
        y: 0,
        duration: 0.7,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: el,
          start: 'top 85%',
          once: true,
        }
      });
    });
  }).catch(() => {});
}
