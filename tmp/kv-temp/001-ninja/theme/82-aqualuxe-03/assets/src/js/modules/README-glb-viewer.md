GLB Viewer (AquaLuxe)

Usage
- Add a node in a template:
- <div class="alx-glb-viewer w-full aspect-[16/9]" data-glb="your-model.glb" data-poster="/path/poster.jpg"></div>

Features
- Orbit rotate/zoom/pan, auto-rotate, fit/reset
- Play/Pause, speed slider, lighting presets
- Click spin+bounce; double-click focus/zoom
- Respects prefers-reduced-motion; pauses when tab hidden
- Local-only GLBs: file must exist under assets/dist/models (copied from assets/src/models)

Notes
- Works in any page where theme.js runs; initialized on DOMContentLoaded via initGLBViewers().