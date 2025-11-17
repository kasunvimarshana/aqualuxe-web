# AquaLuxe WordPress Theme

Modular, WooCommerce-ready, mobile-first, accessible theme with core/modules architecture.

## Structure

- assets/src: raw JS/SCSS/images (compiled by Mix to assets/dist)
- inc/core: core classes (bootstrap, assets, customizer, CPTs, modules loader)
- inc/modules: feature modules (dark_mode, woocommerce, demo_importer)
- templates: partials and template files
- woocommerce: WooCommerce overrides (add as needed)

## Build

1. Open a terminal in `wp-content/themes/aqualuxe`.
2. Install: `npm install`
3. Dev build: `npm run dev` (or `npm run watch`)
4. Prod build: `npm run build`

## Enqueue policy

- Never enqueue raw files. Mix outputs to `assets/dist` and generates `mix-manifest.json`. The helper `asset_uri()` resolves versioned files, falling back to unversioned.

## Modules

- dark_mode: class-based dark theme with persistent preference
- woocommerce: conditional theme support and CSS responsibility
- demo_importer: admin UI to create demo pages and sample products, plus reset

## Security & Accessibility

- Sanitization/escaping via WP APIs
- Nonces on AJAX actions
- ARIA labels, keyboard-accessible nav, color-contrast friendly defaults

## Dual-state

- Works with or without WooCommerce. Shop sections display fallbacks if Woo is inactive.

