// Lightweight hero animation stub with Three.js/GSAP optional import
// Loaded only on front page via conditional enqueue (progressive enhancement)
export async function initHero(canvasSelector) {
  const el = document.querySelector(canvasSelector);
  if (!el || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  // Dynamically import heavy libs for code-splitting
  const [{ Scene, PerspectiveCamera, WebGLRenderer, BoxGeometry, MeshBasicMaterial, Mesh }, gsap] = await Promise.all([
    import('three'),
    import('gsap')
  ]);
  const scene = new Scene();
  const camera = new PerspectiveCamera(75, el.clientWidth / el.clientHeight, 0.1, 1000);
  const renderer = new WebGLRenderer({ canvas: el, antialias: true, alpha: true });
  renderer.setSize(el.clientWidth, el.clientHeight);
  const geom = new BoxGeometry(1, 1, 1);
  const mat = new MeshBasicMaterial({ color: 0x0ea5e9, wireframe: true });
  const cube = new Mesh(geom, mat);
  scene.add(cube);
  camera.position.z = 3;
  function render() {
    renderer.render(scene, camera);
  }
  gsap.gsap.to(cube.rotation, { x: Math.PI * 2, y: Math.PI * 2, repeat: -1, duration: 20, ease: 'linear', onUpdate: render });
  window.addEventListener('resize', () => {
    const w = el.clientWidth, h = el.clientHeight;
    camera.aspect = w / h; camera.updateProjectionMatrix(); renderer.setSize(w, h);
    render();
  });
  render();
}

// Expose a safe global initializer for PHP inline script
window.aqlx_hero_init = () => initHero('#aqlx-hero');
