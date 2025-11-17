# AquaLuxe Theme

A modular, dual-state WordPress theme for luxury aquatic e‑commerce and services. Built with Tailwind + Laravel Mix, no external CDNs, cache-busted assets, and SOLID/DRY/KISS architecture.

## Quick Start

1) In WordPress, select theme folder `wp-content/themes/aqualuxe`.
2) Install Node 18+ and run:
   - npm install
   - npm run build
3) Activate the theme in WP Admin > Appearance > Themes.
4) Optional: Tools > AquaLuxe > Demo Importer to create demo pages.

### Optional: Toggle Modules

Create `module-config.php` in the theme root and return an array of module slugs to load, e.g.:

```php
<?php
return ['dark-mode','multilingual','services','events','woocommerce','wishlist','quick-view','filtering','importer'];
```

If the file returns `null` the default set is loaded.

### Lightweight Analytics (no external services)

The theme dispatches a `window` CustomEvent `alx:analytics` for key interactions (e.g., `[data-cta]` clicks). Listen to it in custom scripts or plugins:

```js
window.addEventListener('alx:analytics', (e)=>{
   // e.detail: { event, payload, ts }
   console.log('Analytics', e.detail);
});
```

## Structure
- core/, inc/: PHP classes (bootstrap, admin, customizer)
- modules/: self-contained features (dark-mode, multilingual, importer, woocommerce bridge)
- templates/: page templates including `templates/pages/home.php`
- assets/src: JS/CSS modules, compiled to assets/dist via Mix

## Security & Performance
- Only dist assets enqueued via mix-manifest
- Reduced motion, ARIA roles, schema.org markup
- Lazy load heavy hero Three.js via IntersectionObserver

## Build
- npm run dev (unminified)
- npm run build (production)

Build notes:
- A postbuild script ensures `assets/dist/mix-manifest.json` includes entries for the main theme assets even on environments where hashing/manifest writing is flaky.

### Development fallbacks
If `assets/dist/mix-manifest.json` is missing, the theme will automatically fall back to unbuilt dev assets:
- CSS: `assets/src/scss/theme.css` and `assets/src/scss/screen.css`
- JS: `assets/src/js/theme.js`

When fallbacks are used, an admin notice appears in WP Admin. Run a production build when ready:

```cmd
cd /d c:\repo\kasun\aqualuxe-web\wp-content\themes\aqualuxe
npm install
npm run build
```

### Ambient audio (optional)
Ambient audio is fully opt-in. Select a local MP3 in Customizer and enable the toggle:

- Appearance > Customize > AquaLuxe Hero
   - Enable ambient audio (hero)
   - Ambient audio file (MP3)

If no file is selected or the toggle is off, no audio is requested.

Recommended sources for high-quality, free assets (verify license is CC0/Public Domain or permissive):
- OpenGameArt (https://opengameart.org/)
- Kenney.nl (https://kenney.nl/assets)
- Wikimedia Commons (https://commons.wikimedia.org/)
- Freesound (filter by CC0) (https://freesound.org/)

Files in `assets/src/audio` are copied to `assets/dist/audio` by the build if you prefer committing demo assets, but it’s not required.

Disable hero ambient audio by setting:

```html
<div id="alx-hero-canvas" data-ambient="off"></div>
```

Or disable globally via Customizer: Appearance > Customize > AquaLuxe Hero > uncheck “Enable ambient audio (hero)”.

## Mini Cart Drawer
- Slide-in WooCommerce mini cart with:
   - Live count via fragments
   - Drawer items (`woocommerce_mini_cart()`)
   - Sticky subtotal + CTAs (View cart, Checkout)
   - Free-shipping progress bar
- Accessibility: dialog semantics, focus trap, ESC to close, aria-live announcement on add-to-cart.
- Behavior: intercepts only primary, unmodified clicks on Cart link; modified clicks navigate.
- Auto-opens after AJAX add-to-cart.

Configure free shipping threshold (default 100.0):
```php
add_filter('aqualuxe_free_shipping_threshold', function(){ return 150.0; });
```

Or set it in WP Admin > Appearance > Customize > Commerce > “Free shipping threshold (subtotal)”. This value is used when auto-detection from shipping zones is disabled or no zone threshold is available.

Additional Customizer toggles
- Enable/disable the mini cart drawer: Appearance > Customize > Commerce > “Enable mini cart drawer”.
- Prefer auto-detection from shipping zones: Appearance > Customize > Commerce > “Auto-detect free shipping threshold from shipping zones”.

Rebuild assets after changes:
```cmd
cd /d c:\repo\kasun\aqualuxe-web\wp-content\themes\aqualuxe
npm run build
```

## License
GPL-2.0-or-later
