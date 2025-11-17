/*
  AquaLuxe GLB Viewer
  - Loads a local GLB and renders it with OrbitControls
  - UI: play/pause, speed, lighting preset, auto-rotate, reset, fit
  - Interactions: click spin+bounce, double-click focus/zoom
  - Perf: pixelRatio cap, pause when hidden, reduced motion friendly, WebGL fallback
*/
import * as THREE from 'three';

export async function mountGLBViewer(container){
  const hasWebGL = (()=>{ try{ const c=document.createElement('canvas'); return !!(window.WebGLRenderingContext && (c.getContext('webgl')||c.getContext('experimental-webgl'))); }catch(e){ return false; } })();
  if(!hasWebGL){ mountFallback(container, 'Your browser does not support WebGL.'); return null; }

  // eslint-disable-next-line no-undef
  const DIST_BASE = (typeof __webpack_public_path__ !== 'undefined' && __webpack_public_path__)
    ? __webpack_public_path__ : '/wp-content/themes/aqualuxe/assets/dist/';

  const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const glbFile = (container.getAttribute('data-glb')||'').trim();
  if(!glbFile){ mountFallback(container, 'No model specified.'); return null; }

  // Lazy import heavy extras
  let GLTFLoader, OrbitControls; try{
    const modL = await import('three/examples/jsm/loaders/GLTFLoader.js');
    const modC = await import('three/examples/jsm/controls/OrbitControls.js');
    GLTFLoader = modL.GLTFLoader; OrbitControls = modC.OrbitControls;
  }catch(e){ mountFallback(container, '3D modules failed to load.'); return null; }

  // Scene setup
  const scene = new THREE.Scene();
  scene.background = null;
  const camera = new THREE.PerspectiveCamera(60, container.clientWidth/container.clientHeight, 0.05, 200);
  camera.position.set(0.6, 0.4, 1.2);
  const renderer = new THREE.WebGLRenderer({ antialias:true, alpha:true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio||1, 1.75));
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.0;
  renderer.setSize(container.clientWidth, container.clientHeight);
  renderer.domElement.style.width = '100%'; renderer.domElement.style.height='100%';
  renderer.domElement.style.display='block';
  container.classList.add('relative');
  container.appendChild(renderer.domElement);

  // Lights (preset driven)
  const lights = { amb: new THREE.AmbientLight(0xffffff, 0.9), dir: new THREE.DirectionalLight(0xffffff, 1.0), hemi: new THREE.HemisphereLight(0xffffff, 0x223344, 0.5) };
  lights.dir.position.set(1,1,1);
  scene.add(lights.amb); scene.add(lights.hemi); scene.add(lights.dir);

  // Controls
  const controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true; controls.dampingFactor = 0.07;
  controls.rotateSpeed = 0.7; controls.zoomSpeed = 0.6; controls.panSpeed = 0.5;
  controls.autoRotate = !prefersReduced; controls.autoRotateSpeed = 0.5;

  // Model
  const loader = new GLTFLoader();
  let mixer=null, model=null; const group = new THREE.Group(); scene.add(group);
  try{
    const gltf = await loader.loadAsync(`${DIST_BASE}models/${glbFile}`);
    model = gltf.scene; normalizeModel(model);
    group.add(model);
    if (gltf.animations && gltf.animations.length){ mixer = new THREE.AnimationMixer(model); gltf.animations.forEach(clip=>{ const a = mixer.clipAction(clip); a.play(); }); }
  }catch(e){ mountFallback(container, 'Model failed to load.'); return null; }

  // Fit view to model
  fitToObject(camera, controls, group);

  // UI overlay
  const ui = buildUI(); container.appendChild(ui.root);
  let playing = !prefersReduced; setPlaying(playing);
  ui.playBtn.addEventListener('click', ()=> setPlaying(!playing));
  ui.speed.addEventListener('input', ()=> setSpeed(parseFloat(ui.speed.value||'1')));
  ui.lightSel.addEventListener('change', ()=> applyLightPreset(ui.lightSel.value));
  ui.resetBtn.addEventListener('click', ()=> fitToObject(camera, controls, group, true));
  ui.autoBtn.addEventListener('click', ()=> { controls.autoRotate = !controls.autoRotate; ui.autoBtn.setAttribute('aria-pressed', String(controls.autoRotate)); });

  // Interactions
  let seqT=0, seqActive=false; // spin+bounce sequence timer
  container.addEventListener('click', ()=>{ if (prefersReduced) return; triggerSpinBounce(); });
  container.addEventListener('dblclick', (e)=>{ if (prefersReduced) return; const pt = ndcFromEvent(e, container); const wp = worldPointAtZ(pt.x, pt.y, 0, camera); smoothFocus(controls, wp, 0.6, 0.65); });

  function triggerSpinBounce(){ seqT = 0; seqActive = true; }
  function setPlaying(p){ playing = p; ui.playBtn.textContent = p ? 'Pause' : 'Play'; }
  function setSpeed(s){ const clamped = Math.max(0.25, Math.min(2, s)); ui.speedValue.textContent = `${clamped.toFixed(2)}x`; if (mixer) mixer.timeScale = clamped; controls.autoRotateSpeed = 0.6 * clamped; }
  function applyLightPreset(name){
    switch(name){
      case 'studio': lights.amb.intensity=1.0; lights.hemi.intensity=0.4; lights.dir.intensity=0.9; lights.dir.color.set(0xffffff); renderer.toneMappingExposure=1.0; break;
      case 'underwater': lights.amb.intensity=0.6; lights.hemi.intensity=0.7; lights.dir.intensity=0.6; lights.dir.color.set(0x8fd3ff); renderer.toneMappingExposure=1.1; break;
      case 'sunset': lights.amb.intensity=0.7; lights.hemi.intensity=0.3; lights.dir.intensity=1.1; lights.dir.color.set(0xffb070); renderer.toneMappingExposure=1.0; break;
      case 'noir': lights.amb.intensity=0.2; lights.hemi.intensity=0.1; lights.dir.intensity=1.2; lights.dir.color.set(0xffffff); renderer.toneMappingExposure=0.9; break;
      default: break;
    }
  }
  applyLightPreset('studio');

  // Render loop
  let last=performance.now();
  function tick(now){
    if (document.hidden){ requestAnimationFrame(tick); return; }
    const dt = Math.min(0.05, (now-last)/1000); last=now;
    if (playing && mixer) mixer.update(dt);
    if (seqActive && model){ seqT += dt; const t = seqT; const spin = Math.min(1, t/1.2); model.rotation.y += 2.8*dt; model.position.y = 0.02*Math.sin(t*6.0)*(1.0-spin*0.2); if (t>1.2){ seqActive=false; model.position.y=0; } }
    controls.update();
    renderer.render(scene, camera);
    requestAnimationFrame(tick);
  }
  requestAnimationFrame(tick);

  function onResize(){ const w=container.clientWidth, h=container.clientHeight; camera.aspect=w/h; camera.updateProjectionMatrix(); renderer.setSize(w,h); }
  window.addEventListener('resize', onResize);

  return { stop(){ try{ window.removeEventListener('resize', onResize); container.replaceChildren(); }catch(e){} } };
}

// Helpers
function mountFallback(container, message){
  container.innerHTML = '';
  const wrap = document.createElement('div'); wrap.className = 'p-4 text-center text-sm text-gray-300 bg-gray-900/70 rounded';
  const poster = container.getAttribute('data-poster'); if (poster){ const img = document.createElement('img'); img.src = poster; img.alt = 'Model preview'; img.className = 'mx-auto max-h-64 object-contain mb-3'; wrap.appendChild(img); }
  const p = document.createElement('p'); p.textContent = message; wrap.appendChild(p);
  const glb = container.getAttribute('data-glb'); if (glb){ const a = document.createElement('a'); a.href = `#`; a.onclick = (e)=>e.preventDefault(); a.className='underline ml-2'; a.textContent='Download model'; }
  container.appendChild(wrap);
}

function normalizeModel(root){
  // Center and scale to a friendly size
  const pivot = new THREE.Group(); pivot.add(root);
  const box = new THREE.Box3().setFromObject(pivot); const c = new THREE.Vector3(); box.getCenter(c); root.position.sub(c);
  const sBox = new THREE.Box3().setFromObject(pivot); const sphere = sBox.getBoundingSphere(new THREE.Sphere()); const r = sphere.radius || 1;
  const target = 0.25; const k = target / r; pivot.scale.setScalar(k);
  pivot.traverse(o=>{ if (o.isMesh){ o.castShadow=false; o.receiveShadow=false; o.frustumCulled=true; if (o.material && o.material.isMeshStandardMaterial){ o.material.roughness = Math.min(0.9, o.material.roughness ?? 0.6); } }});
  return pivot;
}

function fitToObject(camera, controls, obj, animate=false){
  const box = new THREE.Box3().setFromObject(obj); const size = new THREE.Vector3(); box.getSize(size); const center = new THREE.Vector3(); box.getCenter(center);
  const maxDim = Math.max(size.x, size.y, size.z);
  const fov = camera.fov * (Math.PI/180);
  let camZ = Math.abs(maxDim / (2*Math.tan(fov/2))) * 1.6; camZ = Math.min(Math.max(camZ, 0.6), 20);
  const newPos = new THREE.Vector3(center.x + camZ*0.4, center.y + camZ*0.25, center.z + camZ);
  if (animate){ camera.position.lerp(newPos, 0.6); } else { camera.position.copy(newPos); }
  controls.target.copy(center); controls.update();
}

function ndcFromEvent(e, container){ const r = container.getBoundingClientRect(); return { x: ((e.clientX-r.left)/r.width)*2-1, y: -(((e.clientY-r.top)/r.height)*2-1) }; }
function worldPointAtZ(nx, ny, z, camera){ const origin = camera.position.clone(); const target = new THREE.Vector3(nx, ny, 0.5).unproject(camera); const dir = target.sub(origin).normalize(); const t = (z - origin.z)/dir.z; return origin.add(dir.multiplyScalar(t)); }

function smoothFocus(controls, wp, zoomFactor=0.7, lerp=0.6){
  const cam = controls.object; const dir = new THREE.Vector3().subVectors(wp, cam.position).multiplyScalar(zoomFactor);
  cam.position.addScaledVector(dir, lerp); controls.target.lerp(wp, lerp); controls.update();
}

function buildUI(){
  const root = document.createElement('div'); root.className = 'absolute inset-0 pointer-events-none';
  const panel = document.createElement('div'); panel.className = 'pointer-events-auto absolute right-3 top-3 bg-black/50 text-white rounded-md px-3 py-2 space-y-2 shadow'; root.appendChild(panel);

  const row1 = document.createElement('div'); row1.className = 'flex items-center gap-2'; panel.appendChild(row1);
  const playBtn = btn('Pause'); row1.appendChild(playBtn);
  const autoBtn = toggle('Auto-rotate', true); row1.appendChild(autoBtn);
  const resetBtn = btn('Fit'); row1.appendChild(resetBtn);

  const row2 = document.createElement('div'); row2.className = 'flex items-center gap-2'; panel.appendChild(row2);
  const speed = document.createElement('input'); speed.type='range'; speed.min='0.25'; speed.max='2'; speed.step='0.05'; speed.value='1'; speed.className='w-28'; row2.appendChild(label('Speed')); row2.appendChild(speed);
  const speedValue = document.createElement('span'); speedValue.textContent='1.00x'; speedValue.className='ml-1 text-xs opacity-90'; row2.appendChild(speedValue);

  const row3 = document.createElement('div'); row3.className = 'flex items-center gap-2'; panel.appendChild(row3);
  const lightSel = document.createElement('select'); lightSel.className='bg-black/40 border border-white/20 rounded px-1 py-0.5 text-sm'; ['studio','underwater','sunset','noir'].forEach(n=>{ const o=document.createElement('option'); o.value=n; o.textContent=n; lightSel.appendChild(o); }); row3.appendChild(label('Light')); row3.appendChild(lightSel);

  function btn(txt){ const b=document.createElement('button'); b.type='button'; b.textContent=txt; b.className='px-2 py-1 bg-white/10 hover:bg-white/20 rounded text-sm'; return b; }
  function toggle(txt, pressed){ const b=btn(txt); b.setAttribute('aria-pressed', String(pressed)); return b; }
  function label(txt){ const l=document.createElement('span'); l.textContent=txt; l.className='text-xs opacity-80'; return l; }

  return { root, playBtn, speed, speedValue, lightSel, resetBtn, autoBtn };
}

export function initGLBViewers(){
  const nodes = document.querySelectorAll('.alx-glb-viewer[data-glb]');
  nodes.forEach(n=>{ mountGLBViewer(n); });
}
