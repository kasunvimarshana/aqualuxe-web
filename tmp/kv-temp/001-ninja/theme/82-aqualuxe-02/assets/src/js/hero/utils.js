export const TAU = Math.PI * 2;
export const clamp = (v, a=0, b=1) => Math.max(a, Math.min(b, v));
export const lerp = (a, b, t) => a + (b - a) * t;
export const rand = (a=0, b=1) => a + Math.random() * (b - a);
export const pick = (arr) => arr[(Math.random() * arr.length) | 0];

export function prefersReducedMotion(){
  return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

export function makeVisuallyHidden(){
  const span = document.createElement('span');
  span.style.cssText = 'position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;';
  span.setAttribute('aria-live','polite');
  span.setAttribute('aria-atomic','true');
  return span;
}
