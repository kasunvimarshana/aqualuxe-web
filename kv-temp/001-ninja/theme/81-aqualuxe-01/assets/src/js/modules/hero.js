import * as THREE from 'three';
import gsap from 'gsap';

(function(){
  const canvas = document.getElementById('ax-hero-canvas');
  if (!canvas) return;

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(60, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);
  const renderer = new THREE.WebGLRenderer({ canvas, antialias:true, alpha:true });
  function resize(){
    const w = canvas.clientWidth || window.innerWidth;
    const h = canvas.clientHeight || Math.max(400, window.innerHeight*0.7);
    renderer.setSize(w, h, false);
    camera.aspect = w/h; camera.updateProjectionMatrix();
  }
  window.addEventListener('resize', resize); resize();

  const geometry = new THREE.IcosahedronGeometry(2, 2);
  const material = new THREE.MeshStandardMaterial({ color: 0x22aaff, metalness: 0.6, roughness: 0.3, wireframe: false });
  const mesh = new THREE.Mesh(geometry, material);
  scene.add(mesh);

  const light = new THREE.PointLight(0xffffff, 1.2, 100); light.position.set(5,5,5); scene.add(light);
  const light2 = new THREE.PointLight(0x66e1ff, 0.6, 100); light2.position.set(-5,-2,3); scene.add(light2);

  gsap.to(mesh.rotation, { y: Math.PI*2, duration: 20, repeat: -1, ease: 'linear' });

  function animate(){ requestAnimationFrame(animate); renderer.render(scene, camera); }
  animate();
})();
