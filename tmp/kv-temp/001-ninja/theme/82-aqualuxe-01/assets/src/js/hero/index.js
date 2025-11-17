import * as THREE from 'three';
import { gsap } from 'gsap';
import { AmbientAudio } from '../modules/audio';
import { startPlankton } from './plankton';

function prefersReducedMotion(){
  return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

class BubblePool {
  constructor(capacity, scene){ this.pool=[]; this.scene=scene; for(let i=0;i<capacity;i++){ this.pool.push(this.create()); } }
  create(){ const geo=new THREE.SphereGeometry(0.02,8,8); const mat=new THREE.MeshBasicMaterial({color:0x77d1ff, transparent:true, opacity:0.6}); return new THREE.Mesh(geo,mat); }
  spawn(){ const m=this.pool.pop()||this.create(); m.position.set((Math.random()-0.5)*2,-0.8,(Math.random()-0.5)); m.scale.setScalar(THREE.MathUtils.lerp(0.3,1.2,Math.random())); this.scene.add(m); return m; }
  recycle(m){ this.scene.remove(m); this.pool.push(m); }
}

(function initHero(){
  const container = document.getElementById('alx-hero-canvas');
  if(!container) return;

  // Resolve dist base from webpack public path or fallback
  // eslint-disable-next-line no-undef
  const DIST_BASE = (typeof __webpack_public_path__ !== 'undefined' && __webpack_public_path__)
    ? __webpack_public_path__
    : '/wp-content/themes/aqualuxe/assets/dist/';

  // WebGL support check
  const glOk = (()=>{
    try { const c=document.createElement('canvas'); return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl'))); } catch(e){ return false; }
  })();
  if(!glOk){
    container.style.background = 'linear-gradient(180deg,#021d2e 0%, #05334f 100%)';
  container.style.backgroundImage = `url(${DIST_BASE}images/hero-fallback.svg)`;
    container.style.backgroundSize = 'cover';
    container.style.backgroundPosition = 'center';
    return;
  }

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(55, container.clientWidth/container.clientHeight, 0.1, 100);
  camera.position.set(0,0.2,2.2);

  const renderer = new THREE.WebGLRenderer({ antialias:true, alpha:true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(container.clientWidth, container.clientHeight);
  container.appendChild(renderer.domElement);

  // Ambient audio (lazy user opt-in) and plankton overlay
  const globalToggle = (typeof window !== 'undefined' && window.AquaLuxeConfig && Number(window.AquaLuxeConfig.heroAudioEnabled) === 1);
  const dataToggle = container.getAttribute('data-ambient') !== 'off';
  const enableAmbient = globalToggle && dataToggle && !prefersReducedMotion();
  let audio, btn;
  if (enableAmbient) {
    const src = (typeof window !== 'undefined' && window.AquaLuxeConfig && window.AquaLuxeConfig.heroAudioSrc)
      ? window.AquaLuxeConfig.heroAudioSrc
      : '';
    if (src) {
      audio = new AmbientAudio(src);
    if (audio && audio.audio) {
      audio.audio.addEventListener('error', () => {
        try { audio.disable(); } catch(e){}
        if (btn) btn.style.display = 'none';
      });
    }
    btn = document.createElement('button');
    btn.setAttribute('aria-label','Toggle ambient sound');
    btn.className = 'btn btn-ghost absolute top-4 right-4 z-10';
    btn.textContent = 'Sound Off';
    btn.addEventListener('click', ()=>{ audio.toggle(); btn.textContent = audio.enabled? 'Sound On':'Sound Off'; });
    container.style.position = 'relative';
    container.appendChild(btn);
    }
  }
  const stopPlankton = startPlankton(container);

  // Gradient background via large plane
  const gradGeo = new THREE.PlaneGeometry(6,4,1,1);
  const gradMat = new THREE.MeshBasicMaterial({ color: 0x052e49 });
  const grad = new THREE.Mesh(gradGeo, gradMat); grad.position.z = -1; scene.add(grad);

  // Caustic light rays as moving transparent planes
  const rays=[]; for(let i=0;i<3;i++){ const g=new THREE.PlaneGeometry(4,2); const m=new THREE.MeshBasicMaterial({color:0x1fa3d8, transparent:true, opacity:0.1}); const p=new THREE.Mesh(g,m); p.position.set(0,0,-0.2-i*0.05); p.rotation.z = Math.random(); scene.add(p); rays.push(p); }

  // Simple fish: triangles with subtle sway
  const fishGroup = new THREE.Group(); scene.add(fishGroup);
  function makeFish(color=0xff8844){
    const g=new THREE.ConeGeometry(0.08,0.22,8); const m=new THREE.MeshStandardMaterial({color, roughness:0.6, metalness:0.1}); const mesh=new THREE.Mesh(g,m); mesh.rotation.z=Math.PI/2; mesh.castShadow=false; mesh.receiveShadow=false; mesh.userData.vx=THREE.MathUtils.lerp(0.2,0.6,Math.random()); mesh.userData.amp=Math.random()*0.2+0.05; mesh.position.set(THREE.MathUtils.lerp(-1.2,1.2,Math.random()), THREE.MathUtils.lerp(-0.4,0.5,Math.random()), 0);
    return mesh;
  }
  const colors=[0xffa07a,0xffd166,0x43bccd,0x7dd3fc,0xbde0fe];
  for(let i=0;i<12;i++){ fishGroup.add(makeFish(colors[i%colors.length])); }

  const ambient = new THREE.AmbientLight(0x99c9ff, 0.8); scene.add(ambient);
  const dir = new THREE.DirectionalLight(0xffffff, 0.6); dir.position.set(1,1,1); scene.add(dir);

  // Bubbles
  const bubbles = new BubblePool(40, scene);
  const activeBubbles=[];

  // Interactions
  const state={ speedBoost:0 };
  container.addEventListener('mousemove', (e)=>{ const x=(e.offsetX/container.clientWidth)*2-1; gsap.to(camera.position, { x:x*0.2, duration:0.4, overwrite:true }); state.speedBoost=0.4; });
  container.addEventListener('click', ()=>{ for(let i=0;i<5;i++){ const b=bubbles.spawn(); activeBubbles.push({m:b,vy:THREE.MathUtils.lerp(0.2,0.5,Math.random())}); } });
  container.addEventListener('touchstart', ()=>{ for(let i=0;i<8;i++){ const b=bubbles.spawn(); activeBubbles.push({m:b,vy:THREE.MathUtils.lerp(0.3,0.6,Math.random())}); } }, {passive:true});

  function onResize(){
    const w=container.clientWidth, h=container.clientHeight; camera.aspect=w/h; camera.updateProjectionMatrix(); renderer.setSize(w,h);
  }
  window.addEventListener('resize', onResize);

  let last=performance.now();
  const maxFPS = 60; const frameMin = 1000/maxFPS;

  function tick(now){
    const dt = Math.min(0.05, (now-last)/1000); if(now-last < frameMin) return requestAnimationFrame(tick); last=now;

    // Rays subtle motion
    rays.forEach((p,i)=>{ p.position.x=Math.sin(now*0.0002+i)*0.2; p.material.opacity=0.08+0.04*Math.sin(now*0.0003+i); });

    // Fish movement
    fishGroup.children.forEach((f,idx)=>{
      const base = f.userData.vx + state.speedBoost;
      f.position.x += base * dt;
      f.position.y += Math.sin(now*0.002 + idx)* f.userData.amp * dt * 5;
      if (f.position.x > 1.4){ f.position.x = -1.4; f.position.y = THREE.MathUtils.lerp(-0.4,0.5,Math.random()); }
    });
    state.speedBoost = Math.max(0, state.speedBoost - dt*0.5);

    // Spawn bubbles occasionally
    if (Math.random() < 0.06) { const b=bubbles.spawn(); activeBubbles.push({m:b,vy:THREE.MathUtils.lerp(0.15,0.35,Math.random())}); }
    for(let i=activeBubbles.length-1;i>=0;i--){ const b=activeBubbles[i]; b.m.position.y += b.vy*dt; b.m.position.x += Math.sin(now*0.003 + i)*0.02*dt*60; if(b.m.position.y>0.9){ bubbles.recycle(b.m); activeBubbles.splice(i,1); } }

    renderer.render(scene,camera);
  requestAnimationFrame(tick);
  }

  if(!prefersReducedMotion()) requestAnimationFrame(tick);
})();
