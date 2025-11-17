export async function mountOcean(canvas) {
  const [{ WebGLRenderer, Scene, PerspectiveCamera, Color, Fog, DirectionalLight, AmbientLight, PlaneGeometry, MeshPhongMaterial, Mesh }, gsap] = await Promise.all([
    import('three').then(t => t),
    import('gsap').then(m => m.gsap)
  ]);

  const renderer = new WebGLRenderer({ canvas, antialias: true });
  const scene = new Scene();
  const camera = new PerspectiveCamera(55, 2, 0.1, 1000);
  camera.position.set(0, 10, 25);
  scene.background = new Color('#dbeafe');
  scene.fog = new Fog('#dbeafe', 40, 120);

  const dirLight = new DirectionalLight(0xffffff, 0.8);
  dirLight.position.set(5, 10, 7.5);
  scene.add(dirLight);
  scene.add(new AmbientLight(0xffffff, 0.6));

  const geo = new PlaneGeometry(100, 100, 100, 100);
  const mat = new MeshPhongMaterial({ color: '#0ea5e9', shininess: 80, flatShading: true });
  const mesh = new Mesh(geo, mat);
  mesh.rotation.x = -Math.PI / 2;
  scene.add(mesh);

  function resize() {
    const { clientWidth, clientHeight } = canvas;
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(clientWidth, clientHeight, false);
    camera.aspect = clientWidth / clientHeight;
    camera.updateProjectionMatrix();
  }
  resize();
  window.addEventListener('resize', resize);

  // Animate gentle waves via vertex displacement
  const pos = geo.attributes.position;
  const initial = pos.array.slice();
  let t = 0;
  function animate() {
    t += 0.005;
    for (let i = 0; i < pos.count; i++) {
      const ix = i * 3;
      const x = initial[ix];
      const y = initial[ix + 1];
      const z = Math.sin((x + t) * 0.3) * 0.5 + Math.cos((y + t) * 0.2) * 0.5;
      pos.array[ix + 2] = z;
    }
    pos.needsUpdate = true;
    renderer.render(scene, camera);
    requestAnimationFrame(animate);
  }
  animate();
}
