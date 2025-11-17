// Tiny inline WAV data URI for a short "pop" sound (generated sine burst)
// 8kHz, 16-bit PCM, ~60ms. Inlined to avoid external asset dependency.
const INLINE_SOUNDS = new Map([
  ['bubble-pop'],
  ['bubble-pop.wav'],
  ['bubble-pop.mp3']
].map(key => [key, 'data:audio/wav;base64,UklGRiQAAABXQVZFZm10IBAAAAABAAEAQB8AAIA+AAACABAAZGF0YQwAAACAgICAgICAh4eHh4eHgYGBgYGBgYGBgYGBgYGBgICAgICAhYWFhYWFgYGBgYGBgYGBgYGBgYGBgICAh4eHh4eHh4eHgYGBgYGBgYGBgYGBgYGBgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg']));

export class SFX {
  constructor(baseUrl){
    this.base = baseUrl.endsWith('/') ? baseUrl : baseUrl + '/';
    this.enabled = true;
    this.cache = new Map();
  }
  resolveSrc(name){
    // Allow known inline sounds by name
    if (INLINE_SOUNDS.has(name)) return INLINE_SOUNDS.get(name);
    // Also accept bare key without extension
    if (INLINE_SOUNDS.has(name.replace(/\.(wav|mp3)$/i, ''))) return INLINE_SOUNDS.get(name.replace(/\.(wav|mp3)$/i, ''));
    return this.base + name;
  }
  load(name){
    if (this.cache.has(name)) return this.cache.get(name);
    const a = new Audio(); a.preload = 'auto'; a.src = this.resolveSrc(name);
    a.addEventListener('error', () => { this.enabled = false; });
    this.cache.set(name, a); return a;
  }
  play(name, {volume=0.4}={}){
    if (!this.enabled) return;
    const a = this.load(name).cloneNode(true); a.volume=volume; a.play().catch(()=>{});
  }
  mute(){ this.enabled=false; }
  unmute(){ this.enabled=true; }
}
