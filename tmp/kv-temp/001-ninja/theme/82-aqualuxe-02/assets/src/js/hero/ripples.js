import * as THREE from 'three';

export function addRipples(scene){
  const geo = new THREE.PlaneGeometry(6, 4, 1, 1);
  const mat = new THREE.ShaderMaterial({
    transparent:true,
  uniforms:{ uTime:{value:0}, uColor:{value:new THREE.Color(0x1fa3d8)}, uAlpha:{value:0.08}, uEnergy:{value:0.0} },
  vertexShader:`varying vec2 vUv; void main(){ vUv=uv; gl_Position=projectionMatrix*modelViewMatrix*vec4(position,1.0); }`,
  fragmentShader:`varying vec2 vUv; uniform float uTime; uniform vec3 uColor; uniform float uAlpha; uniform float uEnergy; void main(){ float w = sin((vUv.y+uTime*0.05)*20.0)*0.03 + cos((vUv.x+uTime*0.04)*16.0)*0.03; float energy = uEnergy * (0.2 + 0.8 * smoothstep(0.6, 1.0, vUv.y)); float m = smoothstep(0.0,1.0,vUv.y); gl_FragColor = vec4(uColor*(0.4+w+energy)*m, uAlpha + 0.15*energy); }`
  });
  const mesh = new THREE.Mesh(geo, mat); mesh.position.z=-0.25; scene.add(mesh);
  return mesh;
}

export function updateRipples(mesh, t){ if (mesh && mesh.material && mesh.material.uniforms) mesh.material.uniforms.uTime.value = t; }
