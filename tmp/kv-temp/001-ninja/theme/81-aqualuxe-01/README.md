# AquaLuxe Theme

Modular, dual-state (with/without WooCommerce) WordPress theme with Tailwind, Three.js hero, and demo importer.

## Key Features
- Core vs Modules architecture (toggle in `modules/modules.json`)
- Assets built via `webpack.mix.js` to `assets/dist` with cache-busting
- Tailwind CSS (no CDN), GSAP, Three.js, D3 bundled locally
- Customizer: color, font family, container width, dark mode default
- CPTs: Services, Events with taxonomies and meta
- WooCommerce graceful support (styles handled by theme)
- Demo Importer: pages, services, events, basic products

## Install
1. Activate the theme in WP Admin.
2. In theme folder, run Node 20+: `npm ci` then `npm run build`.
3. Visit AquaLuxe > Importer to seed demo content.

## Development
- `npm run dev` for unminified build, `npm run watch` for HMR-like rebuilds.

## Modules
Enable/disable in `modules/modules.json`. Each contains a `bootstrap.php` that registers hooks.

## Security & Performance
- Escaping and sanitization on inputs
- No remote CDNs
- Cache-busted assets from `mix-manifest.json`
