import * as THREE from 'three';
// GLTF loader will be imported on-demand inside loadSpeciesMeshes

export const SPECIES = [
  // Reef species (preferred)
  { key:'clownfish', name:'Clownfish', role:'anemone', model:'zebra-clownfish-1528.glb', color:0xff7f50, count:3, biome:'reef', isReal:true, scale:0.7 },
  { key:'cowfish', name:'Cowfish', role:'grazer', model:'cowfish-883.glb', color:0xc5e06b, count:1, biome:'reef', isReal:true, scale:0.7 },
  // Non-reef or generic; will be skipped in strict/real-only unless explicitly allowed
  { key:'tetra', name:'Tetra', role:'school', model:'tetra-fish-1415.glb', color:0x74d0ff, count:15, biome:'freshwater', isReal:true },
  { key:'anglerfish', name:'Anglerfish', role:'midwater', model:'anglerfish-638.glb', color:0xa6b3c1, count:1, biome:'deepsea', isReal:true },
  { key:'goldfish', name:'Blue Goldfish', role:'midwater', model:'blue-goldfish-722.glb', color:0x88c0ff, count:4, biome:'freshwater', isReal:true },
  { key:'generic_fish', name:'Generic Fish', role:'midwater', model:'fish-1001.glb', color:0x88c0ff, count:6, biome:'unknown', isReal:false },
];

function normalizeFishMesh(root, targetRadius=0.01){
  const pivot = new THREE.Group();
  pivot.add(root);
  // Face +X first so we can base length on X
  pivot.rotation.y = Math.PI * 0.5;
  pivot.updateMatrixWorld(true);

  // Compute current bounds in world, then convert center to pivot local
  const box1 = new THREE.Box3().setFromObject(pivot);
  const size1 = new THREE.Vector3(); box1.getSize(size1);
  const centerW = new THREE.Vector3(); box1.getCenter(centerW);
  const centerLocal = pivot.worldToLocal(centerW.clone());
  // Recenter by offsetting the child root
  root.position.sub(centerLocal);

  // Scale by bounding sphere radius to a small, consistent size
  const box2 = new THREE.Box3().setFromObject(pivot);
  const sphere = box2.getBoundingSphere(new THREE.Sphere());
  const r = sphere.radius || 1;
  const s = targetRadius / r;
  pivot.scale.setScalar(s);

  // Cleanup flags and expose radius for avoidance
  pivot.traverse((o)=>{
    if (o.isMesh){ o.castShadow = false; o.receiveShadow = false; o.frustumCulled = false; }
  });
  pivot.userData.radius = targetRadius;
  return pivot;
}

function makePlaceholder(color=0x88c0ff){
  const g = new THREE.Group();
  const body = new THREE.CapsuleGeometry(0.03,0.09,6,10);
  const m = new THREE.MeshStandardMaterial({color, roughness:0.6, metalness:0.05});
  const b = new THREE.Mesh(body, m); b.rotation.z = Math.PI/2; g.add(b);
  const tail = new THREE.ConeGeometry(0.028,0.07,12);
  const mt = new THREE.MeshStandardMaterial({color, roughness:0.7});
  const t = new THREE.Mesh(tail, mt); t.position.x = -0.14; t.rotation.z = Math.PI/2; g.add(t);
  g.userData.tail = t;
  return g;
}

export async function loadSpeciesMeshes(distBase, opts={ allowPlaceholders:false, strictReef:true, realOnly:true, whitelist:null }){
  const entries = [];
  let GLTFLoader = null;
  try {
    const mod = await import('three/examples/jsm/loaders/GLTFLoader.js');
    GLTFLoader = mod.GLTFLoader || null;
  } catch(e){ GLTFLoader = null; }
  for (const s of SPECIES){
  if (opts.strictReef && s.biome && s.biome!=='reef') continue; // only reef species
  if (opts.realOnly && !s.isReal) continue; // skip generic
  if (opts.whitelist && !opts.whitelist.has(s.model)) continue; // only allowed models
    let mesh = null;
    if (GLTFLoader){
      try {
        const loader = new GLTFLoader();
        const glb = await loader.loadAsync(`${distBase}models/${s.model}`);
        mesh = glb.scene ? normalizeFishMesh(glb.scene) : null;
        if (mesh && typeof s.scale === 'number' && isFinite(s.scale)){
          const mul = Math.max(0.1, Math.min(3, s.scale));
          mesh.scale.multiplyScalar(mul);
          if (mesh.userData && typeof mesh.userData.radius==='number'){ mesh.userData.radius *= mul; }
        }
      } catch(e){ mesh = null; }
    }
    if (!mesh){
      if (opts.allowPlaceholders){
        mesh = makePlaceholder(s.color);
      } else {
        // Skip species without a local model to keep "real species only" guarantee
        // eslint-disable-next-line no-console
        console.warn(`[reef] Missing model for ${s.key}: ${s.model} — skipping`);
        continue;
      }
    }
    entries.push({ ...s, mesh });
  }
  return entries;
}

export function swimBehaviorFor(role){
  switch(role){
    case 'anemone': return { depth:[-0.1,0.15], roam:0.25, homeBias:0.7 };
    case 'midwater': return { depth:[-0.2,0.25], roam:0.5, homeBias:0.2 };
    case 'pelagic': return { depth:[-0.3,0.0], roam:0.8, speed:0.9 };
    case 'grazer': return { depth:[-0.4,-0.05], roam:0.3, floorBias:0.6 };
    case 'school': return { depth:[-0.2,0.3], roam:0.45, school:true };
    case 'seaweed': return { depth:[-0.35,0.1], roam:0.35, kelpBias:0.5 };
    default: return { depth:[-0.3,0.3], roam:0.4 };
  }
}
