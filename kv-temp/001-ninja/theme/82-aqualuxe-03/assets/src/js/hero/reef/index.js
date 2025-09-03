import * as THREE from 'three';
import { loadSpeciesMeshes, SPECIES, swimBehaviorFor } from './species';

export async function startReef(container){
  // eslint-disable-next-line no-undef
  const DIST_BASE = (typeof __webpack_public_path__ !== 'undefined' && __webpack_public_path__)
    ? __webpack_public_path__ : '/wp-content/themes/aqualuxe/assets/dist/';

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(60, container.clientWidth/container.clientHeight, 0.1, 60);
  const baseCam = { x: 0, y: 0.12, z: 5.2 };
  camera.position.set(baseCam.x, baseCam.y, baseCam.z);
  camera.lookAt(0,0,0);
  const renderer = new THREE.WebGLRenderer({ antialias:true, alpha:true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(container.clientWidth, container.clientHeight);
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.1;
  renderer.domElement.style.position='absolute'; renderer.domElement.style.inset='0'; renderer.domElement.style.zIndex='1'; renderer.domElement.style.pointerEvents='none';
  container.style.position='relative'; container.appendChild(renderer.domElement);

  // Track overlay interaction to expand avoidance when users interact with CTAs/text
  const overlayRoot = document.querySelector('.alx-hero-overlay .pointer-events-auto') || document.querySelector('.alx-hero-overlay');
  let overlayActive = false;
  const onOverlayEnter = ()=>{ overlayActive = true; };
  const onOverlayLeave = ()=>{ overlayActive = false; };
  const onFocusIn = (e)=>{ if (overlayRoot && overlayRoot.contains(e.target)) overlayActive = true; };
  const onFocusOut = (e)=>{ if (overlayRoot && overlayRoot.contains(e.target)) overlayActive = false; };
  if (overlayRoot){
    overlayRoot.addEventListener('mouseenter', onOverlayEnter);
    overlayRoot.addEventListener('mouseleave', onOverlayLeave);
    document.addEventListener('focusin', onFocusIn);
    document.addEventListener('focusout', onFocusOut);
  }

  // Optional postprocessing (bloom). Enable with data-reef-post="bloom|on"
  const wantPost = (container.getAttribute('data-reef-post')||'').toLowerCase();
  let composer=null, RenderPass=null, EffectComposer=null, UnrealBloomPass=null;
  if (wantPost==='bloom' || wantPost==='on'){
    try{
      const modA = await import('three/examples/jsm/postprocessing/EffectComposer.js');
      const modB = await import('three/examples/jsm/postprocessing/RenderPass.js');
      const modC = await import('three/examples/jsm/postprocessing/UnrealBloomPass.js');
      EffectComposer = modA.EffectComposer; RenderPass = modB.RenderPass; UnrealBloomPass = modC.UnrealBloomPass;
  composer = new EffectComposer(renderer);
      composer.addPass(new RenderPass(scene, camera));
  const bloom = new UnrealBloomPass(new THREE.Vector2(container.clientWidth, container.clientHeight), 0.18, 0.7, 0.9);
      composer.addPass(bloom);
    } catch(e){ composer=null; }
  }

  // Fog and lighting for underwater feel
  scene.fog = new THREE.FogExp2(0x05253b, 0.22);
  const amb = new THREE.AmbientLight(0x7fb6ff, 0.85); scene.add(amb);
  const hemi = new THREE.HemisphereLight(0x9fd3ff, 0x06263c, 0.6); scene.add(hemi);
  const dir = new THREE.DirectionalLight(0x9fd3ff, 0.8); dir.position.set(-0.5,1,0.6); scene.add(dir);

  // Seabed & corals (instanced simple geometry, local-only)
  const floorGeo = new THREE.PlaneGeometry(6,4); const floorMat=new THREE.MeshPhongMaterial({color:0x06263c, side:THREE.DoubleSide, shininess:10});
  const floor = new THREE.Mesh(floorGeo, floorMat); floor.rotation.x=-Math.PI/2; floor.position.y=-0.48; floor.position.z=-0.6; floor.receiveShadow=true; scene.add(floor);

  function addSeaweed(){
    const group = new THREE.Group();
    const geo = new THREE.BoxGeometry(0.05,1,0.05);
    const anchors = [];
    for(let i=0;i<20;i++){
      const m = new THREE.MeshPhongMaterial({color:0x2aa66a});
      const s = new THREE.Mesh(geo, m);
      const px = THREE.MathUtils.randFloatSpread(1.8);
      const py = -0.45;
      const pz = -THREE.MathUtils.randFloat(0.1,0.6);
      s.position.set(px, py, pz);
      s.scale.y = THREE.MathUtils.randFloat(0.4,0.9);
      group.add(s);
      anchors.push(new THREE.Vector3(px, py + s.scale.y*0.4, 0));
    }
    scene.add(group);
    return { group, anchors };
  }
  const seaweed = addSeaweed();

  function addAnemones(){
    const pts=[]; const group = new THREE.Group();
    for(let i=0;i<3;i++){
      const g = new THREE.SphereGeometry(0.1, 16, 12);
      const m = new THREE.MeshStandardMaterial({color:0x8054aa, roughness:0.9});
      const an = new THREE.Mesh(g, m); an.position.set(-0.8 + i*0.4, -0.38, -0.2 - i*0.05); group.add(an); pts.push(an.position.clone());
    }
    scene.add(group); return { group, homes: pts };
  }
  const anem = addAnemones();

  // Caustics overlay (procedural shader)
  const causticsUni = { t:{ value:0 }, color:{ value: new THREE.Color(0x78c9ff) }, strength:{ value:0.18 } };
  const causticsMat = new THREE.ShaderMaterial({
    uniforms: causticsUni,
    transparent:true, depthWrite:false, blending:THREE.AdditiveBlending,
    vertexShader:`varying vec2 vUv; void main(){ vUv=uv; gl_Position=projectionMatrix*modelViewMatrix*vec4(position,1.0); }`,
    fragmentShader:`varying vec2 vUv; uniform float t; uniform vec3 color; uniform float strength;
      float hash(vec2 p){ return fract(sin(dot(p, vec2(127.1,311.7)))*43758.5453);
      }
      float noise(vec2 p){ vec2 i=floor(p), f=fract(p); float a=hash(i), b=hash(i+vec2(1.0,0.0)), c=hash(i+vec2(0.0,1.0)), d=hash(i+vec2(1.0,1.0)); vec2 u=f*f*(3.0-2.0*f); return mix(a,b,u.x)+ (c-a)*u.y*(1.0-u.x) + (d-b)*u.x*u.y; }
      void main(){ vec2 uv=vUv*4.0; float n1=noise(uv + vec2(t*0.1, t*0.12)); float n2=noise(uv*1.8 + vec2(-t*0.07, t*0.05)); float c=n1*n2; float v=smoothstep(0.55,0.9,c); vec3 col=color* (v*strength); gl_FragColor=vec4(col, v*strength*1.2); }`
  });
  const causticsPlane = new THREE.Mesh(new THREE.PlaneGeometry(6,4), causticsMat); causticsPlane.position.z=-0.5; scene.add(causticsPlane);

  // Bioluminescent points
  const bioGeo = new THREE.BufferGeometry(); const Nbio=120; const pos=new Float32Array(Nbio*3); const phase=new Float32Array(Nbio);
  for(let i=0;i<Nbio;i++){ pos[i*3]=THREE.MathUtils.randFloatSpread(2.6); pos[i*3+1]=THREE.MathUtils.randFloat(-0.4,0.6); pos[i*3+2]=THREE.MathUtils.randFloat(-0.6,0.0); phase[i]=Math.random()*Math.PI*2; }
  bioGeo.setAttribute('position', new THREE.BufferAttribute(pos,3)); bioGeo.setAttribute('phase', new THREE.BufferAttribute(phase,1));
  const bioMat = new THREE.PointsMaterial({ color:0x7ee6ff, size:0.02, transparent:true, opacity:0.0, depthWrite:false, blending:THREE.AdditiveBlending });
  const biolum = new THREE.Points(bioGeo, bioMat); scene.add(biolum);

  // Pointer interactivity (parallax + scatter), disabled for reduced motion
  const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  let nxPointer = 0, nyPointer = 0; // normalized [-1,1]
  let scatterT = 0; const SCATTER_COOLDOWN = 0.35;
  function onPointerMove(e){ if (prefersReduced) return; const rect = container.getBoundingClientRect(); nxPointer = ((e.clientX - rect.left)/rect.width)*2 - 1; nyPointer = -(((e.clientY - rect.top)/rect.height)*2 - 1); }
  function onPointerDown(e){ if (prefersReduced) return; scatterT = SCATTER_COOLDOWN; const rect=container.getBoundingClientRect(); const nx=((e.clientX-rect.left)/rect.width)*2 - 1; const ny=-(((e.clientY-rect.top)/rect.height)*2 - 1); const wp = worldPointAtZ(nx, ny, -0.25); for(let k=0;k<8;k++){ spawnBubble(wp.x + THREE.MathUtils.randFloatSpread(0.2)); } }
  container.addEventListener('pointermove', onPointerMove);
  container.addEventListener('pointerdown', onPointerDown);

  // Particles: bubbles
  const bubbleGeo = new THREE.SphereGeometry(0.01, 8, 8);
  const bubbleMat = new THREE.MeshBasicMaterial({color:0x9fd6ff, transparent:true, opacity:0.8});
  const bubbles=[]; function spawnBubble(x){ const m=new THREE.Mesh(bubbleGeo,bubbleMat.clone()); m.position.set(x,-0.42,-0.1); m.userData.vy=THREE.MathUtils.randFloat(0.08,0.16); scene.add(m); bubbles.push(m); }
  for(let i=0;i<10;i++) spawnBubble(THREE.MathUtils.randFloatSpread(1.6));

  // Load species
  const allowPlaceholders = (container.getAttribute('data-reef-placeholders') === 'on');
  // Prefer a server-generated whitelist provided via data-reef-whitelist to avoid any network probing
  const wlAttr = container.getAttribute('data-reef-whitelist') || '';
  const wlParts = wlAttr.split(',').map(s=>s.trim()).filter(Boolean);
  const wl = wlParts.length ? new Set(wlParts) : null;
  let entries = await loadSpeciesMeshes(DIST_BASE, { allowPlaceholders, whitelist: wl });
  // If nothing loaded (e.g., all GLBs missing), fallback to lightweight placeholders to keep hero lively
  if (!entries || entries.length === 0){
    entries = await loadSpeciesMeshes(DIST_BASE, { allowPlaceholders: true, whitelist: wl });
  }
  const fish=[]; const group = new THREE.Group(); scene.add(group);
  function makeAgent(s, idx){
    const base = s.mesh.clone();
    // Brighten fish materials for visibility
    base.traverse((o)=>{
      if (o.isMesh && o.material){
        const m = o.material;
        if (m.isMeshStandardMaterial){ m.metalness = 0.0; m.roughness = 0.55; m.emissive = new THREE.Color(0x0a1624); m.emissiveIntensity = 0.5; }
        else if (m.isMeshPhongMaterial){ m.shininess = 28; m.emissive = new THREE.Color(0x0a1624); m.emissiveIntensity = 0.5; }
        else if (m.isMeshLambertMaterial){ m.emissive = new THREE.Color(0x0a1624); m.emissiveIntensity = 0.55; }
      }
    });
    const bh = swimBehaviorFor(s.role);
  const agent = { species:s.key, role:s.role, mesh:base, vel:new THREE.Vector3(THREE.MathUtils.randFloat(0.25,0.55),0,0), speed:THREE.MathUtils.randFloat(0.4,0.65),
  radius:0.05, avoidCd:0, home:new THREE.Vector3(THREE.MathUtils.randFloatSpread(1.2), THREE.MathUtils.randFloat(bh.depth[0], bh.depth[1]), 0), bh };
  base.position.copy(agent.home);
  // Keep fish further behind the overlay plane to avoid occlusion
  base.position.z = -0.25;
  group.add(base); return agent;
  }
  for(const s of entries){
    const per = Math.min(4, s.count||1);
    for(let i=0;i<per;i++){ fish.push(makeAgent(s,i)); }
  }

  // Assign ecological anchors
  for (const f of fish){
    if (f.species==='clownfish' && anem.homes.length){ f.home = anem.homes[Math.floor(Math.random()*anem.homes.length)].clone().add(new THREE.Vector3(THREE.MathUtils.randFloat(-0.12,0.12), THREE.MathUtils.randFloat(-0.06,0.1), 0)); f.radius=0.035; }
    if (f.role==='school'){ f.radius=0.04; }
    if (f.species==='shark_great_white'){ f.home = new THREE.Vector3(0, -0.25, 0); f.speed=0.5; f.radius=0.12; }
  }

  // Helpers
  const tmpV = new THREE.Vector3();
  function steerTowards(a, target, maxDelta){ tmpV.copy(target).sub(a); const d=tmpV.length(); if (d>0){ tmpV.divideScalar(d).multiplyScalar(maxDelta); } return tmpV; }

  // Resize
  function onResize(){ const w=container.clientWidth,h=container.clientHeight; camera.aspect=w/h; camera.updateProjectionMatrix(); renderer.setSize(w,h); }
  // Initial fit: if aspect is very wide or very short, back the camera off slightly
  (function initialFit(){ const a = container.clientWidth/Math.max(1,container.clientHeight); if (a<1.5){ camera.position.z = 5.6; } else { camera.position.z = 5.2; } })();
  window.addEventListener('resize', onResize);

  // Project a normalized pointer into world at a specific Z plane
  function worldPointAtZ(nx, ny, zPlane){ const origin = camera.position.clone(); const target = new THREE.Vector3(nx, ny, 0.5).unproject(camera); const dir = target.sub(origin).normalize(); const t = (zPlane - origin.z)/dir.z; return origin.add(dir.multiplyScalar(t)); }

  let last=performance.now();
  function tick(now){
    const dt=Math.min(0.05,(now-last)/1000); last=now;
  // If page is not visible, skip updates to save CPU/GPU
  if (document.hidden){ requestAnimationFrame(tick); return; }
    const t=now*0.001;
    if (!prefersReduced){
      // Camera parallax towards pointer
      const targetX = baseCam.x + nxPointer*0.18;
      const targetY = baseCam.y + nyPointer*0.10;
      camera.position.x = THREE.MathUtils.lerp(camera.position.x, targetX, 0.06);
      camera.position.y = THREE.MathUtils.lerp(camera.position.y, targetY, 0.06);
      camera.lookAt(0,0,0);
      scatterT = Math.max(0, scatterT - dt);
    }

  // Animate caustics
  causticsUni.t.value = t;
  bioMat.opacity = 0.15 + 0.1*Math.sin(t*0.8);

    // Bubble update
    for(let i=bubbles.length-1;i>=0;i--){ const b=bubbles[i]; b.position.y += b.userData.vy*dt; b.material.opacity = 0.6 + 0.4*Math.sin((t+i)*2); if (b.position.y>0.4){ b.position.y=-0.42; b.position.x=THREE.MathUtils.randFloatSpread(1.6);} }

  // Simple school reaction to shark proximity
    const shark = fish.find(f=>f.species==='shark_great_white');
    let sharkPos = null; if (shark) sharkPos = shark.mesh.position;

  // Cursor influence point (for scatter)
  let cursorW = null; if (!prefersReduced){ cursorW = worldPointAtZ(nxPointer, nyPointer, -0.25); }

    // Compute overlay avoid rect in world space (z=0 plane), target inner content wrapper
    let avoidRect=null; {
      let overlay = document.querySelector('.alx-hero-overlay .pointer-events-auto'); if (!overlay) overlay = document.querySelector('.alx-hero-overlay');
      if (overlay){
        const rect = overlay.getBoundingClientRect(); const cRect = container.getBoundingClientRect();
        const hitAtZ0 = (nx, ny) => { const origin = camera.position.clone(); const target = new THREE.Vector3(nx, ny, 0.5).unproject(camera); const dir = target.sub(origin).normalize(); const tInt = (0 - origin.z)/dir.z; return origin.add(dir.multiplyScalar(tInt)); };
        const nxL = (rect.left - cRect.left)/cRect.width * 2 - 1; const nxR = (rect.right - cRect.left)/cRect.width * 2 - 1;
        const nyT = -((rect.top - cRect.top)/cRect.height * 2 - 1); const nyB = -((rect.bottom - cRect.top)/cRect.height * 2 - 1);
    const tl = hitAtZ0(nxL, nyT); const br = hitAtZ0(nxR, nyB);
    const minX=Math.min(tl.x,br.x), maxX=Math.max(tl.x,br.x); const minY=Math.min(tl.y,br.y), maxY=Math.max(tl.y,br.y);
    const boost = overlayActive ? 1.5 : 1.0;
  const padX = (maxX - minX) * (0.24*boost) + 0.08*boost; const padY = (maxY - minY) * (0.26*boost) + 0.08*boost;
        avoidRect = { minX:minX-padX, maxX:maxX+padX, minY:minY-padY, maxY:maxY+padY };
      }
    }

  // Boids-lite per species
    for (let i=0;i<fish.length;i++){
      const f = fish[i];
      const m = f.mesh; const v=f.vel; const maxF = 0.8*dt;
      f.avoidCd = Math.max(0, f.avoidCd - dt);
      // Home/role bias
      const hb = f.bh; if (hb){
        // Depth clamp
        if (m.position.y > hb.depth[1]) v.y -= 0.2*dt; if (m.position.y < hb.depth[0]) v.y += 0.2*dt;
        // Home attraction
        const toHome = steerTowards(m.position, f.home, (hb.homeBias||0.0)*maxF);
        v.add(toHome);
      }
  if (f.role==='seaweed' && seaweed && seaweed.anchors && seaweed.anchors.length){
        // Attract to nearest seaweed anchor for weaving behavior
        let best=null, bd=1e9; for(const p of seaweed.anchors){ const dx=p.x-m.position.x, dy=p.y-m.position.y; const d2=dx*dx+dy*dy; if (d2<bd){ bd=d2; best=p; } }
        if (best){ const steer=steerTowards(m.position, best, 0.5*maxF); v.add(steer); }
      }
  // Scatter from cursor when active
  if (!prefersReduced && cursorW){ const dx=m.position.x-cursorW.x, dy=m.position.y-cursorW.y; const d2 = dx*dx+dy*dy; const influence = scatterT>0 ? 0.8 : 0.25; const r2 = (scatterT>0 ? 0.35 : 0.18); if (d2<r2*r2){ const away = steerTowards(m.position, new THREE.Vector3(m.position.x+dx, m.position.y+dy, 0), influence*maxF); v.add(away); } }
      // Species behaviors
      if (f.role==='school'){
        // Align/cohere with near tangs
        let ax=0,ay=0,cx=0,cy=0,n=0; for(let j=0;j<fish.length;j++){ const o=fish[j]; if (o===f) continue; if (o.role!=='school') continue; const dx=o.mesh.position.x-m.position.x, dy=o.mesh.position.y-m.position.y; const d2=dx*dx+dy*dy; if (d2<0.2*0.2){ ax+=o.vel.x; ay+=o.vel.y; cx+=o.mesh.position.x; cy+=o.mesh.position.y; n++; } }
        if (n>0){ const ali = steerTowards(v, new THREE.Vector3(ax/n, ay/n,0), 0.5*maxF); v.add(ali); const coh= steerTowards(m.position, new THREE.Vector3(cx/n, cy/n,0), 0.35*maxF); v.add(coh); }
        // Flee shark
        if (sharkPos){ const dx=m.position.x-sharkPos.x, dy=m.position.y-sharkPos.y; const d2=dx*dx+dy*dy; if (d2<0.5*0.5){ const flee=steerTowards(m.position, new THREE.Vector3(m.position.x + dx, m.position.y + dy,0), 1.0*maxF); v.add(flee); }}
      }
      if (f.species==='parrotfish'){
        // Graze near floor: slight downward bias and nibble sparkles (opacity wiggle)
        if (Math.random()<0.004){ spawnBubble(m.position.x + THREE.MathUtils.randFloat(-0.05,0.05)); }
      }
      if (f.species==='clownfish'){
        // Darting around anemone
        v.x += THREE.MathUtils.randFloat(-0.2,0.2)*dt; v.y += THREE.MathUtils.randFloat(-0.2,0.2)*dt;
      }
      if (f.species==='shark_great_white'){
        // Slow patrol left-right
        if (Math.abs(m.position.x) > 1.2) v.x *= -1; v.y = THREE.MathUtils.lerp(v.y, 0, 0.02);
      }

      // Gentle damping and clamp
      v.multiplyScalar(1 - Math.min(0.1*dt, 0.08));
      const maxS = f.speed || 0.6; const len=v.length(); if (len>maxS) v.setLength(maxS);
  // Add gentle sway to avoid static poses
  v.y += 0.05*Math.sin(t*1.2 + i)*dt;
  m.position.addScaledVector(v, dt);
      m.rotation.z = Math.atan2(v.y, v.x);

  // Keep within soft world bounds (narrower)
  if (m.position.x < -1.0){ m.position.x = -1.0; v.x = Math.abs(v.x); }
  if (m.position.x >  1.0){ m.position.x =  1.0; v.x = -Math.abs(v.x); }
  if (m.position.y < -0.4){ m.position.y = -0.4; v.y = Math.abs(v.y); }
  if (m.position.y >  0.45){ m.position.y =  0.45; v.y = -Math.abs(v.y); }

      // No-swim rect: slide along edges to keep text/CTAs clear
      if (avoidRect && f.avoidCd<=0){
        const r = m.userData.radius || f.radius || 0.05;
        const x=m.position.x, y=m.position.y;
        // Inflate rect by radius to treat fish as a disc, not a point
        const minX = avoidRect.minX - r, maxX = avoidRect.maxX + r, minY=avoidRect.minY - r, maxY=avoidRect.maxY + r;
        if (x>minX && x<maxX && y>minY && y<maxY){
          const dL=Math.abs(x-minX), dR=Math.abs(maxX-x), dB=Math.abs(y-minY), dT=Math.abs(maxY-y);
          const mmin=Math.min(dL,dR,dB,dT); const nudge=0.04; let nx=0,ny=0,tx=0,ty=0;
          if (mmin===dB){ ny=-1; tx=1; ty=0; m.position.y=minY - nudge; }
          else if (mmin===dT){ ny=1; tx=1; ty=0; m.position.y=maxY + nudge; }
          else if (mmin===dL){ nx=-1; tx=0; ty=1; m.position.x=minX - nudge; }
          else { nx=1; tx=0; ty=1; m.position.x=maxX + nudge; }
          const vt = v.x*tx + v.y*ty; const dirT = vt>=0 ? 1 : -1; const targetSpeed = Math.max(0.25, (f.speed||0.6)*0.9);
          v.x = tx*dirT*targetSpeed + nx*0.15; v.y = ty*dirT*targetSpeed + ny*0.15; f.avoidCd = 0.35;
        }
      }
    }

    // Soft pair push-out to prevent overlaps
    for(let i=0;i<fish.length;i++){
      for(let j=i+1;j<fish.length;j++){
        const a=fish[i], b=fish[j]; const dx=b.mesh.position.x-a.mesh.position.x, dy=b.mesh.position.y-a.mesh.position.y; const d=Math.hypot(dx,dy); const minD=(a.radius||0.05)+(b.radius||0.05); if (d>1e-6 && d<minD){ const push=(minD-d)*0.5; const nx=dx/d, ny=dy/d; a.mesh.position.x -= nx*push; a.mesh.position.y -= ny*push; b.mesh.position.x += nx*push; b.mesh.position.y += ny*push; }
      }
    }

  if (composer){ composer.render(); } else { renderer.render(scene,camera); }
    requestAnimationFrame(tick);
  }
  requestAnimationFrame(tick);

  return { stop: ()=>{ try{ container.removeChild(renderer.domElement);}catch(e){} try{ container.removeEventListener('pointermove', onPointerMove); container.removeEventListener('pointerdown', onPointerDown);}catch(e){} if (overlayRoot){ try{ overlayRoot.removeEventListener('mouseenter', onOverlayEnter); overlayRoot.removeEventListener('mouseleave', onOverlayLeave); document.removeEventListener('focusin', onFocusIn); document.removeEventListener('focusout', onFocusOut);}catch(e){} } } };
}
