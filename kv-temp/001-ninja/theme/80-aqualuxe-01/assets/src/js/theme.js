// Entry point for theme JS
import '../css/theme.css';

// Dark mode toggle with persistent preference
(function(){
  const btn = document.getElementById('darkModeToggle');
  const root = document.documentElement;
  const key = 'aqualuxe:theme';
  if (!btn) return;
  const set = (mode) => {
    if (mode === 'dark') {
      root.classList.add('dark');
    } else {
      root.classList.remove('dark');
    }
    localStorage.setItem(key, mode);
    btn.setAttribute('aria-pressed', mode === 'dark' ? 'true' : 'false');
  };
  const pref = localStorage.getItem(key) || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark':'light');
  set(pref);
  btn.addEventListener('click', () => set(root.classList.contains('dark') ? 'light' : 'dark'));
})();

// Wishlist toggle buttons
(function(){
  function bind(){
    document.querySelectorAll('[data-wishlist]')?.forEach(btn=>{
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-product-id');
        const fd = new FormData();
        fd.append('action','aqualuxe_wishlist_toggle');
        fd.append('nonce', (window.AquaLuxe && AquaLuxe.nonce) || '');
        fd.append('product_id', id);
        fetch((window.AquaLuxe && AquaLuxe.ajax_url) || '/wp-admin/admin-ajax.php', {method:'POST', body:fd})
          .then(r=>r.json()).then(j=>{
            if(j.success){
              btn.classList.toggle('active');
            }
          });
      });
    });
  }
  document.addEventListener('DOMContentLoaded', bind);
})();

// Quick view modal (fetch product markup)
(function(){
  const modal = document.getElementById('aqlx-quick-view');
  if(!modal) return;
  const content = document.getElementById('qv-content');
  document.querySelectorAll('[data-qv]')?.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const url = btn.getAttribute('data-qv');
      modal.classList.remove('hidden');
      fetch(url).then(r=>r.text()).then(html=>{ content.innerHTML = html; });
    });
  });
  modal.querySelector('[data-qv-close]')?.addEventListener('click',()=>modal.classList.add('hidden'));
})();

// Ocean scene initializer for front page
(function(){
  const canvas = document.getElementById('oceanCanvas');
  if (!canvas) return;
  (async function(){
    const three = await import('three');
    const {Scene, PerspectiveCamera, WebGLRenderer, Points, BufferGeometry, BufferAttribute, PointsMaterial} = three;
    const renderer = new WebGLRenderer({canvas, antialias:true, alpha:true});
    const scene = new Scene();
    const camera = new PerspectiveCamera(55, 16/9, 0.1, 1000);
    camera.position.z = 35;
    const geometry = new BufferGeometry();
    const count = 8000; const positions = new Float32Array(count*3);
    for (let i=0;i<count;i++){ positions[i*3+0]=(Math.random()-0.5)*200; positions[i*3+1]=(Math.random()-0.5)*60; positions[i*3+2]=(Math.random()-0.5)*100; }
    geometry.setAttribute('position', new BufferAttribute(positions, 3));
    const material = new PointsMaterial({ color: 0x0ea5e9, size: 0.6, transparent:true, opacity:0.8 });
    const points = new Points(geometry, material); scene.add(points);
    function resize(){ const w = canvas.clientWidth || window.innerWidth; const h = canvas.clientHeight || 400; renderer.setSize(w,h,false); camera.aspect=w/h; camera.updateProjectionMatrix(); }
    window.addEventListener('resize', resize); resize();
    function animate(t){ points.rotation.y = t*0.0001; renderer.render(scene,camera); requestAnimationFrame(animate); }
    requestAnimationFrame(animate);
  })();
})();
