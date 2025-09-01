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
  // Tune pixel ratio based on device capability
  const devMem = (typeof navigator !== 'undefined' && 'deviceMemory' in navigator) ? navigator.deviceMemory : 4;
  const dprCap = devMem <= 2 ? 1.5 : 2;
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, dprCap));

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

  // Seaweed: swaying blades (simple planes pivoted at bottom)
  const seaweedGroup = new THREE.Group();
  if (!reduce){
    const bladeGeom = new THREE.PlaneGeometry(0.25, 3.6, 1, 8);
    const greens = [0x0f766e, 0x10b981, 0x15803d, 0x065f46];
    for (let i=0;i<28;i++){
      const mat = new THREE.MeshStandardMaterial({ color: greens[i%greens.length], roughness: 1, side: THREE.DoubleSide });
      const blade = new THREE.Mesh(bladeGeom, mat);
      blade.position.set(-18 + Math.random()*36, -5.2, -10 - Math.random()*22);
      blade.scale.x = 0.8 + Math.random()*0.6;
      blade.userData.phase = Math.random()*Math.PI*2;
      blade.userData.amp = 0.12 + Math.random()*0.18;
      blade.userData.speed = 0.6 + Math.random()*0.6;
      blade.geometry.translate(0, 1.8, 0); // pivot at bottom
      seaweedGroup.add(blade);
    }
    scene.add(seaweedGroup);
  }

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

  // Fish model (procedural): variants with body + fins + optional markings
  function createFishMaterial(hex){
    return new THREE.MeshStandardMaterial({ color: hex, roughness: 0.5, metalness: 0.05 });
  }
  function baseFish(color){
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

  function createClownfish(){
    // Orange body with 3 white bands and thin dark borders
    const base = baseFish(0xff6a00);
    const bandMat = new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.6, metalness: 0.02, transparent: true, opacity: 0.95 });
    const borderMat = new THREE.MeshStandardMaterial({ color: 0x111111, roughness: 1, metalness: 0, transparent: true, opacity: 0.9 });
    const bandZ = 0.82; // slightly larger than body depth
    const makeBand = (x, w)=>{
      const band = new THREE.Mesh(new THREE.BoxGeometry(w, 0.7, bandZ), bandMat);
      band.position.set(x, 0.02, 0);
      // Thin borders
      const b1 = new THREE.Mesh(new THREE.BoxGeometry(0.04, 0.7, bandZ), borderMat); b1.position.set(x - w/2 - 0.02, 0.02, 0);
      const b2 = new THREE.Mesh(new THREE.BoxGeometry(0.04, 0.7, bandZ), borderMat); b2.position.set(x + w/2 + 0.02, 0.02, 0);
      base.group.add(band, b1, b2);
    };
    makeBand(0.0, 0.18); // middle
    makeBand(0.28, 0.14); // near head
    makeBand(-0.28, 0.14); // near tail
    return base;
  }

  function createAngelfish(){
    // Taller body with elongated dorsal/ventral fins
    const base = baseFish(0x4cc0ff);
    base.body.scale.set(1.4, 1.4, 0.7);
    const finMat = new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.8, metalness: 0.05, transparent: true, opacity: 0.85, side: THREE.DoubleSide });
    const dorsalTall = new THREE.Mesh(new THREE.PlaneGeometry(0.12, 0.9, 1, 1), finMat); dorsalTall.position.set(0.0, 0.75, 0); dorsalTall.rotation.y = Math.PI/2;
    const ventralTall = new THREE.Mesh(new THREE.PlaneGeometry(0.10, 0.8, 1, 1), finMat); ventralTall.position.set(0.05, -0.7, 0); ventralTall.rotation.y = Math.PI/2;
    base.group.add(dorsalTall, ventralTall);
    return base;
  }

  function createParrotfish(){
    // Thicker body with colored head patch
    const base = baseFish(0x2dd4bf); // teal body
    base.body.scale.set(2.0, 1.1, 0.9);
    const head = new THREE.Mesh(new THREE.SphereGeometry(0.25, 12, 8), createFishMaterial(0x22c55e));
    head.scale.set(1.0, 0.9, 0.9);
    head.position.set(0.45, 0.02, 0);
    base.group.add(head);
    return base;
  }

  function createFish(){
    const r = Math.random();
    if (r < 0.34) return createClownfish();
    if (r < 0.67) return createAngelfish();
    return createParrotfish();
  }

  const fishPalette = [0xff7ab6, 0xffd166, 0x9ae6b4, 0x60a5fa, 0xfca5a5, 0xf5d0fe, 0x7dd3fc];
  let fishCount = window.innerWidth < 640 ? 12 : window.innerWidth < 1024 ? 20 : 28;
  if (devMem <= 2) fishCount = Math.max(8, Math.floor(fishCount * 0.7));
  const fishes = [];
  for(let i=0;i<fishCount;i++){
    const f = createFish();
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
  window.addEventListener('touchmove', onPointer, { passive: true });

  // Ripples: subtle rings expanding at pointer location on click/tap
  function spawnRipple(pos){
    try {
      const geo = new THREE.RingGeometry(0.1, 0.2, 48);
      const mat = new THREE.MeshBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.25, side: THREE.DoubleSide });
      const ring = new THREE.Mesh(geo, mat);
      ring.rotation.x = -Math.PI/2;
      ring.position.set(pos.x, -4.95, pos.z);
      ring.renderOrder = 2;
      scene.add(ring);
      gsap.to(ring.scale, { x: 12, y: 12, duration: 2.2, ease: 'sine.out' });
      gsap.to(mat, { opacity: 0, duration: 2.2, ease: 'sine.out', onComplete: ()=>{ try{ scene.remove(ring); geo.dispose(); mat.dispose(); }catch(e){} } });
    } catch(e) { /* ignore */ }
  }
  // Feed pellets and user-triggered bubbles on click/tap
  const pellets = [];
  const pelletMeshes = [];
  function spawnPellet(pos){
    const p = { pos: pos.clone(), ttl: 6.0 };
    // Clamp to just above seabed to ensure visibility
    p.pos.y = Math.max(-4.8, Math.min(4, p.pos.y));
    pellets.push(p);
    // Visual pellet sphere
    try{
      const geom = new THREE.SphereGeometry(0.06, 10, 8);
      const mat = new THREE.MeshStandardMaterial({ color: 0xf5deb3, roughness: 1, metalness: 0, transparent: true, opacity: 0.95 });
      const mesh = new THREE.Mesh(geom, mat);
      mesh.position.copy(p.pos);
      mesh.renderOrder = 3;
      scene.add(mesh);
      pelletMeshes.push({ mesh, geom, mat, ref: p });
    }catch(_){ }
  }
  function spawnClickBubble(e){
    try {
      const rect = canvas.getBoundingClientRect();
      const cx = (('touches' in e) ? e.touches[0].clientX : e.clientX) - rect.left;
      const detail = { x: cx / Math.max(1, rect.width) };
      window.dispatchEvent(new CustomEvent('ax:bubble', { detail }));
    } catch(_) {}
  }
  canvas.addEventListener('pointerdown', (e)=>{ spawnRipple(pointerWorld.clone()); spawnPellet(pointerWorld.clone()); spawnClickBubble(e); playBubblePing(); }, { passive: true });

  // Optional subtle ambient audio (bubbles + water) controlled by toggle
  let audioEnabled = false;
  let audioCtx = null, ambientNode = null, gainNode = null, bubbleTimer = null;
  let volume = 0.06; // default gain
  function playBubblePing(){
    try {
      if (!audioEnabled || !audioCtx) return;
      const o = audioCtx.createOscillator();
      const g = audioCtx.createGain();
      o.type = 'sine'; o.frequency.value = 420 + Math.random()*180;
      g.gain.value = 0.0;
      o.connect(g).connect(audioCtx.destination);
      o.start();
      g.gain.linearRampToValueAtTime(0.04 * (volume/0.2), audioCtx.currentTime + 0.01);
      g.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.12);
      o.stop(audioCtx.currentTime + 0.14);
    } catch(e){}
  }
  function enableAudio(){
    if (audioEnabled || reduce) return; audioEnabled = true;
    try {
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      // Ambient low-pass noise
      const buf = audioCtx.createBuffer(1, audioCtx.sampleRate*2, audioCtx.sampleRate);
      const data = buf.getChannelData(0);
      for (let i=0;i<data.length;i++){ data[i] = (Math.random()*2-1)*0.15; }
      const src = audioCtx.createBufferSource(); src.buffer = buf; src.loop = true;
      const lp = audioCtx.createBiquadFilter(); lp.type = 'lowpass'; lp.frequency.setValueAtTime(600, audioCtx.currentTime);
  gainNode = audioCtx.createGain(); gainNode.gain.value = 0.0;
      src.connect(lp).connect(gainNode).connect(audioCtx.destination);
      src.start(); ambientNode = src;
  // Fade in to current volume
  gainNode.gain.linearRampToValueAtTime(volume, audioCtx.currentTime + 0.4);
      // Random bubble pings
      function bubble(){
        const o = audioCtx.createOscillator();
        const g = audioCtx.createGain();
        o.type = 'sine'; o.frequency.value = 300 + Math.random()*300;
        g.gain.value = 0.0;
        o.connect(g).connect(audioCtx.destination);
        o.start();
        g.gain.linearRampToValueAtTime(0.03, audioCtx.currentTime + 0.01);
        g.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.12);
        o.stop(audioCtx.currentTime + 0.14);
      }
      bubbleTimer = setInterval(()=>{ if (document.hidden) return; if (Math.random()<0.35) bubble(); }, 1200);
    } catch(e) { /* ignore */ }
  }
  function disableAudio(){
    if (!audioEnabled) return; audioEnabled = false;
    try {
      if (gainNode && audioCtx) {
        gainNode.gain.cancelScheduledValues(audioCtx.currentTime);
        gainNode.gain.linearRampToValueAtTime(0.0, audioCtx.currentTime + 0.2);
      }
      if (ambientNode) { setTimeout(()=>{ try{ ambientNode.stop(); }catch(e){} }, 240); }
      if (bubbleTimer) { clearInterval(bubbleTimer); bubbleTimer = null; }
      // keep context for quick resume
    } catch(e) { /* ignore */ }
  }
  const audioBtn = document.getElementById('ax-audio-toggle');
  const iconOn = audioBtn ? audioBtn.querySelector('.ax-ic-on') : null;
  const iconOff = audioBtn ? audioBtn.querySelector('.ax-ic-off') : null;
  const volSlider = document.getElementById('ax-audio-volume');
  const audioWrap = document.querySelector('.ax-hero-audio');
  let idleTimer = null;
  function markActive(){ if(!audioWrap) return; audioWrap.classList.remove('ax-idle'); if(idleTimer) clearTimeout(idleTimer); idleTimer = setTimeout(()=>audioWrap.classList.add('ax-idle'), 2500); }
  if (audioBtn) {
    // Restore preference
    try {
      const pref = localStorage.getItem('ax:audio');
      const savedVol = localStorage.getItem('ax:audio:vol');
      if (savedVol !== null) {
        const v = Math.max(0, Math.min(100, parseInt(savedVol,10)||0));
        volume = (v/100)*0.2;
        if (volSlider) volSlider.value = String(v);
      }
      if (pref === 'on') {
        enableAudio();
        audioBtn.setAttribute('aria-pressed','true');
        audioBtn.setAttribute('aria-label','Disable ambient sound');
        if (iconOn && iconOff) {
          const v = parseInt((savedVol||'60'),10) || 60;
          iconOn.hidden = (v === 0);
          iconOff.hidden = !(v === 0);
        }
        if (volSlider && (savedVol === '0')) audioBtn.setAttribute('data-muted','true');
      } else {
        audioBtn.setAttribute('aria-pressed','false');
        audioBtn.setAttribute('aria-label','Enable ambient sound');
        if (iconOn && iconOff) { iconOn.hidden = true; iconOff.hidden = false; }
        audioBtn.removeAttribute('data-muted');
      }
    } catch(e){}
    const doToggle = ()=>{
      if (!audioEnabled) {
        enableAudio();
        audioBtn.setAttribute('aria-pressed','true');
        audioBtn.setAttribute('aria-label','Disable ambient sound');
        if (iconOn && iconOff) { iconOn.hidden = false; iconOff.hidden = true; }
        try{ localStorage.setItem('ax:audio','on'); }catch(e){}
      } else {
        disableAudio();
        audioBtn.setAttribute('aria-pressed','false');
        audioBtn.setAttribute('aria-label','Enable ambient sound');
        if (iconOn && iconOff) { iconOn.hidden = true; iconOff.hidden = false; }
        try{ localStorage.setItem('ax:audio','off'); }catch(e){}
      }
      // When audio is off, consider it muted for label dimming
      if (audioBtn && !audioEnabled) audioBtn.setAttribute('data-muted','true'); else audioBtn.removeAttribute('data-muted');
      markActive();
    };
    audioBtn.addEventListener('click', doToggle);
    audioBtn.addEventListener('keydown', (e)=>{
      if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); doToggle(); }
    });
  }

  if (volSlider) {
    volSlider.addEventListener('input', ()=>{
      const v = Math.max(0, Math.min(100, parseInt(volSlider.value,10)||0));
      // Map 0..100 -> 0..0.2 for subtle volume range
      volume = (v/100) * 0.2;
      try { localStorage.setItem('ax:audio:vol', String(v)); } catch(e){}
      if (gainNode && audioCtx) {
        gainNode.gain.cancelScheduledValues(audioCtx.currentTime);
        gainNode.gain.linearRampToValueAtTime(volume, audioCtx.currentTime + 0.05);
      }
      if (iconOn && iconOff) { iconOn.hidden = (v === 0); iconOff.hidden = !(v === 0); }
      if (audioBtn) {
        audioBtn.setAttribute('aria-label', v === 0 ? 'Enable ambient sound' : (audioEnabled ? 'Disable ambient sound' : 'Enable ambient sound'));
        if (v === 0) audioBtn.setAttribute('data-muted','true'); else audioBtn.removeAttribute('data-muted');
      }
      markActive();
    });
    ['mousemove','pointermove','touchstart','focusin','keydown'].forEach(evt=>{
      window.addEventListener(evt, markActive, { passive: true });
    });
    if (audioWrap) { audioWrap.addEventListener('mouseenter', markActive); audioWrap.addEventListener('focusin', markActive); }
    markActive();
  }

  // Animation loop
  let running = true;
  // Pause when tab hidden
  document.addEventListener('visibilitychange', ()=>{ running = !document.hidden && inView; if (running) animate(); });
  // Pause when hero is offscreen
  let inView = true;
  if ('IntersectionObserver' in window){
    const io = new IntersectionObserver((entries)=>{
      const e = entries[0];
      inView = !!(e && e.isIntersecting);
      running = inView && !document.hidden;
      if (running) animate();
    }, { root: null, rootMargin: '0px', threshold: 0.05 });
    io.observe(canvas);
  }
  const clock = new THREE.Clock();

  function animate(){
    if (!running) return;
    requestAnimationFrame(animate);
    const dt = Math.min(clock.getDelta(), 0.033);

    // Gentle camera bob
    const t = performance.now()*0.001;
    camera.position.y = 1.2 + Math.sin(t*0.6)*0.05;
    camera.lookAt(lookTarget);

    // Seaweed sway
    if (!reduce && seaweedGroup.children.length){
      for (const b of seaweedGroup.children){
        const ph = b.userData.phase;
        const sp = b.userData.speed;
        const amp = b.userData.amp;
        b.rotation.z = Math.sin(performance.now()*0.001*sp + ph) * amp;
      }
    }

    // Fish movement and tail wag + interactions (hover boost, pellets)
    for (const f of fishes){
      // Tail wag
      if (!reduce) f.tail.rotation.z = Math.sin(t*8 + f.group.position.x)*0.5;

      // Steering towards pointerWorld with mild cohesion
      const toPointer = new THREE.Vector3().subVectors(pointerWorld, f.group.position).setY(0);
      const distP = toPointer.length();
      toPointer.normalize().multiplyScalar(0.02);
      f.dir.add(toPointer).normalize();

      // Attraction to nearest pellet (feeding)
      if (pellets.length){
        let nearest = null, nd=1e9;
        for (const p of pellets){
          const d = f.group.position.distanceTo(p.pos);
          if (d < nd){ nd = d; nearest = p; }
        }
        if (nearest){
          const pull = new THREE.Vector3().subVectors(nearest.pos, f.group.position).normalize().multiplyScalar(0.06);
          f.dir.add(pull).normalize();
          if (nd < 0.6){ nearest.ttl = 0; }
        }
      }

      // Slight upward drift and bounds
      f.dir.y = THREE.MathUtils.clamp(f.dir.y + (Math.random()-0.5)*0.01, -0.05, 0.05);
      // Hover acceleration: fish near the pointer swim a bit faster
      const boost = distP < 4 ? 1.5 : 1.0;
      f.group.position.addScaledVector(f.dir, (f.speed * boost) / (reduce?1.5:1));

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

    // Pellets sink slightly to seabed, then fade and expire
    for (let i=pellets.length-1;i>=0;i--){
      const p = pellets[i];
      p.ttl -= dt;
      if (p.pos.y > -4.8) p.pos.y -= 0.25*dt;
      if (p.ttl <= 0) pellets.splice(i,1);
    }
    for (let i=pelletMeshes.length-1;i>=0;i--){
      const m = pelletMeshes[i];
      m.mesh.position.copy(m.ref.pos);
      // fade last second
      if (m.ref.ttl < 1 && m.mat.opacity > 0){ m.mat.opacity = Math.max(0, m.ref.ttl); }
      if (m.ref.ttl <= 0){
        try{ scene.remove(m.mesh); m.geom.dispose(); m.mat.dispose(); }catch(_){ }
        pelletMeshes.splice(i,1);
      }
    }

    renderer.render(scene, camera);
  }
  animate();
})();
