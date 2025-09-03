import * as THREE from 'three';
import { rand } from './utils';

export class BubblePool {
  constructor(capacity, scene){ this.pool=[]; this.scene=scene; for(let i=0;i<capacity;i++){ this.pool.push(this.create()); } }
  create(){ const geo=new THREE.SphereGeometry(0.02,8,8); const mat=new THREE.MeshPhongMaterial({color:0x9eddff, transparent:true, opacity:0.55, shininess:80}); return new THREE.Mesh(geo,mat); }
  spawn(){ const m=this.pool.pop()||this.create(); m.position.set(rand(-1.1,1.1),-0.9,rand(-0.2,0.2)); m.scale.setScalar(rand(0.3,1.1)); this.scene.add(m); return m; }
  recycle(m){ this.scene.remove(m); this.pool.push(m); }
}

export function updateBubbles(active, pool, dt, now){
  for(let i=active.length-1;i>=0;i--){
    const b=active[i];
    b.m.position.y += b.vy*dt;
    b.m.position.x += Math.sin(now*0.003 + i) * 0.02 * dt * 60;
    // Simple merge when close
    for(let j=i-1;j>=0;j--){
      const a=active[j];
      if (Math.abs(a.m.position.x - b.m.position.x) < 0.03 && Math.abs(a.m.position.y - b.m.position.y) < 0.03){
        a.m.scale.multiplyScalar(1.05); pool.recycle(b.m); active.splice(i,1); break;
      }
    }
    if(b.m.position.y>0.95){ pool.recycle(b.m); active.splice(i,1); }
  }
}
