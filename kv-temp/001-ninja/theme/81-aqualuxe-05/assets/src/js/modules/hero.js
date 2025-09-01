import * as THREE from 'three';
import gsap from 'gsap';

(function(){
  const canvas = document.getElementById('ax-hero-canvas');
  if (!canvas) return;

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(60, 1, 0.1, 1000);
  camera.position.set(0, 0, 5); // Ensure mesh is in view

  let renderer;
  try {
    renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
  } catch (e) {
    // WebGL unsupported; bail out and let the static hero show
    return;
  }
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  function resize(){
    const rect = canvas.getBoundingClientRect();
    const w = Math.max(1, Math.floor(rect.width || window.innerWidth));
    const h = Math.max(200, Math.floor(rect.height || Math.max(400, window.innerHeight*0.7)));
    renderer.setSize(w, h, false);
    camera.aspect = w / h; camera.updateProjectionMatrix();
  }
  window.addEventListener('resize', resize, { passive: true });
  // Resize once styles are applied
  if ('ResizeObserver' in window) { new ResizeObserver(()=>resize()).observe(canvas); }
  resize();

  const geometry = new THREE.IcosahedronGeometry(2, 2);
  const material = new THREE.MeshStandardMaterial({ color: 0x22aaff, metalness: 0.6, roughness: 0.3, wireframe: false });
  const mesh = new THREE.Mesh(geometry, material);
  scene.add(mesh);

  const light = new THREE.PointLight(0xffffff, 1.2, 100); light.position.set(5,5,5); scene.add(light);
  const light2 = new THREE.PointLight(0x66e1ff, 0.6, 100); light2.position.set(-5,-2,3); scene.add(light2);

  const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!reduce) {
    gsap.to(mesh.rotation, { y: Math.PI*2, duration: 20, repeat: -1, ease: 'linear' });
  }

  let running = true;
  document.addEventListener('visibilitychange', ()=>{ running = !document.hidden; if (running) animate(); });
  function animate(){ if (!running) return; requestAnimationFrame(animate); renderer.render(scene, camera); }
  animate();
})();
