import * as THREE from 'three';
import { TAU, rand, lerp, clamp, pick } from './utils';

function lerpAngle(a, b, t){
  let d = b - a;
  while (d > Math.PI) d -= TAU;
  while (d < -Math.PI) d += TAU;
  return a + d * t;
}

const COLORS = [0xffa07a,0xffd166,0x43bccd,0x7dd3fc,0xbde0fe,0xffc0cb,0xff6f61,0x8dd3c7];

function makeBody(color){
  const group = new THREE.Group();
  const body = new THREE.CapsuleGeometry(0.07,0.18,4,6);
  const mat = new THREE.MeshStandardMaterial({color, roughness:0.6, metalness:0.05});
  const m = new THREE.Mesh(body, mat); m.rotation.z = Math.PI/2; group.add(m);
  const tail = new THREE.ConeGeometry(0.05,0.12,8);
  const mt = new THREE.MeshStandardMaterial({color, roughness:0.7});
  const t = new THREE.Mesh(tail, mt); t.position.x = -0.14; t.rotation.z = Math.PI/2; group.add(t);
  group.userData.tail = t;
  return group;
}

export class Fish {
  constructor(){
    const color = pick(COLORS);
    this.mesh = makeBody(color);
    this.position = this.mesh.position;
    this.velocity = new THREE.Vector3(rand(0.2,0.6), rand(-0.05,0.05), 0);
  this.acc = new THREE.Vector3(0,0,0);
    this.speed = this.velocity.length();
  this.maxSpeed = lerp(0.45, 0.7, Math.random());
  this.maxForce = 0.9;
    this.wanderAngle = Math.random()*TAU;
    this.amplitude = rand(0.05,0.18);
    this.target = new THREE.Vector3();
    this.size = lerp(0.8, 1.4, Math.random());
    this.mesh.scale.setScalar(this.size);
  // Approximate collision radius for soft separation (world units)
  this.radius = 0.06 * this.size;
  this._angle = Math.atan2(this.velocity.y, this.velocity.x);
  }
  setBounds(rect){ this.bounds = rect; }
  boost(dir, mag=0.6){ this.velocity.x += dir.x*mag; this.velocity.y += dir.y*mag; }
  update(dt, t){
    // Wander
  this.wanderAngle += rand(-0.25, 0.25) * dt;
  const desire = new THREE.Vector3(Math.cos(this.wanderAngle), Math.sin(this.wanderAngle)*0.35, 0);
  const targetSpeed = Math.min(this.speed, this.maxSpeed);
  desire.multiplyScalar(targetSpeed);
  // Smoothly steer towards desire
  const steer = desire.clone().sub(this.velocity);
  const maxDelta = this.maxForce * dt;
  if (steer.length() > maxDelta) steer.setLength(maxDelta);
  this.velocity.add(steer);

    // Avoid bounds
    if (this.bounds){
      const { minX, maxX, minY, maxY } = this.bounds;
      if (this.position.x > maxX-0.1) this.velocity.x -= 0.4*dt;
      if (this.position.x < minX+0.1) this.velocity.x += 0.4*dt;
  if (this.position.y > maxY-0.1) this.velocity.y -= 0.5*dt; // stronger push down
      if (this.position.y < minY+0.1) this.velocity.y += 0.3*dt;
  // Soft bias to lower half
  if (this.position.y > (maxY+minY)*0.5) this.velocity.y -= 0.05*dt;
    }

  // Friction and speed clamp
  this.velocity.multiplyScalar(1 - Math.min(0.1*dt, 0.08));
  const vlen = this.velocity.length();
  const cap = Math.max(0.2, this.maxSpeed);
  if (vlen > cap) this.velocity.setLength(cap);
  if (vlen < 0.03) this.velocity.add(desire.clone().multiplyScalar(0.2));

    // Integrate
    this.position.x += this.velocity.x * dt;
    this.position.y += this.velocity.y * dt;

    // Swim sway
    const tail = this.mesh.userData.tail; if (tail) tail.rotation.x = Math.sin(t*6 + this.position.x*2) * 0.4;
  const targetAngle = Math.atan2(this.velocity.y, this.velocity.x);
  this._angle = lerpAngle(this._angle, targetAngle, Math.min(1, dt*6));
  this.mesh.rotation.z = this._angle;
  }
}

export function spawnFishes(count){
  const fishes = [];
  for(let i=0;i<count;i++){
    const f = new Fish();
    // Slight spawn bias to the lower half to minimize initial overlap with hero overlay
    f.position.set(rand(-1.2,1.2), rand(-0.6,0.2), rand(-0.2,0.2));
    fishes.push(f);
  }
  return fishes;
}
