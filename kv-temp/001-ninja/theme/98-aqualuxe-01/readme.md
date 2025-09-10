# AquaLuxe WordPress Theme

A production-ready, modular, dual-state (with/without WooCommerce) theme. Mobile-first, accessible, SEO-optimized, SOLID/DRY/KISS.

## Structure

- assets/
  - src/ (raw CSS/JS) → dist/ (built, cache-busted via mix-manifest.json)
- inc/
  - core/ (bootstrap, assets, customizer, security, seo, accessibility, cpt, modules)
- modules/ (feature modules: dark_mode, multilingual, woocommerce)
- templates/ (page templates and parts)
- woocommerce/ (overrides when needed)

## Build

- npm install
- npm run dev
- npm run build

## Theme setup

- Activate theme in WP Admin → Appearance → Themes.
- Assign menus and customize in Appearance → Customize.

## Notes

- No external CDNs. Tailwind via npm only.
- JS/CSS only enqueued from assets/dist using mix-manifest.json.
- Hooks and modules are isolated for easy enable/disable.
