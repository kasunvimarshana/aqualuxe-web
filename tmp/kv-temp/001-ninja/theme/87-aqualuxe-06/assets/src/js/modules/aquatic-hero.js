import * as THREE from 'three';
import { gsap } from 'gsap';

export function initAquaticHero() {
  const canvas = document.getElementById('aquatic-canvas');
  if (!canvas) return;
  try {
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(45, 2, 0.1, 100);
    camera.position.set(0, 0, 6);
    const resize = () => {
      const { clientWidth: w, clientHeight: h } = canvas;
      if (w === 0 || h === 0) return;
      renderer.setSize(w, h, false);
      camera.aspect = w / h; camera.updateProjectionMatrix();
    };
    resize();
    window.addEventListener('resize', resize);

    const geometry = new THREE.SphereGeometry(1.8, 64, 64);
    const material = new THREE.ShaderMaterial({
      uniforms: {
        u_time: { value: 0 },
        u_color1: { value: new THREE.Color('#0ea5e9') },
        u_color2: { value: new THREE.Color('#0f172a') },
      },
      vertexShader: `
        uniform float u_time;
        varying vec2 vUv;
        void main(){
          vUv = uv;
          vec3 p = position;
          p.z += 0.12 * sin(p.x * 3.0 + u_time) * cos(p.y * 3.0 + u_time * 0.8);
          gl_Position = projectionMatrix * modelViewMatrix * vec4(p, 1.0);
        }
      `,
      fragmentShader: `
        precision highp float;
        uniform float u_time;
        uniform vec3 u_color1; uniform vec3 u_color2;
        varying vec2 vUv;
        void main(){
          float t = 0.5 + 0.5 * sin(u_time*0.4 + vUv.y*6.2831);
          vec3 c = mix(u_color2, u_color1, t);
          gl_FragColor = vec4(c, 0.28);
        }
      `,
      transparent: true,
    });
    const mesh = new THREE.Mesh(geometry, material);
    scene.add(mesh);

    const clock = new THREE.Clock();
    const animate = () => {
      const t = clock.getElapsedTime();
      material.uniforms.u_time.value = t;
      mesh.rotation.y += 0.0015;
      renderer.render(scene, camera);
      raf = requestAnimationFrame(animate);
    };
    let raf = requestAnimationFrame(animate);
    gsap.fromTo(mesh.rotation, { x: -0.2 }, { x: 0.2, duration: 6, ease: 'sine.inOut', yoyo: true, repeat: -1 });
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      gsap.globalTimeline.clear();
    }
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) cancelAnimationFrame(raf);
      else raf = requestAnimationFrame(animate);
    });
  } catch (e) {}
}
