import * as THREE from 'three';
import { gsap } from 'gsap';
import { prefersReducedMotion, rand, makeVisuallyHidden } from './utils';
import { spawnFishes } from './fish';
import { BubblePool, updateBubbles } from './bubbles';
import { addCoral, updateCoral } from './coral';
import { addRipples, updateRipples } from './ripples';
import { startPlankton } from './plankton';
import { SFX } from './audio-sfx';

export function startAquarium(container, options={}){
  // Resolve dist base from webpack public path or fallback
  // eslint-disable-next-line no-undef
  const DIST_BASE = (typeof __webpack_public_path__ !== 'undefined' && __webpack_public_path__)
    ? __webpack_public_path__
    : '/wp-content/themes/aqualuxe/assets/dist/';

  // Check WebGL
  const glOk = (()=>{ try { const c=document.createElement('canvas'); return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl'))); } catch(e){ return false; } })();
  if(!glOk){
    // Fallback: static image background
    container.style.background = 'linear-gradient(180deg,#021d2e 0%, #05334f 100%)';
    container.style.backgroundImage = `url(${DIST_BASE}images/hero-fallback.svg)`;
    container.style.backgroundSize = 'cover'; container.style.backgroundPosition = 'center';
    return { stop: ()=>{} };
  }

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(55, container.clientWidth/container.clientHeight, 0.1, 100);
  camera.position.set(0,0.2,2.2);
  const renderer = new THREE.WebGLRenderer({ antialias:true, alpha:true, logarithmicDepthBuffer:false });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(container.clientWidth, container.clientHeight);
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  // Layering: canvas behind overlay text/buttons
  renderer.domElement.style.position = 'absolute';
  renderer.domElement.style.inset = '0';
  renderer.domElement.style.zIndex = '1';
  renderer.domElement.style.pointerEvents = 'none';
  renderer.domElement.style.display = 'block';
  container.style.position = 'relative';
  container.style.pointerEvents = 'auto';
  container.appendChild(renderer.domElement);

  // Boids parameters (configurable via data-* on container)
  const ds = container.dataset || {};
  const num = (v, d) => { const n = parseFloat(v); return Number.isFinite(n) ? n : d; };
  const BOIDS = {
    rSep: num(ds.boidsRSep, 0.12),
    rNeigh: num(ds.boidsRNeigh, 0.22),
    wSep: num(ds.boidsWSep, 0.6),
    wAli: num(ds.boidsWAli, 0.25),
    wCoh: num(ds.boidsWCoh, 0.18),
  };

  // Lighting (soft ambience + dir light)
  const amb = new THREE.AmbientLight(0x99c9ff, 0.85); scene.add(amb);
  const dir = new THREE.DirectionalLight(0xffffff, 0.6); dir.position.set(1,1,1); scene.add(dir);

  // Background gradient
  const gradGeo = new THREE.PlaneGeometry(6,4,1,1);
  const gradMat = new THREE.MeshBasicMaterial({ color: 0x052e49 });
  const grad = new THREE.Mesh(gradGeo, gradMat); grad.position.z = -1; grad.renderOrder = 0; scene.add(grad);

  // Layers
  const ripples = addRipples(scene);
  const coral = addCoral(scene);

  // Fishes
  const fishGroup = new THREE.Group(); fishGroup.renderOrder = 1; scene.add(fishGroup);
  const fishCount = Number((container.dataset||{}).fishCount) || options.fishCount || 20;
  const fishes = spawnFishes(fishCount);
  fishes.forEach(f=>{ fishGroup.add(f.mesh); f.setBounds({minX:-1.4,maxX:1.4,minY:-0.9,maxY:0.3}); });

  // Bubbles
  const bubbles = new BubblePool(64, scene);
  const activeBubbles=[];

  // SFX (gated via config)
  const sfxEnabled = (typeof window !== 'undefined' && window.AquaLuxeConfig && Number(window.AquaLuxeConfig.heroSfxEnabled) === 1);
  const sfx = sfxEnabled ? new SFX(`${DIST_BASE}audio/`) : { play(){}, enabled:false };

  // Accessibility live region
  const live = makeVisuallyHidden(); container.appendChild(live);

  // Interactions
  const state={ speedBoost:0, rippleEnergy:0, feed:{active:false, x:0, y:0, t:0} };
  container.addEventListener('mousemove', (e)=>{
    const x=(e.offsetX/container.clientWidth)*2-1; gsap.to(camera.position, { x:x*0.2, duration:0.4, overwrite:true });
    state.speedBoost=0.5;
    // Encourage nearby fish (simple boost to the right)
    fishes.forEach(f=>f.boost({x:0.6,y:0}, 0.2));
    state.rippleEnergy = Math.min(1, state.rippleEnergy + 0.1);
  });
  container.addEventListener('click', ()=>{
    for(let i=0;i<6;i++){ const b=bubbles.spawn(); activeBubbles.push({m:b,vy:THREE.MathUtils.lerp(0.2,0.6,Math.random())}); }
    sfx.play('bubble-pop',{volume:0.35}); live.textContent = 'Bubbles';
  });
  // Double click to feed fish: attract and emit particles
  function emitFeed(at){
    const geo = new THREE.BufferGeometry();
    const N = 40; const positions = new Float32Array(N*3); const velocities = new Float32Array(N*3);
    for(let i=0;i<N;i++){ positions[i*3]=at.x + (Math.random()-0.5)*0.1; positions[i*3+1]=at.y + (Math.random()-0.5)*0.1; positions[i*3+2]=-0.1; velocities[i*3]=(Math.random()-0.5)*0.2; velocities[i*3+1]=Math.random()*0.3; velocities[i*3+2]=0; }
    geo.setAttribute('position', new THREE.BufferAttribute(positions,3));
    const mat = new THREE.PointsMaterial({color:0xfff2b6, size:0.02, transparent:true, opacity:0.9});
    const pts = new THREE.Points(geo, mat); pts.userData.vel = velocities; pts.userData.life = 1.0; scene.add(pts);
    return pts;
  }
  let feedParticles = null;
  container.addEventListener('dblclick', (e)=>{
    const rect = container.getBoundingClientRect();
    const nx = (e.clientX - rect.left)/rect.width * 2 - 1;
    const ny = -((e.clientY - rect.top)/rect.height * 2 - 1);
    // Project to world at z ~ 0
    const vec = new THREE.Vector3(nx, ny, 0.5).unproject(camera);
    state.feed = {active:true, x:vec.x, y:vec.y, t:1.0};
    if (feedParticles) { scene.remove(feedParticles); feedParticles = null; }
    feedParticles = emitFeed({x:vec.x, y:vec.y});
    live.textContent = 'Feeding';
  });
  container.addEventListener('keydown', (e)=>{
    if (e.key==='f'){ fishes.forEach(f=>f.boost({x:0.8,y:0}, 0.6)); live.textContent='Fish speed boost'; }
    if (e.key==='m'){ sfx.enabled = !sfx.enabled; live.textContent = sfx.enabled? 'Sound on':'Sound off'; }
  });
  container.setAttribute('tabindex','0'); // keyboard focus
  container.setAttribute('role','img'); container.setAttribute('aria-label','Animated aquarium background');

  // Touch: spawn bubbles and slight camera parallax
  container.addEventListener('touchstart', (e)=>{
    const t = e.touches[0]; if (!t) return; const relX=(t.clientX-container.getBoundingClientRect().left)/container.clientWidth; gsap.to(camera.position, {x:(relX*2-1)*0.2, duration:0.3});
    for(let i=0;i<8;i++){ const b=bubbles.spawn(); activeBubbles.push({m:b,vy:THREE.MathUtils.lerp(0.25,0.6,Math.random())}); }
  sfx.play('bubble-pop',{volume:0.35});
    state.rippleEnergy = Math.min(1, state.rippleEnergy + 0.2);
  }, {passive:true});

  // Pinch zoom subtly adjusts camera fov
  let pinchDist = 0;
  container.addEventListener('touchmove', (e)=>{
    if (e.touches.length===2){
      const dx = e.touches[0].clientX - e.touches[1].clientX;
      const dy = e.touches[0].clientY - e.touches[1].clientY;
      const d = Math.hypot(dx,dy);
      if (!pinchDist) pinchDist = d;
      const delta = d - pinchDist; pinchDist = d;
      camera.fov = THREE.MathUtils.clamp(camera.fov - delta*0.02, 45, 65); camera.updateProjectionMatrix();
      state.rippleEnergy = Math.min(1, state.rippleEnergy + 0.05);
    }
  }, {passive:true});
  container.addEventListener('touchend', ()=>{ pinchDist = 0; }, {passive:true});

  // Plankton overlay
  const stopPlankton = startPlankton(container);

  // Resize
  function onResize(){ const w=container.clientWidth, h=container.clientHeight; camera.aspect=w/h; camera.updateProjectionMatrix(); renderer.setSize(w,h); }
  window.addEventListener('resize', onResize);

  // Loop
  let last=performance.now(); const maxFPS=60; const frameMin=1000/maxFPS;
  function tick(now){
    if (prefersReducedMotion()) return; // freeze
    const dt=Math.min(0.05,(now-last)/1000); if(now-last<frameMin) return requestAnimationFrame(tick); last=now;
    const t=now*0.001;

    // Update layers
    updateRipples(ripples, t);
    if (ripples && ripples.material && ripples.material.uniforms){
      ripples.material.uniforms.uEnergy.value = state.rippleEnergy;
      state.rippleEnergy = Math.max(0, state.rippleEnergy - dt*0.5);
    }
    updateCoral(coral, t);

    // Fish
    // Compute a strict screen-space "no-swim" rectangle (overlay) mapped precisely to the world z=0 plane
    let avoidRect = null;
    // Prefer the inner content wrapper so we don't block the entire hero
    let overlay = document.querySelector('.alx-hero-overlay .pointer-events-auto');
    if (!overlay) overlay = document.querySelector('.alx-hero-overlay');
    if (overlay){
      const rect = overlay.getBoundingClientRect();
      const cRect = container.getBoundingClientRect();
      const nxL = (rect.left - cRect.left) / cRect.width * 2 - 1;
      const nxR = (rect.right - cRect.left) / cRect.width * 2 - 1;
      const nyT = -((rect.top - cRect.top) / cRect.height * 2 - 1);
      const nyB = -((rect.bottom - cRect.top) / cRect.height * 2 - 1);

      // Helper: screen (NDC) -> intersection with z=0 world plane
      const hitAtZ0 = (nx, ny) => {
        const origin = camera.position.clone();
        const target = new THREE.Vector3(nx, ny, 0.5).unproject(camera);
        const dir = target.sub(origin).normalize();
        const tInt = (0 - origin.z) / dir.z; // plane z=0
        return origin.add(dir.multiplyScalar(tInt));
      };
      const tl = hitAtZ0(nxL, nyT);
      const br = hitAtZ0(nxR, nyB);
      // Build rect with small margin to ensure visual air gap
      const minX = Math.min(tl.x, br.x), maxX = Math.max(tl.x, br.x);
      const minY = Math.min(tl.y, br.y), maxY = Math.max(tl.y, br.y);
      const padX = (maxX - minX) * 0.10 + 0.03; // slightly larger buffer
      const padY = (maxY - minY) * 0.12 + 0.03;
      avoidRect = { minX: minX - padX, maxX: maxX + padX, minY: minY - padY, maxY: maxY + padY };
    }
    // Gentle boids steering (separation, alignment, cohesion) to avoid clumping and jitter
  const rSep = BOIDS.rSep, rNeigh = BOIDS.rNeigh; // radii
  const wSep = BOIDS.wSep, wAli = BOIDS.wAli, wCoh = BOIDS.wCoh; // weights
  for (let i=0;i<fishes.length;i++){
      const fi = fishes[i];
      let sx=0, sy=0, nSep=0; // separation accumulator
      let ax=0, ay=0, nAli=0; // alignment accumulator
      let cx=0, cy=0, nCoh=0; // cohesion accumulator
      for (let j=0;j<fishes.length;j++){
        if (i===j) continue; const fj=fishes[j];
        const dx = fj.position.x - fi.position.x; const dy = fj.position.y - fi.position.y; const d2 = dx*dx + dy*dy;
        if (d2 < rNeigh*rNeigh){
          // alignment & cohesion neighborhood
          ax += fj.velocity.x; ay += fj.velocity.y; nAli++;
          // Skip cohesion contribution if neighbor is inside avoidRect to reduce pull into overlay
          if (!avoidRect || !(fj.position.x>avoidRect.minX && fj.position.x<avoidRect.maxX && fj.position.y>avoidRect.minY && fj.position.y<avoidRect.maxY)){
            cx += fj.position.x; cy += fj.position.y; nCoh++;
          }
        }
        if (d2 < rSep*rSep && d2 > 1e-6){ // strong separation
          const inv = 1.0/Math.sqrt(d2);
          sx += -dx*inv; sy += -dy*inv; nSep++;
        }
      }
      // Apply forces (clamped to small delta per frame)
      const maxDelta = (fi.maxForce||0.9) * dt;
      if (nSep>0){ let mx=sx/nSep, my=sy/nSep; const mag=Math.hypot(mx,my); if (mag>0){ mx/=mag; my/=mag; fi.velocity.x += mx*maxDelta*wSep; fi.velocity.y += my*maxDelta*wSep; } }
      if (nAli>0){ let vx=ax/nAli, vy=ay/nAli; const mag=Math.hypot(vx,vy); if (mag>0){ vx/=mag; vy/=mag; fi.velocity.x += vx*maxDelta*wAli; fi.velocity.y += vy*maxDelta*wAli; } }
      if (nCoh>0){ let tx=cx/nCoh - fi.position.x, ty=cy/nCoh - fi.position.y; const mag=Math.hypot(tx,ty); if (mag>0){ tx/=mag; ty/=mag; fi.velocity.x += tx*maxDelta*wCoh; fi.velocity.y += ty*maxDelta*wCoh; } }
      // cool down timer for edge handling
      fi.avoidCd = Math.max(0, fi.avoidCd||0 - dt);
    }

    // Soft positional collision resolution to prevent residual overlaps (O(n^2) but small n)
    for (let i=0;i<fishes.length;i++){
      const fi=fishes[i]; const ri = fi.radius||0.06;
      for (let j=i+1;j<fishes.length;j++){
        const fj=fishes[j]; const rj = fj.radius||0.06;
        const dx = fj.position.x - fi.position.x; const dy = fj.position.y - fi.position.y;
        const d = Math.hypot(dx,dy);
        const minD = ri + rj;
        if (d>1e-6 && d < minD){
          const push = (minD - d) * 0.5; // split correction
          const nx = dx/d, ny = dy/d;
          fi.position.x -= nx * push; fi.position.y -= ny * push;
          fj.position.x += nx * push; fj.position.y += ny * push;
          // Dampen opposing velocities slightly
          fi.velocity.x -= nx * 0.05; fi.velocity.y -= ny * 0.05;
          fj.velocity.x += nx * 0.05; fj.velocity.y += ny * 0.05;
        }
      }
    }

    fishes.forEach(f=>{
      f.speed = 0.4 + state.speedBoost;
      // Attraction to feed target
      if (state.feed.active && state.feed.t>0){
        const dx = state.feed.x - f.position.x; const dy = state.feed.y - f.position.y; const d = Math.hypot(dx,dy)+1e-5;
        f.boost({x:dx/d, y:dy/d}, 0.3*state.feed.t);
      }
      // Strict enforcement with edge sliding: if fish is inside the overlay rect, move to nearest edge and slide along it
      if (avoidRect){
        const x=f.position.x, y=f.position.y;
        if (x>avoidRect.minX && x<avoidRect.maxX && y>avoidRect.minY && y<avoidRect.maxY){
          const dL = Math.abs(x - avoidRect.minX);
          const dR = Math.abs(avoidRect.maxX - x);
          const dB = Math.abs(y - avoidRect.minY);
          const dT = Math.abs(avoidRect.maxY - y);
          const m = Math.min(dL,dR,dB,dT);
          const nudge = 0.02;
          let nx=0, ny=0, tx=0, ty=0; // normal and tangent
          if (m===dB){ // bottom edge
            ny = -1; tx = 1; ty = 0; f.position.y = avoidRect.minY - nudge;
          } else if (m===dT){ // top edge
            ny = 1; tx = 1; ty = 0; f.position.y = avoidRect.maxY + nudge;
          } else if (m===dL){ // left edge
            nx = -1; tx = 0; ty = 1; f.position.x = avoidRect.minX - nudge;
          } else { // right edge
            nx = 1; tx = 0; ty = 1; f.position.x = avoidRect.maxX + nudge;
          }
          if (nx===0 && ny===0) { nx=0; ny=1; tx=1; ty=0; }
          // Decompose velocity, keep tangent, push slightly outward
          const vx=f.velocity.x, vy=f.velocity.y;
          const vt = vx*tx + vy*ty; // tangent component
          const dirT = vt>=0 ? 1 : -1;
          const targetSpeed = Math.max(0.25, f.speed*0.9);
          f.velocity.x = tx * dirT * targetSpeed + nx * 0.15;
          f.velocity.y = ty * dirT * targetSpeed + ny * 0.15;
          // Brief cooldown to avoid jitter on the same edge
          f.avoidCd = 0.35;
        }
      }
      f.update(dt, t);
      // Post-update safeguard in case integration moved fish back inside this frame
    if (avoidRect && (f.avoidCd||0)<=0){
        const x=f.position.x, y=f.position.y;
        if (x>avoidRect.minX && x<avoidRect.maxX && y>avoidRect.minY && y<avoidRect.maxY){
      const dL = Math.abs(x - avoidRect.minX);
      const dR = Math.abs(avoidRect.maxX - x);
      const dB = Math.abs(y - avoidRect.minY);
      const dT = Math.abs(avoidRect.maxY - y);
      const m = Math.min(dL,dR,dB,dT);
      const nudge = 0.02;
      let nx=0, ny=0, tx=0, ty=0;
      if (m===dB){ ny=-1; tx=1; ty=0; f.position.y = avoidRect.minY - nudge; }
      else if (m===dT){ ny=1; tx=1; ty=0; f.position.y = avoidRect.maxY + nudge; }
      else if (m===dL){ nx=-1; tx=0; ty=1; f.position.x = avoidRect.minX - nudge; }
      else { nx=1; tx=0; ty=1; f.position.x = avoidRect.maxX + nudge; }
      if (nx===0 && ny===0) { nx=0; ny=1; tx=1; ty=0; }
      const vx=f.velocity.x, vy=f.velocity.y; const vt = vx*tx + vy*ty; const dirT = vt>=0 ? 1 : -1; const targetSpeed = Math.max(0.25, f.speed*0.9);
      f.velocity.x = tx * dirT * targetSpeed + nx * 0.15;
      f.velocity.y = ty * dirT * targetSpeed + ny * 0.15;
      f.avoidCd = 0.35;
        }
      }
    });
    state.speedBoost = Math.max(0, state.speedBoost - dt*0.4);

    // Bubbles
    if (Math.random()<0.08){ const b=bubbles.spawn(); activeBubbles.push({m:b,vy:THREE.MathUtils.lerp(0.15,0.35,Math.random())}); }
    updateBubbles(activeBubbles, bubbles, dt, now);

  // Day-night modulation
  const day = (Math.sin(t*0.05)+1)/2; // 0..1
  amb.intensity = 0.6 + 0.4*day; dir.intensity = 0.4 + 0.4*day;
  grad.material.color.setHex(day>0.5 ? 0x052e49 : 0x031f31);

    // Update feed decay and particles
    if (state.feed.t>0){ state.feed.t = Math.max(0, state.feed.t - dt*0.5); if (state.feed.t===0) state.feed.active=false; }
    if (feedParticles){
      const pos = feedParticles.geometry.getAttribute('position'); const vel = feedParticles.userData.vel; let any=false;
      for(let i=0;i<pos.count;i++){ let x=pos.array[i*3], y=pos.array[i*3+1]; x += vel[i*3]*dt; y += vel[i*3+1]*dt; vel[i*3+1] -= 0.2*dt; pos.array[i*3]=x; pos.array[i*3+1]=y; any = any || y>-0.9; }
      pos.needsUpdate = true; feedParticles.material.opacity = Math.max(0, feedParticles.material.opacity - dt*0.5);
      if (feedParticles.material.opacity<=0) { scene.remove(feedParticles); feedParticles=null; }
    }

    renderer.render(scene, camera);
    requestAnimationFrame(tick);
  }
  requestAnimationFrame(tick);

  return {
    stop: ()=>{ window.removeEventListener('resize', onResize); stopPlankton(); try { container.removeChild(renderer.domElement); } catch(e){} },
  };
}
