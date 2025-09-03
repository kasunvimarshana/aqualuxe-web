import * as THREE from 'three';

export function addCoral(scene){
  const group = new THREE.Group();
  const mat1 = new THREE.MeshStandardMaterial({color:0x3a7ca5, roughness:1});
  const mat2 = new THREE.MeshStandardMaterial({color:0x2a6f97, roughness:1});
  const mat3 = new THREE.MeshStandardMaterial({color:0x22577a, roughness:1});

  for (let i=0;i<20;i++){
    const h = Math.random()*0.3+0.1; const r = Math.random()*0.06+0.03;
    const geo = new THREE.CylinderGeometry(r*0.7, r, h, 6);
    const mat = [mat1, mat2, mat3][i%3];
    const m = new THREE.Mesh(geo, mat);
    m.position.set(THREE.MathUtils.lerp(-1.4,1.4,Math.random()), -0.9 + h/2, -0.3 - Math.random()*0.2);
    m.rotation.z = (Math.random()-0.5)*0.2; m.castShadow=false; m.receiveShadow=false;
    group.add(m);
  }

  // Seaweed planes with sway
  const seaweed=[];
  for(let i=0;i<12;i++){
    const g = new THREE.PlaneGeometry(0.06, 0.5, 1, 8);
    const m = new THREE.MeshBasicMaterial({color:0x1f9366, transparent:true, opacity:0.8, side:THREE.DoubleSide});
    const p = new THREE.Mesh(g,m); p.position.set(THREE.MathUtils.lerp(-1.3,1.3,Math.random()), -0.7, -0.15);
    p.userData.phase = Math.random()*Math.PI*2; seaweed.push(p); group.add(p);
  }
  group.userData.seaweed = seaweed;
  scene.add(group);
  return group;
}

export function updateCoral(group, t){
  const seaweed = group.userData.seaweed || [];
  seaweed.forEach((p, i)=>{ p.rotation.z = Math.sin(t*0.8 + p.userData.phase) * 0.12; });
}
