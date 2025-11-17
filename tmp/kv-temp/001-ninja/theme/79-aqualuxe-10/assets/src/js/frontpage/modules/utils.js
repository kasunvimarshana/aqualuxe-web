// Shared utilities
export const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
export const canWebGL = (() => {
  try {
    const c = document.createElement('canvas');
    return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl')));
  } catch (e) { return false; }
})();
