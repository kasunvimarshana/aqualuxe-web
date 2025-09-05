# AquaLuxe Theme

Modular, Woo/No-Woo dual-state WordPress theme with Tailwind + Mix pipeline.

## Install
1. In `wp-content/themes/aqualuxe`:
   - npm install
   - npm run build
2. Activate “AquaLuxe” in WP Admin.

## Develop
- npm run dev (or watch)

## Structure
- inc/core: setup, assets, security, CPTs, taxonomies, REST, shortcodes, WooCompat
- modules: feature toggles (dark_mode, multilingual, importer, wishlist, quick_view, advanced_filters)
- assets/src: JS/CSS sources bundled to assets/dist with hashed filenames
- templates & root PHP files

## Toggle Modules
Use filter `aqualuxe_enabled_modules` to enable/disable modules.

## Notes
- No external CDNs
- Safe fallbacks without WooCommerce
- Accessibility-first, SEO tags via templates and semantic HTML
