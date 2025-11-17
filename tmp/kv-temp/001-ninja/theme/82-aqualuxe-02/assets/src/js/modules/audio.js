// Simple audio handler for ambient sounds (muted by default, toggleable)
export class AmbientAudio {
  constructor(src, {volume=0.3}={}){
    this.src = src;
    this.audio = new Audio();
    this.audio.loop = true; this.audio.volume = volume; this.audio.preload = 'none';
    this.enabled = false;
  }
  enable(){
    if (!this.enabled) {
      if (!this.audio.src) this.audio.src = this.src;
      this.enabled = true;
      this.audio.play().catch(()=>{});
    }
  }
  disable(){ this.enabled = false; this.audio.pause(); }
  toggle(){ this.enabled? this.disable(): this.enable(); }
}
