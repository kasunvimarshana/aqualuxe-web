import '../css/app.css';
import * as THREE from 'three';
import { gsap } from 'gsap';

// Dark mode toggle with persistent preference
const toggle = document.getElementById('darkModeToggle');
if (toggle) {
  const setPressed = (on) => toggle.setAttribute('aria-pressed', on ? 'true' : 'false');
  const apply = (mode) => {
    const root = document.documentElement;
    if (mode === 'dark') {
      root.classList.add('dark');
      setPressed(true);
    } else {
      root.classList.remove('dark');
      setPressed(false);
    }
  };
  try {
    const pref = localStorage.getItem('aqualuxe:theme');
    apply(pref);
  } catch (e) {}
  toggle.addEventListener('click', () => {
    const isDark = document.documentElement.classList.toggle('dark');
    try { localStorage.setItem('aqualuxe:theme', isDark ? 'dark' : 'light'); } catch (e) {}
    setPressed(isDark);
  });
}

// Progressive enhancement: lazy loading images
document.querySelectorAll('img[loading="lazy"]').forEach(img => {
  img.decoding = 'async';
});

// Quick View (progressive enhancement + a11y)
const qvModal = document.getElementById('qv-modal');
const qvBackdrop = document.getElementById('qv-backdrop');
const qvContent = document.getElementById('qv-content');
const qvClose = document.getElementById('qv-close');
const a11yLive = document.getElementById('a11y-live');
let qvLastFocus = null;

const isOpen = () => qvModal && !qvModal.hasAttribute('hidden');
const setLive = (msg) => { if (a11yLive) a11yLive.textContent = msg; };
const getFocusable = (root) => Array.from(root.querySelectorAll(
  'a[href], area[href], input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])'
)).filter(el => el.offsetParent !== null || el === document.activeElement);

function qvOpen(html) {
  if (!qvModal || !qvBackdrop || !qvContent) return;
  qvContent.innerHTML = html;
  qvContent.setAttribute('aria-busy', 'false');
  qvModal.removeAttribute('hidden');
  qvBackdrop.removeAttribute('hidden');
  qvModal.classList.add('open');
  qvBackdrop.classList.add('open');
  qvLastFocus = document.activeElement;
  // Focus the first focusable element inside the modal
  const focusables = getFocusable(qvModal);
  const first = focusables[0] || qvClose || qvModal;
  first.focus();
  setLive('Quick view opened');
}
function qvHide() {
  if (!qvModal || !qvBackdrop) return;
  qvModal.classList.remove('open');
  qvBackdrop.classList.remove('open');
  qvModal.setAttribute('hidden', '');
  qvBackdrop.setAttribute('hidden', '');
  setLive('Quick view closed');
  if (qvLastFocus && typeof qvLastFocus.focus === 'function') {
    qvLastFocus.focus();
  }
}
if (qvClose) qvClose.addEventListener('click', qvHide);
if (qvBackdrop) qvBackdrop.addEventListener('click', qvHide);
document.addEventListener('keydown', (e) => {
  if (!isOpen()) return;
  if (e.key === 'Escape') {
    e.stopPropagation();
    qvHide();
    return;
  }
  if (e.key === 'Tab') {
    const focusables = getFocusable(qvModal);
    if (focusables.length === 0) {
      e.preventDefault();
      (qvClose || qvModal).focus();
      return;
    }
    const first = focusables[0];
    const last = focusables[focusables.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }
});
document.addEventListener('click', (e) => {
  const t = e.target.closest('[data-qv-id]');
  if (!t) return;
  e.preventDefault();
  const id = t.getAttribute('data-qv-id');
  const url = (window.AQUALUXE?.restUrl || '').replace(/\/$/, '') + '/quickview/' + encodeURIComponent(id);
  // Announce loading state
  if (qvContent) qvContent.setAttribute('aria-busy', 'true');
  setLive('Loading product details…');
  fetch(url, { headers: { 'X-WP-Nonce': window.AQUALUXE?.nonce || '' }})
    .then(r => r.json())
    .then(({html}) => qvOpen(html || ''))
    .catch(() => {
      setLive('Failed to load product details');
      qvOpen('<div class="p-6">Error</div>');
    });
});

// Aquatic hero background (progressive enhancement)
(() => {
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
      id = requestAnimationFrame(animate);
    };
    let id = requestAnimationFrame(animate);
    // Subtle intro using GSAP
    gsap.fromTo(mesh.rotation, { x: -0.2 }, { x: 0.2, duration: 6, ease: 'sine.inOut', yoyo: true, repeat: -1 });
    // Respect reduced motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      gsap.globalTimeline.clear();
    }
    // Cleanup on page hide
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) cancelAnimationFrame(id);
      else id = requestAnimationFrame(animate);
    });
  } catch (e) {
    // Fail silently; canvas stays empty
  }
})();
