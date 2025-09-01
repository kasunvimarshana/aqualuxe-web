<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<section class="ax-hero-section relative overflow-hidden" aria-label="Immersive hero section with animated ocean visuals" role="region">
  <style>
    /* Critical hero styles for fast paint */
  .ax-hero-section{padding-bottom:3.25rem}
  @media(min-width:768px){.ax-hero-section{padding-bottom:4.5rem}}
    #ax-hero-canvas{display:block;width:100%;height:60vh;position:relative;z-index:0}
    @media(min-width:768px){#ax-hero-canvas{height:80vh}}
    /* Overlay sits above visuals; adds subtle halo for readability (especially light theme) */
  .ax-hero-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;text-align:center;padding:1.5rem;z-index:20;pointer-events:none}
    .ax-hero-overlay::after{content:"";position:absolute;left:50%;transform:translateX(-50%);top:12%;width:clamp(680px,65vw,1100px);height:clamp(240px,44vw,520px);z-index:-1;
      background:radial-gradient(ellipse at center, rgba(255,255,255,.50) 0%, rgba(255,255,255,.28) 38%, rgba(255,255,255,.10) 58%, rgba(255,255,255,0) 70%);
      -webkit-mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 60%, rgba(0,0,0,.7) 70%, rgba(0,0,0,0) 78%);
      mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 60%, rgba(0,0,0,.7) 70%, rgba(0,0,0,0) 78%);
      filter:blur(18px);pointer-events:none}
    @media(prefers-color-scheme: dark){
  .ax-hero-overlay::after{background:radial-gradient(ellipse at center, rgba(15,23,42,.55) 0%, rgba(15,23,42,.35) 40%, rgba(15,23,42,.10) 58%, rgba(15,23,42,0) 70%)}
    }
  .ax-hero-overlay .ax-hero-content{position:relative;pointer-events:auto;margin-inline:auto;max-width:80rem}
  /* Account for WP admin bar */
  @media(max-width:782px){body.admin-bar .ax-hero-overlay{padding-top:calc(3.5rem + 46px)}}
  @media(min-width:783px){body.admin-bar .ax-hero-overlay{padding-top:calc(3.5rem + 32px)}}
  .ax-hero-overlay h1,.ax-hero-overlay p{color:#0f172a}
  @media(prefers-color-scheme: dark){.ax-hero-overlay h1,.ax-hero-overlay p{color:#e5e7eb}}
  /* Layer order for decorative overlays */
  #ax-waves{z-index:10;transform:translateZ(0)}
  /* Subtle light rays overlay (very low opacity, GPU-friendly) */
  #ax-rays{position:absolute;inset:0;z-index:12;pointer-events:none;opacity:.08;mix-blend-mode:normal}
  @media(prefers-color-scheme: dark){#ax-rays{opacity:.12;mix-blend-mode:screen}}
  /* Rays are created using repeating conic gradients, masked to the top area */
  #ax-rays{background:
      radial-gradient(120% 80% at 50% -10%, rgba(255,255,255,.18), rgba(255,255,255,0) 60%) ,
      repeating-conic-gradient(from 200deg at 30% -10%, rgba(255,255,255,.22) 0deg, rgba(255,255,255,0) 6deg 22deg, rgba(255,255,255,.16) 28deg 34deg, rgba(255,255,255,0) 40deg 56deg);
    -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.85), rgba(0,0,0,.5) 35%, rgba(0,0,0,.18) 60%, rgba(0,0,0,0) 85%);
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.85), rgba(0,0,0,.5) 35%, rgba(0,0,0,.18) 60%, rgba(0,0,0,0) 85%);
    animation: ax-ray-rotate 24s linear infinite;
    transform: translateZ(0);
  }
  @keyframes ax-ray-rotate{0%{background-position:0 0}100%{background-position:120px 0}}
  @media(prefers-reduced-motion: reduce){#ax-rays{display:none}}
    /* Fade wave edges to avoid visible vertical cut lines on wide screens */
  #ax-waves{-webkit-mask-image:linear-gradient(90deg, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 8%, rgba(0,0,0,1) 92%, rgba(0,0,0,0) 100%);mask-image:linear-gradient(90deg, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 8%, rgba(0,0,0,1) 92%, rgba(0,0,0,0) 100%)}
    #ax-bubbles{z-index:15}
    /* Mobile adjustments: keep text clear of the 3D object, stack CTAs */
    @media(max-width:767px){
  .ax-hero-overlay{align-items:flex-start;padding-top:3rem}
  .ax-hero-overlay::after{width:min(92vw,720px);height:clamp(200px,52vw,440px);top:8%}
      .ax-hero-overlay h1{font-size:clamp(1.75rem,9vw,2.5rem)}
      .ax-hero-overlay p{font-size:clamp(1rem,3.3vw,1.125rem)}
      .ax-hero-ctas{flex-direction:column;gap:.75rem}
    }
  /* Audio toggle positioning */
  .ax-hero-audio{position:absolute;right:.75rem;bottom:calc(.75rem + env(safe-area-inset-bottom,0px));z-index:30;pointer-events:auto}
  .ax-hero-audio .ax-audio-ctrls{display:flex;align-items:center;gap:.5rem}
  .ax-hero-audio .ax-audio-ctrls{display:flex;align-items:center;gap:.5rem}
  .ax-hero-audio button{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem .75rem;border-radius:.5rem;border:1px solid rgba(255,255,255,.6);background:rgba(2,132,199,.15);backdrop-filter:saturate(1.2) blur(2px);color:#0f172a}
  @media(prefers-color-scheme: dark){.ax-hero-audio button{border-color:rgba(203,213,225,.5);color:#e5e7eb;background:rgba(15,23,42,.25)}}
  .ax-hero-audio button:focus-visible{outline:2px solid #0ea5e9;outline-offset:2px}
  .ax-hero-audio .ax-ic{width:16px;height:16px;display:inline-block}
  .ax-hero-audio button[data-muted="true"] .text-sm{opacity:.6}
  .ax-hero-audio .ax-ic[hidden]{display:none!important}
    .ax-hero-audio input[type=range]{appearance:none;-webkit-appearance:none;-moz-appearance:none;width:96px;height:6px;border-radius:999px;background:linear-gradient(90deg, rgba(14,165,233,0.9), rgba(14,165,233,0.5));outline:none;border:1px solid rgba(255,255,255,.5)}
  #ax-waves{z-index:10;transform:translateZ(0)}
  .ax-hero-audio input[type=range]::-moz-range-thumb{width:14px;height:14px;border-radius:50%;background:#fff;border:1px solid rgba(15,23,42,.25)}
    /* Auto-hide on desktop when idle */
    @media(hover:hover){
      .ax-hero-audio .ax-audio-ctrls{transition:opacity .18s ease, transform .2s ease}
      .ax-hero-audio.ax-idle .ax-audio-ctrls{opacity:0;pointer-events:none;transform:translateY(6px)}
    }
    /* Tighten on small screens */
    @media(max-width:767px){
      .ax-hero-audio{right:.5rem;bottom:calc(.5rem + env(safe-area-inset-bottom,0px))}
      .ax-hero-audio input[type=range]{width:72px;height:4px}
      .ax-hero-audio button{padding:.4rem .6rem}
    }
  </style>
  <canvas id="ax-hero-canvas" class="block w-full h-[60vh] md:h-[80vh]"></canvas>
  <!-- Subtle light rays overlay (decorative) -->
  <div id="ax-rays" aria-hidden="true"></div>
  <!-- Layered SVG waves (animated in JS; hidden from AT) -->
  <svg id="ax-waves" class="absolute inset-x-0 bottom-0 w-full h-40 md:h-56 pointer-events-none" viewBox="0 0 1440 320" preserveAspectRatio="none" aria-hidden="true" focusable="false">
    <defs>
      <linearGradient id="ax-grad" x1="0" y1="0" x2="0" y2="1">
  .ax-hero-audio .ax-ic{width:16px;height:16px;display:inline-block}
  .ax-hero-audio .ax-ic[hidden]{display:none!important}
        <stop offset="100%" stop-color="#0369a1" stop-opacity="0.0"/>
      </linearGradient>
    </defs>
    <path id="ax-wave-1" fill="url(#ax-grad)" d="M0,160L80,165.3C160,171,320,181,480,197.3C640,213,800,235,960,234.7C1120,235,1280,213,1360,202.7L1440,192L1440,320L0,320Z"></path>
    <path id="ax-wave-2" fill="url(#ax-grad)" opacity="0.6" d="M0,192L80,186.7C160,181,320,171,480,154.7C640,139,800,117,960,128C1120,139,1280,181,1360,202.7L1440,224L1440,320L0,320Z"></path>
    <path id="ax-wave-3" fill="url(#ax-grad)" opacity="0.4" d="M0,224L80,213.3C160,203,320,181,480,170.7C640,160,800,160,960,165.3C1120,171,1280,181,1360,186.7L1440,192L1440,320L0,320Z"></path>
  </svg>
  <!-- D3 bubbles overlay (decorative) -->
  <svg id="ax-bubbles" class="absolute inset-0 w-full h-full pointer-events-none" aria-hidden="true" focusable="false"></svg>
  <noscript>
    <div class="w-full h-[60vh] md:h-[80vh] bg-gradient-to-b from-cyan-700 to-cyan-900 flex items-center justify-center text-white">
      <div class="text-center p-6">
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">Bringing elegance to aquatic life – globally</h1>
        <p class="mt-4 text-lg opacity-90 max-w-2xl mx-auto">Premium ornamental fish, plants, bespoke aquariums, and professional services.</p>
      </div>
    </div>
  </noscript>
  <div class="absolute inset-0 pointer-events-none" aria-hidden="true"></div>
  <div class="ax-hero-overlay">
    <div class="ax-hero-content">
      <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">Bringing elegance to aquatic life – globally</h1>
      <p class="mt-4 text-lg opacity-90 max-w-2xl mx-auto">Premium ornamental fish, plants, bespoke aquariums, and professional services.</p>
      <div class="mt-6 flex justify-center gap-4 ax-hero-ctas">
        <a href="<?php echo esc_url( (aqualuxe_is_woocommerce_active() && function_exists('wc_get_page_id')) ? get_permalink(call_user_func('wc_get_page_id','shop')) : home_url('/blog/') ); ?>" class="bg-cyan-600 text-white px-6 py-3 rounded shadow hover:bg-cyan-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500" data-ax-event="cta_shop" role="button" aria-label="Shop Now">Shop Now</a>
        <a href="<?php echo esc_url( home_url('/services/') ); ?>" class="border border-cyan-600 text-cyan-600 px-6 py-3 rounded hover:bg-cyan-50 dark:hover:bg-slate-800 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500" data-ax-event="cta_service" role="button" aria-label="Book a Service">Book a Service</a>
      </div>
    </div>
  </div>
  <!-- Audio toggle (opt-in subtle ambience) -->
  <div class="ax-hero-audio" aria-hidden="false">
    <div class="ax-audio-ctrls">
      <button id="ax-audio-toggle" type="button" aria-pressed="false" aria-label="Enable ambient sound">
          <span class="ax-ic ax-ic-on" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true"><path d="M3 10v4a1 1 0 0 0 1 1h3l3.293 3.293A1 1 0 0 0 12 18v-12a1 1 0 0 0-1.707-.707L7 8H4a1 1 0 0 0-1 1z"/><path d="M16.5 6.5a1 1 0 0 1 1.414 0 7 7 0 0 1 0 9.9A1 1 0 0 1 16.5 15c2.343-2.343 2.343-6.157 0-8.5a1 1 0 0 1 0-1.414z"/><path d="M14.5 8.5a1 1 0 0 1 1.414 0 4 4 0 0 1 0 5.657A1 1 0 1 1 14.5 13c1.172-1.172 1.172-3.071 0-4.243a1 1 0 0 1 0-1.414z"/></svg>
          </span>
          <span class="ax-ic ax-ic-off" aria-hidden="true" hidden>
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true">
              <path d="M3 10v4a1 1 0 0 0 1 1h3l3.293 3.293A1 1 0 0 0 12 18v-12a1 1 0 0 0-1.707-.707L7 8H4a1 1 0 0 0-1 1z"/>
              <path d="M16.243 7.757a1 1 0 0 1 1.414 0L19 9.1l1.343-1.343a1 1 0 1 1 1.414 1.414L20.414 10.5l1.343 1.343a1 1 0 0 1-1.414 1.414L19 11.914l-1.343 1.343a1 1 0 1 1-1.414-1.414L17.586 10.5l-1.343-1.343a1 1 0 0 1 0-1.4z"/>
            </svg>
          </span>
        <span class="text-sm">Sound</span>
      </button>
      <input id="ax-audio-volume" type="range" min="0" max="100" value="60" aria-label="Ambient sound volume" />
      <button id="ax-power-toggle" type="button" aria-pressed="false" aria-label="Enable low power mode" title="Low power">
        <span class="ax-ic" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true">
            <path d="M11 2a1 1 0 0 1 1 1v7h-2V3a1 1 0 0 1 1-1z"/>
            <path d="M6.343 6.343a8 8 0 1 0 11.314 0l-1.414 1.414a6 6 0 1 1-8.486 0L6.343 6.343z"/>
          </svg>
        </span>
        <span class="text-sm">Low power</span>
      </button>
    </div>
  </div>
</section>
<section class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-12 grid gap-8">
  <h2 class="text-2xl font-bold">Featured</h2>
  <div id="ax-featured" class="grid grid-cols-2 md:grid-cols-4 gap-6"></div>
</section>
<?php get_footer(); ?>
