import * as THREE from 'three';
import gsap from 'gsap';

// Interactive fish tank hero: tropical fish schooling over coral reefs with bubbles & ripples
(function(){
  const canvas = document.getElementById('ax-hero-canvas');
  if (!canvas) return;

  const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Soft gradient background for depth (keeps renderer alpha for layering with SVG waves)
  canvas.style.background = 'linear-gradient(180deg, rgba(2,132,199,0.75) 0%, rgba(15,118,178,0.65) 35%, rgba(3,105,161,0.55) 60%, rgba(7,89,133,0.50) 100%)';
  canvas.style.backdropFilter = 'saturate(1.1)';

  // Three.js setup
  const scene = new THREE.Scene();
  scene.fog = new THREE.Fog(0x0a2233, 18, 42);
  const camera = new THREE.PerspectiveCamera(60, 1, 0.1, 200);
  camera.position.set(0, 1.2, 12);
  const lookTarget = new THREE.Vector3(0, 1, 0);

  let renderer;
  try {
    renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
  } catch (e) {
    return; // WebGL unsupported; fallback to static hero
  }
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));

  function resize(){
    const rect = canvas.getBoundingClientRect();
    const w = Math.max(1, Math.floor(rect.width || window.innerWidth));
    const h = Math.max(240, Math.floor(rect.height || Math.max(420, window.innerHeight*0.7)));
    renderer.setSize(w, h, false);
    camera.aspect = w / h; camera.updateProjectionMatrix();
  }
  window.addEventListener('resize', resize, { passive: true });
  if ('ResizeObserver' in window) { new ResizeObserver(()=>resize()).observe(canvas); }
  resize();

  // Lighting: soft blue ambient + moving caustic-like light
  const amb = new THREE.AmbientLight(0x88bbee, 0.55); scene.add(amb);
  const key = new THREE.PointLight(0xaee8ff, 1.0, 60); key.position.set(-6, 8, 8); scene.add(key);
  const fill = new THREE.PointLight(0x2ab7ca, 0.6, 80); fill.position.set(8, -2, -6); scene.add(fill);

  // Seabed plane
  const seabedMat = new THREE.MeshStandardMaterial({ color: 0x0c3a4e, roughness: 1, metalness: 0 });
  const seabed = new THREE.Mesh(new THREE.PlaneGeometry(160, 80, 1, 1), seabedMat);
  seabed.rotation.x = -Math.PI/2; seabed.position.y = -6; scene.add(seabed);

  // Decorative coral clusters (low poly)
  const coralColors = [0xffa3a3, 0xffd166, 0x7cd4ff, 0xa7f3d0, 0xf472b6];
  const coralGroup = new THREE.Group();
  function addCoralCluster(x,z,scale){
    const group = new THREE.Group();
    const trunk = new THREE.Mesh(new THREE.CylinderGeometry(0.2*scale, 0.35*scale, 2*scale, 6), new THREE.MeshStandardMaterial({ color: 0x5eead4, roughness: 0.9 }));
    trunk.position.set(0, -5+scale, 0);
    group.add(trunk);
    for(let i=0;i<5;i++){
      const col = coralColors[Math.floor(Math.random()*coralColors.length)];
      const bulb = new THREE.Mesh(new THREE.IcosahedronGeometry(0.6*scale + Math.random()*0.4*scale, 1), new THREE.MeshStandardMaterial({ color: col, roughness: 0.8 }));
      bulb.position.set((Math.random()-0.5)*2*scale, -4.4 + Math.random()*0.6*scale, (Math.random()-0.5)*2*scale);
      group.add(bulb);
    }
    group.position.set(x, 0, z);
    coralGroup.add(group);
  }
  for (let i=0;i<8;i++){
    addCoralCluster(-18 + Math.random()*36, -8 - Math.random()*24, 0.8 + Math.random()*1.5);
  }
  scene.add(coralGroup);

  // Fish model (procedural): body (ellipsoid) + tail (plane) with wag
  function createFishMaterial(hex){
    return new THREE.MeshStandardMaterial({ color: hex, roughness: 0.5, metalness: 0.05 });
  }
  function createFish(color){
    const group = new THREE.Group();
    const body = new THREE.Mesh(new THREE.SphereGeometry(0.35, 16, 12), createFishMaterial(color));
    body.scale.set(1.8, 1.0, 0.8);
    const tail = new THREE.Mesh(new THREE.PlaneGeometry(0.35, 0.25, 1, 1), new THREE.MeshStandardMaterial({ color: 0xffffff, transparent: true, opacity: 0.85, side: THREE.DoubleSide }));
    tail.position.x = -0.55; tail.rotation.y = Math.PI/2;
    const dorsal = new THREE.Mesh(new THREE.ConeGeometry(0.10, 0.25, 10), createFishMaterial(0xffffff));
    dorsal.position.set(0.05, 0.28, 0);
    group.add(body); group.add(tail); group.add(dorsal);
    return { group, body, tail };
  }

  const fishPalette = [0xff7ab6, 0xffd166, 0x9ae6b4, 0x60a5fa, 0xfca5a5, 0xf5d0fe, 0x7dd3fc];
  const fishCount = window.innerWidth < 640 ? 12 : window.innerWidth < 1024 ? 20 : 28;
  const fishes = [];
  for(let i=0;i<fishCount;i++){
    const color = fishPalette[i % fishPalette.length];
    const f = createFish(color);
    f.group.position.set(-12 + Math.random()*24, -2 + Math.random()*6, -12 + Math.random()*-20);
    f.group.rotation.y = Math.random()*Math.PI*2;
    f.speed = 0.02 + Math.random()*0.04; // units per frame
    f.turnSpeed = 0.02 + Math.random()*0.04;
    f.dir = new THREE.Vector3(0.5 + Math.random(), (Math.random()-0.5)*0.2, -0.2 - Math.random()*0.6).normalize();
    fishes.push(f);
    scene.add(f.group);
  }

  // Gentle moving light to simulate caustics
  if (!reduce) {
    gsap.to(key.position, { x: 6, z: -6, y: 10, duration: 12, yoyo: true, repeat: -1, ease: 'sine.inOut' });
  }

  // Pointer interactivity (parallax + subtle attraction)
  let pointer = new THREE.Vector2(0, 0);
  let pointerWorld = new THREE.Vector3();
  const raycaster = new THREE.Raycaster();
  function onPointer(e){
    const rect = canvas.getBoundingClientRect();
    const x = (('touches' in e) ? e.touches[0].clientX : e.clientX) - rect.left;
    const y = (('touches' in e) ? e.touches[0].clientY : e.clientY) - rect.top;
    pointer.set((x/rect.width)*2-1, -(y/rect.height)*2+1);
    // Parallax camera target
    lookTarget.x = THREE.MathUtils.lerp(lookTarget.x, pointer.x * 2.0, 0.08);
    lookTarget.y = THREE.MathUtils.lerp(lookTarget.y, 1 + pointer.y * 1.2, 0.08);
    // Project into world on seabed plane y=-5 for attraction
    raycaster.setFromCamera(pointer, camera);
    const plane = new THREE.Plane(new THREE.Vector3(0,1,0), 5);
    raycaster.ray.intersectPlane(plane, pointerWorld);
  }
  window.addEventListener('pointermove', onPointer, { passive: true });
  window.addEventListener('touchstart', onPointer, { passive: true });

  // Optional subtle ambient audio (bubbles + water), requires user gesture
  let audioStarted = false;
  function startAudio(){
    if (audioStarted || reduce) return; audioStarted = true;
    try {
      const ctx = new (window.AudioContext || window.webkitAudioContext)();
      // Ambient low-pass noise
      const buf = ctx.createBuffer(1, ctx.sampleRate*2, ctx.sampleRate);
      const data = buf.getChannelData(0);
      for (let i=0;i<data.length;i++){ data[i] = (Math.random()*2-1)*0.15; }
      const src = ctx.createBufferSource(); src.buffer = buf; src.loop = true;
      const lp = ctx.createBiquadFilter(); lp.type = 'lowpass'; lp.frequency.setValueAtTime(600, ctx.currentTime);
      const gain = ctx.createGain(); gain.gain.value = 0.06;
      src.connect(lp).connect(gain).connect(ctx.destination);
      src.start();
      // Random bubble pings
      function bubble(){
        const o = ctx.createOscillator();
        const g = ctx.createGain();
        o.type = 'sine'; o.frequency.value = 300 + Math.random()*300;
        g.gain.value = 0.0;
        o.connect(g).connect(ctx.destination);
        o.start();
        g.gain.linearRampToValueAtTime(0.03, ctx.currentTime + 0.01);
        g.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.12);
        o.stop(ctx.currentTime + 0.14);
      }
      const interval = setInterval(()=>{ if (document.hidden) return; if (Math.random()<0.35) bubble(); }, 1200);
      document.addEventListener('visibilitychange', ()=>{ if (document.hidden) { /* keep interval; audio is low */ } });
    } catch(e) { /* ignore audio errors */ }
  }
  canvas.addEventListener('pointerdown', startAudio, { once: true });

  // Animation loop
  let running = true;
  document.addEventListener('visibilitychange', ()=>{ running = !document.hidden; if (running) animate(); });
  const clock = new THREE.Clock();

  function animate(){
    if (!running) return;
    requestAnimationFrame(animate);
    const dt = Math.min(clock.getDelta(), 0.033);

    // Gentle camera bob
    const t = performance.now()*0.001;
    camera.position.y = 1.2 + Math.sin(t*0.6)*0.05;
    camera.lookAt(lookTarget);

    // Fish movement and tail wag
    for (const f of fishes){
      // Tail wag
      if (!reduce) f.tail.rotation.z = Math.sin(t*8 + f.group.position.x)*0.5;

      // Steering towards pointerWorld with mild cohesion
      const toPointer = new THREE.Vector3().subVectors(pointerWorld, f.group.position).setY(0).normalize().multiplyScalar(0.02);
      f.dir.add(toPointer).normalize();

      // Slight upward drift and bounds
      f.dir.y = THREE.MathUtils.clamp(f.dir.y + (Math.random()-0.5)*0.01, -0.05, 0.05);
      f.group.position.addScaledVector(f.dir, f.speed / (reduce?1.5:1));

      // Wrap around bounds for seamless loop
      if (f.group.position.x > 14) f.group.position.x = -14;
      if (f.group.position.x < -14) f.group.position.x = 14;
      if (f.group.position.z < -36) f.group.position.z = -8;
      if (f.group.position.z > -8) f.group.position.z = -36;
      if (f.group.position.y < -4) f.group.position.y = -4 + Math.random()*0.5;
      if (f.group.position.y > 5) f.group.position.y = 5 - Math.random()*0.5;

      // Orient fish along direction (rotate Y)
      const targetYaw = Math.atan2(f.dir.x, f.dir.z);
      f.group.rotation.y = THREE.MathUtils.lerp(f.group.rotation.y, targetYaw, f.turnSpeed);
    }

    renderer.render(scene, camera);
  }
  animate();
})();
