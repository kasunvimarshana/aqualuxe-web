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
    #ax-waves{z-index:10}
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
  .ax-hero-audio{position:absolute;right:.75rem;bottom:.75rem;z-index:30;pointer-events:auto}
  .ax-hero-audio .ax-audio-ctrls{display:flex;align-items:center;gap:.5rem}
  .ax-hero-audio button{display:inline-flex;align-items:center;gap:.5rem;padding:.5rem .75rem;border-radius:.5rem;border:1px solid rgba(255,255,255,.6);background:rgba(2,132,199,.15);backdrop-filter:saturate(1.2) blur(2px);color:#0f172a}
  @media(prefers-color-scheme: dark){.ax-hero-audio button{border-color:rgba(203,213,225,.5);color:#e5e7eb;background:rgba(15,23,42,.25)}}
  .ax-hero-audio button:focus-visible{outline:2px solid #0ea5e9;outline-offset:2px}
  .ax-hero-audio input[type=range]{appearance:none;-webkit-appearance:none;-moz-appearance:none;width:96px;height:6px;border-radius:999px;background:linear-gradient(90deg, rgba(14,165,233,0.9), rgba(14,165,233,0.5));outline:none;border:1px solid rgba(255,255,255,.5)}
  .ax-hero-audio input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:14px;height:14px;border-radius:50%;background:#fff;border:1px solid rgba(15,23,42,.25)}
  .ax-hero-audio input[type=range]::-moz-range-thumb{width:14px;height:14px;border-radius:50%;background:#fff;border:1px solid rgba(15,23,42,.25)}
  </style>
  <canvas id="ax-hero-canvas" class="block w-full h-[60vh] md:h-[80vh]"></canvas>
  <!-- Layered SVG waves (animated in JS; hidden from AT) -->
  <svg id="ax-waves" class="absolute inset-x-0 bottom-0 w-full h-40 md:h-56 pointer-events-none" viewBox="0 0 1440 320" aria-hidden="true" focusable="false">
    <defs>
      <linearGradient id="ax-grad" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%" stop-color="#0ea5e9" stop-opacity="0.6"/>
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
        <span class="ax-audio-ic" aria-hidden="true">🔈</span>
        <span class="text-sm">Sound</span>
      </button>
      <input id="ax-audio-volume" type="range" min="0" max="100" value="60" aria-label="Ambient sound volume" />
    </div>
  </div>
</section>
<section class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-12 grid gap-8">
  <h2 class="text-2xl font-bold">Featured</h2>
  <div id="ax-featured" class="grid grid-cols-2 md:grid-cols-4 gap-6"></div>
</section>
<?php get_footer(); ?>
