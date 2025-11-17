import { AmbientAudio } from '../modules/audio';
import { startAquarium } from './aquarium';

(function initHero(){
  const container = document.getElementById('alx-hero-canvas');
  if(!container) return;

  // Resolve dist base from webpack public path or fallback
  // eslint-disable-next-line no-undef
  const DIST_BASE = (typeof __webpack_public_path__ !== 'undefined' && __webpack_public_path__)
    ? __webpack_public_path__
    : '/wp-content/themes/aqualuxe/assets/dist/';

  // Ambient audio (lazy user opt-in) and plankton overlay
  const globalToggle = (typeof window !== 'undefined' && window.AquaLuxeConfig && Number(window.AquaLuxeConfig.heroAudioEnabled) === 1);
  const dataToggle = container.getAttribute('data-ambient') !== 'off';
  const enableAmbient = globalToggle && dataToggle;
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
  btn.className = 'btn btn-ghost absolute top-4 right-4 z-50 pointer-events-auto';
    btn.textContent = 'Sound Off';
    btn.addEventListener('click', ()=>{ audio.toggle(); btn.textContent = audio.enabled? 'Sound On':'Sound Off'; });
    container.style.position = 'relative';
    container.appendChild(btn);
    }
  }
  // Choose mode: reef (photoreal-ish scaffolding) vs lightweight aquarium
  const mode = container.getAttribute('data-reef') === 'on' ? 'reef' : 'aq';
  if (mode === 'reef'){
    import(/* webpackChunkName: "hero-reef" */ './reef/index').then(m=>m.startReef(container)).catch(()=>startAquarium(container,{ fishCount: 22 }));
  } else {
    startAquarium(container, { fishCount: 22 });
  }
})();
